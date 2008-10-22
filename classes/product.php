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

  

  function __construct() {
    $this->db =MDB2::singleton();
    //  $product=array(
// 		     'id'=>'',
// 		     'group_id'=>0,
// 		     'code'=>'',
// 		     'n_code'=>'',
// 		     'description'=>'';
// 		     'description_med'=>'';
// 		     'details'=>'',
// 		     'units'=>0.0,
// 		     'units_tipo'=>1,
// 		     'has_child'=>0,
// 		     'has_parent'=>0,
// 		     'stock'=>'';
// 		     'stock_value'=>0.0,
// 		     'available'=>'',
// 		     'stock_units'=>1,
// 		     'first_date'=>'',
// 		     'awoutall'=>0,
// 		     'awouty'=>0,
// 		     'awoutq'=>0,
// 		     'awoutm'=>0,
// 		     'awtsall'=>0,
// 		     'awtsq'=>0,
// 		     'outall'=>0,
// 		     'outy'=>0,
// 		     'outq'=>0,
// 		     'outm'=>0,
// 		     'outw'=>0,
// 		     'tsall'=>0,
// 		     'tsy'=>0,
// 		     'tsq'=>0,
// 		     'tsm'=>0,
// 		     'tsw'=>0,
// 		     'weight'=>'',
// 		     'dim'=>'',
// 		     'dim_type'=>1,
// 		     'oweight'=>'',
// 		     'odim'=>'',
// 		     'odim_type'=>1,
// 		     'export_code'=>'',
// 		     'condicion'=>0
// 			);
  }
  
  
  function read($data_to_be_read){

    foreach($data_to_be_read as $table=>$id){

      switch($table){

      case('product_info'):
	$sql=sprintf("select * from product where id=%d",$id);
	$result =& $this->db->query($sql);
	$this->product=$result->fetchRow();   
	break;
      case('product_tree'):
	$sql=sprintf('select d.name as department,d.id as department_id,g.name as group_name,group_id from product left join product_group as g on (g.id=group_id)  left join product_department as d on (d.id=department_id) where product.id=%s ',$id);

	$res = $this->db->query($sql); 
	if ($row=$res->fetchRow()) {
	  $this->group_id=$row['group_id'];
	  $this->department_id=$row['department_id'];
	  $this->group=$row['group_name'];
	  $this->department=$row['department'];
	}
	break;
      case('locations'):
	global $_location_name;
	$this->locations=array('has_unknown'=>false,'has_white_hole'=>false,'has_picking_area'=>false,'has_locations'=>false,'data'=>array());
	$sql=sprintf("select name,product2location.id as id,location_id,stock,picking_rank,tipo,stock  from product2location left join location on (location_id=location.id) where product_id=%d",$id);
	$result =& $this->db->query($sql);
	while($row=$result->fetchRow()){
	  

	  $icon='';
	  if($row['tipo']=='unknown'){
	    $this->location['has_unknown']=true;
	    $name=$_location_name[$row['name']];
	    $icon='<img src="art/icons/exclamation.png" alt="'._('Unknown').'" title="'._('Unknown location').'" />';
	  }else if($row['tipo']=='white_hole'){
	    $this->location['has_white_hole']=true;
	    $name=$_location_name[$row['name']];
	  }else{
	    $this->location['has_locations']=true;
	    $name=$row['name'];
	    
	    if(is_numeric($row['picking_rank']) and $row['picking_rank']>0){
	      $this->location['has_picking_area']=true;
	      if($row['tipo']=='picking')
		$icon='<img src="art/icons/basket.png" alt="'._('Picking Area').'" title="'._('Picking Area').'" />';
	      else if($row['tipo']=='storing')
		$icon='<img src="art/icons/package.png"  alt="'._('Storing Area').'" title="'._('Storing Area').'"  />';
	    }else{
	      if($row['tipo']=='picking')
		$icon='<img src="art/icons/basket_delete.png" />';
	      else if($row['tipo']=='storing')
		$icon='<img src="art/icons/package_delete.png" />';
	      
	    }
	    
	    

	    
	  }
	  
	  

	  $this->locations['data'][]=array(
					   'id'=>$row['id'],
					   'name'=>$name,
					   'location_id'=>$row['location_id'],
					   'stock'=>$row['stock'],
					   'tipo'=>$row['tipo'],
					   'picking_rank'=>$row['picking_rank'],
					   'icon'=>$icon
					   );

	}

	
      case('suppliers'):
	$this->suppliers=array();
	$sql=sprintf("select p2s.supplier_id, p2s.price,p2s.sup_code as code,s.name as name from product2supplier as p2s left join supplier as s on (p2s.supplier_id=s.id) where p2s.product_id=%d",$id);
	
	$result =& $this->db->query($sql);
	//$supplier=array();
	$this->suppliers['name']=array();
	$this->suppliers['price']=array();
	$this->suppliers['code']=array();
	while($row=$result->fetchRow()){
	  $this->suppliers['name'][$row['supplier_id']]=$row['name'];
	  $this->suppliers['price'][$row['supplier_id']]=money($row['price']);
	  $this->suppliers['code'][$row['supplier_id']]=$row['code'];
	}

	$this->suppliers['number']=count($this->suppliers['name']);
	break;
	// print_r($this->suppliers);
      case('categories'):
	$this->categories['list']=array();
	$sql=sprintf("select cat_id,name from product2cat left join cat on (cat_id=cat.id) where product_id=%d ",$id);
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
	$sql=sprintf("select filename,format,principal,caption,id from image where  product_id=%d order by principal desc",$id);
	
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
	  $old_value=$this->product[$key];
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
      if($this->product['dim']!='')
	$a_dim=array($this->product['dim']);
      split('x',$this->product['dim']);
    case('first_date'):
       return strftime("%e %B %Y", strtotime($this->product['first_date']));
       break;
    case('weeks_since'):
      return (date("U")-date("U", strtotime($this->product['first_date'])))/604800;
      
      break;
    case('number_of_suppliers'):
      return  $this->suppliers['number'];
    case('supplier_name'):
      return  $this->suppliers['name'];
    case('supplier_code'):
      return  $this->suppliers['code'];
    case('supplier_price'):
      return  $this->suppliers['price'];
    default:

      if(isset($this->product[$item]))
	return $this->product[$item];
      elseif(isset($this->$item))
	return $this->$item;
      else 
	return false;
    }

  }
    
  function update($values){
    if(!isset($this->product['id']))
      return false;
    
    $res=array();
    foreach($values as $key=>$value){
      $res[$key]=array('res'=>0,'new_value'=>'','desc'=>'Unkwown Error');
      switch($key){
      case('description'):
      case('sdescription'):
	if($this->product[$key]!=$value){
	  
	  if($value==''){
	    $res[$key]['desc']=_('Value Required');
	    break;
	  }
	  if(!preg_match('/[a-z]/i',$value)){
	      $res[$key]['desc']=_('Not Valid Value');
	      break;
	  }

	  $sql=sprintf("update product set %s=%s where id=%d",$key,prepare_mysql($value),$this->product['id']);
	  	  mysql_query($sql);

	  $res[$key]=array('res'=>1,'new_value'=>$value);
	}else
	  $res[$key]=array('res'=>2,'new_value'=>'','desc'=>'No changed');
	
	break;
      case('cat'):
	$cats=split($value);
	
	// delete all cats
	$sql=sprintf("delete from cat  where product_id=%d",$this->product['id']);
	mysql_query($sql);
	foreach($cats as $cat){
	  $sql=sprintf("insert into cat  (cat_id,product_id) values (%d,%d)",$cat,$this->product['id']);
	  mysql_query($sql);
	}
	$res[$key]=array('res'=>1,'new_value'=>join('-',$value));
	break;
      case('details'):
	$sql=sprintf("update product set %s=%s where id=%d",$key,prepare_mysql($value),$this->product['id']);
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
    $product_id = $this->db->lastInsertID();
    $sql=sprintf("insert into inventory(fuzzy,date_start,date_end,name) values (1,NOW,NOW,'%s',)",_('New product'));
    $this->db->exec($sql);
    $inv_id = $this->db->lastInsertID();
    $sql=sprintf("insert into inventory_item (product_id,inventory_id,fecha) values (%d,,%d,NOW)", $product_id,$inv_id);
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
    
    
    $sql=sprintf("select * from todo_transaction where code like '%s' ",addslashes($this->product['code']));
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
		   ,$order_id,$product_id,$ordered,$dispached,$discount,$promotion_id,$charge);
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
	  $this->product['stock'],
	  $this->product['available'],
	  $this->product['stock_value']
	  )=$this->get_stock();
    
    if($update_database){
      $sql=sprintf('update product set stock=%.2f, available=%.2f, stock_value=%.3f where id=%d'
		   , $this->product['stock']
		   ,$this->product['available']
		   ,$this->product['stock_value']
		   ,$this->product['id']
		   );
      $this->db->exec($sql);
    }
    
  }

function get_stock($date=''){
  
  $product_id=$this->product['id'];
  if($date=='')
    $date='NOW()';
  else
    $date="'".addslashes($date)."'";
  
  
  $sql=sprintf("select stock,available,value from stock_history where  product_id=%d and op_date<%s order by op_date desc limit 1",$product_id,$date);
 //print $sql;
  $res = $this->db->query($sql); 
  
  $s='';$a='';$v='';
 if ($row = $res->fetchRow() ) {
   $s=$row['stock'];
   $a=$row['available'];
   $v=$row['value'];
 }
 return array($s,$a,$v);
}



 function set_sales($update_database=true,$update_fam=false){

   $product_id=$this->product['id'];
   if(is_numeric($product_id)){
     $total_sales=0;
    $y_sales=0;
    $q_sales=0;
    $m_sales=0;
    $w_sales=0;



    $sql=sprintf("select  sum(charge) as sales  ,sum(dispached) as outers from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2    ",$product_id);
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      $total_sales=number_format($row['sales'],2,'.','');
      $total_outers=number_format($row['outers'],2,'.','');

    }
    
    $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 YEAR) <= date_index   ",$product_id);
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      $y_sales=number_format($row['sales'],2,'.','');
      $y_outers=number_format($row['outers'],2,'.','');

    }
    
    $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 3 MONTH) <= date_index   ",$product_id);
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      $q_sales=number_format($row['sales'],2,'.','');
      $q_outers=number_format($row['outers'],2,'.','');

    }
    
      $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 MONTH) <= date_index   ",$product_id);
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      $m_sales=number_format($row['sales'],2,'.','');
      $m_outers=number_format($row['outers'],2,'.','');

    }
    
      $sql=sprintf("select  sum(charge) as sales  ,sum(dispached) as outers from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 WEEK) <= date_index   ",$product_id);
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      $w_sales=number_format($row['sales'],2,'.','');
      $w_outers=number_format($row['outers'],2,'.','');
      

    }
    

    $awsall=0;
    $awtsall=0;

    $sql=sprintf("select   (TO_DAYS(NOW())-TO_DAYS(first_date))  as days from product     where product.id=%d    ",$product_id);
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()) {
      
      $days=$row['days'];
      
      if($days>0){
	$awsall=7*$total_outers/$days;
	$awtsall=7*$total_sales/$days;
	//	print "$awtsall $days ";
      }


    }
    
    
    $awsq=number_format(($q_outers/13.00),2,'.','');
    $awtsq=number_format(($q_sales/13.00),2,'.','');


    $awsall=number_format($awsall,2,'.','');
    $awtsall=number_format($awtsall,2,'.','');


    $sql=sprintf("update product set awoutq=%s , awoutall=%s, outall=%s ,outq=%s ,outm=%s ,outw=%s ,outy=%s, awtsq=%s , awtsall=%s, tsall=%s ,tsq=%s ,tsm=%s ,tsw=%s ,tsy=%s where id=%d",
		 $awsq,$awsall,$total_outers,$q_outers,$m_outers,$w_outers,$y_outers,
		 $awtsq,$awtsall,$total_sales,$q_sales,$m_sales,$w_sales,$y_sales,$product_id);
    //print "$sql\n";
    $this->db->exec($sql);
 
    if($update_fam)
      $this->update_family(true);

   }







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
  $family_id=$this->product['group_id'];
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
