<?php
/*
 File: customer.php

 UI customer page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('class.Customer.php');
include_once('class.Category.php');
include_once('duplicate_warning.php');

if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit;
}

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $_SESSION['state']['customer']['id']=$_REQUEST['id'];
    $customer_id=$_REQUEST['id'];
} else {
    $customer_id=$_SESSION['state']['customer']['id'];
}



$modify=$user->can_edit('contacts');



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $_SESSION['state']['customer']['id']=$_REQUEST['id'];
    $customer_id=$_REQUEST['id'];
} else {
    $customer_id=$_SESSION['state']['customer']['id'];
}



$customer=new customer($customer_id);


if(!in_array($customer->data['Customer Store Key'],$user->stores)){
header('Location: customers.php?msg=forbidden');
exit;
}


$_SESSION['state']['customers']['store']=$customer->data['Customer Store Key'];

if (!$customer->id) {
    header('Location: customers.php?error='._('Customer not exists'));
    exit();

}

$_SESSION['state']['customer']['id']=$customer_id;

if (!$modify) {
    header('Location: customer.php');
    exit();

}

 $store=new Store($customer->data['Customer Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');
$site_keys=$store->get_active_sites_keys();

$no_sites=count($site_keys);
$smarty->assign('no_of_sites',$site_keys);

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
               'table.css'
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
            
                   'edit_address.js.php',
              'edit_delivery_address_common.js.php',
			  'edit_billing_address_common.js.php',
              'js/edit_common.js',
              'js/validate_telecom.js',
			  'js/aes.js',
			  'js/sha256.js'
         
              // 'customer.js.php?id='.$customer->id
          );
//$smarty->assign('css_files',$css_files);
//$smarty->assign('js_files',$js_files);


//echo 'date_default_timezone_set: ' . date_default_timezone_get() . strftime("%sH:s %z",strtotime('2010-07-11 09:00:00 +00:00')). '<br />';

$customer->load('contacts');
$smarty->assign('customer',$customer);

//print_r($customer);






$other_email=$customer->get_other_emails_data();

$registered_email=array();
$unregistered_email=array();
$main_email=array();
foreach($other_email as $email){
	$sql=sprintf("select `User Key` from `User Dimension` where `User Handle`='%s'", $email['email']);

	$result=mysql_query($sql);

	if($row=mysql_fetch_array($result)){
		$rnd=md5(rand()); 
		$epwcp1=sprintf("%sinsecure_key%s",$row['User Key'],$rnd);
		$registered_email[]=array('email'=>$email['email'],									
									'epwcp1'=>$epwcp1,
									'epwcp2'=>$rnd,
									'user_key'=>$row['User Key']
							);
	
	}	else
		$unregistered_email[]=array('email'=>$email['email']								
									//'epwcp1'=>$epwcp1,
									//'epwcp2'=>$rnd
							);
	
}

$sql=sprintf("select `User Key` from `User Dimension` where `User Handle`='%s'", $customer->get('Customer Main Plain Email'));
$result=mysql_query($sql);
if($row=mysql_fetch_array($result)){
	$rnd=md5(rand()); 
	$epwcp1=sprintf("%sinsecure_key%s",$row['User Key'],$rnd);
	$main_email=array('email'=>$customer->get('Customer Main Plain Email'),									
						'epwcp1'=>$epwcp1,
						'epwcp2'=>$rnd,
						'user_key'=>$row['User Key']
								);
}
							


//print_r($registered_email);
//print_r($unregistered_email);
$smarty->assign('registered_email',$registered_email);
$smarty->assign('unregistered_email',$unregistered_email);
$smarty->assign('main_email',$main_email);


$smarty->assign('unregistered_count',count($unregistered_email));


$general_options_list=array();

 $general_options_list[]=array('tipo'=>'url','url'=>'customer_categories.php?store_id='.$store->id.'&id=0','label'=>_('Categories'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Lists'));
$general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php?store='.$store->id,'label'=>_('Advanced Search'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

$general_options_list[]=array('class'=>'return','tipo'=>'url','url'=>'customer.php?id='.$customer->id,'label'=>_('Customer').' &#8617;');
//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('other_email_login_handle',$customer->get_other_email_login_handle());
list($site_customer, $login_stat)=$customer->is_user_customer($customer_id);

$_login_stat=array();

if($site_customer){
	
	foreach($login_stat as $key=>$value){

	if($key=='User Last Login' || $key=='User Last Failed Login'){
		$value=strftime("%a %e %b %y %H:%M", strtotime($value." +00:00"));
	}

	$_login_stat[preg_replace('/\s/','',$key)]=$value;
	}
}

$smarty->assign('login_stat',$_login_stat);


$smarty->assign('site_customer',$site_customer);
$smarty->assign('customer_type',$customer->data['Customer Type']);
$css_files[]=$yui_path.'assets/skins/sam/autocomplete.css';
$css_files[]='css/edit_address.css';
$css_files[]='css/edit.css';


if ($customer->data['Customer Type']=='Company') {
    $company=new Company($customer->data['Customer Company Key']);
    if (!$company->id) {
        print "error no company found".print_r($customer);
    }
    $smarty->assign('company',$company);

    $offset=1;// 0 is reserved to new address
    $addresses=$company->get_addresses($offset);
    $smarty->assign('addresses',$addresses);
    $number_of_addresses=count($addresses);
    $smarty->assign('number_of_addresses',$number_of_addresses);

    $contacts=$company->get_contacts($offset);
    $smarty->assign('contacts',$contacts);
    $number_of_contacts=count($contacts);
    $smarty->assign('number_of_contacts',$number_of_contacts);
    $js_files[]=sprintf('edit_company.js.php?id=%d&scope=Customer&scope_key=%d',$company->id,$customer->id);

} else {

    $contact=new Contact($customer->data['Customer Main Contact Key']);
    $smarty->assign('contact',$contact);

    $js_files[]=sprintf('edit_contact.js.php?id=%d&scope=Customer&scope_key=%d',$contact->id,$customer->id);


}


$smarty->assign('scope','customer');
$smarty->assign('scope_key',$customer->id);


if (isset($_REQUEST['p'])) {

        $smarty->assign('parent_list',$_REQUEST['p']);


    if ($_REQUEST['p']=='cs') {

        $order=$_SESSION['state']['customers']['table']['order'];
        $order_label=$order;
        if ($order=='name') {
            $order='`Customer File As`';
            $order_label=_('Name');
        }
        elseif($order=='id') {
            $order='`Customer Key`';
            $order_label=_('ID');
        }
        elseif($order=='location')
        $order='`Customer Main Location`';
        elseif($order=='orders') {
            $order='`Customer Orders`';
            $order_label='# '._('Orders');
        }
        elseif($order=='email')
        $order='`Customer Main Plain Email`';
        elseif($order=='telephone')
        $order='`Customer Main Plain Telephone`';
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
        $order='`Customer Net Payments`';
        elseif($order=='top_profits')
        $order='`Customer Profits Top Percentage`';
        elseif($order=='top_balance')
        $order='`Customer Balance Top Percentage`';
        elseif($order=='top_orders')
        $order='``Customer Orders Top Percentage`';
        elseif($order=='top_invoices')
        $order='``Customer Invoices Top Percentage`';
        elseif($order=='total_refunds')
        $order='`Customer Total Refunds`';

        elseif($order=='activity')
        $order='`Customer Type by Activity`';
        else
            $order='`Customer File As`';

        $_order=preg_replace('/`/','',$order);
        $sql=sprintf("select `Customer Key` as id , `Customer Name` as name from `Customer Dimension`   where  `Customer Store Key` in (%s)  and %s < %s  order by %s desc  limit 1",join(',',$user->stores),$order,prepare_mysql($customer->get($_order)),$order);

        $result=mysql_query($sql);
        if (!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
            $prev=array('id'=>0,'name'=>'');
        mysql_free_result($result);

        $smarty->assign('prev',$prev);
        $sql=sprintf("select `Customer Key` as id , `Customer Name` as name from `Customer Dimension`     where `Customer Store Key` in (%s) and  %s>%s  order by %s   ",join(',',$user->stores),$order,prepare_mysql($customer->get($_order)),$order);

        $result=mysql_query($sql);
        if (!$next=mysql_fetch_array($result, MYSQL_ASSOC))
            $next=array('id'=>0,'name'=>'');
        mysql_free_result($result);
        $smarty->assign('parent_info',"p=cs&");

        $smarty->assign('prev',$prev);
        $smarty->assign('next',$next);
       
        $smarty->assign('parent_url','customers.php?store='.$store->id);
        $parent_title=$store->data['Store Code'].' '._('Customers').' ('.$order_label.')';
        $smarty->assign('parent_title',$parent_title);

    }



}

$sql=sprintf("select * from kbase.`Salutation Dimension` S left join kbase.`Language Dimension` L on S.`Language Code`=L.`Language ISO 639-1 Code`  where `Language Code`=%s limit 1000",prepare_mysql($myconf['lang']));
$result=mysql_query($sql);
$salutations=array();
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $salutations[]=array('txt'=>$row['Salutation'],'relevance'=>$row['Relevance'],'id'=>$row['Salutation Key']);
}
mysql_free_result($result);

$smarty->assign('prefix',$salutations);

$editing_block=$_SESSION['state']['customer']['edit'];
$smarty->assign('edit',$editing_block);
if (isset($_REQUEST['return_to_order'])) {
    $smarty->assign('return_to_order',$_REQUEST['return_to_order']);
}


  $js_files[]='address_data.js.php?tipo=customer&id='.$customer->id;

$js_files[]='edit_contact_from_parent.js.php';

$js_files[]='edit_contact_telecom.js.php';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';
$js_files[]=sprintf('edit_customer.js.php?id=%d&forgot_count=%d&register_count=%d',$customer->id,count($registered_email),count($unregistered_email));


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
//$delivery_addresses=$customer->get_address_objects();


$categories_value=array();
$categories=array();
$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d",$customer->data['Customer Store Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $tmp=new Category($row['Category Key']);
    $selected_array=$tmp->sub_category_selected_by_subject($customer->id);


    if (count($selected_array)==0) {
        $tmp_selected='';
    } else {
        $tmp_selected=array_pop($selected_array);
    }

    $categories[$row['Category Key']]=$tmp;
    $categories_value[$row['Category Key']]=$tmp_selected;

}

$smarty->assign('categories',$categories);
$smarty->assign('categories_value',$categories_value);





$main_email_warning=false;
$main_email_warnings='';
if ($customer->data['Customer Main Email Key']) {
    $main_email= new Email($customer->data['Customer Main Email Key']);
    $main_email_parents=$main_email->get_parent_keys();
    foreach($main_email_parents as $_key=>$_value) {

        if (($_value['Subject Type']=='Customer' and $_value['Subject Key']!=$customer->id)or $_value['Subject Type']=='Supplier') {
        $main_email_warning=true;
        
        }
    }
}
if($main_email_warning){
$main_email_warning='<img style="cursor:pointer" title="Other Customers/Supplier has this email" src="art/icons/error.png" alt="warning"/>';
}
$smarty->assign('main_email_warnings',$main_email_warnings);
$smarty->assign('main_email_warning',$main_email_warning);


/*
$main_telephone_warning=false;
$main_telephone_warnings='';
$main_telephone_warning_data=array();
if ($customer->data['Customer Main Telephone Key']) {
    $main_telephone= new Telecom($customer->data['Customer Main Telephone Key']);
    $main_telephone_parents=$main_telephone->get_parent_keys();
    foreach($main_telephone_parents as $_key=>$_value) {

        if (($_value['Subject Type']=='Customer' and $_value['Subject Key']!=$customer->id)or $_value['Subject Type']=='Supplier') {
        $main_telephone_warning=true;
		
		switch($_value['Subject Type']){
		case 'Customer':
			$subject=new Customer($_value['Subject Key']);
			$_store=new Store($subject->data['Customer Store Key']);
			  $main_telephone_warning.=sprintf(", %s (%s) <a href=\"customer.php?id=%d\">%s</a> %s",_('Customer'),$store->data['Store Code'],$subject->id, $subject->get_formated_id(),$subject->data['Customer Name']);
			
			$subject_type=_('Customer').' '.$_store->data['Store Code'].'';
		break;
		case 'Supplier':
			$subject=new Supplier($_value['Subject Key']);
			
			$main_telephone_warning.=sprintf(", %s <a href=\"supplier.php?id=%d\">%s</a>",_('Supplier'),$subject->id,$subject->data['Customer Name']);
			  
			$subject_type=_('Supplier');
		break;
		}
		
     
		}
    }
}
*/
$main_telephone_warning=check_duplicates($customer);
$smarty->assign('main_telephone_warning',$main_telephone_warning);
$smarty->assign('main_telephone_warning_key',$customer->get('Customer Main Telephone Key'));

$main_mobile_warning=check_duplicates($customer, 'Mobile');
$smarty->assign('main_mobile_warning',$main_mobile_warning);
$smarty->assign('main_mobile_warning_key',$customer->get('Customer Main Mobile Key'));

$main_fax_warning=check_duplicates($customer, 'FAX');
$smarty->assign('main_fax_warning',$main_fax_warning);
$smarty->assign('main_fax_warning_key',$customer->get('Customer Main FAX Key'));

$other_telephone_warning=check_duplicates($customer, 'other_telephone');
$smarty->assign('other_telephone_warning',$other_telephone_warning);

$other_mobile_warning=check_duplicates($customer, 'other_mobile');
$smarty->assign('other_mobile_warning',$other_mobile_warning);

$other_fax_warning=check_duplicates($customer, 'other_fax');
$smarty->assign('other_fax_warning',$other_fax_warning);
//$smarty->assign('delivery_addresses',$delivery_addresses);
$smarty->assign('id',$myconf['customer_id_prefix'].sprintf("%05d",$customer->id));



$correlation_msg='';
 $msg='';
        $sql=sprintf("select * from `Customer Merge Bridge` M left join `Customer Deleted Dimension` D  on (D.`Customer Key`=`Merged Customer Key`)   where M.`Customer Key`=%d and `Date Merged`>= DATE_SUB(NOW(),INTERVAL 1 DAY);   ",$customer->id);
   // print $sql;
    $res2=mysql_query($sql);
        if ($row2=mysql_fetch_assoc($res2)) {
            $msg.=$row2['Customer Card'];
        }
       
        $msg=preg_replace('/^,/','',$msg);
        if ($msg!='') {
            $correlation_msg='<p>'._('Customer recently merged with').': '.$msg.'</p>';

        }
$smarty->assign('recent_merges',$correlation_msg);


$smarty->assign('options_box_width','550px');


$tax_codes=array();

$sql=sprintf("select * from `Tax Category Dimension`");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$tax_codes[$row['Tax Category Code']]=array('code'=>$row['Tax Category Code'],'name'=>$row['Tax Category Name'],'rate'=>$row['Tax Category Rate']);
}

$smarty->assign('tax_codes',$tax_codes);
$smarty->assign('hq_country',$corporate_country_code);

//show case 		
$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer'");
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
		$show_case[$value]=Array('value'=>$row[$key], 'lable'=>$key);
	}
}
//print_r($show_case);
$smarty->assign('show_case',$show_case);	
//print_r($customer);
$sql=sprintf("select `User Key` from `User Dimension` where `User Parent Key`=%d", $customer->id);
//print $sql;
$result=mysql_query($sql);
if($row=mysql_fetch_array($result))
	$smarty->assign('user_main_id',$row['User Key']);	


$delete_button_tooltip='';
if($customer->get('Customer With Orders')=='Yes'){
$delete_button_tooltip=_('Can not be deleted because customer has placed orders').'.';
}else if( $customer->number_of_user_logins()>0){
$delete_button_tooltip=_('Can not be deleted because contact had logged in').'.';

}
$smarty->assign('delete_button_tooltip',$delete_button_tooltip);	

$smarty->assign('parent','customers');
$smarty->assign('title',_('Edit Customer').': '.$customer->get('customer name'));



$smarty->display('edit_customer.tpl');
exit();



?>
