<?php

function get_supplier_code($supplier_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select code from supplier where id=%d",$supplier_id);
  $res=mysql_query($sql); 
  if ($row=$res->fetchRow()){
    return $row['code'];
  }else
    return false;
}


function get_supplier_data($supplier_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select * from supplier where id=%d",$supplier_id);

  $res=mysql_query($sql); 
  $data=array();
  if ($row=$res->fetchRow()){
    $data= $row;
  }

  return $data;
}




?>