<?
class User{
  var $db;
  var $data=array();
  var $tipo;

  function __construct($a1='id',$a2=false) {
    $this->db =MDB2::singleton();

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
    $handle = $data['handle'];
     
     
    if($handle=='')
      {
	$this->msg= array('ok'=>false,'msg'=>_('Wrong user name'));
	return;
      }

    $sql="select count(*) as numh  from liveuser_users where handle='".addslashes($handle)."'";
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      if($row['numh']>0){
	$this->msg= array('ok'=>false,'msg'=>_('The user')." $handle "._("is already in the database!"));return;

      }
    }else{
      $this->msg= array('ok'=>false,'msg'=>_('Unknown error'));return;

    }


   
    
    $sql=sprintf("select handle  from liveuser_users where tipo=1 and id_in_table=%d",$data['id_in_table']);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $this->msg= array('ok'=>false,'msg'=>_('The user with id ')." ".$data['id_in_table']." "._("is already in the database as")." ".$row['handle']);
      return;
    }

     
   
    $sql=sprintf("insert into liveuser_users (created,handle,passwd,isactive,lang_id,tipo,id_in_table) values (NOW(),%s,%s,0,1,%s,%d)"
		 ,prepare_mysql($data['handle'])
		 ,prepare_mysql($data['passwd'])
		 ,$data['tipo']
		 ,$data['id_in_table']
		 );
    mysql_query($sql);

    $user_id=mysql_insert_id();
    $user_type = 1; 
    if(is_numeric($user_id)){
      $sql=sprintf("insert into liveuser_perm_users (auth_user_id,perm_type) values (%d,%d)",$user_id,$user_type);
      mysql_query($sql);
    }else{
      $this->msg= array('ok'=>false,'resp'=>_('Unknown Error'));return;

    }
    $puser_id=mysql_insert_id();
   
    if(isset($data['group'])){
      foreach($data['group'] as $group_id){
	while(is_numeric($group_id)){
	  $sql=sprintf("insert into liveuser_groupusers (perm_user_id,group_id) values (%d,%d)",$puser_id,$group_id);
	  mysql_query($sql);
	}
      }
    }
    $this->msg= array('ok'=>true,'msg'=>_('User added susesfully'));return;
  }


  function get_data($key,$data){
    global $_group;
    //    print "acac---- $key ----  $data ---asasqqqqqqqqq";
    $sql=sprintf("select authuserid,handle,isactive,tipo,id_in_table from liveuser_users where authuserid=%d",$data);
    // print $sql;
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()) {
      $this->id=$row['authuserid'];
      $this->data['handle']=$row['handle'];
      $this->data['isactive']=$row['isactive'];
      $this->data['tipo']=$row['tipo'];
      switch($this->data['tipo']){
      case(1):
	$this->data['tipo_name']=_('Staff');
	$this->data['staff_id']=$row['id_in_table'];
	break;
      }
      $sql=sprintf("select  perm_user_id  from liveuser_perm_users  where auth_user_id=%d",$this->id);
      //      print $sql;
      $result2 =& $this->db->query($sql);
       if($row2=$result2->fetchRow()) {
	 $this->data['perm_id']=$row2['perm_user_id'];
       }

      $this->data['groups']=array();
       $sql=sprintf("select group_id from liveuser_groupusers where perm_user_id=%d  ",$this->data['perm_id']);
       //       print $sql;
       $result2 =& $this->db->query($sql);
       $this->data['groups_list']='';
       while($row2=$result2->fetchRow()) {
	 $this->data['groups'][]=$row2['group_id'];
	 $this->data['groups_list'].=', '.$_group[$row2['group_id']];
       }
       $this->data['groups_list']=preg_replace('/^\,\s/','',$this->data['groups_list']);
    }
    
  }




function set($tipo,$data){
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
   if(strlen($data)!=64)
     return array('ok'=>false,'msg'=>_('Wrong password').' '.strlen($data));
   
   $sql=sprintf("update liveuser_users set passwd=%s where authuserid=%d",prepare_mysql($data),$this->id);
   mysql_query($sql);
   return array('ok'=>true,'msg'=>_('Password changed'));
 }
 
 function save($tipo){
  switch($tipo){
  case('isactive'):
    $sql=sprintf("update liveuser_users set isactive=%d where authuserid=%d",$this->data['isactive'],$this->id);
    mysql_query($sql);
    break;
  }

}
function save_history($tipo,$data){
  switch($tipo){
  case('isactive'):
    if($this->data['isactive'])
      $note=_('User')." ".$this->data['handle']." was  actived";
    else
      $note=_('User')." ".$this->data['handle']." was  disabled";
    $sql=sprintf("insert into  history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values ('%s','USER',%d,'ACT',NULL,'CHG',%d,'%d','%d',%s)",
		 $data['date'],
		 $this->id,
		 $data['user_id'],
		 $data['old_value'],
		 $this->data['isactive'],
		 prepare_mysql($note)
		 );
    mysql_query($sql);
  }

}

 function add_group($to_add,$history=true){
   
   foreach($to_add as $group_id){
     $sql=sprintf("insert into liveuser_groupusers (perm_user_id,group_id) values (%d,%d) ",$this->data['perm_id'],$group_id);
     //print $sql;
     mysql_query($sql);
   }

 }

 function delete_group($to_add,$history=true){
   
   foreach($to_add as $group_id){
     $sql=sprintf("delete from  liveuser_groupusers where perm_user_id=%d and group_id=%d ",$this->data['perm_id'],$group_id);
     mysql_query($sql);
   }

 }



function get($tipo){
  switch($tipo){
  case('isactive'):
    return $this->data['isactive'];
  case('groups'):
    return $this->data['groups'];
  }
}
   
}

?>