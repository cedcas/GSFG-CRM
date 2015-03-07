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
$dictionary['GSF_SeminarDetails'] = array(
	'table'=>'gsf_seminardetails',
	'audited'=>true,
	'fields'=>array (
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => '16',
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
  
   'details_capacity' => 
  array (
    'name' => 'details_capacity',
    'vname' => 'LBL_CAPACITY',
    'type' => 'varchar',
    'dbType' => 'varchar',
    'len' => '10',
    'unified_search' => true,
    'required' => false,
    'importable' => 'required',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'size' => '10',
  ),
  
  'details_from_date' => 
  array (
    'required' => false,
    'name' => 'details_from_date',
    'vname' => 'LBL_DETAILS_FROM_DATE',
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
  'details_to_date' => 
  array (
    'required' => false,
    'name' => 'details_to_date',
    'vname' => 'LBL_DETAILS_TO_DATE',
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
  'details_amount_per_person' => 
  array (
    'required' => false,
    'name' => 'details_amount_per_person',
    'vname' => 'LBL_DETAILS_AMOUNT_PER_PERSON',
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
  'currency_id' => 
  array (
    'required' => false,
    'name' => 'currency_id',
    'vname' => 'LBL_CURRENCY',
    'type' => 'id',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'reportable' => true,
    'len' => 36,
    'size' => '20',
    'studio' => 'visible',
    'function' => 
    array (
      'name' => 'getCurrencyDropDown',
      'returns' => 'html',
    ),
  ),
  'details_total_amount' => 
  array (
    'required' => false,
    'name' => 'details_total_amount',
    'vname' => 'LBL_DETAILS_TOTAL_AMOUNT',
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
  'details_registered' => 
  array (
    'required' => false,
    'name' => 'details_registered',
    'vname' => 'LBL_DETAILS_REGISTERED',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '10',
    'size' => '20',
  ),
  'details_buts_in_sits' => 
  array (
    'required' => false,
    'name' => 'details_buts_in_sits',
    'vname' => 'LBL_DETAILS_BUTS_IN_SITS',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '10',
    'size' => '20',
  ),
  'details_buying_units' => 
  array (
    'required' => false,
    'name' => 'details_buying_units',
    'vname' => 'LBL_DETAILS_BUYING_UNITS',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '10',
    'size' => '20',
  ),
  'details_blue_sheets' => 
  array (
    'required' => false,
    'name' => 'details_blue_sheets',
    'vname' => 'LBL_DETAILS_BLUE_SHEETS',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '10',
    'size' => '20',
  ),
  'details_appointment_sets' => 
  array (
    'required' => false,
    'name' => 'details_appointment_sets',
    'vname' => 'LBL_DETAILS_APPOINTMENT_SETS',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '10',
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
  'gsf_venues_id_c' => 
  array (
    'required' => false,
    'name' => 'gsf_venues_id_c',
    'vname' => '',
    'type' => 'id',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'reportable' => true,
    'len' => 36,
    'size' => '20',
  ),
  'details_venue' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'details_venue',
    'vname' => 'LBL_DETAILS_VENUE',
    'type' => 'relate',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '255',
    'size' => '100',
    'id_name' => 'gsf_venues_id_c',
    'ext2' => 'GSF_Venues',
    'module' => 'GSF_Venues',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
  ),
  'details_venue_address1' => 
  array (
    'required' => false,
    'name' => 'details_venue_address1',
    'vname' => 'LBL_DETAILS_VENUE_ADDRESS1',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '50',
    'size' => '20',
  ),
  'details_venue_city' => 
  array (
    'required' => false,
    'name' => 'details_venue_city',
    'vname' => 'LBL_DETAILS_VENUE_CITY',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '50',
    'size' => '20',
  ),
  'details_venue_state' => 
  array (
    'required' => false,
    'name' => 'details_venue_state',
    'vname' => 'LBL_DETAILS_VENUE_STATE',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '20',
    'size' => '20',
  ),
  'details_venue_postalcode' => 
  array (
    'required' => false,
    'name' => 'details_venue_postalcode',
    'vname' => 'LBL_DETAILS_VENUE_POSTALCODE',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '10',
    'size' => '20',
  ),
  'details_from_time' => 
  array (
    'required' => false,
    'name' => 'details_from_time',
    'vname' => 'LBL_DETAILS_FROM_TIME',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '6:00:00 PM',
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'options' => 'gsf_time_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'details_to_time' => 
  array (
    'required' => false,
    'name' => 'details_to_time',
    'vname' => 'LBL_DETAILS_TO_TIME',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '8:00:00 PM',
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'options' => 'gsf_time_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
),
	'relationships'=>array (
),
	'optimistic_locking'=>true,
);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('GSF_SeminarDetails','GSF_SeminarDetails', array('basic','assignable'));