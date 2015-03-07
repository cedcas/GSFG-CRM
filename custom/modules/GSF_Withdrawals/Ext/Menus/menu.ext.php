<?php 
 //WARNING: The contents of this file are auto-generated

 

if ($_REQUEST['action'] == 'DetailView' || $_REQUEST['action'] == 'EditView')
{
	$view = $_REQUEST['action'];

	$record_id = $_REQUEST['record'];
	
	require_once('data/SugarBean.php');
	$bean = new SugarBean();
	
	$query = "SELECT name FROM gsf_contributions WHERE id = '$record_id' ";
	$results = $bean->db->query($query, true);
	$row = $bean->db->fetchByAssoc($results);
	$parent_name = $row['name'];

	if(ACLController::checkAccess('Documents', 'edit', true))$module_menu[]=Array("index.php?module=Documents&action=EditView&return_module=GSF_Withdrawals&return_action=" . $view . "&gsf_withdrawals_documents_name=" . $parent_name . "&gsf_withdr1ca6drawals_ida=" . $record_id . "&return_id=" . $record_id, "Add Document","CreateTasks");
	
}




?>