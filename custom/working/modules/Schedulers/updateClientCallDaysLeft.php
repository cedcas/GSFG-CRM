<?php


    
    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Scheduler fired updateClientCallDaysLeft()');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Leads/Lead.php');
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
    
    $query_leads = "
       SELECT
            a.id AS id,
            a.date_entered AS date_entered
        FROM
            accounts a
        
        WHERE
            (a.date_entered <> '' OR a.date_entered != NULL) AND
            a.deleted = 0
        ORDER BY
            date_format(date(a.date_entered), '%m-%d') 

        ";
    
    $result_accounts = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_accounts)) {
        $account = new Account();
        $account->retrieve($row['id']);
        
        $date_entered = $row['date_entered'];
            
        //$daysleft = round((strtotime(date("Y-m-d", strtotime($date_entered))) - strtotime(date("Y-m-d"))) / (60*60*24),0);
		$days_since_entered = round((strtotime(date("Y-m-d"))-strtotime(date("Y-m-d", strtotime($date_entered)))) / (60*60*24),0);
        
            
        //while ($daysleft < 0) {
        //    $date_entered = date("Y-m-d", strtotime('+1 year', strtotime($date_entered)));
        //    $daysleft = round((strtotime(date("Y-m-d", strtotime($date_entered))) - strtotime(date("Y-m-d"))) / (60*60*24),0);
		//
		//
		//}
        
        $account->days_left_to_call = $days_since_entered;
        $account->save();
        unset($lead);
			echo "<br><pre>";
            print_r($row['id'].": ".$date_entered." | ".$days_since_entered);
            echo "<br></pre>";
    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;


?>