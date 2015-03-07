<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
require_once("modules/Calendar2/functions.php");
$d_start_time = $current_user->getPreference('d_start_time');
$d_end_time = $current_user->getPreference('d_end_time');


if(empty($d_start_time))
	$d_start_time = "09:00";
if(empty($d_end_time))
	$d_end_time = "18:00";
	

$tarr = explode(":",$d_start_time);
$d_start_hour = $tarr[0];
$d_start_min = $tarr[1];
$tarr = explode(":",$d_end_time);
$d_end_hour = $tarr[0];
$d_end_min = $tarr[1];

$hour_start = $d_start_hour;
$minute_start = $d_start_min;
$hour_end = $d_end_hour;
$minute_end = $d_end_min;


$day_duration_hours = $hour_end - $hour_start;
if($minute_end < $minute_start){
	$day_duration_hours--;
	$day_duration_minutes = $minute_start - $minute_end;
}else
	$day_duration_minutes = $minute_end - $minute_start;

global $current_language, $currentModuele;
$current_module_strings = return_module_language($current_language, 'Calendar2');

if($currentModule == 'Home') {
	//for dashlet
	$dom_name = 'dom_cal_day_short';
} else {
	$dom_name = 'dom_cal_day_long';
}

$weekday_names = array();
$of = 0;
$startday = $first_day_of_a_week;

if($startday != "Monday") {
	$of = 1;
	$count = 0;
	foreach($GLOBALS['app_list_strings'][$dom_name] as $k => $v) {
		if ($k < 2 ) continue;
		$weekday_names[$count] = $GLOBALS['app_list_strings'][$dom_name][$k - $of];
		$count ++;
	}
	$weekday_names[6] = $GLOBALS['app_list_strings'][$dom_name][7];
} else {
	$of = 0;
	$count = 0;
	foreach($GLOBALS['app_list_strings'][$dom_name] as $k => $v) {
		if ($k < 2 ) continue;
		$weekday_names[$count] = $GLOBALS['app_list_strings'][$dom_name][$k - $of];
		$count ++;
	}
	$weekday_names[6] = $GLOBALS['app_list_strings'][$dom_name][1];
}

/*
foreach($GLOBALS['app_list_strings'][$dom_name] as $k => $v)
	$weekday_names[$k-2] = $GLOBALS['app_list_strings'][$dom_name][$k - $of];

if($startday == "Monday")
	$weekday_names[6] = $GLOBALS['app_list_strings'][$dom_name][1];
else
	$weekday_names[6] = $GLOBALS['app_list_strings'][$dom_name][7];
*/


$today_unix = to_timestamp($gmt_today);




global $js_custom_version;
global $sugar_version;

?>

	<script type="text/javascript">
	<?php
	if (isPro()) {
		require_once('modules/Teams/Team.php');
		$tm = new Team();
		$tm->retrieve($GLOBALS['current_user']->default_team);
		echo 'var default_team_name = "'.$tm->name.'";';
		echo 'var default_team_id = "'.$GLOBALS['current_user']->default_team.'";';
		if (is551()) {
			echo 'var is551 = true;';
		} else {
			echo 'var is551 = false;';
		}
	} else {
		echo 'var default_team_name = "";';
		echo 'var default_team_id = "";';
		echo 'var is551 = false;';
	}
	if (!isset($default_activity) || $default_activity == "" || $default_activity == "call") {
		echo 'var default_activity = "call"';
	} else {
		echo 'var default_activity = "meeting"';
	}
	?>
	</script>

	<link type="text/css" href="modules/Calendar2/css/themes/base/ui.all.css" rel="stylesheet" />
	<script type="text/javascript" src="modules/Calendar2/js/jquery-1.3.2.min.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	<script type="text/javascript" src="modules/Calendar2/js/jquery.form.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	<script type="text/javascript" src="modules/Calendar2/js/jquery-ui-1.7.2.custom.min.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	
	<script type="text/javascript" src="include/javascript/sugar_grp_overlib.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>

	<script type="text/javascript" src="modules/Calendar2/PageComm.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>

	<link type="text/css" href="modules/Calendar2/PageStyle.css" rel="stylesheet" />
<?php
$script =<<<EOQ
	<script type="text/javascript">
	
	var pview = "";

	var t_step = "{$t_step}";
	var dropped = 0;
	var records_openable = true;
	var moved_from_cell;
	var deleted_id = "";
	var deleted_module = "";
	var old_caption = "";
	var max_zindex = 50;
	var disable_creating = false;
	var current_user_id = "{$GLOBALS['current_user']->id}";	
	var time_format = "{$GLOBALS['timedate']->get_user_time_format()}";
	var day_duration_hours = "{$day_duration_hours}";
	var day_duration_minutes = "{$day_duration_minutes}";
	
	var lbl_edit = "{$current_module_strings['LBL_EDIT_RECORD']}";
	var lbl_loading = "{$current_module_strings['LBL_LOADING']}";
	var lbl_error_saving = "{$current_module_strings['LBL_ERROR_SAVING']}";
	var lbl_error_loading = "{$current_module_strings['LBL_ERROR_LOADING']}";
	var lbl_another_browser = "{$current_module_strings['LBL_ANOTHER_BROWSER']}";
	var lbl_first_team = "{$current_module_strings['LBL_FIRST_TEAM']}";
	var lbl_remove_participants = "{$current_module_strings['LBL_REMOVE_PARTICIPANTS']}";
	var lbl_cannot_remove_first = "{$current_module_strings['MSG_CANNOT_REMOVE_FIRST']}";
	//this is used by avaxsave to check if it needs to remove am/pm to display a asved record
	var current_module = "{$currentModule}";
	var today_string = "{$today_string}";

	$(function() {
EOQ;
if (isPro() && is551()) {
	$script .=<<<EOQ
		collection['EditView_team_name'].add2 = 	function(){
									if($.browser.opera){
										alert(lbl_another_browser);
										return 0;
									}else
										collection['EditView_team_name'].add();
								}
	
		collection['EditView_team_name'].remove = function(num){
									if(num == 0){
										alert(lbl_first_team);
										return "";
									}else{

									   radio_els=this.get_radios();
									   if(radio_els.length==1){
									    div_el=document.getElementById(this.field_element_name+'_input_div_'+num);
									    input_els=div_el.getElementsByTagName('input');
									    input_els[0].value='';
									    input_els[1].value='';
									    if(this.primary_field){
									     div_el=document.getElementById(this.field_element_name+'_radio_div_'+num);
									     radio_els=div_el.getElementsByTagName('input');
									     radio_els[0].checked=false;
									    }
									   }else{
									    div_el=document.getElementById(this.field_element_name+'_input_div_'+num);
									    tr_to_remove=document.getElementById('lineFields_'+this.field_element_name+'_'+num);
									    div_el.parentNode.parentNode.parentNode.removeChild(tr_to_remove);
									    var radios=this.get_radios();
									    div_id='lineFields_'+this.field_element_name+'_'+num;
									    if(typeof sqs_objects[div_id.replace("_field_","_")]!='undefined'){
									     delete(sqs_objects[div_id.replace("_field_","_")]);
									    }
									    var checked=false;
									    for(var k=0;k<radios.length;k++){
									     if(radios[k].checked){
									      checked=true;
									     }
									    }
									    var primary_checked=document.forms[this.form].elements[this.field+"_allowed_to_check"];
									    var allowed_to_check=true;
									    if(primary_checked&&primary_checked.value=='false'){
									     allowed_to_check=false;
									    }
									    if(/EditView/.test(this.form)&&!checked&&typeof radios[0]!='undefined'&&allowed_to_check){
									     radios[0].checked=true;
									     this.changePrimary(true);
									     this.js_more();
									     this.js_more();
									    }
									    if(radios.length==1){
									     this.more_status=false;
									     document.getElementById('more_'+this.field_element_name).style.display='none';
									     this.show_arrow_label(false);
									     this.js_more();
									    }else{
									     this.js_more();
									     this.js_more();
									    }
									  }
									}
							  };
EOQ;
}

$script .=<<<EOQ
		var droped_to_time;
		$(".t_cell").droppable({
			hoverClass: 't_cell_active',
			tolerance: 'pointer',
			accept: '.record_item, .scDrag',
			drop: function(event, ui) {
				
				if(!ui.draggable.hasClass('scDrag')){
				dropped = 1;
				
				ui.draggable.css( { "position" : "relative", "top" : "0px", "float" : "none" } );
				ui.draggable.appendTo($(this));
				align_divs($(this).attr("id"));
				align_divs(moved_from_cell);
				
				cut_record(ui.draggable.attr('id'));
				
				//
				var sts = "<div style='float:left;'>" + ui.draggable.attr("accept_status") + "&nbsp;</div>";
				
				var span = "<span class='rfloat' onmouseover='return show_i(" + '"' + ui.draggable.attr("record") + '"'  +  ", " + '"' + ui.draggable.attr("acttype") + '"' + ");' onmouseout='return nd(1000);' ><img src='index.php?entryPoint=getImage&themeName=Sugar5&imageName=info_inline.gif'></span><br style='clear: both;'>"; 
				
				ui.draggable.attr("date_start",$(this).attr("datetime"));
				ui.draggable.find('div.record_head').html($(this).attr('lang') + " " + "" + sts + span );
				
				droped_to_time = $(this).attr("lang");
				ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_LOADING'));

				$.getJSON(
						"index.php?module=Calendar2&action=AjaxAfterDrop&sugar_body_only=true",
						{
							"type" : ui.draggable.attr("acttype"),
							"record" : ui.draggable.attr("id"),
							"datetime" : $(this).attr("datetime")
						},
						function(res){
								records_openable = true;
								ui.draggable.attr("time_start", droped_to_time);

							 	if(res.succuss == 'yes'){
									//AddRecords(res);
									$.each(
										res.users,
										function (i,v){
											//updates the current user's scheduler row
											urllib.getURL('./vcal_server_cal2.php?type=vfb&source=outlook&user_id='+v,[["Content-Type", "text/plain"]], function (result) { 
											if (typeof GLOBAL_REGISTRY.freebusy == 'undefined') {
												GLOBAL_REGISTRY.freebusy = new Object();
											}
											if (typeof GLOBAL_REGISTRY.freebusy_adjusted == 'undefined') {
												GLOBAL_REGISTRY.freebusy_adjusted = new Object();
											}
											// parse vCal and put it in the registry using the user_id as a key:
						 					GLOBAL_REGISTRY.freebusy[v] = SugarVCalClient.parseResults(result.responseText, false);                  
						 					// parse for current user adjusted vCal
											GLOBAL_REGISTRY.freebusy_adjusted[v] = SugarVCalClient.parseResults(result.responseText, true);
											})
										} //function
									); //each
								} //endif
								ajaxStatus.hideStatus();
							} //function(res)
					); //getJSON
				}else{
					ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_LOADING'));			
					$.getJSON(
							"index.php?module=Calendar2&action=AjaxFlyCreate&sugar_body_only=true&currentmodule=" + current_module,
							{
								"duration_hours" : '1',
								"duration_minutes" : '0',
								"contact_id" : ui.draggable.attr("contact_id"),
								"account_id" : ui.draggable.attr("account_id"),
								"title" : ui.draggable.attr("account_name") + " " + ui.draggable.html(),
								"datetime" : $(this).attr("datetime")
							},
							function(res){
								records_openable = true;
							 	if(res.succuss == 'yes'){
									AddRecords(res);
									//AddRecordToPage(res);
									$.each(
										res.users,
										function (i,v){
											//updates the current user's scheduler row
											urllib.getURL('./vcal_server_cal2.php?type=vfb&source=outlook&user_id='+v,[["Content-Type", "text/plain"]], function (result) { 
											if (typeof GLOBAL_REGISTRY.freebusy == 'undefined') {
												GLOBAL_REGISTRY.freebusy = new Object();
											}
											if (typeof GLOBAL_REGISTRY.freebusy_adjusted == 'undefined') {
												GLOBAL_REGISTRY.freebusy_adjusted = new Object();
											}
											// parse vCal and put it in the registry using the user_id as a key:
						 					GLOBAL_REGISTRY.freebusy[v] = SugarVCalClient.parseResults(result.responseText, false);                  
						 					// parse for current user adjusted vCal
											GLOBAL_REGISTRY.freebusy_adjusted[v] = SugarVCalClient.parseResults(result.responseText, true);
											})
										}
									);
								}
								ajaxStatus.hideStatus();
							}
					);
				
				}
			},
			
			over: function(event, ui) { 
				ui.draggable.find('div.record_head').html($(this).attr('lang'));	
			},
		
				
			deactivate: function(event, ui) {
				if(dropped == 0){
					ui.draggable.find('div.record_head').html(old_caption);
				}
				
			}
			
		});
		
		$("div.left_cell:nth-child(odd)").addClass("odd");
		$("div.t_cell:nth-child(odd)").addClass("odd");
		$("div.t_icell:nth-child(odd)").addClass("odd");
		$(".t_cell").click(	function() {
						if(!disable_creating){
							
							$('#ui-dialog-title-record_dialog').html("{$current_module_strings['LBL_CREATE_NEW_RECORD']}");
							$('#record_dialog').dialog('open');
							$('#date_start_date').attr("value",$(".t_cell").attr("datetime"));
							$("#form_record").attr("value","");
							$("#name").attr("value","");
							
							hide_sch_div();
							$("#list_div_win").html("");
							
							$("#repeat_type").removeAttr("disabled");
							
							$(".record_dialog_class .ui-dialog-buttonpane button:nth-child(2)").attr("disabled","disabled");
														
							var combo_date_start = new Datetimecombo($(this).attr("datetime"), "date_start", "{$timedate->get_user_time_format()}", 102, '', ''); 
							text = combo_date_start.html('SugarWidgetScheduler.update_time();');
							document.getElementById('date_start_time_section').innerHTML = text;
							eval(combo_date_start.jsscript('SugarWidgetScheduler.update_time();'));
							combo_date_start.update(); 
							
							document.getElementsByName("search_first_name")[0].value="";
							document.getElementsByName("search_last_name")[0].value="";
							document.getElementsByName("search_email")[0].value="";
														
							GLOBAL_REGISTRY.focus.users_arr=[GLOBAL_REGISTRY.current_user];						
							
							SugarWidgetScheduler.update_time();
							
							
							loadCal2Note("","");
						}
					}
		);
		$("#record_dialog").dialog(
				{
					dialogClass: 'record_dialog_class',
					bgiframe: false,
					autoOpen: false,
					height: 550,
					width: 800,
					modal: true,
			
					buttons: {
						'{$current_module_strings['LBL_SAVE_BUTTON']}': function() {
							clear_all_errors();
							if(!(check_form('EditView') && isValidDuration()))
								return false;
							fill_invitees2();
							fill_reccurence();

							//remove old recurrence
							//checks if all of recurred schedules are going to be removed
							var edit_all_recurrence = false;
							edit_all_recurrence = $("#edit_all_recurrence").val();
							if (edit_all_recurrence) {
								//in case the save button is pushed before data is loaded
								if ($("#name").val() == "") return;
								deleted_id = $("#form_record").val();
								deleted_module = $("#cur_module").val();
								var delete_recurring = false;
								var delete_first_recurring = false;
								$.post(
									"index.php?module=Calendar2&action=AjaxRemove&sugar_body_only=true",
									{
										"cur_module" : deleted_module ,
										"record" : deleted_id,
										"delete_recurring": delete_recurring,
										"delete_first_recurring": delete_first_recurring,
										"edit_all_recurrence": edit_all_recurrence
									},
									function(){
										var cell_id = $("#" + deleted_id).parent().attr("id");
										if(pview == 'shared')	
											removeSharedById(deleted_id);
										$("#" + deleted_id).remove();
										align_divs(cell_id);
										
										ids = new Array();
										$.each(
											$("div[cal2_recur_id_c='" + deleted_id + "']"),
											function (i,v){
												ids[i] = $(v).parent().attr('id');
											}
										);				
										$("div[cal2_recur_id_c='" + deleted_id + "']").remove();
										$.each(
											ids,
											function (i,v){
												align_divs(ids[i]);
											}
										);//each
									} //function()
								); //post
							} //end edit_all_recurrence

							$("#EditView").ajaxSubmit(
								{
									beforeSubmit:function(){ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_LOADING'));},
									url: "index.php?module=Calendar2&action=AjaxSave&sugar_body_only=true&currentmodule=" + current_module,
									dataType: "json",
								 	success:	function(res){								 	
									if(res.succuss == 'yes'){
										saveCal2Note(res.record,res.type);
										AddRecords(res);
										$.each(
											res.users,
											function (i,v){
												//updates the current user's scheduler row
												urllib.getURL('./vcal_server_cal2.php?type=vfb&source=outlook&user_id='+v,[["Content-Type", "text/plain"]], function (result) { 
												if (typeof GLOBAL_REGISTRY.freebusy == 'undefined') {
													GLOBAL_REGISTRY.freebusy = new Object();
												}
												if (typeof GLOBAL_REGISTRY.freebusy_adjusted == 'undefined') {
													GLOBAL_REGISTRY.freebusy_adjusted = new Object();
												}
												// parse vCal and put it in the registry using the user_id as a key:
							 					GLOBAL_REGISTRY.freebusy[v] = SugarVCalClient.parseResults(result.responseText, false);
							 					// parse for current user adjusted vCal
												GLOBAL_REGISTRY.freebusy_adjusted[v] = SugarVCalClient.parseResults(result.responseText, true);
												})
											}
										);
						 				$("#record_dialog").dialog('close');
						 				ajaxStatus.hideStatus();
						 				
						 			}else
						 				alert(lbl_error_saving);
						 				return;
						 			} //success
								} 
							);//ajaxSubmit
						},
						
						'{$current_module_strings['LBL_DELETE_BUTTON']}': function(){
							if($("#form_record").val() != "") {
								//checks if all of recurred schedules are going to be removed
								var edit_all_recurrence = false;
								edit_all_recurrence = $("#edit_all_recurrence").val();

								//checks if a recurred schedule is going to be removed
								var delete_recurring = false;
								if( $("#cal2_recur_id_c").val() != '')
									delete_recurring = true;

								//checks if the 1st recurred schedule is going to be removed
								var delete_first_recurring = false;
								if( $("#cal2_repeat_type_c").val() != '')
									delete_first_recurring = true;

								if (!edit_all_recurrence && delete_first_recurring) {
									alert(lbl_cannot_remove_first);
								} else if(confirm("{$current_module_strings['MSG_REMOVE_CONFIRM']}")){
									deleted_id = $("#form_record").val();
									deleted_module = $("#cur_module").val();
									if(edit_all_recurrence){
										$.post(
											"index.php?module=Calendar2&action=AjaxRemove&sugar_body_only=true",
											{
												"cur_module" : deleted_module ,
												"record" : deleted_id,
												"delete_recurring": delete_recurring,
												"delete_first_recurring": delete_first_recurring,
												"edit_all_recurrence": edit_all_recurrence
											},
											function(){
												var cell_id = $("#" + deleted_id).parent().attr("id");
												if(pview == 'shared')	
													removeSharedById(deleted_id);												
												$("#" + deleted_id).remove();
												align_divs(cell_id);
												
												ids = new Array();			
												$.each(
													$("div[cal2_recur_id_c='" + deleted_id + "']"),
													function (i,v){
														ids[i] = $(v).parent().attr('id'); 					
													}
												);				
												$("div[cal2_recur_id_c='" + deleted_id + "']").remove();				
												$.each(
													ids,
													function (i,v){
														align_divs(ids[i]);		
													}				
												);
											}					
										);
										$("#record_dialog").dialog('close');
									} else if (!delete_first_recurring) {
										$.post(
											"index.php?module=Calendar2&action=AjaxRemove&sugar_body_only=true",
											{
												"cur_module" : deleted_module ,
												"record" : deleted_id,
												"delete_recurring": delete_recurring,
												"delete_first_recurring": delete_first_recurring,
												"edit_all_recurrence": edit_all_recurrence
											},
											function(){
												var cell_id = $("#" + deleted_id).parent().attr("id");
												if(pview == 'shared')	
													removeSharedById(deleted_id);												
												$("#" + deleted_id).remove();
												align_divs(cell_id);
											}
										);
										$("#record_dialog").dialog('close');
									}
								}
							}
						},
						'{$current_module_strings['LBL_CANCEL_BUTTON']}': function() {
							$(this).dialog('close');
						}
				
					},
					open: function() {
						$('.ui-dialog-buttonpane').find('button:contains("{$current_module_strings['LBL_SAVE_BUTTON']}")').addClass('button');
						$('.ui-dialog-buttonpane').find('button:contains("{$current_module_strings['LBL_DELETE_BUTTON']}")').addClass('button');
						$('.ui-dialog-buttonpane').find('button:contains("{$current_module_strings['LBL_CANCEL_BUTTON']}")').addClass('button');
					},
					close: function() {
						clearFields();
					}

				}
		);		
		
		
		$("#record_tabs").tabs({ selected: 0 });
		
		var ActRecords = [
EOQ;
				$ft = true;
				foreach($ActRecords as $act){
					if(!$ft)
						$script .= ",";
					$script .= "{";
					$script .= '
						"type" : "'.$act["type"].'", 
						"record" : "'.$act["id"].'",
						"start" : "'.$act["start"].'",
						"accept_status" : "'.str_replace("\"","'",$act["accept_status"]).'",
						"date_start" : "'.$act["date_start"].'",
						"time_start" : "'.$act["time_start"].'",
						"duration_hours" : '.$act["duration_hours"].',
						"duration_minutes" : '.$act["duration_minutes"].',
						"user_id" : "'.$act["user_id"].'",
						"record_name": "'.$act["name"].'",
						"location": "'.$act["location"].'",
						"cal2_recur_id_c": "'.$act["cal2_recur_id_c"].'",
						"cal2_category_c": "'.$act["cal2_category_c"].'",
						"description" : "'.$act["description"].'",
						"detailview" : "'.$act["detailview"].'"
					';
					$script .= "}";
					$ft = false;
				}
$script .=<<<EOQ
		];
		
		$("#settings_dialog").dialog(
				{
					dialogClass: 'settings_dialog_class',
					bgiframe: false,
					autoOpen: false,
					height: 400,
					width: 520,
					modal: true,
					
					buttons: {
						'{$current_module_strings['LBL_APPLY_BUTTON']}': 	function(){
									$("#form_settings").submit();
						
								},
						'{$current_module_strings['LBL_CANCEL_BUTTON']}': function(){
							$(this).dialog('close');
						}
					},
					open: function() {
						$('.ui-dialog-buttonpane').find('button:contains("{$current_module_strings['LBL_APPLY_BUTTON']}")').addClass('button');
						$('.ui-dialog-buttonpane').find('button:contains("{$current_module_strings['LBL_CANCEL_BUTTON']}")').addClass('button');
					},
					close: function(){
						$("#form_settings").resetForm();
					}
				}
		);
		$("#settings_tabs").tabs({ selected: 0 });
		repeat_type_selected();
				
		for ( var i in ActRecords ){
			AddRecordToPage(ActRecords[i]);
		};
		
		$(".day_head[date='"+today_string+"']").addClass("today");
		
	});
	</script>
EOQ;
echo $script;
?>
	

		
	<div id="record_dialog" title="Record dialog" style='display: none;'>
		<div id="record_tabs">
			<ul class="tablist">
				<li style="list-style:none;"><a href="#record_tabs-1" onclick="hide_sch_div();"><?php echo $current_module_strings['LBL_GENERAL']; ?></a></li>
				<li style="list-style:none;"><a href="#record_tabs-2" onclick="show_sch_div('2');"><?php echo $current_module_strings['LBL_PARTICIPANTS']; ?></a></li>
				<li style="list-style:none;"><a href="#record_tabs-5" onclick="show_sch_div('5');"><?php echo $current_module_strings['LBL_INV_CONTACT']; ?></a></li>
				<li style="list-style:none;"><a href="#record_tabs-3" onclick="hide_sch_div();"><?php echo $current_module_strings['LBL_RECURENCE']; ?></a></li>
				<li style="list-style:none;"><a href="#record_tabs-4" onclick="hide_sch_div();"><?php echo $current_module_strings['LBL_NOTE']; ?></a></li>				
			</ul>
			<div id="record_tabs-1">
			
			<form id="EditView" name="EditView" method="POST">
		
				<input name='return_module' id='return_module' type='hidden' value="Meetings">
				<input name='cur_module' id='cur_module' type='hidden' value="">
				<input name='record' id='form_record' type='hidden' value="">
				
				<?php include("modules/Calendar2/PopupEditView.php");?>
			</form>	
				
			</div>
			<div id="record_tabs-2">
				<?php include("modules/Calendar2/PopupParticipants.php");?>
			</div>
			<div id="record_tabs-5">
				<?php include("modules/Calendar2/PopupInvite.php");?>
			</div>			
			<div id="record_tabs-3">
				<?php include("modules/Calendar2/PopupReccurence.php");?>			
			</div>
			<div id="record_tabs-4">
				<?php ?>			
			</div>
			<div class="h3Row" id="scheduler" style="display:none;"></div>
		</div>		
	</div>	
	
	
	<script type="text/javascript" src="include/JSON.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	<script type="text/javascript" src="include/jsolait/init.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	<script type="text/javascript" src="include/jsolait/lib/urllib.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>

	<script type="text/javascript">	
	<?php
		require_once('include/json_config_cal2.php');
		global $json;
        	$json = getJSONobj();
        	$json_config_cal2 = new json_config_cal2();
        	$GRjavascript = $json_config_cal2->get_static_json_server(false, true, 'Meetings');
        	
        	echo $GRjavascript;
	?>
	</script>
	
	<script type="text/javascript" src="include/javascript/jsclass_base.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	<script type="text/javascript" src="modules/Calendar2/jsclass_async_cal2.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	<script type="text/javascript" src="modules/Meetings/jsclass_scheduler.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	<script>toggle_portal_flag();function toggle_portal_flag()  {  } </script>



	<script type="text/javascript">
	
		function fill_invitees() { 
			if (typeof(GLOBAL_REGISTRY) != 'undefined')  {    
				SugarWidgetScheduler.fill_invitees(document.EditView);
			} 
		}
	

	
		var root_div = document.getElementById('scheduler');
		var sugarContainer_instance = new SugarContainer(document.getElementById('scheduler'));
		sugarContainer_instance.start(SugarWidgetScheduler);
		
		if(typeof(GLOBAL_REGISTRY.focus.users_arr)=='undefined'||GLOBAL_REGISTRY.focus.users_arr.length==0){GLOBAL_REGISTRY.focus.users_arr=[GLOBAL_REGISTRY.current_user];}
		/*if ( document.getElementById('save_and_continue') ) {
		    var oldclick = document.getElementById('save_and_continue').attributes['onclick'].nodeValue;
		    document.getElementById('save_and_continue').onclick = function(){
			fill_invitees();
			eval(oldclick);
		    }
		}*/
		
		//$(".schedulerInvitees").remove();
	
	</script>

	<script type="text/javascript">
	<?php 
	if (isPro()) {
		echo 'var teams_or_users = "teams";';
	} else {
		echo 'var teams_or_users = "users";';
	}
	?></script>

	<script type="text/javascript" src="modules/Calendar2/particapants.js?s=<?php echo $sugar_version; ?>&c=<?php echo $js_custom_version; ?>"></script>
	
	<script type="text/javascript">
		var GLOBAL_PARTICIPANTS = new Object();

		GLOBAL_PARTICIPANTS['Teams'] = [ 
			<?php
				require_once('include/database/PearDatabase.php');
				$db = &PearDatabase::getInstance();
				$db2 = &PearDatabase::getInstance();
				if (isPro()) {
					$res = $db->query("SELECT id,name FROM teams WHERE private = 0 AND deleted <> 1 ORDER BY name");
					$ft = true;
					while($row = $db->fetchByAssoc($res)){	
						if(!$ft)
							echo ",";
						else
							$ft = false;
							
						$dbU = &PearDatabase::getInstance();
						$qU = 	"
							SELECT u.id u_id,u.user_name u_name 
								FROM users AS u
								JOIN team_memberships AS tm ON tm.user_id = u.id
								WHERE tm.team_id = '".$row['id']."' AND u.deleted <> 1 AND u.status = 'Active' AND tm.deleted <> 1
								ORDER BY u.user_name 
							";
						$resU = $dbU->query($qU);
						$users_str = "";
						$ftU = true;	
						while($rowU = $dbU->fetchByAssoc($resU)){
							if(!$ftU)
								$users_str .= ",";
							else
								$ftU = false;
							$users_str .=	"{".
										"'id': '" . $rowU['u_id'] . "',".
										"'name': '" . $rowU['u_name'] . "'".
									"}";
						}			
														
						echo	"{".
								"'id': '" . $row['id'] . "',".
								"'name': '" . $row['name'] . "',".
								"'users': [ " . $users_str . " ]". 
							"}";			
					}
				} else {
					$qU = 	"
						SELECT u.id u_id,u.user_name u_name 
							FROM users AS u
							WHERE u.deleted <> 1 AND u.status = 'Active'
							ORDER BY u.user_name 
							";
					$resU = $db->query($qU);
					$users_str = "";
					$ftU = true;	
					while($rowU = $db->fetchByAssoc($resU)){
						if(!$ftU)
							$users_str .= ",";
						else
							$ftU = false;
						$users_str .=	"{".
									"'id': '" . $rowU['u_id'] . "',".
									"'name': '" . $rowU['u_name'] . "'".
								"}";
					}			
														
					echo	"{".
							"'id': '1',".
							"'name': 'Global',".
							"'users': [ " . $users_str . " ]". 
						"}";
					
				}
			?>
				
		];
				
		GLOBAL_PARTICIPANTS['Resources'] = [ 
			<?php
		
				$query = "SELECT id,name FROM resources ";
				global $current_user;
				if (is_admin($current_user)) {
					$query .= " WHERE deleted <> 1 ORDER BY name";
				} else {
					require_once('modules/Resources/Resource.php');
					$temp_res = new Resource();
					if (isPro()) {
						$temp_res->add_team_security_where_clause($query);
						$query .= " AND deleted <> 1 ORDER BY name";
					} else {
						$query .= " WHERE deleted <> 1 ORDER BY name";
					}
				}
				$res = $db->query($query);
				$ft = true;
				while($row = $db->fetchByAssoc($res)){	
					if(!$ft)
						echo ",";
					else
						$ft = false;			
														
					echo	"{".
							"'id': '" . $row['id'] . "',".
							"'name': '" . $row['name'] . "'".
						"}";			
				}
				
				
			?>		
		
		];
		
		<?php
		if (isPro()) {
			echo "fill_teams();";
		} else {
			echo "fill_users_ce();";
		}
		?>
		//fill_contact_ce();
		fill_resources();
		
		prior_filling_select_boxes();
	
	</script>
	
	
	<div id="settings_dialog" title="<?php echo $current_module_strings['LBL_SETTINGS']; ?>" style='display: none;'>
		<div id="settings_tabs">
			<ul class="tablist">
				<li style="list-style:none;"><a href="#settings_tabs-1"><?php echo $current_module_strings['LBL_GENERAL']; ?></a></li>
				<li style="list-style:none;"><a href="#settings_tabs-2"><?php echo $current_module_strings['LBL_GCAL']; ?></a></li>
				<li style="list-style:none;"><a href="#settings_tabs-3"><?php echo $current_module_strings['LBL_CALDAV']; ?></a></li>
			</ul>	
			<!-- Cal2 Modified for google 2010/09/16 -->
			<form name='settings' id='form_settings' method='POST' action='index.php?module=Calendar2&action=SaveSettings'>
			<div id="settings_tabs-1">
				<?php include("modules/Calendar2/PopupSettings.php"); ?>	
			</div>
			<div id="settings_tabs-2"> <!-- Included New Tab for Google Settings -->
				<?php include("modules/Calendar2/PopupGcal.php"); ?>	
			</div>
			<div id="settings_tabs-3"> <!-- Included New Tab for Caldav Settings -->
				<?php include("modules/Calendar2/PopupCaldav.php"); ?>	
			</div>			
			</form>
			<!-- Cal2 End -->
		</div>		
	</div>
	
	<script type="text/javascript">
	var day_start_hours = "<?php echo $d_start_hour; ?>";
	var day_start_minutes = "<?php echo $d_start_min; ?>";
	var day_start_meridiem = "<?php echo $start_m; ?>"; // don't remove this line!
	</script>
	
	
	
	<script type="text/javascript">
			addToValidate('EditView', 'name', 'name', true,'Subject' );
			<?php if (isPro()) echo "addToValidate('EditView', 'team_count', 'relate', true,'Teams' );"; ?>
			<?php if (isPro()) echo "addToValidate('EditView', 'team_name', 'teamset', true,'Teams' );"; ?>
			addToValidate('EditView', 'duration_hours', 'int', true,'Duration Hours' );
			addToValidate('EditView', 'date_start_date', 'date', true,'Start Date' );
			addToValidate('EditView', 'status', 'enum', true,'Status' );
			addToValidateBinaryDependency('EditView', 'assigned_user_name', 'alpha', false,'No match for field: Assigned to', 'assigned_user_id' );
	</script>

<link rel="stylesheet" type="text/css" href="<?php echo getJSPath("themes/default/ext/resources/css/ext-all.css")?>">
<link rel="stylesheet" type="text/css" href="<?php echo getJSPath("themes/default/ext/resources/css/xtheme-gray.css")?>">
<script type="text/javascript" src="include/javascript/sugar_grp_yui_widgets.js"></script>	

<?php

?>
