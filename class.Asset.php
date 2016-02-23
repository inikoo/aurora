<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 19:32:50 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
include_once 'class.Image.management.ext.php';


class Asset extends ObjectwithImage {



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
