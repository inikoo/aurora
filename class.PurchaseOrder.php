<?php
include_once 'class.DB_Table.php';
include_once 'class.Supplier.php';


class PurchaseOrder extends DB_Table{

	function PurchaseOrder($arg1=false, $arg2=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Purchase Order';
		$this->ignore_fields=array('Purchase Order Key');


		if (is_string($arg1)) {
			if (preg_match('/new|create/i', $arg1)) {
				$this->create_order($arg2);
				return;
			}
		}
		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return;
		}
		$this->get_data($arg1, $arg2);

	}



	function create_order($data) {


		$parent=get_object($data['Purchase Order Parent'], $data['Purchase Order Parent Key']);

		$this->editor=$data['editor'];


		$data['Purchase Order Creation Date']=gmdate('Y-m-d H:i:s');
		$data['Purchase Order Last Updated Date']=gmdate('Y-m-d H:i:s');
		$data['Purchase Order Public ID']=$this->get_next_public_id($parent);
		$data['Purchase Order File As']=$this->get_file_as($data['Purchase Order Public ID']);
		$base_data=$this->base_data();



		if (!$parent->id) {
			$this->error=true;
			$this->msg='Error supplier not found';
			return;
		}

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data))
				$base_data[$key]=_trim($value);
		}
		//  print_r($base_data);


		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";

			if (preg_match('/XHTML/', $key))
				$values.="'".addslashes($value)."',";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Purchase Order Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();


			$this->get_data('id', $this->id);



			$history_data=array(
				'History Abstract'=>_('Purchase order created'),
				'History Details'=>'',
				'Action'=>'created'
			);
			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			$this->new=true;

			$parent->update_orders();

		} else {
			// print "Error can not create supplier $sql\n";
		}






	}


	function create_delivery($data) {


		// $warehouse=ger_object('Warehouse',$data['Supplier Delivery Warehouse Key']);




		$delivery_data=array(
			'Supplier Delivery Public ID'=>$data['Supplier Delivery Public ID'],
			'Supplier Delivery Parent'=>$this->get('Purchase Order Parent'),
			'Supplier Delivery Parent Key'=>$this->get('Purchase Order Parent Key'),
			'Supplier Delivery Parent Name'=>$this->get('Purchase Order Parent Name'),
			'Supplier Delivery Parent Code'=>$this->get('Purchase Order Parent Code'),
			'Supplier Delivery Parent Contact Name'=>$this->get('Purchase Order Parent  Contact Name'),
			'Supplier Delivery Parent Email'=>$this->get('Purchase Order Parent  Email'),
			'Supplier Delivery Parent Telephone'=>$this->get('Purchase Order Parent Telephone'),
			'Supplier Delivery Parent Address'=>$this->get('Purchase Order Parent Address'),
			'Supplier Delivery Currency Code'=>$this->get('Purchase Order Currency Code'),
			'Supplier Delivery Incoterm'=>$this->get('Purchase Order Incoterm'),
			'Supplier Delivery Port of Import'=>$this->get('Purchase Order Port of Import'),
			'Supplier Delivery Port of Export'=>$this->get('Purchase Order Port of Export'),
			'Supplier Delivery Purchase Order Key'=>$this->id,
			//'Supplier Delivery Warehouse Key'=>$warehouse->id,
			//'Supplier Delivery Warehouse Metadata'=>json_encode($warehouse->data),

			'editor'=>$this->editor
		);

		//  print_r($delivery_data);



		$delivery=new SupplierDelivery('new', $delivery_data);



		if ($delivery->error) {
			$this->error=true;
			$this->msg=$delivery->msg;

		}elseif ($delivery->new or true) {




			foreach ($data['items'] as $potf_key=>$qty) {


				$sql=sprintf('select `Purchase Order Net Amount`,`Purchase Order Tax Amount`,`Purchase Order Quantity` from `Purchase Order Transaction Fact`  where `Purchase Order Transaction Fact Key`=%d',

					$potf_key
				);
				if ($result=$this->db->query($sql)) {
					if ($row = $result->fetch()) {

						if ($row['Purchase Order Quantity']!=0) {
							$net=$qty*$row['Purchase Order Net Amount']/$row['Purchase Order Quantity'];
							$tax=$qty*$row['Purchase Order Tax Amount']/$row['Purchase Order Quantity'];
						}else {
							$net=0;
							$tax=0;
						}
						$sql=sprintf('update `Purchase Order Transaction Fact` set `Supplier Delivery Net Amount`=%f,`Supplier Delivery Tax Amount`=%f,`Supplier Delivery Quantity`=%f,`Supplier Delivery Key`=%d,`Supplier Delivery Transaction State`=%s where `Purchase Order Transaction Fact Key`=%d',
							$net,
							$tax,
							$qty,
							$delivery->id,
							prepare_mysql('InProcess'),
							$potf_key
						);
						$this->db->exec($sql);
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}



			}
			$delivery->update_totals();
			$this->update_totals();
		}


		return $delivery;

	}


	function get_data($key, $id) {
		if ($key=='id') {
			$sql=sprintf("select * from `Purchase Order Dimension` where `Purchase Order Key`=%d", $id);

		}elseif ($key=='public id' ) {
			$sql=sprintf("select * from `Purchase Order Dimension` where `Purchase Order Public ID`=%s", prepare_mysql($id));

		}elseif ($key=='deleted') {
			$this->get_deleted_data($id);
			return;
		}

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Purchase Order Key'];

			if ($this->data['Purchase Order Metadata']=='') {
				$this->metadata=array();
			}else {
				$this->metadata=json_decode($this->data['Purchase Order Metadata'], true);
			}

		}

	}


	function get_deleted_data( $tag) {

		$this->deleted=true;
		$sql=sprintf("select * from `Purchase Order Deleted Dimension` where `Purchase Order Deleted Key`=%d", $tag);

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Purchase Order Deleted Key'];
			$deleted_data=json_decode(gzuncompress($this->data['Purchase Order Deleted Metadata']), true);
			foreach ( $deleted_data['data'] as $key=>$value) {
				$this->data[$key]=$value;
			}
			$this->items=$deleted_data['items'];
		}
	}


	function get($key='') {
		global $account;

		if (!$this->id)return false;

		switch ($key) {
		case 'State Index':

			switch ($this->data['Purchase Order State']) {
			case 'InProcess':
				return 10;
				break;
			case 'Submitted':
				return 30;
				break;
			case 'Inputted':
				return 60;
				break;
			case 'Dispatched':
				return 70;
				break;
			case 'Received':
				return 80;
				break;
			case 'Checked':
				return 90;
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

		case 'Weight':
			include_once 'utils/natural_language.php';
			if ($this->data['Purchase Order CBM']=='') {
				if ($this->get('Purchase Order Number Items')>0)
					return '<span class="italic very_discreet">'._('Unknown Weight').'</span>';
			}else {
				return ($this->get('Purchase Order Missing Weights')>0?'<i class="fa fa-exclamation-circle warning" aria-hidden="true" title="'._("Some supplier's parts without weight").'" ></i> ':'').weight($this->get('Purchase Order Weight'));
			}
			break;
		case 'CBM':
			if ($this->data['Purchase Order CBM']=='') {
				if ($this->get('Purchase Order Number Items')>0)
					return '<span class="italic very_discreet">'._('Unknown CBM').'</span>';
			}else {
				return ($this->get('Purchase Order Missing CBMs')>0?'<i class="fa fa-exclamation-circle warning" aria-hidden="true" title="'._("Some supplier's parts without CBM").'" ></i> ':'').number($this->data['Purchase Order CBM']).' m³';
			}
			break;
		case 'Estimated Receiving Date':
		case 'Agreed Receiving Date':
		case 'Creation Date':
		case 'Submitted Date':
			if ($this->data['Purchase Order '.$key]=='')return '';
			return strftime("%e %b %Y", strtotime($this->data['Purchase Order '.$key].' +0:00'));

			break;

		case 'Received Date':
			if ($this->get('State Index')<0 or $this->get('State Index')>=60 ) {
				return '';

			}else {

				if ($this->data['Purchase Order Estimated Receiving Date']) {
					return '<span class="discreet"><i class="fa fa-thumb-tack" aria-hidden="true"></i> '.strftime("%e %b %Y", strtotime($this->get('Estimated Receiving Date'))).'</span>';
				}else {

					if ($this->data['Purchase Order State']=='InProcess') {
						$parent=get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);
						if ($parent->get($parent->table_name.' Delivery Days') and is_numeric($parent->get($parent->table_name.' Delivery Days'))) {
							return '<span class="discreet italic">'.strftime("%d-%m-%Y", strtotime('now +'.$parent->get($parent->table_name.' Delivery Days').' days')).'</span>';

						}else {
							return '&nbsp;';
						}
					}else {

						$parent=get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);
						if ($parent->get($parent->table_name.' Delivery Days') and is_numeric($parent->get($parent->table_name.' Delivery Days'))) {
							return '<span class="discreet italic">'.strftime("%d-%m-%Y", strtotime($this->get('Purchase Order Submitted Date').' +'.$parent->get($parent->table_name.' Delivery Days').' days')).'</span>';

						}else {
							return '<span class="super_discreet">'._('Unknown').'</class>';
						}
					}

				}
			}

			break;
		case ('Main Source Type'):
			switch ($this->data['Purchase Order Main Source Type']) {
			case 'Post':
				return _('post');
				break;

			case 'Internet':
				return _('online');
				break;
			case 'Telephone':
				return _('telephone');
				break;
			case 'Fax':
				return _('Fax');
				break;
			case 'In Person':
				return _('in Person');
				break;
			case 'Other':
				return _('other');
				break;


			default:
				return $this->data['Purchase Order Main Source Type'];
				break;
			}
			break;

		case ('State'):
			switch ($this->data['Purchase Order State']) {
			case 'InProcess':
				return _('In Process');
				break;

			case 'Submitted':
				return _('Submitted');
				break;
			case 'Confirmed':
				return _('Confirmed');
				break;
			case 'Checking':
				return _('Checking');
				break;
			case 'In Warehouse':
				return _('In Warehouse');
				break;
			case 'Done':
				return _('Consolidated');
				break;
			case 'Cancelled':
				return _('Cancelled');
				break;



			default:
				return $this->data['Purchase Order State'];
				break;
			}

			break;

		case 'Total Amount':
			return money($this->data['Purchase Order Total Amount'], $this->data['Purchase Order Currency Code']);
			break;
		case 'Number Items':
			return number($this->data ['Purchase Order Number Items']);
			break;
		case 'Number Supplier Delivery Items':

			if ($this->get('State Index')<60) {
				return '-';
			}else {
				return number($this->data ['Purchase Order Number Supplier Delivery Items']);
			}

			break;	
		case 'Number Placed Items':

			if ($this->get('State Index')<80) {
				return '-';
			}else {
				return number($this->data ['Purchase Order Number Placed Items']);
			}

			break;
		default:







			if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/', $key)) {
				$amount='Purchase Order '.$key;
				return money($this->data[$amount], $this->data['Purchase Order Currency Code']);
			}

			if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount Account Currency)$/', $key)) {
				$key=preg_replace('/ Account Currency/', '', $key);
				$amount='Purchase Order '.$key;
				return money($this->data['Purchase Order Currency Exchange']*$this->data[$amount], $account->get('Account Currency'));


			}

			/*

			if (preg_match('/Date$/', $key)) {
				$date='Purchase Order '.$key;
				if ($key=='Estimated Receiving Date' or $key=='Agreed Receiving Date')
					return strftime("%e-%b-%Y", strtotime($this->data[$date].' +0:00'));
				else
					return strftime("%e-%b-%Y %H:%M", strtotime($this->data[$date].' +0:00'));
			}
*/

			break;
		}


		if (array_key_exists($key, $this->data))
			return $this->data[$key];

		if (array_key_exists('Purchase Order '.$key, $this->data))
			return $this->data[$this->table_name.' '.$key];





	}


	function update_item($data) {

		switch ($data['field']) {
		case 'Purchase Order Quantity':
			return $this->update_item_quantity($data);
			break;

		default:

			break;
		}

	}



	function update_item_quantity($data) {


		// Todo calculate taxed, 0 tax for now
		//include_once 'class.TaxCategory.php';
		//$tax_category=new TaxCategory($data['tax_code']);
		//$tax_amount=$tax_category->calculate_tax($data ['amount']);


		$item_key=$data['item_key'];
		$item_historic_key=$data['item_historic_key'];
		$qty=$data['qty'];

		include_once 'class.SupplierPart.php';
		$supplier_part=new SupplierPart($item_key);


		$date=gmdate('Y-m-d H:i:s');
		$transaction_key='';

		if ($qty==0) {

			$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Part Key`=%d ",
				$this->id,
				$item_key
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$sql=sprintf("delete from `Purchase Order Transaction Fact` where `Purchase Order Transaction Fact Key`=%d ",
						$row['Purchase Order Transaction Fact Key']
					);
					$this->db->exec($sql);
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


			$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Part Key`=%d ",
				$this->id,
				$item_key
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$sql = sprintf( "update`Purchase Order Transaction Fact` set  `Purchase Order Quantity`=%f,`Purchase Order Last Updated Date`=%s,`Purchase Order Net Amount`=%f ,`Purchase Order Tax Amount`=%f ,`Purchase Order CBM`=%s,`Purchase Order Weight`=%s  where  `Purchase Order Transaction Fact Key`=%d ",
						$qty,
						prepare_mysql ($date),
						$amount,
						$tax_amount,
						$cbm,
						$weight,
						$row['Purchase Order Transaction Fact Key']
					);
					$this->db->exec($sql);
					/*

					if ($row['Note to Supplier Locked']=='No' and $data['Note to Supplier']!='') {
						$sql = sprintf( "update`Purchase Order Transaction Fact` set  `Note to Supplier`=%s where  `Purchase Order Transaction Fact Key`=%d ",
							prepare_mysql ( $data['Note to Supplier']),
							$row['Purchase Order Transaction Fact Key']
						);

						$this->db->exec($sql);
					}
*/
					$transaction_key=$row['Purchase Order Transaction Fact Key'];
				}else {


					$item_index=1;
					$sql=sprintf('select max(`Purchase Order Item Index`) item_index from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d ',
						$this->id);
					if ($result2=$this->db->query($sql)) {
						if ($row2 = $result2->fetch()) {
							if (is_numeric($row2['item_index'])) {
								$item_index=$row2['item_index']+1;
							}
						}
					}else {
						print_r($error_info=$this->db->errorInfo());
						exit;
					}



					$sql = sprintf( "insert into `Purchase Order Transaction Fact` (`Purchase Order Item Index`,`Supplier Part Key`,`Supplier Part Historic Key`,`Purchase Order Tax Code`,`Currency Code`,`Purchase Order Last Updated Date`,`Purchase Order Transaction State`,
					`Supplier Key`,`Agent Key`,`Purchase Order Key`,`Purchase Order Quantity`,`Purchase Order Net Amount`,`Purchase Order Tax Amount`,`Note to Supplier`,`Purchase Order CBM`,`Purchase Order Weight`,
					`User Key`,`Creation Date`
					)
					values (%d,%d,%d,%s,%s,%s,%s,
					 %d,%s,%d,%.6f,%.2f,%.2f,%s,%s,%s,
					 %d,%s
					 )",
						$item_index,
						$item_key,
						$item_historic_key,
						prepare_mysql ( $tax_code ),
						prepare_mysql ( $this->get('Purchase Order Currency Code') ),
						prepare_mysql ( $date),
						prepare_mysql ($this->get('Purchase Order State') ),

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



				$subtotals=money($amount, $this->get('Purchase Order Currency Code'));

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
				'Purchase_Order_Total_Amount'=>$this->get('Total Amount'),
				'Purchase_Order_Total_Amount_Account_Currency'=>$this->get('Total Amount Account Currency'),
				'Purchase_Order_Weight'=>$this->get('Weight'),
				'Purchase_Order_CBM'=>$this->get('CBM'),
				'Purchase_Order_Number_Items'=>$this->get('Number Items'),
			)
		);

		if ($this->get('Purchase Order Number Items')==0) {
			$this->update_metadata['hide']=array('submit_operation');
		}else {
			$this->update_metadata['show']=array('submit_operation');

		}


		return array('transaction_key'=>$transaction_key, 'subtotals'=>$subtotals, 'to_charge'=>money($amount, $this->data['Purchase Order Currency Code']), 'qty'=>$qty+0);




	}






	function get_next_public_id($parent) {

		$code=$parent->get('Code');

		$line_number=1;
		$sql=sprintf("select `Purchase Order Public ID` from `Purchase Order Dimension` where `Purchase Order Parent Key`=%d order by REPLACE(`Purchase Order Public ID`,%s,'') desc limit 1",
			$parent->id,
			prepare_mysql($code));

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$line_number= (int) preg_replace('/[^\d]/', '', $row['Purchase Order Public ID'])+1;

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		return sprintf('%s%04d', $code, $line_number);

	}


	function get_file_as($name) {

		return $name;
	}


	function update_item_totals_from_order_transactions_old() {




		$sql = "select count(Distinct `Supplier Product ID`) as num_items ,sum(`Purchase Order Net Amount`) as net, sum(`Purchase Order Tax Amount`) as tax,  sum(`Purchase Order Shipping Contribution Amount`) as shipping from `Purchase Order Transaction Fact` where `Purchase Order Key`=" . $this->id;
		//print "$sql\n";
		$result = mysql_query( $sql );
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			//   $total = $row ['gross'] + $row ['tax'] + $row ['shipping']  - $row ['discount'] + $this->data ['Order Items Adjust Amount'];

			$this->data ['Purchase Order Items Net Amount'] = $row ['net'];
			$this->data ['Purchase Order Number Items'] = $row ['num_items'];


			$sql = sprintf( "update `Purchase Order Dimension` set `Purchase Order Number Items`=%d , `Purchase Order Items Net Amount`=%.2f , `Purchase Order Items Tax Amount`=%.2f where  `Purchase Order Key`=%d "
				, $this->data ['Purchase Order Number Items']
				, $this->data ['Purchase Order Items Net Amount']
				, $this->data ['Purchase Order Items Tax Amount']


				, $this->id);


			//exit;
			$this->db->exec($sql);


		}


	}


	function get_number_items() {
		$num_items=0;
		$sql=sprintf("select count(Distinct `Supplier Product ID`) as num_items  from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d", $this->id);
		$result = mysql_query( $sql );
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$num_items=$row['num_items'];
		}

		return $num_items;
	}


	function update_totals() {

		$sql = sprintf("select sum(`Purchase Order Weight`) as  weight,sum(if(isNULL(`Purchase Order Weight`),1,0) )as missing_weights ,sum(if(isNULL(`Purchase Order CBM`),1,0) )as missing_cbms , sum(`Purchase Order CBM` )as cbm ,count(*) as num_items ,sum(if(`Purchase Order Quantity`>0,1,0)) as num_ordered_items ,sum(`Purchase Order Net Amount`) as items_net, sum(`Purchase Order Tax Amount`) as tax,  sum(`Purchase Order Shipping Contribution Amount`) as shipping from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d" ,
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {


				$total_net=$row['items_net']+$this->get('Purchase Order Shipping Net Amount')+$this->get('Purchase Order Charges Net Amount')+  $this->get('Purchase Order Net Credited Amount');
				$total_tax=$row['tax'];
				$total=$total_net+$total_tax;

				$this->update(array(
						'Purchase Order Items Net Amount'=>$row['items_net'],
						'Purchase Order Items Tax Amount'=>$row['tax'],
						'Purchase Order Number Items'=>$row['num_items'],
						'Purchase Order Ordered Number Items'=>$row['num_ordered_items'],
						'Purchase Order Weight'=>$row['weight'],
						'Purchase Order CBM'=>$row['cbm'],
						'Purchase Order Missing Weights'=>$row['missing_weights'],
						'Purchase Order Missing CBMs'=>$row['missing_cbms'],
						'Purchase Order Total Net Amount'=>$total_net,
						'Purchase Order Total Amount'=>$total
					), 'no_history');
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		$sql = sprintf("select count(*) as num_items from `Purchase Order Transaction Fact` where `Supplier Delivery Key`>0 and  `Purchase Order Key`=%d" ,
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->update(array(
						'Purchase Order Number Supplier Delivery Items'=>$row['num_items'],
					), 'no_history');
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($this->get('Purchase Order State')=='InProcess'   ) {

		}else {


			if ($this->get('Purchase Order State')=='Submitted' ) {

				$pending_items_delivery=$this->get('Purchase Order Ordered Number Items')-$this->get('Purchase Order Number Supplier Delivery Items');
				if (!$pending_items_delivery>0 ) {

					$this->update_state('Inputted');

				}
			}


			$max_index=0;
			$max_delivery_state='NA';

			$min_index=100;


			$min_delivery_state='Inputted';


			foreach ($this->get_deliveries('objects') as $delivery) {
				$index=$delivery->get('State Index');


				if ($index<0)continue;

				if ($index>$max_index) {
					$max_index=$index;
					$max_delivery_state=$delivery->get('Supplier Delivery State');
				}

				if ($index<=$min_index) {


					$min_index=$index;
					$min_delivery_state=$delivery->get('Supplier Delivery State');
				}


			}



			$this->update(array(
					'Purchase Order Max Supplier Delivery State'=>$max_delivery_state,
				), 'no_history');


			if ($this->get('State Index')>=60) {
				if ($min_delivery_state=='InProcess') {
					$min_delivery_state='Inputted';
				}

				$this->update_state($min_delivery_state);
			}


		}

	}



	function delete() {

		include_once 'class.Attachment.php';
		if ($this->data['Purchase Order State']=='InProcess') {




			$items=array();
			$sql=sprintf("select POTF.`Supplier Part Historic Key`,`Purchase Order Quantity`,`Supplier Part Reference` from `Purchase Order Transaction Fact` POTF
			left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
            left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)

			 where `Purchase Order Key`=%d", $this->id);


			if ($result=$this->db->query($sql)) {

				foreach ($result as $row) {
					$items[]=array($row['Supplier Part Historic Key'], $row['Supplier Part Reference'], $row['Purchase Order Quantity']);
				}
			}



			$data=array('data'=>$this->data, 'items'=>$items);
			$metadata=json_encode($data);


			$sql=sprintf("insert into `Purchase Order Deleted Dimension`  (`Purchase Order Deleted Key`,`Purchase Order Deleted Public ID`,`Purchase Order Deleted Date`,`Purchase Order Deleted Metadata`) values (%d,%s,%s,%s) ",
				$this->id,
				prepare_mysql($this->get('Purchase Order Public ID')),
				prepare_mysql(gmdate('Y-m-d H:i:s')),
				prepare_mysql(gzcompress($metadata, 9))

			);




			$stmt =  $this->db->prepare($sql);
			$stmt->execute();





			$history_data=array(
				'History Abstract'=>_('Purchase order deleted'),
				'History Details'=>'',
				'Action'=>'created'
			);
			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());


			$sql=sprintf("delete from `Purchase Order Dimension` where `Purchase Order Key`=%d", $this->id);
			$this->db->exec($sql);
			$sql=sprintf("delete from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d", $this->id);
			$this->db->exec($sql);


			$sql=sprintf("select `History Key`,`Type` from `Purchase Order History Bridge` where `Purchase Order Key`=%d", $this->id);
			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {


					if ($row['Type']=='Attachments') {
						$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='Purchase Order History Attachment' and `Subject Key`=%d",
							$row['History Key']
						);

						if ($result2=$this->db->query($sql)) {
							foreach ($result2 as $row2) {
								$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d", $row2['Attachment Bridge Key']);
								$this->db->exec($sql);
								$attachment=new Attachment($row2['Attachment Key']);
								$attachment->delete();
							}
						}else {
							print_r($error_info=$this->db->errorInfo());
							exit;
						}


						$res2=mysql_query($sql);
						while ($row2=mysql_fetch_assoc($res2)) {

						}
					}




				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}





			$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='Purchase Order' and `Subject Key`=%d",
				$this->id
			);
			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d", $row['Attachment Bridge Key']);
					$this->db->exec($sql);
					$attachment=new Attachment($row['Attachment Key']);
					$attachment->delete();
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}




			$this->deleted=true;

		}else {

			$this->error=true;
			$this->msg='Can not deleted submitted purchase orders';
		}
	}


	function mark_as_confirmed($data) {

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Purchase Order Dimension` set `Purchase Order Confirmed Date`=%s,`Purchase Order Agreed Receiving Date`=%s ,`Purchase Order Estimated Receiving Date`=%s,`Purchase Order State`='Confirmed' where `Purchase Order Key`=%d",
			prepare_mysql($data['Purchase Order Confirmed Date']),
			prepare_mysql($data['Purchase Order Agreed Receiving Date']),
			prepare_mysql($data['Purchase Order Agreed Receiving Date']),
			$this->id);


		$this->db->exec($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s ,`Purchase Order Transaction State`='Confirmed'  where `Purchase Order Key`=%d",
			prepare_mysql($data['Purchase Order Confirmed Date']),
			$this->id);
		$this->db->exec($sql);

		$this->get_data('id', $this->id);
		$this->update_affected_products();

		$history_data=array(
			'History Abstract'=>_('Purchase order marked as confirmed'),
			'History Details'=>''
		);
		$this->add_subject_history($history_data);

	}


	function submit($data) {

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Purchase Order Dimension` set `Purchase Order Submitted Date`=%s,`Purchase Order Estimated Receiving Date`=%s,`Purchase Order Main Source Type`=%s,`Purchase Order Main Buyer Key`=%s,`Purchase Order Main Buyer Name`=%s,`Purchase Order State`='Submitted' where `Purchase Order Key`=%d",
			prepare_mysql($data['Purchase Order Submitted Date']),
			prepare_mysql($data['Purchase Order Estimated Receiving Date']),
			prepare_mysql($data['Purchase Order Main Source Type']),
			prepare_mysql($data['Purchase Order Main Buyer Key']),
			prepare_mysql($data['Purchase Order Main Buyer Name']),
			$this->id
		);


		$this->db->exec($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s ,`Purchase Order Transaction State`='Submitted'  where `Purchase Order Key`=%d",
			prepare_mysql($data['Purchase Order Submitted Date']),
			$this->id);
		$this->db->exec($sql);


		$this->update_affected_products();

		$history_data=array(
			'History Abstract'=>_('Purchase order submitted'),
			'History Details'=>''
		);
		$this->add_subject_history($history_data);

	}


	function mark_as_associated_with_sdn($sdn_key, $sdn_name) {

		$sql=sprintf("update `Purchase Order Dimension` set `Purchase Order State`='In Warehouse' where `Purchase Order Key`=%d",
			$this->id
		);
		$this->db->exec($sql);

		$history_data=array(
			'History Abstract'=>sprintf(_('Purchase order associeted with delivery %s'), '<a href="supplier_dn.php?id='.$sdn_key.'">'.$sdn_name.'</a>'),
			'History Details'=>''
		);
		$this->add_subject_history($history_data);

	}





	function update_affected_products() {

		//TODO
		return;

		$sql=sprintf("select `Supplier Product ID`,`Purchase Order Quantity` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			//print_r($row);

			$supplier_product=new SupplierProduct('pid', $row['Supplier Product ID']);
			$parts=$supplier_product->get_parts();
			foreach ($parts as $part) {
				$parts=new Part($part['Part_SKU']);
				$parts->update_next_supplier_shipment_from_po();

			}

		}
	}


	function cancel($data) {
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=$value;
			}

		}

		$sql=sprintf("update `Purchase Order Dimension` set `Purchase Order Cancelled Date`=%s,`Purchase Order Cancel Note`=%s, `Purchase Order State`='Cancelled'   where `Purchase Order Key`=%d"
			, prepare_mysql($this->data['Purchase Order Cancelled Date'])
			, prepare_mysql($this->data['Purchase Order Cancel Note'], false)
			, $this->id);
		$this->db->exec($sql);
		//print $sql;
		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s `Purchase Order Transaction State`='Cancelled'  where `Purchase Order Key`=%d"
			, prepare_mysql($data['Purchase Order Cancelled Date'])
			, $this->id
		);
		$this->db->exec($sql);

		$this->update_affected_products();



	}


	function update_estimated_receiving_date($date) {

		include_once 'utils/date_functions.php';

		$date_data=prepare_mysql_datetime($date, 'date');
		if ($date_data['ok']) {
			$this->update_field('Purchase Order Estimated Receiving Date', $date_data['mysql_date']);
			if ($this->updated) {

				$this->update_affected_products();
			}
			$this->new_value=$this->get('Estimated Receiving Date');
		}else {
			$this->error=true;
			$this->msg=$date_data['status'];

		}


	}




	function update_field_switcher($field, $value, $options='', $metadata='') {
		switch ($field) {
		case 'Purchase Order State':
			$this->update_state($value, $options, $metadata);
			break;
		case 'Purchase Order Estimated Receiving Date':
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


	function update_state($value, $options='', $metadata=array()) {
		$date=gmdate('Y-m-d H:i:s');


		$old_value=$this->get('Purchase Order State');
		$operations=array();


		if ($old_value!=$value) {
			switch ($value) {
			case 'InProcess':

				$this->update_field('Purchase Order Submitted Date', '', 'no_history');
				$this->update_field('Purchase Order Estimated Receiving Date', '', 'no_history');
				$this->update_field('Purchase Order State', $value, 'no_history');
				$operations=array('delete_operations', 'submit_operations', 'all_available_items', 'new_item');


				$history_data=array(
					'History Abstract'=>_('Purchase order send back to process'),
					'History Details'=>'',
					'Action'=>'created'
				);
				$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());



				break;

			case 'Submitted':



				$this->update_field('Purchase Order Submitted Date', $date, 'no_history');
				$this->update_field('Purchase Order Send Date', '', 'no_history');

				$this->update_field('Purchase Order State', $value, 'no_history');
				$operations=array('cancel_operations', 'undo_submit_operations', 'received_operations');

				if ($old_value!='Submitted') {
					if ($this->get('State Index')<=30) {
						$history_abstract=_('Purchase order submitted');
					}else {
						$history_abstract=_('Purchase order set back as submitted');

					}


					$history_data=array(
						'History Abstract'=>$history_abstract,
						'History Details'=>'',
						'Action'=>'created'
					);
					$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

				}

				break;

			case 'Inputted':
			case 'Dispatched':
			case 'Received':
			case 'Checked':
			case 'Placed':


				$this->update_field('Purchase Order State', $value, 'no_history');
				foreach ($metadata as $key=>$_value) {
					$this->update_field($key, $_value, 'no_history');
				}



				break;
			default:
				exit('unknown state:'.$value);
				break;
			}


			$sql=sprintf('update `Purchase Order Transaction Fact` set `Purchase Order Last Updated Date`=%s where `Purchase Order Key`=%d ',
				prepare_mysql($date),
				$this->id
			);
			$this->db->exec($sql);

			$sql=sprintf('update `Purchase Order Transaction Fact` set `Purchase Order Transaction State`=%s where `Purchase Order Key`=%d ',
				prepare_mysql($value),
				$this->id
			);
			$this->db->exec($sql);

			$this->update_affected_products();

		}

		$this->update_metadata=array(
			'class_html'=>array(
				'Purchase_Order_State'=>$this->get('State'),
				'Purchase_Order_Submitted_Date'=>'&nbsp;'.$this->get('Submitted Date'),
				'Purchase_Order_Received_Date'=>'&nbsp;'.$this->get('Received Date'),
				'Purchase_Order_Send_Date'=>'&nbsp;'.$this->get('Send Date'),

			),
			'operations'=>$operations,
			'state_index'=>$this->get('State Index'),
			'pending_items_in_delivery'=>$this->get('Purchase Order Ordered Number Items')-$this->get('Purchase Order Number Supplier Delivery Items')
		);





	}


	function get_estimated_delivery_date() {
		if ($this->data['Purchase Order Estimated Receiving Date']) {
			return strftime("%d-%m-%Y", strtotime($this->data['Purchase Order Estimated Receiving Date']));
		}else {

			if ($this->data['Purchase Order State']=='InProcess') {
				$parent=get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);
				if ($parent->get($parent->table_name.' Delivery Days') and is_numeric($parent->get($parent->table_name.' Delivery Days'))) {
					return strftime("%d-%M-%Y", strtotime('now +'.$parent->get($parent->table_name.' Delivery Days').' days'));

				}else {
					return '';
				}
			}else {

				return '';
			}

		}
	}





	function get_deliveries($scope='keys') {

		if ($scope=='objects') {
			include_once 'class.SupplierDelivery.php';
		}


		$deliveries=array();
		$sql=sprintf("select `Supplier Delivery Key` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d  group by `Supplier Delivery Key`",
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($row['Supplier Delivery Key']=='')continue;

				if ($scope=='objects') {

					$deliveries[$row['Supplier Delivery Key']]=new SupplierDelivery($row['Supplier Delivery Key']);

				}else {
					$deliveries[$row['Supplier Delivery Key']]=$row['Supplier Delivery Key'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $deliveries;

	}






	function get_field_label($field) {

		switch ($field) {

		case 'Purchase Order Public ID':
			$label=_('public Id');
			break;
		case 'Purchase Order Incoterm':
			$label=_('Incoterm');
			break;
		case 'Purchase Order Port of Export':
			$label=_('port of export');
			break;
		case 'Purchase Order Port of Import':
			$label=_('port of import');
			break;
		case 'Purchase Order Estimated Receiving Date':
			$label=_('estimated receiving date');
			break;
		case 'Purchase Order Agreed Receiving Date':
			$label=_('agreed receiving date');
			break;

		default:
			$label=$field;

		}

		return $label;

	}


}


?>
