<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class GSF_SeminarDetails_Hook
{
    function update_parent_field(&$bean, $event, $arguments) {
        
        $parent = $bean->get_linked_beans('gsf_seminars_gsf_seminardetails', 'GSF_Seminars');
        
        if (!empty($parent)) {
            require_once('modules/GSF_Seminars/GSF_Seminars.php');
            $cluster = new GSF_Seminars();
            $cluster->retrieve($parent[0]->id);
            $cluster->save();
        }
        
    }

}
?>