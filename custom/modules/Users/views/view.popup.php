<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.popup.php');

class UsersViewPopup extends ViewPopup {


     function UsersViewPopup(){
         parent::ViewPopup();
     }

     function display() {
     
        if (isset($_REQUEST['filter_role']) || isset($_REQUEST['description'])) {
            //true, as if a search query was performed
            $_REQUEST['query'] = true;
            //to be able to pass the $_REQUEST values in the next refresh page (like clicking search)
            output_add_rewrite_var("filter_role", "filter_role");
        }
        
        parent::display();
    }
}

?>
