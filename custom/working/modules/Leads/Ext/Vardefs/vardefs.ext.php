<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2012-08-14 14:58:33

 

 // created: 2011-03-23 21:21:34

 

 // created: 2011-01-31 21:51:30

 

 // created: 2011-02-17 23:51:39

 

// created: 2011-04-12 19:42:53
$dictionary["Lead"]["fields"]["gsf_seminardetails_leads"] = array (
  'name' => 'gsf_seminardetails_leads',
  'type' => 'link',
  'relationship' => 'gsf_seminardetails_leads',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SEMINARDETAILS_LEADS_FROM_GSF_SEMINARDETAILS_TITLE',
);
$dictionary["Lead"]["fields"]["gsf_seminardetails_leads_name"] = array (
  'name' => 'gsf_seminardetails_leads_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SEMINARDETAILS_LEADS_FROM_GSF_SEMINARDETAILS_TITLE',
  'save' => true,
  'id_name' => 'gsf_semina6647details_ida',
  'link' => 'gsf_seminardetails_leads',
  'table' => 'gsf_seminardetails',
  'module' => 'GSF_SeminarDetails',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["gsf_semina6647details_ida"] = array (
  'name' => 'gsf_semina6647details_ida',
  'type' => 'link',
  'relationship' => 'gsf_seminardetails_leads',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_GSF_SEMINARDETAILS_LEADS_FROM_LEADS_TITLE',
);


 // created: 2011-03-01 23:33:21

 

 // created: 2012-09-04 11:58:57

 

 // created: 2012-09-04 12:25:49

 


$dictionary['Lead']['fields']['lead_source']['required'] = true;
$dictionary['Lead']['fields']['primary_address_street']['required'] = false;
$dictionary['Lead']['fields']['primary_address_city']['required'] = false;
$dictionary['Lead']['fields']['primary_address_state']['required'] = false;
$dictionary['Lead']['fields']['primary_address_postalcode']['required'] = false;
$dictionary['Lead']['fields']['primary_address_country']['required'] = false;

$dictionary["Lead"]["fields"]["seminar_capacity"] = array (
    'name' => 'seminar_capacity',
    'vname' => 'LBL_SEMINAR_CAPACITY',
    'type' => 'varchar',
    'len' => '255',
    'reportable' => true,
    'importable' => true,
);

// 2/20/2013 - CPC & CS
// Rachel requested a new field 'Wedding Anniversary'

$dictionary["Lead"]["fields"]["wedding_anniv"] = array (
    'name' => 'wedding_anniv',
    'vname' => 'LBL_LEAD_WEDDING_ANNIV',
    'type' => 'date',
    'reportable' => true,
    'importable' => true,
);

// 6/29/2013
// BT46 - Spouse Birthday Reminder feature

$dictionary["Lead"]["fields"]["lead_spouse_days_left_to_birthday"] = array (
    'name' => 'lead_spouse_days_left_to_birthday',
    'vname' => 'LBL_LEAD_SPOUSE_DAYS_LEFT_TO_BIRTHDAY',
    'type' => 'varchar',
    'len' => '5',
    'reportable' => true,
    'importable' => true,
);

// 8/18/2012
// BT46 - Birthday Reminder feature

$dictionary["Lead"]["fields"]["lead_days_left_to_birthday"] = array (
    'name' => 'lead_days_left_to_birthday',
    'vname' => 'LBL_LEAD_DAYS_LEFT_TO_BIRTHDAY',
    'type' => 'varchar',
    'len' => '5',
    'reportable' => true,
    'importable' => true,
);


/*
//20110808 - These fields are moved to Accounts module

$dictionary["Lead"]["fields"]["account_upcoming_anniv"] = array (
    'name' => 'account_upcoming_anniv',
    'vname' => 'LBL_ACCOUNT_UPCOMING_ANNIV',
    'type' => 'date',
    'reportable' => true,
    'importable' => true,
);

$dictionary["Lead"]["fields"]["account_days_left_to_anniv"] = array (
    'name' => 'account_days_left_to_anniv',
    'vname' => 'LBL_ACCOUNT_DAYS_LEFT_TO_ANNIV',
    'type' => 'varchar',
    'len' => '5',
    'reportable' => true,
    'importable' => true,
);

$dictionary["Lead"]["fields"]["account_last_anniv_processed"] = array (
    'name' => 'account_last_anniv_processed',
    'vname' => 'LBL_ACCOUNT_LAST_ANNIV_PROCESSED',
    'type' => 'date',
    'reportable' => true,
    'importable' => true,
);
*/

$dictionary["Lead"]["fields"]["attendee_id"] = array (
    'name' => 'attendee_id',
    'vname' => 'LBL_ATTENDEE_ID',
    'type' => 'varchar',
    'len' => '100',
    'reportable' => true,
    'importable' => true,
);

$dictionary["Lead"]["fields"]["main_attendee_id"] = array (
    'name' => 'main_attendee_id',
    'vname' => 'LBL_MAIN_ATTENDEE_ID',
    'type' => 'varchar',
    'len' => '100',
    'reportable' => true,
    'importable' => true,
);



/* FIELDS FOR EMAIL/PDF TEMPLATE */
$dictionary["Lead"]["fields"]["seminar_title"] = array (
    'name' => 'seminar_title',
    'vname' => 'LBL_SEMINAR_TITLE',
    'type' => 'varchar',
    'len' => '255',
    'reportable' => false,
    'importable' => false,
    'comment' => 'for making Email Template only',
);
$dictionary["Lead"]["fields"]["before_meeting_start"] = array (
    'name' => 'before_meeting_start',
    'vname' => 'LBL_BEFORE_MEETING_START',
    'type' => 'varchar',
    'len' => '50',
    'reportable' => false,
    'importable' => false,
    'comment' => 'for making Email Template only',
);
$dictionary["Lead"]["fields"]["after_meeting_start"] = array (
    'name' => 'after_meeting_start',
    'vname' => 'LBL_AFTER_MEETING_START',
    'type' => 'varchar',
    'len' => '50',
    'reportable' => false,
    'importable' => false,
    'comment' => 'for making Email Template only',
);
$dictionary["Lead"]["fields"]["venue_logo_img"] = array (
    'name' => 'venue_logo_img',
    'vname' => 'LBL_VENUE_LOGO_IMG',
    'type' => 'varchar',
    'len' => '150',
    'reportable' => false,
    'importable' => false,
    'comment' => '<img> tag, for making Email Template only',
);
$dictionary["Lead"]["fields"]["venue_logo_filename"] = array (
    'name' => 'venue_logo_filename',
    'vname' => 'LBL_VENUE_LOGO_FILENAME',
    'type' => 'varchar',
    'len' => '150',
    'reportable' => false,
    'importable' => false,
    'comment' => 'file name of the logo only',
);
/* FIELDS FOR EMAIL/PDF TEMPLATE */




$dictionary["Lead"]["fields"]["documents"] = array (
    'name' => 'documents',
    'type' => 'link',
    'relationship' => 'leads_documents',
    'source' => 'non-db',
    'vname' => 'LBL_DOCUMENTS',
);

//create 1-to-many relationship (Leads-Accounts)
$dictionary["Lead"]["fields"]["accounts_link"] = array (
    'name' => 'accounts_link',
    'type' => 'link',
    'relationship' => 'lead_accounts',
    'source' => 'non-db',
    'vname' => 'LBL_ACCOUNTS',
);
//create 1-to-many relationship (Leads-Accounts)
$dictionary["Lead"]["relationships"]["lead_accounts"] = array (
    'lhs_module'=> 'Leads',
    'lhs_table'=> 'leads',
    'lhs_key' => 'id',
    'rhs_module'=> 'Accounts',
    'rhs_table'=> 'accounts',
    'rhs_key' => 'lead_id',
    'relationship_type'=>'one-to-many'
);

//remove the many-to-one relationship (Leads-Accounts)
unset($dictionary["Lead"]["fields"]["accounts"]);





$dictionary["Lead"]["indices"] = array (
    array('name' => 'idx_lead_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_lead_status', 'type' => 'index', 'fields'=> array('status')),
    array('name' => 'idx_lead_source', 'type' => 'index', 'fields'=> array('lead_source')),
    array('name' => 'idx_lead_attendee_id', 'type' => 'index', 'fields'=> array('attendee_id')),
);





/** START Custom fields used for KINAMU Reports **/

$dictionary['Lead']['fields']['name_kreport'] = array(
    'name' => 'name_kreport',
    'vname' => 'LBL_NAME',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => 'CONCAT($.first_name, \' \', $.last_name)',
);


$query_repeat_business_premium = "(SELECT
                                        COALESCE(SUM(ac.accounts_total_premium_c), 0)
                                    FROM
                                        leads l
                                    LEFT JOIN
                                        accounts a
                                    ON
                                        l.id = a.lead_id
                                    LEFT JOIN
                                        accounts_cstm ac
                                    ON
                                        a.id = ac.id_c
                                    WHERE
                                        l.lead_source = ($.lead_source) AND
                                        l.deleted = 0 AND
                                        ac.accounts_repeat_client_c = 1 AND
                                        a.deleted = 0)";
                                        

$dictionary['Lead']['fields']['repeat_business_premium'] = array(
    'name' => 'repeat_business_premium',
    'vname' => 'LBL_REPEAT_BUSINESS_PREMIUM',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "FORMAT($query_repeat_business_premium,2)",
);

$query_total_premium = "(SELECT
                            COALESCE(SUM(ac.accounts_total_premium_c),0) + COALESCE(SUM(a.total_contributions),0)
                        FROM
                            leads l
                        LEFT JOIN
                            accounts a
                        ON
                            l.id = a.lead_id
                        LEFT JOIN
                            accounts_cstm ac
                        ON
                            a.id = ac.id_c
                        WHERE
                            l.lead_source = ($.lead_source) AND
                            l.deleted = 0 AND
                            a.deleted = 0)";
                        
$dictionary['Lead']['fields']['total_premium'] = array(
    'name' => 'total_premium',
    'vname' => 'LBL_TOTAL_PREMIUM',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "FORMAT($query_total_premium,2)",
);

$query_total_contribution = "(SELECT
                                    COALESCE(SUM(c.gsf_contribution_amount), 0)
                                FROM
                                    leads l
                                LEFT JOIN
                                    accounts a
                                ON
                                    l.id = a.lead_id
                                LEFT JOIN
                                    gsf_contributions c
                                ON
                                    a.id = c.account_id
                                WHERE
                                    l.lead_source = ($.lead_source) AND
                                    l.deleted = 0 AND
                                    a.deleted = 0 AND
                                    c.deleted = 0
                                )";


$dictionary['Lead']['fields']['repeat_business_ratio'] = array(
    'name' => 'repeat_business_ratio',
    'vname' => 'LBL_REPEAT_BUSINESS_RATIO',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "CONCAT(ROUND( ( ($query_repeat_business_premium + $query_total_contribution) / $query_total_premium) * 100, 2), '%')",
);


$query_accounts_total_premium = "(SELECT
                                    SUM(COALESCE(ac.accounts_total_premium_c,0))
                                FROM
                                    accounts a
                                LEFT JOIN
                                    accounts_cstm ac
                                ON
                                    a.id = ac.id_c
                                WHERE
                                    a.deleted = 0)";
                            
$dictionary['Lead']['fields']['accounts_total_premium'] = array(
    'name' => 'accounts_total_premium',
    'vname' => 'LBL_ACCOUNTS_TOTAL_PREMIUM',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "FORMAT($query_accounts_total_premium,2)",
);

$query_count_client = "(SELECT
                            COUNT(id)
                        FROM
                            leads
                        WHERE
                            status = 'Client' AND
                            deleted = 0)";

$dictionary['Lead']['fields']['accounts_total_premium_average'] = array(
    'name' => 'accounts_total_premium_average',
    'vname' => 'LBL_ACCOUNTS_ACCOUNTS_TOTAL_PREMIUM_AVERAGE',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "FORMAT(($query_accounts_total_premium / $query_count_client),2)",
);

/** START Custom fields used for KINAMU Reports **/


 // created: 2011-01-31 21:50:08

 

 // created: 2011-03-03 19:33:21

 

 // created: 2011-01-31 21:23:07

 

 // created: 2011-03-19 03:46:49

 

 // created: 2011-03-01 23:32:16

 

 // created: 2012-10-09 12:29:11

 

 // created: 2012-10-09 16:19:11

 

 // created: 2011-02-17 23:48:47

 

 // created: 2011-01-31 21:54:53

 

 // created: 2012-09-04 11:58:23

 
/*************************************Project: GoldstoneOriginal Dev: Angel Magaña, March 2011@2011 Angel Magañacheleguanaco[at]cheleguanaco.comDesc: Functions for ages The contents of this file are governed by the GNU General Public License (GPL).A copy of said license is available here: http://www.gnu.org/copyleft/gpl.htmlThis code is provided AS IS and WITHOUT WARRANTY OF ANY KIND.*************************************/$dictionary['Lead']['fields']['age_c']['function'] = array('name'=>'getAgeMain', 'returns'=>'html', 'include'=>'custom/modules/Leads/CustomAge.php');$dictionary['Lead']['fields']['spouse_age_c']['function'] = array('name'=>'getAgeSpouse', 'returns'=>'html', 'include'=>'custom/modules/Leads/CustomAge.php');

 // created: 2011-02-17 23:48:47

 

 // created: 2011-02-17 23:49:44

 

 // created: 2011-01-31 21:50:40

 

 // created: 2011-02-17 23:45:10

 

 // created: 2011-08-03 07:11:21

 

 // created: 2011-02-17 23:48:47

 

 // created: 2012-09-27 10:38:51

 

 // created: 2011-01-31 21:53:27

 

 // created: 2011-01-31 21:54:23

 

 // created: 2011-01-31 21:23:34

 

 // created: 2011-01-31 21:53:54

 

 // created: 2011-01-31 21:52:41

 

 // created: 2011-01-31 21:56:30

 
?>