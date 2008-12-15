<?
class location{
  var $db;
  var $data=array();
  var $items=false;


  var $tipo;
  var $id=false;


  function __construct($arg1=false,$arg2=false,$tipo='shelf') {
     $this->db =MDB2::singleton();
     
     $this->tipo=$tipo;

     if($arg1=='new' and is_array($arg2)){
       $this->create($arg2);
       return;
     }

     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return;
     }
     $this->get_data($arg1,$arg2);
     


  }


  function create ($data){
    $name=$data['name'];
    $tipo=$data['tipo'];
    print_r($data);
     if($name=='')
       return array('ok'=>false,'msg'=>_('Wrong location name').'.');
    
    if(!($tipo=='picking' or $tipo=='storing' or $tipo=='loading' or $tipo=='display'))
       return array('ok'=>false,'msg'=>_('Wrong location tipo').'.');
    $sql=sprintf('insert into location (name,tipo) values(%s,%s)',prepare_mysql($name),prepare_mysql($tipo));
    print "$sql\n";
    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {
      if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	return array('ok'=>false,'msg'=>_('Error: Another product has the same code').'.');
	 else
	   return array('ok'=>false,'msg'=>_('Unknwon Error').'.');
    }
    $id = $this->db->lastInsertID();
    $this->get_data('id',$id);
  }

  function get_data($key,$tag){
    
    switch($this->tipo){
    case('shelf'):
      if($key=='id')
	$sql=sprintf("select location.id,(select count(*) from product2location where location_id=location.id ) as products ,max_heigth,deep,width,max_products,location.id,location.tipo,location.name,warehouse_area.name as area  from location   left join warehouse_area on (area_id=warehouse_area.id)");
      if($key=='id')
	$sql.=sprintf("where location.id=%d ",$tag);
      else if($key=='name')
	$sql.=sprintf("where  location.name=%s ",prepare_mysql($tag));

      else
	return;
      //      print $sql;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->id=$row['id'];
	$this->data['name']=$row['name'];
	$this->data['num_products']=$row['products'];
	$this->data['max_products']=$row['max_products'];
	$this->data['tipo']=$row['tipo'];
	$this->data['area']=$row['area'];
	$this->data['area_id']=$row['area_id'];
	$this->data['deep']=$row['deep'];
	$this->data['width']=$row['width'];
	$this->data['max_heigth']=$row['max_heigth'];
	$this->data['max_weight']=$row['max_weight'];


	if(
	   is_numeric($this->data['max_heigth']) 
	   and is_numeric($this->data['deep']) 
	   and is_numeric($this->data['width']) 
	   )$this->data['max_vol']=$this->data['max_heigth']*$this->data['width']*$this->data['deep']*0.001;
	else
	  $this->data['max_vol']='';
	

	

      }else
	$this->msg=_('Location do not exist');


    }
  }


  function update($data){
    foreach($data as $key =>$value)
      switch($key){
      case('name'):
	$name=_trim($value);
	
	if($name==''){
	  $this->msg=_('Wrong location name');
	  $this->update_ok=false;
	  return;
	}

	if($name==$this->get($tipo)){
	  $this->msg=_('Nothing to change');
	  $this->update_ok=false;
	  return;
	}

	$location=new Location('name',$value);
	if($location->id){
	  $this->msg=_('Name already exists');
	  $this->update_ok=false;
	  return;
	}
	$this->data['name']=$name;
	$this->msg=_('Location name change');
	$this->update_ok=true;
	break;
   case('max_weight'):
     $value=_trim($value);
     
     if(!is_numeric($value)){
       $this->msg=_('The maximum weight for this location show be numeric');
       $this->update_ok=false;
	  return;
     }
     if($value < 0){
       $this->msg=_('The maximum weight can not be negative');
       $this->update_ok=false;
       return;
     }
     if($value < 0){
	  $this->msg=_('The maximum weight can not be zero');
	  $this->update_ok=false;
	  return;
     }
     if($value==$this->get($tipo)){
       $this->msg=_('Nothing to change');
       $this->update_ok=false;
       return;
	}
     
     $this->data['max_weight']=$value;
     $this->msg=_('Location manxium weight changed');
     $this->update_ok=true;
     break;
 case('max_height'):
     $value=_trim($value);
     
     if(!is_numeric($value)){
       $this->msg=_('The maximum height for this location show be numeric');
       $this->update_ok=false;
	  return;
     }
     if($value < 0){
       $this->msg=_('The maximum height can not be negative');
       $this->update_ok=false;
       return;
     }
     if($value < 0){
	  $this->msg=_('The maximum height can not be zero');
	  $this->update_ok=false;
	  return;
     }
     if($value==$this->get($tipo)){
       $this->msg=_('Nothing to change');
       $this->update_ok=false;
       return;
	}
     
     $this->data['max_height']=$value;
     $this->msg=_('Location maxium height changed');
     $this->update_ok=true;
     break;	
 case('deep'):
     $value=_trim($value);
     
     if(!is_numeric($value)){
       $this->msg=_('The maximum deep for this location show be numeric');
       $this->update_ok=false;
	  return;
     }
     if($value < 0){
       $this->msg=_('The deep can not be negative');
       $this->update_ok=false;
       return;
     }
     if($value < 0){
	  $this->msg=_('The deep can not be zero');
	  $this->update_ok=false;
	  return;
     }
     if($value==$this->get($tipo)){
       $this->msg=_('Nothing to change');
       $this->update_ok=false;
       return;
	}
     
     $this->data['max_height']=$value;
     $this->msg=_('Location deep changed');
     $this->update_ok=true;
     break;
 case('width'):
     $value=_trim($value);
     
     if(!is_numeric($value)){
       $this->msg=_('The maximum width for this location show be numeric');
       $this->update_ok=false;
	  return;
     }
     if($value < 0){
       $this->msg=_('The width can not be negative');
       $this->update_ok=false;
       return;
     }
     if($value < 0){
	  $this->msg=_('The width can not be zero');
	  $this->update_ok=false;
	  return;
     }
     if($value==$this->get($tipo)){
       $this->msg=_('Nothing to change');
       $this->update_ok=false;
       return;
	}
     
     $this->data['max_height']=$value;
     $this->msg=_('Location width changed');
     $this->update_ok=true;
     break;	
case('width'):
     $value=_trim($value);
     
     if(!is_numeric($value)){
       $this->msg=_('The maximum width for this location show be numeric');
       $this->update_ok=false;
	  return;
     }
     if($value < 0){
       $this->msg=_('The width can not be negative');
       $this->update_ok=false;
       return;
     }
     if($value < 0){
	  $this->msg=_('The width can not be zero');
	  $this->update_ok=false;
	  return;
     }
     if($value==$this->get($tipo)){
       $this->msg=_('Nothing to change');
       $this->update_ok=false;
       return;
	}
     
     $this->data['max_height']=$value;
     $this->msg=_('Location width changed');
     $this->update_ok=true;
     break;	
case('max_products'):
     $value=_trim($value);
     
     if(!is_numeric($value) or $value<=0){
         $value='';
     }
     if($value==$this->get($tipo)){
       $this->msg=_('Nothing to change');
       $this->update_ok=false;
       return;
     }

     
     $this->data['max_products']=$value;
     $this->msg=_('Location max_products changed');
     $this->update_ok=true;
     break;	
case('tipo'):
     $value=_trim($value);
     

     if($value==$this->get($tipo)){
       $this->msg=_('Nothing to change');
       $this->update_ok=false;
       return;
     }
     if($value!='picking' or $value!='storing' or $value!='display' or $value!='loading'){
       $this->msg=_('Wrong location tipo');
       $this->update_ok=false;
       return;
     }
     
     $this->data['tipo']=$value;
     $this->msg=_('Location type changed');
     $this->update_ok=true;
     break;	
      }


  }


  function load($key=''){
    switch($key){
    case('items'):
    case('products'):
    case('product'):
      include_once('classes/Product.php');
       $sql=sprintf("select product_id,stock from product2location where location_id=%d ",$this->id);
       //       print $sql;
       $result =& $this->db->query($sql);
       $this->items=array();
       $has_stock=false;
       while($row=$result->fetchRow()){
	 $product=new product($row['product_id']);
	 $this->items[$product->id]=array(
					  'id'=>$product->id,
					  'code'=>$product->get('code'),
					  'stock'=>$row['stock']
					  );
	 if($row['stock']>0)
	   $has_stock=true;
       }
       $this->data['has_stock']=$has_stock;
    }
      

  }


  function get($key){
    switch($key){
    case('num_items'):
    case('num_products'):
      if(!$this->items)
	$this->load('products');
      return count($this->items);
      break;
    case('has_stock'):
        if(!$this->items)
	$this->load('products');
        return $this->data['has_stock'];
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
      
    }


  }
  
  function get_date($key='',$tipo='dt'){
    if(isset($this->dates['ts_'.$key]) and is_numeric($this->dates['ts_'.$key]) ){

      switch($tipo){
      case('dt'):
      default:
	return strftime("%e %B %Y %H:%M", $porder['date_expected']);
      }
    }else
      return false;
  }
  
  

}

?>