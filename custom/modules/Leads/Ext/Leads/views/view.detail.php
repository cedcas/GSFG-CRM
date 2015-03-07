<?php
/**
 * Custom view for Leads module
 * @author: Joed@ASI 20110504
 */
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class LeadsViewDetail extends ViewDetail {
    
    function LeadsViewDetail(){
        parent::ViewDetail();
    }
    
    function display() {
        //this will clear the detailview cache
        $this->th2 = new TemplateHandler();
        $this->th2->clearCache($this->module);
        
        //remove top buttons (Concert Lead, Manage Subscriptions)
        $this->dv->defs['templateMeta']['form']['buttons'] = array(
            'EDIT',
            'DUPLICATE',
            'DELETE',
            array (
                'customCode' => '<input title="{$APP.LBL_DUP_MERGE}" accessKey="M" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Step1\'; this.form.module.value=\'MergeRecords\';" type="submit" name="Merge" value="{$APP.LBL_DUP_MERGE}">'
            ),
        );
        
        //remove the detailview header
        //unset($this->dv->defs['templateMeta']['form']['headerTpl']);
        
        parent::display();
    }
}
?>
