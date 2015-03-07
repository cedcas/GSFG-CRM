<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed 
 * by KINAMU Business Solutions AG. All rights ar (c) 2010 by KINAMU Business
 * Solutions AG.
 *
 * This Version of the KReporter is licensed software and may only be used in 
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of KINAMU Business Solutions AG
 * 
 * You can contact KINAMU Business Solutions AG at Am Concordepark 2/F12
 * A-2320 Schwechat or via email at office@kinamu.com
 * 
 ********************************************************************************/
require_once('modules/KReports/views/view.kreportsdetail.php');
require_once('include/utils.php');
require_once('modules/KReports/utils.php');

class KReportsViewStandardbasic extends KReportsViewDetail {

 	function KReportsViewStandardbasic(){
 		parent::KReportsViewDetail();
 	}
 	
	 function display() {

        global $app_list_stings, $mod_strings, $current_language, $current_user;
			 		
 		// build the Field List for the JSON Reader
 		$arrayList =  $this->json_decode_kinamu( html_entity_decode_utf8($this->bean->listfields));

 		$columnModelArray = '[';
 		foreach($arrayList as $thisList){

 		    	if($columnModelArray != '[') $columnModelArray .= ',';
 				$columnModelArray .= '{header : \'' . trim($thisList['name'], ':') 
 				    . '\', width: ' . ((isset($thisList['width']) && $thisList['width'] != ''  && $thisList['width'] != '0') ? $thisList['width'] : '150') .
 				    ', sortable: false, dataIndex: \'' . trim($thisList['fieldid'], ':') . 
 				    ((isset($thisList['sqlfunction']) && $thisList['sqlfunction'] != '-' ) ? '\', summaryType:\'' . $thisList['sqlfunction'] : '') 
 				    . '\'' . ($thisList['display'] != 'yes' ? ', hidden: true': '')
 				    .  $this->bean->getXtypeRenderer($this->bean->getFieldTypeById($thisList['fieldid']), $thisList['fieldid'])
 				    . '}';

 		}
 		
 		// close the Array
 		$columnModelArray .= "]";

 		$this->ss->assign('columnmodeldarray', $columnModelArray);
 		
 		// process display
		parent::display();
 	}
	
}
?>
