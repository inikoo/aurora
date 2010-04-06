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
function money($amount,$locale=false){
  global $myconf;
  if($amount<0)
    $neg=true;
  else
    $neg=false;
  $amount=abs($amount);
  
  if(!$locale){
    $amount=number_format($amount,2,$_SESSION['locale_info']['decimal_point'],$_SESSION['locale_info']['thousands_sep']);
    $symbol= $_SESSION['locale_info']['currency_symbol'];
    $amount=($neg?'-':'').$symbol.$amount;
    return $amount;
  }else{
    switch($locale){
    case('EUR'):
      $amount=number_format($amount,2,$_SESSION['locale_info']['decimal_point'],$_SESSION['locale_info']['thousands_sep']);
      $symbol='€';
      $amount=($neg?'-':'').$symbol.$amount;
      return $amount;
      break;
    case('GBP'):
      $amount=number_format($amount,2,$_SESSION['locale_info']['decimal_point'],$_SESSION['locale_info']['thousands_sep']);
      $symbol='£';
      $amount=($neg?'-':'').$symbol.$amount;
      return $amount;
      break;


    }

  }

}




?>
