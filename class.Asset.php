<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 19:32:50 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
include_once 'trait.ImageSubject.php';
include_once 'trait.AttachmentSubject.php';
include_once 'trait.NotesSubject.php';


class Asset extends DB_Table{
	use ImageSubject, NotesSubject, AttachmentSubject;

	function update_asset_field_switcher($field, $value, $options='', $metadata) {


		switch ($field) {

		case $this->table_name.' Barcode Number':

			if ($value=='') {
				include_once 'class.Barcode.php';
				$barcode=new Barcode($this->get('Barcode Key'));
				$barcode->editor=$this->editor;
				if ($barcode->id) {
					$asset_data=array(
						'Barcode Asset Type'=>$this->table_name,
						'Barcode Asset Key'=>$this->id
					);

					$barcode->withdrawn_asset($asset_data);
				}
				$this->deleted_value=$this->get('Barcode Number');

				$this->update_field($this->table_name.' Barcode Number', '', $options);
				$this->update_field($this->table_name.' Barcode Key', '', 'no_history');

			}else {

				$available_barcodes=0;
				$sql=sprintf("select `Barcode Key` ,`Barcode Status` from `Barcode Dimension` where `Barcode Number`=%s", $value);


				if ($result=$this->db->query($sql)) {
					if ($row = $result->fetch()) {




						if ($row['Barcode Status']=='Available') {

							include_once 'class.Barcode.php';
							$barcode=new Barcode($row['Barcode Key']);
							$barcode->editor=$this->editor;

							if ($this->get('Barcode Key')) {

								$asset_data=array(
									'Barcode Asset Type'=>$this->table_name,
									'Barcode Asset Key'=>$this->id
								);

								$barcode->withdrawn_asset($asset_data);
							}



							$asset_data=array(
								'Barcode Asset Type'=>$this->table_name,
								'Barcode Asset Key'=>$this->id,
								'Barcode Asset Assigned Date'=>gmdate('Y-m-d H:i:s')
							);



							$barcode->assign_asset($asset_data);
							$barcode_label=sprintf('<i class="fa fa-barcode fa-fw"></i> <span class="link" onClick="change_view(\'inventory/barcode/%d\')">%s</span>', $barcode->id, $barcode->get('Barcode Number'));


							$history_data=array(
								'History Abstract'=>sprintf(_('Barcode %s associated'), $barcode_label ),
								'History Details'=>'',
								'Action'=>'associated'
							);

							$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());


							$this->update_field($this->table_name.' Barcode Number', $value, 'no_history');
							$this->update_field($this->table_name.' Barcode Key', $barcode->id, 'no_history');

						}else {
							$this->error;
							$this->msg=_('Barcode no available');
							return true;
						}


					}else {
						if ($this->get('Barcode Key')) {
							include_once 'class.Barcode.php';
							$barcode=new Barcode($this->get('Barcode Key'));
							$barcode->editor=$this->editor;
							if ($barcode->id) {
								$asset_data=array(
									'Barcode Asset Type'=>$this->table_name,
									'Barcode Asset Key'=>$this->id
								);

								$barcode->withdrawn_asset($asset_data);
							}
						}

						$this->update_field($this->table_name.' Barcode Number', $value, $options);
						$this->update_field($this->table_name.' Barcode Key', '', 'no_history');

					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}
			}

			$this->other_fields_updated=array(
				$this->table_name.'_Barcode_Number'=>array(
					'field'=>$this->table_name.'_Barcode_Number',
					'render'=>true,
					'value'=>$this->get($this->table_name.' Barcode Number'),
					'formatted_value'=>$this->get('Barcode Number'),
					'barcode_key'=>$this->get($this->table_name.' Barcode Key')


				)
			);



			return true;
			break;
		case 'History Note':


			$this->add_note($value, '', '', $metadata['deletable']);
			break;




		default:




			if (preg_match('/^History Note (\d+)/i', $field, $matches)) {
				$history_key=$matches[1];
				$this->edit_note($history_key, $value);
				return true;
			}

			if (preg_match('/^History Note Strikethrough (\d+)/i', $field, $matches)) {
				$history_key=$matches[1];
				$this->edit_note_strikethrough($history_key, $value);
				return true;
			}


			return false;
		}

		return false;
	}


	function get_asset_common($key, $arg1='') {


		if (!$this->id)
			return;

		switch ($key) {

		case 'Family':
			include_once 'class.Category.php';
			if ($this->get($this->table_name.' Family Category Key')>0) {
				$family=new Category($this->get($this->table_name.' Family Category Key'));
				if ($family->id) {
					return array(true,$family);
				}
			}
			return array(true,false);

			break;
		case 'Family Category Key':
			include_once 'class.Category.php';
			if ($this->get($this->table_name.' Family Category Key')>0) {
				$family=new Category($this->get($this->table_name.' Family Category Key'));
				if ($family->id) {
					return array(true,$family->get('Code'));
				}
			}
			return array(true,'');

			break;


		case 'Stock Status Icon':

			switch ($this->data[$this->table_name.' Stock Status']) {
			case 'Surplus':
				$stock_status='<i class="fa  fa-plus-circle fa-fw" aria-hidden="true" title="'._('Surplus stock').'"></i>';
				break;
			case 'Optimal':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true" title="'._('Optimal stock').'"></i>';
				break;
			case 'Low':
				$stock_status='<i class="fa fa-minus-circle fa-fw" aria-hidden="true" title="'._('Low stock').'"></i>';
				break;
			case 'Critical':
				$stock_status='<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"  title="'._('Critical stock').'"></i>';
				break;
			case 'Out_Of_Stock':
				$stock_status='<i class="fa error fa-ban fa-fw" aria-hidden="true"  title="'._('Out of stock').'"></i>';
				break;
			case 'Error':
				$stock_status='<i class="fa fa-question-circle fa-fw" aria-hidden="true"  title="'._('Error').'"></i>';
				break;
			default:
				$stock_status=$this->data[$this->table_name.' Stock Status'];
				break;
			}
			return  array(true, $stock_status);
			break;

		case  'Tariff Code':
			$tariff_code=$this->data[$this->table_name.' Tariff Code'];
			if ($tariff_code!='' and $this->data[$this->table_name.' Tariff Code Valid']=='No') {
				$tariff_code.=' <span class="error invalid_value"><i class="fa fa-exclamation-circle"></i><span> '._('Invalid').'</span></span>';
			}
			return  array(true, $tariff_code);
			break;
		case $this->table_name.' Materials':

			if ($this->data[$this->table_name.' Materials']!='') {
				$materials='';



				$materials_data=json_decode($this->data[$this->table_name.' Materials'], true);


				foreach ($materials_data as $material_data) {

					if ($material_data['may_contain']=='Yes') {
						$may_contain_tag='±';
					}else {
						$may_contain_tag='';
					}

					$materials.=sprintf(', %s%s', $may_contain_tag, $material_data['name']);

					if ($material_data['ratio']>0) {
						$materials.=sprintf(' (%s)', percentage($material_data['ratio'], 1));
					}
				}

				$materials=preg_replace('/^\, /', '', $materials);


				return array(true, $materials);

			}else {
				return array(true, '');
			}
			break;
		case 'Materials':

			if ($this->data[$this->table_name.' Materials']!='') {
				$materials_data=json_decode($this->data[$this->table_name.' Materials'], true);
				$xhtml_materials='';

				foreach ($materials_data as $material_data) {
					if (!array_key_exists('id', $material_data)) {
						continue;
					}

					if ($material_data['may_contain']=='Yes') {
						$may_contain_tag='±';
					}else {
						$may_contain_tag='';
					}

					if ($material_data['id']>0) {
						$xhtml_materials.=sprintf(', %s<span onCLick="change_view(matarial/%d)" class="link" >%s</span>',
							$may_contain_tag,
							$material_data['id'],
							$material_data['name']);
					}else {
						$xhtml_materials.=sprintf(', %s%s', $may_contain_tag, $material_data['name']);

					}


					if ($material_data['ratio']>0) {
						$xhtml_materials.=sprintf(' (%s)', percentage($material_data['ratio'], 1));
					}
				}

				$xhtml_materials=ucfirst(preg_replace('/^\, /', '', $xhtml_materials));
				return array(true, $xhtml_materials);


			}else {
				return array(true, '');
			}
			break;
		case $this->table_name.' Package Dimensions':
		case $this->table_name.' Unit Dimensions':
			$dimensions='';

			$tag=preg_replace('/ Dimensions$/', '', $key);

			if ($this->data[$key]!='') {
				$data=json_decode($this->data[$key], true);
				include_once 'utils/units_functions.php';
				switch ($data['type']) {
				case 'Rectangular':

					$dimensions=number(convert_units($data['l'], 'm', $data['units'])).'x'.number(convert_units($data['w'], 'm', $data['units'])).'x'.number(convert_units($data['h'], 'm', $data['units'])).' ('.$data['units'].')';


					break;
				case 'Cilinder':

					$dimensions=number(convert_units($data['h'], 'm', $data['units'])).'x'.number(convert_units($data['w'], 'm', $data['units'])).' ('.$data['units'].')';


					break;


					break;



				case 'Sphere':
					$dimensions='D:'.number(convert_units($data['h'], 'm', $data['units'])).' ('.$data['units'].')';

					break;

					if (   !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
						$dimensions='';
					}else {
						$dimensions='&#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'String':
					$dimensions='L.'.number(convert_units($data['l'], 'm', $data['units'])).' ('.$data['units'].')';

					break;
					if (   !$part->data['Part '.$tag.' Dimensions Length Display']) {
						$dimensions='';
					}else {
						$dimensions='L:'.number($part->data['Part '.$tag.' Dimensions Length Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'Sheet':
					if ( !$part->data['Part '.$tag.' Dimensions Width Display']  or  !$part->data['Part '.$tag.' Dimensions Length Display']) {
						$dimensions='';
					}else {
						$dimensions=number($part->data['Part '.$tag.' Dimensions Width Display']).'x'.number($part->data['Part '.$tag.' Dimensions Length Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				default:
					$dimensions='';
				}

			}



			return array(true, $dimensions);
			break;
		case 'Package Weight':
		case 'Unit Weight':
			include_once 'utils/natural_language.php';


			return array(true, weight($this->data[$this->table_name.' '.$key]));
			break;

		case 'Package Dimensions':
		case 'Unit Dimensions':

			include_once 'utils/natural_language.php';


			$dimensions='';


			$tag=preg_replace('/ Dimensions$/', '', $key);

			if ($this->data[$this->table_name.' '.$key]!='') {
				$data=json_decode($this->data[$this->table_name.' '.$key], true);
				include_once 'utils/units_functions.php';
				switch ($data['type']) {
				case 'Rectangular':

					$dimensions=number(convert_units($data['l'], 'm', $data['units'])).'x'.number(convert_units($data['w'], 'm', $data['units'])).'x'.number(convert_units($data['h'], 'm', $data['units'])).' ('.$data['units'].')';
					$dimensions.=', <span class="discret">'.volume($data['vol']).'</span>';
					if ($this->data[$this->table_name." $tag Weight"]>0) {
						$dimensions.='<span class="discret">, '.number($this->data[$this->table_name." $tag Weight"]/$data['vol']).'Kg/L</span>';
					}

					break;
				case 'Cilinder':
					$dimensions=number(convert_units($data['h'], 'm', $data['units'])).'x'.number(convert_units($data['w'], 'm', $data['units'])).' ('.$data['units'].')';
					$dimensions.=', <span class="discret">'.volume($data['vol']).'</span>';
					if ($this->data[$this->table_name." $tag Weight"]>0) {
						$dimensions.='<span class="discret">, '.number($this->data[$this->table_name." $tag Weight"]/$data['vol']).'Kg/L</span>';
					}

					break;
					print_r($data);
					exit;
					if ( !$part->data['Part '.$tag.' Dimensions Length Display']  or  !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
						$dimensions='';
					}else {
						$dimensions='L:'.number($part->data['Part '.$tag.' Dimensions Length Display']).' &#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'Sphere':


					$dimensions=_('Diameter').' '.number(convert_units($data['l'], 'm', $data['units'])).$data['units'];
					$dimensions.=', <span class="discret">'.volume($data['vol']).'</span>';
					if ($this->data[$this->table_name." $tag Weight"]>0) {
						$dimensions.='<span class="discret">, '.number($this->data[$this->table_name." $tag Weight"]/$data['vol']).'Kg/L</span>';
					}

					break;
					if (   !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
						$dimensions='';
					}else {
						$dimensions='&#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'String':
					$dimensions=number(convert_units($data['l'], 'm', $data['units'])).$data['units'];
					break;

					if (   !$part->data['Part '.$tag.' Dimensions Length Display']) {
						$dimensions='';
					}else {
						$dimensions='L:'.number($part->data['Part '.$tag.' Dimensions Length Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'Sheet':
					if ( !$part->data['Part '.$tag.' Dimensions Width Display']  or  !$part->data['Part '.$tag.' Dimensions Length Display']) {
						$dimensions='';
					}else {
						$dimensions=number($part->data['Part '.$tag.' Dimensions Width Display']).'x'.number($part->data['Part '.$tag.' Dimensions Length Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				default:
					$dimensions='';
				}

			}



			return array(true, $dimensions);

		default:

			if (preg_match('/'.$this->table_name.' History Note (\d+)/i', $key, $matches)) {


				return array(true, $this->get_note($matches[1]));

			}
			if (preg_match('/History Note (Strikethrough )?(\d+)/i', $key, $matches)) {


				return array(true, nl2br($this->get_note($matches[2])));

			}


			return array(false, false);

		}

	}


	function get_image_key($index=1) {

		$sql=sprintf("select `Image Subject Image Key` from  `Image Subject Bridge` where `Image Subject Object`=%s and `Image Subject Object Key`=%d order by `Image Subject Order`  limit %d,1 ",
			prepare_mysql($this->table_name),
			$this->id,
			($index-1)
		);

		$image_key=0;
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$image_key=$row['Image Subject Image Key'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $image_key;

	}



}


?>
