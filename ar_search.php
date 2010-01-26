<?php
require_once 'common.php';
require_once('class.Email.php');
require_once 'ar_edit_common.php';


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
   search_customer_name($user);
   break;
case('customer'):
case('customers'):
   $data=prepare_values($_REQUEST,array(
			     'q'=>array('type'=>'string')
			     ,'scope'=>array('type'=>'string')
			     ));
    $data['user']=$user;
   search_customer($data);
   break;
default:
    $response=array('state'=>404,'resp'=>"Operation not found $tipo");
    echo json_encode($response);

}


function search_customer($data){
  
  $user=$data['user'];
  $q=$data['q'];
    // $q=_trim($_REQUEST['q']);
    
  if($data['scope']=='store'){
    $stores=$_SESSION['state']['customers']['store'];
    
  }else
    $stores=join(',',$user->stores);
    
    $total_found=0;
    $emails_found=0;
    $emails_results='<table>';
    // Email serach
    if(strlen($q)>3 or preg_match('/@/',$q)){
      $sql=sprintf('select `Customer Key`,`Customer Name`,`Customer Main Plain Email` from `Customer Dimension` where `Customer Store Key` in (%s) and  `Customer Main Plain Email` like "%s%%"  limit 5'
		   ,$stores
		   ,addslashes($q)
		   );
      // print $sql;
      $res=mysql_query($sql);
      while($row=mysql_fetch_array($res)){
	$result=sprintf('<tr><td><a href="customer.php?id=%d">%s</a></td><td class="aright">%s</td></tr>',$row['Customer Key'],$row['Customer Name'],$row['Customer Main Plain Email']);
	$emails_found++;
	$emails_results.=$result;
	$total_found++;
      }
      
    }
    $emails_results.='</table>';


 $names_found=0;
 $names_results='<table>';
 // Email serach
 if(strlen($q)>2){
   $sql=sprintf('select `Customer Key`,`Customer Name` from `Customer Dimension` where `Customer Store Key` in (%s) and  `Customer Name`  REGEXP "[[:<:]]%s"   limit 5'
		   ,$stores
		,addslashes($q)
		);
   // print $sql;
      $res=mysql_query($sql);
      while($row=mysql_fetch_array($res)){
	$result=sprintf('<tr><td class="aright"><a href="customer.php?id=%d">%s</a></td></tr>',$row['Customer Key'],$row['Customer Name']);
	$names_found++;
	$names_results.=$result;
	$total_found++;
      }

    }
  $names_results.='</table>';



 $contacts_found=0;
 $contacts_results='<table>';
 // Email serach
 if(strlen($q)>2){
   $sql=sprintf('select `Customer Key`,`Customer Name`,`Customer Main Contact Name` from `Customer Dimension` where `Customer Store Key` in (%s) and  `Customer Main Contact Name` REGEXP "[[:<:]]%s"   and `Customer Type`="Company" limit 5'
		   ,$stores
		,addslashes($q)
		);
  
      $res=mysql_query($sql);
      while($row=mysql_fetch_array($res)){
	$result=sprintf('<tr><td class="aright"><a href="customer.php?id=%d">%s <b>%s</b></a></td></tr>',$row['Customer Key'],$row['Customer Name'],$row['Customer Main Contact Name']);
	$contacts_found++;
	$contacts_results.=$result;
	$total_found++;
      }

    }
  $contacts_results.='</table>';




 $tax_numbers_found=0;
 $tax_numbers_results='<table>';
 // Email serach
 if(strlen($q)>2){

   if(is_numeric($q)){
  $sql=sprintf('select `Customer Key`,`Customer Name`,`Customer Tax Number` from `Customer Dimension` where `Customer Store Key` in (%s) and  `Customer Tax Number` like "%%%s%%"  limit 5'
		   ,$stores
		,$q
		);
   }else{
   $sql=sprintf('select `Customer Key`,`Customer Name`,`Customer Tax Number` from `Customer Dimension` where `Customer Store Key` in (%s) and  `Customer Tax Number` REGEXP "[[:<:]]%s"  limit 5'
		   ,$stores
		,addslashes($q)
		);
   }
   
      $res=mysql_query($sql);
      while($row=mysql_fetch_array($res)){
	$result=sprintf('<tr><td class="aright"><a href="customer.php?id=%d">%s <b>%s</b></a></td></tr>',$row['Customer Key'],$row['Customer Name'],$row['Customer Tax Number']);
	$tax_numbers_found++;
	$tax_numbers_results.=$result;
	$total_found++;
      }

    }
  $tax_numbers_results.='</table>';


    
     $locations_found=0;
 $locations_results='<table>';
 // Email serach
 if(strlen($q)>1){
   $sql=sprintf('select `Customer Key`,`Customer Name`,`Customer Main Address Postal Code`,`Customer Main Location` from `Customer Dimension` where `Customer Store Key` in (%s) and  `Customer Main Address Postal Code` like "%s%%"  limit 5'
		   ,$stores
		,addslashes($q)
		);
   // print $sql;
      $res=mysql_query($sql);
      while($row=mysql_fetch_array($res)){
	$result=sprintf('<tr><td class="aright"><a href="customer.php?id=%d">%s</a> %s <b>%s</b></td></tr>',$row['Customer Key'],$row['Customer Name'],$row['Customer Main Location'],$row['Customer Main Address Postal Code']);
	$locations_found++;
	$locations_results.=$result;
	$total_found++;
      }

    }
  $locations_results.='</table>';











    
    
    $data=array('results'=>$total_found
		,'emails'=>$emails_found,'emails_results'=>$emails_results
		,'names'=>$names_found,'names_results'=>$names_results
		,'locations'=>$locations_found,'locations_results'=>$locations_results
		,'contacts'=>$contacts_found,'contacts_results'=>$contacts_results
		,'tax_numbers'=>$tax_numbers_found,'tax_numbers_results'=>$tax_numbers_results
		);
    $response=array('state'=>200,'data'=>$data);
    echo json_encode($response);
}







function search_customer_old($user){

    $q=_trim($_REQUEST['q']);
    $stores=join(',',$user->stores);
    
    if(is_numeric($q)){
        if($found_key=search_customer_id($q,$stores)){
         $url='customer.php?id='. $found_key;
        echo json_encode(array('state'=>200,'url'=>$url));
        return;
        }
            
    }
    
    $postal_code_search=false;
    $search_data=array();
    if(preg_match('/\s*(([A-Z]\d{2}[A-Z]{2})|([A-Z]\d{3}[A-Z]{2})|([A-Z]{2}\d{2}[A-Z]{2})|([A-Z]{2}\d{3}[A-Z]{2})|([A-Z]\d[A-Z]\d[A-Z]{2})|([A-Z]{2}\d[A-Z]\d[A-Z]{2})|(GIR0AA))\s*/i',$q,$match)){
        $search_data['Postal Code']=_trim($match[0]);
        $q=preg_replace('/'.$match[0].'/','',$q);
        $postal_code_search=true;
    }
    $tolkens=preg_split('/\s/',$q);
    foreach($tolkens as $key=>$tolken){
        if(Email::is_valid($tolken)){
            $search_data['Customer Email']=$tolken;
        }elseif(!$postal_code_search and is_postal_code($tolken)){
            $tolken_meaning[$key]='postal_code';
            $search_data['Postal Code']=$tolken;
            $postal_code_search=true;
        }elseif($postal_code_search){
            
        }else
        if(isset($search_data['Customer Name']))
            $search_data['Customer Name'].=' '.$tolken;
        else
            $search_data['Customer Name']=$tolken;
        }
    
    
    
   print_r($search_data);
      $_SESSION['search']=array('Type'=>'Customer','Data'=>$search_data);
        echo json_encode(array('state'=>200,'url'=>'customers_lookup.php?res=y'));
        return;
    
    

}

function search_customer_id($id,$valid_stores=false){
    if($valid_stores){
        $stores=" and `Customer Store Key` in ($valid_stores)";
    }else
        $stores='';
    
    $sql=sprintf("select `Customer Key` from `Customer Dimension` where `Customer Key`=%d %s ",$id,$stores);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
        $found=$row['Customer Key'];
    }else
        $found=false;
    return $found;    
}


function search_customer_name($user){
 $target='customer.php';
    $q=$_REQUEST['q'];
    $sql=sprintf("select `Customer Key` from `Customer Dimension` where `Customer Name`=%s ",prepare_mysql($q));
    $result=mysql_query($sql);

    $number_results=mysql_num_rows($result);
    if ($number_results==1) {
        if ($found=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $url=$target.'?id='. $found['id'];
            echo json_encode(array('state'=>200,'url'=>$url));
            return;
        }
    }else{
        
      $_SESSION['search']=array('Type'=>'Customer','Data'=>array('Customer Name'=>$q));
        echo json_encode(array('state'=>200,'url'=>'customer_lookup.php?res=y'));
        return;

    }
mysql_free_result($result);








}

function search_products($q,$tipo,$user){
  global $myconf;
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
        $sql=sprintf("select damlev(UPPER(%s),UPPER(`Product Code`)) as dist1,    damlev(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Product Code`))) as dist2,        `Product Code` as code,`product id` as id from `Product Dimension`  where  `Product Store Key` in (%s)     order by dist1,dist2 limit 1;"
		     ,prepare_mysql($q)
		     ,prepare_mysql($q)
		     ,join(',',$user->stores)
		     );
        $result=mysql_query($sql);
	//	print $sql;     
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
  
     return false;   
    }
    
?>