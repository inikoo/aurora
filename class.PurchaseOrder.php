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

		//  print($sql);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id', $this->id);
			$history_data=array(
				'History Abstract'=>_('Purchase order created'),
				'History Details'=>'',
				'Action'=>'created'
			);
			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			$parent->update_orders();
		}else
			exit(" error can no create purchse order ".mysql_error().' '.$sql);


	}


	function get_data($key, $id) {
		if ($key=='id') {
			$sql=sprintf("select * from `Purchase Order Dimension` where `Purchase Order Key`=%d", $id);
			$result=mysql_query($sql);
			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->id=$this->data['Purchase Order Key'];
			}
		}elseif ($key=='public id' ) {
			$sql=sprintf("select * from `Purchase Order Dimension` where `Purchase Order Public ID`=%s", prepare_mysql($id));
			$result=mysql_query($sql);
			print "$sql\n";
			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->id=$this->data['Purchase Order Key'];
			}
		}
	}


	function get($key='') {
		global $account;

		if (!$this->id)return false;

		switch ($key) {

		case 'Weight':
			include_once 'utils/natural_language.php';
			return weight($this->get('Purchase Order Weight'));
			break;
		case 'CBM':
			if ($this->data['Purchase Order CBM']=='')return '';
			return number($this->data['Purchase Order CBM']).' m³';
			break;
		case 'Estimated Receiving Date':
		case 'Agreed Receiving Date':
		case 'Creation Date':
			return strftime("%e %b %Y", strtotime($this->data['Purchase Order '.$key].' +0:00'));

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
			case 'In Process':
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


		default:






			if ($key=='Number Items')
				return number($this->data ['Purchase Order Number Items']);
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

					$sql = sprintf( "update`Purchase Order Transaction Fact` set  `Purchase Order Quantity`=%f,`Purchase Order Last Updated Date`=%s,`Purchase Order Net Amount`=%f ,`Purchase Order Tax Amount`=%f ,CBM=%s,`Weight`=%s  where  `Purchase Order Transaction Fact Key`=%d ",
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
						mysql_query($sql);
					}
*/
					$transaction_key=$row['Purchase Order Transaction Fact Key'];
				}else {

					$sql = sprintf( "insert into `Purchase Order Transaction Fact` (`Supplier Part Key`,`Supplier Part Historic Key`,`Purchase Order Tax Code`,`Currency Code`,`Purchase Order Last Updated Date`,`Purchase Order Transaction State`,
					`Supplier Key`,`Agent Key`,`Purchase Order Key`,`Purchase Order Quantity`,`Purchase Order Net Amount`,`Purchase Order Tax Amount`,`Note to Supplier`,`CBM`,`Weight`,
					`User Key`,`Creation Date`
					)
					values (%d,%d,%s,%s,%s,%s,
					 %d,%s,%d,%.6f,%.2f,%.2f,%s,%s,%s,
					 %d,%s
					 )",
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



				$subtotals=money($amount,$this->get('Purchase Order Currency Code'));

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

			)
		);


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
			mysql_query( $sql );


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

		$sql = sprintf("select sum(`Weight`) as  weight,sum(`CBM` )as cbm ,count(Distinct `Supplier Part Key`) as num_items ,sum(`Purchase Order Net Amount`) as items_net, sum(`Purchase Order Tax Amount`) as tax,  sum(`Purchase Order Shipping Contribution Amount`) as shipping from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d" ,
			$this->id);
		//print "$sql\n";



		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {


				$total_net=$row['items_net']+$this->get('Purchase Order Shipping Net Amount')+$this->get('Purchase Order Charges Net Amount')+  $this->get('Purchase Order Net Credited Amount');
				$total_tax=$row['tax'];
				$total=$total_net+$total_tax;

				$this->update(array(
						'Purchase Order Items Net Amount'=>$row['items_net'],
						'Purchase Order Items Tax Amount'=>$row['tax'],
						'Purchase Order Items Number Items'=>$row['num_items'],
						'Purchase Order Weight'=>$row['weight'],
						'Purchase Order CBM'=>$row['cbm'],
						'Purchase Order Total Net Amount'=>$total_net,
						'Purchase Order Total Amount'=>$total
					), 'no_history');






			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}


	function update_totals_from_order_transactions_old($force_total=false) {


		if (!$force_total)
			$force_total=array();



		$this->data ['Purchase Order Total Tax Amount'] = $this->data ['Purchase Order Items Tax Amount'] + $this->data ['Purchase Order Shipping Tax Amount']+  $this->data ['Purchase Order Charges Tax Amount']+  $this->data ['Purchase Order Tax Credited Amount'];
		$this->data ['Purchase Order Total Net Amount']=$this->data ['Purchase Order Items Net Amount']+  $this->data ['Purchase Order Shipping Net Amount']+  $this->data ['Purchase Order Charges Net Amount']+  $this->data ['Purchase Order Net Credited Amount'];

		$this->data ['Purchase Order Total Amount'] = $this->data ['Purchase Order Total Tax Amount'] + $this->data ['Purchase Order Total Net Amount'];
		$this->data ['Purchase Order Total To Pay Amount'] = $this->data ['Purchase Order Total Amount'];
		$sql = sprintf( "update `Purchase Order Dimension` set `Purchase Order Total Net Amount`=%.2f ,`Purchase Order Total Tax Amount`=%.2f ,`Purchase Order Shipping Net Amount`=%.2f ,`Purchase Order Shipping Tax Amount`=%.2f ,`Purchase Order Charges Net Amount`=%.2f ,`Purchase Order Charges Tax Amount`=%.2f ,`Purchase Order Total Amount`=%.2f , `Purchase Order Total To Pay Amount`=%.2f  where  `Purchase Order Key`=%d "
			, $this->data ['Purchase Order Total Net Amount']
			, $this->data ['Purchase Order Total Tax Amount']
			, $this->data ['Purchase Order Shipping Net Amount']
			, $this->data ['Purchase Order Shipping Tax Amount']

			, $this->data ['Purchase Order Charges Net Amount']
			, $this->data ['Purchase Order Charges Tax Amount']

			, $this->data ['Purchase Order Total Amount']
			, $this->data ['Purchase Order Total To Pay Amount']
			, $this->data ['Purchase Order Key']
		);


		//exit;


		if (! mysql_query( $sql ))
			exit ( "$sql eroro2 con no update totals" );




	}



	function delete() {
		include_once 'class.Attachment.php';
		if ($this->data['Purchase Order State']=='In Process') {
			$sql=sprintf("delete from `Purchase Order Dimension` where `Purchase Order Key`=%d", $this->id);
			mysql_query($sql);
			$sql=sprintf("delete from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d", $this->id);
			mysql_query($sql);


			$sql=sprintf("select `History Key`,`Type` from `Purchase Order History Bridge` where `Purchase Order Key`=%d", $this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {

				if ($row['Type']=='Attachments') {
					$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='Purchase Order History Attachment' and `Subject Key`=%d",
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

				$sql=sprintf("delete from `Purchase Order History Bridge` where `History Key`=%d ", $row['History Key']);
				mysql_query($sql);

				$sql=sprintf("delete from `History Dimension` where `History Key`=%d", $row['History Key']);
				mysql_query($sql);

			}

			$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='Purchase Order' and `Subject Key`=%d",
				$this->id
			);
			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_assoc($res2)) {
				$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d", $row2['Attachment Bridge Key']);
				mysql_query($sql);
				$attachment=new Attachment($row2['Attachment Key']);
				$attachment->delete();
			}

			$supplier=new Supplier($this->data['Purchase Order Supplier Key']);
			$supplier->editor=$this->editor;
			$history_data=array(
				'History Abstract'=>_('Purchase order in process deleted'),
				'History Details'=>''
			);
			$supplier->add_subject_history($history_data);


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


		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s ,`Purchase Order Transaction State`='Confirmed'  where `Purchase Order Key`=%d",
			prepare_mysql($data['Purchase Order Confirmed Date']),
			$this->id);
		mysql_query($sql);

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


		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s ,`Purchase Order Transaction State`='Submitted'  where `Purchase Order Key`=%d",
			prepare_mysql($data['Purchase Order Submitted Date']),
			$this->id);
		mysql_query($sql);


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
		mysql_query($sql);

		$history_data=array(
			'History Abstract'=>sprintf(_('Purchase order associeted with delivery %s'), '<a href="supplier_dn.php?id='.$sdn_key.'">'.$sdn_name.'</a>'),
			'History Details'=>''
		);
		$this->add_subject_history($history_data);

	}


	function back_to_process() {




		$sql=sprintf("update `Purchase Order Dimension` set `Purchase Order Submitted Date`=NULL,`Purchase Order Confirmed Date`=NULL,`Purchase Order Main Source Type`=NULL ,`Purchase Order State`='In Process'   where `Purchase Order Key`=%d",

			$this->id);


		mysql_query($sql);

		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=NOW() ,`Purchase Order Transaction State`='In Process'  where `Purchase Order Key`=%d",
			$this->id);
		mysql_query($sql);


		$this->update_affected_products();

		$history_data=array(
			'History Abstract'=>_('Purchase order send back to process'),
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


	function update_state_old() {

		$cancelled=0;
		$in_process=0;
		$submitted=0;
		$in_delivery_note=0;
		$items=0;
		$deliver_note_keys=array();
		$sql=sprintf("select `Supplier Delivery Note Key`,`Supplier Invoice Key`,`Purchase Order Transaction State` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d   ", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Supplier Delivery Note Key']!=0  and $row['Purchase Order Transaction State']=='In Warehouse') {
				$deliver_note_keys[$row['Supplier Delivery Note Key']]=1;
			}
			$items++;
			if ($row['Purchase Order Transaction State']=='Cancelled')
				$cancelled++;
			if ($row['Purchase Order Transaction State']=='Submitted')
				$submitted++;
			if ($row['Purchase Order Transaction State']=='In Process')
				$in_process++;
			if ($row['Purchase Order Transaction State']=='In Warehouse')
				$in_delivery_note++;

		}
		//  print_r($deliver_note_keys);
		//   print "xx i:$items  c:$cancelled  d: $in_delivery_note kk: ".count($deliver_note_keys)." \n";


		if ($items==0 ) {
			$status='In Process';
			$xhtml_state='In Process';
		}if ($items==$cancelled) {
			$status='Cancelled';
			$xhtml_state=_('Cancelled');

		}elseif ($in_delivery_note==0 and $submitted==0  ) {
			$status='In Process';
			$xhtml_state=_('In Process');

		}elseif ($in_delivery_note==0  ) {
			$status='Submitted';
			$xhtml_state=_('Submitted');

		}else {
			//print "xxx  $in_delivery_note\n";

			if (count($deliver_note_keys)>0  and  $in_delivery_note>0) {
				if ($in_delivery_note==($items-$cancelled)) {
					$status='Matched With DN';
					$xhtml_state='';
					foreach ($deliver_note_keys as $dn_key) {
						$supplier_dn=new SupplierDeliveryNote($dn_key);
						if ($supplier_dn->id) {
							$xhtml_state.=sprintf(',<a href="supplier_dn.php?id=%d">%s</a>', $supplier_dn->id, $supplier_dn->data['Supplier Delivery Note Public ID']);
						}

					}
					$xhtml_state=preg_replace('/^\,/', _('Matched With DN')." ", $xhtml_state);

				}else {
					$status='Partially Matched With DN';
					$xhtml_state='';
					foreach ($deliver_note_keys as $dn_key) {
						$supplier_dn=new SupplierDeliveryNote($dn_key);
						if ($supplier_dn->id) {
							$xhtml_state.=sprintf(',<a href="supplier_dn.php?id=%d">%s</a>', $supplier_dn->id, $supplier_dn->data['Supplier Delivery Note Public ID']);
						}

					}
					$xhtml_state=preg_replace('/^\,/', _('Matched With DN')." ", $xhtml_state);

				}







			}else {
				$status='Submitted';
				$xhtml_state=_('Submitted')." (*)";


			}
		}
		//   print $status;
		$this->update(
			array(
				'Purchase Order State'=>$status,
				'Purchase Order Current XHTML Payment State'=>$xhtml_state
			)

		);



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
		mysql_query($sql);
		//print $sql;
		$sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s `Purchase Order Transaction State`='Cancelled'  where `Purchase Order Key`=%d"
			, prepare_mysql($data['Purchase Order Cancelled Date'])
			, $this->id
		);
		mysql_query($sql);

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
		case 'Purchase Order Estimated Receiving Date':
			$this->update_estimated_receiving_date($value);
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


	function get_estimated_delivery_date() {
		if ($this->data['Purchase Order Estimated Receiving Date']) {
			return strftime("%d-%m-%Y", strtotime($this->data['Purchase Order Estimated Receiving Date']));
		}else {

			if ($this->data['Purchase Order State']=='In Process') {
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



	function get_formatted_estimated_delivery_date() {

		if ($this->data['Purchase Order Estimated Receiving Date']) {
			return strftime("%e %b %Y", strtotime($this->get('Estimated Receiving Date')));
		}else {

			if ($this->data['Purchase Order State']=='In Process') {
				$parent=get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);
				if ($parent->get($parent->table_name.' Delivery Days') and is_numeric($parent->get($parent->table_name.' Delivery Days'))) {
					return '<span class="from_supplier_delivery_days">'.strftime("%d-%m-%Y", strtotime('now +'.$parent->get($parent->table_name.' Delivery Days').' days')).'</span>';

				}else {
					return _('Unknown');
				}
			}else {

				return _('Unknown');
			}

		}
	}


	function get_sdn_keys() {

		$sdns_keys=array();
		$sql=sprintf("select `Supplier Delivery Note Key` from `Purchase Order SDN Bridge` where `Purchase Order Key`=%d ",
			$this->id);
		$res = mysql_query( $sql );
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Supplier Delivery Note Key']) {
				$sdns_keys[$row['Supplier Delivery Note Key']]=$row['Supplier Delivery Note Key'];
			}
		}
		return $sdns_keys;

	}


	function get_number_sdn() {

		return count($this->get_sdn_keys());
	}


	function get_sdn_objects() {

		include_once 'class.SupplierDeliveryNote.php';

		$sdns=array();
		$sdns_keys=$this->get_sdn_keys();
		foreach ($sdns_keys as $sdns_key) {
			$sdns[$sdns_key]=new SupplierDeliveryNote($sdns_key);
		}
		return $sdns;
	}


	function get_invoices_objects() {

	}


	function get_field_label($field) {
		global $account;

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
