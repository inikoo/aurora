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

	/*
      Constructor: Department
      Initializes the class, trigger  Search/Load/Create for the data set

      Returns:
      void
    */
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

		//   print "$sql\n";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Department Added");
			$this->get_data('id',$this->id,false);
			$this->new=true;

			$this->add_history(array(
					'Action'=>'created'
					,'History Abstract'=>_('Department Created')
					,'History Details'=>_('Department')." ".$this->data['Product Department Name']." (".$this->get('Product Department Code').") "._('Created')
				));


			$store->update_departments();
			return;
		} else {
			$this->error=true;
			$this->msg=_("$sql Error can not create department");

		}

	}

	/*
       Method: get_data
       Obtiene los datos de la tabla Product Department Dimension de acuerdo al Id, al codigo o al code_store.
    */
	// JFA

	function get_data($tipo,$tag,$tag2=false) {

		switch ($tipo) {
		case('id'):
			$sql=sprintf("select * from `Product Department Dimension` where `Product Department Key`=%d ",$tag);
			break;
		case('code'):
			$sql=sprintf("select * from `Product Department Dimension` where `Product Department Code`=%s and `Product Department Most Recent`='Yes'",prepare_mysql($tag));
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


	function update($key,$a1=false,$a2=false) {
		$this->updated=false;
		$this->msg='Nothing to change';

		switch ($key) {
		case('sales_type'):
			$this->update_sales_type($a1);
			break;
		case('code'):

			if ($a1==$this->data['Product Department Code']) {
				$this->updated=true;
				$this->new_value=$a1;
				return;

			}

			if ($a1=='') {
				$this->msg=_('Error: Wrong code (empty)');
				return;
			}

			if (!(strtolower($a1)==strtolower($this->data['Product Department Code']) and $a1!=$this->data['Product Department Code'])) {

				$sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s  COLLATE utf8_general_ci"
					,$this->data['Product Department Store Key']
					,prepare_mysql($a1)
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
				,prepare_mysql($a1)
				,$this->id
			);
			if (mysql_query($sql)) {
				$this->msg=_('Department code updated');
				$this->updated=true;
				$this->new_value=$a1;

				$this->data['Product Department Code']=$a1;
				$editor_data=$this->get_editor_data();


				$this->add_history(array(
						'Indirect Object'=>'Product Department Code'
						,'History Abstract'=>_('Product Department Changed').' ('.$this->get('Product Department Name').')'
						,'History Details'=>_('Store')." ".$this->data['Product Department Name']." "._('code changed from').' '.$old_value." "._('to').' '. $this->get('Product Department Code')
					));




			} else {
				$this->msg=_("Error: Department code could not be updated");

				$this->updated=false;

			}
			break;

		case('name'):

			if ($a1==$this->data['Product Department Name']) {
				$this->updated=true;
				$this->new_value=$a1;
				return;

			}

			if ($a1=='') {
				$this->msg=_('Error: Wrong name (empty)');
				return;
			}
			$sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Name`=%s  COLLATE utf8_general_ci"
				,$this->data['Product Department Store Key']
				,prepare_mysql($a1)
			);
			$res=mysql_query($sql);
			$row=mysql_fetch_array($res);
			if ($row['num']>0) {
				$this->msg=_("Error: Another department with the same name");
				return;
			}
			$old_value=$this->get('Product Department Name');
			$sql=sprintf("update `Product Department Dimension` set `Product Department Name`=%s where `Product Department Key`=%d "
				,prepare_mysql($a1)
				,$this->id
			);
			if (mysql_query($sql)) {
				$this->msg=_('Department name updated');
				$this->updated=true;
				$this->new_value=$a1;
				$this->data['Product Department Name']=$a1;


				$this->add_history(array(
						'Indirect Object'=>'Product Department Name'
						,'History Abstract'=>_('Product Department Name Changed').' ('.$this->get('Product Department Name').')'
						,'History Details'=>_('Product Department')." ("._('Code').":".$this->data['Product Department Code'].") "._('name changed from').' '.$old_value." "._('to').' '. $this->get('Product Department Name')
					));



			} else {
				$this->msg=_("Error: Department name could not be updated");

				$this->updated=false;

			}
			break;
		}
	}


	function delete() {
		$this->deleted=false;
		$this->update_product_data();

		if ($this->get('Total Products')==0) {
			$store=new Store($this->data['Product Department Store Key']);
			$sql=sprintf("delete from `Product Department Dimension` where `Product Department Key`=%d",$this->id);
			if (mysql_query($sql)) {

				$this->deleted=true;

			} else {

				$this->msg=_('Error: can not delete department');
				return;
			}

			$this->deleted=true;
		} else {//when families are associated with this department
			//$this->msg=_('Department can not be deleted because it has associated some products');
			$move_all_products = true;
			$store=new Store($this->data['Product Department Store Key']);

			$sql = sprintf("select * from `Product Family Dimension` where `Product Family Main Department Key` = %d", $this->id);
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$family = new Family($row['Product Family Key']);
				$family->update_department($store->data['Store Orphan Families Department']);
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
			} else {
				$this->deleted_msg='Error family can not be deleted';
			}
		}
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
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
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

		switch ($interval) {


		case 'Total':

			$db_interval='Total';
			$from_date=date('Y-m-d H:i:s',strtotime($this->data['Product Department Valid From']));
			$to_date=gmdate('Y-m-d H:i:s');

			$from_date_1yb=false;
			$to_1yb=false;
			//print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
			break;



		case 'Last Month':
		case 'last_m':
			$db_interval='Last Month';
			$from_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m')-1,1,date('Y')));
			$to_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),1,date('Y')));

			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
			//print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
			break;

		case 'Last Week':
		case 'last_w':
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
		case 'yesterday':
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
		case 'today':
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
		case '3y':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -3 year"));
			$from_date_1yb=false;
			$to_1yb=false;
			break;
		case '1 Year':
		case '1y':
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
		case '1q':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -3 months"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '1 Month':
		case '1m':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -1 month"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '10 Day':
		case '10d':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -10 days"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;
		case '1 Week':
		case '1w':
			$db_interval=$interval;
			$from_date=date('Y-m-d H:i:s',strtotime("now -1 week"));
			$from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
			$to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
			break;

		default:
			return;
			break;
		}

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
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
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

		$sql=sprintf("update `Product Department Dimension` set
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
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Product Department $db_interval Acc 1YB Invoiced Discount Amount"]=$row["discounts"];
				$this->data["Product Department $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
				$this->data["Product Department $db_interval Acc 1YB Invoiced Delta"]=($row["net"]==0?-1000000:$this->data["Product Department $db_interval Acc Invoiced Amount"]/$row["net"]);

				$this->data["Product Department $db_interval Acc 1YB Invoices"]=$row["invoices"];
				$this->data["Product Department $db_interval Acc 1YB Profit"]=$row["net"]-$row['total_cost'];

			}

			$sql=sprintf("update `Product Department Dimension` set
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


	function update_sales_data_old() {
		$on_sale_days=0;

		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension`  where `Product Main Department Key`=".$this->id;

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
		$sql="select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')  and  `Product Department Key`=".$this->id;
		// print $sql;
		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql="select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF  where `Product Department Key`=".$this->id;

		// print $sql;


		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department Total Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department Total Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department Total Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department Total Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department Total Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department Total Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department Total Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department Total Acc Days On Sale']=$on_sale_days;
			$this->data['Product Department Total Acc Customers']=$row['customers'];
			$this->data['Product Department Total Acc Invoices']=$row['invoices'];
			$this->data['Product Department Total Acc Pending Orders']=$pending_orders;


			$this->data['Product Department Valid From']=$_from;
			$this->data['Product Department Valid To']=$_to;
			$sql=sprintf("update `Product Department Dimension` set `Product Department Total Acc Invoiced Gross Amount`=%s,`Product Department Total Acc Invoiced Discount Amount`=%s,`Product Department Total Acc Invoiced Amount`=%s,`Product Department Total Acc Profit`=%s, `Product Department Total Acc Quantity Ordered`=%s , `Product Department Total Acc Quantity Invoiced`=%s,`Product Department Total Acc Quantity Delivered`=%s ,`Product Department Total Acc Days On Sale`=%f ,`Product Department Valid From`=%s,`Product Department Valid To`=%s ,`Product Department Total Acc Customers`=%d,`Product Department Total Acc Invoices`=%d,`Product Department Total Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department Total Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department Total Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department Total Acc Invoiced Amount'])
				,prepare_mysql($this->data['Product Department Total Acc Profit'])
				,prepare_mysql($this->data['Product Department Total Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department Total Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department Total Acc Quantity Delivered'])
				,$on_sale_days
				,prepare_mysql($this->data['Product Department Valid From'])
				,prepare_mysql($this->data['Product Department Valid To'])
				,$this->data['Product Department Total Acc Customers']
				,$this->data['Product Department Total Acc Invoices']
				,$this->data['Product Department Total Acc Pending Orders']

				,$this->id
			);

			//   print "$sql\n";
			//  exit;
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}
		// days on sale

		// --------------------------------------------------------start for 3 year-------------------------------------------------------------------
		$on_sale_days=0;



		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
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
				if ($to>strtotime('today -3 year')) {
					//print "caca";
					$from=strtotime($row['ffrom']);
					if ($from<strtotime('today -3 year'))
						$from=strtotime('today -3 year');

					//     print "*** T:$to F:$from\n";
					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else {
					$on_sale_days=0;

				}
			}
		}



		//$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 year"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 year"))));

		// print "$sql\n";

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department 3 Year Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department 3 Year Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department 3 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department 3 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department 3 Year Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department 3 Year Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department 3 Year Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department 3 Year Acc Customers']=$row['customers'];
			$this->data['Product Department 3 Year Acc Invoices']=$row['invoices'];
			$this->data['Product Department 3 Year Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department 3 Year Acc Invoiced Gross Amount`=%s,`Product Department 3 Year Acc Invoiced Discount Amount`=%s,`Product Department 3 Year Acc Invoiced Amount`=%s,`Product Department 3 Year Acc Profit`=%s, `Product Department 3 Year Acc Quantity Ordered`=%s , `Product Department 3 Year Acc Quantity Invoiced`=%s,`Product Department 3 Year Acc Quantity Delivered`=%s ,`Product Department 3 Year Acc Days On Sale`=%f  ,`Product Department 3 Year Acc Customers`=%d,`Product Department 3 Year Acc Invoices`=%d,`Product Department 3 Year Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department 3 Year Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department 3 Year Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department 3 Year Acc Invoiced Amount'])

				,prepare_mysql($this->data['Product Department 3 Year Acc Profit'])
				,prepare_mysql($this->data['Product Department 3 Year Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department 3 Year Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department 3 Year Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department 3 Year Acc Customers']
				,$this->data['Product Department 3 Year Acc Invoices']
				,$this->data['Product Department 3 Year Acc Pending Orders']
				,$this->id
			);
			//print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}
		// exit;
		// --------------------------------------------------------end for 3 year---------------------------------------------------------------------


		$on_sale_days=0;



		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
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
					$on_sale_days=0;

				}
			}
		}



		//$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
		// print "$sql\n\n";
		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

		// print "$sql\n";

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department 1 Year Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department 1 Year Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department 1 Year Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department 1 Year Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department 1 Year Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department 1 Year Acc Customers']=$row['customers'];
			$this->data['Product Department 1 Year Acc Invoices']=$row['invoices'];
			$this->data['Product Department 1 Year Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department 1 Year Acc Invoiced Gross Amount`=%s,`Product Department 1 Year Acc Invoiced Discount Amount`=%s,`Product Department 1 Year Acc Invoiced Amount`=%s,`Product Department 1 Year Acc Profit`=%s, `Product Department 1 Year Acc Quantity Ordered`=%s , `Product Department 1 Year Acc Quantity Invoiced`=%s,`Product Department 1 Year Acc Quantity Delivered`=%s ,`Product Department 1 Year Acc Days On Sale`=%f  ,`Product Department 1 Year Acc Customers`=%d,`Product Department 1 Year Acc Invoices`=%d,`Product Department 1 Year Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Amount'])

				,prepare_mysql($this->data['Product Department 1 Year Acc Profit'])
				,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department 1 Year Acc Customers']
				,$this->data['Product Department 1 Year Acc Invoices']
				,$this->data['Product Department 1 Year Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}
		// exit;



		// --------------------------------------start for yeartoday-----------------------------------
		$on_sale_days=0;
		if (!function_exists('YTD')) {
			function YTD() {
				$first_day_of_year = date('Y').'-01-01';
				$today = date('Y-m-d');
				$diff = abs((strtotime($today) - strtotime($first_day_of_year))/ (60 * 60 * 24));
				return $diff;
			}
		}
		$yeartoday=YTD();
		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['prods']==0)
				$on_sale_days=0;
			else {


				if ($row['for_sale']>0)
					$to=strtotime('today');
				else
					$to=strtotime($row['to']);
				if ($to>strtotime("today -$yeartoday")) {

					$from=strtotime($row['ffrom']);
					if ($from<strtotime("today -$yeartoday"))
						$from=strtotime("today -$yeartoday");


					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else
					$on_sale_days=0;
			}
		}

		//$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- $yeartoday day"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- $yeartoday day"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department Year To Day Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department Year To Day Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department Year To Day Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department Year To Day Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department Year To Day Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department Year To Day Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department Year To Day Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department Year To Day Acc Customers']=$row['customers'];
			$this->data['Product Department Year To Day Acc Invoices']=$row['invoices'];
			$this->data['Product Department Year To Day Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department Year To Day Acc Invoiced Gross Amount`=%s,`Product Department Year To Day Acc Invoiced Discount Amount`=%s,`Product Department Year To Day Acc Invoiced Amount`=%s,`Product Department Year To Day Acc Profit`=%s, `Product Department Year To Day Acc Quantity Ordered`=%s , `Product Department Year To Day Acc Quantity Invoiced`=%s,`Product Department 10 Day Acc Quantity Delivered`=%s  ,`Product Department 10 Day Acc Days On Sale`=%f  ,`Product Department 10 Day Acc Customers`=%d,`Product Department Year To Day Acc Invoices`=%d,`Product Department Year To Day Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department Year To Day Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department Year To Day Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department Year To Day Acc Invoiced Amount'])

				,prepare_mysql($this->data['Product Department Year To Day Acc Profit'])
				,prepare_mysql($this->data['Product Department Year To Day Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department Year To Day Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department Year To Day Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department Year To Day Acc Customers']
				,$this->data['Product Department Year To Day Acc Invoices']
				,$this->data['Product Department Year To Day Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}

		// --------------------------------------ends for yeartoday-------------------------------------


		// ----------------------------------start for 6 month-----------------------------------------


		$on_sale_days=0;


		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['prods']==0)
				$on_sale_days=0;
			else {


				if ($row['for_sale']>0)
					$to=strtotime('today');
				else
					$to=strtotime($row['to']);
				if ($to>strtotime('today -6 month')) {

					$from=strtotime($row['ffrom']);
					if ($from<strtotime('today -6 month'))
						$from=strtotime('today -6 month');


					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else
					$on_sale_days=0;
			}
		}

		//$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 6 month"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 6 month"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department 6 Month Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department 6 Month Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department 6 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department 6 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department 6 Month Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department 6 Month Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department 6 Month Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department 6 Month Acc Customers']=$row['customers'];
			$this->data['Product Department 6 Month Acc Invoices']=$row['invoices'];
			$this->data['Product Department 6 Month Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department 6 Month Acc Invoiced Gross Amount`=%s,`Product Department 6 Month Acc Invoiced Discount Amount`=%s,`Product Department 6 Month Acc Invoiced Amount`=%s,`Product Department 6 Month Acc Profit`=%s, `Product Department 6 Month Acc Quantity Ordered`=%s , `Product Department 6 Month Acc Quantity Invoiced`=%s,`Product Department 6 Month Acc Quantity Delivered`=%s  ,`Product Department 6 Month Acc Days On Sale`=%f  ,`Product Department 6 Month Acc Customers`=%d,`Product Department 6 Month Acc Invoices`=%d,`Product Department 6 Month Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department 6 Month Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department 6 Month Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department 6 Month Acc Invoiced Amount'])

				,prepare_mysql($this->data['Product Department 6 Month Acc Profit'])
				,prepare_mysql($this->data['Product Department 6 Month Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department 6 Month Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department 6 Month Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department 6 Month Acc Customers']
				,$this->data['Product Department 6 Month Acc Invoices']
				,$this->data['Product Department 6 Month Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}


		// ----------------------------------end for 6 month-----------------------------------------


		$on_sale_days=0;


		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

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

		//$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department 1 Quarter Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department 1 Quarter Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department 1 Quarter Acc Customers']=$row['customers'];
			$this->data['Product Department 1 Quarter Acc Invoices']=$row['invoices'];
			$this->data['Product Department 1 Quarter Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department 1 Quarter Acc Invoiced Gross Amount`=%s,`Product Department 1 Quarter Acc Invoiced Discount Amount`=%s,`Product Department 1 Quarter Acc Invoiced Amount`=%s,`Product Department 1 Quarter Acc Profit`=%s, `Product Department 1 Quarter Acc Quantity Ordered`=%s , `Product Department 1 Quarter Acc Quantity Invoiced`=%s,`Product Department 1 Quarter Acc Quantity Delivered`=%s  ,`Product Department 1 Quarter Acc Days On Sale`=%f  ,`Product Department 1 Quarter Acc Customers`=%d,`Product Department 1 Quarter Acc Invoices`=%d,`Product Department 1 Quarter Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Amount'])

				,prepare_mysql($this->data['Product Department 1 Quarter Acc Profit'])
				,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department 1 Quarter Acc Customers']
				,$this->data['Product Department 1 Quarter Acc Invoices']
				,$this->data['Product Department 1 Quarter Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}

		$on_sale_days=0;

		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
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

		//$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF   where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department 1 Month Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department 1 Month Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department 1 Month Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department 1 Month Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department 1 Month Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department 1 Month Acc Customers']=$row['customers'];
			$this->data['Product Department 1 Month Acc Invoices']=$row['invoices'];
			$this->data['Product Department 1 Month Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department 1 Month Acc Invoiced Gross Amount`=%s,`Product Department 1 Month Acc Invoiced Discount Amount`=%s,`Product Department 1 Month Acc Invoiced Amount`=%s,`Product Department 1 Month Acc Profit`=%s, `Product Department 1 Month Acc Quantity Ordered`=%s , `Product Department 1 Month Acc Quantity Invoiced`=%s,`Product Department 1 Month Acc Quantity Delivered`=%s  ,`Product Department 1 Month Acc Days On Sale`=%f ,`Product Department 1 Month Acc Customers`=%d,`Product Department 1 Month Acc Invoices`=%d,`Product Department 1 Month Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Amount'])

				,prepare_mysql($this->data['Product Department 1 Month Acc Profit'])
				,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department 1 Month Acc Customers']
				,$this->data['Product Department 1 Month Acc Invoices']
				,$this->data['Product Department 1 Month Acc Pending Orders']
				,$this->id

			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}


		// --------------------------------------start for 10 days-----------------------------------
		$on_sale_days=0;


		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['prods']==0)
				$on_sale_days=0;
			else {


				if ($row['for_sale']>0)
					$to=strtotime('today');
				else
					$to=strtotime($row['to']);
				if ($to>strtotime('today -10 day')) {

					$from=strtotime($row['ffrom']);
					if ($from<strtotime('today -10 day'))
						$from=strtotime('today -10 day');


					$on_sale_days=($to-$from)/ (60 * 60 * 24);
				} else
					$on_sale_days=0;
			}
		}

		//$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 10 day"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 10 day"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department 10 Day Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department 10 Day Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department 10 Day Acc Invoiced Amount']=$row['gross']-$row['disc'];

			$this->data['Product Department 10 Day Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department 10 Day Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department 10 Day Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department 10 Day Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department 10 Day Acc Customers']=$row['customers'];
			$this->data['Product Department 10 Day Acc Invoices']=$row['invoices'];
			$this->data['Product Department 10 Day Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department 10 Day Acc Invoiced Gross Amount`=%s,`Product Department 10 Day Acc Invoiced Discount Amount`=%s,`Product Department 10 Day Acc Invoiced Amount`=%s,`Product Department 10 Day Acc Profit`=%s, `Product Department 10 Day Acc Quantity Ordered`=%s , `Product Department 10 Day Acc Quantity Invoiced`=%s,`Product Department 10 Day Acc Quantity Delivered`=%s  ,`Product Department 10 Day Acc Days On Sale`=%f  ,`Product Department 10 Day Acc Customers`=%d,`Product Department 10 Day Acc Invoices`=%d,`Product Department 10 Day Acc Pending Orders`=%d where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department 10 Day Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department 10 Day Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department 10 Day Acc Invoiced Amount'])

				,prepare_mysql($this->data['Product Department 10 Day Acc Profit'])
				,prepare_mysql($this->data['Product Department 10 Day Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department 10 Day Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department 10 Day Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department 10 Day Acc Customers']
				,$this->data['Product Department 10 Day Acc Invoices']
				,$this->data['Product Department 10 Day Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");
		}

		// --------------------------------------ends for 10 days-------------------------------------


		$on_sale_days=0;
		$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Main Department Key`=".$this->id;
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



		// $sql="select sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
		$sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));

		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
		$sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`   where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));


		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department 1 Week Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product Department 1 Week Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product Department 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data['Product Department 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product Department 1 Week Acc Quantity Ordered']=$row['ordered'];

			$this->data['Product Department 1 Week Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product Department 1 Week Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product Department 1 Week Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product Department 1 Week Acc Customers']=$row['customers'];
			$this->data['Product Department 1 Week Acc Invoices']=$row['invoices'];
			$this->data['Product Department 1 Week Acc Pending Orders']=$pending_orders;

			$sql=sprintf("update `Product Department Dimension` set `Product Department 1 Week Acc Invoiced Gross Amount`=%s,`Product Department 1 Week Acc Invoiced Discount Amount`=%s,`Product Department 1 Week Acc Invoiced Amount`=%s,`Product Department 1 Week Acc Profit`=%s, `Product Department 1 Week Acc Quantity Ordered`=%s , `Product Department 1 Week Acc Quantity Invoiced`=%s,`Product Department 1 Week Acc Quantity Delivered`=%s ,`Product Department 1 Week Acc Days On Sale`=%f ,`Product Department 1 Week Acc Customers`=%d,`Product Department 1 Week Acc Invoices`=%d,`Product Department 1 Week Acc Pending Orders`=%d  where `Product Department Key`=%d "
				,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Gross Amount'])
				,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Discount Amount'])
				,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Amount'])
				,prepare_mysql($this->data['Product Department 1 Week Acc Profit'])
				,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Ordered'])
				,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Invoiced'])
				,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Delivered'])
				,$on_sale_days
				,$this->data['Product Department 1 Week Acc Customers']
				,$this->data['Product Department 1 Week Acc Invoices']
				,$this->data['Product Department 1 Week Acc Pending Orders']
				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update dept sales\n");

		}
	}
	function name_if_duplicated($data) {

		$sql=sprintf("select * from `Product Department Dimension` where `Product Department Name`=%s  and `Product Department Store Key`=%d "
			,prepare_mysql($data['Product Department Name'])
			,$data['Product Department Store Key']
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
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
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

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
		$number_active_customers_more_than_50=0;

		$sql=sprintf(" select    (select sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)  from  `Order Transaction Fact`  where  `Order Transaction Fact`.`Customer Key`=OTF.`Customer Key` ) as total_amount  , sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount,OTF.`Customer Key` from `Order Transaction Fact`  OTF  left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`)where `Product Department Key`=%d and `Customer Type by Activity` in ('New','Active') and `Invoice Transaction Gross Amount`>0  group by  OTF.`Customer Key`",$this->id);
		// print "$sql\n";
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$number_active_customers++;
			if ($row['total_amount']!=0 and ($row['amount']/$row['total_amount'])>0.5 )
				$number_active_customers_more_than_50++;
		}

		$this->data['Product Department Active Customers']=$number_active_customers;
		$this->data['Product Department Active Customers More 0.5 Share']=$number_active_customers_more_than_50;

		$sql=sprintf("update `Product Department Dimension` set `Product Department Active Customers`=%d ,`Product Department Active Customers More 0.5 Share`=%d where `Product Department Key`=%d  ",
			$this->data['Product Department Active Customers'],
			$this->data['Product Department Active Customers More 0.5 Share'],
			$this->id
		);
		// print "$sql\n";
		mysql_query($sql);

	}

	function update_families() {
		$sql=sprintf("select count(*) as num from `Product Family Dimension`  where`Product Family Main Department Key`=%d",$this->id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department Families']=$row['num'];
			$sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d  where `Product Department Key`=%d  ",
				$this->data['Product Department Families'],
				$this->id
			);
			mysql_query($sql); //print "$sql\n";
		}

		$sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Main Department Key`=%d and `Product Family Sales Type`='Public Sale' and `Product Family Record Type` in ('New','Normal','Discontinuing')  ",$this->id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department For Public For Sale Families']=$row['num'];
			$sql=sprintf("update `Product Department Dimension` set `Product Department For Public For Sale Families`=%d  where `Product Department Key`=%d  ",
				$this->data['Product Department For Public For Sale Families'],
				$this->id
			);
			mysql_query($sql); //print "$sql\n";
		}

		$sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Main Department Key`=%d  and `Product Family Sales Type`='Public Sale' and `Product Family Record Type`='Discontinued'    "   ,$this->id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Product Department For Public Discontinued Families']=$row['num'];
			$sql=sprintf("update `Product Department Dimension` set `Product Department For Public Discontinued Families`=%d  where `Product Department Key`=%d  ",
				$this->data['Product Department For Public Discontinued Families'],
				$this->id
			);
			//   print "$sql\n";
			mysql_query($sql);
		}


	}













	function update_sales_default_currency_old() {
		$this->data_default_currency=array();
		$this->data_default_currency['Product Department DC Total Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Department DC Total Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Department DC Total Invoiced Amount']=0;
		$this->data_default_currency['Product Department DC Total Profit']=0;
		$this->data_default_currency['Product Department DC 1 Year Acc Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Department DC 1 Year Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Department DC 1 Year Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Department DC 1 Year Acc Profit']=0;
		$this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Department DC 1 Quarter Acc Profit']=0;
		$this->data_default_currency['Product Department DC 1 Month Acc Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Department DC 1 Month Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Department DC 1 Month Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Department DC 1 Month Acc Profit']=0;
		$this->data_default_currency['Product Department DC 1 Week Acc Invoiced Gross Amount']=0;
		$this->data_default_currency['Product Department DC 1 Week Acc Invoiced Discount Amount']=0;
		$this->data_default_currency['Product Department DC 1 Week Acc Invoiced Amount']=0;
		$this->data_default_currency['Product Department DC 1 Week Acc Profit']=0;



		$sql="select     sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF   where `Product Department Key`=".$this->id;


		//print "$sql\n\n";
		// exit;
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Department DC Total Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Department DC Total Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Department DC Total Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Department DC Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}



		$sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Department DC 1 Year Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Department DC 1 Year Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Department DC 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Department DC 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}

		$sql=sprintf("select   sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Department DC 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}

		$sql=sprintf("select    sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross  ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));



		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Department DC 1 Month Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Department DC 1 Month Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Department DC 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Department DC 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}
		$sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross   ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
		// print $sql;
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data_default_currency['Product Department DC 1 Week Acc Invoiced Gross Amount']=$row['gross'];
			$this->data_default_currency['Product Department DC 1 Week Acc Invoiced Discount Amount']=$row['disc'];
			$this->data_default_currency['Product Department DC 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data_default_currency['Product Department DC 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

		}

		$insert_values='';
		$update_values='';
		foreach ($this->data_default_currency as $key=>$value) {
			$insert_values.=sprintf(',%.2f',$value);
			$update_values.=sprintf(',`%s`=%.2f',addslashes($key),$value);
		}
		$insert_values=preg_replace('/^,/','',$insert_values);
		$update_values=preg_replace('/^,/','',$update_values);


		$sql=sprintf('Insert into `Product Department Default Currency` values (%d,%s) ON DUPLICATE KEY UPDATE %s  ',$this->id,$insert_values,$update_values);
		mysql_query($sql);
		//print "$sql\n";



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
	function get_images_slidesshow() {
		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Department' and   `Subject Key`=%d",$this->id);
		$res=mysql_query($sql);
		$images_slideshow=array();
		while ($row=mysql_fetch_array($res)) {
			if ($row['Image Height']!=0)
				$ratio=$row['Image Width']/$row['Image Height'];
			else
				$ratio=1;
			// print_r($row);
			$images_slideshow[]=array('name'=>$row['Image Filename'],'small_url'=>'image.php?id='.$row['Image Key'].'&size=small','thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail','filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
		}
		// print_r($images_slideshow);

		return $images_slideshow;
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
			$this->new_value=array('name'=>$row['Image Filename'],'small_url'=>'image.php?id='.$row['Image Key'].'&size=small','thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail','filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
			// $this->images_slideshow[]=$this->new_value;
		}

		$this->updated=true;
		$this->msg=_("image added");
	}


	function get_pages_keys() {
		$page_keys=array();
		$sql=sprintf("Select `Page Key` from `Page Store Dimension` where `Page Store Section`='Department Catalogue' and  `Page Parent Key`=%d",$this->id);
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

}

?>
