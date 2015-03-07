<?php
/******************************************************************************
OpensourceCRM End User License Agreement

INSTALLING OR USING THE OpensourceCRM's SOFTWARE THAT YOU HAVE SELECTED TO 
PURCHASE IN THE ORDERING PROCESS (THE "SOFTWARE"), YOU ARE AGREEING ON BEHALF OF
THE ENTITY LICENSING THE SOFTWARE ("COMPANY") THAT COMPANY WILL BE BOUND BY AND 
IS BECOMING A PARTY TO THIS END USER LICENSE AGREEMENT ("AGREEMENT") AND THAT 
YOU HAVE THE AUTHORITY TO BIND COMPANY.

IF COMPANY DOES NOT AGREE TO ALL OF THE TERMS OF THIS AGREEMENT, DO NOT SELECT 
THE "ACCEPT" BOX AND DO NOT INSTALL THE SOFTWARE. THE SOFTWARE IS PROTECTED BY 
COPYRIGHT LAWS AND INTERNATIONAL COPYRIGHT TREATIES, AS WELL AS OTHER 
INTELLECTUAL PROPERTY LAWS AND TREATIES. THE SOFTWARE IS LICENSED, NOT SOLD.

    *The COMPANY may not copy, deliver, distribute the SOFTWARE without written
     permit from OpensourceCRM.
    *The COMPANY may not reverse engineer, decompile, or disassemble the 
    SOFTWARE, except and only to the extent that such activity is expressly 
    permitted by applicable law notwithstanding this limitation.
    *The COMPANY may not sell, rent, or lease resell, or otherwise transfer for
     value, the SOFTWARE.
    *Termination. Without prejudice to any other rights, OpensourceCRM may 
    terminate this Agreement if the COMPANY fail to comply with the terms and 
    conditions of this Agreement. In such event, the COMPANY must destroy all 
    copies of the SOFTWARE and all of its component parts.
    *OpensourceCRM will give the COMPANY notice and 30 days to correct above 
    before the contract will be terminated.

The SOFTWARE is protected by copyright and other intellectual property laws and 
treaties. OpensourceCRM owns the title, copyright, and other intellectual 
property rights in the SOFTWARE.
*****************************************************************************/
    require_once 'modules/Calendar2/caldav-client.php';
    require_once 'modules/Calendar2/awl/iCalendar.php';
    require_once 'modules/Calendar2/caldavresource.class.php';
    require_once 'modules/Calendar2/rruleparser.class.php';
    require_once 'modules/Calendar2/vevent.class.php';
    require_once 'modules/Calendar2/icomponent.class.php';

    class CalendarIterator implements Iterator {
        private $list;

        function __construct(array $list) {
            $this->list = $list;
        }

        function current() {
            return current($this->list);
        }

        function next() {
            next($this->list);
        }

        function key() {
            return key($this->list);
        }

        function rewind() {
            reset($this->list);
        }

        function valid() {
            $obj = current($this->list);
            return ($obj !== FALSE);
        }

    }

    class CalendarCaldav extends CaldavRessource {

        private $calendar;

	function __construct($url, $uid = '', $pwd = '', $cal = '') {
	    //file_put_contents('/tmp/dump', "$url\n$uid\n$pwd\n$cal\n", FILE_APPEND);
            if (empty($url))
                throw new Exception("Missing URL");
            parent::__construct($url, $uid, $pwd, $cal);
        }

        private function setComponent(VTYPE $type, array $item, $new = FALSE) {
            switch ($type->ordinal()) {
                case VTYPE::VEVENT:
                    $ical = new VEvent(
                        $item['etag'], $item['href'],
                        $type, $item['ical'], $new);
                    break;
                default:
                    throw new Exception(
                        "$thisType: Unsupported iCalendar component");
            }
            $this->calendar[$item['etag']] = $ical;
            //var_dump($this->calendar[$item['etag']]);
        }

        private function setResource($etag, $resource) {
            if ($resource === NULL)
                unset($this->calendar[$etag]);
            else if (isset($this->calendar[$etag]))
                $this->calendar[$etag]->setResource($resource);
            else {
                $type = new VTYPE($this->getType($resource));
                $this->setComponent($type, array(
                    'etag' => $etag,
                    'href' => NULL,
                    'ical' => $resource),
                    TRUE
                );
            }
        }

        private function getType(iCalendar $iCalendar) {
            $components = $iCalendar->component->GetComponents();
            // Find VCalender component
            foreach($components as $type) {
                try {
                    $vtype = new VTYPE($type->GetType());
                    if ($vtype->ordinal() != VTYPE::VTIMEZONE)
                        break;
                }
                catch (Exception $ex) {}
            }
            return $vtype;
        }

        private function wrapCalendar($component) {
            $cal = "BEGIN:VCALENDAR\r\n";
            $cal .= "PRODID:-//datanom.net//NONSGML WEBCAL Calendar//EN\r\n";
            $cal .= "VERSION:2.0\r\n";
            $cal .= "CALSCALE:GREGORIAN\r\n";
            $cal .= $component;
            $cal .= "END:VCALENDAR\r\n";
            
            return $cal;
        }
        
        function getComponents($start, $end) {
            $this->calendar = array();

            if (! $this->isDateTime($start) || ! $this->isDateTime($end))
                throw new Exception("[$start:$end]: Invalid DateTime format");
            //print "$start:$end<br/>";
            //file_put_contents('/tmp/dump', "$start, $end\n", FILE_APPEND);
            $events = $this->callServer('getEvents', array($start, $end));
            //var_export($events, FALSE);
            //file_put_contents('/tmp/dump', var_export($events, TRUE), FILE_APPEND);
            foreach ($events as $k => $event) {
                $iCalendar = new iCalendar(
                    array('icalendar' => $event['data']));
                $vtype = $this->getType($iCalendar);
                $this->setComponent($vtype, array(
                    'etag' => $event['etag'],
                    'href' => $event['href'],
                    'ical' => $iCalendar
                    )
                );
            }
        }
        
        function newEvent($bean,$caldav_url) {
        	global $timedate;  
        	
        	$td = new TimeDate();		
		
		$s = array();
		
		$s["startDay"] = $bean->date_start;
		$s["startTime"] = $bean->time_start;
		$s["endDay"] = $bean->date_end;
		
		if ($bean->date_start=='' and $bean->date_due!=""){
			$s["startDay"] = $bean->date_due;  #### for tasks if no start date
		}else{
			$s["startDay"]    = $bean->date_start;
		}
		$s["startDay"] = $td->to_display_date_time($s["startDay"]);
		$s["endDay"] = $td->to_display_date_time($s["endDay"]);
		
		global $current_user;
		$pd =   $s["startDay"];
		$date_start_in_db_fmt=$s["startDay"];

		$date_end_in_db_fmt1=(isset($bean->date_due))? $bean->date_due:$bean->date_end;

		$date_start_array=split(" ",trim($date_start_in_db_fmt));
		//$date_time_start =DateTimeUtil2::get_time_start($date_start_array[0],$date_start_array[1]);
		$tt1= $td->to_display_date_time($bean->date_start,true,true);

		$user=$current_user;
		$s["startTime"]=   $td->handle_offset($date_start_in_db_fmt, "H:i:s", false,$current_user);
		$date_end_in_db_fmt1=   $td->handle_offset($date_end_in_db_fmt1, "Y-m-d H:i:s", false,$current_user);

		$ch = explode(" ",$s["startDay"]);
		if ($ch['1']=="00:00" or $ch['1']=="00.00"){
			$s["startDay"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d", false,$current_user);   
			$s["startDay1"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d H:i:s", false,$current_user);
		}else{
			$s["startDay"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d", false,$current_user);  
			$s["startDay1"]  = $td->handle_offset($date_start_in_db_fmt, "Y-m-d H:i:s", false,$current_user);  
		}
		$plugin_format = "d/m/Y H:i";

		if ($td->get_date_time_format(true, $current_user)!=$plugin_format){
			//OSC 2011.3.15 to avoid a notice error
			//$plugin_date_due=$td->swap_formats($bean->date_due, $td->get_date_time_format(true, $current_user),  $plugin_format);
			$plugin_date_due=$td->swap_formats($date_end_in_db_fmt1, $td->get_date_time_format(true, $current_user),  $plugin_format);
			//OSC End
		}else{
			$plugin_date_due=$bean->date_due;
		}
		$plugin_date_start=$td->swap_formats($s["startDay1"], "Y-m-d H:i:s",  $plugin_format);  
		$plugin_date_due1 = $td->swap_formats($date_end_in_db_fmt1, "Y-m-d H:i:s",  $plugin_format);   

		$d1 = explode("/",$plugin_date_start);
		global $current_user;
		$d1[2]=explode(" ",$d1[2]);
		$d1[2]=$d1[2][0];
		if ($bean->time_start!='' and 1==2){
			$t1 = explode(":",$bean->time_start);
		}else{
			$fort1 = explode(" ",$plugin_date_start)  ;
			$fort1 = $fort1[1];
			$t1 = explode(":",$fort1);
		}
		$t1[1] = round($t1[1]);
		if (!isset($t1[2])) $t1[2] = 0;
		$untill  = mktime($t1[0]+$bean->duration_hours,$t1[1]+$bean->duration_minutes, $t1[2], $d1[1], $d1[0], $d1[2]); 

		$date_due=date("Y-m-d H:i:s", $untill);
		$s["endDay"] = $date_due;

		$dtstart = date("Ymd\THis\Z",strtotime($s['startDay1']));
		$dtend = date("Ymd\THis\Z",strtotime($s['endDay']));
		
		if($bean->caldav_uid_c!='')
			$uid = $bean->caldav_uid_c;
		else
			$uid = sha1(microtime() . $dtstart . $dtend);
		$event = new iCalendar(array('type' => 'VEVENT','SUMMARY' => $bean->name,'DTSTART'=>$dtstart,'DTEND'=>$dtend,'UID'=>$uid));

		$vtype = $this->getType($event);
		$etag = sha1(time());
		$this->setComponent($vtype, array(
		                'etag' => $etag,
		                'href' => NULL,
		                'ical' => $event
		                ), true
            	);
            	
            	//  Google: update google response in table
		$sql = "update ".$bean->table_name." set caldav_uid_c=\"".$uid."\" where id='".$bean->id."'";
		$bean->db->query($sql);
            	
		$this->update($caldav_url,$etag);
        }

        function newComponent($c_type) {
            switch (strtoupper($c_type)) {
                case 'VEVENT': $type = 'VEVENT'; break;
                default:
                    throw new Exception(
                        "$thisType: Unsupported iCalendar component");
            }
            $start = gmdate("Ymd\THm\Z");
            $end = strtotime($start) + (60*60);
            $end = gmdate("Ymd\THm\Z", $end);
            //echo "$start:$end<br/>";
            $uid = sha1(microtime() . $start . $end);
            $iCalendar = new iCalendar(array(
                    'type' => $type,
                    'SUMMARY' => 'testing',
                    'DTSTART' => $start,
                    'DTEND' => $end,
                    'UID' => $uid
                )
            );
            $vtype = $this->getType($iCalendar);
            $etag = sha1("This is a new component");
            $this->setComponent($vtype, array(
                'etag' => $etag,
                'href' => NULL,
                'ical' => $iCalendar
                ), true
            );
            return $this->calendar[$etag];
        }
/*
        function reload($start, $end) {
            $res = $this->update();
            if (count($res) < 1) {
                $this->getComponents($start, $end);
            }
            return $res;
        }
*/
        private function updateEvent($url, $etag) {
            $res = array();
            $resource = $this->calendar[$etag];
            if ($resource && $resource->isDirty()) {
                // update (call put)
                $component = $resource->getBaseComponent();
                //print "$etag: update\n";
                $uid = $component->GetPValue('UID');
                $ical = $this->wrapCalendar($component->Render());
                //echo "$uid<br/>".nl2br($ical)."$etag<br/>";
                $url = $resource->getUrl();
                if ($url) {
                    $newEtag = $this->callServer('put', 
                            array("$uid.ics", $ical, $etag));
                }
                else {
                    $newEtag = $this->callServer('put', 
                            array("$uid.ics", $ical));
                }
                if (is_array($newEtag))
                    array_push($res, $newEtag);
                else {
                    $resource->setEtag($newEtag);
                }
            }
            return $res;
        }
                
        function update($url, $etag = NULL) {
            //var_dump($this->calendar);
            if (! $etag) {
                foreach($this->calendar as $id => $resource) {
                    //var_dump($resource);
                    $thisUrl = $resource->getUrl();
                    if ($thisUrl && strcasecmp($url, $thisUrl) == 0) {
                        $etag = $id;
                        break;
                    }
                }
            }
            if ($etag)
                $res = $this->updateEvent($url, $etag);
            else
                $res = array($url => 'Event does not exist');
            return $res;
        }

        function delete($url, $etag = NULL) {
            if ($etag) {
                $res = $this->callServer('delete', array($url, $etag));
            }
            else {
                $res = $this->callServer('delete', array($url));
            }
            return $res;
        }

        // inherited abstract methods from parent
        function offsetExists($etag) {
            return (is_object($this->calendar[$etag]) &&
                $this->calendar[$etag] instanceof IComponent);
        }

        function offsetGet($etag) {
            if ($this->offsetExists($etag))
                return $this->calendar[$etag]->getResource();
        }

        function offsetSet($etag, $ical) {
            $this->setResource($etag, $ical);
        }

        function offsetUnset($etag) {
            $this->setResource($etag, NULL);
        }

        function getIterator() {
            return new CalendarIterator($this->calendar);
        }

    }
/*
$cal = new Calendar(
    'http://calendar.datanom.net/caldav.php/mir/home/',
    'uid',
    'pwd'
);
$cal->getComponents("20030830T000000Z","20031201T000000Z");
//print_r($cal);
$i = 0;
foreach($cal as $obj) {
    $i++;
    print "========= [$i] =========\n";
    //print_r($obj);
    //print_r ($obj->getAlarm());
    print_r($obj->getActiveDates("20031014T000000Z","20031114T000000Z"));
    //print "{$obj->isUTCTime()}\n";
    //$obj->getActiveDates();
}
print "Found $i event(s)\n";

//print_r ($cal->getUrlByEtag($cal->getEtagFromUid('KOrganizer-1670268771.406')));
$time = time();
print "time: $time\n";
$dt = $cal->timestamp2ICal($time, TRUE);
print "dt: $dt\n";
$time = $cal->iCal2Timestamp($dt);
print "time: $time\n";
$dt = $cal->timestamp2ICal($time, FALSE);
print "dt: $dt\n";
$time = $cal->iCal2Timestamp(substr($dt, 0, strpos($dt, 'T')));
$dt = $cal->timestamp2ICal($time, TRUE);
print "dt: $dt\n";
$r = new RRuleParser(
    'FREQ=HOURLY;INTERVAL=3;UNTIL=20070101T170000Z',
    '20070101T090000Z', '20070101T090000Z');
$r = new RRuleParser(
    'FREQ=WEEKLY;COUNT=12;INTERVAL=2',
    '20070101T140000Z', '20070101T120000Z');
print "$r\n";
print_r($r->getEventDates('20070301T140000Z','20070501T140000Z'));
$r = new RRuleParser(
    'FREQ=MONTHLY;BYDAY=MO,TU,WE,TH,FR;BYSETPOS=-1',
    '20070101T000100Z', '20070101T001100Z');
//DTSTART;TZID=US-Eastern:19970105T083000
print "$r\n";
$r = new RRuleParser(
    'FREQ=YEARLY;INTERVAL=2;BYMONTH=1;BYDAY=SU;BYHOUR=8,9;BYMINUTE=30',
    '20070101T000100Z', '20070101T001100Z');
print "$r\n";
print_r ($r->getEventDates('20060101T000100Z', '20060101T001100Z'));
$r = new RRuleParser(
    'FREQ=DAILY;COUNT=10;INTERVAL=2',
    '20070101T000100Z', '20070101T001100Z');
print "$r\n";
//foreach ($cal as $obj)
//    var_dump($obj->getBaseComponent());
//$bak = $cal['3ba46312e910765bf7059a53909d149b'];
//print_r($bak);
//print_r(new Icalendar(array('SUMMARY' => 'test')));
//$cal['3ba46312e910765bf7059a53909d149b'] = new Icalendar(array('SUMMARY' => 'test'));
//print_r($cal['3ba46312e910765bf7059a53909d149b']);
//unset($cal['3ba46312e910765bf7059a53909d149b']);
//var_dump($cal['3ba46312e910765bf7059a53909d149b']);
//$cal['3ba46312e910765bf7059a53909d149b'] = $bak;
//var_dump($cal['3ba46312e910765bf7059a53909d149b']);
//$cal->update();
//print_r($cal['3ba46312e910765bf7059a53909d149b']);*/
