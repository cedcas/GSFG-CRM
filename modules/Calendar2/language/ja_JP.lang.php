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
  'LNK_NEW_CALL' => 'コールのスケジュール',
  'LNK_SYNC_BATCH' => 'Googleと同期',
  'LNK_NEW_MEETING' => 'ミーティングのスケジュール',
  'LNK_NEW_APPOINTMENT' => 'スケジュールの作成',
  'LNK_NEW_TASK' => 'タスクの作成',
  'LNK_CALL_LIST' => 'コール',
  'LNK_MEETING_LIST' => 'ミーティング',
  'LNK_TASK_LIST' => 'タスク',
  'LNK_VIEW_CALENDAR' => '今日',
  'LNK_IMPORT_CALLS'=>'コールのインポート',
  'LNK_IMPORT_MEETINGS'=>'ミーティングのインポート',
  'LNK_IMPORT_TASKS'=>'タスクのインポート',
  'LBL_CALL' => 'コール',
  'LBL_TASK' => 'タスク',
  'LBL_MEETING' => 'ミーティング',
  'LBL_DAY' => '日',
  'LBL_YEAR' => '年',
  'LBL_WEEK' => '週',
  'LBL_MONTH' => '月',
  'LBL_PREVIOUS_MONTH' => '前月',
  'LBL_PREVIOUS_DAY' => '前日',
  'LBL_PREVIOUS_YEAR' => '前年',
  'LBL_PREVIOUS_WEEK' => '前週',
  'LBL_NEXT_MONTH' => '翌月',
  'LBL_NEXT_DAY' => '翌日',
  'LBL_NEXT_YEAR' => '翌年',
  'LBL_NEXT_WEEK' => '翌週',
  'LBL_AM' => 'AM',
  'LBL_PM' => 'PM',
  'LBL_SCHEDULED' => 'スケジュール済み',
  'LBL_SETTINGS' => 'Calendar2の設定',
  'LBL_DEFAULT_ACTIVITY' => 'デフォルトの活動',
  'LBL_ACTIVITY_DISPLAY' => '活動の表示',
  'LBL_CALL_SHOW' => 'コールのみ',
  'LBL_MEETING_SHOW' => 'ミーティングのみ',
  'LBL_BOTH_SHOW' => '両方表示',
  'LBL_BUSY' => '予定あり',
  'LBL_CONFLICT' => '重複',
  'LBL_USER_CALENDARS' => 'ユーザカレンダー',
  'LBL_SHARED' => '共有',
  'LBL_PREVIOUS_SHARED' => '前週',
  'LBL_NEXT_SHARED' => '翌週',
  'LBL_SHARED_CAL_TITLE' => '共有カレンダー',

  'LBL_SHAREDMONTHLY' => '共有（月）',
  'LBL_SHAREDMONTHLY_CAL_TITLE' => '共有（月）カレンダー',  
  'LBL_PREVIOUS_SHAREDMONTHLY' => '前月',
  'LBL_NEXT_SHAREDMONTHLY' => '翌月',  
  
  'LBL_USERS' => 'ユーザ',
  'LBL_CONTACTS' => '取引先担当者',
  'LBL_REFRESH' => '更新',
  'LBL_EDIT' => '編集',
  'LBL_SELECT_USERS' => 'カレンダーに表示するユーザを選択',
  'LBL_FILTER_BY_TEAM' => 'チーム別のユーザを表示:',
  'LBL_ASSIGNED_TO_NAME' => 'アサイン先',
  'LBL_DATE' => '開始日時',
  'LNK_RESOURCE_LIST' => '施設',
  'LNK_NEW_RES' => '施設の作成',
  'LNK_RES_CAL' => '施設カレンダー',
  'LBL_YES' => 'はい',
  'LBL_NO' => 'いいえ',
  'LBL_CREATE_NEW_RECORD' => 'レコードの作成',
  'LBL_LOADING' => 'ロード中...',
  'LBL_EDIT_RECORD' => 'レコードの編集',
  'LBL_ERROR_SAVING' => '保存中にエラー発生',
  'LBL_ERROR_LOADING' => 'ロード中にエラー発生',
  'LBL_ANOTHER_BROWSER' => '別のブラウザを用いてチームを追加してください。',
  'LBL_FIRST_TEAM' => '最初のスケジュールは削除できません。',
  'LBL_REMOVE_PARTICIPANTS' => 'すべての参加者を削除することはできません。',
  'LBL_START_DAY' => '週の開始曜日:',
  'LBL_START_TIME' => '開始時間:',
  'LBL_END_TIME' => '終了時間:',
  'LBL_DURATION' => '時間:',
  'LBL_NAME' => '件名:',
  'LBL_DESCRIPTION' => '詳細:',
  'LBL_LOCATION' => '場所:',
  'LBL_ADDITIONAL_DETAIL' => '追加詳細:',
  'LBL_SHOW_TASKS' => 'タスクを表示:',
  'LBL_AUTO_ACCEPT' => 'スケジュールを自動的に許可:',
  'LBL_GCAL' => 'Googleの設定',
  'LBL_CALDAV' => 'CalDAVの設定',
  'LBL_GENERAL' => '一般項目',
  'LBL_PARTICIPANTS' => '参加者',
  'LBL_INV_CONTACT' => '招待者を追加',
  'LBL_RECURENCE' => '繰り返し設定',
  'LBL_SAVE_BUTTON' => '保存',
  'LBL_APPLY_BUTTON' => '適用',
  'LBL_CANCEL_BUTTON' => 'キャンセル',
  'LBL_DELETE_BUTTON' => '削除',
  'LBL_TODAY' => '今日',
  'LBL_NONE' => 'なし',
  'LBL_SHOW_SEARCH' => '検索を表示',
  'LBL_HIDE_SEARCH' => '検索を非表示',
  'MSG_CANNOT_REMOVE_FIRST' => '繰り返し予定の最初のスケジュールを削除することはできません。スケジュールを編集して再度保存してください。',
  'MSG_REMOVE_CONFIRM' => 'このレコードを削除しても良いですか？',
  'MSG_CANNOT_HANDLE_YEAR' => '要求された年を処理することができません。',
  'MSG_CANNOT_HANDLE_YEAR2' => '年は1970年から2037年までである必要があります。',
  'LBL_NOTE' => 'カレンダーノート',
  'LNK_NEW_CALNOTE' => 'カレンダーノートの作成',
  'LBL_CALDAV_URL' => 'CalDAV URL:',
  'LBL_CALDAV_USERNAME' => 'ユーザ名:',
  'LBL_CALDAV_PASSOWRD' => 'パスワード:',
  'LBL_CALDAV_SYNC_OPT' => '同期設定:',
  'LBL_CALDAV_SYNC_OPT1' => '双方（Calendar2からCalDAV、GoogleからCalDAV）',
  'LBL_CALDAV_SYNC_OPT2' => '一方向（Calendar2からCalDAV）',
  'LBL_CALDAV_SYNC_OPT3' => '一方向（CalDAVからCalendar2）',
  'LBL_CALDAV_PRIORITY' => '優先度:',
  'LBL_CALDAV_PRIORITY1' => 'CalDAV',
  'LBL_CALDAV_PRIORITY2' => 'Calendar2',
  'LBL_CALDAV_SYNC_MOD' => '同期モジュール:',
  'LBL_CALDAV_INTERVAL' => '同期間隔:',  
  'LBL_CALDAV_TIE_SLOT' => '同期期間（月）:',  
  
  'LBL_GCAL_USERNAME' => 'ユーザ名:',
  'LBL_GCAL_PASSOWRD' => 'パスワード:',
  'LBL_GCAL_SYNC_OPT' => '同期オプション:',
  'LBL_GCAL_SYNC_OPT1' => '双方向（Calendar2からGoogle、GoogleからCalendar2）',
  'LBL_GCAL_SYNC_OPT2' => '一方向（Calendar2からGoogle）',
  'LBL_GCAL_SYNC_OPT3' => '一方向（GoogleからCalendar2）',
  'LBL_GCAL_PRIORITY' => '優先度：',
  'LBL_GCAL_PRIORITY1' => 'Google',
  'LBL_GCAL_PRIORITY2' => 'Calendar2',
  'LBL_GCAL_SYNC_MOD' => '同期モジュール',
  'LBL_GCAL_INTERVAL' => '同期間隔：',  
  'LBL_GCAL_TIE_SLOT' => '同期期間（月）',  
  'LBL_ACCEPT_STATUS' => '許可ステータス：',  
  'LBL_TIME_SLOT_ALERT' => '表示時間外にスケジュールがあります。',
);

$mod_list_strings = array(
'dom_cal_weekdays'=>array(
"日",
"月",
"火",
"水",
"木",
"金",
"土",
),
'dom_cal_weekdays_long'=>array(
"日曜日",
"月曜日",
"火曜日",
"水曜日",
"木曜日",
"金曜日",
"土曜日",
),
'dom_cal_month'=>array(
"",
"1月",
"2月",
"3月",
"4月",
"5月",
"6月",
"7月",
"8月",
"9月",
"10月",
"11月",
"12月",
),
'dom_cal_month_long'=>array(
"",
"1月",
"2月",
"3月",
"4月",
"5月",
"6月",
"7月",
"8月",
"9月",
"10月",
"11月",
"12月",
)
);
?>
