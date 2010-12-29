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
                             'store_key'=>array('type'=>'key'),
                            
                         ));
    store_sales($data);
    break;
    
}   


function store_sales($data){
$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`) as net, count(*) as invoices  from `Invoice Dimension` where `Invoice Store Key`=%d group by Date(`Invoice Date`) order by Date(`Invoice Date`) desc",$data['store_key']);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
printf("%s,%d,%f\n",$row['date'],$row['invoices'],$row['net']);
}

}

?>