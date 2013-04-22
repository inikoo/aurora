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

			//print_r($this->data);

			if ($this->data['Category Subject']=='Invoice') {
				$sql=sprintf("insert into `Invoice Category Dimension` (`Invoice Category Key`,`Invoice Category Store Key`) values (%d,%d)",$this->id,$this->data['Category Store Key']);
				mysql_query($sql);
				//print $sql;
			}
			elseif ($this->data['Category Subject']=='Supplier') {
				$sql=sprintf("insert into `Supplier Category Dimension` (`Category Key`) values (%d)",$this->id);
				mysql_query($sql);
			}elseif ($this->data['Category Subject']=='Part') {
				$sql=sprintf("insert into `Part Category Dimension` (`Part Category Key`,`Part Category Warehouse Key`) values (%d,%d)",$this->id,$this->data['Category Warehouse Key']);
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


		if ($this->data['Category Store Key']!=0 and array_key_exists('Category Store Key',$data))
			$data['Category Store Key']=$this->data['Category Store Key'];



		$data['Category Branch Type']='Head';
		$data['Category Subject Multiplicity']=$this->data['Category Subject Multiplicity'];

		$data['Category Root Key']=$this->data['Category Root Key'];
		$data['Category Parent Key']=$this->id;




		if (array_key_exists('Is Category Field Other',$data)) {
			if ($data['Is Category Field Other']=='Yes' and $this->data['Category Can Have Other']=='Yes'  and  $this->data['Category Children Other']=='No' ) {
				$data['Is Category Field Other']='Yes';

			}else {
				$data['Is Category Field Other']='No';
			}
		}else {
			$data['Is Category Field Other']='No';
		}
		// $data['editor']

		$subcategory=new Category('find create',$data);

		if ($data['Is Category Field Other']=='Yes') {
			$this->data['Category Children Other']='Yes';
			$sql=sprintf("update `Category Dimension` set `Category Children Other`=%s where `Category Key`=%d",
				prepare_mysql($this->data['Category Children Other']),
				$this->id
			);
		}

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
`Category Deleted Deep`, `Category Deleted Children`, `Category Deleted Code`, `Category Deleted Label`, `Category Deleted Subject`, `Category Deleted Subject Key`, `Category Deleted Number Subjects`,`Category Deleted Date`)
VALUES (%d,%s, %d, %d, %s,%s, %d, %d, %s, %s, %s, %d,%d,NOW())",
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
			$this->data['Category Number Subjects']

		);

		$is_category_other=$this->data['Is Category Field Other'];

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
		//print $sql_new_deleted_category;
		$sql=sprintf('delete from `Category Dimension` where `Category Key`=%d',$this->id);
		mysql_query($sql);

		foreach ($parent_keys as $parent_key) {
			$parent_category=new Category($parent_key);
			if ($parent_category->id) {
				$parent_category->update_children_data();

				if ($is_category_other=='Yes') {
					$parent_category->data['Category Children Other']='No';
					$sql=sprintf("update `Category Dimension` set `Category Children Other`=%s where `Category Key`=%d",
						prepare_mysql($parent_category->data['Category Children Other']),
						$parent_category->id
					);
				}


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
		$no_assigned_subjects=0;
		$assigned_subjects=0;

		$total_subjects=0;

		switch ($this->data['Category Subject']) {
		case('Part'):

			$sql=sprintf("select count(*) as num from `Part Warehouse Bridge` where `Warehouse Key`=%d",
				$this->data['Category Warehouse Key']);
			break;
		case('Customer'):

			$sql=sprintf("select count(*) as num from `Customer Dimension` where `Customer Store Key`=%d",
				$this->data['Category Store Key']);
			break;

		case('Supplier'):

			$sql=sprintf("select count(*) as num from `Supplier Dimension` ");
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


		$sql=sprintf("select COUNT(Distinct `Subject Key`)  as num from `Category Bridge`  where `Category Key`=%d  ",
			$this->id
		);
		$res=mysql_query($sql);
		$assigned_subjects=0;
		if ($row=mysql_fetch_assoc($res)) {
			$assigned_subjects=$row['num'];
		}
		$no_assigned_subjects=$total_subjects-$assigned_subjects;

		mysql_query($sql);
		$sql=sprintf("update `Category Dimension` set  `Category Subjects Not Assigned`=%d  where `Category Root Key`=%d ",
			$no_assigned_subjects,
			$this->data['Category Root Key']
		);

		$this->data['Category Subjects Not Assigned']=$no_assigned_subjects;

		//print $sql;

		mysql_query($sql);

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


	function update_part_category_status() {

		$elements_numbers=array(
			'In Use'=>0,'Not In Use'=>0
		);

		$sql=sprintf("select count(*) as num ,`Part Status` from  `Part Dimension` P left join `Category Bridge` B on (P.`Part SKU`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Part' group by  `Part Status`   ",
			$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Status']]=number($row['num']);

		}
		
		if($elements_numbers['Not In Use']>0 and $elements_numbers['In Use']==0){
			$this->data['Part Category Status`']='NotInUse';
		}else{
			$this->data['Part Category Status`']='InUse';
		}
		
		$sql=sprintf("update `Part Category Dimension` set `Part Category Status`=%s  where `Part Category Key`=%d",
		prepare_mysql($this->data['Part Category Status`']),
		$this->id
		);
		
		mysql_query($sql);
		
		
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
                     `Part Category $db_interval Acc Margin`=%s,
                     `Part Category $db_interval Acc Acquired`=%s,
                     `Part Category $db_interval Acc Broken`=%s,
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
		//print "$sql\n";

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
                     `Part Category $db_interval Acc 1YB Margin`=%s,
                     `Part Category $db_interval Acc 1YB Acquired`=%s,
                     `Part Category $db_interval Acc 1YB Broken`=%s,
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
			//print "$sql\n";



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

	


		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);


		

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
			case('Supplier'):
				include_once 'class.Supplier.php';

				$supplier=new Supplier($subject_key);
				$abstract=_('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> '._('disassociated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
				$details=_('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> ('.$supplier->data['Supplier Name'].') '._('disassociated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
				break;
			case('Customer'):
				include_once 'class.Customer.php';

				$customer=new Customer($subject_key);
				$abstract=_('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> '._('disassociated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
				$details=_('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> ('.$customer->data['Customer Main Location'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
				break;
			case('Product'):
				include_once 'class.Product.php';

				$product=new Product('pid',$subject_key);
				$abstract=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> '._('disassociated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
				$details=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> ('.$product->data['Product Name'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
				break;


			default:
				$abstract='todo';
				$details='todo';
			}

			if (isset($this->deleting_category)) {
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

			switch ($this->data['Category Subject']) {
			case('Part'):
				break;
			case('Supplier'):
				break;
			case('Customer'):
				$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Changes')",
					$customer->id,
					$history_key
				);
				// print $sql;
				mysql_query($sql);
				break;
			case('Product'):
				break;
			default:

			}


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
				//$this->update_number_of_subjects();
				//$this->update_subjects_data();


				switch ($this->data['Category Subject']) {
				case('Part'):
					include_once 'class.Part.php';

					$part=new Part($subject_key);
					$abstract=_('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('05%d',$part->sku).'</a> '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
					$details=_('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('05%d',$part->sku).'</a> ('.$part->data['Part XHTML Description'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
					break;
				case('Supplier'):
					include_once 'class.Supplier.php';

					$supplier=new Supplier($subject_key);
					$abstract=_('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
					$details=_('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> ('.$supplier->data['Supplier Name'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
					break;
				case('Customer'):
					include_once 'class.Customer.php';

					$customer=new Customer($subject_key);
					$abstract=_('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
					$details=_('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> ('.$customer->data['Customer Main Location'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';


					break;
				case('Product'):
					include_once 'class.Product.php';

					$product=new Product('pid',$subject_key);
					$abstract=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']);
					$details=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> ('.$product->data['Product Name'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>',$this->id,$this->data['Category Code']).' ('.$this->data['Category Label'].')';
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


				switch ($this->data['Category Subject']) {
				case('Part'):
					break;
				case('Supplier'):
					break;
				case('Customer'):
					$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Changes')",
						$customer->id,
						$history_key
					);
					// print $sql;
					mysql_query($sql);
					break;
				case('Product'):
					break;
				default:

				}



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
						//$parent_category->update_number_of_subjects();
						//$parent_category->update_subjects_data();

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
				$other_category->editor=$this->editor;
				$other_category->disassociate_subject($subject_key);


			}

			return $this->associate_subject($subject_key,true,$other_value);





		}

	}


	function get_user_view_icon() {


		if ($this->data['Category Show Subject User Interface']=='Yes') {
			if ($this->data['Category Show Public New Subject']=='Yes') {
				if ($this->data['Category Show Public Edit']=='Yes') {
					$image_tag='yyy';
				}else {
					$image_tag='yyn';
				}
			}
			else {
				if ($this->data['Category Show Public Edit']=='Yes') {
					$image_tag='yny';
				}else {
					$image_tag='ynn';
				}

			}


		}else {
			if ($this->data['Category Show Public New Subject']=='Yes') {

				if ($this->data['Category Show Public Edit']=='Yes') {
					$image_tag='nyy';
				}else {
					$image_tag='nyn';
				}



			}else {


				if ($this->data['Category Show Public Edit']=='Yes') {
					$image_tag='nny';
				}else {
					$image_tag='nnn';
				}


			}

		}

		$public_view_icon='<img src="art/icons/category_user_view_'.$image_tag.'.png" title="'._('Category View').'" /> ';

		return $public_view_icon;

	}
	function get_icon() {
		$branch_type_icon='';
		switch ($this->data['Category Branch Type']) {
		case('Root'):
			$branch_type_icon='<img src="art/icons/category_root'.($this->data['Category Can Have Other']=='Yes'?($this->data['Category Children Other']=='Yes'?'_with_other':'_can_other'):'').'_black.png" title="'._('Root Node').'" /> ';
			break;
		case('Node'):
			$branch_type_icon='<img src="art/icons/category_node'.($this->data['Category Can Have Other']=='Yes'?($this->data['Category Children Other']=='Yes'?'_with_other':'_can_other'):'').'_black.png" title="'._('Node').'" />';
			break;
		case('Head'):
			if ($this->data['Is Category Field Other']=='No')
				$branch_type_icon='<img src="art/icons/category_head_black.png" title="'._('Head Node').'" /> ';
			else
				$branch_type_icon='<img src="art/icons/category_head_other_black.png" title="'._('Head Node').' ('._('Other').')" /> ';

		}

		return $branch_type_icon;
	}

	function post_add_history($history_key,$type=false) {

		if (!$type) {
			$type='Changes';
		}

		switch ($this->data['Category Subject']) {
		case('Part'):
			$sql=sprintf("insert into  `Part Category History Bridge` values (%d,%d,%d,%s)",
				$this->data['Category Warehouse Key'],
				$this->id,
				$history_key,
				prepare_mysql($type)
			);
			mysql_query($sql);
			break;
		case('Supplier'):
			$sql=sprintf("insert into  `Supplier Category History Bridge` values (%d,%d,%s)",
				$this->id,
				$history_key,
				prepare_mysql($type)
			);
			mysql_query($sql);
			break;
		case('Customer'):
			$sql=sprintf("insert into  `Customer Category History Bridge` values (%d,%d,%d,%s)",
				$this->data['Category Store Key'],
				$this->id,
				$history_key,
				prepare_mysql($type)
			);
			mysql_query($sql);
			break;
		case('Product'):
			$sql=sprintf("insert into  `Product Category History Bridge` values (%d,%d,%d,%s)",
				$this->data['Category Store Key'],
				$this->id,
				$history_key,
				prepare_mysql($type)
			);
			mysql_query($sql);
			break;

		}
	}

}
