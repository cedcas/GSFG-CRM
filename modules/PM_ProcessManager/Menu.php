<?php 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * Copyright: SierraCRM, Inc. 2007
 * Portions created by SierraCRM are Copyright (C) SierraCRM, Inc.
 * The contents of this file are subject to the SierraCRM, Inc. End User License Agreement
 * You may not use this file except in compliance with the License. 
 * You may not rent, lease, lend, or in any way distribute or transfer any rights or this file or Process Manager
 * registrations (purchased licenses) to third parties without SierraCRM, Inc. written approval, and subject to
 * agreement by the recipient of the terms of this EULA.
 * Process Manager for SugarCRM is owned by SierraCRM, Inc. and is protected by international and local copyright laws and
 * treaties. You must not remove or alter any copyright notices on any copies of Process Manager for SugarCRM. 
 * You may not use, copy, or distribute Process Manager for SugarCRM, except as granted by SierraCRM, Inc.
 * without written authorization from SierraCRM, Inc. or its designated agents. Furthermore, this Copyright notice
 * does not grant you any rights in connection with any trademarks or service marks of SierraCRM, Inc. 
 * SierraCRM, Inc. reserves all intellectual property rights, including copyrights, and trademark rights of this software.
 ********************************************************************************/
/*********************************************************************************
 *SierraCRM, Inc
 *14563 Ward Court
 *Grass Valley, CA. 95945
 *www.sierracrm.com
 ********************************************************************************/

global $mod_strings, $app_strings, $sugar_config;
$module_menu = Array(
	$module_menu[]=Array("index.php?module=PM_ProcessManager&action=EditView&return_module=PM_ProcessManager&return_action=DetailView", $mod_strings['LBL_CREATE_PROCESS'],"create_process"),
	$module_menu[]=Array("index.php?module=PM_ProcessManager&action=index&return_module=PM_ProcessManager&return_action=index", $mod_strings['LBL_LIST_PROCESSES'],"list_processes"),
	$module_menu[]=Array("index.php?module=PM_ProcessManagerStage&action=EditView&return_module=PM_ProcessManagerStage&return_action=DetailView", $mod_strings['LBL_CREATE_PROCESS_STAGE'],"create_stage"),
	$module_menu[]=Array("index.php?module=PM_ProcessManagerStage&action=index&return_module=PM_ProcessManagerStage&return_action=index", $mod_strings['LBL_LIST_PROCESS_STAGES'],"list_stage"),
	$module_menu[]=Array("index.php?module=PM_ProcessManagerStageTask&action=EditView&return_module=PM_ProcessManagerStageTask&return_action=DetailView", $mod_strings['LBL_CREATE_PROCESS_TASK'],"create_task"),
	$module_menu[]=Array("index.php?module=PM_ProcessManagerStageTask&action=index&return_module=PM_ProcessManagerStageTask&return_action=index", $mod_strings['LBL_LIST_PROCESS_TASK'],"list_task"),
	$module_menu[]=Array("index.php?module=PM_ProcessManager&action=RunProcessManager&isRunningFromMenu=true", 'Run Process Manager',"run_process"),
	);
?>
