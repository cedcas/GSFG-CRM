<?php
/*************************************
Script to generate Seminar Confirmation PDF
Author: Joed@ASI
Date: 20110429

4/2/2015 - CPC
Modified based on Danielle's new requirements;
Removed both header and footer so their company letterhead may be used;
Modified the PDF filename to contain a timestamp;

4/4/2015 - CPC
Added 'lead->save()' to update all of the Lead's Seminar Information to the latest prior to creating the Confirmation PDF;

2/5/2016 - CPC
Requested by Kim to change Danielle's signature & name to Analhi's;
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
        
        //Added to update all of the Lead's Seminar Information to the latest;
        $this->lead->save();
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
        
        $this->pdf->ez['leftMargin'] = 30;
        // Header
        $today = date("F j, Y");
        $header = "\n\n\n\n\n\n\n\n\n\n$today\n\n" .
            "{$this->lead->first_name} {$this->lead->last_name}\n" .
            "{$this->lead->primary_address_street}\n" .
            "{$this->lead->primary_address_city} {$this->lead->primary_address_state} {$this->lead->primary_address_postalcode}\n\n";
        $header .= "\nDear {$this->lead->first_name} {$this->lead->last_name},\n";
        $this->pdf->ezText($header, 11, array('justification'=>'left'));

	// 1st Paragraph
        $eventdate = date('F j, Y', strtotime($this->lead->seminar_date_c));
        $eventday = date('l', strtotime($eventdate));
        $text = "Thank you for your interest in attending ".$this->lead->seminar_title.".  We look forward to meeting you and your guest(s) on {$eventday}, {$eventdate} at:\n\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));
        
	// Venue Information
	$this->pdf->ez['leftMargin'] = 30;
        $this->pdf->ez['leftMargin'] = -2;
        if($this->lead->venue_logo_filename<>''||$this->lead->venue_logo_filename <> NULL){
        	$this->pdf->ezImage('custom/logos/' . $this->lead->venue_logo_filename, 0, 120, "none", "center");
        	}else{
        	$this->pdf->ezImage('custom/logos/' . $this->lead->venue_logo_filename, 0, 120, "none", "center");
	        $this->pdf->ez['leftMargin'] = 70;
	        $this->pdf->ezText("<b>".trim($this->lead->seminar_venue_name_c)."<b>", 13, array('justification'=>'center'));
	        }
        //$this->pdf->ezText("<b>Seminar Venue Here<b>", 13, array('justification'=>'center'));
        $this->pdf->ez['leftMargin'] = 30;
        $this->pdf->ezText($this->lead->seminar_address_c, 10, array('justification'=>'center'));
        $address2 = $this->lead->seminar_city_c . ", " . $this->lead->seminar_state_c . " " . $this->lead->seminar_postalcode_c . "\n";
        $this->pdf->ezText($address2, 11, array('justification'=>'center'));

	$this->pdf->ez['leftMargin'] = 30;
	// 2nd Paragraph
        $text = "Please plan to arrive by ".$this->lead->before_meeting_start." to enjoy the first course before the start of the program. Seating is on a first come, first serve basis so parties of more than 2 are encouraged to arrive early to sit together. There is no admittance to the program after ".$this->lead->after_meeting_start.", please allow time for traffic delays.\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));

	// 3rd Paragraph
        $text = "This is a program that you don't want to miss, however, if there are changes to your reservation please be sure to call our office at 630-620-9300 so that we may notify the restaurant of a change in our attendance.\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));
	
	// Closing
        $text = "\nSee you soon,\n\n";
        $this->pdf->ezText($text, 11, array('justification'=>'left'));

	// Analhi's Signature
	$this->pdf->ezImage('custom/logos/analhi_signature.jpg', 0, 140, "none", "left");
        $name = "Analhi Nuñez\n\n";
        $this->pdf->ezText($name, 11, array('justification'=>'left', 'leading'=>'5'));
        $title = "Assistant Marketing Coordinator\n\n\n\n\n\n\n\n\n\n\n";
        $this->pdf->ezText($title, 11, array('justification'=>'left', 'leading'=>'5'));

        // Media logos
        $this->pdf->ez['leftMargin'] = 0;
        $this->pdf->ezImage('custom/logos/media_logos.jpg', 0, 500, "none", "center");
        
        
        $this->createDocumentRecord();
    }
    
    
    function createDocumentRecord() {
        $today = date("Ymdhis");
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
