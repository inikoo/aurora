<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 August 2016 at 22:09:49 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

trait SupplierCategory {


	function get_supplier_category_part_skus($type='all') {

		$part_skus='';


		if ($type=='in_use') {

			$sql=sprintf('select `Supplier Part Part SKU`  from `Category Bridge`  CB left join `Supplier Part Dimension` SPD on  (`Subject Key`=`Supplier Part Supplier Key`) left join `Part Dimension` P on (P.`Part SKU`=SPD.`Supplier Part Part SKU`) where `Category Key`=%d and `Subject Key`>0 and `Part Status` in ("In Use","Discontinuing") and `Supplier Part Status`!="Discontinued" ',
				$this->id);

		}else {
			$sql=sprintf('select `Supplier Part Part SKU`  from `Category Bridge`  CB left join `Supplier Part Dimension` SPD on  (`Subject Key`=`Supplier Part Supplier Key`) where `Category Key`=%d and `Subject Key`>0 ',
				$this->id);
		}


		$part_skus='';


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($row['Supplier Part Part SKU']!='') {
					$part_skus.=$row['Supplier Part Part SKU'].',';
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}
		$part_skus=preg_replace('/\,$/', '', $part_skus);



		return $part_skus;

	}


	function update_supplier_category_sales($interval) {

		include_once 'utils/date_functions.php';
		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb)=calculate_interval_dates($this->db, $interval);



		$sales_data=$this->get_supplier_category_sales_data($from_date, $to_date);


		$data_to_update=array(
			"Supplier Category $db_interval Acc Customers"=>$sales_data['customers'],
			"Supplier Category $db_interval Acc Repeat Customers"=>$sales_data['repeat_customers'],
			"Supplier Category $db_interval Acc Deliveries"=>$sales_data['deliveries'],
			"Supplier Category $db_interval Acc Profit"=>$sales_data['profit'],
			"Supplier Category $db_interval Acc Invoiced Amount"=>$sales_data['invoiced_amount'],
			"Supplier Category $db_interval Acc Required"=>$sales_data['required'],
			"Supplier Category $db_interval Acc Dispatched"=>$sales_data['dispatched'],
			"Supplier Category $db_interval Acc Keeping Days"=>$sales_data['keep_days'],
			"Supplier Category $db_interval Acc With Stock Days"=>$sales_data['with_stock_days'],
		);

		//print_r($data_to_update);

		$this->update( $data_to_update, 'no_history');

		if ($from_date_1yb) {


			$sales_data=$this->get_supplier_category_sales_data($from_date_1yb, $to_date_1yb);


			$data_to_update=array(

				"Supplier Category $db_interval Acc 1YB Customers"=>$sales_data['customers'],
				"Supplier Category $db_interval Acc 1YB Repeat Customers"=>$sales_data['repeat_customers'],
				"Supplier Category $db_interval Acc 1YB Deliveries"=>$sales_data['deliveries'],
				"Supplier Category $db_interval Acc 1YB Profit"=>$sales_data['profit'],
				"Supplier Category $db_interval Acc 1YB Invoiced Amount"=>$sales_data['invoiced_amount'],
				"Supplier Category $db_interval Acc 1YB Required"=>$sales_data['required'],
				"Supplier Category $db_interval Acc 1YB Dispatched"=>$sales_data['dispatched'],
				"Supplier Category $db_interval Acc 1YB Keeping Days"=>$sales_data['keep_days'],
				"Supplier Category $db_interval Acc 1YB With Stock Days"=>$sales_data['with_stock_days'],

			);
			$this->update( $data_to_update, 'no_history');


		}


	}


	function update_supplier_category_previous_years_data() {

		$data_1y_ago=$this->get_supplier_category_sales_data(date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-01-01 00:00:00'));
		$data_2y_ago=$this->get_supplier_category_sales_data(date('Y-01-01 00:00:00', strtotime('-2 year')), date('Y-01-01 00:00:00', strtotime('-1 year')));
		$data_3y_ago=$this->get_supplier_category_sales_data(date('Y-01-01 00:00:00', strtotime('-3 year')), date('Y-01-01 00:00:00', strtotime('-2 year')));
		$data_4y_ago=$this->get_supplier_category_sales_data(date('Y-01-01 00:00:00', strtotime('-4 year')), date('Y-01-01 00:00:00', strtotime('-3 year')));
		$data_5y_ago=$this->get_supplier_category_sales_data(date('Y-01-01 00:00:00', strtotime('-5 year')), date('Y-01-01 00:00:00', strtotime('-4 year')));




		$data_to_update=array(
			"Supplier Category 1 Year Ago Customers"=>$data_1y_ago['customers'],
			"Supplier Category 1 Year Ago Repeat Customers"=>$data_1y_ago['repeat_customers'],
			"Supplier Category 1 Year Ago Deliveries"=>$data_1y_ago['deliveries'],
			"Supplier Category 1 Year Ago Profit"=>$data_1y_ago['profit'],
			"Supplier Category 1 Year Ago Invoiced Amount"=>$data_1y_ago['invoiced_amount'],
			"Supplier Category 1 Year Ago Required"=>$data_1y_ago['required'],
			"Supplier Category 1 Year Ago Dispatched"=>$data_1y_ago['dispatched'],
			"Supplier Category 1 Year Ago Keeping Day"=>$data_1y_ago['keep_days'],
			"Supplier Category 1 Year Ago With Stock Days"=>$data_1y_ago['with_stock_days'],

			"Supplier Category 2 Year Ago Customers"=>$data_2y_ago['customers'],
			"Supplier Category 2 Year Ago Repeat Customers"=>$data_2y_ago['repeat_customers'],
			"Supplier Category 2 Year Ago Deliveries"=>$data_2y_ago['deliveries'],
			"Supplier Category 2 Year Ago Profit"=>$data_2y_ago['profit'],
			"Supplier Category 2 Year Ago Invoiced Amount"=>$data_2y_ago['invoiced_amount'],
			"Supplier Category 2 Year Ago Required"=>$data_2y_ago['required'],
			"Supplier Category 2 Year Ago Dispatched"=>$data_2y_ago['dispatched'],
			"Supplier Category 2 Year Ago Keeping Day"=>$data_2y_ago['keep_days'],
			"Supplier Category 2 Year Ago With Stock Days"=>$data_2y_ago['with_stock_days'],

			"Supplier Category 3 Year Ago Customers"=>$data_3y_ago['customers'],
			"Supplier Category 3 Year Ago Repeat Customers"=>$data_3y_ago['repeat_customers'],
			"Supplier Category 3 Year Ago Deliveries"=>$data_3y_ago['deliveries'],
			"Supplier Category 3 Year Ago Profit"=>$data_3y_ago['profit'],
			"Supplier Category 3 Year Ago Invoiced Amount"=>$data_3y_ago['invoiced_amount'],
			"Supplier Category 3 Year Ago Required"=>$data_3y_ago['required'],
			"Supplier Category 3 Year Ago Dispatched"=>$data_3y_ago['dispatched'],
			"Supplier Category 3 Year Ago Keeping Day"=>$data_3y_ago['keep_days'],
			"Supplier Category 3 Year Ago With Stock Days"=>$data_3y_ago['with_stock_days'],

			"Supplier Category 4 Year Ago Customers"=>$data_4y_ago['customers'],
			"Supplier Category 4 Year Ago Repeat Customers"=>$data_4y_ago['repeat_customers'],
			"Supplier Category 4 Year Ago Deliveries"=>$data_4y_ago['deliveries'],
			"Supplier Category 4 Year Ago Profit"=>$data_4y_ago['profit'],
			"Supplier Category 4 Year Ago Invoiced Amount"=>$data_4y_ago['invoiced_amount'],
			"Supplier Category 4 Year Ago Required"=>$data_4y_ago['required'],
			"Supplier Category 4 Year Ago Dispatched"=>$data_4y_ago['dispatched'],
			"Supplier Category 4 Year Ago Keeping Day"=>$data_4y_ago['keep_days'],
			"Supplier Category 4 Year Ago With Stock Days"=>$data_4y_ago['with_stock_days'],

			"Supplier Category 5 Year Ago Customers"=>$data_5y_ago['customers'],
			"Supplier Category 5 Year Ago Repeat Customers"=>$data_5y_ago['repeat_customers'],
			"Supplier Category 5 Year Ago Deliveries"=>$data_5y_ago['deliveries'],
			"Supplier Category 5 Year Ago Profit"=>$data_5y_ago['profit'],
			"Supplier Category 5 Year Ago Invoiced Amount"=>$data_5y_ago['invoiced_amount'],
			"Supplier Category 5 Year Ago Required"=>$data_5y_ago['required'],
			"Supplier Category 5 Year Ago Dispatched"=>$data_5y_ago['dispatched'],
			"Supplier Category 5 Year Ago Keeping Day"=>$data_5y_ago['keep_days'],
			"Supplier Category 5 Year Ago With Stock Days"=>$data_5y_ago['with_stock_days'],


		);
		$this->update( $data_to_update, 'no_history');






	}


	function update_supplier_category_previous_quarters_data() {


		include_once 'utils/date_functions.php';


		foreach (range(1, 4) as $i) {
			$dates=get_previous_quarters_dates($i);
			$dates_1yb=get_previous_quarters_dates($i+4);


			$sales_data=$this->get_supplier_category_sales_data($dates['start'], $dates['end']);
			$sales_data_1yb=$this->get_supplier_category_sales_data($dates_1yb['start'], $dates_1yb['end']);

			$data_to_update=array(
				"Supplier Category $i Quarter Ago Customers"=>$sales_data['customers'],
				"Supplier Category $i Quarter Ago Repeat Customers"=>$sales_data['repeat_customers'],
				"Supplier Category $i Quarter Ago Deliveries"=>$sales_data['deliveries'],
				"Supplier Category $i Quarter Ago Profit"=>$sales_data['profit'],
				"Supplier Category $i Quarter Ago Invoiced Amount"=>$sales_data['invoiced_amount'],
				"Supplier Category $i Quarter Ago Required"=>$sales_data['required'],
				"Supplier Category $i Quarter Ago Dispatched"=>$sales_data['dispatched'],
				"Supplier Category $i Quarter Ago Keeping Day"=>$sales_data['keep_days'],
				"Supplier Category $i Quarter Ago With Stock Days"=>$sales_data['with_stock_days'],

				"Supplier Category $i Quarter Ago 1YB Customers"=>$sales_data_1yb['customers'],
				"Supplier Category $i Quarter Ago 1YB Repeat Customers"=>$sales_data_1yb['repeat_customers'],
				"Supplier Category $i Quarter Ago 1YB Deliveries"=>$sales_data_1yb['deliveries'],
				"Supplier Category $i Quarter Ago 1YB Profit"=>$sales_data_1yb['profit'],
				"Supplier Category $i Quarter Ago 1YB Invoiced Amount"=>$sales_data_1yb['invoiced_amount'],
				"Supplier Category $i Quarter Ago 1YB Required"=>$sales_data_1yb['required'],
				"Supplier Category $i Quarter Ago 1YB Dispatched"=>$sales_data_1yb['dispatched'],
				"Supplier Category $i Quarter Ago 1YB Keeping Day"=>$sales_data_1yb['keep_days'],
				"Supplier Category $i Quarter Ago 1YB With Stock Days"=>$sales_data_1yb['with_stock_days'],
			);
			$this->update( $data_to_update, 'no_history');
		}

	}


	function get_supplier_category_customers_total_data($part_skus) {

		$repeat_customers=0;


		$sql=sprintf('select count(`Customer Part Customer Key`) as num  from `Customer Part Bridge` where `Customer Part Delivery Notes`>1 and `Customer Part Part SKU` in (%s)    ',
			$part_skus
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$repeat_customers=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		return $repeat_customers;

	}


	function get_supplier_category_sales_data($from_date, $to_date) {

		$sales_data=array(
			'invoiced_amount'=>0,
			'profit'=>0,
			'required'=>0,
			'dispatched'=>0,
			'deliveries'=>0,
			'customers'=>0,
			'repeat_customers'=>0,
			'keep_days'=>0,
			'with_stock_days'=>0,

		);

		$part_skus=$this->get_supplier_category_part_skus();

		if ($part_skus!='') {

			if ($from_date=='' and  $to_date=='') {
				$sales_data['repeat_customers']=$this->get_supplier_category_customers_total_data($part_skus);
			}



			$sql=sprintf("select count(distinct `Delivery Note Customer Key`) as customers, count( distinct ITF.`Delivery Note Key`) as deliveries, round(ifnull(sum(`Amount In`),0),2) as invoiced_amount,round(ifnull(sum(`Amount In`+`Inventory Transaction Amount`),0),2) as profit,round(ifnull(sum(`Inventory Transaction Quantity`),0),1) as dispatched,round(ifnull(sum(`Required`),0),1) as required from `Inventory Transaction Fact` ITF  left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) where `Inventory Transaction Type` like 'Sale' and `Part SKU` in (%s) %s %s" ,
				$part_skus,
				($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),
				($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')
			);

			//print "$sql\n";

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$sales_data['customers']=$row['customers'];
					$sales_data['invoiced_amount']=$row['invoiced_amount'];
					$sales_data['profit']=$row['profit'];
					$sales_data['dispatched']=-1.0*$row['dispatched'];
					$sales_data['required']=$row['required'];
					$sales_data['deliveries']=$row['deliveries'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


		}

		return $sales_data;

	}




	function update_supplier_category_parts() {


		$parts_skus=$this->get_supplier_category_part_skus();


		$supplier_number_parts=0;
		$supplier_number_active_parts=0;
		$supplier_number_surplus_parts=0;
		$supplier_number_optimal_parts=0;
		$supplier_number_low_parts=0;
		$supplier_number_critical_parts=0;
		$supplier_number_out_of_stock_parts=0;


		if ($parts_skus!='') {


			$supplier_number_parts=count(preg_split('/,/', $parts_skus));


			

			$parts_skus=$this->get_supplier_category_part_skus('in_use');

			if ($parts_skus!='') {

				$sql=sprintf('select count(*) as num , sum(if(`Part Stock Status`="Surplus",1,0)) as surplus, sum(if(`Part Stock Status`="Optimal",1,0)) as optimal, sum(if(`Part Stock Status`="Low",1,0)) as low, sum(if(`Part Stock Status`="Critical",1,0)) as critical, sum(if(`Part Stock Status`="Out_Of_Stock",1,0)) as out_of_stock from `Supplier Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)  where `Supplier Part Part SKU` in (%s) ',
					addslashes($parts_skus)
				);

				//print $sql;
				if ($result=$this->db->query($sql)) {
					if ($row = $result->fetch()) {
						//print_r($row);
						$supplier_number_active_parts=$row['num'];
						if ($row['num']>0) {
							$supplier_number_surplus_parts=$row['surplus'];
							$supplier_number_optimal_parts=$row['optimal'];
							$supplier_number_low_parts=$row['low'];
							$supplier_number_critical_parts=$row['critical'];
							$supplier_number_out_of_stock_parts=$row['out_of_stock'];
						}

					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}

			}



		}

		

		$this->update(array(
				'Supplier Category Number Parts'=>$supplier_number_parts,
				'Supplier Category Number Active Parts'=>$supplier_number_active_parts,
				'Supplier Category Number Surplus Parts'=>$supplier_number_surplus_parts,
				'Supplier Category Number Optimal Parts'=>$supplier_number_optimal_parts,
				'Supplier Category Number Low Parts'=>$supplier_number_low_parts,
				'Supplier Category Number Critical Parts'=>$supplier_number_critical_parts,
				'Supplier Category Number Out Of Stock Parts'=>$supplier_number_out_of_stock_parts,

			), 'no_history');


		


	}


}



?>
