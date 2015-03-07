<?php
/*********************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed 
 * by KINAMU Business Solutions AG. All rights ar (c) 2010 by KINAMU Business
 * Solutions AG.
 *
 * This Version of the KReporter is licensed software and may only be used in 
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of KINAMU Business Solutions AG
 * 
 * You can contact KINAMU Business Solutions AG at Am Concordepark 2/F12
 * A-2320 Schwechat or via email at office@kinamu.com
 * 
 ********************************************************************************/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$listViewDefs['KReports'] = array(
    'NAME' => array(
        'width' => '40', 
        'label' => 'LBL_LIST_SUBJECT', 
        'link' => true,
        'default' => true),

    'REPORT_MODULE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_MODULE', 
        'link' => false,
        'default' => true),        

    'ASSIGNED_USER_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_ASSIGNED_USER_NAME', 
        'link' => false,
        'default' => true),	
);
?>
