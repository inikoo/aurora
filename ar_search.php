<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2015 at 18:35:53 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';





if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'search':

	$data=prepare_values($_REQUEST, array(
			'query'=>array('type'=>'string'),
			'state'=>array('type'=>'json array')
		));

	$data['user']=$user;

	if ($data['state']['module']=='customers') {
		if ($data['state']['current_store']) {
			$data['scope']='store';
			$data['scope_key']=$data['state']['current_store'];
		}else {
			$data['scope']='stores';
		}
		search_customers($db, $account, $memcache_ip, $data);
	}elseif ($data['state']['module']=='products') {
		if ($data['state']['current_store']) {
			$data['scope']='store';
			$data['scope_key']=$data['state']['current_store'];
		}else {
			$data['scope']='stores';
		}
		search_products($db, $account, $memcache_ip, $data);
	}elseif ($data['state']['module']=='inventory') {
		if ($data['state']['current_warehouse']) {
			$data['scope']='warehouse';
			$data['scope_key']=$data['state']['current_warehouse'];
		}else {
			$data['scope']='warehouses';
		}
		search_inventory($db, $account, $memcache_ip, $data);
	}elseif ($data['state']['module']=='hr') {
		search_hr($db, $account, $memcache_ip, $data);

	}

	break;


default:
	$response=array('state'=>405, 'resp'=>'Tab not found '.$tab);
	echo json_encode($response);
	exit;
	break;
}

function search_inventory($db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='' or count($user->warehouses)==0) {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}



	if ($data['scope']=='warehouse') {
		if (in_array($data['scope_key'], $user->stores)) {
			$warehouses=$data['scope_key'];

			if (count($user->warehouses)==1) {
				$part_table="`Part Dimension` P";
				$where_warehouse='';
			}else {
				$part_table="`Part Dimension` P left join `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`)";
				$where_warehouse=sprintf(' and `Warehouse Key`=%d', $data['scope_key']);
			}


		}else {
			$response=array('state'=>200, 'results'=>0, 'data'=>'');
			echo json_encode($response);
			return;
		}
	} else {
		if (count($user->warehouses)==$account->data['Warehouses']) {
			$part_table="`Part Dimension` P";
			$where_warehouse='';
		}else {
			$part_table="`Part Dimension` P left join `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`)";
			$where_warehouse=sprintf(' and `Warehouse Key` in (%s)', join(',', $user->stores));
		}

		$warehouses=join(',', $user->warehouses);
	}
	$memcache_fingerprint=$account->get('Account Code').'SEARCH_INVENTORY'.$warehouses.md5($queries);

	$cache = new Memcached();
	$cache->addServer($memcache_ip, 11211);


	if (strlen($queries)<=2) {
		$memcache_time=295200;
	}if (strlen($queries)<=3) {
		$memcache_time=86400;
	}if (strlen($queries)<=4) {
		$memcache_time=3600;
	}else {
		$memcache_time=300;

	}


	$results_data=$cache->get($memcache_fingerprint);


	if (!$results_data or true) {


		$candidates=array();

		$query_array=preg_split('/\s+/', $queries);
		$number_queries=count($query_array);

		if ($number_queries==1) {
			$q=$queries;
			if (is_numeric($q)) {
				$sql=sprintf("select `Part SKU`,`Part Reference`,`Part Unit Description` from $part_table where true $where_warehouse and `Part SKU`=%d",
					$q);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$candidates[$row['Part SKU']]=2000;
					}
				}else {
					print_r($error_info=$db->errorInfo());
					print $sql;
					exit;
				}


			}


		}


		foreach ($query_array as $q) {




			$sql=sprintf("select `Part SKU`,`Part Reference`,`Part Unit Description` from $part_table  where true $where_warehouse and `Part Reference` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Part Reference']==$q)
						$candidates[$row['Part SKU']]=1000;
					else {

						$len_name=strlen($row['Part Reference']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Part SKU']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}









			$sql=sprintf("select `Part SKU`,`Part Reference`,`Part Unit Description`from $part_table  where true $where_warehouse and `Part Unit Description`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Part Unit Description']==$q)
						$candidates[$row['Part SKU']]=55;
					else {

						$len_name=strlen($row['Part Unit Description']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Part SKU']]=50*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}


		}


		arsort($candidates);



		$total_candidates=count($candidates);

		if ($total_candidates==0) {
			$response=array('state'=>200, 'results'=>0, 'data'=>'');
			echo json_encode($response);
			return;
		}

		$counter=0;
		$customer_keys='';
		$results=array();

		foreach ($candidates as $key=>$val) {
			$counter++;
			$customer_keys.=','.$key;
			$results[$key]='';
			if ($counter>$max_results) {
				break;
			}
		}
		$product_keys=preg_replace('/^,/', '', $customer_keys);

		$sql=sprintf("select `Warehouse Code`,B.`Warehouse Key`,P.`Part SKU`,`Part Reference`,`Part Unit Description` from `Part Dimension` P left join `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`) left join `Warehouse Dimension` W on (B.`Warehouse Key`=W.`Warehouse Key`) where B.`Part SKU` in (%s)",
			$product_keys);

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {





				$results[$row['Part SKU']]=array(
					'warehouse'=>$row['Warehouse Code'],
					'label'=>highlightkeyword(sprintf('%s', $row['Part Reference']), $queries ),
					'details'=>highlightkeyword($row['Part Unit Description'], $queries ),
					'view'=>sprintf('inventory/%d/part/%d', $row['Warehouse Key'], $row['Part SKU'])




				);

			}
		}else {
			print_r($error_info=$db->errorInfo());
			print $sql;
			exit;
		}





		$results_data=array('n'=>count($results), 'd'=>$results);
		$cache->set($memcache_fingerprint, $results_data, $memcache_time);



	}
	$response=array('state'=>200, 'number_results'=>$results_data['n'], 'results'=>$results_data['d'], 'q'=>$queries);

	echo json_encode($response);

}


function search_products($db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='') {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}

	if ($data['scope']=='store') {
		if (in_array($data['scope_key'], $user->stores)) {
			$stores=$data['scope_key'];
			$where_store=sprintf(' and `Store Product Store Key`=%d', $data['scope_key']);
		}else {
			$where_store=' and false';
		}
	} else {
		if (count($user->stores)==$account->data['Stores']) {
			$where_store='';
		}else {
			$where_store=sprintf(' and `Store Product Store Key` in (%s)', join(',', $user->stores));
		}

		$stores=join(',', $user->stores);
	}
	$memcache_fingerprint=$account->get('Account Code').'SEARCH_PROD'.$stores.md5($queries);

	$cache = new Memcached();
	$cache->addServer($memcache_ip, 11211);


	if (strlen($queries)<=2) {
		$memcache_time=295200;
	}if (strlen($queries)<=3) {
		$memcache_time=86400;
	}if (strlen($queries)<=4) {
		$memcache_time=3600;
	}else {
		$memcache_time=300;

	}


	$results_data=$cache->get($memcache_fingerprint);


	if (!$results_data or true) {


		$candidates=array();

		$query_array=preg_split('/\s+/', $queries);
		$number_queries=count($query_array);

		if ($number_queries==1) {
			$q=$queries;
			if (is_numeric($q)) {
				$sql=sprintf("select `Store Product Key`,`Store Product Code`,`Store Product Label` from `Store Product Dimension` where true $where_store and `Store Product Key`=%d",
					$q);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$candidates[$row['Store Product Key']]=2000;
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}


			}


		}


		foreach ($query_array as $q) {




			$sql=sprintf("select `Store Product Key`,`Store Product Code`,`Store Product Label` from `Store Product Dimension` where true $where_store and `Store Product Code` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Store Product Code']==$q)
						$candidates[$row['Store Product Key']]=1000;
					else {

						$len_name=strlen($row['Store Product Code']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Store Product Key']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}









			$sql=sprintf("select `Store Product Key`,`Store Product Code`,`Store Product Label` from `Store Product Dimension` where true $where_store and `Store Product Label`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Store Product Label']==$q)
						$candidates[$row['Store Product Key']]=55;
					else {

						$len_name=strlen($row['Store Product Label']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Store Product Key']]=50*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}


		}


		arsort($candidates);



		$total_candidates=count($candidates);

		if ($total_candidates==0) {
			$response=array('state'=>200, 'results'=>0, 'data'=>'');
			echo json_encode($response);
			return;
		}

		$counter=0;
		$product_keys='';
		$results=array();

		foreach ($candidates as $key=>$val) {
			$counter++;
			$product_keys.=','.$key;
			$results[$key]='';
			if ($counter>$max_results) {
				break;
			}
		}
		$product_keys=preg_replace('/^,/', '', $product_keys);

		$sql=sprintf("select `Store Code`,`Store Key`,`Store Product Key`,`Store Product Code`,`Store Product Label` from `Store Product Dimension` left join `Store Dimension` S on (`Store Product Store Key`=S.`Store Key`) where `Store Product Key` in (%s)",
			$product_keys);

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {





				$results[$row['Store Product Key']]=array(
					'store'=>$row['Store Code'],
					'label'=>highlightkeyword(sprintf('%s', $row['Store Product Code']), $queries ),
					'details'=>highlightkeyword($row['Store Product Label'], $queries ),
					'view'=>sprintf('products/%d/%d', $row['Store Key'], $row['Store Product Key'])




				);

			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}





		$results_data=array('n'=>count($results), 'd'=>$results);
		$cache->set($memcache_fingerprint, $results_data, $memcache_time);



	}
	$response=array('state'=>200, 'number_results'=>$results_data['n'], 'results'=>$results_data['d'], 'q'=>$queries);

	echo json_encode($response);

}


function search_customers($db, $account, $memcache_ip, $data) {




	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='') {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}

	if ($data['scope']=='store') {
		if (in_array($data['scope_key'], $user->stores)) {
			$stores=$data['scope_key'];
			$where_store=sprintf(' and `Customer Store Key`=%d', $data['scope_key']);
		}else {
			$where_store=' and false';
		}
	} else {
		if (count($user->stores)==$account->data['Stores']) {
			$where_store='';
		}else {
			$where_store=sprintf(' and `Customer Store Key` in (%s)', join(',', $user->stores));
		}

		$stores=join(',', $user->stores);
	}
	$memcache_fingerprint=$account->get('Account Code').'SEARCH_CUST'.$stores.md5($queries);

	$cache = new Memcached();
	$cache->addServer($memcache_ip, 11211);


	if (strlen($queries)<=2) {
		$memcache_time=295200;
	}if (strlen($queries)<=3) {
		$memcache_time=86400;
	}if (strlen($queries)<=4) {
		$memcache_time=3600;
	}else {
		$memcache_time=300;

	}


	$results_data=$cache->get($memcache_fingerprint);


	if (!$results_data or $cache) {


		$candidates=array();

		$q=$queries;

		if (is_numeric($q)) {
			$sql=sprintf("select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Key`=%d",
				$q);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$candidates[$row['Customer Key']]=2000;
			}
		}
		$q_just_numbers=preg_replace('/[^\d]/', '', $q);
		if (strlen($q_just_numbers)>4 and strlen($q_just_numbers)<=6) {

			$sql=sprintf("select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Telephone` like '%s%%'",
				$q_just_numbers
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$candidates[$row['Customer Key']]=100;
			}
			$sql=sprintf("select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Mobile` like '%s%%'",
				$q_just_numbers
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$candidates[$row['Customer Key']]=100;
			}
		}
		if (strlen($q_just_numbers)>6) {

			$sql=sprintf("select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Telephone` like '%%%s%%'",
				$q_just_numbers
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$candidates[$row['Customer Key']]=100;
			}
			$sql=sprintf("select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Mobile` like '%%%s%%'",
				$q_just_numbers
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$candidates[$row['Customer Key']]=100;
			}
		}

		$sql=sprintf("select `Customer Key`,`Customer Tax Number` from `Customer Dimension` where true $where_store and `Customer Tax Number` like '%s%%' limit 10 ",
			$q);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Customer Tax Number']==$q)
				$candidates[$row['Customer Key']]=30;
			else {

				$len_name=strlen($row['Customer Tax Number']);
				$len_q=strlen($q);
				$factor=$len_q/$len_name;
				$candidates[$row['Customer Key']]=20*$factor;
			}
		}



		$sql=sprintf("select `Subject Key`,`Email` from `Email Bridge` EB  left join `Email Dimension` E on (EB.`Email Key`=E.`Email Key`) left join `Customer Dimension` CD on (CD.`Customer Key`=`Subject Key`) where true $where_store and `Subject Type`='Customer' and `Email`  like '%s%%' limit 100 ",
			$q);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Email']==$q) {
				$candidates[$row['Subject Key']]=120;
			} else {

				$len_name=strlen($row['Email']);
				$len_q=strlen($q);
				$factor=$len_q/$len_name;
				$candidates[$row['Subject Key']]=100*$factor;
			}
		}

		$q_postal_code=preg_replace('/[^a-z^A-Z^\d]/', '', $q);
		if ($q_postal_code!='') {
			$sql=sprintf("select `Customer Key`,`Customer Main Plain Postal Code` from `Customer Dimension`where true $where_store and `Customer Main Plain Postal Code`!='' and `Customer Main Plain Postal Code` like '%s%%' limit 150",
				addslashes($q_postal_code)
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				if ($row['Customer Main Plain Postal Code']==$q_postal_code) {
					$candidates[$row['Customer Key']]=50;
				} else {
					$len_name=strlen($row['Customer Main Plain Postal Code']);
					$len_q=strlen($q_postal_code);
					$factor=$len_q/$len_name;
					$candidates[$row['Customer Key']]=20*$factor;
				}
			}
		}

		$sql=sprintf("select `Subject Key`,`Contact Name`,`Contact Surname` from `Contact Bridge` EB  left join `Contact Dimension` E on (EB.`Contact Key`=E.`Contact Key`) left join `Customer Dimension` CD on (CD.`Customer Key`=`Subject Key`) where true $where_store and `Subject Type`='Customer' and `Contact Name` like '%s%%' limit 20",
			$q);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Contact Name']==$q) {
				$candidates[$row['Subject Key']]=120;
			} else {
				$len_name=$row['Contact Name'];
				$len_q=strlen($q);
				$factor=$len_name/$len_q;
				$candidates[$row['Subject Key']]=100*$factor;
			}
		}

		$sql=sprintf("select `Subject Key`,`Contact Name`,`Contact Surname` from `Contact Bridge` EB  left join `Contact Dimension` E on (EB.`Contact Key`=E.`Contact Key`) left join `Customer Dimension` CD on (CD.`Customer Key`=`Subject Key`) where true $where_store and `Subject Type`='Customer' and  `Contact Surname`  like '%s%%'  limit 20",
			$q);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Contact Surname']==$q) {
				$candidates[$row['Subject Key']]=120;
			} else {
				$len_name=$row['Contact Surname'];
				$len_q=strlen($q);
				$factor=$len_name/$len_q;
				$candidates[$row['Subject Key']]=100*$factor;
			}
		}

		$sql=sprintf("select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Name` like '%s%%' limit 50",
			$q);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Customer Name']==$q)
				$candidates[$row['Customer Key']]=55;
			else {

				$len_name=strlen($row['Customer Name']);
				$len_q=strlen($q);
				$factor=$len_q/$len_name;
				$candidates[$row['Customer Key']]=50*$factor;
			}
		}

		$sql=sprintf("select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Name`  REGEXP '[[:<:]]%s' limit 100 ",
			$q);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Customer Name']==$q)
				$candidates[$row['Customer Key']]=55;
			else {

				$len_name=strlen($row['Customer Name']);
				$len_q=strlen($q);
				$factor=$len_q/$len_name;
				$candidates[$row['Customer Key']]=50*$factor;
			}
		}

		arsort($candidates);



		$total_candidates=count($candidates);

		if ($total_candidates==0) {
			$response=array('state'=>200, 'results'=>0, 'data'=>'');
			echo json_encode($response);
			return;
		}

		$counter=0;
		$customer_keys='';
		$results=array();

		foreach ($candidates as $key=>$val) {
			$counter++;
			$customer_keys.=','.$key;
			$results[$key]='';
			if ($counter>$max_results) {
				break;
			}
		}
		$customer_keys=preg_replace('/^,/', '', $customer_keys);

		$sql=sprintf("select `Store Code`,`Customer Store Key`,`Customer Main Email Key`, `Customer Main XHTML Telephone`,`Customer Main Telephone Key`,`Customer Main Postal Code`,`Customer Key`,`Customer Main Contact Name`,`Customer Name`,`Customer Type`,`Customer Main Plain Email`,`Customer Main Location`,`Customer Tax Number` from `Customer Dimension` left join `Store Dimension` on (`Customer Store Key`=`Store Key`) where `Customer Key` in (%s)",
			$customer_keys);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {

			$name=$row['Customer Name'];
			if ($row['Customer Type']=='Company' and $row['Customer Main Contact Name']!='') {
				$name.= ', '.$row['Customer Main Contact Name'];
			}
			/*
			if ($row['Customer Tax Number']!='') {
				$name.='<br/>'.$row['Customer Tax Number'];
			}
			if ($row['Customer Type']=='Company') {
				$name.= '<br/>'.$row['Customer Main Contact Name'];
			}

			$address=$row['Customer Main Plain Email'];
			if ($row['Customer Main Telephone Key'])$address.='<br/>T: '.$row['Customer Main XHTML Telephone'];
			$address.='<br/>'.$row['Customer Main Location'];
			if ($row['Customer Main Postal Code'])$address.=', '.$row['Customer Main Postal Code'];
			$address=preg_replace('/^\<br\/\>/', '', $address);
			*/
			$results[$row['Customer Key']]=array(
				'store'=>$row['Store Code'],
				'label'=>highlightkeyword(sprintf('%06d', $row['Customer Key']), $queries ),
				'details'=>highlightkeyword($name, $queries ),
				'view'=>sprintf('customers/%d/%d', $row['Customer Store Key'], $row['Customer Key'])




			);
		}
		$results_data=array('n'=>count($results), 'd'=>$results);
		$cache->set($memcache_fingerprint, $results_data, $memcache_time);



	}
	$response=array('state'=>200, 'number_results'=>$results_data['n'], 'results'=>$results_data['d'], 'q'=>$q);

	echo json_encode($response);

}


function search_hr($db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=_trim($data['query']);

	if ($queries=='') {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}


	$memcache_fingerprint=$account->get('Account Code').'SEARCH_HR'.md5($queries);

	$cache = new Memcached();
	$cache->addServer($memcache_ip, 11211);


	if (strlen($queries)<=2) {
		$memcache_time=295200;
	}if (strlen($queries)<=3) {
		$memcache_time=86400;
	}if (strlen($queries)<=4) {
		$memcache_time=3600;
	}else {
		$memcache_time=300;

	}


	$results_data=$cache->get($memcache_fingerprint);


	if (!$results_data or $cache) {


		$candidates=array();

		// print_r(preg_split('/\s+/', $queries));

		foreach (preg_split('/\s+/', $queries) as  $q) {


			$sql=sprintf("select `Staff Key` from `Staff Dimension` where   `Staff ID`=%s",
				prepare_mysql($q));

			if ($result=$db->query($sql)) {

				if ($row = $result->fetch()) {
					$candidates['S '.$row['Staff Key']]=2000;
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;

			}







			$sql=sprintf("select `Staff Key`,`Staff Alias` from `Staff Dimension` where `Staff Alias` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {

				foreach ($result as $row) {
					if ($row['Staff Alias']==$q)
						$candidates['S '.$row['Staff Key']]=70;
					else {

						$len_name=strlen($row['Staff Alias']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['S '.$row['Staff Key']]=60*$factor;
					}
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}





			$sql=sprintf("select `Staff Key`,`Staff Name` from `Staff Dimension` where  `Staff Name`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);



			if ($result=$db->query($sql)) {

				foreach ($result as $row) {
					if ($row['Staff Name']==$q)
						$candidates['S '.$row['Staff Key']]=55;
					else {

						$len_name=strlen($row['Staff Name']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['S '.$row['Staff Key']]=60*$factor;
					}
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit('b');
			}



		}

		arsort($candidates);



		$total_candidates=count($candidates);

		if ($total_candidates==0) {
			$response=array('state'=>200, 'results'=>0, 'data'=>'');
			echo json_encode($response);
			return;
		}


		$counter=0;
		$results=array();


		$staff_keys='';


		foreach ($candidates as $key=>$val) {
			$_key=preg_split('/ /', $key);
			if ($_key[0]=='S') {

				$sql=sprintf("select `Staff Key`,`Staff ID`,`Staff Alias`,`Staff Name` from `Staff Dimension` where `Staff Key`=%d",
					$_key[1]);
				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$results[$row['Staff Key']]=array(
							'label'=>highlightkeyword($row['Staff Alias'], $queries),
							'details'=>highlightkeyword($row['Staff Name'], $queries),
							'view'=>sprintf('employee/%d',  $row['Staff Key']),
							'score'=>$val
						);
					}
				}else {
					print $sql;
					print_r($error_info=$db->errorInfo());
					exit('a');
				}

			}

			$counter++;

			if ($counter>$max_results)
				break;
		}


		$results_data=array('n'=>count($results), 'd'=>$results);
		$cache->set($memcache_fingerprint, $results_data, $memcache_time);



	}
	$response=array('state'=>200, 'number_results'=>$results_data['n'], 'results'=>$results_data['d'], 'q'=>$q);
	echo json_encode($response);

}


function highlightkeyword($str, $search) {
	$highlightcolor = "#daa732";
	$occurrences = substr_count(strtolower($str), strtolower($search));
	$newstring = $str;
	$match = array();

	for ($i=0;$i<$occurrences;$i++) {
		$match[$i] = stripos($str, $search, $i);
		$match[$i] = substr($str, $match[$i], strlen($search));
		$newstring = str_replace($match[$i], '[#]'.$match[$i].'[@]', strip_tags($newstring));
	}

	$newstring = str_replace('[#]', '<mark>', $newstring);
	$newstring = str_replace('[@]', '</mark>', $newstring);
	return $newstring;

}


?>
