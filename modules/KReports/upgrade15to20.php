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
global $db;

$reports = $db->query("select * from kreports");

while($thisReport = $db->fetchByAssoc($reports))
{
	$updateFlag = false;
	
	$whereGroups = json_decode(html_entity_decode($thisReport['wheregroups']));
	
	if(is_array($whereGroups) && count($whereGroups) > 0)
	{
		for($i = 0; $i < count($whereGroups); $i++)
		{
			if($whereGroups[$i]->unionid == null)
			{
				$whereGroups[$i]->unionid = 'root';
				$updateFlag = true;
			}
		}
	}
	
	
	$whereConditions = json_decode(html_entity_decode($thisReport['whereconditions']));
	if(is_array($whereConditions) && count($whereConditions) > 0)
	{
		for($i = 0; $i < count($whereConditions); $i++)
		{
			if($whereConditions[$i]->unionid == null)
			{
				$whereConditions[$i]->unionid = 'root';
				$updateFlag = true;
			}
		}
	}
	
	
	if($updateFlag)
	{
		$query = "update kreports set wheregroups = '" . json_encode($whereGroups) . "', whereconditions = '" . json_encode($whereConditions) . "' where id = '" . $thisReport['id'] . "'";
		$db->query($query);
	}
}
?>