<?php

class TasksController extends SugarController {

    function action_detailview() {
        require_once('custom/modules/Tasks/Task2.php');
        $this->bean = new Task2();
        $this->bean->retrieve($this->bean->db->quote($_REQUEST['record']));
        $this->view = 'detail';
        return true;
    }
    
    /* Sorting parent_name on Task List view
     * Requirement 2.1
     * override returned query before sugar will use it
     * author: KMJ 2011-11-16
     */
    function action_listview()
    {
        require_once('custom/modules/Tasks/TaskListView.php');
    
        $this->view_object_map['bean'] = $this->bean;
        $this->view = 'list';
        $GLOBALS['view'] = $this->view;
        
        $this->bean = new TaskListView();
    } 

}
?>