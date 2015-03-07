<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.edit.php');

class LeadsViewEdit extends ViewEdit 
{   
    public function __construct() {
        parent::ViewEdit();
    }
    
    
    public function display() {
        
        $this->ev->process();
        
        //START - change dropdown menu for "status" field
        if ($this->bean->id == NULL) {
            $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_1'];
        }
        else {
            if ($this->bean->status == "New") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_1'];
            }
            else if ($this->bean->status == "Registered") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_2'];
            }
            else if ($this->bean->status == "Registration Waitlist") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_3'];
            }
            else if ($this->bean->status == "Registration Cancelled") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_4'];
            }
            else if ($this->bean->status == "Attended") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_5'];
            }
            else if ($this->bean->status == "Converted") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_6'];
            }
            else if ($this->bean->status == "Client") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_7'];
            }
            else if ($this->bean->status == "Follow up") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_8'];
            }
            else if ($this->bean->status == "Dead") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_9'];
            }
            else if ($this->bean->status == "Cancelled" ||
                     $this->bean->status == "Account Terminated") {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom_10'];
            }
            else {
                $this->ev->fieldDefs['status']['options'] = $GLOBALS['app_list_strings']['lead_status_dom'];
            }
        }
        //END - change dropdown menu for "status" field
        
        echo $this->ev->display($this->showTitle);
     }    
}