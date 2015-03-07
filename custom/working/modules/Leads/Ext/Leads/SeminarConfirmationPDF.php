<?php
/*************************************
Script to generate Seminar Confirmation PDF
Autor: Joed@ASI
Date: 20110429
*************************************/

require_once('include/pdf/class.ezpdf.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Documents/Document.php');
require_once('modules/DocumentRevisions/DocumentRevision.php');


if ( (isset($_REQUEST['record']) && !empty($_REQUEST['record'])) &&
     (isset($_REQUEST['action']) && $_REQUEST['action'] == "SeminarConfirmationPDF") ) {
    
    $smpdf = new SeminarConfirmationPDF($_REQUEST['record']);
    $smpdf->generatePDF();
}

class SeminarConfirmationPDF {

    private $pdf;
    private $lead;

    public function SeminarConfirmationPDF($lead_id) {
        $this->pdf = new Cezpdf(array(0,0,598,842));
        $this->lead = new Lead();
        $this->lead->retrieve($lead_id);
    }
    
    function generatePDF() {
        $all = $this->pdf->openObject();
        $this->pdf->saveState();
        $this->pdf->setStrokeColor(0,0,0,1);
        //$this->pdf->line(20,40,578,40);
        //$this->pdf->line(20,822,578,822);
        $this->pdf->restoreState();
        $this->pdf->closeObject();
        $this->pdf->addObject($all,'all');
        
        $this->pdf->selectFont('././././include/fonts/Helvetica.afm');
        
        $this->pdf->ezImage('custom/modules/Leads/goldstonefinancialgroup_for_PDF.JPG', 0, 250, "none", "center");

        $today = date("F j, Y");
        $header = "$today\n\n" .
            "{$this->lead->first_name} {$this->lead->last_name}\n" .
            "{$this->lead->primary_address_street}\n" .
            "{$this->lead->primary_address_city}, {$this->lead->primary_address_state} {$this->lead->primary_address_postalcode}\n\n";

        $this->pdf->ezText($header, 10, array('justification'=>'left'));

        $eventdate = date('F j, Y', strtotime($this->lead->seminar_date_c));
        $eventday = date('l', strtotime($eventdate));
        $text = "Thank you for your interest in attending ".$this->lead->seminar_title.".  We look forward to meeting you and your guest(s) on {$eventday}, {$eventdate} at:\n\n";
        
        $this->pdf->ezText($text, 10, array('justification'=>'left'));
        
        $this->pdf->ezImage('custom/logos/' . $this->lead->venue_logo_filename, 0, 100, "none", "center");
        
        $this->pdf->ezText("<b>" . $this->lead->seminar_venue_name_c . "</b>", 15, array('justification'=>'center'));
        $this->pdf->ezText($this->lead->seminar_address_c, 11, array('justification'=>'center'));
        $address2 = $this->lead->seminar_city_c . ", " . $this->lead->seminar_state_c . " " . $this->lead->seminar_postalcode_c . "\n";
        $this->pdf->ezText($address2, 10, array('justification'=>'center'));

        $text = "Please plan to arrive by ".$this->lead->before_meeting_start." to enjoy appetizers before the start of the program. Seating is on a first come, first serve basis so parties of more than 2 are encouraged to arrive early to sit together. There is no admittance to the program after ".$this->lead->after_meeting_start.", please allow time for traffic delays.\n";

        $this->pdf->ezText($text, 10, array('justification'=>'left'));

        $text = "This is a program that you don't want to miss, however, if there are changes to your reservation please be sure to call the reservation line at 1-800-508-1453 so that we may notify the restaurant of a change in our attendance.\n";

        $this->pdf->ezText($text, 10, array('justification'=>'left'));


        $text = "\nSee you soon,\n\n\n";
        $this->pdf->ezText($text, 10, array('justification'=>'left'));

        $signature = "Rachel Gilmer\n";
        $this->pdf->ezText($signature, 10, array('justification'=>'left', 'leading'=>'5'));

        $signature = "Director of Operations\n" .
                     "GoldStone Financial Group\n" .
                     "One Lincoln Centre\n" .
                     "18W140 Butterfield Rd., 15th floor\n" .
                     "Oakbrook Terrace, IL 60181\n" .
                     "Ph:(630) 613-7691\n" .
                     "Fax:(630)786-3357\n\n";
                   
        $this->pdf->ezText($signature, 10, array('justification'=>'left'));
        
        $this->pdf->ezImage('custom/logos/PDF_footer.jpg', 0, 550, "none", "left");
        
        $text = "\n\n** Don't forget to bring your calendar **\n";

        $this->pdf->ezText($text, 10, array('justification'=>'center'));

        $footer = "\nOne Lincoln Centre <b>.</b> 18W140 Butterfield Rd <b>.</b> 15th Floor <b>.</b> Oakbrook Terrace, IL 60181\n";

        $this->pdf->ezText($footer, 10, array('justification'=>'center'));
        
        //create the pdf and stream it to the page
        //$this->pdf->output();
        //$this->pdf->ezStream();
        
        $this->createDocumentRecord();
    }
    
    
    function createDocumentRecord() {
        $today = date("Ymd");
        $document = new Document();
        $document->document_name = "Seminar Confirmation PDF " . $today . ".pdf";
        $document->parent_id = $this->lead->id;
        $document->save();

        $docrevision = new DocumentRevision();
        $docrevision->change_log = "Document Created";
        $docrevision->document_id = $document->id;
        $docrevision->filename = "Seminar Confirmation PDF " . $today . ".pdf";
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