<?php
include_once('common.php');



if (!isset($page_key) and isset($_REQUEST['id'])) {
    $page_key=$_REQUEST['id'];
}

if (!isset($page_key)) {

    header('Location: index.php');
    exit;
}

$page=new Page($page_key);

if (!$page->id) {
    header('Location: index.php');
    exit;
}

if ($page->data['Page Site Key']!=$site->id) {
    header('Location: index.php');
    exit;
}


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'editor/editor-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              $yui_path.'uploader/uploader-min.js',

              'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
        //      'js/table_common.js',
        
              'js/edit_common.js',
              'upload_common.js.php',
              'js/page.js'
          );

$template_suffix='';
if ($page->data['Page Code']=='login') {
    $Sk="skstart|".(date('U')+300000)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
    $St=AESEncryptCtr($Sk,SKEY, 256);
    $smarty->assign('St',$St);

    if (isset($_REQUEST['logged_out'])) {
        $smarty->assign('logged_out',1);

    }

    if (isset($_REQUEST['from']) and is_numeric($_REQUEST['from'])) {
        $referral=$_REQUEST['from'];
    } else {
        $referral='';
    }
    $smarty->assign('referral',$referral);

    $js_files[]='js/aes.js';
    $js_files[]='js/sha256.js';
    $css_files[]='css/inikoo.css';

} else if ($page->data['Page Code']=='registration') {
    $welcome=false;
    if ($logged_in) {

        if (isset($_REQUEST['welcome'])) {
            $welcome=true;
        } else {

            header('location: profile.php');
            exit;
        }

    }


    $smarty->assign('welcome',$welcome);

    $js_files[]='js/aes.js';
    $js_files[]='js/sha256.js';
    $css_files[]='css/inikoo.css';
}

else if ($page->data['Page Code']=='profile') {




    if (!$logged_in) {
        header('location: login.php');
        exit;
    }


    if (isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('contact','orders','address_book','change_password', 'add_address', 'edit_address'))) {
        $view=$_REQUEST['view'];
    } else {
        $view='contact';
    }

    $template_suffix='_'.$view;


    $smarty->assign('view',$view);

    $smarty->assign('user',$user);
    $rnd='';

    for ($i = 0; $i < 16; $i++) {
        $rnd .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
    }

    $epwcp1=sprintf("%sinsecure_key%s",$user->id,$rnd);

    $smarty->assign('epwcp1',$epwcp1);
    $smarty->assign('rnd',$rnd);
    $js_files[]='js/aes.js';
    $js_files[]='js/sha256.js';
       $js_files[]='js/table_common.js';
     $js_files[]='js/edit_common.js';
     $css_files[]='css/container.css';
    $css_files[]='css/inikoo.css';
    $css_files[]='css/inikoo_table.css';

}

$smarty->assign('logged',$logged_in);
$page->site=$site;
$page->user=$user;
$page->logged=$logged_in;
$page->currency=$store->data['Store Currency Code'];

if ($logged_in) {
    $page->customer=$customer;
}


$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='public_external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page->data['Page Footer Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='public_external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='public_external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='public_external_file.php?id='.$row['external_file_key'];

}


$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `Page Key`=%d",$page->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='public_external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

if ($page->data['Page Store Content Display Type']=='Source') {
    $smarty->assign('type_content','string');
    $smarty->assign('template_string',$page->data['Page Store Source']);
} else {
    $smarty->assign('type_content','file');
    $smarty->assign('template_string',$page->data['Page Store Content Template Filename'].$template_suffix.'.tpl');
    $css_files[]='css/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.css';
    $js_files[]='js/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.js';
}

$customer=new Customer(5);
$page->customer=$customer;
$smarty->assign('filter_name0','Order ID');
$smarty->assign('filter_value0', '');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title',$page->data['Page Title']);
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);
$smarty->display('page.tpl');

?>