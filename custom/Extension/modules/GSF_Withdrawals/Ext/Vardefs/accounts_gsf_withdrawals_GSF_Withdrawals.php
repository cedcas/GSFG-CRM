<?php
// created: 2011-03-19 16:17:15
$dictionary["GSF_Withdrawals"]["fields"]["accounts_gsf_withdrawals"] = array (
  'name' => 'accounts_gsf_withdrawals',
  'type' => 'link',
  'relationship' => 'accounts_gsf_withdrawals',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_ACCOUNTS_TITLE',
);
$dictionary["GSF_Withdrawals"]["fields"]["accounts_gsf_withdrawals_name"] = array (
  'name' => 'accounts_gsf_withdrawals_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_ge7aaccounts_ida',
  'link' => 'accounts_gsf_withdrawals',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["GSF_Withdrawals"]["fields"]["accounts_ge7aaccounts_ida"] = array (
  'name' => 'accounts_ge7aaccounts_ida',
  'type' => 'id',
  'relationship' => 'accounts_gsf_withdrawals',
  'source' => 'non-db',
  'reportable' => false,
  'vname' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_GSF_WITHDRAWALS_TITLE',
);
