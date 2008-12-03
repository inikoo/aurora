<?
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');

class Contact{
  var $db;
  var $data=array();
  var $items=array();

  var $id;
  var $tipo;



  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();
     

     if(is_numeric($arg1) and !$arg2){
       $this->get_data($arg1);

     }



  }


  function get_data($id){
    $sql=sprintf("select id,name,file_as,alias,has_child,has_parent,UNIX_TIMESTAMP(date_creation) as date_creation,UNIX_TIMESTAMP(date_updated) as date_updated,tipo,genero,main_address,main_tel,main_email,main_contact from contact where id=%d",$id);
    // print $sql;
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()){
      $this->data['name']=$row['name'];
      $this->data['file_as']=$row['file_as'];
      $this->data['alias']=$row['alias'];
      $this->data['has_child']=$row['has_child'];
      $this->data['has_parent']=$row['has_parent'];
      $this->dates=array(
			 'ts_creation'=>$row['date_creation'],
			 'ts_updated'=>$row['date_updated']
			 );
      $this->data['tipo']=$row['tipo'];
      $this->data['genero']=$row['genero'];
      $this->data['main_email']=$row['main_email'];
      $main_email=new Email($this->data['main_email']);
      if($main_email->id){
	$this->data['main']['email']=$main_email->data['email'];
	$this->data['main']['formated_email']=$main_email->display();
      }else{
	$this->data['main_email']=false;
	$this->data['main']['email']='';
	$this->data['main']['formated_email']='';
      }
      unset($main_email);
// 	$main_email='';
//       $this->data['main']=array(
// 			'address'=>$row['main_address'],
// 			'tel'=>$row['main_tel'],
// 			'email'=>$main_email->data['email'],
// 			'contact'=>$row['main_contact']
// 			);
      $this->id=$row['id'];

    }

  }

 function get($item=''){

 switch($item){
 case('main_email'):

   return $this->data['main']['email'];
   break;
 default:
   
   if(isset($this->data[$item]))
     return $this->data[$item];
   else
     return false;
       
 }
 }

 function update($values,$args=''){
    $res=array();
    foreach($values as $data){
      
      $key=$data['key'];
      $value=$data['value'];
      $res[$key]=array('ok'=>false,'msg'=>'');
      
      switch($key){
      case('main_email'):
	$main_email=new email($value);
	if(!$main_email->id){
	  $res[$key]['msg']=_('Email not found');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->old['main_email']=$this->data['main']['email'];
	$this->data['main_email']=$value;
	$this->data['main']['email']=$main_email->data['email'];
	$res[$key]['ok']=true;


      }

    }
    return $res;
 }


 function save($key,$history_data=false){
    switch($key){
      case('main_email'):
	$sql=sprintf('update contact set %s=%s where id=%d',$key,prepare_mysql($this->data[$key]),$this->id);
	//	print "$sql\n";
	$this->db->exec($sql);

	if(is_array($history_data)){
	  $this->save_history($key,$this->old[$key],$this->data['main']['email'],$history_data);
	}


	break;
    }

 }

 function save_history($key,$old,$new,$data){
   
 }

  function load($key=''){
    switch($key){
    case('telecoms'):
      $this->load('tels');
      $this->load('emails');
      break;
    case('tels'):
      $sql=sprintf("select telecom_id from telecom2contact left join telecom on (telecom.id=telecom_id) where contact_id=%d ",$this->id);
      $result =& $this->db->query($sql);
      while($row=$result->fetchRow()){
	if($tel=new telecom($row['telecom_id']))
	  $this->tel[]=$tel;
      }
      break;
    case('emails'):
      $sql=sprintf("select id from email where contact_id=%d ",$this->id);
      $result =& $this->db->query($sql);
      while($row=$result->fetchRow()){
	if($email=new telecom($row['id']))
	  $this->email[]=$email;
      }
      break;
    case('contacts'):
      $sql=sprintf("select child_id from contact_relations where parent_id=%d ",$this->id);
      $result =& $this->db->query($sql);
      while($row=$result->fetchRow()){
	if($child=new telecom($row['child_id']))
	  $this->contacts[]=$child;
      }
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