<?php
/*********************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by KINAMU Business Solutions AG. All rights ar (c) 2010 by KINAMU Business
 * Solutions AG.
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of KINAMU Business Solutions AG
 *
 * You can contact KINAMU Business Solutions AG at Am Concordepark 2/F12
 * A-2320 Schwechat or via email at office@kinamu.com
 *
 ********************************************************************************/
$viewdefs['KReports']['DetailView'] = array(
'templateMeta' => array('form' => array('buttons' => array(),
                                        'hidden' => '<input type="hidden" name="to_pdf" id="to_pdf" value=""><input type="hidden" name="dynamicoptions" id="dynamicoptions" value="{$dynamicoptions}">',
            							'footerTpl'=>'modules/KReports/tpls/DetailViewFooter.tpl'),
                        'widths' => array()
                       ),
'panels' => array()
);
