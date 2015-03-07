<?php
/******************************************************************************
OpensourceCRM End User License Agreement

INSTALLING OR USING THE OpensourceCRM's SOFTWARE THAT YOU HAVE SELECTED TO 
PURCHASE IN THE ORDERING PROCESS (THE "SOFTWARE"), YOU ARE AGREEING ON BEHALF OF
THE ENTITY LICENSING THE SOFTWARE ("COMPANY") THAT COMPANY WILL BE BOUND BY AND 
IS BECOMING A PARTY TO THIS END USER LICENSE AGREEMENT ("AGREEMENT") AND THAT 
YOU HAVE THE AUTHORITY TO BIND COMPANY.

IF COMPANY DOES NOT AGREE TO ALL OF THE TERMS OF THIS AGREEMENT, DO NOT SELECT 
THE "ACCEPT" BOX AND DO NOT INSTALL THE SOFTWARE. THE SOFTWARE IS PROTECTED BY 
COPYRIGHT LAWS AND INTERNATIONAL COPYRIGHT TREATIES, AS WELL AS OTHER 
INTELLECTUAL PROPERTY LAWS AND TREATIES. THE SOFTWARE IS LICENSED, NOT SOLD.

    *The COMPANY may not copy, deliver, distribute the SOFTWARE without written
     permit from OpensourceCRM.
    *The COMPANY may not reverse engineer, decompile, or disassemble the 
    SOFTWARE, except and only to the extent that such activity is expressly 
    permitted by applicable law notwithstanding this limitation.
    *The COMPANY may not sell, rent, or lease resell, or otherwise transfer for
     value, the SOFTWARE.
    *Termination. Without prejudice to any other rights, OpensourceCRM may 
    terminate this Agreement if the COMPANY fail to comply with the terms and 
    conditions of this Agreement. In such event, the COMPANY must destroy all 
    copies of the SOFTWARE and all of its component parts.
    *OpensourceCRM will give the COMPANY notice and 30 days to correct above 
    before the contract will be terminated.

The SOFTWARE is protected by copyright and other intellectual property laws and 
treaties. OpensourceCRM owns the title, copyright, and other intellectual 
property rights in the SOFTWARE.
*****************************************************************************/
/*********************************************************************************
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights Reserved.
********************************************************************************/

class ParseXML{
      function GetChildren($vals, &$i) { 
         $children = array(); // Contains node data
         if (isset($vals[$i]['value'])){
            $children['VALUE'] = $vals[$i]['value'];
         } 
         
         while (++$i < count($vals)){ 
            switch ($vals[$i]['type']){
               
            case 'cdata': 
               if (isset($children['VALUE'])){
                  $children['VALUE'] .= $vals[$i]['value'];
               } else {
                  $children['VALUE'] = $vals[$i]['value'];
               } 
            break;
            
            case 'complete':
               if (isset($vals[$i]['attributes'])) {
                  $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                  $index = count($children[$vals[$i]['tag']])-1;
         
                  if (isset($vals[$i]['value'])){ 
                     $children[$vals[$i]['tag']][$index]['VALUE'] = $vals[$i]['value']; 
                  } else {
                     $children[$vals[$i]['tag']][$index]['VALUE'] = '';
                  }
               } else {
                  if (isset($vals[$i]['value'])){
                     $children[$vals[$i]['tag']][]['VALUE'] = $vals[$i]['value']; 
                  } else {
                     $children[$vals[$i]['tag']][]['VALUE'] = '';
                  } 
               }
            break;
            
            case 'open': 
               if (isset($vals[$i]['attributes'])) {
                  $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                  $index = count($children[$vals[$i]['tag']])-1;
                  $children[$vals[$i]['tag']][$index] = array_merge($children[$vals[$i]['tag']][$index],$this->GetChildren($vals, $i));
               } else {
                  $children[$vals[$i]['tag']][] = $this->GetChildren($vals, $i);
               }
            break; 
         
            case 'close': 
               return $children; 
         } 
      }
   }
     
      function GetXMLTree($xmlloc){ 
      $data=$xmlloc;
         $parser = xml_parser_create('ISO-8859-1');
         xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
         xml_parse_into_struct($parser, $data, $vals, $index); 
         xml_parser_free($parser); 
      
         $tree = array(); 
         $i = 0; 
         $newa = array(); 

         if (isset($vals[$i]['attributes'])) {
             $newa = $this->GetChildren($vals, $i);
            $tree[$vals[$i]['tag']][]['ATTRIBUTES'] = (isset($vals[$i]['attributes']))? $vals[$i]['attributes']:""; 
            $index = count($tree[$vals[$i]['tag']])-1;
            $tree[$vals[$i]['tag']][$index] =  array_merge($tree[$vals[$i]['tag']][$index], $newa);
         } else {
            $tree[$vals[$i]['tag']][] = $this->GetChildren($vals, $i); 
         }
      return $tree; 
      }
}