<?php 
 //WARNING: The contents of this file are auto-generated

 

if ($_REQUEST['action'] == 'DetailView' || $_REQUEST['action'] == 'EditView')
{
	$view = $_REQUEST['action'];

	$record_id = $_REQUEST['record'];
	
	require_once('data/SugarBean.php');
	$bean = new SugarBean();
	
	$query = "SELECT name FROM gsf_seminars WHERE id = '$record_id' ";
	$results = $bean->db->query($query, true);
	$row = $bean->db->fetchByAssoc($results);
	$parent_name = $row['name'];

	if(ACLController::checkAccess('GSF_SeminarDetails', 'edit', true))$module_menu[]=Array("index.php?module=GSF_SeminarDetails&action=EditView&return_module=GSF_Seminars&return_action=" . $view . "&gsf_seminars_gsf_seminardetails_name=" . $parent_name . "&gsf_seminac629eminars_ida=" . $record_id . "&return_id=" . $record_id, "Add Seminar Details","CreateTasks");
	
}




?>