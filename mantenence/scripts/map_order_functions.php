<?php

function mb_unserialize($serial_str) {
$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
return unserialize($out);
} 

function parse_payment_method($method){


  $method=_trim($method);
  //  print "$method\n";
  if($method=='' or $method=='0')
    return 0;
  if(preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
    return 'Credit Card';

  //  print "$method\n";
  if(preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
    return 'Check';
  if(preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
    return 'Other';
  if(preg_match('/^(cash|casg|casn)$/i',$method))
    return 'Cash';
  if(preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
    return 'Paypal';
  if(preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
    return 'Bank Transfer';
  if(preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
    return 'Other';
  if(preg_match('/^(postal order)$/i',$method))
    return 'Other';
  if(preg_match('/^(Moneybookers)$/i',$method))
    return 'Other';


  return 'Unknown';

}








function guess_tel($raw_tel,$country_id='',$city_id=''){
  if($raw_tel=='')
    return false;
  $is_mobile=2; // 2 unknown 1 yes 0 no
  $icode='';
  $ncode='';
  $number='';
  $ext='';
  // fisrt try to see if it has an extension;
  $tel_ext=preg_split('/ext|#/i',$raw_tel);

  if(count($tel_ext)==2){
    $ext=preg_replace('/[^0-9]/','',$tel_ext[1]);
  }   
  
  $number=$tel_ext[0];
  // if (*) founf the numbers at the left could be  icodes and the number iside could be the ncode ane the number ate the rigth the numbner
  
 //  if(preg_match('/\(.*\)/i',$number,$possible_ncode)){
//     $possible_ncode=preg_replace('/\(|\)/','', $possible_ncode[0]);
//     if(preg_match('/^0*$/', $possible_ncode)){
//       // forget it
//     }else{
//       $ncode=$possible_ncode;
//     }
//     $number_parts=preg_split('/\(.*\)/i',$number);
//     $icode=$number_parts[0];
//     $number=$number_parts[1];
    
//   }
  
  

//   // remove the internatinal code if found
//   if($country_code=get_icode($country_id))
//     $icode_match="/^\+?$country_code\s*/";
//   else 
//     $icode_match='/^\+\d{1,3}\s+/';

//   if(preg_match($icode_match,$number,$a_ncode)){
//     $icode= $a_ncode[0];
//     $number=str_replace($icode,'',$number);
//   }
//   //$number=preg_replace('/\[\d*\]/','',$number);

  $icode=preg_replace('/[^0-9]/','',$icode);
  $ncode=preg_replace('/[^0-9]/','',$ncode);
  $number=preg_replace('/[^0-9]/','',$number);
  $ext=preg_replace('/[^0-9]/','',$ext);
  
  if($icode=get_icode($country_id)){
    $regex_icode="/^0{0,2}$icode/";
    //    print "$regex_icode  xxxxxxxxxxxxx\n";
    $number=preg_replace($regex_icode,'',$number);
  }
  

  // country expcific

  switch($country_id){
     
  case(30)://UK
    if(preg_match('/^0845/',$number)){
      $icode='';
      $ncode='0845';
      $number=preg_replace('/^0845/','',$number);
    }
    $number=preg_replace('/^0/','',$number);
    if(preg_match('/^7/',$number))
      $is_mobile=1;
    else
      $is_mobile=0;
    break;
  case(75)://Ireland
    if(preg_match('/^0?8(2|3|5|6|7|8|9)/',$number))
      $is_mobile=1;
    else
      $is_mobile=0;
    break;
  case(47)://Spain
  case(165)://France
    if(preg_match('/^0?6/',$number))
      $is_mobile=1;
    else
      $is_mobile=0;
    break;
  }
  
  
  $number=preg_replace('/[^\d]/','',$number);
  $telecom_data=array('icode'=>$icode,'ncode'=>$ncode,'number'=>$number,'ext'=>$ext,'is_mobile'=>$is_mobile);
  //print "$raw_tel\n";
   //print_r($telecom_data);
   // if(!($country_id==30 ))
   //  exit;
  return $telecom_data;

}

function get_icode($country_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select `Country Telephone Code` as tel_code from kbase.`Country Dimension`  where `Country Key`=%d",$country_id);
  $res=mysql_query($sql); 
  if ($row=$res->fetchRow()){
    if($row['tel_code']!='')
      return $row['tel_code'];
  }else
    return '';
}


function country_id($address_id,$default=0){
   $db =& MDB2::singleton();
   $sql=sprintf("select country_id from address_atom where address_id=%d ",$address_id);
   $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
   if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
     return $row['country_id'];
   }else
     return $default;
 

}

function is_street($string){
  if($string=='')
    return false;

  $string=_trim($string);
  // if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

  if(preg_match('/\s+rd\.?\s*$|\s+road\s*$|^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))
    return true;
   if(preg_match('/[a-z\-\#\,]{1,}\s*\d/i',$string))
    return true;

  if(preg_match('/\d.*[a-z]{1,}/i',$string))
    return true;

  

    return false;
}

function is_internal($string){
  if($string=='')
    return false;
  // if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

  if(preg_match('/lot\s*(n-)?\s*\d|suite\s*\d|shop\s*\d|apt\s*\d/i',$string))
    return true;
  else
    return false;
}




// function is_country_d2($country_d2,$country_id){
//    if($country_d2=='')
//      return false;
//       $db =& MDB2::singleton();
//   if($country_id>0)
//     $sql=sprintf("select id from list_country_d2 where (name='%s' or oname='%s') and country_id=%d",addslashes($country_d2),addslashes($country_d2),$country_id);
//   else
//     $sql=sprintf("select id from list_country_d2 where (name='%s' or oname='%s') ",addslashes($country_d2),addslashes($country_d2));

//   //    print "$sql\n";
//  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
//  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//     return true;
//   }else
//     return false;
// }

// function is_town($town,$country_id){
//    if($town=='')
//      return false;
//       $db =& MDB2::singleton();
//   if($country_id>0)
//     $sql=sprintf("select id from list_town where (name='%s' or oname='%s') and country_id=%d",addslashes($town),addslashes($town),$country_id);
//   else
//     $sql=sprintf("select id from list_town where (name='%s' or oname='%s') ",addslashes($town),addslashes($town));

//   //  print "$sql\n";
//  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
//  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//     return true;
//   }else
//     return false;
// }

// function dguess_address($address_raw_data,$defaults,$untrusted=true){

//  $db =& MDB2::singleton();
//  //print_r($address_raw_data);

//  $fix2=true;
//  $debug=true;
//    $debug=false;
//  if($debug)
//     print_r($address_raw_data);
//   if($address_raw_data['address1']=='' 
//      and $address_raw_data['address2']==''
//      and $address_raw_data['address3']=='')
//     return false;


  
//   $address1='';
//   $address2='';
//   $address3='';
//   $town_d2='';
//   $town_d1='';
//   $town='';
//   $country_d2='';
//   $country_d1='';
//   $postcode='';
//   $country='';
//   $town_d2_id=0;
//   $town_d1_id=0;
//   $town_id=0;
//   $country_d2_id=0;
//   $country_d1_id=0;
//  $country_id=0;


//  if($fix2){
//    if(preg_match('/^St. Thomas.*Virgin Islands$/i',$address_raw_data['town']))
//      $address_raw_data['country']='Virgin Islands, U.S.';
   
//  }
 

  
//   $country_d1=$address_raw_data['country_d1'];
//   if(!isset($address_raw_data['country']) or $address_raw_data['country']==''){
//     $country_id=$defaults['country_id'];
    
//   }else{// Try to guess country

//     // Common missconceptions
//     if(preg_match('/^england$|^inglaterra$/i',$address_raw_data['country'])){
//       $address_raw_data['country']='United Kingdom';
//      if($country_d1=='')
// 	$country_d1='England';
//     }else if(preg_match('/^nor.*ireland$|n\.{2}ireland/i',$address_raw_data['country'])){
//       $address_raw_data['country']='United Kingdom';
//       if($country_d1=='')
// 	$country_d1='Northen Ireland';
//     }else if(preg_match('/^r.*ireland$|^s.*ireland|^eire$/i',$address_raw_data['country'])){
//       $address_raw_data['country']='Ireland';
//     }else if(preg_match('/me.ico|m.xico/i',$address_raw_data['country'])){
//       $address_raw_data['country']='Mexico';
//     }else if(preg_match('/scotland|escocia/i',$address_raw_data['country'])){

//       $address_raw_data['country']='United Kingdom';
//       if($country_d1=='')
// 	$country_d1='Scotland';
//     }else if(preg_match('/.*\s+(w|g)ales$/i',$address_raw_data['country'])){
//       $address_raw_data['country']='United Kingdom';
//       if($country_d1=='')
// 	$country_d1='Wales';
//     }else if(preg_match('/canarias$/i',$address_raw_data['country'])){
//       $address_raw_data['country']='Spain';
//       if($country_d1=='')
//       $country_d1='Canarias';
//     }else if(preg_match('/^Channel Islands$/i',$address_raw_data['country'])){

//       if($country_d1!=''){
// 	$address_raw_data['country']=$country_d1;
// 	$country_d1='';
	
//       }else if($address_raw_data['country_d2']!=''){
// 	$address_raw_data['country']=$address_raw_data['country_d2'];
// 	$address_raw_data['country_d2']='';
	
//       } else if($address_raw_data['town']!=''){
// 	$address_raw_data['country']=$address_raw_data['town'];
// 	$address_raw_data['town']='';
	
//       }
      

      
//     }
    

//  $_p=$address_raw_data['postcode'];

//   if(preg_match('/^\s*BFPO\s*\d{1,}\s*$/i',$_p))
//     $address_raw_data['country']='UK';




//     $sql=sprintf("select country.id,name, alias from list_country as country left join list_country_alias as country_alias on (country.code=country_alias.code) where alias='%s' or country.name='%s' group by country.id ",$address_raw_data['country'],$address_raw_data['country']);
//     // print "$sql\n";



//  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
// 	   if($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
//       $country_id=$row['id'];
//       else
// 	$country_id=244;
//   }
//   // Ok the country is already guessed, wat else ok depending of the country letys gloing to try to get the orthers bits of the address




//   // pushh all address up

//   if($untrusted){


//     //Change town if misplaced
    
//     if($address_raw_data['town']=='') {

//       if(is_town($address_raw_data['address3'],$country_id) ){
// 	$address_raw_data['town']=$address_raw_data['address3'];
// 	$address_raw_data['address3']='';
//       }else if(is_town($address_raw_data['country_d2'],$country_id) ){
// 	$address_raw_data['town']=$address_raw_data['country_d2'];
// 	$address_raw_data['country_d2']='';
//       }


//     }



//     if(preg_match('/^\d[a-z]?(bis)?\s*,/',$address_raw_data['address1'])){
//       $address_raw_data['address1']=preg_replace('/\s*,\s*/',' ',$address_raw_data['address1']);
//     }
//     if(preg_match('/^\d[a-z]?(bis)?\s*,/',$address_raw_data['address2'])){
//       $address_raw_data['address2']=preg_replace('/\s*,\s*/',' ',$address_raw_data['address2']);
//     }
//     if(preg_match('/^\d[a-z]?(bis)?\s*,/',$address_raw_data['address3'])){
//       $address_raw_data['address3']=preg_replace('/\s*,\s*/',' ',$address_raw_data['address3']);
//     }
    
//     $address_raw_data['address1']=preg_replace('/,\s*$/',' ',$address_raw_data['address1']);
//     $address_raw_data['address2']=preg_replace('/,\s*$/',' ',$address_raw_data['address2']);
//     $address_raw_data['address3']=preg_replace('/,\s*$/',' ',$address_raw_data['address3']);


//     // this is going to ve dirty
//     //print_r($address_raw_data);
    
//     if(is_street($address_raw_data['address2']) and  $address_raw_data['address1']!=''  and $address_raw_data['address3']==''  ){
//       $tmp=preg_split('/\s*,\s*/i',$address_raw_data['address1']);
//       if(count($tmp)==2 and !preg_match('/^\d*$/i',$tmp[0])   and !preg_match('/^\d*$/i',$tmp[1]) ){
// 	$address_raw_data['address3']=$address_raw_data['address2'];
// 	$address_raw_data['address1']=$tmp[0];
// 	$address_raw_data['address2']=$tmp[1];


//       }

//     }
//     //  print_r($address_raw_data);

//     //print $address_raw_data['address1']."----------------\n";
//     // print $address_raw_data['address2']."----------------\n";



//     if($address_raw_data['address1']==''){ 
//       if($address_raw_data['address2']==''){
// 	// if line 1 and 2  has not data
// 	$address_raw_data['address1']=$address_raw_data['address3'];
// 	$address_raw_data['address3']='';
      

//       }else{

// 	if($address_raw_data['address3']==''){

// 	    $address_raw_data['address1']=$address_raw_data['address2'];
// 	    $address_raw_data['address2']='';
	    
// 	  }else{
// 	    $address_raw_data['address1']=$address_raw_data['address2'];
// 	    $address_raw_data['address2']=$address_raw_data['address3'];
// 	    $address_raw_data['address3']='';
// 	  }


//       }
      
//     }else if($address_raw_data['address2']==''){
//       $address_raw_data['address2']=$address_raw_data['address3'];
//       $address_raw_data['address3']='';
//     }


//   //then volter alas address

//     // print_r($address_raw_data);
//     // exit;

//   //lets do it as an experiment if the only line is 1 has data
//   // split the data in that line  to see what happens
//   if($address_raw_data['address1']!='' and $address_raw_data['address2']=='' and $address_raw_data['address3']==''){
//     $splited_address=preg_split('/\s*,\s*/i',$address_raw_data['address1']);
//     if(count($splited_address)==1){
//       $address3=$splited_address[0];
//     }else if(count($splited_address)==2){
//       // ok separeta bu on li if the sub partes are not like numbers

//       $parte_1=_trim($splited_address[1]);
//       $parte_0=_trim($splited_address[0]);
//       // print "->$parte_1<- ->$parte_0<-\n";
//       if(preg_match('/^\d*$/',$parte_0) or preg_match('/^\d*$/',$parte_1)  ){
// 	 $address3=$address_raw_data['address1'];



//       }else{
	
// 	if(preg_match('/^\d{1,}.+$/',$parte_0) or preg_match('/^.+\d{1,}$/',$parte_1)   ){
// 	  $address3=$address_raw_data['address1'];
// 	}else {
// 	  $address2=$parte_0;
// 	  $address3=$parte_1;
// 	}
//       }
//       // exit ("$address3\n");
//     }else if(count($splited_address)==3){
//       $address1=$splited_address[0];
//       $address2=$splited_address[1];
//       $address3=$splited_address[2];
//     }
      
//   }else if( $address_raw_data['address3']==''){
//     $address2=$address_raw_data['address1'];
//     $address3=$address_raw_data['address2'];

//   }else{

//     // print_r($address_raw_data);
//     $address1=$address_raw_data['address1'];
//     $address2=$address_raw_data['address2'];
//     $address3=$address_raw_data['address3'];

//   }

//   // print("a1 $address1 a2 $address2 a3 $address3 \n");


//      $town=$address_raw_data['town'];
//   $town_d2=$address_raw_data['town_d2'];
//   $town_d1=$address_raw_data['town_d1'];

//   //  print "1:$address1 2:$address2 3:$address3 t:$town \n";

//   $f_a1=($address1==''?false:true);
//   $f_a2=($address2==''?false:true);
//   $f_a3=($address2==''?false:true);



//   $f_t=($town==''?false:true);
//   $f_ta=($town_d2==''?false:true);
//   $f_td=($town_d1==''?false:true);

//   $s_a1=is_street($address1);
//   $s_a2=is_street($address2);
//   $s_a3=is_street($address3);
//   $i_a1=is_internal($address1);
//   $i_a2=is_internal($address2);
//   $i_a3=is_internal($address3);



//   // print "Street grade 1-$s_a1 2-$s_a2 3-$s_a3 \n";
//   //   print "Internal grade 1-$i_a1 2-$i_a2 3-$i_a3 \n";
//   //   print "Filled grade 1-$f_a1 2-$f_a2 3-$f_a3 \n";
//   //   exit;    
//    if(!$f_a1 and $f_a2 and $f_a3){
     
//      if($s_a2 and $i_a3){
       
//        $_a=$address3;
//        $address3=$address2;
//        $address2=$_a;
//      }
       
//    }

   
//    //   exit;

//   // super special case
//   //  if(!$f_a1 and $f_a2 and $f_a3 and )
//    //print("a1 $address1 a2 $address2 a3 $address3 \n");
//   $town_filled=false;
//   // caso 1 all filled a1,a2 and a3
//   if($f_a1 and $f_a2 and $f_a3){ // caso 1 all filled a1,a2 and a3
//     //print "AAAAAAAA\n";
//     if($s_a1 and !$s_a2 and !$s_a3){ //caso    soo  (moviing 2 )
      
//       if(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
// 	//print "AAAAAAAA\n";
// 	$town_filled=true;
// 	$town=$address3;
// 	$town_d2=$address2;
// 	$address3=$address1;
// 	$address2='';
// 	$address1='';

//       }else if(!$f_ta and !$f_td and $f_t){// caso oot
	
// 	$town_d1=$address3;
// 	$town_d2=$address2;
// 	$address3=$address1;
// 	$address2='';
// 	$address1='';

//       }else{
// 	$address3=$address1.', '.$address2.', '.$address3;
// 	$address2='';
// 	$address1='';
	  
//       }
//     }else if ((!$s_a1 and $s_a2 and !$s_a3) OR ($s_a1 and $s_a2 and !$s_a3)){ //caso    oso OR  sso  (move one)
//       //  print "HOLAAAAAAAAAAAA";
//        if($s_a1 and $s_a2 and !$f_a3 and $f_t){ 
// 	 $address3=$address2;
// 	 $address2=$address1;
// 	 $address1='';
	 
//        }elseif(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
// 	$town=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';
//       }else if(!$f_ta and !$f_td and $f_t){// caso oot
// 	$town_d2=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';
//       }else{
// 	$address3=$address2.', '.$address3;
// 	$address2=$address1;
// 	$address1='';
//       }
//     }

//   }elseif(!$f_a1 and $f_a2 and $f_a3){ // case xoo

//     //   print "1 $address1 2 $address2 3 $address3 \n";
//     if($s_a2 and   !$i_a3 and !$s_a3  ){
//       //   print "caca";
//      if(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
       
//        $town=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';
//       }else if(!$f_ta and !$f_td and $f_t){// caso oot
       
// 	$town_d2=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';

//       }else{
       
// 	$address3=$address2.', '.$address3;
// 	$address2=$address1;
// 	$address1='';
//       }


//    }



//   }

  


//   }

  

//   // exit("a1 $address1 a2 $address2 a3 $address3 \n");

//  // get country name
  
//   $sql=sprintf("select name from  list_country where id=%d",$country_id);

//  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
// 	   if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//     $country=$row['name'];
//   }


//   // take opff the name of the comntry from the poscode part
// 	   $postcode=$address_raw_data['postcode'];



//   // $regex='/\s*'.$country.'\s*/i';
//   //  $postcode=preg_replace($regex,'',$postcode);



//   // print $postcode." $regex XXXXXXXXXXXXXXXXX \n";


//   $country_d2=$address_raw_data['country_d2'];
  




//   if(preg_match('/^P\.o\.box\s+\d+$|^po\s+\d+$|^p\.o\.\s+\d+$/i',$town_d2)){

//     $po=$town_d2;
//     $town_d2='';
//     $po=preg_replace('/^P\.o\.box\s+|^po\s+|^p\.o\.\s+/i','PO BOX ',$po);
//     if($address1=='')
//       $address1=$po;
//     else
//       $address1=$po.', '.$address1;
    
//   }




//   switch($country_id){
//   case(30)://UK
//     // ok try to determine the city from aour super database of cities and towns

//     if(preg_match('/Andover.*\sHampshire/i',$town))
//       $town='Andover';

//     if($town_filled){
//       if(is_country_d2($town,30) and is_town($town_d2,30)){
// 	$country_d2=$town;	
// 	$town=$town_d2;
// 	$town_d2='';
//       }
	
//     }

   

//     if($town==''){
//     if($town_d1!='' ){
//       $town=$town_d1;
//       $town_d1='';
//     }
//     elseif($town_d2!=''){
//       $town=$town_d2;
//       $town_d2='';
//     }
//     elseif($address3!='' and ($address2!='' or $address1!='') ){
//       $town=$address3;
//       $address3='';
//     }else if($address2!='' and $address1!=''){
//       $town=$address2;
//       $address2='';
//     }

//   }







//     $postcode=preg_replace('/,?\s*scotland\s*$|united kingdom/i','',$postcode);
//     $postcode=preg_replace('/\s/','',$postcode);
//     if(preg_match('/^bfpo\s*\d/i',$postcode) )
//       $postcode=preg_replace('/bfpo/i','BFPO ',$postcode);
//     else
//       $postcode=substr($postcode,0,strlen($postcode)-3).' '.substr($postcode,-3,3);

    
//     break;
// case(78)://Italy
//   $postcode=preg_replace('/italy|italia/i','',$postcode);
//   $postcode=preg_replace('/\s/i','',$postcode);

//   if($town=='Padova'){
//     $country_d1='Veneto';
//     $country_d2='Padova';
//   }
//  if($town=='Mestre'){
//     $country_d1='Venezia';
//     $country_d2='Veneto';
//   }
 
//  if(preg_match('/Genova\s*(\- Ge)?/i',$town)){
//     $country_d1='Genoa';
//     $country_d2='Liguria';
//     $town='Genova';
//   }
 
//  if(preg_match('/Spilamberto/i',$address3) and preg_match('/Modena/i',$town)){
//     $country_d1='Emilia-Romagna';
//     $country_d2='Modena';
//     $town='Spilamberto';
//     $address3='';
//   }
 
//  if(preg_match('/Pescia/i',$address3) and preg_match('/Toscana/i',$town)){
//     $country_d1='Toscana';
//     $country_d2='Pistoia';
//     $town='Pescia';
//     $address3='';
//   }

// if( preg_match('/Villasor.*Cagliari/i',$town)){
//     $country_d1='Sardinia';
//     $country_d2='Cagliari';
//     $town='Villasor';
//   }
// if( preg_match('/Nocera Superiore/i',$town)){
//     $country_d1='Campania';
//     $country_d2='Salerno';
//     $town='Nocera Superiore';
//   }
// if( preg_match('/^Vicenza$/i',$town)){
//     $country_d1='Veneto';
//     $country_d2='Vicenza';
//     $town='Vicenza';
//   }

// if( preg_match('/^Rome$/i',$town)){
//     $country_d1='Lazio';
//     $country_d2='Rome';
//     $town='Rome';
//   }
// $postcode=_trim($postcode);
//   if(preg_match('/^\d{2}$/',$postcode))
//       $postcode='000'.$postcode;
//   if(preg_match('/^\d{3}$/',$postcode))
//       $postcode='00'.$postcode;

//     if(preg_match('/^\d{4}$/',$postcode))
//       $postcode='0'.$postcode;
//   break;
//   case(75)://Ireland

//     // print "address1: $address1\n";
//     //print "address2: $address2\n";
//     //print "address3: $address3\n";
//     //print "townarea: $town_d2\n";
//     //print "town: $town\n";
//     //    print "country_d2: $country_d2\n";
//     //      print "postcode: $postcode\n";
    
//     $postcode=_trim($postcode);
    
    


//     $country_d2=_trim($country_d2);
//     $postcode=preg_replace('/County COrK/i','',$postcode);
//     $postcode=preg_replace('/^co\.\s*|Republique of Ireland|Louth Ireland|ireland/i','',$postcode);
//     $country_d2=preg_replace('/^co\.\s*|republic of ireland|republic of|ireland/i','',$country_d2);
//     $country_d2=preg_replace('/(co|county)\s+[a-z]+$/i','',$country_d2);
//      $country_d2=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$country_d2);
//    $country_d2 =preg_replace('/(co|county)\s+[a-z]+$/i','',$country_d2);

//     $postcode=preg_replace('/\,+\s*^ireland$/i','',$postcode);
//     $postcode=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$postcode);
//     $town=preg_replace('/(co|county)\s+[a-z]+$/i','',$town);

//     if($town=='Cork')
//       $postcode='';

//     $postcode=preg_replace('/co\s*Donegal|eire|republic of ireland|rep\? of Ireland|n\/a|^ireland$|/i','',$postcode);
//  $postcode=_trim($postcode);
//  $country_d2=_trim($country_d2);
//     //print "country_d2: $country_d2\n";
//     $town=preg_replace('/\-?\s*eire|\s*\-?\s*ireland/i','',$town);
//     //exit;
//     if($country_d2=='Wesstmeath')
//       $country_d2='Westmeath';

//     if($town=='Wesstmeath' or $town=='Westmeath' ){
//       $town='';
//     }

    

//     if(is_town($town_d2,$country_id) and is_country_d2($town,$country_id)){
//       $county_d2=$town;
//       $town=$town_d2;
//       $town_d2='';

//     }
      


//     $postcode=preg_replace('/Rep.?of/i','',$postcode);
//     $postcode=str_replace(',','',$postcode);
//     $postcode=str_replace('.','',$postcode);
//     $postcode=str_replace('DUBLIN','',$postcode);
//     $postcode=str_replace('N/A','',$postcode);
//     $postcode=preg_replace('/Republic\s?of/i','',$postcode);
//     $postcode=preg_replace('/Erie/i','',$postcode);
//     $postcode=preg_replace('/county/i','',$postcode);
    
//     $postcode=preg_replace('/^co/i','County ',$postcode);
//     $postcode=preg_replace('/\s{2,}/',' ',$postcode);
//     $postcode=_trim($postcode);

//     $valid_postalcodes=array('D1','D2','D3','D4','D5','D6','D6w','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D20','D22','D24');

//     if($postcode!=''){
//     $sql="select name from list_country_d2 where  country_id=75 and name like '%$postcode%'";
//     //print "$sql\n";
//     $res=mysql_query($sql); 
//     if ($row=$res->fetchRow()){
//       $postcode='';
//       $country_d2=$row['name'];

//     }    
//     }
//     // delete unganted  postcodes
//     if(preg_match('/COMAYORepublicof|COGALWAY|RepublicofTIPPERARY|Republiqueof|NCW|eire|WD3|123|CoKerry,EIRE|COCORK|COOFFALY|WICKLOW|CoKerry/i',$postcode))
//       $postcode='';

//     if(preg_match('/^co\.?\s+|^country\s+/i',$postcode)){
//       $postcode='';
//       if($country_d2=='')
// 	$country_d2=$postcode;
//       $postcode='';
//     }

//     $town=preg_replace('/\s+ireland\s*/i','',$town);
//     $country_d2=preg_replace('/\s+ireland\s*/i','',$country_d2);
	
    
//     $town=preg_replace('/co\.\s*/i','Co ',$town);
//     $town=preg_replace('/county\s+/i','Co ',$town);

//     // print "$town";
//     $split_town=preg_split('/\s*-\s*|\s*,\s*/i',$town);
//     if(count($split_town)==2){
//       if(preg_match('/^co\s+/i',$split_town[1])){
// 	 if($country_d2=='')
// 	   $country_d2=$split_town[1];
// 	 $town=$split_town[0];
//       }

//     }


//     if(preg_match('/^co\s+/i' ,$town)){
//       if($country_d2=='')
// 	$country_d2=$town;
//       $town=preg_replace('/^co\s+/i','',$town);
//     }
      
//     $country_d2=preg_replace('/co\.?\s+/i','',$country_d2);
//     $country_d2=preg_replace('/county\s+/i','',$country_d2);
    
//     if(preg_match('/\s*Cork\sCity\s*/i',$town_d2)){
//       $town_d2=='';
//       if($town=='')
// 	$town='Cock';
//     }
    
//     if(preg_match('/^dublin\s+\d+$/i',$town_d2)){

//       if($town=='')
// 	$town='Dublin';
//       if($town_d1=='')
// 	$town_d1=preg_replace('/dublin\s+/i','',$town_d2);
//       if($postcode==preg_replace('/dublin\s+/i','',$town_d2))
// 	$postcode='';
//       $town_d2=='';
//     }


//     if(preg_match('/^dublin\s*\d{1,2}$/i',$postcode)){
//       $postcode=preg_replace('/^dublin\s*/i','',$postcode);
//     }
//     $town=_trim($town);
    
//  //  print "$town +++++++++++++++\n";
//     $town=preg_replace('/\s*,?\s*Leinster/i','',$town);
//     if(preg_match('/^dublin\s*6w$/i',$town)){
//       $postcode='D6W';
//       $town='Dublin';
//     }

//     //  print "$town +++++++++++++++\n";
//     if(preg_match('/^dublin\s*\-\s*\d$/i',$town)){
//       $postcode=preg_replace('/^dublin\s*\-\s*/i','',$town);
//       $town='Dublin';
//     }

//      if(preg_match('/^dublin\s*d?\d{1,2}$/i',$town)){
//        $postcode=preg_replace('/^dublin\s*/i','',$town);
//        $town='Dublin';
//     }
     
//      if(is_numeric($postcode))
//        $postcode='D'.$postcode;


//       if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	$town=$address3;
// 	$address3='';
//       }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }
//       }
//       $country_d2=mb_ucwords($country_d2);

//       $postcode=str_replace('-','',$postcode);
//       $postcode=preg_replace('/MUNSTER|County RK/i','',$postcode);
//       $postcode=_trim($postcode);
//       break; 

//   case(89)://Canada
//     $postcode=preg_replace('/\s*canada\s*/i','',$postcode);

//     if($country_d2!='' and $country_d1==''){
//       $country_d1=$country_d2;
//       $country_d2='';
//     }
//     break;
//   case(208)://Czech Republic
//      $postcode=preg_replace('/\s*Czech Republic\s*/i','',$postcode);
//      $postcode=preg_replace('/\s*/i','',$postcode);
//     break;
// case(108)://Cypruss
//        $postcode=preg_replace('/\s*cyprus\s*/i','',$postcode);

//        $postcode=preg_replace('/^cy\-?/i','',$postcode);

//        if($town=='Lefkosia (Nicosia)')
// 	 $town='Nicosia';
//        if($town=='Limassol City Centre')
// 	 $town='Limassol';
       
//         if($town=='Cyprus')
// 	 $town='';

//       if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	$town=$address3;
// 	$address3='';
//       }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }
//       }

//        break;
//   case(240):
//     $town=preg_replace('/\,?\s*Guernsey Islands$/i','',$town);
//      $town=preg_replace('/\,?\s*Guernsey$/i','',$town);
//      $town=preg_replace('/\,?\s*Channel Islands$/i','',$town);
//      $town=preg_replace('/\,?\s*CI$/i','',$town);
//      $town=preg_replace('/\,?\s*C.I.$/i','',$town);

//      if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	if(!preg_match('/^rue\s/i',$address3)){
// 	$town=$address3;
// 	$address3=$address2;
// 	$address2='';
// 	}
// 	  }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }

      

      
//      }
     




//     break;
//   case(104):// Greece
//     $postcode=preg_replace('/greece/i','',$postcode);

//     $postcode=preg_replace('/^(GK|T\.?k\.?)/i','',$postcode);
//     $postcode=preg_replace('/\s/i','',$postcode);
//     $postcode=_trim($postcode);

//     if(preg_match('/^(Attica|Ionian Islands)$/i',$town))
//       $town='';
// if($country_d1=='Attoka'){
//       $country_d1='Attica';

//     }
//     if($town=='Athens')
//       $country_d1='Attica';
// if($town=='Salamina')
//       $country_d1='Attica';
//  if($town=='Corfu'){
//    $town='';
//    $country_d1='Ionian Islands';
//    $country_d2='Corfu';
//  }
//     if($town=='Kefalonia')
//       $country_d1='Ionian Islands';
//     if($town=='Thessaloniki')
//       $country_d1='Central Macedonia';

//     if($town=='Xania - Krete'){
//       $country_d1='Crete';
//       $town='Xania';
//     }
//     if($town=='Salamina - Tsami'){
//       $country_d1='Attica';
//       $town='Salamina';
// 	if($town_d2=='')
// 	  $town_d2='Tsami';
//     }


//     break;

//   case(229)://USA
//   if($country_d2!='' and $country_d1==''){
//       $country_d1=$country_d2;
//       $country_d2='';
//     }

//     $town=preg_replace('/Lousiana/i','Louisiana',$town);
    
//     $country_d1=_trim($country_d1);
//     if(preg_match('/^[a-z]\s*[a-z]$/i',$country_d1))
//       $country_d1=preg_replace('/\s/','',$country_d1);
    
//     $postcode=_trim($postcode);





//     $postcode=preg_replace('/united states of america/i','',$postcode);
    

//     $postcode=preg_replace('/\s*u\s*s\s*a\s*|^United States\s+|United Stated|usa|^united states$|^united states of america$|^america$/i','',$postcode);
//     $postcode=_trim($postcode);

//     if($country_d1==''){
//       $regex='/\s*\-?\s*[a-z]{2}\.?\s*\-?\s*/i';
//       if(preg_match($regex,$postcode,$match)){
// 	$country_d1=preg_replace('/[^a-z]/i','',$match[0]);
// 	$postcode=preg_replace($regex,'',$postcode);
//       }
//       $regex='/\([a-z]{2}\)/i';
//       if(preg_match($regex,$town,$match)){
// 	$country_d1=preg_replace('/[^a-z]/i','',$match[0]);
// 	$town=preg_replace($regex,'',$town);
//       }
//       $regex='/\s{1,}\-?\s*[a-z]{2}\.?$/i';
//       if(preg_match($regex,$town,$match)){
// 	$country_d1=preg_replace('/[^a-z]/i','',$match[0]);
// 	$town=preg_replace($regex,'',$town);
//       }


//       if(is_country_d1($town,229) and $town_d2!=''){
// 	$country_d1=$town;
// 	$town=$town_d2;
// 	$town_d2='';
	
//       }

//     }


//     //   print "$postcode ******** ";
//     if($postcode=='' and preg_match('/\s*\d{4,5}\s*/',$town,$match)){
//        $postcode=trim(trim($match[0]));
//        $town=_trim(preg_replace('/\s*\d{4,5}\s*/','',$town));
//     }

//     $town=preg_replace('/\s*\-\s*$/','',$town);

//     $town_split=preg_split('/\s*\-\s*|\s*,\s*/',$town);

//     $country_d1=_trim($country_d1);

//     if(count($town_split)==2 and is_country_d1($town_split[1],229)){

//       $country_d1=$town_split[1];
//       $town=$town_split[0];

      

//     }
    


//     if($country_d1=='N Y')
//       $country_d1='New York';

//     $states=array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
//     if(strlen($country_d1)==2){
//       if (array_key_exists(strtoupper($country_d1), $states)) {
// 	$country_d1=$states[strtoupper($country_d1)];
//       }
//     }
    
//     if($country_d1==$country_d2)
//       $country_d2='';
    
//     if($town_d1=='Brooklyn' and $town=='New York'){
//       $country_d1='New York';
//     }
//     $postcode=_trim($postcode);
//     if(preg_match('/^d{4}$/',$postcode))
//        $postcode='0'.$postcode;
       
//     break;
//  case(105)://Croatia
//     $postcode=_trim($postcode);
//    $postcode=preg_replace('/croatia/i','',$postcode);
//     $postcode=preg_replace('/^hr-?/i','',$postcode);
//      $postcode=_trim($postcode);
//    break;
//  case(160)://Portugal
//    $postcode=_trim($postcode);
//    $postcode=preg_replace('/portugal/i','',$postcode);
//    $town=preg_replace('/\-?\s*portugal/i','',$town);


//    if($postcode=='' and preg_match('/\s*\d{4}\s*/',$town,$match)){
//        $postcode=trim(trim($match[0]));
//        $town=_trim(preg_replace('/\s*\d{4}\s*/','',$town));
//     }


//    //   if(preg_match('/algarve/i'$town))


//    if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	$town=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';
//       }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }
//       }




//     break;
//  case(21)://Belgium
//   $postcode=_trim($postcode);
//   $postcode=preg_replace('/belgium/i','',$postcode);
//   $postcode=preg_replace('/^b\-?/i','',$postcode);
//   $postcode=_trim($postcode);
//   $t=preg_split('/\s*,\s*/',$town);
//   if(count($t)==2){
//     if(is_country_d1($t[1],$country_id)){
//       $country_d1=$t[1];
//       $town=$t[0];
//     }


//   }

//   $town=_trim($town);
//   if(is_country_d1($town,$country_d1) and $country_d1==''  and ($address2!='' and $address3!='') ){
//    $country_d1=$town;
//    $town='';

//  }
//   if($town=='West Vlaanderen')
//     $town=='West-Vlaanderen';

//   if(is_country_d1($town,$country_d1) and $country_d1==''  and $town_d2!=''  ){
//    $country_d1=$town_d2;
//    $town_d2='';

//  }




//   break;


//   case(80)://Austria
//   $postcode=_trim($postcode);
//   $postcode=preg_replace('/a\-?/i','',$postcode);
//   $town=_trim($town);
//   if(is_country_d1($town,$country_id) and $country_d1==''  and ($address2!='' and $address3!='') ){
//    $country_d1=$town;
//    $town='';

//  }
//  if(is_country_d1($town,$country_d1) and $country_d1==''  and $town_d2!=''  ){
//    $country_d1=$town_d2;
//    $town_d2='';

//  }




//     break;
// case(15)://Australia
//  $postcode=preg_replace('/\s*australia\s*/i','',$postcode);
//   $regex='/\(QLD\)/i';
//   if(preg_match($regex,$town)){
//     $country_d1='Queensland';
//     $town=preg_replace($regex,'',$town);
//   }
//   $regex='/, Western Australia/i';
//   if(preg_match($regex,$town)){
//     $country_d1='Western Australia';
//     $town=preg_replace($regex,'',$town);
//   }

//   if($country_d2='' and $country_d1=='' ){
//     $country_d1=$country_d2;
//     $country_d2='';
//   }
    



//  $town=_trim($town);

//   if(is_country_d1($town,15) and $town_d2!=''){
// 	$country_d1=$town;
// 	$town=$town_d2;
// 	$town_d2='';
	
//       }


//   if(is_country_d1($town,15) and $country_d1==''  and ($address2!='' and $address3!='') ){
//    $country_d1=$town;
//    $town='';

//  }

//      if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	$town=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';
//       }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }
//       }







//   break;
//    case(47)://Spain
//  if(preg_match('/Majorca/i',$town)){
//    $country_d2='Islas Baleares';
//    $country_d1='Islas Baleares';
//    $town='';
//  }
// if(preg_match('/Balearic Islands|Balearic Island/i',$country_d1))
//    $country_d1='Balearic Islands';
//  if(preg_match('/Balearic Islands|Balearic Island/i',$country_d2))
//    $country_d2='Balearic Islands';




//  if(preg_match('/Baleares/i',$address3) and preg_match('/Palma de Mallorca/i',$address2)){
//    $town='Palma de Mallorca';
//    $address3='';
//    $address2='';
//    $country_d1='Balearic Islands';
// }




//      if(preg_match('/Zugena - Provincia Almeria/i',$town)){
// 	 $country_d2='Almeria';
// 	 $town='Zugena';
//        }
//  if(preg_match('/Hinojares - Juen/i',$town)){
// 	 $country_d2='Jaen';
// 	 $town='Hinojares';
//        }


//      if(preg_match('/Mijas Costa, Malaga/i',$town)){
// 	 $country_d2='Malaga';
// 	 $town='Mijas Costa';
//        }
// 	 if(preg_match('/Calvia - Mallorca/i',$town)){
// 	 $town='Calvia';
// 	 $country_d1='Balearic Islands';
//        } 

// 	 if(preg_match('/Ciutadella - Menorca/i',$town)){
// 	 $town='Ciutadella';
// 	 $country_d1='Balearic Islands';
//        } 
//  if(preg_match('/Sax\s*(Alicante)/i',$town)){
// 	 $town='Sax';
// 	 $country_d2='Alicante';
//        } 


//      if(preg_match('/malaga/i',$town)){
//        if(preg_match('/Marbella/i',$address3)){
// 	 $address3='';
// 	 $town='Marbella';
//        }

	 

//      }

//      $postcode=_trim($postcode);
//      $postcode=preg_replace('/spain/i','',$postcode);

     
//      if($postcode=='' and preg_match('/\s*\d{4,5}\s*/',$town,$match)){
//        $postcode=_trim($match[0]);
//        $town=_trim(preg_replace('/\s*\d{4,5}\s*/','',$town));
//      }

    


//     if(preg_match('/^\d{4}$/',$postcode))
//       $postcode='0'.$postcode;

//     $country_d1=_trim(preg_replace('/^Adaluc.a$/i','Andalusia',_trim($country_d1)));

//     $town=_trim($town);

//     if(preg_match('/El Cucador/i',$town)){
// 	 $town_d2='El Cucador';
// 	 $town='Zurgena';
// 	 $country_d2='Almeria';
// 	 $country_d1='Andalusia';
// 	 $postcode='04661';
// 	 if($address2=='Cepsa Garage (Zugena)')
// 	   $address2='';
//     }
//  if(preg_match('/^Arona$/i',$town)){
// 	 $country_d2='Santa Cruz de Tenerife';
// 	 $country_d1='Islas Canarias';

//     }
//  if(preg_match('/^Ceuta$/i',$town)){

// 	 $country_d1='Ceuta';

//     }




//     break;
//   case(126)://Malta
//     $postcode=preg_replace('/malta/i','',$postcode);
//     $postcode=_trim($postcode);
//     $postcode=preg_replace('/\s/i','',$postcode);

//     if(preg_match('/[a-z]*/i',$postcode,$ap) and preg_match('/[0-9]{1,}/i',$postcode,$xxx))
//       $postcode=$ap[0].' '.$xxx[0];

//     $town=preg_replace('/-?\s*malta|gozo\s*\-?/i','',$town);

//       $town=_trim($town);

//     break;
//  case(110)://Latvia
//     $postcode=_trim($postcode);
//     $postcode=preg_replace('/Latvia/i','',$postcode);
//     $postcode=preg_replace('/LV\s*\-?\s*/i','',$postcode);
//     $town=_trim($town);
//     $postcode=_trim($postcode);
//     if(preg_match('/^\d{4}$/',$postcode))
//       $postcode='LV-'.$postcode;
//     break;

//   case(117)://Luxembourg
//     $postcode=_trim($postcode);
//     $postcode=preg_replace('/Luxembourg/i','',$postcode);
//     $postcode=preg_replace('/L\s*\-?\s*/i','',$postcode);
//     $town=preg_replace('/\-?\s*Luxembourg/i','',$town);
//     if($town=='')
//       $town='Luxembourg';
//     $town=_trim($town);
//     $postcode=_trim($postcode);
//     if(preg_match('/^\d{4}$/',$postcode))
//       $postcode='L-'.$postcode;
//     break;
//   case(165)://France
//     $postcode=_trim($postcode);
//     $postcode=preg_replace('/FRANCE|french republic/i','',$postcode);
//     if($postcode=='' and preg_match('/\s*\d{4,5}\s*/',$town,$match)){
//        $postcode=trim(trim($match[0]));
//        $town=preg_replace('/\s*\d{4,5}\s*/','',$town);
//     }

//     if(preg_match('/Digne les Bains|Dignes les Bains/i',$town))
//       $town='Digne-les-Bains';

//      $town=preg_replace('/,\s*france\s*$/i','',$town);

//     if($town=='St Cristophe - Charante'){
//       $town='St Cristophe';
//       $country_d2='Charente';
//       $country_d1='Poitou-Charentes';
//     }
//  if($town=='Cauro - Corse Du Sud'){
//       $town='Cauro';
//       $country_d2='Corse Du Sud';
//       $country_d1='Corse';
//     }

//     if($town=='Charente'){
//       $town='';
//        $country_d2='Charente';
//        $country_d1='Poitou-Charentes';
//     }

//   if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	$town=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';
//       }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }
//       }
//     $postcode=_trim($postcode);
//     if(preg_match('/^\d{4}$/',$postcode))
//       $postcode='0'.$postcode;
//     break;

//   case(196)://Switzerland
//     $postcode=_trim($postcode);
//     $postcode=preg_replace('/Switzerland/i','',$postcode);

//     if(preg_match('/^\d{4}\s+/',$town,$match)){
//       if($postcode=='' or $postcode==trim($match[0])){
// 	$postcode=trim($match[0]);
// 	$town=preg_replace('/^\d{4}\s+/','',$town);
//       }
//     }
    
//     $postcode=preg_replace('/^CH\-/i','',$postcode);
//     break;
// case(193)://Findland
//   $postcode=_trim($postcode);
//   $postcode=preg_replace('/findland|finland/i','',$postcode);
//  $postcode=preg_replace('/^fi\s*\-?\s*/i','',$postcode);

//  if($address3=='Klaukkala' and $town=='Nurmijarvi'){
//    $address3='';
//    $town='Klaukkala';
//      }
//  if(preg_match('/^\d{3}$/',$postcode))
//       $postcode='00'.$postcode;

//     if(preg_match('/^\d{4}$/',$postcode))
//       $postcode='0'.$postcode;

//     break;
// case(242)://Isle of man
//  if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	$town=$address3;
// 	$address3='';
//       }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }
      
//     }





  
//     break;


// case(241)://Jersey

//   $town=preg_replace('/^jersey$|^jersey\s*c\.?i\.?$/i','',$town);
//   $town=preg_replace('/\,?\s*Channel Islands$/i','',$town);
//   $town=preg_replace('/\,?\s*CI$/i','',$town);
//   $town=preg_replace('/\,?\s*C.I.$/i','',$town);
//   $town=preg_replace('/\-?\s*jersey$/i','',$town);
//   $country_d2=preg_replace('/\-?\s*jersey$|Jersy Channel Isles/i','',$country_d2);
//   //  print "1$address1 2$address2 3$address3\n";
//  if($town==''){
//       if($town_d1!='' ){
// 	$town=$town_d1;
// 	$town_d1='';
//       }
//       elseif($town_d2!=''){
// 	$town=$town_d2;
// 	$town_d2='';
//       }
//       elseif($address3!='' and ($address2!='' or $address1!='') ){
// 	$town=$address3;
// 	$address3=$address2;
// 	$address2=$address1;
// 	$address1='';
//       }else if($address2!='' and $address1!=''){
// 	$town=$address2;
// 	$address2='';
//       }
//       }






//     $town=_trim($town);
//      if($town_d2=='' and  preg_match('/\w+\.?\s*St\.? Helier$/i',$town) ){
//        $town_d2=_trim( preg_replace('/St\.? Helier$/i','',$town));
//        $town='St Helier';
//   }

//      $town_d2=preg_replace('/\./','',$town_d2);
//      $town=preg_replace('/^St\s{1,}/','St. ',$town);
  
//     break;

// case(171)://Sweden
//   $postcode=_trim($postcode);
//   $postcode=preg_replace('/sweden/i','',$postcode);

//   $postcode=preg_replace('/^SE\-?/i','',$postcode);
//   if($town=='Malmo')
//     $town='Malmö';
//   if($country_d2=='Sweden')
//     $country_d2='';
//   if(preg_match('/Skaraborg/i',$town))
//     $town='';
  
//   $postcode=preg_replace('/\s/','',$postcode);

//   if(is_country_d1($town,171) and   $address1='' and $address2!='' and $address3!='' ){
//     $country_d1=$town;
//     $address3=$address2;
//     $address2='';
//   }
//  if(is_country_d1($town,171) and   $address1!='' and $address2!='' and $address3!='' ){
//     $country_d1=$town;
//     $address3=$address2;
//     $address2=$address1;
//     $address1='';
//   }

//  if($country_d2!='' and $contry_d1==''){
//    $country_d1=$country_d2;
//    $country_d2='';
//  }

//  $postcode=preg_replace('/\s/','',$postcode);

//  break;
//   case(149)://Norway
//       $postcode=_trim($postcode);
//       $postcode=preg_replace('/norway/i','',$postcode);

//     if(preg_match('/^no.\d+$/i',$town)){
//       if($postcode==''){
// 	$postcode=$town;
// 	$town='';
//       }
//     }
//     $postcode=preg_replace('/^NO\s*\-?\s*/i','',$postcode);

//     $postcode=preg_replace('/^N\-/i','',$postcode);
//     if(preg_match('/^\d{3}$/',$postcode))
//       $postcode='0'.$postcode;


//     break; 
//   case(2)://Netherlands
//     $town=preg_replace('/Noord Brabant/i','Noord-Brabant',$town);
//     $country_d1=preg_replace('/Noord Brabant/i','Noord-Brabant',$country_d1);
//     $country_d2=preg_replace('/Noord Brabant/i','Noord-Brabant',$country_d2);
//  $town=preg_replace('/Zuid Holland/i','Zuid-Holland',$town);
//     $country_d1=preg_replace('/Zuid Holland/i','Zuid-Holland',$country_d1);
//     $country_d2=preg_replace('/Zuid Holland/i','Zuid-Holland',$country_d2);
//  $town=preg_replace('/Noord Holland/i','Noord-Holland',$town);
//     $country_d1=preg_replace('/Noord Holland/i','Noord-Holland',$country_d1);
//     $country_d2=preg_replace('/Noord Holland/i','Noord-Holland',$country_d2);
//  $town=preg_replace('/Gerderland/i','Gelderland',$town);


//  $postcode=_trim($postcode);
//  $postcode=preg_replace('/Netherlands|holland/i','',$postcode);

//  if($postcode==''){
//    preg_match('/\s*\d{4,6}\s*[a-z]{2}\s*/i',$town,$match2);
//    $postcode=_trim($match2[0]);
//  }
//  $postcode=strtoupper($postcode);
//  $postcode=preg_replace('/\s/','',$postcode);
//  if(preg_match('/^\d{4}[a-z]{2}$/i',$postcode)){
//    $town=str_replace($postcode,'',$town);
//    $town=str_replace(strtolower($postcode),'',$town);
//    $_postcode=substr($postcode,0,4).' '.substr($postcode,4,2);
//    $postcode=$_postcode;
//    $town=str_replace($postcode,'',$town);
//    $town=str_replace(strtolower($postcode),'',$town);

//  }
//  $town=_trim($town);
//   if(is_country_d1($address3,2) and $country_d1=='' and $town==''   and ($address1!='' and $address2!='') ){
//    $country_d1=$address3;
//    $address3='';

//  }

//   if(is_country_d1($town,2) and $country_d1=='' and (($address1!='' and $address2!='') or ($address2!='' and $address3!='') or ($address1!='' and $address3!='')  )   ){
//    $country_d1=$town;
//    $town='';

//  }
   

//  if($town=='NH'){
//    $country_d1='North Holland';
//     $town='';
//  }

//  if($town=='Zuid Holland'){
//     $country_d1='Zuid Holland';
//     $town='';
//  }
//  similar_text($country_d1,$country_d2,$w);
//  if($w>90){
//    $country_d2='';
//  }

//  if($country_d1=='' and $country_d2!=''){
//    $country_d1=$country_d2;
//    $country_d2='';
//  }

//  if(preg_match('/Zuid.Holland|ZuidHolland/i',$country_d1))
//    $country_d1='Zuid Holland';


//  if($town==''){
//    if($town_d1!='' ){
//      $town=$town_d1;
//      $town_d1='';
//    }
//    elseif($town_d2!=''){
//      $town=$town_d2;
//      $town_d2='';
//    }
//    elseif($address3!='' and ($address2!='' or $address1!='') ){
//      $town=$address3;
//      $address3=$address2;
//      $address2=$address1;
//      $address1='';
//    }else if($address2!='' and $address1!=''){
//      $town=$address2;
//      $address2='';
//      $address3=$address1;
//      $address1='';
//    }
//  }



//  $town_split=preg_split('/\s*\-\s*|\s*,\s*/',$town);
//  if(count($town_split)==2 and is_country_d1($town_split[1],2)){
//    $country_d1=$town_split[1];
//    $town=$town_split[0];
//  }
 
//  if($address1!='' and $address2=='' and $address3==''){
//    $address3=$address1;
//    $address1='';
//  }




//     break; 


//   case(177):// Germany
//      $postcode=_trim($postcode);
//  $postcode=preg_replace('/germany/i','',$postcode);
//     if($country_d2!='' and $country_d1==''){
//       $country_d1=$country_d2;
//       $country_d2='';
//     }
      

//     $town=preg_replace('/NRW\s*$/i','',$town);


//     if(preg_match('/^berlin$/i',$town))
//       $country_d1='Berlin';
//        if(preg_match('/^Hamburg$/i',$town))
//       $country_d1='Hamburg';
//        if(preg_match('/^Bremen$/i',$town))
//       $country_d1='Bremen';

//        if(preg_match('/^Nuernberg$/i',$town))
//       $town='Nürnberg';
    
//     if(preg_match('/^Osnabruek$/i',$town)){
//    $country_d1='Niedersachsen';
//    $town='Osnabrück';
//  }
//    if(preg_match('/^bavaria$/i',$country_d1))
//       $country_d1='Bayern';


//     $regex='/^\s*\d{5}\s+|\s+\d{5}\s*$/';
//     if(preg_match($regex,$town,$match)){
//       if($postcode=='')$postcode=trim($match[0]);
//       $town=preg_replace($regex,'',$town);
//     }


//     if($country_d1==''){
//       $country_d1=get_country_d1($town,177);
      

//     }



//     break;
//   case(201)://Denmark
//    // FIx postcode in town
//        $postcode=_trim($postcode);
//  $postcode=preg_replace('/denmark|Demnark/i','',$postcode);
// $postcode=preg_replace('/^dk\s*\-?\s*/i','',$postcode);
//  $town=_trim($town);

//    if($postcode=='' and preg_match('/^\d{4}\s+/',$town,$match)){
//      $postcode=trim($match[0]);
//      $town=preg_replace('/^\d{4}\s+/','',$town);
//    }

//     $regex='/\s*2610 Rodovre\s*/i';
//     if(preg_match($regex,$town,$match)){
//       $town='Rodovre';
//       $postcode='2610';
//     }
//  $regex='/KBH K|Kobenhavn/i';
//     if(preg_match($regex,$town,$match)){
//       $town='Kobenhavn';
//     }
//  $regex='/Copenhagen/i';
//     if(preg_match($regex,$town,$match)){
//       $town='Copenhagen';
//     }
//   $regex='/Aarhus C/i';
//     if(preg_match($regex,$town,$match)){
//       $town_d2='Aarhus C';
//       $town='Aarhus';
//     }


//      $regex='/Odense\s*,?\s*/i';
//     if(preg_match($regex,$town,$match)){
//       $town='Odense';
//     }
//      $regex='/\s*Odense\s*/i';
//     if(preg_match($regex,$address3,$match)){
//       $address3='';
//       $town='Odense';
//     }

//     $postcode=_trim($postcode);
//     if(preg_match('/^\d{4}$/',$postcode)){
//       $postcode='DK-'.$postcode;
//     }
//     if(preg_match('/^KLD$/i',$address3))
//        $address3='';
  
//     if(preg_match('/^DK\- 7470 Karup J$/i',$address3)){
//       $address3='';
//       $postcode='DK-7470';
//       $town='Karup J';
//     }
      
          
//     if(preg_match('/Sjalland|Zealand|Sjælland|Sealand/i',$country_d2))
//       $country_d2='';
    

       
//     if(preg_match('/Sjalland|Zealand/i',$town))
//       $town='';

       
// if($address3=='' and $address2!='' and  $address1=='' ){
//      $address3=$address2;
//      $address2=$address1;

//    }


// if($address3=='' and $address2!='' and  $address1!='' ){
//      $address3=$address2;
//      $address2=$address1;
//      $address1='';
//    }

//  if($town==''){
//    if($town_d1!='' ){
//      $town=$town_d1;
//      $town_d1='';
//    }
//    elseif($town_d2!=''){
//      $town=$town_d2;
//      $town_d2='';
//    }
//    elseif($address3!='' and ($address2!='' or $address1!='') ){
//      $town=$address3;
//      $address3=$address2;
//      $address2=$address1;
//      $address1='';
//    }else if($address2!='' and $address1!=''){
//      $town=$address2;
//      $address2='';
//      $address3=$address1;
//      $address1='';
//    }
//  }
    





//     break; 
//   default:
//     $postcode=$address_raw_data['postcode'];
//     $regex='/\s*'.$country.'\s*/i';
//     $postcode=preg_replace($regex,'',$postcode);
    
//   }


// if($address3=='' and $address2!='' and  $address1=='' ){
//      $address3=$address2;
//      $address2=$address1;

//    }


// if($address3=='' and $address2!='' and  $address1!='' ){
//      $address3=$address2;
//      $address2=$address1;
//      $address1='';
//    }

//  if($town==''){
//    if($town_d1!='' ){
//      $town=$town_d1;
//      $town_d1='';
//    }
//    elseif($town_d2!=''){
//      $town=$town_d2;
//      $town_d2='';
//    }
//    elseif($address3!='' and ($address2!='' or $address1!='') ){
//      $town=$address3;
//      $address3=$address2;
//      $address2=$address1;
//      $address1='';
//    }else if($address2!='' and $address1!=''){
//      $town=$address2;
//      $address2='';
//      $address3=$address1;
//      $address1='';
//    }
//  }
    
  


//   // Country ids

//  $sql=sprintf("select id  from list_country_d1 where (name='%s' or oname='%s') and country_id=%d",addslashes($country_d1),addslashes($country_d1),$country_id);
//     //  print "$sql\n";
//     $res=mysql_query($sql); 
//     if ($row=$res->fetchRow())
//       $country_d1_id=$row['id'];
    
//     $sql=sprintf("select id,country_d1_id from list_country_d2 where (name='%s' or oname='%s') and country_id=%d",addslashes($country_d2),addslashes($country_d2),$country_id);
//     $res=mysql_query($sql); 
    
//     if ($row=$res->fetchRow()){
// 	$country_d2_id=$row['id'];
// 	if($res->numRows()==1){
// 	  $country_d1_id=$row['country_d1_id'];
// 	}
	
//     }
//     else
//       $country_d2_id=0;


//     $sql=sprintf("select id,country_d2_id,country_d1_id from list_town where (name='%s' or oname='%s') and country_id=%d",addslashes($town),addslashes($town),$country_id);

//     $res=mysql_query($sql); 
//     if($res->numRows()==1){
      
//     if ($row=$res->fetchRow()){
// 	$town_id=$row['id'];
// 	if($res->numRows()==1){
// 	  if($country_d2_id==0)
// 	    $country_d2_id=$row['country_d2_id'];
// 	  if($country_d1_id==0)
// 	    $country_d1_id=$row['country_d1_id'];
// 	}
	
//     }
//     }
//     else
//       $town_id=0;




//   //=------------------------


















//   if(preg_match('/\d+\s*\-\s*\d+/',$address3)){
//     $address3=preg_replace('/\s*\-\s*/','-',$address3);
//   }
//    if(preg_match('/\d+\s*\-\s*\d+/',$address2)){
//      $address2=preg_replace('/\s*\-\s*/','-',$address2);
//   }
//  $address1=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$address1);
//   $address2=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$address2);
//   $address3=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$address3);
//   $address3=  preg_replace('/^p o box\s+/i','PO BOX ',$address3);
//  $address3=  preg_replace('/^NULL$/i','',$address3);

//   $address1=preg_replace('/\s{2,}/',' ',$address1);
//   $address2=preg_replace('/\s{2,}/',' ',$address2);
//   $address3=preg_replace('/\s{2,}/',' ',$address3);
//   $town=preg_replace('/\s{2,}/',' ',$town);
//   $town_d1=preg_replace('/\s{2,}/',' ',$town_d1);
//   $town_d2=preg_replace('/\s{2,}/',' ',$town_d2);
//   $town=preg_replace('/(\,|\-)$\s*/','',$town);
  
//   $address_data=array(
// 		      'internal_address'=>_trim($address1),
// 		      'building_address'=>_trim($address2),
// 		      'street_address'=>_trim($address3),
// 		      'town_d2'=>_trim($town_d2),
// 		      'town_d1'=>_trim($town_d1),
// 		      'town'=>_trim($town),
// 		      'country_d2'=>_trim($country_d2),
// 		      'country_d1'=>_trim($country_d1),
// 		      'postcode'=>_trim($postcode),
// 		      'country'=>_trim($country),
// 		      'town_d2_id'=>$town_d2_id,
// 		      'town_d1_id'=>$town_d1_id,
// 		      'town_id'=>$town_id,
// 		      'country_d2_id'=>$country_d2_id,
// 		      'country_d1_id'=>$country_d1_id,
// 		      'country_id'=>$country_id,

// 		      );

  

//   // print_r($address_data);


//   if($country_id==244){
//      print_r($address_data);
//     exit("Unknown country");
//   }
  
//   if($debug){
//    print_r($address_data);
//    exit;
//   }
//   // print_r($address_data);
//      return $address_data;

// }


// function display_full_address($address_id,$separator="<br/>"){
//   //  include ('locale.php');

//   if(!is_numeric($address_id))
//     return false;

//   $address_data=get_address_data($address_id);
//   if(!$address_data)
//     return false;
  

//   $header_address=($address_data['internal_address']!=''?$address_data['internal_address'].$separator:'').($address_data['building_address']!=''?$address_data['building_address'].$separator:'').($address_data['street_address']!=''?$address_data['street_address'].$separator:'');
//   $town_address='';
//   if($address_data['town_d2']!='' or $address_data['town_d1']!=''){
//     $town_address=_trim($address_data['town_d2'].' '.$address_data['town_d1']).$separator;
//     $town_address=_trim($town_address);
//   }
//   $town_address.=_trim($address_data['town']).$separator;

//   if($address_data['country_d2']==$address_data['country_d1'])
//     $address_data['country_d1']=='';
//   if($address_data['town']==$address_data['country_d2'])
//     $address_data['country_d2']=='';


//   $country_d1_address='';
//   if($address_data['country_d2']!='' or $address_data['country_d1']!=''){
//     $country_d1_address=_trim($address_data['country_d2'].' '.$address_data['country_d1']).$separator;
//     $country_d1_address=$country_d1_address;
//   }

  

//   $full_address=$header_address.$town_address.($address_data['postcode']!=''?$address_data['postcode'].$separator:'').$country_d1_address.$address_data['country'];

  
//   return $full_address;
   

// }



function get_address_data($address_id){
 $db =& MDB2::singleton();
  $sql=sprintf("select internal_address,building_address, street_address,town_d2,town_d1 ,country_d2,country_d1,town,postcode,country from address where id=%d",$address_id);
  //print "$sql\n";
  $res=mysql_query($sql); 
  if (!$address_data=$res->fetchRow())
    return false;
  $sql=sprintf("select address_id,town_d2_id,town_d1_id,country_d2_id,country_d1_id,town_id,country_id from address_atom where address_id=%d",$address_id);
  //print "$sql\n";

  $res=mysql_query($sql); 
  if (!$address_data_atom=$res->fetchRow())
    return false;
  
  //  print "===========>\n";
  //print_r($address_data);
  // print "_==========>\n";
  return array_merge($address_data, $address_data_atom);
  
}



function insert_address($address_data){
 $db =& MDB2::singleton();
 
 // print_r($address_data);

  //$pc=addslashes($address_data['postcode']);
 $internal_address=($address_data['internal_address']!=''?'"'.addslashes(trim(mb_ucwords($address_data['internal_address']))).'"':'null');
 $building_address=($address_data['building_address']!=''?'"'.addslashes(mb_ucwords($address_data['building_address'])).'"':'null');
 $street_address=($address_data['street_address']!=''?'"'.addslashes(mb_ucwords($address_data['street_address'])).'"':'null');
 
 
 $town_id=$address_data['town_id'];
 $country_d1_id=$address_data['country_d1_id'];
 $country_d2_id=$address_data['country_d2_id'];
 $town_d2_id=$address_data['town_d2_id'];
 $town_d1_id=$address_data['town_d1_id'];

 if($address_data['town']!='')
   $town='"'.addslashes(mb_ucwords($address_data['town'])).'"';
 else{
   $town= 'null';
   if($address_data['town_id']==0)
     $town_id='null';
 }
 
 if($address_data['country_d1']!='')
   $country_d1='"'.addslashes(mb_ucwords($address_data['country_d1'])).'"';
 else{
   $country_d1= 'null';
   if($address_data['country_d1_id']==0)
     $country_d1_id='null';
 }
 


if($address_data['country_d2']!='')
  $country_d2='"'.addslashes(mb_ucwords($address_data['country_d2'])).'"';
 else{
   $country_d2= 'null';
   if($address_data['country_d2_id']==0)
     $country_d2_id='null';
 }
 if($address_data['town_d2']!='')
   $town_d2='"'.addslashes(mb_ucwords($address_data['town_d2'])).'"';
 else{
   $town_d2= 'null';
   if($address_data['town_d2_id']==0)
     $town_d2_id='null';
 }
if($address_data['town_d1']!='')
  $town_d1='"'.addslashes(mb_ucwords($address_data['town_d1'])).'"';
 else{
   $town_d1= 'null';
   if($address_data['town_d1_id']==0)
     $town_d1_id='null';
 }

 $postcode=($address_data['postcode']!=''?'"'.addslashes($address_data['postcode']).'"':'null');
 $country=mb_ucwords($address_data['country']);
 $country_id=$address_data['country_id'];
 
 
 
 $sql=sprintf("insert into address (internal_address,building_address,street_address,town_d2,town_d1,town,country_d2,postcode,country_d1,country) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,'%s')",
	      $internal_address,$building_address,$street_address,$town_d2,$town_d1,$town,$country_d2,$postcode,$country_d1,$country
	      );
 //mysql_query($sql);
 //$address_id = $db->lastInsertID();

 mysql_query($sql);
 $address_id=mysql_insert_id();

$sql=sprintf("insert into address_atom (address_id,town_d2_id,town_d1_id,town_id,country_d2_id,country_d1_id,country_id) values (%d,%s,%s,%s,%s,%s,%d)",
	     $address_id,$town_d2_id,$town_d1_id,$town_id,$country_d2_id,$country_d1_id,$country_id
	     );
//print "$sql\n";
//mysql_query($sql);
 mysql_query($sql);



 return $address_id;
 
}


function get_address_metadata($address_id){
  $db =& MDB2::singleton();
  global $_contact_tipo;
  global $_address_tipo;
  $sql=sprintf("select contact_id,contact.tipo as c_tipo,address2contact.tipo as address_tipo  from address2contact left join contact on (contact_id=contact.id) where address_id=%d",$address_id);
  // print "$sql\n";
  $res=mysql_query($sql); 
  $metadata=array();
  while ($row=$res->fetchRow()){
    
    $metadata[]=array(
		    'contact_tipo'=>$_contact_tipo[$row['c_tipo']],
		    'address_tipo'=>$_address_tipo[$row['address_tipo']],
		    'contact_id'=>$row['contact_id'] ,
		    'contact_tipo_id'=>$row['c_tipo'],
		    'address_tipo_id'=>$row['address_tipo'],
		    );
    return $metadata;
      

  }
   return $metadata;
}





function update_address($address_id,$address_data,$date_index='',$note=''){
 $db =& MDB2::singleton();


 $address_keys=array('postcode','internal_address','building_address','street_address','town_d2','town_d1','country_d2','country_d1','country');
 $address_atom_keys=array('town_d2_id','town_d1_id','country_d2_id','country_d1_id','country_id');
 
 
 $old_values=get_address_data($address_id);
 $array_metadata=get_address_metadata($address_id);

 // print_r($old_values);
 //print_r($address_data);
 $update_sql='';
 $values=array();
 foreach($address_keys as $key){
   //print $old_values[$key]."z".$address_data[$key]."zz\n";
    if(strcmp($old_values[$key],$address_data[$key])){

      $values[]=array('old'=>$old_values[$key],'new'=>$address_data[$key]);
      $array_history_sql[]="insert into history_item (history_id,columna,old_value,new_value) values (%d,'$key',%s,%s)";
      $update_sql.=" $key=".prepare_mysql($address_data[$key])." ,";
	
    }
  }
  //print_r($values);


 if(count($values)>0){
   $update_sql=preg_replace('/,$/','',$update_sql);
    $sql=sprintf("update address  set %s where id=%d",$update_sql,$address_id);
    //  print "$sql\n";
    //mysql_query($sql);
       mysql_query($sql);
    foreach($array_metadata as $metadata){

      $recipient_id=$metadata['contact_id'];
  //     if(is_numeric($customer_id= get_customer_from_contact($recipient_id))){
// 	$recipient_id=$customer_id;
// 	$sujeto='Customer';
//       }else
// 	$sujeto='Contact';


      
	$sujeto='Contact';




      $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('UPD','%s',%d,'%s',%d,%s)",$sujeto,$recipient_id,$metadata['address_tipo'],$address_id,prepare_mysql_date($date_index));
      //       print "$sql\n";
       // mysql_query($sql);
       //$history_id=$db->lastInsertID();
        mysql_query($sql);
      $history_id=mysql_insert_id();
      if($metadata['address_tipo']<4){
	foreach($array_history_sql as $key=>$history_sql){
	  $sql=sprintf($history_sql,$history_id,prepare_mysql($values[$key]['old']),prepare_mysql($values[$key]['new']));
	  //print "$sql\n";
	  // mysql_query($sql);
	  mysql_query($sql);
	}
      }
    }
 }



//  $internal_address=($address_data['internal_address']!=''?'"'.addslashes(trim($address_data['internal_address'])).'"':'null');
//  $building_address=($address_data['building_address']!=''?'"'.addslashes($address_data['building_address']).'"':'null');
//  $street_address=($address_data['street_address']!=''?'"'.addslashes($address_data['street_address']).'"':'null');


// if($address_data['town']!='')
//    $town='"'.addslashes($address_data['town']).'"';
//  else{$town= 'null';if($town_id==0)$town_id='null';}
 
//  if($address_data['town_d1']!='')
//    $town_d1='"'.addslashes($address_data['town_d1']).'"';
//  else{$town_d1= 'null';if($town_d1_id==0)$town_d1_id='null';}
 
//  if($address_data['country_d2']!='')
//    $country_d2='"'.addslashes($address_data['country_d2']).'"';
//  else{$country_d2= 'null';if($country_d2_id==0)$country_d2_id='null';}


// if($address_data['country_d1']!='')
//    $country_d1='"'.addslashes($address_data['country_d1']).'"';
//  else{$country_d1= 'null';if($country_d1_id==0)$country_d1_id='null';}


//  if($address_data['town_d2']!='')
//    $town_d2='"'.addslashes($address_data['town_d2']).'"';
//  else{$town_d2= 'null';if($town_d2_id==0)$town_d2_id='null';}




//   $country=$address_data['country'];

//  // Check what is different and act accordinly
//  $old_address_data=get_address_data($address_id);

//  $update=array();
//  if($old_address_data['country']!=$country)$update['country']=true;
//  if($old_address_data['country_d1']!=$country_d1)$update['country_d1']=true;
//  if($old_address_data['country_d2']!=$country_d2)$update['country_d2']=true;
//  if($old_address_data['town']!=$town)$update['town']=true;
//  if($old_address_data['town_d2']!=$town_d2)$update['town_d2']=true;
//  if($old_address_data['town_d1']!=$town_d1)$update['division']=true;
//  if($old_address_data['postcode']!=$country_d1)$update['division']=true;
//  if($old_address_data['street_address']!=$street_address)$update['street_address']=true;
//  if($old_address_data['build_address']!=$build_address)$update['build_address']=true;
//  if($old_address_data['internal_address']!=$internal_address)$update['internal_address']=true;

 
 


//  $sql=sprintf("update  address set internal_address=%s,building_address=%s,street_address=%s,town=%s,town_d2=%s,town_d1=%s,country_d2=%s,country_d1=%s,postcode=%s,country=%d where id=%d",
// 	      $internal_address,$building_address,$street_address,$town,$town_d2,$town_d1,$country_d2,$country_d1,$postcode,$country,$address_id
// 	      );
//  mysql_query($sql);
//  $sql=sprintf("update  address_atom set town_id=%s,town_d2_id=%s,town_d1_id=%s,country_d2=%s,country_d1_id=%s,country_id=%d where address_id=%d",$town_id,$town_d2_id,$town_d1_id,$country_d2_id,$country_d1_id,$country_id,$address_id);
//  mysql_query($sql);

//  if(count($update)>0){
//    $note=($note==''?'null':$note);
//    $sql=sprintf("insert into history (tipo,sujeto,objeto,date,note) values (2,'contact','address','%s',%s)",$date,$ntre);
//      print "$sql\n";
//     mysql_query($sql);
//     $history_id = $db->lastInsertID();
//  }

//  foreach($update as  $key => $value){
//     $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s','%s','%s')"
// 		 ,$history_id,$old_address_data[$key],$$key);
//     print "$sql\n";
//     mysql_query($sql);
//  }

// exit("update address\n");
}


function associate_address($address_id,$contact_id,$tipo,$description='',$date_index='',$history=true){
 
  // print "$address_id  ++\n";

 global $_contact_tipo;
   global $_address_tipo;
  $db =& MDB2::singleton();

  $sql=sprintf("insert into address2contact  (address_id,contact_id,tipo,description) values (%d,%d,%d,%s)",$address_id,$contact_id,$tipo,prepare_mysql($description));
  // mysql_query($sql);
  mysql_query($sql);
  
  if($history){

    if($date_index=='')
      exit("no date on hisroty associate address\n");
  //rint "xxxx $contact_id "   ;
   $contact_data=get_contact_data($contact_id);
   if(!$contact_data){
     print "Notice: error no contact data in associate address $contact_id\n";
     exit;
   }
   $contact_tipo=$_contact_tipo[$contact_data['tipo']];


//     if(is_numeric($customer_id= get_customer_from_contact($contact_id))){
// 	$recipient_id=$customer_id;
// 	$sujeto='Customer';
//     }else{
// 	$sujeto='Contact';
// 	$recipient_id=$contact_id;
//     }

	$sujeto='Contact';
	$recipient_id=$contact_id;

  $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','%s',%d,'%s',%d,%s)",$sujeto,$recipient_id,$_address_tipo[$tipo],$address_id,prepare_mysql_date($date_index));
  // print "$sql\n";
  //mysql_query($sql);
  //$history_id=$db->lastInsertID();
   mysql_query($sql);
   $history_id=mysql_insert_id();


  $address=display_full_address($address_id);
  $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s',NULL,%s)",$history_id,$_address_tipo[$tipo],prepare_mysql($address));
  // print "$sql\n";
  // mysql_query($sql);
  mysql_query($sql);
  // exit("address add\n");
  }


  return true;
  
}

function get_principal_address($tipo,$contact_id){
 $db =& MDB2::singleton();

  if($tipo=='del_address' or $tipo=='bill_address'){
    $customer_id=get_customer_from_contact($contact_id);
    if($tipo=='bill_address')
      $objeto='main_bill_address';
    elseif($tipo=='del_address')
      $objeto='main_del_address';
    $sql=sprintf("select %s as principal from customer where id=%d",$objeto,$customer_id);
    $res=mysql_query($sql); 
    if ($row=$res->fetchRow()){
      return $row['principal'];
    }
  }elseif($tipo=='main_address' or $tipo=='shop_address'){
     $sql=sprintf("select main_address as principal from contact where id=%d",$contact_id);
    $res=mysql_query($sql); 
    if ($row=$res->fetchRow()){
      return $row['principal'];
    }
    
  }
  
  return false;
  
}


function set_principal_address($recipient_id,$tipo,$address_id,$date_index='',$history=true,$update=false){
  $db =& MDB2::singleton();
  global $_address_tipo;
  global $_contact_tipo;

  // check if it actually changed
  if($tipo==2)
    $princial_address=get_principal_address('bill_address',$recipient_id);
  elseif($tipo==3)
    $princial_address=get_principal_address('del_address',$recipient_id);
  elseif($tipo==1)
    $princial_address=get_principal_address('main_address',$recipient_id);

  //  print "$address_id= "
  if($address_id==$princial_address)
    return;


  $tipo_history=($update?'CHG':'NEW');

  //  print "$tipo    $recipient_id  $address_id history  $history  \n";

   if($address_id==''){
     print "warning no address_id trying to set principal address\n";
     
   }

  if($tipo==2 or $tipo==3){
    $col=($tipo==2?'main_bill_address':'main_del_address');
    $col2=($tipo==2?'Main Billing Address':'Main Delivery Address');
 
  $customer_id=get_customer_from_contact($recipient_id);
  if(!$customer_id){
	 exit("$recipient_id  $col Error nop customer id where trying to update del or bill address\n");
       }
    
   $sql=sprintf("select %s from customer where id=%d",$col,$customer_id);
   $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
   if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
     $old_address_id=$row[$col];
     if($old_address_id=='')
       $change=false;
     else
       $change=true;
     if($old_address_id!=$address_id){

        $contact_data=get_customer_data($customer_id);
	$old_data=$contact_data[$col];

       $sql=sprintf("update customer set %s=%d where id=%d",$col,$address_id,$customer_id);
       mysql_query($sql);
       if($change and $history){
	 $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('%s','Customer',%d,%s,%d,%s)",$tipo_history,$customer_id,prepare_mysql($col2),$address_id,prepare_mysql_date($date_index));
	 
	 mysql_query($sql);
	 $history_id=mysql_insert_id();


	 $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,%s,%s,%s)",$history_id,prepare_mysql($col2),prepare_mysql($old_data),$address_id);
	 
	 // mysql_query($sql);
	 mysql_query($sql);
       }
     }
   }
  }else if($tipo==1){
    $col='main_address';
    $col2='Main Address';

    $contact_data=get_contact_data($recipient_id);
    $old_data=$contact_data[$col];

    $sql=sprintf("update contact set %s=%d where id=%d",$col,$address_id,$recipient_id);
    //  mysql_query($sql);
    mysql_query($sql);
    if($history){

  //     if(is_numeric($customer_id= get_customer_from_contact($recipient_id))){
// 	$recipient_id=$customer_id;
// 	$sujeto='Customer';
//       }else
// 	$sujeto='Contact';
 $sujeto='Contact';
      
      $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values (%s,%s,%d,%s,%d,%s)",prepare_mysql($tipo_history),prepare_mysql($sujeto),$recipient_id,prepare_mysql($col2),$address_id,prepare_mysql_date($date_index));
      //  print "qqqqqqqqq $sql\n";
    //mysql_query($sql);
    //$history_id=$db->lastInsertID();
     mysql_query($sql);
     $history_id=mysql_insert_id();


     
    $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,%s,%s,%s)",$history_id,prepare_mysql($col2),prepare_mysql($old_data),$address_id);
    //print "qqqqqqqqq $sql\n";
    //mysql_query($sql);
      mysql_query($sql);
    }
  }




  //  exit("associate xxxxxxxxxsaddress\n");
  
}



// function is_country_d1($name,$country_id){
//   $name=_trim($name);
//   if($name=='')
//     return false;

//   switch($country_id){
    
//   case(229):


//     $states=array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
//     if(strlen($name)==2){
      
//       if (array_key_exists(strtoupper($name), $states)) {
// 	return true;
//       }
//     }

//     if(in_array(mb_ucwords($name),$states)){
//       return true;
//     }

//     break;
//   default:
//     $name=addslashes($name);
//     $sql=sprintf("select id from list_country_d1 where (name='%s' or oname='%s' or osname='%s') and country_id=%d",$name,$name,$name,$country_id);
//     $db =& MDB2::singleton();
//     $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
//     if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//       return true;
//     }else
//       return false;
//   }
//   // print mb_ucwords($name);
//   return false;


// }

// function get_country_d1($name,$country_id,$tipo='town'){

//   $d1='';
//   if($name=='')
//     return $d1;
//   $sql=sprintf("select list_country_d1.name  from list_town left join list_country_d1 on (country_d1_id=list_country_d1 .id) where list_town.name=%s or list_town.oname=%s and list_town.country_id=%d",prepare_mysql($name),prepare_mysql($name),$country_id);
//   $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
//   if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//     $d1=$row['name'];
//   }

//   return $d1;
// }






function insert_orden_files($order_id,$filename,$checksum,$checksum_header,$checksum_products,$file_date){
  $db =& MDB2::singleton();




  $sql=sprintf("insert into orden_file (order_id,filename,checksum,checksum_header,checksum_products,date) values (%d,'%s','%s','%s','%s','%s')",$order_id,$filename,$checksum,$checksum_header,$checksum_products,date("Y-m-d H:i:s",strtotime('@'.$file_date)));
  // print "$sql\n";

  // mysql_query($sql);
  mysql_query($sql);
}

function update_orden_files($order_id,$filename,$checksum,$checksum_header,$checksum_products,$file_date){
  $db =& MDB2::singleton();
  $sql=sprintf("update orden_file set order_id=%d ,checksum='%s',checksum_header='%s',checksum_products='%s',date='%s' where filename=%s",$order_id,$checksum,$checksum_header,$checksum_products,date("Y-m-d H:i:s",strtotime('@'.$file_date)),prepare_mysql($filename));
  //    print "$sql\n";

  // mysql_query($sql);
  mysql_query($sql);
}




function get_payment_method($method){
  

  $method=_trim($method);
  //  print "$method\n";
  if($method=='' or $method=='0')
    return 0;
  if(preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
    return 2;

  //  print "$method\n";
  if(preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
    return 4;
  if(preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
    return 5;
  if(preg_match('/^(cash|casg|casn)$/i',$method))
    return 1;
  if(preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
    return 6;
  if(preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
    return 3;
  if(preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
    return 7;
  if(preg_match('/^(postal order)$/i',$method))
    return 8;
  if(preg_match('/^(Moneybookers)$/i',$method))
    return 9;

  print "Warning: unnkown pay method $method \n";
  return 0;

}




function get_tax_number($data){
  global $myconf;
  $data['tax_number']='';
  $note='';
  $tax_number='';
  if(!$data['dn_country_code'] or $data['dn_country_code']=='0')
    $data['dn_country_code']='';
  //print $data['dn_country_code']."xxx";
  if(
     (in_array(strtoupper($data['dn_country_code']),$myconf['tax_conditional0_2acode'])  and ($data['tax1']==0 or $data['tax1']=='' or !$data['tax1'])  and $data['notes2']!='' )
     or ($data['dn_country_code']=='' and ($data['tax1']==0 or $data['tax1']=='' or !$data['tax1']) and $data['notes2']!=''  )
     ){



    if(preg_match('/CUSTOMER VAT 75732 Company : 680602=4840/i',$data['notes2'])){

      $note='';
	$tax_number='680602-4840';

    }else{

      
      $tax_number=$data['notes2'];
      $regex='/ - do not change shipping cost - fix price.| \, 15\/30|if oos inform customer, no incense for alt. gift|, CARRIAGE BETWEEN .* AND 110|If oos, send email\!|deliver after 10:30 am|Contact Customer for payment details \!\!\!|Check order CAREFULLY|CHARGE BEFORE PICKING|Please phone 087 652 5769 before delivery\!|Deliveries accepted Tue - Sat 1000-1700 Contac Nic|Daughter: Cristina Viana|if oos inform customer, no more bath sets|pls contact cust if any probs with paym|no carriage, |Please contact customer for out of stock items.|See customer.s note regarding SSC.|delivery to Ireland - |Deliveries accepted Tue to Sat 10 \- 17\:30|deliver after 10am|Delivery after 10.00 am| P 24\/06 via frans mass|see note of 5th FEB|see note of 09\/06\/20009|always quote customer| --- Shipping FOC promotion|Kaym_Whelan@yahoo.ie| - checked see note of 19\/05\!/i';
     
      if(preg_match($regex, $tax_number,$match)){
	
	$note=$match[0];
	$tax_number=preg_replace($regex,'',$tax_number);
      }
    }
    //print "OTN: $tax_number\n";
  // print "tax number: $tax_number\n";
    $tax_number=parse_tax_number($tax_number);

  // print "TN: $tax_number\n";
  if(
     preg_match('/^[a-z]{1,2}\s*\-?\s*[a-z0-9]{8,12}\s*$/i',$tax_number) 
     or preg_match('/^[a-z]{0,2}\s*\d{6,16}\s*[a-z]\.?\d{0,10}$/i',$tax_number)
     or preg_match('/^\d{3} \d{4}\-?\d/i',$tax_number) 
     or preg_match('/[a-z]-\d{6,10}-[a-z]/i',$tax_number) 
     or preg_match('/[a-z]{2}\s*\d{3}\.\d{3}\.\d{3}/i',$tax_number) 
     or preg_match('/\d{3}.\d{3,4}.\d{3,4}/i',$tax_number) 
     or preg_match('/680602-4840/i',$tax_number) 
     or preg_match('/[a-z]{2}\s*\d{2,4}\s*\d{2,3}\s*\d{2,4}\s*[a-z]?\d{2,4}/i',$tax_number) 
     or preg_match('/NL 8132 54 097 B01/i',$tax_number) 
     or preg_match('/n-\d{8} S/i',$tax_number) 
     or preg_match('/tf 2134041/i',$tax_number) 




 ){
    $tax_number=preg_replace('/\s/','',$tax_number);
    if(!($tax_number[2]=='-'  or $tax_number[1]=='-')){

      if(preg_match('/^[a-z]{2}\d/i',$tax_number)){
	$t1=substr($tax_number,0,2);
	$t2=substr($tax_number,2);
	$tax_number=$t1.'-'.$t2;
      }elseif(preg_match('/^[a-z]\d/i',$tax_number)){
	$t1=substr($tax_number,0,1);
	$t2=substr($tax_number,1);
	$tax_number=$t1.'-'.$t2;
      }      
      

    }
    $data['tax_number']=$tax_number;
    $data['notes2']=$note;
    // print "$tax_number\n";
    // return $tax_number;
  }elseif(preg_match('/^\d{7,12}$/i',$tax_number)){
    // print "$tax_number\n";
    // return $tax_number;
      $data['tax_number']=$tax_number;
       $data['notes2']=$note;
  }
  }elseif(preg_match('/^vat\s\d{11}$/i',_trim($data['notes2']))){
    $data['tax_number']=$data['notes2'];
    $data['notes2']='';
  }elseif(preg_match('/SA VAT NO 9116\/677\/16\/3/i',_trim($data['notes2']))){
    $data['tax_number']=preg_replace('/^SA VAT NO /','',$data['notes2']);
    $data['notes2']='';
  }elseif(preg_match('/^tax : tf \d{7}/i',_trim($data['notes2']))){
    $data['tax_number']=preg_replace('/^tax : /','',$data['notes2']);
    $data['notes2']='';
  }elseif(preg_match('/^tax id \d{5,}/i',_trim($data['notes2']))){
    $data['tax_number']=preg_replace('/^tax id /','',$data['notes2']);
    $data['notes2']='';
  }elseif(preg_match('/^(Customer)?\s*tax id\s*:?\s*[a-z]?\d{5,}[a-z]?/i',_trim($data['notes2']))){
    $data['tax_number']=preg_replace('/^(Customer)?\s*tax id\s*:?\s*/','',$data['notes2']);
    $data['notes2']='';
  }elseif(preg_match('/^tax : tf 2134041?/i',_trim($data['notes2']))){
    $data['tax_number']='tf 2134041';
    $data['notes2']='';
  }elseif(preg_match('/^Tax 85 467 757 063?/i',_trim($data['notes2']))){
    $data['tax_number']='85467757063';
    $data['notes2']='';
  }elseif(preg_match('/^EL 046982660 valid?/i',_trim($data['notes2']))){
    $data['tax_number']='EL-046982660';
    $data['notes2']='';
  }elseif(preg_match('/^EL-377 187 83?/i',_trim($data['notes2']))){
    $data['tax_number']='EL-37718783';
    $data['notes2']='';
  }elseif(preg_match('/^FI1622254-8 checked by customs?/i',_trim($data['notes2']))){
    $data['tax_number']='FI-1622254-8';
    $data['notes2']='';
  }elseif(preg_match('/^IE-7251185?/i',_trim($data['notes2']))){
    $data['tax_number']='IE-7251185';
    $data['notes2']='';
  }elseif(preg_match('/^SE556670-257601$/i',_trim($data['notes2']))){
    $data['tax_number']='SE556670-257601';
    $data['notes2']='';
  }elseif(preg_match('/^IE5493347N$/i',_trim($data['notes2']))){
    $data['tax_number']='IE5493347N';
    $data['notes2']='';
  }elseif(preg_match('/^ES-B92544691$/i',_trim($data['notes2']))){
    $data['tax_number']='ES-B92544691';
    $data['notes2']='';
  }
      
  return $data;

}







function read_products($raw_product_data,$y_map){
  
  if(isset($y_map['no_reorder']) and $y_map['no_reorder'])
    $re_order=false;
  else
    $re_order=true;

  if(isset($y_map['no_price_bonus']) and $y_map['no_price_bonus'])
    $no_price_bonus=true;
  else
    $no_price_bonus=false;


  $transactions=array();
  foreach($raw_product_data as $raw_data){
    foreach($y_map as $key=>$value){
      $_data=$raw_data[$value];
      if(preg_match('/order|reorder|bonus/',$key))
	if($_data=='')$_data=0;
      
      if(!$re_order and ($key=='reorder' or $key=='rrp')  )
	$_data=0;
      
      if($no_price_bonus){
	if($key=='order' and $transaction['price']==0)
	  $_data=0;
	if($key=='bonus' and $transaction['price']==0)
	  $_data=$_data+ $raw_data[$y_map['order']]  ;


      }
      if($key=='supplier_product_code' and $raw_data[$y_map['supplier_code']]=='AW'   ){
	$_data=$raw_data[$y_map['code']];
      }

      $transaction[$key]=$_data;
    }


    if($transaction['units']==1 or $transaction['units']=='')
      $transaction['name']=$transaction['description'];
    else
      $transaction['name']=trim($transaction['units'].'x '.$transaction['description']);


    $transaction['fob']=$raw_data['fob'];
    $transactions[]=$transaction;
  }
  // print_r($transactions);
  return $transactions;
}

function set_transactions($transactions,$order_id,$tipo_order,$parent_order_id,$date_index,$record_out_stock=true,$tax_code='S'){
  $db =& MDB2::singleton();


  $date_index=str_replace("'",'',$date_index);


  $my_total_net=0;
  $my_total_rrp=0;
  $my_total_items_order=0;
  $my_total_items_reorder=0;
  $my_total_items_bonus=0;
  $my_total_items_free=0;
  $my_total_items_dispatched=0;
  $value_outstoke=0;
  $credit_value=0;

  //   print_r($transactions);
  foreach($transactions as $transaction){

    
    if($transaction['fob'])
      $promotion_id=1;
    else
      $promotion_id='NULL';

    
    if($transaction['order']=='')$transaction['order']=0;
    if($transaction['reorder']=='')$transaction['reorder']=0;
    if($transaction['bonus']=='')$transaction['bonus']=0;
    if($transaction['discount']=='')$transaction['discount']=0;
    
    $my_items_to_charge=$transaction['order']-$transaction['reorder'];
    $my_items_to_charge_value=$my_items_to_charge*($transaction['price'] * (1-$transaction['discount']));
    //  print_r($transaction);
    $my_items_to_dispach=$my_items_to_charge+$transaction['bonus'];
    if(preg_match('/credit/i',$transaction['code'])){
      //	    $transaction['credit']=-abs( $transaction['credit']);
      $credit_parent=$transaction['description'];
      $my_items_to_charge_value=$transaction['credit'];
    }
    
    $my_total_rrp+=$my_items_to_charge*($transaction['rrp']*$transaction['units']);
    $my_total_net+=$my_items_to_charge_value;
    //	  print $transaction['code']." caca $my_total_net =$my_items_to_charge_value \n ";
    $my_total_items_order+=$transaction['order'];
    $my_total_items_reorder=$transaction['reorder'];
    $my_total_items_bonus+=$transaction['bonus'];
    $my_total_items_dispatched+=$my_items_to_dispach;

    if($transaction['discount']==1)
      $my_total_items_free+=$my_total_items_dispatched;
    $tipo_t=1;
    if($transaction['discount']==1)
      $tipo_t=2;


    // Credits




    if(preg_match('/credit/i',$transaction['code'])){
      $parent_id='';
      $parent='xxxx';
      $tipo=0;
      $parent_note=$transaction['description'];
      
      if(preg_match('/^Credit owed for order no\.:/i',$parent_note)){
	$tipo=1;
	if(preg_match('/\d{4,5}/',$parent_note,$thismatch))
	  {
	    $parent=$thismatch[0];
	  }
	      
      }


      $sql=sprintf("select id from orden where public_id=%d",$parent);
      //  print "$parent_note $sql\n";
      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$parent_id=$row['id'];
      }      
      global $tax_rate;
      $tax_factor=$tax_rate;
    
      $credit_value_net=-$transaction['price'];
      if($tax_code=='S')
	$credit_value_tax=$tax_factor*$credit_value_net;
      else
	$credit_value_tax=0;

      $tipo=2;// Debit done
      $parent_note=preg_replace('/^Credit owed for order no..$/i','',$parent_note);
      //  if(is_numeric($parent_id)){

      


      $sql=sprintf("insert into debit (tipo,order_affected_id,order_original_id,note,value_net,value_tax,date_done,tax_code) value (%d,%d,%s,%s ,'%.2f','%.2f',%s,%s)"
		   ,$tipo
		   ,$order_id
		   ,prepare_mysql($parent_id)
		   ,prepare_mysql($parent_note)
		   ,$credit_value_net
		   ,0
		   ,prepare_mysql_date($date_index)
		   ,prepare_mysql($tax_code));
      // print "$sql\n";
      mysql_query($sql);// mysql_query($sql);

  
    }
	


    //$sql=sprintf("update orden set debits='%.2f' where id=%d",$credit_value,$order_id);
    //mysql_query($sql);//mysql_query($sql);


      
    // do a todo_debit
    //	$sql=sprintf("insert into todo_debit (tipo,order_affected_id,note,value,date_creation,date_done) value (%d,%d,%s,%.2f,%s)",$tipo,$order_id,prepare_mysql($parent_note),$credit_value,$date_index);
    //	print "$sql\n";
    //	mysql_query($sql);// mysql_query($sql);


    //      }


    //}

    $is_cash_promo=false;
    if(preg_match('/Promo$/i',$transaction['code']) and ($transaction['price']*$transaction['order']-$transaction['reorder']+$transaction['bonus'])<0){
      $sql=sprintf("insert into debit (tipo,order_affected_id,order_original_id,note,value_net,value_tax,date_done,tax_code) value (%d,%d,%s,%s ,'%.2f','%.2f',%s,%s)"
		   ,6
		   ,$order_id
		   ,'NULL'
		   ,prepare_mysql($transaction['code'])
		   ,$transaction['price']*$transaction['order']-$transaction['reorder']+$transaction['bonus']
		   ,0
		   ,prepare_mysql_date($date_index)
		   ,prepare_mysql($tax_code));
      //print "$sql\n";
      $is_cash_promo=true;
      mysql_query($sql);// mysql_query($sql);
    }

  
    //print $transaction['code']."\n";
    if($tipo_order==6 or $tipo_order==7 or $tipo_order==8){
      if(is_numeric($parent_order_id))
	$original_order=$parent_order_id;
      else
	$original_order=0;
    }else
      $original_order='NULL';


    if(preg_match('/^PI-/i',$transaction['code']))
      $sql=sprintf("select id from product where description='%s' and code='%s'",addslashes($transaction['description']),addslashes($transaction['code']));
    else
      $sql=sprintf("select id from product where code='%s'",addslashes($transaction['code']));


    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      // Found Product
      $product_id=$row['id'];
     
      //      if(!is_numeric($order_id) or $order_id<1)
      //	exit('Error order id can no be this');
	

      $sql=sprintf("insert into transaction (promotion_id,tipo,order_id,product_id,ordered,dispatched,discount,charge,tax_code,original_order_id) value (%s,%d,%d,%d,%.2f,%.2f,%.3f,%.2f,%s,%s)",$promotion_id,$tipo_t,$order_id,$product_id,$transaction['order'],$my_items_to_dispach,$transaction['discount'],$my_items_to_charge_value,prepare_mysql($tax_code),$original_order);
      
      //print "x $sql\n";
      //exit;
      mysql_query($sql);
      
	if($transaction['reorder']>0 and $record_out_stock){
	    $value_outstoke=$value_outstoke+($transaction['reorder'] * ($transaction['price'] * (1-$transaction['discount'])));
	}
	
	if($transaction['reorder']>0) {
	    $sql=sprintf("insert into outofstock (order_id,product_id,qty,status) value (%d,%d,%.2f,%s)",$order_id,$product_id,$transaction['reorder'],($record_out_stock?1:2));
	    mysql_query($sql);
	    
	}
	   
      if($transaction['bonus']>0  or $transaction['discount']==1) {
	$qty=$transaction['bonus'];
	if($transaction['discount']==1)
	  $qty+=$my_items_to_charge;
	$sql=sprintf("insert into bonus (order_id,product_id,qty,promotion) value (%d,%d,%.2f,%d)",$order_id,$product_id,$qty,$promotion_id);
	//		print "$sql\n";
	mysql_query($sql);
      }
    }else{

      if(!preg_match('/credit/i',$transaction['code'])   and !$is_cash_promo){
	
	$sql=sprintf("insert into todo_transaction (promotion_id,code,description,order_id,ordered,reorder,bonus,price,discount,tax_code,original_order_id) value (%s,'%s','%s',%d,  %.2f,%.2f,%.2f,%.2f,%.2f,%s,%s)",$promotion_id,addslashes($transaction['code']),addslashes($transaction['description']),$order_id,$transaction['order'],$transaction['reorder'],$transaction['bonus'],$transaction['price'],$transaction['discount'],prepare_mysql($tax_code),$original_order);
	//  print "x $sql\n";
	mysql_query($sql);
      }


    }








  }

  $sql=sprintf("update orden set outofstock='%.2f' where id=%d",$value_outstoke,$order_id);
  mysql_query($sql);







  // Blamce the originals
  
  if(is_numeric($parent_order_id)){
    $debit_value_net=0;
    $debit_value_tax=0;
    $sql=sprintf("select value_net,value_tax from debit where order_original_id=%d",$parent_order_id);
    // print "$sql\n";
    $result = mysql_query($sql) or die('Query failed:zzasa ' . mysql_error());
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $debit_value_net+=$row['value_net'];
      $debit_value_tax+=$row['value_tax'];
    }      
    $sql=sprintf("select total,net,tax from orden where id=%d",$parent_order_id);
  
    $result = mysql_query($sql) or die('Query failed:zz ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $balance_net=$row['net']+$debit_value_net;
      $balance_tax=$row['tax']+$debit_value_tax;
      $balance_total=$row['total']+$debit_value_net+$debit_value_tax;
    
      $sql=sprintf("update orden set balance_net='%.2f' , balance_tax='%.2f' , balance_total='%.2f' where id=%d",$balance_net,$balance_tax,$balance_total,$parent_order_id);
      mysql_query($sql);//mysql_query($sql);
    }     
  
  }

  // Balance this one


  // money due to cash promotions

  $debit_value_net=0;
  $debit_value_tax=0;

  $sql=sprintf("select value_net,value_tax from debit where (tipo!=6 and tipo!=5) and order_affected_id=%d",$order_id);
  $result = mysql_query($sql) or die('Query failed:zzasa ' . mysql_error());
  while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $debit_value_net-=$row['value_net'];
    $debit_value_tax-=$row['value_tax'];
  }      
  $sql=sprintf("select total,net,tax from orden where id=%d",$order_id);
  
  $result = mysql_query($sql) or die('Query failed:zz ' . mysql_error());
  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
 

    $balance_net=$row['net']+$debit_value_net;
    $balance_tax=$row['tax']+$debit_value_tax;
    $balance_total=$row['total']+$debit_value_net+$debit_value_tax;
    
    $sql=sprintf("update orden set balance_net='%.2f' , balance_tax='%.2f' , balance_total='%.2f' where id=%d",$balance_net,$balance_tax,$balance_total,$order_id);
    
    //    print "$sql\n";
    mysql_query($sql);//mysql_query($sql);
  }     






}




function setup_contact($act_data,$header_data,$date_index,$editor){
  $co='';
  $header_data['country_d2']='';
  $header_data['country']='';
  $header_data['country_d1']='';

  $new_customer=false;



  $this_is_order_number=$header_data['history'];
  if(!is_numeric($this_is_order_number)){
    //    print "Warning history not numeric\n";
    $this_is_order_number=1;

  }

  //  print_r($header_data);
  //  print_r($act_data);

  if(preg_match('/cash sale/i',$header_data['trade_name'])){
    
    if($header_data['address1']=='' and$header_data['address2']=='' and $header_data['address3']=='' and $header_data['city']=='' and $header_data['postcode']==''  and isset($act_data['contact']) 
       ){
      
    

      $staff_name=$act_data['contact'];

      //$staff=new Staff('alias',$staff_name);
      //$staff_id=$staff->id;
      $staff_id=get_user_id($staff_name,'' , '',false,$editor);
      if(count($staff_id)==1 and $staff_id[0]!=0 ){
	print "Staff $staff_name  sale\n";
	$header_data['address1']=$act_data['contact'];
      }
      unset($act_data);

    }
    

    $staff_name=$header_data['address1'];
    $staff_id=get_user_id($staff_name,false,'','',$editor);
    
    //    $staff=new Staff('alias',$staff_name);
    //$staff_id=$staff->id;

     if(count($staff_id)==1 and $staff_id[0]!=0 ){
      print "Staff sale\n";
      unset($act_data);
    }
  }


  $skip_del_address=false;
  $mob_data=false;
  $tel_data=false;
  $fax_date=false;
  $email_data=false;
  
  if(isset($header_data['phone'])  and $header_data['phone']=='0'  )
    $header_data['phone']='';
  if(isset($header_data['postcode'])  and $header_data['postcode']=='0'  )
    $header_data['postcode']='';

  // print_r($act_data);
  // print_r($header_data);
    
  if(!isset($act_data) or count($act_data)==0){
    $act_data['name']='';
    $act_data['contact']='';
    $act_data['a1']='';
    $act_data['a2']='';
    $act_data['postcode']='';
    $act_data['country_d2']='';
    $act_data['name']='';
    $act_data['a3']='';
    $act_data['town']='';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mob']='';
    $act_data['source']='';
    $act_data['act']='';
    $act_data['email']='';
    $act_data['country']='';
    
    $act_data['town_d1']='';
    $act_data['town_d2']='';


    //print_r($header_data);exit;

if (preg_match('/sale - Philip|staff|staff order|cash sale|staff sale|cash - sale/i',$header_data['trade_name']) or
        preg_match('/staff|staff sale|cash sale/i',$header_data['city']) or
        preg_match('/staff|staff sale|cash sale/i',$header_data['address1']) or
        preg_match('/staff|staff sale|cash sale/i',$header_data['address2']) or
        preg_match('/staff|staff sale|cash sale/i',$header_data['address3']) or
        preg_match('/^staff$|staff sale/i',$header_data['notes']) or

        preg_match('/staff|staff sale|cash sale/i',$header_data['postcode'])) {
    //print "cash\n";
    //exit;
    // Chash tipe try to get staff name
    if ($header_data['address1']=='Al & Bev') {
        $header_data['address1']='Bev';
    }
    $regex='/staff orders?|staff|sales?|cash|\-|:|Mark postage to France/i';

    $header_data['city']=_trim(preg_replace($regex,'',$header_data['city']));
    $header_data['postcode']=_trim(preg_replace($regex,'',$header_data['postcode']));
    $header_data['trade_name']=_trim(preg_replace($regex,'',$header_data['trade_name']));
    $header_data['address1']=_trim(preg_replace($regex,'',$header_data['address1']));
    $header_data['address2']=_trim(preg_replace($regex,'',$header_data['address2']));
    $header_data['address3']=_trim(preg_replace($regex,'',$header_data['address3']));
    $header_data['customer_contact']=_trim(   preg_replace($regex,'',$header_data['customer_contact'])      );
    $header_data['phone']=_trim(preg_replace($regex,'',$header_data['phone']));


    if ($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']!='' and $header_data['customer_contact']=='' )
        $header_data['address1']=$header_data['city'];
    if ($header_data['address1']=='' and $header_data['postcode']!='' and $header_data['city']==''   and $header_data['customer_contact']=='' )
        $header_data['address1']=$header_data['postcode'];
    if ($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']==''  and $header_data['customer_contact']!=''  )
        $header_data['address1']=$header_data['customer_contact'];
    if ($header_data['address1']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']!='')
        $header_data['address1']=$header_data['trade_name'];
    if ($header_data['address1']=='' and $header_data['address2']!='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
        $header_data['address1']=$header_data['address2'];
    if ($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']!='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
        $header_data['address1']=$header_data['address3'];
    if ($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['notes']!=''       and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='') {
        // Unkown

        $header_data['address1']=$header_data['notes'];
    }


    if ($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='') {
        // Unkown
        // Create unknowen customer
        //	$customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
        //return array(false,$customer_id,false,false,false,true,$co);
    }




    if ($header_data['address1']!='') {
        $staff_name=$header_data['address1'];

        $staff_id=get_user_id($staff_name,false,'','',$editor);

        //	$staff=new Staff('alias',$staff_name);

        //	print "$staff_name\n";
        //	  print_r($staff_id);
        //	  exit;
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {


            $staff_id=$staff_id[0];
            $staff=new Staff('id',$staff_id);
// 	  $staff_data=get_staff_data($staff_id);
// 	  //print_r($staff_data);

            $act_data['name']='Ancient Winsdom Staff';
            $act_data['contact']=$staff->data['Staff Name'];
// 	  // print_r(get_contact_data($contact_id));
// 	  // exit;
// 	  if(!$staff_data['customer_id']){
// 	    $customer_id = insert_customer($contact_id,array(9,1,2,3,7,10),$date_index,($this_is_order_number==1?true:false));
// 	    $new_customer=true;
// 	  }else{
// 	    $customer_id = $staff_data['customer_id'];
// 	    $new_customer=false;
// 	  }
// 	  return array($contact_id,$customer_id,false,false,false,$new_customer,$co);




        } else {
            // print $staff_name;
            if (preg_match('/|maureen|church|Parcel Force Driver|sarah|Money in Petty|church|Parcel Force Driver|craig|malcol|Joanne/i',$staff_name)) {
                // $customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
                // return array(false,$customer_id,false,false,false,true,$co);


            }







        }

    }


}


    $act_data=act_transformations($act_data);



    // Try to fix it
    if(isset($header_data['order_num'])){

      switch($header_data['order_num']){
  case(77781):
	$skip_del_address=true;
	$act_data['a1']='20 Thrilmere Avenue';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Elland';
	$act_data['country_d2']='';
	$act_data['country_d1']='';
	$act_data['country']='UK';
	$act_data['postcode']='HX5 9PN';
	$act_data['contact']='Gareth Walker';
	$act_data['name']='Lazer-Me';
	$act_data['tel']='01422250350';
		$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	break;
      case(59470):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='46 Moorland Rise';
	$header_data['address2']='';
	$header_data['address3']='';
	$header_data['city']='Haslingden';
	$header_data['postcode']='BB4 6UA';
	$header_data['country']='UK';
	$act_data['contact']='Susan Sanchia';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town_d1']='El Cucador';
	$act_data['town']='Zurgena';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	break;
 case(83652):
	$skip_del_address=true;
	$act_data['a1']='7-9 Filey Road';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Scarborough';
	$act_data['country_d2']='';
	$act_data['country_d1']='';
	$act_data['country']='UK';
	$act_data['postcode']='YO11 2SE';
	$act_data['email']='karensmith@hotmail.com';
	$act_data['contact']='Karen Smith';
	$act_data['name']='Bradley Court Hotel';
	break;
      case(12636):
	$skip_del_address=true;
	$act_data['a1']='Leoforos Salaminas 103';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';
	break;
      case(44059):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Mondial Forwarding';
	$header_data['address2']='';
	$header_data['address3']='46 Lockfield Avenue';
	$header_data['city']='London';
	$header_data['postcode']='EN3 7PX';
	$header_data['country']='UK';
	$act_data['a1']='PO BOX 493491';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Neapoly';
	$act_data['country_d2']='';
	$act_data['country_d1']='Lakonia';
	$act_data['country']='Greece';
	$act_data['postcode']='';


	break;
      case(8192):
      case(19295):
      case(43870):
      case(28867):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Petrou Fouriki & N.';
	$act_data['a2']='Papanikolaou 6';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';


	break;
      case(16339):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Dynamidi 22';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18902';


	break;
      case(8192):

      case(13577):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Leoforos Salaminas 103';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';


	break;
      case(28867):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='N. Papanikolaou 6';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';
	break;
      case(21169):

	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Oughterard';
	$act_data['country_d2']='Galway';
	$skip_del_address=true;
	   break;
      case(40508):
      case(50878):
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town_d1']='El Cucador';
	$act_data['town']='Zurgena';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	$skip_del_address=true;
	break;
      case(33459):
	$act_data['town']='Alfaz del Pi';
	$act_data['country_d2']='Alicante';
	$act_data['country_d1']='Valencian Community';
	$act_data['country']='Spain';
	$act_data['postcode']='03580';
	$skip_del_address=true;
	break;
      case(33459):
	$act_data['town']='San Miguel de las Salinas';
	$act_data['country_d2']='Alicante';
	$act_data['country_d1']='Valencian Community';
	$act_data['country']='Spain';
	$act_data['postcode']='03193';
	$skip_del_address=true;
	break;

      case(54503):
      case(52941):
      case(52712):
      case(49477):
      case(44644):
      case(44052):
      case(41321):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='15 Kestrel House';
	$header_data['address2']='';
	$header_data['address3']='';
	$header_data['city']='Farnham';
	$header_data['postcode']='GU9 8UY';
	$header_data['country']='UK';
	$act_data['contact']='Ms Pauline Murdock';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town_dq']='El Cucador';
	$act_data['town']='Zurgena';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	break;
      case(52060):
      case(54502):
      case(56510):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='Swan Lodge';
	$header_data['address2']='Hassock Hill Drove';
	$header_data['address3']='';
	$header_data['city']='Gorefield';
	$header_data['postcode']='PE13 4QF';
	$header_data['country']='UK';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	$act_data['contact']='Ms Telma Pope';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='El Cucador';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	break;	
      case(59505):
      case(60970):
      case(68058):
      case(65639):
      case(62012):
      case(63810):
      case(71447):
      case(74506):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='Read Coat Express';
	$header_data['address2']='Global House';
	$header_data['address3']='Manor Court';
	$header_data['city']='Crawley';
	$header_data['postcode']='RH10 9PY';
	$header_data['country']='UK';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='El Cucador';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	break;	

  //    case(15320):
  //    case(17357):
  //    case(60454):
  //    case(39099):
	//$customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	//return array(false,$customer_id,false,false,false,true,$co);
	
	break;
      case(37736):
	$act_data['name']='Steel City Lighting';
	$skip_del_address=true;
	break;
      case(53380):
	$skip_del_address=true;
	break;
      case(34467):
	$act_data['name']='Kathy Van Pelt';
	$act_data['contact']='Kathy Van Pelt';
	$act_data['a1']='1605 Lomax Lane';
	$act_data['postcode']='90278';
	$act_data['country_d2']='California';
	$act_data['town']='Redondo Beach';
	$act_data['country']='USA';
	$act_data['tel']='001 310 318 30 80';
	$skip_del_address=true;
	break;

      case(33833):
	$act_data['name']='IA.CO';
	$act_data['contact']='Debbie Lemke';
	$act_data['a1']='43555 Grimmer Blvd #k188';
	$act_data['postcode']='94538';
	$act_data['country_d2']='California';
	$act_data['town']='Fremont';
	$act_data['country']='USA';
	$act_data['tel']='0015104400120';
	$skip_del_address=true;
	break;

      case(22245):
	$act_data['name']='Living TV - Web Shop';
	$act_data['contact']='Steve Deakin Davies';
	$act_data['mob']='07887985166';
	$skip_del_address=true;
	break;
      case(22502):
	$act_data['name']='Ashok Jhunjhunwala';
	$act_data['a1']='GD-213, Ground Fllor';
	$act_data['a2']='Salt Lake, Sector III';
	$act_data['postcode']='700106';
	$act_data['town']='Kilkaya';
	$act_data['country']='India';
	$act_data['tel']='00919830020595';
	$skip_del_address=true;
	break;
      case(23765):
	$act_data['name']='Ramzi Saade';
	$act_data['contact']='Mr Ramsey Saade';
	$act_data['a1']='Ballafletcher Cottage';
	$act_data['a2']='Peel Road';
	$act_data['postcode']='IM4 4LD';
	$act_data['town']='Braddan';
	$act_data['country']='Isle Of Man';
	$act_data['tel']='07624464629';
	$skip_del_address=true;
	break;

      case(22501):
	$act_data['name']='Taurus HQ';
	$act_data['contact']='Arun Jhunjhunwala';
	$act_data['a1']='210 Tirupati Udyog';
	$act_data['a2']='IB Patel Road';
	$act_data['postcode']='400063';
	$act_data['town']='Mumbai';
	$act_data['country']='India';
	$act_data['tel']='912230966762';
	$skip_del_address=true;
	break;
      case(29798):
	$act_data['name']="Maria A Aranega";
	$act_data['tel']='0033386649331';
	$act_data['a1']='11/13 Rue de la Grande Juiverie';
	$act_data['postcode']='89100';
	
	$act_data['town']='Sens';
	$act_data['country']='France';
	$skip_del_address=true;
	break;
      case(30371):
	$act_data['name']="The Ambassadors";
	$act_data['contact']='Kim Skonier';
	$act_data['tel']='01483545825';
	$act_data['a1']='New Victoria Theatre';
	$act_data['a2']='Ther Peacocks Centre';
	$act_data['postcode']='GU21 6GQ';
	
	$act_data['town']='Woking';

	$skip_del_address=true;
	break;
      case(22405):
	$act_data['name']="Mrs M's Herbals";
	$act_data['contact']='Nicola Manghall';
	$act_data['tel']='01246850186';
	$act_data['a1']='5 Berry Street';
	$act_data['postcode']='S42 5JD';
	
	$act_data['town']='Chesterfield';

	$skip_del_address=true;
	break;


      case(70387):
	$act_data['name']='Le Spa Lelalei o Samoa';
	$act_data['contact']='Ivy Warner';
	$act_data['a1']='PO BOX 460';
	$act_data['a2']='Vaoala';
	$act_data['town']='Apia';
	$act_data['country']='Samoa';
	$act_data['email']='lelaleleiosamoa@hotmail.com';
	$skip_del_address=true;
	break;
      case(7667):
	$act_data['name']='Igneus Products';
	$act_data['a1']='Beta Works';
	$act_data['a2']='New Road';
	$act_data['postcode']='SK23 7JG';
	$act_data['country_d2']='Derbyshire';
	$act_data['town']='Whaley Bridge';
	$skip_del_address=true;

	break;
      case(7796):
	$act_data['name']='Ian Spencer';
	$act_data['contact']='Ian Spencer';
	$act_data['a1']='Bosworth College';
	$act_data['a2']='Leicester Lane';
	$act_data['postcode']='LE9 9JL';
	$act_data['country_d2']='Leicestershire';
	$act_data['town']='Desford';
	$act_data['tel']='1455822841';
	$skip_del_address=true;
	break;
      case(9620):
	$act_data['name']='T A Manson';
	$act_data['contact']='T A Manson';
	$act_data['a1']='21 North lane';
	$act_data['postcode']='CO6 1EG';
	$act_data['town']='Marks Tey';
	$act_data['tel']='01206210927';
	$skip_del_address=true;
	break;
      case(40146):
	$act_data['name']='Mrs M Lindfield';
	$act_data['a1']='6 Summers Mead';
	$act_data['postcode']='BS37 7RB';
	$act_data['town']='Yate';
	$act_data['tel']='0145311307';
	$act_data['mob']='07812058252';
	$skip_del_address=true;
	break;
      case(41102):
	$act_data['name']='Forget me not';
	$act_data['contact']='Dave & Jill Cotton';
	$act_data['a1']='48 Wessington Lane';
	$act_data['postcode']='DE55 7NB';
	$act_data['town']='Alferton';
	$act_data['tel']='01773546724';

	$skip_del_address=true;
	break;
      case(42500):
	$act_data['name']='Julia Lynn';
	$act_data['a1']='37 Meadowhead';
	$act_data['postcode']='S8 7UB';
	$act_data['town']='Sheffield';

	$skip_del_address=true;
	break;
      case(43767):
	$act_data['name']='Lillipots Florits';
	$act_data['contact']='Sharon Janson';
	$act_data['a1']='13 Church Street';
	$act_data['postcode']='S63 7QZ';
	$act_data['town']='Rotherham';
	$act_data['tel']='01709761414';
	$skip_del_address=true;
	break;
      case(44501):
	$act_data['name']='Jane Beale';
	$act_data['a1']='109 Alexandra Road';
	$act_data['postcode']='S2 3EH';
	$act_data['town']='Sheffield';
	$act_data['tel']='01142498811';
	$skip_del_address=true;
	break;
      case(45902):
	$act_data['name']='FGX International';
	$act_data['contact']='Alison Marstson';
	$act_data['a1']='500 George Washington Highway';
	$act_data['postcode']='02917';
	$act_data['town']='Smithfield';
	$act_data['country_d2']='RI';
	$act_data['tel']='14017192211';
	$act_data['country']='USA';
	$skip_del_address=true;
	break;
      case(47954):
	$act_data['name']='Mandy Lewis';
	$act_data['a1']='22 Albert Avenue';
	$act_data['postcode']='PE25 3DQ';
	$act_data['town']='Skegness';
	$act_data['tel']='01754611161';
	$skip_del_address=true;
	break;
      case(48152):
	$act_data['name']='Beeston Rylands Junior Scool';
	$act_data['contact']='Mrs Nicola Langley';
	$act_data['a1']='Trent Road';
	$act_data['postcode']='NG9 1LJ';
	$act_data['town']='Nottingham';
	$act_data['tel']='07792662802';
	$act_data['email']='celebrity.auction@nltworld.com';
	$skip_del_address=true;
	break;
      case(48232):
	$act_data['name']='Spa Moment (UK) Ltd';
	$act_data['contact']='Carolyn Sovatabua';
	$act_data['a1']='Dunston Hole Farm';
	$act_data['a2']='Dunston Road';
	$act_data['postcode']='S41 9RL';
	$act_data['town']='Chesterfield';
	$skip_del_address=true;
	break;
      case(52602):
	$act_data['name']='Stonemen Crafts';
	$act_data['contact']='Shirshir Asthana';
	$act_data['a1']='28/139 Gokulpura';
	$act_data['postcode']='282002';
	$act_data['town']='Agra';
	$act_data['country']='India';
	$skip_del_address=true;
	break;
      case(54856):
	$act_data['name']='Another Paraise';
	$act_data['contact']='Dawn Hopkins';
	$act_data['a1']='12 Ramseyburg Road';
	$act_data['postcode']='07832';
	$act_data['town']='Columbia';
	$act_data['country_d2']='NJ';
	$act_data['country']='USA';
	$skip_del_address=true;
	break;

      case(55732):
	$act_data['name']='John Jackson';
	$act_data['a1']='35 Rustlings Road';
	$act_data['postcode']='S11 7AA';
	$act_data['town']='Sheffield';
	$act_data['mob']='07743877474';
	$skip_del_address=true;
	break;
      case(62459):
	$act_data['name']='Ventura';
	$act_data['contact']='Leslie Jordan';
	$act_data['a1']='Magna Main Reception';
	$act_data['a2']='Sheffield Road';
	$act_data['postcode']='S60 1DX';
	$act_data['town']='Sheffield';
	$act_data['mob']='07810767418';
	$skip_del_address=true;
	break;
      case(67799):
	$act_data['name']='Muhammad Ridhuan';
	$act_data['a1']='Blk 450 Jorong West Street #01-86';
	$act_data['postcode']='640450';
	$act_data['town']='Singapore';
	$act_data['country']='Singapore';
	$skip_del_address=true;
	break;


      case(55957):
	$act_data['name']='Gopal Magic Moments';
	$act_data['contact']='Ashok Sood';
	$act_data['a1']='Corporate Office';
	$act_data['a2']='240 Okhla Industrial Estate Phase III';
	$act_data['postcode']='110020';
	$act_data['town']='New Delhi';
	$act_data['country']='India';
	$skip_del_address=true;
	break;
      case(60760):
	$act_data['name']='ASP';
	$act_data['contact']='Ron Jordan';
	$act_data['a1']='82 Tranwands Brigg';
	$act_data['a2']='Heelands';
	$act_data['postcode']='MK13 7PB';
	$act_data['town']='Milton Keynes';
	$skip_del_address=true;
	break;
      case(63524):
      case(62948):
      case(64321):
      case(67021):
      case(67627):
	$act_data['name']='Cadoworld';
	$act_data['contact']='Philippe Buchy';
	$act_data['a1']='116 Findon Street';
	$act_data['postcode']='S6 4QP';
	$act_data['town']='Sheffield';
	$act_data['email']='buchyp@yahoo.fr';
	$skip_del_address=true;
	break;
      case(26178):
	$act_data['name']='Incentive Ideas Ltd';
	$act_data['contact']='Nicola Standing';
	$act_data['a1']='Enterprise 5';
	$act_data['a2']='Five Lane Ends';
	$act_data['postcode']='BD10 8EW';
	$act_data['town']='Bradfrods';
	$skip_del_address=true;
	break;
      case(73674):
	$act_data['name']='Manse Jewekker';
	$act_data['contact']='Iman Sukhani';
	$act_data['a1']='245 Main Street';
	$act_data['town']='Gibraltar';
	$act_data['country']='Gibraltar';
	$act_data['email']='imansukhani@yahoo.com';
	$act_data['tel']='35077903';
	$skip_del_address=true;
	break;

      case(26701):
	$act_data['name']='Fortune by Alison.com';
	$act_data['contact']='Alison Alden';
	$act_data['a1']='23 York Road';

	$act_data['postcode']='NR30 2NA';
	$act_data['town']='Great Yarmouth';
	$act_data['tel']='01493331227';
	$skip_del_address=true;
	break;
      case(73106):
	$act_data['name']='Tresure Trove Wholesale';
	$act_data['contact']='Dave Sandy';
	$act_data['a1']='3 Westham Road';

	$act_data['postcode']='DT4 8NP';
	$act_data['town']='Weymouth';
	$skip_del_address=true;
	break;
      case(13231):
	$act_data['name']='AA Chivers';
	$act_data['a1']='1 Cherry Wood Grove';
	$act_data['a2']='Lightwood Road';
	$act_data['postcode']='ST3 7XL';
	$act_data['town']='Stoke on Trent';
	$skip_del_address=true;
	break;
      case(27406):
	$act_data['name']='Sue Jackson';
	$act_data['a1']='521 Littleworth Road';
	$act_data['postcode']='WS12 1JA';
	$act_data['town']='Cannock';
	$skip_del_address=true;
	break;
      case(26550):
	$act_data['name']='Lavender Laine Creations';
	$act_data['contact']='Mrs Maguire';
	$act_data['a1']='8 Ripley Street';

	$act_data['postcode']='HX3 8UA';
	$act_data['town']='Halifax';
	$act_data['tel']='01422208929';
	$skip_del_address=true;
	break;
      case(24182):
	$act_data['name']='Tim Shortland';
	$act_data['a1']='1 Haywood Avenue';
	$act_data['postcode']='S36 2QD';
	$act_data['town']='Sheffield';
	$act_data['tel']='01142882824';
	$skip_del_address=true;
	break;
      case(66002):
	// this is a foloe order
	$act_data['name']='Petit Cadeaux';
	$act_data['contact']='Rachel Mackenzie';
	$act_data['a1']='5/A Hutchinson Terrace';
	$act_data['postcode']='EH14 1QB';
	$act_data['town']='Edinburgh';
	$act_data['mob']='07852965703';
	$act_data['email']='xxbabiemackxx@hotmail.com';
	$skip_del_address=true;
	break;
      case(76253):
	//	$customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	//return array(false,$customer_id,false,false,false,true,$co);
	$skip_del_address=true;
	break;
      case(39135):
	$act_data['name']=$staff_name;
	break;
      case(77175):

	$name='Adriana';
	$staff=new Staff('alias',$name);
	$staff_id=$staff->id;
	$act_data['name']=$name;
	$skip_del_address=true;
	$act_data['contact']=$name;
break;
    case(39099):
	$act_data['contact']='Mrs M Lindfield';
	$act_data['a1']='6 Summers Mead';
	$act_data['name']='';
	$act_data['town']='Bristol';
	$act_data['postcode']='BS37 7RB';
	
	$act_data['tel']='01454 311 307';
		$act_data['mob']='07812058525';

	$skip_del_address=true;
	break;

case(17357):
$act_data['name']='Lantons';
	break;
	case(60454):
$act_data['name']='Collette';
	break;
      default:
	
	$email='';
  $tel=$header_data['phone'];
  if(preg_match('/[a-z0-9\.\-]+\@[a-z0-9\.\-]+/',$header_data['phone'],$match)){
     $email=$match[0];
     $tel=preg_replace("/$email/",'',$header_data['phone']);
  }
  $country='';
  $postalcode=$header_data['postcode'];
  if(preg_match('/^[a-z]{4,} \d+$/i',$header_data['postcode'])){
    $tmp=preg_split('/\s/',$header_data['postcode']);
    $country=$tmp[0];
    $postalcode=$tmp[1];
  }
  
//
  //    case(60454):
  //    case(39099):
 
    print "order without act\n";
    $skip_del_address=true;
    $act_data['name']=$header_data['trade_name'];
    $act_data['contact']=$header_data['customer_contact'];
    $act_data['a1']=$header_data['address1'];
    $act_data['a2']=$header_data['address2'];
    $act_data['postcode']=$postalcode;
    $act_data['country_d2']='';
    $act_data['a3']='';
    $act_data['town']=$header_data['city'];
    $act_data['tel']=$tel;
    $act_data['fax']='';
    $act_data['mob']='';
    $act_data['source']='';
    $act_data['act']='';
    $act_data['email']=$email;
    $act_data['country']=$country;
    
    $act_data['town_d1']='';
    $act_data['town_d2']='';
  
	
	
	
      }

    }else
      exit("NO num_inv \n");
    
  
  }




  


  





  $different_delivery_address=false;



  switch($header_data['order_num']){
      case(16339):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Dynamidi 22';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18902';


	break;
    
  case(59470):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='46 Moorland Rise';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['city']='Haslingden';
    $header_data['postcode']='BB4 6UA';
    $header_data['country']='UK';
    $act_data['contact']='Susan Sanchia';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    break;
  case(12891):
    $act_data['town']='Waterdown';
    $act_data['country_d2']='Ontario';
    $header_data['address1']='Global Aerospace M&M forwarding';
    $header_data['address2']='600 Main Street';
    $header_data['address3']='';
    $header_data['country_d2']='New York';
    $header_data['city']='Tonawanda';
    $header_data['postcode']='14151';
    $header_data['country']='USA';
    break;
 case(42804):
	$act_data['postcode']='CV3 2BD';
	$skip_del_address=true;
	break;

 case(37807):
	$act_data['postcode']='WS12 2GL';
	$act_data['town']='Cannock';
	$skip_del_address=true;
	break;
  case(21169):

	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Oughterard';
	$act_data['country_d2']='Galway';
	$skip_del_address=true;
	   break;
  case(54503):
  case(52941):
  case(52712):
  case(49477):
  case(44644):
  case(44052):
  case(41321):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='15 Kestrel House';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['city']='Farnham';
    $header_data['postcode']='GU9 8UY';
    $header_data['country']='UK';
    $act_data['contact']='Ms Pauline Murdock';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    break;
 case(42844):
   print "hols";
	$act_data['a2']="14 Gray's Inn Road";
	$skip_del_address=true;
	break;
  case(53600):
    $header_data['address1']='Viking Forwarders Inc';
    $header_data['address2']='Suite 5';
    $header_data['address3']='10800 NW - 103 Rd Street';
    $header_data['city']='Miami';
    $header_data['country_d2']='Florida';
    $header_data['postcode']='33178';
    $header_data['country']='USA';
    break;
 case(14175):
 case(12809):

    $header_data['country']='Spain';
    $act_data['town']='Olivia';
    $act_data['a3']='';

    $act_data['country_d2']='Valencia';
    $act_data['country_d1']='Valencia';
    $skip_del_address=true;
    break;
  case(52060):
  case(54502):
  case(56510):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='Swan Lodge';
    $header_data['address2']='Hassock Hill Drove';
    $header_data['address3']='';
    $header_data['city']='Gorefield';
    $header_data['postcode']='PE13 4QF';
    $header_data['country']='UK';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    $act_data['contact']='Ms Telma Pope';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    break;	

  case(18235):


    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/o Vitesse Courier Services';
    $header_data['address2']='Wraysbury House';
    $header_data['address3']='';
    $header_data['city']='Colnbrook';
    $header_data['postcode']='SL30AY';
    $header_data['country']='UK';
    $act_data['a1']='Unit 16 Eko Hotel';
    $act_data['a2']='Shopping Complex';
    $act_data['a3']='1 Ajoce Adeogun St';
    $act_data['town']='Victoria Island';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    $act_data['country_d1']='Lagos State';
    break;	

  case(12636):
    $skip_del_address=true;
    $act_data['a1']='Leoforos Salaminas 103';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;
  case(8192):

  case(13577):

    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Leoforos Salaminas 103';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';


    break;
 case(46812):
 $header_data['postcode']='NR3 1UA';
 $header_data['city']='Norwich';
$header_data['country_d1']='Norfolk';
$header_data['address3']='';

 break;

  case(28867):
 $act_data['a1']='N. Papanikolaou 6';
 case(43870):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Petrou Fouriki & N. Papanikolaou 6';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;	
  case(20022):
    $skip_del_address=true;
    $act_data['a1']='Unit 16 Eko Hotel';
    $act_data['a2']='Shopping Complex';
    $act_data['a3']='1 Ajoce Adeogun St';
    $act_data['town']='Victoria Island';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    $act_data['country_d1']='Lagos State';
    break;
  case(57653): $skip_del_address=true;

    $act_data['town']='Lagos';
    $act_data['country_d1']='Lagos State';
    break;
  case(22279):
  case(42170):
    $skip_del_address=true;
    $act_data['a1']='Gra 11';
    $act_data['a2']='109 Woji Rd';
    $act_data['a3']='';
    $act_data['town']='Port Harcourt';
    $act_data['country']='Nigeria';
    $act_data['postcode']='41130';
    $act_data['country_d1']='Rivers State';
    break;	
  case(24811):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Dawson Creek';
    $act_data['country_d2']='British Columbia';
    break; 
  case(60516):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='North Bay';
    $act_data['country_d2']='Ontario';
    break;
  case(60679):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Chillwack';
    $act_data['country_d2']='British Columbia';
    break;

  case(8837):
    $skip_del_address=true;
    $different_delivery_address=true;
    $act_data['a1']='';
    $act_data['a2']='Unit 12';
    $act_data['a3']='Old Clarendon Dye Works';
    $act_data['town']='Leicester';
    $act_data['postcode']='LE2 6AR';
    $act_data['mob']='';
    $act_data['tel']='';
    break;

  case(53921):
    $skip_del_address=true;

    $act_data['town']='Ottawa';
    $act_data['country_d2']='Ontario';
    break;


  case(35020):
    $skip_del_address=true;
    $act_data['a1']='App 3';
    $act_data['a2']='80 Rue Carrier';
    $act_data['a3']='';
    $act_data['town']='Lévis';
    $act_data['country_d2']='Québec';
    break;
  case(34266):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Qualicum';
    $act_data['country_d2']='British Columbia';
    $act_data['postcode']='V9K1T2';
    break;
    
  case(24864):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='PO Box 56866';
    $header_data['city']='Limassol';
    $header_data['postcode']='CY3310';
    $header_data['country']='Cyprus';
    
    break;	
    
  case(8192):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Leoforos Salaminas 103';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;
  case(71271):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Italy';
    $header_data['city']='Mestre';
    $header_data['postcode']='30174';
    $header_data['country_d1']='Veneto';
    $header_data['country_d2']='Venezia';
    $act_data['a1']='C/o CC Futura2';
    $act_data['a2']='Via Chiesanuova 71';
    $act_data['country_d1']='Veneto';
    $act_data['country_d2']='Padova';

    break;
  case(71286):

    $skip_del_address=true;
    $act_data['a1']='68 The Glade';
    $act_data['town']='Athenry';
    $act_data['country_d2']='Co Galway';

    break;


  case(7772):
  case(6979):
  case(8073):
    $skip_del_address=true;
    $act_data['a1']='111 S Central Avenue';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Okmulgee';
    $act_data['country_d2']='Oklahoma';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='74447';
    break;

  case(7941):
  case(7439):
    $skip_del_address=true;
    $act_data['a2']='Line E, Block 7';
    $act_data['a1']='Ijeh Police Barracks';
    $act_data['a3']='';
    $act_data['town']='Ikoyi';
    $act_data['country_d2']='Lagos Island';
    $act_data['country_d1']='Lagos State';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    break;

  case(8557):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Mushin';
    $act_data['country_d2']='Mushin';
    $act_data['country_d1']='Lagos State';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    break;


  case(15952):
  case(14965):
    $skip_del_address=true;
    $act_data['a1']='10 Robert Memorial Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Clinton';
    $act_data['country_d2']='Massachusetts';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='01510';
    break;
  case(21453):
    $skip_del_address=true;
    $act_data['a1']='Hyland Plaza';
    $act_data['a2']='2152 South Highland Drive';
    $act_data['a3']='';
    $act_data['town']='Salt Lake City';
    $act_data['country_d2']='Utah';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='84106';
    break;
  case(23707):
    $skip_del_address=true;
    $act_data['country']='Netherlands';

    break;

  case(34145):
    $skip_del_address=true;
    $act_data['country']='USA';

    break;
  case(40809):
  case(48464):
    $skip_del_address=true;
    $act_data['country']='Spain';
    $act_data['postcode']='03189';
    break;


  case(34148):
  case(34689):
    $skip_del_address=true;
    $act_data['country']='USA';
    $act_data['town']='Alexandria';
    $act_data['country_d2']='Minnesota';
    $act_data['a2']='';
    $act_data['a3']='';
    break;

  case(57927):
    $skip_del_address=true;
    $act_data['country']='USA';
    break;
  case(8643):
    $skip_del_address=true;
    $act_data['country']='Belgium';
    $act_data['country_d2']='';
    break;
 case(41297):
    $header_data['country']='UK';

    break;
  case(64129):
    $skip_del_address=true;
    $act_data['country']='Spain';
    $act_data['town']='Jávea';
    $act_data['country_d1']='Valencia';
    $act_data['country_d2']='Alicante';
    $act_data['a2']='';
    $act_data['a1']='Calle J. Reynolds 3/452';
    break;
  case(23540):
  case(37184):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Finland';
    $header_data['city']='Kärrby';
    $header_data['country_d1']='';
    $header_data['country_d2']='';
    $header_data['address1']='Svartbäcksvägen 144';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['postcode']='06880';

  case(37599):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='United Kingdom';
    $header_data['city']='Orpington';
    $header_data['country_d1']='';
    $header_data['country_d2']='Kent';
    $header_data['address1']='86 Glentrammon Road';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['postcode']='BR6 6DG';
    break;
  case(37661):
    $skip_del_address=true;
    $different_delivery_address=true;
    $act_data['a1']='38 Wellgate';
    $act_data['country_d1']='';
    $act_data['country_d2']='';
    $header_data['country']='United Kingdom';
    $header_data['city']='Lanark';
    $header_data['country_d1']='';
    $header_data['country_d2']='';
    $header_data['address1']='44 Wellgate';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['postcode']='ML11 9DT';
    break;

  case(37445):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Norway';
    $header_data['city']='Straume';
    $header_data['country_d1']='';
    $header_data['country_d2']='';
    $header_data['address1']="Wenche's Heste-Og Hundemassasje";
    $header_data['address2']='';
    $header_data['address3']='Postboks 407';
    $header_data['postcode']='5343';


    break;
  case(75371):

   $header_data['country']='UK';
    break;

  case(75020):
  case(75168):
 $different_delivery_address=true;
    break;
  case(75138):
$header_data['city']='Douglas';
     $header_data['address3']='';
    $header_data['address2']='';
    break;
 case(62926):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Spain';
    $header_data['city']='Triquivijate';
    $header_data['country_d1']='Islas Canarias';
    $header_data['country_d2']='Provincia de Las Palmas';
    $header_data['address3']='';
    break;
  case(74523):
   $header_data['city']='Teguise';
    $header_data['country_d1']='Islas Canarias';
    $header_data['country_d2']='Provincia de Las Palmas';
    $header_data['address3']='';
    $header_data['address2']='';

      $header_data['country']='Spain';
   $header_data['postcode']='35558';
    break;
  case(25192):
  case(33201):
    $skip_del_address=true;
    break;

  case(7759):
  case(7577):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='UK';
    $header_data['city']='Edinburgh';

    $header_data['address2']='';
    $header_data['address3']='';
    break;

  case(71485):
    $skip_del_address=true;
    // $different_delivery_address=true;
    $header_data['country']='Spain';
    break;
    //  case(71923):
    //     $skip_del_address=true;
    //     $different_delivery_address=true;
    //     $header_data['country']='France';
    //     $header_data['address2']='';
    //     $header_data['address3']='';
    //     $header_data['address1']='Quenequen';
    //     $header_data['city']='Scrignac';
    //     $header_data['postcode']='France';
    //    break;
  case(32288):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Spain';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['address1']='CallaeMolins 22';
    $header_data['city']='Majorca';
    $header_data['postcode']='07560';

	
    break;

  case(22340):
  case(23385):
    $skip_del_address=true;
    $act_data['a1']='1779 Vermont Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Elk Grove Village';
    $act_data['country_d2']='Illinois';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='60007';
    break;
  case(14571):

    $act_data['a1']='Grange';
    $act_data['postcode']='';
    $act_data['town']='Kilmore';
    $act_data['country_d2']='Co Wexford';
    $act_data['country']='Ireland';
    $skip_del_address=true;
    break;

  case(24193):
  
    $act_data['a1']='28 Hodder Lane';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Framingham';
    $act_data['country_d2']='Massachusetts';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='01701';
    $header_data['address1']='1 Central Street';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['city']='Framingham';
    $header_data['postcode']='01701';
    $header_data['country']='USA';
    $header_data['country_d2']='Massachusetts';
    break;
  case(76804):
    $skip_del_address=true;

    $act_data['a2']='Petersfield Road';
    $act_data['a3']='';

    break;

  case(49012):
    $skip_del_address=true;
    $act_data['a1']='6708 Foothill Blvd';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Tujunga';
    $act_data['country_d2']='California';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='91042';
    break;
  case(49012):
    $skip_del_address=true;
    $act_data['a1']='6708 Foothill Blvd';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Tujunga';
    $act_data['country_d2']='California';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='91042';
    break;

  case(7699):
    $skip_del_address=true;
    $act_data['a1']='Flat 51';
    $act_data['a2']='Sina 20';
    $act_data['a3']='';
    $act_data['town_d1']='Engomi';
    $act_data['town']='Nicosia';
    $act_data['postcode']='2406';
    break;
  case(23335):
    $skip_del_address=true;
    $act_data['town']='Plainfield';
    $act_data['postcode']='46168';
    $act_data['country_d2']='In';
    break;
  case(30218):
  case(27832):
  case(35721):
  case(51911):
  case(70492):
  case(78129):
    $skip_del_address=true;
 
    $act_data['a1']='PO BOX 32112';
    $act_data['a2']='Galleria Plaza';
    $act_data['a3']='';
    $act_data['town']='Seven Mile Beach';
    $act_data['country_d1']='';
    $act_data['postcode']='';
    $act_data['country']='Cayman Islands';

    break;
  case(30532):
    $skip_del_address=true;
 
    $act_data['a1']='Calle Doña Romera No1 1oC ';
    $act_data['a2']='Getafe';
    $act_data['a3']='';
    $act_data['town']='Madrid';
    $act_data['postcode']='28901';
    $act_data['country']='Spain';

    break;

  case(30597):
    $skip_del_address=true;
 
    $act_data['town']='Pescia';
    $act_data['a2']='';
    $act_data['country_d1']='Tuscany';
    $act_data['country_d2']='Pistoia';
    break;

  
  case(23335):
    $skip_del_address=true;
    $act_data['a1']='725 Normandy Dr';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Euless';
    $act_data['country_d2']='Texas';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='76039';
    break;
  case(21453):
    $skip_del_address=true;
    $act_data['a1']='Hyland Plaza';
    $act_data['a2']='2152 South Highland Drive';
    $act_data['a3']='';
    $act_data['town']='Salt Lake City';
    $act_data['country_d2']='Utah';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='84106';
    break;
  case(20736):
    $skip_del_address=true;
    $act_data['a1']='3065 Saint James Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Boca Raton';
    $act_data['country_d2']='Florida';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='33434';
    break;
  case(19802):
    $skip_del_address=true;
    $act_data['a1']='15 View Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Miller Place';
    $act_data['country_d2']='New York';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='11764';
    break;
  case(21262):
    $skip_del_address=true;
    $act_data['a1']='Po Box 1047';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Townsville';
    $act_data['country_d2']='';
    $act_data['country_d1']='';
    $act_data['country']='Australia';
    $act_data['postcode']='4810';
    break;
  case(24400):
    $skip_del_address=true;
    $act_data['a1']='Rua de Angloa 36-2 (Esq)';
    $act_data['postcode']='4430-014';
    $act_data['town']='Vila Nova de Gaia';
    break;

  case(7173):
    $skip_del_address=true;
    $act_data['a1']='';
    break;
  case(14622):
    $skip_del_address=true;
    $act_data['a1']='12 Forest Dale';
    $act_data['a2']='Rivervalley';
    $act_data['a3']='';
    $act_data['town']='Swords';
    $act_data['country_d2']='Fingal';
    $act_data['country_d1']='';
    $act_data['country']='Ireland';
    $act_data['postcode']='';
    break;
  case(31843):
    $skip_del_address=true;
    $act_data['town']='Conley';
    $act_data['country_d2']='GA';
    $act_data['country']='USA';
    $act_data['postcode']='30288';
    break;
  case(68623):
    $skip_del_address=true;
    $act_data['a1']='Suite 51';
    $act_data['a2']='7 Essex Green Drive';
    $act_data['town']='Peabody,';
    $act_data['country_d2']='MA';
    $act_data['country']='USA';
    $act_data['postcode']='01960';
    break;
  case(8363):
    $skip_del_address=true;
    $act_data['country']='USA';

    break;

  case(22478):
    $skip_del_address=true;
    $act_data['postcode']='';
    break;

  case(70358):
  case(72026):
    $skip_del_address=true;
    $act_data['town']='Pittsfield';
    $act_data['country_d2']='MA';
    $act_data['country']='USA';
    $act_data['postcode']='01201';
    break;
  case(30211):
    $skip_del_address=true;
    $act_data['town']='Campbell'; 
    $act_data['country_d2']='California';
    $act_data['country']='USA';
    break;

  case(23293):
    $skip_del_address=true;
    $act_data['a1']='4544 Alhambra St';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='San Diego';
    $act_data['country_d2']='Texas';
    $act_data['country']='USA';
    $act_data['postcode']='92107';
    break;
  case(8558):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Rocklin';
    $act_data['country_d2']='California';
    $act_data['country']='USA';

    break;
  case(68689):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Bloomfield';
    $act_data['country_d2']='New Jersey';
    $act_data['country']='USA';

    break;

  case(64689):
    $skip_del_address=true;

    $act_data['country_d1']='French Polynesia';
    $act_data['country']='France';

    break;
  case(73721):
    $skip_del_address=true;
    $act_data['a1']='';
    $act_data['a2']='Camelot Longue Rue Clos';
    $act_data['a3']='Longue Rue, Burnt Lane';
    $act_data['town']='St Martins';

    $act_data['country_d2']='';
    $act_data['country_d1']='';
    $act_data['country']='Guernsey';
    $act_data['postcode']='GY4 6HE';
    

    break;

  case(63376):
    $skip_del_address=true;
    $act_data['country']='UK';

    

    break;
  case(25079):
  case(16597):

    $skip_del_address=true;
    $act_data['country']='Ireland';
    break;


  
  case(36910):
    $skip_del_address=true;
    $act_data['a1']='Calle Desamparados 5';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Denia';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
    $act_data['country']='Spain';
    $act_data['postcode']='03700';
    break;
  case(7054):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Tonsberg';
    $act_data['postcode']='3120';
    break;
  case(61835):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Ylistaro';
    break;
  case(52273):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town_d2']='Daeya-dong';
    $act_data['town']='Siheung-si';
    $act_data['country_d1']='Gyeonggi-do';
    break;
  case(52598):
     $act_data['country']='UK';
  case(37556):
    $skip_del_address=true;
    $act_data['a2']='Stadhoudershof 6';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Harmelan';
    $act_data['country']='Netherlands';
    $act_data['postcode']='3481HT';
    $skip_del_address=true;
    break;
  case(73612):
  case(76735):
    $skip_del_address=true;
    $act_data['postcode']='';
    $act_data['country_d1']='Curacao';
    
    break;
  case(61801):
    $skip_del_address=true;
    $act_data['postcode']='24076';
    $act_data['country_d1']='Virginia';
    $act_data['town']='Claudville';
    $act_data['a2']='';
    $act_data['a3']='';
    break;
  case(8660):
    $skip_del_address=true;
    $act_data['a2']='Llanfair Talhaiarn';

    break;

  case(76925):
    $skip_del_address=true;
    
    $act_data['town']='Westbury';
    $act_data['a1']='Unit C-3';
    $act_data['a2']='Z10900 609 Cantiague Rock Rd';
    $act_data['country_d1']='NY';
    break;
  case(35072):
    $skip_del_address=true;
    $act_data['postcode']='IV17 0QG';
    break;
  case(13908):
    $skip_del_address=true;
    $act_data['postcode']='NE63 5QW';
    $act_data['a2']='Rotary Parkway';
    break;
  case(35142):
    $skip_del_address=true;
    $act_data['postcode']='123812';
    break;
  case(43769):
    $skip_del_address=true;
    $act_data['postcode']='PE31 7QU';
    break;
  case(62196):
    $skip_del_address=true;
    $act_data['country_d1']='New South Wales';
    $act_data['town']='Bilambil Heights';
    $act_data['a2']='';
    $act_data['a3']='';
    break;
 case(71271):
$header_data['country']='Italy';
 break;
  case(30141):
    $act_data['country']='Lebanon';
    $act_data['town']='Beirut';
    $act_data['a1']='3rd Floor';
    $act_data['a2']='164 Debbas Street';
    $act_data['a3']='';
    $act_data['town_d1']='Saifi';
    $act_data['postcode']='';
    	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address2']='Ballafletcher Cottage';
	$header_data['address1']='NULL';
	$header_data['address3']='Peel Road';
	$header_data['city']='Braddan';
	$header_data['postcode']='IM4 4LD';
	$header_data['country']='Isle of Man';
    break;
  case(28500):
  case(36619):
    $skip_del_address=true;
    $act_data['country']='Lebanon';
    $act_data['town']='Beirut';
    $act_data['a1']='3rd Floor';
    $act_data['a2']='164 Debbas Street';
    $act_data['a3']='';
    $act_data['town_d1']='Saifi';
    $act_data['postcode']='';
    break;

   case(69405):
    $act_header['postcode']='GU12 4TD';
    break;
  case(71271):
     $act_header['country']='Italy';
    break;
  case(71485):
$act_act['country']='Spain';
    break;
  case(70511):
  case(7165):
  case(53811):
  case(8294):
  
  case(8411):
  case(12819):
  case(13954):
  case(16687):
  case(26028):
  case(72146):
  case(46797):
  case(35803):
  case(33867):
  case(28966):
  case(65549):
  case(52964):
  case(44185):
  case(39120):
  
  case(33821):
    
  case(69305):
    
  case(72754):
  case(68024):
  case(8877):
  
  case(6925):
  case(7272):
  case(7427):
  case(8370):
  case(9448):
  
  case(13003):
  case(53841):
  case(46248):
  case(47855):
  case(25079):
  case(36511):
  case(7414):
  
  case(7650):
  case(8441):
  case(8442):
  case(8503):
  case(8629):
  case(8664):
  case(7650):
  case(8044):
  case(8441):
  case(8442):
  case(8503):
  case(8703):
  case(8745):
  case(8906):
  case(7065):
  case(8061):
  case(12486):
  case(12864):
  case(13075):
  case(13317):
  case(13335):
  case(13598):
  case(14209):
  case(14308):
  case(14512):
  case(14650):
  case(15575):
  case(15805):
  case(15828):
  case(17573):
  case(17831):
  case(7225):
  case(16519):
  case(16486):
  case(16569):
  case(16877):
  case(18746):
  case(19563):
  case(19615):
  case(19652):
  case(19685):
  case(20201):
  case(20357):
  case(21422):
  case(21625):
  case(21915):
  case(22067):
  case(22362):
  case(22731):
  case(22818):
  case(22887):
  case(22906):
  case(22907):
  case(22929):
  case(22998):
  case(23246):
  case(23357):
  case(23579):
  case(23712):
  case(23756):
  case(12847):
  case(14261):
  case(17130):
  case(18010):
  case(19576):
  case(31020):
  case(31205):
  case(31371):
  case(31556):
  case(31745):
  case(32178):
  case(32289):
  case(32345):
  case(32606):
  case(33381):
  case(33771):
  case(35731):
  case(36867):
  case(37782):
  case(39441):
  case(39573):
  case(41943):
 case(45804):
 case(45914):
     case(50950):
  case(51025):
 case(51256):
case(52331):
case(52364):
case(53138):
  case(53384):
 case(54343):
 case(55154):
 case(55256):
 case(55566):
 case(57096):
case(59588):
case(59842):
case(47051):
case(29145):
  case(47825):
  case(15593):
  case(24266):
  case(25214):
 case(25305):
  case(25375):
  case(25977):
 case(25977):
  case(29392):
  case(29550):
    case(29710):
  case(29710):
case(30587):
case(30763):
case(31049):
  case(31677):
  case(33216):
case(33246):
case(33531):
  case(34812):
    case(35098):
      case(78012):
  case(69453):
 case(16121):
 case(36842):
 case(71485):
  case(64945):
  case(75294):
 case(76777):
 case(78383):
  case(78294):
  case(25453):
case(28990):

$skip_del_address=true;
   break;
  case(71485):
$skip_del_address=true;
 $act_data['country']='Spain';
 break;
case(35467):
case(44604):
  case(45817):
  case(50532):
  case(53698):
  case(53698):
  case(62036):
  case(73120):
$skip_del_address=true;
 $act_data['country']='Sweden';
 $act_data['postcode']='52495';
 $act_data['town']='Ljung';
 break;
  case(19937):
$skip_del_address=true;
 $act_data['country']='USA';
 $act_data['postcode']='55040';
 $act_data['town']='Isanti';
 $act_data['country_d1']='Minnesota';
 break;
 case(25712):
$skip_del_address=true;
 $act_data['country']='USA';
 $act_data['a2']='';

 $act_data['town']='Mililani';
 $act_data['country_d1']='Hawaii';
 break;


 case(74033):
  case(73998):
  case(74624):
$skip_del_address=true;
 $act_data['country']='USA';
 $act_data['postcode']='09142';
 $act_data['town']='APO, AE';
 break;


 case(64045):
  case(73723):

$skip_del_address=true;
 $act_data['a1']='34/1 Dolmen Court';
 $act_data['a2']='Dolmen Street';

 break;


  case(24771):
  
    $header_data['country']='UK';
 break;
 case(60300):
  $skip_del_address=true;
    $act_data['a1']='34 St Paul Street';
  break;
  case(37313):
    $act_date['a1']='Local 02';
    $act_date['a2']='Calle Coso 35';
    $act_date['address1']='N-2 Local 5';
    $act_date['address2']='Plaza Jose Maria Forquet';
    break;
  case(37966):
  case(39228):
     $act_date['address1']='N-2 Local 5';
    $act_date['address2']='Plaza Jose Maria Forquet';
    break;
case(71983):
  $skip_del_address=true;
$act_data['a1']='Casa 50';
 $act_data['a2']='Calzada del Hueso 151 ';
 $act_data['a3']='';
$act_data['town']='Mexico City';
$act_data['town_d1']='Coyoacan';
$act_data['town_d2']='Ex-Hacienda Coapa';
$act_data['country_d1']='Distrito Federal';
$act_data['country_d2']='';

 break;



 case(37105):
$skip_del_address=true;
 $act_data['a2']='';
 $act_data['a3']='';
 $act_data['postcode']='22';
 $act_data['town']='Dublin';
    break;
 case(39363):
$skip_del_address=true;
 $act_data['a2']='408 The Spa';
    break;
  case(76426):
   $skip_del_address=true;
    $act_data['postcode']='NR33 8NX';
    break;
 case(34947):
    $header_data['country']='UK';
    $header_data['postcode']='Bh15 3as';
     $header_data['address3']='';
    $header_data['city']='Poole';
    break;
   case(25885):
    $skip_del_address=true;
    $act_data['a2']='Stratham High Road';
    break;
  case(48227):
    $skip_del_address=true;
    $act_data['a1']='29-33 Newton Road';
    break;
   case(56219):
    $skip_del_address=true;
    $act_data['town']='Lerwick';
    break;
  case(56689):

    $header_data['country']='United Kingdom';
    break;
 case(51029):
    $skip_del_address=true;
    $act_data['a1']='Bulwark Shopping Centre';
    break;
  case(42294):
    $skip_del_address=true;
    $act_data['a1']='14 Peveril Street';
    break;
  case(51360):
    $skip_del_address=true;
    $act_data['a1']='Glebe Barn';
    break;
  case(51731):
    $skip_del_address=true;
    $act_data['a1']='Hay Castle';
    $act_data['a2']='Oxford Road';
    break;
      case(50680):
    $skip_del_address=true;
    $act_data['a1']='4 Castlefin Road';
    break;
case(46636):
    $skip_del_address=true;
    $act_data['a1']='47 Nightingales Drive';
    break;
  case(33181):
    $skip_del_address=true;
    $act_data['postcode']='BS4 3QF';
    break;
  case(33181):
    $header_data['postcode']='ML2 0RR';
    break;
  case(43858):
    $skip_del_address=true;
    $header_data['city']='Zagreb';
    $header_data['postcode']='10020';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['address1']='Siget 18C';

    break;

  case(20188):
    $skip_del_address=true;
    $act_data['a1']='1a Jubilee Terrace';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town_d2']='Crawcrook';
    $act_data['town']='Newcastle';
    $act_data['postcode']='NE40 4HL';

    break;
  case(16885):
    $skip_del_address=true;
    $act_data['a1']='Ellon Indoor Market';
    $act_data['a2']='71 Station Road';
    $act_data['a3']='';
    $act_data['town']='Ellon';
    $act_data['postcode']='AB41 9AR';

    break;
  case(19821):
    $skip_del_address=true;
    $act_data['a1']='3a Tudor Parade';
    $act_data['a2']='Berry Lane';
    $act_data['a3']='';
    $act_data['town']='Rickmansworth';
    $act_data['postcode']='WD3 4DF';

    break;
  case(16847):
    $skip_del_address=true;
    $act_data['postcode']='BT28 1TR';
    break;
  case(77342):
    $skip_del_address=true;
    $act_data['a1']='PO BOX 21';
    $act_data['a2']='30th Km Athens-Lavrio NTL Rd.';
    break;
  case(7912):
     $header_data['country']='UK';
    break;
  case(32329):
    $skip_del_address=true;
    $act_data['postcode']='EX39 2DX';
    break;
  case(28867):
 $act_data['a1']='N. Papanikolaou 6';
 case(43870):
  case(19295):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Petrou Fouriki & N. Papanikolaou 6';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;
  case(13086):
  case(13906):

    $skip_del_address=true;
    $act_data['postcode']='L12 0QY';
    break;
  case(16153):
    $skip_del_address=true;
    $act_data['town']='Castlederg';
    $act_data['postcode']='BT81 7AT';
    break;
  case(15996):
    $skip_del_address=true;
    $act_data['postcode']='HP13 6AZ';
    break;
  case(16002):
    $skip_del_address=true;
    $act_data['postcode']='HX1 3UZ';
    break;
  case(16033):
    $skip_del_address=true;
    $act_data['postcode']='G4 0TT';
    break;
  case(14040):
    $skip_del_address=true;
    $act_data['postcode']='LE10 1NL';
    break;
  case(18435):
    $skip_del_address=true;
    $act_data['postcode']='NG24 1UD';
    break;

  case(13086):
    $skip_del_address=true;
    $act_data['postcode']='S8 8AD';
    $act_data['town']='Sheffield';
    $act_data['a1']='NULL';
    break;

  case(8629):
    $skip_del_address=true;
    $act_data['postcode']='BN1 4AZ';

    break;
  case(8044):
    $skip_del_address=true;
    $act_data['country_d1']='Tipperary';
    $act_data['country_d2']='';

    break;
  case(70883):
    $skip_del_address=true;
    $act_data['a1']='10 Rue Bartholmy';
    $act_data['town']='Howald';
    break;
  case(51426):
  case(54164):
  case(64439):

    $skip_del_address=true;
    $act_data['a3']='';
    $act_data['a2']='';
    $act_data['town']='Tandragee';
    break;
  case(17445):
  case(18545):
  case(18792):
  case(19291):

  case(32318):
  case(20756):

    $skip_del_address=true;
    $act_data['town']='Emmen';
    $act_data['postcode']='7823PM';
    break;
  case(70819):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Spijkenisse';
    $act_data['country_d1']='Zuid-Holland';
    break;

  case(70731):
    $skip_del_address=true;
    $act_data['a1']='Ashford';
    $act_data['town']='Ballagh';
    $act_data['country_d2']='Limerick';
    break;
  case(44600):
  case(46505):
  case(50287):
  case(70138):
  case(71302):
  case(73580):
  case(75313):
    $skip_del_address=true;
    $act_data['town']='Algarve';
    $act_data['country_d2']='';
    $act_data['postcode']='495-8400';


    break;
   case(61118):
    $skip_del_address=true;
    $co='Hotel Garbe';
    break;
  case(70582):
    $skip_del_address=true;
    $act_data['a1']='P2 Casa 4 Urb Calas Picas';
    $act_data['a2']=' Av Pont Den Gil';
    $act_data['country_d1']='Balearic Islands';
    $act_data['country_d2']='Balearic Islands';
    $act_data['town']='Ciutadella';
    break;

  case(72265):
    $skip_del_address=true;
    $act_data['country']='Australia';
    $act_data['country_d1']='Western Australia';
    $act_data['a1']='Unit 4';
    $act_data['a2']='39 Shakespeare Avenue';
    $act_data['a3']='';
    $act_data['town']='Yokine';
    break;
  case(71925):
    $act_data['country_d2']='';
    BREAK;
  case(18454):
    $act_data['country']='Saudi Arabia';
    break;
case(62426):
  $skip_del_address=true;
  $act_data['country']='Spain';
  $act_data['town']='Marbella';
  $act_data['a3']='';

    break;
  case(72737):
    $skip_del_address=true;
    $act_data['country_d1']='Midi-Pyrénées';
    $act_data['country_d2']='Tarn';
    $act_data['town']='Soreze';
    $act_data['a2']='';

    break;

  case(60909):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Voru';
    $act_data['postcode']='65610';
    break;

  case(35006):
    $skip_del_address=true;
    $act_data['a1']=' 52 High Street';

    break;


  case(27966):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='New Windsor';
    $act_data['country_d2']='NY';
    $act_data['country']='USA';
    break;

  case(61990):
  case(62814):
$act_data['postcode']='G31 5NZ';
 $act_data['country_d2']='Lanarkshire';
 $act_data['country_d1']='';

 $skip_del_address=true;
    break;

  case(29948):
    $act_data['town']='Stillorgan';
    $skip_del_address=true;
    break;
  case(65336):
    $act_data['town']='Totland Bay';
     $act_data['country_d1']='Isle of Wight';
    $skip_del_address=true;
    break;
  case(68277):
    $act_data['town']='Merthyr Tydfil';
    
     $act_data['country_d1']='';
    $skip_del_address=true;
    break;
 case(68830):
    $header_data['postcode']='4051';
    $header_data['city']='Sousse';
     $header_data['country']='Tunisia';
$header_data['address3']='Khazama Est';
    break;
 case(75064):
  case(75838):
  case(75954):
case(77065):
  case(77502):
$act_data['a1']='16 Market Square';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town']='Enniscorthy';
 $act_data['postcode']='';
$header_data['country_d2']='Wexford';
$header_data['country_d1']='';
$skip_del_address=true;
   break;
  case(54756):
$act_data['a1']='Suite 2000';
$act_data['a2']='';
 $act_data['a3']='1200 Route 22 East';
$act_data['town']='Bridgewater';
 $act_data['postcode']='08807';
$act_data['country_d1']='NY';
$act_data['country']='USA';
$skip_del_address=true;
   break;
 case(60522):
$act_data['a1']='';
$act_data['a2']='Apt 1a';
 $act_data['a3']='1 East 93rd Street';
 $act_data['postcode']='10128';
$act_data['country_d1']='NY';
$act_data['country']='USA';
$skip_del_address=true;
   break;
  case(19715):
$header_data['country']='UK';
 break;

case(61637):
case(63251):
case(65048):
case(72539):
 $skip_del_address=true;
$act_data['a1']='17 Belton Park Avenue';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Donnycarney';
$act_data['town']='Dublin';
 $act_data['postcode']='';
$header_data['country_d2']='';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;
case(44898):
case(45624):
case(45978):
case(46181):
case(46834):
case(47481):
case(48108):
case(48502):
case(48694):
case(51512):
case(59554):
case(59870):
case(60604):
case(61822):
case(62979):
case(65457):
case(67668):
case(73319):
 $skip_del_address=true;
$act_data['a1']='23 Elmdale Crescent';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Ballyfermot';
$act_data['town']='Dublin';
 $act_data['postcode']='';
$header_data['country_d2']='';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;
case(27223):
case(27527):
case(28147):
case(36229):
case(38735):
case(40459):
case(48202):
case(55349):
case(62007):

 $skip_del_address=true;
$act_data['a1']='28 The Dunes Somerville';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Somerville';
$act_data['town']='Tramore';
 $act_data['postcode']='';
$header_data['country_d2']='Waterford';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;

case(65485):
  case(68764):
 $skip_del_address=true;
$act_data['a1']='21 Kilmore Avenue';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Artane';
$act_data['town']='Dublin';
 $act_data['postcode']='D5';
$header_data['country_d2']='';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;

 case(44490):
  case(45495):
case(46899):
case(49377):
  case(50659):
  case(52231):
  case(53098):
case(65835):
 $skip_del_address=true;
$act_data['a1']='22 Connolly Avenue';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='';
$act_data['town']='Newcastle West';
 $act_data['postcode']='D5';
$header_data['country_d2']='Limerick';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;
case(39427):
case(39778):
case(40175):
case(40476):
case(41488):
case(42178):
case(42698):

 $skip_del_address=true;
$act_data['a1']='ST Helier';
$act_data['a2']='';
 $act_data['a3']='22 Grange Lawn';
$act_data['town_d2']='';
$act_data['town']='Waterford';
 $act_data['postcode']='D5';
$header_data['country_d2']='Waterford';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;


case(63234):
    $act_data['town']='Hull';
$act_data['postcode']='HU5 5PL';
 $act_data['a2']='';
 $act_data['a3']='';
$act_data['country_d2']='';
 $act_data['country_d1']='';
    $skip_del_address=true;
    break;
  case(68683):
  case(68578):
  case(68368):
  case(54101):
  case(54717):
  case(65907):
 case(63790):
  case(62631):
 case(64383):
case(66006):
  case(67614):
 case(68914):
  case(69563):
  case(69656):
  case(69812):
  case(69817):
  case(70340):
  case(70378):
  case(70390):
  case(70431):
  case(70953):
 case(71002):
  case(71036):
  case(71131):
  case(71517):
  case(71746):
 case(72356):
  case(73284):
  case(73360):
  case(74227):
  case(74371):
case(14622):
case(14960):
  case(57083):
    $skip_del_address=true;
    break;
case(43780):
$act_data['a1']='Tri Na Ri';
 $act_data['a2']='';
 $act_data['a3']='Slieve Rua';
    $skip_del_address=true;
    break;

case(30793):

case(31080):
  $act_data['country']='Sweden';
    $skip_del_address=true;
    break;

  case(73833):
    $header_data['city']='Calabasas';
    $header_data['country_d2']='CA';
     $act_data['town']='Beverly Hills';
    $act_data['country_d2']='CA';
    break;
  case(72481):
    $header_data['postcode']='CY-4529';
    break;
 case(71108):
   $act_data['postcode']='2820-564';
   $skip_del_address=true;
   break;
 case(71697):
   $act_data['a1']='10-12 Station Road';
   $skip_del_address=true;
   break;
   case(74146):
   $act_data['postcode']='34 - 120';
   $skip_del_address=true;
   break;
  case(71485):
   $act_data['country']='Spain';
   $skip_del_address=true;
   break;
 case(30793):
   $act_data['country']='Sweden';
   $skip_del_address=true;
   break;
case(50918):
   $act_data['town']='St Lawrence';
   $skip_del_address=true;
   break;

  case(67934):
  $act_data['a1']='10  Rue Bartholmy';
  $skip_del_address=true;
  break;

case(64005):
case(72481):
  case(53207):
 $skip_del_address=true;
 $act_data['a1']='PO BOX  50715';
 $act_data['a3']='38-E Karaiskaki Street';
 $act_data['a2']='Kanika Alexander Centre';
 break;
case(13164):
$header_data['address1']='C/O Di Roberta Vianello & Co';
$header_data['address2']='Via Bissa 11/2';
$header_data['city']='Mestre';

    break;
case(65399):
$header_data['address1']='via Zanotto 20/3';
$header_data['address2']='';
$header_data['address3']='';

$header_data['city']='Mestre';
 $header_data['postcode']='30173';
    break;
  case(39546):
case(40197):
$skip_del_address=true;
$act_data['a1']='Acharavi Cafenio Mitsuras 1';
$act_data['a2']='';
$act_data['a3']='';
  break;

case(39100):
  case(40278):
  case(44048):
  case(73689):
$skip_del_address=true;
$act_data['a1']='Odos Nickos Kazantzakis';
$act_data['a2']='';
$act_data['a3']='';
 $act_data['town']='Kato Gouves';
$act_data['country_d1']='Crete';
 $act_data['country_d2']='';
  break;
case(44851):
$skip_del_address=true;
$act_data['a1']='PO BOX 493491';
$act_data['a2']='';
$act_data['a3']='';
 $act_data['town']='Velanidia';
$act_data['country_d1']='Peloponnisos';
 $act_data['country_d2']='Laconia';
  break;
case(19463):
 $act_data['town']='Luqa';
$act_data['postcode']='Lqa05';
$skip_del_address=true;
break;
case(50288):
$act_data['a2']='';
$act_data['town']='Ozu';
$act_data['country_d1']='Shikoku';
 $act_data['country_d2']='Ehime';
 $act_data['postcode']='795-0064';
 $skip_del_address=true;
 break;
case(70693):
$act_data['country_d1']='Shikoku';

 $skip_del_address=true;
 break;

case(65399):


$act_data['a1']='66B Campbell Street';
    $skip_del_address=true;
    break;
  case(59505):
  case(60970):
  case(68058):
  case(65639):
  case(62012):
  case(63810):
  case(71447):
  case(74506):

    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='Read Coat Express';
    $header_data['address2']='Global House';
    $header_data['address3']='Manor Court';
    $header_data['city']='Crawley';
    $header_data['postcode']='RH10 9PY';
    $header_data['country']='UK';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    break;	
  case(39299):
  $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
     $skip_del_address=true;
     break;
  case(68922):
    $header_data['city']='Turre';
    $header_data['postcode']='04639';
    $header_data['country_d2']='Almeria';
    $header_data['country_d1']='Adalucia';
    $header_data['country']='Spain';
    break;
  case(69405):
 $header_data['country']='UK';
  break;
 case(69327):
  case(72295):
  case(74467):
 $act_data['a3']='Niittykuja 2 A2';
 $act_data['a1']='';
    $act_data['a2']='';
    $act_data['town']='Rovaniemi';
     $act_data['country_d1']='';
     $act_data['country_d2']='';
  $skip_del_address=true;
  break;

 case(79117):
 $act_data['a3']='Ste 310';
 $act_data['a1']='PMVB 284';
 $act_data['a2']='8168 Crown Bay Marine';
 $act_data['town']='St Thomas';
 $act_data['country_d1']='';
 $act_data['country_d2']='';
 $act_data['postcode']='802';
 $act_data['country']='Virgin Islands, U.S.';
 $skip_del_address=true;
 break;


 case(23195):
  case(37248):
  case(22234):
 $act_data['a3']='Banegaardsgade 1, 2., -18';
    $act_data['a1']='NULL';
    $act_data['a2']='NULL';
  $skip_del_address=true;
  break;
  case(14648):

    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address2']='';
    $header_data['city']='Ambleside';
    $header_data['country']='UK';
    $act_data['a2']='';
    $act_data['town']='Rydal';

    break;
  case(69467):
     $header_data['postcode']='6';
	 break;
  case(69453):
    $act_data['a1']='Unit G06 Clerkenwell Workshops';
    break;
  case('23230'):
    $header_data['city']='Dignes Les Bains';
    $header_data['postcode']='04000';
    $header_data['country']='France';
    break;

  case('50411'):
    $header_data['city']='Hammam Sousse';
    $header_data['postcode']='4011';
    $header_data['country']='Tunisia';
    break;

  case('50370'):
  $skip_del_address=true;
    $act_data['a1']='Woodhouse';
    $act_data['a2']='3 Kirks Close';

    break;
  case(73501):
      $skip_del_address=true;
       $act_data['country']='UK';
    break;
 case(36140):
  $skip_del_address=true;
    $act_data['town']='St Leonards On Sea';


    break;
 case(77185):
  $skip_del_address=true;
  $act_data['town']='St. Thomas';
  $act_data['country']='Virgin Islands, U.S.';

    break;


  case(30563):
  case('24356'):
    $header_data['address2']='';
    $header_data['postcode']='06880';
    $header_data['city']=mb_ucwords('KÄRRBY');
    $header_data['country']='FINLAND';

    break;
 case('25026'):
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['country_d2']='Lagos';
    $header_data['postcode']='1800-174';
    $header_data['city']='Praia da Luz';
    $header_data['country']='Portugal';

    break;

    break;
 case(29541):
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['country_d2']='';
    $header_data['postcode']='07560';
    $header_data['city']='Majorca';
    $header_data['country']='Spain';

    break;
  }







 if(
(
 

     preg_match('/^Via Bssa$|Via Bssa.*11/i',$header_data['address1'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$header_data['address2'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$header_data['address3'])
     )

) { 
      $header_data['country']='Italy'; 
      $header_data['city']='Mestre';
      $header_data['postcode']='30173';
      $header_data['country_d1']=''; 
      $header_data['country_d2']=''; 
      $header_data['address1']='Via Bssa, 11'; 
      $header_data['address2']='';
      $header_data['address3']='';
   }
//print_r($act_data);
 //print_r($header_data);
 //exit;


 if(preg_match('/^M/i',$header_data['postcode']) and $header_data['city']=='Manchester'){
    $header_data['country']='UK';
  }

  // ============ EXEPTIONS
  //print_r($act_data);

 
  
  if($header_data['address2']=='Arundel' and $header_data['city']==''){
    
    $header_data['city']=$header_data['address2'];
    $header_data['address2']='';
  }
  


//      print "A1 ".$header_data['address1']."\n";
//       print "A2 ".$header_data['address2']."\n"; 
//      print "TO ".$header_data['city']."\n";
//     print $skip_del_address."PO ".$header_data['postcode']."\n";
  
//    print_r($act_data);

  //exit;
  
  if(!$skip_del_address){
    

    if($header_data['postcode']=='07760 Ciutadella'){
      $header_data['town']='Ciutadella';
      $header_data['postcode']='07760';
    }
    
    if(!(
	 _trim(strtolower($act_data['a1']))==_trim(strtolower($header_data['address1'])) and 
	 _trim(strtolower($act_data['a2']))==_trim(strtolower($header_data['address2'])) and 
	 _trim(strtolower($act_data['town']))==_trim(strtolower($header_data['city'])) and 
	 (
	  
	  _trim(strtolower($act_data['postcode']))==_trim(strtolower($header_data['postcode'])) or 
	  _trim(strtolower($act_data['country']).' '.strtolower($act_data['postcode']))==_trim(strtolower($header_data['postcode']))
	  )  
	 
	 )
       
       )
      $different_delivery_address=true;
    

    if($different_delivery_address){ 
      //	print "cacacacacacacacacacaca";
    }
      //print "xxxxxxxxxxxxxxxxxxxxx";
      
 //    if($different_delivery_address and $act_data['town']!=''){ 
//       if(strtolower($act_data['a1'])==strtolower($header_data['address1']) and  strtolower($act_data['a2'])==strtolower($header_data['address2'])  and preg_match('/'.$act_data['town'].'/i',strtolower($header_data['city'])))
// 	$different_delivery_address=false;
//     }
    // check if a country is a valid country and if it is not assume uk
   

    if(strtolower($header_data['postcode'])== strtolower($act_data['country']) and $act_data['country']!=''){
      if(strtolower($header_data['city'])==strtolower($act_data['postcode'].' '.$act_data['town']))
	$different_delivery_address=false;
    }












    $sql=sprintf("select `Country Key` as id from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($header_data['country']),prepare_mysql($header_data['country']));
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if(!$row = mysql_fetch_array($result, MYSQL_ASSOC)) 
      $header_data['country']=$act_data['country'];
      
    
    
      
  }

    
 
  if(preg_match('/South Afrika$|South Africa$/i',$header_data['postcode'])){
    $header_data['country']='South Africa';
    $header_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$header_data['postcode']));
  }

 

  if(preg_match('/N-5353 Straum/i',$header_data['postcode'])){
    $header_data['postcode']='N-5353';
    $header_data['city']='Straum';
  }


 if(preg_match('/30173 Mestre Ve/i',$header_data['address3'])){
    $header_data['postcode']='30173';
    $header_data['city']='Mestre';
    $header_data['address3']='';
    $header_data['country_d1']='Veneto';
    $header_data['country_d2']='Venice';

  }
if(preg_match('/Mrs Roberta Vianello/i',$header_data['address1'])){
    $header_data['address1']='';

  }



  if(preg_match('/United States$/i',$header_data['postcode'])){
    $header_data['country']='USA';
    $header_data['postcode']=_trim(preg_replace('/United States$/i','',$header_data['postcode']));
  }

 

  if(preg_match('/03837 Muro de Alcoy/i',$header_data['city'])){
    $header_data['country']='Spain';
    $header_data['country_d1']='Valencia';
    $header_data['country_d2']='Alicante';
    $header_data['postcode']='03837';
    $header_data['city']='Muro de Alcoy';
  }




  $header_data['postcode']=_trim( $header_data['postcode']);


 
  //  print_r($header_data);
 if(preg_match('/^GY\d/i',$header_data['postcode'])){
    $header_data['country']='Guernsey';
  }

  if(preg_match('/^GY\d/i',$act_data['postcode'])  and ($act_data['country']=='' or $act_data['country']=='Guernsey')){
    $act_data['country']='Guernsey';
    if($act_data['town']=='Guernsey')
      $act_data['town']='';

    if($act_data['a2']=='Les Baissieres, St Peter Port'){
      $act_data['a2']='Les Baissieres';
      $act_data['town']='St Peter Port';
    }
     


  }

  if( preg_match('/^je\d/i',$header_data['postcode'])  and $header_data['country']=='' ){
    $header_data['country']='Jersey';
    if($header_data['city']=='Jersey')
      $header_data['city']='';
  }
  if( preg_match('/^im\d/i',$header_data['postcode'])  and $header_data['country']=='' ){
    $header_data['country']='Isle of Man';

    if($header_data['address2']=='Ramsey'){
      $header_data['city']='Ramsey';
      $header_data['address']='';
    }
  }
 


  

    
 
  

  

  //  print $act_data['contact']." >>> $extra_contact   \n ";
    
  if($act_data['name']!=$act_data['contact'] )
    $tipo_customer='Company';
  else{	  
    $tipo_customer='Person';
      
      
      
  }
  
  //print_r($act_data);
  // print_r($header_data);

  //-----------------------------------------
  if(!isset($act_data['town_d1']))
    $act_data['town_d1']='';
  if(!isset($act_data['town_d2']))
    $act_data['town_d2']='';

 


 
  if(preg_match('/@/',$act_data['country'])){
    $act_data['country']='';

    if( checkPostcode($act_data['postcode'])  )
$act_data['country']='united kingdom';
  }
  
 if(preg_match('/@/',$header_data['country'])){
    $header_data['country']='';

    if( checkPostcode($header_data['postcode'])  )
$header_data['country']='united kingdom';
  }


  if(preg_match('/(.*\s+|^)ibiza\s*$/i',$act_data['town'])){
    $act_data['country']='Spain';
  }
    if(preg_match('/(.*\s+|^)ibiza\s*$/i',$header_data['city'])){
    $header_data['country']='Spain';
  }

  $address_raw_data=get_address_raw();
  $address_raw_data['address1']=$act_data['a1'];
  $address_raw_data['address2']=$act_data['a2'];
  $address_raw_data['address3']=$act_data['a3'];
  $address_raw_data['town']=$act_data['town'];
  $address_raw_data['town_d1']=$act_data['town_d1'];
  $address_raw_data['town_d2']=$act_data['town_d2'];
  $address_raw_data['country_d2']=$act_data['country_d2'];
  $address_raw_data['postcode']=$act_data['postcode'];
  $address_raw_data['country']=$act_data['country'];
  if(isset($act_data['country_d1']))
    $address_raw_data['country_d1']=$act_data['country_d1'];


  $shop_address_data=$address_raw_data;
    
  $extra_id1=$act_data['act'];
  $extra_id2=$shop_address_data['postcode'];

  //  print "$different_delivery_address xxx";
  if($different_delivery_address){

 if(preg_match('/^c\/o/i',$header_data['address1'])){
    $co=$header_data['address1'];
    $header_data['address1']='';
  }
 if(preg_match('/^c\/o/i',$header_data['address2'])){
    $co=$header_data['address2'];
    $header_data['address2']='';
  }
 if(preg_match('/^c\/o/i',$header_data['address3'])){
    $co=$header_data['address3'];
    $header_data['address3']='';
  }

    $address_raw_data_del=get_address_raw();
    $address_raw_data_del['address1']=$header_data['address1'];
    $address_raw_data_del['address2']=$header_data['address2'];
    $address_raw_data_del['address3']=$header_data['address3'];
    $address_raw_data_del['town']=$header_data['city'];
    $address_raw_data_del['postcode']=$header_data['postcode'];
    $address_raw_data_del['country_d2']=$header_data['country_d2'];
    $address_raw_data_del['country_d1']=$header_data['country_d1'];
    $address_raw_data_del['country']=$header_data['country'];


    //print_r($address_raw_data_del);
    $del_address_data=$address_raw_data_del;
   
      
    $different_del_address=true;


    

    if($del_address_data['country']=='' and preg_match('/^je/i',$del_address_data['postcode'])  ){
      $del_address_data['country']='Jersey';
    }
    if($shop_address_data['country']=='' and preg_match('/^je/i',$shop_address_data['postcode'])  ){
      $shop_address_data['country']='Jersey';
    }
    
    if($shop_address_data['country']=='' and preg_match('/^china$/i',$shop_address_data['postcode'])  ){
      $shop_address_data['country']='China';
      $shop_address_data['postcode']='';

    }
if($del_address_data['country']=='' and preg_match('/^china$/i',$del_address_data['postcode'])  ){
      $del_address_data['country']='China';
      $del_address_data['postcode']='';

    }

    $a_diff=array_diff_assoc($del_address_data,$shop_address_data);

    if(isset($a_diff['country_d1_id']))
      unset($a_diff['country_d1_id']);
    if(isset($a_diff['country_d2_id']))
      unset($a_diff['country_d2_id']);
    //  print"***";

//print array_key_exists('postcode',$a_diff)."\n";

  


       foreach($a_diff as $key=>$value){
	 //print $del_address_data[$key]."** \n";
	 if(strtolower($del_address_data[$key])==strtolower($shop_address_data[$key]))
	   unset($a_diff[$key]);
       }
       if(count($a_diff)==0){
	 $different_del_address=false;
	 print "Same address\n";
       }
       
       //  print_r($del_address_data);
       // print_r($shop_address_data);



       // print_r($shop_address_data);
       //exit;   
       if(preg_match('/ireland/i',$shop_address_data['country'])){
   if(count($a_diff)==1 and array_key_exists('postcode',$a_diff))
     $different_del_address=false;
   if(count($a_diff)==1 and array_key_exists('country_d2',$a_diff))
     $different_del_address=false;
    if(count($a_diff)==2 and array_key_exists('country_d2',$a_diff) 
       and array_key_exists('postcode',$a_diff))
     $different_del_address=false;

 }


    if(count($a_diff)==2){
    
      if(array_key_exists('postcode',$a_diff) and array_key_exists('country_d2',$a_diff)
	 and ($shop_address_data['country']=='' or 
	      $shop_address_data['country']=='UK' or 
	      $shop_address_data['country']=='United Kingdom' or
	      $shop_address_data['country']=='Channel Islands' 

	      )){
	//	print "PC of the del address taken (a)\n";
	$different_del_address=false;
	$shop_address_data['postcode']=$del_address_data['postcode'];
      }
    }


  










    if(count($a_diff)==1){
    
      if(array_key_exists('postcode',$a_diff) 
	 and ($shop_address_data['country']=='' or 
	      $shop_address_data['country']=='UK' or 
	      $shop_address_data['country']=='United Kingdom' or
	      $shop_address_data['country']=='Channel Islands' 
	      )){
	//	print "PC of the del address taken\n";
	$different_del_address=false;
	$shop_address_data['postcode']=$del_address_data['postcode'];
      }
      elseif(array_key_exists('country_d2',$a_diff) 
	     or array_key_exists('country_d1',$a_diff) 
	     ){
	//print "D2 x of the del address taken\n";
	$different_del_address=false;

      }

    }
    //    print_r($shop_address_data);
    //print_r($del_address_data);
    //print "xca";
    //exit;
  }else{
    $del_address_data=$shop_address_data;
    $different_del_address=false;
  }





  //  $country_id=$shop_address_data['country_id'];


 

  $email_data=guess_email($act_data['email']);
  
  // print_r($email_data);
  //print "$tipo_customer\n";
  //print_r($act_data);
    
  $shop_address_data['default_country_id']=30;

  if(isset($act_data['act']))
    $customer_data['Customer Old ID']=$act_data['act'];
  else
    $customer_data['Customer Old ID']='';
     
  if($del_address_data['country']=='')
      $del_address_data['country']='United Kingdom';
    if($shop_address_data['country']=='')
      $shop_address_data['country']='United Kingdom';

 
  
  $customer_data['type']=$tipo_customer;
  $customer_data['contact_name']=$act_data['contact'];
  $customer_data['company_name']=$act_data['name'];
  $customer_data['email']=$email_data['email'];
  $customer_data['telephone']=_trim($act_data['tel']);
  $customer_data['fax']=$act_data['fax'];
  $customer_data['mobile']=$act_data['mob'];
  $customer_data['address_data']=$shop_address_data;
  $customer_data['address_data']['type']='3line';

  $customer_data['address_data']=$shop_address_data;
  $customer_data['address_data']['type']='3line';
  $customer_data['address_data']['name']=$act_data['contact'];
  $customer_data['address_data']['company']=$act_data['name'];
  $customer_data['address_data']['telephone']=_trim($act_data['tel']);

  $customer_data['has_shipping']=true;
  if($customer_data['has_shipping']){
    $del_address_data['default_country_id']=30;
    $customer_data['shipping_data']=$del_address_data;

    $customer_data['shipping_data']['name']=$header_data['customer_contact'];
    $customer_data['shipping_data']['company']=$header_data['trade_name'];


  


    $_tel=preg_split('/ /',$header_data['phone']);
    $email=$_tel[count($_tel)-1];
    if(preg_match('/@/i',$email)){

      $email=preg_replace('/\/com$/','.com',$email);
      $email=preg_replace('/\//','',$email);


      $tel=_trim(preg_replace('/'.$email.'/','',$header_data['phone']));

    $email=_trim($email);
    }else{
      $email='';
      $tel=$header_data['phone'];
      
    }
    $tel=_trim(preg_replace('/^\s*\[\s*1\s*\]\s*/','',$tel));
    // print "***** $tel\n";
    $customer_data['shipping_data']['telephone']=$tel;
    $customer_data['shipping_data']['email']=$email;
    $customer_data['shipping_data']['type']='3line';
    
  }

  //  $customer_data['other_id']=$act_data['act'];

  //$customer_data['address_data']=
  
    if(isset($act_data['act']))
    $customer_data['Customer Old ID']=$act_data['act'];
  else
    $customer_data['Customer Old ID']='';
      
  
    //    print_r($customer_data);
  return $customer_data;



  

}







function read_header($raw_header_data,$map_act,$y_map,$map,$convert_encoding=true){


 
  //$new_mem=memory_get_usage(true);
  //    print"x$new_mem x ";
     
  $act_data=array();
  $header_data=array();
  //first read the act part

  $raw_act_data=array_shift($raw_header_data);
  // print_r($raw_act_data);
  if($raw_act_data){

    foreach($raw_act_data as $key=>$col){
      if($convert_encoding)
	$cols[$key]=mb_convert_encoding($col, "UTF-8", "ISO-8859-1");
      else
	$cols[$key]=$col;
    }
 
   $act_data['customer_id_from_inikoo']=0;
    if($cols[65]=='inikoo')
    $act_data['customer_id_from_inikoo']=1;
 
  //       print_r($cols);
   // exit;
    $act_data['name']=mb_ucwords($cols[$map_act['name']]);
    $act_data['contact']=mb_ucwords($cols[$map_act['contact']]);
    if($act_data['name']=='' and $act_data['contact']!='') // Fix only contact
      $act_data['name']=$act_data['contact'];
    $act_data['first_name']=mb_ucwords($cols[$map_act['first_name']]);
    $act_data['a1']=mb_ucwords($cols[$map_act['a1']]);
    $act_data['a2']=mb_ucwords($cols[$map_act['a2']]);
    $act_data['a3']=mb_ucwords($cols[$map_act['a3']]);
    $act_data['town']=mb_ucwords($cols[$map_act['town']]);
    $act_data['country_d2']=mb_ucwords($cols[$map_act['country_d2']]);
    $act_data['postcode']=$cols[$map_act['postcode']];
   
    $act_data['country']=mb_ucwords($cols[$map_act['country']]);
    $act_data['tel']=$cols[$map_act['tel']];
    $act_data['fax']=$cols[$map_act['fax']];
    $act_data['mob']=$cols[$map_act['mob']];
    $act_data['source']=$cols[$map_act['source']];
    $act_data['act']=$cols[$map_act['act']];
    $act_data['email']=$cols[count($cols)-1];
    $act_data['country_d1']='';
    //  if($act_data['a1']==0)$act_data['a1']='';
    //if($act_data['a2']==0)$act_data['a2']='';
    //if($act_data['a3']==0)$act_data['a3']='';

      

  }
  
  // print $raw_header_data[9][5]." $map\n";
  //  print_r($map);
  
  //  print_r($raw_header_data);

  


  

  foreach($map as $key=>$map_data){
    if($map_data){
      //     print "$key  \n";
      //  print_r($raw_header_data);

      $_data=$raw_header_data[$map_data['row']][$map_data['col']];
      //      print "**** $key ".$map_data['row']." ".$map_data['col']." $_data \n";
      
      if($convert_encoding)
	$_data=mb_convert_encoding($_data, "UTF-8", "ISO-8859-1");

      
      if(isset($map_data['tipo']))
	$tipo=$map_data['tipo'];
      else
	$tipo='';
      switch($tipo){
      case('name'):
	$_data=_trim($_data);
	if($_data=='0')$_data='';
	$header[$key]=$_data;

	break;
      case('name'):
	$_data=_trim($_data);
	if($_data=='0')$_data='';
	$header[$key]=mb_ucwords($_data);

	break;
      case('date'):

	$header[$key]=date("Y-m-d",mktime(0, 0, 0, 1 , $_data-1, 1900));
	break;
      default:
	$header[$key]=$_data;
	break;
      }
    }else
      $header[$key]='';
  }
  
  if($header['feedback']=='SinBinBoth'){
    $header['feedback']=1;
  }elseif($header['feedback']=='SinBinPick'){
    $header['feedback']=2;
  }elseif($header['feedback']=='SinBinPack'){
    $header['feedback']=3;
  }else
     $header['feedback']=0;
  

  $new_mem=memory_get_usage(true);
  // print"x$new_mem x ";
    

  return array($act_data,$header);

}



function read_records($handle_csv,$y_map,$number_header_rows){



  $first_order_bonus=false;
    
  $re_order=true;
  if(isset($y_map['no_reorder']) and $y_map['no_reorder'] )
    $re_order=false;

  $header=array(false);
  $products=array();
  $act=false;
  $row=0;
  while(($cols = fgetcsv($handle_csv))!== false){

    if($row<$number_header_rows){// is a header data
      $header[]=$cols;
    }else{
      //      i
   //    if(isset($cols[3])){
// 	if(preg_match('/wsl-1513/i',$cols[3])  ){
// 	  print_r($cols);
// 	print $y_map['bonus']."\n ";
// 	}
//       }
      // print count($cols)."\n";




      if(count($cols)<$y_map['discount'])
	continue;



      if(preg_match('/regalo de bienvenida/i',$cols[$y_map['description']]))
	$first_order_bonus=true;

      //  if($cols[$y_map['code']]=='Pack-29')
      //	print $y_map['bonus'];
     
      if(
	 (
	    


	  $cols[$y_map['code']]!=''
	  and (is_numeric($cols[$y_map['credit']]) or $cols[$y_map['discount']]==1   )
	  and $cols[$y_map['description']]!='' 
	  and (is_numeric($cols[$y_map['price']]) or $cols[$y_map['price']]==''  ) 
	  and (  ( is_numeric($cols[$y_map['order']])   and  $cols[$y_map['order']]!=0   )   
		 or ( is_numeric($cols[$y_map['reorder']])   and  $cols[$y_map['reorder']]!=0   and $re_order   )  
		 or ( is_numeric($cols[$y_map['bonus']])   and  $cols[$y_map['bonus']]!=0   ) )  
	  )or (preg_match('/credit/i',$cols[$y_map['code']])   and  $cols[$y_map['price']]!='' and  $cols[$y_map['price']]!=0  )
	 ){


	


// 	if($cols['units']==1 or $cols['units']='')
// 	  $cols['name']=$cols['description'];
// 	else
// 	  $cols['name']=$cols['units'].'x '.$cols['description'];

	$cols['fob']=$first_order_bonus;
	$products[]=$cols;
      }else if(preg_match('/^public\d*$|^nic$/i',$cols[0])  )
	$header[0]=$cols;
     
    }
    $row++;
  }
  // print_r($products);
  // exit;
  return array($header,$products);

}




function get_customer_msg($data){
  $data['customer_msg']='';
  if(preg_match('/^(EXPORT TO GERMANY|catalogue|DO NOT SEND WINE-SEND ALTERNATIVE|PLEASE HOLD UNTIL Bag-01 IN STOCK|corner of Marine Parade and Graystone Road|Friday \d{1,2}pm|NO WINE\!|Give to Kara|open 10 am to 5 pm|entrance from.*Street|del tue or thu|If Not In Leave In Cupboard By Door Please|if noone in leave with neighbour or in garage|closed on Wednesdays|Shop open 10am-5pm. Closed Wednesdays.|Leave at rear if out|no wine\!?|Look 4 Multi-Storey Carpark|Not open untill? \d{1,2}.\d{1,2}(AM|PM))$/i',_trim($data['notes2']))
     OR preg_match('/difficult to find|URGENT|if out, leave|catalogue - please|Phone with |pls phone |Ensure |Opp car showroom|save until|Next to Hairdresser|Contract if |del after|Monday . 9am|opening hours|Mon,Weds, Thurs 9am - 2pm; Fri 9am - 4pm;|if out leave by the side of the green recycle bin|Del before 3PM|carefully|pls pack|pls pick|9am sharp|email cust on|if any|if cust|notify if|call |access via|contact cust|give wine|call on |pls pick today|can only del|Check order CAREFULLY|CHECK CARRIAGE|contact cust if out of stock|drink so give something else as bonus|WEDNESDAY|DESP TODAY AND PACK CAREFULLY|please pack bath bombs very|If closed with|call if|IF ITEMS OUT OF STOCK CONTACT CUSTOMER|Tuesday|No Substitution please|Thursday|friday|can be left|deluvery |please |closed on|Subs OK|NO WINE alternative gift please 1 box of SG|if out can be left |Please call if|contact cust if something out of stock|if out put|Alternative gift to WINE|Add Catalogu|Call if out of stock|Call if if out of stock|Leave outside|Closed between|Not before|Let (her|me|him) know|oppocite|opposite|Behind|Must go out on|Deliver before|if not there|nobody|Leave in|Deliver|If no-one|Leave at|Deliver on|closed at|Please ring customer before delivery |Delivery Between|nobody|porch |close |Open |Shop open|Shop closed|if out Deliver|Leave at|if not there|next door|delivery before|deliver to|in shed|leave around|leave with|leave on|garage|shop|if noone|if not|despatch|dispatch/i',$data['notes2'])
     ){
    $data['customer_msg']=$data['notes2'];
    $data['notes2']='';
    
  }
  if(preg_match('/^(EXPORT TO GERMANY|catalogue|DO NOT SEND WINE-SEND ALTERNATIVE|PLEASE HOLD UNTIL Bag-01 IN STOCK|corner of Marine Parade and Graystone Road|Friday \d{1,2}pm|NO WINE\!|Give to Kara|open 10 am to 5 pm|entrance from.*Street|del tue or thu|If Not In Leave In Cupboard By Door Please|if noone in leave with neighbour or in garage|closed on Wednesdays|Shop open 10am-5pm. Closed Wednesdays.|Leave at rear if out|no wine\!?|Look 4 Multi-Storey Carpark|Not open untill? \d{1,2}.\d{1,2}(AM|PM))$/i',_trim($data['notes']))
     OR preg_match('/difficult to find|URGENT|if out, leave|catalogue - please|Phone with |pls phone |Ensure |save until|Next to Hairdresser|Contract if more than 2 boxes|del after|Monday . 9am|opening hours|Mon,Weds, Thurs 9am - 2pm; Fri 9am - 4pm;|if out leave by the side of the green recycle bin|Del before 3PM|carefully|pls pack|pls pick|9am sharp|email cust on|if any|if cust|notify if|call |access via|contact cust|give wine|call on |pls pick today|can only del|Check order CAREFULLY|CHECK CARRIAGE|contact cust if out of stock|drink so give something else as bonus|WEDNESDAY|DESP TODAY AND PACK CAREFULLY|please pack bath bombs very|If closed with|call if|IF ITEMS OUT OF STOCK CONTACT CUSTOMER|Tuesday|No Substitution please|Thursday|friday|can be left|deluvery |please |closed on|Subs OK|NO WINE alternative gift please 1 box of SG|if out can be left |Please call if|contact cust if something out of stock|if out put|Alternative gift to WINE|Add Catalogu|Call if out of stock|Call if if out of stock|Leave outside|Closed between|Not before|Let (her|me|him) know|oppocite|opposite|Behind|Must go out on|Deliver before|if not there|nobody|Leave in|Deliver|If no-one|Leave at|Deliver on|closed at|Please ring customer before delivery |Delivery Between|nobody|porch |close |Open |Shop open|Shop closed|if out Deliver|Leave at|if not there|next door|delivery before|deliver to|in shed|leave around|leave with|leave on|garage|shop|if noone|if not|despatch|dispatch/i',$data['notes'])
     ){
    $data['customer_msg'].=' '.$data['notes'];
    $data['notes']='';
    
  }


  return $data;
}



function is_to_be_collected($data){

  if(preg_match('/^(local *|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|Collect .*|Collection.*|to be collected|to collect|collected|customer to collect|to be collect by cust|to be collected.*|will collec.*|to collect.*|to collect today)$/i',_trim($data['notes']))){
    

    $data['shipper_code']='NA';
    $data['collection']='Yes';

      if(preg_match('/^(local|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|to be collected|to collect|collected|customer to collect|to be collect by cust)$/i',_trim($data['notes']))){
       $data['notes']='';
     }

  }

  if(preg_match('/^(local *|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|Collect .*|Collection.*|to be collected|to collect|collected|customer to collect|to be collect by cust|to be collected.*|will collec.*|to collect.*|to collect today)$/i',_trim($data['notes']))){
    
    $data['shipper_code']='NA';
    $data['collection']='Yes';
    
     if(preg_match('/^(local|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|to be collected|to collect|collected|customer to collect|to be collect by cust)$/i',_trim($data['notes2']))){
       $data['notes2']='';
     }

     
  }
  
  // print_r($data);
  return $data;
  
}

function is_showroom($data){
  if(preg_match('/^(showrooms?|Showrooom)$/i',_trim($data['notes']))){
    $data['showroom']='Yes';
    $data['notes']='';
    $data['shipper_code']='NA';
    $data['collection']='Yes';
    
  }
  if(preg_match('/^(showrooms?|Showrooom)$/i',_trim($data['notes2']))){
    $data['showroom']='Yes';
    $data['notes2']='';
  $data['shipper_code']='NA';
     $data['collection']='Yes';
  }
  return $data;
}

function is_staff_sale($data,$editor) {
    $data['staff sale key']=0;

    if (preg_match('/cash sale/i',$data['trade_name'])  or preg_match('/cash sale/i',$data['notes'])) {
        if ($data['shipping']==0) {
            $data['shipper_code']='NA';
            $data['collection']='Yes';
        }



        $tmp=preg_replace('/^Staff Sales?\s*\-?\s*/i','',$data['customer_contact']);
        // exit("x:".$tmp."\n");
        $staff_id=get_user_id($tmp,false,'','',$editor);




        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
            $data['shipper_code']='NA';
            $data['collection']='Yes';
            $data['staff sale']='Yes';

            if (preg_match('/'.$tmp.'/i',$data['notes']))
                $data['notes']='';
            if (preg_match('/'.$tmp.'/i',$data['notes2']))
                $data['notes2']='';

            if (preg_match('/^cash sale$/i',$data['notes2']))
                $data['notes2']='';
            if (preg_match('/^cash sale$/i',$data['notes']))
                $data['notes']='';
        }



        if ($data['staff sale key']==0) {
            $tmp=preg_replace('/Staff Sales?\s*\-?\s*/i','',$data['notes']);
            $staff_id=get_user_id($tmp,false,'','',$editor);
            if (count($staff_id)==1 and $staff_id[0]!=0 ) {
                $data['staff sale key']=$staff_id[0];
                $data['shipper_code']='NA';
                $data['collection']='Yes';
                $data['staff sale']='Yes';
                $data['notes']='';
                if (preg_match('/^cash sale$/i',$data['notes2']))
                    $data['notes2']='';
                if (preg_match('/^cash sale$/i',$data['notes']))
                    $data['notes']='';
                if (preg_match('/'.$tmp.'/i',$data['notes2']))
                    $data['notes2']='';
            }
        }


        if ($data['staff sale key']==0) {
            $tmp=preg_replace('/Staff Sales?\s*\-?\s*/i','',$data['notes2']);
            $staff_id=get_user_id($tmp,false,'','',$editor);
            if (count($staff_id)==1 and $staff_id[0]!=0 ) {
                $data['staff sale key']=$staff_id[0];
                $data['shipper_code']='NA';
                $data['collection']='Yes';
                $data['staff sale']='Yes';
                $data['notes2']='';
                if (preg_match('/^cash sale$/',$data['notes2']))
                    $data['notes2']='';
                if (preg_match('/^cash sale$/',$data['notes']))
                    $data['notes']='';

            }
        }


    }


    if (preg_match('/^(staff|cash) sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['trade_name']))) {

        $data['staff sale']='Yes';
        $data['staff sale name']=preg_replace('/^(staff|cash) sales?\-?\s+\-?\s*/i','',$data['trade_name']);
       
        $staff_id=get_user_id($data['staff sale name'],false,'','',$editor);
        $data['staff sale key']=$staff_id[0];

        $data['shipper_code']='NA';
        $data['collection']='Yes';

    }



    if (preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['notes']))) {

        $data['staff sale']='Yes';
        $data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['notes']);
        $staff_id=get_user_id($data['staff sale name'],false,'','',$editor);
        $data['staff sale key']=$staff_id[0];
        $data['notes']='';
        $data['shipper_code']='NA';
        $data['collection']='Yes';

    }
    if (preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['notes2']))) {

        $data['staff sale']='Yes';
        $data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['notes2']);
        $staff_id=get_user_id($data['staff sale name'],false,'','',$editor);
        $data['staff sale key']=$staff_id[0];
        $data['notes2']='';
        $data['shipper_code']='NA';
        $data['collection']='Yes';

    }
    if (preg_match('/^(staff sale|staff)$/i',_trim($data['notes']))) {
        $data['notes']='';
        $data['staff sale']='Yes';
        $data['shipper_code']='NA';
        $data['collection']='Yes';


        $staff_id=get_user_id($data['customer_contact'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }


    }

    if (preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['postcode']))) {

        $data['staff sale']='Yes';
        $data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['postcode']);
        $staff_id=get_user_id($data['staff sale name'],false,'','',$editor);
        $data['staff sale key']=$staff_id[0];

        $data['shipper_code']='NA';
        $data['collection']='Yes';

    }







    if (preg_match('/^(staff sale|staff)$/i',_trim($data['notes2']))) {
        $data['notes2']='';
        $data['staff sale']='Yes';
        $data['shipper_code']='NA';
        $data['collection']='Yes';
        $data['staff sale key']=0;

        $staff_id=get_user_id($data['customer_contact'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['address1'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['address2'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }

    }


    if (preg_match('/^staff sales?$/i',_trim($data['trade_name']))) {
        $data['staff sale']='Yes';
        $data['shipper_code']='NA';
        $data['collection']='Yes';


        $staff_id=get_user_id($data['address1'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['address2'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['customer_contact'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }




    }


    if (preg_match('/^staff sales?$/i',_trim($data['customer_contact']))) {
        $data['staff sale']='Yes';
        $data['shipper_code']='NA';
        $data['collection']='Yes';
        $staff_id=get_user_id($data['address1'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['address2'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['customer_contact'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
    }

//print_r($data);exit;

    if (preg_match('/^staff sales?|Ancient Winsdom Staff$/i',_trim($data['postcode']))) {
        $data['staff sale']='Yes';
        $data['shipper_code']='NA';
        $data['collection']='Yes';
        $staff_id=get_user_id($data['address1'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['address2'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
        $staff_id=get_user_id($data['customer_contact'],false,'','',$editor);
        if (count($staff_id)==1 and $staff_id[0]!=0 ) {
            $data['staff sale key']=$staff_id[0];
        }
    }

//print_r($data);exit;




//print_r($data);exit;

    return $data;

}

/* function get_tax_number($data){ */
/*   global $myconf; */
/*   $data['tax_number']=''; */
  
/*   if(in_array(strtoupper($data['dn_country_code']),$myconf['tax_conditional0_2acode'] ) and $data['tax1']==0){ */
/*     print $data['notes2']."\n"; */
    
/*   } */
  
/*   return $data; */

/* } */






// function delete_transactions($order_id){

//   $sql=sprintf("delete from bonus where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from transaction where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from todo_transaction where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from outofstock where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from debit where tipo=2 and order_affected_id=%d",$order_id); mysql_query($sql);
// }






function checkPostcode (&$toCheck) {

  // Permitted letters depend upon their position in the postcode.
  $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
  $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
  $alpha3 = "[abcdefghjkstuw]";                                   // Character 3
  $alpha4 = "[abehmnprvwxy]";                                     // Character 4
  $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5
  
  // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA
  $pcexp[0] = '/^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([0-9]{1}'.$alpha5.'{2})$/';

  // Expression for postcodes: ANA NAA
  $pcexp[1] =  '/^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([0-9]{1}'.$alpha5.'{2})$/';

  // Expression for postcodes: AANA NAA
  $pcexp[2] =  '/^('.$alpha1.'{1}'.$alpha2.'[0-9]{1}'.$alpha4.')([0-9]{1}'.$alpha5.'{2})$/';
  
  // Exception for the special postcode GIR 0AA
  $pcexp[3] =  '/^(gir)(0aa)$/';
  
  // Standard BFPO numbers
  $pcexp[4] = '/^(bfpo)([0-9]{1,4})$/';
  
  // c/o BFPO numbers
  $pcexp[5] = '/^(bfpo)(c\/o[0-9]{1,3})$/';

  // Load up the string to check, converting into lowercase and removing spaces
  $postcode = strtolower($toCheck);
  $postcode = str_replace (' ', '', $postcode);
  $postcode = str_replace ('^', '\^', $postcode);
  // print "--->$postcode <----\n";
  // Assume we are not going to find a valid postcode
  $valid = false;
  
  // Check the string against the six types of postcodes
  foreach ($pcexp as $regexp) {
    //print "->$regexp <-->$postcode <----\n";
    if (preg_match($regexp,$postcode, $matches)) {
      
      // Load new postcode back into the form element  
      $toCheck = strtoupper ($matches[1] . ' ' . $matches [2]);
      
      // Take account of the special BFPO c/o format
      $toCheck = preg_replace ('/C\/O/', 'c/o ', $toCheck);
      
      // Remember that we have found that the code is valid and break from loop
      $valid = true;
      break;
    }
  }
    
  // Return with the reformatted valid postcode in uppercase if the postcode was 
  // valid
  if ($valid){return true;} else {return false;};
}




?>
