<?



include_once('common/string.php');



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

  

  function __construct($id=false) {
    $this->db =MDB2::singleton();


    if(is_numeric($id)){
      $this->id=$id;
      $this->read(array('product_info'));
    }

  }
  
  
  function read($data_to_be_read){

    if(!is_array($data_to_be_read))
      $data_to_be_read=array($data_to_be_read);
    foreach($data_to_be_read as $table){

      switch($table){

      case('product_info'):
	$sql=sprintf("select *,UNIX_TIMESTAMP(first_date) as ts_first_date from product where id=%d",$this->id);

	$result =& $this->db->query($sql);
	$this->data=$result->fetchRow();
	if($this->data['ts_first_date']!='')
	  $this->dates=array('ts_first_date'=>$this->data['ts_first_date']);
	else
	  $this->read('first_date');

	break;
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
	$_data=array();
	$this->locations=array('has_display'=>false,'has_unknown'=>false,'has_loading'=>false,'has_white_hole'=>false,'has_picking_area'=>false,'has_physical'=>false,'data'=>array(),'num_physical'=>0,'num_physical_with_stock'=>0,'num_picking_areas'=>0);
	$sql=sprintf("select name,product2location.id as id,location_id,stock,picking_rank,tipo,stock  from product2location left join location on (location_id=location.id) where product_id=%d and picking_rank is not null order by picking_rank  ",$this->id);
	$result =& $this->db->query($sql);
	while($row=$result->fetchRow()){
	  $_data[$row['id']]=array(
				   'id'=>$row['id'],
				   'name'=>$row['name'],
				   'location_id'=>$row['location_id'],
				   'stock'=>$row['stock'],
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
	


	$sql=sprintf("select name,product2location.id as id,location_id,stock,picking_rank,tipo,stock  from product2location left join location on (location_id=location.id) where product_id=%d and picking_rank is  null order by tipo desc  ",$this->id);
	$result =& $this->db->query($sql);
	while($row=$result->fetchRow()){
	  
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
					   'stock'=>$row['stock'],
					   'tipo'=>$tipo,
					   'picking_tipo'=>$picking_tipo,
					   'picking_rank'=>'',
					   'is_physical'=>$is_physical,
					   'can_pick'=>$can_pick,
					   'has_stock'=>($row['stock']>0?true:false)
					   );

	}
	$sql=sprintf("select stock from product where id=%d",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $this->locations['stock']=number($row['stock']);
	}
	$this->locations['data']=$_data;
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
      case('categories'):
	$this->categories['list']=array();
	$sql=sprintf("select cat_id,name from product2cat left join cat on (cat_id=cat.id) where product_id=%d ",$this->id);
	$this->suppliers['list']=array();
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

	$res =& $this->db->query($sql);

	if ($row=$res->fetchRow()) {
	  $date=$row['date_creation'];
	  $ts_date=$row['ts_date_creation'];;

	}else{
	  $date=date('Y-m-d H:i:s');
	  $ts_date=date('U');
	}
	
	$this->data['first_date']=$date;
	$this->dates['first_date']=$ts_date;
	$sql=sprintf("update product set first_date='%s' where (first_date>'%s' or  first_date is null )  and id=%d",$date,$date,$this->id);
	$this->db->exec($sql);


	break;
      case('sales_metadata'):
	$_diff_seconds=date('U')-$this->dates['ts_first_date'];
	$date_diff=$_diff_seconds/24/3600;
	$weeks=$date_diff/7;

	$sql=sprintf("update product set  tsoall=0,tsall=0,tdall=0,awtsoall=NULL, awtsoy=NULL,  awtsoq=NULL, awtsom=NULL, awtsall=NULL, awtsy=NULL, awtsq=NULL,  awtsm=NULL, awtdall=NULL, awtdy=NULL, awtdq=NULL, awtdm=NULL, tsoy=NULL, tsoq=NULL,  tsom=NULL, tsoq=NULL, tsom=NULL, tsow=NULL, tsw=NULL, tsm=NULL, tsq=NULL, tsy=NULL, tdy=NULL, tdq=NULL, tdm=NULL, tdw=NULL  where id=%d",$this->id);
	mysql_query($sql);


	

	$tsall=0;
	$tsoall=0;
	$tdall=0;
	$sql=sprintf("select  sum(charge) as sales  ,sum(dispached) as outers from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2    ",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $tsall=$row['sales'];
	  $tsoall=$row['outers'];
	  

	  $sql=sprintf("update product set tsall=%s,tsoall=%s where id=%d",$tsall,$tsoall,$this->id);
	  mysql_query($sql);
	  
	  if($weeks>=1){
	    $awtsall=$row['sales']/$weeks;
	    $awtsoall=$row['outers']/$weeks;
	     $sql=sprintf("update product set awtsall=%s,awtsoall=%s where id=%d",$awtsall,$awtsoall,$this->id);
	     mysql_query($sql);
	  }

	}
	$sql=sprintf("select  sum(dispached) as outers from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d   and (o.tipo!=3 or o.tipo!=7 or o.tipo!=9  or o.tipo!=10  )     ",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $tdall=$row['outers'];
	  $sql=sprintf("update product set tdall=%s where id=%d",$tdall,$this->id);
	  mysql_query($sql);
	  
	  if($weeks>=1){
	    $awtdall=$row['outers']/$weeks;
	    $sql=sprintf("update product set awtdall=%s where id=%d",$awtdall,$this->id);
	    mysql_query($sql);
	    
	  }

	}
	

	$date_diff=date('U')-$this->dates['ts_first_date']/24/3600;
	if($date_diff>=365){
	  $tsy=0;
	  $tsoy=0;
	  $awtsy=0;
	  $awtsoy=0;
	  $tdy=0;
	  $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 YEAR) <= date_index   ",$this->id);
	  $result =& $this->db->query($sql);
	  if($row=$result->fetchRow()){
	    $tsy=$row['sales'];
	    $tsoy=$row['outers'];
	    $awtsy=$tsy/52.17857142;
	    $awtsoy=$tsoy/52.17857142;
	    $sql=sprintf("update product set tsy=%s,tsoy=%s,awtsy=%s,awtsoy=%s where id=%d",$tsy,$tsoy,$awtsy,$awtsoy,$this->id);
	    mysql_query($sql);
	  }
	  $sql=sprintf("select  sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo!=3 or o.tipo!=7 or o.tipo!=9  or o.tipo!=10  )   and DATE_SUB(CURDATE(),INTERVAL 1 YEAR) <= date_index   ",$this->id);
	  $result =& $this->db->query($sql);
	  if($row=$result->fetchRow()){
	    $tdy=$row['outers'];
	    $awtdy=$row['outers']/52.17857142;;
	    $sql=sprintf("update product set tdy=%s , awtdy=%s   where id=%d",$tdy,$awtdy,$this->id);
	    mysql_query($sql);
	  }


	}
	if($date_diff>=89){
	  $tsq=0;
	  $tsoq=0;
	  $awtsq=0;
	  $awtsoq=0;
	  $tdq=0;
	  
	  $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 3 MONTH) <= date_index   ",$this->id);
	  $result =& $this->db->query($sql);
	  if($row=$result->fetchRow()){
	    
	    $tsq=$row['sales'];
	    $tsoq=$row['outers'];
	    $awtsq=$tsq/13.044642857;
	    $awtsoq=$tsoq/13.044642857;
	    $sql=sprintf("update product set tsq=%s,tsoq=%s,awtsq=%s,awtsoq=%s where id=%d",$tsq,$tsoq,$awtsq,$awtsoq,$this->id);
	    mysql_query($sql);
	  }
	  $sql=sprintf("select  sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo!=3 or o.tipo!=7 or o.tipo!=9  or o.tipo!=10  )   and DATE_SUB(CURDATE(),INTERVAL 3 MONTH) <= date_index   ",$this->id);
	  $result =& $this->db->query($sql);
	  if($row=$result->fetchRow()){
	    $tdq=$row['outers'];
	    $awtdq=$row['outers']/13.044642857;
	    $sql=sprintf("update product set tdq=%s ,awtdq=%s  where id=%d",$tdq,$awtdq,$this->id);
	    mysql_query($sql);
	  }
	  
	}

	if($date_diff>=30){
	  $tsm=0;
	  $tsom=0;
	  $awtsm=0;
	  $awtsom=0;
	  $tdm=0;

	$sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 MONTH) <= date_index   ",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $tsm=$row['sales'];
	  $tsom=$row['outers'];
	  $awtsm=$tsm/4.348214286;
	  $awtsom=$tsom/4.348214286;
	  $sql=sprintf("update product set tsm=%s,tsom=%s,awtsm=%s,awtsom=%s where id=%d",$tsm,$tsom,$awtsm,$awtsom,$this->id);
	  
	  mysql_query($sql);
	}


	$sql=sprintf("select  sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo!=3 or o.tipo!=7 or o.tipo!=9  or o.tipo!=10  )   and DATE_SUB(CURDATE(),INTERVAL 1  MONTH) <= date_index   ",$this->id);
	  $result =& $this->db->query($sql);
	  if($row=$result->fetchRow()){
	    $tdm=$row['outers'];
	    $awtdm=$row['outers']/4.348214286;
	    $sql=sprintf("update product set tdm=%s,  awtdm=%s  where id=%d",$tdm,$awtdm,$this->id);
	    mysql_query($sql);
	  }
	
	}
	
	if($date_diff>5){
	  $tsw=0;
	  $tsow=0;
	  $tdw=0;
	  
	  $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 WEEK) <= date_index   ",$this->id);
	  $result =& $this->db->query($sql);
	  if($row=$result->fetchRow()){
	    $tsw=$row['sales'];
	  $tsow=$row['outers'];
	  $sql=sprintf("update product set tsw=%s,tsow=%s where id=%d",$tsw,$tsow,$this->id);
	  mysql_query($sql);
	  }

	  $sql=sprintf("select  sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and (o.tipo!=3 or o.tipo!=7 or o.tipo!=9  or o.tipo!=10  )   and DATE_SUB(CURDATE(),INTERVAL 1  WEEK) <= date_index   ",$this->id);
	  $result =& $this->db->query($sql);
	  if($row=$result->fetchRow()){
	    $tdw=$row['outers'];
	    $sql=sprintf("update product set tdw=%s  where id=%d",$tdm,$this->id);
	    mysql_query($sql);
	  }


	}

	

	$this->read('product_info');

	break;
      case('parents'):
	break;
      case('children'):
	break;
      case('notes'):
	break;
	
      }
      
    }
  
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


  function get($item=''){

    switch($item){
    case('a_dim'):
      if($this->data['dim']!='')
	$a_dim=array($this->data['dim']);
      split('x',$this->data['dim']);
    case('first_date'):
       return strftime("%e %B %Y", strtotime($this->data['first_date']));
       break;
    case('weeks_since'):
      return (date("U")-date("U", strtotime($this->data['first_date'])))/604800;
      
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
    




  function update_location($data){
    switch($data['tipo']){
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

    case('desassociate_location'):
      $id=$data['p2l_id'];
      $user_id=$data['user_id'];
      $msg=$data['msg'];
      $date='NOW()';
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




      $this->update_location(array('tipo'=>'set_picking_rank','product2location_id'=>$id,'rank'=>0,'user_id'=>$user_id,'no_history'=>true));
      
      // procced to delete
      $sql=sprintf("delete from product2location  where id=%d",$id); 

      mysql_query($sql);
      
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


  function update($values){
    if(!isset($this->data['id']))
      return false;
    
    $res=array();
    foreach($values as $key=>$value){
      $res[$key]=array('res'=>0,'new_value'=>'','desc'=>'Unkwown Error');
      switch($key){
      case('description'):
      case('sdescription'):
	if($this->data[$key]!=$value){
	  
	  if($value==''){
	    $res[$key]['desc']=_('Value Required');
	    break;
	  }
	  if(!preg_match('/[a-z]/i',$value)){
	      $res[$key]['desc']=_('Not Valid Value');
	      break;
	  }

	  $sql=sprintf("update product set %s=%s where id=%d",$key,prepare_mysql($value),$this->data['id']);
	  	  mysql_query($sql);

	  $res[$key]=array('res'=>1,'new_value'=>$value);
	}else
	  $res[$key]=array('res'=>2,'new_value'=>'','desc'=>'No changed');
	
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
      case('details'):
	$sql=sprintf("update product set %s=%s where id=%d",$key,prepare_mysql($value),$this->data['id']);
	mysql_query($sql);
	$res[$key]=array('res'=>1,'new_value'=>$sql);
	break;
      default:
	$res[$key]=array('res'=>0,'new_value'=>'','desc'=>'No key');
      }
    }
      return $res;
    
    
  }
  function save_new($datos){
    
    


    $ncode=normalize_code($code);
    $rpp=(isset($datos['rrp']) and is_numeric($datos['rrp'])?$datos['rrp']:'NULL');
    $sql=sprintf("insert into  product (ncode,rrp,units,units_tipo,price,description,code,group_id,first_date) values ('%s',%s,%s,'%s',%d,'%s','%s','%s',%d,NOW())",
		 addslashes($ncode),
		 $rrp,
		 $datos['units'],
		 $datos['units_tipo'],
		 $datos['price'],
		 addslashes($datos['description']),
		 addslashes($datos['code']),

		 $datos['group_id']);
    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {
      if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	$this->error_msg=_('Error: Another product has the same code').'.';
      else
	$this->error_msg=_('Unknown Error').'.';
      return false;
    }
    $this->id = $this->db->lastInsertID();
    $sql=sprintf("insert into inventory(fuzzy,date_start,date_end,name) values (1,NOW,NOW,'%s',)",_('New product'));
    $this->db->exec($sql);
    $inv_id = $this->db->lastInsertID();
    $sql=sprintf("insert into inventory_item (product_id,inventory_id,fecha) values (%d,,%d,NOW)", $this->id,$inv_id);
    $this->db->exec($sql);
    $this->fix_todotransaction();
    $this->set_stock(true);
    $this->set_sales(true);
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


?>
