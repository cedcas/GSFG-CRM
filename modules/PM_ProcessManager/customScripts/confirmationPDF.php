<?php
/* Script to generate Seminar Confirmation PDF
 * @author Joed@asi
 * 20110429
 *
 */

class confirmationPDF extends SugarBean {

    function confirmationPDF($focusObjectId, $focusObjectType) {
        require_once('custom/modules/Leads/SeminarConfirmationPDF.php');
        $smpdf = new SeminarConfirmationPDF($focusObjectId);
        $smpdf->generatePDF();
    } 
}
?>
