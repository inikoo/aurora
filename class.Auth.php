<?php
/*
 File: Auth.php

 Authenticatin Class
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

class Auth {
	var $user_parent_key=false;

	var $user_key=false;
	private $status=false;
	private $use_cookies=false;
	var $authentication_type=false;
	var $user_log_key=0;
	var $site_key=0;
	var $handle='';
	var $remember=false;
	function Auth($ikey=false,$skey=false,$options='') {
		if (preg_match('/use( |\_)cookies?/i',$options))
			$this->use_cookies=true;
		$this->ikey=$ikey;
		$this->skey=$skey;

		$this->pass=array(
			'handle'=>'No',
			'handle_in_use'=>'No',
			'password'=>'No',
			'time'=>'No',
			'ip'=>'No',
			'ikey'=>'No'
		);
	}


	function authenticate($handle=false,$sk=false,$page='inikoo',$page_key='f0') {

		$this->log_page=$page;

		switch ($this->log_page) {
		case 'staff':
			$this->user_type="'Administrator','Staff','Warehouse'";
			$this->where_user_type=" and `User Type` in ('Administrator','Staff','Warehouse')";
			break;
		case 'customer':
			$this->user_type="'Customer'";
			$this->where_user_type=sprintf(" and `User Type`='Customer' and `User Site Key`=%d ",$page_key);
			break;
		case 'supplier':
			$this->user_type="'Supplier'";
			$this->where_user_type=sprintf(" and `User Type`='Supplier'  ");
			break;
		}

		if ($handle and $sk) {
			$this->handle=$handle;
			$this->sk=$sk;
			$this->authenticate_from_login();
		}
		elseif ($this->use_cookies) {

			$this->handle=$_COOKIE['user_handle'];
			$this->sk=$_COOKIE['sk'];
			$this->authenticate_from_cookie();
		}

	}

	function is_authenticated() {
		return $this->status;
		//return true;
	}

	function authenticate_from_cookie() {


		$this->authentication_type='cookie';
		$this->status=false;
		$pass_tests=false;
		$this->pass=array(
			'handle'=>'No',
			'handle_in_use'=>'No',
			'handle_key'=>0,
			'password'=>'Unknown',
			'time'=>'Unknown',
			'ip'=>'Unknown',
			'ikey'=>'Unknown',
			'main_reason'=>'cookie_error'
		);

		$sql=sprintf("select `User Key`,`User Password`,`User Parent Key` from `User Dimension` where `User Handle`=%s and `User Active`='Yes' %s  "
			,prepare_mysql($this->handle)
			,$this->where_user_type
		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->pass['handle']='Yes';
			$this->pass['handle_in_use']='Yes';

			$st=AESDecryptCtr(AESDecryptCtr($this->sk,$row['User Password'],256),$this->skey,256);
			//echo $st;
			$this->pass['handle_key']=$row['User Key'];
			if (preg_match('/^skstart\|\d+\|[abcdef0-9\.\:]+\|.+\|/',$st)) {
				$this->pass['password']='Yes';
				$data=preg_split('/\|/',$st);

				//print_r($data);
				$time=$data[1];
				$ip=$data[2];
				$ikey=$data[3];

				if (isset($_COOKIE['user_handle'])) {
					$time=time(gmdate('U'))+100;

				}

				$pass_tests=true;


				if ($this->ikey!=$ikey) {
					$pass_tests=false;
					$this->pass['main_reason']='cookie_error';
					$this->pass['ikey']='No';

				} else {
					$this->pass['ikey']='Yes';
				}

			} else {
				$pass_tests=false;
				$this->pass['password']='No';
				$this->pass['main_reason']='cookie_error';
			}
		}

		if ($pass_tests ) {

			$this->status=true;
			$this->user_key=$row['User Key'];
			$this->user_parent_key=$row['User Parent Key'];
			$this->get_last_log();

			//$this->create_user_log();
		} else {
			$this->log_failed_login();
		}
		//echo $this->status;

	}


	function get_last_log() {

		$sql=sprintf("select `User Log Key` from `User Log Dimension`  where `Logout Date` is null  and `User Key`=%d ",$this->user_key);
		// print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {


			$ip=ip();
			$date=gmdate('Y-m-d H:i:s');


			$this->user_log_key=$row['User Log Key'];


			$sql=sprintf("update `User Dimension` set `Session ID`=%s ,`User Last Login IP`=%s,`User Last Login`=%s where `User Key`=%d",
				prepare_mysql(session_id()),
				prepare_mysql($ip),
				prepare_mysql($date),
				$this->user_key
			);
			mysql_query($sql);




		}else {
			$this->create_user_log();
		}

	}

	function set_cookies($handle=false,$sk=false,$page=false,$page_key=false) {
		//setcookie('test2', 'yyyyyy', time()+60*60*24*365);
		//print_r($_COOKIE);
		//print "xxx";
		setcookie('user_handle', $handle, time()+60*60*24*365, "/");
		setcookie('sk', $sk, time()+60*60*24*365, "/");
		//setcookie('page', $page, time()+60*60*24*365, "/");
		setcookie('page_key', $page_key, time()+60*60*24*365, "/");
	}

	function unset_cookies($handle=false,$sk=false,$page=false,$page_key=false) {
		$res=setcookie('user_handle', $handle, time()-100000, "/");
		setcookie('sk', $sk, time()-100000, "/");
		//setcookie('page', $page, time()-3600, "/");
		setcookie('page_key', $page_key, time()-100000, "/");
		//print "xxxx $res X $handle xxxx";

	}


	function authenticate_from_inikoo_masterkey($data,$same_ip=false) {


		$this->authentication_type='masterkey';


		$sql=sprintf("select `MasterKey Internal Key`,U.`User Key`,`User Handle`,`User Parent Key` from `MasterKey Internal Dimension` M left join `User Dimension` U on (U.`User Key`=M.`User Key`)    where `Key`=%s and  `Valid Until`>=%s  ",
			prepare_mysql($data),
			prepare_mysql(date('Y-m-d H:i:s'))

		);



		if ($same_ip) {

			$sql.=sprintf(" and `IP`=%s",
				prepare_mysql(ip())
			);
		}


		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {



			$this->status=true;
			$this->user_key=$row['User Key'];
			$this->user_handle=$row['User Handle'];
			$this->user_parent_key=$row['User Parent Key'];
			$this->create_user_log();

			$sql=sprintf("delete from  `MasterKey Internal Dimension` where `MasterKey Key`=%d   " ,$row['MasterKey Key']);
			mysql_query($sql);
			// print $sql;
			// exit;

		} else {


			// $this->log_failed_login();


		}


	}


	function authenticate_from_masterkey($data,$same_ip=false) {




		$pass_tests=false;
		$this->pass=array(
			'handle'=>'No',
			'handle_in_use'=>'No',
			'handle_key'=>0,
			'password'=>'Unknown',
			'time'=>'Unknown',
			'ip'=>'Unknown',
			'ikey'=>'Unknown',
			'main_reason'=>'masterkey_not_found'
		);

		//'cookie_error','handle','password','logging_timeout','ip','ikey','masterkey_not_found','masterkey_used','masterkey_expired'

		$this->authentication_type='masterkey';
		$sql=sprintf("select `User Key`,`Valid Until`,`MasterKey Key`,`Used`,`Fails Already Used`,`Fails Expired`  from `MasterKey Dimension` M  where `Key`=%s  ",
			prepare_mysql($data)


		);
		//print $sql;
		//  if ($same_ip) {$sql.=sprintf(" and `IP`=%s",prepare_mysql(ip()));}


		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$user=new User($row['User Key']);
			$this->handle=$user->data['User Handle'];
			$this->pass['handle_key']=$user->id;
			$this->pass['user_parent_key']=$user->data['User Parent Key'];
			if ($row['Used']=='No') {



				if (gmdate('U')<date('U',strtotime($row['Valid Until'].' +00:00'))) {

					$sql=sprintf("update `MasterKey Dimension` set `Used`='Yes' ,`Date Used`=%s where  `MasterKey Key`=%d",
						prepare_mysql(gmdate('Y-m-d H:i:s')),
						$row['MasterKey Key']
					);
					mysql_query($sql);

					if ($user->id) {
						$pass_tests=true;
						$this->status=true;
						$this->user_key=$user->id;
						$this->user_handle=$user->data['User Handle'];
						$this->user_parent_key=$user->data['User Parent Key'];
						$this->create_user_log();
					}else {
						$this->pass['main_reason']='handle';
					}
				}else {
					$sql=sprintf("update `MasterKey Dimension` set `Fails Expired`=%d where  `MasterKey Key`=%d",
						$row['Fails Expired']+1,
						$row['MasterKey Key']
					);
					mysql_query($sql);
					$this->pass['main_reason']='masterkey_expired';
					$this->pass['time']='No';
					$this->pass['password']='Yes';
					$this->pass['handle']='Yes';
					$this->pass['ikey']='Yes';

				}
			}
			else {

				$this->pass['password']='Yes';
				$this->pass['handle']='Yes';
				$this->pass['ikey']='Yes';

				$sql=sprintf("update `MasterKey Dimension` set `Fails Already Used`=%d where  `MasterKey Key`=%d",
					$row['Fails Already Used']+1,
					$row['MasterKey Key']
				);
				mysql_query($sql);
				$this->pass['main_reason']='masterkey_used';


			}





		} else {


			// $this->log_failed_login();


		}


		if (!$pass_tests ) {

			$this->log_failed_login();
		}


	}


	function authenticate_from_login() {

		include_once 'aes.php';

		$this->authentication_type='login';
		$this->status=false;
		$pass_tests=false;
		$this->pass=array(
			'handle'=>'No',
			'handle_in_use'=>'No',
			'handle_key'=>0,
			'password'=>'Unknown',
			'time'=>'Unknown',
			'ip'=>'Unknown',
			'ikey'=>'Unknown',
			'main_reason'=>'handle',
			'customer_key'=>0
		);

		$sql=sprintf("select `User Key`,`User Password`,`User Parent Key` from `User Dimension` where `User Handle`=%s and `User Active`='Yes' %s  "
			,prepare_mysql($this->handle)
			,$this->where_user_type
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->pass['handle']='Yes';
			$this->pass['handle_in_use']='Yes';
			$this->pass['user_parent_key']=$row['User Parent Key'];
			$st=AESDecryptCtr(AESDecryptCtr($this->sk,$row['User Password'],256),$this->skey,256);
			//echo $st;
			$this->pass['handle_key']=$row['User Key'];
			//print $st;
			if (preg_match('/^skstart\|\d+\|([abcdef0-9\.\:]+|localhost|unknown)\|.+\|/',$st)) {
				$this->pass['password']='Yes';
				$data=preg_split('/\|/',$st);

				//print_r($data);
				$time=$data[1];
				$ip=$data[2];
				$ikey=$data[3];

				$pass_tests=true;
				if ($time<time(gmdate('U'))  ) {
					$pass_tests=false;
					$this->pass['main_reason']='logging_timeout';
					$this->pass['time']='No';
				} else {
					$this->pass['time']='Yes';
				}
				if (ip()!=$ip) {
					$pass_tests=false;
					$this->pass['main_reason']='ip';
					$this->pass['ip']='No';
				} else {
					$this->pass['ip']='Yes';
				}
				if ($this->ikey!=$ikey) {
					$pass_tests=false;
					$this->pass['main_reason']='ikey';
					$this->pass['ikey']='No';

				} else {
					$this->pass['ikey']='Yes';
				}

			} else {
				$pass_tests=false;
				$this->pass['password']='No';
				$this->pass['main_reason']='password';
			}
		}

		if ($pass_tests ) {
			$this->status=true;
			$this->user_key=$row['User Key'];
			$this->user_parent_key=$row['User Parent Key'];
			$this->create_user_log();
		} else {
			$this->log_failed_login();
		}


	}


	function log_failed_login() {
		$date=gmdate("Y-m-d H:i:s");
		$ip=ip();
		$sql=sprintf("insert into `User Failed Log Dimension` values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			prepare_mysql($this->handle),
			prepare_mysql($this->log_page),
			prepare_mysql($this->pass['handle_key']),
			prepare_mysql($date),
			prepare_mysql($ip),
			prepare_mysql($this->pass['main_reason']),
			prepare_mysql($this->pass['handle']),
			prepare_mysql($this->pass['password']),
			prepare_mysql($this->pass['time']),
			prepare_mysql($this->pass['ip']),
			prepare_mysql($this->pass['ikey'])

		);
		//print $sql;
		mysql_query($sql);
		if ($this->pass['handle_key']) {

			$sql=sprintf("select count(*) as num from `User Failed Log Dimension` where `User Key`=%d",$this->pass['handle_key']);
			$res=mysql_query($sql);
			$num_failed_logins=0;
			if ($row=mysql_fetch_assoc($res)) {
				$num_failed_logins=$row['num'];
			}
			$sql=sprintf("update `User Dimension` set `User Failed Login Count`=%d, `User Last Failed Login IP`=%s,`User Last Failed Login`=%s where `User Key`=%d",
				$num_failed_logins,
				prepare_mysql($ip),
				prepare_mysql($date),
				$this->pass['handle_key']
			);
			mysql_query($sql);

			if ($this->log_page=='customer') {
				$customer=new Customer($this->pass['user_parent_key']);
				switch ($this->pass['main_reason']) {
				case('password'):
					$formated_reason=_('wrong password');
					break;
				case('masterkey_used'):
					$formated_reason=_('reset password link already used');
					break;
				case('masterkey_expired'):
					$formated_reason=_('reset password link expired');
					break;
				default:
					$formated_reason=$this->pass['main_reason'];
				}

				$details='<table>
				<tr><td style="width:120px">'._('Time').':</td><td>'.strftime("%c %Z",strtotime($date.' +00:00')).'</td></tr>
				<tr><td>'._('IP Address').':</td><td>'.$ip.'</td></tr>
				<tr><td>'._('User Agent').':</td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>

				</table>';

				$history_data=array(
					'Date'=>$date,
					'Site Key'=>$this->site_key,
					'Note'=>_('Failed Login')." ($formated_reason) ip:".$ip,
					'Details'=>$details,
					'Action'=>'fail_login',
					'Preposition'=>'because',
					'Indirect Object'=>$this->pass['main_reason'],
					'User Key'=>$this->pass['handle_key']
				);

				$customer->add_history_login($history_data);
				$customer->update_web_data();

			}

		}
	}

	function create_user_log() {
		$ip=ip();
		$date=gmdate('Y-m-d H:i:s');
		$sql=sprintf("INSERT INTO `User Log Dimension` (`User Key`,`Session ID`, `IP`, `Start Date`,`Last Visit Date`, `Logout Date`,`Remember Cookie`,`Site Key`) VALUES (%d, %s, %s, %s,%s, %s,%s,%d)",
			$this->user_key,
			prepare_mysql(session_id()),
			prepare_mysql($ip),
			prepare_mysql($date),
			prepare_mysql($date),
			'NULL',
			prepare_mysql(($this->remember?'Yes':'No')),
			$this->site_key
		);
//print $sql;
		mysql_query($sql);

		$this->user_log_key=mysql_insert_id();

		$sql=sprintf("select count(*) as num from `User Log Dimension` where `User Key`=%d",$this->user_key);
		$res=mysql_query($sql);
		$num_logins=0;
		if ($row=mysql_fetch_assoc($res)) {
			$num_logins=$row['num'];
		}
		if($num_logins>0){
		$this->data['User Has Login']='Yes';
		}else{
		$this->data['User Has Login']='No';
		}
		$sql=sprintf("update `User Dimension` set `User Has Login`=%d , `User Login Count`=%d, `User Last Login IP`=%s,`User Last Login`=%s where `User Key`=%d",
			prepare_mysql($this->data['User Has Login']),
			$num_logins,
			prepare_mysql($ip),
			prepare_mysql($date),
			$this->user_key
		);
		mysql_query($sql);

//print $sql;
		// if ($this->log_page=='customer' or $this->log_page=='masterkey') {
		if ($this->log_page=='customer') {

			$customer=new Customer($this->user_parent_key);
			$details='<table>
				<tr><td style="width:120px">'._('Time').':</td><td>'.strftime("%c %Z",strtotime($date.' +00:00')).'</td></tr>
				<tr><td>'._('IP Address').':</td><td>'.$ip.'</td></tr>
				<tr><td>'._('User Agent').':</td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>
				</table>';

			switch ($this->authentication_type) {
			case('masterkey'):
				$note=_('Logged in from reset password email');
				break;
			default:
				$note=_('Logged in');
			}

			if ($this->remember) {
				$note.=', '._('remember me cookie set');
			}

			$history_data=array(
				'Date'=>$date,
				'Site Key'=>$this->site_key,
				'Note'=>$note,
				'Details'=>$details,
				'Action'=>'login',
				'Indirect Object'=>'',
				'User Key'=>$this->user_key
			);

			$customer->add_history_login($history_data);
			$customer->update_web_data();

		}

	}

	public function get_user_key() {
		return $this->user_key;
	}
	public function get_user_parent_key() {
		return $this->user_parent_key;
	}


	public function set_user_key($user_key) {
		$this->user_key=$user_key;
	}

	public function set_use_cookies() {
		$this->use_cookies=true;
	}
}
?>
