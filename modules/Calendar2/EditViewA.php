<?php

require_once('include/EditView/EditView2.php');

class EditViewA extends EditView{
   function display($showTitle = true, $ajaxSave = false) {
        global $mod_strings, $sugar_config, $app_strings, $app_list_strings, $theme, $current_user;


        if(isset($this->defs['templateMeta']['javascript'])) {
           if(is_array($this->defs['templateMeta']['javascript'])) {
           	 $this->th->ss->assign('externalJSFile', 'modules/' . $this->module . '/metadata/editvewdefs.js');
           } else {
             $this->th->ss->assign('scriptBlocks', $this->defs['templateMeta']['javascript']);
           }
        }
        
        $this->defs['templateMeta']['form']['buttons'] = array('1');
      
      
        

        $this->th->ss->assign('id', $this->fieldDefs['id']['value']);
        $this->th->ss->assign('offset', $this->offset + 1);
        $this->th->ss->assign('APP', $app_strings);
        $this->th->ss->assign('MOD', $mod_strings);
        $this->th->ss->assign('fields', $this->fieldDefs);
        //_pp($this->fieldDefs);
        $this->th->ss->assign('sectionPanels', $this->sectionPanels);
        $this->th->ss->assign('returnModule', $this->returnModule);
        $this->th->ss->assign('returnAction', $this->returnAction);
        $this->th->ss->assign('returnId', $this->returnId);
        $this->th->ss->assign('isDuplicate', $this->isDuplicate);
        $this->th->ss->assign('def', $this->defs);
        $this->th->ss->assign('maxColumns', isset($this->defs['templateMeta']['maxColumns']) ? $this->defs['templateMeta']['maxColumns'] : 2);
        $this->th->ss->assign('module', $this->module);
        $this->th->ss->assign('headerTpl', isset($this->defs['templateMeta']['form']['headerTpl']) ? $this->defs['templateMeta']['form']['headerTpl'] : 'modules/Calendar2/note_templates/header.tpl');
        $this->th->ss->assign('footerTpl', isset($this->defs['templateMeta']['form']['footerTpl']) ? $this->defs['templateMeta']['form']['footerTpl'] : 'modules/Calendar2/note_templates/footer.tpl');
        $this->th->ss->assign('current_user', $current_user);
        $this->th->ss->assign('bean', $this->focus);
        $this->th->ss->assign('isAuditEnabled', $this->focus->is_AuditEnabled());
        $this->th->ss->assign('gridline',$current_user->getPreference('gridline') == 'on' ? '1' : '0');

        global $js_custom_version;
        global $sugar_version;
        $this->th->ss->assign('SUGAR_VERSION', $sugar_version);
        $this->th->ss->assign('JS_CUSTOM_VERSION', $js_custom_version);

        //this is used for multiple forms on one page
        $form_id = "cal2note_form";
        $form_name = "cal2note_form";
        $GLOBALS['log']->debug('EditviewA ajaxSave='.$ajaxSave);
        if($ajaxSave){
        	$form_id = 'form_'.$this->view .'_'.$this->module;
        	$form_name = $form_id;
        	$this->view = $form_name;
        	//$this->defs['templateMeta']['form']['buttons'] = array();
        	//$this->defs['templateMeta']['form']['buttons']['ajax_save'] = array('id' => 'AjaxSave', 'customCode'=>'<input type="button" class="button" value="Save" onclick="this.form.action.value=\'AjaxFormSave\';return saveForm(\''.$form_name.'\', \'multiedit_form_{$module}\', \'Saving {$module}...\');"/>');
        }
        
        
		$form_name = $form_name == "QuickCreate" ? "QuickCreate_{$this->module}" : $form_name;        
        $form_id = $form_id == "QuickCreate" ? "QuickCreate_{$this->module}" : $form_id;
        
        if(isset($this->defs['templateMeta']['preForm'])) {
          $this->th->ss->assign('preForm', $this->defs['templateMeta']['preForm']);
        } //if
        if(isset($this->defs['templateMeta']['form']['closeFormBeforeCustomButtons'])) {
          $this->th->ss->assign('closeFormBeforeCustomButtons', $this->defs['templateMeta']['form']['closeFormBeforeCustomButtons']);
        }
        if(isset($this->defs['templateMeta']['form']['enctype'])) {
          $this->th->ss->assign('enctype', 'enctype="'.$this->defs['templateMeta']['form']['enctype'].'"');
        }
        $this->th->ss->assign('showDetailData', $this->showDetailData);
        $this->th->ss->assign('form_id', $form_id);
        $this->th->ss->assign('form_name', $form_name);
  		$this->th->ss->assign('set_focus_block', get_set_focus_js());
        $this->th->ss->assign('form', isset($this->defs['templateMeta']['form']) ? $this->defs['templateMeta']['form'] : null);
        $this->th->ss->assign('includes', isset($this->defs['templateMeta']['includes']) ? $this->defs['templateMeta']['includes'] : null);
		$this->th->ss->assign('view', $this->view);

        $admin = new Administration();
        $admin->retrieveSettings();
        if(isset($admin->settings['portal_on']) && $admin->settings['portal_on']) {
           $this->th->ss->assign("PORTAL_ENABLED", true);
        } else {
           $this->th->ss->assign("PORTABL_ENABLED", false);
        }


        //Calculate time & date formatting (may need to calculate this depending on a setting)
        global $timedate;
        $this->th->ss->assign('CALENDAR_DATEFORMAT', $timedate->get_cal_date_format());
        $this->th->ss->assign('USER_DATEFORMAT', $timedate->get_user_date_format());
        $time_format = $timedate->get_user_time_format();
        $this->th->ss->assign('TIME_FORMAT', $time_format);

        $date_format = $timedate->get_cal_date_format();
        $time_separator = ":";
        if(preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
           $time_separator = $match[1];
        }

        // Create Smarty variables for the Calendar picker widget
        $t23 = strpos($time_format, '23') !== false ? '%H' : '%I';
        if(!isset($match[2]) || $match[2] == '') {
          $this->th->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M");
        } else {
          $pm = $match[2] == "pm" ? "%P" : "%p";
          $this->th->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M" . $pm);
        }

        $this->th->ss->assign('TIME_SEPARATOR', $time_separator);

		$seps = get_number_seperators();
		$this->th->ss->assign('NUM_GRP_SEP', $seps[0]);
		$this->th->ss->assign('DEC_SEP', $seps[1]);
        $this->th->ss->assign('SHOW_VCR_CONTROL', $this->showVCRControl);

        //$str='';

        $str = $this->showTitle($showTitle);

        //Use the output filter to trim the whitespace
        $this->th->ss->load_filter('output', 'trimwhitespace');
        $str .= $this->th->displayTemplate($this->module, $this->view, $this->tpl, $ajaxSave, $this->defs);
		return $str;
    }
}

?>
