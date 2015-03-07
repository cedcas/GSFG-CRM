<?php
/*********************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed 
 * by KINAMU Business Solutions AG. All rights ar (c) 2010 by KINAMU Business
 * Solutions AG.
 *
 * This Version of the KReporter is licensed software and may only be used in 
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of KINAMU Business Solutions AG
 * 
 * You can contact KINAMU Business Solutions AG at Am Concordepark 2/F12
 * A-2320 Schwechat or via email at office@kinamu.com
 * 
 ********************************************************************************/
require_once('modules/KReports/KReport.php');
require_once('modules/KReports/CronParser.php');
require_once('include/utils.php');

class KReportSchedulerLog{
	static function getLastRunData($jobid){
		global $db;
		
		$logList = $db->query('SELECT MAX(timestamp) as timestamp FROM kreportscheduleslog WHERE job_id =  \'' . $jobid . '\' GROUP BY job_id');
		if ($db->getRowCount($logList) == 1)	
		{
			$lastLogentry = $db->fetchByAssoc($logList);
			return $lastLogentry['timestamp'];
		}
		else 
		{
			return 0;
		}
	}
	
	static function writeNewEntry($jobid){
		global $db;
		$db->query('INSERT INTO kreportscheduleslog SET id=\'' . create_guid() . '\', timestamp=\'' . time() . '\', job_id=\'' . $jobid . '\', status = \'0\'' );
		
	}
}

class KReportScheduler{
	function KReportScheduler(){
		
	}
	
	function executeJob($jobData){
		global $current_user;
		global $db;

		if(!is_array($jobData))
		{
			// TODO: we do not have a full array and thus nee to load from the database
			
		}		
		
		$thisReport = new KReport();
		$thisReport->retrieve($jobData['report_id']);
		
		$current_user_mem = $current_user->id;
		$current_user->retrieve($jobData['user_id']);
		
		switch($jobData['action'])
		{
			case '1': 
				// Excel
				break; 
			case '2': 
				// PDF
				break;
			case '3': 
				// take snapshot
				$thisReport->takeSnapshot();
				break;
		}
		
		$current_user->retrieve($current_user_mem);
	}
	
	function runScheduledReports(){
		global $db;
		$cron = new CronParser();
		$schedulerLines = $db->query('SELECT * FROM kreportschedules WHERE deleted = \'0\'');

		while($schedulerLine = $db->fetchByAssoc($schedulerLines))
		{
			$lastRun = KReportSchedulerLog::getLastRunData($schedulerLine['id']);
			
			$cron->calcLastRan($schedulerLine['minutes'] . ' ' . $schedulerLine['hour'] . ' ' . $schedulerLine['dayofmonth'] . ' ' . $schedulerLine['month'] . ' ' . $schedulerLine['dayofweek']);
			
			if($cron->getLastRanUnix() > $lastRun)
			{
				//kickstart the job
				$this->executeJob($schedulerLine);
				// write the log entry
				KReportSchedulerLog::writeNewEntry($schedulerLine['id']);
			}
		}
	}
}



?>