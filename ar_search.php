<?php
require_once 'common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('product'):
case('product_manage_stock'):
case('edit_product'):
 $q=$_REQUEST['q'];
search_products($q,$tipo,$user);
   
    return;
case('location'):
    $q=$_REQUEST['q'];
     search_location($q,$tipo,$user);
    break;
case('customer_name'):
   search_customer_name($user)
   break;

default:
    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);

}

function search_customer_name($user){
 $target='customer.php';
    $q=$_REQUEST['q'];
    $sql=sprintf("select id,name from customer where name=%s ",prepare_mysql($q));
    $result=mysql_query($sql);

    $number_results=$result->numRows();
    if ($number_results==1) {
        if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $url=$target.'?id='. $found['id'];
            echo json_encode(array('state'=>200,'url'=>$url));
            return;
        }
    }
    elseif($number_results>1) {
        $url='';
        while ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $url.=sprintf('<href="%s?id=%d">%s (%d)</a><br>',$target,$found['name'],$found['id'],$found['id']);

        }

        echo json_encode(array('state'=>200,'url'=>$url));
        return;
        echo json_encode(array('state'=>400,'msg1'=>_('There are')." $number_results "._('customers with this name'),'msg2'=>$url));
        return;

    }
mysql_free_result($result);




    // try to find aprox names

    $sql=sprintf("select damlev(UPPER(%s),UPPER(name))/LENGTH(name) as dist1,    damlev(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(name))) as dist2,name,id from customer  order by dist1,dist2 limit 5;",prepare_mysql($q),prepare_mysql($q));
    $result=mysql_query($sql);
    // print $sql;
    $msg2='';
    while ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
        if ($found['dist1']<.5) {
            $msg2.=sprintf(', <a href="%s?id=%d">%s</a>',$target,$found['id'],$found['name']);
        }
    }
    mysql_free_result($result);
    if ($msg2!='') {
        $msg2=preg_replace('/^\,\s*/','',$msg2);
        echo json_encode(array('state'=>400,'msg1'=>_('Did you mean').":",'msg2'=>$msg2));
        return;
    }

    echo json_encode(array('state'=>500,'msg'=>_('Customer not found')));
    return;




}

function search_products($q,$tipo,$user){
 if ($tipo=='product_manage_stock')
        $target='product_manage_stock.php';
    else
        $target='product.php';

    $q=$_REQUEST['q'];
    $sql=sprintf("select `Product Code`  from `Product Dimension` where `Product Code`='%s'  and `Product Store Key` in (%s)     "
		 ,addslashes($q)
		 ,join(',',$user->stores)
		 );
    $res = mysql_query($sql);
    if ($found=mysql_fetch_array($res)) {
        $url=$target.'?code='. $found['Product Code'];
        echo json_encode(array('state'=>200,'url'=>$url));
        mysql_free_result($res);
        return;
    }
    mysql_free_result($res);
  
    if ($tipo=='product') {
        $sql=sprintf("select `Product Family Key` as id  from `Product Family Dimension` where `Product Family Code`='%s' and `Product Family Store Key` in (%s)   "
		     ,addslashes($q)
		     ,join(',',$user->stores)
		     );
        $result=mysql_query($sql);
        if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $url='family.php?id='. $found['id'];
            echo json_encode(array('state'=>200,'url'=>$url));
            mysql_free_result($result);
            return;
        }
        mysql_free_result($result);
    }
    // try to get similar results
    //   if($myconf['product_code_separator']!=''){
    if (  ($myconf['product_code_separator']!='' and   preg_match('/'.$myconf['product_code_separator'].'/',$q)) or  $myconf['product_code_separator']==''  ) {
        $sql=sprintf("select damlev(UPPER(%s),UPPER(`Product Code`)) as dist1,    damlev(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Product Code`))) as dist2,        `Product Code` as code,`product id` as id from `Product Dimension`  where  Product  Store Key` in (%s)     order by dist1,dist2 limit 1;"
		     ,prepare_mysql($q)
		     ,prepare_mysql($q)
		     ,join(',',$user->stores)
		     );
        $result=mysql_query($sql);
        if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($found['dist1']<3) {
                echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="'.$target.'?pid='.$found['id'].'">'.$found['code'].'</a>'));
                mysql_free_result($result);
                return;
            }
        }
        mysql_free_result($result);


    }
    elseif($tipo=='product') {
        // look on the family list
      $sql=sprintf("select damlev(UPPER(%s),UPPER(`Product Family Code`)) as dist1, damlev(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Product Family Code`))) as dist2, `Product Family Code` as name ,`Product Family Key` id from `Product Family Dimension`  where  `Product Family Store Key` in (%s)     order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q),join(',',$user->stores));
        $result=mysql_query($sql);
        if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($found['dist1']<3) {
                echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="family.php?id='.$found['id'].'">'.$found['name'].'</a> '._('family') ));
                

                    return;
            }
        }
        
        mysql_free_result($result);
    }
    
    echo json_encode(array('state'=>500,'msg'=>_('Product not found')));
}


function search_location($q,$tipo,$user){
    $sql=sprintf("select id from location where name='%s' ",addslashes($q));
    $result=mysql_query($sql);
    if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $url='location.php?id='. $found['id'];
        echo json_encode(array('state'=>200,'url'=>$url));
        return;
    }
    mysql_free_result($result);
    $sql=sprintf("select damlev(UPPER(%s),UPPER(name)) as dist1,    damlev(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(name))) as dist2,name,id from location  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
    $result=mysql_query($sql);
    if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
        if ($found['dist1']<3) {
            echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="location.php?id='.$found['id'].'">'.$found['name'].'</a>'));
            return;
        }
    }
    mysql_free_result($result);
    echo json_encode(array('state'=>500,'msg'=>_('Location not found')));
    return;
    }
    
    
    
    function is_postal_code($postalcode){
    
    if(preg_match('/^([a-z]{2}-?)?\d{3,10}(-\d{3})?$/i',$postalcode) or preg_match('/^([a-z]\d{4}[a-z]|[A-Z]{2}\d{2}|[A-Z]\d{4}[A-Z]{3}|\d{4}[A-Z]{2})$/i',$postalcode))
        return true;
    if(Address::is_valid_postcode($postalcode,'GBR'))    
        return true;
     return false;   
    }
    
?>