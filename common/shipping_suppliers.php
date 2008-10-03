<?
function get_shipping_supplier($name,$order_id){
$db =& MDB2::singleton();



  if($name=='')
    return false;
  
  if(preg_match('/apc to collect/i',$name))
    $name='apc';
  // print "$name\n";
  
  
  if(preg_match('/Must go out on Wed|opposite|signature|next door|Boscowen|Behind|open|closed|shop|drink|please|leave|@|Dispatch|by sea|zif|deliv|take|andy|give|call|leave|showroom|staff|inter|frei|0|shang|local|colle|Kirkby|wine|Customer Own Carrier|Replacement|Shop closed on Mon|Leave with no. 3 if not in or hut|Subs OK but CAL|Deliveries|deliver next door|shortages|Not open until/i',$name))
    return false;
 if(preg_match('/dbl/i',$name))
    $name='dbl';

  if(preg_match('/FRANS MASS|Frans Maas/i',$name))
    $name='dsv';
if(preg_match('/dfds/i',$name))
    $name='dfds';
  if(preg_match('/parcel\s*line/i',$name))
    $name='dpd';
  if(preg_match('/amtrak|amtrack|Amrak|Amstrak/i',$name))
    $name='amtrak';
  if(preg_match('/interlink/i',$name))
    $name='interlink';
if(preg_match('/fedex/i',$name))
    $name='fedex';

  if(preg_match('/parcel\s*force|parcel forcce/i',$name))
    $name='parcelforce';
  if(preg_match('/first class|second class|Recorded Delivery|post|mail/i',$name))
    $name='parcelforce';


  $supplier_id='';
  $sql=sprintf("select id from supplier where code like '%s' ",$name);
  //print "$sql\n";
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    $supplier_id=$row['id'];
    
  }else{
    $sql=sprintf("insert into todo_shipping_supplier (name,order_id) values ('%s',%d) ",$name,$order_id);
    // print "$sql\n";
    mysql_query($sql);
    
  }


  return $supplier_id;



}

?>