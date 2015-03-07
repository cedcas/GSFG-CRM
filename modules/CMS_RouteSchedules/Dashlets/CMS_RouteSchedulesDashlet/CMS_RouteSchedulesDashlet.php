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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/CMS_RouteSchedules/CMS_RouteSchedules.php');

class CMS_RouteSchedulesDashlet extends DashletGeneric {
    
    function CMS_RouteSchedulesDashlet($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/CMS_RouteSchedules/metadata/dashletviewdefs.php');

        parent::DashletGeneric($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'CMS_RouteSchedules');

        $this->searchFields = $dashletData['CMS_RouteSchedulesDashlet']['searchFields'];
        $this->columns = $dashletData['CMS_RouteSchedulesDashlet']['columns'];
        $this->seedBean = new CMS_RouteSchedules();
        
        # 8.3 - Routes Scheduled Today
        # @KMJ
        # load default settings (spec)
        $this->isConfigurable = true;
//        $this->hasScript = true;
        $this->myItemsOnly = 0;
        $this->showMyItemsOnly = 0;
//        $this->displayRows = 10;
        $this->displayTpl = "custom/modules/".$this->seedBean->module_dir."/DashletGenericDisplay.tpl";
        
    }
    
    # 8.3 - Routes Scheduled Today
    # @KMJ - override function process for sql query
    function process() {
        global $current_language, $app_list_strings, $image_path, $current_user;        
        $mod_strings = return_module_language($current_language, 'CMS_RouteSchedules');
       
/*
 
Thats the % of SCANNED BINS divided by SCHEDULED BINS
SCANNED BINS - cms_collections
SCHEDULED BINS - cms_routeschedulesites
 
They are tied by ROUTE SCHEDULE ID
 
 */        
     


        $date_now = date('Y-m-d');
        $lvsParams = array(
                            'custom_select' => "
				, CONCAT(COALESCE(rp.name,''), ' | ', COALESCE(cms_routeschedules.name,'')) AS route,
				CONCAT(COALESCE(u.first_name,''), ' ', COALESCE(u.last_name,'')) AS driver_c,
				COALESCE(SUM(c.distribution_actual_usable),0) AS volume,
				CONCAT(
                    FORMAT(
                        COALESCE(
                           (SELECT COUNT(DISTINCT(cc.cms_bins_id))
                            FROM cms_collections cc
                            WHERE cc.deleted = 0
                            AND (cc.cms_bins_id <> '' OR cc.cms_bins_id IS NOT NULL)
                            AND cc.cms_routeschedules_id = cms_routeschedules.id)
                            /
                           (SELECT COALESCE(SUM(s.site_number_of_bins),0)
                            FROM cms_routeschedulesites rsc
                            LEFT JOIN cms_sites s
                            ON rsc.cms_sites_id = s.id AND s.deleted = 0
                            WHERE rsc.deleted = 0
                            AND rsc.cms_routeschedules_id = cms_routeschedules.id)
                            * 100
                            ,0
                        )
                        ,0
					),
                    '%'
                ) AS percent_c ",
                            
                            'custom_from' => '
					cms_routeschedules
				INNER JOIN
					cms_routeplans rp
					ON cms_routeschedules.cms_routeplans_id = rp.id
					AND rp.deleted = 0
				LEFT JOIN
					users u
					ON cms_routeschedules.driver_id = u.id
				LEFT JOIN
					cms_collections c
					ON cms_routeschedules.id = c.cms_routeschedules_id
                            ',
                            'custom_where' => " AND DATE(cms_routeschedules.route_schedule_date) = DATE(NOW()) GROUP BY cms_routeschedules.id ",
                            'distinct' => true
                     );
        
       parent::process($lvsParams);
     }
     
     function buildWhere() {
         $filters = parent::buildWhere();

         foreach($filters as $k => $f){
             $pos = strpos($f, 'cms_routeschedules.division_id');
             if($pos!==false){
                $d_id = explode('cms_routeschedules.division_id',$f);
                $filters[$k] = '
                    cms_sites.id IN (
                      SELECT record_id FROM securitygroups_records WHERE module="CMS_Sites"
                      AND securitygroup_id '.$d_id[1].'
                    )
                ';
             }
         }
         return $filters;
     }
     
     function display() {
        
        global $db, $current_user;

        $where = '';
        $division_names = array();
        
        if(!empty($this->filters['division_id'][0])){
            $d_id = array();
            foreach($this->filters['division_id'] as $k => $division_id) {
                array_push($d_id, $division_id);
            }
            $where = implode("','",$d_id);
            $where = " AND s.id IN ('".$where."') ";
            
            if (is_admin($current_user)) {
                $SQL = "SELECT s.id, s.name FROM securitygroups s WHERE deleted=0 $where ORDER BY name ASC;";
            } else {
                $SQL = "SELECT s.id, s.name
                    FROM securitygroups_users su
                    LEFT JOIN securitygroups s
                    ON su.securitygroup_id = s.id
                    WHERE su.deleted=0 AND s.deleted=0 AND su.user_id='".$current_user->id."' $where
                    ORDER BY s.name ASC;";
            }
            $result = $db->query($SQL, true);
            
            while ($row = $db->fetchByAssoc($result)) {
                array_push($division_names, $row['name']);
            }
            $division_names = implode(', ', $division_names);
        }
        $this->lvs->ss->assign('DIVISION_SELECTED',$division_names);
        return parent::display();
     }

//     function displayScript() {
//         echo '
//             <script>
//                setInterval(\'SUGAR.mySugar.retrieveDashlet("'.$this->id.'");\', 5000);
//            </script>
//             ';
//     }

}