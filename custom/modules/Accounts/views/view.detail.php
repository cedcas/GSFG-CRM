<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class AccountsViewDetail extends ViewDetail {
    
    function AccountsViewDetail(){
        parent::ViewDetail();
    }
    
    function display() {
        //this will clear the detailview cache
        $this->th2 = new TemplateHandler();
        $this->th2->clearCache($this->module);
        
        $this->dv->defs['templateMeta']['form']['buttons'][] = array(
            'customCode' => '<input title="{$MOD.LBL_ACCOUNT_SUMMARY}" class="button" onclick="this.form.return_module.value=\'Accounts\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\''.$this->bean->id.'\'; this.form.action.value=\'AccountsSummaryPDF\';" type="submit" name="AccountsSummaryPDF" value="{$MOD.LBL_ACCOUNT_SUMMARY}">',
        );
        
        parent::display();
    }
}
?>
