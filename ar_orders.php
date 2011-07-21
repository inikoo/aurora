<?php
/*
 File: ar_orders.php 

 Ajax Server Anchor for the Order Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyrigh (c) 2009, Inikoo 
 
 Version 2.0
*/
require_once 'common.php';
require_once 'class.Order.php';
require_once 'class.Invoice.php';

require_once 'ar_common.php';




if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('orders_lists'):
$data=prepare_values($_REQUEST,array(
                            'store'=>array('type'=>'key'),
                                    
                                        'block_view'=>array('type'=>'enum',
                                                'valid values regex'=>'/orders|invoices|dn/i'
                                               )
                                     
                                     ));
orders_lists($data);
break;

case('transactions_dipatched'):
transactions_dipatched();
break;
case('post_transactions_dipatched'):
post_transactions_dipatched();
break;
case('post_transactions'):
post_transactions();
break;
case('shortcut_key_search'):
list_shortcut_key_search();
break;
case('transactions_in_dn'):
list_transactions_in_dn();
break;
case('transactions_in_process_in_dn'):
list_transactions_in_process_in_dn();
break;
case('transactions_to_pick'):
list_transactions_to_pick();
break;
case('transactions_in_warehouse'):
list_transactions_in_warehouse();
break;
 case('create_po'):
   $po=new Order('po',array('supplier_id'=>$_SESSION['state']['supplier']['id']));
   if(is_numeric($po->id)){
     $response= array('state'=>200,'id'=>$po->id);

   }else
     $response= array('state'=>400,'id'=>_("Error: Purchase order could 't be created"));
     echo json_encode($response);  
   break;
 case('plot_month_outofstock_money'):
 case('plot_month_outofstock'):

   if(isset($_REQUEST['from']))
     $from=$_REQUEST['from'];
   else
     $from=date("Y-m-d",strtotime('-1 year') );
   if(isset($_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=date("Y-m-d",strtotime('now') );
   $_from=$from;
   $_to=$to;

   $int=prepare_mysql_dates($_from,$_to,'date_index','date only,complete months');
   // make the structure of the months
   $data=date_base($_from,$_to,'m','complete months');
   if($tipo=='plot_month_outofstock'){

    $sql=sprintf("select count(DISTINCT  product_id) as products_total ,sum(dispatched) as dispatched, substring(date_index, 1,7) AS dd from transaction left join orden on (order_id=orden.id) where partner=0  %s group by dd;",$int[0]);

    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $data_all[$row['dd']]=array('d_products'=>$row['products_total'],'picks'=>$row['dispatched']);
      
    }
   }
    $sql=sprintf("select count(DISTINCT  product_id) as products,sum(qty) as qty, substring(date_index, 1,7) AS dd,sum(qty*price) as e_cost from outofstock left join orden on (order_id=orden.id) left join product on (product_id=product.id) where  partner=0  %s  group by dd   ",$int[0]);
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $data_outstock[$row['dd']]=array('d_products'=>$row['products'],'picks'=>$row['qty'],'e_cost'=>$row['e_cost']);
    }
    
    foreach($data as $key=>$value){
      $total_products=0;
      $outstock_products=0;
      $total_picks=0;
      $outstock_picks=0;
      $e_cost=0;
      if(isset($data_all[$key])){
	 $total_products=$data_all[$key]['d_products'];
	 $total_picks=$data_all[$key]['picks'];
      }
      if(isset($data_outstock[$key])){
	$outstock_products=$data_outstock[$key]['d_products'];
	$outstock_picks=$data_outstock[$key]['picks'];
	$e_cost=$data_outstock[$key]['e_cost'];
      }

      $per_prods=percentage($outstock_products,$total_products,2,'0','' );
      $per_picks=percentage($outstock_picks,$total_picks,2,'0','' );

      $_data[]=array(
		     'per_product_outstock'=>(float) $per_prods,
			'per_picks_outstock'=>(float) $per_picks,
			'e_cost'=>money($e_cost),
			'date'=>$key,
			'tip_per_product_outstock'=>_('Out of Stock Products')."\n".$per_prods.'% ('.number($outstock_products).' '._('of').' '.number($total_products).')',
			'tip_per_picks_outstock'=>_('Out of Stock Picks')."\n".$per_picks."%\n(".number($outstock_picks).' '._('of').' '.number($total_picks).")\n"._('Estimated Value')."\n@"._('Current Sale Price')."\n".money($e_cost)

		     );
    }
$response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$_data,
			 )
		   );

   echo json_encode($response);
   
   
   break;





case('changesalesplot'):
   if(isset($_REQUEST['value'])){
     $value=$_REQUEST['value'];
     $_SESSION['views']['sales_plot']="$value";

   }
 case('proinvoice'):
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['proinvoice_list'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['proinvoice_list'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['proinvoice_list'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['proinvoice_list'][1];
   
   
   


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   


   

    if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$_SESSION['tables']['proinvoice_list'][4];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$_SESSION['tables']['proinvoice_list'][5];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$_SESSION['tables']['proinvoice_list'][6];



  $_SESSION['tables']['proinvoice_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);

  $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   

   
   $sql="select count(*) as total from orden   $where $wheref ";
   // print "$sql";
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
  }else{
    
      $sql="select count(*) as total from orden  $where";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$filtered=$row['total']-$total;
      }
      
  }
  
  

  $sql="select UNIX_TIMESTAMP(date_index) as date_index ,public_id,customer_name,id,customer_id,total,titulo,tipo,TO_DAYS(NOW())-TO_DAYS(date_index) as desde from orden  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  //  print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'customer_name'=>$row['customer_name'],
		   'customer_id'=>$row['customer_id'],
		   //		   'date_index'=>$row['date_index'],
		   'date_index'=> strftime("%A %e %B %Y", strtotime('@'.$row['date_index'])),
		   'total'=>money($row['total']),
		   'titulo'=>$_order_tipo[$row['tipo']],
		   'tipo'=>$row['tipo'],
		   'desde'=>$row['desde']
		   //		   'file'=>$row['original_file']
		   );
   }

   
   if($total==0){
     $rtext=_('No orders are outstanding').'.';
   }else if($total<$number_results){
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   }else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
 case('report_orders'):
   $_REQUEST['saveto']='report_sales';
 case('orders'):
   if(!$user->can_view('orders'))
     exit();
list_orders();

 
   break;


case('report_invoices'):
  $_REQUEST['saveto']='report_sales';

 case('invoices'):
   if(!$user->can_view('orders'))
  exit();
    
    list_invoices();
   
   break;
 
 case('dn'):
if(!$user->can_view('orders'))
  exit();
    
    list_delivery_notes();
    
 
   break;
 case('po_supplier'):
   
   if(!$user->can_view('purchase orders'))
     exit();
     
     list_purchase_orders_of_supplier();
     

   break;


 

 case('outofstock'):
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['order_id'];

   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['transaction_list'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['transaction_list'][1];
   
   
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['tables']['transaction_list']=array($order,$order_direction);
   
   $where=' where order_id='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   
   $sql="select * from outofstock left join product on (product.id=product_id)  $where order by code ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   //      print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     



     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>$row['product_id']
		   ,'code'=>$row['code']
		   ,'description'=>number($row['units']).'x '.$row['description']
		   //'ordered'=>$row['ordered'],
		   ,'qty'=>number($row['qty'],2)


		   );
   }


    

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data

			 )
		   );
   echo json_encode($response);
   break;
   case('transactions_cancelled'):
   transactions_cancelled();
   break;
 case('transactions_to_process'):
   
   if(isset( $_REQUEST['show_all']) and preg_match('/^(yes|no)$/',$_REQUEST['show_all'])  ){
     
     if($_REQUEST['show_all']=='yes')
       $show_all=true;
     else
       $show_all=false;
     $_SESSION['state']['order']['show_all']=$show_all;
   }else
     $show_all=$_SESSION['state']['order']['show_all'];
   
   if($show_all)
     products_to_sell();
   else
     transactions_to_process();
  
   break;
 case('transactions_invoice'):
   list_transactions_in_invoice();
   break;
   case('transactions_refund'):
   list_transactions_in_refund();
   break;
 case('transactions'):
   list_transactions();
   break;
 case('withproduct'):
      $can_see_customers=$user->can_view('customers');
 list_orders_with_product( $can_see_customers);

   break;
 case('withcustomerproduct'):
   
     list_customers_who_order_product();
   break;
 case('withcustomer'):
 list_orders_with_customer();
 break;
 default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }


function list_orders(){


    global $myconf,$user;

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

    if (isset( $_REQUEST['dispatch']))
        $type=$_REQUEST['dispatch'];
    else
        $type=$_SESSION['state']['orders']['type'];


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

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=0;
	
	if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=0;
	
    if (isset( $_REQUEST['store_id'])    ) {
        $store=$_REQUEST['store_id'];
        $_SESSION['state']['orders']['store']=$store;
    } else
        $store=$_SESSION['state']['orders']['store'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


    $_SESSION['state']['customers']['table']['order']=$order;
    $_SESSION['state']['customers']['table']['order_dir']=$order_direction;
    $_SESSION['state']['customers']['table']['nr']=$number_results;
    $_SESSION['state']['customers']['table']['sf']=$start_from;
    $_SESSION['state']['customers']['table']['where']=$awhere;
    $_SESSION['state']['customers']['table']['f_field']=$f_field;
    $_SESSION['state']['customers']['table']['f_value']=$f_value;

    $where='where true';
    $table='`Order Dimension` O ';
    $where_type='';

    if ($awhere) {



        $tmp=preg_replace('/\\\"/','"',$awhere);
        $tmp=preg_replace('/\\\\\"/','"',$tmp);
        $tmp=preg_replace('/\'/',"\'",$tmp);

        $raw_data=json_decode($tmp, true);
        $raw_data['store_key']=$store;
//print_r( $raw_data);exit;
        list($where,$table)=orders_awhere($raw_data);


    }
    elseif ($type=='all_orders') {
        $where_type='';
        $_SESSION['state']['orders']['type']=$type;

    }
    elseif ($type=='in_process') {
        $where_type=sprintf(' and `Order Current Dispatch State`="In Process" ');
        $_SESSION['state']['orders']['type']=$type;

    }
    elseif($type=='dispatched') {
        $where_type=sprintf(' and `Order Current Dispatch State`="Dispatched" ');
        $_SESSION['state']['orders']['type']=$type;
    }
	elseif($type=='unknown') {
        $where_type=sprintf(' and `Order Current Dispatch State`="Unknown" ');
        $_SESSION['state']['orders']['type']=$type;
    }
	elseif($type=='cancelled') {
        $where_type=sprintf(' and `Order Current Dispatch State`="Cancelled" ');
        $_SESSION['state']['orders']['type']=$type;
    }
	elseif($type=='suspended') {
        $where_type=sprintf(' and `Order Current Dispatch State`="Suspended" ');
        $_SESSION['state']['orders']['type']=$type;
    }
    elseif($type=='list') {
        $sql=sprintf("select * from `List Dimension` where `List Key`=%d",$_REQUEST['list_key']);

        $res=mysql_query($sql);
        if ($customer_list_data=mysql_fetch_assoc($res)) {
            $awhere=false;
            if ($customer_list_data['List Type']=='Static') {
                $table='`List Order Bridge` OB left join `Order Dimension` O  on (OB.`Order Key`=O.`Order Key`)';
                $where_type=sprintf(' and `List Key`=%d ',$_REQUEST['list_key']);

            } else {// Dynamic by DEFAULT



                $tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
                $tmp=preg_replace('/\\\\\"/','"',$tmp);
                $tmp=preg_replace('/\'/',"\'",$tmp);

                $raw_data=json_decode($tmp, true);

                $raw_data['store_key']=$store;
                list($where,$table)=orders_awhere($raw_data);




            }

        } else {
            exit("error");
        }
    }
    else {
        $where_type='';
    }


	$where_interval='';
	if($from & $to){
	    $where_interval=prepare_mysql_dates($from,$to,'`Order Last Updated Date`','only_dates');
		$where_interval=$where_interval['mysql'];
	}



//print "yyyy $where_type";

//exit;


    $filter_msg='';
    $wheref='';

    $currency='';

     $where_stores=sprintf(' and  false');

    if (is_numeric($store) and in_array($store,$user->stores)) {
        $where_stores=sprintf(' and  `Order Store Key`=%d ',$store);
        $store=new Store($store);
        $currency=$store->data['Store Currency Code'];
    } else {
     
        $currency='';
	}


    if(isset( $_REQUEST['all_stores']) and  $_REQUEST['all_stores']  ){
    	$where_stores=sprintf('and `Order Store Key` in (%s)  ',join(',',$user->stores));      			       
    }

    $where.=$where_stores;
//print $where; exit;
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



    $sql="select count(Distinct O.`Order Key`) as total from $table   $where $wheref $where_type $where_interval";

     //    print $sql;exit;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(Distinct O.`Order Key`) as total_without_filters from $table  $where  $where_type $where_interval";
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


    $rtext=$total_records." ".ngettext('order','orders',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_("Showing all orders");



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
		$order='O.`Order Key`';
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
        $order='`Order File As`';
    $sql="select   * from  $table   $where $wheref  $where_type $where_interval group by O.`Order Key` order by $order $order_direction limit $start_from,$number_results";
	//    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  $where_type group by O.`Order Key` order by $order $order_direction limit $start_from,$number_results";

    //print $sql;exit;
    $adata=array();



    $result=mysql_query($sql);
    // print $sql;
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


        $id="<a href='order.php?p=cs&id=".$data['Order Key']."'>".$myconf['order_id_prefix'].sprintf("%05d",$data['Order Key']).'</a>';
		/*
        if ($data['Customer Type']=='Person') {
            $name='<img src="art/icons/user.png" alt="('._('Person').')">';
        } else {
            $name='<img src="art/icons/building.png" alt="('._('Company').')">';

        }
		*/
        $name=" <a href='customer.php?p=cs&id=".$data['Order Key']."'>".($data['Order Customer Name']==''?'<i>'._('Unknown name').'</i>':$data['Order Customer Name']).'</a>';


/*
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

        switch ($data['Customer Type by Activity']) {
        case 'Inactive':
            $activity=_('Lost');
            break;
        case 'Active':
            $activity=_('Active');
            break;
        case 'Prospect':
            $activity=_('Prospect');
            break;
        default:
            $activity=$data['Customer Type by Activity'];
            break;
        }
*/
        $adata[]=array(
                     'id'=>$id,
                     'last_date'=>date('Y-m-d', strtotime($data['Order Last Updated Date'])),
                     'customer'=>$data['Order Customer Name'],
                     'state'=>$data['Order Current Dispatch State'],
                     'total_amount'=>$currency.$data['Order Total Amount'],/*
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

                     'activity'=>$activity
*/
                 );
///if(isset($_REQUEST['textValue'])&isset($_REQUEST['typeValue']))
///{
///	$list_name=$_REQUEST['textValue'];
///	$list_type=$_REQUEST['typeValue'];
///}
///$dataid[]=array('id'=>$id,'list_name'=>$list_name,'list_type'=>$list_type);//
    }
    mysql_free_result($result);

///print_r($dataid);//


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

function list_transactions_in_invoice() {

    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
        $order_id=$_REQUEST['id'];
    else
        $order_id=$_SESSION['state']['invoice']['id'];




    $where=' where `Invoice Quantity`!=0 and  `Invoice Key`='.$order_id;

    $total_charged=0;
    $total_discounts=0;
    $total_picks=0;

    $data=array();
    $sql="select * from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where order by O.`Product Code`  ";

    //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
    //   print $sql;
    $result=mysql_query($sql);
    $total_gross=0;
    $total_discount=0;
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
        $total_discount+=$row['Invoice Transaction Total Discount Amount'];
        $total_gross+=$row['Invoice Transaction Gross Amount'];
        $code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);

        if ($row['Invoice Transaction Total Discount Amount']==0)
            $discount='';
        else
            $discount=money($row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']);

        $data[]=array(

                    'code'=>$code,
                           'description'=>$row['Product XHTML Short Description'],
                                          'tariff_code'=>$row['Product Tariff Code'],
                                                         'quantity'=>number($row['Invoice Quantity']),
                                                                     'gross'=>money($row['Invoice Transaction Gross Amount'],$row['Invoice Currency Code']),
                                                                              'discount'=>$discount,
                                                                                          'to_charge'=>money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code'])
                );
    }

    $invoice=new Invoice($order_id);

    if ($invoice->data['Invoice Shipping Net Amount']!=0) {

        $data[]=array(

                    'code'=>''
                           ,'description'=>_('Shipping')
                                          ,'tariff_code'=>''
                                                         ,'quantity'=>''
                                                                     ,'gross'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                                                                              ,'discount'=>''
                                                                                          ,'to_charge'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                );

    }
    if ($invoice->data['Invoice Charges Net Amount']!=0) {
        $data[]=array(

                    'code'=>''
                           ,'description'=>_('Charges')
                                          ,'tariff_code'=>''
                                                         ,'quantity'=>''
                                                                     ,'gross'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                                                                              ,'discount'=>''
                                                                                          ,'to_charge'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                );
    }
    if ($invoice->data['Invoice Total Tax Amount']!=0) {
        $data[]=array(

                    'code'=>''
                           ,'description'=>_('Tax')
                                          ,'tariff_code'=>''
                                                         ,'quantity'=>''
                                                                     ,'gross'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency'])
                                                                              ,'discount'=>''
                                                                                          ,'to_charge'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency'])
                );
    }

    $data[]=array(

                'code'=>''
                       ,'description'=>_('Total')
                                      ,'tariff_code'=>''
                                                     ,'quantity'=>''
                                                                 ,'gross'=>''
                                                                          ,'discount'=>''
                                                                                      ,'to_charge'=>'<b>'.money($invoice->data['Invoice Total Amount'],$invoice->data['Invoice Currency']).'</b>'
            );

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}
function list_transactions_in_refund() {

    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
        $order_id=$_REQUEST['id'];
    } else {
        $order_id=$_SESSION['state']['invoice']['id'];
    }
    $where=' where   `Refund Key`='.$order_id;
    $total_charged=0;
    $total_discounts=0;
    $total_picks=0;

    $data=array();
    $sql="select `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where   ";
    $result=mysql_query($sql);
   
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
        $data[]=array(
                    'code'=>$code,
                    'description'=>$row['Product XHTML Short Description'],
                    'charged'=>$row['Invoice Quantity'].'/'.money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']).'('.money($row['Invoice Transaction Item Tax Amount'],$row['Invoice Currency Code']).')',
                    'refund_net'=>money($row['Invoice Transaction Net Refund Amount'],$row['Invoice Currency Code']),
                    'refund_tax'=>money($row['Invoice Transaction Tax Refund Amount'],$row['Invoice Currency Code'])
                );
    }
    $sql="select * from `Order No Product Transaction Fact`    $where   ";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[]=array(
                    'code'=>'',
                    'description'=>$row['Transaction Description'],
                    'refund_net'=>money($row['Transaction Invoice Net Amount'],$row['Currency Code']),
                    'refund_tax'=>money($row['Transaction Invoice Tax Amount'],$row['Currency Code'])

                );
    }


    $invoice=new Invoice($order_id);

    if ($invoice->data['Invoice Shipping Net Amount']!=0) {

        $data[]=array(
                    'code'=>'',
                    'description'=>_('Shipping'),
                    'refund_net'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                );

    }
    if ($invoice->data['Invoice Charges Net Amount']!=0) {
        $data[]=array(
                    'code'=>'',
                    'gross'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency']),
                    'refund_net'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                );
    }

    $data[]=array(
                'code'=>'',
                'description'=>_('Total'),
                'refund_net'=>'<b>'.money($invoice->data['Invoice Total Net Amount'],$invoice->data['Invoice Currency']).'</b>',
                'refund_tax'=>'<b>'.money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency']).'</b>'

           );

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data
                                     )
                   );
    echo json_encode($response);
}

function list_transactions_in_dn(){
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['dn']['id'];
   



   $where=sprintf(' where   `Delivery Note Key`=%d',$order_id);

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select `Delivery Note Quantity`,`Product Tariff Code`,O.`Product Code`, PH.`Product ID` ,`Product XHTML Short Description` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
  
   $result=mysql_query($sql);
   $total_gross=0;
   $total_discount=0;
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 
    
    $data[]=array(

		   'code'=>sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code'])
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Delivery Note Quantity'])
		   
		   );
   }



   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_transactions_in_process_in_dn(){
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['dn']['id'];
   



   $where=sprintf(' where   `Delivery Note Key`=%d',$order_id);

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();

   $sql=sprintf("select `Required`,`Part XHTML Description`,`Part XHTML Currently Used In`,ITF.`Part SKU` from `Inventory Transaction Fact` as ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`)$where"); 
   
   $result=mysql_query($sql);
   $total_gross=0;
   $total_discount=0;
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 
    
    $data[]=array(

		   'part'=>sprintf('<a href="part.php?sku=%d">SKU%05d</a>',$row['Part SKU'],$row['Part SKU'])
		   ,'description'=>$row['Part XHTML Description']
		   ,'used_in'=>$row['Part XHTML Currently Used In']
		   ,'quantity'=>number($row['Required'])
		   
		   );
   }



   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function list_customers_who_order_product(){
 $conf=$_SESSION['state']['product']['customers'];

   if(isset( $_REQUEST['code'])){
     $tag=$_REQUEST['code'];
     $mode='code';
   }else if(isset( $_REQUEST['id'])){
     $tag=$_REQUEST['id'];
     $mode='id';
   }else if(isset( $_REQUEST['key'])){
     $tag=$_REQUEST['key'];
     $mode='key';
   }else{
     $tag=$_SESSION['state']['product']['tag'];
     $mode=$_SESSION['state']['product']['mode'];
   }

   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['product']['custumers']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

   $table='`Order Transaction Fact` OTF left join `Product History Dimension`  PD on (PD.`Product Key`=OTF.`Product Key`)  ';

   if($mode=='code'){
     $where=$where.sprintf(" and P.`Product Code`=%s ",prepare_mysql($tag));
     $table='`Order Transaction Fact` OTF left join `Product History Dimension` PD  on (PD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)  ';

   }elseif($mode=='pid')
     $where=$where.sprintf(" and PD.`Product ID`=%d ",$tag);
   elseif($mode=='key')
     $where=$where.sprintf(" and PD.`Product Key`=%d ",$tag);


   $wheref="";
   
  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif($f_field=='customer_name'  and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   $sql="select count(distinct `Customer Key`) as total from  $table  $where $wheref";
   // print $mode.' '.$sql;
   $res = mysql_query($sql);
   if($row=mysql_fetch_array($res)) {
       $total=$row['total'];
   }
    mysql_free_result($res);
   if($wheref==''){
     $filtered=0;
      $total_records=$total;
   }else{
     $sql="select count(distinct `Customer Key`) as total from  $table  $where      ";

     $res = mysql_query($sql);
     if($row=mysql_fetch_array($res)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
     }
      mysql_free_result($res);
   }
   $rtext=$total_records." ".ngettext('customer','customers',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   

   $filter_msg='';
   if($total==0 and $filtered>0){
     switch($f_field){
     case('public_id'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order starting with")." <b>$f_value</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('public_id'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only orders starting with')." <b>$f_value</b>";
       break;
     }
   }

   
   $_order=$order;
   $_dir=$order_direction;

  if($order=='dispatched')
         $order='dispatched';
 elseif($order=='orders')
         $order='orders';
          elseif($order=='charged')
         $order='charged';
          elseif($order=='to_dispatch')
         $order='to_dispatch';
          elseif($order=='dispatched')
         $order='dispatched';
          elseif($order=='nodispatched')
         $order='nodispatched';
  else
     $order='`Customer Name`';


   $sql=sprintf("select   CD.`Customer Key` as customer_id,`Customer Name`,`Customer Main Location`,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`) as charged ,count(distinct `Order Key`) as orders ,sum(`Shipped Quantity`) as dispatched,sum(`Current Manufacturing Quantity`+`Current On Shelf Quantity`+`Current On Box Quantity`) as to_dispatch,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`) as nodispatched from     `Order Transaction Fact` OTF left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)  left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)       left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)     $where $wheref  group by CD.`Customer Key`    order by $order $order_direction  limit $start_from,$number_results "
		);

   $data=array();
   
  $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


     $data[]=array(
		   'customer'=>sprintf('<a href="customer.php?id=%d"><b>%s</b></a>, %s',$row['customer_id'],$row['Customer Name'],$row['Customer Main Location']),
		   'charged'=>money($row['charged']),
		   'orders'=>number($row['orders']),
		   'to_dispatch'=>number($row['to_dispatch']),
		   'dispatched'=>number($row['dispatched']),
		   'nodispatched'=>number($row['nodispatched'])

		   );
   }
   mysql_free_result($res);

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'rtext'=>$rtext,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_orders_with_customer(){

 
 if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['order_withcust'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['order_withcust'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['order_withcust'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['order_withcust'][1];
   

   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $customer_id=$_REQUEST['id'];
   else
     $customer_id=$_SESSION['tables']['order_withcust'][4];


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['tables']['order_withcust']=array($order,$order_direction,$number_results,$start_from,$customer_id);

   $where=sprintf(" where customer_id=%d ",$customer_id);
   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  



   
   $sql="select count(*) as total from orden    $where $wheref";
   
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   mysql_free_result($result);
   if($wheref==''){
     $filtered=0;
   }else{
     $sql="select count(*) as total from orden $where      ";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $filtered=$row['total']-$total;
     }
      mysql_free_result($result);
   }
   

   $sql=sprintf("select tipo,id,public_id,total ,UNIX_TIMESTAMP(date_index) as date_index from orden  $where $wheref     order by $order $order_direction  limit $start_from,$number_results "
		);

   //print "$sql\n";
      $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'date_index'=>$row['date_index'],
		   'date'=> strftime("%A %e %B %Y %H:%I", strtotime('@'.$row['date_index'])),
		   'total'=>money($row['total']),
		   // 'undispatched'=>number($row['undispatched']),
		   'tipo'=>$_order_tipo[$row['tipo']]
		   );
   }
   mysql_free_result($result);
   if($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_orders_with_product($can_see_customers=false){

   $conf=$_SESSION['state']['product']['orders'];
if(isset( $_REQUEST['code'])){
     $tag=$_REQUEST['code'];
     $mode='code';
   }else if(isset( $_REQUEST['id'])){
     $tag=$_REQUEST['id'];
     $mode='id';
   }else if(isset( $_REQUEST['key'])){
     $tag=$_REQUEST['key'];
     $mode='key';
   }else{
     $tag=$_SESSION['state']['product']['tag'];
     $mode=$_SESSION['state']['product']['mode'];
   }



if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];

if(!is_numeric($start_from))
  $start_from=0;

   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;



   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['state']['product']['orders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

   
 

  if($mode=='code')
     $where=$where.sprintf(" and `Product Code`=%s ",prepare_mysql($tag));
   elseif($mode=='pid')
     $where=$where.sprintf(" and `Product ID`=%d ",$tag);
   elseif($mode=='key')
     $where=$where.sprintf(" and `Product Key`=%d ",$tag);



   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  


   $sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF    $where $wheref";
   //print $sql;   
$res = mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
  $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;  $total_records=$total;
   }else{
     $sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF  $where      ";
       $res = mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
       }
       
   }

 $rtext=$total_records." ".ngettext('order','orders',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $filter_msg='';


if($order=='dispatched')
      $order='`Shipped Quantity`';
   elseif($order=='order'){
     $order='`Order Public ID`';
     
  }elseif($order=='customer_name'){
     $order='`Customer Name`';

  }elseif($order=='dispatched'){
     $order='dispatched';

  } elseif($order=='undispatched'){
     $order='undispatched';

  
  }else{
   $order='OTF.`Order Date`';
  
  }
  
  
   $sql=sprintf("select OTF.`Order Key`,OTF.`Order Public ID`,`Customer Name`,CD.`Customer Key`,OTF.`Order Date`,sum(`Shipped Quantity`) as dispatched,
   sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) as undispatched  from 
   `Order Transaction Fact` OTF left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)     %s %s   group by OTF.`Order Key`  order by  $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
		);
   // print $sql;

   $res=mysql_query($sql);
   $data=array();

   while($row= mysql_fetch_array($res, MYSQL_ASSOC) ) {
     if($can_see_customers)
       $customer='<a href="customer.php?id='.$row['Customer Key'].'">'.$row['Customer Name'].'</a>';
     else
       $customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
     


     $data[]=array(
		   'order'=>sprintf("<a href='order.php?id=%d'>%s</a>",$row['Order Key'],$row['Order Public ID']),
		   'customer_name'=>$customer,
		   'date'=> strftime("%e %b %y", strtotime($row['Order Date'])),
		   'dispatched'=>number($row['dispatched']),
		   'undispatched'=>number($row['undispatched'])

		   );
   }

   $response=array('resultset'=>
		   array('state'=>200,
			 
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_delivery_notes(){
   
    $conf=$_SESSION['state']['orders']['dn'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
     
     
     if(isset( $_REQUEST['dn_state_type']))
     $state=$_REQUEST['dn_state_type'];
   else
     $state=$conf['dn_state_type'];
     
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
     $from=$conf['from'];
   else
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $to=$conf['to'];
    else
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
     if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
       $view=$conf['view'];
     else
       $view=$_SESSION['state']['orders']['view'];

   }
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;




   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

        
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales'){
     
     $_SESSION['state']['report']['sales']['order']=$order;
     $_SESSION['state']['report']['sales']['order_dir']=$order_direction;
     $_SESSION['state']['report']['sales']['nr']=$number_results;
     $_SESSION['state']['report']['sales']['sf']=$start_from;
     $_SESSION['state']['report']['sales']['where']=$where;
     $_SESSION['state']['report']['sales']['f_field']=$f_field;
     $_SESSION['state']['report']['sales']['f_value']=$f_value;
     $_SESSION['state']['report']['sales']['to']=$to;
     $_SESSION['state']['report']['sales']['from']=$from;
     $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
     
   }else{
     if(isset( $_REQUEST['store_id'])    ){
     $store=$_REQUEST['store_id'];
     $_SESSION['state']['orders']['store']=$store;
   }else
     $store=$_SESSION['state']['orders']['store'];


    // $_SESSION['state']['orders']['dn']=array('dn_state_type'=>$state,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

 $_SESSION['state']['orders']['dn']['dn_state_type']=$state;
    $_SESSION['state']['orders']['dn']['order']=$order;
    $_SESSION['state']['orders']['dn']['order_dir']=$order_direction;
    $_SESSION['state']['orders']['dn']['nr']=$number_results;
    $_SESSION['state']['orders']['dn']['sf']=$start_from;
    $_SESSION['state']['orders']['dn']['where']=$where;
    $_SESSION['state']['orders']['dn']['f_field']=$f_field;
    $_SESSION['state']['orders']['dn']['f_value']=$f_value;


     $_SESSION['state']['orders']['view']=$view;
     $date_interval=prepare_mysql_dates($from,$to,'`Delivery Note Date`','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
   }
 if(is_numeric($store)){
     $where.=sprintf(' and `Delivery Note Store Key`=%d ',$store);
   }

   $where.=$date_interval['mysql'];
   
 
switch ($state) {
case 'shortages':
    $where.=' and `Delivery Note Type` in ("Shortages","Replacement & Shortages")';
    break;
case 'replacements':
    $where.=' and `Delivery Note Type` in ("Replacement","Replacement & Shortages")';
    break;
case 'donations':
    $where.=' and `Delivery Note Type`="Donation"';
    break;
case 'samples':
    $where.=' and `Delivery Note Type`="Sample"';
    break;
case 'orders':
    $where.=' and `Delivery Note Type`="Order"';
    break;
case 'returned':
    $where.=' and `Delivery Note State`="Cancelled to Restock"';
    break;
case 'send':
    $where.=' and `Delivery Note State`="Dispatched"';
    break;
case 'ready':
    $where.=' and `Delivery Note State` in ("Packed","Approved")';
    break;
case 'packing':
    $where.=" and `Delivery Note State` in ('Picking & Packing','Packer Assigned','Picked','Packing')";
    break;
case 'picking':
    $where.=' and `Delivery Note State` in ("Picking & Packing","Picking")';
    break;

case 'ready_to_pick':
    $where.=' and `Delivery Note State` in ("Ready to be Picked","Picker Assigned")';
    break;
default:

    break;
}
   
   
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif($f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Delivery Note ID` like '".addslashes($f_value)."%'";
  elseif($f_field=='invoice' and $f_value!='')
    $wheref.=" and  `Delivery Note Invoices` like '".addslashes($f_value)."%'";
 elseif($f_field=='order' and $f_value!='')
    $wheref.=" and  `Delivery Note Order` like '".addslashes($f_value)."%'";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";
   


   

   
  $sql="select count(*) as total from `Delivery Note Dimension`   $where $wheref ";
  // print $sql ;
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
  }
  mysql_free_result($result);
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Delivery Note Dimension`  $where";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      mysql_free_result($result);
  }
  $rtext=$total_records." ".ngettext('delivery note','delivery notes',$total_records);
  if($total_records>$number_results)
    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
    $rtext_rpp=_("Showing all delivery notes");

  $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes starting with')." <b>$f_value</b>)";
       break;
     case('customer_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with customer')." <b>".$f_value."*</b>)";
       break;  
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with min value of')." <b>".money($f_value)."*</b>)";
       break;  
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with max value of')." <b>".money($f_value)."*</b>)";
       break;  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days DN').")";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;

   
   if($order=='date' or $order=='')
     $order='`Delivery Note Date`';
   else if($order=='id')
     $order='`Delivery Note File As`';
   else if($order=='customer')
     $order='`Delivery Note Customer Name`';
   else if($order=='type')
     $order='`Delivery Note Type`';
 else if($order=='weight')
     $order='`Delivery Note Weight`';
 else if($order=='parcels')
     $order='`Delivery Note Parcel Type`,`Delivery Note Number Parcels`';

   
  $sql="select *  from `Delivery Note Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  // print $sql;

   $data=array();

   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $order_id=sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Delivery Note Customer Key'],$row['Delivery Note Customer Name']);


     $type=$row['Delivery Note Type'];

     switch($row['Delivery Note Parcel Type']){
     case('Pallet'):
       $parcel_type='P';
       break;
     case('Envelope'):
       $parcel_type='e';
       break;
     default:     
       $parcel_type='b';
       
     }

     if($row['Delivery Note Number Parcels']==''){
       $parcels='?';
     }elseif($row['Delivery Note Parcel Type']=='Pallet' and $row['Delivery Note Number Boxes']){
       $parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$row['Delivery Note Number Boxes'].' b)';
     }else{
       $parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type;
     }
if($row['Delivery Note State']=='Dispatched')
$date=strftime("%e %b %y", strtotime($row['Delivery Note Date']));
else
$date=strftime("%e %b %y", strtotime($row['Delivery Note Date Created']));
     $data[]=array(
		   'id'=>$order_id
		   ,'customer'=>$customer
		   ,'date'=>$date
		   ,'type'=>$type.($row['Delivery Note XHTML Orders']?' ('.$row['Delivery Note XHTML Orders'].')':'')
		   ,'orders'=>$row['Delivery Note XHTML Orders']
		   ,'invoices'=>$row['Delivery Note XHTML Invoices']
		   ,'weight'=>number($row['Delivery Note Weight'],1,true).' Kg'
		   ,'parcels'=>$parcels


);
   }
   mysql_free_result($res);

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function list_invoices(){
 
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $conf=$_SESSION['state']['report']['sales'];
    else
      $conf=$_SESSION['state']['orders']['invoices'];
    if(isset( $_REQUEST['sf']))
      $start_from=$_REQUEST['sf'];
    else
      $start_from=$conf['sf'];
    if(isset( $_REQUEST['nr']))
      $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];

if(isset( $_REQUEST['where']))
  $where=stripslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
  
  if(isset( $_REQUEST['invoice_type']))
  $type=stripslashes($_REQUEST['invoice_type']);
   else
     $type=$conf['invoice_type'];
  
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
     $from=$conf['from'];
   else
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $to=$conf['to'];
    else
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
     if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
       $view=$conf['view'];
     else
       $view=$_SESSION['state']['orders']['view'];

   }
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
//print $where;

   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

        
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales'){
     
     $_SESSION['state']['report']['sales']['order']=$order;
     $_SESSION['state']['report']['sales']['order_dir']=$order_direction;
     $_SESSION['state']['report']['sales']['nr']=$number_results;
     $_SESSION['state']['report']['sales']['sf']=$start_from;
     $_SESSION['state']['report']['sales']['where']=$where;
     $_SESSION['state']['report']['sales']['f_field']=$f_field;
     $_SESSION['state']['report']['sales']['f_value']=$f_value;
     $_SESSION['state']['report']['sales']['to']=$to;
     $_SESSION['state']['report']['sales']['from']=$from;

if(isset($_REQUEST['store_key'])){
$store=$_REQUEST['store_key'];
$_SESSION['state']['report']['sales']['store']=$store;
}else
$store=$_SESSION['state']['report']['sales']['store'];

    $date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
    
   }else{
      if(isset( $_REQUEST['store_id'])    ){
     $store=$_REQUEST['store_id'];
     $_SESSION['state']['orders']['store']=$store;
   }else
     $store=$_SESSION['state']['orders']['store'];


   
     
       
     $_SESSION['state']['orders']['invoices']['order']=$order;
     $_SESSION['state']['orders']['invoices']['order_dir']=$order_direction;
     $_SESSION['state']['orders']['invoices']['nr']=$number_results;
     $_SESSION['state']['orders']['invoices']['sf']=$start_from;
     $_SESSION['state']['orders']['invoices']['where']=$where;
     $_SESSION['state']['orders']['invoices']['f_field']=$f_field;
     $_SESSION['state']['orders']['invoices']['f_value']=$f_value;
    
     $_SESSION['state']['orders']['invoices']['invoice_type']=$type;
     
     
     $_SESSION['state']['orders']['view']=$view;
     $date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
    
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
   }

   
 if(is_numeric($store)){
     $where.=sprintf(' and `Invoice Store Key`=%d ',$store);
   }


switch ($type) {
    case 'paid':
        $where.=' and `Invoice Paid`="Yes"';
        break;
    case 'to_pay':
        $where.=' and `Invoice Paid`!="Yes"';
        break;
    case 'invoices':
        $where.=' and `Invoice Title`="Invoice"';
        break;        
    case 'refunds':
        $where.=' and `Invoice Title`="Refund"';
        break;
    default:
 if(!isset($_REQUEST['saveto']) or $_REQUEST['saveto']!='report_sales'){   
    $_SESSION['state']['orders']['invoices']['invoice_type']='all';
    }
        break;
}


   $where.=$date_interval['mysql'];
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$f_value."    ";
   elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Invoice Customer Name` like   '".addslashes($f_value)."%'";
  elseif( $f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Invoice Public ID` like '".addslashes($f_value)."%'";
   
else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  `Invoice Total Amount`<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  `Invoice Total Amount`>=".$f_value."    ";
   


   

   
  $sql="select count(*) as total from `Invoice Dimension`   $where $wheref ";
  //  print $sql ;
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    $total=$row['total'];
  }
  mysql_free_result($res);
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Invoice Dimension`  $where";
       $res=mysql_query($sql);
  if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      mysql_free_result($res);
  }
  $rtext=$total_records." ".ngettext('invoice','invoices',$total_records);
  if($total_records>$number_results)
    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
    $rtext_rpp=_("Showing all invoices");

  $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
       break;
     case('customer_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
       break;  
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
       break;  
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
       break;  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;

   
   if($order=='date')
     $order='`Invoice Date`';
   else if($order=='last_date')
     $order='`Invoice Last Updated Date`';
   else if($order=='id')
     $order='`Invoice File As`';
   else if($order=='state')
     $order='`Invoice Current Dispatch State`,`Invoice Current Payment State`';
   else if($order=='total_amount')
     $order='`Invoice Total Amount`';
else if($order=='customer')
     $order='`Invoice Customer Name`';
 else if($order=='state')
   $order='`Invoice Has Been Paid In Full`';
else if($order=='net')
     $order='`Invoice Total Net Amount`';
  $sql="select `Invoice Currency`,`Invoice Total Net Amount`,`Invoice Has Been Paid In Full`,`Invoice Key`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice Public ID`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Date`,`Invoice Total Amount`  from `Invoice Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  // print $sql;

   $data=array();

   
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

     $order_id=sprintf('<a href="invoice.php?id=%d">%s</a>',$row['Invoice Key'],$row['Invoice Public ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Invoice Customer Key'],$row['Invoice Customer Name']);
     if($row['Invoice Has Been Paid In Full']=='Yes')
       $state=_('Paid');
     else
       $state=_('No Paid');

     $data[]=array(
		   'id'=>$order_id
		   ,'customer'=>$customer
		   ,'date'=>strftime("%e %b %y", strtotime($row['Invoice Date']))
		   ,'total_amount'=>money($row['Invoice Total Amount'],$row['Invoice Currency'])
		   ,'net'=>money($row['Invoice Total Net Amount'],$row['Invoice Currency'])

		   ,'state'=>$state
		   ,'orders'=>$row['Invoice XHTML Orders']
		   ,'dns'=>$row['Invoice XHTML Delivery Notes']
		   );
   }
mysql_free_result($res);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}






function transactions_to_process(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
   
   

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Order Quantity'])
		   ,'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code'])
		   ,'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   ,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function transactions_dipatched(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Transaction Type` not in ("Resend")  and  O.`Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   
   $order=' order by O.`Product Code`';
   
   $sql="select O.`Order Transaction Fact Key`,`Deal Info`,`Operation`,`Quantity`,`Order Currency Code`,`Order Quantity`,`Order Bonus Quantity`,`No Shipped Due Out of Stock`,P.`Product ID` ,P.`Product Code`,`Product XHTML Short Description`,`Shipped Quantity`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount 
   from `Order Transaction Fact` O left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`) 
   left join `Order Post Transaction Dimension` POT on (O.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`)
left join `Order Transaction Deal Bridge` DB on (DB.`Order Transaction Fact Key`=O.`Order Transaction Fact Key`)
 
   $where $order  ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   //print $sql;

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  
  $ordered='';
  if($row['Order Quantity']!=0)
    $ordered.=number($row['Order Quantity']);
 if($row['Order Bonus Quantity']>0){
    $ordered='<br/>'._('Bonus').' +'.number($row['Order Bonus Quantity']);
  }
   if($row['No Shipped Due Out of Stock']>0){
    $ordered.='<br/> '._('No Stk').' -'.number($row['No Shipped Due Out of Stock']);
  }
  $ordered=preg_replace('/^<br\/>/','',$ordered);
     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     
     $dispatched=number($row['Shipped Quantity']);
     
     if($row['Quantity']>0  and $row['Operation']=='Resend'){
     $dispatched.='<br/> '._('Resend').' +'.number($row['Quantity']);
     }
     
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description'].' <span style="color:red">'.$row['Deal Info'].'</span>'
		  
		   ,'ordered'=>$ordered
		   ,'dispatched'=>$dispatched
		   ,'invoiced'=>money($row['amount'],$row['Order Currency Code'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function post_transactions_dipatched(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Transaction Type`  in ("Replacement","Missing")  and  `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   
   $order=' order by `Product Code`';
   
   $sql="select `Order Transaction Type`,`Delivery Note Quantity`,`Delivery Note ID`,`Delivery Note Key`,P.`Product ID`,`Product Code`,`Product XHTML Short Description` from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where $order  ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
   
   //print $sql;

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  

switch ($row['Order Transaction Type']) {
    case 'Replacement':
       $notes=_('Replacement');
        break;
       case 'Missing':
       $notes=_('Missing');
        break;
        default:
        $notes='';
        
}


     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		    ,'dn'=>sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID'])
		  ,'dispatched'=>number($row['Delivery Note Quantity'])
		  ,'notes'=>$notes
		  );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function post_transactions(){

 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=sprintf(' where  (POT.`Order Key`=%d or  O.`Order Key`=%d )',$order_id,$order_id);

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   
   $order=' order by O.`Product Code`';
   
   $sql="select POT.`Quantity`,`State`,`Operation`,O.`Delivery Note Quantity`,O.`Delivery Note ID`,O.`Delivery Note Key`,P.`Product ID`,O.`Product Code`,`Product XHTML Short Description` from `Order Post Transaction Dimension` POT left  join `Order Transaction Fact` O on (O.`Order Transaction Fact Key`=POT.`Order Post Transaction Fact Key`) left  join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where $order  ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
  
//  print $sql;

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  

switch ($row['Operation']) {
    case 'Resend':
       $notes=_('Resend');
        break;
       case 'Refund':
       $notes=_('Refund');
        break;
        default:
        $notes='';
        
}
switch ($row['State']) {
    case 'In Process':
       $notes.=sprintf(', <a href="new_post_order.php?id=%d">%s</a>',$order_id,_('In Process'));
        break;
       case 'In Warehouse':
       $notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('In Warehouse'),$row['Delivery Note Key'],$row['Delivery Note ID']);
        break;
         case 'Dispatched':
       $notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('Dispatched'),$row['Delivery Note Key'],$row['Delivery Note ID']);
        break;
        default:
        $notes.='';
        
}

if($row['State']!='In Process'){
$qty=$row['Delivery Note Quantity'];
}else{
$qty=number($row['Quantity']);
}

     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		    ,'dn'=>sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID'])
		  ,'dispatched'=>$qty
		  ,'notes'=>$notes
		  );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function transactions_cancelled(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
   
   

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Order Quantity'])
		   ,'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code'])
		   ,'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   ,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function list_transactions_in_warehouse(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
   
   

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Order Quantity'])
		   ,'gross'=>money($row['Order Transaction Gross Amount'])
		   ,'discount'=>money($row['Order Transaction Total Discount Amount'])
		   ,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}



function list_shortcut_key_search(){
 $conf=$_SESSION['state']['product']['orders'];
if(isset( $_REQUEST['code'])){
     $tag=$_REQUEST['code'];
     $mode='code';
   }else if(isset( $_REQUEST['id'])){
     $tag=$_REQUEST['id'];
     $mode='id';
   }else if(isset( $_REQUEST['key'])){
     $tag=$_REQUEST['key'];
     $mode='key';
   }else{
     $tag=$_SESSION['state']['product']['tag'];
     $mode=$_SESSION['state']['product']['mode'];
   }



if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];

if(!is_numeric($start_from))
  $start_from=0;

   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;



   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['state']['product']['orders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';
 $where='where true';
   
 

  if($mode=='code')
     $where=$where.sprintf(" and P.`Product Code`=%s ",prepare_mysql($tag));
   elseif($mode=='pid')
     $where=$where.sprintf(" and PD.`Product ID`=%d ",$tag);
   elseif($mode=='key')
     $where=$where.sprintf(" and PD.`Product Key`=%d ",$tag);



   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  
$sql="select count(*) as total from `Product Family Dimension` $where $wheref";

   //$sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF  left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)  left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)   $where $wheref";
   //print $sql;   
$res = mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
  $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;  $total_records=$total;
   }else{
     $sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)   left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)  $where      ";
       $res = mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
       }
       
   }

 $rtext=$total_records." ".ngettext('order','orders',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $filter_msg='';


if($order=='dispatched')
      $order='`Shipped Quantity`';
   elseif($order=='order'){
     $order='';
     $order_direction ='';

  }else{
   $order='`Delivery Note Date`';
  
  }
  
  
   $sql=sprintf("select `Delivery Note XHTML Orders`,`Customer Name`,CD.`Customer Key`,`Delivery Note Date`,sum(`Shipped Quantity`) as dispatched,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) as undispatched  from     `Order Transaction Fact` OTF  left join   `Delivery Note Dimension` DND on (OTF.`Delivery Note Key`=DND.`Delivery Note Key`) left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)   left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)    left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)    %s %s  and OTF.`Delivery Note Key`>0  group by OTF.`Delivery Note Key`  order by  $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
		);
   // print $sql;

   $res=mysql_query($sql);
   $data=array();

   while($row= mysql_fetch_array($res, MYSQL_ASSOC) ) {
     if($can_see_customers)
       $customer='<a href="customer.php?id='.$row['Customer Key'].'">'.$row['Customer Name'].'</a>';
     else
       $customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
     


     $data[]=array(
		   'order'=>$row['Delivery Note XHTML Orders'],
		   'customer_name'=>$customer,
		   'date'=> strftime("%e %b %y", strtotime($row['Delivery Note Date'])),
		   'dispatched'=>number($row['dispatched']),
		   'undispatched'=>number($row['undispatched'])

		   );
   }

   $response=array('resultset'=>
		   array('state'=>200,
			 
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}


function orders_lists($data) {

    global $user;

    $conf=$_SESSION['state']['orders_lists'][$data['block_view']];
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
        $_SESSION['state']['orders_lists']['store']=$store;
    } else
        $store=$_SESSION['state']['orders_lists']['store'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    $_SESSION['state']['customers']['list']['order']=$order;
    $_SESSION['state']['customers']['list']['order_dir']=$order_direction;
    $_SESSION['state']['customers']['list']['nr']=$number_results;
    $_SESSION['state']['customers']['list']['sf']=$start_from;
    $_SESSION['state']['customers']['list']['where']=$awhere;
    $_SESSION['state']['customers']['list']['f_field']=$f_field;
    $_SESSION['state']['customers']['list']['f_value']=$f_value;
    


$translate_list_scope=array(
'orders'=>'Order',
'invoices'=>'Invoice',
'dn'=>'Delivery Note',

);

    
     $where=' where `List Scope`="'.addslashes($translate_list_scope[$data['block_view']]).'"';
    



    if (in_array($store,$user->stores)) {
        $where.=sprintf(' and `List Store Key`=%d  ',$store);

    }

    $wheref='';

    $sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
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


    $rtext=$total_records." ".ngettext('List','Lists',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_("Showing all Lists");




    $filter_msg='';





    $_order=$order;
    $_dir=$order_direction;


    if ($order=='name')
        $order='`List Name`';
    elseif($order=='creation_date')
    $order='`List Creation Date`';
    elseif($order=='list_type')
    $order='`List Type`';

    else
        $order='`List Key`';


    $sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Store Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";
   
   
   $adata=array();



    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





        $cusomer_list_name=" <a href='orders_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
        switch ($data['List Type']) {
        case 'Static':
            $customer_list_type=_('Static');
            break;
        default:
            $customer_list_type=_('Dynamic');
            break;

        }

        $adata[]=array(


                     'list_type'=>$customer_list_type,
                     'name'=>$cusomer_list_name,
                     'key'=>$data['List key'],
                     'creation_date'=>strftime("%a %e %b %y %H:%M", strtotime($data['List Creation Date']." +00:00")),
                     'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
                     'delete'=>'<img src="art/icons/cross.png"/>'


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
