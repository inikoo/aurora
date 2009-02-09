<?
include_once('Country.php');

class Telecom{

  var $data=array();
  var $id=false;

  
  function __construct($arg1=false,$arg2=false) {


     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);

     }

   if(is_array($arg2) and $arg1='new'){
       $this->create($arg2);
       return;
     }
  $this->get_data($arg1,$arg2);
  }


  function get_data($tipo,$id){
    $sql=sprintf("select * from `Telecom Dimension` where  `Telecom Key`=%d",$id);
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Telecom Key'];
    }
    

}

 function display($tipo=''){

   switch($tipo){
   default:
     $tmp=($this->data['telecom country code']!=''?'+'.$this->data['telecom country code'].' ':'').($this->data['telecom area code']!=''?$this->data['telecom area code'].' ':'').$this->get('spaced_number').($this->data['telecom extension']!=''?' '._('ext').' '.$this->data['telecom extension']:'');
     return $tmp;
   }
 }
 

 function get($key='')
 {

   
   if(array_key_exists($key,$this->data))
     return $this->data[$key];
   
   switch($key){
   case('spaced_number'):
     return _trim(strrev(chunk_split(strrev($this->data['Telecom Number']),4," ")));
     break;

     
   }
   

   $_key=ucwords($key);
   if(array_key_exists($_key,$this->data))

     return $this->data[$_key];
   
   print "Error $key not found in get from telecom\n";
   exit;

   return false;
 }


 function create($data){

 
   $country=new country('code','UNK');
   $this->unknown_country_id=$country->id;
   if(isset($data['country key']) and $data['country key']!='')
     $this->data['Telecom Country Key']=$data['country key'];
   else
     $this->data['Telecom Country Key']=$this->unknown_country_id;
   
   
   $this->data['Telecom Original Number']='';
   $this->data['Telecom Original Extension']='';
   $this->data['Telecom Original Area Code']='';
   $this->data['Telecom Original Country Code']='';
   $this->data['Telecom Original Type']='';
   
   $this->data['Telecom Original Restricted Country Key']='';
   
   $this->data['Telecom Number']='';
   $this->data['Telecom Extension']='';
   $this->data['Telecom Area Code']='';
   $this->data['Telecom Country Code']='';
   $this->data['Telecom Type']='';
   $this->data['Telecom Restricted Country Key']='';
   
   $this->data['Telecom National Access Code']='';
   
   if(isset($data['Telecom Number']))
     $this->data['Telecom Original Number']=$data['Telecom Number'];
   if(isset($data['Telecom Extension']))
     $this->data['Telecom Original Extension']=$data['Telecom Extension'];
   if(isset($data['Telecom Area Code']))
     $this->data['Telecom Original Area Code']=$data['Telecom Area Code'];
   if(isset($data['Telecom Country Code']))
     $this->data['Telecom Original Country Code']=$data['Telecom Country Code'];
   if(isset($data['Telecom Type']))
     $this->data['Telecom Original Type']=$data['Telecom Type'];
   if(isset($data['Telecom Restricted Country Key']))
     $this->data['Telecom Original Restricted Country Key']=$data['Telecom Restricted Country Key'];
   
   $this->parse_telecom(
			$this->data['Telecom Original Number']
			,$this->data['Telecom Country Key']
			,$this->data['Telecom Original Type']
			,$this->data['Telecom Original Country Code']
			,$this->data['Telecom Original Area Code']
			,$this->data['Telecom Original Extension']
			);
    
    //  print_r( $this->data);

    if($this->data['Telecom Number']==''){
      $this->new=false;
      return false;
    }
    



    $sql=sprintf("insert into `Telecom Dimension` (`Telecom Type`,`Telecom Country Code`,`Telecom National Access Code`,`Telecom Area Code`,`Telecom Number`,`Telecom Extension`,`Telecom Country Key`) values (%s,%s,%s,%s,%s,%s,%s)",
		 prepare_mysql($this->data['Telecom Type']),
		 prepare_mysql($this->data['Telecom Country Code']),
		 prepare_mysql($this->data['Telecom National Access Code']),
		 prepare_mysql($this->data['Telecom Area Code']),
		 prepare_mysql($this->data['Telecom Number']),
		 prepare_mysql($this->data['Telecom Extension']),
		 prepare_mysql($this->data['Telecom Country Key'])
		 );
    
    
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->get_data('id',$this->id);
      $this->new=true;
      return true;
    }else{
      print "Error can not create telecom\n";exit;
    }

 }


 
 function parse_telecom($raw_tel,$country_id='244',$tipo='Unknown',$telecom_country_code='',$telecom_area_code='',$extension=''){

   $this->data['Telecom Original Country Code']=$telecom_country_code;
   $this->data['Telecom Original Area Code']=$telecom_area_code;
   $this->data['Telecom Original Extension']=$extension;
   $this->data['Telecom Original Number']=$raw_tel;
   $this->data['Telecom Country Key']=$country_key;
   $this->data['Telecom Original Type']=$tipo;

   $raw_tel=preg_replace('/\(/',' (',$raw_tel);
   $raw_tel=preg_replace('/\)/',') ',$raw_tel);

   $raw_tel=_trim($raw_tel);
   
   if(preg_match('/^\+\d{1,4}\s/',$raw_tel,$match)){
     $len=strlen($match[0]);
     $this->data['Telecom Country Code']=preg_replace('/[^0-9]/','',$match[0]);
     $raw_tel=substr($raw_tel,$len);
   }

   if(preg_match('/^\s*\(\d+\)\s*/',$raw_tel,$match)){
     $len=strlen($match[0]);
     $this->data['Telecom National Access Code']=preg_replace('/[^0-9]/','',$match[0]);
     $raw_tel=substr($raw_tel,$len);
   }


   if($this->data['Telecom Country Code']=='')
     $this->data['Telecom Country Code']=preg_replace('/[^0-9]/','',$this->data['Telecom Original Country Code']);
  
     

   $this->data['Telecom Area Code']=preg_replace('/[^0-9]/','',$this->data['Telecom Original Area Code']);

   // fisrt try to see if it has an extension;


   $tel_ext=preg_split('/ext/i',$raw_tel);

  if(count($tel_ext)==2){
    $this->data['Telecom Extension']=preg_replace('/[^0-9]/','',$tel_ext[1]);
    $this->data['Telecom Number']=preg_replace('/[^0-9]/','',$tel_ext[0]);

  }else{
  
    $this->data['Telecom Extension']=preg_replace('/[^0-9]/','',$this->data['Telecom Original Extension']);
    $this->data['Telecom Number']=preg_replace('/[^0-9]/','',$raw_tel);

  }
  
  if($this->data['Telecom Country Code']!=''){
    $regex_icode="/^0{0,2}".$this->data['Telecom Country Code']."/";
    $this->data['Telecom Number']=preg_replace($regex_icode,'',$this->data['Telecom Number']);
  }
  
  // print_r($this->data);
  // country expcific
  $country_id=$this->date['Telecom Country Key'];


  
  //if($country_id==$this->unknown_country_id or $country_id=='')
  //  $country_id=$this->get_country_id();
  //print $country_id;exit;
  //print

  $this->data['Telecom Country Key']=$country_id;

  $this->data['is_mobile']='';



  switch($country_id){
    
  case(30)://UK
    if(preg_match('/^0845/',$this->data['Telecom Number'])){
      $this->data['National Only Telecom']=1;
      $this->data['Telecom Country Code']='';
      $this->data['Telecom Area Code']='0845';
      $this->data['Telecom National Access Code']='';
      $this->data['Telecom Number']=preg_replace('/^0845/','',$this->data['Telecom Number']);
    }
    $this->data['Telecom Number']=preg_replace('/^0/','',$this->data['Telecom Number']);
     $this->data['telecom national access code']='0';
    if(preg_match('/^7/',$this->data['Telecom Number']))
      $this->data['is_mobile']=1;
    else
      $this->data['is_mobile']=0;
    break;
  case(75)://Ireland
    if(preg_match('/^0?8(2|3|5|6|7|8|9)/',$this->data['Telecom Number']))
      $this->data['is_mobile']=1;
    else
      $this->data['is_mobile']=0;
    break;
  case(47)://Spain
  case(165)://France
    if(preg_match('/^0?6/',$this->data['Telecom Number']))
    $this->data['is_mobile']=1;
    else
      $this->data['is_mobile']=0;
    break;
  }
  
  if($this->data['is_mobile']==1)
    $this->data['Telecom Type']='Mobile';
  else if($this->data['Telecom Original Type']=='Mobile' and $this->data['is_mobile']==0)
    $this->data['Telecom Type']='Unknown';
  else 
    $this->data['Telecom Type']=$this->data['Telecom Original Type'];
  
  return "caca";

  // print_r($this->data);
 }
 

 function get_country_id(){
   if($this->data['Telecom Country Code']){
     $sql="select * from `Country Dimension` where `Country Telephone Code`=".prepare_mysql($this->data['telecom country code']);
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       return $row['Country Key'];
     }
     
   }
   return false;
 }

}
?>