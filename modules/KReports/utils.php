<?php
/*********************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed 
 * by KINAMU Business Solutions AG. All rights ar (c) 2010 by KINAMU Business
 * Solutions AG.
 *
 * This Version of the KReporter is licensed software and may only be used in 
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of KINAMU Business Solutions AG
 * 
 * You can contact KINAMU Business Solutions AG at Am Concordepark 2/F12
 * A-2320 Schwechat or via email at office@kinamu.com
 * 
 ********************************************************************************/
	require_once('modules/KReports/json/JSON.php');

		/*
	 * Function to generate a random String we need for the joins
	 * GUIDs would be too long and not fit the purpose
	 */
	function randomstring(){
		$len = 10;
		$base='abcdefghjkmnpqrstwxyz';
		$max=strlen($base)-1;
		$returnstring = '';
		mt_srand((double)microtime()*1000000);
		while (strlen($returnstring)<$len+1)
		  $returnstring.=$base{mt_rand(0,$max)};
		  
		return $returnstring;
		
	}
	
 	function json_decode_kinamu($json)
	{ 
		// bugfix 2010-8-23: problem with json in AJAX call
		if($json != '')
		{
		    // Author: walidator.info 2009
		    $comment = false;
		    $out = '$x=';
		   
		    for ($i=0; $i<strlen($json); $i++)
		    {
		        if (!$comment)
		        {
		            if ($json[$i] == '{' or $json[$i] == '[')        $out .= ' array(';
		            else if ($json[$i] == '}' or $json[$i] == ']')    $out .= ')';
		            else if ($json[$i] == ':')    $out .= '=>';
		            else                         $out .= $json[$i];           
		        }
		        else $out .= $json[$i];
		        if ($json[$i] == '"')    $comment = !$comment;
		    }
		    eval($out . ';');
		    return $x;
		}
		else 
		{
			return array();
		}
	}  
	
	function jarray_encode_kinamu($inArray)
	{
		if(!is_array($inArray))
			return '';
		
		// so we have an array
		foreach($inArray as $thisKey => $thisValue)
		{
			$resArray[] = "['" . $thisKey . "','" . $thisValue . "']"; 
		}
		return htmlentities('[' . implode(',', $resArray) . ']', ENT_QUOTES);
	}
	
	function json_encode_kinamu($input)
	{
		$json = new Services_JSON();
		return $json->encode($input);
	}
	
	// since this was moved with 5.5.1
	if(!function_exists('html_entity_decode_utf8'))
	{
		function html_entity_decode_utf8($string)
		{
		    static $trans_tbl;
		    // replace numeric entities
		    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $string);
		    $string = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $string);
		    // replace literal entities
		    if (!isset($trans_tbl))
		    {
		        $trans_tbl = array();
		        foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
		            $trans_tbl[$key] = utf8_encode($val);
		    }
		    return strtr($string, $trans_tbl);
		}
	}