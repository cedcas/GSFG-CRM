<?php

class custom_GSF_SeminarDetails {
	
    private $seminarDetails;
    
    function custom_GSF_SeminarDetails(){
    }
    
    function setSeminarDetails($seminarDetails) {
	$this->seminarDetails = $seminarDetails;
    }
    
    function getSeminarDetails() {
	return $this->seminarDetails;
    }
    
    function populateSeminarDetailsHost() {
	try {
	    $soapData = $this->seminarDetails;
	    if(!empty($soapData)){
		foreach($soapData as $key => $data) {
		    foreach($data['name_value_list'] as $k => $d) {
			if($k=='id'){
			    $hosts = $this->extractUsersToHost($d['value']);
			    $assigned_user_id = $this->extractHostId($d['value']);
			    foreach($data['name_value_list'] as $kd => $search) {
				if($search['name']=='assigned_user_name'){
				    $soapData[$key]['name_value_list']['assigned_user_name']['value'] = $hosts;
				}
				if($search['name']=='assigned_user_id'){
				    $soapData[$key]['name_value_list']['assigned_user_id']['value'] = $assigned_user_id;
				}
			    }
			    
			    // force add logo
			    $soapData[$key]['name_value_list']['logo']['name'] = 'logo';
			    $soapData[$key]['name_value_list']['logo']['value'] = $this->getVenueLogo($d['value']);
			}
		    }
		}
	    }
	    $this->setSeminarDetails($soapData);
	}catch(Exception $e){
	    $GLOBALS['log']->fatal($e->getMessage());
	}
    }
    
    function extractUsersToHost($gsf_seminardetails_id) {
	try {
	    global $db;
	    
	    $SQL = "
		SELECT
		    CONCAT(IFNULL(b.first_name,''),' ',IFNULL(b.last_name,'')) assigned_user_name
		FROM
		    gsf_seminardetails_users a
		LEFT JOIN users b on b.id = a.user_id
		WHERE a.deleted = 0 and b.deleted = 0
		AND a.gsf_seminardetails_id = '$gsf_seminardetails_id'
		
	    ";
	    $hosts = array();
	    $result = $db->query($SQL, true);
	    while($h = $db->fetchByAssoc($result)){
		array_push($hosts,$h['assigned_user_name']);
	    }
	    $hosts = implode(',',$hosts);
	    return $hosts;
	}catch(Exception $e){
	    $GLOBALS['log']->fatal($e->getMessage());
	}
    }
    
    function extractHostId($gsf_seminardetails_id) {
	try {
	    global $db;
	    
	    $SQL = "
		SELECT
		    a.user_id
		FROM
		    gsf_seminardetails_users a
		LEFT JOIN users b on b.id = a.user_id
		WHERE a.deleted = 0 and b.deleted = 0
		AND a.gsf_seminardetails_id = '$gsf_seminardetails_id'
		ORDER BY a.date_modified DESC LIMIT 1
		
	    ";
	    $hosts = array();
	    $result = $db->query($SQL, true);
	    while($h = $db->fetchByAssoc($result)){
		array_push($hosts,$h['user_id']);
	    }
	    $hosts = implode(',',$hosts);
	    return $hosts;
	}catch(Exception $e){
	    $GLOBALS['log']->fatal($e->getMessage());
	    return "";
	}
    }
    
    function setNewSeminarLead($gsf_semina6647details_ida, $gsf_semina5325dsleads_idb) {
	try {
	    global $db;
	    global $timedate;
	    
	    $result = $db->query("SELECT uuid() as id", true);
	    $uuid = $db->fetchByAssoc($result);
	    if(!empty($uuid['id'])) {
		$id = $uuid['id'];
		$SQL = "
		    INSERT INTO gsf_seminaretails_leads_c (
			gsf_semina6647details_ida,
			gsf_semina5325dsleads_idb,
			deleted,
			date_modified,
			id
		    ) VALUES (
			'$gsf_semina6647details_ida',
			'$gsf_semina5325dsleads_idb',
			0,
			now(),
			'$id'
		    )
		";
		$result = $db->query($SQL, true);
		
                # insert data for email trigger
                require_once('modules/PM_ProcessManager/insertIntoPmEntryTable.php');
                $hook = new insertIntoPmEntryTable();
                if(class_exists('insertIntoPmEntryTable')){
                    // tableName (leads), objectId (leads.id), update_or_insert (string)
                    $result = $hook->insertIntoProcessMgrEntryTable('leads',$gsf_semina5325dsleads_idb,'insert');
                }else{
                    $GLOBALS['log']->fatal(__FILE__.' - Data for email trigger not sent.');
                }
                
		return array(true,$id);
	    }
	    return array(false,-1);
	}catch(Exception $e){
	    $GLOBALS['log']->fatal($e->getMessage());
	    return array(false,-1);
	}
    }
    
    function getVenueLogo($gsf_seminardetails_id) {
	try {
	    global $db;
	    
	    $SQL = "
		SELECT a.venue_logo, a.venue_logo_filename
		FROM gsf_venues a
		LEFT JOIN gsf_venues_minardetails_c b ON a.id = b.gsf_venues56d9_venues_ida
		LEFT JOIN gsf_seminardetails c ON c.id = b.gsf_venuesc61bdetails_idb
		WHERE a.deleted = 0 AND b.deleted = 0 AND c.deleted = 0
		AND c.id = '$gsf_seminardetails_id'
	    ";
	    
	    $result = $db->query($SQL, true);
	    $logo = '';
	    while($h = $db->fetchByAssoc($result)){
		$logo = $h['venue_logo'];
		$logo = str_replace("{venue_logo_filename}",$h['venue_logo_filename'],$logo);
	    }
	    return $logo;
	}catch(Exception $e){
	    $GLOBALS['log']->fatal($e->getMessage());
	    return "";
	}
    }
}

?>