<?
include_once('Country.php');
class Address{

  var $data=array();
  var $id=false;

  
  function __construct($arg1=false,$arg2=false) {


     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return;
     }
      if($arg1=='new'){
       $this->create($arg2);
       return;
     }

     if($arg1=='fuzzy all'){
       $this->get_data('fuzzy all');
       return;
     }elseif($arg1=='fuzzy country'){
       if(!is_numeric($arg2)){
	 $this->get_data('fuzzy all');
	 return;
       }
       $country=new Country($arg2);
       if(is_numeric($arg2) and $country->get('Country Code')!='UNK'){
	 $this->get_data('fuzzy country',$arg2);
	 return;
       }else{
	  $this->get_data('fuzzy all');
	 return;
       }
	 
	 
     }
  }


  function get_data($tipo,$id=false){
    
    if($tipo=='id')
      $sql=sprintf("select * from `Address Dimension` where  `Address Key`=%d",$id);
    elseif('tipo'=='fuzzy country')
      $sql=sprintf("select * from `Address Dimension` where  `Fuzzy Address`=1 and `Address Fuzzy Type`='country' and `Address Country Key`=%d   ",$id);
    else
      $sql=sprintf("select * from `Address Dimension` where  `Fuzzy Address`=1 and `Address Fuzzy Type`='all' ",$id);




    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
      $this->id=$this->data['Address Key'];
    else{
      print "$sql\n  can not fpuns \n";
     
      // exit(" $sql\n can not open address");

    }
    



}

  function create($data){

    if(isset($data['type']) and $data['type']=='3line'){

      
      $prepared_data=$this->prepare_3line($data);

    }elseif(isset($data['type']) and $data['type']=='aw'){
      $prepared_data=$data;

      $prepared_data['town_d1_id']='';
      $prepared_data['town_d2_id']='';
      $prepared_data['country_d1_id']='';
      $prepared_data['country_d1_id']='';
      $prepared_data['town_id']='';
      $country=new country('name',$prepared_data['country']);
      $prepared_data['country_id']=$country->id;
      //     print_r($prepared_data);exit;
    }else
       return;

    $fuzzy=0;
    $fuzzy_type='';

    // print_r($prepared_data);
    // exit;
    if($prepared_data['internal_address']=='' 
       and $prepared_data['building_address']==''
       and $prepared_data['street_address']==''){
	$fuzzy=1;
	$fuzzy_type='City';
    }

    
    if($prepared_data['town']=='' and  $prepared_data['military_base']=='No'){
      $fuzzy=1;
      $fuzzy_type='Country';
    } 



    $country_unknown=new Country('code','UNK');

    if($prepared_data['country_id']==$country_unknown->id){
      $fuzzy=1;
      $fuzzy_type='All';
    } 



      if($prepared_data['town_d1_id']==0)$prepared_data['town_d1_id']='';
      if($prepared_data['town_d2_id']==0)$prepared_data['town_d2_id']='';
      if($prepared_data['country_d1_id']==0)$prepared_data['country_d1_id']='';
      if($prepared_data['country_d1_id']==0)$prepared_data['country_d1_id']='';



      $this->data['Address Internal']=$prepared_data['internal_address'];
      $this->data['Address Building']=$prepared_data['building_address'];
      $this->parse_street($prepared_data['street_address']);
      $this->data['Address Town Secondary Division']=$prepared_data['town_d2'];
      //   $this->data['address town secondary division key']=$prepared_data['town_d2_id'];
      $this->data['Address Town Primary Division']=$prepared_data['town_d1'];
      // $this->data['address town primary division key']=$prepared_data['town_d1_id'];
      $this->data['Address Town']=$prepared_data['town'];
      $this->data['Address Town Key']=$prepared_data['town_id'];
      $this->data['Postal Code']=$prepared_data['postcode'];

      $this->data['Address Country Secondary Division Key']=$prepared_data['country_d2_id'];
      $d2=$this->get_country_d2_name($this->data['Address Country Secondary Division Key']);
      if($d2!='')
	$this->data['Address Country Secondary Division']=$d2;
      else
	$this->data['Address Country Secondary Division']=$prepared_data['country_d2'];

      $this->data['Address Country Primary Division Key']=$prepared_data['country_d1_id'];
      
      $d1=$this->get_country_d1_name($this->data['Address Country Primary Division Key']);
      if($d1!='')
	$this->data['Address Country Primary Division']=$d1;
      else
	$this->data['Address Country Primary Division']=$prepared_data['country_d1'];

      $country=new country($prepared_data['country_id']);

      $this->data['Fuzzy Address']=$fuzzy;
      $this->data['Address Fuzzy Type']=$fuzzy_type;
      $this->data['Address Country Code']=$country->get('Country Code');
      $this->data['Address Country 2 Alpha Code']=$country->get('Country 2 Alpha Code');
      $this->data['Address Country Key']=$country->get('Country Key');
      $this->data['Address Country Name']=$country->get('Country Name');
      $this->data['Address World Region']=$country->get('World Region');
      $this->data['Address Continent']=$country->get('Continent');
      $this->data['Military Address']=$prepared_data['military_base'];
      $this->data['Military Installation Address']=_trim($this->data['Address Internal'].' '.$this->data['Address Building']);
      $this->data['Military Installation Name']=$prepared_data['military_installation_data']['military base name'];
      $this->data['Military Installation Country Key']=$prepared_data['military_installation_data']['military base country key'];
      $this->data['Military Installation Type']=$prepared_data['military_installation_data']['military base type'];



      $this->data['Address Location']=$this->display('location');
      $this->data['XHTML Address']=$this->display('xhtml');
      
      //  print"\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
      //prepare_mysql(0);
      //exit;
      //print_r($this->data);

      $keys='`Address Data Creation`';
      $values='Now()';
      foreach($this->data as $key=>$value){
	$keys.=",`".$key."`";
	$values.=','.prepare_mysql($value);
	//	print "$key $value ".prepare_mysql($value)."  **********************    \n";
      }
      $values=preg_replace('/^,/','',$values);
      $keys=preg_replace('/^,/','',$keys);

      $sql="insert into `Address Dimension` ($keys) values ($values)";
      //      print_r($this->data);
      //print "$sql\n";      exit;
      //if($this->get('address country code')=='UNK')
      //	exit('address code is UNKNOWN');

      if(mysql_query($sql)){
	$this->id = mysql_insert_id();
	$this->data['Address Key']= $this->id;
      }else{
	print "Error can not create address\n";exit;
	
      }
	
      

    


  }


  function get($key){

    
    if(array_key_exists($key,$this->data))
      return $this->data[$key];
   
    switch($key){
    case('country region'):
      if($this->get('Address Country Primary Division')!='')
	return $this->get('Address Country Primary Division');
      else
	return $this->get('Address Country Secondary Division');
      break;
    
      
    }
    
    // print_r($this->data);
    $_key=ucwords($key);
    if(array_key_exists($_key,$this->data))
      return $this->data[$_key];
    print_r($this->data);
    print "Error $key not found in get from address\n";
    asds();
    exit;
    return false;

  }

 function display($tipo=''){
   $separator="\n";
   switch($tipo){
   case('location'):
     //  print_r($this->data);

     if($this->get('Military Address')=='Yes'){
       $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($this->data['Address Country 2 Alpha Code']),$this->data['Address Country Code'],$this->data['Military Installation Type']);
     }else{

     if($this->get('fuzzy address')){
       //       print $this->get('address fuzzy type')."zzzzz\n";
       switch($this->get('address fuzzy type')){
       case('Country'):
	 $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($this->data['Address Country 2 Alpha Code']),$this->data['Address Country Code'],_('Somewhere in').' '.$this->data['Address Country Name']);
	 break;
       case('All'):
	 $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($this->data['Address Country 2 Alpha Code']),$this->data['Address Country Code'],_('Somewhere in the world'));
	 break;	 
       case('City'):
	 $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($this->data['Address Country 2 Alpha Code']),$this->data['Address Country Code'],_('Somewhere in').' '.$this->data['Address Town']);
       break;
     default:
        $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($this->data['Address Country 2 Alpha Code']),$this->data['Address Country Code'],_('Unknown'));
       }

     }else{
     $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($this->data['Address Country 2 Alpha Code']),$this->data['Address Country Code'],$this->data['Address Town']);
     }
     }
     return _trim($location);
     break;
   case('xhtml'):
   case('html'):
     $separator="<br/>";
     
   default:
      if($this->data['Military Address']=='Yes'){
	$address=$this->data['Military Installation Address'];
	$address_type=_trim($this->data['Military Installation Type']);
	if($address_type!='')
	  $address.=$separator.$address_type;
	$address_type=_trim($this->data['Postal Code']);
	if($address_type!='')
	  $address.=$separator.$address_type;
	$address.=$separator.$this->data['Address Country Name'];

      }else{
	//print_r($this->data);
	$address='';
	$header_address=_trim($this->data['Address Internal'].' '.$this->data['Address Building']);
	if($header_address!='')
	  $address.=$header_address.$separator;
	
	$street_address=_trim($this->data['Street Number'].' '.$this->data['Street Name'].' '.$this->data['Street Type']);
	if($street_address!='')
       $address.=$street_address.$separator;

     
     $subtown_address=$this->data['Address Town Secondary Division'];
     if($this->data['Address Town Primary Division'])
       $subtown_address.=' ,'.$this->data['Address Town Primary Division'];
     $subtown_address=_trim($subtown_address);
       if($subtown_address!='')
       $address.=$subtown_address.$separator;


     
     $town_address=_trim($this->data['Address Town']);
     if($town_address!='')
       $address.=$town_address.$separator;

     $ps_address=_trim($this->data['Postal Code']);
     if($ps_address!='')
       $address.=$ps_address.$separator;
     
     $address.=$this->data['Address Country Name'];
      }
      return _trim($address);
  
   case('header'):

     $separator=', ';
     $address='';
     $header_address=_trim($this->data['Address Internal'].' '.$this->data['Address Building']);
     if($header_address!='')
       $address.=$header_address.$separator;
     
     $street_address=_trim($this->data['Street Number'].' '.$this->data['Street Name'].' '.$this->data['Street Type']);
     if($street_address!='')
       $address.=$street_address.$separator;
     
     
     $subtown_address=$this->data['Address Town Secondary Division'];
     if($this->data['Address Town Primary Division'])
       $subtown_address.=' ,'.$this->data['Address Town Primary Division'];
     $subtown_address=_trim($subtown_address);
     if($subtown_address!='')
       $address.=$subtown_address.$separator;


      return _trim($address);

 }
   
   

 }


 function prepare_3line($address_raw_data,$untrusted=true){


   // print_r($address_raw_data);
 if(!isset($address_raw_data['country']))
   exit;
 $fix2=true;
 $debug=true;
   $debug=false;
 if($debug)
    print_r($address_raw_data);
 //  if($address_raw_data['address1']=='' 
//      and $address_raw_data['address2']==''
//      and $address_raw_data['address3']=='')
//     return false;


  $military_base='No';
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
  $military_installation['address']='';
  $military_installation['military base country key']='';
  $military_installation['military base name']='';
  $military_installation['military base location']='';
  $military_installation['military base type']='';
  $military_installation['military base postal code']='';
  



 if($fix2){
   if(preg_match('/^St. Thomas.*Virgin Islands$/i',$address_raw_data['town']))
     $address_raw_data['country']='Virgin Islands, U.S.';
   
 }
 

  
  $country_d1=$address_raw_data['country_d1'];
  if(!isset($address_raw_data['country']) or $address_raw_data['country']==''){
    $country_id=$address_raw_data['default_country_id'];
    
  }else{// Try to guess country

    // Common missconceptions



    if($address_raw_data['default_country_id']==30){
      if($this->is_valid_postcode($address_raw_data['postcode'],30)){
	$address_raw_data['country_d1']=_trim($address_raw_data['country_d1'].' '.$address_raw_data['country']);
	$address_raw_data['country']='United Kingdom';
      }elseif($this->is_valid_postcode($address_raw_data['country'],30)){
	$address_raw_data['country_d1']=_trim($address_raw_data['country_d1'].' '.$address_raw_data['postcode']);
	$address_raw_data['postcode']=$address_raw_data['country'];
	$address_raw_data['country']='United Kingdom';
      }
      

    }
    
  //   if(preg_match('/re$/i',$address_raw_data['country'])  and preg_match('/^(Co Kildare|)$/i',$address_raw_data['country_d1'])  and preg_match('/\-{0,5}|Dublin/i',$address_raw_data['postcode'])  )
//       $address_raw_data['country']='Ireland';
    

    if(preg_match('/SCOTLAND|wales/i',$address_raw_data['country']))
      $address_raw_data['country']='United Kingdom';
    if(preg_match('/^england$|^inglaterra$/i',$address_raw_data['country'])){
      $address_raw_data['country']='United Kingdom';
     if($country_d1=='')
	$country_d1='England';
    }else if(preg_match('/^nor.*ireland$|n\.{2}ireland/i',$address_raw_data['country'])){
      $address_raw_data['country']='United Kingdom';
      if($country_d1=='')
	$country_d1='Northen Ireland';
    }else if(preg_match('/^r.*ireland$|^s.*ireland|^eire$/i',$address_raw_data['country'])){
      $address_raw_data['country']='Ireland';
    }else if(preg_match('/me.ico|m.xico/i',$address_raw_data['country'])){
      $address_raw_data['country']='Mexico';
    }else if(preg_match('/scotland|escocia/i',$address_raw_data['country'])){

      $address_raw_data['country']='United Kingdom';
      if($country_d1=='')
	$country_d1='Scotland';
    }else if(preg_match('/.*\s+(w|g)ales$/i',$address_raw_data['country'])){
      $address_raw_data['country']='United Kingdom';
      if($country_d1=='')
	$country_d1='Wales';
    }else if(preg_match('/canarias$/i',$address_raw_data['country'])){
      $address_raw_data['country']='Spain';
      if($country_d1=='')
      $country_d1='Canarias';
    }else if(preg_match('/^Channel Islands$/i',$address_raw_data['country'])){

      if($country_d1!=''){
	$address_raw_data['country']=$country_d1;
	$country_d1='';
	
      }else if($address_raw_data['country_d2']!=''){
	$address_raw_data['country']=$address_raw_data['country_d2'];
	$address_raw_data['country_d2']='';
	
      } else if($address_raw_data['town']!=''){
	$address_raw_data['country']=$address_raw_data['town'];
	$address_raw_data['town']='';
	
      }
      

      
    }
    

 $_p=$address_raw_data['postcode'];

  if(preg_match('/^\s*BFPO\s*\d{1,}\s*$/i',$_p))
    $address_raw_data['country']='UK';


  $address_raw_data['country']=preg_replace('/^,|[,\.]$/','',$address_raw_data['country']);
    
  $sql=sprintf("select `Country Key` as id from `Country Dimension` left join `Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($address_raw_data['country']),prepare_mysql($address_raw_data['country']));
  //     print "$sql\n";
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
      $country_id=$row['id'];
    else
      $country_id=244;
  }
  // Ok the country is already guessed, wat else ok depending of the country letys gloing to try to get the orthers bits of the address




  // pushh all address up

  if($untrusted){


    //Change town if misplaced
    
    if($address_raw_data['town']=='') {

      if($this->is_town($address_raw_data['address3'],$country_id) ){
	$address_raw_data['town']=$address_raw_data['address3'];
	$address_raw_data['address3']='';
      }else if($this->is_town($address_raw_data['country_d2'],$country_id) ){
	$address_raw_data['town']=$address_raw_data['country_d2'];
	$address_raw_data['country_d2']='';
      }


    }



    if(preg_match('/^\d[a-z]?(bis)?\s*,/',$address_raw_data['address1'])){
      $address_raw_data['address1']=preg_replace('/\s*,\s*/',' ',$address_raw_data['address1']);
    }
    if(preg_match('/^\d[a-z]?(bis)?\s*,/',$address_raw_data['address2'])){
      $address_raw_data['address2']=preg_replace('/\s*,\s*/',' ',$address_raw_data['address2']);
    }
    if(preg_match('/^\d[a-z]?(bis)?\s*,/',$address_raw_data['address3'])){
      $address_raw_data['address3']=preg_replace('/\s*,\s*/',' ',$address_raw_data['address3']);
    }
    
    $address_raw_data['address1']=preg_replace('/,\s*$/',' ',$address_raw_data['address1']);
    $address_raw_data['address2']=preg_replace('/,\s*$/',' ',$address_raw_data['address2']);
    $address_raw_data['address3']=preg_replace('/,\s*$/',' ',$address_raw_data['address3']);


    // this is going to ve dirty
    //print_r($address_raw_data);
    
    if($this->is_street($address_raw_data['address2']) and  $address_raw_data['address1']!=''  and $address_raw_data['address3']==''  ){
      $tmp=preg_split('/\s*,\s*/i',$address_raw_data['address1']);
      if(count($tmp)==2 and !preg_match('/^\d*$/i',$tmp[0])   and !preg_match('/^\d*$/i',$tmp[1]) ){
	$address_raw_data['address3']=$address_raw_data['address2'];
	$address_raw_data['address1']=$tmp[0];
	$address_raw_data['address2']=$tmp[1];


      }

    }
    //  print_r($address_raw_data);

    //print $address_raw_data['address1']."----------------\n";
    // print $address_raw_data['address2']."----------------\n";



    if($address_raw_data['address1']==''){ 
      if($address_raw_data['address2']==''){
	// if line 1 and 2  has not data
	$address_raw_data['address1']=$address_raw_data['address3'];
	$address_raw_data['address3']='';
      

      }else{

	if($address_raw_data['address3']==''){

	    $address_raw_data['address1']=$address_raw_data['address2'];
	    $address_raw_data['address2']='';
	    
	  }else{
	    $address_raw_data['address1']=$address_raw_data['address2'];
	    $address_raw_data['address2']=$address_raw_data['address3'];
	    $address_raw_data['address3']='';
	  }


      }
      
    }else if($address_raw_data['address2']==''){
      $address_raw_data['address2']=$address_raw_data['address3'];
      $address_raw_data['address3']='';
    }


  //then volter alas address

    // print_r($address_raw_data);
    // exit;

  //lets do it as an experiment if the only line is 1 has data
  // split the data in that line  to see what happens
  if($address_raw_data['address1']!='' and $address_raw_data['address2']=='' and $address_raw_data['address3']==''){
    $splited_address=preg_split('/\s*,\s*/i',$address_raw_data['address1']);
    if(count($splited_address)==1){
      $address3=$splited_address[0];
    }else if(count($splited_address)==2){
      // ok separeta bu on li if the sub partes are not like numbers

      $parte_1=_trim($splited_address[1]);
      $parte_0=_trim($splited_address[0]);
      // print "->$parte_1<- ->$parte_0<-\n";
      if(preg_match('/^\d*$/',$parte_0) or preg_match('/^\d*$/',$parte_1)  ){
	 $address3=$address_raw_data['address1'];



      }else{
	
	if(preg_match('/^\d{1,}.+$/',$parte_0) or preg_match('/^.+\d{1,}$/',$parte_1)   ){
	  $address3=$address_raw_data['address1'];
	}else {
	  $address2=$parte_0;
	  $address3=$parte_1;
	}
      }
      // exit ("$address3\n");
    }else if(count($splited_address)==3){
      $address1=$splited_address[0];
      $address2=$splited_address[1];
      $address3=$splited_address[2];
    }
      
  }else if( $address_raw_data['address3']==''){
    $address2=$address_raw_data['address1'];
    $address3=$address_raw_data['address2'];

  }else{

    // print_r($address_raw_data);
    $address1=$address_raw_data['address1'];
    $address2=$address_raw_data['address2'];
    $address3=$address_raw_data['address3'];

  }

  // print("a1 $address1 a2 $address2 a3 $address3 \n");


     $town=$address_raw_data['town'];
  $town_d2=$address_raw_data['town_d2'];
  $town_d1=$address_raw_data['town_d1'];

  //  print "1:$address1 2:$address2 3:$address3 t:$town \n";

  $f_a1=($address1==''?false:true);
  $f_a2=($address2==''?false:true);
  $f_a3=($address2==''?false:true);



  $f_t=($town==''?false:true);
  $f_ta=($town_d2==''?false:true);
  $f_td=($town_d1==''?false:true);

  $s_a1=$this->is_street($address1);
  $s_a2=$this->is_street($address2);
  $s_a3=$this->is_street($address3);
  $i_a1=$this->is_internal($address1);
  $i_a2=$this->is_internal($address2);
  $i_a3=$this->is_internal($address3);



  // print "Street grade 1-$s_a1 2-$s_a2 3-$s_a3 \n";
  //   print "Internal grade 1-$i_a1 2-$i_a2 3-$i_a3 \n";
  //   print "Filled grade 1-$f_a1 2-$f_a2 3-$f_a3 \n";
  //   exit;    
   if(!$f_a1 and $f_a2 and $f_a3){
     
     if($s_a2 and $i_a3){
       
       $_a=$address3;
       $address3=$address2;
       $address2=$_a;
     }
       
   }

   
   //   exit;

  // super special case
  //  if(!$f_a1 and $f_a2 and $f_a3 and )
   //print("a1 $address1 a2 $address2 a3 $address3 \n");
  $town_filled=false;
  // caso 1 all filled a1,a2 and a3
  if($f_a1 and $f_a2 and $f_a3){ // caso 1 all filled a1,a2 and a3
    //print "AAAAAAAA\n";
    if($s_a1 and !$s_a2 and !$s_a3){ //caso    soo  (moviing 2 )
      
      if(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
	//print "AAAAAAAA\n";
	$town_filled=true;
	$town=$address3;
	$town_d2=$address2;
	$address3=$address1;
	$address2='';
	$address1='';

      }else if(!$f_ta and !$f_td and $f_t){// caso oot
	
	$town_d1=$address3;
	$town_d2=$address2;
	$address3=$address1;
	$address2='';
	$address1='';

      }else{
	$address3=$address1.', '.$address2.', '.$address3;
	$address2='';
	$address1='';
	  
      }
    }else if ((!$s_a1 and $s_a2 and !$s_a3) OR ($s_a1 and $s_a2 and !$s_a3)){ //caso    oso OR  sso  (move one)
      //  print "HOLAAAAAAAAAAAA";
       if($s_a1 and $s_a2 and !$f_a3 and $f_t){ 
	 $address3=$address2;
	 $address2=$address1;
	 $address1='';
	 
       }elseif(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
	$town=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';
      }else if(!$f_ta and !$f_td and $f_t){// caso oot
	$town_d2=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';
      }else{
	$address3=$address2.', '.$address3;
	$address2=$address1;
	$address1='';
      }
    }

  }elseif(!$f_a1 and $f_a2 and $f_a3){ // case xoo

    //   print "1 $address1 2 $address2 3 $address3 \n";
    if($s_a2 and   !$i_a3 and !$s_a3  ){
      //   print "caca";
     if(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
       
       $town=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';
      }else if(!$f_ta and !$f_td and $f_t){// caso oot
       
	$town_d2=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';

      }else{
       
	$address3=$address2.', '.$address3;
	$address2=$address1;
	$address1='';
      }


   }



  }

  


  }

  

  // exit("a1 $address1 a2 $address2 a3 $address3 \n");

 // get country name
  
  $sql=sprintf("select `Country Name` as name from  `Country Dimension`  where `Country Key`=%d",$country_id);

 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	   if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $country=$row['name'];
  }


  // take opff the name of the comntry from the poscode part
	   $postcode=$address_raw_data['postcode'];



  // $regex='/\s*'.$country.'\s*/i';
  //  $postcode=preg_replace($regex,'',$postcode);



  // print $postcode." $regex XXXXXXXXXXXXXXXXX \n";


  $country_d2=$address_raw_data['country_d2'];
  




  if(preg_match('/^P\.o\.box\s+\d+$|^po\s+\d+$|^p\.o\.\s+\d+$/i',$town_d2)){

    $po=$town_d2;
    $town_d2='';
    $po=preg_replace('/^P\.o\.box\s+|^po\s+|^p\.o\.\s+/i','PO BOX ',$po);
    if($address1=='')
      $address1=$po;
    else
      $address1=$po.', '.$address1;
    
  }




  switch($country_id){
  case(30)://UK
    // ok try to determine the city from aour super database of cities and towns

    if(preg_match('/Andover.*\sHampshire/i',$town))
      $town='Andover';

    if($town_filled){
      if($this->is_country_d2($town,30) and $this->is_town($town_d2,30)){
	$country_d2=$town;	
	$town=$town_d2;
	$town_d2='';
      }
	
    }

   

    if($town==''){
    if($town_d1!='' ){
      $town=$town_d1;
      $town_d1='';
    }
    elseif($town_d2!=''){
      $town=$town_d2;
      $town_d2='';
    }
    elseif($address3!='' and ($address2!='' or $address1!='') ){
      $town=$address3;
      $address3='';
    }else if($address2!='' and $address1!=''){
      $town=$address2;
      $address2='';
    }

  }







    $postcode=preg_replace('/,?\s*scotland\s*$|united kingdom/i','',$postcode);
    $postcode=preg_replace('/\s/','',$postcode);
    if(preg_match('/^bfpo\s*\d/i',$postcode) )
      $postcode=preg_replace('/bfpo/i','BFPO ',$postcode);
    else
      $postcode=substr($postcode,0,strlen($postcode)-3).' '.substr($postcode,-3,3);

    
    break;
case(78)://Italy
  $postcode=preg_replace('/italy|italia/i','',$postcode);
  $postcode=preg_replace('/\s/i','',$postcode);

  if($town=='Padova'){
    $country_d1='Veneto';
    $country_d2='Padova';
  }
 if($town=='Mestre'){
    $country_d1='Venezia';
    $country_d2='Veneto';
  }
 
 if(preg_match('/Genova\s*(\- Ge)?/i',$town)){
    $country_d1='Genoa';
    $country_d2='Liguria';
    $town='Genova';
  }
 
 if(preg_match('/Spilamberto/i',$address3) and preg_match('/Modena/i',$town)){
    $country_d1='Emilia-Romagna';
    $country_d2='Modena';
    $town='Spilamberto';
    $address3='';
  }
 
 if(preg_match('/Pescia/i',$address3) and preg_match('/Toscana/i',$town)){
    $country_d1='Toscana';
    $country_d2='Pistoia';
    $town='Pescia';
    $address3='';
  }

if( preg_match('/Villasor.*Cagliari/i',$town)){
    $country_d1='Sardinia';
    $country_d2='Cagliari';
    $town='Villasor';
  }
if( preg_match('/Nocera Superiore/i',$town)){
    $country_d1='Campania';
    $country_d2='Salerno';
    $town='Nocera Superiore';
  }
if( preg_match('/^Vicenza$/i',$town)){
    $country_d1='Veneto';
    $country_d2='Vicenza';
    $town='Vicenza';
  }

if( preg_match('/^Rome$/i',$town)){
    $country_d1='Lazio';
    $country_d2='Rome';
    $town='Rome';
  }
$postcode=_trim($postcode);
  if(preg_match('/^\d{2}$/',$postcode))
      $postcode='000'.$postcode;
  if(preg_match('/^\d{3}$/',$postcode))
      $postcode='00'.$postcode;

    if(preg_match('/^\d{4}$/',$postcode))
      $postcode='0'.$postcode;
  break;
  case(75)://Ireland

    // print "address1: $address1\n";
    //print "address2: $address2\n";
    //print "address3: $address3\n";
    //print "townarea: $town_d2\n";
    //print "town: $town\n";
    //    print "country_d2: $country_d2\n";
    //      print "postcode: $postcode\n";
    
    $postcode=_trim($postcode);
    
    


    $country_d2=_trim($country_d2);
    $postcode=preg_replace('/County COrK/i','',$postcode);
    $postcode=preg_replace('/^co\.\s*|Republique of Ireland|Louth Ireland|ireland/i','',$postcode);
    $country_d2=preg_replace('/^co\.\s*|republic of ireland|republic of|ireland/i','',$country_d2);
    $country_d2=preg_replace('/(co|county)\s+[a-z]+$/i','',$country_d2);
     $country_d2=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$country_d2);
   $country_d2 =preg_replace('/(co|county)\s+[a-z]+$/i','',$country_d2);

    $postcode=preg_replace('/\,+\s*^ireland$/i','',$postcode);
    $postcode=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$postcode);
    $town=preg_replace('/(co|county)\s+[a-z]+$/i','',$town);

    if($town=='Cork')
      $postcode='';

    $postcode=preg_replace('/co\s*Donegal|eire|republic of ireland|rep\? of Ireland|n\/a|^ireland$|/i','',$postcode);
 $postcode=_trim($postcode);
 $country_d2=_trim($country_d2);
    //print "country_d2: $country_d2\n";
    $town=preg_replace('/\-?\s*eire|\s*\-?\s*ireland/i','',$town);
    //exit;
    if($country_d2=='Wesstmeath')
      $country_d2='Westmeath';

    if($town=='Wesstmeath' or $town=='Westmeath' ){
      $town='';
    }

    

    if($this->is_town($town_d2,$country_id) and $this->is_country_d2($town,$country_id)){
      $county_d2=$town;
      $town=$town_d2;
      $town_d2='';

    }
      


    $postcode=preg_replace('/Rep.?of/i','',$postcode);
    $postcode=str_replace(',','',$postcode);
    $postcode=str_replace('.','',$postcode);
    $postcode=str_replace('DUBLIN','',$postcode);
    $postcode=str_replace('N/A','',$postcode);
    $postcode=preg_replace('/Republic\s?of/i','',$postcode);
    $postcode=preg_replace('/Erie/i','',$postcode);
    $postcode=preg_replace('/county/i','',$postcode);
    
    $postcode=preg_replace('/^co/i','County ',$postcode);
    $postcode=preg_replace('/\s{2,}/',' ',$postcode);
    $postcode=_trim($postcode);

    $valid_postalcodes=array('D1','D2','D3','D4','D5','D6','D6w','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D20','D22','D24');

    if($postcode!=''){
    $sql="select `Country Secondary Division Name` as name from `Country Secondary Division Dimension` where  `Country Key`=75 and `Country Secondary Division Name` like '%$postcode%'";
    //print "$sql\n";
    
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
      $postcode='';
      $country_d2=$row['name'];

    }    
    }
    // delete unganted  postcodes
    if(preg_match('/COMAYORepublicof|COGALWAY|RepublicofTIPPERARY|Republiqueof|NCW|eire|WD3|123|CoKerry,EIRE|COCORK|COOFFALY|WICKLOW|CoKerry/i',$postcode))
      $postcode='';

    if(preg_match('/^co\.?\s+|^country\s+/i',$postcode)){
      $postcode='';
      if($country_d2=='')
	$country_d2=$postcode;
      $postcode='';
    }

    $town=preg_replace('/\s+ireland\s*/i','',$town);
    $country_d2=preg_replace('/\s+ireland\s*/i','',$country_d2);
	
    
    $town=preg_replace('/co\.\s*/i','Co ',$town);
    $town=preg_replace('/county\s+/i','Co ',$town);

    // print "$town";
    $split_town=preg_split('/\s*-\s*|\s*,\s*/i',$town);
    if(count($split_town)==2){
      if(preg_match('/^co\s+/i',$split_town[1])){
	 if($country_d2=='')
	   $country_d2=$split_town[1];
	 $town=$split_town[0];
      }

    }


    if(preg_match('/^co\s+/i' ,$town)){
      if($country_d2=='')
	$country_d2=$town;
      $town=preg_replace('/^co\s+/i','',$town);
    }
      
    $country_d2=preg_replace('/co\.?\s+/i','',$country_d2);
    $country_d2=preg_replace('/county\s+/i','',$country_d2);
    
    if(preg_match('/\s*Cork\sCity\s*/i',$town_d2)){
      $town_d2=='';
      if($town=='')
	$town='Cock';
    }
    
    if(preg_match('/^dublin\s+\d+$/i',$town_d2)){

      if($town=='')
	$town='Dublin';
      if($town_d1=='')
	$town_d1=preg_replace('/dublin\s+/i','',$town_d2);
      if($postcode==preg_replace('/dublin\s+/i','',$town_d2))
	$postcode='';
      $town_d2=='';
    }


    if(preg_match('/^dublin\s*\d{1,2}$/i',$postcode)){
      $postcode=preg_replace('/^dublin\s*/i','',$postcode);
    }
    $town=_trim($town);
    
 //  print "$town +++++++++++++++\n";
    $town=preg_replace('/\s*,?\s*Leinster/i','',$town);
    if(preg_match('/^dublin\s*6w$/i',$town)){
      $postcode='D6W';
      $town='Dublin';
    }

    //  print "$town +++++++++++++++\n";
    if(preg_match('/^dublin\s*\-\s*\d$/i',$town)){
      $postcode=preg_replace('/^dublin\s*\-\s*/i','',$town);
      $town='Dublin';
    }

     if(preg_match('/^dublin\s*d?\d{1,2}$/i',$town)){
       $postcode=preg_replace('/^dublin\s*/i','',$town);
       $town='Dublin';
    }
     
     if(is_numeric($postcode))
       $postcode='D'.$postcode;


      if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	$town=$address3;
	$address3='';
      }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }
      }
      $country_d2=mb_ucwords($country_d2);

      $postcode=str_replace('-','',$postcode);
      $postcode=preg_replace('/MUNSTER|County RK/i','',$postcode);
      $postcode=_trim($postcode);
      break; 

  case(89)://Canada
    $postcode=preg_replace('/\s*canada\s*/i','',$postcode);

    if($country_d2!='' and $country_d1==''){
      $country_d1=$country_d2;
      $country_d2='';
    }
    break;
  case(208)://Czech Republic
     $postcode=preg_replace('/\s*Czech Republic\s*/i','',$postcode);
     $postcode=preg_replace('/\s*/i','',$postcode);
    break;
case(108)://Cypruss
       $postcode=preg_replace('/\s*cyprus\s*/i','',$postcode);

       $postcode=preg_replace('/^cy\-?/i','',$postcode);

       if($town=='Lefkosia (Nicosia)')
	 $town='Nicosia';
       if($town=='Limassol City Centre')
	 $town='Limassol';
       
        if($town=='Cyprus')
	 $town='';

      if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	$town=$address3;
	$address3='';
      }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }
      }

       break;
  case(240):
    $town=preg_replace('/\,?\s*Guernsey Islands$/i','',$town);
     $town=preg_replace('/\,?\s*Guernsey$/i','',$town);
     $town=preg_replace('/\,?\s*Channel Islands$/i','',$town);
     $town=preg_replace('/\,?\s*CI$/i','',$town);
     $town=preg_replace('/\,?\s*C.I.$/i','',$town);

     if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	if(!preg_match('/^rue\s/i',$address3)){
	$town=$address3;
	$address3=$address2;
	$address2='';
	}
	  }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }

      

      
     }
     




    break;
  case(104):// Greece
    $postcode=preg_replace('/greece/i','',$postcode);

    $postcode=preg_replace('/^(GK|T\.?k\.?)/i','',$postcode);
    $postcode=preg_replace('/\s/i','',$postcode);
    $postcode=_trim($postcode);

    if(preg_match('/^(Attica|Ionian Islands)$/i',$town))
      $town='';
if($country_d1=='Attoka'){
      $country_d1='Attica';

    }
    if($town=='Athens')
      $country_d1='Attica';
if($town=='Salamina')
      $country_d1='Attica';
 if($town=='Corfu'){
   $town='';
   $country_d1='Ionian Islands';
   $country_d2='Corfu';
 }
    if($town=='Kefalonia')
      $country_d1='Ionian Islands';
    if($town=='Thessaloniki')
      $country_d1='Central Macedonia';

    if($town=='Xania - Krete'){
      $country_d1='Crete';
      $town='Xania';
    }
    if($town=='Salamina - Tsami'){
      $country_d1='Attica';
      $town='Salamina';
	if($town_d2=='')
	  $town_d2='Tsami';
    }


    break;

  case(229)://USA
  if($country_d2!='' and $country_d1==''){
      $country_d1=$country_d2;
      $country_d2='';
    }
  $town=_trim($town);
  $postcode=_trim($postcode);
  //apo address
  if(preg_match('/^(09|96|340)\d+$/',$postcode)){


    $military_base='Yes';
    
    $address1=_trim($address1.' '.$address2.' '.$address3.' '.$town.' '.$country_d2.' '.$country_d1);
    $address2='';
    $address3='';
    $town='';
    $country_d2='';
    $country_d1='';
    $military_installation['address']=$address1;
    $military_installation['military base country key']='';
    $military_installation['military base postal code']=$postcode;
    if(preg_match('/apo ae$/i',$address1) or preg_match('/\sapo ae\s+/i',$address1)){
      $address1=_trim(preg_replace('/apo ae/i','',$address1));
      $military_installation['military base type']='APO AE';
    }
      

 //    if(preg_match('/^(apo|ae)$/i',$town)){
//       $town='';
//     }
//     if(preg_match('/^(apo|ae)$/i',$country_d1)){
//       $country_d1='';
//     }
//     if(preg_match('/^(apo|ae)$/i',$country_d2)){
//       $country_d2='';
//     }
 //     $military_installation['address']=$address1;
//      $military_installation['military base country key']=229;
//      $military_installation['military base name']='';
//      $military_installation['military base location']='';
//      $military_installation['military base type']='';
//      $military_installation['military base postal code']=$postcode;


//     if(preg_match('/^(09)/',$postcode)){

//     $militaty_installation_address='';
//         $militaty_installation_code='';

//     $militaty_installation_name='';
//     $militaty_installation_location='';
//     $militaty_installation_country_key='';
  }



    $town=preg_replace('/Lousiana/i','Louisiana',$town);
    
    $country_d1=_trim($country_d1);
    if(preg_match('/^[a-z]\s*[a-z]$/i',$country_d1))
      $country_d1=preg_replace('/\s/','',$country_d1);
    
    $postcode=_trim($postcode);





    $postcode=preg_replace('/united states of america/i','',$postcode);
    

    $postcode=preg_replace('/\s*u\s*s\s*a\s*|^United States\s+|United Stated|usa|^united states$|^united states of america$|^america$/i','',$postcode);
    $postcode=_trim($postcode);

    if($country_d1==''){
      $regex='/\s*\-?\s*[a-z]{2}\.?\s*\-?\s*/i';
      if(preg_match($regex,$postcode,$match)){
	$country_d1=preg_replace('/[^a-z]/i','',$match[0]);
	$postcode=preg_replace($regex,'',$postcode);
      }
      $regex='/\([a-z]{2}\)/i';
      if(preg_match($regex,$town,$match)){
	$country_d1=preg_replace('/[^a-z]/i','',$match[0]);
	$town=preg_replace($regex,'',$town);
      }
      $regex='/\s{1,}\-?\s*[a-z]{2}\.?$/i';
      if(preg_match($regex,$town,$match)){
	$country_d1=preg_replace('/[^a-z]/i','',$match[0]);
	$town=preg_replace($regex,'',$town);
      }


      if($this->is_country_d1($town,229) and $town_d2!=''){
	$country_d1=$town;
	$town=$town_d2;
	$town_d2='';
	
      }

    }


    //   print "$postcode ******** ";
    if($postcode=='' and preg_match('/\s*\d{4,5}\s*/',$town,$match)){
       $postcode=trim(trim($match[0]));
       $town=_trim(preg_replace('/\s*\d{4,5}\s*/','',$town));
    }

    $town=preg_replace('/\s*\-\s*$/','',$town);

    $town_split=preg_split('/\s*\-\s*|\s*,\s*/',$town);

    $country_d1=_trim($country_d1);

    if(count($town_split)==2 and $this->is_country_d1($town_split[1],229)){

      $country_d1=$town_split[1];
      $town=$town_split[0];

      

    }
    


    if($country_d1=='N Y')
      $country_d1='New York';

    $states=array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
    if(strlen($country_d1)==2){
      if (array_key_exists(strtoupper($country_d1), $states)) {
	$country_d1=$states[strtoupper($country_d1)];
      }
    }
    
    if($country_d1==$country_d2)
      $country_d2='';
    
    if($town_d1=='Brooklyn' and $town=='New York'){
      $country_d1='New York';
    }
    $postcode=_trim($postcode);
    if(preg_match('/^d{4}$/',$postcode))
       $postcode='0'.$postcode;
       
    break;
 case(105)://Croatia
    $postcode=_trim($postcode);
   $postcode=preg_replace('/croatia/i','',$postcode);
    $postcode=preg_replace('/^hr-?/i','',$postcode);
     $postcode=_trim($postcode);
   break;
 case(160)://Portugal
   $postcode=_trim($postcode);
   $postcode=preg_replace('/portugal/i','',$postcode);
   $town=preg_replace('/\-?\s*portugal/i','',$town);


   if($postcode=='' and preg_match('/\s*\d{4}\s*/',$town,$match)){
       $postcode=trim(trim($match[0]));
       $town=_trim(preg_replace('/\s*\d{4}\s*/','',$town));
    }


   //   if(preg_match('/algarve/i'$town))


   if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	$town=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';
      }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }
      }




    break;
 case(21)://Belgium
  $postcode=_trim($postcode);
  $postcode=preg_replace('/belgium/i','',$postcode);
  $postcode=preg_replace('/^b\-?/i','',$postcode);
  $postcode=_trim($postcode);
  $t=preg_split('/\s*,\s*/',$town);
  if(count($t)==2){
    if($this->is_country_d1($t[1],$country_id)){
      $country_d1=$t[1];
      $town=$t[0];
    }


  }

  $town=_trim($town);
  if($this->is_country_d1($town,$country_d1) and $country_d1==''  and ($address2!='' and $address3!='') ){
   $country_d1=$town;
   $town='';

 }
  if($town=='West Vlaanderen')
    $town=='West-Vlaanderen';

  if($this->is_country_d1($town,$country_d1) and $country_d1==''  and $town_d2!=''  ){
   $country_d1=$town_d2;
   $town_d2='';

 }




  break;


  case(80)://Austria
  $postcode=_trim($postcode);
  $postcode=preg_replace('/a\-?/i','',$postcode);
  $town=_trim($town);
  if($this->is_country_d1($town,$country_id) and $country_d1==''  and ($address2!='' and $address3!='') ){
   $country_d1=$town;
   $town='';

 }
 if($this->is_country_d1($town,$country_d1) and $country_d1==''  and $town_d2!=''  ){
   $country_d1=$town_d2;
   $town_d2='';

 }




    break;
case(15)://Australia
 $postcode=preg_replace('/\s*australia\s*/i','',$postcode);
  $regex='/\(QLD\)/i';
  if(preg_match($regex,$town)){
    $country_d1='Queensland';
    $town=preg_replace($regex,'',$town);
  }
  $regex='/, Western Australia/i';
  if(preg_match($regex,$town)){
    $country_d1='Western Australia';
    $town=preg_replace($regex,'',$town);
  }

  if($country_d2='' and $country_d1=='' ){
    $country_d1=$country_d2;
    $country_d2='';
  }
    



 $town=_trim($town);

  if($this->is_country_d1($town,15) and $town_d2!=''){
	$country_d1=$town;
	$town=$town_d2;
	$town_d2='';
	
      }


  if($this->is_country_d1($town,15) and $country_d1==''  and ($address2!='' and $address3!='') ){
   $country_d1=$town;
   $town='';

 }

     if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	$town=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';
      }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }
      }







  break;
   case(47)://Spain
 if(preg_match('/Majorca/i',$town)){
   $country_d2='Islas Baleares';
   $country_d1='Islas Baleares';
   $town='';
 }
if(preg_match('/Balearic Islands|Balearic Island/i',$country_d1))
   $country_d1='Balearic Islands';
 if(preg_match('/Balearic Islands|Balearic Island/i',$country_d2))
   $country_d2='Balearic Islands';




 if(preg_match('/Baleares/i',$address3) and preg_match('/Palma de Mallorca/i',$address2)){
   $town='Palma de Mallorca';
   $address3='';
   $address2='';
   $country_d1='Balearic Islands';
}




     if(preg_match('/Zugena - Provincia Almeria/i',$town)){
	 $country_d2='Almeria';
	 $town='Zugena';
       }
 if(preg_match('/Hinojares - Juen/i',$town)){
	 $country_d2='Jaen';
	 $town='Hinojares';
       }


     if(preg_match('/Mijas Costa, Malaga/i',$town)){
	 $country_d2='Malaga';
	 $town='Mijas Costa';
       }
	 if(preg_match('/Calvia - Mallorca/i',$town)){
	 $town='Calvia';
	 $country_d1='Balearic Islands';
       } 

	 if(preg_match('/Ciutadella - Menorca/i',$town)){
	 $town='Ciutadella';
	 $country_d1='Balearic Islands';
       } 
 if(preg_match('/Sax\s*(Alicante)/i',$town)){
	 $town='Sax';
	 $country_d2='Alicante';
       } 


     if(preg_match('/malaga/i',$town)){
       if(preg_match('/Marbella/i',$address3)){
	 $address3='';
	 $town='Marbella';
       }

	 

     }

     $postcode=_trim($postcode);
     $postcode=preg_replace('/spain/i','',$postcode);

     
     if($postcode=='' and preg_match('/\s*\d{4,5}\s*/',$town,$match)){
       $postcode=_trim($match[0]);
       $town=_trim(preg_replace('/\s*\d{4,5}\s*/','',$town));
     }

    


    if(preg_match('/^\d{4}$/',$postcode))
      $postcode='0'.$postcode;

    $country_d1=_trim(preg_replace('/^Adaluc.a$/i','Andalusia',_trim($country_d1)));

    $town=_trim($town);

    if(preg_match('/El Cucador/i',$town)){
	 $town_d2='El Cucador';
	 $town='Zurgena';
	 $country_d2='Almeria';
	 $country_d1='Andalusia';
	 $postcode='04661';
	 if($address2=='Cepsa Garage (Zugena)')
	   $address2='';
    }
 if(preg_match('/^Arona$/i',$town)){
	 $country_d2='Santa Cruz de Tenerife';
	 $country_d1='Islas Canarias';

    }
 if(preg_match('/^Ceuta$/i',$town)){

	 $country_d1='Ceuta';

    }




    break;
  case(126)://Malta
    $postcode=preg_replace('/malta/i','',$postcode);
    $postcode=_trim($postcode);
    $postcode=preg_replace('/\s/i','',$postcode);

    if(preg_match('/[a-z]*/i',$postcode,$ap) and preg_match('/[0-9]{1,}/i',$postcode,$xxx))
      $postcode=$ap[0].' '.$xxx[0];

    $town=preg_replace('/-?\s*malta|gozo\s*\-?/i','',$town);

      $town=_trim($town);

    break;
 case(110)://Latvia
    $postcode=_trim($postcode);
    $postcode=preg_replace('/Latvia/i','',$postcode);
    $postcode=preg_replace('/LV\s*\-?\s*/i','',$postcode);
    $town=_trim($town);
    $postcode=_trim($postcode);
    if(preg_match('/^\d{4}$/',$postcode))
      $postcode='LV-'.$postcode;
    break;

  case(117)://Luxembourg
    $postcode=_trim($postcode);
    $postcode=preg_replace('/Luxembourg/i','',$postcode);
    $postcode=preg_replace('/L\s*\-?\s*/i','',$postcode);
    $town=preg_replace('/\-?\s*Luxembourg/i','',$town);
    if($town=='')
      $town='Luxembourg';
    $town=_trim($town);
    $postcode=_trim($postcode);
    if(preg_match('/^\d{4}$/',$postcode))
      $postcode='L-'.$postcode;
    break;
  case(165)://France
    $postcode=_trim($postcode);
    $postcode=preg_replace('/FRANCE|french republic/i','',$postcode);
    if($postcode=='' and preg_match('/\s*\d{4,5}\s*/',$town,$match)){
       $postcode=trim(trim($match[0]));
       $town=preg_replace('/\s*\d{4,5}\s*/','',$town);
    }

    if(preg_match('/Digne les Bains|Dignes les Bains/i',$town))
      $town='Digne-les-Bains';

     $town=preg_replace('/,\s*france\s*$/i','',$town);

    if($town=='St Cristophe - Charante'){
      $town='St Cristophe';
      $country_d2='Charente';
      $country_d1='Poitou-Charentes';
    }
 if($town=='Cauro - Corse Du Sud'){
      $town='Cauro';
      $country_d2='Corse Du Sud';
      $country_d1='Corse';
    }

    if($town=='Charente'){
      $town='';
       $country_d2='Charente';
       $country_d1='Poitou-Charentes';
    }

  if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	$town=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';
      }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }
      }
    $postcode=_trim($postcode);
    if(preg_match('/^\d{4}$/',$postcode))
      $postcode='0'.$postcode;
    break;

  case(196)://Switzerland
    $postcode=_trim($postcode);
    $postcode=preg_replace('/Switzerland/i','',$postcode);

    if(preg_match('/^\d{4}\s+/',$town,$match)){
      if($postcode=='' or $postcode==trim($match[0])){
	$postcode=trim($match[0]);
	$town=preg_replace('/^\d{4}\s+/','',$town);
      }
    }
    
    $postcode=preg_replace('/^CH\-/i','',$postcode);
    break;
case(193)://Findland
  $postcode=_trim($postcode);
  $postcode=preg_replace('/findland|finland/i','',$postcode);
 $postcode=preg_replace('/^fi\s*\-?\s*/i','',$postcode);

 if($address3=='Klaukkala' and $town=='Nurmijarvi'){
   $address3='';
   $town='Klaukkala';
     }
 if(preg_match('/^\d{3}$/',$postcode))
      $postcode='00'.$postcode;

    if(preg_match('/^\d{4}$/',$postcode))
      $postcode='0'.$postcode;

    break;
case(242)://Isle of man
 if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	$town=$address3;
	$address3='';
      }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }
      
    }





  
    break;


case(241)://Jersey

  $town=preg_replace('/^jersey$|^jersey\s*c\.?i\.?$/i','',$town);
  $town=preg_replace('/\,?\s*Channel Islands$/i','',$town);
  $town=preg_replace('/\,?\s*CI$/i','',$town);
  $town=preg_replace('/\,?\s*C.I.$/i','',$town);
  $town=preg_replace('/\-?\s*jersey$/i','',$town);
  $country_d2=preg_replace('/\-?\s*jersey$|Jersy Channel Isles/i','',$country_d2);
  //  print "1$address1 2$address2 3$address3\n";
 if($town==''){
      if($town_d1!='' ){
	$town=$town_d1;
	$town_d1='';
      }
      elseif($town_d2!=''){
	$town=$town_d2;
	$town_d2='';
      }
      elseif($address3!='' and ($address2!='' or $address1!='') ){
	$town=$address3;
	$address3=$address2;
	$address2=$address1;
	$address1='';
      }else if($address2!='' and $address1!=''){
	$town=$address2;
	$address2='';
      }
      }






    $town=_trim($town);
     if($town_d2=='' and  preg_match('/\w+\.?\s*St\.? Helier$/i',$town) ){
       $town_d2=_trim( preg_replace('/St\.? Helier$/i','',$town));
       $town='St Helier';
  }

     $town_d2=preg_replace('/\./','',$town_d2);
     $town=preg_replace('/^St\s{1,}/','St. ',$town);
  
    break;

case(171)://Sweden
  $postcode=_trim($postcode);
  $postcode=preg_replace('/sweden/i','',$postcode);

  $postcode=preg_replace('/^SE\-?/i','',$postcode);
  if($town=='Malmo')
    $town='Malmö';
  if($country_d2=='Sweden')
    $country_d2='';
  if(preg_match('/Skaraborg/i',$town))
    $town='';
  
  $postcode=preg_replace('/\s/','',$postcode);

  if($this->is_country_d1($town,171) and   $address1='' and $address2!='' and $address3!='' ){
    $country_d1=$town;
    $address3=$address2;
    $address2='';
  }
 if($this->is_country_d1($town,171) and   $address1!='' and $address2!='' and $address3!='' ){
    $country_d1=$town;
    $address3=$address2;
    $address2=$address1;
    $address1='';
  }

 if($country_d2!='' and $country_d1==''){
   $country_d1=$country_d2;
   $country_d2='';
 }

 $postcode=preg_replace('/\s/','',$postcode);

 break;
  case(149)://Norway
      $postcode=_trim($postcode);
      $postcode=preg_replace('/norway/i','',$postcode);

    if(preg_match('/^no.\d+$/i',$town)){
      if($postcode==''){
	$postcode=$town;
	$town='';
      }
    }
    $postcode=preg_replace('/^NO\s*\-?\s*/i','',$postcode);

    $postcode=preg_replace('/^N\-/i','',$postcode);
    if(preg_match('/^\d{3}$/',$postcode))
      $postcode='0'.$postcode;


    break; 
  case(2)://Netherlands
    $town=preg_replace('/Noord Brabant/i','Noord-Brabant',$town);
    $country_d1=preg_replace('/Noord Brabant/i','Noord-Brabant',$country_d1);
    $country_d2=preg_replace('/Noord Brabant/i','Noord-Brabant',$country_d2);
 $town=preg_replace('/Zuid Holland/i','Zuid-Holland',$town);
    $country_d1=preg_replace('/Zuid Holland/i','Zuid-Holland',$country_d1);
    $country_d2=preg_replace('/Zuid Holland/i','Zuid-Holland',$country_d2);
 $town=preg_replace('/Noord Holland/i','Noord-Holland',$town);
    $country_d1=preg_replace('/Noord Holland/i','Noord-Holland',$country_d1);
    $country_d2=preg_replace('/Noord Holland/i','Noord-Holland',$country_d2);
 $town=preg_replace('/Gerderland/i','Gelderland',$town);


 $postcode=_trim($postcode);
 $postcode=preg_replace('/Netherlands|holland/i','',$postcode);

 if($postcode==''){
   preg_match('/\s*\d{4,6}\s*[a-z]{2}\s*/i',$town,$match2);
   $postcode=_trim($match2[0]);
 }
 $postcode=strtoupper($postcode);
 $postcode=preg_replace('/\s/','',$postcode);
 if(preg_match('/^\d{4}[a-z]{2}$/i',$postcode)){
   $town=str_replace($postcode,'',$town);
   $town=str_replace(strtolower($postcode),'',$town);
   $_postcode=substr($postcode,0,4).' '.substr($postcode,4,2);
   $postcode=$_postcode;
   $town=str_replace($postcode,'',$town);
   $town=str_replace(strtolower($postcode),'',$town);

 }
 $town=_trim($town);
  if($this->is_country_d1($address3,2) and $country_d1=='' and $town==''   and ($address1!='' and $address2!='') ){
   $country_d1=$address3;
   $address3='';

 }

  if($this->is_country_d1($town,2) and $country_d1=='' and (($address1!='' and $address2!='') or ($address2!='' and $address3!='') or ($address1!='' and $address3!='')  )   ){
   $country_d1=$town;
   $town='';

 }
   

 if($town=='NH'){
   $country_d1='North Holland';
    $town='';
 }

 if($town=='Zuid Holland'){
    $country_d1='Zuid Holland';
    $town='';
 }
 similar_text($country_d1,$country_d2,$w);
 if($w>90){
   $country_d2='';
 }

 if($country_d1=='' and $country_d2!=''){
   $country_d1=$country_d2;
   $country_d2='';
 }

 if(preg_match('/Zuid.Holland|ZuidHolland/i',$country_d1))
   $country_d1='Zuid Holland';


 if($town==''){
   if($town_d1!='' ){
     $town=$town_d1;
     $town_d1='';
   }
   elseif($town_d2!=''){
     $town=$town_d2;
     $town_d2='';
   }
   elseif($address3!='' and ($address2!='' or $address1!='') ){
     $town=$address3;
     $address3=$address2;
     $address2=$address1;
     $address1='';
   }else if($address2!='' and $address1!=''){
     $town=$address2;
     $address2='';
     $address3=$address1;
     $address1='';
   }
 }



 $town_split=preg_split('/\s*\-\s*|\s*,\s*/',$town);
 if(count($town_split)==2 and $this->is_country_d1($town_split[1],2)){
   $country_d1=$town_split[1];
   $town=$town_split[0];
 }
 
 if($address1!='' and $address2=='' and $address3==''){
   $address3=$address1;
   $address1='';
 }




    break; 


  case(177):// Germany
     $postcode=_trim($postcode);
 $postcode=preg_replace('/germany/i','',$postcode);
    if($country_d2!='' and $country_d1==''){
      $country_d1=$country_d2;
      $country_d2='';
    }
      

    $town=preg_replace('/NRW\s*$/i','',$town);


    if(preg_match('/^berlin$/i',$town))
      $country_d1='Berlin';
       if(preg_match('/^Hamburg$/i',$town))
      $country_d1='Hamburg';
       if(preg_match('/^Bremen$/i',$town))
      $country_d1='Bremen';

       if(preg_match('/^Nuernberg$/i',$town))
      $town='Nürnberg';
    
    if(preg_match('/^Osnabruek$/i',$town)){
   $country_d1='Niedersachsen';
   $town='Osnabrück';
 }
   if(preg_match('/^bavaria$/i',$country_d1))
      $country_d1='Bayern';


    $regex='/^\s*\d{5}\s+|\s+\d{5}\s*$/';
    if(preg_match($regex,$town,$match)){
      if($postcode=='')$postcode=trim($match[0]);
      $town=preg_replace($regex,'',$town);
    }


    if($country_d1==''){
      $country_d1=$this->get_country_d1_name($town,177);
      

    }



    break;
  case(201)://Denmark
   // FIx postcode in town
       $postcode=_trim($postcode);
 $postcode=preg_replace('/denmark|Demnark/i','',$postcode);
$postcode=preg_replace('/^dk\s*\-?\s*/i','',$postcode);
 $town=_trim($town);

   if($postcode=='' and preg_match('/^\d{4}\s+/',$town,$match)){
     $postcode=trim($match[0]);
     $town=preg_replace('/^\d{4}\s+/','',$town);
   }

    $regex='/\s*2610 Rodovre\s*/i';
    if(preg_match($regex,$town,$match)){
      $town='Rodovre';
      $postcode='2610';
    }
 $regex='/KBH K|Kobenhavn/i';
    if(preg_match($regex,$town,$match)){
      $town='Kobenhavn';
    }
 $regex='/Copenhagen/i';
    if(preg_match($regex,$town,$match)){
      $town='Copenhagen';
    }
  $regex='/Aarhus C/i';
    if(preg_match($regex,$town,$match)){
      $town_d2='Aarhus C';
      $town='Aarhus';
    }


     $regex='/Odense\s*,?\s*/i';
    if(preg_match($regex,$town,$match)){
      $town='Odense';
    }
     $regex='/\s*Odense\s*/i';
    if(preg_match($regex,$address3,$match)){
      $address3='';
      $town='Odense';
    }

    $postcode=_trim($postcode);
    if(preg_match('/^\d{4}$/',$postcode)){
      $postcode='DK-'.$postcode;
    }
    if(preg_match('/^KLD$/i',$address3))
       $address3='';
  
    if(preg_match('/^DK\- 7470 Karup J$/i',$address3)){
      $address3='';
      $postcode='DK-7470';
      $town='Karup J';
    }
      
          
    if(preg_match('/Sjalland|Zealand|Sjælland|Sealand/i',$country_d2))
      $country_d2='';
    

       
    if(preg_match('/Sjalland|Zealand/i',$town))
      $town='';

       
if($address3=='' and $address2!='' and  $address1=='' ){
     $address3=$address2;
     $address2=$address1;

   }


if($address3=='' and $address2!='' and  $address1!='' ){
     $address3=$address2;
     $address2=$address1;
     $address1='';
   }

 if($town==''){
   if($town_d1!='' ){
     $town=$town_d1;
     $town_d1='';
   }
   elseif($town_d2!=''){
     $town=$town_d2;
     $town_d2='';
   }
   elseif($address3!='' and ($address2!='' or $address1!='') ){
     $town=$address3;
     $address3=$address2;
     $address2=$address1;
     $address1='';
   }else if($address2!='' and $address1!=''){
     $town=$address2;
     $address2='';
     $address3=$address1;
     $address1='';
   }
 }
    





    break; 
  default:
    $postcode=$address_raw_data['postcode'];
    $regex='/\s*'.$country.'\s*/i';
    $postcode=preg_replace($regex,'',$postcode);
    
  }


if($address3=='' and $address2!='' and  $address1=='' ){
     $address3=$address2;
     $address2=$address1;

   }


if($address3=='' and $address2!='' and  $address1!='' ){
     $address3=$address2;
     $address2=$address1;
     $address1='';
   }

 if($town==''){
   if($town_d1!='' ){
     $town=$town_d1;
     $town_d1='';
   }
   elseif($town_d2!=''){
     $town=$town_d2;
     $town_d2='';
   }
   elseif($address3!='' and ($address2!='' or $address1!='') ){
     $town=$address3;
     $address3=$address2;
     $address2=$address1;
     $address1='';
   }else if($address2!='' and $address1!=''){
     $town=$address2;
     $address2='';
     $address3=$address1;
     $address1='';
   }
 }
    
  


  // Country ids
 if($country_d1!=''){
 $sql=sprintf("select `Country Primary Division Key` as id  from  `Country Primary Division Dimension` where (`Country Primary Division Name`='%s' or `Country Primary Division Native Name`='%s' or `Country Primary Division Local Native Name`='%s' ) and `Country Key`=%d",addslashes($country_d1),addslashes($country_d1),addslashes($country_d1),$country_id);
 //  print "$sql\n";
 
 $result=mysql_query($sql);
 if($row=mysql_fetch_array($result, MYSQL_ASSOC))
   $country_d1_id=$row['id'];
 }


if($country_d2!=''){
    $sql=sprintf("select `Country Secondary Division Key`  as id, `Country Primary Division Key`   as country_d1_id from `Country Secondary Division Dimension`   where (`Country Secondary Division Name`='%s' or `Country Secondary Division Native Name`='%s' or `Country Secondary Division Local Native Name`='%s' ) and `Country Key`=%d",addslashes($country_d2),addslashes($country_d2),addslashes($country_d2),$country_id);
   

    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
      
      $country_d2_id=$row['id'];
      if(mysql_num_rows($result)==1){
	  $country_d1_id=$row['country_d1_id'];
      }
      
    }
    else
      $country_d2_id=0;
 }



 $sql=sprintf("select `Town Key` as id,`Country Secondary Division Key` as  country_d2_id, `Country Primary Division Key` as country_d1_id from `Town Dimension` where (`Town Name`='%s' or `Town Native Name`='%s' or `Town Local Native Name`='%s' ) and `Country Key`=%d",addslashes($town),addslashes($town),addslashes($town),$country_id);
 //print $sql;
 $res = mysql_query($sql);  
 
 if(mysql_num_rows($res)==1){
   
   $row=mysql_fetch_array($res, MYSQL_ASSOC);
   $town_id=$row['id'];
   if($country_d2_id==0)
     $country_d2_id=$row['country_d2_id'];
   if($country_d1_id==0)
     $country_d1_id=$row['country_d1_id'];
   
     
   
 }
 else
   $town_id=0;
 
 
 if(preg_match('/\d+\s*\-\s*\d+/',$address3)){
    $address3=preg_replace('/\s*\-\s*/','-',$address3);
 }
 if(preg_match('/\d+\s*\-\s*\d+/',$address2)){
   $address2=preg_replace('/\s*\-\s*/','-',$address2);
  }
 $address1=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$address1);
 $address2=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$address2);
 $address3=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$address3);
 $address3=  preg_replace('/^p o box\s+/i','PO BOX ',$address3);
 $address3=  preg_replace('/^NULL$/i','',$address3);

 $address1=preg_replace('/\s{2,}/',' ',$address1);
 $address2=preg_replace('/\s{2,}/',' ',$address2);
 $address3=preg_replace('/\s{2,}/',' ',$address3);
 $town=preg_replace('/\s{2,}/',' ',$town);
 $town_d1=preg_replace('/\s{2,}/',' ',$town_d1);
 $town_d2=preg_replace('/\s{2,}/',' ',$town_d2);
 $town=preg_replace('/(\,|\-)$\s*/','',$town);
  
 $address_data=array(
		     'internal_address'=>mb_ucwords(_trim($address1)),
		     'building_address'=>mb_ucwords(_trim($address2)),
		     'street_address'=>mb_ucwords(_trim($address3)),
		     'town_d2'=>mb_ucwords(_trim($town_d2)),
		     'town_d1'=>mb_ucwords(_trim($town_d1)),
		     'town'=>mb_ucwords(_trim($town)),
		     'country_d2'=>mb_ucwords(_trim($country_d2)),
		     'country_d1'=>mb_ucwords(_trim($country_d1)),
		     'postcode'=>mb_ucwords(_trim($postcode)),
		      'country'=>mb_ucwords(_trim($country)),
		     'town_d2_id'=>$town_d2_id,
		     'town_d1_id'=>$town_d1_id,
		     'town_id'=>$town_id,
		     'country_d2_id'=>$country_d2_id,
		     'country_d1_id'=>$country_d1_id,
		     'country_id'=>$country_id,
		     'military_installation_data'=>$military_installation,
		     'military_base'=>$military_base
		     );
 

     return $address_data;


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


function get_country_d2_name($id=''){
  if(!is_numeric($id))
     return '';
  $sql=sprintf("select `Country Secondary Division Name` as name from `Country Secondary Division Dimension` where `Country Secondary Division Key`=%d",$id);
  //  print $sql;
  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    return $row['name'];
  }
  return '';
}
function get_country_d1_name($id=''){
  if(!is_numeric($id))
     return '';
  $sql=sprintf("select `Country Primary Division Name` as name from `Country Primary Division Dimension` where `Country Primary Division Key`=%d",$id);
  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    return $row['name'];
  }
  return '';
}


function is_country_d1($country_d1,$country_id){
   if($country_d1=='')
     return false;

  if($country_id>0)
    $sql=sprintf("select `Country Primary Division Key` as id from `Country Primary Division Dimension` where (`Country Primary Division Name`='%s' or `Country Primary Division Native Name`='%s' or `Country Primary Division Local Native Name`='%s') and `Country Key`=%d",addslashes($country_d1),addslashes($country_d1),addslashes($country_d1),$country_id);
  else
    $sql=sprintf("select `Country Primary Division Key` as id from `Country Primary Division Dimension` where (`Country Primary Division Name`='%s' or `Country Primary Division Native Name`='%s' or `Country Primary Division Local Native Name`='%s') ",addslashes($country_d1),addslashes($country_d1),addslashes($country_d1));

  //    print "$sql\n";
 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
 if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    return true;
  }else
    return false;
}


function is_country_d2($country_d2,$country_id){
   if($country_d2=='')
     return false;

  if($country_id>0)
    $sql=sprintf("select `Country Secondary Division Key` as id from `Country Secondary Division Dimension` where (`Country Secondary Division Name`='%s' or `Country Secondary Division Native Name`='%s' or `Country Secondary Division Local Native Name`='%s') and `Country Key`=%d",addslashes($country_d2),addslashes($country_d2),addslashes($country_d2),$country_id);
  else
    $sql=sprintf("select `Country Secondary Division Key` as id from `Country Secondary Division Dimension` where (`Country Secondary Division Name`='%s' or `Country Secondary Division Native Name`='%s' or `Country Secondary Division Local Native Name`='%s') ",addslashes($country_d2),addslashes($country_d2),addslashes($country_d2));

  //    print "$sql\n";
 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
 if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    return true;
  }else
    return false;
}

function is_town($town,$country_id){
   if($town=='')
     return false;

  if($country_id>0)
    $sql=sprintf("select `Town Key` as id from `Town Dimension` where (`Town Name`='%s' or `Town Native Name`='%s' or `Town Local Native Name`='%s') and `Country Key`=%d",addslashes($town),addslashes($town),addslashes($town),$country_id);
  else
    $sql=sprintf("select `Town Key` as id from `Town Dimension` where (`Town Name`='%s' or `Town Native Name`='%s' or `Town Local Native Name`='%s') ",addslashes($town),addslashes($town),addslashes($town));

  //  print "$sql\n";
 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
 if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    return true;
  }else
    return false;
}


 function is_valid_postcode($postcode,$country_id){
   $postcode=_trim($postcode);
   switch($country_id){
   case 30:
     if(preg_match('/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {0,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/i',$postcode))
       return true;
     else
       return false;
     break;
   }
   return false;
   
 }

 function parse_street($line){

   //print "********** $line\n";

   $number='';
   $name='';
   $direction='';
   $type='';

   //extract number
   $line=_trim($line);
   if(preg_match('/^\#?\s*\d+(\,\d+\-\d+|\\\d+|\/\d+)?\s*/i',$line,$match)){
     $number=$match[0];
     $len=strlen($number);
     $name=substr($line,$len);
   }elseif(preg_match('/(\#|no\.?)?\s*\d.*$/i',$line,$match)){
     $number=$match[0];
     $len=strlen($number)+1;
     $name=substr($line,strlen($line)-$len);

   }
   $name=preg_replace('/^\s*,\s*/','',$name);

   $name=_trim($name);
   $number=_trim($number);
   
   if(preg_match('/\s(street|st\.?)$/i',$name,$match)){
     $type="Street";
 $len=strlen($match[0])+1;
     $name=substr($name,0,strlen($name)-$len);
   }
   if(preg_match('/\s(road|rd\.?)$/i',$name,$match)){
     $type="Road";
      $len=strlen($match[0])+1;
     $name=substr($name,0,strlen($name)-$len);
   }
   if(preg_match('/\s(close)$/i',$name,$match)){
     $type="Close";
 $len=strlen($match[0])+1;
     $name=substr($name,0,strlen($name)-$len);
   }
   if(preg_match('/\s(Av\.?|avenue|ave\.?)$/i',$name,$match)){
     $type="Avenue";
     $len=strlen($match[0])+1;
     $name=substr($name,0,strlen($name)-$len);
   }
   

   $name=_trim($name);
   
   $this->data['Street Number']=$number;
   $this->data['Street Name']=$name;
   $this->data['Street Type']=$type;
   $this->data['Street Direction']=$direction;


 }

 


}
?>