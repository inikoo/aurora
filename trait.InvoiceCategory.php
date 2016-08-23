<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 August 2016 at 22:12:35 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


trait InvoiceCategory {

	function update_invoice_category_up_today_sales() {
		if (!$this->skip_update_sales) {
			$this->update_invoice_category_sales('Today');
			$this->update_invoice_category_sales('Week To Day');
			$this->update_invoice_category_sales('Month To Day');
			$this->update_invoice_category_sales('Year To Day');
		}
	}


	function update_invoice_category_last_period_sales() {
		if (!$this->skip_update_sales) {
			$this->update_invoice_category_sales('Yesterday');
			$this->update_invoice_category_sales('Last Week');
			$this->update_invoice_category_sales('Last Month');
		}
	}


	function update_invoice_category_interval_sales() {
		if (!$this->skip_update_sales) {
			$this->update_invoice_category_sales('Total');
			$this->update_invoice_category_sales('3 Year');
			$this->update_invoice_category_sales('1 Year');
			$this->update_invoice_category_sales('6 Month');
			$this->update_invoice_category_sales('1 Quarter');
			$this->update_invoice_category_sales('1 Month');
			$this->update_invoice_category_sales('10 Day');
			$this->update_invoice_category_sales('1 Week');
		}
	}


	function update_invoice_category_sales($interval) {

		$to_date='';

		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)=calculate_interval_dates($this->db,$interval);


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

		$sql=sprintf("select sum(if(`Invoice Paid`='Yes',1,0)) as paid  ,sum(if(`Invoice Paid`='No',1,0)) as to_pay  , sum(if(`Invoice Type`='Invoice',1,0)) as invoices  ,sum(if(`Invoice Type`!='Invoice'  ,1,0)) as refunds  ,IFNULL(sum(`Invoice Items Discount Amount`),0) as discounts,IFNULL(sum(`Invoice Total Net Amount`),0) net  ,IFNULL(sum(`Invoice Total Profit`),0) as profit ,IFNULL(sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`),0) as dc_discounts,IFNULL(sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`),0) dc_net  ,IFNULL(sum(`Invoice Total Profit`*`Invoice Currency Exchange`),0) as dc_profit from `Category Bridge` B left join  `Invoice Dimension` I  on ( `Subject Key`=`Invoice Key`)  where `Subject`='Invoice' and `Category Key`=%d and  `Invoice Store Key`=%d %s %s" ,
			$this->id,
			$this->data['Category Store Key'],

			($from_date?sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)):'')

		);



		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
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
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
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
			, $invoice_category_data["Invoice Category $db_interval Acc Discount Amount"]
			, $invoice_category_data["Invoice Category $db_interval Acc Invoiced Amount"]
			, $invoice_category_data["Invoice Category $db_interval Acc Invoices"]
			, $invoice_category_data["Invoice Category $db_interval Acc Refunds"]
			, $invoice_category_data["Invoice Category $db_interval Acc Paid"]
			, $invoice_category_data["Invoice Category $db_interval Acc To Pay"]

			, $invoice_category_data["Invoice Category $db_interval Acc Profit"]
			, $this->id
		);

		$this->db->exec($sql);
		//print "$sql\n\n";
		$sql=sprintf("update `Invoice Category Dimension` set
                     `Invoice Category DC $db_interval Acc Discount Amount`=%.2f,
                     `Invoice Category DC $db_interval Acc Invoiced Amount`=%.2f,
                     `Invoice Category DC $db_interval Acc Profit`=%.2f
                     where `Invoice Category Key`=%d "
			, $invoice_category_data["Invoice Category DC $db_interval Acc Discount Amount"]
			, $invoice_category_data["Invoice Category DC $db_interval Acc Invoiced Amount"]
			, $invoice_category_data["Invoice Category DC $db_interval Acc Profit"]
			, $this->id
		);

		$this->db->exec($sql);



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


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$invoice_category_data["Invoice Category $db_interval Acc 1YB Discount Amount"]=$row["discounts"];
					$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
					$invoice_category_data["Invoice Category $db_interval Acc 1YB Invoices"]=$row["invoices"];
					$invoice_category_data["Invoice Category $db_interval Acc 1YB Profit"]=$row["profit"];
					$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Invoiced Amount"]=$row["dc_net"];
					$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Discount Amount"]=$row["dc_discounts"];
					$invoice_category_data["Invoice Category DC $db_interval Acc 1YB Profit"]=$row["dc_profit"];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



			$sql=sprintf("update `Invoice Category Dimension` set
                         `Invoice Category $db_interval Acc 1YB Discount Amount`=%.2f,
                         `Invoice Category $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Invoice Category $db_interval Acc 1YB Invoices`=%.2f,
                         `Invoice Category $db_interval Acc 1YB Profit`=%.2f
                         where `Invoice Category Key`=%d "
				, $invoice_category_data["Invoice Category $db_interval Acc 1YB Discount Amount"]
				, $invoice_category_data["Invoice Category $db_interval Acc 1YB Invoiced Amount"]
				, $invoice_category_data["Invoice Category $db_interval Acc 1YB Invoices"]
				, $invoice_category_data["Invoice Category $db_interval Acc 1YB Profit"]
				, $this->id
			);

			$this->db->exec($sql);
			// print "$sql\n";
			$sql=sprintf("update `Invoice Category Dimension` set
                         `Invoice Category DC $db_interval Acc 1YB Discount Amount`=%.2f,
                         `Invoice Category DC $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Invoice Category DC $db_interval Acc 1YB Profit`=%.2f
                         where `Invoice Category Key`=%d "
				, $invoice_category_data["Invoice Category DC $db_interval Acc 1YB Discount Amount"]
				, $invoice_category_data["Invoice Category DC $db_interval Acc 1YB Invoiced Amount"]
				, $invoice_category_data["Invoice Category DC $db_interval Acc 1YB Profit"]
				, $this->id
			);
			// print "$sql\n";
			$this->db->exec($sql);
		}


	}


	function get_number_invoices($from, $to) {
		$number_invoices=0;
		if ($this->data['Category Subject']=='Invoice') {

			$where=sprintf(" where `Subject`='Invoice' and  `Category Key`=%d", $this->id);
			$table=' `Category Bridge` left join  `Invoice Dimension` I on (`Subject Key`=`Invoice Key`) ';


			if ($from)$from=$from.' 00:00:00';
			if ($to)$to=$to.' 23:59:59';
			$where_interval=prepare_mysql_dates($from, $to, '`Invoice Date`');
			$where.=$where_interval['mysql'];


			$sql="select count(Distinct I.`Invoice Key`) as total from $table   $where  ";


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$number_invoices=$row['total'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}




		}
		return $number_invoices;

	}


}

?>
