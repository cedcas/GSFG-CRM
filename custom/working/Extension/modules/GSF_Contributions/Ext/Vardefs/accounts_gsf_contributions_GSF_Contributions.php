<?php
// created: 2011-03-19 16:19:06
$dictionary["GSF_Contributions"]["fields"]["accounts_gsf_contributions"] = array (
  'name' => 'accounts_gsf_contributions',
  'type' => 'link',
  'relationship' => 'accounts_gsf_contributions',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_ACCOUNTS_TITLE',
);
$dictionary["GSF_Contributions"]["fields"]["accounts_gsf_contributions_name"] = array (
  'name' => 'accounts_gsf_contributions_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_g813cccounts_ida',
  'link' => 'accounts_gsf_contributions',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["GSF_Contributions"]["fields"]["accounts_g813cccounts_ida"] = array (
  'name' => 'accounts_g813cccounts_ida',
  'type' => 'link',
  'relationship' => 'accounts_gsf_contributions',
  'source' => 'non-db',
  'reportable' => true,
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_GSF_CONTRIBUTIONS_TITLE',
);
