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
    final class VTYPE {
        const VEVENT    = 1;
        const VTODO     = 2;
        const VJOURNAL  = 3;
        const VFREEBUSY = 4;
        const VTIMEZONE = 5;
        const VALARM    = 6;
        private $value;

        public function __construct($value) {
            switch ($value) {
                case 'VEVENT': $this->value = self::VEVENT; break;
                case 'VTODO': $this->value = self::VTODO; break;
                case 'VJOURNAL': $this->value = self::VJOURNAL; break;
                case 'VFREEBUSY': $this->value = self::VFREEBUSY; break;
                case 'VTIMEZONE': $this->value = self::VTIMEZONE; break;
                case 'VALARM': $this->value = self::VALARM; break;
                case self::VEVENT:
                case self::VTODO:
                case self::VJOURNAL:
                case self::VFREEBUSY:
                case self::VTIMEZONE:
                case self::VALARM: $this->value = $value; break;
                default: throw new Exception ("$value: Invalid VTYPE");
            }
        }

        private function __clone() {}

        public function ordinal() {
            return $this->value;
        }

        public function __toString() {
            switch ($this->value) {
                case self::VEVENT: return 'VEVENT'; break;
                case self::VTODO: return 'VTODO'; break;
                case self::VJOURNAL: return 'VJOURNAL'; break;
                case self::VFREEBUSY: return 'VFREEBUSY'; break;
                case self::VTIMEZONE: return 'VTIMEZONE'; break;
                case self::VALARM: return 'VALARM'; break;
            }
        }

    }

    abstract class CaldavRessource
        implements ArrayAccess, IteratorAggregate {

        private   $client;

        function __construct($url, $uid = '', $pwd = '', $cal = '') {
            if (empty($url))
                throw new Exception("Missing URL");
            $this->client = new CalDAVClient($url, $uid, $pwd, $cal);
        }

        /**
         * abstract functions to be implemented by sub classes
         */
        abstract function update($url, $etag = NULL);
        abstract function newComponent($c_type);
        abstract function getComponents($start, $end);
        abstract function delete($url, $etag = NULL);

        protected function callServer($method, $param = array()) {
            $error = TRUE;
            $msg = "Unknown error";

            if (! is_array($param))
                throw new Exception("Parameters must be inclosed in an array");
            switch (strtolower($method)) {
                case 'getevents':
                    if (count($param) != 2) {
                        $error = TRUE;
                        $msg = "Expected 2 parameters";
                        break;
                    }
                    if ($this->isDateTime($param[0]) &&
                        $this->isDateTime($param[1])) {
                        $res = $this->client->GetEvents($param[0], $param[1]);
                        $error = FALSE;
                    }
                    else {
                        $msg = "[${param[0]},${param[1]}]: Invalid DateTime";
                        $error = TRUE;
                    }
                    break;
                case 'getbyuid':
                    if (count($param) != 1) {
                        $error = TRUE;
                        $msg = "Expected 1 parameter";
                        break;
                    }
                    $res = $this->client->GetEntryByUid($param[0]);
                    $error = FALSE;
                    break;
                case 'put':
                	if (count($param) < 2 || count($param) > 3) {
						$error = TRUE;
						$msg = "Syntax: URL, CalDAV_resource[, ETag]";
						break;
					}
					if (count($param) == 2)
						$res = $this->client->DoPUTRequest($param[0], $param[1]);
					else
						$res = $this->client->DoPUTRequest($param[0], $param[1], $param[2]);
					$error = FALSE;
                	break;
                case 'delete':
                	if (count($param) < 1 || count($param) > 2) {
						$error = TRUE;
						$msg = "Syntax: URL[, ETag]";
						break;
					}
					if (count($param) == 1)
						$res = $this->client->DoDELETERequest($param[0]);
					else
						$res = $this->client->DoDELETERequest($param[0], $param[1]);
					$error = FALSE;
                	break;
                default:
                    throw new Exception("$method: Unknown method");
            }
            if ($error)
                throw new Exception($msg);
            else
                return $res;
        }

        static function isDateTime($var) {
            return (preg_match("/^([0-9]{8})T([0-9]{6})Z?$/", $var) > 0);
        }

        /**
         * Returned date-time will always be in UTC
         */
        static function timestamp2ICal($ts, $localtime = TRUE) {
            $ts = (int) $ts;
            if ($ts < 0)
                throw new Exception("$ts: invalid timestamp");
            if ($localtime) {
                $date = date('Ymd', $ts);
                $time = date('His', $ts);
                $res = sprintf("%sT%s", $date, $time);
            }
            else {
                $date = gmdate('Ymd', $ts);
                $time = gmdate('His', $ts);
                $res = sprintf("%sT%sZ", $date, $time);
            }
            return $res;
        }

        static function iCal2Timestamp($ical) {
            if (! self::isDateTime($ical)) {
                // test for badly formed all-day event
                //print "$ical";
                $res = preg_match("/^([0-9]{4})([0-9]{2})([0-9]{2})$/",
                    $ical, $parts);
                if ($res == 0)
                    throw new Exception("$ical: invalid CalDAV Date-Time");
                else {
                    $timepart = array('00', '00', '00');
                    $parts = array_merge($parts, $timepart);
                }
            }
            else {
                $date = "([0-9]{4})([0-9]{2})([0-9]{2})";
                $time = "([0-9]{2})([0-9]{2})([0-9]{2})";
                preg_match("/^${date}T${time}(Z?)$/", $ical, $parts);
            }
            if (count($parts) == 8)
                return gmmktime($parts[4], $parts[5], $parts[6],
                    $parts[2], $parts[3], $parts[1]);
            else
                return mktime($parts[4], $parts[5], $parts[6],
                    $parts[2], $parts[3], $parts[1]);
        }

        private static function down_hour($date) {
            //print "$date<br/>";
            if (! self::isDateTime($date)) {
                // test for badly formed all-day event
                $res = preg_match("/^([0-9]{4})([0-9]{2})([0-9]{2})$/",
                    $date, $parts);
                if ($res == 0)
                    throw new Exception("$date: invalid CalDAV Date-Time");
                else {
                    array_shift($parts);
                    $timepart = array('T', '00', '00', '00');
                    $parts = array_merge($parts, $timepart);
                    return implode('', $parts);
                }
            }
            else {
                $a = explode('T', $date);
                $a[1] = substr_replace($a[1], '0000', 2);
                return $a[0].'T'.$a[1];
            }
        }

        static function fix_allday_event(&$date_a, &$date_b) {
            //print "$date_a:$date_b<br/>";
            if ($date_a == $date_b) {
                if (! self::isDateTime($date_a) && ! self::isDateTime($date_b)) {
                    $res1 = preg_match("/^([0-9]{4})([0-9]{2})([0-9]{2})$/",
                        $date_a);
                    $res2 = preg_match("/^([0-9]{4})([0-9]{2})([0-9]{2})$/",
                        $date_b);
                    if ($res1 == 0 || $res2 == 0)
                        throw new Exception("$date_a, $date_b: invalid CalDAV Date-Time");
                    else {
                        $date_a .= "T000000";
                        $date_b .= "T235959";
                    }
                }
                else {
                    preg_match("/^([0-9]{4}[0-9]{2}[0-9]{2})T([0-9]{6})$/",
                        $date_a, $part_a);
                    preg_match("/^([0-9]{4}[0-9]{2}[0-9]{2})T([0-9]{6})$/",
                        $date_b, $part_b);
                    $date_a = $part_a[0]."T000000";
                    $date_b = $part_b[0]."T235959";
                }
            }
        }

        static function datecmp($date_a, $date_b) {
            $date_a = self::iCal2Timestamp($date_a);
            $date_b = self::iCal2Timestamp(self::down_hour($date_b));
            if ($date_a < $date_b)
                $res = -1;
            else if ($date_a > $date_b)
                $res = 1;
            else
                $res = 0;
            return $res;
        }

        private static function intcmpstr($a_str, $b_str) {
            $a = (int) $a_str;
            $b = (int) $b_str;
            //print "$a:$b<br/>";
            if ($a > $b)
                return 1;
            else if ($a < $b)
                return -1;
            else
                return 0;
        }

        static function cmpdate($date_a, $date_b) {
            $datepart = explode('T', $date_a);
            $d_a = $datepart[0];
            $datepart = explode('T', $date_b);
            $d_b = $datepart[0];
            $y_cmp = self::intcmpstr(substr($d_a, 0, 4), substr($d_b, 0, 4));
            if ($y_cmp == 0) {
                $m_cmp = self::intcmpstr(substr($d_a, 4, 2), substr($d_b, 4, 2));
                if ($m_cmp == 0) {
                    return self::intcmpstr(substr($d_a, 6, 2), substr($d_b, 6, 2));
                }
                return $m_cmp;
            }
            return $y_cmp;
        }

        static function cmptime($time_a, $time_b) {
            $timepart = explode('T', $time_a);
            $t_a = $timepart[1];
            $timepart = explode('T', $time_b);
            $t_b = $timepart[1];
            //print "$t_a:$t_b<br/>";
            $h_cmp = self::intcmpstr(substr($t_a, 0, 2), substr($t_b, 0, 2));
            if ($h_cmp == 0) {
                $m_cmp = self::intcmpstr(substr($t_a, 2, 2), substr($t_b, 2, 2));
                if ($m_cmp == 0) {
                    return self::intcmpstr(substr($t_a, 4, 2), substr($t_b, 4, 2));
                }
                return $m_cmp;
            }
            return $h_cmp;
        }

        static function allDayEvent($time_a, $time_b) {
            //echo $time_a.':'.$time_b.'<br/>';
            $a = explode('T', $time_a);
            if (count($a) < 2)
                array_push($a, '0000');
            $b = explode('T', $time_b);
            if (count($b) < 2)
                array_push($b, '0000');
            $t = strtotime($time_b) - 3600;
            $t = date("Ymd\THm", $t);
            return (self::cmpdate($time_a, $t) == 0 &&
                    $a[1] == '0000' && $b[1] == '0000');
        }
    }
