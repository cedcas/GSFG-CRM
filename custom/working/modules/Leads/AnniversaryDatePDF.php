<?php
/*************************************
Script to generate Anniversary Date PDF
Autor: Joed@ASI
Date: 20110429
*************************************/

/* 20110808
 * Originally in the Lead module but transferred in the Accounts module
 * see /custom/modules/Accounts/AnniversaryDatePDF.php
 */
 
/* 20110816
 * Combined the paragraphs #2 & 3 into one paragraph; Added a 'Sincerely' prior to the signature at the bottom;
 */

die("Invalid action call.");



require_once('include/pdf/class.ezpdf.php');
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
    private $lead;

    public function AnniversaryDatePDF($lead_id) {
        $this->pdf = new Cezpdf();
        $this->lead = new Lead();
        $this->lead->retrieve($lead_id);
    }

    function generatePDF() {
        $this->pdf->selectFont('././././include/fonts/Helvetica.afm');

        $this->pdf->ezImage('custom/modules/Leads/goldstonefinancialgroup_for_PDF.JPG', 0, 250, "none", "center");

        $today = date("F j, Y");
        $header = "\n{$today}\n\n" .
        "{$this->lead->first_name} {$this->lead->last_name}\n" .
        "{$this->lead->primary_address_street}\n" .
        "{$this->lead->primary_address_city}, {$this->lead->primary_address_state} {$this->lead->primary_address_postalcode}\n\n";

        $this->pdf->ezText($header, 11, array('justification'=>'left'));

        $text = "Dear {$this->lead->first_name} {$this->lead->last_name},\n\n" .
                "          As a valued client, we would like to let you know that it is time for your Annual Client Review.  It is important to set aside at least one hour each year to reevaluate your financial goals and ensure that your retirement needs are met.  This appointment is complimentary and will be used to review your account’s progress over the past year and to assure that your portfolio is properly positioned to maximize your investment opportunities.  The time will also be used to discuss any questions or concerns that you may have.  Please bring your Client Account Notebook and account statements with you for review and update.\n\n";

        $this->pdf->ezText($text, 11, array('justification'=>'left'));

        $text = "          Please allow us to fulfill our commitment to serve you.  Please call me at the number below  to choose a time for your Annual Client Review.  We appreciate you and the continued trust you place in our hands.  We look forward to seeing you soon.\n\n\n\n";

        $this->pdf->ezText($text, 11, array('justification'=>'left'));
	
	/*
        $text = "          We appreciate you and the continued trust you place in our hands.  We look forward to seeing you soon.\n\n\n\n";

        $this->pdf->ezText($text, 11, array('justification'=>'left'));
        */

        $text = "\nSincerely,\n\n";
        $this->pdf->ezText($text, 10, array('justification'=>'left'));
        
        $signature = "Rachel Gilmer\n";

        $this->pdf->ezText($signature, 11, array('justification'=>'left', 'leading'=>'5'));

        $signature = "Director of Operations\n" .
                     "GoldStone Financial Group\n" .
                     "One Lincoln Centre\n" .
                     "18W140 Butterfield Rd., 15th floor\n" .
                     "Oakbrook Terrace, IL 60181\n" .
                     "Ph:(630) 613-7691\n" .
                     "Fax:(630)786-3357\n\n";

        $this->pdf->ezText($signature, 11, array('justification'=>'left'));
        
        
        $this->pdf->ezImage('custom/logos/PDF_footer.jpg', 0, 550, "none", "left");
        
        $footer = "\n\n\nOne Lincoln Centre <b>.</b> 18W140 Butterfield Rd <b>.</b> 15th Floor <b>.</b> Oakbrook Terrace, IL 60181";

        $this->pdf->ezText($footer, 11, array('justification'=>'center'));

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