<?php
/* Custom Script for Task 
 * This inserts tasks for Seminar Confirmation
 * @author Cedric P. Castillo
 * 4/2/2015
 *
 * 10/14/2015
 * Changed the task assignment from Marilyn Triplett to Analhi Nunez;
 * Requested by Analhi via email;
 */


class confirmationTask extends SugarBean {
    
    public function confirmationTask($focusObjectId) {
        global $sugar_config;
        $db = DBManagerFactory::getInstance();
        require_once('modules/Tasks/Task.php');
            
            // Create the task
            $task = new Task();
            $task->name = "Seminar Confirmation PDF document to print";
            $task->status = "Not Started";
            $task->priority = "High";
            $task->parent_type = "Leads";
            $task->parent_id = $focusObjectId;
            
            // Assign task to Analhi Nunez
            $task->assigned_user_id = 'f243b985-234b-ccaf-ebbb-556f3fcf45a3';
            
            $task->date_start = gmdate($GLOBALS['timedate']->get_db_date_time_format());
            $date_today_stamp = strtotime($task->date_start);
            $add_a_day = strtotime('+1 day', $date_today_stamp);
            $task->date_due = gmdate($GLOBALS['timedate']->get_db_date_time_format(), $add_a_day);
            
            $task->save(true);
            
            //add record to Division/SecurityGroup
            //$task->load_relationship('SecurityGroups');
            //$task->SecurityGroups->add($division_id);
            
            

    } 
}
?>
