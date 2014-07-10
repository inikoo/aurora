<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 16:55:05 CEST, Malaga , Spain

 Version 2.0
*/


class Payment extends DB_Table {


	function Payment($arg1=false,$arg2=false) {

		$this->table_name='Payment';
		$this->ignore_fields=array('Payment Key');

		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return ;
		}
		if (preg_match('/^(create|new)/i',$arg1)) {
			$this->find($arg2,'create');
			return;
		}
		if (preg_match('/find/i',$arg1)) {
			$this->find($arg2,$arg1);
			return;
		}
		$this->get_data($arg1,$arg2);
		return ;

	}



	function get_data($tipo,$tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Payment Dimension` where `Payment Key`=%d",$tag);
		else
			return;

		// print $sql;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Payment Key'];


	}


	function find($raw_data,$options) {

		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();



		foreach ( $raw_data as $key=> $value) {
			if (array_key_exists($key,$data))
				$data[$key]=$value;

		}




		$this->found=false;


		if (!$this->found and $create) {
			$this->create($data);

		}


	}



	function get($key='') {






		if (isset($this->data[$key]))
			return $this->data[$key];

		switch ($key) {
		
		case('Max Payment to Refund'):
			return round($this->data['Payment Amount']+$this->data['Payment Refund'],2);
		break;
		case 'Transaction Status':
		switch ($this->data['Payment Transaction Status']) {
		//'Pending','Completed','Cancelled','Error'
			
			
			case 'Pending':
				return _('Pending');
				break;
			case 'Completed':
				return _('Completed');
				break;
			case 'Cancelled':
				return _('Cancelled');
				break;
			case 'Error':
				return _('Error');
				break;
			
			default:
				return $this->data['Payment Transaction Status'];

			}

			break;
		
		break;
		case 'Method':

			//'Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Account'
			switch ($this->data['Payment Method']) {
			case 'Credit Card':
				return _('Credit Card');
				break;
			case 'Cash':
				return _('Cash');
				break;
			case 'Paypal':
				return _('Paypal');
				break;
			case 'Check':
				return _('Check');
				break;
			case 'Bank Transfer':
				return _('Bank Transfer');
				break;
			case 'Cash on Delivery':
				return _('Cash on delivery');
				break;
			case 'Other':
			case 'Unknown':
				return _('Other');

				break;
			case 'Account':
				return _('Account');

				break;
			default:
				return $this->data['Payment Method'];

			}

			break;

		case('Amount'):
			return money($this->data['Payment '.$key],$this->data['Payment Currency Code']);
			break;
		case('Completed Date'):
		case('Cancelled Date'):
		case('Created Date'):
			return strftime("%a %e %b %Y %H:%M %Z",strtotime($this->data['Payment '.$key].' +0:00'));
			break;

		}
		$_key=ucfirst($key);
		if (isset($this->data[$_key]))
			return $this->data[$_key];
		return false;

	}



	function create($data) {

		$this->data=$data;

		$keys='';
		$values='';




		foreach ($this->data as $key=>$value) {




			$keys.=",`".$key."`";


			if ($key=='Payment Completed Date' or $key=='Payment Last Updated Date'  or $key=='Payment Cancelled Date'
				or $key=='Payment Order Key' or $key=='Payment Invoice Key' or $key=='Payment Site Key'
			) {
				$values.=','.prepare_mysql($value,true);

			}else {
				$values.=','.prepare_mysql($value,false);
			}

		}



		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Payment Dimension` ($keys) values ($values)";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->data['Address Key']= $this->id;
			$this->new=true;
			$this->get_data('id',$this->id);
		} else {
			print "Error can not create payment\n";
			exit;

		}
	}

	function load_payment_service_provider() {

		$this->payment_service_provider=new Payment_Service_Provider($this->data['Payment Service Provider Key']);
	}
	function load_payment_account() {

		$this->payment_account=new Payment_Account($this->data['Payment Account Key']);
	}

	function get_formated_time_lapse($key) {
		include_once 'common_date_functions.php';
		return gettext_relative_time(gmdate('U')-gmdate('U',strtotime($this->data['Payment '.$key].' +0:00'))  );
	}


	function get_formated_info() {
		$info='';
		$this->load_payment_account();
		$this->load_payment_service_provider();
		switch ($this->data['Payment Transaction Status']) {

		case 'Pending':
			$info=sprintf("%s %s %s %s, %s %s",
				_('A payment of'),
				money($this->data['Payment Amount'],$this->data['Payment Currency Code']),
				_('using'),
				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment service provider'),
				_('is in process')

			);

			break;
		case 'Completed':
			$info=sprintf("%s %s %s %s %s %s. %s: %s",
				_('A payment of'),
				money($this->data['Payment Amount'],$this->data['Payment Currency Code']),
				_('using'),
				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment service provider'),
				_('has been completed sucessfully'),
				_('Reference'),
				$this->data['Payment Transaction ID']

			);

			break;
		case 'Cancelled':
			$info=sprintf("%s %s %s %s %s %s",
				_('A payment of'),
				money($this->data['Payment Amount'],$this->data['Payment Currency Code']),
				_('using'),
				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment service provider'),
				_('has been cancelled')

			);

			break;
		case 'Error':
			$info=sprintf("%s %s %s %s %s %s",
				_('A payment of'),
				money($this->data['Payment Amount'],$this->data['Payment Currency Code']),
				_('using'),
				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment service provider'),
				_('has had an error')

			);

			break;

		}
		return $info;
	}

	function get_formated_short_info() {
		$info='';
		$this->load_payment_account();
		$this->load_payment_service_provider();
		switch ($this->data['Payment Transaction Status']) {

		case 'Pending':
			$info=sprintf("%s, %s",

				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment in process')

			);

			break;
		case 'Completed':
			$info=sprintf("%s, %s, %s: ",

				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment completed sucessfully'),
				_('Reference'),
				$this->data['Payment Transaction ID']

			);

			break;
		case 'Cancelled':
			$info=sprintf("%s, %s",

				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment in cancelled')

			);


			break;
		case 'Error':
			$info=sprintf("%s %s",

				$this->payment_service_provider->data['Payment Service Provider Name'],
				_('payment has had an error')

			);

			break;

		}
		return $info;

	}

	function update_balance() {
		$invoiced_amount=0;
		$sql=sprintf("select sum(`Amount`) as amount from `Invoice Payment Bridge` where `Invoice Key`=%d",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$invoiced_amount=$row['amount'];
		}

		$this->data['Payment Amount Invoiced']=$invoiced_amount;

		$this->data['Payment Balance']=$this->data['Payment Amount']-$this->data['Payment Refund']-$this->data['Payment Amount Invoiced'];


		$sql=sprintf("update `Payment Dimension` set `Payment Amount Invoiced`=%.2f,`Payment Balance`=%.2f where `Payment Key`=%d",
			$this->data['Payment Amount Invoiced'],
			$this->data['Payment Balance'],
			$this->id

		);
		//print $sql;
		mysql_query($sql);

	}

}
