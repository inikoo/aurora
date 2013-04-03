<?php
/*
 File: ar_users.php 

 Ajax Server Anchor for the User Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
require_once 'common.php';
require_once 'ar_common.php';



if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'msg'=>'Non acceptable request (t)');
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
