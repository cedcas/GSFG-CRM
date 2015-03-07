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
 *Calendar2ViewAjaxFlyCreate
 * 
 * */
 
require_once('include/MVC/View/SugarView.php');
require_once('modules/Calendar2/Calendar2.php');
require_once("modules/Calendar2/functions.php");

class Calendar2ViewAjaxFlyCreate extends SugarView {
	
 	function Calendar2ViewAjaxFlyCreate(){
 		parent::SugarView();
 	}
 	
 	function process() {
		$this->display();
 	}

 	function display() {

		require_once("modules/Calls/Call.php");
		require_once("modules/Meetings/Meeting.php");
		
		// include Google Sync File
		require_once "modules/Calendar2/gcal_functions.php";
		global $current_user;
		$gcal_sync_mod = $current_user->getPreference('gcal_sync_mod');
		$dosync=0;

		$default_activity = $current_user->getPreference('default_activity');

		if(!isset($default_activity) || $default_activity == 'call'){
			$bean = new Call();
			$type = 'call';
			$jn = "cal2_call_id_c";	
			if($gcal_sync_mod=="call")
				$dosync=1;
		} else {
			$bean = new Meeting();
			$type = 'meeting';
			$jn = "cal2_meeting_id_c";
			if($gcal_sync_mod=="meeting")
				$dosync=1;
		}
			
		if(!$bean->ACLAccess('Save')) {
			$json_arr = array(
				'succuss' => 'no',
			);
			echo json_encode($json_arr);
			die;
		}
		
		if($bean->id=="")
			$bean->old_id_c="";

		$bean->duration_hours = $_REQUEST['duration_hours'];
		$bean->duration_minutes = $_REQUEST['duration_minutes'];
		$bean->assigned_user_id = $GLOBALS['current_user']->id;
		
		$bean->name = $_REQUEST['title'];
		if (isset($_REQUEST['location']) && !empty($_REQUEST['location']) && isset($bean->location)) $bean->location = $_REQUEST['location'];
		$bean->reminder_time = -1;

		//$bean->date_start = isset($_REQUEST['datetime'])? $_REQUEST['datetime'] : "";
		global $sugar_version;
		if($sugar_version>"6.0")
		{
			global $timedate;
			$sd_offset = strlen(trim($_REQUEST['datetime'])) < 11 ? false : true;
			$bean->date_start = $timedate->to_db($_REQUEST['datetime']);		
		}
		else
		{
			$bean->date_start = isset($_REQUEST['datetime'])? $_REQUEST['datetime'] : "";
		}
		$bean->date_end = isset($_REQUEST['date_end'])? $_REQUEST['date_end'] : "";
		
		global $current_user;
		if (isPro()) {
			if (is551()) {
				require_once('modules/Teams/TeamSet.php');
				$bean->team_id = $current_user->default_team;
				$ts = new TeamSet();
				$team_arr = array();
				$team_arr[0] = $current_user->default_team;
				$team_set_id = $ts->addTeams($team_arr);
				$bean->team_set_id = $team_set_id;
			} else {
				$bean->team_id = $current_user->default_team;
			}
		}


		$bean->update_vcal = false;
		$bean->save();
		
		//Add the current user to an attendee's list
		$bean->load_relationship('users');
		$bean->users->add($current_user->id, array('accept_status'=>'accept'));
		//

		// Process Google Calendar
		$gcal_sync_opt = $current_user->getPreference('gcal_sync_opt');
		if(($gcal_sync_opt==1 or $gcal_sync_opt==2) and $dosync==1)
		{
			$GLOBALS['log']->debug('view.ajaxflycreate.php Going to sync Google Calendar bean->id='.$bean->id);
			$bean->retrieve($bean->id);
			// process google on save only when under google settings it is configured as 2 way and 1 way (from calendar2 -> google)
			$gc = new GoogleCalls($bean,'before_save',array('check_notify'=>''));
			$sql = "update ".$bean->table_name." set google_response_c=\"".$bean->google_response_c."\",old_id_c='".$bean->old_id_c."',old_published_c='".$bean->old_published_c."',old_updated_c='".$bean->old_updated_c."',old_link_alt_c='".$bean->old_link_alt_c."',old_link_self_c='".$bean->old_link_self_c."',old_link_edit_c='".$bean->old_link_edit_c."',old_email_c='".$bean->old_email_c."',g_published_c='".$bean->g_published_c."',g_updated_c='".$bean->g_updated_c."' where id='".$bean->id."'";
			$bean->db->query($sql);
		}
		
		$bean->retrieve($bean->id);
		
		if (isset($_REQUEST['contact_id']) && !empty($_REQUEST['contact_id'])) {
			$bean->load_relationship('contacts');
			$bean->contacts->add($_REQUEST['contact_id']);
		}

		if (isset($_REQUEST['account_id']) && !empty($_REQUEST['account_id'])) {
			$bean->load_relationship('accounts');
			$bean->accounts->add($_REQUEST['account_id']);
		}
		
		global $timedate;
		//$date_start = to_db($bean->date_start);
		//$date_unix = to_timestamp($date_start);	
		//$start = $date_unix;

		if($type == 'call') $users = $bean->get_call_users();
		if($type == 'meeting') $users = $bean->get_meeting_users();
		$user_ids = array();
		foreach($users as $u) {
			$user_ids[] = $u->id;
			vCal2::cache_sugar_vcal($u);
		}
		$team_id = "";
		$team_name = "";
		if (isPro()) {
			$team_id = $bean->team_id;
			$team_name = $bean->team_name;
		} else {
			$team_id = "";
			$team_name = "";
		}

		$loc = (isset($bean->location) && !is_null($bean->location)) ? $bean->location : "";

		$start = to_timestamp_from_uf($bean->date_start);

		//shorten time_start for dashlet
		if ($_REQUEST['currentmodule'] == "Home") {
			$temp_time_start = timestamp_to_user_formated2($start,$GLOBALS['timedate']->get_time_format(false));
		} else {
			$temp_time_start = timestamp_to_user_formated2($start,$GLOBALS['timedate']->get_time_format());
		}

		$accept_status = getStatusInHtml("accept");
		ob_clean();
		$arr_rec = array();
		
		$json_arr = array(
			'succuss' => 'yes',
			'record_name' => $bean->name,
			'record' => $bean->id,
			'type' => $type,
			'assigned_user_id' => $bean->assigned_user_id,
			'user_id' => '',
			'user_name' => $bean->assigned_user_name,
			'date_start' => $bean->date_start,
			'start' => $start,
			'time_start' => $temp_time_start,
			'duration_hours' => $bean->duration_hours,
			'duration_minutes' => $bean->duration_minutes,
			'description' => $bean->description,
			'status' => $bean->status,
			'location' => $loc,
			'accept_status' => $accept_status,
			'team_id' => $team_id,
			'team_name' => $team_name,
			'users' => $user_ids,
			'cal2_recur_id_c' => $bean->$jn,
			'cal2_repeat_type_c' => $bean->cal2_repeat_type_c,
			'cal2_repeat_interval_c' => $bean->cal2_repeat_interval_c,
			'cal2_repeat_end_date_c' => $bean->cal2_repeat_end_date_c,
			'cal2_repeat_days_c' => $bean->cal2_repeat_days_c,
			'arr_rec' => $arr_rec,
			'detailview' => 1,
		);
		echo json_encode($json_arr);
		

	}
}
?>
