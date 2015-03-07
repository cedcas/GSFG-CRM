<?php
/**
* Common hook class used in the CMS_Bins module.
* @author Joed@ASI 20110418
*/

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class CMS_Bins_Hook
{
	
    public function bin_save(&$bean, $event, $arguments) {
    	
    	$db = DBManagerFactory::getInstance();
        
        /*
         * CMS requirement for importing (20110622 by Joed)
         * added if condition to allow import of BINS with the same "id"
         * refer also to custom/modules/Import/views/view.step4.php
         */
        if ($_REQUEST["module"] == "Import") {
            
/*
 12-11 - code update - make sure there is no existing duplicate in cms_bine table for the barcode (cms_bins.name) - this field has now a unique index in vardefs. With this index repair is impossible if there are existing duplicate records (cms_bins.name) in the cms_bins table.
 */            
            
//            $bean->id = $bean->name;
//            $query = "
//                SELECT id FROM cms_bins WHERE name = '".$bean->name."';
//            ";
//            
//            $result = $db->query($query, true);
//            $lastcount = 1;
//            $duplicate = false;
//            
//            while($row = $db->fetchByAssoc($result)) {
//                $duplicate = true;
//                $id_count = explode("-", $row['id']);
//                $dbcount = $id_count[1];
//                
//                if ($dbcount != null) {
//                    $lastcount = ($dbcount > $lastcount) ? $dbcount : $lastcount;
//                }
//            }
//            
//            if ($duplicate) {
//                $bean->id .= "-" . ($lastcount + 1);
//            } else {
//                $bean->id = $bean->name;
//            }

        /* START 12/19/12 - CPC
         * BT# 413 Insert the division that came with the bins import (if present)
         * into securitygroups_records table
         */
        
			if (!empty($bean->securitygroup_id_c)) {
				$division_id = $bean->securitygroup_id_c;
				$bin_id = $bean->id;
				$date_modified = $bean->date_modified;
				
			// delete the inherited division(s) from the user first
			$SQL1 = "DELETE FROM securitygroups_records
				WHERE `record_id`='{$bin_id}' 
					AND `module`='CMS_Bins'";
					
				$db->query($SQL1, true);
				
			$SQL = "
				INSERT INTO securitygroups_records(
				`id`,`securitygroup_id`, `record_id`, `module`, `date_modified`, `deleted`)
				VALUES (
					UUID(),
					'{$division_id}',
					'{$bin_id}',
					'CMS_Bins',
					'{$date_modified}',
					'0'
					)";
		
				$db->query($SQL, true);
				}
        /* END 12/19/12 - CPC */
		
        }
        
        
        /* set old barcode and number for tracking
         * used in Task # 38 (/modules/PM_ProcessManager/customScripts/task_038.php)
         */
        if ($bean->fetched_row['name'] != $bean->name) {
            $bean->bin_old_barcode = $bean->fetched_row['name'];
            
            if ($bean->fetched_row['bin_number'] == $bean->bin_number) {
                $bean->bin_old_number = $bean->fetched_row['bin_number'];
            }
        }
        if ($bean->fetched_row['bin_number'] != $bean->bin_number) {
            $bean->bin_old_number = $bean->fetched_row['bin_number'];
            
            if ($bean->fetched_row['name'] == $bean->name) {
                $bean->bin_old_barcode = $bean->fetched_row['name'];
            }
        }
        
    }
    
    
    /*
     * Allocate the proper TERRITORY base on the Site's TERRITORY
     */
    public function update_territory(&$bean, $event, $arguments) {
        require_once('modules/CMS_Sites/CMS_Sites.php');

        if (!empty($bean->cms_sites_id)) {
            
            $parent_site = new CMS_Sites();
            $parent_site->retrieve($bean->cms_sites_id);
            
            if ($parent_site->cms_territories_id != $bean->cms_territories_id) {
                $bean->cms_territories_id = $parent_site->cms_territories_id;
            }
            
            unset($parent_site);
        }
        //Set TERRITORY to NULL if there is no related Site
        else {
            $bean->cms_territories_id = "";
        }
    }
    
    
    
    /*
     * update the "Number of Bins" field of the related Site record
     */
    public function update_parent_sites_bin_count(&$bean, $event, $arguments) {
        require_once('modules/CMS_Sites/CMS_Sites.php');
        require_once('custom/modules/CMS_Sites/CMS_Sites_Hook.php');
        
        $parent_site = new CMS_Sites();
        $parent_site->retrieve($bean->cms_sites_id);
        $parent_site_hook = new CMS_Sites_Hook();
        $parent_site_hook->update_bin_count($parent_site, null, null);
        
        unset($parent_site);
        unset($parent_site_hook);
    }
    
    
    
    /*
     * check if there are changes in the Bin record and create a BinStatus record
     * as a history record
     */
    function create_binstatus(&$bean, $event, $arguments) {
        $db = DBManagerFactory::getInstance();
        
        $modified = false;
        $auditfields = array(
            "name",
            "bin_serial_number",
            "bin_number",
            "bin_status",
            "bin_status_reason",
            "bin_vendor",
            "bin_purchased_from",
            "bin_sold_to",
            "bin_inventory_date",
            "bin_lock_brand",
            "bin_lock_key_set_code",
            "bin_comment",
            "cms_bintypes_id",
            "bin_type_life_expectancy",
            "bin_type_height",
            "bin_type_normal_capacity_filled",
            "bin_type_width",
            "bin_type_capacity",
            "bin_type_depth",
            "cms_sites_id",
            "cms_territories_id",
            "bin_date_placed",
            "bin_date_removed",
            "bins_activity",
            "refurbish_reasons",
            "start_activity_date",
            "end_activity_date",
            "stats_lbs_per_day",
            "stats_current_contents",
            "assigned_user_id",
	    "4g",
	    "end_inventory_date"
        );
        
        $query = "
            SELECT ".implode(',', $auditfields)."
            FROM cms_bins
            WHERE id = '".$bean->id."'
            LIMIT 1
        ";
        $result = $db->query($query, true);
        $row = $db->fetchByAssoc($result);
        
        if (!empty($row)) {
            foreach ($auditfields as $field) {
                
                if ($row[$field] != $bean->{$field}) {
                    $modified = true;
                }
            }
        } else {
            //new Bin record, so create Bin Status
            $modified = true;
        }
        
        
        if ($modified) {
            require_once('modules/CMS_BinStatus/CMS_BinStatus.php');
            $binstatus = new CMS_BinStatus();
            
            foreach ($auditfields as $field) {
                $binstatus->{$field} = $bean->{$field};
            }
            
            $bean->plus_id = create_guid();
            $bean->minus_id = create_guid();
            $bean->zero_id = create_guid();
            
            $binstatus->cms_bins_id = $bean->id;
            $binstatus->plus_id = $bean->plus_id;
            $binstatus->minus_id = $bean->minus_id;
            $binstatus->zero_id = $bean->zero_id;
            $binstatus->save();
	    
	    
	    # @km: 9.1
	    // create cms_binscount from create cms_binstatus
	    global $current_user;
	    if($_REQUEST['module']=='Import' && $_REQUEST['import_module']=='CMS_Bins'){
		
		# assuming user has a sigle division
		# limit 1 for multiple division user
//		$SQL = " SELECT s.id, s.name
//			FROM securitygroups_users su
//			LEFT JOIN securitygroups s
//			ON su.securitygroup_id = s.id
//			WHERE su.deleted=0 AND s.deleted=0 AND su.user_id = '{$current_user->id}'
//			ORDER BY s.name ASC LIMIT 1
//		";
                        
                # use uploaded securitygroup_id as division_id
                $SQL = "
                    SELECT securitygroup_id FROM securitygroups_records
                    WHERE module = 'CMS_Bins' AND record_id = '{$binstatus->cms_bins_id}'
                ";
		
		$result = $db->query($SQL, true);
		$division_id = $db->fetchByAssoc($result);
//		$division_id = $division_id['id'];
                $division_id = $division_id['securitygroup_id'];
		
		$binscount_id = $bean->plus_id;
		$SQL = "
		    INSERT INTO cms_binscount(
			`bin_activity_name`, `bin_status`, `division_id`, `number_of_bins`, `employee_id`,
			`date_of_activity`, `date_created`, `id`, `bin_id`, `bin_barcode`, `site_id`, `siteid`)
		    VALUES (
			'',
			'New',
			'{$division_id}',
			'+1',
			'{$current_user->id}',
			
			'{$binstatus->bin_inventory_date}',
			'".gmdate($GLOBALS['timedate']->get_db_date_time_format())."',
			'".$binscount_id."',
			'{$binstatus->cms_bins_id}',
			'{$binstatus->name}',
			
			'',
			''
		    )
		";
		$binstatus->binscount_id = $binscount_id;
		$binstatus->save();
		
		$db->query($SQL, true);
	    }else{
		# this should function on CMS_Bins (create/edit)
		# no other functions should pass this point
		if($_REQUEST['module']=='CMS_Bins' && $_REQUEST['action']=='Save' && $_REQUEST['return_module']=='CMS_Bins' && $_REQUEST['return_action']=='DetailView'){
		
		    # duplicate process from IMPORT
		    # assuming user has a sigle division
		    # limit 1 for multiple division user
//		    $SQL = " SELECT s.id, s.name
//		    	FROM securitygroups_users su
//		    	LEFT JOIN securitygroups s
//		    	ON su.securitygroup_id = s.id
//		    	WHERE su.deleted=0 AND s.deleted=0 AND su.user_id = '{$current_user->id}'
//		    	ORDER BY s.name ASC LIMIT 1
//		    ";
                    
                    $SQL = "
                        SELECT securitygroup_id FROM securitygroups_records
                        WHERE module = 'CMS_Bins' AND record_id = '{$binstatus->cms_bins_id}'
                    ";
		    
		    $result = $db->query($SQL, true);
		    $division_id = $db->fetchByAssoc($result);
//		    $division_id = $division_id['id'];
                    $division_id = $division_id['securitygroup_id'];
                    
		    
		    $binscount_id = $bean->plus_id;
		    $binstatus->bin_inventory_date = empty($binstatus->bin_inventory_date)?'000-00-00':$binstatus->bin_inventory_date;
		    $SQL = "
		        INSERT INTO cms_binscount(
		    	`bin_activity_name`, `bin_status`, `division_id`, `number_of_bins`, `employee_id`,
		    	`date_of_activity`, `date_created`, `id`, `bin_id`, `bin_barcode`, `site_id`, `siteid`)
		        VALUES (
		    	'',
		    	'New',
		    	'{$division_id}',
		    	'+1',
		    	'{$current_user->id}',
		    	
		    	'{$binstatus->bin_inventory_date}',
		    	'".gmdate($GLOBALS['timedate']->get_db_date_time_format())."',
		    	'".$binscount_id."',
		    	'{$binstatus->cms_bins_id}',
		    	'{$binstatus->name}',
		    	
		    	'',
		    	''
		        )
		    ";
		    $binstatus->binscount_id = $binscount_id;
		    $binstatus->save();
		    
		    
		    if(empty($_REQUEST['record'])){
			$db->query($SQL, true);
		    }
		}
	    }
        }
    
    }
    
    
    
    /*
     * update Bin's calculated fields
     */
    function update_bin_stats(&$bean, $event, $arguments) {
        require_once('custom/modules/CMS_Bins/bin_utils.php');
        update_bin_statistics($bean->id);
    }
    
    
}
?>