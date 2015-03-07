<?php

/* 6/29/13 - CPC
 * BT# 48 - Disabled the DOWNLOAD BUTTON when dealing with BIRTHDAY REMINDER and CALL ACCOUNT REMINDER;
 *
 * 7/25/13 - CPC
 * Added "ORDER BY document_revisions.date_modified DESC" in the 2nd query in order to ensure
 * the latest document is being linked via the DOWNLOAD button;
 */

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class TaskListView extends Task
{    
    /* Sorting parent_name on Task List view
     * Requirement 2.1
     * override returned query before sugar will use it
     * author: KMJ 2011-11-16
     */
    function create_new_list_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean=null, $singleSelect = false)
    {
	    $additional_left_join = " LEFT JOIN accounts on accounts.id = tasks.parent_id ";
	    if(strpos($order_by, 'parent_name') !== false){
		    $order_by = str_replace('parent_name', 'accounts.name', $order_by);
	    }
	    $return_array = true;
	    $query = parent::create_new_list_query($order_by, $where,$filter,$params, $show_deleted,$join_type, $return_array,$parentbean, $singleSelect);
	    $query['from'] .= $additional_left_join;
	    return $query;
    }
    
    /* Download button on Task List view
     * Requirement 2.3
     * override field data for tasks.download_dumm_c
     * author: KMJ 2011-11-16
     */
    function get_list_view_data(){
	global $db, $current_user;
	
	$list_data = parent::get_list_view_data();
        $task_name = $list_data['NAME'];
        //echo "NAME: ".$task_name;
	        
	// populate download_dummy_c buttons for all entry for list view
	$download_url = 'index.php?entryPoint=download&type=Documents&id=';
	$parent_id = $list_data["PARENT_ID"];
	$parent_type = $list_data['PARENT_TYPE'];
	if(($parent_type=='Leads') && (!stristr($task_name,'Birthday Reminder'))){
	    // Leads
	    $SQL = "
                  SELECT 
                        documents.document_revision_id id
                  FROM documents 
                     WHERE deleted = 0 AND id IN ( SELECT document_id FROM linked_documents WHERE deleted = 0
                     AND (parent_type = 'Accounts' OR parent_type = 'Leads') AND parent_id = '$parent_id') 
                     OR id IN ( 
                        SELECT accounts_d009acuments_idb FROM accounts_documents_c
                        WHERE deleted = 0 AND accounts_d13e2ccounts_ida = '$parent_id' 
                    ) 
                    ORDER BY date_modified DESC
                    LIMIT 1
	    ";
            $result = $db->query($SQL, true);
	    while($r = $db->fetchByAssoc($result)) {
		$download_url .= $r['id'];
		$setCompleteUrl = "<input type='button' value='Download' onclick='document.location.href=\"$download_url\"'>";
		$list_data['DOWNLOAD_DUMMY_C'] = $setCompleteUrl;
	    }
	}elseif(($parent_type=='Accounts') && (!stristr($task_name,'Account Reminder'))){
	    // Accounts
	    $SQL = "
		SELECT
		    document_revisions.id
		FROM accounts
		LEFT JOIN accounts_documents_c ON accounts.id = accounts_documents_c.accounts_d13e2ccounts_ida
		LEFT JOIN documents on documents.id = accounts_documents_c.accounts_d009acuments_idb
		LEFT JOIN document_revisions ON documents.document_revision_id = document_revisions.id
		LEFT JOIN linked_documents ON document_revisions.document_id = linked_documents.document_id
		WHERE accounts.id = '$parent_id' 
		ORDER BY document_revisions.date_modified DESC
		LIMIT 1
	    ";
	    $result = $db->query($SQL, true);
	    while($r = $db->fetchByAssoc($result)) {
		$download_url .= $r['id'];
		$setCompleteUrl = "<input type='button' value='Download' onclick='document.location.href=\"$download_url\"'>";
		$list_data['DOWNLOAD_DUMMY_C'] = $setCompleteUrl;
	    }
	}
	return $list_data;
    }

}


?>