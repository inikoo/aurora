<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
require_once 'common.php';
//require_once '_order.php';

//require_once '_contact.php';
require_once 'class.Customer.php';
require_once 'class.Timer.php';




if(!isset($_REQUEST['tipo']))  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('find_company'):
  require_once 'ar_edit_common.php';
  $data=prepare_values($_REQUEST,array(
				       
				       'values'=>array('type'=>'json array')
				       ));
  find_company($data['values']);
  break;
 case('customer_history_details'):
   customer_history_details();
   break;
 case('contacts'):
 list_contacts();
   break;
 case('companies'):
 list_companies();
   break;
 case('staff'):
list_staff();
   break;
case('customers_advanced_search'):
customer_advanced_search();
  break;
case('customers'):
if(!$user->can_view('customers'))
    exit();
  list_customers();
   break;
case('customer_history'):
list_customer_history();
   break;
case('plot_order_interval'):

  $now="'2008-04-18 08:30:00'";

  $sql="select count(*) as total from customer where order_interval>0    and  (order_interval*3)>DATEDIFF($now,last_order)   ";

  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total_sample=$row['total'];
  }
  $sql="select  CEIL(order_interval) as x ,count(*) as y from customer where order_interval>0 and order_interval<300    and  (order_interval*3)>DATEDIFF($now,last_order)     group by CEIL(order_interval)";
  //   print $sql;  
  $data=array();

  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $data[]=array(
		'x'=>$row['x'],
		'y'=>$row['y']/$total_sample
		);
   }


 $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

   echo json_encode($response);
   break;

case('company_history'):
list_company_history();

   
   break;
 default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }

function list_companies(){
$conf=$_SESSION['state']['companies']['table'];
   if(isset( $_REQUEST['view']))
     $view=$_REQUEST['view'];
   else
     $view=$_SESSION['state']['companies']['view'];
     
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(!is_numeric($start_from))
     $start_from=0;

   if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
   }else
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

    
    
    
   $_SESSION['state']['companies']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
						 ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
						 );
      
     
    
  
   $group='';

   
   switch($restrictions){
   case('forsale'):
     $where.=sprintf(" and `Product Sales State`='For Sale'  ");
     break;
   case('editable'):
     $where.=sprintf(" and `Product Sales State` in ('For Sale','In Process','Unknown')  ");
     break;
   case('notforsale'):
     $where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
     break;
   case('discontinued'):
     $where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
     break;
   case('none'):

     break;
   }

      
   $filter_msg='';
     
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
     
   //  if(!is_numeric($start_from))
   //        $start_from=0;
   //      if(!is_numeric($number_results))
   //        $number_results=25;
     

   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';
   $wheref='';
   if($f_field=='company name' and $f_value!='')
     $wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
   elseif($f_field=='email' and $f_value!='')
     $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";
     
   $sql="select count(*) as total from `Company Dimension`  $where $wheref   ";
//print $sql;
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
mysql_free_result($res);
     
   $rtext=$total_records." ".ngettext('contact','companies',$total_records);
   if($total_records>$number_results)
     $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
     $rtext_rpp=' '._('(Showing all)');
     
   if($total==0 and $filtered>0){
     switch($f_field){
     case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
       break;
     case('email'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('email'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break; 
     }
   }else
      $filter_msg='';
       
   $_order=$order;
   $_order_dir=$order_dir;
     
   if($order=='name')
     $order='`Company File As`';
   elseif($order=='location')
     $order='`Company Main Location`';
    elseif($order=='email')
     $order='`Company Main Plain Email`';
    elseif($order=='telephone')
     $order='`Company Main Plain Telephone`';
    elseif($order=='mobile')
      $order='`Company Main Plain Mobile`';
    elseif($order=='fax')
      $order='`Company Main Plain FAX`';
    elseif($order=='town')
      $order='`Address Town`';
    elseif($order=='contact')
      $order='`Company Main Contact Name`';
    elseif($order=='address')
      $order='`Company Main Plain Address`';
    elseif($order=='postcode')
      $order='`Address Postal Code`';
    elseif($order=='region')
      $order='`Address Country First Division`';
    elseif($order=='country')
      $order='`Address Country Code`';


   $sql="select  * from `Company Dimension` P left join `Address Dimension` on (`Company Main Address Key`=`Address Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
  
   $res = mysql_query($sql);
   $adata=array();

   // print "$sql";
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
     
     $id=sprintf('<a href="company.php?id=%d">%04d</a>',$row['Company Key'],$row['Company Key']);
     if($row['Company Main Contact Key'])
       $contact=sprintf('<a href="company.php?id=%d">%s</a>',$row['Company Main Contact Key'],$row['Company Main Contact Name']);
     else
       $contact='';
    $adata[]=array(
		  
		   'company_key'=>$id
		    ,'id'=>$row['Company Key']
		   ,'name'=>$row['Company Name']
		   ,'location'=>$row['Company Main Location']
		   ,'email'=>$row['Company Main XHTML Email']
		   ,'telephone'=>$row['Company Main Telephone']
		   ,'fax'=>$row['Company Main FAX']
		   ,'contact'=>$contact
		   ,'town'=>$row['Address Town']
		   ,'postcode'=>$row['Address Postal Code']
		   ,'region'=>$row['Address Country First Division']
		   ,'country'=>$row['Address Country Code']
		   ,'address'=>$row['Company Main XHTML Address']
		   );
  }
mysql_free_result($res);


   // $total_records=ceil($total_records/$number_results)+$total_records;

  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			)
		  );

       


   echo json_encode($response);

}
function list_company_history(){
 $conf=$_SESSION['state']['company']['table'];

    if(isset( $_REQUEST['id']))
      $company_id=$_REQUEST['id'];
    else
      $company_id=$_SESSION['state']['company']['id'];
    

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

    if(isset( $_REQUEST['details']))
      $details=$_REQUEST['details'];
    else
      $details=$conf['details'];
    

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
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=$conf['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$conf['to'];

  $elements=$conf['elements'];
  if(isset( $_REQUEST['element_orden']))
    $elements['orden']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cust']))
    $elements['h_cust']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cont']))
    $elements['h_cont']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_note']))
    $elements['note']=$_REQUEST['e_orden'];
  

   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;




   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['company']['id']=$company_id;
   $_SESSION['state']['company']['table']=array('details'=>$details,'elements'=>$elements,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['company']['table']['from'],$_SESSION['state']['company']['table']['to']);
   }else{
     $_SESSION['state']['company']['table']['from']=$date_interval['from'];
     $_SESSION['state']['company']['table']['to']=$date_interval['to'];
   }

   $where.=sprintf(' and (  (`Subject`="Company" and  `Subject Key`=%d) or (`Direct Object`="Company" and  `Direct Object key`=%d ) or (`Indirect Object`="Company" and  `Indirect Object key`=%d )         ) ',$company_id,$company_id,$company_id);
//   if(!$details)
 //    $where.=" and display!='details'";
 //  foreach($elements as $element=>$value){
 //    if(!$value ){
 //      $where.=sprintf(" and objeto!=%s ",prepare_mysql($element));
 //    }
 //  }
   
   $where.=$date_interval['mysql'];
   
   $wheref='';



   if( $f_field=='notes' and $f_value!='' )
     $wheref.=" and   note like '%".addslashes($f_value)."%'   ";
   if($f_field=='upto' and is_numeric($f_value) )
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))<=".$f_value."    ";
   else if($f_field=='older' and is_numeric($f_value))
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))>=".$f_value."    ";
   elseif($f_field=='author' and $f_value!=''){
       if(is_numeric($f_value))
	 $wheref.=" and   staff_id=$f_value   ";
       else{
	 $wheref.=" and  handle like='".addslashes($f_value)."%'   ";
       }
     }
	  
   

   
   
       

   


   
   $sql="select count(*) as total from  `History Dimension`   $where $wheref ";
 // print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   if($where==''){
     $filtered=0;
     $filter_total=0;
     $total_records=$total;
   }else{
     
     $sql="select count(*) as total from  `History Dimension`  $where";
    // print $sql;
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$filtered=$row['total']-$total;
	$total_records=$row['total'];
     }
     
   }
   
      mysql_free_result($result);

   $rtext=$total_records." ".ngettext('record','records',$total_records);
   
   if($total==0)
     $rtext_rpp='';
   elseif($total_records>$number_results)
     $rtext_rpp=sprintf('(%d%s)',$number_results,_('rpp'));
   else
     $rtext_rpp=_('Showing all');


//   print "$f_value $filtered  $total_records  $filter_total";
   $filter_msg='';
   if($filtered>0){
   switch($f_field){
     case('notes'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record matching")." <b>$f_value</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record matching','records matching')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
  case('older'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record older than")." <b>$f_value</b> ".ngettext($f_value,'day','days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record older than','records older than')." <b>$f_value</b> ".ngettext($f_value,'day','days')." <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('upto'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record in the last")." <b>$f_value</b> ".ngettext($f_value,'day','days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record in the last','records inthe last')." <b>$f_value</b> ".ngettext($f_value,'day','days')."<span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  


   }
   }


   
   $_order=$order;
   $_dir=$order_direction;
   if($order=='date')
     $order='History Date';
   if($order=='note')
     $order='History Abstract';
   if($order=='objeto')
     $order='Direct Object';

   $sql="select * from `History Dimension`   $where $wheref  order by `$order` $order_direction limit $start_from,$number_results ";
   //  print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     

     $data[]=array(
		   'id'=>$row['History Key'],
		   'date'=>strftime("%a %e %b %Y", strtotime($row['History Date'])),
		   'time'=>strftime("%H:%M", strtotime($row['History Date'])),
		   'objeto'=>$row['Direct Object'],
		   'note'=>$row['History Abstract'],
		   'handle'=>$row['Author Name']
		   );
   }
   mysql_free_result($result);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 //	 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_contacts(){
$conf=$_SESSION['state']['contacts']['table'];
   if(isset( $_REQUEST['view']))
     $view=$_REQUEST['view'];
   else
     $view=$_SESSION['state']['contacts']['view'];
     
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(!is_numeric($start_from))
     $start_from=0;

   if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
   }else
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

    
    
    
   $_SESSION['state']['contacts']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
						 ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
						 );
      
      
    
   switch($parent){
   case('company'):
     $where=sprintf(' where `Contact Company Key`=%d',$_SESSION['state']['company']['id']);
     break;
   case('supplier'):
     $where=sprintf(' left join `Contact Bridge` B on (P.`Contact Key`=B.`Contact Key`) where `Subject Type`="Supplier" and `Subject Key`=%d',$_SESSION['state']['supplier']['id']);
     break;
   case('customer'):
       $where=sprintf(' left join `Contact Bridge` B on (P.`Contact Key`=B.`Contact Key`) where `Subject Type`="Customer" and `Subject Key`=%d',$_SESSION['state']['customer']['id']);
     break;
   default:
     $where=sprintf(" where `Contact Fuzzy`='No' ");
      
   }
   $group='';
/*    switch($mode){ */
/*    case('same_code'): */
/*      $where.=sprintf(" and `Product Same Code Most Recent`='Yes' "); */
/*      break; */
/*    case('same_id'): */
/*      $where.=sprintf(" and `Product Same ID Most Recent`='Yes' "); */
	      
/*      break; */
/*    } */
   
   switch($restrictions){
   case('forsale'):
     $where.=sprintf(" and `Product Sales State`='For Sale'  ");
     break;
   case('editable'):
     $where.=sprintf(" and `Product Sales State` in ('For Sale','In Process','Unknown')  ");
     break;
   case('notforsale'):
     $where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
     break;
   case('discontinued'):
     $where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
     break;
   case('none'):

     break;
   }

      
   $filter_msg='';
     
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
     
   //  if(!is_numeric($start_from))
   //        $start_from=0;
   //      if(!is_numeric($number_results))
   //        $number_results=25;
     

   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';
   $wheref='';
   if($f_field=='name' and $f_value!='')
     $wheref.=" and  `Contact Name` like '%".addslashes($f_value)."%'";
   elseif($f_field=='email' and $f_value!='')
     $wheref.=" and  `Contact Main Plain Email` like '".addslashes($f_value)."%'";
     
   $sql="select count(*) as total from `Contact Dimension`  $where $wheref   ";

   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;
     $total_records=$total;
   } else{
     $sql="select count(*) as total from `Contact Dimension`  $where   ";
     $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
       $total_records=$row['total'];
       $filtered=$total_records-$total;
     }

   }

     mysql_fetch_array($res);
   $rtext=$total_records." ".ngettext('contact','contacts',$total_records);
   if($total_records>$number_results)
     $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
     $rtext_rpp=' '._('(Showing all)');
     
   if($total==0 and $filtered>0){
     switch($f_field){
     case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
       break;
     case('email'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('contacts with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('email'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('contacts with email like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break; 
     }
   }else
      $filter_msg='';
       
   $_order=$order;
   $_order_dir=$order_dir;
     
   if($order=='name')
     $order='`Contact File As`';
   elseif($order=='location')
     $order='`Contact Main Location`';
    elseif($order=='email')
     $order='`Contact Main Plain Email`';
    elseif($order=='telephone')
     $order='`Contact Main Plain Telephone`';
    elseif($order=='mobile')
      $order='`Contact Main Plain Mobile`';
    elseif($order=='fax')
      $order='`Contact Main Plain FAX`';
    elseif($order=='town')
      $order='`Address Town`';
    elseif($order=='company')
      $order='`Contact Company Name`';
    elseif($order=='address')
      $order='`Contact Main Plain Address`';
    elseif($order=='postcode')
      $order='`Address Postal Code`';
    elseif($order=='region')
      $order='`Address Country First Division`';
    elseif($order=='country')
      $order='`Address Country Code`';


   $sql="select  * from `Contact Dimension` P left join `Address Dimension` on (`Contact Main Address Key`=`Address Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
  
   $res = mysql_query($sql);
   $adata=array();

   // print "$sql";
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
     
     $id=sprintf('<a href="contact.php?id=%d">%04d</a>',$row['Contact Key'],$row['Contact Key']);
     if($row['Contact Company Key'])
       $company=sprintf('<a href="company.php?id=%d">%s</a>',$row['Contact Company Key'],$row['Contact Company Name']);
     else
       $company='';
    $adata[]=array(
		  
		   'id'=>$id
		   ,'name'=>$row['Contact Name']
		   ,'location'=>$row['Contact Main Location']
		   ,'email'=>$row['Contact Main XHTML Email']
		   ,'telephone'=>$row['Contact Main Telephone']
		   ,'mobile'=>$row['Contact Main Mobile']
		   ,'fax'=>$row['Contact Main FAX']
		   ,'company'=>$company
		   ,'town'=>$row['Address Town']
		   ,'postcode'=>$row['Address Postal Code']
		   ,'region'=>$row['Address Country First Division']
		   ,'country'=>$row['Address Country Code']
		   ,'address'=>$row['Contact Main XHTML Address']
		   );
  }
     mysql_fetch_array($res);


   // $total_records=ceil($total_records/$number_results)+$total_records;

  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			)
		  );

       


   echo json_encode($response);
}
function list_customers(){


global $myconf;

  $conf=$_SESSION['state']['customers']['table'];
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
  $_SESSION['state']['customers']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $filter_msg='';
  $wheref='';

  if(($f_field=='customer name'     )  and $f_value!=''){
      $wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
  }elseif(($f_field=='postcode'     )  and $f_value!=''){
      $wheref="  and  `Customer Main Address Postal Code` like '%".addslashes($f_value)."%'";



  }else if($f_field=='id'  )
    $wheref.=" and  `Customer ID` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
  else if($f_field=='maxdesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
  else if($f_field=='mindesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
  else if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  `Customer Orders`<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  `Customer Orders`>=".$f_value."    ";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  `Customer Net Balance`>=".$f_value."    ";






   $sql="select count(*) as total from `Customer Dimension`  $where $wheref";

   $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total_without_filters from `Customer Dimension`  $where ";
     $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
       $total_records=$row['total_without_filters'];
       $filtered=$row['total_without_filters']-$total;
     }

   }else{
     $filtered=0;
     $filter_total=0;
     $total_records=$total;
   }
    mysql_free_result($res);

   $rtext=$total_records." ".ngettext('identified customers','identified customers',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

   if($total==0 and $filtered>0){
     switch($f_field){
     case('customer name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('customer name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('customers with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     }
   }else
      $filter_msg='';
   




   $_order=$order;
   $_dir=$order_direction;
   // if($order=='location'){
//      if($order_direction=='desc')
//        $order='country_code desc ,town desc';
//      else
//        $order='country_code,town';
//      $order_direction='';
//    }

//     if($order=='total'){
//       $order='supertotal';
//    }
    

   if($order=='name')
     $order='`Customer File As`';
   elseif($order=='id')
     $order='`Customer ID`';
   elseif($order=='location')
     $order='`Customer Main Location`';
   elseif($order=='orders')
     $order='`Customer Orders`';
   elseif($order=='email')
     $order='`Customer Email`';
   elseif($order=='telephone')
     $order='`Customer Main Telehone`';
   elseif($order=='last_order')
     $order='`Customer Last Order Date`';
   elseif($order=='contact_name')
     $order='`Customer Main Contact Name`';
   elseif($order=='address')
     $order='`Customer Main Location`';
   elseif($order=='town')
     $order='`Customer Main Address Town`';
   elseif($order=='postcode')
     $order='`Customer Main Address Postal Code`';
   elseif($order=='region')
     $order='`Customer Main Address Country First Division`';
   elseif($order=='country')
     $order='`Customer Main Address Country`';
   //  elseif($order=='ship_address')
   //  $order='`customer main ship to header`';
   elseif($order=='ship_town')
     $order='`Customer Main Ship To Town`';
   elseif($order=='ship_postcode')
     $order='`Customer Main Ship To Postal Code`';
   elseif($order=='ship_region')
     $order='`Customer Main Ship To Country Region`';
   elseif($order=='ship_country')
     $order='`Customer Main Ship To Country`';
   elseif($order=='net_balance')
     $order='`Customer Net Balance`';
   elseif($order=='balance')
     $order='`Customer Outstanding Net Balance`';
   elseif($order=='total_profit')
     $order='`Customer Profit`';
   elseif($order=='total_payments')
     $order='`Customer Net Payments`';
   elseif($order=='top_profits')
     $order='`Customer Profits Top Percentage`';
   elseif($order=='top_balance')
     $order='`Customer Balance Top Percentage`';
   elseif($order=='top_orders')
     $order='``Customer Orders Top Percentage`';
   elseif($order=='top_invoices')
     $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
     $order='`Customer Total Refunds`';
    
  elseif($order=='activity')
     $order='`Customer Type by Activity`';

   $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds`  from `Customer Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results";
   //   print $sql;
   $adata=array();
  
  
  
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){



  
    
  
   //  if($data['factor_num_orders_nd']>.60)
//       $color='bbb';
//     elseif($data['factor_num_orders_nd']>.40)
//       $color='888';
//     elseif($data['factor_num_orders_nd']>.20)
//       $color='444';
//     else
//       $color='000';

//     if($data['factor_num_orders_nd']<.05)
//       $old_orders='';
//     else{
//       $orders_with_no_data=number($data['num_orders_nd']);
//       $old_orders='<span title="'.$orders_with_no_data.' '.ngettext('order','orders',$orders_with_no_data).' '._('with no data available.').'" style="cuersor:pointer;position: relative;top: -0.3em;color:#'.$color.';font-size: 0.8em;">*</span>';
//     }

//     if($data['num_invoices']==0){
//       $color='bbb';
//       $super_total='<i  style="color:#'.$color.'">'._('ND').'</i>';
//       $orders_with_no_data=number($data['num_orders_nd']);
//       $old_orders='<span title="'.$orders_with_no_data.' '.ngettext('order','orders',$orders_with_no_data).' '._('with no data available.').'" style="cuersor:pointer;position: relative;top: -0.3em;color:#'.$color.';font-size: 0.8em;">*</span>';
//     }else
//       $super_total='<i  style="color:#'.$color.'">'.money($data['super_total']).'</i>';
//     $orders=$old_orders.'<i  style="color:#'.$color.'">'.number($data['orders']).'</i>';
//     if($data['is_staff']>0)
//       $location='<span style="color:#999">('._('ex').')</span>'._('Staff');
//     else
//       $location='<img title="'.$data['country_name'].'"  src="art/flags/'.strtolower($data['country_code2']).'.gif" alt="'.$data['country_code'].'"> '.$data['town'].' '.preg_replace('/\s/','',$data['postcode']);

//      $email='';
//      if($data['email']!='')
//        $email='<a href="emailto:'.$data['email'].'"  >'.$data['email'].'</a>';
//      $tel='';
//      if($data['number']!='')
//        $tel=($data['icode']!=''?'+'.$data['icode'].' ':'').$data['number'];


    $id="<a href='customer.php?id=".$data['Customer ID']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer ID']).'</a>'; 
    $name="<a href='customer.php?id=".$data['Customer ID']."'>".$data['Customer Name'].'</a>'; 

    $adata[]=array(
		   'id'=>$id,
		   'name'=>$name,
		   'location'=>$data['Customer Main Location'],
		   'orders'=>number($data['Customer Orders']),
		   'invoices'=>$data['Customer Orders Invoiced'],
		   'email'=>$data['Customer Main XHTML Email'],
		   'telephone'=>$data['Customer Main Telephone'],
		   'last_order'=>strftime("%e %b %Y", strtotime($data['Customer Last Order Date'])),
		   'total_payments'=>money($data['Customer Net Payments']),
		   'net_balance'=>money($data['Customer Net Balance']),
		   'total_refunds'=>money($data['Customer Net Refunds']),
		   'total_profit'=>money($data['Customer Profit']),
		   'balance'=>money($data['Customer Outstanding Net Balance']),


		   'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
		   'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
		   'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
		   'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
		   'contact_name'=>$data['Customer Main Contact Name'],
		   'address'=>$data['Customer Main Location'],
		   'town'=>$data['Customer Main Address Town'],
		   'postcode'=>$data['Customer Main Address Postal Code'],
		   'region'=>$data['Customer Main Address Country First Division'],
		   'country'=>$data['Customer Main Address Country'],
		   //		   'ship_address'=>$data['customer main ship to header'],
		   'ship_town'=>$data['Customer Main Ship To Town'],
		   'ship_postcode'>$data['Customer Main Ship To Postal Code'],
		   'ship_region'=>$data['Customer Main Ship To Country Region'],
		   'ship_country'=>$data['Customer Main Ship To Country'],
		   'activity'=>$data['Customer Type by Activity'],
		   );
  }
mysql_free_result($result);




  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,

			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_customer_history(){

    $conf=$_SESSION['state']['customer']['table'];

    if(isset( $_REQUEST['id']))
      $customer_id=$_REQUEST['id'];
    else
      $customer_id=$_SESSION['state']['customer']['id'];
    

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

    if(isset( $_REQUEST['details']))
      $details=$_REQUEST['details'];
    else
      $details=$conf['details'];
    

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
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=$conf['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$conf['to'];

  $elements=$conf['elements'];
  if(isset( $_REQUEST['element_orden']))
    $elements['orden']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cust']))
    $elements['h_cust']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cont']))
    $elements['h_cont']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_note']))
    $elements['note']=$_REQUEST['e_orden'];
  

   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;




   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['customer']['id']=$customer_id;
   $_SESSION['state']['customer']['table']=array('details'=>$details,'elements'=>$elements,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
   }else{
     $_SESSION['state']['customer']['table']['from']=$date_interval['from'];
     $_SESSION['state']['customer']['table']['to']=$date_interval['to'];
   }

   $where.=sprintf(' and (  (`Subject`="Customer" and  `Subject Key`=%d) or (`Direct Object`="Customer" and  `Direct Object key`=%d ) or (`Indirect Object`="Customer" and  `Indirect Object key`=%d )         ) ',$customer_id,$customer_id,$customer_id);
//   if(!$details)
 //    $where.=" and display!='details'";
 //  foreach($elements as $element=>$value){
 //    if(!$value ){
 //      $where.=sprintf(" and objeto!=%s ",prepare_mysql($element));
 //    }
 //  }
   
   $where.=$date_interval['mysql'];
   
   $wheref='';



   if( $f_field=='notes' and $f_value!='' )
     $wheref.=" and   note like '%".addslashes($f_value)."%'   ";
   if($f_field=='upto' and is_numeric($f_value) )
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))<=".$f_value."    ";
   else if($f_field=='older' and is_numeric($f_value))
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))>=".$f_value."    ";
   elseif($f_field=='author' and $f_value!=''){
       if(is_numeric($f_value))
	 $wheref.=" and   staff_id=$f_value   ";
       else{
	 $wheref.=" and  handle like='".addslashes($f_value)."%'   ";
       }
     }
	  
   

   
   
       

   


   
   $sql="select count(*) as total from  `History Dimension`   $where $wheref ";
 // print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   if($where==''){
     $filtered=0;
     $filter_total=0;
     $total_records=$total;
   }else{
     
     $sql="select count(*) as total from  `History Dimension`  $where";
    // print $sql;
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$filtered=$row['total']-$total;
	$total_records=$row['total'];
     }
     
   }
      mysql_free_result($result);

   
   $rtext=$total_records." ".ngettext('record','records',$total_records);
   
   if($total==0)
     $rtext_rpp='';
   elseif($total_records>$number_results)
     $rtext_rpp=sprintf('(%d%s)',$number_results,_('rpp'));
   else
     $rtext_rpp=_('Showing all');


//   print "$f_value $filtered  $total_records  $filter_total";
   $filter_msg='';
   if($filtered>0){
   switch($f_field){
     case('notes'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record matching")." <b>$f_value</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record matching','records matching')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
  case('older'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record older than")." <b>$f_value</b> ".ngettext($f_value,'day','days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record older than','records older than')." <b>$f_value</b> ".ngettext($f_value,'day','days')." <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('upto'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record in the last")." <b>$f_value</b> ".ngettext($f_value,'day','days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record in the last','records inthe last')." <b>$f_value</b> ".ngettext($f_value,'day','days')."<span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  


   }
   }


   
   $_order=$order;
   $_dir=$order_direction;
   if($order=='date')
     $order='History Date';
   if($order=='note')
     $order='History Abstract';
   if($order=='objeto')
     $order='Direct Object';

   $sql="select * from `History Dimension`   $where $wheref  order by `$order` $order_direction limit $start_from,$number_results ";
   //  print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     

     $data[]=array(
		   'id'=>$row['History Key'],
		   'date'=>strftime("%a %e %b %Y", strtotime($row['History Date'])),
		   'time'=>strftime("%H:%M", strtotime($row['History Date'])),
		   'objeto'=>$row['Direct Object'],
		   'note'=>$row['History Abstract'],
		   'handle'=>$row['Author Name']
		   );
   }
   mysql_free_result($result);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 //	 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function customer_advanced_search(){
 if(!$user->can_view('customers')){
    exit();
  }

 $conf=$_SESSION['state']['customers']['advanced_search'];
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
//     if(isset( $_REQUEST['f_field']))
//      $f_field=$_REQUEST['f_field'];
//    else
//      $f_field=$conf['f_field'];

//   if(isset( $_REQUEST['f_value']))
//      $f_value=$_REQUEST['f_value'];
//    else
//      $f_value=$conf['f_value'];
  if(isset( $_REQUEST['where']))
     $awhere=$_REQUEST['where'];
   else
     $awhere=$conf['where'];
  
  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

   
   $filtered=0;
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;

   //print_r($_SESSION['state']['customers']['advanced_search']);
   $_SESSION['state']['customers']['advanced_search']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from
							    ,'where'=>$awhere
							    //,'f_field'=>$f_field,'f_value'=>$f_value
							   );
   $filter_msg='';
   // $awhere='{"from1":"","from2":"","product_not_ordered1":"","product_not_ordered2":"","product_not_received1":"","product_not_received2":"","product_ordered1":"g(ob)","product_ordered2":"","to1":"","to2":""}';
   $awhere=preg_replace('/\\\"/','"',$awhere);
  //    print "$awhere";
   $awhere=json_decode($awhere,TRUE);
  // print_r($awhere);
   $where='where ';

  if($awhere['product_ordered1']!=''){
    if($awhere['product_ordered1']!='ANY'){
      $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
    }else
      $where_product_ordered1='true';
  }else
    $where_product_ordered1='false';
  
  if($awhere['product_not_ordered1']!=''){
    if($awhere['product_not_ordered1']!='ALL'){
      $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'product.code not like','transaction.product_id not like','product_group.name not like','product_group.id like');
    }else
      $where_product_not_ordered1='false';
  }else
    $where_product_not_ordered1='true';

 if($awhere['product_not_received1']!=''){
    if($awhere['product_not_received1']!='ANY'){
      $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispached)>0 and    product.code  like','(ordered-dispached)>0 and  transaction.product_id not like','(ordered-dispached)>0 and  product_group.name not like','(ordered-dispached)>0 and  product_group.id like');
    }else
      $where_product_not_received1=' ((ordered-dispached)>0)  ';
  }else
    $where_product_not_received1='true';




  $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'date_index','only_dates');


  $geo_base='';
  if($awhere['geo_base']=='home')
    $geo_base='and list_country.id='.$myconf['country_id'];
  elseif($awhere['geo_base']=='nohome')
    $geo_base='and list_country.id!='.$myconf['country_id'];
  $with_mail='';
  if($awhere['mail'])
    $with_mail=' and main_email is not null ';
  $with_tel='';
  if($awhere['tel'])
    $with_tel=' and main_tel is not null ';



  $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].")  $geo_base $with_mail $with_tel";
  
  


  




  
  $sql="select count(distinct customer_id) as total  from customer left join orden on (customer_id=customer.id) left join transaction on (order_id=orden.id) left join product on (product_id=product.id) left join product_group on (group_id=product_group.id) left join product_department on (product_group.department_id=product_department.id)    left join address on (main_bill_address=address.id) left join list_country on (country=list_country.name)   $where  ";
 //print $sql;

   $res=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }else
     $total=0;


   $rtext=$total." ".ngettext($total,'results found','result found');
   
  $sql=" select telecom.number,telecom.icode,telecom.ncode,telecom.ext, postcode,town,list_country.code as country_code,code2 as country_code2,list_country.name as country_name, email ,email.contact as email_contact, UNIX_TIMESTAMP(max(date_index)) as last_order ,count(distinct orden.id) as orders, customer.id,customer.name from customer left join orden on (customer_id=customer.id) left join transaction on (order_id=orden.id) left join product on (product_id=product.id)  left join product_group on (group_id=product_group.id)  left join product_department on (product_group.department_id=product_department.id)      left join email on (main_email=email.id) left join telecom on (main_tel=telecom.id) left join address on (main_bill_address=address.id) left join list_country on (country=list_country.name) $where  group by customer_id order by $order $order_direction limit $start_from,$number_results";
 // print $sql;
 $res=mysql_query($sql);
  $adata=array();
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){
     $id="<a href='customer.php?id=".$data['id']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['id']).'</a>';
     $location='<img title="'.$data['country_name'].'"  src="art/flags/'.strtolower($data['country_code2']).'.gif" alt="'.$data['country_code'].'"> '.$data['town'].' '.preg_replace('/\s/','',$data['postcode']);
     $email='';
     if($data['email']!='')
       $email='<a href="emailto:'.$data['email'].'"  >'.$data['email'].'</a>';
        $tel='';
     if($data['number']!='')
       $tel=($data['icode']!=''?'+'.$data['icode'].' ':'').$data['number'];


     $adata[]=array(
		   'id'=>$id,
		   'name'=>$data['name'],
		   'orders'=>$data['orders'],
		   'last_order'=>strftime("%e %b %Y", strtotime('@'.$data['last_order'])),
		   'location'=>$location,
		   'email'=>$email,
		   'tel'=>$tel,
		     );		   
      
  }
  
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'rtext'=>$rtext,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );	
echo json_encode($response);
}
function customer_history_details(){
  if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])){
     $sql=sprintf("select `History Details` as details from `History Dimension` where `History Key`=%d",$_REQUEST['id']);
     $res = mysql_query($sql);
     if($data=mysql_fetch_array($res, MYSQL_ASSOC)) {
       $response=array('state'=>200,'details'=>$data['details']);
       echo json_encode($response);
       return;
     }
     mysql_free_result($res);
   }
   $response=array('state'=>400,'msg'=>_("Can not get history details"));
   echo json_encode($response);
   return;
}
function list_staff(){
  global $myconf;

$conf=$_SESSION['state']['hr']['staff'];
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
  
  if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
  else
    $view=$_SESSION['state']['hr']['view'];




   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;



  $_SESSION['state']['hr']['staff']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  $_SESSION['state']['hr']['view']=$view;


   $wheref='';
   if($f_field=='name' and $f_value!=''  )
     $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
   else if($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
     $wheref.=sprintf(" and  $f_field=%d ",$f_value);
  
  
  switch($view){
   case('all'):
     break;
   case('staff'):
     $where.=" and `Staff Currently Working`='Yes'  ";
     break;
   case('exstaff'):
     $where.=" and `Staff Currently Working`='No' ";
     break;
  }

   $sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`) $where $wheref";
   

   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)){
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`)   $where ";
     $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)){
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }else{
     $filtered=0;
     $total_records=$total;
   }
   
   mysql_free_result($res);
   $rtext=$total_records." ".ngettext('record','records',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   $filter_msg='';
   
    switch($f_field){
     case('name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
    case('area_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
    case('position_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;

    }

if($order=='name')
  $order='`Staff Name`';

   $sql="select * from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results";

   $adata=array();
   $res=mysql_query($sql);
   while($data=mysql_fetch_array($res)){


     $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
     $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);
     $adata[]=array(
		    'id'=>$id,
		    'alias'=>$data['Staff Alias'],
		    'name'=>$data['Staff Name'],
		    'department'=>$data['Staff Department Key'],
		    'area'=>$data['Staff Area Key'],
		    'position'=>$data['Staff Position Key']
		    
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
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function find_company($data){
  $candidates_data=array();
  // quick try to find the email
  if($data['Company Main Plain Email']!=''){
    $sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  " 
		 ,prepare_mysql($data['Company Main Plain Email'])
		 );
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result)){
      $contact=new Contact($row['Subject Key']);
      $company_key=$contact->company_key();
      if($company_key){
	$_company=new Company ($company_key);
	$subject_key=$company_key;
	$candidates_data[]= array('card'=>$_company->display('card'),'score'=>1000,'key'=>$_company->id,'tipo'=>'company','found'=>1);
      }else{
	$subject_key=$contact->id;
	$candidates_data[]= array('card'=>$contact->display('card'),'score'=>1000,'key'=>$contact->id,'tipo'=>'contact','found'=>1);

	$subject_key=$contact->key;
      }



      $response=array('candidates_data'=>$candidates_data,'action'=>'found_email','found_key'=>$subject_key);
      echo json_encode($response);
      return;
    }
  }



  $max_results=8;

  $company=new company('find fuzzy',$data);
  $found_key=0;
  if($company->found){
    $action='found';
    $found_key=$company->found_key;
  }elseif($company->number_candidate_companies>0)
    $action='found_candidates';
  else
    $action='nothing_found';
  
 
  $count=0;
  foreach($company->candidate_companies as $company_key=>$score){
    if($count>$max_results)
      break;
    $_company=new Company ($company_key);

    $found=0;
    if($company->found_key==$_company->id)
      $found=1;
    $candidates_data[]= array('card'=>$_company->display('card'),'score'=>$score,'key'=>$_company->id,'tipo'=>'company','found'=>$found);
   
    $count++;
  }
  //print_r($company->candidate_companies);
  
  $response=array('candidates_data'=>$candidates_data,'action'=>$action,'found_key'=>$found_key);
  echo json_encode($response);
}


?>