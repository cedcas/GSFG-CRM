<?php

$dictionary["GSF_SourceAccounts"]["indices"] = array (
    array('name' => 'idx_sourceaccounts_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_sourceaccounts_status', 'type' => 'index', 'fields'=> array('source_tax_status')),
);

?>