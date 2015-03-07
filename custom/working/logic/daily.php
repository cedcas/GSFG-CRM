<?php
// Date: 	3/2/2011
// Author: 	Cedric P. Castillo
// Interval: 	every 24 hours / midnight
// Note:	This DB update is called by scheduler

	// The following SQL update will update the "days left to seminar" field in the LEADS_CSTM table		
	//	const CUSTOM_FIELD1 = "bin_type_id";		// Custom field 	
	//	const CUSTOM_FIELD2 = "bin_type_id";		// Custom field					
	//	const CUSTOM_TABLE = "leads_cstm"; 		// Custom table where the custom fields are located							
	
		$db =  DBManagerFactory::getInstance();					
																	
		// Update the record that was just saved								
		//$update_query = "UPDATE ".self::CUSTOM_TABLE." SET ".self::CUSTOM_FIELD1." = '$new_code'								
		//				 WHERE id = '{$bean->id}' AND (".self::CUSTOM_FIELD1." = '' or ".self::CUSTOM_FIELD1." IS NULL)";				
						 				
		$update_query = "UPDATE leads_cstm
				SET `seminar_days_left_c` = DATEDIFF(STR_TO_DATE( `seminar_date_c` , '%m/%d/%Y' ),CURDATE())
				WHERE 	STR_TO_DATE( `seminar_date_c` , '%m/%d/%Y' ) > CURDATE() and 
					STR_TO_DATE( `seminar_date_c` , '%m/%d/%Y' )<>'0000-00-00' and
				      	(`seminar_date_c` is not null or `seminar_date_c` <>'')";
													 				 
		$db->query($update_query, true);								


?>