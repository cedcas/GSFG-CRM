<?php
$dictionary['User']['fields']['name_kreport'] = array(
    'name' => 'name_kreport',
    'vname' => 'LBL_NAME',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => 'CONCAT($.first_name, \' \', $.last_name)',
);

 ?>