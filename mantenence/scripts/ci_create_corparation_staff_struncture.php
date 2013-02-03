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
include_once('../../class.Charge.php');
include_once('../../class.Deal.php');
include_once('../../class.Shipping.php');

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
          'editor'=>array('Date'=>'2003-08-28 09:00:00'),
          'Company Name'=>'AW Regalos',
          'Company Fiscal Name'=>'Costa Imports',
          'Company Tax Number'=>'',
          'Company Registration Number'=>'',
          'Company Main Plain Email'=>'mail@aw-regalos.com',
          'Company Address Line 1'=>'Paseo de la Hispanidad Nave 8',
          'Company Address Line 2'=>'Polígono Industrial Alhaurín de la Torre',
          'Company Address Town'=>'Málaga',
          'Company Address Postal Code'=>'29130',
          'Company Address Country Name'=>'España',
          'Company Tax Number'=>'ES-B92544691'
          
      );


$company=new Company('find create auto',$data);
$address_collection_address_key=$company->data['Company Main Address Key'];
$sql=sprintf("delete * from  `HQ Dimension` " );
mysql_query($sql);
$sql=sprintf("insert into `HQ Dimension` values (%s,'EUR',%d) ",prepare_mysql($company->data['Company Name']),$company->id );
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


    if ($area_code=='PRD')
        $production_area_key=$area->id;
    if ($area_code=='WAH')
        $warehouse_area_key=$area->id;
    if ($area_code=='OFC')
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
                      array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'David','Staff Alias'=>'david','Staff Currently Working'=>'Yes')
                      ,array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Borja','Staff Alias'=>'borja','Staff Currently Working'=>'No')
                      ,array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Dany','Staff Alias'=>'dany','Staff Currently Working'=>'Yes')
                      ,array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Juan','Staff Alias'=>'juan','Staff Currently Working'=>'No')
                      ,array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Alejandro','Staff Alias'=>'alejandro','Staff Currently Working'=>'No')
                      ,array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Jose','Staff Alias'=>'jose','Staff Currently Working'=>'No')


                  )

                  ,'WEB'=>array(
                             array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['MRK'],'Staff Name'=>'Helena','Staff Alias'=>'helena','Staff Currently Working'=>'Yes')
                         )
                         ,'DIR'=>array(
                                    array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['DIR'],'Staff Name'=>'Carlos Lopez','Staff Alias'=>'carlos','Staff Currently Working'=>'Yes')
                                )

                                ,'CUSM'=>array(
                                            array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Lucia','Staff Alias'=>'lucia','Staff Currently Working'=>'Yes')


                                        )

                                        ,'CUS'=>array(
                                                   array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Trini','Staff Alias'=>'trini','Staff Currently Working'=>'Yes'),
                                                   array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Lorena','Staff Alias'=>'lorena','Staff Currently Working'=>'No'),
                                                   array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Karolina','Staff Alias'=>'karolina','Staff Currently Working'=>'No'),
                                                   array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Noemi','Staff Alias'=>'noemi','Staff Currently Working'=>'No'),
                                                   array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Paula','Staff Alias'=>'paula','Staff Currently Working'=>'No'),
                                                   array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Anabel','Staff Alias'=>'anabel','Staff Currently Working'=>'No'),
                                                   array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Penelope','Staff Alias'=>'penelope','Staff Currently Working'=>'No')


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
            $staff=$position->add_staff($data);
            //print_r($data);
            if($data['Staff Currently Working']=='Yes' and $data['Create User']){
                $user=$staff->create_user();
      }
            
            
        }
    }

}

$data=array(
          'Tax Category Code'=>'S1',
          'Tax Category Name'=>'IVA 16%',
          'Tax Category Rate'=>0.16
      );
$cat_tax=new TaxCategory('find',$data,'create');

$data=array(
          'Tax Category Code'=>'S2',
          'Tax Category Name'=>'IVA 4%',
          'Tax Category Rate'=>0.04
      );
$cat_tax=new TaxCategory('find',$data,'create');

$data=array(
          'Tax Category Code'=>'S3',
          'Tax Category Name'=>'IVA 20%',
          'Tax Category Rate'=>0.20
      );
$cat_tax=new TaxCategory('find',$data,'create');

$store_data=array(
            'Store Code'=>'AWR',
		  'Store Name'=>'AW Regalos',
		  'Store Locale'=>'es_ES',
		  'Store Home Country Code 2 Alpha'=>'ES',
		  'Store Currency Code'=>'EUR',
		  'Store Home Country Name'=>'Spain', 
		  'Store Home Country Code 2 Alpha'=>'ES', 
		  'Store URL'=>'www.aw-regalos.com',
		  'Store Email'=>'info@aw-regalos.com',
		  'Store Telephone'=>'(+34) 952 417 609',
		  'Store FAX'=>'(+34) 952 175 621 ',
		  'Store Slogan'=>'Products Exoticos traidos de todo el mundo',
		  'Store Tax Category Code'=>'S1',
		 'Store Collection Address Key'=> $address_collection_address_key
		  );
$store=new Store('find',$store_data,'create');
$warehouse=new Warehouse('find',array('Warehouse Code'=>'A','Warehouse Name'=>'Málaga'),'create');;

$unk_location=new Location('find',array('Location Code'=>'UNK','Location Name'=>'Locación Desconocida'),'create');;

$unk_supplier=new Supplier('find',array('Supplier Code'=>'UNK','Supplier Name'=>'Provedor Desconocido'),'create');;

$charge_data=array(
		     'Charge Description'=>'€5.00 small order'
		      ,'Store Key'=>$store->id
		     ,'Charge Trigger'=>'Order'
		     ,'Charge Type'=>'Amount'
		     ,'Charge Name'=>'Small Order Charge'
		     ,'Charge Terms Type'=>'Order Items Gross Amount'
		     ,'Charge Terms Description'=>'when Order Items Gross Amount is less than €75.00'
		     ,'Charge Begin Date'=>''
		     ,'Charge Expiration Date'=>''
		     );
$small_order_charge=new Charge('find create',$charge_data);

$dept_data=array(
		   'Product Department Code'=>'ND',
		   'Product Department Name'=>'Products sin Departamento',
		   'Product Department Store Key'=>$store->id
		   );

$dept_no_dept=new Department('find',$dept_data,'create');
if($dept_no_dept->error){
  print_r($dept_no_dept);
  exit;
}

$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		   'Product Department Code'=>'Promo',
		   'Product Department Name'=>'Articulos Promotionales',
		   'Product Department Store Key'=>$store->id
		   );
$dept_promo=new Department('find',$dept_data,'create');

$dept_promo_key=$dept_promo->id;

$fam_data=array(
		   'Product Family Code'=>'PND_ES',
		   'Product Family Name'=>'Productos sin Familia',
		   'Product Family Main Department Key'=>$dept_no_dept_key,
		   'Product Family Store Key'=>$store->id,
		   'Product Family Special Characteristic'=>'None'
		   );

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		   'Product Family Code'=>'Promo_ES',
		   'Product Family Name'=>'Promotional Items',
		   'Product Family Main Department Key'=>$dept_promo_key,
		   'Product Family Store Key'=>$store->id,
		   'Product Family Special Characteristic'=>'None'
		   );



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;


 $campaign=array(
		     'Deal Name'=>'Club Oro','Deal Code'=>'Oro'
		     ,'Deal Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		     ,'Campaign Deal Metadata Terms Type'=>'Order Interval'
		     ,'Campaign Deal Metadata Terms Description'=>'last order within 1 month'
		     ,'Campaign Deal Metadata Terms Lock'=>'Yes'

		     );
$gold_camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Metadata Name'=>'[Product Family Code] Club Oro'
	    ,'Deal Metadata Trigger'=>'Family'
	    ,'Deal Metadata Allowance Type'=>'Percentage Off'
	    ,'Deal Metadata Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Metadata Allowance Target'=>'Family'
	    ,'Deal Metadata Allowance Lock'=>'No'
		     );
$gold_camp->add_deal_schema($data);

$data=array(
	    'Deal Metadata Name'=>'Free [Charge Name]'
	    ,'Deal Metadata Trigger'=>'Order'
	    ,'Deal Metadata Allowance Type'=>'Percentage Off'
	    ,'Deal Metadata Allowance Description'=>'Free [Charge Name]'
	    ,'Deal Metadata Allowance Target'=>'Charge'
	    ,'Deal Allowance Key'=>$small_order_charge->id
        ,'Deal Metadata Allowance Lock'=>'Yes'

		   
		     );
$gold_camp->add_deal_schema($data);

$data=array('Deal Metadata Allowance Target Key'=>$small_order_charge->id);
$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		     'Deal Name'=>'Mayoreo en Familia','Deal Code'=>'Mayo'
		     ,'Campaign Trigger'=>'Family'
		     ,'Deal Description'=>'Percentage off when order more than some quantity of products in the same family'
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		      ,'Campaign Deal Metadata Terms Type'=>'Family Quantity Ordered'
		     ,'Campaign Deal Metadata Terms Description'=>'order [Quantity] or more same family'
		     ,'Campaign Deal Metadata Terms Lock'=>'No'
		     );
$vol_camp=new Deal('find create',$campaign);


$data=array(
		     'Deal Metadata Name'=>'[Product Family Code] Volume Discount'
		     ,'Deal Metadata Trigger'=>'Family'
		     ,'Deal Metadata Allowance Type'=>'Percentage Off'
		     ,'Deal Metadata Allowance Description'=>'[Percentage Off] off'
		     ,'Deal Metadata Allowance Target'=>'Family'
		   	 ,'Deal Metadata Allowance Lock'=>'No'

		     );
$vol_camp->add_deal_schema($data);

$volume_cam_id=$vol_camp->id;


$free_shipping_campaign_data=array(
		     'Deal Name'=>'Free Shipping','Deal Code'=>'Envio'
		     
		     ,'Deal Description'=>'Free shipping to selected destinations when order more than some amount'
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		     ,'Campaign Deal Metadata Terms Type'=>'Order Items Net Amount AND Shipping Country'
		     ,'Campaign Deal Metadata Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
		     ,'Campaign Deal Metadata Terms Lock'=>'No'
		     );
$free_shipping_campaign=new Deal('find create',$free_shipping_campaign_data);


$data=array(
		     'Deal Metadata Name'=>'[Country Name] Free Shipping'
		     ,'Deal Metadata Trigger'=>'Order'
		     ,'Deal Metadata Allowance Type'=>'Percentage Off'
		     ,'Deal Metadata Allowance Description'=>'Free Shipping'
		     ,'Deal Metadata Allowance Target'=>'Shipping'
		     ,'Deal Metadata Allowance Lock'=>'Yes'

		     );
$free_shipping_campaign->add_deal_schema($data);

$free_shipping_campaign_id=$free_shipping_campaign->id;

$shipping_uk=new Shipping('find',array('Country Code'=>'GBR'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','GBR','£175');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);



$campaign=array(
		     'Deal Name'=>'BOGOF','Deal Code'=>'Bogof'
		     ,'Deal Description'=>'Buy one Get one Free'
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		       ,'Campaign Deal Metadata Terms Type'=>'Product Quantity Ordered'
		     ,'Campaign Deal Metadata Terms Description'=>'Buy 1'
		     ,'Campaign Deal Metadata Terms Lock'=>'Yes'
		     );
$bogof_camp=new Deal('find create',$campaign);
$data=array(
		     'Deal Metadata Name'=>'[Product Family Code] BOGOF'
		     ,'Deal Metadata Trigger'=>'Family'
		     ,'Deal Metadata Allowance Type'=>'Get Free'
		     ,'Deal Metadata Allowance Description'=>'get 1 free'
		     ,'Deal Metadata Allowance Target'=>'Product'
		    ,'Deal Metadata Allowance Lock'=>'Yes'
		     );
$bogof_camp->add_deal_schema($data);

$data=array(
	    'Deal Metadata Name'=>'[Product Code] BOGOF'
		     ,'Deal Metadata Trigger'=>'Product'
		     ,'Deal Metadata Allowance Type'=>'Get Same Free'
		     ,'Deal Metadata Allowance Description'=>'get 1 free'
		     ,'Deal Metadata Allowance Target'=>'Product'
		     ,'Deal Metadata Allowance Lock'=>'Yes'

		     );
$bogof_camp->add_deal_schema($data);


$bogof_cam_id=$bogof_camp->id;
$campaign=array(
		     'Deal Name'=>'First Order Bonus','Deal Code'=>'Fob'
		     ,'Campaign Trigger'=>'Order'
		     ,'Deal Description'=>'When you order over £100+vat for the first time we give you over a £100 of stock. (at retail value).'
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		     ,'Campaign Deal Metadata Terms Type'=>'Order Total Net Amount AND Order Number'
		     ,'Campaign Deal Metadata Terms Description'=>'order over £100+tax on the first order '
		     ,'Campaign Deal Metadata Terms Lock'=>'Yes'
		     );
$camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Metadata Name'=>'First Order Bonus [Counter]'
	    ,'Deal Metadata Trigger'=>'Order'
            ,'Deal Description'=>'When you order over £100+vat for the first time we give you over a £100 of stock. (at retail value).'
	    ,'Deal Metadata Allowance Type'=>'Get Free'
	    ,'Deal Metadata Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Metadata Allowance Target'=>'Product'
	    ,'Deal Metadata Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);


?>