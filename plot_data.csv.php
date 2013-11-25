<?php


require_once 'common.php';

setlocale(LC_ALL,'en_GB');

require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {

	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('site_requests'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
		));
	site_requests($data);
	break;

case('category'):
	$data=prepare_values($_REQUEST,array(
			'category_key'=>array('type'=>'key'),
		));
	category_assigned_pie($data);
	break;
case('category_subjects'):
	$data=prepare_values($_REQUEST,array(
			'category_key'=>array('type'=>'key'),
		));
	category_subjects_pie($data);
	break;
case('customer_business_type_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	customer_business_type_pie($data);
	break;
case('customer_business_type_assigned_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	customer_business_type_assigned_pie($data);
	break;
case('customer_referral_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	customer_referral_pie($data);
	break;
case('customer_referral_assigned_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	customer_referral_assigned_pie($data);
	break;
case('top_families'):

	$data=prepare_values($_REQUEST,array(
			'store_keys'=>array('type'=>'string'),
			'period'=>array('type'=>'string'),
			'nr'=>array('type'=>'numeric')

		));
	top_families($data);
	break;
case('top_products'):

	$data=prepare_values($_REQUEST,array(
			'store_keys'=>array('type'=>'string'),
			'period'=>array('type'=>'string'),
			'nr'=>array('type'=>'numeric')

		));
	top_products($data);
	break;
case('top_parts'):

	$data=prepare_values($_REQUEST,array(
			'period'=>array('type'=>'string'),
			'nr'=>array('type'=>'numeric')

		));
	top_parts($data);
	break;
case('top_parts_categories'):

	$data=prepare_values($_REQUEST,array(
			'period'=>array('type'=>'string'),
			'nr'=>array('type'=>'numeric')

		));
	top_parts_categories($data);
	break;

case('number_of_contacts'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	number_of_contacts($data);
	break;

case('number_of_customers'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	number_of_customers($data);
	break;
case('store_departments_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),

		));


	store_departments_pie($data);
	break;
case('store_families_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));

	store_families_pie($data);
	break;
case('store_product_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	store_product_pie($data);
	break;



case('customers_orders_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	customers_orders_pie($data);
	break;
case('customers_data_completeness_pie'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));
	customers_data_completeness_pie($data);
	break;
case('customer_departments_pie'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
		));
	customer_departments_pie($data);
	break;
case('customer_families_pie'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
		));
	customer_families_pie($data);
	break;
case('store_sales'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number')
		));
	store_sales($data);
	break;
case('department_sales'):
	$data=prepare_values($_REQUEST,array(
			'department_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number')
		));
	department_sales($data);
	break;
case('family_sales'):
	$data=prepare_values($_REQUEST,array(
			'family_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number')
		));
	family_sales($data);
	break;
case('category_part_sales'):
	$data=prepare_values($_REQUEST,array(
			'category_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number')
		));

	category_part_sales($data);
	break;

case('product_id_sales'):
	$data=prepare_values($_REQUEST,array(
			'product_id'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number')
		));
	product_id_sales($data);
	break;
case('part_sales'):
	$data=prepare_values($_REQUEST,array(
			'part_sku'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number')
		));
	part_sales($data);
	break;
case('supplier_sales'):
	$data=prepare_values($_REQUEST,array(
			'supplier_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number')
		));
	supplier_sales($data);
	break;
case('stacked_invoice_categories_sales'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
		));


	stacked_invoice_categories_sales($data);
	break;

case('stacked_store_sales'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
		));


	stacked_store_sales($data);
	break;
case('part_location_stock_history'):
	$data=prepare_values($_REQUEST,array(
			'part_sku'=>array('type'=>'key'),
			'location_key'=>array('type'=>'numeric'),
			'output'=>array('type'=>'string'),
		));
	if ($data['output']=='value')
		part_location_stock_value_history($data);
	elseif ($data['output']=='end_day_value')
		part_location_stock_end_day_value_history($data);
	elseif ($data['output']=='commercial_value')
		part_location_stock_commercial_value_history($data);
	else
		part_location_stock_history($data);
	break;

case('warehouse_parts_stock_history'):
	$data=prepare_values($_REQUEST,array(
			'warehouse_key'=>array('type'=>'key'),
			'output'=>array('type'=>'string'),
		));
	if ($data['output']=='value')
		warehouse_parts_stock_value_history($data);
	elseif ($data['output']=='end_day_value')
		warehouse_parts_stock_end_day_value_history($data);
	elseif ($data['output']=='commercial_value')
		warehouse_parts_stock_commercial_value_history($data);

	break;


case('top_countries_sales'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true)
		));
	top_countries_sales_pie($data);
	break;

case('top_regions_sales'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true)
		));
	top_regions_sales_pie($data);
	break;

case('top_countries_sales_by_continent'):
	$data=prepare_values($_REQUEST,array(
			'continent_id'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true)
		));
	top_countries_sales_by_continent_pie($data);
	break;

case('top_regions_sales_by_continent'):
	$data=prepare_values($_REQUEST,array(
			'continent_id'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true)
		));
	top_regions_sales_by_continent_pie($data);
	break;


case('top_countries_sales_in_region'):
	$data=prepare_values($_REQUEST,array(
			'region_id'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true)
		));
	top_countries_sales_in_region_pie($data);
	break;

case('region_sales'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'string'),
			'from'=>array('type'=>'date','optional'=>true),
			'to'=>array('type'=>'date','optional'=>true),
			'use_corporate'=>array('type'=>'number'),
			'region_code'=>array('type'=>'string'),
			'region_level'=>array('type'=>'string')
		));
	region_sales($data);
	break;
}


function part_location_stock_end_day_value_history($data) {

	if ($data['location_key']) {

		$sql=sprintf("select `Date`,`Value At Day Cost Open`,`Value At Day Cost High`,`Value At Day Cost Low`,`Value At Day Cost` from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d order by `Date` desc",
			$data['part_sku'],
			$data['location_key']
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%s,%s,%s,%s,%s\n",$row['Date'],$row['Value At Day Cost Open'],$row['Value At Day Cost High'],$row['Value At Day Cost Low'],$row['Value At Day Cost'],$row['Value At Day Cost']);
		}
	} else {// stock in all locations
		$sql=sprintf("select `Date`,sum(`Value At Day Cost Open`) as open ,max(`Value At Day Cost High`) as high,min(`Value At Day Cost Low`) as low,sum(`Value At Day Cost`) as close from `Inventory Spanshot Fact` where `Part SKU`=%d group by `Date` order by `Date` desc",
			$data['part_sku']

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%.2f,%.2f,%.2f,%.2f,%.2f\n",$row['Date'],$row['open'],$row['close'],$row['open'],$row['close'],$row['close']);
		}

	}

}

function part_location_stock_commercial_value_history($data) {

	if ($data['location_key']) {

		$sql=sprintf("select `Date`,`Value Commercial Open`,`Value Commercial High`,`Value Commercial Low`,`Value Commercial` from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d order by `Date` desc",
			$data['part_sku'],
			$data['location_key']
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%s,%s,%s,%s,%s\n",$row['Date'],$row['Value Commercial Open'],$row['Value Commercial High'],$row['Value Commercial Low'],$row['Value Commercial'],$row['Value Commercial']);
		}
	} else {// stock in all locations
		$sql=sprintf("select `Date`,sum(`Value Commercial Open`) as open ,max(`Value Commercial High`) as high,min(`Value Commercial Low`) as low,sum(`Value Commercial`) as close from `Inventory Spanshot Fact` where `Part SKU`=%d group by `Date` order by `Date` desc",
			$data['part_sku']

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%.2f,%.2f,%.2f,%.2f,%.2f\n",$row['Date'],$row['open'],$row['close'],$row['open'],$row['close'],$row['close']);
		}

	}

}

function part_location_stock_value_history($data) {

	if ($data['location_key']) {

		$sql=sprintf("select `Date`,`Value At Cost Open`,`Value At Cost High`,`Value At Cost Low`,`Value At Cost` from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d order by `Date` desc",
			$data['part_sku'],
			$data['location_key']
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%s,%s,%s,%s,%s\n",$row['Date'],$row['Value At Cost Open'],$row['Value At Cost High'],$row['Value At Cost Low'],$row['Value At Cost'],$row['Value At Cost']);
		}
	} else {// stock in all locations
		$sql=sprintf("select `Date`,sum(`Value At Cost Open`) as open ,max(`Value At Cost High`) as high,min(`Value At Cost Low`) as low,sum(`Value At Cost`) as close from `Inventory Spanshot Fact` where `Part SKU`=%d group by `Date` order by `Date` desc",
			$data['part_sku']

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%.2f,%.2f,%.2f,%.2f,%.2f\n",$row['Date'],$row['open'],$row['close'],$row['open'],$row['close'],$row['close']);
		}

	}

}

function part_location_stock_history($data) {

	if ($data['location_key']) {

		$sql=sprintf("select `Date`,`Quantity Open`,`Quantity High`,`Quantity Low`,`Quantity On Hand` from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d order by `Date` desc",
			$data['part_sku'],
			$data['location_key']
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%s,%s,%s,%s,%s\n",$row['Date'],$row['Quantity Open'],$row['Quantity High'],$row['Quantity Low'],$row['Quantity On Hand'],$row['Quantity On Hand']);
		}
	} else {// stock in all locations
		$sql=sprintf("select `Date`,sum(`Quantity Open`) as open ,sum(`Quantity High`) as high,sum(`Quantity Low`) as low,sum(`Quantity On Hand`) as close from `Inventory Spanshot Fact` where `Part SKU`=%d group by `Date` order by `Date` desc",
			$data['part_sku']

		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			printf("%s,%.2f,%.2f,%.2f,%.2f,%.2f\n",$row['Date'],$row['open'],$row['close'],$row['open'],$row['close'],$row['close']);
		}

	}

}




function warehouse_parts_stock_end_day_value_history($data) {

	$sql=sprintf("select `Date`,`Value At Day Cost Open` as open ,`Value At Day Cost High` as high,`Value At Day Cost Low` as low,`Value At Day Cost` as close from `Inventory Warehouse Spanshot Fact` where `Warehouse Key`=%d order by `Date` desc ",
		$data['warehouse_key']

	);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		printf("%s,%.2f,%.2f,%.2f,%.2f,%.2f\n",$row['Date'],$row['open'],$row['high'],$row['low'],$row['close'],$row['close']);
	}



}

function warehouse_parts_stock_commercial_value_history($data) {


	$sql=sprintf("select `Date`,`Value Commercial Open` as open ,`Value Commercial High` as high,`Value Commercial Low` as low,`Value Commercial` as close from `Inventory Warehouse Spanshot Fact` where `Warehouse Key`=%d order by `Date` desc ",
		$data['warehouse_key']

	);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		printf("%s,%.2f,%.2f,%.2f,%.2f,%.2f\n",$row['Date'],$row['open'],$row['high'],$row['low'],$row['close'],$row['close']);
	}



}

function warehouse_parts_stock_value_history($data) {


	$sql=sprintf("select `Date`,`Value At Cost Open` as open ,`Value At Cost High` as high,`Value At Cost Low` as low,`Value At Cost` as close from `Inventory Warehouse Spanshot Fact` where `Warehouse Key`=%d order by `Date` desc ",
		$data['warehouse_key']

	);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		printf("%s,%.2f,%.2f,%.2f,%.2f,%.2f\n",$row['Date'],$row['open'],$row['high'],$row['low'],$row['close'],$row['close']);
	}



}






function site_requests($data) {

	$timeseries_name='Site Users Requests';

	$sql=sprintf("select `Time Series Date`,`Open`,`High`,`Low`,`Close`,`Volume` from `Time Series Dimension` where `Time Series Name`=%s and `Time Series Name Key`=%d order by `Time Series Date` desc",
		prepare_mysql($timeseries_name),
		$data['site_key']
	);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		printf("%s,%s\n",$row['Time Series Date'],$row['Volume']);
	}


}


function number_of_contacts($data) {

	$sql=sprintf("select `Time Series Date`,`Open`,`High`,`Low`,`Close`,`Volume` from `Time Series Dimension` where `Time Series Name`='contact population' and `Time Series Name Key`=%d order by `Time Series Date` desc",
		$data['store_key']
	);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		printf("%s,%s,%s\n",$row['Time Series Date'],$row['Volume'],$row['Close']);
	}


}
function number_of_customers($data) {
	$sql=sprintf("select `Time Series Date`,`Open`,`High`,`Low`,`Close`,`Volume` from `Time Series Dimension` where `Time Series Name`='customer population' and `Time Series Name Key`=%d order by `Time Series Date` desc",
		$data['store_key']
	);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		printf("%s,%s,%s,%s,%s,%s\n",$row['Time Series Date'],$row['Open'],$row['High'],$row['Low'],$row['Close'],$row['Volume']);
	}


}
function customers_orders_pie($data) {

	$pie_data=array(
		"o51_"=>array('title'=>_('Contacts with more than 50 orders'),'number'=>0,'short_title'=>"50> "._("Orders")),
		"o21_50"=>array('title'=>_('Contacts with 21-50 orders'),'number'=>0,'short_title'=>"21-50 "._("Orders")),
		"o11_20"=>array('title'=>_('Contacts with 11-20 orders'),'number'=>0,'short_title'=>"11-20 "._("Orders")),
		"o5_10"=>array('title'=>_('Contacts with 5-10 orders'),'number'=>0,'short_title'=>"5-10 "._("Orders")),
		"o4"=>array('title'=>_('Contacts with 4 orders'),'number'=>0,'short_title'=>"4 "._("Orders")),
		"o3"=>array('title'=>_('Contacts with 3 orders'),'number'=>0,'short_title'=>"3 "._("Orders")),
		"o2"=>array('title'=>_('Contacts with 2 orders'),'number'=>0,'short_title'=>"2 "._("Orders")),
		"o1"=>array('title'=>_('Contacts with one orders'),'number'=>0,'short_title'=>"1 "._("Order")),
		"o0"=>array('title'=>_('Contacts with no orders'),'number'=>0,'short_title'=>_('No Orders')),
	);

	$number_slices=9;
	$others=0;

	$where='where true';
	if ($data['store_key']) {
		$where=sprintf("where `Customer Store Key`=%d",$data['store_key']);
	}


	$sql=sprintf("select
                 sum(if(`Customer Orders`=0,1,0)) as o0 ,
                 sum(if(`Customer Orders`=1,1,0)) as o1 ,
                 sum(if(`Customer Orders`=2,1,0)) as o2 ,
                 sum(if(`Customer Orders`=3,1,0)) as o3 ,
                 sum(if(`Customer Orders`=4,1,0)) as o4 ,
                 sum(if(`Customer Orders`>=5 and `Customer Orders`<=10,1,0)) as o5_10 ,
                 sum(if(`Customer Orders`>=11 and `Customer Orders`<=20,1,0)) as o11_20 ,
                 sum(if(`Customer Orders`>=21 and `Customer Orders`<=50,1,0)) as o21_50 ,
                 sum(if(`Customer Orders`>=51 ,1,0)) as `o51_`
                 from `Customer Dimension` %s",
		$where
	);

	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		$pie_data['o0']['number']=$row['o0'];
		$pie_data['o1']['number']=$row['o1'];
		$pie_data['o2']['number']=$row['o2'];
		$pie_data['o3']['number']=$row['o3'];
		$pie_data['o5_10']['number']=$row['o5_10'];
		$pie_data['o11_20']['number']=$row['o11_20'];
		$pie_data['o21_50']['number']=$row['o21_50'];
		$pie_data['o51_']['number']=$row['o51_'];

	}


	foreach ($pie_data as $key=>$values) {
		if ($values['number']>0)
			printf("%s;%.2f;;;customers.php?where=data_%s,4s\n",$values['short_title'],$values['number'],$key,$values['title']);
	}






}
function customers_data_completeness_pie($data) {

	$pie_data=array(
		"ok"=>array('title'=>_('Contacts with all data'),'number'=>0,'short_title'=>'Ok'),
		"a"=>array('title'=>_('Contacts missing address'),'number'=>0,'short_title'=>"No Address"),
		"e"=>array('title'=>_('Contacts missing email'),'number'=>0,'short_title'=>'No Email'),
		"t"=>array('title'=>_('Contacts missing telephone'),'number'=>0,'short_title'=>'No Tel'),
		"ae"=>array('title'=>_('Contacts missing address & email'),'number'=>0,'short_title'=>'No Email & Address'),
		"at"=>array('title'=>_('Contacts missing address & telephone'),'number'=>0,'short_title'=>'No Address & Tel'),
		"et"=>array('title'=>_('Contacts missing email & telephone'),'number'=>0,'short_title'=>'No Email & Tel'),
		"aet"=>array('title'=>_('Contacts missing address, email & telephone'),'number'=>0,'short_title'=>"No Email Address Tel"),
	);

	$number_slices=9;
	$others=0;

	$where='where true';
	if ($data['store_key']) {
		$where=sprintf("where `Customer Store Key`=%d",$data['store_key']);
	}


	$sql=sprintf("select  count(*) as total,
                 sum(if(!ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as ok ,

                 sum(if(ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as e ,
                 sum(if(!ISNULL(`Customer Main Email Key`) AND  ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as t ,
                 sum(if(!ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='Yes'  ,1,0)) as a,
                 sum(if(ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='Yes'  ,1,0)) as ae,
                 sum(if(!ISNULL(`Customer Main Email Key`) AND  ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='Yes'  ,1,0)) as at,
                 sum(if(ISNULL(`Customer Main Email Key`) AND  !ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as et,
                 sum(if(ISNULL(`Customer Main Email Key`) AND  ISNULL(`Customer Main Telephone Key`) AND  `Customer Main Address Incomplete`='No'  ,1,0)) as aet
                 from `Customer Dimension` %s",
		$where
	);

	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		$pie_data['a']['number']=$row['a'];
		$pie_data['e']['number']=$row['e'];
		$pie_data['t']['number']=$row['t'];
		$pie_data['ae']['number']=$row['ae'];
		$pie_data['at']['number']=$row['at'];
		$pie_data['et']['number']=$row['et'];
		$pie_data['aet']['number']=$row['aet'];
		$pie_data['ok']['number']=$row['total']-$row['a']-$row['e']-$row['t']-$row['ae']-$row['at']-$row['et']-$row['aet'];
	}


	foreach ($pie_data as $key=>$values) {
		if ($values['number']>0)
			printf("%s;%.2f;;;customers.php?where=data_%s,4s\n",$values['short_title'],$values['number'],$key,$values['title']);
	}






}
function customer_departments_pie($data) {
	$number_slices=9;
	$others=0;
	$sql=sprintf("select count(distinct `Product Main Department Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)  where `Customer Key`=%d",
		$data['customer_key']
	);

	$res=mysql_query($sql);
	// print $sql;
	if ($row=mysql_fetch_assoc($res)) {

		if ($row['amount']>0) {
			if ($row['num_slices']==10) {
				$number_slices=10;
			}
			elseif ($row['num_slices']>10) {
				$others=$row['amount'];

				// printf("%s;%.2f\n",_('Others'),$row['amount']);
			}

		}
	}

	$sql=sprintf("select `Product Main Department Code` ,`Product Main Department Name` ,`Product Main Department Key`,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)  where `Customer Key`=%d group by `Product Main Department Key` order by amount desc limit %d",
		$data['customer_key'],
		$number_slices
	);
	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['amount']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['Product Main Department Name'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Product Main Department Code'],$row['amount'],$row['Product Main Department Key'],$descripton);
			$sum_slices+=$row['amount'];

		}
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
	}

}
function customer_families_pie($data) {

	$number_slices=14;
	$others=0;
	$sql=sprintf("select count(distinct `Product Family Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact` OTF where `Customer Key`=%d",
		$data['customer_key']
	);

	$res=mysql_query($sql);
	// print $sql;
	if ($row=mysql_fetch_assoc($res)) {

		if ($row['amount']>0) {
			if ($row['num_slices']==10) {
				$number_slices=10;
			}
			elseif ($row['num_slices']>10) {
				$others=$row['amount'];

				// printf("%s;%.2f\n",_('Others'),$row['amount']);
			}

		}
	}

	$sql=sprintf("select `Product Family Name`,`Product Family Code`,OTF.`Product Family Key` ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join    `Product Family Dimension` F on (OTF.`Product Family Key`=F.`Product Family Key`)  where `Customer Key`=%d group by OTF.`Product Family Key` order by amount desc  limit %d",
		$data['customer_key'],
		$number_slices
	);
	//print $sql;
	$res=mysql_query($sql);
	$sum_slices=0;
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['amount']>0) {
			$descripton=$row['Product Family Name'];
			printf("%s;%.2f;;;family.php?id=%d;%s\n",$row['Product Family Code'],$row['amount'],$row['Product Family Key'],$descripton);
			$sum_slices+=$row['amount'];
		}
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
	}
}
function store_sales($data) {
	global $user;
	$tmp=preg_split('/\,/', $data['store_key']);
	$stores_keys=array();
	foreach ($tmp as $store_key) {

		if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
			$stores_keys[]=$store_key;
		}
	}

	$graph_data=array();



	if (array_key_exists('to',$data) and $data['to']!='') {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data) and $data['from']!='') {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$stores_keys));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);

	//print $sql;

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data) and $data['to']!='') {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Invoice Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data) and $data['from']!='') {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	}

	$corporate_currency='';
	if ($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange`';
	$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount` %s) as net, count(*) as invoices  from `Invoice Dimension` where  %s and `Invoice Store Key`  in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
		$corporate_currency,
		$dates,
		join(',',$stores_keys)
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['invoices'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}
function department_sales($data) {
	global $user;
	$tmp=preg_split('/\,/', $data['department_key']);
	$departments_keys=array();
	foreach ($tmp as $department_key) {

		if (is_numeric($department_key)) {
			$departments_keys[]=$department_key;
		}
	}

	$graph_data=array();



	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Order Transaction Fact` where `Product Department Key` in (%s)  and `Current Payment State`='Paid'  )",join(',',$departments_keys));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);



	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Invoice Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	}

	$corporate_currency='';
	if ($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange Rate`';
	$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` %s) as net, count(*) as invoices  from `Order Transaction Fact` where  %s and `Product Department Key` in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
		$corporate_currency,
		$dates,
		join(',',$departments_keys)
	);
	// print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['invoices'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}
function family_sales($data) {
	global $user;
	$tmp=preg_split('/\,/', $data['family_key']);
	$familys_keys=array();
	foreach ($tmp as $family_key) {

		if (is_numeric($family_key)) {
			$familys_keys[]=$family_key;
		}
	}

	$graph_data=array();



	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Order Transaction Fact` where `Product Family Key` in (%s)  and `Current Payment State`='Paid'  )",join(',',$familys_keys));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);

	//print $sql;

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Invoice Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	}

	$corporate_currency='';
	if ($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange Rate`';
	$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` %s) as net, count(*) as invoices  from `Order Transaction Fact` where  %s and `Product Family Key` in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
		$corporate_currency,
		$dates,
		join(',',$familys_keys)
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['invoices'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}


function category_part_sales($data) {
	global $user;
	$tmp=preg_split('/\,/', $data['category_key']);
	$categories_keys=array();
	foreach ($tmp as $category_key) {

		if (is_numeric($category_key)) {
			$categories_keys[]=$category_key;
		}
	}

	$graph_data=array();



	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Date`)   from `Inventory Transaction Fact` left join `Category Bridge` on (`Subject`='Part' and `Subject Key`=`Part SKU`) where `Category Key` in (%s)  and `Inventory Transaction Type`='Sale'   )",join(',',$categories_keys));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);



	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	}


	$sql=sprintf("select Date(`Date`) as date,sum(`Inventory Transaction Amount`) as net, count(*) as outers  from `Inventory Transaction Fact` left join `Category Bridge` on (`Subject`='Part' and `Subject Key`=`Part SKU`)  where  %s and `Inventory Transaction Type`='Sale' and `Category Key` in (%s)   group by Date(`Date`) order by `Date` desc",
		$dates,
		join(',',$categories_keys)
	);

	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['outers'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",-1*$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}


function product_id_sales($data) {
	global $user;
	$tmp=preg_split('/\,/', $data['product_id']);
	$product_ids=array();
	foreach ($tmp as $product_id) {

		if (is_numeric($product_id)) {
			$product_ids[]=$product_id;
		}
	}

	$graph_data=array();



	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Order Transaction Fact` where `Product ID` in (%s)  and `Current Payment State`='Paid'  )",join(',',$product_ids));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);

	//print $sql;

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Invoice Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	}

	$corporate_currency='';
	if ($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange Rate`';
	$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount` %s) as net, count(*) as invoices  from `Order Transaction Fact` where  %s and `Product ID` in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
		$corporate_currency,
		$dates,
		join(',',$product_ids)
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['invoices'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}




function stacked_invoice_categories_sales($data) {



	$graph_data=array();

	global $user;
	$tmp=preg_split('/\,/', $data['store_key']);
	$store_keys=array();
	foreach ($tmp as $store_key) {

		if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
			$store_keys[]=$store_key;
		}
	}

	$categories_keys=array();

	$sql=sprintf("select C.`Category Key` from `Category Bridge` B left join `Category Dimension` C on  (`Subject`='Invoice' and B.`Category Key`=C.`Category Key`)  where `Category Store Key` in (%s) group by C.`Category Key`",addslashes(join(',',$store_keys)));
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		$categories_keys[]=$row['Category Key'];


	}


	$number_categories=count($categories_keys);

	$number_stores=count($store_keys);
	$tmp=array();
	for ($i=0; $i<$number_categories; $i++) {
		$tmp['value'.$i]=0;
		$tmp['vol'.$i]=0;
	}

	$from=$data['from'];
	$to=$data['to'];

	if ($to=='')$to=date("Y-m-d");
	if ($from=='') {
		$sql=sprintf("select min(DATE(`Invoice Date`)) as from_date  from `Invoice Dimension` where `Invoice Store Key` in (%s) ",
			join(',',$store_keys)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$from=$row['from_date'];
		}
	}

	if ($to)$to=$to.' 23:59:59';
	if ($from)$from=$from.' 00:00:00';
	
	$where_interval=prepare_mysql_dates($from,$to,'`Date`');

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where true  %s order by `Date` ",
		$where_interval['mysql']

	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['Date']]=$tmp;
	}	
	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');

	$i=0;
	foreach ($categories_keys as $category_key) {

	
		$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net, count(*) as invoices  from `Invoice Dimension` left join `Category Bridge` on (`Subject Key`=`Invoice Key`)  where `Subject`='Invoice'  %s and `Category Key`=%d   group by Date(`Invoice Date`) order by `Date` desc",
			$where_interval['mysql'],
				$category_key
			);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$graph_data[$row['date']]['value'.$i]=sprintf("%.2f",$row['net']);
			$graph_data[$row['date']]['vol'.$i]=$row['invoices'];
		}
		$i++;
	}

	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}

	/*
         if (is_numeric($data['store_key'])) {
             $sql=sprintf("select `Store Key`,Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`) as net, count(*) as invoices  from `Invoice Dimension` where `Invoice Store Key`=%d group by Date(`Invoice Date`) order by Date(`Invoice Date`) desc",
                          $data['store_key']);
             $res=mysql_query($sql);
             while ($row=mysql_fetch_assoc($res)) {
                 $sales_data[$row['date']]
                 printf("%s,%d,%f\n",$row['date'],$row['invoices'],$row['net']);
             }
         }
     }

    */
}


function stacked_store_sales($data) {



	$graph_data=array();

	global $user;
	$tmp=preg_split('/\,/', $data['store_key']);
	$store_keys=array();
	foreach ($tmp as $store_key) {

		if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
			$store_keys[]=$store_key;
		}
	}



	$number_stores=count($store_keys);
	$tmp=array();
	for ($i=0; $i<$number_stores; $i++) {
		$tmp['value'.$i]=0;
		$tmp['vol'.$i]=0;
	}

	$from=$data['from'];
	$to=$data['to'];

	if ($to=='')$to=date("Y-m-d");
	if ($from=='') {
		$sql=sprintf("select min(DATE(`Invoice Date`)) as from_date  from `Invoice Dimension` where `Invoice Store Key` in (%s) ",
			join(',',$store_keys)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$from=$row['from_date'];
		}
	}

	if ($to)$to=$to.' 23:59:59';
	if ($from)$from=$from.' 00:00:00';
	
	$where_interval=prepare_mysql_dates($from,$to,'`Date`');

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where true  %s order by `Date` ",
		$where_interval['mysql']

	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['Date']]=$tmp;
	}	
	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	
	$i=0;
	foreach ($store_keys as $store_key) {

		
		$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net, count(*) as invoices  from `Invoice Dimension` where  `Invoice Store Key`=%d %s  group by Date(`Invoice Date`) order by `Date` desc",
			$store_key,
			$where_interval['mysql']
			
			);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$graph_data[$row['date']]['value'.$i]=sprintf("%.2f",$row['net']);
			$graph_data[$row['date']]['vol'.$i]=$row['invoices'];
		}
		$i++;
	}

	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}

	/*
         if (is_numeric($data['store_key'])) {
             $sql=sprintf("select `Store Key`,Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`) as net, count(*) as invoices  from `Invoice Dimension` where `Invoice Store Key`=%d group by Date(`Invoice Date`) order by Date(`Invoice Date`) desc",
                          $data['store_key']);
             $res=mysql_query($sql);
             while ($row=mysql_fetch_assoc($res)) {
                 $sales_data[$row['date']]
                 printf("%s,%d,%f\n",$row['date'],$row['invoices'],$row['net']);
             }
         }
     }

    */
}
function top_families($data) {

	$max_slices=$data['nr'];
	$store_keys=preg_split('/,/',$data['store_keys']);

	if (!is_array($store_keys) or count($store_keys)==0) {
		return;
	}
	$valid_store_keys=array();
	foreach ($store_keys as $store_key) {
		if (is_numeric($store_key))
			$valid_store_keys[]=$store_key;
	}
	if (count($valid_store_keys)==0)return;

	$period=$data['period'];


	$db_interval=get_interval_db_name($period);
	$field='(`Product Family DC '.$db_interval.' Acc Invoiced Amount`)';



	$total=0;
	$sql=sprintf("select sum%s as sales from `Product Family Dimension` F left join  `Product Family Default Currency` DC on (F.`Product Family Key`=DC.`Product Family Key`) where `Product Family Store Key` in (%s)  ",
		$field,
		join(",",$valid_store_keys)
	);
	// print $sql;
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['sales'];
	}

	$others=$total;
	$sql=sprintf("select `Product Family Store Code`,`Product Family Name`,F.`Product Family Key`,`Product Family Code`,%s as sales from `Product Family Dimension` F left join  `Product Family Default Currency` DC on (F.`Product Family Key`=DC.`Product Family Key`) where `Product Family Store Key` in (%s) order by sales desc limit %d ",
		$field,
		join(",",$valid_store_keys),
		$max_slices
	);
	$res=mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_assoc($res)) {
		$descripton='';//$row['Product Family Name'];
		$descripton=$row['Product Family Store Code'].' '.$row['Product Family Code'];
		$code=$row['Product Family Code'];
		printf("%s;%.2f;;;family.php?id=%d;%s\n",$code,$row['sales'],$row['Product Family Key'],$descripton);
		$others-=$row['sales'];
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others);
	}

}

function top_products($data) {

	$max_slices=$data['nr'];
	$store_keys=preg_split('/,/',$data['store_keys']);

	if (!is_array($store_keys) or count($store_keys)==0) {
		return;
	}
	$valid_store_keys=array();
	foreach ($store_keys as $store_key) {
		if (is_numeric($store_key))
			$valid_store_keys[]=$store_key;
	}
	if (count($valid_store_keys)==0)return;

	$period=$data['period'];


	$db_interval=get_interval_db_name($period);
	$field='(`Product ID DC '.$db_interval.' Acc Invoiced Amount`)';

	$total=0;
	$sql=sprintf("select sum%s as sales from `Product Dimension` F left join  `Product ID Default Currency` DC on (F.`Product ID`=DC.`Product ID`) where `Product Store Key` in (%s)  ",
		$field,
		join(",",$valid_store_keys)
	);
	//print $sql;
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['sales'];
	}

	$others=$total;
	$sql=sprintf("select `Store Code`,`Product Name`,F.`Product ID`,`Product Code`,%s as sales from `Product Dimension` F left join  `Product ID Default Currency` DC on (F.`Product ID`=DC.`Product ID`) left join `Store Dimension` S on (`Store Key`=`Product Store Key`)  where `Product Store Key` in (%s) order by sales desc limit %d ",
		$field,
		join(",",$valid_store_keys),
		$max_slices
	);
	$res=mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_assoc($res)) {
		$descripton='';//$row['Product Name'];
		$descripton=$row['Store Code'].' '.$row['Product Code'];
		$code=$row['Product Code'];
		printf("%s;%.2f;;;product.php?pid=%d;%s\n",$code,$row['sales'],$row['Product ID'],$descripton);
		$others-=$row['sales'];
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others);
	}

}

function top_parts($data) {

	global $user;

	$max_slices=$data['nr'];



	$period=$data['period'];


	$warehouses=join(',',$user->warehouses);
	if ($warehouses=='')$warehouses=0;


	if (!$warehouses)
		$where=sprintf('  false ');

	else {
		$where=sprintf('  `Warehouse Key` in (%s) ',$warehouses);
	}

	$db_interval=get_interval_db_name($period);
	$field='(`Part '.$db_interval.' Acc Sold Amount`)';


	$total=0;
	$sql=sprintf("select sum%s as sales from `Part Dimension` P  left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`) where  $where   ",
		$field
	);
	//print $sql;
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['sales'];
	}

	$others=$total;
	$sql=sprintf("select `Part Unit Description`,P.`Part SKU` ,%s as sales from `Part Dimension` P  left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`) where  $where   order by sales desc limit %d ",
		$field,
		$max_slices
	);
	$res=mysql_query($sql);
	// print $sql;
	while ($row=mysql_fetch_assoc($res)) {
		$descripton=$row['Part Unit Description'];
		$code=sprintf("SKU%05d",$row['Part SKU']);
		printf("%s;%.2f;;;part.php?sku=%d;%s\n",$code,$row['sales'],$row['Part SKU'],$descripton);
		$others-=$row['sales'];
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others);
	}

}

function top_parts_categories($data) {

	global $user;

	$max_slices=$data['nr'];



	$period=$data['period'];


	$warehouses=join(',',$user->warehouses);
	if ($warehouses=='')$warehouses=0;


	if (!$warehouses)
		$where=sprintf('  false ');

	else {


		$sql=sprintf("select GROUP_CONCAT(`Warehouse Family Category Key`) as root_category from `Warehouse Dimension` where `Warehouse Key` in (%s)",$warehouses);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$root_category=$row['root_category'];
		}


		$where=sprintf(" `Category Subject`='Part' and  `Category Parent Key` in (%s)",$root_category);



	}


	$db_interval=get_interval_db_name($period);
	$field='(`Part Category '.$db_interval.' Acc Sold Amount`)';



	$total=0;
	$sql=sprintf("select sum%s as sales from `Category Dimension` C  left join `Part Category Dimension` PC on (C.`Category Key`=PC.`Part Category Key`)  where  $where   ",
		$field
	);
	//print $sql;
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['sales'];
	}

	$others=$total;
	$sql=sprintf("select `Category Label`,`Category Key` ,%s as sales from `Category Dimension` C  left join `Part Category Dimension` PC on (C.`Category Key`=PC.`Part Category Key`) where  $where   order by sales desc limit %d ",
		$field,
		$max_slices
	);
	$res=mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_assoc($res)) {
		$descripton=$row['Category Label'];
		$code=$row['Category Label'];
		printf("%s;%.2f;;;part_categories.php?id=%d;%s\n",$code,$row['sales'],$row['Category Key'],$descripton);
		$others-=$row['sales'];
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others);
	}

}

function store_departments_pie($data) {
	$number_slices=9;
	$others=0;



	$sql=sprintf("select count(distinct OTF.`Product Department Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Department Dimension` D on (D.`Product Department Key`=OTF.`Product Department Key`)  where OTF.`Store Key`=%d",
		$data['store_key']
	);

	$res=mysql_query($sql);
	// print $sql;
	if ($row=mysql_fetch_assoc($res)) {

		if ($row['amount']>0) {
			if ($row['num_slices']==10) {
				$number_slices=10;
			}
			elseif ($row['num_slices']>10) {
				$others=$row['amount'];

				// printf("%s;%.2f\n",_('Others'),$row['amount']);
			}

		}
	}

	$sql=sprintf("select `Product Department Code` ,`Product Department Name` ,OTF.`Product Department Key`,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Department Dimension` D on (D.`Product Department Key`=OTF.`Product Department Key`)  where OTF.`Store Key`=%d group by OTF.`Product Department Key` order by amount desc limit %d",
		$data['store_key'],
		$number_slices
	);
	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['amount']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['Product Department Name'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Product Department Code'],$row['amount'],$row['Product Department Key'],$descripton);
			$sum_slices+=$row['amount'];

		}
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
	}

}
function store_families_pie($data) {
	$number_slices=14;
	$others=0;



	$sql=sprintf("select count(distinct OTF.`Product Family Key`) num_slices ,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Family Dimension` D on (D.`Product Family Key`=OTF.`Product Family Key`)  where OTF.`Store Key`=%d",
		$data['store_key']
	);

	$res=mysql_query($sql);
	// print $sql;
	if ($row=mysql_fetch_assoc($res)) {

		if ($row['amount']>0) {
			if ($row['num_slices']==10) {
				$number_slices=10;
			}
			elseif ($row['num_slices']>10) {
				$others=$row['amount'];

				// printf("%s;%.2f\n",_('Others'),$row['amount']);
			}

		}
	}

	$sql=sprintf("select `Product Family Code` ,`Product Family Name` ,OTF.`Product Family Key`,sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as amount   from `Order Transaction Fact`  OTF left join `Product Family Dimension` D on (D.`Product Family Key`=OTF.`Product Family Key`)  where OTF.`Store Key`=%d group by OTF.`Product Family Key` order by amount desc limit %d",
		$data['store_key'],
		$number_slices
	);
	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['amount']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['Product Family Name'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Product Family Code'],$row['amount'],$row['Product Family Key'],$descripton);
			$sum_slices+=$row['amount'];

		}
	}

	if ($others) {
		printf("%s;%.2f;true\n",_('Others'),$others-$sum_slices);
	}

}


function category_assigned_pie($data) {

	switch ($row['Category Subject']) {
	case 'Customer':
		$link='customer_category.php';
		$table='`Category Dimension`';
		$where='';
		break;
	case 'Product':
		$link='product_category.php';
		$table='`Category Dimension`';
		$where='';
		break;
	case 'Supplier':
		$link='supplier_category.php';
		$table='`Category Dimension`';
		$where='';
		break;
	case 'Family':
		$link='family_category.php';
		$table='`Category Dimension`';
		$where='';
		break;
	case 'Invoice':
		$link='invoice_category.php';
		$table='`Category Dimension`';
		$where='';
		break;
	case 'Part':
		$link='part_category.php';
		$table='`Category Dimension`';
		$where='';
		break;
	default:
		$link='';
		$table='`Category Dimension`';
		$where='';
		break;
	}


	$sql=sprintf("select `Category Subject`,`Category Key`,`Category Number Subjects`,`Category Subjects Not Assigned` from %s where  `Category Key`=%d %s ",
		$table,
		$data['category_key'],
		$where
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {



		printf("%s;%d;;ff0000;;%s;40\n",_('No assigned'),$row['Category Subjects Not Assigned'],'');
		printf("%s;%d;true;B0DE09;$link?id=%d;%s\n",_('Assigned'),$row['Category Number Subjects'],$row['Category Key'],'');
	}
}




function category_subjects_pie($data) {
	$max_number_slices=10;
	$sql=sprintf("select `Category Children`,`Category Subject`,`Category Key`,`Category Number Subjects`,`Category Subjects Not Assigned` from `Category Dimension` where `Category Key`=%d",$data['category_key']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		switch ($row['Category Subject']) {
		case 'Customer':
			$link='customer_category.php';
			break;
		case 'Product':
			$link='product_category.php';
			break;
		case 'Supplier':
			$link='supplier_category.php';
			break;
		case 'Family':
			$link='family_category.php';
			break;
		case 'Invoice':
			$link='invoice_category.php';
			break;
		case 'Part':
			$link='part_category.php';
			break;
		default:
			$link='';
			break;
		}

		$sql=sprintf("select `Category Key`,`Category Number Subjects`,`Category Label` from `Category Dimension` where  `Category Parent Key`=%d order by `Category Number Subjects` desc limit %d ",
			$row['Category Key'],
			$max_number_slices
		);

		$res2=mysql_query($sql);
		$slices_number_subjects=0;
		while ($row2=mysql_fetch_assoc($res2)) {
			$slices_number_subjects+=$row2['Category Number Subjects'];
			printf("%s;%d;;;$link?id=%d;%s\n",$row2['Category Label'],$row2['Category Number Subjects'],$row2['Category Key'],'');
		}
		if ($row['Category Children']>$max_number_slices) {
			printf("%s;%d;;;$link?id=%d;%s\n",_('Other Categories'),$row['Category Number Subjects']-$slices_number_subjects,0,'');
		}


	}
}


//-------

function top_countries_sales_pie($data) {
	$number_slices=5;
	$others=0;

	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(DATE(`Invoice Date`))   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$store_keys));
	}




	$sql = sprintf("SELECT `Country Name`, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`)  WHERE %s   group by `Invoice Billing Country 2 Alpha Code` ORDER BY net  DESC LIMIT 5",
		$dates
	);


	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['net']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['Country Name'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Country Name'],$row['net'],$row['net'],$descripton);
			$sum_slices+=$row['net'];


		}
	}

	$sql=sprintf("SELECT sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension`  WHERE %s",
		$dates
	);
	//print $sql;

	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$total_net = $row['net'];
	printf("%s;%.2f;true\n",_('Others'),$total_net-$sum_slices);


}


function top_regions_sales_pie($data) {
	$number_slices=5;
	$others=0;

	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(DATE(`Invoice Date`))   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$store_keys));
	}



	$sql = sprintf("SELECT `World Region` as region, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) WHERE %s group by region ORDER BY net  DESC LIMIT 5",
		$dates
	);


	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {


		if ($row['net']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['region'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['region'],$row['net'],$row['net'],$descripton);
			$sum_slices+=$row['net'];


		}
	}

	$sql=sprintf("SELECT sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension`  WHERE %s",
		$dates
	);
	//print $sql;

	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$total_net = $row['net'];
	printf("%s;%.2f;true\n",_('Others'),$total_net-$sum_slices);


}


function top_countries_sales_by_continent_pie($data) {
	$number_slices=5;
	$others=0;

	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(DATE(`Invoice Date`))   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$store_keys));
	}
	if (array_key_exists('continent_id',$data)) {
		$dates.=sprintf("and `Continent Code`=%s  ",prepare_mysql($data['continent_id']));
	}



	$sql = sprintf("SELECT `Country Name`, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`)  WHERE %s   group by `Invoice Billing Country 2 Alpha Code` ORDER BY net  DESC LIMIT 5",
		$dates
	);


	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['net']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['Country Name'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Country Name'],$row['net'],$row['net'],$descripton);
			$sum_slices+=$row['net'];


		}
	}

	$sql=sprintf("SELECT sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`)  WHERE %s",
		$dates
	);
	//print $sql;

	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$total_net = $row['net'];
	printf("%s;%.2f;true\n",_('Others'),$total_net-$sum_slices);


}


function top_regions_sales_by_continent_pie($data) {
	$number_slices=5;
	$others=0;

	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(DATE(`Invoice Date`))   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$store_keys));
	}
	if (array_key_exists('continent_id',$data)) {
		$dates.=sprintf("and `Continent Code`=%s  ",prepare_mysql($data['continent_id']));
	}


	$sql = sprintf("SELECT `World Region` as region, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) WHERE %s group by region ORDER BY net  DESC LIMIT 5",
		$dates
	);


	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {


		if ($row['net']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['region'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['region'],$row['net'],$row['net'],$descripton);
			$sum_slices+=$row['net'];


		}
	}

	$sql=sprintf("SELECT sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) WHERE %s",
		$dates
	);
	//print $sql;

	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$total_net = $row['net'];
	printf("%s;%.2f;true\n",_('Others'),$total_net-$sum_slices);


}


function top_countries_sales_in_region_pie($data) {
	$number_slices=5;
	$others=0;

	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(DATE(`Invoice Date`))   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$store_keys));
	}
	if (array_key_exists('region_id',$data)) {
		$dates.=sprintf("and `World Region Code`=%s  ",prepare_mysql($data['region_id']));
	}


	$sql = sprintf("SELECT `Country Name`, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) WHERE %s group by `Invoice Billing Country 2 Alpha Code` ORDER BY net  DESC LIMIT 5",
		$dates
	);


	//print $sql;
	$sum_slices=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {


		if ($row['net']>0) {
			// printf("%s;%.2f\n",$row['Product Main Department Code'],$row['amount']);
			$descripton=$row['Country Name'];
			printf("%s;%.2f;;;department.php?id=%d;%s\n",$row['Country Name'],$row['net'],$row['net'],$descripton);
			$sum_slices+=$row['net'];


		}
	}

	$sql=sprintf("SELECT sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) WHERE %s",
		$dates
	);
	//print $sql;

	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$total_net = $row['net'];
	printf("%s;%.2f;true\n",_('Others'),$total_net-$sum_slices);


}

function region_sales($data) {
	global $user;

	//$where_region=prepare_mysql($data['region_code']);

	$tmp=preg_split('/\,/', $data['store_key']);
	$stores_keys=array();
	foreach ($tmp as $store_key) {

		if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
			$stores_keys[]=$store_key;
		}
	}





	$graph_data=array();



	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Invoice Date`)   from `Invoice Dimension` where `Invoice Store Key` in (%s) )  ",join(',',$stores_keys));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);

	//print $sql;

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Invoice Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Invoice Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Invoice Date`>=%s  ",prepare_mysql($data['from']));
	}

	switch ($data['region_level']) {
	case 'Region':
		$where_region .= sprintf("`World Region Code`=%s",prepare_mysql($data['region_code']));
		break;
	case 'Country':
		$where_region .= sprintf("`Country Code`= %s", prepare_mysql($data['region_code']));
		break;
	}

	$corporate_currency='';
	if ($data['use_corporate'])$corporate_currency=' *`Invoice Currency Exchange`';
	$sql=sprintf("select Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount` %s) as net, count(*) as invoices  from `Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) where %s and  %s and `Invoice Store Key`  in (%s)   group by Date(`Invoice Date`) order by `Date` desc",
		$corporate_currency,
		$where_region,
		$dates,
		join(',',$stores_keys)
	);

	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['invoices'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}

function part_sales($data) {
	global $user;
	$tmp=preg_split('/\,/', $data['part_sku']);
	$parts_skus=array();
	foreach ($tmp as $part_sku) {

		if (is_numeric($part_sku)) {
			$parts_skus[]=$part_sku;
		}
	}

	$graph_data=array();



	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Date`)   from `Inventory Transaction Fact` where `Part SKU` in (%s)  and `Inventory Transaction Type`='Sale'   )",join(',',$parts_skus));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);

	//print $sql;

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	}

	$sql=sprintf("select Date(`Date`) as date,sum(`Inventory Transaction Amount`) as net, count(*) as outers  from `Inventory Transaction Fact` where  %s and `Inventory Transaction Type`='Sale' and `Part SKU` in (%s)   group by Date(`Date`) order by `Date` desc",

		$dates,
		join(',',$parts_skus)
	);
	// print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['outers'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",-1*$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}


function supplier_sales($data) {
	global $user;
	$tmp=preg_split('/\,/', $data['supplier_key']);
	$supplier_keys=array();
	foreach ($tmp as $part_sku) {

		if (is_numeric($part_sku)) {
			$supplier_keys[]=$part_sku;
		}
	}

	$graph_data=array();



	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	} else {
		$dates.=sprintf("and  `Date`>= ( select min(`Date`)   from `Inventory Transaction Fact` where `Supplier Key` in (%s)  and `Inventory Transaction Type`='Sale'   )",join(',',$supplier_keys));
	}

	$sql=sprintf("select  `Date` from kbase.`Date Dimension` where  %s order by `Date` desc",
		$dates

	);



	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$graph_data[$row['Date']]['vol']=0;

		$graph_data[$row['Date']]['value']=0;
		//$graph_data[$row['Date']]['date']=$row['Date'];

	}


	if (array_key_exists('to',$data)) {
		$dates=sprintf(" `Date`<=%s  ",prepare_mysql($data['to']));
	} else {
		$dates=sprintf(" `Date`<=NOW()  ");
	}
	if (array_key_exists('from',$data)) {
		$dates.=sprintf("and `Date`>=%s  ",prepare_mysql($data['from']));
	}

	$sql=sprintf("select Date(`Date`) as date,sum(`Inventory Transaction Amount`) as net, count(*) as outers  from `Inventory Transaction Fact` where  %s and `Inventory Transaction Type`='Sale' and `Supplier Key` in (%s)   group by Date(`Date`) order by `Date` desc",

		$dates,
		join(',',$supplier_keys)
	);
	// print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$graph_data[$row['date']]['vol']=$row['outers'];
		$graph_data[$row['date']]['value']=sprintf("%.2f",-1*$row['net']);
	}



	$out='';
	//print_r($graph_data);
	foreach ($graph_data as $key=>$value) {
		print $key.','.join(',',$value)."\n";
	}


}



?>
