<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2016 at 10:37:32 GMT+8, Yuwu, China
 Copyright (c) 20156 Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/text_functions.php';





if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {


case 'find_object':

	$data=prepare_values($_REQUEST, array(
			'query'=>array('type'=>'string'),
			'scope'=>array('type'=>'string'),
			'state'=>array('type'=>'json array')
		));

	$data['user']=$user;


	switch ($data['scope']) {
	case 'stores':
		find_stores($db, $account, $memcache_ip, $data);
		break;
	case 'countries':
		find_countries($db, $account, $memcache_ip, $data);
		break;
	case 'families':
		find_special_category('Family', $db, $account, $memcache_ip, $data);
		break;
	case 'departments':
		find_special_category('Department', $db, $account, $memcache_ip, $data);
		break;
	}



	break;
default:
	$response=array('state'=>405, 'resp'=>'Tab not found '.$tab);
	echo json_encode($response);
	exit;
	break;
}


function find_stores($db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='') {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}



	$where_store=sprintf(' and `Store Key` in (%s)', join(',', $user->stores));

	$memcache_fingerprint=$account->get('Account Code').'SEARCH_STORE'.md5($queries);

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




		foreach ($query_array as $q) {




			$sql=sprintf("select `Store Key`,`Store Code`,`Store Name` from `Store Dimension` where true $where_store and `Store Code` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Store Code']==$q)
						$candidates[$row['Store Key']]=1000;
					else {

						$len_name=strlen($row['Store Code']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Store Key']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}









			$sql=sprintf("select `Store Key`,`Store Code`,`Store Name` from `Store Dimension` where true $where_store and `Store Name`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Store Name']==$q)
						$candidates[$row['Store Key']]=55;
					else {

						$len_name=strlen($row['Store Name']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Store Key']]=50*$factor;
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

		$sql=sprintf("select `Store Code`,`Store Key`,`Store Name` from `Store Dimension` S where `Store Key` in (%s)",
			$product_keys);

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {





				$results[$row['Store Key']]=array(
					'code'=>highlightkeyword(sprintf('%s', $row['Store Code']), $queries ),
					'description'=>highlightkeyword($row['Store Name'], $queries ),

					'value'=>$row['Store Key'],
					'formatted_value'=>$row['Store Name'].' ('.$row['Store Code'].')'




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


function find_special_category($type, $db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='') {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}

	$root_keys='';

	if ($data['scope']=='store') {
		$store_keys=$data['scope_key'];
	} else {
		$store_keys=join(',', $user->stores);
	}

	$sql=sprintf("select GROUP_CONCAT(`Store %s Category Key`) as root_keys from  `Store Dimension` where `Store Key` in (%s)  ",
		addslashes($type),
		$store_keys);

	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {
			$root_keys=$row['root_keys'];
		}
	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
		exit;
	}



	if ($root_keys!='') {
		$where_root_categories=sprintf(' and `Category Root Key` in (%s)', join(',', $user->stores));
	}else {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}

	$memcache_fingerprint=$account->get('Account Code').'SEARCH_SPCL_CAT'.md5($queries);

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




		foreach ($query_array as $q) {




			$sql=sprintf("select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where true $where_root_categories and `Category Code` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Category Code']==$q)
						$candidates[$row['Category Key']]=1000;
					else {

						$len_name=strlen($row['Category Code']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Category Key']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}


			$sql=sprintf("select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where true $where_root_categories and `Category Label`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Category Label']==$q)
						$candidates[$row['Category Key']]=55;
					else {

						$len_name=strlen($row['Category Label']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Category Key']]=50*$factor;
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

		$sql=sprintf("select `Category Code`,`Category Key`,`Category Label` from `Category Dimension` C where `Category Key` in (%s)",
			$product_keys);

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {
				$results[$row['Category Key']]=array(
					'value'=>$row['Category Key'],
					'formatted_value'=>$row['Category Code'].', '.$row['Category Label'],
					'code'=>$row['Category Code'],
					'description'=>$row['Category Label'],

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


function find_countries($db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='') {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}




	$memcache_fingerprint=$account->get('Account Code').'SEARCH_COUNTRY'.md5($queries);

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




		foreach ($query_array as $q) {


			if (strlen($q)<=3) {


				$sql=sprintf("select `Country Key`,`Country Code`,`Country Name` from kbase.`Country Dimension` where `Country Code` like '%s%%' limit 20 ",
					$q);


				if ($result=$db->query($sql)) {
					foreach ($result as $row) {

						if ($row['Country Code']==$q)
							$candidates[$row['Country Key']]=1000;
						else {

							$len_name=strlen($row['Country Code']);
							$len_q=strlen($q);
							$factor=$len_q/$len_name;
							$candidates[$row['Country Key']]=500*$factor;
						}

					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}


			}
			
			if (strlen($q)==2) {


				$sql=sprintf("select `Country Key`,`Country Code`,`Country Name` from kbase.`Country Dimension` where  `Country 2 Alpha Code` like '%s%%' limit 20 ",
					$q);


				if ($result=$db->query($sql)) {
					foreach ($result as $row) {

						if ($row['Country Code']==$q)
							$candidates[$row['Country Key']]=1000;
						else {

							$len_name=strlen($row['Country Code']);
							$len_q=strlen($q);
							$factor=$len_q/$len_name;
							$candidates[$row['Country Key']]=500*$factor;
						}

					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}


			}




			$sql=sprintf("select `Country Key`,`Country Code`,`Country Name` from kbase.`Country Dimension` where  `Country Name`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Country Name']==$q)
						$candidates[$row['Country Key']]=55;
					else {

						$len_name=strlen($row['Country Name']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Country Key']]=50*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}
			
			$sql=sprintf("select `Country Key`,`Country Code`,`Country Local Name` from kbase.`Country Dimension` where  `Country Local Name`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Country Local Name']==$q)
						$candidates[$row['Country Key']]=55;
					else {

						$len_name=strlen($row['Country Local Name']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates[$row['Country Key']]=50*$factor;
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

		$sql=sprintf("select `Country Code`,`Country Key`,`Country Name` from kbase.`Country Dimension` C where `Country Key` in (%s)",
			$product_keys);

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {





				$results[$row['Country Key']]=array(
					'code'=>highlightkeyword(sprintf('%s', $row['Country Code']), $queries ),
					'description'=>highlightkeyword($row['Country Name'], $queries ),

					'value'=>$row['Country Code'],
					'formatted_value'=>$row['Country Name'].' ('.$row['Country Code'].')'




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


?>
