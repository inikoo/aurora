<?php
/*
 File: ar_users.php 

 Ajax Server Anchor for the User Class


 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
require_once 'common.php';
require_once 'class.User.php';
require_once 'class.Staff.php';

$editor=array(
	      'User Key'=>$user->id
	      );



if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('valid_handle'):

  if(!isset($_REQUEST['handle'])){
    $response=array('state'=>400,'msg'=>'Error');
    echo json_encode($response);return;
  }
  
  if(strlen($_REQUEST['handle'])==0){
    $response=array('state'=>400,'msg'=>'No handle set');
    echo json_encode($response);return;
  }
  

  if(strlen($_REQUEST['handle'])<4){
    $response=array('state'=>400,'msg'=>'Handle should have at least 4 characters');
    echo json_encode($response);return;
  }
  
    
  $user=new User('handle',$_REQUEST['handle']);
  if($user->id)
    $response=array('state'=>400,'msg'=>_('Handle already in use'));
  else
    $response=array('state'=>200,'exists'=>0);
  
  echo json_encode($response);
  


  break;
case('change_passwd'):
  change_user_passwd();

 
   
   break;
case('edit_user'):
   

  edit_user();

  
   
   break;


case ('add_user'):

  $data=array(
	      'handle'=>$_REQUEST['handle'],
	      'passwd'=>$_REQUEST['passwd'],
	      'tipo'=>$_REQUEST['tipo_user'],
	      );
  // print_r($data);

  switch($data['tipo']){
  case 1:
    $data['id_in_table']=$_REQUEST['id_in_table'];
    $staff=new Staff($data['id_in_table']);
    $data['name']=$staff->get('First Name');
    $data['surname']=$staff->get('Surname');
    $data['isactive']=1;
    $data['email']=$staff->get('Email');
    break;
  case 4:
    $data['name']=$_REQUEST['name'];
    $data['surname']=$_REQUEST['surname'];
    $data['isactive']=$_REQUEST['isactive'];
    $data['email']=$_REQUEST['email'];
    $data['groups']=preg_replace('/,%/','',$_REQUEST['groups']);

    break;
  }


  $user=new User('new',$data);

  if($user->new){
    $response= array('state'=>200);
  }else
    $response=array('state'=>400,'msg'=>$user->msg);
    
   echo json_encode($response);  
  break;
 case('add_user_borrarme'):



   $handle = $_REQUEST['handle'];
   $password = $_REQUEST['ep'];
   
   if($handle=='')
     {
       $response=array('state'=>401,'resp'=>_('Wrong user name'));
       echo json_encode($response);
       break;
     }
   
   $sql="select count(*) as numh  from liveuser_users where handle='".addslashes($handle)."'";
   $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
   if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
     if($row['numh']>0){
       $response=array('state'=>401,'resp'=>_('The user')." $handle "._("is already in the database!"));
       echo json_encode($response);
       break;
     }
   }else{
     $response=array('state'=>401,'resp'=>_('Unknown Error'));
     echo json_encode($response);
     break;
   }
   $handle=addslashes($_REQUEST['handle']);
   $pwd=addslashes($_REQUEST['ep']);
   $email =  '"'.addslashes($_REQUEST['email']).'"';
   $name= '"'.addslashes($_REQUEST['name']).'"';
   if($_REQUEST['surname']=='')
     $surname='NULL';
   else
     $surname=  '"'.addslashes($_REQUEST['surname']).'"';
   $isactive = $_REQUEST['isactive'][0];
   $user_type = 1; 
   $lang=$_REQUEST['lang'][0];


   $sql=sprintf("insert into liveuser_users (created,handle,passwd,isactive,name,surname,email,lang_id) values (NOW(),'%s','%s',%d,%s,%s,%s,'%s')",$handle,$pwd,$isactive,$name,$surname,$email,$lang);
   mysql_query($sql);
   //print $sql;
   $user_id=mysql_insert_id();
   if(is_numeric($user_id)){
     $sql=sprintf("insert into liveuser_perm_users (auth_user_id,perm_type) values (%d,%d)",$user_id,$user_type);
     mysql_query($sql);
   }else{
     $response=array('state'=>401,'resp'=>_('Unknown Error'));
     echo json_encode($response);
     break;

   }
   $puser_id=mysql_insert_id();
   
   if(isset($_REQUEST['group'])){
     foreach($_REQUEST['group'] as $group_id){
       if(is_numeric($group_id)){



	 $sql=sprintf("insert into liveuser_groupusers (perm_user_id,group_id) values (%d,%d)",$puser_id,$group_id);
	 mysql_query($sql);
       }
     }
   }


   $sql="
select u.authuserid,gu.group_id,group_concat(distinct g.group_id separator ',') as groups,u.isactive as isactive,u.name as name ,u.surname as surname,u.email as email,u.handle as handle,lower(c.code2) as countrycode,l.id as lang_id,c.Name as country from liveuser_users as u left join liveuser_perm_users as pu on (u.authuserid=pu.auth_user_id) left join liveuser_groupusers as gu on (gu.perm_user_id=pu.perm_user_id) left join liveuser_groups as g on (g.group_id=gu.group_id)  left join lang as l on (l.id=lang_id) left join country as c on (l.country_id=c.id)  where u.authuserid=$user_id group by u.authuserid ";
   $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
   while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
     $groups=split(',',$row['groups']);
     $sgroups='';

     $sgroups=array();
     foreach($groups as $group)
       if(is_numeric($group)){
	 $sgroups[]='<group id='.$group.'/>'.$_group[$group];
       }

     
     

     $name=trim($row['name'].' '.$row['surname'] );
       
     $lang='';
     if(is_numeric($row['lang_id']))
       $lang=$_lang[$row['lang_id']];
     
     if($row['isactive'])
       $active='<img src="art/icons/status_online.png" />';
     else
       $active='<img src="art/icons/status_offline.png" />';
     

     $data=array(
		 'handle'=>$row['handle'],
		 'id'=>$row['authuserid'],
		 'name'=>$name,
		 'email'=>$row['email'],
		 'lang'=>'<img src="art/flags/'.$row['countrycode'].'.gif" langid="'.$row['lang_id'].'" /> '.$lang,
		 'groups'=>$sgroups,
		 'password'=>'<img src="art/icons/key.png"/>',
		 'active'=>$active,
		 'delete'=>'<img src="art/icons/status_busy.png"/>'
		 );





   }

   $sql="select g.group_id as id, g.name ,ifnull(group_concat(distinct handle order by handle separator ', '),'') as users from liveuser_groups as g left join liveuser_groupusers as gu on (g.group_id=gu.group_id) left join liveuser_perm_users as pu   on (gu.perm_user_id=pu.perm_user_id  ) left join liveuser_users as u on (u.authuserid=pu.auth_user_id)  group by g.group_id  order by id      ";
   
   $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
   $gdata=array();
   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
     $gdata[]=array(
		    'name'=>$_group[$row['id']],
		    'id'=>$row['id'],
		    'users'=>$row['users']
		    );
   }
   
   
   
   $response=array(
		   'state'=>200,
		   'newuser'=>$handle,
		   'data'=>$data,
		   'gdata'=>$gdata
		   );
 
   echo json_encode($response);

   break;
case('updateone'):

   $key=$_REQUEST['key'];
   switch($key){
   case('active'):
     $sql=sprintf("update liveuser_users set isactive=%d where authuserid=%d",$_REQUEST['value'],$_REQUEST['id'] );
     mysql_query($sql);
     $response=array('state'=>200);
     echo json_encode($response);
     break;

   case('password'):
     $password= mysql_real_escape_string($_REQUEST['value']);
     $sql=sprintf("update liveuser_users set passwd='%s' where authuserid=%d",$password,$_REQUEST['id'] );
     mysql_query($sql);
     $response=array('state'=>200,'newvalue'=>'******');
     echo json_encode($response);
     break;
   case('groups'):
     $groups=split(",",$_REQUEST['value']);


     $sql=sprintf("select perm_user_id  from liveuser_perm_users where auth_user_id=%d",$_REQUEST['id']);
     $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
     if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
       $puser_id=$row['perm_user_id'];
     }

     $sql=sprintf("delete from  liveuser_groupusers where perm_user_id=%d",$puser_id);
     mysql_query($sql);

     foreach($groups as $group){
       $group=preg_replace('/(.*?)id=/','',$group);
       $group=preg_replace('/>(.*?)$/','',$group);
       $group=str_replace('"','' ,$group);
       $group=str_replace('\\','',$group);
       $group=str_replace(' ','' ,$group);
       $group=str_replace('/','' ,$group);
       if(is_numeric($group)){

	 

	 $sql=sprintf("insert into liveuser_groupusers (perm_user_id,group_id) values (%d,%d)",$puser_id,$group);
	 mysql_query($sql);
       }
       
     }





     
     $sql="select g.group_id as id, g.name ,ifnull(group_concat(distinct handle order by handle separator ', '),'') as users from liveuser_groups as g left join liveuser_groupusers as gu on (g.group_id=gu.group_id) left join liveuser_perm_users as pu   on (gu.perm_user_id=pu.perm_user_id  ) left join liveuser_users as u on (u.authuserid=pu.auth_user_id)  group by g.group_id  order by id      ";
     
     $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
     $gdata=array();
     while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
       $gdata[]=array(
		      'name'=>$_group[$row['id']],
		      'id'=>$row['id'],
		      'users'=>$row['users']
		      );
     }
     $response=array('state'=>200,'gdata'=>$gdata);
     echo json_encode($response);

     break;
   case('email'):
     $email=mysql_real_escape_string($_REQUEST['value']);
     $sql=sprintf("update liveuser_users set email='%s' where authuserid=%d",$email,$_REQUEST['id']);
     mysql_query($sql);
     $response=array('state'=>200,'newvalue'=>$_REQUEST['value']);
     echo json_encode($response);
     break;
   case('name'):
     $name='NULL';
     $surname='NULL';
     
     // $names=str_replace("\'","'",$_REQUEST['value']);
     $names=mysql_real_escape_string($_REQUEST['value']);
     $names=split(" ",$names);
     if(count($names)==1)
       $name="'".$names[0]."'";
     
     else{
       $name="'".array_shift($names)."'";
       $surname="'".join(" ",$names)."'";
     }
     
     $sql=sprintf("update liveuser_users set name=%s , surname=%s  where authuserid=%d",$name,$surname,$_REQUEST['id']);

     mysql_query($sql);
     $response=array('state'=>200,'data'=>array('newvalue'=>$_REQUEST['value']));
     echo json_encode($response);
     break;
   case('lang'):
     $lang=$_REQUEST['value'];
     $lang=preg_replace('/(.*?)langid=/','',$lang);
     $lang=preg_replace('/>(.*?)$/','',$lang);
     $lang=str_replace('"','',$lang);
     $lang=str_replace('\\','',$lang);
     $lang=str_replace(' ','',$lang);
     $lang=str_replace('/','',$lang);


     if(is_numeric($lang)){
       $sql=sprintf("update liveuser_users set lang_id=%d  where authuserid=%d",$lang,$_REQUEST['id']);
       mysql_query($sql);
       $response=array('state'=>200,'data'=>array('newvalue'=>$_REQUEST['value']));
     }else
       $response=array('state'=>$lang);

     echo  json_encode($response);
     break;
   case('delete'):

     if($_REQUEST['value']==1){
       $sql=sprintf("select perm_user_id  from liveuser_perm_users where auth_user_id=%d",$_REQUEST['id']);
       $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
       if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	 $puser_id=$row['perm_user_id'];
       }


       $sql=sprintf("delete from liveuser_users where authuserid=%d",$_REQUEST['id']);
       mysql_query($sql);
       $sql=sprintf("delete from liveuser_perm_users where perm_user_id=%d",$puser_id);
       mysql_query($sql);
       $sql=sprintf("delete from liveuser_userrights where perm_user_id=%d",$puser_id);
       mysql_query($sql);
       $sql=sprintf("delete from liveuser_group_users where perm_user_id=%d",$puser_id);
       mysql_query($sql);
     
     

       $response=array('state'=>200);
       echo json_encode($response);
     }
     break;
   default:
     $response=array('state'=>404,'msg'=>_('Sub-operation not found'));
     echo json_encode($response);
   }

}

   function list_users(){

 $conf=$_SESSION['state']['users']['user_list'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
  if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';


  $_SESSION['state']['users']['user_list']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



  $filter_msg='';
  $wheref='';
  if($f_field=='handle' and $f_value!='')
    $wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
  elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `User Alias` like '%".addslashes($f_value)."%'";
  
  $sql="select count(*) as total from `User Dimension`  $where $wheref   ";
  
     $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
       $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(*) as total from `Product Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('user','users',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
     else
       $rtext_rpp=_('(Showing all)');


     $translations=array('handle'=>'`User Handle`');
     if(array_key_exists($order,$translations))
       $order=$translations[$order];
     
       


   $adata=array();
   $sql="Select *,(select GROUP_CONCAT(UGUD.`User Group Key`) from `User Group User Bridge` UGUD left join  `User Group Dimension` UGD on (UGUD.`User Group Key`=UGD.`User Group Key`)      where UGUD.`User Key`=U.`User Key`   ) as Groups  from `User Dimension` U  $where $wheref   order by $order $order_direction limit $start_from,$number_results;";
   //   print $sql;
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {


 
     $groups=preg_split('/,/',$row['Groups']);


     $adata[]=array(
		   'handle'=>$row['User Handle'],
		   'tipo'=>$row['User Type'],
		   'id'=>$row['User Key'],
		   'name'=>$row['User Alias'],
		   'email'=>$row['User Email'],
		   'lang'=>$row['User Language Code'],
		   'groups'=>$groups,
		   'password'=>'<img style="cursor:pointer" user_name="'.$row['User Alias'].'" user_id="'.$row['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>'.($row['User Email']!=''?'<img src="art/icons/key_go.png"/>':''),
		   'passwordmail'=>($row['User Email']!=''?'<img src="art/icons/key_go.png"/>':''),
		   'isactive'=>$row['User Active'],
		   'delete'=>'<img src="art/icons/status_busy.png"/>'
		   );

   }
  mysql_free_result($res);

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
	 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered,
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);

   }


function edit_user(){
  global $editor;
  $user=new User($_REQUEST['user_id']);
  $user->editor=$editor;
  if($user->id){
    $data=array(
		'value'=>$_REQUEST['newvalue'],
		);
    $user->update($_REQUEST['key'],$data);


   if($user->updated)
     $response=array('state'=>200,'data'=>$user->new_value);
   else
     $response=array('state'=>400,'msg'=>$user->msg);
   }else
   $response=array('state'=>400,'msg'=>_("User don't exist"));
   echo json_encode($response);

}
function change_user_passwd(){
  $user=new User($_REQUEST['user_id']);
  $value=$_REQUEST['value'];
   if($user->id){
       $user->change_password($value);
       if(!$user->error)
	 $response=array('state'=>200);
       else
	 $response=array('state'=>400,'msg'=>$user->msg);

       
   }else
     $response=array('state'=>400,'msg'=>_("User don't exist"));
   echo json_encode($response);
}


?>