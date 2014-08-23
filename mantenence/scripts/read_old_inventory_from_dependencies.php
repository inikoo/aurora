<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.User.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.PartLocation.php');
date_default_timezone_set('UTC');

include_once('../../class.InventoryAudit.php');
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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$sql=sprintf("select  (select units from aw_old.product p where p.id=bulk_id) as r1_units,(select code from aw_old.product p where p.id=bulk_id) as r1 ,(select units from aw_old.product p where p.id=product_id) as r2_units ,(select code from aw_old.product p where p.id=product_id) as r2,(select count(*) from aw_old.product_relations as prtmp where prtmp.product_id=aw_old.product_relations.product_id) as multiplicity   from aw_old.product_relations ");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

    if ($row['r1']=='' or $row['r2']=='')
        continue;

    if ($row['multiplicity']==1) {

        $product_parts_to_keep=$row['r1'];
        $product_parts_to_delete=$row['r2'];
        $factor=$row['r2_units']/$row['r1_units'];

        if ($row['r1_units']>$row['r2_units']) {
            $product_parts_to_keep=$row['r2'];
            $product_parts_to_delete=$row['r1'];
            $factor=$row['r1_units']/$row['r2_units'];
        }

        //get_product_skus($row['r1']);
        print "$product_parts_to_delete transfer parts to $product_parts_to_keep \n";



        insert_other_inventary($product_parts_to_delete,$product_parts_to_keep,$factor);





        $to_delete_skus=get_product_all_skus($product_parts_to_delete);

       
        $sql=sprintf("select * from `Inventory Transaction Fact` where `Part SKU` in ($to_delete_skus)  ");
        $res2=mysql_query($sql);
        while ($row2=mysql_fetch_array($res2)) {
            $to_keep_skus=get_product_skus($product_parts_to_keep,$row2['Date']);
            if ($to_keep_skus=='') {
                exit("no parent!!!!  $product_parts_to_keep ".$row2['Date']."  \n");
            }

            if (count(preg_split('/\,/',$to_keep_skus))>1) {
                exit("do not know where to choose  $to_keep_skus  \n");
            } else {
                $base_sku=$to_keep_skus;

            }
            $units_to_keep=get_units_from_sku($base_sku,$row2['Date']);
            $units_to_delete=get_units_from_sku($row2['Part SKU'],$row2['Date']);
            $actual_factor=$units_to_delete/$units_to_keep;

            if ($actual_factor!=$factor) {
                print("factors discrepancies  $actual_factor -> $factor \n ");
            }else{
            //print "Date ".$row2['Date']." QTY ".$row2['Inventory Transaction Quantity']." Cost ".$row2['Inventory Transaction Amount']." Rep:$to_keep_skus F:  $factor, U:($units_to_delete;$units_to_keep ".($units_to_delete/$units_to_keep).")  \n";
            }
       }

    } else {
       // print "Multiplicity:".$row['multiplicity']."\n";
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

    $sql=sprintf("select  GROUP_CONCAT(distinct `Part SKU`) skus from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Product ID` in ($ids)  and  ( (  `Product Part Valid From`<=%s  and  `Product Part Valid To`>=%s and PPD.`Product Part Most Recent`='No')or (`Product Part Valid From`<=%s and  PPD.`Product Part Most Recent`='Yes') )  "
                 ,prepare_mysql($date)
                 ,prepare_mysql($date)
                 ,prepare_mysql($date)
                );
    // print "$sql\n";
    $res=mysql_query($sql);
    $skus='';
    if ($row=mysql_fetch_array($res)) {
        $skus= $row['skus'];
    }

    return $skus;



}


function get_product_skus_first($product_code) {



    $sql=sprintf("select GROUP_CONCAT(`Product ID`) as ids from `Product Dimension` where `Product Code`=%s "
                 ,prepare_mysql($product_code)
                );
    $res=mysql_query($sql);
    $ids='';
    if ($row=mysql_fetch_array($res)) {
        $ids= $row['ids'];
    }

    $sql=sprintf("select  `Part SKU` skus from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Product ID` in ($ids)  order by `Product Part Valid From` limit 1  "

                );

    $res=mysql_query($sql);
    $skus='';
    if ($row=mysql_fetch_array($res)) {
        $skus= $row['skus'];
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

    $sql="select  GROUP_CONCAT(distinct `Part SKU`) skus from `Product Part Dimension` PPD left join `Product Part List` PPL on (PPL.`Product Part Key`=PPD.`Product Part Key`)where `Product ID` in ($ids)  ";

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

    $sql=sprintf("select  GROUP_CONCAT(distinct `Product ID`) ids from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU` in ($sku)  and  ( (  `Product Part Valid From`<%s  and  `Product Part Valid To`>%s )or (`Product Part Valid From`<%s and `Product Part Valid To` IS NULL  ) )  "
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
            print "error units discrepance    $unit_old -> $unit  \n";
        }


    }

if(!$unit){
$sql=sprintf("select  GROUP_CONCAT(distinct `Product ID`) ids from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU` in ($sku)    "
            
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
            print "error units discrepance    $unit_old -> $unit  \n";
        }


    }

}


    return $unit;

}



function insert_other_inventary($code_give,$code_receive,$factor) {

    $part_to_update=array();

    $sql="select (select handle from aw_old.liveuser_users where authuserid=aw_old.in_out.author) as user, code,product_id,aw_old.in_out.date,aw_old.in_out.tipo,aw_old.in_out.quantity ,aw_old.in_out.notes from aw_old.in_out left join aw_old.product on (product.id=product_id) where  in_out.date!='0000-00-00 00:00:00' and product.code='$code_give' and (aw_old.in_out.tipo=2 or aw_old.in_out.tipo=1  or aw_old.in_out.tipo=3)  order by date ";
print "$sql\n";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


        //print $row['user']."\n";
        $user=new User('handle',$row['user'],'Staff');
        $user_key=$user->id;


        $date=$row['date'];
        $code=$row['code'];
        //   print $sql;

        $tipo=$row['tipo'];
        $qty=$row['quantity'];
        $notes=$row['notes'];





        $part_sku=get_product_skus($code_receive,$date);

        if (count(preg_split('/\,/',$part_sku))>1) {
            exit("More more more\n");
        }

        if ($part_sku=='') {

            $part_sku=get_product_skus_first($code_receive);


        }


        $part=new Part($part_sku);
        $part_to_update[$part_sku]=1;
        $cost_per_part=$part->get_unit_cost($date);
        $parts_per_product=$factor;

        if ($tipo==2) {
            $data_inventory_audit=array(
                                      'Inventory Audit Date'=>$date
                                                             ,'Inventory Audit Part SKU'=>$part_sku
                                                                                         ,'Inventory Audit Location Key'=>1
                                                                                                                         ,'Inventory Audit Note'=>$notes
                                                                                                                                                 ,'Inventory Audit User Key'=>$user_key
                                                                                                                                                                             ,'Inventory Audit Quantity'=>$qty*$parts_per_product
                                  );
            // print_r($data_inventory_audit);
            $audit=new InventoryAudit('find',$data_inventory_audit,'create');

        }
        elseif($tipo==1) {


            $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'In',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
            print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql can into insert Inventory Transaction Fact ");


        }
        elseif($tipo==3) {


            $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'Lost',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
           print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql can into insert Inventory Transaction Fact ");


        }

    }
    print "*********\n";
    print_r($part_to_update);

    foreach($part_to_update as $sku=>$val) {
        $part=new Part($sku);
        
        $part->wrap_transactions();
    }



}


?>