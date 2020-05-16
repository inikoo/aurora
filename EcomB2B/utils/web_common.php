<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 April 2018 at 14:24:09 GMT+8, Cyberjaya, Malysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

function get_device() {
    require_once 'external_libs/mobile_detect/Mobile_Detect.php';
    $detect = new Mobile_Detect;

    if ($detect->isTablet()) {
        $detected_device = 'tablet';
        $template_suffix = '.tablet';
    } elseif ($detect->isMobile()) {
        $detected_device = 'mobile';
        $template_suffix = '.mobile';
    } else {
        $detected_device = 'desktop';
        $template_suffix = '';

    }

    return array(
        $detected_device,
        $template_suffix
    );
}

function get_logged_in() {

    $logged_in = !empty($_SESSION['logged_in']);

    if (!$logged_in and !empty($_COOKIE['rmb2'])) {




        include_once('class.WebAuth.php');

        $auth = new WebAuth();
        list($selector, $authenticator) = explode(':', $_COOKIE['rmb2']);


        list($logged_in, $result, $customer_key, $website_user_key, $website_user_log_key) = $auth->authenticate_from_remember($selector, $authenticator, $_SESSION['website_key']);

        if ($logged_in) {

            $_SESSION['logged_in']            = true;
            $_SESSION['customer_key']         = $customer_key;
            $_SESSION['website_user_key']     = $website_user_key;
            $_SESSION['website_user_log_key'] = $website_user_log_key;
        }

    }

    if ($logged_in and (empty($_SESSION['customer_key']) or empty($_SESSION['website_user_key']) or empty($_SESSION['website_user_log_key']))) {


        session_regenerate_id();
        session_destroy();
        unset($_SESSION);
        setcookie(
            'rmb2', 'x:x', time() - 864000, '/'
        );
        header('Location: /index.php');
        exit;
    }

    if ($logged_in and empty($_COOKIE['rmb2'])) {


        require_once "external_libs/random/lib/random.php";
        $selector      = base64_encode(random_bytes(9));
        $authenticator = random_bytes(33);

        setcookie(
            'rmb2', $selector.':'.base64_encode($authenticator), time() + 864000, '/'

        );

        include_once 'keyring/dns.php';

        if (!isset($db)) {

            require 'keyring/dns.php';

            $db = new PDO(
                "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
            );
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        $sql = sprintf(
            'INSERT INTO `Website Auth Token Dimension` (`Website Auth Token Website Key`,`Website Auth Token Selector`,`Website Auth Token Hash`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key`,`Website Auth Token Expire`) 
            VALUES (%d,%s,%s,%d,%d,%d,%s)', $_SESSION['website_key'], prepare_mysql($selector), prepare_mysql(hash('sha256', $authenticator)), $_SESSION['website_user_key'], $_SESSION['customer_key'], $_SESSION['website_user_log_key'],
            prepare_mysql(gmdate('Y-m-d H:i:s', time() + 864000))

        );

        $db->exec($sql);

    }


    return $logged_in;

}

function sanitize($content) {

    include_once 'utils/html_minifier.php';

    if (!empty($content['blocks']) and is_array($content['blocks'])) {
        foreach ($content['blocks'] as $block_key => $block) {

            switch ($block['type']) {
                case 'favourites':
                    $content['blocks'][$block_key]['labels']['with_items'] = process_text($content['blocks'][$block_key]['labels']['with_items']);
                    $content['blocks'][$block_key]['labels']['no_items']   = process_text($content['blocks'][$block_key]['labels']['no_items']);
                    break;

                    $content['blocks'][$block_key]['labels']['with_items'] = process_text($content['blocks'][$block_key]['labels']['with_items']);
                    $content['blocks'][$block_key]['labels']['no_items']   = process_text($content['blocks'][$block_key]['labels']['no_items']);
                    break;
                case 'product':
                    $content['blocks'][$block_key]['text'] = process_text($content['blocks'][$block_key]['text']);
                    break;
                case 'blackboard':
                    foreach ($block['texts'] as $block_text_key => $block_text) {
                        $content['blocks'][$block_key]['texts'][$block_text_key]['text'] = process_text($content['blocks'][$block_key]['texts'][$block_text_key]['text']);
                    }
                    $content['blocks'][$block_key]['mobile_html'] = process_text($content['blocks'][$block_key]['mobile_html']);
                    $content['blocks'][$block_key]['tablet_html'] = process_text($content['blocks'][$block_key]['tablet_html']);


                    break;
                case 'text':
                    foreach ($block['text_blocks'] as $block_text_key => $block_text) {
                        $content['blocks'][$block_key]['text_blocks'][$block_text_key]['text'] = process_text($content['blocks'][$block_key]['text_blocks'][$block_text_key]['text']);
                    }


                    break;
                case 'category_categories':

                    foreach ($block['sections'] as $sections_key => $section) {
                        foreach ($section['items'] as $item_key => $item) {
                            if ($item['type'] == 'text') {
                                $content['blocks'][$block_key]['sections'][$sections_key]['items'][$item_key]['text'] = process_text($content['blocks'][$block_key]['sections'][$sections_key]['items'][$item_key]['text']);
                            }

                        }

                    }
                    break;
                case 'category_products':
                case 'products':
                    foreach ($block['items'] as $item_key => $item) {
                        if ($item['type'] == 'text') {
                            $content['blocks'][$block_key]['items'][$item_key]['text'] = process_text($content['blocks'][$block_key]['items'][$item_key]['text']);
                        } elseif ($item['type'] == 'product') {
                            $content['blocks'][$block_key]['items'][$item_key]['header_text'] = process_text($content['blocks'][$block_key]['items'][$item_key]['header_text']);
                        }

                    }


            }

        }
    }

    return $content;
}


function process_text($text, $minify = true) {

    if ($text == '') {
        return;
    }


    $text = replace_class($text);


    $text = preg_replace('/&nbsp;<\/span>/', '</span>&nbsp;', $text);
    $text = preg_replace('/&nbsp;<\/p>$/', '</p>', $text);
    $text = preg_replace('/<br>/', '<br/>', $text);

    if ($minify) {
        $minifier = new TinyHtmlMinifier([]);
        $text     = $minifier->minify($text);
    }

    return $text;

}

function replace_class($html) {
    return preg_replace_callback(
        '/class="([^"]+)"/', function ($m) {


        if (strpos($m[1], "fr-fill") !== false) {
            $m[0] = preg_replace("/\bfr-fill\b/", '_xa1_', $m[0], 1);
        }

        if (strpos($m[1], "fr-dii") !== false) {
            $m[0] = preg_replace("/\bfr-dii\b/", '_xa2_', $m[0], 1);
        }

        if (strpos($m[1], "fr-rounded") !== false) {
            $m[0] = preg_replace("/\bfr-rounded\b/", '_xa3_', $m[0], 1);
        }

        if (strpos($m[1], "fr-dib") !== false) {
            $m[0] = preg_replace("/\bfr-dib\b/", '_xa4_', $m[0], 1);
        }


        if (strpos($m[1], "fr-deletable") !== false) {
            $m[0] = preg_replace("/\bfr-deletable\b/", '', $m[0], 1);
        }

        if (strpos($m[1], "fr-view") !== false) {
            $m[0] = preg_replace("/\bfr-view\b/", '_au_vw_', $m[0], 1);
        }

        if (strpos($m[1], "fr-emoticon") !== false) {
            $m[0] = preg_replace("/\bfr-emoticon\b/", '_eji_', $m[0], 1);
        }
        if (strpos($m[1], "fr-emoticon-img") !== false) {
            $m[0] = preg_replace("/\bfr-emoticon-img\b/", '_ei_', $m[0], 1);
        }
        if (strpos($m[1], "fr-bordered") !== false) {
            $m[0] = preg_replace("/\bfr-bordered\b/", '_aa1_', $m[0], 1);
        }

        if (strpos($m[1], "fr-clearfix") !== false) {
            $m[0] = preg_replace("/\bfr-clearfix\b/", '_cc_', $m[0], 1);
        }

        if (strpos($m[1], "hide-by-clipping") !== false) {
            $m[0] = preg_replace("/\bhide-by-clipping\b/", '_h_', $m[0], 1);
        }

        if (strpos($m[1], "fr-strong") !== false) {
            $m[0] = preg_replace("/\bfr-strong\b/", '_str_', $m[0], 1);
        }


        if (strpos($m[1], "fr-large") !== false) {
            $m[0] = preg_replace("/\bfr-large\b/", '_f24_', $m[0], 1);
        }

        if (strpos($m[1], "fr-green") !== false) {
            $m[0] = preg_replace("/\bfr-green\b/", '_gre_', $m[0], 1);
        }
        if (strpos($m[1], "fr-video") !== false) {
            $m[0] = preg_replace("/\bfr-video\b/", '_v_', $m[0], 1);
        }
        if (strpos($m[1], "fr-fir") !== false) {
            $m[0] = preg_replace("/\bfr-fir\b/", '_ww_', $m[0], 1);
        }
        if (strpos($m[1], "fr-dvb") !== false) {
            $m[0] = preg_replace("/\bfr-dvb\b/", '_w1w_', $m[0], 1);
        }
        if (strpos($m[1], "fr-dvi") !== false) {
            $m[0] = preg_replace("/\bfr-dvi\b/", '_a1a_', $m[0], 1);
        }
        if (strpos($m[1], "fr-img-wrap") !== false) {
            $m[0] = preg_replace("/\bfr-img-wrap\b/", '_z11_', $m[0], 1);
        }

        if (strpos($m[1], "fr-rv") !== false) {
            $m[0] = preg_replace("/\bfr-rv\b/", '_ffo_', $m[0], 1);
        }

        if (strpos($m[1], "fr-fvl") !== false) {
            $m[0] = preg_replace("/\bfr-fvl\b/", '_ffl_', $m[0], 1);
        }
        if (strpos($m[1], "fr-fvr") !== false) {
            $m[0] = preg_replace("/\bfr-fvr\b/", '_ffr_', $m[0], 1);
        }

        if (strpos($m[1], "fr-thick") !== false) {
            $m[0] = preg_replace("/\bfr-thick\b/", '_ttt_', $m[0], 1);
        }
        if (strpos($m[1], "fr-highlighted") !== false) {
            $m[0] = preg_replace("/\bfr-highlighted\b/", '_hhh_', $m[0], 1);
        }
        if (strpos($m[1], "fr-class-highlighted") !== false) {
            $m[0] = preg_replace("/\bfr-class-highlighted\b/", '_p_', $m[0], 1);
        }
        if (strpos($m[1], "fr-dashed-borders") !== false) {
            $m[0] = preg_replace("/\bfr-dashed-borders\b/", '_ddd_', $m[0], 1);
        }

        if (strpos($m[1], "fr-text-uppercase") !== false) {
            $m[0] = preg_replace("/\bfr-text-uppercase\b/", '_q1', $m[0], 1);
        }
        if (strpos($m[1], "fr-class-transparency") !== false) {
            $m[0] = preg_replace("/\bfr-class-transparency\b/", '_q2', $m[0], 1);
        }
        if (strpos($m[1], "fr-text-bordered") !== false) {
            $m[0] = preg_replace("/\bfr-text-bordered\b/", '_q3', $m[0], 1);
        }
        if (strpos($m[1], "fr-text-gray") !== false) {
            $m[0] = preg_replace("/\bfr-text-gray\b/", 'grey', $m[0], 1);
        }
        if (strpos($m[1], "fr-text-spaced") !== false) {
            $m[0] = preg_replace("/\bfr-text-spaced\b/", 'text_spaced', $m[0], 1);
        }
        if (strpos($m[1], "fr-alternate-rows") !== false) {
            $m[0] = preg_replace("/\bfr-class-code\b/", '_atr1_', $m[0], 1);
        }


        return $m[0];

    }, $html
    );
}
