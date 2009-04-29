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
case('delete_department'):

   if(!isset($_REQUEST['id']))
     return _('Error: no department specificated');
   if(!is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0 )
     return _('Error: wrong department id');
   if(!isset($_REQUEST['delete_type'])  or !($_REQUEST['delete_type']=='delete' or $_REQUEST['delete_type']=='discontinue'  )  )
     return _('Error: delete type no supplied');

   $id=$_REQUEST['id'];
   $department=new Department($id);

   if($_REQUEST['delete_type']=='delete'){

     $department->delete();
   }else if($_REQUEST['delete_type']=='discontinue'){
     $department->close();
   }
   if($department->deleted){
     print 'Ok';
   }else{
     print $department->msg;
   }
   
   
   break;
 case('edit_family'):
   $family=new family($_REQUEST['id']);
   $family->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));
   

   if($family->updated){
     $response= array('state'=>200,'newvalue'=>$family->newvalue,'key'=>$_REQUEST['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$family->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response);  

   break;
case('edit_product'):
   $product=new product($_REQUEST['id']);
   $product->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));
   if($product->updated){
     $response= array('state'=>200,'newvalue'=>$product->newvalue,'key'=>$_REQUEST['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$product->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response);  

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
     $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
     $rtext_rpp='';
   
   
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
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
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
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total `Store Dimension`   $where ";

     $res = $db->query($sql); 
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;$total_records=$row['total'];
     }

   }

    $rtext=$total_records." ".ngettext('store','stores',$total_records);
   if($total_records>$number_results)
     $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
     $rtext_rpp='';


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
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
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
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'filtered'=>$filtered
			)
		  );

  echo json_encode($response);
  break;
case('edit_products'):
   $conf=$_SESSION['state']['products']['table'];
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
    $_SESSION['state']['products']['percentages']=$percentages;
  }else
    $percentages=$_SESSION['state']['products']['percentages'];
  
  

   if(isset( $_REQUEST['period'])){
    $period=$_REQUEST['period'];
    $_SESSION['state']['products']['period']=$period;
  }else
    $period=$_SESSION['state']['products']['period'];

 if(isset( $_REQUEST['avg'])){
    $avg=$_REQUEST['avg'];
    $_SESSION['state']['products']['avg']=$avg;
  }else
    $avg=$_SESSION['state']['products']['avg'];

  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   if(isset( $_REQUEST['parent']))
     $parent=$_REQUEST['parent'];
   else
     $parent=$conf['parent'];

   if(isset( $_REQUEST['mode']))
     $mode=$_REQUEST['mode'];
   else
     $mode=$conf['mode'];
   
    if(isset( $_REQUEST['restrictions']))
     $restrictions=$_REQUEST['restrictions'];
   else
     $restrictions=$conf['restrictions'];



     switch($parent){
     case('store'):
       $where=sprintf(' where `Product Family Store Key`=%d',$_SESSION['state']['store']['id']);
       break;
     case('department'):
       $where=sprintf(' left join `Product Department Bridge` B on (P.`Product Key`=B.`Product Key`) where `Product Department Key`=%d',$_SESSION['state']['department']['id']);
       break;
     case('family'):
       $where=sprintf(' where `Product Family Key`=%d',$_SESSION['state']['family']['id']);
       break;
     case('none'):
       $where=sprintf(' where true ');
       break;
     }
     $group='';
     switch($mode){
     case('same_code'):
       $where.=sprintf(" and `Product Most Recent`='Yes' ");
       break;
     case('same_id'):
       $group=' group by `Product ID`';
       break;
     }
   
     switch($restrictions){
     case('forsale'):
       $where.=sprintf(" and `Product Sales State`='For Sale'  ");
       break;
     case('editable'):
       $where.=sprintf(" and `Product Sales State` in ('For Sale','In process','Unknown')  ");
       break;
     case('notforsale'):
       $where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
       break;
     case('discontinued'):
       $where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
       break;
     case('all'):

       break;
     }


   $filter_msg='';



  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
  
  
  $_SESSION['state']['products']['table']=array(
						'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
);
  
  
  //  $where.=" and `Product Department Key`=".$id;

  
  
  $filter_msg='';
  $wheref='';
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  
    $sql="select count(*) as total from `Product Dimension`   P   $where $wheref";

   $res = $db->query($sql); 
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total  from `Product Dimension`  P  $where ";

     $res = $db->query($sql); 
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total; $total_records=$row['total'];
     }

   }
   $rtext=$total_records." ".ngettext('product','products',$total_records);
   if($total_records>$number_results)
     $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
     $rtext_rpp='';
   
   $_order=$order;
   $_dir=$order_direction;
   
  if($order=='code')
    $order='`Product Code`';
  elseif($order=='name')
    $order='`Product Name`';
  else
    $order='`Product Code`';

  $sql="select *  from `Product Dimension` P  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
  
  $res = mysql_query($sql);
  $adata=array();
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    if($row['Product Total Quantity Ordered']==0 and  $row['Product Total Quantity Invoiced']==0 and  $row['Product Total Quantity Delivered']==0  ){
      $delete='<img src="art/icons/cross.png" /> <span xonclick="delete_family('.$row['Product Key'].')"  id="del_'.$row['Product Key'].'" style="cursor:pointer">'._('Delete').'<span>';
      $delete_type='delete';
    }else{
  $delete='<img src="art/icons/cross.png" /> <span xonclick="discontinue_family('.$row['Product Key'].')"  id="del_'.$row['Product Key'].'" style="cursor:pointer">'._('Discontinue').'<span>';
      $delete_type='discontinue';
    }

    if($row['Product RRP']!=0 and is_numeric($row['Product RRP']))
      $customer_margin=_('CM').' '.number(100*($row['Product RRP']-$row['Product Price'])/$row['Product RRP'],1).'%';
    else
      $customer_margin=_('Not for resale');
    
    if($row['Product Price']!=0 and is_numeric($row['Product Cost']))
      $margin=number(100*($row['Product Price']-$row['Product Cost'])/$row['Product Price'],1).'%';
    else
      $margin=_('ND');
    global $myconf;
    $in_common_currency=$myconf['currency_code'];
    $in_common_currency_price='';
    if($row['Product Currency']!= $in_common_currency){
      if(!isset($exchange[$row['Product Currency']])){
	$exchange[$row['Product Currency']]=currency_conversion($row['Product Currency'],$in_common_currency);

      }
      $in_common_currency_price='('.money($exchange[$row['Product Currency']]*$row['Product Price']).') ';
      
    }



$adata[]=array(
	       'id'=>$row['Product Key'],
	       'code'=>$row['Product Code'],
	       'name'=>$row['Product Name'],
	       'sdescription'=>$row['Product Special Characteristic'],
	       'famsdescription'=>$row['Product Family Special Characteristic'],
	       'units'=>$row['Product Units Per Case'],
	       'units_info'=>$row['Product Units Per Case'],

	       'unit_type'=>$row['Product Unit Type'],
	       'price'=>money($row['Product Price'],$row['Product Currency']),
	       'unit_price'=>money($row['Product Price']/$row['Product Units Per Case'],$row['Product Currency']),
	       'margin'=>$margin,

	       'price_info'=>$in_common_currency_price,

	       'unit_rrp'=>money(($row['Product RRP']/$row['Product Units Per Case']),$row['Product Currency']),
	       'rrp_info'=>$customer_margin,

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
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'filtered'=>$filtered
			)
		  );

  echo json_encode($response);
  break;
 default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }





// case('update_department_name'):
   
//    if(isset($_REQUEST['id'])  and  isset($_REQUEST['value'])  and is_numeric($_REQUEST['id']) and $_REQUEST['value']!=''){
//      $name=addslashes($_REQUEST['value']);
//      $id=$_REQUEST['id'];
//      $sql=sprintf("update product_department set name='%s' where id=%d ",$name,$id);
//      $affected=& $db->exec($sql);
//      if (PEAR::isError($affected)) {
//        if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
// 	 $resp=_('Error: Another department has the same name').'.';
//        else
// 	 $resp=_('Unknown Error').'.';
//        $state='400';
//      }else{
//        $resp= $affected;
//        $state='200';
//      }
//      $response=array('state'=>$state,'resp'=>_($resp));
//    }

//    else
//       $response=array('state'=>400,'resp'=>_('Error'));
//    echo json_encode($response);
//    break;

//  case('update_family_name'):
   
//    if(isset($_REQUEST['id'])  and  isset($_REQUEST['value'])  and is_numeric($_REQUEST['id']) and $_REQUEST['value']!=''){
//      $name=addslashes($_REQUEST['value']);
//      $id=$_REQUEST['id'];
//      $sql=sprintf("update product_group set name='%s' where id=%d ",$name,$id);
//      $affected=& $db->exec($sql);
//      if (PEAR::isError($affected)) {
//        if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
// 	 $resp=_('Error: Another family has the same name').'.';
//        else
// 	 $resp=_('Unknown Error').'.';
//        $state='400';
//      }else{
//        $resp= $affected;
//        $state='200';
//      }
//      $response=array('state'=>$state,'resp'=>_($resp));
//    }

//    else
//       $response=array('state'=>400,'resp'=>_('Error'));
//    echo json_encode($response);
//    break;
//  case('update_family_description'):
   
//    if(isset($_REQUEST['id'])  and  isset($_REQUEST['value'])  and is_numeric($_REQUEST['id']) and $_REQUEST['value']!=''){
//      $name=addslashes($_REQUEST['value']);
//      $id=$_REQUEST['id'];
//      $sql=sprintf("update product_group set description='%s' where id=%d ",$name,$id);
//      $affected=& $db->exec($sql);
//      if (PEAR::isError($affected)) {
//        if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
// 	 $resp=_('Error: Another family has the same name and description').'.';
//        else
// 	 $resp=_('Unknown Error').'.';
//        $state='400';
//      }else{
//        $resp= $affected;
//        $state='200';
//      }
//      $response=array('state'=>$state,'resp'=>_($resp));
//    }

//    else
//       $response=array('state'=>400,'resp'=>_('Error'));
//    echo json_encode($response);
//    break;





//  case('add_tosupplier'):
   
//    if( isset($_REQUEST['supplier_id'])    and is_numeric($_REQUEST['supplier_id']) and      isset($_REQUEST['product_id'])    and is_numeric($_REQUEST['product_id'])    ){

//      $product_id=$_REQUEST['product_id'];
//      $suppiler_id=$_REQUEST['supplier_id'];


//      if(isset($_REQUEST['code']) and  $_REQUEST['code']!='')
//        $code="'".$_REQUEST['code']."'";
//      else
//        $code='NULL';
     
//      if(isset($_REQUEST['price']) and  $_REQUEST['price']!='')
//        $price="'".$_REQUEST['price']."'";
//      else
//        $price='NULL';

     
//      $p2s_id=addtosupplier($product_id,$suppiler_id);

//      if($p2s_id>0){
       
//        $sql=sprintf("update  product2supplier set sup_code=%s , price=%s where id=%d",$code,$price,$p2s_id);
//        //       print "$sql";
//        $affected=& $db->exec($sql);
//      }
//      $state='200';
//      $resp='OK';
     
//      $response=array('state'=>$state,'resp'=>_($resp),'product_id'=>$product_id);
//    }

//    else
//       $response=array('state'=>400,'resp'=>_('Error'));
//    echo json_encode($response);
//    break;

//  case('set_stock'):
   
//    if( isset($_REQUEST['product_id'])    and is_numeric($_REQUEST['product_id']) and      isset($_REQUEST['qty'])    and is_numeric($_REQUEST['qty'])   and      isset($_REQUEST['author'])  ){

     

//      $product_id=$_REQUEST['product_id'];
//      $qty=$_REQUEST['qty'];
     
//      $date=split('-',$_REQUEST['date']);
//      if(count($date)==3 and is_numeric($date[0]) and is_numeric($date[0]) and is_numeric($date[0]) ){
//       $f_date=sprintf("%02d-%02d-%d",$date[0],$date[1],$date[2]);
//       $date=join ('-',array_reverse($date));
//      }else{
//        $response=array('state'=>400,'resp'=>_('Error: in date format, should be (DD-MM-YYYY)'));
//        echo json_encode($response);
//        break;
//      }
     

//      $time=split(':',$_REQUEST['time']);

//      if( count($time)!=2 or  !is_numeric($time[0]) or !is_numeric($time[1]) or $time[0]>23 or  $time[0]<0  or $time[1]>59 or  $time[1]<0    ){
//        $response=array('state'=>400,'resp'=>_('Error: in time format, should be (HH:MM)'));
//        echo json_encode($response);
//        break;
//      }
//      $time=join (':',$time);
//      $datetime=$date.' '.$time.':00';


//      $author=$_REQUEST['author'];
//      if(!is_numeric($author) or $author<0){
//        $response=array('state'=>400,'resp'=>'Error; bad author_id');
//        echo json_encode($response);
//        break;
//      }

//      if($qty<0){
//        $state='400';
//        $resp='Error, you can not set negative stock.';
//      }else{
//        $sql=sprintf("insert into in_out(tipo,date_creation,quantity,product_id,date,author) values (2,NOW(),'%s',%d,'%s',%d)",$qty,$product_id,$datetime,$author);
//        $db->exec($sql);
//        $stock=set_stock($product_id);
//        $state='200';
//        $resp='OK';
//      }
//      $response=array('state'=>$state,'resp'=>_($resp),'stock'=>$stock);
//    }

//    else
//      $response=array('state'=>400,'resp'=>_('Error'));
// echo json_encode($response);
// break;
//  case('new_product'):
   
//    if(
//        isset($_REQUEST['description'])  
//       and  isset($_REQUEST['family_id'])    
//        and  isset($_REQUEST['code'])  
//        and  isset($_REQUEST['units'])  
//        and  isset($_REQUEST['units_tipo'])  
//        and  isset($_REQUEST['price'])  

//        and $_REQUEST['description']!='' 
//        and $_REQUEST['code']!=''    

//        and is_numeric($_REQUEST['price'])  
//        //and is_numeric($_REQUEST['units_tipo'])  

//        and is_numeric($_REQUEST['units'])  
//        and is_numeric($_REQUEST['family_id'])  


//       ){
//      $code=addslashes($_REQUEST['code']);
//      $description=addslashes($_REQUEST['description']);
//      $family_id=$_REQUEST['family_id'];
//      if(isset($_REQUEST['rrp']) and is_numeric($_REQUEST['rrp']))
//        $rrp=$_REQUEST['rrp'];
//      else
//        $rrp='NULL';
     
//      if(isset($_REQUEST['units_carton']) and is_numeric($_REQUEST['units_carton']))
//        $units_carton=$_REQUEST['units_carton'];
//      else
//         $units_carton='NULL';
     
//      $ncode=$code;
//      $c=split('-',$code);
//      if(count($c)==2){
//        if(is_numeric($c[1]))
// 	 $ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
//        else
// 	 $ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
//      }     

//      $sql=sprintf("insert into  product (ncode,rrp,units_carton,units,units_tipo,price,description,code,group_id,first_date) values ('%s',%s,%s,'%s',%d,'%s','%s','%s',%d,NOW())",$ncode,$rrp,$units_carton,$_REQUEST['units'],$_REQUEST['units_tipo'],$_REQUEST['price'],$description,$code,$_REQUEST['family_id']);
//      $affected=& $db->exec($sql);
//      //     print "$sql\n";
//      if (PEAR::isError($affected)) {
//        if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
// 	 $resp=_('Error: Another product has the same code').'.';
//        else
// 	 $resp=_('Unknown Error').'.';
//        $state='400';
//        $data=array();
//      }else{
       
//        $product_id = $db->lastInsertID();

//        $sql=sprintf("insert into inventory(fuzzy,date_start,date_end,name) values (1,NOW,NOW,'%s',)",_('New product'));
//        $db->exec($sql);
//        $inv_id = $db->lastInsertID();
//        $sql=sprintf("insert into inventory_item (product_id,inventory_id,fecha) values (%d,,%d,NOW)",$product_id,$inv_id);
//        $db->exec($sql);

//        fix_todotransaction($product_id);
//        set_stock($product_id);
//        set_available($product_id);
	      
//        // --------Supplier --------------

//        if( isset($_REQUEST['supplier_id'])    and is_numeric($_REQUEST['supplier_id']) and      is_numeric($product_id)    ){
	 

// 	 $suppiler_id=$_REQUEST['supplier_id'];

	 
// 	 if(isset($_REQUEST['scode']) and  $_REQUEST['scode']!='')
// 	   $code="'".$_REQUEST['scode']."'";
// 	 else
// 	   $code='NULL';
     
// 	 if(isset($_REQUEST['sprice']) and  $_REQUEST['sprice']!='')
// 	   $price="'".$_REQUEST['sprice']."'";
// 	 else
// 	   $price='NULL';
	 
	 
// 	 $p2s_id=addtosupplier($product_id,$suppiler_id);
	 
// 	 if($p2s_id>0){
	   
// 	   $sql=sprintf("update  product2supplier set sup_code=%s , price=%s where id=%d",$code,$price,$p2s_id);
	   
// 	   $affected=& $db->exec($sql);
// 	 }
//        }



//        // ============================


//        //normalize product
//        set_sales($product_id);
//        //normalize family

//        //normalize supplier

       


//        $resp='ok';
//        $data= array(
		    
// 		    'id'=>$product_id
// 		    ,'code'=>$_REQUEST['code']
// 		    ,'description'=>$_REQUEST['description']
// 		    ,'units'=>$_REQUEST['units']
// 		    ,'price'=>$_REQUEST['price']
// 		    ,'units_tipo'=>$_REQUEST['units_tipo']
// 		    ,'stock'=>0
// 		    ,'available'=>0
// 		    ,'stock_value'=>0
// 		    ,'tsall'=>0
// 		    ,'tsy'=>0
// 		    ,'tsq'=>0
// 		    ,'tsm'=>0
// 		    );
//        $state='200';
//      }
//      $response=array('state'=>$state,'resp'=>_($resp),'data'=>$data);
//    }

//    else
//       $response=array('state'=>400,'resp'=>_('Error, please check that all the fields are filled'));
//    echo json_encode($response);
//    break;

//  case('edit_product'):
   
//    if(
//        isset($_REQUEST['description'])  
//        and  isset($_REQUEST['id'])    
//        and  isset($_REQUEST['code'])  
//        and  isset($_REQUEST['units'])  
//       and  isset($_REQUEST['units_tipo'])  
//        and  isset($_REQUEST['price'])  
       
//        and $_REQUEST['description']!='' 
//        and $_REQUEST['code']!=''    
       
//        and is_numeric($_REQUEST['price'])  
//        and is_numeric($_REQUEST['units_tipo'])  
       
//        and is_numeric($_REQUEST['units'])  
//        and is_numeric($_REQUEST['id'])  

       
//       ){
//      // Get previous values
     
//      $id=$_REQUEST['id'];
//      $sql=sprintf("select code,description  from product where id=%d",$id);

//      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
//      if(!$olddata=$res->fetchRow()) {
//        $response=array('state'=>400,'resp'=>_("Error, product don't found"));
//        echo json_encode($response);
//        break;
//      }
     
     
//      if($olddata['code']!=$_REQUEST['code'] or $olddata['description']!=$_REQUEST['description'] ){
//        $code=addslashes($_REQUEST['code']);
//        $description=addslashes($_REQUEST['description']);

//        $ncode=$code;
//        $c=split('-',$code);
//        if(count($c)==2){
// 	 if(is_numeric($c[1]))
// 	   $ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
// 	 else
// 	   $ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
//        }     
    

//        $sql=sprintf("update product set ncode='%s', code='%s' ,description='%s'  where id=%d",$ncode,$code,$description,$id);
//        $affected=& $db->exec($sql);
//        // print "$sql\n";
//        if (PEAR::isError($affected)) {
// 	 if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
// 	   $resp=_('Error: Another product has the same code').'.';
// 	 else
// 	   $resp=_('Unknown Error').'.';
// 	 $state='400';
// 	 $data=array();
// 	 $response=array('state'=>400,'resp'=>$resp);
// 	 echo json_encode($response);
// 	 break;
	 
//        }
       
//      }
     
//      // update requiered fields
//      $sql=sprintf("update product set units='%s' ,price='%s',units_tipo=%d  where id=%d",$_REQUEST['units'],$_REQUEST['price'],$_REQUEST['units_tipo'],$id);
//      $db->exec($sql);
       
     
     
//      if(isset($_REQUEST['rrp']) and is_numeric($_REQUEST['rrp']))
//        $rrp=$_REQUEST['rrp'];
//      else
//        $rrp='NULL';
     
//      if(isset($_REQUEST['units_carton']) and is_numeric($_REQUEST['units_carton']))
//        $units_carton=$_REQUEST['units_carton'];
//      else
//        $units_carton='NULL';

     
    

     
//     // update requiered fields
//     $sql=sprintf("update product set units='%s' ,price='%s',units_tipo=%d ,rrp=%s,units_carton where id=%d",$_REQUEST['units'],$_REQUEST['price'],$_REQUEST['units_tipo'],$rrp,$units_carton,$id);
//     $db->exec($sql);
    

    

    
    
//     $resp='ok';
//     $data= array(
// 		  'id'=>$id
// 		 );
//     $state='200';
   
//    $response=array('state'=>$state,'resp'=>_($resp),'data'=>$data);
//    }else
//      $response=array('state'=>400,'resp'=>_('Error, please check that all the fields are filled'));
//    echo json_encode($response);
//    breack;
?>