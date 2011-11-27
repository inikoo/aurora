<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Page.php');
include_once('../../class.Store.php');
include_once('../../class.CompanyArea.php');
include_once('../../class.CompanyDepartment.php');
include_once('../../class.CompanyPosition.php');
include_once('../../class.TaxCategory.php');
include_once('../../class.Charge.php');
include_once('../../class.Staff.php');
include_once('../../class.Deal.php');
include_once('../../class.Shipping.php');

error_reporting(E_ALL);

date_default_timezone_set('UTC');


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

global $myconf;



$office_area_key=1;
  $department_key=1;
$staff=array(
	    


	     'CUS'=>array(
			  array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$department_key,'Staff Name'=>'Callum Wise','Staff Alias'=>'callum','Staff Currently Working'=>'Yes')
			  
			  
			  
			  )


	     );


foreach($staff as $position_codes=>$staff_data) {
  foreach(preg_split('/,/',$position_codes) as $key =>$position_code ){

    $position=new CompanyPosition('code',$position_code);
    if(!$position->id){
      print "$position_code\n";
      //print_r($position);
    exit;
    }
    foreach($staff_data as $data) {
      $staff=$position->add_staff($data);
      if($data['Staff Currently Working']=='Yes' and $data['Create User']){
        $user=$staff->create_user();
      }
    }
  }

}




?>