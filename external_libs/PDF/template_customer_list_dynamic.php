<?php
require_once('common.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
//$sql = "select * from `Customer Dimension` where `Customer Key` = '".$id."'";
$sql="select C.`Customer Key`,C.`Customer Store Key`,C.`Customer Main XHTML Email`,C.`Customer Main Location`,C.`Customer Name`,C.`Customer Type`,C.`Customer Main XHTML Telephone`,C.`Customer Main Contact Name`,C.`Customer Main XHTML Address` from `Customer Dimension` C right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`) where CLD.`Customer List Key`=$id order by CLD.`Customer List Key`";
$result = mysql_query($sql);
$sql_static_name=mysql_fetch_array(mysql_query("select `Customer List Name` from `Customer List Dimension` where `Customer List Key`=$id"));
$static_list_name=$sql_static_name[0];
// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   global $myconf;
$dynamic_list_id=$_REQUEST['id'];
$awhere_sql=mysql_fetch_array(mysql_query("select `Customer List Metadata`,`Customer List Name` from `Customer List Dimension` where `Customer List Key`=$dynamic_list_id"));
$awhere=$awhere_sql[0];
$customer_list_name=$awhere_sql[1];

    $conf=$_SESSION['state']['customers']['table'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];


    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['where']))



        $awhere=$_REQUEST['where'];
    else
        $awhere=$conf['where'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['store_id'])    ) {
        $store=$_REQUEST['store_id'];
        $_SESSION['state']['customers']['store']=$store;
    } else
        $store=$_SESSION['state']['customers']['store'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
 

    $_SESSION['state']['customers']['table']['order']=$order;
    $_SESSION['state']['customers']['table']['order_dir']=$order_direction;
    $_SESSION['state']['customers']['table']['nr']=$number_results;
    $_SESSION['state']['customers']['table']['sf']=$start_from;
    $_SESSION['state']['customers']['table']['where']=$awhere;
    $_SESSION['state']['customers']['table']['type']=$type;
    $_SESSION['state']['customers']['table']['f_field']=$f_field;
    $_SESSION['state']['customers']['table']['f_value']=$f_value;


    $table='`Customer Dimension` C ';

//print "-> $awhere <-";
    if ($awhere) {
    
   
        $awhere=preg_replace('/\\\"/','"',$awhere);
        //    print "$awhere";
        
        
        $where_data=array(
        'product_ordered1'=>'∀',
        'product_not_ordered1'=>'',
        'product_not_received1'=>'',
        'from1'=>'',
        'to1'=>'',
        'dont_have'=>array(),
        'have'=>array(),
        'categories'=>''
        );
        
        $awhere=json_decode($awhere,TRUE);

        
        foreach ($awhere as $key=>$item) {
            $where_data[$key]=$item;
        }
        
        
        $where='where true';


//print_r($where_data);

 $use_categories =false;
        $use_otf =false;

        $where_categories='';
        if ($where_data['categories']!='') {
        
        $categories_keys=preg_split('/,/',$where_data['categories']);
        $valid_categories_keys=array();
        foreach ($categories_keys as $item) {
            if(is_numeric($item))
                $valid_categories_keys[]=$item;
        }
        $categories_keys=join($valid_categories_keys,',');
        if($categories_keys){
        $use_categories =true;
        $where_categories=sprintf(" and `Subject`='Customer' and `Category Key` in (%s)",$categories_keys);
        }
        
        
        } 
        


        if ($where_data['product_ordered1']!='') {
            if ($where_data['product_ordered1']!='∀') {
                $use_otf=true;
                $where_product_ordered1=extract_product_groups($where_data['product_ordered1']);
            } else
                $where_product_ordered1='true';
        } else{
            $where_product_ordered1='false';
        }
        
        if ($where_data['product_not_ordered1']!='') {
            if ($where_data['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($where_data['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($where_data['product_not_received1']!='') {
            if ($where_data['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($where_data['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

        $date_interval1=prepare_mysql_dates($where_data['from1'],$where_data['to1'],'`Invoice Date`','only_dates');
        if ($date_interval1['mysql']) {
            $use_otf=true;
        }

       
        if ($use_otf) {
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
        }
        
        
     if ($use_categories) {
         
         $table.='  left join   `Category Bridge` CatB on (C.`Customer Key`=CatB.`Subject Key`)   ';
        }
     




        $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ".$where_categories;

        foreach($where_data['dont_have'] as $dont_have) {
            switch ($dont_have) {
            case 'tel':
                $where.=sprintf(" and `Customer Main Telephone Key` IS NULL ");
                break;
            case 'email':
                $where.=sprintf(" and `Customer Main Email Key` IS NULL ");
                break;
            case 'fax':
                $where.=sprintf(" and `Customer Main Fax Key` IS NULL ");
                break;
            case 'address':
                $where.=sprintf(" and `Customer Main Address Incomplete`='Yes' ");
                break;
            }
        }
        foreach($where_data['have'] as $dont_have) {
            switch ($dont_have) {
            case 'tel':
                $where.=sprintf(" and `Customer Main Telephone Key` IS NOT NULL ");
                break;
            case 'email':
                $where.=sprintf(" and `Customer Main Email Key` IS NOT NULL ");
                break;
            case 'fax':
                $where.=sprintf(" and `Customer Main Fax Key` IS NOT NULL ");
                break;
            case 'address':
                $where.=sprintf(" and `Customer Main Address Incomplete`='No' ");
                break;
            }
        }


    } else {
        $where='where true ';
    }







    $filter_msg='';
    $wheref='';

    $currency='';
    if (is_numeric($store)) {
        $where.=sprintf(' and `Customer Store Key`=%d ',$store);
        $store=new Store($store);
        $currency=$store->data['Store Currency Code'];
    }



    if ($type=='all_customers') {
        $where.=sprintf(' and `Actual Customer`="Yes" ');
    }
    elseif($type=='active_customers') {
        $where.=sprintf(' and `Active Customer`="Yes" ');
    }

    //  print $f_field;


    if (($f_field=='customer name'     )  and $f_value!='') {
        $wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
    }
    elseif(($f_field=='postcode'     )  and $f_value!='') {
        $wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
    }
    else if ($f_field=='id'  )
        $wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
    else if ($f_field=='last_more' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
    else if ($f_field=='last_less' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
    else if ($f_field=='max' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`<=".$f_value."    ";
    else if ($f_field=='min' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`>=".$f_value."    ";
    else if ($f_field=='maxvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
    else if ($f_field=='minvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
    else if ($f_field=='country' and  $f_value!='') {
        if ($f_value=='UNK') {
            $wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
            $find_data=' '._('a unknown country');
        } else {

            $f_value=Address::parse_country($f_value);
            if ($f_value!='UNK') {
                $wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
                $country=new Country('code',$f_value);
                $find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
            }

        }
    }



    $sql="select count(Distinct C.`Customer Key`) as total from $table  $where $wheref";
//print "$sql<br/>\n";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from $table  $where ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

            $total_records=$row['total_without_filters'];
            $filtered=$row['total_without_filters']-$total;
        }

    } else {
        $filtered=0;
        $filter_total=0;
        $total_records=$total;
    }
    mysql_free_result($res);


    $rtext=$total_records." ".ngettext('customer','customers',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=sprintf("Showing all customers");



    //if($total_records>$number_results)
    // $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('customer name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
            break;
        case('postcode'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
            break;
        case('country'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
            break;

        case('id'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
            break;

        case('last_more'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
            break;
        case('last_more'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
            break;
        case('maxvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
            break;
        case('minvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
            break;


        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('customer name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
            break;
        case('id'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
            break;
        case('postcode'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
            break;
        case('country'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
            break;
        case('last_more'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
            break;
        case('last_less'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
            break;
        case('maxvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
            break;
        case('minvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
            break;
        }
    }
    else
        $filter_msg='';





    $_order=$order;
    $_dir=$order_direction;
    // if($order=='location'){
//      if($order_direction=='desc')
//        $order='country_code desc ,town desc';
//      else
//        $order='country_code,town';
//      $order_direction='';
//    }

//     if($order=='total'){
//       $order='supertotal';
//    }


    if ($order=='name')
        $order='`Customer File As`';
    elseif($order=='id')
    $order='`Customer Key`';
    elseif($order=='location')
    $order='`Customer Main Location`';
    elseif($order=='orders')
    $order='`Customer Orders`';
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
    elseif($order=='contact_since')
    $order='`Customer First Contacted Date`';
    elseif($order=='activity')
    $order='`Customer Type by Activity`';
    else
        $order='`Customer File As`';
    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction ";
    // print $sql;
    $result=mysql_query($sql);
// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

if(mysql_num_rows($result) > 1000)
{
echo "<span style='color:#F00; font-size:22px;'>More than 1000 records so can not print in PDF format</span>";
}
else
{
?>
<center>
<table width="100%" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:20px;">
	 <tr>
    <td colspan="3" align="center" style="font-size:34px;font-weight:bold;">Customer List : <?php echo $customer_list_name;?><hr></td>
  </tr>
<tr height="15px">
    <td width="48%" align="right" style="font-weight:bold;">Customer Name</td>
<td width="2%"></td>
    <td width="50%" style="font-weight:bold;">Postal Address</td>
</tr>
<?php
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
?>

<tr>
    <td width="48%" align="right"><?php echo $row['Customer Name']; ?></td>
<td width="2%"></td>
    <td width="50%"><?php $addr=strip_tags($row['Customer Main XHTML Address'],'<br/>');echo $addr."<br>"; ?></td>
</tr>
<?php
}
?>
</table></center>
<?php 
}
?>
