<?php
/*
 File: Country.php

 This file contains the Country Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/




class Country {

	var $data=array();
	var $id=false;

	function __construct($arg1=false, $arg2=false) {

		global $db;
		$this->db=$db;


		if ($arg1=='id' and is_numeric($arg2)) {
			$this->get_data('id', $arg2);
			return;
		}
		elseif ($arg1=='code') {
			$this->get_data('code', $arg2);
			return;
		}
		elseif ($arg1=='find') {
			$this->get_data('find', $arg2);
			return;
		}
		elseif (preg_match('/^(minicode|2alpha|2 alpha code)$/i', $arg1)) {
			$this->get_data('2 alpha code', $arg2);
			return;
		}
		elseif ($arg1=='name' and $arg2!='') {
			$name=$arg2;
			$this->get_data('name', $name);
			return;
		}
		elseif ($arg1=='new' and is_array($arg2)) {
			$this->create('name', $name);
			return;
		}

		if (is_numeric($arg1) and !$arg2) {
			$this->get_data('id', $arg1);
		}



	}


	function get_data($key, $id) {



		if ($key=='find') {

			if ($id=='') {
				$this->get_data('code', 'UNK');
			}

			if (is_numeric($key)) {


				$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Key`=%d", $id);
				if ($this->data = $this->db->query($sql)->fetch()) {
					$this->id=$this->data['Country Key'];
					return;

				}
			}

			if (strlen($id)==3) {
				$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Code`=%s", prepare_mysql($id));
				if ($this->data = $this->db->query($sql)->fetch()) {
					$this->id=$this->data['Country Key'];
					return;
				}
			}

			if (strlen($id)==3) {
				$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Code`=%s", prepare_mysql($id));
				if ($this->data = $this->db->query($sql)->fetch()) {
					$this->id=$this->data['Country Key'];
					return;
				}
			}




			$sql=sprintf("select `Country Key`  from kbase.`Country Dimension`where  `Country Name`=%s  "
				, prepare_mysql($id)
			);
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Country Key'];
				return;
			}

			$sql=sprintf("select `Country Alias Code`  from kbase.`Country Alias Dimension` where `Country Alias`=%s  "
				, prepare_mysql($id)

			);



			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$this->get_data('code', $row['Country Alias Code']);
					return;
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}

			$this->get_data('code', 'UNK');


		}
		elseif ($key=='id') {
			$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Key`=%d", $id);
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Country Key'];
			}
			return;
		}
		elseif ($key=='2 alpha code' or $key=='2alpha') {
			$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country 2 Alpha Code`=%s", prepare_mysql($id));
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Country Key'];
			}
			return;
		}
		elseif ($key=='code') {
			$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Code`=%s", prepare_mysql($id));
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Country Key'];
			}


			return;
		}

		elseif ($key=='name') {
			$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Name`=%s", prepare_mysql($id));
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Country Key'];
			}
			return;

			$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Official Name`=%s", prepare_mysql($id));
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Country Key'];
			}
			return;
			$sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Native Name`=%s", prepare_mysql($id));
			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Country Key'];
			}
			return;
		}


	}


	function get($key) {

		if (isset($this->data[$key]))
			return $this->data[$key];

		if ($key=='Population') {
			return number($this->data['Country Population']);
		}
		if ($key=='GNP') {
			return money($this->data['Country GNP']);
		}
		return false;

	}


	function get_formatted_exchange_reverse($currency_code, $date=false, $display='') {
		switch ($display) {
		case('tr'):

			return '<tr><td>'.money(1, $currency_code).'</td><td>=</td><td>'.money($this->exchange($currency_code, $date), $this->data['Country Currency Code'])."</td></tr>";

			break;
		default:
			return money(1, $currency_code).'='.money($this->exchange($currency_code, $date), $this->data['Country Currency Code']);
		}

	}


	function get_formatted_exchange($currency_code, $date=false, $display='') {
		switch ($display) {
		case('tr'):

			return '<tr><td>'.money(1, $this->data['Country Currency Code']).'</td><td>=</td><td>'.money(1/$this->exchange($currency_code, $date), $currency_code)."</td></tr>";

			break;
		default:

			return money(1, $this->data['Country Currency Code']).'='.money(1/$this->exchange($currency_code, $date), $currency_code);
		}
	}


	function exchange($currency_code, $date=false) {
		include_once 'class.CurrencyExchange.php';

		$currency_exchange = new CurrencyExchange($currency_code.$this->data['Country Currency Code'], $date);
		$exchange= $currency_exchange->get_exchange();
		return $exchange;

	}


	function get_country_name($locale='en_GB') {
		/*
		include 'country_localized_names.php';
		if (array_key_exists($locale,$country_names)) {
			if (isset( $country_names[$locale][$this->data['Country 2 Alpha Code']])) {
				return $country_names[$locale][$this->data['Country 2 Alpha Code']];
			}
		}
		*/
		return $this->data['Country Name'];

	}


}


?>
