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
if (file_exists("modules/Calendar2/googlecal/GoogleCalendarPRO.php"))
{
	require_once("modules/Calendar2/googlecal/GoogleCalendarPRO.php");
	class MyCurlExt extends GoogleCalendarPRO {

	}
}
else
{
	class MyCurlExt  {

	}
}


class MyCurl  extends MyCurlExt
{
	var $getHeaders = true;//headers will be added to output
	var $getContent = true; //contens will be added to output
	var $followRedirects = true; //should the class go to another URL, if the current is "HTTP/1.1 302 Moved Temporarily"
	
	var $fCookieFile;
	var $fSocket;

	function MyCurl()
	{
		global $path;
		// $this->fCookieFile = tempnam($path, "1g_");
	}

	function init()
	{
		return $this->fSocket = curl_init();
	}

	function setopt($opt, $value)
	{
		return curl_setopt($this->fSocket, $opt, $value);
	}

	function load_defaults()
	{
		$this->setopt(CURLOPT_RETURNTRANSFER, 1);
		$this->setopt(CURLOPT_FOLLOWLOCATION, $this->followRedirects);
		$this->setopt(CURLOPT_REFERER, "http://google.com");
		$this->setopt(CURLOPT_VERBOSE, false); 
		$this->setopt(CURLOPT_SSL_VERIFYPEER, false);
		$this->setopt(CURLOPT_SSL_VERIFYHOST, false);
		$this->setopt(CURLOPT_HEADER, $this->getHeaders);
		$this->setopt(CURLOPT_NOBODY, !$this->getContent);
		$this->setopt(CURLOPT_USERAGENT, "MyCurl");
		$this->setopt(CURLOPT_CUSTOMREQUEST,'POST');
		//if($fp)
		if($fp = tmpfile())
			$this->setopt(CURLOPT_STDERR, $fp);
	}

	function destroy()
	{
		return curl_close($this->fSocket);
	}

	function head($url)
	{
		$this->init();
		if($this->fSocket)
		{
			$this->getHeaders = true;
			$this->getContent = false;
			$this->load_defaults();
			$this->setopt(CURLOPT_POST, 0);
			$this->setopt(CURLOPT_CUSTOMREQUEST,'HEAD');
			$this->setopt(CURLOPT_URL, $url);
			$result = curl_exec($this->fSocket);
			$this->destroy();
			return $result;
		}
		return 0;
	}

	function get($url)
	{
		$this->init();
		if($this->fSocket)
		{
			$this->load_defaults();
			$this->setopt(CURLOPT_POST, 0);
			$this->setopt(CURLOPT_CUSTOMREQUEST,'GET');
			$this->setopt(CURLOPT_URL, $url);
			$result = curl_exec($this->fSocket);
			$this->destroy();
			return $result;
		}
		return 0;
	}

	function post_login($url, $post_data, $arr_headers=array(), $http_code="")
	{
		$this->init();
		if($this->fSocket)
		{
			$post_data = $this->compile_post_data($post_data);
			$this->load_defaults();
			if(!empty($post_data))
				$this->setopt(CURLOPT_POSTFIELDS, $post_data);

			if(!empty($arr_headers))
				$this->setopt(CURLOPT_HTTPHEADER, $arr_headers);
			$this->setopt(CURLOPT_URL, $url);
			$result = curl_exec($this->fSocket);
			//OSC 2011.3.15
			//$nl = explode("Location:",$result);
			$result_val = explode("Location:",$result);
			$nl = preg_split('/[\r\n]/', $result_val[0]);
			foreach ($nl as $key => $val)
			{
				if (preg_match("/Content-Type:/i", $val)) 
				{
					$nl1 = explode("Content-Type:", $nl[$key]);
					break;
				}
			}
			//OSC End
			$new_loc = $nl1[0];    

			$http_code = curl_getinfo($this->fSocket, CURLINFO_HTTP_CODE);
			$this->destroy();
			return $result;
		}
		return 0;
	}

	function post($url, $post_data, $arr_headers=array(), $http_code="",$evid="",$tablename="",$user_data="")
	{
		$this->init();
		if($this->fSocket)
		{
			$post_data = $this->compile_post_data($post_data);
			$this->load_defaults();
			if(!empty($post_data))
				$this->setopt(CURLOPT_POSTFIELDS, $post_data);

			if(!empty($arr_headers))
				$this->setopt(CURLOPT_HTTPHEADER, $arr_headers);
			$this->setopt(CURLOPT_URL, $url);
			$result = curl_exec($this->fSocket);
			debugg("function post:google response ".$result);
			//OSC 2011.3.15
			//$nl = explode("Location:",$result);
			//$nl1 = explode("Content-Type:",$nl[1]);
			$result_val = explode("Location:",$result);
			$nl = preg_split('/[\r\n]/', $result_val[0]);
			foreach ($nl as $key => $val)
			{
				if (preg_match("/Content-Type:/i", $val)) 
				{
					$nl1 = explode("Content-Type:", $nl[$key]);
					break;
				}
			}
			//OSC End
			$new_loc = $nl1[0];    

			if ($tablename!='')
			{
				if ($tablename=="tasks")
				{
					$mmod = new Task;
					$mmod1 = new Task; 
				}
				if ($tablename=="meetings")
				{
					$mmod = new Meeting;
					$mmod1 = new Meeting;
				}
				if ($tablename=="calls")
				{
					$mmod = new Call;
					$mmod1 = new Call; 
				}
				debugg("<br>Adding new event ");
				$newtbname = $tablename."_cstm"   ; 
				$cs1 = "delete from $newtbname where id_c='$evid'";
				$cs2 = "insert into $newtbname (id_c,google_response_c) values ('$evid',\"$result\")";
				//debugg($cs);
				$r = $mmod->db->query($cs1); 
				$r = $mmod->db->query($cs2);  
			}

			$http_code = curl_getinfo($this->fSocket, CURLINFO_HTTP_CODE);
			$this->destroy();

			$s1 = explode("<entry xmlns",$result);
			$s2 = explode("</entry>",$s1[1]);
			$xmlstr = "<entry xmlns".$s2[0]."</entry>";

			$xmlparse = &new ParseXML;
			$xml = $xmlparse->GetXMLTree($xmlstr);

			$old_id=$xml['ENTRY'][0]['ID'][0]['VALUE'];
			$old_published=$xml['ENTRY'][0]['PUBLISHED'][0]['VALUE'];
			$old_updated=$xml['ENTRY'][0]['UPDATED'][0]['VALUE'];
			$old_link_alt=$xml['ENTRY'][0]['LINK'][0]['ATTRIBUTES']['HREF'];
			$old_link_self=$xml['ENTRY'][0]['LINK'][1]['ATTRIBUTES']['HREF'];
			$old_link_edit=$xml['ENTRY'][0]['LINK'][2]['ATTRIBUTES']['HREF'];
			$old_author=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['VALUESTRING'];
			$old_email=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['EMAIL']; 

			$old_updated=      $xml['ENTRY'][0]['UPDATED'][0]['VALUE'];  
			$old_created=      $xml['ENTRY'][0]['PUBLISHED'][0]['VALUE']; 

			$mmod1->disable_row_level_security=true;
			$mmod1->retrieve($evid)  ;
			$mmod1->old_id_c= $old_id;
			$mmod1->old_published_c= $old_published;
			$mmod1->old_updated_c= $old_updated;
			$mmod1->old_link_alt_c= $old_link_alt;
			$mmod1->old_link_self_c= $old_link_self;
			$mmod1->old_link_edit_c = $old_link_edit;
			$mmod1->old_author_c = $old_author;
			$mmod1->old_email_c = $old_email;

			$mmod1->g_published_c = $old_created; 
			$mmod1->g_updated_c = $old_updated;

			$mmod1->Save(); 
			return $result;    
		}
		return 0;
	}

	function post_onl($url, $post_data, $arr_headers=array(), $http_code="",$evid="",$tablename="",$user_data="",$bean)
	{

		$this->init();
		if($this->fSocket)
		{
			$post_data = $this->compile_post_data($post_data);
			$this->load_defaults();
			if(!empty($post_data))
				$this->setopt(CURLOPT_POSTFIELDS, $post_data);

			if(!empty($arr_headers))
				$this->setopt(CURLOPT_HTTPHEADER, $arr_headers);
			$this->setopt(CURLOPT_URL, $url);
			$this->setopt(CURLOPT_COOKIEJAR, $user_data[0]);
			$this->setopt(CURLOPT_COOKIEFILE, $user_data[0]);

			$result = curl_exec($this->fSocket);
			debugg("function post_onl:google response ".$result);
			//OSC 2011.3.15
			//$nl = explode("Location:",$result);
			//$nl1 = explode("Content-Type:",$nl[1]);
			$result_val = explode("Location:",$result);
			$nl = preg_split('/[\r\n]/', $result_val[0]);
			foreach ($nl as $key => $val)
			{
				if (preg_match("/Content-Type:/i", $val)) 
				{
					$nl1 = explode("Content-Type:", $nl[$key]);
					break;
				}
			}
			//OSC End
			$new_loc = $nl1[0];    

			if ($tablename!='')
			{
				if ($tablename=="tasks")
				{
					$mmod = new Task;
					$mmod1 = new Task; 
				}
				if ($tablename=="meetings")
				{
					$mmod = new Meeting;
					$mmod1 = new Meeting;
				}
				if ($tablename=="calls")
				{
					$mmod = new Call;
					$mmod1 = new Call; 
				}
				$newtbname = $tablename; 
				//debugg($cs);
				$bean->google_response_c=$result;
			}

			$http_code = curl_getinfo($this->fSocket, CURLINFO_HTTP_CODE);
			$this->destroy();

			$s1 = explode("<entry xmlns",$result);
			$s2 = explode("</entry>",$s1[1]);
			$xmlstr = "<entry xmlns".$s2[0]."</entry>";

			$xmlparse = &new ParseXML;
			$xml = $xmlparse->GetXMLTree($xmlstr);

			$old_id=$xml['ENTRY'][0]['ID'][0]['VALUE'];
			$old_published=$xml['ENTRY'][0]['PUBLISHED'][0]['VALUE'];
			$old_updated=$xml['ENTRY'][0]['UPDATED'][0]['VALUE'];
			$old_link_alt=$xml['ENTRY'][0]['LINK'][0]['ATTRIBUTES']['HREF'];
			$old_link_self=$xml['ENTRY'][0]['LINK'][1]['ATTRIBUTES']['HREF'];
			$old_link_edit=$xml['ENTRY'][0]['LINK'][2]['ATTRIBUTES']['HREF'];

			$old_author=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['VALUESTRING'];
			$old_email=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['EMAIL']; 
			$old_updated=      $xml['ENTRY'][0]['UPDATED'][0]['VALUE'];  
			$old_created=      $xml['ENTRY'][0]['PUBLISHED'][0]['VALUE']; 

			$bean->old_id_c= $old_id;
			$bean->old_published_c= $old_published;
			$bean->old_updated_c= $old_updated;
			$bean->old_link_alt_c= $old_link_alt;
			$bean->old_link_self_c= $old_link_self;
			$bean->old_link_edit_c = $old_link_edit;
			$bean->old_author_c = $old_author;
			$bean->old_email_c = $old_email;

			$bean->g_published_c = $old_created; 
			$bean->g_updated_c = $old_updated;

			return $result;    
		}
		return 0;
	}

	function post_doc($file,$user_data)
	{
		$this->insert_doc($file,$filename,$file_ext,$file_mime_type,$user_data);
	}

	function curlToHost_doc($url,$method,$headers=array(''))
	{
		ob_start();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,    CURLOPT_VERBOSE, 1); ########### debug
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); // follow redirects recursively
		curl_setopt($ch,CURLOPT_POSTFIELDS,$mime); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_FAILONERROR, false); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 16); // follow redirects recursively
		curl_setopt($ch, CURLOPT_TIMEOUT, 32); // follow redirects recursively

		$aa = curl_exec($ch);
		curl_close($ch);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function get_edit($url, $post_data, $arr_headers=array(), &$http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data,$newlink,$current_user)
	{
		$auth=getAuthCode($user_data[0],$user_data[1]);
		$header = array();
		$header[] = "MIME-Version: 1.0";
		$header[] = "Accept: text/xml";
		$header[] = "Authorization: GoogleLogin auth=".$auth;
		$header[] = "Content-type: application/atom+xml";
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";

		$link=$old_link_edit;
		$entry=curlToHost($newlink, 
		'GET',
		$header,$user_data[0]);   
		debugg("function get_edit:google response ".$entry);  
		$checkok = explode("<link rel='edit' type='application/atom+xml' href='",$entry);
		$checkok1 = explode("'/>",$checkok[1]);
		$checkok_url = $checkok1[0];
		if ($checkok_url!='')
		{
			if ($tablename=="tasks")
			{
				$mmod = new Task;
				$mmod1 = new Task;  
			}
			if ($tablename=="meetings")
			{
				$mmod = new Meeting;
				$mmod1 = new Meeting; 
			}
			if ($tablename=="calls")
			{
				$mmod = new Call;
				$mmod1 = new Call;
			}
			$newtbname = $tablename."_cstm"   ; 

			if (1==2)
			{
				$cs1 = "delete from $newtbname where id_c='$evid'";
				$cs2 = "insert into $newtbname (id_c,google_response_c) values ('$evid',\"$entry\")";
				echo "<br>update ev1 >".$cs1;
				echo "<br>update ev1 >".$cs2;
				$r = $mmod->db->query($cs1); 
				$r = $mmod->db->query($cs2);  
			}

			$s1 = explode("<entry xmlns",$entry);
			$s2 = explode("</entry>",$s1[1]);
			$xmlstr = "<entry xmlns".$s2[0]."</entry>";

			$xmlparse = &new ParseXML;
			$xml = $xmlparse->GetXMLTree($xmlstr);

			$old_id=            $xml['ENTRY'][0]['ID'][0]['VALUE'];
			$old_published=     $xml['ENTRY'][0]['PUBLISHED'][0]['VALUE'];
			$old_updated=       $xml['ENTRY'][0]['UPDATED'][0]['VALUE'];
			$old_link_alt=      $xml['ENTRY'][0]['LINK'][0]['ATTRIBUTES']['HREF'];
			$old_link_self=     $xml['ENTRY'][0]['LINK'][1]['ATTRIBUTES']['HREF'];
			$old_link_edit=     $xml['ENTRY'][0]['LINK'][2]['ATTRIBUTES']['HREF'];
			$old_author=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['VALUESTRING'];
			$old_email=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['EMAIL']; 

			$old_title=        $xml['ENTRY'][0]['TITLE'][0]['VALUE'];    
			$old_content=      $xml['ENTRY'][0]['CONTENT'][0]['VALUE']; 

			$old_when=      $xml['ENTRY'][0]['GD:WHEN'][0];    
			$old_whenstrtime = $old_when['ATTRIBUTES']['STARTTIME'];
			$old_whenendtime = $old_when['ATTRIBUTES']['ENDTIME'];
	
			$old_reminder1_min = $old_when['GD:REMINDER'][0]['ATTRIBUTES']['MINUTES']; 
			$old_reminder1_type = $old_when['GD:REMINDER'][0]['ATTRIBUTES']['METHOD'];

			$old_reminder2_min = $old_when['GD:REMINDER'][1]['ATTRIBUTES']['MINUTES']; 
			$old_reminder2_type = $old_when['GD:REMINDER'][1]['ATTRIBUTES']['METHOD'];
	
			$old_where1=      $xml['ENTRY'][0]['GD:WHERE'][0]['ATTRIBUTES']['VALUESTRING'];      
			$old_where2=      $xml['ENTRY'][0]['GD:WHERE'][0]['VALUE'];                   

			$old_updated=      $xml['ENTRY'][0]['UPDATED'][0]['VALUE'];  
			$old_created=      $xml['ENTRY'][0]['PUBLISHED'][0]['VALUE']; 
			global  $timedate;

			$t1 = explode("T",$old_whenstrtime);
			$t1date = $t1[0];
			$t1t = $t1[1];
			$t1t = explode(".",$t1t);
			$t1time =$t1t[0];
			$newdatestart =  $t1date." ".$t1time;
			$t1 = explode("T",$old_whenendtime);
			$t1date = $t1[0];
			$t1t = $t1[1];
			$t1t = explode(".",$t1t);
			$t1time =$t1t[0];
			$newdateend =  $t1date." ".$t1time;
			$newdatestart1 = $newdatestart;
			$newdatestart = $timedate->handle_offset($newdatestart, $timedate->get_db_date_time_format(), false);  
			$newdateend = $timedate->handle_offset($newdateend, $timedate->get_db_date_time_format(), false); 
			$sq = "SELECT TIMESTAMPDIFF(MINUTE,'$newdatestart','$newdateend') as dateDiff ";
			$r = $mmod1->db->query($sq); 
			$a = $mmod1->db->fetchByAssoc($r,-1,false);
			$datediff =$a['dateDiff'];
			$hdiff = floor($datediff / 60);

			$mindiff =$datediff-floor($hdiff)*60;
			$mmod1->disable_row_level_security=true;
			$mmod1->retrieve($evid)  ;
			global $calls_prefix;
			global $meetings_prefix;
			global $tasks_prefix;  

			if ($mmod1->g_updated_c!=  $old_updated)
			{
				if ($tablename=="tasks")
				{
					$old_title = str_replace( $tasks_prefix,"",$old_title); 
				}
				if ($tablename=="meetings")
				{
					$old_title = str_replace($meetings_prefix,"",$old_title);      
				}
				if ($tablename=="calls")
				{
					$old_title = str_replace( $calls_prefix,"",$old_title);      
				}
				$newdatestart =$timedate->to_display_date_time($newdatestart);
				$newdateend =$timedate->to_display_date_time($newdateend);    

				$mmod1->name= utf8_encode($old_title);
				$mmod1->description= utf8_encode($old_content);    
				$mmod1->location= utf8_encode($old_where1);  
				$mmod1->location= utf8_encode($old_where1);   
				$mmod1->date_start=$newdatestart;
				$mmod1->date_end=$newdateend;
				$mmod1->date_due=$newdateend; 
				$mmod1->duration_hours=$hdiff;
				$mmod1->duration_minutes=$mindiff;   
				$old_reminder1_min=$old_reminder1_min*60;

				debugg("<br>must update sugar. ".$old_title);

				$mmod1->old_id_c= $old_id;
				$mmod1->old_published_c= $old_published;
				$mmod1->old_updated_c= $old_updated;
				$mmod1->old_link_alt_c= $old_link_alt;
				$mmod1->old_link_self_c= $old_link_self;
				$mmod1->old_link_edit_c = $old_link_edit;
				$mmod1->old_author_c = $old_author;
				$mmod1->old_email_c = $old_email;
				$mmod1->g_updated_c=  $old_updated;
				$mmod1->save(); 

			} 
		}
		else
		{
			debugg("<br>***** Error updating event .. response ".var_export($entry,true));
		}
	}

	function get_calendar_prop($newlink,$auth,$user_data)
	{
		$this->fCookieFile=$user_data[0];
		$header = array();
		$header[] = "MIME-Version: 1.0";
		$header[] = "Accept: text/xml";
		$header[] = "Authorization: GoogleLogin auth=".$auth;
		$header[] = "Content-type: application/atom+xml";
		$header[] = "Cache-Control: no-cache";
		$header[]=  "Gdata-Version: 2.1";
		$header[] = "Connection: close \r\n";
		debugg("<br>Check Calendar prop ".$newlink);
		$link=$old_link_edit;

		$all_calendars_link="http://www.google.com/calendar/feeds/default/allcalendars/full";   
		$entry=curlToHost_v2($all_calendars_link, 
		'GET',
		$header,
		$this->fCookieFile);   
		debugg("function get_google_modified_events".$entry);
		$xmlparse = &new ParseXML;
		$xml1 = $xmlparse->GetXMLTree($entry);
		$real_owner=$xml1['FEED'][0]['AUTHOR'][0]['EMAIL'][0]['VALUE']; 

		$google_user=$real_owner;
		if (stristr($google_user,"@")=="")
		{
			$google_user.="@gmail.com";
		}
		$newlink=str_replace("private/full","acl/full",$newlink);
		$newlink=$newlink."/user:".$google_user;

		if (1==2)
		{ 
			$ctr=$xml1['FEED'][0]['LINK'];
			foreach ($ctr as $k=>$v)
			{
				if ($v['ATTRIBUTES']['REL']=="http://schemas.google.com/acl/2007#controlledObject")
				{
					$ctr_url=$v['ATTRIBUTES']['HREF'];
				}
			}
			$ctr_url=str_replace("private/full","acl/full",$ctr_url); 
			echo "<br>\n\n ctrl link ".$ctr_url;
		}
		$entry1=curlToHost_v2($newlink, 
		'GET',
		$header,
		$this->fCookieFile); 
		$xmlparse1 = &new ParseXML;
		$xml2 = $xmlparse1->GetXMLTree($entry1);
		$got_user=$xml2['ENTRY'][0]['GACL:SCOPE'][0]['ATTRIBUTES']['VALUE']; 
		$array['real_owner']=$real_owner;
		$array['got_user']=$got_user;
		return $array;    
	}


	function check_same_user($organizer,$user)
	{
		$o1=explode("@",$organizer);
		$org=$o1[0];
		$o2=explode("@",$user);
		$user=$o2[0]; 
		if ($org==$user)
		{
			return true;
		}
		else
		{
			return false;
		}  
	}

	function get_organizer($who_array)
	{
		foreach ($who_array as $k=>$v)
		{
			$type=$v['ATTRIBUTES']['REL'] ;
			if ($type=="http://schemas.google.com/g/2005#event.organizer")
			{
				return $v['ATTRIBUTES']['EMAIL'];
			}
		}
	}

	function check_shared_event($google_id)
	{
		$calls= $this->check_db_event($google_id,"calls");
		if ($calls['id']!="")
		{
			return $calls;
		}
		$meetings= $this->check_db_event($google_id,"meetings");
		if ($meetings['id']!="")
		{
			return $meetings;
		}
		$tasks= $this->check_db_event($google_id,"tasks");
		if ($tasks['id']!="")
		{
			return $tasks;
		}
		return array("id"=>"");
	}

	function check_db_event($google_id,$tablename)
	{
		global $db;
		$sq="select * from $tablename where ".$tablename.".old_id_c='".$google_id."' and ".$tablename.".deleted=0 limit 0,1";  
		$r=$db->query($sq,true);
		$row=$db->fetchByAssoc($r);
		return $row;
	}

	function post_edit_onl($url , $post_data, $arr_headers=array(), &$http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data,$old_id,$bean)
	{
		$auth=getAuthCode($user_data[0],$user_data[1]);
		$header = array();
		$header[] = "MIME-Version: 1.0";
		$header[] = "Accept: text/xml";
		$header[] = "Authorization: GoogleLogin auth=".$auth;
		$header[] = "Content-length: ".strlen($_entry);
		$header[] = "Content-type: application/atom+xml";
		$header[] = "Cache-Control: no-cache";
		$header[] = "If-Match: *";               // this is new in 150909
		$header[] = "Connection: close \r\n";
		$header[] = $_entry;

		$link=$old_link_edit;
		$entry=curlToHost($link, 
		'PUT',
		$header,$user_data[0]);   
		debugg("function post_edit_onl:google response ".$entry);   
		$checkok = explode("<link rel='edit' type='application/atom+xml' href='",$entry);
		$checkok1 = explode("'/>",$checkok[1]);
		$checkok_url = $checkok1[0];
		if ($checkok_url!='')
		{
			if ($tablename=="tasks")
			{
			}
			if ($tablename=="meetings")
			{
			}
			if ($tablename=="calls")
			{
			}

			$newtbname = $tablename; 
			$s1 = explode("<entry xmlns",$entry);
			$s2 = explode("</entry>",$s1[1]);
			$xmlstr = "<entry xmlns".$s2[0]."</entry>";

			$xmlparse = &new ParseXML;
			$xml = $xmlparse->GetXMLTree($xmlstr);

			$old_id=$xml['ENTRY'][0]['ID'][0]['VALUE'];
			$old_published=$xml['ENTRY'][0]['PUBLISHED'][0]['VALUE'];
			$old_updated=$xml['ENTRY'][0]['UPDATED'][0]['VALUE'];
			$old_link_alt=$xml['ENTRY'][0]['LINK'][0]['ATTRIBUTES']['HREF'];
			$old_link_self=$xml['ENTRY'][0]['LINK'][1]['ATTRIBUTES']['HREF'];
			$old_link_edit=$xml['ENTRY'][0]['LINK'][2]['ATTRIBUTES']['HREF'];
			$old_author=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['VALUESTRING'];
			$old_email=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['EMAIL']; 

			$bean->old_id_c= $old_id;
			$bean->old_published_c= $old_published;
			$bean->old_updated_c= $old_updated;
			$bean->old_link_alt_c= $old_link_alt;
			$bean->old_link_self_c= $old_link_self;
			$bean->old_link_edit_c = $old_link_edit;
			$bean->old_author_c = $old_author;
			$bean->old_email_c = $old_email;
			$bean->google_response_c=$entry;

			$bean->g_updated_c = $old_updated;
		}
		else
		{
			$newtbname = $tablename; 
			$bean->google_response_c=$entry;
			debugg("<br>***** Error updating event .. response ".print_r($entry));
		}
		return $old_id;
	}

	function post_edit($url, $post_data, $arr_headers=array(), &$http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data)
	{
		$auth=getAuthCode($user_data[0],$user_data[1]);
		$header = array();
		$header[] = "MIME-Version: 1.0";
		$header[] = "Accept: text/xml";
		$header[] = "Authorization: GoogleLogin auth=".$auth;
		$header[] = "Content-length: ".strlen($_entry);
		$header[] = "Content-type: application/atom+xml";
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		$header[] = $_entry;

		$link=$old_link_edit;
		$entry1=curlToHost($link, 
		'PUT',
		$header,$user_data[0]);   
		$entry=$entry1;
		debugg("function post_edit:google response ".$entry);                       
		$checkok = explode("<link rel='edit' type='application/atom+xml' href='",$entry);
		$checkok1 = explode("'/>",$checkok[1]);
		$checkok_url = $checkok1[0];
		if ($checkok_url!='')
		{
			if ($tablename=="tasks")
			{
				$mmod = new Task;
				$mmod1 = new Task;  
			}
			if ($tablename=="meetings")
			{
				$mmod = new Meeting;
				$mmod1 = new Meeting; 
			}
			if ($tablename=="calls")
			{
				$mmod = new Call;
				$mmod1 = new Call;
			}

			$newtbname = $tablename; 
			//$cs2 = "update $newtbname (google_response_c) values ('$evid',\"$entry\")";
			
			debugg("<br>update ev1 >".$cs1);
			debugg("<br>update ev1 >".$cs2);
			//$r = $mmod->db->query($cs1); 
			//$r = $mmod->db->query($cs2);  

			$s1 = explode("<entry xmlns",$entry);
			$s2 = explode("</entry>",$s1[1]);
			$xmlstr = "<entry xmlns".$s2[0]."</entry>";

			$xmlparse = &new ParseXML;
			$xml = $xmlparse->GetXMLTree($xmlstr);

			$old_id=$xml['ENTRY'][0]['ID'][0]['VALUE'];
			$old_published=$xml['ENTRY'][0]['PUBLISHED'][0]['VALUE'];
			$old_updated=$xml['ENTRY'][0]['UPDATED'][0]['VALUE'];
			$old_link_alt=$xml['ENTRY'][0]['LINK'][0]['ATTRIBUTES']['HREF'];
			$old_link_self=$xml['ENTRY'][0]['LINK'][1]['ATTRIBUTES']['HREF'];
			$old_link_edit=$xml['ENTRY'][0]['LINK'][2]['ATTRIBUTES']['HREF'];
			$old_author=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['VALUESTRING'];
			$old_email=$xml['ENTRY'][0]['GD:WHO'][0]['ATTRIBUTES']['EMAIL']; 

			$mmod1->disable_row_level_security=true;   
			$mmod1->retrieve($evid)  ;

			$mmod1->old_id_c= $old_id;
			$mmod1->old_published_c= $old_published;
			$mmod1->old_updated_c= $old_updated;
			$mmod1->old_link_alt_c= $old_link_alt;
			$mmod1->old_link_self_c= $old_link_self;
			$mmod1->old_link_edit_c = $old_link_edit;
			$mmod1->old_author_c = $old_author;
			$mmod1->old_email_c = $old_email;

			$mmod1->Save(); 
			debugg("<br>***** Event update ok");
		}
		else
		{
			debugg("<br>***** Error updating event .. response ".print_r($entry));
		}
	}

	function write_sugar_doc($filename,$path,$data)
	{
		global $sugar_config; 
		$path =  $sugar_config["upload_dir"];   
		$fileloc = $path.$filename;
		debugg("<br>** write local file ".$fileloc);
		$handle = fopen($fileloc, "w+");
		$contents = fwrite($handle, $data);
		fclose($handle);
	}

	function compile_post_data($post_data)
	{
		$o="";
		if(!empty($post_data))
		foreach ($post_data as $k=>$v)
		$o.= $k."=".urlencode($v)."&";
		return substr($o,0,-1);
	}

	function get_parsed($result, $bef, $aft="")
	{
		$line=1;
		$len = strlen($bef);
		$pos_bef = strpos($result, $bef);
		if($pos_bef===false)
			return "";
		$pos_bef+=$len;

		if(empty($aft))
		{
			$pos_aft = strpos($result, "\n", $pos_bef);
			if($pos_aft===false)
				$pos_aft = strpos($result, "\r\n", $pos_bef);
		}
		else
			$pos_aft = strpos($result, $aft, $pos_bef);

		if($pos_aft!==false)
			$rez = substr($result, $pos_bef, $pos_aft-$pos_bef);
		else
			$rez = substr($result, $pos_bef);
		return $rez;
	}

	function post_delete_onl($url , $post_data, $arr_headers=array(), &$http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data,$old_id,$bean)
	{
		$auth=getAuthCode($user_data[0],$user_data[1]);
		$header = array();
		$header[] = "MIME-Version: 1.0";
		$header[] = "Accept: text/xml";
		$header[] = "Authorization: GoogleLogin auth=".$auth;
		#$header[] = "Content-length: ".strlen($_entry);
		$header[] = "Content-type: application/atom+xml";
		$header[] = "If-Match: *";
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		if (!isset($_REQUEST['delete_invite_user_id']))
		{
			$link=$old_link_edit;
		}
		else
		{
			$link=$_REQUEST['delete_invite_url'];
		}     

		$link=$old_link_edit;
		debugg("<br><br>link delete ".$link."<br><br>");
		$entry=curlToHost($link, 
		'DELETE',
		$header,$user_data[0]);   
		debugg("<br><br>result delete ".$entry."<br><br>");  
	}

}


	function returnCode($buf) 
	{
		list($response) = explode("\n", $buf, 2);
		if (preg_match("|^HTTP/\d.\d (\d+)|", $response, $matches)) 
		{
			return 0+$matches[1];
		}
		return -1;
	}

	function dfputs($fp, $msg)
	{
		return fputs($fp, $msg);
	}

	function sslToHost($host,$method,$path,$headers='',$data='',$useragent=0)
	{
		$data .= "&accountType=HOSTED_OR_GOOGLE";
		if (empty($method)) 
		{
			$method = 'GET';
		}
		$method = strtoupper($method);
		$fp = fsockopen("ssl://$host", 443, $errno, $err);

		if (FALSE === $fp) 
		{
			debugg("Error $err opening socket!");
			exit(1);
		}

		if ($method == 'GET') 
		{
			$path .= '?' . $data;
		}
		dfputs($fp, "$method $path HTTP/1.0\r\n");
		dfputs($fp, "Host: $host\r\n");
		dfputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
		dfputs($fp, "Content-length: " . strlen($data) . "\r\n");
		dfputs($fp, "accountType: HOSTED_OR_GOOGLE ");

		if (strlen($headers)) 
		{
			dfputs($fp, "$headers\r\n");
		}

		if ($useragent) 
		{
			dfputs($fp, "User-Agent: MSIE\r\n");
		}
		dfputs($fp, "Connection: close\r\n\r\n");
		if ($method == 'POST') 
		{
			dfputs($fp, $data);
		}

		while (!feof($fp)) 
		{
			$buf .= @fgets($fp,128);
		}
		fclose($fp);
		return $buf;
	}

	function sendToHost($host,$method,$path,$headers='',$data='',$useragent=0)
	{
		if (empty($method)) 
		{
			$method = 'GET';
		}
		$method = strtoupper($method);
		$fp = fsockopen($host, 80);
		if ($method == 'GET' && strlen($data)>0) 
		{
			$path .= '?' . $data;
		}
		dfputs($fp, "$method $path HTTP/1.0\r\n");
		dfputs($fp, "Host: $host\r\n");
		if ($method == 'GET' && strlen($data)>0) 
		{
			dfputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
		}
		dfputs($fp, "Content-length: " . strlen($data) . "\r\n");
		if (strlen($headers)) 
		{
			dfputs($fp, "$headers\r\n");
		}

		if ($useragent) 
		{
			dfputs($fp, "User-Agent: MSIE\r\n");
		}
		dfputs($fp, "Connection: close\r\n\r\n");
		if ($method == 'POST') 
		{
			dfputs($fp, $data);
		}

		while (!feof($fp)) 
		{
			$buf .= fgets($fp,128);
		}
		fclose($fp);
		return $buf;
	}

	function curlToHost($url,$method,$headers=array(''),$fCookieFile="")
	{
		ob_start();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch,    CURLOPT_VERBOSE, 1); ########### debug
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); // follow redirects recursively

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 16); // follow redirects recursively
		curl_setopt($ch, CURLOPT_TIMEOUT, 32); // follow redirects recursively

		if ($fCookieFile!="")
		{
			curl_setopt($ch, CURLOPT_COOKIEJAR, $fCookieFile); // follow redirects recursively        
			curl_setopt($ch, CURLOPT_COOKIEFILE, $fCookieFile); // follow redirects recursively 
		}

		$aa = curl_exec($ch);
		curl_close($ch);
		$result_info=curl_getinfo($ch);
		$ret = ob_get_contents(); 
		ob_end_clean();
		debugg("curlToHost result ".$ret);  
		return $ret;
	}

	function curlToHost_v2($url,$method,$headers=array(''),$fCookieFile)
	{
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch,    CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,    CURLOPT_VERBOSE, 1); ########### debug
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); // follow redirects recursively

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8); // follow redirects recursively
		curl_setopt($ch, CURLOPT_TIMEOUT, 8); // follow redirects recursively
		curl_setopt($ch, CURLOPT_COOKIEJAR, $fCookieFile); // follow redirects recursively        
		curl_setopt($ch, CURLOPT_COOKIEFILE, $fCookieFile); // follow redirects recursively       
		$aa = curl_exec($ch);
		curl_close($ch);
		$ret=$aa;
		return $ret;
	}

	function getAuthCode($user, $pword, $service='cl')
	{
		if (isset($_REQUEST[$user]) and $_REQUEST[$user]!="")
		{
			return $_REQUEST[$user]; 
		}

		if (isset($_REQUEST['cal_auth_code'][$user]))
		{
			return $_REQUEST['cal_auth_code'][$user];
		}

		$buf=sslToHost('www.google.com',
		'post', 
		'/accounts/ClientLogin', 
		'', 
		'Email='.$user.'&Passwd='.$pword.'&service='.$service.'&source=phord-gcal.php-1&accountType=HOSTED_OR_GOOGLE');

		$code = returnCode($buf);
		if ($code === 200) 
		{
			$lines=explode("\n", $buf);
			foreach ($lines as $line) 
			{
				if (preg_match("/^Auth=(\S+)/i", $line, $matches)) 
				{
					$_REQUEST[$user]= $matches[1];
					$_REQUEST['cal_auth_code'][$user]=$matches[1]; 
					return $matches[1];
				}
			}
		}
		else
		{
			echo "<br>The system could NOT send events from Google Calendar to Sugar sucessfully. Please check your Google login credentials.. ";
			debugg("Error ($code) retrieving auth code");
		}    
	}

	function compile_post_data($post_data)
	{
		$o="";
		if(!empty($post_data))
		foreach ($post_data as $k=>$v)
		$o.= $k."=".urlencode($v)."&";
		return substr($o,0,-1);
	}

	function read_header($ch, $header) 
	{	
		$curl->headers[] = $header; 
		return strlen($header); 
	} 

?>