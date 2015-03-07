<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class Leads_Hook
{
    
    function set_seminar_title(&$bean, $event, $arguments) {
        
         if (!empty($bean->gsf_semina6647details_ida)) {
            require_once('modules/GSF_SeminarDetails/GSF_SeminarDetails.php');
            $seminardetails = new GSF_SeminarDetails();
            $seminardetails->retrieve($bean->gsf_semina6647details_ida);
            
            if (!empty($seminardetails->gsf_seminac629eminars_ida)) {
                require_once('modules/GSF_Seminars/GSF_Seminars.php');
                $seminars = new GSF_Seminars();
                $seminars->retrieve($seminardetails->gsf_seminac629eminars_ida);
                
                $bean->seminar_title = $seminars->seminar_title;
            }
            
            if (!empty($seminardetails->gsf_venues56d9_venues_ida)) {
                require_once('modules/GSF_Venues/GSF_Venues.php');
                $venue = new GSF_Venues();
                $venue->retrieve($seminardetails->gsf_venues56d9_venues_ida);
                
                $filepath = str_replace("{venue_logo_filename}", $venue->venue_logo_filename, $venue->venue_logo);
                $bean->venue_logo_img = '<p><img src="'.$filepath.'" alt="Venue Logo" width="100" height="100" /></p>';
                $bean->venue_logo_filename = $venue->venue_logo_filename;
            }
            
            
            
            $before = date("Y-m-d H:i:s", strtotime($seminardetails->details_from_time)); 
            $before_stamp = strtotime($before);
            $minus = strtotime('-15 minute', $before_stamp);
            $before_time = date("g:i A", $minus);
            
            $after = date("Y-m-d H:i:s", strtotime($seminardetails->details_from_time)); 
            $after_stamp = strtotime($after);
            $minus = strtotime('+15 minute', $after_stamp);
            $after_time = date("g:i A", $minus);
            
            $bean->before_meeting_start = $before_time;
            $bean->after_meeting_start = $after_time;
            
        }
    }
    
}
?>