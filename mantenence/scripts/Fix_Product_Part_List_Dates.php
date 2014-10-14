<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}
require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql=sprintf("select * from `Part Dimension`  ");
$res=mysql_query($sql);



while ($row=mysql_fetch_array($res)) {
    $sku=$row['Part SKU'];
    $ids=get_part_all_ids($sku);

    $part_from=$row['Part Valid From'];
    $part_to=$row['Part Valid To'];
    // print "$ids\n";
    foreach(preg_split('/\,/',$ids) as $pid) {

        $product=new Product('pid',$pid);

        $product_from=$product->data['Product Valid From'];
        $product_to=$product->data['Product Valid To'];
        $store_key=$product->data['Product Store Key'];

        $from=$part_from;

        if ($row['Part Status']=='In Use') {
            $to='';
        } else {
            $to=$part_to;
            if (strtotime($to)<strtotime($product_to))
                $to=$product_to;
        }

        if ($store_key!=1) {
            $from=$product_from;

        } else {

            $from=$part_from;
            if (strtotime($from)>strtotime($product_from))
                $from=$product_from;

        }


        // print "diff $sku ".$row['Part Status']." $pid ($from  $to)\n";



        $sql=sprintf("select  PPD.`Product Part Key` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU`=%d  and PPD.`Product ID`=%d ",$sku,$pid);
        $res2=mysql_query($sql);

        if ($row2=mysql_fetch_array($res2)) {

            $status='No';
            if ($to=='')
                $status='Yes';

            $sql=sprintf("update `Product Part Dimension` set `Product Part Valid From`=%s , `Product Part Valid To`=%s ,`Product Part Most Recent`=%s where `Product Part Key`=%d"
                         ,prepare_mysql($from)
                         ,prepare_mysql($to)

                         ,prepare_mysql($status)
                         ,$row2['Product Part Key']
                        );

            if (!mysql_query($sql))
                print "$sql\n";

        }

    }





}




function merge_product($product1,$product2) {
    if ($p1->data['Product Units Per Case']==$p2->data['Product Units Per Case']) {
        exit("same units\n");
    }
    if ($p1->data['Product Units Per Case']>$p2->data['Product Units Per Case']) {
        $product_parts_to_keep=$p2;
        $product_parts_to_delete=$p1;
    } else {
        $product_parts_to_keep=$p1;
        $product_parts_to_delete=$p2;
    }

}
function get_product_skus($product_code,$date=false) {

    if (!$date)
        $date=date('Y-m-d H:i:s');

    $sql=sprintf("select GROUP_CONCAT(`Product ID`) as ids from `Product Dimension` where `Product Code`=%s "
                 ,prepare_mysql($product_code)
                );
    $res=mysql_query($sql);
    $ids='';
    if ($row=mysql_fetch_array($res)) {
        $ids= $row['ids'];
    }

    $sql=sprintf("select  GROUP_CONCAT(distinct `Part SKU`) skus from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where PPL.`Product ID` in ($ids) and `Product Part Valid From`<%s    "
                 ,prepare_mysql($date)
                 ,prepare_mysql($date)
                 ,prepare_mysql($date)
                );
    print "$sql\n";
    $res=mysql_query($sql);
    $skus='';
    if ($row=mysql_fetch_array($res)) {
        $skus= $row['skus'];
    }

    return $skus;



}

function get_part_all_ids($sku) {


    $sql=sprintf("select  GROUP_CONCAT(distinct  PPL.`Product ID`) ids from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=$sku   "
                );

    $res=mysql_query($sql);
    $skus='';
    if ($row=mysql_fetch_array($res)) {
        $skus= $row['ids'];
    }

    return $skus;




}



function get_product_all_skus($product_code) {


    $sql=sprintf("select GROUP_CONCAT(distinct `Product ID`) as ids from `Product Dimension` where `Product Code`=%s "
                 ,prepare_mysql($product_code)
                );
    $res=mysql_query($sql);
    $ids='';
    if ($row=mysql_fetch_array($res)) {
        $ids= $row['ids'];
    }

    $sql="select  GROUP_CONCAT(distinct `Part SKU`) skus from `Product Part List` where `Product ID` in ($ids)  ";
    $res=mysql_query($sql);
    $skus='';
    if ($row=mysql_fetch_array($res)) {
        $skus= $row['skus'];
    }

    return $skus;

}


function get_units_from_sku($sku,$date) {

    if (!$date)
        $date=date('Y-m-d H:i:s');

    $sql=sprintf("select  GROUP_CONCAT(distinct PPL.`Product ID`) ids from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU` in ($sku) and  `Product Part Valid From`<%s   "
                 ,prepare_mysql($date)
                 ,prepare_mysql($date)
                 ,prepare_mysql($date)
                );

    $res=mysql_query($sql);
    $ids='';
    if ($row=mysql_fetch_array($res)) {
        $ids= $row['ids'];
    }
    $unit=false;
    $unit_old=false;
    foreach( preg_split('/\,/',$ids) as $pid) {
        $product=new Product('pid',$pid);


        $unit=$product->data['Product Units Per Case'];
        if (!$unit_old)
            $unit_old=$unit;

        if ($unit_old!=$unit) {
            print "error units discrepance\n";
        }


    }

    return $unit;

}


?>