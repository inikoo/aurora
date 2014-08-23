<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');

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

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;
$count=0;
$sql="select * from `Customer Correlation` where `Correlation`>1000 ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $customer_a=new Customer($row['Customer A Key']);
    $customer_b=new Customer($row['Customer B Key']);
    $customer_a->editor=array('Date'=>date('Y-m-d H:i:s'));
    $customer_b->editor=array('Date'=>date('Y-m-d H:i:s'));


    if ($customer_a->get('Orders')==0 and $customer_b->get('Orders')!=0  ) {

        if ( ( $customer_b->data['Customer Send Newsletter']=='Yes' or $customer_a->data['Customer Send Newsletter']==$customer_b->data['Customer Send Newsletter']) and  strtotime($customer_b->data['Customer Last Order Date'])>strtotime($customer_a->data['Customer First Contacted Date'])) {

            print $count++." @ ".$customer_a->id." -> ".$customer_b->id."\n";
            $customer_b->merge($customer_a->id,'C');
        }

    }




    if (  $customer_a->get('Orders')!=0 and $customer_b->get('Orders')==0 ) {

        if ( ($customer_a->data['Customer Send Newsletter']=='Yes' or $customer_a->data['Customer Send Newsletter']==$customer_b->data['Customer Send Newsletter']) and   strtotime($customer_a->data['Customer Last Order Date'])>strtotime($customer_b->data['Customer First Contacted Date'])) {

            print $count++." * ".$customer_b->id." B:".$customer_a->id."\n";
            $customer_a->merge($customer_b->id,'C');
        }

    }


}


$sql="select * from `Customer Correlation` where `Correlation`>1000 ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $customer_a=new Customer($row['Customer A Key']);
    $customer_b=new Customer($row['Customer B Key']);
    $customer_a->editor=array('Date'=>date('Y-m-d H:i:s'));
    $customer_b->editor=array('Date'=>date('Y-m-d H:i:s'));


    if (($customer_a->get('Orders')!=0 and $customer_b->get('Orders')!=0 ) and    strtotime($customer_b->data['Customer Last Order Date'])>strtotime($customer_a->data['Customer Last Order Date'])  ) {

        if ( ( $customer_b->data['Customer Send Newsletter']=='Yes' or $customer_a->data['Customer Send Newsletter']==$customer_b->data['Customer Send Newsletter'])) {

            print $count++." @ ".$customer_a->id." -> ".$customer_b->id."\n";
            $customer_b->merge($customer_a->id,'C');
        }

    }


 if (($customer_a->get('Orders')!=0 and $customer_b->get('Orders')!=0 ) and    strtotime($customer_b->data['Customer Last Order Date'])<strtotime($customer_a->data['Customer Last Order Date'])  ) {

        if ( ( $customer_a->data['Customer Send Newsletter']=='Yes' or $customer_a->data['Customer Send Newsletter']==$customer_b->data['Customer Send Newsletter'])) {

            print $count++." * ".$customer_b->id." -> ".$customer_a->id."\n";
            $customer_a->merge($customer_b->id,'C');
        }

    }
}


$sql="select * from `Customer Correlation` where `Correlation`>1000 ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $customer_a=new Customer($row['Customer A Key']);
    $customer_b=new Customer($row['Customer B Key']);
    $customer_a->editor=array('Date'=>date('Y-m-d H:i:s'));
    $customer_b->editor=array('Date'=>date('Y-m-d H:i:s'));


    if (($customer_a->get('Orders')!=0 and $customer_b->get('Orders')!=0 ) and    strtotime($customer_b->data['Customer Last Order Date'])<strtotime('2011-04-04')  and    strtotime($customer_b->data['Customer Last Order Date'])>strtotime($customer_a->data['Customer Last Order Date'])  ) {

        if (  $customer_a->data['Customer Send Newsletter']=='Yes' and 
                $customer_a->data['Customer Send Email Marketing']=='Yes' and 
                 $customer_a->data['Customer Send Postal Marketing']=='Yes' and 
                 
                  $customer_b->data['Customer Send Newsletter']=='No' and 
                   $customer_b->data['Customer Send Email Marketing']=='No' and 
                    $customer_b->data['Customer Send Postal Marketing']=='No'  and 
                    $customer_b->get('Notes')==0 and
                    $customer_a->get('Notes')!=0
        ) {
          
            
             print $count++." * ".$customer_b->id." -> ".$customer_a->id."\n";
            $customer_a->merge($customer_b->id,'C');
          //  exit;
       
           
        }

    }


 if (($customer_a->get('Orders')!=0 and $customer_b->get('Orders')!=0 ) and  strtotime($customer_a->data['Customer Last Order Date'])<strtotime('2011-04-04')  and    strtotime($customer_b->data['Customer Last Order Date'])<strtotime($customer_a->data['Customer Last Order Date'])  ) {

        if ( 
        $customer_b->data['Customer Send Newsletter']=='Yes' and 
                $customer_b->data['Customer Send Email Marketing']=='Yes' and 
                 $customer_b->data['Customer Send Postal Marketing']=='Yes' and 
                 
                  $customer_a->data['Customer Send Newsletter']=='No' and 
                   $customer_a->data['Customer Send Email Marketing']=='No' and 
                    $customer_a->data['Customer Send Postal Marketing']=='No' and 
                    $customer_a->get('Notes')==0 and
                    $customer_b->get('Notes')!=0
                    
        ) {

  
     print $count++." @ ".$customer_a->id." -> ".$customer_b->id."\n";
            
            $customer_b->merge($customer_a->id,'C');
            //exit;
           
          
            
            
            
        }

    }
    
    
}

/*

$sql="select * from `Customer Correlation` where `Correlation`>1000 ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $customer_a=new Customer($row['Customer A Key']);
    $customer_b=new Customer($row['Customer B Key']);
    $customer_a->editor=array('Date'=>date('Y-m-d H:i:s'));
    $customer_b->editor=array('Date'=>date('Y-m-d H:i:s'));


    if (($customer_a->get('Orders')!=0 and $customer_b->get('Orders')!=0 ) and    strtotime($customer_b->data['Customer Last Order Date'])>strtotime($customer_a->data['Customer Last Order Date'])  ) {

        if (  $customer_a->data['Customer Send Newsletter']=='Yes' ) {
            
            $newsletter=$customer_a->data['Customer Send Newsletter'];
            $email_marketing=$customer_a->data['Customer Send Email Marketing'];
            $postal_marketing=$customer_a->data['Customer Send Postal Marketing'];
            
            print $count++." @ ".$customer_a->id." -> ".$customer_b->id."\n";
           
            $customer_b->merge($customer_a->id,'C');
            
            $sql=sprintf("update `Customer Dimension` set `Customer Send Newsletter`=%s,`Customer Send Email Marketing`=%s,`Customer Send Postal Marketing`=%s where `Customer Key`=%d",
            prepare_mysql($newsletter),
            prepare_mysql($email_marketing),
            prepare_mysql($postal_marketing),
            $customer_b->id
            );
            mysql_query($sql);
             exit;
             
        }

    }


 if (($customer_a->get('Orders')!=0 and $customer_b->get('Orders')!=0 ) and    strtotime($customer_b->data['Customer Last Order Date'])<strtotime($customer_a->data['Customer Last Order Date'])  ) {

        if (  $customer_b->data['Customer Send Newsletter']=='Yes') {

  $newsletter=$customer_b->data['Customer Send Newsletter'];
            $email_marketing=$customer_b->data['Customer Send Email Marketing'];
            $postal_marketing=$customer_b->data['Customer Send Postal Marketing'];

            print $count++." * ".$customer_b->id." -> ".$customer_a->id."\n";
            
           exit;
            $customer_a->merge($customer_b->id,'C');
             $sql=sprintf("update `Customer Dimension` set `Customer Send Newsletter`=%s,`Customer Send Email Marketing`=%s,`Customer Send Postal Marketing`=%s where `Customer Key`=%d",
            prepare_mysql($newsletter),
            prepare_mysql($email_marketing),
            prepare_mysql($postal_marketing),
            $customer_a->id
            );
            mysql_query($sql);
              exit;
            
            
            
        }

    }
    
    
}


*/





?>