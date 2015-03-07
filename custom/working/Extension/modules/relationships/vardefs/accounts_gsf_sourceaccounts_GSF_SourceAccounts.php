<?php
// created: 2011-03-19 16:15:48
$dictionary["GSF_SourceAccounts"]["fields"]["accounts_gsf_sourceaccounts"] = array (
  'name' => 'accounts_gsf_sourceaccounts',
  'type' => 'link',
  'relationship' => 'accounts_gsf_sourceaccounts',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_SOURCEACCOUNTS_FROM_ACCOUNTS_TITLE',
);
$dictionary["GSF_SourceAccounts"]["fields"]["accounts_gsf_sourceaccounts_name"] = array (
  'name' => 'accounts_gsf_sourceaccounts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_SOURCEACCOUNTS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_g2316ccounts_ida',
  'link' => 'accounts_gsf_sourceaccounts',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["GSF_SourceAccounts"]["fields"]["accounts_g2316ccounts_ida"] = array (
  'name' => 'accounts_g2316ccounts_ida',
  'type' => 'link',
  'relationship' => 'accounts_gsf_sourceaccounts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_SOURCEACCOUNTS_FROM_GSF_SOURCEACCOUNTS_TITLE',
);
