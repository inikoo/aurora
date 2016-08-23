<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 August 2016 at 22:03:03 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

trait PartCategory {


	function create_part_timeseries($data) {

		

		$data['Timeseries Parent']='Category';
		$data['Timeseries Parent Key']=$this->id;

		$timeseries=new Timeseries('find', $data, 'create');
		if ($timeseries->new or true) {




			require_once 'utils/date_functions.php';

			if ($this->data['Part Category Valid From']!='') {
				$from=date('Y-m-d', strtotime($this->get('Part Category Valid From')));

			}else {
				$from='';
			}

			if ($this->get('Part Category Status')=='NotInUse') {
				$to=date('Y-m-d', strtotime($this->get('Part Category Valid To')));
			}else {
				$to=date('Y-m-d');
			}




			if ($from and $to) {


				$dates=date_frequency_range($this->db, $timeseries->get('Timeseries Frequency'), $from, $to);

				foreach ($dates as $date_frequency_period) {

					list($sold_amount,$deliveries,$skos)=$this->get_part_timeseries_record_data($timeseries, $date_frequency_period);


					$_date=gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00')) ;

					if ($skos!=0 or $deliveries!=0 or $sold_amount!=0) {
						list($timeseries_record_key, $date)=$timeseries->create_record(array('Timeseries Record Date'=> $_date ));
						$sql=sprintf('update `Timeseries Record Dimension` set
                    `Timeseries Record Integer A`=%d ,
                    `Timeseries Record Integer B`=%d ,
                    `Timeseries Record Float A`=%.2f ,
                    
                    `Timeseries Record Type`=%s
                    where `Timeseries Record Key`=%d
                      ',
							$deliveries,
							$skos,
							$sold_amount,
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

	function get_part_timeseries_record_data($timeseries, $date_frequency_period) {
		$part_skus='';
		$sql=sprintf('select group_concat(`Subject Key`) as part_skus ,`Subject` from `Category Bridge` where `Category Key`=%d and `Subject Key`>0 ', $this->id);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				if ($row['Subject']=='Part') {
					$part_skus=$row['part_skus'];
				}elseif ($row['Subject']=='Category') {
                
					$sql=sprintf('select group_concat(`Subject Key`) as part_skus ,`Subject` from `Category Bridge` where `Category Key` in (%s) and `Subject Key`>0 ', $row['part_skus']);
					if ($result2=$this->db->query($sql)) {
						if ($row2 = $result2->fetch()) {
							$part_skus=$row2['part_skus'];

						}
					}else {
						print_r($error_info=$this->db->errorInfo());
						print $sql;
						exit;
					}


				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

	


		if ($part_skus=='') {
			return array(0, 0, 0);
		}

		if ($timeseries->get('Timeseries Scope')=='Sales') {



			$sql=sprintf("select count(distinct `Delivery Note Key`)  as deliveries,sum(`Amount In`) net,sum(`Inventory Transaction Quantity`) skos

			from `Inventory Transaction Fact` where `Part SKU` in (%s) and `Inventory Transaction Type`='Sale' and  `Date`>=%s  and   `Date`<=%s  " ,
				$part_skus,
				prepare_mysql($date_frequency_period['from']),
				prepare_mysql($date_frequency_period['to'])
			);



			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {


					$deliveries=$row['deliveries'];
					$skos=round(-1*$row['skos']);
					$net=$row['net'];
				}else {
					$deliveries=0;
					$skos=0;
					$net=0;
				}

				return array($net, $deliveries, $skos);

			}else {
				print_r($error_info=$this->db->errorInfo());
				print "$sql\n";
				exit;
			}



		}


	}



	function update_part_category_up_today_sales() {

		if (!$this->skip_update_sales) {
			$this->update_part_category_sales('Today');
			$this->update_part_category_sales('Week To Day');
			$this->update_part_category_sales('Month To Day');
			$this->update_part_category_sales('Year To Day');
		}
	}


	function update_part_category_last_period_sales() {
		if (!$this->skip_update_sales) {
			$this->update_part_category_sales('Yesterday');
			$this->update_part_category_sales('Last Week');
			$this->update_part_category_sales('Last Month');
		}
	}


	function update_part_category_interval_sales() {
		if (!$this->skip_update_sales) {
			$this->update_part_category_sales('Total');
			$this->update_part_category_sales('3 Year');
			$this->update_part_category_sales('1 Year');
			$this->update_part_category_sales('6 Month');
			$this->update_part_category_sales('1 Quarter');
			$this->update_part_category_sales('1 Month');
			$this->update_part_category_sales('10 Day');
			$this->update_part_category_sales('1 Week');
		}
	}


	function get_subcategories_status_numbers($options='') {

		$elements_numbers=array(
			'InUse'=>0, 'NotInUse'=>0
		);

		$sql=sprintf("select count(*) as num ,`Part Category Status` from  `Part Category Dimension` P left join `Category Dimension` C on (C.`Category Key`=P.`Part Category Key`)  where `Category Parent Key`=%d  group by  `Part Category Status`   ",
			$this->id);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($options=='Formatted') {
					$elements_numbers[$row['Part Category Status']]=number($row['num']);

				}else {
					$elements_numbers[$row['Part Category Status']]=$row['num'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

    return $elements_numbers;

	}


	function update_part_category_status() {

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
			$this->data['Part Category Status`']='NotInUse';
		}else {
			$this->data['Part Category Status`']='InUse';
		}

		$sql=sprintf("update `Part Category Dimension` set `Part Category Status`=%s  where `Part Category Key`=%d",
			prepare_mysql($this->data['Part Category Status`']),
			$this->id
		);

		$this->db->exec($sql);


	}


	function update_part_stock_status() {

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




		$sql=sprintf("update `Part Category Dimension` set `Part Category Number Surplus Parts`=%d ,`Part Category Number Optimal Parts`=%d ,`Part Category Number Low Parts`=%d ,`Part Category Number Critical Parts`=%d ,`Part Category Number Out Of Stock Parts`=%d ,`Part Category Number Error Parts`=%d  where `Part Category Key`=%d",
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


	function update_part_category_sales($interval) {

		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)=calculate_interval_dates($this->db,$interval);



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
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
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
			, $this->data["Part Category $db_interval Acc Required"]
			, $this->data["Part Category $db_interval Acc Provided"]
			, $this->data["Part Category $db_interval Acc Given"]
			, $this->data["Part Category $db_interval Acc Sold Amount"]
			, $this->data["Part Category $db_interval Acc Profit"]
			, $this->data["Part Category $db_interval Acc Profit After Storing"]
			, $this->data["Part Category $db_interval Acc Sold"]
			, $this->data["Part Category $db_interval Acc Margin"]
			, $this->data["Part Category $db_interval Acc Acquired"]
			, $this->data["Part Category $db_interval Acc Broken"]
			, $this->data["Part Category $db_interval Acc Lost"]

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
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
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
				, $this->data["Part Category $db_interval Acc 1YB Required"]
				, $this->data["Part Category $db_interval Acc 1YB Provided"]
				, $this->data["Part Category $db_interval Acc 1YB Given"]
				, $this->data["Part Category $db_interval Acc 1YB Sold Amount"]
				, $this->data["Part Category $db_interval Acc 1YB Profit"]
				, $this->data["Part Category $db_interval Acc 1YB Profit After Storing"]
				, $this->data["Part Category $db_interval Acc 1YB Sold"]
				, $this->data["Part Category $db_interval Acc 1YB Margin"]
				, $this->data["Part Category $db_interval Acc 1YB Acquired"]
				, $this->data["Part Category $db_interval Acc 1YB Broken"]
				, $this->data["Part Category $db_interval Acc 1YB Lost"]

				, $this->id);

			$this->db->exec($sql);
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
				, $this->data["Part Category $db_interval Acc 1YD Required"]
				, $this->data["Part Category $db_interval Acc 1YD Provided"]
				, $this->data["Part Category $db_interval Acc 1YD Given"]
				, $this->data["Part Category $db_interval Acc 1YD Sold Amount"]
				, $this->data["Part Category $db_interval Acc 1YD Profit"]
				, $this->data["Part Category $db_interval Acc 1YD Profit After Storing"]
				, $this->data["Part Category $db_interval Acc 1YD Sold"]
				, $this->data["Part Category $db_interval Acc 1YD Margin"]

				, $this->id);

			$this->db->exec($sql);
			//print "$sql\n";




		}


	}


	function update_part_category_previous_years_data() {

		$sales_data=$this->get_part_category_sales_data('1');
		$this->data['Part Category 1 Year Ago Sold Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_part_category_sales_data('2');
		$this->data['Part Category 2 Year Ago Sold Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_part_category_sales_data('3');
		$this->data['Part Category 3 Year Ago Sold Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_part_category_sales_data('4');
		$this->data['Part Category 4 Year Ago Sold Amount']=$sales_data['sold_amount'];


		$sql=sprintf("update `Part Category Dimension` set `Part Category 1 Year Ago Sold Amount`=%.2f, `Part Category 2 Year Ago Sold Amount`=%.2f,`Part Category 3 Year Ago Sold Amount`=%.2f, `Part Category 4 Year Ago Sold Amount`=%.2f where `Part Category Key`=%d ",

			$this->data["Part Category 1 Year Ago Sold Amount"],
			$this->data["Part Category 2 Year Ago Sold Amount"],
			$this->data["Part Category 3 Year Ago Sold Amount"],
			$this->data["Part Category 4 Year Ago Sold Amount"],

			$this->id

		);
		$this->db->exec($sql);


	}


	function get_part_category_sales_data($year_tag) {

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
