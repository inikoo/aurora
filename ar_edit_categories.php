<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
include_once 'class.Category.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('category_heads'):
	list_category_heads();
	break;
case('parts_no_assigned_to_category'):
	list_parts_no_assigned_to_category();
	break;
case('parts_assigned_to_category'):
	list_parts_assigned_to_category();
	break;
	/*
case('disassociate_subject_from_all_sub_categories'):
    $data=prepare_values($_REQUEST,array(

                             'subject'  =>array('type'=>'string'),
                             'subject_key'  =>array('type'=>'key'),
                             'category_key'  =>array('type'=>'key')
                         ));
    disassociate_subject_from_all_sub_categories($data);
    break;
case('disassociate_subject_to_category'):
    $data=prepare_values($_REQUEST,array(
                             'cat_id'  =>array('type'=>'string'),
                             'subject'  =>array('type'=>'string'),
                             'subject_key'  =>array('type'=>'key'),
                             'category_key'  =>array('type'=>'key')
                         ));


    disassociate_subject_to_category($data);

    break;
case('disassociate_subject_to_category_radio'):
    $data=prepare_values($_REQUEST,array(
                             'cat_id'  =>array('type'=>'string'),
                             'subject'  =>array('type'=>'string'),
                             'subject_key'  =>array('type'=>'key'),
                             'category_key'  =>array('type'=>'key'),
                             'parent_category_key'  =>array('type'=>'key')
                         ));


    disassociate_subject_to_category_radio($data);

    break;
 */
case('disassociate_subject'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'  =>array('type'=>'key'),
			'category_key'  =>array('type'=>'key'),
		));


	disassociate_subject_to_category($data);

	break;
case('associate_subject_to_category'):
case('associate_subject_to_category_radio'):
	$data=prepare_values($_REQUEST,array(
			//'cat_id'  =>array('type'=>'string'),
			//'subject'  =>array('type'=>'string'),
			'subject_key'  =>array('type'=>'key'),
			'category_key'  =>array('type'=>'key'),

		));


	associate_subject_to_category($data);

	break;


case('update_other_value'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'  =>array('type'=>'key'),
			'category_key'  =>array('type'=>'key'),
			'other_value'  =>array('type'=>'string'),
		));


	update_other_value($data);
	break;
case('new_category'):
	$data=prepare_values($_REQUEST,array(
			'name'=>array('type'=>'string'),
			'subject'  =>array('type'=>'string'),
			'store_key'  =>array('type'=>'number'),
			'warehouse_key'  =>array('type'=>'number'),
			'parent_key'  =>array('type'=>'number')
		));


	add_category($data);
	break;
case('edit_subcategory'):
	$data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
	edit_categories($data);
	break;

case('edit_categories'):
	$data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
	edit_categories($data);
	break;
case('edit_category'):
	$data=prepare_values($_REQUEST,array('category_key'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));

	edit_category($data);
	break;
case('edit_subcategory'):
	edit_subcategory();
	break;
case('edit_product_category_list'):
	list_edit_product_categories();
	break;
case('edit_customer_category_list'):
	list_edit_customer_categories();
	break;
case('edit_part_category_list'):
	list_edit_part_categories();
	break;
case('edit_supplier_category_list'):
	list_edit_supplier_categories();
	break;
case('delete_subcategory'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
			,'delete_type'=>array('type'=>'string')
		));
	delete_categories($data);
	break;

case('delete_categories'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
			,'delete_type'=>array('type'=>'string')
		));
	delete_categories($data);
	break;

}


function add_category($raw_data) {



	$data=array(
		'Category Code'=>$raw_data['name'],
		'Category Subject'=>$raw_data['subject'],
		'Category Store Key'=>   $raw_data['store_key'],
		'Category Warehouse Key'=>   $raw_data['warehouse_key'],
		'Category Parent Key'=>$raw_data['parent_key'],
	);

	$category=new Category('find create',$data);



	if ($category->new) {
		$response= array('state'=>200,'action'=>'created','category_key'=>$category->id);
	} else {
		if ($category->found)
			$response= array('state'=>400,'action'=>'found','category_key'=>$category->found_key,'msg'=>_('Category name already used'));
		else
			$response= array('state'=>400,'action'=>'error','category_key'=>0,'msg'=>$category->msg,'msg'=>'Error');
	}


	echo json_encode($response);


}
function list_edit_product_categories() {
	$conf=$_SESSION['state']['categories']['table'];

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
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


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


	$subject=$_SESSION['state']['categories']['subject'];
	$subject_key=$_SESSION['state']['categories']['subject_key'];
	$parent_key=$_SESSION['state']['categories']['parent_key'];
	$store_key=$_SESSION['state']['categories']['store_key'];

	$_SESSION['state']['categories']['table']['order']=$order;
	$_SESSION['state']['categories']['table']['order_dir']=$order_direction;
	$_SESSION['state']['categories']['table']['nr']=$number_results;
	$_SESSION['state']['categories']['table']['sf']=$start_from;
	$_SESSION['state']['categories']['table']['where']=$where;
	$_SESSION['state']['categories']['table']['f_field']=$f_field;
	$_SESSION['state']['categories']['table']['f_value']=$f_value;






	$where=sprintf("where   `Category Store Key`=%d  and `Category Subject`=%s and  `Category Parent Key`=%d ",
		$store_key,prepare_mysql($subject),$parent_key);
	if ($subject_key) {
		$where.=sprintf("and `Category Subject Key`=%d",$subject_key); ;
	}




	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {


		$sql="select count(*) as total  from `Category Dimension`    $where ";

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
	elseif ($filtered>0) {
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



	$order='`Category Code`';





	$sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_assoc($res)) {

		$name=$row['Category Code'];

		$delete='<img src="art/icons/delete.png"/>';
		$adata[]=array(
			'go'=>sprintf("<a href='edit_product_category.php?store_id=%d&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",
				$row['Category Store Key'],
				$row['Category Key']),
			'id'=>$row['Category Key'],
			'name'=>$name,

			'delete'=>$delete,
			'delete_type'=>'delete'

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
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function list_edit_customer_categories() {
	$conf=$_SESSION['state']['categories']['table'];

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
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


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


	$subject=$_SESSION['state']['categories']['subject'];
	$subject_key=$_SESSION['state']['categories']['subject_key'];
	$parent_key=$_SESSION['state']['categories']['parent_key'];
	$store_key=$_SESSION['state']['categories']['store_key'];

	$_SESSION['state']['categories']['table']['order']=$order;
	$_SESSION['state']['categories']['table']['order_dir']=$order_direction;
	$_SESSION['state']['categories']['table']['nr']=$number_results;
	$_SESSION['state']['categories']['table']['sf']=$start_from;
	$_SESSION['state']['categories']['table']['where']=$where;
	$_SESSION['state']['categories']['table']['f_field']=$f_field;
	$_SESSION['state']['categories']['table']['f_value']=$f_value;






	$where=sprintf("where   `Category Store Key`=%d  and `Category Subject`=%s and  `Category Parent Key`=%d ",
		$store_key,prepare_mysql($subject),$parent_key);
	if ($subject_key) {
		$where.=sprintf("and `Category Subject Key`=%d",$subject_key); ;
	}




	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {


		$sql="select count(*) as total  from `Category Dimension`    $where ";

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
	elseif ($filtered>0) {
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



	$order='`Category Code`';





	$sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_assoc($res)) {

		$name=$row['Category Code'];
		$label=$row['Category Label'];

		$delete='<div class="buttons small"><button class="negative">'._('Delete').'</button></div>';
		$adata[]=array(
			'go'=>sprintf("<a href='edit_customer_category.php?store_id=%d&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",
				$row['Category Store Key'],
				$row['Category Key']),
			'id'=>$row['Category Key'],
			'name'=>$name,
			'label'=>$label,
			'new_subject'=>$row['Category Show Subject User Interface'],
			'public_new_subject'=>$row['Category Show Public New Subject'],
			'public_edit'=>$row['Category Show Public Edit'],
			'delete'=>$delete,
			'delete_type'=>'delete'

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
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}
function list_edit_part_categories() {
	$conf=$_SESSION['state']['categories']['table'];

	$parent_key=$_REQUEST['parent_key'];

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
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


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


	//  $subject=$_SESSION['state']['categories']['subject'];
	//   $subject_key=$_SESSION['state']['categories']['subject_key'];
	//  $parent_key=$_SESSION['state']['categories']['parent_key'];
	//  $store_key=$_SESSION['state']['categories']['store_key'];

	$_SESSION['state']['categories']['table']['order']=$order;
	$_SESSION['state']['categories']['table']['order_dir']=$order_direction;
	$_SESSION['state']['categories']['table']['nr']=$number_results;
	$_SESSION['state']['categories']['table']['sf']=$start_from;
	$_SESSION['state']['categories']['table']['where']=$where;
	$_SESSION['state']['categories']['table']['f_field']=$f_field;
	$_SESSION['state']['categories']['table']['f_value']=$f_value;






	$where=sprintf("where  `Category Subject`='Part' and  `Category Parent Key`=%d ",
		$parent_key);
	//   if ($subject_key) {
	//       $where.=sprintf("and `Category Subject Key`=%d",$subject_key); ;
	//   }




	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {


		$sql="select count(*) as total  from `Category Dimension`    $where ";

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
	elseif ($filtered>0) {
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



	$order='`Category Code`';





	$sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_assoc($res)) {


		$delete='<div class="buttons small"><button class="negative">'._('Delete').'</button></div>';
		$adata[]=array(
			'go'=>sprintf("<a href='edit_part_category.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",
				$row['Category Key']),
			'id'=>$row['Category Key'],
			'name'=>$row['Category Code'],
			'label'=>$row['Category Label'],

			'delete'=>$delete,
			'delete_type'=>'delete'

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
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}


function list_edit_supplier_categories() {
	$conf=$_SESSION['state']['categories']['table'];

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
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


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


	$subject=$_SESSION['state']['categories']['subject'];
	$subject_key=$_SESSION['state']['categories']['subject_key'];
	$parent_key=$_SESSION['state']['categories']['parent_key'];
	$store_key=$_SESSION['state']['categories']['store_key'];

	$_SESSION['state']['categories']['table']['order']=$order;
	$_SESSION['state']['categories']['table']['order_dir']=$order_direction;
	$_SESSION['state']['categories']['table']['nr']=$number_results;
	$_SESSION['state']['categories']['table']['sf']=$start_from;
	$_SESSION['state']['categories']['table']['where']=$where;
	$_SESSION['state']['categories']['table']['f_field']=$f_field;
	$_SESSION['state']['categories']['table']['f_value']=$f_value;






	$where=sprintf("where   `Category Store Key`=%d  and `Category Subject`=%s and  `Category Parent Key`=%d ",
		$store_key,prepare_mysql($subject),$parent_key);
	if ($subject_key) {
		$where.=sprintf("and `Category Subject Key`=%d",$subject_key); ;
	}




	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {


		$sql="select count(*) as total  from `Category Dimension`    $where ";

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
	elseif ($filtered>0) {
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



	$order='`Category Code`';





	$sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	// print $sql;
	$res = mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_assoc($res)) {

		$name=$row['Category Code'];

		$delete='<img src="art/icons/delete.png"/>';
		$adata[]=array(
			'go'=>sprintf("<a href='edit_supplier_category.php?store_id=%d&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",
				$row['Category Store Key'],
				$row['Category Key']),
			'id'=>$row['Category Key'],
			'name'=>$name,

			'delete'=>$delete,
			'delete_type'=>'delete'

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
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function edit_categories($data) {
	$category=new Category($data['id']);

	$translate_keys=array('id'=>'Category Key','name'=>'Category Code'
		,'label'=>'Category Label'
		,'new_subject'=>'Category Show Subject User Interface'
		,'public_new_subject'=>'Category Show Public New Subject'
		,'public_edit'=>'Category Show Public Edit'
	);

	//if($data['key']=='name'){$data['key']='Category Code';}
	$category->update(array($translate_keys[$data['key']]=>$data['newvalue']));//print($data['key']);
	if ($category->updated) {
		$response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$category->new_value);
	} else {
		$response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue']);
	}
	echo json_encode($response);
}

function edit_category($data) {
	$category=new Category($data['category_key']);
	$translate_keys=array('category_key'=>'Category Key','name'=>'Category Code','label'=>'Category Label','Category Show Subject User Interface'=>'Category Show Subject User Interface'
		,'Category Show Public New Subject'=>'Category Show Public New Subject'
		,'Category Show Public Edit'=>'Category Show Public Edit');
	$category->update(array($translate_keys[$data['key']]=>$data['newvalue']));//print($data['key']);
	if ($category->updated) {
		$response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$category->new_value);
	} else {
		$response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue']);
	}
	echo json_encode($response);
}



function delete_categories($data) {
	include_once 'class.Category.php';
	global $editor;
	$subject=new Category($data['id']);
	if (!$subject->id) {
		$response=array('state'=>400,'msg'=>'Category not found');
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


function disassociate_subject_from_all_sub_categories_old($data) {

	$category=new Category($data['category_key']);
	$sub_cats=$category->get_children_keys();
	if (count($sub_cats)==0) {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('Category has not children'));
		echo json_encode($response);
		return;
	}

	$sql=sprintf("delete from `Category Bridge`  where `Category Key` in (%s) and `Subject`=%s and `Subject Key`=%d",
		join(',',$sub_cats),
		prepare_mysql($data['subject']),
		$data['subject_key']
	);
	mysql_query($sql);
	// print($sql);
	//print "-->".mysql_affected_rows()."<--  "    ;
	if (mysql_affected_rows()>0) {
		$response=array('state'=>200,'action'=>'deleted','msg'=>_('Saved'));
		echo json_encode($response);
	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('Subject could not be disassociated with the category'));
		echo json_encode($response);
	}

}

function disassociate_subject_to_category_old($data) {
	$sql=sprintf("delete from `Category Bridge`  where `Category Key`=%d and `Subject`=%s and `Subject Key`=%d",
		$data['parent_category_key'],
		prepare_mysql($data['subject']),
		$data['subject_key']
	);
	mysql_query($sql);
	// print($sql);
	//print "-->".mysql_affected_rows()."<--  "    ;
	if (mysql_affected_rows()>0) {
		$response=array('state'=>200,'action'=>'deleted','cat_id'=>$data['cat_id']);
		echo json_encode($response);
	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('Subject could not be disassociated with the category'));
		echo json_encode($response);
	}

}


function disassociate_subject_to_category_radio_old($data) {
	$found=false;
	$sql=sprintf("select count(*) as num from `Category Bridge`  where `Category Key`=%d and `Subject`=%s and `Subject Key`=%d",
		$data['category_key'],
		prepare_mysql($data['subject']),
		$data['subject_key']
	);
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		if ($row['num']>0)
			$found=true;

	}


	if (!$found) {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('The Subject is not associated with the category'));
		echo json_encode($response);
		exit;
	}


	$sql=sprintf("delete CB.* from `Category Bridge` as CB left join `Category Dimension` C on (C.`Category Key`=CB.`Category Key`)  where `Category Parent Key`=%d and `Subject`=%s and `Subject Key`=%d",
		$data['parent_category_key'],
		prepare_mysql($data['subject']),
		$data['subject_key']
	);
	mysql_query($sql);
	//print($sql);
	//print "-->".mysql_affected_rows()."<--  "    ;
	if (mysql_affected_rows()>0) {
		$response=array('state'=>200,'action'=>'deleted','cat_id'=>$data['cat_id'],'parent_category_key'=>$data['parent_category_key']);
		echo json_encode($response);
	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('Subject could not be disassociated with the category'));
		echo json_encode($response);
	}

}


function associate_subject_to_category($data) {

	$other_value='';
	if (isset($_REQUEST['other_value']))
		$other_value=$_REQUEST['other_value'];

	$category=new Category($data['category_key']);
	$associated=$category->associate_subject($data['subject_key'],false,$other_value);



	if ($associated) {

		//  $category=new Category($data['category_key']);

		$response=array(
			'state'=>200,
			'number_category_subjects_assigned'=>$category->get('Number Subjects'),
			'number_category_subjects_not_assigned'=>$category->get('Subjects Not Assigned')
		);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$category->msg);
		echo json_encode($response);
	}

}


function disassociate_subject_to_category($data) {

	$category=new Category($data['category_key']);
	$disassociated=$category->disassociate_subject($data['subject_key']);



	if ($disassociated) {

		//  $category=new Category($data['category_key']);

		$response=array(
			'state'=>200,
			'number_category_subjects_assigned'=>$category->get('Number Subjects'),
			'number_category_subjects_not_assigned'=>$category->get('Subjects Not Assigned')
		);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$category->msg);
		echo json_encode($response);
	}

}

function update_other_value($data) {
	$category=new Category($data['category_key']);
	$category->update_other_value($data['subject_key'],$data['other_value']);

	$response=array(
		'state'=>200);

	echo json_encode($response);

}


function associate_subject_to_category_radio_old($data) {


	$found=false;
	$sql=sprintf("select count(*) as num from `Category Bridge`  where `Category Key`=%d and `Subject`=%s and `Subject Key`=%d",
		$data['category_key'],
		prepare_mysql($data['subject']),
		$data['subject_key']
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		if ($row['num']>0)
			$found=true;

	}


	if (isset($_REQUEST['other'])) {
		$other=$_REQUEST['other'];
		$found=false;
	}
	else
		$other=NULL;





	if ($found) {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('Subject already associated with the category'));
		echo json_encode($response);
		exit;
	}

	$old_category_key=0;
	$sql=sprintf("select C.`Category Key` from `Category Bridge` as CB left join `Category Dimension` C on (C.`Category Key`=CB.`Category Key`)  where `Category Parent Key`=%d and `Subject`=%s and `Subject Key`=%d",
		$data['parent_category_key'],
		prepare_mysql($data['subject']),
		$data['subject_key']
	);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$old_category_key=$row['Category Key'];

	}



	$sql=sprintf("delete CB.* from `Category Bridge` as CB left join `Category Dimension` C on (C.`Category Key`=CB.`Category Key`)  where `Category Parent Key`=%d and `Subject`=%s and `Subject Key`=%d",
		$data['parent_category_key'],
		prepare_mysql($data['subject']),
		$data['subject_key']
	);
	mysql_query($sql);

	$old_category=new Category($old_category_key);
	if ($old_category->id)
		$old_category->update_number_of_subjects();
	$old_category->update_subjects_data();

	$sql=sprintf("insert into `Category Bridge` values (%d,%s,%d,%s)",
		$data['category_key'],
		prepare_mysql($data['subject']),
		$data['subject_key'],
		prepare_mysql($other)

	);
	mysql_query($sql);

	//print $sql;

	if (mysql_affected_rows()>0) {

		$category=new Category($data['category_key']);
		$category->update_number_of_subjects();
		$category->update_subjects_data();

		$response=array('state'=>200,'action'=>'added','cat_id'=>$data['cat_id'],'parent_category_key'=>$data['parent_category_key'],'msg'=>_('Saved'));
		echo json_encode($response);
	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('Subject could not associated with the category'));
		echo json_encode($response);
	}

}


function list_parts_no_assigned_to_category() {
	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		return;
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		return;
	}
	$conf=$_SESSION['state']['part_categories']['no_assigned_parts'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];

	} else
		$number_results=$conf['nr'];



	if (!is_numeric($number_results))
		$number_results=25;

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




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


	$_SESSION['state']['part_categories']['no_assigned_parts']['order']=$order;
	$_SESSION['state']['part_categories']['no_assigned_parts']['order_dir']=$order_direction;
	$_SESSION['state']['part_categories']['no_assigned_parts']['nr']=$number_results;
	$_SESSION['state']['part_categories']['no_assigned_parts']['sf']=$start_from;
	$_SESSION['state']['part_categories']['no_assigned_parts']['f_field']=$f_field;
	$_SESSION['state']['part_categories']['no_assigned_parts']['f_value']=$f_value;


	$filter_msg='';
	$sql_type='part';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (!is_numeric($start_from))
		$start_from=0;
	if (!is_numeric($number_results))
		$number_results=25;

	$where=sprintf("where (select count(*) from `Category Bridge` where `Subject`='Part'and `Category Key`=%d  and `Subject Key`=`Part SKU`)=0 ",
		$parent_key
	);






	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='used_in' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='supplied_by' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='sku' and $f_value!='')
		$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";


	$sql="select count(`Part SKU`) as total from `Part Dimension`  $where $wheref ";


	//print $sql;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(`Part SKU`) as total from `Part Dimension`  $where  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=$total_records." ".ngettext('part','parts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with ")." <b>".sprintf("SKU%05d",$f_value)."*</b> ";
			break;

		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part used in ")." <b>".$f_value."*</b> ";
			break;
		case('suppiled_by'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part supplied by ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {


		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with')." <b>".sprintf("SKU%05d",$f_value)."*</b>";
			break;

		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts used in')." <b>".$f_value."*</b>";
			break;
		case('supplied_by'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts supplied by')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';





	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='sku' or $order='formated_sku')
		$order='`Part SKU`';
	elseif ($order=='description')
		$order='`Part Unit Description`';
	elseif ($order=='available_for')
		$order='`Part Available Days Forecast`';
	elseif ($order=='supplied_by')
		$order='`Part XHTML Currently Supplied By`';
	elseif ($order=='used_in')
		$order='`Part XHTML Currently Used In`';


	$sql="select `Part Status`,`Part SKU`,`Part Unit Description`,`Part XHTML Currently Used In` from `Part Dimension`  $where $wheref order by $order $order_direction limit $start_from,$number_results";



	$adata=array();
	$result=mysql_query($sql);

	//print $sql;
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$move_here='<div class="buttons small"><button>'._('Assign Here').'</button></div>';

		$move='<div class="buttons small"><button>'._('Assign to Category').'</button></div>';


		$checkbox=sprintf('<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="pna_%d" onClick="check_part(%d)"/>',
			$data['Part SKU'],
			$data['Part SKU']
		);

		$adata[]=array(
			'checkbox'=>$checkbox,
			'sku'=>$data['Part SKU'],
			'subject_key'=>$data['Part SKU'],

			'formated_sku'=>sprintf('<a href="part.php?sku=%d">%06d</a>',$data['Part SKU'],$data['Part SKU']),
			'description'=>$data['Part Unit Description'],
			'used_in'=>$data['Part XHTML Currently Used In'],
			'status'=>($data['Part Status']=='In Use'?'':_('Discontinued')),
			'move'=>$move,
			'move_here'=>$move_here
		);
	}

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
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function list_parts_assigned_to_category() {

	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		return;
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		return;
	}
	$conf=$_SESSION['state']['part_categories']['edit_parts'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];

	} else
		$number_results=$conf['nr'];



	if (!is_numeric($number_results))
		$number_results=25;

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




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


	$_SESSION['state']['part_categories']['edit_parts']['order']=$order;
	$_SESSION['state']['part_categories']['edit_parts']['order_dir']=$order_direction;
	$_SESSION['state']['part_categories']['edit_parts']['nr']=$number_results;
	$_SESSION['state']['part_categories']['edit_parts']['sf']=$start_from;
	$_SESSION['state']['part_categories']['edit_parts']['f_field']=$f_field;
	$_SESSION['state']['part_categories']['edit_parts']['f_value']=$f_value;


	$filter_msg='';
	$sql_type='part';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (!is_numeric($start_from))
		$start_from=0;
	if (!is_numeric($number_results))
		$number_results=25;

	$where=sprintf("where B.`Category Key`=%d  ",
		$parent_key
	);






	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='used_in' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='supplied_by' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='sku' and $f_value!='')
		$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";


	$sql="select count(`Part SKU`) as total from `Part Dimension` left join `Category Bridge` B on (`Part SKU`=`Subject Key` and `Subject`='Part') $where $wheref ";


	//print $sql;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(`Part SKU`) as total from `Part Dimension` left join `Category Bridge` B on (`Part SKU`=`Subject Key` and `Subject`='Part')  $where  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=$total_records." ".ngettext('part','parts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with ")." <b>".sprintf("SKU%05d",$f_value)."*</b> ";
			break;

		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part used in ")." <b>".$f_value."*</b> ";
			break;
		case('suppiled_by'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part supplied by ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {


		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with')." <b>".sprintf("SKU%05d",$f_value)."*</b>";
			break;

		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts used in')." <b>".$f_value."*</b>";
			break;
		case('supplied_by'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts supplied by')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';





	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='sku' or $order='formated_sku')
		$order='`Part SKU`';
	elseif ($order=='description')
		$order='`Part Unit Description`';
	elseif ($order=='available_for')
		$order='`Part Available Days Forecast`';
	elseif ($order=='supplied_by')
		$order='`Part XHTML Currently Supplied By`';
	elseif ($order=='used_in')
		$order='`Part XHTML Currently Used In`';


	$sql="select `Category Plain Branch Tree`,`Part Status`,`Part SKU`,`Part Unit Description`,`Part XHTML Currently Used In` from `Part Dimension` left join `Category Bridge` B on (`Part SKU`=B.`Subject Key` and `Subject`='Part') left join `Category Dimension` C on (C.`Category Key`=B.`Category Head Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results";



	$adata=array();
	$result=mysql_query($sql);


	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


		$move='<div class="buttons small"><button>'._('Move').'</button></div>';
		$delete='<div class="buttons small"><button>'._('Remove').'</button></div>';


		$checkbox=sprintf('<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="pna_%d" onClick="check_part(%d)"/>',
			$data['Part SKU'],
			$data['Part SKU']
		);

		$hierarchy='<img style="width:14px;" src="art/icons/hierarchy_grey.png" alt="hierarchy" title="'.$data['Category Plain Branch Tree'].'" />';
		$adata[]=array(
			'checkbox'=>$checkbox,
			'sku'=>$data['Part SKU'],
			'subject_key'=>$data['Part SKU'],

			'formated_sku'=>sprintf('<a href="part.php?sku=%d">%06d</a>',$data['Part SKU'],$data['Part SKU']),
			'description'=>$data['Part Unit Description'],
			'used_in'=>$data['Part XHTML Currently Used In'],
			'status'=>($data['Part Status']=='In Use'?'':_('Discontinued')),
			'move'=>$move,
			'delete'=>$delete,
			'hierarchy'=>$hierarchy
		);
	}

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
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function list_category_heads() {


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];


	} else
		$number_results=25;

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order='code';
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir='';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field='code';

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value='';


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	if (isset( $_REQUEST['root_category_key'])) {
		$root_category_key=$_REQUEST['root_category_key'];
	} else {
		exit("error 1");
	}

	if (isset( $_REQUEST['category_key'])) {
		$category_key=$_REQUEST['category_key'];
	} else {
		$category_key=0;
	}

	if (isset( $_REQUEST['category_subject'])) {
		$category_subject=$_REQUEST['category_subject'];
	} else {
		exit("error 3");
	}
	$where=sprintf("where `Category Branch Type`='Head' and `Category Subject`=%s and  `Category Root Key`=%d  ",prepare_mysql($category_subject),$root_category_key);
	if ($category_key) {
		$where.=sprintf("and `Category Key`!=%d ",$category_key);
	}



	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";
	if ($f_field=='label' and $f_value!='')
		$wheref.=" and  `Category Label` like '%".addslashes($f_value)."%'";



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
		$sql="select count(*) as total  from `Category Dimension`  $where ";

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

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with code like ")." <b>*".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with label like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with code like')." <b>*".$f_value."*</b>";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with code like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='code')
		$order='`Category Code`';
	if ($order=='label')
		$order='`Category Label`';



	$sql="select * from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();


	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



		$adata[]=array(
			'key'=>$row['Category Key'],
			'code'=>$row['Category Code'],
			'label'=>$row['Category Label'],



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
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}
