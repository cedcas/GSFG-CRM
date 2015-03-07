<?php
/**
 * Custom view for GSF_Seminars module
 * @author: Joed@ASI 20110511
 */
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class GSF_SeminarsViewDetail extends ViewDetail {
    
    function GSF_SeminarsViewDetail(){
        parent::ViewDetail();
    }
    
    function display() {
        //this will clear the detailview cache
        $this->th2 = new TemplateHandler();
        $this->th2->clearCache($this->module);
        
        $this->dv->defs['templateMeta']['form']['buttons'][] = array(
            'customCode' => '<input title="{$MOD.LBL_RUN_CLUSTER_REPORT}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\''.$this->bean->id.'\'; this.form.action.value=\'ClusterReportPDF\';" type="submit" name="Report" value="{$MOD.LBL_RUN_CLUSTER_REPORT}">',
        );
        
        parent::display();
    }
}
?>
