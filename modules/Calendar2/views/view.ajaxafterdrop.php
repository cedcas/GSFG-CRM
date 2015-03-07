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
 *Calendar2ViewAjaxAfterDrop
 * 
 * */
 
require_once('include/MVC/View/SugarView.php');
require_once('modules/Calendar2/Calendar2.php');

class Calendar2ViewAjaxAfterDrop extends SugarView {
	
 	function Calendar2ViewAjaxAfterDrop(){
 		parent::SugarView();
 	}
 	
 	function process() {
		$this->display();
 	}

 	function display() {

		require_once("modules/Calls/Call.php");
		require_once("modules/Meetings/Meeting.php");
		
		// Cal2 Modified for google 2010/09/16
		
		// Google: include Google Sync File
		require_once "modules/Calendar2/gcal_functions.php";
		global $current_user;
		global $timedate;
		
		// Google:  Get User Preference
		$gcal_sync_mod = $current_user->getPreference('gcal_sync_mod');
		$dosync=0;		

		if($_REQUEST['type'] == 'call')
		{
			$bean = new Call();
			if($gcal_sync_mod=="call") //  Google: if sync module selected as Call then go for sync process
				$dosync=1;
		}	
		if($_REQUEST['type'] == 'meeting') //  Google: if sync module selected as Meeting then go for sync process
		{
			$bean = new Meeting();
			if($gcal_sync_mod=="meeting")
				$dosync=1;
		}	

		$bean->retrieve($_REQUEST['record']);
		
		if($bean->id=="")
			$bean->old_id_c="";		
		
		if(!$bean->ACLAccess('Save')){
			die;	
		}

		global $sugar_version;
		if($sugar_version>"6.0") {
			$sd_offset = strlen(trim($_REQUEST['datetime'])) < 11 ? false : true;
			$bean->date_start = $timedate->to_db($_REQUEST['datetime']);
		} else {
			$bean->date_start = isset($_REQUEST['datetime'])? $_REQUEST['datetime'] : "";
		}
		$bean->date_end = isset($_REQUEST['date_end'])? $_REQUEST['date_end'] : "";
		//$bean->date_end = $_REQUEST['date_end'];

		//vCal is updated later
		$bean->update_vcal = false;
		$bean->save();
		
		//  Google: Process Google Calendar
		$gcal_sync_opt = $current_user->getPreference('gcal_sync_opt');
		if(($gcal_sync_opt==1 or $gcal_sync_opt==2) and $dosync==1)
		{
			$whole_day=0;
			// Google: Get user preference of time settings
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
			if($minute_end < $minute_start)
			{
				$day_duration_hours--;
				$day_duration_minutes = $minute_start - $minute_end;
			}
			else
				$day_duration_minutes = $minute_end - $minute_start;
		
			if($bean->duration_hours==$day_duration_hours and $bean->duration_minutes==$day_duration_minutes)
			{
				$whole_day=1;
				$bean->retrieve($bean->id); //  Google: In case of whole day event, system should clear all time zone conversions by sugar. so retriving data again
			}		
			
			//  Google: process google on save only when under google settings it is configured as 2 way and 1 way (from calendar2 -> google)
			$gc = new GoogleCalls($bean,'before_save',array('check_notify'=>''),$whole_day);
			
			//  Google: update google response in table
			$sql = "update ".$bean->table_name." set google_response_c=\"".$bean->google_response_c."\",old_id_c='".$bean->old_id_c."',old_published_c='".$bean->old_published_c."',old_updated_c='".$bean->old_updated_c."',old_link_alt_c='".$bean->old_link_alt_c."',old_link_self_c='".$bean->old_link_self_c."',old_link_edit_c='".$bean->old_link_edit_c."',old_email_c='".$bean->old_email_c."',g_published_c='".$bean->g_published_c."',g_updated_c='".$bean->g_updated_c."' where id='".$bean->id."'";
			$bean->db->query($sql);
		}		

		// Call2 End

		//updating vCal
		$userlist = array();
		if($_REQUEST['type'] == 'call') {
			$userlist = $bean->get_call_users();
		} else {
			$userlist = $bean->get_meeting_users();
		}
		$user_ids = array();
		foreach($userlist as $u) {
			$user_ids[] = $u->id;
			vCal2::cache_sugar_vcal($u);
		}

		require_once('modules/Resources/Resource.php');
		$bean->load_relationship('resources');
		$reslist = array();
		$reslist = $bean->resources->get(false);
		foreach($reslist as $r) {
			$res = new Resource();
			$res->retrieve($r);
			$user_ids[] = $res->id;
			vCal2::cache_sugar_vcal($res);
		}

		$json_arr = array(
			'succuss' => 'yes',
			'users' => $user_ids,
		);
		echo json_encode($json_arr);
	}
}
?>
