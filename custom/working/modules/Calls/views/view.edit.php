<?php
    require_once('include/json_config.php');
    require_once('include/MVC/View/views/view.edit.php');

    class CallsViewEdit extends ViewEdit 
    {
        function CallsViewEdit() {
            $this->useForSubpanel = true;
//            $this->useModuleQuickCreateTemplate = true;
            parent::ViewEdit();
        }
        
        /**
         * @see SugarView::preDisplay()
         */
        public function preDisplay() 
        {
            if($_REQUEST['module'] != 'Calls' && isset($_REQUEST['status']) && empty($_REQUEST['status'])) {
               $this->bean->status = '';
            } //if
            if(!empty($_REQUEST['status']) && ($_REQUEST['status'] == 'Held')) {
               $this->bean->status = 'Held';
            }
            
             /**
             * The next line of codes will autopopulate the relate field
             * if the bean has a related Case that is related to Sites, City Permit, or Parents
             * @author Christopher Loyola <christopher@crmworks.asia>
             * 
             */     
            # @km: bug 485 make sure record ID is not replaced by xxxx
	    # added: && empty($_REQUEST['record'])
            global $current_user, $beanList, $app_list_strings;
            if($_REQUEST['return_module'] == 'Cases' && !empty($_REQUEST['return_id']) && empty($_REQUEST['record']) )
            {
           
                $parentBean = new $beanList['Cases'];
                $parentBean->retrieve($_REQUEST['return_id']);
                if(array_key_exists($parentBean->parent_type, $app_list_strings['parent_type_display']))
                {
                    $this->bean->id = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'; //This line is needed in order to trigger the automapping of the flex relate field.
                    $this->bean->parent_type = $parentBean->parent_type;    
                    $this->bean->parent_name = $parentBean->parent_name;
                    $this->bean->parent_id = $parentBean->parent_id;
                    
                    # @km: bug 485 (edit button subpanel causes assigned_to value to default login user)
		    # assign only when assigned_to is blank, or $this->bean->id is nul or $this->bean->id = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
                    if(empty($this->bean->assigned_user_id)){
                        $this->bean->assigned_user_id = $current_user->id;
                    }
                    
                }
                
                # @km - bug 446 full form :duration hours
                if(isset($_REQUEST['Calls_subpanel_full_form_button']) && empty($_REQUEST['record']) ){
                    $this->bean->duration_hours = $_REQUEST['duration_hours'];  
                    $this->bean->date_start = $_REQUEST['date_start'];
                    $this->bean->assigned_user_name = $_REQUEST['assigned_user_name'];
                    $this->bean->assigned_user_id = $_REQUEST['assigned_user_id'];
                }
                
            }elseif($_REQUEST['return_module'] == 'Cases' && !empty($_REQUEST['return_id']) && empty($_REQUEST['record'])){
                if(empty($_REQUEST['duration_hours'])){
                    $this->bean->duration_hours = "0";
                }else{
                    $this->bean->duration_hours = $_REQUEST['duration_hours'];
                }
            }
            /**
             * End of autopopulate
             */
            parent::preDisplay();
        }
        
        /**
         * @see SugarView::display()
         */
        public function display() 
        {		
            global $json, $timedate;
            $json = getJSONobj();
            $json_config = new json_config();
            if (isset($this->bean->json_id) && !empty ($this->bean->json_id)) {
                $javascript = $json_config->get_static_json_server(false, true, 'Calls', $this->bean->json_id);
                
            } else {
                $this->bean->json_id = $this->bean->id;
                $javascript = $json_config->get_static_json_server(false, true, 'Calls', $this->bean->id);
                
            }
            $this->ss->assign('JSON_CONFIG_JAVASCRIPT', $javascript);
    
            if($this->ev->isDuplicate){
               $this->bean->status = $GLOBALS['mod_strings']['LBL_DEFAULT_STATUS'];
            } //if
            
            $this->ss->assign('DURATION_HOURS',empty($this->bean->duration_hours)?"0":$this->bean->duration_hours);
            
            print '<script type="text/javascript" src="custom/include/javascript/jquery-1.8.1.min.js"></script>';
            
            parent::display();
            
            
            $defaultTZ = date_default_timezone_get(); 
            date_default_timezone_set('GMT'); 
            $now = time() - ($timedate->adjustmentForUserTimeZone() * 60);
            date_default_timezone_set($defaultTZ);
            
            $date_start = (int) date('i',  strtotime($this->ev->focus->date_start));
            
            $date_start_minutes = "<option value=''></option><option value='00'>00</option>";

            for($i=1; $i<=55; $i++){
                if($i%5==0){
                    $selected = '';
                    if($i==$date_start){
                        $selected = 'selected';
                    }
                    if($i<10){
                        $pad = '0';
                    } else {
                        $pad = '';
                    }
                    $date_start_minutes .= "<option value='".$pad.$i."' {$selected} >".$pad.$i."</option>";
                }
            }
            
            if(empty($this->ev->focus->id)){
                $date_start_minutes_display = ceil(date('i',$now) / 5) * 5;
            }else{
                $date_start_minutes_display = $date_start;
            }
            
            print '
            <script type="text/javascript">
                 jQuery.noConflict()(function($){ // code using jQuery
            ';
            if($_REQUEST['relate_to'] == 'Cases'){

                $date_start_minutes_display = date('i',  strtotime($_REQUEST['date_start']));
                $date_start_hour_display = date('H',  strtotime($_REQUEST['date_start']));
                
                if(empty($date_start_hour_display)){
                    print ' $("#date_start_hours").val("00");';
                }else{
                    print ' $("#date_start_hours").val("'.$date_start_hour_display.'");';
                }
            
                $d_start = explode(' ',$_REQUEST['date_start']);
                $meridiem = $d_start[1][5].$d_start[1][6];
                
                print ' $("#date_start_meridiem").val("'.$meridiem.'");';
                
            }
            
            if($_REQUEST['full_form']=='full_form'){
                $d_start_hour = explode(' ',$_REQUEST['date_start']);
                $d_start_hour = explode(':',$d_start_hour[1]);
                print ' $("#date_start_hours").val("'.$d_start_hour[0].'");';
                print ' combo_date_start.update();';
                print ' $("#name").val("'.$_REQUEST['name'].'");';
                print ' $("#direction").val("'.$_REQUEST['direction'].'");';
                print ' $("#description").val("'.$_REQUEST['description'].'");';
                print ' $("#status").val("'.$_REQUEST['status'].'");';
                print ' 
                        $("#reminder_checked").trigger("click");
                    ';
            }
            
            if($date_start_minutes_display<10 || empty($date_start_minutes_display)){
                $date_start_minutes_display = '0'.$date_start_minutes_display;
            }
            
            print '
                    $("#date_start_minutes").html("'.$date_start_minutes.'");
                    $("#date_start_minutes").val("'.$date_start_minutes_display.'");
                    $("#date_start_mins_hidden").val("'.$date_start_minutes_display.'");
                    
                    $("#date_start_minutes").change(function(){
                         $("#date_start_mins_hidden").val($(this).val());
                    });
                    
                    

                 });
            </script>
            ';
            
            
            if(!$_REQUEST['target_action']=='QuickCreate'){
                # @km - calls 2.2
                include 'custom/modules/Contacts/AddParticipantHtml.php';
            }
            
            # bug 443
            # sync cases related to (Activities subpanel: Log Call)
            if($_REQUEST['target_action']=='QuickCreate'){
                include 'modules/Cases/Case.php';
                $bean = new aCase();
                $bean->retrieve($_REQUEST['acase_id']);
                
                # fix bug 463 - make sure
                # add check so that we won't have conflict default value display to "relate to" field
                # on log call (activities subpanel) - sites, citypermits, parents
                if(!empty($bean->id)){
                    print '
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $("#parent_type").val("'.$bean->parent_type.'");
                                $("#parent_name").val("'.$bean->parent_name.'");
                                $("#parent_id").val("'.$bean->parent_id.'");
                            });
                       </script>
                    ';
                }
                
            }
            
        }
    }
?>