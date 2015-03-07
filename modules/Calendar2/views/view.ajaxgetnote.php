<?php
/******************************************************************************
OpensourceCRM End User License Agreement

INSTALLING OR USING THE OpensourceCRM's SOFTWARE THAT YOU HAVE SELECTED TO 
PURCHASE IN THE ORDERING PROCESS (THE "SOFTWARE"), YOU ARE AGREEING ON BEHALF OF
THE ENTITY LICENSING THE SOFTWARE ("COMPANY") THAT COMPANY WILL BE BOUND BY AND 
IS BECOMING A PARTY TO THIS END USER LICENSE AGREEMENT ("AGREEMENT") AND THAT 
YOU HAVE THE AUTHORITY TO BIND COMPANY.

IF COMPANY DOES NOT AGREE TO ALL OF THE TERMS OF THIS AGREEMENT, DO NOT SELECT 
THE "ACCEPT" BOX AND DO NOT INSTALL THE SOFTWARE. THE SOFTWARE IS PROTECTED BY 
COPYRIGHT LAWS AND INTERNATIONAL COPYRIGHT TREATIES, AS WELL AS OTHER 
INTELLECTUAL PROPERTY LAWS AND TREATIES. THE SOFTWARE IS LICENSED, NOT SOLD.

    *The COMPANY may not copy, deliver, distribute the SOFTWARE without written
     permit from OpensourceCRM.
    *The COMPANY may not reverse engineer, decompile, or disassemble the 
    SOFTWARE, except and only to the extent that such activity is expressly 
    permitted by applicable law notwithstanding this limitation.
    *The COMPANY may not sell, rent, or lease resell, or otherwise transfer for
     value, the SOFTWARE.
    *Termination. Without prejudice to any other rights, OpensourceCRM may 
    terminate this Agreement if the COMPANY fail to comply with the terms and 
    conditions of this Agreement. In such event, the COMPANY must destroy all 
    copies of the SOFTWARE and all of its component parts.
    *OpensourceCRM will give the COMPANY notice and 30 days to correct above 
    before the contract will be terminated.

The SOFTWARE is protected by copyright and other intellectual property laws and 
treaties. OpensourceCRM owns the title, copyright, and other intellectual 
property rights in the SOFTWARE.
*****************************************************************************/
/**
 *Calendar2ViewAjaxAfterDrop
 * 
 * */
 
require_once('include/MVC/View/SugarView.php');
require_once('modules/Calendar2/Calendar2.php');

class Calendar2ViewAjaxGetNote extends SugarView {
	
 	function Calendar2ViewAjaxGetNote(){
 		parent::SugarView();
 	}
 	
 	function process() {
		$this->display();
 	}

 	function display() {

		require_once('modules/Calendar2/EditViewA.php');

		require_once('modules/cal_Notes/cal_Notes.php');
		
		


		$metadataFile = 'modules/cal_Notes/metadata/editviewdefs.php';
		if(file_exists('custom/'.$metadataFile)) 
			$metadataFile = "custom/".$metadataFile;
	
		


		require_once("modules/Calls/Call.php");
		require_once("modules/Meetings/Meeting.php");

		if($_REQUEST['type'] == 'Calls'){
				$bean = new Call();
				$type = 'call';
		}
		if($_REQUEST['type'] == 'Meetings'){
				$bean = new Meeting();
				$type = 'meeting';
		}
		
		$cn_id = "";
		if(!empty($_REQUEST['record'])) {
				$bean->retrieve($_REQUEST['record']);
				$rel = "cal_notes_".$type."s";
				$bean->load_relationship($rel);
				$re = $bean->$rel->get();
				if(count($re))
					$cn_id = $re[0];
		}
		
		


		$bean = new cal_Notes();

		if(!empty($cn_id))
			$bean->retrieve($cn_id);

		$ev = new EditViewA();

		$ss = new Sugar_Smarty();

		$ev->ss =& $ss;


		$ev->setup("cal_Notes", $bean, $metadataFile, 'include/EditView/EditView.tpl');
		$ev->process();
		echo $ev->display(false);
	}
}
?>
