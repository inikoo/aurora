<?
include_once('Contact.php');
class Customer{
  var $db; 
  var $id=false;


  function __construct($arg1=false,$arg2=false) {
    $this->db =MDB2::singleton();
    $this->status_names=array(0=>'new');
    
    if(is_numeric($arg1) and !$arg2){
       $this->get_data($arg1);
       return;
    }
    
    

    
    
    
  }




 function get_data($id){
   $sql=sprintf("select * from customer where id=%s",prepare_mysql($id));
   $result =& $this->db->query($sql);
   if($this->data=$result->fetchRow()){	     
      $this->id=$this->data['id'];
      
      $o_main_bill_address=new address($this->data['main_bill_address']);
      if($o_main_bill_address->id){
	$this->data['main_bill_address_id']=$o_main_bill_address->id;
	$this->data['main_bill_address']=$o_main_bill_address->display('html');
	
      }else{
	  $this->data['main_bill_address_id']=false;
	  $this->data['main_bill_address']='';
      }
      unset($o_main_bill_address);

      $o_main_contact_name=new name($this->data['main_contact_name']);
      if($o_main_contact_name->id){
	$this->data['main_contact_name_id']=$o_main_contact_name->id;
	$this->data['main_contact_name']=$o_main_contact_name->display('html');
      }else{
	//try to auto fix it
	$this->data['main_contact_name_id']=false;
	$this->data['main_contact_name']='';
      }
      unset($o_main_contact_name);

      $o_main_email=new email($this->data['main_email']);
      if($o_main_email->id){
	
	$this->data['main_email_id']=$o_main_email->id;
	$this->data['main']['email']=$o_main_email->display();
	$this->data['main']['formated_email']=$o_main_email->display('link');
      }else{
	//try to auto fix it
	$this->data['main_email_id']=false;
	$this->data['main']['email']='';
	$this->data['main']['formated_email']='';
      }
      unset($o_main_email);

      $o_main_tel=new telecom($this->data['main_tel']);
      if($o_main_tel->id){
	$this->data['main_tel_id']=$o_main_tel->id;
	$this->data['main_tel']=$o_main_tel->display('link');
      }else{
	//try to auto fix it
	$this->data['main_tel_id']=false;
	$this->data['main_tel']='';
      }
      unset($o_main_tel);

    }


  }
 function load($key=''){
    switch($key){
    case('location'):
      global $myconf;
      if($this->data['main_bill_address_id']==''){
	$this->data['location']['country_code']=$myconf['country_code'];
	$this->data['location']['town']='';
      }else{
     
      $sql=sprintf('select code,town from  address  left join list_country on (country=list_country.name) where address.id=%d ',$this->data['main_bill_address_id']);
      //     print_r($this->data);
      // print $sql;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){	     
	$this->data['location']['country_code']=$row['code'];
	$this->data['location']['town']=$row['town'];
      }

      }
      break;
    case('contacts'):
    case('contact'):
      if($this->contact=new contact($this->data['contact_id'])){
	
	$this->contact->load('telecoms');
	$this->contact->load('contacts');
      }
    }
    
  }

 function create_new(){

 }


 function update($values,$args=''){
    $res=array();
    foreach($values as $data){
      
      $key=$data['key'];
      $value=$data['value'];
      $res[$key]=array('ok'=>false,'msg'=>'');
      
      switch($key){

      case('tax_number_valid'):
	if($value)
	  $this->data['tax_number_valid']=1;
	else
	  $this->data['tax_number_valid']=0;
	
	break;

      case('tax_number'):
	$this->data['tax_number']=$value;
	if($value=='')
	  $this->update(array(array('key'=>'tax_number_valid','value'=>0)),'save');
	break;
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
      if(preg_match('/save/',$args)){
	$this->save($key);
      }

    }
    return $res;
 }


 function save($key,$history_data=false){
   switch($key){

   case('tax_number'):
   case('tax_number_valid'):
   case('main_email'):
     $sql=sprintf('update customer set %s=%s where id=%d',$key,prepare_mysql($this->data[$key]),$this->id);
     //print "$sql\n";
     $this->db->exec($sql);
     
	if(is_array($history_data)){
	  $this->save_history($key,$this->old[$key],$this->data['main']['email'],$history_data);
	}
       
	
	break;
    }

 }

 function save_history($key,$old,$new,$data){
     if(isset($data['user_id']))
       $user=$data['user_id'];
     else
       $user=0;
     
     if(isset($data['date']))
       $date=$data['date'];
     else
       $date='NOW()';

   switch($key){
   case('new_note'):
   case('add_note'):
     if(preg_match('/^\s*$/',$data['note'])){
       $this->msg=_('Invalid value');
       return false;
     
     }

     $tipo='NOTE';
     $note=$data['note'];
     


     $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		  ,$date
		  ,prepare_mysql('CUST')
		  ,prepare_mysql($this->id)
		  ,prepare_mysql($tipo)
		  ,'NULL'
		  ,prepare_mysql('NEW')
		  ,prepare_mysql($user)
		  ,prepare_mysql($old)	 
		  ,prepare_mysql($new)	 
		  ,prepare_mysql($note)
		  );
     //  print $sql;
     $this->db->exec($sql);
     $this->msg=_('Note Added');
     return true;
     break;

       case('new_note'):
   case('order'):
     if(preg_match('/^\s*$/',$data['note'])){
       $this->msg=_('Invalid value');
       return false;
     
     }

     $tipo='Order';
     $note=$data['note'];
     
     $action=$data['action'];


     $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		  ,$date
		  ,prepare_mysql('CUST')
		  ,prepare_mysql($this->id)
		  ,prepare_mysql($tipo)
		  ,'NULL'
		  ,prepare_mysql($action)
		  ,prepare_mysql($user)
		  ,prepare_mysql($old)	 
		  ,prepare_mysql($new)	 
		  ,prepare_mysql($note)
		  );
     //  print $sql;
     $this->db->exec($sql);
     $this->msg=_('Note Added');
     return true;

   }
 }


 function get($key){
   switch($key){
   case('location'):
     if(!isset($this->data['location']))
       $this->load('location');
     return $this->data['location']['country_code'].$this->data['location']['town'];
     break;
   case('super_total'):
          return $this->data['total_nd']+$this->data['total'];
	  break;
   case('orders'):
     return $this->data['num_invoices']+$this->data['num_invoices_nd'];
     break;
   default:
     if(isset($this->data[$key]))
       return $this->data[$key];
     else
       return '';
   }
   
 }

}
?>