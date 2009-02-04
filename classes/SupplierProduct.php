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
      $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Code`=%s  and `Supplier Product Name`=%s and `Supplier Product Cost`=%s and `Supplier Product Supplier Key`=%d "
		   ,prepare_mysql($tag['supplier product code'])
		   ,prepare_mysql($tag['supplier product name'])
		   ,prepare_mysql($tag['supplier product cost'])
		   ,prepare_mysql($tag['supplier product supplier key'])

);
      $result =& $this->db->query($sql);
	if($this->data=$result->fetchRow()){
	$this->id=$this->data['supplier product key'];
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
	
      }else{
	$this->new_id=true;
	$diff_price=false;
	$diff_name=false;
      }

      }

      if($this->new_code){
	$tag['supplier product id']=$this->new_id();
	$tag['supplier product most recent']='Yes';
	$tag['supplier product most recent key']='';
	$tag['supplier product valid from']=$tag['date'];
	$tag['supplier product valid to']=$tag['date'];

      }elseif($this->new_id){
	$tag['supplier product id']=$this->new_id();
	$tag['supplier product most recent']='Yes';
	$tag['supplier product most recent key']='';
	$tag['supplier product valid from']=$tag['date'];
	$tag['supplier product valid to']=$tag['date'];
      }else{

	$sql=sprintf("select * from  `Supplier Product Dimension` where `Supplier Product Valid To`<%s order by `Supplier Product Most Recent`='Yes' desc   ",$tag['date']);
	$result3 =& $this->db->query($sql);
	if($last_data=$result->fetchRow()){
	  $tag['supplier product id']=$last_data['supplier product id'];
	  $sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid To`=");

	}
	

	// see if it is the mos recent

	if(strtotime($most_recent_data['supplier product valit to'])<strtotime($tag['date'])){
	  $tag['supplier product most recent']='Yes';
	  if($this->new_id)
	    $tag['supplier product id']=$this->new_id();
	  else
	    $tag['supplier product id']=$most_recent_data['supplier product id'];

	  $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Most Recent`='No',`Supplier Product Valid To`=%s where `Supplier Product Key`=%s  ");

	}else{
	  //chack if is another down the road
	  


	}
	  


      

      }
      


       $data=array(
		  'supplier product supplier key'=>$tag['supplier product supplier key'],
		  'supplier product id'=>$tag['supplier product id'],
		  'supplier product code'=>$tag['supplier product code'],
		  'supplier product name'=>$tag['supplier product name'],
		  'supplier product cost'=>$tag['supplier product cost'],
		  'supplier product valid from'=>$tag['date'],
		  'supplier product valid to'=>$tag['date'],
		  'supplier product most recent'=>$tag['supplier product most recent'],
		  'supplier product most recent key'=>$tag['supplier product most recent key'],
		  );
      $this->create($data);


// 	$most_recent=$row['supplier product most recent key'];
	
// 	if(date('U',strtotime($row3['Supplier Product Valid To']))< date('U',strtotime($tag['date']))){
// 	    $this->most_recent=true;
// 	    $old_most_recent=$row3['id'];
	    
// 	}else
// 	  $most_recent=$row3['id'];
	
// 	  $this->new_id=true;
//       }else{
// 	  $this->most_recent=true;
// 	  $this->new=true;
// 	}


//       $date=$tag['date'];
//       $data=array(
// 		  'supplier product supplier key'=>$tag['supplier product supplier key'],
// 		  'supplier product code'=>$tag['supplier product code'],
// 		  'supplier product name'=>$tag['supplier product name'],
// 		  'supplier product cost'=>$tag['supplier product cost'],
// 		  'supplier product valid from'=>$tag['date'],
// 		  'supplier product valid to'=>$tag['date'],
// 		  'supplier product most recent'=>($this->most_recent?'Yes':'No'),
// 		  'supplier product most recent key'=>$most_recent,
// 		  );
//       $this->create($data);



      
//       if($this->most_recent and !$this->new){
// 	$sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid To`=%s where `Supplier Product Key`=%d   ",$tag['date'],$old_most_recent);
// 	$this->db->exec($sql);
// 	$sql=sprintf("update `Supplier Product Dimension`  set  `Supplier Product Most Recent`='No' and  `Supplier Product Most Recent Key`=%d where `Supplier Product Most Recent Key`=%d  ",$this->id,$old_most_recent);
// 	$this->db->exec($sql);
//       }
      

      
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
      $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Most Recent Key`=%d where `Supplier Product Key`=%d",$this->id,$this->id);
      //print "$sql\n";
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


  function new_part_list($part_list){
    
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
      $_date='NOW()';
      
      $_date=$data['supplier product part valid from'];
      if(!preg_match('/now/i',$_date))
	$_date=prepare_mysql($_date);
      
      
      $sql=sprintf("update `Supplier Product Part List`  set `Supplier Product Part Most Recent`='No' ,`Supplier Product Part Valid To`=%s where `Product ID`=%d and `Part SKU`=%d  and `Supplier Product Part Most Recent`='Yes' ",$_date,$this->data['product id'],$data['part sku']);
      $this->db->exec($sql);
      
      $base_data=$_base_data;
      foreach($data as $key=>$value){
	$base_data[strtolower($key)]=_trim($value);
      }
      
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
      $sql=sprintf('update `Supplier Product Part List` set `Supplier Product Part Most Recent Key`=%d where `Supplier Product Part List Key`=%d',$id,$id);
  
      $this->db->exec($sql);
    }

  }
  
  }


}