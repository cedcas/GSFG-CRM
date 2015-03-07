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
 *Calendar2ViewAjaxNoteSave
 * 
 * */
 
require_once('include/MVC/View/SugarView.php');
require_once('modules/Calendar2/Calendar2.php');

class Calendar2ViewAjaxNoteSave extends SugarView {
	
 	function Calendar2ViewAjaxNoteSave(){
		parent::SugarView();
 	}
 	
 	function process() {
		$this->display();
 	}

 	function display() {
		require_once('modules/cal_Notes/cal_Notes.php');


		require_once("modules/Calls/Call.php");
		require_once("modules/Meetings/Meeting.php");


		if($_REQUEST['type'] == 'call'){
			$bean = new Call();
			$type = 'call';
			$jn = "cal2_call_id_c";
		}
		if($_REQUEST['type'] == 'meeting'){
			$bean = new Meeting();
			$type = 'meeting';
			$jn = "cal2_meeting_id_c";
		}
		
		if(!$bean->ACLAccess('Save')) {
			$json_arr = array(
				'succuss' => 'no',
			);
			echo json_encode($json_arr);
			die;
		}

 		$GLOBALS['log']->debug('In Calendar2ViewAjaxNoteSave a_record='.$_REQUEST['a_record']);

		$cn_id = "";
		if(!empty($_REQUEST['a_record'])) {
			$bean->retrieve($_REQUEST['a_record']);
			$rel = "cal_notes_".$type."s";
			$bean->load_relationship($rel);
			$re = $bean->$rel->get();
			if(count($re))
				$cn_id = $re[0];
		}
		
		
		
		$c = new cal_Notes();

			
		require_once('include/formbase.php');

		if(!empty($_REQUEST['record']))
			$c->retrieve($_REQUEST['record']);

		$c = populateFromPost('', $c);		
		$c->name = $bean->name;					
		$c->save();
		
		if(empty($cn_id))
			$bean->$rel->add($c->id);


		if(($_REQUEST['eor']) || empty($cn_id)){
			$qu = "
				SELECT id FROM ".$bean->table_name." t
				WHERE t.deleted = 0 AND t.".$jn." = '".addslashes($bean->id)."'
			";
		
			$re = $bean->db->query($qu);
			while($ro = $bean->db->fetchByAssoc($re)){
				$bean->retrieve($ro['id']);			
				$rel = "cal_notes_".$type."s";
				$bean->load_relationship($rel);	
				$ra = $bean->$rel->get();
				if(!empty($ra[0]))
					$bean->$rel->delete($bean->id,$ra[0]);	
			
				$c1 = new cal_Notes();
			
				foreach($c1->field_defs as $field=>$def){
					if($field == 'id')
						continue;
					$c1->$field = $c->$field;
				}
			

				foreach($c1->additional_column_fields as $field){
					if($field == 'id')
						continue;
					$c1->$field = $c->$field;
				}
			

				$c1->save();
				$bean->$rel->add($c1->id);	
			
			}
		}
	}
}
?>
