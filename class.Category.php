<?php
/*
 File: Category.php

 This file contains the Category Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Node.php';

class Category extends DB_Table {



	function Category($a1,$a2=false,$a3=false) {
		$this->update_subjects_data=true;
		$this->table_name='Category';
		$this->ignore_fields=array('Category Key');
		$this->all_descendants_keys=array();

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
				$this->find($a2,'create');

			}
		elseif (preg_match('/find/i',$a1))
			$this->find($a2,$a1);
		else
			$this->get_data($a1,$a2,$a3);

	}

	function get_data($tipo,$tag,$tag2=false) {
		switch ($tipo) {
		case 'rootkey_code':
			$sql=sprintf("select * from `Category Dimension` where `Category Root Key`=%d and `Category Code`=%s ",$tag,prepare_mysql($tag2));
			break;
		case 'subject_code':
			// Note it can not be unique is just give the 1st resutl
			$sql=sprintf("select * from `Category Dimension` where `Category Subject`=%s and `Category Code`=%s ",prepare_mysql($tag),prepare_mysql($tag2));
			break;
		default:
			$sql=sprintf("select * from `Category Dimension` where `Category Key`=%d",$tag);

			break;
		}
		$result=mysql_query($sql);
		//print $sql;
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
			$this->id=$this->data['Category Key'];

			if ($this->data['Category Subject']=='Part') {
				$sql=sprintf("select * from `Part Category Dimension` where `Part Category Key`=%d",$this->id);
				//print "$sql\n";
				$result2=mysql_query($sql);
				if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)  ) {
					$this->data=array_merge($this->data,$row);
				}

			}

		}



	}


	function find($raw_data,$options) {

		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

		$this->candidate=array();
		$this->found=false;
		$this->found_key=0;
		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {

			if (array_key_exists($key,$data))
				$data[$key]=$value;

		}

		if (!$data['Category Store Key'] and $data['Category Parent Key']) {
			$parent_category=new Category($data['Category Parent Key']);
			$data['Category Store Key']=$parent_category->data['Category Store Key'];
		}

		$fields=array();


		$sql=sprintf("select `Category Key` from `Category Dimension` where  `Category Parent Key`=%d and `Category Store Key`=%d and `Category Code`=%s ",
			$data['Category Parent Key'],
			$data['Category Store Key'],
			prepare_mysql($data['Category Code'])

		);
		//print_r($fields);
		foreach ($fields as $field) {
			$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
		}


		// print "$sql";

		$result=mysql_query($sql);
		$num_results=mysql_num_rows($result);
		if ($num_results==1) {
			$row=mysql_fetch_array($result, MYSQL_ASSOC);
			$this->found=true;
			$this->found_key=$row['Category Key'];

		}
		if ($this->found) {
			$this->get_data('id',$this->found_key);
		}

		if ($create and !$this->found) {
			$this->create($data);

		}


	}



	function create($data) {

		if ($data['Category Label']=='' ) {
			$data['Category Label']=$data['Category Code'];
		}

		//print_r($data);

		// $data=array('`Category Code`'=>$data['Category Code']);

		$nodes=new Nodes('`Category Dimension`');
		$nodes->add_new($data['Category Parent Key'] , $data);

		if ($nodes->id) {

			$this->get_data('id',$nodes->id);
			//print_r($this->data);

			if ($this->data['Category Parent Key']==0) {
				$abstract=_('Category')." (".$this->data['Category Subject'].")  ".$this->data['Category Code']." "._('Created');
				$details=_trim(_('New Category')." (".$this->data['Category Subject'].")  \"".$this->data['Category Code']."\"  "._('added'));
			} else {
				$abstract=_('Category')." (".$this->data['Category Subject'].")  ".$this->data['Category Code']." "._('Created');
				$details=_trim(_('New Category')." ".$this->data['Category Subject'].") \"".$this->data['Category Code']."\"  "._('added'));

			}


			$history_data=array(
				'History Abstract'=>$abstract,
				'History Details'=>$details,
				'Indirect Object Key'=>$this->data['Category Parent Key'],
				'Indirect Object'=>'Category '.$this->data['Category Subject'],
				'Direct Object Key'=>$this->id,
				'Direct Object'=>'Category '.$this->data['Category Subject'],
				'Action'=>'created'
			);
			$this->add_history($history_data);
			$this->new=true;
			if ($this->data['Category Subject']=='Invoice') {
				$sql=sprintf("insert into `Invoice Category Dimension` (`Category Key`,`Store Key`) values (%d,%d)",$this->id,$this->data['Category Store Key']);
				mysql_query($sql);
			}
			elseif ($this->data['Category Subject']=='Supplier') {
				$sql=sprintf("insert into `Supplier Category Dimension` (`Category Key`) values (%d)",$this->id);
				mysql_query($sql);
			}elseif ($this->data['Category Subject']=='Part') {
				$sql=sprintf("insert into `Part Category Dimension` (`Part Category Key`) values (%d)",$this->id);
				mysql_query($sql);
			}


			$this->update_branch_tree();
			$parent_category=new Category($data['Category Parent Key']);
			if ($parent_category->id) {
				$parent_category->update_children_data();
			}


		}

	}



	function create_children($data) {

		if ($this->data['Category Deep']>$this->data['Category Max Deep']) {

			$this->msg='max deep';
			$this->error=true;
			return;
		}


		$branch_type=$this->data['Category Branch Type'];
		$data['Category Subject']=$this->data['Category Subject'];
		$data['Category Subject Key']=$this->data['Category Subject Key'];

		$data['Category Warehouse Key']=$this->data['Category Warehouse Key'];
		$data['Category Store Key']=$this->data['Category Store Key'];
		$data['Category Store Key']=$this->data['Category Store Key'];
		$data['Category Branch Type']='Head';
		$data['Category Subject Multiplicity']=$this->data['Category Subject Multiplicity'];

		$data['Category Root Key']=$this->data['Category Root Key'];
		$data['Category Parent Key']=$this->id;

		$data['Is Category Field Other']='No';
		if (array_key_exists('Is Category Field Other',$data)) {
			if ($data['Is Category Field Other']=='Yes' and $this->data['Category Can Have Other']=='Yes') {
				$data['Is Category Field Other']='Yes';

			}
		}

		$subcategory=new Category('find create',$data);

		return $subcategory;




	}

	function get_period($period,$key) {
		return $this->get($period.' '.$key);
	}



	function get($key='') {

		if (isset($this->data[$key]))
			return $this->data[$key];


		if ($key=='Subjects Not Assigned' or $key=='Number Subjects') {
			return number($this->data['Category '.$key]);
		}
		if ($key=='Number Children') {
			return number($this->data['Category Children']);
		}



		switch ($this->data['Category Subject']) {
		case 'Part':


			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Month To|Today|Week To).*(Margin|GMROI)$/',$key)) {

				$amount='Part Category '.$key;

				return percentage($this->data[$amount],1);
			}


			if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers|Sold|Given|Required)$/',$key)) {

				$amount='Part Category '.$key;

				return number($this->data[$amount]);
			}

			if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Amount|Profit)$/',$key)) {

				$amount='Part Category '.$key;

				return money($this->data[$amount]);
			}
			return $key;
		}

		return false;
	}



	function update_branch_tree() {

		//'Product','Supplier','Customer','Family','Invoice','Part'

		switch ($this->data['Category Subject']) {
		case('Part'):
			$link='part_category.php';
			break;
		case('Customer'):
			$link='customer_category.php';
			break;
		case('Invoice'):
			$link='invoice_category.php';
			break;
		case('Supplier'):
			$link='supplier_category.php';
			break;
		case('Product'):
			$link='product_category.php';
			break;
		case('Family'):
			$link='family_category.php';
			break;
		default:
			$link='category.php';
		}

		$category_keys=preg_split('/\>/',preg_replace('/\>$/','',$this->data['Category Position']));

		$sql=sprintf("select `Category Code`,`Category Key` from `Category Dimension` where `Category Key` in (%s)",join(',',$category_keys));
		//print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$category_data[$row['Category Key']]=$row['Category Code'];
		}
		$xhtml_tree='';
		$plain_tree='';
		foreach ($category_keys as $key) {
			if (array_key_exists($key, $category_data)) {
				$xhtml_tree.=sprintf(" <a href='%s?id=%d'>%s</a> &rarr;",$link,$key,$category_data[$key]);
				$plain_tree.=sprintf(" %s >",$category_data[$key]);
			}
		}
		$xhtml_tree=preg_replace('/\s*\&rarr\;$/', '', $xhtml_tree);
		$plain_tree=preg_replace('/\s*\>$/', '', $plain_tree);

		$this->data['Category XHTML Branch Tree']=$xhtml_tree;
		$this->data['Category Plain Branch Tree']=$plain_tree;

		$sql=sprintf("update `Category Dimension` set `Category XHTML Branch Tree`=%s ,`Category Plain Branch Tree`=%s where `Category Key`=%d ",
			prepare_mysql($this->data['Category XHTML Branch Tree']),
			prepare_mysql($this->data['Category Plain Branch Tree']),
			$this->id
		);
		mysql_query($sql);



	}





	function delete() {
		$this->deleted=false;

		$sql_new_deleted_category=sprintf("insert into `Category Deleted Dimension` (`Category Deleted Key`, `Category Deleted Branch Type`, `Category Deleted Store Key`, `Category Deleted Warehouse Key`, `Category Deleted XHTML Branch Tree`, `Category Deleted Plain Branch Tree`,
`Category Deleted Deep`, `Category Deleted Children`, `Category Deleted Code`, `Category Deleted Label`, `Category Deleted Subject`, `Category Deleted Subject Key`, `Category Deleted Number Subjects`,`Category Deleted Children Subjects Assigned`,`Category Deleted Date`)
VALUES (%d,%s, %d, %d, %s,%s, %d, %d, %s, %s, %s, %d, %d,%d,NOW())",
			$this->id,
			prepare_mysql($this->data['Category Branch Type']),
			$this->data['Category Store Key'],
			$this->data['Category Warehouse Key'],
			prepare_mysql($this->data['Category XHTML Branch Tree']),
			prepare_mysql($this->data['Category Plain Branch Tree']),
			$this->data['Category Deep'],
			$this->data['Category Children'],
			prepare_mysql($this->data['Category Code']),
			prepare_mysql($this->data['Category Label']),
			prepare_mysql($this->data['Category Subject']),
			$this->data['Category Subject Key'],
			$this->data['Category Number Subjects'],
			$this->data['Category Children Subjects Assigned']

		);

		$parent_keys=$this->get_parent_keys();

		foreach ($this->get_children_objects() as $children) {

			$children->delete();
		}


		$sql=sprintf("select `Subject Key` from `Category Bridge`  where `Category Key`=%d  ",
			$this->id
		);
		$res=mysql_query($sql);
		$this->deleting_category=true;
		while ($row=mysql_fetch_assoc($res)) {
			$this->disassociate_subject($row['Subject Key']);

		}





		if ($this->data['Category Subject']=='Invoice') {
			$sql=sprintf('delete from `Invoice Category Dimension` where `Category Key`=%d',$this->id);
			mysql_query($sql);
		}
		elseif ($this->data['Category Subject']=='Supplier') {
			$sql=sprintf('delete from `Supplier Category Dimension` where `Category Key`=%d',$this->id);
			mysql_query($sql);
		}elseif ($this->data['Category Subject']=='Part') {
			$sql=sprintf('delete from `Part Category Dimension`  where `Category Key`=%d',$this->id);
			mysql_query($sql);
		}





		//print $sql_new_deleted_category;
		mysql_query($sql_new_deleted_category);

		$sql=sprintf('delete from `Category Dimension` where `Category Key`=%d',$this->id);
		mysql_query($sql);

		foreach ($parent_keys as $parent_key) {
			$parent_category=new Category($parent_key);
			if ($parent_category->id) {
				$parent_category->update_children_data();
			}
		}

		$history_data=array(
			'Direct Object Key'=>$this->id,
			'Direct Object'=>'Category '.$this->data['Category Subject'],
			'Indirect Object Key'=>$this->data['Category Parent Key'],
			'Indirect Object'=>'Category '.$this->data['Category Subject'],
			'History Abstract'=>_('Category deleted').' ('.$this->data['Category Code'].')'
			,'History Details'=>_trim(_('Category')." ".$this->data['Category Code'].' ('.$this->data['Category Label'].') '._('has been deleted permanently') )
			,'Action'=>'deleted'
		);
		$this->add_history($history_data);
		$this->deleted=true;

	}


	function sub_category_selected_by_subject($subject_key) {
		$sub_category_keys_selected=array();
		$sql=sprintf("select C.`Category Key` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where `Category Subject`=%s and `Subject Key`=%d and `Category Parent Key`=%d",
			prepare_mysql($this->data['Category Subject']),
			$subject_key,
			$this->id
		);
		$res=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_assoc($res)) {
			$sub_category_keys_selected[$row['Category Key']]=$row['Category Key'];
		}
		return $sub_category_keys_selected;
	}


	function load_all_descendants_keys($category_key=false) {

		if (!$category_key) {
			$category_key=$this->id;
			$this->all_descendants_keys=array();
		}

		$sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d and `Category Key`!=0 ",
			$category_key
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$this->all_descendants_keys[$row['Category Key']]=$row['Category Key'];

			$this->load_all_descendants_keys($row['Category Key']);


		}



	}


	function get_children_keys() {
		$sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d ",
			$this->id
		);

		$res=mysql_query($sql);
		$children_keys=array();
		while ($row=mysql_fetch_assoc($res)) {
			$children_keys[$row['Category Key']]=$row['Category Key'];
		}
		return $children_keys;

	}




	function get_children_objects() {
		$sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d order by `Category Code` ",
			$this->id
		);
		//  print $sql;
		$res=mysql_query($sql);
		$children_keys=array();
		while ($row=mysql_fetch_assoc($res)) {
			$children_keys[$row['Category Key']]=new Category($row['Category Key']);
		}

		return $children_keys;

	}

	function get_children_objects_new_subject() {
		$sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d and `Category Show Subject User Interface`='Yes' order by `Category Code` ",
			$this->id
		);
		//  print $sql;
		$res=mysql_query($sql);
		$children_keys=array();
		while ($row=mysql_fetch_assoc($res)) {
			$children_keys[$row['Category Key']]=new Category($row['Category Key']);
		}

		return $children_keys;

	}

	function get_children_objects_public_edit() {
		$sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d and `Category Show Public Edit`='Yes' order by `Category Code` ",
			$this->id
		);
		//  print $sql;
		$res=mysql_query($sql);
		$children_keys=array();
		while ($row=mysql_fetch_assoc($res)) {
			$children_keys[$row['Category Key']]=new Category($row['Category Key']);
		}

		return $children_keys;

	}


	function get_children_objects_public_new_subject() {
		$sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d and `Category Show Public New Subject`='Yes' order by `Category Code` ",
			$this->id
		);
		//  print $sql;
		$res=mysql_query($sql);
		$children_keys=array();
		while ($row=mysql_fetch_assoc($res)) {
			$children_keys[$row['Category Key']]=new Category($row['Category Key']);
		}

		return $children_keys;

	}



	function update_field_switcher($field,$value,$options='') {


		$base_data=$this->base_data();

		if (array_key_exists($field,$base_data)) {

			if ($field=='Category Code') {
				$this->update_field($field,$value,$options);
				$this->update_branch_tree();

				$this->load_all_descendants_keys();

				foreach ($this->all_descendants_keys as $descendant_key) {
					$descendant=new Category($descendant_key);
					$descendant->update_branch_tree();
				}
			}elseif ($value!=$this->data[$field]) {
				$this->update_field($field,$value,$options);

			}
		}


	}



	function update_children_data() {

		$number_of_children=0;

		$sql = sprintf("SELECT COUNT(*)  as num  FROM `Category Dimension` WHERE `Category Parent Key`=%d and `Category Subject`=%s ",
			$this->id,
			prepare_mysql($this->data['Category Subject'])
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_of_children=$row['num'];
		}
		$has_children_other='No';
		$sql = sprintf("SELECT COUNT(*)  as num  FROM `Category Dimension` WHERE `Category Parent Key`=%d and `Category Subject`=%s and `Is Category Field Other`='Yes' ",
			$this->id,
			prepare_mysql($this->data['Category Subject'])
		);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0) {
				$has_children_other='Yes';
			}
		}




		$max_deep=0;
		if ($number_of_children) {

			$sql = sprintf("SELECT `Category Position`  FROM `Category Dimension` WHERE `Category Position`	RLIKE '^%s[0-9]+>$' and `Category Subject`=%s ",
				$this->data['Category Position'],
				prepare_mysql($this->data['Category Subject'])
			);

			$res=mysql_query($sql);
			$max_deep=0;
			while ($row=mysql_fetch_assoc($res)) {
				$deep=count(preg_split('/\>/',$row['Category Position']))-2;
				if ($deep>$max_deep)
					$max_deep=$deep;
			}
		}

		$sql=sprintf("update `Category Dimension` set `Category Children`=%d ,`Category Children Deep`=%d , `Category Children Other`=%s where `Category Key`=%d ",
			$number_of_children,
			$max_deep,
			prepare_mysql($has_children_other),
			$this->id
		);
		mysql_query($sql);


		if ($this->data['Category Branch Type']!='Root') {
			if ($number_of_children) {
				$sql=sprintf("update `Category Dimension` set `Category Branch Type`='Node' where `Category Key`=%d ",
					$this->id
				);
				mysql_query($sql);
			}else {

				$sql=sprintf("update `Category Dimension` set `Category Branch Type`='Head' where `Category Key`=%d ",
					$this->id
				);
				mysql_query($sql);
			}


		}



	}
	function update_number_of_subjects() {
		$sql=sprintf("select COUNT(DISTINCT `Subject Key`)  as num from `Category Bridge`  where `Category Key`=%d  ",
			$this->id
		);
		$res=mysql_query($sql);
		$num=0;
		if ($row=mysql_fetch_assoc($res)) {
			$num=$row['num'];
		}
		$sql=sprintf("update `Category Dimension` set `Category Number Subjects`=%d where `Category Key`=%d ",
			$num,
			$this->id
		);
		mysql_query($sql);
		// print "$sql\n";
		$this->update_no_assigned_subjects();

	}
	function update_no_assigned_subjects() {
		$children_keys=$this->get_children_keys();
		$no_assigned_subjects=0;
		$assigned_subjects=0;
		$children_no_assigned_subjects=0;
		$children_assigned_subjects=0;
		$total_subjects=0;

		switch ($this->data['Category Subject']) {
		case('Part'):

			$sql=sprintf("select count(*) as num from `Part Warehouse Bridge` where `Warehouse Key`=%d",
				$this->data['Category Warehouse Key']);
			break;
		case('Supplier'):

			$sql=sprintf("select count(*) as num from `Supplier Product Dimension` ");
			break;
		default:
			$table=$this->data['Category Subject'];
			$store=sprintf(" where `%s Store Key`=%d",
				addslashes($this->data['Category Subject']),
				$this->data['Category Store Key']);
			$sql=sprintf("select count(*) as num from `%s Dimension` %s",$table,$store);
			break;
		}



		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$total_subjects=$row['num'];

		}


		$sql=sprintf("select COUNT(`Subject Key`)  as num from `Category Bridge`  where `Category Key`=%d  ",
			$this->id
		);
		$res=mysql_query($sql);
		$assigned_subjects=0;
		if ($row=mysql_fetch_assoc($res)) {
			$assigned_subjects=$row['num'];
		}
		$no_assigned_subjects=$total_subjects-$assigned_subjects;


		if (count($children_keys)>0) {


			$sql=sprintf("select COUNT(DISTINCT `Subject Key`)  as num from `Category Bridge`  where `Category Key` in (%s)  ",
				join(',',$children_keys)
			);
			$res=mysql_query($sql);
			$children_assigned_subjects=0;
			if ($row=mysql_fetch_assoc($res)) {
				$children_assigned_subjects=$row['num'];
			}
			$children_no_assigned_subjects=$total_subjects-$children_assigned_subjects;





		}

		$sql=sprintf("update `Category Dimension` set  `Category Subjects Not Assigned`=%d,  `Category Children Subjects Not Assigned`=%d,`Category Children Subjects Assigned`=%d where `Category Key`=%d ",
			$no_assigned_subjects,
			$children_no_assigned_subjects,
			$children_assigned_subjects,
			$this->id
		);
		mysql_query($sql);
		$sql=sprintf("update `Category Dimension` set  `Category Subjects Not Assigned`=%d, where `Category Root Key`=%d ",
			$no_assigned_subjects,
			$this->data['Category Root Key']
		);



		mysql_query($sql);

	}



	function update_sales_old() {
		$sql="select * from `Store Dimension`";
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$this->update_sales_store($row['Store Key']);
		}
		mysql_free_result($result);
	}



	function update_product_data_old() {
		$sql="select * from `Store Dimension`";
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$this->update_store_product_data($row['Store Key']);
		}
		mysql_free_result($result);
	}

	function update_sales_store_old($store_key) {
		// print_r($this->data);

		if ($this->data['Category Subject']!='Product')
			return;


		$on_sale_days=0;

		$sql=sprintf("select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P left join `Category Bridge` as B on (B.`Subject Key`=P.`Product ID`)  where `Subject`='Product' and `Category Key`=%d and `Product Store Key`=%d",$this->id,$store_key);
		//print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from=strtotime($row['ffrom']);
			$_from=date("Y-m-d H:i:s",$from);
			if ($row['for_sale']>0) {
				$to=strtotime('today');
				$_to=date("Y-m-d H:i:s");
			} else {
				$to=strtotime($row['tto']);
				$_to=date("Y-m-d H:i:s",$to);
			}
			$on_sale_days=($to-$from)/ (60 * 60 * 24);

			if ($row['prods']==0)
				$on_sale_days=0;

		}

		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact` OTF left join `Product History Dimension` PH on (PH.`Product Key`=OTF.`Product Key`)  left join `Category Bridge` B  on  (B.`Subject Key`=PH.`Product ID`)   where `Subject`='Product' and  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')  and  `Category Key`=%d and `Store Key`=%d",$this->id,$store_key);
		//print $sql;
		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF   left join `Product History Dimension` PH on (PH.`Product Key`=OTF.`Product Key`)   left join `Category Bridge` B  on  (B.`Subject Key`=PH.`Product ID`)  where `Subject`='Product' and `Category Key`=%d and `Store Key`=%d",$this->id,$store_key);


		//print "$sql\n\n";
		// exit;
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Category Total Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Category Total Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Category Total Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Category Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Category Total Quantity Ordered']=$row['ordered'];
			$this->data['Product Category Total Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Category Total Quantity Delivered']=$row['delivered'];
			$this->data['Product Category Total Days On Sale']=$on_sale_days;
			$this->data['Product Category Valid From']=$_from;
			$this->data['Product Category Valid To']=$_to;
			$this->data['Product Category Total Customers']=$row['customers'];
			$this->data['Product Category Total Invoices']=$row['invoices'];
			$this->data['Product Category Total Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Category Dimension` set `Product Category Total Invoiced Gross Amount`=%s,`Product Category Total Invoiced Discount Amount`=%s,`Product Category Total Invoiced Amount`=%s,`Product Category Total Profit`=%s, `Product Category Total Quantity Ordered`=%s , `Product Category Total Quantity Invoiced`=%s,`Product Category Total Quantity Delivered`=%s ,`Product Category Total Days On Sale`=%f ,`Product Category Valid From`=%s,`Product Category Valid To`=%s ,`Product Category Total Customers`=%d,`Product Category Total Invoices`=%d,`Product Category Total Pending Orders`=%d  where `Product Category Key`=%d and `Product Category Store Key`=%d  "
				,prepare_mysql($this->data['Product Category Total Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Category Total Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Category Total Invoiced Amount'])

				,prepare_mysql($this->data['Product Category Total Profit'])
				,prepare_mysql($this->data['Product Category Total Quantity Ordered'])
				,prepare_mysql($this->data['Product Category Total Quantity Invoiced'])
				,prepare_mysql($this->data['Product Category Total Quantity Delivered'])
				,$on_sale_days
				,prepare_mysql($this->data['Product Category Valid From'])
				,prepare_mysql($this->data['Product Category Valid To'])
				,$this->data['Product Category Total Customers']
				,$this->data['Product Category Total Invoices']
				,$this->data['Product Category Total Pending Orders']
				,$this->id
				,$store_key
			);
			//  print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}
		// days on sale


		return;

		$on_sale_days=0;



		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from  `Product Dimension` as P left join `Product Category Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Category Key`=".$this->id;
		// print "$sql\n\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['prods']==0)
				$on_sale_days=0;
			else {


				if ($row['for_sale']>0)
					$to=strtotime('today');
				else
					$to=strtotime($row['to']);
				// print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
				// print "*** T:$to   ".strtotime('today -1 year')."  \n";
				if ($to>strtotime('today -1 year')) {
					//print "caca";
					$from=strtotime($row['ffrom']);
					if ($from<strtotime('today -1 year'))
						$from=strtotime('today -1 year');

					//     print "*** T:$to F:$from\n";
					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else {
					//   print "pipi";
					$on_sale_days=0;

				}
			}
		}



		//$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Store 1 Year Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Store 1 Year Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Store 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Store 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Store 1 Year Acc Quantity Ordered']=$row['ordered'];
			$this->data['Store 1 Year Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Store 1 Year Acc Quantity Delivered']=$row['delivered'];
			$this->data['Store 1 Year Acc Customers']=$row['customers'];
			$this->data['Store 1 Year Acc Invoices']=$row['invoices'];
			$this->data['Store 1 Year Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Store Dimension` set `Store 1 Year Acc Invoiced Gross Amount`=%s,`Store 1 Year Acc Invoiced Discount Amount`=%s,`Store 1 Year Acc Invoiced Amount`=%s,`Store 1 Year Acc Profit`=%s, `Store 1 Year Acc Quantity Ordered`=%s , `Store 1 Year Acc Quantity Invoiced`=%s,`Store 1 Year Acc Quantity Delivered`=%s ,`Store 1 Year Acc Days On Sale`=%f ,`Store 1 Year Acc Customers`=%d,`Store 1 Year Acc Invoices`=%d,`Store 1 Year Acc Pending Orders`=%d   where `Store Key`=%d "
				,prepare_mysql($this->data['Store 1 Year Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Store 1 Year Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Store 1 Year Acc Invoiced Amount'])

				,prepare_mysql($this->data['Store 1 Year Acc Profit'])
				,prepare_mysql($this->data['Store 1 Year Acc Quantity Ordered'])
				,prepare_mysql($this->data['Store 1 Year Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Store 1 Year Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Store 1 Year Acc Customers']
				,$this->data['Store 1 Year Acc Invoices']
				,$this->data['Store 1 Year Acc Pending Orders']
				,$this->id
			);
			//  print "$sql\n";


			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}
		// exit;
		$on_sale_days=0;


		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['prods']==0)
				$on_sale_days=0;
			else {


				if ($row['for_sale']>0)
					$to=strtotime('today');
				else
					$to=strtotime($row['to']);
				if ($to>strtotime('today -3 month')) {

					$from=strtotime($row['ffrom']);
					if ($from<strtotime('today -3 month'))
						$from=strtotime('today -3 month');


					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else
					$on_sale_days=0;
			}
		}

		//$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));



		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Store 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Store 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Store 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Store 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Store 1 Quarter Acc Quantity Ordered']=$row['ordered'];
			$this->data['Store 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Store 1 Quarter Acc Quantity Delivered']=$row['delivered'];
			$this->data['Store 1 Quarter Acc Customers']=$row['customers'];
			$this->data['Store 1 Quarter Acc Invoices']=$row['invoices'];
			$this->data['Store 1 Quarter Acc Pending Orders']=$pending_orders;


			$sql=sprintf("update `Store Dimension` set `Store 1 Quarter Acc Invoiced Gross Amount`=%s,`Store 1 Quarter Acc Invoiced Discount Amount`=%s,`Store 1 Quarter Acc Invoiced Amount`=%s,`Store 1 Quarter Acc Profit`=%s, `Store 1 Quarter Acc Quantity Ordered`=%s , `Store 1 Quarter Acc Quantity Invoiced`=%s,`Store 1 Quarter Acc Quantity Delivered`=%s  ,`Store 1 Quarter Acc Days On Sale`=%f ,`Store 1 Quarter Acc Customers`=%d,`Store 1 Quarter Acc Invoices`=%d,`Store 1 Quarter Acc Pending Orders`=%d   where `Store Key`=%d "
				,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Amount'])

				,prepare_mysql($this->data['Store 1 Quarter Acc Profit'])
				,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Ordered'])
				,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Store 1 Quarter Acc Customers']
				,$this->data['Store 1 Quarter Acc Invoices']
				,$this->data['Store 1 Quarter Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}

		$on_sale_days=0;

		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['prods']==0)
				$on_sale_days=0;
			else {


				if ($row['for_sale']>0)
					$to=strtotime('today');
				else
					$to=strtotime($row['to']);
				if ($to>strtotime('today -1 month')) {

					$from=strtotime($row['ffrom']);
					if ($from<strtotime('today -1 month'))
						$from=strtotime('today -1 month');


					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else
					$on_sale_days=0;
			}
		}

		//$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));



		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Store 1 Month Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Store 1 Month Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Store 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Store 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Store 1 Month Acc Quantity Ordered']=$row['ordered'];
			$this->data['Store 1 Month Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Store 1 Month Acc Quantity Delivered']=$row['delivered'];
			$this->data['Store 1 Month Acc Customers']=$row['customers'];
			$this->data['Store 1 Month Acc Invoices']=$row['invoices'];
			$this->data['Store 1 Month Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Store Dimension` set `Store 1 Month Acc Invoiced Gross Amount`=%s,`Store 1 Month Acc Invoiced Discount Amount`=%s,`Store 1 Month Acc Invoiced Amount`=%s,`Store 1 Month Acc Profit`=%s, `Store 1 Month Acc Quantity Ordered`=%s , `Store 1 Month Acc Quantity Invoiced`=%s,`Store 1 Month Acc Quantity Delivered`=%s  ,`Store 1 Month Acc Days On Sale`=%f ,`Store 1 Month Acc Customers`=%d,`Store 1 Month Acc Invoices`=%d,`Store 1 Month Acc Pending Orders`=%d   where `Store Key`=%d "
				,prepare_mysql($this->data['Store 1 Month Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Store 1 Month Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Store 1 Month Acc Invoiced Amount'])

				,prepare_mysql($this->data['Store 1 Month Acc Profit'])
				,prepare_mysql($this->data['Store 1 Month Acc Quantity Ordered'])
				,prepare_mysql($this->data['Store 1 Month Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Store 1 Month Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Store 1 Month Acc Customers']
				,$this->data['Store 1 Month Acc Invoices']
				,$this->data['Store 1 Month Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}

		$on_sale_days=0;
		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Store Key`=".$this->id;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['prods']==0)
				$on_sale_days=0;
			else {


				if ($row['for_sale']>0)
					$to=strtotime('today');
				else
					$to=strtotime($row['to']);
				if ($to>strtotime('today -1 week')) {

					$from=strtotime($row['ffrom']);
					if ($from<strtotime('today -1 week'))
						$from=strtotime('today -1 week');


					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else
					$on_sale_days=0;
			}
		}


		//$sql="select sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Store Key`=".$this->id;

		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross   ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced   from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
		// print $sql;
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Store 1 Week Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Store 1 Week Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Store 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data['Store 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Store 1 Week Acc Quantity Ordered']=$row['ordered'];
			$this->data['Store 1 Week Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Store 1 Week Acc Quantity Delivered']=$row['delivered'];

			$this->data['Store 1 Week Acc Customers']=$row['customers'];
			$this->data['Store 1 Week Acc Invoices']=$row['invoices'];
			$this->data['Store 1 Week Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Store Dimension` set `Store 1 Week Acc Invoiced Gross Amount`=%s,`Store 1 Week Acc Invoiced Discount Amount`=%s,`Store 1 Week Acc Invoiced Amount`=%s,`Store 1 Week Acc Profit`=%s, `Store 1 Week Acc Quantity Ordered`=%s , `Store 1 Week Acc Quantity Invoiced`=%s,`Store 1 Week Acc Quantity Delivered`=%s ,`Store 1 Week Acc Days On Sale`=%f  ,`Store 1 Week Acc Customers`=%d,`Store 1 Week Acc Invoices`=%d,`Store 1 Week Acc Pending Orders`=%d   where `Store Key`=%d "
				,prepare_mysql($this->data['Store 1 Week Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Store 1 Week Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Store 1 Week Acc Invoiced Amount'])
				,prepare_mysql($this->data['Store 1 Week Acc Profit'])
				,prepare_mysql($this->data['Store 1 Week Acc Quantity Ordered'])
				,prepare_mysql($this->data['Store 1 Week Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Store 1 Week Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Store 1 Week Acc Customers']
				,$this->data['Store 1 Week Acc Invoices']
				,$this->data['Store 1 Week Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");

		}



	}


	function update_store_product_data_old($store_key) {
		$in_process=0;
		$public_sale=0;
		$private_sale=0;
		$discontinued=0;
		$not_for_sale=0;
		$sale_unknown=0;
		$availability_optimal=0;
		$availability_low=0;
		$availability_critical=0;
		$availability_outofstock=0;
		$availability_unknown=0;
		$availability_surplus=0;
		if ($this->data['Category Subject']!='Product')
			return;

		$sql=sprintf("select sum(if(`Product Stage`='In process',1,0)) as in_process, sum(if(`Product Availability Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as public_sale,sum(if(`Product Sales Type`='Private Sale',1,0)) as private_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension`left join `Category Bridge` on (`Subject Key`=`Product ID`)  where `Subject`='Product' and   `Product Store Key`=%d and `Category Key`=%d",$store_key,$this->id);


		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$in_process=$row['in_process'];
			$public_sale=$row['public_sale'];
			$private_sale=$row['private_sale'];
			$discontinued=$row['discontinued'];
			$not_for_sale=$row['not_for_sale'];
			$sale_unknown=0;
			$availability_optimal=$row['availability_optimal'];
			$availability_low=$row['availability_low'];
			$availability_critical=$row['availability_critical'];
			$availability_outofstock=$row['availability_outofstock'];
			$availability_unknown=$row['availability_unknown'];
			$availability_surplus=$row['availability_surplus'];



		}

		$sql=sprintf("update `Product Category Dimension` set `Product Category In Process Products`=%d,`Product Category For Sale Products`=%d ,`Product Category Discontinued Products`=%d ,`Product Category Not For Sale Products`=%d ,`Product Category Unknown Sales State Products`=%d, `Product Category Optimal Availability Products`=%d , `Product Category Low Availability Products`=%d ,`Product Category Critical Availability Products`=%d ,`Product Category Out Of Stock Products`=%d,`Product Category Unknown Stock Products`=%d ,`Product Category Surplus Availability Products`=%d where `Product Category Store Key`=%d and `Product Category Key`=%d  ",
			$in_process,
			$public_sale,
			$private_sale,
			$discontinued,
			$not_for_sale,
			$sale_unknown,
			$availability_optimal,
			$availability_low,
			$availability_critical,
			$availability_outofstock,
			$availability_unknown,
			$availability_surplus,
			$store_key,
			$this->id
		);
		//print "$sql\n";exit;
		mysql_query($sql);

		$this->get_data('id',$this->id);

	}


	function update_subjects_data() {

		if ($this->data['Category Branch Type']=='Root' or !$this->update_subjects_data) {
			return;

		}


		//print "updatiog cat ".$this->id."   \n";
		$this->update_up_today();
		$this->update_last_period();
		$this->update_last_interval();
	}


	function update_up_today() {

		switch ($this->data['Category Subject']) {
		case 'Invoice':
			$this->update_invoice_category_up_today_sales();
			break;
		case('Supplier'):
			$this->update_supplier_category_up_today_sales();
			break;
		case('Part'):
			$this->update_part_category_up_today_sales();
			break;

		default:

			break;
		}

	}

	function update_last_period() {

		switch ($this->data['Category Subject']) {
		case 'Invoice':
			$this->update_invoice_category_last_period_sales();
			break;
		case('Supplier'):
			$this->update_supplier_category_last_period_sales();
			break;
		case('Part'):
			$this->update_part_category_last_period_sales();
			break;
		default:

			break;
		}

	}


	function update_last_interval() {

		switch ($this->data['Category Subject']) {
		case 'Invoice':
			$this->update_invoice_category_interval_sales();
			break;
		case('Supplier'):
			$this->update_supplier_category_interval_sales();
			break;
		case('Part'):
			$this->update_part_category_interval_sales();
			break;

		default:

			break;
		}

	}



	function update_supplier_category_up_today_sales() {
		$this->update_supplier_category_sales('Today');
		$this->update_supplier_category_sales('Week To Day');
		$this->update_supplier_category_sales('Month To Day');
		$this->update_supplier_category_sales('Year To Day');
	}

	function update_supplier_category_last_period_sales() {

		$this->update_supplier_category_sales('Yesterday');
		$this->update_supplier_category_sales('Last Week');
		$this->update_supplier_category_sales('Last Month');
	}


	function update_supplier_category_interval_sales() {
		$this->update_supplier_category_sales('Total');
		$this->update_supplier_category_sales('3 Year');
		$this->update_supplier_category_sales('1 Year');
		$this->update_supplier_category_sales('6 Month');
		$this->update_supplier_category_sales('1 Quarter');
		$this->update_supplier_category_sales('1 Month');
		$this->update_supplier_category_sales('10 Day');
		$this->update_supplier_category_sales('1 Week');
	}

	function update_invoice_category_up_today_sales() {
		$this->update_invoice_category_sales('Today');
		$this->update_invoice_category_sales('Week To Day');
		$this->update_invoice_category_sales('Month To Day');
		$this->update_invoice_category_sales('Year To Day');
	}

	function update_invoice_category_last_period_sales() {

		$this->update_invoice_category_sales('Yesterday');
		$this->update_invoice_category_sales('Last Week');
		$this->update_invoice_category_sales('Last Month');
	}


	function update_invoice_category_interval_sales() {
		$this->update_invoice_category_sales('Total');
		$this->update_invoice_category_sales('3 Year');
		$this->update_invoice_category_sales('1 Year');
		$this->update_invoice_category_sales('6 Month');
		$this->update_invoice_category_sales('1 Quarter');
		$this->update_invoice_category_sales('1 Month');
		$this->update_invoice_category_sales('10 Day');
		$this->update_invoice_category_sales('1 Week');
	}


	function update_part_category_up_today_sales() {
		$this->update_part_category_sales('Today');
		$this->update_part_category_sales('Week To Day');
		$this->update_part_category_sales('Month To Day');
		$this->update_part_category_sales('Year To Day');
	}

	function update_part_category_last_period_sales() {

		$this->update_part_category_sales('Yesterday');
		$this->update_part_category_sales('Last Week');
		$this->update_part_category_sales('Last Month');
	}


	function update_part_category_interval_sales() {
		$this->update_part_category_sales('Total');
		$this->update_part_category_sales('3 Year');
		$this->update_part_category_sales('1 Year');
		$this->update_part_category_sales('6 Month');
		$this->update_part_category_sales('1 Quarter');
		$this->update_part_category_sales('1 Month');
		$this->update_part_category_sales('10 Day');
		$this->update_part_category_sales('1 Week');
	}




	function update_part_category_sales_old($interval) {



		$to_date='';
		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);
		setlocale(LC_ALL, 'en_GB');

		//   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

		$this->data["Part Category $db_interval Acc Required"]=0;
		$this->data["Part Category $db_interval Acc Provided"]=0;
		$this->data["Part Category $db_interval Acc Given"]=0;
		$this->data["Part Category $db_interval Acc Sold Amount"]=0;
		$this->data["Part Category $db_interval Acc Profit"]=0;
		$this->data["Part Category $db_interval Acc Profit After Storing"]=0;
		$this->data["Part Category $db_interval Acc Sold"]=0;
		$this->data["Part Category $db_interval Acc Margin"]=0;


		$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing from `Inventory Transaction Fact` ITF left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Category Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//   print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Part Category $db_interval Acc Profit"]=$row['profit'];
			$this->data["Part Category $db_interval Acc Profit After Storing"]=$this->data["Part Category $db_interval Acc Profit"]-$row['cost_storing'];

		}


		$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part') where `Inventory Transaction Type`='In'  and `Category Key`=%d  %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


			$this->data["Part Category $db_interval Acc Acquired"]=$row['bought'];

		}


		$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part') where `Inventory Transaction Type`='Sale' and `Category Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Part Category $db_interval Acc Sold Amount"]=$row['sold_amount'];
			$this->data["Part Category $db_interval Acc Sold"]=$row['sold'];
			$this->data["Part Category $db_interval Acc Provided"]=-1.0*$row['dispatched'];
			$this->data["Part Category $db_interval Acc Required"]=$row['required'];
			$this->data["Part Category $db_interval Acc Given"]=$row['given'];

		}

		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part') where `Inventory Transaction Type`='Broken' and `Category Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Part Category $db_interval Acc Broken"]=-1.*$row['broken'];

		}


		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part') where `Inventory Transaction Type`='Lost' and `Category Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Part Category $db_interval Acc Lost"]=-1.*$row['lost'];

		}






		if ($this->data["Part Category $db_interval Acc Sold Amount"]!=0)
			$margin=$this->data["Part Category $db_interval Acc Profit After Storing"]/$this->data["Part Category $db_interval Acc Sold Amount"];
		else
			$margin=0;
		$this->data["Part Category $db_interval Acc Margin"]=$margin;


		$sql=sprintf("update `Part Category Dimension` set
                     `Part Category $db_interval Acc Required`=%f ,
                     `Part Category $db_interval Acc Provided`=%f,
                     `Part Category $db_interval Acc Given`=%f ,
                     `Part Category $db_interval Acc Sold Amount`=%f ,
                     `Part Category $db_interval Acc Profit`=%f ,
                     `Part Category $db_interval Acc Profit After Storing`=%f ,
                     `Part Category $db_interval Acc Sold`=%f ,
                     `Part Category $db_interval Acc Margin`=%s where
                     `Part Category Key`=%d "
			,$this->data["Part Category $db_interval Acc Required"]
			,$this->data["Part Category $db_interval Acc Provided"]
			,$this->data["Part Category $db_interval Acc Given"]
			,$this->data["Part Category $db_interval Acc Sold Amount"]
			,$this->data["Part Category $db_interval Acc Profit"]
			,$this->data["Part Category $db_interval Acc Profit After Storing"]
			,$this->data["Part Category $db_interval Acc Sold"]
			,$this->data["Part Category $db_interval Acc Margin"]

			,$this->id);

		mysql_query($sql);

		// print "$sql\n";




		if ($from_date_1yb) {

			//    prepare_mysql($from_date_1yb),
			//                  prepare_mysql($to_1yb)





		}


	}

	function update_part_category_sales($interval) {

		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);



		$sql=sprintf("select 	sum(`Part $db_interval Acc Profit`) as profit,
								sum(`Part $db_interval Acc Profit After Storing`) as profit_after_storing,
								sum(`Part $db_interval Acc Acquired`) as bought,
								sum(`Part $db_interval Acc Sold Amount`) as sold_amount,
								sum(`Part $db_interval Acc Sold`) as sold,
								sum(`Part $db_interval Acc Provided`) as dispatched,
								sum(`Part $db_interval Acc Required`) as required,
								sum(`Part $db_interval Acc Given`) as given,
								sum(`Part $db_interval Acc Broken`) as broken,
								sum(`Part $db_interval Acc Lost`) as lost

								from `Part Dimension` ITF left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Category Key`=%d" ,
			$this->id);
		$result=mysql_query($sql);
		//print $sql;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Part Category $db_interval Acc Profit"]=$row['profit'];
			$this->data["Part Category $db_interval Acc Profit After Storing"]=$row['profit_after_storing'];
			$this->data["Part Category $db_interval Acc Acquired"]=$row['bought'];
			$this->data["Part Category $db_interval Acc Sold Amount"]=$row['sold_amount'];
			$this->data["Part Category $db_interval Acc Sold"]=$row['sold'];
			$this->data["Part Category $db_interval Acc Provided"]=-1.0*$row['dispatched'];
			$this->data["Part Category $db_interval Acc Required"]=$row['required'];
			$this->data["Part Category $db_interval Acc Given"]=$row['given'];
			$this->data["Part Category $db_interval Acc Broken"]=$row['broken'];
			$this->data["Part Category $db_interval Acc Lost"]=$row['lost'];

		}


		if ($this->data["Part Category $db_interval Acc Sold Amount"]!=0)
			$margin=$this->data["Part Category $db_interval Acc Profit After Storing"]/$this->data["Part Category $db_interval Acc Sold Amount"];
		else
			$margin=0;
		$this->data["Part Category $db_interval Acc Margin"]=$margin;


		$sql=sprintf("update `Part Category Dimension` set
                     `Part Category $db_interval Acc Required`=%f ,
                     `Part Category $db_interval Acc Provided`=%f,
                     `Part Category $db_interval Acc Given`=%f ,
                     `Part Category $db_interval Acc Sold Amount`=%f ,
                     `Part Category $db_interval Acc Profit`=%f ,
                     `Part Category $db_interval Acc Profit After Storing`=%f ,
                     `Part Category $db_interval Acc Sold`=%f ,
                     `Part Category $db_interval Acc Margin`=%s
                     `Part Category $db_interval Acc Acquired`=%s
                     `Part Category $db_interval Acc Broken`=%s
                     `Part Category $db_interval Acc Lost`=%s
                      where
                     `Part Category Key`=%d "
			,$this->data["Part Category $db_interval Acc Required"]
			,$this->data["Part Category $db_interval Acc Provided"]
			,$this->data["Part Category $db_interval Acc Given"]
			,$this->data["Part Category $db_interval Acc Sold Amount"]
			,$this->data["Part Category $db_interval Acc Profit"]
			,$this->data["Part Category $db_interval Acc Profit After Storing"]
			,$this->data["Part Category $db_interval Acc Sold"]
			,$this->data["Part Category $db_interval Acc Margin"]
			,$this->data["Part Category $db_interval Acc Acquired"]
			,$this->data["Part Category $db_interval Acc Broken"]
			,$this->data["Part Category $db_interval Acc Lost"]

			,$this->id);

		mysql_query($sql);

		if ($from_date_1yb) {

			$sql=sprintf("select 	sum(`Part $db_interval Acc 1YB Profit`) as profit,
								sum(`Part $db_interval Acc 1YB Profit After Storing`) as profit_after_storing,
								sum(`Part $db_interval Acc 1YB Acquired`) as bought,
								sum(`Part $db_interval Acc 1YB Sold Amount`) as sold_amount,
								sum(`Part $db_interval Acc 1YB Sold`) as sold,
								sum(`Part $db_interval Acc 1YB Provided`) as dispatched,
								sum(`Part $db_interval Acc 1YB Required`) as required,
								sum(`Part $db_interval Acc 1YB Given`) as given,
								sum(`Part $db_interval Acc 1YB Broken`) as broken,
								sum(`Part $db_interval Acc 1YB Lost`) as lost

								from `Part Dimension` ITF left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Category Key`=%d" ,
				$this->id);
			$result=mysql_query($sql);
			//print $sql;
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Part Category $db_interval Acc 1YB Profit"]=$row['profit'];
				$this->data["Part Category $db_interval Acc 1YB Profit After Storing"]=$row['profit_after_storing'];
				$this->data["Part Category $db_interval Acc 1YB Acquired"]=$row['bought'];
				$this->data["Part Category $db_interval Acc 1YB Sold Amount"]=$row['sold_amount'];
				$this->data["Part Category $db_interval Acc 1YB Sold"]=$row['sold'];
				$this->data["Part Category $db_interval Acc 1YB Provided"]=-1.0*$row['dispatched'];
				$this->data["Part Category $db_interval Acc 1YB Required"]=$row['required'];
				$this->data["Part Category $db_interval Acc 1YB Given"]=$row['given'];
				$this->data["Part Category $db_interval Acc 1YB Broken"]=$row['broken'];
				$this->data["Part Category $db_interval Acc 1YB Lost"]=$row['lost'];

			}


			if ($this->data["Part Category $db_interval Acc 1YB Sold Amount"]!=0)
				$margin=$this->data["Part Category $db_interval Acc 1YB Profit After Storing"]/$this->data["Part Category $db_interval Acc 1YB Sold Amount"];
			else
				$margin=0;
			$this->data["Part Category $db_interval Acc 1YB Margin"]=$margin;


			$sql=sprintf("update `Part Category Dimension` set
                     `Part Category $db_interval Acc 1YB Required`=%f ,
                     `Part Category $db_interval Acc 1YB Provided`=%f,
                     `Part Category $db_interval Acc 1YB Given`=%f ,
                     `Part Category $db_interval Acc 1YB Sold Amount`=%f ,
                     `Part Category $db_interval Acc 1YB Profit`=%f ,
                     `Part Category $db_interval Acc 1YB Profit After Storing`=%f ,
                     `Part Category $db_interval Acc 1YB Sold`=%f ,
                     `Part Category $db_interval Acc 1YB Margin`=%s
                     `Part Category $db_interval Acc 1YB Acquired`=%s
                     `Part Category $db_interval Acc 1YB Broken`=%s
                     `Part Category $db_interval Acc 1YB Lost`=%s
                      where
                     `Part Category Key`=%d "
				,$this->data["Part Category $db_interval Acc 1YB Required"]
				,$this->data["Part Category $db_interval Acc 1YB Provided"]
				,$this->data["Part Category $db_interval Acc 1YB Given"]
				,$this->data["Part Category $db_interval Acc 1YB Sold Amount"]
				,$this->data["Part Category $db_interval Acc 1YB Profit"]
				,$this->data["Part Category $db_interval Acc 1YB Profit After Storing"]
				,$this->data["Part Category $db_interval Acc 1YB Sold"]
				,$this->data["Part Category $db_interval Acc 1YB Margin"]
				,$this->data["Part Category $db_interval Acc 1YB Acquired"]
				,$this->data["Part Category $db_interval Acc 1YB Broken"]
				,$this->data["Part Category $db_interval Acc 1YB Lost"]

				,$this->id);

			mysql_query($sql);



			$this->data["Part Category $db_interval Acc 1YD Required"]=($this->data["Part Category $db_interval Acc 1YB Required"]==0?0:($this->data["Part Category $db_interval Acc Required"]-$this->data["Part Category $db_interval Acc 1YB Required"])/$this->data["Part Category $db_interval Acc 1YB Required"]);
			$this->data["Part Category $db_interval Acc 1YD Provided"]=($this->data["Part Category $db_interval Acc 1YB Provided"]==0?0:($this->data["Part Category $db_interval Acc Provided"]-$this->data["Part Category $db_interval Acc 1YB Provided"])/$this->data["Part Category $db_interval Acc 1YB Provided"]);
			$this->data["Part Category $db_interval Acc 1YD Given"]=($this->data["Part Category $db_interval Acc 1YB Given"]==0?0:($this->data["Part Category $db_interval Acc Given"]-$this->data["Part Category $db_interval Acc 1YB Given"])/$this->data["Part Category $db_interval Acc 1YB Given"]);
			$this->data["Part Category $db_interval Acc 1YD Sold Amount"]=($this->data["Part Category $db_interval Acc 1YB Sold Amount"]==0?0:($this->data["Part Category $db_interval Acc Sold Amount"]-$this->data["Part Category $db_interval Acc 1YB Sold Amount"])/$this->data["Part Category $db_interval Acc 1YB Sold Amount"]);
			$this->data["Part Category $db_interval Acc 1YD Profit"]=($this->data["Part Category $db_interval Acc 1YB Profit"]==0?0:($this->data["Part Category $db_interval Acc Profit"]-$this->data["Part Category $db_interval Acc 1YB Profit"])/$this->data["Part Category $db_interval Acc 1YB Profit"]);
			$this->data["Part Category $db_interval Acc 1YD Profit After Storing"]=($this->data["Part Category $db_interval Acc 1YB Profit After Storing"]==0?0:($this->data["Part Category $db_interval Acc Profit After Storing"]-$this->data["Part Category $db_interval Acc 1YB Profit After Storing"])/$this->data["Part Category $db_interval Acc 1YB Profit After Storing"]);
			$this->data["Part Category $db_interval Acc 1YD Sold"]=($this->data["Part Category $db_interval Acc 1YB Sold"]==0?0:($this->data["Part Category $db_interval Acc Sold"]-$this->data["Part Category $db_interval Acc 1YB Sold"])/$this->data["Part Category $db_interval Acc 1YB Sold"]);
			$this->data["Part Category $db_interval Acc 1YD Margin"]=($this->data["Part Category $db_interval Acc 1YB Margin"]==0?0:($this->data["Part Category $db_interval Acc Margin"]-$this->data["Part Category $db_interval Acc 1YB Margin"])/$this->data["Part Category $db_interval Acc 1YB Margin"]);


			$sql=sprintf("update `Part Category Dimension` set
                     `Part Category $db_interval Acc 1YD Required`=%f ,
                     `Part Category $db_interval Acc 1YD Provided`=%f,
                     `Part Category $db_interval Acc 1YD Given`=%f ,
                     `Part Category $db_interval Acc 1YD Sold Amount`=%f ,
                     `Part Category $db_interval Acc 1YD Profit`=%f ,
                     `Part Category $db_interval Acc 1YD Profit After Storing`=%f ,
                     `Part Category $db_interval Acc 1YD Sold`=%f ,
                     `Part Category $db_interval Acc 1YD Margin`=%s where
                      `Part Category Key`=%d "
				,$this->data["Part Category $db_interval Acc 1YD Required"]
				,$this->data["Part Category $db_interval Acc 1YD Provided"]
				,$this->data["Part Category $db_interval Acc 1YD Given"]
				,$this->data["Part Category $db_interval Acc 1YD Sold Amount"]
				,$this->data["Part Category $db_interval Acc 1YD Profit"]
				,$this->data["Part Category $db_interval Acc 1YD Profit After Storing"]
				,$this->data["Part Category $db_interval Acc 1YD Sold"]
				,$this->data["Part Category $db_interval Acc 1YD Margin"]

				,$this->id);

			mysql_query($sql);
			//print "$sql\n";

		}


	}

	function update_supplier_category_sales($interval) {

		//  print $interval;

		switch ($interval) {




		case 'Last Month':
			$db_interval='Last Month';
			$from_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m')-1,1,date('Y')));
			$to_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),1,date('Y')));

			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
			//  print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
			break;

		case 'Last Week':
			$db_interval='Last Week';


			$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y'),date('W'));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$from_date=date('Y-m-d 00:00:00',strtotime($row['First Day'].' -1 week'));
				$to_date=date('Y-m-d 00:00:00',strtotime($row['First Day']));

			} else {
				return;
			}



			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
			break;

		case 'Yesterday':
			$db_interval='Yesterday';
			$from_date=date('Y-m-d 00:00:00',strtotime('today -1 day'));
			$to_date=date('Y-m-d 00:00:00',strtotime('today'));

			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("today -1 year"));
			break;

		case 'Week To Day':
		case 'wtd':
			$db_interval='Week To Day';

			$from_date=false;
			$from_date_1yb=false;

			$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y'),date('W'));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$from_date=$row['First Day'].' 00:00:00';
				$lapsed_seconds=strtotime('now')-strtotime($from_date);

			} else {
				return;
			}

			$sql=sprintf("select `First Day`  from  kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y')-1,date('W'));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$from_date_1yb=$row['First Day'].' 00:00:00';
			}


			$to_1yb=date('Y-m-d H:i:s',strtotime($from_date_1yb." +$lapsed_seconds seconds"));



			break;
		case 'Today':

			$db_interval='Today';
			$from_date=date('Y-m-d 00:00:00');
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;


		case 'Month To Day':
		case 'mtd':
			$db_interval='Month To Day';
			$from_date=date('Y-m-01 00:00:00');
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case 'Year To Day':
		case 'ytd':
			$db_interval='Year To Day';
			$from_date=date('Y-01-01 00:00:00');
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			//print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
			break;
		case '3 Year':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -3 year"));
			$from_date_1yb=false;
			$to_1yb=false;
			break;
		case '1 Year':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -1 year"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '6 Month':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -6 months"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '1 Quarter':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -3 months"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '1 Month':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -1 month"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '10 Day':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -10 days"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '1 Week':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -1 week"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;

		default:
			return;
			break;
		}




		//   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

		$supplier_category_data["$db_interval Acc Cost"]=0;
		$supplier_category_data["$db_interval Acc Part Sales"]=0;
		$supplier_category_data["$db_interval Acc Profit"]=0;


		$sql=sprintf("select sum(`Supplier $db_interval Acc Parts Cost`) as cost, sum(`Supplier $db_interval Acc Parts Sold Amount`) as sold, sum(`Supplier $db_interval Acc Parts Profit`) as profit   from `Category Bridge` B left join  `Supplier Dimension` I  on ( `Subject Key`=`Supplier Key`)  where `Subject`='Supplier' and `Category Key`=%d " ,
			$this->id


		);
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$supplier_category_data["$db_interval Acc Cost"]=$row["cost"];
			$supplier_category_data["$db_interval Acc Part Sales"]=$row["sold"];
			$supplier_category_data["$db_interval Acc Profit"]=$row["profit"];

		}

		$sql=sprintf("update `Supplier Category Dimension` set
                     `$db_interval Acc Cost`=%.2f,
                     `$db_interval Acc Part Sales`=%.2f,
                     `$db_interval Acc Profit`=%.2f
                     where `Category Key`=%d "
			,$supplier_category_data["$db_interval Acc Cost"]
			,$supplier_category_data["$db_interval Acc Part Sales"]
			,$supplier_category_data["$db_interval Acc Profit"]
			,$this->id
		);

		mysql_query($sql);

		//     print "$sql\n";

		if ($from_date_1yb) {
			$supplier_category_data["$db_interval Acc 1YB Cost"]=0;
			$supplier_category_data["$db_interval Acc 1YB Part Sales"]=0;
			$supplier_category_data["$db_interval Acc 1YB Profit"]=0;

			$sql=sprintf("select sum(`Supplier $db_interval Acc 1YB Parts Cost`) as cost, sum(`Supplier $db_interval Acc 1YB Parts Sold Amount`) as sold, sum(`Supplier $db_interval Acc 1YB Parts Profit`) as profit   from `Category Bridge` B left join  `Supplier Dimension` I  on ( `Subject Key`=`Supplier Key`)  where `Subject`='Supplier' and `Category Key`=%d " ,
				$this->id


			);
			$result=mysql_query($sql);



			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$supplier_category_data["$db_interval Acc 1YB Cost"]=$row["cost"];
				$supplier_category_data["$db_interval Acc 1YB Part Sales"]=$row["sold"];
				$supplier_category_data["$db_interval Acc 1YB Profit"]=$row["profit"];

			}

			$sql=sprintf("update `Supplier Category Dimension` set
                         `$db_interval Acc 1YB Cost`=%.2f,
                         `$db_interval Acc 1YB Part Sales`=%.2f,
                         `$db_interval Acc 1YB Profit`=%.2f
                         where `Category Key`=%d "
				,$supplier_category_data["$db_interval Acc 1YB Cost"]
				,$supplier_category_data["$db_interval Acc 1YB Part Sales"]
				,$supplier_category_data["$db_interval Acc 1YB Profit"]
				,$this->id
			);

			mysql_query($sql);

		}


	}


	function update_invoice_category_sales($interval) {

		$to_date='';

		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);


		//   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

		$invoice_category_data["Invoice Category $db_interval Acc Discount Amount"]=0;
		$invoice_category_data["Invoice Category $db_interval Acc Invoiced Amount"]=0;
		$invoice_category_data["Invoice Category $db_interval Acc Invoices"]=0;
		$invoice_category_data["Invoice Category $db_interval Acc Refunds"]=0;
		$invoice_category_data["Invoice Category $db_interval Acc Paid"]=0;
		$invoice_category_data["Invoice Category $db_interval Acc To Pay"]=0;

		$invoice_category_data["Invoice Category $db_interval Acc Profit"]=0;
		$invoice_category_data["Invoice Category DC $db_interval Acc Invoiced Amount"]=0;
		$invoice_category_data["Invoice Category DC $db_interval Acc Discount Amount"]=0;
		$invoice_category_data["Invoice Category DC $db_interval Acc Profit"]=0;

		$sql=sprintf("select sum(if(`Invoice Paid`='Yes',1,0)) as paid  ,sum(if(`Invoice Paid`='No',1,0)) as to_pay  , sum(if(`Invoice Type`='Invoice',1,0)) as invoices  ,sum(if(`Invoice Type`='Refund',1,0)) as refunds  ,IFNULL(sum(`Invoice Items Discount Amount`),0) as discounts,IFNULL(sum(`Invoice Total Net Amount`),0) net  ,IFNULL(sum(`Invoice Total Profit`),0) as profit ,IFNULL(sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`),0) as dc_discounts,IFNULL(sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`),0) dc_net  ,IFNULL(sum(`Invoice Total Profit`*`Invoice Currency Exchange`),0) as dc_profit from `Category Bridge` B left join  `Invoice Dimension` I  on ( `Subject Key`=`Invoice Key`)  where `Subject`='Invoice' and `Category Key`=%d and  `Invoice Store Key`=%d %s %s" ,
			$this->id,
			$this->data['Category Store Key'],

			($from_date?sprintf('and `Invoice Date`>%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);

		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$invoice_category_data["Invoice Category $db_interval Acc Discount Amount"]=$row["discounts"];
			$invoice_category_data["Invoice Category $db_interval Acc Invoiced Amount"]=$row["net"];
			$invoice_category_data["Invoice Category $db_interval Acc Invoices"]=$row["invoices"];
			$invoice_category_data["Invoice Category $db_interval Acc Refunds"]=$row["refunds"];
			$invoice_category_data["Invoice Category $db_interval Acc Paid"]=$row["paid"];
			$invoice_category_data["Invoice Category $db_interval Acc To Pay"]=$row["to_pay"];

			$invoice_category_data["Invoice Category $db_interval Acc Profit"]=$row["profit"];
			$invoice_category_data["Invoice Category DC $db_interval Acc Invoiced Amount"]=$row["dc_net"];
			$invoice_category_data["Invoice Category DC $db_interval Acc Discount Amount"]=$row["dc_discounts"];
			$invoice_category_data["Invoice Category DC $db_interval Acc Profit"]=$row["dc_profit"];
		}

		$sql=sprintf("update `Invoice Category Dimension` set
                     `Invoice Category $db_interval Acc Discount Amount`=%.2f,
                     `Invoice Category $db_interval Acc Invoiced Amount`=%.2f,
                     `Invoice Category $db_interval Acc Invoices`=%d,
                     `Invoice Category $db_interval Acc Refunds`=%d,
                     `Invoice Category $db_interval Acc Paid`=%d,
                     `Invoice Category $db_interval Acc To Pay`=%d,

                     `Invoice Category $db_interval Acc Profit`=%.2f
                     where `Invoice Category Key`=%d "
			,$invoice_category_data["Invoice Category $db_interval Acc Discount Amount"]
			,$invoice_category_data["Invoice Category $db_interval Acc Invoiced Amount"]
			,$invoice_category_data["Invoice Category $db_interval Acc Invoices"]
			,$invoice_category_data["Invoice Category $db_interval Acc Refunds"]
			,$invoice_category_data["Invoice Category $db_interval Acc Paid"]
			,$invoice_category_data["Invoice Category $db_interval Acc To Pay"]

			,$invoice_category_data["Invoice Category $db_interval Acc Profit"]
			,$this->id
		);

		mysql_query($sql);
		//print "$sql\n\n";
		$sql=sprintf("update `Invoice Category Dimension` set
                     `Invoice Category DC $db_interval Acc Discount Amount`=%.2f,
                     `Invoice Category DC $db_interval Acc Invoiced Amount`=%.2f,
                     `Invoice Category DC $db_interval Acc Profit`=%.2f
                     where `Invoice Category Key`=%d "
			,$invoice_category_data["Invoice Category DC $db_interval Acc Discount Amount"]
			,$invoice_category_data["Invoice Category DC $db_interval Acc Invoiced Amount"]
			,$invoice_category_data["Invoice Category DC $db_interval Acc Profit"]
			,$this->id
		);

		mysql_query($sql);



		if ($from_date_1yb) {
			$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoices"]=0;
			$invoice_category_data["Invoice Category $db_interval Acc 1YB Discount Amount"]=0;
			$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoiced Amount"]=0;
			$invoice_category_data["Invoice Category $db_interval Acc 1YB Profit"]=0;
			$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Discount Amount"]=0;
			$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Invoiced Amount"]=0;
			$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Profit"]=0;

			$sql=sprintf("select count(*) as invoices,sum(`Invoice Items Discount Amount`) as discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) as profit,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) as dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as dc_profit  from `Category Bridge` B left join  `Invoice Dimension` I  on ( `Subject Key`=`Invoice Key`)  where `Subject`='Invoice' and `Category Key`=%d and  `Invoice Store Key`=%d and  `Invoice Date`>%s and `Invoice Date`<%s" ,
				$this->id,
				$this->data['Category Store Key'],
				prepare_mysql($from_date_1yb),
				prepare_mysql($to_1yb)
			);
			// print "$sql\n\n";
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$invoice_category_data["Invoice Category $db_interval Acc 1YB Discount Amount"]=$row["discounts"];
				$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
				$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoices"]=$row["invoices"];
				$invoice_category_data["Invoice Category $db_interval Acc 1YB Profit"]=$row["profit"];
				$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Invoiced Amount"]=$row["dc_net"];
				$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Discount Amount"]=$row["dc_discounts"];
				$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Profit"]=$row["dc_profit"];
			}

			$sql=sprintf("update `Invoice Category Dimension` set
                         `Invoice Category $db_interval Acc 1YB Discount Amount`=%.2f,
                         `Invoice Category $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Invoice Category $db_interval Acc 1YB Invoices`=%.2f,
                         `Invoice Category $db_interval Acc 1YB Profit`=%.2f
                         where `Invoice Category Key`=%d "
				,$invoice_category_data["Invoice Category $db_interval Acc 1YB Discount Amount"]
				,$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoiced Amount"]
				,$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoices"]
				,$invoice_category_data["Invoice Category $db_interval Acc 1YB Profit"]
				,$this->id
			);

			mysql_query($sql);
			// print "$sql\n";
			$sql=sprintf("update `Invoice Category Dimension` set
                         `Invoice Category DC $db_interval Acc 1YB Discount Amount`=%.2f,
                         `Invoice Category DC $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Invoice Category DC $db_interval Acc 1YB Profit`=%.2f
                         where `Invoice Category Key`=%d "
				,$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Discount Amount"]
				,$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Invoiced Amount"]
				,$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Profit"]
				,$this->id
			);
			// print "$sql\n";
			mysql_query($sql);
		}


	}

	function get_other_categories() {
		$sql=sprintf("select * from `Category Dimension` where `Is Category Field Other`='Yes' and `Category Subject`='Customer'");
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {
			$other_data[$row['Category Parent Key']]=$row['Category Key'];
		}

		return $other_data;
	}

	function get_other_value($subject,$subject_key) {
		$other_values='';
		$sql=sprintf("select ifnull(group_concat(Distinct `Other Note`),'') as other_value from `Category Bridge` B left join  `Category Dimension` C on (C.`Category Key`=B.`Category Key`)  where `Subject`=%s and `Subject Key`=%d and `Other Note`!='' and  `Is Category Field Other`='Yes' and `Category Parent Key`=%d",
			prepare_mysql($subject),
			$subject_key,
			$this->id
		);

		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$other_value=$row['other_value'];
		}



		return $other_value;
	}

	function update_other_value($subject_key,$other_value) {

		$sql=sprintf("update `Category Bridge` set `Other Note` =%s where `Category Key`=%d and `Subject`=%s and `Subject Key`=%d  ",
			prepare_mysql($other_value),
			$this->id,
			prepare_mysql($this->data['Category Subject']),
			$subject_key

		);
		//print $sql;
		mysql_query($sql);

	}


	function number_of_children_with_other_value($subject,$subject_key) {

		$number_of_children=0;

		$sql=sprintf(" select  count(Distinct C.`Category Key`) as number_of_children    from `Category Dimension` C left join  `Category Bridge` B on (C.`Category Key`=B.`Category Key`)  where  `Subject`=%s and `Subject Key`=%d and `Is Category Field Other`='Yes' and `Category Parent Key`=%d",
			prepare_mysql($subject),
			$subject_key,
			$this->id);
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$number_of_children=$row['number_of_children'];
		}
		return $number_of_children;
	}

	function get_children_key_is_other_value() {
		$children_key_is_other_value=0;

		$sql=sprintf(" select `Category Key`    from `Category Dimension` C  where   `Is Category Field Other`='Yes' and `Category Parent Key`=%d",


			$this->id);
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$children_key_is_other_value=$row['Category Key'];
		}
		return $children_key_is_other_value;
	}

	function get_children_key_is_other_value_public_edit() {
		$children_key_is_other_value=0;

		$sql=sprintf(" select `Category Key`    from `Category Dimension` C  where   `Is Category Field Other`='Yes' and `Category Parent Key`=%d and `Category Show Public Edit`='Yes'",


			$this->id);
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$children_key_is_other_value=$row['Category Key'];
		}
		return $children_key_is_other_value;
	}

	function get_parent_keys() {
		$parent_keys=array();
		$category_tree_keys=preg_split('/\>/',preg_replace('/\>$/','',$this->data['Category Position']));
		//print_r($this->data['Category Position']);
		array_pop($category_tree_keys);
		return $category_tree_keys;
	}




	function disassociate_subject($subject_key) {

		if (!$this->is_subject_associated($subject_key)) {
			return true;
		}

		//print "Deleting  $subject_key   from  ".$this->id."  \n";
		if ($this->data['Category Branch Type']!='Head') {



			$sql=sprintf("select B.`Category Head Key` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where `Category Root Key`=%d and `Subject`=%s and `Subject Key`=%d and `Category Branch Type`='Head' group by `Category Head Key` ",
				$this->data['Category Root Key'],
				prepare_mysql($this->data['Category Subject']),
				$subject_key
			);
			$res=mysql_query($sql);
			$return_value=false;
			while ($row=mysql_fetch_assoc($res)) {

				$head_category=new Category($row['Category Head Key']);
				if ($head_category->disassociate_subject($subject_key))
					$return_value=true;

			}
			$this->get_data('id',$this->id);
			return $return_value;

		}


		$sql=sprintf("delete from `Category Bridge` where `Category Key`=%d and `Subject`=%s and `Subject Key`=%d",
			$this->id,
			prepare_mysql($this->data['Category Subject']),
			$subject_key
		);
		mysql_query($sql);
		$deleted= mysql_affected_rows();


		if ($deleted) {

			$this->update_number_of_subjects();
			$this->update_subjects_data();


			switch ($this->data['Category Subject']) {
			case('Part'):
				include_once 'class.Part.php';

				$part=new Part($subject_key);
				$abstract=_('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('%05d',$part->sku).'</a> '._('disassociated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
				$details=_('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('%05d',$part->sku).'</a> ('.$part->data['Part XHTML Description'].') '._('disassociated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
				break;
			default:
				$abstract='todo';
				$details='todo';
			}

			if(isset($this->deleting_category)){
				$abstract.=' ('._('Category Deleted').')';
			}
			
			$history_data=array(
				'Direct Object'=>$this->data['Category Subject'],
				'Direct Object Key'=>$subject_key,
				'Action'=>'associated',
				'Preposition'=>'to',
				'Indirect Object'=>'Category '.$this->data['Category Subject'],
				'Indirect Object Key'=>$this->id,
				'History Abstract'=>$abstract,
				'History Details'=>$details
			);


			$history_key=$this->add_history($history_data,$force=false,$post_arg1='Assign');



			foreach ($this->get_parent_keys() as $parent_key) {




				$sql=sprintf("delete from `Category Bridge` where `Category Key`=%d and `Subject`=%s and `Subject Key`=%d",
					$parent_key,
					prepare_mysql($this->data['Category Subject']),
					$subject_key
				);
				mysql_query($sql);

				if (mysql_affected_rows()) {
					$parent_category=new Category($parent_key);

					$parent_category->update_number_of_subjects();
					$parent_category->update_subjects_data();

				}
			}

			// NOTE: no tested
			if ($this->data['Category Subject Multiplicity']=='Yes') {
				$sql=sprintf("select B.`Category Key` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where `Category Root Key`=%d and `Subject`=%s and `Subject Key`=%d and `Category Branch Type`='Head'",
					$this->data['Category Root Key'],
					prepare_mysql($this->data['Category Subject']),
					$subject_key
				);
				$res=mysql_query($sql);
				while ($row=mysql_fetch_assoc($res)) {


					$category=new Category($row['Category Key']);
					foreach ($category->get_parent_keys() as $parent_key) {
						$sql=sprintf("insert into `Category Bridge` values (%d,%s,%d, NULL,%d)",
							$parent_key,
							prepare_mysql($category->data['Category Subject']),
							$subject_key,
							$subject_key
						);
						mysql_query($sql);
						if (mysql_affected_rows()) {
							$parent_category=new Category($parent_key);
							$parent_category->update_number_of_subjects();
							$parent_category->update_subjects_data();

						}
					}

				}

			}




		}

		return $deleted;

	}

	function is_subject_associated($subject_key) {
		$sql=sprintf("select `Subject Key` from `Category Bridge` where `Category Key`=%d and `Subject Key`=%d ",$this->id,$subject_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return true;
		}else {
			return false;
		}
	}

	function associate_subject($subject_key,$force_associate=false,$other_value='') {



		if ($this->data['Category Branch Type']=='Root') {
			$this->msg=_("Subject can't be associated with category").' (Node is Root)';
			return false;
		}




		if ($this->is_subject_associated($subject_key)) {
			return true;
		}


		//print "Adding $subject_key to ".$this->id."\n";

		if ($this->data['Category Subject Multiplicity']=='Yes' or $force_associate) {


			$sql=sprintf("insert into `Category Bridge` values (%d,%s,%d,%s,%d)",
				$this->id,
				prepare_mysql($this->data['Category Subject']),
				$subject_key,
				prepare_mysql($other_value),
				$this->id
			);
			mysql_query($sql);
			//print $sql;
			$inserted= mysql_affected_rows();



			if ($inserted) {
				$this->update_number_of_subjects();
				$this->update_subjects_data();


				switch ($this->data['Category Subject']) {
				case('Part'):
					include_once 'class.Part.php';

					$part=new Part($subject_key);
					$abstract=_('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('05%d',$part->sku).'</a> '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
					$details=_('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('05%d',$part->sku).'</a> ('.$part->data['Part XHTML Description'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
					break;
				default:
					$abstract='todo';
					$details='todo';
				}


				$history_data=array(
					'Direct Object'=>$this->data['Category Subject'],
					'Direct Object Key'=>$subject_key,
					'Action'=>'associated',
					'Preposition'=>'to',
					'Indirect Object'=>'Category '.$this->data['Category Subject'],
					'Indirect Object Key'=>$this->id,
					'History Abstract'=>$abstract,
					'History Details'=>$details
				);


				$history_key=$this->add_history($history_data,$force=false,$post_arg1='Assign');


				foreach ($this->get_parent_keys() as $parent_key) {
					$sql=sprintf("insert into `Category Bridge` values (%d,%s,%d, NULL,%d)",
						$parent_key,
						prepare_mysql($this->data['Category Subject']),
						$subject_key,
						$this->id
					);
					mysql_query($sql);
					if (mysql_affected_rows()) {
						$parent_category=new Category($parent_key);
						$parent_category->update_number_of_subjects();
						$parent_category->update_subjects_data();

					}
				}

				return true;
			}
			else {
				$this->msg=_("Subject can't be associated with category");
				return false;
			}

		}
		else {


			$parents_where='';
			$parent_keys=$this->get_parent_keys();



			$found=false;
			$sql=sprintf("select B.`Category Key` from `Category Bridge` B left join `Category Dimension` C    on  (C.`Category Key`=B.`Category Key`)   where  `Category Branch Type`='Head' and `Category Root Key`=%d  and B.`Category Key`!=%d and `Subject`=%s and `Subject Key`=%d",
				$this->data['Category Root Key'],
				$this->id,
				prepare_mysql($this->data['Category Subject']),
				$subject_key
			);
			$res=mysql_query($sql);

			while ($row=mysql_fetch_assoc($res)) {



				$other_category=new Category($row['Category Key']);
				//print "delete $subject_key from  ".$row['Category Key']." ";
				$other_category->disassociate_subject($subject_key);


			}

			return $this->associate_subject($subject_key,true,$other_value);





		}

	}



	function post_add_history($history_key,$type=false) {

		if (!$type) {
			$type='Change';
		}

		switch ($this->data['Category Subject']) {
		case('Part'):
			$sql=sprintf("insert into  `Part Category History Bridge` values (%d,%d,%d,%s)",
				$this->data['Category Warehouse Key'],
				$this->id,
				$history_key,
				prepare_mysql($type)
			);
			//print $sql;
			mysql_query($sql);
			break;
		}
	}

}
