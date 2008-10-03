<?



function _trim($string){
  $string=preg_replace('/^\s*/','',$string);
  $string=preg_replace('/\s*$/','',$string);
  return $string;
}

function mb_ucwords($str) {
  $str=_trim($str);
  if(preg_match('/^PO BOX\s+/i',$str))
     return strtoupper($str);

  $exceptions = array();
  $exceptions['Uk'] = 'UK';
  $exceptions['Usa'] = 'USA';
  $exceptions['Hp'] = 'HP';
  $exceptions['Ibm'] = 'IBM';
  $exceptions['Gb'] = 'GB';
  $exceptions['Mb'] = 'MB';
  $exceptions['Cd'] = 'CD';
  $exceptions['Dvd'] = 'DVD';
  $exceptions['Usb'] = 'USB';
  $exceptions['Mm'] = 'mm';
  $exceptions['Cm'] = 'cm';

  $exceptions['De'] = 'de';
  $exceptions['A'] = 'a';
  $exceptions['Del'] = 'del';
  $exceptions['Y'] = 'y';
  $exceptions['O'] = 'o';
  
  $exceptions['The'] = 'the';
  $exceptions['Of'] = 'of';
  $exceptions['Les'] = 'les';
  $exceptions['Las'] = 'las';
  $exceptions['Le'] = 'le';
  $exceptions['And'] = 'and';
  $exceptions['Or'] = 'or';
  $exceptions['At'] = 'at';
  $exceptions['Des'] = 'des';
$exceptions['Fao'] = 'FAO';
  $exceptions['MRS'] = 'Mrs';
  $exceptions['MR'] = 'Mr';
  $exceptions['SR'] = 'Sr';
  $exceptions['RD'] = 'Rd';

    
  if($str=='' or $str==',' or  $str=='+')
    return $str;
  //  print "$str\n";


  $separator = array("-","+",","," ");
   
  $str = mb_strtolower(trim($str),"UTF-8");
  foreach($separator as $s){
    $word = explode($s, $str);

    $return = "";
    foreach ($word as $val){
      //print "* $val\n";
      if(preg_match('/^www\.[^\s]+/i',$val)){
	$return .= $s .mb_strtolower($val,"UTF-8");
      }elseif(preg_match('/^[a-z]{2,}\.$/i',$val)){
	$return .= $s .mb_strtolower($val,"UTF-8");
      }elseif(preg_match('/^(st|Mr|Mrs|Miss|Dr|Ltd)$/i',$val)){
		$return .= $s 
	  . mb_strtoupper($val{0},"UTF-8") 
	  . mb_substr($val,1,mb_strlen($val,"UTF-8")-1,"UTF-8");
      }

elseif(preg_match('/^[^\s]+\.(com|uk|info|biz|org)$/i',$val)){
	$return .= $s .mb_strtolower($val,"UTF-8");
      }
      elseif(preg_match('/^(aa|ee|ii|oo|uu)$/i',$val)){
	$return .= $s .mb_strtoupper($val,"UTF-8");
      }elseif(preg_match('/^([a-z]\.){1,}$/i',$val)){
	$return .= $s .mb_strtoupper($val,"UTF-8");
      }elseif(preg_match('/^c\/o$/i',$val)){
	$return .= $s .'C/O';
     
       }elseif(preg_match('/^t\/a$/i',$val)){
	$return .= $s .'T/A';
     
      }elseif(preg_match('/^([^(aeoiu)]{2,3})$/i',$val)){
	$return .= $s .mb_strtoupper($val,"UTF-8");
      }elseif(preg_match('/^\(.+\)$/i',$val)){
	$text=preg_replace('/^\(|\)$/i','',$val);
	//print "*** $text\n";
	$return .= $s.'('.mb_ucwords($text).')';
      }

      elseif(mb_strlen($val,"UTF-8")>0){
	$return .= $s 
	  . mb_strtoupper($val{0},"UTF-8") 
	  . mb_substr($val,1,mb_strlen($val,"UTF-8")-1,"UTF-8");
      }

    }
    $str = mb_substr($return, 1);
  }
  
  $return{1}=mb_strtoupper($return{1},"UTF-8");
  $return=mb_substr($return, 1);

  foreach($exceptions as $find=>$replace){
    $return = preg_replace('/\s+'.$find.'\s+|\s+'.$find.'$/', ' '.$replace.' ', $return);
    
  }
  $return=_trim($return);
  //  }else
  //  $return='';
  // print $return."\n";
  return $return;
}


function prepare_mysql($string,$no_null=false){
  if(is_array($string)){
    print "Warning is array (prepare_mysql)\n";
    print_r($string);
    return 'NULL';
  }

  if($no_null)
    return "'".addslashes($string)."'";
  else
    return ($string==''?'null':"'".addslashes($string)."'");
}


function prepare_mysql_date($string,$default='NOW()'){
  if($string=='')
    return $default;
  else{
     $string=str_replace("'",'',$string);
    return "'".addslashes($string)."'";
  }
}


function is_url($url){
  if(preg_match('/^(www\.)?[a-z0-9-]+(.[a-z0-9-]+)*\.(com|uk|fr|biz|net|info|mx|jp|org)$/i',$url))
    return true;
  else
    return false;
}

?>