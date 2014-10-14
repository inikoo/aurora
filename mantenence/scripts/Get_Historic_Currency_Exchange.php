<?php

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.CurrencyExchange.php');

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}
require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');
$software='Get_Products.php';
$version='V 1.0';
$Data_Audit_ETL_Software="$software $version";


$days=30;

$currencies=array();
$where='';
$where="where  `Country Currency Code` in ('GBP','EUR','PLN','USD','ZAR','AUD','CAD','CHF','JPY','CNY','IDR','INR') ";

$sql=sprintf("select `Country Currency Code` from kbase.`Country Dimension` $where   group by `Country Currency Code`");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
    if ($row['Country Currency Code']!='')
        $currencies[]=$row['Country Currency Code'];
}

foreach($currencies as $cur1) {
    foreach($currencies as $cur2) {
        if ($cur1!=$cur2) {
            print "Getting $cur1 $cur2\r";
            $random=md5(mt_rand());
            $tmp_file="currency_$random.txt";
            exec("echo '' > $tmp_file");
            exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." $cur1$cur2=X > $tmp_file");
            
	    print "./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." $cur1$cur2=X > $tmp_file\n";
	    read_currency_data($tmp_file);



	    read_currency_inv_data($tmp_file,$cur2.$cur1);
            unset($tmp_file);

            $random=md5(mt_rand());
            $tmp_file="currency_$random.txt";
            exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." $cur2$cur1=X > $tmp_file");
         
	    print("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." $cur2$cur1=X > $tmp_file\n");
         
	    read_currency_data($tmp_file);


	    read_currency_inv_data($tmp_file,$cur1.$cur2);

            unset($tmp_file);

        }

    }
}


function read_currency_data($tmp_file) {

    $row = 1;
    $handle = fopen($tmp_file, "r");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        
        if(count($data)!=3)
            continue;
        $num = count($data);
        $pair=preg_replace('/=X/','',$data[0]);
        $date=date("Y-m-d",strtotime($data[1]));
        $exchange=$data[2];
        if ($exchange>0) {
            $sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f)  ON DUPLICATE KEY UPDATE `Exchange`=%f  ",
                         prepare_mysql($date) ,prepare_mysql($pair),$exchange,$exchange);
             print "$sql\n";
            mysql_query($sql);

	    


        }
    }
    fclose($handle);

}



function read_currency_inv_data($tmp_file,$pair) {

    $row = 1;
    $handle = fopen($tmp_file, "r");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        
        if(count($data)!=3)
            continue;
        $num = count($data);
        
        $date=date("Y-m-d",strtotime($data[1]));
        $exchange=$data[2];
        if ($exchange>0) {
            $sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f) ",
                         prepare_mysql($date) ,prepare_mysql($pair),1/$exchange);
             print "$sql\n";
            mysql_query($sql);

	    


        }
    }
    fclose($handle);

}


?>