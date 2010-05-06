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

error_reporting(E_ALL);

date_default_timezone_set('Europe/London');


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


$data=array(
          'editor'=>array('Date'=>'2003-08-28 09:00:00')
                   ,'Company Name'=>'Ancient Wisdom'
                                   ,'Company Fiscal Name'=>'Ancient Wisdom Marketing Ltd'
                                                          ,'Company Tax Number'=>'764298589'
                                                                                ,'Company Registration Number'=>'4108870'

      );


$company=new Company('find create auto',$data);
$sql=sprintf("delate from  `Corporation Dimension` " );
mysql_query($sql);
$sql=sprintf("insert into `Corporation Dimension` values (%s,%d,'GBP') ",$company->data['Company Name'],$company->id );
mysql_query($sql);



$areas=array(
           array(
               'Company Key'=>$company->id,
               'Company Area Code'=>'OFC',
               'Company Area Name'=>'Office',
               'Company Area Description'=>'House of the administrative and creative Departments',
           )
           ,array(
               'Company Key'=>$company->id,
               'Company Area Code'=>'WAH',
               'Company Area Name'=>'Warehouse',
               'Company Area Description'=>'House of Picking,Packing and Stock Departments',

           )
           ,array(
               'Company Key'=>$company->id,
               'Company Area Code'=>'PRD',
               'Company Area Name'=>'Production',
               'Company Area Description'=>'House of the Manufacture Departments',

           )
       );

foreach($areas as $areas_data) {
    $area=new CompanyArea('find',$areas_data,'create');
}



$departments=array(
                 'OFC'=>array(
                           array(
                               'Company Department Code'=>'CUS.UK',
                               'Company Department Name'=>'Customer Services UK',
                               'Company Department Description'=>'Customer Services')
                           ,array(
                               'Company Department Code'=>'CUS.DE',
                               'Company Department Name'=>'Customer Services Germany',
                               'Company Department Description'=>'Customer Services')
                           ,array(
                               'Company Department Code'=>'CUS.FR',
                               'Company Department Name'=>'Customer Services France',
                               'Company Department Description'=>'Customer Services')
                           ,array(
                               'Company Department Code'=>'CUS.PL',
                               'Company Department Name'=>'Customer Services Poland',
                               'Company Department Description'=>'Customer Services')
                           ,array(
                               'Company Department Code'=>'MRK',
                               'Company Department Name'=>'Marketing',
                               'Company Department Description'=>'Marketing Department')
                           ,array(
                               'Company Department Code'=>'ACC',
                               'Company Department Name'=>'Accounting',
                               'Company Department Description'=>'Accounting Department')
                           ,array(
                               'Company Department Code'=>'SMA',
                               'Company Department Name'=>'Store Product Management',
                               'Company Department Description'=>'Department where we order stock and put it on the webpage for selling')

                       ),'WAH'=>array(
                                   array(
                                       'Company Department Code'=>'OHA',
                                       'Company Department Name'=>'Order Handing',
                                       'Company Department Description'=>'Picking & Packing Department')
                                   ,array(
                                       'Company Department Code'=>'STK',
                                       'Company Department Name'=>'Stock Keeping',
                                       'Company Department Description'=>'Dealing with Deliveries and stock movements')

                               ),'PRD'=>array(
                                           array(
                                               'Company Department Code'=>'GEN',
                                               'Company Department Name'=>'General Production',
                                               'Company Department Description'=>'Product all kinds of products')


                                       )


             );


foreach($departments as $area_code=>$departments_data) {
    $area=new CompanyArea('code',$area_code);
    foreach($departments_data as $data) {
        $area->add_department($data);
    }
}


$positions=array(
               'OHA'=>array(
                         array(
                             'Company Position Code'=>'PIK',
                             'Company Position Title'=>'Picker',
                             'Company Position Description'=>'Warehouse Parts Picker'
                         ),
                          array(
                             'Company Position Code'=>'PAK',
                             'Company Position Title'=>'Packer',
                             'Company Position Description'=>'Orders Packer'
                         ),
                          array(
                             'Company Position Code'=>'CUS',
                             'Company Position Title'=>'Customer Service',
                             'Company Position Description'=>'Customer Service'
                         )
                         
                     )

           );

foreach($positions as $department_code=>$positions_data) {
    $department=new CompanyDepartment('code',$department_code);
    if(!$department->id){
    print_r($department);
    exit;
    }
    foreach($positions_data as $data) {
        $department->add_position($data);
    }
}

$staff=array(
array(s)
);


?>