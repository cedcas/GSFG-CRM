<?php
/*************************************
Script to create Anniversary Date PDF and Task Manually
Autor: Cedric P. Castillo
Date: 4/1/2015
*************************************/


    set_time_limit(0);
    ini_set("max_execution_time", "3600");
    ini_set("memory_limit","1024M");
    
    $GLOBALS['log']->info('----->Script to create Anniversary Date PDF and Task Manually');
    
    global $sugar_config;
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Accounts/Account.php');
    require_once('custom/modules/Accounts/AnniversaryDatePDF.php');
    require_once('modules/PM_ProcessManager/customScripts/anniversaryTask.php');
    
    $db = DBManagerFactory::getInstance();
    
    $current_year = date('Y');
    
    $query_accounts = "
        SELECT
            a.id AS id,
            a.account_days_left_to_anniv AS annivDays
        FROM
            accounts a
        WHERE
            a.account_days_left_to_anniv='30' AND
            a.deleted = 0 AND a.status='Client'
        ";
    
    $result_accounts = $db->query($query_accounts, true);
    
    while ($row = $db->fetchByAssoc($result_accounts)) {
        
        $annivDays = $row['annivDays'];
        $account_id = $row['id'];
        
        // Create the Anniversary PDF
        $adpdf = new AnniversaryDatePDF($account_id);
        $adpdf->generatePDF();
        
        // Create the Task
        $newtask = new anniversaryTask($account_id);

            
    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    //return true;




?>