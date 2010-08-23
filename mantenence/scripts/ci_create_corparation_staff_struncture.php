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
include_once('../../class.Staff.php');

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


$data=array(
          'editor'=>array('Date'=>'2003-08-28 09:00:00')
                   ,'Company Name'=>'AW Regalos'
                                   ,'Company Fiscal Name'=>'Costa Imports'
                                                          ,'Company Tax Number'=>''
                                                                                ,'Company Registration Number'=>''
                                                                                                               ,'Company Main Plain Email'=>'mail@aw-regalos.com'
      );


$company=new Company('find create auto',$data);
$sql=sprintf("delete * from  `Corporation Dimension` " );
mysql_query($sql);
$sql=sprintf("insert into `Corporation Dimension` values (%s,'EUR',%d) ",prepare_mysql($company->data['Company Name']),$company->id );
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

       );

foreach($areas as $areas_data) {
    $area=new CompanyArea('find',$areas_data,'create');
}



$departments=array(
                 'OFC'=>array(
                           array(
                               'Company Department Code'=>'DIR',
                               'Company Department Name'=>'Direction',
                               'Company Department Description'=>'Director Office')
                           ,

                           array(
                               'Company Department Code'=>'CUS',
                               'Company Department Name'=>'Customer Services',
                               'Company Department Description'=>'Customer Services')

                           ,array(
                               'Company Department Code'=>'MRK',
                               'Company Department Name'=>'Marketing',
                               'Company Department Description'=>'Marketing Department')


                       ),'WAH'=>array(
                                   array(
                                       'Company Department Code'=>'OHA',
                                       'Company Department Name'=>'Order Handing',
                                       'Company Department Description'=>'Picking & Packing Department')
                                   ,array(
                                       'Company Department Code'=>'STK',
                                       'Company Department Name'=>'Stock Keeping',
                                       'Company Department Description'=>'Dealing with Deliveries and stock movements')

                               )


             );


foreach($departments as $area_code=>$departments_data) {
    $area=new CompanyArea('code',$area_code);
    
       
    if($area_code=='PRD')
    $production_area_key=$area->id;
       if($area_code=='WAH')
    $warehouse_area_key=$area->id;
       if($area_code=='OFC')
    $office_area_key=$area->id;
    
    foreach($departments_data as $data) {
        $area->add_department($data);
    }
}


$positions=array(
               'MRK'=>array(
                         array(
                             'Company Position Code'=>'WEB',
                             'Company Position Title'=>'Web Designer',
                             'Company Position Description'=>'Web Designer'
                         )

                     )
                     ,'DIR'=>array(
                                array(
                                    'Company Position Code'=>'DIR',
                                    'Company Position Title'=>'General Manager',
                                    'Company Position Description'=>'Director'
                                )
                            )










                            ,'OHA'=>array(
                                       array(
                                           'Company Position Code'=>'PICK',
                                           'Company Position Title'=>'Picker & Packer',
                                           'Company Position Description'=>'Warehouse Parts Picker'
                                       )



                                   )

                                   ,'CUS'=>array(
                                              array(
                                                  'Company Position Code'=>'CUSM',
                                                  'Company Position Title'=>'Manager Customer Service',
                                                  'Company Position Description'=>'Manager Customer Service'
                                              )
                                              ,array(
                                                  'Company Position Code'=>'CUS',
                                                  'Company Position Title'=>'Customer Service',
                                                  'Company Position Description'=>'Customer Service'
                                              )
                                          )

           );
$departments_keys=array();
foreach($positions as $department_codes=>$positions_data) {
    foreach(preg_split('/,/',$department_codes) as $key =>$department_code ) {

        $department=new CompanyDepartment('code',$department_code);
           $departments_keys[$department_code]=$department->id;
        if (!$department->id) {
            print_r($department);
            exit;
        }
        foreach($positions_data as $data) {
            $department->add_position($data);
        }
    }

}






$staff=array(

           'PICK'=>array(
                      array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'David','Staff Alias'=>'david')
                      ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Borja','Staff Alias'=>'borja')
                      ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Dany','Staff Alias'=>'dany')
                      ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Juan','Staff Alias'=>'juan','Staff Currently Working'=>'No')
                      ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Alejandro','Staff Alias'=>'alejandro','Staff Currently Working'=>'No')
                      ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Jose','Staff Alias'=>'jose','Staff Currently Working'=>'No')
                      
                   
                  )

                  ,'WEB'=>array(
                             array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['MRK'],'Staff Name'=>'Helena','Staff Alias'=>'helena')
                         )
                         ,'DIR'=>array(
                                    array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['DIR'],'Staff Name'=>'Carlos Lopez','Staff Alias'=>'carlos')
                                )

                                ,'CUSM'=>array(
                                            array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Lucia','Staff Alias'=>'lucia')


                                        )

                                        ,'CUS'=>array(
                                                   array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Trini','Staff Alias'=>'trini'),
                                                   array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Lorena','Staff Alias'=>'lorena','Staff Currently Working'=>'No'),
                                                   array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Karolina','Staff Alias'=>'karolina','Staff Currently Working'=>'No'),
                                                   array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Noemi','Staff Alias'=>'noemi','Staff Currently Working'=>'No'),
                                                   array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Paula','Staff Alias'=>'paula','Staff Currently Working'=>'No'),
                                                   array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Anabel','Staff Alias'=>'anabel','Staff Currently Working'=>'No'),
                                                   array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Penelope','Staff Alias'=>'penelope','Staff Currently Working'=>'No')


                                               )


       );


foreach($staff as $position_codes=>$staff_data) {
    foreach(preg_split('/,/',$position_codes) as $key =>$position_code ) {

        $position=new CompanyPosition('code',$position_code);
        if (!$position->id) {
            print "$position_code\n";
            //print_r($position);
            exit;
        }
        foreach($staff_data as $data) {
            $position->add_staff($data);
        }
    }

}



?>