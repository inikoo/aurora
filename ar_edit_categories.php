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
case('customers_assigned_to_category'):
	list_customers_assigned_to_category();
	break;
case('customers_no_assigned_to_category'):
	list_customers_no_assigned_to_category();
	break;
case('products_assigned_to_category'):
	list_products_assigned_to_category();
	break;
case('products_no_assigned_to_category'):
	list_products_no_assigned_to_category();
	break;
case('parts_assigned_to_category'):
	list_parts_assigned_to_category();
	break;
case('suppliers_no_assigned_to_category'):
	list_suppliers_no_assigned_to_category();
	break;
case('suppliers_assigned_to_category'):
	list_suppliers_assigned_to_category();
	break;

case('disassociate_multiple_subject_from_category'):
	$data=prepare_values($_REQUEST,array(
			'subject_source_checked_type'  =>array('type'=>'string'),
			'subject_source_checked_subjects'  =>array('type'=>'string'),
			'category_key'  =>array('type'=>'key'),
		));
	disassociate_multiple_subject_from_category($data);
	break;
case('disassociate_subject'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'  =>array('type'=>'key'),
			'category_key'  =>array('type'=>'key'),
		));
	disassociate_subject_from_category($data);
	break;
case('associate_multiple_subject_to_category'):
	$data=prepare_values($_REQUEST,array(
			'subject_source_checked_type'  =>array('type'=>'string'),
			'subject_source_checked_subjects'  =>array('type'=>'string'),
			'subject_source'  =>array('type'=>'numeric'),
			'category_key'  =>array('type'=>'key'),
		));
	associate_multiple_subject_to_category($data);
	break;
case('associate_subject_to_category'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'  =>array('type'=>'key'),
			'category_key'  =>array('type'=>'key'),
		));
	associate_subject_to_category($data);
	break;

case('edit_category_other_value'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'  =>array('type'=>'key'),
			'category_key'  =>array('type'=>'key'),
			'newvalue'  =>array('type'=>'string'),
		));

	$data['other_value']=$data['newvalue'];
	update_other_value($data);
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
			'code'=>array('type'=>'string'),
			'label'=>array('type'=>'string'),
			'parent_key'  =>array('type'=>'key'),
			'other'=>array('type'=>'string')
		));
	add_category($data);
	break;
case('new_main_category'):
	$data=prepare_values($_REQUEST,array(
			'code'=>array('type'=>'string'),
			'label'=>array('type'=>'string'),
			'subject'  =>array('type'=>'string'),
			'store_key'=>array('type'=>'numeric'),
			'warehouse_key'=>array('type'=>'numeric'),
			'max_deep'=>array('type'=>'numeric'),
			'allow_other'=>array('type'=>'string'),
			'multiplicity'=>array('type'=>'string'),
			'show_registration'=>array('type'=>'string'),
			'show_profile'=>array('type'=>'string'),
			'show_ui'=>array('type'=>'string'),


		));
	create_main_category($data);
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
	$data=prepare_values($_REQUEST,
		array('category_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		)
	);

	edit_category($data);
	break;
case('edit_subcategory'):
	edit_subcategory();
	break;
case('edit_product_category_list'):
case('edit_customer_category_list'):
case('edit_part_category_list'):
case('edit_supplier_category_list'):
	list_edit_categories($tipo);
	break;
case('delete_subcategory'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
			,'delete_type'=>array('type'=>'string')
		));
	delete_categories($data);
	break;

case('delete_category'):
	$data=prepare_values($_REQUEST,array(
			'category_key'=>array('type'=>'key')
		));
	delete_category($data);
	break;

}


function create_main_category($raw_data) {
	global $editor;
	$data=array(
		'Category Code'=>$raw_data['code'],
		'Category Label'=>$raw_data['label'],
		'Category Subject'=>$raw_data['subject'],
		'Category Can Have Other'=>$raw_data['allow_other'],
		'Category Store Key'=>$raw_data['store_key'],
		'Category Warehouse Key'=>$raw_data['warehouse_key'],
		'Category Max Deep'=>$raw_data['max_deep'],
		'Category Multiplicity'=>$raw_data['multiplicity'],
		'Category Show Public New Subject'=>$raw_data['show_registration'],
		'Category Show Public Edit'=>$raw_data['show_profile'],
		'Category Show Subject User Interface'=>$raw_data['show_ui'],
		'Category Branch Type'=>'Root',
		'editor'=>$editor

	);
	$category=new Category('find create',$data);

	if ($category->new) {
		$response= array('state'=>200,'action'=>'created','category_key'=>$category->id);
	} else {
		if ($category->found)
			$response= array('state'=>400,'action'=>'found','category_key'=>$category->found_key,'msg'=>_('Category code already used'));
		else
			$response= array('state'=>400,'action'=>'error','category_key'=>0,'msg'=>$category->msg);
	}


	echo json_encode($response);

}

function add_category($raw_data) {

	global $editor;
	$data=array(
		'Category Code'=>$raw_data['code'],
		'Category Label'=>$raw_data['label'],
		'Is Category Field Other'=>$raw_data['other'],
		'editor'=>$editor
	);

	$parent_category=new Category($raw_data['parent_key']);
	$parent_category->editor=$editor;
	$category=$parent_category->create_children($data);






	if ($category->new) {
		$response= array('state'=>200,'action'=>'created','category_key'=>$category->id,'is_category_other'=>$category->data['Is Category Field Other']);
	} else {
		if ($category->found)
			$response= array('state'=>400,'action'=>'found','category_key'=>$category->found_key,'msg'=>_('Category code already used'));
		else
			$response= array('state'=>400,'action'=>'error','category_key'=>0,'msg'=>$category->msg);
	}


	echo json_encode($response);


}



function list_edit_categories($tipo) {

	switch ($tipo) {
	case('edit_part_category_list'):
		$conf_branch='part_categories';
		$subcategory_link='edit_part_category.php';
		$subject='Part';
		break;
	case('edit_supplier_category_list'):
		$conf_branch='supplier_categories';
		$subcategory_link='edit_supplier_category.php';
		$subject='Supplier';
		break;
	case('edit_customer_category_list'):
		$conf_branch='customer_categories';
		$subcategory_link='edit_customer_category.php';
		$subject='Customer';
		break;
	default:
		exit;
	}

	$conf=$_SESSION['state'][$conf_branch]['edit_categories'];

	if (isset($_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		exit;
	if (isset($_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit;

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



	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_Head'])) {
		$elements['Head']=$_REQUEST['elements_Head'];
	}
	if (isset( $_REQUEST['elements_Node'])) {
		$elements['Node']=$_REQUEST['elements_Node'];
	}

	if (isset( $_REQUEST['elements_Root'])) {
		$elements['Root']=$_REQUEST['elements_Root'];
	}




	$_SESSION['state'][$conf_branch]['edit_categories']['order']=$order;
	$_SESSION['state'][$conf_branch]['edit_categories']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_branch]['edit_categories']['nr']=$number_results;
	$_SESSION['state'][$conf_branch]['edit_categories']['sf']=$start_from;
	$_SESSION['state'][$conf_branch]['edit_categories']['f_field']=$f_field;
	$_SESSION['state'][$conf_branch]['edit_categories']['f_value']=$f_value;
	$_SESSION['state'][$conf_branch]['edit_categories']['elements']=$elements;




	if ($parent=='category') {

		$where=sprintf("where  `Category Subject`='%s' and  `Category Parent Key`=%d ",$subject,
			$parent_key);
	}else if ($parent=='warehouse') {

			$where=sprintf("where  `Category Subject`='%s' and  `Category Warehouse Key`=%d ",$subject,
				$parent_key);

		}else if ($parent=='store') {

			$where=sprintf("where  `Category Subject`='%s' and  `Category Store Key`=%d ",$subject,
				$parent_key);



		}else if ($parent=='none') {

			$where=sprintf("where  `Category Subject`='%s'  ",$subject);
		}




	if ($parent!='category') {
		$_elements='';
		foreach ($elements as $_key=>$_value) {
			if ($_value)
				$_elements.=','.prepare_mysql($_key);
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} else {
			$where.=' and `Category Branch Type` in ('.$_elements.')' ;
		}
	}


	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Category Code` like '".addslashes($f_value)."%'";
	if ($f_field=='label' and $f_value!='')
		$wheref.=" and  `Category Label` like '%".addslashes($f_value)."%'";




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

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any category with code like ")." <b>".$f_value."*</b> ";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any category with label like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('categories with code like')." <b>".$f_value."*</b>";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('categories with label like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;



	$order='`Category Code`';


	$go_format="<a href='$subcategory_link?id=%d'><img src='art/icons/page_go.png' alt='go'></a>";



	$sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_assoc($res)) {

		switch ($row['Category Branch Type']) {
		case('Root'):
			$branch_type='<img src="art/icons/category_root'.($row['Category Can Have Other']=='Yes'?($row['Category Children Other']=='Yes'?'_with_other':'_can_other'):'').'.png" title="'.$row['Category Plain Branch Tree'].'" /> <span title="'._('Number of subcategories').'" >['.number($row['Category Children']).']</span>';
			break;
		case('Node'):
			$branch_type='<img src="art/icons/category_node'.($row['Category Can Have Other']=='Yes'?($row['Category Children Other']=='Yes'?'_with_other':'_can_other'):'').'" /> <span title="'._('Number of subcategories').'" >['.number($row['Category Children']).']</span>';
			break;
		case('Head'):
			if ($row['Is Category Field Other']=='No')
				$branch_type='<img src="art/icons/category_head.png" title="'.$row['Category Plain Branch Tree'].'" /> <span title="'._('Number of parts associated with this category').'" >('.number($row['Category Number Subjects']).')</span>';
			else
				$branch_type='<img src="art/icons/category_head_other.png" title="'.$row['Category Plain Branch Tree'].'" /> <span title="'._('Number of parts associated with this category').'" >('.number($row['Category Number Subjects']).')</span>';

		}

		$delete='<div class="buttons small"><button class="negative">'._('Delete').'</button></div>';
		$adata[]=array(
			'go'=>sprintf($go_format,
				$row['Category Key']),
			'id'=>$row['Category Key'],
			'code'=>$row['Category Code'],
			'label'=>$row['Category Label'],
			'branch_type'=>$branch_type,
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
	global $editor;
	$category=new Category($data['id']);
	$category->editor=$editor;
	$translate_keys=array('id'=>'Category Key','code'=>'Category Code'
		,'label'=>'Category Label'
		,'new_subject'=>'Category Show Subject User Interface'
		,'public_new_subject'=>'Category Show Public New Subject'
		,'public_edit'=>'Category Show Public Edit'
		,'Category Show Public New Subject'=>'Category Show Public New Subject'
		,'Category Show Subject User Interface'=>'Category Show Subject User Interface'
		,'Category Show Public Edit'=>'Category Show Public Edit'
	);

	//if($data['key']=='name'){$data['key']='Category Code';}
	$category->update(array($translate_keys[$data['key']]=>$data['newvalue']));//print($data['key']);
	if ($category->updated) {
		$response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$category->new_value,'branch_tree'=>$category->data['Category XHTML Branch Tree'],'user_view_icon'=>$category->get_user_view_icon());
	} else {
		$response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue'],'branch_tree'=>$category->data['Category XHTML Branch Tree'],'user_view_icon'=>$category->get_user_view_icon());
	}
	echo json_encode($response);
}

function edit_category($data) {
	global $editor;
	$category=new Category($data['category_key']);
	$category->editor=$editor;
	$translate_keys=array(
		'category_key'=>'Category Key',
		'code'=>'Category Code',
		'label'=>'Category Label','Category Show Subject User Interface'=>'Category Show Subject User Interface'
		,'Category Show Public New Subject'=>'Category Show Public New Subject'
		,'Category Show Public Edit'=>'Category Show Public Edit');

	$responses=array();
	foreach ($data['values'] as $key=>$value) {
		$field=$translate_keys[$key];
		$category->update_field_switcher($field,$value['value']);
		$responses[]=array('state'=>200,'action'=>($category->new_value?'updated':'no_change'),'key'=>$key,'newvalue'=>$category->data[$field],'branch_tree'=>$category->data['Category XHTML Branch Tree'],'user_view_icon'=>$category->get_user_view_icon());

	}




	echo json_encode($responses);
}



function delete_category($data) {
	global $editor;
	$category=new Category($data['category_key']);
	if (!$category->id) {
		$response=array('state'=>400,'msg'=>'Category not found');
		echo json_encode($response);
		return;
	}
	$category->editor=$editor;
	$was_category_other=$category->data['Is Category Field Other'];
	$category->delete();
	if ($category->deleted) {

		$response=array('state'=>200,'category_key'=>$category->id,'was_category_other'=>$was_category_other);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'category_key'=>$category->id,'msg'=>$category->msg);
		echo json_encode($response);
	}

}

function disassociate_multiple_subject_from_category($data) {
	global $editor;
	$category=new Category($data['category_key']);
	$category->editor=$editor;


	if ($data['subject_source_checked_type']=='unchecked') {
		foreach (preg_split('/,/',$data['subject_source_checked_subjects']) as $subject_key) {
			$category->disassociate_subject($subject_key);
		}
	}else {


		if ($category->data['Category Subject']=='Part') {


			$f_value=$_SESSION['state']['part_categories']['edit_parts']['f_value'];
			$f_field=$_SESSION['state']['part_categories']['edit_parts']['f_field'];
			$where=sprintf("where B.`Category Key`=%d  ",$category->id);

			$wheref='';
			if ($f_field=='used_in' and $f_value!='')
				$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
			elseif ($f_field=='description' and $f_value!='')
				$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
			elseif ($f_field=='supplied_by' and $f_value!='')
				$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
			elseif ($f_field=='sku' and $f_value!='')
				$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";
			$sql="select `Part SKU` from `Part Dimension` left join `Category Bridge` B on (`Part SKU`=`Subject Key` and `Subject`='Part') $where $wheref  group by `Part SKU` ";

			$res=mysql_query($sql);
			$no_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);
			while ($row=mysql_fetch_assoc($res)) {
				if (!in_array($row['Part SKU'],$no_checked_subjects)) {

					$category->disassociate_subject($row['Part SKU']);


				}

			}



		}


	}

	if (isset($_REQUEST['callback_category_key']) and is_numeric($_REQUEST['callback_category_key']) and $_REQUEST['callback_category_key']!=$data['category_key']) {
		$category=new Category($_REQUEST['callback_category_key']);
	}else {
		$category->get_data('id',$category->id);
	}

	$response=array(
		'state'=>200,
		'number_category_subjects_assigned'=>$category->get('Number Subjects'),
		'number_category_subjects_not_assigned'=>$category->get('Subjects Not Assigned')
	);
	echo json_encode($response);

}




function associate_multiple_subject_to_category($data) {
	global $editor;


	$category=new Category($data['category_key']);
	$category->editor=$editor;
	if ($data['subject_source_checked_type']=='unchecked') {
		foreach (preg_split('/,/',$data['subject_source_checked_subjects']) as $subject_key) {
			$category->associate_subject($subject_key);
		}
	}else {

		if ($category->data['Category Subject']=='Part') {
			if ($data['subject_source']) {

				$f_value=$_SESSION['state']['part_categories']['edit_parts']['f_value'];
				$f_field=$_SESSION['state']['part_categories']['edit_parts']['f_field'];
				$where=sprintf("where B.`Category Key`=%d  ",$data['subject_source']);

				$wheref='';
				if ($f_field=='used_in' and $f_value!='')
					$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
				elseif ($f_field=='description' and $f_value!='')
					$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
				elseif ($f_field=='supplied_by' and $f_value!='')
					$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
				elseif ($f_field=='sku' and $f_value!='')
					$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";
				$sql="select `Part SKU` from `Part Dimension` left join `Category Bridge` B on (`Part SKU`=`Subject Key` and `Subject`='Part') $where $wheref  group by `Part SKU` ";

				$res=mysql_query($sql);
				$no_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);
				while ($row=mysql_fetch_assoc($res)) {
					if (!in_array($row['Part SKU'],$no_checked_subjects)) {
						$category->associate_subject($row['Part SKU']);
					}

				}




			}
			else {

				$f_value=$_SESSION['state']['part_categories']['no_assigned_parts']['f_value'];
				$f_field=$_SESSION['state']['part_categories']['no_assigned_parts']['f_field'];
				$where=sprintf("where (select count(*) from `Category Bridge` where `Subject`='Part'and `Category Key`=%d  and `Subject Key`=`Part SKU`)=0 ",
					$category->id
				);


				$wheref='';
				if ($f_field=='used_in' and $f_value!='')
					$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
				elseif ($f_field=='description' and $f_value!='')
					$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
				elseif ($f_field=='supplied_by' and $f_value!='')
					$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
				elseif ($f_field=='sku' and $f_value!='')
					$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";


				$sql="select `Part SKU`  from `Part Dimension`  $where $wheref ";
				$res=mysql_query($sql);
				$no_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);
				while ($row=mysql_fetch_assoc($res)) {
					if (!in_array($row['Part SKU'],$no_checked_subjects)) {
						$category->associate_subject($row['Part SKU']);
					}

				}


			}
		}
	}

	if (isset($_REQUEST['callback_category_key']) and is_numeric($_REQUEST['callback_category_key']) and $_REQUEST['callback_category_key']!=$data['category_key']) {
		$category=new Category($_REQUEST['callback_category_key']);
	}else {
		$category->get_data('id',$category->id);
	}


	$response=array(
		'state'=>200,
		'number_category_subjects_assigned'=>$category->get('Number Subjects'),
		'number_category_subjects_not_assigned'=>$category->get('Subjects Not Assigned')
	);
	echo json_encode($response);

}


function associate_subject_to_category($data) {
	global $editor;
	$other_value='';
	if (isset($_REQUEST['other_value']))
		$other_value=$_REQUEST['other_value'];




	$category=new Category($data['category_key']);
	$category->editor=$editor;
	$associated=$category->associate_subject($data['subject_key'],false,$other_value);




	if ($associated) {

		if (isset($_REQUEST['callback_category_key']) and is_numeric($_REQUEST['callback_category_key']) and $_REQUEST['callback_category_key']!=$data['category_key']) {
			$category=new Category($_REQUEST['callback_category_key']);
		}else {

			$category->get_data('id',$category->id);
		}

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


function disassociate_subject_from_category($data) {
	global $editor;
	$category=new Category($data['category_key']);
	$category->editor=$editor;
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
	global $editor;
	$category=new Category($data['category_key']);
	$category->editor=$editor;
	$category->update_other_value($data['subject_key'],$data['other_value']);

	$response=array(
		'state'=>200,'newvalue'=>$data['other_value']);

	echo json_encode($response);

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



	if (isset( $_REQUEST['checked_all'])) {
		$checked_all=$_REQUEST['checked_all'];

	}else {
		$checked_all=$conf['checked_all'];

	}




	//$check_all=true;

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
	$_SESSION['state']['part_categories']['no_assigned_parts']['checked_all']=$checked_all;


	$filter_msg='';
	$sql_type='part';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (!is_numeric($start_from))
		$start_from=0;
	if (!is_numeric($number_results))
		$number_results=25;


	//NOTE if changed change associate_multiple_subject_to_category too
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

		$sql="select count(`Part SKU`) as total_without_filters from `Part Dimension`  $where  ";

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
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part used in ")." <b>*".$f_value."*</b> ";
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
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts used in')." <b>*".$f_value."*</b>";
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

	////print $sql;
	// if($checked_all){
	$checkbox_checked_format='<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="no_assigned_subject_%d" onClick="check_no_assigned_subject(%d)"/>';
	// }else{
	$checkbox_unchecked_format='<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="no_assigned_subject_%d" onClick="check_no_assigned_subject(%d)"/>';
	// }


	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$move_here='<div class="buttons small"><button>'._('Assign Here').'</button></div>';

		$move='<div class="buttons small"><button>'._('Assign to Category').'</button></div>';


		$checkbox_checked=sprintf($checkbox_checked_format,
			$data['Part SKU'],
			$data['Part SKU']
		);
		$checkbox_unchecked=sprintf($checkbox_unchecked_format,
			$data['Part SKU'],
			$data['Part SKU']
		);

		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,
			'checked'=>0,
			'sku'=>$data['Part SKU'],
			'subject_key'=>$data['Part SKU'],

			'formated_sku'=>sprintf('<a href="part.php?sku=%d">%06d</a>',$data['Part SKU'],$data['Part SKU']),
			'description'=>$data['Part Unit Description'],
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
			'total_records'=>$total,
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

		$sql="select count(`Part SKU`) as total_without_filters from `Part Dimension` left join `Category Bridge` B on (`Part SKU`=`Subject Key` and `Subject`='Part')  $where  ";

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


		$checkbox_unchecked=sprintf('<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Part SKU'],
			$data['Part SKU']
		);
		$checkbox_checked=sprintf('<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Part SKU'],
			$data['Part SKU']
		);

		$hierarchy='<img style="width:14px;" src="art/icons/hierarchy_grey.png" alt="hierarchy" title="'.$data['Category Plain Branch Tree'].'" />';
		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,

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
			'total_records'=>$total,

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
		$wheref.=" and  `Category Code` like '".addslashes($f_value)."%'";
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
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("none with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("none with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'." $total "._('with code like')." <b>".$f_value."*</b>";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'." $total "._('with name like')." <b>*".$f_value."*</b>";
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

function list_suppliers_no_assigned_to_category() {
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
	$conf=$_SESSION['state']['supplier_categories']['no_assigned_suppliers'];


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



	if (isset( $_REQUEST['checked_all'])) {
		$checked_all=$_REQUEST['checked_all'];

	}else {
		$checked_all=$conf['checked_all'];

	}




	//$check_all=true;

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['order']=$order;
	$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['order_dir']=$order_direction;
	$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['nr']=$number_results;
	$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['sf']=$start_from;
	$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['f_field']=$f_field;
	$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['f_value']=$f_value;
	$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['checked_all']=$checked_all;


	$filter_msg='';
	$sql_type='supplier';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (!is_numeric($start_from))
		$start_from=0;
	if (!is_numeric($number_results))
		$number_results=25;


	//NOTE if changed change associate_multiple_subject_to_category too
	$where=sprintf("where (select count(*) from `Category Bridge` where `Subject`='Supplier'and `Category Key`=%d  and `Subject Key`=`Supplier Key`)=0 ",
		$parent_key
	);

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Supplier Code` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Supplier Name` like '%".addslashes($f_value)."%'";


	$sql="select count(`Supplier Key`) as total from `Supplier Dimension`  $where $wheref ";


	//print $sql;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(`Supplier Key`) as total_without_filters from `Supplier Dimension`  $where  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=$total_records." ".ngettext('supplier','suppliers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with code like")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with name like")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {


		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('suppliers with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('suppliers with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';






	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='code')
		$order='`Supplier Code`';
	elseif ($order=='name')
		$order='`Supplier Name`';

	$sql="select `Supplier Key`,`Supplier Name`,`Supplier Code` from `Supplier Dimension`  $where $wheref order by $order $order_direction limit $start_from,$number_results";



	$adata=array();
	$result=mysql_query($sql);

	//print $sql;
	// if($checked_all){
	$checkbox_checked_format='<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="no_assigned_subject_%d" onClick="check_no_assigned_subject(%d)"/>';
	// }else{
	$checkbox_unchecked_format='<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="no_assigned_subject_%d" onClick="check_no_assigned_subject(%d)"/>';
	// }


	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$move_here='<div class="buttons small"><button>'._('Assign Here').'</button></div>';

		$move='<div class="buttons small"><button>'._('Assign to Category').'</button></div>';


		$checkbox_checked=sprintf($checkbox_checked_format,
			$data['Supplier Key'],
			$data['Supplier Key']
		);
		$checkbox_unchecked=sprintf($checkbox_unchecked_format,
			$data['Supplier Key'],
			$data['Supplier Key']
		);

		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,
			'checked'=>0,
			'subject_key'=>$data['Supplier Key'],

			'code'=>sprintf('<a href="supplier.php?sku=%d">%s</a>',$data['Supplier Key'],$data['Supplier Code']),
			'name'=>$data['Supplier Name'],
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
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}
function list_suppliers_assigned_to_category() {

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
	$conf=$_SESSION['state']['supplier_categories']['edit_suppliers'];


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


	$_SESSION['state']['supplier_categories']['edit_suppliers']['order']=$order;
	$_SESSION['state']['supplier_categories']['edit_suppliers']['order_dir']=$order_direction;
	$_SESSION['state']['supplier_categories']['edit_suppliers']['nr']=$number_results;
	$_SESSION['state']['supplier_categories']['edit_suppliers']['sf']=$start_from;
	$_SESSION['state']['supplier_categories']['edit_suppliers']['f_field']=$f_field;
	$_SESSION['state']['supplier_categories']['edit_suppliers']['f_value']=$f_value;


	$filter_msg='';
	$sql_type='supplier';
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
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Supplier Code` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Supplier Name` like '%".addslashes($f_value)."%'";

	$sql="select count(`Supplier Key`) as total from `Supplier Dimension` left join `Category Bridge` B on (`Supplier Key`=`Subject Key` and `Subject`='Supplier') $where $wheref ";


	//print $sql;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(`Supplier Key`) as total_without_filters from `Supplier Dimension` left join `Category Bridge` B on (`Supplier Key`=`Subject Key` and `Subject`='Supplier')  $where  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=$total_records." ".ngettext('supplier','suppliers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with code like")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with name like")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {


		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('suppliers with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('suppliers with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';





	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='code' )
		$order='`Supplier Code`';
	elseif ($order=='name')
		$order='`Supplier Name`';



	$sql="select `Category Plain Branch Tree`,`Supplier Key`,`Supplier Name`,`Supplier Code` from `Supplier Dimension` left join `Category Bridge` B on (`Supplier Key`=B.`Subject Key` and `Subject`='Supplier') left join `Category Dimension` C on (C.`Category Key`=B.`Category Head Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results";



	$adata=array();
	$result=mysql_query($sql);


	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


		$move='<div class="buttons small"><button>'._('Move').'</button></div>';
		$delete='<div class="buttons small"><button>'._('Remove').'</button></div>';


		$checkbox_unchecked=sprintf('<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Supplier Key'],
			$data['Supplier Key']
		);
		$checkbox_checked=sprintf('<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Supplier Key'],
			$data['Supplier Key']
		);

		$hierarchy='<img style="width:14px;" src="art/icons/hierarchy_grey.png" alt="hierarchy" title="'.$data['Category Plain Branch Tree'].'" />';
		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,
			'subject_key'=>$data['Supplier Key'],
			'code'=>sprintf('<a href="supplier.php?sku=%d">%s</a>',$data['Supplier Key'],$data['Supplier Code']),
			'name'=>$data['Supplier Name'],

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
			'total_records'=>$total,

			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function list_customers_no_assigned_to_category() {
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
	$conf=$_SESSION['state']['customer_categories']['no_assigned_customers'];


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



	if (isset( $_REQUEST['checked_all'])) {
		$checked_all=$_REQUEST['checked_all'];

	}else {
		$checked_all=$conf['checked_all'];

	}




	//$check_all=true;

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$_SESSION['state']['customer_categories']['no_assigned_customers']['order']=$order;
	$_SESSION['state']['customer_categories']['no_assigned_customers']['order_dir']=$order_direction;
	$_SESSION['state']['customer_categories']['no_assigned_customers']['nr']=$number_results;
	$_SESSION['state']['customer_categories']['no_assigned_customers']['sf']=$start_from;
	$_SESSION['state']['customer_categories']['no_assigned_customers']['f_field']=$f_field;
	$_SESSION['state']['customer_categories']['no_assigned_customers']['f_value']=$f_value;
	$_SESSION['state']['customer_categories']['no_assigned_customers']['checked_all']=$checked_all;


	$filter_msg='';
	$sql_type='customer';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (!is_numeric($start_from))
		$start_from=0;
	if (!is_numeric($number_results))
		$number_results=25;


	//NOTE if changed change associate_multiple_subject_to_category too
	$where=sprintf("where (select count(*) from `Category Bridge` where `Subject`='Customer'and `Category Key`=%d  and `Subject Key`=`Customer Key`)=0 ",
		$parent_key
	);

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';


	if (($f_field=='customer name'     )  and $f_value!='') {
		$wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
	}
	elseif (($f_field=='postcode'     )  and $f_value!='') {
		$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
	}
	elseif ($f_field=='id'  )
		$wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
	elseif ($f_field=='last_more' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
	elseif ($f_field=='last_less' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
	elseif ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`>=".$f_value."    ";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {

			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
			}

		}
	}
	$sql="select count(`Customer Key`) as total from `Customer Dimension`  $where $wheref ";




	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(`Customer Key`) as total_without_filters from `Customer Dimension`  $where  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=$total_records." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('last_less'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		}
	}
	else
		$filter_msg='';




	$_order=$order;
	$_order_dir=$order_dir;

	if ($order=='name')
		$order='`Customer File As`';
	elseif ($order=='id')
		$order='`Customer Key`';
	elseif ($order=='location')
		$order='`Customer Main Location`';
	elseif ($order=='orders')
		$order='`Customer Orders`';
	elseif ($order=='email')
		$order='`Customer Main Plain Email`';
	elseif ($order=='telephone')
		$order='`Customer Main Plain Telephone`';
	elseif ($order=='last_order')
		$order='`Customer Last Order Date`';
	elseif ($order=='contact_name')
		$order='`Customer Main Contact Name`';
	elseif ($order=='address')
		$order='`Customer Main Location`';
	elseif ($order=='town')
		$order='`Customer Main Town`';
	elseif ($order=='postcode')
		$order='`Customer Main Postal Code`';
	elseif ($order=='region')
		$order='`Customer Main Country First Division`';
	elseif ($order=='country')
		$order='`Customer Main Country`';
	//  elseif($order=='ship_address')
	//  $order='`customer main ship to header`';
	elseif ($order=='ship_town')
		$order='`Customer Main Delivery Address Town`';
	elseif ($order=='ship_postcode')
		$order='`Customer Main Delivery Address Postal Code`';
	elseif ($order=='ship_region')
		$order='`Customer Main Delivery Address Country Region`';
	elseif ($order=='ship_country')
		$order='`Customer Main Delivery Address Country`';
	elseif ($order=='net_balance')
		$order='`Customer Net Balance`';
	elseif ($order=='balance')
		$order='`Customer Outstanding Net Balance`';
	elseif ($order=='total_profit')
		$order='`Customer Profit`';
	elseif ($order=='total_payments')
		$order='`Customer Net Payments`';
	elseif ($order=='top_profits')
		$order='`Customer Profits Top Percentage`';
	elseif ($order=='top_balance')
		$order='`Customer Balance Top Percentage`';
	elseif ($order=='top_orders')
		$order='``Customer Orders Top Percentage`';
	elseif ($order=='top_invoices')
		$order='``Customer Invoices Top Percentage`';
	elseif ($order=='total_refunds')
		$order='`Customer Total Refunds`';
	elseif ($order=='contact_since')
		$order='`Customer First Contacted Date`';
	elseif ($order=='activity')
		$order='`Customer Type by Activity`';
	elseif ($order=='logins')
		$order='`Customer Number Web Logins`';
	elseif ($order=='failed_logins')
		$order='`Customer Number Web Failed Logins`';
	elseif ($order=='requests')
		$order='`Customer Number Web Requests`';
	else
		$order='`Customer File As`';


	$sql="select `Customer Orders`,`Customer Main Location`,`Customer Type by Activity`,`Customer Key`,`Customer Name` from `Customer Dimension`  $where $wheref order by $order $order_direction limit $start_from,$number_results";



	$adata=array();
	$result=mysql_query($sql);

	//print $sql;
	// if($checked_all){
	$checkbox_checked_format='<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="no_assigned_subject_%d" onClick="check_no_assigned_subject(%d)"/>';
	// }else{
	$checkbox_unchecked_format='<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="no_assigned_subject_%d" onClick="check_no_assigned_subject(%d)"/>';
	// }


	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


		switch ($data['Customer Type by Activity']) {
		case 'Inactive':
			$activity=_('Lost');
			break;
		case 'Active':
			$activity=_('Active');
			break;
		case 'Prospect':
			$activity=_('Prospect');
			break;
		default:
			$activity=$data['Customer Type by Activity'];
			break;
		}

		$move_here='<div class="buttons small"><button>'._('Assign Here').'</button></div>';

		$move='<div class="buttons small"><button>'._('Assign to Category').'</button></div>';


		$checkbox_checked=sprintf($checkbox_checked_format,
			$data['Customer Key'],
			$data['Customer Key']
		);
		$checkbox_unchecked=sprintf($checkbox_unchecked_format,
			$data['Customer Key'],
			$data['Customer Key']
		);

		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,
			'checked'=>0,
			'subject_key'=>$data['Customer Key'],
			'id'=>sprintf('<a href="customer.php?id=%d">%05d</a>',$data['Customer Key'],$data['Customer Key']),

			'name'=>sprintf('<a href="customer.php?id=%d">%s</a>',$data['Customer Key'],$data['Customer Name']),
			'location'=>$data['Customer Main Location'],
			'orders'=>number($data['Customer Orders']),
			'activity'=>$activity,
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
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}
function list_customers_assigned_to_category() {

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
	$conf=$_SESSION['state']['customer_categories']['edit_customers'];


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


	$_SESSION['state']['customer_categories']['edit_customers']['order']=$order;
	$_SESSION['state']['customer_categories']['edit_customers']['order_dir']=$order_direction;
	$_SESSION['state']['customer_categories']['edit_customers']['nr']=$number_results;
	$_SESSION['state']['customer_categories']['edit_customers']['sf']=$start_from;
	$_SESSION['state']['customer_categories']['edit_customers']['f_field']=$f_field;
	$_SESSION['state']['customer_categories']['edit_customers']['f_value']=$f_value;


	$filter_msg='';
	$sql_type='customer';
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
	if (($f_field=='customer name'     )  and $f_value!='') {
		$wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
	}
	elseif (($f_field=='postcode'     )  and $f_value!='') {
		$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
	}
	elseif ($f_field=='id'  )
		$wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
	elseif ($f_field=='last_more' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
	elseif ($f_field=='last_less' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
	elseif ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`>=".$f_value."    ";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {

			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
			}

		}
	}
	$sql="select count(`Customer Key`) as total from `Customer Dimension` left join `Category Bridge` B on (`Customer Key`=`Subject Key` and `Subject`='Customer') $where $wheref ";


	//print $sql;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(`Customer Key`) as total_without_filters from `Customer Dimension` left join `Category Bridge` B on (`Customer Key`=`Subject Key` and `Subject`='Customer')  $where  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=$total_records." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('last_less'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		}
	}
	else
		$filter_msg='';



	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='name')
		$order='`Customer File As`';
	elseif ($order=='id')
		$order='`Customer Key`';
	elseif ($order=='location')
		$order='`Customer Main Location`';
	elseif ($order=='orders')
		$order='`Customer Orders`';
	elseif ($order=='email')
		$order='`Customer Main Plain Email`';
	elseif ($order=='telephone')
		$order='`Customer Main Plain Telephone`';
	elseif ($order=='last_order')
		$order='`Customer Last Order Date`';
	elseif ($order=='contact_name')
		$order='`Customer Main Contact Name`';
	elseif ($order=='address')
		$order='`Customer Main Location`';
	elseif ($order=='town')
		$order='`Customer Main Town`';
	elseif ($order=='postcode')
		$order='`Customer Main Postal Code`';
	elseif ($order=='region')
		$order='`Customer Main Country First Division`';
	elseif ($order=='country')
		$order='`Customer Main Country`';
	//  elseif($order=='ship_address')
	//  $order='`customer main ship to header`';
	elseif ($order=='ship_town')
		$order='`Customer Main Delivery Address Town`';
	elseif ($order=='ship_postcode')
		$order='`Customer Main Delivery Address Postal Code`';
	elseif ($order=='ship_region')
		$order='`Customer Main Delivery Address Country Region`';
	elseif ($order=='ship_country')
		$order='`Customer Main Delivery Address Country`';
	elseif ($order=='net_balance')
		$order='`Customer Net Balance`';
	elseif ($order=='balance')
		$order='`Customer Outstanding Net Balance`';
	elseif ($order=='total_profit')
		$order='`Customer Profit`';
	elseif ($order=='total_payments')
		$order='`Customer Net Payments`';
	elseif ($order=='top_profits')
		$order='`Customer Profits Top Percentage`';
	elseif ($order=='top_balance')
		$order='`Customer Balance Top Percentage`';
	elseif ($order=='top_orders')
		$order='``Customer Orders Top Percentage`';
	elseif ($order=='top_invoices')
		$order='``Customer Invoices Top Percentage`';
	elseif ($order=='total_refunds')
		$order='`Customer Total Refunds`';
	elseif ($order=='contact_since')
		$order='`Customer First Contacted Date`';
	elseif ($order=='activity')
		$order='`Customer Type by Activity`';
	elseif ($order=='logins')
		$order='`Customer Number Web Logins`';
	elseif ($order=='failed_logins')
		$order='`Customer Number Web Failed Logins`';
	elseif ($order=='requests')
		$order='`Customer Number Web Requests`';
	else
		$order='`Customer File As`';

	$sql="select B.`Category Key`,`Other Note`,`Category Plain Branch Tree`,`Customer Key`,`Customer Name` from `Customer Dimension` left join `Category Bridge` B on (`Customer Key`=B.`Subject Key` and `Subject`='Customer') left join `Category Dimension` C on (C.`Category Key`=B.`Category Head Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results";



	$adata=array();
	$result=mysql_query($sql);
	//print $sql;

	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


		$move='<div class="buttons small"><button>'._('Move').'</button></div>';
		$delete='<div class="buttons small"><button>'._('Remove').'</button></div>';


		$checkbox_unchecked=sprintf('<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Customer Key'],
			$data['Customer Key']
		);
		$checkbox_checked=sprintf('<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Customer Key'],
			$data['Customer Key']
		);

		$hierarchy='<img style="width:14px;" src="art/icons/hierarchy_grey.png" alt="hierarchy" title="'.$data['Category Plain Branch Tree'].'" />';
		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,
			'other_value'=>$data['Other Note'],
			'subject_key'=>$data['Customer Key'],
			'category_key'=>$data['Category Key'],
			'id'=>sprintf('<a href="customer.php?id=%d">%05d</a>',$data['Customer Key'],$data['Customer Key']),

			'name'=>sprintf('<a href="customer.php?id=%d">%s</a>',$data['Customer Key'],$data['Customer Name']),

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
			'total_records'=>$total,

			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}
