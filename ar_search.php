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
require_once 'utils/text_functions.php';





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
	}elseif ($data['state']['module']=='orders') {
		if ($data['state']['current_store']) {
			$data['scope']='store';
			$data['scope_key']=$data['state']['current_store'];
		}else {
			$data['scope']='stores';
		}
		search_orders($db, $account, $memcache_ip, $data);
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

	}elseif ($data['state']['module']=='suppliers') {
		search_suppliers($db, $account, $memcache_ip, $data);

	}elseif ($data['state']['module']=='delivery_notes') {
		if ($data['state']['current_store']) {
			$data['scope']='store';
			$data['scope_key']=$data['state']['current_store'];
		}else {
			$data['scope']='stores';
		}
		search_delivery_notes($db, $account, $memcache_ip, $data);
	}elseif ($data['state']['module']=='invoices') {
		if ($data['state']['current_store']) {
			$data['scope']='store';
			$data['scope_key']=$data['state']['current_store'];
		}else {
			$data['scope']='stores';
		}
		search_invoices($db, $account, $memcache_ip, $data);
	}

	break;


default:
	$response=array('state'=>405, 'resp'=>'Tab not found '.$tab);
	echo json_encode($response);
	exit;
	break;
}

function search_suppliers($db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='' ) {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}




	$memcache_fingerprint=$account->get('Account Code').'SEARCH_SUPPLIERS'.md5($queries);

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


			$sql=sprintf("select `Supplier Key`,`Supplier Code` from `Supplier Dimension` where `Supplier Code` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Supplier Code']==$q)
						$candidates['S'.$row['Supplier Key']]=1000;
					else {

						$len_name=strlen($row['Supplier Code']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['S'.$row['Supplier Key']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}

			$sql=sprintf("select `Supplier Key`,`Supplier Name` from `Supplier Dimension` where `Supplier Name`  REGEXP '[[:<:]]%s' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Supplier Name']==$q)
						$candidates['S'.$row['Supplier Key']]=800;
					else {

						$len_name=strlen($row['Supplier Name']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['S'.$row['Supplier Key']]=400*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}


			$sql=sprintf("select `Agent Key`,`Agent Code` from `Agent Dimension` where `Agent Code` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Agent Code']==$q)
						$candidates['A'.$row['Agent Key']]=1000;
					else {

						$len_name=strlen($row['Agent Code']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['A'.$row['Agent Key']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}

			$sql=sprintf("select `Agent Key`,`Agent Name` from `Agent Dimension` where `Agent Name`  REGEXP '[[:<:]]%s' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Agent Name']==$q)
						$candidates['A'.$row['Agent Key']]=800;
					else {

						$len_name=strlen($row['Agent Name']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['A'.$row['Agent Key']]=400*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}



			$sql=sprintf("select `Supplier Part Key`,`Supplier Part Reference` from `Supplier Part Dimension` where `Supplier Part Reference` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Supplier Part Reference']==$q)
						$candidates['P'.$row['Supplier Part Key']]=1000;
					else {

						$len_name=strlen($row['Supplier Part Reference']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['P'.$row['Supplier Part Key']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}




			$sql=sprintf("select `Supplier Part Key`,`Part Reference` from `Supplier Part Dimension`  left join   `Part Dimension`  on (`Supplier Part Part SKU`=`Part SKU`) where `Part Reference` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Part Reference']==$q)
						$candidates['P'.$row['Supplier Part Key']]=750;
					else {

						$len_name=strlen($row['Part Reference']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['P'.$row['Supplier Part Key']]=375*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}





			$sql=sprintf("select `Supplier Part Key`,`Part Unit Description` from `Supplier Part Dimension` left join   `Part Dimension`  on (`Supplier Part Part SKU`=`Part SKU`)  where `Part Unit Description`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Part Unit Description']==$q)
						$candidates['P'.$row['Supplier Part Key']]=55;
					else {

						$len_name=strlen($row['Part Unit Description']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['P'.$row['Supplier Part Key']]=50*$factor;
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
		$supplier_parts_keys='';
		$supplier_keys='';
		$agent_keys='';
		$results=array();
		$number_supplier_parts_keys=0;
		$number_supplier_keys=0;
		$number_agent_keys=0;

		foreach ($candidates as $_key=>$val) {
			$counter++;

			if ($_key[0]=='P') {
				$key=preg_replace('/^P/', '', $_key);
				$supplier_parts_keys.=','.$key;
				$results[$_key]='';
				$number_supplier_parts_keys++;

			}elseif ($_key[0]=='S') {
				$key=preg_replace('/^S/', '', $_key);
				$supplier_keys.=','.$key;
				$results[$_key]='';
				$number_supplier_keys++;

			}elseif ($_key[0]=='A') {
				$key=preg_replace('/^A/', '', $_key);
				$agent_keys.=','.$key;
				$results[$_key]='';
				$number_agent_keys++;

			}

			if ($counter>$max_results) {
				break;
			}
		}
		$supplier_parts_keys=preg_replace('/^,/', '', $supplier_parts_keys);
		$supplier_keys=preg_replace('/^,/', '', $supplier_keys);
		$agent_keys=preg_replace('/^,/', '', $agent_keys);


		if ($number_supplier_parts_keys) {
			$sql=sprintf("select `Supplier Part Key`,`Supplier Part Supplier Key`,`Supplier Part Reference`,`Part Unit Description` from `Supplier Part Dimension` left join   `Part Dimension`  on (`Supplier Part Part SKU`=`Part SKU`)   where `Supplier Part Key` in (%s)",
				$supplier_parts_keys);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {





					$results['P'.$row['Supplier Part Key']]=array(
						'label'=>'<i class="fa fa-stop fa-fw "></i> '.highlightkeyword(sprintf('%s', $row['Supplier Part Reference']), $queries ),
						'details'=>highlightkeyword($row['Part Unit Description'], $queries ),
						'view'=>sprintf('supplier/%d/part/%d', $row['Supplier Part Supplier Key'] , $row['Supplier Part Key'])




					);

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}
		}

		if ($number_supplier_keys) {

			$sql=sprintf("select `Supplier Key`,`Supplier Code`,`Supplier Name` from `Supplier Dimension`  where `Supplier Key` in (%s)",
				$supplier_keys);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {





					$results['S'.$row['Supplier Key']]=array(
						'label'=>'<i class="fa fa-ship fa-fw "></i> '.highlightkeyword(sprintf('%s', $row['Supplier Code']), $queries ),
						'details'=>highlightkeyword($row['Supplier Name'], $queries ),
						'view'=>sprintf('supplier/%d', $row['Supplier Key'] )




					);

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}


		}

		if ($number_agent_keys) {

			$sql=sprintf("select `Agent Key`,`Agent Code`,`Agent Name` from `Agent Dimension`  where `Agent Key` in (%s)",
				$agent_keys);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {





					$results['A'.$row['Agent Key']]=array(
						'label'=>'<i class="fa fa-user-secret fa-fw "></i> '.highlightkeyword(sprintf('%s', $row['Agent Code']), $queries ),
						'details'=>highlightkeyword($row['Agent Name'], $queries ),
						'view'=>sprintf('agent/%d', $row['Agent Key'] )




					);

				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}


		}


		$results_data=array('n'=>count($results), 'd'=>$results);
		$cache->set($memcache_fingerprint, $results_data, $memcache_time);



	}
	$response=array('state'=>200, 'number_results'=>$results_data['n'], 'results'=>$results_data['d'], 'q'=>$queries);

	echo json_encode($response);

}


function search_inventory($db, $account, $memcache_ip, $data) {



	$cache=false;
	$max_results=10;
	$user=$data['user'];
	$queries=trim($data['query']);

	if ($queries=='' ) {
		$response=array('state'=>200, 'results'=>0, 'data'=>'');
		echo json_encode($response);
		return;
	}




	$memcache_fingerprint=$account->get('Account Code').'SEARCH_INVENTORY'.md5($queries);

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


	if (!$results_data ) {


		$candidates=array();

		$query_array=preg_split('/\s+/', $queries);
		$number_queries=count($query_array);




		foreach ($query_array as $q) {




			$sql=sprintf("select `Part SKU`,`Part Reference`,`Part Unit Description` from `Part Dimension` where `Part Reference` like '%s%%' limit 20 ",
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









			$sql=sprintf("select `Part SKU`,`Part Reference`,`Part Unit Description` from `Part Dimension` where `Part Unit Description`  REGEXP '[[:<:]]%s' limit 100 ",
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

		$sql=sprintf("select P.`Part SKU`,`Part Reference`,`Part Unit Description` from `Part Dimension` P  where P.`Part SKU` in (%s)",
			$product_keys);

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {





				$results[$row['Part SKU']]=array(
					'label'=>highlightkeyword(sprintf('%s', $row['Part Reference']), $queries ),
					'details'=>highlightkeyword($row['Part Unit Description'], $queries ),
					'view'=>sprintf('part/%d', $row['Part SKU'])




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
			$where_store=sprintf(' and `Product Store Key`=%d', $data['scope_key']);
			$where_cat_store=sprintf(' and `Category Store Key`=%d', $data['scope_key']);

		}else {
			$where_store=' and false';
			$where_cat_store=' and false';
		}
	} else {
		if (count($user->stores)==$account->data['Stores']) {
			$where_store='';
			$where_cat_store='';
		}else {
			$where_store=sprintf(' and `Product Store Key` in (%s)', join(',', $user->stores));
			$where_cat_store=sprintf(' and `Category Store Key` in (%s)', join(',', $user->stores));
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
				$sql=sprintf("select `Product ID`,`Product Code`,`Product Name` from `Product Dimension` where true $where_store and `Product ID`=%d",
					$q);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$candidates['P'.$row['Product ID']]=2000;
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}


			}


		}


		foreach ($query_array as $q) {




			$sql=sprintf("select `Product ID`,`Product Code`,`Product Name` from `Product Dimension` where true $where_store and `Product Code` like '%s%%' limit 20 ",
				$q);


			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Product Code']==$q)
						$candidates['P'.$row['Product ID']]=1000;
					else {

						$len_name=strlen($row['Product Code']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['P'.$row['Product ID']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}

			$sql=sprintf("select `Product ID`,`Product Code`,`Product Name` from `Product Dimension` where true $where_store and `Product Name`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Product Name']==$q)
						$candidates['P'.$row['Product ID']]=55;
					else {

						$len_name=strlen($row['Product Name']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['P'.$row['Product ID']]=50*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}

			$sql=sprintf("select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where `Category Scope`='Product'   $where_cat_store and `Category Code` like '%s%%' limit 20 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					if ($row['Category Code']==$q)
						$candidates['C'.$row['Category Key']]=1000;
					else {

						$len_name=strlen($row['Category Code']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['C'.$row['Category Key']]=500*$factor;
					}

				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}
			$sql=sprintf("select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where `Category Scope`='Product'   $where_cat_store and  `Category Label`  REGEXP '[[:<:]]%s' limit 100 ",
				$q);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {
					if ($row['Category Label']==$q)
						$candidates['C'.$row['Category Key']]=55;
					else {

						$len_name=strlen($row['Category Label']);
						$len_q=strlen($q);
						$factor=$len_q/$len_name;
						$candidates['C'.$row['Category Key']]=50*$factor;
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
		$category_keys='';

		$results=array();
		$number_products_keys=0;
		$number_categories_keys=0;

		foreach ($candidates as $_key=>$val) {
			$counter++;

			if ($_key[0]=='P') {
				$key=preg_replace('/^P/', '', $_key);
				$product_keys.=','.$key;
				$results[$_key]='';
				$number_products_keys++;

			}elseif ($_key[0]=='C') {
				$key=preg_replace('/^C/', '', $_key);
				$category_keys.=','.$key;
				$results[$_key]='';
				$number_categories_keys++;

			}

			if ($counter>$max_results) {
				break;
			}
		}
		$product_keys=preg_replace('/^,/', '', $product_keys);
		$category_keys=preg_replace('/^,/', '', $category_keys);


		if ($number_products_keys) {
			$sql=sprintf("select `Store Code`,`Store Key`,`Product ID`,`Product Code`,`Product Name` from `Product Dimension` left join `Store Dimension` S on (`Product Store Key`=S.`Store Key`) where `Product ID` in (%s)",
				$product_keys);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					$results[$row['Product ID']]=array(
						'store'=>$row['Store Code'],
						'label'=>highlightkeyword(sprintf('%s', $row['Product Code']), $queries ),
						'details'=>highlightkeyword($row['Product Name'], $queries ),
						'view'=>sprintf('products/%d/%d', $row['Store Key'], $row['Product ID'])




					);
				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}
		}


		if ($number_categories_keys) {
			$sql=sprintf("select `Category Code`,`Category Store Key`,`Category Key`,`Category Code`,`Category Label`,`Store Code` from `Category Dimension` left join `Store Dimension` S on (`Category Store Key`=S.`Store Key`) where `Category Key` in (%s)",
				$category_keys);

			if ($result=$db->query($sql)) {
				foreach ($result as $row) {

					$results[$row['Category Key']]=array(
						'store'=>$row['Store Code'],
						'label'=>highlightkeyword(sprintf('%s', $row['Category Code']), $queries ),
						'details'=>highlightkeyword($row['Category Label'], $queries ),
						'view'=>sprintf('products/%d/category/%d', $row['Category Store Key'], $row['Category Key'])




					);
				}
			}else {
				print_r($error_info=$db->errorInfo());
				print $sql;
				exit;
			}
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


function search_orders($db, $account, $memcache_ip, $data) {

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
			$where_store=sprintf(' and `Order Store Key`=%d', $data['scope_key']);
		}else {
			$where_store=' and false';
		}
	} else {
		if (count($user->stores)==$account->data['Stores']) {
			$where_store='';
		}else {
			$where_store=sprintf(' and `Order Store Key` in (%s)', join(',', $user->stores));
		}

		$stores=join(',', $user->stores);
	}
	$memcache_fingerprint=$account->get('Account Code').'SEARCH_ORDER'.$stores.md5($queries);

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



		$sql=sprintf("select `Order Key`,`Order Public ID` from `Order Dimension` where true $where_store and `Order Public ID` like '%s%%'  order by `Order Key` desc limit 10 ",
			$q);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Order Public ID']==$q)
				$candidates[$row['Order Key']]=30;
			else {

				$len_name=strlen($row['Order Public ID']);
				$len_q=strlen($q);
				$factor=$len_q/$len_name;
				$candidates[$row['Order Key']]=20*$factor;
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
		$order_keys='';
		$results=array();

		foreach ($candidates as $key=>$val) {
			$counter++;
			$order_keys.=','.$key;
			$results[$key]='';
			if ($counter>$max_results) {
				break;
			}
		}
		$order_keys=preg_replace('/^,/', '', $order_keys);

		$sql=sprintf("select `Order Key`,`Store Code`,`Order Store Key`,`Order Public ID`,`Order Current Dispatch State`,`Order Customer Name` from `Order Dimension` left join `Store Dimension` on (`Order Store Key`=`Store Key`) where `Order Key` in (%s)",
			$order_keys);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {


			switch ($row['Order Current Dispatch State']) {
			case 'In Process':
				$details= _('In Process');
				break;
			case 'In Process by Customer':
				$details= _('In Process by Customer');
				break;
			case 'Submitted by Customer':
				$details= _('Submitted by Customer');
				break;
			case 'Ready to Pick':
				$details= _('Ready to Pick');
				break;
			case 'Picking & Packing':
				$details= _('Picking & Packing');
				break;
			case 'Packed Done':
				$details= _('Packed & Checked');
				break;
			case 'Ready to Ship':
				$details= _('Ready to Ship');
				break;
			case 'Dispatched':
				$details= _('Dispatched');
				break;
			case 'Unknown':
				$details= _('Unknown');
				break;
			case 'Packing':
				$details= _('Packing');
				break;
			case 'Cancelled':
				$details= _('Cancelled');
				break;
			case 'Suspended':
				$details= _('Suspended');
				break;

			default:
				$details= $row['Order Current Dispatch State'];
				break;
			}


			$details.='<span class="padding_left_20">'.$row['Order Customer Name'].'</span>';

			$results[$row['Order Key']]=array(
				'store'=>$row['Store Code'],
				'label'=>highlightkeyword(sprintf('%s', $row['Order Public ID']), $queries ),
				'details'=>highlightkeyword($details, $queries ),
				'view'=>sprintf('orders/%d/%d', $row['Order Store Key'], $row['Order Key'])




			);
		}
		$results_data=array('n'=>count($results), 'd'=>$results);
		$cache->set($memcache_fingerprint, $results_data, $memcache_time);



	}
	$response=array('state'=>200, 'number_results'=>$results_data['n'], 'results'=>$results_data['d'], 'q'=>$q);

	echo json_encode($response);

}

function search_delivery_notes($db, $account, $memcache_ip, $data) {

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
			$where_store=sprintf(' and `Delivery Note Store Key`=%d', $data['scope_key']);
		}else {
			$where_store=' and false';
		}
	} else {
		if (count($user->stores)==$account->data['Stores']) {
			$where_store='';
		}else {
			$where_store=sprintf(' and `Delivery Note Store Key` in (%s)', join(',', $user->stores));
		}

		$stores=join(',', $user->stores);
	}
	$memcache_fingerprint=$account->get('Account Code').'SEARCH_DN'.$stores.md5($queries);

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



		$sql=sprintf("select `Delivery Note Key`,`Delivery Note ID` from `Delivery Note Dimension` where true $where_store and `Delivery Note ID` like '%s%%'  order by `Delivery Note Key` desc limit 10 ",
			$q);
			
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Delivery Note ID']==$q)
				$candidates[$row['Delivery Note Key']]=30;
			else {

				$len_name=strlen($row['Delivery Note ID']);
				$len_q=strlen($q);
				$factor=$len_q/$len_name;
				$candidates[$row['Delivery Note Key']]=20*$factor;
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
		$order_keys='';
		$results=array();

		foreach ($candidates as $key=>$val) {
			$counter++;
			$order_keys.=','.$key;
			$results[$key]='';
			if ($counter>$max_results) {
				break;
			}
		}
		$order_keys=preg_replace('/^,/', '', $order_keys);

		$sql=sprintf("select `Delivery Note Key`,`Store Code`,`Delivery Note Store Key`,`Delivery Note ID`,`Delivery Note State` from `Delivery Note Dimension` left join `Store Dimension` on (`Delivery Note Store Key`=`Store Key`) where `Delivery Note Key` in (%s)",
			$order_keys);
			
			
			
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {


			switch ($row['Delivery Note State']) {

			case 'Picker & Packer Assigned':
				$details= _('Picker & packer assigned');
				break;
			case 'Picking & Packing':
				$details= _('Picking & packing');
				break;
			case 'Packer Assigned':
				$details= _('Packer assigned');
				break;
			case 'Ready to be Picked':
				$details= _('Ready to be picked');
				break;
			case 'Picker Assigned':
				$details= _('Picker assigned');
				break;
			case 'Picking':
				$details= _('Picking');
				break;
			case 'Picked':
				$details= _('Picked');
				break;
			case 'Packing':
				$details= _('Packing');
				break;
			case 'Packed':
				$details= _('Packed');
				break;
			case 'Approved':
				$details= _('Approved');
				break;
			case 'Dispatched':
				$details= _('Dispatched');
				break;
			case 'Cancelled':
				$details= _('Cancelled');
				break;
			case 'Cancelled to Restock':
				$details= _('Cancelled to restock');
				break;
			case 'Packed Done':
				$details= _('Packed done');
				break;
			default:
				$details= $row['Delivery Note State'];
				break;
			}


			//$details.='<span class="padding_left_20">'.$row['Delivery Note Customer Name'].'</span>';

			$results[$row['Delivery Note Key']]=array(
				'store'=>$row['Store Code'],
				'label'=>highlightkeyword(sprintf('%s', $row['Delivery Note ID']), $queries ),
				'details'=>highlightkeyword($details, $queries ),
				'view'=>sprintf('delivery_notes/%d/%d', $row['Delivery Note Store Key'], $row['Delivery Note Key'])




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







			$sql=sprintf("select `Staff Key`,`Staff Alias` from `Staff Dimension` where `Staff Currently Working`='Yes' and   `Staff Alias` like '%s%%' limit 20 ",
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





			$sql=sprintf("select `Staff Key`,`Staff Name` from `Staff Dimension` where `Staff Currently Working`='Yes' and  `Staff Name`  REGEXP '[[:<:]]%s' limit 100 ",
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

				$sql=sprintf("select `Staff Key`,`Staff ID`,`Staff Alias`,`Staff Name` from `Staff Dimension` where  `Staff Key`=%d",
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





?>
