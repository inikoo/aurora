<?php
class CurrencyExchange {

	var $exchange=false;
	var $source='not found';

	function CurrencyExchange($currency_pair=false,$date=false) {

		$this->exchange=false;

		if (!$date) {
			$this->date=gmdate('Y-m-d');
		}else {
			$this->date=$date;
		}

		if (!preg_match('/^[a-z]{6}$/i',$currency_pair))
			return false;
		$this->currency1=substr($currency_pair,0,3);
		$this->currency2=substr($currency_pair,3,3);
		if ($this->currency1==$this->currency2) {
			$this->exchange=1;
			$this->source='same currency';
		}
		$this->currency_pair=$currency_pair;


		if (!$this->exchange) $this->get_data_exchange_from_kbase();

		if (!$this->exchange) {
			if ($this->date==gmdate('Y-m-d')) {
				$this->get_current_exchange();

			}else {
				$this->get_historic_exchange();


			}



		}


		if ($this->exchange and in_array($this->source,array('yahoo','google','openexchangerates'))) {
			$this->save_exchange();
		}

	}


    function get_exchange(){
        return $this->exchange;
    }

	function save_exchange() {

		$sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f) on duplicate key update `Exchange`=%f "
			,prepare_mysql($this->date) ,prepare_mysql($this->currency_pair),$this->exchange,$this->exchange);
		mysql_query($sql);

		$inv_exchange=(1/$this->exchange);
		$sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f) on duplicate key update `Exchange`=%f "
			,prepare_mysql($this->date) ,prepare_mysql($this->currency2.$this->currency1),$inv_exchange,$inv_exchange);
		mysql_query($sql);
	}

	function get_current_exchange() {


		$this->get_current_exchange_from_yahoo();
		if (!$this->exchange or $this->exchange==1) {
			$this->get_current_exchange_from_openexchangerates();
		}





	}

	function get_data_exchange_from_kbase() {



		$sql=sprintf("select `Exchange` from kbase.`History Currency Exchange Dimension` where `Currency Pair`=%s and `Date`=DATE(%s)     "
			,prepare_mysql($this->currency_pair)
			,prepare_mysql($this->date));
		$res3=mysql_query($sql);

		if ($row3=mysql_fetch_array($res3, MYSQL_ASSOC)) {
			$this->exchange=$row3['Exchange'];
			$this->source='kbase';
		}else {
			$this->exchange=false;
		}

		return $this->exchange;


	}

	

	function get_current_exchange_from_yahoo() {

		$url = "http://download.finance.yahoo.com/d/quotes.csv?s=".$this->currency_pair."=X&f=l1&e=.cs";
		$handle = fopen($url, "r");
		$contents = _trim(fread($handle,2000));

		fclose($handle);


		if (is_numeric($contents) and $contents>0) {
			$this->exchange=$contents;
			$this->source='yahoo';
		}



	}

	function get_historic_exchange() {

		$this->get_historic_exchange_from_openexchangerates();

	}


	function get_historic_exchange_from_openexchangerates() {
		//https://openexchangerates.org



		$api_keys=array(
			'raul@inikoo.com'=>'8158586024e345b2b798c26ee50b6987',
			'exchange1@inikoo.com'=>'21467cd6ca2847cf9fdbc913e616d6e9',
			'exchange2@inikoo.com'=>'e328d66fafc94f6391d2a8e4fbab0389',
			'exchange3@inikoo.com'=>'271f126537a84a3f98599e66781f8bed',
			'exchange4@inikoo.com'=>'756b792276ba4c80807a85b031139d7e',
			'exchange5@inikoo.com'=>'4bc72747362a496c971c528fb1b1d219',


		);
        shuffle($api_keys);
        $api_key=reset($api_keys);

		$url='http://openexchangerates.org/api/historical/'.$this->date.'.json?app_id='.$api_key;
		$data=json_decode(file_get_contents($url),true);


		if (isset($data['rates'][$this->currency1]) and isset($data['rates'][$this->currency2])) {

			$usd_cur1=$data['rates'][$this->currency1];
			$usd_cur2=$data['rates'][$this->currency2];
			$this->exchange= $usd_cur2 * (1 /  $usd_cur1);


			$this->source='openexchangerates';
		}








	}
	
	
	
	function get_current_exchange_from_openexchangerates() {
		//https://openexchangerates.org



		$api_keys=array(
			'raul@inikoo.com'=>'8158586024e345b2b798c26ee50b6987',
			'exchange1@inikoo.com'=>'21467cd6ca2847cf9fdbc913e616d6e9',
			'exchange2@inikoo.com'=>'e328d66fafc94f6391d2a8e4fbab0389',
			'exchange3@inikoo.com'=>'271f126537a84a3f98599e66781f8bed',
			'exchange4@inikoo.com'=>'756b792276ba4c80807a85b031139d7e',
			'exchange5@inikoo.com'=>'4bc72747362a496c971c528fb1b1d219',


		);
        shuffle($api_keys);
        $api_key=reset($api_keys);

		$url='http://openexchangerates.org/api/latest.json?app_id='.$api_key;
		$data=json_decode(file_get_contents($url),true);


		if (isset($data['rates'][$this->currency1]) and isset($data['rates'][$this->currency2])) {

			$usd_cur1=$data['rates'][$this->currency1];
			$usd_cur2=$data['rates'][$this->currency2];
			$this->exchange= $usd_cur2 * (1 /  $usd_cur1);


			$this->source='openexchangerates';
		}








	}
	
}

?>
