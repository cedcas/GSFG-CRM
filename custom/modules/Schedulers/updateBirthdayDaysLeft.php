<?php


    
    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Scheduler fired updateBirthdayDaysLeft()');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Leads/Lead.php');
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
    
    $query_leads = "
        SELECT
            l.id AS id,
            l.birthdate AS bday
        FROM
            leads l
        WHERE
            (l.birthdate <> '' OR l.birthdate != NULL) AND
            l.deleted = 0
        ORDER BY
            date_format(date(l.birthdate), '%m-%d') ASC
        ";
    
    $result_leads = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_leads)) {
        $lead = new Lead();
        $lead->retrieve($row['id']);
        
        $bday = $row['bday'];
            
        $daysleft = round((strtotime(date("Y-m-d", strtotime($bday))) - strtotime(date("Y-m-d"))) / (60*60*24),0);
        
            
        while ($daysleft < 0) {
            $bday = date("Y-m-d", strtotime('+1 year', strtotime($bday)));
            $daysleft = round((strtotime(date("Y-m-d", strtotime($bday))) - strtotime(date("Y-m-d"))) / (60*60*24),0);

            //echo "<br><pre>";
            //print_r($daysleft);
            //echo "<br></pre>";
        }
        
        $lead->lead_days_left_to_birthday = $daysleft;
        $lead->save();
        unset($lead);
    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;


?>