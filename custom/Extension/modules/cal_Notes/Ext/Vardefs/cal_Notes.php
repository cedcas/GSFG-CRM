<?php

$dictionary["cal_Notes"]["fields"]["cal_notes_meetings"] = array (
  'name' => 'cal_notes_meetings',
  'type' => 'link',
  'relationship' => 'cal_notes_meetings',
  'source' => 'non-db',
  'vname' => 'LBL_CAL_NOTES_MEETINGS_FROM_MEETINGS_TITLE',
);
?>
<?php

$dictionary["cal_Notes"]["fields"]["cal_notes_meetings_name"] = array (
  'name' => 'cal_notes_meetings_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CAL_NOTES_MEETINGS_FROM_MEETINGS_TITLE',
  'save' => true,
  'id_name' => 'cal_notes_0c85eetings_idb',
  'link' => 'cal_notes_meetings',
  'table' => 'meetings',
  'module' => 'Meetings',
  'rname' => 'name',
);
?>
<?php

$dictionary["cal_Notes"]["fields"]["cal_notes_0c85eetings_idb"] = array (
  'name' => 'cal_notes_0c85eetings_idb',
  'type' => 'link',
  'relationship' => 'cal_notes_meetings',
  'source' => 'non-db',
  'reportable' => false,
  'vname' => 'LBL_CAL_NOTES_MEETINGS_FROM_MEETINGS_TITLE',
);
?>
<?php

$dictionary["cal_Notes"]["fields"]["cal_notes_calls"] = array (
  'name' => 'cal_notes_calls',
  'type' => 'link',
  'relationship' => 'cal_notes_calls',
  'source' => 'non-db',
  'vname' => 'LBL_CAL_NOTES_CALLS_FROM_CALLS_TITLE',
);
?>
<?php

$dictionary["cal_Notes"]["fields"]["cal_notes_calls_name"] = array (
  'name' => 'cal_notes_calls_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CAL_NOTES_CALLS_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'cal_notes_f62elscalls_idb',
  'link' => 'cal_notes_calls',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
?>
<?php

$dictionary["cal_Notes"]["fields"]["cal_notes_f62elscalls_idb"] = array (
  'name' => 'cal_notes_f62elscalls_idb',
  'type' => 'link',
  'relationship' => 'cal_notes_calls',
  'source' => 'non-db',
  'reportable' => false,
  'vname' => 'LBL_CAL_NOTES_CALLS_FROM_CALLS_TITLE',
);
?>
