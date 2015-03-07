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

    $manifest = array (
         'acceptable_sugar_versions' => 
          array (
            
          ),
          'acceptable_sugar_flavors' =>
          array(
            'CE', 'PRO','ENT'
          ),
          'readme'=>'',
          'key'=>'GSF',
          'author' => 'NetCoresolutions.com',
          'description' => '3/2/11 @ 10:22 AM - Troubleshooting the issue of overwriting the LEADS&#039; editviewdefs.php everytime GSF deploys through Module Builder.',
          'icon' => '',
          'is_uninstallable' => true,
          'name' => 'GSF',
          'published_date' => '2011-03-04 18:38:22',
          'type' => 'module',
          'version' => '1299263902',
          'remove_tables' => 'prompt',
          );
$installdefs = array (
  'id' => 'GSF',
  'beans' => 
  array (
    0 => 
    array (
      'module' => 'GSF_Withdrawals',
      'class' => 'GSF_Withdrawals',
      'path' => 'modules/GSF_Withdrawals/GSF_Withdrawals.php',
      'tab' => true,
    ),
    1 => 
    array (
      'module' => 'GSF_Seminars',
      'class' => 'GSF_Seminars',
      'path' => 'modules/GSF_Seminars/GSF_Seminars.php',
      'tab' => true,
    ),
    2 => 
    array (
      'module' => 'GSF_SourceAccounts',
      'class' => 'GSF_SourceAccounts',
      'path' => 'modules/GSF_SourceAccounts/GSF_SourceAccounts.php',
      'tab' => true,
    ),
    3 => 
    array (
      'module' => 'GSF_Contributions',
      'class' => 'GSF_Contributions',
      'path' => 'modules/GSF_Contributions/GSF_Contributions.php',
      'tab' => true,
    ),
    4 => 
    array (
      'module' => 'GSF_SeminarDetails',
      'class' => 'GSF_SeminarDetails',
      'path' => 'modules/GSF_SeminarDetails/GSF_SeminarDetails.php',
      'tab' => true,
    ),
    5 => 
    array (
      'module' => 'GSF_Venues',
      'class' => 'GSF_Venues',
      'path' => 'modules/GSF_Venues/GSF_Venues.php',
      'tab' => true,
    ),
  ),
  'layoutdefs' => 
  array (
  ),
  'relationships' => 
  array (
  ),
  'image_dir' => '<basepath>/icons',
  'copy' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/modules/GSF_Withdrawals',
      'to' => 'modules/GSF_Withdrawals',
    ),
    1 => 
    array (
      'from' => '<basepath>/SugarModules/modules/GSF_Seminars',
      'to' => 'modules/GSF_Seminars',
    ),
    2 => 
    array (
      'from' => '<basepath>/SugarModules/modules/GSF_SourceAccounts',
      'to' => 'modules/GSF_SourceAccounts',
    ),
    3 => 
    array (
      'from' => '<basepath>/SugarModules/modules/GSF_Contributions',
      'to' => 'modules/GSF_Contributions',
    ),
    4 => 
    array (
      'from' => '<basepath>/SugarModules/modules/GSF_SeminarDetails',
      'to' => 'modules/GSF_SeminarDetails',
    ),
    5 => 
    array (
      'from' => '<basepath>/SugarModules/modules/GSF_Venues',
      'to' => 'modules/GSF_Venues',
    ),
  ),
  'language' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/language/application/en_us.lang.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ),
  ),
);