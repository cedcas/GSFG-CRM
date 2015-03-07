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
                $bean->venue_logo_img = '<div style="text-align: center;"><img src="'.$filepath.'" alt="Venue Logo" width="200" height="200" /></div>';
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
    
    
    
    
    
    /*
     * this is a custom hook that calls the Process Manager hook before doing a validation
     * custom validation
     *   - if the newly created Lead has no Seminar ID
     *   - if there is Seminar ID but the date of the seminar is past
     * if any of the validation is true, then we will not register this Lead to the pm_processmanager_entry_table
     * this is for the Task "Seminar Registration Confirmation via Email" (send email only if there is a Seminar and the date of the seminar is future)
     * author: Joed 20110819
     */
    function setPmEntryTable(&$bean, $event, $arguments) {
        
        global $timedate;
        
        if (empty($bean->fetched_row['last_name'])) { //ensure that this is create
        
            if (empty($bean->gsf_semina6647details_ida)) {
                return;
            } else {
                require_once('modules/GSF_SeminarDetails/GSF_SeminarDetails.php');
                $seminar = new GSF_SeminarDetails();
                $seminar->retrieve($bean->gsf_semina6647details_ida);
                
                $sem_date = strtotime($timedate->to_db($seminar->details_from_date));
                $today = strtotime(date("Y-m-d"));
                
                if ($sem_date < $today ) {
                    return;
                }
            }
            
        }
        
        require_once('modules/PM_ProcessManager/insertIntoPmEntryTable.php');
        
        $hook = new insertIntoPmEntryTable();
        if(!is_null($bean)) {
            $hook->setPmEntryTable($bean, $event, $arguments);
        } else {
            $hook->setPmEntryTable($event, $arguments);
        }
        
    }
    
}
?>
