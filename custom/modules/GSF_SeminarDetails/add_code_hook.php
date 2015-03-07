<?php										
										
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');										
										
/**										
 * add_code - Generic Autonumbering Hook										
 * ---------------------------------------------------------------										
 * The purpose of this hook is to generate generic codes for										
 * numbering entries that go into SugarCRM (e.g. new Leads, etc).										
 * This hook is intended to allow for greater flexibility and is										
 * fired after_save.										
 * ---------------------------------------------------------------										
 * Original Concept by Eli Lindner on SugarCRM.com Forums										
 * Revamp by Kris Tremblay - Silence IT										
 * August 10th, 2009										
 *										
 * MODIFICATIONS										
 * Cedric Castillo for CMS Project										
 * 2/1/2011										
 */										
										
class add_code										
{										
	const CODE_PREFIX = "SEMID";				// Prefix (ie. S represents the Sites prefix)					
	const CODE_SEPARATOR = "-";			// Character(s) separating the prefix and the code						
	const MIN_CODE_LENGTH = 5; 			// e.g. 0001, 0002, etc; used to dictate padding						
	const DATE_FORMAT = "y";				// Date format string for part of the prefix (e.g. "y" = 09 in LD09-)					
	const CUSTOM_FIELD1 = "name";		// Custom field to store the code in							
	const CUSTOM_FIELD2 = "";	// Custom field to store the code in								
	//const CUSTOM_FIELD3 = "";	// Custom field to store the code in								
	const CUSTOM_TABLE = "gsf_seminardetails"; 		// Custom table where the custom field is located							
										
										
										
	function add_code(&$bean, $event, $arguments)									
	{									
		$db =  DBManagerFactory::getInstance();								
		// Create the SITE_ID								
			// Create complete prefix for the code (e.g. LD09-)							
			$prefix = self::CODE_PREFIX.date(self::DATE_FORMAT).self::CODE_SEPARATOR;							
										
			// Get the starting position for the SUBSTR call in the query							
			// 2/8/2011 commented out to simplify the code							
			$prefix_len = strlen($prefix) + 1;							
			//$prefix_len = 1							
										
			$query = "SELECT CAST(SUBSTR(".self::CUSTOM_FIELD1.", $prefix_len) AS UNSIGNED) as ".self::CUSTOM_FIELD1." FROM ".self::CUSTOM_TABLE." 							
					  WHERE (".self::CUSTOM_FIELD1." <> '' OR ".self::CUSTOM_FIELD1." IS NOT NULL)					
					  ORDER BY CAST(SUBSTR(".self::CUSTOM_FIELD1.", $prefix_len) AS UNSIGNED) 					
					  DESC 					
					  LIMIT 1";					
										
			$result = $db->query($query, true);							
			$row = $db->fetchByAssoc($result );							
										
			// Increment the highest code by 1 and pad if necessary							
			$code = $row[self::CUSTOM_FIELD1] + 1;							
			$code = str_pad($code, self::MIN_CODE_LENGTH, "0", STR_PAD_LEFT);							
										
			// Put it all together							
			$new_code = $prefix.$code;							
										
		// Allocate the proper TERRITORY								
		//	$query_territory = "SELECT cms_territories_id_c FROM `cms_territoryzips` 							
		//				WHERE `name` = '{$bean->site_zipcode}'";				
										
		//	$result_territory = $db->query($query_territory, true);							
		//	$row = $db->fetchByAssoc($result_territory);							
		//	$code_territory = $row[self::CUSTOM_FIELD2];							
										
										
		// Find out the # of BINS for this SITE in this table: cms_sites_cms_bins_c								
		// Update: Move this to JOB SCHEDULER. This cannot be in the ADD SITE because there will be no BINS.								
		/*	$query_number_of_bins = "SELECT count(*) FROM `cms_sites_cms_bins_c` 							
						WHERE `cms_sites_9e7as_sites_ida` = '{$bean->id}' and				
      						`deleted` = 0";				
										
										
			$result_number_of_bins = $db->query($query_number_of_bins, true);							
			$row = $db->fetchByAssoc($result_number_of_bins);							
			$code_number_of_bins = $row[self::CUSTOM_FIELD3];							
		*/								
										
		// Update the record that was just saved								
		$update_query = "UPDATE ".self::CUSTOM_TABLE." SET ".self::CUSTOM_FIELD1." = '$new_code'	
						 WHERE id = '{$bean->id}' AND (".self::CUSTOM_FIELD1." = '' or ".self::CUSTOM_FIELD1." IS NULL)";				
						 				
										
						 				 
		$db->query($update_query, true);								
	}									
}										
?>										