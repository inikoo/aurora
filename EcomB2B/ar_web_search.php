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
        search($db, $website, $data, $smarty);
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

function search($db, $website, $data, $smarty) {


    $theme='theme_1';
    $results = process_search($data['query'], $db, $website);




    $smarty->assign('results',$results['results']);

    $response = array(
        'state'   => 200,
        'results' => $smarty->fetch("$theme/_search_results.$theme.EcomB2B.tpl")
    );
    echo json_encode($response);

}


function process_search($q, $db, $website) {


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


        $sql = sprintf(
            'SELECT  `Page Key` ,`Product Main Image Key`,`Product Web State`,`Webpage URL`,`Webpage Name`,`Product Name`,`Product Code`,`Webpage Meta Description`
     FROM  `Product Dimension` P  LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=P.`Product Webpage Key`)


		 WHERE `Webpage Website Key`=%d AND `Product Code` LIKE %s  AND  `Webpage State`="Online"   ', $website->id, prepare_mysql($code_q)
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Product Main Image Key'] > 0) {
                    $image = sprintf('image_root.php?size=small&id=%d', $row['Product Main Image Key']);
                } else {
                    $image = 'art/nopic.png';
                }

                if ($row['Product Web State'] == 'Out of Stock') {
                    $score_match_product_code = 0.7 * $score_match_product_code;
                }

                $page_scores[$row['Page Key']]               = $score_match_product_code;
                $candidates[$row['Page Key']]                = array();
                $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];

                $candidates[$row['Page Key']]['scope']             = 'Product';
                $candidates[$row['Page Key']]['image']             = $image;
                $candidates[$row['Page Key']]['score']             = $score_match_product_code;
                $candidates[$row['Page Key']]['url']               = $row['Webpage URL'];
                $candidates[$row['Page Key']]['title']             = $row['Webpage Name'];
                $candidates[$row['Page Key']]['code']             = $row['Product Code'];

                $candidates[$row['Page Key']]['description']       = $row['Webpage Meta Description'];
                $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            'SELECT   `Page Key` ,`Category Main Image Key`,`Webpage URL`,`Webpage Name`,`Category Label`,`Category Code`,`Webpage Meta Description`
		     FROM   `Product Category Dimension` PC LEFT JOIN    `Category Dimension` C    ON (PC.`Product Category Key`=C.`Category Key`)  LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=PC.`Product Category Webpage Key`)
            WHERE `Webpage Website Key`=%d AND `Category Code` LIKE %s  AND  `Webpage State`="Online"   ', $website->id, prepare_mysql($code_q)
        );
        //print $sql;
        // print "$sql\n";

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Category Main Image Key'] > 0) {
                    $image = sprintf('image_root.php?size=small&id=%d', $row['Category Main Image Key']);
                } else {
                    $image = 'art/nopic.png';
                }


                if (array_key_exists($row['Page Key'], $candidates)) {
                    $candidates[$row['Page Key']]['score'] += $score_match_family_code;
                    $page_scores[$row['Page Key']]         += $score_match_family_code;
                } else {
                    $candidates[$row['Page Key']]                = array();
                    $candidates[$row['Page Key']]['scope']       = 'Category';
                    $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];


                    $candidates[$row['Page Key']]['image']             = $image;
                    $candidates[$row['Page Key']]['score']             = $score_match_family_code;
                    $page_scores[$row['Page Key']]                     = $score_match_family_code;
                    $candidates[$row['Page Key']]['url']               = $row['Webpage URL'];
                    $candidates[$row['Page Key']]['title']             = $row['Webpage Name'];
                    $candidates[$row['Page Key']]['code']             = $row['Category Code'];

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


            $sql = sprintf(
                'SELECT  `Page Key` ,`Product Main Image Key`,`Product Web State`,`Webpage URL`,`Webpage Name`,`Product Name`,`Product Code`,`Webpage Meta Description`
     FROM  `Product Dimension` P  LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=P.`Product Webpage Key`)


		 WHERE `Webpage Website Key`=%d AND `Product Name`  REGEXP \'[[:<:]]%s\'   AND  `Webpage State`="Online"   ', $website->id, $_q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Product Main Image Key'] > 0) {
                        $image = sprintf('image_root.php?size=small&id=%d', $row['Product Main Image Key']);
                    } else {
                        $image = 'art/nopic.png';
                    }

                    if ($row['Product Web State'] == 'Out of Stock') {
                        $score_match_product_code = 0.7 * $score_match_product_code;
                    }

                    $page_scores[$row['Page Key']]               = $score_match_product_code;
                    $candidates[$row['Page Key']]                = array();
                    $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];

                    $candidates[$row['Page Key']]['scope']             = 'Product';
                    $candidates[$row['Page Key']]['image']             = $image;
                    $candidates[$row['Page Key']]['score']             = $score_match_product_code;
                    $candidates[$row['Page Key']]['url']               = $row['Webpage URL'];
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
                'SELECT   `Page Key` ,`Category Main Image Key`,`Webpage URL`,`Webpage Name`,`Category Label`,`Category Code`,`Webpage Meta Description`
		     FROM   `Product Category Dimension` PC LEFT JOIN    `Category Dimension` C    ON (PC.`Product Category Key`=C.`Category Key`)  LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=PC.`Product Category Webpage Key`)
            WHERE `Webpage Website Key`=%d AND  `Category Label`  REGEXP \'[[:<:]]%s\'    AND  `Webpage State`="Online"   ', $website->id, $_q
            );
            //print $sql;
            //print "$sql\n";

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Category Main Image Key'] > 0) {
                        $image = sprintf('image_root.php?size=small&id=%d', $row['Category Main Image Key']);
                    } else {
                        $image = 'art/nopic.png';
                    }


                    if (array_key_exists($row['Page Key'], $candidates)) {
                        $candidates[$row['Page Key']]['score'] += $score_match_family_code;
                        $page_scores[$row['Page Key']]         += $score_match_family_code;
                    } else {
                        $candidates[$row['Page Key']]                = array();
                        $candidates[$row['Page Key']]['scope']       = 'Category';
                        $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];


                        $candidates[$row['Page Key']]['image']             = $image;
                        $candidates[$row['Page Key']]['score']             = $score_match_family_code;
                        $page_scores[$row['Page Key']]                     = $score_match_family_code;
                        $candidates[$row['Page Key']]['url']               = $row['Webpage URL'];
                        $candidates[$row['Page Key']]['title']             = $row['Webpage Name'];
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


    if ($number_query_words > 1 and false) {

        $q_boolean = '';
        foreach ($array_q as $_q) {
            $q_boolean .= "+$_q ";
        }
        $q_boolean = _trim($q_boolean);
        $sql       = sprintf(
            'SELECT
		match (`First Search Full Text`) AGAINST  (%s IN BOOLEAN MODE) AS score,
		PP.`Page Key` ,`Page Store Description`,`Page Store Title`,`Page URL`,`Product Main Image Key`,PP.`Product ID`,`Product Code`,`Product Name`
		FROM `Page Product Dimension` PP
		LEFT JOIN `Search Full Text Dimension` SFTD ON (SFTD.`Subject Key`=PP.`Product ID` AND SFTD.`Subject`="Product")
		LEFT JOIN `Product Dimension` P ON (P.`Product ID`=PP.`Product ID`)
		LEFT JOIN `Page Dimension` PA ON (PA.`Page Key`=PP.`Page Key`)
		LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=PP.`Page Key`)
		WHERE  `Site Key`=%d  AND  `Product Web State`  IN ("For Sale", "Out of Stock")  AND match (`First Search Full Text`) AGAINST  (%s IN BOOLEAN MODE) GROUP BY  PP.`Page Key`   ',
            prepare_mysql($q_boolean), $website->id, prepare_mysql($q_boolean)
        );
        //print "$sql\n";

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Product Main Image Key'] > 0) {
                    $image = sprintf('image_root.php?size=small&id=%d', $row['Product Main Image Key']);
                } else {
                    $image = 'art/nopic.png';
                }

                if (array_key_exists($row['Page Key'], $candidates)) {

                    //print $row['Page Key']." ".$page_scores[$row['Page Key']]." +".$score_match_product_description;

                    $candidates[$row['Page Key']]['score'] += $row['score'] * $number_query_words * $score_boolean_factor;
                    $page_scores[$row['Page Key']]         += $row['score'] * $number_query_words * $score_boolean_factor;

                    //print $row['Product Name']." ".$page_scores[$row['Page Key']]."\n";

                } else {
                    $candidates[$row['Page Key']]                      = array();
                    $candidates[$row['Page Key']]['scope']             = 'Product';
                    $candidates[$row['Page Key']]['webpage_key']       = $row['Page Key'];
                    $candidates[$row['Page Key']]['image']             = $image;
                    $candidates[$row['Page Key']]['score']             = $row['score'] * $number_query_words * $score_boolean_factor;
                    $page_scores[$row['Page Key']]                     = $row['score'] * $number_query_words * $score_boolean_factor;
                    $candidates[$row['Page Key']]['url']               = 'http://'.$row['Page URL'];
                    $candidates[$row['Page Key']]['title']             = $row['Page Store Title'];
                    $candidates[$row['Page Key']]['description']       = $row['Page Store Description'];
                    $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];


                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    /*

    $sql = sprintf(
        'SELECT
		match (`First Search Full Text`) AGAINST  (%s IN BOOLEAN MODE) AS score,
		PP.`Page Key` ,`Page Store Description`,`Page Store Title`,`Page URL`,`Product Main Image Key`,PP.`Product ID`,`Product Code`,`Product Name`
		FROM `Page Product Dimension` PP
		LEFT JOIN `Search Full Text Dimension` SFTD ON (SFTD.`Subject Key`=PP.`Product ID` AND SFTD.`Subject`="Product")
		LEFT JOIN `Product Dimension` P ON (P.`Product ID`=PP.`Product ID`)
		LEFT JOIN `Page Dimension` PA ON (PA.`Page Key`=PP.`Page Key`)
		LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=PP.`Page Key`)
		WHERE  `Site Key`=%d  AND `Product Web State`  IN ("For Sale", "Out of Stock")  AND match (`First Search Full Text`) AGAINST  (%s IN BOOLEAN MODE) GROUP BY  PP.`Page Key`   ',
        prepare_mysql($q), $website->id, prepare_mysql($q)
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            if ($row['Product Main Image Key'] > 0) {
                $image = sprintf('image_root.php?size=small&id=%d', $row['Product Main Image Key']);
            } else {
                $image = 'art/nopic.png';
            }

            if (array_key_exists($row['Page Key'], $candidates)) {

                //print $row['Page Key']." ".$page_scores[$row['Page Key']]." +".$score_match_product_description;

                $candidates[$row['Page Key']]['score'] += $row['score'];
                $page_scores[$row['Page Key']]         += $row['score'];

                //print " ".$page_scores[$row['Page Key']]."\n";

            } else {
                $candidates[$row['Page Key']]                      = array();
                $candidates[$row['Page Key']]['scope']             = 'Product';
                $candidates[$row['Page Key']]['webpage_key']          = $row['Page Key'];
                $candidates[$row['Page Key']]['image']             = $image;
                $candidates[$row['Page Key']]['score']             = $row['score'];
                $page_scores[$row['Page Key']]                     = $row['score'];
                $candidates[$row['Page Key']]['url']               = 'http://'.$row['Page URL'];
                $candidates[$row['Page Key']]['title']             = $row['Page Store Title'];
                $candidates[$row['Page Key']]['description']       = $row['Page Store Description'];
                $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];


            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

*/
    /*

    foreach ($array_q as $_q) {


        $sql = sprintf(
            'SELECT  `Page Code`,`Page Key`, `Page Store Description`,`Page Store Title`,`Product Main Image Key`,`Product ID`,`Product Code`,`Product Name`
		FROM `Product Dimension` P   LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page PArent Key`=P.`Product ID` AND `Page Store Section`="Product Description")
		WHERE `Page Site Key`=%d AND `Product Code`=%s  AND  `Product Web State`  IN ("For Sale", "Out of Stock")  GROUP BY  `Page Key` ', $website->id, prepare_mysql($_q)
        );
        //print $sql;
        //print "$sql\n";

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Product Main Image Key'] > 0) {
                    $image = sprintf('image_root.php?size=small&id=%d', $row['Product Main Image Key']);
                } else {
                    $image = 'art/nopic.png';
                }


                if (array_key_exists($row['Page Key'], $candidates)) {
                    $candidates[$row['Page Key']]['score'] += $score_match_direct_product_code;
                    $page_scores[$row['Page Key']]         += $score_match_direct_product_code;
                } else {
                    $candidates[$row['Page Key']] = array();

                    $page_scores[$row['Page Key']]         = $score_match_direct_product_code;
                    $candidates[$row['Page Key']]['score'] = $score_match_direct_product_code;

                    $candidates[$row['Page Key']]['scope'] = 'Product';

                    $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];

                    $candidates[$row['Page Key']]['image']             = $image;
                    $candidates[$row['Page Key']]['url']               = ($website->data['Site SSL'] == 'Yes' ? 'https://' : 'https://').$website->data['Site URL'].'/'.strtolower($row['Page Code']);
                    $candidates[$row['Page Key']]['title']             = $row['Page Store Title'];
                    $candidates[$row['Page Key']]['description']       = $row['Page Store Description'];
                    $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];

                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


    }
*/

    /*

    $sql = sprintf(
        'SELECT   match (`First Search Full Text`) AGAINST  (%s) AS score,  PP.`Page Key` ,`Page Store Description`,`Page Store Title`,`Page URL`,`Product Family Main Image Key`,`Product Family Key`,`Product Family Code`,`Product Family Name`
	FROM `Page Product Dimension` PP
		LEFT JOIN `Search Full Text Dimension` SFTD ON (SFTD.`Subject Key`=PP.`Product ID` AND SFTD.`Subject`="Family")

	LEFT JOIN `Product Family Dimension` F ON (`Product Family Key`=PP.`Family Key`)
	LEFT JOIN `Page Dimension` PA ON (PA.`Page Key`=PP.`Page Key`)
	LEFT JOIN `Page Store Dimension` PAS ON (PAS.`Page Key`=PP.`Page Key`)
	WHERE `Page Site Key`=%d AND  `Product Family Web Products`=\'With Products For Sale\'  AND match (`First Search Full Text`) AGAINST  (%s)  GROUP BY  PP.`Page Key`   ',

        prepare_mysql($q), $website->id, prepare_mysql($q)
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            if ($row['Product Family Main Image Key'] > 0) {
                $image = sprintf('image_root.php?size=small&id=%d', $row['Product Family Main Image Key']);
            } else {
                $image = 'art/nopic.png';
            }

            if (array_key_exists($row['Page Key'], $candidates)) {

                //print $row['Page Key']." ".$page_scores[$row['Page Key']]." +".$score_match_product_description;

                $candidates[$row['Page Key']]['score'] += $row['score'];
                $page_scores[$row['Page Key']]         += $row['score'];

                //print " ".$page_scores[$row['Page Key']]."\n";

            } else {
                $candidates[$row['Page Key']]             = array();
                $candidates[$row['Page Key']]['scope']    = 'Family';
                $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];

                $candidates[$row['Page Key']]['webpage_key']          = $row['Page Key'];
                $candidates[$row['Page Key']]['image']             = $image;
                $candidates[$row['Page Key']]['score']             = $row['score'];
                $page_scores[$row['Page Key']]                     = $row['score'];
                $candidates[$row['Page Key']]['url']               = 'http://'.$row['Page URL'];
                $candidates[$row['Page Key']]['title']             = $row['Page Store Title'];
                $candidates[$row['Page Key']]['description']       = $row['Page Store Description'];
                $candidates[$row['Page Key']]['asset_description'] = '<span class="code">'.$row['Product Family Code'].'</span> '.$row['Product Family Name'];


            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    */

    /*

    $sql = sprintf(
        " SELECT `Page Store Image Key`, PSSD.`Page Key`,PSSD.`Page URL`,PSSD.`Page Store Title`,PSSD.`Page Store Resume` , match (PSSD.`Page Store Title`) AGAINST  (%s) AS score1,match (PSSD.`Page Store Resume`) AGAINST  (%s) AS score2,match (PSSD.`Page Store Content`) AGAINST  (%s) AS score3
	FROM `Page Store Search Dimension` PSSD  LEFT JOIN `Page Store Dimension` PSD ON (PSD.`Page Key`=PSSD.`Page Key`)   WHERE  PSD.`Page State`='Online' AND  PSSD.`Page Site Key`=%d AND  match (PSSD.`Page Store Title`,PSSD.`Page Store Resume`,PSSD.`Page Store Content`) AGAINST  (%s);",

        prepare_mysql($q), prepare_mysql($q), prepare_mysql($q), $website->id, prepare_mysql($q)
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Page Store Image Key'] > 0) {
                $image = sprintf('image_root.php?size=small&id=%d', $row['Page Store Image Key']);
            } else {
                $image = 'art/nopic.png';
            }


            $score = ($row['score1'] * 3 + $row['score2'] * 2 + $row['score3']) / 6;


            if (array_key_exists($row['Page Key'], $candidates)) {
                $candidates[$row['Page Key']]['score'] += $score;
                $page_scores[$row['Page Key']]         += $score;
            } else {
                $candidates[$row['Page Key']]             = array();
                $candidates[$row['Page Key']]['webpage_key'] = $row['Page Key'];
                $candidates[$row['Page Key']]['scope']    = 'Store';
                $candidates[$row['Page Key']]['image']    = $image;
                $candidates[$row['Page Key']]['score']    = $score;
                $page_scores[$row['Page Key']]            = $score;

                $candidates[$row['Page Key']]['url']               = 'http://'.$row['Page URL'];
                $candidates[$row['Page Key']]['title']             = $row['Page Store Title'];
                $candidates[$row['Page Key']]['description']       = $row['Page Store Resume'];
                $candidates[$row['Page Key']]['asset_description'] = '';


            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    */

    array_multisort($page_scores, SORT_NUMERIC, SORT_DESC, $candidates);


    $number_results = count($candidates);

    $did_you_mean      = '';
    $alternative_found = false;

    /*

   if ($number_results == 0) {

       foreach ($array_q as $_q) {
           $word_soundex = soundex($_q);
           $sql          = sprintf(
               'SELECT `Word` FROM  `Site Content Word Dimension` WHERE `Word Soundex`=%s ORDER BY `Multiplicity` DESC LIMIT 1', prepare_mysql($word_soundex)
           );


           if ($result = $db->query($sql)) {
               if ($row = $result->fetch()) {
                   $did_you_mean      .= $row['Word'].' ';
                   $alternative_found = true;
               } else {
                   $did_you_mean .= $_q.' ';
               }
           } else {
               print_r($error_info = $db->errorInfo());
               print "$sql\n";
               exit;
           }


       }


   }
*/

    if (!$alternative_found) {
        $did_you_mean = '';
    }

    return array(
        'number_results' => $number_results,
        'results'        => $candidates,
        'did_you_mean'   => $did_you_mean
    );

}


?>
