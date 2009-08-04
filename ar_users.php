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
 
   

 case('users'):

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


 
     $groups=split(',',$row['Groups']);


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
   
   break;
 case('groups'):

 $conf=$_SESSION['state']['users']['groups'];
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


  $_SESSION['state']['users']['groups']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);


   $filtered=0;

   $data=array();

 
   $sql="select g.group_id as id, g.name ,ifnull(group_concat(distinct handle order by handle separator ', '),'') as users from liveuser_groups as g left join liveuser_groupusers as gu on (g.group_id=gu.group_id) left join liveuser_perm_users as pu   on (gu.perm_user_id=pu.perm_user_id  ) left join liveuser_users as u on (u.authuserid=pu.auth_user_id)  group by g.group_id   order by $order $order_direction limit $start_from,$number_results       ";

   $res=mysql_query($sql);
   $total=mysql_num_rows($res);
   if($total<$number_results)
     $rtext=$total.' '.ngettext('work group','work groups',$total);
   else
     $rtext='';
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)){

     $data[]=array(
		   'name'=>$_group[$row['id']],
		   'id'=>$row['id'],
		   'users'=>$row['users']
		   );
   }
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 	 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			  'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered

			 )
		   );
 
   echo json_encode($response);
 
   break;

 default:
   $response=array('state'=>404,'msg'=>_('Operation not found'));
   echo json_encode($response);
   
 }







?>