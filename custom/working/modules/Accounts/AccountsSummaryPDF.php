<?php
/*************************************
Script to generate Accounts Summary in PDF
Autor: JOED@ASI
Date: 20110725
*************************************/

require_once('include/pdf/class.ezpdf.php');
require_once('modules/Accounts/Account.php');


if ( (isset($_REQUEST['record']) && !empty($_REQUEST['record'])) &&
     (isset($_REQUEST['action']) && $_REQUEST['action'] == "AccountsSummaryPDF") ) {
    
    $apdf = new AccountsSummaryPDF($_REQUEST['record']);
    $apdf->generatePDF();
}

class AccountsSummaryPDF {

    private $pdf;
    private $account;

    public function AccountsSummaryPDF($record_id) {
        $this->pdf = new Cezpdf(array(0,0,598,842));
        $this->account = new Account();
        $this->account->retrieve($record_id);
    }
    
    function generatePDF() {
        
        $all = $this->pdf->openObject();
        $this->pdf->saveState();
        $this->pdf->setStrokeColor(0,0,0,1);
        $this->pdf->line(20,40,578,40);
        $this->pdf->line(20,822,578,822);
        $this->pdf->restoreState();
        $this->pdf->closeObject();
        $this->pdf->addObject($all,'all');
        
        $this->pdf->selectFont('././././include/fonts/Helvetica.afm');
        
        $this->pdf->ezSetY($this->pdf->y - 5);
        
        $this->pdf->ezImage('custom/logos/goldstone_nighttime.jpg', 0, 540, "none", "left");
        
        $this->pdf->ezText("\n\n<b>ACCOUNT SUMMARY</b>\n", 14, array('justification'=>'center'));
        
        $options['showLines'] = 0;
        $options['shaded'] = 0;
        $options['xOrientation'] = 'center';
        $options['width'] = 1000;
        $options['showHeadings'] = 0;
        $options['shadeHeadings'] = 0;
        $options['rowGap'] = 1;
        $options['fontSize'] = 10;
        
        
        $options['cols'] = array(
            "1"  => array('width' => '150', 'justification' => 'left'),
            "2" => array('width' => '100', 'justification' => 'left'),
            "3" => array('width' => '120', 'justification' => 'left'),
            "4" => array('width' => '100', 'justification' => 'left'),
        );
        $data[0]["1"] = "<b>Client:</b>";
        $data[0]["2"] = $this->account->name;
        $data[0]["3"] = "<b>Assigned to:</b>";
        $data[0]["4"] = $this->account->assigned_user_name;
        
        $this->pdf->ezTable($data, '', '', $options);
        
        
        $options['cols'] = array(
            "1"  => array('width' => '150', 'justification' => 'left'),
            "2" => array('width' => '100', 'justification' => 'left'),
            "3" => array('width' => '120', 'justification' => 'left'),
            "4" => array('width' => '100', 'justification' => 'right'),
        );
        $data[0]["1"] = "<b>Tax Status:</b>";
        $data[0]["2"] = $this->account->accounts_tax_status_c;
        $data[0]["3"] = "<b>Total Contributions:</b>";
        $data[0]["4"] = "$ " . number_format($this->account->total_contributions, 2);
        
        $data[1]["1"] = "<b>Current Company / Product:</b>";
        $data[1]["2"] = $this->account->accounts_company_product_c;
        $data[1]["3"] = "<b>Total Withdrawals:</b>";
        $data[1]["4"] = "$ " . number_format($this->account->total_withdrawals, 2);
        
        $data[2]["1"] = "<b>Current Account:</b>";
        $data[2]["2"] = $this->account->accounts_account_number_c;
        $data[2]["3"] = "<b>Current Value:</b>";
        $data[2]["4"] = "$ " . number_format($this->account->current_value, 2);
        
        $data[3]["1"] = "<b>Anniversary Date:</b>";
        $data[3]["2"] = $this->account->accounts_anniversary_date_c;
        $data[3]["3"] = "";
        $data[3]["4"] = "";

        $this->pdf->ezTable($data, '', '', $options);
        
        
        
        $data = array();
        $sourceaccounts = $this->account->get_linked_beans('accounts_gsf_sourceaccounts', 'GSF_SourceAccounts');
        
        $options['showLines'] = 2;
        $options['width'] = 900;
        $options['showHeadings'] = 1;
        $options['fontSize'] = 10;
        $options['cols'] = array(
            "Original Company"  => array('width' => '125', 'justification' => 'left'),
            "Account #"         => array('width' => '100', 'justification' => 'right'),
            "Amount"            => array('width' => '100', 'justification' => 'right'),
        );
        
        $count = 0;
        foreach ($sourceaccounts as $sourceaccount) {
            $data[$count]["Original Company"] = $sourceaccount->name;
            $data[$count]["Account #" ] = $sourceaccount->source_account_number;
            $data[$count]["Amount"] = "$ " . number_format($sourceaccount->projected_amount, 2);
            $count++;
        }
        
        if (!$count) {
            $data[$count]["Original Company"] = "<i>No Data</i>";
            $data[$count]["Account #" ] = "<i>No Data</i>";
            $data[$count]["Amount"] = "<i>No Data</i>";
        }
        
        $this->pdf->ezText("\n\n\n\n<b>TRANSFERRED FROM</b>\n", 10, array('justification'=>'center'));
        $this->pdf->ezTable($data, '', '', $options);
        
        $this->pdf->ezSetY($this->pdf->y - 100);
        $date_updated = date("m/d/Y h:i A", strtotime($this->account->date_modified));
        $this->pdf->ezText("<b>Last Updated:     </b>" . $date_updated, 10, array('justification'=>'right'));
        
        //create the pdf and stream it to the page
        $this->pdf->output();
        $filename = "Accounts_Summary.pdf";
        $this->pdf->ezStream(array("Content-Disposition" => $filename));
        
    }
    
}

?>