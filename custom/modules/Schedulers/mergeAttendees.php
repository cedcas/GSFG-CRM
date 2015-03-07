<?php

//Job to merge the Lead Spouse to the Main Lead
$GLOBALS['log']->debug("START - Merge Attendees");
$db = DBManagerFactory::getInstance();

require('modules/Leads/Lead.php');

$query = "
    SELECT
        id, first_name, last_name, attendee_id, main_attendee_id
    FROM leads
    WHERE
        deleted = '0' AND
        (attendee_id <> '' OR attendee_id IS NOT NULL) AND
        main_attendee_id <> '0'
    ORDER BY
        attendee_id ASC
    ";

$result = $db->query($query, true);

while ($lead = $db->fetchByAssoc($result)) {
    
    $query_mainattendee = "
        SELECT id
        FROM leads
        WHERE
            attendee_id = '".$lead['main_attendee_id']."'
        ";
    $result_mainattendee = $db->query($query_mainattendee, true);
    $mainattendee = $db->fetchByAssoc($result_mainattendee);
    
    
    if ($mainattendee['id'] != null) {
        
        $mainlead = new Lead();
        $mainlead->retrieve($mainattendee['id']);
        
        if ( (empty($mainlead->spouse_first_name_c) && empty($mainlead->spouse_last_name_c)) &&
             ($mainlead->last_name == $lead['last_name'])
            ) {
            $mainlead->spouse_first_name_c = $lead['first_name'];
            $mainlead->spouse_last_name_c = $lead['last_name'];
            
        } else {
            if (empty($mainlead->description)) {
                $mainlead->description = "Guest:\n" . $lead['first_name'] . " " . $lead['last_name'] . "\n";
            } else {
                $mainlead->description .= "\nGuest:\n" . $lead['first_name'] . " " . $lead['last_name'] . "\n";
            }
        }
        
        $mainlead->save();
        unset($mainlead);
        
        $objlead = new Lead();
        $objlead->mark_deleted($lead['id']);
        unset($objlead);
    }

} //end while loop


$GLOBALS['log']->debug("START - Merge Attendees");

?>