<?php
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2010 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/
$dictionary['GSF_SourceAccounts'] = array(
	'table'=>'gsf_sourceaccounts',
	'audited'=>true,
	'fields'=>array (
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => '50',
    'unified_search' => true,
    'required' => true,
    'importable' => 'required',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'size' => '20',
  ),
  'source_company_product' => 
  array (
    'required' => false,
    'name' => 'source_company_product',
    'vname' => 'LBL_SOURCE_COMPANY_PRODUCT',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'Allianz 1',
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'options' => 'gsf_accounts_products_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'source_account_number' => 
  array (
    'required' => false,
    'name' => 'source_account_number',
    'vname' => 'LBL_SOURCE_ACCOUNT_NUMBER',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '30',
    'size' => '20',
  ),
  'source_anniversary_date' => 
  array (
    'required' => false,
    'name' => 'source_anniversary_date',
    'vname' => 'LBL_SOURCE_ANNIVERSARY_DATE',
    'type' => 'date',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'size' => '20',
  ),
  'source_tax_status' => 
  array (
    'required' => false,
    'name' => 'source_tax_status',
    'vname' => 'LBL_SOURCE_TAX_STATUS',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'IRA',
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'options' => 'gsf_accounts_tax_statuses_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'source_agent_ordered_funds' => 
  array (
    'required' => false,
    'name' => 'source_agent_ordered_funds',
    'vname' => 'LBL_SOURCE_AGENT_ORDERED_FUNDS',
    'type' => 'bool',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '255',
    'size' => '20',
  ),
  'source_transfer' => 
  array (
    'required' => false,
    'name' => 'source_transfer',
    'vname' => 'LBL_SOURCE_TRANSFER',
    'type' => 'bool',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '255',
    'size' => '20',
  ),
  'source_cash_personal_check' => 
  array (
    'required' => false,
    'name' => 'source_cash_personal_check',
    'vname' => 'LBL_SOURCE_CASH_PERSONAL_CHECK',
    'type' => 'bool',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '255',
    'size' => '20',
  ),
  'source_transfer_paperwork_mail' => 
  array (
    'required' => false,
    'name' => 'source_transfer_paperwork_mail',
    'vname' => 'LBL_SOURCE_TRANSFER_PAPERWORK_MAIL',
    'type' => 'date',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'size' => '20',
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Full text of the note',
    'rows' => '4',
    'cols' => '80',
    'required' => false,
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'size' => '20',
    'studio' => 'visible',
  ),
  'projected_amount' =>
  array (
    'required' => false,
    'name' => 'projected_amount',
    'vname' => 'LBL_PROJECTED_AMOUNT',
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
  ),
),
	'relationships'=>array (
),
	'optimistic_locking'=>true,
);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('GSF_SourceAccounts','GSF_SourceAccounts', array('basic','assignable'));