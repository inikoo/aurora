<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Mon 15 April  2019 19:05:07 MYT, Kuala Lumpur, Ma;ysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


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

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

ini_set('memory_limit', '10000M');
$website_key = 1;

$where = " and `Page Key` <=35000   ";
$where = " and `Page Key` >35000   and   `Page Key` <=65000  ";

//$where = " and `Page Key` >65000   and   `Page Key` <=72000  ";
//$where = " and `Page Key` >72000    ";




$print_est = true;

$sql = sprintf("select count(*) as num FROM `Page Store Dimension` WHERE `Webpage Template Filename`='product' and `Webpage Website Key`=%d %s", $website_key,$where);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


//print $total."\n";
//exit;

$lap_time0 = date('U');
$contador  = 0;

$sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Webpage Website Key` FROM `Page Store Dimension` WHERE `Webpage Template Filename`="product"  and  `Webpage Website Key`=%d %s  ', $website_key,$where);

if ($result3 = $db->query($sql)) {
    foreach ($result3 as $row3) {


        $webpage = get_object('Webpage', $row3['Page Key']);
        print $webpage->get('Webpage Code')."\n";

        $content = '';

        $_content_data = $webpage->get('Content Data');


        if (isset($_content_data['old_data'])) {
            $content_data = $_content_data['old_data'];
        } else {
            $content_data = $_content_data;
        }


        //print_r($content_data);;


        if (isset($content_data['description_block']['content'])) {

            $content = $content_data['description_block']['content'];

            if ($content == '<div class="description"></div>') {
                $content = '';
            }

            if (preg_match('/\<div class\=\"description\"\>(.+)\<\/div\>/s', $content, $match)) {
                $content = $match[1];
            }


            $content = preg_replace('/\<p\>\<br\>\<\/p\>/', '', $content);
            $content = preg_replace('/\<p style\=\"text-align: left;\"\><br\>\<\/p\>/', '', $content);
            $content = preg_replace('/\<p style\=\"\"\>\<br\>\<\/p\>/', '', $content);
            $content = preg_replace('/\<span>\&nbsp\;\<\/span\>/', '', $content);

            $content = preg_replace('/line-height\s*\:\s*[0-9.]+px\;/', '', $content);
            $content = preg_replace('/line-height\s*\:\s*[0-9.]+\;/', '', $content);

            $content = preg_replace('/font-size\s*\:\s*[0-9.]+px\;/', '', $content);
            $content = preg_replace('/size=\s*[0-9.]+px\;/', '', $content);


            $content = preg_replace('/\<br\>\s*$/', '', $content);


            $content = str_replace("font-family: 'Open Sans', Helvetica, Arial, sans-serif;", '', $content);
            $content = str_replace("<br><br>", '<br>', $content);

            $content = str_replace("<p><br>", '<p>', $content);
            $content = str_replace("font-family: Arial, sans-serif;", '', $content);
            $content = str_replace('class="Normal-C"', '', $content);

            $content = str_replace("font-family: Open Sans, Helvetica, Arial, sans-serif;", '', $content);

            $content = str_replace("font-family: Tahoma, Geneva, sans-serif;", '', $content);
            $content = str_replace("font-family: Arial, Helvetica, sans-serif;", '', $content);
            $content = str_replace("font-family: Ubuntu, Helvetica, Arial, sans-serif;", '', $content);
            $content = str_replace("font-family: inherit;", '', $content);
            $content = str_replace("font-family: 'Lucida Grande', 'Lucida Sans Unicode', Verdana, Arial, sans-serif;", '', $content);

            $content = str_replace("font-family: inherit;", '', $content);

            $content = str_replace('face="verdana"', '', $content);
            $content = str_replace("font-family: inherit;", '', $content);


            //  $content=str_replace('style=""','',$content);

            // if(preg_match('/font/',$content)){
            //  print $webpage->id."\n";
            // }


        }


        // specific to AW
        switch ($row3['Webpage Website Key']) {

            case 5:
                $title = 'Voir aussi';

                break;
            case 9:
                $title = 'Guarda anche';

                break;
            case 7:
                $title = 'Zobacz także';

                break;


            case 3:

                $title = 'Siehe auch';

                break;
            default:
                $title = 'See also';
        }


        $product = get_object('Public_Product', $webpage->get('Webpage Scope Key'));


        $image_data = $product->get('Image Data2');


        $image_gallery = array();


        foreach ($product->get_image_gallery2() as $image_item) {
            if ($image_item['key'] != $image_data['key']) {
                $image_gallery[] = $image_item;
            }
        }


        $new_content_data = array(
            'blocks'   => array(
                array(
                    'type'            => 'product',
                    'label'           => _('Product'),
                    'icon'            => 'fa-cube',
                    'show'            => 1,
                    'top_margin'      => 20,
                    'bottom_margin'   => 30,
                    'text'            => $content,
                    'show_properties' => true,

                    'image'        => array(
                        'key'           => $image_data['key'],
                        'src'           => $image_data['src'],
                        'caption'       => $image_data['caption'],
                        'width'         => $image_data['width'],
                        'height'        => $image_data['height'],
                        'image_website' => $image_data['image_website']

                    ),
                    'other_images' => $image_gallery


                ),
                array(
                    'type'              => 'see_also',
                    'auto'              => true,
                    'auto_scope'        => 'webpage',
                    'auto_items'        => 5,
                    'auto_last_updated' => '',
                    'label'             => _('See also'),
                    'icon'              => 'fa-link',
                    'show'              => 1,
                    'top_margin'        => 0,
                    'bottom_margin'     => 40,
                    'item_headers'      => false,
                    'items'             => array(),
                    'sort'              => 'Manual',
                    'title'             => $title,
                    'show_title'        => true
                )
            ),
            'old_data' => $_content_data
        );


        $x = json_encode($new_content_data);
        if ($x == '') {
            print_r($row3);

            print_r($webpage->id);

            continue;
        }


        $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="products2" WHERE `Page Key`=%d ', $webpage->id);

        $db->exec($sql);

        $webpage->update(
            array(
                'Page Store Content Data' => json_encode($new_content_data)
            ), 'no_history'
        );

        $webpage->reindex_items();
        $webpage->refill_see_also();
        $webpage->update_navigation();


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
                )."m  ($contador/$total) \r";
        }

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}



