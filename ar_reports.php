<?php
require_once 'common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('ES_1'):
es_1();
}

function es_1(){


global $myconf;

  $conf=$_SESSION['state']['customers']['table'];
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


if(isset( $_REQUEST['y']))
     $year=$_REQUEST['y'];
   else
     $year=date('Y',strtotime('today -1 year'));

if(isset( $_REQUEST['umbral']))
     $umbral=$_REQUEST['umbral'];
   else
     $umbral=3000;


  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

   if(isset( $_REQUEST['store_id'])    ){
     $store=$_REQUEST['store_id'];
     $_SESSION['state']['customers']['store']=$store;
   }else
     $store=$_SESSION['state']['customers']['store'];


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['customers']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $filter_msg='';
   $wheref='';
   
   
   if(is_numeric($store)){
     $where.=sprintf(' and `Customer Store Key`=%d ',$store);
   }
   
$where.=sprintf(' and `Customer Main Address Country Code`="ESP"   and Year(`Invoice Date`)=%d',$year );
   

$rtext='';
$filtered=0;
$_order='';
$_dir='';
$total=0;

   $sql="select  GROUP_CONCAT(`Invoice Key`) as invoice_keys,`Customer Main Location`,`Customer Key`,`Customer Name`,`Customer ID`,`Customer Main XHTML Email`,count(DISTINCT `Invoice Key`) as invoices,sum(`Invoice Total Amount`) as total, sum(`Invoice Total Net Amount`) as net from  `Invoice Dimension` I left join  `Customer Dimension` C  on (I.`Invoice Customer Key`=C.`Customer Key`)  $where $wheref  group by `Customer Key` order by total desc";
   $adata=array();
  
  
  
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){

if($data['total']<$umbral)
break;  
$total++;

$tax1=0;
$tax2=0;

$sql2=sprintf("select `Tax Code`,sum(`Tax Amount`) as amount from `Invoice Tax Bridge` where `Invoice Key` in (%s) group by `Tax Code`  ", $data['invoice_keys']);
$res2=mysql_query($sql2);
while($row2=mysql_fetch_array($res2)){
//print_r($row2);
if($row2['Tax Code']=='IVA'){
$tax1=$row2['amount'];
}
if($row2['Tax Code']=='I2'){
$tax2=$row2['amount'];
}

}

    $id="<a href='customer.php?id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer ID']).'</a>'; 
    $name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>'; 

$tax1=0;
$tax2=0;

    $adata[]=array(
		   'id'=>$id,
		   'name'=>$name,
		   'total'=>money($data['total']),
		   'net'=>money($data['net']),
		   'tax1'=>money($tax1),
		   'tax2'=>money($tax2),
		   'invoices'=>number($data['invoices']),
		   'location'=>$data['Customer Main Location']
		  

		   );
  }
mysql_free_result($result);

$rtext=number($total).' '._('Records found'); 


  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'rtext'=>$rtext,
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