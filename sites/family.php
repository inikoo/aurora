<?php
include_once('common.php');
include_once('class.Department.php');



if (isset($_REQUEST['code'])) {

    $family=new Family('code',$_REQUEST['code'],$store_key);
    if (!$family->id) {

        header('Location: cataloge.php?wfc');
        exit;
    }

} else {
    header('Location: cataloge.php?nfc');
    exit;
}



$css_files=array(
               'css/common.css',
               'css/family.css',
               'css/dropdown.css',
               //'http://yui.yahooapis.com/combo?2.8.0r4/build/paginator/assets/skins/sam/paginator.css&2.8.0r4/build/datatable/assets/skins/sam/datatable.css',
               'css/table.css',
               'css/thumbnail.css',
               'css/family.css',

           );


$js_files=array(
              //'http://yui.yahooapis.com/combo?2.8.0r4/build/utilities/utilities.js&2.8.0r4/build/paginator/paginator-min.js&2.8.0r4/build/datasource/datasource-min.js&2.8.0r4/build/datatable/datatable-min.js&2.8.0r4/build/json/json-min.js',
             
               
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-debug.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container_core-min.js',
              $yui_path.'menu/menu-min.js',
              'js/dropdown.js',
              'js/common.js',

              'js/js/table_common.js',
              'js/table_sites_js/common.js',

              'family.js.php?key='.$family->id
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$page=$site->get_page_object('family',$family->id);
if (!$page->id) {
    header('Location: index.php?page_not_found');
    exit;
}

$smarty->assign('page',$page);
$page_data=$page->get_data_for_smarty($page_data);
$smarty->assign('page_data',$page_data);

update_page_key_visit_log($page->data['Page Key']);
$can_view=array();
$can_view['slideshow']=($page->data['Product Slideshow Layout']=='Yes'?1:0);
$can_view['manual']=($page->data['Product Manual Layout']=='Yes'?1:0);
$can_view['list']=($page->data['Product List Layout']=='Yes'?1:0);
$can_view['thumbnails']=($page->data['Product Thumbnails Layout']=='Yes'?1:0);

$number_views=0;
foreach($can_view as $key=>$value) {
    if ($value) {
        $number_views++;
        $table_type=$key;
    }
}
//print_r($page);
$smarty->assign('can_view_slideshow',$can_view['slideshow']);
$smarty->assign('can_view_manual',$can_view['manual']);
$smarty->assign('can_view_list',$can_view['list']);
$smarty->assign('can_view_thumbnails',$can_view['thumbnails']);


foreach(preg_split('/\,/',$myconf['family_table_type']) as $_table_type) {
    if (array_key_exists($_table_type,$can_view) and $can_view[$_table_type]) {
        $table_type=$_table_type;
        break;
    }
}
$table_type='thumbnails';
$smarty->assign('table_type',$table_type);


$smarty->assign('page_key',$page->data['Page Key']);

$smarty->assign('contents',$page->data['Page Source Template']);
$smarty->assign('header_template',"../templates/family_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"../templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"../templates/left_menu.".$store->data['Store Locale'].".tpl");




$order_by='`Product Code';

$sql=sprintf("select `Product Currency`,`Product Price`,`Product Current Key`,`Product Code`,`Product Web State`,`Product Name`,`Product Units Per Case`,`Product Main Image` from `Product Dimension` where `Product Family Key`=%d and `Product Sales Type`='Public Sale' and `Product Web State` not in ('Offline')  order by %s ",$family->id,$order_by);

$res=mysql_query($sql);
$products=array();
while ($row=mysql_fetch_array($res)) {
    $products[]=array(
                    'key'=>$row['Product Current Key'],
                    'code'=>$row['Product Code'],
                    'name'=>$row['Product Units Per Case'].'x '.$row['Product Name'],
                    'image'=>$pics_path.$row['Product Main Image'],
                    'price'=>money($row['Product Price'],$row['Product Currency']),
                    'ordered'=>0,
                    'to_charge'=>money(0,$row['Product Currency'])
                );
}


$smarty->assign('number_products',count($products));

$smarty->assign('products',$products);
$smarty->assign('family',$family);


$smarty->assign('js_files',$js_files);

$_SESSION['prev_page_key']=$page->data['Page Key'];

//$smarty->display("../templates/family.".$store->data['Store Locale'].".tpl");




?>
