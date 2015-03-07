<?php
/*************************************
Script to generate Cluster Report in PDF
Autor: JOED@ASI
Date: 20110511
*************************************/

require_once('include/pdf/class.ezpdf.php');
require_once('modules/GSF_Seminars/GSF_Seminars.php');


if ( (isset($_REQUEST['record']) && !empty($_REQUEST['record'])) &&
     (isset($_REQUEST['action']) && $_REQUEST['action'] == "ClusterReportPDF") ) {
    
    $crpdf = new ClusterReportPDF($_REQUEST['record']);
    $crpdf->generatePDF();
}

class ClusterReportPDF {

    private $pdf;
    private $cluster;

    public function ClusterReportPDF($record_id) {
        $this->pdf = new Cezpdf('a4', 'landscape');
        $this->cluster = new GSF_Seminars();
        $this->cluster->retrieve($record_id);
    }
    
    function generatePDF() {
    
        $this->pdf->selectFont('././././include/fonts/Helvetica.afm');
        
        $first_row = array(
            "<b>Date Created:</b>",
            $this->cluster->date_entered,
            "<b>Created By:</b>",
            $this->cluster->created_by_name
        );
        
        $data = array(
            array(
                "<b>Cluster:</b>",
                $this->cluster->name,
                "<b>Total Capacity:</b>",
                $this->cluster->seminar_total_capacity,
            ),
            array(
                "<b>Mailing Date:</b>",
                $this->cluster->seminar_mailing_date,
                "<b>Seminar Title:</b>",
                $this->cluster->seminar_title,
            ),
            array(
                "<b>Number of Mailers:</b>",
                $this->cluster->seminar_number_of_mailers,
                "<b>Dollar Spent:</b>",
                number_format($this->cluster->seminar_dollar_spent, 2),
            ),
            array(
                "<b>Description:</b>",
                $this->cluster->description,
                "<b>ZipCodes for Mailers:</b>",
                $this->cluster->seminar_zipcodes_for_mailers,
            ),
        
        );
        
        $options = array(
            "shaded" => 0,
            "showLines" => 2,
            "width" => 550,
        );
        $this->pdf->ezText("\n\n<b>Cluster Report</b>\n", 14, array('justification'=>'center'));
        $this->pdf->ezTable($data, $first_row, "", $options);
        
        
        $headers = array(
            "SEMINAR ID",
            "VENUE",
            "ADDRESS",
            "CITY",
            "ZIP",
            "DATE",
            "TIME",
            "CAPACITY",
            "SALES PEOPLE"
        );
        
        $data = array();
        $seminardetails = $this->cluster->get_linked_beans('gsf_seminars_gsf_seminardetails', 'GSF_SeminarDetails');
        
        foreach ($seminardetails as $seminardetail) {
            $sales_people = array();
            $agents = $seminardetail->get_linked_beans('users', 'User');
            foreach ($agents as $agent) {
                array_push($sales_people, $agent->first_name);
            }
            
            $sales_people = implode(", ", $sales_people);
            
            $row = array(
                $seminardetail->name,
                $seminardetail->gsf_venues_gsf_seminardetails_name . " (".substr($seminardetail->name, -5).")", // add the last 5 digit of the Seminar Details ID
                $seminardetail->details_venue_address1,
                $seminardetail->details_venue_city,
                $seminardetail->details_venue_postalcode,
                $seminardetail->details_from_date,
                $seminardetail->details_from_time,
                $seminardetail->details_capacity,
                $sales_people,
            );
            array_push($data, $row);
        }
        
        $options = array(
            "shaded" => 0,
            "showLines" => 2,
            "width" => 750,
        );
        $this->pdf->ezText("\n\n", 11, array('justification'=>'center'));
        $this->pdf->ezTable($data, $headers, "", $options);
        
        //create the pdf and stream it to the page
        $this->pdf->output();
        $filename = "Cluster_Report.pdf";
        $this->pdf->ezStream(array("Content-Disposition" => $filename));
        
    }
    
}

?>