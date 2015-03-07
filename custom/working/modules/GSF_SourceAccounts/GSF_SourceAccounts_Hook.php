<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class GSF_SourceAccounts_Hook
{

    function update_accounts_total_projected_amount(&$bean, $event, $arguments) {
        
        if (!empty($bean->accounts_g2316ccounts_ida)) {
            require_once('modules/Accounts/Account.php');
            $account = new Account();
            $account->retrieve($bean->accounts_g2316ccounts_ida);
            //to be able to call Account's Hook
            $account->save();
        }
    }
}
?>