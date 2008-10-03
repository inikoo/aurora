<?
include_once('common.php');
include_once('stock_functions.php');




$target_path = "uploads/";
$target_path = $target_path . $_REQUEST["PHPSESSID"].date('U');
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
  $response='OK';
  $handle_csv = fopen($target_path, "r");
  $col=0;
  $error=true;
  $_code=array();
  $_units=array();
  $_description=array();
  $_price=array();
  $_rrp=array();
  $_scode=array();
  $_supplier_id=array();
  $_supplier_cost=array();
  $_weight=array();
  $_tipounit=array();
  $_export_code=array();
  $_note=array();
    

    
  while(($xcols = fgetcsv($handle_csv,0,"\t"))!== false){
      

      
    
    $cols=split(";",$xcols[0]);

    if(preg_match('/@/i',$cols[0])){
      
      
      $new_product['family_id']=$cols[1];
      $new_product['code']=$cols[2];
      $new_product['description']=$cols[5];

      $new_product['units']=$cols[4];
      $new_product['units_carton']=$cols[3];
      $new_product['units_tipo']=$cols[7];
      $new_product['price']=str_replace(",",".",$cols[6]);
      $new_product['rrp']=$cols[15];
      $new_product['supplier']=$cols[20];
      $new_product['scode']=$cols[10];
      $new_product['sprice']=str_replace(",",".",$cols[23]);

      
      $sql=sprintf("select  id from supplier where code like '%s'",$new_product['supplier']);
      $res = $db->query($sql); 
      while ($row=$res->fetchRow()) {
	$new_product['supplier_id']=$row['id'];

      }
         print_r($new_product);
	 
	 

	if($new_product['description']!=''  and $new_product['code']!=''    and is_numeric($new_product['price'])  and is_numeric($new_product['units_tipo'])  and is_numeric($new_product['units'])  and is_numeric($new_product['family_id'])  ){//xxx
	


	$code=addslashes($new_product['code']);
	$description=addslashes($new_product['description']);
	   
	if(isset($new_product['rrp']) and is_numeric($new_product['rrp']))
	  $rrp=$new_product['rrp'];
	else
	  $rrp='NULL';
	   
	if(isset($new_product['units_carton']) and is_numeric($new_product['units_carton']))
	  $units_carton=$new_product['units_carton'];
	else
	  $units_carton='NULL';
	   
	$ncode=$code;
	$c=split('-',$code);
	if(count($c)==2){
	  if(is_numeric($c[1]))
	    $ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
	  else
	    $ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
	}     
	
	$sql=sprintf("insert into  product (ncode,rrp,units_carton,units,units_tipo,price,description,code,group_id) values ('%s',%s,%s,'%s',%d,'%s','%s','%s',%d)",$ncode,$rrp,$units_carton,$new_product['units'],$new_product['units_tipo'],$new_product['price'],$description,$code,$new_product['family_id']);
	print "$sql";

	$affected=& $db->exec($sql);
	   print "$sql\n";
	if (PEAR::isError($affected)) {
	  if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	    $resp=_('Error: Another product has the same code').'.';
	  else
	    $resp=_('Unknown Error').'.';
	  $state='400';
	  $data=array();
	}else{
	     
	  $product_id = $db->lastInsertID();

	  fix_todotransaction($product_id);

	  // --------Supplier --------------

	  if( isset($new_product['supplier_id'])    and is_numeric($new_product['supplier_id']) and      is_numeric($product_id)    ){
	 

	    $suppiler_id=$new_product['supplier_id'];

	 
	    if(isset($new_product['scode']) and  $new_product['scode']!='')
	      $code="'".$new_product['scode']."'";
	    else
	      $code='NULL';
     
	    if(isset($new_product['sprice']) and  $new_product['sprice']!='')
	      $price="'".$new_product['sprice']."'";
	    else
	      $price='NULL';
	 
	 
	    $p2s_id=addtosupplier($product_id,$suppiler_id);
	 
	    if($p2s_id>0){
	   
	      $sql=sprintf("update  product2supplier set sup_code=%s , price=%s where id=%d",$code,$price,$p2s_id);
	   
	            $affected=& $db->exec($sql);
	    }
	  }



	  // ============================

	  

	  
	  
	  
	}
      }
    }
  }
 }




?>