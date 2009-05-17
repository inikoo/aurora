<?
/*
 File: Telecom.php 

 This file contains the Telecom Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('DB_Table.php');
include_once('Country.php');

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
  Look for similar records and take actions dependiing of the options
*/
function find($raw_data,$options){
    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }

   if(!$raw_data){
      $this->new=false;
      $this->msg=_('Error no telecom data');
      if(preg_match('/exit on errors/',$options))
	exit($this->msg);
      return false;
    }

   if(preg_match('/country code [a-z]{3}/',$options,$match)){
      $country_code=preg_replace('/[^\d]/','',$match[0]);
   }else
     $country_code='UNK';

   if(is_string($raw_data))
     $raw_data=$this->parse_number($raw_data,$country_code);

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
    }
    if(preg_match('/in company \d+/',$options,$match)){
      $subject_key=preg_replace('/[^\d]/','',$match[0]);
      $subject_type='Company';
    }elseif(preg_match('/company/',$options,$match)){
      $subject_type='Company';
    }

    if(!$subject_key){
      $options.=' anonymous';
    }else{
        if($subject_type=='Contact'){
	  $subject=new Contact($subject_key);
	}else{
	  $subject=new Company($subject_key);
	}
    }

    
    print_r($data);

    $sql=sprintf("select T.`Telecom Key`,`Subject Key` from `Telecom Dimension` T left join `Telecom Bridge` TB  on (TB.`Telecom Key`=T.`Telecom Key`) where `Telecom Plain Number`=%s and `Subject Type`=%s  "
		 ,prepare_mysql($data['Telecom Plain Number'])
		 ,prepare_mysql($subject_type)
		 );
    $result=mysql_query($sql);
    $num_results=mysql_num_rows($result);
    
      if($num_results==0){
	$this->found=false;

	if($subject_type=='Company'){
	  // Look if another contact has this telecom
	  $sql=sprintf("select T.`Telecom Key`,`Subject Key` from `Telecom Dimension` T left join `Telecom Bridge` TB  on (TB.`Telecom Key`=T.`Telecom Key`) where `Telecom Plain Number`=%s and `Subject Type`='Contact'  "
		   ,prepare_mysql($data['Telecom Plain Number'])
		       );
	  
	$result=mysql_query($sql);
	$num_results=mysql_num_rows($result);
	
	if($num_results==0){
	  if($create){
	    $this->create($data,$options);
	  }
	  return;

	}else{
	  // we can insert the contact to the comapny or hikat of necesary
	  exit("todo in telecom,hiajacking the contact\n");

	}
      }



       	if($create)
	  $this->create($data,$options);
      }else if($num_results==1){
	$this->found=true;
	$row=mysql_fetch_array($result, MYSQL_ASSOC);
		if($subject_type=='Contact'){
	  $subject=new Contact($row['Subject Key']);
	}else{
	  $subject=new Company($row['Subject Key']);
	}
	$this->get_data('id',$row['Email Key']);
	if(!$subject_key or $row['Subject Key']==$subject_key){
	  if($create and !$update){



	    $this->msg=_('Telecom found in').sprintf(' %s. %s (%d)',$subject_type,$subject->display('name'),$subject->id);
	    $this->error=true;
	  }elseif($create){
	    $this->update($data,$options);
	  }
	  return;
	}else{
	   $this->msg=_('Telecom found in another').sprintf(' %s. %s (%d)',$subject_type,$subject->display('name'),$subject->id);
	
	}

      }else{// Found in more than one contact, 
	while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  if($row['Subject Key']==$in_contact){
	    $this->get_data('id',$row['Telecom Key']);
	    $this->update($data,$options);
	    return $this->id;
	  }
	}
	$this->msg=_('Telephone found in')." $num_results ".ngettext($num_results,'record','records');
	return 0;
	
      }
      
    
    if($subject_type=='Company'){
      // Look if another contact has this telecom
      $sql=sprintf("select T.`Telecom Key`,`Subject Key` from `Telecom Dimension` T left join `Telecom Bridge` TB  on (TB.`Telecom Key`=T.`Telecom Key`) where `Telecom Plain Number`=%s and `Subject Type`='Contact'  "
		   ,prepare_mysql($data['Telecom Plain Number'])
		 );

	$result=mysql_query($sql);
	$num_results=mysql_num_rows($result);

	if($num_results==0){

	  if(preg_match('/create/i',$options)){
	    $this->data=$data;
	    $this->create();
	  }
	  return;

	}else{
	  // we can insert the contact to the comapny or hikat of necesary
	  exit("todo in telecom");

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
   $number=preg_replace('/[^\d]/','',$number);
   $number=_trim($number);

   $data['Telecom Number']=$number;
   

   if($country_code='UNK' and isset($data['Telecom Country Telephone Code'])){
     $country_code=Telecom::get_country_code($data['Telecom Country Telephone Code']);
     
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



   switch($country_code){
    
  case('GBR')://UK

    $data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']);
    $data['Telecom National Access Code']='0';
    if($data['Telecom Area Code']==''){
      $data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']);
      $area_code=Telecom::find_area_code($data['Telecom Number'],'GBR');
      if($area_code!=''){
	$data['Telecom Area Code']=$area_code;
	$data['Telecom Number']=preg_replace("/^".$data['Telecom Area Code']."/",'',$data['Telecom Number']);
      }
    }
    $data['Telecom National Access Code']='0';
    if(preg_match('/^8\d\d/',$data['Telecom Number'])){
      $data['National Only Telecom']='Yes';
      $data['Telecom Country Telephone Code']='';
      $data['Telecom Area Code']=$match[0];
      $data['Telecom Number']=preg_replace('/^'.$data['Telecom Area Code'].'/','',$data['Telecom Number']);
      $data['Telecom Technology Type']='Non-geographic';
    }elseif(preg_match('/^7/',$data['Telecom Number']))
	$data['Telecom Technology Type']='Mobile';
    else
      $data['Telecom Technology Type']='Landline';
    
    break;
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
   } 
  
/*   if($data['is_mobile']==1) */
/*     $data['Telecom Type']='Mobile'; */
/*   else if($data['Telecom Original Type']=='Mobile' and $data['is_mobile']==0) */
/*     $data['Telecom Type']='Unknown'; */
/*   else  */
/*     $data['Telecom Type']=$data['Telecom Original Type']; */
   


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
   $tmp=($data['Telecom Country Telephone Code']!=''?'+'.$data['Telecom Country Telephone Code'].' ':'').($data['Telecom Area Code']!=''?$data['Telecom Area Code'].' ':'')._trim(strrev(chunk_split(strrev($data['Telecom Number']),4," "))).($data['Telecom Extension']!=''?' '._('ext').' '.$data['Telecom Extension']:'');
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




}
?>