<?php

$dictionary['GSF_Venues']['fields']['billing_address_street']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_city']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_state']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_postalcode']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_country']['required'] = false;


$dictionary["GSF_Venues"]["indices"] = array (
    array('name' => 'idx_venues_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_venues_type', 'type' => 'index', 'fields'=> array('gsf_venues_type')),
);

?>