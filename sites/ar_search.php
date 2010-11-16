<?php
require_once 'common.php';
require_once('class.Email.php');
require_once 'ar_edit_common.php';





//$q=$_REQUEST['q'];
//$store_key=$_REQUEST['store_key'];

 $data=prepare_values($_REQUEST,array(
			     'q'=>array('type'=>'string')
			     ,'store_key'=>array('type'=>'key')
			     ));
    $data['user']=$user;



$the_results=array();

$max_results=10;
 $user=$data['user'];
  $q=$data['q'];
    // $q=_trim($_REQUEST['q']);
    
  if($q==''){
    $response=array('state'=>200,'results'=>0,'data'=>'');
    echo json_encode($response);
    return;
  }



  
  
  $stores=$store_key;
  
  $extra_q='';
  $array_q=preg_split('/\s/',$q);
  if(count($array_q>1)){
  $q=array_shift($array_q);
  $extra_q=join(' ',$array_q);
  
  }
  
  $found_family=false;
  
 $candidates=array();
 $sql=sprintf('select `Product Family Key`,`Product Family Code` from `Product Family Dimension` where `Product Family Store Key` in (%s) and `Product Family Code` like "%s%%" limit 100 ',$stores,addslashes($q));
 //print $sql;
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    if(strtolower($row['Product Family Code'])==strtolower($q)){
      $candidates['F '.$row['Product Family Key']]=210;
     $found_family=$row['Product Family Key'];
  
    }else{

      $len_name=strlen($row['Product Family Code']);
      $len_q=strlen($q);
      $factor=$len_q/$len_name;
      $candidates['F '.$row['Product Family Key']]=200*$factor;
    }   
  }
  //print $extra_q;
 if($found_family){
 if($extra_q){
 
 $sql=sprintf("SELECT `Product ID`, MATCH(`Product Name`) AGAINST (%s) as Relevance FROM `Product Dimension` WHERE   `Product Family Key`=%d  and MATCH
(`Product Name`) AGAINST(%s IN
BOOLEAN MODE) HAVING Relevance > 0.2 ORDER
BY Relevance DESC",prepare_mysql($extra_q),$found_family,prepare_mysql('+'.join(' +',$array_q)));
 
  //$sql=sprintf('select damlevlim256(UPPER(%s),UPPER(`Product Name`),100) as dist , `Product ID`,`Product Name` from `Product Dimension` where `Product Family Key`=%d order by damlevlim256(UPPER(%s),UPPER(`Product Name`),100)  limit 6 ',prepare_mysql($extra_q),$found_family,prepare_mysql($extra_q));
  //print $sql;
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
   
      $candidates['P '.$row['Product ID']]=$row['Relevance'];
    }   
  }
 
 
 
 
 }else{
  
  
 $sql=sprintf('select `Product ID`,`Product Code` from `Product Dimension` where `Product Store Key` in (%s) and `Product Code` like "%s%%" limit 100 ',$stores,addslashes($q));
  //print $sql;
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    if($row['Product Code']==$q)
      $candidates['P '.$row['Product ID']]=110;
    else{

      $len_name=strlen($row['Product Code']);
      $len_q=strlen($q);
      $factor=$len_q/$len_name;
      $candidates['P '.$row['Product ID']]=100*$factor;
    }   
  }
}

  
 arsort($candidates);
 // $candidates=array_reverse($candidates);
 //print_r($candidates); 
 $total_candidates=count($candidates);
  
  if($total_candidates==0){
    $response=array('state'=>200,'results'=>0,'data'=>'');
    echo json_encode($response);
    return;
  }
  

  $counter=0;
  $customer_keys='';

  $results=array();
  $family_keys='';
  $products_keys='';

  foreach($candidates as $key=>$val){
    $_key=preg_split('/ /',$key);
    if($_key[0]=='F'){
      $family_keys.=','.$_key[1];
      $results[$key]='';
    }else{
      $products_keys.=','.$_key[1];
      $results[$key]='';

    }
    
    $counter++;

    if($counter>$max_results)
      break;
  }
  $family_keys=preg_replace('/^,/','',$family_keys);
  $products_keys=preg_replace('/^,/','',$products_keys);

  if($family_keys){
    $sql=sprintf("select `Product Family Key`,`Product Family Name`,`Product Family Code` ,`Page URL` from `Product Family Dimension` left join `Page Dimension` on (`Page Key`=`Product Family Page Key`) where `Product Family Key` in (%s)",$family_keys);
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
      $the_results[]=array('Title'=>$row['Product Family Code']);
      $image='';
      $results['F '.$row['Product Family Key']]=array('image'=>$image,'code'=>$row['Product Family Code'],'description'=>$row['Product Family Name'],'link'=>$row['Page URL'],'key'=>$row['Product Family Key']);
    }
  }

  if($products_keys){
    $sql=sprintf("select `Product ID`,`Product XHTML Short Description`,`Product Code`,`Product Main Image`  from `Product Dimension`   where `Product ID` in (%s)",$products_keys);
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
      $image='';
      if($row['Product Main Image']!='art/nopic.png')
	$image=sprintf('<img src="%s"> ',preg_replace('/small/','thumbnails',$row['Product Main Image']));
           $the_results[]=array('Title'=>'<span>'.$row['Product Code'].'</span><span style="margin-left:10px">'.$row['Product XHTML Short Description'].'</span>');

     $results['P '.$row['Product ID']]=array('image'=>$image,'code'=>$row['Product Code'],'description'=>$row['Product XHTML Short Description'],'link'=>'product.php?pid=','key'=>$row['Product ID']);
    }
  }


  
$response=array('ResultSet'=>array(
                'Result'=>$the_results
                
                ));  

  
 $response=array('state'=>200,'results'=>count($results),'data'=>$results,'link'=>'');
  echo json_encode($response);





    
    
    
    function is_postal_code($postalcode){
    
    if(preg_match('/^([a-z]{2}-?)?\d{3,10}(-\d{3})?$/i',$postalcode) or preg_match('/^([a-z]\d{4}[a-z]|[A-Z]{2}\d{2}|[A-Z]\d{4}[A-Z]{3}|\d{4}[A-Z]{2})$/i',$postalcode))
        return true;
  
     return false;   
    }
    
?>