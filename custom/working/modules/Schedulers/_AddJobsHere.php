<?php
/*
* 8/18/2012 - CPC
* BT# 46 - Update of Days Left for Birthday field in Leads (job 106 updateBirthdayDaysLeft)
*
* 6/28/2013 - CPC
* Created job strings for 105, 106 & 107 in order to properly schedule
* all those jobs;
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



$job_strings['100'] = 'updateSeminarDaysLeft';
$job_strings['101'] = 'updateAnniversaryDaysLeft';
$job_strings['102'] = 'getMeetingIds';
$job_strings['103'] = 'getMeetingAttendees';
$job_strings['104'] = 'mergeAttendees';
$job_strings['105'] = 'autoAssignOfUser';
$job_strings['106'] = 'updateClientCallDaysLeft';
$job_strings['107'] = 'updateBirthdayDaysLeft';
$job_strings['108'] = 'updateSpouseBirthdayDaysLeft';


function updateSeminarDaysLeft() {
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
    ini_set("max_execution_time", "1800");
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
            (lc.seminar_date_c IS NOT NULL OR lc.seminar_date_c <> '')
    ";
    
    $result_leads = $db->query($query_leads, true);
    
    while ($row = $db->fetchByAssoc($result_leads)) {
        $lead = new Lead();
        $lead->retrieve($row['id']);
        $lead->seminar_days_left_c = $row['daysleft'];
        $lead->save();
        unset($lead);
    }
    
    $sugar_config['logger']['level'] = $log_level;
    
    return true;
}


function updateAnniversaryDaysLeft() {
    
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
            
        $daysleft = ROUND((strtotime(date("Y-m-d", strtotime($anniv))) - strtotime(date("Y-m-d"))) / (60*60*24));
        //print_r("Anniv: ".$anniv." DaysLeft: ".$daysleft);
        
        while ($daysleft < 0) {
            $anniv = date("Y-m-d", strtotime('+1 year', strtotime($anniv)));
            $daysleft = ROUND((strtotime(date("Y-m-d", strtotime($anniv))) - strtotime(date("Y-m-d"))) / (60*60*24));
            
        }
        
        $account->account_days_left_to_anniv = $daysleft;
        $account->save();
        unset($account);
    }
    //end while loop
    
    $sugar_config['logger']['level'] = $log_level;
    
    return true;
}

function updateAnnivDateTest() {
    if (file_exists('custom/modules/Schedulers/updateAnnivDateTest.php')) {
        require('custom/modules/Schedulers/updateAnnivDateTest.php');
    }
    
    return true;
}

function getMeetingIds() {
    if (file_exists('custom/modules/Schedulers/getMeetingIds.php')) {
        require('custom/modules/Schedulers/getMeetingIds.php');
    }
    
    return true;
}

function getMeetingAttendees() {
    if (file_exists('custom/modules/Schedulers/getMeetingAttendees.php')) {
        require('custom/modules/Schedulers/getMeetingAttendees.php');
    }
    
    return true;
}

function mergeAttendees() {
    if (file_exists('custom/modules/Schedulers/mergeAttendees.php')) {
        require('custom/modules/Schedulers/mergeAttendees.php');
    }
    
    return true;
}

function updateBirthdayDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateBirthdayDaysLeft.php')) {
        require('custom/modules/Schedulers/updateBirthdayDaysLeft.php');
    }
    
    return true;
}

function autoAssignOfUser() {
    if (file_exists('custom/modules/Schedulers/autoAssignOfUser.php')) {
        require('custom/modules/Schedulers/autoAssignOfUser.php');
    }
    
    return true;
}

function updateClientCallDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateClientCallDaysLeft.php')) {
        require('custom/modules/Schedulers/updateClientCallDaysLeft.php');
    }
    
    return true;
}

function updateSpouseBirthdayDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateSpouseBirthdayDaysLeft.php')) {
        require('custom/modules/Schedulers/updateSpouseBirthdayDaysLeft.php');
    }
    
    return true;
}
?>