<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/******************************************************************************
OpensourceCRM End User License Agreement

INSTALLING OR USING THE OpensourceCRM's SOFTWARE THAT YOU HAVE SELECTED TO 
PURCHASE IN THE ORDERING PROCESS (THE "SOFTWARE"), YOU ARE AGREEING ON BEHALF OF
THE ENTITY LICENSING THE SOFTWARE ("COMPANY") THAT COMPANY WILL BE BOUND BY AND 
IS BECOMING A PARTY TO THIS END USER LICENSE AGREEMENT ("AGREEMENT") AND THAT 
YOU HAVE THE AUTHORITY TO BIND COMPANY.

IF COMPANY DOES NOT AGREE TO ALL OF THE TERMS OF THIS AGREEMENT, DO NOT SELECT 
THE "ACCEPT" BOX AND DO NOT INSTALL THE SOFTWARE. THE SOFTWARE IS PROTECTED BY 
COPYRIGHT LAWS AND INTERNATIONAL COPYRIGHT TREATIES, AS WELL AS OTHER 
INTELLECTUAL PROPERTY LAWS AND TREATIES. THE SOFTWARE IS LICENSED, NOT SOLD.

    *The COMPANY may not copy, deliver, distribute the SOFTWARE without written
     permit from OpensourceCRM.
    *The COMPANY may not reverse engineer, decompile, or disassemble the 
    SOFTWARE, except and only to the extent that such activity is expressly 
    permitted by applicable law notwithstanding this limitation.
    *The COMPANY may not sell, rent, or lease resell, or otherwise transfer for
     value, the SOFTWARE.
    *Termination. Without prejudice to any other rights, OpensourceCRM may 
    terminate this Agreement if the COMPANY fail to comply with the terms and 
    conditions of this Agreement. In such event, the COMPANY must destroy all 
    copies of the SOFTWARE and all of its component parts.
    *OpensourceCRM will give the COMPANY notice and 30 days to correct above 
    before the contract will be terminated.

The SOFTWARE is protected by copyright and other intellectual property laws and 
treaties. OpensourceCRM owns the title, copyright, and other intellectual 
property rights in the SOFTWARE.
*****************************************************************************/
/*********************************************************************************
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights Reserved.
********************************************************************************/
require_once 'modules/Calendar2/Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');
require_once("modules/Calls/Call.php");
require_once("modules/Meetings/Meeting.php");
require_once('include/database/PearDatabase.php');
include_once('include/database/DBManagerFactory.php');
include_once ("include/TimeDate.php");
        
	function processPageLoad($username,$password,$startDate,$endDate,$userid,$gcal_sync_mod,$gcal_sync_opt,$gcal_prioriy) 
	{
		global $_SESSION, $_GET;
		$client =getClientLoginHttpClient($username,$password);
		return fetchCalendarByDateRange($client, $startDate,$endDate,$userid,$gcal_sync_mod,$gcal_sync_opt,$gcal_prioriy) ;
	}

	function getClientLoginHttpClient($user, $pass) 
	{
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
		return $client;
	}

	function fetchCalendarByDateRange($client, $startDate,$endDate,$userid,$gcal_sync_mod,$gcal_sync_opt,$gcal_prioriy) 
	{
		global $sugar_config;
		
		$gdataCal = new Zend_Gdata_Calendar($client);
		$query = $gdataCal->newEventQuery();
		$query->setUser('default');
		$query->setVisibility('private');
		$query->setProjection('full');
		$query->setOrderby('starttime');
		$query->setStartMin($startDate);
		$query->setStartMax($endDate);
		$eventFeed = $gdataCal->getCalendarEventFeed($query);		
		$td = new TimeDate();
		$CurrentUserId=$userid;

		$userCacheDir = "{$sugar_config['cache_dir']}modules/Calendar2/{$userid}";
		$path = clean_path($userCacheDir);
		if(!file_exists($userCacheDir))
		{
			mkdir_recursive($path);
		}
		$destination = clean_path("{$userCacheDir}/{$userid}");
				
		// write google data		
		$fp = fopen($destination,"w");
		fwrite($fp,serialize($eventFeed));
		fclose($fp);
		return $eventFeed->totalResults->text;
	}	
	
	function ProcessGoogleData($page,$maxpage,$gcal_sync_mod,$gcal_sync_opt,$gcal_prioriy) 
	{
		global $current_user,$sugar_config;
		
		$userid = $current_user->id;
		$ArrRDay = array(
			"1"=>"SU",
			"2"=>"MO",
			"3"=>"TU",
			"4"=>"WE",
			"5"=>"TH",
			"6"=>"FR",
			"7"=>"SA",
		);
		
		$d_start_time = $current_user->getPreference('d_start_time');
		$d_end_time = $current_user->getPreference('d_end_time');
		$gcal_time_slot = $current_user->getPreference('gcal_time_slot');
		
		if(empty($d_start_time))
			$d_start_time = "09:00";
		if(empty($d_end_time))
			$d_end_time = "18:00";

		$tarr = explode(":",$d_start_time);
		$d_start_hour = $tarr[0];
		$d_start_min = $tarr[1];
		$tarr = explode(":",$d_end_time);
		$d_end_hour = $tarr[0];
		$d_end_min = $tarr[1];

		$hour_start = $d_start_hour;
		$minute_start = $d_start_min;
		$hour_end = $d_end_hour;
		$minute_end = $d_end_min;


		$day_duration_hours = $hour_end - $hour_start;
		if($minute_end < $minute_start){
			$day_duration_hours--;
			$day_duration_minutes = $minute_start - $minute_end;
		}else
			$day_duration_minutes = $minute_end - $minute_start;		
		
		$userCacheDir = "{$sugar_config['cache_dir']}modules/Calendar2/{$userid}";		
		$path = clean_path($userCacheDir);
		if(!file_exists($userCacheDir))
		{
			mkdir_recursive($path);
		}
		$destination = clean_path("{$userCacheDir}/{$userid}");

		$eventFeedstr = file_get_contents($destination);
		$eventFeed = unserialize($eventFeedstr);
		
		$td = new TimeDate();
		$CurrentUserId=$userid;
		
		$record_counter=0;
		$process_id = array();

		foreach ($eventFeed as $event) 
		{
			if($record_counter>=($page-1)*$maxpage and $record_counter<$page*$maxpage)
			{
				$record_counter++;
			}
			else
			{
				$record_counter++;
				continue;
			}

			// parse recurence
			if(isset($event->recurrence->text)) 
			{
			if($event->recurrence->text!="")
			{
				$parse_rule = array();
				$rec_arr = explode("\n",$event->recurrence->text);

				$dtstart_arr = explode(":",$rec_arr[0]);
				$starttimestr = $dtstart_arr[1];
				$parse_rule["STARTDATE"]=$starttimestr;

				$dtend_arr = explode(":",$rec_arr[1]);
				$endtimestr = $dtend_arr[1];
				$parse_rule["ENDDATE"]=$endtimestr;
				
				$rrule_arr = explode(";",str_replace("RRULE:","",$rec_arr[2]));				
				for($k=0;$k<count($rrule_arr);$k++)
				{
					$temp = explode("=",$rrule_arr[$k]);
					$parse_rule[$temp[0]]=$temp[1];
				}
			}	
			}	

			if($event->extendedProperty[0]->name=="event_type")
			{
				$tablename = $event->extendedProperty[0]->value;
			}
			else
			{
				$tablename="";
			}
			if($event->extendedProperty[1]->name=="sugar_id")
			{
				$sugar_id = $event->extendedProperty[1]->value;
			}
			else
			{
				$sugar_id="";
			}
			if($event->extendedProperty[2]->name=="sugar_date")
			{
				$sugar_date = $event->extendedProperty[2]->value;
			}
			else
			{
				$sugar_date="";
			}
			if($tablename=="calls" or $gcal_sync_mod=="call")
			{			
				$obj = new Call();
				$jn = "cal2_call_id_c";
			}
			elseif($tablename=="meetings" or $gcal_sync_mod=="meeting")
			{
				$obj = new Meeting();
				$jn = "cal2_meeting_id_c";
			}

			$do_event=0;
			$new_event=0;

			if($sugar_id!="")
			{
				$obj->retrieve($sugar_id);
				if($obj->id!="")
				{
					$process_id[$obj->table_name][] = "'".$obj->id."'";
					if($event->updated->text!=$obj->old_updated_c and $gcal_prioriy==1)
					{
						$do_event=1;
					}
					elseif($event->updated->text!=$obj->old_updated_c and $gcal_prioriy==2)
					{
						$whole_day=0;
						// whole day clause
						if($obj->duration_hours==$day_duration_hours and $obj->duration_minutes==$day_duration_minutes)
						{						
							$whole_day=1;
						}	
												
						if($whole_day==0)
							$obj->preprocess_fields_on_save();
						
						require_once "modules/Calendar2/gcal_functions.php";
						$gc = new GoogleCalls($obj,'before_save',array('check_notify'=>''),$whole_day);
						$sql = "update ".$obj->table_name." set google_response_c=\"".$obj->google_response_c."\",old_id_c='".$obj->old_id_c."',old_published_c='".$obj->old_published_c."',old_updated_c='".$obj->old_updated_c."',old_link_alt_c='".$obj->old_link_alt_c."',old_link_self_c='".$obj->old_link_self_c."',old_link_edit_c='".$obj->old_link_edit_c."',old_email_c='".$obj->old_email_c."',g_published_c='".$obj->g_published_c."',g_updated_c='".$obj->g_updated_c."' where id='".$obj->id."'";
						$obj->db->query($sql);
						continue;
					}
				}
				elseif($gcal_sync_opt==1 or $gcal_sync_opt==3)
				{
					$new_event=1;
					$do_event=1;
				}
			}
			elseif($gcal_sync_opt==1 or $gcal_sync_opt==3)
			{
				$do_event=1;
				$new_event=1;
			}
			
			if($new_event==1)
			{
				$obj->old_id_c=$event->id->text;
				$obj->old_published_c=$event->published->text;
				$obj->old_link_alt_c=$event->link[0]->href;
				$obj->old_link_self_c=$event->link[1]->href;
				$obj->old_link_edit_c=$event->link[2]->href;
				$obj->old_email_c=$event->who[0]->email;
				$obj->g_published_c=$event->published->text;
				$obj->g_updated_c=$event->updated->text;
			}

			if($do_event==1)
			{
				$obj->name=$event->title->text;
				$obj->description=$event->content->text;
				$obj->location=$event->where[0]->valueString;
				$whole_day=0;
				foreach ($event->when as $when) 
				{				
					$obj->date_start=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$when->startTime),0,19)));
					$obj->date_end=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$when->endTime),0,19)));
					$obj->duration_hours=date("H",strtotime($obj->date_end))-date("H",strtotime($obj->date_start));
					$obj->duration_minutes=date("i",strtotime($obj->date_end))-date("i",strtotime($obj->date_start));
					
					// whole day clause
					if($obj->duration_hours==0 and $obj->duration_minutes==0)
					{							
						$obj->duration_hours = $day_duration_hours;
						$obj->duration_minutes = $day_duration_minutes;

						$obj->date_start=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$when->startTime),0,19)." ".$d_start_time));
						$obj->date_end=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$when->endTime),0,19)." ".$d_end_time));
						$whole_day=1;
					}

					$obj->assigned_user_id=$CurrentUserId;
					if(is_array($when->reminders))
					{
						$minutes = $when->reminders[0]->minutes?$when->reminders[0]->minutes:0;
						$hours = $when->reminders[0]->hours?$when->reminders[0]->hours:0;
						$days = $when->reminders[0]->days?$when->reminders[0]->days:0;
						$total_minutes = $minutes + ($hours*60) + ($days*24*60) ;
						$obj->reminder_time= $total_minutes*60;
						$obj->reminder_checked=1;
					}
					else
					{
						$obj->reminder_time=0;
						$obj->reminder_checked=1;
					}
				}	
				$obj->old_updated_c=$event->updated->text;

				// process recurence
				if(isset($event->recurrence->text))
				{
				if($event->recurrence->text!="")
				{
					$obj->date_start=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$parse_rule["STARTDATE"]),0,19)));
					$obj->date_end=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$parse_rule["ENDDATE"]),0,19)));
					$obj->cal2_repeat_type_c = ucfirst(strtolower($parse_rule['FREQ']));					
					$obj->cal2_repeat_interval_c = $parse_rule['INTERVAL']?$parse_rule['INTERVAL']:0;
					$obj->cal2_repeat_end_date_c =  date($td->get_date_format(),strtotime(substr(str_replace("T"," ",$parse_rule['UNTIL']),0,19)));
					$temp_rday = explode(",",$parse_rule['BYDAY']);
					
					// whole day clause
					if($whole_day==1)
					{							
						$obj->duration_hours = $day_duration_hours;
						$obj->duration_minutes = $day_duration_minutes;

						$obj->date_start=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$parse_rule["STARTDATE"]),0,19)." ".$d_start_time));	
						$obj->date_end=date($td->get_date_time_format(),strtotime(substr(str_replace("T"," ",$parse_rule["ENDDATE"]),0,19)." ".$d_end_time));
					}					
					
					$rdaykey="";
					foreach($temp_rday as $rday)
					{
						$rdaykey = array_keys($ArrRDay,$rday);
						$obj->cal2_repeat_days_c .= $rdaykey[0];
					}
				}
				}	

				$obj->save();
				
				$process_id[$obj->table_name][] = "'".$obj->id."'";
				
				$type = "";
				if($obj->object_name == 'Call'){
					$type = 'call';
				} elseif($obj->object_name == 'Meeting'){
					$type = 'meeting';
				}
				$userInvitees[] = $current_user->id;
				$obj->load_relationship('users');	
				
				$userlist = array();
				if ($type == "meeting") {
					$userlist = $obj->get_meeting_users();
				} elseif ($type == "call") {
					$userlist = $obj->get_call_users();
				}
				for ($i=0; $i<count($userlist); $i++) {
					if (!in_array(($userlist[$i]->id), $userInvitees)) {
						$deleteUsers[$userlist[$i]->id] = $userlist[$i]->id;
						$GLOBALS['log']->debug("Going to delete users deleteUsers=".$userlist[$i]->id);
						$obj->users->delete($obj->id, $userlist[$i]->id);
						$GLOBALS['log']->debug("Going to call vCal:cache on deletion user_id=".$userlist[$i]->id);
						vCal2::cache_sugar_vcal($userlist[$i]);
					} else {
						$acceptStatusUsers[$userlist[$i]->id] = $userlist[$i]->accept_status;
						$GLOBALS['log']->debug("Going to delete users acceptStatus=".$acceptStatusUsers[$userlist[$i]->id]);
					}
				}
				$obj->users_arr = $userInvitees;
				$existing_users = array();
				
				foreach($obj->users_arr as $user_id){
					if(empty($user_id))	continue;
					if(!isset($existing_users[$user_id]) && !isset($deleteUsers[$user_id])) {
						if(!isset($acceptStatusUsers[$user_id]) && $user_id != $current_user->id) {
							require_once('modules/Users/User.php');
							$temp_user = new User();
							$temp_user->retrieve($user_id);
							if($temp_user->getPreference('auto_accept','global',$temp_user) == 'true') {
								$obj->users->add($user_id, array('accept_status'=>'accept'));
							} else {
								$obj->users->add($user_id, array('accept_status'=>'accept'));
							}					
						} else {
							if ($user_id == $current_user->id) {
								$obj->users->add($user_id, array('accept_status'=>'accept'));
							} else {
								$obj->users->add($user_id, array('accept_status'=>$acceptStatusUsers[$user_id]));
							}

						}
					}
					//Updates each user's vCal
					require_once('modules/Users/User.php');
					$GLOBALS['log']->debug("Going to call vCal:cache on deletion user_id=".$user_id);
					$usr = new User();
					$usr->retrieve($user_id);
					//vCal2::cache_sugar_vcal($usr);
				}				

				if($new_event==1)
				{
					if($whole_day==1)
						$obj->retrieve($obj->id);
						
					require_once "modules/Calendar2/gcal_functions.php";
					$gc = new GoogleCalls($obj,'update_ext',array('check_notify'=>''),$whole_day);
					$sql = "update ".$obj->table_name." set google_response_c=\"".$obj->google_response_c."\",old_id_c='".$obj->old_id_c."',old_published_c='".$obj->old_published_c."',old_updated_c='".$obj->old_updated_c."',old_link_alt_c='".$obj->old_link_alt_c."',old_link_self_c='".$obj->old_link_self_c."',old_link_edit_c='".$obj->old_link_edit_c."',old_email_c='".$obj->old_email_c."',g_published_c='".$obj->g_published_c."',g_updated_c='".$obj->g_updated_c."' where id='".$obj->id."'";
					$obj->db->query($sql);
				}
				
				if(isset($event->recurrence->text))
				{
				if($event->recurrence->text!="")
				{
					$obj->retrieve($obj->id);
					$arr_rec = createRecurrence($obj, $jn);
					foreach($arr_rec as $recur_id_arr)
					{
						if($obj->table_name == 'meetings')
							$rec_obj = new Meeting();
						else
							$rec_obj = new Call();

						$rec_obj->retrieve($recur_id_arr['record']);
						
						if($whole_day==0)
							$rec_obj->preprocess_fields_on_save();
							
						// process google on save only when under google settings it is configured as 2 way and 1 way (from calendar2 -> google)
						$gc = new GoogleCalls($rec_obj,'before_save',array('check_notify'=>''),$whole_day);
						$sql = "update ".$rec_obj->table_name." set google_response_c=\"".$rec_obj->google_response_c."\",old_id_c='".$rec_obj->old_id_c."',old_published_c='".$rec_obj->old_published_c."',old_updated_c='".$rec_obj->old_updated_c."',old_link_alt_c='".$rec_obj->old_link_alt_c."',old_link_self_c='".$rec_obj->old_link_self_c."',old_link_edit_c='".$rec_obj->old_link_edit_c."',old_email_c='".$rec_obj->old_email_c."',g_published_c='".$rec_obj->g_published_c."',g_updated_c='".$rec_obj->g_updated_c."' where id='".$rec_obj->id."'";						
						$rec_obj->db->query($sql);
					}					
				}
			}
		}
		}
		
		if(count($process_id)>0)
		{
			$chk_startDate=date("Y-m-d",mktime("0","0","0",date("n")-$gcal_time_slot));
			$chk_endDate=date("Y-m-d",mktime("0","0","0",date("n")+$gcal_time_slot));
			global $db;

			if(isset($process_id['calls']))
			{
			if(count($process_id['calls'])>0)
			{					
				$delids = implode(",",$process_id['calls']);
				$sql = "select id from calls where DATE(date_entered) > DATE('".$chk_startDate."') and DATE(date_entered) < DATE('".$chk_endDate."') and deleted=0 and id NOT IN (".$delids.") and old_id_c!=''";
				$res = $db->query($sql);
				while($row = $db->fetchByAssoc($res))
				{
					$obj_del = new Call();
					$obj_del->retrieve($row['id']);

					$table_name = $obj_del->table_name;
					$jn = "cal2_call_id_c";

					$query = "SELECT * from ".$table_name." t WHERE t.deleted = 0 AND t.".$jn." = '".$obj_del->id."'";
					$result = $obj_del->db->query($query, true, "Error retrieveing all of recurred records in AjaxRemove: ");
					while($row = $obj_del->db->fetchByAssoc($result)) {
						$recurred_mtg = new Call();
						$recurred_mtg->retrieve($row['id']);			
						$recurred_mtg->mark_deleted($row['id']);
					}
					//delete the primary record						
					$obj_del->mark_deleted($obj_del->id);						
				}
			}
			}
			elseif(isset($process_id['meetings']))
			{
				if(count($process_id['meetings'])>0)
			{					
					$delids = implode(",",$process_id['meetings']);
				$sql = "select id from meetings where DATE(date_entered) > DATE('".$chk_startDate."') and DATE(date_entered) < DATE('".$chk_endDate."') and deleted=0 and id NOT IN (".$delids.") and old_id_c!=''";
				$res = $db->query($sql);
				while($row = $db->fetchByAssoc($res))
				{
					$obj_del = new Meeting();
					$obj_del->retrieve($row['id']);

					$table_name = $obj_del->table_name;
					$jn = "cal2_meeting_id_c";

					$query = "SELECT * from ".$table_name." t WHERE t.deleted = 0 AND t.".$jn." = '".$obj_del->id."'";
					$result = $obj_del->db->query($query, true, "Error retrieveing all of recurred records in AjaxRemove: ");
					while($row = $obj_del->db->fetchByAssoc($result)) {
						$recurred_mtg = new Meeting();
						$recurred_mtg->retrieve($row['id']);			
						$recurred_mtg->mark_deleted($row['id']);
					}
					//delete the primary record
					$obj_del->mark_deleted($obj_del->id);
				}					
			}
			}	
		}		
	}
	
	function createRecurrence(&$bean, $jn)
	{

		require_once("modules/Calendar2/functions.php");
		$ret_arr = array();
		$repeat_days = "";
		$rtype = "";
			
		$repeat_days = $bean->cal2_repeat_days_c;		
		$rtype = $bean->cal2_repeat_type_c;
		
		$interval = $bean->cal2_repeat_interval_c;		

		$timezone = $GLOBALS['timedate']->getUserTimeZone();
		global $timedate;
		
		$start_unix = to_timestamp_from_uf(date("m/d/Y H:i",strtotime($bean->date_start)));
		$end_unix = to_timestamp_from_uf(date("m/d/Y H:i",strtotime($bean->cal2_repeat_end_date_c)));
		$end_unix = $end_unix + 60*60*24 - 1;


		$start_day = date("w",$start_unix - date('Z',$start_unix)) + 1;
		$start_dayM = date("j",$start_unix - date('Z',$start_unix));
		$GLOBALS['log']->debug('view.ajaxsave.php start_day='.$start_day);
		$GLOBALS['log']->debug('view.ajaxsave.php start_dayM='.$start_dayM);
		$GLOBALS['log']->debug('view.ajaxsave.php interval='.$interval);
		$GLOBALS['log']->debug('view.ajaxsave.php rtype='.$rtype);
		$GLOBALS['log']->debug('view.ajaxsave.php repeat_days='.$repeat_days);

		$qu = " 
			UPDATE	".$bean->table_name." t
			SET t.deleted = 1 	 
			WHERE t.".$jn." = '".addslashes($bean->id)."'
		";	

		$bean->db->query($qu);
		$ft = true;

		if(!empty($rtype) && (!isset($bean->cal2_repeat_end_date_c) || !empty($bean->cal2_repeat_end_date_c)) ){

			if(empty($interval) || $interval == 0)
				$interval = 1;

			$cur_date = $start_unix;
			$GLOBALS['log']->debug('view.ajaxsave.php cur_date='.$cur_date);
			$GLOBALS['log']->debug('view.ajaxsave.php cur_date formatted='.date("F j, Y, g:i a", $cur_date));
			
			$i = 0;
			if($rtype == 'Weekly' || $rtype == 'Monthly (day)')
				$i--;

			while($cur_date <= $end_unix){

				$i++;
				
				if($rtype == 'Daily')
					$step = 60*60*24;
				if($rtype == 'Monthly (date)'){
					$step = 60*60*24 * date('t',$cur_date - date('Z',$cur_date));
					$this_month = date('n',$cur_date - date('Z',$cur_date));
					$next_month = date('n',$cur_date + $step - date('Z',$cur_date));	
					if($next_month - $this_month == 2 || $next_month - $this_month == -10){	
						$day_number = intval(date('d',$cur_date + $step - date('Z',$cur_date))) + 1;							
						$step += 60*60*24 * date('t',$cur_date + $step - $day_number*24*3600 - date('Z',$cur_date));
						$i++;
					}
				}
				if($rtype == 'Yearly'){
					$step = 60*60*24 * 365;
					if( date('d',$cur_date + $step - date('Z',$cur_date)) !=  date('d',$cur_date - date('Z',$cur_date)) )
						$step += 60*60*24;
				}
				
				if($rtype == 'Weekly'){
					$step = 60*60*24*7;
					//sunday of the week
					$week_start_day = $start_unix - ($start_day -1)*60*60*24 + $step * $i;
					$GLOBALS['log']->debug('view.ajaxsave.php weekly: week_start_day='.date("F j, Y, g:i a", $week_start_day));

					//if($i % $interval == 0)
					if($i > 0 && $i % $interval == 0)
						//for($d = $start_day; $d < 7; $d++)
						for($d = 1; $d < 8; $d++)
							//if(strpos($repeat_days,(string)($d + 1)) !== false){
							if(strpos($repeat_days,(string)($d)) !== false){
								//if($cur_date + $d*60*60*24 > $end_unix)
								if($week_start_day + ($d-1)*60*60*24 > $end_unix)
									break;
								$GLOBALS['log']->debug('view.ajaxsave.php for createClone week_start_date='.date("F j, Y, g:i a", $week_start_day));
								//$ret_arr[] = $this->create_clone($bean,$cur_date + ($d)*60*60*24,$jn);
								$ret_arr[] = create_clone($bean, $week_start_day + ($d - 1)*60*60*24, $jn);
							}
					//$start_day = 0;
				}

				if($rtype == 'Monthly (day)'){
					$step = 60*60*24 * date('t',$cur_date - date('Z',$cur_date));

					if($i % $interval == 0 && $start_dayM <= $start_day)
						for($d = $start_day; $d < 7; $d++){
							$dd = date('w',$cur_date + $d*60*60*24 - date('Z',$cur_date));
							if(strpos($repeat_days,(string)($dd + 1)) !== false){
								//$ST_curr = getDST($cur_date + $d*60*60*24);		
								//$cur_date += ($ST_prev - $ST_curr)*3600;
								//$ST_prev = getDST($cur_date + $d*60*60*24);
								if($cur_date + $d*60*60*24 > $end_unix)
									break;
								$ret_arr[] = create_clone($bean,$cur_date + $d*60*60*24,$jn);
							}
						}
					$start_day = 0;
					$start_dayM = 0;
				}


				$cur_date += $step;

				//$ST_curr = getDST($cur_date);		
				//$cur_date += ($ST_prev - $ST_curr)*3600;
				//$ST_prev = getDST($cur_date);
				
				if($i % $interval != 0)				
					continue;

				if($cur_date > $end_unix)
					break;
					
				if($rtype == 'Weekly' || $rtype == 'Monthly (day)')
					continue;		

				$ret_arr[] = create_clone($bean,$cur_date,$jn);

			}
		}
		
	 return $ret_arr;
	
	}	
	
	function create_clone(&$bean,$cur_date,$jn){
		$GLOBALS['log']->debug('view.ajaxsave.php create_clone: cur_date formatted='.date("F j, Y, g:i a", $cur_date));

		$obj = clone_rec($bean);	
		$obj->date_start = timestamp_to_user_formated2($cur_date);
		$obj->date_end = timestamp_to_user_formated2($cur_date);	
		$GLOBALS['log']->debug('view.ajaxsave.php create_clone: date_start='.$obj->date_start);
		$obj->$jn = $bean->id;
		$obj->save();		
		$obj_id = $obj->id;
		
		$obj->retrieve($obj_id);
				
		
		$date_unix = $cur_date;
		return 	array(
				'record' => $obj_id,
				'start' => $date_unix,
			);
	}

	function clone_rec($bean) {
		$obj = new $bean->object_name();
		$obj->name = $bean->name;
		$obj->duration_hours = $bean->duration_hours;
		$obj->duration_minutes = $bean->duration_minutes;
		$obj->reminder_time = $bean->reminder_time;
		if($obj->object_name == 'Call')
			$obj->direction = $bean->direction; 
		$obj->status = $bean->status;
		$obj->location = $bean->location;
		$obj->assigned_user_id = $bean->assigned_user_id;
		$obj->parent_type = $bean->parent_type;
		$obj->parent_id = $bean->parent_id;
		$obj->description = $bean->description;
		$obj->cal2_category_c = $bean->cal2_category_c;
		$obj->cal2_options_c = $bean->cal2_options_c;
		$obj->cal2_whole_day_c = $bean->cal2_whole_day_c;
		
		if (isPro()) {
			$obj->team_id = $bean->team_id;
			if (is551()) {
				$obj->team_set_id = $bean->team_set_id;
			}
		}
		
		return $obj;
	}
	
	
?>