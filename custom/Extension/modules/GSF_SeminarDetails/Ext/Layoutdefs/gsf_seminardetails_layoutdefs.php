<?php

$layout_defs['GSF_SeminarDetails']['subpanel_setup']['users'] = array(
    'order' => 200,
    'sort_by' => 'user_name',
    'sort_order' => 'asc',
    'module' => 'Users',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'users',
    'add_subpanel_data' => 'user_id',
    'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'Users',
            'mode' => 'MultiSelect',
            //used only as flag to filter the Users popup list
            //see custom\modules\Users\metadata\popupdefs.php
            'initial_filter_fields' => array('name' => 'description'),
        ),
    ),
);

$layout_defs['GSF_SeminarDetails']['subpanel_setup']['gsf_contributions'] = array(
    'order' => 10,
    'sort_by' => 'name',
    'sort_order' => 'asc',
    'module' => 'GSF_Contributions',
    'subpanel_name' => 'Accountdefault',
    'get_subpanel_data' => 'gsf_contributions_link',
    'add_subpanel_data' => 'gsf_contributions_id',
    'title_key' => 'LBL_GSF_CONTRIBUTIONS_SUBPANEL_TITLE',
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
    ),
);
