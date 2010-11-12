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



if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('country_list'):
    list_country_list();
    break;
case('world_region'):
    list_world_region();
    break;
case('continent_list'):
    list_continent_list();
    break;
case('countries_in_continent'):
    list_countries_in_continent();
    break;
 default:
   $response=array('state'=>404,'msg'=>_('Operation not found'));
   echo json_encode($response);
   
 }



function list_country_list(){
 $conf=$_SESSION['state']['world']['countries'];
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


  $_SESSION['state']['world']['countries']=array(
					
						 'order'=>$order
						 ,'order_dir'=>$order_direction
						 ,'nr'=>$number_results,
						 'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
 
  $sql="select count(*) as total from kbase.`Country Dimension` ";
  
     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(*) as total from kbase.`Country Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
  // $rtext=$total_records." ".ngettext('country_name','country_name',$total_records);
   //  if($total_records>$number_results)
   //    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
  //   else
  //     $rtext_rpp=_('(Showing all)');


    // $translations=array('handle'=>'`User Handle`');
   //  if(array_key_exists($order,$translations))
   //    $order=$translations[$order];
     
    //$order=`Country Name`;


  $_order=$order;
   $_dir=$order_direction;

   
   if($order=='country_name' or $order=='')
     $order='`Country Name`';
   






   $adata=array();
 $sql="select distinct `Country Name`,`Country Code` from kbase.`Country Dimension` order by $order $order_direction  limit $start_from,$number_results;";

    
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
    $country_name=sprintf('<a href="region.php?country=%s">%s</a>',$row['Country Code'],$row['Country Name']);
     $adata[]=array(
		   'country_name'=>$country_name
		 
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
			// 'records_text'=>$rtext,
			// 'records_order'=>$order,
			// 'records_order_dir'=>$order_dir,
			// 'filtered'=>$filtered,
			// 'rtext'=>$rtext,
			//'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}
//---------------------------world region starts here---------
function  list_world_region(){
 $conf=$_SESSION['state']['world']['wregions'];
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


  $_SESSION['state']['world']['wregions']=array(
						
						 'order'=>$order
						 ,'order_dir'=>$order_direction
						 ,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
 
  $sql="select count(*) as total from kbase.`Country Dimension` ";
  
     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(*) as total from kbase.`Country Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
  // $rtext=$total_records." ".ngettext('country_name','country_name',$total_records);
   //  if($total_records>$number_results)
   //    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
  //   else
  //     $rtext_rpp=_('(Showing all)');


    // $translations=array('handle'=>'`User Handle`');
   //  if(array_key_exists($order,$translations))
   //    $order=$translations[$order];
     
    //$order=`Country Name`;


  $_order=$order;
   $_dir=$order_direction;

   
   if($order=='wregion_name' or $order=='')
     $order='`World Region`';
   






   $adata=array();
 $sql="select distinct `World Region`,`World Region Code` from kbase.`Country Dimension` order by $order $order_direction  limit $start_from,$number_results;";

    
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
$wregion_name=sprintf('<a href="region.php?world_region=%s">%s</a>',$row['World Region Code'],$row['World Region']);
     $adata[]=array(
		   'wregion_name'=>$wregion_name
		 
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
			// 'records_text'=>$rtext,
			// 'records_order'=>$order,
			// 'records_order_dir'=>$order_dir,
			// 'filtered'=>$filtered,
			// 'rtext'=>$rtext,
			//'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}
// ------------------------------------------ continents list  starts here ------------------------------------------------
function list_continent_list(){
 $conf=$_SESSION['state']['world']['continents'];
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


  $_SESSION['state']['world']['continents']=array(
					
						 'order'=>$order
						 ,'order_dir'=>$order_direction
						 ,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
 
  $sql="select count(*) as total from kbase.`Country Dimension` ";
  
     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(*) as total from kbase.`Country Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
  // $rtext=$total_records." ".ngettext('country_name','country_name',$total_records);
   //  if($total_records>$number_results)
   //    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
  //   else
  //     $rtext_rpp=_('(Showing all)');


    // $translations=array('handle'=>'`User Handle`');
   //  if(array_key_exists($order,$translations))
   //    $order=$translations[$order];
     
    //$order=`Country Name`;


  $_order=$order;
   $_dir=$order_direction;

   
   if($order=='continent_name' or $order=='')
     $order='`Continent`';
   






   $adata=array();
 $sql="select distinct `Continent`,`Continent Code` from kbase.`Country Dimension` order by $order $order_direction  limit $start_from,$number_results;";

 
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
$continent_name=sprintf('<a href="region.php?continent=%s">%s</a>',$row['Continent Code'],$row['Continent']);
     $adata[]=array(
		   'continent_name'=>$continent_name
		 
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
			// 'records_text'=>$rtext,
			// 'records_order'=>$order,
			// 'records_order_dir'=>$order_dir,
			// 'filtered'=>$filtered,
			// 'rtext'=>$rtext,
			//'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}
// ----------------------------------------------- continent -------------------------------------------------------------
function list_countries_in_continent(){
 $conf=$_SESSION['state']['world']['continents'];
 

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

$continent_code=$_SESSION['state']['continent']['code'];


 $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';


  $_SESSION['state']['world']['continents']=array(
						
						 'order'=>$order
						 ,'order_dir'=>$order_direction
						 ,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
 
  $sql="select count(*) as total from kbase.`Country Dimension` ";
  
     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(*) as total from kbase.`Country Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
  // $rtext=$total_records." ".ngettext('country_name','country_name',$total_records);
   //  if($total_records>$number_results)
   //    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
  //   else
  //     $rtext_rpp=_('(Showing all)');


    // $translations=array('handle'=>'`User Handle`');
   //  if(array_key_exists($order,$translations))
   //    $order=$translations[$order];
     
   


  $_order=$order;
   $_dir=$order_direction;

   
   if($order=='continent_name' or $order=='')
     $order='`Continent`';
//if($f_value=='continent_code' or $f_value=='')
    // $f_value='`Continent Code`';
   


$where=sprintf(" where  `Continent Code`=%s ",prepare_mysql($continent_code));

   $adata=array();
 $sql="select * from kbase.`Country Dimension` $where $wheref order by $order $order_direction  limit $start_from,$number_results;";

 //print($sql);
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
//$continent_name=sprintf('<a href="region.php?continent=%s">%s</a>',$row['Continent Code'],$row['Continent']);
     $adata[]=array(
		   'continent_code'=>$row['Continent Code'], 
		   'country_name'=>$row['Country Name'],
		   'world_region'=>$row['World Region'],
		   'head_of_state'=>$row['Country Head of State'],
		   'goverment_form'=>$row['Country Goverment Form'],
		   'currency'=>$row['Country Currency Code']		 
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
			// 'records_text'=>$rtext,
			// 'records_order'=>$order,
			// 'records_order_dir'=>$order_dir,
			// 'filtered'=>$filtered,
			// 'rtext'=>$rtext,
			//'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

