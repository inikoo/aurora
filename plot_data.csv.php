<?php


require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {

    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('store_sales'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'string'),

                         ));
    store_sales($data);
    break;

}


function store_sales($data) {
$graph_data=array();

  $store_keys=preg_split('/,/',$data['store_key']);
$number_stores=count($store_keys);
$tmp=array();
for($i=0;$i<$number_stores;$i++){

$tmp['vol'.$i]=0;
}
for($i=0;$i<$number_stores;$i++){

$tmp['value'.$i]=0;
}

 $sql=sprintf("select  `Date` from kbase.`Date Dimension` where `Date`>= ( select min(`Invoice Date`)   from `Invoice Dimension` where `Invoice Store Key`=%d ) and `Date`<=NOW()  order by `Date` desc",
                         $data['store_key']);

//print $sql;

            $res=mysql_query($sql);
            while ($row=mysql_fetch_assoc($res)) {
            
            
            $graph_data[$row['Date']]=$tmp;
            //$graph_data[$row['Date']]['date']=$row['Date'];

            }

$graph_data=array();
$i=0;
foreach($store_keys as $store_key){
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
foreach($graph_data as $key=>$value){
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
