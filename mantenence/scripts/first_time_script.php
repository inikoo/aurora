<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 Inikoo Ltd
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
       
	  'Company Name'=>'ACME',
	  'Company Fiscal Name'=>'ACME Ltd',
	  'Company Tax Number'=>'000000000',
	  'Company Registration Number'=>'0000000',
	  'Company Main Plain Email'=>'demo@example.com',
	  'Company Address Line 1'=>'Acme House',
	  'Company Address Town'=>'SOUTHAMPTON',
	  'Company Address Line 2'=>'3 High Street',
	  'Company Address Line 3'=>'Hedle End',
	  'Company Address Postal Code'=>'SO31 4NG',
	  'Company Address Country Name'=>'United Kingdom',
	  'Company Address Country First Division'=>'',
	  'Company Address Country Second Division'=>'',
      );


$company=new Company('find create auto',$data);
$address_collection_address_key=$company->data['Company Main Address Key'];
$sql=sprintf("delete * from  `Account Dimension` " );
mysql_query($sql);
$sql=sprintf("insert into `Account Dimension` values (%s,'GBR','GB','GBP',%d) ",prepare_mysql($company->data['Company Name']),$company->id );
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
                       

                       ),'WAH'=>array(
                                   array(
                                       'Company Department Code'=>'OHA',
                                       'Company Department Name'=>'Order Handing',
                                       'Company Department Description'=>'Picking & Packing Department')
                                  
                               )


             );


foreach($departments as $area_code=>$departments_data) {
    $area=new CompanyArea('code',$area_code);
    
   
       if($area_code=='WAH')
    $warehouse_area_key=$area->id;
       if($area_code=='OFC')
    $office_area_key=$area->id;
    
    
    foreach($departments_data as $data) {
   // print_r($data);
        $area->add_department($data);
    }
}


$positions=array(
		
		 'DIR'=>array(
			      array(
				    'Company Position Code'=>'DIR',
				    'Company Position Title'=>'Director',
				    'Company Position Description'=>'General Director'
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
	    
	       ,'CUS'=>array(
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

//print_r($positions_data);
//print "$department_code\n";

    $department=new CompanyDepartment('code',$department_code);
    $departments_keys[$department_code]=$department->id;
    if(!$department->id){
      print_r($department);
    exit("xxxxxx");
    }
    foreach($positions_data as $data) {
      $department->add_position($data);
    }
  }

}






$staff=array(
	   
	       'OHA.M'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$warehouse_area_key,'Staff Department Key'=>$departments_keys['OHA'],'Staff Name'=>'Mark Smith','Staff Alias'=>'mark','Staff Currently Working'=>'Yes')

			    
			     )
	   	      ,'DIR'=>array(
			     array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['DIR'],'Staff Name'=>'Albert McGlober','Staff Alias'=>'albert','Staff Currently Working'=>'Yes')
				)
	        

	      ,'CUS'=>array(
			    array('Create User'=>true,'Staff Area Key'=>$office_area_key,'Staff Department Key'=>$departments_keys['CUS'],'Staff Name'=>'Nancy Cowell','Staff Alias'=>'nancy','Staff Currently Working'=>'Yes')
						    
			    )


	     );


foreach($staff as $position_codes=>$staff_data) {
  foreach(preg_split('/,/',$position_codes) as $key =>$position_code ){

    $position=new CompanyPosition('code',$position_code);
    if(!$position->id){
      print "$position_code\n";
      //print_r($position);
    exit("xxxxx xxxxx");
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
'Tax Category Name'=>'VAT 20%',
'Tax Category Rate'=>0.2
);
$cat_tax=new TaxCategory('find',$data,'create');


$store_data=array(
            'Store Code'=>'UK',
		  'Store Name'=>'Acme',
		  'Store Locale'=>'en_GB',
		  'Store Home Country Code 2 Alpha'=>'GB',
		  'Store Currency Code'=>'GBP',
		  'Store Home Country Name'=>'United Kingdom', 
		  'Store Home Country Code 2 Alpha'=>'UK', 
		  'Store URL'=>'acme.biz',
		  'Store Email'=>'mail@acme.biz',
		  'Store Telephone'=>'+44 (0) 114 000 0000',
		  'Store FAX'=>'+44 (0) 114 000 0001',
		  'Store Slogan'=>'guacamole',
		  'Store Tax Category Code'=>'S1',
		 'Store Collection Address Key'=> $address_collection_address_key
		  );
$store=new Store('find',$store_data,'create');

$store_collection_address=array(
    ''
);


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



?>
