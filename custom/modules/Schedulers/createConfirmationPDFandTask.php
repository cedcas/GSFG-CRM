<?php
/*************************************
Script to create Seminar Confirmation Date PDF and Task Manually
Autor: Cedric P. Castillo
Date: 4/2/2015
*************************************/


    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Script to create Seminar Confirmation PDF and Task Manually');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Leads/Lead.php');
    require_once('custom/modules/Leads/SeminarConfirmationPDF.php');
    require_once('modules/PM_ProcessManager/customScripts/confirmationTask.php');
    
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
    
    $query_leads = "
		        SELECT
		            DISTINCT l.id AS id
		        FROM
		            leads l
		        LEFT JOIN
		            leads_cstm lc
		        ON
		            l.id = lc.id_c
		        WHERE
		            l.deleted = 0 AND
		            STR_TO_DATE(lc.seminar_date_c, '%m/%d/%Y') > CURDATE() AND
		            STR_TO_DATE(lc.seminar_date_c, '%m/%d/%Y') <> '0000-00-00' AND
		            (lc.seminar_date_c IS NOT NULL OR lc.seminar_date_c <> '') AND 
		            DATEDIFF(STR_TO_DATE(lc.seminar_date_c, '%m/%d/%Y'), CURDATE()) IN ('5') AND 
		            l.lead_source='Seminar'
        ";
    
    $result_leads = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_leads)) {
        
        $lead_id = $row['id'];
        
        // Create the Seminar Confirmation PDF
        $scpdf = new SeminarConfirmationPDF($lead_id);
        $scpdf->generatePDF();
        
        // Create the Seminar Confirmation Task
        $newtask = new confirmationTask($lead_id);

            
    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;




?>