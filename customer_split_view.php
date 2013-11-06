<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('class.Customer.php');
include_once('class.Store.php');
if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit;
}

$modify=$user->can_edit('contacts');


if (isset($_REQUEST['id_a']) and is_numeric($_REQUEST['id_a']) ) {

    $customer_id_a=$_REQUEST['id_a'];
} else {
    header('Location: customers.php?error=no_id_a');
    exit();
}


$customer_a['name']='';
if (isset($_REQUEST['name_a'])) {
    $customer_a['name']=urldecode($_REQUEST['name_a']);
}
$customer_b['name']='';
if (isset($_REQUEST['name_b'])) {
    $customer_b['name']=urldecode($_REQUEST['name_b']);
}


$customer_a['id']=false;
$customer=new customer($customer_id_a);
if ($customer->id) {

//print_r($customer->data);

    $customer_a['formated_id']=$customer->get_formated_id($myconf['customer_id_prefix']);
    $customer_a['card']=$customer->display('card',$myconf['customer_id_prefix']);
    $customer_a['sticky_note']=$customer->get('Sticky Note');
    $customer_a['since']=$customer->get('First Contacted Date');
    $customer_a['last_order_date']=$customer->get('Last Order Date');
    $customer_a['orders']=$customer->get('Orders');
    $customer_a['notes']=$customer->get('Notes');
    $customer_a['id']=$customer->id;
    $customer_a['store_key']=$customer->data['Customer Store Key'];
    $customer_a['deleted']=false;

    $smarty->assign('customer_a_object',$customer);

} else {
    $sql=sprintf("select * from `Customer Deleted Dimension` where `Customer Key`=%d",$customer_id_a);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $customer_a['formated_id']=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
        $customer_a['card']=$row['Customer Card'];
        $customer_a['sticky_note']='';
        $customer_a['since']='';
        $customer_a['last_order_date']='';
        $customer_a['orders']=0;
        $customer_a['notes']=0;
        $customer_a['id']=$row['Customer Key'];
        $customer_a['store_key']=$row['Customer Store Key'];
        $customer_a['deleted']=true;

        $customer_a['msg']=_('Customer deleted');

        $msg='';
        $sql=sprintf("select * from `Customer Merge Bridge` where `Merged Customer Key`=%d",$customer_id_a);
        $res2=mysql_query($sql);
        if ($row2=mysql_fetch_assoc($res2)) {


            $_customer=new Customer($row2['Customer Key']);
            $msg.=','.sprintf("<a style='color:SteelBlue' href='customer.php?id=%d'>%s</a>",$_customer->id,$_customer->get_formated_id($myconf['customer_id_prefix']));
        }
        $msg=preg_replace('/^,/','',$msg);
        if ($msg!='') {
            $customer_a['msg']=_('Customer merged with').': '.$msg;

        }

    }

}

if (isset($_REQUEST['id_b']) and is_numeric($_REQUEST['id_b']) ) {

    $customer_id_b=$_REQUEST['id_b'];
} else {
    header('Location: customers.php?error=no_id_b');
    exit();
}
$customer_b['id']=false;
$customer=new customer($customer_id_b);
if ($customer->id) {
    $customer_b['formated_id']=$customer->get_formated_id($myconf['customer_id_prefix']);
    $customer_b['card']=$customer->display('card',$myconf['customer_id_prefix']);
    $customer_b['sticky_note']=$customer->get('Sticky Note');
    $customer_b['since']=$customer->get('First Contacted Date');
    $customer_b['last_order_date']=$customer->get('Last Order Date');
    $customer_b['orders']=$customer->get('Orders');
    $customer_b['notes']=$customer->get('Notes');
    $customer_b['id']=$customer->id;
    $customer_b['store_key']=$customer->data['Customer Store Key'];
    $customer_b['deleted']=false;

    $smarty->assign('customer_b_object',$customer);



} else {
    $sql=sprintf("select * from `Customer Deleted Dimension` where `Customer Key`=%d",$customer_id_b);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $customer_b['formated_id']=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
        $customer_b['card']=$row['Customer Card'];
        $customer_b['sticky_note']='';
        $customer_b['since']='';
        $customer_b['last_order_date']='';
        $customer_b['orders']=0;
        $customer_b['notes']=0;
        $customer_b['id']=$row['Customer Key'];
        $customer_b['store_key']=$row['Customer Store Key'];
        $customer_b['deleted']=true;
        $customer_b['msg']=_('Customer deleted');

        $msg='';
        $sql=sprintf("select * from `Customer Merge Bridge` where `Merged Customer Key`=%d",$customer_id_b);
        $res2=mysql_query($sql);
        if ($row2=mysql_fetch_assoc($res2)) {


            $_customer=new Customer($row2['Customer Key']);
            $msg.=','.sprintf("<a style='color:SteelBlue' href='customer.php?id=%d'>%s</a>",$_customer->id,$_customer->get_formated_id($myconf['customer_id_prefix']));
        }
        $msg=preg_replace('/^,/','',$msg);
        if ($msg!='') {
            $customer_b['msg']=_('Customer merged with').': '.$msg;

        }

    }
}

//print_r($customer_a);
//print_r($customer_b);
//exit;

if (!$customer_a['id'] or !$customer_b['id']) {
    header('Location: customers.php?error=customers_not_exists');
    exit();

}
if ($customer_a['id']==$customer_b['id']) {
    header('Location: customer.php?id='.$customer_b['id']);
    exit();

}

if ($customer_a['store_key']!=$customer_b['store_key']) {
    header('Location: customers.php?error=Customers_not_same_store');
    exit();

}

$store=new Store($customer_b['store_key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               'css/text_editor.css',
               'css/common.css',
               'css/button.css',
               'css/container.css',
               'css/table.css',
               'css/customer.css'

           );
$css_files[]='theme.css.php';
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
              'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'customer_split_view.js.php'
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('customer_a',$customer_a);
$smarty->assign('customer_b',$customer_b);


$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');








if ($modify and !$customer_a['deleted'] and !$customer_b['deleted']) {
    $can_merge=true;
} else {
    $can_merge=false;
}

$smarty->assign('can_merge',$can_merge);
$smarty->assign('options_box_width','550px');

//$smarty->assign('id_a',$myconf['customer_id_prefix'].sprintf("%05d",$customer_a->id));
//$smarty->assign('id_b',$myconf['customer_id_prefix'].sprintf("%05d",$customer_b->id));


if (!$customer_a['deleted'] and !$customer_b['deleted']) {




    if (isset($_REQUEST['p']) and isset($_REQUEST['score'])) {

        if ($_REQUEST['p']=='cs') {

            $order=$_SESSION['state']['customers']['correlations']['order'];
            $order_desc=$_SESSION['state']['customers']['correlations']['order_dir'];
            $order_label=$order;
            if ($order=='name_a') {
                $order_field='`Customer A Name`';
                $order="`Customer A Name` $order_desc,`Customer A Key` $order_desc";
                $order_label=_('Customer Name');
                $_order='name';
            }
            elseif($order=='name_b') {
                $order_field='`Customer B Name`';
                $order="`Customer B Name` $order_desc,`Customer B Key` $order_desc";
                $order_label=_('Customer Name');
                $_order='name';
            }
            elseif($order=='id_a') {
                $order_field='`Customer A Key`';
                $order="`Customer A Key` $order_desc";
                $order_label=_('Customer ID');
                $_order='id';
            }
            elseif($order=='id_b') {
                $order_field='`Customer B Key`';
                $order="`Customer B Key` $order_desc";
                $order_label=_('Customer ID');
                $_order='id';
            }
            else {
                $order_field='`Correlation`';
                $order="`Correlation` $order_desc ,`Customer A Key` desc ";
                $order_label=_('Correlation');
            }

            if ($order=='name_a' or $order=='id_a') {

                $sql=sprintf("select `Customer A Key` as id_a,`Customer B Key` as id_b,`Correlation` as score,`Customer A Name` as name_a,`Customer B Name` as name_b  from `Customer Correlation`   where `Customer A Key`!=%d  and `Customer B Key`!=%d  and   %s <= %s   order by %s   limit 1",$customer_a['id'],$customer_b['id'],$order_field,prepare_mysql($customer_a[$_order]),$order);
                $result=mysql_query($sql);
                if (!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
                    $prev=array('id_a'=>0,'id_b'=>'0');
                mysql_free_result($result);
                $smarty->assign('prev',$prev);

                $sql=sprintf("select `Customer A Key` as id_a,`Customer B Key` as id_b,`Correlation` as score,`Customer A Name` as name_a,`Customer B Name` as name_b  from `Customer Correlation`     where `Customer A Key`!=%d  and `Customer B Key`!=%d  and   %s>=%s   order by %s   ",$customer_a['id'],$customer_b['id'],$order_field,prepare_mysql($customer_a[$_order]),$order);

                $result=mysql_query($sql);
                if (!$next=mysql_fetch_array($result, MYSQL_ASSOC))
                    $next=array('id_a'=>0,'id_b'=>'0');
                mysql_free_result($result);
                $smarty->assign('parent_info',"p=cs&");

            }
            elseif($order=='name_b' or $order=='id_b') {

                $sql=sprintf("select `Customer A Key` as id_a,`Customer B Key` as id_b,`Correlation` as score,`Customer A Name` as name_a,`Customer B Name` as name_b  from `Customer Correlation`   where `Customer A Key`!=%d  and `Customer B Key`!=%d  and   %s <= %s  order by %s   limit 1",$customer_a['id'],$customer_b['id'],$order_field,prepare_mysql($customer_a[$_order]),$order);
                $result=mysql_query($sql);
                if (!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
                    $prev=array('id_a'=>0,'id_b'=>'0');
                mysql_free_result($result);

                $sql=sprintf("select `Customer A Key` as id_a,`Customer B Key` as id_b,`Correlation` as score,`Customer A Name` as name_a,`Customer B Name` as name_b  from `Customer Correlation`     where `Customer A Key`!=%d  and `Customer B Key`!=%d  and   %s>=%s  order by %s   ",$customer_a['id'],$customer_b['id'],$order_field,prepare_mysql($customer_b[$_order]),$order);

                $result=mysql_query($sql);
                if (!$next=mysql_fetch_array($result, MYSQL_ASSOC))
                    $next=array('id_a'=>0,'id_b'=>'0');
                mysql_free_result($result);

            }
            else {
         
                $sql=sprintf("select `Customer A Key` as id_a,`Customer B Key` as id_b,`Correlation` as score,`Customer A Name` as name_a,`Customer B Name` as name_b  from `Customer Correlation`   where  `Customer A Key`!=%d  and `Customer B Key`!=%d  and   %s <= %s  and   `Customer A Key` <= %s  order by %s   limit 1",$customer_a['id'],$customer_b['id'],$order_field,prepare_mysql($_REQUEST['score']),$customer_a['id'],$order);
                //  print $sql;
                $result=mysql_query($sql);
                if (!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
                    $next=array('id_a'=>0,'id_b'=>'0','name_a'=>'','name_b'=>'');
                mysql_free_result($result);
                $smarty->assign('prev',$prev);

                $sql=sprintf("select `Customer A Key` as id_a,`Customer B Key` as id_b,`Correlation` as score,`Customer A Name` as name_a,`Customer B Name` as name_b  from `Customer Correlation`     where `Customer A Key`!=%d  and `Customer B Key`!=%d  and   %s>=%s  and  `Customer A Key` >= %s order by %s   ",$customer_a['id'],$customer_b['id'],$order_field,prepare_mysql($_REQUEST['score']),$customer_a['id'],$order);

                $result=mysql_query($sql);
                if (!$next=mysql_fetch_array($result, MYSQL_ASSOC))
                    $next=array('id_a'=>0,'id_b'=>'0','name_a'=>'','name_b'=>'');
                mysql_free_result($result);
                 $smarty->assign('next',$next);

            }
            $smarty->assign('parent_info',"p=cs&");

            if ($_SESSION['state']['customers']['correlations']['order_dir']=='desc') {

                $_prev=$prev;
                $prev=$next;
                $next=$_prev;
            }

            //$smarty->assign('prev',$prev);
            //$smarty->assign('next',$next);

            //$smarty->assign('my_parent_url','customers_stats.php?store='.$store->id);
            //$parent_title=$store->data['Store Code'].' '._('Potential Duplicates').' ('.$order_label.')';
            // $smarty->assign('my_parent_title',$parent_title);

        }



    }

}



$smarty->assign('parent','customers');
$smarty->assign('title',_('Customer Split View'));


$smarty->display('customer_split_view.tpl');

?>
