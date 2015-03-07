<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2011-04-08 15:26:55

 

 // created: 2011-04-08 15:26:36

 

 // created: 2011-04-08 15:32:36

 

 // created: 2011-04-08 15:27:34

 

 // created: 2011-04-08 16:05:14

 

 // created: 2011-04-08 15:31:51

 

 // created: 2011-04-08 15:32:13

 

// created: 2011-11-09 23:46:15
$dictionary["User"]["fields"]["gsf_seminardetails_users"] = array (
  'name' => 'gsf_seminardetails_users',
  'type' => 'link',
  'relationship' => 'gsf_seminardetails_users',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SEMINARDETAILS_USERS_FROM_GSF_SEMINARDETAILS_TITLE',
);


$dictionary['User']['fields']['name_kreport'] = array(
    'name' => 'name_kreport',
    'vname' => 'LBL_NAME',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => 'CONCAT($.first_name, \' \', $.last_name)',
);

 
?>