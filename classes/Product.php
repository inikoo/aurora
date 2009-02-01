<?
include_once('Deal.php');
class product{
  
 
  var $product=array();
  var $categories=array();

  var $parents=array();
  var $childs=array();
  var $supplier=false;
  var $locations=false;
  var $notes=array();
  var $images=false;
  var $weblink=false;
  var $db;

  var $location_to_update=false;

  function __construct($a1,$a2=false,$a3=false) {
    $this->db =MDB2::singleton();


    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    } else
      $this->get_data($a1,$a2,$a3);

  }
  



  function get_data($tipo,$tag,$extra=false){
    global $_shape,$_units_tipo,$_units_tipo_abr,$_units_tipo_plural;

    if($tipo=='id')
      $sql=sprintf("select * from `Product Dimension` where `Product Key`=%d ",$tag);
    elseif($tipo=='code'){
      $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Most Recent`='Yes' ",prepare_mysql($tag));
    }      elseif($tipo=='code_fuzzy'){
	$code=$tag['code'];
	$name=$tag['name'];
	$units=$tag['units'];
	$unit_type=$tag['unit_type'];
	$price=$tag['price'];
	
	$date=$tag['date'];

	$auto_fix=$tag['auto_fix'];
	$sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Price`=%s and `Product Name`=%s and `Product Units Per Case`=%s and `Product Unit Type`  ",prepare_mysql($tag)); 

    }

    //print "$sql\n";
    if($result =& $this->db->query($sql)){
      $this->data=$result->fetchRow();
      $this->id=$this->data['product key'];
    }
  }

 //    $this->dim_units='cm';


      
//     $sql=sprintf("select *,UNIX_TIMESTAMP(first_date) as first_date from product ");


//     if($tipo=='id')
//       $sql.=sprintf("where id=%d",$tag);
//     else if($tipo=='code')
//       $sql.=sprintf("where code=%s",prepare_mysql($tag));
//     //     print $sql;
//     if($result =& $this->db->query($sql)){
//       $this->data=$result->fetchRow();
//       $this->id=$this->data['id'];
//       if($this->data['first_date']!='')
// 	$this->data['dates']=array('first_date'=>($this->data['first_date']?strftime("%e %b %Y",$this->data['first_date']):''));
//       else{
// 	$this->load('first_date','save');
//       }
//       $this->data['units_tipo_name']=$_units_tipo[$this->data['units_tipo']];
//       $this->data['units_tipo_shortname']=$_units_tipo_abr[$this->data['units_tipo']];
//       $this->data['units_tipo_plural']=$_units_tipo_plural[$this->data['units_tipo']];
      


//       $this->data['odim_tipo']=$this->data['odim_tipo'];
//       $this->data['odim_tipo_name']=$_shape[$this->data['odim_tipo']];
//       $this->data['dim_tipo']=$this->data['dim_tipo'];
//       $this->data['dim_tipo_name']=$_shape[$this->data['dim_tipo']];
//       if($this->data['odim']!='')
// 	$this->data['odimension']=$this->data['odim_tipo_name']." (".$this->data['odim'].")".$this->dim_units;
//       else
// 	$this->data['odimension']='';
//       if($this->data['dim']!='')
// 	$this->data['dimension']=$this->data['dim_tipo_name']." (".$this->data['dim'].")".$this->dim_units;
//       else
// 	$this->data['dimension']='';
	  
//       $this->get('ovol');
//       $this->get('vol');


  
  
  function load($data_to_be_read,$args=''){
    
    if(!is_array($data_to_be_read))
      $data_to_be_read=array($data_to_be_read);
    foreach($data_to_be_read as $table){
      
      switch($table){
      case('weblinks'):
	$this->weblink=array();
// 	$sql=sprintf("select * from product_webpages  where   product_id=%d ",$this->id);
// 	$result =& $this->db->query($sql);
// 	while($row=$result->fetchRow()){
// 	  $this->weblink[$row['link']]=array('id'=>$row['id'],'title'=>$row['title']);
// 	}
	
	break;

      case('product_tree'):
// 	$sql=sprintf('select d.name as department,d.id as department_id,g.name as group_name,group_id from product left join product_group as g on (g.id=group_id)  left join product_department as d on (d.id=department_id) where product.id=%s ',$this->id);

// 	$res = $this->db->query($sql); 
// 	if ($row=$res->fetchRow()) {
// 	  $this->group_id=$row['group_id'];
// 	  $this->department_id=$row['department_id'];
// 	  $this->group=$row['group_name'];
// 	  $this->department=$row['department'];
// 	}
	break;
      case('locations'):
	global $_location_tipo;
	$this->load('same_products');

// 	$_data=array();
// 	$this->locations=array('is_parent'=>false,'has_display'=>false,'has_unknown'=>false,'has_loading'=>false,'has_link'=>false,'has_white_hole'=>false,'has_picking_area'=>false,'has_physical'=>false,'data'=>array(),'num_physical'=>0,'num_physical_with_stock'=>0,'num_picking_areas'=>0);
// 	$sql=sprintf("select max_stock,name,product2location.id as id,location_id,stock,picking_rank,tipo,stock  from product2location left join location on (location_id=location.id) where product_id=%d and picking_rank is not null order by picking_rank  ",$this->data['location_parent_id']);
// 	$result =& $this->db->query($sql);
// 	$num_same_products=count($this->same_products);
// 	if($num_same_products>0){
// 	  $this->locations['has_link']=true;
// 	  if($this->id==$this->data['location_parent_id'])
// 	    $this->locations['is_parent']=true;
// 	}
// 	$stock_units=0;	  
// 	while($row=$result->fetchRow()){
// 	  $stock=number($row['stock']/$this->data['units'],1);
// 	  if($num_same_products==0)
// 	    $stock_outers=$stock;
// 	  else{
// 	    $stock_outers='<b>'.number($stock,1).'</b>';
// 	    foreach($this->same_products as $_same){
// 	      $stock_outers.=';'.number($row['stock']/$_same['units'],1);
// 	    }
// 	  }
	  
// 	  $stock_units+=$row['stock'];
	  
// 	  if($row['max_stock']=='' or $row['max_stock']<=0 )
// 	    $max_units=_('Not set');
// 	  else
// 	    $max_units=$row['max_stock'];
// 	  $_data[$row['id']]=array(
// 				   'id'=>$row['id'],
// 				   'name'=>$row['name'],
// 				   'location_id'=>$row['location_id'],
// 				   'stock'=>$stock,
// 				   'stock_units'=>$row['stock'],
// 				   'stock_outers'=>$stock_outers,
// 				   'max_units'=>$max_units,
// 				   'tipo'=>$_location_tipo[$row['tipo']],
// 				   'picking_tipo'=>getOrdinal($row['picking_rank']),
// 				   'picking_rank'=>$row['picking_rank'],
// 				   'is_physical'=>true,
// 				   'can_pick'=>true,
// 				   'has_stock'=>($row['stock']>0?true:false)
// 				   );
// 	  $this->locations['num_physical']++;
// 	  if($row['stock']>0)
// 	    $this->locations['num_physical_with_stock']++;
// 	  $this->locations['num_picking_areas']++;
// 	  $this->locations['has_physical']=true;
	  


// 	}
	


// 	$sql=sprintf("select max_stock,name,product2location.id as id,location_id,stock,picking_rank,tipo,stock  from product2location left join location on (location_id=location.id) where product_id=%d and picking_rank is  null order by tipo desc  ",$this->data['location_parent_id']);
// 	$result =& $this->db->query($sql);
// 	while($row=$result->fetchRow()){
// 	  $stock_units+=$row['stock'];
// 	  $stock=number($row['stock']/$this->data['units'],1);
// 	  if($num_same_products==0)
// 	    $stock_outers=$stock;
// 	  else{
// 	    $stock_outers='<b>'.number($stock,1).'</b>';
// 	    foreach($this->same_products as $_same){
// 	      $stock_outers.=';'.number($row['stock']/$_same['units'],1);
// 	    }
// 	  }


// 	  $is_physical=false;
// 	  $picking_tipo='';
// 	  $can_pick=false;
// 	  $icon='';
// 	  $tipo='';
// 	  if($row['tipo']=='unknown'){
// 	    $this->locations['has_unknown']=true;
// 	    $name=$_location_tipo[$row['tipo']];
// 	  }else if($row['tipo']=='white_hole'){
// 	    $this->locations['has_white_hole']=true;
// 	    $name=$_location_tipo[$row['tipo']];
// 	  }else if($row['tipo']=='loading'){
// 	    $this->locations['has_loading']=true;
// 	    $name=$row['name'];
	  
// 	  }else{
// 	    $tipo=$_location_tipo[$row['tipo']];
// 	    $name=$row['name'];
// 	    $this->locations['has_physical']=true;
// 	    $this->locations['num_physical']++;
// 	    if($row['stock']>0)
// 	      $this->locations['num_physical_with_stock']++;
// 	    $is_physical=true;
// 	  }
	    
// 	  if($row['max_stock']=='' or $row['max_stock']<=0 )
// 	    $max_units=_('Not set');
// 	  else
// 	    $max_units=$row['max_stock'];

// 	  $_data[$row['id']]=array(
// 				   'id'=>$row['id'],
// 				   'name'=>$name,
// 				   'location_id'=>$row['location_id'],
// 				   'stock'=>$stock,
// 				   'stock_units'=>$row['stock'],
// 				   'stock_outers'=>$stock_outers,
// 				   'max_units'=>$max_units,
// 				   'tipo'=>$tipo,
// 				   'picking_tipo'=>$picking_tipo,
// 				   'picking_rank'=>'',
// 				   'is_physical'=>$is_physical,
// 				   'can_pick'=>$can_pick,
// 				   'has_stock'=>($row['stock']>0?true:false)
// 				   );

// 	}
	
// 	$this->locations['data']=$_data;
// 	$this->locations['stock']=$this->data['stock'];
// 	$this->locations['stock_units']=$stock_units;
// 	$this->locations['stock_outers']="<b>".$this->data['stock'].'</b>';

// 	foreach($this->same_products as $_same){
// 	  $this->locations['stock_outers'].=';'.number($stock_units/$_same['units']);
// 	}


// 	break;
//       case('suppliers'):
// 	$this->supplier=array();
// // 	$sql=sprintf("select p2s.supplier_id, p2s.price,p2s.sup_code,s.code as code,s.name as name from product2supplier as p2s left join supplier as s on (p2s.supplier_id=s.id) where p2s.product_id=%d order by s.code",$this->id);
	
	
// // 	$result =& $this->db->query($sql);
// // 	while($row=$result->fetchRow()){
// // 	  $this->supplier[$row['supplier_id']]=array(
// // 						     'id'=>$row['supplier_id'],
// // 						     'name'=>$row['name'],
// // 						     'price'=>$row['price'],
// // 						     'formated_price'=>money($row['price']),
// // 						     'supplier_product_code'=>$row['sup_code'],
// // 						     'code'=>$row['code']

// // 						     );
// // 	}


	break;
	// print_r($this->suppliers);
      case('same_products'):
// 	$sql=sprintf("select id,code,units from product  where location_parent_id=%d and id!=%d order by units",$this->data['location_parent_id'],$this->id);
// 	$result =& $this->db->query($sql);
// 	$this->same_products=array();
// 	while($row=$result->fetchRow()){
// 	  $this->same_products[$row['id']]=array(
// 						 'code'=>$row['code'],
// 						 'units'=>$row['units'],
// 						 'f_units'=>number($row['units'])
// 						 );
	    
// 	}
	break;
      case('categories'):

	$this->categories['list']=array();
	$sql=sprintf("select cat_id,name from product2cat left join cat on (cat_id=cat.id) where product_id=%d ",$this->id);
	$result =& $this->db->query($sql);
	while($row=$result->fetchRow()){
	  $this->categories['list'][$row['cat_id']]=$row['name'];
	  //	  $this->categories['parents'][$row['cat_id']]=
	}

	$this->categories['number']=count($this->categories['list']);
	break;
      case('images'):
// 	$this->image_path='server_files/images/';
// 	$this->images=array();
// 	$sql=sprintf("select checksum,name,format,principal,caption,id,width,height,size from image where  product_id=%d order by principal desc",$this->id);
// 	$result =& $this->db->query($sql);
// 	$principal=false;
// 	$default=false;
// 	while($row=$result->fetchRow()){
// 	  if(!$default)
// 	    $default=$row['id'];
// 	  if($row['principal']==1 and !$principal){
// 	    $set_principal=true;
// 	    $principal=true;
// 	    $this->data['principal_image']=$row['id'];
// 	  }else
// 	    $set_principal=false;
	  
// 	  $this->images[$row['id']]=array(
// 					  'id'=>$row['id'],
// 					  'width'=>$row['width'],
// 					  'height'=>$row['height'],
// 					  'size'=>$row['size'],
// 					  'caption'=>$row['caption'],
// 					  'checksum'=>$row['checksum'],
// 					  'principal'=>$set_principal,
// 					  'tb'=>$this->image_path.'tb/'.$row['name'].'_tb.'.$row['format'],
// 					  'med'=>$this->image_path.'med/'.$row['name'].'_med.'.$row['format'],
// 					  'orig'=>$this->image_path.'original/'.$row['name'].'_orig.'.$row['format'],
// 					  'name'=>$row['name']
// 					  );
// 	}
// 	if(!$principal and count($this->images)>0){
// 	  $this->images[$default]['principal']=true;
// 	  $this->data['principal_image']=$default;
// 	}
	  
	break;
      case('stock_forecast'):
	//simplest one
	//get the best possible average
	$aw=0;
	if(is_numeric($this->get('awtsoq')) and $this->get('awtsoq')>0)
	  $aw=$this->get('awtsoq');
	elseif(is_numeric($this->get('awtsom')) and $this->get('awtsom')>0)
	  $aw=$this->get('awtsoq');
	elseif(is_numeric($this->get('tsow')) and $this->get('tsow')>0)
	  $aw=$this->get('tdso');
	


	if($aw>0 and is_numeric($this->data['stock']) and $this->data['stock']>=0){
	  $this->data['days_to_ns']=$this->data['stock']/$aw/7;
	  $sql=sprintf("update product set days_to_ns='%.1f' where id=%d",$this->data['days_to_ns'],$this->id);

	  $this->db->exec($sql);
	  

	}
	break;
      case('first_date'):
	$sql=sprintf("select date_creation,UNIX_TIMESTAMP(date_creation) as ts_date_creation from transaction left join orden on (order_id=orden.id) where product_id=%d order by date_index limit 1",$this->id);
	//print "$sql\n";
	$res =& $this->db->query($sql);
	if ($row=$res->fetchRow()) {
	  $this->data['first_date']=$row['ts_date_creation'];;
	  $this->data['dates']['first_date']=strftime("%e %b %Y", $this->data['first_date']);
	}else{
	  $this->data['first_date']='';
	  $this->data['dates']['first_date']='';
	}

	if(preg_match('/save/',$args))
	  $this->save('first_date');
	
	break;
      case('sales'):
	$weeks=$this->get('weeks');
	

	$this->data['sales']['tsall']=0;
	$this->data['sales']['tsoall']=0;
	$this->data['sales']['awtsall']='';
	$this->data['sales']['awtsoall']='';


	// all the products out
	$sql=sprintf("select   ifnull(sum(charge),0) as sales  , ifnull(sum(dispached),0) as outers from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo=2 or o.tipo=4 or o.tipo=5 or o.tipo=6 or o.tipo=8) "
		     ,$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $this->data['sales']['tsall']=$row['sales'];
	  $this->data['sales']['tsoall']=$row['outers'];
	}
	if($weeks>0){
	  $this->data['sales']['awtsall']= $this->data['sales']['tsall']/$weeks;
	  $this->data['sales']['awtsoall']=$this->data['sales']['tsoall']/$weeks;
	}
	
	$sql=sprintf("select (TO_DAYS(NOW())-TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 year))) as days, weekday(DATE_SUB(CURDATE(),INTERVAL 1 year)) as day,    sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo=2 or o.tipo=4 or o.tipo=5 or o.tipo=6 or o.tipo=8)   and DATE_SUB(CURDATE(),INTERVAL 1 YEAR) <= date_index "
		     ,$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $this->data['sales']['tsy']=$row['sales'];
	  $this->data['sales']['tsoy']=$row['outers'];
	  $_weeks=number_weeks($row['days'],$row['day']);
	  if($weeks>=$_weeks){
	    $this->data['sales']['awtsy']=$row['sales']/$_weeks;
	    $this->data['sales']['awtsoy']=$row['outers']/$_weeks;
	  }else{
	    $this->data['sales']['awtsy']=$this->data['sales']['awtsall'];
	    $this->data['sales']['awtsoy']=$this->data['sales']['awtsall'];
	  }
	}
	$sql=sprintf("select (TO_DAYS(NOW())-TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 3 month))) as days, weekday(DATE_SUB(CURDATE(),INTERVAL 3 month)) as day,    ifnull( sum(charge),0) as sales , ifnull(sum(dispached),0) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo=2 or o.tipo=4 or o.tipo=5 or o.tipo=6 or o.tipo=8)   and DATE_SUB(CURDATE(),INTERVAL 3 month) <= date_index "
		     ,$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $this->data['sales']['tsq']=$row['sales'];
	  $this->data['sales']['tsoq']=$row['outers'];
	  $_weeks=number_weeks($row['days'],$row['day']);
	  if($weeks>=$_weeks){
	    $this->data['sales']['awtsq']=$row['sales']/$_weeks;
	    $this->data['sales']['awtsoq']=$row['outers']/$_weeks;
	  }else{
	    $this->data['sales']['awtsq']=$this->data['sales']['awtsall'];
	    $this->data['sales']['awtsoq']=$this->data['sales']['awtsall'];
	  }
	}

	$sql=sprintf("select (TO_DAYS(NOW())-TO_DAYS(DATE_SUB(CURDATE(),INTERVAL 1 month))) as days, weekday(DATE_SUB(CURDATE(),INTERVAL 1 month)) as day,    ifnull(sum(charge),0) as sales , ifnull(sum(dispached),0) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo=2 or o.tipo=4 or o.tipo=5 or o.tipo=6 or o.tipo=8)   and DATE_SUB(CURDATE(),INTERVAL 1 month) <= date_index "
		     ,$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $this->data['sales']['tsm']=$row['sales'];
	  $this->data['sales']['tsom']=$row['outers'];

	  $_weeks=number_weeks($row['days'],$row['day']);
	  if($weeks>=$_weeks){
	    $this->data['sales']['awtsm']=$row['sales']/$_weeks;
	    $this->data['sales']['awtsom']=$row['outers']/$_weeks;
	  }else{
	    $this->data['sales']['awtsm']=$this->data['sales']['awtsall'];
	    $this->data['sales']['awtsom']=$this->data['sales']['awtsall'];
	  }
	}
	$sql=sprintf("select     ifnull(sum(charge),0) as sales , ifnull(sum(dispached),0) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo=2 or o.tipo=4 or o.tipo=5 or o.tipo=6 or o.tipo=8)   and DATE_SUB(CURDATE(),INTERVAL 1 week) <= date_index "
		     ,$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  
	  $this->data['sales']['tsw']=$row['sales'];
	  $this->data['sales']['tsow']=$row['outers'];

	}








	break;
       
      case('parents'):
	break;
      case('children'):
	break;
      case('notes'):
	break;
	
      }
      
    }
    return true;
  }
  function set($item_array){
    
    foreach($item_array as $key=>$value){
      if (array_key_exists($key, $this->product)) {
	$old_value=$this->data[$key];
	$new_value=$value;
	if($old_value!=$new_value){
	  $this->changes[$key]=array('old_value'=>$old_value,'new_value'=>$new_value);
	}else if(array_key_exists($key, $this->changes))
	  unset($this->changes[$key]);
      }
	
    }
  }


  function read($key,$data=false){


    switch($key){
    case('weblink'):
      $sql=sprintf("select * from product_webpages  where   product_id=%d and link=%s",$this->id,prepare_mysql($data));
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	return array('id'=>$row['id'],'link'=>$row['link'],'title'=>$row['title']);
      }else
	return false;
      break;
    case('max_units_per_location'):
      
      
      $sql=sprintf("select max_stock from product2location  where   id=%d and product_id=%d",$data['id'],$this->id);
      //      print $sql;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	return $row['max_stock'];
      }else
	return false;
      
      break;
    case('dimension'):
    case('odimension'):


      global $_shape;
      $sql=sprintf("select dim as dimension,odim as odimension,dim_tipo as dimension_tipo,odim_tipo as odimension_tipo from product where  id=%d",$this->id);
      
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	if($row[$key]!='')
	  return $_shape[$row[$key.'_tipo']]." (".$row[$key].')'.$this->dim_units;
	else
	  return '';
      }
      break;
    case('image'):
      $sql=sprintf("select checksum,name,format,principal,caption,id,width,height,size from image where  id=%d",$data);
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$image=array(
		     'id'=>$row['id'],
		     'width'=>$row['width'],
		     'height'=>$row['height'],
		     'size'=>$row['size'],
		     'caption'=>$row['caption'],
		     'checksum'=>$row['checksum'],
		     'principal'=>$row['principal'],
		     'tb'=>$this->image_path.'tb/'.$row['name'].'_tb.'.$row['format'],
		     'med'=>$this->image_path.'med/'.$row['name'].'_med.'.$row['format'],
		     'orig'=>$this->image_path.'original/'.$row['name'].'_orig.'.$row['format'],
		     'name'=>$row['name']
		     );
	return $image;
	
      }
      break;
    case('principal_image'):
      $sql=sprintf("select checksum,name,format,principal,caption,id,width,height,size from image where  principal=1 and product_id=%d",$this->id);
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$image=array(
		     'id'=>$row['id'],
		     'width'=>$row['width'],
		     'height'=>$row['height'],
		     'size'=>$row['size'],
		     'caption'=>$row['caption'],
		     'checksum'=>$row['checksum'],
		     'principal'=>$row['principal'],
		     'tb'=>$this->image_path.'tb/'.$row['name'].'_tb.'.$row['format'],
		     'med'=>$this->image_path.'med/'.$row['name'].'_med.'.$row['format'],
		     'orig'=>$this->image_path.'original/'.$row['name'].'_orig.'.$row['format'],
		     'name'=>$row['name']
		     );
	return $image;
	
      }else{
	$sql=sprintf("select checksum,name,format,principal,caption,id,width,height,size from image where  product_id=%d order by principal desc",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $image=array(
		       'id'=>$row['id'],
		       'width'=>$row['width'],
		       'height'=>$row['height'],
		       'size'=>$row['size'],
		       'caption'=>$row['caption'],
		       'checksum'=>$row['checksum'],
		       'principal'=>$row['principal'],
		       'tb'=>$this->image_path.'tb/'.$row['name'].'_tb.'.$row['format'],
		       'med'=>$this->image_path.'med/'.$row['name'].'_med.'.$row['format'],
		       'orig'=>$this->image_path.'original/'.$row['name'].'_orig.'.$row['format'],
		       'name'=>$row['name']
		       );
	  return $image;
	  
	}
      }
      break;
    
    case('img_new'):
      break;
    case('img_caption'):
      $sql=sprintf("select caption as value from image where id=%d",$this->changing_img);
      $res = $this->db->query($sql); 
      if ($row=$res->fetchRow()) {
	return $row['value'];
      }
      break;
    default:

      $sql=sprintf("select %s as value  from product where id=%d",addslashes($key),$this->id);
      //      print $key;
      $res = $this->db->query($sql); 
      if ($row=$res->fetchRow()) {
	return $row['value'];
      }
    }
    return false;
    

  }



  function get($item='',$data=false){
    $key=strtolower($item);
    if(isset($this->data[$key]))
      return $this->data[$key];
    
    


    switch($item){
    case('formated price'):
      return money($this->data['product price']);
      break;
    case('formated unitary rrp'):
      return money($this->data['product unitary rrp']);
      break;
    case('xhtml short description'):
      global $myconf;
      $desc='';
      if($this->get('Product Units per Case')>1){
	$desc=number($this->get('Product Units per Case')).'x ';
	}
      $desc.=' <span class="prod_sdesc">'.$this->get('Product Name').'</span>';
      if($this->get('Product Price')>0){
	$desc.=' ('.money($this->get('Product Units per Case')).')';
	}
      
      return _trim($desc);
    case('p2l_id'):
      $key=key($data);
      if(!$this->locations)
	$this->load('locations');
      foreach($this->locations['data'] as $_id=>$_loc){
	if($_loc[$key]==$data[$key])
	  return $_id;
      }
      return false;
    case('weblinks'):
      if(!$this->weblink)
	$this->load('weblinks');
      return $this->weblink;
      break;
    case('num_links'):
    case('num_weblinks'):
      if(!$this->weblink)
	$this->load('weblinks');
      return count($this->weblink);
      break;
    case('new_image'):
      if(isset($this->changing_img)  and isset($this->images[$this->changing_img]))
	return $this->images[$this->changing_img];
      else
	return false;
      break;
    case('tsall'):
    case('tsy'):
    case('tsq'):
    case('tsm'):
    case('tsw'):
    case('tsoall'):
    case('tsoy'):
    case('tsoq'):
    case('tsom'):
    case('tsow'):
    case('awtsall'):
    case('awtsy'):
    case('awtsq'):
    case('awtsm'):
    case('awtsoall'):
    case('awtsoy'):
    case('awtsoq'):
    case('awtsom'):
   //    if(!isset($this->data['sales'][$item]))
// 	$this->get('sales');


//       return $this->data['sales'][$item];
      break;
    case('sales'):
    //   $this->data['sales']=array();
//       $sql=sprintf("select * from sales  where tipo='prod' and tipo_id=%d",$this->id);
//       if($result =& $this->db->query($sql)){
// 	$this->data['sales']=$result->fetchRow();
//       }else{
// 	$this->load('sales');
// 	$this->save('sales');
	
//       }
      break;
    case('img_caption'):
      return $this->images[$this->changing_img]['caption'];
      break;
    case('img_new'):
      return $this->images[0];
      break;
    case('vol'):
      $this->data['vol']=volumen($this->data['dim_tipo'],$this->data['dim']);
      break;
    case('ovol'):
      $this->data['ovol']=volumen($this->data['odim_tipo'],$this->data['odim']);
      break;
    case('a_dim'):
      if($this->data['dim']!='')
	$a_dim=array($this->data['dim']);
      split('x',$this->data['dim']);
    case('mysql_first_date'):
      return  date("Y-m-d",strtotime("@".$this->data['first_date']));;
      break;
    case('first_date'):
      return $this->data['dates']['first_date'];
      break;
    case('weeks'):
      if(!isset( $this->data['weeks'])){

	if(is_numeric($this->data['first_date'])){
	  $date1=date('d-m-Y',strtotime('@'.$this->data['first_date']));
	  $day1=date('N')-1;
	  $date2=date('d-m-Y');
	  $days=datediff('d',$date1,$date2);
	  $weeks=number_weeks($days,$day1);
	}else
	  $weeks=0;
	$this->data['weeks']=$weeks;
      }

      return $this->data['weeks'];
      
      break;
    case('num_suppliers'):
    case('number_of_suppliers'):
      if(!$this->supplier)
	$this->load('suppliers');
      return  count($this->supplier);
    case('num_pics'):
    case('num_images'):
      if(!$this->images)
	$this->load('images');
      return count($this->images);
    case('dimension'):
      global $_shape;
      if($this->data['dim']!='')
	return $_shape[$this->data['dim_tipo']]." (".$this->data['dim'].")".$this->dim_units;
      else
	return '';
      break;
    case('odimension'):
      global $_shape;
      if($this->data['odim']!='')
	return $_shape[$this->data['odim_tipo']]." (".$this->data['odim'].")".$this->dim_units;
      else
	return '';
      break;  
    case('max_units_per_location'):
      $p2l_id=false;
      $_key=key($data);
      if($_key=='id'){
	$p2l_id=$data[$_key];
      }
      if(isset($this->locations['data'][$p2l_id]['max_units']))
	if($this->locations['data'][$p2l_id]['max_units']=='')
	  return _('Not set');
	else
	  return $this->locations['data'][$p2l_id]['max_units'];
      else
	return false;
      break;
    case('pl2_id'):
      if(!$this->locations)
	$this->load('locations');
      $_key=key($data);
      if($_key=='id'){
	foreach($this->locations['data'] as $p2l_id=>$loc_data){
	  // print "$p2l_id *******8\n";
	  if($loc_data['location_id']==$data[$_key])
	    return $p2l_id;
	}
      }
      return false;
      break;
    default:

      if(isset($this->data[$item]))
	return $this->data[$item];
      elseif(isset($this->$item))
	return $this->$item;
      else 
	return false;
    }

  }

 



//   function update_location($data){
//     switch($data['tipo']){
//     case('link'):
//       $user_id=$data['user_id'];
//       $product_to_link_id=$data['product_id'];
//       $date='NOW()';
//       $this->load('locations');
//       if($product_to_link_id==$this->id)
// 	return array(false,_('Nothing to change '));
//       $link_product=new Product($product_to_link_id);
//       $link_product->load('locations');
//       if(!$link_product->id)
// 	return array('ok'=>false,'msg'=>_('Product to be linked do not exist'));

//       if($link_product->get('units_tipo_id')!=$this->get('units_tipo_id'))
// 	return array('ok'=>false,'msg'=>_('Product to be links has dirent units type'));

      



//       $_parent=new Product($this->get('location_parent_id'));
//       $_parent->load('locations');
      



//       if($link_product->data['units']>=$_parent->data['units']){
// 	$this_parent=true;
// 	$parent=$_parent;
// 	$child=$link_product;

//       }else{
// 	$this_parent=false;
// 	$child=$_parent;
// 	$parent=$link_product;

//       }
//       //	$old_value=$child->data['location_parent_id'];


//       //print "chilsd id:".$child->id."  ".$child->data['code']." locs:".count($child->locations['data'])."\n";
//       //print " parent id: ".$parent->id."   ".$parent->data['code']." locs:".count($parent->locations['data'])."\n";

//       foreach($child->locations['data'] as $_p2l_id=>$_location_data){
// 	$_stock_units=$_location_data['stock_units'];

// 	print "locid ".$_location_data['location_id'];
// 	print_r($parent->locations['data']);
       
// 	if($pl2_id=$parent->get('p2l_id',array('location_id'=>$_location_data['location_id']))){
	  
// 	}else{

// 	  $data=array(
// 		      //    'product_id'=>$product_id,
// 		      'location_name'=>$_location_data['name'],
// 		      'is_primary'=>0,
// 		      'user_id'=>0,
// 		      'can_pick'=>0,
// 		      'tipo'=>'associate_location'
// 		      );
// 	  //	  print_r($data);

// 	  $res=$parent->update_location($data);
// 	  //print_r($res);
// 	  //exit;
// 	  $pl2_id=$parent->new_location_p2l_id;
// 	}


// 	if($_stock_units>0){
// 	  $data=array(
// 		      'p2l_id'=>$_p2l_id,
// 		      'qty'=>0,
// 		      'msg'=>_('Stock transfer to master product  arfer linking'),
// 		      'user_id'=>0,
// 		      'tipo'=>'change_qty'
// 		      );
// 	  $child->update_location($data);
	    
// 	  $data=array(
// 		      'p2l_id'=>$pl2_id,
// 		      'qty'=>$_location_data['stock_units']+$parent->locations['data'][$pl2_id]['stock_units'],
// 		      'msg'=>_('Adding stock from dependant product arfer linking'),
// 		      'user_id'=>0,
// 		      'tipo'=>'change_qty'
// 		      );
// 	  $parent->update_location($data);
// 	}
	  
// 	$data=array(

// 		    'p2l_id'=>$_p2l_id,
// 		    'user_id'=>0,
// 		    'tipo'=>'desassociate_location',
// 		    'msg'=>_('Linking locations to a master product')
// 		    );
// 	$child->update_location($data);
//       }
      

      
//       if(!$this_parent){
// 	// this has a new parent
// 	$old_value=$this->data['location_parent_id'];
// 	$sql=sprintf("update product set location_parent_id=%d where id=%d ",$link_product->data['location_parent_id'],$this->id);
// 	$this->save('has_parent');
// 	$link_product->save('has_child');
// 	//	print $sql;
// 	mysql_query($sql);
// 	$this->set_stock();
	
// 	$note=$this->data['code']." "._('is linked to')." ".$link_product->data['code'];
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',%d,%s)"
// 		     ,$date,$this->id,$user_id,$old_value ,$link_product->id,prepare_mysql($note)); 
// 	mysql_query($sql);
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',%d,%s)"
// 		     ,$date,$link_product->id,$user_id,$old_value ,$link_product->id,prepare_mysql($note)); 
// 	mysql_query($sql);
// 	return array(
// 		     'ok'=>true,
// 		     'master_id'=>$link_product->id
// 		     );
    
//       }else{
// 	// THIS IS THE PARENT

// 	$this->save('has_child');
// 	$link_product->save('has_parent');
      
// 	$note=$link_product->data['code']." "._('is linked to')." ".$this->data['code'];
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',%d,%s)"
// 		     ,$date,$link_product->id,$user_id,$link_product->data['location_parent_id'] ,$this->id,prepare_mysql($note)); 
// 	mysql_query($sql);
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',%d,%s)"
// 		     ,$date,$this->id,$user_id,$link_product->data['location_parent_id'] ,$this->id,prepare_mysql($note)); 
// 	mysql_query($sql);


// 	$link_product->load('same_products');
// 	foreach($link_product->same_products as $key=>$value){
// 	  $_tmp=new Product($key);
// 	  $old_value=$_tmp->data['location_parent_id'];
// 	  $note=$link_product->same_products[$key]['code']." "._('is linked to')." ".$this->data['code'];
// 	  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',%d,%s)"
// 		       ,$date,$key,$user_id,$old_value ,$this->id,prepare_mysql($note)); 
// 	  mysql_query($sql);
// 	  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',%d,%s)"
// 		       ,$date,$this->id,$user_id,$old_value ,$this->id,prepare_mysql($note)); 
// 	  mysql_query($sql); 
	    
// 	}

// 	$sql=sprintf("update product set location_parent_id=%d where location_parent_id=%d or    id=%d   ",$this->data['location_parent_id'],$link_product->id,$link_product->id);
// 	//	print $sql;
// 	mysql_query($sql);
// 	$this->set_stock();
// 	return array(
// 		     'ok'=>true,
// 		     'master_id'=>$this->id
// 		     );
//       }

	
	
    
	

    
      
    
      
//       break;

//     case('unlink'):
//       $user_id=$data['user_id'];
//       $date='NOW()';
      
//       $old_value=$this->data['location_parent_id'];
//       $this->load('same_products');
//       $this->load('locations');
      
//       if($this->locations['is_parent']){
// 	// unlink the children
// 	foreach($this->same_products as $key=>$value){
// 	  $sql=sprintf("update product set location_parent_id=%d where id=%d",$key,$key);
// 	  mysql_query($sql);
// 	  $note=_('Product unlinked from')." ".$this->data['code'];
// 	  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'PLO',NULL,'CHG',%s,'%d','%d')",$date,$key,$user_id,$this->id,$key,prepare_mysql($note)); 
// 	  mysql_query($sql);
// 	}
// 	return array(true);	
//       }else if($old_value!=$this->id){
	
	
// 	$sql=sprintf("update product set location_parent_id=%d where id=%d",$this->id,$this->id);
	
// 	mysql_query($sql);
// 	$note=_('Product unlinked from')." ".$this->same_products[$old_value]['code'];
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'PLO',NULL,'CHG',%s,'%d','%d')",$date,$this->id,$user_id,$old_value,$this->id,prepare_mysql($note)); 
// 	mysql_query($sql);
// 	return array(true);	
//       }
//       return array(false,_('Nothing to change'));	
//       break;
//     case('set_picking_rank'):
      
//       // print_r($data);
//       $id=$data['product2location_id'];
//       $rank=$data['rank'];
//       $user_id=$data['user_id'];
//       $date='NOW()';
//       $history=(isset($data['no_history']) and  $data['no_history']?false:true);

//       $sql=sprintf("select picking_rank,product_id,location_id from product2location  where id=%d",$id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$location_id=$row['location_id'];
// 	$old_rank=$row['picking_rank'];
//       }else
// 	return array(false,_('No such location'));
//       $this->load('locations');
//       $location_data=$this->get('locations');

//       if(preg_match('/^\+/',$rank)){
// 	$change=preg_replace('/^\+/','',$rank);
// 	if(!is_numeric($change))
// 	  return array(false,_('Wrong new rank'));
// 	if($old_rank=='')
// 	  $rank=$location_data['num_picking_areas']+$change;
// 	else
// 	  $rank=$old_rank+$change;

//       }else if(preg_match('/^\-/',$rank)){
// 	$change=preg_replace('/^\-/','',$rank);
// 	if(!is_numeric($change))
// 	  return array(false,_('Wrong new rank'));
// 	if($old_rank=='')
// 	  $rank=$location_data['num_picking_areas']-$change-1;
// 	else
// 	  $rank=$old_rank-$change;
	
// 	if($rank<1)
// 	  $rank=1;
//       }


//       if(!is_numeric($rank))
// 	return array(false,_('The picking prefrerence should be a positive interger')); 
//       if($rank>$location_data['num_picking_areas'] or $rank<0)
// 	$new_rank=$location_data['num_picking_areas']+1;
//       else
// 	$new_rank=$rank;
      
      
//       if($rank==0)
// 	$sql=sprintf("update product2location  set picking_rank=NULL where id=%d",$id);// products con not be picked from this location
//       else
// 	$sql=sprintf("update product2location  set picking_rank=%d where id=%d",$new_rank,$id); 
//       //  print $sql;
//       mysql_query($sql);
      
//       $sql=sprintf("select id ,picking_rank from product2location where product_id=%d and id!=%d order by picking_rank",$this->id,$id);
      
//       $result =& $this->db->query($sql);
//       $_rank=1;
//       while($row=$result->fetchRow()){
// 	if($_rank==$new_rank)
// 	  $_rank++;
	
// 	if(is_numeric($row['picking_rank'])){
// 	  $sql=sprintf("update product2location  set picking_rank=%d where id=%d",$_rank,$row['id']); 
// 	  mysql_query($sql);
// 	  $_rank++;
// 	}
	
//       }
      
//       if($history){
// 	$note=_('Picking rank for').' '.$this->get('code').' '._('changed to')." ".$rank;
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'LOC',%d,'CHGRANK',%s,'%d','%d',%s)",$date,$this->id,$location_id,$user_id,(is_numeric($rank_old)?$rank_old:0),$rank,prepare_mysql($note)); 
// 	mysql_query($sql);
//       }
      
//       $this->load('locations');
//       $locations_data=$this->get('locations');
//       return array(true,$locations_data);	
      
      
    
//       break;
//     case('change_qty'):

   

//       $id=$data['p2l_id'];
//       $user_id=$data['user_id'];

//       $qty=$data['qty'];
//       $msg=$data['msg'];
//       $date='NOW()';
//       if(!is_numeric($qty) )
// 	return array(false,_('Wrong stock value'));




//       $sql=sprintf("select stock,picking_rank,product_id,location_id,name from product2location  left join location on (location.id=location_id) where product2location.id=%d",$id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	if($row['product_id']!=$this->id)
// 	  return array(false,_('This location is no associated with the product'));
// 	$location_id=$row['location_id'];
// 	$old_qty=$row['stock'];
// 	$location_name=$row['name'];
// 	$change=$qty-$old_qty;
//       }else
// 	return array(false,_('This location is no associated with the product'));

//       if($qty<0 and $location_id!=2)
// 	return array(false,_('Stock can not be negative'));


//       if($change==0){
// 	$note=_('Audit').', '.number($qty).' '.ngettext('outer','outers',$change).' '._('in').' '.$location_name.' '.$msg;
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'AUD',%d,%s,'%s','%s')",$date,$this->id,$location_id,$user_id,prepare_mysql($note),$old_qty, $qty); 
// 	mysql_query($sql);
//       }else{
// 	$sql=sprintf("update product2location set stock=%.4f where id=%d",$qty,$id); 
// 	mysql_query($sql);
// 	$this->set_stock();
// 	$note=_('Audit').', '.number($qty).' '.ngettext('outer','outers',$change).' '._('in').' '.$location_name.' ('.($change>0?'+':'').number($change).')'.' '.$msg;
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'AUD',%d,%s,'%s','%s')",$date,$this->id,$location_id,$user_id,prepare_mysql($note),$old_qty, $qty); 
// 	mysql_query($sql);
//       }
//       $this->load(array('locations'));
//       $locations_data=$this->get('locations');
//       return array(true,$locations_data,$this->data['stock']);
//       break;

//     case('change_location'):
//       $id=$data['p2l_id'];
//       $user_id=$data['user_id'];
//       $new_location_name=stripslashes($data['new_location_name']);
//       $msg=$data['msg'];
//       $date='NOW()';
      

//       $sql=sprintf("select id from location  where name=%s",prepare_mysql($new_location_name)); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$new_location_id=$row['id'];
	
//       }else
// 	return array(false,_('This location do not exist'));
      
		   
//       $sql=sprintf("select picking_rank,product_id,location_id,name as location_name from product2location  left join location on location.id=location_id  where product2location.id=%d",$id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	if($row['product_id']!=$this->id)
// 	  return array(false,_('This location is no associated with the product'));
// 	if($row['location_id']==$new_location_id)
// 	  return array(false,_('Nothing to change'));
// 	$old_location_id=$row['location_id'];
// 	$old_location_name=$row['location_name'];
//       }else{
// 	return array(false,_('This location is no associated with the product'));
//       }  
      
      
		   
//       $sql=sprintf("update product2location set location_id=%d where id=%d",$new_location_id,$id); 
//       mysql_query($sql);
//       $this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>999999999,'user_id'=>$user_id,'no_history'=>true));
      
//       if($old_location_id==1)
// 	$note=_('Unknown location has been identified as').' '.$new_location_name;
//       else
// 	$note=$new_location_name.' '._('was wrongly identified as').' '.$old_location_name.' ('._('Corrected').')'.($msg!=''?'; '.stripslashes($msg):'');
      
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'L2P',%d,'CHG',%d,'%d','%d',%s)"
// 		   ,$date,$this->id,$id,$user_id,$old_location_id, $new_location_id,prepare_mysql($note)); 
//       //      return array(false,$sql);
//       mysql_query($sql);
      
//       $this->load(array('locations'));
//       $locations_data=$this->get('locations');
//       return array(true,$locations_data,$new_location_id);
//       break;


//     case('swap_picking'):
//       $id=$data['p2l_id'];
//       $user_id=$data['user_id'];

//       $action=$data['action'];
//       $date='NOW()';

//       $sql=sprintf("select name,picking_rank,product_id,location_id from product2location  left join location on (location.id=location_id) where product2location.id=%d",$id); 
//       //print $sql;
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	if($row['product_id']!=$this->id)
// 	  return array(false,_('This location is no associated with the product'));
// 	if($action==1 and is_numeric($row['picking_rank'])  or  $action==0 and !is_numeric($row['picking_rank'])  )
// 	  return array(false,_('Nothing to change'));
// 	$location_id=$row['location_id'];
// 	$location_name=$row['name'];
//       }else
// 	return array(false,_('This location is no associated with the product'));
//       // del
//       $this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>($action?999999999:0),'user_id'=>$user_id,'no_history'=>true));
      
//       if($action==1)
// 	$note=_('Products now can be picked from').' '.$location_name;
//       else
// 	$note=_('Products can no longer be picked from').' '.$location_name;
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'PCK',%d,%s,'%s','%s')",$date,$this->id,$location_id,$user_id,prepare_mysql($note),($row['picking_rank']==''?0:1),$action); 

//       mysql_query($sql);
	
//       $this->load('locations');
//       $locations_data=$this->get('locations');
//       return array(true,$locations_data);
//       break;

//     case('delete_all'):

//       $sql=sprintf("delete from product2location  where product_id=%d",$this->id); 
//       mysql_query($sql);
//       break;

//     case('desassociate_location'):
//       $id=$data['p2l_id'];

//       $sql=sprintf("select location.name,code,product2location.stock,product_id,location_id from product2location  left join product on (product.id=product_id) left join location on (location_id=location.id) where product2location.id=%d",$id); 
//       //print $sql;
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	if($row['product_id']!=$this->id)
// 	  return array(false,_('This location is no associated with the product'));
// 	if($row['stock']>0 and $row['location_id']!=1)
// 	  return array(false,_('There is still products in the location'));
// 	$stock=$row['stock'];
// 	$location_id=$row['location_id'];
// 	$product_code=$row['code'];
// 	$location_name=$row['name'];
//       }else
// 	return array(false,_('This location is no associated with the product'));
//       // del




//       $this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>0,'user_id'=>'','no_history'=>true));
      
//       // procced to delete
//       $sql=sprintf("delete from product2location  where id=%d",$id); 

//       mysql_query($sql);
      
//       if(!isset($data['no_history'])){
// 	$user_id=$data['user_id'];
// 	$msg=$data['msg'];
// 	$date='NOW()';
	
	
// 	if($location_id==1){
// 	  if($stock>0){
// 	    $note=number($stock)." "._('outers lost, its location was never identificated;').' '.stripslashes($msg);
// 	    $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'P2L',%d,'STK',%d,%s,'%s','%s')",$date,$this->id,$id,$user_id,prepare_mysql($note),$stock,0); 
// 	    mysql_query($sql);
// 	  }
// 	}
// 	else{
// 	  $note=$product_code." "._('no longer located on')." $location_name";
// 	  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note) values (%s,'PROD',%d,'LOC',%d,'DEL',%d,%s)",$date,$this->id,$location_id,$user_id,prepare_mysql($note)); 
// 	  mysql_query($sql);
// 	}
//       }
//       $this->set_stock();
//       $this->load('locations');
//       $locations_data=$this->get('locations');
//       return array(true,$locations_data);
//       break;

//     case('associate_location'):
//       $location_name=$data['location_name'];
//       $can_pick=$data['can_pick'];
//       $is_primary=$data['is_primary'];
//       $user_id=$data['user_id'];
//       $date='NOW()';
//       $sql=sprintf("select id,tipo,name from location  where name like %s",prepare_mysql($location_name)); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$location_id=$row['id'];
// 	$location_name=$row['name'];
// 	$location_tipo=$row['tipo'];
//       }else
// 	return array(false,_('No such location'));
      
//       $sql=sprintf("select id from product2location  where location_id=%d and product_id=%d",$location_id,$this->id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	return array(false,_('This product is already on this location'));
//       }
//       $sql=sprintf("select code from product where id=%d",$this->id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$product_code=$row['code'];
//       }
      


//       //    print_r($data);
//       //print "y $can_pick x $is_primary   ";
//       $sql=sprintf("insert into product2location  (product_id,location_id) values (%d,%d)",$this->id,$location_id); 
//       mysql_query($sql);
//       $id=mysql_insert_id();
//       $rank=0;
//       if($can_pick){
// 	if($is_primary)
// 	  $rank=1;
// 	else
// 	  $rank=99999999;
// 	//	print_r(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>$rank,'user_id'=>$user_id,'no_history'=>true));
// 	$this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>$rank,'user_id'=>$user_id,'no_history'=>true));
//       }
      
//       $this->load(array('locations'));
//       $locations_data=$this->get('locations');
      
      
//       if($locations_data['num_physical']>1)
// 	$note=$product_code." "._('is also located on')." $location_name" ;
//       else
// 	$note=$product_code." "._('is located on')." $location_name" ;



//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note) values (%s,'PROD',%d,'LOC',%d,'NEW',%d,'%s')",$date,$this->id,$location_id,$user_id,$note); 
//       mysql_query($sql);

	
//       $this->load(array('locations'));
//       $this->new_location_p2l_id=$id;
//       $locations_data=$this->get('locations');
//       return array(true,
// 		   $locations_data,
// 		   $location_id,
// 		   $location_name,
// 		   $location_tipo,
// 		   $rank,
// 		   ($rank==1?getOrdinal(1):getOrdinal($locations_data['num_physical'])) ,
// 		   $id,
// 		   $can_pick
// 		   );
  
//       break;
      

//     case('damaged_stock'):
//       $from_id=$data['from'];
//       $qty=$data['qty'];
//       $user_id=$data['user_id'];

//       $message=$data['message'];
//       $date='NOW()';
//       if($qty<=0)
// 	return array(false,_('Check the number of outers'));
//       // check of posible
//       $sql=sprintf("select location_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$from_id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$from_name=$row['name'];
// 	$from_qty=$row['stock'];
// 	$location_id=$row['location_id'];
//       }
//       if($qty>$from_qty)
// 	return array(false,_('Can not move so many outers'));
      
//       $sql=sprintf("update product2location set stock=%s where id=%d",$from_qty-$qty,$from_id); 

//       mysql_query($sql);
      
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note) values (%s,'PROD',%d,'LOC',%d,'DAM',%d,%s)",$date,$this->id,$location_id,$user_id,
// 		   prepare_mysql($qty.' '.ngettext('outer','outers',$qty).' '._('damaged').'; '.stripslashes($message))
// 		   ); 
//       mysql_query($sql);


//       $this->set_stock();
//       $this->load(array('locations'));
//       $locations_data=$this->get('locations');
//       return array(true,$locations_data);

//       break;
      
//     case('move_stock'):
//       $from_id=$data['from'];
//       $to_id=$data['to'];
//       $qty=$data['qty'];
//       $user_id=$data['user_id'];

//       $date='NOW()';
//       if($qty<=0)
// 	return array(false,_('Check the number of outers'));

//       // check of posible
//       $sql=sprintf("select location_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$from_id); 

//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$from_name=$row['name'];
// 	$from_qty=$row['stock'];	
// 	$from_location_id=$row['location_id'];
//       }
//       if($qty>$from_qty)
// 	return array(false,_('Can not move so many outers'));
      

//       $sql=sprintf("select location_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$to_id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$to_name=$row['name'];
// 	$to_qty=$row['stock'];
// 	$to_location_id=$row['location_id'];
//       }

//       $sql=sprintf("update product2location set stock=%s where id=%d",$from_qty-$qty,$from_id); 
//       // print "$sql";
//       mysql_query($sql);
//       $sql=sprintf("update product2location set stock=%s where id=%d",$to_qty+$qty,$to_id); 
//       // print "$sql";
//       mysql_query($sql);
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOF',%d,'%s',%d,%d)"
// 		   ,$date
// 		   ,$this->id
// 		   ,$from_location_id
// 		   ,$user_id
// 		   ,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name
// 		   ,$from_qty
// 		   ,$from_qty-$qty
// 		   ); 

//       mysql_query($sql);
      
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOT',%d,'%s',%d,%d)",$date,$this->id,$to_location_id,$user_id,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name,$to_qty,$to_qty+$qty); 
//       mysql_query($sql);
      
      
//       //   return array(false,$sql);
      
//       $this->load(array('locations'));
//       $locations_data=$this->get('locations');

//       return array(true,$locations_data);
//       break;
      
//     case('move_stock_to'):
//       $from_id=$data['from_id'];
//       $to_name=stripslashes($data['to_name']);
//       $qty=$data['qty'];
//       $user_id=$data['user_id'];
//       $date='NOW()';
      
//       if($qty<=0)
// 	return array(false,_('Check the number of outers'));

      
//       $sql=sprintf("select location_id,product_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$from_id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	if($row['product_id']!=$this->id)
// 	  return array(false,_('There this product is not in this location'));
// 	$from_name=$row['name'];
// 	$from_qty=$row['stock'];	
// 	$from_location_id=$row['location_id'];
//       }
//       if($qty>$from_qty)
// 	return array(false,_('Can not move so many outers'));
      
      
//       $sql=sprintf("select  stock,product2location.id from product2location left join location on (location.id=location_id) where product_id=%d and location.name=%s",$this->id,prepare_mysql($to_name)); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$to_id=$row['id'];

//       }else{
// 	// associate to new location
// 	$new_loc_data=array(
// 			    'location_name'=>$to_name,
// 			    'can_pick'=>true,
// 			    'is_primary'=>false,
// 			    'user_id'=>$user_id,
// 			    'tipo'=>'associate_location'
// 			    );
// 	$res=$this->update_location($new_loc_data);
// 	if(!$res[0])
// 	  return array(false,$res[1]);
// 	$sql=sprintf("select  stock,product2location.id from product2location left join location on (location.id=location_id) where product_id=%d and location.name=%s",$this->id,prepare_mysql($to_name)); 

// 	$result2 =& $this->db->query($sql);
// 	if($row2=$result2->fetchRow()){
// 	  $to_id=$row2['id'];
// 	}else
// 	  return array(false,_('Could not associate new location'));


//       }



//       $sql=sprintf("select stock,location_id,location.name,product2location.id from product2location left join location on (location.id=location_id) where product2location.id=%d",$to_id); 

//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$to_name=$row['name'];
// 	$to_id=$row['id'];
// 	$to_qty=$row['stock'];
// 	$to_location_id=$row['location_id'];
//       }else
// 	return array(false,_('Error on  new location'));



//       $sql=sprintf("update product2location set stock=%s where id=%d",$from_qty-$qty,$from_id); 
//       // print "$sql";
//       mysql_query($sql);
//       $sql=sprintf("update product2location set stock=%s where id=%d",$to_qty+$qty,$to_id); 
//       // print "$sql";
//       mysql_query($sql);
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOF',%d,'%s',%d,%d)",$date,$this->id,$from_location_id,$user_id,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name,$from_qty,$from_qty-$qty); 
//       mysql_query($sql);
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOT',%d,'%s',%d,%d)",$date,$this->id,$to_location_id,$user_id,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name,$to_qty,$to_qty+$qty); 
//       mysql_query($sql);
      


      
//       $this->load(array('locations'));
//       $locations_data=$this->get('locations');

//       return array(true,$locations_data);
//       break;





  


  function update($values,$args=''){
    $res=array();
    
    foreach($values as $data){

      $key=$data['key'];
      
      $value=$data['value'];
      $res[$key]=array('ok'=>false,'msg'=>'');
      
      switch($key){
	
      case('weblink'):
	if(!$this->weblink)
	  $this->load('weblinks');
	
	$value=_($value);
	$title=_($data['title']);
	$this->weblink[$value]=array('title'=>$title);
	$this->weblink_updated=$value;

	break;
	
      case('max_units_per_location'):
	$p2l_id=$data['p2l_id'];
	//	print $p2l_id;
	if(!is_numeric($p2l_id) or $p2l_id<=0){
	  $res[$key]['msg']=_('Wrong location id');
	  $res[$key]['ok']=false;
	  continue;
	}
	if(!$this->locations){
	  $this->load('locations');

	}
	//	print_r($this->locations['data']);
	if(!isset($this->locations['data'][$p2l_id])){
	  $res[$key]['msg']=_('Error: Location not assocated with product');
	  $res[$key]['ok']=false;
	  continue;
	}
	if(!is_numeric($value) or  $value<=0){
	  $value='';
	}

	if($this->locations['data'][$p2l_id]['max_units']==$value){
	  $res[$key]['msg']=_('Max units not changed (same values)');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->locations['data'][$p2l_id]['max_units']=$value;
	$this->location_to_update=$p2l_id;
	$res[$key]['msg']=_('Max units in location changed');
	$res[$key]['ok']=true;

	break;
      case('units'):
	if(!is_numeric($value)){
	  $res[$key]['msg']=_('Units per outer should be a number');
	  $res[$key]['ok']=false;
	  continue;
	}
	if($value<=0){
	  $res[$key]['msg']=_('Units per outer should be a positive number');
	  $res[$key]['ok']=false;
	  continue;
	}
	if($value==$this->data['units']){
	  $res[$key]['msg']=_('Units per outer no to changed');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->data['units']=$value;
	
	$res_oweight=$this->update(array(array('key'=>'oweight','value'=>$data['oweight'])));
	$res_odim=$this->update(array(array('key'=>'odim','value'=>$data['odim'])));
	$res_price=$this->update(array(array('key'=>'price','value'=>$data['price'])));
	
	$res['oweight']=$res_oweight['oweight'];
	$res['odim']=$res_odim['odim'];
	$res['price']=$res_price['price'];
	$res[$key]['ok']=true;
	continue;
	
	break;
      case('supplier_new'):
	if(!is_numeric($value) or $value<1){
	  $res[$key]['msg']=_('Wrong supplier indentifier');
	  $res[$key]['ok']=false;
	  continue;
	}
	
	if(isset($this->supplier[$value])){
	  $res[$key]['msg']=_('Supplier already associated with product');
	  $res[$key]['ok']=false;
	  continue;
	}

	$supplier=new Supplier($value);
	if(!$supplier->id){
	  $res[$key]['msg']=_('Supplier not found');
	  $res[$key]['ok']=false;
	  continue;
	}
	if(!$this->supplier){
	  $this->load('suppliers');
	}
	$this->supplier_to_update=$value;
	$this->supplier_to_update_name=$supplier->data['code'];
	$this->new_supplier=$value;
	if(!is_numeric($data['sup_cost'])){
	  $price='';
	  $this->supplier_cost_changed=false;
	}else{
	  $price=$data['sup_cost'];
	  $this->old_supplier_cost='';
	  $this->supplier_cost_changed=true;
	}
	if(preg_match('/^\s*$/',$data['sup_code']) ){
	  $code='';
	  $this->supplier_code_changed=false;
	}else{
	  $code=$data['sup_code'];
	  $this->old_supplier_code='';
	  $this->supplier_code_changed=true;
	}


	$this->supplier[$value]=array(
				      'id'=>$supplier->id,
				      'name'=>$supplier->data['name'],
				      'price'=>$price,
				      'formated_price'=>money($price),
				      'supplier_product_code'=>$code,
				      'code'=>$supplier->data['code']
				      );
	
	
	

	$res[$key]['ok']=true;
	break;
      case('supplier'):
	if(!$this->supplier){
	  $this->load('suppliers');
	}
	if(!$this->supplier[$value]){
	  $res[$key]['msg']=_('Supplier not associated with the product');
	  $res[$key]['ok']=false;
	  continue;
	}
	
	if($data['sup_cost']=='' and $this->supplier[$value]['price']=='')
	  $this->supplier_cost_changed=false;
	else{

	  $data['sup_cost']= unformat_money($data['sup_cost']);
	  
	  if(!is_numeric($data['sup_cost'])){
	    $res[$key]['msg']=_("Product cost is not a number")." ";
	    $res[$key]['ok']=false;
	    continue;
	  }
	}
	if(preg_match('/^\s*$/',$data['sup_code'])){
	  $res[$key]['msg']=_('Supplier product code should have al least one characher');
	  $res[$key]['ok']=false;
	  continue;
	}
		
	$this->supplier_to_update_name= $this->supplier[$value]['code'];
	$this->supplier_to_update=$value;
	if($this->supplier[$value]['price']!=$data['sup_cost']){
	  $this->supplier_cost_changed=true;
	  $this->old_supplier_cost=$this->supplier[$value]['price'];
	  $this->supplier[$value]['price']=$data['sup_cost'];
	}else
	  $this->supplier_cost_changed=false;
	
	if($this->supplier[$value]['supplier_product_code']!=$data['sup_code']){
	  $this->old_supplier_code=$this->supplier[$value]['supplier_product_code'];
	  $this->supplier[$value]['supplier_product_code']=$data['sup_code'];
	  $this->supplier_code_changed=true;
	}else
	  $this->supplier_code_changed=false;
	$res[$key]['ok']=true;
	
	break;
      case('supplier_delete'):
	
	if(!$this->supplier){
	  $this->load('suppliers');
	}
	if(!$this->supplier[$value]){
	  $res[$key]['msg']=_('Supplier not associated with the product');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->supplier_to_delete_name=$this->supplier[$value]['code'];
	unset($this->supplier[$value]);
	$this->supplier_to_delete=$value;
	$res[$key]['ok']=true;

	break;


	
      case('img_set_principal'):
	if(!$this->images)
	  $this->load('images');
	$this->changing_img=$value;
	if(!isset($this->images[$this->changing_img])){
	  $res[$key]['msg']=_('Image not found');
	  $res[$key]['ok']=false;
	  continue;

	}

	if($this->images[$this->changing_img]['principal']){
	  $res[$key]['msg']=_('Image is already the principal');
	  $res[$key]['ok']=false;
	  continue;

	}
	  
	foreach($this->images as $_key => $value){
	  $this->images[$_key]['principal']=false;
	}
	$this->data['principal_image']=$this->changing_img;
	$this->images[$this->changing_img]['principal']=true;
	$res[$key]['ok']=true;
	break;

      case('img_delete'):
	if(!$this->images)
	  $this->load('images');
	$this->img_to_delete=$value;
	if(!isset($this->images[$this->img_to_delete])){
	  $res[$key]['msg']=_('Image not found');
	  $res[$key]['ok']=false;
	  continue;

	}
	$this->new_principal_img='';
	if($this->images[$this->img_to_delete]['principal'] and count($this->images)>1  ){
	 
	  foreach($this->images as $_key=>$_value){
	    if($_key!=$this->img_to_delete){
	      $_data[]=array(
			     'key'=>'img_set_principal',
			     'value'=>$_key,
			     );
	      $_res=$this->update($_data);
	      // print_r($_res);
	      $this->save('img_set_principal',
			  array(
				'user_id'=>0
				)
			  );
	      $this->new_principal_img=$_key;
	      break;
	    }
	  }
	}
	  
	unset($this->images[$this->img_to_delete]);
	
	
	$res[$key]['ok']=true;
	

	break;
      case('img_new'):
	if(!$this->images)
	  $this->load('images');
	$checksum=md5_file($value);
	$same_as_other=false;
	foreach($this->images as $_key=>$_value){
	  if($_value['checksum']==$checksum){
	    $same_as_other=true;
	    $same_as=$_value['name'];
	    break;
	  }
	    
	}

	if($same_as_other){
	  $res[$key]['msg']=_('Image already uploaded')." (".$same_as.")";
	  $res[$key]['ok']=false;
	  unlink($value);
	  continue;

	}

	$code=$this->get('code');
	$target_path = $value;
	
	$im = @imagecreatefromjpeg($target_path);
	if ($im) {  
	  $images=$this->data['image_index'];
	  $this->data['image_index']=$images+1;

	  $format='jpg';
	  $name=$code.'_'.$images;
	  $this->images[0]=array(
				 'width' => imagesx($im),
				 'height' => imagesy($im),
				 'size'=>$s=filesize($target_path),
				 'checksum'=>$checksum,
				 'principal'=>($images==0?true:false),
				 'caption'=>'',
				 'name'=>$name,
				 'tmp_filename'=>$target_path,
				 'format'=>$format,
				 'tb'=>$this->image_path.'tb/'.$name.'_tb.'.$format,
				 'med'=>$this->image_path.'med/'.$name.'_med.'.$format,
				 'orig'=>$this->image_path.'original/'.$name.'_orig.'.$format,
				 

				 );
	}
 
	$res[$key]['ok']=true;
	 
	break;
      case('img_caption'):
	if(!$this->images)
	  $this->load('images');
	$image_id=$data['image_id'];
	//	print_r($data);

	if(!isset($this->images[$image_id])){
	  $res[$key]['msg']=_('Image not found');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->images[$image_id]['caption']=$value;
	$this->changing_img=$image_id;
	$res[$key]['ok']=true;
	break;
	
	//Must be numeric
      case('price'):
      case('weight'):
      case('oweight'):
	if($this->data[$key]==$value){

	  switch($key){
	  case('price'):
	    $same_msg=_('Price per outer has not change');
	    break;
	  case('weight'):
	    $same_msg=_('Weight per unit has not change');
	    break;
	  case('oweight'):
	    $same_msg=_('Weight per outer has not change');
	  }

	  
	  $res[$key]['msg']=$same_msg;
	  $res[$key]['ok']=false;
	  continue;
	}
	if(!is_numeric($value)){
	  switch($key){
	  case('price'):
	    $err_msg=_('Error: Price per outer must be a number');
	  case('weight'):
	    $err_msg=_('Error: Weight per unit must be a number');
	  case('oweight'):
	    $err_msg=_('Error: Weight per outer must be a number');
	  }

	  $res[$key]['msg']=$err_msg;
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->data[$key]=$value;
	$res[$key]['ok']=true;
	break;
      case('rrp'):
	//numeric can be null
	if($this->data[$key]==$value){
	  $res[$key]['msg']=_('Same values');
	  $res[$key]['ok']=false;
	  continue;
	}
	if(!(is_numeric($value) or $value=='' )){
	  $res[$key]['msg']=_('Value is not numeric');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->data[$key]=$value;
	//	print "RPP:".$this->data[$key]." ";
	$res[$key]['ok']=true;
	break;
	//Must be alpha & not null
      case('description'):
      case('sdescription'):
	if($this->data[$key]==$value){
	  $res[$key]['msg']=_('Same values');
	  $res[$key]['ok']=false;
	  continue;
	}
	if($value==''){
	  $res[$key]['msg']=_('Value Required');
	  $res[$key]['ok']=false;
	  continue;
	}
	if(!preg_match('/[a-z]/i',$value)){
	  $res[$key]['msg']=_('Not Valid Value');
	  $res[$key]['ok']=false;
	  continue;
	}
	
	$this->data[$key]=$value;
	$res[$key]['ok']=true;
	break;
            
      case('details'):
	if($this->data[$key]==$value){
	  $res[$key]['msg']=_('Same values');
	  $res[$key]['ok']=true;
	  continue;
	}$this->data[$key]=$value;
	$res[$key]['ok']=true;
	break;
      case('cat'):
	$cats=split($value);
	
	// delete all cats
	$sql=sprintf("delete from cat  where product_id=%d",$this->data['id']);
	mysql_query($sql);
	foreach($cats as $cat){
	  $sql=sprintf("insert into cat  (cat_id,product_id) values (%d,%d)",$cat,$this->data['id']);
	  mysql_query($sql);
	}
	$res[$key]=array('res'=>1,'new_value'=>join('-',$value));
	break;
      case('dim'):
      case('odim'):

	
	if(!preg_match('/^shape\d\_/',$value)){
	  $res[$key]=array('ok'=>false,'msg'=>_('Error: Dimension with wrong format'));
	  continue;
	}
	
	

	list($tipo,$dims)=preg_split('/_/',$value);
	$tipo=preg_replace('/^shape/','',$tipo);
	$_dims=preg_split('/x/',$dims);
	
	if($this->data[$key]==$dims and $this->data[$key.'_tipo']==$tipo ){
	  if($key=='dim')
	    $res[$key]=array('ok'=>false,'msg'=>_('Product volume not change'));
	  else
	    $res[$key]=array('ok'=>false,'msg'=>_('Outer volume not change'));
	  continue;
	}
	
	$this->data[$key]=$dims;
	$this->data[$key.'_tipo']=$tipo;
	


	$res[$key]=array('ok'=>true,'msg'=>'');
	break;
      case('web_status'):
	
	if($value=='onsale' or $value=='discontinued' or $value=='hidden' or $value=='offline' or $value=='outofstock'){
	  if($this->data['web_status']==$value){
	    $res[$key]['msg']=_('Nothing to change');
	    $res[$key]['ok']=false;
	    $res[$key]['same']=true;
	    continue;
	  }else{
	    $this->data['web_status']=$value;
	    $res[$key]['ok']=true;
	    $res[$key]['same']=false;
	    $res[$key]['msg']=_('Web status changed');
	    //
	    $this->data['sincro_db']=0;
	    $this->data['sincro_pages']=0;
	    

	  }

	  
	}else{
	  $res[$key]['msg']=_('Wrong web status value');
	  $res[$key]['ok']=false;
	  continue;

	}


	break;
      default:
	
	$res[$key]=array('ok'=>false,'msg'=>_('Unkwown key'));
      }

      if(preg_match('/save/',$args)){
       
	$this->save($key);
	
      }
    }
    return $res;
  }


  function save($key,$history_data=false){

    $msg='';
    switch($key){
    case('has_child'):
      $sql=sprintf("update product set has_child=1 where id=%d",$this->id);
      $this->db->exec($sql);
      break;
    case('has_child'):
      $sql=sprintf("update product set has_child=0 where id=%d",$this->id);
      $this->db->exec($sql);
      break;

    case('has_parent'):
      $sql=sprintf("update product set has_parent=1 where id=%d",$this->id);
      $this->db->exec($sql);
      break;
    case('has_no_parent'):
      $sql=sprintf("update product set has_parent=0 where id=%d",$this->id);
      $this->db->exec($sql);
      break;
    case('weblink'):
      
      if(!$old_data=$this->read('weblink',$this->weblink_updated)){
	$sql=sprintf("insert into product_webpages (product_id,link,title) values (%d,%s,%s)"
		     ,$this->id
		     ,prepare_mysql($this->weblink_updated)
		     ,prepare_mysql($this->weblink[$this->weblink_updated]['title'])
		     );
	//print "$sql\n";
	$this->db->exec($sql);
      }else{
	$sql=sprintf("update product_webpages set title=%s where id=%d "
		     ,prepare_mysql($this->weblink[$this->weblink_updated]['title'])
		     ,$old_data['id']
		     );
	$this->db->exec($sql);
      }

      break;
    case('max_units_per_location'):
      $old_value=$this->read($key,array('id'=>$this->location_to_update));
      $sql=sprintf("update  product2location set max_stock=%s where id=%d",prepare_mysql($this->locations['data'][$this->location_to_update]['max_units']),$this->location_to_update);
      // print $sql;
      $this->db->exec($sql);
      
      if(is_array($history_data)){
	$history_data['p2l_id']=$this->location_to_update;
	$history_data['location_id']=$this->locations['data'][$this->location_to_update]['location_id'];
	$history_data['loc_name']=$this->locations['data'][$this->location_to_update]['name'];
	$msg=$this->save_history($key,$old_value,$this->locations['data'][$this->location_to_update]['max_units'],$history_data);
      }

      break;
    case('supplier_new'):
      
      $sql=sprintf("insert into product2supplier (supplier_id,product_id) values (%d,%d)",$this->new_supplier,$this->id);
      $this->db->exec($sql);
      if(is_array($history_data)){
	$history_data['supplier_id']=$this->new_supplier;
	$msg=$this->save_history('supplier_new','',$this->supplier[$this->new_supplier]['code'],$history_data);
      }
      $msg.=$this->save('supplier');
     
      break;
    case('supplier'):

      if(isset($this->supplier_code_changed) and $this->supplier_code_changed){

	$code=$this->supplier[$this->supplier_to_update]['supplier_product_code'];
	$sql=sprintf("update product2supplier set sup_code=%s  where product_id=%d and supplier_id=%d",prepare_mysql($code),$this->id,$this->supplier_to_update);
	//	print $sql;
	$this->db->exec($sql);
	if(is_array($history_data)){
	  $history_data['supplier_id']=$this->supplier_to_update;
	  $history_data['supplier_name']=$this->supplier_to_update_name;
	  $msg=$this->save_history('supplier_code',$this->old_supplier_code,$code,$history_data);
	}
      }
      
      if(isset($this->supplier_cost_changed) and $this->supplier_cost_changed){
	$cost=$this->supplier[$this->supplier_to_update]['price'];
	$sql=sprintf("update product2supplier set   price=%.4f where product_id=%d and supplier_id=%d",$cost,$this->id,$this->supplier_to_update);
	$this->db->exec($sql);
	if(is_array($history_data)){
	  $history_data['supplier_id']=$this->supplier_to_update;
	  $history_data['supplier_name']=$this->supplier_to_update_name;
	  $msg=$this->save_history('supplier_cost',$this->old_supplier_cost,$cost,$history_data);
	}
      }

      
      
      break;

    case('supplier_delete'):

      $sql=sprintf("delete from product2supplier where product_id=%d and supplier_id=%d",$this->id,$this->supplier_to_delete);
      $this->db->exec($sql);
      if(is_array($history_data)){
	$history_data['supplier_id']=$this->supplier_to_delete;
 	$msg=$this->save_history($key,$this->supplier_to_delete_name,'',$history_data);
      }
      break;
    case('img_set_principal'):
      $old_image=$this->read('principal_image');
      $old_value=$old_image['name'];
      $sql="update image set principal=0 where product_id=".$this->id;
      $this->db->exec($sql);
      $sql=sprintf("update image set principal=1 where id=%d",$this->changing_img);
      $this->db->exec($sql);
      if(is_array($history_data)){
	$history_data['image_id']=$this->changing_img;
 	$msg=$this->save_history($key,$old_value,$this->images[$this->changing_img]['name'],$history_data);
      }
     
      break;
    case('img_delete'):
      $old_value=$this->read('image',$this->img_to_delete);


      if(is_file($old_value['tb'])) {
	unlink($old_value['tb']);
      }
      if(is_file($old_value['med'])) {
	unlink($old_value['med']);
      }
      if(is_file($old_value['orig'])) {
	unlink($old_value['orig']);
      }



      $sql=sprintf("delete from image where id=%d",$this->img_to_delete);
      //      print $sql;
      $this->db->exec($sql);
      if(is_array($history_data)){
	$history_data['image_id']=$old_value['id'];
	$msg=$this->save_history($key,$old_value['name'],'',$history_data);
      }
      
      break;
    case('img_new'):
      $old_value='';
      $value=$this->images[0];


      // 	if(move_uploaded_file($tmp_file, $target_path)) {
      $im = @imagecreatefromjpeg($value['tmp_filename']);
      if ($im) { 
	$w = imagesx($im);
	$h = imagesy($im);
	    
	if($h > 0) 
	  { 
	    $r = $w/$h;
	    imagejpeg($im,$this->image_path.'original/'.$value['name'].'_orig.jpg');
		
	    $med_maxh=130;
	    $med_maxw=190;
	    $tb_maxh=21;
	    $tb_maxw=30;


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
	    imagejpeg($im_med,$this->image_path.'med/'.$value['name'].'_med.jpg');
	    $im_tb = imagecreatetruecolor($tb_w, $tb_h);
	    imagecopyresampled($im_tb, $im, 0, 0, 0, 0, $tb_w, $tb_h, $w, $h);
	    imagejpeg($im_tb,$this->image_path.'tb/'.$value['name'].'_tb.jpg');
	     
	     


	  }
      }
      unlink($value['tmp_filename']);



      $sql=sprintf("insert into image  (name,product_id,width,height,size,checksum,principal) values (%s,%d,%d,%d,%d,%s,%d)"
		   ,prepare_mysql($value['name'])
		   ,$this->id
		   ,$value['width']
		   ,$value['height']
		   ,$value['size']
		   ,prepare_mysql($value['checksum'])
		   ,($value['principal']?1:0)
		   );
      $affected=& $this->db->exec($sql);
      if (PEAR::isError($affected)) {
	if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	  return array('ok'=>false,'msg'=>_('Error: Another product has the same code').'.');
	else
	  return array('ok'=>false,'msg'=>_('Unknwon Error').'.');
      }
      $image_id = $this->db->lastInsertID();

      $sql=sprintf("update product set image_index=%d where id=%d",$this->data['image_index'],$this->id);
      //  print $sql;
      $this->db->exec($sql);
      $this->changing_img=$image_id;
      $this->images= array_change_key_name( 0, $image_id,$this->images );
      $this->images[$image_id]['id']=$image_id;
      if(is_array($history_data)){
	$history_data['image_id']=$image_id;
	$msg=$this->save_history($key,'',$this->images[$image_id]['name'],$history_data);
      }

      break;
    case('img_caption'):
      $old_value=$this->read($key);
      $value=$this->get($key);
      $sql=sprintf("update image set caption=%s  where  id=%d"
 		   ,prepare_mysql($value),$this->changing_img);
      $this->db->exec($sql);

      if(is_array($history_data)){
	$history_data['image_id']=$this->changing_img;
	$msg=$this->save_history($key,$old_value,$this->get($key),$history_data);
      }

      break;
    case('first_date'):
      $old_value=$this->read($key);

      if(is_numeric($this->data['first_date'])){
	$date=date("Y-m-d H:i:s",strtotime("@".$this->data['first_date']));

	$sql=sprintf("update product set first_date=%s  where  id=%d"
		     ,prepare_mysql($date),$this->id);
	//	print "$sql\n";
	$this->db->exec($sql);
	
	if(is_array($history_data)){
	  $msg=$this->save_history($key,$old_value,$history_data);
	}

      }
      break;
    case('sales'):
      $sql=sprintf("select id from sales where tipo='prod' and tipo_id=%d",$this->id);
      $res = $this->db->query($sql); 
      if ($row=$res->fetchRow()) {
	$sales_id=$row['id'];
      }else{
	$sql=sprintf("insert into sales (tipo,tipo_id) values ('prod',%d)",$this->id);
	$this->db->exec($sql);
	$sales_id=$this->db->lastInsertID();
	
      }
      foreach($this->data['sales'] as $key=>$value){
	if(preg_match('/^aw/',$key)){
	  if(is_numeric($value))
	    $sql=sprintf("update sales set %s=%f where id=%d",$key,$value,$sales_id);
	  else
	    $sql=sprintf("update sales set %s=NULL where id=%d",$key,$sales_id);
	  $this->db->exec($sql);

	}
	if(preg_match('/^ts/',$key)){
	  $sql=sprintf("update sales set %s=%.2f where id=%d",$key,$value,$sales_id);
	  //	  print "$sql\n";
	  $this->db->exec($sql);
	}  
	

      }
      break;
    case('odim'):
    case('dim'):
      $old_value=$this->read($key.'ension');
      $dims=$this->data[$key];
      $dims_tipo=$this->data[$key.'_tipo'];
      $sql=sprintf("update product set %s=%s,%s=%s where id=%d",$key,prepare_mysql($dims),$key.'_tipo',prepare_mysql($dims_tipo),$this->id);
      $this->db->exec($sql);
      if(is_array($history_data)){
	$new_value=$this->get($key.'ension');
	$msg=$this->save_history($key,$old_value,$new_value,$history_data);
      }


      break;
    case('sincro_db'):
      $sql=sprintf("update product set sincro_db=1,nosincro_db_why=NULL where id=%d"
		   ,$this->id
		   );
      $this->db->exec($sql);
      //	print $sql;
      break;
    case('sincro_pages'):
      $old=$this->read('sincro_pages');
      $new=1;
     
      if($old!=$new){
	$sql=sprintf("update product set sincro_pages=1,nosincro_pages_why=NULL where id=%d"
		     ,$this->id
		     );
	// print $sql;
	$this->db->exec($sql);
	if(is_array($history_data)){
	  $old_why=$this->get('nosincro_pages_why');
	  $msg=$this->save_history($key,$old_why,'',$history_data);
	}
      }
      break;	
    case('web_status'):
      
      $sql=sprintf("update product set web_status=%s , sincro_pages=0,sincro_db=0,nosincro_db_why=%s,nosincro_db_why=%s where id=%d"
		   ,prepare_mysql($this->get($key))
		   ,prepare_mysql(_('Web status changes'))
		   ,prepare_mysql(_('Web status changes'))
		   ,$this->id
		   );
      //  print $sql;
      $this->db->exec($sql);
      
      break;
       
       

    default:

      $old_value=$this->read($key);
      //print $old_value." ".$this->data[$key]."\n";
      if($old_value!=$this->data[$key]){
 	$sql=sprintf("update product set %s=%s where id=%d",$key,prepare_mysql($this->get($key)),$this->id);
	//	print $sql;
	//	print $this->get($key);
 	$this->db->exec($sql);
      }

      if(is_array($history_data)){
 	$msg=$this->save_history($key,$old_value,$this->get($key),$history_data);
      }
      
      break; 
    }
    return $msg;

  }




  function save_history($key,$old,$new,$data){
    
    switch($key){
    case('sincro_pages'):
      $note=_('Web pages checked');
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='SYNP';
      $objeto_id='';
      $action='OK';
      break;
    case('max_units_per_location'):
      if(is_numeric($new))
	$note=_('Max units in location')." ".$data['loc_name']." "._('set to').' '.$new;
      else
	$note=_('Max units uncapped in location')." ".$data['loc_name'];
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='LOC';
      $objeto_id=$data['location_id'];
      $action='CHGMAX';
      break;
    case('units'):
      $note=_('Units per outer changed to').": ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='UNITS';
      $objeto_id='';
      $action='CHG';
      break;
    case('supplier_new'):
      $note=_('Supplier associated with this product').": ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='SUP';
      $objeto_id=$data['supplier_id'];
      $action='NEW';
      break;
    case('supplier_cost'):

      if(is_numeric($old)){
	$diff=$new-$old;
	$prefix='';
	if($diff>0){
	  $txt=_('Product cost ('.$data['supplier_name'].') incresed by')." ";
	  $prefix='+';
	}else{
	  $prefix='-';
	  $txt=_('Product cost ('.$data['supplier_name'].') decresed by')." ";
	}
      
	$per=percentage($diff,$old);
	$note=$txt.money($diff)." (".$per.") "._('to')." ".money($new).' '._('per').' '.$this->data['units_tipo_name'];
      }else
	$note=_('Product cost ('.$data['supplier_name'].') set to')." ".money($new).' '._('per').' '.$this->data['units_tipo_shortname'];
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='SUPCOST';
      $objeto_id=$data['supplier_id'];
      $action='CHG';
      break;
    case('supplier_code'):
      $note=_('Supplier ('.$data['supplier_name'].') product code set to')." ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='SUP';
      $objeto_id=$data['supplier_id'];
      $action='DEL';
      break;
    case('supplier_delete'):
      $note=_('Supplier')." ".$old." "._('no longer supplies this product');
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='SUP';
      $objeto_id=$data['supplier_id'];
      $action='DEL';
      break;
    case('odim'):
      $note=_('Product outer dimentions set to').": ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='ODIM';
      $objeto_id='';
      $action='CHG';
      break;
    case('dim'):
      $note=_('Product dimentions set to').": ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='DIM';
      $objeto_id='';
      $action='CHG';
      break;   
    case('oweight'):
      $note=_('Product weight set to').": ".number($new,3).' '._("Kg").' '._('per outer');
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='OWEIGTH';
      $objeto_id='';
      $action='CHG';
      break;

    case('weight'):
      $note=_('Product weight set to').": ".number($new,3).' '._("Kg").' '._('per').' '.$this->data['units_tipo_name'];
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='WEIGTH';
      $objeto_id='';
      $action='CHG';
      break;

    case('img_set_principal'):
      $note=_('New image marked as  principal').": ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='IMG';
      $objeto_id=$data['image_id'];
      $action='PRI';
      break;
     
    case('img_delete'):
      $note=_('Image deleted').": ".$old;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='IMG';
      $objeto_id=$data['image_id'];
      $action='DEL';
      break;
    case('img_new'):
      $note=_('New image')." ".$this->images[$data['image_id']]['name'];
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='IMG';
      $objeto_id=$data['image_id'];
      $action='NEW';
      break;
      

    case('img_caption'):
      if($new=='')
	$note=_('Caption deleted for image')." ".$this->images[$data['image_id']]['name'];
      else
	$note=_('Caption for image')." ".$this->images[$data['image_id']]['name']." "._('changed to')." ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='IMGCAP';
      $objeto_id=$data['image_id'];
      $action='CHG';
      break;
      
    case('details'):
      $note=_('Product detailed description changed');
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='DEST';
      $objeto_id='';
      $action='CHG';
      break;
    case('description'):
      $note=_('Product description changed to')." ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='DESC';
      $objeto_id='';
      $action='CHG';
      break;
    case('sdescription'):
      $note=_('Product short description changed to')." ".$new;
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='SDESC';
      $objeto_id='';
      $action='CHG';
      break;
    case('price'):
      $diff=$new-$old;
      $prefix='';
      if($diff>0){
	$txt=_('Price incresed by')." ";
	$prefix='+';
      }else{
	$prefix='-';
	$txt=_('Price decresed by')." ";
      }
      
      $per=percentage($diff,$old);
      $note=$txt.money($diff)." (".$per.") "._('to')." ".money($new).' '._('per').' '.$this->data['units_tipo_shortname'];

      
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='PRICE';
      $objeto_id='';
      $action='CHG';
      
      break;
    case('rrp'):
      if($old==''){
	$note=_('RRP set to')." ".money($new);
      }elseif($new==''){
	$note=_('RRP unset')." ("._('was')." ".money($old).")";
      }else{
	$diff=$new-$old;
	$prefix='';
	if($diff>0){
	  $txt=_('RRP incresed by')." ";
	  $prefix='+';
	}else{
	  $prefix='-';
	  $txt=_('RRP decresed by')." ";
	}
      
	$per=percentage($diff,$old);
	$note=$txt.money($diff)." (".$per.") "._('to')." ".money($new);
      }
      
      $sujeto='PROD';
      $sujeto_id=$this->id;
      $objeto='RRP';
      $objeto_id='';
      $action='CHG';
       
      break;
    default:
      return;
    }
    
    if(isset($data['date']))
      $date=$data['date'];
    else
      $date='NOW()';
    if(isset($data['user_id']))
      $user_id=$data['user_id'];
    else
      $user_id='';
    
    $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		 ,$date
		 ,prepare_mysql($sujeto)
		 ,prepare_mysql($sujeto_id)
		 ,prepare_mysql($objeto)
		 ,prepare_mysql($objeto_id)
		 ,prepare_mysql($action)
		 ,prepare_mysql($user_id)
		 ,prepare_mysql($old)	 
		 ,prepare_mysql($new)	 
		 ,prepare_mysql($note)); 
    print $sql;
    $this->db->exec($sql);

    return $note;
  }

function new_id(){
  

  $sql="select max(`Product id`) as id from `Product Dimension`";
  $result =& $this->db->query($sql);

  if(    $row=$result->fetchRow()){

    $id=$row['id']+1;
  }else{

    $id=1;
  }  

  //print "$id\n";
  // exit;
  return $id;
}


  function create($data){


    
    $code=$data['code'];
    $ncode=$this->normalize_code($code);

    $id_number=$this->new_id();

    if(isset($data['units per case']) and is_numeric($data['units per case']) and $data['units per case']>0)
      $units_factor=$data['units per case'];
    else
      $units_factor=1;
    $sql=sprintf("insert into  `Product Dimension` (`Product id`,`Product Code File As`,`Product Code`,`Product Status`,`Product Units Per Case`,`Product Valid From`,`Product Valid To`,`Product Most Recent`) values (%s,%s,%s,'%s',%f,NOW(),'%s','Yes')",
		 prepare_mysql($id_number),
		 prepare_mysql($ncode),
		 prepare_mysql($code),
		 'Preparing Product',
		 $units_factor,
		 date("Y-m-d H:i:s",strtotime("+24 month"))
		 );
    

    //   print "$sql\n";

    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {
      if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	return array('ok'=>false,'msg'=>_('Error: Another product has the same code').'.');
      else
	return array('ok'=>false,'msg'=>_('Unknwon Error').'.');
    }
    $this->id = $this->db->lastInsertID();  
    $this->get_data('id',$this->id);

    $family=false;
    $department=false;

    if(isset($data['family code']) and  $data['family code']!=''){
      if(!isset($data['family name']) or  $data['family name']=='')
	$data['family name']=$data['family code'];
      
      $family=new Family('code',$data['family code']);
      if(!$family->id){
	$fam_data=array(
			'code'=>$data['family code'],
			'name'=>$data['family name'],
			);
	$family=new Family('create',$fam_data);
      }
      $family->add_product($this->id,'principal');
    }
    $this->get_data('id',$this->id);
    
    if(isset($data['department code']) and  $data['department code']!=''){
      if(!isset($data['department name']) or  $data['department name']=='')
	$data['department name']=$data['department code'];
      
      $department=new Department('code',$data['department code']);
      if(!$department->id){
	$dept_data=array(
			'code'=>$data['department code'],
			'name'=>$data['department name'],
			);
	$department=new Department('create',$dept_data);
      }
      if(is_object($family) and $family->id)
	$department->add_family($family->id,'principal noproducts');
      $department->add_product($this->id,'principal');

    }
    $this->get_data('id',$this->id);
    








    $sql=sprintf("update  `Product Dimension` set `Product Current Product Key`=%d where `Product Key`=%d",$this->id,$this->id);
      $this->db->exec($sql);

    if(isset($data['price']) and is_numeric($data['price'])){
      $sql=sprintf("update  `Product Dimension` set `Product Price`=%.2f where `Product Key`=%d",$data['price'],$this->id);
      $this->db->exec($sql);
      $price=$data['price']/$units_factor;
      if($price<0.01)
	$price=0.01;
      $sql=sprintf("update  `Product Dimension` set `Product Unitary Price`=%.2f where `Product Key`=%d",$price,$this->id);
      $this->db->exec($sql);
    }
    if(isset($data['rrp']) and is_numeric($data['rrp'])){
      
      $sql=sprintf("update  `Product Dimension` set `Product Unitary RRP`=%.2f where `Product Key`=%d",$data['rrp'],$this->id);
      $this->db->exec($sql);
    }
    if(isset($data['name']) and $data['name']!=''){
      $sql=sprintf("update  `Product Dimension` set `Product Name`=%s where `Product Key`=%d",prepare_mysql($data['name']),$this->id);
      $this->db->exec($sql);
    }
    if(isset($data['short name']) and $data['short name']!=''){
      $sql=sprintf("update  `Product Dimension` set `Product Short Name`=%s where `Product Key`=%d",prepare_mysql($data['short name']),$this->id);
      $this->db->exec($sql);
     }
    $this->get_data('id',$this->id);

     if($this->get('xhtml short description')){
    $sql=sprintf("update  `Product Dimension` set `Product XHTML Short Description`=%s where `Product Key`=%d",prepare_mysql($this->get('xhtml short description')),$this->id);
    //  print $sql;
      
      $this->db->exec($sql);
           }

    
    if(isset($data['deals']) and is_array($data['deals'])){


      foreach($data['deals'] as $deal_data){
	//	print_r($deal_data);
	if($deal_data['deal trigger']=='Family')
	  $deal_data['deal trigger key']=$this->get('product family key');
	if($deal_data['deal trigger']=='Product')
	  $deal_data['deal trigger key']=$this->id;
	if($deal_data['deal allowance target']=='Product')
	  $deal_data['deal allowance target key']=$this->id;
	$deal=new Deal('create',$deal_data);
	
      }
    }
    //   exit;

    if(isset($data['sale state']) and preg_match('/^for sale|discontinued|in prrocess|unknown|history|not for sale/i',$data['sale state']) ){
      $sql=sprintf("update  `Product Dimension` set `Product Sales State`=%s where `Product Key`=%d",prepare_mysql($data['sale state']),$this->id);
      //  print "$sql\n";exit;
	
      $this->db->exec($sql);
     }
    
    

    $this->get_data('id',$this->id);
    
    


  //    if(isset($data['family code']) and $data['family code']!=''){
//        $sql=sprintf("update  `Product Dimension` set `Product Family Code`=%s where `Product Key`=%d",prepare_mysql($data['family code']),$this->id);
//        $this->db->exec($sql);
//      }
//      if(isset($data['family name']) and $data['family name']!=''){
//        $sql=sprintf("update  `Product Dimension` set `Product Family Name`=%s where `Product Key`=%d",prepare_mysql($data['family name']),$this->id);
//        $this->db->exec($sql);
//      }
//      if(isset($data['special characteristic']) and $data['special characteristic']!=''){
//        $sql=sprintf("update  `Product Dimension` set `Product Special Characteristic`=%s where `Product Key`=%d",prepare_mysql($data['special characteristic']),$this->id);
//        $this->db->exec($sql);
//      }
//   if(isset($data['department name']) and $data['department name']!=''){
//        $sql=sprintf("update  `Product Dimension` set `Product Department Name`=%s where `Product Key`=%d",prepare_mysql($data['department name']),$this->id);
//        $this->db->exec($sql);
//      }

    //$this->data['price']=$data['price'];

    //$this->data['rrp']=$rrp;
    //$this->data['description']=$data['description'];
    //$this->data['sdescription']=$data['sdescription'];

     $this->msg='Product Created';
    
    //$this->fix_todotransaction();
    //$this->set_stock(true);
    //$this->set_sales(true);
  }
  



  function new_part_list($part_list){
    
    $_base_data=array(
		      'product id'=>$this->data['product id'],
		      'part sku'=>'',
		      'product part id'=>'',
		      'requiered'=>'',
		      'parts per product'=>'',
		      'product part note'=>'',
		      'product part type'=>'',
		      'product part metadata'=>'',
		      'product part valid from'=>'',
		      'product part valid to'=>'',
		      'product part most recent'=>'Yes'
		     );


    
    foreach($part_list as $data){
      $_date='NOW()';
      
      $_date=$data['product part valid from'];
      if(!preg_match('/now/i',$_date))
	$_date=prepare_mysql($_date);

    
    $sql=sprintf("update `Product Part List`  set `Product Part Most Recent`='No' ,`Product Part Valid To`=%s where `Product ID`=%d and `Part SKU`=%d  and `Product Part Most Recent`='Yes' ",$_date,$this->data['product id'],$data['part sku']);
    $this->db->exec($sql);
    
    $base_data=$_base_data;
    foreach($data as $key=>$value){
      $base_data[strtolower($key)]=_trim($value);
    }
    
    $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      if(($key='product part valid from' or $key=='product part valid to') and preg_match('/now/i',$value))
	$values.="NOW(),";
      else
	$values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Product Part List` %s %s",$keys,$values);
    //    print "$sql\n";exit;
    $affected=& $this->db->exec($sql);
  }
  
  }


function normalize_code($code){
    $ncode=$code;
    $c=split('-',$code);
    if(count($c)==2){
      if(is_numeric($c[1]))
	$ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
      else{
	if(preg_match('/^[^\d]+\d+$/',$c[1])){
	  if(preg_match('/\d*$/',$c[1],$match_num) and preg_match('/^[^\d]*/',$c[1],$match_alpha)){
	    $ncode=sprintf("%s-%s%05d",strtolower($c[0]),strtolower($match_alpha[0]),$match_num[0]);
	    return $ncode;
	  }
	}
	if(preg_match('/^\d+[^\d]+$/',$c[1])){
	  if(preg_match('/^\d*/',$c[1],$match_num) and preg_match('/[^\d]*$/',$c[1],$match_alpha)){
	    $ncode=sprintf("%s-%05d%s",strtolower($c[0]),$match_num[0],strtolower($match_alpha[0]));
	    return $ncode;
	  }
	}
	

	$ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
      }

    }if(count($c)==3){
      if(is_numeric($c[1]) and is_numeric($c[2])){
	$ncode=sprintf("%s-%05d-%05d",strtolower($c[0]),$c[1],$c[2]);
	return $ncode;
      }
      if(!is_numeric($c[1]) and is_numeric($c[2])){
	$ncode=sprintf("%s-%s-%05d",strtolower($c[0]),strtolower($c[1]),$c[2]);
	return $ncode;
      }
      if(is_numeric($c[1]) and !is_numeric($c[2])){
	$ncode=sprintf("%s-%05d-%s",strtolower($c[0]),$c[1],strtolower($c[2]));
	return $ncode;
      }



    }
    

    return $ncode;
  }
  




  function fix_todotransaction(){
    
    
    $sql=sprintf("select * from todo_transaction where code like '%s' ",addslashes($this->data['code']));
    //  print "$sql";
    $res2 = $this->db->query($sql); 
    while ($row2=$res2->fetchRow()) {
      $code=$row['code'];
      $ordered=$row2['ordered'];
      $dispached=$row2['ordered']-$row2['reorder']+$row2['bonus'];
      $discount=$row2['discount'];
      $promotion_id=0;
      $charge=number_format($row2['price']*(1-$discount)*($row2['ordered']-$row2['reorder']),2,'.','');
      $order_id=$row2['order_id'];
      
      $sql=sprintf("insert into  transaction  (order_id,product_id,ordered,dispached,discount,promotion_id,charge) values   (%d,%d,'%s','%s','%s','%s','%s')"
		   ,$order_id,$this->id,$ordered,$dispached,$discount,$promotion_id,$charge);
      //	print "$sql\n";
      $affected=& $this->db->exec($sql);
      if (!PEAR::isError($affected)) {
	$sql=sprintf("delete from todo_transaction where id=%d",$row2['id']);
	//print "$sql\n";
	$this->db->exec($sql);
      }
    }
  }
 
  


  function set_stock($update_database=true){

    
    list (
	  $this->data['stock'],
	  $this->data['available'],
	  $this->data['stock_value']
	  )=$this->get_stock();
    
    if($update_database){
      $sql=sprintf('update product set stock=%.2f, available=%.2f, stock_value=%.3f where id=%d'
		   , $this->data['stock']
		   ,$this->data['available']
		   ,$this->data['stock_value']
		   ,$this->id
		   );

      $this->db->exec($sql);
      $this->load('stock_forecast');
      
    }
    
  }

  function get_stock($date=''){

    $white_star=0;
    $stock=0;
    $available=0;
  
    $sql=sprintf("select stock,picking_rank,location_id  from product2location where product_id=%d ",$this->id);

    $result =& $this->db->query($sql);
    $white_star=0;
    while($row=$result->fetchRow()){
      if($row['location_id']==2)
	$white_star=$row['stock'];
      else{
	$stock+=$row['stock'];
	if($row['picking_rank']>0)
	  $available+=$row['picking_rank'];
      }
    }
  
    $stock=$stock/$this->data['units'];
    $available=$available/$this->data['units'];
    return array($stock,$available,0);
  }





  function update_department(){

    $department_id=$this->department_id;
    $this->db =& MDB2::singleton();
    $sql=" select id ,
(select sum(product.tsall) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsall,
(select sum(product.tsy) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsy,
(select sum(product.tsq) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsq,
(select sum(product.tsm) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsm,



(select sum(product.stock_value) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as stock_value,(select count(*) from product_group where department_id=d.id    ) as families   ,(select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as products,(select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id and (condicion=0  or (condicion=1 and stock>0)  or (condicion=2 and stock>0)   )    )   as active  , (select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id and (condicion=0 and stock=0  ) )   as outofstock, (select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id and ( isnull(stock) or stock<0  ) )   as stockerror       from product_department  as d where d.id=$department_id";

    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      $products=$row['products'];
      $families=$row['families'];
      $outofstock=$row['outofstock'];
      $stockerror=$row['stockerror'];
      //    $total_sales=$row['total_sales'];
      $active=$row['active'];
      $stock_value=$row['stock_value'];
      if(!is_numeric($stock_value))
	$stock_value=0;
  
      $tsall=number_format($row['tsall'],2,'.','');
      $tsy=number_format($row['tsy'],2,'.','');
      $tsq=number_format($row['tsq'],2,'.','');
      $tsm=number_format($row['tsm'],2,'.','');


      $sql=sprintf("update product_department set  tsall=%s, tsy=%s,tsq=%s,tsm=%s    ,stock_value=%s ,families='%d',products='%d',outofstock='%d',stockerror='%d',active='%d' where id=%d  ",$tsall,$tsy,$tsq,$tsm,$stock_value,$families,$products,$outofstock,$stockerror,$active,$department_id); 
      // print "$sql\n";
      $this->db->exec($sql);
    }

  }

  function update_family($update_depto=false){
    $family_id=$this->data['group_id'];
    $this->db =& MDB2::singleton();
    $sql=" select id ,(select sum(product.tsq)  from product where group_id=g.id     ) as tsq , (select sum(product.tsm)  from product where group_id=g.id     ) as tsm ,  (select sum(product.tsy)  from product where group_id=g.id     ) as tsy , (select sum(product.tsall)  from product where group_id=g.id     ) as tsall , (select sum(product.stock_value)  from product where group_id=g.id     ) as stock_value , (select count(*) from product where group_id=g.id    ) as products  ,(select count(*) from product where group_id=g.id and (condicion=0  or (condicion=1 and stock>0)  or (condicion=2 and stock>0)   )    )   as active  ,(select count(*) from product where group_id=g.id and (condicion=0 and stock=0  ) )   as outofstock,(select count(*) from product where group_id=g.id and ( isnull(stock) or stock<0  ) )   as stockerror       from product_group  as g where g.id=$family_id";
    // print "$sql\n";
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      $products=$row['products'];
      $outofstock=$row['outofstock'];
      $stockerror=$row['stockerror'];
      //    $total_sales=$row['total_sales'];
      $active=$row['active'];
    

      $tsall=number_format($row['tsall'],2,'.','');
      $tsy=number_format($row['tsy'],2,'.','');
      $tsq=number_format($row['tsq'],2,'.','');
      $tsm=number_format($row['tsm'],2,'.','');

    
      $stock_value=$row['stock_value'];
      if(!is_numeric($stock_value))
	$stock_value=0;


      $sql=sprintf("update product_group set  tsall=%s, tsy=%s,tsq=%s,tsm=%s    ,  stock_value=%s ,products='%d',outofstock='%d',stockerror='%d',active='%d' where id=%d  ",$tsall,$tsy,$tsq,$tsm,  $stock_value,$products,$outofstock,$stockerror,$active,$family_id); 


    


      //  print "$sql\n";
      //exit;
      $this->db->exec($sql);

      if($update_depto){
	$this->update_department();

      }




    }

  }
}


function get_cat_base($tipo,$tipo_diplay='name',$parent_id=false,$deep=0,$prefix='',$parents=''){
  

    

  if($deep>20)
    return array();
  
  $deep++;
  $cat=array();

  if($parent_id){
    $sql=sprintf('select id,%s,name as iname from cat left  join cat_relations on (id=cat_id) where tipo=%d and parent_id=%d order by %s',$tipo_diplay,$tipo,$parent_id,$tipo_diplay);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
      
      $_name=$prefix.' >  '.$row[$tipo_diplay];
      if($parents=='')
	$_parents=$parent_id;
      else
	$_parents=$parents.','.$parent_id;

      list($subcats,$_deep)=get_cat_base($tipo,$tipo_diplay,$row['id'],$deep,$_name,$_parents);
      foreach($subcats as $subcat){
	$cat[$subcat['id']]=array('iname'=>$subcat['iname'],'show'=>1,'name'=>$subcat['name'],'_deep'=>$subcat['_deep'],'id'=>$subcat['id'],'parents'=>$subcat['parents']);
      }
      if(count($subcats)>0)
	$suffix=' >  ('._('Others').')';
      else
	$suffix='';

      $cat[$row['id']]=array('iname'=>$row['iname'],'show'=>1,'name'=>$_name.$suffix,'_deep'=>$deep,'id'=>$row['id'],'parents'=>$_parents);

    }
  }else{
    $sql=sprintf('select id,%s,name as iname from cat left  join cat_relations on (id=cat_id) where tipo=%d and ISNULL(cat_id) order by %s',$tipo_diplay,$tipo,$tipo_diplay);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
      
      list($subcats,$_deep)=get_cat_base($tipo,$tipo_diplay,$row['id'],$deep,$row[$tipo_diplay],$parents);
      
      foreach($subcats as $subcat){
	$cat[$subcat['id']]=array('iname'=>$subcat['iname'],'show'=>1,'name'=>$subcat['name'],'_deep'=>$subcat['_deep'],'id'=>$subcat['id'],'parents'=>$subcat['parents']);
      }
      if(count($subcats)>0)
	$suffix=' >  ('._('Others').')';
      else
	$suffix='';
      
      $cat[$row['id']]=array('iname'=>$row['iname'],'show'=>1,'name'=>$prefix.' '.$row[$tipo_diplay].$suffix,'_deep'=>$deep,'id'=>$row['id'],'parents'=>$parents);

    }
    
  }
  return array($cat,$deep);
}



function get_cat_tree($tipo,$parent_id=false,$deep=0){
  if($deep>20)
    return array();
  
  $deep++;
  $cat=array();

  if($parent_id){
    $sql=sprintf('select id,name from cat left  join cat_relations on (id=cat_id) where tipo=%d and parent_id=%d order by name',$tipo,$parent_id);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
      
      $cat[]=array('name'=>$row['name'],'deep'=>$deep);
      list($subcats,$_deep)=get_cat_tree($tipo,$row['id'],$deep);
      foreach($subcats as $subcat){
	$cat[]=array('name'=>$subcat['name'],'deep'=>$subcat['deep'],'id'=>$subcat['id']);
      }

    }
  }else{
    $sql=sprintf('select id,name from cat left  join cat_relations on (id=cat_id) where tipo=%d and ISNULL(cat_id) order by name',$tipo);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
      $cat[]=array('name'=>$row['name'],'deep'=>$deep);
      list($subcats,$_deep)=get_cat_tree($tipo,$row['id'],$deep);

      foreach($subcats as $subcat){
	$cat[]=array('name'=>$subcat['name'],'deep'=>$subcat['deep'],'id'=>$subcat['id']);
      }
    }
    
  }
  return array($cat,$deep);
}
 
function volumen($tipo,$data){

  if($data=='')
    return '';
  switch($tipo){
  case 1:
    $_data=split('x',$data);
    return $_data[0]* $_data[1]*$_data[2];
    break;
  case 2:
    return $data*$data*$data*0.523598775;
    break;
  case 3:
    $_data=split('x',$data);
    return 0.785398162*$_data[0]*$data[1];
    break;
  case 4:
    return 0.007853982*$data;
    break;
  case 5:
    $_data=split('x',$data);
    return $_data[0]* $_data[1]*0.1;
    break; 
  default:
    return '';
  }
  
}

function ln_dim($tipo,$data){
  global $_shape;
  if($data=='')
    return '';
  switch($tipo){
  case 1:
    $_data=split('x',$data);
    return $_shape[$tipo]." ("._('w').':'.number($_data[0])._('cm').","._('d').":".number($_data[1])._('cm').","._('h').":".number( $_data[2])._('cm').")";
    break;
  case 2:
    return $_shape[$tipo]." (&empty;:".number( $data)._('cm').")";
    break;
  case 3:
    $_data=split('x',$data);
    return $_shape[$tipo]." ("._('h').':'.number($_data[1])._('cm').",&empty;:".number( $_data[0])._('cm').")";
    break;
  case 4:
    return $_shape[$tipo]." ("._('lenght').":".number( $data)._('cm').")";
    break;
  case 5:
    $_data=split('x',$data);
    return $_shape[$tipo]." ("._('w').':'.number($_data[0])._('cm').","._('h').":".number( $_data[1])._('cm').")";
    break; 
  default:
    return '';
  }
  
}

//    if($key=='weight'){
// 		$history_text1=_('Product unitary weight set to');
// 		$history_text2=_('Product unitary weight changed');
// 		$tipo_code='UWE';
// 	      }else{
// 		$history_text1=_('Product outer weight set to');
// 		$history_text2=_('Product outer weight changed');
// 		$tipo_code='OWE';
// 	      }
	      
// 	      if($old_value=='')
// 		$note=$history_text1." ".number($value,3)._('Kg');
// 	      else{
		
// 		$note=$history_text2.": ".number($old_value,3)._('Kg')." &rarr; ".number($value,3)._('Kg');
// 	      }
// 	      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'UPD',NULL,'%s',%d,%s,%s,%s)"
// 			   ,$date,$this->id,$value['user_id'],$tipo_code,($old_value==''?'NULL':$old_value),$value,prepare_mysql($note)); 
	      
// 	      mysql_query($sql);
// 	      //	      $res[$key]=array('res'=>2,'new_value'=>'','desc'=>$sql);
// 	       $res[$key]=array('res'=>1,'new_value'=>sprintf("%.3f",$value));
// 	    }
// 	  }
// 	}
//	break;








// 	$old_value=$this->data[$key];
// 	$old_value_tipo=$this->data[$key.'_tipo'];
// 	$old_value_tipo_id=$this->data[$key.'_tipo_id'];
// 	if($old_value!=$dims or $old_value_tipo_id!=$tipo){
// 	  $sql=sprintf("update product set %s=%s,%s=%s where id=%d",$key,prepare_mysql($dims),$key.'_tipo',prepare_mysql($tipo),$this->id);
// 	  //	  return $res[$key]=array('res'=>2,'desc'=>$sql);
// 	  $affected=& $this->db->exec($sql);
// 	  if (PEAR::isError($affected))
// 	    $res[$key]=array('res'=>2,'new_value'=>'','desc'=>'Error while trying to update product');
// 	  else{
// 	    $res[$key]=array('res'=>1,'new_value'=>$value);
// 	    global $_shape;
// 	    $this->data[$key]=$dims;
// 	    $this->data[$key.'_tipo_id']=$tipo;
// 	    $this->data[$key.'_tipo']=$_shape[$tipo];
	    

// 	    $this->get($preffix.'vol');
// 	    if($key=='dim'){
// 	      $history_text1=_('Product dimentions set to');
// 	      $history_text2=_('Product dimentions changed');
// 	      $tipo_code='UDIM';
	      
// 	    }else{
// 	      $history_text1=_('Outer dimentions set to');
// 	      $history_text2=_('Outer dimentions changed');
// 	      $tipo_code='ODIM';
// 	    }
	    
	    
// 	    if($old_value=='')
// 	      $note=$history_text1." ".ln_dim($this->data[$preffix.'dim_tipo_id'],$this->data[$preffix.'dim']);
// 	    else{
	      
// 	      $note=$history_text2.": ".ln_dim($old_value_tipo_id,$old_value)." &rarr; ".ln_dim($this->data[$preffix.'dim_tipo_id'],$this->data[$preffix.'dim']);
// 		}
// 	    $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'UPD',NULL,'%s',%d,%s,%s,%s)"
// 			 ,$date,$this->id,$value['user_id'],$tipo_code,($old_value==''?'NULL':prepare_mysql($old_value_tipo_id.'_'.$old_value)),prepare_mysql($tipo.'_'.$dims),prepare_mysql($note)); 
// 	    // return $res[$key]=array('res'=>2,'desc'=>$sql);
// 	    mysql_query($sql);
// 	    //	      $res[$key]=array('res'=>2,'new_value'=>'','desc'=>$sql);
// 	    $res[$key]=array('res'=>1,'new_value'=>$dims);
// 	  }
// 	}
// 	}
//  function update_supplier($data){
//     switch($data['tipo']){
//     case('new'):
//       $supplier_id=$data['supplier_id'];
//       $code=stripslashes($data['code']);
//       $user_id=$data['user_id'];
//       $cost=$data['cost'];
//       $date='NOW()';

//       $sql=sprintf("select name from supplier  where id=%d",$supplier_id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$supplier_name=$row['name'];
	
//       }else
// 	return array(false,_('Supplier do not exist'));


//       $sql=sprintf("insert into product2supplier (supplier_id,product_id,price,sup_code) values (%d,%d,%.3f,%s)",$supplier_id,$this->id,$cost,prepare_mysql($code));

//       $this->db->exec($sql);
//       $p2s_id=$this->db->lastInsertID();
//       $note=_('New supplier for')." ".$this->data['code'].": ".$supplier_name;
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'NEW',%d,NULL,'%d',%s)"
// 		   ,$date,$this->id,$supplier_id,$user_id,$p2s_id,prepare_mysql($note)); 
//       mysql_query($sql);
      
//       return array(true);
//       break;
//     case('update'):
//       $supplier_id=$data['supplier_id'];
//       $code=stripslashes($data['code']);
//       $user_id=$data['user_id'];
//       $cost=$data['cost'];
//       $date='NOW()';

//       $sql=sprintf("select id,sup_code,price from product2supplier where product_id=%d and supplier_id=%d ",$this->id,$supplier_id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$old_code=$row['sup_code'];
// 	$old_cost=$row['price'];
// 	$p2s_id=$row['id'];
//       }else
// 	return array(false,_('Supplier is not associated with the product'));

//       if($old_code!=$code){
// 	$sql=sprintf("update product2supplier set sup_code=%s where id=%d",prepare_mysql($code),$p2s_id);

// 	mysql_query($sql);

// 	$note=_("The suppliers code for")." ".$this->data['code']." "._('changed')." $old_code &rarr; $code";
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'COD',%d,%s,%s,%s)"
// 		     ,$date,$this->id,$supplier_id,$user_id,prepare_mysql($old_code),prepare_mysql($code),prepare_mysql($note)); 
// 	mysql_query($sql);
//       }
//       if($old_cost!=$cost){
// 	$sql=sprintf("update product2supplier set price=%.4f where id=%d",$cost,$p2s_id);
// 	mysql_query($sql);
// 	$note=_("The suppliers unit cost for")." ".$this->data['code']." "._('changed')." ".money($old_cost)." &rarr; ".money($code);
// 	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'COS',%d,%.4f,'%.4f',%s)"
// 		     ,$date,$this->id,$supplier_id,$user_id,$old_cost,$cost,prepare_mysql($note)); 
// 	mysql_query($sql);
//       }
//       return array(true);
      
//       break;
//     case('delete'):
//       $supplier_id=$data['supplier_id'];
//       $user_id=$data['user_id'];
//       $date='NOW()';


//       $sql=sprintf("select id from product2supplier where product_id=%d and supplier_id%d ",$this->id,$supplier_id); 
//       $result =& $this->db->query($sql);
//       if($row=$result->fetchRow()){
// 	$p2s_id=$row['id'];
//       }else
// 	return array(false,_('Supplier is not associated with the product'));

//       $sql=sprintf("delete from product2supplier where id=%d",$p2s_id);
//       mysql_query($sql);
      

//       $note=$this->code." "._('is no longer supplier by ').$supplier_name;
//       $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'DEL',%d,'%d',NULL,%s)"
// 		   ,$date,$this->id,$supplier_id,$user_id,$p2s_id,prepare_mysql($note)); 
//       mysql_query($sql);
//       return array(true);
//       break;

//     }
//   }

function xnew_id(){
  $left_side='101011';
    
    
  $select="select max(`Product id`) as id_number from `Product Dimension`";
  if($result =& $this->db->query($sql)){
    $row=$result->fetchRow();
    return $row['id_number']+1;
  }else
    return $left_side.'000001';
  
    
}



?>
