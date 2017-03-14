<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


require_once 'common.php';

require_once 'class.Store.php';
require_once 'class.Category.php';
require_once 'class.Product.php';
require_once 'class.Part.php';


$mysql_host = 'bk3.inikoo.com';
$mysql_user = 'inikoo';

$con_drop = @mysql_connect($mysql_host, $mysql_user, 'E76hfjmPAFRJTy7z');
if (!$con_drop) {
    print "Error can not connect with dropshipping database server\n";
    exit;
}
$db2 = @mysql_select_db("drop", $con_drop);
if (!$db2) {
    print "Error can not access the database in drop \n";
    exit;
}

$con = @mysql_connect($dns_host, $dns_user, $dns_pwd);

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db = @mysql_select_db("dw", $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$store = new Store('code', 'DS');


$department_bridge = array();
$family_bridge     = array();

//print_r($store);


/*

//exit;
$sql= "SELECT * FROM `drop`.`catalog_category_entity` where level=2";
$res=mysql_query($sql,$con_drop);
while ($row=mysql_fetch_assoc($res)) {

	$code='';
	$name='';
	$description='';

	$sql=sprintf("SELECT * FROM `drop`.`catalog_category_entity_varchar` WHERE  `entity_id` =%d  and attribute_id=%d ",$row['entity_id'] , getMagentoAttNumber($con_drop,'name',3));
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$code=preg_replace('/\s/i','',$row2['value']);
		$code=preg_replace('/\'/i','',$code);
		$code=preg_replace('/\&/i','',$code);
		$code=substr($code,0,5);
		$name=$row2['value'];
	}

	$sql=sprintf("SELECT * FROM `drop`.`catalog_category_entity_text` WHERE  `entity_id` =%d  and attribute_id=%d ",$row['entity_id'] , getMagentoAttNumber($con_drop,'meta_description',3));
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {

		$description=$row2['value'];
	}


	if (in_array($code,array('chess'))) {

		continue;
	}




	// print "D: -----\n";
	//print "D: ".$row['entity_id']." code:   $code\n";
	// print "name: $name\n";
	// print "description: $description\n";
	$editor['Date']=$row['created_at'];
	$department=new Department('find',array(
			'Product Department Code'=>$code
			,'Product Department Name'=>$name
			,'Product Department Description'=>$name
			,'Product Department Store Key'=>$store->id
			,'Product Department Valid From'=>$row['created_at']
			,'editor'=>$editor

		),'create');
	if ($department->id) {
		$department_bridge[$row['entity_id']]=$department->id;
	}
}





$sql= "SELECT * FROM `drop`.`catalog_category_entity` where level in (3) and children_count>0";
$res=mysql_query($sql,$con_drop);
while ($row=mysql_fetch_assoc($res)) {

	$department_bridge[$row['entity_id']]=$department_bridge[$row['parent_id']];
}




$sql= "SELECT * FROM `drop`.`catalog_category_entity` where level in (3,4) and children_count=0";
$res=mysql_query($sql,$con_drop);
while ($row=mysql_fetch_assoc($res)) {





	$code='';
	$name='';
	$description='';

	$sql=sprintf("SELECT * FROM `drop`.`catalog_category_entity_varchar` WHERE  `entity_id` =%d  and attribute_id=%d ",$row['entity_id'] , getMagentoAttNumber($con_drop,'name',3));
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$code=preg_replace('/\s/i','',$row2['value']);
		$code=preg_replace('/\'/i','',$code);
		$code=preg_replace('/\&/i','',$code);
		$code=substr($code,0,5);
		$name=$row2['value'];
	}

	$sql=sprintf("SELECT * FROM `drop`.`catalog_category_entity_text` WHERE  `entity_id` =%d  and attribute_id=%d ",$row['entity_id'] , getMagentoAttNumber($con_drop,'meta_description',3) );
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {

		$description=$row2['value'];
	}
	//print "F: parent_id ".$row['parent_id']." ";
	// print "code:   $code\n";
	// print "name: $name\n";
	//print "description: $description\n";

	if (array_key_exists($row['parent_id'],$department_bridge)) {
		$department=new Department($department_bridge[$row['parent_id']]);
	}else {
		$department=new Department('code_store','ND_'.$store->data['Store Code'],$store->id);
	}


	$editor['Date']=$row['created_at'];
	$family_data=array(

		'Product Family Code'=>$code,
		'Product Family Name'=>$name,
		'Product Family Description'=>$description,
		'Product Family Special Characteristic'=>$name,
		'Product Family Main Department Key'=>$department->id,
		'Product Family Store Key'=>$department->data['Product Department Store Key'],
		'Product Family Valid From'=>$row['created_at'],
		'editor'=>$editor

	);

	
	$family=new Family('create',$family_data);
	if ($family->id) {
		$family_bridge[$row['entity_id']]=$family->id;
	}




}

*/

$sql = "SELECT * FROM `drop`.`catalog_product_entity` WHERE sku IS NOT NULL AND sku NOT IN ('EO-')   ";
$res = mysql_query($sql, $con_drop);
while ($row = mysql_fetch_assoc($res)) {

    $store_code    = $store->data['Store Code'];
    $order_data_id = $row['entity_id'];

    $sql   = sprintf(
        "SELECT * FROM `Product Import Metadata` WHERE `Metadata`=%s AND `Import Date`>=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at'])

    );
    $resxx = mysql_query($sql);
    if ($rowxx = mysql_fetch_assoc($resxx)) {

        continue;
    }


    $code = $row['sku'];


    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'name', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $name = $row2['value'];
    } else {
        exit("error no name associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'awsku', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $sku = $row2['value'];
    } else {
        //print $row['entity_id']." $code error no sku associated \n";
        exit("error no sku associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'relate', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        //print_r($row2);
        $parts_per_product = floatval($row2['value']);
    } else {
        exit("error no part_relation associated\n");
    }


    if (!is_numeric($parts_per_product) or $parts_per_product <= 0) {
        print_r($row);
        print_r($row2);
        exit("wrong parts per product\n");
    }


    if ($parts_per_product == '') {
        print "$sku $parts_per_product\n";
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_text` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'description', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $description = $row2['value'];
    } else {
        exit("error no description associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'price', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $price = $row2['value'];
    } else {
        exit("error no description associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'weight', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $weight = $row2['value'];
    } else {
        exit("error no description associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_category_product` WHERE  `product_id` =%d   ", $row['entity_id']);
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {

      // print_r($row2);


        $sql  = sprintf("SELECT * FROM `drop`.`catalog_category_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row2['category_id'], getMagentoAttNumber($con_drop, 'name', 3));
        $res2 = mysql_query($sql, $con_drop);
        if ($row2 = mysql_fetch_assoc($res2)) {


            $category_code = preg_replace('/\s/i', '', $row2['value']);
            $category_code = preg_replace('/\'/i', '', $category_code);
            $category_code = preg_replace('/\&/i', '', $category_code);
            $category_code = substr($category_code, 0, 5);


        }

    }


    //print_r($family_bridge);
    $weight = $weight / 1000;
    //$weight=500;
    //print_r($family);
    //exit;
    //print $family->data['Product Family Code']."\n";
    $editor['Date'] = $row['created_at'];


    $part = new Part('sku', $sku);

    if(!$part->sku){

        print '** Error SKU not found '.$code."  $sku  \n";
        continue;
    }


    $product_data = array(


        'Product Code'            => $code,
        'Product CPNP Number'     => '',
        'Product Parts'           => json_encode(
           array( array(
                'Key'      => '',
                'Part SKU' => $part->sku,
                'Ratio'    => $parts_per_product,
                'Note'     => '',
            )
           )
        ),
        'Family Category Code'    => $category_code,
        'Product Label in Family' => '',
        'Product Units Per Case'  => 1,
        'Product Unit Label'      => 'piece',
        'Product Price'           => $price,
        'Product Name'            => $name,
        'Product Unit RRP'        => '',
        'Product Unit Weight'     => $weight,
        'Product Description'     => $description,
        'editor'                  => $editor,
    );


    /*

            'Product Stage'=>'Normal',
            'Product Sales Type'=>'Public Sale',
            'Product Type'=>'Normal',
            'Product Stage'=>'Normal',
            'Product Record Type'=>'Normal',
            'Product Web Configuration'=>'Online Auto',
            'Product Store Key'=>$store->id,
            'Product Currency'=>$store->data['Store Currency Code'],
            'Product Locale'=>$store->data['Store Locale'],
            'Product Price'=>$price,
            //  'Product rrp'=>$price,
            'Product Units Per Case'=>1,
            'Product Family Key'=>$category_code,

            'Product Valid From'=>$editor['Date'],
            //  'Product valid to'=>$editor['Date'],
            'Product Code'=>$code,
            'Product Name'=>$name,
            'Product Description'=>$description,
            'Product Special Characteristic'=>$name,
            'editor'=>$editor,
            'Product Net Weight'=>$weight,
            'Product Package Weight'=>$weight,
            //  'Product Part Metadata'=>$data['values']['Product Part Metadata']
        );

    */



    $product = $store->create_product($product_data);


    if($store->error){


        if( $store->error_code=='duplicate_product_code_reference'){
            $sql = sprintf(
                "INSERT INTO `Product Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at']), prepare_mysql($row['updated_at'])
            );
            mysql_query($sql);
        }else{
            print $store->msg."\n";
            print $store->error_code."\n";


        }


    }else {


        if ($product->id) {

            $sql = sprintf(
                "INSERT INTO `Product Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at']), prepare_mysql($row['updated_at'])
            );
             mysql_query($sql);
        }

    }



    continue;

    $product = new Product('find', $product_data, 'create');
    //print "$sku $parts_per_product\n";

    //print_r($product);
    if ($product->new_id) {
        $part = new Part('sku', $sku);

        if ($part->sku) {
            $part_list           = array();
            $part_list[]         = array(

                'Part SKU' => $part->get('Part SKU'),

                'Parts Per Product' => $parts_per_product,
                'Product Part Type' => 'Simple'

            );
            $product_part_header = array(
                'Product Part Valid From'  => $editor['Date'],
                //'Product Part Valid To'=>$date2,
                'Product Part Most Recent' => 'Yes',
                'Product Part Type'        => 'Simple'

            );

            //print_r($product_part_header);

            $product->new_current_part_list($product_part_header, $part_list);
            $part->update_used_in();

        } else {
            print "error no sku found";
            print_r($product_data);

        }

    }

    if ($product->found_in_id) {
        $update_data = array(
            'Product Net Weight'     => $weight,
            'Product Package Weight' => $weight
        );
        //print_r($update_data);
        $product->update($update_data);
    }
    $product->update_web_configuration('Online Auto');


    //print_r($product);
    //print " $name\n $description";

}


function getMagentoAttNumber($dbh, $attribute_code, $entity_type_id) {

    global $con_drop;
    $Att_Got = '';
    $sql     = "SELECT `attribute_id` FROM `drop`.`eav_attribute` WHERE `attribute_code` LIKE '".$attribute_code."' AND `entity_type_id` =".$entity_type_id."  ";
    $res     = mysql_query($sql, $con_drop);
    if ($row = mysql_fetch_assoc($res)) {


        $Att_Got = $row['attribute_id'];
    } else {
        print $sql."\n";

        echo mysql_errno($con_drop).": ".mysql_error($con_drop)."\n";
        exit;
    }


    return $Att_Got;

}


?>
