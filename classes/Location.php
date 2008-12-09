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
      $sql=sprintf("select location.id,(select count(*) from product2location where location_id=location.id ) as products ,max_heigth,deep,length,height,location_tipo.max_weight as max_weight,location.id,location.tipo,location.name,wharehouse_area.name as area  from location  left join location_tipo on (tipo_id=location_tipo.id) left join wharehouse_area on (area_id=wharehouse_area.id) where location.id=%d ",$tag);
      else if($key=='name')
	$sql=sprintf("select location.id,(select count(*) from product2location where location_id=location.id ) as products ,max_heigth,deep,length,height,location_tipo.max_weight as max_weight,location.id,location.tipo,location.name,wharehouse_area.name as area  from location  left join location_tipo on (tipo_id=location_tipo.id) left join wharehouse_area on (area_id=wharehouse_area.id) where location.name=%s ",prepare_mysql($tag));

      else
	return;
      //  print $sql;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->id=$row['id'];
	$this->data['name']=$row['name'];
	$this->data['num_products']=$row['products'];
	$this->data['used_for']=$row['tipo'];
	$this->data['area']=$row['area'];
	$this->data['dim']=array(
				 'max_heigth'=>$row['max_heigth'],
				 'max_weight'=>$row['max_weight'],
				 'max_length'=>$row['length'],
				 'max_deep'=>$row['deep'],
				 'max_vol'=>false
				 );
	if(
	   is_numeric($this->data['dim']['max_heigth']) 
	   and is_numeric($this->data['dim']['max_length']) 
	   and is_numeric($this->data['dim']['max_deep']) 
	   )$this->data['dim']['max_vol']=$this->data['dim']['max_heigth']*$this->data['dim']['max_length']*$this->data['dim']['max_deep'];

	

	

      }else
	$this->msg=_('Location do not exist');


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