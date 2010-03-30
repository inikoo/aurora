<?php
/*
 Function: prepare_mysql
Prepare string to be useed in the Database

If string is empty returs NULL unless $null_if_empty is false

Parameter:
$string - *string* to be prepared
$null_if_empty - *bolean* config flag
 */

function prepare_mysql($string,$null_if_empty=true){

  if(is_numeric($string)){
    return "'".$string."'";
  }elseif($string=='' and $null_if_empty){
    return 'NULL';
  }else{
     return "'".addslashes($string)."'";
  

  }
}

?>
