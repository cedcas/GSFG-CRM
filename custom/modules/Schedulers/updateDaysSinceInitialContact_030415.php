<?php
/*
3/1/2015 - 	Cedric P. Castillo
		Modified to update the lead using SQL updae instead of through the model (not working);  This updates
		all LEADS that has a value within its INITIAL CONTACT field (date field);  This updates the #/number
		of days since the initial contact date;
*/


    
    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Scheduler fired updateDaysSinceInitialContact()');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Leads/Lead.php');
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
    
    $query_leads = "
       SELECT
            l.id AS id,
            l.initial_contact AS initial_contact
        FROM
            leads l
        
        WHERE
            (l.initial_contact <> '' OR l.initial_contact != NULL) AND
            l.deleted = 0
        ORDER BY
            date_format(date(l.initial_contact), '%m-%d') 

        ";
    
    $result_leads = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_leads)) {
        $lead = new Lead();
        $lead->retrieve($row['id']);
        $initial_contact = $row['initial_contact'];
	$days_since_entered = round((strtotime(date("Y-m-d"))-strtotime(date("Y-m-d", strtotime($initial_contact)))) / (60*60*24),0);
	
	// commented out the following to make the logic hook do the 
        // calculation for number of days since initial contact
        //$lead->lead_days_since_initial_contact = $days_since_entered;
        //$lead->save();
        
        //3/1/2015 - saving through model was not working, doing the update through SQL
        $update_leads = "
        		UPDATE leads
        		SET lead_days_since_initial_contact = '".$days_since_entered."'
        		WHERE id = '".$row['id']."';";
        
        print_r($update_leads);
        $db->query($update_leads, true);
        
        // only save when values aren't the same to prevent double dip
       	//if($days_since_entered!=$lead->lead_days_since_initial_contact){
       	//$lead->lead_days_since_initial_contact = $days_since_entered;
        //	$lead->save();
        	
        //	}
        
        //$lead->save();
	echo "<br><pre>";
        print_r($row['id'].": ".$initial_contact." | ".$days_since_entered. " | ".$lead->lead_days_since_initial_contact);
        echo "<br></pre>";
        $lead->save();
        unset($lead);

    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;


?>