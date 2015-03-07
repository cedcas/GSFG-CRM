<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class GSF_Seminars_Hook
{
    function update_fields(&$bean, $event, $arguments) {
        
        $db =  DBManagerFactory::getInstance();
        
        $query = "
            SELECT
                SUM(s.details_capacity) AS total
            FROM
                gsf_seminardetails s
            LEFT JOIN
                gsf_seminarminardetails_c cs
            ON
                s.id = cs.gsf_semina6236details_idb
            WHERE
                s.deleted = 0 AND
                cs.deleted = 0 AND
                cs.gsf_seminac629eminars_ida = '". $bean->id ."'
        ";
            
        $result = $db->query($query, true);
        $row = $db->fetchByAssoc($result);
            
        $bean->seminar_total_capacity = (empty($row)) ? "0" : $row['total'];
        
    }
}
?>