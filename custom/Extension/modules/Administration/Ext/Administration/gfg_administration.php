<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$admin_option_defs = array();

$admin_option_defs['Campaigns']['campaigns'] = array(
    'Campaigns', //icon
    'LBL_CAMPAIGNS_TITLE',
    'LBL_CAMPAIGNS_DESCRIPTION',
    './index.php?module=Campaigns&action=index&return_module=Administration&return_action=index'
);

$admin_option_defs['GSF_Seminars']['gsf_seminars'] = array(
    'Documents', //icon
    'LBL_GSF_SEMINARS_TITLE',
    'LBL_GSF_SEMINARS_DESCRIPTION',
    './index.php?module=GSF_Seminars&action=index&return_module=Administration&return_action=index'
);

$admin_option_defs['GSF_SeminarDetails']['gsf_seminardetails'] = array(
    'Tasks', //icon
    'LBL_GSF_SEMINARDETAILS_TITLE',
    'LBL_GSF_SEMINARDETAILS_DESCRIPTION',
    './index.php?module=GSF_SeminarDetails&action=index&return_module=Administration&return_action=index'
);

$admin_option_defs['GSF_Venues']['gsf_venues'] = array(
    'GSF_Venues', //icon
    'LBL_GSF_VENUES_TITLE',
    'LBL_GSF_VENUES_DESCRIPTION',
    './index.php?module=GSF_Venues&action=index&return_module=Administration&return_action=index'
);

$admin_option_defs['PM_ProcessManager']['pm_processmanager'] = array(
    'PM_ProcessManager', //icon
    'LBL_PM_PROCESSMANAGER_TITLE',
    'LBL_PM_PROCESSMANAGER_DESCRIPTION',
    './index.php?module=PM_ProcessManager&action=index&return_module=Administration&return_action=index'
);

//$admin_group_header[] = array(
$gfg_group_header = array(
    'LBL_GFG_ADMINISTRATION',
    '',
    false,
    $admin_option_defs,
    'LBL_GFG_ADMINISTRATION_DESCRIPTION'
);

//put the GFG Administration panel on the top of the Administration Page (first element of the array)
array_unshift($admin_group_header, $gfg_group_header);
?>