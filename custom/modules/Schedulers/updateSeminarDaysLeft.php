<?php
/*
*
* 3/19/2015 - CPC
* created this function as its own page due to issues encountered such as not getting executed;
*
*
*/

var_dump($job_strings);
$job_strings[]='runProcessManager';
var_dump($job_strings);
function runProcessManager() {
	require_once('modules/PM_ProcessManager/ProcessManagerEngine.php');
	$processManager = new ProcessManagerEngine();
	$processManager->processManagerMain();
	return true;
} 

    	global $sugar_config;
	$GLOBALS['log']->info('----->Scheduler fired updateSeminarDaysLeft()');
	// Date: 	3/2/2011
	// Author: 	Cedric P. Castillo
	// Interval: 	every 24 hours / midnight
	// Note:	This DB update is called by scheduler

	// The following SQL update will update the "days left to seminar" field in the LEADS_CSTM table		
	//	const CUSTOM_FIELD1 = "bin_type_id";		// Custom field 	
	//	const CUSTOM_FIELD2 = "bin_type_id";		// Custom field					
	//	const CUSTOM_TABLE = "leads_cstm"; 		// Custom table where the custom fields are located							
	
		//$db =  DBManagerFactory::getInstance();					
		$db = DBManagerFactory::getInstance();															
		// Update the record that was just saved								
		// $update_query = "UPDATE ".self::CUSTOM_TABLE." SET ".self::CUSTOM_FIELD1." = '$new_code'								
		// 				 WHERE id = '{$bean->id}' AND (".self::CUSTOM_FIELD1." = '' or ".self::CUSTOM_FIELD1." IS NULL)";				
		/*
		$update_query = "UPDATE leads_cstm
				SET `seminar_days_left_c` = DATEDIFF(STR_TO_DATE( `seminar_date_c` , '%m/%d/%Y' ),CURDATE())
				WHERE 	STR_TO_DATE( `seminar_date_c` , '%m/%d/%Y' ) > CURDATE() and 
					STR_TO_DATE( `seminar_date_c` , '%m/%d/%Y' )<>'0000-00-00' and
				      	(`seminar_date_c` is not null or `seminar_date_c` <>'')";
													 				 
		$db->query($update_query, true);
        */
    
    /* customization by JOED@ASI 20110509
     * updating process to use the $bean->save() inorder to trigger the Save Hook
     * for this module, created by the Process Manager
     */
    global $sugar_config;
    ini_set("max_execution_time", "3600");
    $log_level = $sugar_config['logger']['level'];
    $sugar_config['logger']['level'] = "fatal";
    
    require_once('modules/Leads/Lead.php');
    
    
    $query_leads = "
        SELECT
            l.id AS id, DATEDIFF(STR_TO_DATE(lc.seminar_date_c, '%m/%d/%Y'), CURDATE()) AS daysleft
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
            DATEDIFF(STR_TO_DATE(lc.seminar_date_c, '%m/%d/%Y'), CURDATE()) IN ('1','2','3','4','5')
    ";
    
    $result_leads = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_leads)) {
        $lead = new Lead();
        $lead->retrieve($row['id']);
        $lead->seminar_days_left_c = $row['daysleft'];
        //$lead->seminar_days_left_c = '5';
        $lead->save();
        unset($lead);
    }
    
    $sugar_config['logger']['level'] = $log_level;
    
?>