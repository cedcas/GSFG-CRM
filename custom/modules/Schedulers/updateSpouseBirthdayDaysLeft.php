<?php

/* 6/29/13 - CPC
 * BT# 49 - This file generates the number of days left before the lead's spouse's birthday;
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
            lc.spouse_date_of_birth_c AS bday
        FROM
            leads l, leads_cstm lc
        WHERE
            l.id=lc.id_c AND
            (lc.spouse_date_of_birth_c <> '' OR lc.spouse_date_of_birth_c != NULL) AND
            l.deleted = 0
        ORDER BY
            date_format(date(lc.spouse_date_of_birth_c), '%m-%d') ASC
        ";
    
    $result_leads = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_leads)) {
        $lead = new Lead();
        $lead->retrieve($row['id']);
        $lead_id = $lead->id;
        $bday = $lead->spouse_date_of_birth_c;     
        $daysleft = round((strtotime(date("Y-m-d", strtotime($bday))) - strtotime(date("Y-m-d"))) / (60*60*24),0);
        
            
        while ($daysleft < 0) {
            $bday = date("Y-m-d", strtotime('+1 year', strtotime($bday)));
            $daysleft = round((strtotime(date("Y-m-d", strtotime($bday))) - strtotime(date("Y-m-d"))) / (60*60*24),0);
        }

        //echo "<br><pre>";
        //print_r($lead_id.": ".$bday.": ".$daysleft);
        //echo "<br></pre>";
            
        $lead->lead_spouse_days_left_to_birthday= $daysleft;
        $lead->save();
        unset($lead);
    }
    //end while loop
    $GLOBALS['log']->info('----->Scheduler ends updateSpouseBirthdayDaysLeft()');
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;


?>