<?php
/*
 File: region.php

 UI product page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'common_date_functions.php';

include_once 'class.Address.php';
$ammap_path='external_libs/ammap';
$smarty->assign('ammap_path',$ammap_path);


$parent_page='product';
$smarty->assign('page',$parent_page);


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'css/calendar.css',
	'theme.css.php'
);

$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'region.js.php',
	'js/localize_calendar.js',
	'js/reports_calendar.js',
	$ammap_path.'/ammap/swfobject.js'

);
$report_name='report_geo_sales';

if (isset($_REQUEST['country'])) {
	$mode='country';
	$tag=$_REQUEST['country'];
}
elseif (isset($_REQUEST['wregion'])) {
	$mode='wregion';
	$tag=$_REQUEST['wregion'];


}
elseif (isset($_REQUEST['continent'])) {

	$mode='continent';
	$tag=$_REQUEST['continent'];


}
elseif (isset($_REQUEST['world'])) {
	$mode='world';
	$tag='world';
}else {
	$mode=$_SESSION['state'][$report_name]['mode'];
	$tag=$_SESSION['state'][$report_name]['mode_key'];
}

$_SESSION['state'][$report_name]['mode']=$mode;
$_SESSION['state'][$report_name]['mode_key']=$tag;




$root_title=_('Geographical Sales Report');
$smarty->assign('report_url','report_geo_sales.php');

if ($_SESSION['state'][$report_name]['store_keys']=='all')
	$store_keys=join(',',$user->stores);
else
	$store_keys=$_SESSION['state'][$report_name]['store_keys'];



switch ($mode) {
case 'world':
	$tag='world';
	$js_files[]='report_geo_sales_world.js.php';
	$view=$_SESSION['state'][$report_name]['world']['view'];
	$map_link=$_SESSION['state'][$report_name]['world']['map_links'];


	$smarty->assign('settings_file','conf/world_heatmap_settings.xml');
	$smarty->assign('view',$view);
	$smarty->assign('map_links',$map_link);


	$template='report_geo_sales_world.tpl';
	break;
case 'wregion':
	$smarty->assign('plot_tipo',$_SESSION['state'][$report_name]['wregion']['plot_tipo']);

	$sql=sprintf("select `World Region`,`World Region Code`,`Continent`,`Continent Code` from kbase.`Country Dimension` where `World Region Code`=%s",prepare_mysql($tag));
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$smarty->assign('wregion_name',$row['World Region']);
		$smarty->assign('continent_name',$row['Continent']);

		$smarty->assign('continent_code',$row['Continent Code']);
		// print $row['World Region Code'];
		$_SESSION['state']['wregion']['code']=$row['World Region Code'];
	} else {

		header('Location: report_geo_sales.php?world?error');
		//print $sql;
		exit;

	}


	$js_files[]='report_geo_sales_wregion.js.php';
	$js_files[]='common_customers.js.php';
	$smarty->assign('wregion_code',$tag);
	$template='report_geo_sales_wregion.tpl';
	$_SESSION['state']['wregion']['code']=$tag;




	$view=$_SESSION['state'][$report_name]['wregion']['view'];
	$map_link=$_SESSION['state'][$report_name]['wregion']['map_links'];
	$smarty->assign('view',$view);
	$smarty->assign('map_links',$map_link);



	break;
case 'continent':
	$js_files[]='report_geo_sales_continent.js.php';
	$smarty->assign('continent_code',$tag);
	$template='report_geo_sales_continent.tpl';
	$_SESSION['state']['continent']['code']=$tag;


	$view=$_SESSION['state'][$report_name]['continent']['view'];
	$map_link=$_SESSION['state'][$report_name]['continent']['map_links'];
	$smarty->assign('view',$view);
	$smarty->assign('map_links',$map_link);



	break;

case 'country':

	$country=new Country('code',  Address::parse_country($tag));
	$smarty->assign('plot_tipo',$_SESSION['state'][$report_name]['country']['plot_tipo']);
	$sql=sprintf("select `World Region`,`World Region Code`,`Continent`,`Continent Code`, `Country Name`from kbase.`Country Dimension` where `Country Code`=%s",prepare_mysql($country->data['Country Code']));
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$smarty->assign('wregion_name',$row['World Region']);
		$smarty->assign('wregion_code',$row['World Region Code']);
		$smarty->assign('continent_name',$row['Continent']);
		$smarty->assign('continent_code',$row['Continent Code']);
		$smarty->assign('country_name',$row['Country Name']);
	} else {
		header('Location: report_geo_sales.php?world');
		//print $sql;
		exit;
	}
	$view=$_SESSION['state'][$report_name]['country']['view'];

	$js_files[]='report_geo_sales_country.js.php';
	$js_files[]='common_customers.js.php';

	$smarty->assign('country_code',$tag);

	$smarty->assign('customer_view',$_SESSION['state']['customers']['customers']['view']);
	$_SESSION['state']['country']['code']=$tag;
	$smarty->assign('country',$country);
	$smarty->assign('view',$view);
	$template='report_geo_sales_country.tpl';

}

$_SESSION['state']['region']['tag']=$tag;
$_SESSION['state']['region']['mode']=$mode;

$_SESSION['state']['region']['orders']['mode']=$mode;
$_SESSION['state']['region']['customers']['mode']=$mode;

if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['report_customers']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['report_customers']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['report_customers']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['report_customers']['period']=$period;
$_SESSION['state']['report_customers']['from']=$from;
$_SESSION['state']['report_customers']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


$tipo_filter0=$_SESSION['state']['world']['countries']['f_field'];
$filter_menu0=array(
	'country_code'=>array('db_key'=>'country_code','menu_label'=>_('Country Code'),'label'=>_('Code')),
	'wregion_code'=>array('db_key'=>'wregion_code','menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
	'continent_code'=>array('db_key'=>'continent_code','menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
);

$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',$_SESSION['state']['world']['countries']['f_value']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);


$tipo_filter1=$_SESSION['state']['world']['wregions']['f_field'];
$filter_menu1=array(
	'wregion_code'=>array('db_key'=>'wregion_code','menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
	'continent_code'=>array('db_key'=>'continent_code','menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
);

$smarty->assign('filter_name1',$filter_menu0[$tipo_filter1]['label']);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['world']['wregions']['f_value']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);











//Top countries in the world
$top_countries=array();
$sql = sprintf("SELECT `Country Name`, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`)  WHERE `Invoice Date`>%s and  `Invoice Date`<%s group by `Invoice Billing Country 2 Alpha Code` ORDER BY net  DESC LIMIT 5",
	prepare_mysql($from),
	prepare_mysql($to)
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$top_countries[]=array('country'=>$row['Country Name'],'sales'=>money($row['net'],$corporate_currency_symbol));
}
$smarty->assign('top_countries',$top_countries);

//Top regions in the world
$top_regions=array();

$sql = sprintf("SELECT `World Region` as region, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) WHERE `Invoice Date`>%s and  `Invoice Date`<%s group by region ORDER BY net  DESC LIMIT 5",

	prepare_mysql($from),
	prepare_mysql($to)
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$top_regions[]=array('region'=>$row['region'],'sales'=>money($row['net'],$corporate_currency_symbol));
}

$smarty->assign('top_regions',$top_regions);

//Top countries by Continent
$top_countries_in_continent=array();

$sql = sprintf("SELECT `Country Name`, `World Region`, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`)  WHERE `Invoice Date`>%s and  `Invoice Date`<%s and `Continent Code`=%s group by `World Region`, `Invoice Billing Country 2 Alpha Code` ORDER BY net  DESC LIMIT 5",
	prepare_mysql($from),
	prepare_mysql($to),
	prepare_mysql($tag)
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$top_countries_in_continent[]=array('country'=>$row['Country Name'],'sales'=>money($row['net'],$corporate_currency_symbol));
}

$smarty->assign('top_countries_in_continent',$top_countries_in_continent);

//Top Regions by Continent
$top_regions_in_continent=array();

$sql = sprintf("SELECT `World Region` as region, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`) WHERE `Invoice Date`>%s and  `Invoice Date`<%s and `Continent Code`=%s group by region ORDER BY net  DESC LIMIT 5",
	prepare_mysql($from),
	prepare_mysql($to),
	prepare_mysql($tag)
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$top_regions_in_continent[]=array('region'=>$row['region'],'sales'=>money($row['net'],$corporate_currency_symbol));
}


$smarty->assign('top_regions_in_continent',$top_regions_in_continent);

//Top Countries in the region
$top_countries_in_region=array();

$sql = sprintf("SELECT `Country Name`, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`)  WHERE `Invoice Date`>=%s and  `Invoice Date`<=%s and `World Region Code`=%s group by  `Invoice Billing Country 2 Alpha Code` ORDER BY net  DESC LIMIT 5",
	prepare_mysql($from),
	prepare_mysql($to),
	prepare_mysql($tag)
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$top_countries_in_region[]=array('country'=>$row['Country Name'],'sales'=>money($row['net'],$corporate_currency_symbol));
}

$smarty->assign('top_countries_in_region',$top_countries_in_region);


//Customers by country
$customers_by_country=array();

$sql = sprintf("SELECT `Invoice Customer Name`, `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net  FROM dw.`Invoice Dimension` left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=`Invoice Dimension`.`Invoice Billing Country 2 Alpha Code`)  WHERE `Invoice Date`>=%s and  `Invoice Date`<=%s and `World Region Code`=%s group by  `Invoice Billing Country 2 Alpha Code` ORDER BY net  DESC LIMIT 5",
	prepare_mysql($from),
	prepare_mysql($to),
	prepare_mysql($tag)
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$top_countries_in_region[]=array('country'=>$row['Country Name'],'sales'=>money($row['net'],$corporate_currency_symbol));
}

$smarty->assign('top_countries_in_region',$top_countries_in_region);




$tipo_filter2=$_SESSION['state']['world']['continents']['f_field'];
$filter_menu2=array(
	'continent_code'=>array('db_key'=>'continent_code','menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
);

$smarty->assign('filter_name2',$filter_menu0[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter1',$tipo_filter2);
$smarty->assign('filter_value2',$_SESSION['state']['world']['continents']['f_value']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('no_sales_message',_("There's no sales from"));
$smarty->assign('parent','reports');
$smarty->assign('title',_('Sales by location'));




$smarty->display($template);
?>
