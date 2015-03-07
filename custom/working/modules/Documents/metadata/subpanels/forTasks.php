<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


$subpanel_layout = array(
    'top_buttons' => array(),

    'where' => '',
    
    'list_fields'=> array(
        
        'document_name'=> array(
            'name' => 'document_name',
            'vname' => 'LBL_LIST_DOCUMENT_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '30%',
        ),
        'date_entered'=>array(
                'name' => 'date_entered',
                'vname' => 'LBL_DATE_ENTERED',
                'width' => '10%',
        ),
    ),
);
?>