<?

function get_supplier_code($supplier_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select code from supplier where id=%d",$supplier_id);
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return $row['code'];
  }else
    return false;
}

?>