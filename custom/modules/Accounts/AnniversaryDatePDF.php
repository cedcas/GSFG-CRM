<?php
/*************************************
Script to generate Anniversary Date PDF
Autor: Joed@ASI
Date: 20110808
*************************************/

/* 20110816
 * Combined the paragraphs #2 & 3 into one paragraph; Added a 'Sincerely' prior to the signature at the bottom;
 *
 * 2011-11-17 KMJ
 * Point to account owner; Requirement 2.2
 *
 * 3/8/2013 CPC
 * Trim the account owner to eliminate space between name and comma
 * 
 * 3/6/2015 CPC
 * Modified PDF based on Danielle's request;
 * - Removed the top logo (due to letterhead printing)
 * - Formatting and proper spacing
 * - Terri Weston's signature (image)
 * - Removed the footer / disclosure (due to letterhead printing)
 * - Deletion of company address and contact information
 *
 * 3/9/2015 CPC
 * Modified PDF based on Danielle's request;
 * - Put back Terri's title
 * - Made the Media Logo much lower
 * - Added TIME in the Anniversary PDF filename, i.e. "Anniversarry PDF 20150309124810.pdf", from just "Anniversarry PDF 20150309.pdf"
 * - Fixed the weird character that came from copying directly from Word document;
 *
 * 8/10/2015 CPC
 * Commented out the media logo at the bottom of the PDF @ line 104, as requested by Analhi & Rachel;
 *
 * 9/8/2015 CPC
 * Requested by Analhi & Amy:
 * 	Uncommented out the media logo at the bottom of the PDF;
 *	Replaced Terri's name & signature with Jake Kuper's;
 */

require_once('include/pdf/class.ezpdf.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Documents/Document.php');
require_once('modules/DocumentRevisions/DocumentRevision.php');


if ( (isset($_REQUEST['record']) && !empty($_REQUEST['record'])) &&
     (isset($_REQUEST['action']) && $_REQUEST['action'] == "AnniversaryDatePDF") ) {
    
    $adpdf = new AnniversaryDatePDF($_REQUEST['record']);
    $adpdf->generatePDF();
}

class AnniversaryDatePDF {

    private $pdf;
    private $account;
    private $lead;

    public function AnniversaryDatePDF($account_id) {
        $this->pdf = new Cezpdf();
        $this->account = new Account();
        $this->account->retrieve($account_id);
        
        $this->lead = new Lead();
        $this->lead->retrieve($this->account->lead_id);
    }

    function generatePDF() {
        $this->pdf->selectFont('././././include/fonts/Helvetica.afm');    
        $left = $this->pdf->ez['leftMargin'];
        $this->pdf->ez['leftMargin'] = -3;
        $this->pdf->ez['leftMargin'] = $left;
        
        $today = date("F j, Y");
        
        $header = "\n\n\n\n\n\n\n\n\n{$today}\n\n\n\n" .
		"{$this->account->account_owner_c}\n" .
	        "{$this->lead->primary_address_street}\n" .
	        "{$this->lead->primary_address_city} {$this->lead->primary_address_state} {$this->lead->primary_address_postalcode}\n\n";
        $this->pdf->ezText($header, 11, array('justification'=>'left'));

	$account_owner = TRIM($this->account->account_owner_c);
	$text = "Dear {$account_owner},\n\n" .
	
                "As a valued client, we would like to let you know that it is time for your Annual Client Review.  It is important to set aside at least one hour each year to reevaluate your financial goals and ensure that your retirement needs are being met.\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));

        $text = "This appointment is complimentary and will be used to review your account's progress over the past year and to assure that your portfolio is properly positioned to maximize your investment opportunities.  The time will also be used to discuss any questions or concerns that you may have.  Please bring all current statements from your financial portfolio(s) with you for review.\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));

        $text = "Please allow us to fulfill our commitment to serve you; call (630) 620-9300 to choose a time for your Annual Client Review. We appreciate you and the continued trust you place in our hands.  We look forward to seeing you soon.\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));
	

        $text = "\nSincerely,\n\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));
	
	// Jake's signature
	$this->pdf->ezImage('custom/logos/jakekuper_sig.jpg', 0, 140, "none", "left");
	
	// Extra spaces
	$signature = "Jake Kuper\n\n" .
        	     "Client Relations Coordinator\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
        $this->pdf->ezText($signature, 11, array('justification'=>'left', 'leading'=>'5'));

	// Media logos
        $this->pdf->ezImage('custom/logos/media_logos.jpg', 0, 500, "none", "left");

        
        $this->createDocumentRecord();
    }

    function createDocumentRecord() {
        $today = date("Ymdhis");
        $filename = "Anniversary Date PDF " . $today . ".pdf";
        $document = new Document();
        $document->document_name = $filename;
        $document->parent_id = $this->lead->id;
        $document->accounts_d13e2ccounts_ida = $this->account->id;
        $document->save();

        $docrevision = new DocumentRevision();
        $docrevision->change_log = "Document Created";
        $docrevision->document_id = $document->id;
        $docrevision->filename = $filename;
        $docrevision->file_ext = "pdf";
        $docrevision->file_mime_type = "application/pdf";
        $docrevision->revision = "1";
        $docrevision->save();

        $document->document_revision_id = $docrevision->id;
        $document->save();
        $this->saveDocumentFile($docrevision->id);
    }
    
    function saveDocumentFile($filename) {
        global $sugar_config;
        // START - save the file
        $dir = '././././' . $sugar_config['upload_dir'] . '/';
        if (!file_exists($dir)){
            mkdir($dir, 0777);
        }
        $filename = $dir. $filename;
        $fp = fopen($filename, 'w');
        fwrite($fp, $this->pdf->output());
        fclose($fp);
        // END - save the file
    }
    
}
?>
