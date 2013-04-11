<?php
/*
 File: Part.php

 This file contains the Part Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'class.Product.php';

class part extends DB_Table {


	private $current_locations_loaded=false;
	public $sku=false;

	function __construct($a1,$a2=false) {

		$this->table_name='Part';
		$this->ignore_fields=array(
			'Part Key'
		);

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
				$this->msg=$this->create($a2);

			} else
			$this->get_data($a1,$a2);

	}




	function get_data($tipo,$tag) {
		if ($tipo=='id' or $tipo=='sku')
			$sql=sprintf("select * from `Part Dimension` where `Part SKU`=%d ",$tag);
		else
			return;

		$result=mysql_query($sql);
		if (($this->data=mysql_fetch_array($result, MYSQL_ASSOC))) {
			$this->id=$this->data['Part SKU'];
			$this->sku=$this->data['Part SKU'];
		}


	}

	function create($data) {
		// print_r($data);
		$base_data=array(
			'part status'=>'In Use',
			'part xhtml currently used in'=>'',
			'part xhtml currently supplied by'=>'',
			'part xhtml description'=>'',
			'part unit description'=>'',
			'part package size metadata'=>'',
			'part package volume'=>'',
			'part package minimun orthogonal volume'=>'',
			'part gross weight'=>'',
			'part valid from'=>'',
			'part valid to'=>'',
		);
		foreach ($data as $key=>$value) {
			if (isset( $base_data[strtolower($key)]) )
				$base_data[strtolower($key)]=_trim($value);
		}

		//    if(!$this->valid_sku($base_data['part sku']) ){

		// }


		if ($base_data['part xhtml description']=='') {
			$base_data['part xhtml description']=strip_tags($base_data['part xhtml description']);
		}

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);

		$sql=sprintf("insert into `Part Dimension` %s %s",$keys,$values);
		//print $sql;
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->sku =$this->id ;
			$this->new=true;

			$warehouse_key=1;
			if (array_key_exists('warehouse key', $data) and is_numeric($data['warehouse key'])  and  $data['warehouse key']>0) {
				$warehouse_key=$data['warehouse key'];
			}

			$sql=sprintf("insert into `Part Warehouse Bridge` values (%d,%d)",$this->sku,$warehouse_key);
			//print "$sql\n";
			mysql_query($sql);
			$this->get_data('id',$this->id);
			$data_for_history=array(
				'Action'=>'created',
				'History Abstract'=>_('Part Created'),
				'History Details'=>_('Part')." ".$this->get_sku()." (".$this->data['Part Unit Description'].")"._('Created')
			);

			$this->update_main_state();
		} else {
			print "Error Part can not be created $sql\n";
			$this->msg='Error Part can not be created';
			exit;
		}

	}

	function update_custom_fields($id, $value) {
		$this->update(array($id=>$value));
	}

	function update_main_state() {
		if ($this->data['Part Status']=='In Use') {

			if ($this->data['Part Available']=='Yes') {
				$this->data['Part Main State']='Keeping';
			} else {
				$this->data['Part Main State']='LastStock';
			}

		}
		else {
			if ($this->data['Part Available']=='Yes') {
				$this->data['Part Main State']='NotKeeping';
			} else {
				$this->data['Part Main State']='Discontinued';
			}
		}

		$sql=sprintf("update `Part Dimension`  set `Part Main State`=%s where  `Part SKU`=%d   "
			,prepare_mysql($this->data['Part Main State'])
			,$this->id
		);
		mysql_query($sql);
		//print "$sql\n";

	}

	function update_status($value,$options='') {

		$this->update_field('Part Status',$value,$options);

		if ($value=='In Use') {


		} elseif ($value=='Not In Use') {

			$locations=$this->get_location_keys();



			foreach ($locations as $location_key) {
				$part_location=new PartLocation($this->sku.'_'.$location_key);

				$part_location->disassociate();

			}

		}
		$this->update_main_state();


		$sql=sprintf("select `Category Key` from `Category Bridge` where `Subject`='Part' and `Subject Key`=%d",$this->sku);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$category=new Category($row['Category Key']);
			$category->update_part_category_status();
		}


		$products=$this->get_product_ids();
		foreach ($products as $product_pid) {
			$product=new Product ('pid',$product_pid);
			$product->update_availability_type();

		}


	}

	function update_field_switcher($field,$value,$options='') {



		switch ($field) {
		case('Store Sticky Note'):
			$this->update_field_switcher('Sticky Note',$value);
			break;
		case('Sticky Note'):
			$this->update_field('Part '.$field,$value,'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;
		case('Part Status'):
			$this->update_status($value,$options);
			break;
		case('Part Tariff Code'):
			$this->update_tariff_code($value,$options);
			break;
		default:
			$base_data=$this->base_data();

			if (array_key_exists($field,$base_data)) {

				if ($value!=$this->data[$field]) {

					if ($field=='Part General Description' or $field=='Part Health And Safety')
						$options.=' nohistory';
					$this->update_field($field,$value,$options);




				}
			}
			elseif (preg_match('/^custom_field_part/i',$field)) {
				$this->update_field($field,$value,$options);
			}

		}




	}

	function update_tariff_code_valid() {

		$tariff_code=$this->data['Part Tariff Code'];
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

		$sql=sprintf("update `Part Dimension` set `Part Tariff Code Valid`=%s where `Part SKU`=%d",prepare_mysql($valid),$this->sku);
		mysql_query($sql);

	}


	function update_duty_rate($value,$options='') {

		$this->update_field('Part Duty Rate',$value,$options);
		$product_ids=$this->get_product_ids();

		foreach ($product_ids as $product_id) {
			$product=new Product('pid',$product_id);
			$product->update_field('Product Duty Rate',$value,$options);
		}
	}


	function update_tariff_code($value,$options='') {

		$this->update_field('Part Tariff Code',$value,$options);
		$this->update_tariff_code_valid();
		$product_ids=$this->get_product_ids();

		foreach ($product_ids as $product_id) {
			$product=new Product('pid',$product_id);
			$product->update_field('Product Tariff Code',$value,$options);
		}
	}


	function load($data_to_be_read,$args='') {
		switch ($data_to_be_read) {


		case('locations'):
			$this->load_locations($args);


			break;

		case('stock_data'):
			$astock=0;
			$avaue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where  `Part SKU`=%d and `Date`>=%s and `Date`<=%s group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  ));
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part Total Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part Total AVG Stock`=%s ,`Part Total AVG Stock Value`=%s,`Part Total Keeping Days`=%f ,`Part Total Out of Stock Days`=%f , `Part Total Unknown Stock Days`=%s, `Part Total GMROI`=%s where `Part SKU`=%d"
				,$astock
				,$avalue
				,$tdays
				,$outstock
				,$unknown
				,$gmroi
				,$this->id);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql  ** errot con not update part stock history all");

			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
			//print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Year Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Year Acc AVG Stock`=%s ,`Part 1 Year Acc AVG Stock Value`=%s,`Part 1 Year Acc Keeping Days`=%f ,`Part 1 Year Acc Out of Stock Days`=%f , `Part 1 Year Acc Unknown Stock Days`=%s, `Part 1 Year Acc GMROI`=%s where `Part SKU`=%d"
				,$astock
				,$avalue
				,$tdays
				,$outstock
				,$unknown
				,$gmroi
				,$this->id);
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql **  errot con not update part stock history yr aa");


			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Quarter Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc AVG Stock`=%s ,`Part 1 Quarter Acc AVG Stock Value`=%s,`Part 1 Quarter Acc Keeping Days`=%f ,`Part 1 Quarter Acc Out of Stock Days`=%f , `Part 1 Quarter Acc Unknown Stock Days`=%s, `Part 1 Quarter Acc GMROI`=%s where `Part SKU`=%d"
				,$astock
				,$avalue
				,$tdays
				,$outstock
				,$unknown
				,$gmroi
				,$this->id);
			//   print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql z errot con not update part stock history yr bb");

			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$astock=$astock/$days_ok;
				$avalue=$avalue/$days_ok;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Month Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Month Acc AVG Stock`=%s ,`Part 1 Month Acc AVG Stock Value`=%s,`Part 1 Month Acc Keeping Days`=%f ,`Part 1 Month Acc Out of Stock Days`=%f , `Part 1 Month Acc Unknown Stock Days`=%s, `Part 1 Month Acc GMROI`=%s where `Part SKU`=%d"
				,$astock
				,$avalue
				,$tdays
				,$outstock
				,$unknown
				,$gmroi
				,$this->id);
			//   print "$sql\n";
			if (!mysql_query($sql))
				exit(" $sql x errot con not update part stock history yr cc");


			$astock=0;
			$avalue=0;

			$sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
			// print "$sql\n";
			$result=mysql_query($sql);
			$days=0;
			$errors=0;
			$outstock=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				if (is_numeric($row['stock']))
					$astock+=$row['stock'];
				if (is_numeric($row['value']))
					$avalue+=$row['value'];
				$days++;

				if (is_numeric($row['stock']) and $row['stock']==0)
					$outstock++;
				if ($row['stock']=='ERROR')
					$errors++;
			}

			$days_ok=$days-$errors;

			$gmroi='NULL';
			if ($days_ok>0) {
				$tmp=1.0000001/$days_ok;
				$astock=$astock*$tmp;
				$avalue=$avalue*$tmp;
				if ($avalue>0)
					$gmroi=$this->data['Part 1 Week Acc Profit']/$avalue;
			} else {
				$astock='NULL';
				$avalue='NULL';
			}

			$tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
			//print "$tdays $days o: $outstock e: $errors \n";
			$unknown=$tdays-$days_ok;
			$sql=sprintf("update `Part Dimension` set `Part 1 Week Acc AVG Stock`=%s ,`Part 1 Week Acc AVG Stock Value`=%s,`Part 1 Week Acc Keeping Days`=%f ,`Part 1 Week Acc Out of Stock Days`=%f , `Part 1 Week Acc Unknown Stock Days`=%s, `Part 1 Week Acc GMROI`=%s where `Part SKU`=%d"
				,$astock
				,$avalue
				,$tdays
				,$outstock
				,$unknown
				,$gmroi
				,$this->id);
			//   print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql q errot con not update part stock history wk");

			break;
		case('used in list'):

			$sql=sprintf("select `Product ID` from `Product Part Dimension` PPD  left join  `Product Part List` PPL on (PPL.`Product Part Key`=PPD.`Product Part Key`)  where `Part SKU`=%d group by `Product ID`",$this->data['Part SKU']);
			// print $sql;
			$result=mysql_query($sql);
			$this->used_in_list=array();

			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->used_in_list[]=$row['Product ID'];
			}
			//   print_r($this->used_in_list);
			break;






		}

	}

	function formated_sku() {
		return $this->get_sku();

	}

	function get_sku() {
		return sprintf("SKU%05d",$this->sku);

	}

	function get_period($period,$key) {
		return $this->get($period.' '.$key);
	}

	function get($key='',$args=false) {






		if (array_key_exists($key,$this->data))
			return $this->data[$key];




		if (preg_match('/No Supplied$/',$key)) {

			$_key=preg_replace('/ No Supplied$/','',$key);
			if (preg_match('/^Part /',$key)) {
				return $this->data["$_key Required"]-$this->data["$_key Provided"];

			} else {
				return number($this->data["Part $_key Required"]-$this->data["Part $_key Provided"]);
			}

		}


		if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Month To|Today|Week To).*(Amount|Profit)$/',$key)) {

			$amount='Part '.$key;

			return money($this->data[$amount]);
		}

		if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Month To|Today|Week To).*(Margin|GMROI)$/',$key)) {

			$amount='Part '.$key;

			return percentage($this->data[$amount],1);
		}


		if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Provided|Broken|Acquired)$/',$key) or $key=='Current Stock'  ) {

			$amount='Part '.$key;

			return number($this->data[$amount]);
		}

		$_key=preg_replace('/^part /','',$key);
		if (isset($this->data[$_key]))
			return $this->data[$key];


		switch ($key) {
		case("Sticky Note"):
			return nl2br($this->data['Part Sticky Note']);
			break;
		case('Current Stock Available'):

			return number($this->data['Part Current On Hand Stock']-$this->data['Part Current Stock In Process']);

		case('Cost'):
			global $corporate_currency;
			return money($this->data['Part Current Stock Cost Per Unit'],$corporate_currency);


			break;

		case('Volume'):

			if (!is_numeric($this->data['Part Package Volume']) or $this->data['Part Package Volume']==0) {
				return '';
			}


			$number_digits=strlen(substr(strrchr($this->data['Part Package Volume'], "."), 1));


			if ($this->data['Part Package Volume']<1) {
				return number($this->data['Part Gross Weight']*1000,$number_digits).'mL';
			}else {
				return number($this->data['Part Package Volume'],$number_digits).'L';
			}

			break;


		case('Weight'):

			if (!is_numeric($this->data['Part Gross Weight']) or $this->data['Part Gross Weight']==0) {
				return '';
			}


			$number_digits=(int)strlen(substr(strrchr($this->data['Part Gross Weight'], "."), 1));

			if ($this->data['Part Gross Weight']<1) {
				return number($this->data['Part Gross Weight']*1000,$number_digits).'g';
			}else {
				return number($this->data['Part Gross Weight'],$number_digits).'kg';
			}

			break;
		case('SKU'):
			return sprintf('SKU%5d',$this->sku);
			break;
		case('Unit Cost'):
			return $this->get_unit_cost($args);
			break;
		case('Picking Location Key'):

			return $this->get_picking_location_key();
			break;
		case('Valid From'):
		case('Valid From Datetime'):

			return strftime("%c",strtotime($this->data['Part Valid From']+' 0:00'));
			break;
		case('Valid To'):
			return strftime("%c",strtotime($this->data['Part Valid To']+' 0:00'));
			break;
		

			break;

		case('Current Associated Locations'):

			if (!$this->current_locations_loaded)
				$this->load_current_locations();
			return $this->current_associated_locations;
			break;

		case('Associated Locations'):
			$associate=array();
			$associated=array();

			if ($args!='') {
				$date=" and `Date`<='".date("Y-m-d H:i:s",strtotime($args))."'";
			} else
				$date='';



			$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Inventory Transaction Type`='Associate' and `Part SKU`=%d  %s  group by `Location Key`  ",$this->data['Part SKU'],$date);
			//  print $sql;
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$associate[]=$row['Location Key'];
			}
			foreach ($associate as $location_key) {
				$sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where (`Inventory Transaction Type`='Associate' or `Inventory Transaction Type`='Disassociate') and `Part SKU`=%d and `Location Key`=%d %s order by `Date` desc limit 1 ",$this->data['Part SKU'],$location_key,$date);
				//   print $sql;
				$res=mysql_query($sql);
				if ($row=mysql_fetch_array($res)) {

					if ($row['Inventory Transaction Type']=='Associate')
						$associated[]=$location_key;
				}

			}

			return $associated;
			break;

		}

		return false;
	}

	function get_unit($number) {
		//'10','25','100','200','bag','ball','box','doz','dwt','ea','foot','gram','gross','hank','kilo','ib','m','oz','ozt','pair','pkg','set','skein','spool','strand','ten','tube','vial','yd'
		switch ($this->data['Part Unit']) {
		case 'bag':
			$unit=ngettext('bag','bags',$number);
			break;
		case 'box':
			$unit=ngettext('box','boxes',$number);

			break;
		case 'doz':
			$unit=ngettext('dozen','dozens',$number);

			break;
		case 'ea':
			$unit=ngettext('unit','units',$number);

			break;
		default:
			$unit=$this->data['Part Unit'];
			break;
		}
		return $unit;
	}

	function get_current_stock() {
		$stock=0;
		$value=0;
		$in_process=0;
		$sql=sprintf("select sum(`Quantity On Hand`) as stock ,sum(`Quantity In Process`) as in_process ,sum(`Stock Value`) as value from `Part Location Dimension` where `Part SKU`=%d ",$this->id);
		$res=mysql_query($sql);
		//print $sql;
		if ($row=mysql_fetch_array($res)) {
			$stock=round($row['stock'],3);
			$in_process=round($row['in_process'],3);
			$value=$row['value'];

		}



		return array($stock,$value,$in_process);

	}

	function get_stock($date) {
		$stock=0;
		$value=0;
		$sql=sprintf("select ifnull(sum(`Quantity On Hand`),0) as stock,ifnull(sum(`Value At Cost`),0) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`=%s"
			,$this->id,prepare_mysql($date));
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$stock=$row['stock'];
			$value=$row['value'];
		}
		return array($stock,$value);
	}




	function get_all_product_ids() {
		$sql=sprintf("select `Product Part Dimension`.`Product ID` from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d  group by `Product ID`",$this->data['Part SKU']);
		// print $sql;
		$result=mysql_query($sql);
		$products=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$products[$row['Product ID']]=array('Product ID'=>$row['Product ID']);
		}
		return $products;
	}


	function update_stock_state() {

		if ($this->data['Part Current Stock']<0) {
			$stock_state='Error';
		}elseif ($this->data['Part Current Stock']==0) {
			$stock_state='OutofStock';
		}elseif ($this->data['Part Days Available Forecast']<=$this->data['Part Delivery Days']) {
			$stock_state='VeryLow';
		}elseif ($this->data['Part Days Available Forecast']<=$this->data['Part Delivery Days']+7) {
			$stock_state='Low';
		}elseif ($this->data['Part Days Available Forecast']>=$this->data['Part Excess Availability Days Limit']) {
			$stock_state='Excess';
		}else {
			$stock_state='Normal';
		}
		$this->data['Part Stock State']=$stock_state;

		$sql=sprintf("update `Part Dimension`  set `Part Stock State`=%s where  `Part SKU`=%d   ",
			prepare_mysql($this->data['Part Stock State']),
			$this->id
		);
		//print $sql;
		mysql_query($sql);


		$products=$this->get_current_product_ids();

		foreach ($products as  $product_id=>$values) {
			$product=new Product('pid',$product_id);
			if ($product->id) {
				$product->update_availability();
			}
		}


	}

	function update_stock() {


		$picked=0;
		$required=0;


		$sql=sprintf("select sum(`Picked`) as picked, sum(`Required`) as required from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`='Order In Process'"
			,$this->id
		);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$picked=round($row['picked'],3);
			$required=round($row['required'],3);
		}


		list($stock,$value,$in_process)=$this->get_current_stock();
		//print $stock;
		$this->data['Part Current Stock']=$stock+$picked;
		$this->data['Part Current Value']=$value;
		$this->data['Part Current Stock In Process']=$required-$picked;
		$this->data['Part Current Stock Picked']=$picked;
		$this->data['Part Current On Hand Stock']=$stock;



		$sql=sprintf("update `Part Dimension`  set `Part Current Stock`=%f ,`Part Current Value`=%f,`Part Current Stock In Process`=%f,`Part Current Stock Picked`=%f,
			       `Part Current On Hand Stock`=%f where  `Part SKU`=%d   "
			,$stock+$picked
			,$value
			,$required-$picked
			,$picked
			,$stock
			,$this->id
		);
		mysql_query($sql);

		$this->update_stock_state();





		//print "$sql\n";
	}

	function update_availability() {


		$availability='No';

		$supplier_products=$this->get_supplier_products();
		// if (count($supplier_products)>0) {
		//   $availability='Yes';
		// }




		//print_r($supplier_products);

		//TODO meka it work if you have more that 2 suppliers, for now all parts are 1-1 (1-n,n-1) are treated as production

		foreach ($supplier_products as $supplier_product) {

			if ($supplier_product['Supplier Product Part In Use']=='Yes')
				$availability='Yes';
		}


		$sql=sprintf("update `Part Dimension`  set `Part Available`=%s where  `Part SKU`=%d   "
			,prepare_mysql($availability)
			,$this->sku
		);
		mysql_query($sql);




		//print "$sql\n";
		$this->update_main_state();


		$products=$this->get_product_ids();
		foreach ($products as $product_pid) {
			$product=new Product ('pid',$product_pid);
			$product->update_availability_type();

		}

	}

	function update_valid_to($date) {
		$this->data['Part Valid To']=$date;
		$sql=sprintf("update `Part Dimension`  set `Part Valid To`=%s where  `Part SKU`=%d    "
			,prepare_mysql($date)
			,$this->id
		);
		mysql_query($sql);
		//print "$sql\n";
		if (mysql_affected_rows()) {
			//print "sdasdas asdkokk; $date\n";
			$this->update_product_part_list_dates();
		}



	}

	function update_valid_from($date) {
		$this->data['Part Valid To']=$date;
		$sql=sprintf("update `Part Dimension`  set `Part Valid From`=%s where  `Part SKU`=%d    "
			,prepare_mysql($date)
			,$this->id
		);
		mysql_query($sql);

		$this->update_product_part_list_dates();


	}

	function update_picking_location() {

		$sql=sprintf("select * from `Part Location Dimension` PL left join `Location Dimension` L on (L.`Location Key`=PL.`Location Key`) where `Part SKU`=%d and `Can Pick`='Yes'  ",$this->sku);
		$res=mysql_query($sql);
		$picking_location='';
		while ($row=mysql_fetch_assoc($res)) {
			$picking_location.=sprintf(", <href='location.php?id=%d'>%s</a>",$row['Location Key'],$row['Location Code']);
		}
		//print $sql;
		$picking_location=preg_replace('/^,/','',$picking_location);
		$this->data['Part XHTML Picking Location']=$picking_location;

		$sql=sprintf("update `Part Dimension`  set `Part XHTML Picking Location`=%s where  `Part SKU`=%d   "
			,prepare_mysql($this->data['Part XHTML Picking Location'],false)
			,$this->id
		);
		// print $sql;
		//  exit;
		mysql_query($sql);

	}

	function update_valid_dates($date) {
		$affected_from=0;
		$affected_to=0;
		$sql=sprintf("update `Part Dimension`  set `Part Valid From`=%s where  `Part SKU`=%d and `Part Valid From`>%s   "
			,prepare_mysql($date)
			,$this->id
			,prepare_mysql($date)

		);
		//     print $sql;
		mysql_query($sql);
		if ($affected_from=mysql_affected_rows())
			$this->data['Part Valid From']=$date;
		$sql=sprintf("update `Part Dimension`  set `Part Valid To`=%s where  `Part SKU`=%d and `Part Valid To`<%s   "
			,prepare_mysql($date)
			,$this->id
			,prepare_mysql($date)

		);
		mysql_query($sql);
		if ($affected_to=mysql_affected_rows())
			$this->data['Part Valid To']=$date;


		return $affected_to+$affected_from;
	}

	function get_suppliers() {
		$suppliers=array();
		$sql=sprintf("select `Supplier Product Code`,  SD.`Supplier Key`,`Supplier Code` from `Supplier Product Part List` SPPL   left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPPL.`Supplier Key`)
                     where `Part SKU`=%d  order by `Supplier Key`;",$this->data['Part SKU']);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$suppliers[$row['Supplier Key']]=array('Supplier Key'=>$row['Supplier Key']);
		}
		return $suppliers;
	}


	function get_historic_locations() {
		$locations=array();

		$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Inventory Transaction Type`='Associate' and `Part SKU`=%d   group by `Location Key`  ",$this->data['Part SKU']);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$locations[$row['Location Key']]=$row['Location Key'];
		}

		return $locations;

	}


	function get_all_supplier_products_pids() {


		$supplier_products=array();
		$sql=sprintf("select  SPPD.`Supplier Product ID`
                     from `Supplier Product Part List` SPPL
                     left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)
                     where `Part SKU`=%d ;
                     ",$this->data['Part SKU']);
		// print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$supplier_products[$row['Supplier Product ID']]=$row['Supplier Product ID'];


		}
		return $supplier_products;
	}
	function get_supplier_products($date=false) {
		//exit("xxxx");
		if ($date) {
			return $this->get_supplier_products_historic($date);
		}

		$supplier_products=array();
		$sql=sprintf("

                     select  SPPD.`Supplier Product ID` , `Supplier Product Current Key`,SPPD.`Supplier Product Part Key`,`Supplier Product Part In Use`,`Supplier Product Units Per Part`,SPD.`Supplier Product Code`,  SPD.`Supplier Key`,`Supplier Code`
                     from `Supplier Product Part List` SPPL
                     left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)
                     left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product ID`=SPPD.`Supplier Product ID`) where `Part SKU`=%d and `Supplier Product Part Most Recent`='Yes';
                     ",$this->data['Part SKU']);
		// print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$supplier_products[$row['Supplier Product ID']]=array(
				'Supplier Key'=>$row['Supplier Key'],
				'Supplier Product ID'=>$row['Supplier Product ID'],
				'Supplier Product Keys'=>$row['Supplier Product Current Key'],
				'Supplier Product Current Key'=>$row['Supplier Product Current Key'],
				'Supplier Product Code'=>$row['Supplier Product Code'],
				'Supplier Product Units Per Part'=>$row['Supplier Product Units Per Part'],
				'Supplier Product Part Key'=>$row['Supplier Product Part Key'],
				'Supplier Product Part In Use'=>$row['Supplier Product Part In Use'],

			);
		}
		//print_r($supplier_products);
		return $supplier_products;
	}

	function get_supplier_products_historic($date) {
		$supplier_products=array();
		$sql=sprintf("select SPD.`Supplier Product ID`, `SPH Key`,  `Supplier Product Units Per Part`,SPD.`Supplier Product Code`,  SD.`Supplier Key`,SD.`Supplier Code`     from `Supplier Product Part List` SPPL    left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)    left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product ID`=SPPD.`Supplier Product ID`)    left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPD.`Supplier Key`)     left join `Supplier Product History Dimension` H on ( H.`Supplier Product ID`=SPD.`Supplier Product ID` )    where `Part SKU`=%d
                     and ( (`SPH Valid From`<=%s and `SPH Valid To`>=%s and `SPH Type`='Historic') or (`SPH Valid From`<=%s and  `SPH Type`='Normal')     )
                     and ( (`Supplier Product Part Valid From`<=%s  and `Supplier Product Part Valid To`>=%s and `Supplier Product Part Most Recent`='No') or  (`Supplier Product Part Valid From`<=%s and `Supplier Product Part Most Recent`='Yes')
                     ) ;"
			,$this->data['Part SKU'],
			prepare_mysql($date),
			prepare_mysql($date),
			prepare_mysql($date),
			prepare_mysql($date),
			prepare_mysql($date),
			prepare_mysql($date)
		);
		//print "$sql\n\n";
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


			if (isset($supplier_products[$row['Supplier Product ID']])) {

				$supplier_products[$row['Supplier Product ID']]['Supplier Product Keys'].=','.$row['SPH Key'];


			} else {

				$supplier_products[$row['Supplier Product ID']]=array(
					'Supplier Key'=>$row['Supplier Key'],
					'Supplier Product Keys'=>$row['SPH Key'],
					'Supplier Product ID'=>$row['Supplier Product ID'],
					'Supplier Product Code'=>$row['Supplier Product Code'],
					'Supplier Product Units Per Part'=>$row['Supplier Product Units Per Part']

				);
			}

		}
		return $supplier_products;
	}

	function load_locations($date='') {

		if (preg_match('/\d{4}-\{d}2-\d{2}/',$date))
			$this->load_locations_historic($date);
		else
			$this->load_current_locations();
	}

	function load_current_historic($date) {
		$this->all_historic_associated_locations=array();
		$this->associated_location_on_date=array();

		$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Inventory Transaction Type`='Associate' and `Part SKU`=%d  `Date`=%s  group by `Location Key`  ",$this->data['Part SKU'],$date);
		// print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->all_historic_associated_locations[]=$row['Location Key'];
		}
		foreach ($this->all_historic_associated_locations as $location_key) {
			$sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where (`Inventory Transaction Type`='Associate' or `Inventory Transaction Type`='Disassociate') and `Part SKU`=%d and `Location Key`=%d %s order by `Date` desc limit 1 ",$this->data['Part SKU'],$location_key,$date);
			//   print $sql;
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {

				if ($row['Inventory Transaction Type']=='Associate')
					$this->associated_location_on_date[]=$location_key;
			}

		}

	}

	function get_picking_location_key($date=false,$qty=1) {
		if ($date) {
			return $this->get_picking_location_historic($date,$qty);
		}

		//FORCING PICKING FOR PICKING LOCAtION EVEN IF IS NEGATIVE

		$this->unknown_location_associated=false;
		$locations=array();
		$sql=sprintf("select `Location Key` from `Part Location Dimension` where `Part SKU` in (%s) order by `Can Pick` ;",$this->sku);
		//print "$sql\n";
		$res=mysql_query($sql);
		$locations_data=array();
		while ($row=mysql_fetch_assoc($res)) {
			$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
			list($stock,$value,$in_process)=$part_location->get_stock();
			$locations_data[]=array('location_key'=>$row['Location Key'],'stock'=>$stock);

		}

		$number_associated_locations=count($locations_data);

		if ($number_associated_locations==0) {
			$this->unknown_location_associated=true;
			$locations[]= array('location_key'=>1,'qty'=>$qty);
			$qty=0;
		}else {

			foreach ($locations_data as $location_data) {

				$locations[]=array('location_key'=>$location_data['location_key'],'qty'=>$qty);
				break;





			}
			//print_r($locations);
			//print "--- $qty\n";



		}

		//print_r($locations);
		return $locations;

	}

	function get_picking_location_key_origial($date=false,$qty=1) {
		if ($date) {
			return $this->get_picking_location_historic($date,$qty);
		}
		$this->unknown_location_associated=false;
		$locations=array();
		$sql=sprintf("select `Location Key` from `Part Location Dimension` where `Part SKU` in (%s) order by `Can Pick` ;",$this->sku);
		//print "$sql\n";
		$res=mysql_query($sql);
		$locations_data=array();
		while ($row=mysql_fetch_assoc($res)) {
			$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
			list($stock,$value,$in_process)=$part_location->get_stock();
			$locations_data[]=array('location_key'=>$row['Location Key'],'stock'=>$stock);

		}

		$number_associated_locations=count($locations_data);

		if ($number_associated_locations==0) {
			$this->unknown_location_associated=true;
			$locations[]= array('location_key'=>1,'qty'=>$qty);
			$qty=0;
		}else {

			foreach ($locations_data as $location_data) {
				if ($qty>0) {
					if ($location_data['stock']>=$qty) {
						$locations[]=array('location_key'=>$location_data['location_key'],'qty'=>$qty);
						$qty=0;
					}
					elseif ($location_data['stock']>0) {
						$locations[]=array('location_key'=>$location_data['location_key'],'qty'=>$location_data['stock']);
						$qty=$qty-$location_data['stock'];
					}
				}



			}
			//print_r($locations);
			//print "--- $qty\n";

			if (count($locations)==0) {

				$locations[]= array('location_key'=>$locations_data[0]['location_key'],'qty'=>$qty);
				$qty=0;
			}
			if ($qty>0) {
				$locations[0]['qty']=$locations[0]['qty']+$qty;
			}


		}

		//print_r($locations);
		return $locations;

	}


	function associate_unknown_location_historic($date=false) {

		if (!$date) {
			$date=gmdate("Y-m-d H:i:s");
		}
		$date=date("Y-m-d H:i:s",strtotime("$date -1 second"));
		$location_key=1;


		$sql=sprintf("select `Inventory Transaction Key` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Inventory Transaction Type`='Associate' and `Date`>%s order by `Date`  ",$this->sku,$location_key,prepare_mysql($date));

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Key`=%d  "
				,$row['Inventory Transaction Key']
			);
			// print "$sql\n";
			mysql_query($sql);

			$details=_('Part')." SKU".sprintf("%05d",$this->sku)." "._('associated with unknown location');
			$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
				,$this->sku
				,$location_key
				,"'Associate'"
				,0
				,0
				,0
				,prepare_mysql($details)
				,prepare_mysql($date)

			);
			mysql_query($sql);

		}

		else {

			$sql=sprintf("select `Inventory Transaction Key` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Inventory Transaction Type`='Disassociate' and `Date`>%s order by `Date`  ",$this->sku,$location_key,prepare_mysql($date));

			$res2=mysql_query($sql);
			// print $sql;
			if ($row2=mysql_fetch_array($res2)) {

			}else {



				$pl_data=array(
					'Part SKU'=>$this->sku,
					'Location Key'=>$location_key,
					'Date'=>$date);
				//print_r($pl_data);
				$part_location=new PartLocation('find',$pl_data,'create');
			}

			//print_r($part_location);
		}


	}

	function get_picking_location_historic($date,$qty) {


		include_once 'class.PartLocation.php';

		$this->unknown_location_associated=false;


		$locations=array();
		$was_associated=array();
		$sql=sprintf("select ITF.`Location Key`  from `Inventory Transaction Fact` ITF    where `Inventory Transaction Type`='Associate' and  `Part SKU`=%d and `Date`<=%s   order by `Location Key`  desc  ",$this->sku,prepare_mysql($date));

		$result=mysql_query($sql);
		$_locations=array();


		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			if (in_array($row['Location Key'],$_locations)) {
				continue;
			}else {
				$_locations[]=$row['Location Key'];
			}
			$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);


			if ($part_location->location->data['Location Mainly Used For']=='Picking') {


				if ($part_location->is_associated($date)) {
					list($stock,$value,$in_process)=$part_location->get_stock($date);
					$was_associated[]=array('location_key'=>$row['Location Key'],'stock'=>$stock);

				}
			}

		}


		$sql=sprintf("select ITF.`Location Key`  from `Inventory Transaction Fact` ITF    where `Inventory Transaction Type`='Associate' and  `Part SKU`=%d and `Date`<=%s   ",$this->sku,prepare_mysql($date));

		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			if (in_array($row['Location Key'],$_locations)) {
				continue;
			}else {
				$_locations[]=$row['Location Key'];
			}
			$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);

			// print_r($part_location->location);
			if ($part_location->location->data['Location Mainly Used For']!='Picking') {
				if ($part_location->is_associated($date)) {
					list($stock,$value,$in_process)=$part_location->get_stock($date);
					$was_associated[]=array('location_key'=>$row['Location Key'],'stock'=>$stock);

				}
			}

		}




		//print "------------------".$this->sku."\n";
		// print_r($was_associated);

		//print "==================\n";


		$number_associated_locations=count($was_associated);

		if ($number_associated_locations==0) {
			$this->unknown_location_associated=true;
			$locations[]= array('location_key'=>1,'qty'=>$qty);
			$qty=0;
		}else {
			//foreach ($was_associated as $key => $row) {
			// $_location_key[$key]  = $row['location_key'];
			//}
			//array_multisort($_location_key, SORT_DESC, $was_associated);

			foreach ($was_associated as $location_data) {
				if ($qty>0) {
					if ($location_data['stock']>=$qty) {
						$locations[]=array('location_key'=>$location_data['location_key'],'qty'=>$qty);
						$qty=0;
					}
					elseif ($location_data['stock']>0) {
						$locations[]=array('location_key'=>$location_data['location_key'],'qty'=>$location_data['stock']);
						$qty=$qty-$location_data['stock'];
					}
				}



			}
			//print_r($locations);
			//print "--- $qty\n";

			if (count($locations)==0) {

				$locations[]= array('location_key'=>$was_associated[0]['location_key'],'qty'=>$qty);
				$qty=0;
			}
			if ($qty>0) {
				$locations[0]['qty']=$locations[0]['qty']+$qty;
			}


		}
		//if ($this->unknown_location_associated)
		// print "\n".$this->sku." unknown location addes\n";


		//print_r($locations);
		//print "`~~~~~~~~~~~~~~~\n";

		return $locations;


	}




	function get_current_products($for_smarty=false) {


		// $sql=sprintf("select `Store Code`,PD.`Product ID`,`Product Code`                                                                                 from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` PD on (PD.`Product ID`=PPD.`Product ID`) left join `Store Dimension`  on (PD.`Product Store Key`=`Store Key`)  where PPL.`Part SKU`=%d and `Product Part Most Recent`='Yes'  order by `Product Code`,`Store Code`",$this->data['Part SKU']);

		$sql=sprintf("select  `Product Web Configuration`,`Product Web State`,`Store Key`,`Store Code`,P.`Product ID`,`Product Code`,`Product Store Key` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` P on (P.`Product ID`=PPD.`Product ID`) left join `Store Dimension` on (`Product Store Key`=`Store Key`)  where  `Part SKU`=%d  and  `Product Part Most Recent`='Yes'  and `Product Record Type`='Normal'"
			,$this->sku

		);
		//print $sql;
		$res=mysql_query($sql);
		$products=array();
		while ($row=mysql_fetch_array($res)) {
			$products[$row['Product ID']]= array(
				'ProductID'=>$row['Product ID'],
				'ProductCode'=>$row['Product Code'],
				'StoreCode'=>$row['Store Code'],
				'StoreKey'=>$row['Store Key'],
				'ProductWebConfiguration'=>$row['Product Web Configuration'],
				'ProductWebState'=>$row['Product Web State'],
			);
		}

		return $products;




	}




	function get_locations($for_smarty=false) {

		$sql=sprintf("select * from `Part Location Dimension` where `Part SKU` in (%s)",$this->sku);

		$res=mysql_query($sql);
		$part_locations=array();
		while ($row=mysql_fetch_assoc($res)) {

			$location=new Location($row['Location Key']);

			$row['Formated Quantity On Hand']=number($row['Quantity On Hand']);

			$row['Part Formated SKU']=$this->get_sku();

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

	function get_location_keys() {
		$this->load_current_locations();
		return $this->current_associated_locations;
	}

	function load_current_locations() {
		$this->current_associated_locations=array();
		$sql=sprintf("select `Location Key` from `Part Location Dimension` where   `Part SKU`=%d    group by `Location Key`  ",$this->data['Part SKU']);
		//  print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->current_associated_locations[]=$row['Location Key'];
		}
		$this->current_locations_loaded=true;

	}

	function items_per_product($product_ID,$date=false) {
		$where_date='';

		$sql=sprintf("select AVG(`Parts Per Product`) as parts_per_product from `Product Part Dimension` PPD left join `Product Part List` PPL on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU`=%d and  `Product ID`=%d %s  "
			,$this->id
			,$product_ID
			,$where_date
		);
		//  print "$sql\n";
		$parts_per_product=0;
		$result3=mysql_query($sql);
		if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
			if (is_numeric($row3['parts_per_product']))
				$parts_per_product=$row3['parts_per_product'];
		}
		return $parts_per_product;


	}


	function get_fomated_unit_commercial_value($datetime='') {

		return money($this->get_unit_commercial_value($datetime));
	}

	function get_current_formated_commercial_value() {

		return money($this->data['Part Current On Hand Stock']*$this->get_unit_commercial_value());
	}

	function get_current_formated_value_at_cost() {
		//return number($this->data['Part Current Value'],2);
		return money( $this->data['Part Current Value']);
	}



	function get_current_formated_value_at_current_cost() {

		//return number($this->data['Part Current On Hand Stock']*$this->get_unit_cost(),2);
		$a=floatval(3.000*3.575);
		$a=round(3.575+3.575+3.575,3);
		return money($this->data['Part Current On Hand Stock']*$this->get_unit_cost());
	}

	function get_unit_commercial_value($datetime='') {



		$commercial_value=0;
		$sum_commercial_value=0;
		$count_commercial_value_samples=0;

		$product_part_lists=$this->get_product_part_list($datetime);

		// print_r($product_part_lists);

		foreach ($product_part_lists as $product_part_list) {



			$product=new Product('pid',$product_part_list['Product ID']);


			if ($product->pid) {
				$price=$product->get_historic_price_corporate_currency($datetime)/$product_part_list['Parts Per Product'];
				$_price=$product->get_historic_price($datetime)/$product_part_list['Parts Per Product'];
				// print "**** ".$product->data['Product Name']." $price  $_price\n";

				if ($price>0) {
					$sum_commercial_value+=$price;
					$count_commercial_value_samples++;
				}
			}

		}


		if ($count_commercial_value_samples) {
			$commercial_value=$sum_commercial_value/$count_commercial_value_samples;
		}
		// print "xx $commercial_value";

		return $commercial_value;
	}





	function update_stock_history() {


		$sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where `Part SKU`=%d group by `Location Key`",$this->sku);
		//print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			//      print $this->sku.'_'.$row['Location Key']."\n";
			$part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
			$part_location->update_stock_history();
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




	function update_sales_from_invoices($interval) {

		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_date_1yb)=calculate_inteval_dates($interval);
		//print "$db_interval,$from_date,$to_date,$from_date_1yb,$to_date_1yb  \n";

		setlocale(LC_ALL, 'en_GB');


		$this->data["Part $db_interval Acc Required"]=0;
		$this->data["Part $db_interval Acc Provided"]=0;
		$this->data["Part $db_interval Acc Given"]=0;
		$this->data["Part $db_interval Acc Sold Amount"]=0;
		$this->data["Part $db_interval Acc Profit"]=0;
		$this->data["Part $db_interval Acc Profit After Storing"]=0;
		$this->data["Part $db_interval Acc Sold"]=0;
		$this->data["Part $db_interval Acc Margin"]=0;


		$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                     from `Inventory Transaction Fact` ITF  where `Part SKU`=%d %s %s" ,
			$this->sku,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//   print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Part $db_interval Acc Profit"]=$row['profit'];
			$this->data["Part $db_interval Acc Profit After Storing"]=$this->data["Part $db_interval Acc Profit"]-$row['cost_storing'];

		}


		$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='In'  and `Part SKU`=%d  %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


			$this->data["Part $db_interval Acc Acquired"]=$row['bought'];

		}


		$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Sale' and `Part SKU`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Part $db_interval Acc Sold Amount"]=$row['sold_amount'];
			$this->data["Part $db_interval Acc Sold"]=$row['sold'];
			$this->data["Part $db_interval Acc Provided"]=-1.0*$row['dispatched'];
			$this->data["Part $db_interval Acc Required"]=$row['required'];
			$this->data["Part $db_interval Acc Given"]=$row['given'];

		}

		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Part SKU`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Part $db_interval Acc Broken"]=-1.*$row['broken'];

		}


		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Lost' and `Part SKU`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Part $db_interval Acc Lost"]=-1.*$row['lost'];

		}






		if ($this->data["Part $db_interval Acc Sold Amount"]!=0)
			$margin=$this->data["Part $db_interval Acc Profit After Storing"]/$this->data["Part $db_interval Acc Sold Amount"];
		else
			$margin=0;
		$this->data["Part $db_interval Acc Margin"]=$margin;


		$sql=sprintf("update `Part Dimension` set
                     `Part $db_interval Acc Required`=%f ,
                     `Part $db_interval Acc Provided`=%f,
                     `Part $db_interval Acc Given`=%f ,
                     `Part $db_interval Acc Sold Amount`=%f ,
                     `Part $db_interval Acc Profit`=%f ,
                     `Part $db_interval Acc Profit After Storing`=%f ,
                     `Part $db_interval Acc Sold`=%f ,
                     `Part $db_interval Acc Margin`=%s where
                     `Part SKU`=%d "
			,$this->data["Part $db_interval Acc Required"]
			,$this->data["Part $db_interval Acc Provided"]
			,$this->data["Part $db_interval Acc Given"]
			,$this->data["Part $db_interval Acc Sold Amount"]
			,$this->data["Part $db_interval Acc Profit"]
			,$this->data["Part $db_interval Acc Profit After Storing"]
			,$this->data["Part $db_interval Acc Sold"]
			,$this->data["Part $db_interval Acc Margin"]

			,$this->id);

		mysql_query($sql);





		if ($from_date_1yb) {


			$this->data["Part $db_interval Acc 1YB Required"]=0;
			$this->data["Part $db_interval Acc 1YB Provided"]=0;
			$this->data["Part $db_interval Acc 1YB Given"]=0;
			$this->data["Part $db_interval Acc 1YB Sold Amount"]=0;
			$this->data["Part $db_interval Acc 1YB Profit"]=0;
			$this->data["Part $db_interval Acc 1YB Profit After Storing"]=0;
			$this->data["Part $db_interval Acc 1YB Sold"]=0;
			$this->data["Part $db_interval Acc 1YB Margin"]=0;


			$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                     from `Inventory Transaction Fact` ITF  where `Part SKU`=%d %s %s" ,
				$this->sku,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//   print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Part $db_interval Acc 1YB Profit"]=$row['profit'];
				$this->data["Part $db_interval Acc 1YB Profit After Storing"]=$this->data["Part $db_interval Acc 1YB Profit"]-$row['cost_storing'];

			}


			$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='In'  and `Part SKU`=%d  %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


				$this->data["Part $db_interval Acc 1YB Acquired"]=$row['bought'];

			}


			$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Sale' and `Part SKU`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Part $db_interval Acc 1YB Sold Amount"]=$row['sold_amount'];
				$this->data["Part $db_interval Acc 1YB Sold"]=$row['sold'];
				$this->data["Part $db_interval Acc 1YB Provided"]=-1.0*$row['dispatched'];
				$this->data["Part $db_interval Acc 1YB Required"]=$row['required'];
				$this->data["Part $db_interval Acc 1YB Given"]=$row['given'];

			}

			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Part SKU`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Part $db_interval Acc 1YB Broken"]=-1.*$row['broken'];

			}


			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Lost' and `Part SKU`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),
				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Part $db_interval Acc 1YB Lost"]=-1.*$row['lost'];

			}






			if ($this->data["Part $db_interval Acc 1YB Sold Amount"]!=0)
				$margin=$this->data["Part $db_interval Acc 1YB Profit After Storing"]/$this->data["Part $db_interval Acc 1YB Sold Amount"];
			else
				$margin=0;
			$this->data["Part $db_interval Acc 1YB Margin"]=$margin;


			$sql=sprintf("update `Part Dimension` set
                     `Part $db_interval Acc 1YB Required`=%f ,
                     `Part $db_interval Acc 1YB Provided`=%f,
                     `Part $db_interval Acc 1YB Given`=%f ,
                     `Part $db_interval Acc 1YB Sold Amount`=%f ,
                     `Part $db_interval Acc 1YB Profit`=%f ,
                     `Part $db_interval Acc 1YB Profit After Storing`=%f ,
                     `Part $db_interval Acc 1YB Sold`=%f ,
                     `Part $db_interval Acc 1YB Margin`=%s where
                     `Part SKU`=%d "
				,$this->data["Part $db_interval Acc 1YB Required"]
				,$this->data["Part $db_interval Acc 1YB Provided"]
				,$this->data["Part $db_interval Acc 1YB Given"]
				,$this->data["Part $db_interval Acc 1YB Sold Amount"]
				,$this->data["Part $db_interval Acc 1YB Profit"]
				,$this->data["Part $db_interval Acc 1YB Profit After Storing"]
				,$this->data["Part $db_interval Acc 1YB Sold"]
				,$this->data["Part $db_interval Acc 1YB Margin"]

				,$this->id);

			mysql_query($sql);


			$this->data["Part $db_interval Acc 1YD Required"]=($this->data["Part $db_interval Acc 1YB Required"]==0?0:($this->data["Part $db_interval Acc Required"]-$this->data["Part $db_interval Acc 1YB Required"])/$this->data["Part $db_interval Acc 1YB Required"]);
			$this->data["Part $db_interval Acc 1YD Provided"]=($this->data["Part $db_interval Acc 1YB Provided"]==0?0:($this->data["Part $db_interval Acc Provided"]-$this->data["Part $db_interval Acc 1YB Provided"])/$this->data["Part $db_interval Acc 1YB Provided"]);
			$this->data["Part $db_interval Acc 1YD Given"]=($this->data["Part $db_interval Acc 1YB Given"]==0?0:($this->data["Part $db_interval Acc Given"]-$this->data["Part $db_interval Acc 1YB Given"])/$this->data["Part $db_interval Acc 1YB Given"]);
			$this->data["Part $db_interval Acc 1YD Sold Amount"]=($this->data["Part $db_interval Acc 1YB Sold Amount"]==0?0:($this->data["Part $db_interval Acc Sold Amount"]-$this->data["Part $db_interval Acc 1YB Sold Amount"])/$this->data["Part $db_interval Acc 1YB Sold Amount"]);
			$this->data["Part $db_interval Acc 1YD Profit"]=($this->data["Part $db_interval Acc 1YB Profit"]==0?0:($this->data["Part $db_interval Acc Profit"]-$this->data["Part $db_interval Acc 1YB Profit"])/$this->data["Part $db_interval Acc 1YB Profit"]);
			$this->data["Part $db_interval Acc 1YD Profit After Storing"]=($this->data["Part $db_interval Acc 1YB Profit After Storing"]==0?0:($this->data["Part $db_interval Acc Profit After Storing"]-$this->data["Part $db_interval Acc 1YB Profit After Storing"])/$this->data["Part $db_interval Acc 1YB Profit After Storing"]);
			$this->data["Part $db_interval Acc 1YD Sold"]=($this->data["Part $db_interval Acc 1YB Sold"]==0?0:($this->data["Part $db_interval Acc Sold"]-$this->data["Part $db_interval Acc 1YB Sold"])/$this->data["Part $db_interval Acc 1YB Sold"]);
			$this->data["Part $db_interval Acc 1YD Margin"]=($this->data["Part $db_interval Acc 1YB Margin"]==0?0:($this->data["Part $db_interval Acc Margin"]-$this->data["Part $db_interval Acc 1YB Margin"])/$this->data["Part $db_interval Acc 1YB Margin"]);


			$sql=sprintf("update `Part Dimension` set
                     `Part $db_interval Acc 1YD Required`=%f ,
                     `Part $db_interval Acc 1YD Provided`=%f,
                     `Part $db_interval Acc 1YD Given`=%f ,
                     `Part $db_interval Acc 1YD Sold Amount`=%f ,
                     `Part $db_interval Acc 1YD Profit`=%f ,
                     `Part $db_interval Acc 1YD Profit After Storing`=%f ,
                     `Part $db_interval Acc 1YD Sold`=%f ,
                     `Part $db_interval Acc 1YD Margin`=%s where
                     `Part SKU`=%d "
				,$this->data["Part $db_interval Acc 1YD Required"]
				,$this->data["Part $db_interval Acc 1YD Provided"]
				,$this->data["Part $db_interval Acc 1YD Given"]
				,$this->data["Part $db_interval Acc 1YD Sold Amount"]
				,$this->data["Part $db_interval Acc 1YD Profit"]
				,$this->data["Part $db_interval Acc 1YD Profit After Storing"]
				,$this->data["Part $db_interval Acc 1YD Sold"]
				,$this->data["Part $db_interval Acc 1YD Margin"]

				,$this->id);

			mysql_query($sql);


			//print "$sql\n";


		}


	}





	function update_sales_old() {
		// the product wich this one is
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s   "
			,prepare_mysql($this->data['Part SKU'])
			,prepare_mysql($this->data['Part Valid From'])
			,prepare_mysql($this->data['Part Valid To'])
		);
		//       print "$sql\n\n\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=floatval($row['amount_in']);
			$value=floatval($row['value']);
			$value_free=floatval($row['value_free']);
			$sold=$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;
		if ($amount_in==0)
			$margin=0;
		else {
			$margin=$profit_sold/$amount_in;
			// $margin=($value-$value_free)/$amount_in;
			// $margin=sprintf("%.6f",($value)*$tmp);
			$margin=preg_replace('/:/','1',$margin);
			//$margin=$value/$amount_in;
		}

		//     var_dump( $value );
		//       var_dump(  $amount_in);
		//       var_dump( 0.7/7 );
		$sql=sprintf("update `Part Dimension` set `Part Total Required`=%f ,`Part Total Provided`=%f,`Part Total Given`=%f ,`Part Total Sold Amount`=%f ,`Part Total Absolute Profit`=%f ,`Part Total Profit When Sold`=%f , `Part Total Sold`=%f , `Part Total Margin`=%f  where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//    print "$sql\n";
		if (!mysql_query($sql))
			exit(" $sql\n error xxx $margin b $value c $value_free d $amount_in  con not uopdate product part when loading sales");

		// --------------------------------------- Start of 3 year -----------------------------------------------------------------------------
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and  `Date`>=%s     ",
			prepare_mysql($this->data['Part SKU']),
			prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 year")))  );
		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;
		$sql=sprintf("update `Part Dimension` set `Part 3 Year Acc Required`=%f ,`Part 3 Year Acc Provided`=%f,`Part 3 Year Acc Given`=%f ,`Part 3 Year Acc Sold Amount`=%f ,`Part 3 Year Acc Absolute Profit`=%f ,`Part 3 Year Acc Profit When Sold`=%f , `Part 3 Year Acc Sold`=%f , `Part 3 Year Acc Margin`=%s where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//  print "$sql\n";
		if (!mysql_query($sql))
			exit(" $sql\n error con not uopdate product part when loading sales");
		// --------------------------------------- End of 3 year -------------------------------------------------------------------------------
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale'  and `Date`>=%s     ",
			prepare_mysql($this->data['Part SKU']),
			prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;
		$sql=sprintf("update `Part Dimension` set `Part 1 Year Acc Required`=%f ,`Part 1 Year Acc Provided`=%f,`Part 1 Year Acc Given`=%f ,`Part 1 Year Acc Sold Amount`=%f ,`Part 1 Year Acc Absolute Profit`=%f ,`Part 1 Year Acc Profit When Sold`=%f , `Part 1 Year Acc Sold`=%f , `Part 1 Year Acc Margin`=%s where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//  print "$sql\n";
		if (!mysql_query($sql))
			exit(" $sql\n error con not uopdate product part when loading sales");

		// --------------------------------------- Start of yeartoday -----------------------------------------------------------------------------



		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and  `Date`>=%s     ",
			prepare_mysql($this->data['Part SKU']),
			prepare_mysql(date("Y-01-01 00:00:00"))
		);
		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;
		$sql=sprintf("update `Part Dimension` set `Part Year To Day Acc Required`=%f ,`Part Year To Day Acc Provided`=%f,`Part Year To Day Acc Given`=%f ,`Part Year To Day Acc Sold Amount`=%f ,`Part Year To Day Acc Absolute Profit`=%f ,`Part Year To Day Acc Profit When Sold`=%f , `Part Year To Day Acc Sold`=%f , `Part Year To Day Acc Margin`=%s where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//  print "$sql\n";
		if (!mysql_query($sql))
			exit(" $sql\n error con not uopdate product part when loading sales");
		// --------------------------------------- End of yeartoday-------------------------------------------------------------------------------
		// ----------------------------------------Start of 6 month ------------------------------------------------------------------------------
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s     ",
			prepare_mysql($this->data['Part SKU']),prepare_mysql(date("Y-m-d H:i:s",strtotime("now -6 month")))  );
		//      print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;

		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;

		$sql=sprintf("update `Part Dimension` set `Part 6 Month Acc Required`=%f ,`Part 6 Month Acc Provided`=%f,`Part 6 Month Acc Given`=%f ,`Part 6 Month Acc Sold Amount`=%f ,`Part 6 Month Acc Absolute Profit`=%f ,`Part 6 Month Acc Profit When Sold`=%f  , `Part 6 Month Acc Sold`=%f  , `Part 6 Month Acc Margin`=%s where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//   print "$sql\n";
		if (!mysql_query($sql))
			exit("error con not uopdate product part when loading sales");
		// ----------------------------------------End of 6 month --------------------------------------------------------------------------------
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s     ",
			prepare_mysql($this->data['Part SKU']),
			prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
		//      print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;

		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;

		$sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc Required`=%f ,`Part 1 Quarter Acc Provided`=%f,`Part 1 Quarter Acc Given`=%f ,`Part 1 Quarter Acc Sold Amount`=%f ,`Part 1 Quarter Acc Absolute Profit`=%f ,`Part 1 Quarter Acc Profit When Sold`=%f  , `Part 1 Quarter Acc Sold`=%f  , `Part 1 Quarter Acc Margin`=%s where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//   print "$sql\n";
		if (!mysql_query($sql))
			exit("error con not uopdate product part when loading sales");



		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale'   and `Date`>=%s     ",
			prepare_mysql($this->data['Part SKU']),
			prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
		//      print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}
		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;

		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;


		$sql=sprintf("update `Part Dimension` set `Part 1 Month Acc Required`=%f ,`Part 1 Month Acc Provided`=%f,`Part 1 Month Acc Given`=%f ,`Part 1 Month Acc Sold Amount`=%f ,`Part 1 Month Acc Absolute Profit`=%f ,`Part 1 Month Acc Profit When Sold`=%f  , `Part 1 Month Acc Sold`=%f , `Part 1 Month Acc Margin`=%s  where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//            print "$sql\n";
		if (!mysql_query($sql))
			exit(" $sql\n error con not uopdate product part when loading sales");
		// --------------------------------------------------------Start of 10 days----------------------------------------------
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and  `Date`>=%s     ",
			prepare_mysql($this->data['Part SKU']),prepare_mysql(date("Y-m-d H:i:s",strtotime("now -10 day")))  );
		//        print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}

		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;

		$sql=sprintf("update `Part Dimension` set `Part 10 Day Acc Required`=%f ,`Part 10 Day Acc Provided`=%f,`Part 10 Day Acc Given`=%f ,`Part 10 Day Acc Sold Amount`=%f ,`Part 10 Day Acc Absolute Profit`=%f ,`Part 10 Day Acc Profit When Sold`=%f  , `Part 10 Day Acc Sold`=%f , `Part 10 Day Acc Margin`=%s where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//            print "$sql\n";
		if (!mysql_query($sql))
			exit(" $sql\n error con not uopdate product part when loading sales");
		// ---------------------------------------------------------End of 10 days----------------------------------------------
		$sold=0;
		$required=0;
		$provided=0;
		$given=0;
		$amount_in=0;
		$value=0;
		$value_free=0;
		$margin=0;
		$sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale'  and `Date`>=%s",
			prepare_mysql($this->data['Part SKU']),prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
		//        print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$required=$row['required'];
			$provided=$row['qty'];
			$given=$row['given'];
			$amount_in=$row['amount_in'];
			$value=$row['value'];
			$value_free=$row['value_free'];
			$sold=$row['qty']-$row['given'];
		}

		$abs_profit=$amount_in-$value;
		$profit_sold=$amount_in-$value+$value_free;
		if ($amount_in==0)
			$margin=0;
		else
			$margin=$profit_sold/$amount_in;

		$sql=sprintf("update `Part Dimension` set `Part 1 Week Acc Required`=%f ,`Part 1 Week Acc Provided`=%f,`Part 1 Week Acc Given`=%f ,`Part 1 Week Acc Sold Amount`=%f ,`Part 1 Week Acc Absolute Profit`=%f ,`Part 1 Week Acc Profit When Sold`=%f  , `Part 1 Week Acc Sold`=%f , `Part 1 Week Acc Margin`=%s where `Part SKU`=%d "
			,$required
			,$provided
			,$given
			,$amount_in
			,$abs_profit
			,$profit_sold,$sold,$margin
			,$this->id);
		//            print "$sql\n";
		if (!mysql_query($sql))
			exit(" $sql\n error con not uopdate product part when loading sales");

	}

	function update_available_forecast() {

		// -------------- simple forecast -------------------------

		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`='Associate' order by `Date` desc"
			,$this->id);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$date=$row['Date'];
			$interval=(date('U')-strtotime($date))/3600/24;
		} else
			$interval=0;

		if ($this->data['Part Current Stock']=='' or $this->data['Part Current Stock']<0) {
			$this->data['Part Days Available Forecast']=0;
			$this->data['Part XHTML Available For Forecast']='?';
		}
		elseif ($this->data['Part Current Stock']==0) {
			$this->data['Part Days Available Forecast']=0;
			$this->data['Part XHTML Available For Forecast']=0;
		}
		else {

			if ($this->data['Part 1 Year Acc Required']>0) {
				if ($interval>(365)) {
					$interval=365;
				}

				$this->data['Part Days Available Forecast']=$interval*$this->data['Part Current Stock']/$this->data['Part 1 Year Acc Required'];
				$this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast'],0).' '._('d');
			}
			elseif ($this->data['Part 1 Quarter Acc Required']>0) {



				// print $this->data['Part 1 Quarter Acc Required']."xxxx\n";
				if ($interval>(365/4)) {
					$interval=365/4;
				}
				//print $this->data['Part 1 Quarter Acc Required']/$interval;


				$this->data['Part Days Available Forecast']=$interval*$this->data['Part Current Stock']/$this->data['Part 1 Quarter Acc Required'];
				$this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast'],0).' '._('d');
			}
			else {

				$from_since=(date('U')-strtotime($this->data['Part Valid From'])/86400);
				if ($from_since<($this->data['Part Excess Availability Days Limit']/2)) {
					$forecast=$this->data['Part Excess Availability Days Limit']-1;
				}else {
					$forecast=$this->data['Part Excess Availability Days Limit']+$from_since;
				}



				$this->data['Part Days Available Forecast']=$forecast;
				$this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast'],0).' '._('d');




			}





		}

		$sql=sprintf("update `Part Dimension` set `Part Days Available Forecast`=%s,`Part XHTML Available For Forecast`=%s where `Part SKU`=%d",$this->data['Part Days Available Forecast'],prepare_mysql($this->data['Part XHTML Available For Forecast']),$this->id );
		//print $sql;
		mysql_query($sql);

	}

	function update_days_until_out_of_stock() {
		$this->get_days_until_out_of_stock();
	}
	function get_days_until_out_of_stock() {

		if ($this->data['Part Current Stock']==0) {
			$days=0;
			$days_formated='0';
			return array($days,$days_formated);
		}


		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`='Associate' order by `Date` desc"
			,$this->id);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$date=$row['Date'];
			$interval=(date('U')-strtotime($date))/3600/24;
			if ($interval<21) {
				$qty=$this->data['Part Total Provided']+$this->data['Part Total Lost'];
				$qty_per_day=$qty/$interval;
				$days=$this->data['Part Current Stock']/$qty_per_day;
				$days_formated=$days.' '._('days');
				return array($days,$days_formated);

			}


		} else {
			$days=0;
			$days_formated='ND';
			return array($days,$days_formated);
		}

		//include_once('class.TimeSeries.php');
		/*

		$sql=sprintf("select `First Day` from kbase.`Week Dimension` where `Year Week`=%s",date("YW"));
		$res=mysql_query($sql);
		$no_data=true;
		if ($row=mysql_fetch_array($res)) {
			$date=date("Y-m-d",strtotime($row['First Day'].' -1 day'));
		}
		list($stock,$value)=$this->get_stock($date);
		print "$stock,$value\n";



		// $tm=new TimeSeries(array('m','part sku '.$row['Part SKU']));
		//  $tm->get_values();$tm->save_values();
		//  $tm->forecast();

		$sql=sprintf("select `Time Series Value` from `Time Series Dimension` where `Time Series Frequency`='Weekly' and `Times Series Name`='SkuS' and `Time Series Name Key`=%d  and `Time Series Type`='Forecast' order by `Time Series Date`",$this->id);


		$resmysql_query($sql);
		$future_stock='';
		while ($row=mysql_fetch_array($res)) {

		}
*/



	}


	function update_estimated_future_cost() {
		list($avg_cost,$min_cost)=$this->get_estimated_future_cost();



		$sql=sprintf("update `Part Dimension` set `Part Average Future Cost Per Unit`=%s,`Part Minimum Future Cost Per Unit`=%s where `Part SKU`=%d "
			,prepare_mysql($avg_cost)
			,prepare_mysql($min_cost)
			,$this->id);

		//print "$sql\n";
		mysql_query($sql);
	}

	function get_formated_unit_cost($date=false) {

		return money($this->get_unit_cost($date));
	}


	function get_unit_cost($date=false) {



		if ($date) {
			// print "from date";

			$sql=sprintf("select AVG(`SPH Case Cost`/`SPH Units Per Case`*`Supplier Product Units Per Part`) as cost
                         from `Supplier Product Dimension` SP
                         left join `Supplier Product Part Dimension` SPPD  on (SP.`Supplier Product ID`=SPPD.`Supplier Product ID` )
                         left join `Supplier Product Part List` B  on (SPPD.`Supplier Product Part Key`=B.`Supplier Product Part Key` )
                         left join  `Supplier Product History Dimension` SPHD on (SPHD.`Supplier Product ID`=SP.`Supplier Product ID`)
                         where `Part SKU`=%d and
                         (
                         ( `Supplier Product Part Most Recent`='Yes'  and `Supplier Product Part Valid From`<=%s ) or
                         ( `Supplier Product Part Most Recent`='No' and `Supplier Product Part Valid From`<=%s and `Supplier Product Part Valid To`>=%s) ) and
                         (`SPH Valid From`<=%s and `SPH Valid To`>=%s)
                         ",
				$this->sku
				,prepare_mysql($date)
				,prepare_mysql($date)
				,prepare_mysql($date)
				,prepare_mysql($date)
				,prepare_mysql($date)
			);



			//   print "$sql\n\n";
			//exit;
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				if (is_numeric($row['cost']))
					return $row['cost'];
			}
		}
		// print "not found in date";

		$sql=sprintf("select AVG(`SPH Case Cost`/`SPH Units Per Case`*`Supplier Product Units Per Part`) as cost
                     from `Supplier Product Dimension` SP
                     left join `Supplier Product Part Dimension` SPPD  on (SP.`Supplier Product ID`=SPPD.`Supplier Product ID` )
                     left join `Supplier Product Part List` B  on (SPPD.`Supplier Product Part Key`=B.`Supplier Product Part Key` )
                     left join  `Supplier Product History Dimension` SPHD ON (SPHD.`SPH Key`=SP.`Supplier Product Current Key`)
                     where `Part SKU`=%d and `Supplier Product Part Most Recent`='Yes' ",$this->sku);
		//print "$sql\n\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			return $row['cost'];
		}

		print "Error can not fount part (SKU".$this->id.") unit cost on $dates\n";

		return 0;




	}

	function get_estimated_future_cost() {
		$sql=sprintf("select min(`Supplier Product Cost Per Case`*`Supplier Product Units Per Part`/`Supplier Product Units Per Case`) as min_cost ,avg(`Supplier Product Cost Per Case`*`Supplier Product Units Per Part`/`Supplier Product Units Per Case`) as avg_cost   from `Supplier Product Part List` SPPL left join  `Supplier Product Part Dimension` SPPD on (  SPPL.`Supplier Product Part Key`=SPPD.`Supplier Product Part Key`)    left join  `Supplier Product Dimension` SPD  on (SPPD.`Supplier Product ID`=SPD.`Supplier Product ID`)      where `Part SKU`=%d and `Supplier Product Part Most Recent`='Yes'",$this->sku);
		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if (is_numeric($row['avg_cost']))
				$avg_cost=$row['avg_cost'];
			else
				$avg_cost='';
			if (is_numeric($row['min_cost']))
				$min_cost=$row['min_cost'];
			else
				$min_cost='';

		} else {
			$avg_cost='';
			$min_cost='';
		}

		// print "($avg_cost,$min_cost\n";
		return array($avg_cost,$min_cost);

	}

	function update_used_in() {
		$used_in_products='';
		$raw_used_in_products='';
		$sql=sprintf("select `Store Code`,PD.`Product ID`,`Product Code` from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` PD on (PD.`Product ID`=PPD.`Product ID`) left join `Store Dimension`  on (PD.`Product Store Key`=`Store Key`)  where PPL.`Part SKU`=%d and `Product Part Most Recent`='Yes' and `Product Record Type`='Normal' order by `Product Code`,`Store Code`",$this->data['Part SKU']);
		$result=mysql_query($sql);
		//   print "$sql\n";
		$used_in=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


			if (!array_key_exists(strtolower($row['Product Code']),$used_in))
				$used_in[strtolower($row['Product Code'])]=array();
			if (!array_key_exists($row['Store Code'],$used_in[strtolower($row['Product Code'])]))
				$used_in[strtolower($row['Product Code'])][$row['Store Code']]=array();
			$used_in[strtolower($row['Product Code'])][$row['Store Code']][$row['Product ID']]=1;

		}
		//print_r($used_in);
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
		$sql=sprintf("update `Part Dimension` set `Part XHTML Currently Used In`=%s ,`Part Currently Used In`=%s  where `Part SKU`=%d",
			prepare_mysql(_trim($used_in_products)),
			prepare_mysql(_trim($raw_used_in_products)),
			$this->id);
		//  print "$sql\n";
		mysql_query($sql);
	}

	function wrap_transactions() {

		$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where  `Part SKU`=%d  group by `Location Key`  ",$this->sku);
		$locations=array();
		$res2=mysql_query($sql);
		while ($row2=mysql_fetch_array($res2)) {
			$locations[$row2['Location Key']]=$row2['Location Key'];

		}

		if (count($locations)==0)return;


		foreach ($locations as $location_key) {
			/*
			$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`,`Inventory Transaction Key`   ",$this->sku,$location_key);
			//print "$sql\n";
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				if ($row3['Inventory Transaction Type']=='Associate') {
					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Associate') and `Date`=%s and `Location Key`=%d  "
						,$this->sku
						,prepare_mysql($row3['Date'])
						,$location_key
					);
					// print "$sql\n";
					mysql_query($sql);
				}
			}

			$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc ,`Inventory Transaction Key` desc ",$this->sku,$location_key);
			$last_itf_date='none';
			$res3=mysql_query($sql);
			//print "$sql\n";
			if ($row3=mysql_fetch_array($res3)) {
				if ($row3['Inventory Transaction Type']=='Disassociate') {
					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Disassociate') and `Date`=%s and `Location Key`=%d  "
						,$this->sku
						,prepare_mysql($row3['Date'])
						,$location_key
					);
					//print "$sql\n";
					mysql_query($sql);
				}
			}


*/


			$sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date`' ,$this->sku,$location_key);
			$first_audit_date='none';
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				$first_audit_date=($row3['Inventory Audit Date']);
			}
			//   print "\n$sql\n";
			$sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`  ",$this->sku,$location_key);
			$first_itf_date='none';
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				$first_itf_date=($row3['Date']);
			}
			// print "$sql\n";
			//print "R: $first_audit_date $first_itf_date \n ";
			if ($first_audit_date=='none' and $first_itf_date=='none') {
				//    print "\nError1 : Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']."  \n";
				//    exit;
				//    return;
				$first_date=$this->data['Part Valid From'];
				// print "\nError1 : Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']." ".$this->data['Part Valid From']." \n";

			}
			elseif ($first_audit_date=='none') {
				$first_date=$first_itf_date;
			}
			elseif ($first_itf_date=='none') {
				$first_date=$first_audit_date;
			}
			else {
				if (strtotime($first_itf_date)< strtotime($first_audit_date) )
					$first_date=$first_itf_date;
				else
					$first_date=$first_audit_date;

			}

			// $first_date=date("Y-m-d H:i:s",strtotime($first_date." -1 second"));

			//   print $first_date;


			$replace_associate=true;
			$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Date`=%d and `Inventory Transaction Type`='Associate'   ",
				$this->sku,
				$location_key,
				prepare_mysql($first_date)
			);
			//print "$sql\n";
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_array($res3)) {
				$replace_associate=false;
			}




			$part_location=new PartLocation($this->sku.'_'.$location_key);
			if ($replace_associate) {





				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate') and `Part SKU`=%d and `Location Key`=%d order by `Date`  limit 1 "
					,$this->sku
					,$location_key
				);
				mysql_query($sql);

				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Audit') and `Note` like '%%Part associated with location%%'   and  `Part SKU`=%d and `Location Key`=%d  order by `Date` limit 1 "
					,$this->sku
					,$location_key
				);
				mysql_query($sql);
				//print $sql;
				$first_date=date("Y-m-d H:i:s",strtotime($first_date." -1 second"));
				$part_location->associate(array('date'=>$first_date));
				$this->update_valid_from($first_date);
			}
			/*$location=new Location($location_key);
				$details=_('Part')." SKU".sprintf("%05d",$this->sku)." "._('associated with location').": ".$location->data['Location Code'];
				$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
					,$this->sku
					,$location_key
					,"'Associate'"
					,0
					,0
					,0
					,prepare_mysql($details)
					,prepare_mysql($first_date)

				);
				mysql_query($sql);
				print "$sql\n";
				*/







			if ($this->data['Part Status']=='Not In Use') {

				$sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date` desc' ,$this->sku,$location_key);
				$last_audit_date='none';
				$res3=mysql_query($sql);
				if ($row3=mysql_fetch_array($res3)) {
					$last_audit_date=($row3['Inventory Audit Date']);
				}

				$sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc  ",$this->sku,$location_key);
				$last_itf_date='none';
				$res3=mysql_query($sql);
				if ($row3=mysql_fetch_array($res3)) {
					$last_itf_date=($row3['Date']);
				}
				//print "$sql\n";

				if ($last_audit_date=='none' and $last_itf_date=='none') {
					print "\nError2: Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']."  \n";
					return;
				}
				elseif ($last_audit_date=='none') {
					$last_date=$last_itf_date;
				}


				elseif ($last_itf_date=='none') {

					$last_date=$last_audit_date;
				}
				else {
					if (strtotime($last_itf_date)>strtotime($last_audit_date) )
						$last_date=$last_itf_date;
					else
						$last_date=$last_audit_date;

				}



				$replace_disassociate=true;
				$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  and `Date`=%d and `Inventory Transaction Type`='Disassociate'   ",
					$this->sku,
					$location_key,
					prepare_mysql($last_date)
				);
				//print "$sql\n";
				$res3=mysql_query($sql);
				if ($row3=mysql_fetch_array($res3)) {
					$replace_disassociate=false;
				}



				if ($replace_disassociate) {


					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Disassociate') and `Part SKU`=%d and `Location Key`=%d order by `Date` desc limit 1 "
						,$this->sku
						,$location_key
					);
					mysql_query($sql);

					$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Audit') and `Note` like '%%disassociate %%'   and  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc limit 1 "
						,$this->sku
						,$location_key
					);
					mysql_query($sql);


					//$part_location->audit(0,_('Audit due to discontinued'),$last_date);

					$last_date=date("Y-m-d H:i:s",strtotime($last_date." +1 second"));
					$data=array('Date'=>$last_date,'Note'=>_('Discontinued'));
					//print_r($data);

					$part_location->disassociate($data);

				}
				$this->update_valid_to($last_date);



			}
			$this->update_stock();


		}



		//Todo wrap by valid_dates


	}

	function get_description() {
		return $this->data['Part Unit Description'];
	}

	function get_product_ids($date=false) {

		if (!$date) {
			return $this->get_current_product_ids();
		}
		$product_ids=array();

		$sql=sprintf("select  `Product ID` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and  `Product Part Valid From`<=%s  and `Product Part Most Recent`='Yes'  "
			,$this->sku
			,prepare_mysql($date)

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_ids[$row['Product ID']]= $row['Product ID'];
		}

		$sql=sprintf("select  `Product ID` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and `Product Part Valid From`<=%s  and `Product Part Valid To`>=%s and `Product Part Most Recent`='No'  "
			,$this->sku
			,prepare_mysql($date)
			,prepare_mysql($date)
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_ids[$row['Product ID']]= $row['Product ID'];
		}

		return $product_ids;
	}

	function get_current_product_ids() {
		$sql=sprintf("select `Product Part Dimension`.`Product ID` from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d and `Product Part Most Recent`='Yes' ",$this->sku);
		// print $sql;
		$result=mysql_query($sql);
		$product_ids=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$product_ids[$row['Product ID']]= $row['Product ID'];
		}
		return $product_ids;
	}






	function get_product_part_list($date=false) {

		if (!$date) {
			$sql=sprintf("select * from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and  `Product Part Most Recent`='Yes'  "
				,$this->sku

			)
			;  $result=mysql_query($sql);
			$product_part_list=array();
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$product_part_list[$row['Product Part Key']]= $row;
			}
			return $product_part_list;
		}

		$product_part_list=array();
		$sql=sprintf("select * from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and  `Product Part Valid From`<=%s  and `Product Part Most Recent`='Yes'  "
			,$this->sku
			,prepare_mysql($date)

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_part_list[$row['Product Part Key']]= $row;
		}

		$sql=sprintf("select * from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  and `Product Part Valid From`<=%s  and `Product Part Valid To`<=%s and `Product Part Most Recent`='No'  "
			,$this->sku
			,prepare_mysql($date)
			,prepare_mysql($date)
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$product_part_list[$row['Product Part Key']]= $row;
		}

		return $product_part_list;
	}

	function get_current_product_part_list() {
		$sql=sprintf("select * from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d and `Product Part Most Recent`='Yes' ",$this->data['Part SKU']);
		// print $sql;
		$result=mysql_query($sql);
		$product_part_list=array();
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$product_part_list[$row['Product Part Key']]= $row;
		}
		return $product_part_list;
	}





	function update_product_part_list_dates() {

		$part_from=$this->data['Part Valid From'];
		$part_to=$this->data['Part Valid To'];

		foreach ($this->get_product_ids()   as $pid) {

			$product=new Product('pid',$pid);

			$product_from=$product->data['Product Valid From'];
			$product_to=$product->data['Product Valid To'];
			$store_key=$product->data['Product Store Key'];

			$from=$part_from;

			if ($this->data['Part Status']=='In Use') {
				$to='';
			} else {
				$to=$part_to;
				if (strtotime($to)<strtotime($product_to))
					$to=$product_to;
			}

			$from=$part_from;
			if (strtotime($from)>strtotime($product_from))
				$from=$product_from;

			$sql=sprintf("select  PPD.`Product Part Key` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU`=%d  and PPD.`Product ID`=%d "
				,$this->sku,$pid);
			$res2=mysql_query($sql);

			if ($row2=mysql_fetch_array($res2)) {

				// $status='No';
				// if ($to=='')
				//    $status='Yes';

				$sql=sprintf("update `Product Part Dimension` set `Product Part Valid From`=%s , `Product Part Valid To`=%s  where `Product Part Key`=%d"
					,prepare_mysql($from)
					,prepare_mysql($to)
					,$row2['Product Part Key']
				);

				if (!mysql_query($sql))
					print "$sql\n";

			}

		}





	}

	function update_full_search() {

		$first_full_search=$this->get_sku().' '.strip_tags($this->data['Part Unit Description']);
		$second_full_search=strip_tags($this->data['Part XHTML Currently Supplied By']);

		$sql=sprintf("insert into `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`)  values  (%s,'Part',%d,%s,%s,%s,%s,%s) on duplicate key
                     update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s "
			,0
			,$this->sku
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->get_sku(),false)
			,prepare_mysql($this->data['Part Unit Description'],false)
			,prepare_mysql('',false)
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->get_sku(),false)
			,prepare_mysql($this->data['Part Unit Description'],false)

			,prepare_mysql('',false)
		);
		mysql_query($sql);

	}

	function update_supplied_by() {
		$supplied_by='';
		$sql=sprintf("select SPD.`Supplier Product ID`,  `Supplier Product Code`,  SD.`Supplier Key`,SD.`Supplier Code` from `Supplier Product Part List` SPPL left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product ID`=SPPD.`Supplier Product ID`) left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPD.`Supplier Key`) where `Part SKU`=%d  order by `Supplier Key`;",
			$this->data['Part SKU']);
		$result=mysql_query($sql);
		//print "$sql\n";
		$supplier=array();
		$current_supplier='_';
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$_current_supplier=$row['Supplier Key'];
			if ($_current_supplier!=$current_supplier) {
				$supplied_by.=sprintf(', <a href="supplier.php?id=%d">%s</a>(<a href="supplier_product.php?pid=%d">%s</a>',
					$row['Supplier Key'],
					$row['Supplier Code'],
					$row['Supplier Product ID'],
					$row['Supplier Product Code']);
				$current_supplier=$_current_supplier;
			} else {
				$supplied_by.=sprintf(', <a href="supplier_product.php?pid=%d">%s</a>',
					$row['Supplier Product ID'],
					$row['Supplier Product Code']
				);

			}

		}
		$supplied_by.=")";

		$supplied_by=_trim(preg_replace('/^, /','',$supplied_by));
		if ($supplied_by=='')
			$supplied_by=_('Unknown Supplier');


		$sql=sprintf("update `Part Dimension` set `Part XHTML Currently Supplied By`=%s where `Part SKU`=%d",prepare_mysql(_trim($supplied_by)),$this->id);
		//       print "$sql\n";exit;
		if (!mysql_query($sql))
			exit("error can no suplied by part 498239048");


	}



	function get_warehouse_keys() {
		$warehouse_keys=array();

		$sql=sprintf("select `Warehouse Key` from `Part Warehouse Bridge` where `Part SKU`=%d",
			$this->sku
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$warehouse_keys[$row['Warehouse Key']]=$row['Warehouse Key'];
		}

		return $warehouse_keys;

	}

	function get_next_shipments() {
		$next_shipments=array();

		return $next_shipments;

	}

	function remove_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Part' and `Subject Key`=%d  and `Image Key`=%d",$this->sku,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("delete from `Image Bridge` where `Subject Type`='Part' and `Subject Key`=%d  and `Image Key`=%d",$this->sku,$image_key);
			mysql_query($sql);
			$this->updated=true;


			$number_images=$this->get_number_of_images();

			if ($number_images==0) {
				$main_image_src='';
				$main_image_key=0;
				$this->data['Product Family Main Image']='art/nopic.png';
				$this->data['Product Family Main Image Key']=$main_image_key;
				$sql=sprintf("update `Part Dimension` set `Part Main Image`=%s ,`Part Image Key`=%d where `Part SKU`=%d",
					prepare_mysql($main_image_src),
					$main_image_key,
					$this->sku
				);

				mysql_query($sql);
			}else if ($row['Is Principal']=='Yes') {

					$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Part' and `Subject Key`=%d  ",$this->sku);
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



		$sql=sprintf("update `Image Bridge` set `Image Caption`=%s where  `Subject Type`='Part' and `Subject Key`=%d  and `Image Key`=%d"
			,prepare_mysql($value)
			,$this->sku,$image_key);
		mysql_query($sql);
		//print $sql;
		if (mysql_affected_rows()) {
			$this->new_value=$value;
			$this->updated=true;
		} else {
			$this->msg=_('No change');

		}

	}
	function get_images_slidesshow() {
		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Part' and   `Subject Key`=%d",$this->sku);
		$res=mysql_query($sql);
		$images_slideshow=array();
		while ($row=mysql_fetch_array($res)) {
			if ($row['Image Height']!=0)
				$ratio=$row['Image Width']/$row['Image Height'];
			else
				$ratio=1;
			// print_r($row);
			$images_slideshow[]=array('name'=>$row['Image Filename'],'small_url'=>'image.php?id='.$row['Image Key'].'&size=small','thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail','filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
		}
		// print_r($images_slideshow);

		return $images_slideshow;
	}
	function get_main_image_key() {

		return $this->data['Part Main Image Key'];
	}
	function update_main_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Part' and `Subject Key`=%d  and `Image Key`=%d",$this->sku,$image_key);
		$res=mysql_query($sql);
		if (!mysql_num_rows($res)) {
			$this->error=true;
			$this->msg='image not associated';
		}

		$sql=sprintf("update `Image Bridge` set `Is Principal`='No' where `Subject Type`='Part' and `Subject Key`=%d  ",$this->sku);
		mysql_query($sql);
		$sql=sprintf("update `Image Bridge` set `Is Principal`='Yes' where `Subject Type`='Part' and `Subject Key`=%d  and `Image Key`=%d",$this->sku,$image_key);
		mysql_query($sql);


		$main_image_src='image.php?id='.$image_key.'&size=small';
		$main_image_key=$image_key;

		$this->data['Part Main Image']=$main_image_src;
		$this->data['Part Main Image Key']=$main_image_key;
		$sql=sprintf("update `Part Dimension` set `Part Main Image`=%s ,`Part Main Image Key`=%d where `Part SKU`=%d",
			prepare_mysql($main_image_src),
			$main_image_key,
			$this->sku
		);

		mysql_query($sql);

		$this->updated=true;

	}
	function get_number_of_images() {
		$number_of_images=0;
		$sql=sprintf("select count(*) as num from `Image Bridge` where `Subject Type`='Part' and `Subject Key`=%d ",$this->sku);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_of_images=$row['num'];
		}
		return $number_of_images;
	}
	function add_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Part' and `Subject Key`=%d  and `Image Key`=%d",$this->sku,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->nochange=true;
			$this->msg=_('Image already uploaded');
			return;
		}


		$number_images=$this->get_number_of_images();
		if ($number_images==0) {
			$principal='Yes';
		} else {
			$principal='No';
		}

		$sql=sprintf("insert into `Image Bridge` values ('Part',%d,%d,%s,'')"
			,$this->sku
			,$image_key
			,prepare_mysql($principal)

		);

		mysql_query($sql);

		if ($principal=='Yes') {
			$this->update_main_image($image_key);
		}


		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Part' and   `Subject Key`=%d and  PIB.`Image Key`=%d"
			,$this->sku
			,$image_key
		);

		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			if ($row['Image Height']!=0)
				$ratio=$row['Image Width']/$row['Image Height'];
			else
				$ratio=1;
			$this->new_value=array('name'=>$row['Image Filename'],'small_url'=>'image.php?id='.$row['Image Key'].'&size=small','thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail','filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
			// $this->images_slideshow[]=$this->new_value;
		}

		$this->updated=true;
		$this->msg="image added";
	}


	function update_number_transactions() {



		$transactions=array('all_transactions'=>0,'in_transactions'=>0,'out_transactions'=>0,'audit_transactions'=>0,'oip_transactions'=>0,'move_transactions'=>0);
		$sql=sprintf("select sum(if(`Inventory Transaction Type` not in ('Move In','Move Out','Associate','Disassociate'),1,0))  as all_transactions , sum(if(`Inventory Transaction Type`='Not Found' or `Inventory Transaction Type`='No Dispatched' or `Inventory Transaction Type`='Audit',1,0)) as audit_transactions,sum(if(`Inventory Transaction Type`='Move',1,0)) as move_transactions,sum(if(`Inventory Transaction Type`='Sale' or `Inventory Transaction Type`='Broken' or  `Inventory Transaction Type`='Other Out' or `Inventory Transaction Type`='Lost',1,0)) as out_transactions, sum(if(`Inventory Transaction Type`='Order In Process',1,0)) as oip_transactions, sum(if(`Inventory Transaction Type`='In',1,0)) as in_transactions from `Inventory Transaction Fact` where `Part SKU`=%d",
			$this->sku);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Part Transactions']=$row['all_transactions'];
			$this->data['Part Transactions In']=$row['in_transactions'];
			$this->data['Part Transactions Out']=$row['out_transactions'];
			$this->data['Part Transactions Audit']=$row['audit_transactions'];
			$this->data['Part Transactions OIP']=$row['oip_transactions'];
			$this->data['Part Transactions Move']=$row['move_transactions'];

			$sql=sprintf("Update `Part Dimension` set `Part Transactions`=%d ,`Part Transactions In`=%d,`Part Transactions Out`=%d ,`Part Transactions Audit`=%d,`Part Transactions OIP`=%d ,`Part Transactions Move`=%d where `Part SKU`=%d ",
				$this->data['Part Transactions'],
				$this->data['Part Transactions In'],
				$this->data['Part Transactions Out'],
				$this->data['Part Transactions Audit'],
				$this->data['Part Transactions OIP'],
				$this->data['Part Transactions Move'],
				$this->sku

			);
			//print "$sql\n";
			mysql_query($sql);
		}


	}

	function delete() {


	}

	function get_categories() {


		$part_categories=array();


		$sql=sprintf("select C.`Category Key`,`Category Label` from `Category Dimension` C left join `Category Bridge` B on (B.`Category Key`=C.`Category Key`) where `Subject`='Part' and `Subject Key`=%d ",$this->sku);
		// print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$part_categories[]=array('category_key'=>$row['Category Key'],'category_label'=>$row['Category Label']);
		}

		return $part_categories;
	}

}
