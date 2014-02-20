<?php
/*
 File: Family.php

 This file contains the Contact Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.Product.php';



class Family extends DB_Table {


	var $products=false;
	var $external_DB_link=false;
	var $id=false;
	var $data=array();
	var $locale;
	var $url;
	var $user_id;
	var $method;
	var $match=true;
	var $currency;
	/*
      Constructor: Family
      Initializes the class, trigger  Search/Load/Create for the data set

      Returns:
      void
    */


	function Family($a1=false,$a2=false,$a3=false) {
		//Transfered from LightFamily

		//End Transfer

		$this->table_name='Product Family';
		$this->page_data=false;
		$this->ignore_fields=array(
			'Product Family Key',
			'Product Family For Sale Products',
			'Product Family In Process Products',
			'Product Family Not For Sale Products',
			'Product Family Discontinued Products',
			'Product Family Unknown Sales State Products',
			'Product Family Surplus Availability Products',
			'Product Family Optimal Availability Products',
			'Product Family Low Availability Products',
			'Product Family Critical Availability Products',
			'Product Family Out Of Stock Products',
			'Product Family Unknown Stock Products',
			'Product Family Total Invoiced Gross Amount',
			'Product Family Total Invoiced Discount Amount',
			'Product Family Total Invoiced Amount',
			'Product Family Total Profit',
			'Product Family Total Quantity Ordered',
			'Product Family Total Quantity Invoiced',
			'Product Family Total Quantity Delivere',
			'Product Family Total Days On Sale',
			'Product Family Total Days Available',
			'Product Family 1 Year Acc Invoiced Gross Amount',
			'Product Family 1 Year Acc Invoiced Discount Amount',
			'Product Family 1 Year Acc Invoiced Amount',
			'Product Family 1 Year Acc Profit',
			'Product Family 1 Year Acc Quantity Ordered',
			'Product Family 1 Year Acc Quantity Invoiced',
			'Product Family 1 Year Acc Quantity Delivere',
			'Product Family 1 Year Acc Days On Sale',
			'Product Family 1 Year Acc Days Available',
			'Product Family 1 Quarter Acc Invoiced Gross Amount',
			'Product Family 1 Quarter Acc Invoiced Discount Amount',
			'Product Family 1 Quarter Acc Invoiced Amount',
			'Product Family 1 Quarter Acc Profit',
			'Product Family 1 Quarter Acc Quantity Ordered',
			'Product Family 1 Quarter Acc Quantity Invoiced',
			'Product Family 1 Quarter Acc Quantity Delivere',
			'Product Family 1 Quarter Acc Days On Sale',
			'Product Family 1 Quarter Acc Days Available',
			'Product Family 1 Month Acc Invoiced Gross Amount',
			'Product Family 1 Month Acc Invoiced Discount Amount',
			'Product Family 1 Month Acc Invoiced Amount',
			'Product Family 1 Month Acc Profit',
			'Product Family 1 Month Acc Quantity Ordered',
			'Product Family 1 Month Acc Quantity Invoiced',
			'Product Family 1 Month Acc Quantity Delivere',
			'Product Family 1 Month Acc Days On Sale',
			'Product Family 1 Month Acc Days Available',
			'Product Family 1 Week Acc Invoiced Gross Amount',
			'Product Family 1 Week Acc Invoiced Discount Amount',
			'Product Family 1 Week Acc Invoiced Amount',
			'Product Family 1 Week Acc Profit',
			'Product Family 1 Week Acc Quantity Ordered',
			'Product Family 1 Week Acc Quantity Invoiced',
			'Product Family 1 Week Acc Quantity Delivere',
			'Product Family 1 Week Acc Days On Sale',
			'Product Family 1 Week Acc Days Available',
			'Product Family Total Quantity Delivered',
			'Product Family 1 Year Acc Quantity Delivered',
			'Product Family 1 Month Acc Quantity Delivered',
			'Product Family 1 Quarter Acc Quantity Delivered',
			'Product Family 1 Week Acc Quantity Delivered'


		);


		if (is_numeric($a1) and !$a2  )
			$this->get_data('id',$a1,false);
		else if (preg_match('/new|create/',$a1) ) {
				$this->find($a2,'create');
			} else if (preg_match('/find/',$a1) ) {
				$this->find($a2,$a3);
			}
		elseif ($a2!='')
			$this->get_data($a1,$a2,$a3);
	}


	/*
        Function: find
        Busca the family
      */
	function find($raw_data,$options) {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found=false;
		$this->found_key=false;
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
				$data[$key]=_trim($value);
		}
		if ($data['Product Family Store Key']=='' ) {
			$this->error=true;
			$this->msg='Store Key not provided';
			return;
		}
		if ($data['Product Family Main Department Key']=='' ) {
			$this->error=true;
			$this->msg='Department Key code empty';
			return;
		}

		if ($data['Product Family Code']=='' ) {
			$this->error=true;
			$this->msg='Family code empty';
			return;
		}

		if ($data['Product Family Name']=='')
			$data['Product Family Name']=$data['Product Family Code'];

		$sql=sprintf("select * from `Product Family Dimension` where `Product Family Code`=%s  and `Product Family Store Key`=%d "
			,prepare_mysql($data['Product Family Code'])
			,$data['Product Family Store Key']
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Product Family Key'];
		}

		if ($this->found) {
			$this->get_data('id',$this->found_key);
			if ($create) {
				$this->msg=_('Family').' '.$this->data['Product Family Code'].' '._('is already created');
			}
		}

		if (!$this->found and $create) {

			$this->create($data);

		}



	}


	function create($data) {
		$this->new=false;

		$base_data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$base_data))
				$base_data[$key]=_trim($value);
		}

		if ($data['Product Family Special Characteristic']!='')
			$data['Product Family Special Characteristic']=$this->special_characteristic_if_duplicated($data);


		$department=new Department($base_data['Product Family Main Department Key']);
		$base_data['Product Family Main Department Code']=$department->get('Product Department Code');
		$base_data['Product Family Main Department Name']=$department->get('Product Department Name');

		$store=new Store($base_data['Product Family Store Key']);
		$base_data['Product Family Store Code']=$store->get('Store Code');
		$base_data['Product Family Currency Code']=$store->data['Store Currency Code'];


		if ($base_data['Product Family Special Characteristic']=='') {
			$base_data['Product Family Special Characteristic']=$base_data['Product Family Name'];
		}

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			if (preg_match('/Product Family Description/',$key))
				$values.="'".addslashes($value)."',";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Product Family Dimension` %s %s",$keys,$values);

		// print_r($data);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id',$this->id,false);
			$this->msg=_("Family Added");



			$sql=sprintf("insert into  `Product Family Default Currency`  (`Product Family Key`) values (%d) ",$this->id);
			mysql_query($sql);

			//     $sql=sprintf("insert into `Product Family Department Bridge` values (%d,%d)",$this->id,$department->id);
			//mysql_query($sql);

			$data_for_history=array('Action'=>'created'
				,'History Abstract'=>_('Family Created')
				,'History Details'=>_('Family')." ".$this->data['Product Family Name']." (".$this->get('Product Family Code').") "._('Created')
			);
			$this->add_history($data_for_history);



			$department->update_families();
			$store->update_families();
			$this->update_full_search();
			$this->new=true;

		} else {
			$this->error=true;
			$this->msg="$sql  Error can not create the family";
			print "$sql\n";
			exit;
		}
	}


	function get_data($tipo,$tag,$tag2=false) {
		switch ($tipo) {
		case('id'):
			$sql=sprintf("select *  from `Product Family Dimension` where `Product Family Key`=%d ",$tag);
			break;
		case('code'):
		case('code_store'):
			$sql=sprintf("select *  from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Store Key`=%d ",prepare_mysql($tag),$tag2);
			//print $sql;
			break;
		}

		// print $sql;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Product Family Key'];

	}



	function update_department($key) {

		if (!is_numeric($key)) {
			$this->error=true;
			$this->msg='Key is not a number';
			return;
		}

		$old_department_label=sprintf("%s, %s",$this->data['Product Family Main Department Code'],$this->data['Product Family Main Department Name']);

		//$old_family=new Department($this->data['Product Family Key']);
		$new_department=new Department($key);
		//print_r($new_department);
		$sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Key`=%d, `Product Family Main Department Code`=%s, `Product Family Main Department Name`=%s where `Product Family Key`=%d",
			$key,
			prepare_mysql($new_department->data['Product Department Code']),
			prepare_mysql($new_department->data['Product Department Name']),
			$this->id);


		mysql_query($sql);

		$sql=sprintf("update `Product Dimension` set `Product Main Department Key`=%d, `Product Main Department Code`=%s, `Product Main Department Name`=%s where `Product Family Key`=%d",
			$key,
			prepare_mysql($new_department->data['Product Department Code']),
			prepare_mysql($new_department->data['Product Department Name']),
			$this->id
		);
		mysql_query($sql);





		//$old_family->update_product_data();
		$new_department->update_product_data();

		$this->data['Product Family Key']=$key;
		$this->new_value=$key;
		$this->new_data=array('code'=>$new_department->data['Product Department Code'] ,'name'=>$new_department->data['Product Department Name'],'key'=>$new_department->id );
		$this->updated=true;
		$new_department_label=sprintf("%s, %s",$new_department->data['Product Department Code'],$new_department->data['Product Department Name']);


		$this->add_history(array(
				'Indirect Object'=>'Family Department'
				,'History Abstract'=>_("Family's department changed").' ('.$new_department->data['Product Department Code'].', '.$new_department->data['Product Department Name'].')'
				,'History Details'=>_("Family's department changed")."; ".$old_department_label." &rarr; ".$new_department_label
			));


	}

	function update_code($value) {
		if ($value==$this->data['Product Family Code']) {
			$this->updated=true;
			$this->new_value=$value;
			return;

		}

		if ($value=='') {
			$this->msg=_('Error: Wrong code (empty)');
			return;
		}
		if (!(strtolower($value)==strtolower($this->data['Product Family Code']) and $value!=$this->data['Product Family Code'])) {

			$sql=sprintf("select count(*) as num from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s  COLLATE utf8_general_ci "
				,$this->data['Product Family Store Key']
				,prepare_mysql($value)
			);
			$res=mysql_query($sql);
			$row=mysql_fetch_array($res);
			if ($row['num']>0) {
				$this->msg=_("Error: Another family with the same code");
				return;
			}
		}
		$old_value=$this->get('Product Family Code') ;
		$sql=sprintf("update `Product Family Dimension` set `Product Family Code`=%s where `Product Family Key`=%d "
			,prepare_mysql($value)
			,$this->id
		);
		if (mysql_query($sql)) {
			$this->msg=_('Family code updated');
			$this->updated=true;
			$this->new_value=$value;

			$this->data['Product Family Code']=$value;
			$this->update_full_search();

			$data_for_history=array(
				'Indirect Object'=>'Product Family Code'
				,'History Abstract'=>_('Product family Code changed').' ('.$this->get('Product Family Code').')'
				,'History Details'=>_('Family')." ".$this->data['Product Family Name']." "._('changed code from').' '.$old_value." "._('to').' '. $this->get('Product Family Code')
			);
			$this->add_history($data_for_history);

		} else {
			$this->msg=_("Error: Family code could not be updated");

			$this->updated=false;

		}
	}


	function update_name($value) {
		if ($value==$this->data['Product Family Name']) {
			$this->updated=true;
			$this->new_value=$value;
			return;

		}

		if ($value=='') {
			$this->msg=_('Error: Wrong name (empty)');
			return;
		}
		if (!(strtolower($value)==strtolower($this->data['Product Family Name']) and $value!=$this->data['Product Family Name'])) {
			$sql=sprintf("select count(*) as num from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Name`=%s  COLLATE utf8_general_ci"
				,$this->data['Product Family Store Key']
				,prepare_mysql($value)
			);
			$res=mysql_query($sql);
			$row=mysql_fetch_array($res);
			if ($row['num']>0) {
				$this->msg=_("Error: Another family with the same name");
				return;
			}
		}
		$old_value=$this->get('Product Family Name') ;
		$sql=sprintf("update `Product Family Dimension` set `Product Family Name`=%s where `Product Family Key`=%d "
			,prepare_mysql($value)
			,$this->id
		);
		if (mysql_query($sql)) {
			$this->msg=_('Family name updated');
			$this->updated=true;
			$this->new_value=$value;

			$this->data['Product Family Name']=$value;
			$this->update_full_search();
			$this->add_history(array(
					'Indirect Object'=>'Product Family Name'
					,'History Abstract'=>('Product Family Name Changed').' ('.$this->get('Product Family Name').')'
					,'History Details'=>_('Product Family')." ("._('Code').":".$this->data['Product Family Code'].") "._('name changed from').' '.$old_value." "._('to').' '. $this->get('Product Family Name')
				));



		} else {
			$this->msg=_("Error: Family name could not be updated");

			$this->updated=false;

		}
	}

	function update_field_switcher($field,$value,$options='') {

		switch ($field) {
		case('Store Sticky Note'):
			$this->update_field_switcher('Sticky Note',$value);
			break;
		case('Sticky Note'):
			$this->update_field('Product Family '.$field,$value,'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;
		case('special_char'):
		case('Product Family Special Characteristic'):
			$this->update_field('Product Family Special Characteristic',$value);
			break;
		case('code'):
			$this->update_code($value);
			break;
		case('name'):
			$this->update_name($value);
			break;
		case('sales_type'):
			$this->update_sales_type($value);
			break;
		case('description'):
			$this->update_description($value);
			break;
		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field,$value,$options);
				}
			}
		}
	}




	function update_description($description) {

		$old_description=$this->data['Product Family Description'];
		$this->update_field('Product Family Description',$description,'nohistory');

		if ($this->updated) {
			//set_include_path(get_include_path() . PATH_SEPARATOR . 'external_libs/PEAR');
			//include_once 'Text/Diff.php';
			//include_once 'Text/Diff/Renderer/inline.php';

			//$lines1=preg_split('/\n/',$old_description);
			//$lines2=preg_split('/\n/',$this->data['Product Family Description']);


			//$diff = new Text_Diff('native', array($lines1,$lines2));
			//$renderer = new Text_Diff_Renderer_inline();

			//$rendered_difference= preg_replace('/\<del\>/','<span class="diff_del">',$renderer->render($diff));
			//$rendered_difference= preg_replace('/\<\/del\>/','</span>',$rendered_difference);
			//$rendered_difference= preg_replace('/\<ins\>/','<span class="diff_ins">',$rendered_difference);
			//$rendered_difference= preg_replace('/\<\/ins\>/','</span>',$rendered_difference);



			$history_data=array(
				'History Abstract'=>_('Product Family Description Changed')
				,'History Details'=>''//$rendered_difference

				,'Indirect Object'=>'Product Family Description'
			);
			// print_r($history_data);
			$this->add_history($history_data);


		}



		//todo maje nice highlited diff history

	}



	function update_sales_type($value) {
		if (
			$value=='Public Sale' or $value=='Private Sale' or $value=='Not For Sale'
		) {
			$sales_state=$value;

			$sql=sprintf("update `Product Family Dimension` set `Product Family Sales Type`=%s  where  `Product Family Key`=%d "
				,prepare_mysql($sales_state)
				,$this->id
			);
			//print $sql;
			if (mysql_query($sql)) {

				$this->msg=_('Family Sales Type updated');
				$this->updated=true;

				$this->new_value=$value;
				return;
			} else {
				$this->msg=_("Error: Family sales type could not be updated ");
				$this->updated=false;
				return;
			}
		} else
			$this->msg=_("Error: wrong value")." [Sales Type] ($value)";
		$this->updated=false;
	}


	/*
        Function: delete
        Funcion que permite eliminar registros en la tabla Product Family Dimension,Product Family Department Bridge, cuidando la integridad referencial con los productos.
    */

	function delete() {
		$this->deleted=false;
		$this->update_product_data();

		if ($this->get('Total Products')==0) {
			$store=new Store($this->data['Product Family Store Key']);
			$department_keys=$this->get_department_keys();
			$sql=sprintf("delete from `Product Family Dimension` where `Product Family Key`=%d",$this->id);

			if (mysql_query($sql)) {

				$sql=sprintf("delete from `Product Family Department Bridge` where `Product Family Key`=%d",$this->id);
				mysql_query($sql);
				foreach ($department_keys as $dept_key) {

					$department=new Department($dept_key);
					$department->update_product_data();
				}
				$store->update_product_data();
				$this->deleted=true;

			} else {

				$this->msg=_('Error: can not delete family');
				return;
			}

			$this->deleted=true;
		} else { //Family has associated Products
			$move_all_products = true;
			$store = new Store($this->data['Product Family Store Key']);
			$department_keys=$this->get_department_keys();
			$sql = sprintf("select * from `Product Dimension` where `Product Family Key` = %d", $this->id);
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$product = new Product($row['Product ID']);
				$product->update('Product Family Key', $store->data['Store Orphan Products Family']);
				if (!$product->updated) {
					$move_all_products&=false;
				}
			}


			if ($move_all_products) {
				$sql=sprintf("delete from `Product Family Dimension` where `Product Family Key`=%d",$this->id);
				mysql_query($sql);
			}

			if (mysql_affected_rows()>0) {
				$sql=sprintf("delete from `Product Family Department Bridge` where `Product Family Key`=%d",$this->id);
				mysql_query($sql);
				foreach ($department_keys as $dept_key) {
					$department=new Department($dept_key);
					$department->update_product_data();
				}
				$store->update_product_data();
				$store->update_product_data();
				$this->deleted=true;
			} else {
				$this->deleted_msg='Error family can not be deleted';
			}


		}
	}


	function get_department_keys() {
		$department_keys=array();
		$sql=sprintf("Select `Product Department Key` from `Product Family Department Bridge` where `Product Family Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$department_keys[]=$row['Product Department Key'];
		}
		return $department_keys;
	}


	function get_pages_keys() {
		$page_keys=array();
		$sql=sprintf("Select `Page Key` from `Page Store Dimension` where `Page Store Section`='Family Catalogue' and  `Page Parent Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$page_keys[]=$row['Page Key'];
		}
		return $page_keys;
	}





	function get_similar_families() {
		$similar_families='';
		$sql=sprintf("select `Product Family Code`,`Family B Key`,`Weight` from `Product Family Semantic Correlation` left join `Product Family Dimension` on (`Family B Key`=`Product Family Key`) where `Family A Key`=%d order by `Weight` desc limit 5",$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$similar_families.=sprintf(', <a href="family.php?id=%d">%s</a> (%s)',$row['Family B Key'],$row['Product Family Code'],number($row['Weight'],2));
		}
		$similar_families=preg_replace('/^, /','',$similar_families);

		if ($similar_families=='')
			$similar_families= "<span style='color:#666;font-style:italic;'>"._('No similar families')."</span>";

		return $similar_families;
	}

	function get_sales_correlated_families() {
		$sales_correlated_families='';
		$sql=sprintf("select `Product Family Code`,`Family B Key`,`Correlation` from `Product Family Sales Correlation` left join `Product Family Dimension` on (`Family B Key`=`Product Family Key`) where `Family A Key`=%d order by `Correlation` desc limit 5",$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$sales_correlated_families.=sprintf(', <a href="family.php?id=%d">%s</a> (%s)',$row['Family B Key'],$row['Product Family Code'],percentage($row['Correlation'],1));
		}
		$sales_correlated_families=preg_replace('/^, /','',$sales_correlated_families);

		if ($sales_correlated_families=='')
			$sales_correlated_families= "<span style='color:#666;font-style:italic;'>"._('No correlated families')."</span>";

		return $sales_correlated_families;
	}

	function get_sold_in_pages() {
		$sold_in_pages='';
		$sql=sprintf("select P.`Page Key`,`Page Code` from  `Page Product Dimension` B left join `Page Store Dimension` P on (B.`Page Key`=P.`Page Key`) where `Family Key`=%d group by B.`Page Key` ",$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$sold_in_pages.=sprintf(', <a href="page.php?id=%d">%s</a>',$row['Page Key'],$row['Page Code']);
		}
		$sold_in_pages=preg_replace('/^, /','',$sold_in_pages);

		if ($sold_in_pages=='')
			$sold_in_pages= "<span style='color:#666;font-style:italic;'>"._('No in website')."</span>";

		return $sold_in_pages;
	}


	function get($key,$options=false) {

		if (!$this->id)
			return '';

		if (array_key_exists($key,$this->data))
			return $this->data[$key];


		if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Amount|Profit)$/',$key)) {

			$amount='Product Family '.$key;

			return money($this->data[$amount]);
		}
		if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers)$/',$key)) {

			$amount='Product Family '.$key;

			return number($this->data[$amount]);
		}


		switch ($key) {
		case("Sticky Note"):
			return nl2br($this->data['Product Family Sticky Note']);
			break;
		case('Similar Families'):
			return $this->get_similar_families();
		case ('Sales Correlated Families'):
			return $this->get_sales_correlated_families();
		case ('Sold in Pages'):
			return $this->get_sold_in_pages();
			break;
		case('Price From Info'):
			$min=99999999;
			$product_id='';
			$changed=false;
			foreach ($this->products as $key => $value) {
				if ($value['Product Price']<$min and $value['Product Price']>0) {
					$min=$value['Product Price'];
					$product_id=$value['Product Key'];
					$changed=true;
				}
			}

			if ($changed) {
				$product=new Product($product_id);
				return '<div class="prod_info">'.$product->get('Price Formated','from').'</div>';
			} else
				return '';


			break;

		case('Product Family Description Length'):
			return strlen($this->data['Product Family Description']);
			break;
		case('Product Family Description MD5 Hash'):
			return md5($this->data['Product Family Description']);
			break;




		case('Total Products'):

			return $this->get_number_products();
			break;


		case('weeks'):
			if (is_numeric($this->data['first_date'])) {
				$date1=date('Y-m-d',strtotime('@'.$this->data['first_date']));
				$day1=date('N')-1;
				$date2=date('Y-m-d');
				$days=datediff('d',$date1,$date2);
				$weeks=number_weeks($days,$day1);
			} else
				$weeks=0;
			return $weeks;
		}

	}



	function add_product($product_id,$args=false) {

		$product=new Product($product_id);
		if ($product->id) {
			$sql=sprintf("update  `Product Dimension` set `Product Family Key`=%d ,`Product Family Code`=%s,`Product Family Name`=%s where `Product Key`=%s    "
				,$this->id
				,prepare_mysql($this->get('Product Family Code'))
				,prepare_mysql($this->get('Product Family Name'))
				,$product->id);
			mysql_query($sql);
			$this->update_product_data();
			// print "$sql\n";
		}
	}




	function update_up_today_sales() {

		$this->update_sales_from_invoices('Today');
		$this->update_sales_from_invoices('Week To Day');
		$this->update_sales_from_invoices('Month To Day');
		$this->update_sales_from_invoices('Year To Day');

		$this->update_sales_from_invoices('Total');
	}

	function update_last_period_sales() {

		$this->update_sales_from_invoices('Yesterday');
		$this->update_sales_from_invoices('Last Week');
		$this->update_sales_from_invoices('Last Month');

	}


	function update_interval_sales() {

		$this->update_sales_from_invoices('3 Year');
		$this->update_sales_from_invoices('1 Year');
		$this->update_sales_from_invoices('6 Month');
		$this->update_sales_from_invoices('1 Quarter');
		$this->update_sales_from_invoices('1 Month');
		$this->update_sales_from_invoices('10 Day');
		$this->update_sales_from_invoices('1 Week');

	}


	function update_sales_from_invoices($interval) {



		$to_date='';

		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);


		setlocale(LC_ALL, 'en_GB');

		//   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

		$this->data["Product Family $db_interval Acc Invoiced Discount Amount"]=0;
		$this->data["Product Family $db_interval Acc Invoiced Gross Amount"]=0;

		$this->data["Product Family $db_interval Acc Invoiced Amount"]=0;
		$this->data["Product Family $db_interval Acc Invoices"]=0;
		$this->data["Product Family $db_interval Acc Profit"]=0;
		$this->data["Product Family $db_interval Acc Customers"]=0;
		$this->data["Product Family $db_interval Acc Quantity Ordered"]=0;
		$this->data["Product Family $db_interval Acc Quantity Invoiced"]=0;
		$this->data["Product Family $db_interval Acc Quantity Delivered"]=0;
		$this->data["Product Family DC $db_interval Acc Invoiced Amount"]=0;
		$this->data["Product Family DC $db_interval Acc Invoiced Discount Amount"]=0;
		$this->data["Product Family DC $db_interval Acc Invoiced Gross Amount"]=0;

		$this->data["Product Family DC $db_interval Acc Profit"]=0;

		$sql=sprintf("select  sum(`Shipped Quantity`) as qty_delivered,sum(`Order Quantity`) as qty_ordered,sum(`Invoice Quantity`) as qty_invoiced ,count(Distinct `Customer Key`)as customers,count(distinct `Invoice Key`) as invoices,IFNULL(sum(`Invoice Transaction Total Discount Amount`),0) as discounts,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net ,sum(`Invoice Transaction Gross Amount`) as gross  ,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) as total_cost,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`) as dc_discounts,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net,sum((`Invoice Transaction Gross Amount`)*`Invoice Currency Exchange Rate`) dc_gross  ,sum((`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`)*`Invoice Currency Exchange Rate`) as dc_total_cost from `Order Transaction Fact` where  `Current Dispatching State`='Dispatched' and `Product Family Key`=%d %s %s" ,
			$this->id,

			($from_date?sprintf('and `Invoice Date`>%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')

		);

		$result=mysql_query($sql);

		//  print $sql."\n\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Product Family $db_interval Acc Invoiced Discount Amount"]=$row["discounts"];
			$this->data["Product Family $db_interval Acc Invoiced Amount"]=$row["net"];
			$this->data["Product Family $db_interval Acc Invoiced Gross Amount"]=$row["gross"];

			$this->data["Product Family $db_interval Acc Invoices"]=$row["invoices"];
			$this->data["Product Family $db_interval Acc Profit"]=$row["net"]-$row['total_cost'];
			$this->data["Product Family $db_interval Acc Customers"]=$row["customers"];
			$this->data["Product Family $db_interval Acc Quantity Ordered"]=$row["qty_ordered"];
			$this->data["Product Family $db_interval Acc Quantity Invoiced"]=$row["qty_invoiced"];
			$this->data["Product Family $db_interval Acc Quantity Delivered"]=$row["qty_delivered"];

			$this->data["Product Family DC $db_interval Acc Invoiced Amount"]=$row["dc_net"];
			$this->data["Product Family DC $db_interval Acc Invoiced Discount Amount"]=$row["dc_discounts"];
			$this->data["Product Family DC $db_interval Acc Invoiced Gross Amount"]=$row["dc_gross"];

			$this->data["Product Family DC $db_interval Acc Profit"]=$row["dc_net"]-$row['dc_total_cost'];
		}


		/*
$sql="select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')  and  `Product Family Key`=".$this->id;
		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
*/

		$sql=sprintf("update `Product Family Dimension` set
                     `Product Family $db_interval Acc Invoiced Discount Amount`=%.2f,
                                          `Product Family $db_interval Acc Invoiced Gross Amount`=%.2f,

                     `Product Family $db_interval Acc Invoiced Amount`=%.2f,
                     `Product Family $db_interval Acc Invoices`=%d,
                     `Product Family $db_interval Acc Profit`=%.2f,
                      `Product Family $db_interval Acc Customers`=%d,
                       `Product Family $db_interval Acc Quantity Ordered`=%d,
                       `Product Family $db_interval Acc Quantity Invoiced`=%d,
                       `Product Family $db_interval Acc Quantity Delivered`=%d
                     where `Product Family Key`=%d "
			,$this->data["Product Family $db_interval Acc Invoiced Discount Amount"]
			,$this->data["Product Family $db_interval Acc Invoiced Gross Amount"]

			,$this->data["Product Family $db_interval Acc Invoiced Amount"]
			,$this->data["Product Family $db_interval Acc Invoices"]
			,$this->data["Product Family $db_interval Acc Profit"]
			,$this->data["Product Family $db_interval Acc Customers"]
			,$this->data["Product Family $db_interval Acc Quantity Ordered"]
			,$this->data["Product Family $db_interval Acc Quantity Invoiced"]
			,$this->data["Product Family $db_interval Acc Quantity Delivered"]

			,$this->id
		);

		mysql_query($sql);
		//print $sql."\n\n";

		$sql=sprintf("update `Product Family Default Currency` set
                             `Product Family DC $db_interval Acc Invoiced Discount Amount`=%.2f,
                                                          `Product Family DC $db_interval Acc Invoiced Gross Amount`=%.2f,

                             `Product Family DC $db_interval Acc Invoiced Amount`=%.2f,
                             `Product Family DC $db_interval Acc Profit`=%.2f
                             where `Product Family Key`=%d "
			,$this->data["Product Family DC $db_interval Acc Invoiced Discount Amount"]
			,$this->data["Product Family DC $db_interval Acc Invoiced Gross Amount"]

			,$this->data["Product Family DC $db_interval Acc Invoiced Amount"]
			,$this->data["Product Family DC $db_interval Acc Profit"]
			,$this->id
		);

		mysql_query($sql);

		//print "XX: $interval $from_date_1yb\n\n";

		if ($from_date_1yb) {
			$this->data["Product Family $db_interval Acc 1YB Invoices"]=0;
			$this->data["Product Family $db_interval Acc 1YB Invoiced Discount Amount"]=0;
			$this->data["Product Family $db_interval Acc 1YB Invoiced Amount"]=0;
			$this->data["Product Family $db_interval Acc 1YB Profit"]=0;
			$this->data["Product Family $db_interval Acc 1YB Invoiced Delta"]=0;


			$sql=sprintf("select count(distinct `Invoice Key`) as invoices,IFNULL(sum(`Invoice Transaction Total Discount Amount`),0) as discounts,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net  ,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) as total_cost ,
                         sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`) as dc_discounts,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net  ,sum((`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`)*`Invoice Currency Exchange Rate`) as dc_total_cost from `Order Transaction Fact` where `Current Dispatching State`='Dispatched' and `Product Family Key`=%d and `Invoice Date`>=%s %s" ,
				$this->id,
				prepare_mysql($from_date_1yb),
				($to_1yb?sprintf('and `Invoice Date`<%s',prepare_mysql($to_1yb)):'')

			);



			// print "$sql\n\n";
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Product Family $db_interval Acc 1YB Invoiced Discount Amount"]=$row["discounts"];
				$this->data["Product Family $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
				$this->data["Product Family $db_interval Acc 1YB Invoiced Delta"]=($row["net"]==0?-1000000:$this->data["Product Family $db_interval Acc Invoiced Amount"]/$row["net"]);
				$this->data["Product Family $db_interval Acc 1YB Invoices"]=$row["invoices"];
				$this->data["Product Family $db_interval Acc 1YB Profit"]=$row["net"]-$row['total_cost'];

			}

			$sql=sprintf("update `Product Family Dimension` set
                         `Product Family $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Product Family $db_interval Acc 1YB Invoiced Amount`=%.2f,
                                                 `Product Family $db_interval Acc 1YB Invoiced Delta`=%f,

                         `Product Family $db_interval Acc 1YB Invoices`=%.2f,
                         `Product Family $db_interval Acc 1YB Profit`=%.2f
                         where `Product Family Key`=%d "
				,$this->data["Product Family $db_interval Acc 1YB Invoiced Discount Amount"]
				,$this->data["Product Family $db_interval Acc 1YB Invoiced Amount"]
				,$this->data["Product Family $db_interval Acc 1YB Invoiced Delta"]

				,$this->data["Product Family $db_interval Acc 1YB Invoices"]
				,$this->data["Product Family $db_interval Acc 1YB Profit"]
				,$this->id
			);

			mysql_query($sql);
			//print "$sql\n";


		}

		return array(substr($from_date, -19,-9), date("Y-m-d"));

	}





	function special_characteristic_if_duplicated($data) {

		$sql=sprintf("select * from `Product Family Dimension` where `Product Family Special Characteristic`=%s  and `Product Family Store Key`=%d "
			,prepare_mysql($data['Product Family Special Characteristic'])
			,$data['Product Family Store Key']
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$s_char=$row['Product Family Special Characteristic'];
			$number=1;
			$sql=sprintf("select * from `Product Family Dimension` where `Product Family Special Characteristic` like '%s (%%)'  and `Product Family Store Key`=%d "
				,addslashes($data['Product Family Special Characteristic'])
				,$data['Product Family Store Key']
			);
			$result2=mysql_query($sql);

			while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {

				if (preg_match('/\(\d+\)$/',$row2['Product Family Special Characteristic'],$match))
					$_number=preg_replace('/[^\d]/','',$match[0]);
				if ($_number>$number)
					$number=$_number;
			}

			$number++;

			return $data['Product Family Special Characteristic']." ($number)";

		} else {
			return $data['Product Family Special Characteristic'];
		}


	}


	function update_product_price_data() {
		$from_price=0;
		$price_multiplicity=0;
		$sql=sprintf("select min(`Product Price`) as from_price ,count(distinct `Product Price`) as price_multiplicity from `Product Dimension` where `Product Family Key`=%d and `Product Sales Type`='Public Sale'",
			$this->id);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$from_price=$row['from_price'];
			$price_multiplicity=$row['price_multiplicity'];
		}
		$sql=sprintf("update `Product Family Dimension` set `Product Family From Price`=%.2f,`Product Family Product Price Multiplicity`=%d where `Product Family Key`=%d",
			$from_price,
			$price_multiplicity,
			$this->id);
		//print "$sql\n";
		mysql_query($sql);

	}

	function update_product_data() {


		$sql=sprintf("select  sum(if(`Product Availability Type`='Discontinued'  and `Product Availability`>0   ,1,0)) as to_be_discontinued ,
                     sum(if(`Product Main Type`='Historic',1,0)) as historic ,
                     sum(if(`Product Main Type`='Discontinued',1,0) ) as discontinued,
                     sum(if(`Product Main Type`='Private',1,0) ) as private_sale,
                     sum(if(`Product Main Type`='NoSale',1,0) ) as not_for_sale,
                     sum(if(`Product Main Type`='Sale' ,1,0)) as public_sale,
                     sum(if(`Product Stage`='In process',1,0)) as in_process ,
                     sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,
                     sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,
                     sum(if(`Product Availability State`='Low',1,0)) as availability_low,
                     sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,
                     sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,
                     sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock
                     from `Product Dimension` where `Product Family Key`=%d",$this->id);
		//  print $sql;
		//exit;

		$availability='No Applicable';
		$sales_type='No Applicable';
		$historic=0;

		$in_process=0;
		$public_sale=0;
		$private_sale=0;
		$discontinued=0;
		$not_for_sale=0;

		$availability_optimal=0;
		$availability_low=0;
		$availability_critical=0;
		$availability_outofstock=0;
		$availability_unknown=0;
		$availability_surplus=0;
		$to_be_discontinued=0;

		$result=mysql_query($sql);



		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			//   print_r($row);
			$to_be_discontinued=$row['to_be_discontinued'];
			$historic=$row['historic'];

			$in_process=$row['in_process'];
			$public_sale=$row['public_sale'];
			$private_sale=$row['private_sale'];
			$discontinued=$row['discontinued'];
			$not_for_sale=$row['not_for_sale'];

			$availability_optimal=$row['availability_optimal'];
			$availability_low=$row['availability_low'];
			$availability_critical=$row['availability_critical'];
			$availability_outofstock=$row['availability_outofstock'];
			$availability_unknown=$row['availability_unknown'];
			$availability_surplus=$row['availability_surplus'];



			if ($public_sale==0 and $private_sale==0 and $not_for_sale>0) {
				$sales_type='Not for Sale';
			}
			elseif ($public_sale==0 and $private_sale>0) {
				$sales_type='Private Sale Only';
			}
			elseif ($public_sale>0 ) {
				$sales_type='Public Sale';
			}
			else
				$sales_type='Unknown';

			$avalilable_products=$availability_optimal+$availability_low+$availability_critical+$availability_surplus+$availability_unknown;
			if ( $avalilable_products>0 and $availability_outofstock>0  ) {
				$availability='Some Out of Stock';
			} else if ($avalilable_products>0) {
					$availability='Normal';
				} else if ($avalilable_products==0 and $availability_outofstock>0) {
					$availability='All Out of Stock';
				}


		}


		$total_products=$discontinued+$public_sale+$private_sale+$not_for_sale;



		if ($public_sale>0 ) {
			$record_type='Normal';

			if ($public_sale==$to_be_discontinued) {
				$record_type='Discontinuing';
			}


		}
		elseif ($private_sale>0 ) {
			$record_type='Nosale';

		}
		elseif ($discontinued>0) {
			$record_type='Discontinued';

		}
		else if ($in_process>0) {
				$record_type='InProcess';
			} else {
			$record_type='Nosale';
		}

		$sql=sprintf("update `Product Family Dimension` set `Product Family Record Type`=%s,`Product Family In Process Products`=%d,`Product Family For Public Sale Products`=%d ,`Product Family For Private Sale Products`=%d,`Product Family Discontinued Products`=%d ,`Product Family Not For Sale Products`=%d , `Product Family Optimal Availability Products`=%d , `Product Family Low Availability Products`=%d ,`Product Family Critical Availability Products`=%d ,`Product Family Out Of Stock Products`=%d,`Product Family Unknown Stock Products`=%d ,`Product Family Surplus Availability Products`=%d ,`Product Family Sales Type`=%s,`Product Family Availability`=%s where `Product Family Key`=%d  ",
			prepare_mysql($record_type),
			$in_process,
			$public_sale,
			$private_sale,
			$discontinued,
			$not_for_sale,

			$availability_optimal,
			$availability_low,
			$availability_critical,
			$availability_outofstock,
			$availability_unknown,
			$availability_surplus,
			prepare_mysql($sales_type),
			prepare_mysql($availability),
			$this->id
		);



		mysql_query($sql);



		$this->get_data('id',$this->id);
	}




	function product_timeline($extra_where='') {
		//todo: this scheme dont take in count products with 2 sku or sku changing over time
		$min_date=date('Y-m-d H:i:s');
		$max_date=date('Y-m-d H:i:s');

		$sql=sprintf("select `Product Code`,`Product ID`,`Product Sales Type`,`Product Record Type`,`Product Valid From`,`Product Valid To` from `Product Dimension`  where `Product Family Key`=%d  %s  ",$this->id,$extra_where);
		$res=mysql_query($sql);
		$products=array();
		$skus=array();
		while ($row=mysql_fetch_array($res)) {
			if (strtotime($min_date)>strtotime($row['Product Valid From']))
				$min_date=$row['Product Valid From'];
			if (strtotime($min_date)>strtotime($row['Product Valid To']))
				$min_date=$row['Product Valid To'];

			$_product=new Product('pid',$row['Product ID']);
			$units_per_case=$_product->data['Product Units Per Case'];
			$sku=$_product->get('Parts SKU');


			$products[]=array(
				'code'=>$row['Product Code'],'units_per_case'=>$units_per_case,'sku'=>$sku[0],'id'=>$row['Product ID'],'from'=>$row['Product Valid From'],'to'=>($row['Product Sales Type']!='Not for Sale'?date('Y-m-d H:i:s'):$row['Product Valid To']));
		}
		//print "$min_date $max_date\n";
		//print_r($products);

		$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=DATE(%s) and `Date`<=DATE(%s)",prepare_mysql($min_date),prepare_mysql($max_date));
		$res=mysql_query($sql);
		$dates=array();
		$dates_skus=array();
		$dates_ppp=array();
		//print $sql;
		while ($row=mysql_fetch_array($res)) {
			$dates[$row['Date']]=array();
			$dates_skus[$row['Date']]=array();
			$dates_ppp[$row['Date']]=array();
			foreach ($products as $product) {
				if (!(strtotime($product['to'])<strtotime($row['Date'].' OO:00:00')  or  strtotime($product['from'])>strtotime($row['Date'].' 23:59:59')  )) {
					$dates[$row['Date']][]=$product['id'];
					$dates_skus[$row['Date']][]=$product['sku'];
					$dates_ppp[$row['Date']][]=$product['units_per_case'];
				}

			}
			sort($dates[$row['Date']]);

		}

		//  print_r($dates);

		$border_dates=array();
		$pivot=array();
		foreach ($dates as $key=>$date) {
			if ($pivot!=$date) {
				//print "$key\n";
				$border_dates[]=$key;
			}
			$pivot=$date;
		}
		$border_dates[]=$key;
		//print_r($border_dates);
		$counter=0;
		$product_interval=array();
		foreach ($border_dates as $border) {

			$recent='No';
			if ($counter==count($border_dates)-1)
				$recent='Yes';
			if ($counter>0) {
				$product_interval[]=array(
					'Product IDs'=>$dates[$lower_bound],
					'Product SKUs'=>$dates_skus[$lower_bound],
					'Products Per Part'=>$dates_ppp[$lower_bound],
					'Valid From'=>$lower_bound.' 00:00:00',
					'Valid To'=>date('Y-m-d h:i:s',strtotime($border.' -1 second')),
					'Most Recent'=>$recent
				);
			}
			$lower_bound=$border;
			$counter++;
		}

		//print_r($product_interval);

		return $product_interval;


	}

	function get_next_product_code() {
		$next_code='';
		$sql=sprintf("select `Product Code File As` from `Product Dimension` where `Product Family Key`=%d order by  `Product Code File As` desc limit 1 ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$next_code=$row['Product Code File As'];
			if (preg_match('/^[a-z]+\-\d+$/',$row['Product Code File As'])) {
				$last_number=1;
				if (preg_match('/\d+$/',$row['Product Code File As'],$match)) {
					$last_number+=$match[0];
				}
				$next_code=sprintf("%s-%02d",$this->data['Product Family Code'],$last_number);
			}


			return $next_code;
		} else
			return '';

	}

	function update_sales_state() {

		$this->update_product_data();

	}

	function get_number_products() {
		$number_products=0;
		$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d "
			,$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_products=$row['num'];

		}
		return $number_products;
	}



	function get_number_products_by_sales_type($tipo=false) {
		$number_products=array('Public Sale'=>0,'Private Sale'=>0,'Not for Sale'=>0,'Discontinued'=>0);

		$sql=sprintf("select count(*) as num, `Product Sales Type` from `Product Dimension` where `Product Family Key`=%d group by `Product Sales Type`"
			,$this->id
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$number_products[$row['Product Sales Type']]=$row['num'];

		}
		if (!$tipo)
			return $number_products;
		else if (array_key_exists($tipo,$number_products))
				return $number_products[$tipo];
			else
				return 0;

	}








	function has_layout_old_to_delete($type) {


		if (!$this->data['Family Page Key'])
			return false;
		if (!$this->page_data) {
			$this->page_data=$this->get_page_data();
			if (!$this->page_data)
				return false;
		}

		switch ($type) {
		case "thumbnails":
			if ($this->page_data['Product Thumbnails Layout']=='Yes')
				return true;
			break;
		case "list":
		case "lists":
			if ($this->page_data['List Layout']=='Yes')
				return true;
			break;

		case "slideshow":
			if ($this->page_data['Product Slideshow Layout']=='Yes')
				return true;
			break;
		case "manual":
			if ($this->page_data['Product Manual Layout']=='Yes')
				return true;
			break;
		default:
			return false;
			break;
		}

		return false;
	}





	function update_full_search() {

		$first_full_search=$this->data['Product Family Code'].' '.$this->data['Product Family Name'];
		$second_full_search='';

		if ($this->data['Product Family Main Image']!='art/nopic.png')
			$img=preg_replace('/small/','thumbnails',$this->data['Product Family Main Image']);
		else
			$img='';

		$description1='<b><a href="family.php?id='.$this->id.'">'.$this->data['Product Family Code'].'</a></b>';
		$description2=$this->data['Product Family Name'];
		$description='<table ><tr style="border:none;"><td  class="col1"'.$description1.'</td><td class="col2">'.$description2.'</td></tr></table>';


		$sql=sprintf("insert into `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`) values  (%s,'Family',%d,%s,%s,%s,%s,%s) on duplicate key
                     update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s"
			,$this->data['Product Family Store Key']
			,$this->id
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->data['Product Family Code'],false)
			,prepare_mysql($description,false)
			,prepare_mysql($img,false)
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->data['Product Family Code'],false)
			,prepare_mysql($description,false)
			,prepare_mysql($img,false)
		);
		mysql_query($sql);
		//exit($sql);
	}

	function update_sales_default_currency() {
		$this->data_default_currency=array();
		$this->data_default_currency['Product Family DC Total Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Family DC Total Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Family DC Total Invoiced Amount']=0;
		$this->data_default_currency['Product Family DC Total Profit']=0;
		$this->data_default_currency['Product Family DC 1 Year Acc Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Family DC 1 Year Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Family DC 1 Year Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Family DC 1 Year Acc Profit']=0;
		$this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Family DC 1 Quarter Acc Profit']=0;
		$this->data_default_currency['Product Family DC 1 Month Acc Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Family DC 1 Month Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Family DC 1 Month Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Family DC 1 Month Acc Profit']=0;
		$this->data_default_currency['Product Family DC 1 Week Acc Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Family DC 1 Week Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Family DC 1 Week Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Family DC 1 Week Acc Profit']=0;



		$sql="select     sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF   where `Product Family Key`=".$this->id;


		//print "$sql\n\n";
		// exit;
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Family DC Total Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Family DC Total Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Family DC Total Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Family DC Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}



		$sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Family DC 1 Year Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Family DC 1 Year Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Family DC 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Family DC 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}

		$sql=sprintf("select   sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF    where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Family DC 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}

		$sql=sprintf("select    sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross  ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));



		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Family DC 1 Month Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Family DC 1 Month Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Family DC 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Family DC 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}
		$sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross   ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
		// print $sql;
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Family DC 1 Week Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Family DC 1 Week Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Family DC 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Family DC 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}

		$insert_values='';
		$update_values='';
		foreach ($this->data_default_currency as $key=>$value) {
			$insert_values.=sprintf(',%.2f',$value);
			$update_values.=sprintf(',`%s`=%.2f',addslashes($key),$value);
		}
		$insert_values=preg_replace('/^,/','',$insert_values);
		$update_values=preg_replace('/^,/','',$update_values);


		$sql=sprintf('Insert into `Product Family Default Currency` values (%d,%s) ON DUPLICATE KEY UPDATE %s  ',$this->id,$insert_values,$update_values);
		mysql_query($sql);
		//print "$sql\n";



	}


	function get_orders_keys() {
		$orders_keys=array();
		$sql=sprintf("select `Order Key` from  `Order Transaction Fact`  where `Product Family Key`=%d",
			$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$orders_keys[$row['Order Key']]=$row['Order Key'];

		}
		return $orders_keys;

	}
























	function update_correlated_sales_families() {
		$orders=0;

		$sql=sprintf("select count(DISTINCT `Order Key`) as num from  `Order Transaction Fact`  where `Product Family Key`=%d  and `Order Quantity`>0 and `Order Transaction Type`='Order'",
			$this->id);
		$res=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_assoc($res)) {
			$orders=$row['num'];
		}

		if ($orders) {
			$orders_keys=$this->get_orders_keys();
			$sql=sprintf("select `Product Family Key` from `Product Family Dimension` where `Product Family Key`!=%d and `Product Family Store Key`=%d ",
				$this->id,
				$this->data['Product Family Store Key']
			);
			$result=mysql_query($sql);
			//print "$sql\n";
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$family=new Family($row['Product Family Key']);
				$family_orders_keys=$family->get_orders_keys();
				$common_orders=array_intersect_key($orders_keys,$family_orders_keys);
				$number_common_orders=count($common_orders);
				$probability=$number_common_orders/$orders;
				// print $family->id." $probability\n";

				if ($probability>0.000001) {
					$sql=sprintf("insert into `Product Family Sales Correlation` values (%d,%d,%f,%d) ON DUPLICATE KEY UPDATE `Correlation`=%f , `Samples`=%d  ",
						$this->id,
						$family->id,
						$probability,
						$orders,
						$probability,
						$orders
					);
					mysql_query($sql);
					//    print "$sql\n";

				}

			}



		}

	}


	function update_similar_families() {

		$department_codes=array();
		$department_keys=array();
		$see_also=array();


		$this_family_name=$this->data['Product Family Name'];
		$department_key=$this->data['Product Family Main Department Key'];
		$code=$this->data['Product Family Code'];

		$finger_print=_trim(strtolower($this->data['Product Family Code'].' '.$this->data['Product Family Name'].' '.$this->data['Product Family Description']));
		$sql=sprintf("select `Product Family Main Department Key`,`Product Family Key`,`Product Family Name`, `Product Family Code` from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Key`!=%d",
			$this->data['Product Family Store Key'],
			$this->id);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$other_finger_print=strtolower($row['Product Family Code'].' '.$row['Product Family Name']);
			$weight=sentence_similarity($finger_print,$other_finger_print)/100;

			if (!$row['Product Family Main Department Key']==$department_key)
				$weight=$weight/1.4;

			if ($weight>0.000001) {
				$sql=sprintf("insert into `Product Family Semantic Correlation` values (%d,%d,%f) ON DUPLICATE KEY UPDATE `Weight`=%f  ",
					$this->id,
					$row['Product Family Key'],
					$weight,
					$weight

				);
				mysql_query($sql);


			}





		}
	}


	function get_product_in_family_with_order_form_deleteme($data, $header=false, $type, $secure, $_port, $_protocol, $url, $server, $ecommerce_url, $username, $method, $options=false, $user=false, $path=false) {


		if (isset($options['order_by']))
			if (strtolower($options['order_by']) == 'price')
				$order_by='`Product RRP`';
			elseif (strtolower($options['order_by']) == 'code')
				$order_by='`Product Code File As`';
			elseif (strtolower($options['order_by']) == 'name')
				$order_by='`Product Name`';
			else
				$order_by='`Product Code File As`';
			else
				$order_by='`Product Code File As`';

			if (isset($options['limit']))
				$limit='limit '.$options['limit'];
			else
				$limit='';

			if (isset($options['range'])) {
				list($range1, $range2)=explode(":", strtoupper($options['range']));
				$range_where=sprintf("and ( (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d) || (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d))", ord($range1), ord($range2), ord($range1)+32, ord($range2)+32);

			} else
			$range_where="";//"  true";


		$print_header=true;
		$print_rrp=true;
		if (isset($options['rrp'])) {
			//print 'ok';
			$print_rrp=$options['rrp'];
		}

		$show_unit=true;
		if (isset($options['unit'])) {
			//print 'ok';
			$show_unit=$options['unit'];
		}


		$print_price=true;

		switch ($type) {
		case 'ecommerce':
			$this->url=$ecommerce_url;
			$this->user_id=$username;
			$this->method=$method;
			break;
		case 'inikoo':
			$this->method='sc';
			$this->user=$user;
			break;
		default:
			break;
		}

		$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$number_records=$row['num'];
		} else {
			// NO PRODUCTS
			return;
		}




		if ($this->locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
		}
		if ($this->locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
		}
		elseif ($this->locale=='es_ES') {
			$out_of_stock='Fuera de Stock';
			$discontinued='Fuera de Stock';
		}

		elseif ($this->locale=='fr_FR') {
			$out_of_stock='Rupture de stock';
			$discontinued='Rupture de stock';
		}
		else {
			$out_of_stock='Out of Stock';
			$discontinued='Discontinued';
		}



		$form=sprintf('<table class="product_list form" >' );

		if ($print_header) {

			$rrp_label='';

			if ($print_rrp) {

				$sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) rrp_min, max(`Product RRP`/`Product Units Per Case`) as rrp_max,avg(`Product RRP`/`Product Units Per Case`)  as rrp_avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $this->id);

				$res=mysql_query($sql);
				if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
					$rrp=$row['rrp_min'];


					$rrp= $this->get_formated_rrp(array(
							'Product RRP'=>$rrp,
							'Product Units Per Case'=>1,
							'Product Unit Type'=>''),array('prefix'=>false, 'show_unit'=>$show_unit));



					if ($row['rrp_avg']<=0) {
						$rrp_label='';
						$print_rrp=false;
					}
					elseif ($row['rrp_avg']==$row['rrp_min']) {
						$rrp_label='<br/><span class="rrp">RRP: '.$rrp.'</span>';
						$print_rrp=false;
					}
					else
						$rrp_label='<br/><span class="rrp">RRP from '.$rrp.'</span>';



				} else {
					return;
				}
			}

			if ($print_price) {

				$sql=sprintf("select min(`Product Price`/`Product Units Per Case`) price_min, max(`Product Price`/`Product Units Per Case`) as price_max,avg(`Product Price`/`Product Units Per Case`)  as price_avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $this->id);

				$res=mysql_query($sql);
				if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
					$price=$row['price_min'];


					$price= $this->get_formated_price(array(
							'Product Price'=>$price,
							'Product Units Per Case'=>1,
							'Product Unit Type'=>'',
							'Label'=>($row['price_avg']==$row['price_min']?'price':'from')

						));


					$price_label='<br/><span class="price">'.$price.'</span>';




				} else {
					return;
				}
			}


			$form.='<tr class="list_info" ><td colspan="4"><p>'.$this->data['Product Family Name'].$price_label.$rrp_label.'</p></td><td>';


		}

		if ($this->method=='reload') {

			$form.=sprintf('
                           <form action="%s" method="post">
                           <input type="hidden" name="userid" value="%s">
                           <input type="hidden" name="nnocart"> '
				,$ecommerce_url
				,addslashes($username)

			);
			$counter=1;
			//$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
			$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
			//print $sql;
			$result=mysql_query($sql);
			//while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			foreach ($data as $row) {



				if ($print_rrp) {

					$rrp= $this->get_formated_rrp(array(
							'Product RRP'=>$row['Product RRP'],
							'Product Units Per Case'=>$row['Product Units Per Case'],
							'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

				} else {
					$rrp='';
				}





				if ($row['Product Web State']=='Out of Stock') {
					$class_state='out_of_stock';

					$state=' <span class="out_of_stock">('.$out_of_stock.')</span>';

				}
				elseif ($row['Product Web State']=='Discontinued') {
					$class_state='discontinued';
					$state=' <span class="discontinued">'.$discontinued.'</span>';

				}
				else {

					$class_state='';
					$state='';


				}

				$price= $this->get_formated_price(array(
						'Product Price'=>$row['Product Price'],
						'Product Units Per Case'=>1,
						'Product Unit Type'=>'',
						'Label'=>(''),
						'price per unit text'=>''

					));




				if ($counter==0)
					$tr_class='class="top"';
				else
					$tr_class='';
				$form.=sprintf('<tr %s >
                               <input type="hidden"  name="discountpr%s"     value="1,%.2f"  >
                               <input type="hidden"  name="product%s"  value="%s %s" >
                               <td class="code">%s</td><td class="price">%s</td>
                               <td class="input"><input name="qty%s"  id="qty%s"  type="text" value="" class="%s"  %s ></td>
                               <td class="description">%s %s</td><td class="rrp">%s</td>
                               </tr>'."\n",
					$tr_class,
					$counter,$row['Product Price'],
					$counter,$row['Product Code'],$row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],

					$row['Product Code'],
					$price,
					$counter,
					$counter,
					$class_state,
					($class_state!=''?' readonly="readonly" ':''),
					$row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
					$state,
					$rrp

				);





				$counter++;
			}

			$form.=sprintf('<tr class="space"><td colspan="4">
                           <input type="hidden" name="return" value="%s">
                           <input class="button" name="Submit" type="submit"  value="Order">
                           <input class="button" name="Reset" type="reset"  id="Reset" value="Reset"></td></tr></form></table>
                           '
				,ecommerceURL($secure, $_port, $_protocol, $url, $server));

		} else if ($this->method=='sc') {
				$form.=sprintf('
                           <form action="%s" method="post">
                           <input type="hidden" name="userid" value="%s">
                           <input type="hidden" name="nnocart"> '
					,$ecommerce_url
					,addslashes($username)

				);
				$counter=1;
				//$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
				$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
				//print $sql;
				$result=mysql_query($sql);
				//while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				foreach ($data as $row) {



					if ($print_rrp) {

						$rrp= $this->get_formated_rrp(array(
								'Product RRP'=>$row['Product RRP'],
								'Product Units Per Case'=>$row['Product Units Per Case'],
								'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

					} else {
						$rrp='';
					}





					if ($row['Product Web State']=='Out of Stock') {
						$class_state='out_of_stock';

						$state=' <span class="out_of_stock">('.$out_of_stock.')</span>';

					}
					elseif ($row['Product Web State']=='Discontinued') {
						$class_state='discontinued';
						$state=' <span class="discontinued">'.$discontinued.'</span>';

					}
					else {

						$class_state='';
						$state='';


					}

					$price= $this->get_formated_price(array(
							'Product Price'=>$row['Product Price'],
							'Product Units Per Case'=>1,
							'Product Unit Type'=>'',
							'Label'=>(''),
							'price per unit text'=>''

						));




					if ($counter==0)
						$tr_class='class="top"';
					else
						$tr_class='';

					$sql=sprintf("select * from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process' order by `Order Public ID` DESC", $this->user->get('User Parent Key'));
					$result1=mysql_query($sql);
					if ($row1=mysql_fetch_array($result1))
						$order_exist=true;

					$order_key=$row1['Order Key'];

					$sql=sprintf("select `Order Quantity` from `Order Transaction Fact` where `Order Key`=%d and `Product ID`=%d", $order_key, $row['Product ID']);
					$result1=mysql_query($sql);
					if ($row1=mysql_fetch_array($result1))
						$old_qty=$row1['Order Quantity'];
					else
						$old_qty=0;

					$form.=sprintf('<tr %s >
                               <input type="hidden" id="order_id%d" value="%d">
                               <input type="hidden" id="pid%d" value="%d">
                               <input type="hidden" id="old_qty%d" value="%d">
                               <td class="code">%s</td>
                               <td class="price">%s</td>
                               <td class="input"><input  id="qty%s"  type="text" value="%s" class="%s"  %s ></td>
                               <td><img src="art/icons/basket_add.png" onClick="order_single_product(%d)" style="display:%s"/></td>
                               <td class="description">%s %s</td><td class="rrp">%s</td>
                               <td><span id="loading%d"></span></td>
                               </tr>'."\n",
						$tr_class,

						$row['Product ID'],$order_key,
						$row['Product ID'],$row['Product ID'],
						$row['Product ID'],$old_qty,
						$row['Product Code'],
						$price,

						$row['Product ID'],($old_qty>0?$old_qty:''),
						$class_state,
						($class_state!=''?' readonly="readonly" ':''),
						$path,
						$row['Product ID'], ($class_state!=''?' none ':''),
						$row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
						$state,
						$rrp,
						$row['Product ID']
					);


					$counter++;
				}

				$form.=sprintf('</form></table>');
			}
		return $form;
	}





	function get_product_in_family_no_price_deleteme($data, $header_options=false, $options=false) {

		if (isset($options['order_by']))
			if (strtolower($options['order_by']) == 'price')
				$order_by='`Product RRP`';
			elseif (strtolower($options['order_by']) == 'code')
				$order_by='`Product Code File As`';
			elseif (strtolower($options['order_by']) == 'name')
				$order_by='`Product Name`';
			else
				$order_by='`Product Code File As`';
			else
				$order_by='`Product Code File As`';

			if (isset($options['limit']))
				$limit='limit '.$options['limit'];
			else
				$limit='';

			if (isset($options['range'])) {
				list($range1, $range2)=explode(":", strtoupper($options['range']));
				$range_where=sprintf("and ( (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d) || (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d))", ord($range1), ord($range2), ord($range1)+32, ord($range2)+32);

			} else
			$range_where="";//"  true";


		$show_unit=true;
		if (isset($options['unit'])) {
			//print 'ok';
			$show_unit=$options['unit'];
		}

		$print_header=true;
		$print_rrp=false;
		$print_register=true;

		$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$number_records=$row['num'];
		} else {
			// NO PRODUCTS XXX
			return;
		}

		if ($this->locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
			$register='In order to see prices register';

		}
		if ($this->locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
			$register='In order to see prices register';

		}
		elseif ($this->locale=='es_ES') {
			$out_of_stock='Fuera de Stock';
			$discontinued='Fuera de Stock';
			$register='In order to see prices register';

		}
		elseif ($this->locale=='fr_FR') {
			$out_of_stock='Rupture de stock';
			$discontinued='Rupture de stock';
			$register='In order to see prices register';

		}
		else {
			$out_of_stock='Out of Stock';
			$discontinued='Discontinued';
			$register='Please login to see wholesale prices';
		}
		$form=sprintf('<table class="product_list" >' );

		if ($print_header) {

			$rrp_label='';

			if ($print_rrp) {

				if ($number_records==1) {

				} elseif ($number_records>2) {

					$sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) min, max(`Product RRP`/`Product Units Per Case`) as max ,avg(`Product RRP`/`Product Units Per Case`)  as avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $this->id);
					$res=mysql_query($sql);
					if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
						$rrp=$row['min'];


						$rrp= $this->get_formated_rrp(array(
								'Product RRP'=>$rrp,
								'Product Units Per Case'=>1,
								'Product Unit Type'=>''),array('prefix'=>false,'show_unit'=>$show_unit));

						if ($row['rrp_avg']<=0) {
							$rrp_label='';
							$print_rrp=false;
						}
						if ($row['avg']==$row['min'])
							$rrp_label='<br/>RRP: '.$rrp;
						else
							$rrp_label='<br/>RRP from '.$rrp;



					} else {
						return;
					}

				}

			}


			$form.='<tr class="list_info" ><td colspan="4"><p>'.$this->data['Product Family Name'].$rrp_label.'</p></td><td>';
			if ($print_register and $number_records>10)
				$form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);


		}

		//$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' order by %s %s", $this->id, $order_by, $limit);
		$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
		//print $sql;
		$result=mysql_query($sql);
		$counter=0;
		//while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		foreach ($data as $row) {


			if ($print_rrp) {

				$rrp= $this->get_formated_rrp(array(
						'Product RRP'=>$row['Product RRP'],
						'Product Units Per Case'=>$row['Product Units Per Case'],
						'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

			} else {
				$rrp='';
			}
			if ($row['Product Web State']=='Out of Stock') {
				$class_state='out_of_stock';
				$state=$out_of_stock;

			}
			elseif ($row['Product Web State']=='Discontinued') {
				$class_state='discontinued';
				$state=$discontinued;

			}
			else {

				$class_state='';
				$state='';


			}


			if ($counter==0)
				$tr_class='class="top"';
			else
				$tr_class='';
			$form.=sprintf('<tr %s ><td class="code">%s</td><td class="description">%s</td><td class="rrp">%s</td><td class="%s">%s</td></tr>',
				$tr_class,
				$row['Product Code'],
				$row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
				$rrp,
				$class_state,
				$state
			);


			$counter++;
		}

		if ($print_register)
			$form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);
		$form.=sprintf('</table>');
		return $form;

	}





	function get_period($period,$key) {

		return $this->get($period.' '.$key);
	}








	function get_formated_rrp($data,$options=false) {

		$data=array(
			'Product RRP'=>$data['Product RRP'],
			'Product Units Per Case'=>$data['Product Units Per Case'],
			'Product Currency'=>$this->currency,
			'Product Unit Type'=>$data['Product Unit Type'],
			'locale'=>$this->locale);

		return formated_rrp($data,$options);
	}

	function get_formated_price($data,$options=false) {

		$_data=array(
			'Product Price'=>$data['Product Price'],
			'Product Units Per Case'=>$data['Product Units Per Case'],
			'Product Currency'=>$this->currency,
			'Product Unit Type'=>$data['Product Unit Type'],
			'Label'=>$data['Label'],
			'locale'=>$this->locale,

		);

		if (isset($data['price per unit text']))
			$_data['price per unit text']=$data['price per unit text'];

		return formated_price($_data,$options);
	}







	function strleft1($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}


	function remove_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Family' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("delete from `Image Bridge` where `Subject Type`='Family' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
			mysql_query($sql);
			$this->updated=true;

			$number_images=$this->get_number_of_images();

			if ($number_images==0) {
				$main_image_src='';
				$main_image_key=0;
				$this->data['Product Family Main Image']='art/nopic.png';
				$this->data['Product Family Main Image Key']=$main_image_key;
				$sql=sprintf("update `Product Family Dimension` set `Product Family Main Image`=%s ,`Product Family Main Image Key`=%d where `Product Family Key`=%d",
					prepare_mysql($main_image_src),
					$main_image_key,
					$this->id
				);
				mysql_query($sql);

			}elseif ($row['Is Principal']=='Yes') {

				$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Family' and `Subject Key`=%d  ",$this->id);
				$res2=mysql_query($sql);
				if ($row2=mysql_fetch_assoc($res2)) {
					$this->update_main_image($row2['Image Key']) ;
				}
			}


		} else {
			$this->error=true;
			$this->msg='image not associated';

		}





	}
	function update_image_caption($image_key,$value) {
		$value=_trim($value);



		$sql=sprintf("update `Image Bridge` set `Image Caption`=%s where  `Subject Type`='Family' and `Subject Key`=%d  and `Image Key`=%d"
			,prepare_mysql($value)
			,$this->id,$image_key);
		mysql_query($sql);
		//print $sql;
		if (mysql_affected_rows()) {
			$this->new_value=$value;
			$this->updated=true;
		} else {
			$this->msg=_('No change');

		}

	}

	function get_main_image_key() {

		return $this->data['Product Family Main Image Key'];
	}
	function update_main_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Family' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if (!mysql_num_rows($res)) {
			$this->error=true;
			$this->msg='image not associated';
			//print $this->msg."\n";
		}

		$sql=sprintf("update `Image Bridge` set `Is Principal`='No' where `Subject Type`='Family' and `Subject Key`=%d  ",$this->id);
		mysql_query($sql);
		$sql=sprintf("update `Image Bridge` set `Is Principal`='Yes' where `Subject Type`='Family' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		mysql_query($sql);


		$main_image_src='image.php?id='.$image_key.'&size=small';
		$main_image_key=$image_key;

		$this->data['Product Family Main Image']=$main_image_src;
		$this->data['Product Family Main Image Key']=$main_image_key;
		$sql=sprintf("update `Product Family Dimension` set `Product Family Main Image`=%s ,`Product Family Main Image Key`=%d where `Product Family Key`=%d",
			prepare_mysql($main_image_src),
			$main_image_key,
			$this->id
		);

		mysql_query($sql);

		//print $sql."\n";

		$this->updated=true;

	}



	function post_add_history($history_key,$type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Product Family History Bridge` (`Family Key`,`History Key`,`Type`) values (%d,%d,%s)",
			$this->id,
			$history_key,
			prepare_mysql($type)
		);
		mysql_query($sql);

	}
	
		function get_formated_discounts(){
		$formated_discounts='';
		$sql=sprintf("select `Deal Description`,`Deal Name`,D.`Deal Key`,`Deal Component Allowance Description` from `Deal Target Bridge`  B left join `Deal Component Dimension` DC on (DC.`Deal Component Key`=B.`Deal Component Key`) left join `Deal Dimension` D on (D.`Deal Key`=B.`Deal Key`) where `Subject`='Family' and `Subject Key`=%d ",$this->id,$this->id);
//return $sql;
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$formated_discounts.=', <span title="'.$row['Deal Description'].'"><a href="deal.php?id='.$row['Deal Key'].'">'.$row['Deal Name']. '</a> <b>'.$row['Deal Component Allowance Description'].'</b></span>';
		}
		$formated_discounts=preg_replace('/^, /','',$formated_discounts);
		return $formated_discounts;
	}


}
?>
