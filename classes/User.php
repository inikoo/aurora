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
	if(is_numeric($group_id)){



	  $sql=sprintf("insert into liveuser_groupusers (perm_user_id,group_id) values (%d,%d)",$puser_id,$group_id);
	  mysql_query($sql);
	}
      }
    }
    $this->msg= array('ok'=>true,'msg'=>_('User added susesfully'));return;
  }


  function get_data($key,$data){
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
  }
}
function save($tipo){
  switch($tipo){
  case('isactive'):
    $sql=sprintf("update liveuser_users set isactive=%d where authuserid=%d",$this->data['isactive'],$this->id);
    mysql_query($sql);

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



function get($tipo){
  switch($tipo){
  case('isactive'):
    return $this->data['isactive'];

  }
}
   
}

?>