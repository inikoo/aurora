<?php

include_once('common.php');
include_once('class.LightCustomer.php');
include_once('class.Customer.php');
include_once('class.Store.php');
//include_once('header.php');


$data=array('type'=>'parent', 'width'=>1000, 'customer_profile'=>1);

set_parameters($data);


include_once('top_navigation.php');
include_once('footer.php');
$smarty->assign('footer',$footer_);


/*
print_r($user);exit;
if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit;
}
*/



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
  
    $customer_id=$_REQUEST['id'];
	//$_SESSION['state']['customer']['id']=$customer_id;
} else {
    $customer_id=$_SESSION['state']['customer']['id'];
}



//$customer=new LightCustomer($customer_id);
$customer=new Customer($customer_id);


/*
if(!in_array($customer->data['Customer Store Key'],$user->stores)){
header('Location: customers.php?msg=forbidden');
exit;
}
*/


$_SESSION['state']['customer']['id']=$customer_id;
$_SESSION['state']['customers']['store']=$customer->data['Customer Store Key'];
$_SESSION['state']['customer']['view']='details';


if (isset($_REQUEST['view']) and preg_match('/^(history|products|orders|details)$/',$_REQUEST['view']) ) {

    $view=$_REQUEST['view'];
} else {
    $view=$_SESSION['state']['customer']['view'];
}
if (!$customer->data['Customer Orders'] and ($view=='products' or $view=='orders')) {
 //   $view='history';
}
//print $view;
$smarty->assign('view',$view);
$_SESSION['state']['customer']['view']=$view;

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               'text_editor.css',
               'common.css',
               'button.css',
               'css/container.css',
               'table.css',
               'css/profile.css',
               'css/upload.css'
           );
//$css_files[]='theme.css.php';
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
              $yui_path.'uploader/uploader-min.js',
              
              'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
             
             
               'upload_common.js.php',
                'orders.js.php?customer_key='.$customer->id.'&customer_type='.$customer->get('Customer Type')
          );
          
          
          
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//if($user->id)
$smarty->assign('user',$user);

if($customer){
$customer->load('contacts');
$smarty->assign('customer',$customer);
}

list($customer_type, $login_stat)=$customer->is_user_customer($customer_id);
$_login_stat=array();

if($customer_type){
	
	foreach($login_stat as $key=>$value){

	if($key=='User Last Login' || $key=='User Last Failed Login'){
		$value=strftime("%a %e %b %y %H:%M", strtotime($value." +00:00"));
	}

	$_login_stat[preg_replace('/\s/','',$key)]=$value;
	}
}

$smarty->assign('login_stat',$_login_stat);
$smarty->assign('customer_type',$customer_type);

$smarty->assign('search_label','');
//$smarty->assign('search_scope','customers');

 $store=new Store($customer->data['Customer Store Key']);
$smarty->assign('store', $store);



$smarty->assign('store_id',$customer->data['Customer Store Key']);


$general_options_list=array();
//$general_options_list[]=array('tipo'=>'url','url'=>'customer_categories.php?store_id='.$store->id.'&id=0','label'=>_('Categories'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Lists'));
//$general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php?store='.$store->id,'label'=>_('Advanced Search'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

    $general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'client.php?id='.$customer->id,'label'=>_('Edit Customer'));


$smarty->assign('general_options_list',$general_options_list);




$smarty->assign('number_orders',$customer->get('Customer Orders'));
$smarty->assign('parent','customers');
$smarty->assign('title','Customer: '.$customer->get('customer name'));
$customer_home=_("Customers List");

$total_orders=$customer->get('Customer Orders');
$smarty->assign('orders',number($total_orders)  );
$total_net=$customer->get('Customer Total Net Payments');
$smarty->assign('total_net',money($total_net));
$total_invoices=$customer->get('Customer Orders Invoiced');
$smarty->assign('invoices',number($total_invoices)  );
if ($total_invoices>0)
    $smarty->assign('total_net_average',money($total_net/$total_invoices));

$order_interval=$customer->get('Customer Order Interval');

if ($order_interval>10) {
    $order_interval=round($order_interval/7);
    if ( $order_interval==1)
        $order_interval=_('week');
    else
        $order_interval=$order_interval.' '._('weeks');

} else if ($order_interval=='')
    $order_interval='';
else
    $order_interval=round($order_interval).' '._('days');
$smarty->assign('orders_interval',$order_interval);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
                 //   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
                 'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
                 'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
             );
			 
			 
$_SESSION['state']['customer']['table']['f_field'] = 'notes';
$tipo_filter=$_SESSION['state']['customer']['table']['f_field'];
$_SESSION['state']['customer']['table']['f_value']='';
$filter_value=$_SESSION['state']['customer']['table']['f_value'];

//print_r($_SESSION);exit;

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Code like','label'=>_('Code')),
             );
$_SESSION['state']['customer']['orders']['order']='last_update';
$_SESSION['state']['customer']['orders']['order_dir']='desc';
$_SESSION['state']['customer']['orders']['f_field']='public_id';
$_SESSION['state']['customer']['orders']['f_value']='';

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$elements_number=array('Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Customer History Bridge` where `Customer Key`=%d group by `Type`",$customer->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Type']]=$row['num'];
}

$elements=array('Notes'=>1,'Orders'=>1,'Changes'=>1,'Attachments'=>1,'Emails'=>1);
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$elements);

$gold_reward=0;
//print_r($customer->data);
if($customer->data['Customer Last Order Date']    ){
  $last_order_date=$customer->data['Customer Last Order Date'];
  $last_order_date='2011-01-15';
  $last_order_time=strtotime( $last_order_date);
  // print $last_order_time;
  if( (date('U')-$last_order_time)<2592000 )
    $gold_reward='Gold Reward Member';

}
$correlation_msg='';
 $msg='';
        $sql=sprintf("select * from `Customer Correlation` where `Customer A Key`=%d and `Correlation`>200",$customer->id);
        $res2=mysql_query($sql);
        while ($row2=mysql_fetch_assoc($res2)) {
            $msg.=','.sprintf("<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>",$customer->id,$row2['Customer B Key'],$myconf['customer_id_prefix'].sprintf("%05d",$row2['Customer B Key']));
        }
         $sql=sprintf("select * from `Customer Correlation` where `Customer B Key`=%d and `Correlation`>200",$customer->id);
        $res2=mysql_query($sql);
        while ($row2=mysql_fetch_assoc($res2)) {
            $msg.=','.sprintf("<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>",$customer->id,$row2['Customer A Key'],$myconf['customer_id_prefix'].sprintf("%05d",$row2['Customer A Key']));
        }
        
        $msg=preg_replace('/^,/','',$msg);
        if ($msg!='') {
            $correlation_msg='<p>'._('Potential duplicated').': '.$msg.'</p>';

        }


//show case 		
$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field In Showcase`='Yes' and `Custom Field Table`='Customer'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$show_case=Array();
$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){

	foreach($custom_field as $key=>$value){
		$show_case[$value]=$row[$key];
	}
}



$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$customer_custom_fields=Array();
$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){

	foreach($custom_field as $key=>$value){
		$customer_custom_fields[$value]=$row[$key];
	}
}

$smarty->assign('show_case',$show_case);	
$smarty->assign('customer_custom_fields',$customer_custom_fields);	
$smarty->assign('correlation_msg',$correlation_msg);
$smarty->assign('hq_country',$myconf['country']);

$smarty->assign('gold_reward',$gold_reward);

$smarty->assign('options_box_width','550px');
$smarty->assign('id',$myconf['customer_id_prefix'].sprintf("%05d",$customer->id));


$smarty->assign('other_email_login_handle',$customer->get_other_email_login_handle());
$smarty->assign('header',$header);

$smarty->display('orders.tpl');

?>
