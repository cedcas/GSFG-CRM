<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class GSF_Withdrawals_Hook
{
    function set_name(&$bean, $event, $arguments) {
        
        $bean->name = number_format($bean->gsf_withdrawal_amount, 2);
    }
    
    function update_accounts_total_withdrawal(&$bean, $event, $arguments) {
        
        if (!empty($bean->accounts_ge7aaccounts_ida)) {
            require_once('modules/Accounts/Account.php');
            $account = new Account();
            $account->retrieve($bean->accounts_ge7aaccounts_ida);
            //to be able to call Account's Hook
            $account->save();
        }
    }
}
?>