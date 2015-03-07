<?php
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
/**
 *Calendar2ViewAjaxSave
 * 
 * */
 
require_once('include/MVC/View/SugarView.php');
require_once('modules/Calendar2/Calendar2.php');

class Calendar2ViewAjaxSave extends SugarView {

 	function Calendar2ViewAjaxSave(){
 		parent::SugarView();

 	}
 	
 	function process() {
		$this->display();
 	}

 	function display() {

		require_once("modules/Calls/Call.php");
		require_once("modules/Meetings/Meeting.php");

		require_once("modules/Calendar2/functions.php");
		
		global $current_user;
		include_once('modules/Calendar2/calendar.class.php');
		$caldav_url = $current_user->getPreference('caldav_url'); // Caldav: Get url
		$caldav_username = $current_user->getPreference('caldav_username'); // Caldav: Get username
		$caldav_password = $current_user->getPreference('caldav_password'); // Caldav: Get password		
		
		// Caldav: Get user prefernce for caldav sync
		$caldav_sync_opt = $current_user->getPreference('caldav_sync_opt');
		$caldav_prioriy = $current_user->getPreference('caldav_prioriy');	
		$caldav_sync_mod = $current_user->getPreference('caldav_sync_mod');
		$caldav_time_slot = $current_user->getPreference('caldav_time_slot');				

		// Cal2 Modified for google 2010/09/16

		// Google: include Google Sync File		
		require_once "modules/Calendar2/gcal_functions.php";
		global $current_user;
		
		global $timedate,$sugar_version;
		
		// Google:  Get User Preference
		$gcal_sync_mod = $current_user->getPreference('gcal_sync_mod');
		$dosync=0;
		
		foreach($_REQUEST as $a => $b) {
			$GLOBALS['log']->debug("ajaxsave REQUEST $a = $b"."<br>");
		}
		if(isset($_REQUEST['appttype']) && $_REQUEST['appttype'] == 'Calls'){
			$bean = new Call();
			$type = 'call';
			$jn = "cal2_call_id_c";
			if($gcal_sync_mod=="call") //  Google: if sync module selected as Call then go for sync process
				$dosync=1;
		} elseif(isset($_REQUEST['appttype']) && $_REQUEST['appttype'] == 'Meetings'){
			$bean = new Meeting();
			$type = 'meeting';
			$jn = "cal2_meeting_id_c";
			if($gcal_sync_mod=="meeting") //  Google: if sync module selected as Meeting then go for sync process
				$dosync=1;
		} elseif($_REQUEST['cur_module'] == "Meetings") {
			$bean = new Meeting();
			$type = 'meeting';
			$jn = "cal2_meeting_id_c";
			if($gcal_sync_mod=="meeting") //  Google: if sync module selected as Meeting then go for sync process
				$dosync=1;
		} else {
			$bean = new Call();
			$type = 'call';
			$jn = "cal2_call_id_c";
			if($gcal_sync_mod=="call") //  Google: if sync module selected as Call then go for sync process
				$dosync=1;
		}
		

		if(!empty($_REQUEST['record'])) {
			$bean->retrieve($_REQUEST['record']);
		}
		else
		{
			$bean->old_id_c=""; //  Google: if new record set google event id to blank as this has been used as condition in google wrapper class
		}

		if(!$bean->ACLAccess('Save')) {
			$json_arr = array(
				'succuss' => 'no',
			);
			echo json_encode($json_arr);
			die;
		}

		if (!isset($_REQUEST['name']) || $_REQUEST['name'] == "") {
			$json_arr = array(
				'succuss' => 'no',
			);
			echo json_encode($json_arr);
			die;
		}

		$bean->name = $_REQUEST['name'];
		$bean->location = (isset($_REQUEST['location']) && !empty($_REQUEST['location'])) ? $_REQUEST['location'] : "";
		
		// patch for sugar 6.1
		if($sugar_version>"6.0")
		{
			$sd_offset = strlen(trim($_REQUEST['date_start'])) < 11 ? false : true;
			$bean->date_start = $timedate->to_db($_REQUEST['date_start']);
		}
		else
		{
			$bean->date_start = $_REQUEST['date_start'];		
		}

		$bean->date_end = $_REQUEST['date_start'];
		if (!isset($_REQUEST['duration_hours'])) $_REQUEST['duration_hours'] = 0;
		$bean->duration_hours = $_REQUEST['duration_hours'];
		if (!isset($_REQUEST['duration_minutes'])) {
			if ($bean->duration_hours == 0) {
				$_REQUEST['duration_minutes'] = 15;
			} else {
				$_REQUEST['duration_minutes'] = 0;
			}
		}
		$bean->duration_minutes = $_REQUEST['duration_minutes'];
		$whole_day=0;
		if(isset($_REQUEST['cal2_whole_day_c']) && !empty($_REQUEST['cal2_whole_day_c'])){
			$bean->duration_hours = $_REQUEST['duration_hours_h'];
			$bean->duration_minutes = $_REQUEST['duration_minutes_h'];	
			$whole_day=1; //  Google: set flag whether event is whole day or not
		}

		if(isset($_REQUEST['reminder_checked']) && !empty($_REQUEST['reminder_checked']))
			$bean->reminder_time = $_REQUEST['reminder_time']; 
		else
			$bean->reminder_time = -1;
		if(isset($_REQUEST['cur_module']) && $_REQUEST['cur_module'] == 'Calls')
		$bean->direction = $_REQUEST['direction'];
		$bean->status = $_REQUEST['status'];
		$bean->assigned_user_id = $_REQUEST['cal2_assigned_user_id'];
		
		//2010/09/10 to send out notification in parent::save()
		$_REQUEST['assigned_user_id'] = $_REQUEST['cal2_assigned_user_id'];
		//
		
		if (isset($_REQUEST['parent_type'])) $bean->parent_type = $_REQUEST['parent_type'];
		if (isset($_REQUEST['parent_id'])) $bean->parent_id = $_REQUEST['parent_id'];
		if (isset($_REQUEST['description'])) $bean->description = $_REQUEST['description'];
		if (isset($_REQUEST['cal2_category_c'])) $bean->cal2_category_c = $_REQUEST['cal2_category_c'];
		if(!isset($_REQUEST['cal2_options_c']) || empty($_REQUEST['cal2_options_c']))
			$bean->cal2_options_c = false;
		else
			$bean->cal2_options_c = $_REQUEST['cal2_options_c'];
		if(!isset($_REQUEST['cal2_whole_day_c']) || empty($_REQUEST['cal2_whole_day_c']))
			$bean->cal2_whole_day_c = false;
		else
			$bean->cal2_whole_day_c = $_REQUEST['cal2_whole_day_c'];

		if (isPro()) {
			if (is551()) {
				require_once('modules/Teams/TeamSet.php');
				$ts = new TeamSet();
				$team_arr = array();
				for($i = 0; $i < 50; $i++)
					if(isset($_REQUEST["id_team_name_collection_".$i]) && !empty($_REQUEST["id_team_name_collection_".$i]))		
						$team_arr[] = $_REQUEST["id_team_name_collection_".$i];
				$bean->team_id = $_REQUEST["id_team_name_collection_".$_REQUEST["primary_team_name_collection"]];
				$team_set_id = $ts->addTeams($team_arr);
				$bean->team_set_id = $team_set_id;
			} else {
				$bean->team_id = $_REQUEST['team_id'];
			}
		}

		if(((!isset($_REQUEST['cal2_recur_id_c']) || empty($_REQUEST['cal2_recur_id_c'])) && $_REQUEST['edit_all_recurrence'] == true) || (isset($_REQUEST['cal2_repeat_type_c']) && $_REQUEST['cal2_repeat_type_c'] != '')) {
			$bean->cal2_repeat_type_c = $_REQUEST['cal2_repeat_type_c'];
			$bean->cal2_repeat_interval_c = $_REQUEST['cal2_repeat_interval_c'];
			$bean->cal2_repeat_end_date_c = $_REQUEST['cal2_repeat_end_date_c'];
			$bean->cal2_repeat_days_c = $_REQUEST['cal2_repeat_days_c'];	
			$this->processfirstdate($bean);
		}

		//when edit_all_recurrence is set to true, this is a new recurrence.
		if ($_REQUEST['edit_all_recurrence'] == true) {
			$bean->id = "";
			$GLOBALS['log']->debug("ajaxsave going call save() date_entered = ".$bean->date_entered."<br>");
			$GLOBALS['log']->debug("ajaxsave going call save() date_modified = ".$bean->date_modified."<br>");
			if (!empty($bean->date_entered)) $bean->date_entered = "";
		}
		if (!empty($bean->date_modified)) $bean->date_modified = "";
		$GLOBALS['log']->debug("ajaxsave going call save() date_entered = ".$bean->date_entered."<br>");
		$GLOBALS['log']->debug("ajaxsave going call save() date_modified = ".$bean->date_modified."<br>");


		//vCal is updated later in invitees_filling
		$bean->update_vcal = false;

		if($bean->id=="") $bean->old_id_c="";
		
		//check if system's outgoing server is configured
		require_once('include/OutboundEmail/OutboundEmail.php');
		$oe = new OutboundEmail();
		$system = $oe->getSystemMailerSettings();
		$GLOBALS['log']->debug('ajaxsave Outbound email system smtp server='.$system->mail_smtpserver);
		if (empty($system->mail_smtpserver)) {
			if (isset($_REQUEST['send_invites']) && $_REQUEST['send_invites']) $_REQUEST['send_invites'] = "0";
			$bean->save();
		} else {
			if (isset($_REQUEST['send_invites']) && $_REQUEST['send_invites']) $_REQUEST['send_invites'] = "1";
			$bean->save(true);
		}
		//if current_user is included in participatns, its shcedule is automatically set to 'accept'
		$this->invitees_filling($bean);		
		
		if(isset($_REQUEST['send_invites'])) {
		if($_REQUEST['send_invites'] == "1") {
			$admin = new Administration();
			$admin->retrieveSettings();
			$notify_list = $bean->get_notification_recipients();
			foreach ($notify_list as $notify_user) {
					$GLOBALS['log']->debug('SugarBean:: notification user='.$notify_user->name);
					$bean->send_assignment_notifications($notify_user, $admin);
			}
		}
		}	
		
		// caldav sync begin
		if($caldav_url!='' and $caldav_username!='' and $caldav_password!='' and ($caldav_sync_opt==1 or $caldav_sync_opt==2))
		{
			$caldav_obj = new CalendarCaldav($caldav_url, $caldav_username, $caldav_password);
			if($bean->caldav_uid_c!='')
				$caldav_obj->newEvent($bean,$bean->caldav_uid_c.'.ics');
			else
				$caldav_obj->newEvent($bean,$caldav_url);
				
		}
		
		// calldav sync end
		
		//  Google: Process Google Calendar
		$gcal_sync_opt = $current_user->getPreference('gcal_sync_opt');
		if(($gcal_sync_opt==1 or $gcal_sync_opt==2) and $dosync==1)
		{
			if($whole_day==1)
				$bean->retrieve($bean->id); //  Google: In case of whole day event, system should clear all time zone conversions by sugar. so retriving data again
				
			//  Google: process google on save only when under google settings it is configured as 2 way and 1 way (from calendar2 -> google)
			$gc = new GoogleCalls($bean,'before_save',array('check_notify'=>''),$whole_day);
			
			//  Google: update google response in table
			$sql = "update ".$bean->table_name." set google_response_c=\"".$bean->google_response_c."\",old_id_c='".$bean->old_id_c."',old_published_c='".$bean->old_published_c."',old_updated_c='".$bean->old_updated_c."',old_link_alt_c='".$bean->old_link_alt_c."',old_link_self_c='".$bean->old_link_self_c."',old_link_edit_c='".$bean->old_link_edit_c."',old_email_c='".$bean->old_email_c."',g_published_c='".$bean->g_published_c."',g_updated_c='".$bean->g_updated_c."' where id='".$bean->id."'";
			$bean->db->query($sql);
		}		

		//store resource ids to update schedulerrows later
		$user_ids = array();
		foreach($bean->resources_arr as $r) $user_ids[] = $r;

		$arr_rec = array();
		if(((!isset($_REQUEST['cal2_recur_id_c']) || empty($_REQUEST['cal2_recur_id_c'])) && $_REQUEST['edit_all_recurrence'] == true) || (isset($_REQUEST['cal2_repeat_type_c']) && $_REQUEST['cal2_repeat_type_c'] != '') )
		{
			//  Google: delete previous recourance in google calendar
			if(($gcal_sync_opt==1 or $gcal_sync_opt==2) and $dosync==1)
			{
				$sql_data = " select id from ".$bean->table_name." t WHERE t.".$jn." = '".addslashes($_REQUEST['record'])."'";			
				$res_data = $bean->db->query($sql_data);
				while($row_data=$bean->db->fetchByAssoc($res_data))
				{
					if($type == 'meeting')
						$rec_obj = new Meeting();				
					else
						$rec_obj = new Call();
					$rec_obj_del = new Meeting();
					$rec_obj_del->retrieve($row_data['id']);
					$gc = new GoogleCalls($rec_obj_del,'before_delete',array('check_notify'=>''));
				}
			}	
			$arr_rec = $this->createRecurrence($bean, $jn);
			if(($gcal_sync_opt==1 or $gcal_sync_opt==2) and $dosync==1)
			{
				foreach($arr_rec as $recur_id_arr)
				{
					if($type == 'meeting')
						$rec_obj = new Meeting();
					else
						$rec_obj = new Call();

					$rec_obj->retrieve($recur_id_arr['record']);				
					if($gcal_sync_opt==1 or $gcal_sync_opt==2)
					{
						// patch for sugar 6.1
						if($sugar_version>"6.0")
						{
							$rec_obj->save();
						}
						else
						{
						if($whole_day==0)
						{
							$rec_obj->preprocess_fields_on_save();
							}
						}

						//  Google: Create Recurance events in google
						$gc = new GoogleCalls($rec_obj,'before_save',array('check_notify'=>''),$whole_day);

						// update google response in table
						$sql = "update ".$rec_obj->table_name." set google_response_c=\"".$rec_obj->google_response_c."\",old_id_c='".$rec_obj->old_id_c."',old_published_c='".$rec_obj->old_published_c."',old_updated_c='".$rec_obj->old_updated_c."',old_link_alt_c='".$rec_obj->old_link_alt_c."',old_link_self_c='".$rec_obj->old_link_self_c."',old_link_edit_c='".$rec_obj->old_link_edit_c."',old_email_c='".$rec_obj->old_email_c."',g_published_c='".$rec_obj->g_published_c."',g_updated_c='".$rec_obj->g_updated_c."' where id='".$rec_obj->id."'";
						$bean->db->query($sql);
					}
				}
			}	
		}

		// Cal2 End
		
		$bean->retrieve($bean->id); // do not delete this line!!! it prevents the sugar's bug with timedate!
		


		if($type == 'call') $users = $bean->get_call_users();
		if($type == 'meeting') $users = $bean->get_meeting_users();
		//store user ids to update schedulerrows on browser
		//$user_ids = array();
		foreach($users as $u) $user_ids[] = $u->id;

		$team_id = "";
		$team_name = "";
		if (isPro()) {
			$team_id = $bean->team_id;
			$team_name = $bean->team_name;
		} else {
			$team_id = "";
			$team_name = "";
		}

		
		$start = to_timestamp_from_uf($bean->date_start);

		$loc = (!is_null($bean->location)) ? $bean->location : "";
		$repeat_type = (isset($_REQUEST['cal2_repeat_type_c'])) ? $_REQUEST['cal2_repeat_type_c'] : "";
		$repeat_interval = (isset($_REQUEST['cal2_repeat_interval_c'])) ? $_REQUEST['cal2_repeat_interval_c'] : "";
		$repeat_end_date = (isset($_REQUEST['cal2_repeat_end_date_c'])) ? $_REQUEST['cal2_repeat_end_date_c'] : "";
		$repeat_days = (isset($_REQUEST['cal2_repeat_days_c'])) ? $_REQUEST['cal2_repeat_days_c'] : "";
		$recur_id = (isset($_REQUEST['cal2_recur_id_c'])) ? $_REQUEST['cal2_recur_id_c'] : "";
		
		// Cal2 Modified for showing events accept status 2010/09/21
		if($type == 'call')
		{
			$sql_accept_st = "select accept_status from calls_users WHERE user_id = '" . $current_user->id . "' and call_id='".$bean->id."'";
		}
		elseif($type == 'meeting')
		{
			$sql_accept_st = "select accept_status from meetings_users WHERE user_id = '" . $current_user->id . "' and meeting_id='".$bean->id."'";
		}
		$res_accept = $bean->db->query($sql_accept_st);
		$row_accept = $bean->db->fetchByAssoc($res_accept);

		$accept_status = getStatusInHtml($row_accept['accept_status']);
		ob_clean();
		$json_arr = array(
			'succuss' => 'yes',
			'record_name' => $bean->name,
			'record' => $bean->id,
			'type' => $type,
			'accept_status' => $accept_status,
			'assigned_user_id' => $bean->assigned_user_id,
			'user_id' => '',
			'user_name' => $bean->assigned_user_name,
			'date_start' => $bean->date_start,
			'start' => $start,
			'time_start' => timestamp_to_user_formated2($start,$GLOBALS['timedate']->get_time_format()),
			'duration_hours' => $bean->duration_hours,
			'duration_minutes' => $bean->duration_minutes,
			'description' => $bean->description,
			'status' => $bean->status,
			'location' => $loc,
			'team_id' => $team_id,
			'team_name' => $team_name,
			'users' => $user_ids,
			'cal2_recur_id_c' => $recur_id,
			'cal2_repeat_type_c' => $repeat_type,
			'cal2_repeat_interval_c' => $repeat_interval,
			'cal2_repeat_end_date_c' => $repeat_end_date,
			'cal2_repeat_days_c' => $repeat_days,
			'arr_rec' => $arr_rec,
			'detailview' => 1,
		);
		echo json_encode($json_arr);
	}	

	function invitees_filling(&$bean){

		global $current_user;

		$type = "";
		if($bean->object_name == 'Call'){
			$type = 'call';
		} elseif($bean->object_name == 'Meeting'){
			$type = 'meeting';
		}

		if(!empty($_POST['user_invitees']))
			$userInvitees = explode(',', trim($_POST['user_invitees'], ','));
		else 
			$userInvitees = array();

		//assigned user is always included in participants
		if (!in_array($_REQUEST['cal2_assigned_user_id'], $userInvitees)) $userInvitees[] = $_REQUEST['cal2_assigned_user_id'];
		//
		
		$deleteUsers = array();
		$bean->load_relationship('users');	

		//Finding removed users and storing existing accept status
	    //we call get_meeting_users instead of link->get to get accept status
		$userlist = array();
		if ($type == "meeting") {
			$userlist = $bean->get_meeting_users();
		} elseif ($type == "call") {
			$userlist = $bean->get_call_users();
		}
		for ($i=0; $i<count($userlist); $i++) {
			if (!in_array(($userlist[$i]->id), $userInvitees)) {
				$deleteUsers[$userlist[$i]->id] = $userlist[$i]->id;
				$GLOBALS['log']->debug("Going to delete users deleteUsers=".$userlist[$i]->id);
				$bean->users->delete($bean->id, $userlist[$i]->id);
				$GLOBALS['log']->debug("Going to call vCal:cache on deletion user_id=".$userlist[$i]->id);
				vCal2::cache_sugar_vcal($userlist[$i]);
			} else {
				$acceptStatusUsers[$userlist[$i]->id] = $userlist[$i]->accept_status;
				$GLOBALS['log']->debug("Going to delete users acceptStatus=".$acceptStatusUsers[$userlist[$i]->id]);
			}
		}
		
		if(!empty($_POST['contacts_assigned']))
			$contacts_assigned = explode(',', trim($_POST['contacts_assigned'], ','));
		else 
			$contacts_assigned = array();

		if(!empty($_POST['parent_id']) && $_POST['parent_type'] == 'Contacts') {
			$contacts_assigned[] = $_POST['parent_id'];
		}			

		$deleteContact = array();
		$bean->load_relationship('contacts');	
		require_once("modules/Contacts/Contact.php");	
		$contactlist = array();
		if ($type == "meeting") {
			$query = "SELECT meetings_contacts.required, meetings_contacts.accept_status, meetings_contacts.contact_id from meetings_contacts where meetings_contacts.meeting_id='$bean->id' AND meetings_contacts.deleted=0";
			$result = $bean->db->query($query, true);
			$list = Array();

			while($row = $bean->db->fetchByAssoc($result)) {
				$template = new Contact(); 
				$record = $template->retrieve($row['contact_id']);
				$template->required = $row['required'];
				$template->accept_status = $row['accept_status'];

				if($record != null) {
					$list[] = $template;
				}
			}
			$contactlist =  $list;
		} elseif ($type == "call") {
			$query = "SELECT calls_contacts.required, calls_contacts.accept_status, calls_contacts.contact_id from calls_contacts where calls_contacts.call_id='$bean->id' AND calls_contacts.deleted=0";
			$result = $bean->db->query($query, true);
			$list = Array();

			while($row = $bean->db->fetchByAssoc($result)) {
				$template = new Contact(); 
				$record = $template->retrieve($row['contact_id']);
				$template->required = $row['required'];
				$template->accept_status = $row['accept_status'];

				if($record != null) {
					$list[] = $template;
				}
			}
			$contactlist =  $list;
		}
		for ($i=0; $i<count($contactlist); $i++) {
			if (!in_array(($contactlist[$i]->id), $contacts_assigned)) {
				$deleteContact[$contactlist[$i]->id] = $contactlist[$i]->id;
				$GLOBALS['log']->debug("Going to delete contacts deleteContact=".$contactlist[$i]->id);
				$bean->contacts->delete($bean->id, $contactlist[$i]->id);
				$GLOBALS['log']->debug("Going to call vCal:cache on deletion contact_id=".$contactlist[$i]->id);				
			} else {
				$acceptStatusContacts[$contactlist[$i]->id] = $contactlist[$i]->accept_status;
				$GLOBALS['log']->debug("Going to delete contact acceptStatus=".$acceptStatusContacts[$userlist[$i]->id]);
			}
		}
		
		// process lead invitess
		if(!empty($_POST['leads_assigned']))
			$leads_assigned = explode(',', trim($_POST['leads_assigned'], ','));
		else 
			$leads_assigned = array();

		$deleteLeads = array();
		$bean->load_relationship('leads');	

		if($type == "meeting") 
		{
			$query = "SELECT mu.lead_id, mu.accept_status FROM meetings_leads mu WHERE mu.meeting_id='$bean->id' AND mu.deleted=0";			
		} 
		elseif ($type == "call") 
		{
			$query = "SELECT mu.lead_id, mu.accept_status FROM calls_leads mu where mu.call_id='$bean->id' AND mu.deleted=0";
		}
		$acceptStatusLeads = array();
		$result = $bean->db->query($query, true);

		while($row = $bean->db->fetchByAssoc($result)) 
		{
			if (!in_array(($row['lead_id']->id), $leads_assigned)) 
				$deleteLeads[$row['lead_id']] = $row['lead_id'];
			else
				$acceptStatusLeads[$row['lead_id']] = $row['accept_status'];
		}
    		if(count($deleteLeads) > 0) 
    		{
    			$sql = '';
    			foreach($deleteLeads as $u) 
    			{
    		        	$sql .= ",'" . $u . "'";
    			}
    			$sql = substr($sql, 1);
    			// We could run a delete SQL statement here, but will just mark as deleted instead
    			if ($type == "meeting")
    			{
    				$sql = "UPDATE meetings_leads set deleted = 1 where lead_id in ($sql) AND meeting_id = '". $bean->id . "'";
    			}
    			elseif ($type == "call") 
    			{
    				$sql = "UPDATE calls_leads set deleted = 1 where lead_id in ($sql) AND call_id = '". $bean->id . "'";
    			}
    			$bean->db->query($sql);
    		}
    		
    		
		//Finding removed resources	
		if(!empty($_POST['resources_assigned'])) 
			$resourcesAssigned = explode(',', trim($_POST['resources_assigned'], ','));
		else 
			$resourcesAssigned = array();

		$deleteResources = array();
		$bean->load_relationship('resources');
		$reslist = array();
		$reslist = $bean->resources->get(false);
		for ($i=0; $i<count($reslist); $i++) {
			if (!in_array(($reslist[$i]), $resourcesAssigned))
				$deleteResources[$reslist[$i]] = $reslist[$i];
		}

		if(count($deleteResources) > 0){
				$GLOBALS['log']->debug("Going to call count=".count($deleteResources));
			foreach($deleteResources as $r) {
				$GLOBALS['log']->debug("Going to call vCals r=".$r);
				$bean->resources->delete($bean->id, $r);
				require_once('modules/Resources/Resource.php');
				$res = new Resource();
				$res->retrieve($r);
				$GLOBALS['log']->debug("Going to call vCal:cache on deletion resource_id=".$r);
				vCal2::cache_sugar_vcal($res);
			}
		}

		//Now relations to users and resources are being rebuilt, then vCals are updated.
		
		$bean->users_arr = $userInvitees;
		$bean->contacts_arr = $contacts_assigned;
		$bean->resources_arr = $resourcesAssigned;
		$bean->leads_arr = array();
    		$bean->leads_arr = $leads_assigned;
    		
		if(!empty($_POST['parent_id']) && $_POST['parent_type'] == 'Leads') {
			$bean->leads_arr[] = $_POST['parent_id'];
		}    	
		
		
		// Process leads
		$existing_leads =  array();
		if(!empty($_POST['existing_lead_invitees'])) 
		{
			$existing_leads =  explode(",", trim($_POST['existing_lead_invitees'], ','));
		}

		foreach($bean->leads_arr as $lead_id) 
		{
			if(empty($lead_id) || isset($existing_leads[$lead_id]) || isset($deleteLeads[$lead_id])) 
			{
				continue;
			}
			if(!isset($acceptStatusLeads[$lead_id])) 
			{
				$bean->leads->add($lead_id);
			}
			else 
			{
				// update query to preserve accept_status
				if ($type == "call")
				{
					$qU  = 'UPDATE calls_leads SET deleted = 0, accept_status = \''.$acceptStatusLeads[$lead_id].'\' ';
					$qU .= 'WHERE call_id = \''.$bean->id.'\' ';
					$qU .= 'AND lead_id = \''.$lead_id.'\'';				
				}
				elseif ($type == "meeting") 
				{
					$qU  = 'UPDATE meetings_leads SET deleted = 0, accept_status = \''.$acceptStatusLeads[$lead_id].'\' ';
					$qU .= 'WHERE meeting_id = \''.$bean->id.'\' ';
					$qU .= 'AND lead_id = \''.$lead_id.'\'';
				}				
				$bean->db->query($qU);
			}
		}		

		$existing_contact = array();
		foreach($bean->contacts_arr as $contact_id){
			if(empty($contact_id))	continue;
			if(!isset($existing_contact[$contact_id]) && !isset($deleteContact[$contact_id])) 
			{
				if(isset($acceptStatusContacts[$contact_id]))
				{
					if ($type == "call")
					{
						// update query to preserve accept_status
						$qU  = 'UPDATE calls_contacts SET deleted = 0, accept_status = \''.$acceptStatusContacts[$contact_id].'\' ';
						$qU .= 'WHERE call_id = \''.$bean->id.'\' ';
						$qU .= 'AND contact_id = \''.$contact_id.'\'';
					}
					elseif ($type == "meeting") 
					{
						// update query to preserve accept_status
						$qU  = 'UPDATE meetings_contacts SET deleted = 0, accept_status = \''.$acceptStatusContacts[$contact_id].'\' ';
						$qU .= 'WHERE meeting_id = \''.$focus->id.'\' ';
						$qU .= 'AND contact_id = \''.$contact_id.'\'';					
					}
					$bean->db->query($qU);					
				}
				else
					$bean->contacts->add($contact_id);
			}
		}		

		$existing_users = array();
		if(!empty($_POST['existing_invitees'])) 
			$existing_users =  explode(",", trim($_POST['existing_invitees'], ','));	

		foreach($bean->users_arr as $user_id){
			if(empty($user_id))	continue;
			if(!isset($existing_users[$user_id]) && !isset($deleteUsers[$user_id])) {
				if(!isset($acceptStatusUsers[$user_id]) && $user_id != $current_user->id) {
					require_once('modules/Users/User.php');
					$temp_user = new User();
					$temp_user->retrieve($user_id);
					if($temp_user->getPreference('auto_accept','global',$temp_user) == 'true') {
						$bean->users->add($user_id, array('accept_status'=>'accept'));
					} else {
						$bean->users->add($user_id, array('accept_status'=>'accept'));
					}					
				} else {
					if ($user_id == $current_user->id) {
						//$bean->users->add($user_id, array('accept_status'=>'accept'));
						$bean->users->add($user_id, array('accept_status'=>$_POST['accept_status']));
					} else {
						$bean->users->add($user_id, array('accept_status'=>$acceptStatusUsers[$user_id]));
					}
					
				}
			}
			//Updates each user's vCal
			require_once('modules/Users/User.php');
			$GLOBALS['log']->debug("Going to call vCal:cache on deletion user_id=".$user_id);
			$usr = new User();
			$usr->retrieve($user_id);
			vCal2::cache_sugar_vcal($usr);
		}
		
		//Rebuilding relations to resources, and vCals are updated.
		$existing_resources =  array();
		if(!empty($_POST['existing_resources_assigned'])) 
			$existing_resources =  explode(",", trim($_POST['existing_resources_assigned'], ','));

			foreach($bean->resources_arr as $resource_id){
			if(empty($resource_id) || isset($exiting_resources[$resource_id]) || isset($deleteResources[$resource_id])) 
				continue;
			
			$bean->resources->add($resource_id, array('accept_status'=>'accept'));

			require_once('modules/Resources/Resource.php');
			$res = new Resource();
			$res->retrieve($resource_id);
			$GLOBALS['log']->debug("Going to call vCal:cache resource_id=".$resource_id);
			vCal2::cache_sugar_vcal($res);
		}
	}
	
	function processfirstdate(&$bean)
	{
		require_once("modules/Calendar2/functions.php");
		$repeat_days = "";
				
		if (isset($_REQUEST['cal2_repeat_days_c']) && !empty($_REQUEST['cal2_repeat_days_c'])) $repeat_days = $_REQUEST['cal2_repeat_days_c'];
		
		$timezone = $GLOBALS['timedate']->getUserTimeZone();
		global $timedate;
		
		if($repeat_days!="")
		{
			$ArrData = array();
			for($i=0;$i<strlen($repeat_days);$i++)
			{
				$Nrepeat_days = $repeat_days[$i]-1;
				$startd = date("N",strtotime($bean->date_start));
				if($Nrepeat_days>$startd)
				{	
					$diff = $Nrepeat_days - $startd;
					$date_start = date($timedate->get_date_time_format(),mktime(date("H",strtotime($bean->date_start)),date("i",strtotime($bean->date_start)),date("s",strtotime($bean->date_start)),date("n",strtotime($bean->date_start)),date("j",strtotime($bean->date_start))+$diff,date("Y",strtotime($bean->date_start))));
				}
				elseif($Nrepeat_days<$startd)
				{
					$diff = 7-($startd - $Nrepeat_days);
					$date_start = date($timedate->get_date_time_format(),mktime(date("H",strtotime($bean->date_start)),date("i",strtotime($bean->date_start)),date("s",strtotime($bean->date_start)),date("n",strtotime($bean->date_start)),date("j",strtotime($bean->date_start))+$diff,date("Y",strtotime($bean->date_start))));
				}
				else
					$date_start = $bean->date_start;
				$ArrData[to_timestamp_from_uf($date_start)] = $date_start;
			}
			asort($ArrData);
			$bean->date_start = current($ArrData);		
		}	
	}
	
	function createRecurrence(&$bean, $jn) {

		require_once("modules/Calendar2/functions.php");

		$ret_arr = array();
		$repeat_days = "";
		$rtype = "";
		
		
		if (isset($_REQUEST['cal2_repeat_days_c']) && !empty($_REQUEST['cal2_repeat_days_c'])) $repeat_days = $_REQUEST['cal2_repeat_days_c'];
		
		if (isset($_REQUEST['cal2_repeat_type_c']) && !empty($_REQUEST['cal2_repeat_type_c'])) $rtype = $_REQUEST['cal2_repeat_type_c'];
		
		if (isset($_REQUEST['cal2_repeat_interval_c']) && !empty($_REQUEST['cal2_repeat_interval_c'])) {
			$interval = $_REQUEST['cal2_repeat_interval_c'];
		} else {
			$interval = 0;
		}

		$timezone = $GLOBALS['timedate']->getUserTimeZone();
		global $timedate;
		
		$bean->retrieve($bean->id);
		


		$start_unix = to_timestamp_from_uf($_REQUEST['date_start']);
		$first_occurance = to_timestamp_from_uf($bean->date_start); // for weekly events need to check
		

		$end_unix = to_timestamp_from_uf($_REQUEST['cal2_repeat_end_date_c']);
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
			WHERE t.".$jn." = '".addslashes($_REQUEST['record'])."'
		";	

		$bean->db->query($qu);
		$ft = true;

		if(!empty($rtype) && (!isset($_REQUEST['cal2_repeat_end_date_c']) || !empty($_REQUEST['cal2_repeat_end_date_c'])) ){
			
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

					if($i >= 0 && $i % $interval == 0)
					{
						for($d = 1; $d < 8; $d++)
						{
							if(strpos($repeat_days,(string)($d)) !== false)
							{
								if(($week_start_day + ($d-1)*60*60*24) == $first_occurance or ($week_start_day + ($d-1)*60*60*24) < $first_occurance)
									continue;

								if($week_start_day + ($d-1)*60*60*24 > $end_unix)
									break;
								$GLOBALS['log']->debug('view.ajaxsave.php for createClone week_start_date='.date("F j, Y, g:i a", $week_start_day));
								//$ret_arr[] = $this->create_clone($bean,$cur_date + ($d)*60*60*24,$jn);
								$ret_arr[] = $this->create_clone($bean, $week_start_day + ($d - 1)*60*60*24, $jn);
							}
						}
					}	
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
								$ret_arr[] = $this->create_clone($bean,$cur_date + $d*60*60*24,$jn);
							}
						}
					$start_day = 0;
					$start_dayM = 0;
				}

				$cur_date += $step;
				
				if($i % $interval != 0)
					continue;
				
				if($cur_date > $end_unix)
					break;
					
				if($rtype == 'Weekly' || $rtype == 'Monthly (day)')
					continue;		

				$ret_arr[] = $this->create_clone($bean,$cur_date,$jn);

			}
		}
	 return $ret_arr;
	
	}
	
	function create_clone(&$bean,$cur_date,$jn){

		$GLOBALS['log']->debug('view.ajaxsave.php create_clone: cur_date formatted='.date("F j, Y, g:i a", $cur_date));

		global $timedate,$sugar_version;
		
		$obj = $this->clone_rec($bean);	

		// patch for sugar 6.1
		if($sugar_version>"6.0")
		{
			$sd_offset = strlen(trim(timestamp_to_user_formated2($cur_date))) < 11 ? false : true;
			print $obj->date_start = $timedate->to_db(timestamp_to_user_formated2($cur_date));
		}
		else
		{
			$obj->date_start = timestamp_to_user_formated2($cur_date);
			$obj->date_end = timestamp_to_user_formated2($cur_date);	
		}

		$GLOBALS['log']->debug('view.ajaxsave.php create_clone: date_start='.$obj->date_start);
		$obj->$jn = $bean->id;
		$obj->save();		
		$obj_id = $obj->id;
		
		$obj->retrieve($obj_id);
				
		$this->invitees_filling($obj);
		
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
	
}
?>
