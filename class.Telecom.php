<?php
/*
 File: Telecom.php 

 This file contains the Telecom Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Country.php');

class Telecom extends DB_Table {


     /*
       Constructor: Telecom
     
       Initializes the class, Search/Load or Create for the Telephone/Fax data set 

     */
  function Telecom($arg1=false,$arg2=false) {
    
    $this->table_name='Telecom';
    $this->ignore_fields=array('Telecom Key');
    
    
    if(!$arg1 and !$arg2){
      $this->error=true;
      $this->msg='No data provided';
      return;
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    if ($arg1=='new'){
      $this->create($arg2);
      return;
    }
    if(preg_match('/find/i',$arg1)){
      $this->find($arg2,$arg1);
      return;
    }
    $this->get_data($arg1,$arg2);
  }


  function get_data($tipo,$id){

    if($tipo=='id'){
     
      if($id==0){
	$this->msg="error telecom key can not be zero T:$tipo ID:$id\n";
	return;
      }
      $sql=sprintf("select * from `Telecom Dimension` where  `Telecom Key`=%d",$id);
      $result=mysql_query($sql);
      
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Telecom Key'];
      }
    
    }
}
  /*
Function:display
   */
  function display($tipo=''){


   switch($tipo){
   case('plain'):
     return $this->data['Telecom Plain Number'];
   case('formated'):
      return $this->formated_number($this->data);
      break;
   case('xhtml'):
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
  Function: find
  Given a set of telephone number components try to find it on the database updating properties, if not found creates a new record

   Parmaters:
   $raw_data - associative array with the telephone number data (DB fields as keys)
   $options - string 
   
   auto - the method will update/create the telephone number with out asking for instructions 
   create|update - methos will create or update the telephone number with the data provided
*/
function find($raw_data,$options){
  // print_r($raw_data);

  
  if(isset($raw_data['editor']) and is_array($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){

	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }



   $this->found=false;
   $this->found_in=false;
   $this->found_out=false;
   
   $in_contacts=array();
   $mode='Contact';
   $parent='Contact';
   $create=false;
   if(preg_match('/create|update/i',$options)){
     $create=true;
   }
   $auto=false;
    if(preg_match('/auto/i',$options)){
      $auto=true;
    }
    
   if(!$raw_data){
      $this->error=true;
      $this->msg=_('Error no telecom data');
      if(preg_match('/exit on errors/',$options))
	exit($this->msg);
      return false;
    }

  
   

   
   //print "OPTIONS $options\n";

   if(preg_match('/country code [a-z]{3}/i',$options,$match)){
     $country_code=preg_replace('/country code /','',$match[0]);
   }else
     $country_code='UNK';
   
   
   
   $raw_number=false;
   if(isset($raw_data['Telecom Raw Number'])){
     $raw_number=$raw_data['Telecom Raw Number'];
   }
   if(is_string($raw_data)){
     $raw_number=$raw_data;
   }




   if($raw_number!=''){
     $_data=preg_replace('/[^\d]/','',$raw_number);

     if(strlen($_data)<3){

       $this->error=true;
       $this->msg=_('Error, invalid telecom data');
       if(preg_match('/exit on errors/',$options))
	 exit($this->msg);
       return false;
       
     }
     $raw_data=$this->parse_number($raw_number,$country_code);
   
   }else{
     $this->error=true;
       $this->msg=_('Error, no telecom data');
       if(preg_match('/exit on errors/',$options))
	 exit($this->msg);
       return false;
     
   }

   if($raw_data['Telecom Number']==''){
     $this->error=true;
     $this->msg=_('Error no telecom number data');
      if(preg_match('/exit on errors/',$options))
	exit($this->msg);
      return false;
   }
     

   

   $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=$value;
    }

    $data=$this->clean_data($data);
    
   

    if($data['Telecom Number']==''){
      $this->msg=_('Wrong telephone number');
      return false;
    }


    $data['Telecom Plain Number']=Telecom::plain_number($data);
   
    $subject=false;
    $subject_key=0;
    $subject_type='Contact';

      if(preg_match('/in contact \d+/',$options,$match)){
      $subject_key=preg_replace('/[^\d]/','',$match[0]);
      $subject_type='Contact';

      $mode='Contact in';
      $in_contact=array($subject_key);


    }
    if(preg_match('/in company \d+/',$options,$match)){
      $subject_key=preg_replace('/[^\d]/','',$match[0]);
      $subject_type='Company';
      $company=new Company($subject_key);
      $in_contact=$company->get_contact_keys();
      $mode='Company in';

    }elseif(preg_match('/company/',$options,$match)){
      $subject_type='Company';
      $mode='Company';
    }

    if($mode=='Contact')
      $options.=' anonymous';
    
    $intl_code_max_score=10;
    $ext_code_max_score=10;
    $tel_max_score=80;
    $exact_match_bonus=10;
    $this->found=false;
    
    $this->found_number=0;
    $this->found_ext=0;
    $this->found_intl_code=0;

    if($data['Telecom Area Code'].$data['Telecom Number']!=''){
     
      $len_tel=strlen($data['Telecom Area Code'].$data['Telecom Number']);
      
    $sql=sprintf("select `Telecom Extension`,`Telecom Country Telephone Code`,`Telecom Extension`,damlevlim256(CONCAT(`Telecom Area Code`,`Telecom Number`),%s,4) as dist1,T.`Telecom Key`,`Subject Key` from `Telecom Dimension` T left join `Telecom Bridge` TB  on (TB.`Telecom Key`=T.`Telecom Key`)  where  `Subject Type`='Contact' and  damlevlim256(CONCAT(`Telecom Area Code`,`Telecom Number`),%s,4)<4  order by dist1  limit 100 "
		 ,prepare_mysql($data['Telecom Area Code'].$data['Telecom Number'])
		 ,prepare_mysql($data['Telecom Area Code'].$data['Telecom Number'])
		 );
    
    //$sql=sprintf("select * from `Telecom Dimension`  limit10 ",prepare_mysql($data['Telecom Area Code'].$data['Telecom Number']));
    $result=mysql_query($sql);
    // print $sql."<br><br>";
    // echo mysql_errno() . ": " . mysql_error() . "\n";
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $contact_key=$row['Subject Key'];
      if($row['dist1']>3)
	break;
      $dist=$row['dist1']/$len_tel;
      $score=$tel_max_score*exp(-100*$dist*$dist);
      $_score=$score;

      if($row['dist1']==0){
	$score+=$exact_match_bonus;
	$this->found_number=1;
      }
     
  
      if($row['Telecom Country Telephone Code']==$data['Telecom Country Telephone Code']){
	if($data['Telecom Country Telephone Code']!=''){
	  $this->found_intl_code=1;
	  $score+= $intl_code_max_score*$_score;
	}
      }else{
	if($data['Telecom Country Telephone Code']!='' and $row['Telecom Country Telephone Code']!='')
	  $this->found_intl_code=-2;
      }
      
      if($row['Telecom Extension']==$data['Telecom Extension']){
	if($data['Telecom Extension']!=''){
	  $this->found_ext=2;
	  $score+= $ext_max_score*$_score;
	}
      }else{
	if($data['Telecom Extension']!='' and $row['Telecom Extension']!='')
	  $this->found_ext=-2;
      }
      

       if(isset($this->candidate[$contact_key]))
	$this->candidate[$contact_key]+=$score;
      else
	$this->candidate[$contact_key]=$score;





       if(!$this->found and(  $this->found_number and ($this->found_ext>=0 and $this->found_intl_code>=0) ) ){
	 $this->found=true;
	 $this->get_data('id',$row['Telecom Key']);
	 
	 //print "----> ".$row['Telecom Key']."\n";
	 if($mode=='Contact in' or $mode=='Company in'){
	if(in_array($row['Subject Key'],$in_contact)){
	  
	  $this->found_in=true;
	  $this->found_out=false;
	}else{
	  
	  $this->found_in=false;
	  $this->found_out=true;
	}
	 }

       }
       
       
    }
    }

    

   
  if($create){
    if($this->found){

	$this->update($data,$options);
    }else{
	// not found
	if($auto){
	  usort($this->candidate);
	  foreach($this->candidate as $key =>$val){
	    if($val>=90){
	      $this->found=true;
	      if(in_array($key,$in_contact))
		$this->found_in=true;
	      else
		$this->found_out=true;

	      $this->get_data('id',$key);
	      $this->update($data,$options);
	      return;
	    }
	  }

	}

	$this->create($data,$options);

      }

    }




}



/*
Function: create
Insert new number to the database


 */
protected function create($data,$optios=''){
   
 if(!$data){
    $this->new=false;
    $this->error=true;
    $this->msg.=" Error no telecom data";
    if(preg_match('/exit on errors/',$options))
      exit($this->msg);
    return false;
  }
  


       
  $this->data=$this->base_data();
  foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data))
      $this->data[$key]=$value;
  }
   
   if($this->data['Telecom Number']==''){
     $this->new=false;
     $this->error=true;
     $this->msg=_('Wrong telephone number');
   }

   if($this->data['Telecom Technology Type']==''){
     $this->data['Telecom Technology Type']='Unknown';
   }

   // print $sql;
   $sql=sprintf("insert into `Telecom Dimension` (`Telecom Technology Type`,`Telecom Country Telephone Code`,`Telecom National Access Code`,`Telecom Area Code`,`Telecom Number`,`Telecom Extension`,`Telecom Plain Number`) values (%s,%s,%s,%s,%s,%s,%s)",
		prepare_mysql($this->data['Telecom Technology Type']),
		prepare_mysql($this->data['Telecom Country Telephone Code']),
		prepare_mysql($this->data['Telecom National Access Code'],false),
		prepare_mysql($this->data['Telecom Area Code']),
		prepare_mysql($this->data['Telecom Number']),
		prepare_mysql($this->data['Telecom Extension'],false),
		prepare_mysql($this->data['Telecom Plain Number'])
		);


   if(mysql_query($sql)){
     $this->id = mysql_insert_id();
     $this->get_data('id',$this->id);
     $this->new=true;
     
     // Some times some post production should be made. 
     $this->postproduction();


     return true;
   }else{
     $this->error=true;
   
     $this->msg="Error can not create telecom\n";
     $this->new=false;
   }
   
 }

 /*Function: clean_data
   Parse the number in its componets
   
   Parameter:
   $raw_data array with telecom fields
   
   Returns:
   $data  array with cleaned telecom field
   
  */
 function clean_data($raw_data){
   
   $data=Telecom::base_data();
   foreach($raw_data as $key=>$val){
     if(array_key_exists($key,$data)){
       
       $data[$key]=$val;
     }
   }

   return $data;
   
 }




 /*Function: parse_number
   Parse the number in its componets

   Parameters:
   $data -  string with the number 
   
   
  */
 function parse_number($number,$country_code='UNK'){
   //    print "parsing number $number $country_code\n";

   $data=array('Telecom Technology Type'=>'Unknown'
	       ,'Telecom Country Telephone Code'=>''
	       ,'Telecom National Access Code'=>''
	       ,'Telecom Area Code'=>''
	       ,'Telecom Number'=>''
	       ,'Telecom Extension'=>''
	       ,'National Only Telecom'=>'No'
	       ,'Telecom Plain Number'=>''
	       );

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
   
   }
   // +44 1142729165
   elseif(preg_match('/^\+\d+ \d/',$number)){
     preg_match('/^\+\d+\s*/',$number,$match);
     $number=preg_replace('/^\+\d+\s*/','',$match[0]);
     $data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);

     
   }
   $number=preg_replace('/[^\d]/','',$number);
   $number=_trim($number);

   $data['Telecom Number']=$number;
   

   if($country_code=='UNK' and isset($data['Telecom Country Telephone Code'])){
     $country_code=Telecom::get_country_code($data['Telecom Country Telephone Code']);
     
   }

 /*   print "$country_code\n"; */
/*    print_r($data); */

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

   //  print "parsing number $number $country_code\n";

   switch($country_code){
    
  case('GBR')://UK
    // print "---------------uk\n";
    //    print_r($data);
    $data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']);

    if(preg_match('/^8(00\d{6,7}|08\d7|20\d{3}|45\d{3})/',$data['Telecom Number'],$match)){
      $data['National Only Telecom']='Yes';
      $data['Telecom Country Telephone Code']='';

      preg_match('/^\d{3}/',$data['Telecom Number'],$match);
      $data['Telecom Area Code']=$match[0];
      $data['Telecom Number']=preg_replace('/^'.$data['Telecom Area Code'].'/','',$data['Telecom Number']);
      $data['Telecom Technology Type']='Non-geographic';
    }else{


      
      $data['Telecom Country Telephone Code']='44';
      $data['Telecom Number']=preg_replace('/^44/','',$data['Telecom Number']);
      
      
      $data['Telecom National Access Code']='0';
      if($data['Telecom Area Code']==''){
	$data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']);
	$area_code=Telecom::find_area_code($data['Telecom Number'],'GBR');
	if($area_code!=''){
	  $data['Telecom Area Code']=$area_code;
	  $data['Telecom Number']=preg_replace("/^".$data['Telecom Area Code']."/",'',$data['Telecom Number']);
	}
      }
      
      
      if(preg_match('/^7/',$data['Telecom Area Code'].$data['Telecom Number']))
	$data['Telecom Technology Type']='Mobile';
      else
	$data['Telecom Technology Type']='Landline';
      
    
    }

    break;
  case('IRL')://Ireland
    if(preg_match('/^0?8(2|3|5|6|7|8|9)/',$data['Telecom Number']))
      $data['is_mobile']=1;
    else
      $data['is_mobile']=0;
    $data['Telecom Country Telephone Code']='353';
     $data['Telecom Number']=preg_replace('/^353/','',$data['Telecom Number']);

    break;
/*   case('ESP')://Spain */
/*   case('FRA')://France */
/*     if(preg_match('/^0?6/',$data['Telecom Number'])) */
/*     $data['is_mobile']=1; */
/*     else */
/*       $data['is_mobile']=0; */
/*     break; */
   } 
  
/*   if($data['is_mobile']==1) */
/*     $data['Telecom Type']='Mobile'; */
/*   else if($data['Telecom Original Type']=='Mobile' and $data['is_mobile']==0) */
/*     $data['Telecom Type']='Unknown'; */
/*   else  */
/*     $data['Telecom Type']=$data['Telecom Original Type']; */
   


   $data['Telecom Plain Number']=Telecom::plain_number($data);

   // print_r($data);
  return $data;

  // print_r($this->data);
 }
 /*Function: plain_number
   Returns the telephone number with out format or international codes
  */
 public static function plain_number($data){
   $number=preg_replace('/[^\d]/','',$data['Telecom Country Telephone Code'].$data['Telecom National Access Code'].$data['Telecom Area Code'].$data['Telecom Number']);
   $ext=preg_replace('/[^\d]/','',$data['Telecom Extension']);
   if($ext!='')
     $number.='e'.$ext;
   return $number;
 }


 function is_mobile(){
   if($this->data['Telecom Technology Type']=='Mobile')
     return true;
   else
     return false;

 }

 /*Function: formated_number
   Returns the formated  telephone number
  */
 public static function formated_number($data){
   switch(strlen($data['Telecom Number'])){
    case 6:
    $the_number=preg_replace('/^\d{2}/','$0 ',$data['Telecom Number']);
    $the_number=preg_replace('/^\d{2} \d{2}/','$0 ',$the_number);
    
     break; 
   case 8:
    $the_number=preg_replace('/^\d{2}/','$0 ',$data['Telecom Number']);
    $the_number=preg_replace('/^\d{2} \d{2}/','$0 ',$the_number);
    
     break;
   case 9:
     $the_number=preg_replace('/^\d{2}/','$0 ',$data['Telecom Number']);
     $the_number=preg_replace('/^\d{2} \d{3}/','$0 ',$the_number);
     break;
   default:
     $the_number=strrev(chunk_split(strrev($data['Telecom Number']),4," "));
   }
   $the_number=_trim($the_number)."";

   if($data['Telecom National Access Code']!='')
     $nac=sprintf("(%d) ",$data['Telecom National Access Code']);
   else
     $nac='';
       
   $tmp=($data['Telecom Country Telephone Code']!=''?'+'.$data['Telecom Country Telephone Code'].' ':'').$nac.($data['Telecom Area Code']!=''?$data['Telecom Area Code'].' ':'').$the_number.($data['Telecom Extension']!=''?' '._('ext').' '.$data['Telecom Extension']:'');
   
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

   Parameter:
   $tel_code - Intertnational telephone code
   
   Return:
   3 letter country code

  */

 public static  function get_country_code($tel_code=''){
   if($tel_code){
     $sql="select * from `Country Dimension` where `Country Telephone Code`=".prepare_mysql($tel_code);
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       return $row['Country Code'];
     }
       
   }
   return 'UNK';
 }
 /*
   Function: find_area_code
   Find for the telephone access code in a number
  */
 
 function find_area_code($number,$country_code='UNK'){
   // print "$number,$country_code\n  ";
   
   if(strlen($number>5)){
     for($i=5;$i>1;$i--){
       $proposed_code=substr($number, 0,$i); 
       
       $sql=sprintf("select `Telephone Local Code Key` from `Telephone Local Code` where LENGTH(`Telephone Local Code`)=%d and `Telephone Local Code`=%s and `Telephone Local Code Country Code`=%s ",$i,prepare_mysql($proposed_code),prepare_mysql($country_code));
       //  print "$sql\n";
       $result=mysql_query($sql);
       $num_results=mysql_num_rows($result);
       if($num_results>0)
	 return $proposed_code;
     }
   }
   return '';
}
 /*
   Function:postproduction
  */

 private function postproduction(){
   $country_code=$this->get_country_code($this->data['Telecom Country Telephone Code']);
     switch($country_code){
    
     case('GBR')://UK

       // By defaul when creating the tlecom Telecom Area Code is set to null if '' fix it for mobile 
       if($this->data['Telecom Technology Type']=='Mobile' and $this->data['Telecom Area Code']==''){
	 $sql=sprintf("update `Telecom Dimension` set `Telecom Area Code`='' where `Telecom Key`=%d",$this->id);
	 mysql_query($sql);
       }
       break;
     }
   
 }


  function set_scope($raw_scope='',$scope_key=0){
    $scope='Unknown';
    $raw_scope=_trim($raw_scope);
    if(preg_match('/^customers?$/i',$raw_scope)){
      $scope='Customer';
    }else if(preg_match('/^(contacts?|person)$/i',$raw_scope)){
      $scope='Contact';
    }else if(preg_match('/^(company?|bussiness)$/i',$raw_scope)){
      $scope='Company';
    }else if(preg_match('/^(supplier)$/i',$raw_scope)){
      $scope='Supplier';
    }else if(preg_match('/^(staff)$/i',$raw_scope)){
      $scope='Staff';
    }
    
    $this->scope=$scope;
    $this->scope_key=$scope_key;
    $this->load_metadata();
    
  }


function load_metadata(){
  $this->data['Type']=array();
  $where_scope=sprintf(' and `Subject Type`=%s',prepare_mysql($this->scope));
  
  $where_scope_key='';
    if($this->scope_key)
      $where_scope_key=sprintf(' and `Subject Key`=%d',$this->scope_key);
    
    $sql=sprintf("select * from `Telecom Bridge` where `Telecom Key`=%d %s  %s "
		 ,$this->id
		 ,$where_scope
		 ,$where_scope_key
		 );
    $res=mysql_query($sql);


    $this->data['Telecom Type']=array();
    $this->associated_with_scope=false;
    while($row=mysql_fetch_array($res)){
        $this->associated_with_scope=true;
      $this->data['Telecom Type'][$row['Telecom Type']]=$row['Telecom Type'];
      $this->data['Telecom Is Main'][$row['Telecom Type']]=$row['Is Main'];
      $this->data['Telecom Is Active'][$row['Telecom Type']]=$row['Is Active'];
    }
    
    
  }


/*
  function: is_associated
 */

function is_associated($scope,$scope_key,$args='only valid'){
 $extra_args='';
    if(preg_match('/only active|active only/i',$args))
      $extra_args=" and `Is Active`='Yes'";
    if(preg_match('/only main|main only/i',$args))
      $extra_args=" and `Is Main`='Yes'";
    if(preg_match('/only not? active/i',$args))
      $extra_args=" and `Is Active`='No'";
    if(preg_match('/only not? main/i',$args))
      $extra_args=" and `Is Main`='No'";

  $sql=sprintf("select `Telecom Key` from `Telecom Bridge`  where `Subject Type`=%s and `Subject Key`=%d  %s  "
	       ,prepare_mysql($scope)
	       ,$scope_key
	       ,$extra_args
	       );
 
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    return true;
  }
  return false;

}



/*Method: update
  Switcher calling the apropiate update method
  Parameters:
  $data - associated array with Email Dimension fields
    */
public function update($data,$options=''){
   $old_plain=$this->display('plain');
   $old_xhtml=$this->display('xhtml');
  if(isset($data['editor'])){
    foreach($data['editor'] as $key=>$value){
      if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
    }
  }

  $base_data=$this->base_data();
  foreach($data as $key=>$value){
    if($value!=$this->data[$key]){
      $this->update_field_switcher($key,$value,$options);
	}
    
  }

    
  if(!$this->updated)
    $this->msg.=' '._('Nothing to be updated')."\n";
  else{
    $new_plain=$this->display('plain');
    $new_xhtml=$this->display('xhtml');
    if($old_plain!=$new_plain)
      $this->update_field_switcher('Telecom Plain Number',$new_plain,$options);
    if($new_xhtml!=$old_xhtml){


        $sql=sprintf("select `Contact Key` as `Subject Key`  from `Contact Dimension` where `Contact Main Telephone Key`=%d;",$this->id);
        $res=mysql_query($sql);
        while($row=mysql_fetch_array($res)){
            $contact=new Contact($row['Subject Key']);
            $contact->update_telephone($this->id); 
        }
        mysql_free_result($res);
        
        $sql=sprintf("select `Contact Key` as `Subject Key`  from `Contact Dimension` where `Contact Main FAX Key`=%d;",$this->id);
        $res=mysql_query($sql);
        while($row=mysql_fetch_array($res)){
            $contact=new Contact($row['Subject Key']);
            $contact->update_fax($this->id); 
        }
        mysql_free_result($res);
        
        $sql=sprintf("select `Contact Key` as `Subject Key`  from `Contact Dimension` where `Contact Main Mobile Key`=%d;",$this->id);
        $res=mysql_query($sql);
        while($row=mysql_fetch_array($res)){
            $contact=new Contact($row['Subject Key']);
            $contact->update_mobile($this->id); 
        }
        mysql_free_result($res);
        
               
 



      
    } 
      

  }

}


function update_number($value,$country_code='UNK'){
  $data=$this->parse_number($value,$country_code);
  $this->update($data);
}

function update_field_switcher($field,$value,$options=''){
  if($field=='Telecom Plain Number')
    $options.=' no history';
  $this->update_field($field,$value,$options);
  
 

}



}
?>