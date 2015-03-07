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




require_once('include/Dashlets/DashletGeneric.php');


class MyTasksDashlet extends DashletGeneric { 
    function MyTasksDashlet($id, $def = null) {
        global $current_user, $app_strings;
		require('modules/Tasks/Dashlets/MyTasksDashlet/MyTasksDashlet.data.php');
		
        parent::DashletGeneric($id, $def);
        
        if(empty($def['title'])) $this->title = translate('LBL_LIST_MY_TASKS', 'Tasks');

        $this->searchFields = $dashletData['MyTasksDashlet']['searchFields'];
        $this->columns = $dashletData['MyTasksDashlet']['columns'];
        
               
        $this->seedBean = new Task();        
        
        # 8.2
        # @KMJ
        # Exception List/Task List
        $this->title = translate('LBL_LIST_TASKLIST', 'Tasks');
        $this->isConfigurable = true;
//        $this->hasScript = true;
        $this->myItemsOnly = 0;
        $this->showMyItemsOnly = 0;    
        $this->displayTpl = "custom/modules/".$this->seedBean->module_dir."/DashletGenericDisplay.tpl";
    }
    
    function process() {
        global $current_language, $app_list_strings, $image_path, $current_user;        
        $mod_strings = return_module_language($current_language, 'Tasks');

        $date_now = date('Y-m-d');
        $lvsParams = array(
                            'custom_select' => "
                                
                            ",
                            'custom_from' => "
                                left join cms_sites on cms_sites.id = tasks.parent_id AND tasks.parent_type = 'CMS_Sites'
                            ",
                            'custom_where' => " ",
                            'distinct' => true
                     );
        
        parent::process($lvsParams);
     }
    
    function buildWhere() {
         $filters = parent::buildWhere();

         foreach($filters as $k => $f){
             $pos = strpos($f, 'tasks.division_id');
             if($pos!==false){
                $d_id = explode('tasks.division_id',$f);
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
        
        $division_names = '';
        $where = '';
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
            $division_names = array();
            $result = $db->query($SQL, true);
            while ($row = $db->fetchByAssoc($result)) {
                array_push($division_names, $row['name']);
            }
            $division_names = implode(', ', $division_names);            
        }
        $this->lvs->ss->assign('DIVISION_SELECTED',$division_names);
        return parent::display();
     }
}

?>
