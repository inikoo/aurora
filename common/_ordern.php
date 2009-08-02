<?php

function get_order_data($order_id){
$db =& MDB2::singleton();
  $sql=sprintf("select * from orden where id=%d",$order_id);
  $res=mysql_query($sql); 
  if ($row=$res->fetchRow()){
    return $row['id'];
  }else
    return false;


}

function get_parent_order_public_id($order_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select id from orden where id=%d",$contact_id);
  $res=mysql_query($sql); 
  if ($row=$res->fetchRow()){
    $sql=sprintf("select public_id from orden where id=%d",$row['id']);
    $res2 = mysql_query($sql);  
    if ($row2=$res2->fetchRow()){
      return $row2['public_id'];
    }
    
  }

  
  return false;


}







?>