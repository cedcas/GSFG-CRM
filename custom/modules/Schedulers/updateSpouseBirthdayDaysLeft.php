<?php

/* 6/29/13 - CPC
 * BT# 49 - This file generates the number of days left before the lead's spouse's birthday;
 *
 * 4/8/2015 - CPC
 * BT# 56 - Updated with SQL Update and updated with the new field 'spouse_birthday';
 *
 */

    
    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Scheduler fired updateSpouseBirthdayDaysLeft()');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Leads/Lead.php');
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
    
    $query_leads = "
        SELECT
            l.id AS id,
            l.spouse_birthday AS bday
        FROM
            leads l
        WHERE
            
            (l.spouse_birthday <> '' OR l.spouse_birthday != NULL) AND
            l.deleted = 0
        ORDER BY
            date_format(date(l.spouse_birthday), '%m-%d') ASC
        ";
    
    $result_leads = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_leads)) {
    
    	$lead_id = $row['id'];
    	$spouse_bday = $row['bday'];
    	   
        $daysleft = round((strtotime(date("Y-m-d", strtotime($spouse_bday))) - strtotime(date("Y-m-d"))) / (60*60*24),0);

        while ($daysleft < 0) {
            $spouse_bday = date("Y-m-d", strtotime('+1 year', strtotime($spouse_bday)));
            $daysleft = round((strtotime(date("Y-m-d", strtotime($spouse_bday))) - strtotime(date("Y-m-d"))) / (60*60*24),0);
        }

	$update_leads = "
        UPDATE
            leads l
       	SET l.lead_spouse_days_left_to_birthday = '".$daysleft."'
        WHERE
            l.id = '".$lead_id."'
        ";        
        
        $db->query($update_leads, true);
        
	//echo "<br><pre>";
        //print_r($lead_id.": ".$bday.": ".$daysleft.": ".$update_leads);
        //echo "<br></pre>";           
         
    }
    //end while loop
    $GLOBALS['log']->info('----->Scheduler ends updateSpouseBirthdayDaysLeft()');
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;


?>