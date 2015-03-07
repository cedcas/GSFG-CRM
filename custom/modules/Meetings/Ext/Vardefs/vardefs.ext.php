<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

 // created: 2012-03-02 17:33:47

 

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
$dictionary['Meeting']['fields']['resources'] = array (
  	'name' => 'resources',
    'type' => 'link',
    'relationship' => 'meetings_resources',
    'source'=>'non-db',
	'vname'=>'LBL_RESOURCE',
	);
$dictionary['Meeting']['fields']['cal2_category_c'] = array (
      'required' => '0',
      'name' => 'cal2_category_c',
      'vname' => 'LBL_CATEGORY',
      'type' => 'enum',
      'massupdate' => '0',
      'default' => 'First',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => 100,
      'options' => 'category_list',
      'studio' => 'visible',
      'dependency' => NULL,
      'id' => 'Meetingscal2_category_c',
    );
$dictionary['Meeting']['fields']['cal2_options_c'] = array (
      'required' => '0',
      'name' => 'cal2_options_c',
      'vname' => 'LBL_PRIVATE',
      'type' => 'bool',
      'massupdate' => '0',
      'default' => '0',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'Meetingscal2_options_c',
    );
$dictionary['Meeting']['fields']['cal2_whole_day_c'] = array (
      'required' => '0',
      'name' => 'cal2_whole_day_c',
      'vname' => 'LBL_WHOLE_DAY',
      'type' => 'bool',
      'massupdate' => '0',
      'default' => '0',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'Meetingscal2_whole_day_c',
    );
$dictionary['Meeting']['fields']['cal2_meeting_id_c'] = array (
      'required' => '0',
      'name' => 'cal2_meeting_id_c',
      'vname' => 'LBL_LIST_RELATED_TO',
      'type' => 'id',
      'massupdate' => '0',
      'default' => NULL,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '36',
      'id' => 'Meetingscal2_meeting_id_c',
    );
$dictionary['Meeting']['fields']['cal2_recur_id_c'] = array (
      'required' => '0',
      'source' => 'non-db',
      'name' => 'cal2_recur_id_c',
      'vname' => 'LBL_REC_ID',
      'type' => 'relate',
      'massupdate' => '0',
      'default' => NULL,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 1,
      'len' => '255',
      'id_name' => 'cal2_meeting_id_c',
      'ext2' => 'Meetings',
      'module' => 'Meetings',
      'rname' => 'name',
      'quicksearch' => 'enabled',
      'studio' => 'visible',
      'id' => 'Meetingscal2_recur_id_c',
    );
$dictionary['Meeting']['fields']['cal2_repeat_type_c'] = array (
      'required' => '0',
      'name' => 'cal2_repeat_type_c',
      'vname' => 'LBL_REPEAT_TYPE',
      'type' => 'varchar',
      'massupdate' => '0',
      'default' => NULL,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 1,
      'len' => '25',
      'id' => 'Meetingscal2_repeat_type_c',
    );
$dictionary['Meeting']['fields']['cal2_repeat_interval_c'] = array (
      'required' => '0',
      'name' => 'cal2_repeat_interval_c',
      'vname' => 'LBL_REPEAT_INTERVAL',
      'type' => 'int',
      'massupdate' => '0',
      'default' => NULL,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 1,
      'len' => '11',
      'disable_num_format' => NULL,
      'id' => 'Meetingscal2_repeat_interval_c',
    );
$dictionary['Meeting']['fields']['cal2_repeat_days_c'] = array (
      'required' => '0',
      'name' => 'cal2_repeat_days_c',
      'vname' => 'LBL_REPEAT_DAYS',
      'type' => 'varchar',
      'massupdate' => '0',
      'default' => NULL,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 1,
      'len' => '25',
      'id' => 'Meetingscal2_repeat_days_c',
    );
$dictionary['Meeting']['fields']['cal2_repeat_end_date_c'] = array (
      'required' => '0',
      'name' => 'cal2_repeat_end_date_c',
      'vname' => 'LBL_REPEAT_END_DATE',
      'type' => 'date',
      'massupdate' => '0',
      'default' => NULL,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 1,
      'id' => 'Meetingscal2_repeat_end_date_c',
    );
$dictionary["Meeting"]["fields"]["cal_notes_meetings"] = array (
  'name' => 'cal_notes_meetings',
  'type' => 'link',
  'relationship' => 'cal_notes_meetings',
  'source' => 'non-db',
  'vname' => 'LBL_CAL_NOTES_MEETINGS_FROM_CAL_NOTES_TITLE',
);
$dictionary["Meeting"]["fields"]["cal_notes_meetings_name"] = array (
  'name' => 'cal_notes_meetings_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CAL_NOTES_MEETINGS_FROM_CAL_NOTES_TITLE',
  'save' => true,
  'id_name' => 'cal_notes_76a4l_notes_ida',
  'link' => 'cal_notes_meetings',
  'table' => 'cal_notes',
  'module' => 'cal_Notes',
  'rname' => 'name',
);
$dictionary["Meeting"]["fields"]["cal_notes_76a4l_notes_ida"] = array (
  'name' => 'cal_notes_76a4l_notes_ida',
  'type' => 'link',
  'relationship' => 'cal_notes_meetings',
  'source' => 'non-db',
  'reportable' => false,
  'vname' => 'LBL_CAL_NOTES_MEETINGS_FROM_CAL_NOTES_TITLE',
);



// google calendar fields
$dictionary['Meeting']['fields']['reminder_checked'] = array (
      'required' => '0',
      'name' => 'reminder_checked',
      'vname' => 'LBL_REMINDER_CHECKED',
      'type' => 'bool',
      'massupdate' => '0',
      'default' => '0',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'reminder_checked',
    );
$dictionary['Meeting']['fields']['google_response_c'] = array (
      'required' => '0',
      'name' => 'google_response_c',
      'vname' => 'LBL_GOOGLE_RESPONSE',
      'type' => 'text',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'id' => 'google_response_c',
    );
$dictionary['Meeting']['fields']['old_id_c'] = array (
      'required' => '0',
      'name' => 'old_id_c',
      'vname' => 'LBL_OLD_ID',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'old_id_c',
    );    
$dictionary['Meeting']['fields']['old_published_c'] = array (
      'required' => '0',
      'name' => 'old_published_c',
      'vname' => 'LBL_OLD_PUBLISHED',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'old_published_c',
    );
$dictionary['Meeting']['fields']['old_updated_c'] = array (
      'required' => '0',
      'name' => 'old_updated_c',
      'vname' => 'LBL_OLD_UPDATED',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'old_updated_c',
    );
$dictionary['Meeting']['fields']['old_link_alt_c'] = array (
      'required' => '0',
      'name' => 'old_link_alt_c',
      'vname' => 'LBL_OLD_LINK_ALT',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'old_link_alt_c',
    );
$dictionary['Meeting']['fields']['old_link_self_c'] = array (
      'required' => '0',
      'name' => 'old_link_self_c',
      'vname' => 'LBL_OLD_LINK_SELF',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'old_link_self_c',
    );    
$dictionary['Meeting']['fields']['old_link_edit_c'] = array (
      'required' => '0',
      'name' => 'old_link_edit_c',
      'vname' => 'LBL_OLD_LINK_EDIT',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'old_link_edit_c',
    );
$dictionary['Meeting']['fields']['old_link_edit_c'] = array (
      'required' => '0',
      'name' => 'old_link_edit_c',
      'vname' => 'LBL_OLD_LINK_EDIT',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'old_link_edit_c',
    );
$dictionary['Meeting']['fields']['g_published_c'] = array (
      'required' => '0',
      'name' => 'g_published_c',
      'vname' => 'LBL_G_PUBLISHED',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'g_published_c',
    );
$dictionary['Meeting']['fields']['g_updated_c'] = array (
      'required' => '0',
      'name' => 'g_updated_c',
      'vname' => 'LBL_G_PUBLISHED',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'len' => '255',
      'id' => 'g_updated_c',
    );
$dictionary['Meeting']['fields']['google_mresponse_c'] = array (
      'required' => '0',
      'name' => 'google_mresponse_c',
      'vname' => 'LBL_google_mRESPONSE',
      'type' => 'text',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'id' => 'google_mresponse_c',
    ); 
$dictionary['Meeting']['fields']['google_mresponse_c'] = array (
      'required' => '0',
      'name' => 'google_mresponse_c',
      'vname' => 'LBL_google_mRESPONSE',
      'type' => 'text',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'id' => 'google_mresponse_c',
    );
$dictionary['Meeting']['fields']['location'] = array (
      'required' => '0',
      'name' => 'location',
      'vname' => 'LBL_LOCATION',
      'type' => 'varchar',
      'len' => '255',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => 0,
      'id' => 'location',
    );    
$dictionary['Meeting']['fields']['old_email_c'] = array (
      'required' => '0',
      'name' => 'old_email_c',
      'vname' => 'LBL_OLD_EMAIL',
      'type' => 'varchar',
      'massupdate' => '0',
      'isnull' => 'true',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'len' => '255',
      'reportable' => 0,
      'id' => 'old_email_c',
    );    

?>