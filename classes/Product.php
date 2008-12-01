<?

class product{
  
 
  var $product=array();
  var $categories=array();

  var $parents=array();
  var $childs=array();
  var $suppliers=array();
  var $locations=array();
  var $notes=array();
  var $images=array();

  var $db;

  

  function __construct($a1,$a2=false) {
    $this->db =MDB2::singleton();


    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    } else
      $this->get_data($a1,$a2);

  }
  



  function get_data($tipo,$product_id){
    global $_shape;
    $sql=sprintf("select *,UNIX_TIMESTAMP(first_date) as first_date from product where id=%d",$product_id);
    if($result =& $this->db->query($sql)){
      $this->data=$result->fetchRow();
            $this->id=$this->data['id'];
      if($this->data['first_date']!='')
	$this->data['dates']=array('first_date'=>($this->data['first_date']?strftime("%e %b %Y",$this->data['first_date']):''));
      else{
	$this->load('first_date','save');
      }
      $this->data['odim_tipo_id']=$this->data['odim_tipo'];
      $this->data['odim_tipo']=$_shape[$this->data['odim_tipo_id']];
      $this->data['dim_tipo_id']=$this->data['dim_tipo'];
      $this->data['dim_tipo']=$_shape[$this->data['dim_tipo_id']];
      $this->get('ovol');
      $this->get('vol');

      return true;
    }else
      return false;
  }
  
  
  function load($data_to_be_read,$args=''){
    
    if(!is_array($data_to_be_read))
      $data_to_be_read=array($data_to_be_read);
    foreach($data_to_be_read as $table){
      
      switch($table){
      case('product_tree'):
	$sql=sprintf('select d.name as department,d.id as department_id,g.name as group_name,group_id from product left join product_group as g on (g.id=group_id)  left join product_department as d on (d.id=department_id) where product.id=%s ',$this->id);

	$res = $this->db->query($sql); 
	if ($row=$res->fetchRow()) {
	  $this->group_id=$row['group_id'];
	  $this->department_id=$row['department_id'];
	  $this->group=$row['group_name'];
	  $this->department=$row['department'];
	}
	break;
      case('locations'):
	global $_location_tipo;
	$this->read('same_products');

	$_data=array();
	$this->locations=array('is_parent'=>false,'has_display'=>false,'has_unknown'=>false,'has_loading'=>false,'has_link'=>false,'has_white_hole'=>false,'has_picking_area'=>false,'has_physical'=>false,'data'=>array(),'num_physical'=>0,'num_physical_with_stock'=>0,'num_picking_areas'=>0);
	$sql=sprintf("select name,product2location.id as id,location_id,stock,picking_rank,tipo,stock  from product2location left join location on (location_id=location.id) where product_id=%d and picking_rank is not null order by picking_rank  ",$this->data['location_parent_id']);
	$result =& $this->db->query($sql);
	$num_same_products=count($this->same_products);
	if($num_same_products>0){
	  $this->locations['has_link']=true;
	  if($this->id==$this->data['location_parent_id'])
	    $this->locations['is_parent']=true;
	}
	$stock_units=0;	  
	while($row=$result->fetchRow()){
	  $stock=number($row['stock']/$this->data['units'],1);
	  if($num_same_products==0)
	    $stock_outers=$stock;
	  else{
	    $stock_outers='<b>'.number($stock,1).'</b>';
	    foreach($this->same_products as $_same){
	      $stock_outers.=';'.number($row['stock']/$_same['units'],1);
	    }
	  }
	  
	  $stock_units+=$row['stock'];
	  
	  $_data[$row['id']]=array(
				   'id'=>$row['id'],
				   'name'=>$row['name'],
				   'location_id'=>$row['location_id'],
				   'stock'=>$stock,
				   'stock_units'=>$row['stock'],
				   'stock_outers'=>$stock_outers,
				   'tipo'=>$_location_tipo[$row['tipo']],
				   'picking_tipo'=>getOrdinal($row['picking_rank']),
				   'picking_rank'=>$row['picking_rank'],
				   'is_physical'=>true,
				   'can_pick'=>true,
				   'has_stock'=>($row['stock']>0?true:false)
				   );
	  $this->locations['num_physical']++;
	  if($row['stock']>0)
	    $this->locations['num_physical_with_stock']++;
	  $this->locations['num_picking_areas']++;
	  $this->locations['has_physical']=true;
	  


	}
	


	$sql=sprintf("select name,product2location.id as id,location_id,stock,picking_rank,tipo,stock  from product2location left join location on (location_id=location.id) where product_id=%d and picking_rank is  null order by tipo desc  ",$this->data['location_parent_id']);
	$result =& $this->db->query($sql);
	while($row=$result->fetchRow()){
	   $stock_units+=$row['stock'];
	   $stock=number($row['stock']/$this->data['units'],1);
	  if($num_same_products==0)
	    $stock_outers=$stock;
	  else{
	    $stock_outers='<b>'.number($stock,1).'</b>';
	    foreach($this->same_products as $_same){
	      $stock_outers.=';'.number($row['stock']/$_same['units'],1);
	    }
	  }


	  $is_physical=false;
	  $picking_tipo='';
	  $can_pick=false;
	  $icon='';
	  $tipo='';
	  if($row['tipo']=='unknown'){
	    $this->locations['has_unknown']=true;
	    $name=$_location_tipo[$row['tipo']];
	  }else if($row['tipo']=='white_hole'){
	    $this->locations['has_white_hole']=true;
	    $name=$_location_tipo[$row['tipo']];
	  }else if($row['tipo']=='loading'){
	    $this->locations['has_loading']=true;
	    $name=$row['name'];
	  
	  }else{
	    $tipo=$_location_tipo[$row['tipo']];
	    $name=$row['name'];
	    $this->locations['has_physical']=true;
	    $this->locations['num_physical']++;
	    if($row['stock']>0)
	      $this->locations['num_physical_with_stock']++;
	    $is_physical=true;
	  }
	    
	  

	  $_data[$row['id']]=array(
					   'id'=>$row['id'],
					   'name'=>$name,
					   'location_id'=>$row['location_id'],
					   'stock'=>$stock,
					   'stock_units'=>$row['stock'],
					   'stock_outers'=>$stock_outers,
					   'tipo'=>$tipo,
					   'picking_tipo'=>$picking_tipo,
					   'picking_rank'=>'',
					   'is_physical'=>$is_physical,
					   'can_pick'=>$can_pick,
					   'has_stock'=>($row['stock']>0?true:false)
					   );

	}
	
	$this->locations['data']=$_data;
	$this->locations['stock']=$this->data['stock'];
	$this->locations['stock_units']=$stock_units;
	$this->locations['stock_outers']="<b>".$this->data['stock'].'</b>';

	foreach($this->same_products as $_same){
	  $this->locations['stock_outers'].=';'.number($stock_units/$_same['units']);
	}


	break;
      case('suppliers'):
	$this->suppliers=array();
	$sql=sprintf("select p2s.supplier_id, p2s.price,p2s.sup_code as code,s.name as name from product2supplier as p2s left join supplier as s on (p2s.supplier_id=s.id) where p2s.product_id=%d",$this->id);
	
	$result =& $this->db->query($sql);
	//$supplier=array();
	$this->suppliers['name']=array();
	$this->suppliers['price']=array();
	$this->suppliers['code']=array();
	while($row=$result->fetchRow()){
	  $this->suppliers['name'][$row['supplier_id']]=$row['name'];
	  $this->suppliers['price'][$row['supplier_id']]=money($row['price']);
	  $this->suppliers['num_price'][$row['supplier_id']]=$row['price'];
	  $this->suppliers['code'][$row['supplier_id']]=$row['code'];
	}

	$this->suppliers['number']=count($this->suppliers['name']);
	break;
	// print_r($this->suppliers);
      case('same_products'):
	$sql=sprintf("select id,code,units from product  where location_parent_id=%d and id!=%d order by units",$this->data['location_parent_id'],$this->id);
	$result =& $this->db->query($sql);
	$this->same_products=array();
	while($row=$result->fetchRow()){
	  $this->same_products[$row['id']]=array(
						 'code'=>$row['code'],
						 'units'=>$row['units'],
						 'f_units'=>number($row['units'])
						 );
	    
	}
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
	$this->images=array();
	$sql=sprintf("select filename,format,principal,caption,id from image where  product_id=%d order by principal desc",$this->id);
	$result =& $this->db->query($sql);
	$principal=false;
	while($row=$result->fetchRow()){
	  if($row['principal']==1 and !$principal){
	    $src='med/'.$row['filename'].'_med.'.$row['format'];
	    $set_principal=true;
	  }else{
	    $src='tb/'.$row['filename'].'_tb.'.$row['format'];
	  }
	  $this->images[]=array('id'=>$row['id'],'src'=>$src,'caption'=>$row['caption']);
	}

	
	break;
      case('stock_forecast'):
	//simplest one
	//get the best possible average
	$aw=0;
	if(is_numeric($this->data['awtdq']) and $this->data['awtdq']>0)
	  $aw=$this->data['awtsq'];
	elseif(is_numeric($this->data['awtdm']) and $this->data['awtdm']>0)
	  $aw=$this->data['awtdq'];
	elseif(is_numeric($this->data['tdw']) and $this->data['tdw']>0)
	  $aw=$this->data['tdw'];
	


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


 function read($key){
   $sql=sprintf("select %s as value  from product where id=%d",addslashes($key),$this->id);
   //   print "$sql\n";
   $res = $this->db->query($sql); 
   if ($row=$res->fetchRow()) {
     return $row['value'];
   }else
     return false;
	

  }



  function get($item=''){

    switch($item){
    case('sales'):
      $this->data['sales']=array();
      $sql=sprintf("select * from sales  where tipo='prod' and tipo_id=%d",$this->id);
      if($result =& $this->db->query($sql)){
	$this->data['sales']=$result->fetchRow();
      }else{
	$this->load('sales');
	$this->save('sales');
      }
      

      
    case('vol'):
      $this->data['vol']=volumen($this->data['dim_tipo_id'],$this->data['dim']);
      break;
    case('ovol'):
      $this->data['ovol']=volumen($this->data['odim_tipo_id'],$this->data['odim']);
      break;
    case('a_dim'):
      if($this->data['dim']!='')
	$a_dim=array($this->data['dim']);
      split('x',$this->data['dim']);
    case('first_date'):
      return $data->data['dates']['first_date'];
      break;
    case('weeks'):
      if(is_numeric($this->data['first_date'])){
      	$date1=date('d-m-Y',strtotime('@'.$this->data['first_date']));
	$day1=date('N')-1;
	$date2=date('d-m-Y');
	$days=datediff('d',$date1,$date2);
	$weeks=number_weeks($days,$day1);
      }else
	$weeks=0;
      return $weeks;
      
      break;
    case('number_of_suppliers'):
      return  $this->suppliers['number'];
    case('supplier_name'):
      return  $this->suppliers['name'];
    case('supplier_code'):
      return  $this->suppliers['code'];
    case('supplier_price'):
      return  $this->suppliers['price'];
    case('supplier_num_price'):
      return  $this->suppliers['num_price'];
    default:

      if(isset($this->data[$item]))
	return $this->data[$item];
      elseif(isset($this->$item))
	return $this->$item;
      else 
	return false;
    }

  }
    

  function update_supplier($data){
    switch($data['tipo']){
    case('new'):
      $supplier_id=$data['supplier_id'];
      $code=stripslashes($data['code']);
      $user_id=$data['user_id'];
      $cost=$data['cost'];
      $date='NOW()';

      $sql=sprintf("select name from supplier  where id=%d",$supplier_id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$supplier_name=$row['name'];
	
      }else
	return array(false,_('Supplier do not exist'));


      $sql=sprintf("insert into product2supplier (supplier_id,product_id,price,sup_code) values (%d,%d,%.3f,%s)",$supplier_id,$this->id,$cost,prepare_mysql($code));

      $this->db->exec($sql);
      $p2s_id=$this->db->lastInsertID();
      $note=_('New supplier for')." ".$this->data['code'].": ".$supplier_name;
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'NEW',%d,NULL,'%d',%s)"
		   ,$date,$this->id,$supplier_id,$user_id,$p2s_id,prepare_mysql($note)); 
      mysql_query($sql);
      
      return array(true);
      break;
    case('update'):
      $supplier_id=$data['supplier_id'];
      $code=stripslashes($data['code']);
      $user_id=$data['user_id'];
      $cost=$data['cost'];
      $date='NOW()';

      $sql=sprintf("select id,sup_code,price from product2supplier where product_id=%d and supplier_id=%d ",$this->id,$supplier_id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$old_code=$row['sup_code'];
	$old_cost=$row['price'];
	$p2s_id=$row['id'];
      }else
	return array(false,_('Supplier is not associated with the product'));

      if($old_code!=$code){
	$sql=sprintf("update product2supplier set sup_code=%s where id=%d",prepare_mysql($code),$p2s_id);

	mysql_query($sql);

	$note=_("The suppliers code for")." ".$this->data['code']." "._('changed')." $old_code &rarr; $code";
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'COD',%d,%s,%s,%s)"
		     ,$date,$this->id,$supplier_id,$user_id,prepare_mysql($old_code),prepare_mysql($code),prepare_mysql($note)); 
	mysql_query($sql);
      }
      if($old_cost!=$cost){
	$sql=sprintf("update product2supplier set price=%.4f where id=%d",$cost,$p2s_id);
	mysql_query($sql);
	$note=_("The suppliers unit cost for")." ".$this->data['code']." "._('changed')." ".money($old_cost)." &rarr; ".money($code);
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'COS',%d,%.4f,'%.4f',%s)"
		     ,$date,$this->id,$supplier_id,$user_id,$old_cost,$cost,prepare_mysql($note)); 
	mysql_query($sql);
      }
      return array(true);
      
      break;
    case('delete'):
      $supplier_id=$data['supplier_id'];
      $user_id=$data['user_id'];
      $date='NOW()';


      $sql=sprintf("select id from product2supplier where product_id=%d and supplier_id%d ",$this->id,$supplier_id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$p2s_id=$row['id'];
      }else
	return array(false,_('Supplier is not associated with the product'));

      $sql=sprintf("delete from product2supplier where id=%d",$p2s_id);
      mysql_query($sql);
      

      $note=$this->code." "._('is no longer supplier by ').$supplier_name;
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'SUP',%d,'DEL',%d,'%d',NULL,%s)"
		   ,$date,$this->id,$supplier_id,$user_id,$p2s_id,prepare_mysql($note)); 
      mysql_query($sql);
      return array(true);
      break;

    }
  }

  function update_location($data){
    switch($data['tipo']){
    case('link'):
      $user_id=$data['user_id'];
      $product_id=$data['product_id'];
      $date='NOW()';
      
      if($product_id==$this->id)
	return array(false,_('Nothing to change '));
      if(!$link_product=new Product($product_id))
	return array(false,_('Product to be linked do not exist'));
      if($link_product->data['units']>=$this->data['units']){
	$old_value=$this->data['location_parent_id'];
	$sql=sprintf("update product set location_parent_id=%d where id=%d ",$link_product->id,$this->id);
	mysql_query($sql);
	$this->set_stock();
	
	$note=$this->data['code']." "._('is now linked to')." ".$link_product->data['code'];
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',%d,%s)"
		     ,$date,$this->id,$user_id,$old_value ,$link_product->id,prepare_mysql($note)); 
	mysql_query($sql);

      }else{
	//this product is the new macho man
	$sql=sprintf("update product2location set product_id=%d where product_id=%d",$this->id,$link_product->id);
	mysql_query($sql);
	$sql=sprintf("update product set location_parent_id=%d where location_parent_id=%d  or id=%d or id=%d ",$this->id,$link_product->id,$this->id,$link_product->id);
	mysql_query($sql);
	unset($link_product);
	$this->set_stock();
	$this->read('same_products');
	if($num_linked=count($this->same_products)>0){
	  $linked_codes='';
	  $linked_ids='';
	  foreach($this->same_products as $key=>$value){
	    $linked_ids.=','.$key;
	    $linked_codes.=','.$value['code'];
	  }
	  $linked_ids=preg_replace('/^\,/','',$linked_ids);
	  $linked_codes=preg_replace('/^\,/','',$linked_codes);
	  
	  $note=$linked_codes." ".ngettext('is now linked to this product','are linked to this product',$num_linked);
	  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'CLO',NULL,'NEW',%d,'%d',NULL,%s)"
		       ,$date,$this->id,$user_id,$linked_ids,prepare_mysql($note)); 
	  mysql_query($sql);
	}

	return array(true);


      }
      
      
      
      break;

    case('unlink'):
      $user_id=$data['user_id'];
      $date='NOW()';
      
      $old_value=$this->data['location_parent_id'];
      $this->read('same_products');
      $this->read('locations');
      
      if($this->locations['is_parent']){
	// unlink the children
	foreach($this->same_products as $key=>$value){
	  $sql=sprintf("update product set location_parent_id=%d where id=%d",$key,$key);
	  mysql_query($sql);
	  $note=_('Product unlinked from')." ".$this->data['code'];
	  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'PLO',NULL,'ULI',%s,'%d','%d')",$date,$key,$user_id,$this->id,$key,prepare_mysql($note)); 
	  mysql_query($sql);
	}
	return array(true);	
      }else if($old_value!=$this->id){
	
	
	$sql=sprintf("update product set location_parent_id=%d where id=%d",$this->id,$this->id);
	
	mysql_query($sql);
	$note=_('Product unlinked from')." ".$this->same_products[$old_value]['code'];
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'PLO',NULL,'ULI',%s,'%d','%d')",$date,$this->id,$user_id,$old_value,$this->id,prepare_mysql($note)); 
	mysql_query($sql);
	return array(true);	
      }
      return array(false,_('Nothing to change'));	
      break;
    case('set_picking_rank'):
      
      // print_r($data);
      $id=$data['product2location_id'];
      $rank=$data['rank'];
      $user_id=$data['user_id'];
      $date='NOW()';
      $history=(isset($data['no_history']) and  $data['no_history']?false:true);

      $sql=sprintf("select picking_rank,product_id,location_id from product2location  where id=%d",$id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$location_id=$row['location_id'];
	$old_rank=$row['picking_rank'];
      }else
	return array(false,_('No such location'));
      $this->read(array('locations'));
      $location_data=$this->get('locations');

      if(preg_match('/^\+/',$rank)){
	$change=preg_replace('/^\+/','',$rank);
	if(!is_numeric($change))
	  return array(false,_('Wrong new rank'));
	if($old_rank=='')
	  $rank=$location_data['num_picking_areas']+$change;
	else
	  $rank=$old_rank+$change;

      }else if(preg_match('/^\-/',$rank)){
	$change=preg_replace('/^\-/','',$rank);
	if(!is_numeric($change))
	  return array(false,_('Wrong new rank'));
	if($old_rank=='')
	  $rank=$location_data['num_picking_areas']-$change-1;
	else
	  $rank=$old_rank-$change;
	
	if($rank<1)
	  $rank=1;
      }


      if(!is_numeric($rank))
	return array(false,_('The picking prefrerence should be a positive interger')); 
      if($rank>$location_data['num_picking_areas'] or $rank<0)
	$new_rank=$location_data['num_picking_areas']+1;
      else
	$new_rank=$rank;
      
      
      if($rank==0)
	$sql=sprintf("update product2location  set picking_rank=NULL where id=%d",$id);// products con not be picked from this location
      else
	$sql=sprintf("update product2location  set picking_rank=%d where id=%d",$new_rank,$id); 
      //  print $sql;
      mysql_query($sql);
      
      $sql=sprintf("select id ,picking_rank from product2location where product_id=%d and id!=%d order by picking_rank",$this->id,$id);
      
      $result =& $this->db->query($sql);
      $_rank=1;
      while($row=$result->fetchRow()){
	if($_rank==$new_rank)
	  $_rank++;
	
	if(is_numeric($row['picking_rank'])){
	  $sql=sprintf("update product2location  set picking_rank=%d where id=%d",$_rank,$row['id']); 
	  mysql_query($sql);
	  $_rank++;
	}
	
      }
      
      if($history){
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,tipo,staff_id,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'CPI',%s,'%d','%d')",$date,$this->id,$location_id,$user_id,(is_numeric($rank_old)?$rank_old:0),$rank); 
	mysql_query($sql);
      }
      
      $this->read(array('locations'));
      $locations_data=$this->get('locations');
      return array(true,$locations_data);	
      
      
    
      break;
 case('change_qty'):

   

      $id=$data['p2l_id'];
      $user_id=$data['user_id'];

      $qty=$data['qty'];
      $msg=$data['msg'];
      $date='NOW()';
      if(!is_numeric($qty) or $qty<0)
	return array(false,_('Wrong stock value'));


      $sql=sprintf("select stock,picking_rank,product_id,location_id,name from product2location  left join location on (location.id=location_id) where product2location.id=%d",$id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	if($row['product_id']!=$this->id)
	  return array(false,_('This location is no associated with the product'));
	$location_id=$row['location_id'];
	$old_qty=$row['stock'];
	$location_name=$row['name'];
	$change=$qty-$old_qty;
      }else
	return array(false,_('This location is no associated with the product'));


      if($change==0){
	$note=_('Audit').', '.number($qty).' '.ngettext('outer','outers',$change).' '._('in').' '.$location_name.$msg;
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'AUD',%d,%s,'%s','%s')",$date,$this->id,$location_id,$user_id,prepare_mysql($note),$old_qty, $qty); 
	mysql_query($sql);
      }else{
	$sql=sprintf("update product2location set stock=%.4f where id=%d",$qty,$id); 
	mysql_query($sql);
	$this->set_stock();
	$note=_('Audit').', '.number($qty).' '.ngettext('outer','outers',$change).' '._('in').' '.$location_name.' ('.($change>0?'+':'').number($change).')'.$msg;
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'AUD',%d,%s,'%s','%s')",$date,$this->id,$location_id,$user_id,prepare_mysql($note),$old_qty, $qty); 
	mysql_query($sql);
      }
	$this->read(array('locations'));
      $locations_data=$this->get('locations');
      return array(true,$locations_data,$this->data['stock']);
      break;

  case('change_location'):
      $id=$data['p2l_id'];
      $user_id=$data['user_id'];
      $new_location_name=stripslashes($data['new_location_name']);
      $msg=$data['msg'];
      $date='NOW()';
      

      $sql=sprintf("select id from location  where name=%s",prepare_mysql($new_location_name)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$new_location_id=$row['id'];
	
      }else
	return array(false,_('This location do not exist'));
      
		   
      $sql=sprintf("select picking_rank,product_id,location_id,name as location_name from product2location  left join location on location.id=location_id  where product2location.id=%d",$id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	if($row['product_id']!=$this->id)
	  return array(false,_('This location is no associated with the product'));
	if($row['location_id']==$new_location_id)
	  return array(false,_('Nothing to change'));
	$old_location_id=$row['location_id'];
	$old_location_name=$row['location_name'];
      }else{
	return array(false,_('This location is no associated with the product'));
      }  
      
      
		   
      $sql=sprintf("update product2location set location_id=%d where id=%d",$new_location_id,$id); 
      mysql_query($sql);
      $this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>999999999,'user_id'=>$user_id,'no_history'=>true));
      
      if($old_location_id==1)
	$note=_('Unknown location has been identified as').' '.$new_location_name;
      else
	$note=$new_location_name.' '._('was wrongly identified as').' '.$old_location_name.' ('._('Corrected').')'.($msg!=''?'; '.stripslashes($msg):'');
      
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PROD',%d,'L2P',%d,'ERL',%d,'%d','%d',%s)"
		   ,$date,$this->id,$id,$user_id,$old_location_id, $new_location_id,prepare_mysql($note)); 
      //      return array(false,$sql);
      mysql_query($sql);
      
      $this->read(array('locations'));
      $locations_data=$this->get('locations');
      return array(true,$locations_data,$new_location_id);
      break;


    case('swap_picking'):
      $id=$data['p2l_id'];
      $user_id=$data['user_id'];

      $action=$data['action'];
      $date='NOW()';

      $sql=sprintf("select name,picking_rank,product_id,location_id from product2location  left join location on (location.id=location_id) where product2location.id=%d",$id); 
      //print $sql;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	if($row['product_id']!=$this->id)
	  return array(false,_('This location is no associated with the product'));
	if($action==1 and is_numeric($row['picking_rank'])  or  $action==0 and !is_numeric($row['picking_rank'])  )
	  return array(false,_('Nothing to change'));
	$location_id=$row['location_id'];
	$location_name=$row['name'];
      }else
	return array(false,_('This location is no associated with the product'));
      // del
      $this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>($action?999999999:0),'user_id'=>$user_id,'no_history'=>true));
      
      if($action==1)
	$note=_('Products now can be picked from').' '.$location_name;
      else
	$note=_('Products can no longer be picked from').' '.$location_name;
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'PCK',%d,%s,'%s','%s')",$date,$this->id,$location_id,$user_id,prepare_mysql($note),($row['picking_rank']==''?0:1),$action); 

      mysql_query($sql);
	
      $this->read('locations');
      $locations_data=$this->get('locations');
      return array(true,$locations_data);
      break;

    case('delete_all'):

      $sql=sprintf("delete from product2location  where product_id=%d",$this->id); 
      mysql_query($sql);
      break;

    case('desassociate_location'):
      $id=$data['p2l_id'];

      $sql=sprintf("select location.name,code,product2location.stock,product_id,location_id from product2location  left join product on (product.id=product_id) left join location on (location_id=location.id) where product2location.id=%d",$id); 
      //print $sql;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	if($row['product_id']!=$this->id)
	  return array(false,_('This location is no associated with the product'));
	if($row['stock']>0 and $row['location_id']!=1)
	  return array(false,_('There is still products in the location'));
	$stock=$row['stock'];
	$location_id=$row['location_id'];
	$product_code=$row['code'];
	$location_name=$row['name'];
      }else
	return array(false,_('This location is no associated with the product'));
      // del




      $this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>0,'user_id'=>'','no_history'=>true));
      
      // procced to delete
      $sql=sprintf("delete from product2location  where id=%d",$id); 

      mysql_query($sql);
      
      if(!isset($data['no_history'])){
	$user_id=$data['user_id'];
	$msg=$data['msg'];
	$date='NOW()';
	
	
	if($location_id==1){
	  if($stock>0){
	    $note=number($stock)." "._('outers lost, its location was never identificated;').' '.stripslashes($msg);
	    $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'P2L',%d,'STK',%d,%s,'%s','%s')",$date,$this->id,$id,$user_id,prepare_mysql($note),$stock,0); 
	    mysql_query($sql);
	  }
      }
	else{
	  $note=$product_code." "._('no longer located on')." $location_name";
	  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note) values (%s,'PROD',%d,'LOC',%d,'DEL',%d,%s)",$date,$this->id,$location_id,$user_id,prepare_mysql($note)); 
	  mysql_query($sql);
	}
      }
      $this->set_stock();
      $this->read('locations');
      $locations_data=$this->get('locations');
      return array(true,$locations_data);
      break;

  case('associate_location'):
      $location_name=$data['location_name'];
      $can_pick=$data['can_pick'];
      $is_primary=$data['is_primary'];
      $user_id=$data['user_id'];
      $date='NOW()';
      $sql=sprintf("select id,tipo,name from location  where name like %s",prepare_mysql($location_name)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$location_id=$row['id'];
	$location_name=$row['name'];
	$location_tipo=$row['tipo'];
      }else
	return array(false,_('No such location'));
      
      $sql=sprintf("select id from product2location  where location_id=%d and product_id=%d",$location_id,$this->id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	return array(false,_('This product is already on this location'));
      }
      $sql=sprintf("select code from product where id=%d",$this->id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$product_code=$row['code'];
      }
      


      //    print_r($data);
      //print "y $can_pick x $is_primary   ";
      $sql=sprintf("insert into product2location  (product_id,location_id) values (%d,%d)",$this->id,$location_id); 
      mysql_query($sql);
      $id=mysql_insert_id();
      $rank=0;
      if($can_pick){
	if($is_primary)
	  $rank=1;
	else
	  $rank=99999999;

	$this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>$rank,'user_id'=>$user_id,'no_history'=>true));
      }
      
      $this->read(array('locations'));
      $locations_data=$this->get('locations');
      
      
      if($locations_data['num_physical']>1)
	$note=$product_code." "._('is also located on')." $location_name" ;
      else
	$note=$product_code." "._('is located on')." $location_name" ;



      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note) values (%s,'PROD',%d,'LOC',%d,'NEW',%d,'%s')",$date,$this->id,$location_id,$user_id,$note); 
      mysql_query($sql);

	
      $this->read(array('locations'));
      $locations_data=$this->get('locations');
      return array(true,$locations_data,$location_id,$location_name,$location_tipo,$rank,($rank==1?getOrdinal(1):getOrdinal($locations_data['num_physical'])) ,$id);
  
      break;
      

    case('damaged_stock'):
      $from_id=$data['from'];
      $qty=$data['qty'];
      $user_id=$data['user_id'];

      $message=$data['message'];
      $date='NOW()';
      if($qty<=0)
	return array(false,_('Check the number of outers'));
      // check of posible
      $sql=sprintf("select location_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$from_id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$from_name=$row['name'];
	$from_qty=$row['stock'];
	$location_id=$row['location_id'];
      }
      if($qty>$from_qty)
	return array(false,_('Can not move so many outers'));
      
      $sql=sprintf("update product2location set stock=%s where id=%d",$from_qty-$qty,$from_id); 

      mysql_query($sql);
      
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note) values (%s,'PROD',%d,'LOC',%d,'DAM',%d,%s)",$date,$this->id,$location_id,$user_id,
		   prepare_mysql($qty.' '.ngettext('outer','outers',$qty).' '._('damaged').'; '.stripslashes($message))
		   ); 
      mysql_query($sql);


      $this->set_stock();
      $this->read(array('locations'));
      $locations_data=$this->get('locations');
      return array(true,$locations_data);

      break;
      
    case('move_stock'):
      $from_id=$data['from'];
      $to_id=$data['to'];
      $qty=$data['qty'];
      $user_id=$data['user_id'];

      $date='NOW()';
      if($qty<=0)
	return array(false,_('Check the number of outers'));

      // check of posible
      $sql=sprintf("select location_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$from_id); 

      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$from_name=$row['name'];
	$from_qty=$row['stock'];	
	$from_location_id=$row['location_id'];
      }
      if($qty>$from_qty)
	return array(false,_('Can not move so many outers'));
      

      $sql=sprintf("select location_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$to_id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$to_name=$row['name'];
	$to_qty=$row['stock'];
	$to_location_id=$row['location_id'];
      }

      $sql=sprintf("update product2location set stock=%s where id=%d",$from_qty-$qty,$from_id); 
      // print "$sql";
            mysql_query($sql);
      $sql=sprintf("update product2location set stock=%s where id=%d",$to_qty+$qty,$to_id); 
      // print "$sql";
      mysql_query($sql);
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOF',%d,'%s',%d,%d)",$date,$this->id,$from_location__id,$user_id,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name,$from_qty-$qty); 
      mysql_query($sql);
      
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOT',%d,'%s',%d,%d)",$date,$this->id,$to_location_id,$user_id,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name,$to_qty+$qty); 
      mysql_query($sql);
      
      
      //   return array(false,$sql);
      
      $this->read(array('locations'));
      $locations_data=$this->get('locations');

      return array(true,$locations_data);
      break;
      
    case('move_stock_to'):
      $from_id=$data['from_id'];
      $to_name=stripslashes($data['to_name']);
      $qty=$data['qty'];
      $user_id=$data['user_id'];
      $date='NOW()';
      
      if($qty<=0)
	return array(false,_('Check the number of outers'));

      
      $sql=sprintf("select location_id,product_id,location.name,stock from product2location left join location on (location.id=location_id) where product2location.id=%d",$from_id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	if($row['product_id']!=$this->id)
	  return array(false,_('There this product is not in this location'));
	$from_name=$row['name'];
	$from_qty=$row['stock'];	
	$from_location_id=$row['location_id'];
      }
      if($qty>$from_qty)
	return array(false,_('Can not move so many outers'));
      
      
      $sql=sprintf("select  stock,product2location.id from product2location left join location on (location.id=location_id) where product_id=%d and location.name=%s",$this->id,prepare_mysql($to_name)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$to_id=$row['id'];

      }else{
	// associate to new location
	$new_loc_data=array(
			    'location_name'=>$to_name,
			    'can_pick'=>true,
			    'is_primary'=>false,
			    'user_id'=>$user_id,
			    'tipo'=>'associate_location'
			    );
	$res=$this->update_location($new_loc_data);
	if(!$res[0])
	  return array(false,$res[1]);
	$sql=sprintf("select  stock,product2location.id from product2location left join location on (location.id=location_id) where product_id=%d and location.name=%s",$this->id,prepare_mysql($to_name)); 

	$result2 =& $this->db->query($sql);
	if($row2=$result2->fetchRow()){
	   $to_id=$row2['id'];
	 }else
	   return array(false,_('Could not associate new location'));


      }



      $sql=sprintf("select stock,location_id,location.name,product2location.id from product2location left join location on (location.id=location_id) where product2location.id=%d",$to_id); 

      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$to_name=$row['name'];
	$to_id=$row['id'];
	$to_qty=$row['stock'];
	$to_location_id=$row['location_id'];
      }else
	return array(false,_('Error on  new location'));



      $sql=sprintf("update product2location set stock=%s where id=%d",$from_qty-$qty,$from_id); 
      // print "$sql";
      mysql_query($sql);
      $sql=sprintf("update product2location set stock=%s where id=%d",$to_qty+$qty,$to_id); 
      // print "$sql";
      mysql_query($sql);
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOF',%d,'%s',%d,%d)",$date,$this->id,$from_location_id,$user_id,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name,$from_qty,$from_qty-$qty); 
      mysql_query($sql);
      $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,note,old_value,new_value) values (%s,'PROD',%d,'LOC',%d,'MOT',%d,'%s',%d,%d)",$date,$this->id,$to_location_id,$user_id,$qty.' '._('outers has been moved from').' '.$from_name.' '._('to').' '.$to_name,$to_qty,$to_qty+$qty); 
      mysql_query($sql);
      


      
      $this->read(array('locations'));
      $locations_data=$this->get('locations');

      return array(true,$locations_data);
      break;



    }

  }


  function update($values,$args=''){
    


    $date='NOW()';
    $res=array();
    foreach($values as $data){
      
      
      $key=$data['key'];
      $value=$data['value'];
      $res[$key]=array('res'=>0,'new_value'=>'','desc'=>'Unkwown Error');
      
      
      
      
      switch($key){
	//Must be numeric
      case('units'):
      case('price'):
      case('weight'):
      case('oweight'):
      case('cweight'):
	if($this->data[$key]==$value)
	  continue;
	if(!is_numeric($value)){
	  $res[$key]['desc']=_('Value is not numeric');
	  continue;
	}
	$this->data[$key]=$value;
	break;
	//Must be alpha & not null
      case('description'):
      case('sdescription'):
	if($this->data[$key]==$value)
	  continue;
	if($value==''){
	  $res[$key]['desc']=_('Value Required');
	  continue;
	}
	if(!preg_match('/[a-z]/i',$value)){
	  $res[$key]['desc']=_('Not Valid Value');
	  continue;
	}
	$this->data[$key]=$value;
	break;
            
      case('details'):
	if($this->data[$key]==$value)
	  continue;
	$this->data[$key]=$value;
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
      
	if($key=='dim')
	  $preffix='';
	else
	  $preffix='o';
	
	if(!preg_match('/^shape\d\_/',$value)){
	  $res[$key]=array('res'=>2,'desc'=>'Wrong_data');
	  continue;
	}
	
	list($tipo,$dims)=preg_split('/_/',$value);
	$tipo=preg_replace('/^shape/','',$tipo);
	$_dims=preg_split('/x/',$dims);
	
	$this->data[$key]=$dims;
	$this->data[$key.'_tipo']=$tipo;
      
	break;
	default:
	
	$res[$key]=array('res'=>2,'new_value'=>'','desc'=>'Unkwown key');
      }
      if(preg_match('/save/',$args)){

	$this->save($key);
	
      }
    }
    return $res;
  }


  function save($tipo,$history_data=false){
    switch($tipo){

       

    case('first_date'):
       $old_value=$this->read($tipo);

      if(is_numeric($this->data['first_date'])){
	$date=date("Y-m-d H:i:s",strtotime("@".$this->data['first_date']));

	$sql=sprintf("update product set first_date=%s  where  id=%d"
		     ,prepare_mysql($date),$this->id);
	//	print "$sql\n";
	$this->db->exec($sql);
	
	if(is_array($history_data)){
	  $this->save_history($tipo,$old_value,$history_data);
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
    default:
      
      $old_value=$this->read($tipo);
      // print "$old_value ".$this->data[$tipo]." \n";

      if($old_value!=$this->data[$tipo]){
	$sql=sprintf("update product set %s=%s where id=%d",$tipo,prepare_mysql($this->data[$tipo]),$this->id);

	$this->db->exec($sql);
      }

      if(is_array($history_data)){
	$this->save_history($tipo,$old_value,$history_data);
      }
      
      break; 
    }

  }







  function create($data){


    if(!is_numeric($data['group_id']) or $data['group_id']<=0 )
      return array('ok'=>false,'msg'=>_("Wrong group id"));
    if($data['code']=='' )
      return array('ok'=>false,'msg'=>_("Wrong product code"));
    if($data['description']=='' )
      return array('ok'=>false,'msg'=>_("Wrong description, it can't be empty"));
    
    $sql=sprintf("select id from product_group where id=%d"
		 ,$data['group_id']);
     $res = $this->db->query($sql); 
     if(!$tmp=$res->fetchRow()){
       return array('ok'=>false,'msg'=>_("The product group don't exist"));
     }
     
     $sql=sprintf("select id from product where code=%s "
		  ,prepare_mysql($data['code'])
		  );
     $res = $this->db->query($sql); 
     if($tmp=$res->fetchRow()){
       return array(
		    'ok'=>false
		    ,'msg'=>_('There is other product family with the same name/description')
		  );
     }
     
     $code=$data['code'];
     $ncode=$this->normalize_code($code);
     $rpp=(isset($data['rrp']) and is_numeric($data['rrp'])?$data['rrp']:'NULL');
     

     if( isset($data['rrp']) and is_numeric($data['rrp'])    )
       $rrp=$data['rrp'];
     else
       $rrp='NULL';
     
     if( isset($data['units_tipo']) and is_numeric($data['units_tipo'])   and $data['units_tipo']>0 )
       $units_tipo=$data['units_tipo'];
     else
      $units_tipo=1;
     
     if( isset($data['units']) and is_numeric($data['units'])   and $data['units']>0 )
       $units=$data['units'];
     else
       $units=1;

     
     if( isset($data['sdescription'])  and $data['sdescription']!='' )
       $sdescription=$data['sdescription'];
     else
       $sdescription=$data['description'];
     


     
    $sale_status=(
		  (
		   !isset($data['sale_status']) 
		   or $data['sale_status']!='normal'
		   or $data['sale_status']!='nosale'
		   or $data['sale_status']!='discontinued'
		   )?'nosale':$data['sale_status']);
    $sql=sprintf("insert into  product (sale_status,ncode,rrp,units,units_tipo,price,description,sdescription,code,group_id) values (%s,%s,%s,%.3f,%d,%.2f,%s,%s,%s,%d)",
		 prepare_mysql($sale_status),
		 prepare_mysql($ncode),
		 $rrp,
		 $units,
		 $units_tipo,
		 
		 $data['price'],
		 prepare_mysql($data['description']),
		 prepare_mysql($sdescription),
		 prepare_mysql($data['code']),
		 $data['group_id']

		 );
    
    //  print "$sql\n";

    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {
      if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	return array('ok'=>false,'msg'=>_('Error: Another product has the same code').'.');
      else
	return array('ok'=>false,'msg'=>_('Unknwon Error').'.');
    }
    $this->id = $this->db->lastInsertID();
    $this->data['ncode']=$ncode;	
    $this->data['code']=$data['code'];
    $this->data['group_id']=$data['group_id'];
    $this->data['units']=$units;
    $this->data['units_tipo']=$units_tipo;
    $this->data['price']=$data['price'];

    $this->data['rrp']=$rrp;
    $this->data['description']=$data['description'];
    $this->data['sdescription']=$data['sdescription'];

    return array('ok'=>true);

    //$this->fix_todotransaction();
    //$this->set_stock(true);
    //$this->set_sales(true);
  }
  
  



  function normalize_code($code){
    $ncode=$code;
    $c=split('-',$code);
    if(count($c)==2){
      if(is_numeric($c[1]))
	 $ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
      else
	$ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
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
      $this->read('stock_forecast');
      
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


?>
