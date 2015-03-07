<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2011-03-19 17:14:19
$dictionary["GSF_Venues"]["fields"]["gsf_venues_gsf_seminardetails"] = array (
  'name' => 'gsf_venues_gsf_seminardetails',
  'type' => 'link',
  'relationship' => 'gsf_venues_gsf_seminardetails',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_GSF_VENUES_GSF_SEMINARDETAILS_FROM_GSF_SEMINARDETAILS_TITLE',
);



$dictionary['GSF_Venues']['fields']['billing_address_street']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_city']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_state']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_postalcode']['required'] = true;
$dictionary['GSF_Venues']['fields']['billing_address_country']['required'] = false;


$dictionary["GSF_Venues"]["indices"] = array (
    array('name' => 'idx_venues_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_venues_type', 'type' => 'index', 'fields'=> array('gsf_venues_type')),
);



 // created: 2011-11-21 14:02:44
$dictionary['GSF_Venues']['fields']['venue_logo']['default']='http://crm.goldstonefinancialgroup.com/custom/logos/{venue_logo_filename}';

 
?>