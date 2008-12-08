<?

class location{
  var $db;
  var $data=array();
  var $items=false;

  var $id;
  var $tipo;



  function __construct($id=false,$tipo='shelf') {
     $this->db =MDB2::singleton();
     
     $this->tipo=$tipo;
     if(is_numeric($id)){
       $this->id=$id;
       $this->get_data();
     }



  }


  function get_data(){
    
    switch($this->tipo){
    case('shelf'):
      $sql=sprintf("select (select count(*) from product2location where location_id=location.id ) as products ,max_heigth,deep,length,height,location_tipo.max_weight as max_weight,location.id,location.tipo,location.name,wharehouse_area.name as area  from location  left join location_tipo on (tipo_id=location_tipo.id) left join wharehouse_area on (area_id=wharehouse_area.id) where location.id=%d ",$this->id);

      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
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