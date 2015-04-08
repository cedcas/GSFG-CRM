<?php

/*************************************
Project: Goldstone
Original Dev: Angel Magaña, March 2011
@2011 Angel Magaña
cheleguanaco[at]cheleguanaco.com

Desc: Functions for ages 

The contents of this file are governed by the GNU General Public License (GPL).
A copy of said license is available here: http://www.gnu.org/copyleft/gpl.html
This code is provided AS IS and WITHOUT WARRANTY OF ANY KIND.

*************************************/

function getAgeMain($focus, $field, $value, $view)
{	
	$age = 0;
	
	$id = $focus->id;
	
	$query = "SELECT FLOOR(DATEDIFF(NOW(), birthdate) / 365.25) AS age FROM leads WHERE id = '$id' ";
	
	$results = $focus->db->query($query, true);
	$row = $focus->db->fetchByAssoc($results);
	
	$age = $row['age'];

	return $age;
}

function getAgeSpouse($focus, $field, $value, $view)
{	
	$age = 0;
	
	$id = $focus->id;
	
	$query = "SELECT FLOOR(DATEDIFF(NOW(), spouse_birthday) / 365.25) AS age FROM leads WHERE id = '$id' ";
	
	$results = $focus->db->query($query, true);
	$row = $focus->db->fetchByAssoc($results);
	
	$age = $row['age'];

	return $age;
}

?>