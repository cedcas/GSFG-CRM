<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
$t_step = 60;

require_once("modules/Calendar2/PageComm.php");

if (!function_exists("add_zero")) {
	function add_zero($t){
		if($t < 10)
			return "0" . $t;
		else
			return $t;
	}

}

?>
<style type="text/css">
		.day_col, .left_time_col{
			border-bottom-width: 2px;	
		}
</style>
<?php


$weekday_names = $GLOBALS['app_list_strings']['dom_cal_day_short'];

$weekEnd1 = 0;
$weekEnd2 = 6;

if($startday == "Monday"){	
	for($d = 1; $d < 7; $d++)
		$weekday_names[$d] = $weekday_names[$d + 1];
	$weekday_names[7] = $GLOBALS['app_list_strings']['dom_cal_day_short'][1];
	$weekEnd1 = 5;
	$weekEnd2 = 6;	
}

$Tw = date("w",$today_unix - date('Z',$today_unix));
$Ti = date("i",$today_unix - date('Z',$today_unix));
$Ts = date("s",$today_unix - date('Z',$today_unix));
$Th = date("H",$today_unix - date('Z',$today_unix));
$Td = date("d",$today_unix - date('Z',$today_unix));
$Tm = date("m",$today_unix - date('Z',$today_unix));
$Ty = date("Y",$today_unix - date('Z',$today_unix));
$Tt = date("t",$today_unix - date('Z',$today_unix));
$Tt = date("z",$today_unix - date('Z',$today_unix));
$TL = date("L",$today_unix - date('Z',$today_unix));
$timezone = $GLOBALS['timedate']->getUserTimeZone();

$diy = 365;
if($TL == 1)
	$diy++;	

$year_start_unix = $today_unix - $Ts - 60*$Ti - 60*60*$Th - 60*60*24*($Tz);// -  $timezone['gmtOffset']*60;
$year_end_unix = $month_start_unix + 60*60*24*($diy);

//$Tw = date("w",$year_start_unix + $timezone['gmtOffset']*60 - date('Z'));
$Tw = date("w",$year_start_unix - date('Z',$year_start_unix));

$week_start_unix = $year_start_unix - 60*60*24*($Tw);

if($startday == "Monday"){
	$week_start_unix = $week_start_unix + 60*60*24;	
}
$week_end_unix = $week_start_unix + 60*60*24*7;
if($startday == "Monday"){
	$week_end_unix = $week_end_unix + 60*60*24;	
}


echo '<table id="daily_cal_table" cellspacing="1" cellpadding="0" border="0" width="100%">';

$curr_time_g = $year_start_unix;

for($m = 0; $m < 12; $m++){
	
	$gmt_g = $date_arr['year'] . "-" . add_zero($m + 1) . "-" . "01";
	$g_parsed = date_parse($gmt_g);
	$g_unix = gmmktime($g_parsed['hour'],$g_parsed['minute'],$g_parsed['second'],$g_parsed['month'],$g_parsed['day'],$g_parsed['year']);
	$Tw = date("w",$g_unix - date('Z',$g_unix));
	$Ti = date("i",$g_unix - date('Z',$g_unix));
	$Ts = date("s",$g_unix - date('Z',$g_unix));
	$Th = date("H",$g_unix - date('Z',$g_unix));
	$Td = date("d",$g_unix - date('Z',$g_unix));
	$Tm = date("m",$g_unix - date('Z',$g_unix));
	$Ty = date("Y",$g_unix - date('Z',$g_unix));
	$Tt = date("t",$g_unix - date('Z',$g_unix));
	$Tz = date("z",$g_unix - date('Z',$g_unix));
	$TL = date("L",$g_unix - date('Z',$g_unix));
	$timezone = $GLOBALS['timedate']->getUserTimeZone();	

	$month_start_unix = $g_unix - $Ts - 60*$Ti - 60*60*$Th - 60*60*24*($Td - 1);// - $timezone['gmtOffset']*60;
	$month_end_unix = $month_start_unix + 60*60*24*($Tt);

	//$Tw = date("w",$month_start_unix + $timezone['gmtOffset']*60 - date('Z',$month_start_unix));
	$Tw = date("w",$month_start_unix - date('Z',$month_start_unix));
	
	$week_start_unix = $month_start_unix - 60*60*24*($Tw);

	if($startday == "Monday"){
		$week_start_unix = $week_start_unix + 60*60*24;	
		if(date("j",$week_start_unix - date('Z',$week_start_unix)) == 1)
			$week_start_unix = $week_start_unix - 7*60*60*24;
	}
	

	if($m % 3 == 0)
		echo "<tr>";
		
			echo '<td class="yearCalBodyMonth" align="center" valign="top" scope="row">';
				echo '<a href="index.php?module=Calendar2&action=index&view=month&&hour=0&day=1&month='.($m+1).'&year='.timestamp_to_user_formated2($month_start_unix,'Y').'">'.$GLOBALS['app_list_strings']['dom_cal_month_long'][$m+1].'</a>';
				
				echo '<table id="daily_cal_table" cellspacing="1" cellpadding="0" border="0" width="100%">';
				
				
					echo '<tr class="monthCalBodyTH">';
						for($d = 0; $d < 7; $d++)
							echo '<th width="14%">'.$weekday_names[$d+1].'</th>';			
					echo '</tr>';
				
					$curr_time_g = $week_start_unix;
					$w = 0;
					while($curr_time_g < $month_end_unix){
						echo '<tr class="monthViewDayHeight yearViewDayHeight">';
							for($d = 0; $d < 7; $d++){
								$curr_time = $week_start_unix + $d*86400 + $w*60*60*24*7;

								if($curr_time < $month_start_unix || $curr_time >= $month_end_unix)
									$monC = "";
								else
									$monC = '<a href="index.php?module=Calendar2&action=index&view=day&hour=0&day='.timestamp_to_user_formated2($curr_time,'j').'&month='.timestamp_to_user_formated2($curr_time,'n').'&year='.timestamp_to_user_formated2($curr_time,'Y').'">'.timestamp_to_user_formated2($curr_time,'j').'</a>';
								
									
								if($d == $weekEnd1 || $d == $weekEnd2)	
									echo "<td class='weekEnd'>"; 
								else
									echo "<td>";				
								
										echo $monC;
									echo "</td>";
							}
						echo "</tr>";
						$curr_time_g += 60*60*24*7;
						$w++;
					}				
				echo '</table>';	
						
			echo '</td>';	
	
	if(($m - 2) % 3 == 0)
		echo "</tr>";	
}

echo "</table>";



?>
