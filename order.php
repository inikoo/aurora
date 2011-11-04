<?php
include_once('common.php');
include_once('class.CurrencyExchange.php');


include_once('class.Store.php');

include_once('class.Order.php');
if (!$user->can_view('orders')) {
    header('Location: index.php');
    exit;
}

$modify=$user->can_edit('orders');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               // $yui_path.'editor/assets/skins/sam/editor.css',
               // 'text_editor.css',
               'common.css',
               'container.css',
               'table.css',
		 		 'button.css'
           );


$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js'
          );

if (isset($_REQUEST['new']) ) {
    date_default_timezone_set('UTC');
    if (isset($_REQUEST['customer_key']) and is_numeric($_REQUEST['customer_key']) ) {
        $customer=new Customer($_REQUEST['customer_key']);
        if (!$customer->id)
            $customer=new Customer('create anonymous');
    } else
        $customer=new Customer('create anonymous');
    $editor=array(
                'Author Name'=>$user->data['User Alias'],
                'Author Alias'=>$user->data['User Alias'],
                'Author Type'=>$user->data['User Type'],
                'Author Key'=>$user->data['User Parent Key'],
                'User Key'=>$user->id
            );

    $order_data=array(

                    'Customer Key'=>$customer->id,
                    'Order Original Data MIME Type'=>'application/inikoo',
                    'Order Type'=>'Order',
                    'editor'=>$editor

                );


    $order=new Order('new',$order_data);
//exit;
    if ($order->error)
        exit('error');


    $ship_to=$customer->get_ship_to();
    $order-> update_ship_to($ship_to->id);


    header('Location: order.php?id='.$order->id);
    exit;



}



if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])) {
    header('Location: orders_server.php?msg=wrong_id');
    exit;
}

$general_options_list=array();
$order_id=$_REQUEST['id'];
$_SESSION['state']['order']['id']=$order_id;
$order=new Order($order_id);
if (!$order->id) {
    header('Location: orders_server.php?msg=order_not_found');
    exit;

}
if (!($user->can_view('stores') and in_array($order->data['Order Store Key'],$user->stores)   ) ) {
    header('Location: orders_server.php');
    exit;
}

$customer=new Customer($order->get('order customer key'));


$store=new Store($order->data['Order Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

if (isset($_REQUEST['pick_aid'])) {
    $js_files[]='order_pick_aid.js.php';
    $template='order_pick_aid.tpl';
} else {

    switch ($order->get('Order Current Dispatch State')) {

    case('In Process'):
  case('Ready to Pick'):
        $js_files[]='js/edit_common.js';


        $js_files[]='edit_address.js.php';
        $js_files[]='address_data.js.php?tipo=customer&id='.$customer->id;

        $js_files[]='edit_delivery_address_js/common.js';
        $js_files[]='order_in_process.js.php?order_key='.$order_id.'&customer_key='.$customer->id;

        $css_files[]='css/edit_address.css';


        $template='order_in_process.tpl';



        $_SESSION['state']['order']['store_key']=$order->data['Order Store Key'];


        if ($order->data['Order Number Items']) {
            $products_display_type='ordered_products';

        } else {
            $products_display_type='all_products';

        }

        $_SESSION['state']['order']['products']['display']=$products_display_type;

        $products_display_type=$_SESSION['state']['order']['products']['display'];

        $smarty->assign('products_display_type',$products_display_type);




        $tipo_filter=$_SESSION['state']['order']['products']['f_field'];


        $smarty->assign('filter',$tipo_filter);
        $smarty->assign('filter_value',$_SESSION['state']['order']['products']['f_value']);
        $filter_menu=array(
            'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
            'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
            'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

        );
        $smarty->assign('filter_menu0',$filter_menu);
        $smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


        $paginator_menu=array(10,25,50,100);
        $smarty->assign('paginator_menu0',$paginator_menu);

        $smarty->assign('search_label',_('Products'));
        $smarty->assign('search_scope','products');

 $general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

        break;
    case('Dispatched'):



        if ($modify) {
            $general_options_list[]=array('tipo'=>'url','url'=>'new_post_order.php?id='.$order->id,'label'=>_('Post Dispatch Operations'));
//   $general_options_list[]=array('tipo'=>'url','url'=>'new_post_order.php?type=sht&id='.$order->id,'label'=>_('Make Shortage'));
            //     $general_options_list[]=array('tipo'=>'url','url'=>'new_refund.php?id='.$order->id,'label'=>_('Refund'));


        }

        $smarty->assign('search_label',_('Orders'));
        $smarty->assign('search_scope','orders_store');

        $js_files[]='order_dispatched.js.php';
        $template='order_dispatched.tpl';
        break;
    case('Cancelled'):
        $smarty->assign('search_label',_('Orders'));
        $smarty->assign('search_scope','orders_store');

        $js_files[]='order_cancelled.js.php';
        $template='order_cancelled.tpl';
        break;
    case('Suspended'):


        $js_files[]='order_suspended.js.php';
        $template='order_suspended.tpl';
        break;
    case('Unknown'):
        $js_files[]='order_unknown.js.php';
        $template='order_unknown.tpl';
        break;
    case('Ready to Ship'):
        $js_files[]='order_ready_to_ship.js.php';
        $template='order_ready_to_ship.tpl';
        break;
    default:
        exit('todo '.$order->get('Order Current Dispatch State'));
        break;
    }
}
$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('order',$order);
$smarty->assign('customer',$customer);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Order').' '.$order->get('Order Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($template);
?>
