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
	use ImageSubject,NotesSubject,AttachmentSubject;

	function update_subject_field_switcher($field, $value, $options='', $metadata) {


		switch ($field) {

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

		switch ($key) {
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

			if ($this->data[$key]!='') {
				$data=json_decode($this->data[$key], true);
				include_once 'utils/units_functions.php';
				switch ($data['type']) {
				case 'Rectangular':

					$dimensions=number(convert_units($data['l'], 'm', $data['units'])).'x'.number(convert_units($data['w'], 'm', $data['units'])).'x'.number(convert_units($data['h'], 'm', $data['units'])).' ('.$data['units'].')';


					break;
				case 'Cilinder':
					if ( !$part->data['Part '.$tag.' Dimensions Length Display']  or  !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
						$dimensions='';
					}else {
						$dimensions='L:'.number($part->data['Part '.$tag.' Dimensions Length Display']).' &#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'Sphere':
					if (   !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
						$dimensions='';
					}else {
						$dimensions='&#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'String':
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
		case 'Package Dimensions':
		case 'Unit Dimensions':
			$dimensions='';


        $tag=preg_replace('/ Dimensions$/','',$key);

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
					if ( !$part->data['Part '.$tag.' Dimensions Length Display']  or  !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
						$dimensions='';
					}else {
						$dimensions='L:'.number($part->data['Part '.$tag.' Dimensions Length Display']).' &#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'Sphere':
					if (   !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
						$dimensions='';
					}else {
						$dimensions='&#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
					}
					break;
				case 'String':
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





}


?>
