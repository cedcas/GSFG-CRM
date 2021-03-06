<?php
/*
*
* 3/19/2015 - CPC
* created this function as its own page due to issues encountered such as not getting executed;
*
* 3/31/2015 - CPC
* Modified to update the accounts using SQL update instead of through the model (not working or stopping); 
*
*/

/*
var_dump($job_strings);
$job_strings[]='runProcessManager';
var_dump($job_strings);
function runProcessManager() {
	require_once('modules/PM_ProcessManager/ProcessManagerEngine.php');
	$processManager = new ProcessManagerEngine();
	$processManager->processManagerMain();
	return true;
} 
*/
    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Scheduler fired updateAnniversaryDaysLeft()');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Accounts/Account.php');
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
    
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
        
        $anniv = $row['anniv'];
        $account_id = $row['id'];
            
        $daysleft = ROUND((strtotime(date("Y-m-d", strtotime($anniv))) - strtotime(date("Y-m-d"))) / (60*60*24));
        //print_r("Anniv: ".$anniv." DaysLeft: ".$daysleft);
        
        while ($daysleft < 0) {
            $anniv = date("Y-m-d", strtotime('+1 year', strtotime($anniv)));
            $daysleft = ROUND((strtotime(date("Y-m-d", strtotime($anniv))) - strtotime(date("Y-m-d"))) / (60*60*24));
            
        }
        
        $query_update = "
        	UPDATE accounts
        	SET 	account_days_left_to_anniv = ".$daysleft.",
        		date_modified = NOW()
        	WHERE id = '".$account_id."'
        ";
        $db->query($query_update, true);
        
        //$account->account_days_left_to_anniv = $daysleft;
        //$account->save();
        unset($account);
    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;




?>