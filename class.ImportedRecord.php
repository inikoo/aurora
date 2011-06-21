<?php
/*
 
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2011, Inikoo 
 

*/
include_once('class.DB_Table.php');


class ImportedRecord extends DB_Table{

  
  
  function ImportedRecord($a1,$a2=false,$a3=false) {

    $this->table_name='Imported Record';
    $this->ignore_fields=array('Imported Record Key');

    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }elseif($a1=='find'){
      $this->find($a2,$a3);
      
    }else
       $this->get_data($a1,$a2);
  }


  function get_data($key,$tag){
    
    if($key=='id'){
    //  $sql=sprintf("select `Imported Record Key`,`Imported Record Creation Date`,`Imported Record Start Date`,`Imported Record Finish Date`,`Imported Record Scope`,`Imported Record Scope Key`,`Original Records`,`Ignored Records`,`Imported Records`,`Error Records`,`Scope List Key` from `Imported Record Dimension` where `Imported Record Key`=%d",$tag);
      $sql=sprintf("select * from `Imported Record Dimension` where `Imported Record Key`=%d",$tag);
   
   }
     
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Imported Record Key'];
    }
      



  }
 
  function find($raw_data,$options){
  if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
      }
    }
    
    $this->found=false;
    $this->found_key=false;

    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }

    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=_trim($value);
    }
    

    //    print_r($raw_data);

    if($data['Imported Record Scope']=='' ){
      $this->error=true;
      $this->msg='Imported Record Scope empty';
      return;
    }


    

    $sql=sprintf("select `Imported Record Key` from `Imported Record Dimension` where `Imported Record Scope`=%s and `Imported Record Scope Key`=%s and `Imported Record Checksum File`=%s  and `Imported Record Start Date` is NULL",
		 prepare_mysql($data['Imported Record Scope']),
		 prepare_mysql($data['Imported Record Scope Key']),
		 prepare_mysql($data['Imported Record Checksum File'])
		 ); 

    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->found=true;
      $this->found_key=$row['Imported Record Key'];
    }
   
   
    if($create and !$this->found){
      $this->create($data);
      return;
    }
    if($this->found)
      $this->get_data('id',$this->found_key);



    
    if($update and $this->found){

    }


  }

function create($data){
   $this->new=false;
   $base_data=$this->base_data();
  
    foreach($data as $key=>$value){
      if(array_key_exists($key,$base_data))
	$base_data[$key]=_trim($value);
    }

      $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Imported Record Dimension` %s %s",$keys,$values);
    
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->msg=_("Imported Record");
      $this->get_data('id',$this->id);
   $this->new=true;
  
   
   return;
 }else{
   $this->msg=" Error can not create Imported Record";
 }
}
 



  
function append_not_imported_log($value){

$value=$this->data['Not Imported Log'].$value;
$this->update_field_switcher('Not Imported Log',$value);
}


  


  function get($key,$data=false){
    switch($key){
      
      case('To do'):
      return number($this->data['Original Records']-$this->data['Ignored Records']-$this->data['Imported Records']-$this->data['Error Records']);
      break;
      case('Ignored'):
      return number($this->data['Ignored Records']);
      break;
            case('Imported'):
      return number($this->data['Imported Records']);
      break;
            case('Error'):
      return number($this->data['Error Records']);
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
    

 function get_scope_list_link(){
 if($this->data['Scope List Key']){
 
 return sprintf("<a href='customers_list.php?id=%d'>%s</a>",
                            $this->data['Scope List Key'],
                            _('Imported customers list')
                                                                         );
 }else{
 return '';
 }
 }
 
 function get_scope_list_link(){
 
 if($thi)
 
 sprintf('<a href="%s" target="_blank">%s</a>',
                        "app_files/import_errors/$error_log_file_name.csv",
                        _('No added records')
 
 
 }
  
}

?>