<?php
include_once 'class.Store.php';
include_once 'class.Family.php';
include_once 'class.Department.php';


class TimeSeries {

	public $freq=false;
	public $name=false;

	public $name_key=0;
	public $name_key2=0;
	public $parent_key=0;
	public $values=array();
	public $error=false;
	public $label='';
	public $no_negative_values=true;
	public $no_data=true;
	public $to_present=false;
	public $metadata='';

	function TimeSeries($arg) {

		if (!is_array($arg) or !(count($arg)==2  or count($arg)==3)  )
			return;

		foreach ($arg as $key=>$value) {
			if ($key==0 or preg_match('/freq|freq|f/i',$key)) {
				$this->freq=$value;
				if (preg_match('/month|monthly|^m$/i',$value)) {
					$this->freq='Monthly';
					$this->frequency=12;
				}
				if (preg_match('/week|weekly|^w$/i',$value)) {
					$this->freq='Weekly';
					$this->frequency=52;
				}
				if (preg_match('/day|daily|^d$/i',$value)) {
					$this->freq='Daily';
					$this->frequency=365;
				}
				if (preg_match('/quarter|quarterly|^q$/i',$value)) {
					$this->freq='Quarterly';
					$this->frequency=4;
				}
				if (preg_match('/annualy|year|yearly|^y$/i',$value)) {
					$this->freq='Yearly';
					$this->frequency=1;
				}
			}
			if ($key==1 or preg_match('/name|n/i',$key)) {
				$this->name=$value;
			}
			if ($key==3 or preg_match('/key|k|id/i',$key)) {
				$this->name_key=$value;
			}

		}

		if (!$this->name or !$this->freq)
			return;
			
			
			
			
		if (preg_match('/contact population \((\d)+\)\s*?/i',$this->name,$match)) {
			$store_key_array=array();



			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$store_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$store_keys.=sprintf("%d,",$key);
						$store_key_array[]=$key;

					}
				}
				$store_keys=preg_replace('/,$/',')',$store_keys);
			}

			//$this->keys=$keys;
			//print_r($store_keys);

			$this->name_key=array_pop($keys);
			$this->name='contact population';

			$this->label=_('CoP')." (".$this->name_key.")";


		}elseif (preg_match('/customer population \((\d)+\)\s*?/i',$this->name,$match)) {
			$store_key_array=array();



			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$store_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$store_keys.=sprintf("%d,",$key);
						$store_key_array[]=$key;

					}
				}
				$store_keys=preg_replace('/,$/',')',$store_keys);
			}

			//$this->keys=$keys;
			//print_r($store_keys);

			$this->name_key=array_pop($keys);
			$this->name='customer population';
			//$this->count='count(*)';
			//$this->date_field='`Invoice Date`';
			//$this->table='`Invoice Dimension`';
			//$this->value_field='`Invoice Currency Exchange`*`Invoice Total Net Amount`';
			//$this->max_forecast_bins=12;
			//$this->where=sprintf(' and `Invoice Category Key`=%d',$category_key);
			$this->label=_('CP')." (".$this->name_key.")";


		}elseif (preg_match('/Site No Users Requests \((\d)+\)\s*?/i',$this->name,$match)) {
			$store_key_array=array();



			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$store_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$store_keys.=sprintf("%d,",$key);
						$store_key_array[]=$key;

					}
				}
				$store_keys=preg_replace('/,$/',')',$store_keys);
			}

			//$this->keys=$keys;
			//print_r($store_keys);

			$this->name_key=array_pop($keys);
			$this->name='Site No Users Requests';
			//$this->count='count(*)';
			//$this->date_field='`Invoice Date`';
			//$this->table='`Invoice Dimension`';
			//$this->value_field='`Invoice Currency Exchange`*`Invoice Total Net Amount`';
			//$this->max_forecast_bins=12;
			//$this->where=sprintf(' and `Invoice Category Key`=%d',$category_key);
			$this->label=_('SNUR')." (".$this->name_key.")";


		}elseif (preg_match('/Site Users Requests \((\d)+\)\s*?/i',$this->name,$match)) {
			$store_key_array=array();



			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$store_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$store_keys.=sprintf("%d,",$key);
						$store_key_array[]=$key;

					}
				}
				$store_keys=preg_replace('/,$/',')',$store_keys);
			}

			//$this->keys=$keys;
			//print_r($store_keys);

			$this->name_key=array_pop($keys);
			$this->name='Site Users Requests';
			//$this->count='count(*)';
			//$this->date_field='`Invoice Date`';
			//$this->table='`Invoice Dimension`';
			//$this->value_field='`Invoice Currency Exchange`*`Invoice Total Net Amount`';
			//$this->max_forecast_bins=12;
			//$this->where=sprintf(' and `Invoice Category Key`=%d',$category_key);
			$this->label=_('SUR')." (".$this->name_key.")";


		}elseif (preg_match('/^invoice(\s|_)category:?/i',$this->name)) {

			$category=_trim(preg_replace('/^invoice(\s|_)category:?/','',$this->name));
			$category=preg_replace('/^\(/','',$category);
			$category=preg_replace('/\)$/','',$category);


			if (!is_numeric($category))
				$sql=sprintf("select *  from `Invoice Category Dimension` where `Invoice Category Code`=%s",prepare_mysql($category));
			else
				$sql=sprintf("select *  from `Invoice Category Dimension` where `Invoice Category Key`=%d",$category);

			//print $sql;

			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$category_key=$row['Invoice Category Key'];
				$category_code=$row['Invoice Category Code'];

			} else {
				$this->error=true;
				$this->msg="Invoice category not found";

				return;
			}

			$this->name_key=$category_key;
			$this->name='invoice cat';
			$this->count='count(*)';
			$this->date_field='`Invoice Date`';
			$this->table='`Invoice Dimension`';
			$this->value_field='`Invoice Currency Exchange`*`Invoice Total Net Amount`';
			$this->max_forecast_bins=12;
			$this->where=sprintf(' and `Invoice Category Key`=%d',$category_key);
			$this->label=_('Sales')." (".$category_code.")";

		}
		elseif (preg_match('/invoices? profit/i',$this->name)) {
			$this->name='profit';
			$this->count='count(*)';
			$this->date_field='`Invoice Date`';
			$this->table='`Order Transaction Fact`';
			$this->value_field='`Invoice Currency Exchange`*(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';
			$this->max_forecast_bins=12;
			$this->where='and `Current Dispatching State`="Dispatched"';
			$this->label=_('Profit');
		}
		elseif (preg_match('/^invoices?/i',$this->name)) {
			$this->name='invoices';
			$this->count='count(*)';
			$this->date_field='`Invoice Date`';
			$this->table='`Invoice Dimension`';
			$this->value_field='`Invoice Currency Exchange`*`Invoice Total Net Amount`';
			$this->max_forecast_bins=12;
			$this->where='';
			$this->label=_('Sales');
		}
		elseif (preg_match('/(product department|department) \((\d|,)+\) sales?$/i',$this->name,$match)
			or preg_match('/(product department|department) \((\d|,)+\) profit?$/i',$this->name,$match)

		) {

			$department_key_array=array();
			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$department_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$department_keys.=sprintf("%d,",$key);
						$department_key_array[]=$key;

					}
				}


				$department_keys=preg_replace('/,$/',')',$department_keys);

			}
			$num_keys=count($department_key_array);
			if ( $num_keys==0) {
				$this->error=true;
				return;
			}
			// print "--------";
			if (preg_match('/sales?$/i',$this->name)) {
				$tipo='sales';
				$name='PDS';
			} else {
				$tipo='profit';
				$name='PDP';
			}

			if ($num_keys>1) {
				$this->name=$name.$department_keys;
				foreach ( $department_key_array as $key) {
					$department=new Department($key);
					$this->label.=','.$department->data['Product Department Code'];
				}
				$this->label=preg_replace('/^,/','',$this->label);
			} else {
				$this->name=$name;
				$this->name_key=preg_replace('/\(|\)/','',$department_keys);
				$department=new Department($this->name_key);
				$this->label=$department->data['Product Department Code'];
				$this->parent_key=$department->data['Product Department Store Key'];
			}

			$this->count='count(Distinct `Order Key`)';
			$this->date_field='`Invoice Date`';
			$this->table='`Order Transaction Fact` OTF left join `Product History Dimension` HP  on (OTF.`Product Key`=HP.`Product Key`) left join `Product Dimension` P  on (P.`Product ID`=HP.`Product ID`) ';
			if ($tipo=='sales')
				$this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
			else
				$this->value_field='(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';


			$this->where=sprintf(" and `Product Main Department Key` in %s ",$department_keys);
			$this->max_forecast_bins=12;

		}
		elseif (preg_match('/(product family|family) \((\d|,)+\) sales?/i',$this->name,$match)
			or preg_match('/(product family|family) \((\d|,)+\) profit?$/i',$this->name,$match)
		) {

			$family_key_array=array();
			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$family_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$family_keys.=sprintf("%d,",$key);
						$family_key_array[]=$key;

					}
				}


				$family_keys=preg_replace('/,$/',')',$family_keys);

			}
			$num_keys=count($family_key_array);
			if ( $num_keys==0) {
				$this->error=true;
				return;
			}

			if (preg_match('/sales?$/i',$this->name)) {
				$tipo='sales';
				$name='PFS';
			} else {
				$tipo='profit';
				$name='PFP';
			}


			if ($num_keys>1) {
				$this->name=$name.$family_keys;
				foreach ( $family_key_array as $key) {
					$family=new Family($key);
					$this->label.=','.$family->data['Product Family Code'];
				}
				$this->label=preg_replace('/^,/','',$this->label);
			} else {
				$this->name=$name;
				$this->name_key=preg_replace('/\(|\)/','',$family_keys);
				$family=new Family($this->name_key);
				$this->label=$family->data['Product Family Code'];
				$this->parent_key=$family->data['Product Family Main Department Key'];
			}

			$this->count='count(Distinct `Order Key`)';
			$this->date_field='`Invoice Date`';
			$this->table='`Order Transaction Fact`  OTF left join `Product History Dimension` HP  on (OTF.`Product Key`=HP.`Product Key`) left join `Product Dimension` P  on (P.`Product ID`=HP.`Product ID`) ';
			if ($tipo=='sales')
				$this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
			else
				$this->value_field='(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';



			$this->where=sprintf(" and `Product Family Key` in %s ",$family_keys);
			$this->max_forecast_bins=12;
		}
		elseif (preg_match('/store \((\d|,)+\) sales?/i',$this->name,$match)
			or preg_match('/store \((\d|,)+\) profit/i',$this->name,$match)
		) {
			$store_key_array=array();
			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$store_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$store_keys.=sprintf("%d,",$key);
						$store_key_array[]=$key;

					}
				}
				$store_keys=preg_replace('/,$/',')',$store_keys);
			}

			$num_keys=count($store_key_array);
			if ($num_keys==0) {
				$this->error=true;
				return;
			}
			$default_currency=false;
			if (preg_match('/\(DC\)/i',$this->name)) {
				$default_currency=true;
			}

			if (!$default_currency) {
				if (preg_match('/sales?$/i',$this->name)  ) {
					$tipo='sales';
					$name='SS';
				} else {
					$tipo='profit';
					$name='SP';
				}
			} else {
				if (preg_match('/sales?$/i',$this->name)  ) {
					$tipo='sales_dc';
					$name='SS_DC';
				} else {
					$tipo='profit_dc';
					$name='SP_DC';
				}


			}

			if ($num_keys>1) {
				$this->name=$name.$store_keys;
				foreach ( $store_key_array as $key) {
					$store=new Store($key);
					$this->label.=','.$store->data['Store Code'];
				}
				$this->label=preg_replace('/^,/','',$this->label);
			} else {
				$this->name=$name;
				$this->name_key=preg_replace('/\(|\)/','',$store_keys);
				$store=new Store($this->name_key);
				$this->label=$store->data['Store Code'];
			}

			$this->count='count(Distinct `Order Key`)';
			$this->date_field='`Invoice Date`';
			$this->table='`Order Transaction Fact`  OTF left join `Product History Dimension` HP  on (OTF.`Product Key`=HP.`Product Key`) left join `Product Dimension` P  on (P.`Product ID`=HP.`Product ID`) ';

			if ($tipo=='sales')
				$this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
			elseif ($tipo=='profit')
				$this->value_field='(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';
			elseif ($tipo=='sales_dc')
				$this->value_field="`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`)";
			elseif ($tipo=='profit_dc')
				$this->value_field='`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';


			$this->where=sprintf(" and `Store Key` in %s ",$store_keys);
			$this->max_forecast_bins=12;
		}
		elseif (preg_match('/product id \((\d|,)+\) sales?/i',$this->name,$match)
			or preg_match('/product id \((\d|,)+\) profit?$/i',$this->name,$match)
		) {
			$product_key_array=array();
			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);
				$product_keys='(';
				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$product_keys.=sprintf("%d,",$key);
						$product_key_array[]=$key;
					}
				}
				$product_keys=preg_replace('/,$/',')',$product_keys);
			}
			$num_keys=count($product_key_array);
			if ( $num_keys==0) {
				$this->error=true;
				return;
			}


			if (preg_match('/sales?$/i',$this->name)) {
				$tipo='sales';
				$name='PidS';
			} else {
				$tipo='profit';
				$name='PidP';
			}


			if ($num_keys>1) {
				$this->name=$name.$product_keys;
				foreach ( $product_key_array as $key) {
					$product=new Product('pid',$key);
					$this->label.=','.$product->data['Product Code']." (".$product->data['Product ID'].")";
				}
				$this->label=preg_replace('/^,/','',$this->label);
			} else {
				$this->name=$name;
				$this->name_key=preg_replace('/\(|\)/','',$product_keys);
				$product=new Product('pid',$this->name_key);
				$this->label=$product->data['Product Code']." (".$product->data['Product ID'].")";
				$this->parent_key=$product->data['Product Family Key'];
			}

			$this->count='count(Distinct `Order Key`)';
			$this->date_field='`Invoice Date`';
			$this->table='`Order Transaction Fact` OTF left join `Product History Dimension` HP  on (OTF.`Product Key`=HP.`Product Key`)   left join `Product Dimension` P  on (P.`Product ID`=HP.`Product ID`)   ';



			if ($tipo=='sales')
				$this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
			else
				$this->value_field='(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';


			$this->where=sprintf(" and HP.`Product ID` in %s ",$product_keys);
			$this->max_forecast_bins=12;
		}
		elseif (preg_match('/product code \((\d|,)+\) sales?/i',$this->name,$match)
			or preg_match('/product code \((\d|,)+\) profit?$/i',$this->name,$match)
		) {
			$product_key_array=array();
			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);
				$product_keys='(';
				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$product_keys.=sprintf("%d,",$key);
						$product_key_array[]=$key;
					}
				}
				$product_keys=preg_replace('/,$/',')',$product_keys);
			}
			$num_keys=count($product_key_array);
			if ( $num_keys==0) {
				$this->error=true;
				return;
			}

			if (preg_match('/sales?$/i',$this->name)) {
				$tipo='sales';
				$name='PcodeS';
			} else {
				$tipo='profit';
				$name='PcodeP';
			}


			if ($num_keys>1) {
				$this->name=$name.$product_keys;
				$product_codes="(";
				foreach ( $product_key_array as $key) {
					$product=new Product($key);
					$this->label.=','.$product->data['Product Code'];
					$product_codes.=prepare_mysql($product->data['Product Code']).",";
				}
				$product_codes=preg_replace('/,$/',')',$product_codes);
				$this->label=preg_replace('/^,/','',$this->label);
			} else {
				$this->name=$name;
				$this->name_key=preg_replace('/\(|\)/','',$product_keys);
				$product=new Product($this->name_key);
				$this->label=$product->data['Product Code'];
				$this->parent_key=$product->data['Product Family Key'];
				$product_codes="(".prepare_mysql($product->data['Product Product Code']).")";
			}

			$this->count='count(Distinct `Order Key`)';
			$this->date_field='`Invoice Date`';
			$this->table='`Order Transaction Fact` OTF left join `Product Dimension` P  on (OTF.`Product Key`=P.`Product Key`)  ';

			if ($tipo=='sales')
				$this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
			else
				$this->value_field='(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';

			$this->where=sprintf(" and `Product Code` in %s ",$product_codes);
			$this->max_forecast_bins=12;
		}
		elseif (preg_match('/product id \((\d|,)+\) sales?/i',$this->name,$match)
			or preg_match('/product id \((\d|,)+\) profit?$/i',$this->name,$match)
		) {
			$product_key_array=array();
			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);
				$product_keys='(';
				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$product_keys.=sprintf("%d,",$key);
						$product_key_array[]=$key;
					}
				}
				$product_keys=preg_replace('/,$/',')',$product_keys);
			}
			$num_keys=count($product_key_array);
			if ( $num_keys==0) {
				$this->error=true;
				return;
			}


			if (preg_match('/sales?$/i',$this->name)) {
				$tipo='sales';
				$name='PidS';
			} else {
				$tipo='profit';
				$name='PidP';
			}


			if ($num_keys>1) {
				$this->name=$name.$product_keys;
				foreach ( $product_key_array as $key) {
					$product=new Product('pid',$key);
					$this->label.=','.$product->data['Product Code']." (".$product->data['Product ID'].")";
				}
				$this->label=preg_replace('/^,/','',$this->label);
			} else {
				$this->name=$name;
				$this->name_key=preg_replace('/\(|\)/','',$product_keys);
				$product=new Product('pid',$this->name_key);
				$this->label=$product->data['Product Code']." (".$product->data['Product ID'].")";
				$this->parent_key=$product->data['Product Family Key'];
			}

			$this->count='count(Distinct `Order Key`)';
			$this->date_field='`Invoice Date`';
			$this->table='`Order Transaction Fact` OTF left join `Product History Dimension` HP  on (OTF.`Product Key`=HP.`Product Key`)   left join `Product Dimension` P  on (P.`Product ID`=HP.`Product ID`)   ';



			if ($tipo=='sales')
				$this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
			else
				$this->value_field='(`Invoice Transaction Gross Amount`-IFNULL(`Invoice Transaction Total Discount Amount`,0)+IFNULL(`Invoice Transaction Shipping Amount`,0)+IFNULL(`Invoice Transaction Charges Amount`,0)-IFNULL(`Cost Supplier`,0)-IFNULL(`Cost Manufacure`,0)-IFNULL(`Cost Storing`,0)-IFNULL(`Cost Handing`,0)-IFNULL(`Cost Shipping`,0))';


			$this->where=sprintf(" and HP.`Product ID` in %s ",$product_keys);
			$this->max_forecast_bins=12;
		}
		elseif (preg_match('/part sku \((\d|,)+\) required/i',$this->name,$match)) {




			$this->prepare_part_requiered_provided($match[0]);


		}    elseif (preg_match('/^part sku \(?\d+\)?/i',$this->name,$match)) {




			$this->prepare_part(preg_replace('/[^\d]/i','',$match[0]));


		} elseif (preg_match('/warehouse \((\d|,)+\) stock value/i',$this->name,$match)
			or preg_match('/warehouse \((\d|,)+\) stock value in/i',$this->name,$match)
			or preg_match('/warehouse \((\d|,)+\) stock value out/i',$this->name,$match)
		) {
			include_once 'class.Warehouse.php';


			$warehouse_key_array=array();
			if (preg_match('/\(.+\)/',$match[0],$keys)) {
				$keys=preg_replace('/\(|\)/','',$keys[0]);
				$keys=preg_split('/\s*,\s*/',$keys);

				$warehouse_keys='(';

				foreach ($keys as $key) {
					if (is_numeric($key)) {
						$warehouse_keys.=sprintf("%d,",$key);
						$warehouse_key_array[]=$key;

					}
				}
				$warehouse_keys=preg_replace('/,$/',')',$warehouse_keys);
			}

			$num_keys=count($warehouse_key_array);
			if ($num_keys==0) {
				$this->error=true;
				return;
			}


			if (preg_match('/stock value out$/i',$this->name)  ) {
				$tipo='stock_value_out';
				$name='WSVout';
			}if (preg_match('/stock value in$/i',$this->name)  ) {
				$tipo='stock_value_in';
				$name='WSVin';
			} else {
				$tipo='stock_value';
				$name='WSV';

			}


			if ($num_keys>1) {
				$this->name=$name.$warehouse_keys;
				foreach ( $warehouse_key_array as $key) {
					$warehouse=new Warehouse($key);
					$this->label.=','.$warehouse->data['Warehouse Code'];
				}
				$this->label=preg_replace('/^,/','',$this->label);
			} else {
				$this->name=$name;
				$this->name_key=preg_replace('/\(|\)/','',$warehouse_keys);
				$warehouse=new Warehouse($this->name_key);
				$this->label=$warehouse->data['Warehouse Code'];
			}

			$this->count='count(Distinct `Part SKU`)';
			$this->date_field='`Date`';
			$this->table='`Inventory Spanshot Fact`  ';

			if ($tipo=='stock_value')
				$this->value_field="`Value At Cost`";


			$this->where=sprintf(" and `Warehouse Key` in %s ",$warehouse_keys);
			$this->max_forecast_bins=12;

		}



	}

	function prepare_part($part_sku) {


		$this->keys=$part_sku;
		$tipo='stock';
		$name='SkuS';


		$this->label=$part_sku;
		$this->name=$name;

		$this->name_key=$part_sku;
		$this->label=$part_sku;
		$this->parent_key=false;

		$this->count='sum(`Quantity Sold`+`Quantity Lost`)';
		$this->date_field='`Date`';
		$this->table='`Inventory Spanshot Fact`   ';


		$this->value_field='`Quantity Sold`+`Quantity Lost`';

		$this->where=sprintf(" and `Part SKU` = %d",$part_sku);
		$this->max_forecast_bins=60;


	}




	function prepare_part_requiered_provided($keys_data) {
		$part_sku_array=array();
		if (preg_match('/\(.+\)/',$keys_data,$keys)) {
			$keys=preg_replace('/\(|\)/','',$keys[0]);
			$keys=preg_split('/\s*,\s*/',$keys);
			$part_skus='(';
			foreach ($keys as $key) {
				if (is_numeric($key)) {
					$part_skus.=sprintf("%d,",$key);
					$part_sku_array[]=$key;
				}
			}
			$part_skus=preg_replace('/,$/',')',$part_skus);
		}

		$this->keys=$keys;
		$tipo='required';
		$name='SkuR';


		$this->label=$part_skus;
		$this->name=$name.$part_skus;

		if (count($part_sku_array)==1 ) {
			$this->name_key=$part_sku_array[0];
			$part=new Part($this->name_key);
			$this->label=$part->get_sku();
		}


		$this->parent_key=false;

		$this->count='count(Distinct `Date`)';
		$this->date_field='`Date`';
		$this->table='`Inventory Transaction Fact`   ';


		$this->value_field='Required';

		$this->where=sprintf(" and `Part SKU` in %s ",$part_skus);
		$this->max_forecast_bins=12;


	}




	function get_values($options='') {

		if ($this->to_present) {
			$this->last_date=date('Y-m-d');
		} else {
			if (!$this->last_date=$this->last_date()) {

				return;
			}
		}
		switch ($this->freq) {
		case('Monthly'):

			$this->get_values_per_month();
			break;
		case('Yearly'):

			$this->get_values_per_year();
			break;
		case('Quarterly'):

			$this->get_values_per_quarter();
			break;
		case('Weekly'):

			$this->get_values_per_week();
			break;
		case('Daily'):

			$this->get_values_day_by_day($options);
			break;

		}

	}


	function save_day_values($date,$data) {

		$sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,%s,%s,%s,%s,%s,%s,'Data','','','')
  ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d , `Open`=%s,`High`=%s,`Low`=%s,`Close`=%s,`Volume`=%s,`Adj Close`=%s, `Time Series Type`='Data' ,`Time Series Tag`='' ,`Time Series Parent Key`=%d ",
			prepare_mysql($date),
			prepare_mysql($this->freq),
			prepare_mysql($this->name),
			$this->name_key,
			$this->name_key2,
			$this->parent_key,
			prepare_mysql($this->label),
			$data['value'],
			$data['count'],
			prepare_mysql($data['open']),
			prepare_mysql($data['high']),
			prepare_mysql($data['low']),
			prepare_mysql($data['close']),
			prepare_mysql($data['volume']),
			prepare_mysql($data['adj close']),
			$data['value'],
			$data['count'],
			prepare_mysql($data['open']),
			prepare_mysql($data['high']),
			prepare_mysql($data['low']),
			prepare_mysql($data['close']),
			prepare_mysql($data['volume']),
			prepare_mysql($data['adj close']),
			$this->parent_key
		);
		mysql_query($sql);

//print "$sql\n";

	}


	function save_values() {


		if ($this->no_data)
			return;

		$sql=sprintf("update `Time Series Dimension` set `Time Series Tag`='D' where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d  and `Time Series Name Second Key`=%d  "
			,prepare_mysql($this->name)
			,prepare_mysql($this->freq)
			,$this->name_key
			,$this->name_key2

		);

		if (!isset($this->first)) {
		}

		$sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,'First','','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='First' ,`Time Series Tag`='',`Time Series Parent Key`=%d "
			,prepare_mysql($this->first['date'])
			,prepare_mysql($this->freq)
			,prepare_mysql($this->name)
			,$this->name_key
			,$this->name_key2
			,$this->parent_key
			,prepare_mysql($this->label)
			,$this->first['value']
			,$this->first['count']
			,$this->first['value']
			,$this->first['count']
			,$this->parent_key
		);
		mysql_query($sql);


		foreach ($this->values as $date=>$data) {
			$sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,'Data','','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='Data' ,`Time Series Tag`='' ,`Time Series Parent Key`=%d "
				,prepare_mysql($date)
				,prepare_mysql($this->freq)
				,prepare_mysql($this->name)
				,$this->name_key
				,$this->name_key2
				,$this->parent_key
				,prepare_mysql($this->label)
				,$data['value']
				,$data['count']
				,$data['value']
				,$data['count']
				,$this->parent_key
			);
			mysql_query($sql);
		}

		if (isset($this->current)) {
			$sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,'Current','','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='Current' ,`Time Series Tag`='' ,`Time Series Parent Key`=%d "
				,prepare_mysql($this->current['date'])
				,prepare_mysql($this->freq)
				,prepare_mysql($this->name)
				,$this->name_key
				,$this->name_key2
				,$this->parent_key
				,prepare_mysql($this->label)
				,$this->current['value']
				,$this->current['count']
				,$this->current['value']
				,$this->current['count']
				,$this->parent_key
			);
			mysql_query($sql);
		}

		$sql=sprintf("delete from `Time Series Dimension`  where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d and `Time Series Tag`='D'"
			,prepare_mysql($this->name)
			,prepare_mysql($this->freq)
			,$this->name_key
			,$this->name_key2

		);

		mysql_query($sql);
		// exit;
	}

	function save_forecast($label='Forecast') {



		$sql=sprintf("delete from `Time Series Dimension`  where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d and `Time Series Type`=%s and `Time Series Metadata`=%s "
			,prepare_mysql($this->name)
			,prepare_mysql($this->freq)
			,$this->name_key
			,$this->name_key2
			,prepare_mysql($label)
			,prepare_mysql($this->metadata,false)
		);

		// print $sql;

		mysql_query($sql);

		if (!is_array($this->forecast))
			return;
		foreach ($this->forecast as $date=>$data) {
			$sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,%s,%s,'',%s)    "
				,prepare_mysql($date)
				,prepare_mysql($this->freq)
				,prepare_mysql($this->name)
				,$this->name_key
				,$this->name_key2
				,$this->parent_key
				,prepare_mysql($this->label)
				,$data['value']
				,$data['count']
				,prepare_mysql($label)
				,prepare_mysql($this->metadata,false)
				,prepare_mysql($data['deviation'])
			);
			mysql_query($sql);

		}



	}



	function targets($start_date) {
		if ($this->no_data)
			return;
		if ($this->has_only_zeros()) {

		} else {

			if ($this->freq=='Monthly') {

				$this->forecast=$this->R_script($end_date);
				$this->save_forecast('target');
			} else {


			}
		}


	}


	function forecast() {


		if ($this->no_data)
			return;


		if ($this->has_only_zeros()) {

		} else {
			if ($this->freq=='Weekly') {

				$this->forecast=$this->R_script_weekly();
				$this->save_forecast();
			}
			if ($this->freq=='Monthly') {

				$this->forecast=$this->R_script();
				$this->save_forecast();
			} else {
				$this->forecast_using_monthly_data();

			}
		}
	}

	function has_only_zeros() {
		$only_zero_values=true;
		foreach ($this->values as $key=>$data) {
			if ($data['value']!=0 or $data['count']!=0) {
				$only_zero_values=false;
				break;
			}
		}
		return $only_zero_values;


	}


	function forecast_using_monthly_data() {
		return;
	}

	function R_script($until=false,$number_period_for_forecasting=false) {

		$read=false;
		$forecast=array();

		$values='';
		$count='';

		$from=$this->first_complete_year."-".$this->first_complete_bin."-01";
		$from_time=strtotime($from);

		if (!$until)
			$until=date('Y-m-d');
		$until_time=strtotime($until);

		foreach ($this->values as $key=>$data) {
			if ($until_time>=strtotime($key)) {

				$values.=sprintf(',%f',$data['value']);
				$count.=sprintf(',%d',$data['count']);
			}
		}

		$start_year=date("Y",$from_time);
		$start_bin=date("m",$from_time);

		$number_values=count($this->values);

		if ($number_values<2)
			return array();


		if ($number_values<=6)
			$few_points=true;
		else
			$few_points=false;


		if (!$number_period_for_forecasting)
			$number_period_for_forecasting=$this->guess_number_of_forecats_bins($number_values);

		$values=preg_replace('/^,/','',$values);
		$count=preg_replace('/^,/','',$count);




		$script=sprintf("library(forecast,quietly );values=c(%s);",$values);
		if (!$few_points) {
			$script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
			$script.="fit<-ets(ts);fcast =forecast(fit,$number_period_for_forecasting);print(fcast) ;print ('--count data--');";
			$script.=sprintf("values=c(%s);",$count);
			$script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
			$script.="fit<-ets(ts);fcast = forecast(fit,$number_period_for_forecasting);print(fcast) ;";
		} else {
			$script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
			$script.="fit<-arima(ts);fcast =forecast(fit,$number_period_for_forecasting);print(fcast) ;print ('--count data--');";
			$script.=sprintf("values=c(%s);",$count);
			$script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
			$script.="fit<-arima(ts);fcast = forecast(fit,$number_period_for_forecasting);print(fcast) ;";

		}



		$cmd = "echo \"$script\" |  R --vanilla --slave -q";

		$handle = popen($cmd, "r");
		$ret = "";
		do {
			$data = fread($handle, 8192);
			if (strlen($data) == 0) {
				break;
			}
			$ret .= $data;
		} while (true);
		pclose($handle);

		if (preg_match('/--count data-/',$ret)) {

			$ret_data = preg_split('/--count data-/',$ret);

			$values_forecast_data=$ret_data[0];
			$count_forecast_data=$ret_data[1];

			$values_forecast_data = preg_split('/\n/',$values_forecast_data);
			$count_forecast_data = preg_split('/\n/',$count_forecast_data);


			$forecast_bins=0;
			foreach ($values_forecast_data as $line) {
				if ($forecast_bins>$number_period_for_forecasting)
					break;
				$line=_trim($line);
				if ($read and $line!='') {
					$regex='/^[a-z]{3}\s\d{4}\s*/i';
					if (!preg_match($regex,$line,$match))
						continue;
					$line=preg_replace($regex,'',$line);
					$date=date("Y-m-d",strtotime($match[0]));
					$data=preg_split('/\s/',$line);

					if ($this->no_negative_values) {
						foreach ($data as $_key=>$_value) {
							if (is_numeric($_value) and $_value<0)
								$data[$_key]=0;
						}
					}

					if ($data[0]==0)
						$uncertainty=0;
					else
						$uncertainty=($data[4]-$data[3])/(2*$data[0]);

					$forecast[$date]=array(
						'date'=>$date
						,'value'=>$data[0]
						,'deviation'=>$data[1].','.$data[2].','.$data[3].','.$data[4].','.$uncertainty
					);
					$forecast_bins++;
				}


				if (preg_match('/Point Forecast/i',$line))
					$read=true;
			}


			$forecast_bins=0;
			foreach ($count_forecast_data as $line) {
				if ($forecast_bins>$number_period_for_forecasting)
					break;
				$line=_trim($line);
				if ($read and $line!='') {
					$regex='/^[a-z]{3}\s\d{4}\s*/i';
					if (!preg_match($regex,$line,$match))
						continue;
					$line=preg_replace($regex,'',$line);
					$date=date("Y-m-d",strtotime($match[0]));
					$data=preg_split('/\s/',$line);

					if ($this->no_negative_values) {
						foreach ($data as $_key=>$_value) {
							if (is_numeric($_value) and $_value<0)
								$data[$_key]=0;
						}
					}

					if ($data[0]==0)
						$uncertainty=0;
					else
						$uncertainty=($data[4]-$data[3])/(2*$data[0]);

					$forecast[$date]['count']=round($data[0]);
					$forecast[$date]['deviation'].='|'.round($data[1]).','.round($data[2]).','.round($data[3]).','.round($data[4]).','.$uncertainty;
					$forecast_bins++;

				}

				if (preg_match('/Point Forecast/i',$line))
					$read=true;
			}


			return $forecast;

		}


	}

	function R_script_weekly($until=false,$number_period_for_forecasting=false) {

		$read=false;
		$forecast=array();

		$values='';
		$count='';



		$first=true;
		$last_date='';
		foreach ($this->values as $date=>$data) {
			if (date('W',strtotime($date))<53  and  date('YW',strtotime($date))!= date('YW')   ) {
				if ($first) {
					$start_year=date('Y',strtotime($date));
					$start_bin=date('W',strtotime($date));
					$first=false;
				}
				$values.=sprintf(',%f',$data['value']);
				$count.=sprintf(',%d',$data['count']);
				$last_date=$date;
			}
		}


		$number_values=count($this->values);

		if ($number_values<2)
			return array();


		if ($number_values<=6)
			$few_points=true;
		else
			$few_points=false;
		// print "values : $number_values\n";


		// if (!$number_period_for_forecasting)
		//    $number_period_for_forecasting=$this->guess_number_of_forecats_bins($number_values);

		$number_period_for_forecasting=26;
		$values=preg_replace('/^,/','',$values);
		$count=preg_replace('/^,/','',$count);




		//print $values;
		$script=sprintf("library(forecast,quietly );values=c(%s);",$values);
		// if (!$few_points) {
		//   $script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
		//  $script.="fit<-ets(ts);fcast =forecast(fit,$number_period_for_forecasting);print(fcast) ;print ('--count data--');";
		// $script.=sprintf("values=c(%s);",$count);
		//  $script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
		//  $script.="fit<-ets(ts);fcast = forecast(fit,$number_period_for_forecasting);print(fcast) ;";
		// } else {
		$script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
		$script.="fit<-arima(ts);fcast =forecast(fit,$number_period_for_forecasting);print(fcast) ;print ('--count data--');";
		$script.=sprintf("values=c(%s);",$count);
		$script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$start_year,$start_bin,$this->frequency);
		$script.="fit<-arima(ts);fcast = forecast(fit,$number_period_for_forecasting);print(fcast) ;";

		//}
		//print $script;


		$cmd = "echo \"$script\" |  R --vanilla --slave -q";

		$handle = popen($cmd, "r");
		$ret = "";
		do {
			$data = fread($handle, 8192);
			if (strlen($data) == 0) {
				break;
			}
			$ret .= $data;
		} while (true);
		pclose($handle);

		if (preg_match('/--count data-/',$ret)) {

			$ret_data = preg_split('/--count data-/',$ret);

			$values_forecast_data=$ret_data[0];
			$count_forecast_data=$ret_data[1];

			$values_forecast_data = preg_split('/\n/',$values_forecast_data);
			$count_forecast_data = preg_split('/\n/',$count_forecast_data);




			$forecast_bins=0;
			$forecast_week_number=0;
			foreach ($values_forecast_data as $line) {

				$line=_trim($line);
				if ($read and $line!='') {
					$regex='/^\d{4}\.\d+\s*/i';
					if (!preg_match($regex,$line,$match))
						continue;


					$line=preg_replace($regex,'',$line);
					$forecast_week_number++;
					$num_weeks=7*$forecast_week_number;
					$date=date("Y-m-d",strtotime($last_date." + $num_weeks days"));
					$data=preg_split('/\s/',$line);

					if ($this->no_negative_values) {
						foreach ($data as $_key=>$_value) {
							if (is_numeric($_value) and $_value<0)
								$data[$_key]=0;
						}
					}

					if ($data[0]==0)
						$uncertainty=0;
					else
						$uncertainty=($data[4]-$data[3])/(2*$data[0]);

					$forecast[$date]=array(
						'date'=>$date
						,'value'=>$data[0]
						,'deviation'=>$data[1].','.$data[2].','.$data[3].','.$data[4].','.$uncertainty
					);
					$forecast_bins++;
				}


				if (preg_match('/Point Forecast/i',$line))
					$read=true;
			}


			$forecast_bins=0;
			$forecast_week_number=0;
			foreach ($count_forecast_data as $line) {

				$line=_trim($line);
				if ($read and $line!='') {
					$regex='/^\d{4}\.\d+\s*/i';
					if (!preg_match($regex,$line,$match))
						continue;


					$line=preg_replace($regex,'',$line);
					$forecast_week_number++;
					$num_weeks=7*$forecast_week_number;
					$date=date("Y-m-d",strtotime($last_date." + $num_weeks days"));
					$data=preg_split('/\s/',$line);



					if ($this->no_negative_values) {
						foreach ($data as $_key=>$_value) {
							if (is_numeric($_value) and $_value<0)
								$data[$_key]=0;
						}
					}

					if ($data[0]==0)
						$uncertainty=0;
					else
						$uncertainty=($data[4]-$data[3])/(2*$data[0]);

					$forecast[$date]['count']=round($data[0]);
					$forecast[$date]['deviation'].='|'.round($data[1]).','.round($data[2]).','.round($data[3]).','.round($data[4]).','.$uncertainty;
					$forecast_bins++;

				}

				if (preg_match('/Point Forecast/i',$line))
					$read=true;
			}


			return $forecast;

		}


	}

	function get_values_per_year() {

		$this->first_complete_year();
		if ($this->no_data)
			return;

		$start_year=$this->start_year;
		$last_year=date("Y");

		if ($last_year<$start_year) {
			$this->error=true;
			$this->no_data=true;
			return;
		}
		for ($year=$start_year; $year<=$last_year; $year++  ) {

			$this->values["$year-01-01"]=array('count'=>0,'value'=>0);
		}

		$sql=sprintf("SELECT %s as number,%s as date ,YEAR(%s) AS year ,sum(%s) as value FROM %s where YEAR(%s)>=%s and YEAR(%s)<=%s %s  GROUP BY year limit 10000"
			,$this->count
			,$this->date_field,$this->date_field
			,$this->value_field
			,$this->table
			,$this->date_field,prepare_mysql($start_year)
			,$this->date_field,prepare_mysql($last_year)
			,$this->where
		);


		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$year=$row['year'];
			if ($year==date("Y") or $year==$this->start_year) {
				if ($year==date("Y")) {
					$this->current=array('date'=>"$year-01-01",'count'=>$row['number'],'value'=>$row['value']);

				}
				if ($year==$this->start_year) {
					$this->first=array('date'=>"$year-01-01",'count'=>$row['number'],'value'=>$row['value']);

				}
				unset($this->values["$year-01-01"]);
			} else {
				$this->values["$year-01-01"]['count']=$row['number'];
				$this->values["$year-01-01"]['value']=$row['value'];
			}
		}


	}

function get_site_users_requests_value_day($date,$last_close){
	
	$new=0;


		$sql=sprintf("select count(*) as request  from `User Request Dimension` URD  left join `Page Store Dimension` PSD on (PSD.`Page Key`=URD.`Page Key`) where  `Page Site Key`=%d and Date(`Date`)=%s and `User Key`!=0",
			$this->name_key,
			prepare_mysql($date)
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$new=$row['request'];
		}
		//  print "$sql\n";
		$data=array(
			'value'=>$last_close,
			'count'=>0,
			'open'=>$last_close,
			'close'=>$last_close+$new,
			'low'=>$last_close+$new,
			'high'=>$last_close,
			'volume'=>$new,
			'adj close'=>false,

		);
		//   print_r($data);

		return $data;
	
	
	}

	function get_site_no_users_requests_value_day($date,$last_close){
	
	$new=0;


		$sql=sprintf("select count(*) as request  from `User Request Dimension` URD  left join `Page Store Dimension` PSD on (PSD.`Page Key`=URD.`Page Key`) where  `Page Site Key`=%d and Date(`Date`)=%s and `User Key`=0",
			$this->name_key,
			prepare_mysql($date)
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$new=$row['request'];
		}
		//  print "$sql\n";
		$data=array(
			'value'=>$last_close,
			'count'=>0,
			'open'=>$last_close,
			'close'=>$last_close+$new,
			'low'=>$last_close+$new,
			'high'=>$last_close,
			'volume'=>$new,
			'adj close'=>false,

		);
		//   print_r($data);

		return $data;
	
	
	}

	function get_contact_population_value_day($date,$last_close) {

		$new=0;


		$sql=sprintf("select count(*) as new  from `Customer Dimension` where  `Customer Store Key`=%d and Date(`Customer First Contacted Date`)=%s",
			$this->name_key,
			prepare_mysql($date)
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$new=$row['new'];
		}
		//  print "$sql\n";
		$data=array(
			'value'=>$last_close,
			'count'=>0,
			'open'=>$last_close,
			'close'=>$last_close+$new,
			'low'=>$last_close+$new,
			'high'=>$last_close,
			'volume'=>$new,
			'adj close'=>false,

		);
		//   print_r($data);

		return $data;



	}





	function get_customer_population_value_day($date,$last_close) {

		$new_customers=0;
		$lost_customers=0;
		$delta_data_date=array();
		$delta_data=array();
		$sql=sprintf("select `Customer First Order Date` as the_date from `Customer Dimension`  where `Customer With Orders`='Yes' and `Customer Store Key`=%d and DATE(`Customer First Order Date`)=%s",
			$this->name_key,
			prepare_mysql($date)
		);

		$res=mysql_query($sql);
		//print "$sql\n";
		while ($row=mysql_fetch_array($res)) {
			$delta_data[]=1;
			$delta_data_date[]=strtotime($row['the_date']);
			$new_customers++;
		}

		$sql=sprintf("select `Customer Lost Date` the_date  from `Customer Dimension`  where`Customer Type by Activity`='Lost'  and `Customer With Orders`='Yes' and `Customer Store Key`=%d and Date(`Customer Lost Date`)=%s",
			$this->name_key,
			prepare_mysql($date)
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$delta_data[]=-1;
			$delta_data_date[]=strtotime($row['the_date']);
			$lost_customers++;
		}
		//print "$sql\n";

		if ($new_customers==0 and $lost_customers==0) {

			$data=array(
				'value'=>$last_close,
				'count'=>0,
				'open'=>$last_close,
				'close'=>$last_close,
				'low'=>$last_close,
				'high'=>$last_close,
				'volume'=>$last_close,
				'adj close'=>false,
				'note'=>'no change'
			);
			//print_r($data);
			return $data;

		}else {

			array_multisort($delta_data_date,$delta_data);
			$data['adj close']=false;
			$data['open']=$last_close;
			$min=9999999999;
			$max=-9999999999;
			$current=$last_close;
			$volume=0;
			foreach ($delta_data as $delta) {
				$current+=$delta;
				$volume++;
				if ($current>$max)
					$max=$current;
				if ($current<$min)
					$min=$current;


			}
			$data['close']=$current;
			$data['high']=$current;
			$data['low']=$min;
			$data['volume']=$volume;

			//print_r($data);

			return $data;


		}


		return false;
	}


	function get_value_day($date,$last_close=false) {

		if ($this->name=='customer population') {
			return $this->get_customer_population_value_day($date,$last_close);
		}elseif ($this->name=='contact population') {
			return $this->get_contact_population_value_day($date,$last_close);
		}elseif ($this->name=='Site No Users Requests') {
			return $this->get_site_no_users_requests_value_day($date,$last_close);
		}elseif ($this->name=='Site Users Requests') {
			return $this->get_site_users_requests_value_day($date,$last_close);
		}


		$sql=sprintf("SELECT %s as number,%s as date  ,sum(%s) as value FROM %s where %s=%s %s  "
			,$this->count
			,$this->date_field
			,$this->value_field
			,$this->table
			,$this->date_field,prepare_mysql($date)
			,$this->where
		);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_assoc($res)) {
			$data=array(
				'value'=>$row['value'],
				'count'=>$row['number'],
				'close'=>$row['value']
			);

			return $data;
		}else {
			return false;
		}

	}





	function get_values_day_by_day($options='') {

		if (preg_match('/save/i',$options)) {
			$save=true;
		}else {
			$save=false;
		}



		$this->first_complete_day();
		if ($this->no_data)
			return;

		$start_day=$this->start_day;
		$last_day=date("Y-m-d");

		if ($last_day<$start_day) {
			print "$start_day  $last_day  \n";
			$this->error=true;
			$this->no_data=true;
			return;
		}




		$sql=sprintf("select `Date` as date from kbase.`Date Dimension` where `Date`>=%s and `Date` <= %s  ; "
			,prepare_mysql($this->start_date)
			,prepare_mysql($this->last_date)
		);
		print $sql;

		$data=array();
		$all_data=array();
		$res = mysql_query($sql);

		$start_day=$this->first_complete_date;
		$last_day=date("Y-m-d");
		$is_first=false;
		$is_current=false;
		$is_data=false;

		$last_close=0;

		while ($row=mysql_fetch_array($res)) {

			if ($row['date']==$start_day) {
				$this->first=array(
					'date'=>$row['date'],
					'value'=>0,
					'count'=>0,
					'open'=>0,
					'low'=>0,
					'high'=>0,
					'close'=>0,
					'volume'=>0,
					'adj close'=>0
				);
				$is_first=true;
			} else if ($row['date']==$last_day) {
					$this->current=array(
						'date'=>$row['date'],
						'value'=>0,
						'count'=>0,
						'open'=>0,
						'low'=>0,
						'high'=>0,
						'close'=>0,
						'volume'=>0,
						'adj close'=>0
					);
					$is_current=true;
				} else {
				$data[$row['date']]=array(
					'date'=>$row['date'],
					'value'=>0,
					'count'=>0,
					'open'=>0,
					'low'=>0,
					'high'=>0,
					'close'=>0,
					'volume'=>0,
					'adj close'=>0
				);
				$is_data=true;
			}

			$all_data[$row['date']]=array('date'=>$row['date'],
				'value'=>0,
				'count'=>0,
				'open'=>0,
				'low'=>0,
				'high'=>0,
				'close'=>0,
				'volume'=>0,
				'adj close'=>0
			);


			if ($values=$this->get_value_day($row['date'],$last_close)) {
				// print_r($values);
				$last_close=$values['close'];
				foreach ($values as $key=>$value) {
					if (array_key_exists($key,$all_data[$row['date']])) {
						$all_data[$row['date']][$key]=$value;
						if ($is_first)
							$this->first[$key]=$value;
						elseif ($is_current)
							$this->current[$key]=$value;
						else
							$data[$row['date']][$key]=$value;
					}
				}

			}


			if ($save) {
				$this->save_day_values($row['date'],$all_data[$row['date']]);
			}


		}





	}

	function get_values_per_day() {


		$this->first_complete_day();
		if ($this->no_data)
			return;

		$start_day=$this->start_day;
		$last_day=date("Y-m-d");

		if ($last_day<$start_day) {
			$this->error=true;
			$this->no_data=true;
			return;
		}




		$sql=sprintf("select `Date` as date from kbase.`Date Dimension` where `Date`>=%s and `Date` <= %s  ; "
			,prepare_mysql($this->start_date)
			,prepare_mysql($this->last_date)
		);

		$data=array();
		$res = mysql_query($sql);

		$start_day=$this->first_complete_date;
		$last_day=date("Y-m-d");
		while ($row=mysql_fetch_array($res)) {

			if ($row['date']==$start_day) {
				$this->first=array(
					'date'=>$row['date']
					,'value'=>0
					,'count'=>0
				);
			} else if ($row['date']==$last_day) {
					$this->current=array(
						'date'=>$row['date']
						,'value'=>0
						,'count'=>0
					);
				} else {
				$data[$row['date']]=array(
					'date'=>$row['date']
					,'value'=>0
					,'count'=>0
				);
			}
		}

		$sql=sprintf("SELECT %s as number,%s as date  ,sum(%s) as value FROM %s where %s>=%s and %s<=%s %s  GROUP BY date limit 100000"
			,$this->count
			,$this->date_field
			,$this->value_field
			,$this->table
			,$this->date_field,prepare_mysql($start_day)
			,$this->date_field,prepare_mysql($last_day)
			,$this->where
		);


		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$day=$row['date'];



			if ($day==date('Y-m-d')) {
				$this->current=array('date'=>$day,'count'=>$row['number'],'value'=>$row['value']);
				unset($this->values[$day]);

			}elseif ($day==$start_day) {
				$this->first=array('date'=>$day,'count'=>$row['number'],'value'=>$row['value']);
				unset($this->values[$day]);


			} else {
				$this->values[$day]['count']=$row['number'];
				$this->values[$day]['value']=$row['value'];
			}
		}



	}



	function get_values_per_month() {



		$this->first_complete_month();
		if ($this->no_data)
			return;


		$first_dd=date("Y-m",strtotime($this->start_date));
		$last_dd=date("Y-m",strtotime($this->last_date));
		$current_dd=date("Y-m");

		$sql=sprintf("SELECT `First Day` as date ,substring(`First Day`, 1,7) AS dd  FROM kbase.`Month Dimension` where `First Day`>=%s  and `First Day`<=%s  GROUP BY dd order by `First Day` "
			,prepare_mysql($this->start_date)
			,prepare_mysql($this->last_date)
		);

		$res=mysql_query($sql);
		$this->values=array();
		while ($row=mysql_fetch_array($res)) {
			/*  if($row['dd']==$this->start_year.'-'.$this->start_bin) */
			/*        $this->first=array('date'=>$row['dd'].'-01','count'=>0,'value'=>0); */
			/*      else if($row['dd']==date("Y-m")) */
			/*         $this->current=array('date'=>$row['dd'].'-01','count'=>0,'value'=>0); */
			/*      else */
			$data[$row['dd']]=array('date'=>$row['date'],'count'=>0,'value'=>0);
		}

		$sql=sprintf("SELECT %s as number,%s as date ,substring(%s, 1,7) AS dd ,sum(%s) as value FROM %s where Date(%s)>=%s  and Date(%s)<=%s %s  GROUP BY dd limit 10000"
			,$this->count
			,$this->date_field,$this->date_field
			,$this->value_field
			,$this->table
			,$this->date_field,prepare_mysql($this->start_date)
			,$this->date_field,prepare_mysql(date("Y-m-d"))
			,$this->where
		);

		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			if ($row['dd']==$current_dd or $row['dd']==$first_dd) {
				if ($row['dd']==$current_dd) {
					$this->current=array('date'=>$row['dd'].'-01','count'=>$row['number'],'value'=>$row['value']);

				}
				if ($row['dd']==$first_dd) {
					$this->first=array('date'=>$row['dd'].'-01','count'=>$row['number'],'value'=>$row['value']);

				}
				unset($data[$row['dd']]);
			} else {
				$data[$row['dd']]['count']=$row['number'];
				$data[$row['dd']]['value']=$row['value'];
			}
		}

		foreach ($data as $_values) {
			$this->values[$_values['date']]=$_values;
		}
	}


	function get_values_per_week() {

		$this->first_complete_week();
		if ($this->no_data)
			return;
		$this->last_date=date("Y-m-d");




		$first_yearweek=yearweek($this->start_date);
		$last_yearweek=yearweek($this->last_date );
		$current_yearweek=yearweek(date("Y-m-d")) ;

		$sql=sprintf("select `First Day` as date,`Year Week` as yearweek,`Year` as year from kbase.`Week Dimension` where `Last Day`>=%s and `First Day` <= %s  ; "
			,prepare_mysql($this->start_date)
			,prepare_mysql($this->last_date)
		);


		$data=array();
		$res = mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {

			if ($row['yearweek']==$first_yearweek) {
				$this->first=array(
					'date'=>$row['date']
					,'value'=>0
					,'count'=>0
				);
			} else if ($row['yearweek']==$current_yearweek) {
					$this->current=array(
						'date'=>$row['date']
						,'value'=>0
						,'count'=>0
					);
				} else {
				$data[$row['yearweek']]=array(
					'date'=>$row['date']
					,'value'=>0
					,'count'=>0
				);
			}
		}


		$sql=sprintf("SELECT %s as number,%s as date ,YEARWEEK(%s,3) AS yearweek  , sum(%s) as value FROM %s where %s>=%s  and %s<=%s %s  GROUP BY yearweek limit 10000"
			,$this->count
			,$this->date_field,$this->date_field
			,$this->value_field
			,$this->table
			,$this->date_field,prepare_mysql($this->start_date)
			,$this->date_field,prepare_mysql($this->last_date)
			,$this->where
		);


		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {

			if ($row['yearweek']==$first_yearweek or $row['yearweek']==$current_yearweek ) {
				if ($row['yearweek']==$first_yearweek) {
					//      $this->first=$data[$row['yearweek']];
					$this->first['count']=$row['number'];
					$this->first['value']=$row['value'];

				}
				if ($row['yearweek']==$current_yearweek) {
					//$this->current=$data[$row['normalized_yearweek']];
					$this->current['count']=$row['number'];
					$this->current['value']=$row['value'];

				}
				unset($data[$row['yearweek']]);
			} else {

				$data[$row['yearweek']]['count']=$row['number'];
				$data[$row['yearweek']]['value']=$row['value'];
			}
		}
		foreach ($data as $_values) {
			$this->values[$_values['date']]=$_values;
		}


	}

	function get_normalized_week_values() {

		$this->first_complete_week();
		if ($this->no_data)
			return;
		$this->last_date=date("Y-m-d");




		$first_yearweek=$this->normalized_yearweek($this->start_date);
		$last_yearweek=$this->normalized_yearweek($this->last_date );
		$current_yearweek=$this->normalized_yearweek(date("Y-m-d") );

		$sql=sprintf("select count(*) as factor,`First Day` as date,`Year Week Normalized` as yearweek,`Year` as year from `Week Dimension` where `Normalized Last Day`>%s and `First Day` <= %s  group by `Year Week Normalized`; "
			,prepare_mysql($this->start_date)
			,prepare_mysql($this->last_date)
		);


		$data=array();
		$res = mysql_query($sql);


		while ($row=mysql_fetch_array($res)) {
			$data[$row['yearweek']]=array(
				'date'=>$row['date']
				,'value'=>0
				,'count'=>0
				,'factor'=>$row['factor']
			);
		}

		$sql=sprintf("SELECT %s as number,%s as date ,YEARWEEK(%s,3) AS yearweek , (select `Year Week Normalized` from `Week Dimension` as WD where WD.`Year Week`=yearweek) as normalized_yearweek , sum(%s) as value FROM %s where %s>=%s  and %s<=%s %s  GROUP BY yearweek limit 10000"
			,$this->count
			,$this->date_field,$this->date_field
			,$this->value_field
			,$this->table
			,$this->date_field,prepare_mysql($this->start_date)
			,$this->date_field,prepare_mysql($this->last_date)
			,$this->where
		);


		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {

			if ($row['normalized_yearweek']==$first_yearweek or $row['normalized_yearweek']==$current_yearweek) {

				unset($data[$row['normalized_yearweek']]);
			} else {

				$data[$row['normalized_yearweek']]['count']=$row['number'];
				$data[$row['normalized_yearweek']]['value']=$row['value']/$data['factor'];
			}
		}
		foreach ($data as $_values) {
			$this->normalized_values[$_values['date']]=$_values;
		}
		//  exit;




	}





	function get_values_per_quarter() {

		$this->first_complete_quarter();
		if ($this->no_data)
			return;
		$this->last_date=date("Y-m-d");


		$first_yearquarter=yearquarter($this->start_date);
		$last_yearquarter=yearquarter($this->last_date );
		$current_yearquarter=yearquarter(date("Y-m-d") );

		$sql=sprintf("select `First Day` as date, `Year Quarter` as yearquarter  from kbase.`Quarter Dimension` where `Year Quarter`>=%s and `Year Quarter` <= %s; "
			,prepare_mysql(yearquarter($this->start_date))
			,prepare_mysql(yearquarter($this->last_date))
		);

		$data=array();
		$res = mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$data[$row['yearquarter']]=array(
				'date'=>$row['date']
				,'value'=>0
				,'count'=>0
			);
		}




		$sql=sprintf("SELECT %s as number,%s as date ,concat(year(%s),quarter(%s)) AS yearquarter ,sum(%s) as value FROM %s where %s>=%s  and %s<=%s %s  GROUP BY yearquarter limit 10000"
			,$this->count
			,$this->date_field,$this->date_field,$this->date_field
			,$this->value_field
			,$this->table
			,$this->date_field,prepare_mysql($this->start_date)
			,$this->date_field,prepare_mysql($this->last_date)
			,$this->where
		);


		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['yearquarter']==$first_yearquarter or $row['yearquarter']==$current_yearquarter) {
				if ($row['yearquarter']==$first_yearquarter) {
					$this->first=$data[$row['yearquarter']];
					$this->first['count']=$row['number'];
					$this->first['value']=$row['value'];

				}
				if ($row['yearquarter']==$current_yearquarter) {
					$this->current=$data[$row['yearquarter']];
					$this->current['count']=$row['number'];
					$this->current['value']=$row['value'];
				}
				unset($data[$row['yearquarter']]);

			} else {

				$data[$row['yearquarter']]['count']=$row['number'];
				$data[$row['yearquarter']]['value']=$row['value'];
			}
		}
		foreach ($data as $_values) {
			$this->values[$_values['date']]=$_values;
		}

	}

	function first_complete_month() {
		$sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s  where %s IS NOT NULL %s   order by %s limit 1  "
			,$this->date_field
			,$this->date_field
			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);
		// print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$time=mktime(0, 0, 0, $row["m"] , 1, $row["y"]);
			$this->start_date=date("Y-m-d", $time);
			$this->start_year=date("Y", $time);
			$this->start_bin=date("m", $time);
			$time=mktime(0, 0, 0, $row["m"]+1 , 1, $row["y"]);
			$this->first_complete_date=date("Y-m-d", $time);
			$this->first_complete_year=date("Y", $time);
			$this->first_complete_bin=date("m", $time);
			$this->no_data=false;
		} else {
			$this->no_data=true;

		}
	}


	function store_first_complete_day() {

		$sql=sprintf("select `Store Valid From` as day from `Store Dimension`  where `Store Valid From` IS NOT NULL  and `Store Key`=%d  "

			,$this->name_key

		);

		//print "$sql\n";

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {

			$this->start_date=date("Y-m-d", strtotime($row['day']));
			$this->start_day=$this->start_date;
			$this->start_bin=1;
			$time=strtotime($row['day'].' +1 day');
			$this->first_complete_date=date("Y-m-d", $time);
			$this->first_complete_bin=1;
			$this->no_data=false;
		} else
			$this->no_data=true;
	}

	function first_complete_day() {

	if ($this->name=='Site No Users Requests' or $this->name=='Site Users Requests') {
	$sql=sprintf("select `Site From` as the_date from `Site Dimension`  where  `Site Key`=%d ",
				$this->name_key

			);

			$res=mysql_query($sql);

			if ($row=mysql_fetch_array($res)) {

			if($row['the_date']=='')
				return;

				$this->start_date=date("Y-m-d", strtotime($row['the_date']));
				$this->start_day=$this->start_date;
				$this->start_bin=1;
				$time=strtotime($row['the_date'].' +1 day');
				$this->first_complete_date=date("Y-m-d", $time);
				$this->first_complete_bin=1;
				$this->no_data=false;
				return $this->start_date;

			}else{
				return;
			}
	
	}
	
		if ($this->name=='customer population') {

			$sql=sprintf("select min(`Customer First Order Date`) as the_date from `Customer Dimension`  where `Customer With Orders`='Yes' and `Customer Store Key`=%d ",
				$this->name_key

			);

			$res=mysql_query($sql);

			if ($row=mysql_fetch_array($res)) {

				$this->start_date=date("Y-m-d", strtotime($row['the_date']));
				$this->start_day=$this->start_date;
				$this->start_bin=1;
				$time=strtotime($row['the_date'].' +1 day');
				$this->first_complete_date=date("Y-m-d", $time);
				$this->first_complete_bin=1;
				$this->no_data=false;
				return $this->start_date;

			}
		}

		if ($this->name=='customer population' or $this->name=='contact population') {
			return $this->store_first_complete_day();
		}


		$sql=sprintf("select %s as day from %s  where %s IS NOT NULL %s   order by %s limit 1  "

			,$this->date_field
			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);

		//  print "$sql\n";

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {

			$this->start_date=date("Y-m-d", strtotime($row['day']));
			$this->start_day=$this->start_date;
			$this->start_bin=1;
			$time=strtotime($row['day'].' +1 day');
			$this->first_complete_date=date("Y-m-d", $time);
			$this->first_complete_bin=1;
			$this->no_data=false;
		} else
			$this->no_data=true;


		//    print "caca";

	}

	function first_complete_year() {
		$sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s  where %s IS NOT NULL %s   order by %s limit 1  "
			,$this->date_field
			,$this->date_field
			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);

		//print "$sql\n";

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$time=mktime(0, 0, 0, 1 , 1, $row["y"]);
			$this->start_date=date("Y-m-d", $time);
			$this->start_year=date("Y", $time);
			$this->start_bin=1;
			$time=mktime(0, 0, 0,1, 1, $row["y"]+1);
			$this->first_complete_date=date("Y-m-d", $time);
			$this->first_complete_year=date("Y", $time);
			$this->first_complete_bin=1;
			$this->no_data=false;
		} else
			$this->no_data=true;
	}

	function first_complete_quarter() {
		$sql=sprintf("select  MONTH(%s) as m,QUARTER(%s) as q,YEAR(%s) as y from %s  where %s IS NOT NULL %s   order by %s limit 1  "
			,$this->date_field
			,$this->date_field
			,$this->date_field
			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$time=mktime(0, 0, 0, $row["m"] , 1, $row["y"]);
			$this->start_date=date("Y-m-d", $time);
			$this->start_year=date("Y", $time);
			$this->start_bin=date("m", $time);
			$time=mktime(0, 0, 0,$row["m"]+3, 1, $row["y"]);
			$this->first_complete_date=date("Y-m-d", $time);
			$this->first_complete_year=date("Y", $time);
			if ($row['m']<=3)
				$quarter=2;
			elseif ($row['m']<=6)
				$quarter=3;
			elseif ($row['m']<=9)
				$quarter=4;
			else
				$quarter=1;

			$this->first_complete_bin=$quarter;
			$this->no_data=false;
		} else
			$this->no_data=true;

	}




	function first_complete_week() {
		$sql=sprintf("select  %s as date  from %s  where %s IS NOT NULL %s   order by %s limit 1  "
			,$this->date_field
			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {

			$yearweek=$this->normalized_yearweek($row['date']);



			$time=strtotime($row['date']);
			$this->start_date=date("Y-m-d", $time);
			$this->start_year=date("Y", $time);
			$this->start_bin=$yearweek;
			$time=strtotime($row['date']." +1 day");
			$this->first_complete_date=date("Y-m-d", $time);
			$this->first_complete_year=date("Y", $time);

			$yearweek=$this->normalized_yearweek($this->first_complete_date);


			$this->first_complete_bin=$yearweek;
			$this->no_data=false;
		} else
			$this->no_data=true;



	}




	function store_last_day() {
		$sql=sprintf("select `Store Valid To` as date from `Store Dimension` where `Store Valid To` IS NOT NULL and `Store Key` =%d  "
			,$this->name_key

		);
		
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			return $row['date'];

		} else
			return gmdate('Y-m-d');

	}


function site_last_day() {
		$sql=sprintf("select `Site To` as date from `Site Dimension` where `Site To` IS NOT NULL and `Site Key` =%d  "
			,$this->name_key

		);
		
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			return $row['date'];

		} else
			return gmdate('Y-m-d');

	}


	function last_date() {

		if ($this->name=='customer population' or $this->name=='contact population') {
			return $this->store_last_day();
		}
		
		if ($this->name=='Site No Users Requests' or $this->name=='Site Users Requests') {
			return $this->site_last_day();
		}

		$sql=sprintf("select %s as date from %s where %s IS NOT NULL %s  order by %s desc limit 1  "
			,$this->date_field


			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);
		// print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			return $row['date'];

		} else
			return false;

	}

	function last_complete_month() {
		//  return "2009-07-31 23:59:59";
		$sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s where %s IS NOT NULL %s  order by %s desc limit 1  "
			,$this->date_field
			,$this->date_field

			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);
		// print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$last_time=mktime(0, 0, 0, date($row["m"])-1 , -1, date($row["y"]));
			if ($last_time>mktime(0, 0, 0, date("m") , date("d"), date("y") ))
				$last_time=mktime(0, 0, 0, date("m") , -1, date("y"));
			return date("Y-m-d", $last_time);
		}
	}

	function last_complete_year() {
		$sql=sprintf("select YEAR(%s) as y from %s where %s IS NOT NULL %s  order by %s desc limit 1  "
			,$this->date_field
			,$this->date_field
			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$last_time=mktime(0, 0, 0, 1 , 1, date($row["y"])-1 );
			return date("Y-m-d", $last_time);
		}
	}

	function last_complete_quarter() {
		$sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s where %s IS NOT NULL %s  order by %s desc limit 1  "
			,$this->date_field
			,$this->date_field
			,$this->table
			,$this->date_field
			,$this->where
			,$this->date_field
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {

			if ($row['m']<=3)
				$last_time=mktime(0, 0, 0, 10 , 1, date("y")-1);
			elseif ($row['m']<=6)
				$last_time=mktime(0, 0, 0, 1 , 1, date("y"));
			elseif ($row['m']<=9)
				$last_time=mktime(0, 0, 0, 4 , 1, date("y"));
			else
				$last_time=mktime(0, 0, 0, 7 , 1, date("y"));

			return date("Y-m-d", $last_time);
		}
	}





	function plot_data($from=false,$to=false) {



		$tipo='';
		$suffix='';
		if ($this->name=='profit') {

			$tipo='PI';
			$suffix='';

		}
		if ($this->name=='invoices') {

			$tipo='SI';
			$suffix='';

		}elseif ($this->name=='SkuS') {

			$tipo='PartOut';
			$suffix=preg_replace('/.*\(/','',$this->name_key);
			$suffix=preg_replace('/\)/','',$suffix);
			$suffix=preg_replace('/,/','_',$suffix);

		}
		elseif ($this->name=='invoice cat') {

			$tipo='PI';


		}
		elseif (preg_match('/^(PDS|SS|PFS|PidS|PcodeS)/',$this->name)) {
			$tipo='PI';
			$suffix=preg_replace('/.*\(/','',$this->name_key);
			$suffix=preg_replace('/\)/','',$suffix);
			$suffix=preg_replace('/,/','_',$suffix);
		}
		elseif (preg_match('/^(PDP|SP|PFP|PidP|PcodeP)/',$this->name)) {
			$tipo='PP';
			$suffix=preg_replace('/.*\(/','',$this->name_key);
			$suffix=preg_replace('/\)/','',$suffix);
			$suffix=preg_replace('/,/','_',$suffix);
		}



		if ( $this->freq=='Monthly') {

			return $this->plot_data_per_month($tipo,$suffix,$from,$to);
		}
		elseif ($this->freq=='Yearly')
			return $this->plot_data_per_year($tipo,$suffix,$from,$to);
		elseif ($this->freq=='Weekly')
			return $this->plot_data_per_week($tipo,$suffix,$from,$to);
		elseif ($this->freq=='Quarterly')
			return $this->plot_data_per_quarter($tipo,$suffix,$from,$to);

	}




	function set_labels() {
		$tipo='';
		$suffix='';




		if ($this->name=='profit') {

			$tipo='PI';
			$suffix='';

		}
		if ($this->name=='invoices') {

			$tipo='SI';
			$suffix='';

		}


		elseif (preg_match('/^(PDS|SS|PFS|PidS|PcodeS)/',$this->name)) {
			$tipo='PI';
			$suffix=preg_replace('/.*\(/','',$this->name_key);
			$suffix=preg_replace('/\)/','',$suffix);
			$suffix=preg_replace('/,/','_',$suffix);
		}
		elseif (preg_match('/^(PDP|SP|PFP|PidP|PcodeP)/',$this->name)) {
			$tipo='PP';
			$suffix=preg_replace('/.*\(/','',$this->name_key);
			$suffix=preg_replace('/\)/','',$suffix);
			$suffix=preg_replace('/,/','_',$suffix);
		}
		elseif (preg_match('/^(PDP|SP|PFP|PidP|PcodeP)/',$this->name)) {
			$tipo='PP';
			$suffix=preg_replace('/.*\(/','',$this->name_key);
			$suffix=preg_replace('/\)/','',$suffix);
			$suffix=preg_replace('/,/','_',$suffix);
		}elseif (preg_match('/^(SkuS)/',$this->name)) {
			$tipo='Part';
			$suffix=preg_replace('/.*\(/','',$this->name_key);
			$suffix=preg_replace('/\)/','',$suffix);
			$suffix=preg_replace('/,/','_',$suffix);
		}


		if ( $this->freq=='Monthly') {

			return $this->set_labels_per_month($tipo,$suffix,$from,$to);
		}
		elseif ($this->freq=='Yearly')
			return $this->set_labels_per_year($tipo,$suffix,$from,$to);
		elseif ($this->freq=='Weekly')
			return $this->set_labels_per_week($tipo,$suffix,$from,$to);
		elseif ($this->freq=='Quarterly')
			return $this->set_labels_per_quarter($tipo,$suffix,$from,$to);


	}



	function plot_data_per_month($tipo,$suffix,$from='',$to='',$currency='') {

		$from=prepare_mysql_datetime($from,'date');
		$to=prepare_mysql_datetime($to,'date');



		$where_from='';
		if ($from['ok'])
			$where_from=sprintf('and `Time Series Date`>=%s ',prepare_mysql($from['mysql_date']));
		$where_to='';
		if ($to['ok'])
			$where_to=sprintf('and `Time Series Date`<=%s ',prepare_mysql($to['mysql_date']));




		$data=array();


		$sql=sprintf("SELECT `Time Series Label`,`Time Series Type`,`Time Series Value` as value,MONTH(`Time Series Date`) as month,`Time Series Count` as count ,UNIX_TIMESTAMP(`Time Series Date`) as date ,substring(`Time Series Date`, 1,7) AS dd from `Time Series Dimension` where  `Time Series Frequency`='Monthly' and  `Time Series Name`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d %s %s order by `Time Series Date`,`Time Series Type` desc"
			,prepare_mysql($this->name)
			,$this->name_key
			,$this->name_key2
			,$where_from
			,$where_to
		);
		//print $sql;
		$prev_month='';
		$prev_year=array();
		$forecast_region=false;
		$data_region=false;
		$res = mysql_query($sql);
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			if (is_numeric($prev_month)) {
				$diff=$row['value']-$prev_month;
				if ($diff==0)
					$diff_prev_month=_('No change from last month')."\n";
				else
					$diff_prev_month=percentage($diff,$prev_month,1,'NA','%',true)." "._('change (last month)')."\n";
			} else
				$diff_prev_month='';

			if (isset($prev_year[$row['month']])) {
				$diff=$row['value']-$prev_year[$row['month']];
				if ($diff==0)
					$diff_prev_year=_('No change from last year')."\n";
				else
					$diff_prev_year=percentage($diff,$prev_year[$row['month']],1,'NA','%',true)." "._('change (last year)')."\n";
			} else {
				$diff_prev_year='';
			}


			if ($tipo=='SI' or $tipo=='PI') {
				$tip=$row['Time Series Label'].' '._('Sales')
					." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['value'],$currency).($row['Time Series Type']=='Forecast'?" ("._('Forecast').")":"")."\n".$diff_prev_month.$diff_prev_year."(".$row['count']." "._('Invoices').")";

			}
			elseif ($tipo=="PO") {

				$tip=_('Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['value'],$currency)."\n".
					$diff_prev_month.$diff_prev_year."(".$row['count']." "._('Outers Shipped').")";

			}
			elseif ($tipo=='PI' or $tipo=='PP') {
				$tip=$row['Time Series Label'].' '._('Profit')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['value'],$currency)."\n".$diff_prev_month.$diff_prev_year."(".$row['count']." "._('Invoices').")";

			}elseif ($tipo=='PartOut') {
				$tip=_('Part').' '.$row['Time Series Label'].' '._('Out')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".number($row['value'])."\n".$diff_prev_month.$diff_prev_year;

			}



			//  $data[$row['dd']]=array(
			//        'date'=>strftime("%m/%y", strtotime('@'.$row['date']))
			//         );

			$data[$row['dd']]['date']=strftime("%m/%y", strtotime('@'.$row['date']));


			// print $row['dd']."<br>\n";
			if ($row['Time Series Type']=='First') {
				$first_value=array($row['dd'],$row['value'],$tip) ;

			}
			elseif ($row['Time Series Type']=='Current') {



				$current_value=array($row['dd'],$row['value'],$tip) ;



				$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
				$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];
				$data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
				$data[$last_complete_value[0]]['tip_tails'.$suffix]='';


			}
			elseif ($row['Time Series Type']=='Data' ) {
				if (!$data_region and isset($first_value)) {

					$data[$first_value[0]]['tails'.$suffix]=(float) $first_value[1];
					$data[$first_value[0]]['tip_tails'.$suffix]=$first_value[2];
					$data[$row['dd']]['tails'.$suffix]=(float) $row['value'];
					$data[$row['dd']]['tip_tails'.$suffix]=$tip;

				}
				$data_region=true;

				// if ($row['Time Series Type']=='Current') {
				$data[$row['dd']]['value'.$suffix]=(float) $row['value'];
				$data[$row['dd']]['tip_value'.$suffix]=$tip.$row['Time Series Type'];
				// }

				$last_complete_value=array($row['dd'],$row['value'],$tip) ;




			}
			elseif ($row['Time Series Type']=='Forecast' ) {

				if (!$forecast_region and isset($last_complete_value[0])) {
					$data[$last_complete_value[0]]['forecast'.$suffix]=(float) $last_complete_value[1];
				}
				$forecast_region=true;
				$data[$row['dd']]['forecast'.$suffix]=(float) $row['value'];
				$data[$row['dd']]['tip_forecast'.$suffix]=$tip;
			}



			$prev_month=$row['value'];
			$prev_year[$row['month']]=$row['value'];
		}

		if (isset($current_value[0])) {
			unset($data[$current_value[0]]['value'.$suffix]);
			unset($data[$current_value[0]]['tip_value'.$suffix]);
		}
		mysql_free_result($res);

		return $data;

	}


	function plot_data_per_year($tipo,$suffix,$from='',$to='') {


		$from=prepare_mysql_datetime($from,'date');
		$to=prepare_mysql_datetime($to,'date');


		$where_from='';
		if ($from['ok'])
			$where_from=sprintf('and `Time Series Date`>=%s ',prepare_mysql($from['mysql_date']));
		$where_to='';
		if ($to['ok'])
			$where_to=sprintf('and `Time Series Date`<=%s ',prepare_mysql($to['mysql_date']));

		$data=array();

		$sql=sprintf("SELECT `Time Series Label`,`Time Series Type`,`Time Series Value` as value,YEAR(`Time Series Date`) as year,`Time Series Count` as count ,UNIX_TIMESTAMP(`Time Series Date`) as date from `Time Series Dimension` where  `Time Series Frequency`='Yearly' and  `Time Series Name`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d %s %s order by `Time Series Date`,`Time Series Type` desc"
			,prepare_mysql($this->name)
			,$this->name_key
			,$this->name_key2
			,$where_from
			,$where_to
		);

		$prev_year=array();
		$forecast_region=false;
		$data_region=false;
		$res = mysql_query($sql);
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			if (is_numeric($prev_year)) {
				$diff=$row['value']-$prev_year;
				if ($diff==0)
					$diff_prev_year=_('No change from last year')."\n";
				else
					$diff_prev_year=percentage($diff,$prev_year,1,'NA','%',true)." "._('change (last year)')."\n";
			} else
				$diff_prev_year='';

			if ($tipo=='SI' or $tipo=='PI') {
				$tip=$row['Time Series Label'].' '._('Sales')." ".strftime("%Y", strtotime('@'.$row['date']))."\n".money($row['value'])."\n".$diff_prev_year."(".$row['count']." "._('Invoices').")";

			}
			elseif ($tipo=="PO") {
				$tip=_('Sales')." ".strftime("%Y", strtotime('@'.$row['date']))."\n".money($row['value'])."\n".$$diff_prev_year."(".$row['count']." "._('Outers Shipped').")";

			}
			elseif ($tipo=='PI' or $tipo=='PP') {
				$tip=$row['Time Series Label'].' '._('Profit')." ".strftime("%Y", strtotime('@'.$row['date']))."\n".money($row['value'])."\n".$diff_prev_year."(".$row['count']." "._('Invoices').")";

			}



			$data[$row['year']]=array(
				'date'=>strftime("%y", strtotime('@'.$row['date']))
			);
			// print $row['year']."<br>\n";

			// print_r($row);

			if ($row['Time Series Type']=='First') {
				$first_value=array($row['year'],$row['value'],$tip) ;
				$last_complete_value=array($row['year'],$row['value'],$tip) ;
			}
			if ($row['Time Series Type']=='Current') {
				$current_value=array($row['year'],$row['value'],$tip) ;

				$data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
				$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
				$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
				$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];
			}
			if ($row['Time Series Type']=='Data') {
				if (!$data_region and isset($first_value[1])) {

					$data[$first_value[0]]['tails'.$suffix]=(float) $first_value[1];
					$data[$first_value[0]]['tip_tails'.$suffix]=$first_value[2];
					$data[$row['year']]['tails'.$suffix]=(float) $row['value'];
					$data[$row['year']]['tip_tails'.$suffix]=$tip;

				}
				$data_region=true;

				$data[$row['year']]['value'.$suffix]=(float) $row['value'];
				$data[$row['year']]['tip_value'.$suffix]=$tip;
				$last_complete_value=array($row['year'],$row['value'],$tip) ;
			}
			if ($row['Time Series Type']=='Forecast') {

				if (!$forecast_region) {
					$data[$last_complete_value[0]]['forecast'.$suffix]=(float) $last_complete_value[1];

					// $data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
					//$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
					//$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
					//$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];

				}
				$forecast_region=true;
				$data[$row['year']]['forecast'.$suffix]=(float) $row['value'];
				$data[$row['year']]['tip_forecast'.$suffix]=$tip;
			}



			$prev_year=$row['value'];

		}
		mysql_free_result($res);

		return $data;

	}



	function plot_data_per_day($tipo,$suffix,$from='',$to='') {



		$from=prepare_mysql_datetime($from,'date');
		$to=prepare_mysql_datetime($to,'date');


		$where_from='';
		if ($from['ok'])
			$where_from=sprintf('and `Time Series Date`>=%s ',prepare_mysql($from['mysql_date']));
		$where_to='';
		if ($to['ok'])
			$where_to=sprintf('and `Time Series Date`<=%s ',prepare_mysql($to['mysql_date']));

		$data=array();
		$where_dates=prepare_mysql_dates($from,$to,"`Time Series Date`");
		$sql=sprintf("SELECT `Time Series Label`,`Time Series Type`,`Time Series Value` as value,`Time Series Date`,`Time Series Count` as count ,UNIX_TIMESTAMP(`Time Series Date`) as date from `Time Series Dimension` where  `Time Series Frequency`='Yearly' and  `Time Series Name`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d %s %s order by `Time Series Date`,`Time Series Type` desc"
			,prepare_mysql($this->name)
			,$this->name_key
			,$this->name_key2
			,$where_from
			,$where_to
		);

		$prev_year=array();
		$forecast_region=false;
		$data_region=false;
		$res = mysql_query($sql);
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			/*
            if (is_numeric($prev_year)) {
                $diff=$row['value']-$prev_year;
                if ($diff==0)
                    $diff_prev_year=_('No change from last year')."\n";
                else
                    $diff_prev_year=percentage($diff,$prev_year,1,'NA','%',true)." "._('change (last year)')."\n";
            } else
                $diff_prev_year='';
*/
			if ($tipo=='SI' or $tipo=='PI') {
				$tip=$row['Time Series Label'].' '._('Sales')." ".strftime("%d-%m-%Y", strtotime($row['Time Series Date']))."\n".money($row['value'])."\n".$diff_prev_year."(".$row['count']." "._('Invoices').")";

			}
			elseif ($tipo=="PO") {
				$tip=_('Sales')." ".strftime("%d-%m-%Y", strtotime($row['Time Series Date']))."\n".money($row['value'])."\n".$$diff_prev_year."(".$row['count']." "._('Outers Shipped').")";

			}
			elseif ($tipo=='PI' or $tipo=='PP') {
				$tip=$row['Time Series Label'].' '._('Profit')." ".strftime("%d-%m-%Y", strtotime($row['Time Series Date']))."\n".money($row['value'])."\n".$diff_prev_year."(".$row['count']." "._('Invoices').")";

			} elseif ($tipo=='WSV') {
				$tip=$row['Time Series Label'].' '._('Stock Value')." ".strftime("%d-%m-%Y", strtotime($row['Time Series Date']))."\n".money($row['value'])."\n".$diff_prev_year."(".$row['count']." "._('Invoices').")";

			}



			$data[$row['Time Series Date']]=array(
				'date'=>strftime("%d-%m-%Y", strtotime($row['Time Series Date']))
			);
			// print $row['Time Series Date']."<br>\n";
			if ($row['Time Series Type']=='First') {
				$first_value=array($row['Time Series Date'],$row['value'],$tip) ;

			}
			if ($row['Time Series Type']=='Current') {
				$current_value=array($row['Time Series Date'],$row['value'],$tip) ;

				$data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
				$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
				$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
				$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];
			}
			if ($row['Time Series Type']=='Data') {
				if (!$data_region and isset($first_value[1])) {

					$data[$first_value[0]]['tails'.$suffix]=(float) $first_value[1];
					$data[$first_value[0]]['tip_tails'.$suffix]=$first_value[2];
					$data[$row['Time Series Date']]['tails'.$suffix]=(float) $row['value'];
					$data[$row['Time Series Date']]['tip_tails'.$suffix]=$tip;

				}
				$data_region=true;

				$data[$row['Time Series Date']]['value'.$suffix]=(float) $row['value'];
				$data[$row['Time Series Date']]['tip_value'.$suffix]=$tip;
				$last_complete_value=array($row['Time Series Date'],$row['value'],$tip) ;
			}
			if ($row['Time Series Type']=='Forecast') {

				if (!$forecast_region) {
					$data[$last_complete_value[0]]['forecast'.$suffix]=(float) $last_complete_value[1];

					// $data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
					//$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
					//$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
					//$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];

				}
				$forecast_region=true;
				$data[$row['Time Series Date']]['forecast'.$suffix]=(float) $row['value'];
				$data[$row['Time Series Date']]['tip_forecast'.$suffix]=$tip;
			}



			$prev_year=$row['value'];

		}
		mysql_free_result($res);
		//$_data=array();
		//$i=0;

		//foreach($data as $__data)
		//   $_data[]=$__data;

		return $data;

	}







	function plot_data_per_quarter($tipo,$suffix,$from='',$to='') {

		$from=prepare_mysql_datetime($from,'date');
		$to=prepare_mysql_datetime($to,'date');


		$where_from='';
		if ($from['ok'])
			$where_from=sprintf('and `Time Series Date`>=%s ',prepare_mysql($from['mysql_date']));
		$where_to='';
		if ($to['ok'])
			$where_to=sprintf('and `Time Series Date`<=%s ',prepare_mysql($to['mysql_date']));





		$data=array();
		//$where_dates=prepare_mysql_dates($from,$to,"`Time Series Date`");
		$sql=sprintf("SELECT `Time Series Label`,`Time Series Type`,`Time Series Value` as value, CONCAT(YEAR(`Time Series Date`),QUARTER(`Time Series Date`))   yearquarter,QUARTER(`Time Series Date`) as quarter,YEAR(`Time Series Date`) as year,`Time Series Count` as count ,UNIX_TIMESTAMP(`Time Series Date`) as date from `Time Series Dimension` where  `Time Series Frequency`='Quarterly' and  `Time Series Name`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d    order by `Time Series Date`,`Time Series Type` desc"
			,prepare_mysql($this->name)
			,$this->name_key
			,$this->name_key2
			,$where_from
			,$where_to
		);

		$prev_yearquarter=array();
		$forecast_region=false;
		$data_region=false;
		$res = mysql_query($sql);
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			if (is_numeric($prev_yearquarter)) {
				$diff=$row['value']-$prev_yearquarter;
				if ($diff==0)
					$diff_prev_yearquarter=_('No change from last quarter')."\n";
				else
					$diff_prev_yearquarter=percentage($diff,$prev_yearquarter,1,_('NA'),'%',true)." "._('change (last quarter)')."\n";
			} else
				$diff_prev_yearquarter='';




			if ($tipo=='SI' or $tipo=='PI') {
				$tip=$row['Time Series Label'].' '._('Sales')." ".$row['quarter']." "._('quarter')." ".$row['year']."\n".money($row['value'])."\n".$diff_prev_yearquarter."(".$row['count']." "._('Invoices').")";

			}
			elseif ($tipo=="PO") {
				$tip=_('Sales')." ".$row['quarter']." "._('quarter')." ".$row['year']."\n".money($row['value'])."\n".$$diff_prev_yearquarter."(".$row['count']." "._('Outers Shipped').")";

			}
			elseif ($tipo=='PI' or $tipo=='PP') {
				$tip=$row['Time Series Label'].' '._('Profit')." ".$row['quarter']." "._('quarter')." ".$row['year']."\n".money($row['value'])."\n".$diff_prev_yearquarter."(".$row['count']." "._('Invoices').")";

			}



			$data[$row['yearquarter']]=array(
				'date'=>$row['yearquarter']
			);
			// print $row['yearquarter']."<br>\n";
			if ($row['Time Series Type']=='First') {
				$first_value=array($row['yearquarter'],$row['value'],$tip) ;

			}
			if ($row['Time Series Type']=='Current') {
				$current_value=array($row['yearquarter'],$row['value'],$tip) ;

				$data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
				$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
				$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
				$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];
			}
			if ($row['Time Series Type']=='Data') {
				if (!$data_region) {

					$data[$first_value[0]]['tails'.$suffix]=(float) $first_value[1];
					$data[$first_value[0]]['tip_tails'.$suffix]=$first_value[2];
					$data[$row['yearquarter']]['tails'.$suffix]=(float) $row['value'];
					$data[$row['yearquarter']]['tip_tails'.$suffix]=$tip;

				}
				$data_region=true;

				$data[$row['yearquarter']]['value'.$suffix]=(float) $row['value'];
				$data[$row['yearquarter']]['tip_value'.$suffix]=$tip;
				$last_complete_value=array($row['yearquarter'],$row['value'],$tip) ;
			}
			if ($row['Time Series Type']=='Forecast') {

				if (!$forecast_region) {
					$data[$last_complete_value[0]]['forecast'.$suffix]=(float) $last_complete_value[1];

					// $data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
					//$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
					//$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
					//$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];

				}
				$forecast_region=true;
				$data[$row['yearquarter']]['forecast'.$suffix]=(float) $row['value'];
				$data[$row['yearquarter']]['tip_forecast'.$suffix]=$tip;
			}



			$prev_yearquarter=$row['value'];

		}
		mysql_free_result($res);
		//$_data=array();
		//$i=0;

		//foreach($data as $__data)
		//   $_data[]=$__data;

		return $data;

	}

	function plot_data_per_week($tipo,$suffix,$from,$to) {
		$data=array();
		//print "$from\n";
		$from=prepare_mysql_datetime($from,'date');
		$to=prepare_mysql_datetime($to,'date');

		//print_r($from);
		$where_from='';
		if ($from['ok'])
			$where_from=sprintf('and `Time Series Date`>=%s ',prepare_mysql($from['mysql_date']));
		$where_to='';
		if ($to['ok'])
			$where_to=sprintf('and `Time Series Date`<=%s ',prepare_mysql($to['mysql_date']));





		$sql=sprintf("SELECT `Time Series Label`,`Time Series Type`,`Time Series Value` as value, YEARWEEK(`Time Series Date`,3)   yearweek ,WEEK(`Time Series Date`,3) as week,YEAR(`Time Series Date`) as year,`Time Series Count` as count ,UNIX_TIMESTAMP(`Time Series Date`) as date from `Time Series Dimension` where  `Time Series Frequency`='Weekly' and  `Time Series Name`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d %s %s order by `Time Series Date`,`Time Series Type` desc"
			,prepare_mysql($this->name)
			,$this->name_key
			,$this->name_key2
			,$where_from
			,$where_to
		);
		//exit($sql);
		$prev_yearweek=array();
		$forecast_region=false;
		$data_region=false;
		$res = mysql_query($sql);
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			if (is_numeric($prev_yearweek)) {
				$diff=$row['value']-$prev_yearweek;
				if ($diff==0)
					$diff_prev_yearweek=_('No change from last week')."\n";
				else
					$diff_prev_yearweek=percentage($diff,$prev_yearweek,1,_('NA'),'%',true)." "._('change (last week)')."\n";
			} else
				$diff_prev_yearweek='';




			if ($tipo=='SI' or $tipo=='PI') {
				$tip=$row['Time Series Label'].' '._('Sales')." ".$row['week']." "._('week')." ".$row['year']."\n".money($row['value'])."\n".$diff_prev_yearweek."(".$row['count']." "._('Invoices').")";

			}
			elseif ($tipo=="PO") {
				$tip=_('Sales')." ".$row['yearweek']."\n".money($row['value'])."\n".$$diff_prev_yearweek."(".$row['count']." "._('Outers Shipped').")";

			}
			elseif ($tipo=='PI' or $tipo=='PP') {
				$tip=$row['Time Series Label']." ".$row['quarter']." "._('week')." ".$row['year']."\n".money($row['value'])."\n".$diff_prev_yearweek."(".$row['count']." "._('Invoices').")";

			}elseif ($tipo=='PartOut') {
				$tip=_('Part').' '.$row['Time Series Label'].' '._('Out')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".number($row['value'])."\n".$diff_prev_yearweek;

			}



			$data[$row['yearweek']]=array(
				'date'=>$row['yearweek']
			);
			// print $row['yearweek']."<br>\n";
			if ($row['Time Series Type']=='First') {
				$first_value=array($row['yearweek'],$row['value'],$tip) ;

			}
			if ($row['Time Series Type']=='Current') {
				$current_value=array($row['yearweek'],$row['value'],$tip) ;

				$data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
				$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
				$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
				$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];
			}
			if ($row['Time Series Type']=='Data') {
				if (!$data_region) {

					if (isset($first_value[0])) {
						$data[$first_value[0]]['tails'.$suffix]=(float) $first_value[1];
						$data[$first_value[0]]['tip_tails'.$suffix]=$first_value[2];
						$data[$row['yearweek']]['tails'.$suffix]=(float) $row['value'];
						$data[$row['yearweek']]['tip_tails'.$suffix]=$tip;
					}

				}
				$data_region=true;

				$data[$row['yearweek']]['value'.$suffix]=(float) $row['value'];
				$data[$row['yearweek']]['tip_value'.$suffix]=$tip;
				$last_complete_value=array($row['yearweek'],$row['value'],$tip) ;
			}
			if ($row['Time Series Type']=='Forecast') {

				if (!$forecast_region) {
					$data[$last_complete_value[0]]['forecast'.$suffix]=(float) $last_complete_value[1];

					// $data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
					//$data[$last_complete_value[0]]['tip_tails'.$suffix]='';
					//$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
					//$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];

				}
				$forecast_region=true;
				$data[$row['yearweek']]['forecast'.$suffix]=(float) $row['value'];
				$data[$row['yearweek']]['tip_forecast'.$suffix]=$tip;
			}



			$prev_yearweek=$row['value'];

		}
		mysql_free_result($res);
		//$_data=array();
		//$i=0;

		//foreach($data as $__data)
		//   $_data[]=$__data;

		return $data;

	}




	function normalized_yearweek($date) {
		$yearweek=false;
		$sql=sprintf("select `Year Week Normalized`,`Normalized Last Day`  from kbase.`Week Dimension` where `First Day`<=%s and `Normalized Last Day`>=%s limit 1"
			,prepare_mysql($date)
			,prepare_mysql($date)
		);
		//print "$sql\n";
		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_array($res2))
			$yearweek=$row2['Year Week Normalized'];

		return $yearweek;

	}



	function guess_number_of_forecats_bins($number_values) {


		if ($number_values<=6)
			$few_points=true;

		if ($number_values<=1)
			return 0;
		elseif ($number_values<=3) {
			$number_period_for_forecasting=1;
		}
		elseif ($number_values<=5) {
			$number_period_for_forecasting=1;
		}
		elseif ($number_values<=7) {
			$number_period_for_forecasting=3;
		}
		elseif ($number_values<=9) {
			$number_period_for_forecasting=4;
		}
		elseif ($number_values<=11) {
			$number_period_for_forecasting=6;
			if (date("m")==5 )
				$number_period_for_forecasting=7;
		}
		elseif ($number_values<=48) {
			$number_period_for_forecasting=12;
			if (date("m")==12 )
				$number_period_for_forecasting=13;

		}
		elseif ($number_values<=72) {
			$number_period_for_forecasting=24;
			if (date("m")==12 )
				$number_period_for_forecasting=15;
		}
		else {
			$number_period_for_forecasting=36;
			if (date("m")==12 )
				$number_period_for_forecasting=37;

		}


		return $number_period_for_forecasting;


	}

}
?>
