<?php
/*
 File: customer.php

 UI customer page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Kaktus

 Version 2.0
*/

include_once('common.php');
include_once('class.Customer.php');
include_once('class.Category.php');

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
               'container.css',
               'table.css'
           );
include_once('Theme.php');
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
              'address_data.js.php?tipo=customer&id='.$customer->id,
              'edit_delivery_address_common.js.php'
              // 'customer.js.php?id='.$customer->id
          );
//$smarty->assign('css_files',$css_files);
//$smarty->assign('js_files',$js_files);


//echo 'date_default_timezone_set: ' . date_default_timezone_get() . strftime("%sH:s %z",strtotime('2010-07-11 09:00:00 +00:00')). '<br />';

$customer->load('contacts');
$smarty->assign('customer',$customer);



$general_options_list=array();


$general_options_list[]=array('tipo'=>'url','url'=>'customer.php?id='.$customer->id,'label'=>_('Exit Edit'));
$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('customer_type',$customer->data['Customer Type']);
$css_files[]=$yui_path.'assets/skins/sam/autocomplete.css';
$css_files[]='css/edit_address.css';
$css_files[]='css/edit.css';
$js_files[]='js/edit_common.js';
$js_files[]='js/validate_telecom.js';

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



$js_files[]='edit_address.js.php';
$js_files[]='edit_contact_from_parent.js.php';

$js_files[]='edit_contact_telecom.js.php';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';
$js_files[]=sprintf('edit_customer.js.php?id=%d',$customer->id);


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



/*

$nodes=new nodes('`Category Dimension`');
$nodes->sql_condition = "AND `Category Subject`='Customer' AND `Category Store Key`=".$customer->data['Customer Store Key'] ;
$nodes->load_comb();
$comb=$nodes->comb;
//print_r($comb);

$sql=sprintf("select PCB.`Category Key`,`Category Position` from `Category Bridge` PCB left join `Category Dimension` C on (C.`Category Key`=PCB.`Category Key`)   where  PCB.`Subject Key`=%d  and `Subject`='Customer'    " ,
$customer->id);
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $parents=preg_replace('/\d+>$/','',$row['Category Position']);
  $root=preg_replace('/>.*$/','',$row['Category Position']);
  // print "$root $parents ".$row['Category Key']."\n";
  $comb[$root]['teeth'][$parents]['elements'][$row['Category Key']]['selected']=true;



}
mysql_free_result($res);




$smarty->assign('categories',$comb);
$smarty->assign('number_categories',count($comb));
*/

$main_telephone_warning=false;
$main_telephone_warnings='';
if ($customer->data['Customer Main Telephone Key']) {
    $main_telephone= new Telecom($customer->data['Customer Main Telephone Key']);
    $main_telephone_parents=$main_telephone->get_parent_keys();
    foreach($main_telephone_parents as $_key=>$_value) {

        if (($_value['Subject Type']=='Customer' and $_value['Subject Key']!=$customer->id)or $_value['Subject Type']=='Supplier') {
        $main_telephone_warning=true;
        
        }
    }
}
if($main_telephone_warning){
$main_telephone_warning='<img style="cursor:pointer" title="Other Customers/Supplier has this telephone" src="art/icons/error.png" alt="warning"/>';
}
$smarty->assign('main_telephone_warnings',$main_telephone_warnings);
$smarty->assign('main_telephone_warning',$main_telephone_warning);

//$smarty->assign('delivery_addresses',$delivery_addresses);
$smarty->display('edit_customer.tpl');
exit();



?>
