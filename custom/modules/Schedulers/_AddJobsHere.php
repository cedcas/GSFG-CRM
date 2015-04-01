<?php
/*
* 8/18/2012 - CPC
* BT# 46 - Update of Days Left for Birthday field in Leads (job 106 updateBirthdayDaysLeft)
*
* 6/28/2013 - CPC
* Created job strings for 105, 106 & 107 in order to properly schedule
* all those jobs;
*
* 2/4/2014 - CPC
* Create job string 108 in order to schedule updateDaysSinceInitialContact;
*
* 3/5/2015 - CPC
* updateSeminarDaysLeft()
* Modified the query to ONLY update the records that has 1, 2, 3, 4, 5 days left prior to seminar;  This [a] minimizes the 
* number of records that needs to be updated and [b] it eventually takes all records down to '0' and not leave them
* straggling with a positive number;
*
* 3/19/2015 - CPC
* Moved updateSeminarDaysLeft() and updateAnniversaryDaysLeft() outside of this page due to issues encountered such as not getting executed;
*
* 4/1/2015 - CPC
* Added createAnniversaryPDFandTask.php
*
*/
				var_dump($job_strings);
				$job_strings[]='runProcessManager';
				var_dump($job_strings);
				function runProcessManager() {
				require_once('modules/PM_ProcessManager/ProcessManagerEngine.php');
				$processManager = new ProcessManagerEngine();
				$processManager->processManagerMain();
				return true;
				} 



$job_strings['100'] = 'updateSeminarDaysLeft';
$job_strings['101'] = 'updateAnniversaryDaysLeft';
$job_strings['102'] = 'getMeetingIds';
$job_strings['103'] = 'getMeetingAttendees';
$job_strings['104'] = 'mergeAttendees';
$job_strings['105'] = 'autoAssignOfUser';
$job_strings['106'] = 'updateClientCallDaysLeft';
$job_strings['107'] = 'updateBirthdayDaysLeft';
$job_strings['108'] = 'updateSpouseBirthdayDaysLeft';
$job_strings['109'] = 'updateDaysSinceInitialContact';
$job_strings['110'] = 'createAnniversaryPDFandTask';


function updateSeminarDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateSeminarDaysLeft.php')) {
        require('custom/modules/Schedulers/updateSeminarDaysLeft.php');
    }
    
    return true;
}


function updateAnniversaryDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateAnniversaryDaysLeft.php')) {
        require('custom/modules/Schedulers/updateAnniversaryDaysLeft.php');
    }
    
    return true;
}

function updateAnnivDateTest() {
    if (file_exists('custom/modules/Schedulers/updateAnnivDateTest.php')) {
        require('custom/modules/Schedulers/updateAnnivDateTest.php');
    }
    
    return true;
}

function getMeetingIds() {
    if (file_exists('custom/modules/Schedulers/getMeetingIds.php')) {
        require('custom/modules/Schedulers/getMeetingIds.php');
    }
    
    return true;
}

function getMeetingAttendees() {
    if (file_exists('custom/modules/Schedulers/getMeetingAttendees.php')) {
        require('custom/modules/Schedulers/getMeetingAttendees.php');
    }
    
    return true;
}

function mergeAttendees() {
    if (file_exists('custom/modules/Schedulers/mergeAttendees.php')) {
        require('custom/modules/Schedulers/mergeAttendees.php');
    }
    
    return true;
}

function updateBirthdayDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateBirthdayDaysLeft.php')) {
        require('custom/modules/Schedulers/updateBirthdayDaysLeft.php');
    }
    
    return true;
}

function autoAssignOfUser() {
    if (file_exists('custom/modules/Schedulers/autoAssignOfUser.php')) {
        require('custom/modules/Schedulers/autoAssignOfUser.php');
    }
    
    return true;
}

function updateClientCallDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateClientCallDaysLeft.php')) {
        require('custom/modules/Schedulers/updateClientCallDaysLeft.php');
    }
    
    return true;
}

function updateSpouseBirthdayDaysLeft() {
    if (file_exists('custom/modules/Schedulers/updateSpouseBirthdayDaysLeft.php')) {
        require('custom/modules/Schedulers/updateSpouseBirthdayDaysLeft.php');
    }
    
    return true;
}

function updateDaysSinceInitialContact() {
    if (file_exists('custom/modules/Schedulers/updateDaysSinceInitialContact.php')) {
        require('custom/modules/Schedulers/updateDaysSinceInitialContact.php');
    }
    
    return true;
}

function createAnniversaryPDFandTask() {
    if (file_exists('custom/modules/Schedulers/createAnniversaryPDFandTask.php')) {
        require('custom/modules/Schedulers/createAnniversaryPDFandTask.php');
    }
    
    return true;
}
?>