<?

class supplierproduct{
  
  var $db;
  var $id=false;
  var $most_recent=false;
  var $new=false;
  var $new_id=false;
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
  



  function get_data($tipo,$tag){
    if($tipo=='id'){
      $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Key`=%d ",$tag);
      $result =& $this->db->query($sql);
      if($this->data=$result->fetchRow())
	$this->id=$this->data['supplier product key'];
      return;

    }elseif($tipo='supplier-code-name-cost'){
      
      $auto_add=$tag['auto_add'];
      $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Code`=%s  and `Supplier Product Name`=%s and `Supplier Product Cost`=%s and `Supplier Product Supplier Key`=%s "
		   ,prepare_mysql($tag['supplier product code'])
		   ,prepare_mysql($tag['supplier product name'])
		   ,prepare_mysql($tag['supplier product cost'])
		   ,prepare_mysql($tag['supplier product supplier key'])

		   );

      $result =& $this->db->query($sql);
      if($this->data=$result->fetchRow()){

	$this->id=$this->data['supplier product key'];
	
	if(strtotime($this->data['supplier product valid to'])<strtotime($tag['date'])  ){
	  $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Valid To`=%s where `Supplier Product Key`=%d",prepare_mysql($tag['date']),$this->id);
	  $this->data['supplier product valid to']=$tag['date'];
	  $this->db->exec($sql);
	}
	if(strtotime($this->data['supplier product valid from'])>strtotime($tag['date'])  ){
	  $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Valid From`=%s where `Supplier Product Key`=%d",prepare_mysql($tag['date']),$this->id);
	  $this->db->exec($sql);
	  $this->data['supplier product valid from']=$tag['date'];
	}


	return;
      }

    
      if(!$auto_add)
	return;


      $diff_price=true;
      $diff_name=true;
      $this->new_id=false;
      $this->new=true;
      $this->new_code=false;

      $sql=sprintf("select count(*) as num from `Supplier Product Dimension` where `Supplier Product Code`=%s  and `Supplier Product Supplier Key`=%d"
		   ,prepare_mysql($tag['supplier product code'])
		   ,prepare_mysql($tag['supplier product supplier key'])
		   );
      $result2 =& $this->db->query($sql);
      
      if($row=$result2->fetchRow()){
	$number_sp=$row['num'];
      }
      if($number_sp==0){
	$this->new_code=true;
	$tag['supplier product id']=$this->new_id();
	$tag['supplier product valid from']=$tag['date'];
	$tag['supplier product valid to']=$tag['date'];
	$tag['supplier product most recent']='Yes';
	$tag['supplier product most recent key']='';
	$this->create($tag);
      
      }else{
	$sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Most Recent`='Yes',`Supplier Product Code`=%s  and `Supplier Product Supplier Key`=%d  and   `Supplier Product Name`=%s   "
		     ,prepare_mysql($tag['supplier product code'])
		     ,prepare_mysql($tag['supplier product supplier key'])
		     ,prepare_mysql($tag['supplier product name'])
		     );
	$result2 =& $this->db->query($sql);
	if($same_id_data=$result->fetchRow()){
	  // just price difference
	  $diff_name=false;
	  if($tag['supplier product cost']==$same_id_data['supplier product cost'])
	    $diff_price=false;
	  $this->new_id=false;
	  $tag['supplier product id']=$same_id_data['supplier product id'];

	  $tag['supplier product valid from']=$tag['date'];
	  $tag['supplier product valid to']=$tag['date'];
	  
	  $sql=sprintf("select * from  `Supplier Product Dimension` where `Supplier Product Valid To`<%s and `Supplier Product Most Recent`='Yes' and `Supplier Product ID`=%d  ",$tag['date'],$tag['supplier product most recent key']);
	  $result3 =& $this->db->query($sql);
	  if($last_data=$result->fetchRow()){
	    $tag['supplier product most recent']='No';
	    $tag['supplier product most recent key']=$last_data['product key'];
	  }else{
	    $tag['supplier product most recent']='Yes';
	  }
	  $this->create($tag);


	}else{
	  $this->new_id=true;
	  $diff_price=false;
	  $diff_name=false;
	  $this->new_code=true;
	  $tag['supplier product id']=$this->new_id();
	  $tag['supplier product valid from']=$tag['date'];
	  $tag['supplier product valid to']=$tag['date'];
	  $tag['supplier product most recent']='Yes';
	  $tag['supplier product most recent key']='';
	  $this->create($tag);

	}

      }

    
    }

  }


  
  function create($data){
    
      $base_data=array(
		       'supplier product supplier key'=>'',
		       'supplier product id'=>'',
		       'supplier product code'=>'',
		       'supplier product name'=>'',
		       'supplier product description'=>'',
		       'supplier product cost'=>'',
		       'supplier product valid from'=>date("Y-m-d H:i:s"),
		       'supplier product valid to'=>date("Y-m-d H:i:s"),
		       'supplier product most recent'=>'Yes',
		       'supplier product most recent key'=>''
		       );
      foreach($data as $key=>$value){
	if(isset($base_data[strtolower($key)]))
	  $base_data[strtolower($key)]=_trim($value);
      }
 
      if(!$this->valid_id($base_data['supplier product id'])  ){
	$base_data['supplier product id']=$this->new_id();
      }

      if(preg_match('/^yes$/i',$base_data['supplier product most recent']))
	$base_data['supplier product most recent']='Yes';
      else
	$base_data['supplier product most recent']='No';


      $keys='(';$values='values(';
      foreach($base_data as $key=>$value){
	$keys.="`$key`,";
	$values.=prepare_mysql($value).",";
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      $sql=sprintf("insert into `Supplier Product Dimension` %s %s",$keys,$values);
      //print "$sql\n";
      $affected=& $this->db->exec($sql);
      $this->id = $this->db->lastInsertID();

      if($base_data['supplier product most recent']=='Yes'){
      
	$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Most Recent`='No' where `Supplier Product ID`=%d  and `Product Supplier Key`!=%d",$base_data['supplier product id'],$this->id);
	$this->db->exec($sql);
      
	$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Most Recent Key`=%d where `Supplier Product Key`=%d",$this->id,$this->id);
	$this->db->exec($sql);
      }


      
      
      $this->get_data('id',$this->id);

    }

    function load($data_to_be_read,$args=''){

    }
  
    function get($key=''){
      $key=strtolower($key);
      if(isset($this->data[$key]))
	return $this->data[$key];

      $_key=preg_replace('/^supplier product /','',$key);
      if(isset($this->data[$_key]))
	return $this->data[$key];

    
      switch($key){
      
      }
    
      return false;
    }
  

    function valid_id($id){
      if(is_numeric($id) and $id>0 and $id<9223372036854775807)
	return true;
      else
	return false;
    }

    function used_id($id){
      $sql="select count(*) as num from `Supplier Product Dimension` where `Supplier Product ID`=".prepare_mysql($id);
      if($result =& $this->db->query($sql)){
	if($row['num']>0)
	  return true;
      }
      return false;
    }

    function new_id(){
      $sql="select max(`Supplier Product ID`) as id from `Supplier Product Dimension`";

      if($result =& $this->db->query($sql)){
	$row=$result->fetchRow();
	return $row['id']+1;
      }else
	return 1;

    }


    function new_part_list($product_list_id,$part_list){
    
      if(!$this->valid_id($product_list_id))
	$product_list_id=$this->new_part_list_id();

      $_base_data=array(
			'supplier product key'=>$this->id,
			'part key'=>'',
			'factor supplier product'=>'',
			'supplier product units per part'=>'',
			'supplier product part valid from'=>date('Y-m-d H:i:s'),
			'supplier product part valid to'=>date('Y-m-d H:i:s'),
			'supplier product part most recent'=>'Yes',
			'supplier product part most recent key'=>'',
			);


    
      foreach($part_list as $data){

      
	$base_data=$_base_data;
	foreach($data as $key=>$value){
	  if(isset($base_data[strtolower($key)]))
	    $base_data[strtolower($key)]=_trim($value);
	}
      
	$base_data['supplier product part id']=$product_list_id;

	$keys='(';$values='values(';
	foreach($base_data as $key=>$value){
	  $keys.="`$key`,";
	  if(($key='supplier product part valid from' or $key=='supplier product part valid to') and preg_match('/now/i',$value))
	    $values.="NOW(),";
	  else
	    $values.=prepare_mysql($value).",";
	}
	$keys=preg_replace('/,$/',')',$keys);
	$values=preg_replace('/,$/',')',$values);
	$sql=sprintf("insert into `Supplier Product Part List` %s %s",$keys,$values);
	//print "$sql\n";exit;
	$affected=& $this->db->exec($sql);

	$id=$this->db->lastInsertID();
	if($base_data['supplier product part most recent']=='Yes'){
	$sql=sprintf("update `Supplier Product Part List`  set `Supplier Product Part Most Recent`='No',`Supplier Product Part Most Recent Key`=%d where `Supplier Product Part ID`=%d and `Product Part Key`!=%d  ",$id,$base_data['supplier product part id'],$id);
	$this->db->exec($sql);
	  $sql=sprintf('update `Supplier Product Part List` set `Supplier Product Part Most Recent Key`=%d where `Supplier Product Part Key`=%d',$id,$id);
  
	  $this->db->exec($sql);
	}

      }
  
    }




function new_part_list_id(){
  $sql="select max(`Product Part ID`) as id from `Product Part List`";
  $result =& $this->db->query($sql);
  if($row=$result->fetchRow()){
    $id=$row['id']+1;
  }else{
    $id=1;
  }  
  return $id;
}






  }