<?php
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Deal.php';
include_once '../../class.Charge.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Warehouse.php';
include_once '../../class.Node.php';
include_once '../../class.Shipping.php';
include_once '../../class.SupplierProduct.php';
include_once 'local_map.php';

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
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
$codigos=array();


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$fh = fopen('/tmp/zeleris.txt', 'w');
$codigo_remitente='38406';
$nombre_remitente='Costa Import SL (AW-Regalos)';
mb_internal_encoding("utf-8");
$sql=sprintf('select `Customer Key` from `Customer Dimension`');
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
	$customer=new Customer($row['Customer Key']);
	
	$address=new Address($customer->data['Customer Main Delivery Address Key']);
	//print_r($address->display('lines'));
	
	//$address_lines=_trim(preg_replace('/\s*\r\n?\s*|\s*\n\s*/',$address->display('lines'),','));
	//if($address_lines==',')$address_lines='ND';
	$address_lines=$address->display('lines');
	fwrite($fh, mb_str_pad($codigo_remitente, 9));
	fwrite($fh, mb_str_pad($nombre_remitente, 40));
	fwrite($fh, mb_str_pad($customer->id, 20));
	fwrite($fh, mb_str_pad(substr($customer->data['Customer Name'],0,40), 40));
	fwrite($fh, mb_str_pad(substr($address_lines,0,80), 80));
	fwrite($fh, mb_str_pad(substr($customer->data['Customer Main Delivery Address Town'],0,40), 40));
	fwrite($fh, mb_str_pad(substr($customer->data['Customer Main Delivery Address Postal Code'],0,12), 12));
	fwrite($fh, mb_str_pad(substr($customer->data['Customer Main Delivery Address Country 2 Alpha Code'],0,2), 3));

	fwrite($fh, mb_str_pad(substr($customer->data['Customer Main Plain Telephone'],0,15), 15));
	fwrite($fh, mb_str_pad(substr($customer->data['Customer Main Plain Mobile'],0,15), 15));
	fwrite($fh, mb_str_pad(substr($customer->data['Customer Main Contact Name'],0,40), 40));

    fwrite($fh, "\n");
}




function mb_str_pad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT) {
    $str_len = mb_strlen($str);
    $pad_str_len = mb_strlen($pad_str);
    if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
        $str_len = 1; // @debug
    }
    if (!$pad_len || !$pad_str_len || $pad_len <= $str_len) {
        return $str;
    }

    $result = null;
    if ($dir == STR_PAD_BOTH) {
        $length = ($pad_len - $str_len) / 2;
        $repeat = ceil($length / $pad_str_len);
        $result = mb_substr(str_repeat($pad_str, $repeat), 0, floor($length))
                . $str
                . mb_substr(str_repeat($pad_str, $repeat), 0, ceil($length));
    } else {
        $repeat = ceil($str_len - $pad_str_len + $pad_len);
        if ($dir == STR_PAD_RIGHT) {
            $result = $str . str_repeat($pad_str, $repeat);
            $result = mb_substr($result, 0, $pad_len);
        } else if ($dir == STR_PAD_LEFT) {
            $result = str_repeat($pad_str, $repeat);
            $result = mb_substr($result, 0, 
                        $pad_len - (($str_len - $pad_str_len) + $pad_str_len))
                    . $str;
        }
    }

    return $result;
}
?>
