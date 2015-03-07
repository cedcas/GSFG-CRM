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

?>

<table class="edit view" cellspacing="1" cellpadding="0" border="0" width="100%">

	<tr>
		<td class="dataLabel">
			<?php echo $current_module_strings['LBL_GCAL_USERNAME']; ?>
		</td>
		<td class='tabEditViewDF'>
			<input type="text" name="gcal_username" id="gcal_username" value="<?php echo $gcal_username?>">
		</td>		
		<td class="dataLabel">
			<?php echo $current_module_strings['LBL_GCAL_PASSOWRD']; ?>
		</td>
		<td class='tabEditViewDF'>
			<input type="password" name="gcal_password" id="gcal_password" value="<?php echo $gcal_password?>">
		</td>			
	</tr>
	<tr>
		<td class="dataLabel" valign="top">
			<?php echo $current_module_strings['LBL_GCAL_SYNC_OPT']; ?>
		</td>
		<td class='tabEditViewDF' colspan="3">
			<input type="radio" name="gcal_sync_opt" value="1" <?php if($gcal_sync_opt==1) echo 'checked';?>> <?php echo $current_module_strings['LBL_GCAL_SYNC_OPT1']; ?>
			<br>
			<input type="radio" name="gcal_sync_opt" value="2" <?php if($gcal_sync_opt==2) echo 'checked';?>> <?php echo $current_module_strings['LBL_GCAL_SYNC_OPT2']; ?>
			<br>
			<input type="radio" name="gcal_sync_opt" value="3" <?php if($gcal_sync_opt==3) echo 'checked';?>> <?php echo $current_module_strings['LBL_GCAL_SYNC_OPT3']; ?>
		</td>	
	</tr>
	<tr>
		<td class="dataLabel" valign="top">
			<?php echo $current_module_strings['LBL_GCAL_PRIORITY']; ?>
		</td>
		<td class='tabEditViewDF' colspan="3">
			<input type="radio" name="gcal_prioriy" value="1" <?php if($gcal_prioriy==1) echo 'checked';?>> <?php echo $current_module_strings['LBL_GCAL_PRIORITY1']; ?>
			<br>
			<input type="radio" name="gcal_prioriy" value="2" <?php if($gcal_prioriy==2) echo 'checked';?>> <?php echo $current_module_strings['LBL_GCAL_PRIORITY2']; ?>
		</td>	
	</tr>
	<tr>
		<td class="dataLabel">
			<?php echo $current_module_strings['LBL_GCAL_SYNC_MOD']; ?>
		</td>
		<td class='tabEditViewDF' colspan="3">
			<input type="radio" name="gcal_sync_mod" value="call"  <?php if($gcal_sync_mod=='call') echo 'checked';?>> <?php echo $current_module_strings['LBL_CALL']; ?>
			<br><input type="radio" name="gcal_sync_mod" value="meeting"  <?php if($gcal_sync_mod=='meeting') echo 'checked';?>> <?php echo $current_module_strings['LBL_MEETING']; ?>
		</td>		
	</tr>
	<tr>
		<td class="dataLabel">
			<?php echo $current_module_strings['LBL_GCAL_TIE_SLOT']; ?>
		</td>
		<td class='tabEditViewDF' colspan="3">
			<input type="text" name="gcal_time_slot" id="gcal_time_slot" value="<?php echo $gcal_time_slot?>">
		</td>		
	</tr>	
</table>

<?php

?>
