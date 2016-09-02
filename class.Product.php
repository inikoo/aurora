<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Based in 2009 class.Product.php
 Created: 16 February 2016 at 22:35:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'class.Asset.php';

class Product extends Asset{

	function __construct($arg1=false, $arg2=false, $arg3=false) {

		global $db;
		$this->db=$db;


		$this->table_name='Product';
		$this->ignore_fields=array('Product ID');
		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return ;
		}
		if (preg_match('/^find/i', $arg1)) {

			$this->find($arg2, $arg3);
			return;
		}

		if (preg_match('/create|new/i', $arg1) and is_array($arg2) ) {

			$this->find($arg2, 'create');
			return;
		}
		$this->get_data($arg1, $arg2, $arg3);



	}


	function get_data($key, $id, $aux_id=false) {

		if ($key=='id') {
			$sql=sprintf("select * from `Product Dimension` where `Product ID`=%d", $id);
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Product ID'];
				$this->historic_id=$this->data['Product Current Key'];
			}
		}elseif ($key=='store_code') {
			$sql=sprintf("select * from `Product Dimension` where `Product Store Key`=%s  and `Product Code`=%s", $id, prepare_mysql($aux_id));
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Product ID'];
				$this->historic_id=$this->data['Product Current Key'];
			}
		}elseif ($key=='historic_key') {
			$sql=sprintf("select * from `Product History Dimension` where `Product Key`=%s", $id);
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->historic_id=$this->data['Product Key'];
				$this->id=$this->data['Product ID'];


				$sql=sprintf("select * from `Product Dimension` where `Product ID`=%d", $this->data['Product ID']);
				if ($row = $this->db->query($sql)->fetch()) {

					foreach ($row as $key=>$value) {
						$this->data[$key]=$value;
					}
				}



			}
		}
		else {
			sdasdas();
			exit ("wrong id in class.product get_data :$key  \n");
			return;
		}


		$this->get_store_data();
	}


	function get_store_data() {

		$sql=sprintf('select * from `Store Dimension` where `Store Key`=%d ', $this->data['Product Store Key']);
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data[$key]=$value;
			}
		}



	}


	function get_parts($scope='keys') {


		if ($scope=='objects') {
			include_once 'class.Part.php';
		}

		$sql=sprintf('select `Product Part Part SKU` as `Part SKU` from `Product Part Bridge` where `Product Part Product ID`=%d ', $this->id);

		$parts=array();

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if ($scope=='objects') {
					$parts[$row['Part SKU']]=new Part($row['Part SKU']);
				}else {
					$parts[$row['Part SKU']]=$row['Part SKU'];
				}


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $parts;
	}


	function get_parts_data($with_objects=false) {

		include_once 'class.Part.php';

		$sql=sprintf("select `Product Part Key`,`Product Part Linked Fields`,`Product Part Part SKU`,`Product Part Ratio`,`Product Part Note` from `Product Part Bridge` where `Product Part Product ID`=%d ",
			$this->id
		);
		$parts_data=array();
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$part_data=$row;

				$part_data=array(
					'Key'=>$row['Product Part Key'],
					'Ratio'=>$row['Product Part Ratio'],
					'Note'=>$row['Product Part Note'],
					'Part SKU'=>$row['Product Part Part SKU'],
				);


				if ($row['Product Part Linked Fields']=='') {
					$part_data['Linked Fields']=array();
					$part_data['Number Linked Fields']=0;
				}else {
					$part_data['Linked Fields']=json_decode($row['Product Part Linked Fields'], true);
					$part_data['Number Linked Fields']=count($part_data['Linked Fields']);
				}
				if ($with_objects) {
					$part_data['Part']=new Part($row['Product Part Part SKU']);
				}


				$parts_data[]=$part_data;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $parts_data;
	}


	function get($key, $arg1='') {


		include_once 'utils/natural_language.php';

		list($got, $result)=$this->get_asset_common($key, $arg1);
		if ($got)return $result;

		if (!$this->id)
			return;

		switch ($key) {

		case 'Price':

			$price= money($this->data['Product Price'], $this->data['Store Currency Code']);

			if ($this->data['Product Units Per Case']!=1) {

				$price.=' ('.money($this->data['Product Price']/$this->data['Product Units Per Case'], $this->data['Store Currency Code']).'/'.$this->data['Product Unit Label'].')';


				//$price.=' ('.sprintf(_('%s per %s'), money($this->data['Product Price']/$this->data['Product Units Per Case'], $this->data['Store Currency Code']), $this->data['Product Unit Label']).')';
			}

			$unit_margin=$this->data['Product Price']-$this->data['Product Cost'];
			$price_other_info=sprintf(_('margin %s'), percentage($unit_margin, $this->data['Product RRP']));



			$price_other_info=preg_replace('/^, /', '', $price_other_info);
			if ($price_other_info!='') {
				$price.=' <span class="'.($unit_margin<0?'error':'').'  discreet padding_left_10">'.$price_other_info.'</span>';
			}


			return $price;
			break;
		case 'Unit Price':
			return money($this->data['Product Price']/$this->data['Product Units Per Case'], $this->data['Store Currency Code']);
			break;
		case 'Formatted Per Outer':
			return _('per outer');
			break;
		case 'RRP':
			if ($this->data['Product RRP']=='')return '';
			return money($this->data['Product RRP'], $this->data['Store Currency Code']);
			break;
		case 'Unit RRP':

			if ($this->data['Product RRP']=='')return '';

			$rrp= money($this->data['Product RRP']/$this->data['Product Units Per Case'], $this->data['Store Currency Code']);
			if ($this->get('Product Units Per Case')!=1) {
				$rrp.='/'.$this->get('Product Unit Label');
			}



			$unit_margin=$this->data['Product RRP']-$this->data['Product Price'];
			$rrp_other_info=sprintf(_('margin %s'), percentage($unit_margin, $this->data['Product RRP']));



			$rrp_other_info=preg_replace('/^, /', '', $rrp_other_info);
			if ($rrp_other_info!='') {
				$rrp.=' <span class="'.($unit_margin<0?'error':'').'  discreet padding_left_10">'.$rrp_other_info.'</span>';
			}
			return $rrp;
			break;



			return money($this->data['Product RRP']/$this->data['Product Units Per Case'], $this->data['Store Currency Code']);
			break;
		case 'Product Unit RRP':
			return $this->data['Product RRP']/$this->data['Product Units Per Case'];
			break;

		case 'Unit Type':
			if ($this->data['Product Unit Type']=='')return '';
			return _($this->data['Product Unit Type']);

			/*
			if ($this->data['Product Unit Type']=='')return '';
			$unit_type_data=json_decode($this->data['Product Unit Type'], true);
			$unit_type_key=key($unit_type_data);

			$unit_type_value=$unit_type_data[$unit_type_key];
			$unit_type_key=_($unit_type_key);
			if ($unit_type_key!=$unit_type_value) {
				return "$unit_type_value ($unit_type_key)";
			}else {
				return $unit_type_key;
			}
*/
			break;
		case 'Parts':
			$parts='';



			$parts_data=$this->get_parts_data(true);


			foreach ($parts_data as $part_data) {

				$parts.=', '.number($part_data['Ratio']).'x <span class="link" onClick="change_view(\'part/'.$part_data['Part']->id.'\')">'.$part_data['Part']->get('Reference').'</span>';
				if ($part_data['Note']!='') {
					$parts.=' <span class="very_discreet">('.$part_data['Note'].')</span>';
				}

			}

			if ($parts=='') {
				$parts='<span class="discret">'._('No parts assigned').'</span>';
			}
			$parts=preg_replace('/^, /', '', $parts);
			return $parts;

			break;
		case 'xOuter Weight':
			return weight($this->data['Product Outer Weight']);


		case 'xProduct Outer Weight':
			$str = number_format($this->data['Product Outer Weight'], 4);

			return preg_replace('/(?<=\d{3})0+$/', '', $str);

		case 'Product Price':
			$str = number_format($this->data['Product Price'], 4);

			return preg_replace('/(?<=\d{2})0+$/', '', $str);
			break;
		case 'Price':
			return money($this->data['Product Price'], $this->data['Store Currency Code']);
			break;
		default:
			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Product '.$key, $this->data))
				return $this->data['Product '.$key];

		}


	}


	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Product ID':
			$label=_('id');
			break;

		case 'Product Code':
			$label=_('code');
			break;
		case 'Product Outer Description':
			$label=_('description');
			break;
		case 'Product Unit Description':
			$label=_('unit description');
			break;
		case 'Product Price':
			$label=_('Price');
			break;
		case 'Product Outer Weight':
			$label=_('weight');
			break;
		case 'Product Outer Dimensions':
			$label=_('dimensions');
			break;
		case 'Product Units Per Outer':
			$label=_('retail units per outer');
			break;
		case 'Product Outer Tariff Code':
			$label=_('tariff code');
			break;
		case 'Product Outer Duty Rate':
			$label=_('duty rate');
			break;
		case 'Product Unit Type':
			$label=_('unit type');
			break;
		case 'Product Label in Family':
			$label=_('label in family');
			break;

		case 'Product Unit Weight':
			$label=_('unit weight');
			break;
		case 'Product Unit Dimensions':
			$label=_('unit dimensions');
			break;
		case 'Product Units Per Case':
			$label=_('units per outer');
			break;
		case 'Product Unit Label':
			$label=_('unit label');
			break;
		case 'Product Parts':
			$label=_('parts');
			break;
		case 'Product Name':
			$label=_('unit name');
			break;

		case 'Product Unit RRP':
			$label=_('unit RRP');
			break;
		default:
			$label=$field;

		}

		return $label;

	}


	function find($raw_data, $options) {



		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}


		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}



		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data)) {
				$data[$key]=_trim($value);
			}
		}


		$sql=sprintf("select `Product ID` from `Product Dimension` where  `Product Store Key`=%s and `Product Code`=%s",
			$data['Product Store Key'],
			prepare_mysql($data['Product Code'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->found=true;
				$this->found_key=$row['Product ID'];
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

		include_once 'utils/natural_language.php';


		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=_trim($value);
			}
		}
		$this->editor=$data['editor'];

		if ($this->data['Product Valid From']=='') {
			$this->data['Product Valid From']=gmdate('Y-m-d H:i:s');
		}


		$this->data['Product Code File As']=get_file_as($this->data['Product Code']);

		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";
			if (in_array($key, array('Product Valid To', 'Product Unit Weight', 'Product Outer Weight'))) {
				$values.=','.prepare_mysql($value, true);

			}else {
				$values.=','.prepare_mysql($value, false);
			}
		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Product Dimension` ($keys) values ($values)";
		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->get_data('id', $this->id);

			$sql=sprintf("insert into  `Product DC Data`  (`Product ID`) values (%d) ", $this->id);
			$this->db->exec($sql);

			$sql=sprintf("insert into  `Product Data`  (`Product ID`) values (%d) ", $this->id);
			$this->db->exec($sql);




			$history_data=array(
				'History Abstract'=>sprintf(_('%s product record created'), $this->data['Product Outer Description']),
				'History Details'=>'',
				'Action'=>'created'
			);

			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			$this->new=true;






		}else {
			$this->error=true;
			$this->msg='Error inserting Product record';
		}



	}



	function update_field_switcher($field, $value, $options='', $metadata='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {
		case 'Product Package Dimensions':
		case 'Product Unit Dimensions':


			include_once 'utils/parse_natural_language.php';

			$tag=preg_replace('/ Dimensions$/', '', $field);

			if ($value=='') {
				$dim='';
				$vol='';
			}else {
				$dim=parse_dimensions($value);
				if ($dim=='') {
					$this->error=true;
					$this->msg=sprintf(_("Dimensions can't be parsed (%s)"), $value);
					return;
				}
				$_tmp=json_decode($dim, true);
				$vol=$_tmp['vol'];
			}

			$this->update_field($tag.' Dimensions', $dim, $options);
			

			break;
		
		case 'Product Materials':
			include_once 'utils/parse_materials.php';


			$materials_to_update=array();
			$sql=sprintf('select `Material Key` from `Product Material Bridge` where `Product ID`=%d', $this->id);
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




				$sql=sprintf("delete from `Product Material Bridge` where `Product ID`=%d ", $this->id);
				$this->db->exec($sql);

			}else {

				$materials_data=parse_materials($value, $this->editor);

				$sql=sprintf("delete from `Product Material Bridge` where `Product ID`=%d ", $this->id);

				$this->db->exec($sql);

				foreach ($materials_data as $material_data) {

					if ($material_data['id']>0) {
						$sql=sprintf("insert into `Product Material Bridge` (`Product ID`, `Material Key`, `Ratio`, `May Contain`) values (%d, %d, %s, %s) ",
							$this->id,
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


			$this->update_field('Product Materials', $materials, $options);
			$updated=$this->updated;
			


			$this->updated=$updated;
			break;
		
		
		case 'Product Code':
			$value=_trim($value);

			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Code missing');
				return;
			}

			if (preg_match('/\s/', $value)  ) {
				$this->error=true;
				$this->msg=_("Code can't have spaces");
				return;
			}

			if (preg_match('/\,/', $value)  ) {
				$this->error=true;
				$this->msg=_("Code can't have commas");
				return;
			}

			$sql=sprintf('select count(*) as num from `Product Dimension` where `Product Code`=%s and `Product Store Key`=%d and  `Product Status`!="Discontinued"  and `Product ID`!=%d ',
				prepare_mysql($value),
				$this->get('Product Store Key'),
				$this->id
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					if ($row['num']>0) {
						$this->error=true;
						$this->msg=sprintf(_("Another product has this code (%s)"), $value);
						return;
					}
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}




			$this->update_field($field, $value, $options);
			$updated=$this->updated;
			$this->update_historic_object();
			$this->updated=$updated;

			break;

		case 'Product Name':
			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Unit name missing');
				return;
			}

			$this->update_field($field, $value, $options);
			$updated=$this->updated;
			$this->update_historic_object();
			$this->updated=$updated;

			break;
		case 'Product Unit Label':
			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Unit label missing');
				return;
			}

			$this->update_field($field, $value, $options);

			$this->other_fields_updated=array(
				'Product_Price'=>array(
					'field'=>'Product_Price',
					'render'=>true,
					'value'=>$this->get('Product Price'),
					'formatted_value'=>$this->get('Price'),
				),
				'Product_Unit_RRP'=>array(
					'field'=>'Product_Unit_RRP',
					'render'=>true,
					'value'=>$this->get('Product Unit RRP'),
					'formatted_value'=>$this->get('Unit RRP'),
				),

			);

			break;
		case 'Product Label in Family':

			$this->update_field('Product Special Characteristic', $value, $options);// Migration

			$this->update_field($field, $value, $options);

			break;


		case 'Product Price':


			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Price missing');
				return;
			}

			if (  $value!=''and (    !is_numeric($value) or $value<0  )) {
				$this->error=true;
				$this->msg=sprintf(_('Invalid price (%s)'), $value);
				return;
			}



			$this->update_field($field, $value, $options);

			$this->other_fields_updated=array(

				'Product_Unit_RRP'=>array(
					'field'=>'Product_Unit_RRP',
					'render'=>true,
					'value'=>$this->get('Product Unit RRP'),
					'formatted_value'=>$this->get('Unit RRP'),
				),

			);

			$updated=$this->updated;
			$this->update_historic_object();
			$this->updated=$updated;

			break;


		case 'Product Unit RRP':


			if (  $value!=''and (    !is_numeric($value) or $value<0  )) {
				$this->error=true;
				$this->msg=sprintf(_('Invalid unit RRP (%s)'), $value);
				return;
			}

			if ($value=='') {
				$this->update_field('Product RRP', '', $options);

			}else {
				$this->update_field('Product RRP', $value*$this->data['Product Units Per Case'], $options);

			}






			break;

		case 'Product Units Per Case':
			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Units per outer missing');
				return;
			}

			if (!is_numeric($value) or $value<0  ) {
				$this->error=true;
				$this->msg=sprintf(_('Invalid units per outer (%s)'), $value);
				return;
			}

			$old_value=$this->get('Product Units Per Case');

			$this->update_field('Product Units Per Case', $value, $options);
			$updated=$this->updated;
			if (is_numeric($old_value) and $old_value>0) {
				$rrp_per_unit=$this->get('Product RRP')/$old_value;
				$this->update_field('Product RRP', $rrp_per_unit*$this->get('Product Units Per Case'), $options);

			}




			$this->other_fields_updated=array(
				'Product_Price'=>array(
					'field'=>'Product_Price',
					'render'=>true,
					'value'=>$this->get('Product Price'),
					'formatted_value'=>$this->get('Price'),
				),
				'Product_Unit_RRP'=>array(
					'field'=>'Product_Unit_RRP',
					'render'=>true,
					'value'=>$this->get('Product Unit RRP'),
					'formatted_value'=>$this->get('Unit RRP'),
				),

			);


			$this->update_historic_object();
			$this->updated=$updated;

			break;



		case 'Product Parts':

			$this->update_part_list($value, $options);

			break;
		case 'Product Public':
			if ($value=='Yes' and in_array($this->get('Product Status'), array('Suspended', 'Discontinued')  )) {
				return ;
			}
			$this->update_field($field, $value, $options);
			break;
		case 'Product Outer Dimensions':

			if ($value=='') {
				$dim='';
				$vol='';
			}else {
				$dim=parse_dimensions($value);
				if ($dim=='') {
					$this->error=true;
					$this->msg=_("Package dimensions can't be parsed");
					return;
				}
				$_tmp=json_decode($dim, true);
				$vol=$_tmp['vol'];
			}

			$this->update_field('Product Outer Dimensions', $dim, $options);
			$this->update_field('Product Outer Volume', $vol, $options);


			break;


		case 'Product Family Category Key':
			include_once 'class.Category.php';
			$family=new Category($value);
			$family->associate_subject($this->id);
			$this->update_field($field, $value, 'no_history');

			$sql=sprintf("select C.`Category Key` from `Category Dimension` C left join `Category Bridge` B on (C.`Category Key`=B.`Category Key`) where `Category Root Key`=%d and `Subject Key`=%d and `Subject`='Category' and `Category Branch Type`='Head'",

				$this->data['Store Department Category Key'],
				$family->id
			);
			//print $sql;
			$departmet_key='';
			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$departmet_key=$row['Category Key'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}
			$this->update_field('Product Department Category Key', $departmet_key, 'no_history');


			$this->other_fields_updated=array(
				'Store_Product_Family_Category_Key'=>array(
					'field'=>'Store_Product_Family_Category_Key',
					'render'=>true,
					'value'=>$this->get('Family Category Key'),
					'formatted_value'=>$family->get('Code').', '.$family->get('Label')


				)
			);

		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
		$this->reread();

	}


	function update_part_list($value, $options='') {


		$value=json_decode($value, true);




		$part_list=$this->get_parts_data();

		$old_part_list_keys=array();
		foreach ($part_list as $product_part) {
			$old_part_list_keys[$product_part['Key']]=$product_part['Key'];
		}


		$new_part_list_keys=array();
		foreach ($value as $product_part) {
			if (isset($product_part['Key'])) {
				$new_part_list_keys[$product_part['Key']]=$product_part['Key'];
			}
		}

		if (count(array_diff($old_part_list_keys, $new_part_list_keys))!=0) {

			//print_r($old_part_list_keys);
			//print_r($new_part_list_keys);
			$this->error=true;
			$this->msg=_('Another user updated current part list, refresh and try again');
			return;
		}

		foreach ($value as $product_part) {

			//print_r($product_part);
			if ($product_part['Key']>0) {

				$sql=sprintf('update `Product Part Bridge` set `Product Part Note`=%s where `Product Part Key`=%d and `Product Part Product ID`=%d ',
					prepare_mysql($product_part['Note']),
					$product_part['Key'],
					$this->id
				);

				$updt = $this->db->prepare($sql);
				$updt->execute();
				if ($updt->rowCount()) {
					$this->updated=true;
				}


				if ($product_part['Ratio']==0) {
					$sql=sprintf('delete from `Product Part Bridge` where `Product Part Key`=%d and `Product Part Product ID`=%d ',
						$product_part['Key'],
						$this->id
					);

					$updt = $this->db->prepare($sql);
					$updt->execute();
					if ($updt->rowCount()) {
						$this->updated=true;
					}

				}else {

					$sql=sprintf('update `Product Part Bridge` set `Product Part Ratio`=%f where `Product Part Key`=%d and `Product Part Product ID`=%d ',
						$product_part['Ratio'],
						$product_part['Key'],
						$this->id
					);

					$updt = $this->db->prepare($sql);
					$updt->execute();
					if ($updt->rowCount()) {
						$this->updated=true;
					}
				}

			}
			else {

				if ($product_part['Part SKU']>0) {

					$sql=sprintf('insert into `Product Part Bridge` (`Product Part Product ID`,`Product Part Part SKU`,`Product Part Ratio`,`Product Part Note`) values (%d,%d,%f,%s)',
						$this->id,
						$product_part['Part SKU'],
						$product_part['Ratio'],
						prepare_mysql($product_part['Note'], false)
					);
					//print $sql;
					$this->db->exec($sql);
					$this->updated=true;
				}
			}
		}

		$this->update_availability();


	}


	function update_availability() {


		$sql=sprintf(" select `Part Stock State`,`Part Current On Hand Stock`-`Part Current Stock In Process` as stock,`Part Current Stock In Process`,`Part Current On Hand Stock`,`Product Part Ratio`
		 from     `Product Part Bridge` B left join   `Part Dimension` P   on (P.`Part SKU`=B.`Product Part Part SKU`)   where B.`Product Part Product ID`=%d   ",
			$this->id
		);




		$result=mysql_query($sql);
		$stock=99999999999;
		$tipo='Excess';
		$change=false;
		$stock_error=false;



		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {



				if ($row['Part Stock State']=='Error')
					$tipo='Error';
				elseif ($row['Part Stock State']=='OutofStock' and $tipo!='Error')
					$tipo='OutofStock';
				elseif ($row['Part Stock State']=='VeryLow' and $tipo!='Error' and $tipo!='OutofStock' )
					$tipo='VeryLow';
				else if ($row['Part Stock State']=='Low' and $tipo!='Error' and $tipo!='OutofStock' and $tipo!='VeryLow')
					$tipo='Low';
				elseif ($row['Part Stock State']=='Normal' and $tipo=='Excess' )
					$tipo='Normal';

				if (is_numeric($row['stock']) and is_numeric($row['Product Part Ratio'])  and $row['Product Part Ratio']>0 ) {

					$_part_stock=$row['stock'];
					if ($row['Part Current On Hand Stock']==0  and $row['Part Current Stock In Process']>0 ) {
						$_part_stock=0;
					}

					$_stock=$_part_stock/$row['Product Part Ratio'];
					if ($stock>$_stock) {
						$stock=$_stock;
						$change=true;
					}
				}
				else {

					$stock=0;
					$stock_error=true;
				}


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		if (!$change or $stock_error)
			$stock='';
		if (is_numeric($stock) and $stock<0)
			$stock='';

		$old_web_state=$this->get('Product Web State');

		if ($this->get('Product Status')=='Active') {

			switch ($this->data['Product Web Configuration']) {
			case 'Offline':
				$web_state= 'Offline';
				break;
			case 'Online Force Out of Stock':
				$web_state= 'Out of Stock';
				break;
			case 'Online Force For Sale':
				$web_state= 'For Sale';
				break;
			case 'Online Auto':



				if ($this->get('Product Availability')>0) {
					$web_state= 'For Sale';
				}else {
					$web_state= 'Out of Stock';
				}


				break;

			default:
				$web_state= 'Offline';
				break;
			}

		}else {
			$web_state='Offline';
		}


		$this->update(array(
				'Product Availability'=>$stock,
				'Product Availability State'=>$tipo,
				'Product Web State'=>$web_state,
				//  'Product Available Days Forecast'=>$days_available
			), 'no_history');


		if ( ($old_web_state=='For Sale' and $web_state!='For Sale') or ($old_web_state!='For Sale' and  $web_state=='For Sale' )  ) {

			if (isset($this->editor['User Key'])and is_numeric($this->editor['User Key'])  )
				$user_key=$this->editor['User Key'];
			else
				$user_key=0;

			//------

			$sql=sprintf("select UNIX_TIMESTAMP(`Date`) as date,`Product Availability Key` from `Product Availability Timeline` where `Product ID`=%d  order by `Date`  desc limit 1",
				$this->id
			);



			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$last_record_key=$row['Product Availability Key'];
					$last_record_date=$row['date'];
				}else {
					$last_record_key=false;
					$last_record_date=false;
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}




			$new_date_formated=gmdate('Y-m-d H:i:s');
			$new_date=gmdate('U');

			$sql=sprintf("insert into `Product Availability Timeline`  (`Product ID`,`Store Key`,`Department Key`,`Family Key`,`User Key`,`Date`,`Availability`,`Web State`) values (%d,%d,%d,%d,%d,%s,%s,%s) ",
				$this->id,
				$this->data['Product Store Key'],
				$this->data['Product Main Department Key'],
				$this->data['Product Family Key'],
				$user_key,
				prepare_mysql($new_date_formated),
				prepare_mysql(($web_state=='For Sale'?'Yes':'No')),
				prepare_mysql($web_state)

			);
			$this->db->exec($sql);

			if ($last_record_key) {
				$sql=sprintf("update `Product Availability Timeline` set `Duration`=%d where `Product Availability Key`=%d",
					$new_date-$last_record_date,
					$last_record_key

				);
				$this->db->exec($sql);

			}

			//------

			if ($web_state=='For Sale') {
				$sql=sprintf("update `Email Site Reminder Dimension` set `Email Site Reminder State`='Ready' where `Email Site Reminder State`='Waiting' and `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d ",
					$this->id
				);

			}else {
				$sql=sprintf("update `Email Site Reminder Dimension` set `Email Site Reminder State`='Waiting' where `Email Site Reminder State`='Ready' and `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d ",
					$this->id
				);

			}
			$this->db->exec($sql);


		}




		$this->other_fields_updated=array(
			'Product_Availability'=>array(
				'field'=>'Product_Availability',
				'value'=>$this->get('Product Availability'),
				'formatted_value'=>$this->get('Availability'),


			),
			'Product_Web_State'=>array(
				'field'=>'Product_Web_State',
				'value'=>$this->get('Product Web State'),
				'formatted_value'=>$this->get('Web State'),


			)
		);











	}


	function get_linked_fields_data() {

		$sql=sprintf("select `Product Part Part SKU`,`Product Part Linked Fields` from `Product Part Bridge` where `Product Part Product ID`=%d", $this->id);

		$linked_fields_data=array();
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($row['Product Part Linked Fields']!='') {
					$linked_fields=json_decode($row['Product Part Linked Fields'], true);

					foreach ($linked_fields as $key=>$value) {
						$value=preg_replace('/\s/', '_', $value);
						$linked_fields_data[$value]=$row['Product Part Part SKU'];
					}

				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $linked_fields_data;

	}


	function create_time_series($date=false) {
		if (!$date) {
			$date=gmdate("Y-m-d");
		}
		$sql=sprintf("select sum(`Invoice Quantity`) as outers,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as sales,  sum(`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)) as dc_sales, count(Distinct `Customer Key`) as customers , count(Distinct `Invoice Key`) as invoices from `Order Transaction Fact` where `Product ID`=%d and `Current Dispatching State`='Dispatched' and `Invoice Date`>=%s  and `Invoice Date`<=%s   ",
			$this->id,
			prepare_mysql($date.' 00:00:00'),
			prepare_mysql($date.' 23:59:59')

		);
		$outers=0;
		$sales=0;
		$dc_sales=0;
		$customers=0;
		$invoices=0;


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$sales=$row['sales'];
				$dc_sales=$row['dc_sales'];
				$customers=$row['customers'];
				$invoices=$row['invoices'];
				$outers=$row['outers'];




			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		if ($invoices!=0 and $customers!=0 and $sales!=0 and $outers!=0) {


			$sql=sprintf("insert into `Order Spanshot Fact`(`Date`, `Product ID`, `Availability`, `Outers Out`, `Sales`, `Sales DC`, `Customers`, `Invoices`) values (%s,%d   ,%f,%f, %.2f,%.2f,  %d,%d) ON DUPLICATE KEY UPDATE `Outers Out`=%f,`Sales`=%.2f,`Sales DC`=%.2f,`Customers`=%d,`Invoices`=%d ",
				prepare_mysql($date),

				$this->id,
				1,
				$outers,
				$sales,
				$dc_sales,
				$customers,
				$invoices,

				$outers,
				$sales,
				$dc_sales,
				$customers,
				$invoices


			);
			$this->db->exec($sql);

			//$this->update_sales_averages();
		}

	}


	function update_historic_object() {

		if (!$this->id)return;

		$old_value=$this->get('Product Current Key');
		$changed=false;



		$desc=$this->get('Product Units Per Case').'x '.$this->get('Product Name').' ('.$this->get('Price').')';

		$sql=sprintf('select `Product Key` from `Product History Dimension` where
		`Product History Code`=%s and `Product History Units Per Case`=%d and `Product History Price`=%.2f and
		`Product History Name`=%s and `Product ID`=%d',

			prepare_mysql($this->data['Product Code']),
			$this->data['Product Units Per Case'],
			$this->data['Product Price'],
			prepare_mysql($this->data['Product Name']),
			$this->id
		);

		//print "$sql\n";

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {


				$this->update(array('Product Current Key'=>$row['Product Key']), 'no_history');
				$changed=true;

			}else {




				$sql=sprintf('insert into `Product History Dimension` (`Product ID`,`Product History Code`,`Product History Units Per Case`,
						`Product History Price`, `Product History Name`,`Product History Valid From`,`Product History Short Description`,`Product History XHTML Short Description`,`Product History Special Characteristic`

				) values (%d,%s,%d,%.2f,%s,%s,%s,%s,%s) ',
					$this->id,
					prepare_mysql($this->data['Product Code']),
					$this->data['Product Units Per Case'],
					$this->data['Product Price'],
					prepare_mysql($this->data['Product Name']),
					prepare_mysql(gmdate('Y-m-d H:i:s')),
					prepare_mysql($desc),
					prepare_mysql($desc),
					prepare_mysql($this->get('Product Special Characteristic'))
				);
				//print "$sql\n";
				// exit;
				if ($this->db->exec($sql)) {
					$this->update(array('Product Current Key'=>$this->db->lastInsertId()), 'no_history');
					$changed=true;
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			print $sql;
			exit;
		}




		$change_orders_in_basket=true;
		$change_orders_in_process=false;

		$states_to_change='';
		if ($change_orders_in_basket) {
			$states_to_change="'In Process by Customer','In Process','Out of Stock in Basket',";
		}
		if ($change_orders_in_process) {
			$states_to_change.="'Submitted by Customer','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Packing','Packed','Packed Done','No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other'";
		}

		$states_to_change=preg_replace('/\,$/', '', $states_to_change);
		if ($changed and  $old_value>0 and $states_to_change!='' ) {




			include_once 'class.Order.php';

			$orders=array();
			$sql=sprintf("select `Order Key`,`Delivery Note Key`,`Order Quantity`,`Order Transaction Fact Key` from `Order Transaction Fact` OTF  where `Product ID`=%d   and `Product Key`!=%d and  `Current Dispatching State` in (%s) and `Invoice Key` is NULL ",
				$this->id,
				$this->get('Product Current Key'),
				$states_to_change

			);

			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {




					$sql=sprintf('update `Order Transaction Fact` set  `Product Key`=%d, `Product Code`=%s, `Order Transaction Gross Amount`=%.2f, `Order Transaction Total Discount Amount`=0	, `Order Transaction Amount`=%.2f  where `Order Transaction Fact Key`=%d',
						$this->get('Product Current Key'),
						prepare_mysql($this->get('Product Code')),
						$this->get('Product Price')*$row['Order Quantity'],
						$this->get('Product Price')*$row['Order Quantity'],

						$row['Order Transaction Fact Key']
					);

					//    print "$sql\n";
					$this->db->exec($sql);

					$order=new Order($row['Order Key']);
					
				
					
					$order->update_number_products();
					$order->update_insurance();

					$order->update_discounts_items();
					$order->update_totals();
					$order->update_shipping($row['Delivery Note Key'], false);
					$order->update_charges($row['Delivery Note Key'], false);
					$order->update_discounts_no_items($row['Delivery Note Key']);
					$order->update_deal_bridge();
					$order->update_totals();
					$order->update_number_products();
					$order->apply_payment_from_customer_account();
					$order->update_payment_state();
				}



			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}
		}






	}


}




?>
