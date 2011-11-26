<?php
/*
 File: staff.php 

 UI staff page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Staff.php');
if(!$user->can_view('staff')){
header('Location: index.php');
   exit;
 }

$modify=$user->can_edit('contacts');


if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $_SESSION['state']['staff']['id']=$_REQUEST['id'];
  $staff_id=$_REQUEST['id'];


}else{
  $staff_id=$_SESSION['state']['staff']['id'];
}


$staff=new Staff($staff_id);

if(!$staff->id){
 header('Location: hr.php?error='._('Staff not exists'));
  exit();

}

$_SESSION['state']['staff']['id']=$staff_id;
//$_SESSION['state']['staff']['store']=$customer->data['Customer Store Key'];


/*if(isset($_REQUEST['view']) and preg_match('/^(history|products|orders)$/',$_REQUEST['view']) ){
  $_SESSION['state']['staff']['view']=$_REQUEST['view'];
  $view=$_REQUEST['view'];
}else{
  $view=$_SESSION['state']['staff']['view'];
}
$smarty->assign('view',$view);
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="external_libs/wdCalendar/wdCalendar/css/dailog.css" rel="stylesheet" type="text/css" />
    <link href="external_libs/wdCalendar/wdCalendar/css/calendar.css" rel="stylesheet" type="text/css" /> 
    <link href="external_libs/wdCalendar/wdCalendar/css/dp.css" rel="stylesheet" type="text/css" />   
    <link href="external_libs/wdCalendar/wdCalendar/css/alert.css" rel="stylesheet" type="text/css" /> 
    <link href="external_libs/wdCalendar/wdCalendar/css/main.css" rel="stylesheet" type="text/css" /> 
    <script src="external_libs/wdCalendar/wdCalendar/src/jquery.js" type="text/javascript"></script>  
    
    <script src="external_libs/wdCalendar/wdCalendar/src/Plugins/Common.js" type="text/javascript"></script>    
    <script src="external_libs/wdCalendar/wdCalendar/src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>     
    <script src="external_libs/wdCalendar/wdCalendar/src/Plugins/jquery.datepicker.js" type="text/javascript"></script>

    <script src="external_libs/wdCalendar/wdCalendar/src/Plugins/jquery.alert.js" type="text/javascript"></script>    
    <script src="external_libs/wdCalendar/wdCalendar/src/Plugins/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script>
    <script src="external_libs/wdCalendar/wdCalendar/src/Plugins/wdCalendar_lang_US.js" type="text/javascript"></script>    
    <script src="external_libs/wdCalendar/wdCalendar/src/Plugins/jquery.calendar.js" type="text/javascript"></script>   
    
    <script type="text/javascript">
        $(document).ready(function() {     
           var view="week";          
           
            var DATA_FEED_URL = "external_libs/wdCalendar/wdCalendar/php/datafeed_each_staff_holidays.php";
            var op = {
                view: view,
                theme:3,
                showday: new Date(),
                EditCmdhandler:Edit,
                DeleteCmdhandler:Delete,
                ViewCmdhandler:View,    
                onWeekOrMonthToDay:wtd,
                onBeforeRequestData: cal_beforerequest,
                onAfterRequestData: cal_afterrequest,
                onRequestDataError: cal_onerror, 
                autoload:true,
                url: DATA_FEED_URL + "?method=list&staff_id=<?php echo $staff_id;?>",  
                quickAddUrl: DATA_FEED_URL + "?method=add", 
                quickUpdateUrl: DATA_FEED_URL + "?method=update",
                quickDeleteUrl: DATA_FEED_URL + "?method=remove"        
            };
            var $dv = $("#calhead");
            var _MH = document.documentElement.clientHeight;
            var dvH = $dv.height() + 2;
            op.height = _MH - dvH;
            op.eventItems =[];

            var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
            $("#caltoolbar").noSelect();
            
            $("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
            onReturn:function(r){                          
                            var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
                            if (p && p.datestrshow) {
                                $("#txtdatetimeshow").text(p.datestrshow);
                            }
                     } 
            });
            function cal_beforerequest(type)
            {
                var t="Loading data...";
                switch(type)
                {
                    case 1:
                        t="Loading data...";
                        break;
                    case 2:                      
                    case 3:  
                    case 4:    
                        t="The request is being processed ...";                                   
                        break;
                }
                $("#errorpannel").hide();
                $("#loadingpannel").html(t).show();    
            }
            function cal_afterrequest(type)
            {
                switch(type)
                {
                    case 1:
                        $("#loadingpannel").hide();
                        break;
                    case 2:
                    case 3:
                    case 4:
                        $("#loadingpannel").html("Success!");
                        window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
                    break;
                }              
               
            }
            function cal_onerror(type,data)
            {
                $("#errorpannel").show();
            }
            function Edit(data)
            {
               var eurl="external_libs/wdCalendar/wdCalendar/edit_each_staff_holidays.php?id={0}&start={2}&end={3}&isallday={4}&title={1}&staffkey{10}";   
                if(data)
                {
                    var url = StrFormat(eurl,data);
                    OpenModelWindow(url,{ width: 600, height: 400, caption:"Manage  The Calendar",onclose:function(){
                       $("#gridcontainer").reload();
                    }});
                }
            }    
            function View(data)
            {
               /*var str = "";
                $.each(data, function(i, item){
                    str += "[" + i + "]: " + item + "\n";
                });
                alert(str); */ 
		 var str = "";
                $.each(data, function(i, item){
                    str += "[" + i + "]: " + item + "\n";
		if(i==10){
                window.location = "staff.php?id="+ item;
		}
                });
		

       
            }    
            function Delete(data,callback)
            {           
                
                $.alerts.okButton="Ok";  
                $.alerts.cancelButton="Cancel";  
                hiConfirm("Are You Sure to Delete this Event", 'Confirm',function(r){ r && callback(0);});           
            }
            function wtd(p)
            {
               if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $("#showdaybtn").addClass("fcurrent");
            }
            //to show day view
            $("#showdaybtn").click(function(e) {
                //document.location.href="#day";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("day").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            //to show week view
            $("#showweekbtn").click(function(e) {
               // sdocument.location.href="#week";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("week").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //to show month view
            $("#showmonthbtn").click(function(e) {
                //document.location.href="#month";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("month").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            
            $("#showreflashbtn").click(function(e){
                $("#gridcontainer").reload();
            });
            
            //Add a new event
            $("#faddbtn").click(function(e) {
                var url ="external_libs/wdCalendar/wdCalendar/edit_staff_holidays.php";
                OpenModelWindow(url,{ width: 500, height: 400, caption: "Create New Calendar"});
            });
            //go to today
            $("#showtodaybtn").click(function(e) {
                var p = $("#gridcontainer").gotoDate().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }


            });
            //previous date range
            $("#sfprevbtn").click(function(e) {
                var p = $("#gridcontainer").previousRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //next date range
            $("#sfnextbtn").click(function(e) {
                var p = $("#gridcontainer").nextRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            
        });
    </script>    
<?php 


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 'text_editor.css',
		 
		 'button.css',
		 'container.css'
		 
		 );
 $css_files[]='theme.css.php';
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'staff.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




//$customer->load('contacts');
$smarty->assign('staff',$staff);


  $smarty->assign('search_label',_('Staff'));
$smarty->assign('search_scope','staff');

  
  $order=$_SESSION['state']['hr']['staff']['order'];
  if($order=='name')
    $order='`Staff Name`';
 elseif($order=='id')
    $order='`Staff Key`';
 /*  elseif($order=='location')
     $order='`Customer Main Location`';
   elseif($order=='orders')
     $order='`Customer Orders`';
   elseif($order=='email')
     $order='`Customer Email`';
   elseif($order=='telephone')
     $order='`Customer Main Telehone`';
   elseif($order=='last_order')
     $order='`Customer Last Order Date`';
   elseif($order=='contact_name')
     $order='`Customer Main Contact Name`';
   elseif($order=='address')
     $order='`Customer Main Location`';
   elseif($order=='town')
     $order='`Customer Main Town`';
   elseif($order=='postcode')
     $order='`Customer Main Postal Code`';
   elseif($order=='region')
     $order='`Customer Main Country First Division`';
   elseif($order=='country')
     $order='`Customer Main Country`';
   //  elseif($order=='ship_address')
   //  $order='`customer main ship to header`';
   elseif($order=='ship_town')
     $order='`Customer Main Delivery Address Town`';
   elseif($order=='ship_postcode')
     $order='`Customer Main Delivery Address Postal Code`';
   elseif($order=='ship_region')
     $order='`Customer Main Delivery Address Country Region`';
   elseif($order=='ship_country')
     $order='`Customer Main Delivery Address Country`';
   elseif($order=='net_balance')
     $order='`Customer Net Balance`';
   elseif($order=='balance')
     $order='`Customer Outstanding Net Balance`';
   elseif($order=='total_profit')
     $order='`Customer Profit`';
   elseif($order=='total_payments')
     $order='`Customer Total Payments`';
   elseif($order=='top_profits')
     $order='`Customer Profits Top Percentage`';
   elseif($order=='top_balance')
     $order='`Customer Balance Top Percentage`';
   elseif($order=='top_orders')
     $order='``Customer Orders Top Percentage`';
   elseif($order=='top_invoices')
     $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
     $order='`Customer Total Refunds`';*/
else
   $order='`Staff Key`';

   $_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Staff Key` as id , `Staff Name` as name from `Staff Dimension`   where  %s < %s  order by %s desc  limit 1",$order,prepare_mysql($staff->get($_order)),$order);
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'name'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$sql=sprintf("select `Staff Key` as id , `Staff Name` as name from `Staff Dimension`     where  %s>%s  order by %s   ",$order,prepare_mysql($staff->get($_order)),$order);

$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'name'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



//$show_details=$_SESSION['state']['staff']['details'];
//$smarty->assign('show_details',$show_details);
$general_options_list=array();

 






$smarty->assign('general_options_list',$general_options_list);





$smarty->assign('parent','hr');
$smarty->assign('title','Staff: '.$staff->get('Staff Name'));
$staff_home=_("Staff List");
//$smarty->assign('id',$myconf['staff_id_prefix'].sprintf("%05d",$staff->id));

$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );
$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];
$filter_value=$_SESSION['state']['hr']['staff']['f_value'];

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
//$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Code like','label'=>_('Code')),
		   );
//$tipo_filter=$_SESSION['state']['staff']['assets']['f_field'];
//$filter_value=$_SESSION['state']['staff']['assets']['f_value'];

$smarty->assign('filter_value1',$filter_value);
$smarty->assign('filter_menu1',$filter_menu);
//$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->display('staff.tpl');

?>
