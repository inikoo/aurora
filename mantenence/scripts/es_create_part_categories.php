<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 Inikoo
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Category.php');
include_once('../../class.Node.php');

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

global $myconf;

$sql=sprintf("delete from `Category Dimension` where `Category Subject`='Part';");
mysql_query($sql);

$sql=sprintf("delete from `Category Bridge` where `Subject`='Part';");
mysql_query($sql);


$data=array('Category Warehouse Key'=>1,'Category Code'=>'Temas','Category Subject'=>'Part');
$main_cat1=new Category('find create',$data);

$data=array('Category Warehouse Key'=>1,'Category Code'=>'Joyeria','Category Subject'=>'Part', 'Category Parent Key'=>$main_cat1->id);
$main_cat=new Category('find create',$data);

$sql=sprintf("select * from `Part Dimension`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $part=new Part($row['Part SKU']);
//$part->update_used_in();
    $used_in=preg_split('/\s+/',$part->data['Part Currently Used In']);
    foreach($used_in as $code) {
        if (preg_match('/^(clm|amer|bsm|bsh|phf|pfm)\-/i',$code,$match)) {
           
             
              
                
                $_data=array(
                           'category_key'=>$main_cat->id,
                           'parent_category_key'=>$main_cat1->id,
                           'subject'=>'Part',
                           'subject_key'=>$part->sku,
                       );

                associate_subject_to_category($_data);

           

        }
    }

}

$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Part' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$category=new Category($row['Category Key']);
	$category->update_up_today();
	$category->update_last_period();
	$category->update_last_interval();
	print "Category ".$category->id."\t\t\n";
}

function associate_subject_to_category($data) {


    $found=false;
    $sql=sprintf("select count(*) as num from `Category Bridge`  where `Category Key`=%d and `Subject`=%s and `Subject Key`=%d",
                 $data['category_key'],
                 prepare_mysql($data['subject']),
                 $data['subject_key']
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        if ($row['num']>0)
            $found=true;

    }


    if ($found) {
      
    }

    $old_category_key=0;
    $sql=sprintf("select C.`Category Key` from `Category Bridge` as CB left join `Category Dimension` C on (C.`Category Key`=CB.`Category Key`)  where `Category Parent Key`=%d and `Subject`=%s and `Subject Key`=%d",
                 $data['parent_category_key'],
                 prepare_mysql($data['subject']),
                 $data['subject_key']
                );
    $result=mysql_query($sql);
    if ($row=mysql_fetch_assoc($result)) {
        $old_category_key=$row['Category Key'];

    }


    $sql=sprintf("delete CB.* from `Category Bridge` as CB left join `Category Dimension` C on (C.`Category Key`=CB.`Category Key`)  where `Category Parent Key`=%d and `Subject`=%s and `Subject Key`=%d",
                 $data['parent_category_key'],
                 prepare_mysql($data['subject']),
                 $data['subject_key']
                );
    mysql_query($sql);

    $old_category=new Category($old_category_key);
    if ($old_category->id)
        $old_category->update_number_of_subjects();
    $old_category->update_subjects_data();

    $sql=sprintf("insert into `Category Bridge` values (%d,%s,%d,NULL)",
                 $data['category_key'],
                 prepare_mysql($data['subject']),
                 $data['subject_key']
                );
    mysql_query($sql);
	//print "$sql\n";
    if (mysql_affected_rows()>0) {



        $category=new Category($data['category_key']);
        $category->update_number_of_subjects();
        //$category->update_subjects_data();





    
    } 

}


?>