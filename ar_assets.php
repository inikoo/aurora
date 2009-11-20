<?php
/*
 File: ar_assets.php

 Ajax Server Anchor for the Product,Family,Department and Part Clases

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/

require_once 'common.php';
//require_once 'stock_functions.php';
require_once 'class.Product.php';
require_once 'class.Department.php';
require_once 'class.Family.php';

require_once 'class.Order.php';
require_once 'class.Location.php'
;
require_once 'class.PartLocation.php';
//require_once 'common_functions.php';
//require_once 'ar_common.php';





if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('charges'):
    list_charges();
    break;
case('campaigns'):
    list_campaigns();
    break;
case('deals'):
    list_deals();
    break;


case('product_history'):
    list_asset_history('product');
    break;
case('store_history'):
    list_asset_history('store');
    break;
case('department_history'):
    list_asset_history('department');
    break;
case('family_history'):
    list_asset_history('family');
    break;
case('product_server'):
    list_products_with_same_code();
    break;
case('customers_per_store'):
    list_customers_per_store();
    break;
case('orders_per_store'):
    list_orders_per_store();
    break;
case('product_code_timeline'):
  product_code_timeline();
  break;
case('product_subcategories'):
  list_product_subcategories();
  break;

case('order_received'):
case('order_expected'):
case('order_checked'):
case('order_cancelled'):
case('order_consolidated'):
    $data=array(
              'user_id'=>$LU->getProperty('auth_user_id'),
              'done_by'=>(!isset($_REQUEST['done_by'])?$LU->getProperty('auth_user_id'):json_decode(preg_replace('/\\\"/','"',$_REQUEST['done_by']),true)),
              'date'=>$_REQUEST['date'],
              'time'=>$_REQUEST['time']
          );



    $order=new order($_REQUEST['tipo_order'],$_REQUEST['order_id']);
    if (!$order->id) {
        $response= array('state'=>400,'msg'=>_('Error: Order not found'));
        echo json_encode($response);
        exit;
    }
    $_tipo=preg_replace('/^order\_/','date_',$tipo);
    $_tipo2=preg_replace('/^order\_/','',$tipo);
    $res=$order->set($_tipo,$data);

    if ($res['ok']) {
        $order->load('supplier');
        $response= array('state'=>200,'date'=>$order->data['dates'][$_tipo2],'title'=>$order->supplier->data['code']."<br/>"._('Purchase Order')." ".$order->id." (".$order->data['status'].")",);


    } else {
        $response= array('state'=>400,'msg'=>$res['msg']);
    }
    echo json_encode($response);

    break;


case('order_submit'):
    $data=array(
              'user_id'=>$LU->getProperty('auth_user_id'),
              'sdate'=>$_REQUEST['date'],
              'stime'=>$_REQUEST['time']
          );
    $order=new order($_REQUEST['tipo_order'],$_REQUEST['order_id']);
    if (!$order->id) {
        $response= array('state'=>400,'msg'=>_('Error: Order not found'));
        echo json_encode($response);
        exit;
    }
    $res=$order->set('date_submited',$data);
    $res_bis=array('ok'=>true);

    if ($_REQUEST['edate']!='' and $res['ok']) {
        $res_bis=$order->set('date_expected',array('date'=>$_REQUEST['edate'],'user_id'=>$LU->getProperty('auth_user_id'),'history'=>false));
        // print_r( $res_bis);
    }
    if ($res['ok']) {
        $order->load('supplier');
        $response= array(
                       'state'=>200,
                       'date_submited'=>$order->data['dates']['submited'],
                       'ts_submited'=>$order->data['date_submited'],
                       'title'=>$order->supplier->data['code']."<br/>"._('Purchase Order')." ".$order->id." (".$order->data['status'].")",
                       'ts_expected'=>$order->data['date_expected'],
                       'date_expected'=>$order->data['dates']['expected']
                                       //		      'msg'=>print_r($res_bis)
                   );
        if ($_REQUEST['tipo_order']=='po')
            $_SESSION['state']['po']['new']='';
    } else {
        $response= array('state'=>400,'msg'=>$res['msg']);
    }
    echo json_encode($response);

    break;

case('order_add_item'):
    $data=array(
              'user_id'=>$LU->getProperty('auth_user_id'),
              'product_id'=>$_REQUEST['product_id'],
              'qty'=>$_REQUEST['qty']
          );
    $order=new order($_REQUEST['tipo_order'],$_REQUEST['order_id']);
    if (!$order->id) {
        $response= array('state'=>400,'msg'=>_('Error: Order not found'));
        echo json_encode($response);
        exit;
    }
    $res=$order->add_item($data);
    if ($res['ok']) {
        $response= array('state'=>200,'data'=>$order->data,'item_data'=>$res['item_data']);
    } else {
        $response= array('state'=>400,'msg'=>$res['msg']);
    }
    echo json_encode($response);

    break;
case('order_item_checked'):
    $data=array(
              'user_id'=>$LU->getProperty('auth_user_id'),
              'product_id'=>$_REQUEST['product_id'],
              'qty'=>$_REQUEST['qty']
          );
    $order=new order($_REQUEST['tipo_order'],$_REQUEST['order_id']);
    if (!$order->id) {
        $response= array('state'=>400,'msg'=>_('Error: Order not found'));
        echo json_encode($response);
        exit;
    }
    $res=$order->item_checked($data);
    if ($res['ok']) {
        $response= array('state'=>200,'data'=>$order->data,'item_data'=>$res['item_data']);
    } else {
        $response= array('state'=>400,'msg'=>$res['msg']);
    }
    echo json_encode($response);

    break;
case('sincro_pages'):
    $product=new product($_SESSION['state']['product']['id']);
    $product->save($tipo,array('user_id'=>$LU->getProperty('auth_user_id')));
    $response= array(
                   'ok'=>true,
                   'msg'=>_('Pages checked')
               );
    echo json_encode($response);
    break;
    break;
case('ep_update'):
    $data[]=array(
                'key'=>$_REQUEST['key'],
                'value'=>$_REQUEST['value']
            );
    //     print_r($data);
    if (isset($_REQUEST['sup_cost']))
        $data[0]['sup_cost']=$_REQUEST['sup_cost'];
    if (isset($_REQUEST['sup_code']))
        $data[0]['sup_code']=$_REQUEST['sup_code'];
    if (isset($_REQUEST['image_id']))
        $data[0]['image_id']=$_REQUEST['image_id'];
    if (isset($_REQUEST['price']))
        $data[0]['price']=$_REQUEST['price'];
    if (isset($_REQUEST['odim']))
        $data[0]['odim']=$_REQUEST['odim'];
    if (isset($_REQUEST['oweight']))
        $data[0]['oweight']=$_REQUEST['oweight'];

    if ($_REQUEST['key']=='img_new') {
        if ($_FILES['testFile']['tmp_name']=='') {
            $response= array(
                           'ok'=>false,
                           'msg'=>_('No file')
                       );
            echo json_encode($response);
            break;
        }

        $target_path = "uploads/".$_REQUEST["PHPSESSID"].'_'.date('U');
        if (move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {

        }
        $data[0]['value']=$target_path;

    }

    $product=new product($_SESSION['state']['product']['id']);
    $_res=$product->update($data);
    // print_r($_res);
    $res=$_res[$_REQUEST['key']];
    if ($res['ok']) {
        $res['msg']=$product->save($_REQUEST['key'],array('user_id'=>$LU->getProperty('auth_user_id')));

        if ($_REQUEST['key']=='units') {

            if ($_res['price']['ok'])
                $res['msg'].='; '.$product->save('price',array('user_id'=>$LU->getProperty('auth_user_id')));
            else
                $res['msg'].='; '.$_res['price']['msg'];

            if ($_res['oweight']['ok'])
                $res['msg'].='; '.$product->save('oweight',array('user_id'=>$LU->getProperty('auth_user_id')));
            else
                $res['msg'].='; '.$_res['oweight']['msg'];

            if ($_res['odim']['ok'])
                $res['msg'].='; '.$product->save('odim',array('user_id'=>$LU->getProperty('auth_user_id')));
            else
                $res['msg'].='; '.$_res['odim']['msg'];
        }

    }


    if ($res['ok']) {
        $response= array(
                       'ok'=>true,
                       'msg'=>$res['msg']
                   );

        if ($_REQUEST['key']=='web_status') {
            $response['same']=$res['same'];
            $response['web_status']=$_web_status[$product->get('web_status')];

            $web_status_error=0;
            $web_status_error_title='';
            if ($product->get('web_status')=='onsale') {
                if (!($product->get('stock')>0)) {
                    $web_status_error=1;
                    $web_status_error_title=_('This product is out of stock');
                }
            } else {
                if ($product->get('stock')>0) {
                    $web_status_error=1;
                    $web_status_error_title=_('This product is not for sale on the webpage');
                }
            }
            $response['web_status_error']=$web_status_error;
            $response['web_status_error_title']=$web_status_error_title;
        }

        if ($_REQUEST['key']=='img_new') {
            $response['data']=$product->get('new_image');
            if ($product->get('num_images')==1)
                $response['is_principal']=1;
            else
                $response['is_principal']=0;
        }
        if ($_REQUEST['key']=='supplier_new') {
            $response['data']=$product->supplier[$product->new_supplier];
            $response['currency']=$myconf['currency_symbol'];
            $response['thousand_sep']=$myconf['thousand_sep'];
            $response['decimal_point']=$myconf['decimal_point'];

            $response['units_tipo_name']=$product->data['units_tipo_name'];
        }


        if ($_REQUEST['key']=='img_delete') {
            $response['new_principal']=$product->new_principal_img;
        }

    } else
        $response= array(
                       'ok'=>false,
                       'msg'=>$res['msg']
                   );

    echo json_encode($response);
    break;
case('pml_change_max_units'):
    $data[]=array(
                'key'=>$_REQUEST['key'],
                'value'=>$_REQUEST['value'],
                'p2l_id'=>$_REQUEST['p2l_id']
            );

    $product=new product($_SESSION['state']['product']['id']);
    $_res=$product->update($data);
    $res=$_res[$_REQUEST['key']];

    if ($res['ok']) {
        $res['msg']=$product->save($_REQUEST['key'],array('user_id'=>$LU->getProperty('auth_user_id')));
        $response= array(
                       'ok'=>true,
                       'msg'=>$res['msg'],
                       'max_units'=>$product->get('max_units_per_location',array('id'=>$_REQUEST['p2l_id']))
                   );
    } else
        $response= array(
                       'ok'=>false,
                       'msg'=>$res['msg']
                   );

    echo json_encode($response);
    break;
case('pml_change_location'):
    $data=array(

              'p2l_id'=>$_REQUEST['id'],
              'new_location_name'=>$_REQUEST['new_location_name'],
              'msg'=>$_REQUEST['msg'],

              'user_id'=>$LU->getProperty('auth_user_id'),
              'tipo'=>'change_location'
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);
    if ($res[0])
        $response= array(
                       'state'=>200,
                       'data'=>$res[1],
                       'new_location_id'=>$res[2]
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );
    echo json_encode($response);
    break;
case('pml_unlink'):
    $data=array(
              'tipo'=>'unlink', 'user_id'=>$LU->getProperty('auth_user_id')
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);
    if ($res[0])
        $response= array(
                       'state'=>200,
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );
    echo json_encode($response);
    break;
case('pml_link'):
    $data=array(
              'product_id'=>$_REQUEST['product_id'], 'user_id'=>$LU->getProperty('auth_user_id'),
              'tipo'=>'link'
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);
    if ($res['ok'])
        $response= array(
                       'state'=>200,
                       'master_id'=>$res['master_id'],
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res['msg']
                   );
    echo json_encode($response);
    break;

case('pml_change_qty'):
    $data=array(
              'p2l_id'=>$_REQUEST['id'],
              'qty'=>$_REQUEST['qty'],
              'msg'=>$_REQUEST['msg'],
              'user_id'=>$LU->getProperty('auth_user_id'),
              'tipo'=>'change_qty'
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);
    if ($res[0])
        $response= array(
                       'state'=>200,
                       'data'=>$res[1],
                       'stock'=>$res[2],
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );
    echo json_encode($response);
    break;
case('pml_increse_picking_rank'):
    $data=array(

              'product2location_id'=>$_REQUEST['id'],
              'rank'=>'-1',
              'user_id'=>$LU->getProperty('auth_user_id'),
              'tipo'=>'set_picking_rank'
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);
    if ($res[0])
        $response= array(
                       'state'=>200,
                       'data'=>$res[1]
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );
    echo json_encode($response);
    break;
case('pml_swap_picking'):
    $data=array(

              'p2l_id'=>$_REQUEST['id'],
              'action'=>$_REQUEST['action'],
              'user_id'=>$LU->getProperty('auth_user_id'),
              'tipo'=>'swap_picking'
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);
    if ($res[0])
        $response= array(
                       'state'=>200,
                       'data'=>$res[1]
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );
    echo json_encode($response);
    break;
case('pml_desassociate_location'):


    $id=$_REQUEST['id'];
    $part_location=new PartLocation(array('LocationPart'=>$id));
    $data=array(
              'date'=>'',
              'user_id'=>$LU->getProperty('auth_user_id'),
              'note'=>$_REQUEST['msg']
          );

    $response= array(
                   'state'=>200,
                   'data'=>''
               );


    echo json_encode($response);
    break;



case('pml_new_location'):

    if (isset($_REQUEST['product_id']))
        $product_id=$_REQUEST['product_id'];
    else
        $product_id=$_SESSION['state']['product']['id'];


    if (isset($_REQUEST['location_id'])) {
        $sql=sprintf("select name from location where id=%d",$_REQUEST['location_id']);
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $location_name=$row['name'];
        }

    } else
        $location_name=$_REQUEST['location_name'];

    $data=array(
              //    'product_id'=>$product_id,
              'location_name'=>$location_name,
              'is_primary'=>($_REQUEST['is_primary']=='true'?true:false),
              'user_id'=>$LU->getProperty('auth_user_id'),
              'can_pick'=>($_REQUEST['can_pick']=='true'?true:false),
              'tipo'=>'associate_location'
          );
    $product=new product($product_id);
    $res=$product->update_location($data);

    if ($data['can_pick']) {
        $tipo_img='art/icons/basket.png';
        if ($data['is_primary'])
            $row=1;
        else
            $row=$res[1]['num_physical'];
    } else {
        $row=$res[1]['num_physical'];
        $tipo_img='art/icons/basket_delete.png';
    }

    if ($res[0]) {
        // calculate the numer of products on this location
        $location=new Location($res[2]);

        $response= array(
                       'where'=>$row,
                       'state'=>200,
                       'data'=>$res[1],
                       'name'=>$res[3],
                       'tipo'=>$_location_tipo[$res[4]],
                       'picking_rank'=>$res[5],
                       'tipo_rank'=>$res[6],
                       'can_pick'=>$res[8],
                       'rank_img'=>$tipo_img,
                       'id'=>$res[2],
                       'pl_id'=>$res[7],
                       'num_products'=>$location->get('num_produts'),
                       'stock'=>$location->get('has_stock')
                   );
    } else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );
    echo json_encode($response);
    break;


case('pml_damaged_stock'):
    $data=array(

              'from'=>$_REQUEST['from'],
              'qty'=>$_REQUEST['qty'],
              'user_id'=>$LU->getProperty('auth_user_id'),
              'message'=>$_REQUEST['message'],
              'tipo'=>'damaged_stock'
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);

    if ($res[0])
        $response= array(
                       'state'=>200,
                       'data'=>$res[1]
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );
    echo json_encode($response);
    break;

case('pml_move_stock'):
    $data=array(

              'from'=>$_REQUEST['from'],
              'to'=>$_REQUEST['to'],
              'qty'=>$_REQUEST['qty'],
              'user_id'=>$LU->getProperty('auth_user_id'),
              'tipo'=>'move_stock'
          );
    $product=new product($_SESSION['state']['product']['id']);
    $res=$product->update_location($data);

    if ($res[0])
        $response= array(
                       'state'=>200,
                       'data'=>$res[1]
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>$res[1]
                   );



    echo json_encode($response);


    break;
case('pml_move_multiple_stocks'):
    $_data=preg_replace('/\\\"/','"',$_REQUEST['data']);
    $_data=json_decode($_data,true);
    $to_name=$_REQUEST['toname'];
    $ok=true;
    $error_msg='';

    foreach($_data as $id=>$value) {

        $data=array(
                  'qty'=>$value['qty'],
                  'from_id'=>$id,
                  'to_name'=>$to_name,
                  'user_id'=>$LU->getProperty('auth_user_id'),
                  'tipo'=>'move_stock_to'
              );



        $product=new product($value['product_id']);
        $res=$product->update_location($data);

        if (!$res[0]) {

            $ok=false;
            $error_msg.='; '.$res[1];
        }
    }

    if ($ok)
        $response= array(
                       'state'=>200,
                       'data'=>''
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>_('Some errors ocurred').$error_msg
                   );



    echo json_encode($response);


    break;

case('pml_audit_stocks'):
    $_data=preg_replace('/\\\"/','"',$_REQUEST['data']);
    $_data=json_decode($_data,true);

    $ok=true;



    foreach($_data as $id=>$value) {


        $part_location=new PartLocation(array('LocationPart'=>$id));
        $msg=($_REQUEST['msg1']!=''?'; '.$_REQUEST['msg1']:'').($_REQUEST['msg2']!=''?'; '.$_REQUEST['msg2']:'').($value['msg']!=''?'; '.$value['msg']:'');
        $msg=preg_replace('/^\;\s*/','',$msg);
        $data=array(
                  'qty'=>$value['qty']
                        ,'note'=>$msg
                                ,'user key'=>$LU->getProperty('auth_user_id')
                                            ,'date'=>''
                                                    ,'options'=>''
              );
        $part_location->audit($data);

        //      $data=array(
        // 	       'qty'=>$value['qty'],
//  	       'msg'=>$msg,
//  	       'LocationPart'=>$id,
//  	       'user_id'=>$LU->getProperty('auth_user_id'),
//  	       'tipo'=>'change_qty'
//  	       );


//    $product=new product($value['product_id']);
//    $res=$product->update_location($data);
//    $error_msg='';
//    if(!$res[0]){
//      $ok=false;
//      $error_msg=';'.$res[1];
//    }

    }
    // if($ok)
    $response= array(
                   'state'=>200,
                   'data'=>''
               );
//   else
//      $response= array(
// 		      'state'=>400,
// 		      'msg'=>_('Some errors ocurred')
// 		      );



    echo json_encode($response);


    break;
case('pml_multiple_damaged'):
    $_data=preg_replace('/\\\"/','"',$_REQUEST['data']);
    $_data=json_decode($_data,true);

    $ok=true;


    foreach($_data as $id=>$value) {

        $msg=($_REQUEST['msg1']!=''?$_REQUEST['msg1'].';':'').$value['msg'];
        $msg=preg_replace('/^\s*/','',$msg);
        $data=array(
                  'qty'=>$value['qty'],
                  'message'=>$msg,
                  'from'=>$id,
                  'user_id'=>$LU->getProperty('auth_user_id'),
                  'tipo'=>'damaged_stock'
              );



        $product=new product($value['product_id']);
        $res=$product->update_location($data);
        $error_msg='';
        if (!$res[0]) {
            $ok=false;
            $error_msg=';'.$res[1];
        }

    }

    if ($ok)
        $response= array(
                       'state'=>200,
                       'data'=>''
                   );
    else
        $response= array(
                       'state'=>400,
                       'msg'=>_('Some errors ocurred')
                   );



    echo json_encode($response);


    break;

case('products_name'):

    if (!isset($_REQUEST['query']) or $_REQUEST['query']=='') {
        $response= array(
                       'state'=>400,
                       'data'=>array()
                   );
        echo json_encode($response);
        return;
    }


    if (isset($_REQUEST['except']) and  isset($_REQUEST['except_id'])  and   is_numeric($_REQUEST['except_id'])) {

        if ($_REQUEST['except']=='location') {

            $sql=sprintf("select product.id as product_id,description,product.code,product2location.id as id,0 as qty from product left join product2location on (product.id=product_id) where product.code like   '%s%%'   and (select count(*) from product2location as p2l  where location_id=%s and p2l.product_id=product.id)=0   order by ncode ",addslashes($_REQUEST['query']),$_REQUEST['except_id']);
            $_data=array();
            $res=mysql_query($sql);
            while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $_data[]= array(
                              'scode'=>$data['code']
                                      ,'code'=>sprintf('<a href="product_manage_stock.php?id=%d">%s</a>',$data['product_id'],$data['code'])
                                              ,'description'=>$data['description']
                                                             ,'current_qty'=>sprintf('<span  used="0"  value="%s" id="s'.$data['id'].'"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['qty'],$data['qty'],$data['id'],$data['product_id'],number($data['qty']))
                                                                            ,'changed_qty'=>sprintf('<span   used="0" id="cs'.$data['id'].'"  onclick="change_reset(%d,%d)"   ">0</span>',$data['id'],$data['product_id'])
                                                                                           ,'new_qty'=>sprintf('<span  used="0"  value="%s" id="ns'.$data['id'].'"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['qty'],$data['qty'],$data['id'],$data['product_id'],number($data['qty']))
                                                                                                      ,'_qty_move'=>'<input id="qm'.$data['id'].'" onchange="qty_changed('.$data['id'].','.$data['product_id'].')" type="text" value="" size=3>'
                                                                                                                   ,'_qty_change'=>'<input id="qc'.$data['id'].'" onchange="qty_changed('.$data['id'].','.$data['product_id'].')" type="text" value="" size=3>'
                                                                                                                                  ,'_qty_damaged'=>'<input id="qd'.$data['id'].'" onchange="qty_changed('.$data['id'].','.$data['product_id'].')" type="text" value="" size=3>'
                                                                                                                                                  ,'note'=>'<input  id="n'.$data['id'].'" type="text" value="" style="width:100px">'
                                                                                                                                                          ,'delete'=>($data['qty']==0?'<img onclick="remove_prod('.$data['id'].','.$data['product_id'].')" style="cursor:pointer" title="'._('Remove').' '.$data['code'].'" alt="'._('Desassociate Product').'" src="art/icons/cross.png".>':'')
                                                                                                                                                                    ,'product_id'=>$data['product_id']
                          );
            }
            $response= array(
                           'state'=>200,
                           'data'=>$_data
                       );
            echo json_encode($response);


            break;



        }

    }
// else{


//      $sql=sprintf("select code from product where code like   '%s%%'  order by ncode ",$_REQUEST['query']);
//    }
//    //   print $sql;
//    $res=mysql_query($sql);
//    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
//      $data[]=array('code'=>$row['code']);
//    }


//    $response= array(
// 		    'state'=>200,
// 		    'data'=>$data
// 		    );
//    echo json_encode($response);


    break;


case('find_part'):

    find_part();







    break;


case('locations_name'):

    if (!isset($_REQUEST['query']) or $_REQUEST['query']=='') {
        $response= array(
                       'state'=>400,
                       'data'=>array()
                   );
        echo json_encode($response);
        return;
    }


    if (isset($_REQUEST['all']) and $_REQUEST['all']==1)
        $sql=sprintf("select name from location where name like '%s%%' ",$_REQUEST['query']);
    elseif(isset($_REQUEST['except_location'])) {
        $sql=sprintf("select * from location where name like '%s%%' and id!=%d  ",$_REQUEST['query'],$_REQUEST['except_location']);
    }
    else {

        if (!isset($_REQUEST['product_id']))
            $product_id=$_SESSION['state']['product']['id'];
        else
            $product_id=$_REQUEST['product_id'];
        $sql=sprintf("select * from location where name like '%s%%' and (select count(*) from product2location where location_id=location.id and product_id=%d)=0   ",$_REQUEST['query'],$product_id);
    }
    //   print $sql;
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[]=array('name'=>$row['name']);
    }


    $response= array(
                   'state'=>200,
                   'data'=>$data
               );
    echo json_encode($response);


    break;

case('update_product'):
    if (!isset($_REQUEST['product_id'])) {
        $response=array('state'=>400,'resp'=>_('Error'));
        echo json_encode($response);
        break;
    }

    include_once('class.product.php');
    $product_id=$_REQUEST['product_id'];

    $values=array();
    foreach($_REQUEST as $key=>$value) {
        if (preg_match('/^v_.*/i',$key)) {
            $key=preg_replace('/^v_/','',$key);
            $values[$key]=$value;
        }
    }
    $product=New product($product_id);
    $product->read('product_info');

    $result=  $product->update($values);


    $response= array(
                   'state'=>200,
                   'res'=>$result
               );
    echo json_encode($response);


    break;

case('editproductdetails'):

    $description=addslashes($_REQUEST['editor']);
    $product_id=$_REQUEST['product_id'];
    if ($description=='') {
        $response= array(
                       'state'=>400,
                       'desc'=>_('Nothing to add')
                   );
        echo json_encode($response);
        break;
    }
    $sql=sprintf("update product set description_med='%s' where id=%d",$description,$product_id);

    mysql_query($sql);
    //   print $_REQUEST['editor'];
    $response= array(
                   'state'=>200
               );
    echo json_encode($response);
    break;
case('changepic'):
    $new_id=$_REQUEST['new_id'];


    $sql=sprintf("select filename,format,id,product_id,caption from image where id=%d",$new_id);
    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $caption=$row['caption'];
        $product_id=$row['product_id'];
        $new_src='images/med/'.$row['filename'].'_med.'.$row['format'];
        $sql=sprintf("update image set principal=0 where product_id=%d",$product_id);
        mysql_query($sql);
        $sql=sprintf("update image set principal=1 where id=%d",$new_id);
        //     print $sql;
        mysql_query($sql);

        $sql=sprintf("select filename,id,format from image where product_id=%d and principal=0 limit 5",$product_id);
        $res2 = mysql_query($sql);
        $other_img_src=array('','','','','');
        $other_img_id=array(0,0,0,0,0);
        $num_others=0;
        while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
            $other_img_src[$num_others]='images/tb/'.$row2['filename'].'_tb.'.$row2['format'];
            $other_img_id[$num_others]=$row2['id'];
            $num_others++;
        }
        $response= array(
                       'state'=>200,
                       'new_src'=>$new_src,
                       'new_id'=>$new_id,
                       'other_img'=>$other_img_src,
                       'other_img_id'=>$other_img_id,
                       'others'=>$num_others,
                       'caption'=>$caption
                   );
        echo json_encode($response);
        break;
    }
    $response=array('resultset'=>
                                array(
                                    'state'=>400,
                                    'resp'=>_('Error')
                                )
                   );
    echo json_encode($response);
    break;

case('uploadpic'):

    $id=$_SESSION['state']['product']['id'];

    $product= new Product($id);
    $product->load('images');
    $code=$product->get('code');
    $target_path = "uploads/".$_REQUEST["PHPSESSID"].'_'.date('U');
    if (move_uploaded_file($_FILES['testFile']['tmp_name'], $target_path)) {
        $im = @imagecreatefromjpeg($target_path);
        if ($im) {
            $w = imagesx($im);
            $h = imagesy($im);

            if ($h > 0) {
                $r = $w/$h;
                $s=filesize($target_path);
                $c=md5_file($target_path);



                //   print "$images $w $h $s $c";
                $images=$product->get('num_images');
                //	     print $images;
                imagejpeg($im,'app_files/images/original/'.$code.'_'.$images.'_orig.jpg');

                $med_maxh=130;
                $med_maxw=190;
                $tb_maxh=21;
                $tb_maxw=30;


                if ($r>1.4615) {
                    $med_w=$med_maxw;
                    $med_h=$med_w/$r;
                    $tb_w=$tb_maxw;
                    $tb_h=$tb_w/$r;

                } else {

                    $med_h=$med_maxh;
                    $med_w=$med_h*$r;
                    $tb_h=$tb_maxh;
                    $tb_w=$tb_h*$r;
                }



                return;
                $im_med = imagecreatetruecolor($med_w, $med_h);
                imagecopyresampled($im_med, $im, 0, 0, 0, 0, $med_w, $med_h, $w, $h);
                imagejpeg($im_med,$this->image_path.'med/'.$code.'_'.$images.'_med.jpg');
                $im_tb = imagecreatetruecolor($tb_w, $tb_h);
                imagecopyresampled($im_tb, $im, 0, 0, 0, 0, $tb_w, $tb_h, $w, $h);
                imagejpeg($im_tb,$this->image_path.'tb/'.$code.'_'.$images.'_tb.jpg');

            }
        }
    }



//   $sql=sprintf("select code from product where id=%d",$id);
//    $res = mysql_query($sql);
//    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
//      $code=strtolower($row['code']);
//      $target_path = "uploads/";
//      $target_path = $target_path . $_REQUEST["PHPSESSID"].date('U');
//      if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
//        $im = @imagecreatefromjpeg($target_path);
//        if ($im) {
// 	 $w = imagesx($im);
// 	 $h = imagesy($im);

// 	 if($h > 0)
// 	   { $r = $w/$h;

// 	     $s=filesize($target_path);
// 	     $c=md5_file($target_path);




// 	     $sql=sprintf("select checksum  from image where  product_id=%d",$id);
// 	     $res2 = mysql_query($sql);
// 	     while($row2=mysql_fetch_array($res2, MYSQL_ASSOC))) {
// 	       if($c==$row2['checksum']){
// 		 $response=array('state'=>400,'resp'=>_('Image already uploaded'));
// 		 echo json_encode($response);
// 		 break 2;
// 	       }

// 	     }



// 	     $sql=sprintf("select count(*) as num from image where  product_id=%d",$id);
// 	     $res2 = mysql_query($sql);
// 	     if($row2=mysql_fetch_array($res2, MYSQL_ASSOC))) {
// 	       $images=$row2['num'];
// 	     }


// 	     $med_maxh=130;
// 	     $med_maxw=190;
// 	     $tb_maxh=21;
// 	     $tb_maxw=30;


// 	     //   print "$images $w $h $s $c";
// 	     imagejpeg($im,'images/original/'.$code.'_'.$images.'_orig.jpg');

// 	     if($r>1.4615){
// 	       $med_w=$med_maxw;
// 	       $med_h=$med_w/$r;
// 	       $tb_w=$tb_maxw;
// 	       $tb_h=$tb_w/$r;

// 	     }else{

// 	       $med_h=$med_maxh;
// 	       $med_w=$med_h*$r;
// 	       $tb_h=$tb_maxh;
// 	       $tb_w=$tb_h*$r;
// 	     }

// 	     $im_med = imagecreatetruecolor($med_w, $med_h);
// 	     imagecopyresampled($im_med, $im, 0, 0, 0, 0, $med_w, $med_h, $w, $h);
// 	     imagejpeg($im_med,'images/med/'.$code.'_'.$images.'_med.jpg');
// 	     $im_tb = imagecreatetruecolor($tb_w, $tb_h);
// 	     imagecopyresampled($im_tb, $im, 0, 0, 0, 0, $tb_w, $tb_h, $w, $h);
// 	     imagejpeg($im_tb,'images/tb/'.$code.'_'.$images.'_tb.jpg');


// 	     $sql=sprintf("update image set principal=0 where product_id=%d",$id);
// 	     mysql_query($sql);

// 	     $caption=$_REQUEST['caption'];
// 	     $sql=sprintf("insert into image (filename,product_id,width,height,size,checksum,caption,principal) values ('%s',%d,%d,%d,%d,'%s','%s',1)",$code.'_'.$images,$id,$w,$h,$s,$c,addslashes($caption));
// 	     mysql_query($sql);
// 	     $new_id =  mysql_insert_id();
// 	     // make the new pric the pricipal





// 	     $sql=sprintf("select filename,id,format from image where product_id=%d and principal=0 limit 5",$id);

// 	     $res2 = mysql_query($sql);
// 	     $other_img_src=array('','','','','');
// 	     $other_img_id=array(0,0,0,0,0);
// 	     $num_others=0;
// 	     while($row2=mysql_fetch_array($res2, MYSQL_ASSOC))) {
// 	       $other_img_src[$num_others]='images/tb/'.$row2['filename'].'_tb.'.$row2['format'];
// 	       $other_img_id[$num_others]=$row2['id'];
// 	       $num_others++;
// 	     }
// 	      $num_others++;

// 	     $response=array(
// 			   'state'=>200,
// 			   'new_src'=>'images/med/'.$code.'_'.$images.'_med.jpg',
// 			   'new_id'=>$new_id,
// 			   'other_img'=>$other_img_src,
// 			   'other_img_id'=>$other_img_id,
// 			   'others'=>$num_others,
// 			   'caption'=>$caption

// 		     );
// 	     echo json_encode($response);
// 	     break;









// 	   }

//        }



//        // save the original (expeced to be  a big file)


//        //       print "$w $h";


//      }else{
//        $response=array('state'=>400,'resp'=>_('Error'));
//        echo json_encode($response);
//        break;
//      }

//    }
//  $response=array('state'=>400,'resp'=>_('Error'));
//        echo json_encode($response);
//        break;

    break;

case('search'):
    $q=$_REQUEST['q'];
    $sql=sprintf("select id from product where code='%s' ",addslashes($q));
    $result=mysql_query($sql);
    if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $url='product.php?id='. $found['id'];
        echo json_encode(array('state'=>200,'url'=>$url));
        break;
    }
    $sql=sprintf("select id from product_group where name='%s' ",addslashes($q));
    $result=mysql_query($sql);
    if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $url='family.php?id='. $found['id'];
        echo json_encode(array('state'=>200,'url'=>$url));
        break;
    }

    // try to get similar results
    //   if($myconf['product_code_separator']!=''){
    if (  ($myconf['product_code_separator']!='' and   preg_match('/'.$myconf['product_code_separator'].'/',$q)) or  $myconf['product_code_separator']==''  ) {
        $sql=sprintf("select levenshtein(UPPER(%s),UPPER(code)) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(code))) as dist2,        code,id from product  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
        $result=mysql_query($sql);
        if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($found['dist1']<3) {
                echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="product.php?id='.$found['id'].'">'.$found['code'].'</a>'));
                break;
            }
        }


    } else {
        // look on the family list
        $sql=sprintf("select levenshtein(UPPER(%s),UPPER(name)) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(name))) as dist2, name ,id from product_group  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
        $result=mysql_query($sql);
        if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($found['dist1']<3) {
                echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="family.php?id='.$found['id'].'">'.$found['name'].'</a> '._('family') ));
                break;
            }
        }

    }
    echo json_encode(array('state'=>500,'msg'=>_('Product not found')));
    break;



    break;
case('changetableview'):
    if (isset($_REQUEST['value'])) {
        $value=$_REQUEST['value'];
        if (is_numeric($value) and $value>=0 and $value<3)
            $_SESSION['views']['assets_tables']=$value;


    }
    break;
case('changeproductplot'):
    if (isset($_REQUEST['value'])) {
        $value=$_REQUEST['value'];
        $_SESSION['views']['product_plot']="$value";

    }
    break;
case('changeproductblock'):
    if (isset($_REQUEST['value']) and isset($_REQUEST['block'])) {
        $value=$_REQUEST['value'];
        $block=$_REQUEST['block'];


        if (is_numeric($value) and ($value==0 or $value==1)    and is_numeric($block) and $value>=0 and $value<6      )
            $_SESSION['views']['product_blocks'][$block]=$value;
    }
    break;




case('codefromsup'):
    $q=addslashes($_REQUEST['query']);
    $sql="select product.id,code,description from product left join product2supplier on (product_id=product.id) where product2supplier.supplier_id=2 and code like '$q%' ";
    //     print $sql;
    $res = mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[]=array(
                    'id'=>$row['id'],
                    'code'=>$row['code'],
                    'description'=>$row['description']
                );
    }
    $response=array('resultset'=>
                                array(
                                    'state'=>200,
                                    'data'=>$data
                                )
                   );
    echo json_encode($response);
    break;




case('prodindex'):
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$_SESSION['tables']['pindex_list'][3];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$_SESSION['tables']['pindex_list'][2];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$_SESSION['tables']['pindex_list'][0];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$_SESSION['tables']['pindex_list'][1];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$_SESSION['tables']['pindex_list'][4];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$_SESSION['tables']['pindex_list'][5];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$_SESSION['tables']['pindex_list'][6];






    $_SESSION['tables']['pindex_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);

    $wheref='';
    if ($f_field=='p.code' or $f_field=='g.name'  or $f_field=='g.code'    and $f_value!='')
        $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
    elseif($f_field=='p.description'    and $f_value!='')
    $wheref.=" and  ".$f_field." like '%".addslashes($f_value)."%'";

//   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
//     if($_REQUEST['f_field']=='p.code' or $_REQUEST['f_field']=='g.name' or $_REQUEST['f_field']=='d.code'){
//       if($_REQUEST['f_value']!='')
// 	$where=" where  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
//     }elseif($_REQUEST['f_field']=='p.description'){
//       if($_REQUEST['f_value']!='')
// 	$where=" where  ".$_REQUEST['f_field']."  like '%".addslashes($_REQUEST['f_value'])."%'";
//     }
//   }



    $sql="select count(*) as total from product  as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) $where $wheref ";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($where=='') {
        $filtered=0;
    } else {

        $sql="select count(*) as total from product  as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id)  $where ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
        }

    }



    $sql="select p.awoutq,p.awtsq,p.price,p.units,ifnull(p.stock,-10000) as stock,p.condicion as condicion, p.code as code, p.id as id,p.description as description , group_id,department_id,g.name as fam, d.code as department
         from product as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id)  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
    //     print $sql;
    $result=mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[]=array(
                    'id'=>$row['id'],
                    'condicion'=>$row['condicion'],
                    'stock'=>floor($row['stock']),
                    'code'=>$row['code'],
                    'description'=>number($row['units']).'x '.$row['description'].' @'.money($row['price']),
                    'group_id'=>$row['group_id'],
                    'department_id'=>$row['department_id'],
                    'fam'=>$row['fam'],
                    'department'=>$row['department'],
                    'awtsq'=>money($row['awtsq']).' ('.number($row['awoutq'],1).')'


                );
    }

    if ($total<$number_results)
        $rtext=$total.' '.ngettext('record returned','records returned',$total);
    else
        $rtext='';
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      //			 'records_returned'=>$start_from+$res->numRows(),
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
    break;
    //==============================================================================================
case('families'):
    list_families();


    break;
    //========================================================================================
case('stores'):
    list_stores();
    break;
    //=====================================================================================
case('departments'):
    list_departments();

    break;


case('products'):
    list_products();

    break;
    //========================================================================
case('parts'):
    list_parts();

    break;
    //=============================================================================


// case('all_supplier_products'):
//    if(!$LU->checkRight(SUP_VIEW))
//     exit;

//    $conf=$_SESSION['state']['supplier_products']['table'];
//   if(isset( $_REQUEST['sf']))
//     $start_from=$_REQUEST['sf'];
//   else
//     $start_from=$conf['sf'];
//   if(isset( $_REQUEST['nr']))
//     $number_results=$_REQUEST['nr'];
//    else
//      $number_results=$conf['nr'];
//   if(isset( $_REQUEST['o']))
//     $order=$_REQUEST['o'];
//   else
//     $order=$conf['order'];
//   if(isset( $_REQUEST['od']))
//     $order_dir=$_REQUEST['od'];
//   else
//     $order_dir=$conf['order_dir'];
//   if(isset( $_REQUEST['f_field']))
//     $f_field=$_REQUEST['f_field'];
//    else
//      $f_field=$conf['f_field'];

//   if(isset( $_REQUEST['f_value']))
//     $f_value=$_REQUEST['f_value'];
//   else
//     $f_value=$conf['f_value'];
//   if(isset( $_REQUEST['where']))
//     $where=$_REQUEST['where'];
//    else
//      $where=$conf['where'];




//    if(isset( $_REQUEST['tableid']))
//      $tableid=$_REQUEST['tableid'];
//    else
//     $tableid=0;

//    if(isset( $_REQUEST['view'])){
//      $view=$_REQUEST['view'];
//      $_SESSION['state']['supplier_products']['view']=$view;
//    }else
//      $view=$_SESSION['state']['supplier_products']['view'];

//    if(isset( $_REQUEST['period'])){
//      $period=$_REQUEST['period'];
//      $_SESSION['state']['supplier_products']['period']=$period;
//    }else
//      $period=$_SESSION['state']['supplier_products']['period'];

//    if(isset( $_REQUEST['percentage'])){
//      $percentage=$_REQUEST['percentage'];
//      $_SESSION['state']['supplier_products']['percentage']=$percentage;
//    }else
//      $percentage=$_SESSION['state']['supplier_products']['percentage'];

//    $filter_msg='';
//    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
//    $_order=$order;
//    $_dir=$order_direction;

//    $product_percentage=$percentage;
//    $product_view=$view;
//    $product_period=$period;

//    $_SESSION['state']['supplier_products']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value

// 						    );





//   $wheref='';
//   if(($f_field=='code' ) and $f_value!='')
//     $wheref.=" and  `Supplier Product XHTML Used In` like '%".addslashes($f_value)."%'";
// if($f_field=='sup_code' and $f_value!='')
//     $wheref.=" and  `Supplier Product Code` like '".addslashes($f_value)."%'";






//   $sql="select count(*) as total from `Supplier Product Dimension`  $where $wheref ";

//   $result=mysql_query($sql);
//   if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

//    $total=$row['total'];
//   }
//     if($wheref==''){
//       $filtered=0; $total_records=$total;
//     }else{

//       $sql="select count(*) as total from `Supplier Product Dimension`  $where  ";
//       $result=mysql_query($sql);
//       if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
// 	$total_records=$row['total'];
// 	$filtered=$row['total']-$total;
//       }

//     }



//     $rtext=$total_records." ".ngettext('pruduct','products',$total_records);
//     if($total_records>$number_results)
//       $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
//     $filter_msg='';

//  switch($f_field){
//  case('code'):
//        if($total==0 and $filtered>0)
// 	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code")." <b>".$f_value."*</b>  <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Clear Filter')."</span>";
//        elseif($filtered>0)
// 	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with code')." <b>".$f_value."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
//        break;
//  case('sup_code'):
//        if($total==0 and $filtered>0)
// 	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with supplier code")." <b>".$f_value."*</b> ";
//        elseif($filtered>0)
// 	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with supplier code')." <b>".$f_value."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
//        break;

//  }
//  if($order=='id')
//    $order='`Supplier Product ID`';
//  elseif($order=='code')
//    $order='`Supplier Product Code`';
//  elseif($order='usedin')
//    $order='`Supplier Product XHTML Used In`';

//    $sql="select * from `Supplier Product Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

//    $data=array();

//    $result=mysql_query($sql);
//    while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){


//      if($product_period=='all'){
//        $profit=money($row['Supplier Product Total Parts Profit']);
//        $profit2=money($row['Supplier Product Total Parts Profit After Storing']);
//        $allcost=money($row['Supplier Product Total Cost']);
//        $used=number($row['Supplier Product Total Parts Used']);
//        $required=number($row['Supplier Product Total Parts Required']);
//        $provided=number($row['Supplier Product Total Parts Provided']);
//        $lost=number($row['Supplier Product Total Parts Lost']);
//        $broken=$row['Supplier Product Total Parts Broken'];
//      }else if($product_period=='year'){
//        $profit=money($row['Supplier Product 1 Year Acc Parts Profit']);
//        $profit2=money($row['Supplier Product 1 Year Acc Parts Profit After Storing']);
//        $allcost=money($row['Supplier Product 1 Year Acc Cost']);
//        $used=number($row['Supplier Product 1 Year Acc Parts Used']);
//        $required=number($row['Supplier Product 1 Year Acc Parts Required']);
//        $provided=number($row['Supplier Product 1 Year Acc Parts Provided']);
//        $lost=number($row['Supplier Product 1 Year Acc Parts Lost']);
//        $broken=number($row['Supplier Product 1 Year Acc Parts Broken']);
//      }else if($product_period=='quarter'){
//        $profit=money($row['Supplier Product 1 Quarter Acc Parts Profit']);
//        $profit2=money($row['Supplier Product 1 Quarter Acc Parts Profit After Storing']);
//        $allcost=money($row['Supplier Product 1 Quarter Acc Cost']);
//        $used=number($row['Supplier Product 1 Quarter Acc Parts Used']);
//        $required=number($row['Supplier Product 1 Quarter Acc Parts Required']);
//        $provided=number($row['Supplier Product 1 Quarter Acc Parts Provided']);
//        $lost=number($row['Supplier Product 1 Quarter Acc Parts Lost']);
//        $broken=number($row['Supplier Product 1 Quarter Acc Parts Broken']);
//      }else if($product_period=='month'){
//        $profit=money($row['Supplier Product 1 Month Acc Parts Profit']);
//        $profit2=money($row['Supplier Product 1 Month Acc Parts Profit After Storing']);
//        $allcost=money($row['Supplier Product 1 Month Acc Cost']);
//        $used=number($row['Supplier Product 1 Month Acc Parts Used']);
//        $required=number($row['Supplier Product 1 Month Acc Parts Required']);
//        $provided=number($row['Supplier Product 1 Month Acc Parts Provided']);
//        $lost=number($row['Supplier Product 1 Month Acc Parts Lost']);
//        $broken=number($row['Supplier Product 1 Month Acc Parts Broken']);
//      }else if($product_period=='week'){
//        $profit=money($row['Supplier Product 1 Week Acc Parts Profit']);
//        $profit2=money($row['Supplier Product 1 Week Acc Parts Profit After Storing']);
//        $allcost=money($row['Supplier Product 1 Week Acc Cost']);
//        $used=number($row['Supplier Product 1 Week Acc Parts Used']);
//        $required=number($row['Supplier Product 1 Week Acc Parts Required']);
//        $provided=number($row['Supplier Product 1 Week Acc Parts Provided']);
//        $lost=number($row['Supplier Product 1 Week Acc Parts Lost']);
//        $broken=number($row['Supplier Product 1 Week Acc Parts Broken']);
//      }

//      $supplier=sprintf('<a href="supplier.php?id=%d">%s</a>',$row['supplier key'],$row['supplier code']);
//      $data[]=array(
// 		   'id'=>sprintf('<a href="supplier_product.php?id=%d">%06d</a>',$row['Supplier Product Key'],$row['Supplier Product ID'])
// 		   ,'code'=>sprintf('<a href="supplier_product.php?id=%d">%s</a>',$row['Supplier Product Key'],$row['Supplier Product Code'])
// 		   ,'name'=>$row['Supplier Product Name']
// 		   ,'cost'=>money($row['Supplier Product Cost'])
// 		   ,'usedin'=>$row['Supplier Product XHTML Used In']
// 		   ,'profit'=>$profit
// 		   ,'allcost'=>$allcost
// 		   ,'used'=>$used
// 		   ,'required'=>$required
// 		   ,'provided'=>$provided
// 		   ,'lost'=>$lost
// 		   ,'broken'=>$broken
// 		   ,'supplier'=>$supplier
// 		   );
//    }


//    $response=array('resultset'=>
// 		   array('state'=>200,
// 			 'data'=>$data,
// 			 'sort_key'=>$_order,
// 			 'sort_dir'=>$_dir,
// 			 'tableid'=>$tableid,
// 			 'filter_msg'=>$filter_msg,
// 			 'rtext'=>$rtext,
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$total,
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
// 			 )
// 		   );
//    echo json_encode($response);
//    break;


case('withsupplier_po'):
    if (!$LU->checkRight(SUP_VIEW))
        exit;

    $conf=$_SESSION['state']['po']['items'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['id']))
        $supplier_id=$_REQUEST['id'];
    else
        $supplier_id=$_SESSION['state']['supplier']['id'];

    if (isset( $_REQUEST['po_id']))
        $po_id=$_REQUEST['po_id'];
    else
        $po_id=$_SESSION['state']['po']['id'];


    $all_products_supplier=false;


    if (isset( $_REQUEST['all_products_supplier'])) {
        $all_products_supplier=$_REQUEST['all_products_supplier'];

    } else
        $all_products_supplier=$conf['all_products_supplier'];

    $all_products=false;
    if (isset( $_REQUEST['all_products'])) {
        $all_products=$_REQUEST['all_products'];

    } else
        $all_products=$conf['all_products'];


    if ($all_products_supplier)
        $all_products=false;

    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $filter_msg='';
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;




    $_SESSION['state']['po']['items']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'all_products_supplier'=>$all_products_supplier,'all_products'=>$all_products);
    $_SESSION['state']['supplier']['id']=$supplier_id;



    if ($all_products_supplier)
        $where=$where.' and `Supplier Key`='.$supplier_id;
    elseif($all_products)
    $all_products_supplier=true;
    else {
        $f_value='';
        $where=$where.' and `Purchase Order Key`='.$po_id;

    }


    $wheref='';
    if (($f_field=='p.code' or $f_field=='sup_code') and $f_value!='')
        $wheref.=" and  p.code  like '".addslashes($f_value)."%'";
    if (($f_field=='sup_code') and $f_value!='')
        $wheref.=" and  sup_code like '".addslashes($f_value)."%'";





    if ($all_products_supplier)
        $sql="select count(*) as total from `Supplier Product Dimension` $where $wheref ";
    else
        $sql="select count(*) as total from `Purchase Order Transaction Fact` $where $wheref ";


    //   print $sql;

    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        if ($all_products_supplier)
            $sql="select count(*) as total from `Supplier Product Dimension`  $where  ";
        else
            $sql="select count(*) as total from `Purchase Order Transaction Fact`  $where $wheref ";
        $res = mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    if ($all_products_supplier)
        $rtext=$total_records." ".ngettext('products','products',$total_records);
    else
        $rtext=$total_records." ".ngettext('products in po','products in po',$total_records);
    if ($total_records>$number_results)
        $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
    $filter_msg='';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('sup_code'):
        case('p.code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('p.code'):
        case('sup_code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;

        }
    }
    else
        $filter_msg='';


    if ($all_products_supplier) {
        $sql="select count(*) as total from `Supplier Product Dimension`  $where $wheref ";

        // $sql="select p.units as punits,(select concat_ws('|',IFNULL(expected_price,''),IFNULL(expected_qty,''),IFNULL(price,''),IFNULL(qty,''),IFNULL(damaged,''),IFNULL(qty-damaged,'')) from porden_item where porden_id=$po_id and porden_item.p2s_id=ps.id) as po_data,   sup_code,ps.id as p2s_id,(p.units*ps.price) as price_outer,ps.price as price_unit,stock,p.condicion as condicion, p.code as code, p.id as id,p.description as description , group_id,department_id,g.name as fam, d.code as department
        //from product as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) left join product2supplier as ps on (product_id=p.id)  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

    } else {

        $sql="select *  from `Purchase Order Transaction Fact` POTF left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product Key`=POTF.`Supplier Product Key`)   $where $wheref ";

        // $sql=sprintf("select   (qty-damaged) as useful,  damaged,p.units as punits, expected_qty,expected_price, porden_item.price,qty  ,   sup_code,ps.id as p2s_id,(p.units*ps.price) as price_outer,ps.price as price_unit,stock,p.condicion as condicion, p.code as code, p.id as id,p.description as description , group_id,department_id,g.name as fam, d.code as department
        //from porden_item left join product2supplier as ps on ( p2s_id=ps.id)  left join product as p on (product_id=p.id)  left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id)  $where $wheref  order by $order $order_direction                   ");

    }

    $res = mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        if ($all_products_supplier) {
            if ($row['po_data']!='') {
                list($expected_price,$expected_qty,$price,$qty,$damaged,$useful)=preg_split('/\|/',$row['po_data']);
            } else {
                $expected_price='';
                $expected_qty='';
                $price='';
                $qty='';
                $damaged='';
                $useful='';

            }

        } else {
            $expected_price=$row['Purchase Order Amount'];
            $expected_qty=$row['Purchase Order Quantity'];
            $price=$row['price'];
            $qty=$row['qty'];
            $damaged=$row['damaged'];
            $useful=$row['useful'];
        }


        $diff=$qty-$expected_qty;
        if ($diff>0)
            $diff='+'.$diff;

        //($row['punits']!=1?number($row['stock']:''))

        $code='<a tabindex="2" href="product.php?id='.$row['id'].'">'.$row['code'].'</a>';



        $data[]=array(
                    'id'=>$row['id'],
                    'p2s_id'=>$row['p2s_id'],

                    'condicion'=>$row['condicion'],
                    'price_unit'=>"(".money($row['price_unit']).")",
                    'price_outer'=>money($row['price_outer']),
                    'stock'=>($row['stock']==''?'': ($row['stock']==0?0:     number($row['stock']).($row['punits']!=1?"(".number($row['stock']* $row['punits'] ).")":'')   )),
                    'code'=>$code,
                    'sup_code'=>$row['sup_code'],
                    'qty'=>"<span  style='color:#777'>".($qty==''?'':number($qty/$row['punits'],1)).'</span> ['.($qty==''?'':number($qty,1)).']',
                    'expected_qty_edit'=>"<span id='oqty".$row['p2s_id']."' style='color:#777'>".($expected_qty==''?'':number($expected_qty/$row['punits'],1)).'</span> <input type="text" value="'.($expected_qty==''?'':number($expected_qty,1)).'" onchange="value_changed(this)" size="3"  id="p'.$row['p2s_id'].'"  pid="'.$row['p2s_id'].'" class="aright" />',
                    'expected_qty'=>"<span  style='color:#777'>".(($expected_qty=='' or $row['punits']==1)?'':number($expected_qty/$row['punits'],1)).'</span> [<span id="eqty'.$row['p2s_id'].'"  onClick="eqty(this,'.$row['p2s_id'].','.$row['punits'].')">'.($expected_qty==''?'':number($expected_qty,1))."</span>]",
                    'diff'=>'<span id="diff'.$row['p2s_id'].'">'.$diff.'</span>',
                    'qty_edit'=>"<span id='ocqty".$row['p2s_id']."' style='color:#777'>".($qty==''?'':number($qty/$row['punits'],1)).'</span> <input type="text" value="'.($qty==''?'':number($qty,1)).'" onchange="value_checked(this,'.$row['p2s_id'].','.$row['punits'].','.($all_products?1:0).')" size="3"  id="qc'.$row['p2s_id'].'"  pid="'.$row['p2s_id'].'"  prodid="'.$row['id'].'"   class="aright" />',
                    'damaged_edit'=>"<span id='do".$row['p2s_id']."' style='color:#777'>".($qty==''?'':number($damaged/$row['punits'],1)).'</span> <input type="text" value="'.($damaged==''?'':number($damaged,1)).'" onchange="value_damaged(this,'.$row['p2s_id'].','.$row['punits'].')" size="3"  id="du'.$row['p2s_id'].'"  pid="'.$row['p2s_id'].'" class="aright" />',
                    'description'=>number($row['punits'])."x ".$row['description'],
                    'group_id'=>$row['group_id'],
                    'department_id'=>$row['department_id'],
                    'fam'=>$row['fam'],
                    'department'=>$row['department'],
                    'delete'=>'<img src="art/icons/link_delete.png"/>',
                    'price'=>"<span>".($qty==''?'':money($price))."</span>",
                    'usable'=>'<span id="uo'.$row['p2s_id'].'">'.($row['punits']==1?'':number($useful/$row['punits'])).'</span> [<span id="uu'.$row['p2s_id'].'">'.number($useful)."</span>]",
                    'expected_price'=>"<span id='ep".$row['p2s_id']."'>".($expected_qty==''?'':money($expected_price))."</span>"
                );
    }


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'rtext'=>$rtext,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$start_from+$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
    break;

case('list_pareto_products'):
    $first_day=addslashes($myconf['data_since']);
    $data_name='tsy';
    $sql=sprintf("select code,id,$data_name as value from product where $data_name>0 order by $data_name  desc ");

    $result=mysql_query($sql);
    $data=array();
    $i=0;
    $total_value=0;
    $data=array();
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total_value+=$row['value'];
        $code[]=$row['code'];
        $data[]=$row['value'];
        $i++;
    }
    $total_products=$i;
    $_value=0;
    printf("<table border=1>");
    if ($total_value>0) {
        $i=1;
        foreach($data as $key=>$value) {
            $_value+=$value;
            $_cvalue=100*$_value/$total_value;
            $_code=$code[$key];
            $_cprod=$i/$total_products*100;
            //       if($_value<0.8*$total_value)
            printf("<tr><td>$i<td><td>$_code <td></td><td>$value</td><td>$_value</td><td>%.2f</td><td>%.2f</td> <tr>",$_cvalue,$_cprod);
            $i++;
        }
    }
    break;
case('list_total_net_sales_week'):
    $first_day=addslashes($myconf['data_since']);
    $sql=sprintf("select yearweek  from list_week where yearweek not like '%%53' and first_day>%s and first_day < DATE_SUB(date(NOW()), INTERVAL 1 week); ",prepare_mysql($first_day));

    $result=mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[$row['yearweek']]=0;
    }

    $sql=sprintf("select sum(net) as net,yearweek(date_index,1) as year_week from orden  left join transaction on (order_id=orden.id) where product_id=11291 and date_index>%s and orden.tipo=2 group by yearweek(date_index) ",prepare_mysql($first_day));

    $result=mysql_query($sql);
    $fix_w53='';
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

        if ($fix_w53!='') {
            $b=$fix_w53;
            $a= $row['net'];

            $data[$row['year_week']]=(2*$a+$b)/3;
            $fix_w53='';
        }
        elseif(preg_match('/53$/',$row['year_week'])) {

            $b=$row['net'];
            //change previous weeks
            $a= $data[$row['year_week']-1];
            $data[$row['year_week']-1]=(2*$a+$b)/3;
            $fix_w53=$b;
        }
        else {
            if (array_key_exists($row['year_week'],$data))
                $data[$row['year_week']]=$row['net'];
        }

    }
    $i=0;

    foreach($data as $key=>$value) {
        $i++;
        print "$value\n";
    }
    break;

case('plot_daily_part_stock_history'):
    if (isset($_REQUEST['sku'])) {
        $part_sku=$_REQUEST['sku'];
    } else
        $part_sku=$_SESSION['state']['part']['sku'];
    $sql=sprintf("select `Quantity Sold`,IFNULL(`Quantity On Hand`,-1) as `Quantity On Hand`,`Date` from `Inventory Spanshot Fact` where `Part SKU`=%d order by `Date`  ",$part_sku);
    $res = mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        if ($row['Quantity On Hand']<0)
            $_stock=_('Unknown');
        else
            $_stock=number($row['Quantity On Hand']);
        $data[]=array(
                    'sales'=>(float)$row['Quantity Sold']
                            ,'tip_sales'=>''
                                         ,'stock'=>(float)$row['Quantity On Hand']
                                                  ,'tip_stock'=>strftime("%e %b %Y", strtotime($row['Date']))."\n"._('Stock').":$_stock "._('Units')."\n"._('Sold').":".number($row['Quantity Sold'])." "._('Units')
                                                               ,'date'=>strftime("%e %b %Y", strtotime($row['Date']))
                );
    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);

    break;

case('plot_product_week_outers'):
case('plot_product_week_sales'):

    //$product_id=$_SESSION['state']['product']['id'];

//    $sql=sprintf("select (TO_DAYS(NOW())-TO_DAYS(first_date))/7 as weeks_since  from product where id=%d",$product_id);
//    $result=mysql_query($sql);

//    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
//      $weeks=floor($row['weeks_since']);
//    }
    // $product=new Product($product_id);
    //$first_day=$product->get('mysql_first_date');
    $product_id=$_REQUEST['product_id'];
    $first_day=$_REQUEST['first_day'];
    // print $first_day;

    $sql="select date_format(first_day,'%c') as month, first_day as date, yearweek,date_format(first_day,'%v %x') as week,  UNIX_TIMESTAMP(first_day)+36000 as utime  from list_week where first_day>'$first_day' and first_day < NOW(); ";

    $data=array();
    $result=mysql_query($sql);
    $i=0;
    $last_month='';
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $index[$row['yearweek']]=$i;
        $date=$row['utime'].'x  '.strftime("%b%y",$row['utime']);
        $data[]=array(
                    'tip_asales'=>_('No sales this week'),
                    'tip_profit'=>_('No sales this week'),

                    'tip_out'=>_('No sales this week'),
                    'tip_bonus'=>_('No bonus this week'),
                    'date'=>$date,
                    'week'=>$row['week'],
                    'utime'=>$row['utime'],
                    'asales'=>0,
                    'profit'=>0,
                    'out'=>0,
                    'bonus'=>0,
                    'outofstock'=>0,
                );

        $i++;
    }


    $tipo_orders=' (orden.tipo!=0 or orden.tipo!=3 or  orden.tipo!=9 or orden.tipo!=10) ';
    $sql=sprintf("select YEARWEEK(date_index) as yearweek,sum(charge)as asales,sum(profit)as profit,sum(dispached)as _out from transaction left join orden on (order_id=orden.id) where %s and product_id=%d  group by YEARWEEK(date_index)",$tipo_orders,$product_id);

    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

        if (isset($index[$row['yearweek']])) {
            $_index=$index[$row['yearweek']];
            $data[$_index]['asales']=(float)$row['asales'];
            $data[$_index]['profit']=(float)$row['profit'];

            $data[$_index]['out']=(int)$row['_out'];
            $fday=strftime("%d %b", strtotime('@'.$data[$_index]['utime']));
            $data[$_index]['tip_out']=_('Outer Dispached')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['_out']).' '._('Outers');
            $data[$_index]['tip_asales']=_('Sales')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['asales']);
            $data[$_index]['tip_profit']=_('Profit')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['profit']);

        }
    }

    $tipo_orders=' (orden.tipo!=0 or orden.tipo!=3 or  orden.tipo!=9 or orden.tipo!=10) ';
    $sql=sprintf("select YEARWEEK(date_index) as yearweek,sum(qty)as bono from bonus left join orden on (order_id=orden.id) where %s and product_id=%d  group by YEARWEEK(date_index)",$tipo_orders,$product_id);

    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

        if (isset($index[$row['yearweek']])) {
            $_index=$index[$row['yearweek']];
            $data[$_index]['bonus']=(float)$row['bono'];
            $fday=strftime("%d %b", strtotime('@'.$data[$_index]['utime']));
            $data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');

        }
    }

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;

case('plot_department_monthout'):
case('plot_department_monthsales'):
    $department_id=$_SESSION['state']['department']['id'];
    $today=sprintf("%d%02d",date("Y"),date("m"));




    $sql=sprintf("select month(first_date) as m ,  year(first_date) as y ,period_diff( $today, concat(year(first_date),if(month(first_date)<10,concat('0',month(first_date)) ,month(first_date))   )  )   as since from product_department where id=%d",$department_id);

    $result=mysql_query($sql);

    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $month=floor($row['m']);
        $year=floor($row['y']);
        $first=sprintf("%d%02d",$year,$month);
        $since=$row['since'];
    }
    //   print "$year $month ";

    $data=array();

    for ($i=$since; $i>=0; $i--) {

        $data[]=array(
                    'asales'=>0,
                    'out'=>0,
                    'bonus'=>0,
                    //		   'month'=>$m,
                    //'year'=>$y,
                    'date'=>strftime("%m/%y", strtotime("today -$i month")),
                    'tip'=>'No sales in '.strftime("%b %y", strtotime("today -$i month")),
                    'since'=>$i
                );

    }

    //   print "$since $month $year";
    //  print_r($data);
    $tipo_orders=' (o.tipo!=0 or o.tipo!=3 or  o.tipo!=9 or o.tipo!=10) ';
    $sql=sprintf("select   -period_diff( $first, concat(year(date_index),if(month(date_index)<10,concat('0',month(date_index)) ,month(date_index))   )  )   as since  ,  sum(charge) as asales ,sum(dispached)as _out ,year(date_index) as y,month(date_index) as m,  concat(year(date_index),100+month(date_index) )  as monthyear    from  transaction as t left join orden as o on (order_id=o.id) left join product on (product_id=product.id) left join product_group as pg on (group_id=pg.id)  where %s and department_id=%d  group by monthyear order by  monthyear ",$tipo_orders,$department_id);
    //      print "$sql";
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[$row['since']]['out']=(int)$row['_out'];
        $data[$row['since']]['asales']=(float)$row['asales'];
        if ($tipo=='plot_monthout')
            $data[$row['since']]['tip_out']=_('Outers Dispached')."\n".strftime("%B %Y", strtotime("today -".$row['since']." month"))."\n".number($row['_out']).' '._('Outers');
        else
            $data[$row['since']]['tip_asales']=_('Sales')."\n".strftime("%B %Y", strtotime("today -".$row['since']." month"))."\n".money($row['asales'])."\n".number($row['_out']).' '._('Outers');

    }


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;




case('plot_monthout'):
case('plot_monthsales'):
    $product_id=$_SESSION['state']['product']['id'];
    $today=sprintf("%d%02d",date("Y"),date("m"));




    $sql=sprintf("select month(first_date) as m ,  year(first_date) as y ,period_diff( $today, concat(year(first_date),if(month(first_date)<10,concat('0',month(first_date)) ,month(first_date))   )  )   as since from product where id=%d",$product_id);

    $result=mysql_query($sql);

    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $month=floor($row['m']);
        $year=floor($row['y']);
        $first=sprintf("%d%02d",$year,$month);
        $since=$row['since'];
    }
    //   print "$year $month ";

    $data=array();

    for ($i=$since; $i>=0; $i--) {

        $data[]=array(
                    'asales'=>0,
                    'out'=>0,
                    //		   'month'=>$m,
                    //'year'=>$y,
                    'date'=>strftime("%m/%y", strtotime("today -$i month")),
                    'tip'=>'No sales in '.strftime("%b %y", strtotime("today -$i month")),
                    'since'=>$i
                );

    }

    //   print "$since $month $year";
    //  print_r($data);
    $tipo_orders=' (o.tipo!=0 or o.tipo!=3 or  o.tipo!=9 or o.tipo!=10) ';
    $sql=sprintf("select   -period_diff( $first, concat(year(date_index),if(month(date_index)<10,concat('0',month(date_index)) ,month(date_index))   )  )   as since  ,  sum(charge) as asales ,sum(dispached)as _out ,year(date_index) as y,month(date_index) as m,  concat(year(date_index),100+month(date_index) )  as monthyear    from  transaction as t left join orden as o on (order_id=o.id) left join product on (product_id=product.id) where %s and product_id=%d  group by monthyear order by  monthyear ",$tipo_orders,$product_id);
    //      print "$sql";
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[$row['since']]['out']=(int)$row['_out'];
        $data[$row['since']]['asales']=(float)$row['asales'];
        if ($tipo=='plot_monthout')
            $data[$row['since']]['tip_out']=_('Outers Dispached')."\n".strftime("%B %Y", strtotime("today -".$row['since']." month"))."\n".number($row['_out']).' '._('Outers');
        else
            $data[$row['since']]['tip_asales']=_('Sales')."\n".strftime("%B %Y", strtotime("today -".$row['since']." month"))."\n".money($row['asales'])."\n".number($row['_out']).' '._('Outers');

    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;

case('plot_quartersales'):
case('plot_quarterout'):
    $product_id=$_SESSION['tables']['order_withprod'][4];
    $today=sprintf("%d%02d",date("Y"),date("m"));


    $sql=sprintf("select quarter(first_date) as q ,  year(first_date) as y from product where id=%d",$product_id);
    //print "$sql";
    $result=mysql_query($sql);

    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $q=floor($row['q']);
        $y=floor($row['y']);
    }

    $i=0;
    foreach (range($y,date('Y')) as $year) {
        foreach (range(1,4) as $quarter) {
            if ($year==$y and $q>$quarter)
                continue;
            if ($year==date('Y') and date('Q')>$quarter)
                continue;
            $index["$year$quarter"]=$i;
            $_year=preg_replace('/^../','',$year);
            $data[]=array(
                        'date'=>"$quarter"."Q$_year",
                        'tip'=>_('No sales this quarter'),
                        'asales'=>0,
                        'out'=>0

                    );
            $i++;
        }
    }


    $tipo_orders=' (o.tipo!=0 or o.tipo!=3 or  o.tipo!=9 or o.tipo!=10) ';
    $sql=sprintf("select YEAR(date_index) as year,QUARTER(date_index) as quarter, concat(YEAR(date_index),QUARTER(date_index)) as qy,sum(charge)as asales,sum(dispached)as _out from transaction left join orden as o on (order_id=o.id) where %s  and product_id=%d  group by qy ",$tipo_orders,$product_id);
    //      print "$sql";
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[$index[$row['qy']]]['asales']=$row['asales'];
        $data[$index[$row['qy']]]['out']=$row['_out'];
        switch ($row['quarter']) {
        case 1:
            $_q=_('First quarter')." ".$row['year'];
            break;
        case 2:
            $_q=_('Second quarter')." ".$row['year'];
            break;
        case 3:
            $_q=_('Third quarter')." ".$row['year'];
            break;
        case 4:
            $_q=_('Last quarter')." ".$row['year'];
            break;
        default:
            $_q=_('Error');
        }
        if ($tipo=='plot_quarterout')
            $data[$index[$row['qy']]]['tip']=_('Outers Dispached')."\n$_q\n".number($row['asales']).' '._('outers');
        else
            $data[$index[$row['qy']]]['tip']=_('Sales')."\n$_q\n".money($row['asales']);
    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;
case('plot_yearout'):
case('plot_yearsales'):
    $product_id=$_SESSION['tables']['order_withprod'][4];
    $today=sprintf("%d%02d",date("Y"),date("m"));


    $sql=sprintf("select year(first_date) as y from product where id=%d",$product_id);
    //print "$sql";
    $result=mysql_query($sql);

    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $y=floor($row['y']);
    }

    $i=0;
    foreach (range($y,date('Y')) as $year) {
        $index[$year]=$i;
        $data[]=array(
                    'date'=>"$year",
                    'tip'=>_('No sales in $year'),
                    'asales'=>0,
                    'out'=>0
                );
        $i++;

    }


    $tipo_orders=' (o.tipo!=0 or o.tipo!=3 or  o.tipo!=9 or o.tipo!=10) ';
    $sql=sprintf("select YEAR(date_index) as year ,sum(charge)as asales ,sum(dispached)as _out from transaction left join orden as o on (order_id=o.id) where %s  and product_id=%d  group by year ",$tipo_orders,$product_id);
    //      print "$sql";
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[$index[$row['year']]]['out']=$row['_out'];
        $data[$index[$row['year']]]['asales']=$row['asales'];
        if ($tipo=='plot_yearout')
            $data[$index[$row['year']]]['tip']=_('Outers Dispached')." on ".$row['year']."\n".number($row['_out'])." "._('outers');
        else
            $data[$index[$row['year']]]['tip']=$row['year'].' '._('Sales')."\n".money($row['asales']);
    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;

case('plot_weekorders'):
    $product_id=$_SESSION['tables']['order_withprod'][4];

    $sql=sprintf("select (TO_DAYS(NOW())-TO_DAYS(first_date))/7 as weeks_since  from product where id=%d",$product_id);
    $result=mysql_query($sql);

    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $weeks=floor($row['weeks_since']);
    }

    $data=array();
    for ($i=$weeks; $i>=0; $i--) {

        $year=date("Y",strtotime("today - $i week"));
        $week=date("W",strtotime("today - $i week"));
        $data[]=array(
                    'aorders'=>0,
                    'acustomers'=>0,
                    'weekyear'=>$i,
                    'fweek'=>"\n"._('Week starting ')."\n".date("d-m-y", mktimefromcw($year, $week, 0))
                );
    }

    $sql=sprintf("select  count(DISTINCT o.customer_id) as acustomers,count(DISTINCT o.id) as aorders,yearweek(date_index)  as weekyear, (52*(year(date_index)-year(first_date))+ (week(date_index,3)-week(first_date,3))) as week_num from  transaction as t left join orden as o on (order_id=o.id) left join product on (product_id=product.id) where product_id=%d and o.tipo=2 group by weekyear order by week_num  ",$product_id);
    //     print "$sql";
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[$row['week_num']]['aorders']=$row['aorders'];
        $data[$row['week_num']]['acustomers']=$row['acustomers'];


    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;


case('plot_daystock'):
    $product_id=$_SESSION['tables']['order_withprod'][4];


    $sql=sprintf("select TO_DAYS(op_date) as day,op_qty,op_date as date,stock from stock_history where product_id=%d   order by op_date  limit 2000",$product_id);
    //     print "$sql";
    $result=mysql_query($sql);
    $_day='xxx';
    $i=0;
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        if ($_day==$row['day']) {
            array_pop($data);
            $data[]=array(
                        'stock'=>$row['stock'],
                        'day'=>$row['day'],
                        'tip'=>$row['date']
                    );

            if ($i>700000)
                break;
        } else {
            $i++;
            $data[]=array(
                        'stock'=>$row['stock']-$row['op_qty'],
                        'day'=>$row['day'],
                        'tip'=>$row['date']
                    );

            $data[]=array(
                        'stock'=>$row['stock'],
                        'day'=>$row['day'],
                        'tip'=>$row['date']
                    );

            $_day=$row['day'];
        }


    }

    $data=array();
    $data[]=array('stock'=>1,'day'=>1);
    $data[]=array('stock'=>4,'day'=>2);
    $data[]=array('stock'=>9,'day'=>3);
    $data[]=array('stock'=>16,'day'=>4);
    $data[]=array('stock'=>100,'day'=>10);
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;








case('plot_weeksalesperorder'):
    $product_id=$_SESSION['tables']['order_withprod'][4];

    $sql=sprintf("select (TO_DAYS(NOW())-TO_DAYS(first_date))/7 as weeks_since  from product where id=%d",$product_id);
    $result=mysql_query($sql);

    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $weeks=floor($row['weeks_since']);
    }

    $data=array();
    for ($i=$weeks; $i>=0; $i--) {

        $year=date("Y",strtotime("today - $i week"));
        $week=date("W",strtotime("today - $i week"));
        $data[]=array(
                    'asalesperorder'=>0,
                    'weekyear'=>$i,
                    'fweek'=>"\n"._('Week starting ')."\n".date("d-m-y", mktimefromcw($year, $week, 0))
                );
    }

    $sql=sprintf("select  sum(charge) as asales,count(DISTINCT o.id) as aorders,yearweek(date_index)  as weekyear, (52*(year(date_index)-year(first_date))+ (week(date_index,3)-week(first_date,3))) as week_num from  transaction as t left join orden as o on (order_id=o.id) left join product on (product_id=product.id) where product_id=%d and o.tipo=2 group by weekyear order by week_num  ",$product_id);
    //   print "$sql";
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

        if ($row['aorders']>0)
            $salesperorder=number_format($row['asales']/$row['aorders'],1,".","");
        else
            $salesperorder=0;
        $data[$row['week_num']]['asalesperorder']=$salesperorder;

    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;

case('stock_history'):
    $conf=$_SESSION['state']['product']['stock_history'];
    $product_id=$_SESSION['state']['product']['id'];
    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    list($date_interval,$error)=prepare_mysql_dates($from,$to);
    if ($error) {
        list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
    } else {
        $_SESSION['state']['product']['stock_history']['from']=$from;
        $_SESSION['state']['product']['stock_history']['to']=$to;
    }

    $_SESSION['state']['product']['stock_history']=
        array(
            'order'=>$order,
            'order_dir'=>$order_direction,
            'nr'=>$number_results,
            'sf'=>$start_from,
            'where'=>$where,
            'f_field'=>$f_field,
            'f_value'=>$f_value,
            'from'=>$from,
            'to'=>$to,
            'elements'=>$elements
        );
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';




//  $view='';
//  foreach($elements as $key=>$val){
//    if(!$val)
//      $view.=' and op_tipo!='.$key;
//  }


    $wheref='';
//   if($f_field=='name' and $f_value!='')
//     $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";




    $where=$where.sprintf(" and sujeto='PROD' and sujeto_id=%d and objeto='LOC'",$product_id);


    //   $where =$where.$view.sprintf(' and product_id=%d  %s',$product_id,$date_interval);

    $sql="select count(*) as total from history    $where $wheref";
    //   print "$sql";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='')
        $filtered=0;
    else {
        $sql="select count(*) as total from history  $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
        }

    }


    if ($total==0)
        $rtext=_('No stock movements');
    else
        $rtext=$total.' '.ngettext('stock operetion','stock operations',$total);




    $sql=sprintf("select  UNIX_TIMESTAMP(date) as date,handle as author ,history.note,history.staff_id  from history left join liveuser_users  on (authuserid=history.staff_id) $where $wheref order by $order $order_direction limit $start_from,$number_results ");
    // print $sql;
    $result=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


        $adata[]=array(

                     'author'=>$data['author']
                              ,'note'=>$data['note']
                                      ,'date'=>strftime("%a %e %b %Y %T", strtotime('@'.$data['date'])),
                 );
    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'rtext'=>$rtext,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$start_from+$res->numRows(),
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
    break;



default:

    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);

}

function list_departments() {
//     $conf_table='store';
//       $conf=$_SESSION['state']['store']['table'];
//       $conf2=$_SESSION['state']['store'];


    if (isset( $_REQUEST['store']) and  is_numeric( $_REQUEST['store']))
        $store_id=$_REQUEST['store'];
    else
        $store_id=$_SESSION['state']['store']['id'];


    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent='none';


    if ($parent=='store') {
        $conf=$_SESSION['state']['store']['table'];
        $conf2=$_SESSION['state']['store'];

        $conf_table='store';
    } else {

        $conf=$_SESSION['state']['store']['table'];
        $conf2=$_SESSION['state']['store'];
        $conf_table='departments';
    }




    if (isset( $_REQUEST['sf'])) {
        $start_from=$_REQUEST['sf'];


    } else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
    } else
        $percentages=$conf2['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
    } else
        $period=$conf2['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
    } else
        $avg=$conf2['avg'];





    $_SESSION['state'][$conf_table]['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'parent'=>$parent);
    $_SESSION['state'][$conf_table]['percentages']=$percentages;
    $_SESSION['state'][$conf_table]['period']=$period;
    $_SESSION['state'][$conf_table]['avg']=$avg;


    switch ($parent) {
    case('store'):
        $where=sprintf("where  `Product Department Store Key`=%d",$store_id);
        break;
    default:
        $where='where true';

    }


    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Product Department Name` like '".addslashes($f_value)."%'";
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";





    $sql="select count(*) as total from `Product Department Dimension`   $where $wheref";

    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Product Department Dimension`   $where ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
            mysql_free_result($result);
        }

    }

    $rtext=$total_records." ".ngettext('department','departments',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';
    $_dir=$order_direction;
    $_order=$order;

    if ($order=='families')
        $order='`Product Department Families`';
    if ($order=='todo')
        $order='`Product Department In Process Products`';
    if ($order=='profit') {
        if ($period=='all')
            $order='`Product Department Total Profit`';
        elseif($period=='year')
        $order='`Product Department 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Department 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Department 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Department 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Department Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Department 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Department 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Department 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Department 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='name')
    $order='`Product Department Name`';
    elseif($order=='code')
    $order='`Product Department Code`';
    elseif($order=='active')
    $order='`Product Department For Sale Products`';
    elseif($order=='outofstock')
    $order='`Product Department Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Product Department Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Product Department Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Product Department Optimal Availability Products`';
    elseif($order=='low')
    $order='`Product Department Low Availability Products`';
    elseif($order=='critical')
    $order='`Product Department Critical Availability Products`';


    $sum_families=0;
    $sum_active=0;
    $sql="select sum(`Product Department For Sale Products`) as sum_active,sum(`Product Department Families`) as sum_families  from `Product Department Dimension` $where   ";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $sum_families=$row['sum_families'];
        $sum_active=$row['sum_active'];

    }

    if ($period=='all') {


        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select   max(`Product Department Total Days Available`) as 'Product Department Total Days Available',max(`Product Department Total Days On Sale`) as 'Product Department Total Days On Sale', sum(if(`Product Department Total Profit`<0,`Product Department Total Profit`,0)) as total_profit_minus,sum(if(`Product Department Total Profit`>=0,`Product Department Total Profit`,0)) as total_profit_plus,sum(`Product Department Total Invoiced Amount`) as sum_total_sales  from `Product Department Dimension` $where   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

         

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
           
              if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department Total Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department Total Days On Sale']>0)
                        $factor=7/$row['Product Department Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department Total Days Available']>0)
                        $factor=30.4368499/$row['Product Department Total Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department Total Days Available']>0)
                        $factor=7/$row['Product Department Total Days Available'];
                    else
                        $factor=0;
                }
            $sum_total_sales=$row['sum_total_sales']*$factor;
            $sum_total_profit=$sum_total_profit*$factor;
        
        }
        mysql_free_result($result);
    }
    elseif($period=='year') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Year Acc Days Available`) as 'Product Department 1 Year Acc Days Available',max(`Product Department 1 Year Acc Days On Sale`) as 'Product Department 1 Year Acc Days On Sale', sum(if(`Product Department 1 Year Acc Profit`<0,`Product Department 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Year Acc Profit`>=0,`Product Department 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Sale Products`) as sum_active,sum(`Product Department 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

   if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Year Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }



            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
            
            
            
            
            
            
            
            
            
            
            
            
        }
        mysql_free_result($result);
    }
    elseif($period=='quarter') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Quarter Acc Days Available`) as 'Product Department 1 Quarter Acc Days Available',max(`Product Department 1 Quarter Acc Days On Sale`) as 'Product Department 1 Quarter Acc Days On Sale',sum(if(`Product Department 1 Quarter Acc Profit`<0,`Product Department 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Quarter Acc Profit`>=0,`Product Department 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Sale Products`) as sum_active,sum(`Product Department 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        
          if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Quarter Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Quarter Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }
        
        

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);
    }
    elseif($period=='month') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Month Acc Days Available`) as 'Product Department 1 Month Acc Days Available',max(`Product Department 1 Month Acc Days On Sale`) as 'Product Department 1 Month Acc Days On Sale',sum(if(`Product Department 1 Month Acc Profit`<0,`Product Department 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Month Acc Profit`>=0,`Product Department 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Sale Products`) as sum_active,sum(`Product Department 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Product Department Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


   if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Month Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }

            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }
    elseif($period=='week') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Week Acc Days Available`) as 'Product Department 1 Week Acc Days Available',max(`Product Department 1 Week Acc Days On Sale`) as 'Product Department 1 Week Acc Days On Sale',sum(if(`Product Department 1 Week Acc Profit`<0,`Product Department 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Week Acc Profit`>=0,`Product Department 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Sale Products`) as sum_active,sum(`Product Department 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

 if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Week Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Week Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }


            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }



    $sql="select *  from `Product Department Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();
    //print "$period";
    global $myconf;
    $currency_code=$myconf['currency_code'];
    $sum_active=0;
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      	$currency_code=$row['Product Department Currency Code'];
        $code=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Department Key'],$row['Product Department Code']);
        $name=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Department Key'],$row['Product Department Name']);

        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Product Department Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department Total Profit']>=0)
                    $tprofit=percentage($row['Product Department Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product Department 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product Department 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product Department 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product Department 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


        } else {






            if ($period=='all') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department Total Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department Total Days On Sale']>0)
                        $factor=7/$row['Product Department Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department Total Days Available']>0)
                        $factor=30.4368499/$row['Product Department Total Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department Total Days Available']>0)
                        $factor=7/$row['Product Department Total Days Available'];
                    else
                        $factor=0;
                }

                $tsall=$row['Product Department Total Invoiced Amount']*$factor;
                $tprofit=$row['Product Department Total Profit']*$factor;

 //print ($row['Product Department Total Days On Sale']/30/12)."\n";


            }
            elseif($period=='year') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Year Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }









                $tsall=$row['Product Department 1 Year Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 1 Year Acc Profit']*$factor;
            }
            elseif($period=='quarter') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Quarter Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Quarter Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=money($row['Product Department 1 Quarter Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Department 1 Quarter Acc Profit']*$factor);
            }
            elseif($period=='month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Month Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=$row['Product Department 1 Month Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 1 Month Acc Profit']*$factor;
            }
            elseif($period=='week') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Week Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Week Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=$row['Product Department 1 Week Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 1 Week Acc Profit']*$factor;
            }



        }
        $sum_active+=$row['Product Department For Sale Products'];
	if(!$percentages){
	$tsall=money($tsall,$row['Product Department Currency Code']);
	$tprofit=money($tprofit,$row['Product Department Currency Code']);
	}
        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'families'=>number($row['Product Department Families']),
                     'active'=>number($row['Product Department For Sale Products']),
                     'todo'=>number($row['Product Department In Process Products']),

                     'outofstock'=>number($row['Product Department Out Of Stock Products']),
                     'stock_error'=>number($row['Product Department Unknown Stock Products']),
                     'stock_value'=>money($row['Product Department Stock Value']),
                     'surplus'=>number($row['Product Department Surplus Availability Products']),
                     'optimal'=>number($row['Product Department Optimal Availability Products']),
                     'low'=>number($row['Product Department Low Availability Products']),
                     'critical'=>number($row['Product Department Critical Availability Products']),


                     'sales'=>$tsall,
                     'profit'=>$tprofit

                 );


    }
    mysql_free_result($res);

    if ($percentages) {
        $tsall='100.00%';
        $tprofit='100.00%';
    }else {
      $tsall=money($sum_total_sales,$currency_code);
      $tprofit=money($sum_total_profit,$currency_code);
    }

    $adata[]=array(

                 'code'=>_('Total'),
                 'families'=>number($sum_families),
                 'active'=>number($sum_active),
                 'sales'=>$tsall,
                 'profit'=>$tprofit
// 		 'outofstock'=>number($row['product department out of stock products']),
// 		 'stockerror'=>number($row['product department unknown stock products']),
// 		 'stock_value'=>money($row['product department stock value']),
// 		 'tsall'=>$tsall,'tprofit'=>$tprofit,
// 		 'per_tsall'=>percentage($row['product department total invoiced amount'],$sum_total_sales,2),
// 		 'tsm'=>money($row['product department 1 month acc invoiced amount']),
// 		 'per_tsm'=>percentage($row['product department 1 month acc invoiced amount'],$sum_month_sales,2),
             );




    $total_records=ceil($total_records/$number_results)+$total_records;
//$total_records=5;
//print $total_records;
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}
function list_products() {
    $conf=$_SESSION['state']['products']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['products']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];

        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    }      else
        $number_results=$conf['nr'];







    //    if(!is_numeric($number_results)){
    // 	print $number_results."xx";
    // 	$number_results=25;

    //       }

    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;



    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $_SESSION['state']['products']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['products']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['products']['period']=$period;
    } else
        $period=$_SESSION['state']['products']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['products']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['products']['avg'];


    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent=$conf['parent'];

    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];




    $_SESSION['state']['products']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                                            ,'mode'=>$mode,'restrictions'=>$restrictions,'parent'=>$parent
                                                 );



    $db_table='`Product Dimension`';



    switch ($parent) {
    case('store'):
        $where=sprintf(' where `Product Store Key`=%d',$_SESSION['state']['store']['id']);
        break;
    case('department'):
        $where=sprintf(' left join `Product Department Bridge` B on (P.`Product Key`=B.`Product Key`) where `Product Department Key`=%d',$_SESSION['state']['department']['id']);
        break;
    case('family'):
        $where=sprintf(' where `Product Family Key`=%d',$_SESSION['state']['family']['id']);
        break;
    default:
        $where=sprintf(' where true ');

    }
    $group='';
    /*    switch($mode){ */
    /*    case('same_code'): */
    /*       $db_table='`Product Same Code Dimension`'; */
    /*      break; */
    /*    case('same_id'): */
    /*      $where.=sprintf("  "); */
    /*      break; */
    /*    case('history'): */

    /*      break; */
    /*    } */

    switch ($restrictions) {
    case('forsale'):
        $where.=sprintf(" and `Product Sales State`='For Sale'  ");
        break;
    case('editable'):
        $where.=sprintf(" and `Product Sales State` in ('For Sale','In Process','Unknown')  ");
        break;
    case('notforsale'):
        $where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
        break;
    case('discontinued'):
        $where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
        break;
    case('all'):

        break;
    }


    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from $db_table  $where $wheref   ";
    //print_r($conf);exit;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Product Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }


    $rtext=$total_records." ".ngettext('product','products',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;

    if ($order=='stock')
        $order='`Product Availability`';
    if ($order=='code')
        $order='`Product Code File As`';
    else if ($order=='name')
        $order='`Product Name`';
    else if ($order=='available_for')
        $order='`Product Available Days Forecast`';


    if ($order=='profit') {
        if ($period=='all')
            $order='`Product Total Profit`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='margin') {
        if ($period=='all')
            $order='`Product Total Margin`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Margin`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Margin`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Margin`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Margin`';

    }
    elseif($order=='sold') {
        if ($period=='all')
            $order='`Product Total Quantity Invoiced`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Quantity Invoiced`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Quantity Invoiced`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Quantity Invoiced`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Quantity Invoiced`';

    }
    elseif($order=='family') {
        $order='`Product Family`Code';
    }
    elseif($order=='dept') {
        $order='`Product Main Department Code`';
    }
    elseif($order=='expcode') {
        $order='`Product Tariff Code`';
    }
    elseif($order=='parts') {
        $order='`Product XHTML Parts`';
    }
    elseif($order=='supplied') {
        $order='`Product XHTML Supplied By`';
    }
    elseif($order=='gmroi') {
        $order='`Product GMROI`';
    }
    elseif($order=='state') {
        $order='`Product Sales State`';
    }
    elseif($order=='web') {
        $order='`Product Web State`';
    }

    $sum_total_sales=0;
    $sum_total_profit=0;
    $sum_total_stock_value=0;


    if ($percentages) {

        $sum_total_stock_value=0;
        $sql="select sum(`Product Stock Value`) as sum_stock_value  from `Product Dimension` $where $wheref     ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $sum_total_stock_value=$row['sum_stock_value'];
        }

        if ($period=='all') {


            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product Total Profit`<0,`Product Total Profit`,0)) as total_profit_minus,sum(if(`Product Total Profit`>=0,`Product Total Profit`,0)) as total_profit_plus,sum(`Product Total Invoiced Amount`) as sum_total_sales ,sum(`Product Stock Value`) as sum_stock_value  from `Product Dimension` $where $wheref     ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];

            }
        }
        elseif($period=='year') {

            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Year Acc Profit`<0,`Product 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Year Acc Profit`>=0,`Product 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Dimension` $where $wheref   ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
        elseif($period=='quarter') {

            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Quarter Acc Profit`<0,`Product 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Quarter Acc Profit`>=0,`Product 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Product Dimension`   $where $wheref   ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
        elseif($period=='month') {

            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Month Acc Profit`<0,`Product 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Month Acc Profit`>=0,`Product 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Product Dimension`  $where $wheref    ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
        elseif($period=='week') {
            $sum_families=0;
            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Week Acc Profit`<0,`Product 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Week Acc Profit`>=0,`Product 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Product Dimension`  $where $wheref    ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];

                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }

    }

    $sql="select  * from `Product Dimension` P   $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);
    $adata=array();

    $counter=0;
    $total_units=0;

    $sum_unitary_price=0;
    $counter_unitary_price=0;
    $sum_sold=0;
    $sum_units=0;
    $sum_sales=0;
    $sum_profit=0;
    $count_margin=0;
    $sum_margin=0;

    //print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $counter++;




        $counter_unitary_price++;
        $sum_unitary_price+=$row['Product Price']/$row['Product Units Per Case'];




        $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);


        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Product Total Invoiced Amount'],$sum_total_sales,2);

                if ($row['Product Total Profit']>=0)
                    $tprofit=percentage($row['Product Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


        } else {






            if ($period=='all') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Total Days On Sale']>0)
                        $factor=30.4368499/$row['Product Total Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product Total Days On Sale']>0)
                        $factor=7/$row['Product Total Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Total Days Available']>0)
                        $factor=30.4368499/$row['Product Total Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Total Days Available']>0)
                        $factor=7/$row['Product Total Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {

                    $tsall=($row['Product Total Invoiced Amount']*$factor);
                    $tprofit=($row['Product Total Profit']*$factor);
                    $sold=$row['Product Total Quantity Invoiced']*$factor;
                }


                $margin=$row['Product Total Margin'];



            }
            elseif($period=='year') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Year Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Year Acc Days Available']>0)
                        $factor=7/$row['Product 1 Year Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product 1 Year Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product 1 Year Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product 1 Year Acc Profit']*$factor);
                }
                $margin=$row['Product 1 Year Acc Margin'];
            }
            elseif($period=='quarter') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Quarter Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Quarter Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Quarter Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Quarter Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Quarter Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Quarter Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Quarter Acc Days Available']>0)
                        $factor=7/$row['Product 1 Quarter Acc Days Available'];
                    else
                        $factor='ND';
                }

                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product 1 Quarter Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product 1 Quarter Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product 1 Quarter Acc Profit']*$factor);
                }
                $margin=$row['Product 1 Quarter Acc Margin'];

            }
            elseif($period=='month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Month Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Month Acc Days Available']>0)
                        $factor=7/$row['Product 1 Month Acc Days Available'];
                    else
                        $factor='ND';
                }

                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $tsall=$row['Product 1 Month Acc Invoiced Amount']*$factor;
                    $tprofit=$row['Product 1 Month Acc Profit']*$factor;
                    $sold=$row['Product 1 Month Acc Quantity Invoiced']*$factor;
                }
                $margin=$row['Product 1 Month Acc Margin'];
            }
            elseif($period=='week') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Week Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Week Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Week Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Week Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Week Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Week Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Week Acc Days Available']>0)
                        $factor=7/$row['Product 1 Week Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $tsall=$row['Product 1 Week Acc Invoiced Amount']*$factor;
                    $sold=$row['Product 1 Week Acc Quantity Invoiced']*$factor;
                    $tprofit=$row['Product 1 Week Acc Profit']*$factor;


                }
                $margin=$row['Product 1 Week Acc Margin'];

            }



        }

        if (is_numeric($row['Product Availability']))
            $stock=number($row['Product Availability']);
        else
            $stock='?';

        $sum_sold+=$sold;
        $sum_units+=$sold*$row['Product Units Per Case'];

        $sum_sales+=$tsall;
        $sum_profit+=$tprofit;

        if ($margin=='') {
            if ($sold==0)
                $margin=_('ND');
            else
                $margin=_('NA');

        } else {
            $count_margin++;
            $sum_margin+=$margin;
            $margin=number($margin,1)."%";
        }

        $type=$row['Product Sales State'];
        if ($row['Product Record Type']=='In Process')
            $type.='<span style="color:red">*</span>';

        switch ($row['Product Web State']) {
        case('Online Force Out of Stock'):
            $web_state=_('Out of Stock');
            break;
        case('Online Auto'):
            $web_state=_('Auto');
            break;
        case('Unknown'):
            $web_state=_('Unknown');
        case('Offline'):
            $web_state=_('Offline');
            break;
        case('Online Force Hide'):
            $web_state=_('Hide');
            break;
        case('Online Force For Sale'):
            $web_state=_('Sale');
            break;
        default:
            $web_state=$row['Product Web State'];
        }

        $adata[]=array(

                     'code'=>$code,
                     'name'=>$row['Product XHTML Short Description'],
                     'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case']).' '._('ea'),
                     'family'=>$row['Product Family Name'],
                     'dept'=>$row['Product Main Department Name'],
                     'expcode'=>$row['Product Tariff Code'],
                     'parts'=>$row['Product XHTML Parts'],
                     'supplied'=>$row['Product XHTML Supplied By'],
                     'gmroi'=>$row['Product GMROI'],
                     'stock_value'=>money($row['Product Stock Value']),
                     'stock'=>$stock,
                     'sales'=>money($tsall),
                     'profit'=>money($tprofit),
                     'margin'=>$margin,
                     'sold'=>number($sold),
                     'state'=>$type,
                     'web'=>$web_state,
		     'image'=>$row['Product Main Image'],
		     'type'=>'item'
                 );
    }

    if ($percentages) {
        $tsall='100.00%';
        $tprofit='100.00%';
        $tstock_value='100.00%';
    } else {
        $tsall=money($sum_total_sales);
        $tprofit=money($sum_total_profit);
        $tstock_value=money($sum_total_stock_value);

    }


    $total_title='Total';
    if ($view=='sales')
        $total_title=_('Total');

    if ($counter_unitary_price>0)
        $average_unit_price=$sum_unitary_price/$counter_unitary_price;
    else
        $average_unit_price=_('ND');
    if ($count_margin>0)
        $avg_margin='&lang;'.number($sum_margin/$count_margin,1)."%&rang;";
    else
        $avg_margin=_('ND');
    $adata[]=array(

                 'code'=>$total_title,
                 'name'=>'',
                 'shortname'=>number($sum_units).'x',
                 'stock_value'=>$tstock_value,
                 'sold'=>number($sum_sold),
                 'sales'=>money($sum_sales),
                 'profit'=>money($sum_profit),
                 'margin'=>$avg_margin,
		 'type'=>'total'
             );


    $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );




    echo json_encode($response);
}
function list_parts() {
    $conf=$_SESSION['state']['parts']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['parts']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];
    if (!is_numeric($number_results))
        $number_results=25;

    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['avg']))
        $avg=$_REQUEST['avg'];
    else
        $avg=$_SESSION['state']['parts']['avg'];
    $_SESSION['state']['parts']['avg']=$avg;


    if (isset( $_REQUEST['period']))
        $period=$_REQUEST['period'];
    else
        $period=$_SESSION['state']['parts']['period'];
    $_SESSION['state']['parts']['period']=$period;


    if (isset( $_REQUEST['percentage']))
        $percentage=$_REQUEST['percentage'];
    else
        $percentage=$_SESSION['state']['parts']['percentage'];
    $_SESSION['state']['parts']['percentage']=$percentage;

    $_SESSION['state']['parts']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);


    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    if (!is_numeric($start_from))
        $start_from=0;
    if (!is_numeric($number_results))
        $number_results=25;

    $where.="  ";

    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='used_in' and $f_value!='')
        $wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
    elseif($f_field=='description' and $f_value!='')
    $wheref.=" and  `Part XHTML Description` like '%".addslashes($f_value)."%'";
    elseif($f_field=='supplied_by' and $f_value!='')
    $wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";


    $sql="select count(*) as total from `Part Dimension`  $where $wheref";

    //   print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Part Dimension`  $where ";


        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }




    $rtext=$total_records." ".ngettext('part','parts',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');
    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('used_in'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part used in ")." <b>".$f_value."*</b> ";
            break;
        case('suppiled_by'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part supplied by ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {


        switch ($f_field) {
        case('used_in'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts used in')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('supplied_by'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts supplied by')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with description like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';




    $_order=$order;
    $_order_dir=$order_dir;

    if ($order=='stock')
        $order='`Part Current Stock`';
    if ($order=='sku')
        $order='`Part SKU`';
    else if ($order=='description')
        $order='`Part XHTML Description`';
    else if ($order=='available_for')
        $order='`Part Available Days Forecast`';
    else if ($order=='supplied_by')
        $order='`Part XHTML Currently Supplied By`';
    else if ($order=='used_in')
        $order='`Part XHTML Currently Used In`';

    else if ($order=='margin') {
        if ($period=='all')
            $order=' `Part Total Margin` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Margin` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Margin` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Margin` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Margin` ';

    } else if ($order=='sold') {
        if ($period=='all')
            $order=' `Part Total Sold` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Sold` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Sold` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Sold` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Sold` ';

    } else if ($order=='money_in') {
        if ($period=='all')
            $order=' `Part Total Sold Amount` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Sold Amount` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Sold Amount` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Sold Amount` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Sold Amount` ';

    } else if ($order=='profit_sold') {
        if ($period=='all')
            $order=' `Part Total Profit When Sold` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Profit When Sold` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Profit When Sold` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Profit When Sold` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Profit When Sold` ';

    } else if ($order=='avg_stock') {
        if ($period=='all')
            $order=' `Part Total AVG Stock` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc AVG Stock` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc AVG Stock` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc AVG Stock` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc AVG Stock` ';

    } else if ($order=='avg_stockvalue') {
        if ($period=='all')
            $order=' `Part Total AVG Stock Value` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc AVG Stock Value` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc AVG Stock Value` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc AVG Stock Value` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc AVG Stock Value` ';

    } else if ($order=='keep_days') {
        if ($period=='all')
            $order=' `Part Total Keeping Days` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Keeping Days` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Keeping Days` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Keeping Days` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Keeping Days` ';

    } else if ($order=='outstock_days') {
        if ($period=='all')
            $order=' `Part Total Out of Stock Days` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Out of Stock Days` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Out of Stock Days` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Out of Stock Days` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Out of Stock Days` ';

    } else if ($order=='unknown_days') {
        if ($period=='all')
            $order=' `Part Total Unknown Stock Days` ';
        elseif($period=='year')
        $order=' `Part 1 Year Unknown Stock Days` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Unknown Stock Days` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Unknown Stock Days` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Unknown Stock Days` ';

    } else if ($order=='gmroi') {
        if ($period=='all')
            $order=' `Part Total GMROI` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc GMROI` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc GMROI` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc GMROI` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc GMROI` ';

    }





    $sql="select * from `Part Dimension`  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
    //          print $sql;
    $adata=array();
    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

        if ($period=='all') {
            if ($avg=='totals') {
                $sold=number($data['Part Total Sold']);
                $given=number($data['Part Total Given']);
                $sold_amount=money($data['Part Total Sold Amount']);
                $abs_profit=money($data['Part Total Absolute Profit']);
                $profit_sold=money($data['Part Total Profit When Sold']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part Total Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part Total Keeping Days']-$data['Part Total Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part Total Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part Total Keeping Days']-$data['Part Total Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part Total Sold']/$factor);
                    $given=number($data['Part Total Given']/$factor);
                    $sold_amount=money($data['Part Total Sold Amount']/$factor);
                    $abs_profit=money($data['Part Total Absolute Profit']/$factor);
                    $profit_sold=money($data['Part Total Profit When Sold']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";
            $margin=percentage($data['Part Total Margin'],1);
            $avg_stock=number($data['Part Total AVG Stock']);
            $avg_stockvalue=money($data['Part Total AVG Stock Value']);
            $keep_days=number($data['Part Total Keeping Days'],0);
            $outstock_days=percentage($data['Part Total Out of Stock Days'],$data['Part Total Keeping Days']);
            $unknown_days=percentage($data['Part Total Unknown Stock Days'],$data['Part Total Keeping Days']);
            $gmroi=number($data['Part Total GMROI'],0);

        }
        elseif($period=='year') {


            if ($avg=='totals') {
                $sold=number($data['Part 1 Year Acc Sold']);
                $given=number($data['Part 1 Year Acc Given']);
                $sold_amount=money($data['Part 1 Year Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Year Acc Absolute Profit']);
                $profit_sold=money($data['Part 1 Year Acc Profit When Sold']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 1 Year Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 1 Year Acc Keeping Days']-$data['Part 1 Year Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Year Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Year Acc Keeping Days']-$data['Part 1 Year Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Year Acc Sold']/$factor);
                    $given=number($data['Part 1 Year Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Year Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Year Acc Absolute Profit']/$factor);
                    $profit_sold=money($data['Part 1 Year Acc Profit When Sold']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";


            $margin=percentage($data['Part 1 Year Acc Margin'],1);
            $avg_stock=number($data['Part 1 Year Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Year Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Year Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Year Acc Out of Stock Days'],$data['Part 1 Year Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Year Acc Unknown Stock Days'],$data['Part 1 Year Acc Keeping Days']);
            $gmroi=number($data['Part 1 Year Acc GMROI'],0);



        }
        elseif($period=='quarter') {


            if ($avg=='totals') {
                $sold=number($data['Part 1 Quarter Acc Sold']);
                $given=number($data['Part 1 Quarter Acc Given']);
                $sold_amount=money($data['Part 1 Quarter Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Quarter Acc Absolute Profit']);
                $profit_sold=money($data['Part 1 Quarter Acc Profit When Sold']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 1 Quarter Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 1 Quarter Acc Keeping Days']-$data['Part 1 Quarter Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Quarter Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Quarter Acc Keeping Days']-$data['Part 1 Quarter Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Quarter Acc Sold']/$factor);
                    $given=number($data['Part 1 Quarter Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Quarter Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Quarter Acc Absolute Profit']/$factor);
                    $profit_sold=money($data['Part 1 Quarter Acc Profit When Sold']/$factor);
                }
            }



            if ($given!=0)
                $sold="$sold ($given)";
            $margin=percentage($data['Part 1 Quarter Acc Margin'],1);
            $avg_stock=number($data['Part 1 Quarter Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Quarter Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Quarter Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Quarter Acc Out of Stock Days'],$data['Part 1 Quarter Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Quarter Acc Unknown Stock Days'],$data['Part 1 Quarter Acc Keeping Days']);
            $gmroi=number($data['Part 1 Quarter Acc GMROI'],0);

        }
        elseif($period=='month') {




            if ($avg=='totals') {
                $sold=number($data['Part 1 Month Acc Sold']);
                $given=number($data['Part 1 Month Acc Given']);
                $sold_amount=money($data['Part 1 Month Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Month Acc Absolute Profit']);
                $profit_sold=money($data['Part 1 Month Acc Profit When Sold']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 1 Month Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 1 Month Acc Keeping Days']-$data['Part 1 Month Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Month Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Month Acc Keeping Days']-$data['Part 1 Month Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Month Acc Sold']/$factor);
                    $given=number($data['Part 1 Month Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Month Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Month Acc Absolute Profit']/$factor);
                    $profit_sold=money($data['Part 1 Month Acc Profit When Sold']/$factor);
                }
            }


            if ($given!=0)
                $sold="$sold ($given)";

            $margin=percentage($data['Part 1 Month Acc Margin'],1);

            $avg_stock=number($data['Part 1 Month Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Month Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Month Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Month Acc Out of Stock Days'],$data['Part 1 Month Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Month Acc Unknown Stock Days'],$data['Part 1 Month Acc Keeping Days']);
            $gmroi=number($data['Part 1 Month Acc GMROI'],0);


        }
        elseif($period=='week') {


            if ($avg=='totals') {
                $sold=number($data['Part 1 Week Acc Sold']);
                $given=number($data['Part 1 Week Acc Given']);
                $sold_amount=money($data['Part 1 Week Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Week Acc Absolute Profit']);
                $profit_sold=money($data['Part 1 Week Acc Profit When Sold']);
            } else {

                if ($avg=='week')
                    $factor=$data['Part 1 Week Acc Keeping Days']/30.4368499;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Week Acc Keeping Days']-$data['Part 1 Week Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Week Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Week Acc Keeping Days']-$data['Part 1 Week Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Week Acc Sold']/$factor);
                    $given=number($data['Part 1 Week Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Week Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Week Acc Absolute Profit']/$factor);
                    $profit_sold=money($data['Part 1 Week Acc Profit When Sold']/$factor);
                }
            }



            if ($given!=0)
                $sold="$sold ($given)";
            $margin=percentage($data['Part 1 Week Acc Margin'],1);
            $avg_stock=number($data['Part 1 Week Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Week Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Week Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Week Acc Out of Stock Days'],$data['Part 1 Week Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Week Acc Unknown Stock Days'],$data['Part 1 Week Acc Keeping Days']);
            $gmroi=number($data['Part 1 Week Acc GMROI'],0);

        }



        $adata[]=array(
                     'sku'=>sprintf('<a href="part.php?id=%d">%06d</a>',$data['Part SKU'],$data['Part SKU'])
                           ,'description'=>$data['Part XHTML Description']
                                          ,'used_in'=>$data['Part XHTML Currently Used In']
                                                     ,'supplied_by'=>$data['Part XHTML Currently Supplied By']
                                                                    ,'stock'=>number($data['Part Current Stock'])
                                                                             ,'available_for'=>interval($data['Part XHTML Available For Forecast'])
                                                                                              ,'stock_value'=>money($data['Part Current Stock Cost'])
                                                                                                             ,'sold'=>$sold
                                                                                                                     ,'given'=>$given
                                                                                                                              ,'money_in'=>$sold_amount
                                                                                                                                          ,'profit'=>$abs_profit
                                                                                                                                                    ,'profit_sold'=>$profit_sold
                                                                                                                                                                   ,'margin'=>$margin
                                                                                                                                                                             ,'avg_stock'=>$avg_stock
                                                                                                                                                                                          ,'avg_stockvalue'=>$avg_stockvalue
                                                                                                                                                                                                            ,'keep_days'=>$keep_days
                                                                                                                                                                                                                         ,'outstock_days'=>$outstock_days
                                                                                                                                                                                                                                          ,'unknown_days'=>$unknown_days
                                                                                                                                                                                                                                                          ,'gmroi'=>$gmroi
                 );
    }

    $total_title=_('Total');

    $adata[]=array(

                 'sku'=>$total_title,
             );

    $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}

function list_products_with_same_code() {
    $conf=$_SESSION['state']['product']['server'];
    $tableid=0;
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];


    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];


    $code=$_SESSION['state']['product']['server']['tag'];
    $where=sprintf('where `Product Code`=%s  ',prepare_mysql($code));
    $wheref='';

    $order_direction=$order_dir;
    $_order=$order;
    $_dir=$order_direction;
    if ($order=='store')
        $order='`Store Name`';

    $sql="select *  from `Product Dimension` left join `Store Dimension` S  on (`Store Key`=`Product Store Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    // print $sql;
    $res = mysql_query($sql);
    $number_results=mysql_num_rows($res);

    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $id=sprintf("<a href='product.php?pid=%d'>%05d</a>",$row['Product ID'],$row['Product ID']);
        $adata[]=array(
                     'id'=>$id
                          ,'description'=>$row['Product XHTML Short Description']
                                         ,'store'=>$row['Store Name']
                                                  ,'parts'=>$row['Product XHTML Parts']
                 );

    }
    mysql_free_result($res);
    $rtext=number($number_results).' '._('products with the same code');
    $rtext_rpp='';
    $filter_msg='';
    $total_records=$number_results;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );

    echo json_encode($response);
}

function find_part() {
    if (!isset($_REQUEST['query']) or $_REQUEST['query']=='') {
        $response= array(
                       'state'=>400,
                       'data'=>array()
                   );
        echo json_encode($response);
        return;
    }


    if (isset($_REQUEST['except']) and  isset($_REQUEST['except_id'])  and   is_numeric($_REQUEST['except_id']) and $_REQUEST['except']=='location' ) {

        $sql=sprintf("select `Part SKU`,`Part XHTML Description`,`Part Currently Used In` from `Part Dimension` where  (`Part SKU`=%d or `Part XHTML Currently Used In` like '%%%s%%' ) limit 20 ",$_REQUEST['query'],addslashes($_REQUEST['query']));

    } else {
        $sql=sprintf("select `Part SKU`,`Part XHTML Description`,`Part Currently Used In` from `Part Dimension` where  (`Part SKU`=%d or `Part XHTML Currently Used In` like '%%%s%%' ) limit 20",$_REQUEST['query'],addslashes($_REQUEST['query']));

    }
    //print $sql;

    $_data=array();
    $res=mysql_query($sql);

    //  $qty_on_hand=0;
//        $location_key=$_REQUEST['except_id'];

    while ($data=mysql_fetch_array($res)) {
        //$loc_sku=$location_key.'_'.$data['Part SKU'];


        $_data[]= array(
                      'info'=>sprintf("%s:%05d - %s",_('SKU'),$data['Part SKU'],$data['Part XHTML Description'])
                             ,'sku'=>$data['Part SKU']
                                    ,'description'=>$data['Part XHTML Description']
                                                   ,'usedin'=>$data['Part Currently Used In']

                                                             //	 'sku'=>sprintf('<a href="part.php?sku=%d">%s</a>',$data['Part SKU'],$data['Part SKU'])
                                                             // ,'description'=>$data['Part XHTML Description']
                                                             //  ,'current_qty'=>sprintf('<span  used="0"  value="%s" id="s%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$qty_on_hand,$loc_sku,$qty_on_hand,$location_key,$data['Part SKU'],number($qty_on_hand))
// 			 ,'changed_qty'=>sprintf('<span   used="0" id="cs%s"  onclick="change_reset(\'%s\',%d)"   ">0</span>',$loc_sku,$loc_sku,$data['Part SKU'])
// 			 ,'new_qty'=>sprintf('<span  used="0"  value="%s" id="ns%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$qty_on_hand,$loc_sku,$qty_on_hand,$location_key,$data['Part SKU'],number($qty_on_hand))
// 			 ,'_qty_move'=>'<input id="qm'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
// 			 ,'_qty_change'=>'<input id="qc'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
// 			 ,'_qty_damaged'=>'<input id="qd'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
// 			 ,'note'=>'<input  id="n'.$loc_sku.'" type="text" value="" style="width:100px">'
// 			 ,'delete'=>($qty_on_hand==0?'<img onclick="remove_prod('.$location_key.','.$data['Part SKU'].')" style="cursor:pointer" title="'._('Remove').' '.$data['Part SKU'].'" alt="'._('Desassociate Product').'" src="art/icons/cross.png".>':'')
// 			 ,'part_sku'=>$data['Part SKU']

                  );
    }
    $response= array(
                   'state'=>200,
                   'data'=>$_data
               );
    echo json_encode($response);


}



function list_families() {


    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent='none';

    if ($parent=='department') {

        $conf=$_SESSION['state']['department'];
        $conf_table='department';

    } else {

        $conf=$_SESSION['state']['families'];
        $conf_table='families';
    }


    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['table']['sf'];
    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];

        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }


    } else
        $number_results=$conf['table']['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['table']['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['table']['order_dir'];

    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['table']['where'];

    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['table']['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['table']['f_value'];



    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $conf['percentages']=$percentages;
    } else
        $percentages=$conf['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $conf['period']=$period;
    } else
        $period=$conf['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $conf['avg']=$avg;
    } else
        $avg=$conf['avg'];




    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];

    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


    $_SESSION['state'][$conf_table]['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
            ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
                                                  );

    $_SESSION['state'][$conf_table]['period']=$period;
    $_SESSION['state'][$conf_table]['mode']=$mode;
    $_SESSION['state'][$conf_table]['restrictions']=$restrictions;
    $_SESSION['state'][$conf_table]['parent']=$parent;



    //  $where.=" and `Product Department Key`=".$id;
    switch ($parent) {
    case('store'):
        $where=sprintf(' where `Product Family Store Key`=%d',$_SESSION['state']['store']['id']);
        break;
    case('department'):
        $where=sprintf(' where `Product Family Main Department Key`=%d',$_SESSION['state']['department']['id']);
        break;
    default:
        $where=sprintf(' where true ');

    }


    $filter_msg='';
    $wheref='';
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and `Product Family Code`  like '".addslashes($f_value)."%'";
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and `Product Family Name`  like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Product Family Dimension`      $where $wheref";
    //print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Product Family Dimension`    $where ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    $rtext=$total_records." ".ngettext('family','families',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp='';



    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with code like")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with this description").": <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with code like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with this description')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';






    $_order=$order;
    $_dir=$order_direction;

    if ($order=='profit') {
        if ($period=='all')
            $order='`Product Family Total Profit`';
        elseif($period=='year')
        $order='`Product Family 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Family 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Family 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Family 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Family Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Family 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Family 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Family 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Family 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='code')
    $order='`Product Family Code`';
    elseif($order=='name')
    $order='`Product Family Name`';
    elseif($order=='active')
    $order='`Product Family For Sale Products`';
    elseif($order=='discontinued')
    $order='`Product Family Discontinued Products`';
    elseif($order=='todo')
    $order='`Product Family In Process Products`';
    elseif($order=='notforsale')
    $order='`Product Family Not For Sale Products`';

    elseif($order=='outofstock')
    $order='`Product Family Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Product Family Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Product Family Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Product Family Optimal Availability Products`';
    elseif($order=='low')
    $order='`Product Family Low Availability Products`';
    elseif($order=='critical')
    $order='`Product Family Critical Availability Products`';




    $sum_active=0;

    $sql="select sum(`Product Family For Sale Products`) as sum_active  from `Product Family Dimension`  $where  ";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

        $sum_active=$row['sum_active'];
    }



    if ($period=='all') {


        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family Total Profit`<0,`Product Family Total Profit`,0)) as total_profit_minus,sum(if(`Product Family Total Profit`>=0,`Product Family Total Profit`,0)) as total_profit_plus,sum(`Product Family Total Invoiced Amount`) as sum_total_sales   from `Product Family Dimension` $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='year') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Year Acc Profit`<0,`Product Family 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Year Acc Profit`>=0,`Product Family 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='quarter') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Quarter Acc Profit`<0,`Product Family 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Quarter Acc Profit`>=0,`Product Family 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='month') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Month Acc Profit`<0,`Product Family 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Month Acc Profit`>=0,`Product Family 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='week') {
        $sum_families=0;
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Week Acc Profit`<0,`Product Family 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Week Acc Profit`>=0,`Product Family 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }



    $sql="select *  from `Product Family Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();
    //  print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $code=sprintf('<a href="family.php?id=%d">%s</a>',$row['Product Family Key'],$row['Product Family Code']);
        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Product Family Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family Total Profit']>=0)
                    $tprofit=percentage($row['Product Family Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product Family 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product Family 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product Family 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product Family 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


        } else {






            if ($period=='all') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family Total Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family Total Days On Sale']>0)
                        $factor=7/$row['Product Family Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family Total Days Available']>0)
                        $factor=30.4368499/$row['Product Family Total Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family Total Days Available']>0)
                        $factor=7/$row['Product Family Total Days Available'];
                    else
                        $factor=0;
                }

                $tsall=money($row['Product Family Total Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family Total Profit']*$factor);




            }
            elseif($period=='year') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Year Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }









                $tsall=money($row['Product Family 1 Year Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Year Acc Profit']*$factor);
            }
            elseif($period=='quarter') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Quarter Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Quarter Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Quarter Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=money($row['Product Family 1 Quarter Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Quarter Acc Profit']*$factor);
            }
            elseif($period=='month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Month Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=money($row['Product Family 1 Month Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Month Acc Profit']*$factor);
            }
            elseif($period=='week') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Week Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Week Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Week Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=money($row['Product Family 1 Week Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Week Acc Profit']*$factor);
            }



        }
        $store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Family Store Key'],$row['Product Family Store Code']);
        $adata[]=array(

                     'code'=>$code,
                     'name'=>$row['Product Family Name'],
                     'active'=>number($row['Product Family For Sale Products']),
                     'todo'=>number($row['Product Family In Process Products']),
                     'discontinued'=>number($row['Product Family Discontinued Products']),
                     'notforsale'=>number($row['Product Family Not For Sale Products']),

                     'outofstock'=>number($row['Product Family Out Of Stock Products']),
                     'stock_error'=>number($row['Product Family Unknown Stock Products']),
                     'stock_value'=>money($row['Product Family Stock Value']),
                     'store'=>$store,
                     'sales'=>$tsall,
                     'profit'=>$tprofit,
                     'surplus'=>number($row['Product Family Surplus Availability Products']),
                     'optimal'=>number($row['Product Family Optimal Availability Products']),
                     'low'=>number($row['Product Family Low Availability Products']),
                     'critical'=>number($row['Product Family Critical Availability Products'])
                 );
    }

    if ($percentages) {
        $tsall='100.00%';
        $tprofit='100.00%';
    } else {
        $tsall=money($sum_total_sales);
        $tprofit=money($sum_total_profit);
    }

    $adata[]=array(

                 'code'=>_('Total'),
                 'name'=>'',
                 'active'=>number($sum_active),
// 		 'outofstock'=>number($row['product family out of stock products']),
// 		 'stockerror'=>number($row['product family unknown stock products']),
// 		 'stock_value'=>money($row['product family stock value']),
		 'sales'=>$tsall,
		 'profit'=>$tprofit

             );

    $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );

    echo json_encode($response);

}

function list_asset_history($asset_type) {

    $id_key='id';
    if ($asset_type=='product') {
        $asset='Product';
        $id_key='tag';
    }
    elseif($asset_type=='family') {
        $asset='Family';
    }
    elseif($asset_type=='department') {
        $asset='Department';
    }
    elseif($asset_type=='store') {
        $asset='Store';
    }



    $conf=$_SESSION['state'][$asset_type]['history'];
    $asset_id=$_SESSION['state'][$asset_type][$id_key];
    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    list($date_interval,$error)=prepare_mysql_dates($from,$to);
    if ($error) {
        list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
    } else {
        $_SESSION['state'][$asset_type]['history']['from']=$from;
        $_SESSION['state'][$asset_type]['history']['to']=$to;
    }

    $_SESSION['state'][$asset_type]['history']=
        array(
            'order'=>$order,
            'order_dir'=>$order_direction,
            'nr'=>$number_results,
            'sf'=>$start_from,
            'where'=>$where,
            'f_field'=>$f_field,
            'f_value'=>$f_value,
            'from'=>$from,
            'to'=>$to,
            'elements'=>$elements
        );
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';




    $wheref='';

    $where=$where.sprintf(" and `Subject`='User'  and ( (`Direct Object`='%s' and `Direct Object Key`=%d) or (`Indirect Object`='%s' and `Indirect Object Key`=%d)  )    "
                          ,$asset
                          ,$asset_id
                          ,$asset
                          ,$asset_id
                         );


    //   $where =$where.$view.sprintf(' and asset_id=%d  %s',$asset_id,$date_interval);

    $sql="select count(*) as total from  `History Dimension`  $where $wheref";
    //  print "$sql";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='')
        $filtered=0;
    else {
        $sql="select count(*) as total from  `History Dimension`  $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
        }

    }


    if ($total==0)
        $rtext=_('No stock movements');
    else
        $rtext=$total.' '.ngettext('stock operation','stock operations',$total);


    if ($order=='date')
        $order='`History Date`';
    else
        $order='`History Date`';


    $sql=sprintf("select  * from `History Dimension` H left join `User Dimension` U on (U.`User Key`=H.`Subject Key`)   $where $wheref order by $order $order_direction limit $start_from,$number_results ");
    // print $sql;
    $result=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



        $tipo=$data['Action'];
        $author=$data['Author Name'];


        $adata[]=array(

                     'author'=>$author
                              ,'tipo'=>$tipo
                                      ,'abstract'=>$data['History Abstract']
                                                  ,'details'=>$data['History Details']
                                                             ,'date'=>strftime("%a %e %b %Y %T", strtotime($data['History Date'])),
                 );
    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'rtext'=>$rtext,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$start_from+$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);

}

function list_stores() {

    $conf=$_SESSION['state']['stores']['table'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];





    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['exchange_type']))
      $exchange_type=addslashes($_REQUEST['exchange_type']);
    else
      $exchange_type=$conf['exchange_type'];

    if (isset( $_REQUEST['exchange_value']))
      $exchange_value=addslashes($_REQUEST['exchange_value']);
    else
      $exchange_value=$conf['exchange_value'];

    if (isset( $_REQUEST['show_default_currency']))
      $show_default_currency=addslashes($_REQUEST['show_default_currency']);
    else
      $show_default_currency=$conf['show_default_currency'];

    


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $_SESSION['state']['stores']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['stores']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['stores']['period']=$period;
    } else
        $period=$_SESSION['state']['stores']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['stores']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['stores']['avg'];

    $_SESSION['state']['stores']['table']=array('exchange_type'=>$exchange_type,'exchange_value'=>$exchange_value,'show_default_currency'=>$show_default_currency,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);
    $where="where true  ";

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='families')
        $order='`Store Families`';
    elseif($order=='departments')
    $order='`Store Departments`';
    elseif($order=='code')
    $order='`Store Code`';
    elseif($order=='todo')
    $order='`Store In Process Products`';
    elseif($order=='discontinued')
    $order='`Store In Process Products`';
    else if ($order=='profit') {
        if ($period=='all')
            $order='`Store Total Profit`';
        elseif($period=='year')
        $order='`Store 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Store 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Store 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Store 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Store Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Store 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Store 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Store 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Store 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='name')
    $order='`Store Name`';
    elseif($order=='active')
    $order='`Store For Sale Products`';
    elseif($order=='outofstock')
    $order='`Store Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Store Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Store Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Store Optimal Availability Products`';
    elseif($order=='low')
    $order='`Store Low Availability Products`';
    elseif($order=='critical')
    $order='`Store Critical Availability Products`';



    $sql="select sum(`Store For Sale Products`) as sum_active,sum(`Store Families`) as sum_families  from `Store Dimension` $where $wheref   ";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $sum_families=$row['sum_families'];
        $sum_active=$row['sum_active'];
    }
    mysql_free_result($result);

    global $myconf;

    if ($period=='all') {


        $sum_total_sales=0;
        $sum_month_sales=0;
	$sum_total_profit_plus=0;
	$sum_total_profit_minus=0;
	$sum_total_profit=0;
  	if($exchange_type=='day2day'){
	  $sql=sprintf("select sum(if(`Store DC Total Profit`<0,`Store DC Total Profit`,0)) as total_profit_minus,sum(if(`Store DC Total Profit`>=0,`Store DC Total Profit`,0)) as total_profit_plus,sum(`Store DC Total Invoiced Amount`) as sum_total_sales  from `Store Default Currency`  S   %s %s",$where,$wheref);
	  //	  print $sql;
	  $result=mysql_query($sql);
	  if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	    
            $sum_total_sales+=$row['sum_total_sales'];

            $sum_total_profit_plus=+$row['total_profit_plus'];
            $sum_total_profit_minus=+$row['total_profit_minus'];
            $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
	  }
	  mysql_free_result($result);
	}else{
	  $sql=sprintf("select sum(if(`Store Total Profit`<0,`Store Total Profit`,0)) as total_profit_minus,sum(if(`Store Total Profit`>=0,`Store Total Profit`,0)) as total_profit_plus,sum(`Store Total Invoiced Amount`) as sum_total_sales  from `Store Dimension`  S   %s %s and `Store Currency Code`!= %s ",$where,$wheref,prepare_mysql($myconf['currency_code']));
	//print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales+=$row['sum_total_sales']*$exchange_value;

            $sum_total_profit_plus+=$row['total_profit_plus']*$exchange_value;
            $sum_total_profit_minus+=$row['total_profit_minus']*$exchange_value;
            $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

	}



    }
    elseif($period=='year') {

        $sum_total_sales=0;
        $sum_month_sales=0;
	$sum_total_profit_plus=0;
	$sum_total_profit_minus=0;
	$sum_total_profit=0;


       
	if($exchange_type=='day2day'){
	  $sql=sprintf("select sum(if(`Store DC 1 Year Acc Profit`<0,`Store DC 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store DC 1 Year Acc Profit`>=0,`Store DC 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Store DC 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Store Default Currency`  S   %s %s",$where,$wheref);
	  //print $sql;
	  $result=mysql_query($sql);
	  if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	    
            $sum_total_sales+=$row['sum_total_sales'];

            $sum_total_profit_plus=+$row['total_profit_plus'];
            $sum_total_profit_minus=+$row['total_profit_minus'];
            $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
	  }
	  mysql_free_result($result);
	}else{
	  $sql=sprintf("select sum(if(`Store 1 Year Acc Profit`<0,`Store 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Year Acc Profit`>=0,`Store 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Store 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Store Dimension`  S   %s %s and `Store Currency Code`!= %s ",$where,$wheref,prepare_mysql($myconf['currency_code']));
	  //print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales+=$row['sum_total_sales']*$exchange_value;

            $sum_total_profit_plus+=$row['total_profit_plus']*$exchange_value;
            $sum_total_profit_minus+=$row['total_profit_minus']*$exchange_value;
            $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

	}
	
	
	


    }
    elseif($period=='quarter') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 1 Quarter Acc Profit`<0,`Store 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Quarter Acc Profit`>=0,`Store 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Store For Sale Products`) as sum_active,sum(`Store 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

    }
    elseif($period=='month') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 1 Month Acc Profit`<0,`Store 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Month Acc Profit`>=0,`Store 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Store For Sale Products`) as sum_active,sum(`Store 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

    }
    elseif($period=='week') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 1 Week Acc Profit`<0,`Store 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Week Acc Profit`>=0,`Store 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Store For Sale Products`) as sum_active,sum(`Store 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension`   $where $wheref  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

    }



    $sql="select *  from `Store Dimension` S  left join `Store Default Currency` DC on DC.`Store Key`=S.`Store Key`   $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
    $adata=array();
    $sum_sales=0;
    $sum_profit=0;
    $sum_outofstock=0;
    $sum_low=0;
    $sum_optimal=0;
    $sum_critical=0;
    $sum_surplus=0;
    $sum_unknown=0;
    $sum_departments=0;
    $sum_families=0;
    $sum_todo=0;
    $sum_discontinued=0;

    $DC_tag='';
    if($exchange_type=='day2day' and $show_default_currency  )
      $DC_tag=' DC';

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);

        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Store DC Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC Total Profit']>=0)
                    $tprofit=percentage($row['Store DC Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Store DC 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Store DC 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Store DC 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Store DC 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


        } else {






            if ($period=="all") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." Total Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store".$DC_tag." Total Days On Sale"]>0)
                        $factor=7/$row["Store".$DC_tag." Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store".$DC_tag." Total Days Available"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." Total Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store".$DC_tag." Total Days Available"]>0)
                        $factor=7/$row["Store".$DC_tag." Total Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Store".$DC_tag." Total Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." Total Profit"]*$factor);




            }
            elseif($period=="year") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store".$DC_tag." 1 Year Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store".$DC_tag." 1 Year Acc Days Available"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }









                $tsall=($row["Store".$DC_tag." 1 Year Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Year Acc Profit"]*$factor);
            }
            elseif($period=="quarter") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store".$DC_tag." 1 Quarter Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store".$DC_tag." 1 Quarter Acc Days Available"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Store".$DC_tag." 1 Quarter Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Quarter Acc Profit"]*$factor);
            }
            elseif($period=="month") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store".$DC_tag." 1 Month Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store".$DC_tag." 1 Month Acc Days Available"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Store".$DC_tag." 1 Month Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Month Acc Profit"]*$factor);
            }
            elseif($period=="week") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store".$DC_tag." 1 Week Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store".$DC_tag." 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store".$DC_tag." 1 Week Acc Days Available"]>0)
                        $factor=7/$row["Store".$DC_tag." 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Store".$DC_tag." 1 Week Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Week Acc Profit"]*$factor);
            }



        }

        $sum_sales+=$tsall;
        $sum_profit+=$tprofit;
        $sum_low+=$row['Store Low Availability Products'];
        $sum_optimal+=$row['Store Optimal Availability Products'];
        $sum_low+=$row['Store Low Availability Products'];
        $sum_critical+=$row['Store Critical Availability Products'];
        $sum_surplus+=$row['Store Surplus Availability Products'];
        $sum_outofstock+=$row['Store Out Of Stock Products'];
        $sum_unknown+=$row['Store Unknown Stock Products'];
        $sum_departments+=$row['Store Departments'];
        $sum_families+=$row['Store Families'];
        $sum_todo+=$row['Store In Process Products'];
        $sum_discontinued+=$row['Store Discontinued Products'];

	
	if(!$percentages){
	  if($show_default_currency){
	    $class='';
	    if($myconf['currency_code']!=$row['Store Currency Code'])
	      $class='currency_exchanged';
	    
	    
	    $sales='<span class="'.$class.'">'.money($tsall).'</span>';
	    $profit='<span class="'.$class.'">'.money($tprofit).'</span>';
	  }else{
	    $sales=money($tsall,$row['Store Currency Code']);
	    $profit=money($tprofit,$row['Store Currency Code']);
	  }
	}else{
	  $sales=$tsall;
	  $profit=$tprofit;
	}	  

        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'departments'=>number($row['Store Departments']),
                     'families'=>number($row['Store Families']),
                     'active'=>number($row['Store For Sale Products']),
                     'todo'=>number($row['Store In Process Products']),
                     'discontinued'=>number($row['Store Discontinued Products']),
                     'outofstock'=>number($row['Store Out Of Stock Products']),
                     'stock_error'=>number($row['Store Unknown Stock Products']),
                     'stock_value'=>money($row['Store Stock Value']),
                     'surplus'=>number($row['Store Surplus Availability Products']),
                     'optimal'=>number($row['Store Optimal Availability Products']),
                     'low'=>number($row['Store Low Availability Products']),
                     'critical'=>number($row['Store Critical Availability Products']),
                     'sales'=>$sales,
                     'profit'=>$profit

                 );
    }
    mysql_free_result($res);

    if ($percentages) {
        $sum_sales='100.00%';
        $sum_profit='100.00%';
//       $sum_low=
//   $sum_optimal=$row['Store Optimal Availability Products'];
//   $sum_low=$row['Store Low Availability Products'];
//   $sum_critical=$row['Store Critical Availability Products'];
//   $sum_surplus=$row['Store Surplus Availability Products'];
    } else {
        $sum_sales=money($sum_total_sales);
        $sum_profit=money($sum_total_profit);
    }

    $sum_outofstock=number($sum_outofstock);
    $sum_low=number($sum_low);
    $sum_optimal=number($sum_optimal);
    $sum_critical=number($sum_critical);
    $sum_surplus=number($sum_surplus);
    $sum_unknown=number($sum_unknown);
    $sum_departments=number($sum_departments);
    $sum_families=number($sum_families);
    $sum_todo=number($sum_todo);
    $sum_discontinued=number($sum_discontinued);
    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),
                 'active'=>number($sum_active),
                 'sales'=>$sum_sales,
                 'profit'=>$sum_profit,
                 'todo'=>$sum_todo,
                 'discontinued'=>$sum_discontinued,
                 'low'=>$sum_low,
                 'critical'=>$sum_critical,
                 'surplus'=>$sum_surplus,
                 'optimal'=>$sum_optimal,
                 'outofstock'=>$sum_outofstock,
                 'stock_error'=>$sum_unknown,
                 'departments'=>$sum_departments,
                 'families'=>$sum_families
             );


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}

function list_charges() {


    $parent='store';

    if ( isset($_REQUEST['parent']))
        $parent= $_REQUEST['parent'];

    if ($parent=='store')
        $parent_id=$_SESSION['state']['store']['id'];
    else
        return;

    $conf=$_SESSION['state'][$parent]['charges'];




    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];


    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;





    $_SESSION['state'][$parent]['charges']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);
    if ($parent=='store')
        $where=sprintf("where  `Store Key`=%d ",$parent_id);
    else
        $where=sprintf("where true ");

    $filter_msg='';
    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and  CONCAT(`Charge Description`,' ',`Charge Terms Description`) like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Charge Name` like '".addslashes($f_value)."%'";








    $sql="select count(*) as total from `Charge Dimension`   $where $wheref";
    // print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total `Charge Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('charge','charges',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with this name ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with description like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='name')
        $order='`Charge Name`';
    elseif($order=='description')
    $order='`Charge Description`,`Charge Terms Description`';
    else
        $order='`Charge Name`';


    $sql="select *  from `Charge Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $adata[]=array(
                     'name'=>$row['Charge Name'],
                     'description'=>$row['Charge Description'].' '.$row['Charge Terms Description'],


                 );
    }
    mysql_free_result($res);



    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}


function list_campaigns() {


    $parent='store';

    if ( isset($_REQUEST['parent']))
        $parent= $_REQUEST['parent'];

    if ($parent=='store')
        $parent_id=$_SESSION['state']['store']['id'];
    else
        return;

    $conf=$_SESSION['state'][$parent]['campaigns'];


    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];


    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    $_SESSION['state'][$parent]['campaigns']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

    if ($parent=='store')
        $where=sprintf("where  `Store Key`=%d    ",$parent_id);
    else
        $where=sprintf("where true ");;

    $filter_msg='';
    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and  `Campaign Description` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Campaign Name` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Campaign Dimension`   $where $wheref";
    //  print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total `Campaign Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('campaign','campaigns',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with this name ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with description like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='name')
        $order='`Campaign Name`';
    elseif($order=='description')
    $order='`Campaign Description`';
    else
        $order='`Campaign Name`';


    $sql="select *  from `Campaign Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $sql=sprintf("select * from `Campaign Deal Schema`  where `Campaign Key`=%d  ",$row['Campaign Key']);
        $res2 = mysql_query($sql);
        $deals='<ul style="padding:10px 20px">';
        while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
            $deals.=sprintf("<li style='list-style-type: circle' >%s</li>",$row2['Deal Name']);
        }
        $deals.='</ul>';
        $adata[]=array(
                     'name'=>$row['Campaign Name'],
                     'description'=>$row['Campaign Description'].$deals


                 );
    }
    mysql_free_result($res);



    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}


function list_deals() {


    $parent='store';

    if ( isset($_REQUEST['parent']))
        $parent= $_REQUEST['parent'];

    if ($parent=='store')
        $parent_id=$_SESSION['state']['store']['id'];
    elseif($parent=='department')
    $parent_id=$_SESSION['state']['department']['id'];
    elseif($parent=='family')
    $parent_id=$_SESSION['state']['family']['id'];
    elseif($parent=='product')
    $parent_id=$_SESSION['state']['product']['pid'];
    else
        return;

    $conf=$_SESSION['state'][$parent]['deals'];


    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];


    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    $_SESSION['state'][$parent]['deals']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

    if ($parent=='store')
        $where=sprintf("where  `Store Key`=%d and `Deal Trigger`='Order'    ",$parent_id);
    elseif($parent=='department')
    $where=sprintf("where    `Deal Trigger`='Department' and  `Deal Trigger Key`=%d   ",$parent_id);
    elseif($parent=='family')
    $where=sprintf("where    `Deal Trigger`='Family' and  `Deal Trigger Key`=%d   ",$parent_id);
    elseif($parent=='product')
    $where=sprintf("where    `Deal Trigger`='Product' and  `Deal Trigger Key`=%d   ",$parent_id);
    else
        $where=sprintf("where true ");;
    // print "$parent $where";
    $filter_msg='';
    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Deal Dimension`   $where $wheref";
    //  print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total `Deal Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('deal','deals',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='name')
        $order='`Deal Name`';
    elseif($order=='description')
    $order='`Deal Terms Description`,`Deal Allowance Description`';
    else
        $order='`Deal Name`';


    $sql="select *  from `Deal Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



        $adata[]=array(
                     'name'=>$row['Deal Name'],
                     'description'=>$row['Deal Terms Description'].' &rArr; '.$row['Deal Allowance Description']


                 );
    }
    mysql_free_result($res);



    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);

}


function list_customers_per_store() {

    $conf=$_SESSION['state']['stores']['customers'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];





    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];



    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
      
    } else
        $percentages=$_SESSION['state']['stores']['customers']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else
        $period=$_SESSION['state']['stores']['customers']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else
        $avg=$_SESSION['state']['stores']['customers']['avg'];

    $_SESSION['state']['stores']['customers']=array(
						    'percentages'=>$percentages
						    ,'period'=>$period
						    ,'avg'=>$avg
						    ,'order'=>$order
						    ,'order_dir'=>$order_direction
						    ,'nr'=>$number_results
						    ,'sf'=>$start_from
						    ,'where'=>$where
						    ,'f_field'=>$f_field
						    ,'f_value'=>$f_value
						    );
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);
    $where="where true  ";

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

 
    if($order=='code')
    $order='`Store Code`';
    elseif($order=='name')
      $order='`Store Name`';
    elseif($order=='total')
      $order='total';
    elseif($order=='active')
      $order='active';
    elseif($order=='new')
      $order='new';
    else
      $order='`Store Code`';

     $total_customers=0;
     $sql="select  count(*) as customers, sum(IF(`New Customer`='Yes',1,0)) as new,sum(IF(`Active Customer`='Yes',1,0)) as active   from `Customer Dimension`  $where     ";
  //print $sql;
    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total_customers=$row['customers'];
      $total_new=$row['new'];
      $total_active=$row['active'];
    }
    




    $sql="select `Store Name`,`Store Code`,`Store Key`, count(*) as total,sum(IF(`New Customer`='Yes',1,0)) as new,sum(IF(`Active Customer`='Yes',1,0)) as active   from `Customer Dimension` S left join `Store Dimension` on `Store Key`=`Customer Store Key`  $where $wheref group by `Customer Store Key`  order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
  

   
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="customers.php?store=%d">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="customers.php?store=%d">%s</a>',$row['Store Key'],$row['Store Code']);

	if($percentages){
	  $total=percentage($row['total'],$total_customers);
	  $active=percentage($row['active'],$total_active);
	  $new=percentage($row['new'],$total_customersnew);

	}else{
	  $total=number($row['total']);
	  $active=number($row['active']);
	  $new=number($row['new']);
	}

        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'total'=>$total,
		     'active'=>$active,
		     'new'=>$new
                 );
    }
    mysql_free_result($res);

    if ($percentages) {
        $sum_total='100.00%';
	$sum_active='100.00%';
	$sum_new='100.00%';
    } else {
      $sum_total=number($total_customers);
      $sum_active=number($total_active);
      $sum_new=number($total_new);
    }

  
    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),
		 'total'=>$sum_total,
		 'active'=>$sum_active,
		 'new'=>$sum_new

             );


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}



function list_orders_per_store() {

    $conf=$_SESSION['state']['stores']['orders'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];





    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];



    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
      
    } else
        $percentages=$_SESSION['state']['stores']['orders']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else
        $period=$_SESSION['state']['stores']['orders']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else
        $avg=$_SESSION['state']['stores']['orders']['avg'];

    $_SESSION['state']['stores']['orders']=array(
						    'percentages'=>$percentages						    ,'period'=>$period
						    ,'avg'=>$avg
						    ,'order'=>$order
						    ,'order_dir'=>$order_direction
						    ,'nr'=>$number_results
						    ,'sf'=>$start_from
						    ,'where'=>$where
						    ,'f_field'=>$f_field
						    ,'f_value'=>$f_value
						    );
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);
    $where="where true  ";

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

 
    if($order=='code')
    $order='`Store Code`';
    elseif($order=='name')
      $order='`Store Name`';
    elseif($order=='orders')
      $order='orders';
    elseif($order=='cancelled')
      $order='cancelled';
    elseif($order=='unknown')
      $order='unknown';
    elseif($order=='paid')
      $order='paid';
    elseif($order=='pending')
      $order='panding';
    else


      $order='`Store Code`';

     
    $total_orders=0;
    $total_unknown=0;
    $total_dispached=0;
    $total_cancelled=0;
    $total_todo=0;
    $total_paid=0;

     $sql="select  count(*) as orders,sum(IF(`Order Current Payment State`='Paid',1,0)) as paid,sum(IF(`Order Current Dispatch State`='Cancelled',1,0)) as cancelled ,sum(IF(`Order Current Dispatch State`='Unknown',1,0)) as unknown ,sum(IF(`Order Current Dispatch State`='Dispached',1,0)) as dispached   from `Order Dimension`  $where     ";
  //print $sql;
    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $total_orders=$row['orders'];
      $total_unknown=$row['unknown'];
      $total_dispached=$row['dispached'];
      $total_cancelled=$row['cancelled'];
      $total_todo=$total_orders-$total_cancelled-$total_dispached-$total_unknown;
      $total_paid=$row['paid'];

 }
    




    $sql="select `Store Name`,`Store Code`,`Store Key`, count(*) as orders  ,sum(IF(`Order Current Payment State`='Paid',1,0)) as paid,sum(IF(`Order Current Dispatch State`='Cancelled',1,0)) as cancelled ,sum(IF(`Order Current Dispatch State`='Unknown',1,0)) as unknown ,sum(IF(`Order Current Dispatch State`='Dispached',1,0)) as dispached   from `Order Dimension` S left join `Store Dimension` on `Store Key`=`Order Store Key`  $where $wheref group by `Order Store Key`  order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
  

   
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="orders.php?store=%d">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="orders.php?store=%d">%s</a>',$row['Store Key'],$row['Store Code']);

	$todo=$row['orders']-$row['unknown']-$row['dispached']-$row['cancelled'];
	if($percentages){
	  $orders=percentage($row['orders'],$total_orders);
	  $cancelled=percentage($row['cancelled'],$total_cancelled);
	  $paid=percentage($row['paid'],$total_paid);
	  $unknown=percentage($row['unknown'],$total_unknown);
	  $todo=percentage($todo,$total_todo);
	  $dispached=percentage($row['dispached'],$total_dispached);

	}else{
	  $orders=number($row['orders']);
	  $cancelled=number($row['cancelled']);
	  $paid=number($row['paid']);
	  $unknown=number($row['unknown']);
	  $todo=number($todo);
	  $dispached=number($row['dispached']);
	  
	}

        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'orders'=>$orders,
		     'paid'=>$paid,
		     'unknown'=>$unknown,
		     'cancelled'=>$cancelled,
		     'dispached'=>$dispached,
		     'pending'=>$todo,

                 );
    }
    mysql_free_result($res);

    if ($percentages) {
        $sum_orders='100.00%';
	$sum_cancelled='100.00%';
	$sum_paid='100.00%';
	$sum_unknown='100.00%';
		
    } else {
      $sum_orders=number($total_orders);
      $sum_cancelled=number($total_cancelled);
      $sum_paid=number($total_paid);
      $sum_unknown=number($total_unknown);
      $sum_todo=number($total_todo);
      $sum_dispached=number($total_dispached);
      
    }

  
    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),
		 'orders'=>$sum_orders,
		 'unknown'=>$sum_unknown,
		 'paid'=>$sum_paid,
		 'cancelled'=>$sum_cancelled,
		 'dispached'=>$sum_dispached,
		 'pending'=>$sum_todo,

             );


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}


function product_code_timeline() {

  
  
 


  $conf=$_SESSION['state']['product']['code_timeline'];
  //print_r($conf);
  $tableid=0;
  if (isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];


    if (isset( $_REQUEST['code']))
        $code=$_REQUEST['code'];
    else
        $code=$conf['code'];


    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];


   
    $where=sprintf('where `Product Code`=%s  ',prepare_mysql($code));
   
    $wheref='';

    $order_direction=$order_dir;
    $_order=$order;
    $_dir=$order_direction;
    if ($order=='pid')
        $order='`Product ID`';
    if ($order=='from')
      $order='`Product History Valid From`';
    if ($order=='to')
      $order='`Product History Valid To`';
    else
      $order='`Product History Valid From`';


    $sql="select * from `Product History Dimension` PH left join `Product Dimension`  P on (P.`Product ID`=PH.`Product ID`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    // print $sql;
    $res = mysql_query($sql);
    $number_results=mysql_num_rows($res);

    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      $id=sprintf("<a href='product.php?pid=%d'>%05d</a> <a href='product.php?key=%d'>(%05d)</a>"
      ,$row['Product ID'],$row['Product ID']
      ,$row['Product Key'] ,$row['Product Key']
      
      );
        $adata[]=array(
                     'pid'=>$id
		     ,'description'=>$row['Product History XHTML Short Description']
		    
		     ,'parts'=>$row['Product XHTML Parts']
		     ,'from'=>strftime("%e %b %Y", strtotime($row['Product History Valid From']))
		     ,'to'=>strftime("%e %b %Y", strtotime($row['Product History Valid To']))
		   ,'sales'=>money($row['Product History Total Invoiced Amount'],$row['Product Currency'])
                 );

    }
    mysql_free_result($res);
    $rtext=number($number_results).' '._('products with the same code');
    $rtext_rpp='';
    $filter_msg='';
    $total_records=$number_results;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );

    echo json_encode($response);
}

function list_product_subcategories(){
 $conf=$_SESSION['state']['product_categories']['subcategories'];
 $conf2=$_SESSION['state']['product_categories'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];





    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['exchange_type'])){
      $exchange_type=addslashes($_REQUEST['exchange_type']);
      $_SESSION['state']['product_categories']['exchange_type']=$exchange_type;
    }else
      $exchange_type=$conf2['exchange_type'];

    if (isset( $_REQUEST['exchange_value'])){
      $exchange_value=addslashes($_REQUEST['exchange_value']);
      $_SESSION['state']['product_categories']['exchange_value']=$exchange_value;
    }else
      $exchange_value=$conf2['exchange_value'];

    if (isset( $_REQUEST['show_default_currency'])){
      $show_default_currency=addslashes($_REQUEST['show_default_currency']);
      $_SESSION['state']['product_categories']['show_default_currency']=$show_default_currency;
    }else
      $show_default_currency=$conf2['show_default_currency'];

    


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $_SESSION['state']['product_categories']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['product_categories']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['product_categories']['period']=$period;
    } else
        $period=$_SESSION['state']['product_categories']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['product_categories']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['product_categories']['avg'];

    if (isset( $_REQUEST['stores_mode'])) {
      $stores_mode=$_REQUEST['stores_mode'];
      $_SESSION['state']['product_categories']['stores_mode']=$stores_mode;
    } else
      $stores_mode=$_SESSION['state']['product_categories']['stores_mode'];

    $_SESSION['state']['product_categories']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    if (isset( $_REQUEST['category'])) {
      $root_category=$_REQUEST['category'];
      $_SESSION['state']['product_categories']['category']=$avg;
    } else
      $root_category=$_SESSION['state']['product_categories']['category'];


    


    $where=sprintf("where `Category Position` like '%d>%%'  and `Category Deep`>1 ",$root_category);
    
    if($stores_mode=='grouped')
      $group=' group by `Category Key`';
    else
      $group='';

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Category Name` like '%".addslashes($f_value)."%'";




    $sql="select count(*) as total   from `Category Dimension` S  left join `Product Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)  $where $wheref";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Category Dimension` S  left join `Product Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('category','categories',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
   
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
      
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='families')
        $order='`Product Category Families`';
    elseif($order=='departments')
    $order='`Product Category Departments`';
    elseif($order=='code')
    $order='`Product Category Code`';
    elseif($order=='todo')
    $order='`Product Category In Process Products`';
    elseif($order=='discontinued')
    $order='`Product Category In Process Products`';
    else if ($order=='profit') {
        if ($period=='all')
            $order='`Product Category Total Profit`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Category Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='name')
    $order='`Category Name`';
    elseif($order=='active')
    $order='`Product Category For Sale Products`';
    elseif($order=='outofstock')
    $order='`Product Category Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Product Category Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Product Category Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Product Category Optimal Availability Products`';
    elseif($order=='low')
    $order='`Product Category Low Availability Products`';
    elseif($order=='critical')
    $order='`Product Category Critical Availability Products`';





    $sql="select *  from `Category Dimension` S  left join `Product Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)   $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
    // print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
    $adata=array();
    $sum_sales=0;
    $sum_profit=0;
    $sum_outofstock=0;
    $sum_low=0;
    $sum_optimal=0;
    $sum_critical=0;
    $sum_surplus=0;
    $sum_unknown=0;
    $sum_departments=0;
    $sum_families=0;
    $sum_todo=0;
    $sum_discontinued=0;

    $DC_tag='';
    if($exchange_type=='day2day' and $show_default_currency  )
      $DC_tag=' DC';

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
      //$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Name']);
        //$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Code']);

        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Product Category DC Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC Total Profit']>=0)
                    $tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product Category DC 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product Category DC 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product Category DC 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product Category DC 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Category DC 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


        } else {






            if ($period=="all") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." Total Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." Total Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." Total Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." Total Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." Total Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." Total Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Product Category".$DC_tag." Total Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." Total Profit"]*$factor);




            }
            elseif($period=="year") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }









                $tsall=($row["Product Category".$DC_tag." 1 Year Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Year Acc Profit"]*$factor);
            }
            elseif($period=="quarter") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Product Category".$DC_tag." 1 Quarter Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Quarter Acc Profit"]*$factor);
            }
            elseif($period=="month") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Product Category".$DC_tag." 1 Month Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Month Acc Profit"]*$factor);
            }
            elseif($period=="week") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
                        $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
                        $factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Product Category".$DC_tag." 1 Week Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Product Category".$DC_tag." 1 Week Acc Profit"]*$factor);
            }



        }

        $sum_sales+=$tsall;
        $sum_profit+=$tprofit;
        $sum_low+=$row['Product Category Low Availability Products'];
        $sum_optimal+=$row['Product Category Optimal Availability Products'];
        $sum_low+=$row['Product Category Low Availability Products'];
        $sum_critical+=$row['Product Category Critical Availability Products'];
        $sum_surplus+=$row['Product Category Surplus Availability Products'];
        $sum_outofstock+=$row['Product Category Out Of Stock Products'];
        $sum_unknown+=$row['Product Category Unknown Stock Products'];
        $sum_departments+=$row['Product Category Departments'];
        $sum_families+=$row['Product Category Families'];
        $sum_todo+=$row['Product Category In Process Products'];
        $sum_discontinued+=$row['Product Category Discontinued Products'];

	
	if(!$percentages){
	  if($show_default_currency){
	    $class='';
	    if($myconf['currency_code']!=$row['Product Category Currency Code'])
	      $class='currency_exchanged';
	    
	    
	    $sales='<span class="'.$class.'">'.money($tsall).'</span>';
	    $profit='<span class="'.$class.'">'.money($tprofit).'</span>';
	  }else{
	    $sales=money($tsall,$row['Product Category Currency Code']);
	    $profit=money($tprofit,$row['Product Category Currency Code']);
	  }
	}else{
	  $sales=$tsall;
	  $profit=$tprofit;
	}
	if($stores_mode=='grouped')
	  $name=$row['Product Category Key'].' '.$row['Category Name'];
	else
	  $name=$row['Product Category Key'].' '.$row['Category Name']." (".$row['Product Category Store Key'].")";
        $adata[]=array(
		       'id'=>$row['Category Key'],
		       'name'=>$name,
		       'departments'=>number($row['Product Category Departments']),
		       'families'=>number($row['Product Category Families']),
		       'active'=>number($row['Product Category For Sale Products']),
		       'todo'=>number($row['Product Category In Process Products']),
		       'discontinued'=>number($row['Product Category Discontinued Products']),
		       'outofstock'=>number($row['Product Category Out Of Stock Products']),
		       'stock_error'=>number($row['Product Category Unknown Stock Products']),
		       'stock_value'=>money($row['Product Category Stock Value']),
		       'surplus'=>number($row['Product Category Surplus Availability Products']),
		       'optimal'=>number($row['Product Category Optimal Availability Products']),
		       'low'=>number($row['Product Category Low Availability Products']),
		       'critical'=>number($row['Product Category Critical Availability Products']),
		       'sales'=>$sales,
		       'profit'=>$profit
		      

                 );
    }
    mysql_free_result($res);

   /*  if ($percentages) { */
/*         $sum_sales='100.00%'; */
/*         $sum_profit='100.00%'; */
/* //       $sum_low= */
/* //   $sum_optimal=$row['Product Category Optimal Availability Products']; */
/* //   $sum_low=$row['Product Category Low Availability Products']; */
/* //   $sum_critical=$row['Product Category Critical Availability Products']; */
/* //   $sum_surplus=$row['Product Category Surplus Availability Products']; */
/*     } else { */
/*         $sum_sales=money($sum_total_sales); */
/*         $sum_profit=money($sum_total_profit); */
/*     } */

/*     $sum_outofstock=number($sum_outofstock); */
/*     $sum_low=number($sum_low); */
/*     $sum_optimal=number($sum_optimal); */
/*     $sum_critical=number($sum_critical); */
/*     $sum_surplus=number($sum_surplus); */
/*     $sum_unknown=number($sum_unknown); */
/*     $sum_departments=number($sum_departments); */
/*     $sum_families=number($sum_families); */
/*     $sum_todo=number($sum_todo); */
/*     $sum_discontinued=number($sum_discontinued); */
/*     $adata[]=array( */

/*                  'name'=>_('Total'), */
/*                  'active'=>number($sum_active), */
/*                  'sales'=>$sum_sales, */
/*                  'profit'=>$sum_profit, */
/*                  'todo'=>$sum_todo, */
/*                  'discontinued'=>$sum_discontinued, */
/*                  'low'=>$sum_low, */
/*                  'critical'=>$sum_critical, */
/*                  'surplus'=>$sum_surplus, */
/*                  'optimal'=>$sum_optimal, */
/*                  'outofstock'=>$sum_outofstock, */
/*                  'stock_error'=>$sum_unknown, */
/*                  'departments'=>$sum_departments, */
/*                  'families'=>$sum_families */
/*              ); */


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    //   $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}


?>