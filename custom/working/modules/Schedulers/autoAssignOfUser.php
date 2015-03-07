<?php
$GLOBALS['log']->debug("START - Auto Assign of User");
global $sugar_config;
$db = DBManagerFactory::getInstance();

/* cron job - update leads.assigned_user_id from assigned seminardetails (host)
 * @KMJ 2011-11-13
 */

class LeadUpdater {
    
    private $host;
    private $user_name;
    private $password;
    private $schema;
    private $connection;
    private $leads_list;
    
    function __construct() {
	include 'config.php';
	$this->host = $sugar_config['dbconfig']['db_host_name'];
	$this->user_name = $sugar_config['dbconfig']['db_user_name'];
	$this->password = $sugar_config['dbconfig']['db_password'];
	$this->schema = $sugar_config['dbconfig']['db_name'];
	$this->leads_list = array();
    }
    
    function dbConnect() {
	if (!$this->connection = mysql_connect($this->host, $this->user_name , $this->password)) {
	    //print_r("Connection Fails!");
	    return false;}
	else {
	    mysql_select_db($this->schema, $this->connection); 
	    //print_r("Connection Passes!");
	    return true;
	}
    }
    
    function setLeadsList() {
	$SQL_target_leads = "
	    SELECT leads.id FROM leads WHERE assigned_user_id IN (
		SELECT id FROM users WHERE is_admin = 1
	    )
	";
	$result = mysql_query($SQL_target_leads, $this->connection);
	while($leads = mysql_fetch_object($result)) {
	    // get only 1 assigned for a seminar
	    $SQL_update_lead_seminardetails_host = "
		UPDATE leads SET assigned_user_id = (
		    SELECT
			a.user_id
		    FROM gsf_seminardetails_users a
		    LEFT JOIN gsf_seminardetails b on a.gsf_seminardetails_id = b.id
		    LEFT JOIN gsf_seminaretails_leads_c c on b.id = c.gsf_semina6647details_ida
		    LEFT JOIN users on users.id = a.user_id
		    WHERE c.gsf_semina5325dsleads_idb = '$leads->id'
		    ORDER BY c.date_modified desc LIMIT 1
		) WHERE leads.id = '$leads->id'
	    ";
	    mysql_query($SQL_update_lead_seminardetails_host, $this->connection);
	}
    }
}

$lead_updater = new LeadUpdater();
if(!$lead_updater->dbConnect()) {
    echo "Error Connecting to Database".
    die();
}
$lead_updater->setLeadsList();

$GLOBALS['log']->debug("END - Auto Assign of User");
?>