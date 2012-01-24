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


$data=array(
          'editor'=>array('Date'=>'2003-08-28 09:00:00'),
	  'Company Name'=>'Ancient Wisdom',
	  'Company Fiscal Name'=>'Ancient Wisdom Marketing Ltd',
	  'Company Tax Number'=>'764298589',
	  'Company Registration Number'=>'4108870',
	  'Company Main Plain Email'=>'mail@ancientwisdom.biz',
	  'Company Address Line 1'=>'Unit 15, Block B',
	  'Company Address Town'=>'Sheffield',
	  'Company Address Line 2'=>'Parkwood Business Park',
	  'Company Address Line 3'=>'Parkwood Road',
	  'Company Address Postal Code'=>'S3 8EL',
	  'Company Address Country Name'=>'United Kingdom',
	  'Company Address Country First Division'=>'',
	  'Company Address Country Second Division'=>'',
      );


$company=new Company('find create auto',$data);
$address_collection_address_key=$company->data['Company Main Address Key'];
$sql=sprintf("delete * from  `HQ Dimension` " );
mysql_query($sql);
$sql=sprintf("insert into `HQ Dimension` values (%s,'GBP',%d) ",prepare_mysql($company->data['Company Name']),$company->id );
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
	     
			      array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Joanna Ciba','Staff Alias'=>'joana','Staff Type'=>'Temporal Worker','Staff Currently Working'=>'Yes')
			      ,array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Magdalena Dawiskiba','Staff Alias'=>'magda','Staff Currently Working'=>'Yes')
			      ,array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Dusan Belan','Staff Alias'=>'dusan','Staff Currently Working'=>'Yes')
			      ,array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Lucie Sicova','Staff Alias'=>'lucie','Staff Currently Working'=>'Yes')
			      ,array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Daniela Matovlava','Staff Alias'=>'daniela','Staff Currently Working'=>'Yes')
			      ,array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Olga Belanova','Staff Alias'=>'olga','Staff Currently Working'=>'Yes')
			      ,array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Dana Marsallova','Staff Alias'=>'dana','Staff Currently Working'=>'Yes')
			      ,array('Create User'=>false,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Danielle Cox','Staff Alias'=>'danielle','Staff Type'=>'Temporal Worker','Staff Currently Working'=>'No')
			      )
	      ,'PROD.M'=>array(
	     
			      array('Create User'=>true,'Staff Area Key'=>$production_area_key,'Staff Department Key'=>$departments_keys['GEN'],'Staff Name'=>'Neal','Staff Alias'=>'neal','Staff Currently Working'=>'Yes')
			      )

	      ,'PICK'=>array(
			    array('Create User'=>false,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Steffanie Cox','Staff Alias'=>'stephanie','Staff Type'=>'Temporal Worker','Staff Currently Working'=>'No')
			    ,array('Create User'=>false,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Adriana Bobokova','Staff Alias'=>'adriana','Staff Currently Working'=>'Yes')
			    ,array('Create User'=>false,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Janet Walker','Staff Alias'=>'janet','Staff Currently Working'=>'No')
			    , array('Create User'=>false,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Lenka Ondrisova','Staff Alias'=>'lenka','Staff Currently Working'=>'No')
			    )
	      ,'PACK'=>array(
			     array('Create User'=>false,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Andrew Barry','Staff Alias'=>'andy','Staff Currently Working'=>'Yes')
			     ,array('Create User'=>false,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Lucy Adams','Staff Alias'=>'lucy','Staff Type'=>'Temporal Worker','Staff Currently Working'=>'Yes')
			     ,array('Create User'=>false,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Ben','Staff Alias'=>'ben','Staff Type'=>'Temporal Worker','Staff Currently Working'=>'Yes')

			     )
	       ,'WAH.SK'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['STK'],'Staff Name'=>'Michael Wragg','Staff Alias'=>'michael','Staff Currently Working'=>'Yes')
			     ,array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['STK'],'Staff Name'=>'Brian','Staff Alias'=>'brian','Staff Currently Working'=>'Yes')

			    
			     )
	       ,'OHA.M'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Craige Blakemore','Staff Alias'=>'craige','Staff Currently Working'=>'Yes')
			     )
	  

	      ,'BUY'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['SMA'],'Staff Name'=>'Alan W','Staff Alias'=>'alan','Staff Currently Working'=>'Yes')
			     
				)
	      ,'OFC.SK'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['STK'],'Staff Name'=>'Eric Zee','Staff Alias'=>'eric','Staff Currently Working'=>'Yes')
				)
	      ,'WEB'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Raul Perusquia','Staff Alias'=>'raul','Staff Currently Working'=>'Yes')
				)
	      ,'DIR'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['DIR'],'Staff Name'=>'David Hardy','Staff Alias'=>'david','Staff Currently Working'=>'Yes')
				)
	       ,'ACC'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['ACC'],'Staff Name'=>'Slavka Hardy','Staff Alias'=>'slavka','Staff Currently Working'=>'Yes')
			     )
	       ,'MRK.O'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['MRK'],'Staff Name'=>'Katka Buchy','Staff Alias'=>'katka','Staff Currently Working'=>'No')
			     ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['MRK'],'Staff Name'=>'Tomas Belam','Staff Alias'=>'tomas','Staff Currently Working'=>'Yes')
			     )


	      ,'CUS'=>array(
			    array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Kerry Miskelly','Staff Alias'=>'kerry','Staff Currently Working'=>'Yes')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Sarka Doubravova','Staff Alias'=>'sarka','Staff Currently Working'=>'Yes')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Zoe','Staff Alias'=>'zoe','Staff Currently Working'=>'No')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Philippe Buchy','Staff Alias'=>'philippe','Staff Currently Working'=>'Yes')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.DE'],'Staff Name'=>'Martina Otte','Staff Alias'=>'martina','Staff Currently Working'=>'Yes')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.FR'],'Staff Name'=>'Nassim Khelifa','Staff Alias'=>'nassim','Staff Currently Working'=>'No')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.FR'],'Staff Name'=>'Bruno Petit-Jean','Staff Alias'=>'bruno','Staff Currently Working'=>'Yes')

			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.FR'],'Staff Name'=>'Nabil','Staff Alias'=>'nabil','Staff Currently Working'=>'No')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Amanda Fray','Staff Alias'=>'amanda','Staff Currently Working'=>'No')
			    ,Array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.PL'],'Staff Name'=>'Urszula Baka','Staff Alias'=>'urszula','Staff Currently Working'=>'Yes')
			    ,array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS.UK'],'Staff Name'=>'Zoe Hilbert','Staff Alias'=>'zhilbert','Staff Currently Working'=>'Yes')

			    
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


$data=array(
	    'Tax Category Code'=>'S1',
	    'Tax Category Name'=>'VAT 17.5%',
	'Tax Category Rate'=>0.175
);
$cat_tax=new TaxCategory('find',$data,'create');



$data=array(
	    'Tax Category Code'=>'S2',
'Tax Category Name'=>'VAT 20%',
'Tax Category Rate'=>0.2
);
$cat_tax=new TaxCategory('find',$data,'create');
$data=array(
	    'Tax Category Code'=>'S3',
'Tax Category Name'=>'VAT 15%',
'Tax Category Rate'=>0.15
);
$cat_tax=new TaxCategory('find',$data,'create');

$store_data=array('Store Code'=>'UK',
		  'Store Name'=>'Ancient Wisdom',
		  'Store Locale'=>'en_GB',
		  'Store Home Country Code 2 Alpha'=>'GB',
		  'Store Currency Code'=>'GBP',
		  'Store Home Country Name'=>'United Kingdom', 
		  'Store Home Country Short Name'=>'UK', 
		  'Store URL'=>'ancietwisdom.biz',
		  'Store Email'=>'mail@ancientwisdom.biz',
		  'Store Telephone'=>'+44 (0) 114 272 9165',
		  'Store FAX'=>'+44 (0) 114 270 6571',
		  'Store Slogan'=>'giftware sourced worldwide',
		  'Store Tax Category Code'=>'S1',
		 'Store Collection Address Key'=> $address_collection_address_key
		  );
$store=new Store('find',$store_data,'create');

$store_collection_address=array(
    ''
);

$store_data=array('Store Code'=>'DE',
		  'Store Name'=>'AW-Geshenke',
		  'Store Locale'=>'de_DE',
		  'Store Home Country Code 2 Alpha'=>'DE',
		  'Store Currency Code'=>'EUR',
		  'Store Home Country Name'=>'Germany', 
		  'Store Home Country Short Name'=>'DE', 
		  'Store URL'=>'aw-geschenke.com',
		  'Store Email'=>'martina@aw-geschenke.com',
		  'Store Telephone'=>'+49 (0)831 2531 986',
		  'Store FAX'=>'',
		  'Store Slogan'=>'Geschenkwaren',
'Store Tax Category Code'=>'S1',
		 'Store Collection Address Key'=> $address_collection_address_key

		  );
$store=new Store('find',$store_data,'create');
$store_data=array('Store Code'=>'FR',
		  'Store Name'=>'AW-Cadeaux',
		  'Store Locale'=>'fr_FR',
		  'Store Home Country Code 2 Alpha'=>'FR',
		  'Store Currency Code'=>'EUR',
		  'Store Home Country Name'=>'France', 
		  'Store Home Country Short Name'=>'FR', 
		  'Store URL'=>'aw-cadeux.com',
		  'Store Email'=>'nassim@aw-cadeux.com',
		  'Store Telephone'=>'',
		  'Store FAX'=>'',
		  'Store Slogan'=>'',
		  'Store Tax Category Code'=>'S1',
		  		 'Store Collection Address Key'=> $address_collection_address_key

		  );
$store=new Store('find',$store_data,'create');

$warehouse=new Warehouse('find',array('Warehouse Code'=>'W','Warehouse Name'=>'Parkwood'),'create');

$unk_location=new Location('find',array('Location Code'=>'UNK','Location Name'=>'Unknown'),'create');

$unk_supplier=new Supplier('find',array('Supplier Code'=>'UNK','Supplier Name'=>'Unknown'),'create');

$store_key=1;
$charge_data=array(
		     'Charge Description'=>'£7.50 small order'
		      ,'Store Key'=>$store_key
		     ,'Charge Trigger'=>'Order'
		     ,'Charge Type'=>'Amount'
		     ,'Charge Name'=>'Small Order Charge'
		     ,'Charge Terms Type'=>'Order Items Gross Amount'
		     ,'Charge Terms Description'=>'when Order Items Gross Amount is less than £50.00'
		     ,'Charge Begin Date'=>''
		     ,'Charge Expiration Date'=>''
		     );
$small_order_charge=new Charge('find create',$charge_data);


$dept_data=array(
		   'Product Department Code'=>'ND_UK',
		   'Product Department Name'=>'Products Without Department',
		   'Product Department Store Key'=>$store_key
		   );

$dept_no_dept=new Department('find',$dept_data,'create');
$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		   'Product Department Code'=>'Promo_UK',
		   'Product Department Name'=>'Promotional Items',
		   'Product Department Store Key'=>$store_key
		   );
$dept_promo=new Department('find',$dept_data,'create');

$dept_promo_key=$dept_promo->id;

$fam_data=array(
		   'Product Family Code'=>'PND_UK',
		   'Product Family Name'=>'Products Without Family',
		   'Product Family Main Department Key'=>$dept_no_dept_key,
		   'Product Family Store Key'=>$store_key,
		   'Product Family Special Characteristic'=>'None'
		   );

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		   'Product Family Code'=>'Promo_UK',
		   'Product Family Name'=>'Promotional Items',
		   'Product Family Main Department Key'=>$dept_promo_key,
		   'Product Family Store Key'=>$store_key,
		   'Product Family Special Characteristic'=>'None'
		   );



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;


 $campaign=array(
		     'Deal Name'=>'Gold Reward'
		     ,'Deal Code'=>'UK.GR'
		     ,'Deal Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		     ,'Campaign Deal Metadata Terms Type'=>'Order Interval'
		     ,'Campaign Deal Metadata Terms Description'=>'last order within 1 month'
		     ,'Campaign Deal Metadata Terms Lock'=>'Yes'

		     );
$gold_camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Metadata Name'=>'[Product Family Code] Gold Reward'
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
		     'Deal Name'=>'Volumen Discount'
		      ,'Deal Code'=>'UK.Vol'
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
		     'Deal Name'=>'Free Shipping'
		      ,'Deal Code'=>'UK.FShip'
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
		     'Deal Name'=>'BOGOF'
		       ,'Deal Code'=>'UK.BOGOF'
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
		     'Deal Name'=>'First Order Bonus'
		     ,'Campaign Trigger'=>'Order'
		       ,'Deal Code'=>'UK.FOB'
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


//=============================================================
// Germany
$store=new Store("code","DE");
$store_key=$store->id;

//exit($store_key);
$dept_data=array(
		 'Product Department Code'=>'ND_DE',
		 'Product Department Name'=>'Products Without Department',
		 'Product Department Store Key'=>$store_key
		 );

$dept_no_dept=new Department('find',$dept_data,'create');
$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		 'Product Department Code'=>'Promo_DE',
		 'Product Department Name'=>'Promotional Items',
		 'Product Department Store Key'=>$store_key
		 );
$dept_promo=new Department('find',$dept_data,'create');
$dept_promo_key=$dept_promo->id;

$fam_data=array(
		'Product Family Code'=>'PND_DE',
		'Product Family Name'=>'Products Without Family',
		'Product Family Main Department Key'=>$dept_no_dept_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		'Product Family Code'=>'Promo_DE',
		'Product Family Name'=>'Promotional Items',
		'Product Family Main Department Key'=>$dept_promo_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;

$campaign=array(
		'Deal Name'=>'Goldprämie'
		 ,'Deal Code'=>'DE.GR'
		,'Deal Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Order Interval'
		,'Campaign Deal Metadata Terms Description'=>'last order within 1 month'
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
        ,'Store Key'=>$store_key
		);
$gold_camp=new Deal('find create',$campaign);
//print_r($gold_camp);
//exit;

$data=array(
	    'Deal Metadata Name'=>'[Product Family Code] Goldprämie'
	    ,'Deal Metadata Trigger'=>'Family'
	    ,'Deal Metadata Allowance Type'=>'Percentage Off'
	    ,'Deal Metadata Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Metadata Allowance Target'=>'Family'
	    ,'Deal Metadata Allowance Lock'=>'No'
	    );
$gold_camp->add_deal_schema($data);



//$data=array('Deal Metadata Allowance Target Key'=>$small_order_charge->id);
//$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		'Deal Name'=>'Volumen Discount'
	 ,'Deal Code'=>'DE.Vol'
		,'Campaign Trigger'=>'Family'
		,'Deal Description'=>'Percentage off when order more than some quantity of products in the same family'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Family Quantity Ordered'
		,'Campaign Deal Metadata Terms Description'=>'order [Quantity] or more same family'
		,'Campaign Deal Metadata Terms Lock'=>'No'
		,'Store Key'=>$store_key
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
				   'Deal Name'=>'Free Shipping'
		     	 ,'Deal Code'=>'DE.FShip'
				   ,'Deal Description'=>'Free shipping to selected destinations when order more than some amount'
				   ,'Deal Begin Date'=>''
				   ,'Deal Expiration Date'=>''
				   ,'Campaign Deal Metadata Terms Type'=>'Order Items Net Amount AND Shipping Country'
				   ,'Campaign Deal Metadata Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
				   ,'Campaign Deal Metadata Terms Lock'=>'No'
				   ,'Store Key'=>$store_key
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


$shipping_uk=new Shipping('find',array('Country Code'=>'DEU'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DEU','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'DNK'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DNK','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);



$shipping_uk=new Shipping('find',array('Country Code'=>'AUT'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'NOR'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€795');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);

$campaign=array(
		'Deal Name'=>'BOGOF'
	 ,'Deal Code'=>'DE.BOGOF'
		,'Deal Description'=>'Buy one Get one Free'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Product Quantity Ordered'
		,'Campaign Deal Metadata Terms Description'=>'Buy 1'
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
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
		'Deal Name'=>'First Order Bonus'
	 ,'Deal Code'=>'DE.FOB'
		,'Campaign Trigger'=>'Order'
		,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Order Total Net Amount AND Order Number'
		,'Campaign Deal Metadata Terms Description'=>'order over £100+tax on the first order '
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
		);
$camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Metadata Name'=>'First Order Bonus [Counter]'
	    ,'Deal Metadata Trigger'=>'Order'
            ,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
	    ,'Deal Metadata Allowance Type'=>'Get Free'
	    ,'Deal Metadata Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Metadata Allowance Target'=>'Product'
	    ,'Deal Metadata Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);
//============================================================
// France

$store=new Store("code","FR");
$store_key=$store->id;

$dept_data=array(
		 'Product Department Code'=>'ND_FR',
		 'Product Department Name'=>'Products Without Department',
		 'Product Department Store Key'=>$store_key
		 );

$dept_no_dept=new Department('find',$dept_data,'create');
$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		 'Product Department Code'=>'Promo_FR',
		 'Product Department Name'=>'Promotional Items',
		 'Product Department Store Key'=>$store_key
		 );
$dept_promo=new Department('find',$dept_data,'create');
$dept_promo_key=$dept_promo->id;

$fam_data=array(
		'Product Family Code'=>'PND_FR',
		'Product Family Name'=>'Products Without Family',
		'Product Family Main Department Key'=>$dept_no_dept_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		'Product Family Code'=>'Promo_FR',
		'Product Family Name'=>'Promotional Items',
		'Product Family Main Department Key'=>$dept_promo_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;

$campaign=array(
		'Deal Name'=>'Statut Gold'	
 ,'Deal Code'=>'FR.GR'
		,'Deal Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Order Interval'
		,'Campaign Deal Metadata Terms Description'=>'last order within 1 month'
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
        ,'Store Key'=>$store_key
		);
$gold_camp=new Deal('find create',$campaign);
//print_r($gold_camp);
//exit;

$data=array(
	    'Deal Metadata Name'=>'[Product Family Code] Statut Gold'
	    ,'Deal Metadata Trigger'=>'Family'
	    ,'Deal Metadata Allowance Type'=>'Percentage Off'
	    ,'Deal Metadata Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Metadata Allowance Target'=>'Family'
	    ,'Deal Metadata Allowance Lock'=>'No'
	    );
$gold_camp->add_deal_schema($data);



//$data=array('Deal Metadata Allowance Target Key'=>$small_order_charge->id);
//$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		'Deal Name'=>'Volumen Discount' ,'Deal Code'=>'FR.Vol'
		,'Campaign Trigger'=>'Family'
		,'Deal Description'=>'Percentage off when order more than some quantity of products in the same family'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Family Quantity Ordered'
		,'Campaign Deal Metadata Terms Description'=>'order [Quantity] or more same family'
		,'Campaign Deal Metadata Terms Lock'=>'No'
		,'Store Key'=>$store_key
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
				   'Deal Name'=>'Free Shipping'
		      ,'Deal Code'=>'FR.FShip'
				   ,'Deal Description'=>'Free shipping to selected destinations when order more than some amount'
				   ,'Deal Begin Date'=>''
				   ,'Deal Expiration Date'=>''
				   ,'Campaign Deal Metadata Terms Type'=>'Order Items Net Amount AND Shipping Country'
				   ,'Campaign Deal Metadata Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
				   ,'Campaign Deal Metadata Terms Lock'=>'No'
				   ,'Store Key'=>$store_key
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


$shipping_uk=new Shipping('find',array('Country Code'=>'DEU'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DEU','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'DNK'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DNK','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);



$shipping_uk=new Shipping('find',array('Country Code'=>'AUT'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'NOR'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€795');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);

$campaign=array(
		'Deal Name'=>'BOGOF'
 ,'Deal Code'=>'FR.BOGOF'
		,'Deal Description'=>'Buy one Get one Free'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Product Quantity Ordered'
		,'Campaign Deal Metadata Terms Description'=>'Buy 1'
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
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
		'Deal Name'=>'First Order Bonus' ,'Deal Code'=>'FR.FOB'

		,'Campaign Trigger'=>'Order'
		,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Order Total Net Amount AND Order Number'
		,'Campaign Deal Metadata Terms Description'=>'order over £100+tax on the first order '
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
		);
$camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Metadata Name'=>'First Order Bonus [Counter]'
	    ,'Deal Metadata Trigger'=>'Order'
            ,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
	    ,'Deal Metadata Allowance Type'=>'Get Free'
	    ,'Deal Metadata Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Metadata Allowance Target'=>'Product'
	    ,'Deal Metadata Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);

//==================================================
// Poland

$store_data=array('Store Code'=>'PL',
		  'Store Name'=>'AW Podarki',
		  'Store Locale'=>'pl_PL',
		  'Store Home Country Code 2 Alpha'=>'PL',
		  'Store Currency Code'=>'PLN',
		  'Store Home Country Name'=>'Poland', 
		  'Store Home Country Short Name'=>'PL', 
		  'Store URL'=>'www.aw-podarki.com',
		  'Store Telephone'=>'+48 1142 677 736',
		  'Store Email'=>'urszula@aw-podarki.com',
		   'Store Tax Category Code'=>'S1',
		  		 'Store Collection Address Key'=> $address_collection_address_key

		  );
$store=new Store('find',$store_data,'create');
$store_key=$store->id;

$dept_data=array(
		 'Product Department Code'=>'ND_PL',
		 'Product Department Name'=>'Products Without Department',
		 'Product Department Store Key'=>$store_key
		 );

$dept_no_dept=new Department('find',$dept_data,'create');
$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		 'Product Department Code'=>'Promo_PL',
		 'Product Department Name'=>'Promotional Items',
		 'Product Department Store Key'=>$store_key
		 );
$dept_promo=new Department('find',$dept_data,'create');
$dept_promo_key=$dept_promo->id;

$fam_data=array(
		'Product Family Code'=>'PND_PL',
		'Product Family Name'=>'Products Without Family',
		'Product Family Main Department Key'=>$dept_no_dept_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		'Product Family Code'=>'Promo_PL',
		'Product Family Name'=>'Promotional Items',
		'Product Family Main Department Key'=>$dept_promo_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;

$campaign=array(
		'Deal Name'=>'Goldprämie'
		,'Deal Code'=>'PL.GR'

		,'Deal Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Order Interval'
		,'Campaign Deal Metadata Terms Description'=>'last order within 1 month'
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
        ,'Store Key'=>$store_key
		);
$gold_camp=new Deal('find create',$campaign);
//print_r($gold_camp);
//exit;

$data=array(
	    'Deal Metadata Name'=>'[Product Family Code] Goldprämie'
	    ,'Deal Metadata Trigger'=>'Family'
	    ,'Deal Metadata Allowance Type'=>'Percentage Off'
	    ,'Deal Metadata Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Metadata Allowance Target'=>'Family'
	    ,'Deal Metadata Allowance Lock'=>'No'
	    );
$gold_camp->add_deal_schema($data);



//$data=array('Deal Metadata Allowance Target Key'=>$small_order_charge->id);
//$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		'Deal Name'=>'Volumen Discount'	,'Deal Code'=>'PL.Vol'
		,'Campaign Trigger'=>'Family'
		,'Deal Description'=>'Percentage off when order more than some quantity of products in the same family'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Family Quantity Ordered'
		,'Campaign Deal Metadata Terms Description'=>'order [Quantity] or more same family'
		,'Campaign Deal Metadata Terms Lock'=>'No'
		,'Store Key'=>$store_key
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
				   'Deal Name'=>'Free Shipping'	,'Deal Code'=>'PL.FShip'
		     
				   ,'Deal Description'=>'Free shipping to selected destinations when order more than some amount'
				   ,'Deal Begin Date'=>''
				   ,'Deal Expiration Date'=>''
				   ,'Campaign Deal Metadata Terms Type'=>'Order Items Net Amount AND Shipping Country'
				   ,'Campaign Deal Metadata Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
				   ,'Campaign Deal Metadata Terms Lock'=>'No'
				   ,'Store Key'=>$store_key
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


$shipping_uk=new Shipping('find',array('Country Code'=>'DEU'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DEU','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'DNK'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DNK','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);



$shipping_uk=new Shipping('find',array('Country Code'=>'AUT'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€500');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'NOR'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€795');
$data=array(
	    'Deal Metadata Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Metadata Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);

$campaign=array(
		'Deal Name'=>'BOGOF'	,'Deal Code'=>'PL.BOGOF'
		,'Deal Description'=>'Buy one Get one Free'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Product Quantity Ordered'
		,'Campaign Deal Metadata Terms Description'=>'Buy 1'
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
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
		'Deal Name'=>'First Order Bonus'	,'Deal Code'=>'PL.FOB'
		,'Campaign Trigger'=>'Order'
		,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Metadata Terms Type'=>'Order Total Net Amount AND Order Number'
		,'Campaign Deal Metadata Terms Description'=>'order over £100+tax on the first order '
		,'Campaign Deal Metadata Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
		);
$camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Metadata Name'=>'First Order Bonus [Counter]'
	    ,'Deal Metadata Trigger'=>'Order'
            ,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
	    ,'Deal Metadata Allowance Type'=>'Get Free'
	    ,'Deal Metadata Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Metadata Allowance Target'=>'Product'
	    ,'Deal Metadata Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);

?>