<?php

$dictionary["GSF_Withdrawals"]["indices"] = array (
    array('name' => 'idx_withdrawals_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_withdrawals_type', 'type' => 'index', 'fields'=> array('gsf_withdrawal_type')),
    array('name' => 'idx_withdrawals_date', 'type' => 'index', 'fields'=> array('gsf_withdrawal_date')),
);

 ?>