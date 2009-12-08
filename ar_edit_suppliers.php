<?php
require_once 'class.Timer.php';

require_once 'common.php';
require_once 'class.Company.php';
require_once 'class.Supplier.php';
require_once 'ar_edit_common.php';



if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$editor=array(
	      'Author Name'=>$user->data['User Alias'],
	      'Author Type'=>$user->data['User Type'],
	      'Author Key'=>$user->data['User Parent Key'],
	      'User Key'=>$user->id
	      );



$tipo=$_REQUEST['tipo'];
switch($tipo){
case('new_supplier'):
 $data=prepare_values($_REQUEST,array(
			     'values'=>array('type'=>'json array')
			     
				      ));
 new_supplier($data);
 
 break;
case('supplier_products'):
  list_supplier_products();
  break;
case('edit_supplier'):
  edit_supplier();
  break;
case('edit_product_supplier'):
  edit_product_supplier();
  break;
case('complex_edit_supplier'):
  complex_edit_supplier();
  break;
case('edit_suppliers'):
  edit_suppliers();
  break;
default:
   $response=array('state'=>405,'resp'=>'Unknown Type');
    echo json_encode($response);

}

function edit_supplier() {
  $key=$_REQUEST['key'];
 
  
  $supplier=new supplier($_REQUEST['supplier_key']);
  global $editor;
  $supplier->editor=$editor;
  
  if($key=='Attach'){
    // print_r($_FILES);
    $note=stripslashes(urldecode($_REQUEST['newvalue']));
    $target_path = "uploads/".'attach_'.date('U');
    $original_name=$_FILES['testFile']['name'];
    $type=$_FILES['testFile']['type'];
    $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

    if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
      $supplier->add_attach($target_path,$data);
      
    }
  }else{
    

    
    $key_dic=array(
		   'name'=>'Supplier Name'
		   ,'email'=>'Supplier Email'
		   ,'telephone'=>'Supplier Main Plain Telephone'
		   ,'contact_name'=>'Email'
		   ,"address"=>'Address'
		   ,"town"=>'Main Address Town'
		   ,"postcode"=>'Main Address Town'
		   ,"region"=>'Main Address Town'
		   ,"country"=>'Main Address Country'
		   ,"ship_address"=>'Main Ship To'
		   ,"ship_town"=>'Main Ship To Town'
		   ,"ship_postcode"=>'Main Ship To Postal Code'
		   ,"ship_region"=>'Main Ship To Country Region'
		   ,"ship_country"=>'Main Ship To Country'
		   
    );
    if(array_key_exists($_REQUEST['key'],$key_dic))
       $key=$key_dic[$_REQUEST['key']];
    
    
    $supplier->update(array($key=>stripslashes(urldecode($_REQUEST['newvalue']))));
  }


    if ($supplier->updated) {
        $response= array('state'=>200,'newvalue'=>$supplier->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$supplier->msg,'key'=>$_REQUEST['key']);
    }
    echo json_encode($response);

}

function edit_product_supplier() {
  $key=$_REQUEST['key'];
 
  
  $supplier=new SupplierProduct('id',$_REQUEST['sph_key']);
  global $editor;
  $supplier->editor=$editor;
  
  if($key=='Attach'){
    // print_r($_FILES);
    $note=stripslashes(urldecode($_REQUEST['newvalue']));
    $target_path = "uploads/".'attach_'.date('U');
    $original_name=$_FILES['testFile']['name'];
    $type=$_FILES['testFile']['type'];
    $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

    if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
      $supplier->add_attach($target_path,$data);
      
    }
  }else{
    

    
    $key_dic=array(
		   'name'=>'Supplier Product Name'
		   ,'description'=>'Supplier Product Description'
		   ,'unit_type'=>'Supplier Product Unit Type'
		   ,'units'=>'Supplier Product Units Per Case'
		   ,"cost"=>'Supplier Product Cost'

		   
    );
    if(array_key_exists($_REQUEST['key'],$key_dic))
       $key=$key_dic[$_REQUEST['key']];
    
    
    $supplier->update(array($key=>stripslashes(urldecode($_REQUEST['newvalue']))));
  }


    if ($supplier->updated) {
        $response= array('state'=>200,'newvalue'=>$supplier->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$supplier->msg,'key'=>$_REQUEST['key']);
    }
    echo json_encode($response);

}




function complex_edit_supplier(){
global $editor;
 if(!isset($_REQUEST['key']) ){
    $response=array('state'=>400,'msg'=>'Error no key');
     echo json_encode($response);
	 return;
  }
 if( !isset($_REQUEST['newvalue']) ){
   $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
	 return;
 }
 if( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ){
   $supplier_key=$_SESSION['state']['supplier']['id'];
 }else
   $supplier_key=$_REQUEST['id'];

 $supplier=new Supplier($supplier_key);

 if(!$supplier->id){
   $response=array('state'=>400,'msg'=>_('Supplier not found'));
    echo json_encode($response);
	 return;
 }
  
 $translator=array(
		   'name'=>'Supplier Name'
		   ,'fiscal_name'=>'Supplier Fiscal Name'
		   ,'tax_number'=>'Supplier Tax Number'
		   ,'registration_number'=>'Supplier Registration Number'
		   

		   );
		  
  if (array_key_exists($_REQUEST['key'], $translator)) {
    $update_data=array(
		       'editor'=>$editor
		       ,$translator[$_REQUEST['key']]=>stripslashes(urldecode($_REQUEST['newvalue']))
		       );
    $supplier->update($update_data);
    
    if($supplier->error_updated){
      $response=array('state'=>200,'action'=>'error','msg'=>$supplier->msg_updated,'key'=>$_REQUEST['key']);
    }else{
    
      if($supplier->updated){
	$response=array('state'=>200,'action'=>'updated','msg'=>$supplier->msg_updated,'key'=>$_REQUEST['key'],'newvalue'=>$supplier->new_value);
      }else{
	$response=array('state'=>200,'action'=>'nochange','msg'=>$supplier->msg_updated,'key'=>$_REQUEST['key']);

      }

    }


  }else{
    $response=array('state'=>400,'msg'=>_('Key not in Supplier'));
  }
  echo json_encode($response);

}

function edit_suppliers(){
global $myconf;

    $conf=$_SESSION['state']['suppliers']['table'];
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
  $_SESSION['state']['suppliers']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  
  
  

  $_order=$order;
  $_dir=$order_direction;
  


   $wheref='';
  if($f_field=='code'  and $f_value!='')
    $wheref.=" and `Supplier Code` like '".addslashes($f_value)."%'";
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  `Supplier Name` like '".addslashes($f_value)."%'";
 elseif($f_field=='low' and is_numeric($f_value))
    $wheref.=" and lowstock>=$f_value  ";
   elseif($f_field=='outofstock' and is_numeric($f_value))
    $wheref.=" and outofstock>=$f_value  ";


   $sql="select count(*) as total from `Supplier Dimension`    $where $wheref";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total from `Supplier Dimension` $where      ";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       	$total_records=$row['total'];
       $filtered=$row['total']-$total;
     }
     
   }
   $rtext=$total_records." ".ngettext('supplier','suppliers',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

  $filter_msg='';
  
  switch($f_field){
  case('code'):
    if($total==0 and $filtered>0)
      $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with code")." <b>$f_value</b>* ";
    elseif($filtered>0)
      $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('suppliers with code')." <b>$f_value</b>*) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
    break;
  case('name'):
    if($total==0 and $filtered>0)
      $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with name")." <b>$f_value</b>* ";
    elseif($filtered>0)
      $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('suppliers with name')." <b>$f_value</b>*) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
    break;
  case('low'):
    if($total==0 and $filtered>0)
      $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with more than ")." <b>".number($f_value)."</b> "._('low stock products');
    elseif($filtered>0)
      $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('Suppliers with')." <b><".number($f_value)."</b> "._('low stock products').") <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
    break;
  case('outofstock'):
    if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with more than ")." <b>".number($f_value)."</b> "._('out of stock products');
    elseif($filtered>0)
      $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('Suppliers with')." <b><".number($f_value)."</b> "._('out of stock products').") <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show all')."</span>";
    break;
  }
  


  $order='`Supplier Code`';
  if($order=='id' or $order=='supplier_key')
    $order='`Supplier Key`';
  if($order=='code')
    $order='`Supplier Code`';
  elseif($order=='name')
    $order='`Supplier Name`';
  elseif($order=='id')
       $order='`Supplier Key`';
  elseif($order=='location')
       $order='`Supplier Location`';
  elseif($order=='email')
    $order='`Supplier Main XHTML Email`';
  
  //    elseif($order='used_in')
  //        $order='Supplier Product XHTML Used In';
  
  $sql="select *   from `Supplier Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results";
   // print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

   

     $data[]=array(
		   'supplier_key'=>$row['Supplier Key']
		   ,'id'=>''
		   ,'code'=>$row['Supplier Code']
		   ,'name'=>$row['Supplier Name']
		  
		   ,'location'=>$row['Supplier Location']
		   ,'email'=>$row['Supplier Main Plain Email']
		   ,'go'=>sprintf("<a href='edit_supplier.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Supplier Key'])
		   
		   );
   }
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'rtext'=>$rtext,
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


function list_supplier_products() {
    $conf=$_SESSION['state']['supplier']['products'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['id']))
        $supplier_id=$_REQUEST['id'];
    else
        $supplier_id=$_SESSION['state']['supplier']['id'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['product_view']))
        $product_view=$_REQUEST['product_view'];
    else
        $product_view=$conf['view'];
    if (isset( $_REQUEST['product_period']))
        $product_period=$_REQUEST['product_period'];
    else
        $product_period=$conf['period'];

    if (isset( $_REQUEST['product_percentage']))
        $product_percentage=$_REQUEST['product_percentage'];
    else
        $product_percentage=$conf['percentage'];

    $filter_msg='';
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;


    $_SESSION['state']['supplier']['products']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
            ,'view'=>$product_view
                    ,'percentage'=>$product_percentage
                                  ,'period'=>$product_period
                                                    );
    $_SESSION['state']['supplier']['id']=$supplier_id;

    $where=$where.' and `supplier key`='.$supplier_id;


    $wheref='';


    if (($f_field=='code' ) and $f_value!='')
        $wheref.=" and  `Supplier Product XHTML Used In` like '".addslashes($f_value)."%'";
    if ($f_field=='sup_code' and $f_value!='')
        $wheref.=" and  `Supplier Product Code` like '".addslashes($f_value)."%'";








    $sql="select count(*) as total from `Supplier Product Dimension`  $where $wheref ";


    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {

        $sql="select count(*) as total `Supplier Product Dimension`  $where  ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$row['total']-$total;
        }

    }

    $rtext=$total_records." ".ngettext('pruduct','products',$total_records);
    if ($total_records>$number_results)
        $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
    $filter_msg='';

    switch ($f_field) {
    case('p.code'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code")." <b>".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with code')." <b>".$f_value."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
        break;
    case('sup_code'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with supplier code")." <b>".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with supplier code')." <b>".$f_value."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
        break;

    }
    if ($order=='id')
        $order='`Supplier Product ID`';
    elseif($order=='code')
    $order='`Supplier Product Code`';
    elseif($order='usedin')
    $order='`Supplier Product XHTML Used In`';

    $sql="select * from `Supplier Product Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
    $data=array();

    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


       



        $data[]=array(
		      'sph_key'=>$row['Supplier Product Current Key']
		      ,'code'=>$row['Supplier Product Code']
		      
                      
		      ,'name'=>$row['Supplier Product Name']
		      ,'cost'=>money($row['Supplier Product Cost'])
		      ,'usedin'=>$row['Supplier Product XHTML Used In']
		      ,'unit_type'=>$row['Supplier Product Unit Type']
		      ,'units'=>$row['Supplier Product Units Per Case']

		      
		      );
    }


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
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