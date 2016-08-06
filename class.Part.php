<?php
/*
 File: Part.php

 This file contains the Part Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'class.Asset.php';

class Part extends Asset{



	private $current_locations_loaded=false;
	public $sku=false;
	public $warehouse_key=1;
	public $locale='en_GB';

	function __construct($arg1, $arg2=false, $arg3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Part';
		$this->ignore_fields=array(
			'Part Key'
		);

		if (is_numeric($arg1) and !$arg2) {
			$this->get_data('id', $arg1);
			return;
		}


		if (preg_match('/^find/i', $arg1)) {

			$this->find($arg2, $arg3);
			return;
		}

		if (preg_match('/^create/i', $arg1)) {
			$this->create($arg2);
			return;
		}


		$this->get_data($arg1, $arg2);

	}




	function get_data($tipo, $tag) {
		if ($tipo=='id' or $tipo=='sku')
			$sql=sprintf("select * from `Part Dimension` where `Part SKU`=%d ", $tag);
		else if ($tipo=='code' or $tipo=='reference')
			$sql=sprintf("select * from `Part Dimension` where `Part Reference`=%s ", prepare_mysql($tag));
		else
			return;

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Part SKU'];
			$this->sku=$this->data['Part SKU'];
		}



	}


	function get_supplier_parts($scope='keys') {

		include_once 'class.SupplierPart.php';

		if ($scope=='objects') {
			include_once 'class.Part.php';
		}

		$sql=sprintf('select `Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Part SKU`=%d ', $this->id);

		$supplier_parts=array();

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if ($scope=='objects') {
					$supplier_parts[$row['Supplier Part Key']]=new SupplierPart($row['Supplier Part Key']);
				}else {
					$supplier_parts[$row['Supplier Part Key']]=$row['Supplier Part Key'];
				}


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $supplier_parts;
	}


	function load_acc_data() {
		$sql=sprintf("select * from `Part Data` where `Part SKU`=%d", $this->id);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				foreach ($row as $key=>$value) {
					$this->data[$key]=$value;
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


	function find($raw_data, $options) {



		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}


		$create='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}


		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data)) {
				$data[$key]=_trim($value);
			}
		}



		$sql=sprintf("select `Part SKU` from `Part Dimension` where `Part Reference`=%s", prepare_mysql($data['Part Reference']));


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->found=true;
				$this->found_key=$row['Part SKU'];
				$this->get_data('id', $this->found_key);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($create and !$this->found) {




			$this->create($raw_data);

		}



	}


	function create($data) {

		include_once 'class.Category.php';

		// print_r($data);
		global $account;

		if (array_key_exists('Part Family Category Code', $data)) {

			$root_category=new Category($account->get('Account Part Family Category Key'));
			if ($root_category->id) {
				$root_category->editor=$this->editor;
				$family=$root_category->create_category(array('Category Code'=>$data['Part Family Category Code']));
				if ($family->id) {
					$data['Part Family Category Key']=$family->id;
				}
			}
		}

		if (!isset($data['Part Valid From']) or $data['Part Valid From']=='') {
			$data['Part Valid From']=gmdate('Y-m-d H:i:s');
		}
		$base_data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data)) {
				$base_data[$key]=_trim($value);
			}
		}


		//   $base_data['Part Available']='No';

		//  if ($base_data['Part XHTML Description']=='') {
		//   $base_data['Part XHTML Description']=strip_tags($base_data['Part XHTML Description']);
		//  }

		//print_r($base_data);

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";

			if (in_array($key, array('Part XHTML Next Supplier Shipment', 'Part XHTML Picking Location'))) {
				$values.=prepare_mysql($value, false).",";

			}else {

				$values.=prepare_mysql($value).",";
			}
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);

		//print_r($base_data);

		$sql=sprintf("insert into `Part Dimension` %s %s", $keys, $values);


		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->sku =$this->id ;
			$this->new=true;

			$sql="insert into `Part Data` (`Part SKU`) values(".$this->id.");";
			$this->db->exec($sql);


			$this->get_data('id', $this->id);
			$history_data=array(
				'Action'=>'created',
				'History Abstract'=>_('Part created'),
				'History Details'=>''
			);
			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			$this->update_main_state();


			if ($this->get('Part Family Category Key')) {
				$family=new Category($this->get('Part Family Category Key'));
				$family->editor=$this->editor;


				if ($family->id) {
					$family->associate_subject($this->id);
				}
			}


		} else {
			print "Error Part can not be created $sql\n";
			$this->msg='Error Part can not be created';
			exit;
		}

	}


	function update_custom_fields($id, $value) {
		$this->update(array($id=>$value));
	}


	function update_main_state() {
		if ($this->data['Part Status']=='In Use') {

			if ($this->data['Part Available']=='Yes') {
				$this->data['Part Main State']='Keeping';
			} else {
				$this->data['Part Main State']='LastStock';
			}

		}
		else {
			if ($this->data['Part Available']=='Yes') {
				$this->data['Part Main State']='NotKeeping';
			} else {
				$this->data['Part Main State']='Discontinued';
			}
		}

		$sql=sprintf("update `Part Dimension`  set `Part Main State`=%s where  `Part SKU`=%d   "
			, prepare_mysql($this->data['Part Main State'])
			, $this->id
		);
		$this->db->exec($sql);

	}


	function update_status($value, $options='') {

		$this->update_field('Part Status', $value, $options);

		if ($value=='In Use') {


		} elseif ($value=='Not In Use') {

			$locations=$this->get_location_keys();



			foreach ($locations as $location_key) {
				$part_location=new PartLocation($this->sku.'_'.$location_key);

				$part_location->disassociate();

			}

			$this->update_stock();

			$this->data['Part Valid To']=gmdate("Y-m-d H:i:s");
			$sql=sprintf("update `Part Dimension` set `Part Valid To`=%s where `Part SKU`=%d", prepare_mysql($this->data['Part Valid To']), $this->sku);
			$this->db->exec($sql);

			$this->get_data('sku', $this->sku);
			$this->update_availability_for_products_configuration('Automatic', $options);





		}
		$this->update_main_state();


		$sql=sprintf("select `Category Key` from `Category Bridge` where `Subject`='Part' and `Subject Key`=%d", $this->sku);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$category=new Category($row['Category Key']);
				$category->update_part_category_status();
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$products=$this->get_product_ids();
		foreach ($products as $product_pid) {
			$product=new Product ('pid', $product_pid);
			$product->update_availability_type();

		}


	}


	function update_availability_for_products_configuration($value, $options) {

		$this->update_field('Part Available for Products Configuration', $value, $options);
		$new_value=$this->new_value;
		$updated=$this->updated;

		if (preg_match('/dont_update_pages/', $options)) {
			$update_products=false;
		}else {
			$update_products=true;
		}

		$this->update_availability_for_products($update_products);
		$this->new_value=$new_value;
		$this->updated=$updated;

	}



	function update_availability_for_products($update_pages=true) {

		switch ($this->data['Part Available for Products Configuration']) {
		case 'Yes':
		case 'No':
			$this->update_field('Part Available for Products', $this->data['Part Available for Products Configuration']);
			break;
		case 'Automatic':
			if ($this->data['Part Current Stock']>0 and $this->data['Part Status']=='In Use') {
				$this->update_field('Part Available for Products', 'Yes');
			}else {
				$this->update_field('Part Available for Products', 'No');
			}

		}



		if ($this->updated) {


			if (isset($this->editor['User Key'])and is_numeric($this->editor['User Key'])  )
				$user_key=$this->editor['User Key'];
			else
				$user_key=0;

			$sql=sprintf("select UNIX_TIMESTAMP(`Date`) as date,`Part Availability for Products Key` from `Part Availability for Products Timeline` where `Part SKU`=%d and `Warehouse Key`=%d  order by `Date` desc ,`Part Availability for Products Key` desc limit 1",
				$this->sku,
				$this->warehouse_key
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$last_record_key=$row['Part Availability for Products Key'];
				$last_record_date=$row['date'];
			}else {
				$last_record_key=false;
				$last_record_date=false;
			}

			$new_date_formatted=gmdate('Y-m-d H:i:s');
			$new_date=gmdate('U');

			$sql=sprintf("insert into `Part Availability for Products Timeline`  (`Part SKU`,`User Key`,`Warehouse Key`,`Date`,`Availability for Products`) values (%d,%d,%d,%s,%s) ",
				$this->sku,
				$user_key,
				$this->warehouse_key,
				prepare_mysql($new_date_formatted),
				prepare_mysql($this->data['Part Available for Products'])

			);
			$this->db->exec($sql);

			if ($last_record_key) {
				$sql=sprintf("update `Part Availability for Products Timeline` set `Duration`=%d where `Part Availability for Products Key`=%d",
					$new_date-$last_record_date,
					$last_record_key

				);
				$this->db->exec($sql);

			}


			$products=$this->get_current_products_objects();
			foreach ($products as $product) {
				$product->editor=$this->editor;
				$product->update_web_state($update_pages);

			}

		}

	}


	function update_linked_products($field, $value, $options, $metadata) {



		foreach ($this->get_products_data() as $product_data) {
			if ($field=='Part Package Weight') {
				$value=$value*$product_data['Parts Per Product'];
			}
			if (array_key_exists($field, $product_data['Linked Fields'])) {
				$product=new Product($product_data['Store Product Key']);
				$update_data=array();


				$update_data[$product_data['Linked Fields'][$field]]=$value;

				$product->update($update_data);
			}
		}

	}


	function update_field_switcher($field, $value, $options='', $metadata='') {

		if ($this->update_asset_field_switcher($field, $value, $options, $metadata)) {
			return;
		}

		switch ($field) {

		case 'Part Units Per Package':
			$this->update_field('Part Units Per Package', $value, $options);

			$supplier_parts=$this->get_supplier_parts('objects');
			//$supplier_parts->update(array('Part Units Per Package'=>$value));
			break;
		case 'Part Family Category Key';
			global $account;
			include_once 'class.Category.php';


			$category=$this->get('Family');


			if ($value!='') {


				$category=new Category($value);
				if ($category->id and $category->get('Category Root Key')==$account->get('Account Part Family Category Key') ) {
					$category->associate_subject($this->id);
					$this->update_field($field, $value, $options);



				}else {
					$this->error=true;
					$this->msg='wrong category';

				}

				$this->other_fields_updated=array(
					'Part_Family_Code'=>array(
						'field'=>'Part_Family_Code',
						'value'=>$category->get('Code'),
						'formatted_value'=>$category->get('Code'),


					),
					'Part_Family_Label'=>array(
						'field'=>'Part_Family_Label',
						'value'=>$category->get('Label'),
						'formatted_value'=>$category->get('Label'),


					),
					'Part_Family_Key'=>array(
						'field'=>'Part_Family_Key',
						'value'=>$category->id,
						'formatted_value'=>$category->id,


					)
				);

			}elseif ($value=='' and $category) {


				$category->disassociate_subject($this->id);

				$this->other_fields_updated=array(
					'Part_Family_Code'=>array(
						'field'=>'Part_Family_Code',
						'value'=>'',
						'formatted_value'=>'',


					),
					'Part_Family_Label'=>array(
						'field'=>'Part_Family_Label',
						'value'=>'',
						'formatted_value'=>'<span class="italic discreet">'._('Not set').'</span>',


					),
					'Part_Family_Key'=>array(
						'field'=>'Part_Family_Key',
						'value'=>'',
						'formatted_value'=>'',


					)
				);

			}


			else {
				return;
			}

			$this->update_field('Part Family Category Key', $value, 'no_history');



			break;
		case 'Part Materials':
			include_once 'utils/parse_materials.php';


			$materials_to_update=array();
			$sql=sprintf('select `Material Key` from `Part Material Bridge` where `Part SKU`=%d', $this->id);
			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$materials_to_update[$row['Material Key']]=true;
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


			if ($value=='') {
				$materials='';




				$sql=sprintf("delete from `Part Material Bridge` where `Part SKU`=%d ", $this->sku);
				$this->db->exec($sql);

			}else {

				$materials_data=parse_materials($value, $this->editor);

				$sql=sprintf("delete from `Part Material Bridge` where `Part SKU`=%d ", $this->sku);

				$this->db->exec($sql);

				foreach ($materials_data as $material_data) {

					if ($material_data['id']>0) {
						$sql=sprintf("insert into `Part Material Bridge` (`Part SKU`, `Material Key`, `Ratio`, `May Contain`) values (%d, %d, %s, %s) ",
							$this->sku,
							$material_data['id'],
							prepare_mysql($material_data['ratio']),
							prepare_mysql($material_data['may_contain'])

						);
						$this->db->exec($sql);

						if (isset($materials_to_update[$material_data['id']])) {
							$materials_to_update[$material_data['id']]=false;
						}else {
							$materials_to_update[$material_data['id']]=true;
						}

					}


				}


				$materials=json_encode($materials_data);
			}


			foreach ($materials_to_update as  $material_key=>$update) {
				if ($update) {
					$material=new Material($material_key);
					$material->update_stats();

				}
			}


			$this->update_field('Part Materials', $materials, $options);
			$this->update_linked_products($field, $value, $options, $metadata);

			break;

		case 'Part Package Dimensions':
		case 'Part Unit Dimensions':

			$tag=preg_replace('/ Dimensions$/', '', $field);

			if ($value=='') {
				$dim='';
				$vol='';
			}else {
				$dim=parse_dimensions($value);
				if ($dim=='') {
					$this->error=true;
					$this->msg=_("Dimensions can't be parsed");
					return;
				}
				$_tmp=json_decode($dim, true);
				$vol=$_tmp['vol'];
			}

			$this->update_field($tag.' Dimensions', $dim, $options);
			$this->update_field($tag.' Volume', $vol,  'no_history');
			$this->update_linked_products($field, $value, $options, $metadata);


			break;
		case 'Part Package Weight':
		case 'Part Unit Weight':


			$tag=preg_replace('/ Weight$/', '', $field);
			$tag2=preg_replace('/^Part /', '', $tag);
			$tag3=preg_replace('/ /', '_', $tag);

			$this->update_field($field, $value, $options);

			$this->other_fields_updated=array(
				$tag3.'_Dimensions'=>array(
					'field'=>$tag3.'_Dimensions',
					'render'=>true,
					'value'=>$this->get($tag.' Dimensions'),
					'formatted_value'=>$this->get($tag2.' Dimensions'),


				)
			);
			$this->update_linked_products($field, $value, $options, $metadata);


			if ($field=='Part Package Weight') {

				if ($value!='') {
					$purchase_order_keys=array();
					$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Quantity`,`Supplier Part Packages Per Carton` from `Purchase Order Transaction Fact` POTF left join `Supplier Part Dimension` S on (POTF.`Supplier Part Key`=S.`Supplier Part Key`)  where `Supplier Part Part SKU`=%d  and `Purchase Order Weight` is NULL and `Purchase Order Transaction State` in ('InProcess','Submitted')  ",
						$this->id
					);
					//print $sql;
					if ($result=$this->db->query($sql)) {
						foreach ($result as $row) {
							$purchase_order_keys[$row['Purchase Order Key']]=$row['Purchase Order Key'];
							$sql=sprintf('update `Purchase Order Transaction Fact` set  `Purchase Order Weight`=%f where `Purchase Order Transaction Fact Key`=%d',
								$this->get('Part Package Weight')*$row['Supplier Part Packages Per Carton']*$row['Purchase Order Quantity'],
								$row['Purchase Order Transaction Fact Key']
							);
							$this->db->exec($sql);
						}
						include_once 'class.PurchaseOrder.php';
						foreach ($purchase_order_keys as $purchase_order_key) {
							$purchase_order=new PurchaseOrder($purchase_order_key);
							$purchase_order->update_totals();
						}

					}else {
						print_r($error_info=$this->db->errorInfo());
						exit;
					}
				}

			}


			break;
		case('Part Tariff Code'):

			if ($value=='') {
				$tariff_code_valid='';
			}else {
				include_once 'utils/validate_tariff_code.php';
				$tariff_code_valid=validate_tariff_code($value, $this->db);
			}

			$this->update_field($field, $value, $options);
			$this->update_field('Part Tariff Code Valid', $tariff_code_valid, 'no_history');
			$this->update_linked_products($field, $value, $options, $metadata);

			break;
		case 'Part UN Number':
		case 'Part UN Class':
		case 'Part Packing Group':
		case 'Part Proper Shipping Name':
		case 'Part Hazard Indentification Number':
		case('Part Duty Rate'):
			$this->update_field($field, $value, $options);
			$this->update_linked_products($field, $value, $options, $metadata);
			break;
		case('Part Status'):
			$this->update_status($value, $options);
			break;
		case('Part Available for Products Configuration'):
			$this->update_availability_for_products_configuration($value, $options);
			break;
			/*
		case('Part Tariff Code'):
		case('Part Duty Rate'):
		case 'Part UN Number':
		case 'Part UN Class':
		case 'Part Health And Safety':
		case 'Part Packing Group':
		case 'Part Proper Shipping Name':
		case 'Part Hazard Indentification Number':
		case 'Part Unit Dimensions Type':
		case 'Part Unit Dimensions Display Units':
		case 'Part Unit Dimensions Width Display':
		case 'Part Unit Dimensions Depth Display':
		case 'Part Unit Dimensions Length Display':
		case 'Part Unit Dimensions Diameter Display':
		case 'Part Package Dimensions Type':
		case 'Part Package Dimensions Display Units':
		case 'Part Package Dimensions Width Display':
		case 'Part Package Dimensions Depth Display':
		case 'Part Package Dimensions Length Display':
		case 'Part Package Dimensions Diameter Display':
		case 'Part Unit Weight Display':
		case 'Part Unit Weight Display Units':
		case 'Part Package Weight Display':
		case 'Part Package Weight Display Units':
		case 'Part Unit Materials':
		case 'Part Origin Country Code':

			$this->update_fields_used_in_products($field, $value, $options);
			break;
		*/

		case 'Part Next Set Supplier Shipment':
			$this->update_set_next_supplier_shipment($value, $options);
			break;
		default:
			$base_data=$this->base_data();

			if (array_key_exists($field, $base_data)) {

				if ($value!=$this->data[$field]) {

					if ($field=='Part General Description' or $field=='Part Health And Safety')
						$options.=' nohistory';
					$this->update_field($field, $value, $options);




				}
			}
			elseif (preg_match('/^custom_field_part/i', $field)) {
				$this->update_field($field, $value, $options);
			}

		}




	}


	function update_cost() {

		$supplier_parts=get_supplier_parts('objects');

		$cost_available=false;
		$cost_no_available=false;
		$cost_discontinued=false;
		foreach ($supplier_parts as $supplier_part) {
			if ($supplier_part->get('Supplier Part Status')) {

				if ($cost_available==false or $cost_available>$supplier_part->get('Supplier Part Unit Cost')) {
					$cost_available=$supplier_part->get('Supplier Part Unit Cost');
				}elseif ($cost_no_available==false or $cost_no_available>$supplier_part->get('Supplier Part Unit Cost')) {
					$cost_no_available=$supplier_part->get('Supplier Part Unit Cost');
				}elseif ($cost_discontinued==false or $cost_discontinued>$supplier_part->get('Supplier Part Unit Cost')) {
					$cost_discontinued=$supplier_part->get('Supplier Part Unit Cost');
				}


			}

		}

		$cost='';
		if ($cost_available!=false) {
			$cost=$cost_available;
		}

		if ($cost==false and $cost_no_available!=false) {
			$cost=$cost_no_available;
		}

		if ($cost==false and $cost_no_available!=false) {
			$cost=$cost_no_available;
		}

		if ($cost!=false) {
			$cost=$code*$this->data['Part Units Per Package'];
		}

		$this->update_field('Part Cost');


	}


	function update_weight_dimensions_data($field, $value, $type) {

		include_once 'utils/units_functions.php';

		//print "$field $value |";

		$this->update_field($field, $value);
		$_new_value=$this->new_value;
		$_updated=$this->updated;

		$this->updated=true;
		$this->new_value=$value;
		if ($this->updated) {

			if (preg_match('/Package/i', $field)) {
				$tag='Package';
			}else {
				$tag='Unit';
			}
			if ($field!='Part '.$tag.' '.$type.' Display Units') {
				$value_in_standard_units=convert_units($value, $this->data['Part '.$tag.' '.$type.' Display Units'], ($type=='Dimensions'?'m':'Kg'));



				$this->update_field(preg_replace('/\sDisplay$/', '', $field), $value_in_standard_units, 'nohistory');
			}elseif ($field=='Part '.$tag.' Dimensions Display Units') {

				$width_in_standard_units=convert_units($this->data['Part '.$tag.' Dimensions Width Display'], $value, 'm');
				$depth_in_standard_units=convert_units($this->data['Part '.$tag.' Dimensions Depth Display'], $value, 'm');
				$length_in_standard_units=convert_units($this->data['Part '.$tag.' Dimensions Length Display'], $value, 'm');
				$diameter_in_standard_units=convert_units($this->data['Part '.$tag.' Dimensions Diameter Display'], $value, 'm');


				$this->update_field('Part '.$tag.' Dimensions Width', $width_in_standard_units, 'nohistory');
				$this->update_field('Part '.$tag.' Dimensions Depth', $depth_in_standard_units, 'nohistory');
				$this->update_field('Part '.$tag.' Dimensions Length', $length_in_standard_units, 'nohistory');
				$this->update_field('Part '.$tag.' Dimensions Diameter', $diameter_in_standard_units, 'nohistory');



			}

			//print "x".$this->updated."<<";



			//print "x".$this->updated."< $type <";
			if ($type=='Dimensions') {
				include_once 'utils/geometry_functions.php';
				$volume=get_volume($this->data["Part $tag Dimensions Type"], $this->data["Part $tag Dimensions Width"], $this->data["Part $tag Dimensions Depth"], $this->data["Part $tag Dimensions Length"], $this->data["Part $tag Dimensions Diameter"]);

				//print "*** $volume $volume";
				if (is_numeric($volume) and $volume>0) {

					$this->update_field('Part '.$tag.' Dimensions Volume', $volume, 'nohistory');
				}
				$this->update_field('Part '.$tag.' XHTML Dimensions', $this->get_xhtml_dimensions($tag), 'nohistory');

			}else {
				$this->update_field('Part '.$tag.' Weight', convert_units($this->data['Part '.$tag.' Weight Display'], $this->data['Part '.$tag.' '.$type.' Display Units'], 'Kg'), 'nohistory');

			}





			$this->updated=$_updated;
			$this->new_value=$_new_value;
		}
	}

















	function load($data_to_be_read, $args='') {
		switch ($data_to_be_read) {


		case('locations'):
			$this->load_locations($args);


			break;

		case('stock_data'):
			$astock=0;
			$avaue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`), 'ERROR') as stock, avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where  `Part SKU`=%d and `Date`>=%s and `Date`<=%s group by `Date`", $this->data['Part SKU'], prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid From']))), prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid To']))  ));
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part Total Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part Total AVG Stock`=%s , `Part Total AVG Stock Value`=%s, `Part Total Keeping Days`=%f , `Part Total Out of Stock Days`=%f , `Part Total Unknown Stock Days`=%s, `Part Total GMROI`=%s where `Part SKU`=%d"
				, $astock
				, $avalue
				, $tdays
				, $outstock
				, $unknown
				, $gmroi
				, $this->id);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql  ** errot con not update part stock history all");

			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`), 'ERROR') as stock, avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`", $this->data['Part SKU'], prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid From']))), prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid To']))  )  , prepare_mysql(date("Y-m-d H:i:s", strtotime("now -1 year")))  );
			//print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Year Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Year Acc AVG Stock`=%s , `Part 1 Year Acc AVG Stock Value`=%s, `Part 1 Year Acc Keeping Days`=%f , `Part 1 Year Acc Out of Stock Days`=%f , `Part 1 Year Acc Unknown Stock Days`=%s, `Part 1 Year Acc GMROI`=%s where `Part SKU`=%d"
				, $astock
				, $avalue
				, $tdays
				, $outstock
				, $unknown
				, $gmroi
				, $this->id);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql **  errot con not update part stock history yr aa");


			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`), 'ERROR') as stock, avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`", $this->data['Part SKU'], prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid From']))), prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid To']))  )  , prepare_mysql(date("Y-m-d H:i:s", strtotime("now -3 month")))  );
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Quarter Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc AVG Stock`=%s , `Part 1 Quarter Acc AVG Stock Value`=%s, `Part 1 Quarter Acc Keeping Days`=%f , `Part 1 Quarter Acc Out of Stock Days`=%f , `Part 1 Quarter Acc Unknown Stock Days`=%s, `Part 1 Quarter Acc GMROI`=%s where `Part SKU`=%d"
				, $astock
				, $avalue
				, $tdays
				, $outstock
				, $unknown
				, $gmroi
				, $this->id);
			//   print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql z errot con not update part stock history yr bb");

			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`), 'ERROR') as stock, avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`", $this->data['Part SKU'], prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid From']))), prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid To']))  )  , prepare_mysql(date("Y-m-d H:i:s", strtotime("now -1 month")))  );
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Month Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Month Acc AVG Stock`=%s , `Part 1 Month Acc AVG Stock Value`=%s, `Part 1 Month Acc Keeping Days`=%f , `Part 1 Month Acc Out of Stock Days`=%f , `Part 1 Month Acc Unknown Stock Days`=%s, `Part 1 Month Acc GMROI`=%s where `Part SKU`=%d"
				, $astock
				, $avalue
				, $tdays
				, $outstock
				, $unknown
				, $gmroi
				, $this->id);
			//   print "$sql\n";
			if (!mysql_query($sql))
				exit(" $sql x errot con not update part stock history yr cc");


			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`), 'ERROR') as stock, avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`", $this->data['Part SKU'], prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid From']))), prepare_mysql(date("Y-m-d", strtotime($this->data['Part Valid To']))  )  , prepare_mysql(date("Y-m-d H:i:s", strtotime("now -1 week")))  );
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$tmp=1.0000001/$days_ok;
				$astock=$astock*$tmp;
				$avalue=$avalue*$tmp;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Week Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Week Acc AVG Stock`=%s , `Part 1 Week Acc AVG Stock Value`=%s, `Part 1 Week Acc Keeping Days`=%f , `Part 1 Week Acc Out of Stock Days`=%f , `Part 1 Week Acc Unknown Stock Days`=%s, `Part 1 Week Acc GMROI`=%s where `Part SKU`=%d"
				, $astock
				, $avalue
				, $tdays
				, $outstock
				, $unknown
				, $gmroi
				, $this->id);
			//   print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql q errot con not update part stock history wk");

			break;
		case('used in list'):

			$sql=sprintf("select `Product ID` from `Product Part Dimension` PPD  left join  `Product Part List` PPL on (PPL.`Product Part Key`=PPD.`Product Part Key`)  where `Part SKU`=%d group by `Product ID`", $this->data['Part SKU']);
			// print $sql;
			$result=mysql_query($sql);
			$this->used_in_list=array();

			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->used_in_list[]=$row['Product ID'];
			}
			//   print_r($this->used_in_list);
			break;






		}

	}






	function get_period($period, $key) {
		return $this->get($period.' '.$key);
	}


	function get($key='', $args=false) {

		global $account;

		list($got, $result)=$this->get_asset_common($key, $args);
		if ($got)return $result;

		if (!$this->id)
			return;


		switch ($key) {



		case 'Unit Price':
			include_once 'utils/natural_language.php';
			$unit_price= money($this->data['Part Unit Price'], $account->get('Account Currency'));

			$price_other_info='';
			if ($this->data['Part Units Per Package']!=1 and  is_numeric($this->data['Part Units Per Package'])) {
				$price_other_info='('.money($this->data['Part Unit Price']*$this->data['Part Units Per Package'], $account->get('Account Currency')).' '._('per SKO').'), ';
			}


			if ($this->data['Part Units Per Package']!=0 and  is_numeric($this->data['Part Units Per Package'])) {

				$unit_margin=$this->data['Part Unit Price']-($this->data['Part Cost']/$this->data['Part Units Per Package']);

				$price_other_info.=sprintf('<span class="'.($unit_margin<0?'error':'').'">'._('margin %s').'</span>', percentage($unit_margin, $this->data['Part Unit Price']));
			}

			$price_other_info=preg_replace('/^, /', '', $price_other_info);
			if ($price_other_info!='') {
				$unit_price.=' <span class="discreet">'.$price_other_info.'</span>';
			}

			return $unit_price;
			break;
		case 'Unit RRP':
			if ($this->data['Part Unit RRP']=='')return '';

			include_once 'utils/natural_language.php';
			$rrp= money($this->data['Part Unit RRP'], $account->get('Account Currency'));


			$unit_margin=$this->data['Part Unit RRP']-$this->data['Part Unit Price'];
			$rrp_other_info=sprintf(_('margin %s'), percentage($unit_margin, $this->data['Part Unit RRP']));



			$rrp_other_info=preg_replace('/^, /', '', $rrp_other_info);
			if ($rrp_other_info!='') {
				$rrp.=' <span class="'.($unit_margin<0?'error':'').'  discreet">'.$rrp_other_info.'</span>';
			}
			return $rrp;
			break;
		case 'Barcode':

			if ($this->get('Part Barcode Number')=='')return '';

			return '<i '.
				($this->get('Part Barcode Key')?
				'class="fa fa-barcode button" onClick="change_view(\'inventory/barcode/'.$this->get('Part Barcode Key').'\')"':'class="fa fa-barcode"').
				' ></i><span class="Part_Barcode_Number ">'.$this->get('Part Barcode Number').'</span>';

			break;

		case 'Available Forecast':

			if ($this->data['Part Stock Status']=='Out_Of_Stock' or  $this->data['Part Stock Status']=='Error') return '';
			include_once 'utils/natural_language.php';
			return '<span style="font-size:80%">'.sprintf(_('%s until out of stock'), '<span style="font-size:120%" title="'.sprintf("%s %s", number($this->data['Part Days Available Forecast'], 1) ,
					ngettext("day", "days", intval($this->data['Part Days Available Forecast'] ) )).'">'.seconds_to_natural_string($this->data['Part Days Available Forecast']*86400, true).'</span>').'</span>';
			break;

		case 'Origin Country Code':
			if ($this->data['Part Origin Country Code']) {
				include_once 'class.Country.php';
				$country=new Country('code', $this->data['Part Origin Country Code']);
				return '<img src="/art/flags/'.strtolower($country->get('Country 2 Alpha Code')).'.gif" title="'.$country->get('Country Code').'"> '._($country->get('Country Name'));
			}else {
				return '';
			}

			break;
		case 'Origin Country':
			if ($this->data['Part Origin Country Code']) {
				include_once 'class.Country.php';
				$country=new Country('code', $this->data['Part Origin Country Code']);
				return $country->get('Country Name');
			}else {
				return '';
			}

			break;



		case 'SKU':
			return sprintf("sku%05d", $this->sku);
			break;

			break;
		case 'Next Supplier Shipment':
			if ($this->data['Part Next Supplier Shipment']=='') {
				return '';
			}else {
				return strftime("%a, %e %b %y", strtotime($this->data['Part Next Supplier Shipment'].' +0:00'));
			}
			break;

		case('Current Stock Available'):

			return number($this->data['Part Current On Hand Stock']-$this->data['Part Current Stock In Process']);

		case('Cost'):
			global $corporate_currency;
			return money($this->data['Part Current Stock Cost Per Unit'], $corporate_currency);


			break;

			break;
		case('Valid From'):
		case('Valid From Datetime'):

			return strftime("%a %e %b %Y %H:%M %Z", strtotime($this->data['Part Valid From']+' 0:00'));
			break;
		case('Valid To'):
			return strftime("%a %e %b %Y %H:%M %Z", strtotime($this->data['Part Valid To']+' 0:00'));
			break;
		default:

			if (preg_match('/No Supplied$/', $key)) {

				$_key=preg_replace('/ No Supplied$/', '', $key);
				if (preg_match('/^Part /', $key)) {
					return $this->data["$_key Required"]-$this->data["$_key Provided"];

				} else {
					return number($this->data["Part $_key Required"]-$this->data["Part $_key Provided"]);
				}

			}


			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Month To|Today|Week To).*(Amount|Profit)$/', $key)) {

				$amount='Part '.$key;

				return money($this->data[$amount]);
			}

			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Month To|Today|Week To).*(Margin|GMROI)$/', $key)) {

				$amount='Part '.$key;

				return percentage($this->data[$amount], 1);
			}


			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Provided|Broken|Acquired)$/', $key) or $key=='Current Stock'  ) {

				$amount='Part '.$key;

				return number($this->data[$amount]);
			}


			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Part '.$key, $this->data))
				return $this->data['Part '.$key];

		}

		return false;
	}


	function get_unit($number) {
		//'10','25','100','200','bag','ball','box','doz','dwt','ea','foot','gram','gross','hank','kilo','ib','m','oz','ozt','pair','pkg','set','skein','spool','strand','ten','tube','vial','yd'
		switch ($this->data['Part Unit']) {
		case 'bag':
			$unit=ngettext('bag', 'bags', $number);
			break;
		case 'box':
			$unit=ngettext('box', 'boxes', $number);

			break;
		case 'doz':
			$unit=ngettext('dozen', 'dozens', $number);

			break;
		case 'ea':
			$unit=ngettext('unit', 'units', $number);

			break;
		default:
			$unit=$this->data['Part Unit'];
			break;
		}
		return $unit;
	}


	function get_current_stock() {
		$stock=0;
		$value=0;
		$in_process=0;

		/*

			$sql=sprintf("select sum(`Quantity On Hand`) as stock , sum(`Quantity In Process`) as in_process , sum(`Stock Value`) as value from `Part Location Dimension` where `Part SKU`=%d ",$this->id);
			$res=mysql_query($sql);
			//print $sql;
			if ($row=mysql_fetch_array($res)) {
				$stock=round($row['stock'],3);
				$in_process=round($row['in_process'],3);
				$value=$row['value'];

			}
*/

		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as stock , sum(`Inventory Transaction Amount`) as value
																																																								from `Inventory Transaction Fact` where `Part SKU`=%d ",
			$this->sku
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$stock=round($row['stock'], 5);
			$value=$row['value'];
		}









		return array($stock, $value, $in_process);

	}


	function get_stock($date) {
		$stock=0;
		$value=0;
		$sql=sprintf("select ifnull(sum(`Quantity On Hand`), 0) as stock, ifnull(sum(`Value At Cost`), 0) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`=%s"
			, $this->id, prepare_mysql($date));
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$stock=$row['stock'];
			$value=$row['value'];
		}
		return array($stock, $value);
	}




	function get_all_product_ids() {
		$sql=sprintf("select `Product Part Dimension`.`Product ID` from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d  group by `Product ID`", $this->data['Part SKU']);
		// print $sql;
		$result=mysql_query($sql);
		$products=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$products[$row['Product ID']]=array('Product ID'=>$row['Product ID']);
		}
		return $products;
	}


	function update_stock_status() {

		if ($this->data['Part Current Stock']<0) {
			$stock_state='Error';
		}elseif ($this->data['Part Current Stock']==0) {
			$stock_state='Out_of_Stock';
		}elseif ($this->data['Part Days Available Forecast']<=$this->data['Part Delivery Days']) {
			$stock_state='Critical';
		}elseif ($this->data['Part Days Available Forecast']<=$this->data['Part Delivery Days']+7) {
			$stock_state='Low';
		}elseif ($this->data['Part Days Available Forecast']>=$this->data['Part Excess Availability Days Limit']) {
			$stock_state='Surplus';
		}else {
			$stock_state='Optimal';
		}
		$this->data['Part Stock State']=$stock_state;

		$sql=sprintf("update `Part Dimension`  set `Part Stock Status`=%s where  `Part SKU`=%d   ",
			prepare_mysql($this->data['Part Stock State']),
			$this->id
		);

		$this->db->exec($sql);


		/*
		$products=$this->get_current_product_ids();

		foreach ($products as  $product_id=>$values) {
			$product=new Product('pid', $product_id);
			if ($product->id) {
				$product->update_availability();
			}
		}
*/

	}


	function update_stock() {


		$picked=0;
		$required=0;


		$sql=sprintf("select sum(`Picked`) as picked, sum(`Required`) as required from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`='Order In Process'"
			, $this->id
		);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$picked=round($row['picked'], 3);
			$required=round($row['required'], 3);
		}


		list($stock, $value, $in_process)=$this->get_current_stock();
		//print $stock;
		$this->data['Part Current Stock']=$stock+$picked;
		$this->data['Part Current Value']=$value;
		$this->data['Part Current Stock In Process']=$required-$picked;
		$this->data['Part Current Stock Picked']=$picked;
		$this->data['Part Current On Hand Stock']=$stock;



		$sql=sprintf("update `Part Dimension`  set `Part Current Stock`=%f , `Part Current Value`=%f, `Part Current Stock In Process`=%f, `Part Current Stock Picked`=%f,
																																																																	`Part Current On Hand Stock`=%f where  `Part SKU`=%d   "
			, $stock+$picked
			, $value
			, $required-$picked
			, $picked
			, $stock
			, $this->id
		);
		$this->db->exec($sql);
		//print "-> $stock , $picked, $required, , , ";
		$this->update_stock_status();

		$this->update_available_forecast();



		//print "$sql\n";
	}


	function update_availability() {


		$availability='No';

		$supplier_products=$this->get_supplier_products();
		// if (count($supplier_products)>0) {
		//   $availability='Yes';
		// }




		//print_r($supplier_products);

		//TODO meka it work if you have more that 2 suppliers, for now all parts are 1-1 (1-n,n-1) are treated as production

		foreach ($supplier_products as $supplier_product) {

			if ($supplier_product['Supplier Product Part In Use']=='Yes')
				$availability='Yes';
		}


		$sql=sprintf("update `Part Dimension`  set `Part Available`=%s where  `Part SKU`=%d   "
			, prepare_mysql($availability)
			, $this->sku
		);
		$this->db->exec($sql);




		//print "$sql\n";
		$this->update_main_state();


		$products=$this->get_product_ids();
		foreach ($products as $product_pid) {
			$product=new Product ('pid', $product_pid);
			$product->update_availability_type();

		}

	}


	function update_valid_to($date) {
		$this->data['Part Valid To']=$date;
		$sql=sprintf("update `Part Dimension`  set `Part Valid To`=%s where  `Part SKU`=%d    "
			, prepare_mysql($date)
			, $this->id
		);

		$op=$this->db->prepare($sql);
		$op->execute();
		if ($op->rowCount()) {
			//print "sdasdas asdkokk; $date\n";
			$this->update_product_part_list_dates();
		}



	}


	function update_last_date_from_transactions($type) {

		if ($type=='Sale') {
			$field='Part Last Sale Date';
		}elseif ($type=='In') {
			$field='Part Last Booked In Date';
		}else {
			print "$type\n";
			return false;
		}
		$date='';
		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`=%s order by `Date` desc limit 1",
			$this->id,
			prepare_mysql($type)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$date=$row['Date'];
		}
		$sql=sprintf("update `Part Dimension`  set `%s`=%s where  `Part SKU`=%d",
			$field,
			prepare_mysql($date),
			$this->id
		);

		$this->db->exec($sql);
		$this->data[$field]=$date;
	}


	function update_last_sale_date() {
		$date='';
		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type` like 'Sale' order by `Date` desc limit 1",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$date=$row['Date'];
		}
		$sql=sprintf("update `Part Dimension`  set `Part Last Sale Date`=%s where  `Part SKU`=%d",
			prepare_mysql($date),
			$this->id
		);
		$this->db->exec($sql);
		$this->data['Part Last Sale Date']=$date;
	}



	function update_valid_from($date) {
		$this->data['Part Valid To']=$date;
		$sql=sprintf("update `Part Dimension`  set `Part Valid From`=%s where  `Part SKU`=%d    "
			, prepare_mysql($date)
			, $this->id
		);
		mysql_query($sql);

		$this->update_product_part_list_dates();


	}


	function update_picking_location() {

		$sql=sprintf("select * from `Part Location Dimension` PL left join `Location Dimension` L on (L.`Location Key`=PL.`Location Key`) where `Part SKU`=%d and `Can Pick`='Yes'  ", $this->sku);

		$picking_location='';


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$picking_location.=sprintf(", <href='location.php?id=%d'>%s</a>", $row['Location Key'], $row['Location Code']);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$picking_location=preg_replace('/^,/', '', $picking_location);
		$this->data['Part XHTML Picking Location']=$picking_location;

		$sql=sprintf("update `Part Dimension`  set `Part XHTML Picking Location`=%s where  `Part SKU`=%d   "
			, prepare_mysql($this->data['Part XHTML Picking Location'], false)
			, $this->id
		);
		$this->db->exec($sql);

	}


	function update_valid_dates($date) {
		$affected_from=0;
		$affected_to=0;
		$sql=sprintf("update `Part Dimension`  set `Part Valid From`=%s where  `Part SKU`=%d and `Part Valid From`>%s   "
			, prepare_mysql($date)
			, $this->id
			, prepare_mysql($date)

		);

		$op=$this->db->prepare($sql);
		$op->execute();
		if ($affected_from=$op->rowCount()) {
			$this->data['Part Valid From']=$date;
		}
		$sql=sprintf("update `Part Dimension`  set `Part Valid To`=%s where  `Part SKU`=%d and `Part Valid To`<%s   "
			, prepare_mysql($date)
			, $this->id
			, prepare_mysql($date)

		);


		$op=$this->db->prepare($sql);
		$op->execute();
		if ($affected_to=$op->rowCount()) {
			$this->data['Part Valid To']=$date;
		}




		return $affected_to+$affected_from;
	}




	function get_historic_locations() {
		$locations=array();

		$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Associate' and `Part SKU`=%d   group by `Location Key`  ", $this->data['Part SKU']);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$locations[$row['Location Key']]=$row['Location Key'];
		}

		return $locations;

	}



	function load_locations($date='') {

		if (preg_match('/\d{4}-\{d}2-\d{2}/', $date))
			$this->load_locations_historic($date);
		else
			$this->load_current_locations();
	}


	function load_current_historic($date) {
		$this->all_historic_associated_locations=array();
		$this->associated_location_on_date=array();

		$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Associate' and `Part SKU`=%d  `Date`=%s  group by `Location Key`  ", $this->data['Part SKU'], $date);
		// print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->all_historic_associated_locations[]=$row['Location Key'];
		}
		foreach ($this->all_historic_associated_locations as $location_key) {
			$sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where (`Inventory Transaction Type` like 'Associate' or `Inventory Transaction Type` like 'Disassociate') and `Part SKU`=%d and `Location Key`=%d %s order by `Date` desc limit 1 ", $this->data['Part SKU'], $location_key, $date);
			//   print $sql;
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {

				if ($row['Inventory Transaction Type']=='Associate')
					$this->associated_location_on_date[]=$location_key;
			}

		}

	}


	function get_picking_location_key($date=false, $qty=1, $historic=false) {
		if ($historic) {
			return $this->get_picking_location_historic($date, $qty);
		}

		//FORCING PICKING FOR PICKING LOCAtION EVEN IF IS NEGATIVE

		$this->unknown_location_associated=false;
		$locations=array();
		$sql=sprintf("select `Location Key` from `Part Location Dimension` where `Part SKU` in (%s) order by `Can Pick` ;", $this->sku);
		//print "$sql\n";
		$res=mysql_query($sql);
		$locations_data=array();
		while ($row=mysql_fetch_assoc($res)) {
			$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
			//list($stock,$value,$in_process)=$part_location->get_stock();
			$stock=$part_location->data['Quantity On Hand'];

			$locations_data[]=array('location_key'=>$row['Location Key'], 'stock'=>$stock);

		}

		$number_associated_locations=count($locations_data);

		if ($number_associated_locations==0) {
			$this->unknown_location_associated=true;
			$locations[]= array('location_key'=>1, 'qty'=>$qty);
			$qty=0;
		}else {

			foreach ($locations_data as $location_data) {

				$locations[]=array('location_key'=>$location_data['location_key'], 'qty'=>$qty);
				break;





			}
			//print_r($locations);
			//print "--- $qty\n";



		}

		//print_r($locations);
		return $locations;

	}


	function get_picking_location_key_origial($date=false, $qty=1) {
		if ($date) {
			return $this->get_picking_location_historic($date, $qty);
		}
		$this->unknown_location_associated=false;
		$locations=array();
		$sql=sprintf("select `Location Key` from `Part Location Dimension` where `Part SKU` in (%s) order by `Can Pick` ;", $this->sku);
		//print "$sql\n";
		$res=mysql_query($sql);
		$locations_data=array();
		while ($row=mysql_fetch_assoc($res)) {
			$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
			list($stock, $value, $in_process)=$part_location->get_stock();
			$locations_data[]=array('location_key'=>$row['Location Key'], 'stock'=>$stock);

		}

		$number_associated_locations=count($locations_data);

		if ($number_associated_locations==0) {
			$this->unknown_location_associated=true;
			$locations[]= array('location_key'=>1, 'qty'=>$qty);
			$qty=0;
		}else {

			foreach ($locations_data as $location_data) {
				if ($qty>0) {
					if ($location_data['stock']>=$qty) {
						$locations[]=array('location_key'=>$location_data['location_key'], 'qty'=>$qty);
						$qty=0;
					}
					elseif ($location_data['stock']>0) {
						$locations[]=array('location_key'=>$location_data['location_key'], 'qty'=>$location_data['stock']);
						$qty=$qty-$location_data['stock'];
					}
				}



			}
			//print_r($locations);
			//print "--- $qty\n";

			if (count($locations)==0) {

				$locations[]= array('location_key'=>$locations_data[0]['location_key'], 'qty'=>$qty);
				$qty=0;
			}
			if ($qty>0) {
				$locations[0]['qty']=$locations[0]['qty']+$qty;
			}


		}

		//print_r($locations);
		return $locations;

	}


	function associate_unknown_location_historic($date=false) {

		if (!$date) {
			$date=gmdate("Y-m-d H:i:s");
		}
		$date=date("Y-m-d H:i:s", strtotime("$date -1 second"));
		$location_key=1;


		$sql=sprintf("select `Inventory Transaction Key` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Inventory Transaction Type` like 'Associate' and `Date`>%s order by `Date`  ", $this->sku, $location_key, prepare_mysql($date));

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Key`=%d  "
				, $row['Inventory Transaction Key']
			);
			$this->db->exec($sql);

			$details=_('Part')." SKU".sprintf("%05d", $this->sku)." "._('associated with unknown location');
			$sql=sprintf("insert into `Inventory Transaction Fact` (`Inventory Transaction Record Type`, `Inventory Transaction Section`, `Part SKU`, `Location Key`, `Inventory Transaction Type`, `Inventory Transaction Quantity`, `Inventory Transaction Amount`, `User Key`, `Note`, `Date`) values (%s, %s, %d, %d, %s, %f, %.2f, %s, %s, %s)",
				"'Helper'",
				"'Other'",
				$this->sku,
				$location_key,
				"'Associate'",
				0,
				0,
				0,
				prepare_mysql($details),
				prepare_mysql($date)

			);
			$this->db->exec($sql);

		}

		else {

			$sql=sprintf("select `Inventory Transaction Key` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Inventory Transaction Type` like 'Disassociate' and `Date`>%s order by `Date`  ", $this->sku, $location_key, prepare_mysql($date));

			$res2=mysql_query($sql);
			// print $sql;
			if ($row2=mysql_fetch_array($res2)) {

			}else {



				$pl_data=array(
					'Part SKU'=>$this->sku,
					'Location Key'=>$location_key,
					'Date'=>$date);
				//print_r($pl_data);
				$part_location=new PartLocation('find', $pl_data, 'create');
			}

			//print_r($part_location);
		}


	}





	function get_picking_location_historic($date, $qty) {


		include_once 'class.PartLocation.php';

		$this->unknown_location_associated=false;


		$locations=array();
		$was_associated=array();
		$sql=sprintf("select ITF.`Location Key`  from `Inventory Transaction Fact` ITF    where `Inventory Transaction Type` like 'Associate' and  `Part SKU`=%d and `Date`<=%s   order by `Location Key`  desc  ", $this->sku, prepare_mysql($date));


		$_locations=array();


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if (in_array($row['Location Key'], $_locations)) {
					continue;
				}else {
					$_locations[]=$row['Location Key'];
				}
				$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);


				if ($part_location->location->data['Location Mainly Used For']=='Picking') {


					if ($part_location->is_associated($date)) {
						list($stock, $value, $in_process)=$part_location->get_stock($date);
						$was_associated[]=array('location_key'=>$row['Location Key'], 'stock'=>$stock);

					}
				}


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		$sql=sprintf("select ITF.`Location Key`  from `Inventory Transaction Fact` ITF    where `Inventory Transaction Type` like 'Associate' and  `Part SKU`=%d and `Date`<=%s   ", $this->sku, prepare_mysql($date));


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if (in_array($row['Location Key'], $_locations)) {
					continue;
				}else {
					$_locations[]=$row['Location Key'];
				}
				$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);

				// print_r($part_location->location);
				if ($part_location->location->data['Location Mainly Used For']!='Picking') {
					if ($part_location->is_associated($date)) {
						list($stock, $value, $in_process)=$part_location->get_stock($date);
						$was_associated[]=array('location_key'=>$row['Location Key'], 'stock'=>$stock);

					}
				}


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		//print "------------------".$this->data['Part Currently Used In']."\n";
		// print_r($was_associated);

		//print "==================\n";


		$number_associated_locations=count($was_associated);

		if ($number_associated_locations==0) {
			$this->unknown_location_associated=true;
			$locations[]= array('location_key'=>1, 'qty'=>$qty);
			$qty=0;
		}else {
			//foreach ($was_associated as $key => $row) {
			// $_location_key[$key]  = $row['location_key'];
			//}
			//array_multisort($_location_key, SORT_DESC, $was_associated);

			foreach ($was_associated as $location_data) {
				if ($qty>0) {
					if ($location_data['stock']>=$qty) {
						$locations[]=array('location_key'=>$location_data['location_key'], 'qty'=>$qty);
						$qty=0;
					}
					elseif ($location_data['stock']>0) {
						$locations[]=array('location_key'=>$location_data['location_key'], 'qty'=>$location_data['stock']);
						$qty=$qty-$location_data['stock'];
					}
				}



			}
			//print_r($locations);
			//print "--- $qty\n";

			if (count($locations)==0) {

				$locations[]= array('location_key'=>$was_associated[0]['location_key'], 'qty'=>$qty);
				$qty=0;
			}
			if ($qty>0) {
				$locations[0]['qty']=$locations[0]['qty']+$qty;
			}


		}
		//if ($this->unknown_location_associated)
		// print "\n".$this->sku." unknown location addes\n";


		//print_r($locations);
		//print "`~~~~~~~~~~~~~~~\n";

		return $locations;


	}


	function get_barcode_data() {

		switch ($this->data['Part Barcode Data Source']) {
		case 'SKU':
			return $this->sku;
		case 'Reference':
			return $this->data['Part Reference'];
		default:
			return $this->data['Part Barcode Data'];


		}

	}


	function get_current_products($for_smarty=false) {

		$sql=sprintf("select  `Product Number Web Pages`,`Product Web Configuration`,`Product Web State`,`Store Key`,`Store Code`,P.`Product ID`,`Product Code`,`Product Store Key` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` P on (P.`Product ID`=PPD.`Product ID`) left join `Store Dimension` on (`Product Store Key`=`Store Key`)  where  `Part SKU`=%d  and  `Product Part Most Recent`='Yes'  and `Product Record Type`='Normal'",
			$this->sku
		);
		//print $sql;
		$res=mysql_query($sql);
		$products=array();
		while ($row=mysql_fetch_array($res)) {
			$products[$row['Product ID']]= array(
				'ProductID'=>$row['Product ID'],
				'ProductCode'=>$row['Product Code'],
				'StoreCode'=>$row['Store Code'],
				'StoreKey'=>$row['Store Key'],
				'ProductNumberWebPages'=>$row['Product Number Web Pages'],
				'ProductWebConfiguration'=>$row['Product Web Configuration'],
				'ProductWebState'=>$row['Product Web State'],
			);
		}

		return $products;
	}


	function get_current_products_objects() {

		$sql=sprintf("select  P.`Product ID` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` P on (P.`Product ID`=PPD.`Product ID`) left join `Store Dimension` on (`Product Store Key`=`Store Key`)  where  `Part SKU`=%d  and  `Product Part Most Recent`='Yes'  and `Product Record Type`='Normal'",
			$this->sku
		);
		//print $sql;
		$res=mysql_query($sql);
		$products=array();
		while ($row=mysql_fetch_array($res)) {
			$products[]=new Product('pid', $row['Product ID']);
		}

		return $products;
	}




	function get_locations($for_smarty=false) {
		$sql=sprintf("select PL.`Location Key`,`Location Code`,`Quantity On Hand`,`Location Warehouse Key`,`Location Mainly Used For`,`Part SKU`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Can Pick` from `Part Location Dimension` PL left join `Location Dimension` L on (L.`Location Key`=PL.`Location Key`)  where `Part SKU`=%d", $this->sku);

		$res=mysql_query($sql);
		$part_locations=array();




		while ($row=mysql_fetch_assoc($res)) {

			switch ($row['Location Mainly Used For']) {
			case 'Picking':
				$used_for=sprintf('<i class="fa fa-fw fa-shopping-basket" aria-hidden="true" title="%s" ></i>', _('Picking'));
				break;
			case 'Storing':
				$used_for=sprintf('<i class="fa fa-fw  fa-hdd-o" aria-hidden="true" title="%s"></i>', _('Storing'));
				break;
			default:
				$used_for=sprintf('<i class="fa fa-fw  fa-map-maker" aria-hidden="true" title="%s"></i>', $row['Location Mainly Used For']);
			}

			$part_locations[]=array(
				'formatted_stock'=>number($row['Quantity On Hand'], 3),
				'stock'=>$row['Quantity On Hand'],
				'warehouse_key'=>$row['Location Warehouse Key'],

				'location_key'=>$row['Location Key'],
				'part_sku'=>$row['Part SKU'],

				'location_code'=>$row['Location Code'],
				'location_used_for_icon'=>$used_for,
				'location_used_for'=>$row['Location Mainly Used For'],
				'formatted_min_qty'=>($row['Minimum Quantity']!=''?$row['Minimum Quantity']:'?'),
				'formatted_max_qty'=>($row['Maximum Quantity']!=''?$row['Maximum Quantity']:'?'),
				'formatted_move_qty'=>($row['Moving Quantity']!=''?$row['Moving Quantity']:'?'),
				'min_qty'=>$row['Minimum Quantity'],
				'max_qty'=>$row['Maximum Quantity'],
				'move_qty'=>$row['Moving Quantity'],

				'can_pick'=>$row['Can Pick']
			);


		}

		return $part_locations;
	}


	function get_location_keys() {
		$this->load_current_locations();
		return $this->current_associated_locations;
	}


	function load_current_locations() {
		$this->current_associated_locations=array();
		$sql=sprintf("select `Location Key` from `Part Location Dimension` where   `Part SKU`=%d    group by `Location Key`  ", $this->data['Part SKU']);
		//  print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->current_associated_locations[]=$row['Location Key'];
		}
		$this->current_locations_loaded=true;

	}


	function items_per_product($product_ID, $date=false) {
		$where_date='';

		$sql=sprintf("select AVG(`Parts Per Product`) as parts_per_product from `Product Part Dimension` PPD left join `Product Part List` PPL on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU`=%d and  `Product ID`=%d %s  "
			, $this->id
			, $product_ID
			, $where_date
		);
		//  print "$sql\n";
		$parts_per_product=0;
		$result3=mysql_query($sql);
		if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
			if (is_numeric($row3['parts_per_product']))
				$parts_per_product=$row3['parts_per_product'];
		}
		return $parts_per_product;


	}


	function get_fomated_unit_commercial_value($datetime='') {

		return money($this->get_unit_commercial_value($datetime));
	}


	function get_current_formatted_commercial_value() {

		return money($this->data['Part Current On Hand Stock']*$this->get_unit_commercial_value());
	}


	function get_current_formatted_value_at_cost() {
		//return number($this->data['Part Current Value'],2);
		return money( $this->data['Part Current Value']);
	}



	function get_current_formatted_value_at_current_cost() {

		$a=floatval(3.000*3.575);
		$a=round(3.575+3.575+3.575, 3);
		return money($this->data['Part Current On Hand Stock']*$this->data['Part Cost']  );
	}


	function get_unit_commercial_value($datetime='') {



		$commercial_value=0;
		$sum_commercial_value=0;
		$count_commercial_value_samples=0;

		$product_part_lists=$this->get_product_part_list($datetime);

		// print_r($product_part_lists);

		foreach ($product_part_lists as $product_part_list) {



			$product=new Product('pid', $product_part_list['Product ID']);


			if ($product->pid and $product_part_list['Parts Per Product']>0) {
				$price=$product->get_historic_price_corporate_currency($datetime)/$product_part_list['Parts Per Product'];

				if ($price>0) {
					$sum_commercial_value+=$price;
					$count_commercial_value_samples++;
				}
			}

		}


		if ($count_commercial_value_samples) {
			$commercial_value=$sum_commercial_value/$count_commercial_value_samples;
		}
		// print "xx $commercial_value";

		return $commercial_value;
	}


	function fix_stock_transactions() {

		include_once 'class.PartLocation.php';

		$sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where `Part SKU`=%d group by `Location Key`", $this->sku);



		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
				$part_location->redo_adjusts();
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select `Inventory Transaction Key`,`Date`,`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Location Key`,`Note`,`Inventory Transaction Quantity`,`Required`  from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Section` in ('Out','OIP') order by `Date`", $this->sku);



		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {


				if ($row['Inventory Transaction Section']=='OIP') {
					$qty=$row['Required'];
				}else {
					$qty=-1*$row['Inventory Transaction Quantity'];
				}
				$picking_locations=$this->get_picking_location_historic($row['Date'], $qty);

				if (count($picking_locations==1) and $picking_locations[0]['location_key']!=$row['Location Key']) {

					$_location=new Location($picking_locations[0]['location_key']);
					$note=$row['Note'];

					if (preg_match('/(<.*a> )(.*)/', $note, $matches)) {

						if ($_location->id==1) {
							$location_note.=' '._('Taken from an')." ".sprintf("<a href='location.php?id=1'>%s</a>", _('Unknown Location'));
						} else {
							$location_note=' '._('Taken from').": ".sprintf("<a href='location.php?id=%d'>%s</a>", $_location->id, $_location->data['Location Code']);
						}


						$note=$matches[1].$location_note;
					}else {

						$note.=' (WL)';
					}



					$sql=sprintf('update `Inventory Transaction Fact` set `Location Key`=%d ,`Note`=%s where `Inventory Transaction Key`=%d',
						$_location->id,
						prepare_mysql($note),
						$row['Inventory Transaction Key']
					);
					print $sql;
					$this->db->exec($sql);
					print_r($row);
					print_r($picking_locations);
				}


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$this->update_stock();

	}


	function update_stock_history() {


		$sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where `Part SKU`=%d group by `Location Key`", $this->sku);



		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
				$part_location->update_stock_history();
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


	function update_stock_in_transactions() {

		$locations_data=array();
		$stock=0;
		$sql=sprintf("select `Inventory Transaction Quantity` ,`Inventory Transaction Key`,`Location Key` from `Inventory Transaction Fact` where `Part SKU`=%d order by `Date`,`Event Order`", $this->sku);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			if (array_key_exists($row['Location Key'], $locations_data)) {
				$locations_data[$row['Location Key']]+=$row['Inventory Transaction Quantity'];
			}else {
				$locations_data[$row['Location Key']]=$row['Inventory Transaction Quantity'];
			}

			$stock+=$row['Inventory Transaction Quantity'];
			$sql=sprintf("update `Inventory Transaction Fact` set `Part Stock`=%f,`Part Location Stock`=%f where `Inventory Transaction Key`=%d",
				$stock,
				$locations_data[$row['Location Key']],
				$row['Inventory Transaction Key']
			);
			mysql_query($sql);
			//print "$sql\n";
		}


	}


	function update_up_today_sales() {
		$this->update_sales_from_invoices('Total');

		$this->update_sales_from_invoices('Today');
		$this->update_sales_from_invoices('Week To Day');
		$this->update_sales_from_invoices('Month To Day');
		$this->update_sales_from_invoices('Year To Day');


	}


	function update_last_period_sales() {

		$this->update_sales_from_invoices('Yesterday');
		$this->update_sales_from_invoices('Last Week');
		$this->update_sales_from_invoices('Last Month');
	}


	function update_interval_sales() {

		$this->update_sales_from_invoices('3 Year');
		$this->update_sales_from_invoices('1 Year');
		$this->update_sales_from_invoices('6 Month');
		$this->update_sales_from_invoices('1 Quarter');
		$this->update_sales_from_invoices('1 Month');
		$this->update_sales_from_invoices('10 Day');
		$this->update_sales_from_invoices('1 Week');

	}




	function update_sales_from_invoices($interval) {

		include_once 'utils/date_functions.php';
		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb)=calculate_interval_dates($interval);
		//print "$db_interval,$from_date,$to_date,$from_date_1yb,$to_date_1yb  \n";

		setlocale(LC_ALL, 'en_GB');


		$this->data["Part $db_interval Acc Required"]=0;
		$this->data["Part $db_interval Acc Provided"]=0;
		$this->data["Part $db_interval Acc Given"]=0;
		$this->data["Part $db_interval Acc Sold Amount"]=0;
		$this->data["Part $db_interval Acc Profit"]=0;
		$this->data["Part $db_interval Acc Profit After Storing"]=0;
		$this->data["Part $db_interval Acc Sold"]=0;
		$this->data["Part $db_interval Acc Margin"]=0;


		$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                     from `Inventory Transaction Fact` ITF  where `Part SKU`=%d %s %s" ,
			$this->sku,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);




		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Part $db_interval Acc Profit"]=$row['profit'];
				$this->data["Part $db_interval Acc Profit After Storing"]=$this->data["Part $db_interval Acc Profit"]-$row['cost_storing'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'In'  and `Part SKU`=%d  %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Part $db_interval Acc Acquired"]=$row['bought'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Part SKU`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Part $db_interval Acc Sold Amount"]=$row['sold_amount'];
				$this->data["Part $db_interval Acc Sold"]=$row['sold'];
				$this->data["Part $db_interval Acc Provided"]=-1.0*$row['dispatched'];
				$this->data["Part $db_interval Acc Required"]=$row['required'];
				$this->data["Part $db_interval Acc Given"]=$row['given'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Part SKU`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Part $db_interval Acc Broken"]=-1.*$row['broken'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Lost' and `Part SKU`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Part $db_interval Acc Lost"]=-1.*$row['lost'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}







		if ($this->data["Part $db_interval Acc Sold Amount"]!=0)
			$margin=$this->data["Part $db_interval Acc Profit After Storing"]/$this->data["Part $db_interval Acc Sold Amount"];
		else
			$margin=0;
		$this->data["Part $db_interval Acc Margin"]=$margin;


		$sql=sprintf("update `Part Data` set
                     `Part $db_interval Acc Required`=%f ,
                     `Part $db_interval Acc Provided`=%f,
                     `Part $db_interval Acc Given`=%f ,
                     `Part $db_interval Acc Sold Amount`=%f ,
                     `Part $db_interval Acc Profit`=%f ,
                     `Part $db_interval Acc Profit After Storing`=%f ,
                     `Part $db_interval Acc Sold`=%f ,
                     `Part $db_interval Acc Margin`=%s where
                     `Part SKU`=%d "
			, $this->data["Part $db_interval Acc Required"]
			, $this->data["Part $db_interval Acc Provided"]
			, $this->data["Part $db_interval Acc Given"]
			, $this->data["Part $db_interval Acc Sold Amount"]
			, $this->data["Part $db_interval Acc Profit"]
			, $this->data["Part $db_interval Acc Profit After Storing"]
			, $this->data["Part $db_interval Acc Sold"]
			, $this->data["Part $db_interval Acc Margin"]

			, $this->id);


		//print "$sql\n";

		$this->db->exec($sql);




		if ($from_date_1yb) {


			$this->data["Part $db_interval Acc 1YB Required"]=0;
			$this->data["Part $db_interval Acc 1YB Provided"]=0;
			$this->data["Part $db_interval Acc 1YB Given"]=0;
			$this->data["Part $db_interval Acc 1YB Sold Amount"]=0;
			$this->data["Part $db_interval Acc 1YB Profit"]=0;
			$this->data["Part $db_interval Acc 1YB Profit After Storing"]=0;
			$this->data["Part $db_interval Acc 1YB Sold"]=0;
			$this->data["Part $db_interval Acc 1YB Margin"]=0;


			$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                     from `Inventory Transaction Fact` ITF  where `Part SKU`=%d %s %s" ,
				$this->sku,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Part $db_interval Acc 1YB Profit"]=$row['profit'];
					$this->data["Part $db_interval Acc 1YB Profit After Storing"]=$this->data["Part $db_interval Acc 1YB Profit"]-$row['cost_storing'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



			$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'In'  and `Part SKU`=%d  %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Part $db_interval Acc 1YB Acquired"]=$row['bought'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


			$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Part SKU`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$this->data["Part $db_interval Acc 1YB Sold Amount"]=$row['sold_amount'];
					$this->data["Part $db_interval Acc 1YB Sold"]=$row['sold'];
					$this->data["Part $db_interval Acc 1YB Provided"]=-1.0*$row['dispatched'];
					$this->data["Part $db_interval Acc 1YB Required"]=$row['required'];
					$this->data["Part $db_interval Acc 1YB Given"]=$row['given'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}

			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Part SKU`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Part $db_interval Acc 1YB Broken"]=-1.*$row['broken'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Lost' and `Part SKU`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Part $db_interval Acc 1YB Lost"]=-1.*$row['lost'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}







			if ($this->data["Part $db_interval Acc 1YB Sold Amount"]!=0)
				$margin=$this->data["Part $db_interval Acc 1YB Profit After Storing"]/$this->data["Part $db_interval Acc 1YB Sold Amount"];
			else
				$margin=0;
			$this->data["Part $db_interval Acc 1YB Margin"]=$margin;


			$sql=sprintf("update `Part Data` set
                     `Part $db_interval Acc 1YB Required`=%f ,
                     `Part $db_interval Acc 1YB Provided`=%f,
                     `Part $db_interval Acc 1YB Given`=%f ,
                     `Part $db_interval Acc 1YB Sold Amount`=%f ,
                     `Part $db_interval Acc 1YB Profit`=%f ,
                     `Part $db_interval Acc 1YB Profit After Storing`=%f ,
                     `Part $db_interval Acc 1YB Sold`=%f ,
                     `Part $db_interval Acc 1YB Margin`=%s where
                     `Part SKU`=%d "
				, $this->data["Part $db_interval Acc 1YB Required"]
				, $this->data["Part $db_interval Acc 1YB Provided"]
				, $this->data["Part $db_interval Acc 1YB Given"]
				, $this->data["Part $db_interval Acc 1YB Sold Amount"]
				, $this->data["Part $db_interval Acc 1YB Profit"]
				, $this->data["Part $db_interval Acc 1YB Profit After Storing"]
				, $this->data["Part $db_interval Acc 1YB Sold"]
				, $this->data["Part $db_interval Acc 1YB Margin"]

				, $this->id);

			$this->db->exec($sql);


			$this->data["Part $db_interval Acc 1YD Required"]=($this->data["Part $db_interval Acc 1YB Required"]==0?0:($this->data["Part $db_interval Acc Required"]-$this->data["Part $db_interval Acc 1YB Required"])/$this->data["Part $db_interval Acc 1YB Required"]);
			$this->data["Part $db_interval Acc 1YD Provided"]=($this->data["Part $db_interval Acc 1YB Provided"]==0?0:($this->data["Part $db_interval Acc Provided"]-$this->data["Part $db_interval Acc 1YB Provided"])/$this->data["Part $db_interval Acc 1YB Provided"]);
			$this->data["Part $db_interval Acc 1YD Given"]=($this->data["Part $db_interval Acc 1YB Given"]==0?0:($this->data["Part $db_interval Acc Given"]-$this->data["Part $db_interval Acc 1YB Given"])/$this->data["Part $db_interval Acc 1YB Given"]);
			$this->data["Part $db_interval Acc 1YD Sold Amount"]=($this->data["Part $db_interval Acc 1YB Sold Amount"]==0?0:($this->data["Part $db_interval Acc Sold Amount"]-$this->data["Part $db_interval Acc 1YB Sold Amount"])/$this->data["Part $db_interval Acc 1YB Sold Amount"]);
			$this->data["Part $db_interval Acc 1YD Profit"]=($this->data["Part $db_interval Acc 1YB Profit"]==0?0:($this->data["Part $db_interval Acc Profit"]-$this->data["Part $db_interval Acc 1YB Profit"])/$this->data["Part $db_interval Acc 1YB Profit"]);
			$this->data["Part $db_interval Acc 1YD Profit After Storing"]=($this->data["Part $db_interval Acc 1YB Profit After Storing"]==0?0:($this->data["Part $db_interval Acc Profit After Storing"]-$this->data["Part $db_interval Acc 1YB Profit After Storing"])/$this->data["Part $db_interval Acc 1YB Profit After Storing"]);
			$this->data["Part $db_interval Acc 1YD Sold"]=($this->data["Part $db_interval Acc 1YB Sold"]==0?0:($this->data["Part $db_interval Acc Sold"]-$this->data["Part $db_interval Acc 1YB Sold"])/$this->data["Part $db_interval Acc 1YB Sold"]);
			$this->data["Part $db_interval Acc 1YD Margin"]=($this->data["Part $db_interval Acc 1YB Margin"]==0?0:($this->data["Part $db_interval Acc Margin"]-$this->data["Part $db_interval Acc 1YB Margin"])/$this->data["Part $db_interval Acc 1YB Margin"]);


			$sql=sprintf("update `Part Data` set
                     `Part $db_interval Acc 1YD Required`=%f ,
                     `Part $db_interval Acc 1YD Provided`=%f,
                     `Part $db_interval Acc 1YD Given`=%f ,
                     `Part $db_interval Acc 1YD Sold Amount`=%f ,
                     `Part $db_interval Acc 1YD Profit`=%f ,
                     `Part $db_interval Acc 1YD Profit After Storing`=%f ,
                     `Part $db_interval Acc 1YD Sold`=%f ,
                     `Part $db_interval Acc 1YD Margin`=%s where
                     `Part SKU`=%d "
				, $this->data["Part $db_interval Acc 1YD Required"]
				, $this->data["Part $db_interval Acc 1YD Provided"]
				, $this->data["Part $db_interval Acc 1YD Given"]
				, $this->data["Part $db_interval Acc 1YD Sold Amount"]
				, $this->data["Part $db_interval Acc 1YD Profit"]
				, $this->data["Part $db_interval Acc 1YD Profit After Storing"]
				, $this->data["Part $db_interval Acc 1YD Sold"]
				, $this->data["Part $db_interval Acc 1YD Margin"]

				, $this->id);

			$this->db->exec($sql);


			//print "$sql\n";


		}


	}







	function update_available_forecast() {

		$this->load_acc_data();


		// -------------- simple forecast -------------------------

		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type` like 'Associate' order by `Date` desc"
			, $this->id);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$date=$row['Date'];
			$interval=(date('U')-strtotime($date))/3600/24;
		} else
			$interval=0;

		if ($this->data['Part Current Stock']=='' or $this->data['Part Current Stock']<0) {
			$this->data['Part Days Available Forecast']=0;
			$this->data['Part XHTML Available For Forecast']='?';
		}
		elseif ($this->data['Part Current Stock']==0) {
			$this->data['Part Days Available Forecast']=0;
			$this->data['Part XHTML Available For Forecast']=0;
		}
		else {

			if ($this->data['Part 1 Year Acc Required']>0) {
				if ($interval>(365)) {
					$interval=365;
				}

				$this->data['Part Days Available Forecast']=$interval*$this->data['Part Current Stock']/$this->data['Part 1 Year Acc Required'];
				$this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast'], 0).' '._('d');
			}
			elseif ($this->data['Part 1 Quarter Acc Required']>0) {



				// print $this->data['Part 1 Quarter Acc Required']."xxxx\n";
				if ($interval>(365/4)) {
					$interval=365/4;
				}
				//print $this->data['Part 1 Quarter Acc Required']/$interval;


				$this->data['Part Days Available Forecast']=$interval*$this->data['Part Current Stock']/$this->data['Part 1 Quarter Acc Required'];
				$this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast'], 0).' '._('d');
			}
			else {

				$from_since=(date('U')-strtotime($this->data['Part Valid From'])/86400);
				if ($from_since<($this->data['Part Excess Availability Days Limit']/2)) {
					$forecast=$this->data['Part Excess Availability Days Limit']-1;
				}else {
					$forecast=$this->data['Part Excess Availability Days Limit']+$from_since;
				}



				$this->data['Part Days Available Forecast']=$forecast;
				$this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast'], 0).' '._('d');




			}





		}

		$sql=sprintf("update `Part Dimension` set `Part Days Available Forecast`=%s,`Part XHTML Available for Forecast`=%s where `Part SKU`=%d", $this->data['Part Days Available Forecast'], prepare_mysql($this->data['Part XHTML Available For Forecast']), $this->id );
		//print $sql;
		mysql_query($sql);

	}


	function update_days_until_out_of_stock() {
		$this->get_days_until_out_of_stock();
	}


	function get_days_until_out_of_stock() {

		if ($this->data['Part Current Stock']==0) {
			$days=0;
			$days_formatted='0';
			return array($days, $days_formatted);
		}


		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type` like 'Associate' order by `Date` desc"
			, $this->id);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$date=$row['Date'];
			$interval=(date('U')-strtotime($date))/3600/24;
			if ($interval<21) {
				$qty=$this->data['Part Total Acc Provided']+$this->data['Part Total Acc Lost'];
				if ($interval!=0) {
					$qty_per_day=$qty/$interval;

					if ($qty==0) {
						$days=0;
					}else {
						$days=$this->data['Part Current Stock']/$qty_per_day;
					}

				}else {
					$days=100;

				}

				$days_formatted=$days.' '._('days');


				return array($days, $days_formatted);

			}


		} else {
			$days=0;
			$days_formatted='ND';
			return array($days, $days_formatted);
		}

		//include_once('class.TimeSeries.php');
		/*

		$sql=sprintf("select `First Day` from kbase.`Week Dimension` where `Year Week`=%s",date("YW"));
		$res=mysql_query($sql);
		$no_data=true;
		if ($row=mysql_fetch_array($res)) {
			$date=date("Y-m-d",strtotime($row['First Day'].' -1 day'));
		}
		list($stock,$value)=$this->get_stock($date);
		print "$stock,$value\n";



		// $tm=new TimeSeries(array('m','part sku '.$row['Part SKU']));
		//  $tm->get_values();$tm->save_values();
		//  $tm->forecast();

		$sql=sprintf("select `Time Series Value` from `Time Series Dimension` where `Time Series Frequency`='Weekly' and `Times Series Name`='SkuS' and `Time Series Name Key`=%d  and `Time Series Type`='Forecast' order by `Time Series Date`",$this->id);


		$resmysql_query($sql);
		$future_stock='';
		while ($row=mysql_fetch_array($res)) {

		}
*/



	}


	function update_estimated_future_cost() {
		list($avg_cost, $min_cost)=$this->get_estimated_future_cost();



		$sql=sprintf("update `Part Dimension` set `Part Average Future Cost Per Unit`=%s,`Part Minimum Future Cost Per Unit`=%s where `Part SKU`=%d "
			, prepare_mysql($avg_cost)
			, prepare_mysql($min_cost)
			, $this->id);

		//print "$sql\n";
		mysql_query($sql);
	}


	function get_formatted_unit_cost($date=false) {

		return money($this->data['Part Cost'] );
	}






	function get_estimated_future_cost() {
		$sql=sprintf("select min(`Supplier Product Cost Per case`*`Supplier Product Units Per Part`/`Supplier Product Units Per case`) as min_cost ,avg(`Supplier Product Cost Per case`*`Supplier Product Units Per Part`/`Supplier Product Units Per case`) as avg_cost   from `Supplier Product Part List` SPPL left join  `Supplier Product Part Dimension` SPPD on (  SPPL.`Supplier Product Part Key`=SPPD.`Supplier Product Part Key`)    left join  `Supplier Product Dimension` SPD  on (SPPD.`Supplier Product ID`=SPD.`Supplier Product ID`)      where `Part SKU`=%d and `Supplier Product Part Most Recent`='Yes'", $this->sku);
		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if (is_numeric($row['avg_cost']))
				$avg_cost=$row['avg_cost'];
			else
				$avg_cost='';
			if (is_numeric($row['min_cost']))
				$min_cost=$row['min_cost'];
			else
				$min_cost='';

		} else {
			$avg_cost='';
			$min_cost='';
		}

		// print "($avg_cost,$min_cost\n";
		return array($avg_cost, $min_cost);

	}


	function update_used_in() {
		$used_in_products='';
		$raw_used_in_products='';
		$sql=sprintf("select `Store Code`,PD.`Product ID`,`Product Code` from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` PD on (PD.`Product ID`=PPD.`Product ID`) left join `Store Dimension`  on (PD.`Product Store Key`=`Store Key`)  where PPL.`Part SKU`=%d and `Product Part Most Recent`='Yes' and `Product Record Type`='Normal' order by `Product Code`,`Store Code`", $this->data['Part SKU']);
		$result=mysql_query($sql);
		//   print "$sql\n";
		$used_in=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


			if (!array_key_exists(strtolower($row['Product Code']), $used_in))
				$used_in[strtolower($row['Product Code'])]=array();
			if (!array_key_exists($row['Store Code'], $used_in[strtolower($row['Product Code'])]))
				$used_in[strtolower($row['Product Code'])][$row['Store Code']]=array();
			$used_in[strtolower($row['Product Code'])][$row['Store Code']][$row['Product ID']]=1;

		}
		//print_r($used_in);
		foreach ($used_in as $code=>$store_data) {
			$raw_used_in_products.=' '.$code;
			$used_in_products.=sprintf(', <a href="product.php?code=%s">%s</a>', $code, $code);
			$used_in_products_2='';
			foreach ($store_data as $store_code=>$product_id_data) {
				foreach ($product_id_data as $product_id=>$tmp) {
					$used_in_products_2.=sprintf(',<a href="product.php?pid=%d">%s</a>', $product_id, $store_code);
				}
			}
			$used_in_products_2=preg_replace('/^,/', '', $used_in_products_2);
			$used_in_products.=" ($used_in_products_2)";

		}

		//$used_in_products.=sprintf(', <a href="product.php?code=%s">%s</a>',$row['Product Code'],$row['Product Code']);
		//$raw_used_in_products=' '.$row['Product Code'];

		$used_in_products=preg_replace('/^, /', '', $used_in_products);
		$sql=sprintf("update `Part Dimension` set `Part XHTML Currently Used In`=%s ,`Part Currently Used In`=%s  where `Part SKU`=%d",
			prepare_mysql(_trim($used_in_products)),
			prepare_mysql(_trim($raw_used_in_products)),
			$this->id);
		//  print "$sql\n";
		mysql_query($sql);
	}


	function wrap_transactions() {

		$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where  `Part SKU`=%d  group by `Location Key`  ", $this->sku);
		$locations=array();
		$res2=mysql_query($sql);
		while ($row2=mysql_fetch_array($res2)) {
			$locations[$row2['Location Key']]=$row2['Location Key'];

		}

		if (count($locations)==0)return;


		foreach ($locations as $location_key) {
			/*
			$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`,`Inventory Transaction Key`   ",$this->sku,$location_key);
			//print "$sql\n";
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				if ($row3['Inventory Transaction Type']=='Associate') {
					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Associate') and `Date`=%s and `Location Key`=%d  "
						,$this->sku
						,prepare_mysql($row3['Date'])
						,$location_key
					);
					// print "$sql\n";
					mysql_query($sql);
				}
			}

			$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc ,`Inventory Transaction Key` desc ",$this->sku,$location_key);
			$last_itf_date='none';
			$res3=mysql_query($sql);
			//print "$sql\n";
			if ($row3=mysql_fetch_array($res3)) {
				if ($row3['Inventory Transaction Type']=='Disassociate') {
					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Disassociate') and `Date`=%s and `Location Key`=%d  "
						,$this->sku
						,prepare_mysql($row3['Date'])
						,$location_key
					);
					//print "$sql\n";
					mysql_query($sql);
				}
			}


*/


			$sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date`' , $this->sku, $location_key);
			$first_audit_date='none';
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				$first_audit_date=($row3['Inventory Audit Date']);
			}
			//   print "\n$sql\n";
			$sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`  ", $this->sku, $location_key);
			$first_itf_date='none';
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				$first_itf_date=($row3['Date']);
			}
			// print "$sql\n";
			//print "R: $first_audit_date $first_itf_date \n ";
			if ($first_audit_date=='none' and $first_itf_date=='none') {
				//    print "\nError1 : Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']."  \n";
				//    exit;
				//    return;
				$first_date=$this->data['Part Valid From'];
				// print "\nError1 : Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']." ".$this->data['Part Valid From']." \n";

			}
			elseif ($first_audit_date=='none') {
				$first_date=$first_itf_date;
			}
			elseif ($first_itf_date=='none') {
				$first_date=$first_audit_date;
			}
			else {
				if (strtotime($first_itf_date)< strtotime($first_audit_date) )
					$first_date=$first_itf_date;
				else
					$first_date=$first_audit_date;

			}

			// $first_date=date("Y-m-d H:i:s",strtotime($first_date." -1 second"));

			//   print $first_date;


			$replace_associate=true;
			$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Date`=%d and `Inventory Transaction Type` like 'Associate'   ",
				$this->sku,
				$location_key,
				prepare_mysql($first_date)
			);
			//print "$sql\n";
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				$replace_associate=false;
			}




			$part_location=new PartLocation($this->sku.'_'.$location_key);
			if ($replace_associate) {





				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate') and `Part SKU`=%d and `Location Key`=%d order by `Date`  limit 1 "
					, $this->sku
					, $location_key
				);
				$this->db->exec($sql);

				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Audit') and `Note` like '%%Part associated with location%%'   and  `Part SKU`=%d and `Location Key`=%d  order by `Date` limit 1 "
					, $this->sku
					, $location_key
				);
				$this->db->exec($sql);
				//print $sql;
				$first_date=date("Y-m-d H:i:s", strtotime($first_date." -1 second"));
				$part_location->associate(array('date'=>$first_date));
				$this->update_valid_from($first_date);
			}








			if ($this->data['Part Status']=='Not In Use') {

				$sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date` desc' , $this->sku, $location_key);
				$last_audit_date='none';
				$res3=mysql_query($sql);
				if ($row3=mysql_fetch_array($res3)) {
					$last_audit_date=($row3['Inventory Audit Date']);
				}

				$sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc  ", $this->sku, $location_key);
				$last_itf_date='none';
				$res3=mysql_query($sql);
				if ($row3=mysql_fetch_array($res3)) {
					$last_itf_date=($row3['Date']);
				}
				//print "$sql\n";

				if ($last_audit_date=='none' and $last_itf_date=='none') {
					print "\nError2: Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']."  \n";
					return;
				}
				elseif ($last_audit_date=='none') {
					$last_date=$last_itf_date;
				}


				elseif ($last_itf_date=='none') {

					$last_date=$last_audit_date;
				}
				else {
					if (strtotime($last_itf_date)>strtotime($last_audit_date) )
						$last_date=$last_itf_date;
					else
						$last_date=$last_audit_date;

				}



				$replace_disassociate=true;
				$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Date`=%d and `Inventory Transaction Type` like 'Disassociate'   ",
					$this->sku,
					$location_key,
					prepare_mysql($last_date)
				);
				//print "$sql\n";
				$res3=mysql_query($sql);
				if ($row3=mysql_fetch_array($res3)) {
					$replace_disassociate=false;
				}



				if ($replace_disassociate) {


					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Disassociate') and `Part SKU`=%d and `Location Key`=%d order by `Date` desc limit 1 "
						, $this->sku
						, $location_key
					);
					mysql_query($sql);

					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Audit') and `Note` like '%%disassociate %%'   and  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc limit 1 "
						, $this->sku
						, $location_key
					);
					mysql_query($sql);


					//$part_location->audit(0,_('Audit due to discontinued'),$last_date);

					$last_date=date("Y-m-d H:i:s", strtotime($last_date." +1 second"));
					$data=array('Date'=>$last_date, 'Note'=>_('Discontinued'));
					//print_r($data);

					$part_location->disassociate($data);

				}
				$this->update_valid_to($last_date);



			}
			$this->update_stock();


		}



		//Todo wrap by valid_dates


	}


	function get_description() {
		return $this->data['Part Unit Description'];
	}


	function get_product_ids($date=false) {

		if (!$date) {
			return $this->get_current_product_ids();
		}
		$product_ids=array();

		$sql=sprintf("select  `Product ID` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and  `Product Part Valid From`<=%s  and `Product Part Most Recent`='Yes'  "
			, $this->sku
			, prepare_mysql($date)

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_ids[$row['Product ID']]= $row['Product ID'];
		}

		$sql=sprintf("select  `Product ID` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and `Product Part Valid From`<=%s  and `Product Part Valid To`>=%s and `Product Part Most Recent`='No'  "
			, $this->sku
			, prepare_mysql($date)
			, prepare_mysql($date)
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_ids[$row['Product ID']]= $row['Product ID'];
		}

		return $product_ids;
	}


	function get_current_product_ids() {
		$sql=sprintf("select `Product Part Dimension`.`Product ID` from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d and `Product Part Most Recent`='Yes' ", $this->sku);

		$result=mysql_query($sql);
		$product_ids=array();

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$product_ids[$row['Product ID']]= $row['Product ID'];
		}
		return $product_ids;
	}






	function get_product_part_list($date=false) {

		if (!$date) {
			$sql=sprintf("select * from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and  `Product Part Most Recent`='Yes'  "
				, $this->sku

			)
			;  $result=mysql_query($sql);
			$product_part_list=array();
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$product_part_list[$row['Product Part Key']]= $row;
			}
			return $product_part_list;
		}

		$product_part_list=array();
		$sql=sprintf("select * from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and  `Product Part Valid From`<=%s  and `Product Part Most Recent`='Yes'  "
			, $this->sku
			, prepare_mysql($date)

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_part_list[$row['Product Part Key']]= $row;
		}

		$sql=sprintf("select * from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and `Product Part Valid From`<=%s  and `Product Part Valid To`<=%s and `Product Part Most Recent`='No'  "
			, $this->sku
			, prepare_mysql($date)
			, prepare_mysql($date)
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_part_list[$row['Product Part Key']]= $row;
		}

		return $product_part_list;
	}


	function get_current_product_part_list() {
		$sql=sprintf("select * from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d and `Product Part Most Recent`='Yes' ", $this->data['Part SKU']);
		// print $sql;
		$result=mysql_query($sql);
		$product_part_list=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$product_part_list[$row['Product Part Key']]= $row;
		}
		return $product_part_list;
	}





	function update_product_part_list_dates() {

		$part_from=$this->data['Part Valid From'];
		$part_to=$this->data['Part Valid To'];

		foreach ($this->get_product_ids()   as $pid) {

			$product=new Product('pid', $pid);

			$product_from=$product->data['Product Valid From'];
			$product_to=$product->data['Product Valid To'];
			$store_key=$product->data['Product Store Key'];

			$from=$part_from;

			if ($this->data['Part Status']=='In Use') {
				$to='';
			} else {
				$to=$part_to;
				if (strtotime($to)<strtotime($product_to))
					$to=$product_to;
			}

			$from=$part_from;
			if (strtotime($from)>strtotime($product_from))
				$from=$product_from;

			$sql=sprintf("select  PPD.`Product Part Key` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU`=%d  and PPD.`Product ID`=%d "
				, $this->sku, $pid);
			$res2=mysql_query($sql);

			if ($row2=mysql_fetch_array($res2)) {

				// $status='No';
				// if ($to=='')
				//    $status='Yes';

				$sql=sprintf("update `Product Part Dimension` set `Product Part Valid From`=%s , `Product Part Valid To`=%s  where `Product Part Key`=%d"
					, prepare_mysql($from)
					, prepare_mysql($to)
					, $row2['Product Part Key']
				);

				if (!mysql_query($sql))
					print "$sql\n";

			}

		}





	}


	function update_full_search() {

		$first_full_search=$this->get_sku().' '.strip_tags($this->data['Part Unit Description']);
		$second_full_search=strip_tags($this->data['Part XHTML Currently Supplied By']);

		$sql=sprintf("insert into `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`)  values  (%s,'Part',%d,%s,%s,%s,%s,%s) on duplicate key
                     update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s "
			, 0
			, $this->sku
			, prepare_mysql($first_full_search)
			, prepare_mysql($second_full_search, false)
			, prepare_mysql($this->get_sku(), false)
			, prepare_mysql($this->data['Part Unit Description'], false)
			, prepare_mysql('', false)
			, prepare_mysql($first_full_search)
			, prepare_mysql($second_full_search, false)
			, prepare_mysql($this->get_sku(), false)
			, prepare_mysql($this->data['Part Unit Description'], false)

			, prepare_mysql('', false)
		);
		mysql_query($sql);

	}





	function get_warehouse_keys() {
		$warehouse_keys=array();

		$sql=sprintf("select `Warehouse Key` from `Part Warehouse Bridge` where `Part SKU`=%d",
			$this->sku
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$warehouse_keys[$row['Warehouse Key']]=$row['Warehouse Key'];
		}

		return $warehouse_keys;

	}




	function update_number_transactions() {



		$transactions=array('all_transactions'=>0, 'in_transactions'=>0, 'out_transactions'=>0, 'audit_transactions'=>0, 'oip_transactions'=>0, 'move_transactions'=>0);
		$sql=sprintf("select sum(if(`Inventory Transaction Type` not in ('Move In','Move Out','Associate','Disassociate'),1,0))  as all_transactions , sum(if(`Inventory Transaction Type`='Not Found' or `Inventory Transaction Type` like 'No Dispatched' or `Inventory Transaction Type` like 'Audit',1,0)) as audit_transactions,sum(if(`Inventory Transaction Type`='Move',1,0)) as move_transactions,sum(if(`Inventory Transaction Type` like 'Sale' or `Inventory Transaction Type`='Broken' or  `Inventory Transaction Type` like 'Other Out' or `Inventory Transaction Type` like 'Lost',1,0)) as out_transactions, sum(if(`Inventory Transaction Type`='Order In Process',1,0)) as oip_transactions, sum(if(`Inventory Transaction Type` like 'In',1,0)) as in_transactions from `Inventory Transaction Fact` where `Part SKU`=%d",
			$this->sku);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Part Transactions']=$row['all_transactions'];
			$this->data['Part Transactions In']=$row['in_transactions'];
			$this->data['Part Transactions Out']=$row['out_transactions'];
			$this->data['Part Transactions Audit']=$row['audit_transactions'];
			$this->data['Part Transactions OIP']=$row['oip_transactions'];
			$this->data['Part Transactions Move']=$row['move_transactions'];

			$sql=sprintf("Update `Part Dimension` set `Part Transactions`=%d ,`Part Transactions In`=%d,`Part Transactions Out`=%d ,`Part Transactions Audit`=%d,`Part Transactions OIP`=%d ,`Part Transactions Move`=%d where `Part SKU`=%d ",
				$this->data['Part Transactions'],
				$this->data['Part Transactions In'],
				$this->data['Part Transactions Out'],
				$this->data['Part Transactions Audit'],
				$this->data['Part Transactions OIP'],
				$this->data['Part Transactions Move'],
				$this->sku

			);
			//print "$sql\n";
			mysql_query($sql);
		}


	}


	function delete($metadata=false) {




		$sql=sprintf('insert into `Part Deleted Dimension`  (`Part Deleted Key`,`Part Deleted Reference`,`Part Deleted Date`,`Part Deleted Metadata`) values (%d,%s,%s,%s) ',
			$this->id,
			prepare_mysql($this->get('Part Reference')),
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql(gzcompress(json_encode($this->data), 9))

		);
		$this->db->exec($sql);




		$sql=sprintf('delete from `Part Dimension`  where `Part SKU`=%d ',
			$this->id
		);
		$this->db->exec($sql);


		$history_data=array(
			'History Abstract'=>sprintf(_("Part record %s deleted"), $this->data['Part Reference']),
			'History Details'=>'',
			'Action'=>'deleted'
		);

		$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());




		$this->deleted=true;


		$sql=sprintf('select `Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Part SKU`=%d  ', $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$supplier_part=get_object('Supplier Part', $row['Supplier Part Key']);
				$supplier_part->delete();
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


	function get_categories() {


		$part_categories=array();


		$sql=sprintf("select C.`Category Key`,`Category Label` from `Category Dimension` C left join `Category Bridge` B on (B.`Category Key`=C.`Category Key`) where `Subject`='Part' and `Subject Key`=%d and `Category Branch Type`='Head'", $this->sku);
		// print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {
			$part_categories[]=array('category_key'=>$row['Category Key'], 'category_label'=>$row['Category Label']);
		}

		return $part_categories;
	}


	function get_MSDS_attachment_key() {



		if (!$this->data['Part MSDS Attachment Bridge Key']) {
			return 0;
		}
		$attachment_key=0;
		$sql=sprintf("select `Attachment Key` from `Attachment Bridge` where `Subject`='Part MSDS' and `Subject Key`=%d ",
			$this->id
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$attachment_key=$row['Attachment Key'];
		}
		return $attachment_key;

	}


	function delete_MSDS_attachment() {

		//print_r($this->data);

		if ($this->data['Part MSDS Attachment Bridge Key']=='') {
			$this->msg=_('No file is set up as MSDS');
			return;
		}

		$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d",
			$this->data['Part MSDS Attachment Bridge Key']
		);
		mysql_query($sql);
		//print "$sql  xx\n";

		$attach=new Attachment($this->get_MSDS_attachment_key());
		$attach->delete();
		$attach_info=$this->data['Part MSDS Attachment XHTML Info'];
		$sql=sprintf("update `Part Dimension` set `Part MSDS Attachment Bridge Key`=0, `Part MSDS Attachment XHTML Info`='' where `Part SKU`=%d ",
			$this->sku

		);
		$this->db->exec($sql);
		$this->data['Part MSDS Attachment XHTML Info']='';
		$this->data['Part MSDS Attachment Bridge Key']='';
		$history_data=array(
			'History Abstract'=>_('MSDS Attachment deleted').'.',
			'History Details'=>$attach_info,
			'Action'=>'edited',
			'Direct Object'=>'Attachment',
			'Prepostion'=>'',
			'Indirect Object'=>$this->table_name,
			'Indirect Object Key'=>$this->sku
		);

		$history_key=$this->add_subject_history($history_data, true, 'No', 'Changes');
	}


	function update_MSDS_attachment($attach, $filename, $caption) {

		if (!is_object($attach)) {
			$this->error=true;
			$this->msg='error attach not an object';
			return;
		}elseif (!$attach->id) {
			$this->error=true;
			$this->msg='error attach not found';
			return;

		}

		//print $attach->id."att id \n";


		if ($attach->id==$this->get_MSDS_attachment_key()) {
			$this->msg=_('This file already set up as MSDS');
			return;
		}

		if ($this->data['Part MSDS Attachment Bridge Key']) {
			$this->delete_MSDS_attachment();

		}



		$sql=sprintf("insert into `Attachment Bridge` (`Attachment Key`,`Subject`,`Subject Key`,`Attachment File Original Name`,`Attachment Caption`) values (%d,'Part MSDS',%d,%s,%s)",
			$attach->id,
			$this->sku,
			prepare_mysql($filename),
			prepare_mysql($caption)
		);
		mysql_query($sql);
		//print $sql;

		$attach_bridge_key=mysql_insert_id();
		$attach_info=$attach->get_abstract($filename, $caption, $attach_bridge_key);

		if ($this->data['Part MSDS Attachment Bridge Key']) {
			$history_data=array(
				'History Abstract'=>_('MSDS Attachment replaced').'. '.$attach_info,
				'History Details'=>$attach->get_details(),
				'Action'=>'edited',
				'Direct Object'=>'Attachment',
				'Prepostion'=>'',
				'Indirect Object'=>$this->table_name,
				'Indirect Object Key'=>$this->sku
			);

		}else {
			$history_data=array(
				'History Abstract'=>_('MSDS Attachment uploaded').'; '.$attach_info,
				'History Details'=>$attach->get_details(),
				'Action'=>'associated',
				'Direct Object'=>'Attachment',
				'Prepostion'=>'',
				'Indirect Object'=>$this->table_name,
				'Indirect Object Key'=>$this->sku
			);

		}


		$history_key=$this->add_subject_history($history_data, true, 'No', 'Changes');

		$sql=sprintf("update `Part Dimension` set `Part MSDS Attachment Bridge Key`=%d, `Part MSDS Attachment XHTML Info`=%s where `Part SKU`=%d ",
			$attach_bridge_key,
			prepare_mysql($attach_info),
			$this->sku

		);
		$this->db->exec($sql);
		$this->data['Part MSDS Attachment Bridge Key']=$attach_bridge_key;
		$this->data['Part MSDS Attachment XHTML Info']=$attach_info;


		$this->updated=true;





	}



	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Part SKU':
			$label=_('SKU');
			break;

		case 'Part Reference':
			$label=_('reference');
			break;
		case 'Part Unit Description':
			$label=_('unit description');
			break;
		case 'Part Package Description':
			$label=_('SKO description');
			break;
		case 'Store Product Price':
			$label=_('Price');
			break;

		case 'Part Package Weight':
			$label=_('SKO weight');
			break;
		case 'Part Package Dimensions':
			$label=_('SKO dimensions');
			break;
		case 'Part Unit Weight':
			$label=_('unit weight');
			break;
		case 'Part Unit Dimensions':
			$label=_('unit dimensions');
			break;
		case 'Part Tariff Code':
			$label=_('tariff code');
			break;

		case 'Part Duty Rate':
			$label=_('duty rate');
			break;

		case 'Part UN Number':
			$label=_('UN number');
			break;

		case 'Part UN Class':
			$label=_('UN class');
			break;
		case 'Part Packing Group':
			$label=_('packing group');
			break;
		case 'Part Proper Shipping Name':
			$label=_('proper shipping name');
			break;
		case 'Part Hazard Indentification Number':
			$label=_('hazard indentification number');
			break;
		case 'Part Materials':
			$label=_('Materials/Ingredients');
			break;
		case 'Part Origin Country Code':
			$label=_('country of origin');
			break;
		case 'Part Units':
			$label=_('units per SKO');
			break;
		case 'Part Barcode Number':
			$label=_('barcode');
			break;


		default:
			$label=$field;

		}

		return $label;

	}


	function get_products_data($with_objects=false) {

		include_once 'class.Product.php';

		$sql=sprintf("select `Linked Fields`,`Store Product Key`,`Parts Per Product`,`Note` from `Store Product Part Bridge` where `Part SKU`=%d ",
			$this->id
		);
		$products_data=array();
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$product_data=$row;
				if ($product_data['Linked Fields']=='') {
					$product_data['Linked Fields']=array();
					$product_data['Number Linked Fields']=0;
				}else {
					$product_data['Linked Fields']=json_decode($row['Linked Fields'], true);
					$product_data['Number Linked Fields']=count($product_data['Linked Fields']);
				}
				if ($with_objects) {
					$product_data['Product']=new Product($row['Store Product Key']);
				}
				$products_data[]=$product_data;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $products_data;
	}


}
