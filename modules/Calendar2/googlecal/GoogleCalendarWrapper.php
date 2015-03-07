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

include "modules/Calendar2/googlecal/MyCurl.php"; 


class GoogleCalendarWrapper extends MyCurl 
{

	var $email;
	var $password;
	var $feed_url = "http://www.google.com/calendar/feeds/default/private/full"; 
	var $fAuth;
	var $isLogged = false;
	var $feed_url_prepared;

	function GoogleCalendarWrapper($email, $password,$cusr="",$assigned_link="")
	{
		global $current_user;
		$this->feed_url = "http://www.google.com/calendar/feeds/default/private/full";

		if (isset($current_user->google_mcalendar_c) and trim($current_user->google_mcalendar_c)!='' and trim($current_user->google_mcalendar_c)!='1')
		{
			$this->feed_url =  $current_user->google_mcalendar_c;
		}
		if (isset($cusr->google_mcalendar_c) and trim($cusr->google_mcalendar_c)!='' and trim($cusr->google_mcalendar_c)!='1')
		{
			$this->feed_url =  $cusr->google_mcalendar_c;
		}
		if ($assigned_link!="")
		{
			$this->feed_url =  $assigned_link; 
		}
		$this->email = $email;
		$this->password = $password;
		$this->feed_url_prepared = $this->feed_url;
		parent::MyCurl();
	}

	//login with Google's technology of "ClientLogin"
	//check here: http://code.google.com/apis/accounts/AuthForInstalledApps.html
	function login1()
	{
		$this->isLogged = true;
	}

	function login()
	{
		if (isset($_REQUEST['cal_auth_code'][$this->email]))
		{
			$this->fAuth=$_REQUEST['cal_auth_code'][$this->email];
			$this->isLogged = true;
			return 1;
		}
		$this->fCookieFile=$this->email;  
		debugg("new cookie".$this->email);
		$post_data = array();
		$post_data['Email']  = $this->email;
		$post_data['Passwd'] = $this->password;
		$post_data['source'] = "exampleCo-exampleApp-1";
		$post_data['service'] = "cl";
		$post_data['accountType'] = "HOSTED_OR_GOOGLE";

		$this->getHeaders = true;
		$this->getContent = true;

		$http_code = "";
		$response = $this->post_login("https://www.google.com/accounts/ClientLogin", $post_data, null, $http_code);
		$http_code=$this->returnCodeGC($response);        
		if($http_code==200 or $http_code=="HTTP/1.1 200 OK")
		{
			$this->fAuth = parent::get_parsed($response, "Auth=");
			$this->isLogged = true;
			$_REQUEST['cal_auth_code'][$this->email]=$this->fAuth;
			return 1;
		}
		else
		{
			if($_REQUEST['SyncMyEventsFromGoogle'])
			{
				echo "<br>The system could NOT send events from Google Calendar to Sugar sucessfully. Please check your Google login credentials.";
				die();
			}
			if($_REQUEST['ou']==1)
			{
				exit;
			}
		}
		$this->isLogged = false;
		return 0;
	}

	function returnCodeGC($buf) 
	{
		list($response) = explode("\n", $buf, 2);
		if (preg_match("|^HTTP/\d.\d (\d+)|", $response, $matches)) 
		{
			return 0+$matches[1];
		}
		return -1;
	}

	//to make the feed URL writable, it should be ended with "private/full"
	//check this: http://code.google.com/apis/gdata/calendar.html#get_feed
	function prepare_feed_url()
	{
		$url = parse_url($this->feed_url);
		$path = explode("/", $url["path"]);
		$size = sizeof($path);
		if($size>4)
		{
			$path[$size-1] = "full";
			$path[$size-2] = "private";
			$path = implode("/", $path);
		}
		$this->feed_url_prepared = $url["scheme"]."://".$url["host"].$path;
	}

	//adds new event into calendar
	//filled $settings array should be provided
	function get_all_cts_list($user_data,$docid,$sugarid,$google_mdocsafterdater_c,$limit,$current_user)
	{
		$this->prepare_feed_url();
		$this->get_all_contacts_list($user_data,$docid,$sugarid,$google_mdocsafterdater_c,$limit,$current_user);
	}
	
	function add_cnt_onl($file,$filename,$file_ext,$file_mime_type,$user_data,$evid,$bean)
	{
		$this->prepare_feed_url();
		$this->insert_cnt_onl($file,$filename,$file_ext,$file_mime_type,$user_data,$evid,$revision,$bean);
	}
	
	function delete_cnt_onl($file,$filename,$file_ext,$file_mime_type,$user_data,$evid,$bean)
	{
		$this->prepare_feed_url();
		$this->delete_contact_onl($file,$filename,$file_ext,$file_mime_type,$user_data,$evid,$revision,$bean);
	}

	function add_event($settings,$evid,$tablename,$user_data,$add_notifications)
	{
		global $offset_val;
		if(!$this->isLogged)
			$this->login();

		if($this->isLogged)
		{
			if ($tablename=="tasks")
			{
				$mmod = new Task;
			}
			if ($tablename=="meetings")
			{
				$mmod = new Meeting;
			}
			if ($tablename=="calls")
			{
				$mmod = new Call;
			}
			$mmod->disable_row_level_security=true;
			$mmod->retrieve($evid)  ;   
			$str = $mmod->google_response_c ;
			$newtbname = $tablename."_cstm"   ;

			if ($settings['reminder_checked']=="1")
			{
			}
			$settings["reminder_time"]=trim($settings["reminder_time"]);

			IF (($settings["reminder_time"]=='' or $settings["reminder_time"]<0) and $settings["reminder_checked"]=="1")
			{
				$settings["reminder_time"] = 10;  
			}
			if ($mmod->old_id_c!="")
			{
				$old_id=$mmod->old_id_c;
				$old_published=$mmod->old_published_c;
				$old_updated=$mmod->old_updated_c;
				$old_link_alt=$mmod->old_link_alt_c;
				$old_link_self=$mmod->old_link_self_c;
				$old_link_edit=$mmod->old_link_edit_c;
				$old_author=$mmod->old_author_c;
				$old_email=$mmod->old_email_c;
				$settings["reminder_time"] = 10; 
				$offset_val=".000+03:00"; 
				$offset_val=".000+00:00";  
				if ($add_notifications=="on" and $settings["reminder_time"]>0)
				{
					if ($settings["reminder_time"]==1)
					{
						$settings["reminder_time"]=5;
					}
					$remindersettings="<gd:reminder minutes='".$settings["reminder_time"]."' method='email'/><gd:reminder minutes='".$settings["reminder_time"]."' method='sms'/>";
				}
				else
				{
					$remindersettings="";
				}
				$putextended="<gd:extendedProperty name='event_type' value='".$mmod->table_name."' /><gd:extendedProperty name='sugar_id' value='".$mmod->id."' /><gd:extendedProperty name='sugar_date' value='".$mmod->date_modified."' />";
				$_entry="<?xml version='1.0' encoding='UTF-8'?><entry xmlns='http://www.w3.org/2005/Atom' xmlns:batch='http://schemas.google.com/gdata/batch' xmlns:gCal='http://schemas.google.com/gCal/2005' xmlns:gd='http://schemas.google.com/g/2005'><id>$old_id</id><published>$old_published</published><updated>$old_updated</updated><category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'/><title type='text'>".$settings["title"]."</title><content type='text'>".$settings["content"]."</content><link rel='alternate' type='text/html' href='$old_link_alt' title='alternate'/><link rel='self' type='application/atom+xml' href='$old_link_self'/><link rel='edit' type='application/atom+xml' href='$old_link_edit'/><author><name>".$user_data[0]."</name><email>".$user_data[0]."</email></author><gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'/><gd:visibility value='http://schemas.google.com/g/2005#event.default'/><gd:transparency value='http://schemas.google.com/g/2005#event.opaque'/><gd:when startTime='".$settings["startDay"]."T".$settings["startTime"]."".$offset_val."' endTime='".$settings["endDay"]."T".$settings["endTime"].$offset_val."'>".$remindersettings."</gd:when><gd:who rel='http://schemas.google.com/g/2005#event.organizer' valueString='$old_author' email='$old_email'/><gd:where valueString='".$settings["where"]."'/>".$putextended."</entry>";
				debugg("function :add_event send to google".$_entry);

				$header = "";
				$this->prepare_feed_url();
				$http_code = "";
				$this->post_edit($this->feed_url_prepared, null, $header, $http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data,$old_id);
			}
			else
			{
				$offset_val=".000+03:00"; 
				$offset_val=".000+00:00";   
				$settings["reminder_time"] = 10;

				if ($add_notifications=="on" and $settings["reminder_time"]>0)
				{
					if ($settings["reminder_time"]==1)
					{
						$settings["reminder_time"]=5;
					}
					$remindersettings="         <gd:reminder minutes='".$settings["reminder_time"]."' method='email'></gd:reminder>
					<gd:reminder minutes='".$settings["reminder_time"]."' method='sms'></gd:reminder>";
				}
				else
				{
					$remindersettings="";
				}

				$_entry  = "<entry xmlns='http://www.w3.org/2005/Atom' xmlns:gd='http://schemas.google.com/g/2005'>
				<category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'></category>
				<title type='text'>".$settings["title"]."</title>
				<content type='text'>".$settings["content"]."</content>
				<author>
				<name>".$this->email."</name>
				<email>".$this->email."</email>
				</author>
				<gd:transparency
				value='http://schemas.google.com/g/2005#event.opaque'>
				</gd:transparency>   
				<gd:eventStatus
				value='http://schemas.google.com/g/2005#event.confirmed'>
				</gd:eventStatus>
				<gd:where valueString='".$settings["where"]."'></gd:where>
				<gd:when startTime='".$settings["startDay"]."T".$settings["startTime"].$offset_val."'
				endTime='".$settings["endDay"]."T".$settings["endTime"].$offset_val."'>
				".$remindersettings."</gd:when>
				<gd:extendedProperty name='event_type' value='".$mmod->table_name."' />
				<gd:extendedProperty name='sugar_id' value='".$mmod->id."' />
				<gd:extendedProperty name='sugar_date' value='".$mmod->date_entered."' />
				</entry>";

				$this->prepare_feed_url();

				$http_code = "";
				$header = array();
				$header[] = "Host: www.google.com";
				$header[] = "MIME-Version: 1.0";
				$header[] = "Accept: text/xml";
				$header[] = "Authorization: GoogleLogin auth=".$this->fAuth;
				$header[] = "Content-length: ".strlen($_entry);
				$header[] = "Content-type: application/atom+xml";
				$header[] = "Cache-Control: no-cache";
				$header[] = "X-Http-Method: PUT ";
				$header[] = "Connection: close \r\n";
				$header[] = $_entry;
				$this->post($this->feed_url_prepared, null, $header, $http_code,$evid,$tablename,$user_data);
			}
		}
	}

	function delete_event($settings,$evid,$tablename,$user_data,$add_notifications)
	{
		global $offset_val;
		if(!$this->isLogged)
			$this->login();

		if($this->isLogged)
		{
			if ($tablename=="tasks")
			{
				$mmod = new Task;
			}
			if ($tablename=="meetings")
			{
				$mmod = new Meeting;
			}
			if ($tablename=="calls")
			{
				$mmod = new Call;
			}
			$mmod->disable_row_level_security=true;
			$mmod->retrieve($evid)  ;   
			$str = $mmod->google_response_c ;

			$newtbname = $tablename."_cstm"   ;

			if ($mmod->old_id_c!="")
			{
				$old_id=$mmod->old_id_c;
				$old_published=$mmod->old_published_c;
				$old_updated=$mmod->old_updated_c;
				$old_link_alt=$mmod->old_link_alt_c;
				$old_link_self=$mmod->old_link_self_c;
				$old_link_edit=$mmod->old_link_edit_c;
				$old_author=$mmod->old_author_c;
				$old_email=$mmod->old_email_c;
				$settings["reminder_time"] = 10; 
				$offset_val=".000+03:00"; 
				$offset_val=".000+00:00";  
				if (isset($_REQUEST['delete_invite_user_id']))
				{
					$old_link_edit=$_REQUEST['delete_invite_url']; 
				}
				$_entry="";
				$_entry="<?xml version='1.0' encoding='UTF-8'?><entry xmlns='http://www.w3.org/2005/Atom' xmlns:batch='http://schemas.google.com/gdata/batch' xmlns:gCal='http://schemas.google.com/gCal/2005' xmlns:gd='http://schemas.google.com/g/2005'><id>$old_id</id><published>$old_published</published><updated>$old_updated</updated><category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'/><title type='text'>".$settings["title"]."</title><content type='text'>".$settings["content"]."</content><link rel='alternate' type='text/html' href='$old_link_alt' title='alternate'/><link rel='self' type='application/atom+xml' href='$old_link_self'/><link rel='edit' type='application/atom+xml' href='$old_link_edit'/><author><name>".$user_data[0]."</name><email>".$user_data[0]."</email></author><gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'/><gd:visibility value='http://schemas.google.com/g/2005#event.default'/><gd:transparency value='http://schemas.google.com/g/2005#event.opaque'/><gd:when startTime='".$settings["startDay"]."T".$settings["startTime"]."".$offset_val."' endTime='".$settings["endDay"]."T".$settings["endTime"].$offset_val."'>".$remindersettings."</gd:when><gd:who rel='http://schemas.google.com/g/2005#event.organizer' valueString='$old_author' email='$old_email'/><gd:where valueString='".$settings["where"]."'/></entry>";

				$http_code = "";
				$header = "";
				$this->prepare_feed_url();
				$this->post_delete_onl($this->feed_url_prepared, null, $header, $http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data,$old_id,"");
			}
		}
	}


	function get_event_google($settings,$evid,$tablename,$user_data,$current_user)
	{
		global $offset_val;
		if(!$this->isLogged)
			$this->login();

		if($this->isLogged)
		{
			if ($tablename=="tasks")
			{
				$mmod = new Task;
			}
			if($tablename=="meetings")
			{
				$mmod = new Meeting;
			}
			if($tablename=="calls")
			{
				$mmod->disable_row_level_security=true;
				$mmod = new Call;
			}
			$mmod->retrieve($evid)  ;   
			$str = $mmod->google_response_c ;

			$newtbname = $tablename."_cstm"   ;

			if ($mmod->old_id_c!="")
			{
				$old_id=$mmod->old_id_c;
				$old_published=$mmod->old_published_c;
				$old_updated=$mmod->old_updated_c;
				$old_link_alt=$mmod->old_link_alt_c;
				$old_link_self=$mmod->old_link_self_c;
				$old_link_edit=$mmod->old_link_edit_c;
				$old_author=$mmod->old_author_c;
				$old_email=$mmod->old_email_c;
				$settings["reminder_time"] = 10; 
				$offset_val=".000+03:00"; 
				$_entry="<?xml version='1.0' encoding='UTF-8'?><entry xmlns='http://www.w3.org/2005/Atom' xmlns:batch='http://schemas.google.com/gdata/batch' xmlns:gCal='http://schemas.google.com/gCal/2005' xmlns:gd='http://schemas.google.com/g/2005'><id>$old_id</id><published>$old_published</published><updated>$old_updated</updated><category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'/><title type='text'>".$settings["title"]."</title><content type='text'>".$settings["content"]."</content><link rel='alternate' type='text/html' href='$old_link_alt' title='alternate'/><link rel='self' type='application/atom+xml' href='$old_link_self'/><link rel='edit' type='application/atom+xml' href='$old_link_edit'/><author><name>".$user_data[0]."</name><email>".$user_data[0]."</email></author><gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'/><gd:visibility value='http://schemas.google.com/g/2005#event.default'/><gd:transparency value='http://schemas.google.com/g/2005#event.opaque'/><gd:when startTime='".$settings["startDay"]."T".$settings["startTime"]."".$offset_val."' endTime='".$settings["endDay"]."T".$settings["endTime"].$offset_val."'><gd:reminder minutes='".$settings["reminder_time"]."' method='email'/><gd:reminder minutes='".$settings["reminder_time"]."' method='sms'/></gd:when><gd:who rel='http://schemas.google.com/g/2005#event.organizer' valueString='$old_author' email='$old_email'/><gd:where valueString='".$settings["where"]."'/></entry>";

				$http_code = "";
				$header = "";
				$this->prepare_feed_url();
				$this->get_edit($this->feed_url_prepared, null, $header, $http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data,$old_id,$current_user);
			}
		}
	}

	function add_event_onl($settings,$evid,$tablename,$user_data,$bean,$add_notifications)
	{
		global $offset_val;
		if(!$this->isLogged)
			$this->login();

		if($this->isLogged)
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
			if(isset($bean->google_response_c))
				$str = $bean->google_response_c;
			else
				$str = '';
				
			$newtbname = $tablename."_cstm"   ;
			if($bean->reminder_checked=="1")
			{
				if ($bean->reminder_time>0)
				{
					$settings["reminder_time"]= $bean->reminder_time/60;
				}
				else
				{
					$settings["reminder_time"]= "";
				}
			}
			$settings["reminder_time"]=trim($settings["reminder_time"]);
			IF (($settings["reminder_time"]=='' or $settings["reminder_time"]<0) and $bean->reminder_checked=="1")
			{
				$settings["reminder_time"] = 10;  
			}
			if ($settings["reminder_time"]<0)
			{
				$settings["reminder_time"]="";
			}
			if (isset($_SESSION["mass_upd_assigned"])) 
			{
				if ($_SESSION["mass_upd_assigned"]==true)
				{
					$bean->old_id_c="";
				}
			}

			if ($bean->old_id_c!="")
			{
				$old_id=$bean->old_id_c;
				$old_published=$bean->old_published_c;
				$old_updated=$bean->old_updated_c;
				$old_link_alt=$bean->old_link_alt_c;
				$old_link_self=$bean->old_link_self_c;
				$old_link_edit=$bean->old_link_edit_c;
				$old_author=$bean->old_author_c;
				$old_email=$bean->old_email_c;
				$offset_val=".000+03:00"; 
				$offset_val=".000Z";
				
				if($settings["recurrance"]==1)
				{
$recurrancesettings="<gd:recurrence>
DTSTART;TZID=".$settings['r_startdate']."
DTEND;TZID=".$settings['r_enddate']."
RRULE:FREQ=".$settings['r_freq'].";";
if($settings['r_bday']!="")
$recurrancesettings.="BYDAY=".$settings['r_bday'].";";
if($settings['r_interval']>0)
$recurrancesettings.="INTERVAL=".$settings['r_interval'].";";
$recurrancesettings.="UNTIL=".$settings['r_untill']."
</gd:recurrence>";
				}
				else
				{
					$recurrancesettings="";
				}
				if($settings["reminder_time"]>0)
				{
					if ($settings["reminder_time"]==1)
					{
						$settings["reminder_time"]=5;
					}
					$remindersettings="<gd:reminder minutes='".$settings["reminder_time"]."' method='email'/><gd:reminder minutes='".$settings["reminder_time"]."' method='sms'/>";
				}
				else
				{
					$remindersettings="";
				}
				$putextended="<gd:extendedProperty name='event_type' value='".$bean->table_name."' /><gd:extendedProperty name='sugar_id' value='".$bean->id."' /><gd:extendedProperty name='sugar_date' value='".$bean->date_modified."' />";
				if($recurrancesettings!="")
					$_entry="<?xml version='1.0' encoding='UTF-8'?><entry xmlns='http://www.w3.org/2005/Atom' xmlns:batch='http://schemas.google.com/gdata/batch' xmlns:gCal='http://schemas.google.com/gCal/2005' xmlns:gd='http://schemas.google.com/g/2005'><id>$old_id</id><published>$old_published</published><updated>$old_updated</updated><category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'/><title type='text'>".$settings["title"]."</title><content type='text'>".$settings["content"]."</content><link rel='alternate' type='text/html' href='$old_link_alt' title='alternate'/><link rel='self' type='application/atom+xml' href='$old_link_self'/><link rel='edit' type='application/atom+xml' href='$old_link_edit'/><author><name>".$user_data[0]."</name><email>".$user_data[0]."</email></author><gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'/><gd:visibility value='http://schemas.google.com/g/2005#event.default'/><gd:transparency value='http://schemas.google.com/g/2005#event.opaque'/><gd:when startTime='".$settings["startDay"]."T".$settings["startTime"].$offset_val."' endTime='".$settings["endDay"]."T".$settings["endTime"].$offset_val."'>".$remindersettings."</gd:when><gd:who rel='http://schemas.google.com/g/2005#event.organizer' valueString='$old_author' email='$old_email'/><gd:where valueString='".$settings["where"]."'/>".$recurrancesettings.$putextended."</entry>";
				else
				{
					if($settings["startTime"]!="" and $settings["endTime"]!="")
						$_entry="<?xml version='1.0' encoding='UTF-8'?><entry xmlns='http://www.w3.org/2005/Atom' xmlns:batch='http://schemas.google.com/gdata/batch' xmlns:gCal='http://schemas.google.com/gCal/2005' xmlns:gd='http://schemas.google.com/g/2005'><id>$old_id</id><published>$old_published</published><updated>$old_updated</updated><category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'/><title type='text'>".$settings["title"]."</title><content type='text'>".$settings["content"]."</content><link rel='alternate' type='text/html' href='$old_link_alt' title='alternate'/><link rel='self' type='application/atom+xml' href='$old_link_self'/><link rel='edit' type='application/atom+xml' href='$old_link_edit'/><author><name>".$user_data[0]."</name><email>".$user_data[0]."</email></author><gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'/><gd:visibility value='http://schemas.google.com/g/2005#event.default'/><gd:transparency value='http://schemas.google.com/g/2005#event.opaque'/><gd:when startTime='".$settings["startDay"]."T".$settings["startTime"].$offset_val."' endTime='".$settings["endDay"]."T".$settings["endTime"].$offset_val."'>".$remindersettings."</gd:when><gd:who rel='http://schemas.google.com/g/2005#event.organizer' valueString='$old_author' email='$old_email'/><gd:where valueString='".$settings["where"]."'/>".$putextended."</entry>";
					else
						$_entry="<?xml version='1.0' encoding='UTF-8'?><entry xmlns='http://www.w3.org/2005/Atom' xmlns:batch='http://schemas.google.com/gdata/batch' xmlns:gCal='http://schemas.google.com/gCal/2005' xmlns:gd='http://schemas.google.com/g/2005'><id>$old_id</id><published>$old_published</published><updated>$old_updated</updated><category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'/><title type='text'>".$settings["title"]."</title><content type='text'>".$settings["content"]."</content><link rel='alternate' type='text/html' href='$old_link_alt' title='alternate'/><link rel='self' type='application/atom+xml' href='$old_link_self'/><link rel='edit' type='application/atom+xml' href='$old_link_edit'/><author><name>".$user_data[0]."</name><email>".$user_data[0]."</email></author><gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'/><gd:visibility value='http://schemas.google.com/g/2005#event.default'/><gd:transparency value='http://schemas.google.com/g/2005#event.opaque'/><gd:when startTime='".$settings["startDay"]."' endTime='".$settings["endDay"]."'>".$remindersettings."</gd:when><gd:who rel='http://schemas.google.com/g/2005#event.organizer' valueString='$old_author' email='$old_email'/><gd:where valueString='".$settings["where"]."'/>".$putextended."</entry>";
				}
				$header = "";
				$http_code = "";
				

				
				$this->post_edit_onl($this->feed_url_prepared, null, $header, $http_code,$evid,$tablename,$_entry,$old_link_edit,$user_data,$old_id,$bean);

			}
			else
			{
				if ($settings["reminder_time"]>0)
				{
					if ($settings["reminder_time"]==1)
					{
						$settings["reminder_time"]=5;
					}
					$remindersettings="         <gd:reminder minutes='".$settings["reminder_time"]."' method='email'></gd:reminder>
					<gd:reminder minutes='".$settings["reminder_time"]."' method='sms'></gd:reminder>";
				}
				else
				{
					$remindersettings="";
				}
				if(isset($settings["recurrance"]) && $settings["recurrance"]==1)
				{
$recurrancesettings="
<gd:recurrence>DTSTART;TZID=".$settings['r_startdate']."
DTEND;TZID=".$settings['r_enddate']."
RRULE:FREQ=".$settings['r_freq'].";";
if($settings['r_bday']!="")
$recurrancesettings.="BYDAY=".$settings['r_bday'].";";
if($settings['r_interval']>0)
$recurrancesettings.="INTERVAL=".$settings['r_interval'].";";
$recurrancesettings.="UNTIL=".$settings['r_untill']."
</gd:recurrence>";
				}
				else
				{
					$recurrancesettings="";
				}
				$offset_val=".000Z"; 

				$_entry  = "<entry xmlns='http://www.w3.org/2005/Atom' xmlns:gCal='http://schemas.google.com/gCal/2005' xmlns:gd='http://schemas.google.com/g/2005'><category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'></category><title type='text'>".$settings["title"]."</title>
				<content type='text'>".$settings["content"]."</content>
				<author><name>".$this->email."</name><email>".$this->email."</email></author>
				<gd:transparency value='http://schemas.google.com/g/2005#event.opaque'></gd:transparency>   
				<gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'></gd:eventStatus>
				<gd:where valueString='".$settings["where"]."' />";
				if($recurrancesettings!="")
				{
					$_entry .= $remindersettings.$recurrancesettings;
				}
				else
				{
					if($settings["startTime"]!="" and $settings["endTime"]!="")
						$_entry .= "<gd:when startTime='".$settings["startDay"]."T".$settings["startTime"].$offset_val."' endTime='".$settings["endDay"]."T".$settings["endTime"].$offset_val."'>".$remindersettings."</gd:when>";
					else
						$_entry .= "<gd:when startTime='".$settings["startDay"]."' endTime='".$settings["endDay"]."'>".$remindersettings."</gd:when>";
				}
				$_entry .= "<gd:extendedProperty name='event_type' value='".$bean->table_name."' /><gd:extendedProperty name='sugar_id' value='".$bean->id."' />
				<gd:extendedProperty name='sugar_date' value='".$bean->date_entered."' />
				</entry>";

				debugg("function :add_event_onl - new event send to google".$_entry); 
				$this->prepare_feed_url();
				$header = array();
				$http_code = "";
				$header[] = "Host: www.google.com";
				$header[] = "MIME-Version: 1.0";
				$header[] = "Accept: text/xml";
				$header[] = "Authorization: GoogleLogin auth=".$this->fAuth;
				$header[] = "Content-length: ".strlen($_entry);
				$header[] = "Content-type: application/atom+xml";
				$header[] = "Cache-Control: no-cache";
				$header[] = "X-Http-Method: PUT ";
				$header[] = "Connection: close \r\n";
				$header[] = $_entry;
				$this->post_onl($this->feed_url_prepared, null, $header, $http_code,$evid,$tablename,$user_data,$bean);
			}
		}
	}
}

	function debugg($text)
	{
		
	}

	function get_user_email_pass1($username)
	{
		require_once("modules/Users/User.php");
		$user = new User();
		$user->retrieve($username)  ;
		$a=array($user->google_mmail_c,$user->google_mpass_c);
		return $a;
	}

	function get_user_email_pass_cts($username)
	{
		require_once("modules/Users/User.php");
		$user = new User();
		$user->retrieve($username)  ;

		$a=array($user->google_mmail_cts_c,$user->google_mpass_cts_c);
		return $a;
	}

?>