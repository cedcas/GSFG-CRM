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
require_once('modules/PM_ProcessManager/ProcessManagerEngine.php');
global $runningProcessManager;
global $isRunningFromMenu;
$isRunningFromMenu = $_REQUEST['isRunningFromMenu'];

$processManagerEngine = new ProcessManagerEngine();
$processManagerEngine->processManagerMain();
header("Location: index.php?action=index&module=PM_ProcessManager");

?>