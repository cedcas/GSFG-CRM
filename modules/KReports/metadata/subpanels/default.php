<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


$subpanel_layout = array(
	'top_buttons' => array(
			array (
	 		 'widget_class'=>'SubPanelTopSelectButton', 'popup_module' => 'KReports'
				),
	),		
	'list_fields' => array(

		'name'=>array(
			 'vname' => 'LBL_NAME',
			 //'widget_class' => 'SubPanelDetailViewLink',
			 'width' => '30%',
		),
		'report_module'=>array(
			 'vname' => 'LBL_MODULE',
			 'width' => '15%',
		),
		'report_status'=>array(
			 'vname' => 'LBL_REPORT_STATUS',
			 'width' => '15%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			 'widget_class' => 'SubPanelRemoveButton',
			 'width' => '2%',
		),
	),
);		
?>
