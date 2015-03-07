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
 *Calendar2ViewAjaxRemove
 * 
 * */
 
require_once('include/MVC/View/SugarView.php');

class Calendar2ViewAjaxRemove extends SugarView {
	
 	function Calendar2ViewAjaxRemove(){
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
		
		include_once('modules/Calendar2/calendar.class.php');
		$caldav_url = $current_user->getPreference('caldav_url'); // Caldav: Get url
		$caldav_username = $current_user->getPreference('caldav_username'); // Caldav: Get username
		$caldav_password = $current_user->getPreference('caldav_password'); // Caldav: Get password		
		
		// Caldav: Get user prefernce for caldav sync
		$caldav_sync_opt = $current_user->getPreference('caldav_sync_opt');
		$caldav_prioriy = $current_user->getPreference('caldav_prioriy');	
		$caldav_sync_mod = $current_user->getPreference('caldav_sync_mod');
		$caldav_time_slot = $current_user->getPreference('caldav_time_slot');			
		
		// Google: Get user preference for sync module
		$gcal_sync_mod = $current_user->getPreference('gcal_sync_mod');
		$dosync=0;
		
		$GLOBALS['log']->debug('AjaxRemove starts');
		$GLOBALS['log']->debug('AjaxRemove cur_module='.$_REQUEST['cur_module']);
		

		if($_REQUEST['cur_module'] == 'Calls'){
			$bean = new Call();
			$table_name = $bean->table_name;
			$jn = "cal2_call_id_c";
			if($gcal_sync_mod=="call")//  Google: if sync module selected as Call then go for sync process
				$dosync=1;
		}
		if($_REQUEST['cur_module'] == 'Meetings'){
			$bean = new Meeting();
			$table_name = $bean->table_name;
			$jn = "cal2_meeting_id_c";
			if($gcal_sync_mod=="meeting") //  Google: if sync module selected as Meeting then go for sync process
				$dosync=1;
		}

		$bean->retrieve($_REQUEST['record']);

		if(!$bean->ACLAccess('Save')){
			die;	
		}

		$GLOBALS['log']->debug('AjaxRemove cal2_repeat_type_c = '.$bean->cal2_repeat_type_c);
		$GLOBALS['log']->debug('AjaxRemove edit_all_recurrence = '.$_REQUEST['edit_all_recurrence']);
		$GLOBALS['log']->debug('AjaxRemove delete_recurring = '.$_REQUEST['delete_recurring']);
		$GLOBALS['log']->debug('AjaxRemove delete_first_recurring = '.$_REQUEST['delete_first_recurring']);
		
		// caldav sync begin
		if($caldav_sync_opt==1 or $caldav_sync_opt==2)
		{
			$caldav_obj = new CalendarCaldav($caldav_url, $caldav_username, $caldav_password);
			if($bean->caldav_uid_c!='')
				$caldav_obj->delete($bean->caldav_uid_c.'.ics');
		}
		// calldav sync end
		
		// Google: Process Google Calendar		
		$gcal_sync_opt = $current_user->getPreference('gcal_sync_opt');
		if(($gcal_sync_opt==1 or $gcal_sync_opt==2) and $dosync==1)
		{
			// Delete event from google
			$gc = new GoogleCalls($bean,'before_delete',array('check_notify'=>''));
		}		

		// Call2 End		

		//delete_recurring: true if a deleted meeting is among recurrrd ones
		//delete_first_recurring: true if a deleted meeting is the 1st one among recurred ones
		//no_recurrence: true if an user does not want to edit all of recurred events
		if ($_REQUEST['edit_all_recurrence'] == true) {
		//delete all of recurred events except for the 1st one (which is deleted later)
			$query = "SELECT * from ".$table_name." t WHERE t.deleted = 0 AND t.".$jn." = '".$bean->id."'";
			$GLOBALS['log']->debug('AjaxRemove delete_recurring query= '.$query);
			$result = $bean->db->query($query, true, "Error retrieveing all of recurred records in AjaxRemove: ");
			while($row = $bean->db->fetchByAssoc($result)) {
				if($_REQUEST['cur_module'] == 'Meetings'){
					$recurred_mtg = new Meeting();
				} else {
					$recurred_mtg = new Call();
				}
				$recurred_mtg->retrieve($row['id']);			
				// Process Google Calendar		
				$gcal_sync_opt = $current_user->getPreference('gcal_sync_opt');
				if(($gcal_sync_opt==1 or $gcal_sync_opt==2) and $dosync==1)
				{
					// process google on save only when under google settings it is configured as 2 way and 1 way (from calendar2 -> google)
					$gc = new GoogleCalls($recurred_mtg,'before_delete',array('check_notify'=>''));
				}
			
				$recurred_mtg->mark_deleted($row['id']);
				$GLOBALS['log']->debug('AjaxRemove edit_all deleting recurred '.$row['id']);
			}
			//delete the primary record
			$bean->mark_deleted($bean->id);
			$GLOBALS['log']->debug('AjaxRemove edit_all deleting '.$bean->id);
		} elseif($_REQUEST['delete_recurring'] == true){
		//just delete this record
			$bean->mark_deleted($bean->id);
			$GLOBALS['log']->debug('AjaxRemove deleting'.$bean->id);
		}
		
		

		//updating vCals
		$userInvitees = array();
		if(isset($_POST['user_invitees']) && !empty($_POST['user_invitees'])) {
			global $current_user;
			$userInvitees = explode(',', trim($_POST['user_invitees'], ','));
			foreach($userInvitees as $user_id) {
				//Updates each user's vCal
				if($user_id == $current_user->id) continue;
				require_once('modules/Users/User.php');
				$temp_usr = new User();
				$temp_usr->retrieve($user_id);
				vCal::cache_sugar_vcal($temp_usr);
			}
		}

		$resourcesAssigned = array();
		if(isset($_POST['resources_assigned']) && !empty($_POST['resources_assigned'])) {
			$resourcesAssigned = explode(',', trim($_POST['resources_assigned'], ','));
			require_once('modules/Resources/Resource.php');
			foreach($resourcesAssigned as $res_id) {
				$temp_res = new Resource();
				$temp_res->retrieve($res_id);
				vCal2::cache_sugar_vcal($res);
			}
		}
	}
}
?>
