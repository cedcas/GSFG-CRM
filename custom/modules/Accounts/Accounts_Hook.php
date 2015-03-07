<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class Accounts_Hook
{
    
    function set_repeat_client(&$bean, $event, $arguments) {
        
        if (!empty($bean->lead_id)) {
            
            $query = "
                SELECT
                    a.id as first_account
                FROM
                    accounts a
                WHERE
                    a.deleted = 0 AND
                    a.lead_id = '".$bean->lead_id."'
                ORDER BY
                    a.date_entered ASC
                LIMIT 1
            ";
                
            $result = $bean->db->query($query, true);
            $row = $bean->db->fetchByAssoc($result);
            
            $first_account = $row['first_account'];
            
            
            $query = "
                UPDATE
                    accounts a
                LEFT JOIN
                    accounts_cstm ac
                ON
                    a.id = ac.id_c
                SET
                    ac.accounts_repeat_client_c = IF(a.id = '$first_account', 0, 1)
                WHERE
                    a.lead_id = '".$bean->lead_id."'
            ";
            
            $bean->db->query($query, true);
            
        }
    }
    
    
    
    function update_account_values(&$bean, $event, $arguments) {
        $accounts_total_premium_c = (!empty($bean->accounts_total_premium_c))     ? $bean->accounts_total_premium_c : 0;
        $bonus_percentage         = (!empty($bean->bonus_percentage))             ? $bean->bonus_percentage : 0;
        $adjustment               = (!empty($bean->adjustment))                   ? $bean->adjustment : 0;
        
        $query1 = "
            SELECT
                SUM(COALESCE(gsf_contribution_amount,0)) as total
            FROM
                gsf_contributions
            WHERE
                deleted = 0 AND
                account_id = '".$bean->id."'
            ";
        $result1 = $bean->db->query($query1, true);
        $row1 = $bean->db->fetchByAssoc($result1);
        
        $query2 = "
            SELECT
                SUM(COALESCE(w.gsf_withdrawal_amount,0)) as total
            FROM
                accounts_gs_withdrawals_c acw
            LEFT JOIN
                gsf_withdrawals w
            ON
                w.id = acw.accounts_gce4bdrawals_idb AND
                w.deleted = 0
            WHERE
                acw.deleted = 0 AND
                acw.accounts_ge7aaccounts_ida = '".$bean->id."'
            ";
        $result2 = $bean->db->query($query2, true);
        $row2 = $bean->db->fetchByAssoc($result2);
        
        $query3 = "
            SELECT
                SUM(COALESCE(s.projected_amount,0)) as total
            FROM
                accounts_gsurceaccounts_c acs
            LEFT JOIN
                gsf_sourceaccounts s
            ON
                s.id = acs.accounts_gf0f6ccounts_idb AND
                s.deleted = 0
            WHERE
                acs.deleted = 0 AND
                acs.accounts_g2316ccounts_ida = '".$bean->id."'
            ";
        $result3 = $bean->db->query($query3, true);
        $row3 = $bean->db->fetchByAssoc($result3);
        
        $total_contributions = $row1['total'];
        $total_withdrawals = $row2['total'];
        $total_projected_amount  = $row3['total'];
        
        $bonus_and_premium = ($bonus_percentage / 100) * $accounts_total_premium_c;
        $current_value = ($total_contributions + ($accounts_total_premium_c + $bonus_and_premium) - $total_withdrawals + ($adjustment));
        
        $update_query = "
            UPDATE
                accounts a
            LEFT JOIN
                accounts_cstm ac
            ON
                a.id = ac.id_c
            SET
                a.current_value = '".$current_value."',
                a.total_contributions = '".$total_contributions."',
                a.total_withdrawals = '".$total_withdrawals."',
                a.adjustment = '".$adjustment."',
                ac.accounts_projected_amount_c = '".$total_projected_amount."'
            WHERE
                a.id = '".$bean->id."'
        ";
        $bean->db->query($update_query, true);
    }
    
    /*
     * Change leads.status = Client when Account is created for it
     * Requirement 2.6
     * @ KMJ 2011-11-13
     */
    function checkAccountSetLead(&$bean, $event, $arguments) {
       if(!empty($bean->lead_id)) {
            $SQL_update_lead = "
                UPDATE leads SET status = 'Client' WHERE id = '$bean->lead_id'
            ";
            $bean->db->query($SQL_update_lead, true);
       }
       
    }
}
?>