<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 December 2015 at 16:57:07 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function extract_geo_groups($str, $q_country_code='C.`Customer Main Country Code`', $q_wregion_code='C.`Customer Main Country Code`', $q_town_name='C.`Customer Main Town`', $q_post_code='C.`Customer Main Postal Code`') {
	if ($str=='')
		return '';
	$where='';
	$where_c='';
	$where_t='';
	$where_pc='';
	$where_wr='';
	$use_product=false;
	$town_names=array();
	$post_code_names=array();

	$country_codes=array();
	$wregion_codes=array();

	if (preg_match_all('/t\([a-z0-9\-\,]*\)/i', $str, $matches)) {
		foreach ($matches[0] as $match) {
			$_towns=preg_replace('/\)$/i', '', preg_replace('/^t\(/i', '', $match));
			$_towns=preg_split('/\s*,\s*/i', $_towns);
			foreach ($_towns as $town) {
				if ($town!='') {
					$town=addslashes($town);
					$town_names[$town]=$town;
				} else {
					$town_names['_none_']='';
				}
			}
		}
		if (count($town_names)>0)
			$where_t.= " or $q_town_name in ('".join("','", $town_names)."')";

		$str=preg_replace('/t\([a-z0-9\-\,]*\)/i', '', $str);
	}

	if (preg_match_all('/pc\([a-z0-9\-\,]*\)/i', $str, $matches)) {
		foreach ($matches[0] as $match) {
			$_post_codes=preg_replace('/\)$/i', '', preg_replace('/^pc\(/i', '', $match));
			$_post_codes=preg_split('/\s*,\s*/i', $_post_codes);
			foreach ($_post_codes as $post_code) {
				if ($post_code!='') {
					$post_code=addslashes($post_code);
					$post_code_names[$post_code]=$post_code;
				} else {
					$town_names['_none_']='';
				}
			}
		}
		if (count($post_code_names)>0)
			$where_t.= " or $q_post_code in ('".join("','", $post_code_names)."')";

		$str=preg_replace('/pc\([a-z0-9\-\,]*\)/i', '', $str);
	}
	if (preg_match_all('/wr\([a-z0-9\-\,]*\)/i', $str, $matches)) {


		foreach ($matches[0] as $match) {

			$_world_regions=preg_replace('/\)$/i', '', preg_replace('/^wr\(/i', '', $match));
			$_world_regions=preg_split('/\s*,\s*/i', $_world_regions);

			// print_r($_world_regions);
			foreach ($_world_regions as $world_region) {
				if ($world_region!='' and strlen($world_region)==4) {
					$world_region=addslashes($world_region);
					$wregion_codes[$world_region]=$world_region;
				}

			}
		}
		$sql=sprintf("select `Country Code` from kbase.`Country Dimension` where `World Region Code` in (%s)", "'".join("','", $wregion_codes)."'");
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$country_codes[$row['Country Code']]=$row['Country Code'];

		}
		$str=preg_replace('/wr\([a-z0-9\-\,]*\)/i', '', $str);
	}
	$products=preg_split('/\s*,\s*/i', $str);
	$where_c='';
	foreach ($products as $product) {
		if ($product!='' and strlen($product)==3) {
			$product=addslashes($product);
			$country_codes[$product]=$product;

		}
	}
	if (count($country_codes)>0)
		$where_c.= " or $q_country_code in ('".join("','", $country_codes)."')";

	$where=preg_replace('/^\s*or\s*/i', '', $where_wr.$where_c.$where_pc.$where_t);
	if ($where!='')
		$where=' and '.$where;
	return $where;

}


function extract_product_groups($str, $store_key=0, $q_prod_name='OTF.`Product Code` like', $q_prod_id='OTF.`Product ID`', $q_group_id='OTF.`Product Family Key` in', $q_department_id='OTF.`Product Department Key`  in') {


	if ($str=='')
		return '';
	$where='';
	$where_g='';
	$where_d='';
	$use_product=false;




	$department_names=array();
	$department_ids=array();

	if (preg_match_all('/d\([a-z0-9\-\,]*\)/i', $str, $matches)) {


		foreach ($matches[0] as $match) {

			$_groups=preg_replace('/\)$/i', '', preg_replace('/^d\(/i', '', $match));
			$_groups=preg_split('/\s*,\s*/i', $_groups);

			foreach ($_groups as $group) {
				//$use_product=true;
				if (is_numeric($group)) {
					$department_ids[$group]=$group;
				} else {
					$department_names[$group]=prepare_mysql($group);

				}

			}
		}
		$str=preg_replace('/d\([a-z0-9\-\,]*\)/i', '', $str);
	}
	if (count($department_names)>0) {
		if ($store_key and is_numeric($store_key))
			$store_where=' and `Product Department Store Key`='.$store_key;
		else
			$store_where='';
		$sql=sprintf("select `Product Department Key` from `Product Department Dimension` where `Product Department Code` in (%s) %s ", join(',', $department_names), $store_where);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$department_ids[$row['Product Department Key']]=$row['Product Department Key'];
		}

	}

	if (count($department_ids)>0) {
		$where_d='or '.$q_department_id.' ('.join(',', $department_ids).') ';
		//   $use_product=true;
	}



	$family_names=array();
	$family_ids=array();

	if (preg_match_all('/f\([a-z0-9\-\,]*\)/i', $str, $matches)) {

		foreach ($matches[0] as $match) {

			$_groups=preg_replace('/\)$/i', '', preg_replace('/^f\(/i', '', $match));
			$_groups=preg_split('/\s*,\s*/i', $_groups);

			foreach ($_groups as $group) {
				//$use_product=true;
				if (is_numeric($group)) {
					$family_ids[$group]=$group;
				} else {
					$family_names[$group]=prepare_mysql($group);

				}

			}
		}
		$str=preg_replace('/f\([a-z0-9\-\,]*\)/i', '', $str);
	}



	if (count($family_names)>0) {
		if ($store_key and is_numeric($store_key))
			$store_where=' and `Product Family Store Key`='.$store_key;
		else
			$store_where='';
		$sql=sprintf("select `Product Family Key` from `Product Family Dimension` where `Product Family Code` in (%s) %s ", join(',', $family_names), $store_where);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$family_ids[$row['Product Family Key']]=$row['Product Family Key'];
		}

	}

	if (count($family_ids)>0) {
		$where_g='or '.$q_group_id.' ('.join(',', $family_ids).') ';
		// $use_product=true;
	}
	//print_r($family_ids);


	$products=preg_split('/\s*,\s*/i', $str);

	$where_p='';
	foreach ($products as $product) {
		if ($product!='') {
			$product=addslashes($product);
			if (is_numeric($product))
				$where_p.= " or $q_prod_id  '$product'";
			else
				$where_p.= " or $q_prod_name  '$product'";
		}
	}



	$where=preg_replace('/^\s*or\s*/i', '', $where_d.$where_g.$where_p);




	return array('('.$where.')', $use_product);

}




?>
