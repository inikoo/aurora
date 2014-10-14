

<?php
include_once('../../conf/dns.php');
include_once('../../class.Product.php');
include_once('../../class.Order.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
require_once '../../myconf/conf.php';           

date_default_timezone_set('UTC');
// $product=new Product(1);
// $product->load('first_date','save');
// exit;
$sql="select product.units,transaction.id ,product_id,dispatched,charge,date_index from transaction left join orden on (order_id=orden.id) left join product on (product_id=product.id)";
//print "$sql\n";
$res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $id=$row['id'];
  $product_id=$row['product_id'];
  $dispatched=$row['dispatched'];
  $charge=$row['charge'];
  $estimated_cost=estimated_cost($product_id,$date);
  $profit=$charge-($row['units']*$dispatched*$estimated_cost);
  print "$id $charge $profit \r";
  $sql=sprintf("update transaction set profit=%.2f where id=%d",$profit,$id);
  mysql_query($sql);
 }
print"\n";
function estimated_cost($product_id,$date){
  $db =MDB2::singleton();
  $cost=0;
  $sql=sprintf("select avg(price) as cost from product2supplier where product_id=%d",$product_id);
  $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $cost=$row['cost'];
  }else{
    $sql=sprintf("select price,units from product whereid=%d",$product_id);
    $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $cost=0.4*$row['price']/$row['units'];
    }
  }
  return $cost;
}

?>