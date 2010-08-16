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


$data=array(
          'editor'=>array('Date'=>'2003-08-28 09:00:00')
	  ,'Company Name'=>'Ancient Wisdom'
	  ,'Company Fiscal Name'=>'Ancient Wisdom Marketing Ltd'
	  ,'Company Tax Number'=>'764298589'
	  ,'Company Registration Number'=>'4108870'
	  ,'Company Main Plain Email'=>'mail@ancientwisdom.biz'
      );


$company=new Company('find create auto',$data);
$sql=sprintf("delete * from  `Corporation Dimension` " );
mysql_query($sql);
$sql=sprintf("insert into `Corporation Dimension` values (%s,'GBP',%d) ",prepare_mysql($company->data['Company Name']),$company->id );
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
                               'Company Department Code'=>'DIR',
                               'Company Department Name'=>'Direction',
                               'Company Department Description'=>'Director Office')
                           , 
			      
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
				    'Company Position Code'=>'MRK.O',
				    'Company Position Title'=>'Marketing',
				    'Company Position Description'=>'Marketing'
				    )
			      ,array(
				    'Company Position Code'=>'WEB',
				    'Company Position Title'=>'Web Designer',
				    'Company Position Description'=>'Web Designer'
				    )
			      
			      )
		 ,'DIR'=>array(
			      array(
				    'Company Position Code'=>'DIR',
				    'Company Position Title'=>'Director',
				    'Company Position Description'=>'General Director'
				    )
			      )
		 	 
		 ,'ACC'=>array(
			      array(
				    'Company Position Code'=>'ACC',
				    'Company Position Title'=>'Accounts',
				    'Company Position Description'=>'General Accounts '
				    )
			      )
		 	 
		 
		 	 
		 
		 	 
		 ,'STK'=>array(
			       
                          array(
				'Company Position Code'=>'WAH.SK',
				'Company Position Title'=>'Warehouse Stock Keeper',
                             'Company Position Description'=>'Stock Receaving & Handing'
                         ),array(
                             'Company Position Code'=>'OFC.SK',
                             'Company Position Title'=>'Stock Controller',
                             'Company Position Description'=>'Stock Control'
                         )
                          
                          
                         
                     )
		  ,'SMA'=>array(
			       
                    array(
                             'Company Position Code'=>'BUY',
                             'Company Position Title'=>'Buyer',
                             'Company Position Description'=>'Buyer'
                         )
                          
                          
                         
                     )
               ,'OHA'=>array(
                         array(
                             'Company Position Code'=>'PICK',
                             'Company Position Title'=>'Picker',
                             'Company Position Description'=>'Warehouse Parts Picker'
                         ),
                          array(
                             'Company Position Code'=>'PACK',
                             'Company Position Title'=>'Packer',
                             'Company Position Description'=>'Orders Packer'
                         ),
                     
                          array(
                             'Company Position Code'=>'OHA.DM',
                             'Company Position Title'=>'Dispatch Supervisor',
                             'Company Position Description'=>'Dispatch Supervisor'
                         ),
                          array(
                             'Company Position Code'=>'OHA.M',
                             'Company Position Title'=>'Warehouse Manager',
                             'Company Position Description'=>'Warehouse Supervisor'
                         )
                          
                         
                     )
	       ,'GEN'=>array(
                         array(
                             'Company Position Code'=>'PROD.M',
                             'Company Position Title'=>'Production Commander in Chief',
                             'Company Position Description'=>'Production Supervisor'
                         ),
                          array(
                             'Company Position Code'=>'PROD.O',
                             'Company Position Title'=>'Production Operative',
                             'Company Position Description'=>'Production Associate'
                         )
			     )
	       ,'CUS.UK,CUS.PL,CUS.DE,CUS.FR'=>array(
			array(
                             'Company Position Code'=>'CUS',
                             'Company Position Title'=>'Customer Service',
                             'Company Position Description'=>'Customer Service'
                         )	   
						     )
						     
		 );
$departments_keys=array();
foreach($positions as $department_codes=>$positions_data) {
  foreach(preg_split('/,/',$department_codes) as $key =>$department_code ){

    $department=new CompanyDepartment('code',$department_code);
    $departments_keys[$department_code]=$department->id;
    if(!$department->id){
      print_r($department);
    exit;
    }
    foreach($positions_data as $data) {
      $department->add_position($data);
    }
  }

}






$staff=array(
	      'PROD.O'=>array(
	     
			      array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Joanna Ciba','Staff Alias'=>'joana','Staff Type'=>'Temporal Worker')
			      ,array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Magdalena Dawiskiba','Staff Alias'=>'magda')
			      ,array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Dusan Belan','Staff Alias'=>'dusan')
			      ,array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Lucie Sicova','Staff Alias'=>'lucie')
			      ,array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Daniela Matovlava','Staff Alias'=>'daniela')
			      ,array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Olga Belanova','Staff Alias'=>'olga')
			      ,array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Dana Marsallova','Staff Alias'=>'dana')
			      ,array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Danielle Cox','Staff Alias'=>'danielle','Staff Type'=>'Temporal Worker')
			      )
	      ,'PROD.M'=>array(
	     
			      array('Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Neal','Staff Alias'=>'neal')
			      )

	      ,'PICK'=>array(
			    array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Steffanie Cox','Staff Alias'=>'stephanie','Staff Type'=>'Temporal Worker','Staff Currently Working'=>'No')
			    ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Adriana Bobokova','Staff Alias'=>'adriana')
			    ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Janet Walker','Staff Alias'=>'janet')
			    , array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Lenka Ondrisova','Staff Alias'=>'lenka','Staff Currently Working'=>'No')
			    )
	      ,'PACK'=>array(
			     array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Andrew Barry','Staff Alias'=>'andy')
			     ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Lucy Adams','Staff Alias'=>'lucy','Staff Type'=>'Temporal Worker')
			     			     ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Ben','Staff Alias'=>'ben','Staff Type'=>'Temporal Worker')

			     )
	       ,'WAH.SK'=>array(
			     array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['STK'],'Staff Name'=>'Michael Wragg','Staff Alias'=>'michael')
			     ,array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['STK'],'Staff Name'=>'Brian','Staff Alias'=>'brian')

			    
			     )
	       ,'OHA.M'=>array(
			     array('Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Craige Blakemore','Staff Alias'=>'craige')
			     )
	  

	      ,'BUY'=>array(
			     array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['SMA'],'Staff Name'=>'Alan W','Staff Alias'=>'alan')
			     
				)
	      ,'OFC.SK'=>array(
			     array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['STK'],'Staff Name'=>'Eric Zee','Staff Alias'=>'eric')
				)
	      ,'WEB'=>array(
			     array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Raul Perusquia','Staff Alias'=>'raul')
				)
	      ,'DIR'=>array(
			     array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['DIR'],'Staff Name'=>'David Hardy','Staff Alias'=>'david')
				)
	       ,'ACC'=>array(
			     array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['ACC'],'Staff Name'=>'Slavka Hardy','Staff Alias'=>'slavka')
			     )
	       ,'MRK.O'=>array(
			     array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['MRK'],'Staff Name'=>'Katka Buchy','Staff Alias'=>'katka','Staff Currently Working'=>'No')
			     ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['MRK'],'Staff Name'=>'Tomas Belam','Staff Alias'=>'tomas')
			     )


	      ,'CUS'=>array(
			    array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Kerry Miskelly','Staff Alias'=>'kerry')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Sarka Doubravova','Staff Alias'=>'sarka')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Zoe','Staff Alias'=>'zoe','Staff Currently Working'=>'No')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Philippe Buchy','Staff Alias'=>'philippe')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.DE'],'Staff Name'=>'Martina Otte','Staff Alias'=>'martina')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.FR'],'Staff Name'=>'Nassim Khelifa','Staff Alias'=>'nassim','Staff Currently Working'=>'No')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.FR'],'Staff Name'=>'Bruno Petit-Jean','Staff Alias'=>'bruno')

			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.FR'],'Staff Name'=>'Nabil','Staff Alias'=>'nabil','Staff Currently Working'=>'No')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Amanda Fray','Staff Alias'=>'amanda','Staff Currently Working'=>'No')
			    ,Array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.PL'],'Staff Name'=>'Urszula Baka','Staff Alias'=>'urszula')
			    ,array('Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Zoe Hilbert','Staff Alias'=>'zhilbert')

			    
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
      $position->add_staff($data);
    }
  }

}



?>