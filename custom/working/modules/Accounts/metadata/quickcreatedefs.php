<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
/*********************************************************************************

 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 

$viewdefs ['Accounts'] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          'SAVE',
          'CANCEL',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        array (
          'label' => '10',
          'field' => '30',
        ),
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        array (
          'file' => 'modules/Accounts/Account.js',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_account_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
          1 => 
          array (
            'name' => 'accounts_total_premium_c',
            'label' => 'LBL_ACCOUNTS_TOTAL_PREMIUM',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'accounts_company_product_c',
            'studio' => 'visible',
            'label' => 'LBL_ACCOUNTS_COMPANY_PRODUCT',
          ),
          1 => 
          array (
            'name' => 'accounts_tax_status_c',
            'studio' => 'visible',
            'label' => 'LBL_ACCOUNTS_TAX_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'accounts_account_number_c',
            'label' => 'LBL_ACCOUNTS_ACCOUNT_NUMBER',
          ),
          1 => 
          array (
            'name' => 'accounts_repeat_client_c',
            'label' => 'LBL_ACCOUNTS_REPEAT_CLIENT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'accounts_anniversary_date_c',
            'label' => 'LBL_ACCOUNTS_ANNIVERSARY_DATE',
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'lead_name',
            'label' => 'LBL_LEAD_NAME',
          ),
          1 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => '',
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'accounts_application_mailed__c',
            'label' => 'LBL_ACCOUNTS_APPLICATION_MAILED_',
          ),
          1 => 
          array (
            'name' => 'accounts_tracking_number_c',
            'label' => 'LBL_ACCOUNTS_TRACKING_NUMBER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'accounts_application_receive_c',
            'label' => 'LBL_ACCOUNTS_APPLICATION_RECEIVE',
          ),
          1 => 
          array (
            'name' => 'accounts_projected_amount_c',
            'label' => 'LBL_ACCOUNTS_PROJECTED_AMOUNT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'accounts_other_description_c',
            'studio' => 'visible',
            'label' => 'LBL_ACCOUNTS_OTHER_DESCRIPTION',
          ),
          1 => '',
        ),
      ),
    ),
  ),
); 
 
?>