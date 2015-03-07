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
$dictionary['GSF_Contributions'] = array(
	'table'=>'gsf_contributions',
	'audited'=>true,
	'fields'=>array (
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Description of the sale',
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
  'sales_stage' => 
  array (
    'name' => 'sales_stage',
    'vname' => 'LBL_SALES_STAGE',
    'type' => 'enum',
    'options' => 'sales_stage_dom',
    'len' => 100,
    'audited' => true,
    'comment' => 'Indication of progression towards closure',
    'required' => false,
    'importable' => 'required',
    'massupdate' => 0,
    'default' => 'Prospecting',
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'reportable' => true,
    'size' => '20',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'amount_usdollar' => 
  array (
    'name' => 'amount_usdollar',
    'vname' => 'LBL_AMOUNT_USDOLLAR',
    'type' => 'currency',
    'group' => 'amount',
    'dbType' => 'double',
    'disable_num_format' => true,
    'audited' => false,
    'comment' => 'Formatted amount of the sale',
    'required' => false,
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'reportable' => true,
    'len' => 26,
    'size' => '20',
  ),
  'currency_id' => 
  array (
    'name' => 'currency_id',
    'type' => 'id',
    'group' => 'currency_id',
    'vname' => 'LBL_CURRENCY',
    'function' => 
    array (
      'name' => 'getCurrencyDropDown',
      'returns' => 'html',
    ),
    'reportable' => true,
    'comment' => 'Currency used for display purposes',
    'required' => false,
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'len' => 36,
    'size' => '20',
    'studio' => 'visible',
  ),
  'date_closed' => 
  array (
    'name' => 'date_closed',
    'vname' => 'LBL_DATE_CLOSED',
    'type' => 'date',
    'audited' => true,
    'required' => false,
    'comment' => 'Expected or actual date the sale will close',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'reportable' => true,
    'size' => '20',
  ),
  'gsf_contribution_type' => 
  array (
    'name' => 'gsf_contribution_type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'options' => 'gsf_contribution_type_list',
    'len' => 100,
    'comment' => 'The Sale is of this type',
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
    'dependency' => false,
  ),
  'gsf_contribution_amount' => 
  array (
    'required' => true,
    'name' => 'gsf_contribution_amount',
    'vname' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 26,
    'size' => '20',
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'name',
    'dbType' => 'varchar',
    'vname' => 'LBL_NAME',
    'comment' => 'Name of the Sale',
    'unified_search' => true,
    'audited' => true,
    'merge_filter' => 'selected',
    'required' => false,
    'importable' => 'required',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'reportable' => true,
    'len' => '255',
    'size' => '20',
	'link' => true,
  ),
  'amount' => 
  array (
    'name' => 'amount',
    'vname' => 'LBL_AMOUNT',
    'type' => 'currency',
    'dbType' => 'double',
    'comment' => 'Unconverted amount of the sale',
    'duplicate_merge' => 'disabled',
    'required' => false,
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 26,
    'size' => '20',
  ),
  'gsf_contribution_date' => 
  array (
    'required' => false,
    'name' => 'gsf_contribution_date',
    'vname' => 'LBL_GSF_CONTRIBUTION_DATE',
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
    'display_default' => 'now',
  ),
  'gsf_contribution_repeat_client' => 
  array (
    'required' => false,
    'name' => 'gsf_contribution_repeat_client',
    'vname' => 'LBL_GSF_CONTRIBUTION_REPEAT_CLIENT',
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
),
	'relationships'=>array (
),
	'optimistic_locking'=>true,
);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('GSF_Contributions','GSF_Contributions', array('basic','assignable','sale'));