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
require_once('include/nusoap/nusoap.php');
include_once ("modules/Calendar2/googlecal/clsParseXML.php");
include_once ("include/TimeDate.php");
include_once ("modules/Calendar2/googlecal/GoogleCalendarWrapper.php");
require_once('modules/Calendar2/DateTimeUtil.php');

class GoogleCalls 
{

	function GoogleCalls(&$bean, $event, $arguments,$whole_day=0)
	{
		global $timedate;  
		$offset_val=".000+02:00";
		$td = new TimeDate();
		$tablename= $bean->table_name;

		global $calls_prefix,$meetings_prefix,$tasks_prefix;   
		$calls_prefix = "";
		$meetings_prefix = "";
		$tasks_prefix = "";

		$s = array();

		$s["title"] = $bean->name;
		if ($tablename=='meetings'){
			$s["title"] = $meetings_prefix.$s["title"];
		}
		if ($tablename=='calls'){
			$s["title"] = $calls_prefix.$s["title"];
		}
		if ($tablename=='tasks'){
			$s["title"] = $tasks_prefix.$s["title"];
		}

		$s["content"] = $bean->description;
		$s["where"] = $bean->location;
		$s["startDay"] = $bean->date_start;
		$s["startTime"] = $bean->time_start;
		$s["endDay"] = $bean->date_end;
		$s["reminder_time"]=$bean->reminder_time/60;
		if ($s["reminder_time"]=='' or $s["reminder_time"]==0){
			$s["reminder_time"] = 10;
		}

		if ($bean->date_start=='' and $bean->date_due!=""){
			$s["startDay"] = $bean->date_due;  #### for tasks if no start date
		}else{
			$s["startDay"]    = $bean->date_start;
		}
		$s["startDay"] = $td->to_display_date_time($s["startDay"]);
		$s["endDay"] = $td->to_display_date_time($s["endDay"]);
		
		global $current_user;
		$pd =   $s["startDay"];
		//OSC 2011.3.15 chaging to db format
		$date_start_in_db_fmt=$s["startDay"];
//		$date_start_in_db_fmt=$td->swap_formats($bean->date_start, $td->get_date_time_format(), $td->get_db_date_time_format());
		//OSC End

		//OSC 2011.3.15 to avoid a notice error
		//$date_end_in_db_fmt1=$bean->date_due;
		$date_end_in_db_fmt1=(isset($bean->date_due))? $bean->date_due:$bean->date_end;
		//OSC End

		$date_start_array=split(" ",trim($date_start_in_db_fmt));
		//$date_time_start =DateTimeUtil2::get_time_start($date_start_array[0],$date_start_array[1]);
		//OSC 2011.3.15 to avoid a notice error
		//$tt1= $td->to_display_date_time($bean->date_start,true,true,false);
		$tt1= $td->to_display_date_time($bean->date_start,true,true);
		//OSC End

		$user=$current_user;
		$s["startTime"]=   $td->handle_offset($date_start_in_db_fmt, "H:i:s", false,$current_user);
		$date_end_in_db_fmt1=   $td->handle_offset($date_end_in_db_fmt1, "Y-m-d H:i:s", false,$current_user);

		$ch = explode(" ",$s["startDay"]);
		if ($ch['1']=="00:00" or $ch['1']=="00.00"){
			$s["startDay"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d", false,$current_user);   
			$s["startDay1"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d H:i:s", false,$current_user);
		}else{
			$s["startDay"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d", false,$current_user);  
			$s["startDay1"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d H:i:s", false,$current_user);  
		}
		$plugin_format = "d/m/Y H:i";

		if ($td->get_date_time_format(true, $current_user)!=$plugin_format){
			//OSC 2011.3.15 to avoid a notice error
			//$plugin_date_due=$td->swap_formats($bean->date_due, $td->get_date_time_format(true, $current_user),  $plugin_format);
			$plugin_date_due=$td->swap_formats($date_end_in_db_fmt1, $td->get_date_time_format(true, $current_user),  $plugin_format);
			//OSC End
		}else{
			$plugin_date_due=$bean->date_due;
		}
		$plugin_date_start=$td->swap_formats($s["startDay1"], "Y-m-d H:i:s",  $plugin_format);  
		$plugin_date_due1 = $td->swap_formats($date_end_in_db_fmt1, "Y-m-d H:i:s",  $plugin_format);   

		$d1 = explode("/",$plugin_date_start);
		global $current_user;
		$d1[2]=explode(" ",$d1[2]);
		$d1[2]=$d1[2][0];
		if ($bean->time_start!='' and 1==2){
			$t1 = explode(":",$bean->time_start);
		}else{
			$fort1 = explode(" ",$plugin_date_start)  ;
			$fort1 = $fort1[1];
			$t1 = explode(":",$fort1);
		}
		$t1[1] = round($t1[1]);
		//OSC 2011.3.15 to avoid a notice error
		if (!isset($t1[2])) $t1[2] = 0;
		//OSC End
		$untill  = mktime($t1[0]+$bean->duration_hours,$t1[1]+$bean->duration_minutes, $t1[2], $d1[1], $d1[0], $d1[2]); 

		if ($tablename=='tasks'){
			if ($plugin_date_due=="" or $bean->date_due==""){
				$forexp = $plugin_date_start;
			}else{
				$forexp = $plugin_date_due;   
				$forexp=$plugin_date_due1;
			}
			$d1d = explode("/",$forexp);
			$d1d[2]=explode(" ",$d1d[2]);
			$d1d[2]=$d1d[2][0];
			$fort1d = explode(" ",$forexp)  ;
			$fort1d = $fort1d[1];
			$t1d = explode(":",$fort1d);   
			$untill  = mktime($t1d[0],$t1d[1], $t1d[2], $d1d[1]  , $d1d[0], $d1d[2]); 
		}

		$date_due=date("Y-m-d", $untill);
		$time_due=date("H:i:s", $untill);
		$s["endTime"] = $time_due;
		$s["endDay"] = $date_due;
		
		if ($bean->assigned_user_id==''){
			$userid=$bean->created_by;
		}else{
			$userid=$bean->assigned_user_id;
		}

		$gcal_username = $current_user->getPreference('gcal_username');
		$gcal_password = $current_user->getPreference('gcal_password');

		if ($gcal_username != '' and $gcal_password != '')
		{
			if($whole_day==1)
			{
				$s["endTime"]="";
				$s["startTime"]="";
			}
		
			$assigned_link = "http://www.google.com/calendar/feeds/default/private/full";
			$gc = new GoogleCalendarWrapper($gcal_username, $gcal_password,"",$assigned_link);
			global $current_user;

			$doevent = false;
			$add_notifications="on";
			if($event=="before_delete")
			{
				$gc->delete_event($s,$bean->id,$tablename,array($gcal_username,$gcal_password),$bean,$add_notifications);  
			}
			else
				$gc->add_event_onl($s,$bean->id,$tablename,array($gcal_username,$gcal_password),$bean,$add_notifications);
		}
	}
}

?>