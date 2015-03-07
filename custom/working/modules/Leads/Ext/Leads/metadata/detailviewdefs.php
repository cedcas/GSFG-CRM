<?php
$viewdefs ['Leads'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'" name="convert" value="{$MOD.LBL_CONVERTLEAD}">',
          ),
          4 => 
          array (
            'customCode' => '<input title="{$APP.LBL_DUP_MERGE}" accessKey="M" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Step1\'; this.form.module.value=\'MergeRecords\';" type="submit" name="Merge" value="{$APP.LBL_DUP_MERGE}">',
          ),
          5 => 
          array (
            'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">',
          ),
        ),
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Leads/Lead.js',
        ),
      ),
      'useTabs' => false,
    ),
    'panels' => 
    array (
      'LBL_CONTACT_INFORMATION' => 
      array (
        0 => 
        array (
          'date_entered',
          'created_by_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'displayParams' => '',
          ),
          1 => 
          array (
            'name' => 'last_name',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'spouse_first_name_c',
            'label' => 'LBL_SPOUSE_FIRST_NAME',
          ),
          1 => 
          array (
            'name' => 'spouse_last_name_c',
            'label' => 'LBL_SPOUSE_LAST_NAME',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'phone_work',
            'comment' => 'Work phone number of the contact',
            'label' => 'LBL_OFFICE_PHONE',
          ),
          1 => 
          array (
            'name' => 'phone_home',
            'comment' => 'Home phone number of the contact',
            'label' => 'LBL_HOME_PHONE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'phone_mobile',
            'comment' => 'Mobile phone number of the contact',
            'label' => 'LBL_MOBILE_PHONE',
          ),
          1 => 
          array (
            'name' => 'phone_other',
            'comment' => 'Other phone number for the contact',
            'label' => 'LBL_OTHER_PHONE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'alt',
              'copy' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'email1',
            'studio' => 
            array (
              'editField' => true,
            ),
            'label' => 'LBL_EMAIL_ADDRESS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),

      ),
      'LBL_PANEL_ADVANCED' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'social_security_number_c',
            'label' => 'LBL_SOCIAL_SECURITY_NUMBER',
          ),
          1 => 
          array (
            'name' => 'spouse_social_security_numbe_c',
            'label' => 'LBL_SPOUSE_SOCIAL_SECURITY_NUMBE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'birthdate',
            'comment' => 'The birthdate of the contact',
            'label' => 'LBL_BIRTHDATE',
          ),
          1 => 
          array (
            'name' => 'spouse_date_of_birth_c',
            'label' => 'LBL_SPOUSE_DATE_OF_BIRTH',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'age_c',
            'label' => 'LBL_AGE',
          ),
          1 => 
          array (
            'name' => 'spouse_age_c',
            'label' => 'LBL_SPOUSE_AGE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'drivers_license_number_c',
            'label' => 'LBL_DRIVERS_LICENSE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'spouse_drivers_license_numbe_c',
            'label' => 'LBL_SPOUSE_DRIVERS_LICENSE_NUMBE',
          ),
        ),
      ),
      'lbl_detailview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'gsf_seminardetails_leads_name',
            /*'name' => 'seminar_venue_c',*/
            'studio' => 'visible',
            'label' => 'LBL_SEMINAR_VENUE',
          ),
          1 => 
          array (
            'name' => 'seminar_venue_name_c',
            'label' => 'LBL_SEMINAR_VENUE_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'seminar_date_c',
            'label' => 'LBL_SEMINAR_DATE',
          ),
          1 => 
          array (
            'name' => 'seminar_time_c',
            'label' => 'LBL_SEMINAR_TIME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'seminar_address_c',
            'label' => 'LBL_SEMINAR_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'seminar_city_c',
            'label' => 'LBL_SEMINAR_CITY',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'seminar_state_c',
            'label' => 'LBL_SEMINAR_STATE',
          ),
          1 => 
          array (
            'name' => 'seminar_postalcode_c',
            'label' => 'LBL_SEMINAR_POSTALCODE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'seminar_capacity',
            'label' => 'LBL_SEMINAR_CAPACITY',
          ),
          1 => 
          array (
            'name' => 'seminar_days_left_c',
            'label' => 'LBL_SEMINAR_DAYS_LEFT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'seminar_attended_c',
            'label' => 'LBL_SEMINAR_ATTENDED',
          ),
          1 => 
          array (
            'name' => 'attendee_id',
            'label' => 'LBL_ATTENDEE_ID',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'meeting_attended_c',
            'label' => 'LBL_MEETING_ATTENDED',
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
            'displayParams' => array(
                'initial_filter' => '&filter_role=filter_role',
            ),
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'comment' => 'Status of the lead',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'account_upcoming_anniv',
            'label' => 'LBL_ACCOUNT_UPCOMING_ANNIV',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'lead_source',
            'comment' => 'Lead source (ex: Web, print)',
            'label' => 'LBL_LEAD_SOURCE',
          ),
          1 => 
          array (
            'name' => 'account_days_left_to_anniv',
            'label' => 'LBL_ACCOUNT_DAYS_LEFT_TO_ANNIV',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'meeting_date_c',
            'label' => 'LBL_MEETING_DATE',
          ),
          1 => 
          array (
            'name' => 'account_last_anniv_processed',
            'label' => 'LBL_ACCOUNT_LAST_ANNIV_PROCESSED',
          ),
        ),
      ),
    ),
  ),
);
?>