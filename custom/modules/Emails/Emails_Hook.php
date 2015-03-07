<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class Emails_Hook
{
    /* Lead Generation function
     * This function will create a Lead record base on the saved/imported Email
     * @author JOED@ASI 20110511
     */
    function leads_generation(&$bean, $event, $arguments) {
        
        /* if newly imported (status is Unread) and
         * if the email's subject is "New reservation from SeminarEdge",
         * then we will create a lead
         */
        if ( $bean->status == "unread" && strtolower($bean->name) == strtolower("New reservation from SeminarEdge") ) {
            
            $GLOBALS['log']->info("START - Emails Hook Lead Generation");
            
            $emailbody = strip_tags($bean->description_html, '<table><th><tr><tbody><td><br><br /><br/>');
            $emailbody = str_replace("\n", "", $emailbody);
            $emailbody = str_replace("\r", "", $emailbody);
            
            //parse the table that contains the attendees
            $return = $this->parseTable($emailbody);
            $table = $return[0];
            $row_start = $return[1];
            
            //get only the first 2 rows ($table[0] and $table[1]), the lead and his/her spouse
            if (!empty($table)) {
                
                $GLOBALS['log']->info("HTML table for Attendees found! Creating Lead record.");
                
                require_once('modules/Leads/Lead.php');
                require_once('custom/modules/Emails/nameParser.php');
                
                $lead = new Lead();
                
                //the lead
                if (!empty($table[$row_start][0])) {
                    $lname = new nameParser($table[$row_start][0]);
                    $lead->first_name  = $lname->getFirstName();
                    $tmp = $lname->getMiddleName();
                    $lead->first_name .= empty($tmp) ? "" : " ".$tmp;
                    
                    $tmp = $lname->getSuffix();
                    $lead->last_name  = $lname->getLastName();
                    $lead->last_name .= empty($tmp) ? "" : " ".$tmp;
                }
                
                if (!empty($table[$row_start][1])) {
                    $lead->phone_home = $table[$row_start][1];
                }
                
                //the spouse (if there is)
                if (isset($table[$row_start+1])) {
                    if (!empty($table[$row_start+1][0])) {
                        $sname = new nameParser($table[$row_start+1][0]);
                        $lead->spouse_first_name_c  = $sname->getFirstName();
                        $tmp = $sname->getMiddleName();
                        $lead->spouse_first_name_c .= empty($tmp) ? "" : " ".$tmp;
                        
                        $tmp = $sname->getSuffix();
                        $lead->spouse_last_name_c  = $sname->getLastName();
                        $lead->spouse_last_name_c .= empty($tmp) ? "" : " ".$tmp;
                    }
                    
                    if (!empty($table[$row_start+1][1])) {
                        $lead->phone_other = $table[$row_start+1][1];
                    }
                }
                
                
                // START - get seminar details
                preg_match_all("/Meeting Info: on .*?SEMID(.*?)[\s]*</", $emailbody, $matches);
                $s_num = $matches[1];
                if (!empty($s_num[0])) {
                    $seminar_name = "SEMID" . $s_num[0];
                    $seminar_name = str_replace(")", "", $seminar_name);
                }
                
                preg_match_all("/Meeting Info: on .*?(.*?)[\s]* /", $emailbody, $matches);
                $s_date = $matches[1];
                $seminar_date =  date('Y-m-d', strtotime($s_date[0]));
                
                preg_match_all("/Meeting Info: on .*? (.*?)[\s]* at/", $emailbody, $matches);
                $s_time = $matches[1];
                $seminar_time = $s_time[0];
                
                preg_match_all("/Meeting Info: on .*?at (.*?)[\s]*</", $emailbody, $matches);
                $s_venue = $matches[1];
                $seminar_venue = addslashes($s_venue[0]);
                
                
                preg_match_all("/Meeting Info: on .*?at (.*?)[\s]* \(.*?/", $emailbody, $matches);
                $s_venue = $matches[1];
                $seminar_venue = trim(addslashes($s_venue[0]));

                if (empty($seminar_venue)) {
                    preg_match_all("/Meeting Info: on .*?at (.*?)[\s]*<.*?/", $emailbody, $matches);
                    $s_venue = $matches[1];
                    $seminar_venue = trim(addslashes($s_venue[0]));
                }
                // END - get seminar details
                
                
                $db =  DBManagerFactory::getInstance();
                
                //get Seminar Detail record in the database, and link it to the Lead
                
                //if seminar name is specified (i.e. SEMID11-00001)
                if (isset($seminar_name) && !empty($seminar_name)) {
                    $query = "
                        SELECT
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
                            s.name = '$seminar_name' AND
                            sv.deleted = 0;
                        ";
                        
                }
                //else, look into the seminar info (i.e. date, time , venue)
                else {
                    $query = "
                        SELECT
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
                            s.details_from_date = '$seminar_date' AND
                            s.details_from_time = '$seminar_time' AND
                            UPPER(v.name) LIKE UPPER('%$seminar_venue%') AND
                            sv.deleted = 0;
                    ";
                }
                
                $result = $db->query($query, true);
                $row = $db->fetchByAssoc($result);
                
                if (!empty($row)) {
                    $lead->gsf_semina6647details_ida = $row['id'];
                    $lead->seminar_venue_name_c      = $row['name'];
                    $lead->seminar_date_c            = date('m/d/Y', strtotime($row['details_from_date']));
                    $lead->seminar_time_c            = $row['details_from_time'];
                    $lead->seminar_address_c         = $row['details_venue_address1'];
                    $lead->seminar_city_c            = $row['details_venue_city'];
                    $lead->seminar_state_c           = $row['details_venue_state'];
                    $lead->seminar_postalcode_c      = $row['details_venue_postalcode'];
                    $lead->seminar_capacity          = $row['details_capacity'];
                    
                } else {
                    $GLOBALS['log']->info("Seminar Detail record not found in the database!");
                }
                
                $lead->description = $bean->description;
                $lead->status = "Registered";
                $lead->assigned_user_id = $bean->assigned_user_id;
                $lead->save();
                
                $GLOBALS['log']->info("Successfully created a Lead record. ID: " . $lead->id);
                
                //link the lead record and the email record
                $lead->load_relationship('emails');
                $lead->emails->add($bean->id);
                
            } else {
                $GLOBALS['log']->info("There was no HTML table for Attendees! No Lead record created.");
            }
            
            $GLOBALS['log']->info("END - Emails Hook Lead Generation");
        
        } //if - outer
    
    }
    
    
    function parseTable($html) {
        //http://blog.mspace.fm/2009/10/14/parse-an-html-table-with-php/
        // Find the table
        preg_match("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
        
        // Get title for each row
        preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/", $table_html[0], $matches);
        $row_headers = $matches[1];
        
        // Iterate each row
        preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0], $matches);

        $table = array();
        
        foreach ($matches[1] as $row_html)  {
            preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
            $row = array();

            for($i=0; $i<count($td_matches[1]); $i++) {
                $td = strip_tags(html_entity_decode($td_matches[1][$i]));
                //$row[$row_headers[$i]] = $td;
                $row[] = $td;
            }
            
            if(count($row) > 0) {
                $table[] = $row;
            }
        }
        
        $row_start = (empty($row_headers)) ? 1 : 0;
        return array($table, $row_start);
    }
    
    /*
    function parseTable($html) {
        //http://blog.mspace.fm/2009/10/14/parse-an-html-table-with-php/
        // Find the table
        preg_match("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
        
        // Get title for each row
        preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/", $table_html[0], $matches);
        $row_headers = $matches[1];
        
        // Iterate each row
        preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0], $matches);
     
        $table = array();
        
        foreach ($matches[1] as $row_html)  {
            preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
            $row = array();
            
            for($i=0; $i<count($td_matches[1]); $i++) {
                $td = strip_tags(html_entity_decode($td_matches[1][$i]));
                //$row[$row_headers[$i]] = $td;
                $row[] = $td;
            }
            
            if(count($row) > 0) {
                $table[] = $row;
            }
        }
        
        return $table;
    }
    */
    
    
}
?>