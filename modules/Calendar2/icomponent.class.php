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

    require_once 'modules/Calendar2/caldavresource.class.php';

    abstract class IComponent {

        public  $type;
        private $component;
        private $url;
        private $etag;
        private $dirty;

        function __construct($etag, $url, VTYPE $type,
                             iCalendar $component, $new) {
            $this->etag = $etag;
            $this->url = $url;
            $this->component = $component;
            $this->type = $type;
            $this->dirty = $new;
        }

        public function isDirty() {
            return $this->dirty;
        }

        public function setDirty() {
            $this->dirty = TRUE;
        }
        
        public function getResource() {
            return $this->component;
        }

        public function setResource(iCalendar $component) {
            $this->component = $component;
            $this->dirty = TRUE;
        }

        public function getBaseComponent() {
            return $this->getComponent($this->type);
        }

        public function getUrl() {
            return $this->url;
        }

        public function getEtag() {
            return $this->etag;
        }

        public function setEtag($etag) {
            $this->etag = $etag;
        }
        
        public function getComponent($type) {
            $ref = $this->component;
            //print_r($ref);

            if ($this->component === NULL)
                $ical = NULL;
            else if ($type instanceof VTYPE && $type->ordinal() == VTYPE::VTIMEZONE) {
                $ical = $ref->component->GetComponents('VTIMEZONE');
            }
            else {
                //$theType = sprintf("%s", $this->type);
                //print "self: $theType\n";
                $component = $ref->component->GetComponents($this->type);
                //print_r($component);
                if (! $type instanceof VTYPE)
                    $type = new VTYPE($type);
                //$theType = sprintf("%s", $type);
                //print "instance: $theType\n";
                if (count($component) > 0)
                    $ical = $component[0];
                if ($type->ordinal() != $this->type->ordinal() && $ical) {
                    $ical = $ical->GetComponents($type);
                }
            }
            return $ical;
        }

        public function isUTCTime() {
            $event = $this->getBaseComponent();
            $start = $event->GetPValue('DTSTART');
            $end = $event->GetPValue('DTEND');

            if (! ($start && $end))
                throw new Exception("Not a valid iCal component");
            return ($start[strlen($start) - 1] == 'Z' ||
                    $nd[strlen($end) - 1] == 'Z');
        }

        public function getDetails() {
            $event = $this->getBaseComponent();
            $start = strtotime($event->GetPValue('DTSTART'));
            $start = date("Y-m-d H:m", $start);
            $end = strtotime($event->GetPValue('DTEND'));
            $end = date("Y-m-d H:m", $end);
            $title = $event->GetPValue('SUMMARY');
            
            return "$start-$end: $title";
        }
        
        public function getTZID() {
            $res = 'UTC';

            if (! $this->isUTCTime()) {
                $timezone = $this->getTimeZone();
                if ($timezone) {
                    $res = $timezone->GetPValue('TZID');
                }
                // timezone not given assume TZID = server's timezone
                // servers default timezone is UTC
            }
            return $res;
        }

        function getTimeZone() {
            $timezone = $this->getComponent(VTYPE::VTIMEZONE);
            if ($timezone)
                $timezone = $timezone[0];
            return $timezone;
        }

        public function __toString() {
            return $this->type->__toString();
        }

        /**
         * The following functions should be overloaded in
         * the child classes if the have specific functionality
         */

        function isActive($start, $end) {
            return FALSE;
        }

        function getActiveDates() {
            return array();
        }

        function getAlarm() {
            return NULL;
        }

    }
