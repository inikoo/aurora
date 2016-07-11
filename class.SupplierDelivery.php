<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2016 at 19:05:57 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';


class SupplierDelivery extends DB_Table {

	function SupplierDelivery($arg1=false, $arg2=false, $arg3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Supplier Delivery';
		$this->ignore_fields=array('Supplier Delivery Key');


		if (is_string($arg1)) {
			if (preg_match('/new|create/i', $arg1)) {
				$this->find($arg2, 'create');
				return;
			}
			if (preg_match('/find/i', $arg1)) {
				$this->find($arg2, $arg3);
				return;
			}
		}

		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return;
		}
		$this->get_data($arg1, $arg2);

	}


	function find($raw_data, $options) {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found=false;
		$this->found_key=false;
		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}


		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data))
				$data[$key]=_trim($value);
		}


		$sql=sprintf("select `Supplier Delivery Key` from `Supplier Delivery Dimension` where `Supplier Delivery Public ID`=%s  and `Supplier Delivery Parent`=%s and `Supplier Delivery Parent Key`=%d ",
			prepare_mysql($data['Supplier Delivery Public ID']),
			prepare_mysql($data['Supplier Delivery Parent']),
			$data['Supplier Delivery Parent Key']
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Supplier Delivery Key'];

			$this->found=true;
			$this->found_key=$row['Supplier Delivery Key'];
			$this->get_data('id', $this->found_key);
			$this->duplicated_field='Supplier Delivery Public ID';


		}


		if ($this->found_key) {
			$this->get_data('id', $this->found_key);
		}

		if ($create and !$this->found_key) {

			$this->create($data);

		}


	}


	function create($data) {



		$parent=get_object($data['Supplier Delivery Parent'], $data['Supplier Delivery Parent Key']);


		if (!$parent->id) {
			$this->error=true;
			$this->msg='wrong parent';
			return;
		}



		//print_r($data);
		$data['Supplier Delivery Creation Date']=gmdate('Y-m-d H:i:s');
		$data['Supplier Delivery Last Updated Date']=gmdate('Y-m-d H:i:s');


		$data['Supplier Delivery File As']=$this->get_file_as($data['Supplier Delivery Public ID']);
		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data))
				$base_data[$key]=_trim($value);
		}
		//  print_r($base_data);


		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";

			if (preg_match('/XHTML|Supplier Delivery POs/', $key))
				$values.="'".addslashes($value)."',";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);

		$sql=sprintf("insert into `Supplier Delivery Dimension` %s %s", $keys, $values);




		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();


			$this->get_data('id', $this->id);



			$history_data=array(
				'History Abstract'=>_('Supplier delivery created'),
				'History Details'=>'',
				'Action'=>'created'
			);
			$this->add_history($history_data);
			$this->new=true;

			$parent->update_orders();

		} else {
			print "Error can not create supplier delivery\n $sql\n";
		}





	}


	function get_data($key, $id) {

		if ($key=='id') {
			$sql=sprintf("select * from `Supplier Delivery Dimension` where `Supplier Delivery Key`=%d", $id);
		}
		elseif ($key=='public id' or $key=='public_id') {
			$sql=sprintf("select * from `Supplier Delivery Dimension` where `Supplier Delivery Public ID`=%s", prepare_mysql($id));
		}else{
		    exit('unknown key:'.$key);
		}

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Supplier Delivery Key'];
		}
	}


	function get_order_data() {



		$sql=sprintf('select * from `Purchase Order Dimension` where `Purchase Order Key`=%d ',
			$this->get('Supplier Delivery Purchase Order Key'));


		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data[$key]=$value;
			}
		}



	}



	function get($key='') {

		global $account;

		if (array_key_exists( $key, $this->data ))
			return $this->data [$key];



		switch ($key) {
		case 'Weight':
			include_once 'utils/natural_language.php';
			return weight($this->get('Supplier Delivery Weight'));
			break;
		case 'CBM':
			if ($this->data['Supplier Delivery CBM']=='')return '';
			return number($this->data['Supplier Delivery CBM']).' m³';
			break;
		case 'Estimated Receiving Date':
		case 'Creation Date':
		case 'Checked Date':
		case 'Dispatched Date':
		case 'Placed Date':
			if ($this->data['Supplier Delivery '.$key]=='')return '';
			return strftime("%e %b %Y", strtotime($this->data['Supplier Delivery '.$key].' +0:00'));

			break;
		case 'Received Date':
			if ($this->get('State Index')<0) {
				return '';

			}elseif ($this->get('State Index')>=40) {

				if ($this->get('Supplier Delivery Received Date')=='')return 'Error';
				return strftime("%e %b %Y", strtotime($this->get('Supplier Delivery Received Date')));
			}else {

				if ($this->data['Supplier Delivery Estimated Receiving Date']) {
					return '<span class="discreet"><i class="fa fa-thumb-tack" aria-hidden="true"></i> '.strftime("%e %b %Y", strtotime($this->get('Estimated Receiving Date'))).'</span>';
				}else {

					if ($this->data['Supplier Delivery State']=='InProcess') {
						$parent=get_object($this->data['Supplier Delivery Parent'], $this->data['Supplier Delivery Parent Key']);
						if ($parent->get($parent->table_name.' Delivery Days') and is_numeric($parent->get($parent->table_name.' Delivery Days'))) {
							return '<span class="discreet italic">'.strftime("%d-%m-%Y", strtotime('now +'.$parent->get($parent->table_name.' Delivery Days').' days')).'</span>';

						}else {
							return '&nbsp;';
						}
					}else {

						$parent=get_object($this->data['Supplier Delivery Parent'], $this->data['Supplier Delivery Parent Key']);
						if ($parent->get($parent->table_name.' Delivery Days') and is_numeric($parent->get($parent->table_name.' Delivery Days'))) {
							return '<span class="discreet italic">'.strftime("%d-%m-%Y", strtotime($this->get('Supplier Delivery Submitted Date').' +'.$parent->get($parent->table_name.' Delivery Days').' days')).'</span>';

						}else {
							return '<span class="super_discreet">'._('Unknown').'</class>';
						}
					}

				}
			}

			break;

		case 'PO Creation Date':
		case 'PO Submitted Date':

			$key=preg_replace('/^PO /', '', $key);

			if ($this->data['Purchase Order '.$key]=='')return '';
			return strftime("%e %b %Y", strtotime($this->data['Purchase Order '.$key].' +0:00'));

			break;
		case 'Total Amount':
			return money($this->data['Supplier Delivery Total Amount'], $this->data['Supplier Delivery Currency Code']);
			break;

		case 'Number Items':
		case 'Number Ordered Items':
		case 'Number Items Without PO':
			return number($this->data ['Supplier Delivery '.$key]);
		case 'State Index':
			switch ($this->data['Supplier Delivery State']) {
			case 'InProcess':
				return 10;
				break;
			case 'Dispatched':
				return 30;
				break;
			case 'Received':
				return 40;
				break;
			case 'Checked':
				return 50;
				break;
			case 'Placing':
				return 80;
				break;
			case 'Done':
				return 100;
				break;

			case 'Cancelled':
				return -10;
				break;


			default:
				return 0;
				break;
			}
			break;
		default:

			break;
		}







		if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/', $key)) {
			$amount='Supplier Delivery '.$key;
			return money($this->data[$amount]);
		}

		if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount Account Currency)$/', $key)) {
			$key=preg_replace('/ Account Currency/', '', $key);
			$amount='Supplier Delivery '.$key;
			return money($this->data['Supplier Delivery Currency Exchange']*$this->data[$amount], $account->get('Account Currency'));


		}


		if (array_key_exists($key, $this->data))
			return $this->data[$key];

		if (array_key_exists('Supplier Delivery '.$key, $this->data))
			return $this->data[$this->table_name.' '.$key];


	}






	function update_delivered_transaction($data) {


		if ($data ['Supplier Delivery Received Quantity']<0)
			$data ['Supplier Delivery Received Quantity']=0;

		$sql=sprintf("select `Supplier Delivery Damaged Quantity`,`Supplier Delivery Quantity`,`Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Purchase Order Transaction Fact Key`=%d order by `Purchase Order Last Updated Date` desc "
			, $this->id
			, $data['Purchase Order Transaction Fact Key']
		);

		//    print $sql;

		$res=mysql_query($sql);
		$sum_quantity=0;
		$sum_damaged_quantity=0;

		$quantity_data=array();

		while ($row=mysql_fetch_array($res)) {
			$quantity_data[$row['Purchase Order Transaction Fact Key']]=$row['Supplier Delivery Quantity'];
			$sum_quantity+=$row['Supplier Delivery Quantity'];
			$sum_damaged_quantity+=$row['Supplier Delivery Damaged Quantity'];

		}


		if (count($quantity_data)==0) {
			$this->error=true;
			$this->msg="Item do not found $sql";

			return;
		} else if (count($quantity_data)==1) {
			foreach ($quantity_data as $key=>$value) {
				$quantity_data[$key]=$data ['Supplier Delivery Received Quantity'];
			}
		} else {
			$resolved=false;
			$difference=$data ['Supplier Delivery Received Quantity']-$sum_quantity;

			if ($data ['Supplier Delivery Received Quantity']==0) {
				foreach ($quantity_data as $key=>$value) {
					$quantity_data[$key]=0;
				}


			} else if ($difference==0) {
				$resolved=true;

			} else if ($difference<0) {

				foreach ($quantity_data as $key=>$value) {
					if ($resolved)
						break;
					if ($value==$difference) {
						$quantity_data[$key]=0;
						$resolved=true;
					}
				}

				foreach ($quantity_data as $key=>$value) {
					if ($resolved)
						break;

					if ($value<$difference) {
						$quantity_data[$key]=0;
						$difference=$value-$difference;
					} else {
						$quantity_data[$key]=$value-$difference;
						$difference=0;
					}
					if ($difference==0)
						$resolved=true;

				}




			}



		}

		foreach ($quantity_data as $pofk=>$received_quantity) {
			$sql = sprintf("update`Purchase Order Transaction Fact` set  `Supplier Delivery Received Quantity`=%f,`Supplier Delivery Last Updated Date`=%s  where `Supplier Delivery Key`=%d and `Purchase Order Transaction Fact Key`=%d"
				, $received_quantity
				, prepare_mysql ( $data ['Supplier Delivery Last Updated Date'] )
				, $this->id
				, $pofk
			);
			//print "$sql";
			mysql_query($sql);
		}

		$data=array(
			'Supplier Delivery Last Updated Date'=>$data ['Supplier Delivery Last Updated Date'],
			'Purchase Order Transaction Fact Key'=>$data['Purchase Order Transaction Fact Key'],
			'Supplier Delivery Damaged Quantity'=>$sum_damaged_quantity,
			'Supplier Delivery Received Quantity'=>$received_quantity


		);
		$damaged_data=$this->update_damaged_transaction($data);
		$this->update_item_totals_from_order_transactions();
		$this->update_affected_products();

		return array('qty'=>$data ['Supplier Delivery Received Quantity'], 'damaged_qty'=>$damaged_data['damaged_qty']);

	}


	function update_damaged_transaction($data) {


		if ($data ['Supplier Delivery Damaged Quantity']<0)
			$data ['Supplier Delivery Damaged Quantity']=0;

		$sql=sprintf("select `Supplier Delivery Received Quantity`,`Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Purchase Order Transaction Fact Key`=%d order by `Purchase Order Last Updated Date` desc "
			, $this->id
			, $data['Purchase Order Transaction Fact Key']
		);
		$res=mysql_query($sql);
		$sum_quantity=0;
		$sum_damaged_quantity=0;

		$quantity_data=array();
		$damaged_quantity_data=array();

		while ($row=mysql_fetch_array($res)) {
			$quantity_data[$row['Purchase Order Transaction Fact Key']]=$row['Supplier Delivery Received Quantity'];
			$damaged_quantity_data[$row['Purchase Order Transaction Fact Key']]=0;

			$sum_quantity+=$row['Supplier Delivery Received Quantity'];
		}


		if ($sum_quantity<$data ['Supplier Delivery Damaged Quantity'])
			$data ['Supplier Delivery Damaged Quantity']=$sum_quantity;




		if (count($quantity_data)==0) {
			$this->error=true;
			$this->msg="Item do not found $sql";

			return;
		} else if (count($quantity_data)==1) {
			foreach ($quantity_data as $key=>$value) {
				$damaged_quantity_data[$key]=$data ['Supplier Delivery Damaged Quantity'];
			}
		} else {
			$damaged=$data ['Supplier Delivery Damaged Quantity'];

			foreach ($quantity_data as $key=>$value) {


				if ($value>=$damaged) {
					$damaged_quantity_data[$key]=$damaged;
					$damaged=0;
				} else {
					$damaged_quantity_data[$key]=$value;
					$damaged=$damaged-$value;
				}

			}




		}





		foreach ($damaged_quantity_data as $potfk=>$damaged_quantity) {
			$sql = sprintf("update`Purchase Order Transaction Fact` set  `Supplier Delivery Damaged Quantity`=%f,`Supplier Delivery Last Updated Date`=%s  where `Supplier Delivery Key`=%d and `Purchase Order Transaction Fact Key`=%d"
				, $damaged_quantity
				, prepare_mysql ( $data ['Supplier Delivery Last Updated Date'] )
				, $this->id
				, $potfk
			);
			//print "$sql";
			mysql_query($sql);
		}


		return array('qty'=>$sum_quantity, 'damaged_qty'=>$data ['Supplier Delivery Damaged Quantity']);

	}



	function get_next_public_id($supplier_key) {
		$supplier=new Supplier($supplier_key);
		$code=$supplier->data['Supplier Code'];

		$sql=sprintf("select `Supplier Delivery Public ID` from `Supplier Delivery Dimension` where `Supplier Delivery Supplier Key`=%d order by REPLACE(`Supplier Delivery Public ID`,%s,'') desc limit 1", $supplier_key, prepare_mysql($code));
		$res=mysql_query($sql);

		$line_number=1;
		if ($row=mysql_fetch_array($res))
			$line_number= (int) preg_replace('/[^\d]/', '', $row['Supplier Delivery Public ID'])+1;

		return sprintf('%s%04d', $code, $line_number);

	}


	function get_file_as($name) {

		return $name;
	}



	function update_field_switcher($field, $value, $options='', $metadata='') {
		switch ($field) {
		case 'Supplier Delivery State':
			$this->update_state($value, $options, $metadata);
			break;
		case 'Supplier Delivery Estimated Receiving Date':
			$this->update_field($field, $value, $options);
			$this->update_affected_products();

			break;
		default:



			$base_data=$this->base_data();



			if (array_key_exists($field, $base_data)) {
				if ($value!=$this->data[$field]) {

					$this->update_field($field, $value, $options);
				}
			}

			break;
		}



	}



	function update_item($item_key, $item_historic_key, $qty) {


		// Todo calculate taxed, 0 tax for now
		//include_once 'class.TaxCategory.php';
		//$tax_category=new TaxCategory($data['tax_code']);
		//$tax_amount=$tax_category->calculate_tax($data ['amount']);



		include_once 'class.SupplierPart.php';
		$supplier_part=new SupplierPart($item_key);


		$date=gmdate('Y-m-d H:i:s');
		$transaction_key='';

		if ($qty==0) {

			$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Note to Supplier Locked` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Supplier Part Key`=%d ",
				$this->id,
				$item_key
			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					if ($row['Purchase Order Key']=='') {

						$sql=sprintf("delete from `Purchase Order Transaction Fact` where `Purchase Order Transaction Fact Key`=%d ",
							$row['Purchase Order Transaction Fact Key']
						);
						$this->db->exec($sql);
					}else {

						$sql=sprintf("update  `Purchase Order Transaction Fact` set  `Supplier Delivery Key`=NULL,`Supplier Delivery Received Location Key`=1, `Supplier Delivery Quantity`=0,`Supplier Delivery Received Quantity`=0,`Supplier Delivery Damaged Quantity`=0,`Supplier Delivery Placed Quantity`=0,`Supplier Delivery Net Amount`=0,`Supplier Delivery Tax Amount`=0,`Supplier Delivery Transaction State`=NULL,`Supplier Delivery Counted`='No' where `Purchase Order Transaction Fact Key`=%d ",
							$row['Purchase Order Transaction Fact Key']
						);

						print $sql;
						$this->db->exec($sql);

					}
					$transaction_key=$row['Purchase Order Transaction Fact Key'];

				}
			}



			$amount=0;
			$subtotals='';


		}else {


			$amount=$qty*$supplier_part->get('Supplier Part Unit Cost')*$supplier_part->get('Supplier Part Units Per Package')*$supplier_part->get('Supplier Part Packages Per Carton');
			if (is_numeric($supplier_part->get('Supplier Part Carton CBM'))) {
				$cbm=$qty*$supplier_part->get('Supplier Part Carton CBM');
			}else {
				$cbm='NULL';
			}


			if (is_numeric($supplier_part->part->get('Part Package Weight'))) {
				$weight=$qty*$supplier_part->part->get('Part Package Weight')*$supplier_part->get('Supplier Part Packages Per Carton');
			}else {
				$weight='NULL';
			}


			// Todo calculate taxed, 0 tax for now
			$tax_amount=0;
			$tax_code ='';


			$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Supplier Part Key`=%d ",
				$this->id,
				$item_key
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$sql = sprintf( "update`Purchase Order Transaction Fact` set  `Supplier Delivery Quantity`=%f,`Supplier Delivery Last Updated Date`=%s,`Supplier Delivery Net Amount`=%f ,`Supplier Delivery Tax Amount`=%f ,`Supplier Delivery CBM`=%s,`Supplier Delivery Weight`=%s  where  `Purchase Order Transaction Fact Key`=%d ",
						$qty,
						prepare_mysql ($date),
						$amount,
						$tax_amount,
						$cbm,
						$weight,
						$row['Purchase Order Transaction Fact Key']
					);

					$this->db->exec($sql);

					$transaction_key=$row['Purchase Order Transaction Fact Key'];
				}else {

					$sql = sprintf( "insert into `Purchase Order Transaction Fact` (`Supplier Part Key`,`Supplier Part Historic Key`,`Currency Code`,`Supplier Delivery Last Updated Date`,`Supplier Delivery Transaction State`,
					`Supplier Key`,`Agent Key`,`Supplier Delivery Key`,`Supplier Delivery Quantity`,`Supplier Delivery Net Amount`,`Supplier Delivery Tax Amount`,`Note to Supplier`,`Supplier Delivery CBM`,`Supplier Delivery Weight`,
					`User Key`,`Creation Date`
					)
					values (%d,%d,%s,%s,%s,
					 %d,%s,%d,%.6f,%.2f,%.2f,%s,%s,%s,
					 %d,%s
					 )",
						$item_key,
						$item_historic_key,
						prepare_mysql ( $this->get('Supplier Delivery Currency Code') ),
						prepare_mysql ( $date),
						prepare_mysql ($this->get('Supplier Delivery State') ),

						$supplier_part->get('Supplier Part Supplier Key'),
						($supplier_part->get('Supplier Part Agent Key')==''?'Null':sprintf("%d", $supplier_part->get('Supplier Part Agent Key'))),
						$this->id,
						$qty,
						$amount,
						$tax_amount,
						prepare_mysql ($supplier_part->get('Supplier Part Note to Supplier'), false),
						$cbm,
						$weight,
						$this->editor['User Key'],
						prepare_mysql ( $date)


					);

					$this->db->exec($sql);
					$transaction_key=$this->db->lastInsertId();


				}



				$subtotals=money($amount, $this->get('Supplier Delivery Currency Code'));

				if ($weight>0) {
					$subtotals.=' '.weight($weight);
				}
				if ($cbm>0) {
					$subtotals.=' '.number($cbm).' m³';
				}



			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



		}

		$this->update_totals();

		$this->update_metadata=array(
			'class_html'=>array(
				'Supplier_Delivery_Total_Amount'=>$this->get('Total Amount'),
				'Supplier_Delivery_Total_Amount_Account_Currency'=>$this->get('Total Amount Account Currency'),
				'Supplier_Delivery_Weight'=>$this->get('Weight'),
				'Supplier_Delivery_CBM'=>$this->get('CBM'),

			)
		);


		return array('transaction_key'=>$transaction_key, 'subtotals'=>$subtotals, 'to_charge'=>money($amount, $this->data['Purchase Order Currency Code']), 'qty'=>$qty+0);




	}






	function delete() {

		if ($this->data['Supplier Delivery Current State']=='InProcess') {



			$sql=sprintf("delete from `Supplier Delivery Dimension` where `Supplier Delivery Key`=%d", $this->id);
			mysql_query($sql);
			$sql=sprintf("delete from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Purchase Order Key` is NULL and `Supplier Invoice Key` IS NULL ", $this->id);
			mysql_query($sql);
			$sql=sprintf("update `Purchase Order Transaction Fact` set `Supplier Delivery Key`=NULL ,`Supplier Delivery Quantity`=0 , `Supplier Delivery Quantity Type`=NULL,`Supplier Delivery Last Updated Date`=NULL  where `Supplier Delivery Key`=%d  ", $this->id);
			mysql_query($sql);



			$sql=sprintf("select `History Key`,`Type` from `Supplier Delivery History Bridge` where `Supplier Delivery Key`=%d", $this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {

				if ($row['Type']=='Attachments') {
					$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='Supplier Delivery History Attachment' and `Subject Key`=%d",
						$row['History Key']
					);
					$res2=mysql_query($sql);
					while ($row2=mysql_fetch_assoc($res2)) {
						$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d", $row2['Attachment Bridge Key']);
						mysql_query($sql);
						$attachment=new Attachment($row2['Attachment Key']);
						$attachment->delete();
					}
				}

				$sql=sprintf("delete from `Supplier Delivery History Bridge` where `History Key`=%d ", $row['History Key']);
				mysql_query($sql);

				$sql=sprintf("delete from `History Dimension` where `History Key`=%d", $row['History Key']);
				mysql_query($sql);

			}

			$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='Supplier Delivery' and `Subject Key`=%d",
				$this->id
			);
			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_assoc($res2)) {
				$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d", $row2['Attachment Bridge Key']);
				mysql_query($sql);
				$attachment=new Attachment($row2['Attachment Key']);
				$attachment->delete();
			}


			foreach ($this->get_purchase_orders_objects() as $po) {
				$po->editor=$this->editor;
				$history_data=array(
					'History Abstract'=>_('Supplier Delivery in process deleted'),
					'History Details'=>''
				);
				$po->add_subject_history($history_data);
			}

			$sql=sprintf("delete from `Purchase Order SDN Bridge` where `Supplier Delivery Key`=%d", $this->id);
			mysql_query($sql);


			$supplier=new Supplier($this->data['Supplier Delivery Supplier Key']);
			$supplier->editor=$this->editor;
			$history_data=array(
				'History Abstract'=>_('Supplier Delivery in process deleted'),
				'History Details'=>''
			);
			$supplier->add_subject_history($history_data);







		} else {
			$this->error=true;
			$this->msg='Can not deleted submitted Supplier Delivery';
		}
	}


	function input($data) {


		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Supplier Delivery Dimension` set `Supplier Delivery Input Date`=%s,`Supplier Delivery Main Inputter Key`=%s,`Supplier Delivery Current State`='Inputted'   where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Input Date'])
			, prepare_mysql($data['Supplier Delivery Main Inputter Key'])
			, $this->id);

		//print $sql;
		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Last Updated Date`=%s, `Supplier Delivery State`='Inputted',`Purchase Order Transaction State`='In Warehouse'  where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Input Date'])
			, $this->id
		);
		mysql_query($sql);
		//print $sql;

		$history_data=array(
			'History Abstract'=>_('Delivery data inputted'),
			'History Details'=>''
		);
		$this->add_subject_history($history_data);

		$this->update_affected_products();
	}







	function update_affected_products() {
		$sql=sprintf("select `Supplier Product ID`,`Supplier Delivery Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$supplier_product=new SupplierProduct('key', $row['Supplier Product ID']);
			$products=$supplier_product->get_products();
			foreach ($products as $product) {
				$product=new Product('pid', $product['Product ID']);
				$product->update_next_supplier_shippment();

			}

		}


	}


	function mark_as_received($data) {




		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Supplier Delivery Dimension` set `Supplier Delivery Received Date`=%s,`Supplier Delivery Main Receiver Key`=%s,`Supplier Delivery Current State`='Received'   where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Received Date'])
			, prepare_mysql($data['Supplier Delivery Main Receiver Key'])
			, $this->id);

		//print $sql;
		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set `Supplier Delivery Received Location Key`=%d , `Supplier Delivery Last Updated Date`=%s, `Supplier Delivery State`='Received'  where `Supplier Delivery Key`=%d"
			, $data['Supplier Delivery Received Location Key']
			, prepare_mysql($data['Supplier Delivery Received Date'])
			, $this->id
		);
		mysql_query($sql);
		// print $sql;

		$history_data=array(
			'History Abstract'=>_('Delivery received'),
			'History Details'=>''
		);
		$this->add_subject_history($history_data);

		$this->update_store_products();


	}



	function mark_as_checked($data) {


		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Supplier Delivery Dimension` set `Supplier Delivery Input Date`=%s,`Supplier Delivery Main Checker Key`=%s,`Supplier Delivery Current State`='Checked'   where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Checked Date'])
			, prepare_mysql($data['Supplier Delivery Main Checker Key'])
			, $this->id);

		//print $sql;
		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Last Updated Date`=%s, `Supplier Delivery State`='Checked'  where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Checked Date'])
			, $this->id
		);
		mysql_query($sql);
		//print $sql;


		//$unknown_convertions=$this->check_for_unknown_sku_conversions();
		//if(count($unknown_convertions))

		$this->convert_to_parts();



		$this->update_store_products();






	}


	function mark_as_damages_checked($data) {


		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Supplier Delivery Dimension` set `Supplier Delivery Input Date`=%s,`Supplier Delivery Main Inputter Key`=%s,`Supplier Delivery Current State`='Damages Checked'   where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Damages Checked Date'])
			, prepare_mysql($data['Supplier Delivery Main Damages Checker Key'])
			, $this->id);

		//print $sql;
		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Last Updated Date`=%s, `Supplier Delivery State`='Damages Checked'  where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Received Date'])
			, $this->id
		);
		mysql_query($sql);
		//print $sql;

		$sql=sprintf("select `Supplier Product ID`,`Supplier Delivery Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$supplier_product=new SupplierProduct('key', $row['Supplier Product ID']);
			$products=$supplier_product->get_products();
			foreach ($products as $product) {
				$product=new Product('pid', $product['Product ID']);
				$product->update_next_supplier_shippment();

			}

		}

	}


	function receive($data) {


		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Supplier Delivery Dimension` set `Supplier Delivery Submitted Date`=%s,`Supplier Delivery Estimated Receiving Date`=%s,`Supplier Delivery Main Source Type`=%s,`Supplier Delivery Main Buyer Key`=%s,`Supplier Delivery Main Buyer Name`=%s,`Supplier Delivery Current Dispatch State`='Submitted'   where `Supplier Delivery Key`=%d"
			, prepare_mysql($data['Supplier Delivery Submitted Date'])
			, prepare_mysql($data['Supplier Delivery Estimated Receiving Date'])
			, prepare_mysql($data['Supplier Delivery Main Source Type'])
			, prepare_mysql($data['Supplier Delivery Main Buyer Key'])
			, prepare_mysql($data['Supplier Delivery Main Buyer Name'])
			, $this->id);


		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Last Updated Date`=%s `Supplier Delivery Current Dispatching State`='Submitted'  where `Supplier Delivery Key`=%d", prepare_mysql($data['Supplier Delivery Submitted Date']), $this->id);
		mysql_query($sql);

		$this->update_affected_products();


	}


	function get_pos_keys() {


	}


	function update_pos($raw_po_keys) {
		$po_keys=array();
		foreach ($raw_po_keys as $po_key) {
			if (!is_numeric($po_key))
				continue;
			$po=new PurchaseOrder($po_key);
			$po->editor=$this->editor;
			if (!$po->id)
				continue;
			if ($this->data['Supplier Delivery Supplier Key']!=$po->data['Purchase Order Supplier Key'])
				continue;
			$po_keys[$po->id]=$po->id;

			$sql=sprintf('insert into `Purchase Order SDN Bridge` (`Purchase Order Key`,`Supplier Delivery Key`) values (%d,%d)',
				$po->id,
				$this->id
			);
			mysql_query($sql);
			$po->mark_as_associated_with_sdn($this->id, $this->data['Supplier Delivery Public ID']);
		}



		$pos=join(',', $po_keys);
		$sql=sprintf("update `Supplier Delivery Dimension` set `Supplier Delivery POs`=%s where `Supplier Delivery Key`=%d "
			, prepare_mysql($pos)
			, $this->id
		);
		mysql_query($sql);
		$this->data['Supplier Delivery POs']=$pos;
	}



	function get_purchase_orders_keys() {

		$pos_keys=array();
		$sql=sprintf("select `Purchase Order Key` from `Purchase Order SDN Bridge` where `Supplier Delivery Key`=%d ", $this->id);
		$res = mysql_query( $sql );
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Purchase Order Key']) {
				$pos_keys[$row['Purchase Order Key']]=$row['Purchase Order Key'];
			}
		}
		return $pos_keys;

	}


	function get_number_purchase_orders() {

		return count($this->get_purchase_orders_keys());
	}


	function get_purchase_orders_objects() {
		$pos=array();
		$pos_keys=$this->get_purchase_orders_keys();
		foreach ($pos_keys as $pos_key) {
			$pos[$pos_key]=new PurchaseOrder($pos_key);
		}
		return $pos;
	}




	function creating_take_values_from_pos() {
		$items=array();
		$supplier_product_keys=array();
		//print_r(preg_split('/\,/',$this->data['Supplier Delivery POs'] )) ;
		foreach (preg_split('/\,/', $this->data['Supplier Delivery POs']) as $po_key) {
			$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Supplier Product ID`,`Purchase Order Quantity`,`Purchase Order Quantity Type` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d  ", $po_key);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

				if (array_key_exists($row['Supplier Product ID'], $supplier_product_keys)) {
					$line= $supplier_product_keys[$row['Supplier Product ID']];

					if ($items[$line]['Purchase Order Quantity Type']!=$row['Purchase Order Quantity Type']) {
						$supplier_product=new SupplierProduct($row['Supplier Product ID']);
						$row['Purchase Order Quantity']=$row['Purchase Order Quantity'] *$supplier_product->units_convertion_factor($row['Purchase Order Quantity Type'], $items[$line]['Purchase Order Quantity Type']);
						$row['Purchase Order Quantity Type']=$items[$line]['Purchase Order Quantity Type'];
					}


				}


				$supplier_product_keys[$row['Supplier Product ID']]=$row['Purchase Order Transaction Fact Key'];
				$items[$row['Purchase Order Transaction Fact Key']]=array(
					'Supplier Product ID'=>$row['Supplier Product ID'],
					'Purchase Order Quantity'=>$row['Purchase Order Quantity'],
					'Purchase Order Quantity Type'=>$row['Purchase Order Quantity Type'],
					'Purchase Order Transaction Fact Key'=>$row['Purchase Order Transaction Fact Key'],
					'Purchase Order Key'=>$po_key
				);

			}

		}

		foreach ($items as $item) {

			$sql=sprintf("select `Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Supplier Product ID`=%d ", $this->id, $item['Supplier Product ID']);
			$res=mysql_query($sql);
			// print $sql;
			if ($row=mysql_fetch_array($res)) {
				if ($row['Purchase Order Transaction Fact Key'])
					$line=$row['Purchase Order Transaction Fact Key'];
			}

			$sql=sprintf("update  `Purchase Order Transaction Fact` set   `Supplier Delivery Last Updated Date`=%s, `Supplier Delivery Key`=%d,`Supplier Delivery Quantity`=%f ,`Supplier Delivery Quantity Type`=%s where  `Purchase Order Key`=%d and `Purchase Order Transaction Fact Key`=%d"
				, gmdate("Y-m-d H:i:s")
				, $this->id
				, $item['Purchase Order Quantity']
				, prepare_mysql($item['Purchase Order Quantity Type'])
				, $item['Purchase Order Key']
				, $item['Purchase Order Transaction Fact Key']
			);
			mysql_query($sql);
			//  print $sql;
		}

		$this->update_item_totals_from_order_transactions();

	}


	function counting_take_values_from_dn() {


		$sql=sprintf("update  `Purchase Order Transaction Fact` set `Supplier Delivery Counted`='No',`Supplier Delivery Received Quantity`=`Supplier Delivery Quantity` where  `Supplier Delivery Key`=%d and `Supplier Delivery Counted`!='Yes' "
			, $this->id
		);
		//PRINT $sql;
		mysql_query($sql);




	}


	function update_transaction_counted($data) {


		$value=$data['Supplier Delivery Counted'];
		$date=$data['Supplier Delivery Counted'];


		$sql=sprintf("select `Supplier Delivery Damaged Quantity`,`Supplier Delivery Received Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Purchase Order Transaction Fact Key`=%d  "
			, $this->id
			, $data['Purchase Order Transaction Fact Key']
		);
		$res=mysql_query($sql);
		$total_quantity=0;
		$damaged_total_quantity=0;
		//print $sql;
		while ($row=mysql_fetch_array($res)) {
			//  print $total_quantity.' '.$row['Supplier Delivery Received Quantity'];
			$total_quantity+=$row['Supplier Delivery Received Quantity'];
			$damaged_total_quantity+=$row['Supplier Delivery Damaged Quantity'];
		}

		if ($damaged_total_quantity>0)
			$value='Yes';

		$sql=sprintf("update  `Purchase Order Transaction Fact` set  `Supplier Delivery Counted`=%s ,`Supplier Delivery Last Updated Date`=%s where `Supplier Delivery Key`=%d and `Purchase Order Transaction Fact Key`=%d  "
			, prepare_mysql($value)
			, prepare_mysql($date)

			, $this->id
			, $data['Purchase Order Transaction Fact Key']
		);
		mysql_query($sql);
		// print $sql;

		return array('qty'=>$total_quantity, 'counted'=>$value, 'damaged_qty'=>$damaged_total_quantity);

	}





	function update_store_products() {

		$sql=sprintf("select `Supplier Product ID`,`Supplier Delivery Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$supplier_product=new SupplierProduct('key', $row['Supplier Product ID']);
			$products=$supplier_product->get_products();
			foreach ($products as $product) {
				$product=new Product('pid', $product['Product ID']);
				$product->update_next_supplier_shippment();

			}

		}
	}


	function convert_to_parts() {

		include_once 'class.PartLocation.php';

		$parts=array();

		$sql=sprintf("select `Supplier Delivery Received Location Key`,`Purchase Order Transaction Fact Key`, `Supplier Product ID`,`Supplier Delivery Received Quantity`-`Supplier Delivery Damaged Quantity` as quantity from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d ", $this->id);
		$res=mysql_query($sql);
		// print $sql;
		while ($row=mysql_fetch_array($res)) {
			$quantity=$row['quantity'];


			if ($quantity>0) {
				$supplier_product=new SupplierProduct($row['Supplier Product ID']);
				if ($supplier_product->data['Supplier Product Part Convertion']=='1:1') {
					$parts_data=$supplier_product->get_parts();
					$part_data=array_shift($parts_data);

					//  print_r($part_data);

					$supplier_part_units_convertion=$supplier_product->units_convertion_factor($part_data['Part_Unit']);
					if (!$supplier_part_units_convertion)
						continue;

					$quantity=$quantity*$supplier_part_units_convertion;
					$parts_quantity=$quantity/$part_data['Supplier_Product_Units_Per_Part'];

					if (array_key_exists($part_data['Part_SKU'].'_'.$row['Supplier Delivery Received Location Key'], $parts)) {


						$parts[$part_data['Part_SKU'].'_'.$row['Supplier Delivery Received Location Key']]['Quantity']+=$parts_quantity;


					} else {

						$parts[$part_data['Part_SKU'].'_'.$row['Supplier Delivery Received Location Key']]=array(
							'Part SKU'=>$part_data['Part_SKU'],
							'Quantity'=>$parts_quantity,
							'Location Key'=>$row['Supplier Delivery Received Location Key']
						);
					}


					$sql=sprintf("update `Purchase Order Transaction Fact` set `Supplier Deliver Note Part Assigned`='Yes' where `Purchase Order Transaction Fact Key`=%d  ", $row['Purchase Order Transaction Fact Key']);
					mysql_query($sql);

					//$notes=sprintf('SKUs to place: <button class="place_sku" onClick="place(this)" id="%d"  >%s</button>',$part_data['Part_SKU'],$parts_quantity);
					$notes='';
					$sql=sprintf('insert into `Supplier Delivery Item Part Bridge` values (%d,%d,%d,%f,"No",%s) '
						, $this->id
						, $row['Purchase Order Transaction Fact Key']
						, $part_data['Part_SKU']

						, $parts_quantity
						, prepare_mysql($notes, false)
					);
					mysql_query($sql);
					//print "$sql\n";;


				}



			}



		}

		foreach ($parts as $data) {
			//print_r($this->get_editor_data());
			$part_location_data=array('Part SKU'=>$data['Part SKU'], 'Location Key'=>$data['Location Key'], 'editor'=>$this->editor);
			// print_r($part_location_data);
			$part_location=new PartLocation('find', $part_location_data, 'create');
			$part_location->add_stock(
				array(
					'Quantity'=>$data['Quantity'],
					'Origin'=>_('Supplier Delivery').' <a href="supplier_dn.php?id='.$this->id.'">'.$this->data['Supplier Delivery Public ID'].'</a>'
				)
			);
		}





	}


	function get_field_label($field) {

		switch ($field) {

		case 'Supplier Delivery Public ID':
			$label=_('public Id');
			break;
		case 'Supplier Delivery Incoterm':
			$label=_('Incoterm');
			break;
		case 'Supplier Delivery Port of Export':
			$label=_('port of export');
			break;
		case 'Supplier Delivery Port of Import':
			$label=_('port of import');
			break;
		case 'Supplier Delivery Estimated Receiving Date':
			$label=_('estimated receiving date');
			break;

		default:
			$label=$field;

		}

		return $label;

	}


	function update_state($value, $options, $metadata) {
		$date=gmdate('Y-m-d H:i:s');



		if ($value=='InProcess or Dispatched') {
			if ($this->get('Supplier Delivery Dispatched Date')=='') {
				$value='InProcess';
			}else {
				$value='Dispatched';
				$metadata=array('Supplier Delivery Dispatched Date'=>$this->get('Supplier Delivery Dispatched Date'));
			}
		}



		switch ($value) {
		case 'InProcess':

			$this->update_field('Supplier Delivery Dispatched Date', '', 'no_history');
			//$this->update_field('Supplier Delivery Estimated Receiving Date', '', 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');
			$operations=array('delete_operations', 'submit_operations', 'all_available_items', 'new_item');

			break;



		case 'Dispatched':

			$this->update_field('Supplier Delivery Dispatched Date', $date, 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');
			foreach ($metadata as $key=>$_value) {

				$this->update_field($key, $_value, 'no_history');
			}

			$operations=array('cancel_operations', 'undo_send_operations', 'received_operations');


			break;
		case 'Received':

			$this->update_field('Supplier Delivery Received Date', $date, 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');
			foreach ($metadata as $key=>$_value) {
				$this->update_field($key, $_value, 'no_history');
			}

			$operations=array('cancel_operations', 'undo_send_operations', 'received_operations');


			break;
		default:
			exit('unknown state '.$value);
			break;
		}


		$this->update_metadata=array(
			'class_html'=>array(
				'Supplier_Delivery_State'=>$this->get('State'),
				'Supplier_Delivery_Dispatched_Date'=>'&nbsp;'.$this->get('Dispatched Date'),
				'Supplier_Delivery_Received_Date'=>'&nbsp;'.$this->get('Received Date'),
				'Supplier_Delivery_Checked_Date'=>'&nbsp;'.$this->get('Checked Date'),

			),
			'operations'=>$operations,
			'state_index'=>$this->get('State Index')
		);


		$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Last Updated Date`=%s where `Supplier Delivery Key`=%d ',
			prepare_mysql($date),
			$this->id
		);
		$this->db->exec($sql);

		$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Transaction State`=%s where `Supplier Delivery Key`=%d ',
			prepare_mysql($value),
			$this->id
		);
		$this->db->exec($sql);




	}


	function update_totals() {


		$this->data ['Supplier Delivery Number Items'] = $row['num_items'];
		$this->data ['Supplier Delivery Number Items Without PO'] =  $row['num_items']-$row['ordered_products'];
		$this->data ['Supplier Delivery Number Ordered Items'] = $row['ordered_products'];



		$sql = sprintf("select sum(`Supplier Delivery Weight`) as  weight,sum(`Supplier Delivery CBM` )as cbm , sum(if(`Purchase Order Key`>0,1,0)) as ordered_items, count(*) as num_items ,sum(`Supplier Delivery Net Amount`) as net,sum(`Supplier Delivery Tax Amount`) as tax from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d" ,
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {


				$total_net=$row['net']+$this->get('Supplier Delivery Shipping Net Amount')+$this->get('Supplier Delivery Charges Net Amount')+  $this->get('Supplier Delivery Other Net Amount');
				$total_tax=$row['tax']+$this->get('Supplier Delivery Shipping Tax Amount')+$this->get('Supplier Delivery Charges Tax Amount')+  $this->get('Supplier Delivery Other Tax Amount');
				$total=$total_net+$total_tax;

				$this->update(array(
						'Supplier Delivery Items Net Amount'=>$row['net'],
						'Supplier Delivery Items Tax Amount'=>$row['tax'],
						'Supplier Delivery Number Items'=>$row['num_items'],
						'Supplier Delivery Number Ordered Items'=>$row['ordered_items'],
						'Supplier Delivery Number Items Without PO'=>$row['num_items']-$row['ordered_items'],
						'Supplier Delivery Weight'=>$row['weight'],
						'Supplier Delivery CBM'=>$row['cbm'],
						'Supplier Delivery Total Net Amount'=>$total_net,
						'Supplier Delivery Total Tax Amount'=>$total_tax,
						'Supplier Delivery Total Amount'=>$total
					), 'no_history');
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}



}



?>
