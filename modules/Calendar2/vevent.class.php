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
/* $Id$ */

    require_once 'modules/Calendar2/awl/iCalendar.php';
    require_once 'modules/Calendar2/caldavresource.class.php';
    require_once 'modules/Calendar2/icomponent.class.php';
    require_once 'modules/Calendar2/rruleparser.class.php';

    class VEvent extends IComponent {

        private $rulesParser;

        function __construct($etag, $url, VTYPE $type, iCalendar $item, $new) {
            parent::__construct($etag, $url, $type, $item, $new);
            $this->rulesParser = new RRuleParser();
        }

        function isActive($start, $end) {
            $res = FALSE;
            if (!($start && $end))
                return TRUE;
            if (! CaldavRessource::isDateTime($start) ||
                ! CaldavRessource::isDateTime($end))
                throw new Exception(
                    "[$start,$end] Invalid CalDAV DateTime format");
            $event = $this->getBaseComponent();
            if ($start && !$end) {
                if (CaldavRessource::datecmp(
                    $start, $event->GetPValue('DTSTART')) < 0)
                    $res = TRUE;
            }
            else {
                if (CaldavRessource::datecmp(
                        $start, $event->GetPValue('DTSTART')) < 0 &&
                    CaldavRessource::datecmp(
                        $end, $event->GetPValue('DTEND')) > 0)
                    $res = TRUE;
            }
            return $res;
        }

        function getActiveDates($range_start = NULL, $range_end = NULL) {
            $res = array();
            $event = $this->getBaseComponent();
            //print_r($event);
            $start = $event->GetPValue('DTSTART');
            $end = $event->GetPValue('DTEND');
            //print "$start:$end<br/>";
            if (! ($start && $end))
                return $res;
            $rrule = $event->GetPValue('RRULE');
            if ($rrule) {
                $this->rulesParser->setRule($rrule, $start, $end);
                //print $this->rulesParser->__toString()."\n";
                $res = $this->rulesParser->getEventDates(
                                    $range_start, $range_end);
                //print_r($res);
            }
            else {
                if ($this->isActive($range_start, $range_end))
                    array_push($res, $start);
            }
            //var_dump($res);
            return $res;
        }

        function getRRule() {
            return $this->rulesParser;
        }

        function getAlarm() {
            $alarm = $this->getComponent(VTYPE::VALARM);
//            print_r($alarm);
            if ($alarm)
                $alarm = $alarm[0];
            return $alarm;
        }

        function setProperty($name, $value) {
            $component = $this->getBaseComponent();
            $properties = $component->GetProperties();
            $match = FALSE;
            $update = FALSE;
            
            if (count($properties) > 0) {
                foreach ($properties as $property) {
                    //echo "B: " . $property->Name(). ":" . $property->Value() . "<br/>";
                    $test1 = explode(';', $name);
                    $test2 = explode(';', $property->Name());
                    if (strcasecmp($test1[0], $test2[0]) === 0) {
                        if (strcmp($property->Value(), $value) !== 0) {
                            $property->Value($value);
                            //echo "B: " . $property->Name(). ":" . $property->Value() . "<br/>";
                            $update = TRUE;
                        }
                        $match = TRUE;
                    }
                }
            }
            if ($match == FALSE) {
                $component->AddProperty(strtoupper($name), $value);
                $update = TRUE;
            }
            else {
                if ($update)
                    $component->SetProperties($properties);
            }
            if ($update) {
                $this->AddDefault($component);
                $this->setDirty();
            }
            //$properties = $component->GetProperties();
            //foreach ($properties as $property) {
            //    echo "A: " . $property->Name(). ":" . $property->Value() . "<br/>";
            //}
            //echo "<br/>";
            //exit;
        }
        
        private function AddDefault(iCalComponent $component) {
            $properties = $component->GetProperties();;
            $now = gmdate("Ymd\THis\Z");
            $a = array(1,1,1);
            foreach ($properties as $property) {
                //echo "D: " . $property->Name(). ":" . $property->Value() . "<br/>";
                if (strcasecmp('DTSTAMP', $property->Name()) === 0) {
                    $property->Value($now);
                    $a[0] = 0;
                }
                if (strcasecmp('LAST-MODIFIED', $property->Name()) === 0) {
                    $property->Value($now);
                    $a[1] = 0;
                }
                if (strcasecmp('X-WEBCAL-GENERATION', $property->Name()) === 0) {
                    $property->Value('1');
                    $a[2] = 0;
                }
            }
            for ($i = 0; $i < count($a); $i++) {
                //echo $i.':'.$a[$i]."<br/>";
                if ($a[$i]) {
                    switch ($i) {
                        case 0: $c['DTSTAMP'] = $now; break;
                        case 1: $c['LAST-MODIFIED'] = $now; break;
                        case 2: $c['X-WEBCAL-GENERATION'] = 1; break;
                        default: continue;
                    }
                    $key = key($c);
                    $val = $c[$key];
                    $component->AddProperty($key, $val);
                    $c = NULL;
                }
            }
        }
    }
