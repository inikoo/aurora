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






function filter_header($data){
  foreach($data as $key=>$value){
    $data[$key]=_trim($value);
  }
  
  if(preg_match('/\d{2}-\d{2}-\d{2}/',$data['notes2']))
     $data['notes2']='';
  return $data;
}

function guess_email($email,$contact='',$tipo=1){
  if(check_email_address($email) ){

    // if($contact=='')
    //  $contact=get_name($contact_id);
    $email_data=array ('email'=>$email,'contact'=>$contact,'tipo'=>$tipo);
    
    return $email_data;
    
  }

  else
    return false;
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
  $sql=sprintf("select `Country Telephone Code` as tel_code from `Country Dimension`  where `Country Key`=%d",$country_id);
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


function check_email_address($email) {
return Email::is_valid($email);
}

function get_address_raw(){
  
 $address1='';
  $address2='';
  $address3='';
  $town_d2='';
  $town_d1='';
  $town='';
  $country_d2='';
  $country_d1='';
  $postcode='';
  $country='';
  $town_d2_id=0;
  $town_d1_id=0;
  $town_id=0;
  $country_d2_id=0;
  $country_d1_id=0;
  $country_id=0;
  
   $address_data=array(
		      'address1'=>$address1,
		      'address2'=>$address2,
		      'address3'=>$address3,
		      'town_d2'=>$town_d2,
		      'town_d1'=>$town_d1,
		      'town'=>$town,
		      'country_d2'=>$country_d2,
		      'country_d1'=>$country_d1,
		      'postcode'=>$postcode,
		      'country'=>$country,
		      'town_d2_id'=>$town_d2_id,
		      'town_d1_id'=>$town_d1_id,
		      'town_id'=>$town_id,
		      'country_d2_id'=>$country_d2_id,
		      'country_d1_id'=>$country_d1_id,
		      'country'=>$country_id,

		       );

   return $address_data;
}





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


function xcreate_orden($customer_id,
		      $header,
		      $act,
		      $date_index,
		      $date_order,
		      $date_inv,
		      $tipo,
		      $address_del_id='',
		      $address_bill_id='',
		      $new_customer,
		      $is_island,$parent_order_id
		      ,$partner=0
		      ,$co=''
		      ){
  



  $db =& MDB2::singleton();
  global $tax_rate,$home_country_id;
  $tax_factor=$tax_rate;
  $total_tax=$header['tax1']+$header['tax2'];
  $total_net=$header['total_net'];
  $total=$header['total_topay'];
  if($total!=0 and $total_tax==0){
    $tax_factor=0;
  }

  $ajuste_in_net=$total_net-$header['total_items_charge_value']-$header['charges']-$header['shipping'];
  $ajuste_in_tax=$total_tax-($total_net*$tax_factor);
  $ajuste_in_total=$total-$total_net-$total_tax;

  //  $balance=$total-(($total_net+$balance_net)*(1+$tax_factor));
  
  //   $balance_total=number_format($balance_net*(1+$tax_factor)+$balance,2);
  //   $balance_net=number_format($balance_net,2);
  //   $balance=number_format($balance,2);
			 
  





  if($new_customer){
    

    
    

    if($is_island){
      $sql="insert into nodata_island (customer_id,history,history2) values ($customer_id,".$header['history'].",".$header['history'].")";
      // print "$sql\n";
      mysql_query($sql);
    }
      

    $number_of_orders_old=$header['history'];
    if(!is_numeric($number_of_orders_old))
      $number_of_orders_old=0;

    if($number_of_orders_old>1){
      $number_orders_no_data=$number_of_orders_old-1;
      $sql="update customer set num_orders_nd=$number_orders_no_data where id=$customer_id";
      mysql_query($sql);
      //      $_date_index=date('Y-m-d H:i:s',  strtotime(date('U',strtotime($date_index))-1)  );
      $_date_index=date('Y-m-d H:i:s',    strtotime(str_replace("'",'',$date_index))-1);
      
      if($number_orders_no_data==1)
	$sql="insert into note (texto,op,op_id,date_index,code) values ('There is one previous order for which no details are available','Customer',$customer_id,'$_date_index',1)";
      else
	$sql="insert into note (texto,op,op_id,date_index,code) values ('There are $number_orders_no_data previous orders for which no details are available','Customer',$customer_id,'$_date_index',1)";
      mysql_query($sql);
      $sql="insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('ISL','Customer',$customer_id,'End',NULL,$date_index)";
      mysql_query($sql);
      $history_id=mysql_insert_id();
      //print "$sql\n";      
      $sql="insert into history_item (history_id,columna,old_value,new_value) values ($history_id,'Orders',0,$number_orders_no_data)";
      mysql_query($sql);

      //    print "$sql\n";
      //exit;
    }


  }else{
    


    if($is_island){
      $sql="update nodata_island set history=".$header['history']."  where customer_id=$customer_id";
      //	print "$sql\n";
      mysql_query($sql);
    }else{
      // find is the customer was in a island
      $sql=sprintf("select history,id  from nodata_island  where done=0 and customer_id=%d",$customer_id);

      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if(($diff_history=$header['history']-$row['history']-1)>0){
	  // update nodata orders and set a note ---------------------
	  $customer_data=get_customer_data($customer_id);
	    
	  $number_orders_no_data=$diff_history+$customer_data['num_orders_nd'];
	  $sql="update customer set num_orders_nd=$number_orders_no_data where id=$customer_id";
	  //  print "$diff_history $number_orders_no_data     $sql";
	  mysql_query($sql);
	  $sql="update nodata_island set history='".$header['history']."',done=1  where customer_id=$customer_id";
	  //	print "$sql\n";
	  mysql_query($sql);

	  //      $_date_index=date('Y-m-d H:i:s',  strtotime(date('U',strtotime($date_index))-1)  );
	  $_date_index=date('Y-m-d H:i:s',    strtotime(str_replace("'",'',$date_index))-1);
	    
	  if($diff_history==1)
	    $sql="insert into note (texto,op,op_id,date_index,code) values ('It\'s one order for which no details are available in this period','Customer',$customer_id,'$_date_index',2)";
	  else
	    $sql="insert into note (texto,op,op_id,date_index,code) values ('There are $diff_history orders for which no details are available in this period','Customer',$customer_id,'$_date_index',2)";
	  mysql_query($sql);
	  // print "$sql\n";
	  $sql="insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('ISL','Customer',$customer_id,'End',NULL,$date_index)";
	  mysql_query($sql);
	  $history_id=mysql_insert_id();

	  $sql="insert into history_item (history_id,columna,old_value,new_value) values ($history_id,'Orders',0,$diff_history)";
	  mysql_query($sql);

	  //---------------------------------------------------------


	}
      }      

    }


    $customer_data=get_customer_data($customer_id);

    $number_of_orders_old=$customer_data['num_invoices']+ $customer_data['num_pro_invoices']+ $customer_data['num_cancels']+$customer_data['num_orders_nd'];
    if($number_of_orders_old==0){
      $number_of_orders_old=$header['history'];
      
      $number_of_orders_old=$number_of_orders_old-$customer_data['num_orders'];
      if($number_of_orders_old<0)
	$number_of_orders_old=0;
      
    }


  }

  if($tipo==1 or $tipo==2 or $tipo==3)
    $number_of_orders=$number_of_orders_old+1;
  else
    $number_of_orders=$number_of_orders_old;



  //  print_r($header);
  //source tipo - can be i(internet),t(telephone),f(fax),p(post),s(showroom),a(staffsales),u(unknown)
  if($header['source_tipo']=='')$header['source_tipo']='u';
  $_gold=($header['gold']=='Gold Reward'?'1':'0');





  if($header['customer_contact']=='')
    $header['customer_contact']=$header['trade_name'];

  if($header['trade_name']=='')
    $header['trade_name']=$header['customer_contact'];

  // print_r($header);

  $payment_method=get_payment_method($header['pay_method']);
  //print "$payment_method\n";
  //exit;

  $del_country_id=country_id($address_del_id,$home_country_id);


  $sql=sprintf("insert into orden (fao,feedback_id,source_tipo,customer_name,contact_name,customer_id2,customer_id3,tel,public_id,parcels,weight,order_hist,gold,taken_by,net
				       ,tax
				       ,total
				       ,balance_net
				       ,balance_tax
				       ,balance_total
				       ,payment_method,date_creation,date_processed,date_invoiced,titulo,customer_id,address_del,address_bill,tipo,date_index,parent_id,partner,del_country_id) values
				       (%s,%d,'%s',%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s,%d,%d)
				       ",prepare_mysql($co),
	       $header['feedback'],
	       $header['source_tipo'],
	       prepare_mysql($header['trade_name']),
	       prepare_mysql($header['customer_contact']),
	       prepare_mysql($header['extra_id1']),
	       prepare_mysql($header['extra_id2']),
	       prepare_mysql($header['phone']),
	       prepare_mysql($header['order_num']),
	       prepare_mysql($header['parcels']),
	       prepare_mysql($header['weight']),
	       $number_of_orders,
	       $_gold,
	       'null',
	       $total_net,
	       $total_tax,
	       $total,
	       $ajuste_in_net,
	       $ajuste_in_tax,
	       $ajuste_in_total,
	       prepare_mysql($payment_method),
	       $date_order,
	       $date_order,
	       $date_inv,
	       prepare_mysql(mb_ucwords($header['ltipo'])),
	       $customer_id,
	       prepare_mysql(display_full_address($address_del_id)),
	       prepare_mysql(display_full_address($address_bill_id)),
	       $tipo,
	       $date_index,
	       prepare_mysql($parent_order_id),$partner,$del_country_id
	       );
  
  $sql=preg_replace('/\n/','',$sql);
  $sql=preg_replace('/\s{2,}/',' ',$sql);
    
  mysql_query($sql);
  //  exit("$sql\n");
  // $order_id = $db->lastInsertID();
  $order_id=mysql_insert_id();



  $a_taken=get_user_id($header['takenby'],$order_id,'taken');
  if(count($a_taken)==1)
    $_taken=$a_taken[0];
  else
    $_taken='null';

  $sql=sprintf("update orden set taken_by=%s where id=%d",$_taken,$order_id);
  mysql_query($sql);


  if($order_id<1 or !is_numeric($order_id))
    exit("$order_id ->   $sql\n");
  //   if($balance!=0){
  //    $sql=sprintf("insert into balance (order_id,tax_code,value) values (%d,NULL,%.2f)",$order_id,$balance);
  //    print "$sql\n";
  //   mysql_query($sql);
  //   }
  //   if($balance_net!=0){
  //    $sql=sprintf("insert into balance (order_id,tax_code,value) values (%d,'S',%.2f)",$order_id,$balance_net);
  //    print "$sql\n";
  //  mysql_query($sql);
  //  }

  if($tax_factor==0){
    $tax_id=1;
    $tax_code='NULL';
  }else{
    $tax_id=2;
    $tax_code='S';
    $sql=sprintf("insert into tax (order_id,code,value) values (%d,'S',%.2f)",$order_id,$total_tax);
    //print "$sql\n";
    mysql_query($sql);


  }
  if($header['charges']!=0){
   
    $sql=sprintf("insert into charge (tipo,order_id,tax_code,value) values (1,%d,%s,%.2f)",$order_id,prepare_mysql($tax_code),$header['charges']);
    // print $header['charges']." $sql\n";
    mysql_query($sql);
  }


  $notes=$header['notes'];

  if($notes=='0')
    $notes='';


  $notes2=$header['notes2'];
  
  if(isset($act['tax_number'])){
    $tax_number_act=get_tax_number($act['tax_number']);
  }else
    $tax_number_act=false;

  $tax_number=false;
  $country_id= get_customer_country_id($customer_id);
  // print "$country_id\n";
  if(is_numeric($country_id) and $country_id>0 and $country_id!=$home_country_id){

    $tax_number=get_tax_number($notes2);
  }

  if($tax_number){
    change_tax_number($customer_id,$tax_number,$date_index,($new_customer?false:true));
    $notes2='';
  }elseif($tax_number_act){
    change_tax_number($customer_id,$tax_number_act,$date_index,($new_customer?false:true));
    
  }




  //exit;

  if(preg_match('/showroom|staff|local|colle/i',$notes) and $header['shipping']==0){// Collected
    $tipo_deliver=1;
  }else{
    $tipo_deliver=2;

    // Try to get the delevery comapny

    $shipping_supplier_id=get_shipping_supplier($notes,$order_id);
    //print "$shipping_supplier_id\n";
    
    if(is_numeric($shipping_supplier_id) and $shipping_supplier_id>0){
      $notes='';
    }else
      $shipping_supplier_id='';
    $sql=sprintf("insert into shipping (supplier_id,order_id,value,tax_code) values (%s,%d,%.2f,%s)",prepare_mysql($shipping_supplier_id),$order_id,$header['shipping'],prepare_mysql($tax_code));
    // print "$sql\n";
    mysql_query($sql);
  }
  
  $sql=sprintf("update orden set note=%s,note2=%s,tax_code=%s where id=%d",prepare_mysql($notes),
	       prepare_mysql($notes2),prepare_mysql($tax_code),$order_id);
  mysql_query($sql);

  if($date_order!='null' or $date_order!=''){
    $tipo=2;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_order);
    mysql_query($sql);
  }
  if($date_inv!='null' or $date_inv!=''){
    $tipo=5;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_inv);
    mysql_query($sql);
  }


  if($header['tax1']>0 or $header['tax1']<0)
    $tax_code='S';
  else
    $tax_code='';




  return array($order_id,$tax_code);

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




function update_orden($order_id,
		      $customer_id,
		      $header,
		      $act,
		      $date_index,
		      $date_order,
		      $date_inv,
		      $tipo,
		      $address_del_id='',
		      $address_bill_id='',
		      $new_customer,
		      $is_island,$parent_order_id,$partner=0,$co=''
		      ){



  $db =& MDB2::singleton();
  global $tax_rate;
  global $home_country_id;
  // first delete all the related sub tables
  $sql="delete from tax where order_id=$order_id";mysql_query($sql);
  $sql="delete from charge where order_id=$order_id";mysql_query($sql);
  $sql="delete from shipping where order_id=$order_id";mysql_query($sql);
  $sql="delete from balance where order_id=$order_id";mysql_query($sql);
  $sql="delete from todo_users where order_id=$order_id";mysql_query($sql);
  $sql="delete from todo_shipping_supplier where order_id=$order_id";mysql_query($sql);
  
 $sql="delete from pick where order_id=$order_id";mysql_query($sql);
      $sql="delete from pack where order_id=$order_id";mysql_query($sql);

  $tax_factor=$tax_rate;
  $total_tax=$header['tax1']+$header['tax2'];
  $total_net=$header['total_net'];
  $total=$header['total_topay'];
  if($total!=0 and $total_tax==0){
    $tax_factor=0;
  }

  $ajuste_in_net=$total_net-$header['total_items_charge_value']-$header['charges']-$header['shipping'];
  $ajuste_in_tax=$total_tax-($total_net*$tax_factor);
  $ajuste_in_total=$total-$total_net-$total_tax;



  //  print_r($header);
  //source tipo - can be i(internet),t(telephone),f(fax),p(post),s(showroom),a(staffsales),u(unknown)
  if($header['source_tipo']=='')$header['source_tipo']='u';
  $_gold=($header['gold']=='Gold Reward'?'1':'0');

  $a_taken=get_user_id($header['takenby'],addslashes($order_id),'taken');
  //print "---------  ".$header['takenby']."  ------\n";
  // print_r($a_taken);
 
  if(count($a_taken)==1)
    $_taken=$a_taken[0];
  else
    $_taken='null';
  $payment_method=get_payment_method($header['pay_method']);
  $del_country_id=country_id($address_del_id,$home_country_id);

  $sql=sprintf("update orden set fao=%s,feedback_id=%d,source_tipo='%s',customer_name='%s',contact_name='%s',customer_id2=%s,customer_id3=%s,tel='%s',public_id='%s',parcels=%s,weight=%s,gold='%s',taken_by=%s
				       ,net=%.2f
				       ,tax=%.2f
				       ,total=%.2f
				       ,balance_net=%.2f
 ,balance_tax=%.2f ,balance_total=%.2f
				       ,payment_method='%s',date_creation=%s,date_processed=%s,date_invoiced=%s,titulo='%s',customer_id=%d,address_del=%s,address_bill=%s,tipo=%s,date_index=%s,parent_id=%s,partner='%s',del_country_id=%d where id=%d", prepare_mysql($co),
	       $header['feedback'],
	       $header['source_tipo'],
	       addslashes($header['trade_name']),
	       addslashes($header['customer_contact']),
	       prepare_mysql($header['extra_id1']),
	       prepare_mysql($header['extra_id2']),
	       addslashes($header['phone']),
	       addslashes($header['order_num']),
	       prepare_mysql($header['parcels']),
	       prepare_mysql($header['weight']),
	       $_gold,
	       $_taken,
	       $total_net,
	       $total_tax,
	       $total,
	       $ajuste_in_net,
	       $ajuste_in_tax,
	       $ajuste_in_total,
	       addslashes($payment_method),
	       $date_order,
	       $date_order,
	       $date_inv,
	       addslashes(mb_ucwords($header['ltipo'])),
	       $customer_id,
	       prepare_mysql(display_full_address($address_del_id)),
	       prepare_mysql(display_full_address($address_bill_id)),
	       $tipo,
	       $date_index,prepare_mysql($parent_order_id),$partner,$del_country_id,
	       $order_id
	       );
  
  mysql_query($sql);

      print "$sql";







  if($tax_factor==0){
    $tax_id=1;
    $tax_code='NULL';
  }else{
    $tax_id=2;
    $tax_code='S';
    $sql=sprintf("insert into tax (order_id,code,value) values (%d,'S',%.2f)",$order_id,$total_tax);
    //print "$sql\n";
    mysql_query($sql);


  }
  if($header['charges']!=0){
   
    $sql=sprintf("insert into charge (tipo,order_id,tax_code,value) values (1,%d,%s,%.2f)",$order_id,prepare_mysql($tax_code),$header['charges']);
    // print $header['charges']." $sql\n";
    mysql_query($sql);
  }


  $notes2=$header['notes2'];
  
  if(isset($act['tax_number'])){
    $tax_number_act=get_tax_number($act['tax_number']);
  }else
    $tax_number_act=false;

  $tax_number=false;
  $country_id= get_customer_country_id($customer_id);
  //print "$country_id\n";
  if(is_numeric($country_id) and $country_id>0 and $country_id!=$home_country_id){

    $tax_number=get_tax_number($notes2);
  }

  if($tax_number){
    change_tax_number($customer_id,$tax_number,$date_index,($new_customer?false:true));
    $notes2='';
  }elseif($tax_number_act){
    change_tax_number($customer_id,$tax_number_act,$date_index,($new_customer?false:true));
    
  }










  $notes=$header['notes'];

  if($notes=='0')
    $notes='';

  if(preg_match('/showroom|staff|local|colle/i',$notes) and $header['shipping']==0){// Collected
    $tipo_deliver=1;
  }else{
    $tipo_deliver=2;

    // Try to get the delevery comapny

    $shipping_supplier_id=get_shipping_supplier($notes,$order_id);
    //print "$shipping_supplier_id\n";
    
    if(is_numeric($shipping_supplier_id) and $shipping_supplier_id>0){
      $notes='';
    }else
      $shipping_supplier_id='';
    $sql=sprintf("insert into shipping (supplier_id,order_id,value,tax_code) values (%s,%d,%.2f,%s)",prepare_mysql($shipping_supplier_id),$order_id,$header['shipping'],prepare_mysql($tax_code));
    // print "$sql\n";
    mysql_query($sql);
  }
  
  $sql=sprintf("update orden set note=%s,note2=%s,tax_code=%s where id=%d",prepare_mysql($notes),
	       prepare_mysql($notes2),prepare_mysql($tax_code),$order_id);
  mysql_query($sql);

  if($date_order!='null' or $date_order!=''){
    $tipo=2;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_order);
    mysql_query($sql);
  }
  if($date_inv!='null' or $date_inv!=''){
    $tipo=5;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_inv);
    mysql_query($sql);
  }


  if($header['tax1']>0 or $header['tax1']<0)
    $tax_code='S';
  else
    $tax_code='';



  //   if($date_order!='null' or $date_order!=''){
  //     $tipo=2;
  //     $sql=sprintf("update orden_history set fecha=%s where tipo=%d and order_id=%d",$date_order,$tipo,$order_id);
  //     mysql_query($sql);
  //   }else{
  //     $sql=sprintf("delete orden_history where tipo=%d and order_id=%d",$tipo,$order_id);
  //     mysql_query($sql);

  //   }

  //   if($date_inv!='null' or $date_inv!=''){
  //     $tipo=2;
  //     $sql=sprintf("update orden_history set fecha=%s where tipo=%d and order_id=%d",$date_inv,$tipo,$order_id);
  //     mysql_query($sql);
  //   }else{
  //     $sql=sprintf("delete orden_history where tipo=%d and order_id=%d",$tipo,$order_id);
  //     mysql_query($sql);

  //   }


  return $tax_code;

  //   print "$sql\n";
  // exit;


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
  $my_total_items_dispached=0;
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
    $my_total_items_dispached+=$my_items_to_dispach;

    if($transaction['discount']==1)
      $my_total_items_free+=$my_total_items_dispached;
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
	

      $sql=sprintf("insert into transaction (promotion_id,tipo,order_id,product_id,ordered,dispached,discount,charge,tax_code,original_order_id) value (%s,%d,%d,%d,%.2f,%.2f,%.3f,%.2f,%s,%s)",$promotion_id,$tipo_t,$order_id,$product_id,$transaction['order'],$my_items_to_dispach,$transaction['discount'],$my_items_to_charge_value,prepare_mysql($tax_code),$original_order);
      
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


function get_dates($filedate,$header_data,$tipo_order,$new_file=true){


  $datetime_updated=date("Y-m-d H:i:s",$filedate);
  $time_updated_menos30min=date("H:i:s",$filedate-1800);

  list($date_updated,$time_updated)=preg_split('/\s+/',$datetime_updated);
  if($new_file){
    if($tipo_order==2  or $tipo_order==6 or $tipo_order==7 or $tipo_order==9   ){
      
      //print_r($header_data);
      if($header_data['date_inv']=='' or $header_data['date_inv']=='1970-01-01')
	$header_data['date_inv']=$header_data['date_order'];
      
      if($date_updated ==$header_data['date_inv']){
	
	$date_charged=$date_updated." ".$time_updated;
// 	if($header_data['date_inv']==$header_data['date_order']){
// 	  $date_processed=$header_data['date_order']." ".$time_updated_menos30min;
// 	  print "caca";
// 	}else
	$date_processed=$header_data['date_order']." 09:30:00";
	if(strtotime($date_processed)>strtotime($date_charged))
	  $date_processed=$header_data['date_order']." ".$time_updated_menos30min;

      }else{
	$date_charged=$header_data['date_inv']." 16:30:00";
	$date_processed=$header_data['date_order']." 09:30:00";
      }
      $date_index=$date_charged;
    }else{



      $date_charged="NULL";
      if($date_updated ==$header_data['date_order']){
	//print $header_data['date_order']." xssssssssssssxx";
	$date_processed=$date_updated." ".$time_updated;
	// print "$date_processed  xssssssssssssxx\n";

      }
      else
	$date_processed=$header_data['date_order']." 08:30:00";
      $date_index=$date_processed;

      // print $date_index." xxx\n";

    }
  }
  //  print "$date_index,$date_processed,$date_charged\n";
  return array($date_index,$date_processed,$date_charged);

}


function setup_contact($act_data,$header_data,$date_index){
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
      $staff_id=get_user_id($staff_name,'' , '',false);
      if(count($staff_id)==1 and $staff_id[0]!=0 ){
	print "Staff $staff_name  sale\n";
	$header_data['address1']=$act_data['contact'];
      }
      unset($act_data);

    }
    

    $staff_name=$header_data['address1'];
    $staff_id=get_user_id($staff_name);
    
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


    //print_r($header_data);
    //exit;

    if(preg_match('/sale - Philip|staff|staff order|cash sale|staff sale|cash - sale/i',$header_data['trade_name']) or 
       preg_match('/staff|staff sale|cash sale/i',$header_data['city']) or
       preg_match('/staff|staff sale|cash sale/i',$header_data['address1']) or
       preg_match('/staff|staff sale|cash sale/i',$header_data['address2']) or
       preg_match('/staff|staff sale|cash sale/i',$header_data['address3']) or
       preg_match('/^staff$|staff sale/i',$header_data['notes']) or

       preg_match('/staff|staff sale|cash sale/i',$header_data['postcode'])){
      //print "cash\n";
      // Chash tipe try to get staff name
      if($header_data['address1']=='Al & Bev')
	$header_data['address1']='Bev';
	
      $regex='/staff orders?|staff|sales?|cash|\-|:|Mark postage to France/i';
	
      $header_data['city']=_trim(preg_replace($regex,'',$header_data['city']));
      $header_data['postcode']=_trim(preg_replace($regex,'',$header_data['postcode']));
      $header_data['trade_name']=_trim(preg_replace($regex,'',$header_data['trade_name']));
      $header_data['address1']=_trim(preg_replace($regex,'',$header_data['address1']));
      $header_data['address2']=_trim(preg_replace($regex,'',$header_data['address2']));
      $header_data['address3']=_trim(preg_replace($regex,'',$header_data['address3']));
      $header_data['customer_contact']=_trim(   preg_replace($regex,'',$header_data['customer_contact'])      );
      $header_data['phone']=_trim(preg_replace($regex,'',$header_data['phone']));

	
      if($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']!='' and $header_data['customer_contact']=='' )
	$header_data['address1']=$header_data['city'];
      if($header_data['address1']=='' and $header_data['postcode']!='' and $header_data['city']==''   and $header_data['customer_contact']=='' )
	$header_data['address1']=$header_data['postcode'];
      if($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']==''  and $header_data['customer_contact']!=''  )
	$header_data['address1']=$header_data['customer_contact'];
      if($header_data['address1']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']!='')
	$header_data['address1']=$header_data['trade_name'];
      if($header_data['address1']=='' and $header_data['address2']!='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
	$header_data['address1']=$header_data['address2'];
      if($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']!='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
	$header_data['address1']=$header_data['address3'];
      if($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['notes']!=''       and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']==''){
	// Unkown
	  
	$header_data['address1']=$header_data['notes'];
      }


      if($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']==''){
	// Unkown
	// Create unknowen customer
	//	$customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	//return array(false,$customer_id,false,false,false,true,$co);
      }

      

	
      if($header_data['address1']!=''){
	$staff_name=$header_data['address1'];

	$staff_id=get_user_id($staff_name);
	
	//	$staff=new Staff('alias',$staff_name);

	//	print "$staff_name\n";
	//	  print_r($staff_id);
	//	  exit;
	if(count($staff_id)==1 and $staff_id[0]!=0 ){

	    
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




	}else{
	  // print $staff_name;
	  if(preg_match('/|maureen|church|Parcel Force Driver|sarah|Money in Petty|church|Parcel Force Driver|craig|malcol|Joanne/i',$staff_name)){
	    // $customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	    // return array(false,$customer_id,false,false,false,true,$co);
	       
	       
	  }
	      
	    





	}
	    
      }
	

    }

  



    // Try to fix it
    if(!isset($header_data['order_num']))
      exit("NO num_inv \n");
    
  
  }


 





  $different_delivery_address=false;



  // switch($header_data['order_num']){ 
  //}





//print_r($act_data);
 //print_r($header_data);
 //exit;





  if($act_data['town']=='Korea South' and $act_data['country']=='' ){
    $act_data['country']='Korea South'; 
    $act_data['town']='';

    if($act_data['a3']=='Seoul'){
      $act_data['town']='Seoul';
      $act_data['a3']='';}
  }

  if($act_data['postcode']=='Korea South' and ($act_data['country']=='' or $act_data['country']=='Korea South' ) ){
    $act_data['country']='Korea South'; 
    $act_data['postcode']='';

    if($act_data['a3']=='Seoul'){
      $act_data['town']='Seoul';
      $act_data['a3']='';}
  }


  if(preg_match('/^eire$/i',$act_data['postcode'])){
    $act_data['country']='Ireland'; 
    $act_data['postcode']='';

  }
  if(preg_match('/^(Las Palmas)$/i',$act_data['town'])){
    $act_data['country']='Spain'; 

  }
 if(preg_match('/^(Ciudad Real)$/i',$act_data['town'])  and $act_data['country']=='E'  ){
    $act_data['country']='Spain'; 
  }

 

  if(preg_match('/\(.+\)/i',$act_data['name'],$match)){
    $_contact=preg_replace('/^\(|\)$/i','',$match[0]);
    // print "$_contact\n";
    if(strtolower($_contact)==strtolower($act_data['contact'])){
      $act_data['name']=preg_replace('/\(.+\)/i','',$act_data['name']);
    }
  }


  if( preg_match('/^belfast\s*,/i',$act_data['town'])){
    $act_data['town']='Belfast';
  }
  if( preg_match('/^via cork$/i',$act_data['town'])){
    $act_data['town']='';
  }


  if( preg_match('/^co\.? (Westmeath|Meath)$/i',$act_data['town'])){
    $act_data['town']='';
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












    $sql=sprintf("select `Country Key` as id from `Country Dimension` left join `Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($header_data['country']),prepare_mysql($header_data['country']));
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if(!$row = mysql_fetch_array($result, MYSQL_ASSOC)) 
      $header_data['country']=$act_data['country'];
      
    
    
      
  }

    
  //  print "xxxxxxx $different_delivery_address xxxxxxxxxxxxxx";
  //exit;
  // Ok in some cases the country is in the post code so try to get it

  if($act_data['country']==''){


    if(preg_match('/spain\s*.\s*ibiza/i',$act_data['postcode'])){
      $act_data['country']='Spain';
      $act_data['postcode']='';
      $act_data['country_d1']='Balearic Islands';
      $act_data['country_d2']='Balearic Islands';
    }
      
    

    $tmp_array=preg_split('/\s+/',$act_data['postcode']) ;

    if(count($tmp_array)==2){
      $sql=sprintf("select `Country Name` as name from `Country Dimension` left join `Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[1];
      }

       $sql=sprintf("select `Country Name` as name from `Country Dimension` left join `Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[1]),prepare_mysql($tmp_array[1]));
      

      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[0];
      }
    }elseif(count($tmp_array)==1){
      $sql=sprintf("select `Country Name` as name from `Country Dimension` left join `Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']='';
      }
	

    }
  }else{
    //    print_r($act_data);
    if(strtolower(_trim($act_data['country']))==strtolower(_trim($act_data['postcode'])))
      $act_data['postcode']='';
  }
  


    
    
  if($act_data['postcode']=="" and preg_match('/\s*\d{4,6}\s*/i',$act_data['town'],$match))
    {
  
      if($act_data['country']!="Netherlands"){
	$act_data['postcode']=_trim($match[0]);
	$act_data['town']=preg_replace('/\s*\d{4,6}\s*/','',$act_data['town']);
      }
    }


  if($act_data['a2']=='Ascheffel'){
    $act_data['town']='Ascheffel';
    $act_data['a2']='';
  }
  if(preg_match('/South Afrika$|South Africa$/i',$header_data['postcode'])){
    $header_data['country']='South Africa';
    $header_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$header_data['postcode']));
  }

  if(preg_match('/South Afrika$|South Africa$/i',$act_data['postcode'])){
    $act_data['country']='South Africa';
    $act_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$act_data['postcode']));
  }


  if(preg_match('/N-5353 Straum/i',$header_data['postcode'])){
    $header_data['postcode']='N-5353';
    $header_data['city']='Straum';
  }

 if(preg_match('/Via Chiesanuova, 71/i',$act_data['a1']) and preg_match('/C.O .c.c. Futura2./i',$act_data['a2'])  ){
   $act_data['a1']='C/O CC Futura';
  $act_data['a2']='Via Chiesanuova, 71';
  }

 if(preg_match('/30173 Mestre Ve/i',$header_data['address3'])){
    $header_data['postcode']='30173';
    $header_data['city']='Mestre';
    $header_data['address3']='';
    $header_data['country_d1']='Veneto';
    $header_data['country_d2']='Venice';

  }




  if(preg_match('/United States$/i',$header_data['postcode'])){
    $header_data['country']='USA';
    $header_data['postcode']=_trim(preg_replace('/United States$/i','',$header_data['postcode']));
  }

  if(preg_match('/United States$/i',$act_data['postcode'])){
    $act_data['country']='USA';
    $act_data['postcode']=_trim(preg_replace('/United States$/i','',$act_data['postcode']));
  }


  if(preg_match('/Lewiston - Ny/i',$act_data['town'])){
    $act_data['country']='USA';
    $act_data['town']='Lewiston';
    $act_data['country_d1']='NY';
 
  }
  //print_r($act_data);
 if(preg_match('/^101 Reykjavik$/i',$act_data['postcode'])){
   $act_data['town']='Reykjavik';
    $act_data['country']='Iceland';
    $act_data['postcode']='101';
    // print_r($act_data);
  }
if(preg_match('/^101 Reykjavik$/i',$act_data['town'])){
   $act_data['town']='Reykjavik';
    $act_data['country']='Iceland';
    $act_data['postcode']='101';
    // print_r($act_data);
  }


 if(preg_match('/Fi\-\d{4,5}/i',$act_data['postcode'])  and $act_data['country_d1']==''){
   
    $act_data['country']='Finland';
    // print_r($act_data);
  }


  if(preg_match('/Drogheda.*Co Louth/i',$act_data['town'])){

    $act_data['town']='Drogheda';
    $act_data['country_d2']='Co Louth';
 
  }

  if(preg_match('/Tampa\s*.\s*Florida/i',$act_data['town'])){

    $act_data['town']='Tampa';
    $act_data['country_d1']='Florida';
 
  }

  if($act_data['country']=='USA' and   preg_match('/\-\s*ny/i',$act_data['town'])){

    $act_data['town']=preg_replace('/\-\s*ny/i','',$act_data['town']);
    $act_data['country_d1']='New York';
 
  }



  if(preg_match('/alberta/i',$act_data['town'])  and  preg_match('/Onoway/i',$act_data['a3']) ){
    $act_data['a3']='';
    $act_data['town']='Onoway';
    $act_data['country_d1']='Alberta';
 
  }




  if(preg_match('/alicante/i',$act_data['country_d2']) and $act_data['country']==''  ){
    $act_data['country']='Spain';
    $act_data['country_d1']='Valencia';
  }

  if(preg_match('/Alfaz del Pi - Alicante/i',$act_data['town'])  ){
    $act_data['town']='Alfaz del Pi';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


  if(preg_match('/03837 Muro de Alcoy/i',$header_data['city'])){
    $header_data['country']='Spain';
    $header_data['country_d1']='Valencia';
    $header_data['country_d2']='Alicante';
    $header_data['postcode']='03837';
    $header_data['city']='Muro de Alcoy';
  }




  if(preg_match('/Viterbo/i',$act_data['town'])  and preg_match('/Soriano Nel Cimino/i',$act_data['a2']) ){
    $act_data['country']='Italy';
    $act_data['town']='Soriano Nel Cimino';
    $act_data['country_d1']='Lazio';
    $act_data['country_d2']='Viterbo';
    $act_data['a2']='';
    $act_data['postcode']='01028';
  }



  // print_r($header_data);
  //exit;

  //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // Her we fix the distinct speciffic errors in the input fiels
  $act_data['town']=_trim($act_data['town']);

  if($act_data['country']=='Cyprus'){
    if($act_data['a3']=='1065 Nicosia'){
      $act_data['postcode']='1065';
      $act_data['town']='Nicosia';
      $act_data['a3']='';
    }
  }
    
  if($act_data['postcode']=='Cyprus')
    {
      $act_data['country']='Cyprus';
      $act_data['postcode']='';
    }
 
 
if(preg_match('/^\d{5}$/',$act_data['country']) and $act_data['postcode']==''){
    $act_data['postcode']=$act_data['country'];
    $act_data['country']='';
}

if(preg_match('/^granada$/i',$act_data['town']) and $act_data['country']==''){
    $act_data['country']='Spain';
}



  if($act_data['postcode']=='' and preg_match('/\s*no\-\d{4}\s*/',$act_data['town'],$match) ){
    $act_data['postcode']=_trim($match[0]);
    $act_data['town']=preg_replace('/\s*no\-\d{4}\s*/','',$act_data['town']);

  }

  // print_r($act_data);

  if($act_data['country']=='Korea South' or $act_data['country']=='South Korea'){
    if($act_data['a3']=='Seoul' ){
      $act_data['town']='Seoul';$act_data['a3']='';
    }    
    if(preg_match('/^Kangseo.Gu$/i',_trim($act_data['a2']))){
      $act_data['town_d1']='Gangseo-gu';
      $act_data['a2']='';
    }
    if($act_data['a1']=='105-207 Whagok-Dong'){
      $act_data['town_d2']='Hwagok-dong';
      $act_data['a1']='105-207';
    }

  }

  // print_r($act_data);

  if($act_data['a2']=='Yokneam Ilit' ){
    $act_data['a2']='';
    $act_data['town']='Yokneam Ilit';
  }
  if(preg_match('/Pyrgos.*Limassol/',$act_data['town'])){

    $act_data['town']='Pyrgos';
  }


  if($act_data['a1']=='Sharn Brook' and $act_data['town']=='Bedfordshire'){
    $act_data['a1']='NULL';
    $act_data['town']='Sharnbrook';
  }

  if($act_data['a2']=='Upper Marlboro' and $act_data['town']=='Md'){
    $act_data['a2']='';
    $act_data['town']='Upper Marlboro';
    $act_data['country_d1']='MD';
  }



  if($act_data['a2']=='55299 Nackenheim' and $act_data['town']=='Germany'){
    $act_data['a2']='';
    $act_data['country_d1']='';
    $act_data['postcode']='55299';
    $act_data['town']='Nackenheim';
  }

  if($act_data['town']=='Siverstone - Oregon'){
    $act_data['town']='Siverstone';
    $act_data['country_d2']='Oregon';
    $act_data['country']='USA';
  }

  if($act_data['town']=='5227 Nesttun'){
    $act_data['town']='Nesttun';
    $act_data['postcode']='5227';
  }
  if($act_data['town']=='3960 Stathelle'){
    $act_data['town']='Stathelle';
    $act_data['postcode']='3960';
  }
  if($act_data['town']=='45700 Kuusankoski'){
    $act_data['town']='Kuusankoski';
    $act_data['postcode']='45700';
  }
  if($act_data['town']=='06880 Kärrby'){
    $act_data['town']='Kärrby';
    $act_data['postcode']='06880';
  }
  if($act_data['town']=='2500 Valby'){
    $act_data['town']='Valby';
    $act_data['postcode']='2500';
  }

  if($act_data['town']=='21442 Malmö'){
    $act_data['town']='Malmö';
    $act_data['postcode']='21442';
  }
  if($act_data['town']=='11522 Stockholm'){
    $act_data['town']='Stockholm';
    $act_data['postcode']='11522';
  }

  if($act_data['town']=='1191 Jm Ouderkerk A/d Amstel'){
    $act_data['town']='Ouderkerk aan de Amstel';
    $act_data['postcode']='1191JM';
  }
  if($act_data['town']=='7823 Pm Emmen'){
    $act_data['town']='Emmen';
    $act_data['postcode']='7823PM';
  }
  if($act_data['town']=='1092 Budapest'){
    $act_data['town']='Budapest';
    $act_data['postcode']='1092';
  }


  if($act_data['town']=='Lanzarote, las Palmas' ){
    $act_data['town']='';
   
    $act_data['country_d1']='Canary Islands';
   
    $act_data['country_d2']='Las Palmas';
    $act_data['town']='';

    if( $act_data['a2']=='Costa Teguise'){
      $act_data['a2']='';
      $act_data['town']='Costa Teguise';
    }


  }


  if($act_data['town']=='Zugena - Provincia Almeria'){
    $act_data['town']='Zurgena';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    
  }

if($act_data['town']=='Alhama de Almeria, Almeria'){
    $act_data['town']='Alhama de Almeria';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    
  }
  
 if($act_data['town']=='Coulby Newham - Middlesbrough'){
   $act_data['country_d2']='Middlesbrough';
   $act_data['town']='Coulby Newham';
 }

if($act_data['town']=='Lerwick - Shetland Isles'){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Lerwick';
 }
if($act_data['town']=='Ollaberry - Shetland Islands'){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Ollaberry';
 }
if($act_data['town']=='Shetland - Shetland Islands' and $act_data['a1']=='Brae' ){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Brae';
   $act_data['a1']='NULL';
   
 }

 if(preg_match('/$MK40.*1hs/i',$act_data['postcode']) ){
   $act_data['country']='United Kingdom';
   
 }

 if(preg_match('/DH5.*9RS/i',$act_data['postcode'])  and $act_data['a1']=='Linden House' ){
   $act_data['a1']='Linden House';
   $act_data['a2']='2 Heather Drive';
   $act_data['a3']='';
   $act_data['town']='Houghton Le Spring';
  }

  if($act_data['town']=='Malaga' and $act_data['a2']=='Coin'){
    $act_data['town']='Coin';
    $act_data['country_d1']='Andalusia';
    $act_data['country_d2']='Malaga';
    $act_data['a2']='';
  }

 if($act_data['town']=='Villasor Pr. Cagliari'){
   $act_data['town']='Villasor';
   $act_data['country_d2']='Cagliari';
}

if($act_data['town']=='Leebotwood (nr Church Stretton'){
   $act_data['town']='Leebotwood Nr. Church Stretton';
}
if($act_data['town']=='Nea Moudhania - Chalkidiki'){
   $act_data['town']='Nea Moudhania';
   $act_data['country_d2']='Chalkidiki';
}

if($act_data['town']=='Cradley Heath, West Midlands'){
   $act_data['town']='Cradley Heath';
   $act_data['country_d2']='';
}

if($act_data['town']=='Garswood, Ashton In Makerf'){
   $act_data['town']='Ashton-in-Makerfield';
   $act_data['town_d2']='Garswood';
}
if($act_data['town']=='Boulogne Billancourt Cedex'){
   $act_data['town']='Boulogne Billancourt';
}

if($act_data['town']=='Furzton - Milton Keynes'){
   $act_data['town']='Milton Keynes';
$act_data['town_d2']='Furzton';
}

if($act_data['town']=='Glenfield - Leicester'){
   $act_data['town']='Leicester';
$act_data['town_d2']='Glenfield';
}
if($act_data['town']=='Edinburgh - Midlothian'){
   $act_data['town']='Edinburgh';
}

if($act_data['town']=='Killorglin - Co Kerry'){
   $act_data['town']='Killorglin';
}
if($act_data['town']=='Castledawson - Co Derry'){
   $act_data['town']='Castledawson';
}
if($act_data['town']=='Douglas, Isle of Man'){
   $act_data['town']='Douglas';
   $act_data['country']='Isle of Man';
}

if($act_data['town']=='Aberdeen, Aberdeenshire'){
   $act_data['town']='Aberdeen';
}

if($act_data['town']=='Elephant & Castle, London'){
   $act_data['town']='London';
$act_data['town_d2']='Elephant & Castle';
}
if($act_data['town']=='Muswell Hill, London'){
   $act_data['town']='London';
$act_data['town_d2']='Muswell Hill';
}

if($act_data['town']=='South Norwood, London'){
   $act_data['town']='London';
$act_data['town_d2']='South Norwood';
}

 if(preg_match('/Isle of Wight/i',$act_data['town']))
   $act_data['town']='';

if($act_data['town']=='Walkinstown - Dublin'){
   $act_data['town']='Dublin';
$act_data['town_d2']='Walkinstown';
}
if($act_data['town']=='Yarmouth - Isle of Wight'){
   $act_data['town']='Yarmouth';
   $act_data['country_d2']='Isle of Wight';
 }
if($act_data['town']=='New Port - Isle of Wight'){
   $act_data['town']='New Port';
   $act_data['country_d2']='Isle of Wight';
 }





if($act_data['town']=='Kingston-Upon Thames'){
   $act_data['town']='Kingston-Upon-Thames';
}

if($act_data['town']=='Bradford - On - Avon'){
   $act_data['town']='Bradford-On-Avon';
}

if($act_data['town']=='Tongham - Nr Farnham'){
   $act_data['town']='Tongham Nr. Farnham';
}

if($act_data['town']=='Hornbæk - Sjælland'){
   $act_data['town']='Hornbæk';
   $act_data['country_d2']='Sjælland';
}



  if($act_data['town']=='7779de Overijssel'){
    $act_data['town']='Overijssel';
    $act_data['postcode']='7779DE';
  }
  if($act_data['town']=='3015 Br Rotterdam'){
    $act_data['town']='Rotterdam';
    $act_data['postcode']='3015BR';
  }

  $act_data['postcode']=_trim(preg_replace('/the Netherlands/i','',$act_data['postcode']));

  if( preg_match('/boggon/i',$act_data['name'])  and preg_match('/35617|48051/i',$act_data['act'])    ){
    $act_data['name']='Temenos Academy';
  }
  if( preg_match('/dudden/i',$act_data['name'])  and preg_match('/25124/i',$act_data['act'])    ){
    $act_data['name']='Mr Jeff C Dudden';
  }
    

  if( preg_match('/Spain.*Canary Island/i',$act_data['country'])  and preg_match('/Arguineguin/i',$act_data['a2'])    ){
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Arguinegín';
    $act_data['country_d2']='Las Palmas';
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';

  }


  if( preg_match('/Spain.*Canary Island/i',$act_data['country'])      ){
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';
  }
  if( preg_match('/^Tenerife.*Canary Island/i',$act_data['town'])      ){
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';
    $act_data['town']='Tenerife';

    if($act_data['a2']=='Playa de las Americas' and $act_data['a3']=='Adeje'){
      $act_data['a2']='';
      $act_data['a3']='';
      $act_data['town']='Playa de las Americas';
    }

  }

  $header_data['postcode']=_trim( $header_data['postcode']);


  if(preg_match('/Northern Ireland/i',$act_data['town'])  ){
    $act_data['town']=_trim(preg_replace('/\,?\-?\s*Northern Ireland/i','',$act_data['town']));
  }

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

  if( preg_match('/^bfpo\s*\d/i',$act_data['town'])  and $act_data['postcode']=='' ){
    $act_data['postcode']=strtoupper($act_data['town']);
    $act_data['town']='';
  }


  if( preg_match('/^je\d/i',$header_data['postcode'])  and $header_data['country']=='' ){
    $header_data['country']='Jersey';
    if($header_data['city']=='Jersey')
      $act_data['city']='';
  }
  if( preg_match('/^im\d/i',$header_data['postcode'])  and $header_data['country']=='' ){
    $header_data['country']='Isle of Man';

    if($header_data['address2']=='Ramsey'){
      $header_data['city']='Ramsey';
      $header_data['address']='';
    }
  }
  if( preg_match('/^je\d/i',$act_data['postcode'])  and $act_data['country']=='' ){
    $act_data['country']='Jersey';
    if($act_data['town']=='Jersey')
      $act_data['town']='';
  }
  if( preg_match('/^im\d/i',$act_data['postcode'])  and $act_data['country']=='' ){
    $act_data['country']='Isle of Man';

    if($act_data['a2']=='Ramsey'){
      $act_data['town']='Ramsey';
      $act_data['a2']='';
    }
     
    if(preg_match('/Isle of Man/i',$act_data['town'])  ){
      $act_data['town']=_trim(preg_replace('/\,?\-?\s*Isle of Man/i','',$act_data['town']));

    }
  }

  if(preg_match('/^Norfolk$|^West Midlands$/i',$act_data['town']))
    $act_data['town']='';


  if($act_data['town']=='St.pauls Bay')
    $act_data['town']='St Pauls Bay';

  if($act_data['town']=='Outside the Royal Festival Hall'){
  $act_data['town']='London';
    $act_data['country_d2']='';
    $act_data['country_d1']='';
  }

  if($act_data['town']=='Ashton Under Lyne, Tameside')
    $act_data['town']='Ashton Under Lyne';


  if(preg_match('/Las Palmas de Gran Canaria/i',$act_data['a2'])  ){
    $act_data['a2']='';
    $act_data['country_d2']='Las Palmas';
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';

  }

  if($act_data['postcode']=='5260Demnark')
    $act_data['postcode']='DK-5260';
 

  if(preg_match('/ch6\s*5dz/i', $act_data['country'] )){
    
    $act_data['country']='';
    $act_data['postcode']='ch6 5dz';
  }
    
  if($act_data['country']=='Scotish Island' or $act_data['country']=='West Sussex' ){
    $act_data['country']='';
  }





  if( preg_match('/Mark Postage To France/i',$act_data['a1'])){
    $act_data['a1']='';
  }

  if( preg_match('/Spain.*Baleares/i',$act_data['country']) ){
    $act_data['country_d1']='Balearic Islands';
    $act_data['country']='Spain';
  }

  if($act_data['town']=='7182 Calvia - Mallorca'){
    $act_data['postcode']='07182';
    $act_data['town']='Calvia';
    $act_data['country_d1']='Balearic Islands';
    $act_data['country_d2']='Balearic Islands';
  }

  if($act_data['town']=='Lefkosia (nicosia)')
    $act_data['town']='Nicosia';


  if($act_data['town']=='07820 San Antonio - Ibiza')
    $act_data['postcode']='07820';

  if($act_data['postcode']=='Co Cork, Ireland')
    $act_data['postcode']='';
  if($act_data['town']=='Alicante - Spain')
    $act_data['town']='Alicante';
  if( preg_match('/San Antonio.*Ibiza/i',$act_data['town']) ){
    $act_data['town']='Sant Antoni de Portmany';
      
  }

  if( preg_match('/Perth.*Western Autralia/i',$act_data['town']) ){
    $act_data['town']='Perth';
    $act_data['country_d2']='Western Autralia';

  }
  if($act_data['a1']=='Kerem Maharal,' ){
    $act_data['a1']='NULL';
    $act_data['town']='Kerem Maharal';
  }

  if( preg_match('/Bs37 7rb|S2 3eh/i',$act_data['town'])){
    $act_data['town']='';
    $act_data['postcode']=strtoupper($act_data['town']);
  }

  if( preg_match('/castle market/i',$act_data['a3'])  and $act_data['postcode']=='' and $act_data['town']=='Sheffield' ){
    $act_data['postcode']='S1 2AD';
  }
  if($act_data['town']=='Albox, Almeria'){
    $act_data['town']='Albox';
    $act_data['country_d1']='Andalucía';
    $act_data['country_d2']='Almería';
  }

  if( preg_match('/^bfpo\s+\d+/i',$act_data['town'])){
    $act_data['country']='United Kingdom';
    $act_data['town']='';
    $act_data['postcode']=strtoupper($act_data['town']);
  }

  if($act_data['postcode']=='50004 Zaragoza'){
    $act_data['town']='Zaragoza';
    $act_data['postcode']='50004';
  }
  if($act_data['postcode']=='08530 Barcelona'){
    $act_data['town']='Barcelona';
    $act_data['postcode']='08530';
  }
  if($act_data['postcode']=='28300 Madrid'){
    $act_data['town']='Madrid';
    $act_data['postcode']='28300';
  }
  if($act_data['postcode']=='28013 Madrid'){
    $act_data['town']='Madrid';
    $act_data['postcode']='28013';
  }

  if($act_data['act']=='27821'){
    $act_data['act']='21179';
    $act_data['name']='Soap & Soak';
  }
  

  if(strtolower($act_data['town'])=='la romana (alicante)'){
    $act_data['town']='La Romana';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }

  if($act_data['town']=='Sax (alicante)'){
    $act_data['town']='Sax';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


  if($act_data['postcode']=='30383 Cartagena'){
    $act_data['town']='Cartagena';
    $act_data['postcode']='30383';
  }
  if($act_data['postcode']=='07760 Ciutadella'){
    $act_data['town']='Ciutadella';
    $act_data['postcode']='07760';
  }
    

  if($act_data['town']=='Tucson Az'){
    $act_data['town']='Tucson';
    $act_data['country_d2']='Arizona';
  }

  if($act_data['country']=='Ireland' and $act_data['a3']=='Castleblaney' ){
    $act_data['town']='Castleblaney';
    $act_data['country_d2']='Monaghan';
    $act_data['a3']='';
  }


  if($act_data['town']=='Port Angeles (wa)'){
    $act_data['town']='Port Angeles';
    $act_data['country_d2']='WA';
  }
  if($act_data['town']=='Beverly Hills (ca)'){
    $act_data['town']='Beverly Hills';
    $act_data['country_d2']='California';
  }
  if($act_data['town']=='Milwaukee, Wi'){
    $act_data['town']='Milwaukee';
    $act_data['country_d2']='Wi';
  }

  if($act_data['town']=='Kingston, Ma'){
    $act_data['town']='Kingston';
    $act_data['country_d2']='Ma';
  } 
  if($act_data['town']=='Mcdonough, Ga'){
    $act_data['town']='Mcdonough';
    $act_data['country_d2']='Ga';
  }
  if($act_data['town']=='Bridgewater, Nj'){
    $act_data['town']='Bridgewater';
    $act_data['country_d2']='NJ';
  }
  if($act_data['town']=='Marietta, Ga'){
    $act_data['town']='Marietta';
    $act_data['country_d2']='Ga';
  }
  if($act_data['town']=='Duluth - Ga'){
    $act_data['town']='Duluth';
    $act_data['country_d2']='Ga';
  } 


  if($act_data['town']=='Hoffman Estates - Il.'){
    $act_data['town']='Hoffman Estates';
    $act_data['country_d2']='Il';
  } 
  if($act_data['town']=='Shelton Ct'){
    $act_data['town']='Shelton';
    $act_data['country_d2']='Ct';
  }
  if($act_data['town']=='Raton - Nm.'){
    $act_data['town']='Raton';
    $act_data['country_d2']='NM';
  }
  if($act_data['town']=='Monett, Mo'){
    $act_data['town']='Monett';
    $act_data['country_d2']='MO';
  }
  if($act_data['town']=='Alton, Il'){
    $act_data['town']='Alton';
    $act_data['country_d2']='Il';
  } 
  if($act_data['town']=='Zanesville, Ohio'){
    $act_data['town']='Zanesville';
    $act_data['country_d2']='Ohio';
  }
  if($act_data['town']=='Pinola, Ms'){
    $act_data['town']='Pinola';
    $act_data['country_d2']='MS';
  }
  if($act_data['town']=='Port Jefferson Station - Ny'){
    $act_data['town']='Port Jefferson Station';
    $act_data['country_d2']='NY';
  } 
  if($act_data['town']=='Houston - Texas'){
    $act_data['town']='Houston';
    $act_data['country_d2']='Texas';
  }
  if($act_data['town']=='Cambell Hall - Ny'){
    $act_data['town']='Cambell Hall';
    $act_data['country_d2']='NY';
  } 
  if($act_data['postcode']=='04400 Almeria - SPAIN'){
    $act_data['postcode']='04400';
    $act_data['country_d1']='Andalucía';
    $act_data['country_d2']='Almería';
  }
    
  if( preg_match('/Whaley Bridge, Derbyshire Sk23 7jg/i',$act_data['town'])){
    $act_data['country']='United Kingdom';
    $act_data['town']='Whaley Bridge';
    $act_data['postcode']='SK23 7JG';
  }

  if( preg_match('/Beirut\s*.\s*Lebanon/i',$act_data['country'])){
    $act_data['country']='Lebanon';
    $act_data['town']='Beirut';
  }

  if( preg_match('/01902 850 006|north ayrshire|stoke.on trent|Suffolk|Norfolk/i',$act_data['country']))
    $act_data['country']='';

  if( preg_match('/Channel Islands/i',$act_data['country']) ){
      
    if(preg_match('/^(jersey\s+)?\s*je/i',$act_data['postcode'])){
	
      $act_data['postcode']=preg_replace('/\s*jersey\s*/i','',$act_data['postcode']);
      $act_data['country']='Jersey';



    }
  }


  if( preg_match('/ireland/i',$act_data['country']) and preg_match('/^bt/i',$act_data['postcode']) )
    $act_data['country']='United Kingdom';
    
  if( preg_match('/Co Kerry, Ireland/i',$act_data['country'])  )
    $act_data['country']='Ireland';


  if($act_data['act']=='21808'){
    $act_data['name']='Luss Glass Studio';
    $act_data['contact']='Janine Smith';
  }

  if($act_data['act']=='33387'){
    $act_data['act']='9050';
  }


  if($act_data['name']=='Crocodile Antiques (1)')
    $act_data['name']='Crocodile Antiques';

  // print $act_data['mob'].'-'.$act_data['act']."-\n";
  if($act_data['mob']=='01723 376447' and $act_data['act']=='26456'){
    $act_data['mob']='';
  }

  if($act_data['contact']=='Thandi' and $act_data['act']=='21217'){
    $act_data['contact']='Thandi Viljoen';
  }

    
    
  if(preg_match('/G12 8aa/i',$act_data['country'])){
    $act_data['country']='';
    $act_data['postcode']='G12 8AA';
  }
    
    
  $split_town=preg_split('/\s*,\s*/i',$act_data['town']);
  if(count($split_town)==2){
    if(preg_match('/jersey/i',$split_town[1])){
      $act_data['town']=$split_town[0];
      $act_data['country']='Jersey';
    }
	
  }




  if(check_email_address($act_data['country'])){
    if($act_data['email']=='')
      $act_data['email']=$act_data['country'];
    $act_data['country']='';
  }
  if(preg_match('/Clwyd/i',$act_data['country']))$act_data['country']='';
    
  if($act_data['country']=='Harmelen (netherlands)'){
    $act_data['town']='Harmelen';
    $act_data['country']='netherlands';
  }

  if($act_data['postcode']=='USA'){
    $act_data['postcode']='';
    $act_data['country']='United States';
  }

  if($act_data['town']=='Fgura, Europe'){
    $act_data['town']='Fgura';
  }
  if($act_data['town']=='3800 Limburg'){
    $act_data['town']='Limburg';
    $act_data['postcode']='3800';
  }
  if($act_data['town']=='West Vlaanderen'){
    $act_data['town']='West Vlaanderen';
    $act_data['postcode']='8800';
  }





  if($act_data['town']=='Nordrheinwestfalen' and $act_data['a2']=='Bochum'){
    $act_data['town']='Bochum';
    $act_data['country_d1']='Nordrhein-Westfalen';
    $act_data['a2']='';
  }

  if($act_data['town']=='Schwaig, Bavaria'){
    $act_data['town']='Schwaig';
    $act_data['country_d1']='Bayern';
  }
  if($act_data['town']=='Central Milton Keynes'){
    $act_data['town']='Milton Keynes';
  }
  if($act_data['town']=='No-5353 Straume'){
    $act_data['town']='Straume';
    $act_data['postcode']='No-5353';
  }

  if($act_data['a2']=='Vibrac' and $act_data['a3']=='Charente'){
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Vibrac';
    $act_data['country_d1']='Poitou-Charentes';
    $act_data['country_d2']='Charente';

  }



  if($act_data['town']=='Tiefenau' and $act_data['postcode']=='1609'){
    $act_data['country_d1']='Sachsen';
    $act_data['postcode']='01609';
  }

  if($act_data['town']=='Abingdon Oxfordshire')
    $act_data['town']='Abingdon';

  if($act_data['town']=='Bromham, Chippenham')
    $act_data['town']='Bromham';
  if($act_data['town']=='Buckinghamshire')
    $act_data['town']='';

  // print_r($act_data);    
  if(preg_match('/\s*eire\*/i',$act_data['postcode'])){
    $act_data['postcode']='';
    $act_data['country']='Ireland';
  }
    
  if(preg_match('/MO 63136/i',$act_data['postcode']) and  $act_data['country']='USA'  ){
    $act_data['postcode']='63136';
    $act_data['country_d1']='MO';
  }
    


  if($act_data['town']=='Halle' and $act_data['postcode']=='33790'){
    $act_data['country_d1']='Nordrhein-Westfalen';
      
  }
    
    
  if(preg_match('/^\s*\d{4,6}\s*$/',$act_data['town'])){
    $act_data['postcode']=$act_data['town'];
    $act_data['town']='';
  }
  if($act_data['town']=='Bilbao - Vizcaya'){
    $act_data['town']='Bilbao';
    $act_data['country_d2']='Vizcaya';
  }

  if($act_data['country']=='Balearic Isles'){
    $act_data['country']='Spain';
    $act_data['country_d1']='Balearic Islands';

  }
  if(preg_match('/^\d{5}$/',_trim($act_data['country'])) and $act_data['postcode']==''){
    $act_data['postcode']=$act_data['country'];
    $act_data['country']='Spain';
  }

  if(preg_match('/spain/i',_trim($act_data['country'])){
    $act_data['country']='Spain';
  }

   if(preg_match('/^\d{5}$/',_trim($header_data['country'])) and $header_data['postcode']==''){
    $header_data['postcode']=$header_data['country'];
    $header_data['country']='Spain';
  }


   if(preg_match('/^España$/i',_trim($act_data['country_d2'])) and $act_data['country']==''){
    $act_data['country_d2']='';
    $act_data['country']='Spain';
  }



  if($act_data['country']=='Guernsey, C.i')
    $act_data['country']='Guernsey';
    
  if($act_data['town']=='Guernsey, C.i'){
    $act_data['town']='Guernsey';
    $act_data['country']='Guernsey';
  }

  if($act_data['town']=='South yorkshire'){
    $act_data['town']='';
      
    if($act_data['a3']!=''){
      $act_data['town']=$act_data['a3'];
      $act_data['a3']='';
    }else if($act_data['a2']!=''){
      $act_data['town']=$act_data['a2'];
      $act_data['a2']='';
    }
  }
    
  //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // fix contracts
    
    
  $extra_contact=false;
  if($act_data['contact']!=''){

    $_contact=$act_data['contact'];
    $split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
    if(count($split_names)==2){
      $split_names1=preg_split('/\s+/i',$split_names[0]);
      $split_names2=preg_split('/\s+/i',$split_names[1]);
      if(count($split_names1)==1 and count($split_names2)==2 ){
	$name1=$split_names1[0].' '.$split_names2[1];
	$name2=$split_names[1];
      }else{
	$name1=$split_names[0];
	$name2=$split_names[1];
      }
      $act_data['contact']=$name1;
      $extra_contact=$name2;
      if($_contact==$act_data['name']){
	$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
      }

    }
    $there_is_contact=true;
  }else{
    $there_is_contact=false;
    if(!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb/i',$act_data['name']))
      $act_data['contact']=$act_data['name'];

  }
    
  //  print $act_data['contact']." >>> $extra_contact   \n ";
    
  if($act_data['name']!=$act_data['contact'] )
    $tipo_customer='Company';
  else{	  
    $tipo_customer='Person';
      
      
    if($act_data['name']=='Jill Clare' and  $act_data['contact']=='Jill Clare'){
      $tipo_customer='Company';
      $act_data['contact']='';
    }
  }

  //  print_r($act_data);
  
  // print_r($header_data);

  //-----------------------------------------
  if(!isset($act_data['town_d1']))
    $act_data['town_d1']='';
  if(!isset($act_data['town_d2']))
    $act_data['town_d2']='';

  if(preg_match('/^c\/o/i',$act_data['a1'])){
    $co=$act_data['a1'];
    $act_data['a1']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a2'])){
    $co=$act_data['a2'];
    $act_data['a2']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a3'])){
    $co=$act_data['a3'];
    $act_data['a3']='';
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


    


    $a_diff=array_diff_assoc($del_address_data,$shop_address_data);

    if(isset($a_diff['country_d1_id']))
      unset($a_diff['country_d1_id']);
    if(isset($a_diff['country_d2_id']))
      unset($a_diff['country_d2_id']);
    //   print"***";

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
	 and ($shop_address_data['country']=='' 
	      
	      
	      )){
	//	print "PC of the del address taken (a)\n";
	$different_del_address=false;
	$shop_address_data['postcode']=$del_address_data['postcode'];
      }
    }


  










    if(count($a_diff)==1){
    
      if(array_key_exists('postcode',$a_diff) 
	 and ($shop_address_data['country']=='' 
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


  $act_data['tel']=preg_replace('/\[\d*\]/','',$act_data['tel']);
  $act_data['tel']=preg_replace('/\(/','',$act_data['tel']);
  $act_data['tel']=preg_replace('/\)/','',$act_data['tel']);
  $act_data['fax']=preg_replace('/\[\d*\]/','',$act_data['fax']);
  $act_data['fax']=preg_replace('/\(/','',$act_data['fax']);
  $act_data['fax']=preg_replace('/\)/','',$act_data['fax']);
  $act_data['mob']=preg_replace('/\[\d*\]/','',$act_data['mob']);
  $act_data['mob']=preg_replace('/\(/','',$act_data['mob']);
  $act_data['mob']=preg_replace('/\)/','',$act_data['mob']);

  $email_data=guess_email($act_data['email']);
  
  // print_r($email_data);
  //print "$tipo_customer\n";
  //print_r($act_data);
    
  $shop_address_data['default_country_id']=30;

  if(isset($act_data['act']))
    $customer_data['other id']=$act_data['act'];
  else
    $customer_data['other id']='';

  
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
    $customer_data['other id']=$act_data['act'];
  else
    $customer_data['other id']='';
      


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
 
    //     print_r($cols);
    //exit;
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
      ////     print "$key  \n";
      //      print_r($map_data);
      //print "**** $key ".$map_data['row']." ".$map_data['col']."\n";
      $_data=$raw_header_data[$map_data['row']][$map_data['col']];
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

// function set_pickers_and_packers($order_id,$header_data){
//   $db =& MDB2::singleton();
//   $picker_ids=get_user_id($header_data['pickedby'],$order_id,'picked');
//   $packer_ids=get_user_id($header_data['packedby'],$order_id,'packed');

//   if(count($picker_ids)==0){
//     $sql=sprintf('insert into pick (order_id,picker_id,share) values (%d,0,1.00)',$order_id);
//     //mysql_query($sql);
//     mysql_query($sql);
//   }
//   if(count($packer_ids)==0){
//     $sql=sprintf('insert into pack (order_id,packer_id,share) values (%d,0,1.00)',$order_id);
//     //mysql_query($sql);
//     mysql_query($sql);
//   } 

//   foreach($picker_ids as $picker_id){
//     $share=1/count($picker_ids);
//     $sql=sprintf('insert into pick (order_id,picker_id,share) values (%d,%d,%.2f)',$order_id,$picker_id,$share);
//     // mysql_query($sql);
//     mysql_query($sql);
//   }
//   foreach($packer_ids as $packer_id){
//     $share=1/count($packer_ids);
//     $sql=sprintf('insert into pack (order_id,packer_id,share) values (%d,%d,%.2f)',$order_id,$packer_id,$share);
//     //mysql_query($sql);
//     mysql_query($sql);
//   }
  

// }


function get_user_id($oname,$return_xhtml=false,$tag=''){


  
  $ids=array();
  if($oname=='' or is_numeric($oname)){
    if($return_xhtml)
      return array('id'=>array(0),'xhtml'=>_('Unknown'));
    else
      return array(0);
    
  }


  $_names=array();
  
  $_names=preg_split('/\s*(\+|\&|,+|\/|\-)\s*/',$oname);

  $xhtml='';

   foreach($_names as $_name){    
     $original_part=$_name;
     $_name=_trim(strtolower($_name));
    if($_name=='')
      continue;
    $original_name=$_name;
    
    $_name=preg_replace('/^\s*/','',$_name);
    $_name=preg_replace('/\s*$/','',$_name);
    if(preg_match('/michele|michell/i',$_name)   )
      $_name='michelle';
    else if( $_name=='salvka' or    preg_match('/^slavka/i',$_name) or $_name=='slavke' or $_name=='slavla' )
      $_name='slavka';
 else if(preg_match('/^malcom$/i',$_name)  )
      $_name='malcolm';

    else if(preg_match('/katerina/i',$_name) or $_name=='katka]' or   $_name=='katk'   )
      $_name='katka';
 else if(preg_match('/richard w/i',$_name) or $_name=='rich')
      $_name='richard';
    else if(preg_match('/david\s?(hardy)?/i',$_name))
      $_name='david';
    else if(preg_match('/philip|phil/i',$_name))
      $_name='philippe';
     else if(preg_match('/amanada|amand\s*$/i',$_name))
      $_name='amanda';
     else if(preg_match('/janette/i',$_name) or $_name=='jqnet' )
      $_name='janet';
  else if(preg_match('/pete/i',$_name))
      $_name='peter';
 else if(preg_match('/debra/i',$_name))
      $_name='debbie';
 else if(preg_match('/sam/i',$_name))
      $_name='samantha';
     else if($_name=='philip' or $_name=='ph' or $_name=='phi' )
       $_name='philippe';
     else if(  $_name=='aqb' or $_name=='kj' or  $_name=='act' or $_name=='tr'  or    $_name=='other' or $_name=='?' or $_name=='bb'){
       if($return_xhtml)
	 return array('id'=>array(0),'xhtml'=>_('Unknown'));
       else
	 return array(0);

     }
     else if($_name=='thomas' or $_name=='tb' or preg_match('/^\s*tomas\s*$/i',$_name) or $_name=='tom' )
       $_name='tomas';
     else if($_name=='alam' or $_name=='aw' or   $_name=='al' or   $_name=='al.'  or  $_name=='ala' )
       $_name='alan';
 else if($_name=='carol')
       $_name='carole';

     else if($_name=='dushan' or $_name=='duscan' or $_name=='dus')
       $_name='dusan';
     else if($_name=='eli' or $_name=='eilska' or $_name=='eilsk' or $_name=='elsika' or $_name=='elishka')
       $_name='eliska';
     else if($_name=='jiom' or $_name=='tim'  or $_name=='jimbob'    or  $_name=='jikm')
       $_name='jim';
     else if($_name=='beverley' or $_name=='ber'  or $_name=='bav')
       $_name='bev';
     else if(   $_name=='albett' or  $_name=='alnert'  or    $_name=='alberft' or   $_name=='alberyt' or    $_name=='alabert'  or   $_name=='albet' or $_name=='albert ' or $_name=='albet ' or$_name=='alberto'  or $_name=='alb'  or $_name=='albery' or $_name=='alberty' or $_name=='ac'  or $_name=='albeert'  )
       $_name='albert';
     else if($_name=='ab' or $_name=='adr')
       $_name='adriana';
     else if($_name=='jamet' or $_name=='jante' or $_name=='jant' or $_name=='jnet' or $_name=='j' or $_name=='jenet'  or $_name=='jsnet'  )
       $_name='janet';
     else if($_name=='slvaka')
       $_name='slavka';
     else if($_name=='ct')
       $_name='craig';
     else if($_name=='k ' or $_name=='k' or $_name=='katerina2')
       $_name='katka';
     else if($_name=='daniella' or $_name=='daniella' )
       $_name='daniela';
     else if($_name=='cc' or $_name==' cc')
      $_name='chris';
    else if($_name=='bret')
      $_name='brett';
    else if($_name=='lucia' or $_name=='luc')
      $_name='lucie';
    else if($_name=='mat')
      $_name='matus';
    else if($_name=='ob' or $_name=='o.b.')
      $_name='olga';
    else if($_name=='stacy')
      $_name='stacey';
     else if($_name=='kkzoe' or $_name=='kzoe')
      $_name='zoe';
  else if($_name=='cph')
      $_name='caleb';
 else if($_name=='jenka')
      $_name='lenka';
 else if($_name=='jjanka')
      $_name='janka';
else if($_name=='jarina')
      $_name='jirina';

    

    $sql=sprintf("select `Staff Key`,`Staff Alias` from `Staff Dimension` where `Staff Alias`=%s",prepare_mysql($_name));
    //   print "$sql\n";
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $id=$row['Staff Key'];
      $ids[$id]=$id;

      $xhtml.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($row['Staff Alias']));

    }else{
      //      $sql=sprintf("insert into todo_users (name,order_id,tipo) values ('%s','%s','%s')",addslashes($original_name),$order_id,$tipo);
      //    print "$sql\n";
      //mysql_query($sql);
      //print "Staff name not found $oname \n";
      $id=0;
      $ids[$id]=$id;

      $xhtml.=sprintf(', %s',$original_name);


    }
  }
   $_ids=array();
   foreach($ids as $values){
     $_ids[]=$values;
   }

   $xhtml=preg_replace("/^\,\s*/","",$xhtml);

   if($return_xhtml)
     return array('id'=>$_ids,'xhtml'=>$xhtml);
   else
     return $_ids;
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






// function delete_transactions($order_id){

//   $sql=sprintf("delete from bonus where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from transaction where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from todo_transaction where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from outofstock where order_id=%d",$order_id); mysql_query($sql);
//   $sql=sprintf("delete from debit where tipo=2 and order_affected_id=%d",$order_id); mysql_query($sql);
// }



function is_shipping_supplier($data,$date_order){


  //  if(preg_match('/^(per post|Pacel Force|Airmail|Amtrak.*|1st Class Post|Amstrak|via Frans Maas|Fist class post|DBL|apc|post|interlink|parcel\s*force|ups|fedex|royal\s*mail|by post|printmovers|1st class|first class|frans mass|frans maas|apc to collect|post . standart parcel|post . 2 parcels.*|post office|schenker|parcel force worldwide|amtrak|percel porce|parceline|post 1st|dfds transport|dpd|dbl pallet|tnt|interlink\s*express?|amtrack|post 1at class|post \- sing for|dvs|.*royal mail.*|Parce Force|Parcel Force Wordwide|Roayl Mail|Post 1st Class|Parcel Line|dbl|POST SIGNED FOR|Parcelforce.*|AMRAK|Post Sign For|post .*|FedEx .*|dbl|Parcel Force .*|DSV pallet|Hastings Freight|Amtrak|apc .*|dpd .*|dbl|Parcel F orce Sat Del|Mc Grath Freigth|Parcel Porce)$/i',_trim($data['notes'])))
   if(preg_match('/^(Use his own shipper|Cust owns carrier|cust own courier|customer own carrier|customer own carrier|own transport|Own Courrier|own driver)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='_OWN';
  }elseif(preg_match('/^(Via Post Office|Send By Post|First Class Post|Sent by post|Royalmail.*|per post|Airmail|1st Class Post|Fist class post|post|royal\s*mail|by post|1st class|first class|post . standart parcel|post . 2 parcels.*|post office|post 1st|post 1at class|post \- sing for|.*royal mail.*|Roayl Mail|Post 1st Class|POST SIGNED FOR|Post Sign For|post .*)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='RoyalM';
  } elseif(preg_match('/^(ParcelForcel|Parcelforce.*|PacelForce|Parcel Force.*|Parcel Forcce|Pacel Force|parcel\s*force|parcel force worldwide|percel porce|Parce Force|Parcel Force Wordwide|Parcel F orce Sat Del|Parcel Porce|parcel force.*)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='PForce';
  } elseif(preg_match('/^(DSV.*|frans maas|frans mass|via Frans Maas)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='DSV';
  }elseif(preg_match('/^(Amtrac|Amstrak|Amtrak.*|amtrak|amtrack|AMRAK)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='DSV';
  }elseif(preg_match('/^(dpd|parcel line|dpd .*|Parceline)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='DPD';
  }elseif(preg_match('/^(interlink.*)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='ILink';
  }elseif(preg_match('/^(tnt)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='TNT';
  }elseif(preg_match('/^(fedex.*)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='Fedex';
  }elseif(preg_match('/^(ups)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='UPS';
  }elseif(preg_match('/^(dfds.*)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='DFDS';
  }elseif(preg_match('/^(Mc Grath Freigth)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='McGF';
  }elseif(preg_match('/^(Hastings Freight)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='HastFre';
  }elseif(preg_match('/^(apc|apc .*)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='APC';
  }elseif(preg_match('/^(dbl|dbl .*)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='APC';
  }elseif(preg_match('/^(Future Fowarding)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='FutFo';
  }elseif(preg_match('/^(Printmovers)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='PrintM';
  }elseif(preg_match('/^(Schenker)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['shipper_code']='Schenker';
  }elseif(preg_match('/^(shang|andy|andy to take( tomorrow)?|to be deliv. by Neil|Give to Malcom)$/i',_trim($data['notes']))){
   
    $data['shipper_code']='_Other';
  }
  


 if(preg_match('/^(Use his own shipper|Cust owns carrier|cust own courier|customer own carrier|customer own carrier|own transport|Own Courrier|own driver)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='_OWN';
  }elseif(preg_match('/^(Via Post Office|Send By Post|First Class Post|Sent by post|Royalmail.*|per post|Airmail|1st Class Post|Fist class post|post|royal\s*mail|by post|1st class|first class|post . standart parcel|post . 2 parcels.*|post office|post 1st|post 1at class|post \- sing for|.*royal mail.*|Roayl Mail|Post 1st Class|POST SIGNED FOR|Post Sign For|post .*)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='RoyalM';
  } elseif(preg_match('/^(ParcelForcel|Parcelforce.*|PacelForce|Parcel Force.*|Parcel Forcce|Pacel Force|parcel\s*force|parcel force worldwide|percel porce|Parce Force|Parcel Force Wordwide|Parcel F orce Sat Del|Parcel Porce|parcel force.*)$/i',_trim($data['notes2']))){
   // exit("s".$data['notes2']."xxxxxxx");
   $data['notes2']='';
    $data['shipper_code']='PForce';
  } elseif(preg_match('/^(DSV.*|frans maas|frans mass|via Frans Maas)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='DSV';
  }elseif(preg_match('/^(Amtrac|Amstrak|Amtrak.*|amtrak|amtrack|AMRAK)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='DSV';
  }elseif(preg_match('/^(dpd|parcel line|dpd .*|Parceline)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='DPD';
  }elseif(preg_match('/^(interlink.*)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='ILink';
  }elseif(preg_match('/^(tnt)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='TNT';
  }elseif(preg_match('/^(fedex.*)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='Fedex';
  }elseif(preg_match('/^(ups)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='UPS';
  }elseif(preg_match('/^(dfds.*)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='DFDS';
  }elseif(preg_match('/^(Mc Grath Freigth)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='McGF';
  }elseif(preg_match('/^(Hastings Freight)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='HastFre';
  }elseif(preg_match('/^(apc|apc .*)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='APC';
  }elseif(preg_match('/^(dbl|dbl .*)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='APC';
  }elseif(preg_match('/^(Future Fowarding)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='FutFo';
  }elseif(preg_match('/^(Printmovers)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='PrintM';
  }elseif(preg_match('/^(Schenker)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['shipper_code']='Schenker';
  }elseif(preg_match('/^(shang|andy|andy to take( tomorrow)?|to be deliv. by Neil|Give to Malcom)$/i',_trim($data['notes2']))){
   
    $data['shipper_code']='_Other';
  }
  











 $the_supplier_data=array(
			  'editor'=>array('Date'=>$date_order)
			  ,'Supplier Name'=>$data['shipper_code']
			  ,'Supplier Code'=>$data['shipper_code']
			  );
 if($data['shipper_code']!='' and $data['shipper_code']!='_OWN' and $data['shipper_code']!='_Other'){

     //print $data['shipper_code']."<---\n";
     $supplier=new Supplier('code',$data['shipper_code']);
     if(!$supplier->id){
       
       $supplier=new Supplier('new',$the_supplier_data);
     }
     //exit;
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

function is_staff_sale($data){
  $data['staff sale key']=0;

if(preg_match('/cash sale/i',$data['trade_name'])  or preg_match('/cash sale/i',$data['notes'])){
  if($data['shipping']==0){
  $data['shipper_code']='NA';
  $data['collection']='Yes';
  }
  


  $tmp=preg_replace('/^Staff Sales?\s*\-?\s*/i','',$data['customer_contact']);
  // exit("x:".$tmp."\n");
  $staff_id=get_user_id($tmp);
  if(count($staff_id)==1 and $staff_id[0]!=0 ){
    $data['staff sale key']=$staff_id[0];
    $data['shipper_code']='NA';
    $data['collection']='Yes';
    $data['staff sale']='Yes';

    if(preg_match('/'.$tmp.'/i',$data['notes']))
      $data['notes']='';
     if(preg_match('/'.$tmp.'/i',$data['notes2']))
      $data['notes2']='';

     if(preg_match('/^cash sale$/i',$data['notes2']))
       $data['notes2']='';
     if(preg_match('/^cash sale$/i',$data['notes']))
       $data['notes']='';
  }
  


  if($data['staff sale key']==0){
    $tmp=preg_replace('/Staff Sales?\s*\-?\s*/i','',$data['notes']);
    $staff_id=get_user_id($tmp);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
      $data['staff sale key']=$staff_id[0];
    $data['shipper_code']='NA';
    $data['collection']='Yes';
    $data['staff sale']='Yes';
     $data['notes']='';
if(preg_match('/^cash sale$/i',$data['notes2']))
       $data['notes2']='';
     if(preg_match('/^cash sale$/i',$data['notes']))
       $data['notes']='';
       if(preg_match('/'.$tmp.'/i',$data['notes2']))
	 $data['notes2']='';
    }
  }


  if($data['staff sale key']==0){
    $tmp=preg_replace('/Staff Sales?\s*\-?\s*/i','',$data['notes2']);
    $staff_id=get_user_id($tmp);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
      $data['staff sale key']=$staff_id[0];
      $data['shipper_code']='NA';
      $data['collection']='Yes';
      $data['staff sale']='Yes';
      $data['notes2']='';
if(preg_match('/^cash sale$/',$data['notes2']))
       $data['notes2']='';
     if(preg_match('/^cash sale$/',$data['notes']))
       $data['notes']='';
     
    }
  }
  

}


 if(preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['trade_name']))){
   
   $data['staff sale']='Yes';
   $data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['trade_name']);
   $staff_id=get_user_id($data['staff sale name']);
   $data['staff sale key']=$staff_id[0];
   
   $data['shipper_code']='NA';
   $data['collection']='Yes';

  }



  if(preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['notes']))){

    $data['staff sale']='Yes';
    $data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['notes']);
    $staff_id=get_user_id($data['staff sale name']);
    $data['staff sale key']=$staff_id[0];
    $data['notes']='';
    $data['shipper_code']='NA';
    $data['collection']='Yes';
    
  }
if(preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['notes2']))){

    $data['staff sale']='Yes';
    $data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['notes2']);
    $staff_id=get_user_id($data['staff sale name']);
    $data['staff sale key']=$staff_id[0];
    $data['notes2']='';
    $data['shipper_code']='NA';
    $data['collection']='Yes';
    
  }
  if(preg_match('/^(staff sale|staff)$/i',_trim($data['notes']))){
    $data['notes']='';
    $data['staff sale']='Yes';
   $data['shipper_code']='NA';
     $data['collection']='Yes';


       $staff_id=get_user_id($data['customer_contact']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
    

  }
    
if(preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['postcode']))){

    $data['staff sale']='Yes';
    $data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['postcode']);
    $staff_id=get_user_id($data['staff sale name']);
    $data['staff sale key']=$staff_id[0];

    $data['shipper_code']='NA';
    $data['collection']='Yes';
    
  }
 
    



  

  if(preg_match('/^(staff sale|staff)$/i',_trim($data['notes2']))){
    $data['notes2']='';
    $data['staff sale']='Yes';
   $data['shipper_code']='NA';
     $data['collection']='Yes';
      $data['staff sale key']=0;

        $staff_id=get_user_id($data['customer_contact']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
     $staff_id=get_user_id($data['address1']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
    $staff_id=get_user_id($data['address2']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }

  }


 if(preg_match('/^staff sales?$/i',_trim($data['trade_name']))){
    $data['staff sale']='Yes';
    $data['shipper_code']='NA';
    $data['collection']='Yes';

    
   $staff_id=get_user_id($data['address1']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
    $staff_id=get_user_id($data['address2']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
    $staff_id=get_user_id($data['customer_contact']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }

    


  }


 if(preg_match('/^staff sales?$/i',_trim($data['customer_contact']))){
    $data['staff sale']='Yes';
    $data['shipper_code']='NA';
    $data['collection']='Yes';
   $staff_id=get_user_id($data['address1']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
     $staff_id=get_user_id($data['address2']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
    $staff_id=get_user_id($data['customer_contact']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
  }

 //print_r($data);exit;

if(preg_match('/^staff sales?|Ancient Winsdom Staff$/i',_trim($data['postcode']))){
    $data['staff sale']='Yes';
    $data['shipper_code']='NA';
    $data['collection']='Yes';
   $staff_id=get_user_id($data['address1']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
      $staff_id=get_user_id($data['address2']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
    $staff_id=get_user_id($data['customer_contact']);
    if(count($staff_id)==1 and $staff_id[0]!=0 ){
       $data['staff sale key']=$staff_id[0];
    }
  }

//print_r($data);exit;




//print_r($data);exit;

  return $data;

}



function get_tax_number($data){
  global $myconf;
  $data['tax_number']='';
  $note='';
  $tax_number='';
  if(!isset($data['dn_country_code']) or !$data['dn_country_code'] or $data['dn_country_code']=='0')
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
  $tax_number=_trim($tax_number);
  // print "$tax_number\n";
 $tax_number=preg_replace('/tax id\s*:?\s*-?\s*/i','',$tax_number);
 $tax_number=preg_replace('/V\.a\.t\. N.*:\s*-?\s*/i','',$tax_number);

 $tax_number=preg_replace('/VAT NO\s*-\s*/i','',$tax_number);
  $tax_number=preg_replace('/^VAT No\.\:\s*/i','',$tax_number);
  $tax_number=preg_replace('/^vat no\s*(\.|:)?\s*/i','',$tax_number);
  $tax_number=preg_replace('/^vat\s*(\:|\-)?\s*/i','',$tax_number);
  $tax_number=preg_replace('/^vat\s*reg\*(\:|\-)?\s*/i','',$tax_number);
 $tax_number=preg_replace('/\-?\s*Checked and Valid$/i','',$tax_number);
 $tax_number=preg_replace('/\-?\s*valid and checked$/i','',$tax_number);
 $tax_number=preg_replace('/tax\s*:?\s*/i','',$tax_number);

  $tax_number=preg_replace('/\-?\s*ok$/i','',$tax_number);
  $tax_number=preg_replace('/\-?\s*checked$/i','',$tax_number);
  $tax_number=preg_replace('/\s*ckecked$/i','',$tax_number);
  $tax_number=preg_replace('/\-?\s*checked\s+valid\.?$/i','',$tax_number);
  $tax_number=preg_replace('/\s*\-?\s*valid$/i','',$tax_number);
  $tax_number=preg_replace('/\s*\-?\s*verified$/i','',$tax_number);
  $tax_number=preg_replace('/\s*\-?\s*Checked\s*\!{0,5}$/i','',$tax_number);
  $tax_number=preg_replace('/\-?\s*\(checked\)$/i','',$tax_number);
  $tax_number=preg_replace('/\-?\s*\(check ok\)$/i','',$tax_number);
 $tax_number=preg_replace('/\-?\s*valid\s*\(HM\)$/i','',$tax_number);
$tax_number=preg_replace('/\-?\s*checked by customs$/i','',$tax_number);

  if(preg_match('/EL137399039 checkedEL-137399039/i',$tax_number))
    $tax_number='EL137399039';
 if(preg_match('/PT:503958271, validPT-503958271/i',$tax_number))
    $tax_number='PT-503958271';
 if(preg_match('/NL060484305B02 validNL060484305B02 valid/i',$tax_number))
    $tax_number='NL060484305B02';
 if(preg_match('/^IE : 3756781C$/i',$tax_number))
    $tax_number='IE3756781C';

  $tax_number=_trim($tax_number);
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


?>
