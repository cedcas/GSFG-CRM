<?php

    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Scheduler fired updateAnniversaryDaysLeftTest()');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Accounts/Account.php');
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
/*
    $query_accounts = "
        SELECT
            a.id AS id,
            ac.accounts_anniversary_date_c AS anniv
        FROM
            accounts a
        LEFT JOIN
            accounts_cstm ac
        ON
            a.id = ac.id_c
        WHERE
            (ac.accounts_anniversary_date_c <> '' OR 
            ac.accounts_anniversary_date_c != NULL) AND
            a.deleted = 0 AND
            a.status = 'Client' AND
            ac.accounts_anniversary_date_c >'2000-01-01' AND
            a.account_days_left_to_anniv>=1 AND
	    a.account_days_left_to_anniv<15
        ORDER BY
            date_format(date(ac.accounts_anniversary_date_c), '%m-%d') ASC
        ";
 */   
     $query_accounts = "
        SELECT
            a.id AS id,
            ac.accounts_anniversary_date_c AS anniv
        FROM
            accounts a
        LEFT JOIN
            accounts_cstm ac
        ON
            a.id = ac.id_c
        WHERE
            (ac.accounts_anniversary_date_c <> '' OR ac.accounts_anniversary_date_c != NULL) AND
            a.deleted = 0 AND a.status='Client'
        ORDER BY
            date_format(date(ac.accounts_anniversary_date_c), '%m-%d') ASC
        ";
        
    $result_accounts = $db->query($query_accounts, true);
    
    while ($row = $db->fetchByAssoc($result_accounts)) {
        $account = new Account();
        $account->retrieve($row['id']);
        
        $account_id = $row['id'];
        $anniv = $row['anniv'];
            
        $daysleft = ROUND((strtotime(date("Y-m-d", strtotime($anniv))) - strtotime(date("Y-m-d"))) / (60*60*24));
        print_r("Out Anniv: ".$anniv." DaysLeft: ".$daysleft. " ID: " .$account_id. "<BR>");
        
        while ($daysleft < 0) {
            $anniv = date("Y-m-d", strtotime('+1 year', strtotime($anniv)));
            $daysleft = ROUND((strtotime(date("Y-m-d", strtotime($anniv))) - strtotime(date("Y-m-d"))) / (60*60*24));
            //$daysleft = (strtotime(date("Y-m-d", strtotime($anniv))) - strtotime(date("Y-m-d"))) / (60*60*24);
            print_r("In Anniv: ".$anniv." DaysLeft: ".$daysleft."<BR>");
        }
        
        $account->account_days_left_to_anniv = $daysleft;
        $account->save();
        unset($account);
    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    return true;
?>