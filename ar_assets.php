<?
require_once 'common.php';
require_once 'stock_functions.php';
require_once 'classes/Product.php';

//require_once 'common_functions.php';
//require_once 'ar_common.php';


if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }


if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
 case('ep_update_supplier'):
     $data=array(

		 'supplier_id'=>$_REQUEST['supplier_id'],
		 'cost'=>$_REQUEST['cost'],
		 'code'=>$_REQUEST['code'],
		 'user_id'=>$LU->getProperty('auth_user_id'),
		 'tipo'=>$_REQUEST['op_tipo']
		 );
     $product=new product($_SESSION['state']['product']['id']);
     $res=$product->update_supplier($data);
     if($res[0])
       $response= array(
			'state'=>200,
			//			'data'=>$res[1],

			);
     else
       $response= array(
			'state'=>400,
			'msg'=>$res[1]
		      );
     echo json_encode($response);  
     break;
 case('ep_update'):
     $data[]=array(
		 'key'=>$_REQUEST['key'],
		 'data'=>$_REQUEST['value'],
		 'user_id'=>$LU->getProperty('auth_user_id'),
		 );
     $product=new product($_SESSION['state']['product']['id']);
     $res=$product->update($data);
     $response= array('res'=>$res);
     
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
     if($res[0])
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
     if($res[0])
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
     if($res[0])
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
     if($res[0])
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

   if(!isset($_REQUEST['product_id']))
     $product_id=$_SESSION['state']['product']['id'];
   else
     $product_id=$_REQUEST['product_id'];
   $data=array(
	       'product_id'=>$product_id,
	       'p2l_id'=>$_REQUEST['id'],
	       'user_id'=>$LU->getProperty('auth_user_id'),
	       'tipo'=>'desassociate_location',
	       'msg'=>$_REQUEST['msg']
	       );
     $product=new product($product_id);
     $res=$product->update_location($data);
     if($res[0])
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
     

   
 case('pml_new_location'):

   if(isset($_REQUEST['product_id']))
     $product_id=$_REQUEST['product_id'];
   else
     $product_id=$_SESSION['state']['product']['id'];


   if(isset($_REQUEST['location_id'])){
     $sql=sprintf("select name from location where id=%d",$_REQUEST['location_id']);
     $result =& $db->query($sql);
     if($row=$result->fetchRow()){
       $location_name=$row['name'];
     }

   }else
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
   
   if($data['can_pick']){
     $tipo_img='art/icons/basket.png';
     if($data['is_primary'])
       $row=1;
     else
       $row=$res[1]['num_physical'];
   }else{
     $row=$res[1]['num_physical'];
     $tipo_img='art/icons/basket_delete.png';
   }
   
   if($res[0]){

     $response= array(
		      'where'=>$row,
		      'state'=>200,
		      'data'=>$res[1],
		      'name'=>$res[3],
		      'tipo'=>$_location_tipo[$res[4]],
		      'tipo_rank'=>$res[6],
		      'rank_img'=>$tipo_img,
		      'id'=>$res[2],
		      'pl_id'=>$res[7]
		      );
 }else
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
   
   if($res[0])
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
   
   if($res[0])
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

   foreach($_data as $id=>$value){

   $data=array(
	       'qty'=>$value['qty'],
	       'from_id'=>$id,
	       'to_name'=>$to_name,
	       'user_id'=>$LU->getProperty('auth_user_id'),
	       'tipo'=>'move_stock_to'
	       );



   $product=new product($value['product_id']);
   $res=$product->update_location($data);

   if(!$res[0]){

     $ok=false;
     $error_msg.='; '.$res[1];
   }
   }

   if($ok)
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


   foreach($_data as $id=>$value){
     
     $msg=($_REQUEST['msg1']!=''?'; '.$_REQUEST['msg1']:'').($_REQUEST['msg2']!=''?'; '.$_REQUEST['msg2']:'').($value['msg']!=''?'; '.$value['msg']:'');
     $data=array(
	       'qty'=>$value['qty'],
	       'msg'=>$msg,
	       'p2l_id'=>$id,
	       'user_id'=>$LU->getProperty('auth_user_id'),
	       'tipo'=>'change_qty'
	       );



   $product=new product($value['product_id']);
   $res=$product->update_location($data);
   $error_msg='';
   if(!$res[0]){
     $ok=false;
     $error_msg=';'.$res[1];
   }
   
   }

   if($ok)
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
 case('pml_multiple_damaged'):
   $_data=preg_replace('/\\\"/','"',$_REQUEST['data']);
   $_data=json_decode($_data,true);

   $ok=true;


   foreach($_data as $id=>$value){
     
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
   if(!$res[0]){
     $ok=false;
     $error_msg=';'.$res[1];
   }
   
   }

   if($ok)
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

   if(!isset($_REQUEST['query']) or $_REQUEST['query']==''){
     $response= array(
		      'state'=>400,
		      'data'=>array()
		      );
     echo json_encode($response);
     return;
   }
     

   if(isset($_REQUEST['except']) and  isset($_REQUEST['except_id'])  and   is_numeric($_REQUEST['except_id'])){
     
     if($_REQUEST['except']=='location'){

       $sql=sprintf("select product_id,description,product.code,product2location.id as id,0 as qty from product left join product2location on (product.id=product_id) where product.code like   '%s%%'   and (select count(*) from product2location as p2l  where location_id=%s and p2l.product_id=product.id)=0   order by ncode ",addslashes($_REQUEST['query']),$_REQUEST['except_id']);
       $_data=array();
       $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
       while($data=$res->fetchRow()) {
	 $_data[]= array(
			 'scode'=>$data['code']
			 ,'code'=>sprintf('<a href="product.php?id=%d">%s</a>',$data['product_id'],$data['code'])
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
//    $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
//    while($row=$res->fetchRow()) {
//      $data[]=array('code'=>$row['code']);
//    }
   

//    $response= array(
// 		    'state'=>200,
// 		    'data'=>$data
// 		    );
//    echo json_encode($response);


   break;


 case('locations_name'):

   if(!isset($_REQUEST['query']) or $_REQUEST['query']==''){
     $response= array(
		      'state'=>400,
		      'data'=>array()
		      );
     echo json_encode($response);
     return;
   }
     
   
   if(isset($_REQUEST['all']) and $_REQUEST['all']==1)
     $sql=sprintf("select name from location where name like '%s%%' ",$_REQUEST['query']);
   elseif(isset($_REQUEST['except_location'])){
     $sql=sprintf("select * from location where name like '%s%%' and id!=%d  ",$_REQUEST['query'],$_REQUEST['except_location']);
   }else{
     
     if(!isset($_REQUEST['product_id']))
       $product_id=$_SESSION['state']['product']['id'];
     else
       $product_id=$_REQUEST['product_id'];
     $sql=sprintf("select * from location where name like '%s%%' and (select count(*) from product2location where location_id=location.id and product_id=%d)=0   ",$_REQUEST['query'],$product_id);
   }
   //   print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   while($row=$res->fetchRow()) {
     $data[]=array('name'=>$row['name']);
   }
   

   $response= array(
		    'state'=>200,
		    'data'=>$data
		    );
   echo json_encode($response);


   break;

 case('update_product'):
   if(!isset($_REQUEST['product_id'])){
     $response=array('state'=>400,'resp'=>_('Error'));
     echo json_encode($response);
     break;
   }
     
   include_once('classes/product.php');
   $product_id=$_REQUEST['product_id'];

   $values=array();
   foreach($_REQUEST as $key=>$value){
     if(preg_match('/^v_.*/i',$key)){
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
   if($description==''){
       $response= array(
			'state'=>400,
			'desc'=>_('Nothing to add')
		    );
       echo json_encode($response);
       break;
   }
   $sql=sprintf("update product set description_med='%s' where id=%d",$description,$product_id);

   $db->exec($sql);
   //   print $_REQUEST['editor'];
   $response= array(
		    'state'=>200
		    );
   echo json_encode($response);
   break;
 case('changepic'):
   $new_id=$_REQUEST['new_id'];

   
   $sql=sprintf("select filename,format,id,product_id,caption from image where id=%d",$new_id);
   $res = $db->query($sql);
   if($row=$res->fetchRow()) {
     $caption=$row['caption'];
     $product_id=$row['product_id'];
     $new_src='images/med/'.$row['filename'].'_med.'.$row['format'];
     $sql=sprintf("update image set principal=0 where product_id=%d",$product_id);
     $db->exec($sql);
     $sql=sprintf("update image set principal=1 where id=%d",$new_id);
     //     print $sql;
     $db->exec($sql);
     
     $sql=sprintf("select filename,id,format from image where product_id=%d and principal=0 limit 5",$product_id);
     $res2 = $db->query($sql);
     $other_img_src=array('','','','','');
     $other_img_id=array(0,0,0,0,0);
     $num_others=0;
     while($row2=$res2->fetchRow()) {
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
   $id=$_REQUEST['product_id'];
   $sql=sprintf("select code from product where id=%d",$id);
   $res = $db->query($sql);
   if($row=$res->fetchRow()) {
     $code=strtolower($row['code']);
     $target_path = "uploads/";
     $target_path = $target_path . $_REQUEST["PHPSESSID"].date('U');
     if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
       $im = @imagecreatefromjpeg($target_path);
       if ($im) { 
	 $w = imagesx($im);
	 $h = imagesy($im);
	 
	 if($h > 0) 
	   { $r = $w/$h;
	     
	     $s=filesize($target_path);
	     $c=md5_file($target_path);
	     


	     
	     $sql=sprintf("select checksum  from image where  product_id=%d",$id);
	     $res2 = $db->query($sql);
	     while($row2=$res2->fetchRow()) {
	       if($c==$row2['checksum']){
		 $response=array('state'=>400,'resp'=>_('Image already uploaded'));
		 echo json_encode($response);
		 break 2;
	       }
	       
	     }



	     $sql=sprintf("select count(*) as num from image where  product_id=%d",$id);
	     $res2 = $db->query($sql);
	     if($row2=$res2->fetchRow()) {
	       $images=$row2['num'];
	     }
	     
	     
	     $med_maxh=130;
	     $med_maxw=190;
	     $tb_maxh=21;
	     $tb_maxw=30;
	     

	     //   print "$images $w $h $s $c";
	     imagejpeg($im,'images/original/'.$code.'_'.$images.'_orig.jpg');

	     if($r>1.4615){
	       $med_w=$med_maxw;
	       $med_h=$med_w/$r;
	       $tb_w=$tb_maxw;
	       $tb_h=$tb_w/$r;

	     }else{
	       
	       $med_h=$med_maxh;
	       $med_w=$med_h*$r;
	       $tb_h=$tb_maxh;
	       $tb_w=$tb_h*$r;
	     }

	     $im_med = imagecreatetruecolor($med_w, $med_h);
	     imagecopyresampled($im_med, $im, 0, 0, 0, 0, $med_w, $med_h, $w, $h);
	     imagejpeg($im_med,'images/med/'.$code.'_'.$images.'_med.jpg');
	     $im_tb = imagecreatetruecolor($tb_w, $tb_h);
	     imagecopyresampled($im_tb, $im, 0, 0, 0, 0, $tb_w, $tb_h, $w, $h);
	     imagejpeg($im_tb,'images/tb/'.$code.'_'.$images.'_tb.jpg');
	     

	     $sql=sprintf("update image set principal=0 where product_id=%d",$id);
	     $db->exec($sql);

	     $caption=$_REQUEST['caption'];
	     $sql=sprintf("insert into image (filename,product_id,width,height,size,checksum,caption,principal) values ('%s',%d,%d,%d,%d,'%s','%s',1)",$code.'_'.$images,$id,$w,$h,$s,$c,addslashes($caption));
	     $db->exec($sql);
	     $new_id = $db->lastInsertID();
	     // make the new pric the pricipal


	     

	     
	     $sql=sprintf("select filename,id,format from image where product_id=%d and principal=0 limit 5",$id);

	     $res2 = $db->query($sql);
	     $other_img_src=array('','','','','');
	     $other_img_id=array(0,0,0,0,0);
	     $num_others=0;
	     while($row2=$res2->fetchRow()) {
	       $other_img_src[$num_others]='images/tb/'.$row2['filename'].'_tb.'.$row2['format'];
	       $other_img_id[$num_others]=$row2['id'];
	       $num_others++;
	     }
	      $num_others++;
	     
	     $response=array(
			   'state'=>200,
			   'new_src'=>'images/med/'.$code.'_'.$images.'_med.jpg',
			   'new_id'=>$new_id,
			   'other_img'=>$other_img_src,
			   'other_img_id'=>$other_img_id,
			   'others'=>$num_others,
			   'caption'=>$caption
			   
		     );
	     echo json_encode($response);
	     break;








	     
	   }
	 
       }



       // save the original (expeced to be  a big file)

       
       //       print "$w $h";
       
       
     }else{
       $response=array('state'=>400,'resp'=>_('Error'));
       echo json_encode($response);
       break;
     }
     
   }
 $response=array('state'=>400,'resp'=>_('Error'));
       echo json_encode($response);
       break;
 
   break;

 case('search'):
   $q=$_REQUEST['q'];
   $sql=sprintf("select id from product where code='%s' ",addslashes($q));
   $result =& $db->query($sql);
   if($found=$result->fetchRow()){
     $url='product.php?id='. $found['id'];
     echo json_encode(array('state'=>200,'url'=>$url));
     break;
   }
   $sql=sprintf("select id from product_group where name='%s' ",addslashes($q));
   $result =& $db->query($sql);
   if($found=$result->fetchRow()){
     $url='family.php?id='. $found['id'];
     echo json_encode(array('state'=>200,'url'=>$url));
     break;
   }
   
   // try to get similar results 
   //   if($myconf['product_code_separator']!=''){
   if(  ($myconf['product_code_separator']!='' and   preg_match('/'.$myconf['product_code_separator'].'/',$q)) or  $myconf['product_code_separator']==''  ){
     $sql=sprintf("select levenshtein(UPPER(%s),UPPER(code)) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(code))) as dist2,        code,id from product  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
     $result =& $db->query($sql);
     if($found=$result->fetchRow()){
       if($found['dist1']<3){
	 echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="product.php?id='.$found['id'].'">'.$found['code'].'</a>'));
	 break;
       }
     }
    
     
   }else{
     // look on the family list
     $sql=sprintf("select levenshtein(UPPER(%s),UPPER(name)) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(name))) as dist2, name ,id from product_group  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
     $result =& $db->query($sql);
     if($found=$result->fetchRow()){
       if($found['dist1']<3){
	 echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="family.php?id='.$found['id'].'">'.$found['name'].'</a> '._('family') ));
	 break;
       }
     }

   }
 echo json_encode(array('state'=>500,'msg'=>_('Product not found')));
     break;

   

   break;
 case('changetableview'):
   if(isset($_REQUEST['value'])){
     $value=$_REQUEST['value'];
     if(is_numeric($value) and $value>=0 and $value<3)
       $_SESSION['views']['assets_tables']=$value;


   }
   break;
 case('changeproductplot'):
   if(isset($_REQUEST['value'])){
     $value=$_REQUEST['value'];
     $_SESSION['views']['product_plot']="$value";

   }
   break;
 case('changeproductblock'):
   if(isset($_REQUEST['value']) and isset($_REQUEST['block'])){
     $value=$_REQUEST['value'];
     $block=$_REQUEST['block'];
	  

     if(is_numeric($value) and ($value==0 or $value==1)    and is_numeric($block) and $value>=0 and $value<6      )
       $_SESSION['views']['product_blocks'][$block]=$value;
   }
   break;
   



 case('codefromsup'):
   $q=addslashes($_REQUEST['query']);
   $sql="select product.id,code,description from product left join product2supplier on (product_id=product.id) where product2supplier.supplier_id=2 and code like '$q%' ";
   //     print $sql;
   $res = $db->query($sql);
   $data=array();
   while($row=$res->fetchRow()) {
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
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
    $start_from=$_SESSION['tables']['pindex_list'][3];
  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$_SESSION['tables']['pindex_list'][2];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$_SESSION['tables']['pindex_list'][0];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$_SESSION['tables']['pindex_list'][1];
  
       
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
  


   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$_SESSION['tables']['pindex_list'][4];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$_SESSION['tables']['pindex_list'][5];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$_SESSION['tables']['pindex_list'][6];
  





  $_SESSION['tables']['pindex_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);

  $wheref='';
  if($f_field=='p.code' or $f_field=='g.name'  or $f_field=='g.code'    and $f_value!='')
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
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total=$row['total'];
  }
    if($where==''){
      $filtered=0;
    }else{
      
      $sql="select count(*) as total from product  as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id)  $where ";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$filtered=$row['total']-$total;
      }
      
    }
    


   $sql="select p.awoutq,p.awtsq,p.price,p.units,ifnull(p.stock,-10000) as stock,p.condicion as condicion, p.code as code, p.id as id,p.description as description , group_id,department_id,g.name as fam, d.code as department 
from product as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id)  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
   //     print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
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

   if($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
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
 case('index'):

   $conf=$_SESSION['state']['departments']['table'];
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
  
  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;



   $filter_msg='';



  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
  
  
  $_SESSION['state']['departments']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from);
  
  $where='';
  $filtered=0;
  
  $_order=$order;
  $_dir=$order_direction;
  if($order=='per_tsall')
    $order='tsall';
  if($order=='per_tsm')
    $order='tsm';
  
  $total_sales=0;
   $total_m=0;
   $sql="select awtsq,tsall,tsy,tsq,tsm,id,code,name,families,products,active,outofstock,stockerror,stock_value from product_department $where   order by $order $order_direction limit $start_from,$number_results    ";
   //   print "$sql";   
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  $adata=array();
  while($row=$res->fetchRow()) {
    $total_sales+=$row['tsall'];
    $total_m+=$row['tsm'];
    $adata[]=array(
		   'id'=>$row['id'],

		   'code'=>$row['code'],
		   'name'=>$row['code'],
		   'families'=>number($row['families']),
		   'products'=>number($row['products']),
		   'active'=>number($row['active']),
		   'outofstock'=>number($row['outofstock']),
		   'stockerror'=>number($row['stockerror']),
		   'stock_value'=>money($row['stock_value']),
		   'tsy'=>money($row['tsy']),
		   'tsq'=>money($row['tsq']),
		   'tsm'=>money($row['tsm']),
		   'tsall'=>money($row['tsall']),
		   'per_tsm'=>$row['tsm'],
		   'per_tsall'=>$row['tsall'],
		   'awtsq'=>money($row['awtsq'])
		   );
  }
  foreach($adata as $key=>$value){
    $adata[$key]['per_tsall']=percentage($adata[$key]['per_tsall'],$total_sales,2);
    $adata[$key]['per_tsm']=percentage($adata[$key]['per_tsm'],$total_sales,2);

  }



  $total=$res->numRows();
  if($total<$number_results)
    $rtext=$total.' '.ngettext('department','departments',$total);
  else
    $rtext='';
  
  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$res->numRows(),
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
			)
		  );
  echo json_encode($response);
  break;
  
  
 case('department'):
   
   $conf=$_SESSION['state']['department']['table'];
   if(isset( $_REQUEST['id']) and is_numeric($_REQUEST['id'])){
     $id=$_REQUEST['id'];
     $_SESSION['state']['department']['id']=$id;
   }   else
     $id=$_SESSION['state']['department']['id'];
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];
   
   if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];

   
   if(isset( $_REQUEST['tableid']))
     $tableid=$_REQUEST['tableid'];
   else
    $tableid=0;
   

  // print_r($_SESSION['tables']['families_list']);

  //  print_r($_SESSION['tables']['families_list']);

 $filter_msg='';
  $wheref='';
  if($f_field=='name' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


  
    $_SESSION['state']['department']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $where =$where.sprintf(' and department_id=%d',$id);

   $sql="select count(*) as total from product_group  $where $wheref";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select count(*) as total from product_group  $where ";

     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }




   

  $_order=$order;
  $_dir=$order_direction;


  $sql="select id,name,description,stock_value,stockerror,outofstock,tsall,tsy,tsq,tsm,awtsq,active from product_group    $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  
  $adata=array();
  
  while($data=$res->fetchRow()) {
    $adata[]=array(
		   'id'=>$data['id']
		   ,'name'=>$data['name']
		   ,'description'=>$data['description']
		   ,'stock_value'=>money($data['stock_value'])
		   ,'stockerror'=>number($data['stockerror'])
		   ,'outofstock'=>number($data['outofstock'])
		   ,'tsall'=>money($data['tsall'])
		   ,'tsy'=>money($data['tsy'])
		   ,'tsq'=>money($data['tsq'])
		   ,'tsm'=>money($data['tsm'])
		   ,'awtsq'=>money($data['awtsq'])
		   ,'active'=>number($data['active'])

);
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 	'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,

			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			'records_returned'=>$start_from+$res->numRows(),
			'records_perpage'=>$number_results,

			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;

 case('locations'):
   
   $conf=$_SESSION['state']['warehouse']['locations'];
   
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];
   
   if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];

   
   if(isset( $_REQUEST['tableid']))
     $tableid=$_REQUEST['tableid'];
   else
    $tableid=0;
   

   
   
 
   $wheref='';
   if($f_field=='location.name' and $f_value!='')
     $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
   

  
   $_SESSION['state']['warehouse']['locations']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   
   
   $sql="select count(*) as total from location  left join location_tipo on (tipo_id=location_tipo.id) left join wharehouse_area on (area_id=wharehouse_area.id)  $where $wheref";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0;
       $total_records=$total;
   }else{
     $sql="select count(*) as total from location  left join location_tipo on (tipo_id=location_tipo.id) left join wharehouse_area on (area_id=wharehouse_area.id)  $where ";

     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }

   




   if($total==0 and $filtered>0){
     switch($f_field){
     case('location.name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any location name starting with")." <b>$f_value</b> ";
       break;
     }
   }elseif($filtered>0){
     switch($f_field){
     case('location.name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only locations starting with')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     }
   }else
      $filter_msg='';
   
   
   $rtext=$total_records." ".ngettext('location','locations',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $_order=$order;
  $_dir=$order_direction;


  $sql="select (select count(*) from product2location where location_id=location.id ) as products ,deep,length,height,location_tipo.max_weight as max_weight,location.id,location.tipo,location.name,wharehouse_area.name as area  from location  left join location_tipo on (tipo_id=location_tipo.id) left join wharehouse_area on (area_id=wharehouse_area.id)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
  //  print "$sql";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  
  $adata=array();

  while($data=$res->fetchRow()) {
  if(is_numeric($data['deep']) and is_numeric($data['length']) and is_numeric($data['height']))
    $max_vol=$data['deep']*$data['length']*$data['height'];
  else
    $max_vol='';
  if($data['name']=='_UNK'){
    $name=_('Unknown');
    $tipo='';
  }elseif($data['name']=='_WHL'){
    $name=_('White Star');
    $tipo=_('Balancing');
  }else{
    $name=sprintf('<a href="location.php?id=%d" >%s</a>',$data['id'],$data['name']);
    $tipo=mb_ucwords($data['tipo']);
  }
  $adata[]=array(
		 'id'=>$data['id']
		 ,'tipo'=>$tipo
		 ,'name'=>$name
		 ,'area'=>$data['area']
		 ,'products'=>$data['products']
		 ,'max_weight'=>$data['max_weight']
		 ,'max_volumen'=>$max_vol
		 );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 	'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'rtext'=>$rtext,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			'records_returned'=>$start_from+$res->numRows(),
			'records_perpage'=>$number_results,

			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;


  case('outofstock'):
     $conf=$_SESSION['state']['report_outofstock']['table'];


     
      if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
    $start_from=$conf['sf'];
   if(!is_numeric($start_from))
     $start_from=0;

  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$conf['nr'];
   if(!is_numeric($number_results))
     $number_results=25;



  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


 if(isset( $_REQUEST['from']))
   $from=$_REQUEST['from'];
   else
     $from=$_SESSION['state']['report_outofstock']['from'];
  if(isset( $_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=$_SESSION['state']['report_outofstock']['to'];
  
  
     if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
 if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
 // print "xx $from $to";
 $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
 // print_r($date_interval);
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['report_outofstock']['from'],$_SESSION['state']['report_outofstock']['to']);
   }else{
     $_SESSION['state']['report_outofstock']['from']=$date_interval['from'];
     $_SESSION['state']['report_outofstock']['to']=$date_interval['to'];
   }

    $_SESSION['state']['report_outofstock']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

  $where.=$date_interval['mysql'];
     $filter_msg='';
     
     $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
     
     if(!is_numeric($start_from))
       $start_from=0;
     if(!is_numeric($number_results))
       $number_results=25;


 $_order=$order;
 $_dir=$order_direction;
  $filter_msg='';
  $wheref='';
  if($f_field=='code' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


  
   



   $sql="select product_id from outofstock left join orden on (orden.id=order_id)  $where $wheref  and orden.tipo=2  group by product_id";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow())
     $total=$res->numRows();
   else
     $total=0;
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select product_id  as total   from outofstock left join orden on (orden.id=order_id)  $where ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow())
       $_total=$res->numRows();
     else
       $_total=0;
     $filtered=$_total-$total;
   }




   

  $norder=($order=='code'?'ncode':$order);
  
  $sql="select  count(distinct picker_id) as pickers, group_concat(distinct staff.alias) as pickers_name,count(distinct orden.id) as orders,product.id,product.code,product.description,product.stock from outofstock left join orden on (orden.id=order_id) left join product on (product.id=product_id) left join pick on (pick.order_id=orden.id) left join staff on (picker_id=staff.id)   $where $wheref and orden.tipo=2 group by product_id  order by $norder $order_direction limit $start_from,$number_results     ";
  //    print "$sql";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  
  $adata=array();
  
  while($data=$res->fetchRow()) {
    $adata[]=array(
		   'id'=>$data['id']
		   ,'pickers'=>$data['pickers'].'('.$data['pickers_name'].')'
		   ,'orders'=>$data['orders']
		   ,'code'=>$data['code']
		   ,'description'=>$data['description']
		   ,'stock'=>number($data['stock'])

		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;



 case('outofstock_all'):
     $conf=$_SESSION['state']['report_outofstock']['table'];

     if(isset( $_REQUEST['view']))
       $view=$_REQUEST['view'];
     else
       $view=$_SESSION['state']['family']['view'];
     
      if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
    $start_from=$conf['sf'];
   if(!is_numeric($start_from))
     $start_from=0;

  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$conf['nr'];
   if(!is_numeric($number_results))
     $number_results=25;

  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


 if(isset( $_REQUEST['from']))
   $from=$_REQUEST['from'];
   else
     $from=$_SESSION['state']['report_outofstock']['from'];
  if(isset( $_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=$_SESSION['state']['report_outofstock']['to'];
  
  
     if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
 if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
 // print "xx $from $to";
 $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
 // print_r($date_interval);
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['report_outofstock']['from'],$_SESSION['state']['report_outofstock']['to']);
   }else{
     $_SESSION['state']['report_outofstock']['from']=$date_interval['from'];
     $_SESSION['state']['report_outofstock']['to']=$date_interval['to'];
   }

    $_SESSION['state']['report_outofstock']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

  $where.=$date_interval['mysql'];
     $filter_msg='';
     
     $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
     
     if(!is_numeric($start_from))
       $start_from=0;
     if(!is_numeric($number_results))
       $number_results=25;


 $_order=$order;
 $_dir=$order_direction;
  $filter_msg='';
  $wheref='';
  if($f_field=='code' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


  
   



   $sql="select product_id from outofstock left join orden on (orden.id=order_id)  $where $wheref  and orden.tipo=2  ";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow())
     $total=$res->numRows();
   else
     $total=0;
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select product_id  as total   from outofstock left join orden on (orden.id=order_id)  $where ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow())
       $_total=$res->numRows();
     else
       $_total=0;
     $filtered=$_total-$total;
   }





   

  $norder=($order=='code'?'ncode':$order);
  $norder=($order=='orders'?'public_id':$order);
  $norder='code, '.$norder;
  $sql="select product_id,outofstock.id,orden.public_id,staff.alias as picker,product.code as code,product.stock,date_index from outofstock left join orden on (orden.id=order_id) left join product on (product.id=product_id) left join pick on (pick.order_id=orden.id) left join staff on (picker_id=staff.id)   $where $wheref and orden.tipo=2   order by $norder $order_direction limit $start_from,$number_results     ";
   print "$sql";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  
  $adata=array();
  
  while($data=$res->fetchRow()) {
    $adata[]=array(
		   'id'=>$data['id']
		   ,'pickers'=>$data['picker']
		   ,'orders'=>$data['public_id']
		   ,'code'=>$data['code']
		   ,'stock'=> number(stock_date($data['product_id'],$data['date_index'])).'  <b>'.number($data['stock']).'</b>'
		   ,'date'=>$data['date_index']
		   
		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;




 case('family'):
     $conf=$_SESSION['state']['family']['table'];
     
     if(isset( $_REQUEST['id']) and is_numeric($_REQUEST['id'])){
       $id=$_REQUEST['id'];
       $_SESSION['state']['family']['id']=$id;
     }   else
       $id=$_SESSION['state']['family']['id'];
     
     if(isset( $_REQUEST['view']))
       $view=$_REQUEST['view'];
     else
       $view=$_SESSION['state']['family']['view'];
     
      if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
    $start_from=$conf['sf'];
   if(!is_numeric($start_from))
     $start_from=0;

  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$conf['nr'];
   if(!is_numeric($number_results))
     $number_results=25;

  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

  
  
     if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];


 if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
         $_SESSION['state']['family']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

     
     $filter_msg='';
     
     $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
     
     if(!is_numeric($start_from))
       $start_from=0;
     if(!is_numeric($number_results))
       $number_results=25;


 $_order=$order;
 $_dir=$order_direction;
  $filter_msg='';
  $wheref='';
  if($f_field=='code' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";



   
   $where =$where.sprintf(' and group_id=%d',$id);


   $sql="select count(*) as total from product  $where $wheref";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select count(*) as total from product  $where ";

     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }




   

  $norder=($order=='code'?'ncode':$order);
  
  $sql="select id,code,description,product.price as price,product.units as units,product.units_tipo as units_tipo,ncode,stock,available,stock_value,tsall,tsy,tsq,tsm,awtsq from product    $where $wheref  order by $norder $order_direction limit $start_from,$number_results    ";

  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  
  $adata=array();
  
  while($data=$res->fetchRow()) {
    $adata[]=array(
		   'id'=>$data['id']
		   ,'code'=>$data['code']
		   ,'description'=>$data['description']
		   ,'units'=>number($data['units'])
		   ,'price'=>money($data['price'])
		   ,'units_tipo'=>$_units_tipo_abr[($data['units_tipo'])]
		   ,'stock'=>number($data['stock'])
		   ,'available'=>number($data['available'])
		   ,'stock_value'=>money($data['stock_value'])
		   ,'tsall'=>money($data['tsall'])
		   ,'tsy'=>money($data['tsy'])
		   ,'tsq'=>money($data['tsq'])
		   ,'tsm'=>money($data['tsm'])
		   ,'awtsq'=>money($data['awtsq'])

		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
 case('products'):
     $conf=$_SESSION['state']['products']['table'];
     
     
     if(isset( $_REQUEST['view']))
       $view=$_REQUEST['view'];
     else
       $view=$_SESSION['state']['family']['view'];
     
      if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
    $start_from=$conf['sf'];
   if(!is_numeric($start_from))
     $start_from=0;

  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$conf['nr'];
   if(!is_numeric($number_results))
     $number_results=25;

  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

  
  
     if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];


 if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
         $_SESSION['state']['products']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

     
     $filter_msg='';
     
     $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
     
     if(!is_numeric($start_from))
       $start_from=0;
     if(!is_numeric($number_results))
       $number_results=25;


 $_order=$order;
 $_dir=$order_direction;
  $filter_msg='';
  $wheref='';
  if($f_field=='code' and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";



   



   $sql="select count(*) as total from product  $where $wheref";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select count(*) as total from product  $where ";

     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }




   

  $norder=($order=='code'?'ncode':$order);
  
  $sql="select days_to_ns,id,code,description,product.price as price,product.units as units,product.units_tipo as units_tipo,ncode,stock,available,stock_value,tsall,tsy,tsq,tsm,tsw,awtsall,awtsy,awtsm,tsoall,tsoy,tsoq,tsom,tsow,awtsoall,awtsoy,awtsom from product    $where $wheref  order by $norder $order_direction limit $start_from,$number_results    ";

  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  
  $adata=array();
  
  while($data=$res->fetchRow()) {
    $adata[]=array(
		   'id'=>$data['id']
		   ,'code'=>$data['code']
		   ,'description'=>$data['description']
		   ,'units'=>number($data['units'])
		   ,'price'=>money($data['price'])
		   ,'units_tipo'=>$_units_tipo_abr[($data['units_tipo'])]
		   ,'stock'=>number($data['stock'])
		   ,'days_to_ns'=>interval($data['days_to_ns'])
		   ,'available'=>number($data['available'])
		   ,'stock_value'=>money($data['stock_value'])
		   ,'tsall'=>money($data['tsall'])
		   ,'tsy'=>money($data['tsy'])
		   ,'tsq'=>money($data['tsq'])
		   ,'tsm'=>money($data['tsm'])
		   ,'tsw'=>money($data['tsw'])
		   ,'awtsall'=>money($data['awtsall'])
		   ,'awtsy'=>money($data['awtsy'])
		   ,'awtsm'=>money($data['awtsm'])
		   ,'tsoall'=>number($data['tsoall'])
		   ,'tsoy'=>number($data['tsoy'])
		   ,'tsoq'=>number($data['tsoq'])
		   ,'tsom'=>number($data['tsom'])
		   ,'tsow'=>number($data['tsow'])
		   ,'awtsoall'=>number($data['awtsoall'])
		   ,'awtsoy'=>number($data['awtsoy'])
		   ,'awtsom'=>number($data['awtsom'])
		   

		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;


 case('update_department_name'):
   
   if(isset($_REQUEST['id'])  and  isset($_REQUEST['value'])  and is_numeric($_REQUEST['id']) and $_REQUEST['value']!=''){
     $name=addslashes($_REQUEST['value']);
     $id=$_REQUEST['id'];
     $sql=sprintf("update product_department set name='%s' where id=%d ",$name,$id);
     $affected=& $db->exec($sql);
     if (PEAR::isError($affected)) {
       if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	 $resp=_('Error: Another department has the same name').'.';
       else
	 $resp=_('Unknown Error').'.';
       $state='400';
     }else{
       $resp= $affected;
       $state='200';
     }
     $response=array('state'=>$state,'resp'=>_($resp));
   }

   else
      $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;

 case('update_family_name'):
   
   if(isset($_REQUEST['id'])  and  isset($_REQUEST['value'])  and is_numeric($_REQUEST['id']) and $_REQUEST['value']!=''){
     $name=addslashes($_REQUEST['value']);
     $id=$_REQUEST['id'];
     $sql=sprintf("update product_group set name='%s' where id=%d ",$name,$id);
     $affected=& $db->exec($sql);
     if (PEAR::isError($affected)) {
       if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	 $resp=_('Error: Another family has the same name').'.';
       else
	 $resp=_('Unknown Error').'.';
       $state='400';
     }else{
       $resp= $affected;
       $state='200';
     }
     $response=array('state'=>$state,'resp'=>_($resp));
   }

   else
      $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;
 case('update_family_description'):
   
   if(isset($_REQUEST['id'])  and  isset($_REQUEST['value'])  and is_numeric($_REQUEST['id']) and $_REQUEST['value']!=''){
     $name=addslashes($_REQUEST['value']);
     $id=$_REQUEST['id'];
     $sql=sprintf("update product_group set description='%s' where id=%d ",$name,$id);
     $affected=& $db->exec($sql);
     if (PEAR::isError($affected)) {
       if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	 $resp=_('Error: Another family has the same name and description').'.';
       else
	 $resp=_('Unknown Error').'.';
       $state='400';
     }else{
       $resp= $affected;
       $state='200';
     }
     $response=array('state'=>$state,'resp'=>_($resp));
   }

   else
      $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;




 case('new_department'):
   
   if(isset($_REQUEST['name'])  and  isset($_REQUEST['code'])    and $_REQUEST['code']!='' and $_REQUEST['name']!=''){
     $name=addslashes($_REQUEST['name']);
     $code=addslashes($_REQUEST['code']);

     $sql=sprintf("insert into  product_department (code,name) values ('%s','%s')",$code,$name);
     $affected=& $db->exec($sql);

     if (PEAR::isError($affected)) {
       if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	 $resp=_('Error: Another department has the same code/name').'.';
       else
	 $resp=_('Unknown Error').'.';
       $state='400';
       $data=array();
     }else{
       $department_id = $db->lastInsertID();
       $resp='ok';
       $data= array(
	  'id'=>$department_id,
	  'code'=>$code,
	  'name'=>$name,
	  'families'=>0,
	  'products'=>0,
	  'active'=>0,
	  'outofstock'=>0,
	  'stockerror'=>0	    
		    );
       $state='200';
     }
     $response=array('state'=>$state,'resp'=>_($resp),'data'=>$data);
   }

   else
      $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;

   
 case('new_family'):
   
   if(isset($_REQUEST['name'])  and  isset($_REQUEST['description'])  and  isset($_REQUEST['id'])    and $_REQUEST['description']!='' and $_REQUEST['name']!=''    and is_numeric($_REQUEST['id'])  ){
     $name=addslashes($_REQUEST['name']);
     $description=addslashes($_REQUEST['description']);

     $sql=sprintf("insert into  product_group (description,name,department_id) values ('%s','%s',%d)",$description,$name,$_REQUEST['id']);
     $affected=& $db->exec($sql);

     if (PEAR::isError($affected)) {
       if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	 $resp=_('Error: Another department has the same name-description').'.';
       else
	 $resp=_('Unknown Error').'.';
       $state='400';
       $data=array();
     }else{
       $family_id = $db->lastInsertID();
       $resp='ok';
       $data= array(
	  'id'=>$family_id,
	  'description'=>$description,
	  'name'=>$name,
	  'products'=>0,
	  'active'=>0,
	  'outofstock'=>0,
	  'stockerror'=>0	    
		    );
       $state='200';
     }
     $response=array('state'=>$state,'resp'=>_($resp),'data'=>$data);
   }

   else
      $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;

 case('add_tosupplier'):
   
   if( isset($_REQUEST['supplier_id'])    and is_numeric($_REQUEST['supplier_id']) and      isset($_REQUEST['product_id'])    and is_numeric($_REQUEST['product_id'])    ){

     $product_id=$_REQUEST['product_id'];
     $suppiler_id=$_REQUEST['supplier_id'];


     if(isset($_REQUEST['code']) and  $_REQUEST['code']!='')
       $code="'".$_REQUEST['code']."'";
     else
       $code='NULL';
     
     if(isset($_REQUEST['price']) and  $_REQUEST['price']!='')
       $price="'".$_REQUEST['price']."'";
     else
       $price='NULL';

     
     $p2s_id=addtosupplier($product_id,$suppiler_id);

     if($p2s_id>0){
       
       $sql=sprintf("update  product2supplier set sup_code=%s , price=%s where id=%d",$code,$price,$p2s_id);
       //       print "$sql";
       $affected=& $db->exec($sql);
     }
     $state='200';
     $resp='OK';
     
     $response=array('state'=>$state,'resp'=>_($resp),'product_id'=>$product_id);
   }

   else
      $response=array('state'=>400,'resp'=>_('Error'));
   echo json_encode($response);
   break;

 case('set_stock'):
   
   if( isset($_REQUEST['product_id'])    and is_numeric($_REQUEST['product_id']) and      isset($_REQUEST['qty'])    and is_numeric($_REQUEST['qty'])   and      isset($_REQUEST['author'])  ){

     

     $product_id=$_REQUEST['product_id'];
     $qty=$_REQUEST['qty'];
     
     $date=split('-',$_REQUEST['date']);
     if(count($date)==3 and is_numeric($date[0]) and is_numeric($date[0]) and is_numeric($date[0]) ){
      $f_date=sprintf("%02d-%02d-%d",$date[0],$date[1],$date[2]);
      $date=join ('-',array_reverse($date));
     }else{
       $response=array('state'=>400,'resp'=>_('Error: in date format, should be (DD-MM-YYYY)'));
       echo json_encode($response);
       break;
     }
     

     $time=split(':',$_REQUEST['time']);

     if( count($time)!=2 or  !is_numeric($time[0]) or !is_numeric($time[1]) or $time[0]>23 or  $time[0]<0  or $time[1]>59 or  $time[1]<0    ){
       $response=array('state'=>400,'resp'=>_('Error: in time format, should be (HH:MM)'));
       echo json_encode($response);
       break;
     }
     $time=join (':',$time);
     $datetime=$date.' '.$time.':00';


     $author=$_REQUEST['author'];
     if(!is_numeric($author) or $author<0){
       $response=array('state'=>400,'resp'=>'Error; bad author_id');
       echo json_encode($response);
       break;
     }

     if($qty<0){
       $state='400';
       $resp='Error, you can not set negative stock.';
     }else{
       $sql=sprintf("insert into in_out(tipo,date_creation,quantity,product_id,date,author) values (2,NOW(),'%s',%d,'%s',%d)",$qty,$product_id,$datetime,$author);
       $db->exec($sql);
       $stock=set_stock($product_id);
       $state='200';
       $resp='OK';
     }
     $response=array('state'=>$state,'resp'=>_($resp),'stock'=>$stock);
   }

   else
     $response=array('state'=>400,'resp'=>_('Error'));
echo json_encode($response);
break;
 case('new_product'):
   
   if(
       isset($_REQUEST['description'])  
      and  isset($_REQUEST['family_id'])    
       and  isset($_REQUEST['code'])  
       and  isset($_REQUEST['units'])  
       and  isset($_REQUEST['units_tipo'])  
       and  isset($_REQUEST['price'])  

       and $_REQUEST['description']!='' 
       and $_REQUEST['code']!=''    

       and is_numeric($_REQUEST['price'])  
       //and is_numeric($_REQUEST['units_tipo'])  

       and is_numeric($_REQUEST['units'])  
       and is_numeric($_REQUEST['family_id'])  


      ){
     $code=addslashes($_REQUEST['code']);
     $description=addslashes($_REQUEST['description']);
     $family_id=$_REQUEST['family_id'];
     if(isset($_REQUEST['rrp']) and is_numeric($_REQUEST['rrp']))
       $rrp=$_REQUEST['rrp'];
     else
       $rrp='NULL';
     
     if(isset($_REQUEST['units_carton']) and is_numeric($_REQUEST['units_carton']))
       $units_carton=$_REQUEST['units_carton'];
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

     $sql=sprintf("insert into  product (ncode,rrp,units_carton,units,units_tipo,price,description,code,group_id,first_date) values ('%s',%s,%s,'%s',%d,'%s','%s','%s',%d,NOW())",$ncode,$rrp,$units_carton,$_REQUEST['units'],$_REQUEST['units_tipo'],$_REQUEST['price'],$description,$code,$_REQUEST['family_id']);
     $affected=& $db->exec($sql);
     //     print "$sql\n";
     if (PEAR::isError($affected)) {
       if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	 $resp=_('Error: Another product has the same code').'.';
       else
	 $resp=_('Unknown Error').'.';
       $state='400';
       $data=array();
     }else{
       
       $product_id = $db->lastInsertID();

       $sql=sprintf("insert into inventory(fuzzy,date_start,date_end,name) values (1,NOW,NOW,'%s',)",_('New product'));
       $db->exec($sql);
       $inv_id = $db->lastInsertID();
       $sql=sprintf("insert into inventory_item (product_id,inventory_id,fecha) values (%d,,%d,NOW)",$product_id,$inv_id);
       $db->exec($sql);

       fix_todotransaction($product_id);
       set_stock($product_id);
       set_available($product_id);
	      
       // --------Supplier --------------

       if( isset($_REQUEST['supplier_id'])    and is_numeric($_REQUEST['supplier_id']) and      is_numeric($product_id)    ){
	 

	 $suppiler_id=$_REQUEST['supplier_id'];

	 
	 if(isset($_REQUEST['scode']) and  $_REQUEST['scode']!='')
	   $code="'".$_REQUEST['scode']."'";
	 else
	   $code='NULL';
     
	 if(isset($_REQUEST['sprice']) and  $_REQUEST['sprice']!='')
	   $price="'".$_REQUEST['sprice']."'";
	 else
	   $price='NULL';
	 
	 
	 $p2s_id=addtosupplier($product_id,$suppiler_id);
	 
	 if($p2s_id>0){
	   
	   $sql=sprintf("update  product2supplier set sup_code=%s , price=%s where id=%d",$code,$price,$p2s_id);
	   
	   $affected=& $db->exec($sql);
	 }
       }



       // ============================


       //normalize product
       set_sales($product_id);
       //normalize family

       //normalize supplier

       


       $resp='ok';
       $data= array(
		    
		    'id'=>$product_id
		    ,'code'=>$_REQUEST['code']
		    ,'description'=>$_REQUEST['description']
		    ,'units'=>$_REQUEST['units']
		    ,'price'=>$_REQUEST['price']
		    ,'units_tipo'=>$_REQUEST['units_tipo']
		    ,'stock'=>0
		    ,'available'=>0
		    ,'stock_value'=>0
		    ,'tsall'=>0
		    ,'tsy'=>0
		    ,'tsq'=>0
		    ,'tsm'=>0
		    );
       $state='200';
     }
     $response=array('state'=>$state,'resp'=>_($resp),'data'=>$data);
   }

   else
      $response=array('state'=>400,'resp'=>_('Error, please check that all the fields are filled'));
   echo json_encode($response);
   break;

 case('edit_product'):
   
   if(
       isset($_REQUEST['description'])  
       and  isset($_REQUEST['id'])    
       and  isset($_REQUEST['code'])  
       and  isset($_REQUEST['units'])  
      and  isset($_REQUEST['units_tipo'])  
       and  isset($_REQUEST['price'])  
       
       and $_REQUEST['description']!='' 
       and $_REQUEST['code']!=''    
       
       and is_numeric($_REQUEST['price'])  
       and is_numeric($_REQUEST['units_tipo'])  
       
       and is_numeric($_REQUEST['units'])  
       and is_numeric($_REQUEST['id'])  

       
      ){
     // Get previous values
     
     $id=$_REQUEST['id'];
     $sql=sprintf("select code,description  from product where id=%d",$id);

     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if(!$olddata=$res->fetchRow()) {
       $response=array('state'=>400,'resp'=>_("Error, product don't found"));
       echo json_encode($response);
       break;
     }
     
     
     if($olddata['code']!=$_REQUEST['code'] or $olddata['description']!=$_REQUEST['description'] ){
       $code=addslashes($_REQUEST['code']);
       $description=addslashes($_REQUEST['description']);

       $ncode=$code;
       $c=split('-',$code);
       if(count($c)==2){
	 if(is_numeric($c[1]))
	   $ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
	 else
	   $ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
       }     
    

       $sql=sprintf("update product set ncode='%s', code='%s' ,description='%s'  where id=%d",$ncode,$code,$description,$id);
       $affected=& $db->exec($sql);
       // print "$sql\n";
       if (PEAR::isError($affected)) {
	 if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	   $resp=_('Error: Another product has the same code').'.';
	 else
	   $resp=_('Unknown Error').'.';
	 $state='400';
	 $data=array();
	 $response=array('state'=>400,'resp'=>$resp);
	 echo json_encode($response);
	 break;
	 
       }
       
     }
     
     // update requiered fields
     $sql=sprintf("update product set units='%s' ,price='%s',units_tipo=%d  where id=%d",$_REQUEST['units'],$_REQUEST['price'],$_REQUEST['units_tipo'],$id);
     $db->exec($sql);
       
     
     
     if(isset($_REQUEST['rrp']) and is_numeric($_REQUEST['rrp']))
       $rrp=$_REQUEST['rrp'];
     else
       $rrp='NULL';
     
     if(isset($_REQUEST['units_carton']) and is_numeric($_REQUEST['units_carton']))
       $units_carton=$_REQUEST['units_carton'];
     else
       $units_carton='NULL';

     
    

     
    // update requiered fields
    $sql=sprintf("update product set units='%s' ,price='%s',units_tipo=%d ,rrp=%s,units_carton where id=%d",$_REQUEST['units'],$_REQUEST['price'],$_REQUEST['units_tipo'],$rrp,$units_carton,$id);
    $db->exec($sql);
    

    

    
    
    $resp='ok';
    $data= array(
		  'id'=>$id
		 );
    $state='200';
   
   $response=array('state'=>$state,'resp'=>_($resp),'data'=>$data);
   }else
     $response=array('state'=>400,'resp'=>_('Error, please check that all the fields are filled'));
   echo json_encode($response);
   break;
   
 case('withsupplier'):
   if(!$LU->checkRight(SUP_VIEW))
    exit;

    $conf=$_SESSION['state']['supplier']['products'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
 

   if(isset( $_REQUEST['id']))
    $supplier_id=$_REQUEST['id'];
  else
    $supplier_id=$_SESSION['state']['supplier']['id'];


   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 $filter_msg='';
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
 $_order=$order;
 $_dir=$order_direction;
 

   $_SESSION['state']['supplier']['products']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $_SESSION['state']['supplier']['id']=$supplier_id;

  $where=$where.' and ps.supplier_id='.$supplier_id;


  $wheref='';
  if(($f_field=='p.code' or $f_field=='sup_code') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";

  





  $sql="select count(*) as total from product  as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) left join product2supplier as ps on (product_id=p.id) $where $wheref ";


  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total=$row['total'];
  }
    if($wheref==''){
      $filtered=0;
    }else{
      
      $sql="select count(*) as total from product  as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) left join product2supplier as ps on (product_id=p.id)  $where  ";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$filtered=$row['total']-$total;
      }
      
    }
    


   $sql="select sup_code,ps.id as p2s_id,(p.units*ps.price) as price_outer,ps.price as price_unit,stock,p.condicion as condicion, p.code as code, p.id as id,p.description as description , group_id,department_id,g.name as fam, d.code as department 
from product as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) left join product2supplier as ps on (product_id=p.id)  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $code='<a href="product.php?id='.$row['id'].'">'.$row['code'].'</a>';
     $data[]=array(
		   'id'=>$row['id'],
		   'p2s_id'=>$row['p2s_id'],

		   'condicion'=>$row['condicion'],
		   'price_unit'=>money($row['price_unit']),
		   'price_outer'=>money($row['price_outer']),
		   'stock'=>($row['stock']==''?'':number($row['stock'])),
		   'code'=>$code,
		   'sup_code'=>$row['sup_code'],

		   'description'=>$row['description'],
		   'group_id'=>$row['group_id'],
		   'department_id'=>$row['department_id'],
		   'fam'=>$row['fam'],
		   'department'=>$row['department'],
		   'delete'=>'<img src="art/icons/link_delete.png"/>'

		   );
   }

   if($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
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

 case('withsupplier_po'):
   if(!$LU->checkRight(SUP_VIEW))
    exit;

    $conf=$_SESSION['state']['supplier']['products'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
 

   if(isset( $_REQUEST['id']))
    $supplier_id=$_REQUEST['id'];
  else
    $supplier_id=$_SESSION['state']['supplier']['id'];
   
 if(isset( $_REQUEST['po_id']))
    $po_id=$_REQUEST['po_id'];
  else
    $po_id=$_SESSION['state']['po']['id'];


   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 $filter_msg='';
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
 $_order=$order;
 $_dir=$order_direction;
 

   $_SESSION['state']['supplier']['products']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $_SESSION['state']['supplier']['id']=$supplier_id;

  $where=$where.' and ps.supplier_id='.$supplier_id;


  $wheref='';
  if(($f_field=='code' or $f_field=='sup_code') and $f_value!='')
    $wheref.=" and  p.code  like '".addslashes($f_value)."%'";
  if(($f_field=='sup_code') and $f_value!='')
    $wheref.=" and  sup_code like '".addslashes($f_value)."%'";
  





  $sql="select count(*) as total from product  as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) left join product2supplier as ps on (product_id=p.id) $where $wheref ";


  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total=$row['total'];
  }
    if($wheref==''){
      $filtered=0;
    }else{
      
      $sql="select count(*) as total from product  as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) left join product2supplier as ps on (product_id=p.id)  $where  ";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$filtered=$row['total']-$total;
      }
      
    }
    


   $sql="select p.units as punits,(select expected_qty from porden_item where porden_id=$po_id and porden_item.p2s_id=ps.id) as qty,   sup_code,ps.id as p2s_id,(p.units*ps.price) as price_outer,ps.price as price_unit,stock,p.condicion as condicion, p.code as code, p.id as id,p.description as description , group_id,department_id,g.name as fam, d.code as department 
from product as p left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id) left join product2supplier as ps on (product_id=p.id)  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $code='<a href="product.php?id='.$row['id'].'">'.$row['code'].'</a>';
     $data[]=array(
		   'id'=>$row['id'],
		   'p2s_id'=>$row['p2s_id'],

		   'condicion'=>$row['condicion'],
		   'price_unit'=>money($row['price_unit']),
		   'price_outer'=>money($row['price_outer']),
		   'stock'=>($row['stock']==''?'':number($row['stock']).' ('.number($row['stock']*$row['punits']).')'  ),
		   'code'=>$code,
		   'sup_code'=>$row['sup_code'],
		   'qty'=>'<input type="text" value="" onchange="value_changed(this)" size="3"  id="p'.$row['id'].'"  pid="'.$row['id'].'" class="aright" />',
		   'description'=>$row['description'],
		   'group_id'=>$row['group_id'],
		   'department_id'=>$row['department_id'],
		   'fam'=>$row['fam'],
		   'department'=>$row['department'],
		   'delete'=>'<img src="art/icons/link_delete.png"/>'

		   );
   }

   if($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
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
   case('list_pareto_products'):
   $first_day=addslashes($myconf['data_since']);
   $data_name='tsy';
   $sql=sprintf("select code,id,$data_name as value from product where $data_name>0 order by $data_name  desc ");

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   $i=0;
   $total_value=0;
   $data=array();
   while($row=$res->fetchRow()) {
     $total_value+=$row['value'];
     $code[]=$row['code'];
     $data[]=$row['value'];
     $i++;
   }
   $total_products=$i;
   $_value=0;
    printf("<table border=1>");
   if($total_value>0){
     $i=1;
     foreach($data as $key=>$value){
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

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $data[$row['yearweek']]=0;
   }

   $sql=sprintf("select sum(net) as net,yearweek(date_index,1) as year_week from orden  left join transaction on (order_id=orden.id) where product_id=11291 and date_index>%s and orden.tipo=2 group by yearweek(date_index) ",prepare_mysql($first_day));
   
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $fix_w53='';
   while($row=$res->fetchRow()) {

     if($fix_w53!=''){
       $b=$fix_w53;
       $a= $row['net'];

       $data[$row['year_week']]=(2*$a+$b)/3;
       $fix_w53='';
     }elseif(preg_match('/53$/',$row['year_week'])){
       
       $b=$row['net'];
       //change previous weeks
       $a= $data[$row['year_week']-1];
       $data[$row['year_week']-1]=(2*$a+$b)/3;
       $fix_w53=$b;
     }else{
       if(array_key_exists($row['year_week'],$data))
	 $data[$row['year_week']]=$row['net'];
     }

   }
   $i=0;

   foreach($data as $key=>$value){
     $i++;
     print "$value\n";
   }
   break;
 case('plot_weekout'): 
 case('plot_weeksales'):
   
   $product_id=$_SESSION['state']['product']['id'];

//    $sql=sprintf("select (TO_DAYS(NOW())-TO_DAYS(first_date))/7 as weeks_since  from product where id=%d",$product_id);
//    $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

//    if($row=$res->fetchRow()) {
//      $weeks=floor($row['weeks_since']);
//    }

   $first_day=addslashes($myconf['data_since']);
   $sql="select first_day as date, yearweek,date_format(first_day,'%v %x') as week,  UNIX_TIMESTAMP(first_day) as utime  from list_week where first_day>'$first_day' and first_day < NOW(); ";

   $data=array();
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $i=0;
   while($row=$res->fetchRow()) {
     $index[$row['yearweek']]=$i;
     $data[]=array(
		   'tip'=>_('No sales this week'),
		   'date'=>$row['date'],
		   'week'=>$row['week'],
		   'utime'=>$row['utime'],
		   'asales'=>0,
		   'out'=>0,
		   
		   );

     $i++;
    }


   $tipo_orders=' (orden.tipo!=0 or orden.tipo!=3 or  orden.tipo!=9 or orden.tipo!=10) ';
   $sql=sprintf("select YEARWEEK(date_index) as yearweek,sum(charge)as asales,sum(dispached)as _out from transaction left join orden on (order_id=orden.id) where %s and product_id=%d  group by YEARWEEK(date_index)",$tipo_orders,$product_id);

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

    while($row=$res->fetchRow()) {

      if(isset($index[$row['yearweek']])){
	$_index=$index[$row['yearweek']];
	$data[$_index]['asales']=(float)$row['asales'];
	$data[$_index]['out']=(int)$row['_out'];
	$fday=strftime("%d %b", strtotime('@'.$data[$_index]['utime']));
	if($tipo=='plot_weekout')
	  $data[$_index]['tip']=_('Outer Dispached')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['_out']).' '._('Outers');
	else
	  $data[$_index]['tip']=_('Sales')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['asales']);
      }
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

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   if($row=$res->fetchRow()) {
     $month=floor($row['m']);
     $year=floor($row['y']);
     $first=sprintf("%d%02d",$year,$month);
     $since=$row['since'];
   }
   //   print "$year $month ";

   $data=array();
  
   for($i=$since;$i>=0;$i--){

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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {
     $data[$row['since']]['out']=(int)$row['_out'];
     $data[$row['since']]['asales']=(float)$row['asales'];
     if($tipo=='plot_monthout')
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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   if($row=$res->fetchRow()) {
     $q=floor($row['q']);
     $y=floor($row['y']);
   }

   $i=0;
   foreach (range($y,date('Y')) as $year) {
     foreach (range(1,4) as $quarter) {
       if($year==$y and $q>$quarter)
	 continue;
       if($year==date('Y') and date('Q')>$quarter)
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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {
      $data[$index[$row['qy']]]['asales']=$row['asales'];
      $data[$index[$row['qy']]]['out']=$row['_out'];
      switch($row['quarter']){
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
      if($tipo=='plot_quarterout')
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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   
   if($row=$res->fetchRow()) {
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
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

while($row=$res->fetchRow()) {
  $data[$index[$row['year']]]['out']=$row['_out'];
  $data[$index[$row['year']]]['asales']=$row['asales'];
  if($tipo=='plot_yearout')
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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   if($row=$res->fetchRow()) {
     $weeks=floor($row['weeks_since']);
   }

   $data=array();
   for($i=$weeks;$i>=0;$i--){

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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {
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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $_day='xxx';
   $i=0;
   while($row=$res->fetchRow()) {
     if($_day==$row['day']){
       array_pop($data);
       $data[]=array(
		     'stock'=>$row['stock'],
		     'day'=>$row['day'],
		     'tip'=>$row['date']
		     );

	if($i>700000)
	  break;
     }else{
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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   if($row=$res->fetchRow()) {
     $weeks=floor($row['weeks_since']);
   }

   $data=array();
   for($i=$weeks;$i>=0;$i--){

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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {

     if($row['aorders']>0)
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
 if(isset( $_REQUEST['elements']))
     $elements=$_REQUEST['elements'];
   else
     $elements=$conf['elements'];

 if(isset( $_REQUEST['from']))
     $from=$_REQUEST['from'];
   else
     $from=$conf['from'];
  if(isset( $_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=$conf['to'];
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
 
 
 list($date_interval,$error)=prepare_mysql_dates($from,$to);
  if($error){
    list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
  }else{
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
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select count(*) as total from history  $where ";
     
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }
   
   
   if($total==0)
     $rtext=_('No stock movements');
   else
     $rtext=$total.' '.ngettext('stock operetion','stock operations',$total);
   



  $sql=sprintf("select  UNIX_TIMESTAMP(date) as date,handle as author ,history.note,history.staff_id  from history left join liveuser_users  on (authuserid=history.staff_id) $where $wheref order by $order $order_direction limit $start_from,$number_results ");
  // print $sql;
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  $adata=array();
  while($data=$res->fetchRow()) {


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
case('location_stock_history'):
 $conf=$_SESSION['state']['location']['stock_history'];
 $location_id=$_SESSION['state']['location']['id'];
 if(isset( $_REQUEST['elements']))
     $elements=$_REQUEST['elements'];
   else
     $elements=$conf['elements'];

 if(isset( $_REQUEST['from']))
     $from=$_REQUEST['from'];
   else
     $from=$conf['from'];
  if(isset( $_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=$conf['to'];
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
 
 
 list($date_interval,$error)=prepare_mysql_dates($from,$to);
  if($error){
    list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
  }else{
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

  


  $wheref='';

  $where=$where.sprintf(" and objeto='LOC' and objeto_id=%d  ",$location_id);

   
  //   $where =$where.$view.sprintf(' and product_id=%d  %s',$product_id,$date_interval);
   
   $sql="select count(*) as total from history   $where $wheref";
   //   print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select count(*) as total from history   $where ";
     
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }
   
   
   if($total==0)
     $rtext=_('No stock movements');
   else
     $rtext=$total.' '.ngettext('stock operation','stock operations',$total);
   



  $sql=sprintf("select  UNIX_TIMESTAMP(date) as date,handle as author ,history.note,history.staff_id  from history left join liveuser_users  on (authuserid=history.staff_id)  left join product2location on (product2location.id=objeto_id)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");
  // print $sql;
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  $adata=array();
  while($data=$res->fetchRow()) {


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
 case('products_in_location'):

   $conf=$_SESSION['state']['location']['products'];
   $location_id=$_SESSION['state']['location']['id'];
   
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
     else
       $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
  
 if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
 
 


  $_SESSION['state']['location']['products']=
    array(
	  'order'=>$order,
	  'order_dir'=>$order_direction,
	  //'nr'=>$number_results,
	  // 'sf'=>$start_from,
	  'where'=>$where,
	  'f_field'=>$f_field,
	  'f_value'=>$f_value,
	  //  'from'=>$from,
	  //  'to'=>$to,
	  //  'elements'=>$elements
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

  
  $start_from=0;
  $number_results=99999999;

  $where=$where.sprintf(" and location_id=%d",$location_id);

   
  //   $where =$where.$view.sprintf(' and product_id=%d  %s',$product_id,$date_interval);
   
   $sql="select count(distinct product_id) as total from product2location    $where $wheref";
   //   print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0;
       $total_records=$total;
   }else{
     $sql="select  count(distinct product_id) as total from product2location  $where ";
     
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }
   
   
   if($total_records==0)
     $rtext=_('No products on this location');
   else
     $rtext=$total_records.' '.ngettext('product','products',$total_records);
   
   if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   




   $sql=sprintf("select  product2location.id,product_id,product.code as code, product.description ,product2location.stock as qty from product2location left join product on (product.id=product_id) $where $wheref order by $order $order_direction  ");

  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  $adata=array();
  while($data=$res->fetchRow()) {


    $adata[]=array(

		   'code'=>sprintf('<a href="product.php?id=%d">%s</a>',$data['product_id'],$data['code'])
		   ,'description'=>$data['description']
		   ,'current_qty'=>sprintf('<span  used="0"  value="%s" id="s'.$data['id'].'"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['qty'],$data['qty'],$data['id'],$data['product_id'],number($data['qty']))
		   ,'changed_qty'=>sprintf('<span   used="0" id="cs'.$data['id'].'"  onclick="change_reset(%d,%d)"   ">0</span>',$data['id'],$data['product_id'])
		   ,'new_qty'=>sprintf('<span  used="0"  value="%s" id="ns'.$data['id'].'"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['qty'],$data['qty'],$data['id'],$data['product_id'],number($data['qty']))
		   ,'_qty_move'=>'<input id="qm'.$data['id'].'" onchange="qty_changed('.$data['id'].','.$data['product_id'].')" type="text" value="" size=3>'
		   ,'_qty_change'=>'<input id="qc'.$data['id'].'" onchange="qty_changed('.$data['id'].','.$data['product_id'].')" type="text" value="" size=3>'
		   ,'_qty_damaged'=>'<input id="qd'.$data['id'].'" onchange="qty_changed('.$data['id'].','.$data['product_id'].')" type="text" value="" size=3>'
		   ,'note'=>'<input  id="n'.$data['id'].'" type="text" value="" style="width:100px">'
		   ,'delete'=>($data['qty']==0?'<img onclick="remove_prod('.$data['id'].','.$data['product_id'].')" style="cursor:pointer" title="'._('Remove').' '.$data['code'].'" alt="'._('Desassociate Product').'" src="art/icons/cross.png".>':'')
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




?>