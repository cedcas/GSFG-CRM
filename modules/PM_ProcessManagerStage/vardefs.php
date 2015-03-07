<?php
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004 - 2007 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/
$dictionary['PM_ProcessManagerStage'] = array(
	'table'=>'pm_processmanagerstage',
	'audited'=>true,
	'fields'=>array (
  'stage_order' => 
  array (
    'required' => false,
    'name' => 'stage_order',
    'vname' => 'LBL_STAGE_ORDER',
    'type' => 'int',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => '11',
    'default' => 0,
  ),
  'start_delay_minutes' => 
  array (
    'required' => false,
    'name' => 'start_delay_minutes',
    'vname' => 'LBL_START_DELAY_MINUTES',
    'type' => 'enum',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => 100,
    'options' => 'start_delay_minutes',
    'studio' => 'visible',
  ),
  'start_delay_hours' => 
  array (
    'required' => false,
    'name' => 'start_delay_hours',
    'vname' => 'LBL_START_DELAY_HOURS',
    'type' => 'enum',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => 100,
    'options' => 'start_delay_hours',
    'studio' => 'visible',
  ),
  'start_delay_days' => 
  array (
    'required' => false,
    'name' => 'start_delay_days',
    'vname' => 'LBL_START_DELAY_DAYS',
    'type' => 'enum',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => 100,
    'options' => 'start_delay_days',
    'studio' => 'visible',
  ),
  'start_delay_months' => 
  array (
    'required' => false,
    'name' => 'start_delay_months',
    'vname' => 'LBL_START_DELAY_MONTHS',
    'type' => 'enum',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => 100,
    'options' => 'start_delay_months',
    'studio' => 'visible',
  ),
  'start_delay_years' => 
  array (
    'required' => false,
    'name' => 'start_delay_years',
    'vname' => 'LBL_START_DELAY_YEARS',
    'type' => 'enum',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => 100,
    'options' => 'start_delay_years',
    'studio' => 'visible',
  ),
  'process_id' => 
  array (
    'required' => false,
    'name' => 'process_id',
    'vname' => 'LBL_PROCESS_ID',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => '36',
    'default' => 'null',
  ),
  
 //PATCH 05-04-2010 - Support for Teams in Pro and Enterprise
   'team_id' => 
  array (
    'required' => false,
    'name' => 'team_id',
    'vname' => 'LBL_TEAM_ID',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => '36',
    'default' => 'null',
  ), 
    'team_set_id' => 
  array (
    'required' => false,
    'name' => 'team_set_id',
    'vname' => 'LBL_TEAM_SET_ID',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => '36',
    'default' => 'null',
  ), 
  
  //END PATCH 05-04-2010   
),
	'relationships'=>array (
),
	'optimistic_lock'=>true,
);
require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('PM_ProcessManagerStage','PM_ProcessManagerStage', array('basic','team_security','assignable'));