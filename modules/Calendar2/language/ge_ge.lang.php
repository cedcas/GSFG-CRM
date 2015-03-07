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
$mod_strings = array (
  'LBL_MODULE_NAME'=>'Calendar2',
  'LBL_MODULE_TITLE'=>'Calendar2',
  'LNK_NEW_CALL' => 'Anruf erstellen',
    'LNK_SYNC_BATCH' => 'Sync Batch durchführen',
  'LNK_NEW_MEETING' => 'Meeting erstellen',
  'LNK_NEW_APPOINTMENT' => 'Meeting erstellen',
  'LNK_NEW_TASK' => 'Aufgabe erstellen',
  'LNK_CALL_LIST' => 'Anrufe',
  'LNK_MEETING_LIST' => 'Meetings',
  'LNK_TASK_LIST' => 'Aufgaben',
  'LNK_VIEW_CALENDAR' => 'Heute',
  'LNK_IMPORT_CALLS'=>'Anrufe importieren',
  'LNK_IMPORT_MEETINGS'=>'Meetings importieren',
  'LNK_IMPORT_TASKS'=>'Aufgaben importieren',
  'LBL_CALL' => 'Anruf',
    'LBL_TASK' => 'Aufgabe',
  'LBL_MEETING' => 'Meeting',
  'LBL_DAY' => 'Tag',
  'LBL_YEAR' => 'Jahr',
  'LBL_WEEK' => 'Woche',
  'LBL_MONTH' => 'Monat',
  'LBL_PREVIOUS_MONTH' => 'Voriger Monat',
  'LBL_PREVIOUS_DAY' => 'Voriger Tag',
  'LBL_PREVIOUS_YEAR' => 'Voriges Jahr',
  'LBL_PREVIOUS_WEEK' => 'Vorige Woche',
  'LBL_NEXT_MONTH' => 'Nächster Monat',
  'LBL_NEXT_DAY' => 'Nächster Tag',
  'LBL_NEXT_YEAR' => 'Nächstes Jahr',
  'LBL_NEXT_WEEK' => 'Nächste Woche',
  'LBL_AM' => 'AM',
  'LBL_PM' => 'PM',
  'LBL_SCHEDULED' => 'Gebucht',
  'LBL_SETTINGS' => 'Calendar2 Einstellungen',
  'LBL_DEFAULT_ACTIVITY' => 'Default Aktivität',
  'LBL_ACTIVITY_DISPLAY' => 'Nur Aktivität anzeigen',
  'LBL_CALL_SHOW' => 'Nur Anrufe anzeigen',
  'LBL_MEETING_SHOW' => 'Nur Meetings anzeigen',
  'LBL_BOTH_SHOW' => 'Beides anzeigen',
  'LBL_BUSY' => 'Belegt',
  'LBL_CONFLICT' => 'Terminkonflikt',
  'LBL_USER_CALENDARS' => 'Benutzerkalender',
  'LBL_SHARED' => 'Gemeinsam',
  'LBL_PREVIOUS_SHARED' => 'Zurück',
  'LBL_NEXT_SHARED' => 'Weiter',
  'LBL_SHARED_CAL_TITLE' => 'Gemeinsame Kalender',
  
  'LBL_SHAREDMONTHLY' => 'Gemeinsamer Monatskalender',
  'LBL_SHAREDMONTHLY_CAL_TITLE' => 'Gemeinsamer Monatskalender',  
  'LBL_PREVIOUS_SHAREDMONTHLY' => 'Zurück',
  'LBL_NEXT_SHAREDMONTHLY' => 'Nächster',  
 
  
  'LBL_USERS' => 'Benutzer',
  'LBL_REFRESH' => 'Aktualisieren',
  'LBL_EDIT' => 'Bearbeiten',
  'LBL_SELECT_USERS' => 'Benutzer für Anzeige wählen',
  'LBL_FILTER_BY_TEAM' => 'Benutzerliste nach Team filtern:',
  'LBL_ASSIGNED_TO_NAME' => 'Zugewiesen an',
  'LBL_DATE' => 'Star Datum u. Zeit',
  'LNK_RESOURCE_LIST' => 'Ressourcen',
  'LNK_NEW_RES' => 'Neue Ressource',
  'LNK_RES_CAL' => 'Ressourcenkalender',
  'LBL_YES' => 'Ja',
  'LBL_NO' => 'Nein',
  'LBL_CREATE_NEW_RECORD' => 'Neuer Satz erstellen',
  'LBL_LOADING' => 'Lädt.........',
  'LBL_EDIT_RECORD' => 'Satz bearbeiten',
  'LBL_ERROR_SAVING' => 'Fehler beim Speichern',
  'LBL_ERROR_LOADING' => 'Fehler beim Laden',
  'LBL_ANOTHER_BROWSER' => 'Verwenden Sie einen anderen Browser, um weitere Teams zu erstellen.',
  'LBL_FIRST_TEAM' => 'Der erste Team kann nicht entfernt werden.',
  'LBL_REMOVE_PARTICIPANTS' => 'Sie können nicht alle Teilnehmern entfernen.',
  'LBL_START_DAY' => 'Woche Starttag:',
  'LBL_START_TIME' => 'Startzeit:',
  'LBL_END_TIME' => 'Endzeit:',
  'LBL_DURATION' => 'Dauer:',
  'LBL_NAME' => 'Name:',
  'LBL_DESCRIPTION' => 'Beschreibung:',
  'LBL_LOCATION' => 'Ort:',
  'LBL_ADDITIONAL_DETAIL' => 'Zusatzdetails:',
  'LBL_SHOW_TASKS' => 'Aufgaben zeigen:',
  'LBL_AUTO_ACCEPT' => 'Termin automatisch annehmen?:',
  'LBL_GCAL' => 'Google Settings',  
  'LBL_GENERAL' => 'Allgemeines',
  'LBL_PARTICIPANTS' => 'Teilnehmer',
  'LBL_INV_CONTACT' => 'Kontakte hinzufügen',
  'LBL_RECURENCE' => 'Wiederholung',
  'LBL_SAVE_BUTTON' => 'Speichern',
  'LBL_APPLY_BUTTON' => 'Anwenden',
  'LBL_CANCEL_BUTTON' => 'Abbrechen',
  'LBL_DELETE_BUTTON' => 'Löschen',
  'LBL_TODAY' => 'Heute',
  'LBL_NONE' => 'Keine',
  'LBL_SHOW_SEARCH' => 'Suche anzeigen',
  'LBL_HIDE_SEARCH' => 'Suche verstecken',
  'MSG_CANNOT_REMOVE_FIRST' => 'Der erste Element eines Wiederholtermines kann nicht gelöscht werden. Bitte der Zeitplan bearbeiten und specihern.',
  'MSG_REMOVE_CONFIRM' => 'Wollen Sie wirklich diesen Datensatz löschen?',
  'MSG_CANNOT_HANDLE_YEAR' => 'Der Kalender kann diese Jahr nicht bearbeiten',
  'MSG_CANNOT_HANDLE_YEAR2' => 'Jahr muss zwischen 1970 und 2037 liegeen',
  'LBL_NOTE' => 'Notiz',
  'LNK_NEW_CALNOTE' => 'Neue Notiz',
   'LBL_GCAL_USERNAME' => 'Benutzer:',
  'LBL_GCAL_PASSOWRD' => 'Passwort:',
  'LBL_GCAL_SYNC_OPT' => 'Sync Optionen:',
  'LBL_GCAL_SYNC_OPT1' => 'Zwei Richtungen (Von Calendar2 zu Google, und Google zu Calendar2)',
  'LBL_GCAL_SYNC_OPT2' => 'Eine Richtung (Von Calendar2 zu Google)',
  'LBL_GCAL_SYNC_OPT3' => 'Eine Richtung (Von Google zu Calendar2)',
  'LBL_GCAL_PRIORITY' => 'Priorität:',
  'LBL_GCAL_PRIORITY1' => 'Google',
  'LBL_GCAL_PRIORITY2' => 'Calendar2',
  'LBL_GCAL_SYNC_MOD' => 'Sync Modules',
  'LBL_GCAL_INTERVAL' => 'Intervall:',  
  'LBL_GCAL_TIE_SLOT' => 'Zeitfenster:',  
  'LBL_ACCEPT_STATUS' => 'Status:',  
  'LBL_TIME_SLOT_ALERT' => 'Sie haben Termine vor bzw. nach diesem Zeitfenster.',  
);

$mod_list_strings = array(
'dom_cal_weekdays'=>array(
"Son",
"Mon",
"Die",
"Mit",
"Don",
"Fre",
"Sam",
),
'dom_cal_weekdays_long'=>array(
"Sonntag",
"Montag",
"Dienstag",
"Mittwoch",
"Donnerstag",
"Freitag",
"Samstag",
),
'dom_cal_month'=>array(
"",
"Jan",
"Feb",
"Mär",
"Apr",
"Mai",
"Jun",
"Jul",
"Aug",
"Sep",
"Okt",
"Nov",
"Dez",
),
'dom_cal_month_long'=>array(
"",
"Januar",
"Februar",
"März",
"April",
"Mai",
"Juni",
"Juli",
"August",
"September",
"Oktober",
"November",
"Dezember",
)
);
?>
