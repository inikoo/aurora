<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 May 2018 at 18:11:04 CEST, Mijas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3

*/


$currency = '';
$where    = 'where true';
$table    = '`Customer Dimension` C ';
$group_by = ' group by OTF.`Customer Key` ';





    include_once 'class.Category.php';
    $category = new Category($parameters['parent_key']);

    if (!in_array($category->data['Category Store Key'], $user->stores)) {
        return;
    }

    $store=get_object('store',$category->get('Store Key'));

    if($store->get('Store Family Category Key')==$category->get('Category Root Key')){



        $table = '`Order Transaction Fact` OTF  left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`)  left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)  ';


        $fav=sprintf('select count(distinct `Customer Favourite Product Product ID`) from `Customer Favourite Product Fact` left join `Product Dimension` on (`Product ID`=`Customer Favourite Product Product ID`) where  `Customer Favourite Product Customer Key`=OTF.`Customer Key` and  `Product Family Category Key`=%d',$parameters['parent_key']);

        $where = sprintf(' where  P.`Product Family Category Key`=%d ', $parameters['parent_key']);



    }elseif($store->get('Store Department Category Key')==$category->get('Category Root Key')){
        $table = '`Order Transaction Fact` OTF  left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`)  left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)  ';
        $fav=sprintf('select count(distinct `Customer Favourite Product Product ID`) from `Customer Favourite Product Fact` left join `Product Dimension` on (`Product ID`=`Customer Favourite Product Product ID`) where `Customer Favourite Product Customer Key`=OTF.`Customer Key` and `Product Department Category Key`=%d',$parameters['parent_key']);

        $where = sprintf(' where  P.`Product Department Category Key`=%d ', $parameters['parent_key']);

    }else{
exit();
    }



$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'name') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );


} elseif (($parameters['f_field'] == 'postcode') and $f_value != '') {
    $wheref = "  and  `Customer Main Plain Postal Code` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'id') {
    $wheref .= " and  `Customer Key` like '".addslashes(
            preg_replace('/\s*|\,|\./', '', $f_value)
        )."%' ";
} elseif ($parameters['f_field'] == 'last_more' and is_numeric($f_value)) {
    $wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
} elseif ($parameters['f_field'] == 'last_less' and is_numeric($f_value)) {
    $wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'max' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Orders`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'min' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Orders`>=".$f_value."    ";
} elseif ($parameters['f_field'] == 'maxvalue' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Net Balance`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'minvalue' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Net Balance`>=".$f_value."    ";
}

$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'formatted_id') {
    $order = 'C.`Customer Key`';
} elseif ($order == 'location') {
    $order = '`Customer Location`';
} elseif ($order == 'last_invoice') {
    $order = 'last_invoice';
} elseif ($order == 'invoices') {
    $order = 'invoices';
}elseif ($order == 'invoiced_amount') {
    $order = 'invoiced_amount';
}elseif ($order == 'basket_amount') {
    $order = 'basket_amount';
}elseif ($order == 'favourited') {
    $order = 'favourited';
}  else {
    $order = '`Customer File As`';
}


$sql_totals = "select count(Distinct C.`Customer Key`) as num from $table  $where ";


$fields = " C.`Customer Key`,`Customer Name`,`Customer First Contacted Date`,`Customer Type by Activity`,`Customer Store Key`,`Customer Location` ,
  max(`Invoice Date`)    as last_invoice, count(distinct `Invoice Key`) as invoices,
  sum(if(`Invoice Key`>0, `Order Transaction Amount`,0)) as invoiced_amount,`Order Currency Code`,
  
   sum(if(`Current Dispatching State`='In Process', `Order Transaction Amount`,0)) as basket_amount, ($fav) as favourited
   
  
  
 
 ";


//print "$sql_totals\n";
?>
