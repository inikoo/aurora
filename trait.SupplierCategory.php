<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 August 2016 at 22:09:49 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

trait SupplierCategory {


	function update_supplier_category_up_today_sales() {
		if (!$this->skip_update_sales) {
			$this->update_supplier_category_sales('Today');
			$this->update_supplier_category_sales('Week To Day');
			$this->update_supplier_category_sales('Month To Day');
			$this->update_supplier_category_sales('Year To Day');
		}
	}


	function update_supplier_category_last_period_sales() {
		if (!$this->skip_update_sales) {
			$this->update_supplier_category_sales('Yesterday');
			$this->update_supplier_category_sales('Last Week');
			$this->update_supplier_category_sales('Last Month');
		}
	}


	function update_supplier_category_interval_sales() {
		if (!$this->skip_update_sales) {
			$this->update_supplier_category_sales('Total');
			$this->update_supplier_category_sales('3 Year');
			$this->update_supplier_category_sales('1 Year');
			$this->update_supplier_category_sales('6 Month');
			$this->update_supplier_category_sales('1 Quarter');
			$this->update_supplier_category_sales('1 Month');
			$this->update_supplier_category_sales('10 Day');
			$this->update_supplier_category_sales('1 Week');
		}
	}



	function update_supplier_category_previous_years_data() {

		$sales_data=$this->get_supplier_category_sales_data('1');
		$this->data['1 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_supplier_category_sales_data('2');
		$this->data['2 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_supplier_category_sales_data('3');
		$this->data['3 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_supplier_category_sales_data('4');
		$this->data['4 Year Ago Sales Amount']=$sales_data['sold_amount'];


		$sql=sprintf("update `Supplier Category Dimension` set `1 Year Ago Sales Amount`=%.2f, `2 Year Ago Sales Amount`=%.2f,`3 Year Ago Sales Amount`=%.2f, `4 Year Ago Sales Amount`=%.2f where `Category Key`=%d ",

			$this->data["1 Year Ago Sales Amount"],
			$this->data["2 Year Ago Sales Amount"],
			$this->data["3 Year Ago Sales Amount"],
			$this->data["4 Year Ago Sales Amount"],

			$this->id

		);

		$this->db->exec($sql);


	}


	function get_supplier_category_sales_data($year_tag) {

		$sales_data=array(
			'sold_amount'=>0,


		);


		$sql=sprintf("select sum(`Supplier %s Year Ago Sales Amount`) as sold_amount   from `Category Bridge` B left join  `Supplier Dimension` I  on ( `Subject Key`=`Supplier Key`)  where `Subject`='Supplier' and `Category Key`=%d " ,
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


	function update_supplier_category_sales($interval) {

		//  print $interval;




		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)=calculate_interval_dates($this->db,$interval);




		$supplier_category_data["$db_interval Acc Cost"]=0;
		$supplier_category_data["$db_interval Acc Part Sales"]=0;
		$supplier_category_data["$db_interval Acc Profit"]=0;


		$sql=sprintf("select sum(`Supplier $db_interval Acc Parts Cost`) as cost, sum(`Supplier $db_interval Acc Parts Sold Amount`) as sold, sum(`Supplier $db_interval Acc Parts Profit`) as profit   from `Category Bridge` B left join  `Supplier Dimension` I  on ( `Subject Key`=`Supplier Key`)  where `Subject`='Supplier' and `Category Key`=%d " ,
			$this->id


		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$supplier_category_data["$db_interval Acc Cost"]=$row["cost"];
				$supplier_category_data["$db_interval Acc Part Sales"]=$row["sold"];
				$supplier_category_data["$db_interval Acc Profit"]=$row["profit"];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("update `Supplier Category Dimension` set
                     `$db_interval Acc Cost`=%.2f,
                     `$db_interval Acc Part Sales`=%.2f,
                     `$db_interval Acc Profit`=%.2f
                     where `Category Key`=%d "
			, $supplier_category_data["$db_interval Acc Cost"]
			, $supplier_category_data["$db_interval Acc Part Sales"]
			, $supplier_category_data["$db_interval Acc Profit"]
			, $this->id
		);

		$this->db->exec($sql);

		//     print "$sql\n";

		if ($from_date_1yb) {
			$supplier_category_data["$db_interval Acc 1YB Cost"]=0;
			$supplier_category_data["$db_interval Acc 1YB Part Sales"]=0;
			$supplier_category_data["$db_interval Acc 1YB Profit"]=0;

			$sql=sprintf("select sum(`Supplier $db_interval Acc 1YB Parts Cost`) as cost, sum(`Supplier $db_interval Acc 1YB Parts Sold Amount`) as sold, sum(`Supplier $db_interval Acc 1YB Parts Profit`) as profit   from `Category Bridge` B left join  `Supplier Dimension` I  on ( `Subject Key`=`Supplier Key`)  where `Subject`='Supplier' and `Category Key`=%d " ,
				$this->id


			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$supplier_category_data["$db_interval Acc 1YB Cost"]=$row["cost"];
					$supplier_category_data["$db_interval Acc 1YB Part Sales"]=$row["sold"];
					$supplier_category_data["$db_interval Acc 1YB Profit"]=$row["profit"];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



			$sql=sprintf("update `Supplier Category Dimension` set
                         `$db_interval Acc 1YB Cost`=%.2f,
                         `$db_interval Acc 1YB Part Sales`=%.2f,
                         `$db_interval Acc 1YB Profit`=%.2f
                         where `Category Key`=%d "
				, $supplier_category_data["$db_interval Acc 1YB Cost"]
				, $supplier_category_data["$db_interval Acc 1YB Part Sales"]
				, $supplier_category_data["$db_interval Acc 1YB Profit"]
				, $this->id
			);
			$this->db->exec($sql);

		}


	}


}



?>
