<?php
require_once('include/formbase.php');
require_once('modules/PM_ProcessManager/PM_ProcessManager.php');
require_once('log4php/LoggerManager.php');
require_once('data/SugarBean.php');
global $current_user;
$thisSugarBean = new SugarBean();
$focus = new PM_ProcessManager();
$focus->retrieve($_POST['record']);
//Now update the record with the name
//Now see if there any filter table entries and if so then add
$return_id = $focus->id;
$queryDelete = "Delete from pm_process_filter_table where process_id = '$return_id'";
$focus->db->query($queryDelete, true);
//Now redirect to Detail View Page
handleRedirect($return_id, "PM_ProcessManager");
//Check to see if the entry in the process filter table already exists
?>