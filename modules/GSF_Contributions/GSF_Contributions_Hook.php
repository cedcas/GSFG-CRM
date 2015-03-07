<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class GSF_Contributions_Hook
{
    function set_name(&$bean, $event, $arguments) {
        
        $bean->name = number_format($bean->gsf_contribution_amount, 2);
    }
    
    function update_accounts_total_contributions(&$bean, $event, $arguments) {
        
        if (!empty($bean->account_id)) {
            require_once('modules/Accounts/Account.php');
            $account = new Account();
            $account->retrieve($bean->account_id);
            //to be able to call Account's Hook
            $account->save();
        }
    }
}
?>