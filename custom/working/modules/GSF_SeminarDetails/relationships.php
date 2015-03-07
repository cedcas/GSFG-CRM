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
$relationships = array (
  'gsf_seminardetails_gsf_contributions' => 
  array (
    'id' => 'd2cea25e-f897-e5ed-0be4-4ebb634ef534',
    'relationship_name' => 'gsf_seminardetails_gsf_contributions',
    'lhs_module' => 'GSF_SeminarDetails',
    'lhs_table' => 'gsf_seminardetails',
    'lhs_key' => 'id',
    'rhs_module' => 'GSF_Contributions',
    'rhs_table' => 'gsf_contributions',
    'rhs_key' => 'gsf_seminardetails_id',
    'join_table' => NULL,
    'join_key_lhs' => NULL,
    'join_key_rhs' => NULL,
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => 'Accountdefault',
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'gsf_seminardetails_leads' => 
  array (
    'id' => 'ee3d2684-7f44-ebe5-38d2-4ebb63c6b32b',
    'relationship_name' => 'gsf_seminardetails_leads',
    'lhs_module' => 'GSF_SeminarDetails',
    'lhs_table' => 'gsf_seminardetails',
    'lhs_key' => 'id',
    'rhs_module' => 'Leads',
    'rhs_table' => 'leads',
    'rhs_key' => 'id',
    'join_table' => 'gsf_seminaretails_leads_c',
    'join_key_lhs' => 'gsf_semina6647details_ida',
    'join_key_rhs' => 'gsf_semina5325dsleads_idb',
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => 'default',
    'lhs_subpanel' => NULL,
    'from_studio' => true,
    'is_custom' => true,
    'relationship_only' => false,
    'for_activities' => false,
  ),
  'gsf_seminars_gsf_seminardetails' => 
  array (
    'id' => 'ee5cf62e-e7e2-1943-3070-4ebb639109c0',
    'relationship_name' => 'gsf_seminars_gsf_seminardetails',
    'lhs_module' => 'GSF_Seminars',
    'lhs_table' => 'gsf_seminars',
    'lhs_key' => 'id',
    'rhs_module' => 'GSF_SeminarDetails',
    'rhs_table' => 'gsf_seminardetails',
    'rhs_key' => 'id',
    'join_table' => 'gsf_seminarminardetails_c',
    'join_key_lhs' => 'gsf_seminac629eminars_ida',
    'join_key_rhs' => 'gsf_semina6236details_idb',
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => 'GSF_Seminarsdefault',
    'lhs_subpanel' => NULL,
    'from_studio' => true,
    'is_custom' => true,
    'relationship_only' => false,
    'for_activities' => false,
  ),
  'gsf_venues_gsf_seminardetails' => 
  array (
    'id' => 'ef176da1-0f04-09b8-b61a-4ebb63385ef7',
    'relationship_name' => 'gsf_venues_gsf_seminardetails',
    'lhs_module' => 'GSF_Venues',
    'lhs_table' => 'gsf_venues',
    'lhs_key' => 'id',
    'rhs_module' => 'GSF_SeminarDetails',
    'rhs_table' => 'gsf_seminardetails',
    'rhs_key' => 'id',
    'join_table' => 'gsf_venues_minardetails_c',
    'join_key_lhs' => 'gsf_venues56d9_venues_ida',
    'join_key_rhs' => 'gsf_venuesc61bdetails_idb',
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => 'GSF_Seminarsdefault',
    'lhs_subpanel' => NULL,
    'from_studio' => true,
    'is_custom' => true,
    'relationship_only' => false,
    'for_activities' => false,
  ),
  'gsf_seminardetails_users' => 
  array (
    'rhs_label' => 'Users',
    'lhs_label' => 'Seminar Details',
    'lhs_subpanel' => 'default',
    'rhs_subpanel' => 'default',
    'lhs_module' => 'GSF_SeminarDetails',
    'rhs_module' => 'Users',
    'relationship_type' => 'many-to-many',
    'readonly' => true,
    'deleted' => false,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
    'relationship_name' => 'gsf_seminardetails_users',
  ),
);
?>
