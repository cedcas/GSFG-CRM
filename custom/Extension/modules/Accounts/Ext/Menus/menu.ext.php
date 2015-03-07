<?php 

if ($_REQUEST['action'] == 'DetailView' || $_REQUEST['action'] == 'EditView')
{
	$view = $_REQUEST['action'];

	$record_id = $_REQUEST['record'];
	
	require_once('data/SugarBean.php');
	$bean = new SugarBean();
	
	$query = "SELECT name FROM accounts WHERE id = '$record_id' ";
	$results = $bean->db->query($query, true);
	$row = $bean->db->fetchByAssoc($results);
	$parent_name = $row['name'];

	if(ACLController::checkAccess('GSF_SourceAccounts', 'edit', true))$module_menu[]=Array("index.php?module=GSF_SourceAccounts&action=EditView&return_module=Accounts&return_action=" . $view . "&accounts_gsf_sourceaccounts_name=" . $parent_name . "&accounts_g2316ccounts_ida=" . $record_id . "&return_id=" . $record_id, "Add Source Account","CreateTasks");

	if(ACLController::checkAccess('GSF_Contributions', 'edit', true))$module_menu[]=Array("index.php?module=GSF_Contributions&action=EditView&return_module=Accounts&return_action=". $view . "&accounts_gsf_contributions_name=" . $parent_name . "&accounts_g813cccounts_ida=" . $record_id . "&return_id=" . $record_id, "Add Contribution","CreateTasks");

	if(ACLController::checkAccess('GSF_Withdrawals', 'edit', true))$module_menu[]=Array("index.php?module=GSF_Withdrawals&action=EditView&return_module=Accounts&return_action=". $view . "&accounts_gsf_withdrawals_name=" . $parent_name . "&accounts_ge7aaccounts_ida=" . $record_id . "&return_id=" . $record_id, "Add Withdrawal","CreateTasks");

	
}


?>
