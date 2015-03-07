<?php
$GLOBALS['log']->debug("START - Get Meetings ID");
global $sugar_config;
$db = DBManagerFactory::getInstance();

$url = $sugar_config['teledirect_api_url_1'];
$username = $sugar_config['teledirect_api_username'];
$password = $sugar_config['teledirect_api_password'];


$date_today = date("Y-m-d"); 
$date_today_stamp = strtotime($date_today);
$minus = strtotime('-7 day', $date_today_stamp);

$date_today = date("n-j-y"); //ie: 1/5/11, 5/20/11
$date_lastweek = date("n-j-y", $minus);

//CPC - 12/2/2012
//Use the following date range to manually update Seminar ID
//$date_lastweek = "11/1/2014";
//$date_today = "2/17/2015";

$urlparams = array(
    'UserName' => $username,
    'Password' => $password,
    'FromDate' => $date_lastweek,
    'ToDate' => $date_today,
);

$url .= "?" . http_build_query($urlparams, '', '&');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);

$response = curl_exec($ch); 

if (curl_errno($ch)) {
    $GLOBALS['log']->fatal(curl_error($ch));
} else {
    curl_close($ch);
}
//print_r($url);
//echo "<pre>";

$data = new SimpleXMLElement($response);
$clusters = $data->Seminar;

foreach ($clusters as $cluster) {
    $meetings = $cluster->Meetings;
    
    foreach ($meetings as $meeting) {
        
        foreach ($meeting as $meetingrecord) {
            
            //print_r($meetingrecord);
            //$test = "Venutis Restaurant (12345)";
            preg_match_all("/.*?\((.*?)[\s]*\)/", $meetingrecord->Location, $matches);
            $seminar_venue = $matches[1];
            $seminar_id = $seminar_venue[0];
            
            if (!empty($seminar_id)) {
                $query = "
                    UPDATE
                        gsf_seminardetails
                    SET
                        meeting_id = '" . $meetingrecord["MeetingID"] . "'
                    WHERE
                        name LIKE '%" . $seminar_id . "' AND
                        (meeting_id IS NULL OR meeting_id = '') AND
                        deleted = 0
                    ";
                $db->query($query, true);
            } else {
                $query = "
                    UPDATE
                        gsf_seminardetails
                    SET
                        meeting_id = '" . $meetingrecord["MeetingID"] . "'
                    WHERE
                        details_from_date = '".date("Y-m-d", strtotime($meetingrecord->StartDate))."' AND
                        details_to_date = '".date("Y-m-d", strtotime($meetingrecord->CloseDate))."' AND
                        (meeting_id IS NULL OR meeting_id = '') AND
                        deleted = 0 AND
                        id = (
                            SELECT 
                                vs.gsf_venuesc61bdetails_idb
                            FROM
                                gsf_venues_minardetails_c vs
                            RIGHT JOIN
                                gsf_venues v
                            ON
                                vs.gsf_venues56d9_venues_ida = v.id
                            WHERE
                                vs.deleted = 0 AND
                                v.deleted = 0 AND
                                UPPER(v.name) LIKE UPPER('%" . $meetingrecord->Location . "%')
                            LIMIT 1
                        )
                    ";
                $db->query($query, true);
            }
        }
    }
    
}

$GLOBALS['log']->debug("END - Get Meetings ID");
?>