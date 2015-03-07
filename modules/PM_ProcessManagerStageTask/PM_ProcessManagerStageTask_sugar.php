<?PHP
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004 - 2007 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/
/**
 * THIS CLASS IS GENERATED BY MODULE BUILDER
 * PLEASE DO NOT CHANGE THIS CLASS
 * PLACE ANY CUSTOMIZATIONS IN PM_ProcessManagerStageTask
 */


class PM_ProcessManagerStageTask_sugar extends Basic {
	var $new_schema = true;
	var $module_dir = 'PM_ProcessManagerStageTask';
	var $object_name = 'PM_ProcessManagerStageTask';
	var $table_name = 'pm_processmanagerstagetask';
		var $id;
		var $name;
		var $date_entered;
		var $date_modified;
		var $modified_user_id;
		var $modified_by_name;
		var $created_by;
		var $created_by_name;
		var $description;
		var $deleted;
		var $created_by_link;
		var $modified_user_link;
		var $assigned_user_id;
		var $assigned_user_name;
		var $assigned_user_link;
		var $task_order;
		var $stage_id;
		var $email_template_defs_id;
		var $calls_defs_id;
		var $task_defs_id;
		var $task_type;
		var $start_delay_type;
		var $custom_script;
		var $email_templates;
		var $task_subject;
		var $task_priority;
		var $task_due_date_delay_minutes;
		var $task_due_date_delay_hours;
		var $task_due_date_delay_days;
		var $task_due_date_delay_months;
		var $task_due_date_delay_years;
		var $assigned_user_id_task;
		var $task_description;
		var $call_subject;
		var $reminder_checked;
		var $reminder_time;
		var $call_due_date_delay_minutes;
		var $call_due_date_delay_hours;
		var $call_due_date_delay_days;
		var $call_due_date_delay_months;
		var $call_due_date_delay_years;
		var $assigned_user_id_call;
		var $call_description;
		var $email_template_name;
		var $contact_role;
		var $detail_view_task_subject;
		var $detail_view_task_priority;
		var $detail_view_due_date_delay_minutes;
		var $detail_view_due_date_delay_hours;
		var $detail_view_due_date_delay_days;
		var $detail_view_due_date_delay_months;
		var $detail_view_due_date_delay_years;
		var $detail_view_assigned_user_id_task;
		var $detail_view_call_call_subject;
		var $detail_view_call_reminder_time;
		var $detail_view_call_start_delay_minutes;
		var $detail_view_call_start_delay_hours;
		var $detail_view_call_start_delay_days;
		var $detail_view_call_start_delay_months;
		var $detail_view_call_start_delay_years;
		var $detail_view_call_assigned_user_id_call;
		var $is_escalatable_task;
		var $escalation_delay_minutes_task;
	




	function PM_ProcessManagerStageTask_sugar(){	
		parent::Basic();
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL': return true;
		}
		return false;
}
		
}
?>