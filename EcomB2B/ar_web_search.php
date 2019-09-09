<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 21:01:09 CEST, Trnava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;

require_once 'common.php';
require_once 'utils/ar_web_common.php';
require_once 'utils/get_addressing.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'search':
        $data = prepare_values(
            $_REQUEST, array(
                         'query' => array('type' => 'string')
                     )
        );


        search($db, $website, $data, $smarty, $template_suffix, $order_key);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

function search($db, $website, $data, $smarty, $template_suffix, $order_key) {


    $theme   = 'theme_1';
    $results = process_search($data['query'], $db, $website, $order_key);


    $smarty->assign('results', $results['results']);


    $response = array(
        'state'     => 200,
        'results'   => $smarty->fetch("$theme/_search_results.$theme.EcomB2B".$template_suffix.'.tpl'),
        'analytics' => get_search_results_analytics_data($results['results'])
    );
    echo json_encode($response);

}


function get_search_results_analytics_data($results) {


    $analytics_data = array();
    foreach ($results as $result) {


        if ($result['scope'] == 'Product') {
            $analytics_data[] = array(
                'id'       => $result['code'],
                'name'     => $result['name'],
                'category' => $result['family_code'],
                'price'    => $result['raw_price']
            );
        }
    }

    return $analytics_data;
}

function process_search($q, $db, $website, $order_key) {


    $candidates  = array();
    $page_scores = array();
    if ($q == '') {

        return array(
            'number_results' => count($candidates),
            'results'        => $candidates,
            'did_you_mean'   => ''
        );
    }


    $array_q            = preg_split('/\s+/', $q);
    $number_query_words = count($array_q);


    $found_family  = false;
    $found_product = false;


    $score_match_direct_product_code = 5.5;
    $score_match_product_code        = 3.5;
    $score_match_family_code         = 5.5;
    $score_boolean_factor            = 6;

    foreach ($array_q as $_q) {

        $_q = addslashes($_q);

        $code_q = $_q;

        if (strlen($_q) > 2) {
            $code_q .= '%';
        }


        if ($order_key) {
            $ordered = sprintf('(SELECT `Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Product ID`=P.`Product ID`) as ordered,', $order_key);
        } else {
            $ordered = ' "" as ordered,';

        }


        $sql = sprintf(
            'SELECT  %s `Product Currency`,`Category Code`,`Product Price`,`Product ID`,`Webpage Code`,`Page Key` ,`Product Web State`,`Product Main Image Key`,`Product Web State`,`Webpage URL`,`Webpage Name`,`Product Name`,`Product Code`,`Webpage Meta Description`,`Product Units Per Case`
            FROM  `Product Dimension` P  
            LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=P.`Product Webpage Key`)
            left join  `Category Dimension`  on (`Category Key`=`Product Family Category Key`)

		 WHERE `Webpage Website Key`=%d AND `Product Code` LIKE %s  AND  `Webpage State`="Online" AND `Product Status` IN ("Active","Discontinuing")   ', $ordered, $website->id, prepare_mysql($code_q)
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                if ($row['Product Main Image Key'] > 0) {
                    $image = sprintf('wi.php?s=320x280&id=%d', $row['Product Main Image Key']);

                    $image_mobile = get_image_mobile($row['Product Main Image Key']);


                } else {
                    $image        = 'art/nopic.png';
                    $image_mobile = 'art/nopic.png';

                }


                if ($row['Product Web State'] == 'Out of Stock') {
                    $score_match_product_code = 0.7 * $score_match_product_code;
                }

                $page_scores[$row['Page Key']]               = $score_match_product_code;
                $candidates[$row['Page Key']]                = array();
                $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];

                $candidates[$row['Page Key']]['scope']        = 'Product';
                $candidates[$row['Page Key']]['image']        = $image;
                $candidates[$row['Page Key']]['image_mobile'] = $image_mobile;
                $candidates[$row['Page Key']]['score']        = $score_match_product_code;
                $candidates[$row['Page Key']]['url']          = '/'.strtolower($row['Webpage Code']);
                $candidates[$row['Page Key']]['title']        = $row['Webpage Name'];
                $candidates[$row['Page Key']]['key']          = $row['Product ID'];
                $candidates[$row['Page Key']]['ordered']      = $row['ordered'];
                $candidates[$row['Page Key']]['family_code']  = $row['Category Code'];
                $candidates[$row['Page Key']]['raw_price']    = $row['Product Price'];

                $candidates[$row['Page Key']]['web_state'] = $row['Product Web State'];
                // todo a proper way to do this class
                $candidates[$row['Page Key']]['out_of_stock_class'] = 'out_of_stock';
                $candidates[$row['Page Key']]['out_of_stock_label'] = _('Out of stock');


                $candidates[$row['Page Key']]['code']  = $row['Product Code'];
                $candidates[$row['Page Key']]['name']  = ($row['Product Units Per Case'] > 1 ? $row['Product Units Per Case'].'x ' : '').$row['Product Name'];
                $candidates[$row['Page Key']]['price'] = money($row['Product Price'], $row['Product Currency']);

                $candidates[$row['Page Key']]['description']       = $row['Webpage Meta Description'];
                $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf(
            'SELECT  `Webpage Code`, `Page Key` ,`Category Main Image Key`,`Webpage URL`,`Webpage Name`,`Category Label`,`Category Code`,`Webpage Meta Description`,`Category Label`
		     FROM   `Product Category Dimension` PC LEFT JOIN    `Category Dimension` C    ON (PC.`Product Category Key`=C.`Category Key`)  LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=PC.`Product Category Webpage Key`)
            WHERE `Webpage Website Key`=%d AND `Category Code` LIKE %s  AND  `Webpage State`="Online"   ', $website->id, prepare_mysql($code_q)
        );
        //print $sql;
        // print "$sql\n";


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                //   print_r($row);

                if ($row['Category Main Image Key'] > 0) {
                    $image        = sprintf('wi.php?s=320x280&id=%d', $row['Category Main Image Key']);
                    $image_mobile = get_image_mobile($row['Category Main Image Key']);

                } else {
                    $image        = 'art/nopic.png';
                    $image_mobile = 'art/nopic.png';

                }


                if (array_key_exists($row['Page Key'], $candidates)) {
                    $candidates[$row['Page Key']]['score'] += $score_match_family_code;
                    $page_scores[$row['Page Key']]         += $score_match_family_code;
                } else {
                    $candidates[$row['Page Key']]                = array();
                    $candidates[$row['Page Key']]['scope']       = 'Category';
                    $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];

                    $candidates[$row['Page Key']]['image_mobile'] = $image_mobile;

                    $candidates[$row['Page Key']]['image'] = $image;
                    $candidates[$row['Page Key']]['score'] = $score_match_family_code;
                    $page_scores[$row['Page Key']]         = $score_match_family_code;
                    $candidates[$row['Page Key']]['url']   = '/'.strtolower($row['Webpage Code']);
                    $candidates[$row['Page Key']]['title'] = $row['Webpage Name'];
                    $candidates[$row['Page Key']]['key']   = '';

                    $candidates[$row['Page Key']]['code'] = $row['Category Code'];
                    $candidates[$row['Page Key']]['name'] = $row['Category Label'];

                    $candidates[$row['Page Key']]['description']       = $row['Webpage Meta Description'];
                    $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Category Code'].'</span> '.$row['Category Label'];

                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        if (strlen($_q) > 2) {

            if ($order_key) {
                $ordered = sprintf('(SELECT `Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Product ID`=P.`Product ID`) as ordered,', $order_key);
            } else {
                $ordered = ' "" as ordered,';

            }

            $sql = sprintf(
                'SELECT %s `Product Currency`,`Category Code`,`Product Price`,`Product ID`,`Product Web State`, `Webpage Code`,`Page Key` ,`Product Main Image Key`,`Product Web State`,`Webpage URL`,`Webpage Name`,`Product Name`,`Product Code`,`Webpage Meta Description`,`Product Units Per Case`
     FROM  `Product Dimension` P  LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=P.`Product Webpage Key`)  left join  `Category Dimension`  on (`Category Key`=`Product Family Category Key`)


		 WHERE `Webpage Website Key`=%d AND `Product Name`  REGEXP \'[[:<:]]%s\'   AND  `Webpage State`="Online"   AND `Product Status` IN ("Active","Discontinuing")  ', $ordered, $website->id, $_q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Product Main Image Key'] > 0) {
                        $image        = sprintf('wi.php?s=320x280&id=%d', $row['Product Main Image Key']);
                        $image_mobile = get_image_mobile($row['Product Main Image Key']);

                    } else {
                        $image        = 'art/nopic.png';
                        $image_mobile = 'art/nopic.png';

                    }

                    if ($row['Product Web State'] == 'Out of Stock') {
                        $score_match_product_code = 0.7 * $score_match_product_code;
                    }


                    $page_scores[$row['Page Key']]                = $score_match_product_code;
                    $candidates[$row['Page Key']]                 = array();
                    $candidates[$row['Page Key']]['webpage_key']  = $row['Page Key'];
                    $candidates[$row['Page Key']]['image_mobile'] = $image_mobile;

                    $candidates[$row['Page Key']]['scope']       = 'Product';
                    $candidates[$row['Page Key']]['image']       = $image;
                    $candidates[$row['Page Key']]['score']       = $score_match_product_code;
                    $candidates[$row['Page Key']]['url']         = '/'.strtolower($row['Webpage Code']);
                    $candidates[$row['Page Key']]['key']         = $row['Product ID'];
                    $candidates[$row['Page Key']]['code']        = $row['Product Code'];
                    $candidates[$row['Page Key']]['name']        = ($row['Product Units Per Case'] > 1 ? $row['Product Units Per Case'].'x ' : '').$row['Product Name'];
                    $candidates[$row['Page Key']]['price']       = money($row['Product Price'], $row['Product Currency']);
                    $candidates[$row['Page Key']]['ordered']     = $row['ordered'];
                    $candidates[$row['Page Key']]['family_code'] = $row['Category Code'];
                    $candidates[$row['Page Key']]['raw_price']   = $row['Product Price'];


                    $candidates[$row['Page Key']]['web_state'] = $row['Product Web State'];
                    // todo a proper way to do this class
                    $candidates[$row['Page Key']]['out_of_stock_class'] = 'out_of_stock';
                    $candidates[$row['Page Key']]['out_of_stock_label'] = _('Out of stock');


                    $candidates[$row['Page Key']]['title']             = $row['Webpage Name'];
                    $candidates[$row['Page Key']]['description']       = $row['Webpage Meta Description'];
                    $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                'SELECT   `Webpage Code`,`Page Key` ,`Category Main Image Key`,`Webpage URL`,`Webpage Name`,`Category Label`,`Category Code`,`Webpage Meta Description`
		     FROM   `Product Category Dimension` PC LEFT JOIN    `Category Dimension` C    ON (PC.`Product Category Key`=C.`Category Key`)  LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=PC.`Product Category Webpage Key`)
            WHERE `Webpage Website Key`=%d AND  `Category Label`  REGEXP \'[[:<:]]%s\'    AND  `Webpage State`="Online"   ', $website->id, $_q
            );
            //print $sql;
            //print "$sql\n";


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Category Main Image Key'] > 0) {
                        $image = sprintf('wi.php?s=320x280&id=%d', $row['Category Main Image Key']);

                        $image_mobile = get_image_mobile($row['Category Main Image Key']);


                    } else {
                        $image        = 'art/nopic.png';
                        $image_mobile = 'art/nopic.png';
                    }


                    if (array_key_exists($row['Page Key'], $candidates)) {
                        $candidates[$row['Page Key']]['score'] += $score_match_family_code;
                        $page_scores[$row['Page Key']]         += $score_match_family_code;
                    } else {
                        $candidates[$row['Page Key']]                 = array();
                        $candidates[$row['Page Key']]['scope']        = 'Category';
                        $candidates[$row['Page Key']]['webpage_key']  = $row['Page Key'];
                        $candidates[$row['Page Key']]['image_mobile'] = $image_mobile;


                        $candidates[$row['Page Key']]['image'] = $image;
                        $candidates[$row['Page Key']]['score'] = $score_match_family_code;
                        $page_scores[$row['Page Key']]         = $score_match_family_code;
                        $candidates[$row['Page Key']]['url']   = '/'.strtolower($row['Webpage Code']);
                        $candidates[$row['Page Key']]['title'] = $row['Webpage Name'];
                        $candidates[$row['Page Key']]['code']  = $row['Category Code'];
                        $candidates[$row['Page Key']]['key']   = '';

                        $candidates[$row['Page Key']]['name']              = $row['Category Label'];
                        $candidates[$row['Page Key']]['description']       = $row['Webpage Meta Description'];
                        $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Category Code'].'</span> '.$row['Category Label'];

                    }
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


        }


    }


    array_multisort($page_scores, SORT_NUMERIC, SORT_DESC, $candidates);


    $number_results = count($candidates);

    $did_you_mean      = '';
    $alternative_found = false;


    if (!$alternative_found) {
        $did_you_mean = '';
    }


    return array(
        'number_results' => $number_results,
        'results'        => $candidates,
        'did_you_mean'   => $did_you_mean
    );

}

function get_image_mobile($image_key) {


    return 'wi?id='.$image_key.'&s=120x120';


}


