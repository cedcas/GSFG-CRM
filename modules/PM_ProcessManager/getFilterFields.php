<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
$startTime = microtime(true);
require_once('include/entryPoint.php');
ob_start();
require_once('include/MVC/SugarApplication.php');
$app = new SugarApplication();
$app->startSession();
require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
$processManagerUtil = new ProcessManagerUtils();
$table=$_GET["table"];
$queryFieldList = 'show fields from ' .$table;
$resultFieldList = $processManagerUtil->db->query($queryFieldList, true);
$fields = "<option value=\"Please Specify\">Please Specify</option>";
while($rowFieldList = $processManagerUtil->db->fetchByAssoc($resultFieldList)){
		$fieldName = $rowFieldList['Field'];
		$fields .= "<option value=\"$fieldName\">$fieldName</option>";

}
//Now go and get the Custom Fields

//Now go and see if there are any custom fields for the given module
$customTable = $table .'_cstm';
//get the database name 
global $sugar_config;
$dbname=$sugar_config['dbconfig']['db_name'];
//If we are on windows then we need to set the dbname to lowercase for mysql on windows
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $dbname = strtolower($dbname);
} 
$columnName = 'Tables_in_' .$dbname;
$queryShowTables = 'show tables';
$resultShowTables = $processManagerUtil->db->query($queryShowTables, true);
while($rowShowTables = $processManagerUtil->db->fetchByAssoc($resultShowTables)){
		$tableName = $rowShowTables[$columnName];
		if ($customTable == $tableName) {
			//we have a custom table so go and get the custom fields and add to the field array
			$queryCustomTable = "show fields from $tableName";
			$resultCustomTable = $processManagerUtil->db->query($queryCustomTable, true);
				while($rowCustomTable = $processManagerUtil->db->fetchByAssoc($resultCustomTable)){
					$fieldName = $rowCustomTable['Field'];
    					$fields .= "<option value=\"$fieldName\">$fieldName</option>";
    				}
			}
		}
echo "<div id='DIV_INFO'>
<table width='100%' border='0' cellspacing='1' cellpadding='0'  class='edit view'>
<th align='left' colspan='8'>
<h4>DIV_INFO</h4>
</th>
<tr>
<td valign='top' id='object_fields_label' width='8.33%' scope='row'>
Project Fields:
</td>
<td valign='top' width='25%' ><select name='object_fields' id='object_fields' title='' >";
echo "$fields";
echo "</select>
<td valign='top' id='_label' width='8.33%' scope='row'>
</td>
<td valign='top' width='25%' >
<td valign='top' id='_label' width='8.33%' scope='row'>
</td>
<td valign='top' width='25%' >
</tr>
</table>
</div>";
?>