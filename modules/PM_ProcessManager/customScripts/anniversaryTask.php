<?php
/* Custom Script for Task 
 * This inserts tasks for Anniversary records
 * @author Cedric P. Castillo
 * 4/1/2015
 *
 */


class anniversaryTask extends SugarBean {
    
    public function anniversaryTask($focusObjectId) {
        global $sugar_config;
        $db = DBManagerFactory::getInstance();
        require_once('modules/Tasks/Task.php');
            
            // Create the task
            $task = new Task();
            $task->name = "Anniversary Date PDF document to print";
            $task->status = "Not Started";
            $task->priority = "High";
            $task->parent_type = "Accounts";
            $task->parent_id = $focusObjectId;
            
            // Assign task to Trudy Poor
            $task->assigned_user_id = '737dd1ef-eac4-be72-5f6e-54e3bf6e0080';
            
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
