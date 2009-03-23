<?
require_once 'common.php';
require_once 'stock_functions.php';
require_once 'classes/Product.php';
require_once 'classes/Department.php';
require_once 'classes/Family.php';

require_once 'classes/Order.php';
require_once 'classes/Location.php';
require_once 'classes/PartLocation.php';


if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }


if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){

 case('delete_family'):

   if(!isset($_REQUEST['id']))
     return _('Error: no family specificated');
   if(!is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0 )
     return _('Error: wriong family id');
   if(!isset($_REQUEST['delete_type'])  or !($_REQUEST['delete_type']=='delete' or $_REQUEST['delete_type']=='discontinue'  )  )
     return _('Error: delete type no supplied');

   $id=$_REQUEST['id'];
   $family=new Family($id);

   if($_REQUEST['delete_type']=='delete'){

     $family->delete();
   }else if($_REQUEST['delete_type']=='discontinue'){
     $family->discontinue();
   }
   if($family->deleted){
     print 'Ok';
   }else{
     print $family->msg;
   }
   
   
   break;
case('delete_store'):

   if(!isset($_REQUEST['id']))
     return _('Error: no store specificated');
   if(!is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0 )
     return _('Error: wrong store id');
   if(!isset($_REQUEST['delete_type'])  or !($_REQUEST['delete_type']=='delete' or $_REQUEST['delete_type']=='close'  )  )
     return _('Error: delete type no supplied');

   $id=$_REQUEST['id'];
   $store=new Store($id);

   if($_REQUEST['delete_type']=='delete'){

     $store->delete();
   }else if($_REQUEST['delete_type']=='close'){
     $store->close();
   }
   if($store->deleted){
     print 'Ok';
   }else{
     print $store->msg;
   }
   
   
   break;

 case('edit_department'):
   $department=new Department($_REQUEST['id']);
   $department->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));
   
   //   $response= array('state'=>400,'msg'=>print_r($_REQUEST);
   //echo json_encode($response);  
   // exit;
   if($department->updated){
     $response= array('state'=>200,'newvalue'=>$department->newvalue,'key'=>$_REQUEST['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$department->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response);  
   exit;
   break;
 case('edit_store'):
   $store=new Store($_REQUEST['id']);
   $store->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));
     
   if($store->updated){
     $response= array('state'=>200,'newvalue'=>$store->newvalue,'key'=>$_REQUEST['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$store->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response);  
 
   break;

 case('new_store'):
   
   if(isset($_REQUEST['name'])  and  isset($_REQUEST['code'])   ){



     $store=new Store('create',array(
					      'Store Code'=>$_REQUEST['code']
					      ,'Store Name'=>$_REQUEST['name']

					      ));
     if(!$store->new){
       $state='400';
     }else{
       
       $state='200';
     }
     $response=array('state'=>$state,'msg'=>$store->msg);
   }

   else
      $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;
 case('new_department'):
   if(isset($_REQUEST['name'])  and  isset($_REQUEST['code'])   ){
     $store_key=$_SESSION['state']['store']['id'];
     $department=new Department('create',array(
					      'Product Department Code'=>$_REQUEST['code']
					      ,'Product Department Name'=>$_REQUEST['name']
					      ,'Product Department Store Key'=>$store_key
					       ));
     if(!$department->new){
       $state='400';
     }else{
       $state='200';
     }
     $response=array('state'=>$state,'msg'=>$department->msg);
   }
   else
     $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;
 case('new_family'):
 if(isset($_REQUEST['name'])  and  isset($_REQUEST['code'])   ){
     $department_key=$_SESSION['state']['department']['id'];
     
     $family=new Family('create',array(
					      
				       'Product Family Code'=>$_REQUEST['code']
				       ,'Product Family Name'=>$_REQUEST['name']
				       ,'Product Family Description'=>$_REQUEST['description']
				       ,'Product Family Main Department Key'=>$department_key



      
				       ));
     if(!$family->new){
       $state='401';
     }else{
       $state='200';
     }

     $response=array('state'=>$state,'msg'=>$family->msg);


 }
 else
     $response=array('state'=>400,'msg'=>_('Error'));
   echo json_encode($response);
   break;
    case('edit_departments'):
   
   if(!isset($_REQUEST['parent']))
     $parent='store';
   else
     $parent=$_REQUEST['parent'];

   if($parent=='store'){

   $conf=$_SESSION['state']['store']['table'];

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
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];
   
   if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];

   
   if(isset( $_REQUEST['tableid']))
     $tableid=$_REQUEST['tableid'];
   else
    $tableid=0;
   

  if(isset( $_REQUEST['percentages'])){
    $percentages=$_REQUEST['percentages'];
    $_SESSION['state']['store']['percentages']=$percentages;
  }else
    $percentages=$_SESSION['state']['store']['percentages'];
  
  

   if(isset( $_REQUEST['period'])){
    $period=$_REQUEST['period'];
    $_SESSION['state']['store']['period']=$period;
  }else
    $period=$_SESSION['state']['store']['period'];

 if(isset( $_REQUEST['avg'])){
    $avg=$_REQUEST['avg'];
    $_SESSION['state']['store']['avg']=$avg;
  }else
    $avg=$_SESSION['state']['store']['avg'];


$store_id=$_SESSION['state']['store']['id'];



    $_SESSION['state']['store']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    $where=$where.' '.sprintf(" and `Product Department Store Key`=%d",$store_id);
   
 $filter_msg='';
  $wheref='';
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   }else{
     return;
   }



   $sql="select count(*) as total from `Product Department Dimension`   $where $wheref";
   // print $sql;
   $res = mysql_query($sql); 
   if($row=mysql_fetch_array($res)) {
     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total `Product Department Dimension`   $where ";

     $res = $db->query($sql); 
     if($row=$res->fetchRow()) {
      	$total_records=$row['total'];
	$filtered=$total_records-$total;
     }

   }

 $rtext=$total_records." ".ngettext('department','departments',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

   $_dir=$order_direction;
   $_order=$order;
   
  if($order=='name')
    $order='`Product Department Name`';
   elseif($order=='code')
    $order='`Product Department Code`';
  

    $sql="select D.`Product Department Key`,`Product Department Code`,`Product Department Name`,`Product Department For Sale Products`+`Product Department In Process Products`+`Product Department Not For Sale Products`+`Product Department Discontinued Products`+`Product Department Unknown Sales State Products` as Products  from `Product Department Dimension` D  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    $res = mysql_query($sql);
    $adata=array();
    //print "$period";
    while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      if($row['Products']>0){
	$delete='<img src="art/icons/cross.png" /> <span  style="cursor:pointer">'._('Discontinue').'<span>';
	$delete_type='discontinue';
      }else{
	$delete='<img src="art/icons/cross.png" /> <span  style="cursor:pointer">'._('Delete').'<span>';
      $delete_type='delete';
    }


      $adata[]=array(
		     'id'=>$row['Product Department Key'],
		     'name'=>$row['Product Department Name'],
		     'code'=>$row['Product Department Code'],
		     'delete'=>$delete,
		     'delete_type'=>$delete_type
		   );
   }
 







   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'rtext'=>$rtext,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;

case('edit_stores'):
   
   $conf=$_SESSION['state']['stores']['table'];

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
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];
   
   if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];

   
   if(isset( $_REQUEST['tableid']))
     $tableid=$_REQUEST['tableid'];
   else
    $tableid=0;
   

  if(isset( $_REQUEST['percentages'])){
    $percentages=$_REQUEST['percentages'];
    $_SESSION['state']['stores']['percentages']=$percentages;
  }else
    $percentages=$_SESSION['state']['stores']['percentages'];
  
  

   if(isset( $_REQUEST['period'])){
    $period=$_REQUEST['period'];
    $_SESSION['state']['stores']['period']=$period;
  }else
    $period=$_SESSION['state']['stores']['period'];

 if(isset( $_REQUEST['avg'])){
    $avg=$_REQUEST['avg'];
    $_SESSION['state']['stores']['avg']=$avg;
  }else
    $avg=$_SESSION['state']['stores']['avg'];

    $_SESSION['state']['stores']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  // print_r($_SESSION['tables']['families_list']);

  //  print_r($_SESSION['tables']['families_list']);
$where=" ";
   
 $filter_msg='';
  $wheref='';
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


  



   $sql="select count(*) as total from `Store Dimension`   $where $wheref";

   $res = $db->query($sql); 
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select count(*) as total `Store Dimension`   $where ";

     $res = $db->query($sql); 
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }

   $_dir=$order_direction;
   $_order=$order;
   

   if($order=='name')
     $order='`Store Name`';
   else if($order=='code')
     $order='`Store Code`';
   else
     $order='`Store Code`';



 
   $sql="select *  from `Store Dimension`  order by $order $order_direction limit $start_from,$number_results    ";
   
   $res = mysql_query($sql);
   $adata=array();
   //  print "$sql";
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     if($row['Store For Sale Products']>0){
       $delete='<img src="art/icons/cross.png" /> <span conclick="close_store('.$row['Store Key'].')"  id="del_'.$row['Store Key'].'" style="cursor:pointer">'._('Close').'<span>';
       $delete_type='close';
     }else{
       $delete='<img src="art/icons/cross.png" /> <span conclick="delete_store('.$row['Store Key'].')"  id="del_'.$row['Store Key'].'" style="cursor:pointer">'._('Delete').'<span>';
       $delete_type='delete';
     }
     $adata[]=array(
		    'id'=>$row['Store Key']
		    ,'code'=>$row['Store Code']
		    ,'name'=>$row['Store Name']
		    ,'delete'=>$delete
		    ,'delete_type'=>$delete_type
		  );
  }


   $total=mysql_num_rows($res);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
case('edit_families'):
   $conf=$_SESSION['state']['families']['table'];
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
  
  if(isset( $_REQUEST['where']))
    $where=$_REQUEST['where'];
  else
    $where=$conf['where'];

 if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];
   
   if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];



  if(isset( $_REQUEST['percentages'])){
    $percentages=$_REQUEST['percentages'];
    $_SESSION['state']['families']['percentages']=$percentages;
  }else
    $percentages=$_SESSION['state']['families']['percentages'];
  
  

   if(isset( $_REQUEST['period'])){
    $period=$_REQUEST['period'];
    $_SESSION['state']['families']['period']=$period;
  }else
    $period=$_SESSION['state']['families']['period'];

 if(isset( $_REQUEST['avg'])){
    $avg=$_REQUEST['avg'];
    $_SESSION['state']['families']['avg']=$avg;
  }else
    $avg=$_SESSION['state']['families']['avg'];

  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

   if(isset( $_REQUEST['parent'])){
     switch($_REQUEST['parent']){
     case('store'):
       $where=sprintf(' where `Product Family Store Key`=%d',$_SESSION['state']['store']['id']);
       break;
     case('department'):
       $where=sprintf(' left join `Product Family Department Bridge` B on (F.`Product Family Key`=B.`Product Family Key`) where `Product Department Key`=%d',$_SESSION['state']['department']['id']);
       break;
     case('all'):
         $where=sprintf(' where true ');
       break;
     }
   }
   


   $filter_msg='';



  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
  
  
  $_SESSION['state']['families']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  
  
  //  $where.=" and `Product Department Key`=".$id;

  
  
  $filter_msg='';
  $wheref='';
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  
    $sql="select count(*) as total from `Product Family Dimension`   F   $where $wheref";

   $res = $db->query($sql); 
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total  from `Product Family Dimension`  F  $where ";

     $res = $db->query($sql); 
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total; $total_records=$row['total'];
     }

   }
 $rtext=$total_records." ".ngettext('family','families',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
     else
       $rtext_rpp='';
  
  $_order=$order;
  $_dir=$order_direction;
  
  if($order=='code')
    $order='`Product Family Code`';
  elseif($order=='name')
    $order='`Product Family Name`';
  
  $sql="select F.`Product Family Key`,`Product Family Code`,`Product Family Name`,`Product Family For Sale Products`+`Product Family In Process Products`+`Product Family Not For Sale Products`+`Product Family Discontinued Products`+`Product Family Unknown Sales State Products` as Products  from `Product Family Dimension` F  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
  
  $res = mysql_query($sql);
  $adata=array();
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    if($row['Products']>0){
      $delete='<img src="art/icons/cross.png" /> <span xonclick="discontinue_family('.$row['Product Family Key'].')"  id="del_'.$row['Product Family Key'].'" style="cursor:pointer">'._('Discontinue').'<span>';
      $delete_type='discontinue';
    }else{
      $delete='<img src="art/icons/cross.png" /> <span xonclick="delete_family('.$row['Product Family Key'].')"  id="del_'.$row['Product Family Key'].'" style="cursor:pointer">'._('Delete').'<span>';
      $delete_type='delete';
    }
$adata[]=array(
	       'id'=>$row['Product Family Key'],
	       'code'=>$row['Product Family Code'],
	       'name'=>$row['Product Family Name'],
	       'delete'=>$delete,
	       'delete_type'=>$delete_type

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
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
			)
		  );

  echo json_encode($response);
  break;

 default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }
?>