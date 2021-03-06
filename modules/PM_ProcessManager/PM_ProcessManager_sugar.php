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
 * PLACE ANY CUSTOMIZATIONS IN PM_ProcessManager
 */


class PM_ProcessManager_sugar extends Basic {
	var $new_schema = true;
	var $module_dir = 'PM_ProcessManager';
	var $object_name = 'PM_ProcessManager';
	var $table_name = 'pm_processmanager';
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
		var $status;
		var $process_object;
		var $start_event;
		var $cancel_on_event;
		var $process_object_cancel_field;
		var $process_object_cancel_field_value;
		var $contacts_fields;
		var $accounts_fields;
		var $leads_fields;
		var $cases_fields;
		var $opportunities_fields;
		var $bugs_fields;
		var $process_filter_field1;
		var $filter_list1;
		var $process_object_field1_value;
		var $process_filter_field2;
		var $filter_list2;
		var $process_object_field2_value;
		var $process_filter_field3;
		var $filter_list3;
		var $process_object_field3_value;
		var $process_filter_field4;
		var $filter_list4;
		var $process_object_field4_value;
		var $process_filter_field5;
		var $filter_list5;
		var $process_object_field5_value;
		var $detail_view_field1;
		var $detail_view_value1;
		var $detail_view_operator1;
		var $detail_view_field2;
		var $detail_view_value2;
		var $detail_view_operator2;
		var $detail_view_field3;
		var $detail_view_value3;
		var $detail_view_operator3;
		var $detail_view_field4;
		var $detail_view_value4;
		var $detail_view_operator4;
		var $detail_view_field5;
		var $detail_view_value5;
		var $detail_view_operator5;
		var $detail_view_process_object_cancel_field;
		var $detail_view_process_object_cancel_field_value;
	




	function PM_ProcessManager_sugar(){	
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