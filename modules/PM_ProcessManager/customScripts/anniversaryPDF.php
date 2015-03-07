<?php
/* Script to generate Anniversary Date PDF
 * @author Joed@asi
 * 20110509
 *
 */

class anniversaryPDF extends SugarBean {

    function anniversaryPDF($focusObjectId, $focusObjectType) {
        require_once('custom/modules/Accounts/AnniversaryDatePDF.php');
        
        $adpdf = new AnniversaryDatePDF($focusObjectId);
        $adpdf->generatePDF();
        
        
        //20110808 - not used anymore since the fields to update are moved to Accounts module
        /*
        $db = DBManagerFactory::getInstance();
         
        //update the Lead's "Last Anniversary Processed" (account_last_anniv_processed)
        //so that it will process the next of his Account Anniversary Date
        $query = "
            UPDATE
                leads l
            SET l.account_last_anniv_processed = (
                    SELECT
                        ac.accounts_anniversary_date_c AS anniv
                    FROM
                        accounts a
                    LEFT JOIN
                        accounts_cstm ac
                    ON
                        a.id = ac.id_c
                    WHERE
                        a.lead_id = '{$focusObjectId}' AND
                        (ac.accounts_anniversary_date_c != '' OR ac.accounts_anniversary_date_c != NULL) AND
                        date_format(ac.accounts_anniversary_date_c, '%m-%d') > date_format(date(now()), '%m-%d') AND
                        date_format(ac.accounts_anniversary_date_c, '%m-%d') != COALESCE(date_format(l.account_last_anniv_processed, '%m-%d'), '0') AND
                        a.deleted = 0
                    ORDER BY
                        date_format(date(ac.accounts_anniversary_date_c), '%m-%d') ASC
                    LIMIT 1
                )
            WHERE
                l.id = '{$focusObjectId}'
            LIMIT 1
            ";
        
        $db->query($query, true);
        */
        
    } 
}
?>
