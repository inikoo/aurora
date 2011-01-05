<?php


require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {

    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
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

                         ));
    store_sales($data);
    break;

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
   
        if ($row['amount']>0){
        if($row['num_slices']==10){
        $number_slices=10;
        }elseif($row['num_slices']>10){
        $others=$row['amount'];
        
       // printf("%s;%.2f\n",_('Others'),$row['amount']);
   }
   
   }
   }

    $sql=sprintf("select `Product Main Department Code` ,`Product Main Department Name` ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)  where `Customer Key`=%d group by `Product Main Department Key` order by amount desc limit %d",
                 $data['customer_key'],
                 $number_slices
                );
//print $sql;
$sum_slices=0;
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        if ($row['amount']>0){
            printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
            $sum_slices+=$row['amount'];
            
    }
    }
    
    if($others){
    printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
    }
    
}

function customer_families_pie($data) {

$number_slices=14;
$others=0;
 $sql=sprintf("select count(distinct `Product Family Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)  where `Customer Key`=%d",
                 $data['customer_key']
                );

    $res=mysql_query($sql);
   // print $sql;
    if ($row=mysql_fetch_assoc($res)) {
   
        if ($row['amount']>0){
        if($row['num_slices']==10){
        $number_slices=10;
        }elseif($row['num_slices']>10){
        $others=$row['amount'];
        
       // printf("%s;%.2f\n",_('Others'),$row['amount']);
   }
   
   }
   }

    $sql=sprintf("select `Product Family Name`,`Product Family Code` ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)  where `Customer Key`=%d group by `Product Family Key` order by amount desc  limit %d",
                 $data['customer_key'],
                   $number_slices
                );
//print $sql;
    $res=mysql_query($sql);
    $sum_slices=0;
    while ($row=mysql_fetch_assoc($res)) {
        if ($row['amount']>0)
            printf("%s;%.2f\n",$row['Product Family Code'],$row['amount']);
            $sum_slices+=$row['amount'];
    }
    
     if($others){
    printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
    }
}


function store_sales($data) {
    $graph_data=array();

    $store_keys=preg_split('/,/',$data['store_key']);
    $number_stores=count($store_keys);
    $tmp=array();
    for ($i=0; $i<$number_stores; $i++) {

        $tmp['vol'.$i]='';
    }
    for ($i=0; $i<$number_stores; $i++) {

        $tmp['value'.$i]='';
    }

    $sql=sprintf("select  `Date` from kbase.`Date Dimension` where `Date`>= ( select min(`Invoice Date`)   from `Invoice Dimension` where `Invoice Store Key`=%d ) and `Date`<=NOW()  order by `Date` desc",
                 $data['store_key']);

//print $sql;

    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {


        $graph_data[$row['Date']]=$tmp;
        //$graph_data[$row['Date']]['date']=$row['Date'];

    }

//$graph_data=array();
    $i=0;
    foreach($store_keys as $store_key) {
        $sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`) as net, count(*) as invoices  from `Invoice Dimension` where `Invoice Store Key`=%d group by Date(`Invoice Date`) order by `Date` desc",
                     $store_key);
        // print $sql;
        $res=mysql_query($sql);
        while ($row=mysql_fetch_assoc($res)) {
            $graph_data[$row['date']]['vol'.$i]=$row['invoices'];
            $graph_data[$row['date']]['value'.$i]=$row['net'];
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

?>
