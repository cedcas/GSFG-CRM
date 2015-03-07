<?php
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
class Calendar2Controller extends SugarController {
	
    function action_AjaxSave()
    {
		$this->view = 'ajaxsave';
    }

    function action_AjaxAfterDrop()
    {
		$this->view = 'ajaxafterdrop';
    }

    function action_AjaxGetGR()
    {
		$this->view = 'ajaxgetgr';
    }

    function action_AjaxGetGRArr()
    {
		$this->view = 'ajaxgetgrarr';
    }

    function action_AjaxLoad()
    {
		$this->view = 'ajaxload';
    }

    function action_AjaxRemove()
    {
		$this->view = 'ajaxremove';
    }
    function action_AjaxSearchContacts()
    {
		$this->view = 'ajaxsearchcontacts';
    }
    function action_AjaxFlyCreate()
    {
		$this->view = 'ajaxflycreate';
    }
    
    function action_AjaxGetNote()
    {
		$this->view = 'ajaxgetnote';
    }
    
    function action_AjaxNoteSave()
    {
		$this->view = 'ajaxnotesave';
    }

}
?>