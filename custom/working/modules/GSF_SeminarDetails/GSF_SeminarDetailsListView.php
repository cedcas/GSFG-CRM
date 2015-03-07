<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class GSF_SeminarDetailsListView extends GSF_SeminarDetails
{
    /* Override each entry for Salesperson column: using gsf_seminar_details_users table
     * @ KMJ 2011-11-21
     *
     * CPC
     * 9/17/2012
     * Added the 'deleted=0' in the WHERE clause to accurately find the user for the seminar details;
     */
    function get_list_view_data(){
	global $db;
	
	$list_data = parent::get_list_view_data();

	$user_link = 'index.php?module=MADERAL&action=DetailView&record=';
	$gsf_details_id = $list_data["ID"];
	$SQL = "
	    SELECT user_id, CONCAT(IFNULL(users.first_name,''),' ',IFNULL(users.last_name,'')) user_name FROM gsf_seminardetails_users
	    LEFT JOIN users on users.id = gsf_seminardetails_users.user_id
	    WHERE gsf_seminardetails_users.gsf_seminardetails_id = '$gsf_details_id'
	    	AND gsf_seminardetails_users.deleted =  '0'
	";
	$result = $db->query($SQL, true);
	while($r = $db->fetchByAssoc($result)) {
	    $list_data['ASSIGNED_USER_NAME'] = $r['user_name'];
	    $list_data['ASSIGNED_USER_ID'] = $r['user_id'];
	}
	return $list_data;
    }
}


?>