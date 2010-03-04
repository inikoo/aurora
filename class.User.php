<?php
/*
 File: User.php 

 This file contains the User Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Store.php');
include_once('class.Warehouse.php');

class User extends DB_Table{


  private $groups_read=false;
  private $rights_read=false;
  
  function User($a1='id',$a2=false) {

 $this->table_name='User';
    if($a1=='new' and is_array($a2)){
      $this->create($a2);
      return;
    }
     
    if(is_numeric($a1) and !$a2){
      $_data= $a1;
      $key='id';
    }else{
      $_data= $a2;
      $key=$a1;
    }

    $this->get_data($key,$_data);
    return;
  }
   
  function create($data){
    $this->new=false;
    $this->msg=_('Unknown Error(0)');
    $base_data=$this->base_data();
    
    foreach($data as $key=>$value){
      if(isset($base_data[strtolower($key)]))
	$base_data[strtolower($key)]=_trim($value);
    }
  
    
    if($base_data['User Handle']=='')
      {
	$this->msg=_('Wrong handle');
	return;
      }
    if(strlen($base_data['User Handle'])<4)
      {
	$this->msg=_('Handle to short');
	return;
      }
    $sql="select count(*) as numh  from `User Dimenson` where handle=".prepare_mysql($base_data['handle']);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      if($row['numh']>0){
	$this->msg= _('The user')." ".$base_data['User Handle']." "._("is already in the database!");return;
	
      }
    }else{
      $this->msg= _('Unknown error');return;
      
    }
  

    if($base_data['User Type']=='Staff'){
      
      $sql=sprintf("select `User Handle`  from `User Dimension` where `User Type`='Staff' and `User Parent Key`%d",$data['User Parent Key']);
      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$this->msg=_('The staff member  with id ')." ".$data['User Parent Key']." "._("is already in the database as")." ".$row['User Handle'];
	return;
      }
      
    }
  
    $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `User Dimension` %s %s",$keys,$values);
   
      
    if(mysql_query($sql)){
      
      $user_id=mysql_insert_id();
     
    
    if(isset($data['group'])){
      $groups=split(',',$data['group']);
      foreach($data['group'] as $group_id){
	while(is_numeric($group_id)){
	  $sql=sprintf("insert into liveuser_groupusers (perm_user_id,group_id) values (%d,%d)",$puser_id,$group_id);
	  mysql_query($sql);
	}
      }
    }
    $this->new=true;
    $this->msg= _('User added susesfully');
    $this->get_data('id',$user_id);
    return;
    }else{
      $this->msg= _('Unknown error(2)');return;
    }
    

 
  }


  function get_data($key,$data){
    global $_group;
    //    print "acac---- $key ----  $data ---asasqqqqqqqqq";
    if($key=='handle')
      $sql=sprintf("select * from  `User Dimension` where `User Handle`=%s",prepare_mysql($data));
 
    else
      $sql=sprintf("select * from `User Dimension` where `User Key`=%d",$data);
     

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $this->id=$this->data['User Key'];
      $this->data['User Password']='';
    }
   

  }


  function update_active($value){
    $this->updated=false;
    
    if(preg_match('/^(activate|yes|si|1|true)$/i',$value))
      $value='Yes';
     if(preg_match('/^(des?activate|no|0|false)$/i',$value))
      $value='No';

     if(!preg_match('/^(Yes|No)$/',$value)){
       $this->error=true;
       $thus->msg=_('Wrong value');
     }

     $sql=sprintf("update `User Dimension` set `User Active`=%s where `User Key`=%d  ",prepare_mysql($value),$this->id);
     mysql_query($sql);
     //print $sql;
     if (mysql_affected_rows()>0) {
       $this->updated=true;
       $this->data['User Active']=$value;
       $this->new_value=$value;
       if($value=='Yes'){
	 $history_data=array(
			     'note'=>_('User Activated')
			     ,'details'=>_trim(_('User')." ".$this->data['User Alias']." (".$this->data['User Type'].")  "._('activated'))
			     ,'action'=>'edited'
			     
			     );
       }else{
	 $history_data=array(
			     'note'=>_('User Desactivated')
			    ,'details'=>_trim(_('User')." ".$this->data['User Alias']." (".$this->data['User Type'].")  "._('deactivated'))
			     ,'action'=>'edited'
			  );
	 
       }

      $this->add_history($history_data);

     }else{
       $this->msg=_('Nothing to change');
     }

  }


  function update_warehouses($value){
    $this->updated=false;
    
    if($this->data['User Type']!='Staff')
      return;
    $warehouses=preg_split('/,/',$value);
    foreach($warehouses as $key=>$value){
      if(!is_numeric($value) )
	unset($warehouses[$key]);
    }
    $this->read_warehouses();
    $old_warehouses=$this->warehouses;
    $to_delete = array_diff($old_warehouses, $warehouses);
    $to_add = array_diff($warehouses, $old_warehouses);
    $changed=0;
    if(count($to_delete)>0){
      $changed+=$this->delete_warehouse($to_delete);
    }
    if(count($to_add)>0){
      $changed+=$this->add_warehouse($to_add);
    }
    $this->read_warehouses();	
    if($changed>0){
      $this->updated=true;
      $this->new_value=$this->warehouses;
    }
  }

  function update_stores($value){
    $this->updated=false;
    
    if($this->data['User Type']!='Staff')
      return;
    $stores=preg_split('/,/',$value);
    foreach($stores as $key=>$value){
      if(!is_numeric($value) )
	unset($stores[$key]);
    }
    $this->read_stores();
    $old_stores=$this->stores;
    $to_delete = array_diff($old_stores, $stores);
    $to_add = array_diff($stores, $old_stores);
    $changed=0;
    if(count($to_delete)>0){
      $changed+=$this->delete_store($to_delete);
    }
    if(count($to_add)>0){
      $changed+=$this->add_store($to_add);
    }
    $this->read_stores();	
    if($changed>0){
      $this->updated=true;
      $this->new_value=$this->stores;
    }
  }

  
  
  


  function update_groups($value){
    
    $this->updated=false;

    //     global $_group;
     $groups=preg_split('/,/',$value);
     foreach($groups as $key=>$value){
       if(!is_numeric($value) )
	 unset($groups[$key]);
     }
       

     $this->read_groups();
     
     
     $old_groups=$this->groups_key_array;
   
     $to_delete = array_diff($old_groups, $groups);
     $to_add = array_diff($groups, $old_groups);
   

    

     $changed=0;
	if(count($to_delete)>0){
	  $changed+=$this->delete_group($to_delete);

	}
	if(count($to_add)>0){
	  $changed+=$this->add_group($to_add);

	}
	$this->read_groups();	
	
	if($changed>0){
	  $this->updated=true;
	  $this->new_value=$this->groups_key_array;
	}


  }


function update($tipo,$data){
 switch($tipo){
  case('isactive'):
    $this->update_active($data['value']);
    break;
 case('groups'):
   $this->update_groups($data['value']);
    break;
 case('stores'):
   $this->update_stores($data['value']);
    break;
 case('warehouses'):
   $this->update_warehouses($data['value']);
    break;
 case('name'):
 case('alias'):
 case('User Alias'):
    $this->update_field('User Alias',$data['value']);
    break;
 }


}
function xupdate($tipo,$data){
  switch($tipo){
  case('isactive'):
       
    if($data['value'])
      $value=1;
    else
      $value=0;
    if($value==$this->data['isactive'])
      return array('ok'=>true);
    $old_value=$this->data['isactive'];
    $this->data['isactive']=$value;
    $this->save('isactive');
    $this->save_history('isactive',array('user_id'=>$data['user_id'],'date'=>date('Y-m-d H:i:s'),'old_value'=>$old_value   ));
    return array('ok'=>true);
    break;
      case('groups'):
       global $_group;
	$groups=split(',',$data['value']);
	foreach($groups as $key=>$value){
	  if(!is_numeric($value) )
	    unset($groups[$key]);
	}
       
	
	$old_groups=$this->data['groups'];
	//	print_r($old_groups);
	//	print_r($groups);
	$to_delete = array_diff($old_groups, $groups);
	$to_add = array_diff($groups, $old_groups);
	//	print_r($to_delete);
	//	print_r($to_add);
	
	$this->data['groups']=$groups;
	$this->data['groups_list']='';
	foreach($this->data['groups'] as $group_id){
	  $this->data['groups_list'].=', '.$_group[$group_id];
	}
	$this->data['groups_list']=preg_replace('/^\,\s/','',$this->data['groups_list']);
	if(count($to_delete)>0){
	$this->delete_group($to_delete);
	//$this->save_history('isactive',array('user_id'=>$data['user_id'],'date'=>date('Y-m-d H:i:s'),'old_value'=>$old_value   ));
	}
	if(count($to_add)>0){
	$this->add_group($to_add);
	//$this->save_history('isactive',array('user_id'=>$data['user_id'],'date'=>date('Y-m-d H:i:s'),'old_value'=>$old_value   ));
	}

	return array('ok'=>true);
	break;
  }
}
 

 
 function change_password($data){
   if(strlen($data)!=64){
     $this->error=true;
     $this->error_updated=true;
     $this->msg.=', Wrong password format';
     $this->msg_updated.=', Wrong password format';

   }

   $sql=sprintf("update `User Dimension` set `User Password`=%s where `User Key`=%d",prepare_mysql($data),$this->id);
   mysql_query($sql);
   $this->updated=true;
   
 }





 function add_group($to_add,$history=true){
   $changed=0;
   foreach($to_add as $group_id){

      $sql=sprintf("select * from  `User Group Dimension` where `User Group Key`=%d",$group_id);
      $res=mysql_query($sql);
      if($row=mysql_fetch_array($res)){
	$group_name=$row['User Group Name'];
	

     $sql=sprintf("insert into `User Group User Bridge`values (%d,%d) ",$this->id,$group_id);
     //print $sql;
     mysql_query($sql);
      if (mysql_affected_rows()>0) {
      $changed++;
     $history_data=array(
			 'note'=>_('User added to Group')
			 ,'details'=>_trim(_('User')." ".$this->data['User Alias']." "._('added to')." ".$group_name)
			 ,'action'=>'associate'
			 ,'indirect_object'=>'Group'
			 ,'indirect_object_key'=>$group_id
			 );
     $this->add_history($history_data);
      }
   }


   }
return $changed;
 }

 function delete_group($to_delete,$history=true){
    $changed=0;
   foreach($to_delete as $group_id){
     $sql=sprintf("delete from `User Group User Bridge` where `User Key`=%d and `User Group Key`=%d ",$this->id,$group_id);
     //   print $sql;
     mysql_query($sql);
   
     if (mysql_affected_rows()>0) {
       $changed++;
       $history_data=array(
			   'note'=>_('User deleted from Group')
			   ,'details'=>_trim(_('User')." ".$this->data['User Alias']." "._('removed from')." ".$this->groups[$group_id]['User Group Name'])
			 ,'action'=>'disassociate'
			   ,'indirect_object'=>'Group'
			 ,'indirect_object_key'=>$group_id
			   );
       $this->add_history($history_data);
     }  
   }
return $changed;
 }

function add_store($to_add,$history=true){
   $changed=0;
   foreach($to_add as $scope_id){
    
    $store=new Store($scope_id);
    if(!$store->id)
    continue;
    $sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Store',%d) ",$this->id,$scope_id);
     //print $sql;
     mysql_query($sql);
      if (mysql_affected_rows()>0) {
     $changed++;
     $history_data=array(
			 'note'=>_('User Rights Associated with Store')
			 ,'details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights associated with')." ".$store->data['Store Name'])
			 ,'action'=>'associate'
			 ,'indirect_object'=>'Store'
			 ,'indirect_object_key'=>$store->id
			 );
     $this->add_history($history_data);
   }
   }
   return $changed;

 }


 function delete_store($to_delete,$history=true){
   $changed=0;
   foreach($to_delete as $scope_id){
     $store=new Store($scope_id);
     if(!$store->id)
       continue;
     $sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `scope Key`=%d and `Scope`='Store' ",$this->id,$scope_id);
     mysql_query($sql);
   }
   if (mysql_affected_rows()>0) {
     $changed++;
     $history_data=array(
			 'note'=>_('User Rights Disassociated with Store')
			 ,'details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights disassociated with')." ".$store->data['Store Name'])
			 ,'action'=>'disassociate'
			 ,'indirect_object'=>'Store'
			 ,'indirect_object_key'=>$store->id
			 );
     $this->add_history($history_data);
     
   }
   return $changed;
 }


function add_warehouse($to_add,$history=true){
   $changed=0;
   foreach($to_add as $scope_id){
    
    $warehouse=new Warehouse($scope_id);
    if(!$warehouse->id)
    continue;
    $sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Warehouse',%d) ",$this->id,$scope_id);
     //print $sql;
     mysql_query($sql);
      if (mysql_affected_rows()>0) {
     $changed++;
     $history_data=array(
			 'note'=>_('User Rights Associated with Warehouse')
			 ,'details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights associated with')." ".$warehouse->data['Warehouse Name'])
			 ,'action'=>'associate'
			 ,'indirect_object'=>'Warehouse'
			 ,'indirect_object_key'=>$warehouse->id
			 );
     $this->add_history($history_data);
   }
   }
   return $changed;

 }


 function delete_warehouse($to_delete,$history=true){
   $changed=0;
   foreach($to_delete as $scope_id){
     $warehouse=new Warehouse($scope_id);
     if(!$warehouse->id)
       continue;
     $sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `scope Key`=%d and `Scope`='Warehouse'",$this->id,$scope_id);
     mysql_query($sql);
   }
   if (mysql_affected_rows()>0) {
     $changed++;
     $history_data=array(
			 'note'=>_('User Rights Disassociated with Warehouse')
			 ,'details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights disassociated with')." ".$warehouse->data['Warehouse Name'])
			 ,'action'=>'disassociate'
			 ,'indirect_object'=>'Warehouse'
			 ,'indirect_object_key'=>$warehouse->id
			 );
     $this->add_history($history_data);
     
   }
   return $changed;
 }


function get($key){
  
   
  
  if(array_key_exists($key,$this->data))
    return $this->data[$key];


  switch($tipo){
  case('User Pasword'):
    return "******";
  case('isactive'):
    return $this->data['Is Active'];
  case('groups'):
    return $this->data['groups'];
  }

 if(array_key_exists($key,$this->data))
    return $this->data[$key];

}
   
 function is($tag=''){
   if(strtolower($this->data['User Type'])==strtolower($tag)){
     return true;
   }else
     return false;
   
 }

 function can_view($tag,$tag_key=false){

   return $this->can_do('View',$tag,$tag_key);
   
 }
 
 function can_create($tag,$tag_key=false){
   return $this->can_do('Create',$tag,$tag_key);
 }
 function can_edit($tag,$tag_key=false){
   return $this->can_do('Edit',$tag,$tag_key);
 }
 function can_delete($tag,$tag_key=false){
   return $this->can_do('Delete',$tag,$tag_key);
 }
 

 function can_do($right_type,$tag,$tag_key=false){
   if(!is_string($tag))
     return false;
   $tag=strtolower(_trim($tag));
 if($tag_key==false){
 if(isset($this->rights_allow[$right_type][$tag]))
 return true;
 else
 return false;
 }




 //    return $this->can_do_any($right_type,$tag);
//  if(!is_numeric($tag_key) or $tag_key<=0 or !preg_match('/^\d+$/',$tag_key) )
//     return false;
//  return $this->can_do_this_key($right_type,$tag,$tag_key);
   
 }
 
 
 
 
 
 
 
function can_do_anyx($right_type,$tag){
  
  if(array_key_exists($tag,$this->rights_allow[$right_type]))
    return true;
  else
    return false;
}


function can_do_this_key($right_type,$tag,$tag_key){
  
  
  
  
  if(isset($this->rights_allow[$right_type][$tag])){
    if(array_key_exists($tag_key, $this->stores))
        return true;
    else
        false;
  }else
    return false;
  
 
}


function read_groups(){
  $this->groups=array();
  $this->groups_key_list='';
  $this->groups_key_array=array();

  $sql=sprintf("select * from `User Group User Bridge` UGUB left join `User Group Dimension` GD on (GD.`User Group Key`=UGUB.`User Group Key`) where UGUB.`User Key`=%d",$this->id);
  //print $sql;
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    $this->groups[$row['User Group Key']]=array('User Group Name'=>$row['User Group Name']);
    $this->groups_key_list.=','.$row['User Group Key'];
    $this->groups_key_array[]=$row['User Group Key'];
  }
  $this->groups_key_list=preg_replace('/^,/','', $this->groups_key_list);
  
  $this->groups_read=true;
}



function  read_warehouses() {

    $this->warehouses=array();
    $sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Warehouse'"
                 , $this->id);
    $res=mysql_query($sql);
    while ($row=mysql_fetch_array($res)) {
        $this->warehouses[]=$row['Scope Key'];
    }
    $this->warehouse_righs='none';
    if ($this->data['User Type']=='Staff') {
        $sql="select count(*) as num_warehouses from `Warehouse Dimension`";
        $res=mysql_query($sql);
        $row=mysql_fetch_array($res);
        $num_warehouses=$row['num_warehouses'];
        $num_warehouses=count($this->warehouses);
        if ($num_warehouses==$num_warehouses)
            $this->warehouse_rights='all';
        else
            $this->warehouse_righs='some';
    }
}

function  read_stores() {

    $this->stores=array();
    $sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Store' "
                 , $this->id);
    $res=mysql_query($sql);
    while ($row=mysql_fetch_array($res)) {
        $this->stores[]=$row['Scope Key'];
    }
    $this->store_righs='none';
    if ($this->data['User Type']=='Staff') {
        $sql="select count(*) as num_stores from `Store Dimension`";
        $res=mysql_query($sql);
        $row=mysql_fetch_array($res);
        $num_stores=$row['num_stores'];
        $num_stores=count($this->stores);
        if ($num_stores==$num_stores)
            $this->store_rights='all';
        else
            $this->store_righs='some';
    }
}




function read_rights(){
  
  $this->rights_allow['View']=array();
  $this->rights_allow['Delete']=array();
  $this->rights_allow['Edit']=array();
  $this->rights_allow['Create']=array();
  $this->rights=array();
  
  if(!$this->groups_read)
     $this->read_groups();

   if(count($this->groups)>0){

   $sql=sprintf("select * from `User Group Rights Bridge`  UGRB left join `Right Dimension` RD on (RD.`Right Key`=UGRB.`Right Key`)  where `Group Key` in (%s)"
    , $this->groups_key_list);
 
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     if($row['Right Type']=='View'){
       $this->rights_allow['View'][$row['Right Name']]=array(
        'Right Name'=>$row['Right Name'],
        //'Right Access'=>$row['Right Access'],
        //'Right Access Keys'=>$row['Rigth Access Keys']
        );
       $this->rights[$row['Right Name']]['View']='View';
     }if($row['Right Type']=='Delete'){
       $this->rights_allow['Delete'][$row['Right Name']]=$row['Right Name'];
       $this->rights[$row['Right Name']]['Delete']='Delete';
     }if($row['Right Type']=='Edit'){
       $this->rights_allow['Edit'][$row['Right Name']]=array('Right Name'=>$row['Right Name']
       //,'Right Access'=>$row['Right Access'],'Rigth Access Keys'=>$row['Rigth Access Keys']
       );
       $this->rights[$row['Right Name']]['Edit']='Edit';
     }if($row['Right Type']=='Create'){
       $this->rights_allow['Create'][$row['Right Name']]=$row['Right Name'];
       $this->rights[$row['Right Name']]['Create']='Create';
     }
     

   }
   }
    $sql=sprintf("select * from `User Rights Bridge`  URB left join  `Right Dimension` RD on (RD.`Right Key`=URB.`Right Key`)  where `User Key`=%d", $this->id);
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)){
     if($row['Right Type']=='View'){
       $this->rights_allow['View'][$row['Right Name']]=$row['Right Name'];
       $this->rights[$row['Right Name']]['View']='View';
     }if($row['Right Type']=='Delete'){
       $this->rights_allow['Delete'][$row['Right Name']]=$row['Right Name'];
       $this->rights[$row['Right Name']]['`Delete']='Delete';
     }if($row['Right Type']=='Edit'){
       $this->rights_allow['Edit'][$row['Right Name']]=$row['Right Name'];
       $this->rights[$row['Right Name']]['Edit']='Edit';
     }if($row['Right Type']=='Create'){
       $this->rights_allow['Create'][$row['Right Name']]=$row['Right Name'];
       $this->rights[$row['Right Name']]['Create']='Create';
     }
   }

//print_r($this->rights_allow);
   
 }
 

function can_view_list($right_name){
  $list=array();

    if(isset($this->rights_allow['View'][$right_name])){
      $rights_data=$this->rights_allow['View'][$right_name];
      if($rights_data['Right Access']=='All'){
	
	switch($right_name){
	case('stores'):
	  $sql=sprintf('select `Store Key`  from `Store Dimension`');

	  $res=mysql_query($sql);
	  while($row=mysql_fetch_array($res)){
	    $list[]=$row['Store Key'];
	  }
	  break;
	}
	
      }
      
    }
    
  return $list;
}

}



 

?>