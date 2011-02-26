<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
require_once 'common.php';
//require_once '_order.php';

//require_once '_contact.php';
require_once 'class.Customer.php';
require_once 'class.Timer.php';




if (!isset($_REQUEST['tipo']))  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('customers'):
$list_name=$_REQUEST['textValue'];
$list_type=$_REQUEST['typeValue'];
echo "List \"".$list_name."\" of type \"".$list_type."\" is saved";
    if (!$user->can_view('customers'))
        exit();
    list_customers();
    break;
case('customer_list_static'):
    list_customer_list_static();
    break;
case('customer_list_dynamic'):
    list_customer_list_dynamic();
    break;
case('customers_lists'):
    list_customers_lists();
    break;

default:
    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);
}

function list_customers() {

$list_name=$_REQUEST['textValue'];
$list_type=$_REQUEST['typeValue'];

    global $myconf;

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
/*
//print "-> $awhere <-";
    if ($awhere) {
    
   
        $awhere=preg_replace('/\\\"/','"',$awhere);
       //    print "$awhere";
        $awhere=json_decode($awhere,TRUE);

        
        $where='where true';

        $use_otf =false;

        if ($awhere['product_ordered1']!='') {
            if ($awhere['product_ordered1']!='∀') {
                $use_otf=true;
                $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
            } else
                $where_product_ordered1='true';
        } else{
            $where_product_ordered1='false';
        }
        
        if ($awhere['product_not_ordered1']!='') {
            if ($awhere['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($awhere['product_not_received1']!='') {
            if ($awhere['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

        $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'`Invoice Date`','only_dates');
        if ($date_interval1['mysql']) {
            $use_otf=true;
        }


        if ($use_otf) {
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
        }

     




        $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ";

        foreach($awhere['dont_have'] as $dont_have) {
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
        foreach($awhere['have'] as $dont_have) {
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

*/
///////////////////////////////////////////////////////////////////////////////////

//print "-> $awhere <-";
    if ($awhere) {
    
   
        $awhere=preg_replace('/\\\"/','"',$awhere);
        //   print "$awhere";
        
        
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

 /// print_r($awhere);      
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

/////////////////////////////////////////////////////////////////////////////////////




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
    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction limit $start_from,$number_results";
 // print $sql;
    $adata=array();


    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

$customer_key=$data['Customer Key'];
        $id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
        if ($data['Customer Type']=='Person') {
            $name='<img src="art/icons/user.png" alt="('._('Person').')">';
        } else {
            $name='<img src="art/icons/building.png" alt="('._('Company').')">';

        }

        $name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';



        if ($data['Customer Orders']==0)
            $last_order_date='';
        else
            $last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

        $contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


        if ($data['Customer Billing Address Link']=='Contact')
            $billing_address='<i>'._('Same as Contact').'</i>';
        else
            $billing_address=$data['Customer XHTML Billing Address'];

        if ($data['Customer Delivery Address Link']=='Contact')
            $delivery_address='<i>'._('Same as Contact').'</i>';
        elseif($data['Customer Delivery Address Link']=='Billing')
        $delivery_address='<i>'._('Same as Billing').'</i>';
        else
            $delivery_address=$data['Customer XHTML Main Delivery Address'];

        $adata[]=array(
                     'id'=>$id,
                     'name'=>$name,
                     'location'=>$data['Customer Main Location'],
                     'orders'=>number($data['Customer Orders']),
                     'invoices'=>$data['Customer Orders Invoiced'],
                     'email'=>$data['Customer Main XHTML Email'],
                     'telephone'=>$data['Customer Main XHTML Telephone'],
                     'last_order'=>$last_order_date,
                     'contact_since'=>$contact_since,
                     'total_payments'=>money($data['Customer Net Payments'],$currency),
                     'net_balance'=>money($data['Customer Net Balance'],$currency),
                     'total_refunds'=>money($data['Customer Net Refunds'],$currency),
                     'total_profit'=>money($data['Customer Profit'],$currency),
                     'balance'=>money($data['Customer Outstanding Net Balance'],$currency),
                     'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
                     'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
                     'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
                     'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
                     'contact_name'=>$data['Customer Main Contact Name'],
                     'address'=>$data['Customer Main XHTML Address'],
                     'billing_address'=>$billing_address,
                     'delivery_address'=>$delivery_address,
                     'activity'=>$data['Customer Type by Activity']

                 );
 }
    mysql_free_result($result);

// **************************************************************************start **************************************************************************************************

    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction";
 // print $sql;
    $adata=array();


    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

$customer_key=$data['Customer Key'];
        $id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
        if ($data['Customer Type']=='Person') {
            $name='<img src="art/icons/user.png" alt="('._('Person').')">';
        } else {
            $name='<img src="art/icons/building.png" alt="('._('Company').')">';

        }

        $name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';



        if ($data['Customer Orders']==0)
            $last_order_date='';
        else
            $last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

        $contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


        if ($data['Customer Billing Address Link']=='Contact')
            $billing_address='<i>'._('Same as Contact').'</i>';
        else
            $billing_address=$data['Customer XHTML Billing Address'];

        if ($data['Customer Delivery Address Link']=='Contact')
            $delivery_address='<i>'._('Same as Contact').'</i>';
        elseif($data['Customer Delivery Address Link']=='Billing')
        $delivery_address='<i>'._('Same as Billing').'</i>';
        else
            $delivery_address=$data['Customer XHTML Main Delivery Address'];

        $adata[]=array(
                     'id'=>$id,
                     'name'=>$name,
                     'location'=>$data['Customer Main Location'],
                     'orders'=>number($data['Customer Orders']),
                     'invoices'=>$data['Customer Orders Invoiced'],
                     'email'=>$data['Customer Main XHTML Email'],
                     'telephone'=>$data['Customer Main XHTML Telephone'],
                     'last_order'=>$last_order_date,
                     'contact_since'=>$contact_since,
                     'total_payments'=>money($data['Customer Net Payments'],$currency),
                     'net_balance'=>money($data['Customer Net Balance'],$currency),
                     'total_refunds'=>money($data['Customer Net Refunds'],$currency),
                     'total_profit'=>money($data['Customer Profit'],$currency),
                     'balance'=>money($data['Customer Outstanding Net Balance'],$currency),
                     'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
                     'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
                     'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
                     'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
                     'contact_name'=>$data['Customer Main Contact Name'],
                     'address'=>$data['Customer Main XHTML Address'],
                     'billing_address'=>$billing_address,
                     'delivery_address'=>$delivery_address,
                     'activity'=>$data['Customer Type by Activity']

                 );

 //$meta_data=$where;
$metadata = json_encode($where_data);
//echo "<br>meta=".$metadata;
//$meta_data=$where_data;      
$store_id=$_REQUEST['store_id'];
$dt=date("Y-m-d H:i:s");
$list_sql=sprintf("insert into `Customer List Dimension` (`Customer List Key`,`Customer List Store Key`,`Customer List Name`,`Customer List Type`,`Customer List Metadata`,`Customer List Creation Date`) values (%d,%d,%s,%s,%s,%s)",
                         '',
                         $store_id,
                         prepare_mysql($list_name),
                         prepare_mysql($list_type),
                        '\'\'',
                         prepare_mysql($dt)
                        );

$list_query=mysql_query($list_sql);
$fetch_type_sql=mysql_fetch_array(mysql_query("select `Customer List Type`,max(`Customer List Key`) as max_customer_list_key from `Customer List Dimension` where `Customer List Key`=(select max(`Customer List Key`) from `Customer List Dimension`)"));
$max_customer_list_key=$fetch_type_sql['max_customer_list_key'];
if($fetch_type_sql[0]=='Dynamic'){
$metadata_sql=sprintf("update `Customer List Dimension` set `Customer List Metadata`=%s where `Customer List Key`=".$max_customer_list_key.""
                         ,prepare_mysql($metadata)
                         );

$metadata_query=mysql_query($metadata_sql);
}
if($list_type=='static')
{$customer_list_key=mysql_fetch_array(mysql_query("select max(`Customer List Key`) from `Customer List Dimension` order by `Customer List Creation Date` DESC"));
$customer_list_id=$customer_list_key[0];
$list_sql2=sprintf("insert into `Customer List Customer Bridge` (`Customer List Key`,`Customer Key`) values (%d,%d)",
                         $customer_list_id,
                         $customer_key
                        );
$list_query2=mysql_query($list_sql2);

}
  
 }
    mysql_free_result($result);
//print_r($dataid);//-----------------------------------------------------------------------------------------------------
/*$response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,

                                      'records_perpage'=>$number_results,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
*/
//-------------------------------------------------------------------------------------------------------------------------- 
}


function list_customer_list_static() {
    global $myconf;
$static_list_id=$_REQUEST['id'];
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
    /* $_SESSION['state']['customers']['table']=array(
     'order'=>$order,
     'order_dir'=>$order_direction,
     'nr'=>$number_results,
     'sf'=>$start_from,
     'where'=>$where,
     'type'=>$type,

     'f_field'=>$f_field,
     'f_value'=>$f_value
     );*/




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
        $awhere=json_decode($awhere,TRUE);

        
        $where='where true';



//print_r($awhere);


        $use_otf =false;

        if ($awhere['product_ordered1']!='') {
            if ($awhere['product_ordered1']!='∀') {
                $use_otf=true;
                $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
            } else
                $where_product_ordered1='true';
        } else{
            $where_product_ordered1='false';
        }
        
        if ($awhere['product_not_ordered1']!='') {
            if ($awhere['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($awhere['product_not_received1']!='') {
            if ($awhere['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

        $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'`Invoice Date`','only_dates');
        if ($date_interval1['mysql']) {
            $use_otf=true;
        }


        if ($use_otf) {
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
        }

     




        $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ";

        foreach($awhere['dont_have'] as $dont_have) {
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
        foreach($awhere['have'] as $dont_have) {
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
       // $where.=sprintf(' and `Customer Store Key`=%d ',$store);
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



$sql="select count(C.`Customer Key`) as total from `Customer Dimension` C right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`) where CLD.`Customer List Key`=$static_list_id ";  
////$sql="select count(C.`Customer Key`) as total from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref";
//$sql="select count(CLCB.`Customer List Key`) as total from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref";
//print "$sql<br/>\n";
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`) $where ";
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


    $rtext=$total_records." ".ngettext('Customer Data','Customers Data',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=sprintf("Showing all Lists");



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

$order='CLD.`Customer List Key`';
  //  $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction limit $start_from,$number_results";
//$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref  order by $order DESC $order_direction limit $start_from,$number_results";
///$sql="select distinct CLD.`Customer List key`,CLD.`Customer List Name`,CLD.`Customer List Store Key`,CLD.`Customer List Creation Date` from `Customer List Dimension` CLD right join `Customer List Customer Bridge` CLCB on (CLD.`Customer List Key`=CLCB.`Customer List Key`) and CLD.`Customer List Type`='Static' order by $order DESC $order_direction limit $start_from,$number_results";
$sql="select C.`Customer Key`,C.`Customer Store Key`,C.`Customer Main XHTML Email`,C.`Customer Main Location`,C.`Customer Name`,C.`Customer Type`,C.`Customer Main XHTML Telephone` from `Customer Dimension` C right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`) where CLD.`Customer List Key`=$static_list_id order by $order DESC $order_direction limit $start_from,$number_results";
//print $sql;
    $adata=array();
    $result=mysql_query($sql);
   while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
        if ($data['Customer Type']=='Person') {
            $name='<img src="art/icons/user.png" alt="('._('Person').')">';
        } else {
            $name='<img src="art/icons/building.png" alt="('._('Company').')">';
        }
        $name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';
      
$customer_list_key=" <a href='new_campaign.php?customer_list_key=".$static_list_id."'>"."Create".'</a>';
        $adata[]=array(
		     
                    'id'=>$id,
                     'name'=>$name,
                   'location'=>$data['Customer Main Location'],
                    'store_key'=>$data['Customer Store Key'],
                     'email'=>$data['Customer Main XHTML Email'],
                     'telephone'=>$data['Customer Main XHTML Telephone'],
               
                     'customer_list_key'=>$customer_list_key,
                    
                 );

   }
    mysql_free_result($result);


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}



function list_customer_list_dynamic() {
    global $myconf;

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
    /* $_SESSION['state']['customers']['table']=array(
     'order'=>$order,
     'order_dir'=>$order_direction,
     'nr'=>$number_results,
     'sf'=>$start_from,
     'where'=>$where,
     'type'=>$type,

     'f_field'=>$f_field,
     'f_value'=>$f_value
     );*/




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
        $awhere=json_decode($awhere,TRUE);

        
        $where='where true';



//print_r($awhere);


        $use_otf =false;

        if ($awhere['product_ordered1']!='') {
            if ($awhere['product_ordered1']!='∀') {
                $use_otf=true;
                $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
            } else
                $where_product_ordered1='true';
        } else{
            $where_product_ordered1='false';
        }
        
        if ($awhere['product_not_ordered1']!='') {
            if ($awhere['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($awhere['product_not_received1']!='') {
            if ($awhere['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

        $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'`Invoice Date`','only_dates');
        if ($date_interval1['mysql']) {
            $use_otf=true;
        }


        if ($use_otf) {
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
        }

     



        $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ";

        foreach($awhere['dont_have'] as $dont_have) {
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
        foreach($awhere['have'] as $dont_have) {
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
       // $where.=sprintf(' and `Customer Store Key`=%d ',$store);
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



$sql="select count(distinct `Customer List Key`) as total from `Customer List Dimension` where `Customer List Type`='Dynamic' ";  
////$sql="select count(C.`Customer Key`) as total from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref";
//$sql="select count(CLCB.`Customer List Key`) as total from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref";
//print "$sql<br/>\n";
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`) $where ";
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


    $rtext=$total_records." ".ngettext('List Data','Lists Data',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=sprintf("Showing all Lists");



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

$order='CLD.`Customer List Key`';
  //  $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction limit $start_from,$number_results";
//$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref  order by $order DESC $order_direction limit $start_from,$number_results";
$sql="select distinct CLD.`Customer List key`,CLD.`Customer List Name`,CLD.`Customer List Store Key`,CLD.`Customer List Creation Date` from `Customer List Dimension` CLD  where  CLD.`Customer List Type`='Dynamic' order by $order DESC $order_direction limit $start_from,$number_results";
//print $sql;
    $adata=array();



    $result=mysql_query($sql);
   while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


       /* $id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
        if ($data['Customer Type']=='Person') {
            $name='<img src="art/icons/user.png" alt="('._('Person').')">';
        } else {
            $name='<img src="art/icons/building.png" alt="('._('Company').')">';

        }

        $name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';



        if ($data['Customer Orders']==0)
            $last_order_date='';
        else
            $last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

        $contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


        if ($data['Customer Billing Address Link']=='Contact')
            $billing_address='<i>'._('Same as Contact').'</i>';
        else
            $billing_address=$data['Customer XHTML Billing Address'];

        if ($data['Customer Delivery Address Link']=='Contact')
            $delivery_address='<i>'._('Same as Contact').'</i>';
        elseif($data['Customer Delivery Address Link']=='Billing')
        $delivery_address='<i>'._('Same as Billing').'</i>';
        else
            $delivery_address=$data['Customer XHTML Main Delivery Address'];*/
//print("select count(`Customer Key`) from `Customer List Customer Bridge` where `customer List Key`=".$data['Customer List key']);

/*
$customer_no_table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
print $data['Customer List Metadata'];print("***********");
$sql_customer_no="select count(`Customer Key`) from".$customer_no_table.$data['Customer List Metadata']." and `Customer List Key`=".$data['Customer List key'];
print $sql_customer_no;
*/

//$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction limit $start_from,$number_results";



$sql_no_of_customer=mysql_fetch_array(mysql_query("select count(`Customer Key`) from `Customer List Customer Bridge` where `customer List Key`=".$data['Customer List key']));
//$sql_no_of_customer1=mysql_fetch_array(mysql_query($sql_no_of_customer));
//print $sql_no_of_customer;
$customer_list_key=" <a href='new_campaign.php?customer_list_key=".$data['Customer List key']."'>"."Create".'</a>';
        $adata[]=array(
		   //  'no_of_customer'=>$sql_no_of_customer[0],
                     
                     'customer_list_name'=>$data['Customer List Name'],
                     'customer_list_key'=>$customer_list_key,
                     'customer_list_creation_date'=>$data['Customer List Creation Date'],
                     
                    

                 );

   }
    mysql_free_result($result);


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}




function list_customers_lists() {
    global $myconf;

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
    /* $_SESSION['state']['customers']['table']=array(
     'order'=>$order,
     'order_dir'=>$order_direction,
     'nr'=>$number_results,
     'sf'=>$start_from,
     'where'=>$where,
     'type'=>$type,

     'f_field'=>$f_field,
     'f_value'=>$f_value
     );*/




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
        $awhere=json_decode($awhere,TRUE);

        
        $where='where true';



//print_r($awhere);


        $use_otf =false;

        if ($awhere['product_ordered1']!='') {
            if ($awhere['product_ordered1']!='∀') {
                $use_otf=true;
                $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
            } else
                $where_product_ordered1='true';
        } else{
            $where_product_ordered1='false';
        }
        
        if ($awhere['product_not_ordered1']!='') {
            if ($awhere['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($awhere['product_not_received1']!='') {
            if ($awhere['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

        $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'`Invoice Date`','only_dates');
        if ($date_interval1['mysql']) {
            $use_otf=true;
        }


        if ($use_otf) {
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
        }

     




        $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ";

        foreach($awhere['dont_have'] as $dont_have) {
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
        foreach($awhere['have'] as $dont_have) {
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
       // $where.=sprintf(' and `Customer Store Key`=%d ',$store);
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



$sql="select count(distinct `Customer List Key`) as total from `Customer List Customer Bridge` ";  
////$sql="select count(C.`Customer Key`) as total from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref";
//$sql="select count(CLCB.`Customer List Key`) as total from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref";
//print "$sql<br/>\n";
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from $table  right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`) $where ";
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


    $rtext=$total_records." ".ngettext('List Data','Lists Data',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=sprintf("Showing all Lists");



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

$order='CLD.`Customer List Key`';
  //  $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction limit $start_from,$number_results";
//$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`)  $where $wheref  order by $order DESC $order_direction limit $start_from,$number_results";
//$sql="select distinct CLD.`Customer List key`,CLD.`Customer List Name`,CLD.`Customer List Store Key`,CLD.`Customer List Creation Date` from `Customer List Dimension` CLD right join `Customer List Customer Bridge` CLCB on (CLD.`Customer List Key`=CLCB.`Customer List Key`) and CLD.`Customer List Type`='Static' order by $order DESC $order_direction limit $start_from,$number_results";


$sql="select distinct CLD.`Customer List key`,CLD.`Customer List Name`,CLD.`Customer List Store Key`,CLD.`Customer List Creation Date`,CLD.`Customer List Type` from `Customer List Dimension` CLD   order by $order DESC $order_direction limit $start_from,$number_results";

//print $sql;
    $adata=array();



    $result=mysql_query($sql);
   while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


       /* $id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
        if ($data['Customer Type']=='Person') {
            $name='<img src="art/icons/user.png" alt="('._('Person').')">';
        } else {
            $name='<img src="art/icons/building.png" alt="('._('Company').')">';

        }

        $name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';



        if ($data['Customer Orders']==0)
            $last_order_date='';
        else
            $last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

        $contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


        if ($data['Customer Billing Address Link']=='Contact')
            $billing_address='<i>'._('Same as Contact').'</i>';
        else
            $billing_address=$data['Customer XHTML Billing Address'];

        if ($data['Customer Delivery Address Link']=='Contact')
            $delivery_address='<i>'._('Same as Contact').'</i>';
        elseif($data['Customer Delivery Address Link']=='Billing')
        $delivery_address='<i>'._('Same as Billing').'</i>';
        else
            $delivery_address=$data['Customer XHTML Main Delivery Address'];*/
//print("select count(`Customer Key`) from `Customer List Customer Bridge` where `customer List Key`=".$data['Customer List key']);
$sql_no_of_customer=mysql_fetch_array(mysql_query("select count(`Customer Key`) from `Customer List Customer Bridge` where `customer List Key`=".$data['Customer List key']));
//$sql_no_of_customer1=mysql_fetch_array(mysql_query($sql_no_of_customer));
//print $sql_no_of_customer;

$customer_list_key=" <a href='new_campaign.php?customer_list_key=".$data['Customer List key']."'>"."Create".'</a>';

if($data['Customer List Type']=='Static')
$cusomer_list_name=" <a href='customer_list_static.php?id=".$data['Customer List key']."'>".$data['Customer List Name'].'</a>';
else
$cusomer_list_name=" <a href='customer_list_dynamic.php?id=".$data['Customer List key']."'>".$data['Customer List Name'].'</a>';
        $adata[]=array(
		   //  'no_of_customer'=>$sql_no_of_customer[0],
                 /*    'id'=>$id,
                     'name'=>$name,
                   'location'=>$data['Customer Main Location'],
                    'orders'=>number($data['Customer Orders']),
                    'invoices'=>$data['Customer Orders Invoiced'],
                     'email'=>$data['Customer Main XHTML Email'],
                     'telephone'=>$data['Customer Main XHTML Telephone'],
                     'last_order'=>$last_order_date,
                     'contact_since'=>$contact_since,

                     'total_payments'=>money($data['Customer Net Payments'],$currency),
                     'net_balance'=>money($data['Customer Net Balance'],$currency),
                     'total_refunds'=>money($data['Customer Net Refunds'],$currency),
                     'total_profit'=>money($data['Customer Profit'],$currency),
                    'balance'=>money($data['Customer Outstanding Net Balance'],$currency),


                     'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
                     'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
                    'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
                    'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
                    'contact_name'=>$data['Customer Main Contact Name'],
                    'address'=>$data['Customer Main XHTML Address'],
                    'billing_address'=>$billing_address,
                    'delivery_address'=>$delivery_address,*/
                    
		     'customer_list_type'=>$data['Customer List Type'],
                     'customer_list_name'=>$cusomer_list_name,
                     'customer_list_key'=>$customer_list_key,
                     'customer_list_creation_date'=>$data['Customer List Creation Date'],
                     
                     //'town'=>$data['Customer Main Town'],
                     //'postcode'=>$data['Customer Main Postal Code'],
                     //'region'=>$data['Customer Main Country First Division'],
                     //'country'=>$data['Customer Main Country'],
                     //'ship_address'=>$data['customer main ship to header'],
                     //'ship_town'=>$data['Customer Main Delivery Address Town'],
                     //'ship_postcode'>$data['Customer Main Delivery Address Postal Code'],
                     //'ship_region'=>$data['Customer Main Delivery Address Region'],
                     //'ship_country'=>$data['Customer Main Delivery Address Country'],
                    ////// 'activity'=>$data['Customer Type by Activity']

                 );

   }
    mysql_free_result($result);


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}



?>
