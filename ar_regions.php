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
require_once 'ar_common.php';



if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('country_list'):
    list_countries();
    break;
case('postal_code'):
    list_postal_code();
    break;
case('city'):
    list_city();
    break;
case('department'):
    list_department();
    break;
case('family'):
    list_family();
    break;
case('product'):
    list_product();
    break;
case('wregion'):
    list_world_regions();
    break;
case('continent_list'):
    list_continents();
    break;
case('countries_in_continent'):
    list_countries_in_continent();
    break;
case('countries_in_wregion'):
    list_countries_in_wregion();
    break;
case('country_info_from_2alpha'):
$data=prepare_values($_REQUEST,array(
                             '2alpha'=>array('type'=>'string'),
                                'prefix'=>array('type'=>'string','optional'=>true)
                         ));
   $data['tag']=  '2alpha';                    
    country_info($data);    
    break;
 default:
   $response=array('state'=>404,'msg'=>_('Operation not found'));
   echo json_encode($response);
   
 }

function country_info($data){
include_once('class.Country.php');
$country=new Country($data['tag'],$data[$data['tag']]);

 $response=array('state'=>200,'data'=>$country->data);
if(array_key_exists('prefix', $data)){
$response['prefix']=$data['prefix'];
}

   echo json_encode($response);

}

function list_countries(){
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


  $_SESSION['state']['world']['countries']['order']=$order;
  $_SESSION['state']['world']['countries']['order_dir']=$order_direction;
  $_SESSION['state']['world']['countries']['nr']=$number_results;
  $_SESSION['state']['world']['countries']['sf']=$start_from;
  $_SESSION['state']['world']['countries']['where']=$where;
  $_SESSION['state']['world']['countries']['f_field']=$f_field;
  $_SESSION['state']['world']['countries']['f_value']=$f_value;






  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
  

if($f_field=='country_code' and $f_value!='')
    $wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'"; 
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 
  $sql="select count(*) as total from kbase.`Country Dimension` $where $wheref  ";
  
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

     
   $rtext=$total_records." ".ngettext('Country','Countries',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     case('country_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
       break;
      case('wregion_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
       break;
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='population')
     $order='`Country Population`';
 elseif($order=='gnp')
     $order='`Country GNP`';
     else
      $order='`Country Name`';





   $adata=array();
 $sql="select  `Country Postal Code Format`,`Country Postal Code Regex`,`World Region Code`,`World Region`,`Country GNP`,`Country Population`,`Country Code`,`Country Name`,`Country 2 Alpha Code` from kbase.`Country Dimension` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

    
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
       $wregion=sprintf('<a href="wregion.php?country=%s">%s</a>',$row['World Region Code'],$row['World Region']);
    $country_name=sprintf('<a href="region.php?country=%s">%s</a>',$row['Country 2 Alpha Code'],$row['Country Name']);
        $country_code=sprintf('<a href="region.php?country=%s">%s</a>',$row['Country 2 Alpha Code'],$row['Country Code']);
        $country_flag=sprintf('<img  src="art/flags/%s.gif" alt="">',strtolower($row['Country 2 Alpha Code']));

if($row['Country Population']<100000){
$population='>0.1M';
}else{
$population=number($row['Country Population']/1000000,1).'M';
}
if($row['Country GNP']=='')
$gnp='ND';
elseif($row['Country GNP']<1000)
$gnp='$'.number($row['Country GNP'],0);
else
$gnp='$'.number($row['Country GNP']/1000,0).'k';

     $adata[]=array(
  //    'plain_name'=>$row['Country Name'],
	//	  'plain_code'=>$row['Country Code'],
		   'name'=>$country_name,
		  'code'=>$country_code,
		  'flag'=>$country_flag,
        'population'=>$population,
        'gnp'=>$gnp,
        'wregion'=>$wregion,
        'code3a'=>$row['Country Code'],
        'code2a'=>$row['Country 2 Alpha Code'],
         'plain_name'=>$row['Country Name'],
          'postal_regex'=>$row['Country Postal Code Regex'],
              'postcode_help'=>$row['Country Postal Code Format'],
       

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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}



// -----------------------------------starts for postal code----------------------------

function list_postal_code(){
 $conf=$_SESSION['state']['world']['postal_code'];
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


  $_SESSION['state']['world']['postal_code']['order']=$order;
  $_SESSION['state']['world']['postal_code']['order_dir']=$order_direction;
  $_SESSION['state']['world']['postal_code']['nr']=$number_results;
  $_SESSION['state']['world']['postal_code']['sf']=$start_from;
  $_SESSION['state']['world']['postal_code']['where']=$where;
  $_SESSION['state']['world']['postal_code']['f_field']=$f_field;
  $_SESSION['state']['world']['postal_code']['f_value']=$f_value;






  $where=sprintf('where `Customer Main Postal Code`!="" ');


  $filter_msg='';
  $wheref='';
  

if($f_field=='country_code' and $f_value!='')
    $wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'"; 
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 elseif($f_field=='postal_code' and $f_value!='')
    $wheref.=" and  `Customer Main Postal Code` like '".addslashes($f_value)."%'";     
 
  $sql="select count(DISTINCT `Customer Main Postal Code`) as total from `Customer Dimension` $where $wheref  ";

     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(DISTINCT `Customer Main Postal Code`) as total from `Customer Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('Postal Code','Postal Codes',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     case('country_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
       break;
      case('wregion_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
       break;
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  


      case('postal_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any postal code with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('postal codes with code like')." <b>$f_value</b>)";
       break;  
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='times_used')
     $order='`times_used`';
 else if($order=='name')
     $order='`Customer Main Country`';
     else
      $order='`Customer Main Postal Code`';





   $adata=array();
 $sql="select  count(*) times_used,`Customer Main Postal Code`,`Customer Main Country 2 Alpha Code`,`Customer Main Country` from `Customer Dimension` $where $wheref  group by `Customer Main Postal Code` order by $order $order_direction  limit $start_from,$number_results;";

 
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
    $country_name=sprintf('<a href="region.php?country=%s">%s</a>',$row['Customer Main Country 2 Alpha Code'],$row['Customer Main Country']);
        $country_flag=sprintf('<img  src="art/flags/%s.gif" alt="">',strtolower($row['Customer Main Country 2 Alpha Code']));


     $adata[]=array(
  
		   'name'=>$country_name,
		  'code'=>$row['Customer Main Postal Code'],
		  'flag'=>$country_flag,
    'times_used'=>number($row['times_used']),
       

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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

// -----------------------------------ends  for postal code----------------------------




// -----------------------------------starts for city code----------------------------

function list_city(){
 $conf=$_SESSION['state']['world']['city'];
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


  $_SESSION['state']['world']['city']['order']=$order;
  $_SESSION['state']['world']['city']['order_dir']=$order_direction;
  $_SESSION['state']['world']['city']['nr']=$number_results;
  $_SESSION['state']['world']['city']['sf']=$start_from;
  $_SESSION['state']['world']['city']['where']=$where;
  $_SESSION['state']['world']['city']['f_field']=$f_field;
  $_SESSION['state']['world']['city']['f_value']=$f_value;






  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
  

if($f_field=='country_code' and $f_value!='')
    $wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'"; 
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 elseif($f_field=='postal_code' and $f_value!='')
    $wheref.=" and  `Customer Main Town` like '".addslashes($f_value)."%'";     
 
  $sql="select count(DISTINCT `Customer Main Town`) as total from `Customer Dimension` $where $wheref  ";

     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(DISTINCT `Customer Main Town`) as total from `Customer Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('City','Cities',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     case('country_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
       break;
      case('wregion_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
       break;
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  


      case('postal_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any postal code with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('postal codes with code like')." <b>$f_value</b>)";
       break;  
      case('city'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any city with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('city with code like')." <b>$f_value</b>)";
       break;  
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='population')
     $order='`Country Population`';
 elseif($order=='gnp')
     $order='`Country GNP`';
     else
      $order='`Customer Main Country`';





   $adata=array();
 $sql="select  * from `Customer Dimension` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

 
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
     //  $wregion=sprintf('<a href="wregion.php?country=%s">%s</a>',$row['World Region Code'],$row['World Region']);
    $country_name=sprintf('<a href="region.php?country=%s">%s</a>',$row['Customer Main Country 2 Alpha Code'],$row['Customer Main Country']);
       // $country_code=sprintf('<a href="region.php?country=%s">%s</a>',$row['Customer Main Postal Code'],$row['Customer Main Country Code']);
        $country_flag=sprintf('<img  src="art/flags/%s.gif" alt="">',strtolower($row['Customer Main Country 2 Alpha Code']));

/*if($row['Country Population']<100000){
$population='>0.1M';
}else{
$population=number($row['Country Population']/1000000,1).'M';
}
if($row['Country GNP']=='')
$gnp='ND';
elseif($row['Country GNP']<1000)
$gnp='$'.number($row['Country GNP'],0);
else
$gnp='$'.number($row['Country GNP']/1000,0).'k';
*/
     $adata[]=array(
  //    'plain_name'=>$row['Country Name'],
	//	  'plain_code'=>$row['Country Code'],
		   'name'=>$country_name,
		  'city'=>$row['Customer Main Town'],
		  'flag'=>$country_flag,
       // 'population'=>$population,
        //'gnp'=>$gnp,
       // 'wregion'=>$wregion,
       // 'code3a'=>$row['Country Code'],
       // 'code2a'=>$row['Country 2 Alpha Code'],
       //  'plain_name'=>$row['Country Name'],
       //   'postal_regex'=>$row['Country Postal Code Regex'],
         //     'postcode_help'=>$row['Country Postal Code Format'],
       

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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

// -----------------------------------ends  for city code----------------------------


// -----------------------------------starts for department----------------------------

function list_department(){
 $conf=$_SESSION['state']['world']['department'];
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


  $_SESSION['state']['world']['department']['order']=$order;
  $_SESSION['state']['world']['department']['order_dir']=$order_direction;
  $_SESSION['state']['world']['department']['nr']=$number_results;
  $_SESSION['state']['world']['department']['sf']=$start_from;
  $_SESSION['state']['world']['department']['where']=$where;
  $_SESSION['state']['world']['department']['f_field']=$f_field;
  $_SESSION['state']['world']['department']['f_value']=$f_value;






  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
  

if($f_field=='country_code' and $f_value!='')
    $wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'"; 
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 elseif($f_field=='city' and $f_value!='')
    $wheref.=" and  `Customer Main Town` like '".addslashes($f_value)."%'";     
  elseif($f_field=='department' and $f_value!='')
    $wheref.=" and  `Product Department Name` like '".addslashes($f_value)."%'"; 
    
  $sql="select count(DISTINCT `Product Department Name`) as total from `Product Department Dimension` $where $wheref  ";

     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(DISTINCT `Product Department Name`) as total from `Product Department Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('Department','Departments',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     case('country_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
       break;
      case('wregion_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
       break;
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  


      case('postal_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any postal code with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('postal codes with code like')." <b>$f_value</b>)";
       break;  
      case('city'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any city with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('city with code like')." <b>$f_value</b>)";
       break;  
      case('department_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('department with code like')." <b>$f_value</b>)";
       break;  
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='population')
     $order='`Country Population`';
 elseif($order=='gnp')
     $order='`Country GNP`';
     else
      $order='`Product Department Name`';





   $adata=array();
 $sql="select  * from `Product Department Dimension` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

 
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
     //  $wregion=sprintf('<a href="wregion.php?country=%s">%s</a>',$row['World Region Code'],$row['World Region']);
  //  $country_name=sprintf('<a href="region.php?country=%s">%s</a>',$row['Customer Main Country 2 Alpha Code'],$row['Customer Main Country']);
       // $country_code=sprintf('<a href="region.php?country=%s">%s</a>',$row['Customer Main Postal Code'],$row['Customer Main Country Code']);
   //     $country_flag=sprintf('<img  src="art/flags/%s.gif" alt="">',strtolower($row['Customer Main Country 2 Alpha Code']));

/*if($row['Country Population']<100000){
$population='>0.1M';
}else{
$population=number($row['Country Population']/1000000,1).'M';
}
if($row['Country GNP']=='')
$gnp='ND';
elseif($row['Country GNP']<1000)
$gnp='$'.number($row['Country GNP'],0);
else
$gnp='$'.number($row['Country GNP']/1000,0).'k';
*/
     $adata[]=array(
  //    'plain_name'=>$row['Country Name'],
	//	  'plain_code'=>$row['Country Code'],
		   'store_code'=>$row['Product Department Store Code'],
		  'department_name'=>$row['Product Department Name'],
		  'department_code'=>$row['Product Department Code'],
       // 'population'=>$population,
        //'gnp'=>$gnp,
       // 'wregion'=>$wregion,
       // 'code3a'=>$row['Country Code'],
       // 'code2a'=>$row['Country 2 Alpha Code'],
       //  'plain_name'=>$row['Country Name'],
       //   'postal_regex'=>$row['Country Postal Code Regex'],
         //     'postcode_help'=>$row['Country Postal Code Format'],
       

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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

// -----------------------------------ends  for department----------------------------


// -----------------------------------starts for family----------------------------

function list_family(){
 $conf=$_SESSION['state']['world']['family'];
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


  $_SESSION['state']['world']['family']['order']=$order;
  $_SESSION['state']['world']['family']['order_dir']=$order_direction;
  $_SESSION['state']['world']['family']['nr']=$number_results;
  $_SESSION['state']['world']['family']['sf']=$start_from;
  $_SESSION['state']['world']['family']['where']=$where;
  $_SESSION['state']['world']['family']['f_field']=$f_field;
  $_SESSION['state']['world']['family']['f_value']=$f_value;






  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
  

if($f_field=='country_code' and $f_value!='')
    $wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'"; 
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 elseif($f_field=='city' and $f_value!='')
    $wheref.=" and  `Customer Main Town` like '".addslashes($f_value)."%'";     
  elseif($f_field=='family_name' and $f_value!='')
    $wheref.=" and  `Product Family Name` like '".addslashes($f_value)."%'"; 
    
  $sql="select count(DISTINCT `Product Family Name`) as total from `Product Family Dimension` $where $wheref  ";

     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(DISTINCT `Product Family Name`) as total from `Product Family Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('Family','Families',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     case('country_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
       break;
      case('wregion_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
       break;
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  


      case('postal_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any postal code with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('postal codes with code like')." <b>$f_value</b>)";
       break;  
      case('city'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any city with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('city with code like')." <b>$f_value</b>)";
       break;  
      case('department_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with name")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('department with name like')." <b>$f_value</b>)";
       break;  
      case('family_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with name")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('family with name like')." <b>$f_value</b>)";
       break;  
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='population')
     $order='`Country Population`';
 elseif($order=='gnp')
     $order='`Country GNP`';
     else
      $order='`Product Family Name`';





   $adata=array();
 $sql="select  * from `Product Family Dimension` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

 
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
     //  $wregion=sprintf('<a href="wregion.php?country=%s">%s</a>',$row['World Region Code'],$row['World Region']);
  //  $country_name=sprintf('<a href="region.php?country=%s">%s</a>',$row['Customer Main Country 2 Alpha Code'],$row['Customer Main Country']);
       // $country_code=sprintf('<a href="region.php?country=%s">%s</a>',$row['Customer Main Postal Code'],$row['Customer Main Country Code']);
   //     $country_flag=sprintf('<img  src="art/flags/%s.gif" alt="">',strtolower($row['Customer Main Country 2 Alpha Code']));

/*if($row['Country Population']<100000){
$population='>0.1M';
}else{
$population=number($row['Country Population']/1000000,1).'M';
}
if($row['Country GNP']=='')
$gnp='ND';
elseif($row['Country GNP']<1000)
$gnp='$'.number($row['Country GNP'],0);
else
$gnp='$'.number($row['Country GNP']/1000,0).'k';
*/
     $adata[]=array(
  //    'plain_name'=>$row['Country Name'],
	//	  'plain_code'=>$row['Country Code'],
		   'store_code'=>$row['Product Family Store Code'],
		  'family_name'=>$row['Product Family Name'],
		  'family_code'=>$row['Product Family Code'],
       // 'population'=>$population,
        //'gnp'=>$gnp,
       // 'wregion'=>$wregion,
       // 'code3a'=>$row['Country Code'],
       // 'code2a'=>$row['Country 2 Alpha Code'],
       //  'plain_name'=>$row['Country Name'],
       //   'postal_regex'=>$row['Country Postal Code Regex'],
         //     'postcode_help'=>$row['Country Postal Code Format'],
       

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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

// -----------------------------------ends  for family----------------------------

// -----------------------------------starts for products----------------------------

function list_product(){
 $conf=$_SESSION['state']['world']['product'];
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


  $_SESSION['state']['world']['product']['order']=$order;
  $_SESSION['state']['world']['product']['order_dir']=$order_direction;
  $_SESSION['state']['world']['product']['nr']=$number_results;
  $_SESSION['state']['world']['product']['sf']=$start_from;
  $_SESSION['state']['world']['product']['where']=$where;
  $_SESSION['state']['world']['product']['f_field']=$f_field;
  $_SESSION['state']['world']['product']['f_value']=$f_value;






  $where=sprintf('where true ');


  $filter_msg='';
  $wheref='';
  

if($f_field=='country_code' and $f_value!='')
    $wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'"; 
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 elseif($f_field=='city' and $f_value!='')
    $wheref.=" and  `Customer Main Town` like '".addslashes($f_value)."%'";     
  elseif($f_field=='product_name' and $f_value!='')
    $wheref.=" and  `Product Name` like '".addslashes($f_value)."%'"; 
    
  $sql="select count(DISTINCT `Product Name`) as total from `Product Dimension` $where $wheref  ";

     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(DISTINCT `Product Name`) as total from `Product Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('Product','Products',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     case('country_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
       break;
      case('wregion_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
       break;
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  


      case('postal_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any postal code with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('postal codes with code like')." <b>$f_value</b>)";
       break;  
      case('city'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any city with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('city with code like')." <b>$f_value</b>)";
       break;  
      case('department_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('department with code like')." <b>$f_value</b>)";
       break;  
      case('family_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('family with code like')." <b>$f_value</b>)";
       break;  
      case('product_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('product with code like')." <b>$f_value</b>)";
       break;  
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='population')
     $order='`Country Population`';
 elseif($order=='gnp')
     $order='`Country GNP`';
     else
      $order='`Product Name`';





   $adata=array();
 $sql="select  * from `Product Dimension` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

 
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
  
     $adata[]=array(
 
		  'product_name'=>$row['Product Short Description'],
		  'product_code'=>$row['Product Code'],
    

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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

// -----------------------------------ends  for product----------------------------
function list_world_regions(){
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


  $_SESSION['state']['world']['wregions']['order']=$order;
  $_SESSION['state']['world']['wregions']['order_dir']=$order_direction;
  $_SESSION['state']['world']['wregions']['nr']=$number_results;
  $_SESSION['state']['world']['wregions']['sf']=$start_from;
  $_SESSION['state']['world']['wregions']['where']=$where;
  $_SESSION['state']['world']['wregions']['f_field']=$f_field;
  $_SESSION['state']['world']['wregions']['f_value']=$f_value;

 



  $where=sprintf('where `World Region Code`!="UNKN"    ');


  $filter_msg='';
  $wheref='';
 

 if($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'"; 
 elseif($f_field=='wregion_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 
  $sql="select count(Distinct  `World Region Code`) as total from kbase.`Country Dimension` $where $wheref  ";
  
     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(Distinct  `World Region Code`) as total from kbase.`Country Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('Region','Regions',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     
      case('wregion_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
       break;
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  
     }







  $_order=$order;
   $_dir=$order_direction;

   
   if($order=='wregion_code' )
     $order='`World Region Code`';
      elseif($order=='population' )
     $order='`Population`';
        elseif($order=='gnp' )
     $order='`GNP`';
   else
     $order='`World Region`';
   


   



   $adata=array();
 $sql="select group_concat(concat('<img src=\"art/flags/',lower(`Country 2 Alpha Code`),'.gif\"> ') separator ' ') as flags, count(*) as Countries,sum(`Country GNP`) as GNP,sum(`Country Population`) as Population, `World Region`,`World Region Code` from kbase.`Country Dimension` $where $wheref group by `World Region Code` order by $order $order_direction  limit $start_from,$number_results;";

   // print $sql;
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
$wregion_name=sprintf('<a href="region.php?wregion=%s">%s</a>',$row['World Region Code'],$row['World Region']);
    $wregion_code=sprintf('<a href="region.php?wregion=%s">%s</a>',$row['World Region Code'],$row['World Region Code']);
    if($row['Population']<100000){
$population='>0.1M';
}else{
$population=number($row['Population']/1000000,1).'M';
}
if($row['GNP']=='')
$gnp='ND';
elseif($row['GNP']<1000)
$gnp='$'.number($row['GNP'],0);
else
$gnp='$'.number($row['GNP']/1000,0).'k';
    
    $adata[]=array(
		   'wregion_name'=>$wregion_name,
		 'wregion_code'=>$wregion_code,
		 'countries'=>number($row['Countries']),
        'population'=>$population,
        'gnp'=>$gnp,
        'flags'=>$row['flags']
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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

function list_continents(){
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


$_SESSION['state']['world']['continents']['order']=$order;
  $_SESSION['state']['world']['continents']['order_dir']=$order_direction;
  $_SESSION['state']['world']['continents']['nr']=$number_results;
  $_SESSION['state']['world']['continents']['sf']=$start_from;
  $_SESSION['state']['world']['continents']['where']=$where;
  $_SESSION['state']['world']['continents']['f_field']=$f_field;
  $_SESSION['state']['world']['continents']['f_value']=$f_value;



 $where=sprintf('where `Country Code`!="UNK" ');


  $filter_msg='';
  $wheref='';
  

 if($f_field=='continent_code' and $f_value!='')
    $wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";     
 
  $sql="select count(Distinct `Continent Code`) as total from kbase.`Country Dimension` $where $wheref  ";
  
     $res=mysql_query($sql);
    if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total=$row['total'];
     }
     mysql_free_result($res);
     if($wheref==''){
       $filtered=0;
       $total_records=$total;
     } else{
       $sql="select count(Distinct `Continent Code`) as total from kbase.`Country Dimension`  $where   ";
       $res=mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	 $total_records=$row['total'];
	 $filtered=$total_records-$total;
       }
  mysql_free_result($res);
   }

     
   $rtext=$total_records." ".ngettext('Continent','Continents',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
    
       case('continent_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
       break;  
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='population')
     $order='`Population`';
 elseif($order=='gnp')
     $order='`GNP`';
      elseif($order=='continent_code')
     $order='`Continent Code`';
     else
      $order='`Continent`';







   $adata=array();
 $sql="select  count(*) as Countries,sum(`Country GNP`) as GNP,sum(`Country Population`) as Population,`Continent`,`Continent Code` from kbase.`Country Dimension` $where $wheref  group by `Continent Code` order by $order $order_direction  limit $start_from,$number_results;";

 
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
    if($row['Population']<100000){
$population='>0.1M';
}else{
$population=number($row['Population']/1000000,1).'M';
}
if($row['GNP']=='')
$gnp='ND';
elseif($row['GNP']<1000)
$gnp='$'.number($row['GNP'],0);
else
$gnp='$'.number($row['GNP']/1000,0).'k';
$continent_name=sprintf('<a href="region.php?continent=%s">%s</a>',$row['Continent Code'],$row['Continent']);
$continent_code=sprintf('<a href="region.php?continent=%s">%s</a>',$row['Continent Code'],$row['Continent Code']);

     $adata[]=array(
		   'continent_name'=>$continent_name,
		  'continent_code'=>$continent_code,
		 'countries'=>number($row['Countries']),
        'population'=>$population,
        'gnp'=>$gnp,
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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}

function list_countries_in_wregion(){
 $conf=$_SESSION['state']['wregion']['countries'];
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


  $_SESSION['state']['wegion']['countries']['order']=$order;
  $_SESSION['state']['wegion']['countries']['order_dir']=$order_direction;
  $_SESSION['state']['wegion']['countries']['nr']=$number_results;
  $_SESSION['state']['wegion']['countries']['sf']=$start_from;
  $_SESSION['state']['wegion']['countries']['where']=$where;
  $_SESSION['state']['wegion']['countries']['f_field']=$f_field;
  $_SESSION['state']['wegion']['countries']['f_value']=$f_value;






  $where=sprintf('where `World Region Code`=%s ',prepare_mysql($_SESSION['state']['wregion']['code']));


  $filter_msg='';
  $wheref='';
  

if($f_field=='country_code' and $f_value!='')
    $wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
 
 
  $sql="select count(*) as total from kbase.`Country Dimension` $where $wheref  ";
  
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

     
   $rtext=$total_records." ".ngettext('Country','Countries',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
       $rtext_rpp=_('(Showing all)');


  $filter_msg='';

     switch($f_field){
     case('country_code'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
       break;
    
     }





  $_order=$order;
   $_dir=$order_direction;

   
 
   if($order=='population')
     $order='`Country Population`';
 elseif($order=='gnp')
     $order='`Country GNP`';
     else
      $order='`Country Name`';





   $adata=array();
 $sql="select  `World Region Code`,`World Region`,`Country GNP`,`Country Population`,`Country Code`,`Country Name`,`Country 2 Alpha Code` from kbase.`Country Dimension` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

    
   $res=mysql_query($sql);
   
   while($row=mysql_fetch_array($res)) {
       $wregion=sprintf('<a href="wregion.php?country=%s">%s</a>',$row['World Region Code'],$row['World Region']);
    $country_name=sprintf('<a href="region.php?country=%s">%s</a>',$row['Country 2 Alpha Code'],$row['Country Name']);
        $country_code=sprintf('<a href="region.php?country=%s">%s</a>',$row['Country 2 Alpha Code'],$row['Country Code']);
        $country_flag=sprintf('<img  src="art/flags/%s.gif" alt="">',strtolower($row['Country 2 Alpha Code']));

if($row['Country Population']<100000){
$population='>0.1M';
}else{
$population=number($row['Country Population']/1000000,1).'M';
}
if($row['Country GNP']=='')
$gnp='ND';
elseif($row['Country GNP']<1000)
$gnp='$'.number($row['Country GNP'],0);
else
$gnp='$'.number($row['Country GNP']/1000,0).'k';

     $adata[]=array(
		   'name'=>$country_name,
		  'code'=>$country_code,
		  'flag'=>$country_flag,
        'population'=>$population,
        'gnp'=>$gnp,
        'wregion'=>$wregion
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
			 'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
			 )
		   );
     
   echo json_encode($response);
}
