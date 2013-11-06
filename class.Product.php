<?php
/*
  File: Product.php

  This file contains the Product Class

  About:get  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) on009, Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.DealComponent.php';
include_once 'class.SupplierProduct.php';
include_once 'class.Part.php';
include_once 'class.Store.php';
include_once 'class.Family.php';
include_once 'common_store_functions.php';
include_once 'class.Department.php';
/* class: product
   Class to manage the *Product Family Dimension* table
*/



class product extends DB_Table {
	public $new_key=false;
	public $new_code=false;
	public $new_id=false;

	public $product=array();
	public $categories=array();
	public $parents=array();
	public $childs=array();
	public $supplier=false;
	public $locations=false;
	public $notes=array();

	public $weblink=false;
	public $parts=false;
	public $parts_skus=false;
	public $parts_location=false;
	public $mode='pid';
	public $system_format=true;
	public $msg='';


	public $new_value=false;
	public $new_part_list=false;
	public $part_list_updated=false;

	public $new_data=array();
	public $data=array();
	// Variable: new
	// Indicate if a new product was created
	public $deleted=false;


	public $location_to_update=false;
	// Variable: id
	// Reference tothe Product Key

	public $unknown_txt='Unknown';

	private $historic_keys=array();
	private $historic_keys_with_same_code=array();
	var $id=false;
	var $locale;
	var $url;
	var $user_id;
	var $method;
	var $match=true;
	/*
      Constructor: Product
      Initializes the object.

      Parameters:
      a1 - Tag or Product Key
    */
	function Product($a1,$a2=false,$a3=false) {
		global $external_DB_link;
		$this->external_DB_link=$external_DB_link;
		$this->table_name='Product';
		$this->ignore_fields=array(
			'Product Key'
		);
		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
				$this->msg=$this->create($a2);
			}
		elseif ($a1=='find') {
			$this->find($a2,$a3);
		}
		else
			$this->get_data($a1,$a2,$a3);
	}


	function get_data($tipo,$tag,$extra=false) {

		//  print_r($tag['editor']);
		if (isset($tag['editor']) and is_array($tag['editor'])) {

			foreach ($tag['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}


		if ($tipo=='id' or $tipo=='key') {
			$this->mode='key';
			$sql=sprintf("select * from `Product History Dimension` where `Product Key`=%d ",$tag);

			$result=mysql_query($sql);
			if ( ($row=mysql_fetch_array($result, MYSQL_ASSOC))) {

				foreach ($row as $key=>$value) {
					$this->data[$key]=$value;
				}

				$this->id=$this->data['Product Key'];
				$this->pid=$this->data['Product ID'];

				//$this->get_data('pid',$this->pid);


			} else
				return;
			mysql_free_result($result);

			$sql=sprintf("select `Product Family Code`,`Product Family Key`,`Product Main Department Key`,`Product Store Key`,`Product Locale`,`Product Code`,`Product Current Key`,`Product Parts Weight`,`Product Units Per Case`,`Product Code`,`Product Type`,`Product Record Type`,`Product Sales Type`,`Product Availability Type`,`Product Stage` from `Product Dimension` where `Product ID`=%d ",$this->pid);
			//  print "$sql\n";
			$result=mysql_query($sql);
			//print "hols";
			if ( $row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->locale=$row['Product Locale'];
				$this->code=$row['Product Code'];
				$items_from_parent=array('Product Family Code','Product Current Key','Product Parts Weight','Product Units Per Case','Product Code','Product Type','Product Record Type','Product Sales Type','Product Family Key','Product Main Department Key','Product Store Key','Product Availability Type','Product Stage');
				foreach ($items_from_parent as $item)
					//   print "** $item\n";
					//   print_r($row);
					$this->data[$item]=$row[$item];

				// print "caca";
			} else
				return;
			mysql_free_result($result);
			return;
		}
		else if ($tipo=='pid') {
				$sql=sprintf("select * from `Product Dimension` where `Product ID`=%d    ",$tag);
				$this->mode='pid';
				$result=mysql_query($sql);

				if ( ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))) {
					$this->locale=$this->data['Product Locale'];
					$this->id=$this->data['Product Current Key'];
					$this->pid=$this->data['Product ID'];
					$this->code=$this->data['Product Code'];
				}

				mysql_free_result($result);


				return;
			}
		elseif ($tipo=='code') {
			$this->mode='code';
			$sql=sprintf("select * from `Product Dimension` where `Product Code`=%s  and `Product Store Key`=%d and `Product Record Type`='Normal'",prepare_mysql($tag), $extra);

			$result=mysql_query($sql);
			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->code=$this->data['Product Code'];
				$this->id=$this->data['Product ID'];
			} else
				$this->match=false;
			//print_r($this->data);
			return;

		}
		if ($tipo=='code_store' or $tipo=='code-store') {
			$this->mode='pid';
			$sql=sprintf("select * from `Product Dimension` where  `Product Code`=%s and `Product Store Key`=%d    order by
                         `Product Record Type`='Normal' DESC
                        ,`Product Record Type`='Historic' DESC",prepare_mysql($tag),$extra);
			//print $sql;
			//print_r( $tag);exit;
			$result=mysql_query($sql);
			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->id=$this->data['Product Current Key'];
				$this->locale=$this->data['Product Locale'];
				$this->pid=$this->data['Product ID'];
				$this->code=$this->data['Product Code'];
			}
			return;

		}
	}


	function find($raw_data,$options) {

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found_in_code=false;
		$this->found_in_id=false;
		$this->found_in_key=false;
		$this->found_in_store=false;


		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}
		//print_r($raw_data);
		$data=$this->get_base_data();
		foreach ($raw_data as $_key=>$value) {
			$key=strtolower($_key);
			if (array_key_exists($key,$data))
				$data[$key]=_trim($value);
		}
		//print_r($data);

		if ($data['product code']=='' or $data['product price']=='') {
			$this->error=true;
			return;
		}

		if ($data['product store key']=='')
			$data['product store key']=1;
		if ($data['product name']=='')
			$data['product name']=$data['product code'];


		$sql=sprintf("select `Product Code` from `Product Same Code Dimension` where `Product Code`=%s  "
			,prepare_mysql($data['product code'])
		);
		//print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found_in_code=true;
			$this->found=true;
			$this->found_code=$row['Product Code'];

			$sql=sprintf("select `Product Code` from `Product Dimension` where `Product Code`=%s  and  `Product Store Key`=%d "
				,prepare_mysql($data['product code'])
				,$data['product store key']
			);
			//print "$sql\n";
			$result4=mysql_query($sql);
			if ($row4=mysql_fetch_array($result4)) {
				$this->found_in_store=true;
				$this->found=true;


				$sql=sprintf("select `Product ID`,`Product Current Key` from `Product Dimension` where `Product Code`=%s and `Product Units Per Case`=%f and `Product Unit Type`=%s  and  `Product Store Key`=%d  ORDER BY `Product Record Type` "
					,prepare_mysql($data['product code'])
					,$data['product units per case']
					,prepare_mysql($data['product unit type'])
					,$data['product store key']
				);
				// print "aqui zxseu $sql\n";
				$result2=mysql_query($sql);
				if ($row2=mysql_fetch_array($result2)) {
					$this->found_in_id=true;
					$this->found=true;
					$this->found_id=$row2['Product ID'];
					$this->get_data('pid',$this->found_id);
					$sql=sprintf("select `Product Key` from `Product History Dimension` where `Product ID`=%d and `Product History Price`=%.2f and `Product History Name`=%s  "
						,$row2['Product ID']
						,$data['product price']
						,prepare_mysql($data['product name'])
					);
					// print "$sql\n";
					$result3=mysql_query($sql);
					if ($row3=mysql_fetch_array($result3)) {
						$this->found_in_key=true;
						$this->found=true;
						$this->found_key=$row3['Product Key'];
						$this->get_data('id',$this->found_key);

					}


				}
			}

		}

		// print "Found in key ".$this->found_in_key."\n";
		// print "Found in id ".$this->found_in_id."\n";
		// print "Found in store ".$this->found_in_store."\n";
		//print "Found in code ".$this->found_in_code."\n";
		//print "Found in key ".$this->found_key."\n";

		if ($create) {

			if ($this->found_in_key) {
				// print "Found updating date limits\n";



				$this->get_data('key',$this->found_key);



			}
			elseif ($this->found_in_id) {
				//print "Creatinf new sub id\n";
				$this->get_data('pid',$this->found_id);
				$this->create_key($data);
				$sql=sprintf("update  `Product History Dimension` set `Product History Short Description`=%s ,`Product History XHTML Short Description`=%s ,`Product ID`=%d where `Product Key`=%d"
					,prepare_mysql($this->get('historic short description'))
					,prepare_mysql($this->get('historic xhtml short description'))
					,$this->pid
					,$this->id
				);
				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);


			}
			elseif ($this->found_in_store) {
				//print "Creatinf new id\n";
				$this->create_key($data);
				$this->create_product_id($data);


			}
			elseif ($this->found_in_code) {
				//print "Creatinf new id (NEW CODE in store)\n";

				$this->create_key($data);
				$this->create_product_id($data);

			}
			else {
				//print "NEW CODE\n";
				$this->create($data);
			}

			$this->update_valid_dates($raw_data['product valid from']);
			if (isset($raw_data['product valid to']))
				$this->update_valid_dates($raw_data['product valid to']);

		}

	}

	function get_period($period,$key) {
		return $this->get($period.' '.$key);
	}


	function get($key='',$data=false) {

		if (!$this->id)
			return;

		if (array_key_exists($key,$this->data))
			return $this->data[$key];


		if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Amount|Profit)$/',$key)) {

			$amount='Product '.$key;

			return money($this->data[$amount]);
		}
		if (preg_match('/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers)$/',$key)) {

			$amount='Product '.$key;
			//print "->$amount"."<-";



			return number($this->data[$amount]);
		}

		switch ($key) {
		case("Sticky Note"):
			return nl2br($this->data['Product Sticky Note']);
			break;
		case('Product Currency'):
			$store=new Store($this->data['Product Store Key']);
			return $store->data['Store Currency Code'];


		case('ID'):
			return sprintf("%05d",$this->pid);
		case('Margin'):

			if ($this->data["Product Cost"]=='') {
				return _('ND');
			}

			return percentage($this->data["Product Price"]-$this->data["Product Cost"],$this->data["Product Price"]);
			break;
		case('RRP Margin'):
			return percentage($this->data["Product RRP"]-$this->data["Product Price"],$this->data["Product RRP"]);
			break;

		case('Price'):
			return money($this->data['Product Price'],$this->get('Product Currency'));
			break;
		case('Formated Price'):
			return $this->get_formated_price();
		case('Product Price Per Unit'):
			return $this->data['Product Price']/$this->data['Product Units Per Case'];
			break;
		case('Price Per Unit'):
			return money($this->data['Product Price']/$this->data['Product Units Per Case'],$this->get('Product Currency'));
			break;
		case('RRP'):
			return money($this->data['Product RRP'],$this->get('Product Currency'));
			break;
		case('RRP Per Unit'):
			return money($this->data['Product RRP']/$this->data['Product Units Per Case'],$this->get('Product Currency'));
			break;
		case('Product RRP Per Unit'):
			return $this->data['Product RRP']/$this->data['Product Units Per Case'];
			break;
		case('Formated RRP'):
			return get_formated_rrp();
			break;

		case('RRP Profit'):
			return $this->money($this->data['Product RRP']-$this->data['Product Price']);
			break;
		case('Profit'):
			return $this->money($this->data['Product Price']-$this->data['Product Cost']);
			break;

		case('Cost Per Unit'):
			return money($this->data['Product Cost']/$this->data['Product Units Per Case']);
			break;
		case('Cost'):
			return $this->money($this->data['Product Cost']);
			break;

		case('Formated Cost'):
			if ($this->data['Product Units Per Case']==1)
				return $this->money($this->data['Product Cost']);
			else
				return $this->money($this->data['Product Cost']).' <span style="font-weight:400;color:#555">('.$this->get('Cost Per Unit').' '._('each').')</span>';
			break;

		case('Same Code 1 Quarter WAVG Quantity Delivered'):
			return $this->data['Product Same Code 1 Quarter Acc Quantity Delivered']/12;
			break;

	

		case('Units'):
			return $this->number($this->data['Product Units Per Case']);
			break;


		case('Product Description Length'):
			return strlen($this->data['Product Description']);
			break;
		case('Product Description MD5 Hash'):
			return md5($this->data['Product Description']);
			break;
		case('Parts SKU'):
			$sql=sprintf("select `Part SKU` from `Product Part Dimension` PPD left join  `Product Part List` PPL on (PPD.`Product Part Key`=PPL.`Product Part Key`)   where PPD.`Product ID`=%d  and PPD.`Product part Most Recent`='Yes'  ;",$this->data['Product ID']);
			$result=mysql_query($sql);
			$parts=array();
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$parts[]=$row['Part SKU'];
			}
			return $parts;
			break;

		case('Unit Type'):
			return $this->data['Product Unit Type'];
		case('Unit Type Abbreviation'):
			if ($this->data['Product Unit Type']=='Piece')
				return _('Pc');
			if ($this->data['Product Unit Type']=='Grams')
				return _('g');
			if ($this->data['Product Unit Type']=='Meter')
				return _('m');

			return $this->data['Product Unit Type'];

		case('Number of Parts'):


			return count($this->get_current_part_list());


			break;
		case('Product Total Invoiced Net Amount'):
			return $this->data['Product Total Invoiced Gross Amount']-$this->data['Product Total Invoiced Discount Amount'];
		case('formated total net sales'):
			return money($this->data['Product Total Invoiced Gross Amount']-$this->data['Product Total Invoiced Discount Amount']);
		case('Formated Product Total Quantity Invoiced'):
			return number($this->data['Product Total Quantity Invoiced']);



		case('historic short description'):
		case('short description'):


			if ($key=='historic short description') {
				$units=$this->get('Product Units Per Case');
				$name=$this->data_historic['Product Name'];
				$price=$this->data_historic['Product Price'];
				$currency=$this->get('Product Currency');
			} else {
				$units=$this->get('Product Units Per Case');
				$name=$this->data['Product Name'];
				$price=$this->data['Product Price'];
				$currency=$this->get('Product Currency');
			}
			$desc='';
			if ($units>1) {
				$desc=number($units).'x ';
			}
			$desc.=' '.$name;
			if ($price>0) {
				$desc.=' ('.money_locale($price,$this->locale,$this->get('Product Currency')).')';
			}

			return _trim($desc);
		case('historic xhtml short description'):
		case('xhtml short description'):
			if ($key=='historic xhtml short description') {
				$units=$this->get('Product Units Per Case');
				$name=$this->data_historic['Product Name'];
				$price=$this->data_historic['Product Price'];
				$currency=$this->get('Product Currency');
			} else {
				$units=$this->get('Product Units Per Case');
				$name=$this->get('Product Name');
				$price=$this->get('Product Price');
				$currency=$this->get('Product Currency');
			}
			$desc='';
			if ($units>1) {
				$desc=number($units).'x ';
			}
			$desc.=' <span class="prod_sdesc">'.$name.'</span>';
			if ($price>0) {
				//print money($price,$currency)." $price,$currency <---\n";exit;

				$desc.=' ('.money_locale($price,$this->locale,$currency).')';
			}

			return _trim($desc);




		case('For Sale Since Date'):
			if ($this->data['Product For Sale Since Date']=='')
				return $this->unknown_txt;
			return strftime('%c',strtotime($this->data["Product For Sale Since Date"].' +0:00'));

			break;




		}
		$_key=ucwords($key);
		if (isset($this->data[$_key]))
			return $this->data[$_key];
		// print_r($this);
		//  exit( "Error -> $key <- not found in get from Product\n");


		return false;

	}



	function money($number) {

		if ($this->system_format) {
			return money($number,$this->get('Product Currency'));
		}


		if (preg_match('/fr_FR|de_DE|es_ES/',$this->locale)) {
			return $this->number($number,2).$this->data['Currency Symbol'];
		} else
			return $this->data['Currency Symbol'].$this->number($number,2);

	}

	/*
      Function: number
      Formatea el numero dependiendo el pais
    */
	// JFA
	function number($number,$decimal_places=1) {


		if ($this->system_format) {
			return number($number,$decimal_places);
		}

		$thousand_sep=',';
		$decimal_point='.';
		if (preg_match('/es_ES|de_DE/',$this->locale)) {
			$thousand_sep='.';
			$decimal_point=',';
		}


		return number_format($number,$decimal_places,$decimal_point,$thousand_sep);

	}

	function new_id() {
		$sql="select max(`Product id`) as id from `Product Dimension`";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['id']+1;
		} else {
			$id=1;
		}
		return $id;
	}







	/*
      Function: get_base_data
      Obtiene los diferentes valores de los atributos del producto
    */
	// JFA


	function get_base_data_history() {
		global $myconf;
		$base_data=array(
			'product history price'=>'',
			'product history name'=>'',
			'product history short description'=>'',
			'product history xhtml short description'=>'',
			'product history special characteristic'=>'',
			'product history valid from'=>date("Y-m-d H:i:s"),
			'product history valid to'=>date("Y-m-d H:i:s"),


		);

		return $base_data;
	}




	function get_base_data() {
		global $myconf,$corporate_currency;
		$base_data=array(
			'product sales type'=>'Public Sale',
			'product type'=>'Normal',
			'product record type'=>'Normal',
			'product availability type'=>'Normal',
			'product stage'=>'In Process',
			'Product web configuration'=>'Offline',
			'product store key'=>1,
			'product locale'=>$myconf['lang'].'_'.$myconf['country'],
			'product currency'=>$corporate_currency,

			'product code file as'=>'',
			'product code'=>'',
			'product price'=>'',
			'product rrp'=>'',
			'product name'=>'',
			'product short description'=>'',
			'product xhtml short description'=>'',
			'product special characteristic'=>'',
			'product special characteristic component a'=>'',
			'product special characteristic component b'=>'',

			'product description'=>'',
			'product family key'=>'',
			'product family code'=>'',
			'product family name'=>'',
			'product main department key'=>'',
			'product main department code'=>'',
			'product main department name'=>'',
			'product package type'=>'Box',
			// 'product package size metadata'=>'',
			//   'product net weight'=>'',
			//   'product gross weight'=>'',
			'product units per case'=>'1',
			'product unit type'=>'Piece',
			'product unit container'=>'',
			'product unit xhtml description'=>'',
			'product availability state'=>'Normal',
			'product valid from'=>date("Y-m-d H:i:s"),
			'product valid to'=>date("Y-m-d H:i:s"),
			'product current key'=>'',
			'product part metadata'=>'',

		);

		return $base_data;
	}



	function get_base_data_same_code() {
		global $myconf;
		$base_data=array(
			'product code file as'=>'',
			'product code'=>'',
			'product same code valid from'=>date("Y-m-d H:i:s"),
			'product same code valid to'=>date("Y-m-d H:i:s"),


		);

		return $base_data;
	}




	function create_key($data,$set_as_current=true) {


		$base_data_history=$this->get_base_data_history();
		foreach ($data as $key=>$value) {
			$key=strtolower($key);
			$key=preg_replace('/^product/','product history',$key);
			if (isset($base_data_history[$key]))
				$base_data_history[$key]=_trim($value);
		}



		$keys='(';
		$values='values(';
		foreach ($base_data_history as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		// print_r($data);

		$sql=sprintf("insert into `Product History Dimension` %s %s",$keys,$values);
		//   print "creating parod key --------------------------------\n";
		// print "$sql\n";exit;
		if (mysql_query($sql)) {
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

			$this->new_key=true;
			$this->new_key_id=mysql_insert_id();
			if ($set_as_current) {
				$this->id =$this->new_key_id;
				$this->key=$this->id;


			}




			$this->data_historic['Product Name']=$base_data_history['product history name'];
			$this->data_historic['Product Price']=$base_data_history['product history price'];






		}

	}

	function change_current_key($new_current_key) {
		// print "HOLA: $new_current_key\n";
		if ($new_current_key!=$this->data['Product Current Key']) {

			$sql=sprintf("select `Product History Price`,`Product History Name` from `Product History Dimension` where `Product ID`=%d and `Product Key`=%d "
				,$this->pid
				,$new_current_key
			);

			$res=mysql_query($sql);
			$num_historic_records=mysql_num_rows($res);
			if ($num_historic_records==0) {
				$this->error=true;
				$this->msg.=';Can not change product current key because mre key is not associated with ID';
				return;
			}
			$row=mysql_fetch_array($res);

			$price=$row['Product History Price'];
			$this->data['Product Price']=sprintf("%.2f",$price);
			$this->data['Product Name']=$row['Product History Name'];
			$this->data['Product XHTML Short Description']=$this->get('xhtml short description');
			$this->data['Product Short Description']=$this->get('short description');

			$sql=sprintf("update `Product Dimension` set `Product Name`=%s,`Product Short Description`=%s ,`Product XHTML Short Description`=%s,`Product Price`=%.2f,`Product Current Key`=%d  where `Product ID`=%d "
				,prepare_mysql($this->data['Product Name'])

				,prepare_mysql($this->data['Product Short Description'])
				,prepare_mysql($this->data['Product XHTML Short Description'])
				,$price
				,$new_current_key
				,$this->pid
			);

			mysql_query($sql);
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			//print $sql;
			$this->data['Product Current Key']=$new_current_key;
			$this->updated=true;
			$this->id =$new_current_key;
			$this->key=$this->id;
		}
	}



	function create_product_id($data) {

		$base_data=$this->get_base_data();

		foreach ($data as $_key=>$value) {
			$key=strtolower($_key);
			if (array_key_exists($key,$base_data) and $key!='product availability state')
				$base_data[$key]=_trim($value);
		}


		//print_r($base_data);exit;

		$base_data['product code file as']=$this->normalize_code($base_data['product code']);

		if (!is_numeric($base_data['product units per case']) or $base_data['product units per case']<1)
			$base_data['product units per case']=1;


		$family=new Family($base_data['product family key']);
		if (!$family->id) {
			$this->error=true;
			$this->msg='Wrong family';
			print_r($data);
			exit("Error Creating product: product family key family not found\n");
			return;
		}

		$department=new Department($family->data['Product Family Main Department Key']);

		$base_data['product main department key']=$department->id;
		$base_data['product main department code']=$department->data['Product Department Code'];
		$base_data['product main department name']=$department->data['Product Department Name'];
		$base_data['product family code']=$family->data['Product Family Code'];
		$base_data['product family name']=$family->data['Product Family Name'];

		$store=new Store($base_data['product store key']);



		$base_data['product current key']=$this->id;



		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			//print "$key\n";
			if($key=='product special characteristic component a' or $key=='product special characteristic component b')
						$values.=prepare_mysql($value,false).",";

			else
			$values.=prepare_mysql($value).",";

			// print "`$key`,".' -> '.$values."\n";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);

		$old_pids=$this->get_product_ids_with_same_code_store($base_data['product code'],$base_data['product store key']);
		//if(count($old_pids)>0){
		//print $base_data['product code']."\n";
		//print_r($old_pids);
		// exit;
		//}
		$sql=sprintf("insert into `Product Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			$this->pid = mysql_insert_id();
			$this->code =$base_data['product code'];
			$this->new_id=true;
			$this->new=true;

			$sql=sprintf("insert into  `Product ID Default Currency`  (`Product ID`) values (%d) ",$this->new_id);
			mysql_query($sql);

			$editor_data=$this->get_editor_data();

			$data_for_history=array(
				'Action'=>'created',
				'History Abstract'=>_('Product Created'),
				'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('Created')
			);
			$this->add_history($data_for_history);

			$family->update_product_data();
			
			$family->update_product_price_data();
			
			$department->update_product_data();
			$store->update_product_data();

			$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Default`='Yes' and `Category Subject`='Product' ");
			$res_cat=mysql_query($sql);
			//print "$sql\n";
			while ($row=mysql_fetch_array($res_cat)) {
				$sql=sprintf("insert into `Category Bridge` values (%d,'Product',%d, NULL) ",$row['Category Key'],$this->pid  );
				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			}

		} else {
			exit("Error can not insert if $sql\n");

		}

		$this->get_data('pid',$this->pid);
		$this->update_main_type();
		$this->data['Product Short Description']=$this->get('short description');
		$this->data['Product XHTML Short Description']=$this->get('xhtml short description');

		$sql=sprintf("update  `Product Dimension` set `Product Short Description`=%s ,`Product XHTML Short Description`=%s where `Product ID`=%d"
			,prepare_mysql($this->data['Product Short Description'])
			,prepare_mysql($this->data['Product XHTML Short Description'])
			,$this->pid);
		mysql_query($sql);

		$this->update_full_search();


		if ($this->new_key) {
			$sql=sprintf("update  `Product History Dimension` set `Product History Short Description`=%s ,`Product History XHTML Short Description`=%s ,`Product ID`=%d where `Product Key`=%d"
				,prepare_mysql($this->get('historic short description'))
				,prepare_mysql($this->get('historic xhtml short description'))
				,$this->pid
				,$this->id
			);
			mysql_query($sql);
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);


			$history_data=array(
				'Action'=>'created',
				'History Abstract'=>_('Product Created'),
				'History Details'=>_('Product')." ".$this->data['Product Code']." (".$this->pid.") "._('created')
			);
			$this->add_subject_history($history_data);
		}

	}

	function create_code($data) {
		$base_data_same_code=$this->get_base_data_same_code();
		foreach ($data as $key=>$value) {
			$key=strtolower($key);
			if ($key=='product valid from')
				$key='product same code valid from';
			else if ($key=='product valid to')
					$key='product same code valid to';

				if (isset($base_data_same_code[$key]))
					$base_data_same_code[$key]=_trim($value);
		}
		$base_data_same_code['product code file as']=$this->normalize_code($base_data_same_code['product code']);

		$keys='(';
		$values='values(';
		foreach ($base_data_same_code as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Product Same Code Dimension` %s %s",$keys,$values);
		// print "$sql\n";
		if (mysql_query($sql)) {
			$this->new_code=true;
			$this->code = $base_data_same_code['product code'];
		}

	}



	/*
      Method: create
      Crea o actualiza valores de la tabla Product Dimension
    */
	//


	function create($data) {

		$this->new_key=false;
		$this->new_id=false;
		$this->new_code=false;
		//print_r($data);exit;

		$this->create_key($data);
		$this->create_product_id($data);
		$this->create_code($data);



	
		$this->get_data('pid',$this->pid);
		$this->msg='Product Created';
		$this->new=true;
	
	}





	function find_product_part_list($list) {

		$this_list_num_parts=count($list);
		$good_product_parts=array();
		$found_product_parts=array();
		$found_list=array();
		foreach ($list as $key=>$value) {

			$sql=sprintf("select PPD.`Product Part Key` from  `Product Part Dimension`  PPD  left join  `Product Part List` PPL on (PPL.`Product Part Key`=PPD.`Product Part Key`)where `Product ID`=%d and `Part SKU`=%d  and `Parts Per Product`=%f and `Product Part Type`=%s   ",
				$this->pid,
				$value['Part SKU'],
				$value['Parts Per Product'],
				prepare_mysql($value['Product Part Type'])
			);

			$res=mysql_query($sql);
			//print "$sql\n";
			$found_list[$value['Part SKU']]=array();
			while ($row=mysql_fetch_assoc($res)) {
				$found_list[$value['Part SKU']][$row['Product Part Key']]=$row['Product Part Key'];
				$found_product_parts[$row['Product Part Key']]=$row['Product Part Key'];
			}
		}



		foreach ($found_list as $sku=>$found_data) {
			if (count($found_data)==0) {
				return 0;
			}
		}

		foreach ($found_product_parts as $product_part_key) {
			$sql=sprintf("select count(*) as num from  `Product Part List` where `Product Part Key`=%d",$product_part_key);
			//print "$sql\n";
			$res=mysql_query($sql);
			$num_parts;
			if ($row=mysql_fetch_assoc($res)) {
				$num_parts=$row['num'];
			}
			if ($num_parts!=$this_list_num_parts)
				break;

			foreach ($found_list as $sku=>$found_data) {
				if (!array_key_exists($product_part_key,$found_data)) {
					break;
				}
				$good_product_parts[$product_part_key]=$product_part_key;
			}

		}

		//print_r($good_product_parts);
		if (count($good_product_parts)==0) {
			return 0;
		}
		elseif (count($good_product_parts)==1) {
			return array_pop($good_product_parts);
		}
		else {
			print "Error ====\n";
			print_r($list);
			print_r($good_product_parts);
			exit("Debug this part list is duplicated (P)\n");
		}

	}


	function new_historic_part_list($header_data,$list) {

		$product_part_key=$this->find_product_part_list($list);
		if ($product_part_key) {
			$this->update_product_part_list($product_part_key,$header_data,$list);
			$this->update_product_part_list_historic_dates($product_part_key,$header_data['Product Part Valid From'],$header_data['Product Part Valid To']);

		} else {
			$product_part_key=$this->create_product_part_list($header_data,$list);
		}

		$this->update_parts();
		$this->update_cost_supplier();
		$this->update_main_type();
		$this->update_availability_type();
		$this->update_availability();

	}




	function get_product_part_dimension_data($product_part_key) {
		$sql=sprintf("select * from `Product Part Dimension` where `Product Part Key`=%d  ",$product_part_key);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row;
		} else
			return false;
	}

	function get_product_part_list_data($product_part_key) {
		$data=array();
		$sql=sprintf("select * from `Product Part List` where `Product Part Key`=%d  ",$product_part_key);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$data[$row['Part SKU']]=$row;
		}
		return $data;
	}


	function update_product_part_list_historic_dates($product_part_key,$date1,$date2) {
		$sql=sprintf("update `Product Part Dimension` set `Product Part Valid From`=%s where `Product Part Key`=%d and (`Product Part Valid From` is null or `Product Part Valid From`>%s)"
			,prepare_mysql($date1)
			,$product_part_key
			,prepare_mysql($date1)
		);
		mysql_query($sql);
		$sql=sprintf("update `Product Part Dimension` set `Product Part Valid To`=%s where `Product Part Key`=%d and (`Product Part Valid To` is null or `Product Part Valid To`<%s)"
			,prepare_mysql($date2)
			,$product_part_key
			,prepare_mysql($date2)
		);
		mysql_query($sql);
	}


	function update_product_part_list($product_part_key,$header_data,$list) {

		$this->new_value=array();

		$old_data=$this->get_product_part_dimension_data($product_part_key);
		$old_items_data=$this->get_product_part_list_data($product_part_key);

		if ($old_data['Product Part Metadata']!=$header_data['Product Part Metadata']) {
			$sql=sprintf("update `Product Part Dimension` set `Product Part Metadata`=%s where `Product Part Key`=%d"
				,prepare_mysql($header_data['Product Part Metadata'])
				,$product_part_key
			);
			mysql_query($sql);
			$this->updated=true;
			$this->part_list_updated=true;

			$this->new_value['Product Part Metadata']=$header_data['Product Part Metadata'];
		}

		foreach ($list as $item) {
			if ($old_items_data[ $item['Part SKU'] ] ['Product Part List Note']!=$item['Product Part List Note']   ) {
				$sql=sprintf("update `Product Part List` set `Product Part List Note`=%s where `Product Part List Key`=%d ",
					prepare_mysql($item['Product Part List Note']),
					$old_items_data[$item['Part SKU']]['Product Part List Key']
				);

				mysql_query($sql);
				$this->updated=true;
				$this->new_value['items'][$item['Part SKU']]['Product Part List Note']=$item['Product Part List Note'];
			}
		}
	}

	function get_current_part_key() {
		$product_part_key=0;
		$sql=sprintf("select `Product Part Key` from `Product Part Dimension` where `Product ID`=%d and `Product Part Most Recent`='Yes' ",$this->pid);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$product_part_key=$row['Product Part Key'];

		}
		return $product_part_key;
	}

	function set_part_list_as_current($product_part_key) {
		$current_part_key=$this->get_current_part_key();
		if ($current_part_key!=$product_part_key) {
			$sql=sprintf("update `Product Part Dimension` set `Product Part Valid To`=%s where `Product Part Key`=%d  ",prepare_mysql(date('Y-m-d H:i:s')),$current_part_key);
			mysql_query($sql);
			$sql=sprintf("update `Product Part List` set `Product Part Most Recent`='No' where `Product ID`=%d  ",$this->pid);
			mysql_query($sql);
			$sql=sprintf("update `Product Part List` set `Product Part Most Recent`='Yes' where `Product Part Key`=%d  ",$product_part_key);
			mysql_query($sql);
			$sql=sprintf("update `Product Part Dimension` set `Product Part Most Recent`='No' where `Product ID`=%d  ",$this->pid);
			mysql_query($sql);
			$sql=sprintf("update `Product Part Dimension` set `Product Part Most Recent`='Yes' ,`Product Part Valid To`=NULL  where `Product Part Key`=%d  ",$product_part_key);
			mysql_query($sql);
		}

		$this->get_cost_supplier();

	}



	function new_current_part_list($header_data,$list) {

		$product_part_key=$this->find_product_part_list($list);
		if ($product_part_key) {
			$this->update_product_part_list($product_part_key,$header_data,$list);
		} else {
			$product_part_key=$this->create_product_part_list($header_data,$list);
		}
		$this->set_part_list_as_current($product_part_key);
		$this->update_parts();
		$this->update_cost_supplier();
		$this->update_main_type();
		$this->update_availability_type();
		$this->update_availability();


	}


	function create_product_part_list($header_data,$list) {
		$product_part_key=0;
		$_base_list_data=array(
			'Part SKU'=>'',
			'Parts Per Product'=>'',
			'Product Part List Note'=>'',
		);
		$_base_data=array(
			'Product ID'=>$this->pid,
			'Product Part Type'=>'Simple',
			'Product Part Metadata'=>'',
			'Product Part Valid From'=>date('Y-m-d H:i:s'),
			'Product Part Valid To'=>date('Y-m-d H:i:s'),
			'Product Part Most Recent'=>'No',
		);

		$base_data=$_base_data;
		foreach ($header_data as $key=>$value) {
			if (array_key_exists($key,$base_data))
				$base_data[$key]=_trim($value);
		}

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Product Part Metadata' )
				$values.=prepare_mysql($value,false).',';
			else
				$values.=prepare_mysql($value).',';
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Product Part Dimension` %s %s",$keys,$values);
		if (mysql_query($sql)) {
			$product_part_key=mysql_insert_id();
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

			$this->new_value=array('Product Part Key'=>$product_part_key);
			$this->updated=true;
			$this->new_part_list=true;
			$this->new_part_list_key=$product_part_key;

			foreach ($list as $data) {
				$items_base_data=$_base_list_data;
				foreach ($data as $key=>$value) {
					if (array_key_exists($key,$items_base_data))
						$items_base_data[$key]=_trim($value);
				}
				$items_base_data['Product Part Key']=$product_part_key;
				$keys='(';
				$values='values(';
				foreach ($items_base_data as $key=>$value) {
					$keys.="`$key`,";
					if ($key=='Product Part List Note')
						$values.=prepare_mysql($value,false).',';
					else
						$values.=prepare_mysql($value).',';
				}
				$keys=preg_replace('/,$/',')',$keys);
				$values=preg_replace('/,$/',')',$values);
				$sql=sprintf("insert into `Product Part List` %s %s",$keys,$values);
				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			}
		}
		return $product_part_key;
	}





	function create_part_list($header_data,$part_list) {











		$_base_list_data=array(
			'product id'=>$this->data['Product ID'],
			'part sku'=>'',
			'requiered'=>'',
			'parts per product'=>'',
			'product part list note'=>'',
			'product part list metadata'=>'',

		);

		$_base_data=array(
			'product id'=>$this->data['Product ID'],

			'product part type'=>'Simple Pick',
			'product part metadata'=>'',
			'product part valid from'=>date('Y-m-d H:i:s'),
			'product part valid to'=>date('Y-m-d H:i:s'),
			'product part most recent'=>'Yes',

		);

		$base_data=$_base_data;
		foreach ($header_data as $key=>$value) {
			$key=strtolower($key);
			if (array_key_exists($key,$base_data))
				$base_data[$key]=_trim($value);
		}

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='product part metadata' )
				$values.=prepare_mysql($value,false).',';
			else
				$values.=prepare_mysql($value).',';
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Product Part Dimension` %s %s",$keys,$values);
		// print "$sql\n";
		if (mysql_query($sql)) {
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			$product_part_key=mysql_insert_id();

			if ($base_data['product part most recent']=='Yes') {

				$sql=sprintf("update `Product Part Dimension` set `Product Part Most Recent`='No' where `Product ID`=%d  and `Product Part Key`!=%d      "
					,$product_part_key,$product_part_key);
				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);


			}






			foreach ($part_list as $data) {
				$base_data=$_base_list_data;
				foreach ($data as $key=>$value) {
					$key=strtolower($key);
					if (array_key_exists($key,$base_data))
						$base_data[$key]=_trim($value);
				}
				$base_data['product part key']=$product_part_key;
				$keys='(';
				$values='values(';
				foreach ($base_data as $key=>$value) {
					$keys.="`$key`,";
					if ($key=='product part list metadata' or $key=='product part list note')
						$values.=prepare_mysql($value,false).',';
					else
						$values.=prepare_mysql($value).',';
				}
				$keys=preg_replace('/,$/',')',$keys);
				$values=preg_replace('/,$/',')',$values);
				$sql=sprintf("insert into `Product Part List` %s %s",$keys,$values);
				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				//print "$sql\n";


			}


			//------------------

		}
	}




	function normalize_code($code) {
		$ncode=$code;
		$c=preg_split('/\-/',$code);
		if (count($c)==2) {
			if (is_numeric($c[1]))
				$ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
			else {
				if (preg_match('/^[^\d]+\d+$/',$c[1])) {
					if (preg_match('/\d*$/',$c[1],$match_num) and preg_match('/^[^\d]*/',$c[1],$match_alpha)) {
						$ncode=sprintf("%s-%s%05d",strtolower($c[0]),strtolower($match_alpha[0]),$match_num[0]);
						return $ncode;
					}
				}
				if (preg_match('/^\d+[^\d]+$/',$c[1])) {
					if (preg_match('/^\d*/',$c[1],$match_num) and preg_match('/[^\d]*$/',$c[1],$match_alpha)) {
						$ncode=sprintf("%s-%05d%s",strtolower($c[0]),$match_num[0],strtolower($match_alpha[0]));
						return $ncode;
					}
				}


				$ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
			}

		}
		if (count($c)==3) {
			if (is_numeric($c[1]) and is_numeric($c[2])) {
				$ncode=sprintf("%s-%05d-%05d",strtolower($c[0]),$c[1],$c[2]);
				return $ncode;
			}
			if (!is_numeric($c[1]) and is_numeric($c[2])) {
				$ncode=sprintf("%s-%s-%05d",strtolower($c[0]),strtolower($c[1]),$c[2]);
				return $ncode;
			}
			if (is_numeric($c[1]) and !is_numeric($c[2])) {
				$ncode=sprintf("%s-%05d-%s",strtolower($c[0]),$c[1],strtolower($c[2]));
				return $ncode;
			}



		}


		return $ncode;
	}


	/*
      Method: group_by
      Despliega informacion de la tabla Product Dimnsion de forma agrupada
    */
	// JFA


	function group_by($key) {
		switch ($key) {
		case('code'):
			$sql=sprintf("select sum(`Product Total Quantity Invoiced`) as `Product Total Quantity Invoiced`,sum(`Product Total Invoiced Gross Amount`) as `Product Total Invoiced Gross Amount`, sum(`Product Total Invoiced Discount Amount`) as `Product Total Invoiced Discount Amount` from `Product Dimension` where `Product Code`=%s ",prepare_mysql($this->data['Product Code']));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				foreach ($row as $_key=>$value)
					$this->data[$_key]=$value;
			}

		}

	}

	function get_part_locations($for_smarty=false) {
		$skus=join(',',$this->get_current_part_skus());
		//print_r($this->get_current_part_list());
		$part_locations=array();

		if ($skus=='')
			return $part_locations;

		$sql=sprintf("select * from `Part Location Dimension` where `Part SKU` in (%s)",$skus);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$location=new Location($row['Location Key']);
			$part=new Part($row['Part SKU']);

			$row['Part Formated SKU']=$part->get_sku();
			$row['Location Code']=$location->data['Location Code'];
			if ($for_smarty) {
				$row_for_smarty=array();
				foreach ($row as $key=>$value) {
					$row_for_smarty[preg_replace('/\s/','',$key)]=$value;
				}
				$part_locations[$row['Part SKU'].'_'.$row['Location Key']]=$row_for_smarty;

			} else {
				$part_locations[$row['Part SKU'].'_'.$row['Location Key']]=$row;
			}
		}

		return $part_locations;
	}


	function load($key) {

		switch ($key) {
		case('redundant data'):
			$sql=sprintf("update  `Product Dimension` set `Product Short Description`=%s ,`Product XHTML Short Description`=%s where `Product Key`=%d",prepare_mysql($this->get('short description')),prepare_mysql($this->get('xhtml short description')),$this->id);
			mysql_query($sql);
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

			break;
		case('same code data'):
			$sql=sprintf("select * from `Product Dimension` where  `Product Key`=%d",$this->data['Product Same Code Most Recent Key']);
			//  print "$sql\n";
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$fam=sprintf('<a href="family.php?id=%d">%s</a>',$row['Product Family Key'],$row['Product Family Code']);
				$dept=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Main Department Key'],$row['Product Main Department Code']);
				$sql=sprintf("update `Product Dimension` set `Product Same Code XHTML Family`=%s, `Product Same Code Family Code`=%s,  `Product Same Code XHTML Main Department`=%s,  `Product Same Code Main Department Code`=%s ,`Product Same Code Tariff Code`=%s,`Product Same Code XHTML Short Description`=%s,`Product Same Code XHTML Parts`=%s,`Product Same Code XHTML Supplied By`=%s ,`Product Same Code XHTML Picking`=%s ,`Product Same Code Main Picking Location`=%s where `Product Key`=%d "
					,prepare_mysql($fam)
					,prepare_mysql($row['Product Family Code'])
					,prepare_mysql($dept)
					,prepare_mysql($row['Product Main Department Code'])

					,prepare_mysql($row['Product Tariff Code'])
					,prepare_mysql($row['Product XHTML Short Description'])
					,prepare_mysql($row['Product XHTML Parts'])
					,prepare_mysql($row['Product XHTML Supplied By'])
					,prepare_mysql($row['Product XHTML Picking'])
					,prepare_mysql($row['Product Main Picking Location'])
					,$this->id
				);
				//  print "$sql\n";
				if (!mysql_query($sql))
					exit("$sql can not update prioduct ame code data\n");

			}
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			break;









		case('avalilability'):
		case('stock'):
			$this->update_availability();

			break;
		case('days'):
			$this->update_days();






			break;

		case('sales'):

			$this->update_historic_sales_data();
			$this->update_sales_data();
			$this->update_same_code_sales_data();

			break;

		}


	}









	function update_field_switcher($field,$value,$options='') {


		switch ($field) {
		case('Store Sticky Note'):
			$this->update_field_switcher('Sticky Note',$value);
			break;
		case('Sticky Note'):
			$this->update_field('Product '.$field,$value,'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;
		case('Product Sales Type'):
			$this->update_sales_type($value);
			break;
		case('Remove Categories'):
			$this->remove_categories($value);
			break;
		case('Add Categories'):
			$this->add_categories($value);
			break;
		case('Remove Category'):
			$this->remove_category($value);
			break;
		case('Add Category'):
			$this->add_category($value);
			break;
		case('Product Parts Weight'):
			$this->update_gross_weight($value);
			break;

		case('Product Family Key'):
			$this->update_family_key($value);
			break;
		case('web_configuration'):
			return $this->update_web_configuration($value);
		case('Product Use Part H and S'):
		case('Product Use Part Tariff Data'):
		case('Product Use Part Properties'):
		case('Product Use Part Pictures'):
			$this->update_part_links($field,$value);
			break;
		case('sales_type'):
		case('sales_state'):
			$this->update_sales_type($value);

			break;



		case('processing'):
		exit("todo not ready yet");
		
/*
			if ( $this->data['Product Record Type']=='Historic'  ) {
				$this->msg=_("Error: You can edit historic records");
				$this->updated=false;
				return;
			}

			if ( $value!=_('Editing') and $value!=_('Live')  ) {
				$this->msg=_("Error: Wrong values ($value)");
				$this->updated=false;

				return;


			}

			if ( $value==_('Editing')  ) {
				//changing to editing mode
				if (  $this->data['Product Stage']=='In Process'  or  $this->data['Product Stage']=='New') {
					$this->updated=true;
					$this->new_value=_('Editing');
					return;
				}

				$sql=sprintf("update `Product Dimension` set `Product Stage`=%s  ,`Product Editing Price`=%f,`Product Editing RRP`=%s,`Product Editing Name`=%s,`Product Editing Special Characteristic`=%s ,`Product Editing Units Per Case`=%f ,`Product Editing Unit Type`=%s  where `Product Key`=%d "
					,prepare_mysql('In Process')
					,$this->data['Product Price']
					,($this->data['Product RRP']==''?'NULL':$this->data['Product RRP'])
					,prepare_mysql($this->data['Product Name'])
					,prepare_mysql($this->data['Product Special Characteristic'])
					,$this->data['Product Units Per Case']
					,prepare_mysql($this->data['Product Unit Type'])
					,$this->id
				);
				if (mysql_query($sql)) {
					if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
					$this->msg=_('Product Record Type updated');
					$this->updated=true;

					$this->new_value=_('Editing');
					return;
				} else {
					$this->msg=_("Error: Product stage could not be updated"." $sql");
					$this->updated=false;
					return;
				}

			} else {
				// Change from editing to normal
				if ( $this->data['Product Stage']=='Normal' ) {
					$this->updated=true;
					$this->new_value=_('Live');
					return;
				}


				if ($this->data['Product Stage']=='New' ) {

					$sql=sprintf("update `Product Dimension` set `Product Stage`=%s  where `Product Key`=%d "
						,prepare_mysql('Normal')
						,$this->id
					);
					if (mysql_query($sql)) {
						if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
						$this->msg=_('Product Stage updated');
						$this->updated=true;

						$this->new_value=_('Live');
						return;
					} else {
						$this->msg=_("Error: Product stage could not be updated");
						$this->updated=false;
						return;
					}

				}


				if ($this->data['Product Editing Price']!=$this->data['Product Price'] or $this->data['Product Editing Units Per Case']!=$this->data['Product Units Per Case']  or $this->data['Product Editing Unit Type']!=$this->data['Product Unit Type'] ) {
					// Have to create new ID for the product
					$this->create_new_id();
					if (!$this->new) {
						$this->msg=_("Error: Product record state could not be updated").' (no new)';
						$this->updated=false;
					}

					$sql=sprintf("update `Product Dimension` set `Product Record Type`=%s, `Product Most Recent`='No' where `Product Key`=%d "
						,prepare_mysql('Historic')
						,$this->id
					);

					if (mysql_query($sql)) {
						if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
						$this->msg=_('Product Record Type updated');
						$this->updated=true;
						$this->new_value=_('Live');
					} else {
						$this->msg=_("$sql Error: Product record state could not be updated");
						$this->updated=false;
					}
					return;



				} else {
					// No change in procce, or unis no necessity of make a new product with different ID

					$sql=sprintf("update `Product Dimension` set `Product Stage`=%s, `Product RRP`=%s,`Product Name`=%s,`Product Special Characteristic`=%s  where `Product ID`=%d"
						,prepare_mysql('Normal')
						,($this->data['Product Editing RRP']==''?'NULL':$this->data['Product Editing RRP'])
						,prepare_mysql($this->data['Product Editing Name'])
						,prepare_mysql($this->data['Product Editing Special Characteristic'])

						,$this->pid
					);
					if (mysql_query($sql)) {
						if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
						$this->msg=_('Product Record Type updated');
						$this->updated=true;
						$this->new_value=_('Live');
					} else {
						$this->msg=_("Error: Product record state could not be updated");
						$this->updated=false;
					}
					return;

				}


			}


*/
			break;
			
			
		case('Product Units Per Case'):
			$this->update_units_per_case($value);
			break;
		case('Product Unit Type'):
			$this->update_units_type($value);
			break;
		case('Product Price'):
		case('Product Price Per Unit'):
		case('Product Margin'):
		case('Product Unit Price'):
			$this->update_price($field,$value);
			break;
		case('Product RRP'):
		case('Product RRP Per Unit'):


			$this->update_rrp($field,$value);
			break;
		case('code'):

			if ($this->data['Product Stage']!='In process') {

				if ($this->data['Product Total Acc Quantity Ordered']>0) {
					$this->msg=_('This product code can not changed');
					return;
				}
			}

			if ($value==$this->data['Product Code']) {
				$this->updated=true;
				$this->new_value=$value;
				return;

			}

			if ($value=='') {
				$this->msg=_('Error: Wrong code (empty)');
				return;
			}
			$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s  COLLATE utf8_general_ci "
				,$this->data['Product Store Key']
				,prepare_mysql($value)
			);
			$res=mysql_query($sql);
			$row=mysql_fetch_array($res);
			if ($row['num']>0) {
				$this->msg=_("Error: Another product with the same code");
				return;
			}

			$sql=sprintf("update `Product Dimension` set `Product Code`=%s where `Product Key`=%d "
				,prepare_mysql($value)
				,$this->id
			);
			if (mysql_query($sql)) {
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				$this->msg=_('Product code updated');
				$this->updated=true;
				$this->new_value=$value;
			} else {
				$this->msg=_("Error: Product code could not be updated");

				$this->updated=false;

			}
			break;

		case('Product Name'):
			$this->update_name($value);
			break;
		case('Product Special Characteristic'):
			$this->update_special_characteristic($value);
			break;
		case('Product Description'):
			$this->update_description($value);
			break;




		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field,$value,$options);
				}
			}

		}


	}



	function selfsave() {
		$values='';
		foreach ($this->data as $key=>$value) {
			if (preg_match('/name|price|rrp|description|special|case|unit|^product code$|file as|store|family|department|state|tariff|package|volume|weight|availa|stock|recent|updated/i',$key))
				$values.="`$key`=".prepare_mysql($value).",";

		}
		//$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/','',$values);
		$sql=sprintf("update `Product Dimension` set %s where `Product Key`=%d",$values,$this->id);
		if (!mysql_query($sql)) {
			exit("error can not self save $sql\n");
		}
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
	}



	function syncronize() {

		global $external_dns_host,$external_dns_user,$external_dns_pwd,$default_DB_link;
		$ext_link = mysql_connect($external_dns_host,$external_dns_user,$external_dns_pwd);

		$this->get('id',$this->id,false);
		mysql_select_db($external_dns_db, $ex_link);
		$sql="update";

		mysql_select_db($external_dns_db, $default_DB_link);

	}



	function save_to_db($sql) {

		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

	}


	function removeaccents($string) {
		return strtr($string,"v©","e");
	}




	function update_valid_dates($date) {
		$this->update_valid_dates_key($date);
		$this->update_valid_dates_id($date);
		$this->update_valid_dates_code($date);

	}


	function update_for_sale_since($date) {
		$this->updated_field['Product For Sale Since Date']=false;
		$sql=sprintf("update `Product Dimension`  set `Product For Sale Since Date`=%s where  `Product ID`=%d and (`Product For Sale Since Date`>%s or `Product For Sale Since Date` IS NULL)   "
			,prepare_mysql($date)
			,$this->pid
			,prepare_mysql($date)

		);
		// print "$sql\n";
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		if (mysql_affected_rows()>0) {
			$editor_data=$this->get_editor_data();
			$this->updated_field['Product For Sale Since Date']=true;
			$sql=sprintf("select `History Key`  from `History Dimension` where `Action`='sold_since' and `Direct Object`='Product' and `Direct Object Key`=%d  ",$this->pid);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$sql=sprintf("update `History Dimension` set `History Date`=%s where `History Key`=%d  ",
					prepare_mysql($date),
					$row['History Key']
				);
				mysql_query($sql);
			} else {
				$this->data['Product For Sale Since Date']=$date;

				$this->add_history(array(
						'Date'=>$date
						,'Action'=>'sold_since'
						,'History Abstract'=>_('Product Set for Sale')
						,'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('set for sale')
					));


			}
		}


	}
	function update_last_sold_date($date) {
		$this->updated_field['Product Last Sold Date']=false;

		$sql=sprintf("update `Product Dimension`  set `Product Last Sold Date`=%s where  `Product ID`=%d and (`Product Last Sold Date`<%s or `Product Last Sold Date` IS NULL)  "
			,prepare_mysql($date)
			,$this->pid
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		if (mysql_affected_rows()>0) {
			$this->updated_field['Product Last Sold Date']=true;
			$editor_data=$this->get_editor_data();

			$sql=sprintf("select `History Key`  from `History Dimension` where `Action`='last_sold' and `Direct Object`='Product' and `Direct Object Key`=%d  ",$this->pid);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$sql=sprintf("update `History Dimension` set `History Date`=%s where `History Key`=%d  ",
					prepare_mysql($date),
					$row['History Key']
				);
				mysql_query($sql);
			} else {


				$this->data['Product Last Sold Date']=$date;

				$this->add_history(array(
						'Date'=>$date
						,'Action'=>'last_sold'
						,'History Abstract'=>_('Product Last Sold')
						,'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('last sold date')
					));



			}

		}
	}

	function update_first_sold_date($date) {
		$this->updated_field['Product First Sold Date']=false;

		$sql=sprintf("update `Product Dimension`  set `Product First Sold Date`=%s where  `Product ID`=%d and (`Product First Sold Date`>%s or `Product First Sold Date` IS NULL)  "
			,prepare_mysql($date)
			,$this->pid
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		if (mysql_affected_rows()>0) {
			$this->updated_field['Product First Sold Date']=true;
			$editor_data=$this->get_editor_data();

			$sql=sprintf("select `History Key`  from `History Dimension` where `Action`='first_sold' and `Direct Object`='Product' and `Direct Object Key`=%d  ",$this->pid);
			//print "$sql\n";
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$sql=sprintf("update `History Dimension` set `History Date`=%s where `History Key`=%d  ",
					prepare_mysql($date),
					$row['History Key']
				);
				mysql_query($sql);
				//print "$sql\n";
			} else {


				$this->data['Product First Sold Date']=$date;
				$this->add_history(array(
						'Date'=>$date
						,'Action'=>'first_sold'
						,'History Abstract'=>_('Product First Sold')
						,'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('first sold date')
					));



			}

		}
	}

	function update_valid_dates_key($date) {
		$affected=0;
		//print_r($this->data);
		$sql=sprintf("update `Product History Dimension`  set `Product History Valid From`=%s where  `Product Key`=%d and `Product History Valid From`>%s   "
			,prepare_mysql($date)
			,$this->id
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		// print "$sql\n";
		$affected+=mysql_affected_rows();
		$sql=sprintf("update `Product History Dimension`  set `Product History Valid To`=%s where  `Product Key`=%d and `Product History Valid To`<%s   "
			,prepare_mysql($date)
			,$this->id
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		//print "$sql\n";
		$affected+=mysql_affected_rows();
		// if($affected)
		//     exit;
		return $affected;
	}


	function update_valid_dates_id($date) {
		$affected=0;
		$sql=sprintf("update `Product Dimension`  set `Product Valid From`=%s where  `Product ID`=%d and `Product Valid From`>%s   "
			,prepare_mysql($date)
			,$this->pid
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		$affected+=mysql_affected_rows();
		$sql=sprintf("update `Product Dimension`  set `Product Valid To`=%s where  `Product ID`=%d and `Product Valid To`<%s   "
			,prepare_mysql($date)
			,$this->pid
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		$affected+=mysql_affected_rows();
		return $affected;
	}

	function update_valid_dates_code($date) {
		$affected=0;
		$sql=sprintf("update `Product Same Code Dimension`  set `Product Same Code Valid From`=%s where  `Product Code`=%s and `Product Same Code Valid From`>%s   "
			,prepare_mysql($date)
			,prepare_mysql($this->code)
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		$affected+=mysql_affected_rows();
		$sql=sprintf("update `Product Same Code Dimension`  set `Product Same Code Valid To`=%s where  `Product Code`=%s and `Product Same Code Valid To`<%s   "
			,prepare_mysql($date)
			,prepare_mysql($this->code)
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		$affected+=mysql_affected_rows();
		return $affected;
	}


	/*
      function: load_currency_data
      Set the currency extra data in the $data array
    */
	function load_currency_data() {



		$sql=sprintf('select * from kbase.`Currency Dimension` where `Currency Code`=%s'
			,prepare_mysql($this->get('Product Currency'))
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->data['Currency Symbol']=$row['Currency Symbol'];
			$this->data['Currency Name']=$row['Currency Name'];
		}
		mysql_free_result($res);
	}

	function get_historic_price_corporate_currency($datetime='') {

		global $corporate_currency;

		$price=$this->get_historic_price($datetime);


		if ($price!=0 and $this->data['Product Currency']!=$corporate_currency) {
			include_once 'class.CurrencyExchange.php';

			//print "------------------>".$this->data['Product Currency'].'xx'.$corporate_currency;

			$currency_exchange = new CurrencyExchange($this->data['Product Currency'].$corporate_currency,date('Y-m-d',strtotime($datetime)));



			$price=$price*$currency_exchange->exchange;
		}

		return $price;
	}

	function get_historic_price($datetime='') {

		if (!$datetime) {
			return $this->data['Product Price'];
		}

		$price=0;
		$sum_price=0;
		$count_prices=0;

		$sql=sprintf("select `Product History Price` from `Product History Dimension` where `Product Key`=%d   and `Product History Valid From`<=%s  ",
			$this->data['Product Current Key'],
			prepare_mysql($datetime)
		);



		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$sum_price+=$row['Product History Price'];
			$count_prices++;
		}

		$sql=sprintf("select `Product History Price` from `Product History Dimension` where `Product Key`!=%d and `Product ID`=%d  and `Product History Valid From` <=%s and `Product History Valid To`>=%s ",
			$this->data['Product Current Key'],
			$this->pid,
			prepare_mysql($datetime),
			prepare_mysql($datetime)
		);
		// print $sql;
		$res=mysql_query($sql);
		$this->historic_keys=array();
		while ($row=mysql_fetch_array($res)) {
			$sum_price+=$row['Product History Price'];
			$count_prices++;
		}

		if ($count_prices) {
			$price=$sum_price/$count_prices;
		}

		return $price;

	}


	function get_historic_keys() {
		$sql=sprintf("select `Product Key` from `Product History Dimension` where `Product ID`=%d group by `Product Key`"
			,$this->pid);
		// print $sql;
		$res=mysql_query($sql);
		$this->historic_keys=array();
		while ($row=mysql_fetch_array($res)) {
			$this->historic_keys[]=$row['Product Key'];
		}

	}


	function load_categories() {
		$sql=sprintf("select `Category Key` from `Category Bridge` where `Subject`='Product' and `Subject Key`=%d",$this->data['Product ID']);
		// print "$sql\n";
		$res=mysql_query($sql);
		$this->categories=array();
		while ($row=mysql_fetch_array($res)) {
			$this->categories[$row['Category Key']]=$row['Category Key'];
		}
	}

	function get_product_ids_with_same_code_store($code,$store_key) {
		$sql=sprintf("select `Product ID` from `Product Dimension`  where `Product Code`=%s and `Product Store Key`=%d group by `Product ID`",
			prepare_mysql($code),
			$store_key
		);
		$res=mysql_query($sql);
		$pids=array();
		while ($row=mysql_fetch_array($res)) {
			$pids[$row['Product ID']]=$row['Product ID'];
		}
		return $pids;
	}

	function get_historic_keys_with_same_code() {
		$sql=sprintf("select PHD.`Product Key` from `Product History Dimension` PHD left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`)  where `Product Code`=%s group by `Product Key`"
			,prepare_mysql($this->code));
		// print "$sql\n";
		$res=mysql_query($sql);
		$this->historic_keys_with_same_code=array();
		while ($row=mysql_fetch_array($res)) {
			$this->historic_keys_with_same_code[]=$row['Product Key'];
		}

	}


	function update_up_today_sales() {
		$this->update_sales_from_invoices('Total');
		$this->update_sales_from_invoices('Today');
		$this->update_sales_from_invoices('Week To Day');
		$this->update_sales_from_invoices('Month To Day');
		$this->update_sales_from_invoices('Year To Day');

	}

	function update_last_period_sales() {

		$this->update_sales_from_invoices('Yesterday');
		$this->update_sales_from_invoices('Last Week');
		$this->update_sales_from_invoices('Last Month');
	}

	function update_interval_sales() {

		$this->update_sales_from_invoices('3 Year');
		$this->update_sales_from_invoices('1 Year');
		$this->update_sales_from_invoices('6 Month');
		$this->update_sales_from_invoices('1 Quarter');
		$this->update_sales_from_invoices('1 Month');
		$this->update_sales_from_invoices('10 Day');
		$this->update_sales_from_invoices('1 Week');

	}

	function update_up_today_historic_key_sales() {
		$this->update_historic_key_sales_from_invoices('Today');
		$this->update_historic_key_sales_from_invoices('Week To Day');
		$this->update_historic_key_sales_from_invoices('Month To Day');
		$this->update_historic_key_sales_from_invoices('Year To Day');
		$this->update_historic_key_sales_from_invoices('Total');
	}

	function update_last_period_historic_key_sales() {

		$this->update_historic_key_sales_from_invoices('Yesterday');
		$this->update_historic_key_sales_from_invoices('Last Week');
		$this->update_historic_key_sales_from_invoices('Last Month');
	}

	function update_interval_historic_key_sales() {

		$this->update_historic_key_sales_from_invoices('3 Year');
		$this->update_historic_key_sales_from_invoices('1 Year');
		$this->update_historic_key_sales_from_invoices('6 Month');
		$this->update_historic_key_sales_from_invoices('1 Quarter');
		$this->update_historic_key_sales_from_invoices('1 Month');
		$this->update_historic_key_sales_from_invoices('10 Day');
		$this->update_historic_key_sales_from_invoices('1 Week');

	}


	function update_sales_from_invoices($interval) {

		$to_date='';
		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);


		$sql=sprintf("select count(Distinct `Customer Key`) as customers,count(Distinct `Invoice Key`) as invoices,sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as dc_gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as dc_disc ,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) as dc_net,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Cost Supplier`)*`Invoice Currency Exchange Rate`) as dc_profit from `Order Transaction Fact` where    `Current Dispatching State`='Dispatched' and  `Product ID`=%d %s %s ",
			$this->pid,
			($from_date?sprintf('and `Invoice Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')

		);
		//print "$sql\n";
		
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$this->data['Product $db_interval Acc Customers']=$row['customers'];
			$this->data['Product $db_interval Acc Invoices']=$row['invoices'];
			$this->data['Product $db_interval Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product $db_interval Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product $db_interval Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data['Product $db_interval Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product $db_interval Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product $db_interval Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product $db_interval Acc Quantity Delivered']=$row['delivered'];
			$this->data['Product ID DC $db_interval Acc Invoiced Gross Amount']=$row['dc_gross'];
			$this->data['Product ID DC $db_interval Acc Invoiced Discount Amount']=$row['dc_disc'];
			$this->data['Product ID DC $db_interval Acc Invoiced Amount']=$row['dc_net'];
			$this->data['Product ID DC $db_interval Acc Profit']=$row['dc_profit'];


		} else {
			$this->data['Product $db_interval Acc Customers']=0;
			$this->data['Product $db_interval Acc Invoices']=0;
			$this->data['Product $db_interval Acc Invoiced Gross Amount']=0;
			$this->data['Product $db_interval Acc Invoiced Discount Amount']=0;
			$this->data['Product $db_interval Acc Profit']=0;
			$this->data['Product $db_interval Acc Invoiced Amount']=0;
			$this->data['Product $db_interval Acc Quantity Ordered']=0;
			$this->data['Product $db_interval Acc Quantity Invoiced']=0;
			$this->data['Product $db_interval Acc Quantity Delivered']=0;
			$this->data['Product ID DC $db_interval Acc Invoiced Gross Amount']=0;
			$this->data['Product ID DC $db_interval Acc Invoiced Discount Amount']=0;
			$this->data['Product ID DC $db_interval Acc Invoiced Amount']=0;
			$this->data['Product ID DC $db_interval Acc Profit']=0;
		}
		$sql=sprintf("update `Product Dimension` set `Product $db_interval Acc Customers`=%d,`Product $db_interval Acc Invoices`=%d,`Product $db_interval Acc Invoiced Gross Amount`=%.2f,`Product $db_interval Acc Invoiced Discount Amount`=%.2f,`Product $db_interval Acc Invoiced Amount`=%.2f,`Product $db_interval Acc Profit`=%.2f, `Product $db_interval Acc Quantity Ordered`=%s , `Product $db_interval Acc Quantity Invoiced`=%s,`Product $db_interval Acc Quantity Delivered`=%s  where `Product ID`=%d "
			,$this->data['Product $db_interval Acc Customers']
			,$this->data['Product $db_interval Acc Invoices']
			,$this->data['Product $db_interval Acc Invoiced Gross Amount']
			,$this->data['Product $db_interval Acc Invoiced Discount Amount']
			,$this->data['Product $db_interval Acc Invoiced Amount']
			,$this->data['Product $db_interval Acc Profit']
			,prepare_mysql($this->data['Product $db_interval Acc Quantity Ordered'])
			,prepare_mysql($this->data['Product $db_interval Acc Quantity Invoiced'])
			,prepare_mysql($this->data['Product $db_interval Acc Quantity Delivered'])
			,$this->pid
		);
		mysql_query($sql);
		//print "$sql\n\n";
		$sql=sprintf("update `Product ID Default Currency` set `Product ID DC $db_interval Acc Invoiced Gross Amount`=%.2f,`Product ID DC $db_interval Acc Invoiced Discount Amount`=%.2f,`Product ID DC $db_interval Acc Invoiced Amount`=%.2f,`Product ID DC $db_interval Acc Profit`=%.2f where `Product ID`=%d "
			,$this->data['Product ID DC $db_interval Acc Invoiced Gross Amount']
			,$this->data['Product ID DC $db_interval Acc Invoiced Discount Amount']
			,$this->data['Product ID DC $db_interval Acc Invoiced Amount']
			,$this->data['Product ID DC $db_interval Acc Profit']

			,$this->pid
		);
		mysql_query($sql);
		//print "$sql\n\n";
		//exit;



		if ($from_date_1yb) {
			$this->data["Product $db_interval Acc 1YB Invoices"]=0;
			$this->data["Product $db_interval Acc 1YB Invoiced Discount Amount"]=0;
			$this->data["Product $db_interval Acc 1YB Invoiced Amount"]=0;
			$this->data["Product $db_interval Acc 1YB Profit"]=0;
			$this->data["Product $db_interval Acc 1YB Invoiced Delta"]=0;


			$sql=sprintf("select count(distinct `Invoice Key`) as invoices,IFNULL(sum(`Invoice Transaction Total Discount Amount`),0) as discounts,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net  ,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) as total_cost ,
                         sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`) as dc_discounts,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net  ,sum((`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`)*`Invoice Currency Exchange Rate`) as dc_total_cost from `Order Transaction Fact` where  `Current Dispatching State`='Dispatched' and `Product ID`=%d and `Invoice Date`>=%s %s" ,
				$this->pid,
				prepare_mysql($from_date_1yb),
				($to_1yb?sprintf('and `Invoice Date`<%s',prepare_mysql($to_1yb)):'')

			);



			// print "$sql\n\n";
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Product $db_interval Acc 1YB Invoiced Discount Amount"]=$row["discounts"];
				$this->data["Product $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
				$this->data["Product $db_interval Acc 1YB Invoiced Delta"]=($row["net"]==0?-1000000:$this->data["Product $db_interval Acc Invoiced Amount"]/$row["net"]);
				$this->data["Product $db_interval Acc 1YB Invoices"]=$row["invoices"];
				$this->data["Product $db_interval Acc 1YB Profit"]=$row["net"]-$row['total_cost'];

			}

			$sql=sprintf("update `Product Dimension` set
                         `Product $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Product $db_interval Acc 1YB Invoiced Amount`=%.2f,
                        `Product $db_interval Acc 1YB Invoiced Delta`=%f,
                         `Product $db_interval Acc 1YB Invoices`=%.2f,
                         `Product $db_interval Acc 1YB Profit`=%.2f
                         where `Product ID`=%d "
				,$this->data["Product $db_interval Acc 1YB Invoiced Discount Amount"]
				,$this->data["Product $db_interval Acc 1YB Invoiced Amount"]
				,$this->data["Product $db_interval Acc 1YB Invoiced Delta"]

				,$this->data["Product $db_interval Acc 1YB Invoices"]
				,$this->data["Product $db_interval Acc 1YB Profit"]
				,$this->pid
			);

			mysql_query($sql);
			//print "$sql\n";


		}




	}



	function update_historic_key_sales_from_invoices($interval) {

		$to_date='';
		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);


		$sql=sprintf("select count(Distinct `Customer Key`) as customers,count(Distinct `Invoice Key`) as invoices,sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced ,	sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc 		from `Order Transaction Fact` where  `Current Dispatching State`='Dispatched' and `Product Key`=%d %s %s ",
			$this->id,
			($from_date?sprintf('and `Invoice Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')

		);

		// print "$sql\n";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$this->data['Product History $db_interval Acc Customers']=$row['customers'];
			$this->data['Product History $db_interval Acc Invoices']=$row['invoices'];
			$this->data['Product History $db_interval Acc Invoiced Gross Amount']=$row['gross'];
			$this->data['Product History $db_interval Acc Invoiced Discount Amount']=$row['disc'];
			$this->data['Product History $db_interval Acc Invoiced Amount']=$row['gross']-$row['disc'];
			$this->data['Product History $db_interval Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
			$this->data['Product History $db_interval Acc Quantity Ordered']=$row['ordered'];
			$this->data['Product History $db_interval Acc Quantity Invoiced']=$row['invoiced'];
			$this->data['Product History $db_interval Acc Quantity Delivered']=$row['delivered'];
		} else {
			$this->data['Product History $db_interval Acc Customers']=0;
			$this->data['Product History $db_interval Acc Invoices']=0;
			$this->data['Product History $db_interval Acc Invoiced Gross Amount']=0;
			$this->data['Product History $db_interval Acc Invoiced Discount Amount']=0;
			$this->data['Product History $db_interval Acc Profit']=0;
			$this->data['Product History $db_interval Acc Invoiced Amount']=0;
			$this->data['Product History $db_interval Acc Quantity Ordered']=0;
			$this->data['Product History $db_interval Acc Quantity Invoiced']=0;
			$this->data['Product History $db_interval Acc Quantity Delivered']=0;
		}
		$sql=sprintf("update `Product History Dimension` set `Product History $db_interval Acc Customers`=%d,`Product History $db_interval Acc Invoices`=%d,`Product History $db_interval Acc Invoiced Gross Amount`=%.2f,`Product History $db_interval Acc Invoiced Discount Amount`=%.2f,`Product History $db_interval Acc Invoiced Amount`=%.2f,`Product History $db_interval Acc Profit`=%.2f, `Product History $db_interval Acc Quantity Ordered`=%s , `Product History $db_interval Acc Quantity Invoiced`=%s,`Product History $db_interval Acc Quantity Delivered`=%s  where `Product Key`=%d "
			,$this->data['Product History $db_interval Acc Customers']
			,$this->data['Product History $db_interval Acc Invoices']
			,$this->data['Product History $db_interval Acc Invoiced Gross Amount']
			,$this->data['Product History $db_interval Acc Invoiced Discount Amount']
			,$this->data['Product History $db_interval Acc Invoiced Amount']
			,$this->data['Product History $db_interval Acc Profit']
			,prepare_mysql($this->data['Product History $db_interval Acc Quantity Ordered'])
			,prepare_mysql($this->data['Product History $db_interval Acc Quantity Invoiced'])
			,prepare_mysql($this->data['Product History $db_interval Acc Quantity Delivered'])
			,$this->id
		);
		mysql_query($sql);

		//print "$sql\n\n\n";


	}









	function update_field($field,$value,$options='') {

		$this->updated=false;
		$null_if_empty=true;

		if ($options=='no_null') {
			$null_if_empty=false;

		}

		if (is_array($value))
			return;
		$value=_trim($value);


		$old_value=_('Unknown');


		$sql="select `".$field."` as value from  `Product Dimension`  where `Product ID`=".$this->pid;

		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$old_value=$row['value'];
		}


		$sql="update `Product Dimension` set `".$field."`=".prepare_mysql($value,$null_if_empty)." where `Product ID`=".$this->pid;


		mysql_query($sql);
		$affected=mysql_affected_rows();
		if ($affected==-1) {
			$this->msg.=' '._('Record can not be updated')."\n";
			$this->error_updated=true;
			$this->error=true;

			return;
		}
		elseif ($affected==0) {
			$this->data[$field]=$value;
		}
		else {



			$this->data[$field]=$value;
			$this->msg.=" $field "._('Record updated').", \n";
			$this->msg_updated.=" $field "._('Record updated').", \n";
			$this->updated=true;
			$this->new_value=$value;

			$save_history=true;
			if (preg_match('/no( |\_)history|nohistory/i',$options))
				$save_history=false;

			if (!$this->new and $save_history) {

				$history_data=array(
					'Indirect Object'=>$field,
					'old_value'=>$old_value,
					'new_value'=>$value

				);



				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')",$this->table_name,$this->pid,$history_key);
				mysql_query($sql);

			}

		}

	}


	function update_price($key,$value) {


		$change_at='now';

		$store=new store($this->data['Product Store Key']);

		if ($key=='Product Margin') {
			if (!is_numeric($this->data['Product Cost'])) {
				$this->msg=_("Error: The product cost is unknown");
				$this->updated=false;
				return;
			}
			$margin=floatval(ereg_replace("[^-0-9\.]","",$value));
			if (!is_numeric($margin)) {
				$this->msg=_("Error: Product margin should be a numeric value");
				$this->updated=false;
				return;
			}
			elseif ($margin==-100) {
				$this->msg=_("Error: Product margin can not have this value");
				$this->updated=false;
				return;
			}
			$amount=100*$this->data['Product Cost']/($margin+100);
		} else {
			list($currency,$amount)=parse_money($value,$store->data['Store Currency Code']);
			if (!is_numeric($amount)) {
				$this->msg=_("Error: Product price should be a numeric value");
				$this->updated=false;
			}
			if ($this->get('Product Currency')!=$currency) {
				$amount=$amount*currency_conversion($currency,$store->data['Store Currency Code']);
			}
		}

		if ($this->data['Product Stage']=='In Process') {

			if ($key=='Product Price Per Unit')
				$amount=$amount*$this->data['Product Editing Units Per Case'];
			if ($amount==$this->data['Product Editing Price']) {
				$this->updated=true;
				$this->new_value=money($amount,$store->data['Store Currency Code']);
				return;
			}
			$sql=sprintf("update `Product Dimension` set `Product Editing Price`=%f where `Product Key`=%d "
				,$amount
				,$this->id
			);
			if (mysql_query($sql)) {
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				$this->msg=_('Product price updated');
				$this->updated=true;
				$this->data['Product Editing Price']=$amount;

				if ($this->data['Product Editing Price']!=0 and is_numeric($this->data['Product Cost']))
					$margin=number(100*($this->data['Product Editing Price']-$this->data['Product Cost'])/$this->data['Product Editing Price'],1).'%';
				else
					$margin=_('ND');
				$this->new_value=money($amount,$this->get('Product Currency'));
				$this->new_data=array(
					'Product Price'=>money($amount,$this->get('Product Currency')),
					'Product Price Per Unit'=>money($amount/$this->data['Product Editing Units Per Case'],$this->get('Product Currency')),
					'Product Margin'=>$margin
				);
			} else {
				$this->msg=_("Error: Product price could not be updated");
				$this->updated=false;

			}

			return;
			// end in proccess editing
		} else {
			// Live product

			if ($key=='Product Price Per Unit' or $key=='Product Unit Price')
				$amount=$amount*$this->data['Product Units Per Case'];


			if ($amount==$this->data['Product Price']) {
				$this->updated=false;
				$this->new_value=money($amount,$this->get('Product Currency'));
				return;

			}
			$old_formated_price=$this->get('Formated Price');
			$sql=sprintf("select `Product Key` from `Product History Dimension` where `Product ID`=%d and `Product History Price`=%.2f "
				,$this->pid
				,$amount
			);
			//print $sql;
			$res=mysql_query($sql);

			$num_historic_records=mysql_num_rows($res);

			if ($num_historic_records==0) {
				$data=array('Product Price'=>$amount);
				$this->create_key($data);
				$sql=sprintf("update  `Product History Dimension` set `Product History Name`=%s, `Product History Short Description`=%s ,`Product History XHTML Short Description`=%s ,`Product ID`=%d where `Product Key`=%d"
					,prepare_mysql($this->data['Product Name'])
					,prepare_mysql($this->get('short description'))
					,prepare_mysql($this->get('xhtml short description'))
					,$this->pid
					,$this->new_key_id
				);
				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				//print "$sql\n";

				if ($change_at=='now') {
					$this->change_current_key($this->new_key_id);

				}
				$this->updated=true;

			}
			elseif ($num_historic_records==1) {
				$row=mysql_fetch_array($res);
				$key_matched=$row['Product Key'];

				if ($change_at=='now') {
					$this->change_current_key($key_matched);

				}
				$this->updated=true;
			}
			else {
				exit("exit more that one hitoric product\n ");

			}



			if ($this->updated) {

				if ($this->get('RRP Margin')!='')
					$customer_margin=$this->get('RRP Margin');
				else
					$customer_margin=_('ND');




				if ($key=='Product Price Per Unit' or $key=='Product Unit Price')
					$this->new_value=$this->get('Price Per Unit');
				else if ($key=='Product Margin')
						$this->new_value=$this->get('Margin');
					else
						$this->new_value=$this->get('Price');



					$this->new_data=array(
						'Price'=>$this->get('Price'),
						'Unit Price'=>$this->get('Price Per Unit'),
						'Margin'=>$this->get('Margin'),
						'RRP Margin'=>$customer_margin
					);


				$this->updated_fields['Product Price']=array(
					'Product Price'=>$this->data['Product Price'],
					'Formated Price'=>$this->get('Formated Price'),
					'Price Per Unit'=>$this->get('Price Per Unit'),
					'Margin'=>$this->get('Margin'),
					'RRP Margin'=>$this->get('RRP Margin'),
					'Price'=>$this->get('Price')
				);

				//print_r($this->updated_fields['Product Price']);

				$this->add_history(array(
						'Indirect Object'=>'Product Price'
						,'History Abstract'=>_('Product Price Changed').' ('.$this->get('Price').')'
						,'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('price changed').' '._('from')." ".$old_formated_price."  "._('to').' '. $this->get('Formated Price')
					));



			}


		}

	}
	function update_rrp($key,$value) {



		if ($value=='' or preg_match('/^(no|none|na|no for|nada)$/',$value)) {
			$amount='NULL';
		} else {
			list($currency,$amount)=parse_money($value,$this->get('Product Currency'));

			// print "val: $value c: $currency a:$amount \n";

			if (!is_numeric($amount)) {
				$this->msg=_("Error: Product RRP should be a numeric value");
				$this->updated=false;


			}


			if ($key=='Product RRP Per Unit')
				$amount=$amount*$this->data['Product Units Per Case'];




			if ($this->get('Product Currency')!=$currency) {
				//print "curency $amount $currency ".$this->get('Product Currency')."\n";
				$amount=$amount*currency_conversion($currency,$this->get('Product Currency'));
			}

		}



		if ( ($amount=='NULL' and $this->data['Product RRP']=='') or ( is_numeric($amount) and  $amount==$this->data['Product RRP'])    ) {
			$this->updated=false;
			if ($amount=='NULL')
				$this->new_value='';
			else
				$this->new_value=money($amount,$this->get('Product Currency'));

			return;

		}

		$old_rrp_per_unit=$this->get('RRP Per Unit');

		$sql=sprintf("update `Product Dimension` set `Product RRP`=%s where `Product ID`=%d "
			,$amount
			,$this->pid
		);



		//   print $this->data['Product Code'].' '.$this->data['Product RRP']." -> $amount ";
		//print_r($this->data);
		//print "value $value; c: $currency a: $amount\n";
		//print "$sql\n";exit("dup rrp");
		if (mysql_query($sql)) {
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			$this->msg=_('Product RRP updated');
			$this->updated=true;

			if ($amount=='NULL') {
				$this->data['Product RRP']='';

			} else {
				$this->data['Product RRP']=$amount;

			}

			if ($this->get('RRP Margin')!='')
				$customer_margin=_('RRP Margin').' '.$this->get('RRP Margin');
			else
				$customer_margin=_('Not for resale');

			$this->new_value=$this->get('RRP Per Unit');
			//$this->new_value=array('RRP Margin'=>$customer_margin,'RRP Per Unit'=>$this->get('RRP Per Unit'));

			$this->new_data=array(
				'RRP'=>$this->get('RRP'),
				'RRP Margin'=>$this->get('RRP Margin'),
				'RRP Per Unit'=>$this->get('RRP Per Unit'),
				'Product RRP'=>$this->get('Product RRP')
			);

			$this->add_history(array(
					'Indirect Object'=>'Product RRP',
					'History Abstract'=>_('Product RRP Changed').' ('.$this->get('RRP Per Unit').')',
					'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('RRP changed').' '._('from')." ".$old_rrp_per_unit." "._('per unit')." "._('to').' '. $this->get('RRP Per Unit').' '._('per unit')
				));





		} else {
			$this->msg=_("Error: Product Recomended Retail Price could not be updated");
			$this->updated=false;
		}

	}







	function update_family_key($key) {

		if (!is_numeric($key)) {
			$this->error=true;
			$this->msg='Key is not a number';
			return;
		}



		$old_family=new Family($this->data['Product Family Key']);
		$new_family=new Family($key);

		$sql=sprintf("update `Product Dimension` set `Product Family Key`=%d, `Product Family Code`=%s, `Product Family Name`=%s,
                     `Product Main Department Key`=%d,
                     `Product Main Department Code`=%s,
                     `Product Main Department Name`=%s

                     where `Product ID`=%d",
			$new_family->id,
			prepare_mysql($new_family->data['Product Family Code']),
			prepare_mysql($new_family->data['Product Family Name']),
			$new_family->data['Product Family Main Department Key'],
			prepare_mysql($new_family->data['Product Family Main Department Code']),
			prepare_mysql($new_family->data['Product Family Main Department Name']),

			$this->pid);


		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

		$old_family->update_product_data();
		$new_family->update_product_data();

		if ($new_family->data['Product Family Main Department Key']!=$old_family->data['Product Family Main Department Key']) {
			$old_department=new Department($old_family->data['Product Family Main Department Key']);
			$new_department=new Department($new_family->data['Product Family Main Department Key']);
			$new_department->update_product_data();
			$new_department->update_product_data();
		}

		$this->data['Product Family Key']=$key;
		$this->new_value=$key;
		$this->new_data=array('code'=>$new_family->data['Product Family Code'] );
		$this->updated=true;

	}


	function get_weight_from_parts() {

		$parts_info=$this->get_current_part_list();
		$weight=0;
		foreach ($parts_info as $part_info) {
			$part=$part_info['part'];
			if ($part->data['Part Package Weight']!='')
				$weight+=$part->data['Part Package Weight']*$part_info['Parts Per Product'];
		}
		return $weight;

	}





	function update_gross_weight($weight) {

		if (!is_numeric($weight)) {
			$this->error=true;
			$this->msg='Weight is not a number';
			return;
		}

		$sql=sprintf("update `Product Dimension` set `Product Parts Weight`=%f where `Product ID`=%d",$weight,$this->pid);
		mysql_query($sql);

		$this->data['Product Parts Weight']=$weight;
		$this->new_value=$weight;
		$this->updated=true;

	}




	function update_name($value) {

		if ($this->data['Product Stage']=='In Process') {
			if ($value==$this->data['Product Editing Name']) {
				$this->updated=true;
				$this->new_value=$value;
				return;
			}
		} else {
			if ($value==$this->data['Product Name']) {
				$this->updated=true;
				$this->new_value=$value;
				return;
			}
		}

		if ($value=='') {
			$this->msg=_('Error: Wrong name (empty)');
			return;
		}
		if (!(strtolower($value)==strtolower($this->data['Product Name']) and $value!=$this->data['Product Name'])) {
			$sql=sprintf("select * from `Product Dimension` where `Product Store Key`=%d and  ( `Product Name`=%s  COLLATE utf8_general_ci  ) "
				,$this->data['Product Store Key']
				,prepare_mysql($value)


			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$this->msg=_("Error: Another product has already this same name").". (".$row['Product Code'].")";
				return;
			}
		}
		if ($this->data['Product Stage']=='In Process')
			$edit_column='Product Editing Name';
		else
			$edit_column='Product Name';
		$old_name=$this->get('Product Name');
		$this->data[$edit_column]=$value;
		$this->data['Product Short Description']=$this->get('short description');
		$this->data['Product XHTML Short Description']=$this->get('xhtml short description');

		$sql=sprintf("update `Product Dimension` set `%s`=%s ,`Product Short Description`=%s,`Product XHTML Short Description`=%s where `Product ID`=%d "
			,$edit_column
			,prepare_mysql($value)
			,prepare_mysql($this->get('short description'))
			,prepare_mysql($this->get('xhtml short description'))
			,$this->pid
		);
		if (mysql_query($sql)) {
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			$this->msg=_('Product name updated');
			$this->updated=true;
			$this->new_value=$value;
			$this->data[$edit_column]=$value;

			if ($edit_column=='Product Name') {


				$this->updated_fields['Product Name']=array(
					'Product Name'=>$this->data['Product Name']
				);

				$this->add_history(array(
						'Indirect Object'=>'Product Name'
						,'History Abstract'=>_('Product Name Changed').' ('.$this->get('Product Name').')'
						,'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('Name').' '._('from')." ".$old_name." "._('to').' '. $this->get('Product Name')
					));

				$this->update_full_search();

			}
		} else {
			$this->msg=_("Error: Product name could not be updated");

			$this->updated=false;

		}
	}
	function update_special_characteristic($value) {
		if ($this->data['Product Stage']=='In Process') {
			if ($value==$this->data['Product Editing Special Characteristic']) {
				$this->updated=true;
				$this->new_value=$value;
				return;
			}
		} else {

			if ($value==$this->data['Product Special Characteristic']) {
				$this->updated=true;
				$this->new_value=$value;
				return;
			}
		}

		if ($value=='') {
			$this->msg=_('Error: Wrong Product Special Characteristic (empty)');
			return;
		}



		if ($this->data['Product Stage']=='In Process')
			$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Editing Special Characteristic`=%s   and `Product ID`!=%d"
				,$this->data['Product Family Key']
				,prepare_mysql($value)
				,$this->pid
			);
		else
			$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Special Characteristic`=%s   and `Product ID`!=%d"
				,$this->data['Product Family Key']
				,prepare_mysql($value)
				,$this->pid
			);



		$res=mysql_query($sql);
		$row=mysql_fetch_array($res);
		if ($row['num']>0) {
			$this->msg=_("Error: Another product with the same Product/Family Special Characteristic in this family");
			return;
		}
		if ($this->data['Product Stage']=='In Process')
			$editing_column='Product Editing Special Characteristic';
		else
			$editing_column='Product Special Characteristic';

		$old_special_characteristic=$this->get('Product Special Characteristic');

		$sql=sprintf("update `Product Dimension` set `%s`=%s where `Product ID`=%d "
			,$editing_column
			,prepare_mysql($value)
			,$this->pid
		);
		if (mysql_query($sql)) {
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			$this->data['Product Special Characteristic']=$value;
			$this->msg=_('Product Special Characteristic');
			$this->updated=true;
			$this->new_value=$this->data['Product Special Characteristic'] ;
			$this->updated_fields['Product Special Characteristic']=array(
				'Product Special Characteristic'=>$this->data['Product Special Characteristic']
			);

			$this->add_history(array(
					'Indirect Object'=>'Product Special Characteristic'
					,'History Abstract'=>_('Product Special Characteristic Changed').' ('.$this->get('Product Special Characteristic').')'
					,'History Details'=>_('Product')." ".$this->code." (ID:".$this->get('ID').") "._('Special Characteristic').' '._('from')." ".$old_special_characteristic." "._('to').' '. $this->get('Product Special Characteristic')
				));




		} else {
			$this->error=true;
			$this->msg=_("Error: Product Special Characteristic could not be updated");

			$this->updated=false;

		}
	}


	function update_description($description) {
		$description=_($description);
		$old_description=$this->data['Product Description'];
		if (strcmp($description,$old_description)) {
			$sql=sprintf("update `Product Dimension` set `Product Description`=%s where `Product ID`=%d "
				,prepare_mysql($description)
				,$this->pid
			);
			if (mysql_query($sql)) {
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				$this->data['Product Description']=$description;
				$this->msg=_('Product Description changed');
				$this->updated=true;
				$this->new_value=$description;
				$editor_data=$this->get_editor_data();
				if ($old_description=='') {
					$abstract=_('Product Description Created');
					$details=_('Product Description Created');
				} else {
					$abstract=_('Product Description Changed');
					$details=_('Product Description Changed');
				}


				$this->updated_fields['Product Description']=array(
					'Product Description Length'=>$this->get('Product Description Length'),
					'Product Description'=>$this->get('Product Description'),
					'Product Description MD5 Hash'=>$this->get('Product Description MD5 Hash')
				);


				$this->add_history(array(
						'Indirect Object'=>'Product Description'
						,'History Abstract'=>$abstract
						,'History Details'=>$details
					));




			} else {
				$this->error=true;
				$this->msg=_("Error: Product Description could not be updated");

				$this->updated=false;

			}
		}
	}






	function remove_category($category_key) {

		$sql=sprintf("select PCB.`Category Key`,`Category Position`,`Category Code` from `Category Bridge` PCB left join `Category Dimension` C on (C.`Category Key`=PCB.`Category Key`)   where  PCB.`Subject Key`=%d  and `Subject`='Product'  and PCB.`Category Key`=%d   " ,$this->pid,$category_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$cat_removed=$row['Category Code'];
			$category_location=preg_split('/>/',preg_replace('/>$/','',preg_replace('/^\d+>/','',$row['Category Position'])));
			foreach ($category_location as $category_location_key) {

				$sql=sprintf("delete from `Category Bridge` where `Subject Key`=%d  and `Subject`='Product'  and `Category Key`=%d ",$this->pid,$category_location_key);

				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				if (mysql_affected_rows()>0) {
					$this->updated_fields['Product Category'][$category_location_key]=0;
				}

			}

			$this->updated=true;
			$this->new_value='';
			$editor_data=$this->get_editor_data();

			$abstract=_('Product remove from Category')." ($cat_removed)";
			$details=_('Product remove from Category');
			$this->msg=$abstract;

			$this->add_history(array(
					'Action'=>'disassociate'
					,'Preposition'=>'from'
					,'Indirect Object'=>'Category'
					,'History Abstract'=>$abstract
					,'History Details'=>$details
				));





		} else {
			$this->update=false;
		}


	}


	function add_categories($category_array) {

		foreach ($category_array as $category_key) {
			$this->add_category($category_key);
		}
	}


	function remove_categories($category_array) {
		foreach ($category_array as $category_key) {
			$this->remove_category($category_key);
		}
	}



	function add_category($category_key) {

		$num_inserted_categories=0;
		$num_inserted_errors=0;
		$sql=sprintf("select PCB.`Category Key`,`Category Position`,`Category Code` from `Category Bridge` PCB left join `Category Dimension` C on (C.`Category Key`=PCB.`Category Key`)   where  PCB.`Subject Key`=%d  and `Subject`='Product'  and PCB.`Category Key`=%d   " ,$this->pid,$category_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->update=false;
			$this->msg=_('Product already in Category')." (".$row['Category Code'].")";
			return;
		}
		mysql_free_result($res);







		$sql=sprintf("select * from `Category Dimension`    where  `Category Key`=%d   " ,$category_key);
		//print "$sql\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$cat_added=$row['Category Code'];
			//      print preg_replace('/^\d+>/','',$row['Category Position'])."\n";
			$category_location=preg_split('/>/',  preg_replace('/>$/','',preg_replace('/^\d+>/','',$row['Category Position']))  )   ;
			$category_parents=preg_replace('/\d+>$/','',$row['Category Position']);
			$category_deep=$row['Category Deep'];
			$to_delete=array();
			if ($row['Category Default']=='Yes') {
				$default=true;


			} else {
				$default=false;
			}



			foreach ($category_location as $category_location_key) {
				$sql=sprintf("insert into  `Category Bridge`   values (%d,'Product',%d, NULL) ",$category_location_key,$this->pid);
				//print "$sql\n";
				if (mysql_query($sql)) {
					if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
					if (mysql_affected_rows()>0) {
						$num_inserted_categories++;
						$this->updated_fields['Product Category'][$category_location_key]=1;

						if ($default) {
							$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Position` like '%s%%' and `Category Default`='No' and `Category Deep`=%d  "
								,$category_parents
								,$category_deep
							);
							$_res=mysql_query($sql);
							while ($_row=mysql_fetch_array($_res)) {
								$this->remove_category($_row['Category Key']);
							}
						} else {
							$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Position` like '%s%%' and `Category Default`='Yes' and `Category Deep`=%d  "
								,$category_parents
								,$category_deep
							);
							$_res=mysql_query($sql);
							while ($_row=mysql_fetch_array($_res)) {
								$this->remove_category($_row['Category Key']);
							}

						}


					}
				} else
					$num_inserted_errors++;

			}
			if ($num_inserted_categories>0) {
				$this->updated=true;
				$this->new_value='';
				$editor_data=$this->get_editor_data();

				$abstract=_('Product added to Category')." ($cat_added)";
				$details=_('Product added to Category');
				$this->msg=$abstract;
				$this->add_history(array(
						'Action'=>'associate'
						,'Preposition'=>'to'
						,'Indirect Object'=>'Category'
						,'History Abstract'=>$abstract
						,'History Details'=>$details
					));


			}



		} else {
			$this->arror=true;
			$this->update=false;
			$this->msg='Category do not exists';
			return;

		}


	}

	function get_part_type() {
		$sql=sprintf("select `Product Part Type` from `Product Part Dimension` where `Product ID`=%d and `Product Part Most Recent`='Yes'",$this->pid);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			return $row['Product Part Type'];
		} else
			return false;

	}

	function get_part_list($datetime=false) {
		if (!$datetime)
			return $this->get_current_part_list();
		$part_list=array();
		$this->product_part_key=0;
		$this->product_part_type='';
		$sql=sprintf("select `Product Part Key`,`Product Part Type` from `Product Part Dimension` where `Product ID`=%d and
                     ((  `Product Part Valid From`<=%s and `Product Part Valid To`>=%s and `Product Part Most Recent`='No') or
                     ( `Product Part Most Recent`='Yes' and `Product Part Valid From`<=%s )  )   limit 1 "
			,$this->pid
			,prepare_mysql($datetime)
			,prepare_mysql($datetime)
			,prepare_mysql($datetime)
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$product_part_key=$row['Product Part Key'];
			$type=$row['Product Part Type'];
			$this->product_part_key=$product_part_key;
			$this->product_part_type=$row['Product Part Type'];

			$sql=sprintf("select *  from `Product Part List` where `Product Part Key`=%d "
				,$product_part_key);
			$res2=mysql_query($sql);
			//print "$sql\n";
			while ($row2=mysql_fetch_array($res2,MYSQL_ASSOC)) {
				$part_list[$row2['Part SKU']]=$row2;
			}


		}

		$this->product_part=$part_list;
		return $part_list;
	}





	function get_current_product_part_key() {
		$key=0;
		$sql=sprintf("select `Product Part Key`,`Product Part Type` from `Product Part Dimension` where `Product ID`=%d and  `Product Part Most Recent`='Yes' limit 1 "
			,$this->pid
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$key=$row['Product Part Key'];
		}
		return $key;
	}


	function get_current_part_skus() {

		$skus=array();
		$sql=sprintf("select `Part SKU`  from `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`) where `Product ID`=%d and  `Product Part Most Recent`='Yes' "
			,$this->pid
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$skus[$row['Part SKU']]=$row['Part SKU'];
		}
		return $skus;
	}

	function get_parts_objects() {
		$parts=array();
		foreach ($this->get_current_part_skus() as $part_sku) {
			$parts[$part_sku]=new Part($part_sku);

		}
		return $parts;

	}

	function get_all_part_skus() {

		$skus=array();
		$sql=sprintf("select *  from `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`) where `Product ID`=%d  "
			,$this->pid
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$skus[$row['Part SKU']]=$row['Part SKU'];
		}
		return $skus;
	}



	function get_number_of_parts() {
		return count($this->get_current_part_skus());
	}

	function get_current_part_list($options=false) {

		$part_list=array();

		$sql=sprintf("select *  from `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`) where `Product ID`=%d and  `Product Part Most Recent`='Yes' "
			,$this->pid
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			if (preg_match('/smarty/i',$options)) {
				$_row=array();
				foreach ($row as $key=>$value) {
					if ($key=='Parts Per Product') {
						$value=floattostr($value);
					}
					$_row[preg_replace('/\s+/','_',$key)]=$value;
				}
				$part_list[$row['Part SKU']]=$_row;

			} else {

				$part_list[$row['Part SKU']]=$row;
			}
			$part_list[$row['Part SKU']]['part']=new Part($row['Part SKU']);

		}

		return $part_list;
	}


	function update_days() {



		$sql=sprintf("select `Product History For Sale Since Date`,`Product History Last Sold Date`  from `Product History Dimension` where `Product Key`=%s",prepare_mysql($this->id));
		$result=mysql_query($sql);
		// print $sql;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			if ($this->data['Product Sales Type']!='Not For Sale' and $this->id==$this->data['Product Current Key'])
				$row['Product History Last Sold Date']=date('Y-m-d H:i:s');

			$tdays = (strtotime($row['Product History Last Sold Date']) - strtotime($row['Product History For Sale Since Date'])) / (60 * 60 * 24);


			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -1 year'))
				$ydays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -1 year'))
					$_from=strtotime('today -1 year');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$ydays=($_to-$_from)/ (60 * 60 * 24);
			}


			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -3 month'))
				$qdays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -3 month'))
					$_from=strtotime('today -3 month');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$qdays=($_to-$_from)/ (60 * 60 * 24);
			}

			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -1 month'))
				$mdays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -1 month'))
					$_from=strtotime('today -1 month');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$mdays=($_to-$_from)/ (60 * 60 * 24);
			}
			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -1 week'))
				$wdays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -1 week'))
					$_from=strtotime('today -1 week');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$wdays=($_to-$_from)/ (60 * 60 * 24);
			}


			$for_sale_since=$row['Product History For Sale Since Date'];
			$last_sold_date=$row['Product History Last Sold Date'];



			$sql=sprintf("update `Product History Dimension` set `Product History Total Days On Sale`=%f , `Product History 1 Year Acc Days On Sale`=%f ,`Product History 1 Quarter Acc Days On Sale`=%f ,`Product History 1 Month Acc Days On Sale`=%f ,`Product History 1 Week Acc Days On Sale`=%f  where `Product key`=%d "
				,$tdays
				,$ydays
				,$qdays
				,$mdays
				,$wdays


				,$this->id
			);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql\ncan not update product days\n");
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		}
		// return;
		mysql_free_result($result);


		//same code
		$total_days=array();
		$y_days=array();
		$q_days=array();
		$m_days=array();
		$w_days=array();



		$sql=sprintf("select min(`Product For Sale Since Date`) as `Product History For Sale Since Date` ,max(`Product Last Sold Date`) as `Product History Last Sold Date` ,sum(IF(`Product Sales Type`!='Not for Sale',1,0)) state from `Product Dimension` where `Product Code`=%s",prepare_mysql($this->data['Product Code']));
		$result=mysql_query($sql);
		// print $sql;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			/* 	$from=strtotime($row['since']); */
			/* 	$to=strtotime($row['last']); */

			if ($row['state']>0)
				$row['Product History Last Sold Date']=date("Y-m-d H:i:s");

			if (strtotime($row['Product History Last Sold Date']) - strtotime($row['Product History For Sale Since Date'])<0  ) {
				print "Error ".$this->data['Product Code']."  wrong dates skipping \n";
				return;
			}




			$tdays = (strtotime($row['Product History Last Sold Date']) - strtotime($row['Product History For Sale Since Date'])) / (60 * 60 * 24);


			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -1 year'))
				$ydays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -1 year'))
					$_from=strtotime('today -1 year');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$ydays=($_to-$_from)/ (60 * 60 * 24);
			}


			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -3 month'))
				$qdays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -3 month'))
					$_from=strtotime('today -3 month');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$qdays=($_to-$_from)/ (60 * 60 * 24);
			}

			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -1 month'))
				$mdays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -1 month'))
					$_from=strtotime('today -1 month');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$mdays=($_to-$_from)/ (60 * 60 * 24);
			}
			if (strtotime($row['Product History Last Sold Date'])<strtotime('today -1 week'))
				$wdays=0;
			else {
				$_to=strtotime($row['Product History Last Sold Date']);
				if (strtotime($row['Product History For Sale Since Date'])<strtotime('today -1 week'))
					$_from=strtotime('today -1 week');
				else
					$_from=strtotime($row['Product History For Sale Since Date']);
				$wdays=($_to-$_from)/ (60 * 60 * 24);
			}












			/* 	$i=0; */
			/* 	while ($check_date != $end_date) { */


			/* 	  if (isset($total_days[$check_date])) */
			/* 	    $total_days[$check_date]++; */
			/* 	  else */
			/* 	    $total_days[$check_date]=1; */

			/* 	  $_date=strtotime($check_date); */

			/* 	  if ($_date>strtotime('today - 1 year')) { */
			/* 	    if (isset($y_days[$check_date])) */
			/* 	      $y_days[$check_date]++; */
			/* 	    else */
			/* 	      $y_days[$check_date]=1; */
			/* 	  } */
			/* 	  if ($_date>strtotime('today - 3 month')) { */
			/* 	    if (isset($q_days[$check_date])) */
			/* 	      $q_days[$check_date]++; */
			/* 	    else */
			/* 	      $q_days[$check_date]=1; */
			/* 	  } */
			/* 	  if ($_date>strtotime('today - 1 month')) { */
			/* 	    if (isset($m_days[$check_date])) */
			/* 	      $m_days[$check_date]++; */
			/* 	    else */
			/* 	      $m_days[$check_date]=1; */
			/* 	  } */
			/* 	  if ($_date>strtotime('today - 3 month')) { */
			/* 	    if (isset($w_days[$check_date])) */
			/* 	      $w_days[$check_date]++; */
			/* 	    else */
			/* 	      $w_days[$check_date]=1; */
			/* 	  } */


			/* 	  $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date))); */
			/* 	  $i++; */

			/* 	  if ($i > 50000) { */
			/* 	    die ("$start_date  $end_date   to many days Error a!"); */
			/* 	  } */
			/* 	} */
			/* 	//   print "$start_date $end_date ".count($total_days)."\n"; */


		}
		// print_r($days);
		$total_days=count($total_days);
		$y_days=count($y_days);
		$q_days=count($q_days);
		$m_days=count($m_days);
		$w_days=count($w_days);

		$sql=sprintf("update `Product Same Code Dimension` set `Product Same Code Total Days On Sale`=%f ,`Product Same Code 1 Year Acc Days On Sale`=%f , `Product Same Code 1 Quarter Acc Days On Sale`=%f, `Product Same Code 1 Month Acc Days On Sale`=%f , `Product Same Code 1 Week Acc Days On Sale`=%f where  `Product Code`=%s "
			,$total_days
			,$y_days
			,$q_days
			,$m_days
			,$w_days
			,prepare_mysql($this->data['Product Code'])
		);

		if (!mysql_query($sql))
			exit("$sql\ncan not update product same code total days\n");

		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

		mysql_free_result($result);

		$total_days=array();
		$y_days=array();
		$q_days=array();
		$m_days=array();
		$w_days=array();



		$sql=sprintf("select `Product For Sale Since Date`,`Product Last Sold Date`,`Product Sales Type` from `Product Dimension` where `Product ID`=%s",prepare_mysql($this->data['Product ID']));
		$result=mysql_query($sql);
		// print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			if ($row['Product Sales Type']!='Not for Sale')
				$row['Product Last Sold Date']=date("Y-m-d H:i:s");


			if (strtotime($row['Product Last Sold Date']) - strtotime($row['Product For Sale Since Date'])<0) {
				print "Error ".$this->data['Product Code']."    wrong dates  ".$row['Product For Sale Since Date']." - ".$row['Product Last Sold Date']."  skipping \n";
				// continue;
			}


			$tdays = (strtotime($row['Product Last Sold Date']) - strtotime($row['Product For Sale Since Date'])) / (60 * 60 * 24);


			if (strtotime($row['Product Last Sold Date'])<strtotime('today -1 year'))
				$ydays=0;
			else {
				$_to=strtotime($row['Product Last Sold Date']);
				if (strtotime($row['Product For Sale Since Date'])<strtotime('today -1 year'))
					$_from=strtotime('today -1 year');
				else
					$_from=strtotime($row['Product For Sale Since Date']);
				$ydays=($_to-$_from)/ (60 * 60 * 24);
			}


			if (strtotime($row['Product Last Sold Date'])<strtotime('today -3 month'))
				$qdays=0;
			else {
				$_to=strtotime($row['Product Last Sold Date']);
				if (strtotime($row['Product For Sale Since Date'])<strtotime('today -3 month'))
					$_from=strtotime('today -3 month');
				else
					$_from=strtotime($row['Product For Sale Since Date']);
				$qdays=($_to-$_from)/ (60 * 60 * 24);
			}

			if (strtotime($row['Product Last Sold Date'])<strtotime('today -1 month'))
				$mdays=0;
			else {
				$_to=strtotime($row['Product Last Sold Date']);
				if (strtotime($row['Product For Sale Since Date'])<strtotime('today -1 month'))
					$_from=strtotime('today -1 month');
				else
					$_from=strtotime($row['Product For Sale Since Date']);
				$mdays=($_to-$_from)/ (60 * 60 * 24);
			}
			if (strtotime($row['Product Last Sold Date'])<strtotime('today -1 week'))
				$wdays=0;
			else {
				$_to=strtotime($row['Product Last Sold Date']);
				if (strtotime($row['Product For Sale Since Date'])<strtotime('today -1 week'))
					$_from=strtotime('today -1 week');
				else
					$_from=strtotime($row['Product For Sale Since Date']);
				$wdays=($_to-$_from)/ (60 * 60 * 24);
			}








		}

		$total_days=count($total_days);
		$y_days=count($y_days);
		$q_days=count($q_days);
		$m_days=count($m_days);
		$w_days=count($w_days);
		$sql=sprintf("update `Product Dimension` set `Product Total Acc Days On Sale`=%f ,`Product 1 Year Acc Days On Sale`=%f , `Product 1 Quarter Acc Days On Sale`=%f, `Product 1 Month Acc Days On Sale`=%f , `Product 1 Week Acc Days On Sale`=%f where  `Product ID`=%d "
			,$total_days
			,$y_days
			,$q_days
			,$m_days
			,$w_days
			,$this->pid
		);
		//  print $sql;
		if (!mysql_query($sql))
			exit("$sql\ncan not update product same id total days\n");
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

		mysql_free_result($result);

		return;
	}


	function update_if_still_new() {
		$one_week=604800;
		if ($this->data['Product Stage']=='New' and date('U')-strtotime($this->data['Product For Sale Since Date'])>$one_week ) {
			$this->data['Product Stage']='Normal';
			$sql=sprintf("update `Product Dimension` set `Product Stage`=%s  where  `Product ID`=%d "
				,prepare_mysql($this->data['Product Stage'])
				,$this->pid
			);
			mysql_query($sql);
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		}


	}




	function update_next_supplier_shippment() {

		$part_list=$this->get_part_list();


		if (count($part_list)==1) {
			$this->update_next_supplier_shippment_simple($part_list);
			return;
		}

	}

	function update_next_supplier_shippment_simple($part_list) {

		$part_list=array_shift($part_list);
		//  print_r($part_list);
		$part=new Part($part_list['Part SKU']);

		$supplier_products=$part->get_supplier_products();
		//print_r($supplier_products);
		$next_shippment='';
		// print $this->code."\n";
		foreach ($supplier_products as $supplier_product) {

			//  $sql=sprintf("select `Purchase Order Current Dispatching State `,`Supplier Delivery Note Received Quantity`,`Supplier Delivery Note Damaged Quantity`,SDND.`Supplier Delivery Note Key`,`Supplier Delivery Note Public ID`,`Supplier Delivery Note Date`,`Supplier Delivery Note State`,`Purchase Order Estimated Receiving Date`,`Purchase Order Current Dispatch State`,`Purchase Order Cancelled Date`,`Purchase Order Estimated Receiving Date`,`Purchase Order Submitted Date`,`Purchase Order Public ID`,POTF.`Purchase Order Key`,`Purchase Order Quantity` from `Purchase Order Transaction Fact` POTF  left join `Supplier Product Dimension` SPD on (`Supplier Product Current Key`=`Supplier Product ID`) left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) left join `Supplier Delivery Note Dimension` SDND on (SDND.`Supplier Delivery Note Key`=POTF.`Supplier Delivery Note Key`) where   SPD.`Supplier Product Code`=%s and SPD.`Supplier Key`=%d order by PO.`Purchase Order Last Updated Date` "
			//   ,prepare_mysql($supplier_product['Supplier Product Code'])
			//  ,$supplier_product['Supplier Key']

			//  );
			$sql=sprintf("select POTF.`Supplier Delivery Note Last Updated Date`,`Supplier Delivery Note Quantity`,`Purchase Order Current Dispatching State`,`Supplier Delivery Note Received Quantity`,`Supplier Delivery Note Damaged Quantity`,SDND.`Supplier Delivery Note Key`,`Supplier Delivery Note Public ID`,`Supplier Delivery Note Date`,`Supplier Delivery Note State`,`Purchase Order Estimated Receiving Date`,`Purchase Order Current Dispatch State`,`Purchase Order Cancelled Date`,`Purchase Order Estimated Receiving Date`,`Purchase Order Submitted Date`,`Purchase Order Public ID`,POTF.`Purchase Order Key`,`Purchase Order Quantity` from `Purchase Order Transaction Fact` POTF  left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) left join `Supplier Delivery Note Dimension` SDND on (SDND.`Supplier Delivery Note Key`=POTF.`Supplier Delivery Note Key`)  where `Supplier Product ID` in (%s) "
				,$supplier_product['Supplier Product IDs']
			);

			$res=mysql_query($sql);
			// print "$sql\n";
			while ($row=mysql_fetch_assoc($res)) {
				$number=floor($row['Purchase Order Quantity']/$supplier_product['Supplier Product Units Per Part']/$part_list['Parts Per Product']);
				//  print_r($supplier_product);
				// print_r($part_list);
				// print $number;
				// print_r($row);
				if ($row['Purchase Order Current Dispatching State']=='Cancelled' ) {
					if ($number<1)
						continue;

					if (date('U')-strtotime($row['Purchase Order Cancelled Date']<604800)) {
						$next_shippment.=sprintf("<span style='text-decoration:line-through'><br/>%s, PO <a href='porder.php?id=%d'>%s</a>:<br/>An order has been placed for  <b>%s outers</b></span> %s order cancelled."
							,strftime("%e %b %y", strtotime($row['Purchase Order Submitted Date']))
							,$row['Purchase Order Key']
							,$row['Purchase Order Public ID']
							,number($number)
							,strftime("%e %b %y", strtotime($row['Purchase Order Cancelled Date']))
						);
					}
				}
				elseif ($row['Purchase Order Current Dispatching State']=='Submitted' ) {


					if ($number<1)
						continue;
					$next_shippment.=sprintf("<br/>%s, PO <a href='porder.php?id=%d'>%s</a>:<br/>An order has been placed for  <b>%s outers</b>."
						,strftime("%e %b %y", strtotime($row['Purchase Order Submitted Date']))
						,$row['Purchase Order Key']
						,$row['Purchase Order Public ID']
						,number($number)
					);
					if ($row['Purchase Order Estimated Receiving Date']!='') {
						$next_shippment.='<br/>Estimated Delivery: '.strftime("%e-%b-%Y",strtotime($row['Purchase Order Estimated Receiving Date']));
					}



				}
				elseif ($row['Purchase Order Current Dispatch State']=='Matched With DN'  and   $row['Supplier Delivery Note State']!='Placed'  ) {

					if ($row['Supplier Delivery Note State']=='Inputted' ) {
						$qty=$row['Supplier Delivery Note Quantity'];
						$note=_('Waiting for dispatch');
					}
					elseif ($row['Supplier Delivery Note State']=='Received'  ) {
						$qty=$row['Supplier Delivery Note Quantity'];
						$note=_('Waiting for checking');
					}
					elseif ($row['Supplier Delivery Note State']=='Checked') {
						$qty=$row['Supplier Delivery Note Received Quantity']-$row['Supplier Delivery Note Damaged Quantity'];
						$note=_('Checking delivery');

					}

					$number=floor($qty/$supplier_product['Supplier Product Units Per Part']/$part_list['Parts Per Product']);


					$next_shippment.=sprintf("<br/>%s, DN <a href='supplier_dn.php?id=%d'>%s</a>:<br/>An order has been received for  <b>%s outers</b>. (%s)"
						,strftime("%e %b %y", strtotime($row['Supplier Delivery Note Last Updated Date']))
						,$row['Supplier Delivery Note Key']
						,$row['Supplier Delivery Note Public ID']
						,number($number)
						,$note
					);





				}




			}

		}
		$next_shippment=preg_replace('/^\<br\/\>/','',$next_shippment);

		$sql=sprintf("update `Product Dimension` set `Product Next Supplier Shipment`=%s where `Product ID`=%d",prepare_mysql($next_shippment,false),$this->data['Product ID']);
		// print "$sql\n";
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

	}

	function update_number_pages() {
		$number_pages=0;
		$sql=sprintf("select count(Distinct `Page Key`) as num from `Page Product Dimension`  where `Product ID`=%d",
			$this->pid
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)   ) {
			$number_pages=$row['num'];
		}
		$this->data['Product Number Web Pages']=$number_pages;

		$sql=sprintf("update `Product Dimension` set `Product Number Web Pages`=%d where `Product ID`=%d",
			$this->data['Product Number Web Pages'],
			$this->pid
		);
		//print "$sql\n";
		mysql_query($sql);

	}


	function update_parts() {
		$parts='';
		$mysql_where='';



		//        if ($this->data['Product Record Type']=='Discontinued' or $this->data['Product Record Type']=='Historic') {
		//           $sql=sprintf("select `Part SKU` from  `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`) where PPD.`Product ID`=%d order by `Product Part Valid To` desc limit 1;",$this->data['Product ID']);

		//        } else {
		$sql=sprintf("select `Part SKU` from  `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`) where PPD.`Product ID`=%d and PPD.`Product Part Most Recent`='Yes';",$this->data['Product ID']);
		//      }


		// print "$sql\n";
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$parts.=sprintf(', <a href="part.php?sku=%d">SKU%005d</a>',$row['Part SKU'],$row['Part SKU']);
			$mysql_where.=', '.$row['Part SKU'];
		}







		$parts=preg_replace('/^, /','',$parts);
		$mysql_where=preg_replace('/^, /','',$mysql_where);

		if ($mysql_where=='')
			$mysql_where=0;
		$supplied_by='';



		if ($this->data['Product Type']=='Normal') {

			$sql=sprintf("select  SPPL.`Supplier Product Part Key`, `Supplier Product Code` ,  SD.`Supplier Key`,SD.`Supplier Code`
                         from `Supplier Product Part List` SPPL
                         left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)
                         left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product ID`=SPPD.`Supplier Product ID`)

                         left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPD.`Supplier Key`)

                         where `Part SKU` in (%s) and `Supplier Product Part Most Recent`='Yes' order by `Supplier Key`;"
				,$mysql_where);
			$result=mysql_query($sql);

			$supplier=array();
			$current_supplier='_';
			//print $sql;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$_current_supplier=$row['Supplier Key'];


				if ($_current_supplier!=$current_supplier) {
					$supplied_by.=sprintf(', <a href="supplier.php?id=%d">%s</a>(<a href="supplier_product.php?code=%s&supplier_key=%d">%s</a>'
						,$row['Supplier Key']
						,$row['Supplier Code']
						,$row['Supplier Product Code']
						,$row['Supplier Key']
						,$row['Supplier Product Code']);
					$current_supplier=$_current_supplier;
				} else {
					$supplied_by.=sprintf(', <a href="supplier_product.php?code=%s">%s</a>',$row['Supplier Product Code'],$row['Supplier Product Code']);

				}


			}

			$supplied_by.=")";

			$supplied_by=_trim(preg_replace('/^, /','',$supplied_by));

		} else {
			$supplied_by='Mix';

		}


		if ($supplied_by=='')
			$supplied_by=_('Unknown');



		$sql=sprintf("update `Product Dimension` set `Product XHTML Parts`=%s  , `Product XHTML Supplied By`=%s where `Product ID`=%d",prepare_mysql(_trim($parts)),prepare_mysql(_trim($supplied_by)),$this->pid);
		//print "$sql\n";
		if (!mysql_query($sql))
			exit("$sql  eerror can not updat eparts pf product 1234234\n");
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

	}


	function update_main_type() {



		if ($this->data['Product Record Type']=='Historic') {
			$this->data['Product Main Type']='Historic';
		}
		elseif ($this->data['Product Sales Type']=='Private Sale') {
			$this->data['Product Main Type']='Private';
		}
		elseif ($this->data['Product Sales Type']=='Not for Sale') {
			$this->data['Product Main Type']='NoSale';
		}
		else {

			if ($this->data['Product Availability Type']=='Discontinued' and $this->data['Product Availability']<=0) {
				$this->data['Product Main Type']='Discontinued';
			} else {

				$this->data['Product Main Type']='Sale';
			}


		}


		$sql=sprintf("update `Product Dimension` set `Product Main Type`=%s where `Product ID`=%d",prepare_mysql($this->data['Product Main Type']),$this->pid);
		// print "$sql\n";
		mysql_query($sql);

		$family=new Family($this->data['Product Family Key']);
		$family->update_product_data();

	}

	function update_availability_type() {


		if ($this->data['Product Record Type']=='Historic') {
			$availability_type='Discontinued';
		} else {


			$availability_type='Normal';
			$current_part_skus=$this->get_current_part_skus();

			foreach ($current_part_skus as $sku) {

				$part=new Part($sku);

				if ( $part->data['Part Available']=='No'  or  ($part->data['Part Status']=='Not In Use')    ) {
					$availability_type='Discontinued';
				}

			}
		}
		$this->data['Product Availability Type']=$availability_type;

		$sql=sprintf("update `Product Dimension` set `Product Availability Type`=%s where `Product ID`=%d",prepare_mysql($this->data['Product Availability Type']),$this->pid);
		//  print "$sql\n";

		mysql_query($sql);

		$this->update_web_state();
		$this->update_main_type();

	}



	function update_availability() {



		// $stock_forecast_method='basic1';
		// $stock_tipo_method='basic1';

		// get parts;
		$sql=sprintf(" select `Part Stock State`,`Part Current On Hand Stock`-`Part Current Stock In Process` as stock,`Part Current Stock In Process`,`Part Current On Hand Stock`,`Parts Per Product` from `Part Dimension` PD       left join `Product Part List` PPL on (PD.`Part SKU`=PPL.`Part SKU`)       left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`)        where PPD.`Product ID`=%d  and PPD.`Product Part Most Recent`='Yes' group by PD.`Part SKU`  ",$this->data['Product ID']);
		//print "$sql\n";



		$result=mysql_query($sql);
		$stock=99999999999;
		$tipo='Excess';
		$change=false;
		$stock_error=false;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


			if ($row['Part Stock State']=='Error')
				$tipo='Error';
			else if ($row['Part Stock State']=='OutofStock' and $tipo!='Error')
					$tipo='OutofStock';
				else if ($row['Part Stock State']=='VeryLow' and $tipo!='Error' and $tipo!='OutofStock' )
						$tipo='VeryLow';
					else if ($row['Part Stock State']=='Low' and $tipo!='Error' and $tipo!='OutofStock' and $tipo!='VeryLow')
							$tipo='Low';
						else if ($row['Part Stock State']=='Normal' and $tipo=='Excess' )
								$tipo='Normal';

							if (is_numeric($row['stock']) and is_numeric($row['Parts Per Product'])  and $row['Parts Per Product']>0 ) {

								$_part_stock=$row['stock'];
								if ($row['Part Current On Hand Stock']==0  and $row['Part Current Stock In Process']>0 ) {
									$_part_stock=0;
								}

								$_stock=$_part_stock/$row['Parts Per Product'];
								if ($stock>$_stock) {
									$stock=$_stock;
									$change=true;
								}
							}
						else {

							$stock=0;
							$stock_error=true;
						}

		}

		//   print "Stock: $stock\n";
		if (!$change or $stock_error)
			$stock='NULL';
		//   print "Stock: $stock\n";
		if (is_numeric($stock) and $stock<0)
			$stock='NULL';
		// print "Stock: $stock\n";
		$sql=sprintf("update `Product Dimension` set `Product Availability`=%s where `Product ID`=%d",$stock,$this->pid);
		//print "$sql\n";

		mysql_query($sql);
		$days_available='NULL';
		$avg_day_sales=0;




		$sql=sprintf("update `Product Dimension` set `Product Availability State`=%s,`Product Available Days Forecast`=%s where `Product ID`=%d",prepare_mysql($tipo),$days_available,$this->pid);
		mysql_query($sql);



		// if( mysql_affected_rows()){
		//$family=new Family($this->data['Product Family Key']);
		//$family->update_product_data();
		//$department=new Department($this->data['Product Main Department Key']);
		//$department->update_product_data();
		//$store=new Store($this->data['Product Store Key']);
		//$store->update_product_data();

		$this->update_web_state();





	}

	function get_formated_rrp($locale='') {

		$data=array(
			'Product RRP'=>$this->data['Product RRP'],
			'Product Units Per Case'=>$this->data['Product Units Per Case'],
			'Product Currency'=>$this->get('Product Currency'),
			'Product Unit Type'=>$this->data['Product Unit Type'],
			'locale'=>$locale);

		return formated_rrp($data);
	}




	function get_formated_price($locale='') {

		$data=array(
			'Product Price'=>$this->data['Product Price'],
			'Product Units Per Case'=>$this->data['Product Units Per Case'],
			'Product Currency'=>$this->get('Product Currency'),
			'Product Unit Type'=>$this->data['Product Unit Type'],


			'locale'=>$locale);

		return formated_price($data);
	}

	function get_formated_price_per_unit($locale='') {

		$data=array(
			'Product Price'=>$this->data['Product Price'],
			'Product Units Per Case'=>$this->data['Product Units Per Case'],
			'Product Currency'=>$this->get('Product Currency'),
			'Product Unit Type'=>$this->data['Product Unit Type'],
			'Label'=>'',


			'locale'=>$locale);

		return formated_price_per_unit($data);
	}

	function update_units_per_case($units) {

		if (!is_numeric($units)) {
			$this->error=true;
			$this->msg='Units per case is not a number';
			return;
		}

		if ($units<=0) {
			$this->error=true;
			$this->msg='Units can not be zero or negative number';
			return;
		}



		$sql=sprintf("update `Product Dimension` set `Product Units Per Case`=%f where `Product ID`=%d",$units,$this->pid);
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		$this->data['Product Units Per Case']=$units;
		$this->new_value=$units;
		$this->updated=true;

	}




	function get_xhtml_part_links($field) {
		global $user;
		$xhtml_link='';
		$part_skus=$this->get_current_part_skus();
		$number_of_parts=count($part_skus);
		if ($number_of_parts==0) {

			return _('No parts associated with product');

		}

		$edit_part_page_block='';

		switch ($field) {
		case('Product Use Part Properties'):
			$edit_part_page_block='&edit=description&edit_description_block=properties';
		case('Product Use Part Pictures'):
			if ($edit_part_page_block=='')
				$edit_part_page_block='&edit=description&edit_description_block=pictures';
		case('Product Use Part H and S'):
			if ($edit_part_page_block=='')
				$edit_part_page_block='&edit=description&edit_description_block=health_and_safety';
		case('Product Use Part Tariff Data'):
			if ($edit_part_page_block=='')
				$edit_part_page_block='&edit=description&edit_description_block=description';


			$part_sku=array_shift($part_skus);

			if ($user->can_edit('parts')) {
				$xhtml_link= _('Linked to part').sprintf(': <a href="edit_part.php?sku=%d%s">SKU%05d</a>',
					$part_sku,
					$edit_part_page_block,
					$part_sku

				);
			}
			elseif ($user->can_view('parts')) {
				$xhtml_link= _('Linked to part').sprintf(': <a href="part.php?sku=%d">SKU%05d</a>',
					$part_sku,
					$part_sku
				);
			}
			else {
				$xhtml_link= _('Linked to part').sprintf(': SKU%05d',
					$part_sku
				);
			}




			if ($field=='Product Use Part Properties') {
				$xhtml_link.=' <span style="font-size:80%; vertical-align:bottom;">o</span>';
				if ($this->data['Product Part Ratio']==1) {
					$xhtml_link.='&#8801;, ';
				}elseif ($this->data['Product Part Ratio']==0) {
					$xhtml_link.='&#8230;,';
				}elseif ($this->data['Product Part Ratio']>1) {
					$xhtml_link.='&#8834;<span style="font-size:80%; vertical-align:bottom;">'.$this->data['Product Part Units Ratio'].'</i>,';
				}else {
					$xhtml_link.='&#8835;,';
				}
				$xhtml_link.=' <span style="font-size:80%; vertical-align:bottom;">u</span>';


				if ($this->data['Product Part Units Ratio']==1) {
					$xhtml_link.='&#8801;';
				}elseif ($this->data['Product Part Units Ratio']==0) {
					$xhtml_link.='&#8230;';
				}elseif ($this->data['Product Part Units Ratio']>1) {
					$xhtml_link.='&#8834;<span style="font-size:80%; vertical-align:bottom;">'.$this->data['Product Part Units Ratio'].'</span>';
				}else {
					$xhtml_link.='&#8835;';
				}

			}

			break;
		}



		return $xhtml_link;
	}


	function update_part_links($field,$value) {

		if ($value=='Yes') {

			$part_list=$this->get_parts_objects();
			$number_of_parts=count($part_list);
			if ($number_of_parts==0) {

				$this->error=true;
				$this->msg=_('No parts associated with product');
				return;
			}

			switch ($field) {

			case('Product Use Part H and S'):
				$part=array_shift($part_list);
				$this->update_field('Product UN Number',$part->data['Part UN Number']);
				$this->update_field('Product UN Class',$part->data['Part UN Class']);
				$this->update_field('Product Health And Safety',$part->data['Part Health And Safety']);
				$this->update_field('Product Packing Group',$part->data['Part Packing Group']);
				$this->update_field('Product Proper Shipping Name',$part->data['Part Proper Shipping Name']);
				$this->update_field('Product Hazard Indentification Number',$part->data['Part Hazard Indentification Number']);

				break;
			case('Product Use Part Tariff Data'):

				$part=array_shift($part_list);


				$this->update_field('Product Tariff Code',$part->data['Part Tariff Code']);
				$this->update_field('Product Duty Rate',$part->data['Part Duty Rate']);

				break;
			case('Product Use Part Properties'):
				break;
			case('Product Use Part Pictures'):
			}

		}

		$this->update_field($field,$value);



	}




	function update_units_type($value) {

		$valid_values=getEnumValues('Product Dimension', 'Product Unit Type');

		if (!in_array($value,$valid_values)) {
			$this->error=true;
			$this->msg='Not valid value';
			return;
		}

		$sql=sprintf("update `Product Dimension` set `Product Unit Type`=%s where `Product ID`=%d",prepare_mysql($value),$this->pid);
		//print $sql;
		mysql_query($sql);
		if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
		$this->data['Product Unit Type']=$value;
		$this->new_value=$value;
		$this->updated=true;

	}

	function get_parts_info() {
		$sql=sprintf("select `Part Stock State`,IFNULL(`Part Days Available Forecast`,'UNK') as days,`Parts Per Product`,`Product Part List Key`,`Product Part List Note`,PPL.`Part SKU`,`Part Unit Description` from `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`)    left join `Part Dimension` PD on (PD.`Part SKU`=PPL.`Part SKU`) where PPD.`Product ID`=%d and PPD.`Product Part Most Recent`='Yes';",$this->data['Product ID']);
		//print $sql;
		$result=mysql_query($sql);
		$parts=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$part=new Part($row['Part SKU']);
			$parts[$row['Part SKU']]=array(
				'key'=>$row['Product Part List Key'],
				'sku'=>$part->get_sku(),
				'description'=>$part->get_description(),
				'note'=>$row['Product Part List Note'],
				'parts_per_product'=>$row['Parts Per Product'],
				'days_available'=>$row['days'],
				'stock_state'=>$row['Part Stock State']
			);
		}
		return $parts;

	}


	function update_weight_from_parts() {




		$parts_info=$this->get_parts_info();
		$weight_unit=0;
		$weight_package=0;
		$weight_unit_units=array();
		$weight_package_units=array();
		foreach ($parts_info as $sku => $part_info) {
			$part=new Part($sku);
			$weight_package+= $part_info['parts_per_product']*$part->data['Part Package Weight'];
			if (array_key_exists($part->data['Part Unit Weight Display Units'], $weight_unit_units)) {
				$weight_unit_units[$part->data['Part Unit Weight Display Units']]+=1;
			}else {
				$weight_unit_units[$part->data['Part Unit Weight Display Units']]=1;
			}
			if (array_key_exists($part->data['Part Package Weight Display Units'], $weight_package_units)) {
				$weight_package_units[$part->data['Part Package Weight Display Units']]+=1;
			}else {
				$weight_package_units[$part->data['Part Package Weight Display Units']]=1;
			}
			if ($part_info['parts_per_product']!=0)
				$weight_unit+=$this->data['Product Units Per Case']/$part_info['parts_per_product']*$part->data['Part Unit Weight'];

		}

		$this->update_field('Product Parts Weight',$weight_package);

		if ($this->data['Product Use Part Properties']=='Yes') {

			asort($weight_unit_units);
			asort($weight_package_units);

			$tmp_lastValue = end($weight_unit_units);
			$weight_unit_units_lastKey = key($weight_unit_units);
			$tmp_lastValue = end($weight_package_units);
			$weight_package_units_lastKey = key($weight_package_units);

			include_once 'common_units_functions.php';

			$weight_unit_display=convert_units($weight_unit,'Kg',$weight_unit_units_lastKey);
			$weight_package_display=convert_units($weight_package,'Kg',$weight_package_units_lastKey);

			$this->update_field('Product XHTML Unit Weight',($weight_unit_display>0?number($weight_unit_display).$weight_unit_units_lastKey:''));

			$this->update_field('Product XHTML Package Weight',($weight_package_display>0?number($weight_package_display).$weight_package_units_lastKey:''));

		}





	}


	function update_part_ratio() {

		$parts_info=$this->get_parts_info();
		if (count($parts_info)!=1) {
			$units_ratio=0;
			$ratio=0;
		}else {
			$part_info=array_pop($parts_info);
			$ratio=$part_info['parts_per_product'];
			if ($part_info['parts_per_product']==0) {
				$units_ratio=0;

			}else {

				$units_ratio=$this->data['Product Units Per Case']/$part_info['parts_per_product'];
			}

		}

		$sql=sprintf("update `Product Dimension` set `Product Part Ratio`=%f,`Product Part Units Ratio`=%f where `Product ID`=%d",
			$ratio,
			$units_ratio,
			$this->pid
		);
		mysql_query($sql);
		$this->data['Product Part Ratio']=$ratio;
		$this->data['Product Part Units Ratio']=$units_ratio;


	}


	
	function update_part_list_item($product_part_list_key,$data) {

		$sql=sprintf("select `Parts Per Product`,`Product Part List Note` from `Product Part List` where `Product ID`=%d and `Product Part List Key`=%d",$this->pid,$product_part_list_key);
		$result=mysql_query($sql);
		$this->new_value=array();
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			foreach ($data as $key=>$value) {
				//print_r($row);
				//print "$value $key";
				if (array_key_exists($key,$row) and $row[$key]!=$value) {

					$sql=sprintf("update `Product Part List` set `$key`=%s where `Product Part List Key`=%d ",prepare_mysql($value),$product_part_list_key);
					// print $sql;
					mysql_query($sql);
					$this->updated=true;
					$this->new_value[$key]=$value;

				}
			}



		} else {
			$this->error=true;
			$this->msg='Part list item not associated with product id';
		}


		if (array_key_exists('Parts Per Product',$this->new_value)) {
			$this->update_parts();
		}

	}


	function update_full_search() {

		$first_full_search=$this->data['Product Code'].' '.$this->data['Product Short Description'];
		$second_full_search='';

		if ($this->data['Product Main Image']!='art/nopic.png')
			$img=preg_replace('/small/','thumbnails',$this->data['Product Main Image']);
		else
			$img='';

		$description1='<b><a href="product.php?pid='.$this->pid.'">'.$this->data['Product Code'].'</a></b><br/>'.$this->data['Product XHTML Parts'];
		$description2=$this->data['Product XHTML Short Description'];
		$description='<table ><tr><td class="col1"'.$description1.'</td><td class="col2">'.$description2.'</td></tr></table>';

		$sql=sprintf("insert into `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`) values  (%s,'Product',%d,%s,%s,%s,%s,%s) on duplicate key
                     update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s"
			,$this->data['Product Store Key']
			,$this->pid
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->data['Product Code'],false)
			,prepare_mysql($description,false)
			,prepare_mysql($img,false)
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->data['Product Code'],false)
			,prepare_mysql($description,false)
			,prepare_mysql($img,false)
		);
		mysql_query($sql);
		//exit($sql);
	}


	function set_duplicates_as_historic($date=false) {
		$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s and `Product Record Type`!='Historic' and `Product ID`!=%d "
			,$this->data['Product Store Key']
			,prepare_mysql($this->code)
			,$this->pid

		);

		$res_code=mysql_query($sql);
		$old_pids=array();
		while ($row_c=mysql_fetch_array($res_code)) {
			$old_pids[]=$row_c['Product ID'];
			$product_to_set_as_historic=new Product('pid',$row_c['Product ID']);
			$product_to_set_as_historic->set_as_historic($date);
		}

		return $old_pids;

	}


	function set_as_historic($date=false) {
		if (!$date) {
			$date=date("Y-m-d H:i:s");
		}

		$sql=sprintf("update `Product Dimension` set `Product Valid To`=%s,`Product Record Type`='Historic',`Product Availability Type`='Discontinued',`Product Web Configuration`='Offline',`Product Sales Type`='Public Sale',`Product Web State`='Offline',`Product Availability`=0,`Product Available Days Forecast`=0,`Product XHTML Available Forecast`='Historic',`Product Availability State`='OutofStock' where `Product ID`=%d"
			,prepare_mysql($date)
			,$this->pid);
		//  print "$sql\n";
		mysql_query($sql);




		//$this->data['Product Record Type']='Historic';
		$this->get_data('pid',$this->data['Product ID']);
		// $this->update_sales_type('Public Sale');

		$sql=sprintf("update `Product History Dimension` set `Product History Valid To`=%s where `Product Key`=%d"
			,prepare_mysql($date)
			,$this->data['Product Current Key']
		);

		if (!mysql_query($sql))
			exit($sql);



		$this->update_main_type();
		$this->update_availability_type();
		$this->update_availability();



		//     $sql=sprintf("update `Product Part Dimension` set `Product Part Valid To`=%s  where `Product ID`=%d  "
		//                ,prepare_mysql($date)
		//               ,$this->pid

		//            );
		//if (!mysql_query($sql))
		//   exit($sql);


	}
	function update_cost() {

		$cost=$this->get_cost_supplier();

		$sql=sprintf("update `Product Dimension` set `Product Cost`=%s  where `Product ID`=%d "
			,$cost
			,$this->pid
		);
		mysql_query($sql);
	}


	function get_cost_supplier($date=false) {
		$cost=0;

		foreach ($this->get_part_list() as $part_data) {
			$part=$part_data['part'];

			if ($part->data['Part Current Stock']>0 and !$date) {
				$part_cost=$part->data['Part Current Stock Cost Per Unit'];
			} else {
				$part_cost=$part->get_unit_cost($date);
			}


			$cost+=$part_cost*$part_data['Parts Per Product'];

		}


		// exit;
		return $cost;


	}

	


	function update_web_state() {

		$old_web_state=$this->data['Product Web State'];

		$web_state=$this->get_web_state();
		$sql=sprintf('update `Product Dimension` set `Product Web State`=%s where `Product ID`=%d',
			prepare_mysql($web_state),
			$this->pid
		);

		//print "$sql\n";
		mysql_query($sql);
		$this->data['Product Web State']=$web_state;

		if ($old_web_state=='Offline' and $this->data['Product Web State']!='Offline') {
			$sql=sprintf("select `Page Key` from `Page Product List Dimension` where `Page Product List Type`='FamilyList' and `Page Product List Parent Key`=%d ",
				$this->data['Product Family Key']);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$page=new Page($row['Page Key']);
				$page->update_list_products();
			}

		}

		if ($old_web_state!='Offline' and $this->data['Product Web State']=='Offline') {
			$sql=sprintf("select `Page Key` from `Page Product Dimension` where `Product ID`=%d and `Parent Type`='List'",
				$this->pid);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$page=new Page($row['Page Key']);
				$page->update_list_products();
			}

		}


	}


	function get_formated_sales_type() {
		switch ($this->data['Product Sales Type']) {
		case('Public Sale'):
			$sales_type=_('Public Sale');
			break;
		case('Private Sale'):
			$sales_type=_('Private Sale');
			break;
		case('Not for Sale'):
			$sales_type=_('Not for Sale');
			break;


		}
		return $sales_type;
	}

	function get_formated_web_state() {

		switch ($this->data['Product Web Configuration']) {
		case('Online Force Out of Stock'):
			$web_configuration=_('Forced');
			break;
		case('Online Auto'):
			$web_configuration=_('Auto');
			break;
		case('Offline'):
			$web_configuration=_('Forced');
			break;
		case('Online Force For Sale'):
			$web_configuration=_('Forced');
			break;

		}

		switch ($this->data['Product Web State']) {
		case('Out of Stock'):
			$web_state='<span class=="out_of_stock">['._('Out of Stock').']</span>';
			break;
		case('For Sale'):
			$web_state='';
			break;
		case('Discontinued'):
			$web_state=_('Discontinued');
		case('Offline'):
			$web_state=_('Offline');
			break;


		}


		$description='<span class="web_state">'.$web_state.'</span> (<span>'.$web_configuration.')</span>';

		return $description;

	}

	function get_web_state() {





		if ($this->data['Product Sales Type']!='Public Sale'  or $this->data['Product Record Type']=='Historic' or $this->data['Product Stage']=='In Process') {

			return 'Offline';
		}

		switch ($this->data['Product Web Configuration']) {
		case 'Offline':
			return 'Offline';
			break;
		case 'Online Force Out of Stock':
			return 'Out of Stock';
			break;
		case 'Online Force For Sale':
			return 'For Sale';
			break;
		case 'Online Auto':

			if ($this->data['Product Availability']>0) {
				return 'For Sale';
			} else {

				if ($this->data['Product Availability Type']=='Discontinued') {


					$sql=sprintf("select `Store Web Days Until Remove Discontinued Products` as days from `Store Dimension` where `Store Key`=%d",$this->data['Product Store Key']);
					$res=mysql_query($sql);

					$interval=7776000;
					// print "$sql\n";
					if ($row=mysql_fetch_assoc($res)) {
						if ($row['days'])
							$interval=$row['days']*86400;


					}
					if (date('U')-strtotime($this->data['Product Valid To'])>$interval  )
						return 'Offline';
					else
						return 'Discontinued';



				} else {

					return 'Out of Stock';
				}

			}

			break;

		default:
			return 'Offline';
			break;
		}






	}







	function update_stage($value) {

		$sql=sprintf("update `Product Dimension` set `Product Stage`=%s  where  `Product ID`=%d "
			,prepare_mysql($value)
			,$this->pid
		);
		mysql_query($sql);

	}

	function update_sales_type($value) {
		if (
			$value=='Public Sale' or $value=='Private Sale'
			or $value=='Not For Sale' or $value=='Discontinued Public Sale'
		) {

			$sales_state=$value;


			$sql=sprintf("update `Product Dimension` set `Product Sales Type`=%s  where  `Product ID`=%d "
				,prepare_mysql($sales_state)
				,$this->pid
			);
			//print $sql;
			if (mysql_query($sql)) {



				$this->data['Product Sales Type']=$sales_state;

				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
				$this->msg=_('Product Sales Type updated');
				$this->updated=true;




				//if ($value=='Public Sale')
				// $_web_configuration='Online Auto';
				//else
				// $_web_configuration='Offline';
				//$this->update_web_configuration($_web_configuration);


				$this->update_main_type();
				$this->update_availability_type();
				$this->update_availability();


				$this->msg=_('Product Sales Type updated');
				$this->new_value=$value;


				return;
			} else {
				$this->msg=_("Error: Product sales type could not be updated ");
				$this->updated=false;
				return;
			}
		} else
			$this->msg=_("Error: wrong value")." [Sales Type] ($value)";
		$this->updated=false;
	}



	function update_web_configuration($a1) {
		//print "update web cont\n";

		if ($a1!='Online Force Out of Stock' and $a1!='Online Auto' and $a1!='Offline'
			and $a1!= 'Online Force For Sale'      ) {
			$this->msg='Wrong value '.$a1;
			$this->error=true;
			return;
		}


		$web_state=$a1;
		$sql=sprintf("update `Product Dimension` set `Product Web Configuration`=%s  where  `Product ID`=%d "
			,prepare_mysql($web_state)
			,$this->pid
		);
		mysql_query($sql);
		$this->data['Product Web Configuration']=$web_state;

		$this->update_web_state();


		//if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);



		$this->msg=_('Product Web Configuration updated');
		$this->updated=true;
		$this->data['Product Web Configuration']=$web_state;
		$this->update_web_state();
		$this->new_value=$this->data['Product Web Configuration'];

		switch ($this->data['Product Web Configuration']) {
		case('Online Force Out of Stock'):
			$formated_web_configuration_bis='<img src="art/icons/police_hat.jpg" style="height:16px;;vertical-align:top" /> '._('Out of stock');
			$formated_web_configuration=_('Force Out of Stock');
			break;
		case('Online Auto'):
			$formated_web_configuration_bis=_('Automatic');
			$formated_web_configuration=_('Automatic');
			break;
		case('Offline'):
			$formated_web_configuration_bis='<img src="art/icons/police_hat.jpg" style="height:16px;;vertical-align:top" /> '._('Offline');
			$formated_web_configuration=_('Force Offline');
			break;
		case('Online Force For Sale'):
			$formated_web_configuration_bis='<img src="art/icons/police_hat.jpg" style="height:16px;;vertical-align:top" /> '._('Online');
			$formated_web_configuration=_('Force Online');
			break;
		default:
			$formated_web_configuration='';
			$formated_web_configuration_bis='';
			break;

		}

		$this->update_number_pages();

		if ($this->data['Product Number Web Pages']==0) {
			$web_state=_('Not on website');
			$icon='<img src="art/icons/world_light_bw.png" alt="" title="'._('Not in website').'" />';

		}else {


			switch ($this->data['Product Web State']) {
			case('Out of Stock'):
				$web_state='<span class=="out_of_stock">['._('Out of Stock').']</span>';
				$icon='<img src="art/icons/no_stock.jpg" alt="" />';
				break;
			case('For Sale'):
				$web_state='';
				$icon='<img src="art/icons/world.png" alt="" />';
				break;
			case('Discontinued'):
				$web_state=_('Discontinued');
				$icon='<img src="art/icons/sold_out.gif" alt="" />';
			case('Offline'):
				$web_state=_('Offline');
				$icon='<img src="art/icons/sold_out.gif" alt="" />';
				break;

			default:
				$web_state='';
				$icon='';
				break;
			}
		}

		if ($this->data['Product Sales Type']!='Public Sale') {
			$web_configuration=$this->data['Product Sales Type'];
			switch ($this->data['Product Sales Type']) {
			case 'Private Sale':
				$formated_web_configuration=_('Private Sale');
				break;
			default:
				$formated_web_configuration=_('Not For Sale');
				break;
			}
		} else {

			$web_configuration=$this->data['Product Web Configuration'];
		}


		$description=$this->data['Product XHTML Short Description'].' <span class="stock">'._('Stock').': '.number($this->data['Product Availability']).'</span> <span class="webs_tate">'.$web_state.'</span>';
		$this->new_data=array(
			'formated_web_configuration'=>$formated_web_configuration,
			'formated_web_configuration_bis'=>$formated_web_configuration_bis,
			'web_configuration'=>$web_configuration,
			'number_web_pages'=>$this->data['Product Number Web Pages'],
			'description'=>$description,
			'icon'=>$icon,
			'pid'=>$this->pid
		);

		return;


	}



	


	function remove_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("delete from `Image Bridge` where `Subject Type`='Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
			mysql_query($sql);
			$this->updated=true;
			$number_images=$this->get_number_of_images();
			if ($number_images==0) {
				$main_image_src='art/nopic.png';
				$main_image_key=0;
				$this->data['Product Main Image']=$main_image_src;
				$this->data['Product Main Image Key']=$main_image_key;
				$sql=sprintf("update `Product Dimension` set `Product Main Image`=%s ,`Product Main Image Key`=%d where `Product ID`=%d",
					prepare_mysql($main_image_src),
					$main_image_key,
					$this->pid
				);
				mysql_query($sql);
				print $sql;

			} else if ($row['Is Principal']=='Yes') {

					$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Product' and `Subject Key`=%d  ",$this->pid);
					$res2=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res2)) {
						$this->update_main_image($row2['Image Key']) ;
					}
				}


		} else {
			$this->error=true;
			$this->msg='image not associated';

		}





	}
	function update_image_caption($image_key,$value) {
		$value=_trim($value);



		$sql=sprintf("update `Image Bridge` set `Image Caption`=%s where  `Subject Type`='Product' and `Subject Key`=%d  and `Image Key`=%d"
			,prepare_mysql($value)
			,$this->pid,$image_key);
		mysql_query($sql);
		//print $sql;
		if (mysql_affected_rows()) {
			$this->new_value=$value;
			$this->updated=true;
		} else {
			$this->msg=_('No change');

		}

	}

	function get_main_image_key() {

		return $this->data['Product Main Image Key'];
	}
	function update_main_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
		$res=mysql_query($sql);
		if (!mysql_num_rows($res)) {
			$this->error=true;
			$this->msg='image not associated';
		}

		$sql=sprintf("update `Image Bridge` set `Is Principal`='No' where `Subject Type`='Product' and `Subject Key`=%d  ",$this->pid);
		mysql_query($sql);
		$sql=sprintf("update `Image Bridge` set `Is Principal`='Yes' where `Subject Type`='Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
		mysql_query($sql);


		$main_image_src='image.php?id='.$image_key.'&size=small';
		$main_image_key=$image_key;

		$this->data['Product Main Image']=$main_image_src;
		$this->data['Product Main Image Key']=$main_image_key;
		$sql=sprintf("update `Product Dimension` set `Product Main Image`=%s ,`Product Main Image Key`=%d where `Product ID`=%d",
			prepare_mysql($main_image_src),
			$main_image_key,
			$this->pid
		);

		mysql_query($sql);

		$this->updated=true;

	}


	function delete() {
		$sql=sprintf("select count(*) as num from `Order Transaction Fact` where `Product ID` = %d", $this->pid);
		$result=mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		if ($row['num'] > 0) {
			$this->delete = false;
			$this->msg = _("Product cannot be deleted");
		}
		else {
			$sql = sprintf("delete from `Product Dimension` where `Product ID` = %d", $this->pid);
			mysql_query($sql);
			$sql = sprintf("delete from `Product History Dimension` where `Product ID` = %d", $this->pid);
			mysql_query($sql);
			$this->delete = true;
			$this->msg = _("deleted");

		}
	}

	function post_add_history($history_key,$type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Product History Bridge` (`Product ID`,`History Key`,`Type`) values (%d,%d,%s)",
			$this->pid,
			$history_key,
			prepare_mysql($type)
		);
		mysql_query($sql);

	}

	function get_barcode_data() {



		switch ($this->data['Product Barcode Data Source']) {
		case 'ID':
			return $this->pid;
		case 'Key':
			return $this->id;
		default:
			return $this->data['Product Barcode Data'];


		}

	}

	function delete_info_sheet_attachment() {

		//print_r($this->data);

		if ($this->data['Product Info Sheet Attachment Bridge Key']=='') {
			$this->msg=_('No file is set up as info_sheet');
			return;
		}

		$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d",
			$this->data['Product Info Sheet Attachment Bridge Key']
		);
		mysql_query($sql);
		//print "$sql  xx\n";

		$attach=new Attachment($this->get_info_sheet_attachment_key());
		$attach->delete();
		$attach_info=$this->data['Product Info Sheet Attachment XHTML Info'];
		$sql=sprintf("update `Product Dimension` set `Product Info Sheet Attachment Bridge Key`=0, `Product Info Sheet Attachment XHTML Info`='' where `Product SKU`=%d ",
			$this->sku

		);
		mysql_query($sql);
		$this->data['Product Info Sheet Attachment XHTML Info']='';
		$this->data['Product Info Sheet Attachment Bridge Key']='';
		$history_data=array(
			'History Abstract'=>_('Info Sheet Attachment deleted').'.',
			'History Details'=>$attach_info,
			'Action'=>'edited',
			'Direct Object'=>'Attachment',
			'Prepostion'=>'',
			'Indirect Object'=>$this->table_name,
			'Indirect Object Key'=>$this->sku
		);

		$history_key=$this->add_subject_history($history_data,true,'No','Changes');
	}

	function update_info_sheet_attachment($attach,$filename,$caption) {

		if (!is_object($attach)) {
			$this->error=true;
			$this->msg='error attach not an object';
			return;
		}elseif (!$attach->id) {
			$this->error=true;
			$this->msg='error attach not found';
			return;

		}

		//print $attach->id."att id \n";


		if ($attach->id==$this->get_info_sheet_attachment_key()) {
			$this->msg=_('This file already set up as info sheet');
			return;
		}

		if ($this->data['Product Info Sheet Attachment Bridge Key']) {
			$this->delete_info_sheet_attachment();

		}



		$sql=sprintf("insert into `Attachment Bridge` (`Attachment Key`,`Subject`,`Subject Key`,`Attachment File Original Name`,`Attachment Caption`) values (%d,'Product Info Sheet',%d,%s,%s)",
			$attach->id,
			$this->sku,
			prepare_mysql($filename),
			prepare_mysql($caption)
		);
		mysql_query($sql);
		//print $sql;

		$attach_bridge_key=mysql_insert_id();
		$attach_info=$attach->get_abstract($filename,$caption,$attach_bridge_key);

		if ($this->data['Product Info Sheet Attachment Bridge Key']) {
			$history_data=array(
				'History Abstract'=>_('Info sheet replaced').'. '.$attach_info,
				'History Details'=>$attach->get_details(),
				'Action'=>'edited',
				'Direct Object'=>'Attachment',
				'Prepostion'=>'',
				'Indirect Object'=>$this->table_name,
				'Indirect Object Key'=>$this->sku
			);

		}else {
			$history_data=array(
				'History Abstract'=>_('Info sheet uploaded').'; '.$attach_info,
				'History Details'=>$attach->get_details(),
				'Action'=>'associated',
				'Direct Object'=>'Attachment',
				'Prepostion'=>'',
				'Indirect Object'=>$this->table_name,
				'Indirect Object Key'=>$this->sku
			);

		}


		$history_key=$this->add_subject_history($history_data,true,'No','Changes');

		$sql=sprintf("update `Product Dimension` set `Product Info Sheet Attachment Bridge Key`=%d, `Product Info Sheet Attachment XHTML Info`=%s where `Product SKU`=%d ",
			$attach_bridge_key,
			prepare_mysql($attach_info),
			$this->sku

		);
		mysql_query($sql);
		$this->data['Product Info Sheet Attachment Bridge Key']=$attach_bridge_key;
		$this->data['Product Info Sheet Attachment XHTML Info']=$attach_info;


		$this->updated=true;





	}


}
?>
