<?php

class GSF_SeminarDetailsController extends SugarController {
    
    /* Salesperson column in ListView
     * override returned query before sugar will use it
     * author: KMJ 2011-11-19
     */
    function action_listview()
    {
        require_once('custom/modules/GSF_SeminarDetails/GSF_SeminarDetailsListView.php');
    
        $this->view_object_map['bean'] = $this->bean;
        $this->view = 'list';
        $GLOBALS['view'] = $this->view;
        
        $this->bean = new GSF_SeminarDetailsListView();
    } 

}
?>