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
require_once("include/utils.php");
global $current_language;
global $current_user;
$calls_lang = return_module_language($current_language, "Calls");
$meetings_lang = return_module_language($current_language, "Meetings");

global $app_strings,$app_list_strings,$beanList;
global $timedate;
$gmt_default_date_start = $timedate->get_gmt_db_datetime();
$user_default_date_start  = $timedate->handle_offset($gmt_default_date_start, $GLOBALS['timedate']->get_date_time_format());

//$CALENDAR_DATEFORMAT = $timedate->get_cal_date_format());
//$USER_DATEFORMAT = $timedate->get_user_date_format());
$date_format = $timedate->get_cal_date_format();
$time_format = $timedate->get_user_time_format();
$TIME_FORMAT = $time_format;      
$t23 = strpos($time_format, '23') !== false ? '%H' : '%I';
$time_separator = strpos($time_format, ':') !== false ? ':' : '.';
if(!isset($match[2]) || $match[2] == '') {
    $CALENDAR_FORMAT = $date_format . ' ' . $t23 . $time_separator . "%M";
} else {
    $pm = $match[2] == "pm" ? "%P" : "%p";
    $CALENDAR_FORMAT = $date_format . ' ' . $t23 . $time_separator . "%M" . $pm;
}


$hours_arr = array ();
$num_of_hours = 24;
$start_at = 0;

$TIME_MERIDIEM = "";
$time_pref = $timedate->get_time_format();
if(strpos($time_pref, 'a') || strpos($time_pref, 'A')) {
	$num_of_hours = 13;
	$start_at = 1;

	$options = strpos($time_pref, 'a') ? $app_list_strings['dom_meridiem_lowercase'] : $app_list_strings['dom_meridiem_uppercase'];
   	$TIME_MERIDIEM = get_select_options_with_id($options, 'am');   
   	
   	$TIME_MERIDIEM = "<select name='date_start_meridiem' tabindex='2'>".$TIME_MERIDIEM."</select>";
} 

for ($i = $start_at; $i < $num_of_hours; $i ++) {
	$i = $i."";
	if (strlen($i) == 1) {
		$i = "0".$i;
	}
	$hours_arr[$i] = $i;
}
$TIME_START_HOUR_OPTIONS = get_select_options_with_id($hours_arr, 1);
$min_options = array('0'=>'00', '15'=>'15', '30'=>'30', '45'=>'45');

//$TIME_START_MINUTE_OPTIONS = get_select_options_with_id($focus->minutes_values, 0);
$TIME_START_MINUTES_OPTIONS = get_select_options_with_id($min_options, 0);
	
$reminder_t = $current_user->getPreference('reminder_time');
$reminderHTML = '<select name="reminder_time">';
$reminderHTML .= get_select_options_with_id($app_list_strings['reminder_time_options'], $reminder_t);
$reminderHTML .= '</select>';

//$default_activity = $current_user->getPreference('default_activity');
$GLOBALS['log']->debug('PopupEditview default_activity='.$default_activity);
if (!isset($default_activity) || $default_activity == "" || $default_activity == "call") {
	$default_activity = "call";
	$call_checked = "checked";
	$mtg_checked = "";
	$dir_display = 'display: inline;';
} else {
	$call_checked = "";
	$mtg_checked = "checked";
	$dir_display = 'display: none;';
}
?>

<input type="hidden" name="user_invitees">
<input type="hidden" name="resources_assigned">
<input type="hidden" name="contacts_assigned">
<input type="hidden" name="leads_assigned">

<input type="hidden" name="cal2_repeat_type_c" id="cal2_repeat_type_c">
<input type="hidden" name="cal2_repeat_interval_c" id="cal2_repeat_interval_c">
<input type="hidden" name="cal2_repeat_end_date_c" id="cal2_repeat_end_date_c">
<input type="hidden" name="cal2_repeat_days_c" id="cal2_repeat_days_c">

<input type="hidden" name="edit_all_recurrence" id="edit_all_recurrence">

<input type="hidden" name="cal2_recur_id_c" id="cal2_recur_id_c" value="">


<table class="edit view" cellspacing="1" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_SUBJECT']."";?>
			<span class="required">*</span>
		</td>
		<td class='tabEditViewDF' valign="top">
				<input id="name" type="text" tabindex="100" title="" value="" maxlength="" size="30" name="name"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span>
				
		
				<input type="radio" name="appttype" id="radio_meeting" onchange="if(this.checked){this.form.cur_module.value='Meetings'; this.form.direction.style.display='none'; GR_update_focus('Meetings','');}" value="Meetings" tabindex="100"  <?php echo $mtg_checked; ?> />
				<?php
				echo $calls_lang['LNK_NEW_MEETING']."";
				
				?>
				
				<input type="radio" name="appttype" id="radio_call" onchange="if(this.checked){this.form.cur_module.value='Calls'; this.form.direction.style.display = 'inline'; GR_update_focus('Calls','');}" value="Calls" tabindex="100"  <?php echo $call_checked; ?> />
				<?php
				echo $calls_lang['LNK_NEW_CALL']."";
				?>
				
				</span>
			
		</td>
	</tr>
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_DATE_TIME']."";?>
			<span class="required">*</span>		
		</td>	
		<td class='tabEditViewDF' valign="top">
				<table border="0" cellpadding="0" cellspacing="0">
				<tr valign="middle">
				<td nowrap="nowrap">
				<input autocomplete="off" id="date_start_date" value="<?php echo $user_default_date_start; ?>" size="11" maxlength="10" title="" tabindex="102" onblur="combo_date_start.update(); " type="text">
				<img src="themes/default/images/jscalendar.gif" alt="Enter Date" id="date_start_trigger" align="absmiddle" border="0">&nbsp;
				</td>
				<td nowrap="nowrap">
					<div id="date_start_time_section" style='float:left;'>
						<select size="1" id="date_start_hours" tabindex="102" onchange="combo_date_start.update();">
							<?php echo $TIME_START_HOUR_OPTIONS; ?>
						</select>&nbsp;:
					&nbsp;
						<select size="1" id="date_start_minutes" tabindex="102" onchange="combo_date_start.update(); ">
							<?php echo $TIME_START_MINUTES_OPTIONS; ?>
						</select>
					&nbsp;
						<?php echo $TIME_MERIDIEM;?>
					</div>
					&nbsp;
					&nbsp; <input type='checkbox' name='cal2_whole_day_c' id='cal2_whole_day_c' onclick="check_whole_day();">  &nbsp;<?php echo $calls_lang['LBL_WHOLE_DAY'];?> <br style='clear: both;'>	
				</td>
				</tr>
				</table>
				<input id="date_start" name="date_start" value="<?php echo $user_default_date_start; ?>" type="hidden">
				<script type="text/javascript" src="include/SugarFields/Fields/Datetimecombo/Datetimecombo.js"></script>
				<script type="text/javascript">
					var combo_date_start = new Datetimecombo("<?php echo $user_default_date_start; ?>", "date_start", "<?php echo $TIME_FORMAT; ?>", 102, '', ''); 
					text = combo_date_start.html('SugarWidgetScheduler.update_time();');
					document.getElementById('date_start_time_section').innerHTML = text;
					eval(combo_date_start.jsscript('SugarWidgetScheduler.update_time();'));
				</script>
				<script type="text/javascript">
					function update_date_start_available() {
					      YAHOO.util.Event.onAvailable("date_start_date", this.handleOnAvailable, this); 
					}

					update_date_start_available.prototype.handleOnAvailable = function(me) {
						Calendar.setup ({
						onClose : update_date_start,
						inputField : "date_start_date",
						ifFormat : "<?php echo $CALENDAR_FORMAT;?>",
						daFormat : "<?php echo $CALENDAR_FORMAT;?>",
						button : "date_start_trigger",
						singleClick : true,
						step : 1,
						weekNumbers:false
						});
	
						//Call update for first time to round hours and minute values
						combo_date_start.update();
					}

					var obj_date_start = new update_date_start_available(); 
				</script>


		</td>
	</tr>
	
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_DURATION']."";?>
			<span class="required">*</span>
		</td>
		<td class='tabEditViewDF' valign="top">
			<script type="text/javascript">function isValidDuration() { form = document.getElementById('EditView'); if ( form.duration_hours.value + form.duration_minutes.value <= 0 ) { alert('<?php echo $calls_lang["NOTICE_DURATION_TIME"];?>'); return false; } return true; }</script>
			<input name="duration_hours" id="duration_hours" tabindex="1" size="2" maxlength="2" type="text" value="0" onkeyup="SugarWidgetScheduler.update_time();">
			<select name="duration_minutes" id="duration_minutes" tabindex="1" onchange="SugarWidgetScheduler.update_time();">
				<option value="0">00</option>
				<option value="15" selected="">15</option>
				<option value="30">30</option>
				<option value="45">45</option>
			</select>
			
			<input type="hidden" name="duration_hours_h" id="duration_hours_h">
			<input type="hidden" name="duration_minutes_h" id="duration_minutes_h">
			
			<span class="dateFormat"><?php echo $calls_lang["LBL_HOURS_MINUTES"];?></span>
			
		</td>
	</tr>	
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_REMINDER']."";?>
		</td>	
		<td class='tabEditViewDF' valign="top">	
			<input name="reminder_checked" type="hidden" value="0">
			<input name="reminder_checked" onclick='toggleDisplay("should_remind_list");' type="checkbox" class="checkbox" value="1" >
			<div id="should_remind_list" style="display:none"><?php echo $reminderHTML;?></div>	
		</td>	
	</tr>

	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $meetings_lang['LBL_LOCATION'];?>
		</td>	
		<td class='tabEditViewDF' valign="top">
			<input id="location" type="text" title="" value="" maxlength="" size="40" name="location"/>
		</td>
	</tr>

	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_STATUS'];?>
			<span class="required">*</span>
		</td>	
		<td class='tabEditViewDF' valign="top">		
			<select name="direction" id="direction" title="" tabindex="101" style="<?php echo $dir_display; ?>">
					<?php
						foreach($app_list_strings['call_direction_dom'] as $k => $v)
							echo '<option label="'.$v.'" value="'.$k.'">'.$v.'</option>';
					?>			
			</select>
			<select name="status" id="status" title="" tabindex="101">
					<?php
						foreach($app_list_strings['call_status_dom'] as $k => $v)
							echo '<option label="'.$v.'" value="'.$k.'">'.$v.'</option>';
					?>			
			</select>
		</td>	
	</tr>
	<?php 
	if (isPro()) {
		echo '<tr>';
		if (is551()) {
			echo '	<td class="dataLabel" width="20%" valign="top" scope="row">';
			echo $calls_lang['LBL_TEAMS'].":";
			echo '<span class="required">*</span>';
			echo '</td>';
			echo '<td class="tabEditViewDF" valign="top">';
			include("modules/Calendar2/TeamsEditView.php");
			echo '</td>';
		} else {
			echo '	<td class="dataLabel" width="20%" valign="top" scope="row">';
			echo $calls_lang['LBL_TEAM'].":";
			echo '<span class="required">*</span>';
			echo '</td>';
			echo '<td class="tabEditViewDF" valign="top">';
			echo "<input name='team_name' class='sqsEnabled yui-ac-input' tabindex='103' id='team_name' size='' value='".$current_user->default_team_name."' title='' autocomplete='off' type='text'>";
			echo "<input name='team_id' id='team_id' value='".$current_user->default_team."' type='hidden'>";
			echo "<input name='btn_team_name' id='btn_team_name' tabindex='103' title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accesskey='T' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' onclick='open_popup(\"Teams\", 600, 400, \"\", true, false, {\"call_back_function\":\"set_return\",\"form_name\":\"EditView\",\"field_to_name_array\":{\"id\":\"team_id\",\"name\":\"team_name\"}}, \"single\", true);' type='button'>";
			echo "<input name='btn_clr_team_name' id='btn_clr_team_name' tabindex='103' title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accesskey='C' class='button' onclick='this.form.team_name.value = \"\"; this.form.team_id.value = \"\";' value='".$app_strings['LBL_CLEAR_BUTTON_LABEL']."' type='button'>";
			echo "</td>";
		}
		echo '</tr>';
	}
	?>
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_ASSIGNED_TO_NAME'].":";?>
		</td>
		<td class='tabEditViewDF' valign="top">
				<input name="cal2_assigned_user_name" class="sqsEnabled yui-ac-input" tabindex="104" id="cal2_assigned_user_name" size="" value="<?php echo $current_user->user_name; ?>" title="" autocomplete="off" type="text"><div class="yui-ac-container" id="EditView_assigned_user_name_results"><div style="display: none;" class="yui-ac-content"><div style="display: none;" class="yui-ac-hd"></div><div class="yui-ac-bd"><ul><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li></ul></div><div style="display: none;" class="yui-ac-ft"></div></div></div>
				<input name="cal2_assigned_user_id" id="cal2_assigned_user_id" value="<?php echo $current_user->id; ?>" type="hidden">
				<input name="btn_assigned_user_name" id="btn_assigned_user_name" tabindex="104" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE'];?>" accesskey="T" class="button" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL'];?>" onclick='open_popup("Users", 600, 400, "", true, false, {"call_back_function":"set_return","form_name":"EditView","field_to_name_array":{"id":"cal2_assigned_user_id","user_name":"cal2_assigned_user_name"}}, "single", true);' type="button">
				<input name="btn_clr_assigned_user_name" id="btn_clr_assigned_user_name" tabindex="104" title="<?php echo $app_strings['LBL_CLEAR_BUTTON_TITLE'];?>" accesskey="C" class="button" onclick="this.form.cal2_assigned_user_name.value = ''; this.form.cal2_assigned_user_id.value = '';" value="<?php echo $app_strings['LBL_CLEAR_BUTTON_LABEL'];?>" type="button">
		</td>	
	</tr>
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $app_strings['LBL_LIST_RELATED_TO'].":";?>
		</td>	
		<td class='tabEditViewDF' valign="top">
		
			<?php
				$parent_types = $app_list_strings['record_type_display'];
				$disabled_parent_types = ACLController::disabledModuleList($parent_types,false, 'list');
				foreach($disabled_parent_types as $disabled_parent_type){
					if($disabled_parent_type != $focus->parent_type){
						unset($parent_types[$disabled_parent_type]);
					}
				}
				

			?>
		
				<select name="parent_type" tabindex="107" id="parent_type" title="" onchange='document.EditView.parent_name.value="";document.EditView.parent_id.value="";  checkParentType(document.EditView.parent_type.value, document.EditView.btn_parent_name);changeQS();'>
					<?php
					foreach($app_list_strings['parent_type_display'] as $k => $v)
						echo '<option label="'.$v.'" value="'.$k.'">'.$v.'</option>';
					?>
					
					
				</select>
				<input name="parent_name" id="parent_name" class="sqsEnabled" tabindex="107" size="" value="" autocomplete="off" type="text">
				<input name="parent_id" id="parent_id" value="" type="hidden">
				<input name="btn_parent_name" id="btn_parent_name" tabindex="107" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE'];?>" accesskey="T" class="button" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL'];?>" onclick='open_popup(document.EditView.parent_type.value, 600, 400, "", true, false, {"call_back_function":"set_return","form_name":"EditView","field_to_name_array":{"id":"parent_id","name":"parent_name"}}, "single", true);' type="button">

				<input name="btn_clr_parent_name" id="btn_clr_parent_name" tabindex="107" title="<?php echo $app_strings['LBL_CLEAR_BUTTON_TITLE'];?>" accesskey="C" class="button" onclick="this.form.parent_name.value = ''; this.form.parent_id.value = '';" value="<?php echo $app_strings['LBL_CLEAR_BUTTON_LABEL'];?>" type="button">
				
				<!-- for link of parent object -->
				<span id="paren_dtl_link" style="padding-left:5px;"></span>
				
				<script type="text/javascript">
				function changeQS() {

					new_module = document.forms["EditView"].elements["parent_type"].value;

					if(typeof(disabledModules[new_module]) != 'undefined') {

						sqs_objects["EditView_parent_name"]["disable"] = true;
						document.forms["EditView"].elements["parent_name"].readOnly = true;

					} else {

						sqs_objects["EditView_parent_name"]["disable"] = false;
						document.forms["EditView"].elements["parent_name"].readOnly = false;

					}

					sqs_objects["EditView_parent_name"]["modules"] = new Array(new_module);
					if(typeof QSProcessedFieldsArray != 'undefined')
				    	{
					   QSProcessedFieldsArray["EditView_parent_name"] = false;
					}
					sqs_objects["EditView_parent_name"] = sqs_objects["parent_"+new_module];
				    
				    enableQS(false);

				}

				</script>
				<script>var disabledModules=[];</script>
		</td>	
	</tr>	
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_SEND_BUTTON_LABEL'].":";?>
		</td>	
		<td class='tabEditViewDF' valign="top">
			<input type='checkbox' id='send_invites' name='send_invites'>
		</td>
	</tr>	
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_CATEGORY'].":";?>
		</td>	
		<td class='tabEditViewDF' valign="top">
			<select id='cal2_category_c' name='cal2_category_c'>
			<?php 					
				foreach($app_list_strings['category_list'] as $k => $v)
					echo '<option label="'.$v.'" value="'.$k.'">'.$v.'</option>';
			?>
			</select>			
		</td>
	</tr>
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_PRIVATE'].":";?>
		</td>	
		<td class='tabEditViewDF' valign="top">
			<input type='checkbox' id='cal2_options_c' name='cal2_options_c'>			
		</td>
	</tr>	
	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $current_module_strings['LBL_ACCEPT_STATUS']; ?>
		</td>	
		<td class='tabEditViewDF' valign="top">
			<select name="accept_status" id="accept_status" title="">
				<?php
					foreach($app_list_strings['dom_meeting_accept_status'] as $k => $v)
						echo '<option label="'.$v.'" value="'.$k.'">'.$v.'</option>';
				?>			
			</select>
		</td>
	</tr>

	<tr>
		<td class="dataLabel" width="20%" valign="top" scope="row">
			<?php echo $calls_lang['LBL_DESCRIPTION'];?>
		</td>	
		<td class='tabEditViewDF' valign="top">
			<textarea id='description' name='description' cols='60' rows='4'></textarea>
		</td>
	</tr>


</table>
<script type="text/javascript">
disableOnUnloadEditView(document.forms["EditView"]);
</script>

<?php

?>
