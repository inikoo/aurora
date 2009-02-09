<?
include_once('Country.php');

class Telecom{

  var $data=array();
  var $id=false;

  
  function __construct($arg1=false,$arg2=false) {

    //print "$arg1 ********".is_numeric($arg1)."*******\n";
     if(is_numeric($arg1)){
       //  print "yess\n";
       $this->get_data('id',$arg1);

     }

   if(is_array($arg2) and $arg1='new'){
       $this->create($arg2);
       return;
     }
  $this->get_data($arg1,$arg2);
  }


  function get_data($tipo,$id){

    if($tipo=='id'){
     
      if($id==0){
	print "error telecom key can not be zero T:$tipo ID:$id\n";
	exit;
      }
      $sql=sprintf("select * from `Telecom Dimension` where  `Telecom Key`=%d",$id);
      $result=mysql_query($sql);
      
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Telecom Key'];
      }
    
    }
}

 function display($tipo=''){

   switch($tipo){
   default:
     $tmp=($this->data['Telecom Country Code']!=''?'+'.$this->data['Telecom Country Code'].' ':'').($this->data['Telecom Area Code']!=''?$this->data['Telecom Area Code'].' ':'').$this->get('spaced_number').($this->data['Telecom Extension']!=''?' '._('ext').' '.$this->data['Telecom Extension']:'');
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



 
//    $country=new country('code','UNK');
//    $this->unknown_country_id=$country->id;
//    if(isset($data['country key']) and $data['country key']!='')
//      $this->data['Telecom Country Key']=$data['country key'];
//    else
//      $this->data['Telecom Country Key']=$this->unknown_country_id;
   
   
//    $this->data['Telecom Original Number']='';
//    $this->data['Telecom Original Extension']='';
//    $this->data['Telecom Original Area Code']='';
//    $this->data['Telecom Original Country Code']='';
//    $this->data['Telecom Original Type']='';
   
//    $this->data['Telecom Original Restricted Country Key']='';
   
//    $this->data['Telecom Number']='';
//    $this->data['Telecom Extension']='';
//    $this->data['Telecom Area Code']='';
//    $this->data['Telecom Country Code']='';
//    $this->data['Telecom Type']='';
//    $this->data['Telecom Restricted Country Key']='';
   
//    $this->data['Telecom National Access Code']='';
   
//    if(isset($data['Telecom Number']))
//      $this->data['Telecom Original Number']=$data['Telecom Number'];
//    if(isset($data['Telecom Extension']))
//      $this->data['Telecom Original Extension']=$data['Telecom Extension'];
//    if(isset($data['Telecom Area Code']))
//      $this->data['Telecom Original Area Code']=$data['Telecom Area Code'];
//    if(isset($data['Telecom Country Code']))
//      $this->data['Telecom Original Country Code']=$data['Telecom Country Code'];
//    if(isset($data['Telecom Type']))
//      $this->data['Telecom Original Type']=$data['Telecom Type'];
//    if(isset($data['Telecom Restricted Country Key']))
//      $this->data['Telecom Original Restricted Country Key']=$data['Telecom Restricted Country Key'];
   
//    $this->parse_telecom(
// 			$this->data['Telecom Original Number']
// 			,$this->data['Telecom Country Key']
// 			,$this->data['Telecom Original Type']
// 			,$this->data['Telecom Original Country Code']
// 			,$this->data['Telecom Original Area Code']
// 			,$this->data['Telecom Original Extension']
// 			);
    
   //  print_r( $this->data);
   //  print_r( $data);
   $this->data=$this->parse_telecom($data);

//    exit;

    if($this->data['Telecom Number']==''){
      $this->new=false;
      return;
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


 
 function parse_telecom($data,$arg2=244){
   if(is_array($data)){
     
     if(!array_key_exists('Telecom Original Number',$data)){
       print_r($data);
       print "warning no telecom number provided";
       exit;
     }
       

     if(!array_key_exists('Telecom Original Country Code',$data))
       $data['Telecom Original Country Code']='';
     if(!array_key_exists('Telecom Original Area Code',$data))
       $data['Telecom Original Area Code']='';
     if(!array_key_exists('Telecom Original Extension',$data))
       $data['Telecom Original Extension']='';
     if(!array_key_exists('Telecom Original Extension',$data))
       $data['Telecom Original Extension'];
     if(!array_key_exists('Telecom Original Country Key',$data))
       $data['Telecom Original Country Key']=244;
     if(!array_key_exists('Telecom Original Type',$data))
       $data['Telecom Original Type']='Unknown';
        if(!array_key_exists('Telecom Original National Access Code',$data))
       $data['Telecom Original National Access Code']='';


   }elseif(is_string($data)){
     $raw_tel=$data;
     $data=array();
     $data['Telecom Original Number']=$raw_tel;
     $data['Telecom Original Country Code']='';
     $data['Telecom Original Area Code']='';
     $data['Telecom Original Extension']='';
     $data['Telecom Country Key']=$arg2;
     $data['Telecom Original Type']='Unknown';
     $data['Telecom Original National Access Code']='';
     
   }

   $data['Telecom Country Key']=$data['Telecom Original Country Key'];
   $data['Telecom National Access Code']=$data['Telecom Original National Access Code'];

   $raw_tel=$data['Telecom Original Number'];
   // print "org1 $data ".$raw_tel."\n";
   $raw_tel=preg_replace('/\(/',' (',$raw_tel);
   $raw_tel=preg_replace('/\)/',') ',$raw_tel);
   $raw_tel=preg_replace('/-/','',$raw_tel);
 $raw_tel=_trim($raw_tel);
 // print "org2 $data ".$raw_tel."\n";
   
   if(preg_match('/^\+\d{1,4}\s/',$raw_tel,$match)){
     $len=strlen($match[0]);
     $data['Telecom Country Code']=preg_replace('/[^0-9]/','',$match[0]);
     $raw_tel=substr($raw_tel,$len);
   }

   if(preg_match('/^\s*\(\d+\)\s*/',$raw_tel,$match)){
     $len=strlen($match[0]);
     $data['Telecom National Access Code']=preg_replace('/[^0-9]/','',$match[0]);
     $raw_tel=substr($raw_tel,$len);
   }


   
   $data['Telecom Country Code']=preg_replace('/[^0-9]/','',$data['Telecom Original Country Code']);
   $data['Telecom Area Code']=preg_replace('/[^0-9]/','',$data['Telecom Original Area Code']);

   // fisrt try to see if it has an extension;


   $tel_ext=preg_split('/ext/i',$raw_tel);

  if(count($tel_ext)==2){
    $data['Telecom Extension']=preg_replace('/[^0-9]/','',$tel_ext[1]);
    $data['Telecom Number']=preg_replace('/[^0-9]/','',$tel_ext[0]);

  }else{
  
    $data['Telecom Extension']=preg_replace('/[^0-9]/','',$data['Telecom Original Extension']);
    $data['Telecom Number']=preg_replace('/[^0-9]/','',$raw_tel);

  }
  
  if($data['Telecom Country Code']!=''){
    $regex_icode="^0{0,2}".$data['Telecom Country Code'];
    $data['Telecom Number']=preg_replace('/^0{0,2}'.$data['Telecom Country Code'].'/i','',$data['Telecom Number']);
  }
  
  // print_r($data);
  // country expcific
  //  $country_id=$this->date['Telecom Country Key'];


  
  //if($country_id==$this->unknown_country_id or $country_id=='')
  //  $country_id=$this->get_country_id();
  //print $country_id;exit;
  //print

  //$data['Telecom Country Key']=$country_id;

  $data['is_mobile']='';



  switch($data['Telecom Country Key']){
    
  case(30)://UK
    if(preg_match('/^0845/',$data['Telecom Number'])){
      $data['National Only Telecom']=1;
      $data['Telecom Country Code']='';
      $data['Telecom Area Code']='0845';
      $data['Telecom National Access Code']='';
      $data['Telecom Number']=preg_replace('/^0845/','',$data['Telecom Number']);
    }
    $data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']);
     $data['Telecom National Access Code']='0';
    if(preg_match('/^7/',$data['Telecom Number']))
      $data['is_mobile']=1;
    else
      $data['is_mobile']=0;
    break;
  case(75)://Ireland
    if(preg_match('/^0?8(2|3|5|6|7|8|9)/',$data['Telecom Number']))
      $data['is_mobile']=1;
    else
      $data['is_mobile']=0;
    break;
  case(47)://Spain
  case(165)://France
    if(preg_match('/^0?6/',$data['Telecom Number']))
    $data['is_mobile']=1;
    else
      $data['is_mobile']=0;
    break;
  }
  
  if($data['is_mobile']==1)
    $data['Telecom Type']='Mobile';
  else if($data['Telecom Original Type']=='Mobile' and $data['is_mobile']==0)
    $data['Telecom Type']='Unknown';
  else 
    $data['Telecom Type']=$data['Telecom Original Type'];
  



  return $data;

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