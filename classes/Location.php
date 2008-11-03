<?
include_once('common/string.php');
class location{
  var $db;
  var $data=array();
  var $items=array();

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
    case('product'):
      include_once('classes/Product.php');
       $sql=sprintf("select product_id from product2location where location_id=%d ",$this->id);
      $result =& $this->db->query($sql);
      while($row=$result->fetchRow()){
	$this->items[]=new product($row['product_id']);
      }
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