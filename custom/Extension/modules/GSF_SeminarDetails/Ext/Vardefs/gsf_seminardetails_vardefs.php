<?php

$dictionary['GSF_SeminarDetails']['fields']['details_capacity']['required'] = true;
$dictionary['GSF_SeminarDetails']['fields']['details_from_date']['required'] = true;
$dictionary['GSF_SeminarDetails']['fields']['details_from_time']['required'] = true;
$dictionary['GSF_SeminarDetails']['fields']['gsf_venues_gsf_seminardetails_name']['required'] = true;

$dictionary['GSF_SeminarDetails']['fields']['users'] = array (
    'name' => 'users',
    'type' => 'link',
    'relationship' => 'gsf_seminardetails_users',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
    'vname'=>'LBL_USERS',
);

$dictionary['GSF_SeminarDetails']['fields']['gsf_contributions_link'] = array (
    'name' => 'gsf_contributions_link',
    'type' => 'link',
    'relationship' => 'gsf_seminardetails_gsf_contributions',
    'module'=>'GSF_Contributions',
    'bean_name'=>'GSF_Contributions',
    'source'=>'non-db',
    'vname'=>'LBL_GSF_SEMINARDETAILS',
);

$dictionary['GSF_SeminarDetails']['relationships']['gsf_seminardetails_gsf_contributions'] = array (
    'lhs_module'=> 'GSF_SeminarDetails',
    'lhs_table'=> 'gsf_seminardetails',
    'lhs_key' => 'id',
    'rhs_module'=> 'GSF_Contributions',
    'rhs_table'=> 'gsf_contributions',
    'rhs_key' => 'gsf_seminardetails_id',
    'relationship_type'=>'one-to-many',
);


$dictionary["GSF_SeminarDetails"]["fields"]["meeting_id"] = array (
    'required' => false,
    'name' => 'meeting_id',
    'vname' => 'LBL_MEETING_ID',
    'type' => 'varchar',
    'len' => '150',
    'massupdate' => 0,
    'importable' => 'true',
    'audited' => true,
    'reportable' => true,
);


$dictionary["GSF_SeminarDetails"]["indices"] = array (
    array('name' => 'idx_seminardetails_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_seminardetails_meeting_id', 'type' => 'index', 'fields'=> array('meeting_id')),
    array('name' => 'idx_seminardetails_date_from', 'type' => 'index', 'fields'=> array('details_from_date')),
    array('name' => 'idx_seminardetails_date_to', 'type' => 'index', 'fields'=> array('details_to_date')),
);



/** START Custom fields used for KINAMU Reports **/

//MARKETING REPORT
$dictionary['GSF_SeminarDetails']['fields']['program_drop_off_rate'] = array(
    'name' => 'program_drop_off_rate',
    'vname' => 'LBL_PROGRAM_DROP_OFF_RATE',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => 'CONCAT(ROUND((1 - ($.details_buts_in_sits / $.details_registered)) * 100, 2), "%")',
);

$query = "
SELECT
    s.seminar_number_of_mailers
FROM
    gsf_seminarminardetails_c sd
LEFT JOIN
    gsf_seminars s
ON
    sd.gsf_seminac629eminars_ida = s.id
WHERE
    sd.gsf_semina6236details_idb = ($.id) AND
    sd.deleted = 0 AND
    s.deleted = 0
LIMIT 1
";

$dictionary['GSF_SeminarDetails']['fields']['mailer_response_rate'] = array(
    'name' => 'mailer_response_rate',
    'vname' => 'LBL_MAILER_RESPONSE_RATE',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "CONCAT(ROUND(($.details_registered / ($query)) * 100, 2), \"%\")",
);

$dictionary['GSF_SeminarDetails']['fields']['newbu_vs_bis'] = array(
    'name' => 'newbu_vs_bis',
    'vname' => 'LBL_NEWBU_VS_BIS',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => 'CONCAT(ROUND(($.details_buying_units / $.details_buts_in_sits) * 100, 2), "%")',
);

$dictionary['GSF_SeminarDetails']['fields']['agent_response_rate'] = array(
    'name' => 'agent_response_rate',
    'vname' => 'LBL_AGENT_RESPONSE_RATE',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => 'CONCAT(ROUND(($.details_blue_sheets / $.details_buying_units) * 100, 2), "%")',
);

$dictionary['GSF_SeminarDetails']['fields']['appointment_response_rate'] = array(
    'name' => 'appointment_response_rate',
    'vname' => 'LBL_APPOINTMENT_RESPONSE_RATE',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => 'CONCAT(ROUND(($.details_appointment_sets / $.details_blue_sheets) * 100, 2), "%")',
);

//SALES REPORT
$query_appointment_held = "(SELECT
                    COUNT(l.id)
                FROM
                    gsf_seminaretails_leads_c sl
                LEFT JOIN
                    leads l
                ON
                    sl.gsf_semina5325dsleads_idb = l.id
                LEFT JOIN
                    leads_cstm lc
                ON
                    l.id = lc.id_c
                WHERE
                    sl.gsf_semina6647details_ida = ($.id) AND
                    sl.deleted = 0 AND
                    l.deleted = 0 AND
                    lc.seminar_attended_c = 1)";
$dictionary['GSF_SeminarDetails']['fields']['appointment_held'] = array(
    'name' => 'appointment_held',
    'vname' => 'LBL_APPOINTMENT_HELD',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "$query_appointment_held",
);

$query_clients_open = "(SELECT
                            COUNT(l.id)
                        FROM
                            gsf_seminaretails_leads_c sl
                        LEFT JOIN
                            leads l
                        ON
                            sl.gsf_semina5325dsleads_idb = l.id
                        WHERE
                            sl.gsf_semina6647details_ida = ($.id) AND
                            sl.deleted = 0 AND
                            l.status = 'Client' AND
                            l.deleted = 0)";
$dictionary['GSF_SeminarDetails']['fields']['clients_open'] = array(
    'name' => 'clients_open',
    'vname' => 'LBL_CLIENTS_OPEN',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "$query_clients_open",
);


$dictionary['GSF_SeminarDetails']['fields']['accounts_open'] = array(
    'name' => 'accounts_open',
    'vname' => 'LBL_ACCOUNTS_OPEN',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "(SELECT
                    COUNT(a.id)
                FROM
                    gsf_seminaretails_leads_c sl
                LEFT JOIN
                    leads l
                ON
                    sl.gsf_semina5325dsleads_idb = l.id
                LEFT JOIN
                    accounts a
                ON
                    l.id = a.lead_id
                WHERE
                    sl.gsf_semina6647details_ida = ($.id) AND
                    sl.deleted = 0 AND
                    l.deleted = 0 AND
                    a.status = 'Client' AND
                    a.deleted = 0)",
);

$dictionary['GSF_SeminarDetails']['fields']['accounts_cancelled'] = array(
    'name' => 'accounts_cancelled',
    'vname' => 'LBL_ACCOUNTS_CANCELLED',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "(SELECT
                    COUNT(a.id)
                FROM
                    gsf_seminaretails_leads_c sl
                LEFT JOIN
                    leads l
                ON
                    sl.gsf_semina5325dsleads_idb = l.id
                LEFT JOIN
                    accounts a
                ON
                    l.id = a.lead_id
                WHERE
                    sl.gsf_semina6647details_ida = ($.id) AND
                    sl.deleted = 0 AND
                    l.deleted = 0 AND
                    a.status = 'Cancelled' AND
                    a.deleted = 0)",
);

$dictionary['GSF_SeminarDetails']['fields']['appointment_ratio'] = array(
    'name' => 'appointment_ratio',
    'vname' => 'LBL_APPOINTMENT_RATIO',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "ROUND(($query_appointment_held) / $.details_appointment_sets, 2)",
);

$dictionary['GSF_SeminarDetails']['fields']['sales_ratio'] = array(
    'name' => 'sales_ratio',
    'vname' => 'LBL_SALES_RATIO',
    'source' => 'non-db',
    'type' => 'kreporter',
    'eval' => "ROUND(($query_clients_open) / ($query_appointment_held), 2)",
);
/** END Custom fields used for KINAMU Reports **/
?>