<?php
/*************************************
Script to generate Anniversary Date PDF
Autor: Joed@ASI
Date: 20110808
*************************************/

/* 20110816
 * Combined the paragraphs #2 & 3 into one paragraph; Added a 'Sincerely' prior to the signature at the bottom;
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
        $this->pdf->ezImage('custom/modules/Leads/goldstonefinancialgroup_logo.JPG', 0, 160, "none", "center");
        $this->pdf->ez['leftMargin'] = $left;
        
        $today = date("F j, Y");
        $header = "\n{$today}\n\n" .
        //"{$this->lead->first_name} {$this->lead->last_name}\n" .
	// Point to account owner
	// Requirement 2.2
	// author: KMJ 2011-11-17
	"{$this->account->account_owner_c}\n" .
	
        "{$this->lead->primary_address_street}\n" .
        "{$this->lead->primary_address_city}, {$this->lead->primary_address_state} {$this->lead->primary_address_postalcode}\n\n";

        $this->pdf->ezText($header, 11, array('justification'=>'left'));

        //$text = "Dear {$this->lead->first_name} {$this->lead->last_name},\n\n" .
	
	// Point to account owner
	// Requirement 2.2
	// author: KMJ 2011-11-17
	//$text = "Dear {$this->account->account_owner_c},\n\n" .

	// 3/8/2013 - CPC
	// Trim the account owner to eliminate space between name and comma
	$account_owner = TRIM($this->account->account_owner_c);
	$text = "Dear {$account_owner},\n\n" .
	
                "          As a valued client, we would like to let you know that it is time for your Annual Client Review.  It is important to set aside at least one hour each year to reevaluate your financial goals and ensure that your retirement needs are met.  This appointment is complimentary and will be used to review your account’s progress over the past year and to assure that your portfolio is properly positioned to maximize your investment opportunities.  The time will also be used to discuss any questions or concerns that you may have.  Please bring your Client Account Notebook and account statements with you for review and update.\n\n";

        $this->pdf->ezText($text, 11, array('justification'=>'left'));

        $text = "          Please allow us to fulfill our commitment to serve you.  Please call me at the number below  to choose a time for your Annual Client Review.  We appreciate you and the continued trust you place in our hands.  We look forward to seeing you soon.\n\n";

        $this->pdf->ezText($text, 11, array('justification'=>'left'));
	
	/*
        $text = "          We appreciate you and the continued trust you place in our hands.  We look forward to seeing you soon.\n\n\n\n";

        $this->pdf->ezText($text, 11, array('justification'=>'left'));
        */

        $text = "\nSincerely,\n\n";
        $this->pdf->ezText($text, 10, array('justification'=>'left'));
        
        $signature = "Danielle LeGare\n";

        $this->pdf->ezText($signature, 11, array('justification'=>'left', 'leading'=>'5'));

        $signature = "Client Relations Coordinator\n" .
                     "GoldStone Financial Group\n" .
                     "One Lincoln Centre\n" .
                     "18W140 Butterfield Rd., 14th floor Suite 1490\n" .
                     "Oakbrook Terrace, IL 60181\n" .
                     "Ph:(630) 613-7691\n" .
                     "Fax:(630)786-3357\n\n";

        $this->pdf->ezText($signature, 11, array('justification'=>'left'));
        
        
        $this->pdf->ezImage('custom/logos/PDF_footer.jpg', 0, 550, "none", "left");
        
        $footer = "One Lincoln Centre <b>.</b> 18W140 Butterfield Rd <b>.</b> 15th Floor <b>.</b> Oakbrook Terrace, IL 60181\n\n";

        $this->pdf->ezText($footer, 11, array('justification'=>'center'));
        
        $disclaimer= "Securities offered through Center Street Securities, Inc.(CSS), a registered Broker-Dealer & member FINRA & SIPC. " .
        	     "Investment Advisory Services offered through Brookstone Capital Management LLC (BCM), an SEC Registered Investment Advisor. " .
        	     "BCM is independent of CSS. GoldStone Financial Group in independent of CSS & BCM.";

        $this->pdf->ezText($disclaimer, 8, array('justification'=>'center'));
        
        
        //create the pdf and stream it to the page
        //$pdfcode = $this->pdf->output();
        //$this->pdf->ezStream();
        
        $this->createDocumentRecord();
    }

    function createDocumentRecord() {
        $today = date("Ymd");
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