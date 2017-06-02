<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2016 at 18:36:54 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

if(function_exists('mysql_connect')) {

    $default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
    if (!$default_DB_link) {
        print "Error can not connect with database server\n";
    }
    $db_selected = mysql_select_db($dns_db, $default_DB_link);
    if (!$db_selected) {
        print "Error can not access the database\n";
        exit;
    }
    mysql_set_charset('utf8');
    mysql_query("SET time_zone='+0:00'");

}

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Site.php';
require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';

require_once 'conf/footer_data.php';
require_once 'conf/header_data.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

/*
$sql = sprintf('SELECT `Product Category Index Category Key` FROM `Product Category Index`  GROUP BY `Product Category Index Category Key`  ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = new Category($row['Product Category Index Category Key']);
        $category->get_webpage();



        if ($category->webpage->id) {
            $sql = sprintf(
                'UPDATE `Product Category Index`  SET `Product Category Index Website Key`=%d WHERE  `Product Category Index Category Key`=%d ', $category->webpage->id,
                $row['Product Category Index Category Key']

            );

            $db->exec($sql);

        }
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}
*/


//migrate_website($db);

//create_webpage_types($db);
//normalize_webpage_scopes($db);

//migrate_product_pages($db);


add_headers_and_footers($db);

delete_system_webpages($db);

add_system_webpages($db);

//========
//set_scope($db);

function delete_system_webpages($db){

    $sql=sprintf("select `Page Key` from `Page Store Dimension` where `Webpage Code` like '%%.sys' ");
    if ($result=$db->query($sql)) {
    		foreach ($result as $row) {
                $webpage = new Page($row['Page Key']);
                $webpage->delete(false);
            }
    }else {
    		print_r($error_info=$db->errorInfo());
    		print "$sql\n";
    		exit;
    }


}

function add_system_webpages($db) {

    $sql = sprintf('SELECT `Website Key` FROM `Website Dimension`');
    print_r($sql);

    if ($result = $db->query($sql)) {


        foreach ($result as $row) {

            $website = new Website($row['Website Key']);

            $db->exec('truncate ``');

            include_once ('conf/webpage_types.php');
            foreach ($webpage_types as $webpage_type) {
                $sql = sprintf(
                    'INSERT INTO `Webpage Type Dimension` (`Webpage Type Website Key`,`Webpage Type Code`) VALUES (%d,%s) ', $website->id, prepare_mysql($webpage_type['code'])
                );
                $db->exec($sql);
            }



            //print_r($website);

            include_once 'conf/website_system_webpages.php';
            foreach (website_system_webpages_config($website->get('Website Type')) as $website_system_webpages) {

               print_r($website_system_webpages);

                $website->create_system_webpage($website_system_webpages);
            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function add_headers_and_footers($db) {

    global $editor;

    $sql = sprintf('truncate `Website Footer Dimension`');
    $db->exec($sql);

    $sql = sprintf('truncate `Website Header Dimension`');
    $db->exec($sql);

    $sql = sprintf('truncate `Website Image Dimension`');
    $db->exec($sql);


    $sql = sprintf('UPDATE `Website Dimension` SET `Website Footer Key`=NULL ,`Website Header Key`=NULL ');
    $db->exec($sql);

    $sql = sprintf('SELECT `Website Key` FROM `Website Dimension`');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $website = new Website($row['Website Key']);


            $footer_data = array(
                'Website Footer Code' => 'default',
                'Website Footer Data' => json_encode(get_default_footer_data(1)),
                'editor'              => $editor

            );
            $website->create_footer($footer_data);


            $logo_image_key = $website->add_image(
                array(
                    'Image Filename'                   => 'website.logo.png',
                    'Upload Data'                      => array(
                        'tmp_name' => 'conf/website.logo.png',
                        'type'     => 'png'
                    ),
                    'Image Subject Object Image Scope' => json_encode(
                        array(
                            'scope'     => 'website_logo',
                            'scope_key' => $website->id

                        )
                    )

                )
            );


            $_header_data                   = get_default_header_data(1);
            $_header_data['logo_image_key'] = $logo_image_key;
            $header_data                    = array(
                'Website Header Code' => 'default',
                'Website Header Data' => json_encode($_header_data),
                'editor'              => $editor

            );
            $website->create_header($header_data);


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function migrate_product_pages($db) {

    $sql = sprintf('SELECT `Page Key`,`Webpage Scope Key` FROM `Page Store Dimension` WHERE `Webpage Scope`="Product" AND (`Webpage Content Data`="" OR `Webpage Content Data` IS NULL )  ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = new Page($row['Page Key']);
            $webpage->update_version();

            $public_product = new Product('id', $row['Webpage Scope Key']);

            if (($webpage->id and $webpage->get('Content Data') == '')) {


                print $webpage->get('Code')."\n";
                $content_data = array(
                    'description_block' => array(
                        'class' => '',

                        'content' => sprintf('<div class="description">%s</div>', $public_product->get('Description'))


                    ),
                    'tabs'              => array()

                );

                $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');
                $webpage->publish();

            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}


function create_webpage_types($db) {

    include 'conf/webpage_types.php';


    $sql = sprintf('truncate `Website Type Dimension` ');
    $db->exec($sql);


    $sql = sprintf('SELECT * FROM `Website Dimension` ');
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            foreach ($webpage_types as $webpage_type) {


                $sql = sprintf(
                    'INSERT INTO `Webpage Type Dimension` (`Webpage Type Website Key`,`Webpage Type Code`) VALUES (%d,%s) ', $row['Website Key'], prepare_mysql($webpage_type['code'])
                );

                $db->exec($sql);

            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function normalize_webpage_scopes($db) {


    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` ORDER BY `Page Key` DESC ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $page = new Page($row['Page Key']);


            if ($page->data['Page Store Section Type'] == 'Product') {
                $type = 'Prod';


            } elseif ($page->data['Page Store Section'] == 'Department Catalogue') {
                $type = 'Cats';

            } elseif ($page->data['Page Store Section'] == 'Family Catalogue') {

                $type = 'Prods';

            } elseif ($page->data['Page Store Section'] == 'Information' or $page->data['Page Store Section'] == 'Front Page Store') {
                $type = 'Info';

            } else {
                print $page->data['Page Store Section']."\n";
                $type = 'Sys';
            }


            $webpage_type = new Webpage_Type('website_code', $page->get('Page Site Key'), $type);


            $page->update(
                array(
                    'Webpage Website Key' => $page->get('Page Site Key'),
                    'Webpage Store Key'   => $page->get('Page Store Key'),
                    'Webpage State'       => $page->get('Page State'),
                    'Webpage Type Key'    => $webpage_type->id,
                ), 'no_history'
            );


            $page->update(
                array(
                    'Webpage Code' => $page->get('Page Code'),
                ), 'no_history'
            );

            $page->update_version();

            print $page->id."\r";


        }
    }

    $sql = sprintf('SELECT `Webpage Type Key` FROM `Webpage Type Dimension` ');
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage_type = new Webpage_Type($row['Webpage Type Key']);
            $webpage_type->update_number_webpages();
            print $webpage_type->id."\r";
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE `Page Key`=73 ');
    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = new Page($row['Page Key']);
            $store   = new Store($row['Page Store Key']);


            //======
            $scope = false;

            if ($webpage->data['Page Store Section'] == 'Product Description') {
                include_once('class.Public_Product.php');
                $scope       = new Public_Product($webpage->data['Page Parent Key']);
                $scope_found = 'Product';


            } elseif ($webpage->data['Page Store Section'] == 'Category') {
                include_once('class.Public_Category.php');

                $scope       = new Public_Category($webpage->data['Page Parent Key']);
                $scope_found = 'Category';

            } elseif ($webpage->data['Page Store Section'] == 'Family Catalogue') {

                include_once('class.Store.php');

                include_once('class.Public_Category.php');
                $store    = new Store($webpage->get('Page Store Key'));
                $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $webpage->get('Code'));
                if ($category->id) {
                    $scope       = $category;
                    $scope_found = 'Category';

                } else {

                    $sql = sprintf('SELECT `Product Family Code`  FROM `Product Family Dimension` WHERE `Product Family Key`=%d ', $webpage->data['Page Parent Key']);


                    if ($result2 = $db->query($sql)) {
                        if ($row2 = $result2->fetch()) {

                            $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);
                            if ($category->id) {
                                $scope       = $category;
                                $scope_found = 'Category';

                            } else {
                                print 'F '.$webpage->get('Code').' '.$webpage->data['Page Parent Code'].' '."$sql\n";

                            }

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


            } elseif ($webpage->data['Page Store Section'] == 'Department Catalogue') {

                include_once('class.Store.php');

                include_once('class.Public_Category.php');
                $store    = new Store($webpage->get('Page Store Key'));
                $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $webpage->get('Code'));
                if ($category->id) {
                    $scope       = $category;
                    $scope_found = 'Category';

                } else {

                    $sql = sprintf('SELECT `Product Department Code`  FROM `Product Department Dimension` WHERE `Product Department Key`=%d ', $webpage->data['Page Parent Key']);


                    if ($result2 = $db->query($sql)) {
                        if ($row2 = $result2->fetch()) {

                            $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $row2['Product Department Code']);
                            if ($category->id) {
                                $scope       = $category;
                                $scope_found = 'Category';

                            } else {
                                print 'D '.$webpage->get('Code').' '.$webpage->data['Page Parent Code'].' '."$sql\n";

                            }

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


            }


            //===


            if ($scope) {


                if ($scope->get_object_name() == 'Product') {
                    $webpage->update(
                        array(
                            'Webpage Scope'     => 'Product',
                            'Webpage Scope Key' => $scope->id
                        ), 'no_history'
                    );

                } elseif ($scope->get_object_name() == 'Category') {


                    $category = new Category($scope->id);

                    if ($category->get('Category Subject') == 'Product') {

                        $webpage->update(
                            array(
                                'Webpage Scope'     => 'Category Products',
                                'Webpage Scope Key' => $category->id
                            ), 'no_history'
                        );


                    } elseif ($category->get('Category Subject') == 'Category') {
                        //print $category->get('Category Subject');

                        $webpage->update(
                            array(
                                'Webpage Scope'     => 'Category Categories',
                                'Webpage Scope Key' => $scope->id
                            ), 'no_history'
                        );


                        if ($category->get('Category Root Key') == $store->get('Store Family Category Key')) {

                            $webpage->update(
                                array(
                                    'Webpage Scope Metadata' => 'Family',
                                ), 'no_history'
                            );
                        } elseif ($category->get('Category Root Key') == $store->get('Store Department Category Key')) {

                            $webpage->update(
                                array(
                                    'Webpage Scope Metadata' => 'Department',
                                ), 'no_history'
                            );
                        }

                    }


                }

            } else {

                if ($row['Page Store Section'] == 'Family Catalogue') {
                    //  print "Family ".$row['Page Parent Code']."\n";


                }
                if ($row['Page Store Section'] == 'Department Catalogue') {
                    //print "Dept  ".$row['Page Store Key']." ".$row['Page Parent Code']."\n";

                }

            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}


function set_scope($db) {

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE `Webpage Scope Key` IS NULL OR  `Webpage Scope Key`=0 ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = new Page($row['Page Key']);
            $store   = new Store($row['Page Store Key']);


            //======
            $scope = false;

            if ($webpage->data['Page Store Section'] == 'Product Description') {
                include_once('class.Public_Product.php');
                $scope       = new Public_Product($webpage->data['Page Parent Key']);
                $scope_found = 'Product';


            } elseif ($webpage->data['Page Store Section'] == 'Category') {
                include_once('class.Public_Category.php');

                $scope       = new Public_Category($webpage->data['Page Parent Key']);
                $scope_found = 'Category';

            } elseif ($webpage->data['Page Store Section'] == 'Family Catalogue') {

                include_once('class.Store.php');

                include_once('class.Public_Category.php');
                $store    = new Store($webpage->get('Page Store Key'));
                $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $webpage->get('Code'));
                if ($category->id) {
                    $scope       = $category;
                    $scope_found = 'Category';

                } else {

                    $sql = sprintf('SELECT `Product Family Code`  FROM `Product Family Dimension` WHERE `Product Family Key`=%d ', $webpage->data['Page Parent Key']);


                    if ($result2 = $db->query($sql)) {
                        if ($row2 = $result2->fetch()) {

                            $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);
                            if ($category->id) {
                                $scope       = $category;
                                $scope_found = 'Category';

                            } else {
                                print 'F '.$webpage->get('Code').' '.$webpage->data['Page Parent Code'].' '."$sql\n";

                            }

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


            } elseif ($webpage->data['Page Store Section'] == 'Department Catalogue') {

                include_once('class.Store.php');

                include_once('class.Public_Category.php');
                $store    = new Store($webpage->get('Page Store Key'));
                $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $webpage->get('Code'));
                if ($category->id) {
                    $scope       = $category;
                    $scope_found = 'Category';

                } else {

                    $sql = sprintf('SELECT `Product Department Code`  FROM `Product Department Dimension` WHERE `Product Department Key`=%d ', $webpage->data['Page Parent Key']);


                    if ($result2 = $db->query($sql)) {
                        if ($row2 = $result2->fetch()) {

                            $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $row2['Product Department Code']);
                            if ($category->id) {
                                $scope       = $category;
                                $scope_found = 'Category';

                            } else {
                                print 'D '.$webpage->get('Code').' '.$webpage->data['Page Parent Code'].' '."$sql\n";

                            }

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


            }


            //===


            if ($scope) {


                if ($scope->get_object_name() == 'Product') {
                    $webpage->update(
                        array(
                            'Webpage Scope'     => 'Product',
                            'Webpage Scope Key' => $scope->id
                        ), 'no_history'
                    );

                } elseif ($scope->get_object_name() == 'Category') {


                    $category = new Category($scope->id);

                    if ($category->get('Category Subject') == 'Product') {

                        $webpage->update(
                            array(
                                'Webpage Scope'     => 'Category Products',
                                'Webpage Scope Key' => $category->id
                            ), 'no_history'
                        );


                    } elseif ($category->get('Category Subject') == 'Category') {
                        //print $category->get('Category Subject');

                        $webpage->update(
                            array(
                                'Webpage Scope'     => 'Category Categories',
                                'Webpage Scope Key' => $scope->id
                            ), 'no_history'
                        );


                        if ($category->get('Category Root Key') == $store->get('Store Family Category Key')) {

                            $webpage->update(
                                array(
                                    'Webpage Scope Metadata' => 'Family',
                                ), 'no_history'
                            );
                        } elseif ($category->get('Category Root Key') == $store->get('Store Department Category Key')) {

                            $webpage->update(
                                array(
                                    'Webpage Scope Metadata' => 'Department',
                                ), 'no_history'
                            );
                        }

                    }


                }

            } else {

                if ($row['Page Store Section'] == 'Family Catalogue') {
                    //  print "Family ".$row['Page Parent Code']."\n";


                }
                if ($row['Page Store Section'] == 'Department Catalogue') {
                    //print "Dept  ".$row['Page Store Key']." ".$row['Page Parent Code']."\n";

                }

            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}


//$sql = 'truncate `Template Scope Dimension`;truncate `Template Dimension`;truncate `Website Dimension`;truncate `Website Node Dimension`;truncate `Webpage Dimension`; truncate `Webpage Version Block Bridge`;truncate `Webpage Version Dimension`';
//$db->exec($sql);

function migrate_website($db) {

    global $editor;


    $sql = sprintf('truncate `Website Dimension` ');
    $db->exec($sql);

    $sql = sprintf('truncate `Website Data` ');
    $db->exec($sql);

    $sql = sprintf('SELECT * FROM `Site Dimension` WHERE `Site Key`=1 ');

    $sql = sprintf('SELECT * FROM `Site Dimension` ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $site          = new Site($row['Site Key']);
            $store         = new Store($site->get('Site Store Key'));
            $store->editor = $store;

            $website_data = array(
                'Website Key'    => $site->id,
                'Website Code'   => $site->get('Site Code'),
                'Website Name'   => $site->get('Site Name'),
                'Website Type'   => ($site->get('Site Code') == 'DS' ? 'EcomDS' : 'EcomB2B'),
                'Website URL'    => $site->get('Site URL'),
                'Website Locale' => $site->get('Site Locale'),
                'Website Name'   => $site->get('Site Name'),
                'editor'         => $editor
            );


            // print_r($website_data);

            $website = $store->create_website($website_data);

            //   print_r($website);

            if ($site->get('Site From') != '') {
                $website->update(
                    array(
                        'Website Valid From' => $site->get('Site From'),
                        'no_history'
                    )
                );
            }


        }
    }
}

//exit();


/*

$website->create_no_product_webnodes();
$website->create_product_webnodes();


exit;


//	$website_node[$website_key]['Insp']=$website_node[$website_key]['Home']->create_subnode(array('Website Node Code'=>'p.Insp', 'Website Node Name'=>_('Inspiration'), 'Website Node Locked'=>'No', 'Website Node Type'=>'Root'));


$node = $website_node[$website_key]['Home']->create_subnode(
    array(
        'Website Node Code'       => 'p.Cat',
        'Website Node Name'       => _('Catalogue'),
        'Website Node Locked'     => 'Yes',
        'Website Node Type'       => 'Root',
        'Website Node Icon'       => 'th',
        'Website Node Class'      => 'Categories',
        'Website Node Object'     => 'Category',
        'Website Node Object Key' => $store->get(
            'Store Department Category Key'
        )
    )
);


$page = new Webpage($node->get_webpage_key());
$page->update(
    array(
        'Webpage Properties' => json_encode(
            array('body_classes' => 'page-category layout-fullwidth')
        )
    ), 'no_history'
);

$settings = array(
    'title' => array(
        'edit'  => 'string',
        'id'    => 'title',
        'value' => _('Catalogue')
    ),
);
$page->append_block(
    array(
        'Webpage Block Template' => 'product_nodes',
        'Webpage Block Settings' => $settings
    )
);


$sql = sprintf(
    "SELECT `Page Key`,`Page Code`,`Page Store Title`,`Page Parent Key`,`Page State` FROM `Page Store Dimension` WHERE `Page Store Section Type`='Department' AND `Page Site Key`=%d", $website_key
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        print 'd.'.$row['Page Code']."\n";
        $dep_key  = $row['Page Parent Key'];
        $dep_node = $node->create_subnode(
            array(
                'Website Node Code'   => 'd.'.$row['Page Code'],
                'Website Node Name'   => $row['Page Store Title'],
                'Website Node Locked' => 'No',
                'Website Node Type'   => 'Branch',
                'Website Node Icon'   => 'th',
                'Website Node Status' => $row['Page State']
            )
        );

        $page = new Webpage($dep_node->get_webpage_key());
        $page->update(
            array(
                'Webpage Properties' => json_encode(
                    array('body_classes' => 'page-category layout-fullwidth')
                )
            ), 'no_history'
        );

        $settings = array(
            'title' => array(
                'edit'  => 'string',
                'id'    => 'title',
                'value' => $row['Page Store Title']
            ),
        );
        $page->append_block(
            array(
                'Webpage Block Template' => 'product_nodes',
                'Webpage Block Settings' => $settings
            )
        );


        $sql = sprintf(
            "SELECT `Page Key`,`Page Code`,`Page Store Title`,`Product Family Code`,`Page Parent Key`,`Page State` FROM `Page Store Dimension` LEFT JOIN `Product Family Dimension` ON (`Product Family Key`=`Page Parent Key`) WHERE `Page Store Section Type`='Family' AND `Product Family Main Department Key`=%d",
            $dep_key
        );
        //print "$sql\n";
        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {

                $fam_key = $row2['Page Parent Key'];
                print ' f.'.$row2['Page Code']." $fam_key  ".$row2['Product Family Code']."   \n";
                $fam_node = $dep_node->create_subnode(
                    array(
                        'Website Node Code'   => 'f.'.$row2['Page Code'],
                        'Website Node Name'   => $row2['Page Store Title'],
                        'Website Node Locked' => 'No',
                        'Website Node Type'   => 'Branch',
                        'Website Node Icon'   => 'pagelines',
                        'Website Node Status' => $row2['Page State']
                    )
                );

                $page = new Webpage($dep_node->get_webpage_key());
                $page->update(
                    array(
                        'Webpage Properties' => json_encode(
                            array('body_classes' => 'page-category layout-fullwidth')
                        )
                    ), 'no_history'
                );

                $settings = array(
                    'title' => array(
                        'edit'  => 'string',
                        'id'    => 'title',
                        'value' => $row2['Page Store Title']
                    ),
                );
                $page->append_block(
                    array(
                        'Webpage Block Template' => 'products',
                        'Webpage Block Settings' => $settings
                    )
                );


                $sql = sprintf(
                    "SELECT `Page Key`,`Page Code`,`Page Store Title`,`Page State`,`Product ID` FROM `Page Store Dimension` LEFT JOIN `Product Dimension` ON (`Product ID`=`Page Parent Key`) WHERE `Page Store Section Type`='Product' AND `Product Family Key`=%d",
                    $fam_key
                );
                //print "$sql\n";
                if ($result3 = $db->query($sql)) {
                    foreach ($result3 as $row3) {


                        $product_node = $fam_node->create_subnode(
                            array(
                                'Website Node Code'   => 'a.'.$row3['Page Code'],
                                'Website Node Name'   => $row3['Page Store Title'],
                                'Website Node Locked' => 'No',
                                'Website Node Type'   => 'Branch',
                                'Website Node Icon'   => 'leaf',
                                'Website Node Status' => $row3['Page State']
                            )
                        );
                        print '  a.'.$row3['Page Code']."\n";
                        $page = new Webpage(
                            $product_node->get_webpage_key()
                        );
                        $page->update(
                            array(
                                'Webpage Properties' => json_encode(
                                    array('body_classes' => 'page-product layout-fullwidth')
                                )
                            ), 'no_history'
                        );

                        $product = new Product($row3['Product ID']);

                        $settings = array(
                            'title'   => array(
                                'edit'  => 'string',
                                'id'    => 'title',
                                'value' => $row3['Page Store Title']
                            ),
                            'product' => array(
                                'code'    => $product->get('Code'),
                                'name'    => $product->get('Name'),
                                'summary' => array(
                                    array(
                                        'class' => '',
                                        'label' => 'Code',
                                        'value' => $product->get(
                                            'Code'
                                        ),
                                        'ref'
                                    ),
                                    array(
                                        'class' => '',
                                        'label' => 'Availability',
                                        'value' => 'In Stock',
                                        'ref'
                                    )
                                )
                            )


                        );
                        $page->append_block(
                            array(
                                'Webpage Block Template' => 'product',
                                'Webpage Block Settings' => $settings
                            )
                        );


                        exit;


                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


}

exit;

}

exit;


$sql = sprintf(
    'SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Store Data Dimension` PSD ON (PS.`Page Key`=PSD.`Page Key`) LEFT JOIN `Page Dimension` P ON (P.`Page Key`=PS.`Page Key`) LEFT JOIN `Site Dimension` S ON (S.`Site Key`=PS.`Page Site Key`) '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $sql = sprintf(
            "INSERT INTO `Webpage Dimension` (`Webpage Key`,`Webpage Website Key`,`Webpage Store Key`,`Webpage Parent Key`,`Webpage Code`,`Webpage Name`,`Webpage Status`,`Webpage From`) VALUES(%d,%d,%d,%d,%s,%s,%s,%s);",
            $row['Page Key'], $row['Page Site Key'], $row['Page Store Key'], $row['Page Parent Key'], prepare_mysql($row['Page Store Code']), prepare_mysql($row['Page Store Title']),
            prepare_mysql($row['Page Store State']), prepare_mysql($row['Page Store Creation Date'])

        );
        print "$sql\n";


        $db->exec($sql);
        $sql = "INSERT INTO `Website Data` (`Website Key`) VALUES(".$row['Site Key'].");";
        $db->exec($sql);

    }
}

*/

/*
 *
 *

stange code in crate functioon in class.Page.php

 //==== set scope


            if ($migration_hack) {


                include_once 'class.Store.php';
                $store = new Store($this->data['Page Store Key']);


                //======
                $scope = false;

                if ($this->data['Page Store Section'] == 'Product Description') {
                    include_once('class.Public_Product.php');
                    $scope       = new Public_Product($this->data['Page Parent Key']);
                    $scope_found = 'Product';


                } elseif ($this->data['Page Store Section'] == 'Category') {
                    include_once('class.Public_Category.php');

                    $scope       = new Public_Category($this->data['Page Parent Key']);
                    $scope_found = 'Category';

                } elseif ($this->data['Page Store Section'] == 'Family Catalogue') {


                    include_once('class.Public_Category.php');
                    $store    = new Store($this->get('Page Store Key'));
                    $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $this->get('Code'));
                    if ($category->id) {
                        $scope       = $category;
                        $scope_found = 'Category';

                    } else {

                        $sql = sprintf('SELECT `Product Family Code`  FROM `Product Family Dimension` WHERE `Product Family Key`=%d ', $this->data['Page Parent Key']);


                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {

                                $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);
                                if ($category->id) {
                                    $scope       = $category;
                                    $scope_found = 'Category';

                                } else {
                                    //    print 'F '.$this->get('Code').' '.$this->data['Page Parent Code'].' '."$sql\n";

                                }

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }

                    }


                } elseif ($this->data['Page Store Section'] == 'Department Catalogue') {

                    include_once('class.Store.php');

                    include_once('class.Public_Category.php');
                    $store    = new Store($this->get('Page Store Key'));
                    $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $this->get('Code'));
                    if ($category->id) {
                        $scope       = $category;
                        $scope_found = 'Category';

                    } else {

                        $sql = sprintf('SELECT `Product Department Code`  FROM `Product Department Dimension` WHERE `Product Department Key`=%d ', $this->data['Page Parent Key']);


                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {

                                $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $row2['Product Department Code']);
                                if ($category->id) {
                                    $scope       = $category;
                                    $scope_found = 'Category';

                                } else {
                                    //  print 'D '.$this->get('Code').' '.$this->data['Page Parent Code'].' '."$sql\n";

                                }

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }

                    }


                }


                //===
                require_once 'class.Webpage_Type.php';


                if ($scope) {


                    if ($scope->get_object_name() == 'Product') {
                        $this->update(
                            array(
                                'Webpage Scope'     => 'Product',
                                'Webpage Scope Key' => $scope->id
                            ), 'no_history'
                        );

                    } elseif ($scope->get_object_name() == 'Category') {


                        $category = new Category($scope->id);

                        if ($category->get('Category Subject') == 'Product') {

                            $this->update(
                                array(
                                    'Webpage Scope'     => 'Category Products',
                                    'Webpage Scope Key' => $category->id
                                ), 'no_history'
                            );


                        } elseif ($category->get('Category Subject') == 'Category') {
                            //print $category->get('Category Subject');

                            $this->update(
                                array(
                                    'Webpage Scope'     => 'Category Categories',
                                    'Webpage Scope Key' => $scope->id
                                ), 'no_history'
                            );


                            if ($category->get('Category Root Key') == $store->get('Store Family Category Key')) {

                                $this->update(
                                    array(
                                        'Webpage Scope Metadata' => 'Family',
                                    ), 'no_history'
                                );
                            } elseif ($category->get('Category Root Key') == $store->get('Store Department Category Key')) {

                                $this->update(
                                    array(
                                        'Webpage Scope Metadata' => 'Department',
                                    ), 'no_history'
                                );
                            }

                        }


                    }

                }


                if ($this->data['Page Store Section Type'] == 'Product') {
                    $type = 'Prod';


                } elseif ($this->data['Page Store Section'] == 'Department Catalogue') {
                    $type = 'Cats';

                } elseif ($this->data['Page Store Section'] == 'Family Catalogue') {

                    $type = 'Prods';

                } elseif ($this->data['Page Store Section'] == 'Information' or $this->data['Page Store Section'] == 'Front Page Store') {
                    $type = 'Info';

                } else {
                    print $this->data['Page Store Section']."\n";
                    $type = 'Sys';
                }


                $webpage_type = new Webpage_Type('website_code', $this->get('Page Site Key'), $type);


                $this->update(
                    array(
                        'Webpage Website Key' => $this->get('Page Site Key'),
                        'Webpage Store Key'   => $this->get('Page Store Key'),
                        'Webpage State'       => $this->get('Page State'),
                        'Webpage Type Key'    => $webpage_type->id,
                    ), 'no_history'
                );


                $this->update(
                    array(
                        'Webpage Code' => $this->get('Page Code'),
                    ), 'no_history'
                );

                $webpage_type->update_number_webpages();

                $this->update_version();
            }

            //===============




*
 *
 *
 */

?>
