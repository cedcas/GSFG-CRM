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
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$mod_strings = array (
  'LBL_MODULE_NAME' => 'KINAMU Reports',
  'LBL_REPORT_STATUS' => 'Report Status',
  'LBL_MODULE_TITLE' => 'KINAMU Reports',
  'LBL_SEARCH_FORM_TITLE' => 'Report Search',
  'LBL_LIST_FORM_TITLE' => 'Report List',
  'LBL_NEW_FORM_TITLE' => 'Create Report',
  'LBL_LIST_CLOSE' => 'Close',
  'LBL_LIST_SUBJECT' => 'Title',
  'LBL_DESCRIPTION' => 'Description:',
  'LNK_NEW_REPORT' => 'Create Reports',
  'LNK_REPORT_LIST' => 'Reports',

  'LBL_UNIONTREE' => 'union Modules',
  'LBL_UNIONLISTFIELDS' => 'Union List Fields',
  'LBL_UNIONFIELDDISPLAYPATH' => 'Union Path',
  'LBL_UNIONFIELDNAME' => 'Union Field name',

  'LBL_LIST_MODULE' => 'Module', 
  'LBL_LIST_ASSIGNED_USER_NAME' => 'assigned User',

  'LBL_TARGETLIST_NAME' => 'Target List Name',
  'LBL_TARGETLIST_PROMPT' => 'Name of the new Targetlist',

  'LBL_DUPLIACTE_NAME' => 'Neuer Report Name',
  'LBL_DUPLICATE_PROMPT' => 'Bitte geben Sie den Namen für den neuen Report ein',

  'LBL_DYNAMIC_OPTIONS' => 'Dynamic Selection Options',

  // Grid headers
  'LBL_FIELDNAME' => 'Feldname',
  'LBL_NAME' => 'Name',
  'LBL_OPERATOR' => 'Operator',
  'LBL_VALUE_FROM' => 'gleich/von', 
  'LBL_VALUE_TO' => 'bis',
  'LBL_JOIN_TYPE' => 'erforderlic',
  'LBL_TYPE' => 'Typ',
  'LBL_WIDTH' => 'Breite',
  'LBL_SORTPRIORITY' => 'Sortseq.',
  'LBL_SORTSEQUENCE' => 'Sort',
  'LBL_DISPLAY' => 'Anzeigen', 
  'LBL_LINK' => 'Link',
  'LBL_FIXEDVALUE' => 'fester Wert',
  'LBL_PATH' => 'Pfad', 
  'LBL_SEQUENCE' => 'Sequenz',
  'LBL_GROUPBY' => 'Group by',
  'LBL_SQLFUNCTION' => 'Funktion',
  'LBL_DISPLAYFUNCTION' => 'disp. Funkt.',
  'LBL_USEREDITABLE' => 'editierbar',

   // Title and Headers for Multiselect Popup
   'LBL_MUTLISELECT_POPUP_TITLE' => 'Wählen Sie einen Wert aus',
   'LBL_MULTISELECT_VALUE_HEADER' => 'ID', 
   'LBL_MULTISELECT_TEXT_HEADER' => 'Wert', 
   'LBL_MUTLISELECT_CLOSE_BUTTON' => 'Speichern',
   'LBL_MUTLISELECT_CANCEL_BUTTON' => 'Abbrechen',

   // for the Snapshot Comaprison
   'LBL_SNAPSHOTCOMPARISON_POPUP_TITLE' => 'Chart by Chart',
   'LBL_SNAPSHOTTRENDANALYSIS_POPUP_TITLE' => 'Trend Analyse',
   'LBL_SNAPSHOTCOMPARISON_SNAPHOT_HEADER' => 'Snapshot',
   'LBL_SNAPSHOTCOMPARISON_DESCRIPTION_HEADER' => 'Beschreibung', 
   'LBL_SNAPSHOTCOMPARISON_SELECT_CHART' => 'Wählen Sie ein Chart aus:',
   'LBL_SNAPSHOTCOMPARISON_SELECT_LEFT' => 'linke Quelle:',
   'LBL_SNAPSHOTCOMPARISON_SELECT_RIGHT' => 'rechte Quelle:',
   'LBL_SNAPSHOTCOMPARISON_DATASERIES' => 'Data',
   'LBL_SNAPSHOTCOMPARISON_DATADIMENSION' => 'Dimension',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE' => 'Charttyp',
   'LBL_BASIC_TRENDLINE_BUTTON_LABEL' => 'Trendanalyse',

   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSLINE' => 'Linie',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_STACKEDAREA2D' => 'Fläche',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR2D' => 'Balken 2D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR3D' => 'Balken 3D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN2D' => 'Säulen 2D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN3D' => 'Säulen 3D',

   'LBL_SNAPSHOTCOMPARISON_LOADINGCHARTMSG' => 'Lade Chart',

   // Operator Names
  'LBL_OP_EQUALS' => '=',
  'LBL_OP_NOTEQUAL' => 'â‰ ',
  'LBL_OP_STARTS' => 'beginnt mit',
  'LBL_OP_CONTAINS' => 'enthält',
  'LBL_OP_NOTSTARTS' => 'beginnt nicht mit',
  'LBL_OP_NOTCONTAINS' => 'enthält nicht',
  'LBL_OP_BETWEEN' => 'ist zwischen',
  'LBL_OP_ISEMPTY' => 'ist leer',
  'LBL_OP_ISEMPTYORNULL' => 'is leer oder NULL',
  'LBL_OP_ISNULL' => 'ist NULL',
  'LBL_OP_ISNOTEMPTY' => 'ist nicht leer',
  'LBL_OP_BEFORE' => 'vor',
  'LBL_OP_AFTER' => 'nach',
  'LBL_OP_THISMONTH' => 'dieser Monat',
  'LBL_OP_NEXT3MONTH' => 'in den nächsten drei Monaten',
  'LBL_OP_LASTMONTH' => 'letzer Monat',
  'LBL_OP_LAST3MONTH' => 'in den letten drei Monaten',
  'LBL_OP_THISYEAR' => 'dieses Jahr',
  'LBL_OP_LASTYEAR' => 'letztes Jahr',
  'LBL_OP_GREATER' => '>',
  'LBL_OP_LESS' => '<',
  'LBL_OP_GREATEREQUAL' => '>=',
  'LBL_OP_LESSEQUAL' => '<=',
  'LBL_OP_ONEOF' => 'eines von',
  'LBL_OP_ONEOFNOT' => 'keines von',
  'LBL_OP_ONEOFNOTORNULL' => 'keines von oder NULL',

  // List Limits
  'LBL_LI_TOP10' => 'top 10',
  'LBL_LI_TOP20' => 'top 20',
  'LBL_LI_TOP50' => 'top 50',
  'LBL_LI_TOP250' => 'top 250',
  'LBL_LI_BOTTOM50' => 'bottom 50',
  'LBL_LI_BOTTOM10' => 'bottom 10',
  'LBL_LI_NOLIMIT' => 'no limit',

  // buttons
  'LBL_CHANGE_GROUP_NAME' => 'Change Name of Group',
  'LBL_CHANGE_GROUP_NAME_PROMPT' => 'Name :',
  'LBL_ADD_GROUP_NAME' => 'Create new Group',
  'LBL_ADDTOFAVORITE_BUTTON_LABEL' => 'zu Favoriten', 
  'LBL_REMOVEFAVORITE_BUTTON_LABEL' => 'Favroit löschen',
  'LBL_FAVORITE_NAME' => 'Favoriten Name', 
  'LBL_FAVORITENAME_PROMPT' => 'geben sie den namen für den Favoriten ein', 
  'LBL_SELECTION_CLAUSE' => 'Select Clause: ',
  'LBL_SELECTION_LIMIT' => 'limit List to:',
  'LBL_EDIT_BUTTON_LABEL' => 'ändern',
  'LBL_DELETE_BUTTON_LABEL' => 'löschen',
  'LBL_ADD_BUTTON_LABEL' => 'hinzufügen',
  'LBL_ADDEMTPY_BUTTON_LABEL' => 'Festwert hinzufügen',
  'LBL_DOWN_BUTTON_LABEL' => '',
  'LBL_UP_BUTTON_LABEL' => '',
  'LBL_SNAPSHOT_BUTTON_LABEL' => 'Take Snapshot',
  'LBL_SNAPSHOTMENU_BUTTON_LABEL' => 'Snapshots',
  'LBL_TOOLSMENU_BUTTON_LABEL' => 'Werkzeuge',
  'LBL_EXPORTMENU_BUTTON_LABEL' => 'Export',
  'LBL_COMPARE_SNAPSHOTS_BUTTON_LABEL' => 'Chart by Chart Comparison',
  'LBL_EXPORT_TO_EXCEL_BUTTON_LABEL' => 'EXCEL',
  'LBL_EXPORT_TO_KLM_BUTTON_LABEL' => 'Google Earth KML',
  'LBL_EXPORT_TO_PDF_BUTTON_LABEL' => 'PDF',	
  'LBL_EXPORT_TO_PDFWCHART_BUTTON_LABEL' => 'PDF w. Chart',	
  'LBL_EXPORT_TO_TARGETLIST_BUTTON_LABEL' => 'Targetlist',	
  'LBL_SQL_BUTTON_LABEL' => 'SQL',
  'LBL_DUPLICATE_REPORT_BUTTON_LABEL' => 'Report duplizieren',
  'LBL_PDFORIENTATION' => 'PDF Ausrichtung',
  'LBL_LISTTYPE' => 'List Typ',
  'LBL_CHART_LAYOUTS' => 'Layout',
  'LBL_CHART_TYPE' => 'Typ',
  'LBL_CHART_DIMENSION' => 'Dimension',
  'LBL_CHART_INDEX_LABEL' => 'Chart Index',
  'LBL_CHART_INDEX_EMPTY_TEXT' => 'Chart ID auswählen',
  'LBL_CHART_LABEL' => 'Chart',
  'LBL_CHART_HEIGHT_LABEL' => 'Chart Höhe',
 
  // Chart values
  'LBL_CV_CHARTTYPE' => 'Chart Type', 
  'LBL_CV_TITLE' => 'Title', 
  'LBL_CV_DIMENSION' => 'Dimension', 
  'LBL_CV_COLORSCHEMA' => 'Colortheme',
  'LBL_CV_DATASERIES' => 'Data', 
  'LBL_CV_DIMENSIONX' => 'Dimension X',
  'LBL_CV_DIMENSIONY' => 'Dimension Y',
  'LBL_CV_SHOWEMPTY' => 'show empty values',
  'LBL_CV_SHOWVALUES' => 'display Values',
  'LBL_CV_SHOWPERCENTVALUES' => 'display Percentvalues',
  'LBL_CV_SHOWNAMES' => 'display Names',
  'LBL_CV_SHOWLEGEND' => 'display Legend',
  'LBL_CV_SCALENUMBERS' => 'Zahlen skalieren',
  'LBL_CV_SHOWDECIMALS' => 'Dezimalstellen anzeigen',
  'LBL_CV_PLOTFILLRATIO' => 'Plot Fill Ratio',
  'LBL_CV_ROTATENAMES' => 'rotate Names',
  'LBL_CV_1AND2DIM' => 'one and two dimensioanl Charts',
  'LBL_CV_MULTISERIES' => 'Multiseries/Multivalues Charts',
  'LBL_CV_USEROUNDEDGES' => 'round Edges',
  'LBL_CV_SHOWPLOTBORDER' => 'show Border',
  'LBL_CV_SHOWSHADOW' => 'show Shadow',
  'LBL_CV_TRENDDATA' => 'Trenddata',
  'LBL_CV_TRENDOVER' => 'trend by',
  'LBL_CV_TRENDCHART' => 'Trendchart',

  // field group labels
  'LBL_CHART_PARAMETERS' => 'Chart Parameters', 
  'LBL_CHART_NAME' => 'Chart Title',

  // Dropdown Values
  'LBL_DD_1' => 'yes',
  'LBL_DD_0' => 'no',

  // Chart Type
  'LBL_CT_PIE2D' => 'Pie 2D',
  'LBL_CT_DOUGHNUT2D' => 'Dougnut 2D',
  'LBL_CT_BAR2D' => 'Bar 2D',
  'LBL_CT_BAR3D' => 'Bar 3D',
  'LBL_CT_COLUMN2D' => 'Column 2D',
  'LBL_CT_COLUMN3D' => 'Column 3D',
  'LBL_CT_PIE3D' => 'Pie 3D',
  'LBL_CT_COLUMN3D' => 'Column 3D',
  'LBL_CT_STACKEDCOLUMN2D' => 'stacked Column 2D',
  'LBL_CT_STACKEDCOLUMN3D' => 'stacked Column 3D',
  'LBL_CT_STACKEDBAR2D' => 'stacked Bar 2D',
  'LBL_CT_STACKEDBAR3D' => 'stacked Bar 3D',
  'LBL_CT_MSBAR2D' => 'Multiseries Bar 2D',
  'LBL_CT_MSBAR3D' => 'Multiseries Bar 3D',
  'LBL_CT_MSCOLUMN2D' => 'Multiseries Column 2D',
  'LBL_CT_MSCOLUMN3D' => 'Multiseries Column 3D',
  'LBL_CT_MSAREA' => 'Area',
  'LBL_CT_STACKEDAREA2D' => 'stacked Area',
  'LBL_CT_NOCHART' => '-',


  'LBL_CD_ONEDIMENSIONAL' => 'onedimensional',
  'LBL_CD_TWODIMENSIONAL' => 'twodimensional',
  'LBL_CD_MULTISERIES' => 'multiseries',
  'LBL_CD_MULTIVALUES' => 'multivalues',
  'LBL_CD_TRENDANALYSIS' => 'Trend Analysis',

  // List Types
  'LBL_LT_STANDARD' => 'standard',
  'LBL_LT_GROUPED' => 'grouped',
  'LBL_LT_GRPWTHSUMM' => 'grouped w. summary',
  'LBL_LT_MATRIX' => 'Matrix',
  'LBL_LT_HTML' => 'plain HTML',
  'LBL_LT_GRPTREE' => 'multilevel Tree',

  // DropDownValues
  'LBL_DD_SEQ_YES' => 'Yes',
  'LBL_DD_SEQ_NO' => 'No',
  'LBL_DD_SEQ_PRIMARY' => '1',

  'LBL_DD_SEQ_2' => '2',
  'LBL_DD_SEQ_3' => '3',
  'LBL_DD_SEQ_4' => '4',
  'LBL_DD_SEQ_5' => '5',

  // Panel Titles
  'LBL_WHERRE_CLAUSE_TITLE' => 'Selectioncriteria',
  
  //Confirm Dialog
  'LBL_DIALOG_CONFIRM' => 'Confirm',
  'LBL_DIALOG_DELETE_YN' => 'are you sure you want to delete this Report?',
// == ende english


  'LBL_MODULE_NAME' => 'KINAMU Reports',
  'LBL_MODULE_TITLE' => 'KINAMU Reports',
  'LBL_SEARCH_FORM_TITLE' => 'Report Suche',
  'LBL_LIST_FORM_TITLE' => 'Report Liste',
  'LBL_NEW_FORM_TITLE' => 'Report anlegen',
  'LBL_LIST_CLOSE' => 'Schliessen',
  'LBL_LIST_SUBJECT' => 'Titel',
  'LBL_DESCRIPTION' => 'Beschreibung:',
  'LNK_NEW_REPORT' => 'Report anlegen',
  'LNK_REPORT_LIST' => 'Reports',

  'LBL_LIST_MODULE' => 'Modul', 
  'LBL_LIST_ASSIGNED_USER_NAME' => 'zugewiesener Benutzer',

  'LBL_TARGETLIST_NAME' => 'Target List Name',
  'LBL_TARGETLIST_PROMPT' => 'Name der neuen Target List',

  'LBL_DYNAMIC_OPTIONS' => 'Dynamische Selektion',

  // Grid headers
  'LBL_FIELDNAME' => 'Feldname',
  'LBL_NAME' => 'Name',
  'LBL_OPERATOR' => 'Operator',
  'LBL_VALUE_FROM' => 'Gleich/von', 
  'LBL_VALUE_TO' => 'bis',
  'LBL_JOIN_TYPE' => 'benötigt',
  'LBL_TYPE' => 'Type',
  'LBL_WIDTH' => 'Breite',
  'LBL_SORTPRIORITY' => 'Sortseq.',
  'LBL_SORTSEQUENCE' => 'Sort',
  'LBL_DISPLAY' => 'Anzeige', 
  'LBL_LINK' => 'Link',
  'LBL_PATH' => 'Pfad', 
  'LBL_SEQUENCE' => 'Sequenz',
  'LBL_GROUPBY' => 'Gruppieren nach',
  'LBL_SQLFUNCTION' => 'Funktion',
  'LBL_DISPLAYFUNCTION' => 'disp. Funkt.',
  'LBL_USEREDITABLE' => 'editierbar',

   // Title and Headers for Multiselect Popup
   'LBL_MUTLISELECT_POPUP_TITLE' => 'Werte auswählen',
   'LBL_MULTISELECT_VALUE_HEADER' => 'ID', 
   'LBL_MULTISELECT_TEXT_HEADER' => 'Wert', 
   'LBL_MUTLISELECT_CLOSE_BUTTON' => 'Sichern',
   'LBL_MUTLISELECT_CANCEL_BUTTON' => 'Abbrechen',

   // for the Snapshot Comaprison
   'LBL_SNAPSHOTCOMPARISON_POPUP_TITLE' => 'Chart vergleich',
   'LBL_SNAPSHOTTRENDANALYSIS_POPUP_TITLE' => 'Trend Analyse',
   'LBL_SNAPSHOTCOMPARISON_SNAPHOT_HEADER' => 'Snapshot',
   'LBL_SNAPSHOTCOMPARISON_DESCRIPTION_HEADER' => 'Beschreibung', 
   'LBL_SNAPSHOTCOMPARISON_SELECT_CHART' => 'Chart auswählem:',
   'LBL_SNAPSHOTCOMPARISON_SELECT_LEFT' => 'linke Quelle auswählen:',
   'LBL_SNAPSHOTCOMPARISON_SELECT_RIGHT' => 'rechte Quelle auswählen:',
   'LBL_SNAPSHOTCOMPARISON_DATASERIES' => 'Data',
   'LBL_SNAPSHOTCOMPARISON_DATADIMENSION' => 'Dimension',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE' => 'Crattyp',
   'LBL_BASIC_TRENDLINE_BUTTON_LABEL' => 'Trend Analyse',

   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSLINE' => 'Linie',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_STACKEDAREA2D' => 'Fläche',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR2D' => 'Balken 2D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR3D' => 'Balken 3D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN2D' => 'Säulen 2D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN3D' => 'Säulen 3D',

   'LBL_SNAPSHOTCOMPARISON_LOADINGCHARTMSG' => 'lade Chart',

  // Operator Names
  'LBL_OP_EQUALS' => '=',
  'LBL_OP_NOTEQUAL' => '≠',
  'LBL_OP_STARTS' => 'beginnt mit',
  'LBL_OP_CONTAINS' => 'enthält',
  'LBL_OP_NOTSTARTS' => 'beginnt nicht mit',
  'LBL_OP_NOTCONTAINS' => 'enthält nicht',
  'LBL_OP_BETWEEN' => 'ist zwischen',
  'LBL_OP_ISEMPTY' => 'ist leer',
  'LBL_OP_ISEMPTYORNULL' => 'ist leer oder NULL',
  'LBL_OP_ISNULL' => 'ist NULL',
  'LBL_OP_ISNOTEMPTY' => 'ist nicht leer',
  'LBL_OP_THISMONTH' => 'diesen Monat',
  'LBL_OP_NEXT3MONTH' => 'in den nächsten 3 Monaten',
  'LBL_OP_LASTMONTH' => 'letzten Monat',
  'LBL_OP_LAST3MONTH' => 'in den letzten 3 Monaten',
  'LBL_OP_THISYEAR' => 'dieses Jahr',
  'LBL_OP_LASTYEAR' => 'letztes Jahr',
  'LBL_OP_GREATER' => '>',
  'LBL_OP_LESS' => '<',
  'LBL_OP_GREATEREQUAL' => '>=',
  'LBL_OP_LESSEQUAL' => '<=',
  'LBL_OP_ONEOF' => 'eines von',
  'LBL_OP_ONEOFNOT' => 'nicht eines von',
  'LBL_OP_ONEOFNOTORNULL' => 'nicht eines von oder NULL',

  // List Limits
  'LBL_LI_TOP10' => 'top 10',
  'LBL_LI_TOP20' => 'top 20',
  'LBL_LI_TOP50' => 'top 50',
  'LBL_LI_TOP250' => 'top 250',
  'LBL_LI_BOTTOM50' => 'bottom 50',
  'LBL_LI_BOTTOM10' => 'bottom 10',
  'LBL_LI_NOLIMIT' => 'no limit',

  // buttons
  'LBL_CHANGE_GROUP_NAME' => 'Gruppenname ändern',
  'LBL_CHANGE_GROUP_NAME_PROMPT' => 'Name :',
  'LBL_ADD_GROUP_NAME' => 'neue Gruppe anlegen',
  'LBL_SELECTION_CLAUSE' => 'Auswahlbedingung:',
  'LBL_SELECTION_LIMIT' => 'Limit:',
  'LBL_EDIT_BUTTON_LABEL' => 'ändern',
  'LBL_DELETE_BUTTON_LABEL' => 'löschen',
  'LBL_ADD_BUTTON_LABEL' => 'hinzufügen',
  'LBL_DOWN_BUTTON_LABEL' => '',
  'LBL_UP_BUTTON_LABEL' => '',
  'LBL_SNAPSHOT_BUTTON_LABEL' => 'Snapshot erstellen',
  'LBL_SNAPSHOTMENU_BUTTON_LABEL' => 'Snapshots',
  'LBL_EXPORTMENU_BUTTON_LABEL' => 'Export',
  'LBL_COMPARE_SNAPSHOTS_BUTTON_LABEL' => 'Chart vergleich',
  'LBL_EXPORT_TO_EXCEL_BUTTON_LABEL' => 'EXCEL',
  'LBL_EXPORT_TO_PDF_BUTTON_LABEL' => 'PDF',	
  'LBL_EXPORT_TO_PDFWCHART_BUTTON_LABEL' => 'PDF m. Chart',	
  'LBL_EXPORT_TO_TARGETLIST_BUTTON_LABEL' => 'Targetliste',	
  'LBL_PDFORIENTATION' => 'PDF Ausrichtung',
  'LBL_LISTTYPE' => 'List Typ',
  'LBL_CHART_LAYOUTS' => 'Layout',
  'LBL_CHART_TYPE' => 'Typ',
  'LBL_CHART_INDEX_LABEL' => 'Chart Index',
  'LBL_CHART_INDEX_EMPTY_TEXT' => 'Wählen Sie eine Chart ID aus',
  'LBL_CHART_LABEL' => 'Chart',
  'LBL_CHART_HEIGHT_LABEL' => 'Chart Höhe', 

  // Chart values
  'LBL_CV_CHARTTYPE' => 'Chart Typ', 
  'LBL_CV_TITLE' => 'Titel für Chart', 
  'LBL_CV_DIMENSION' => 'Dimension', 
  'LBL_CV_DATASERIES' => 'Data', 
  'LBL_CV_DIMENSIONX' => 'Dimension X',
  'LBL_CV_DIMENSIONY' => 'Dimension Y',
  'LBL_CV_SHOWEMPTY' => 'leere Werte anzeigen',
  'LBL_CV_SHOWVALUES' => 'Werte anzeigen',
  'LBL_CV_SHOWPERCENTVALUES' => 'Prozentwerte anzeigen',
  'LBL_CV_SHOWNAMES' => 'Namen anzeigen',
  'LBL_CV_SHOWLEGEND' => 'Legende anzeigen',
  'LBL_CV_PLOTFILLRATIO' => 'plot Fill Ratio',
  'LBL_CV_ROTATENAMES' => 'Namen drehen',

  // Dropdown Values
  'LBL_DD_1' => 'ja',
  'LBL_DD_0' => 'nein',

  // Chart Type
  'LBL_CT_PIE2D' => 'Kuchen 2D',
  'LBL_CT_DOUGHNUT2D' => 'Dougnut 2D',
  'LBL_CT_BAR2D' => 'Balken 2D',
  'LBL_CT_BAR3D' => 'Balken 3D',
  'LBL_CT_COLUMN2D' => 'Säulen 2D',
  'LBL_CT_COLUMN3D' => 'Säulen 3D',
  'LBL_CT_PIE3D' => 'Kuchen 3D',
  'LBL_CT_COLUMN3D' => 'Säulen 3D',
  'LBL_CT_STACKEDCOLUMN2D' => 'gestapelte Säulen 2D',
  'LBL_CT_STACKEDCOLUMN3D' => 'gestapelte Säulen 3D',
  'LBL_CT_STACKEDBAR2D' => 'gestapelte Balken 2D',
  'LBL_CT_STACKEDBAR3D' => 'gestapelte Balken 3D',
  'LBL_CT_MSBAR2D' => 'Multiseries Balken 2D',
  'LBL_CT_MSBAR3D' => 'Multiseries Balken 3D',
  'LBL_CT_MSCOLUMN2D' => 'Multiseries Säulen 2D',
  'LBL_CT_MSCOLUMN3D' => 'Multiseries Säulen 3D',
  'LBL_CT_NOCHART' => '-',

  // List Types
  'LBL_LT_STANDARD' => 'standard',
  'LBL_LT_GROUPED' => 'gruppiert',
  'LBL_LT_GRPWTHSUMM' => 'gruppiert m. Zusammenfassung',
  'LBL_LT_MATRIX' => 'Matrix',
  'LBL_LT_HTML' => 'plain HTML',
  'LBL_LT_GRPTREE' => 'multilevel Tree',

  // DropDownValues
  'LBL_DD_SEQ_YES' => 'ja',
  'LBL_DD_SEQ_NO' => 'nein',
  'LBL_DD_SEQ_PRIMARY' => '1',

  'LBL_DD_SEQ_2' => '2',
  'LBL_DD_SEQ_3' => '3',
  'LBL_DD_SEQ_4' => '4',
  'LBL_DD_SEQ_5' => '5',

  // Panel Titles
  'LBL_WHERRE_CLAUSE_TITLE' => 'Auswahlbedingungen',
  
  //Confirm Dialog
  'LBL_DIALOG_CONFIRM' => 'Bestätigen',
  'LBL_DIALOG_DELETE_YN' => 'Sind sie sicher dass sie den Report löschen wollen?',  

  // for the scheduler
  'LBL_SCHED_MONTH' => 'Monat',
  'LBL_SCHED_WEEK' => 'Woche',
  'LBL_SCHED_DAY' => 'Tag',
  'LBL_SCHED_HOUR' => 'Stunde',
  'LBL_SCHED_MIN' => 'Minute',
  'LBL_SCHED_ACT' => 'Aktion',
  'LBL_SCHED_RECEIPIENTS' => 'an',
  'LBL_SCHED_ACT_SNAPSHOT' => 'erstelle Snapshot',
  'LBL_SCHED_ACT_EXCEL' => 'sende Excel',
  'LBL_SCHED_ACT_PDF' => 'sende PDF',
  'LBL_SCHEDULER_POPUP_TITLE' => 'Report Action einplanen',
  'LBL_SCHEDULER_BUTTON' => 'Scheduler',
  'LBL_SCHEDULER_PANEL_ADDBUTTON' => 'neuer Job',
  
  'LBL_SCHED_MONTH_00' => 'jeden',
  'LBL_SCHED_MONTH_01' => 'Januar',
  'LBL_SCHED_MONTH_02' => 'Februar',
  'LBL_SCHED_MONTH_03' => 'Maerz',
  'LBL_SCHED_MONTH_04' => 'April',
  'LBL_SCHED_MONTH_05' => 'Mai',
  'LBL_SCHED_MONTH_06' => 'Juni',
  'LBL_SCHED_MONTH_07' => 'Juli',
  'LBL_SCHED_MONTH_08' => 'August',
  'LBL_SCHED_MONTH_09' => 'September',
  'LBL_SCHED_MONTH_10' => 'Oktober',
  'LBL_SCHED_MONTH_11' => 'November',
  'LBL_SCHED_MONTH_12' => 'Dezember',

  'LBL_SCHED_WEEK_00' => 'jede Woche',
  'LBL_SCHED_WEEK_01' => 'erste Woche',
  'LBL_SCHED_WEEK_02' => 'zweite Woche',
  'LBL_SCHED_WEEK_03' => 'dritte Woche',
  'LBL_SCHED_WEEK_04' => 'vierte Woche',
  'LBL_SCHED_WEEK_05' => 'letzte Woche',

  'LBL_SCHED_DAY_00' => 'jeden Tag',
  'LBL_SCHED_DAY_01' => 'Montag',
  'LBL_SCHED_DAY_02' => 'Dienstag',
  'LBL_SCHED_DAY_03' => 'Mittwoch',
  'LBL_SCHED_DAY_04' => 'Donnerstag',
  'LBL_SCHED_DAY_05' => 'Freitag',
  'LBL_SCHED_DAY_06' => 'Samstag',
  'LBL_SCHED_DAY_07' => 'Sonntag',
  'LBL_SCHED_DAY_08' => 'werktags',

  'LBL_SCHED_HR_EVERY' => 'every hour'
); 

?>
