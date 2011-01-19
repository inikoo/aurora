<?php
/*
 File: Staff.php 

 This file contains the Staff Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

  //require_once 'class.Name.php';
require_once 'class.Email.php';
require_once 'class.User.php';

class Staff extends DB_Table{

 




  function __construct($arg1=false,$arg2=false,$arg3=false) {

     $this->table_name='Staff';
     $this->ignore_fields=array('Staff Key');

  if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/^find/i',$arg1)){
     
       $this->find($arg2,$arg3);
       return;
     }   

     if(preg_match('/create|new/i',$arg1)){
     
       $this->find($arg2,'create');
       return;
     }       
     $this->get_data($arg1,$arg2);
   
     

  }

  






  function get_data($key,$id){
    
    if($key=='alias')
      $sql=sprintf("select * from `Staff Dimension` where `Staff Alias`=%s",prepare_mysql($id));
    elseif($key=='id')
            $sql=sprintf("select * from  `Staff Dimension`     where `Staff Key`=%d",$id);
    else
      return;

   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Staff Key'];
    

  }

  function get($key){
    if(!$this->id)
      return;
     if(array_key_exists($key,$this->data))
      return $this->data[$key];
     switch($key){
     case('Formated ID'):
     case("ID"):
        return $this->get_formated_id();
     case('First Name'):
       if(!is_object($this->contact))
	 $this->contact=new Contact($this->data['Staff Contact Key']);
       if($this->contact->id)
	 return $this->contact->data['Contact First Name'];
       else
	 return '';
       break;
     case('Surname'):
       if(!is_object($this->contact))
	 $this->contact=new Contact($this->data['Staff Contact Key']);
       if($this->contact->id)
	 return $this->contact->data['Contact Surname'];
       else
	 return '';
       break;
     case('Email'):
       if(!is_object($this->contact))
	 $this->contact=new Contact($this->data['Staff Contact Key']);
       if($this->contact->id)
	 return strip_tags($this->contact->data['Contact Main XHTML Email']);
       else
	 return '';
       break;


     }

    
  }

   function get_formated_id(){
     global $myconf;
     
     $sql="select count(*) as num from `Staff Dimension`";
     $res=mysql_query($sql);
     $min_number_zeros=4;
     if($row=mysql_fetch_array($res)){
       if(strlen($row['num'])-1>$min_number_zeros)
	 $min_number_zeros=strlen($row['num'])-01;
     }
     if(!is_numeric($min_number_zeros))
       $min_number_zeros=4;

     return sprintf("%s%0".$min_number_zeros."d",$myconf['staff_id_prefix'], $this->data['Staff ID']);

   }



   function find($raw_data,$options){

     

     if(isset($raw_data['editor'])){
       foreach($raw_data['editor'] as $key=>$value){
	 
	 if(array_key_exists($key,$this->editor))
	 $this->editor[$key]=$value;
	 
       }
     }
     
     
     $create='';
     $update='';
     if(preg_match('/create/i',$options)){
       $create='create';
     }
     if(preg_match('/update/i',$options)){
       $update='update';
     }


     $data=$this->base_data();
    foreach($raw_data as $key=>$value) {
      if (array_key_exists($key,$data)) {
	$data[$key]=_trim($value);
      }
    } 

     
    $sql=sprintf("select `Staff Key` from `Staff Dimension` where `Staff Alias`=%s",prepare_mysql($data['Staff Alias']));
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->found_key=$row['Staff Key'];
      $this->get_data('id',$this->found_key);
    }
    

     if($create and !$this->found){
       
        $sql="select `Corporation Company Key` from `Corporation Dimension`";
      $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
    $company_key=$row['Corporation Company Key'];
$company=new Company($company_key);
    }else{
    exit("Error no corporation\n");
    }
    
     $data=array(
                          'Staff Address Line 1'=>'',
                          'Staff Address Town'=>'',
                          'Staff Address Line 2'=>'',
                          'Staff Address Line 3'=>'',
                          'Staff Address Postal Code'=>'',
                          'Staff Address Country Code'=>'',
                          'Staff Address Country Name'=>$company->data['Company Main Country'],
                          'Staff Address Country First Division'=>'',
                          'Staff Address Country Second Division'=>''
                      );
       
       foreach($raw_data as $key=>$value){
       $data[$key]=$value;
       }
       
       
	$this->create($data);
	
     }



   }


   function create($data){
     $sql="select `Corporation Company Key` from `Corporation Dimension`";
      $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
    $company_key=$row['Corporation Company Key'];
$company=new Company($company_key);
    }else{
    exit("Error no corporation\n");
    }
    
    
    
    
   $child=new Contact ('find in staff create update',$data);

	if($child->error){
	  $this->error=true;
	  $this->error=$child->error;
	  return;
	}
	
	$company->create_contact_bridge($child->id);
	$data['Staff Contact Key']=$child->id;


     $contact=new Contact($data['Staff Contact Key']);
     $data['Staff Name']=$contact->display('name');
     


    $this->data=$this->base_data();
    foreach($data as $key=>$value){
      if(array_key_exists($key,$this->data)){
	$this->data[$key]=_trim($value);
      }
    }

  
   
   


    $keys='';
    $values='';
    foreach($this->data as $key=>$value){
      $keys.=",`".$key."`";
      $values.=','.prepare_mysql($value,false);
    }
    $values=preg_replace('/^,/','',$values);
    $keys=preg_replace('/^,/','',$keys);

    $sql="insert into `Staff Dimension` ($keys) values ($values)";
    //print $sql;

    if(mysql_query($sql)){

      $this->id=mysql_insert_id();
      $this->get_data('id',$this->id);
      
      
      
      

      if(!$this->data['Staff ID']){
	$sql=sprintf("update `Staff Dimension` set `Staff ID`=%d where `Staff Key`=%d",$this->id,$this->id);
	mysql_query($sql);
      }
	
     
       $history_data=array(
			  'History Abstract'=>_('Staff Created')
			  ,'History Details'=>_trim(_('New staff')." \"".$this->data['Staff Name']."\"  "._('added'))
			  ,'Action'=>'created'
			  );
      $this->add_history($history_data);
      $this->new=true;

      
  


      
    }else{
      // print "Error can not create staff $sql\n";
    }




   }

function create_user() {
    $password=generatePassword(8,10);
    $user_data=array(
                   'User Handle'=>$this->data['Staff Alias'],
                   'User Alias'=>$this->data['Staff Name'],

                   'User Password'=>hash('sha256',$password),
                   'User Active'=>'Yes',
                   'User Type'=>'Staff',

                   'User Parent Key'=>$this->id,

               );
     
    $user= new User('find',$user_data,'create');
  
   return $user;

}

    function update_name($value) {
    
      if ($value=='') {
            $this->error=true;
            $this->msg=_('Invalid Name');
            return;
        }
  
        $contact=new Contact($this->data['Staff Contact Key']);
        $contact->editor=$this->editor;
        $contact->update(array('Contact Name'=>$value));


        if ($contact->updated) {

            $this->updated=true;
            $this->new_value=$contact->new_value;
        }

    }

   function update_field_switcher($field,$value,$options='') {
        if (is_string($value))
            $value=_trim($value);

        switch ($field) {
        case('Staff Name'):
             case('name'):
            $this->update_name($value);
            break;

        default:
            $base_data=$this->base_data();
            if (array_key_exists($field,$base_data)) {
                $this->update_field($field,$value,$options);
            }
        }
    }


function get_name(){
    return $this->data['Staff Name'];
}

}


?>
