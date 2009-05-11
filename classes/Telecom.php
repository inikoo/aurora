<?
/*
 File: Telecom.php 

 This file contains the Telecom Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('Country.php');

class Telecom{
  // Array: data
  // Class data
  var $data=array();
  // Integer: id
  // Database Primary Key
  var $id=false;

     /*
       Constructor: Telecom
     
       Initializes the class, Search/Load or Create for the Telephone/Fax data set 

     */
  function Telecom($arg1=false,$arg2=false) {

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

    if(!$data)
      $data=$this->data;
   switch($tipo){
   case('plain'):
     return $this->plain_number($this->data);
   case('number'):
   default:
     return $this->formated_number($this->data);
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

/*
   Function: base_data
   Initialize data  array with the default field values
   */
function base_data(){
   $data=array();

   $ignore_fields=array('Telecom Key');

   $result = mysql_query("SHOW COLUMNS FROM `Telecom Dimension`");
   if (!$result) {
     echo 'Could not run query: ' . mysql_error();
     exit;
   }
   if (mysql_num_rows($result) > 0) {
     while ($row = mysql_fetch_assoc($result)) {
       if(!in_array($row['Field'],$ignore_fields))
	 $data[$row['Field']]=$row['Default'];
     }
   }
   return $data;
 }
/*
  Function: create
  Insert new number to the database
*/
function find($data,$options){
  
  $sql=sprintf("select `Telephone Key`,`Subject Key`  from `Telecom Dimension` where `Telecom Plain Number`=%s and `Telecom`")

}



/*
Function: create
Insert new number to the database


 */
 function create($data){
   
   if(is_string($data)){
     $this->data=$this->parse_number($data);
   }elseif(is_array($data)){
     $this->data=$this->clean_data($data);
   }
   
   if($this->data['Telecom Number']==''){
     $this->new=false;
     $this->error=true;
     $this->msg=_('Wrong telephone number');
   }

   $sql=sprintf("insert into `Telecom Dimension` (`Telecom Type`,`Telecom Country Telephone Code`,`Telecom National Access Code`,`Telecom Area Code`,`Telecom Number`,`Telecom Extension`,`Telecom Plain Number`) values (%s,%s,%s,%s,%s,%s,%s)",
		prepare_mysql($this->data['Telecom Type']),
		prepare_mysql($this->data['Telecom Country Telephone Code']),
		prepare_mysql($this->data['Telecom National Access Code']),
		prepare_mysql($this->data['Telecom Area Code']),
		prepare_mysql($this->data['Telecom Number']),
		prepare_mysql($this->data['Telecom Extension']),
		prepare_mysql($this->display('plain'))
		);
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->get_data('id',$this->id);
      $this->new=true;
      return true;
    }else{
      $this->msg="Error can not create telecom\n";
      $this->new=false;
    }

 }

 /*Function: clean_data
   Parse the number in its componets

   
  */
 function clean_data($raw_data){
   $data=Telecom::base_data();
   foreach($raw_data as $key=>$val){
     if(array_key_exists($key,$data)){
       $data=$val;
     }
   }
   
   return $data;
   
 }

 /*Function: parse_number
   Parse the number in its componets

   Parameters:
   $data - _mixed_ can be a array of the procosed components or a string with the number 
   
   
  */
 function parse_number($number,$country_code='UNK'){
   $data=Telecom::base_data();

   $number=_trim($number);
   if(preg_match('/e/i',$number)){
     $tmp=preg_split('/\s*(ext|e)\s*/i',$number);
     if(count($tmp)==2){
       $number=$tmp[0];
       $data['Telecom Extension']=$tmp[1];
     }elseif(count($tmp)>2){
       $this->error=true;
       $this->msg=_('Error in number');
       
     }
   }
   // parse common formats
  // +44 1142729165
   if(preg_match('/^\+\d+ \d/',$number)){
     preg_match('/^\+\d+\s*/',$number,$match);
     
     $number=preg_replace('/^\+\d+\s*/','',$number);
     $data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);



   }
   // +44 (0) 114 2729165
   elseif(preg_match('/^\+\d+ \(\d+\) \d{1,3} \d/',$number)){
     preg_match('/^\+\d+\s*/',$number,$match);
     
     $number=preg_replace('/^\+\d+\s*/','',$number);
     $data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);

     preg_match('/^\(\d+\) /',$number,$match);
     $number=preg_replace('/^\(\d+\) /','',$number);
     $data['Telecom National Access Code']=preg_replace('/[^\d]/','',$match[0]);
     preg_match('/^\d{1,3}\s*/',$number,$match);
     $number=preg_replace('/^\d{1,3}\s*/','',$number);
     $data['Telecom Area Code']=preg_replace('/[^\d]/','',$match[0]);
     $data['Telecom Number']=preg_replace('/[^\d]/','',$number);
     return $data;
   }
   //  +44 (0) 1142729165
   else if(preg_match('/^\+\d+ \(\d+\) \d/',$number)){
     preg_match('/^\+\d+\s*/',$number,$match);
     
     $number=preg_replace('/^\+\d+\s*/','',$number);
     $data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);

     preg_match('/^\(\d+\) /',$number,$match);
     $number=preg_replace('/^\(\d+\) /','',$number);
     $data['Telecom National Access Code']=preg_replace('/[^\d]/','',$match[0]);


   }
   // +44 114 2729165
   elseif(preg_match('/^\+\d+ \d{1,3} \d/',$number)){
     preg_match('/^\+\d+\s*/',$number,$match);
     $number=preg_replace('/^\+\d+\s*/','',$number);
     $data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);
     preg_match('/^\d{1,3}\s*/',$number,$match);
     $number=preg_replace('/^\d{1,3}\s*/','',$number);
     $data['Telecom Area Code']=preg_replace('/[^\d]/','',$match[0]);
     $data['Telecom Number']=preg_replace('/[^\d]/','',$number);
     return $data;
   }
   // +44 1142729165
   elseif(preg_match('/^\+\d+ \d/',$number)){
     preg_match('/^\+\d+\s*/',$number,$match);
     $number=preg_replace('/^\+\d+\s*/','',$match[0]);
     $data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);

     
   }

  /*  $raw_tel=$data['Telecom Original Number']; */
/*    // print "org1 $data ".$raw_tel."\n"; */
/*    $raw_tel=preg_replace('/\(/',' (',$raw_tel); */
/*    $raw_tel=preg_replace('/\)/',') ',$raw_tel); */
/*    $raw_tel=preg_replace('/-/','',$raw_tel); */
/*    $raw_tel=_trim($raw_tel); */
/*  // print "org2 $data ".$raw_tel."\n"; */
   
/*    if(preg_match('/^\+\d{1,4}\s/',$raw_tel,$match)){ */
/*      $len=strlen($match[0]); */
/*      $data['Telecom Country Telephone Code']=preg_replace('/[^0-9]/','',$match[0]); */
/*      $raw_tel=substr($raw_tel,$len); */
/*    } */

/*    if(preg_match('/^\s*\(\d+\)\s*\/',$raw_tel,$match)){ */
/*      $len=strlen($match[0]); */
/*      $data['Telecom National Access Code']=preg_replace('/[^0-9]/','',$match[0]); */
/*      $raw_tel=substr($raw_tel,$len); */
/*    } */


   
/*    $data['Telecom Country Telephone Code']=preg_replace('/[^0-9]/','',$data['Telecom Original Country Telephone Code']); */
/*    $data['Telecom Area Code']=preg_replace('/[^0-9]/','',$data['Telecom Original Area Code']); */

/*    // fisrt try to see if it has an extension; */


/*    $tel_ext=preg_split('/ext/i',$raw_tel); */

/*   if(count($tel_ext)==2){ */
/*     $data['Telecom Extension']=preg_replace('/[^0-9]/','',$tel_ext[1]); */
/*     $data['Telecom Number']=preg_replace('/[^0-9]/','',$tel_ext[0]); */

/*   }else{ */
  
/*     $data['Telecom Extension']=preg_replace('/[^0-9]/','',$data['Telecom Original Extension']); */
/*     $data['Telecom Number']=preg_replace('/[^0-9]/','',$raw_tel); */

/*   } */
  
/*   if($data['Telecom Country Telephone Code']!=''){ */
/*     $regex_icode="^0{0,2}".$data['Telecom Country Telephone Code']; */
/*     $data['Telecom Number']=preg_replace('/^0{0,2}'.$data['Telecom Country Telephone Code'].'/i','',$data['Telecom Number']); */
/*   } */
  
/*   // print_r($data); */
/*   // country expcific */
/*   //  $country_id=$this->date['Telecom Country Code']; */


  
/*   //if($country_id==$this->unknown_country_id or $country_id=='') */
/*   //  $country_id=$this->get_country_id(); */
/*   //print $country_id;exit; */
/*   //print */

/*   //$data['Telecom Country Code']=$country_id; */

/*   $data['is_mobile']=''; */



/*   switch($data['Telecom Country Code']){ */
    
/*   case('GBR')://UK */
/*     if(preg_match('/^0845/',$data['Telecom Number'])){ */
/*       $data['National Only Telecom']=1; */
/*       $data['Telecom Country Telephone Code']=''; */
/*       $data['Telecom Area Code']='0845'; */
/*       $data['Telecom National Access Code']=''; */
/*       $data['Telecom Number']=preg_replace('/^0845/','',$data['Telecom Number']); */
/*     } */
/*     $data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']); */
/*      $data['Telecom National Access Code']='0'; */
/*     if(preg_match('/^7/',$data['Telecom Number'])) */
/*       $data['is_mobile']=1; */
/*     else */
/*       $data['is_mobile']=0; */
/*     break; */
/*   case('IRL')://Ireland */
/*     if(preg_match('/^0?8(2|3|5|6|7|8|9)/',$data['Telecom Number'])) */
/*       $data['is_mobile']=1; */
/*     else */
/*       $data['is_mobile']=0; */
/*     break; */
/*   case('ESP')://Spain */
/*   case('FRA')://France */
/*     if(preg_match('/^0?6/',$data['Telecom Number'])) */
/*     $data['is_mobile']=1; */
/*     else */
/*       $data['is_mobile']=0; */
/*     break; */
/*   } */
  
/*   if($data['is_mobile']==1) */
/*     $data['Telecom Type']='Mobile'; */
/*   else if($data['Telecom Original Type']=='Mobile' and $data['is_mobile']==0) */
/*     $data['Telecom Type']='Unknown'; */
/*   else  */
/*     $data['Telecom Type']=$data['Telecom Original Type']; */
  

   $data['Telecom Number']=$number;
   $data['Telecom Plain Number']=Telecom::plain_number($data);
   

  return $data;

  // print_r($this->data);
 }
 /*Function: plain_number
   Returns the telephone number with out format or international codes
  */
 public static function plain_number($data){
   $number=preg_replace('/[^\d]/','',$data['Telecom Area Code'].$data['Telecom Number']);
   return $number;
 }

 /*Function: formated_number
   Returns the formated  telephone number
  */
 public static function formated_number($data){
   $tmp=($data['Telecom Country Telephone Code']!=''?'+'.$data['Telecom Country Telephone Code'].' ':'').($data['Telecom Area Code']!=''?$data['Telecom Area Code'].' ':'').$get('spaced_number').($data['Telecom Extension']!=''?' '._('ext').' '.$data['Telecom Extension']:'');
   return $tmp;
 }

 /*Function: get_country_id
   Returns the country key of this telephone
  */

 private function get_country_id(){
   if($this->data['Telecom Country Telephone Code']){
     $sql="select * from `Country Dimension` where `Country Telephone Code`=".prepare_mysql($this->data['Telecom Country Telephone Code']);
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       return $row['Country Key'];
     }
   }
   return 244;
 }
 /*Function: get_country_code
   Returns the country code of this telephone
  */

 private function get_country_code(){
   if($this->data['Telecom Country Telephone Code']){
     $sql="select * from `Country Dimension` where `Country Telephone Code`=".prepare_mysql($this->data['Telecom Country Telephone Code']);
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       return $row['Country Code'];
     }
       
   }
   return 'UNK';
 }



}
?>