<?php
/*
 File: SupplierProduct.php

 This file contains the SupplierProduct Class
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
class supplierproduct extends DB_Table {

	public $external_DB_link=false;
	public $locale='en_GB';
	function supplierproduct($a1,$a2=false,$a3=false) {

		$this->table_name='Supplier Product';
		$this->ignore_fields=array(
			'Supplier Product ID'
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
	function find($raw_data,$options) {

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found_in_code=false;
		$this->found_in_key=false;



		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}
		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {

			if (array_key_exists($key,$data))
				$data[$key]=_trim($value);
		}

		if (!isset($raw_data['SPH Units Per Case']) or $raw_data['SPH Units Per Case']=='')
			$raw_data['SPH Units Per Case']=1;

		if (!isset($raw_data['SPH Case Cost']) or $raw_data['SPH Case Cost']=='')
			$raw_data['SPH Case Cost']=$raw_data['Supplier Product Cost Per Case'];

		$data['SPH Units Per Case']=$raw_data['SPH Units Per Case'];
		$data['SPH Case Cost']=$raw_data['SPH Case Cost'];

		if ($data['Supplier Product Code']=='' or $raw_data['SPH Case Cost']=='' ) {
			$this->error=true;
			$this->msg='No code/cost';
			return;
		}

		if ($data['Supplier Key']=='')
			$data['Supplier Key']=1;
		if ($data['Supplier Product Name']=='')
			$data['Supplier Product Name']=$data['Supplier Product Code'];





		$sql=sprintf("select `Supplier Product Code`,`Supplier Product ID` from `Supplier Product Dimension` where `Supplier Product Code`=%s  and  `Supplier Key`=%d "
			,prepare_mysql($data['Supplier Product Code'])
			,$data['Supplier Key']
		);
		$result4=mysql_query($sql);
		if ($row4=mysql_fetch_array($result4)) {
			$this->found_in_code=true;
			$this->found_pid=$row4['Supplier Product ID'];
			$this->found_code=$row4['Supplier Product Code'];
			$this->get_data('code',$data['Supplier Product Code'],$data['Supplier Key']);
			$sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product ID`=%d  and `SPH Case Cost`=%.2f and `SPH Units Per Case`=%d"

				,$row4['Supplier Product ID']
				,$data['SPH Case Cost']
				,$data['SPH Units Per Case']
			);
			//print "$sql\n";
			$result2=mysql_query($sql);
			if ($row2=mysql_fetch_array($result2)) {
				$this->found_in_key=true;
				$this->found=true;
				$this->found_key=$row2['SPH Key'];
				$this->get_data('key',$this->found_key);

			}
		}



		// print "FK: ".$this->found_in_key." FC:".$this->found_in_code."\n";

		if ($create) {

			if ($this->found_in_key) {
				$this->get_data('key',$this->found_key);



			}
			elseif ($this->found_in_code) {

				$this->get_data('pid',$this->pid);
				$data['Supplier Product ID']=$this->pid;
				$this->create_key($data);

			}
			else {
				// print_r($data);
				$this->create($data);
			}

			if (isset($raw_data['Supplier Product Valid From']))
				$this->update_valid_dates($raw_data['Supplier Product Valid From']);
			if (isset($raw_data['Supplier Product Valid To']))
				$this->update_valid_dates($raw_data['Supplier Product Valid To']);
			else {
				$this->update_valid_dates(gmdate('Y-m-d H:i:s'));
			}
		}

	}
	function get_data($tipo,$tag,$supplier_key=1) {
		if ($tipo=='id' or $tipo=='key') {
			$sql=sprintf("select * from `Supplier Product History Dimension` where `SPH Key`=%d ",$tag);
			//print "$sql\n";
			$result=mysql_query($sql);
			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->id=$this->data['SPH Key'];
				$this->key=$this->id;
				$sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product ID`=%d "

					,$this->data['Supplier Product ID']
				);

				$result2=mysql_query($sql);
				if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
					$this->code=$row['Supplier Product Code'];
					$this->supplier_key=$row['Supplier Key'];
					$this->pid=$row['Supplier Product ID'];
					foreach ($row as $key=>$value) {
						$this->data[$key]=$value;
					}


				} else {
					$this->pid=0;
					$this->code='';
					$this->supplier_key='';
					$this->data['Supplier Product Code']='';
					$this->data['Supplier Key']='';

				}
			}
			return;

		} else if ($tipo=='code') {
				$sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Code`=%s and   `Supplier Key`=%d "
					,prepare_mysql($tag)
					,$supplier_key
				);

				$result=mysql_query($sql);
				if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
					$this->id=$this->data['Supplier Product Current Key'];

					$this->pid=$this->data['Supplier Product ID'];

					$this->key=$this->id;
					$this->code=$this->data['Supplier Product Code'];
					$this->supplier_key=$this->data['Supplier Key'];
				}
				return;

			}
		elseif ($tipo=='pid') {
			$sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product ID`=%d",
				$tag

			);

			$result=mysql_query($sql);
			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->id=$this->data['Supplier Product Current Key'];

				$this->pid=$this->data['Supplier Product ID'];

				$this->key=$this->id;
				$this->code=$this->data['Supplier Product Code'];
				$this->supplier_key=$this->data['Supplier Key'];

				$sql=sprintf("select * from `Supplier Product History Dimension` where `SPH Key`=%d ",$this->id);

				$result2=mysql_query($sql);
				if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {

					foreach ($row as $key=>$value) {
						$this->data[$key]=$value;
					}

				}
			}

		}
	}
	function create($data) {
		$this->new_key=false;
		$this->new_code=false;
		$this->create_key($data);
		$this->create_code($data);



		$this->msg='Supplier Product Created';
		$this->new=true;


	}
	function create_key($data) {
		//print_r($data);
		$base_data=array(
			'SPH Case Cost'=>'',
			'SPH Units Per Case'=>'1',
			'SPH Type'=>'Normal',
			'SPH Valid From'=>gmdate("Y-m-d H:i:s"),
			'SPH Valid To'=>gmdate("Y-m-d H:i:s"),
		);





		foreach ($data as $key=>$value) {

			if ($key=='Supplier Product Valid From') {
				$key='SPH Valid From';
			}
			elseif ($key=='Supplier Product Valid To') {
				$key='SPH Valid To';
			}


			$key=preg_replace('/^supplier product /i','sph ',$key);
			if (array_key_exists($key,$base_data))

				$base_data[$key]=_trim($value);
		}


		if (array_key_exists('Supplier Product ID',$data))
			$base_data['Supplier Product ID']=$data['Supplier Product ID'];


		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {

			if ($key=='SPH Case Cost') {
				$keys.="`$key`,";
				$values.=sprintf("%.2f",$value).",";

			} else {

				$keys.="`$key`,";
				$values.=prepare_mysql($value).",";

			}
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Supplier Product History Dimension` %s %s",$keys,$values);
		// print "$sql\n\n";
		if (mysql_query($sql)) {
			$this->key = mysql_insert_id();
			$this->new_key=true;
			$this->new_key_id=$this->key;
			$this->get_data('key',$this->key);
			//print $this->key."\n";

		} else {
			print "$sql  Error can not create Supplier Product\n";
			exit;
		}

	}
	function create_code($data) {
		$base_data=array(
			'Supplier Key'=>1,
			'Supplier Product Code'=>'',
			'Supplier Product Name'=>'',
			'Supplier Product Description'=>'',

			'Supplier Product Valid From'=>gmdate("Y-m-d H:i:s"),
			'Supplier Product Valid To'=>gmdate("Y-m-d H:i:s"),

		);

		foreach ($data as $key=>$value) {
			if (isset($base_data[$key]))
				$base_data[$key]=_trim($value);
		}
		$supplier=new Supplier($base_data['Supplier Key']);
		$base_data['Supplier Code']=$supplier->data['Supplier Code'];
		$base_data['Supplier Name']=$supplier->data['Supplier Name'];
		$base_data['Supplier Product Current Key']=$this->key;


		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {


			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";


		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Supplier Product Dimension` %s %s",$keys,$values);
		//print "$sql\n\n";
		if (mysql_query($sql)) {
			//print mysql_affected_rows()."\n";
			$this->code = $base_data['Supplier Product Code'];
			$this->supplier_key = $base_data['Supplier Key'];
			$this->pid=mysql_insert_id();
			$this->new_key_id=$this->pid;
			$this->new_code=true;
			$this->id_=mysql_insert_id();
			$sql=sprintf("update `Supplier Product History Dimension` set `Supplier Product ID`=%d where `SPH Key`=%d",
				$this->pid,
				$this->id
			);
			mysql_query($sql);
			$this->get_data('pid',$this->pid);
		} else {
			print "$sql  Error can not create Supplier Product\n";
			exit;
		}

	}
	function get_products() {
		$products=array();
		$sql=sprintf("select PD.`Product ID`,`Product Code`,`Supplier Product Units Per Part`,`Parts Per Product`
                         from `Supplier Product Part List` SPPL
                         left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)
                         left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`)
                         left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`)
                         left join `Product Dimension` PD on (PPD.`Product ID`=PD.`Product ID`)
                         where SPPD.`Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' group by `Product Code`;"

			,$this->pid
		);


		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$units_ratio=  1.0/$row['Supplier Product Units Per Part']/$row['Parts Per Product'];
			$products[$row['Product ID']]=array('Product ID'=>$row['Product ID'],'Product Code'=>$row['Product Code'],'Units Ratio'=>$units_ratio);
		}

		return $products;
	}
	function load($data_to_be_read,$args='') {
		switch ($data_to_be_read) {

		case('parts'):

			$parts=$this->get_parts();

			$this->parts_sku=array();
			foreach ($parts as $key=>$value) {
				$this->parts_sku[]=$key;
			}


			break;
		case('sales'):
			$this->upload_sales();
		case('current_key_sales'):
			$this->upload_current_key_sales();



			break;

		}
	}

	function get_part_skus() {
		$part_skus=array();
		$sql=sprintf("select SPPL.`Part SKU` from  `Supplier Product Part Dimension` SPPD left join  `Supplier Product Part List` SPPL on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Part Dimension` P on (SPPL.`Part SKU`=P.`Part SKU`) where `Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' group by  `Part SKU`;",
			$this->pid
		);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$part_skus[$row['Part SKU']]=$row['Part SKU'];
		}
		return $part_skus;
	}

	function get_number_historic_parts() {

		$number_historic_parts=0;
		$sql=sprintf("select count(distinct `Part SKU`) as num from `Supplier Product Part List` L left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`) where `Supplier Product Part Most Recent`='No' and `Supplier Product ID`=%d",
			$this->pid
		);
		//print $sql;
		$res = mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)  ) {
			$number_historic_parts=$row['num'];
		}
		return $number_historic_parts;

	}



	function get_parts() {
		$parts=array();
		$sql=sprintf("select SPPL.`Part SKU`,`Supplier Product Units Per Part`,`Part Unit`  from  `Supplier Product Part Dimension` SPPD left join  `Supplier Product Part List` SPPL on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Part Dimension` P on (SPPL.`Part SKU`=P.`Part SKU`) where `Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' group by  `Part SKU`;",
			$this->pid
		);


		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



			$parts[$row['Part SKU']]=array(
				'Part_SKU'=>$row['Part SKU'],
				'Supplier_Product_Units_Per_Part'=>$row['Supplier Product Units Per Part'],
				'Part_Unit'=>$row['Part Unit'],
				'part'=>new Part($row['Part SKU']),
				'Parts_Per_Supplier_Product_Unit'=>($row['Supplier Product Units Per Part']==0?0:1/$row['Supplier Product Units Per Part']),
			);
		}

		return $parts;
	}
	function get_parts_objects() {
		$parts=array();
		$sql=sprintf("select `Part SKU`  from  `Supplier Product Part Dimension` SPPD left join  `Supplier Product Part List` SPPL on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) where `Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' group by  `Part SKU`;",
			$this->id
		);
		// print "$sql\n";
		//exit;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$parts[$row['Part SKU']]=new Part($row['Part SKU']);

		}

		return $parts;
	}
	function get($key='') {

		if (array_key_exists($key,$this->data))
			return $this->data[$key];

		$_key=preg_replace('/^Supplier Product /','',$key);
		if (isset($this->data[$_key]))
			return $this->data[$key];


		switch ($key) {
		case 'Origin Country Code':
			if ($this->data['Supplier Product Origin Country Code']) {
				include_once 'class.Country.php';
				$country=new Country('code',$this->data['Supplier Product Origin Country Code']);
				return $country->get_country_name($this->locale);
			}else {
				return '';
			}
			break;
		case('Units Per Case'):
			return number($this->data['Supplier Product '.$key]);
			break;
		case('Unit'):
			return $this->get_formated_unit();
			break;
		case('Formated Cost'):
			
			return money($this->data['Supplier Product Cost Per Case'],$this->data['Supplier Product Currency']);

		}

		return false;
	}
	function valid_id($id) {
		if (is_numeric($id) and $id>0 and $id<9223372036854775807)
			return true;
		else
			return false;
	}
	function used_id($id) {
		$sql="select count(*) as num from `Supplier Product Dimension` where `Supplier Product ID`=".prepare_mysql($id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['num']>0)
				return true;
		}
		return false;
	}
	function new_id() {
		$sql="select max(`Supplier Product ID`) as id from `Supplier Product Dimension`";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			return $row['id']+1;
		} else
			return 1;

	}
	
	function update_valid_dates($date) {
		$this->update_valid_dates_key($date);
		$this->update_valid_dates_code($date);

	}
	function update_valid_dates_key($date) {
		$affected=0;
		$sql=sprintf("update `Supplier Product History Dimension`  set `SPH Valid From`=%s where  `SPH Key`=%d and `SPH Valid From`>%s   "
			,prepare_mysql($date)
			,$this->id
			,prepare_mysql($date)

		);
		mysql_query($sql);
		$affected+=mysql_affected_rows();
		$sql=sprintf("update `Supplier Product History Dimension`  set `SPH Valid To`=%s where  `SPH Key`=%d and `SPH Valid To`<%s   "
			,prepare_mysql($date)
			,$this->id
			,prepare_mysql($date)

		);
		mysql_query($sql);
		$affected+=mysql_affected_rows();
		return $affected;
	}
	function update_valid_dates_code($date) {
		$affected=0;
		$sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid From`=%s where  `Supplier Product ID`=%d and `Supplier Product Valid From`>%s   "

			,prepare_mysql($date)
			,$this->pid
			,prepare_mysql($date)

		);
		mysql_query($sql);
		$affected+=mysql_affected_rows();
		$sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid To`=%s where `Supplier Product ID`=%d and `Supplier Product Valid To`<%s   "
			,prepare_mysql($date)
			,$this->pid
			,prepare_mysql($date)

		);
		mysql_query($sql);
		$affected+=mysql_affected_rows();
		return $affected;
	}
	function update_stock() {
		$parts=$this->get_parts();

		$stock=0;
		if (count($parts)==1) {
			$part_data=array_pop($parts);
			//print_r($part_data);
			$part=$part_data['part'];
			if ($part->sku)
				$stock=$part->data['Part Current Stock']*$part_data['Supplier_Product_Units_Per_Part'];


		}

		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Stock`=%f  where `Supplier Product Code`=%s and `Supplier Key`=%d "
			,$stock
			,prepare_mysql($this->data['Supplier Product Code'])
			,$this->data['Supplier Key']
		);
		mysql_query($sql);
		//print "$sql\n";

	}
	function update_days_available() {
		$parts=$this->get_parts();
		$days_until=0;
		if (count($parts)==1) {
			$part_data=array_pop($parts);
			$part=$part_data['part'];
			if ($part->sku)
				$days_until=$part->data['Part Days Available Forecast'];



		}

		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Days Available`=%f  where `Supplier Product Code`=%s and `Supplier Key`=%d "
			,$days_until
			,prepare_mysql($this->data['Supplier Product Code'])
			,$this->data['Supplier Key']
		);
		mysql_query($sql);
		//print "$sql\n";

	}
	function update_cost($value) {
		$change_at='now';

		$amount=$value;
		if ($amount==$this->data['Supplier Product Cost Per Case']) {
			$this->updated=false;
			$this->new_value=$amount;
			//$this->new_value=money($amount,$this->data['Supplier Product Currency']);
			return;

		}
		
		
		
		
		$old_formated_price=$this->get('Formated Price');
		$sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product ID`=%d and `SPH Case Cost`=%.2f "

			
			,$this->pid
			,$amount
		);
		 
		$res=mysql_query($sql);

		$num_historic_records=mysql_num_rows($res);
		
		
		
		if ($num_historic_records==0) {
			$data=array(
				'SPH Case Cost'=>$amount
				,'Supplier Product ID'=>$this->pid
			);
			$this->create_key($data);

			if ($change_at=='now') {
				$this->change_current_key($this->new_key_id);

			}
			$this->updated=true;

		}
		elseif ($num_historic_records==1) {
			$row=mysql_fetch_assoc($res);
			
			$key_matched=$row['SPH Key'];
			if ($change_at=='now') {
				$this->change_current_key($key_matched);

			}
			$this->updated=true;
		}
		else {
			exit("exit more that one hitoric product\n ");

		}



		if ($this->updated) {




			$this->new_value=$this->get('Formated Cost');

			$note=_('Supplier Product Cost Changed').' ('.$this->code.','.$this->get('Formated Cost').')';
			$details=_('Supplier Product').": ".$this->code." (Supplier:".$this->data['Supplier Code'].") "._('cost changed').' '._('from')." ".$old_formated_price."  "._('to').' '. $this->get('Formated Cost') ;
			$action='edited';
		}
	}


	function update_state($value) {


		if (!in_array($value,array('Available','NoAvailable','Discontinued'))) {
			$this->error=true;
			$this->msg='wrong Supplier Product State value: '.$value;
			return;
		}

		$old_value=$this->data['Supplier Product State'];

		if ($old_value==$value) {

			return;
		}


		switch ($value) {
		case 'Available':

			$this->update_field('Supplier Product Status','In Use','no_history');
			$this->update_field('Supplier Product Available','Yes','no_history');


			$sql=sprintf("select `Supplier Product Part Key` from `Supplier Product Part Dimension` where `Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' ",
				$this->pid);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$product_part_key=$row['Supplier Product Part Key'];
				$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`='Yes' , `Supplier Product Part Valid To`=%s where  `Supplier Product Part Key`=%d ",
					gmdate("Y-m-d H:i:s"),
					$product_part_key

				);
				print $sql;
				mysql_query($sql);
			}
			break;
		case 'NoAvailable':

			$this->update_field('Supplier Product Status','In Use','no_history');
			$this->update_field('Supplier Product Available','No','no_history');


			$sql=sprintf("select `Supplier Product Part Key` from `Supplier Product Part Dimension` where `Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' ",
				$this->pid);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$product_part_key=$row['Supplier Product Part Key'];
				$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`='No' , `Supplier Product Part Valid To`=%s where  `Supplier Product Part Key`=%d ",
					gmdate("Y-m-d H:i:s"),
					$product_part_key

				);
				print $sql;
				mysql_query($sql);
			}
			break;
		case 'Discontinued':

			$this->update_field('Supplier Product Status','Not In Use','no_history');
			$this->update_field('Supplier Product Available','No','no_history');
			$sql=sprintf("select `Supplier Product Part Key` from `Supplier Product Part Dimension` where `Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' ",$this->pid);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$product_part_key=$row['Supplier Product Part Key'];
				$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`='No',`Supplier Product Part Most Recent`='No' , `Supplier Product Part Valid To`=%s where  `Supplier Product Part Key`=%d ",
					gmdate("Y-m-d H:i:s"),
					$product_part_key

				);
				mysql_query($sql);
			}
			break;

		}

		$this->update_field('Supplier Product State',$value);

	}

	function update_field_switcher($field,$value,$options='') {

		switch ($field) {
		case('Supplier Product Tariff Code'):
			$this->update_tariff_code($value);

			break;
		case('Supplier Product State'):
			$this->update_state($value);

			break;
		case('Supplier Product Cost Per Case'):
			$this->update_cost($value);
			break;
		case('supplier_key'):
			$this->update_supplier_key($value);
			break;

		case('url'):
			$field="Supplier Product URL";


		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {

				if ($value!=$this->data[$field]) {

					$this->update_field($field,$value,$options);
				}
			}


		}


	}
	function change_current_key($new_current_key) {

		$sql=sprintf("select `SPH Case Cost` from `Supplier Product History Dimension` where  `Supplier Product ID`=%d and `SPH Key`=%d "
			,$this->pid
			,$new_current_key
		);
		//print $sql;
		$res=mysql_query($sql);
		$num_historic_records=mysql_num_rows($res);
		if ($num_historic_records==0) {
			$this->error=true;
			$this->msg.=';Can not change product current key because mre key is not associated with ID';
			return;
		}
		$row=mysql_fetch_array($res);

		$price=$row['SPH Case Cost'];


		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Cost Per Case`=%.2f,`Supplier Product Current Key`=%d  where `Supplier Product ID`=%d "
			,$price
			,$new_current_key
			,$this->pid
		);
		//print $sql;
		mysql_query($sql);
		$this->data['Supplier Product Cost Per Case']=sprintf("%.2f",$price);
		$this->data['Supplier Product Current Key']=$new_current_key;

		$this->id =$new_current_key;


	}

	function get_historic_keys() {
		$sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product ID`=%d ",
			$this->pid

		);
		// print $sql;
		$res=mysql_query($sql);
		$historic_keys=array();
		while ($row=mysql_fetch_array($res)) {
			$historic_keys[]=$row['SPH Key'];
		}
		return $historic_keys;
	}





	function update_up_today_sales() {
		$this->update_sales('All');
		$this->update_sales('Today');
		$this->update_sales('Week To Day');
		$this->update_sales('Month To Day');
		$this->update_sales('Year To Day');
	}

	function update_last_period_sales() {

		$this->update_sales('Yesterday');
		$this->update_sales('Last Week');
		$this->update_sales('Last Month');
	}

	function update_interval_sales() {
		$this->update_sales('3 Year');
		$this->update_sales('1 Year');
		$this->update_sales('6 Month');
		$this->update_sales('1 Quarter');
		$this->update_sales('1 Month');
		$this->update_sales('10 Day');
		$this->update_sales('1 Week');
	}



	function update_previous_years_data() {

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-1 year')),date('Y-01-01 00:00:00'));
		$this->data['Supplier Product 1 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-2 year')),date('Y-01-01 00:00:00',strtotime('-1 year')));
		$this->data['Supplier Product 2 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-3 year')),date('Y-01-01 00:00:00',strtotime('-2 year')));
		$this->data['Supplier Product 3 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-4 year')),date('Y-01-01 00:00:00',strtotime('-3 year')));
		$this->data['Supplier Product 4 Year Ago Sales Amount']=$sales_data['sold_amount'];


		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Year Ago Sales Amount`=%.2f, `Supplier Product 2 Year Ago Sales Amount`=%.2f,`Supplier Product 3 Year Ago Sales Amount`=%.2f, `Supplier Product 4 Year Ago Sales Amount`=%.2f where `Supplier Product Key`=%d ",

			$this->data["Supplier Product 1 Year Ago Sales Amount"],
			$this->data["Supplier Product 2 Year Ago Sales Amount"],
			$this->data["Supplier Product 3 Year Ago Sales Amount"],
			$this->data["Supplier Product 4 Year Ago Sales Amount"],

			$this->pid

		);

		mysql_query($sql);


	}


	function get_sales_data($from_date,$to_date) {

		$sales_data=array(
			'sold_amount'=>0,
			'sold'=>0,
			'dispatched'=>0,
			'required'=>0,
			'no_dispatched'=>0,

		);


		$sql=sprintf("select sum(`Amount In`) as sold_amount,sum(`Inventory Transaction Quantity`) as dispatched,sum(`Required`) as required,sum(`Given`) as given,sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,sum(-`Given`-`Inventory Transaction Quantity`) as sold from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Supplier Product ID`=%d %s %s" ,
			$this->pid,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		$result=mysql_query($sql);


		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sales_data['sold_amount']=$row['sold_amount'];
			$sales_data['sold']=$row['sold'];
			$sales_data['dispatched']=-1.0*$row['dispatched'];
			$sales_data['required']=$row['required'];
			$sales_data['no_dispatched']=$row['no_dispatched'];



		}

		return $sales_data;
	}


	function update_sales($interval) {


		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);

		//print "$interval : $db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb\n";

		setlocale(LC_ALL, 'en_GB');


		$this->data["Supplier Product $db_interval Acc Parts Profit"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Profit After Storing"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Cost"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Sold Amount"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Bought"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Required"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Dispatched"]=0;
		$this->data["Supplier Product $db_interval Acc Parts No Dispatched"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Sold"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Lost"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Broken"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Returned"]=0;
		$this->data["Supplier Product $db_interval Acc Parts Margin"]=0;



		$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                         from `Inventory Transaction Fact` ITF  where `Supplier Product ID`=%d %s %s" ,
			$this->pid,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Supplier Product $db_interval Acc Parts Profit"]=$row['profit'];
			$this->data["Supplier Product $db_interval Acc Parts Profit After Storing"]=$this->data["Supplier Product $db_interval Acc Parts Profit"]-$row['cost_storing'];

		}

		$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought  from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'In'  and `Supplier Product ID`=%d %s %s" ,
			$this->pid,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier Product $db_interval Acc Parts Cost"]=$row['cost'];
			$this->data["Supplier Product $db_interval Acc Parts Bought"]=$row['bought'];

		}

		$sql=sprintf("select sum(`Amount In`) as sold_amount,sum(`Inventory Transaction Quantity`) as dispatched,sum(`Required`) as required,sum(`Given`) as given,sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,sum(-`Given`-`Inventory Transaction Quantity`) as sold from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Supplier Product ID`=%d %s %s" ,
			$this->pid,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		// print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier Product $db_interval Acc Parts Sold Amount"]=$row['sold_amount'];
			$this->data["Supplier Product $db_interval Acc Parts Sold"]=$row['sold'];
			$this->data["Supplier Product $db_interval Acc Parts Dispatched"]=-1.0*$row['dispatched'];
			$this->data["Supplier Product $db_interval Acc Parts Required"]=$row['required'];
			$this->data["Supplier Product $db_interval Acc Parts No Dispatched"]=$row['no_dispatched'];


		}

		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Supplier Product ID`=%d %s %s" ,
			$this->pid,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier Product $db_interval Acc Parts Broken"]=-1.*$row['broken'];

		}


		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Lost' and `Supplier Product ID`=%d %s %s" ,
			$this->pid,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier Product $db_interval Acc Parts Lost"]=-1.*$row['lost'];

		}

		if ($this->data["Supplier Product $db_interval Acc Parts Sold Amount"]!=0)
			$margin=$this->data["Supplier Product $db_interval Acc Parts Profit After Storing"]/$this->data["Supplier Product $db_interval Acc Parts Sold Amount"];
		else
			$margin=0;

		$this->data["Supplier Product $db_interval Acc Parts Margin"]=$margin;
		$sql=sprintf("update `Supplier Product Dimension` set
                         `Supplier Product $db_interval Acc Parts Profit`=%.2f,
                         `Supplier Product $db_interval Acc Parts Profit After Storing`=%.2f,
                         `Supplier Product $db_interval Acc Parts Cost`=%.2f,
                         `Supplier Product $db_interval Acc Parts Sold Amount`=%.2f,
                         `Supplier Product $db_interval Acc Parts Sold`=%f,
                         `Supplier Product $db_interval Acc Parts Dispatched`=%f,
                         `Supplier Product $db_interval Acc Parts Required`=%f,
                         `Supplier Product $db_interval Acc Parts No Dispatched`=%f,
                         `Supplier Product $db_interval Acc Parts Broken`=%f,
                         `Supplier Product $db_interval Acc Parts Lost`=%f,
                         `Supplier Product $db_interval Acc Parts Returned`=%f,
                         `Supplier Product $db_interval Acc Parts Margin`=%f
                         where `Supplier Product ID`=%d ",
			$this->data["Supplier Product $db_interval Acc Parts Profit"],
			$this->data["Supplier Product $db_interval Acc Parts Profit After Storing"],
			$this->data["Supplier Product $db_interval Acc Parts Cost"],
			$this->data["Supplier Product $db_interval Acc Parts Sold Amount"],
			$this->data["Supplier Product $db_interval Acc Parts Sold"],
			$this->data["Supplier Product $db_interval Acc Parts Dispatched"],
			$this->data["Supplier Product $db_interval Acc Parts Required"],
			$this->data["Supplier Product $db_interval Acc Parts No Dispatched"],
			$this->data["Supplier Product $db_interval Acc Parts Broken"],
			$this->data["Supplier Product $db_interval Acc Parts Lost"],
			$this->data["Supplier Product $db_interval Acc Parts Returned"],
			$this->data["Supplier Product $db_interval Acc Parts Margin"],
			$this->pid

		);

		//print "$sql\n";

		mysql_query($sql);



		if ($from_date_1yb) {


			$this->data["Supplier Product $db_interval Acc 1YB Parts Profit"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Profit After Storing"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Cost"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Sold Amount"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Bought"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Required"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Dispatched"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts No Dispatched"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Sold"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Lost"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Broken"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Returned"]=0;
			$this->data["Supplier Product $db_interval Acc 1YB Parts Margin"]=0;



			$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                         from `Inventory Transaction Fact` ITF  where `Supplier Product ID`=%d %s %s" ,
				$this->pid,
				($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

				($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Supplier Product $db_interval Acc 1YB Parts Profit"]=$row['profit'];
				$this->data["Supplier Product $db_interval Acc 1YB Parts Profit After Storing"]=$this->data["Supplier Product $db_interval Acc 1YB Parts Profit"]-$row['cost_storing'];

			}

			$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'In'  and `Supplier Product ID`=%d %s %s" ,
				$this->pid,
				($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

				($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier Product $db_interval Acc 1YB Parts Cost"]=$row['cost'];
				$this->data["Supplier Product $db_interval Acc 1YB Parts Bought"]=$row['bought'];

			}

			$sql=sprintf("select sum(`Amount In`) as sold_amount,
                         sum(`Inventory Transaction Quantity`) as dispatched,
                         sum(`Required`) as required,
                         sum(`Given`) as given,
                         sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                         sum(-`Given`-`Inventory Transaction Quantity`) as sold
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Supplier Product ID`=%d %s %s" ,
				$this->pid,
				($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

				($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier Product $db_interval Acc 1YB Parts Sold Amount"]=$row['sold_amount'];
				$this->data["Supplier Product $db_interval Acc 1YB Parts Sold"]=$row['sold'];
				$this->data["Supplier Product $db_interval Acc 1YB Parts Dispatched"]=-1.0*$row['dispatched'];
				$this->data["Supplier Product $db_interval Acc 1YB Parts Required"]=$row['required'];
				$this->data["Supplier Product $db_interval Acc 1YB Parts No Dispatched"]=$row['no_dispatched'];


			}

			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Supplier Product ID`=%d %s %s" ,
				$this->pid,
				($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

				($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier Product $db_interval Acc 1YB Parts Broken"]=-1.*$row['broken'];

			}


			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Lost' and `Supplier Product ID`=%d %s %s" ,
				$this->pid,
				($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

				($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier Product $db_interval Acc 1YB Parts Lost"]=-1.*$row['lost'];

			}

			if ($this->data["Supplier Product $db_interval Acc 1YB Parts Sold Amount"]!=0)
				$margin=$this->data["Supplier Product $db_interval Acc 1YB Parts Profit After Storing"]/$this->data["Supplier Product $db_interval Acc 1YB Parts Sold Amount"];
			else
				$margin=0;

			$this->data["Supplier Product $db_interval Acc 1YB Parts Margin"]=$margin;
			$sql=sprintf("update `Supplier Product Dimension` set
                         `Supplier Product $db_interval Acc 1YB Parts Profit`=%.2f,
                         `Supplier Product $db_interval Acc 1YB Parts Profit After Storing`=%.2f,
                         `Supplier Product $db_interval Acc 1YB Parts Cost`=%.2f,
                         `Supplier Product $db_interval Acc 1YB Parts Sold Amount`=%.2f,
                         `Supplier Product $db_interval Acc 1YB Parts Sold`=%f,
                         `Supplier Product $db_interval Acc 1YB Parts Dispatched`=%f,
                         `Supplier Product $db_interval Acc 1YB Parts Required`=%f,
                         `Supplier Product $db_interval Acc 1YB Parts No Dispatched`=%f,
                         `Supplier Product $db_interval Acc 1YB Parts Broken`=%f,
                         `Supplier Product $db_interval Acc 1YB Parts Lost`=%f,
                         `Supplier Product $db_interval Acc 1YB Parts Returned`=%f,
                         `Supplier Product $db_interval Acc 1YB Parts Margin`=%f
                         where `Supplier Product ID`=%d ",
				$this->data["Supplier Product $db_interval Acc 1YB Parts Profit"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Profit After Storing"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Cost"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Sold Amount"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Sold"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Dispatched"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Required"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts No Dispatched"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Broken"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Lost"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Returned"],
				$this->data["Supplier Product $db_interval Acc 1YB Parts Margin"],
				$this->pid

			);

			mysql_query($sql);

			// print "$sql\n";

		}


	}









	function upload_current_key_sales() {
		$this->load('parts');
		// total
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$storing=0;
		$cost=0;


		$sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where   `Inventory Transaction Type` like 'Sale' and `Supplier Product ID`=%d  ",$this->id);
		//print_r($this->parts_sku);
		//   print "$sql\n";
		//exit;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=-$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=-$row['qty']-$row['given'];
			$storing=$row['storing'];
		}
		$abs_profit=$amount_in+$value;
		$profit_sold=$amount_in+$value-$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=($value-$value_free)/$amount_in;
		$profit_sold_after_storing=$profit_sold-$storing;


		$sql=sprintf("update `Supplier Product History Dimension` set `SPH Total Cost`=%.2f,`SPH Total Parts Required`=%f ,`SPH Total Parts Provided`=%f,`SPH Total Parts Used`=%f ,`SPH Total Sold Amount`=%f ,`SPH Total Parts Profit`=%f ,`SPH Total Parts Profit After Storing`=%f  where `SPH Key`=%d "
			,$cost
			,$required
			,$provided
			,$given+$provided
			,$amount_in
			,$profit_sold
			,$profit_sold_after_storing
			,$this->id


		);

		if (!mysql_query($sql))
			exit("*** error con not uopdate product part when loading sales");





		// 1 year



		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where  `Inventory Transaction Type` like 'Sale' and `Supplier Product ID`=%d and `Date`>=%s    ",$this->id,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=-$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=-$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in+$value;
		$profit_sold=$amount_in+$value-$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=($value-$value_free)/$amount_in;
		$profit_sold_after_storing=$profit_sold;

		$cost=0;

		$sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Year Acc Cost`=%.2f,`SPH 1 Year Acc Parts Required`=%f ,`SPH 1 Year Acc Parts Provided`=%f,`SPH 1 Year Acc Parts Used`=%f ,`SPH 1 Year Acc Sold Amount`=%f ,`SPH 1 Year Acc Parts Profit`=%f  where `SPH Key`=%d "
			,$cost=0
			,$required
			,$provided
			,$given+$provided
			,$amount_in
			,$profit_sold
			,$this->id

		);
		//    print "$sql\n";
		if (!mysql_query($sql))
			exit("*error con not uopdate product part when loading sales");




		//1 quarter


		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where  `Inventory Transaction Type` like 'Sale' and `Supplier Product ID`=%d   and `Date`<=%s and `Date`>=%s     ",$this->id,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
		//      print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=-$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=-$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in+$value;
		$profit_sold=$amount_in+$value-$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=($value-$value_free)/$amount_in;

		$cost=0;
		$sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Quarter Acc Cost`=%.2f,`SPH 1 Quarter Acc Parts Required`=%f ,`SPH 1 Quarter Acc Parts Provided`=%f,`SPH 1 Quarter Acc Parts Used`=%f ,`SPH 1 Quarter Acc Sold Amount`=%f ,`SPH 1 Quarter Acc Parts Profit`=%f  where `SPH Key`=%d "
			,$cost
			,$required
			,$provided
			,$given+$provided
			,$amount_in
			,$profit_sold
			,$this->id);
		//                  print "$sql\n";
		if (!mysql_query($sql))
			exit("error con not uopdate product part when loading sales 1 q");






		//1 month




		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Sale'  and `Supplier Product ID`=%d and `Date`<=%s and `Date`>=%s   ",$this->id,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
		//      print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=-$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=-$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in+$value;
		$profit_sold=$amount_in+$value-$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=($value-$value_free)/$amount_in;

		$cost=0;
		$sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Month Acc Cost`=%.2f, `SPH 1 Month Acc Parts Required`=%f ,`SPH 1 Month Acc Parts Provided`=%f,`SPH 1 Month Acc Parts Used`=%f ,`SPH 1 Month Acc Sold Amount`=%f ,`SPH 1 Month Acc Parts Profit`=%f  where `SPH Key`=%d "
			,$cost
			,$required
			,$provided
			,$given+$provided
			,$amount_in
			,$profit_sold
			,$this->id);
		//                  print "$sql\n";
		if (!mysql_query($sql))
			exit("error con not uopdate product part when loading sales");




		// 1 week



		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Sale'  and `Supplier Product ID`=%d  and `Date`<=%s and `Date`>=%s    ",$this->id,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
		//      print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=-$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=-$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in+$value;
		$profit_sold=$amount_in+$value-$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=($value-$value_free)/$amount_in;

		$cost=0;
		$sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Week Acc Cost`=%.2f,`SPH 1 Week Acc Parts Required`=%f ,`SPH 1 Week Acc Parts Provided`=%f,`SPH 1 Week Acc Parts Used`=%f ,`SPH 1 Week Acc Sold Amount`=%f ,`SPH 1 Week Acc Parts Profit`=%f  where `SPH Key`=%d "
			,$cost
			,$required
			,$provided
			,$given+$provided
			,$amount_in
			,$profit_sold
			,$this->id);
		//                  print "$sql\n";
		if (!mysql_query($sql))
			exit("error con not uopdate product part when loading sales");






	}







	function get_formated_unit() {

		switch ($this->data['Supplier Product Unit Type']) {
		case('ea'):
			return _('Item');
			break;
		default:
			return $this->data['Supplier Product Unit Type'];

		}

	}
	function get_formated_price($locale='') {

		$data=array(
			'Product Price'=>$this->data['SPH Case Cost'],
			'Product Units Per Case'=>$this->data['SPH Units Per Case'],
			'Product Currency'=>$this->get('Supplier Product Currency'),
			'Product Unit Type'=>$this->data['Supplier Product Unit Type'],


			'locale'=>$locale);

		return formated_price($data);
	}
	function get_formated_price_per_case($locale='') {

		$data=array(
			'Product Price'=>$this->data['SPH Case Cost']*$this->data['SPH Units Per Case'],
			'Product Units Per Case'=>1,
			'Product Currency'=>$this->get('Supplier Product Currency'),
			'Product Unit Type'=>_('Case'),

			'Label'=>'',


			'locale'=>$locale);

		return formated_price($data);
	}
	function get_formated_price_per_unit($locale='') {

		$data=array(
			'Product Price'=>$this->data['SPH Case Cost'],
			'Product Units Per Case'=>$this->data['SPH Units Per Case'],
			'Product Currency'=>$this->get('Supplier Product Currency'),
			'Product Unit Type'=>$this->data['Supplier Product Unit Type'],

			'Label'=>'',


			'locale'=>$locale);

		return formated_price_per_unit($data);
	}
	function units_convertion_factor($unit_from,$unit_to=false) {
		return 1;
	}
	function get_part_locations() {

	}


	function new_current_part_list($header_data,$list) {

		$product_part_key=$this->find_product_part_list($list);
		if ($product_part_key) {
			$this->update_product_part_list($product_part_key,$header_data,$list);
		} else {
			$product_part_key=$this->create_product_part_list($header_data,$list);
		}
		$this->set_part_list_as_current($product_part_key);

	}
	function create_product_part_list($header_data,$list) {
		$product_part_key=0;
		$_base_list_data=array(
			'Part SKU'=>'',
			'Supplier Product Units Per Part'=>''
		);
		$_base_data=array(
			'Supplier Product ID'=>$this->pid,
			'Supplier Product Historic Key'=>$this->id,
			'Supplier Product Part Type'=>'Simple',
			'Supplier Product Part Metadata'=>'',
			'Supplier Product Part Valid From'=>gmdate('Y-m-d H:i:s'),
			'Supplier Product Part Valid To'=>gmdate('Y-m-d H:i:s'),
			'Supplier Product Part Most Recent'=>'No',
			'Supplier Product Part In Use'=>'No'

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
			if ($key=='Supplier Product Part Metadata' )
				$values.=prepare_mysql($value,false).',';
			else
				$values.=prepare_mysql($value).',';
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Supplier Product Part Dimension` %s %s",$keys,$values);
		if (mysql_query($sql)) {
			$product_part_key=mysql_insert_id();
			if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

			$this->new_value=array('Supplier Product Part Key'=>$product_part_key);
			$this->updated=true;
			$this->new_part_list=true;
			$this->new_part_list_key=$product_part_key;

			foreach ($list as $data) {
				$items_base_data=$_base_list_data;
				foreach ($data as $key=>$value) {
					if (array_key_exists($key,$items_base_data))
						$items_base_data[$key]=_trim($value);
				}
				$items_base_data['Supplier Product Part Key']=$product_part_key;
				$keys='(';
				$values='values(';
				foreach ($items_base_data as $key=>$value) {
					$keys.="`$key`,";

					$values.=prepare_mysql($value).',';
				}
				$keys=preg_replace('/,$/',')',$keys);
				$values=preg_replace('/,$/',')',$values);
				$sql=sprintf("insert into `Supplier Product Part List` %s %s",$keys,$values);
				mysql_query($sql);
				if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
			}
		}
		return $product_part_key;
	}
	function set_part_list_as_current($product_part_key) {
		$current_part_key=$this->get_current_part_key();
		if ($current_part_key!=$product_part_key) {
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid To`=%s where `Supplier Product Part Key`=%d  ",prepare_mysql(gmdate('Y-m-d H:i:s')),$current_part_key);
			mysql_query($sql);
			$sql=sprintf("update `Supplier Product Part List` set `Supplier Product Part Most Recent`='No' where `Supplier Product ID`=%d  ",$this->pid);
			mysql_query($sql);
			$sql=sprintf("update `Supplier Product Part List` set `Supplier Product Part Most Recent`='Yes' where `Supplier Product Part Key`=%d  ",$product_part_key);
			mysql_query($sql);
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='No' where `Supplier Product ID`=%d  ",$this->pid);
			mysql_query($sql);
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='Yes' ,`Supplier Product Part Valid To`=NULL  where `Supplier Product Part Key`=%d  ",$product_part_key);
			mysql_query($sql);
		}

		foreach ($this->get_parts_objects() as $part) {

			$part->update_estimated_future_cost();
		}
		//  exit;

	}
	function update_product_part_list($product_part_key,$header_data,$list) {

		$this->new_value=array();

		$old_data=$this->get_product_part_dimension_data($product_part_key);
		$old_items_data=$this->get_product_part_list_data($product_part_key);

		if ($old_data['Supplier Product Part Metadata']!=$header_data['Supplier Product Part Metadata']) {
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Metadata`=%s where `Supplier Product Part Key`=%d"
				,prepare_mysql($header_data['Supplier Product Part Metadata'])
				,$product_part_key
			);
			mysql_query($sql);
			$this->updated=true;
			$this->part_list_updated=true;

			$this->new_value['Supplier Product Part Metadata']=$header_data['Supplier Product Part Metadata'];
		}


	}
	function get_product_part_dimension_data($product_part_key) {
		$sql=sprintf("select * from `Supplier Product Part Dimension` where `Supplier Product Part Key`=%d  ",$product_part_key);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row;
		} else
			return false;
	}
	function get_product_part_list_data($product_part_key) {
		$data=array();
		$sql=sprintf("select * from `Supplier Product Part List` where `Supplier Product Part Key`=%d  ",$product_part_key);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$data[$row['Part SKU']]=$row;
		}
		return $data;
	}
	function get_current_part_key() {
		$product_part_key=0;
		$sql=sprintf("select `Supplier Product Part Key` from `Supplier Product Part Dimension` where `Supplier Product ID`=%d and `Supplier Product Part Most Recent`='Yes' ",$this->pid);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$product_part_key=$row['Supplier Product Part Key'];

		}
		return $product_part_key;
	}
	function find_product_part_list($list) {

		$this_list_num_parts=count($list);
		$good_product_parts=array();
		$found_product_parts=array();

		foreach ($list as $key=>$value) {

			$sql=sprintf("select PPD.`Supplier Product Part Key` from  `Supplier Product Part Dimension`  PPD  left join  `Supplier Product Part List` PPL on (PPL.`Supplier Product Part Key`=PPD.`Supplier Product Part Key`)where `Supplier Product ID`=%d and `Part SKU`=%d  and `Supplier Product Units Per Part`=%f and `Supplier Product Part Type`=%s   ",
				$this->pid,
				$value['Part SKU'],
				$value['Supplier Product Units Per Part'],
				prepare_mysql($value['Supplier Product Part Type'])
			);

			$res=mysql_query($sql);

			$found_list[$value['Part SKU']]=array();
			while ($row=mysql_fetch_assoc($res)) {
				$found_list[$value['Part SKU']][$row['Supplier Product Part Key']]=$row['Supplier Product Part Key'];
				$found_product_parts[$row['Supplier Product Part Key']]=$row['Supplier Product Part Key'];
			}
		}

		foreach ($found_list as $sku=>$found_data) {
			if (count($found_data)==0) {
				return 0;
			}
		}

		foreach ($found_product_parts as $product_part_key) {
			$sql=sprintf("select count(*) as num from  `Supplier Product Part List` where `Supplier Product Part Key`=%d",$product_part_key);
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
			print("Debug this part list is duplicated (SP)\n");
			return array_pop($good_product_parts);

		}

	}
	function new_historic_part_list($header_data,$list) {
		//print_r($header_data);
		//print_r($list);
		$product_part_key=$this->find_product_part_list($list);
		if ($product_part_key) {
			$this->update_product_part_list($product_part_key,$header_data,$list);
			$this->update_product_part_list_historic_dates($product_part_key,$header_data['Supplier Product Part Valid From'],$header_data['Supplier Product Part Valid To']);

		} else {
			$product_part_key=$this->create_product_part_list($header_data,$list);
		}
	}

	function update_product_part_list_historic_dates($product_part_key,$date1,$date2) {
		$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid From`=%s where `Supplier Product Part Key`=%d and (`Supplier Product Part Valid From` is null or `Supplier Product Part Valid From`>%s)"
			,prepare_mysql($date1)
			,$product_part_key
			,prepare_mysql($date1)
		);
		mysql_query($sql);
		//print "$sql\n";
		$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid To`=%s where `Supplier Product Part Key`=%d and (`Supplier Product Part Valid To` is null or `Supplier Product Part Valid To`<%s)"
			,prepare_mysql($date2)
			,$product_part_key
			,prepare_mysql($date2)
		);
		mysql_query($sql);
	}


	function update_sold_as() {
		$used_in_products='';
		$raw_used_in_products='';

		$part_skus=$this->get_part_skus();
		if (count($part_skus)==0)
			$part_skus=0;
		else
			$part_skus=join(',',$part_skus);

		$sql=sprintf("select `Store Code`,PD.`Product ID`,`Product Code` from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` PD on (PD.`Product ID`=PPD.`Product ID`) left join `Store Dimension`  on (PD.`Product Store Key`=`Store Key`)  where PPL.`Part SKU` in (%s)  order by `Product Code`,`Store Code`",$part_skus);
		$result=mysql_query($sql);
		// print "$sql\n";
		$used_in=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			if (!array_key_exists($row['Product Code'],$used_in))
				$used_in[$row['Product Code']]=array();
			if (!array_key_exists($row['Store Code'],$used_in[$row['Product Code']]))
				$used_in[$row['Product Code']][$row['Store Code']]=array();
			$used_in[$row['Product Code']][$row['Store Code']][$row['Product ID']]=1;

		}
		// print_r($used_in);
		foreach ($used_in as $code=>$store_data) {
			$raw_used_in_products.=' '.$code;
			$used_in_products.=sprintf(', <a href="product.php?code=%s">%s</a>',$code,$code);
			$used_in_products_2='';
			foreach ($store_data as $store_code=>$product_id_data) {
				foreach ($product_id_data as $product_id=>$tmp) {
					$used_in_products_2.=sprintf(',<a href="product.php?pid=%d">%s</a>',$product_id,$store_code);
				}
			}
			$used_in_products_2=preg_replace('/^,/','',$used_in_products_2);
			$used_in_products.=" ($used_in_products_2)";

		}

		//$used_in_products.=sprintf(', <a href="product.php?code=%s">%s</a>',$row['Product Code'],$row['Product Code']);
		//$raw_used_in_products=' '.$row['Product Code'];

		$used_in_products=preg_replace('/^, /','',$used_in_products);
		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product XHTML Sold As`=%s ,`Supplier Product Sold As`=%s  where `Supplier Product ID`=%d",
			prepare_mysql(_trim($used_in_products)),
			prepare_mysql(_trim($raw_used_in_products)),
			$this->pid);

		mysql_query($sql);
		//print "$sql\n";
	}

	function update_store_as() {
		$used_in_products='';



		$used_in_parts='';
		$sql=sprintf("select PD.`Part SKU` from `Supplier Product Part List` SPPL left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Part Dimension` PD on (SPPL.`Part SKU`=PD.`Part SKU`) where `Supplier Product ID`=%d  and `Supplier Product Part Most Recent`='Yes' group by PD.`Part SKU`;",
			$this->pid
		);
		$result=mysql_query($sql);
		$num_parts=0;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$used_in_parts.=sprintf(', <a href="part.php?sku=%d">%s</a>',$row['Part SKU'],$row['Part SKU']);
			$num_parts++;
		}
		$used_in_parts=preg_replace('/^, /','',$used_in_parts);

		if ($num_parts==0)
			$used_in_parts='';
		else if ($num_parts==1)
				$used_in_parts='SKU:'.$used_in_parts;
			else
				$used_in_parts='SKUs:'.$used_in_parts;

			$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product XHTML Store As`=%s where  `Supplier Product ID`=%d"
				,prepare_mysql($used_in_parts)

				,$this->pid
			);
		//print "$sql\n";
		if (!mysql_query($sql))
			exit("$sql error can not update used in insuppiler product \n");

		//exit;


	}


	function remove_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Supplier Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("delete from `Image Bridge` where `Subject Type`='Supplier Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
			mysql_query($sql);
			$this->updated=true;


			$number_images=$this->get_number_of_images();

			if ($number_images==0) {
				$main_image_src='art/nopic.png';
				$main_image_key=0;
				$this->data['Supplier Product Main Image']=$main_image_src;
				$this->data['Supplier Product Main Image Key']=$main_image_key;
				$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Main Image`=%s ,`Supplier Product Main Image Key`=%d where `Supplier Product ID`=%d",
					prepare_mysql($main_image_src),
					$main_image_key,
					$this->pid
				);

				mysql_query($sql);
			}else if ($row['Is Principal']=='Yes') {

					$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Supplier Product' and `Subject Key`=%d  ",$this->pid);
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



		$sql=sprintf("update `Image Bridge` set `Image Caption`=%s where  `Subject Type`='Supplier Product' and `Subject Key`=%d  and `Image Key`=%d"
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

		return $this->data['Supplier Product Main Image Key'];
	}
	function update_main_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Supplier Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
		$res=mysql_query($sql);
		if (!mysql_num_rows($res)) {
			$this->error=true;
			$this->msg='image not associated';
		}

		$sql=sprintf("update `Image Bridge` set `Is Principal`='No' where `Subject Type`='Supplier Product' and `Subject Key`=%d  ",$this->pid);
		mysql_query($sql);
		$sql=sprintf("update `Image Bridge` set `Is Principal`='Yes' where `Subject Type`='Supplier Product' and `Subject Key`=%d  and `Image Key`=%d",$this->pid,$image_key);
		mysql_query($sql);


		$main_image_src='image.php?id='.$image_key.'&size=small';
		$main_image_key=$image_key;

		$this->data['Supplier Product Main Image']=$main_image_src;
		$this->data['Supplier Product Main Image Key']=$main_image_key;
		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Main Image`=%s ,`Supplier Product Main Image Key`=%d where `Supplier Product ID`=%d",
			prepare_mysql($main_image_src),
			$main_image_key,
			$this->pid
		);

		mysql_query($sql);

		$this->updated=true;

	}
	function get_number_of_images() {
		$number_of_images=0;
		$sql=sprintf("select count(*) as num from `Image Bridge` where `Subject Type`='Supplier Product' and `Subject Key`=%d ",$this->pid);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_of_images=$row['num'];
		}
		return $number_of_images;
	}

	function update_availability() {

	}

	function update_supplier_key($key) {


		if (!is_numeric($key)) {
			$this->error=true;
			$this->msg='Key is not a number';
			return;
		}

		if ($key!=$this->data['Supplier Key']) {




			$new_supplier=new Supplier($key);
			if (!$new_supplier->id) {
				$this->error=true;
				$this->msg='supplier not found';
				return;
			}



			$old_supplier=new Supplier($this->data['Supplier Key']);



			$sql=sprintf("update `Supplier Product Dimension` set `Supplier Key`=%d, `Supplier Code`=%s, `Supplier Name`=%s where `Supplier Product ID`=%d",
				$new_supplier->id,
				prepare_mysql($new_supplier->data['Supplier Code']),
				prepare_mysql($new_supplier->data['Supplier Name']),
				$this->pid);


			mysql_query($sql);

			$old_supplier->update_products_info();
			$new_supplier->update_products_info();


			$this->get_data('pid',$this->pid);

			$this->new_value=$key;


			$this->updated=true;


			$details='<table>
				<tr><td style="width:120px">'._('Time').':</td><td>'.strftime("%a %e %b %Y %H:%M:%S %Z").'</td></tr>
				<tr><td>'._('User').':</td><td>'.$this->editor['Author Alias'].'</td></tr>

				<tr><td>'._('Action').':</td><td>'._('Family changed').'</td></tr>
				<tr><td>'._('Old value').':</td><td><a href="family.php?id='.$old_supplier->id.'">'.$old_supplier->get('Supplier Code').'</a></td></tr>
				<tr><td>'._('New value').':</td><td><a href="family.php?id='.$new_supplier->id.'">'.$new_supplier->get('Supplier Code').'</a></td></tr>


				</table>';



			$this->add_history(array(

					'History Details'=>$details,

					'History Abstract'=>_('Product moved to supplier').": ".$this->get('Supplier Code')
				));


		}

		$supplier_branch='<a href="supplier.php?id='.$this->data['Supplier Key'].'" title="'.$this->data['Supplier Name'].'">'.$this->get('Supplier Code').'</a>';

		$this->new_data=array(
			'code'=>$this->data['Supplier Code'] ,
			'name'=>$this->data['Supplier Name'] ,
			'key'=>$this->data['Supplier Key']  ,
			'supplier_branch'=>$supplier_branch

		);

	}


	function update_tariff_code_valid() {

		$tariff_code=$this->data['Supplier Product Tariff Code'];
		if (strlen($tariff_code)==10  ) {
			$tariff_code=substr($tariff_code,0, -2);
		}


		$sql=sprintf("select count(*) as num  from kbase.`Commodity Code Dimension` where `Commodity Code`=%s ",
			prepare_mysql($tariff_code)
		);
		$res=mysql_query($sql);
		$valid='No';
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0) {
				$valid='Yes';
			}
		}

		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Tariff Code Valid`=%s where `Supplier Product ID`=%d",prepare_mysql($valid),$this->pid);
		mysql_query($sql);

	}



	function update_tariff_code($value,$options='') {

		$this->update_field('Supplier Product Tariff Code',$value,$options);
		$this->update_tariff_code_valid();


	}

	function get_MSDS_attachment_key() {



		if (!$this->data['Supplier Product MSDS Attachment Bridge Key']) {
			return 0;
		}
		$attachment_key=0;
		$sql=sprintf("select `Attachment Key` from `Attachment Bridge` where `Subject`='Supplier Product MSDS' and `Subject Key`=%d ",
			$this->id
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$attachment_key=$row['Attachment Key'];
		}
		return $attachment_key;

	}

	function delete_MSDS_attachment() {

		//print_r($this->data);

		if ($this->data['Supplier Product MSDS Attachment Bridge Key']=='') {
			$this->msg=_('No file is set up as MSDS');
			return;
		}

		$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d",
			$this->data['Supplier Product MSDS Attachment Bridge Key']
		);
		mysql_query($sql);
		//print "$sql  xx\n";

		$attach=new Attachment($this->get_MSDS_attachment_key());
		$attach->delete();
		$attach_info=$this->data['Supplier Product MSDS Attachment XHTML Info'];
		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product MSDS Attachment Bridge Key`=0, `Supplier Product MSDS Attachment XHTML Info`='' where `Supplier Product ID`=%d ",
			$this->pid

		);
		mysql_query($sql);
		$this->data['Supplier Product MSDS Attachment XHTML Info']='';
		$this->data['Supplier Product MSDS Attachment Bridge Key']='';
		$history_data=array(
			'History Abstract'=>_('MSDS Attachment deleted').'.',
			'History Details'=>$attach_info,
			'Action'=>'edited',
			'Direct Object'=>'Attachment',
			'Prepostion'=>'',
			'Indirect Object'=>$this->table_name,
			'Indirect Object Key'=>$this->pid
		);

		$history_key=$this->add_subject_history($history_data,true,'No','Changes');
	}

	function update_MSDS_attachment($attach,$filename,$caption) {

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


		if ($attach->id==$this->get_MSDS_attachment_key()) {
			$this->msg=_('This file already set up as MSDS');
			return;
		}

		if ($this->data['Supplier Product MSDS Attachment Bridge Key']) {
			$this->delete_MSDS_attachment();

		}



		$sql=sprintf("insert into `Attachment Bridge` (`Attachment Key`,`Subject`,`Subject Key`,`Attachment File Original Name`,`Attachment Caption`) values (%d,'Supplier Product MSDS',%d,%s,%s)",
			$attach->id,
			$this->pid,
			prepare_mysql($filename),
			prepare_mysql($caption)
		);
		mysql_query($sql);
		//print $sql;

		$attach_bridge_key=mysql_insert_id();
		$attach_info=$attach->get_abstract($filename,$caption,$attach_bridge_key);

		if ($this->data['Supplier Product MSDS Attachment Bridge Key']) {
			$history_data=array(
				'History Abstract'=>_('MSDS Attachment replaced').'. '.$attach_info,
				'History Details'=>$attach->get_details(),
				'Action'=>'edited',
				'Direct Object'=>'Attachment',
				'Prepostion'=>'',
				'Indirect Object'=>$this->table_name,
				'Indirect Object Key'=>$this->pid
			);

		}else {
			$history_data=array(
				'History Abstract'=>_('MSDS Attachment uploaded').'; '.$attach_info,
				'History Details'=>$attach->get_details(),
				'Action'=>'associated',
				'Direct Object'=>'Attachment',
				'Prepostion'=>'',
				'Indirect Object'=>$this->table_name,
				'Indirect Object Key'=>$this->pid
			);

		}


		$history_key=$this->add_subject_history($history_data,true,'No','Changes');

		$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product MSDS Attachment Bridge Key`=%d, `Supplier Product MSDS Attachment XHTML Info`=%s where `Supplier Product ID`=%d ",
			$attach_bridge_key,
			prepare_mysql($attach_info),
			$this->pid

		);

		mysql_query($sql);
		$this->data['Supplier Product MSDS Attachment Bridge Key']=$attach_bridge_key;
		$this->data['Supplier Product MSDS Attachment XHTML Info']=$attach_info;


		$this->updated=true;





	}



}
