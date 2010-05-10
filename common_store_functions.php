<?php
function formated_rrp($data){

$locale=$data['locale'];

  $rrp=money($data['Product RRP']/$data['Product Units Per Case'],$locale,$data['Product Currency']);
  if ($locale=="de_DE"){
    return "UVP: $rrp pro Stück";
  }elseif ($locale=="pl_PL"){
    return "SCD:$rrp za sztukę";
  }elseif($locale=="fr_FR") {
    return "PVC:$rrp  /unité PVC";
  }
  else
    return _("RRP").": $rrp "._("each");
  
}


function formated_price($data){

  $locale=$data['locale'];

  
  $price=money_locale($data['Product Price'],$locale,$data['Product Currency']);
  $price_per_unit=money_locale($data['Product Price']/$data['Product Units Per Case'],$locale,$data['Product Currency']);
  
  if ($locale=='de_DE') {
    if (isset($data['price per unit text'])) {
      $str=$data['price per unit text']." $price";
    } else {
      if ($data['Product Units Per Case']>1)
	$str="$price/".$data['Product Units Per Case']." ($price_per_unit pro Stück)";
      else
	$str="$price pro Stück";
    }  
    
    if ($data=='from')
      return 'Preis ab '.$str;
    else
      return 'Preis: '.$str;
  
	
  }elseif ($locale=='pl_PL') {
	if (isset($data['price per unit text'])) {
	  $str=$data['price per unit text']." $price";
	} else {
	  if ($data['Product Units Per Case']>1)
	$str="$price/".$data['Product Units Per Case']." ($price_per_unit za sztukę)";
      else
	$str="$price za sztukę";

	}
	if ($data=='from')
	  return 'Cena od '.$str;
	else
	  return 'Cena: '.$str;


      }
      elseif($locale=='fr_FR') {

	if ( is_array($data) and isset($data['price per unit text'])  ) {
	  $str=$data['price per unit text']." $price";
	} else {
	  if ($data['Product Units Per Case']>1)
	    $str="$price/".$data['Product Units Per Case']." ($price_per_unit par unité)";
	  else
	    $str="$price par unité";

	}
	if ($data=='from')
	  return 'Prix à partir de '.$str;
	else
	  return 'Prix: '.$str;

      }
      else {

	if ( is_array($data) and isset($data['price per unit text'])  ) {
	  $str=$data['price per unit text']." $price";
	} else {
	  if ($data['Product Units Per Case']>1)
	    $str="$price/".$data['Product Units Per Case']." ($price_per_unit per unit)";
	  else
	$str="$price per unit";

	}
	if ($data=='from')
	  return 'Price from '.$str;
	else
	  return 'Price: '.$str;
	


      }



}


?>