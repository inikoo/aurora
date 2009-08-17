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
 case('users'):
    list_users();
    break;
 case('groups'):
    list_groups();
    break;
 default:
   $response=array('state'=>404,'msg'=>_('Operation not found'));
   echo json_encode($response);
   
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
function list_groups(){
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
$_order=$order;
if($order=='name'){
$_order='`User Group Name`';
}else
    $_order='`User Group Name`';
 
   $sql="select *,(select GROUP_CONCAT(`User Alias`) from `User Dimension` U left join `User Group User Bridge` UGUB on (U.`User Key`=UGUB.`User Key`) 
   where UGUB.`User Group Key`=UG.`User Group Key` ) as Users from `User Group Dimension` UG  order by $_order $order_direction limit $start_from,$number_results       ";
//print $sql;
   $res=mysql_query($sql);
   $total=mysql_num_rows($res);
   if($total<$number_results)
     $rtext=$total.' '.ngettext('work group','work groups',$total);
   else
     $rtext='';
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)){

     $data[]=array(
		   'name'=>$row['User Group Name'],
		   'id'=>$row['User Group Key'],
		   'users'=>$row['Users']
		   );
   }
     mysql_free_result($res);
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
}




?>