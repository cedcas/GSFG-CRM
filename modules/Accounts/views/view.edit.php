<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


require_once('include/MVC/View/views/view.edit.php');

class AccountsViewEdit extends ViewEdit 
{   
    public function __construct()
    {
        parent::ViewEdit();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }
     
     /**
      * @see SugarView::display()
      * 
      * We are overridding the display method to manipulate the sectionPanels.
      * If portal is not enabled then don't show the Portal Information panel.
      */
    public function display() {
        
        $this->ev->process();
        
         if ( isset($_REQUEST['lead_id']) && !empty($_REQUEST['lead_id']) ) {
            $parent_lead = new Lead();
            $parent_lead->retrieve($_REQUEST['lead_id']);
            $this->ev->fieldDefs['assigned_user_id']['value'] = $parent_lead->assigned_user_id;
            $this->ev->fieldDefs['assigned_user_name']['value'] = $parent_lead->assigned_user_name;
        }
        echo $this->ev->display($this->showTitle);
    }
}