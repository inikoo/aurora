<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2016 at 12:18:51 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

trait ProductCategory {

	function get_see_also_data() {
		return $this->webpage->get_see_also_data();
	}


	function get_related_products_data() {
		return $this->webpage->get_related_products_data();

	}


	function get_webpage() {

		$page_key=0;
		include_once 'class.Page.php';


		$category_key=$this->id;

		include_once 'class.Store.php';
		$store=new Store($this->get('Category Store Key'));

		// Migration
		if ($this->get('Category Root Key')==$store->get('Store Family Category Key')) {


			$sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
				$this->get('Category Store Key'),
				prepare_mysql($this->get('Category Code'))
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$category_key=$row['Product Family Key'];
				}
			}


		}




		$sql=sprintf('select `Page Key` from `Page Store Dimension` where `Page Store Section Type`="Family"  and  `Page Parent Key`=%d ', $category_key);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$page_key=$row['Page Key'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}
		$this->webpage=new Page($row['Page Key']);
		$this->webpage->editor=$this->editor;


		// Temporal should be take off bcuse page should be created when product is createss
		/*
		if (!$this->webpage->id) {

			$page_data=array(
				'Page Store Content Display Type'=>'Template',
				'Page Store Content Template Filename'=>'product',
				'Page State'=>'Online'

			);
			include_once 'class.Store.php';

			$store=new Store($this->get('Product Store Key'));

			foreach ($store->get_sites('objects') as $site) {

				$product_page_key=$site->add_product_page($this->id, $page_data);
				$this->webpage=new Page($product_page_key);
			}


		}

		*/

	}


	function create_product_timeseries($data) {

		if ( $this->get('Category Branch Type')=='Root') {
			return;
		}


		$data['Timeseries Parent']='Category';
		$data['Timeseries Parent Key']=$this->id;

		$timeseries=new Timeseries('find', $data, 'create');
		if ($timeseries->new or true) {

			require_once 'utils/date_functions.php';

			if ($this->data['Product Category Valid From']!='') {
				$from=date('Y-m-d', strtotime($this->get('Product Category Valid From')));

			}else {
				$from='';
			}

			if ($this->get('Product Category Status')=='Discontinued') {
				$to=date('Y-m-d', strtotime($this->get('Product Category Valid To')));
			}else {
				$to=date('Y-m-d');
			}




			if ($from and $to) {

				$this->update_product_timeseries_record($timeseries, $to, $from);





			}

			if ($timeseries->get('Timeseries Number Records')==0)
				$timeseries->update(array('Timeseries Updated'=>gmdate('Y-m-d H:i:s')), 'no_history');


		}

	}


	function update_product_timeseries_record($timeseries, $to, $from) {

		if ( $this->get('Category Branch Type')=='Root') {
			return;
		}

		$dates=date_frequency_range($this->db, $timeseries->get('Timeseries Frequency'), $from, $to);

		foreach ($dates as $date_frequency_period) {

			list($invoices, $customers, $net, $dc_net)=$this->get_product_timeseries_record_data($timeseries, $date_frequency_period);


			$_date=gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00')) ;

			if ($invoices!=0 or $customers!=0 or $net!=0) {
				list($timeseries_record_key, $date)=$timeseries->create_record(array('Timeseries Record Date'=> $_date ));
				$sql=sprintf('update `Timeseries Record Dimension` set `Timeseries Record Integer A`=%d ,`Timeseries Record Integer B`=%d ,`Timeseries Record Float A`=%.2f ,`Timeseries Record Float B`=%.2f ,`Timeseries Record Type`=%s where `Timeseries Record Key`=%d',
					$invoices,
					$customers,
					$net,
					$dc_net,
					prepare_mysql('Data'),
					$timeseries_record_key

				);

				$update_sql = $this->db->prepare($sql);
				$update_sql->execute();

				if ($update_sql->rowCount() or $date==date('Y-m-d')) {
					$timeseries->update(array('Timeseries Updated'=>gmdate('Y-m-d H:i:s')), 'no_history');
				}

			}else {
				$sql=sprintf('delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=%d and `Timeseries Record Date`=%s ',
					$timeseries->id,
					prepare_mysql($_date)
				);

				$update_sql = $this->db->prepare($sql);
				$update_sql->execute();
				if ($update_sql->rowCount()) {
					$timeseries->update(array('Timeseries Updated'=>gmdate('Y-m-d H:i:s')), 'no_history');

				}

			}
			$timeseries->update_stats();
			//$updated=$this->update_product_timeseries_record($timeseries, $timeseries_record_key, $date);

			//$timeseries->update_stats();
			//if ($updated) {
			// $timeseries->update(array('Timeseries Updated'=>gmdate('Y-m-d H:i:s')), 'no_history');
			//}

		}

	}


	function get_product_timeseries_record_data($timeseries, $date_frequency_period) {



		$product_ids=$this->get_product_ids();


		if ($product_ids=='') {
			return array(0, 0, 0, 0);
		}

		if ($timeseries->get('Timeseries Scope')=='Sales') {



			$sql=sprintf("select count(distinct `Invoice Key`)  as invoices,count(distinct `Customer Key`)  as customers,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net

			from `Order Transaction Fact` where `Product ID` in (%s)  and `Invoice Key`>0 and  `Invoice Date`>=%s  and   `Invoice Date`<=%s  " ,
				$product_ids,
				prepare_mysql($date_frequency_period['from']),
				prepare_mysql($date_frequency_period['to'])
			);



			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {


					$invoices=$row['invoices'];
					$customers=$row['customers'];
					$net=$row['net'];
					$dc_net=$row['dc_net'];
				}else {
					$invoices=0;
					$customers=0;
					$net=0;
					$dc_net=0;
				}

				return array($invoices, $customers, $net, $dc_net);

			}else {
				print_r($error_info=$this->db->errorInfo());
				print "$sql\n";
				exit;
			}



		}


	}


	function update_product_category_up_today_sales() {

		if (!$this->skip_update_sales) {
			$this->update_product_category_sales('Today');
			$this->update_product_category_sales('Week To Day');
			$this->update_product_category_sales('Month To Day');
			$this->update_product_category_sales('Year To Day');
		}
	}


	function update_product_category_last_period_sales() {
		if (!$this->skip_update_sales) {
			$this->update_product_category_sales('Yesterday');
			$this->update_product_category_sales('Last Week');
			$this->update_product_category_sales('Last Month');
		}
	}


	function update_product_category_interval_sales() {
		if (!$this->skip_update_sales) {
			$this->update_product_category_sales('Total');
			$this->update_product_category_sales('3 Year');
			$this->update_product_category_sales('1 Year');
			$this->update_product_category_sales('6 Month');
			$this->update_product_category_sales('1 Quarter');
			$this->update_product_category_sales('1 Month');
			$this->update_product_category_sales('10 Day');
			$this->update_product_category_sales('1 Week');
		}
	}


	function get_products_subcategories_status_numbers($options='') {

		$elements_numbers=array(
			'InUse'=>0, 'NotInUse'=>0
		);

		$sql=sprintf("select count(*) as num ,`Product Category Status` from  `Product Category Dimension` P left join `Category Dimension` C on (C.`Category Key`=P.`Product Category Key`)  where `Category Parent Key`=%d  group by  `Product Category Status`   ",
			$this->id);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($options=='Formatted') {
					$elements_numbers[$row['Product Category Status']]=number($row['num']);

				}else {
					$elements_numbers[$row['Product Category Status']]=$row['num'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $elements_numbers;

	}




	function update_product_category_status() {

		$elements_numbers=array(
			'In Process'=>0, 'Active'=>0, 'Suspended'=>0, 'Discontinuing'=>0, 'Discontinued'=>0
		);

		$sql=sprintf("select count(*) as num ,`Product Status` as status from  `Product Dimension` P left join `Category Bridge` B on (P.`Product ID`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Product' group by  `Product Status`   ",
			$this->id

		);

		//print "$sql\n";

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$elements_numbers[$row['status']]=number($row['num']);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		if ($elements_numbers['Discontinued']>0 and $elements_numbers['Active']==0) {
			$this->data['Product Category Status']='Discontinued';
		}elseif ($elements_numbers['Discontinuing']>0 and $elements_numbers['Active']==0) {
			$this->data['Product Category Status']='Discontinuing';
		}elseif ($elements_numbers['Suspended']>0 and $elements_numbers['Active']==0) {
			$this->data['Product Category Status']='Suspended';
		}elseif ($elements_numbers['In Process']>0 and $elements_numbers['Active']==0) {
			$this->data['Product Category Status']='In Process';
		}else {
			if ($elements_numbers['Active']>0) {
				$this->data['Product Category Status']='Active';
			}else {
				$this->data['Product Category Status']='In Process';

			}
		}

		$sql=sprintf("update `Product Category Dimension` set `Product Category Status`=%s,`Product Category In Process Products`=%d,`Product Category Active Products`=%d,`Product Category Suspended Products`=%d,`Product Category Discontinued Products`=%d  where `Product Category Key`=%d",
			prepare_mysql($this->data['Product Category Status']),
			$elements_numbers['In Process'],
			$elements_numbers['Active'],
			$elements_numbers['Suspended'],
			$elements_numbers['Discontinued'],

			$this->id
		);
		//print "$sql\n";

		$this->db->exec($sql);


	}


	function update_product_stock_status() {

		$elements_numbers=array(
			'Surplus'=>0, 'Optimal'=>0, 'Low'=>0, 'Critical'=>0, 'Out_Of_Stock'=>0, 'Error'=>0
		);

		$sql=sprintf("select count(*) as num ,`Part Stock Status` from  `Part Dimension` P left join `Category Bridge` B on (P.`Part SKU`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Part' group by  `Part Stock Status`   ",
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$elements_numbers[$row['Part Stock Status']]=number($row['num']);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		$sql=sprintf("update `Product Category Dimension` set `Product Category Number Surplus Parts`=%d ,`Product Category Number Optimal Parts`=%d ,`Product Category Number Low Parts`=%d ,`Product Category Number Critical Parts`=%d ,`Product Category Number Out Of Stock Parts`=%d ,`Product Category Number Error Parts`=%d  where `Product Category Key`=%d",
			$elements_numbers['Surplus'],
			$elements_numbers['Optimal'],
			$elements_numbers['Low'],
			$elements_numbers['Critical'],
			$elements_numbers['Out_Of_Stock'],
			$elements_numbers['Error'],
			$this->id
		);

		$this->db->exec($sql);


	}




	function update_product_category_sales($interval, $this_year=true, $last_year=true) {

		include_once 'utils/date_functions.php';



		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)=calculate_interval_dates($this->db, $interval);

		if ($this_year) {

			$sales_product_category_data=$this->get_product_category_sales_data($from_date, $to_date);


			$data_to_update=array(
				"Product Category $db_interval Acc Customers"=>$sales_product_category_data['customers'],
				"Product Category $db_interval Acc Invoices"=>$sales_product_category_data['invoices'],
				"Product Category $db_interval Acc Profit"=>$sales_product_category_data['profit'],
				"Product Category $db_interval Acc Invoiced Amount"=>$sales_product_category_data['net'],
				"Product Category $db_interval Acc Quantity Ordered"=>$sales_product_category_data['ordered'],
				"Product Category $db_interval Acc Quantity Invoiced"=>$sales_product_category_data['invoiced'],
				"Product Category $db_interval Acc Quantity Delivered"=>$sales_product_category_data['delivered'],
				"Product Category DC $db_interval Acc Profit"=>$sales_product_category_data['dc_net'],
				"Product Category DC $db_interval Acc Invoiced Amount"=>$sales_product_category_data['dc_profit']
			);
			$this->update( $data_to_update, 'no_history');

		}

		if ($from_date_1yb and $last_year) {

			$sales_product_category_data=$this->get_product_category_sales_data($from_date_1yb, $to_1yb);

			$data_to_update=array(
				"Product Category $db_interval Acc 1YB Customers"=>$sales_product_category_data['customers'],
				"Product Category $db_interval Acc 1YB Invoices"=>$sales_product_category_data['invoices'],
				"Product Category $db_interval Acc 1YB Profit"=>$sales_product_category_data['profit'],
				"Product Category $db_interval Acc 1YB Invoiced Amount"=>$sales_product_category_data['net'],
				"Product Category $db_interval Acc 1YB Quantity Ordered"=>$sales_product_category_data['ordered'],
				"Product Category $db_interval Acc 1YB Quantity Invoiced"=>$sales_product_category_data['invoiced'],
				"Product Category $db_interval Acc 1YB Quantity Delivered"=>$sales_product_category_data['delivered'],
				"Product Category DC $db_interval Acc 1YB Profit"=>$sales_product_category_data['dc_net'],
				"Product Category DC $db_interval Acc 1YB Invoiced Amount"=>$sales_product_category_data['dc_profit']
			);
			$this->update( $data_to_update, 'no_history');

		}




	}



	function update_product_category_previous_years_data() {

		$data_1y_ago=$this->get_product_category_sales_data(date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-01-01 00:00:00'));
		$data_2y_ago=$this->get_product_category_sales_data(date('Y-01-01 00:00:00', strtotime('-2 year')), date('Y-01-01 00:00:00', strtotime('-1 year')));
		$data_3y_ago=$this->get_product_category_sales_data(date('Y-01-01 00:00:00', strtotime('-3 year')), date('Y-01-01 00:00:00', strtotime('-2 year')));
		$data_4y_ago=$this->get_product_category_sales_data(date('Y-01-01 00:00:00', strtotime('-4 year')), date('Y-01-01 00:00:00', strtotime('-3 year')));
		$data_5y_ago=$this->get_product_category_sales_data(date('Y-01-01 00:00:00', strtotime('-5 year')), date('Y-01-01 00:00:00', strtotime('-4 year')));

		$data_to_update=array(
			"Product Category 1 Year Ago Customers"=>$data_1y_ago['customers'],
			"Product Category 1 Year Ago Invoices"=>$data_1y_ago['invoices'],
			"Product Category 1 Year Ago Profit"=>$data_1y_ago['profit'],
			"Product Category 1 Year Ago Invoiced Amount"=>$data_1y_ago['net'],
			"Product Category 1 Year Ago Quantity Ordered"=>$data_1y_ago['ordered'],
			"Product Category 1 Year Ago Quantity Invoiced"=>$data_1y_ago['invoiced'],
			"Product Category 1 Year Ago Quantity Delivered"=>$data_1y_ago['delivered'],
			"Product Category DC 1 Year Ago Profit"=>$data_1y_ago['dc_net'],
			"Product Category DC 1 Year Ago Invoiced Amount"=>$data_1y_ago['dc_profit'],

			"Product Category 2 Year Ago Customers"=>$data_2y_ago['customers'],
			"Product Category 2 Year Ago Invoices"=>$data_2y_ago['invoices'],
			"Product Category 2 Year Ago Profit"=>$data_2y_ago['profit'],
			"Product Category 2 Year Ago Invoiced Amount"=>$data_2y_ago['net'],
			"Product Category 2 Year Ago Quantity Ordered"=>$data_2y_ago['ordered'],
			"Product Category 2 Year Ago Quantity Invoiced"=>$data_2y_ago['invoiced'],
			"Product Category 2 Year Ago Quantity Delivered"=>$data_2y_ago['delivered'],
			"Product Category DC 2 Year Ago Profit"=>$data_2y_ago['dc_net'],
			"Product Category DC 2 Year Ago Invoiced Amount"=>$data_2y_ago['dc_profit'],

			"Product Category 3 Year Ago Customers"=>$data_3y_ago['customers'],
			"Product Category 3 Year Ago Invoices"=>$data_3y_ago['invoices'],
			"Product Category 3 Year Ago Profit"=>$data_3y_ago['profit'],
			"Product Category 3 Year Ago Invoiced Amount"=>$data_3y_ago['net'],
			"Product Category 3 Year Ago Quantity Ordered"=>$data_3y_ago['ordered'],
			"Product Category 3 Year Ago Quantity Invoiced"=>$data_3y_ago['invoiced'],
			"Product Category 3 Year Ago Quantity Delivered"=>$data_3y_ago['delivered'],
			"Product Category DC 3 Year Ago Profit"=>$data_3y_ago['dc_net'],
			"Product Category DC 3 Year Ago Invoiced Amount"=>$data_3y_ago['dc_profit'],

			"Product Category 4 Year Ago Customers"=>$data_4y_ago['customers'],
			"Product Category 4 Year Ago Invoices"=>$data_4y_ago['invoices'],
			"Product Category 4 Year Ago Profit"=>$data_4y_ago['profit'],
			"Product Category 4 Year Ago Invoiced Amount"=>$data_4y_ago['net'],
			"Product Category 4 Year Ago Quantity Ordered"=>$data_4y_ago['ordered'],
			"Product Category 4 Year Ago Quantity Invoiced"=>$data_4y_ago['invoiced'],
			"Product Category 4 Year Ago Quantity Delivered"=>$data_4y_ago['delivered'],
			"Product Category DC 4 Year Ago Profit"=>$data_4y_ago['dc_net'],
			"Product Category DC 4 Year Ago Invoiced Amount"=>$data_4y_ago['dc_profit'],

			"Product Category 5 Year Ago Customers"=>$data_5y_ago['customers'],
			"Product Category 5 Year Ago Invoices"=>$data_5y_ago['invoices'],
			"Product Category 5 Year Ago Profit"=>$data_5y_ago['profit'],
			"Product Category 5 Year Ago Invoiced Amount"=>$data_5y_ago['net'],
			"Product Category 5 Year Ago Quantity Ordered"=>$data_5y_ago['ordered'],
			"Product Category 5 Year Ago Quantity Invoiced"=>$data_5y_ago['invoiced'],
			"Product Category 5 Year Ago Quantity Delivered"=>$data_5y_ago['delivered'],
			"Product Category DC 5 Year Ago Profit"=>$data_5y_ago['dc_net'],
			"Product Category DC 5 Year Ago Invoiced Amount"=>$data_5y_ago['dc_profit']
		);
		$this->update( $data_to_update, 'no_history');

	}


	function update_product_category_previous_quarters_data() {


		include_once 'utils/date_functions.php';


		foreach (range(1, 4) as $i) {
			$dates=get_previous_quarters_dates($i);
			$dates_1yb=get_previous_quarters_dates($i+4);


			$sales_product_category_data=$this->get_product_category_sales_data($dates['start'], $dates['end']);
			$sales_product_category_data_1yb=$this->get_product_category_sales_data($dates_1yb['start'], $dates_1yb['end']);

			$data_to_update=array(

				"Product Category $i Quarter Ago Customers"=>$sales_product_category_data['customers'],
				"Product Category $i Quarter Ago Invoices"=>$sales_product_category_data['invoices'],
				"Product Category $i Quarter Ago Profit"=>$sales_product_category_data['profit'],
				"Product Category $i Quarter Ago Invoiced Amount"=>$sales_product_category_data['net'],
				"Product Category $i Quarter Ago Quantity Ordered"=>$sales_product_category_data['ordered'],
				"Product Category $i Quarter Ago Quantity Invoiced"=>$sales_product_category_data['invoiced'],
				"Product Category $i Quarter Ago Quantity Delivered"=>$sales_product_category_data['delivered'],
				"Product Category DC $i Quarter Ago Profit"=>$sales_product_category_data['dc_net'],
				"Product Category DC $i Quarter Ago Invoiced Amount"=>$sales_product_category_data['dc_profit'],

				"Product Category $i Quarter Ago 1YB Customers"=>$sales_product_category_data_1yb['customers'],
				"Product Category $i Quarter Ago 1YB Invoices"=>$sales_product_category_data_1yb['invoices'],
				"Product Category $i Quarter Ago 1YB Profit"=>$sales_product_category_data_1yb['profit'],
				"Product Category $i Quarter Ago 1YB Invoiced Amount"=>$sales_product_category_data_1yb['net'],
				"Product Category $i Quarter Ago 1YB Quantity Ordered"=>$sales_product_category_data_1yb['ordered'],
				"Product Category $i Quarter Ago 1YB Quantity Invoiced"=>$sales_product_category_data_1yb['invoiced'],
				"Product Category $i Quarter Ago 1YB Quantity Delivered"=>$sales_product_category_data_1yb['delivered'],
				"Product Category DC $i Quarter Ago 1YB Profit"=>$sales_product_category_data_1yb['dc_net'],
				"Product Category DC $i Quarter Ago 1YB Invoiced Amount"=>$sales_product_category_data_1yb['dc_profit']


			);
			$this->update( $data_to_update, 'no_history');
		}

	}





	function get_categories($scope='keys') {

		if (   $scope=='objects') {
			include_once 'class.Category.php';
		}

		$type='Category';

		$categories=array();


		$sql=sprintf("select B.`Category Key` from `Category Dimension` C left join `Category Bridge` B on (B.`Category Key`=C.`Category Key`) where `Subject`=%s and `Subject Key`=%d and `Category Branch Type`!='Root'",
			prepare_mysql($type),
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if ($scope=='objects') {
					$categories[$row['Category Key']]=new Category($row['Category Key']);
				}else {
					$categories[$row['Category Key']]=$row['Category Key'];
				}


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $categories;


	}




	function get_category_data() {


		$type='Category';

		$sql=sprintf("select B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where  `Category Branch Type`='Head'  and B.`Subject Key`=%d and B.`Subject`=%s",
			$this->id,
			prepare_mysql($type)
		);

		$category_data=array();



		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {




				$sql=sprintf("select `Category Label`,`Category Code` from `Category Dimension` where `Category Key`=%d", $row['Category Root Key']);


				if ($result2=$this->db->query($sql)) {
					if ($row2 = $result2->fetch()) {
						$root_label=$row2['Category Label'];
						$root_code=$row2['Category Code'];
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}



				if ($row['Is Category Field Other']=='Yes' and $row['Other Note']!='') {
					$value=$row['Other Note'];
				}
				else {
					$value=$row['Category Label'];
				}
				$category_data[]=array(
					'root_label'=>$root_label,
					'root_code'=>$root_code,
					'label'=>$row['Category Label'],
					'code'=>$row['Category Code'],
					'value'=>$value,
					'category_key'=>$row['Category Key']
				);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		return $category_data;
	}


	function get_product_ids() {

		$product_ids='';
		$sql=sprintf('select `Subject Key` from `Category Bridge` where `Category Key`=%d and `Subject Key`>0 ', $this->id);
		$product_ids='';
		$subject_type='';
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$product_ids.=$row['Subject Key'].',';
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$product_ids=preg_replace('/\,$/', '', $product_ids);

		if ($product_ids!='' and $this->get('Category Subject')=='Category') {
			$category_ids=$product_ids;
			$product_ids='';
			$sql=sprintf('select `Subject Key`  from `Category Bridge` where `Category Key` in (%s) and `Subject Key`>0 ',
				$category_ids);
			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$product_ids.=$row['Subject Key'].',';
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				print "$sql\n";
				exit;
			}
			$product_ids=preg_replace('/\,$/', '', $product_ids);

		}
		//print $product_ids;

		return $product_ids;

	}


	function get_product_category_sales_data($from_date, $to_date) {

		$sales_product_category_data=array(
			'customers'=>0,
			'invoices'=>0,
			'net'=>0,
			'profit'=>0,
			'ordered'=>0,
			'invoiced'=>0,
			'delivered'=>0,
			'dc_net'=>0,
			'dc_profit'=>0,

		);

		$product_ids=$this->get_product_ids();




		if ($product_ids!='' and $this->get('Category Branch Type')!='Root') {

			$sql=sprintf("select
		ifnull(count(Distinct `Customer Key`),0) as customers,
		ifnull(count(Distinct `Invoice Key`),0) as invoices,
		round(ifnull(sum( `Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` +(  `Cost Supplier`/`Invoice Currency Exchange Rate`)  ),0),2) as profit,
		round(ifnull(sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`),0),2) as net ,
		round(ifnull(sum(`Shipped Quantity`),0),1) as delivered,
		round(ifnull(sum(`Order Quantity`),0),1) as ordered,
		round(ifnull(sum(`Invoice Quantity`),0),1) as invoiced,
		round(ifnull(sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`),0),2) as dc_net,
		round(ifnull(sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Cost Supplier`)*`Invoice Currency Exchange Rate`),0),2) as dc_profit
		from `Order Transaction Fact` USE INDEX (`Product ID`,`Invoice Date`) where    `Invoice Key` is not NULL  and  `Product ID` in (%s) %s %s ",
				$product_ids,
				($from_date?sprintf('and `Invoice Date`>=%s', prepare_mysql($from_date)):''),
				($to_date?sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)):'')

			);
			//print "$sql\n";
			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {



					$sales_product_category_data['customers']=$row['customers'];
					$sales_product_category_data['invoices']=$row['invoices'];
					$sales_product_category_data['net']=$row['net'];
					$sales_product_category_data['profit']=$row['profit'];
					$sales_product_category_data['ordered']=$row['ordered'];
					$sales_product_category_data['invoiced']=$row['invoiced'];
					$sales_product_category_data['delivered']=$row['delivered'];
					$sales_product_category_data['dc_net']=$row['dc_net'];
					$sales_product_category_data['dc_profit']=$row['dc_profit'];


				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}

			//print "$sql\n";
		}

		return $sales_product_category_data;
	}


}

?>
