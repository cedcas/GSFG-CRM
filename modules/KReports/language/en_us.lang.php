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
  'LBL_SAVE_BUTTON_LABEL' => 'save',
  'LBL_CANCEL_BUTTON_LABEL' => 'cancel',
  'LBL_REPORT_NAME_LABEL' => 'Report Name: &nbsp;',
  'LBL_ASSIGNED_USER_LABEL' => 'Assigned User:&nbsp;',
  'LBL_REPORT_OPTIONS' => 'Report Options',
  'LBL_DEFAULT_NAME' => 'new Report', 
  'LBL_SEARCHING' => 'searching ...', 
  'LBL_LONGTEXT_LABEL' => 'Description',


  'LBL_AUTH_CHECK' => 'Authorization Check',
  'LBL_AUTH_FULL' => 'on all Nodes',
  'LBL_AUTH_TOP' => 'top Node only',
  'LBL_AUTH_NONE' => 'disabled',
  'LBL_SHOW_DELETED' => 'show deleted',
  'LBL_FOLDED_PANELS' => 'collapsible Panels',
  'LBL_DYNOPTIONS_FOLDED' => 'Dynamic Options collapsed',
  'LBL_DYNOPTIONS_UNFOLDED' => 'Dynamic Options open',
  'LBL_RESULTS_FOLDED' => 'Results collapsed', 
  'LBL_RESULTS_UNFOLDED' => 'Results open',
  'LBL_OPTIONS_MENUITEMS' => 'Toolbar Items',
  'LBL_SHOW_EXPORT' => 'show Export Options',
  'LBL_SHOW_SNAPSHOTS' => 'show Snapshots Option',
  'LBL_SHOW_TOOLS' => 'show Tools Option',

  'LBL_MODULE_NAME' => 'KINAMU Reports',
  'LBL_REPORT_STATUS' => 'Report Status',
  'LBL_MODULE_TITLE' => 'KINAMU Reports',
  'LBL_SEARCH_FORM_TITLE' => 'Report Search',
  'LBL_LIST_FORM_TITLE' => 'Report List',
  'LBL_NEW_FORM_TITLE' => 'Create KINAMU Report',
  'LBL_LIST_CLOSE' => 'Close',
  'LBL_LIST_SUBJECT' => 'Title',
  'LBL_DESCRIPTION' => 'Description:',
  'LNK_NEW_REPORT' => 'Create new Report',
  'LNK_REPORT_LIST' => 'List Reports',

  'LBL_UNIONTREE' => 'union Modules',
  'LBL_UNIONLISTFIELDS' => 'Union List Fields',
  'LBL_UNIONFIELDDISPLAYPATH' => 'Union Path',
  'LBL_UNIONFIELDNAME' => 'Union Field name',

  'LBL_LIST_MODULE' => 'Module', 
  'LBL_LIST_ASSIGNED_USER_NAME' => 'assigned User',

  'LBL_DEFINITIONS' => 'Report Definition',
  'LBL_MODULES' => 'Modules',
  'LBL_LISTFIELDS' => 'Display Fields',
  'LBL_CHARTDEFINITION' => 'Chart Details',

  'LBL_TARGETLIST_NAME' => 'Target List Name',
  'LBL_TARGETLIST_PROMPT' => 'Name of the new Targetlist',

  'LBL_DUPLIACTE_NAME' => 'new Report Name',
  'LBL_DUPLICATE_PROMPT' => 'enter the name for the new report',

  'LBL_DYNAMIC_OPTIONS' => 'Dynamic Selection Options',

  // Grid headers
  'LBL_FIELDNAME' => 'Fieldname',
  'LBL_NAME' => 'Name',
  'LBL_OPERATOR' => 'Operator',
  'LBL_VALUE_FROM' => 'Equals/From', 
  'LBL_VALUE_TO' => 'To',
  'LBL_JOIN_TYPE' => 'Required',
  'LBL_TYPE' => 'Type',
  'LBL_WIDTH' => 'Width',
  'LBL_SORTPRIORITY' => 'Sortseq.',
  'LBL_SORTSEQUENCE' => 'Sort',
  'LBL_DISPLAY' => 'Display', 
  'LBL_LINK' => 'Link',
  'LBL_FIXEDVALUE' => 'fixed Value',
  'LBL_PATH' => 'Path', 
  'LBL_SEQUENCE' => 'Sequence',
  'LBL_GROUPBY' => 'Group by',
  'LBL_SQLFUNCTION' => 'Function',
  'LBL_CUSTOMSQLFUNCTION' => 'CustomFunction',
  'LBL_VALUETYPE' => 'Value Type',
  'LBL_DISPLAYFUNCTION' => 'disp. Funct.',
  'LBL_USEREDITABLE' => 'allow edit',

   // Title and Headers for Multiselect Popup
   'LBL_MUTLISELECT_POPUP_TITLE' => 'Select Values',
   'LBL_MULTISELECT_VALUE_HEADER' => 'ID', 
   'LBL_MULTISELECT_TEXT_HEADER' => 'Value', 
   'LBL_MUTLISELECT_CLOSE_BUTTON' => 'Update',
   'LBL_MUTLISELECT_CANCEL_BUTTON' => 'Cancel',

   // for the Snapshot Comaprison
   'LBL_SNAPSHOTCOMPARISON_POPUP_TITLE' => 'Chart by Chart',
   'LBL_SNAPSHOTTRENDANALYSIS_POPUP_TITLE' => 'Trend Analysis',
   'LBL_SNAPSHOTCOMPARISON_SNAPHOT_HEADER' => 'Snapshot',
   'LBL_SNAPSHOTCOMPARISON_DESCRIPTION_HEADER' => 'Description', 
   'LBL_SNAPSHOTCOMPARISON_SELECT_CHART' => 'Select Chart:',
   'LBL_SNAPSHOTCOMPARISON_SELECT_LEFT' => 'Select left source:',
   'LBL_SNAPSHOTCOMPARISON_SELECT_RIGHT' => 'Select right source:',
   'LBL_SNAPSHOTCOMPARISON_DATASERIES' => 'Data',
   'LBL_SNAPSHOTCOMPARISON_DATADIMENSION' => 'Dimension',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE' => 'Charttype',
   'LBL_BASIC_TRENDLINE_BUTTON_LABEL' => 'Trend Analysis',

   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSLINE' => 'Line',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_STACKEDAREA2D' => 'Area',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR2D' => 'Bars 2D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR3D' => 'Bars 3D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN2D' => 'Column 2D',
   'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN3D' => 'Column 3D',

   'LBL_SNAPSHOTCOMPARISON_LOADINGCHARTMSG' => 'loading Chart',

   // Operator Names
  'LBL_OP_IGNORE' => 'ignore',
  'LBL_OP_EQUALS' => '=',
  'LBL_OP_NOTEQUAL' => '≠',
  'LBL_OP_STARTS' => 'starts with',
  'LBL_OP_CONTAINS' => 'contains',
  'LBL_OP_NOTSTARTS' => 'does not start with',
  'LBL_OP_NOTCONTAINS' => 'does not contain',
  'LBL_OP_BETWEEN' => 'is between',
  'LBL_OP_ISEMPTY' => 'is empty',
  'LBL_OP_ISEMPTYORNULL' => 'is empty or NULL',
  'LBL_OP_ISNULL' => 'is NULL',
  'LBL_OP_ISNOTEMPTY' => 'is not empty',
  'LBL_OP_THISMONTH' => 'current month',
  'LBL_OP_NEXT3MONTH' => 'within the next 3 month',
  'LBL_OP_BEFORE' => 'before',
  'LBL_OP_AFTER' => 'after',
  'LBL_OP_LASTMONTH' => 'last month',
  'LBL_OP_LAST3MONTH' => 'within the last 3 month',
  'LBL_OP_THISYEAR' => 'this year',
  'LBL_OP_LASTYEAR' => 'last year',
  'LBL_OP_GREATER' => '>',
  'LBL_OP_LESS' => '<',
  'LBL_OP_GREATEREQUAL' => '>=',
  'LBL_OP_LESSEQUAL' => '<=',
  'LBL_OP_ONEOF' => 'is one of',
  'LBL_OP_ONEOFNOT' => 'is not one of',
  'LBL_OP_ONEOFNOTORNULL' => 'is not one of or NULL',

  // for the List view Menu
  'LBL_LISTVIEW_OPTIONS' => 'List Options',

  // List Limits
  'LBL_LI_TOP10' => 'top 10',
  'LBL_LI_TOP20' => 'top 20',
  'LBL_LI_TOP50' => 'top 50',
  'LBL_LI_TOP250' => 'top 250',
  'LBL_LI_BOTTOM50' => 'bottom 50',
  'LBL_LI_BOTTOM10' => 'bottom 10',
  'LBL_LI_NOLIMIT' => 'no limit',

  // PDF Orientation
  'LBL_LI_PORTRAIT' => 'Portrait', 
  'LBL_LI_LANDSCAPE' => 'Landscape',

  // MAP Menu Item 
  'LBL_MAP' => 'Map Options',
  'LBL_MASS_GEOCODE_BUTTON_LABEL' => 'Geocode Results',

  // buttons
  'LBL_CHANGE_GROUP_NAME' => 'Change Name of Group',
  'LBL_CHANGE_GROUP_NAME_PROMPT' => 'Name :',
  'LBL_ADD_GROUP_NAME' => 'Create new Group',
  'LBL_ADDTOFAVORITE_BUTTON_LABEL' => 'to favorites', 
  'LBL_REMOVEFAVORITE_BUTTON_LABEL' => 'delete favorite',
  'LBL_FAVORITE_NAME' => 'Favorite Name', 
  'LBL_FAVORITENAME_PROMPT' => 'enter name for Favorite', 
  'LBL_SELECTION_CLAUSE' => 'Select Clause: ',
  'LBL_SELECTION_LIMIT' => 'limit List to:',
  'LBL_EDIT_BUTTON_LABEL' => 'edit',
  'LBL_DELETE_BUTTON_LABEL' => 'delete',
  'LBL_ADD_BUTTON_LABEL' => 'add',
  'LBL_ADDEMTPY_BUTTON_LABEL' => 'add fixed',
  'LBL_DOWN_BUTTON_LABEL' => '',
  'LBL_UP_BUTTON_LABEL' => '',
  'LBL_SNAPSHOT_BUTTON_LABEL' => 'Take Snapshot',
  'LBL_SNAPSHOTMENU_BUTTON_LABEL' => 'Snapshots',
  'LBL_TOOLSMENU_BUTTON_LABEL' => 'Tools',
  'LBL_EXPORTMENU_BUTTON_LABEL' => 'Export',
  'LBL_COMPARE_SNAPSHOTS_BUTTON_LABEL' => 'Chart by Chart Comparison',
  'LBL_EXPORT_TO_EXCEL_BUTTON_LABEL' => 'EXCEL',
  'LBL_EXPORT_TO_KLM_BUTTON_LABEL' => 'Google Earth KML',
  'LBL_EXPORT_TO_PDF_BUTTON_LABEL' => 'PDF',	
  'LBL_EXPORT_TO_PDFWCHART_BUTTON_LABEL' => 'PDF w. Chart',	
  'LBL_EXPORT_TO_TARGETLIST_BUTTON_LABEL' => 'Targetlist',	
  'LBL_SQL_BUTTON_LABEL' => 'SQL',
  'LBL_DUPLICATE_REPORT_BUTTON_LABEL' => 'duplicate Report',
  'LBL_PDFORIENTATION' => 'PDF Orientation',
  'LBL_LISTTYPE' => 'List Type',
  'LBL_CHART_LAYOUTS' => 'Layout',
  'LBL_CHART_TYPE' => 'Type',
  'LBL_CHART_DIMENSION' => 'Dimension',
  'LBL_CHART_INDEX_LABEL' => 'Chart Index',
  'LBL_CHART_INDEX_EMPTY_TEXT' => 'Select a Chart ID',
  'LBL_CHART_LABEL' => 'Chart',
  'LBL_CHART_HEIGHT_LABEL' => 'Chart Height',
 
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
  'LBL_CV_SCALENUMBERS' => 'scale Numbers',
  'LBL_CV_SHOWDECIMALS' => 'show Decimals',
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
  'LBL_LT_PIVOT' => 'Pivot Table',

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

  // for the scheduler
  'LBL_SCHED_MONTH' => 'month',
  'LBL_SCHED_METHOD' => 'method',
  'LBL_SCHED_DAYOFMONTH' => 'day of month',
  'LBL_SCHED_WEEK' => 'week',
  'LBL_SCHED_DAYOFWEEK' => 'day of week',
  'LBL_SCHED_HOUR' => 'hour',
  'LBL_SCHED_MIN' => 'min',
  'LBL_SCHED_ACT' => 'Action',
  'LBL_SCHED_RECEIPIENTS' => 'send to',
  'LBL_SCHED_ACT_SNAPSHOT' => 'take snapshot',
  'LBL_SCHED_ACT_EXCEL' => 'send Excel',
  'LBL_SCHED_ACT_PDF' => 'send PDF',
  'LBL_SCHEDULER_POPUP_TITLE' => 'schedule Report Action',
  'LBL_SCHEDULER_BUTTON' => 'Scheduler',
  'LBL_SCHEDULER_PANEL_ADDBUTTON' => 'new Job',
  'LBL_SCHEDULER_PANEL_REMOVEBUTTON' => 'delete Jobs',
  
  'LBL_SCHED_MONTH_00' => 'every month',
  'LBL_SCHED_MONTH_01' => 'January',
  'LBL_SCHED_MONTH_02' => 'February',
  'LBL_SCHED_MONTH_03' => 'March',
  'LBL_SCHED_MONTH_04' => 'April',
  'LBL_SCHED_MONTH_05' => 'May',
  'LBL_SCHED_MONTH_06' => 'June',
  'LBL_SCHED_MONTH_07' => 'July',
  'LBL_SCHED_MONTH_08' => 'August',
  'LBL_SCHED_MONTH_09' => 'September',
  'LBL_SCHED_MONTH_10' => 'October',
  'LBL_SCHED_MONTH_11' => 'November',
  'LBL_SCHED_MONTH_12' => 'December',

  'LBL_SCHED_METHOD_01' => 'fixed day',
  'LBL_SCHED_METHOD_00' => 'variable day',

  'LBL_SCHED_WEEK_00' => 'every week',
  'LBL_SCHED_WEEK_01' => 'first week',
  'LBL_SCHED_WEEK_02' => 'second week',
  'LBL_SCHED_WEEK_03' => 'third week',
  'LBL_SCHED_WEEK_04' => 'fourth week',
  'LBL_SCHED_WEEK_05' => 'last week',

  'LBL_SCHED_DAY_00' => 'every day',
  'LBL_SCHED_DAY_01' => 'Monday',
  'LBL_SCHED_DAY_02' => 'Tuesday',
  'LBL_SCHED_DAY_03' => 'Wednesday',
  'LBL_SCHED_DAY_04' => 'Thursday',
  'LBL_SCHED_DAY_05' => 'Friday',
  'LBL_SCHED_DAY_06' => 'Saturday',
  'LBL_SCHED_DAY_07' => 'Sonnday',
  'LBL_SCHED_DAY_08' => 'weekdays',

  'LBL_SCHED_HR_EVERY' => 'every hour',
  
  'LBL_SCHED_ACTION_1' => 'send Excel',
  'LBL_SCHED_ACTION_2' => 'send PDF',
  'LBL_SCHED_ACTION_3' => 'take Snapshot',
  
  // for the map 
  'LBL_MAP_DETAILS' => 'Map Details',
  'LBL_SHOW_MAP' => 'show Map',
  'LBL_LONGITUDE' => 'Longitude', 
  'LBL_LATITUDE' => 'Latitude',
  'LBL_PUSHPINTYPE' => 'Type',
  'LBL_LINE1' => 'Line 1',
  'LBL_LINE2' => 'Line 2',
  'LBL_LINE3' => 'Line 3',
  'LBL_LINE4' => 'Line 4',
  'LBL_FIELDSET_MAPPING' => 'Mapping Details',
  'LBL_FIELDSET_MAPINFO' => 'Pushpin info', 
  'LBL_FIELDSET_GEOCODE' => 'Mass Geoocode',
  'LBL_GEOCODE_ENABLE' => 'enable', 
  'LBL_GEOCODE_STREET' => 'Street', 
  'LBL_GEOCODE_CITY' => 'City',
  'LBL_GEOCODE_POSTALCODE' => 'Postalcode',
  'LBL_GEOCODE_COUNTRY' => 'Country',
  
   // for the views options
   'LBL_STANDARDPROPERTIES' => 'Standard View Properties',
   'LBL_TREEPROPERTIES' => 'Treeview Properties',
   'LBL_PIVOTPROPERTIES' => 'Pivot Properties',
   'LBL_TREELISTPROPERTIES_POPUP_TITLE' => 'Mutlilevel Tree List Properties', 
   'LBL_STANDARDGRIDPROPERTIES_POPUP_TITLE' => 'Standard View Properties',
   'LBL_RESET_BUTTON' => 'Reset', 
   'LBL_TREESTRCUTUREGRID_TITLE' => 'Tree Hierarchy',
   'LBL_REPOSITORYGRID_TITLE' => 'available Fields',
   'LBL_CANCEL_BUTTON' => 'Cancel', 
   'LBL_CLOSE_BUTTON' => 'Close',
   'LBL_LISTTYPEPROPERTIES' => 'Properties',
   'LBL_XAXIS_TITLE' => 'X-Axis Fields',
   'LBL_YAXIS_TITLE' => 'Y-Axis Fields',
   'LBL_VALUES_TITLE' => 'Value Fields',
   'LBL_SUMMARIZATION_TITLE' => 'Sumamrization Fields',
   'LBL_FUNCTION' => 'Function',
   'LBL_PIVOTGRIDPROPERTIES_PIVOT_TITLE' => 'Pivot Caption',
   'LBL_PIVOTGRIDPROPERTIES_PIVOT_FUNCTION' => 'Function',
   'LBL_PIVOTGRIDPROPERTIES_PIVOT_FIELD' => 'Field',
   'LBL_PIVOTGRIDPROPERTIES_POPUP_TITLE' => 'PIVOT Grid properties',
   'LBL_FUNCTION_SUM' => 'Summe', 
   'LBL_FUNCTION_COUNT' => 'Count', 
   'LBL_FUNCTION_AVG' => 'Average', 
   'LBL_FUNCTION_MIN' => 'Minimum', 
   'LBL_FUNCTION_MAX' => 'Maximum', 

	// Value Types
	'LBL_VALUETYPE_POFSUM' => '% of Sum', 
    'LBL_VALUETYPE_POFCOUNT' => '% of Count', 
    'LBL_VALUETYPE_POFAVG' => '% of Average',

	// panel title
	'LBL_STANDARDGRIDPANELTITLE' => 'Report Result',
	'LBL_STANDRDGRIDPANEL_FOOTERWCOUNT' => 'Displaying Records {0} - {1} of {2}',	
    'LBL_STANDRDGRIDPANEL_FOOTERWOCOUNT' => 'Displaying Records {0} - {1}',

	'LBL_STANDARDGRIDPROPERTIES_COUNT' => 'process Count',
	'LBL_STANDARDGRIDPROPERTIES_SYNCHRONOUSCOUNT' => 'syncronous',
    'LBL_STANDARDGRIDPROPERTIES_ASYNCHRONOUSCOUNT' => 'asyncronous',
    'LBL_STANDARDGRIDPROPERTIES_NOCOUNT' => 'no count',

	// General Labels
	'LBL_YES' => 'yes', 
    'LBL_NO' => 'no', 
    'LBL_SORT_ASC' => 'asc.', 
    'LBL_SORT_DESC' => 'desc.',
    'LBL_JT_OPTIONAL' => 'optional',
    'LBL_JT_REQUIRED' => 'required'
 ); 

?>
