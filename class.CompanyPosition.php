<?php
/*
 File: CompanyPosition.php 

 This file contains the Company Position Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');



class CompanyPosition extends DB_Table{
  

  var $locations=false;



  function CompanyPosition($arg1=false,$arg2=false,$arg3=false) {

    $this->table_name='Company Position';
    $this->ignore_fields=array('Company Position Key');

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
   Find W Area with similar data
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
    
    
    //look for areas with the same code in the same company
    $sql=sprintf("select `Company Position Key` from `Company Position Dimension` where  `Company Position Code`=%s"
		 ,prepare_mysql($data['Company Position Code']));
    
    // print $sql;
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->found_key=$row['Company Position Key'];
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
      
      if($this->data['Company Position Code']==''){
	$this->msg=('Wrong company area name');
	$this->new=false;
	$this->error=true;
	return;
      }
    
      if($this->data['Company Position Title']==''){
	$this->data['Company Position Title']=$this->data['Company Position Code'];
      }

      $keys='(';$values='values(';
      foreach($this->data as $key=>$value){

	$keys.="`$key`,";
	$_mode=true;
	if($key=='Company Position Description')
	  $_mode=false;
	$values.=prepare_mysql($value,$_mode).",";
      }
    
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);

    $sql=sprintf("insert into `Company Position Dimension` %s %s",$keys,$values);
    //print "$sql\n";
    // exit;
    if(mysql_query($sql)){
      $this->id= mysql_insert_id();
      $this->new=true;
      $this->get_data('id',$this->id);
      
    }else{
      exit($sql);
    }

    }

  function get_data($key,$tag){
    
    if($key=='id')
      $sql=sprintf("select * from `Company Position Dimension` where `Company Position Key`=%d",$tag);
    else if($key=='code')
      $sql=sprintf("select  *  from `Company Position Dimension` where `Company Position Code`=%s ",prepare_mysql($tag));
    else
      return;

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Company Position Key'];
    }
      



  }
 


  function xupdate($data){
    foreach($data as $key =>$value)
      switch($key){
      case('code'):
	$name=_trim($value);
	
	if($name==''){
	  $this->msg=_('Wrong company area code');
	  $this->update_ok=false;
	  return;
	}

	if($name==$this->data['Warehose Area Code']){
	  $this->msg=_('Nothing to change');
	  $this->update_ok=false;
	  return;
	}

	$WA=new CompanyPosition('code',$value);
	if($WA->id){
	  $this->msg=_('Another ware house has the same name');
	  $this->update_ok=false;
	  return;
	}
	$this->data['Wareahouse Area Code']=$name;
	$this->msg=_('Company Position name changed');
	$this->update_ok=true;
	break;
      }
    
    
  }


  function load($key=''){
    switch($key){
    case('locations'):
      
      break;

    }
      

  }


  function get($key,$data=false){
    switch($key){
    case('num_locations'):
    case('number_locations'):
      if(!$this->areas)
	$this->load('areas');
      return count($this->areas);
      break;
    case('locations'):
      if(!$this->locations)
	$this->load('locations');
      return $this->locations;
      break;
    case('area'):
      if(!$this->locations)
	$this->load('locations');
      if(isset($this->locations[$data['id']]))
	return $this->locations[$data['id']];
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
 


function add_staff($data) {
    $this->new_position=false;


    $staff= new Staff('find',$data,'create');
    if ($staff->id) {
        $this->new_employee_msg=$staff->msg;

        if ($staff->new) {
            $this->new_employee=true;

        } else {
            if ($staff->found)
                $this->new_employee_msg=_('staff Code already in the Company');
        }
        $this->associate_staff($staff->id);
        return $staff;
    }

}
  
  function associate_staff($staff_key){
    if(!array_key_exists($staff_key,$this->get_staff_keys())){
        $sql=sprintf("insert into `Company Position Staff Bridge` values (%d,%d) ",$this->id,$staff_key);
        mysql_query($sql);
        
        
	$staff=new Staff($staff_key);
	
	$note=_('Staff associated with position');
	$details=_('Company Staff')." ".$staff->data['Staff Name']." "._('associated with position')." (".$this->data['Company Position Code'].") ".$this->data['Company Position Title'];
	
	
	$history_data=array(
			    'History Abstract'=>$note
			    ,'History Details'=>$details
			    ,'Action'=>'associated'
			    ,'Preposition'=>'to'
				    ,'Direct Object'=>'Staff'
			    ,'Direct Object Key'=>$staff_key
			    ,'Indirect Object'=>'Position'
				    ,'Indirect Object Key'=>$this->id
			    
			    );
	$this->add_history($history_data);
        
        
    }

}
     
function get_staff_keys(){
    $staff_keys=array();
    $sql=sprintf("select `Staff Key` from `Company Position Staff Bridge` where `Position Key`=%d",$this->id);
    //print $sql;
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
        $staff_keys[$row['Staff Key']]=$row['Staff Key'];
    }
    return $staff_keys;
}

function delete(){

$sql=sprintf('delete from `Company Position Dimension` where `Company Position Key`=%d',$this->id);
mysql_query($sql);

$history_data=array(
                    'History Abstract'=>_('Company Position deleted').' ('.$this->data['Company Position Title'].')','subject'=>_(_('Position'))
                    ,'History Details'=>_trim(_('Company Position')." ".$this->data['Company Position Title'].' ('.$this->data['Company Position Code'].') '._('has been permanently') )
		    ,'Action'=>'deleted'
		    );
 $this->add_history($history_data);
$this->deleted=true;

}


}

?>
