<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2011-03-19 16:19:06
$dictionary["Account"]["fields"]["accounts_gsf_contributions"] = array (
  'name' => 'accounts_gsf_contributions',
  'type' => 'link',
  'relationship' => 'accounts_gsf_contributions',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_GSF_CONTRIBUTIONS_TITLE',
);


 // created: 2011-11-22 00:49:44

 

 // created: 2011-02-06 15:09:45

 

 // created: 2011-02-06 15:07:15

 

// created: 2011-03-19 16:17:15
$dictionary["Account"]["fields"]["accounts_gsf_withdrawals"] = array (
  'name' => 'accounts_gsf_withdrawals',
  'type' => 'link',
  'relationship' => 'accounts_gsf_withdrawals',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_GSF_WITHDRAWALS_TITLE',
);


 // created: 2013-01-02 14:54:16

 


$dictionary["Account"]["fields"]["accounts_total_premium_c"]["required"] = true;

$dictionary["Account"]["fields"]["status"] = array (
    'required' => false,
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'Client',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'reportable' => true,
    'len' => 100,
    'options' => 'gsf_account_status_list',
    'studio' => 'visible',
    'dependency' => false,
);
    
$dictionary["Account"]["fields"]["total_contributions"] = array (
    'required' => false,
    'name' => 'total_contributions',
    'vname' => 'LBL_TOTAL_CONTRIBUTIONS',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'default' => 0,
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 26,
    'size' => '20',
);

$dictionary["Account"]["fields"]["future_assets"] = array (
    'required' => false,
    'name' => 'future_assets',
    'vname' => 'LBL_FUTURE_ASSETS',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'reportable' => true,
    'len' => 100,
    'options' => 'gsf_future_assets_list',
    'studio' => 'visible',
    'dependency' => false,
);

$dictionary["Account"]["fields"]["bonus_percentage"] = array (
    'required' => false,
    'name' => 'bonus_percentage',
    'vname' => 'LBL_BONUS_PERCENTAGE',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '0',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'reportable' => true,
    'len' => 100,
    'options' => 'gsf_bonus_percentage_list',
    'studio' => 'visible',
    'dependency' => false,
);

$dictionary["Account"]["fields"]["adjustment"] = array (
    'required' => false,
    'name' => 'adjustment',
    'vname' => 'LBL_ADJUSTMENT',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'default' => 0,
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 26,
    'size' => '20',
);

$dictionary["Account"]["fields"]["total_withdrawals"] = array (
    'required' => false,
    'name' => 'total_withdrawals',
    'vname' => 'LBL_TOTAL_WITHDRAWALS',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'default' => 0,
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 26,
    'size' => '20',
);

$dictionary["Account"]["fields"]["current_value"] = array (
    'required' => false,
    'name' => 'current_value',
    'vname' => 'LBL_CURRENT_VALUE',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'default' => 0,
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 26,
    'size' => '20',
);



$dictionary["Account"]["fields"]["account_days_left_to_anniv"] = array (
    'name' => 'account_days_left_to_anniv',
    'vname' => 'LBL_ACCOUNT_DAYS_LEFT_TO_ANNIV',
    'type' => 'varchar',
    'len' => '5',
    'reportable' => true,
    'importable' => true,
);



//create many-to-one relationship (Accounts-Leads)
$dictionary["Account"]["fields"]["lead_id"] = array (
    'name' => 'lead_id',
    'vname' => 'LBL_LEAD_ID',
    'required' => false,
    'type' => 'id',
    'reportable' => true,
    'massupdate' => false,
    'audited' => true,
);
//create many-to-one relationship (Accounts-Leads)
$dictionary["Account"]["fields"]["lead_name"] = array (
    'required' => false,
    'name' => 'lead_name',
    'rname' => 'name',
    'id_name' => 'lead_id',
    'vname' => 'LBL_LEAD_NAME',
    'type' => 'relate',
    'table' => 'leads',
    'isnull' => 'true',
    'module' => 'Leads',
    'link' => 'lead_link',
    'massupdate' => false,
    'source' => 'non-db',
    'reportable' => true,
);
//create many-to-one relationship (Accounts-Leads)
$dictionary["Account"]["fields"]["lead_link"] = array (
    'name' => 'lead_link',
    'type' => 'link',
    'relationship' => 'lead_accounts',
    'vname' => 'LBL_LEAD_NAME',
    'link_type' => 'one',
    'module' => 'Leads',
    'bean_name' => 'Lead',
    'source' => 'non-db',
);
//remove the one-to-many relationship (Accounts-Leads)
unset($dictionary["Account"]["fields"]["leads"]);
unset($dictionary["Account"]["relationships"]["account_leads"]);



$dictionary['Account']['fields']['gsf_contributions_link'] = array (
    'name' => 'gsf_contributions_link',
    'type' => 'link',
    'relationship' => 'account_gsf_contributions',
    'module'=>'GSF_Contributions',
    'bean_name'=>'GSF_Contributions',
    'source'=>'non-db',
    'vname'=>'LBL_GSF_CONTRIBUTIONS',
);

$dictionary['Account']['relationships']['account_gsf_contributions'] = array (
    'lhs_module'=> 'Accounts',
    'lhs_table'=> 'accounts',
    'lhs_key' => 'id',
    'rhs_module'=> 'GSF_Contributions',
    'rhs_table'=> 'gsf_contributions',
    'rhs_key' => 'account_id',
    'relationship_type'=>'one-to-many',
);



$dictionary["Account"]["indices"] = array (
    array('name' => 'idx_account_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_account_status', 'type' => 'index', 'fields'=> array('status')),
    array('name' => 'idx_account_lead', 'type' => 'index', 'fields'=> array('lead_id')),
);



// START - Custom fields for KINAMU Reports

$query_total_premium = "(SELECT
                            (SUM(COALESCE(ac.accounts_total_premium_c,0)) + SUM(COALESCE(a.total_contributions,0)))
                        FROM
                            gsf_seminardetails s
                        LEFT JOIN
                            gsf_seminaretails_leads_c sl
                        ON
                            s.id = sl.gsf_semina6647details_ida
                        LEFT JOIN
                            leads l
                        ON
                            sl.gsf_semina5325dsleads_idb = l.id
                        LEFT JOIN
                            accounts a
                        ON
                            l.id = a.lead_id
                        LEFT JOIN
                            accounts_cstm ac
                        ON
                            a.id = ac.id_c
                        WHERE
                            s.deleted = 0 AND
                            sl.deleted = 0 AND
                            l.deleted = 0 AND
                            a.assigned_user_id = ($.assigned_user_id) AND
                            a.deleted = 0)";

$dictionary['Account']['fields']['total_premium'] = array(
    'name' => 'total_premium',
    'vname' => 'LBL_TOTAL_PREMIUM',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "FORMAT($query_total_premium,2)",
);

$query_average_premium = "( SELECT
                                AVG(tbl.total)
                            FROM (SELECT
                                    SUM(COALESCE(ac.accounts_total_premium_c,0)) as total,
                                    a.assigned_user_id as agent
                                FROM
                                    gsf_seminardetails s
                                LEFT JOIN
                                    gsf_seminaretails_leads_c sl
                                ON
                                    s.id = sl.gsf_semina6647details_ida
                                LEFT JOIN
                                    leads l
                                ON
                                    sl.gsf_semina5325dsleads_idb = l.id
                                LEFT JOIN
                                    accounts a
                                ON
                                    l.id = a.lead_id
                                LEFT JOIN
                                    accounts_cstm ac
                                ON
                                    a.id = ac.id_c
                                WHERE
                                    s.deleted = 0 AND
                                    sl.deleted = 0 AND
                                    l.deleted = 0 AND
                                    a.deleted = 0
                                GROUP BY
                                    s.id,
                                    a.assigned_user_id) AS tbl
                            WHERE
                                tbl.agent = ($.assigned_user_id))";
                                
$query_average_contributions = "( SELECT
                                AVG(tbl.total)
                            FROM (SELECT
                                    SUM(COALESCE(a.total_contributions,0)) as total,
                                    a.assigned_user_id as agent
                                FROM
                                    gsf_seminardetails s
                                LEFT JOIN
                                    gsf_seminaretails_leads_c sl
                                ON
                                    s.id = sl.gsf_semina6647details_ida
                                LEFT JOIN
                                    leads l
                                ON
                                    sl.gsf_semina5325dsleads_idb = l.id
                                LEFT JOIN
                                    accounts a
                                ON
                                    l.id = a.lead_id
                                LEFT JOIN
                                    accounts_cstm ac
                                ON
                                    a.id = ac.id_c
                                WHERE
                                    s.deleted = 0 AND
                                    sl.deleted = 0 AND
                                    l.deleted = 0 AND
                                    a.deleted = 0
                                GROUP BY
                                    s.id,
                                    a.assigned_user_id) AS tbl
                            WHERE
                                tbl.agent = ($.assigned_user_id))";

$dictionary['Account']['fields']['average_premium'] = array(
    'name' => 'average_premium',
    'vname' => 'LBL_AVERAGE_PREMIUM',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "FORMAT(($query_average_premium + $query_average_contributions),2)",
);

// END - Custom fields for KINAMU Reports

 

 // created: 2011-02-06 15:07:37

 

 // created: 2011-02-06 15:08:20

 

 // created: 2011-02-06 15:08:39

 

 // created: 2011-02-06 15:06:48

 

// created: 2011-02-07 13:04:32
$dictionary["Account"]["fields"]["accounts_documents"] = array (
  'name' => 'accounts_documents',
  'type' => 'link',
  'relationship' => 'accounts_documents',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);


 // created: 2011-02-06 15:10:50

 

 // created: 2011-02-06 15:08:59

 

 // created: 2011-02-06 15:09:18

 

// created: 2011-03-19 16:15:48
$dictionary["Account"]["fields"]["accounts_gsf_sourceaccounts"] = array (
  'name' => 'accounts_gsf_sourceaccounts',
  'type' => 'link',
  'relationship' => 'accounts_gsf_sourceaccounts',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_SOURCEACCOUNTS_FROM_GSF_SOURCEACCOUNTS_TITLE',
);


 // created: 2011-02-06 15:10:05

 

 // created: 2013-01-02 14:40:13

 

 // created: 2011-02-06 15:06:25

 

 // created: 2011-02-06 15:13:58
$dictionary['Account']['fields']['description']['rows']='4';

 
?>