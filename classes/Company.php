<?

include_once('Contact.php');
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');

class company{
  var $db;
  var $data=array();
  var $items=array();

  var $id=false;


  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();
     

     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/create|new/i',$arg1)){
       $this->create($arg2);
       return;
     }       
      $this->get_data($arg1,$arg2);
       return ;

 }


  function get($key){
    //  print $key."xxxxxxxx";
    $key=strtolower($key);
    if(isset($this->data[$key]))
      return $this->data[$key];
   
    return false;

  }


  function get_data($tipo,$id){
    $sql=sprintf("select * from `Company Dimension` where `Company Key`=%d",$id);
    // print $sql;
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()){
      $this->data=$row;
      $this->id=$row['company key'];
    }
     
  }

  function create($data){
    if(!is_array($data))
      $data=array('name'=>_('Unknown Name'));


    // print_r($data);

    $name=$data['name'];
    $file_as=$this->file_as($data['name']);
    $company_id=$this->get_id();
    
    if(!isset($data['contact key']) or !is_numeric($data['contact key'])){
      $contact=new contact($new);
    }else{
    $contact_id=$data['contact key'];
    $contact=new contact($contact_id);
    }

    //print_r($contact->data);
    $sql=sprintf("insert into `Company Dimension` (`Company ID`,`Company Name`,`Company File as`,`Company XHTML Address`,`Company Country Key`,`Company Country`,`Company Location`,`Company Principal Contact`,`Company Principal Contact Key`) values (%d,%s,%s,%s,%s,%s,%s,%s,%d)",
		 $company_id,
		 prepare_mysql($name),
		 prepare_mysql($file_as),
		 prepare_mysql($contact->get('Main Contact XHTML Address')),
		 prepare_mysql($contact->get('Main Contact Country Code')),
		 prepare_mysql($contact->get('Main Contact Country')),
		 prepare_mysql($contact->get('Main Contact location')),
		 prepare_mysql($contact->get('contact name')),
		 $contact->id

		 
		 );
    //    print "$sql\n";
    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {
      if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	return array('ok'=>false,'msg'=>_('Error: Another company has the same id').'.');
      else
      return array('ok'=>false,'msg'=>_('Unknwon Error').'.');
    }
    $this->id = $this->db->lastInsertID();  
    
  
  }

  function get_id(){
    
    $sql="select max(`Company ID`)  as company_id from `Company Dimension`";
    $result =& $this->db->query($sql);
    if( $row=$result->fetchRow()){
      preg_match('/\d+$/',_trim($row['company_id']),$match);
      $right_side=$match[0];
      $number=(double) $right_side;
      $number++;
      $id=$number;
    }else{
      $id=1;
    }  
    return $id;
  }

  function load($key=''){
    switch($key){
   
    case('contacts'):
    case('contact'):
      $this->contact=new Contact($this->data['contact_id']);
      if($this->contact->id){
	$this->contact->load('telecoms');
	$this->contact->load('contacts');
      }

    }
    
  }
  
  function add_email($email_data,$args='principal'){
    //  $emails=$this->get('emails');
    //  print_r($this->data);

    $contact=new contact($this->get('company principal contact key'));


    $contact->add_email($email_data,$args);

    if($contact->add_email){
      $this->msg['email added'];
    }

  }

 function add_tel($tel_data,$args='principal'){

   $tel_data['country key']=$this->get('Company Country Key');
   $contact=new contact($this->get('company principal contact key'));

   $contact->add_tel($tel_data,$args);
   
   if($contact->add_tel){
      $this->msg['telecom added'];
    }

  }


  function create_code($name){
    preg_replace('/[!a-z]/i','',$name);
    preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
    preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
    preg_split('/\s*/',$name);
    return $name;
  }

   function check_code($name){
    return $name;
  }
   function file_as($name){
    return $name;
  }

}

?>