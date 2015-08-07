<?php
/*
 File: Department.php

 This file contains the Department Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.Family.php';
include_once 'class.Page.php';

/* class: Department
   Class to manage the *Product Department Dimension* table
*/


class Department extends DB_Table {

	public $new_value=false;


	var $external_DB_link=false;
	function Department($a1=false,$a2=false,$a3=false) {
		$this->table_name='Product Department';
		$this->ignore_fields=array(
			'Product Department Key',
			'Product Department Families',
			'Product Department For Public Sale Products',
			'Product Department For Private Sale Products',
			'Product Department In Process Products',
			'Product Department Not For Sale Products',
			'Product Department Discontinued Products',
			'Product Department Unknown Sales State Products',
			'Product Department Surplus Availability Products',
			'Product Department Optimal Availability Products',
			'Product Department Low Availability Products',
			'Product Department Critical Availability Products',
			'Product Department Out Of Stock Products',
			'Product Department Unknown Stock Products',
			'Product Department Total Acc Invoiced Gross Amount',
			'Product Department Total Acc Invoiced Discount Amount',
			'Product Department Total Acc Invoiced Amount',
			'Product Department Total Acc Profit',
			'Product Department Total Acc Quantity Ordered',
			'Product Department Total Acc Quantity Invoiced',
			'Product Department Total Acc Quantity Delivere',
			'Product Department Total Acc Days On Sale',
			'Product Department Total Acc Days Available',
			'Product Department 1 Year Acc Invoiced Gross Amount',
			'Product Department 1 Year Acc Invoiced Discount Amount',
			'Product Department 1 Year Acc Invoiced Amount',
			'Product Department 1 Year Acc Profit',
			'Product Department 1 Year Acc Quantity Ordered',
			'Product Department 1 Year Acc Quantity Invoiced',
			'Product Department 1 Year Acc Quantity Delivere',
			'Product Department 1 Year Acc Days On Sale',
			'Product Department 1 Year Acc Days Available',
			'Product Department 1 Quarter Acc Invoiced Gross Amount',
			'Product Department 1 Quarter Acc Invoiced Discount Amount',
			'Product Department 1 Quarter Acc Invoiced Amount',
			'Product Department 1 Quarter Acc Profit',
			'Product Department 1 Quarter Acc Quantity Ordered',
			'Product Department 1 Quarter Acc Quantity Invoiced',
			'Product Department 1 Quarter Acc Quantity Delivere',
			'Product Department 1 Quarter Acc Days On Sale',
			'Product Department 1 Quarter Acc Days Available',
			'Product Department 1 Month Acc Invoiced Gross Amount',
			'Product Department 1 Month Acc Invoiced Discount Amount',
			'Product Department 1 Month Acc Invoiced Amount',
			'Product Department 1 Month Acc Profit',
			'Product Department 1 Month Acc Quantity Ordered',
			'Product Department 1 Month Acc Quantity Invoiced',
			'Product Department 1 Month Acc Quantity Delivere',
			'Product Department 1 Month Acc Days On Sale',
			'Product Department 1 Month Acc Days Available',
			'Product Department 1 Week Acc Invoiced Gross Amount',
			'Product Department 1 Week Acc Invoiced Discount Amount',
			'Product Department 1 Week Acc Invoiced Amount',
			'Product Department 1 Week Acc Profit',
			'Product Department 1 Week Acc Quantity Ordered',
			'Product Department 1 Week Acc Quantity Invoiced',
			'Product Department 1 Week Acc Quantity Delivere',
			'Product Department 1 Week Acc Days On Sale',
			'Product Department 1 Week Acc Days Available',
			'Product Department Total Acc Quantity Delivered',
			'Product Department 1 Year Acc Quantity Delivered',
			'Product Department 1 Month Acc Quantity Delivered',
			'Product Department 1 Quarter Acc Quantity Delivered',
			'Product Department 1 Week Acc Quantity Delivered',
			'Product Department Stock Value'


		);

		if (is_numeric($a1) and !$a2  and $a1>0 )
			$this->get_data('id',$a1,false);
		else if ( preg_match('/new|create/i',$a1)) {
				$this->find($a2,'create');
			} else if ( preg_match('/find/i',$a1)) {
				$this->find($a2,$a3);
			}
		elseif ($a2!='')
			$this->get_data($a1,$a2,$a3);

	}


	function find($raw_data,$options) {


		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found=false;
		$this->found_key=false;
		$create=false;
		$update=false;
		if (preg_match('/create/i',$options)) {
			$create=true;
		}
		if (preg_match('/update/i',$options)) {
			$update=true;
		}

		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))
				$data[$key]=_trim($value);
		}



		if ($data['Product Department Code']=='' ) {
			$this->msg=_("Error: Wrong department code");
			$this->error=true;
			return;
		}

		if ($data['Product Department Name']=='') {
			$data['Product Department Name']=$data['Product Department Code'];
			$this->msg=_("Warning: No department name");
		}

		if ( !is_numeric($data['Product Department Store Key']) or $data['Product Department Store Key']<=0 ) {
			$this->error=true;
			$this->msg=_("Error: Incorrect Store Key");
			return;
		}
		$sql=sprintf("select `Product Department Key`from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s "
			,$data['Product Department Store Key']
			,prepare_mysql($data['Product Department Code'])
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->found=true;
			$this->found_key=$row['Product Department Key'];

		}

		if ($this->found)
			$this->get_data('id',$this->found_key);

		if (!$this->found & $create) {
			$this->create($data);
		} else if ($create) {
				$this->msg=_('There is already another department with this code');

			}









	}


	/*
      Function: create
      Crea nuevos registros en la tabla product department dimension, evitando duplicidad de registros.
    */
	// JFA

	function create($data) {



		$this->new=false;

		if ($data['Product Department Name']!='')
			$data['Product Department Name']=$this->name_if_duplicated($data);

		$store=new Store($data['Product Department Store Key']);
		if (!$store->id) {
			$this->error=true;
			exit("error store not ".$data['Product Department Store Key']." found\n");
		}

		$data['Product Department Store Code']=$store->data['Store Code'];
		$data['Product Department Currency Code']=$store->data['Store Currency Code'];

		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if (preg_match('/Product Department Description|Marketing|Slogan/i',$key))
				$values.=prepare_mysql($value,false).",";

			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Product Department Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Department created");
			$this->get_data('id',$this->id,false);
			$this->new=true;

			$sql=sprintf("insert into `Product Department Data Dimension`  (`Product Department Key`) values (%d)",$this->id);
			mysql_query($sql);


			$sql=sprintf("insert into `Product Department Default Currency`  (`Product Department Key`) values (%d)",$this->id);
			mysql_query($sql);


			$this->add_history(array(
					'Action'=>'created'
					,'History Abstract'=>_('Department created')
					,'History Details'=>_('Department')." ".$this->data['Product Department Name']." (".$this->get('Product Department Code').") "._('Created')
				),true);
			$store->editor=$this->editor;
			$store->add_history(array(
					'Action'=>'created'
					,'History Abstract'=>_('Department created')." (".$this->get('Product Department Code').")"
					,'History Details'=>_('Department')." ".$this->data['Product Department Name']." (".$this->get('Product Department Code').") "._('Created')
				),true);

			$store->update_departments();
			return;
		} else {
			$this->error=true;
			$this->msg="Error can not create department. $sql";

		}

	}


	function get_data($tipo,$tag,$tag2=false) {

		switch ($tipo) {
		case('id'):
			$sql=sprintf("select * from `Product Department Dimension` where `Product Department Key`=%d ",$tag);
			break;
		case('code'):
			$sql=sprintf("select * from `Product Department Dimension` where `Product Department Code`=%s and `Product Department Most Recent`='Yes'",prepare_mysql($tag));
			break;
		case('code_store'):
			$sql=sprintf("select * from `Product Department Dimension` where `Product Department Code`=%s and `Product Department Most Recent`='Yes' and `Product Department Store Key`=%d",prepare_mysql($tag),$tag2);

			break;
		default:
			$sql=sprintf("select * from `Product Department Dimension` where `Product Department Type`='Unknown' ");
		}


		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Product Department Key'];

	}

	function load_acc_data() {
		if ($this->id) {
			$sql=sprintf("select * from `Product Department Data Dimension` where `Product Department Key`=%d",$this->id);
			$res =mysql_query($sql);


			if ($row=mysql_fetch_assoc($res)) {
				foreach ($row as $key=>$value) {
					$this->data[$key]=$value;
				}

			}


		}
	}


	function update_sales_type($value) {
		if (
			$value=='Public Sale' or $value=='Private Sale' or $value=='Not For Sale'
		) {
			$sales_state=$value;

			$sql=sprintf("update `Product Department Dimension` set `Product Department Sales Type`=%s  where  `Product Department Key`=%d "
				,prepare_mysql($sales_state)
				,$this->id
			);
			//print $sql;
			if (mysql_query($sql)) {
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				$this->msg=_('Department Sales Type updated');
				$this->updated=true;

				$this->new_value=$value;
				return;
			} else {
				$this->msg=_("Error: Department sales type could not be updated ");
				$this->updated=false;
				return;
			}
		} else
			$this->msg=_("Error: wrong value")." [Sales Type] ($value)";
		$this->updated=false;
	}




	function update_field_switcher($field,$value,$options='') {


		switch ($field) {
		case('Product Department Sales Type'):
			$this->update_sales_type($value);
			break;
		case('Product Department Code'):
			$this->update_code($value);
			break;
		case('Product Department Name'):
			$this->update_name($value);
			break;

		case('Product Department Description'):
			$this->update_description($value,$options);
			break;


		case('Product Department Sticky Note'):
			$this->update_field_switcher('Sticky Note',$value);
			break;
		case('Sticky Note'):
			$this->update_field('Product Department '.$field,$value,'no_null');
			$this->new_value=html_entity_decode($this->new_value);
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

	function update_description($value,$options) {

		$this->update_field('Product Department Description',$value,$options);

		foreach ($this->get_pages_keys() as $page_key  ) {
			$page=new Page($page_key);

			if ($page->data['Page Type']=='Store' and $page->data['Page Store Content Display Type']=='Template') {
				$page->update_store_search();
			}
		}

	}

	function update_code($code) {

		if ($code==$this->data['Product Department Code']) {
			$this->updated=true;
			$this->new_value=$code;
			return;

		}

		if ($code=='') {
			$this->msg=_('Error: Wrong code (empty)');
			return;
		}

		if (!(strtolower($code)==strtolower($this->data['Product Department Code']) and $code!=$this->data['Product Department Code'])) {

			$sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s  COLLATE utf8_general_ci"
				,$this->data['Product Department Store Key']
				,prepare_mysql($code)
			);
			$res=mysql_query($sql);
			$row=mysql_fetch_array($res);
			if ($row['num']>0) {
				$this->msg=_("Error: Another department with the same code");
				return;
			}
		}
		$old_value=$this->get('Product Department Code');
		$sql=sprintf("update `Product Department Dimension` set `Product Department Code`=%s where `Product Department Key`=%d "
			,prepare_mysql($code)
			,$this->id
		);
		if (mysql_query($sql)) {
			$this->msg=_('Department code updated');
			$this->updated=true;
			$this->new_value=$code;

			$this->data['Product Department Code']=$code;


			$sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Code`=%s where `Product Family Main Department Key`=%d ",
				prepare_mysql($code),
				$this->id
			);
			mysql_query($sql);

			$sql=sprintf("update `Product Dimension` set `Product Main Department Code`=%s where `Product Main Department Key`=%d ",
				prepare_mysql($code),
				$this->id
			);
			mysql_query($sql);


			$editor_data=$this->get_editor_data();


			$this->add_history(array(
					'Indirect Object'=>'Product Department Code'
					,'History Abstract'=>_('Product Department Changed').' ('.$this->get('Product Department Name').')'
					,'History Details'=>_('Store')." ".$this->data['Product Department Name']." "._('code changed from').' '.$old_value." "._('to').' '. $this->get('Product Department Code')
				));




		} else {
			$this->msg="Error: Department code could not be updated";

			$this->updated=false;

		}

	}

	function update_name($name) {
		if ($name==$this->data['Product Department Name']) {
			$this->updated=true;
			$this->new_value=$name;
			return;

		}

		if ($name=='') {
			$this->msg=_('Error: Wrong name (empty)');
			return;
		}
		$sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Name`=%s  COLLATE utf8_general_ci"
			,$this->data['Product Department Store Key']
			,prepare_mysql($name)
		);
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res);
		if ($row['num']>0) {
			$this->msg=_("Error: Another department with the same name");
			return;
		}
		$old_value=$this->get('Product Department Name');
		$sql=sprintf("update `Product Department Dimension` set `Product Department Name`=%s where `Product Department Key`=%d "
			,prepare_mysql($name)
			,$this->id
		);


		if (mysql_query($sql)) {
			$this->msg=_('Department name updated');
			$this->updated=true;
			$this->new_value=$name;
			$this->data['Product Department Name']=$name;


			$sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Name`=%s where `Product Family Main Department Key`=%d ",
				prepare_mysql($name),
				$this->id
			);
			mysql_query($sql);

			$sql=sprintf("update `Product Dimension` set `Product Main Department Name`=%s where `Product Main Department Key`=%d ",
				prepare_mysql($name),
				$this->id
			);
			mysql_query($sql);


			$this->add_history(array(
					'Indirect Object'=>'Product Department Name',
					'History Abstract'=>_('Product Department Name Changed').' ('.$this->get('Product Department Name').')',
					'History Details'=>_('Product Department')." ("._('Code').":".$this->data['Product Department Code'].") "._('name changed from').' '.$old_value." "._('to').' '. $this->get('Product Department Name')
				));


			foreach ($this->get_pages_keys() as $page_key  ) {
				$page=new Page($page_key);

				if ($page->data['Page Type']=='Store' and $page->data['Page Store Content Display Type']=='Template') {
					$page->update(array('Page Store Title'=>$this->data['Product Department Name']));
					$page->update_store_search();
				}
			}

		} else {
			$this->msg="Error: Department name could not be updated";

			$this->updated=false;

		}

	}

	function delete() {
		$this->deleted=false;
		$this->update_product_data();
		$store=new Store($this->data['Product Department Store Key']);
		$store->editor=$this->editor;
		if ($this->get('Total Products')==0) {
			$store=new Store($this->data['Product Department Store Key']);
			$sql=sprintf("delete from `Product Department Dimension` where `Product Department Key`=%d",$this->id);
			if (mysql_query($sql)) {

				$this->deleted=true;


				$store->add_history(array(
						'Action'=>'deleted'
						,'History Abstract'=>_('Department deleted')." (".$this->get('Product Department Code').")"
						,'History Details'=>_('Department')." ".$this->data['Product Department Name']." (".$this->get('Product Department Code').") "._('deleted')
					),true);

				$store->update_departments();

			} else {

				$this->msg='Error: can not delete department';
				return;
			}

			$this->deleted=true;
		} else {//when families are associated with this department
			//$this->msg=_('Department can not be deleted because it has associated some products');
			$move_all_products = true;


			$sql = sprintf("select * from `Product Family Dimension` where `Product Family Main Department Key` = %d", $this->id);
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$family = new Family($row['Product Family Key']);
				$family->update_department($store->data['Store No Products Department Key']);
				if (!$family->updated) {
					$move_all_products&=false;
				}
			}


			if ($move_all_products) {
				$sql=sprintf("delete from `Product Department Dimension` where `Product Department Key`=%d",$this->id);
				mysql_query($sql);
			}

			if (mysql_affected_rows()>0) {
				$this->deleted=true;

				$store->add_history(array(
						'Action'=>'deleted'
						,'History Abstract'=>_('Department deleted')." (".$this->get('Product Department Code').")"
						,'History Details'=>_('Department')." ".$this->data['Product Department Name']." (".$this->get('Product Department Code').") "._('deleted')
					),true);



			} else {
				$this->deleted_msg='Error department can not be deleted';
			}
		}
	}

	function get_family_keys() {
		$family_keys=array();
		$sql=sprintf('select `Product Family Key` from `Product Family Dimension` where `Product Family Main Department Key`=%d',
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$family_keys[]=$row['Product Family Key'];
		}
		return $family_keys;

	}

	function get_period($period,$key) {
		return $this->get($period.' '.$key);
	}

	function get($key) {

		if (array_key_exists($key,$this->data))
			return $this->data[$key];

		if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Amount|Profit)$/',$key)) {

			$amount='Product Department '.$key;


			return money($this->data[$amount]);
		}
		if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers)$/',$key)) {

			$amount='Product Department '.$key;

			return number($this->data[$amount]);
		}

		switch ($key) {
		case("Sticky Note"):
			return nl2br($this->data['Product Department Sticky Note']);
			break;
		case('For Public For Sale Families'):
			return number($this->data['Product Department For Public For Sale Families']);
			break;
		case('For Public Discontinued Families'):
			return number($this->data['Product Department For Public Discontinued Families']);
			break;
		case('For Sale Products'):
			return number($this->data['Product Department For Public Sale Products']);
			break;
		case('Families'):
			return number($this->data['Product Department Families']);
			break;


		case('Total Products'):
			return $this->data['Product Department For Public Sale Products']+$this->data['Product Department For Private Sale Products']+$this->data['Product Department In Process Products']+$this->data['Product Department Not For Sale Products']+$this->data['Product Department Discontinued Products']+$this->data['Product Department Unknown Sales State Products'];
			break;

			//   case('weeks'):
			//      $_diff_seconds=date('U')-$this->data['first_date'];
			//      $day_diff=$_diff_seconds/24/3600;
			//      $weeks=$day_diff/7;
			//      return $weeks;
		}

	}


	function add_product($product_id,$args=false) {


		$product=new Product($product_id);
		if ($product->id) {
			$sql=sprintf("insert into `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$this->id);
			mysql_query($sql);
			$this->update_product_data();


			if (preg_match('/principal/',$args)) {
				$sql=sprintf("update  `Product Dimension` set `Product Main Department Key`=%d ,`Product Main Department Code`=%s,`Product Main Department Name`=%s where `Product ID`=%d    "
					,$this->id
					,prepare_mysql($this->get('Product Department Code'))
					,prepare_mysql($this->get('Product Department Name'))
					,$product->pid);

				mysql_query($sql);
			}
		}
	}

	/*
       Method: add_family
       Agrega registros a la tabla Product Family Department Bridge, actualiza la tabla Product Department Dimension, Product Family Dimension
    */
	// JFA

	function add_family($family_id,$args=false) {
		$family=new Family($family_id);
		if ($family->id) {
			$sql=sprintf("insert into `Product Family Department Bridge` (`Product Family Key`,`Product Department Key`) values (%d,%d)",$family->id,$this->id);
			mysql_query($sql);

			$sql=sprintf("select count(*) as num from `Product Family Department Bridge`  where `Product Department Key`=%d",$this->id);
			$result=mysql_query($sql);
			if ($row=mysql_fetch_assoc($result)) {
				$sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d   where `Product Department Key`=%d  ",
					$row['num'],
					$this->id
				);
				//  print "$sql\n";exit;
				mysql_query($sql);
			}
			if (!preg_match('/noproduct/i',$args) ) {
				foreach ($family->get('products') as $key => $value) {
					$this->add_product($key,$args);
				}
			}

			if (preg_match('/principal/',$args)) {
				$sql=sprintf("update  `Product Family Dimension` set `Product Family Main Department Key`=%d ,`Product Family Main Department Code`=%s,`Product Family Main Department Name`=%s where `Product Family Key`=%s    "
					,$this->id
					,prepare_mysql($this->get('Product Department Code'))
					,prepare_mysql($this->get('Product Department Name'))
					,$family->id);
				mysql_query($sql);
			}
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
		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_interval_dates($interval);



		setlocale(LC_ALL, 'en_GB');

		//   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

		$this->data["Product Department $db_interval Acc Invoiced Discount Amount"]=0;
		$this->data["Product Department $db_interval Acc Invoiced Amount"]=0;
		$this->data["Product Department $db_interval Acc Invoices"]=0;
		$this->data["Product Department $db_interval Acc Profit"]=0;
		$this->data["Product Department $db_interval Acc Customers"]=0;
		$this->data["Product Department $db_interval Acc Quantity Ordered"]=0;
		$this->data["Product Department $db_interval Acc Quantity Invoiced"]=0;
		$this->data["Product Department $db_interval Acc Quantity Delivered"]=0;
		$this->data["Product Department DC $db_interval Acc Invoiced Amount"]=0;
		$this->data["Product Department DC $db_interval Acc Invoiced Discount Amount"]=0;
		$this->data["Product Department DC $db_interval Acc Invoiced Gross Amount"]=0;
		$this->data["Product Department DC $db_interval Acc Profit"]=0;

		$sql=sprintf("select  sum(`Shipped Quantity`) as qty_delivered,sum(`Order Quantity`) as qty_ordered,sum(`Invoice Quantity`) as qty_invoiced ,count(Distinct `Customer Key`)as customers,count(distinct `Invoice Key`) as invoices,IFNULL(sum(`Invoice Transaction Total Discount Amount`),0) as discounts,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net  ,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) as total_cost ,
                     sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`) as dc_discounts,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net,sum((`Invoice Transaction Gross Amount`)*`Invoice Currency Exchange Rate`) dc_gross  ,sum((`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`)*`Invoice Currency Exchange Rate`) as dc_total_cost from `Order Transaction Fact` where `Product Department Key`=%d and `Invoice Date`>=%s %s" ,
			$this->id,
			prepare_mysql($from_date),
			($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')

		);

		$result=mysql_query($sql);

		//print $sql."\n\n";
		if ($row=mysql_fetch_assoc($result)) {
			$this->data["Product Department $db_interval Acc Invoiced Discount Amount"]=$row["discounts"];
			$this->data["Product Department $db_interval Acc Invoiced Amount"]=$row["net"];
			$this->data["Product Department $db_interval Acc Invoices"]=$row["invoices"];
			$this->data["Product Department $db_interval Acc Profit"]=$row["net"]-$row['total_cost'];
			$this->data["Product Department $db_interval Acc Customers"]=$row["customers"];
			$this->data["Product Department $db_interval Acc Quantity Ordered"]=$row["qty_ordered"];
			$this->data["Product Department $db_interval Acc Quantity Invoiced"]=$row["qty_invoiced"];
			$this->data["Product Department $db_interval Acc Quantity Delivered"]=$row["qty_delivered"];

			$this->data["Product Department DC $db_interval Acc Invoiced Amount"]=$row["dc_net"];
			$this->data["Product Department DC $db_interval Acc Invoiced Discount Amount"]=$row["dc_discounts"];
			$this->data["Product Department DC $db_interval Acc Invoiced Gross Amount"]=$row["dc_gross"];

			$this->data["Product Department DC $db_interval Acc Profit"]=$row["dc_net"]-$row['dc_total_cost'];
		}

		$sql=sprintf("update `Product Department Data Dimension` set
                     `Product Department $db_interval Acc Invoiced Discount Amount`=%.2f,
                     `Product Department $db_interval Acc Invoiced Amount`=%.2f,
                     `Product Department $db_interval Acc Invoices`=%d,
                     `Product Department $db_interval Acc Profit`=%.2f,
                      `Product Department $db_interval Acc Customers`=%d,
                       `Product Department $db_interval Acc Quantity Ordered`=%d,
                       `Product Department $db_interval Acc Quantity Invoiced`=%d,
                       `Product Department $db_interval Acc Quantity Delivered`=%d
                     where `Product Department Key`=%d "
			,$this->data["Product Department $db_interval Acc Invoiced Discount Amount"]
			,$this->data["Product Department $db_interval Acc Invoiced Amount"]
			,$this->data["Product Department $db_interval Acc Invoices"]
			,$this->data["Product Department $db_interval Acc Profit"]
			,$this->data["Product Department $db_interval Acc Customers"]
			,$this->data["Product Department $db_interval Acc Quantity Ordered"]
			,$this->data["Product Department $db_interval Acc Quantity Invoiced"]
			,$this->data["Product Department $db_interval Acc Quantity Delivered"]

			,$this->id
		);

		mysql_query($sql);
		//print $sql."\n\n";

		$sql=sprintf("update `Product Department Default Currency` set
                             `Product Department DC $db_interval Acc Invoiced Discount Amount`=%.2f,
                             `Product Department DC $db_interval Acc Invoiced Gross Amount`=%.2f,
                             `Product Department DC $db_interval Acc Invoiced Amount`=%.2f,
                             `Product Department DC $db_interval Acc Profit`=%.2f
                             where `Product Department Key`=%d "
			,$this->data["Product Department DC $db_interval Acc Invoiced Discount Amount"]
			,$this->data["Product Department DC $db_interval Acc Invoiced Gross Amount"]

			,$this->data["Product Department DC $db_interval Acc Invoiced Amount"]
			,$this->data["Product Department DC $db_interval Acc Profit"]
			,$this->id
		);

		mysql_query($sql);

		if ($from_date_1yb) {
			$this->data["Product Department $db_interval Acc 1YB Invoices"]=0;
			$this->data["Product Department $db_interval Acc 1YB Invoiced Discount Amount"]=0;
			$this->data["Product Department $db_interval Acc 1YB Invoiced Amount"]=0;
			$this->data["Product Department $db_interval Acc 1YB Profit"]=0;
			$this->data["Product Department $db_interval Acc 1YB Invoiced Delta"]=0;

			$sql=sprintf("select count(distinct `Invoice Key`) as invoices,IFNULL(sum(`Invoice Transaction Total Discount Amount`),0) as discounts,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net  ,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) as total_cost ,
                         sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`) as dc_discounts,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net  ,sum((`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`)*`Invoice Currency Exchange Rate`) as dc_total_cost from `Order Transaction Fact` where `Product Department Key`=%d and `Invoice Date`>=%s %s" ,
				$this->id,
				prepare_mysql($from_date_1yb),
				($to_1yb?sprintf('and `Invoice Date`<%s',prepare_mysql($to_1yb)):'')

			);


			$result=mysql_query($sql);
			if ($row=mysql_fetch_assoc($result)) {
				$this->data["Product Department $db_interval Acc 1YB Invoiced Discount Amount"]=$row["discounts"];
				$this->data["Product Department $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
				$this->data["Product Department $db_interval Acc 1YB Invoiced Delta"]=($row["net"]==0?-1000000:$this->data["Product Department $db_interval Acc Invoiced Amount"]/$row["net"]);

				$this->data["Product Department $db_interval Acc 1YB Invoices"]=$row["invoices"];
				$this->data["Product Department $db_interval Acc 1YB Profit"]=$row["net"]-$row['total_cost'];

			}

			$sql=sprintf("update `Product Department Data Dimension` set
                         `Product Department $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Product Department $db_interval Acc 1YB Invoiced Amount`=%.2f,
                        `Product Department $db_interval Acc 1YB Invoiced Delta`=%f,
                         `Product Department $db_interval Acc 1YB Invoices`=%.2f,
                         `Product Department $db_interval Acc 1YB Profit`=%.2f
                         where `Product Department Key`=%d "
				,$this->data["Product Department $db_interval Acc 1YB Invoiced Discount Amount"]
				,$this->data["Product Department $db_interval Acc 1YB Invoiced Amount"]
				,$this->data["Product Department $db_interval Acc 1YB Invoiced Delta"]
				,$this->data["Product Department $db_interval Acc 1YB Invoices"]
				,$this->data["Product Department $db_interval Acc 1YB Profit"]
				,$this->id
			);

			mysql_query($sql);
			//print "$sql\n";


		}

		return array(substr($from_date, -19,-9), date("Y-m-d"));

	}



	function name_if_duplicated($data) {

		$sql=sprintf("select * from `Product Department Dimension` where `Product Department Name`=%s  and `Product Department Store Key`=%d "
			,prepare_mysql($data['Product Department Name'])
			,$data['Product Department Store Key']
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$s_char=$row['Product Department Name'];
			$number=1;
			$sql=sprintf("select * from `Product Department Dimension` where `Product Department Name` like '%s (%%)'  and `Product Department Store Key`=%d "
				,addslashes($data['Product Department Name'])
				,$data['Product Department Store Key']
			);
			$result2=mysql_query($sql);

			while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {

				if (preg_match('/\(\d+\)$/',$row2['Product Department Name'],$match))
					$_number=preg_replace('/[^\d]/','',$match[0]);
				if ($_number>$number)
					$number=$_number;
			}

			$number++;

			return $data['Product Department Name']." ($number)";

		} else {
			return $data['Product Department Name'];
		}


	}


	function update_averages_per_item() {


		$sql=sprintf("select *  from `Product Dimension` where `Product Main Department Key`=%d  ",$this->id);
		$res=mysql_query($sql);
		$avg_weekly_sales_per_product=0;
		$avg_weekly_sales_per_product_1y=0;
		$avg_weekly_sales_per_product_1q=0;
		$avg_weekly_sales_per_product_1m=0;
		$avg_weekly_sales_per_product_1w=0;
		$avg_weekly_profit_per_product=0;
		$avg_weekly_profit_per_product_1y=0;
		$avg_weekly_profit_per_product_1q=0;
		$avg_weekly_profit_per_product_1m=0;
		$avg_weekly_profit_per_product_1w=0;
		$count=0;
		$count_1y=0;
		$count_1q=0;
		$count_1m=0;
		$count_1w=0;
		while ($row=mysql_fetch_array($res)) {
			if ( $row['Product Total Days On Sale']>0) {
				$avg_weekly_sales_per_product+=7*$row['Product Total Invoiced Amount']/$row['Product Total Days On Sale'];
				$avg_weekly_profit_per_product+=7*$row['Product Total Profit']/$row['Product Total Days On Sale'];
				$count++;
			}
			if ( $row['Product 1 Year Acc Days On Sale']>0) {
				$avg_weekly_sales_per_product_1y+=7*$row['Product 1 Year Acc Invoiced Amount']/$row['Product 1 Year Acc Days On Sale'];
				$avg_weekly_profit_per_product_1y+=7*$row['Product 1 Year Acc Profit']/$row['Product 1 Year Acc Days On Sale'];
				$count_1y++;
			}
			if ( $row['Product 1 Quarter Acc Days On Sale']>0) {
				$avg_weekly_sales_per_product_1q+=7*$row['Product 1 Quarter Acc Invoiced Amount']/$row['Product 1 Quarter Acc Days On Sale'];
				$avg_weekly_profit_per_product_1q+=7*$row['Product 1 Quarter Acc Profit']/$row['Product 1 Quarter Acc Days On Sale'];
				$count_1q++;
			}
			if ( $row['Product 1 Month Acc Days On Sale']>0) {
				$avg_weekly_sales_per_product_1m+=7*$row['Product 1 Month Acc Invoiced Amount']/$row['Product 1 Month Acc Days On Sale'];
				$avg_weekly_profit_per_product_1m+=7*$row['Product 1 Month Acc Profit']/$row['Product 1 Month Acc Days On Sale'];
				$count_1m++;
			}
			if ( $row['Product 1 Week Acc Days On Sale']>0) {
				$avg_weekly_sales_per_product_1w+=7*$row['Product 1 Week Acc Invoiced Amount']/$row['Product 1 Week Acc Days On Sale'];
				$avg_weekly_profit_per_product_1w+=7*$row['Product 1 Week Acc Profit']/$row['Product 1 Week Acc Days On Sale'];

				$count_1w++;

			}

		}
		if ($count!=0) {
			$avg_weekly_sales_per_product=$avg_weekly_sales_per_product/$count;
			$avg_weekly_sales_per_product_1y/=$count_1y;
			$avg_weekly_sales_per_product_1q/=$count_1q;
			$avg_weekly_sales_per_product_1m/=$count_1m;
			$avg_weekly_sales_per_product_1w/=$count_1w;
			$avg_weekly_profit_per_product/=$count;
			$avg_weekly_profit_per_product_1y/=$count_1y;
			$avg_weekly_profit_per_product_1q/=$count_1q;
			$avg_weekly_profit_per_product_1m/=$count_1m;
			$avg_weekly_profit_per_product_1w/=$count_1w;


		}

		$this->data['Product Department Total Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product;
		$this->data['Product Department Total Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product;
		$this->data['Product Department 1 Year Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1y;
		$this->data['Product Department 1 Year Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1y;
		$this->data['Product Department 1 Quarter Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1q;
		$this->data['Product Department 1 Quarter Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1q;
		$this->data['Product Department 1 Month Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1m;
		$this->data['Product Department 1 Month Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1m;
		$this->data['Product Department 1 Week Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1w;
		$this->data['Product Department 1 Week Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1w;


		$sql=sprintf("update `Product Department Dimension` set `Product Department Total Acc Avg Week Sales Per Product`=%.2f , `Product Department Total Acc Avg Week Profit Per Product`=%.2f ,`Product Department 1 Year Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Year Acc Avg Week Profit Per Product`=%.2f,`Product Department 1 Quarter Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Quarter Acc Avg Week Profit Per Product`=%.2f,`Product Department 1 Month Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Month Acc Avg Week Profit Per Product`=%.2f ,`Product Department 1 Week Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Week Acc Avg Week Profit Per Product`=%.2f where `Product Department Key`=%d   "
			,$this->data['Product Department Total Acc Avg Week Sales Per Product']
			,$this->data['Product Department Total Acc Avg Week Profit Per Product']
			,$this->data['Product Department 1 Year Acc Avg Week Sales Per Product']
			,$this->data['Product Department 1 Year Acc Avg Week Profit Per Product']
			,$this->data['Product Department 1 Quarter Acc Avg Week Sales Per Product']
			,$this->data['Product Department 1 Quarter Acc Avg Week Profit Per Product']
			,$this->data['Product Department 1 Month Acc Avg Week Sales Per Product']
			,$this->data['Product Department 1 Month Acc Avg Week Profit Per Product']
			,$this->data['Product Department 1 Week Acc Avg Week Sales Per Product']
			,$this->data['Product Department 1 Week Acc Avg Week Profit Per Product']

			,$this->id);
		mysql_query($sql);
		//print "$sql\n";
	}


	function update_product_data() {
		$sql=sprintf("select sum(if(`Product Stage`='In process',1,0)) as in_process,sum(if(`Product Sales Type`='Unknown',1,0)) as sale_unknown, sum(if(`Product Availability Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as public_sale,sum(if(`Product Sales Type`='Private Sale',1,0)) as private_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Main Department Key`=%d",$this->id);

		$availability='No Applicable';
		$sales_type='No Applicable';
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


		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {

			$in_process=$row['in_process'];
			$public_sale=$row['public_sale'];
			$private_sale=$row['private_sale'];
			$discontinued=$row['discontinued'];
			$not_for_sale=$row['not_for_sale'];
			$sale_unknown=$row['sale_unknown'];
			$availability_optimal=$row['availability_optimal'];
			$availability_low=$row['availability_low'];
			$availability_critical=$row['availability_critical'];
			$availability_outofstock=$row['availability_outofstock'];
			$availability_unknown=$row['availability_unknown'];
			$availability_surplus=$row['availability_surplus'];





		}

		$sql=sprintf("update `Product Department Dimension` set `Product Department In Process Products`=%d,`Product Department For Public Sale Products`=%d ,`Product Department For Private Sale Products`=%d,`Product Department Discontinued Products`=%d ,`Product Department Not For Sale Products`=%d ,`Product Department Unknown Sales State Products`=%d, `Product Department Optimal Availability Products`=%d , `Product Department Low Availability Products`=%d ,`Product Department Critical Availability Products`=%d ,`Product Department Out Of Stock Products`=%d,`Product Department Unknown Stock Products`=%d ,`Product Department Surplus Availability Products`=%d  where `Product Department Key`=%d  ",
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

			// prepare_mysql($sales_type),
			// prepare_mysql($availability),
			$this->id
		);

		mysql_query($sql);
		// print "$sql\n";



		$this->get_data('id',$this->id);
	}

	function update_customers() {
		$number_active_customers=0;
		$number_active_customers_more_than_75=0;
		$number_active_customers_more_than_50=0;
		$number_active_customers_more_than_25=0;

		$sql=sprintf(" select
		(select sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)  from  `Order Transaction Fact`  where  `Order Transaction Fact`.`Customer Key`=OTF.`Customer Key` ) as total_amount  ,
		 sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount,
		 OTF.`Customer Key` from `Order Transaction Fact`  OTF  left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`)where `Product Department Key`=%d and `Customer Type by Activity` in ('New','Active') and `Invoice Transaction Gross Amount`>0
		  group by  OTF.`Customer Key`",$this->id);
		// print "$sql\n";
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {
			$number_active_customers++;
			if ($row['total_amount']!=0 and ($row['amount']/$row['total_amount'])>0.75 )
				$number_active_customers_more_than_75++;
			if ($row['total_amount']!=0 and ($row['amount']/$row['total_amount'])>0.5 )
				$number_active_customers_more_than_50++;
			if ($row['total_amount']!=0 and ($row['amount']/$row['total_amount'])>0.25 )
				$number_active_customers_more_than_25++;		
				
		}

		$this->data['Product Department Active Customers']=$number_active_customers;
		$this->data['Product Department Active Customers More 0.75 Share']=$number_active_customers_more_than_75;
		$this->data['Product Department Active Customers More 0.5 Share']=$number_active_customers_more_than_50;
		$this->data['Product Department Active Customers More 0.25 Share']=$number_active_customers_more_than_25;

		$sql=sprintf("update `Product Department Dimension` set `Product Department Active Customers`=%d ,
		`Product Department Active Customers More 0.75 Share`=%d,
				`Product Department Active Customers More 0.5 Share`=%d,
		`Product Department Active Customers More 0.25 Share`=%d

		where `Product Department Key`=%d  ",
			$this->data['Product Department Active Customers'],
			$this->data['Product Department Active Customers More 0.75 Share'],
			$this->data['Product Department Active Customers More 0.5 Share'],
			$this->data['Product Department Active Customers More 0.25 Share'],
			$this->id
		);
		// print "$sql\n";
		mysql_query($sql);

	}

	function update_families() {
		$sql=sprintf("select count(*) as num from `Product Family Dimension`  where`Product Family Main Department Key`=%d",$this->id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$this->data['Product Department Families']=$row['num'];
			$sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d  where `Product Department Key`=%d  ",
				$this->data['Product Department Families'],
				$this->id
			);
			mysql_query($sql); //print "$sql\n";
		}

		$sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Main Department Key`=%d and `Product Family Sales Type`='Public Sale' and `Product Family Record Type` in ('New','Normal','Discontinuing')  ",$this->id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$this->data['Product Department For Public For Sale Families']=$row['num'];
			$sql=sprintf("update `Product Department Dimension` set `Product Department For Public For Sale Families`=%d  where `Product Department Key`=%d  ",
				$this->data['Product Department For Public For Sale Families'],
				$this->id
			);
			mysql_query($sql); //print "$sql\n";
		}

		$sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Main Department Key`=%d  and `Product Family Sales Type`='Public Sale' and `Product Family Record Type`='Discontinued'    "   ,$this->id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$this->data['Product Department For Public Discontinued Families']=$row['num'];
			$sql=sprintf("update `Product Department Dimension` set `Product Department For Public Discontinued Families`=%d  where `Product Department Key`=%d  ",
				$this->data['Product Department For Public Discontinued Families'],
				$this->id
			);
			//   print "$sql\n";
			mysql_query($sql);
		}


	}















	function remove_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Department' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("delete from `Image Bridge` where `Subject Type`='Department' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
			mysql_query($sql);
			$this->updated=true;

			$number_images=$this->get_number_of_images();

			if ($number_images==0) {
				$main_image_src='';
				$main_image_key=0;
				$this->data['Product Department Main Image']='art/nopic.png';
				$this->data['Product Department Main Image Key']=$main_image_key;
				$sql=sprintf("update `Product Department Dimension` set `Product Department Main Image`=%s ,`Product Department Main Image Key`=%d where `Product Department Key`=%d",
					prepare_mysql($main_image_src),
					$main_image_key,
					$this->id
				);

				mysql_query($sql);
			}
			elseif ($row['Is Principal']=='Yes') {

				$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Department' and `Subject Key`=%d  ",$this->id);
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



		$sql=sprintf("update `Image Bridge` set `Image Caption`=%s where  `Subject Type`='Department' and `Subject Key`=%d  and `Image Key`=%d"
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

		return $this->data['Product Department Main Image Key'];
	}
	function update_main_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Department' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if (!mysql_num_rows($res)) {
			$this->error=true;
			$this->msg='image not associated';
		}

		$sql=sprintf("update `Image Bridge` set `Is Principal`='No' where `Subject Type`='Department' and `Subject Key`=%d  ",$this->id);
		mysql_query($sql);
		$sql=sprintf("update `Image Bridge` set `Is Principal`='Yes' where `Subject Type`='Department' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		mysql_query($sql);


		$main_image_src='image.php?id='.$image_key.'&size=small';
		$main_image_key=$image_key;

		$this->data['Product Department Main Image']=$main_image_src;
		$this->data['Product Department Main Image Key']=$main_image_key;
		$sql=sprintf("update `Product Department Dimension` set `Product Department Main Image`=%s ,`Product Department Main Image Key`=%d where `Product Department Key`=%d",
			prepare_mysql($main_image_src),
			$main_image_key,
			$this->id
		);

		mysql_query($sql);

		$page_keys=$this->get_pages_keys();
		foreach ($page_keys as $page_key) {
			$page=new Page($page_key);
			$page->update_image_key();
		}



		$this->updated=true;

	}
	function get_number_of_images() {
		$number_of_images=0;
		$sql=sprintf("select count(*) as num from `Image Bridge` where `Subject Type`='Department' and `Subject Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_of_images=$row['num'];
		}
		return $number_of_images;
	}
	function add_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Department' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->nochange=true;
			$this->msg=_('Image already uploaded');
			return;
		}


		$number_images=$this->get_number_of_images();
		if ($number_images==0) {
			$principal='Yes';
		} else {
			$principal='No';
		}

		$sql=sprintf("insert into `Image Bridge` values ('Department',%d,%d,%s,'')"
			,$this->id
			,$image_key
			,prepare_mysql($principal)

		);

		mysql_query($sql);

		if ($principal=='Yes') {
			$this->update_main_image($image_key);
		}


		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Department' and   `Subject Key`=%d and  PIB.`Image Key`=%d"
			,$this->id
			,$image_key
		);

		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			if ($row['Image Height']!=0)
				$ratio=$row['Image Width']/$row['Image Height'];
			else
				$ratio=1;

			include_once 'common_units_functions.php';
			$this->new_value=array(
				'name'=>$row['Image Filename'],
				'small_url'=>'image.php?id='.$row['Image Key'].'&size=small',
				'thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail',
				'filename'=>$row['Image Filename'],
				'ratio'=>$ratio,
				'caption'=>$row['Image Caption'],
				'is_principal'=>$row['Is Principal'],
				'id'=>$row['Image Key'],
				'size'=>formatSizeUnits($row['Image File Size']
				)
			);  }

		$this->updated=true;
		$this->msg=_("image added");
	}


	function get_pages_keys() {
		$page_keys=array();
		$sql=sprintf("Select `Page Key` from `Page Store Dimension` where `Page Store Section Type`='Department' and  `Page Parent Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$page_keys[]=$row['Page Key'];
		}
		return $page_keys;
	}

	function get_sales_delta($interval) {
		$delta=  delta($this->data["Product Department $interval Acc Invoiced Amount"],$this->data["Product Department $interval Acc 1YB Invoiced Amount"]);
		;
		if ($this->data["Product Department $interval Acc 1YB Invoiced Amount"]>$this->data["Product Department $interval Acc Invoiced Amount"]) {
			return "<span style='color:red'>$delta</span>";
		}else {
			return $delta;
		}

	}

	function post_add_history($history_key,$type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Product Department History Bridge` (`Department Key`,`History Key`,`Type`) values (%d,%d,%s)",
			$this->id,
			$history_key,
			prepare_mysql($type)
		);
		mysql_query($sql);

	}

	function get_valid_to() {
		/*
	To do discintinued Department
	if($this->data['Product Department Record Type']=='Discontinued'){
			return $this->data['Product Department Valid To'];
		}else{
			return gmdate("Y-m-d H:i:s");
		}

		*/
		return gmdate("Y-m-d H:i:s");

	}

	function update_sales_averages() {

		include_once 'common_stat_functions.php';

		$sql=sprintf("select sum(`Sales`) as sales,sum(`Availability`) as availability  from `Order Spanshot Fact` where `Product Department Key`=%d   group by `Date`;",
			$this->id
		);
		$res=mysql_query($sql);

		$counter_available=0;
		$counter=0;
		$sum=0;
		while ($row=mysql_fetch_assoc($res)) {

			$sum+=$row['sales'];
			$counter++;
			if ($row['sales']==$row['availability']) {
				$counter_available++;
			}


		}


		if ($counter>0) {
			$this->data['Product Department Number Days on Sale']=$counter;
			$this->data['Product Department Avg Day Sales']=$sum/$counter;
			$this->data['Product Department Number Days Available']=$counter_available;

		}else {
			$this->data['Product Department Number Days on Sale']=0;
			$this->data['Product Department Avg Day Sales']=0;
			$this->data['Product Department Number Days Available']=0;


		}

		$sql=sprintf("select sum(`Sales`) as sales  from `Order Spanshot Fact` where `Product Department Key`=%d and sales>0  group by `Date`;",
			$this->id
		);
		$res=mysql_query($sql);
		$data_sales=array();
		$max_value=0;
		$counter=0;
		$sum=0;
		while ($row=mysql_fetch_assoc($res)) {
			$data_sales[]=$row['sales'];
			$sum+=$row['sales'];
			$counter++;
			if ($row['sales']>$max_value) {
				$max_value=$row['sales'];
			}
		}


		if ($counter>0) {






			$this->data['Product Department Number Days with Sales']=$counter;
			$this->data['Product Department Avg with Sale Day Sales']=$sum/$counter;
			$this->data['Product Department STD with Sale Day Sales']=standard_deviation($data_sales);
			$this->data['Product Department Max Day Sales']=$max_value;
		}else {
			$this->data['Product Department Number Days with Sales']=0;
			$this->data['Product Department Avg with Sale Day Sales']=0;
			$this->data['Product Department STD with Sale Day Sales']=0;
			$this->data['Product Department Max Day Sales']=0;

		}

		$sql=sprintf("update `Product Department Dimension` set `Product Department Number Days on Sale`=%d,`Product Department Avg Day Sales`=%d,`Product Department Number Days Available`=%f,`Product Department Number Days with Sales`=%d,`Product Department Avg with Sale Day Sales`=%f,`Product Department STD with Sale Day Sales`=%f,`Product Department Max Day Sales`=%f where `Product Department Key`=%d",
			$this->data['Product Department Number Days on Sale'],
			$this->data['Product Department Avg Day Sales'],
			$this->data['Product Department Number Days Available'],
			$this->data['Product Department Number Days with Sales'],
			$this->data['Product Department Avg with Sale Day Sales'],
			$this->data['Product Department STD with Sale Day Sales'],
			$this->data['Product Department Max Day Sales'],
			$this->id
		);
		mysql_query($sql);

	}

	function get_formated_discounts() {
		$formated_discounts='';
		$sql=sprintf("select `Deal Description`,`Deal Name`,D.`Deal Key`,`Deal Component Allowance Description` from `Deal Target Bridge`  B left join `Deal Component Dimension` DC on (DC.`Deal Component Key`=B.`Deal Component Key`) left join `Deal Dimension` D on (D.`Deal Key`=B.`Deal Key`) where `Subject`='Department' and `Subject Key`=%d ",$this->id,$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$formated_discounts.=', <span title="'.$row['Deal Description'].'"><a href="deal.php?id='.$row['Deal Key'].'">'.$row['Deal Name']. '</a> <b>'.$row['Deal Component Allowance Description'].'</b></span>';
		}
		$formated_discounts=preg_replace('/^, /','',$formated_discounts);
		return $formated_discounts;
	}

}

?>
