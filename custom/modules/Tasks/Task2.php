<?php

require_once('modules/Tasks/Task.php');

class Task2 extends Task {

    function Task2() {
        parent::Task();
    }
    
    function get_accounts_leads_documents() {
        $listquery = "
            SELECT *
            FROM documents
            WHERE
                deleted = 0 AND
                (id IN (
                    SELECT document_id
                    FROM linked_documents
                    WHERE deleted = 0 AND
                    (parent_type = 'Accounts' OR parent_type = 'Leads') AND
                    parent_id = '".$this->parent_id."'
                    ) OR
                 id IN (
                    SELECT accounts_d009acuments_idb
                    FROM accounts_documents_c
                    WHERE deleted = 0 AND
                    accounts_d13e2ccounts_ida = '".$this->parent_id."'
                    )
                )
        ";
        return $listquery;
    }
    
}
?>