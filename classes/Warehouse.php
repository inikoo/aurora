<?

class warehouse{
  var $db;
  var $data=array();
  var $areas=false;
  var $id=false;


  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();
     

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
    
  
     if($name=='')
       return array('ok'=>false,'msg'=>_('Wrong warehouse name').'.');
    

    $sql=sprintf('insert into location (name) values(%s)',prepare_mysql($name));
    print "$sql\n";
    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {
      if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	return array('ok'=>false,'msg'=>_('Error: Another warehouse has the same name').'.');
	 else
	   return array('ok'=>false,'msg'=>_('Unknwon Error').'.');
    }
    $id = $this->db->lastInsertID();
    $this->get_data('id',$id);
  }

  function get_data($key,$tag){
    
    if($key=='id')
      $sql=sprintf("select id,name from warehouse where warehouse.id=%d",$tag);
    else if($key=='name')
      $sql=sprintf("select id,name from warehouse where warehouse.name=%s ",prepare_mysql($tag));
    else
      return;
    //print $sql;    
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()){
      $this->id=$row['id'];
      $this->data['name']=$row['name'];
    }      
    $this->msg=_('Warehouse not found');


  }
 


  function update($data){
    foreach($data as $key =>$value)
      switch($key){
      case('name'):
	$name=_trim($value);
	
	if($name==''){
	  $this->msg=_('Wrong warehouse name');
	  $this->update_ok=false;
	  return;
	}

	if($name==$this->get($tipo)){
	  $this->msg=_('Nothing to change');
	  $this->update_ok=false;
	  return;
	}

	$location=new Warehouse('name',$value);
	if($location->id){
	  $this->msg=_('Another ware house has the same name');
	  $this->update_ok=false;
	  return;
	}
	$this->data['name']=$name;
	$this->msg=_('Warehouse name changed');
	$this->update_ok=true;
	break;
      }
    
    
  }


  function load($key=''){
    switch($key){
    case('areas'):
       $sql=sprintf("select id,name from warehouse_area where warehouse_id=%d ",$this->id);

    $result =& $this->db->query($sql);
       $this->areas=array();
       while($row=$result->fetchRow()){
	 $this->areas[$row['id']]=array(
					  'id'=>$row['id'],
					  'name'=>$row['name'],
					  );
       }
    }
      

  }


  function get($key,$data=false){
    switch($key){
    case('num_areas'):
    case('number_areas'):
      if(!$this->areas)
	$this->load('areas');
      return count($this->areas);
      break;
    case('areas'):
      if(!$this->areas)
	$this->load('areas');
      return $this->areas;
      break;
    case('area'):
      if(!$this->areas)
	$this->load('areas');
      if(isset($this->areas[$data['id']]))
	return $this->areas[$data['id']];
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
      
}

?>