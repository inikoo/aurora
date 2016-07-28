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

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Supplier Delivery Key'];

				$this->found=true;
				$this->found_key=$row['Supplier Delivery Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Supplier Delivery Public ID';



			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		if ($this->found_key) {
			$this->get_data('id', $this->found_key);
		}

		if ($create and !$this->found_key) {

			$this->create($data);

		}


	}


	function create($data) {

		include_once 'utils/natural_language.php';

		$parent=get_object($data['Supplier Delivery Parent'], $data['Supplier Delivery Parent Key']);


		if (!$parent->id) {
			$this->error=true;
			$this->msg='wrong parent';
			return;
		}



		//print_r($data);
		$data['Supplier Delivery Creation Date']=gmdate('Y-m-d H:i:s');
		$data['Supplier Delivery Last Updated Date']=gmdate('Y-m-d H:i:s');


		$data['Supplier Delivery File As']=get_file_as($data['Supplier Delivery Public ID']);
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
		}else {
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
		if (!$this->id) {
			return;
		}






		switch ($key) {
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
			case 'Placed':
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
		case 'Checked Percentage or Date':
			if ($this->get('State Index')<0) {
				if ($this->get('Supplier Delivery Checked Date')=='')return '';
				return strftime("%e %b %Y", strtotime($this->get('Supplier Delivery Checked Date')));

			}elseif ($this->get('State Index')<40) {
				return '';
			}elseif ($this->get('State Index')<50) {
				return percentage($this->get('Supplier Delivery Number Checked Items'), $this->get('Supplier Delivery Number Dispatched Items'));
			}else {
				if ($this->data['Supplier Delivery Checked Date']=='')return '';
				return strftime("%e %b %Y", strtotime($this->data['Supplier Delivery Checked Date'].' +0:00'));
			}

			break;
		case 'Placed Percentage or Date':



			if ($this->get('State Index')<40) {
				return '';
			}elseif ($this->get('State Index')<50) {
				if ($this->get('Supplier Delivery Number Placed Items')>0) {
					return percentage($this->get('Supplier Delivery Number Placed Items'), $this->get('Supplier Delivery Number Dispatched Items'));
				}else {
					return '';
				}
			}elseif ($this->get('State Index')<100) {
				return percentage($this->get('Supplier Delivery Number Placed Items'), $this->get('Supplier Delivery Number Received and Checked Items'));
			}else {
				if ($this->data['Supplier Delivery Checked Date']=='')return '';
				return strftime("%e %b %Y", strtotime($this->data['Supplier Delivery Placed Date'].' +0:00'));
			}

			break;
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
		case 'Cancelled Date':
			if ($this->data['Supplier Delivery '.$key]=='')return '';
			return strftime("%e %b %Y", strtotime($this->data['Supplier Delivery '.$key].' +0:00'));

			break;
		case 'Received Date':
			if ($this->get('State Index')<0) {
				if ($this->get('Supplier Delivery Received Date')=='')return '';
				return strftime("%e %b %Y", strtotime($this->get('Supplier Delivery Received Date')));

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
			break;

		case ('State'):
			switch ($this->data['Supplier Delivery State']) {
			case 'InProcess':
				return _('Inputted');
				break;
			case 'Dispatched':
				return _('Dispatched');
				break;
			case 'Confirmed':
				return _('Confirmed');
				break;
			case 'Received':
				return _('Received');
				break;
			case 'Placed':
				return _('Placed');
				break;
			case 'Cancelled':
				return _('Cancelled');
				break;
			default:
				break;
			}

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









	function update_field_switcher($field, $value, $options='', $metadata='') {
		switch ($field) {
		case 'Supplier Delivery State':
			$this->update_state($value, $options, $metadata);
			break;
		case 'Supplier Delivery Estimated Receiving Date':
			$this->update_field($field, $value, $options);
			$this->update_supplier_parts();

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



	function update_item($data) {

		switch ($data['field']) {
		case 'Supplier Delivery Quantity':
			return $this->update_item_delivery_quantity($data);
			break;
		case 'Supplier Delivery Checked Quantity':
			return $this->update_item_delivery_checked_quantity($data);
			break;
		case 'Supplier Delivery Placed Quantity':
			return $this->update_item_delivery_placed_quantity($data);
			break;
		default:

			break;
		}



	}


	function update_item_delivery_quantity($data) {

		$item_key=$data['item_key'];
		$item_historic_key=$data['item_historic_key'];
		$qty=$data['qty'];


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

						$sql=sprintf("update  `Purchase Order Transaction Fact` set  `Supplier Delivery Key`=NULL,`Supplier Delivery Received Location Key`=1, `Supplier Delivery Quantity`=0,`Supplier Delivery Checked Quantity`=0,`Supplier Delivery Damaged Quantity`=0,`Supplier Delivery Placed Quantity`=0,`Supplier Delivery Net Amount`=0,`Supplier Delivery Tax Amount`=0,`Supplier Delivery Transaction State`=NULL,`Supplier Delivery Counted`='No' where `Purchase Order Transaction Fact Key`=%d ",
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


	function update_item_delivery_checked_quantity($data) {

		include_once 'class.SupplierPart.php';
		$supplier_part=new SupplierPart($data['item_key']);


		$date=gmdate('Y-m-d H:i:s');
		$transaction_key=$data['transaction_key'];


		$qty=$data['qty']/$supplier_part->get('Supplier Part Packages Per Carton');
		if ($qty<0)$qty=0;


		$sql=sprintf('select `Supplier Delivery Transaction Placed`,`Part SKU`,POTF.`Purchase Order Transaction Fact Key`,`Supplier Delivery Placed Quantity`,`Supplier Part Packages Per Carton`,POTF.`Metadata`
		 from `Purchase Order Transaction Fact`  POTF
left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)



		where  `Purchase Order Transaction Fact Key`=%d ',
			$transaction_key
		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				if ($qty==0) {

					if ($row['Supplier Delivery Placed Quantity']>$qty) {
						$placed='Yes';
					}else {
						$placed='NA';
					}
				}else {

					if ($row['Supplier Delivery Placed Quantity']>=$qty) {
						$placed='Yes';
					}else {
						$placed='No';
					}

				}

				$sql = sprintf( "update`Purchase Order Transaction Fact` set  `Supplier Delivery Checked Quantity`=%f,`Supplier Delivery Last Updated Date`=%s ,`Supplier Delivery Transaction Placed`=%s where  `Purchase Order Transaction Fact Key`=%d ",
					$qty,
					prepare_mysql ($date),
					prepare_mysql ($placed),
					$transaction_key
				);

				$this->db->exec($sql);

				if ($placed=='Yes') {
					$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Transaction State`="Placed"  where `Purchase Order Transaction Fact Key`=%d ',

						$this->id
					);
					$this->db->exec($sql);
				}else {
					$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Transaction State`="Checked"  where `Purchase Order Transaction Fact Key`=%d ',

						$this->id
					);
				}


				$quantity=($qty-$row['Supplier Delivery Placed Quantity'])*$row['Supplier Part Packages Per Carton'];


				if ($row['Metadata']=='') {
					$metadata=array();
				}else {
					$metadata=json_decode($row['Metadata'], true);
				}

				$placement='<div  class="placement_data mini_table right no_padding" style="padding-right:2px">';
				if (  isset($metadata['placement_data'])) {

					foreach ($metadata['placement_data'] as $placement_data) {
						$placement.='<div style="clear:both;">
				<div class="data w150 aright link" onClick="change_view(\'locations/'.$placement_data['wk'].'/'.$placement_data['lk'].'\')" >'.$placement_data['l'].'</div>
				<div  class=" data w75 aleft"  >'.$placement_data['qty'].' '._('SKO').' <i class="fa fa-sign-out" aria-hidden="true"></i></div>
				</div>';


					}
				}
				$placement.='<div style="clear:both"></div>';


				$placement.='
			    <div style="clear:both"  id="place_item_'.$row['Purchase Order Transaction Fact Key'].'" class="place_item '.($placed=='No'?'':'hide').' " part_sku="'.$row['Part SKU'].'" transaction_key="'.$row['Purchase Order Transaction Fact Key'].'"  >
			    <input class="place_qty width_50 changed" value="'.($quantity+0).'" ovalue="'.($quantity+0).'"  min="1" max="'.$quantity.'"  >
				<input class="location_code"  placeholder="'._('Location code').'"  >
				<i  class="fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Place to location').'"  location_key="" onClick="place_item(this)"  ></i>
                <div>';




			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		$this->update_totals();

		if ($this->get('Supplier Delivery State')=='Received') {
			if ($this->get('Supplier Delivery Number Dispatched Items')==$this->get('Supplier Delivery Number Checked Items')) {
				$this->update_state('Checked');
			}

		}elseif ($this->get('Supplier Delivery State')=='Checked') {
			if ($this->get('Supplier Delivery Number Placed Items')==$this->get('Supplier Delivery Number Received and Checked Items')) {
				$this->update_state('Placed');
			}

		}






		$this->update_metadata=array(
			'class_html'=>array(
				'Supplier_Delivery_State'=>$this->get('State'),
				'Supplier_Delivery_Number_Received_and_Checked_Items'=>$this->get('Number Received and Checked Items'),
				'Supplier_Delivery_Checked_Percentage_or_Date'=>'&nbsp;'.$this->get('Checked Percentage or Date'),
				'Supplier_Delivery_Placed_Percentage_or_Date'=>'&nbsp;'.$this->get('Placed Percentage or Date')
			),
			'placement'=>$placement,
			'state_index'=>$this->get('State Index')

		);


		return array('transaction_key'=>$transaction_key, 'qty'=>$qty+0);




	}


	function update_item_delivery_placed_quantity($data) {

		include_once 'class.SupplierPart.php';



		$date=gmdate('Y-m-d H:i:s');
		$transaction_key=$data['transaction_key'];





		$sql=sprintf('select POTF.`Purchase Order Transaction Fact Key`,`Supplier Part Packages Per Carton`,`Part SKU`,`Supplier Delivery Placed Quantity`,POTF.`Supplier Part Key`,`Supplier Delivery Checked Quantity`,`Metadata`

		 from `Purchase Order Transaction Fact`  POTF
left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)

		  where  `Purchase Order Transaction Fact Key`=%d ',
			$transaction_key
		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {


				$supplier_part=new SupplierPart($row['Supplier Part Key']);
				$qty=($data['qty']+$row['Supplier Delivery Placed Quantity'])/$supplier_part->get('Supplier Part Packages Per Carton');
				if ($qty<0)$qty=0;


				if ($row['Supplier Delivery Checked Quantity']<$qty) {
					$this->error=true;
					$this->msg='Placed qty > than delivery qty';

					return false;
				}elseif ($row['Supplier Delivery Checked Quantity']==$qty) {
					$placed='Yes';
				}else {
					$placed='No';
				}

				if ($row['Metadata']=='') {
					$metadata=array('placement_data'=>array($data['placement_data']));
				}else {
					$metadata=json_decode($row['Metadata'], true);
					if (isset($metadata['placement_data'])) {
						$metadata['placement_data'][]=$data['placement_data'];
					}else {
						$metadata['placement_data']=array($data['placement_data']);
					}
				}

				$placement_data=$metadata['placement_data'];

				$encoded_metadata=json_encode($metadata);

				$sql = sprintf( "update`Purchase Order Transaction Fact` set  `Supplier Delivery Placed Quantity`=%f,`Supplier Delivery Last Updated Date`=%s ,`Supplier Delivery Transaction Placed`=%s ,`Metadata`=%s where  `Purchase Order Transaction Fact Key`=%d ",
					$qty,
					prepare_mysql ($date),
					prepare_mysql ($placed),
					prepare_mysql ($encoded_metadata),
					$transaction_key
				);






				$this->db->exec($sql);

				$place_qty=($row['Supplier Delivery Checked Quantity']-$qty)*$supplier_part->get('Supplier Part Packages Per Carton');

				if ($placed=='Yes') {
					$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Transaction State`="Placed"  where `Purchase Order Transaction Fact Key`=%d ',

						$this->id
					);
					$this->db->exec($sql);
				}








				$placement='<div  class="placement_data mini_table right no_padding" style="padding-right:2px">';
				if (  isset($metadata['placement_data'])) {

					foreach ($metadata['placement_data'] as $placement_data) {
						$placement.='<div style="clear:both;">
				<div class="data w150 aright link" onClick="change_view(\'locations/'.$placement_data['wk'].'/'.$placement_data['lk'].'\')" >'.$placement_data['l'].'</div>
				<div  class=" data w75 aleft"  >'.$placement_data['qty'].' '._('SKO').' <i class="fa fa-sign-out" aria-hidden="true"></i></div>
				</div>';


					}
				}
				$placement.='<div style="clear:both"></div>';


				$placement.='
			    <div style="clear:both"  id="place_item_'.$row['Purchase Order Transaction Fact Key'].'" class="place_item '.($placed=='No'?'':'hide').' " part_sku="'.$row['Part SKU'].'" transaction_key="'.$row['Purchase Order Transaction Fact Key'].'"  >
			    <input class="place_qty width_50 changed" value="'.($place_qty+0).'" ovalue="'.($place_qty+0).'"  min="1" max="'.$place_qty.'"  >
				<input class="location_code"  placeholder="'._('Location code').'"  >
				<i  class="fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Place to location').'"  location_key="" onClick="place_item(this)"  ></i>
                <div>';





			}else {
				$this->error=true;
				$this->msg='po transaction not found';
				return;
			}


		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		$this->update_totals();



		if ($this->get('Supplier Delivery State')=='Checked') {
			if ($this->get('Supplier Delivery Number Placed Items')==$this->get('Supplier Delivery Number Received and Checked Items')) {
				$this->update_state('Placed');
			}

		}

		$operations=array();


		$this->update_metadata=array(
			'class_html'=>array(
				'Supplier_Delivery_State'=>$this->get('State'),
				'Supplier_Delivery_Number_Placed_Items'=>$this->get('Number Placed Items'),
				'Supplier_Delivery_Checked_Percentage_or_Date'=>'&nbsp;'.$this->get('Checked Percentage or Date'),
				'Supplier_Delivery_Placed_Percentage_or_Date'=>'&nbsp;'.$this->get('Placed Percentage or Date')

			),
			'placement'=>$placement,
			'operations'=>$operations,
			'state_index'=>$this->get('State Index')
		);



		return array('transaction_key'=>$transaction_key, 'qty'=>$qty+0, 'placement_data'=>$placement_data, 'placed'=>$placed, 'place_qty'=>$place_qty);




	}




	function delete() {

		if ($this->data['Supplier Delivery State']=='InProcess') {



			$sql=sprintf("delete from `Supplier Delivery Dimension` where `Supplier Delivery Key`=%d", $this->id);
			$this->db->exec($sql);
			$sql=sprintf("delete from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and `Purchase Order Key` is NULL  ", $this->id);
			$this->db->exec($sql);
			$sql=sprintf("update `Purchase Order Transaction Fact` set `Supplier Delivery Key`=NULL ,`Supplier Delivery Quantity`=0 ,`Supplier Delivery Checked Quantity`=NULL,`Supplier Delivery Placed Quantity`=NULL,`Supplier Delivery Net Amount`=0,`Supplier Delivery Tax Amount`=0,`Supplier Delivery Transaction State`=NULL,`Supplier Delivery Transaction Placed`=NULL,`Supplier Delivery CBM`=NULL,`Supplier Delivery CBM`=NULL  ,`Supplier Delivery Last Updated Date`=NULL  where `Supplier Delivery Key`=%d  ", $this->id);
			$this->db->exec($sql);





			$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='Supplier Delivery' and `Subject Key`=%d",
				$this->id
			);


			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {

					$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d", $row['Attachment Bridge Key']);
					$this->db->exec($sql);
					$attachment=new Attachment($row2['Attachment Key']);
					$attachment->delete();

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


			include_once 'class.PurchaseOrder.php';
			$purchase_order=new PurchaseOrder($this->get('Supplier Delivery Purchase Order Key'));
			$purchase_order->update_totals();

			if ( $purchase_order->get('Purchase Order Submitted Date')!='') {
				$purchase_order->update_state('Submitted', array('Purchase Order Submitted Date'=>$purchase_order->get('Purchase Order Submitted Date')));
			}


			$this->deleted=true;


			return sprintf('%s/%d/order/%s',
				strtolower($purchase_order->get('Purchase Order Parent')),
				$purchase_order->get('Purchase Order Parent Key'),
				$purchase_order->id
			);



		} else {
			$this->error=true;
			$this->msg='Can not deleted submitted Supplier Delivery';
		}
	}





	function update_supplier_parts() {

		include_once 'class.SupplierPart.php';

		$sql=sprintf("select `Supplier Part Key`,`Supplier Delivery Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d", $this->id);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				$supplier_part=new SupplierPart($row['Supplier Part Key']);
				$supplier_part->update_next_supplier_shippment();



			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
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


	function update_state($value, $options='', $metadata=array()) {
		$date=gmdate('Y-m-d H:i:s');



		if ($value=='InProcess or Dispatched') {

			if ($this->get('Supplier Delivery Placed Items')=='Yes') {
				$this->error=true;
				$this->msg="Can't roll back delivery status with placed items";
				return;
			}


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


			$operations=array('delete_operations', 'received_operations', 'dispatched_operations');


			break;



		case 'Dispatched':




			$this->update_field('Supplier Delivery Dispatched Date', $date, 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');
			foreach ($metadata as $key=>$_value) {

				$this->update_field($key, $_value, 'no_history');
			}

			$operations=array('cancel_operations', 'undo_send_operations', 'received_operations');

			$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Transaction State`=%s ,`Supplier Delivery Last Updated Date`=%s where `Supplier Delivery Key`=%d ',
				prepare_mysql($value),
				prepare_mysql($date),
				$this->id
			);
			$this->db->exec($sql);


			$operations=array('cancel_operations', 'undo_dispatched_operations', 'received_operations');


			break;
		case 'Received':

			$this->update_field('Supplier Delivery Received Date', $date, 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');
			foreach ($metadata as $key=>$_value) {
				$this->update_field($key, $_value, 'no_history');
			}

			$operations=array('cancel_operations', 'undo_send_operations', 'received_operations');

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

			$operations=array('cancel_operations', 'undo_received_operations', );





			break;
		case 'Checked':

			$this->update_field('Supplier Delivery Checked Date', $date, 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');
			foreach ($metadata as $key=>$_value) {
				$this->update_field($key, $_value, 'no_history');
			}

			$operations=array('cancel_operations', 'undo_send_operations', 'received_operations');


			break;
		case 'Placed':

			$this->update_field('Supplier Delivery Placed Date', $date, 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');
			foreach ($metadata as $key=>$_value) {
				$this->update_field($key, $_value, 'no_history');
			}

			$operations=array();


			break;
		case 'Cancelled':


			if ($this->get('Supplier Delivery Placed Items')=='Yes') {
				$this->error=true;
				$this->msg="Can't cancel delivery with placed items";
				return;
			}

			$this->update_field('Supplier Delivery Cancelled Date', $date, 'no_history');
			$this->update_field('Supplier Delivery State', $value, 'no_history');


			$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Transaction State`=%s ,`Supplier Delivery Last Updated Date`=%s where `Supplier Delivery Key`=%d ',
				prepare_mysql($value),
				prepare_mysql($date),
				$this->id
			);
			$this->db->exec($sql);


			$history_data=array(
				'History Abstract'=>_('Supplier delivery cancelled'),
				'History Details'=>'',
				'Action'=>'edited'
			);
			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());
			$operations=array();
			break;

		default:
			exit('unknown state '.$value);
			break;
		}


		$this->update_totals();

		include_once 'class.PurchaseOrder.php';
		$purchase_order=new PurchaseOrder($this->get('Supplier Delivery Purchase Order Key'));
		$purchase_order->update_totals();


		$this->update_metadata=array(
			'class_html'=>array(
				'Supplier_Delivery_State'=>$this->get('State'),
				'Supplier_Delivery_Dispatched_Date'=>'&nbsp;'.$this->get('Dispatched Date'),
				'Supplier_Delivery_Received_Date'=>'&nbsp;'.$this->get('Received Date'),
				'Supplier_Delivery_Checked_Date'=>'&nbsp;'.$this->get('Checked Date'),
				'Supplier_Delivery_Number_Dispatched_Items'=>$this->get('Number Dispatched Items'),
				'Supplier_Delivery_Number_Received_Items'=>$this->get('Number Received Items')

			),
			'operations'=>$operations,
			'state_index'=>$this->get('State Index')
		);






	}


	function update_totals() {





		$sql = sprintf("select  sum(if(`Supplier Delivery Transaction Placed`='Yes',1,0)) as placed_items, sum(if(`Supplier Delivery Transaction Placed`='No',1,0)) as no_placed_items,  sum(`Supplier Delivery Weight`) as  weight,sum(`Supplier Delivery CBM` )as cbm ,
		 sum( if( `Supplier Delivery Checked Quantity` is null,0,1)) as checked_items,
		 sum( if( `Supplier Delivery Checked Quantity`>0,1,0)) as received_checked_items,
		 sum(if(`Purchase Order Key`>0,1,0)) as ordered_items,
		sum(if(`Supplier Delivery Quantity`>0,1,0))  num_items,

		sum(`Supplier Delivery Placed Quantity`) as placed_qty,

		sum(`Supplier Delivery Net Amount`) as net,sum(`Supplier Delivery Tax Amount`) as tax from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d" ,
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				if ($this->get('State Index')>=30) {
					$dispatched_items=$row['num_items'];
				}else {
					$dispatched_items=0;
				}


				$total_net=$row['net']+$this->get('Supplier Delivery Shipping Net Amount')+$this->get('Supplier Delivery Charges Net Amount')+  $this->get('Supplier Delivery Other Net Amount');
				$total_tax=$row['tax']+$this->get('Supplier Delivery Shipping Tax Amount')+$this->get('Supplier Delivery Charges Tax Amount')+  $this->get('Supplier Delivery Other Tax Amount');
				$total=$total_net+$total_tax;

				$this->update(array(
						'Supplier Delivery Items Net Amount'=>$row['net'],
						'Supplier Delivery Items Tax Amount'=>$row['tax'],
						'Supplier Delivery Number Items'=>$row['num_items'],
						'Supplier Delivery Number Dispatched Items'=>$dispatched_items,
						'Supplier Delivery Number Checked Items'=>$row['checked_items'],
						'Supplier Delivery Number Received and Checked Items'=>$row['received_checked_items'],
						'Supplier Delivery Number Placed Items'=>$row['placed_items'],
						'Supplier Delivery Number Ordered Items'=>$row['ordered_items'],
						'Supplier Delivery Number Items Without PO'=>$row['num_items']-$row['ordered_items'],
						'Supplier Delivery Weight'=>$row['weight'],
						'Supplier Delivery CBM'=>$row['cbm'],
						'Supplier Delivery Total Net Amount'=>$total_net,
						'Supplier Delivery Total Tax Amount'=>$total_tax,
						'Supplier Delivery Total Amount'=>$total,
						'Supplier Delivery Placed Items'=>($row['placed_qty']>0?'Yes':'No')
					), 'no_history');




			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}



}



?>
