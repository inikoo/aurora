<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
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

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

global $myconf;


$sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $data=array('Category Code'=>'Type of Business','Category Subject'=>'Customer','Category Store Key'=>$row['Store Key']);
    $cat_type_business[$row['Store Key']]=new Category('find create',$data);
    $data=array('Category Code'=>'Referrer','Category Subject'=>'Customer','Category Store Key'=>$row['Store Key']);
    $cat_referrer[$row['Store Key']]=new Category('find create',$data);

    if ($row['Store Code']=='UK') {
        $valid_sub_cats_referrals[$row['Store Key']]=array(
        'Craft Focus Magazine',
'Craft Focus Magazine',
'Gift Focus Magazine',
'Gifts Today',
'Giftware Review',
'Heritage Shop Catalogue',
'Google',
'Yahoo',
'Bing',
'Market Times',
'The Trader Magazine',
'MTN Market Trade News',
'Progressive Gifts',
'The Trader Website',
'Other',
'Facebook',
'Twitter','StartUpPlus','Referral','Google','Ancient Wisdom','Other','Bing','Craft Focus Magazine','Facebook','Garden Shop Catalogue','Gift Focus Magazine','Gifts Today','Giftware Index','Giftware Review','Heritage Shop Catalogue','Market Times','MTN','Progressive Gifts','The Trader Magazine','The Trader Website','The Wholesaler Website','Twitter','Yahoo'

        );
        foreach($valid_sub_cats_referrals[$row['Store Key']] as $valid_sub_cats_referral) {
            $data=array('Category Code'=>$valid_sub_cats_referral,'Category Subject'=>'Customer','Category Parent Key'=>$cat_referrer[$row['Store Key']]->id,'Category Store Key'=>$row['Store Key']);
            $subcat_type_referrer=new Category('find create',$data);
        }
    } else {
        $valid_sub_cats_referrals[$row['Store Key']]=array('Referral','Google','Bing','Yahoo','Other');
        foreach($valid_sub_cats_referrals[$row['Store Key']] as $valid_sub_cats_referral) {
            $data=array('Category Code'=>$valid_sub_cats_referral,'Category Subject'=>'Customer','Category Parent Key'=>$cat_referrer[$row['Store Key']]->id,'Category Store Key'=>$row['Store Key']);
            $subcat_type_referrer=new Category('find create',$data);
        }

    }

    $valid_sub_cats_type_bussiness[$row['Store Key']]=array(
        'Craft Fairs',
'Department Store',
'Ebay Seller',
'Florists',
'Garden Centre',
'Gift Shop',
'Internet Shop',
'Market Trader',
'Party Planner',
'Tourist Attraction',
'Wedding Planner',
'Wholesaler',
'Other','Gift Shop','Internet Shop','Market Trader','Party Planner','Craft Fairs','Tourist Attraction','Wedding Planner','Wholesaler','Department Store','Florist','Ebay Seller','Garden Centre','NPO','Hospitality Industry','Therapist','Event','Other'

    );
    foreach($valid_sub_cats_type_bussiness[$row['Store Key']] as $valid_sub_cats_type_business) {
        $data=array('Category Code'=>$valid_sub_cats_type_business,'Category Subject'=>'Customer','Category Parent Key'=>$cat_type_business[$row['Store Key']]->id,'Category Store Key'=>$row['Store Key']);
        $subcat_type_type_business=new Category('find create',$data);
    }


}

     


?>