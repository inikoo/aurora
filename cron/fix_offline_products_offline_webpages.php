<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 220 March 2017 at 11:14:19 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';
require_once 'class.Part.php';

$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'=>'System',
    'Subject Key'=>0,
    'Author Name'=>'Script (fix offline)',
    'Author Alias' => 'Script (fix offline)',
);



$sql=sprintf('select `Product Category Webpage Key` from `Product Category Dimension` where `Product Category Public`="No" ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
		    $webpage=get_object('Webpage',$row['Product Category Webpage Key']);
		    if($webpage->id and $webpage->get('Webpage State')=='Online'){
                $webpage->update_state('Offline');
            }


		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}





exit;

$sql = sprintf("SELECT `History Abstract`,`Direct Object Key`,`History Date`  FROM  `History Dimension`  WHERE `Indirect Object`='Product Web Configuration' AND `History Date`  > '2018-01-01 00:52:48' AND `User Key`=0    ORDER BY `History Date` ASC;");



if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        print_r($row);

        $product = get_object('Product', $row['Direct Object Key']);
        $product->editor=$editor;


        if (($product->get('Product Status') == 'Active' or $product->get('Product Status') == 'Discontinuing')  and ($product->get('Product Number of Parts') > 0)) {
            $product->update(
                array(
                    'Product Web Configuration' => 'Online Auto'
                )
            );

            print $product->id."\n";

        }else{
            // print_r($product);




            // print_r($product);


            //exit;

        }

        $product->update_web_state();
        $product->update_availability();

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

exit;

$sql = sprintf('SELECT * FROM `Product Dimension`  WHERE  `Product Status` !="Discontinued"  and  `Product Number of Parts` =0 ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = get_object('Product', $row['Product ID']);
        $product->update(array('Product Status'=>'Suspended'),'no_history');



    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf('SELECT * FROM `Product Dimension`  WHERE  `Product Status` ="Active" and `Product Web Configuration`="Online Force For Sale" ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = get_object('Product', $row['Product ID']);


            $product->fast_update(
                array(
                    'Product Web Configuration' => 'Online Auto',
                    'Product Valid To'          => ''
                )
            );


        //print_r($product);

        $product->update_web_state();
        //exit;


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf('SELECT * FROM `Product Dimension`  WHERE  `Product Status` ="Active"  and `Product Web State`="Offline" and `Product Web Configuration`="Online Auto" ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = get_object('Product', $row['Product ID']);

        if ($product->get('Product Web Configuration') == 'Online Force For Sale') {

            $product->fast_update(
                array(
                    'Product Web Configuration' => 'Online Auto',
                    'Product Valid To'          => ''
                )
            );
        }

        //print_r($product);

        $product->update_web_state();
        //exit;


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}



$sql = sprintf('SELECT * FROM `Product Dimension`  WHERE  `Product Status` IN ("Suspended","Discontinued")  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = get_object('Product', $row['Product ID']);

        $product->fast_update(
            array(
                'Product Web Configuration' => 'Offline',
                'Product Web State'         => 'Offline'

            )
        );

        if ($product->get('Product Valid To') == '') {
            $product->update_field('Product Valid To', gmdate('Y-m-d H:i:s'), 'no_history');

        }


        if ($row['Product Webpage Key']) {

            $webpage = new Page($row['Product Webpage Key']);

            if ($webpage->get('Webpage State') == 'Online') {
                $webpage->editor = $editor;
                $webpage->update_state('Offline');
            }
        }

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

$sql = sprintf('SELECT * FROM `Product Dimension`    WHERE  `Product Status` IN ("Discontinuing") ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = get_object('Product', $row['Product ID']);

        $product->fast_update(
            array(
                'Product Web Configuration' => 'Online Auto',
                'Product Valid To'          => ''
            )
        );

        $product->update_web_state();


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}





$sql = sprintf(
"
SELECT  `Page Key`,`Product Web State`,`Product Web Configuration`,`Webpage State`,`Page State`,`Product Store Key`,`Product Code` FROM `Product Dimension` LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`) WHERE `Product Web State`='Offline'  AND `Webpage State`='Online';
"
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        print_r($row);
        print "Unpublish\n";
        $webpage = new Page($row['Page Key']);
        $webpage->update_state('Offline');

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

//'For Sale','Out of Stock','Discontinued','Offline'

$sql = sprintf(
    "
SELECT  `Page Key`,`Product Web State`,`Product Web Configuration`,`Webpage State`,`Page State`,`Product Store Key`,`Product Code` FROM `Product Dimension` LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`) WHERE `Product Web State`!='Offline'  AND `Webpage State`='Offline';"

);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $webpage = new Page($row['Page Key']);
        print "publish  \n";
        print_r($row);
        $webpage->update_state('Online');

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

?>
