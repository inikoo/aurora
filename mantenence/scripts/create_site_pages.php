<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Site.php');
include_once('../../class.Image.php');

include_once('../../class.Page.php');
include_once('../../class.Store.php');
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant2';
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


$sql=sprintf("select * from `Site Dimension`  where `Site Key`=1");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
$site=new Site($row['Site Key']);


$page_data=array(
 'Page Store Section'=>'Client Section',
 'Page Store Title'=>_('Profile'),
 'Page Short Title'=>_('Profile'),
  'Page Code'=>'profile',
  'Page URL'=>'profile.php',
  'Page Store Content Display Type'=>'Template',
  'Page Store Content Template Filename'=>'profile',
  'Number See Also Links'=>0,

 );
$site->add_store_page($page_data);

//'Front Page Store','Search','Product Description','Information','Category Catalogue','Family Catalogue','Department Catalogue','Unknown','Store Catalogue','Registration','Client Section','Check Out'

 $page_data=array(
 'Page Store Section'=>'Registration',
 'Page Store Title'=>_('Registration'),
 'Page Short Title'=>_('Registration'),
  'Page Code'=>'registration',
  'Page URL'=>'registration.php',
  'Page Store Content Display Type'=>'Template',
  'Page Store Content Template Filename'=>'registration',
    'Number See Also Links'=>0
 );
$site->add_store_page($page_data);

exit;
 $page_data=array(
 'Page Store Section'=>'Registration',
 'Page Store Title'=>_('Log in'),
 'Page Short Title'=>_('Log in'),
  'Page Code'=>'login',
  'Page URL'=>'login.php',
  'Page Store Content Display Type'=>'Template',
  'Page Store Content Template Filename'=>'login',
    'Number See Also Links'=>0

 );
$site->add_store_page($page_data);


}


?>