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

include_once('class.Store.php');
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


				$dates=date_frequency_range($this->db, $timeseries->get('Timeseries Frequency'), $from, $to);

				foreach ($dates as $date_frequency_period) {

					list($invoices, $customers, $net, $dc_net)=$this->get_product_timeseries_record_data($timeseries, $date_frequency_period);


					$_date=gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00')) ;

					if ($invoices!=0 or $customers!=0 or $net!=0) {
						list($timeseries_record_key, $date)=$timeseries->create_record(array('Timeseries Record Date'=> $_date ));
						$sql=sprintf('update `Timeseries Record Dimension` set
                    `Timeseries Record Integer A`=%d ,
                    `Timeseries Record Integer B`=%d ,
                    `Timeseries Record Float A`=%.2f ,
                    `Timeseries Record Float B`=%.2f ,
                    `Timeseries Record Type`=%s
                    where `Timeseries Record Key`=%d
                      ',
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

			if ($timeseries->get('Timeseries Number Records')==0)
				$timeseries->update(array('Timeseries Updated'=>gmdate('Y-m-d H:i:s')), 'no_history');


		}

	}


	function get_product_timeseries_record_data($timeseries, $date_frequency_period) {

		$product_ids='';
		$sql=sprintf('select `Subject Key`,`Subject` from `Category Bridge` where `Category Key`=%d and `Subject Key`>0 ', $this->id);
		$product_ids='';
		$subject_type='';
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$product_ids.=$row['Subject Key'].',';
				$subject_type=$row['Subject'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}
		$product_ids=preg_replace('/\,$/', '', $product_ids);

		if ($subject_type=='Category') {
			$category_ids=$product_ids;
			$product_ids='';
			$sql=sprintf('select `Subject Key` ,`Subject` from `Category Bridge` where `Category Key` in (%s) and `Subject Key`>0 ',
				$category_ids);
			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$product_ids.=$row['Subject Key'].',';
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}
			$product_ids=preg_replace('/\,$/', '', $product_ids);

		}


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
			'In Use'=>0, 'Not In Use'=>0
		);

		$sql=sprintf("select count(*) as num ,`Part Status` from  `Part Dimension` P left join `Category Bridge` B on (P.`Part SKU`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Part' group by  `Part Status`   ",
			$this->id);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$elements_numbers[$row['Part Status']]=number($row['num']);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		if ($elements_numbers['Not In Use']>0 and $elements_numbers['In Use']==0) {
			$this->data['Product Category Status`']='NotInUse';
		}else {
			$this->data['Product Category Status`']='InUse';
		}

		$sql=sprintf("update `Product Category Dimension` set `Product Category Status`=%s  where `Product Category Key`=%d",
			prepare_mysql($this->data['Product Category Status`']),
			$this->id
		);

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


	function update_product_category_sales($interval) {

		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)=calculate_interval_dates($this->db, $interval);



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

								from `Part Data` ITF left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Category Key`=%d" ,
			$this->id);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Product Category $db_interval Acc Profit"]=$row['profit'];
				$this->data["Product Category $db_interval Acc Profit After Storing"]=$row['profit_after_storing'];
				$this->data["Product Category $db_interval Acc Acquired"]=$row['bought'];
				$this->data["Product Category $db_interval Acc Sold Amount"]=$row['sold_amount'];
				$this->data["Product Category $db_interval Acc Sold"]=$row['sold'];
				$this->data["Product Category $db_interval Acc Provided"]=-1.0*$row['dispatched'];
				$this->data["Product Category $db_interval Acc Required"]=$row['required'];
				$this->data["Product Category $db_interval Acc Given"]=$row['given'];
				$this->data["Product Category $db_interval Acc Broken"]=$row['broken'];
				$this->data["Product Category $db_interval Acc Lost"]=$row['lost'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		if ($this->data["Product Category $db_interval Acc Sold Amount"]!=0)
			$margin=$this->data["Product Category $db_interval Acc Profit After Storing"]/$this->data["Product Category $db_interval Acc Sold Amount"];
		else
			$margin=0;
		$this->data["Product Category $db_interval Acc Margin"]=$margin;


		$sql=sprintf("update `Product Category Dimension` set
                     `Product Category $db_interval Acc Required`=%f ,
                     `Product Category $db_interval Acc Provided`=%f,
                     `Product Category $db_interval Acc Given`=%f ,
                     `Product Category $db_interval Acc Sold Amount`=%f ,
                     `Product Category $db_interval Acc Profit`=%f ,
                     `Product Category $db_interval Acc Profit After Storing`=%f ,
                     `Product Category $db_interval Acc Sold`=%f ,
                     `Product Category $db_interval Acc Margin`=%s,
                     `Product Category $db_interval Acc Acquired`=%s,
                     `Product Category $db_interval Acc Broken`=%s,
                     `Product Category $db_interval Acc Lost`=%s
                      where
                     `Product Category Key`=%d "
			, $this->data["Product Category $db_interval Acc Required"]
			, $this->data["Product Category $db_interval Acc Provided"]
			, $this->data["Product Category $db_interval Acc Given"]
			, $this->data["Product Category $db_interval Acc Sold Amount"]
			, $this->data["Product Category $db_interval Acc Profit"]
			, $this->data["Product Category $db_interval Acc Profit After Storing"]
			, $this->data["Product Category $db_interval Acc Sold"]
			, $this->data["Product Category $db_interval Acc Margin"]
			, $this->data["Product Category $db_interval Acc Acquired"]
			, $this->data["Product Category $db_interval Acc Broken"]
			, $this->data["Product Category $db_interval Acc Lost"]

			, $this->id);

		$this->db->exec($sql);
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

								from `Part Data` ITF left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Category Key`=%d" ,
				$this->id);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Product Category $db_interval Acc 1YB Profit"]=$row['profit'];
					$this->data["Product Category $db_interval Acc 1YB Profit After Storing"]=$row['profit_after_storing'];
					$this->data["Product Category $db_interval Acc 1YB Acquired"]=$row['bought'];
					$this->data["Product Category $db_interval Acc 1YB Sold Amount"]=$row['sold_amount'];
					$this->data["Product Category $db_interval Acc 1YB Sold"]=$row['sold'];
					$this->data["Product Category $db_interval Acc 1YB Provided"]=-1.0*$row['dispatched'];
					$this->data["Product Category $db_interval Acc 1YB Required"]=$row['required'];
					$this->data["Product Category $db_interval Acc 1YB Given"]=$row['given'];
					$this->data["Product Category $db_interval Acc 1YB Broken"]=$row['broken'];
					$this->data["Product Category $db_interval Acc 1YB Lost"]=$row['lost'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



			if ($this->data["Product Category $db_interval Acc 1YB Sold Amount"]!=0)
				$margin=$this->data["Product Category $db_interval Acc 1YB Profit After Storing"]/$this->data["Product Category $db_interval Acc 1YB Sold Amount"];
			else
				$margin=0;
			$this->data["Product Category $db_interval Acc 1YB Margin"]=$margin;


			$sql=sprintf("update `Product Category Dimension` set
                     `Product Category $db_interval Acc 1YB Required`=%f ,
                     `Product Category $db_interval Acc 1YB Provided`=%f,
                     `Product Category $db_interval Acc 1YB Given`=%f ,
                     `Product Category $db_interval Acc 1YB Sold Amount`=%f ,
                     `Product Category $db_interval Acc 1YB Profit`=%f ,
                     `Product Category $db_interval Acc 1YB Profit After Storing`=%f ,
                     `Product Category $db_interval Acc 1YB Sold`=%f ,
                     `Product Category $db_interval Acc 1YB Margin`=%s,
                     `Product Category $db_interval Acc 1YB Acquired`=%s,
                     `Product Category $db_interval Acc 1YB Broken`=%s,
                     `Product Category $db_interval Acc 1YB Lost`=%s
                      where
                     `Product Category Key`=%d "
				, $this->data["Product Category $db_interval Acc 1YB Required"]
				, $this->data["Product Category $db_interval Acc 1YB Provided"]
				, $this->data["Product Category $db_interval Acc 1YB Given"]
				, $this->data["Product Category $db_interval Acc 1YB Sold Amount"]
				, $this->data["Product Category $db_interval Acc 1YB Profit"]
				, $this->data["Product Category $db_interval Acc 1YB Profit After Storing"]
				, $this->data["Product Category $db_interval Acc 1YB Sold"]
				, $this->data["Product Category $db_interval Acc 1YB Margin"]
				, $this->data["Product Category $db_interval Acc 1YB Acquired"]
				, $this->data["Product Category $db_interval Acc 1YB Broken"]
				, $this->data["Product Category $db_interval Acc 1YB Lost"]

				, $this->id);

			$this->db->exec($sql);
			//print "$sql\n";



			$this->data["Product Category $db_interval Acc 1YD Required"]=($this->data["Product Category $db_interval Acc 1YB Required"]==0?0:($this->data["Product Category $db_interval Acc Required"]-$this->data["Product Category $db_interval Acc 1YB Required"])/$this->data["Product Category $db_interval Acc 1YB Required"]);
			$this->data["Product Category $db_interval Acc 1YD Provided"]=($this->data["Product Category $db_interval Acc 1YB Provided"]==0?0:($this->data["Product Category $db_interval Acc Provided"]-$this->data["Product Category $db_interval Acc 1YB Provided"])/$this->data["Product Category $db_interval Acc 1YB Provided"]);
			$this->data["Product Category $db_interval Acc 1YD Given"]=($this->data["Product Category $db_interval Acc 1YB Given"]==0?0:($this->data["Product Category $db_interval Acc Given"]-$this->data["Product Category $db_interval Acc 1YB Given"])/$this->data["Product Category $db_interval Acc 1YB Given"]);
			$this->data["Product Category $db_interval Acc 1YD Sold Amount"]=($this->data["Product Category $db_interval Acc 1YB Sold Amount"]==0?0:($this->data["Product Category $db_interval Acc Sold Amount"]-$this->data["Product Category $db_interval Acc 1YB Sold Amount"])/$this->data["Product Category $db_interval Acc 1YB Sold Amount"]);
			$this->data["Product Category $db_interval Acc 1YD Profit"]=($this->data["Product Category $db_interval Acc 1YB Profit"]==0?0:($this->data["Product Category $db_interval Acc Profit"]-$this->data["Product Category $db_interval Acc 1YB Profit"])/$this->data["Product Category $db_interval Acc 1YB Profit"]);
			$this->data["Product Category $db_interval Acc 1YD Profit After Storing"]=($this->data["Product Category $db_interval Acc 1YB Profit After Storing"]==0?0:($this->data["Product Category $db_interval Acc Profit After Storing"]-$this->data["Product Category $db_interval Acc 1YB Profit After Storing"])/$this->data["Product Category $db_interval Acc 1YB Profit After Storing"]);
			$this->data["Product Category $db_interval Acc 1YD Sold"]=($this->data["Product Category $db_interval Acc 1YB Sold"]==0?0:($this->data["Product Category $db_interval Acc Sold"]-$this->data["Product Category $db_interval Acc 1YB Sold"])/$this->data["Product Category $db_interval Acc 1YB Sold"]);
			$this->data["Product Category $db_interval Acc 1YD Margin"]=($this->data["Product Category $db_interval Acc 1YB Margin"]==0?0:($this->data["Product Category $db_interval Acc Margin"]-$this->data["Product Category $db_interval Acc 1YB Margin"])/$this->data["Product Category $db_interval Acc 1YB Margin"]);


			$sql=sprintf("update `Product Category Dimension` set
                     `Product Category $db_interval Acc 1YD Required`=%f ,
                     `Product Category $db_interval Acc 1YD Provided`=%f,
                     `Product Category $db_interval Acc 1YD Given`=%f ,
                     `Product Category $db_interval Acc 1YD Sold Amount`=%f ,
                     `Product Category $db_interval Acc 1YD Profit`=%f ,
                     `Product Category $db_interval Acc 1YD Profit After Storing`=%f ,
                     `Product Category $db_interval Acc 1YD Sold`=%f ,
                     `Product Category $db_interval Acc 1YD Margin`=%s where
                      `Product Category Key`=%d "
				, $this->data["Product Category $db_interval Acc 1YD Required"]
				, $this->data["Product Category $db_interval Acc 1YD Provided"]
				, $this->data["Product Category $db_interval Acc 1YD Given"]
				, $this->data["Product Category $db_interval Acc 1YD Sold Amount"]
				, $this->data["Product Category $db_interval Acc 1YD Profit"]
				, $this->data["Product Category $db_interval Acc 1YD Profit After Storing"]
				, $this->data["Product Category $db_interval Acc 1YD Sold"]
				, $this->data["Product Category $db_interval Acc 1YD Margin"]

				, $this->id);

			$this->db->exec($sql);
			//print "$sql\n";




		}


	}


	function update_product_category_previous_years_data() {

		$sales_data=$this->get_product_category_sales_data('1');
		$this->data['Product Category 1 Year Ago Sold Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_product_category_sales_data('2');
		$this->data['Product Category 2 Year Ago Sold Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_product_category_sales_data('3');
		$this->data['Product Category 3 Year Ago Sold Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_product_category_sales_data('4');
		$this->data['Product Category 4 Year Ago Sold Amount']=$sales_data['sold_amount'];


		$sql=sprintf("update `Product Category Dimension` set `Product Category 1 Year Ago Sold Amount`=%.2f, `Product Category 2 Year Ago Sold Amount`=%.2f,`Product Category 3 Year Ago Sold Amount`=%.2f, `Product Category 4 Year Ago Sold Amount`=%.2f where `Product Category Key`=%d ",

			$this->data["Product Category 1 Year Ago Sold Amount"],
			$this->data["Product Category 2 Year Ago Sold Amount"],
			$this->data["Product Category 3 Year Ago Sold Amount"],
			$this->data["Product Category 4 Year Ago Sold Amount"],

			$this->id

		);
		$this->db->exec($sql);


	}


	function get_product_category_sales_data($year_tag) {

		$sales_data=array(
			'sold_amount'=>0,


		);


		$sql=sprintf("select sum(`Part %s Year Ago Sold Amount`) as sold_amount   from `Category Bridge` B left join  `Part Data` P  on ( `Subject Key`=`Part SKU`)  where `Subject`='Part' and `Category Key`=%d " ,
			$year_tag,
			$this->id


		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$sales_data['sold_amount']=$row['sold_amount'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		return $sales_data;
	}



}

?>
