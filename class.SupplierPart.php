<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 10:49:10 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';
include_once 'class.Part.php';

class SupplierPart extends DB_Table{


	function SupplierPart($a1, $a2=false, $a3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Supplier Part';
		$this->ignore_fields=array('Supplier Part Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		}elseif ($a1=='find') {
			$this->find($a2, $a3);

		}else
			$this->get_data($a1, $a2);
	}


	function get_data($key, $tag) {

		if ($key=='id')
			$sql=sprintf("select * from `Supplier Part Dimension` where `Supplier Part Key`=%d", $tag);
		else
			return;

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Supplier Part Key'];

			$this->part=new Part($this->data['Supplier Part Part SKU']);
		}



	}


	function get_supplier_data() {

		$sql=sprintf('select * from `Supplier Dimension` where `Supplier Key`=%d ', $this->get('Supplier Part Supplier Key'));
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data[$key]=$value;
			}
		}



	}


	function get_historic_data($key) {

		$sql=sprintf('select * from `Supplier Part Historic Dimension` where `Supplier Part Historic Dimension`=%d ',
			$key
		);
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data[$key]=$value;
			}
		}



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


		if (!$data['Supplier Part Part SKU']) {

			$sql=sprintf("select `Part SKU` from `Part Dimension` where `Part Reference`=%s", prepare_mysql($raw_data['Part Reference']));


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$this->found=true;
					$this->found_key=$row['Part SKU'];
					$this->duplicated_field='Part Reference';
					return;
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


		}


		if ($data['Supplier Part Status']!='Discontinued') {
			$sql=sprintf("select `Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d and `Supplier Part Part SKU`=%d and `Supplier Part Status`!='Discontinued' ",
				$data['Supplier Part Supplier Key'],
				$data['Supplier Part Part SKU']
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$this->found=true;
					$this->found_key=$row['Supplier Part Key'];
					$this->get_data('id', $this->found_key);
					$this->duplicated_field='Available Supplier Part';
					return;
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}

		}

		$sql=sprintf("select `Supplier Part Key` from `Supplier Part Dimension` where  `Supplier Part Supplier Key`=%d and `Supplier Part Reference`=%s  ",
			$data['Supplier Part Supplier Key'],
			prepare_mysql($data['Supplier Part Reference'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Supplier Part Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Supplier Part Reference';
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($create and !$this->found) {
			$this->create($data);
			return;
		}




	}


	function create($data) {
		$this->new=false;
		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data))
				$base_data[$key]=_trim($value);
		}


		if ($base_data['Supplier Part From']=='') {
			$base_data['Supplier Part From']=gmdate('Y-m-d H:i:s');
		}



		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			//   if (preg_match('/^(Supplier Part Address|Supplier Part Company Name|Supplier Part Company Number|Supplier Part VAT Number|Supplier Part Telephone|Supplier Part Email)$/i', $key))
			//    $values.=prepare_mysql($value, false).",";
			//   else
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Supplier Part Dimension` %s %s", $keys, $values);


		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->msg="Supplier part added";
			$this->get_data('id', $this->id);
			$this->update_historic_object();
			$this->new=true;

			$history_data=array(
				'Action'=>'created',
				'History Abstract'=>_("Supplier's part created"),
				'History Details'=>''
			);
			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

            


			return;
		}else {
			$this->msg=_(" Error can not create supplier part");
			print $sql;
			exit;
		}
	}




	function get($key, $data=false) {

		if (!$this->id) {
			return '';
		}



		switch ($key) {

		case 'Supplier Key':


			return $this->get('Supplier Name').(($this->get('Supplier Code')!='' and $this->get('Supplier Code')!=$this->get('Supplier Name'))?' ('.$this->get('Supplier Code').')':'');


			break;
		case 'Average Delivery Days':
			if ($this->data['Supplier Part Average Delivery Days']=='')return '';
			return sprintf("%d %s", $this->data['Supplier Part Average Delivery Days'], ngettext("day", "days", $this->data['Supplier Part Average Delivery Days']));

			break;
		case 'Average Delivery':

			if ($this->data['Supplier Part Status']!='Discontinued' and  $this->data['Supplier Part Average Delivery Days']!='') {
				include_once 'utils/natural_language.php';
				return '<i class="fa fa-hourglass-end fa-fw" aria-hidden="true" title="'._('Delivery time').'" ></i>  <span title="'.sprintf("%s %s", number($this->data['Supplier Part Average Delivery Days'], 1) , ngettext("day", "days", number($this->data['Supplier Part Average Delivery Days'], 1))).'">'.seconds_to_natural_string($this->data['Supplier Part Average Delivery Days']*86400, true).'</span>';
			}
			break;

		case 'Minimum Carton Order':

			if ($this->data['Supplier Part Minimum Carton Order']=='')return '';
			return number($this->data['Supplier Part Minimum Carton Order']).' '._('cartons');
			break;

		case 'Carton CBM':
			if ($this->data['Supplier Part Carton CBM']=='')return '';
			return number($this->data['Supplier Part Carton CBM']).' m³';
			break;
		case 'Packages Per Carton':
			if ($this->data['Supplier Part Packages Per Carton']=='')return '';
			$value=number($this->data['Supplier Part Packages Per Carton']);
			if ($this->data['Supplier Part Units Per Package']!=1 and is_numeric($this->data['Supplier Part Units Per Package'])) {
				$value.=' <span class="very_discret">('.number($this->data['Supplier Part Packages Per Carton']*$this->data['Supplier Part Units Per Package']).' '._('units').')</span>';
			}

			return $value;
			break;

		case 'Unit Cost':

			if ($this->data['Supplier Part Unit Cost']=='')return '';

			$cost= money($this->data['Supplier Part Unit Cost'], $this->data['Supplier Part Currency Code']).' '._('per unit');


			$cost_other_info='';
			if ($this->data['Supplier Part Units Per Package']!=1 and  is_numeric($this->data['Supplier Part Units Per Package'])) {
				$cost_other_info=money($this->data['Supplier Part Unit Cost']*$this->data['Supplier Part Units Per Package'], $this->data['Supplier Part Currency Code']).' '._('per outer');
			}
			if ($this->data['Supplier Part Packages Per Carton']!=1 and  is_numeric($this->data['Supplier Part Packages Per Carton'])) {
				$cost_other_info.=', '.money($this->data['Supplier Part Unit Cost']*$this->data['Supplier Part Units Per Package']*$this->data['Supplier Part Packages Per Carton'], $this->data['Supplier Part Currency Code']).' '._('per carton');
			}

			$cost_other_info=preg_replace('/^, /', '', $cost_other_info);
			if ($cost_other_info!='') {
				$cost.=' <span class="very_discret">('.$cost_other_info.')</span>';
			}


			return $cost;
			break;
		case 'Status':

			switch ($this->data['Supplier Part Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-stop success" ></i> %s', _('Available'));
				break;
			case 'NoAvailable':
				$status=sprintf('<i class="fa fa-stop warning" ></i> %s', _('No available'));

				break;
			case 'Discontinued':
				$status=sprintf('<i class="fa fa-ban error" ></i> %s', _('Discontinued'));

				break;
			default:
				$status=$this->data['Supplier Part Status'];
				break;
			}

			return $status;
			break;
		default:
			if (preg_match('/^Part /', $key)) {


				return $this->part->get(preg_replace('/^Part /', '', $key));

			}

			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Supplier Part '.$key, $this->data))
				return $this->data['Supplier Part '.$key];
		}
		return '';
	}


	function update_field_switcher($field, $value, $options='', $metadata='') {



		switch ($field) {
		case 'Supplier Part Currency Code':
		case 'Supplier Part Reference':
		case 'Supplier Part Unit Cost':
			$this->update_field($field, $value, $options);
			if (!preg_match('/skip_update_historic_object/', $options)) {
				$this->update_historic_object();
			}
			break;
		
			
		case 'Supplier Part Carton CBM':

			$this->update_field($field, $value, $options);
			if (!preg_match('/skip_update_historic_object/', $options)) {
				$this->update_historic_object();
			}
			if ($value!='') {
				$purchase_order_keys=array();
				$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Quantity` from `Purchase Order Transaction Fact` where `Supplier Part Key`=%d  and `Purchase Order CBM` is NULL and `Purchase Order Transaction State` in ('InProcess','Submitted')  ",
				$this->id
				);
				//print $sql;
				if ($result=$this->db->query($sql)) {
					foreach ($result as $row) {
						$purchase_order_keys[$row['Purchase Order Key']]=$row['Purchase Order Key'];
						$sql=sprintf('update `Purchase Order Transaction Fact` set  `Purchase Order CBM`=%f where `Purchase Order Transaction Fact Key`=%d',
						$row['Purchase Order Quantity']*$this->get('Supplier Part Carton CBM'),
						$row['Purchase Order Transaction Fact Key']
						);
						$this->db->exec($sql);
					}
					include_once('class.PurchaseOrder.php');
					foreach($purchase_order_keys as $purchase_order_key){
					    $purchase_order=new PurchaseOrder($purchase_order_key);
					    $purchase_order->update_totals();
					}
					
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}
			}

			break;

		case 'Supplier Part Units Per Package':
			$this->update_field($field, $value, $options);
			$this->other_fields_updated=array(
				'Supplier_Part_Cost'=>array(
					'field'=>'Supplier_Part_Cost',
					'render'=>true,
					'value'=>$this->get('Supplier Part Cost'),
					'formatted_value'=>$this->get('Cost'),
				),
				'Supplier_Part_Packages_Per_Carton'=>array(
					'field'=>'Supplier_Part_Packages_Per_Carton',
					'render'=>true,
					'value'=>$this->get('Supplier Part Packages Per Carton'),
					'formatted_value'=>$this->get('Packages Per Carton'),
				)
			);
			$this->update_field($field, $value, $options);
			if (!preg_match('/skip_update_historic_object/', $options)) {
				$this->update_historic_object();
			}
			break;
		case 'Supplier Part Packages Per Carton':
			$this->update_field($field, $value, $options);
			$this->other_fields_updated=array(
				'Supplier_Part_Cost'=>array(
					'field'=>'Supplier_Part_Cost',
					'render'=>true,
					'value'=>$this->get('Supplier Part Cost'),
					'formatted_value'=>$this->get('Cost'),
				)

			);
			$this->update_field($field, $value, $options);
			if (!preg_match('/skip_update_historic_object/', $options)) {
				$this->update_historic_object();
			}
			break;
		default:


			if (preg_match('/^Part /', $field)) {

				//$field=preg_replace('/^Part /', '', $field);
				$this->part->update(array($field=>$value), $options);
				$this->updated=$this->part->updated;
				$this->msg=$this->part->msg;
				$this->error=$this->part->error;

			}else {

				$base_data=$this->base_data();

				if (array_key_exists($field, $base_data)) {

					if ($value!=$this->data[$field]) {


						$this->update_field($field, $value, $options);




					}
				}


			}

		}


	}


	function update_historic_object() {

		if (!$this->id)return;

		$metadata=json_encode(array(
				'u'=>$this->data['Supplier Part Units Per Package'],
				'p'=>$this->data['Supplier Part Packages Per Carton'],
				'cbm'=>$this->data['Supplier Part Carton CBM'],
				'cur'=>$this->data['Supplier Part Currency Code']

			));

		$sql=sprintf('select `Supplier Part Historic Key` from `Supplier Part Historic Dimension` where `Supplier Part Historic Supplier Part Key`=%d and `Supplier Part Historic Reference`=%s and `Supplier Part Historic Unit Cost`=%f and `Supplier Part Historic Metadata`=%s',
			$this->id,
			prepare_mysql($this->data['Supplier Part Reference']),
			$this->data['Supplier Part Unit Cost'],
			prepare_mysql($metadata)


		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->update(array('Supplier Part Historic Key'=>$row['Supplier Part Historic Key']), 'no_history');

			}else {
				$sql=sprintf('insert into `Supplier Part Historic Dimension` (`Supplier Part Historic Supplier Part Key`,`Supplier Part Historic Reference`,`Supplier Part Historic Unit Cost`,`Supplier Part Historic Metadata`) values (%d,%s,%f,%s) ',
					$this->id,
					prepare_mysql($this->data['Supplier Part Reference']),
					$this->data['Supplier Part Unit Cost'],
					prepare_mysql($metadata)
				);
				if ($this->db->exec($sql)) {
					$this->update(array('Supplier Part Historic Key'=>$this->db->lastInsertId()), 'no_history');

				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			print $sql;
			exit;
		}



	}


	function delete($metadata=false) {




		$sql=sprintf('insert into `Supplier Part Deleted Dimension`  (`Supplier Part Deleted Key`,`Supplier Part Deleted Reference`,`Supplier Part Deleted From`,`Supplier Part Deleted To`,`Supplier Part Metadata`) values (%d,%s,%s,%s,%s) ',
			$this->id,
			prepare_mysql($this->get('Supplier Part Reference')),
			prepare_mysql($this->get('Supplier Part From')),
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql(gzcompress(json_encode($this->data), 9))

		);

		//print "$sql\n";

		$this->db->exec($sql);



		$sql=sprintf('delete from `Supplier Part Dimension`  where `Supplier Part Key`=%d ',
			$this->id
		);
		$this->db->exec($sql);
		//print "$sql\n";

		$history_data=array(
			'History Abstract'=>sprintf(_("Supplier's part record %s deleted"), $this->data['Supplier Part Reference']),
			'History Details'=>'',
			'Action'=>'deleted'
		);

		$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());


		$this->deleted=true;
	}


	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Supplier Part Reference':
			$label=_("supplier's SKU");
			break;
		case 'Supplier Part Cost':
			$label=_('unit cost');
			break;
		case 'Supplier Part Batch':
			$label=_('batch');
			break;
		case 'Supplier Part Status':
			$label=_('availability');
			break;
		case 'Supplier Part Minimum Carton Order':
			$label=_("minimum order");
			break;
		case 'Supplier Part Units Per Package':
			$label=_("units per outer (SKO)");
			break;
		case 'Supplier Part Packages Per Carton':
			$label=_("outers (SKO) per carton");
			break;
		case 'Supplier Part Carton CBM':
			$label=_("carton CBM");
			break;
		case 'Supplier Part Average Delivery Days':
			$label=_("average delivery time");
			break;
		case 'Supplier Part Unit Cost':
			$label=_("unit cost");
			break;
		case 'Supplier Part Unit Extra Cost':
			$label=_("unit extra costs");
			break;
		case 'Part Reference':
			$label=_("part reference");
			break;
		case 'Part Barcode Number':
			$label=_("part barcode");
			break;
		case 'Part Unit Price':
			$label=_("Unit price");
			break;
		case 'Part Unit RRP':
			$label=_("unit RRP");
			break;
		default:
			$label=$field;

		}

		return $label;

	}

    function update_next_supplier_shippment(){
        
    }


}


?>
