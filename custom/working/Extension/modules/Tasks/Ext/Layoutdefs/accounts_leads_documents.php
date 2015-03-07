<?php

$layout_defs["Tasks"]["subpanel_setup"]["accounts_leads_documents"] = array (
    'order' => 100,
    'module' => 'Documents',
    'subpanel_name' => 'forTasks',
    'sort_order' => 'asc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_ACCOUNTS_LEADS_DOCUMENTS_TITLE',
    'get_subpanel_data' => 'function:get_accounts_leads_documents',
    'top_buttons' => array (),
);
