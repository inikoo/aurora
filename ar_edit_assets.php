<?php


$connect_to_external=true;


require_once 'common.php';

require_once 'class.Product.php';
require_once 'class.Department.php';
require_once 'class.Family.php';
require_once 'class.Category.php';
require_once 'class.Order.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';
require_once 'class.Image.php';
require_once 'ar_edit_common.php';
if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }


$tipo=$_REQUEST['tipo'];
switch($tipo){
case('edit_part_list'):
$data=prepare_values($_REQUEST,array(
                             'newvalue'=>array('type'=>'json array')
                             ,'key'=>array('type'=>'key')
                             ));
 edit_part_list($data);
break;
case('store_pages'):
list_pages_for_edition();
break;
case('edit_page_layout'):
edit_page_layout();
break;
case('edit_part_new_product'):
  if(isset($_REQUEST['part_sku']))
    edit_part_new_product($_REQUEST['part_sku']);
  break;
case('delete_part_new_product'):
   if(isset($_REQUEST['part_sku']))
     delete_part_new_product($_REQUEST['part_sku']);
 break;
case('edit_family_page_html_head'):
case('edit_family_page_header'):
case('edit_family_page_content'):
$data=prepare_values($_REQUEST,array(
                             'newvalue'=>array('type'=>'string'),
                             'key'=>array('type'=>'srting'),
                             'id'=>array('type'=>'key')
                             ));

 edit_page('family',$data);
  break;
case('add_part_new_product'):

  if(isset($_REQUEST['sku']))
    add_part_new_product($_REQUEST['sku']);

   break;
case('part_list'):
  list_parts_in_product();
  break;
case('edit_charges'):
  list_charges_for_edition();
  break;
case('edit_campaigns'):
  list_campaigns_for_edition();
  break;
case('edit_deals'):
  list_deals_for_edition();
  break;

case('delete_image'):

  delete_image();
  

  break;
case('upload_product_image'):
upload_image();
break;
 case('delete_family'):
delete_family();
     break;
case('delete_store'):
delete_store();
   break;
case('delete_department'):
delete_department();
   break;
 case('edit_family'):
 $data=prepare_values($_REQUEST,array(
                             'newvalue'=>array('type'=>'string'),
                             'key'=>array('type'=>'string'),
                             'id'=>array('type'=>'key')
                             ));

  edit_family($data); 
   break;

case('edit_product_advanced'):
  edit_product_multi();
   break;
case('edit_product_price'):
case('edit_product_weight'):

case('edit_product_description'):
case('edit_product'):
  edit_product();
   break;
case('edit_categories'):
    $data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
    edit_categories($data);
    break;
case('edit_category'):
  edit_category();
   break;
case('edit_subcategory'):
  edit_subcategory();
   break;
 case('edit_department'):
 edit_department();
   break;
 case('edit_store'):
 edit_store();

   break;
 case('edit_deal'):
 edit_deal();
   break;

 case('new_store'):
   
   create_store();
   break;
 case('new_department'):
  create_department();
   break;
 case('create_family'):
 $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')
                             ,'parent_key'=>array('type'=>'key')
                             ));
create_family($data);
   break;
case('edit_departments'):
  list_departments_for_edition();
 
   break;
case('edit_stores_list'):

case('edit_stores'):
   list_stores_for_edition();
   
   break;
case('edit_families'):
list_families_for_edition();
 
  break;
case('edit_products'):
  list_products_for_edition();
  break;
case('edit_product_categories'):
    list_edit_product_categories();
    break;

case('delete_categories'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key')
                                  ,'delete_type'=>array('type'=>'string')
                         ));
    delete_categories($data);
    break;


 default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }
function create_store(){
  global $editor;
  if(isset($_REQUEST['name'])  and  isset($_REQUEST['code'])   ){
    $store=new Store('find',array(
				  'Store Code'=>$_REQUEST['code']
				  ,'Store Name'=>$_REQUEST['name']
				  ,'editor'=>$editor
				  ),'create');
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
}
function create_department(){
  global $editor;
 if(isset($_REQUEST['name'])  and  isset($_REQUEST['code'])   ){
     $store_key=$_SESSION['state']['store']['id'];
     $department=new Department('find',array(
					      'Product Department Code'=>$_REQUEST['code']
					      ,'Product Department Name'=>$_REQUEST['name']
					      ,'Product Department Store Key'=>$store_key
					      ,'editor'=>$editor
					       ),'create');
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
}
function create_family($data){
  global $editor;
  
  
  
 if(array_key_exists('Product Family Name',$data['values']) 
 and  array_key_exists('Product Family Code',$data['values']) 
 and  array_key_exists('Product Family Special Characteristic',$data['values']) 
 and  array_key_exists('Product Family Description',$data['values']) 
 
 ){
     $department_key=$data['parent_key'];
     
     $family=new Family('create',array(
					      
				       'Product Family Code'=>$data['values']['Product Family Code']
				       ,'Product Family Name'=>$data['values']['Product Family Name']
				       ,'Product Family Description'=>$data['values']['Product Family Description']
				       ,'Product Family Special Characteristic'=>$data['values']['Product Family Special Characteristic']
				       ,'Product Family Main Department Key'=>$department_key
				       ,'editor'=>$editor
				       ));
     if(!$family->new){
      
        $response=array('state'=>200,'msg'=>$family->msg,'action'=>'found','object_key'=>$family->id);
     }else{
     
        $response=array('state'=>200,'msg'=>$family->msg,'action'=>'created');
     }

    


 }
 else
     $response=array('state'=>400,'msg'=>_('Error'));
   echo json_encode($response);
}

function delete_part_new_product($sku){

  unset($_SESSION['state']['new_product']['parts'][$sku]);
  print 'Ok';
  
   
}


function delete_family(){
 if(!isset($_REQUEST['id']))
     return 'Error: no family specificated';
   if(!is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0 )
     return 'Error: wrong family id';
   if(!isset($_REQUEST['delete_type'])  or !($_REQUEST['delete_type']=='delete' or $_REQUEST['delete_type']=='discontinue'  )  )
     return 'Error: delete type no supplied';

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
   
}
function delete_store(){
  if(!isset($_REQUEST['id']))
     return 'Error: no store key';
   if(!is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0 )
     return 'Error: wrong store id';
   if(!isset($_REQUEST['delete_type'])  or !($_REQUEST['delete_type']=='delete' or $_REQUEST['delete_type']=='close'  )  )
     return 'Error: delete type no supplied';

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
   
}
function delete_department(){
  if(!isset($_REQUEST['id']))
     return 'Error: no department key';
   if(!is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0 )
     return 'Error: wrong department id';
   if(!isset($_REQUEST['delete_type'])  or !($_REQUEST['delete_type']=='delete' or $_REQUEST['delete_type']=='discontinue'  )  )
     return 'Error: delete type no supplied';

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
   
   
}
function edit_store(){
  $store=new Store($_REQUEST['id']);
  global $editor;
  $store->editor=$editor;
   $store->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));
     
   if($store->updated){
     $response= array('state'=>200,'newvalue'=>$store->new_value,'key'=>$_REQUEST['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$store->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response);  
}
function edit_department(){
  $department=new Department($_REQUEST['id']);
  global $editor;
  $department->editor=$editor;

 $department->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));
   
   //   $response= array('state'=>400,'msg'=>print_r($_REQUEST);
   //echo json_encode($response);  
   // exit;
   if($department->updated){
     $response= array('state'=>200,'newvalue'=>$department->new_value,'key'=>$_REQUEST['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$department->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response);  

}
function edit_product(){
  $product=new product('pid',$_REQUEST['pid']);
  global $editor;
 $product->editor=$editor;
   $translator=array(
		     'name'=>'Product Name',
		     'sdescription'=>'Product Special Characteristic',
		     'special_characteristic'=>'Product Special Characteristic',
		     'description'=>'Product Description',

		     'price'=>'Product Price',
		     'unit_price'=>'Product Unit Price',
		     'margin'=>'Product Margin',
		     'unit_rrp'=>'Product RRP Per Unit',
		     'rrp'=>'Product RRP Per Unit',

		     'sales_type'=>'Product Sales Type',
		     'unit_weight'=>'Product Net Weight Per Unit',
		     'outer_weight'=>'Product Gross Weight',

		     );
    
    if(array_key_exists($_REQUEST['key'],$translator))
      $key=$translator[$_REQUEST['key']];
    else
      $key=$_REQUEST['key'];

    $product->update($key,stripslashes(urldecode($_REQUEST['newvalue'])));
   

   if($product->updated){
     $response= array('state'=>200,'newvalue'=>$product->new_value,'newdata'=>$product->new_data,'key'=>$_REQUEST['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$product->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response); 
}

function edit_category(){
  $category=new Category('category_key',$_REQUEST['category_key']);
  global $editor;
 $category->editor=$editor;
$key=$_REQUEST['key'];
  if($key=='Attach'){
    // print_r($_FILES);
    $note=stripslashes(urldecode($_REQUEST['newvalue']));
    $target_path = "uploads/".'attach_'.date('U');
    $original_name=$_FILES['testFile']['name'];
    $type=$_FILES['testFile']['type'];
    $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

    if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
      $category->add_attach($target_path,$data);
      
    }
  }else{
    

    
    $key_dic=array(
		   'name'=>'Category Name'
		   ,'id'=>'Category Key'
		 // ,'alias'=>'Staff Alias'
		  // ,'type'=>'Staff Type'
		  
		   
    );
    if(array_key_exists($_REQUEST['key'],$key_dic))
       $key=$key_dic[$_REQUEST['key']];
    
    $update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
    $category->update($update_data);
  }


    if ($category->updated) {
        $response= array('state'=>200,'newvalue'=>$category->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$category->msg,'key'=>$_REQUEST['key']);
    }
   echo json_encode($response); 
}


function edit_subcategory(){
$category_key=$_REQUEST['category_key'];

  $category=new Category('category_key',$_REQUEST['category_key']);
  global $editor;
 $category->editor=$editor;
$key=$_REQUEST['key'];
  if($key=='Attach'){
    $note=stripslashes(urldecode($_REQUEST['newvalue']));
    $target_path = "uploads/".'attach_'.date('U');
    $original_name=$_FILES['testFile']['name'];
    $type=$_FILES['testFile']['type'];
    $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

    if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
      $category->add_attach($target_path,$data);
      
    }
  }else{
    

    
    $key_dic=array(
		   'name'=>'Category Name'
		   ,'id'=>'Category Key'
		 // ,'alias'=>'Staff Alias'
		  // ,'type'=>'Staff Type'
		  
		   
    );
    if(array_key_exists($_REQUEST['key'],$key_dic))
       $key=$key_dic[$_REQUEST['key']];
if($key=='subcategory_name')$key='Category Name';
    echo "key=".$key;
    $update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));echo " updte data=".$update_data;
    $category->update($update_data);
  }


    if ($category->updated) {
        $response= array('state'=>200,'newvalue'=>$category->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$category->msg,'key'=>$_REQUEST['key']);
    }
   echo json_encode($response); 
}

function edit_family($data){
  $family=new family($data['id']);
 global $editor;
 $family->editor=$editor;
 $family->update(array($data['key']=>stripslashes(urldecode($data['newvalue']))));
 

   if($family->updated){
     $response= array('state'=>200,'newvalue'=>$family->new_value,'key'=>$data['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$family->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response); 
}


function edit_deal(){
 $deal=new deal($_REQUEST['deal_key']);
 global $editor;
 $deal->editor=$editor;
 $deal->update(array($_REQUEST['key']=>stripslashes(urldecode($_REQUEST['newvalue']))));
 

   if($deal->updated){
     $response= array('state'=>200,'newvalue'=>$deal->new_value,'key'=>$_REQUEST['key'],'description'=>$deal->get('Description'));
	  
   }else{
     $response= array('state'=>400,'msg'=>$deal->msg,'key'=>$_REQUEST['key']);
   }
   echo json_encode($response); 
}



function upload_image($subject='product'){

	$target_path = "app_files/tmp/";
 	$filename='pimg_'.date('U');
 	
 	if (!file_exists($target_path)) {
    	$response= array('state'=>400,'msg'=>"Image tmp directory do not exist (".$target_path.")");
 			echo json_encode($response); 
 			return;
	} 
 	
 
                        
  	if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path.$filename )) {
   		include_once('class.Image.php');
   		$name=preg_replace('/\.[a-z]+$/i','',$_FILES['testFile']['name']);
	   	$name=preg_replace('/[^a-z^\.^0-9]/i','_',$name);
   		$data=array(
	    	'file'=>$filename
	   		,'path'=>'app_files/pics/assets/'
	    	,'name'=>$name
	    	,'original_name'=>$_FILES['testFile']['name']
	    	,'type'=>$_FILES['testFile']['type']
	    	,'caption'=>''
	    	);
		$image=new Image('find',$data,'create');
	   	if(!$image->error){
	   		$subject=$_REQUEST['subject'];
		  	if($subject=='product')
      			$subject=new product('pid',$_REQUEST['subject_key']);
		  	if($subject=='family')
      			$subject=new Family('id',$_REQUEST['subject_key']);
  		 	if($subject=='department')
      			$subject=new Department('id',$_REQUEST['subject_key']);
  			$subject->add_image($image->id);
  			$subject->update_main_image();
  			$msg=array(
	     		'set_main'=>_('Set Main')
	     		,'main'=>_('Main Image')
	     		,'caption'=>_('Caption')
	     		,'save_caption'=>_('Save caption')
	     		,'delete'=>_('Delete')
	     		);
  			$response= array('state'=>200,'msg'=>$msg,'image_key'=>$image->id,'data'=>$subject->new_value);
  			echo json_encode($response); 
           	return;
   		}else{
     		$response= array('state'=>400,'msg'=>$image->msg);
 			echo json_encode($response); 
 			return;
		}
  	}else{
  		$response= array('state'=>400,'msg'=>'no image');
 			echo json_encode($response); 
 			return;
  	}
  }


function edit_product_multi(){

  if(!isset($_REQUEST['value'])  and isset($_REQUEST['newvalue']) )
    $_REQUEST['value']=$_REQUEST['newvalue'];
  if(!isset($_REQUEST['id']) or !isset($_REQUEST['key']) or  !isset($_REQUEST['value'])       ){
    $response= array('state'=>400,'msg'=>'error','key'=>$_REQUEST['key']);
    echo json_encode($response); 
    return;
  }

  $product=new product('pid',$_REQUEST['id']);
  $result=array();
  $updated=false;
  if($_REQUEST['key']=='array'){
    $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
    $tmp=preg_replace('/\\\\\"/','"',$tmp);
    $raw_data=json_decode($tmp, true);
   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   
   $result=array();
   //print_r($raw_data);
   foreach($raw_data as $key=>$value){
     $product->update($key,$value);
   }
  }else{

    $translator=array('name'=>'Product Name');
    
    if(array_key_exists($_REQUEST['key'],$translator))
      $key=$translator[$_REQUEST['key']];
    else
      $key=$_REQUEST['key'];
    $value=stripslashes(urldecode($_REQUEST['value']));  
    $product->update($key,$value);
  }
  

  $response= array('state'=>200,'updated_fields'=>$product->updated_fields,'errors_while_updating'=>$product->errors_while_updating);
  echo json_encode($response);  
}

function list_products_for_edition(){
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
//      switch($mode){
//      case('same_code'):
//        $where.=sprintf(" and `Product Most Recent`='Yes' ");
//        break;
//      case('same_id'):
//        $group=' group by `Product ID`';
//        break;
//      }
//    
     switch($restrictions){
     case('for_public_sale'):
       $where.=sprintf(" and `Product Sales Type`='Public Sale'  ");
       break;
     case('for_private_sale'):
       $where.=sprintf(" and  `Product Sales Type`='Private Sale' ");
       break;
     case('not_for_sale'):
       $where.=sprintf(" and `Product Sales Type` in ('Not For Sale')  ");
       break;
     case('discontinued'):
       $where.=sprintf(" and `Product Sales Type` in ('Discontinued')  ");
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
//print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   mysql_free_result($result);
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total  from `Product Dimension`  P  $where ";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $filtered=$row['total']-$total; $total_records=$row['total'];
     }
     mysql_free_result($result);

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
     elseif($order=='shortname')
     $order='`Product XHTML Short Description`';
    
  else
    $order='`Product Code`';

  $sql="select *  from `Product Dimension` P  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
  
  $res = mysql_query($sql);
  $adata=array();
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    if($row['Product Total Quantity Ordered']==0 and  $row['Product Total Quantity Invoiced']==0 and  $row['Product Total Quantity Delivered']==0  ){
      $delete='<img src="art/icons/delete.png" /> <span>'._('Delete').'<span>';
      $delete_type='delete';
    }else{
  $delete='<img src="art/icons/discontinue.png" /> <span>'._('Discontinue').'<span>';
      $delete_type='discontinue';
    }

    if($row['Product RRP']!=0 and is_numeric($row['Product RRP']))
      $customer_margin=_('Margin').' '.percentage($row['Product RRP']-$row['Product Price'],$row['Product Price']);
    else
      $customer_margin=_('ND');
    
    if($row['Product Price']!=0 and is_numeric($row['Product Cost']))
      $margin= percentage($row['Product Price']-$row['Product Cost'],$row['Product Cost']);     
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


    if($row['Product Record Type']=='In Process'){

      if($row['Product Editing Price']!=0 and is_numeric($row['Product Cost']))
	$margin=number(100*($row['Product Editing Price']-$row['Product Cost'])/$row['Product Editing Price'],1).'%';
      else
	$margin=_('ND');
      global $myconf;
      $in_common_currency=$myconf['currency_code'];
      $in_common_currency_price='';
      if($row['Product Currency']!= $in_common_currency){
	if(!isset($exchange[$row['Product Currency']])){
	  $exchange[$row['Product Currency']]=currency_conversion($row['Product Currency'],$in_common_currency);
	  
	}
	$in_common_currency_price='('.money($exchange[$row['Product Currency']]*$row['Product Editing Price']).') ';
	
      }
      


      $processing=_('Editing');
      $name=$row['Product Editing Name'];
      $sdescription=$row['Product Editing Special Characteristic'];
      $famsdescription=$row['Product Editing Family Special Characteristic'];
      $price=money($row['Product Editing Price'],$row['Product Currency']);
      if(is_numeric($row['Product Editing Units Per Case']) and $row['Product Editing Units Per Case']!=1){
	$unit_price=money($row['Product Editing Price']/$row['Product Editing Units Per Case'],$row['Product Currency']);
      }else
	$unit_price='?';
      $units=$row['Product Editing Units Per Case'];
      $unit_type=$row['Product Editing Unit Type'];
      $units_info='';
    }else{

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


      $processing=_('Live');
      $name=$row['Product Name'];
      $sdescription=$row['Product Special Characteristic'];

      $price=money($row['Product Price'],$row['Product Currency']);
      $unit_price=money($row['Product Price']/$row['Product Units Per Case'],$row['Product Currency']);
      $units=$row['Product Units Per Case'];
      $unit_type=$row['Product Unit Type'];
      $units_info=number($row['Product Units Per Case']);
    }


    if($row['Product Record Type']=='New')
      $processing=_('Editing');

    switch($row['Product Sales Type']){
    case('Public Sale'):
	$sales_type=_('Public Sale');
	break;
   case('Private Sale'):
	$sales_type=_('Private Sale');
	break;
    case('Not for Sale'):
      $sales_type=_('Not For Sale');	
      break;
      default:
         $sales_type=$row['Product Sales Type'];
 
    }


       switch($row['Product Record Type']){
      default:
         $record_type=$row['Product Record Type'];
 
    }
    
    
    switch($row['Product Web State']){
    case('Online Force Out of Stock'):
      $web_state=_('Out of Stock');
	break;
    case('Online Auto'):
      $web_state=_('Auto');
      break;
    case('Unknown'):
      $web_state=_('Unknown');
    case('Offline'):
      $web_state=_('Offline');
      break;
    case('Online Force Hide'):
      $web_state=_('Hide');	
      break;
    case('Online Force For Sale'):
      $web_state=_('Sale');	
      break;

    }

    


$adata[]=array(
	       'pid'=>$row['Product ID'],
	       'code'=>$row['Product Code'],
	       'code_price'=>sprintf('%s <a href="edit_product.php?pid=%d&edit=prices"><img src="art/icons/external.png"/></a>',$row['Product Code'],$row['Product ID']),
	       'smallname'=>$row['Product XHTML Short Description'],

	       'name'=>$row['Product Name'],
	       'processing'=>$processing,
	       'sales_type'=>$sales_type,
	       'record_type'=>$record_type,

	       'web_state'=>$web_state,
	       'state_info'=>$sales_type,
	       'sdescription'=>$sdescription,

	       'units'=>$units,
	       'units_info'=>$units_info,

	       'unit_type'=>$unit_type,
	       'price'=>$price,
	       'unit_price'=>$unit_price,
	       'margin'=>$margin,

	       'price_info'=>$in_common_currency_price,

	       'unit_rrp'=>money(($row['Product RRP']/$row['Product Units Per Case']),$row['Product Currency']),
	       'rrp_info'=>$customer_margin,

	       'delete'=>$delete,
	       'delete_type'=>$delete_type,
	       'go'=>sprintf("<a href='product.php?pid=%d&edit=1'><img src='art/icons/page_go.png' alt='go'></a>",$row['Product ID'])
	       
	       );
  }
 // print $rtext;
mysql_free_result($res);

//  $rtext='21 records';
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
}
function list_families_for_edition(){  
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
       $where=sprintf('  where `Product Family Main Department Key`=%d',$_SESSION['state']['department']['id']);
       break;
     case('none'):
         $where=sprintf(' where true ');
       break;
     }
   }
   


   $filter_msg='';



  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
  
  
  //$_SESSION['state']['families']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  $conf_table='families';
    $_SESSION['state'][$conf_table]['table']['order']=$order;
    $_SESSION['state'][$conf_table]['table']['order_dir']=$order_dir;
    $_SESSION['state'][$conf_table]['table']['nr']=$number_results;
    $_SESSION['state'][$conf_table]['table']['sf']=$start_from;
    $_SESSION['state'][$conf_table]['table']['where']=$where;
    $_SESSION['state'][$conf_table]['table']['f_field']=$f_field;
    $_SESSION['state'][$conf_table]['table']['f_value']=$f_value;

  
  //  $where.=" and `Product Department Key`=".$id;

  
  
  $filter_msg='';
  $wheref='';
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  
    $sql="select count(*) as total from `Product Family Dimension`   F   $where $wheref";

   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   mysql_free_result($result);
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total  from `Product Family Dimension`  F  $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $filtered=$row['total']-$total; $total_records=$row['total'];
     }
mysql_free_result($result);
   }
  
   $rtext=sprintf(ngettext("%d family", "%d families", $total_records), $total_records);
   if($total_records>$number_results)
     $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
     $rtext_rpp='('._('Showing all').')';
   
   $_order=$order;
   $_dir=$order_direction;
   
  if($order=='code')
    $order='`Product Family Code`';
  elseif($order=='name')
    $order='`Product Family Name`';
  
  $sql="select `Product Family Sales Type`,F.`Product Family Key`,`Product Family Code`,`Product Family Name`,`Product Family For Public Sale Products`+`Product Family In Process Products`+`Product Family Not For Sale Products`+`Product Family Discontinued Products`+`Product Family Unknown Sales State Products` as Products  from `Product Family Dimension` F  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
  $res = mysql_query($sql);
  $adata=array();
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
   
    
    switch ($row['Product Family Sales Type']) {
    case 'Public Sale':
        $sales_type=_('Public Sale');
        break;
    case 'Private Sale':
        $sales_type=_('Private Sale');
        break;
    case 'Not for Sale':
        $sales_type=_('Not for Sale');
        break;        
}

    
    
$adata[]=array(
	       'id'=>$row['Product Family Key'],
	       'edit'=>sprintf('<a href="family.php?id=%d&edit=1">%03d<a>',$row['Product Family Key'],$row['Product Family Key']),
	       'code'=>$row['Product Family Code'],
	       'name'=>$row['Product Family Name'],
	       'sales_type'=>$sales_type,
	      
	       'go'=>sprintf("<a href='family.php?id=%d&edit=1'><img src='art/icons/page_go.png' alt='go'></a>",$row['Product Family Key'])

		   );
  }
mysql_free_result($res);
  $response=array('resultset'=>
		  array(
		  
		       'state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
		  
		  
		 
			)
		  );

  echo json_encode($response);
  }
function list_stores_for_edition(){
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

    $_SESSION['state']['stores']['table']['order']=$order;
     $_SESSION['state']['stores']['table']['order_dir']=$order_direction;
      $_SESSION['state']['stores']['table']['nr']=$number_results;
       $_SESSION['state']['stores']['table']['sf']=$start_from;
        $_SESSION['state']['stores']['table']['where']=$where;
         $_SESSION['state']['stores']['table']['_field']=$f_field;
          $_SESSION['state']['stores']['table']['f_value']=$f_value;
    
 
$where=" ";
   
 $filter_msg='';
  $wheref='';
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


  



   $sql="select count(*) as total from `Store Dimension`   $where $wheref";

   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   mysql_free_result($result);
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total `Store Dimension`   $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $filtered=$row['total']-$total;$total_records=$row['total'];
     }
mysql_free_result($result);
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
   //   print "$sql";
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     if($row['Store For Public Sale Products']>0){
       $delete='<img src="art/icons/discontinue.png" /> <span conclick="close_store('.$row['Store Key'].')"  id="del_'.$row['Store Key'].'" style="cursor:pointer">'._('Close').'<span>';
       $delete_type='close';
     }else{
       $delete='<img src="art/icons/delete.png" /> <span conclick="delete_store('.$row['Store Key'].')"  id="del_'.$row['Store Key'].'" style="cursor:pointer">'._('Delete').'<span>';
       $delete_type='delete';
     }
     $adata[]=array(
		    'id'=>$row['Store Key']
		    ,'code'=>$row['Store Code']
		    ,'name'=>$row['Store Name']
		    ,'delete'=>$delete
		    ,'delete_type'=>$delete_type
		    ,'go'=>sprintf("<a href='store.php?id=%d&edit=1'><img src='art/icons/page_go.png' alt='go'></a>",$row['Store Key'])
		  );
  }


   $total=mysql_num_rows($res);
 mysql_free_result($res);
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
}
function list_departments_for_edition(){
 if(!isset($_REQUEST['parent']))
     $parent='store';
  else
    $parent=$_REQUEST['parent'];


  if($parent=='store')  
    $conf=$_SESSION['state']['store']['table'];
  else
    $conf=$_SESSION['state']['departments']['table'];
  
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

$conf_table='store';

  // $_SESSION['state']['store']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    $_SESSION['state'][$conf_table]['table']['order']=$order;
    $_SESSION['state'][$conf_table]['table']['order_dir']=$order_dir;
    $_SESSION['state'][$conf_table]['table']['nr']=$number_results;
    $_SESSION['state'][$conf_table]['table']['sf']=$start_from;
    $_SESSION['state'][$conf_table]['table']['where']=$where;
    $_SESSION['state'][$conf_table]['table']['f_field']=$f_field;
    $_SESSION['state'][$conf_table]['table']['f_value']=$f_value;
   
   
   //$where=$where.' '.sprintf(" and `Product Department Store Key`=%d",$store_id);
   
   $filter_msg='';
   $wheref='';
   if($f_field=='name' and $f_value!='')
     $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
   
   
   switch($parent){
   case('store'):
     $where=sprintf(' where `Product Department Store Key`=%d',$_SESSION['state']['store']['id']);
     break;
   case('none'):
     $where=sprintf(' where true ');
     break;
   }
  

   $sql="select count(*) as total from `Product Department Dimension`   $where $wheref";
   // print $sql;
   $res = mysql_query($sql); 
   if($row=mysql_fetch_array($res)) {
     $total=$row['total'];
   }
   mysql_free_result($res);
   if($wheref==''){
       $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total `Product Department Dimension`   $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      	$total_records=$row['total'];
	$filtered=$total_records-$total;
     }
     mysql_free_result($result);

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
   elseif($order=='sales_type')
    $order='`Product Department Sales Type`';
else
$order='`Product Department Name`';
    $sql="select D.`Product Department Sales Type`, D.`Product Department Key`,`Product Department Code`,`Product Department Name`,`Product Department For Public Sale Products`+`Product Department For Private Sale Products`+`Product Department In Process Products` as Products  from `Product Department Dimension` D  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    
    $res = mysql_query($sql);
    $adata=array();
   // print "$sql";
    while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
//      if($row['Products']>0){
//	$delete='<img src="art/icons/discontinue.png" /> <span  style="cursor:pointer">'._('Discontinue').'<span>';//
//	$delete_type='discontinue';
//      }else{
//	$delete='<img src="art/icons/delete.png" /> <span  style="cursor:pointer">'._('Delete').'<span>';
//      $delete_type='delete';
//    }

switch ($row['Product Department Sales Type']) {
    case 'Public Sale':
        $sales_type=_('Public Sale');
        break;
    case 'Private Sale':
        $sales_type=_('Private Sale');
        break;
    case 'Not for Sale':
        $sales_type=_('Not for Sale');
        break;        
}



      $adata[]=array(
		     'id'=>$row['Product Department Key'],
		     'name'=>$row['Product Department Name'],
		     'code'=>$row['Product Department Code'],
		     'sales_type'=>$sales_type,
		     //'delete_type'=>$delete_type,
		     'go'=>sprintf("<a href='department.php?id=%d&edit=1'><img src='art/icons/page_go.png' alt='go'></a>",$row['Product Department Key'])
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
			 'rtext_rpp'=>$rtext_rpp,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}


function delete_image(){
  $scope=$_REQUEST['scope'];
  $scope_key=$_REQUEST['scope_key'];
  $image_key=$_REQUEST['image_key'];

if($scope=='product')
    $subject=new Product('pid',$scope_key);
elseif($scope=='family')
    $subject=new Family($scope_key);
elseif($scope=='department')
    $subject=new Department($scope_key);    


$subject->remove_image($image_key);
$image=new Image($image_key);

$image->delete();

if($subject->updated){
 $response=array('state'=>200,'msg'=>$subject->msg,'image_key'=>$image->id);
    echo json_encode($response);

}else{
 $response=array('state'=>400,'msg'=>$subject->msg);
    echo json_encode($response);

}
return;

}



function list_pages_for_edition(){


  $parent='store';

   if( isset($_REQUEST['parent']))
     $parent= $_REQUEST['parent'];

   if($parent=='store')
     $parent_id=$_SESSION['state']['store']['id'];
   else
     return;

   $conf=$_SESSION['state'][$parent]['pages'];

   


   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   

   if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     if($start_from>0){
       $page=floor($start_from/$number_results);
       $start_from=$start_from-$page;
     }
   
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
   

  

   
   $_SESSION['state'][$parent]['pages']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  // print_r($_SESSION['tables']['families_list']);

  //  print_r($_SESSION['tables']['families_list']);
 
 $where=' where `Page Type`="Store" ';
 if($parent=='store')
     $where.=sprintf("and `Page Store Function` in ('Front Page Store','Search','Information','Unknown','Store Catalogue') and `Page Store Key`=%d ",$parent_id);
   
   
 $filter_msg='';
  $wheref='';
  if($f_field=='description' and $f_value!='')
    $wheref.=" and  CONCAT(`Charge Description`,' ',`Charge Terms Description`) like '".addslashes($f_value)."%'";
  elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Charge Name` like '".addslashes($f_value)."%'";




  
 


   $sql="select count(*) as total from `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  $where $wheref";
   // print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
mysql_free_result($result);
     if($wheref==''){
       $filtered=0;$total_records=$total;
   }else{
     $sql="select count(*) as total `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)   $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $total_records=$row['total'];
	 $filtered=$total_records-$total;
     }
mysql_free_result($result);

   }

  
     $rtext=$total_records." ".ngettext('charge','charges',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
     else
       $rtext_rpp=' ('._('Showing all').')';

  if($total==0 and $filtered>0){
       switch($f_field){
     case('name'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with this name ")." <b>".$f_value."*</b> ";
	 break;
       case('description'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with description like ")." <b>".$f_value."*</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
      case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with name like')." <b>".$f_value."*</b>";
       break; 
     case('description'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with description like')." <b>".$f_value."*</b>";
       break; 
     }
   }else
      $filter_msg='';

   $_dir=$order_direction;
   $_order=$order;
   
  
  if($order=='title')
     $order='`Page Title`';
   else
         $order='`Page Section`';


 
   $sql="select *  from `Page Dimension`  P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`) $where    order by $order $order_direction limit $start_from,$number_results    ";

   $res = mysql_query($sql);
   
   $total=mysql_num_rows($res);
   
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
    
    $adata[]=array(
           'id'=>$row['Page Key'],		
		  'section'=>$row['Page Section'],
		  'title'=>$row['Page Title'],
		  'go'=>sprintf("<a href='edit_page.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Page Key'])

		  
		   );
  }
  mysql_free_result($res);

  

  // if($total<$number_results)
  //  $rtext=$total.' '.ngettext('store','stores',$total);
  //else
  //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

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

function list_charges_for_edition(){


  $parent='store';

   if( isset($_REQUEST['parent']))
     $parent= $_REQUEST['parent'];

   if($parent=='store')
     $parent_id=$_SESSION['state']['store']['id'];
   else
     return;

   $conf=$_SESSION['state'][$parent]['charges'];

   


   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   

   if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     if($start_from>0){
       $page=floor($start_from/$number_results);
       $start_from=$start_from-$page;
     }
   
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
   

  

   
   $_SESSION['state'][$parent]['charges']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  // print_r($_SESSION['tables']['families_list']);

  //  print_r($_SESSION['tables']['families_list']);
   if($parent=='store')
     $where=sprintf("where  `Store Key`=%d ",$parent_id);
   else
     $where=sprintf("where true ");
   
 $filter_msg='';
  $wheref='';
  if($f_field=='description' and $f_value!='')
    $wheref.=" and  CONCAT(`Charge Description`,' ',`Charge Terms Description`) like '".addslashes($f_value)."%'";
  elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Charge Name` like '".addslashes($f_value)."%'";




  
 


   $sql="select count(*) as total from `Charge Dimension`   $where $wheref";
   // print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
mysql_free_result($result);
     
     if($wheref==''){
       $filtered=0;$total_records=$total;
   }else{
     $sql="select count(*) as total `Charge Dimension`   $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $total_records=$row['total'];
	 $filtered=$total_records-$total;
     }
mysql_free_result($result);

   }

  
     $rtext=$total_records." ".ngettext('charge','charges',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
     else
       $rtext_rpp=' ('._('Showing all').')';

  if($total==0 and $filtered>0){
       switch($f_field){
     case('name'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with this name ")." <b>".$f_value."*</b> ";
	 break;
       case('description'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with description like ")." <b>".$f_value."*</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
      case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with name like')." <b>".$f_value."*</b>";
       break; 
     case('description'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with description like')." <b>".$f_value."*</b>";
       break; 
     }
   }else
      $filter_msg='';

   $_dir=$order_direction;
   $_order=$order;
   
   if($order=='name')
     $order='`Charge Name`';
   elseif($order=='description')
     $order='`Charge Description`,`Charge Terms Description`';
   else
     $order='`Charge Name`';

 
   $sql="select *  from `Charge Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

   $res = mysql_query($sql);
   
   $total=mysql_num_rows($res);
   
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
    
    $adata[]=array(
		  'name'=>$row['Charge Name'],
		  'description'=>$row['Charge Description'].' '.$row['Charge Terms Description'],
		  
		  
		   );
  }
  mysql_free_result($res);

  

  // if($total<$number_results)
  //  $rtext=$total.' '.ngettext('store','stores',$total);
  //else
  //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

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


function list_campaigns_for_edition(){


   $parent='store';

   if( isset($_REQUEST['parent']))
     $parent= $_REQUEST['parent'];

   if($parent=='store')
     $parent_id=$_SESSION['state']['store']['id'];
   else
     return;

   $conf=$_SESSION['state'][$parent]['campaigns'];


   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   

   if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     if($start_from>0){
       $page=floor($start_from/$number_results);
       $start_from=$start_from-$page;
     }
   
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
   
   
   $_SESSION['state'][$parent]['campaigns']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   
   if($parent=='store')
     $where=sprintf("where  `Store Key`=%d    ",$parent_id);
   else
     $where=sprintf("where true ");;
   
   $filter_msg='';
  $wheref='';
  if($f_field=='description' and $f_value!='')
    $wheref.=" and  `Campaign Description` like '".addslashes($f_value)."%'";
  elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Campaign Name` like '".addslashes($f_value)."%'";

   $sql="select count(*) as total from `Campaign Dimension`   $where $wheref";
   //  print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
mysql_free_result($result);
     
     if($wheref==''){
       $filtered=0;$total_records=$total;
   }else{
     $sql="select count(*) as total `Campaign Dimension`   $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $total_records=$row['total'];
	 $filtered=$total_records-$total;
     }
mysql_free_result($result);

   }

  
     $rtext=$total_records." ".ngettext('campaign','campaigns',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
     else
       $rtext_rpp=' ('._('Showing all').')';

  if($total==0 and $filtered>0){
       switch($f_field){
     case('name'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with this name ")." <b>".$f_value."*</b> ";
	 break;
       case('description'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with description like ")." <b>".$f_value."*</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
      case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with name like')." <b>".$f_value."*</b>";
       break; 
     case('description'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with description like')." <b>".$f_value."*</b>";
       break; 
     }
   }else
      $filter_msg='';

   $_dir=$order_direction;
   $_order=$order;
   
   if($order=='name')
     $order='`Campaign Name`';
   elseif($order=='description')
     $order='`Campaign Description`';
   else
     $order='`Campaign Name`';

 
   $sql="select *  from `Campaign Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

   $res = mysql_query($sql);
   
   $total=mysql_num_rows($res);
   
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
    $sql=sprintf("select * from `Campaign Deal Schema`  where `Campaign Key`=%d  ",$row['Campaign Key']);
    $res2 = mysql_query($sql);
    $deals='<ul style="padding:10px 20px">';
    while($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
      $deals.=sprintf("<li style='list-style-type: circle' >%s</li>",$row2['Deal Name']);
    }
    $deals.='</ul>';
    $adata[]=array(
		  'name'=>$row['Campaign Name'],
		  'description'=>$row['Campaign Description'].$deals
		  
		  
		   );
  }
  mysql_free_result($res);

  

  // if($total<$number_results)
  //  $rtext=$total.' '.ngettext('store','stores',$total);
  //else
  //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

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


function list_deals_for_edition(){


   $parent='store';
 if( isset($_REQUEST['parent']))
     $parent= $_REQUEST['parent'];

   if($parent=='store')
     $parent_id=$_SESSION['state']['store']['id'];
   elseif($parent=='department')
     $parent_id=$_SESSION['state']['department']['id'];
  elseif($parent=='family')
     $parent_id=$_SESSION['state']['family']['id'];
  elseif($parent=='product')
     $parent_id=$_SESSION['state']['product']['pid'];
   else
     return;
  

   $conf=$_SESSION['state'][$parent]['deals'];


   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   

   if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     if($start_from>0){
       $page=floor($start_from/$number_results);
       $start_from=$start_from-$page;
     }
   
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
   
   
   $_SESSION['state'][$parent]['deals']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   
   if($parent=='store')
     $where=sprintf("where  D.`Store Key`=%d and D.`Deal Trigger`='Order'    ",$parent_id);
   elseif($parent=='department')
     $where=sprintf("where    D.`Deal Trigger`='Department' and  D.`Deal Trigger Key`=%d   ",$parent_id);
   elseif($parent=='family')
     $where=sprintf("where    D.`Deal Trigger`='Family' and  D.`Deal Trigger Key`=%d   ",$parent_id);
   elseif($parent=='product')
     $where=sprintf("where    D.`Deal Trigger`='Product' and  D.`Deal Trigger Key`=%d   ",$parent_id);
   else
     $where=sprintf("where true ");;


   
   $filter_msg='';
  $wheref='';
 
if($f_field=='description' and $f_value!='')
    $wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";

  elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";

   $sql="select count(*) as total from `Deal Dimension` D   $where $wheref";
   //  print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
mysql_free_result($result);
     
     if($wheref==''){
       $filtered=0;$total_records=$total;
   }else{
     $sql="select count(*) as total `Deal Dimension`  D  $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $total_records=$row['total'];
	 $filtered=$total_records-$total;
     }
mysql_free_result($result);

   }

  
     $rtext=$total_records." ".ngettext('deal','deals',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
     else
       $rtext_rpp=' ('._('Showing all').')';

  if($total==0 and $filtered>0){
       switch($f_field){
     case('name'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
	 break;
       case('description'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
      case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
       break; 
     case('description'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
       break; 
     }
   }else
      $filter_msg='';

   $_dir=$order_direction;
   $_order=$order;
   
   if($order=='name')
     $order='D.`Deal Name`';
   elseif($order=='description')
      $order='`Deal Terms Description`,`Deal Allowance Description`';
   else
     $order='D.`Deal Name`';

 
   $sql="select D.`Deal Trigger`,`Deal Key`,D.`Deal Name`,`Campaign Deal Schema Key`,`Campaign Name`,`Campaign Deal Schema Key`  from `Deal Dimension` D left join `Campaign Deal Schema`CDS  on (CDS.`Deal Schema Key`=`Campaign Deal Schema Key`) left join `Campaign Dimension`C  on (CDS.`Campaign Key`=C.`Campaign Key`)  $where    order by $order $order_direction limit $start_from,$number_results    ";
  // print $sql;
   $res = mysql_query($sql);
   $total=mysql_num_rows($res);
   $adata=array();
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     // $meta_data=preg_split('/,/',$row['Deal Allowance Metadata']);
     
     $deal=new Deal($row['Deal Key']);

     // print_r($deal->terms_input_form());

     //print_r($deal->allowance_input_form());
     
     $input_allowance='';
     foreach($deal->allowance_input_form() as $form_data){
       $input_allowance.=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
       <td style="width:15em"  style="text-align:left"><input id="deal_allowance%d" onKeyUp="deal_allowance_changed(%d)" %s class="%s" style="width:5em" value="%s" ovalue="%s" /> %s 
       <span id="deal_allowance_save%d" style="visibility:hidden" class="state_details" onClick="deal_allowance_save(%d)">'._('Save').'</span> 
       <span id="deal_allowance_reset%d" style="visibility:hidden" style="margin-left:10px "class="state_details"  onClick="deal_allowance_reset(%d)">'._('Reset').'</span></td>'
				 ,$form_data['Label']
				   ,$row['Deal Key']
				 ,$row['Deal Key']
				 ,($form_data['Lock Value']?'READONLY':'')
				 ,$form_data['Value Class']
				 ,$form_data['Value']
				  ,$form_data['Value']
				 ,$form_data['Lock Label']
				 ,$row['Deal Key']
				 ,$row['Deal Key']
				 ,$row['Deal Key']
				 ,$row['Deal Key']	 


				 );
     }
     $input_term='';
     foreach($deal->terms_input_form() as $form_data){
       //print_r($form_data);
       
    
       

       if($form_data['Value Class']=='country'){
	  $input_term=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
	  <td style="width:15em"  style="text-align:left"><div style="margin-top:1px"><input id="country_code" value="" type="hidden">
	  <input id="country" %s class="%s"style="width:15em" value="%s" /><div id="country_container" style="" ></div></div> %s
	  
	  <script type="text/javascript">
    	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
     	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a"]}
     	var Countries_AC = new YAHOO.widget.AutoComplete("country", "country_container", Countries_DS);
     	Countries_AC.useShadow = true;
     	Countries_AC.resultTypeList = false;
     	Countries_AC.formatResult = country_formatResult;
     	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
      </script>
	  </td>'
	  		,$form_data['Label']
	  		,($form_data['Lock Value']?'READONLY':'')
	  			 ,$form_data['Value Class']
	  		,$form_data['Value']
	  		,$form_data['Lock Label']);
       }else
       {

	 $input_term=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
	 <td style="width:15em"  style="text-align:left"><input id="deal_term%d" onKeyUp="deal_term_changed(%d)" %s class="%s" style="width:5em" value="%s" ovalue="%s" /> %s <span id="deal_term_save%d" style="visibility:hidden" class="state_details" onClick="deal_term_save(%d)">'._('Save').'</span> <span id="deal_term_reset%d" style="visibility:hidden" style="margin-left:10px "class="state_details"  onClick="deal_term_reset(%d)">'._('Reset').'</span></td>'
			     ,$form_data['Label']
			     ,$row['Deal Key']
			     ,$row['Deal Key']
			     ,($form_data['Lock Value']?'READONLY':'')
			     ,$form_data['Value Class']
			     ,$form_data['Value']
			     ,$form_data['Value']
			     ,$form_data['Lock Label']
			     ,$row['Deal Key']
			     ,$row['Deal Key']
			     ,$row['Deal Key']
			     ,$row['Deal Key']
			     );

       }

     }
     
    
     
     $edit='<table style="margin:10px"><tr style="border:none">'.$input_allowance.'</tr><tr style="border:none">'.$input_term.'</tr></table>';
     
     
     $name=$row['Deal Name'];
       if($row['Campaign Deal Schema Key']){
	 $name.=sprintf('<br/><a style="text-decoration:underline" href="edit_campaign.php?id=%d">%s</a>',$row['Campaign Deal Schema Key'],$row['Campaign Name']);
       }
     $adata[]=array(
		    'status'=>$deal->get_xhtml_status(),
		    'name'=>$name,
		    'description'=>'<span id="deal_description'.$deal->id.'">'.$deal->get('Description').'</span>'.$edit,
		    'from'=>'',
		    'to'=>''
		    
		    );
   }
  mysql_free_result($res);

  

  // if($total<$number_results)
  //  $rtext=$total.' '.ngettext('store','stores',$total);
  //else
  //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

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



function list_parts_in_product(){

   $conf=$_SESSION['state']['product']['parts'];


if(isset( $_REQUEST['product_id']))
     $product_id=$_REQUEST['product_id'];
   else
     $product_id=$_SESSION['state']['product']['id'];


   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   

   if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     if($start_from>0){
       $page=floor($start_from/$number_results);
       $start_from=$start_from-$page;
     }
   
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
   
   
   $_SESSION['state']['product']['parts']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   
  
 

   if($product_id){
   
   $filter_msg='';
  
   $wheref='';
   $where=sprintf("where `Product ID`=%d ",$product_id);;
   
   if($f_field=='sku' and $f_value!='')
     $wheref.=sprintf(" and `Part SKU`=%d   ",$f_value);
   
  

   $sql="select count(*) as total from `Product Part List`   $where $wheref";
   //  print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
mysql_free_result($result);
     
     if($wheref==''){
       $filtered=0;$total_records=$total;
   }else{
     $sql="select count(*) as total `Deal Part List`   $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $total_records=$row['total'];
	 $filtered=$total_records-$total;
     }
mysql_free_result($result);

   }

  
     $rtext=$total_records." ".ngettext('part','parts',$total_records);
     if($total_records>$number_results)
       $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
     else
       $rtext_rpp=' ('._('Showing all').')';

  if($total==0 and $filtered>0){
       switch($f_field){
     case('name'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
	 break;
       case('description'):
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
      case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
       break; 
     case('description'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
       break; 
     }
   }else
      $filter_msg='';
  /*  }else{//products parts for new product */
     
/*      $total=count($_SESSION['state']['new_product']['parts']); */
/*      $total_records=$total; */
/*      $filtered=0; */
/*    } */



   $_dir=$order_direction;
   $_order=$order;
   
  
   $order='`Part SKU`';

 
   $sql="select *  from `Product Part List` $where    order by $order $order_direction limit $start_from,$number_results    ";
   //print $sql;
   $res = mysql_query($sql);
   $total=mysql_num_rows($res);
   $adata=array();
   while($row=mysql_fetch_array($res, MYSQL_ASSOC) ) {
     // $meta_data=preg_split('/,/',$row['Deal Allowance Metadata']);
     
     
     $adata[]=array(
		    'sku'=>$row['Part SKU'],
		    'description'=>'x',
		    'picks'=>'c',
		    'notes'=>'v'
		    
		    );
   }
  mysql_free_result($res);

   }else{
     $adata=array();
     if(isset($_SESSION['state']['new_product']['parts'])){
       foreach($_SESSION['state']['new_product']['parts'] as $values)
	 $adata[]=$values;
     }
     $rtext=_('Choose or create a part');
     $rtext_rpp='';
     $total_records=count($adata);
     $filter_msg='';
     $_dir=$order_direction;
     $_order=$order;

     if($total_records>0){
       $rtext=$total_records." ".ngettext('part','parts',$total_records);
     }
       
   }



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

function add_part_new_product($sku){
  
  $part=new Part('sku',$sku);
  if($part->sku){
    // $_SESSION['state']['new_product']['parts']=array();
    if(!isset($_SESSION['state']['new_product']['parts']))
      $_SESSION['state']['new_product']['parts']=array();
    $tmp=$_SESSION['state']['new_product']['parts'];
    if(array_key_exists($part->sku,$tmp)){
      $_SESSION['state']['new_product']['parts'][$part->sku]['picks']=$_SESSION['state']['new_product']['parts'][$part->sku]['picks']+1;
      $msg=_('Part already selected, incesiong oick number');
    }else{
      $_SESSION['state']['new_product']['parts'][$part->sku]=array(
								   'part_sku'=>$part->sku
								   ,'sku'=>$part->get_sku()
								   ,'description'=>$part->data['Part XHTML Description']
								   ,'picks'=>1
								   ,'notes'=>''
								   ,'delete'=>'<img src="art/icons/delete.png"/>'
								   );
      $msg=_('Adding part to list');
    }
    $response=array('state'=>200,'msg'=>$msg);
    echo json_encode($response);

  }else{
    $response=array('state'=>400,'msg'=>'Part SKU not found');
     echo json_encode($response);
    
  }

}

function edit_part_new_product($sku){
  if(isset($_SESSION['state']['new_product']['parts'])){
     $tmp=$_SESSION['state']['new_product']['parts'];
     if(array_key_exists($sku,$tmp)){
       switch($_REQUEST['key']){
       case('picks'):
	 $picks=$_REQUEST['newvalue'];
	 if(is_numeric($picks)){
	   $_SESSION['state']['new_product']['parts'][$sku]['picks']=$picks;
	   $response=array('state'=>200,'newvalue'=>$picks);
	   echo json_encode($response);
	   return;
	 }
	 break;
       case('notes'):
	 
	  $_SESSION['state']['new_product']['parts'][$sku]['notes']=$_REQUEST['newvalue'];
	   $response=array('state'=>200,'newvalue'=>$_REQUEST['newvalue']);
	   echo json_encode($response);
	   return;

	 break;
       }


     }
     $response=array('state'=>200,'msg'=>_('Wrong value'));
     echo json_encode($response);
  }

  

}

function  edit_page($subject,$data){
$family=new family($data['id']);
 global $editor;
 $family->editor=$editor;
 $page=new Page($family->data['Product Family Page Key']);
 $page->update_field_switcher($data['key'],stripslashes(urldecode($data['newvalue'])));
 

   if($page->updated){
     $response= array('state'=>200,'newvalue'=>$page->new_value,'key'=>$data['key']);
	  
   }else{
     $response= array('state'=>400,'msg'=>$page->msg,'key'=>$data['key']);
   }
   echo json_encode($response); 

  }

function edit_page_layout(){
$page_key=$_REQUEST['page_key'];
$layout=$_REQUEST['layout'];
$value=$_REQUEST['newvalue'];

$page=new Page($page_key);
$page->update_show_layout($layout,$value);

 if($page->updated){
     $response= array('state'=>200,'newvalue'=>$page->new_value);
	  
   }else{
     $response= array('state'=>400,'msg'=>$page->msg);
   }
   echo json_encode($response); 


}




function edit_part_list($data){

$product=new Product($_SESSION['state']['product']['mode'],$_SESSION['state']['product']['tag']);
$product_part_key=$data['key'];
$values=$data['newvalue'];



$part_list_data=array();
foreach($values as $key =>$value){

if(!$value['deleted']){

	$part_list_data[$value['sku']]=array(
 			   'Product ID'=>$product->get('Product ID'),
 			   'Part SKU'=>$value['sku'],
 			   'Product Part Type'=>'Simple',
 			   'Parts Per Product'=>$value['ppp'],
 			   'Product Part List Note'=>$value['note']
 			   );
 			   }

}
$date=date('Y-m-d H:i:s');
$header_data=array(
'Product Part Valid From'=>$date
,'Product Part Metadata'=>''
      ,'Product Part Valid To'=>''
		      ,'Product Part Most Recent'=>'Yes'
);

$value['confirm']='new';


//print_r($part_list_data);


$product_part_key=$product->find_product_part_list($part_list_data);





if(!$product_part_key and $value['confirm']=='new'){

foreach($product->data as $key=>$val){
$data[strtolower($key)]=$val;
}
$data['product valid from']=$date;


$product->create_key($data);
$product->create_product_id($data);

//print "--------\n";
$product->new_current_part_list($header_data,$part_list_data)  ;
//print "============\n";
$product->set_duplicates_as_historic();




}else{

$product->new_current_part_list($header_data,$part_list_data);
}





//
//if($product_part_key){
///$this->update_product_part_list($product_part_key,$header_data,$list);
//}else{
//$product_part_key=$this->create_product_part_list($header_data,$list);
//}
//$this->set_part_list_as_current($product_part_key);



if($product->new_id){
  $response= array('state'=>200,'new'=>true,'newvalue'=>$product->pid);
}elseif($product->updated){
  $response= array('state'=>200,'changed'=>true,'newvalue'=>$product->new_value);
}elseif($product->error){
 $response= array('state'=>400,'msg'=>$product->msg);
}

else{
 $response= array('state'=>200,'changed'=>false);
}
 echo json_encode($response); 

}

function list_edit_product_categories() {
    $conf=$_SESSION['state']['product_categories']['subcategories'];
    $conf2=$_SESSION['state']['product_categories'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];





    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['exchange_type'])) {
        $exchange_type=addslashes($_REQUEST['exchange_type']);
        $_SESSION['state']['product_categories']['exchange_type']=$exchange_type;
    } else
        $exchange_type=$conf2['exchange_type'];

    if (isset( $_REQUEST['exchange_value'])) {
        $exchange_value=addslashes($_REQUEST['exchange_value']);
        $_SESSION['state']['product_categories']['exchange_value']=$exchange_value;
    } else
        $exchange_value=$conf2['exchange_value'];

    if (isset( $_REQUEST['show_default_currency'])) {
        $show_default_currency=addslashes($_REQUEST['show_default_currency']);
        $_SESSION['state']['product_categories']['show_default_currency']=$show_default_currency;
    } else
        $show_default_currency=$conf2['show_default_currency'];




    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $_SESSION['state']['product_categories']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['product_categories']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['product_categories']['period']=$period;
    } else
        $period=$_SESSION['state']['product_categories']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['product_categories']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['product_categories']['avg'];

    if (isset( $_REQUEST['stores_mode'])) {
        $stores_mode=$_REQUEST['stores_mode'];
        $_SESSION['state']['product_categories']['stores_mode']=$stores_mode;
    } else
        $stores_mode=$_SESSION['state']['product_categories']['stores_mode'];

    $_SESSION['state']['product_categories']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    if (isset( $_REQUEST['category'])) {
        $root_category=$_REQUEST['category'];
        $_SESSION['state']['product_categories']['category']=$avg;
    } else
        $root_category=$_SESSION['state']['product_categories']['category_key'];





    $where=sprintf("where `Category Subject`='Product' and  `Category Parent Key`=%d ",$root_category);
  //  $where=sprintf("where `Category Subject`='Product'  ");

    if ($stores_mode=='grouped')
        $group=' group by `Category Key`';
    else
        $group='';

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Category Name` like '%".addslashes($f_value)."%'";




    $sql="select count(*) as total   from `Category Dimension`   $where $wheref";
    
//$sql=" describe `Category Dimension`;";
// $sql="select *  from `Category Dimension` where `Category Parent Key`=1 ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
      $total=$row['total'];
//   print_r($row);
   }
    mysql_free_result($res);

//exit;
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Category Dimension` S  left join `Product Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('category','categories',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='families')
        $order='`Product Category Families`';
    elseif($order=='departments')
    $order='`Product Category Departments`';
    elseif($order=='code')
    $order='`Product Category Code`';
    elseif($order=='todo')
    $order='`Product Category In Process Products`';
    elseif($order=='discontinued')
    $order='`Product Category In Process Products`';
    else if ($order=='profit') {
        if ($period=='all')
            $order='`Product Category Total Profit`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Category Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='name')
    $order='`Category Name`';
    elseif($order=='active')
    $order='`Product Category For Public Sale Products`';
    elseif($order=='outofstock')
    $order='`Product Category Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Product Category Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Product Category Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Product Category Optimal Availability Products`';
    elseif($order=='low')
    $order='`Product Category Low Availability Products`';
    elseif($order=='critical')
    $order='`Product Category Critical Availability Products`';





    $sql="select *  from `Category Dimension` S  left join `Product Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)   $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
    // print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
    $adata=array();
    $sum_sales=0;
    $sum_profit=0;
    $sum_outofstock=0;
    $sum_low=0;
    $sum_optimal=0;
    $sum_critical=0;
    $sum_surplus=0;
    $sum_unknown=0;
    $sum_departments=0;
    $sum_families=0;
    $sum_todo=0;
    $sum_discontinued=0;

    $DC_tag='';
    if ($exchange_type=='day2day' and $show_default_currency  )
        $DC_tag=' DC';

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        //$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Name']);
        //$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Code']);

        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Product Category DC Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC Total Profit']>=0)
                    $tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product Category DC 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product Category DC 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product Category DC 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product Category DC 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


        } else {






            if ($period=="all") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." Total Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." Total Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." Total Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." Total Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." Total Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." Total Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Product Category".$DC_tag." Total Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." Total Profit"]*$factor);




            }
            elseif($period=="year") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }









                $tsall=($row["Product Category".$DC_tag." 1 Year Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Year Acc Profit"]*$factor);
            }
            elseif($period=="quarter") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Product Category".$DC_tag." 1 Quarter Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Quarter Acc Profit"]*$factor);
            }
            elseif($period=="month") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Product Category".$DC_tag." 1 Month Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Month Acc Profit"]*$factor);
            }
            elseif($period=="week") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Product Category".$DC_tag." 1 Week Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Week Acc Profit"]*$factor);
            }



        }

        $sum_sales+=$tsall;
        $sum_profit+=$tprofit;
        $sum_low+=$row['Product Category Low Availability Products'];
        $sum_optimal+=$row['Product Category Optimal Availability Products'];
        $sum_low+=$row['Product Category Low Availability Products'];
        $sum_critical+=$row['Product Category Critical Availability Products'];
        $sum_surplus+=$row['Product Category Surplus Availability Products'];
        $sum_outofstock+=$row['Product Category Out Of Stock Products'];
        $sum_unknown+=$row['Product Category Unknown Stock Products'];
        $sum_departments+=$row['Product Category Departments'];
        $sum_families+=$row['Product Category Families'];
        $sum_todo+=$row['Product Category In Process Products'];
        $sum_discontinued+=$row['Product Category Discontinued Products'];


        if (!$percentages) {
            if ($show_default_currency) {
                $class='';
                if ($myconf['currency_code']!=$row['Product Category Currency Code'])
                    $class='currency_exchanged';


                $sales='<span class="'.$class.'">'.money($tsall).'</span>';
                $profit='<span class="'.$class.'">'.money($tprofit).'</span>';
            } else {
                $sales=money($tsall,$row['Product Category Currency Code']);
                $profit=money($tprofit,$row['Product Category Currency Code']);
            }
        } else {
            $sales=$tsall;
            $profit=$tprofit;
        }
        if ($stores_mode=='grouped')
          //  $name=sprintf('<a href="categories.php?id=%d">%s</a>',$row['Category Key'],$row['Category Name']);
               $name=$row['Category Name'];
        else
            $name=$row['Product Category Key'].' '.$row['Category Name']." (".$row['Product Category Store Key'].")";
            $delete='<img src="art/icons/delete.png"/>';
        $adata[]=array(
		     'go'=>sprintf("<a href='edit_category.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Category Key']),
                     'id'=>$row['Category Key'],
                     'name'=>$name,
                     'departments'=>number($row['Product Category Departments']),
                     'families'=>number($row['Product Category Families']),
                     'active'=>number($row['Product Category For Public Sale Products']),
                     'todo'=>number($row['Product Category In Process Products']),
                     'discontinued'=>number($row['Product Category Discontinued Products']),
                     'outofstock'=>number($row['Product Category Out Of Stock Products']),
                     'stock_error'=>number($row['Product Category Unknown Stock Products']),
                     'stock_value'=>money($row['Product Category Stock Value']),
                     'surplus'=>number($row['Product Category Surplus Availability Products']),
                     'optimal'=>number($row['Product Category Optimal Availability Products']),
                     'low'=>number($row['Product Category Low Availability Products']),
                     'critical'=>number($row['Product Category Critical Availability Products']),
                     'sales'=>$sales,
                     'profit'=>$profit
                                               ,'delete'=>$delete
                                                          ,'delete_type'=>'delete'

                 );
    }
    mysql_free_result($res);

    /*  if ($percentages) { */
    /*         $sum_sales='100.00%'; */
    /*         $sum_profit='100.00%'; */
    /* //       $sum_low= */
    /* //   $sum_optimal=$row['Product Category Optimal Availability Products']; */
    /* //   $sum_low=$row['Product Category Low Availability Products']; */
    /* //   $sum_critical=$row['Product Category Critical Availability Products']; */
    /* //   $sum_surplus=$row['Product Category Surplus Availability Products']; */
    /*     } else { */
    /*         $sum_sales=money($sum_total_sales); */
    /*         $sum_profit=money($sum_total_profit); */
    /*     } */

    /*     $sum_outofstock=number($sum_outofstock); */
    /*     $sum_low=number($sum_low); */
    /*     $sum_optimal=number($sum_optimal); */
    /*     $sum_critical=number($sum_critical); */
    /*     $sum_surplus=number($sum_surplus); */
    /*     $sum_unknown=number($sum_unknown); */
    /*     $sum_departments=number($sum_departments); */
    /*     $sum_families=number($sum_families); */
    /*     $sum_todo=number($sum_todo); */
    /*     $sum_discontinued=number($sum_discontinued); */
    /*     $adata[]=array( */

    /*                  'name'=>_('Total'), */
    /*                  'active'=>number($sum_active), */
    /*                  'sales'=>$sum_sales, */
    /*                  'profit'=>$sum_profit, */
    /*                  'todo'=>$sum_todo, */
    /*                  'discontinued'=>$sum_discontinued, */
    /*                  'low'=>$sum_low, */
    /*                  'critical'=>$sum_critical, */
    /*                  'surplus'=>$sum_surplus, */
    /*                  'optimal'=>$sum_optimal, */
    /*                  'outofstock'=>$sum_outofstock, */
    /*                  'stock_error'=>$sum_unknown, */
    /*                  'departments'=>$sum_departments, */
    /*                  'families'=>$sum_families */
    /*              ); */


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    //   $total_records=ceil($total_records/$number_results)+$total_records;

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


function edit_categories($data){
$category=new Category($data['id']);
if($data['key']=='name'){$data['key']='Category Name';}
$category->update(array($data['key']=>$data['newvalue']));print($data['key']);
 if($category->updated){
    $response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$category->new_value);
 }else{
     $response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue']);
      }
 echo json_encode($response);
}

function delete_categories($data) {
    include_once('class.Category.php');
    global $editor;
    $subject=new Category($data['id']);
    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Area not found');
        echo json_encode($response);
        return;
    }
    $subject->editor=$editor;
    $subject->delete();
    if ($subject->deleted) {
        $action='deleted';
        $msg=_('Area deleted');

    } else {
        $action='nochage';
        $msg=_('Area could not be deleted');
    }
    $response=array('state'=>200,'action'=>$action);
    echo json_encode($response);
}

?>
