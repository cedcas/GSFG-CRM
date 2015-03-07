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
require_once("modules/Calls/Call.php");
require_once("modules/Meetings/Meeting.php");
require_once("modules/Users/User.php");
require_once("modules/Calendar2/functions.php");
require_once "modules/Calendar2/gcal_import_functions.php";
require_once "modules/Calendar2/gcal_functions.php";
require_once('include/utils.php');
include_once ("include/TimeDate.php");
include_once('modules/Calendar2/calendar.class.php');

class GoogleImport {

	/**
	 * Class constructor
	 */
	function __construct() {
		
	}

	function InitSync() 
	{
		// Get access to globals
		global $db,$mod_strings,$app_list_strings;	
		global $current_user;
		
		$user = $current_user;
		$cal_total = 0;
		$gcal_total = 0;
		$caldav_total = 0;
		$meeting_total = 0;
		$gcal_sync_opt = '';

		$gcal_username = $user->getPreference('gcal_username'); // Google: Get username
		$gcal_password = $user->getPreference('gcal_password'); // Google: Get password
		if($gcal_username!="" and $gcal_password!="")
		{
			// verifying google settings
			include_once ("modules/Calendar2/googlecal/GoogleCalendarWrapper.php");
			$gc = new GoogleCalendarWrapper($gcal_username, $gcal_password,"");
			$resp = $gc->Login();
			if ($gc->fAuth=="")
			{
				ob_end_clean();
				print "Error: Login Authentication Failed";
				exit;
			}
			// Google: Get user prefernce for google sync
			$gcal_sync_opt = $user->getPreference('gcal_sync_opt');
			$gcal_prioriy = $user->getPreference('gcal_prioriy');	
			$gcal_sync_mod = $user->getPreference('gcal_sync_mod');
			$gcal_time_slot = $user->getPreference('gcal_time_slot');				

			$startDate=date("Y-m-d",mktime("0","0","0",date("n")-$gcal_time_slot));
			$endDate=date("Y-m-d",mktime("0","0","0",date("n")+$gcal_time_slot));

			// Google: Get total number of events in google calendar
			$gcal_total = processPageLoad($gcal_username,$gcal_password,$startDate,$endDate,$user->id,$gcal_sync_mod,$gcal_sync_opt,$gcal_prioriy);
			if($gcal_total=="")
				$gcal_total=0;
			
			if($gcal_sync_opt==1 or $gcal_sync_opt==2)
			{
				// Google: Get total number of events in Calendar2 modules
				// calls
				if($gcal_sync_mod=="call")
				{
					$sql_data = "select id from calls where (old_id_c='' or isnull(old_id_c)) and deleted=0";
					$res_data = $db->query($sql_data);
					$cal_total = $db->getRowCount($res_data);
				}	
				// meetings
				if($gcal_sync_mod=="meeting")
				{
					$sql_data = "select id from meetings where (old_id_c='' or isnull(old_id_c)) and deleted=0";
					$res_data = $db->query($sql_data);
					$meeting_total = $db->getRowCount($res_data);
				}
			}
		}
		
		$caldav_url = $user->getPreference('caldav_url'); // Caldav: Get url
		$caldav_username = $user->getPreference('caldav_username'); // Caldav: Get username
		$caldav_password = $user->getPreference('caldav_password'); // Caldav: Get password
		if($caldav_username!="" and $caldav_password!="" and $caldav_url!="")
		{
			global $sugar_config;			
			$caldav_obj = new CalendarCaldav($caldav_url, $caldav_username, $caldav_password);
			
			// Caldav: Get user prefernce for caldav sync
			$caldav_sync_opt = $user->getPreference('caldav_sync_opt');
			$caldav_prioriy = $user->getPreference('caldav_prioriy');	
			$caldav_sync_mod = $user->getPreference('caldav_sync_mod');
			$caldav_time_slot = $user->getPreference('caldav_time_slot');				

			$startDate=date("Ymd",mktime("0","0","0",date("n")-$caldav_time_slot))."T000000Z";
			$endDate=date("Ymd",mktime("0","0","0",date("n")+$caldav_time_slot))."T000000Z";
			
			$caldav_obj->getComponents($startDate,$endDate);
			ob_clean();
			
			$i=0;
			foreach($caldav_obj as $obj) 
		{
    				$caldav_total++;
    			}			
		}
			
		// calls
		$sql = "select id from calls where DATE(date_entered) > DATE('".$startDate."') and DATE(date_entered) < DATE('".$endDate."') and deleted=0 and old_id_c!=''";
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

		// meetings
		$sql = "select id from meetings where DATE(date_entered) > DATE('".$startDate."') and DATE(date_entered) < DATE('".$endDate."') and deleted=0 and old_id_c!=''";
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
			
			ob_end_clean();
		print "Success||".$gcal_sync_opt."||".$gcal_total."||".$cal_total."||".$meeting_total."||".$caldav_total;
			exit;
		}
	
	function SyncGoolgeEvents($page,$maxpage) 
	{
		// Get access to globals
		global $db,$mod_strings,$app_list_strings;	
		global $current_user;
		
		// Google: Get user prefernce for google sync
		$gcal_sync_opt = $current_user->getPreference('gcal_sync_opt');
		$gcal_prioriy = $current_user->getPreference('gcal_prioriy');	
		$gcal_sync_mod = $current_user->getPreference('gcal_sync_mod');
		$gcal_time_slot = $current_user->getPreference('gcal_time_slot');		
		
		// Google: Process all google data as per preferences configured
		ProcessGoogleData($page,$maxpage,$gcal_sync_mod,$gcal_sync_opt,$gcal_prioriy);
		ob_end_clean();
		print "Success";
		exit;
	}
	
	function SyncCal2Events($page,$maxpage) 
	{
		// Get access to globals
		global $db,$mod_strings,$app_list_strings;	
		global $current_user;
		
		$td = new TimeDate();
		
		// Google: Get user prefernce for google sync		
		$gcal_sync_opt = $current_user->getPreference('gcal_sync_opt');
		$gcal_prioriy = $current_user->getPreference('gcal_prioriy');	
		$gcal_sync_mod = $current_user->getPreference('gcal_sync_mod');
		$gcal_time_slot = $current_user->getPreference('gcal_time_slot');		
		
		if($gcal_sync_opt==1 or $gcal_sync_opt==2)
		{
			$d_start_time = $current_user->getPreference('d_start_time');
			$d_end_time = $current_user->getPreference('d_end_time');
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
				
			// Google: Process all claendar2 data as per preferences configured
			// calls
			$limit = " limit ".($page-1)*$maxpage.", ".$maxpage;
			if($gcal_sync_mod=="call")
			{
				$sql_data = "select id from calls where (old_id_c='' or isnull(old_id_c)) and deleted=0".$limit;
				$res_data = $db->query($sql_data);
				while($row_data=$db->fetchByAssoc($res_data))
				{
					$whole_day=0;
					$obj = new Call();
					$obj->retrieve($row_data['id']);
					
					if($obj->duration_hours==$day_duration_hours and $obj->duration_minutes==$day_duration_minutes)
					{								
						$whole_day=1;
					}
					else
						$obj->preprocess_fields_on_save();
						
					$gc = new GoogleCalls($obj,'before_save',array('check_notify'=>''),$whole_day);
					$sql = "update ".$obj->table_name." set google_response_c=\"".$obj->google_response_c."\",old_id_c='".$obj->old_id_c."',old_published_c='".$obj->old_published_c."',old_updated_c='".$obj->old_updated_c."',old_link_alt_c='".$obj->old_link_alt_c."',old_link_self_c='".$obj->old_link_self_c."',old_link_edit_c='".$obj->old_link_edit_c."',old_email_c='".$obj->old_email_c."',g_published_c='".$obj->g_published_c."',g_updated_c='".$obj->g_updated_c."' where id='".$obj->id."'";
					$obj->db->query($sql);
				}
			}	
			// meetings
			if($gcal_sync_mod=="meeting")
			{
				$sql_data = "select id from meetings where (old_id_c='' or isnull(old_id_c)) and deleted=0".$limit;
				$res_data = $db->query($sql_data);
				while($row_data=$db->fetchByAssoc($res_data))
				{
					$whole_day=0;
					$obj = new Meeting();
					$obj->retrieve($row_data['id']);					
					
					if($obj->duration_hours==$day_duration_hours and $obj->duration_minutes==$day_duration_minutes)
					{								
						$whole_day=1;
					}
					else
					{
						$obj->preprocess_fields_on_save();
					}
					
					// process google on save only when under google settings it is configured as 2 way and 1 way (from calendar2 -> google)
					$gc = new GoogleCalls($obj,'before_save',array('check_notify'=>''),$whole_day);
					$sql = "update ".$obj->table_name." set google_response_c=\"".$obj->google_response_c."\",old_id_c='".$obj->old_id_c."',old_published_c='".$obj->old_published_c."',old_updated_c='".$obj->old_updated_c."',old_link_alt_c='".$obj->old_link_alt_c."',old_link_self_c='".$obj->old_link_self_c."',old_link_edit_c='".$obj->old_link_edit_c."',old_email_c='".$obj->old_email_c."',g_published_c='".$obj->g_published_c."',g_updated_c='".$obj->g_updated_c."' where id='".$obj->id."'";
					$obj->db->query($sql);
				}
			}
		}
		ob_end_clean();
		print "Success";
		exit;
	}
	
	function SyncCalDavEvents($page,$maxpage) 
	{
		// Get access to globals
		global $db,$mod_strings,$app_list_strings;	
		global $current_user;
		
		$td = new TimeDate();
		
		// Caldav: Get user prefernce for caldav sync		
		$caldav_sync_opt = $current_user->getPreference('caldav_sync_opt');		
		$caldav_prioriy = $current_user->getPreference('caldav_prioriy');	
		$caldav_sync_mod = $current_user->getPreference('caldav_sync_mod');
		$caldav_time_slot = $current_user->getPreference('caldav_time_slot');		
		
		if($caldav_sync_opt==1 or $caldav_sync_opt==3)
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
			$caldav_time_slot = $current_user->getPreference('caldav_time_slot');

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

			$caldav_url = $current_user->getPreference('caldav_url'); // Caldav: Get url
			$caldav_username = $current_user->getPreference('caldav_username'); // Caldav: Get username
			$caldav_password = $current_user->getPreference('caldav_password'); // Caldav: Get password
			
			global $sugar_config;			
			$caldav_obj = new CalendarCaldav($caldav_url, $caldav_username, $caldav_password);

			// Caldav: Get user prefernce for caldav sync
			$caldav_sync_opt = $current_user->getPreference('caldav_sync_opt');
			$caldav_prioriy = $current_user->getPreference('caldav_prioriy');	
			$caldav_sync_mod = $current_user->getPreference('caldav_sync_mod');
			$caldav_time_slot = $current_user->getPreference('caldav_time_slot');				

			$startDate=date("Ymd",mktime("0","0","0",date("n")-$caldav_time_slot))."T000000Z";
			$endDate=date("Ymd",mktime("0","0","0",date("n")+$caldav_time_slot))."T000000Z";

			$caldav_obj->getComponents($startDate,$endDate);
			ob_clean();			
			
			$td = new TimeDate();
			$CurrentUserId=$userid;
					
			$record_counter=0;
			$process_id = array();
print_r($caldav_obj);
exit;
			foreach($caldav_obj as $k => $event ) 
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
				if($caldav_sync_mod=="call")
				{			
					$obj = new Call();
					$jn = "cal2_call_id_c";
				}
				elseif($caldav_sync_mod=="meeting")
				{
					$obj = new Meeting();
					$jn = "cal2_meeting_id_c";
				}
				
				$event_obj = $event->getBaseComponent();
				//$event->setProperty('EVENT_TYPE',$obj->table_name);
				//$event->setProperty('SUMMARY','updated title');
				
				//$caldav_obj->update($caldav_url,$event->getEtag());
				

				$obj->caldav_uid_c=$event_obj->GetPValue('UID');
				$obj->caldav_response_c=$event_obj->Render();
				$obj->name=$event_obj->GetPValue('SUMMARY');
				$obj->description=$event_obj->GetPValue('DESCRIPTION');
				$obj->location=$event_obj->GetPValue('LOCATION');
				
				$obj->date_start=date($td->get_date_time_format(),strtotime($event_obj->GetPValue('DTSTART')));
				$obj->date_end=date($td->get_date_time_format(),strtotime($event_obj->GetPValue('DTEND')));
				$obj->duration_hours=date("H",strtotime($obj->date_end))-date("H",strtotime($obj->date_start));
				$obj->duration_minutes=date("i",strtotime($obj->date_end))-date("i",strtotime($obj->date_start));
				
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
				}
			}
			
			if(count($process_id)>0)
			{
				$chk_startDate=date("Y-m-d",mktime("0","0","0",date("n")-$gcal_time_slot));
				$chk_endDate=date("Y-m-d",mktime("0","0","0",date("n")+$gcal_time_slot));
				global $db;

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
				elseif(count($process_id['meetings'])>0)
				{					
					$delids = implode(",",$process_id['calls']);
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
		ob_end_clean();
		print "Success";
		exit;
	}	
}

?>