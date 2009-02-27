<?
class location{

  var $data=array();
  var $parts=false;


  var $tipo;
  var $id=false;


  function __construct($arg1=false,$arg2=false,$tipo='shelf') {
     $this->tipo=$tipo;
     if(($arg1=='new'  or  $arg1=='create')and is_array($arg2)){
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
    $warehouse_id=$data['Location Warehouse Key'];
    $area=$data['Location Area'];
    $name=$data['Location Code'];
    $tipo=$data['Location Mainly Used For'];

    if($name=='')
      return array('ok'=>false,'msg'=>_('Wrong location name').'.');
    
    if(!($tipo=='picking' or $tipo=='storing' or $tipo=='loading' or $tipo=='display'))
       return array('ok'=>false,'msg'=>_('Wrong location tipo').'.');
    $sql=sprintf('insert into `Location Dimension` (`Location Code`,`Location Mainly Used For`,`Location Warehouse Key`,`Location Area`) values(%s,%s,%d,%s)'
		 ,prepare_mysql($name)
		 ,prepare_mysql($tipo)
		 ,$warehouse_id
		 ,prepare_mysql($area)
		 );
    if(mysql_query($sql)){
      $id =  mysql_insert_id();
      $this->get_data('id',$id);
    }else
      exit("$sql\n Error con not insert new location\n");
    
  }

  function get_data($key,$tag){
    
      
    $sql=sprintf("select * from `Location Dimension`");
      if($key=='id')
	$sql.=sprintf("where `Location Key`=%d ",$tag);
      else if($key=='name' or $key=='code')
	$sql.=sprintf("where  `Location Code`=%s ",prepare_mysql($tag));
      else
	return;
      //      print $sql;
      
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
	$this->id=$this->data['Location Key'];
      else
	$this->msg=_('Location do not exist');


    
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


  function load($key='',$args=false){
    switch($key){
    case('items'):
    case('parts'):
    case('part'):
      include_once('classes/Part.php');
      if(!$args)
	$date=date("Y-m-d");
      else
	$date=$args;
      
      $sql=sprintf("select `Part SKU` from `Inventory Spanshot Fact`  where `Location Key`=%d  and `Date`=%s group by `Part SKU`",$this->id,prepare_mysql($date));
       //       print $sql;

      $this->parts=array();
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$part=new part('sku',$row['Part SKU']);
	$this->parts[$part->id]=array(
				      'id'=>$part->id,
				      'sku'=>$part->get('Part SKU'),
				      );
	
      }

       

       break;
    }
      

  }


  function get($key){
    switch($key){

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