<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 February 2017 at 18:46:46 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
*/



class Job_Position {



	function Job_Position($a1,$a2=false) {

		//global $db;
		//$this->db = $db;
		$this->id=false;


		if(!$a2){
            $this->get_data( 'id',$a1);

        }else{
            $this->get_data( $a1,$a2);

        }


	}


	function get_data($tag,$key) {

		include_once 'conf/roles.php';



		if (isset($roles[$key])) {
			$this->data=$roles[$key];
			$this->id=$key;
		}


	}



	function get($key, $data = false) {

		if (!$this->id) {
			return '';
		}


		switch ($key) {
            case 'title':

                return $this->data[$key];
		default:


		}

		return '';
	}



}


?>
