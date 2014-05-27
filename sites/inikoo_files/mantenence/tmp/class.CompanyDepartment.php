<?php
/*
 File: CompanyDepartment.php 

 This file contains the Company Department Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Company.php');


class CompanyDepartment extends DB_Table{
  

  var $departments=false;



  function CompanyDepartment($arg1=false,$arg2=false,$arg3=false) {

    $this->table_name='Company Department';
    $this->ignore_fields=array('Company Department Key');

     if(preg_match('/^(new|create)$/i',$arg1) and is_array($arg2)){
       $this->create($arg2);
       return;
     }

     if(preg_match('/find/i',$arg1)){
       $this->find($arg2,$arg3);
       return;
     }
     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return;
     }
     $this->get_data($arg1,$arg2);
  }

  /*
   Method: find
   Find W Department with similar data
  */   
  
  function find($raw_data,$options){
  
    if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }
   
    
    $this->found=false;
    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }
    
    $data=$this->base_data();
    foreach($raw_data as $key=>$val){
      $_key=$key;
      $data[$_key]=$val;
    }
    
    
    //look for areas with the same code in the same Company
    $sql=sprintf("select `Company Department Key` from `Company Department Dimension`  where `Company Department Dimension`.`Company Key`=%d and `Company Department Code`=%s"
		,$data['Company Key']
		 ,prepare_mysql($data['Company Department Code']));
    
    //print $sql;
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->found_key=$row['Company Department Key'];
    }

    //what to do if found
    if($this->found){
      $this->get_data('id',$this->found_key);
    }
      

    if($create){
      if($this->found){
	$this->update($raw_data,$options);
      }else{

	$this->create($data,$options);

      }


    }
  }


    function create ($data,$options=''){

      $this->data=$this->base_data();
      foreach($data as $key=>$value){
	if(array_key_exists($key,$this->data))
	  $this->data[$key]=_trim($value);
      }
      
      if($this->data['Company Department Code']==''){
	$this->msg=('Wrong Company area name');
	$this->new=false;
	$this->error=true;
	return;
      }
    
      if($this->data['Company Department Name']==''){
	$this->data['Company Department Name']=$this->data['Company Department Code'];
      }

      $keys='(';$values='values(';
      foreach($this->data as $key=>$value){

	$keys.="`$key`,";
	$_mode=true;
	if($key=='Company Department Description')
	  $_mode=false;
	$values.=prepare_mysql($value,$_mode).",";
      }
    
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);

    $sql=sprintf("insert into `Company Department Dimension` %s %s",$keys,$values);
    //print "$sql\n";
    // exit;
    if(mysql_query($sql)){
      $this->id= mysql_insert_id();
      $this->get_data('id',$this->id);
     
	    $this->new=true;





    }else{
      exit("Error:$sql\n");
    }

    }

  function get_data($key,$tag){
    
    if($key=='id')
      $sql=sprintf("select * from `Company Department Dimension` where `Company Department Key`=%d",$tag);
    else if($key=='code')
      $sql=sprintf("select  *  from `Company Department Dimension` where `Company Department Code`=%s ",prepare_mysql($tag));
    else
      return;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Company Department Key'];
    }
      



  }
 




  function load($key=''){
    switch($key){
    case('departments'):
      
      break;

    }
      

  }


  function get($key,$data=false){
    switch($key){
    case('num_positions'):
    case('number_positions'):
      if(!$this->positions)
	$this->load('positions');
      return count($this->positions);
      break;
    case('positions'):
      if(!$this->positons)
	$this->load('positions');
      return $this->positions;
      break;
   
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
 

function load_positions(){
$this->positions=array();
$sql=sprintf('Select * from `Company Position Dimension` where `Company Department Key`=%d',$this->id);
$res=mysql_query($sql);
while($row=mysql_fetch_array($res,MYSQL_ASSOC)){
    $this->positions[$row['Company Position Key']]=$row;
}
}

function delete(){
$this->deleted=false;
if($this->data['Company Department Number Employees']>0){
$this->msg=_('Company Department could not be deleted because').' '.gettext($this->data['Company Department Number Employees'],'employee','employees').' '.gettext($this->data['Company Department Number Employees'],'is','are').' '._('associated with it');
return;
}

$this->load_positions();
foreach($this->positions as $position_key=>$position){
    $position=new CompanyPosition($position_key);
    $position->editor=$this->editor;
    $position->delete();
}

$sql=sprintf('delete from `Company Department Dimension` where `Company Department Key`=%d',$this->id);
mysql_query($sql);

$history_data=array(
                    'History Abstract'=>_('Company Department deleted').' ('.$this->data['Company Department Name'].')'
                    ,'History Details'=>_trim(_('Company Department')." ".$this->data['Company Department Name'].' ('.$this->data['Company Department Code'].') '._('has been permenentely') )
                     ,'Action'=>'deleted'
                          );
 $this->add_history($history_data);
$this->deleted=true;

}


    function update_children() {

       
        $sql=sprintf('select count(*) as number from `Company Position Dimension` where `Company Department Key`=%d',$this->id);
        $res=mysql_query($sql);
        $number_positions=0;
        if ($row=mysql_fetch_array($res)) {
            $number_positions=$row['number'];
        }        
        $sql=sprintf('select count(*) as number from `Staff Dimension` where `Company Department Key`=%d',$this->id);
        $res=mysql_query($sql);
        $number_employees=0;
        if ($row=mysql_fetch_array($res)) {
            $number_employees=$row['number'];
        }

        $sql=sprintf('update `Company Department Dimension` set `Company Department Number Positions`=%d,`Company Department Number Employees`=%d where `Company Department Key`=%d'
        ,$number_positions
        ,$number_employees
        ,$this->id
        );
        mysql_query($sql);
        $this->get_data('id',$this->id);
    }
 
  
  
  
  
     function add_position($data) {
        $this->new_area=false;
        //$data['Company Key']=$this->data['Company Key'];
        //$data['Company Area Key']=$this->id;
        $position= new CompanyPosition('find',$data,'create');
        if($position->id){
        $this->new_position_msg=$position->msg;
        
        if ($position->new){
            $this->new_position=true;
         
        }else {
            if ($position->found)
                $this->new_position_msg=_('position Code already in the Company');
        }
        $this->associate_position($position->id);
        }
        
    }

function get_position_keys(){
    $position_keys=array();
    $sql=sprintf("select `Position Key` from `Company Department Position Bridge` where `Department Key`=%d",$this->id);
    //print $sql;
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
        $position_keys[$row['Position Key']]=$row['Position Key'];
    }
    return $position_keys;
}

function associate_position($position_key){
    if(!array_key_exists($position_key,$this->get_position_keys())){
        $sql=sprintf("insert into `Company Department Position Bridge` values (%d,%d) ",$this->id,$position_key);
        mysql_query($sql);
        
       
        
        
    }

}
  
  
  
  
  
  
  
  
  
  
     
}

?>