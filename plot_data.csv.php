<?php


require_once 'common.php';

setlocale(LC_ALL,'en_GB');

require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {

    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('customer_business_type_pie'):
 $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
customer_business_type_pie($data);
break;
case('customer_business_type_assigned_pie'):
 $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
customer_business_type_assigned_pie($data);
break;
case('customer_referral_pie'):
 $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
customer_referral_pie($data);
break;
case('customer_referral_assigned_pie'):
 $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
customer_referral_assigned_pie($data);
break;
case('top_families'):

    $data=prepare_values($_REQUEST,array(
                             'store_keys'=>array('type'=>'string'),
                             'period'=>array('type'=>'string')
                         ));
    top_families($data);
    break;

case('number_of_contacts'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
    number_of_contacts($data);
    break;

case('number_of_customers'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
    number_of_customers($data);
    break;
case('store_departments_pie'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             
                         ));
  
    
    store_departments_pie($data);
    break;
 case('store_families_pie'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
                  
    store_families_pie($data);
    break;
case('store_product_pie'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
    store_product_pie($data);
    break;    
    
    
    
case('customers_orders_pie'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
    customers_orders_pie($data);
    break;
case('customers_data_completeness_pie'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                         ));
    customers_data_completeness_pie($data);
    break;
case('customer_departments_pie'):
    $data=prepare_values($_REQUEST,array(
                             'customer_key'=>array('type'=>'key'),
                         ));
    customer_departments_pie($data);
    break;
case('customer_families_pie'):
    $data=prepare_values($_REQUEST,array(
                             'customer_key'=>array('type'=>'key'),
                         ));
    customer_families_pie($data);
    break;
case('store_sales'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'string'),
                             'from'=>array('type'=>'date','optional'=>true),
                             'to'=>array('type'=>'date','optional'=>true),
                             'use_corporate'=>array('type'=>'number')
                         ));
    store_sales($data);
    break;
case('department_sales'):
    $data=prepare_values($_REQUEST,array(
                             'department_key'=>array('type'=>'string'),
                             'from'=>array('type'=>'date','optional'=>true),
                             'to'=>array('type'=>'date','optional'=>true),
                             'use_corporate'=>array('type'=>'number')
                         ));
    department_sales($data);
    break;
case('family_sales'):
    $data=prepare_values($_REQUEST,array(
                             'family_key'=>array('type'=>'string'),
                             'from'=>array('type'=>'date','optional'=>true),
                             'to'=>array('type'=>'date','optional'=>true),
                             'use_corporate'=>array('type'=>'number')
                         ));
    family_sales($data);
    break;
 case('product_id_sales'):
    $data=prepare_values($_REQUEST,array(
                             'product_id'=>array('type'=>'string'),
                             'from'=>array('type'=>'date','optional'=>true),
                             'to'=>array('type'=>'date','optional'=>true),
                             'use_corporate'=>array('type'=>'number')
                         ));
    product_id_sales($data);
    break;   
    
case('stacked_store_sales'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'string'),
                             'from'=>array('type'=>'date','optional'=>true),
                             'to'=>array('type'=>'date','optional'=>true),
                         ));
    stacked_store_sales($data);
    break;    
case('part_location_stock_history'):
    $data=prepare_values($_REQUEST,array(
                             'part_sku'=>array('type'=>'key'),
                             'location_key'=>array('type'=>'numeric'),
                         ));
    part_location_stock_history($data);
    break;
}

function part_location_stock_history($data) {

if($data['location_key']){

    $sql=sprintf("select `Date`,`Quantity Open`,`Quantity High`,`Quantity Low`,`Quantity On Hand`, (`Quantity Sold`+`Quantity In`+`Quantity Lost`) as `Volume` from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d order by `Date` desc",
                 $data['part_sku'],
                 $data['location_key']
                );
    $res=mysql_query($sql);

    while ($row=mysql_fetch_assoc($res)) {
        printf("%s,%s,%s,%s,%s,%s\n",$row['Date'],$row['Quantity Open'],$row['Quantity High'],$row['Quantity Low'],$row['Quantity On Hand'],$row['Volume']);
    }
}else{// stock in all locatins
 $sql=sprintf("select `Date`,sum(`Quantity Open`) as open ,max(`Quantity High`) as high,min(`Quantity Low`) as low,sum(`Quantity On Hand`) as close, sum(`Quantity Sold`+`Quantity In`+`Quantity Lost`) as `Volume` from `Inventory Spanshot Fact` where `Part SKU`=%d group by `Date` order by `Date` desc",
                 $data['part_sku']
              
                );
    $res=mysql_query($sql);

    while ($row=mysql_fetch_assoc($res)) {
        printf("%s,%s,%s,%s,%s,%s\n",$row['Date'],$row['open'],$row['high'],$row['low'],$row['close'],$row['Volume']);
    }

}

}
function number_of_contacts($data) {

    $sql=sprintf("select `Time Series Date`,`Open`,`High`,`Low`,`Close`,`Volume` from `Time Series Dimension` where `Time Series Name`='contact population' and `Time Series Name Key`=%d order by `Time Series Date` desc",
                 $data['store_key']
                );
    $res=mysql_query($sql);

    while ($row=mysql_fetch_assoc($res)) {
        printf("%s,%s,%s\n",$row['Time Series Date'],$row['Volume'],$row['Close']);
    }


}
function number_of_customers($data) {
    $sql=sprintf("select `Time Series Date`,`Open`,`High`,`Low`,`Close`,`Volume` from `Time Series Dimension` where `Time Series Name`='customer population' and `Time Series Name Key`=%d order by `Time Series Date` desc",
                 $data['store_key']
                );
    $res=mysql_query($sql);

    while ($row=mysql_fetch_assoc($res)) {
        printf("%s,%s,%s,%s,%s,%s\n",$row['Time Series Date'],$row['Open'],$row['High'],$row['Low'],$row['Close'],$row['Volume']);
    }


}
function customers_orders_pie($data) {

    $pie_data=array(
                  "o51_"=>array('title'=>_('Contacts with more than 50 orders'),'number'=>0,'short_title'=>"50> "._("Orders")),
                  "o21_50"=>array('title'=>_('Contacts with 21-50 orders'),'number'=>0,'short_title'=>"21-50 "._("Orders")),
                  "o11_20"=>array('title'=>_('Contacts with 11-20 orders'),'number'=>0,'short_title'=>"11-20 "._("Orders")),
                  "o5_10"=>array('title'=>_('Contacts with 5-10 orders'),'number'=>0,'short_title'=>"5-10 "._("Orders")),
                  "o4"=>array('title'=>_('Contacts with 4 orders'),'number'=>0,'short_title'=>"4 "._("Orders")),
                  "o3"=>array('title'=>_('Contacts with 3 orders'),'number'=>0,'short_title'=>"3 "._("Orders")),
                  "o2"=>array('title'=>_('Contacts with 2 orders'),'number'=>0,'short_title'=>"2 "._("Orders")),
                  "o1"=>array('title'=>_('Contacts with one orders'),'number'=>0,'short_title'=>"1 "._("Order")),
                  "o0"=>array('title'=>_('Contacts with no orders'),'number'=>0,'short_title'=>_('No Orders')),
              );

    $number_slices=9;
    $others=0;

    $where='where true';
    if ($data['store_key']) {
        $where=sprintf("where `Customer Store Key`=%d",$data['store_key']);
    }


    $sql=sprintf("select
                 sum(if(`Customer Orders`=0,1,0)) as o0 ,
                 sum(if(`Customer Orders`=1,1,0)) as o1 ,
                 sum(if(`Customer Orders`=2,1,0)) as o2 ,
                 sum(if(`Customer Orders`=3,1,0)) as o3 ,
                 sum(if(`Customer Orders`=4,1,0)) as o4 ,
                 sum(if(`Customer Orders`>=5 and `Customer Orders`<=10,1,0)) as o5_10 ,
                 sum(if(`Customer Orders`>=11 and `Customer Orders`<=20,1,0)) as o11_20 ,
                 sum(if(`Customer Orders`>=21 and `Customer Orders`<=50,1,0)) as o21_50 ,
                 sum(if(`Customer Orders`>=51 ,1,0)) as `o51_`
                 from `Customer Dimension` %s",
                 $where
                );

    $res=mysql_query($sql);

    while ($row=mysql_fetch_assoc($res)) {
        $pie_data['o0']['number']=$row['o0'];
        $pie_data['o1']['number']=$row['o1'];
        $pie_data['o2']['number']=$row['o2'];
        $pie_data['o3']['number']=$row['o3'];
        $pie_data['o5_10']['number']=$row['o5_10'];
        $pie_data['o11_20']['number']=$row['o11_20'];
        $pie_data['o21_50']['number']=$row['o21_50'];
        $pie_data['o51_']['number']=$row['o51_'];

    }


    foreach($pie_data as $key=>$values) {
        if ($values['number']>0)
            printf("%s;%.2f;;;customers.php?where=data_%s,4s\n",$values['short_title'],$values['number'],$key,$values['title']);
    }






}
function customers_data_completeness_pie($data) {

    $pie_data=array(
                  "ok"=>array('title'=>_('Contacts with all data'),'number'=>0,'short_title'=>'Ok'),
                  "a"=>array('title'=>_('Contacts missing address'),'number'=>0,'short_title'=>"No Address"),
                  "e"=>array('title'=>_('Contacts missing email'),'number'=>0,'short_title'=>'No Email'),
                  "t"=>array('title'=>_('Contacts missing telephone'),'number'=>0,'short_title'=>'No Tel'),
                  "ae"=>array('title'=>_('Contacts missing address & email'),'number'=>0,'short_title'=>'No Email & Address'),
                  "at"=>array('title'=>_('Contacts missing address & telephone'),'number'=>0,'short_title'=>'No Address & Tel'),
                  "et"=>array('title'=>_('Contacts missing email & telephone'),'number'=>0,'short_title'=>'No Email & Tel'),
                  "aet"=>array('title'=>_('Contacts missing address, email & telephone'),'number'=>0,'short_title'=>"No Email Address Tel"),
              );

    $number_slices=9;
    $others=0;

    $where='where true';
    if ($data['store_key']) {
        $where=sprintf("where `Customer Store Key`=%d",$data['store_key']);
    }


    $sql=sprintf("select  count(*) as total,
                     sum(if(!ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as ok ,

                 sum(if(ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as e ,
                 sum(if(!ISNULL(`Customer Main Email Key`) AND  ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as t ,
                 sum(if(!ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='Yes'  ,1,0)) as a,
                 sum(if(ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='Yes'  ,1,0)) as ae,
                 sum(if(!ISNULL(`Customer Main Email Key`) AND  ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='Yes'  ,1,0)) as at,
                 sum(if(ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as et,
                 sum(if(ISNULL(`Customer Main Email Key`) AND  ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as aet
                 from `Customer Dimension` %s",
                 $where
                );

    $res=mysql_query($sql);

    while ($row=mysql_fetch_assoc($res)) {
        $pie_data['a']['number']=$row['a'];
        $pie_data['e']['number']=$row['e'];
        $pie_data['t']['number']=$row['t'];
        $pie_data['ae']['number']=$row['ae'];
        $pie_data['at']['number']=$row['at'];
        $pie_data['et']['number']=$row['et'];
        $pie_data['aet']['number']=$row['aet'];
        $pie_data['ok']['number']=$row['total']-$row['a']-$row['e']-$row['t']-$row['ae']-$row['at']-$row['et']-$row['aet'];
    }


    foreach($pie_data as $key=>$values) {
        if ($values['number']>0)
            printf("%s;%.2f;;;customers.php?where=data_%s,4s\n",$values['short_title'],$values['number'],$key,$values['title']);
    }






}
function customer_departments_pie($data) {
    $number_slices=9;
    $others=0;
    $sql=sprintf("select count(distinct `Product Main Department Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)  where `Customer Key`=%d",
                 $data['customer_key']
                );

    $res=mysql_query($sql);
    // print $sql;
    if ($row=mysql_fetch_assoc($res)) {

        if ($row['amount']>0) {
            if ($row['num_slices']==10) {
                $number_slices=10;
            }
            elseif($row['num_slices']>10) {
                $others=$row['amount'];

                // printf("%s;%.2f\n",_('Others'),$row['amount']);
            }

        }
    }

    $sql=sprintf("select `Product Main Department Code` ,`Product Main Department Name` ,`Product Main Department Key`,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)  where `Customer Key`=%d group by `Product Main Department Key` order by amount desc limit %d",
                 $data['customer_key'],
                 $number_slices
                );
//print $sql;
    $sum_slices=0;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        if ($row['amount']>0) {
            // printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
            $descripton=$row['Product Main Department Name'];
            printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Product Main Department Code'],$row['amount'],$row['Product Main Department Key'],$descripton);
            $sum_slices+=$row['amount'];

        }
    }

    if ($others) {
        printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
    }

}
function customer_families_pie($data) {

    $number_slices=14;
    $others=0;
    $sql=sprintf("select count(distinct `Product Family Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact` OTF where `Customer Key`=%d",
                 $data['customer_key']
                );

    $res=mysql_query($sql);
    // print $sql;
    if ($row=mysql_fetch_assoc($res)) {

        if ($row['amount']>0) {
            if ($row['num_slices']==10) {
                $number_slices=10;
            }
            elseif($row['num_slices']>10) {
                $others=$row['amount'];

                // printf("%s;%.2f\n",_('Others'),$row['amount']);
            }

        }
    }

    $sql=sprintf("select `Product Family Name`,`Product Family Code`,OTF.`Product Family Key` ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product Family Dimension` F on (OTF.`Product Family Key`=F.`Product Family Key`)  where `Customer Key`=%d group by OTF.`Product Family Key` order by amount desc  limit %d",
                 $data['customer_key'],
                 $number_slices
                );
//print $sql;
    $res=mysql_query($sql);
    $sum_slices=0;
    while ($row=mysql_fetch_assoc($res)) {
        if ($row['amount']>0) {
            $descripton=$row['Product Family Name'];
            printf("%s;%.2f;;;family.php?id=%d;%s\n",$row['Product Family Code'],$row['amount'],$row['Product Family Key'],$descripton);
            $sum_slices+=$row['amount'];
        }
    }

    if ($others) {
        printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
    }
}
function store_sales($data) {
    global $user;
    $tmp=preg_split('/\,/', $data['store_key']);
    $stores_keys=array();
    foreach($tmp as $store_key) {

        if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
            $stores_keys[]=$store_key;
        }
    }

    $graph_data=array();



    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
    } else {
        $dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$stores_keys));
    }

    $sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
                 $dates

                );

//print $sql;

    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {

        $graph_data[$row['Date']]['vol']=0;

        $graph_data[$row['Date']]['value']=0;
        //$graph_data[$row['Date']]['date']=$row['Date'];

    }


    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Invoice Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
    }

    $corporate_currency='';
    if($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange`';
    $sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount` %s) as net, count(*) as invoices  from `Invoice Dimension` where  %s and `Invoice Store Key`  in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
                 $corporate_currency,
                 $dates,
                 join(',',$stores_keys)
                );
   // print $sql;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        $graph_data[$row['date']]['vol']=$row['invoices'];
        $graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
    }



    $out='';
//print_r($graph_data);
    foreach($graph_data as $key=>$value) {
        print $key.','.join(',',$value)."\n";
    }


}
function department_sales($data) {
    global $user;
    $tmp=preg_split('/\,/', $data['department_key']);
    $departments_keys=array();
    foreach($tmp as $department_key) {

        if (is_numeric($department_key)) {
            $departments_keys[]=$department_key;
        }
    }

    $graph_data=array();



    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
    } else {
        $dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Order Transaction Fact` where `Product Depament Key` in (%s)  and `Current Payment State`='Paid'  )",join(',',$departments_keys));
    }

    $sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
                 $dates

                );

//print $sql;

    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {

        $graph_data[$row['Date']]['vol']=0;

        $graph_data[$row['Date']]['value']=0;
        //$graph_data[$row['Date']]['date']=$row['Date'];

    }


    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Invoice Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
    }

    $corporate_currency='';
    if($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange Rate`';
    $sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` %s) as net, count(*) as invoices  from `Order Transaction Fact` where  %s and `Product Depament Key` in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
                 $corporate_currency,
                 $dates,
                 join(',',$departments_keys)
                );
   //print $sql;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        $graph_data[$row['date']]['vol']=$row['invoices'];
        $graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
    }



    $out='';
//print_r($graph_data);
    foreach($graph_data as $key=>$value) {
        print $key.','.join(',',$value)."\n";
    }


}
function family_sales($data) {
    global $user;
    $tmp=preg_split('/\,/', $data['family_key']);
    $familys_keys=array();
    foreach($tmp as $family_key) {

        if (is_numeric($family_key)) {
            $familys_keys[]=$family_key;
        }
    }

    $graph_data=array();



    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
    } else {
        $dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Order Transaction Fact` where `Product Family Key` in (%s)  and `Current Payment State`='Paid'  )",join(',',$familys_keys));
    }

    $sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
                 $dates

                );

//print $sql;

    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {

        $graph_data[$row['Date']]['vol']=0;

        $graph_data[$row['Date']]['value']=0;
        //$graph_data[$row['Date']]['date']=$row['Date'];

    }


    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Invoice Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
    }

    $corporate_currency='';
    if($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange Rate`';
    $sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` %s) as net, count(*) as invoices  from `Order Transaction Fact` where  %s and `Product Family Key` in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
                 $corporate_currency,
                 $dates,
                 join(',',$familys_keys)
                );
   //print $sql;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        $graph_data[$row['date']]['vol']=$row['invoices'];
        $graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
    }



    $out='';
//print_r($graph_data);
    foreach($graph_data as $key=>$value) {
        print $key.','.join(',',$value)."\n";
    }


}
function product_id_sales($data) {
    global $user;
    $tmp=preg_split('/\,/', $data['product_id']);
    $product_ids=array();
    foreach($tmp as $product_id) {

        if (is_numeric($product_id)) {
            $product_ids[]=$product_id;
        }
    }

    $graph_data=array();



    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
    } else {
        $dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Order Transaction Fact` where `Product ID` in (%s)  and `Current Payment State`='Paid'  )",join(',',$product_ids));
    }

    $sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
                 $dates

                );

//print $sql;

    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {

        $graph_data[$row['Date']]['vol']=0;

        $graph_data[$row['Date']]['value']=0;
        //$graph_data[$row['Date']]['date']=$row['Date'];

    }


    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Invoice Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
    }

    $corporate_currency='';
    if($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange Rate`';
    $sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` %s) as net, count(*) as invoices  from `Order Transaction Fact` where  %s and `Product ID` in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
                 $corporate_currency,
                 $dates,
                 join(',',$product_ids)
                );
   //print $sql;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        $graph_data[$row['date']]['vol']=$row['invoices'];
        $graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
    }



    $out='';
//print_r($graph_data);
    foreach($graph_data as $key=>$value) {
        print $key.','.join(',',$value)."\n";
    }


}
function stacked_store_sales($data) {



    $graph_data=array();
    
    global $user;
    $tmp=preg_split('/\,/', $data['store_key']);
    $store_keys=array();
    foreach($tmp as $store_key) {

        if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
            $store_keys[]=$store_key;
        }
    }
    

   
    $number_stores=count($store_keys);
    $tmp=array();
    for ($i=0; $i<$number_stores; $i++) {
 $tmp['value'.$i]=0;
        $tmp['vol'.$i]=0;
    }
   
    if (array_key_exists('to',$data)) {
        $dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
    } else {
        $dates=sprintf(" `Date`<=NOW()  ");
    }
    if (array_key_exists('from',$data)) {
        $dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
    } else {
        $dates.=sprintf("and  `Date`>= ( select min(DATE(`Invoice Date`))   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$store_keys));
    }

    $sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` ",
                 $dates

                );

//print $sql;

    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {


        $graph_data[$row['Date']]=$tmp;
        //$graph_data[$row['Date']]['date']=$row['Date'];

    }

//$graph_data=array();
    $i=0;
    foreach($store_keys as $store_key) {

        if (array_key_exists('to',$data)) {
            $dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
        } else {
            $dates=sprintf(" `Invoice Date`<=NOW()  ");
        }
        if (array_key_exists('from',$data)) {
            $dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
        }

        $sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net, count(*) as invoices  from `Invoice Dimension` where  %s and `Invoice Store Key`=%d   group by Date(`Invoice Date`) order by `Date` desc",
                     $dates,
                     $store_key);
        //print $sql;
        $res=mysql_query($sql);
        while ($row=mysql_fetch_assoc($res)) {
           
            $graph_data[$row['date']]['value'.$i]=sprintf("%.2f",$row['net']);
             $graph_data[$row['date']]['vol'.$i]=$row['invoices'];
        }
        $i++;
    }

    $out='';
//print_r($graph_data);
    foreach($graph_data as $key=>$value) {
        print $key.','.join(',',$value)."\n";
    }

    /*
         if (is_numeric($data['store_key'])) {
             $sql=sprintf("select `Store Key`,Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`) as net, count(*) as invoices  from `Invoice Dimension` where `Invoice Store Key`=%d group by Date(`Invoice Date`) order by Date(`Invoice Date`) desc",
                          $data['store_key']);
             $res=mysql_query($sql);
             while ($row=mysql_fetch_assoc($res)) {
                 $sales_data[$row['date']]
                 printf("%s,%d,%f\n",$row['date'],$row['invoices'],$row['net']);
             }
         }
     }

    */
}
function top_families($data) {

    $max_slices=20;


    $store_keys=preg_split('/,/',$data['store_keys']);

    if (!is_array($store_keys) or count($store_keys)==0) {
        return;
    }

    $valid_store_keys=array();
    foreach($store_keys as $store_key) {
        if (is_numeric($store_key))
            $valid_store_keys[]=$store_key;
    }
    if (count($valid_store_keys)==0)return;

    $period=$data['period'];

    $field='(`Product Family Total Invoiced Gross Amount`-`Product Family Total Invoiced Discount Amount`)';



    switch ($period) {

    case('1m'):
        $field='(`Product Family 1 Month Acc Invoiced Amount`)';

        break;
    case('1y'):
        $field='(`Product Family 1 Year Acc Invoiced Amount`)';

        break;
    case('1q'):
        $field='(`Product Family 1 Quarter Acc Invoiced Amount`)';

        break;
    default:
        $field='(`Product Family Total Invoiced Gross Amount`-`Product Family Total Invoiced Discount Amount`)';


    }







    $total=0;
    $sql=sprintf("select sum%s as sales from `Product Family Dimension` where `Product Family Store Key` in (%s)  ",
                 $field,
                 join(",",$valid_store_keys)
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $total=$row['sales'];
    }

    $others=$total;
    $sql=sprintf("select `Product Family Store Code`,`Product Family Name`,`Product Family Key`,`Product Family Code`,%s as sales from `Product Family Dimension` where `Product Family Store Key` in (%s) order by sales desc limit %d ",
                 $field,
                 join(",",$valid_store_keys),
                 $max_slices
                );
    $res=mysql_query($sql);
    //print $sql;
    while ($row=mysql_fetch_assoc($res)) {
        $descripton='';//$row['Product Family Name'];
                $descripton=$row['Product Family Store Code'].' '.$row['Product Family Code'];

        $code=$row['Product Family Code'];
        printf("%s;%.2f;;;family.php?id=%d;%s\n",$code,$row['sales'],$row['Product Family Key'],$descripton);
        $others-=$row['sales'];
    }

    if ($others) {
        printf("%s;%.2f;true\n",_('Others'),$others);
    }

}


function store_departments_pie($data) {
    $number_slices=9;
    $others=0;
    
  
    
    $sql=sprintf("select count(distinct OTF.`Product Department Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Department Dimension` D on (D.`Product Department Key`=OTF.`Product Department Key`)  where OTF.`Store Key`=%d",
                 $data['store_key']
                );

    $res=mysql_query($sql);
    // print $sql;
    if ($row=mysql_fetch_assoc($res)) {

        if ($row['amount']>0) {
            if ($row['num_slices']==10) {
                $number_slices=10;
            }
            elseif($row['num_slices']>10) {
                $others=$row['amount'];

                // printf("%s;%.2f\n",_('Others'),$row['amount']);
            }

        }
    }

    $sql=sprintf("select `Product Department Code` ,`Product Department Name` ,OTF.`Product Department Key`,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Department Dimension` D on (D.`Product Department Key`=OTF.`Product Department Key`)  where OTF.`Store Key`=%d group by OTF.`Product Department Key` order by amount desc limit %d",
                 $data['store_key'],
                 $number_slices
                );
//print $sql;
    $sum_slices=0;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        if ($row['amount']>0) {
            // printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
            $descripton=$row['Product Department Name'];
            printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Product Department Code'],$row['amount'],$row['Product Department Key'],$descripton);
            $sum_slices+=$row['amount'];

        }
    }

    if ($others) {
        printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
    }

}
function store_families_pie($data) {
    $number_slices=14;
    $others=0;
    
  
    
    $sql=sprintf("select count(distinct OTF.`Product Family Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Family Dimension` D on (D.`Product Family Key`=OTF.`Product Family Key`)  where OTF.`Store Key`=%d",
                 $data['store_key']
                );

    $res=mysql_query($sql);
    // print $sql;
    if ($row=mysql_fetch_assoc($res)) {

        if ($row['amount']>0) {
            if ($row['num_slices']==10) {
                $number_slices=10;
            }
            elseif($row['num_slices']>10) {
                $others=$row['amount'];

                // printf("%s;%.2f\n",_('Others'),$row['amount']);
            }

        }
    }

    $sql=sprintf("select `Product Family Code` ,`Product Family Name` ,OTF.`Product Family Key`,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Family Dimension` D on (D.`Product Family Key`=OTF.`Product Family Key`)  where OTF.`Store Key`=%d group by OTF.`Product Family Key` order by amount desc limit %d",
                 $data['store_key'],
                 $number_slices
                );
//print $sql;
    $sum_slices=0;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        if ($row['amount']>0) {
            // printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
            $descripton=$row['Product Family Name'];
            printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Product Family Code'],$row['amount'],$row['Product Family Key'],$descripton);
            $sum_slices+=$row['amount'];

        }
    }

    if ($others) {
        printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
    }

}

function customer_referral_assigned_pie($data) {
    $sql=sprintf("select `Category Key`,`Category Children Subjects Assigned`,`Category Children Subjects Not Assigned` from `Category Dimension` where `Category Subject`='Customer' and `Category Name`='Referrer' and `Category Deep`=1 and `Category Store Key`=%d",$data['store_key']);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        printf("%s;%d;;ff0000;;%s;40\n",_('No assigned'),$row['Category Children Subjects Not Assigned'],'');
        printf("%s;%d;true;B0DE09;customer_categories.php?id=%d;%s\n",_('Assigned'),$row['Category Children Subjects Assigned'],$row['Category Key'],'');
    }
}


function customer_referral_pie($data) {

$sql=sprintf("select `Category Key`,`Category Children Subjects Assigned`,`Category Children Subjects Not Assigned` from `Category Dimension` where `Category Subject`='Customer' and `Category Name`='Referrer' and `Category Deep`=1 and `Category Store Key`=%d",$data['store_key']);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {

    $sql=sprintf("select `Category Key`,`Category Number Subjects`,`Category Label` from `Category Dimension` where `Category Subject`='Customer' and `Category Parent Key`=%d order by `Category Number Subjects` desc",$row['Category Key']);
    $res2=mysql_query($sql);
    while ($row2=mysql_fetch_assoc($res2)) {
        printf("%s;%d;;;customer_categories.php?id=%d;%s\n",$row2['Category Label'],$row2['Category Number Subjects'],$row2['Category Key'],'');
    }
    }
}

function customer_business_type_assigned_pie($data) {
    $sql=sprintf("select `Category Key`,`Category Children Subjects Assigned`,`Category Children Subjects Not Assigned` from `Category Dimension` where `Category Subject`='Customer' and `Category Name`='Type of Business' and `Category Deep`=1 and `Category Store Key`=%d ",$data['store_key']);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        printf("%s;%d;;ff0000;;%s;40\n",_('No assigned'),$row['Category Children Subjects Not Assigned'],'');
        printf("%s;%d;true;B0DE09;customer_categories.php?id=%d;%s\n",_('Assigned'),$row['Category Children Subjects Assigned'],$row['Category Key'],'');
    }
}


function customer_business_type_pie($data) {

$sql=sprintf("select `Category Key`,`Category Children Subjects Assigned`,`Category Children Subjects Not Assigned` from `Category Dimension` where `Category Subject`='Customer' and `Category Name`='Type of Business' and `Category Deep`=1 and `Category Store Key`=%d",$data['store_key']);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {

    $sql=sprintf("select `Category Key`,`Category Number Subjects`,`Category Label` from `Category Dimension` where `Category Subject`='Customer' and `Category Parent Key`=%d order by `Category Number Subjects` desc ",$row['Category Key']);
    $res2=mysql_query($sql);
    while ($row2=mysql_fetch_assoc($res2)) {
        printf("%s;%d;;;customer_categories.php?id=%d;%s\n",$row2['Category Label'],$row2['Category Number Subjects'],$row2['Category Key'],'');
    }
    }
}


?>
