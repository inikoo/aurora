<?php

include_once 'utils/natural_language.php';


if (!isset($page_key) and isset($_REQUEST['id'])) {
    $page_key = $_REQUEST['id'];
}

if (!isset($page_key)) {
    header('Location: index.php?no_page_key');
    exit;
}

if (!isset($skip_common)) {
    include_once 'common.php';
}


if (!$is_cached) {

    $page = new Page($page_key);


    if (!$page->id) {
        header('Location: index.php?no_page');
        exit;
    }


    if ($page->data['Page Site Key'] != $site->id) {
        header('Location: index.php?site_page_not_match');
        //    exit("No site/page not match");
        exit;
    }

    if ($page->data['Page Store Section'] == 'Search') {
        header('Location: search.php');
        exit;
    }

    if ($page->data['Page Store Section'] == 'Registration') {
        header('Location: registration.php');
        exit;
    }
    if ($page->data['Page Store Section'] == 'Login') {
        header('Location: login.php');
        exit;
    }
    if ($page->data['Page Store Section'] == 'Reset') {
        header('Location: reset.php');
        exit;
    }

    if ($page->data['Page Store Section'] == 'Client Section') {
        header('Location: profile.php');
        exit;
    }


    if ($page->data['Page State'] == 'Offline') {


        $site_url = $site->data['Site URL'];
        $url      = $_SERVER['REQUEST_URI'];
        $url      = preg_replace('/^\//', '', $url);
        $url      = preg_replace('/\?.*$/', '', $url);

        $original_url = $url;
        header("Location: http://".$site_url."/404.php?&url=$url&original_url=$original_url");

        exit;
    }

    //'System','Info','Department','Family','Product','FamilyCategory','ProductCategory','Thanks'
    if (in_array(
        $page->data['Page Store Section Type'], array(
                                                  'Family',
                                                  'Product'
                                              )
    )) {

        if ($order_in_process and $order_in_process->id) {
            if ($order_in_process->data['Order Current Dispatch State'] == 'Waiting for Payment Confirmation') {
                header('Location: waiting_payment_confirmation.php');
                exit;

            }
        }
    }
    $template_suffix = '';

    if ($logged_in) {
        $page->customer = $customer;
        $page->order    = $order_in_process;
    }

    $smarty->assign('logged', $logged_in);
    $page->site            = $site;
    $page->user            = $user;
    $page->logged          = $logged_in;
    $page->currency        = $store->data['Store Currency Code'];
    $page->currency_symbol = currency_symbol($store->data['Store Currency Code']);
    $page->customer        = $customer;


    if (in_array(
            $page->get('Page Store Content Template Filename'), array(
                                                                  'products_showcase',
                                                                  'categories_showcase',
                                                                  ''
                                                              )
        ) and $page->get('Page Store Content Display Type') == 'Template'
    ) {
        $version = 2;
    } else {
        $version = 1;

    }


    $smarty->assign('_version_', $version);


    $smarty->assign('title', $page->data['Page Title']);
    $smarty->assign('store', $store);
    $smarty->assign('page', $page);
    $smarty->assign('site', $site);

    $css_files = array();
    $js_files  = array();


    if ($version == 1) {

        $base_css_files = array(
            $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
            $yui_path.'menu/assets/skins/sam/menu.css',
            'css/inikoo.css',
            'css/style.css'

        );
    } else {
        $base_css_files = array(
            $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
            $yui_path.'menu/assets/skins/sam/menu.css',
            'css/inikoo.css',
            'css/style.css',

        );

    }

    if ($version == 2) {

        $base_js_files = array(
            $yui_path.'utilities/utilities.js',
            $yui_path.'json/json-min.js',
            'js/common.js',
            'js/edit_common.js',

            // 'js/page.js'
        );
    } else {
        $base_js_files = array(
            "js/jquery.min.js",
            "js/analytics.js",
            $yui_path.'utilities/utilities.js',
            $yui_path.'json/json-min.js',
            'js/common.js',
            'js/edit_common.js',

            // 'js/page.js'
        );
    }

    //$js_files=array("js/jquery.min.js","js/analytics.js");
    $js_files[] = sprintf(INIKOO_ACCOUNT."_js/menu_%02d.js", $site->id);


    // Dont put YUI stuff in normal assets pages (except if is inikoo -check out-)
    if (!$site->data['Site Checkout Method'] == 'Inikoo' and !in_array(
            $page->data['Page Store Section'], array(
                                                 'Registration',
                                                 'Client Section',
                                                 'Checkout',
                                                 'Login',
                                                 'Welcome',
                                                 'Reset',
                                                 'Basket'
                                             )
        )
    ) {
        $base_js_files = array();
    }

    if ($logged_in and $site->data['Site Checkout Method'] == 'Inikoo') {
        $base_css_files[] = 'css/order_fields.css';
    }


    $sql =
        sprintf("SELECT `External File Type`,`Page Store External File Key` AS external_file_key FROM `Page Header External File Bridge` WHERE `Page Header Key`=%d", $page->data['Page Header Key']);
    $res = mysql_query($sql);
    //print $sql;
    while ($row = mysql_fetch_assoc($res)) {
        if ($row['External File Type'] == 'CSS') {
            $base_css_files[] = sprintf(INIKOO_ACCOUNT."_css/%07d.css", $row['external_file_key']);
        } else {
            $base_js_files[] = sprintf(INIKOO_ACCOUNT."_js/%07d.js", $row['external_file_key']);
        }
    }

    $sql =
        sprintf("SELECT `External File Type`,`Page Store External File Key` AS external_file_key FROM `Page Footer External File Bridge` WHERE `Page Footer Key`=%d", $page->data['Page Footer Key']);
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        if ($row['External File Type'] == 'CSS') {

            $base_css_files[] = sprintf(INIKOO_ACCOUNT."_css/%07d.css", $row['external_file_key']);
        } else {
            $base_js_files[] = sprintf(INIKOO_ACCOUNT."_js/%07d.js", $row['external_file_key']);
        }
    }

    $sql = sprintf("SELECT `External File Type`,`Page Store External File Key` AS external_file_key FROM `Site External File Bridge` WHERE `Site Key`=%d", $site->id);
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        if ($row['External File Type'] == 'CSS') {
            $base_css_files[] = sprintf(INIKOO_ACCOUNT."_css/%07d.css", $row['external_file_key']);
        } else {
            $base_js_files[] = sprintf(INIKOO_ACCOUNT."_js/%07d.js", $row['external_file_key']);
        }
    }

    $sql = sprintf("SELECT `External File Type`,`Page Store External File Key` AS external_file_key FROM `Page Store External File Bridge` WHERE `Page Key`=%d", $page->id);
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        if ($row['External File Type'] == 'CSS') {
            $base_css_files[] = sprintf(INIKOO_ACCOUNT."_css/%07d.css", $row['external_file_key']);
        } else {
            $base_js_files[] = sprintf(INIKOO_ACCOUNT."_js/%07d.js", $row['external_file_key']);
        }
    }


    if ($page->data['Page Store Content Display Type'] == 'Source') {

        $smarty->assign('type_content', 'file');
        $smarty->assign('template_string', $page->data['Page Store Source']);
        $smarty->assign('user_template', true);


        $smarty->assign('template_string', sprintf("pages/%07d.tpl", $page->id));

    } else {

        $smarty->assign('type_content', 'file');


        if ($version == 1) {

            $css_files[] = 'css/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.css';

        }


        if ($page->data['Page Code'] == 'login') {

            //if (strpos((isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:''), 'Chrome') !== false) {
            // $smarty->assign('template_string','login.chrome.tpl');
            // $js_files[]='js/login.chrome.js';
            //}else {
            $smarty->assign('template_string', 'login.tpl');
            $js_files[] = 'js/login.js';
            //}
        } else {

            $smarty->assign('template_string', $page->data['Page Store Content Template Filename'].$template_suffix.'.tpl');
            if ($version == 1) {
                $js_files[] = 'js/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.js';
            }
        }
    }


    $js_files[] = 'js/reminders.js';


    if ($site->data['Site Checkout Method'] == 'Inikoo') {
        $js_files[] = 'js/fill_basket.js';
        $js_files[] = 'js/edit_favorites.js';

    }
    $js_files[] = 'js/edit_currency.js';


    if ($version == 1) {
        $js_files[]  = sprintf(INIKOO_ACCOUNT."_js/page_%05d.js", $page->id);
        $css_files[] = sprintf(INIKOO_ACCOUNT."_css/page_%05d.css", $page->id);
    }

    if ($site->data['Site Search Method'] == 'Custome') {
        $js_files[]  = sprintf(INIKOO_ACCOUNT."_js/search_%02d.js", $site->id);
        $css_files[] = sprintf(INIKOO_ACCOUNT."_css/search_%02d.css", $site->id);
    } else {
        $js_files[]  = 'js/bar_search.js';
        $css_files[] = 'css/bar_search.css';
    }
    if ($site->data['Site Checkout Method'] == 'Mals') {
        $js_files[] = 'js/basket_emals_commerce.js';
    }


    if ($page->data['Page Store Section'] == 'Family Catalogue' and $version == 1) {

        $js_files[] = 'js/fz.shadow.js';
        $js_files[] = 'js/fz.js';
        $js_files[] = 'js/imgpop.js';
    }


    $css_files[] = sprintf(INIKOO_ACCOUNT."_css/menu_%02d.css", $site->id);

    $js_no_async_files = array();
    //$js_no_async_files=array("js/jquery.min.js","js/analytics.js?1");
    //$js_no_async_files[]=sprintf(INIKOO_ACCOUNT."_js/menu_%02d.js",$site->id);
    $smarty->assign('js_no_async_files', join(',', $js_no_async_files));


    $css_files = array_merge($base_css_files, $css_files);

    // print_r($css_files);

    $js_files = array_merge($base_js_files, $js_files);

    $smarty->assign('css_files', join(',', $css_files).'?v=150608');
    $smarty->assign('js_files', join(',', $js_files).'?v=150814');


    include 'template_assignments.php';


}


if ($page->get('Webpage Version') == 2 and false) {


    $footer_data = array(
        'rows' => array(
            array(
                'type'    => 'main_4',
                'columns' => array(

                    array(
                        'type' => 'address',

                        'items' => array(

                        array(
                                'type'=>'logo',
                                'src'   => 'theme_1/images/footer-logo.png',
                                'alt'   => '',
                                'title' => ''

                            ),
                         array(
                                'type'=>'text',
                                'icon' => 'fa-map-marker ',
                                'text' => '2901 Marmora Road, Glassgow,<br>Seattle, WA 98122-1090'
                            ),
                          array(
                                'type'=>'text',
                                'icon' => 'fa-phone',
                                'text' => '1 -234 -456 -7890'
                            ),
                     array(
                                'type'=>'email',
                                'text' => 'info@yourdomain.com'
                            ),
                           array(
                                'type'=>'logo',
                                'src'   => 'theme_1/images/footer-wmap.png',
                                'alt'   => '',
                                'title' => ''

                            ),
                        ),


                    ),

                    array(
                        'type'   => 'links',
                        'header' => 'Useful Links',

                        'items' => array(
                            array(
                                'url'   => '#',
                                'label' => 'Home Page Variations'
                            ),
                            array(
                                'url'   => '#',
                                'label' => 'Awsome Slidershows'
                            ),
                            array(
                                'url'   => '#',
                                'label' => 'Features and Typography'
                            )
                        )
                    ),

                    array(
                        'type'   => 'links',
                        'header' => 'Useful Links 2',
                        'items'  => array(
                            array(
                                'url'   => '#',
                                'label' => 'Home Page Variations'
                            ),
                            array(
                                'url'   => '#',
                                'label' => 'Awsome Slidershows'
                            ),
                            array(
                                'url'   => '#',
                                'label' => 'Features and Typography'
                            )
                        )
                    ),


                    array(
                        'type'    => 'text',
                        'header'  => 'About Us',
                        'text' => '
                        
                        <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined</p>
                        <br />
                        <p>An chunks as necessary, making this the first true generator on the Internet. Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover desktop publishing packages many purpose web sites.</p>

                        '
                    )

                )

            ),
            array(
                'type' => 'copyright',
                'columns' => array(
                    array(
                        'type'=>'text',
                        'text'=>'Copyright © 2014 Aaika.com. All rights reserved.  <a href="#">Terms of Use</a> | <a href="#">Privacy Policy</a>'

                    ),
                    array(
                        'type'=>'social_links',
                        'items'=>array(
                            array(
                                'icon' => 'fa-facebook',
                                'url' => '#'

                            )

                        ),
                        'items'=>array(
                            array(
                                'icon' => 'fa-twitter',
                                'url' => '#'

                            ),
                            array(
                                'icon' => 'fa-linkedin',
                                'url' => '#'

                            )

                        )



                    )

    )

            )

        )


    );
    $smarty->assign('footer_data', $footer_data);


    $smarty->display('webpage.tpl', $page_key);

} else {
    $smarty->display('page.tpl', $page_key);
}


?>
