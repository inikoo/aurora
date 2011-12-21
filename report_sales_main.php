<?php
include_once('common.php');
include_once('report_functions.php');
include_once('class.Store.php');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'theme.css.php'
           );


$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'external_libs/amstock/amstock/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              //		'report_sales.js.php',
              'report_sales_main.js.php',
              'js/calendar_interval.js',
              'reports_calendar.js.php',
              'js/dropdown.js'

          );

$root_title=_('Sales Report');

include_once('reports_list.php');

$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



if (isset($_REQUEST['tipo'])) {
    $tipo=$_REQUEST['tipo'];
    $_SESSION['state']['report_sales']['tipo']=$tipo;
} else
    $tipo=$_SESSION['state']['report_sales']['tipo'];
    
  
 
    

$sql=sprintf("select count(*) as num_stores,GROUP_CONCAT(Distinct `Currency Symbol`) as store_currencies from  `Store Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`Store Currency Code`) ");
$res=mysql_query($sql);

if ($row=mysql_fetch_array($res)) {
    $num_stores=$row['num_stores'];
    $store_currencies=$row['store_currencies'];
} else {
    exit("no stores");
}

if ($_SESSION['state']['report_sales']['store_keys']=='all') {
    $store_keys=join(',',$user->stores);
    $formated_store_keys='all';
} else {
    $store_keys=$_SESSION['state']['report_sales']['store_keys'];
    $formated_store_keys=$store_keys;

}

if ($store_keys=='all') {
    global $user;
    $store_keys=join(',',$user->stores);

}

$am_safe_store_keys=preg_replace('/,/','|',$store_keys);




$smarty->assign('store_currencies',$store_currencies);
$smarty->assign('corporate_currency_symbol',$corporate_currency_symbol);

$store_key=$store_keys;


$smarty->assign('view',$_SESSION['state']['report_sales']['view']);

//print_r($_SESSION['state']['report_sales']['currency']);

$smarty->assign('currencies',$_SESSION['state']['report_sales']['currency']);
$smarty->assign('am_safe_store_keys',$am_safe_store_keys);

$smarty->assign('store_keys',$store_keys);
$smarty->assign('formated_store_keys',$formated_store_keys);
$report_name='report_sales';

include_once('report_dates.php');
$smarty->assign('report_url','report_sales_main.php');

$_SESSION['state']['report_sales']['to']=$to;
$_SESSION['state']['report_sales']['from']=$from;
$_SESSION['state']['report_sales']['period']=$period;



global $myconf;

/* $valid_rates=array( */
/* 		   array('date'=>'01-01-2000','rate'=>17.5), */
/* 		   array('date'=>'01-12-2008','rate'=>15) */
/* 		   ); */


$int=prepare_mysql_dates($from,$to,'`Invoice Date`','date start end');


$store_data=array();
$store_data_profit=array();

$sql="select `Store Name`,`Store Key`,`Store Currency Code` from `Store Dimension`";
$result=mysql_query($sql);
$mixed_currencies=false;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

    if ($row['Store Currency Code']!=$corporate_currency)
        $mixed_currencies=true;
    $store_data[$row['Store Key']]=array(
                                       'store'=>sprintf('<a href="report_sales.php?store_key=%d%s">%s</a>',$row['Store Key'],$link,$row['Store Name']),
                                       'currency_code'=>$row['Store Currency Code'],
                                       'net'=>money(0,$row['Store Currency Code']),
                                       'tax'=>money(0,$row['Store Currency Code']),
                                       'eq_tax'=>money(0),
                                       'eq_net'=>money(0),
                                       '_eq_tax'=>0,
                                       '_eq_net'=>0,
                                       'invoices'=>0,
                                       '_invoices'=>0,

                                       'per_invoices'=>'00.0%',
                                       'last_yr_invoices'=>'&#8734;%',
                                       'last_yr_net'=>'&#8734;%'
                                   );

    $store_data_profit[$row['Store Key']]=array(
                                              'store'=>sprintf('<a href="report_sales.php?store_key=%d%s">%s</a>',$row['Store Key'],$link,$row['Store Name']),
                                              'currency_code'=>$row['Store Currency Code'],
                                              'net'=>money(0,$row['Store Currency Code']),
                                              'profit'=>money(0,$row['Store Currency Code']),
                                              'eq_net'=>money(0,$corporate_currency),
                                              'eq_profit'=>money(0,$corporate_currency),
                                              'margin'=>'<b>NA</b>'
                                          );



    $sql=sprintf("select `Category Name`,`Store Name`,`Store Key`,`Store Currency Code`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Profit`) as profit,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) left join `Category Bridge` B  on (`Subject Key`=`Invoice Key` and `Subject`='Invoice') left join `Category Dimension` C  on (B.`Category Key`=C.`Category Key`) where `Invoice Store Key`=%d %s group by B.`Category Key` ",$row['Store Key'],$int[0]);
    $result2=mysql_query($sql);
    if (mysql_num_rows($result2) >1 ) {
        while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
            $store_data[$row['Store Key'].'.'.$row2['Category Name']]=array(
                        'store'=>''
                                ,'substore'=>sprintf("%s",$row2['Category Name'])
                                            ,'invoices'=>number($row2['invoices'])
                                                        ,'_invoices'=>$row2['invoices']
                                                                     ,'net'=>money($row2['net'],$row['Store Currency Code'])
                                                                            ,'tax'=>money($row2['tax'],$row['Store Currency Code'])
                                                                                   ,'eq_net'=>money($row2['eq_net'])
                                                                                             ,'eq_tax'=>money($row2['eq_tax'])
                                                                                                       ,'_eq_net'=>$row2['eq_net']
                                                                                                                  ,'_eq_tax'=>$row2['eq_tax']
                                                                                                                             ,'currency_code'=>$row['Store Currency Code']
                    );

            $store_data_profit[$row['Store Key'].'.'.$row2['Category Name']]=array(
                        'store'=>''
                                ,'substore'=>sprintf("%s",$row2['Category Name'])

                                            ,'net'=>money($row2['net'],$row['Store Currency Code'])
                                                   ,'profit'=>money($row2['profit'],$row['Store Currency Code'])
                                                             ,'margin'=>percentage($row2['profit'],$row2['net'])

                    );



        }

    }


    $last_yr_int=prepare_mysql_dates(date("Y-m-d H:i:s",strtotime($from.' -1 year')),date("Y-m-d H:i:s",strtotime($to.' -1 year')),'`Invoice Date`','date start end');

    $sql=sprintf("select `Category Name`,`Store Name`,`Store Key`,`Store Currency Code`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Profit`) as profit,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) left join `Category Bridge` B  on (`Subject Key`=`Invoice Key` and `Subject`='Invoice') left join `Category Dimension` C  on (B.`Category Key`=C.`Category Key`) where `Invoice Store Key`=%d %s group by B.`Category Key` ",$row['Store Key'],$last_yr_int[0]);
    $result2=mysql_query($sql);
    if (mysql_num_rows($result2) >1 ) {
        while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
            $last_yr_store_data[$row['Store Key'].'.'.$row2['Category Name']]=array(
                        'store'=>''
                                ,'substore'=>sprintf("%s",$row2['Category Name'])
                                            ,'invoices'=>number($row2['invoices'])
                                                        ,'_invoices'=>$row2['invoices']
                                                                     ,'net'=>money($row2['net'],$row['Store Currency Code'])
                                                                            ,'tax'=>money($row2['tax'],$row['Store Currency Code'])
                                                                                   ,'eq_net'=>money($row2['eq_net'])
                                                                                             ,'eq_tax'=>money($row2['eq_tax'])
                                                                                                       ,'_eq_net'=>$row2['eq_net']
                                                                                                                  ,'_eq_tax'=>$row2['eq_tax']
                                                                                                                             ,'currency_code'=>$row['Store Currency Code']
                    );

            $last_yr_store_data_profit[$row['Store Key'].'.'.$row2['Category Name']]=array(
                        'store'=>''
                                ,'substore'=>sprintf("%s",$row2['Category Name'])

                                            ,'net'=>money($row2['net'],$row['Store Currency Code'])
                                                   ,'profit'=>money($row2['profit'],$row['Store Currency Code'])
                                                             ,'margin'=>percentage($row2['profit'],$row2['net'])

                    );



        }

    }





}




$sum_net_eq=0;
$sum_tax_eq=0;
$sum_inv=0;
$sum_profit_eq=0;


$sql="select `Invoice Store Key`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as eq_profit,sum(`Invoice Total Profit`) as profit,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` where true ".$int[0]." group by `Invoice Store Key`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $sum_net_eq+=$row['eq_net'];
    $sum_profit_eq+=$row['eq_profit'];
    $sum_tax_eq+=$row['eq_tax'];
    $sum_inv+=$row['invoices'];
    $store_data[$row['Invoice Store Key']]['class']='geo';
    $store_data[$row['Invoice Store Key']]['invoices']=number($row['invoices']);
    $store_data[$row['Invoice Store Key']]['_invoices']=$row['invoices'];

    $store_data[$row['Invoice Store Key']]['net']=money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $store_data[$row['Invoice Store Key']]['tax']=money($row['tax'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $store_data[$row['Invoice Store Key']]['eq_net']=money($row['eq_net'],$corporate_currency);
    $store_data[$row['Invoice Store Key']]['eq_tax']=money($row['eq_tax'],$corporate_currency);
    $store_data[$row['Invoice Store Key']]['_eq_net']=$row['eq_net'];
    $store_data[$row['Invoice Store Key']]['_eq_tax']=$row['eq_tax'];
    $store_data_profit[$row['Invoice Store Key']]['net']=money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $store_data_profit[$row['Invoice Store Key']]['eq_net']=money($row['eq_net'],$corporate_currency);
    $store_data_profit[$row['Invoice Store Key']]['profit']=money($row['profit'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $store_data_profit[$row['Invoice Store Key']]['eq_profit']=money($row['eq_profit'],$corporate_currency);
    $store_data_profit[$row['Invoice Store Key']]['margin']=percentage($row['profit'],$row['net']);
}


//last year data
$last_yr_sum_net_eq=0;
$last_yr_sum_tax_eq=0;
$last_yr_sum_inv=0;
$last_yr_sum_profit_eq=0;

$last_yr_int=prepare_mysql_dates(date("Y-m-d H:i:s",strtotime($from.' -1 year')),date("Y-m-d H:i:s",strtotime($to.' -1 year')),'`Invoice Date`','date start end');

//print_r($last_yr_int);
$sql="select `Invoice Store Key`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as eq_profit,sum(`Invoice Total Profit`) as profit,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` where true ".$last_yr_int[0]." group by `Invoice Store Key`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $last_yr_sum_net_eq+=$row['eq_net'];
    $last_yr_sum_profit_eq+=$row['eq_profit'];
    $last_yr_sum_tax_eq+=$row['eq_tax'];
    $last_yr_sum_inv+=$row['invoices'];
    $last_yr_store_data[$row['Invoice Store Key']]['class']='geo';
    $last_yr_store_data[$row['Invoice Store Key']]['invoices']=number($row['invoices']);
    $last_yr_store_data[$row['Invoice Store Key']]['_invoices']=$row['invoices'];

    $last_yr_store_data[$row['Invoice Store Key']]['net']=money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $last_yr_store_data[$row['Invoice Store Key']]['tax']=money($row['tax'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $last_yr_store_data[$row['Invoice Store Key']]['eq_net']=money($row['eq_net'],$corporate_currency);
    $last_yr_store_data[$row['Invoice Store Key']]['eq_tax']=money($row['eq_tax'],$corporate_currency);
    $last_yr_store_data[$row['Invoice Store Key']]['_eq_net']=$row['eq_net'];
    $last_yr_store_data[$row['Invoice Store Key']]['_eq_tax']=$row['eq_tax'];
    $last_yr_store_data_profit[$row['Invoice Store Key']]['net']=money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $last_yr_store_data_profit[$row['Invoice Store Key']]['eq_net']=money($row['eq_net'],$corporate_currency);
    $last_yr_store_data_profit[$row['Invoice Store Key']]['profit']=money($row['profit'],$store_data[$row['Invoice Store Key']]['currency_code']);
    $last_yr_store_data_profit[$row['Invoice Store Key']]['eq_profit']=money($row['eq_profit'],$corporate_currency);
    $last_yr_store_data_profit[$row['Invoice Store Key']]['margin']=percentage($row['profit'],$row['net']);
}
//print_r($last_yr_store_data);

$part_of_interval_in_the_future=false;
if (strtotime($to)>strtotime('now')) {
    $part_of_interval_in_the_future=true;

    $last_yr_interval_in_future_sum_net_eq=0;
    $last_yr_interval_in_future_sum_tax_eq=0;
    $last_yr_interval_in_future_sum_inv=0;
    $last_yr_interval_in_future_sum_profit_eq=0;

    $last_yr_interval_in_future_int=prepare_mysql_dates(date("Y-m-d H:i:s",strtotime($from.' -1 year')),date("Y-m-d H:i:s",strtotime('now -1 year')),'`Invoice Date`','date start end');

//print_r($last_yr_interval_in_future_int);
    $sql="select `Invoice Store Key`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as eq_profit,sum(`Invoice Total Profit`) as profit,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` where true ".$last_yr_interval_in_future_int[0]." group by `Invoice Store Key`";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $last_yr_interval_in_future_sum_net_eq+=$row['eq_net'];
        $last_yr_interval_in_future_sum_profit_eq+=$row['eq_profit'];
        $last_yr_interval_in_future_sum_tax_eq+=$row['eq_tax'];
        $last_yr_interval_in_future_sum_inv+=$row['invoices'];
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['class']='geo';
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['invoices']=number($row['invoices']);
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['_invoices']=$row['invoices'];

        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['net']=money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']);
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['tax']=money($row['tax'],$store_data[$row['Invoice Store Key']]['currency_code']);
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['eq_net']=money($row['eq_net'],$corporate_currency);
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['eq_tax']=money($row['eq_tax'],$corporate_currency);
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['_eq_net']=$row['eq_net'];
        $last_yr_interval_in_future_store_data[$row['Invoice Store Key']]['_eq_tax']=$row['eq_tax'];
        $last_yr_interval_in_future_store_data_profit[$row['Invoice Store Key']]['net']=money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']);
        $last_yr_interval_in_future_store_data_profit[$row['Invoice Store Key']]['eq_net']=money($row['eq_net'],$corporate_currency);
        $last_yr_interval_in_future_store_data_profit[$row['Invoice Store Key']]['profit']=money($row['profit'],$store_data[$row['Invoice Store Key']]['currency_code']);
        $last_yr_interval_in_future_store_data_profit[$row['Invoice Store Key']]['eq_profit']=money($row['eq_profit'],$corporate_currency);
        $last_yr_interval_in_future_store_data_profit[$row['Invoice Store Key']]['margin']=percentage($row['profit'],$row['net']);
    }



}




//print_r($last_yr_store_data);
//print_r($store_data);
//print_r($last_yr_store_data);
foreach($store_data as $key=>$val) {
    $store_data[$key]['class']='geo';

    $store_data[$key]['per_invoices']=percentage($val['_invoices'],$sum_inv);

    if ($part_of_interval_in_the_future) {
        if (isset($last_yr_interval_in_future_store_data[$key]['invoices']))
            $store_data[$key]['last_yr_invoices']=delta($store_data[$key]['_invoices'],$last_yr_interval_in_future_store_data[$key]['_invoices']);
        if (isset($last_yr_interval_in_future_store_data[$key]['_eq_net']))
            $store_data[$key]['last_yr_net']=delta($store_data[$key]['_eq_net'],$last_yr_interval_in_future_store_data[$key]['_eq_net']);

    } else {
        if (isset($last_yr_store_data[$key]['_invoices'])) {
            $store_data[$key]['last_yr_invoices']=delta($store_data[$key]['_invoices'],$last_yr_store_data[$key]['_invoices']);

        }
        if (isset($last_yr_store_data[$key]['_eq_net']))
            $store_data[$key]['last_yr_net']=delta($store_data[$key]['_eq_net'],$last_yr_store_data[$key]['_eq_net']);

    }




    if ($val['store']!='') {
        if ($val['currency_code']!=$corporate_currency)
            $store_data[$key]['per_eq_net']='<span class="mix_currency">'.percentage($val['_eq_net'],$sum_net_eq,2).'</span>';
        else
            $store_data[$key]['per_eq_net']=percentage($val['_eq_net'],$sum_net_eq,2);
    } else {


        if ($val['currency_code']!=$corporate_currency)
            $store_data[$key]['per_eq_net']='<span class="mix_currency">'.percentage($val['_eq_net'],$sum_net_eq,2).'</span>';
        else
            $store_data[$key]['per_eq_net']=percentage($val['_eq_net'],$sum_net_eq,2);

    }


}
$last_year_invoices=delta($sum_inv,$last_yr_sum_inv);
$last_year_net=delta($sum_net_eq,$last_yr_sum_net_eq);

if ($mixed_currencies) {



    $store_data[]=array(
                      'class'=>'total',
                      'store'=>_('Total'),
                      'invoices'=>number($sum_inv),
                      'last_yr_invoices'=>$last_year_invoices,
                      'last_yr_net'=>$last_year_net,
                      'net'=>'',
                      'tax'=>'<span class="mix_currency">'.money($sum_tax_eq).'</span>',
                      'eq_net'=>'<span class="mix_currency">'.money($sum_net_eq).'</span>',
                      'eq_tax'=>'<span class="mix_currency">'.money($sum_tax_eq).'</span>',
                  );
    $store_data_profit[]=array(
                             'class'=>'total',
                             'store'=>_('Total'),
                             'invoices'=>number($sum_inv),
                             'net'=>'<b><span class="mix_currency">'.money($sum_net_eq).'</span></b>',
                             'tax'=>'<span class="mix_currency">'.money($sum_tax_eq).'</span>',
                             'eq_net'=>'<span >'.money($sum_net_eq).'</span>',
                             'eq_tax'=>'<span >'.money($sum_tax_eq).'</span>'
                         );


} else {
    $store_data[]=array(
                      'class'=>'total',
                      'store'=>_('Total'),
                      'invoices'=>number($sum_inv),
                      'last_yr_invoices'=>$last_year_invoices,
                      'last_yr_net'=>$last_year_net,
                      'net'=>money($sum_net_eq),
                      'tax'=>money($sum_tax_eq),
                      'eq_net'=>money($sum_net_eq),
                      'eq_tax'=>money($sum_tax_eq)
                  );
    $store_data_profit[]=array(
                             'class'=>'total',
                             'store'=>_('Total'),
                             'net'=>'<b><span class="mix_currency">'.money($sum_net_eq).'</span></b>',
                             'profit'=>'<span class="mix_currency">'.money($sum_profit_eq).'</span>',
                             'eq_profit'=>'<span ><b>'.money($sum_profit_eq).'</b></span>',
                             'eq_net'=>'<span ><b>'.money($sum_net_eq).'</b></span>'

                         );
}








$smarty->assign('mixed_currencies',$mixed_currencies);



//print_r($store_data);
$smarty->assign('store_data',$store_data);
$smarty->assign('store_data_profit',$store_data_profit);

$plot_tipo=$_SESSION['state']['report_sales']['plot'];
$smarty->assign('plot_tipo',$plot_tipo);


$day_interval=get_time_interval(strtotime($from),(strtotime($to)))+1;
$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('currency',$myconf['currency_symbol']);

 $smarty->assign('quick_period',$quick_period);
$smarty->display('report_sales_main.tpl');



?>

