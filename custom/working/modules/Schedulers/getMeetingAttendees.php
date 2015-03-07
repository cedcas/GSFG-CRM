<?php

$GLOBALS['log']->debug("START - Get Meeting Attendees");
global $sugar_config, $current_user;
$db = DBManagerFactory::getInstance();

$url = $sugar_config['teledirect_api_url_2'];
$username = $sugar_config['teledirect_api_username'];
$password = $sugar_config['teledirect_api_password'];

require('modules/Leads/Lead.php');
$date_today = date("Y-m-d"); 

$query = "
    SELECT
        s.meeting_id,
        s.id,
        v.name,
        s.details_from_date,
        s.details_from_time,
        s.details_venue_address1,
        s.details_venue_city,
        s.details_venue_state,
        s.details_venue_postalcode,
        s.details_capacity
    FROM
        gsf_seminardetails s
    LEFT JOIN
        gsf_venues_minardetails_c sv
    ON
        s.id = sv.gsf_venuesc61bdetails_idb
    LEFT JOIN
        gsf_venues v
    ON
        sv.gsf_venues56d9_venues_ida = v.id
    WHERE
        (s.details_from_date > '".$date_today."' OR s.details_from_date = '".$date_today."') AND
        (s.meeting_id IS NOT NULL OR s.meeting_id <> '') AND
        s.deleted = 0 AND
        sv.deleted = 0

    ";
/*
 * 9/13/2013 - CPC
 * The following query is used to grab leads from specific meeting id;
 */
/*
$query = "
    SELECT
        s.meeting_id,
        s.id,
        v.name,
        s.details_from_date,
        s.details_from_time,
        s.details_venue_address1,
        s.details_venue_city,
        s.details_venue_state,
        s.details_venue_postalcode,
        s.details_capacity
    FROM
        gsf_seminardetails s
    LEFT JOIN
        gsf_venues_minardetails_c sv
    ON
        s.id = sv.gsf_venuesc61bdetails_idb
    LEFT JOIN
        gsf_venues v
    ON
        sv.gsf_venues56d9_venues_ida = v.id
    WHERE
        s.meeting_id in ('228550','228549','228548')

    ";
*/    

$result = $db->query($query, true);

while ($row = $db->fetchByAssoc($result)) {
    
    $urlparams = array(
        'UserName' => $username,
        'Password' => $password,
        'MeetingID' => $row['meeting_id'],
        
    );
    
    $curl_url = $url . "?" . http_build_query($urlparams, '', '&');

    $ch = curl_init($curl_url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);

    $response = curl_exec($ch); 

    if (curl_errno($ch)) {
        $GLOBALS['log']->fatal(curl_error($ch));
    } else {
        curl_close($ch);
    }
    
    $data = new SimpleXMLElement($response);
    
    echo "<pre>";
    print_r($data);

    $attendees = $data->Attendee;

    foreach ($attendees as $attendee) {
    
        $query_existing = "
            SELECT COUNT(id) as total
            FROM leads
            WHERE
                attendee_id = '".$attendee['AttendeeID']."'
            ";
        $result_existing = $db->query($query_existing, true);
        $row_existing = $db->fetchByAssoc($result_existing);
        
        
        if (!$row_existing['total']) {
            //CREATE LEAD
            $lead = new Lead();
            $lead->attendee_id      = $attendee['AttendeeID'];
            $lead->main_attendee_id = $attendee['MainAttendeeID'];
            $lead->first_name       = $attendee->FirstName;
            $lead->last_name        = $attendee->LastName;
            $lead->phone_home       = $attendee->PhoneNumber;
            $lead->email1           = $attendee->Email;
            $lead->primary_address_street   = $attendee->Address;
            $lead->primary_address_city     = $attendee->City;
            $lead->primary_address_state    = $attendee->State;
            $lead->primary_address_postalcode   = $attendee->ZipCode;
            
            if(!empty($attendee->MealSelection)) {
                $lead->description .= $attendee->MealSelection . "\n";
            }
            if (!empty($attendee->Notes)) {
                $lead->description .= $attendee->Notes . "\n";
            }
            if (!empty($attendee->Notes2)) {
                $lead->description .= $attendee->Notes2 . "\n";
            }
            
            $lead->gsf_semina6647details_ida = $row['id'];
            $lead->seminar_venue_name_c      = $row['name'];
            $lead->seminar_date_c            = date('m/d/Y', strtotime($row['details_from_date']));
            $lead->seminar_time_c            = $row['details_from_time'];
            $lead->seminar_address_c         = $row['details_venue_address1'];
            $lead->seminar_city_c            = $row['details_venue_city'];
            $lead->seminar_state_c           = $row['details_venue_state'];
            $lead->seminar_postalcode_c      = $row['details_venue_postalcode'];
            $lead->seminar_capacity          = $row['details_capacity'];
            
            $lead->status = "Registered";
            $lead->lead_source = "Seminar";
            $lead->assigned_user_id = $current_user->id;
            $lead->save();
            unset($lead);
        }
    }
} //end while loop

    

$GLOBALS['log']->debug("END - Get Meeting Attendees");

?>