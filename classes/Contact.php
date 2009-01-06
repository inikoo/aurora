<?
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');

class Contact{
  var $db;
  var $data=array();
  var $items=array();
  var $emails=false;

  var $id;




  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();
     
     $this->unknown_name=_('Unknown Name');

     if(preg_match('/^new$/',$arg1)){

       $this->create($arg2);

     }

     if(is_numeric($arg1) and !$arg2){
       $this->get_data('id',$arg1);

     }



  }


  function get_data($key,$id){
  //   $sql=sprintf("select id,name,file_as,alias,has_child,has_parent,UNIX_TIMESTAMP(date_creation) as date_creation,UNIX_TIMESTAMP(date_updated) as date_updated,tipo,genero,main_address,main_tel,main_email,main_contact from contact where id=%d",$id);
//     // print $sql;
//     $result =& $this->db->query($sql);
//     if($row=$result->fetchRow()){
//       $this->data['name']=$row['name'];
//       $this->data['file_as']=$row['file_as'];
//       $this->data['alias']=$row['alias'];
//       $this->data['has_child']=$row['has_child'];
//       $this->data['has_parent']=$row['has_parent'];
//       $this->dates=array(
// 			 'ts_creation'=>$row['date_creation'],
// 			 'ts_updated'=>$row['date_updated']
// 			 );
//       $this->data['tipo']=$row['tipo'];
//       $this->data['genero']=$row['genero'];
//       $this->data['main_email']=$row['main_email'];
//       $main_email=new Email($this->data['main_email']);
//       if($main_email->id){
// 	$this->data['main']['email']=$main_email->data['email'];
// 	$this->data['main']['formated_email']=$main_email->display();
//       }else{
// 	$this->data['main_email']=false;
// 	$this->data['main']['email']='';
// 	$this->data['main']['formated_email']='';
//       }
//       unset($main_email);
// // 	$main_email='';
// //       $this->data['main']=array(
// // 			'address'=>$row['main_address'],
// // 			'tel'=>$row['main_tel'],
// // 			'email'=>$main_email->data['email'],
// // 			'contact'=>$row['main_contact']
// // 			);
//       $this->id=$row['id'];

//     }
    
    if($key=='id')
      $sql=sprintf("SELECT * FROM dw.`Contact Dimension` C where `Contact Key`=%d",$id); 
    else
      return;
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()){
      $this->data=$row;
      $this->id=$row['contact key'];
    }

  }

  function create ($data){
    if(!is_array($data))
      $data=array();

    // print_r($data);

    if(isset($data['name_data'])){
      $this->name=$data['name_data'];
      $name=$this->display('name');
      
    }elseif(isset($data['name']) and $data['name']!=''){
      $name=$data['name'];
      $this->name=$this->parse_name_data($name);
    }else{
      $name=$this->unknown_name;
      $this->name=array();
    }
    
    $this->data['contact name']=$name;
    $file_as=$this->file_as();
    
    $contact_id=$this->get_id();
    $sql=sprintf("insert into `Contact Dimension` (`Contact ID`,`Contact Name`,`Contact File as`) values (%d,%s,%s)",
		 $contact_id,
		 prepare_mysql($name),
		 prepare_mysql($file_as)
		 
	       );
    //  print $sql;
    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {
      if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
	return array('ok'=>false,'msg'=>_('Error: Another contact has the same Id').'.');
      else
	return array('ok'=>false,'msg'=>_('Unknwon Error').'.');
    }
    $this->id = $this->db->lastInsertID();  
  

    if(!isset($data['address']) and !isset($data['address_data'])){
      $address=new address('fuzzy all');
    }else if(isset($data['address'])){
      if(preg_match('/\d*$/i',$data['address'],$match))
	$address=new address('fuzzy country',$match[0]);
      else
	 $address=new address('fuzzy all');
    }else{
      $address_data=$this->parse_address($data['address_data']);
      $address=new address('new',$address_data);
      
    }
    $address_id=$address->id;
    // print_r($address->data);
    $sql=sprintf("insert into `Contact Bridge` (`Contact Key`,`Address Key`) values (%d,%d)",
		 $this->id,
		 $address->id
	       );
    $this->db->exec($sql);

    //print "$sql\n";
    $sql=sprintf("update `Contact Dimension`  set `Main Contact Location`=%s ,`Main Contact XHTML Address`=%s , `Main Contact Country Key`=%d,`Main Contact Country`=%s,`Main Country Code`=%s where `Contact Key`=%d ",
		 prepare_mysql($address->get('address location')),
		 prepare_mysql($address->get('xhtml address')),
		 $address->get('Country Id'),
		 prepare_mysql($address->get('address Country name')),
		 prepare_mysql($address->get('address Country Code')),
		 $this->id
		 );
    //print "$sql\n";
    $this->db->exec($sql);
  }    
  
  
  
  function get($item='',$data=false){
    
    $key=strtolower($item);
    if(isset($this->data[$key]))
      return $this->data[$key];


    switch($item){
    case('has_email_id'):
      if(!$this->emails)
	$this->load('emails');
      return array_key_exists($data,$this->emails);
   break;
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

 function add_email($data,$args='principal'){

   $email=$data['email'];
   if(isset($data['email_type']))
     $email_type=$data['email_type'];
   else
     $email_type='';
   $email_data=array(
		     'email description'=>'',
		     'email'=>$email,
		     'email type'=>'Unknown',
		     'email contact name'=>'',
		     'email validated'=>0,
		     'email verified'=>0,
		     );


   if($this->data['contact name']!=$this->unknown_name)
     $email_data['email Contact Name']=$this->data['contact name'];
   if(preg_match('/work/i',$email_type))
     $email_data['email type']='Work';
   if(preg_match('/personal/i',$email_type))
     $email_data['email type']='personal';
   if(preg_match('/other/i',$email_type))
     $email_data['email type']='other';

   $email=new email('new',$email_data);
   if($email->new){
     
     $sql=sprintf("insert into  `Email Bridge` (`Email Key`, `Contact Key`) values (%d,%d)  ",$email->id,$this->id);
     $this->db->exec($sql);
     if(preg_match('/principal/i',$args)){
       $sql=sprintf("update `Contact Dimension` set `Contact Main XHTML Email`=%s where `Contact Key`=%d",prepare_mysql($email->display('html')),$this->id);
       $this->data['contact main xhtml email']=$email->display('html');
       $this->db->exec($sql);
     }

     $this->add_email=true;
   }else{
     $this->add_email=false;
     
   }

    

  }



 function add_tel($data,$args='principal'){


   $telecom=new telecom('new',$data);
   if($telecom->new){
     
     $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$telecom->id,$this->id);
     $this->db->exec($sql);
     print "$sql\n";

     if(preg_match('/principal/i',$args)){

       if($telecom->get('telecom type')=='Mobile')
	 $telecom_tipo='Main Contact Mobile';
       elseif(preg_match('/fax/i',$telecom->get('telecom type')))
	  $telecom_tipo='Main Contact FAX';
       else
	 $telecom_tipo='Main Main Telephone';
       $sql=sprintf("update `Contact Dimension` set `%s`=%s where `Contact Key`=%d",$telecom_tipo,prepare_mysql($telecom->display('html')),$this->id);
       print "$sql\n";
       $this->db->exec($sql);
     }



     $this->add_telecom=true;
   }else{
     $this->add_telecom=false;
     
   }

    

  }



 function update($values,$args='',$history_data=false){

     $key=key($data);
     $value=$data['value'];

     switch($key){
     case('main_email'):
       if($value==$this->get($key)){
	 $this->msg=_('Same value');
	 $this->updated=false;
	 return;
       }
       $main_email=new email($value);
       if(!$main_email->id){
	 $this->msg=_('Email not found');
	 $this->updated=false;
	 return;
       }
       $email_contact_id=$main_email->get('contact_id');
       if($email_contact_id==0 or $email_contact_id){
	 $main_email->update(
			     array('contact_id'=>array('value'=>$this->id))
			     ,'save',$history_data
			     );
       }

       if($main_email->get('contact_id')){
	 $this->msg=_('Email not found');
	 $this->updated=false;
	 return;
       }

       $this->old['main_email']=$this->data['main']['email'];
       $this->data['main_email']=$value;
       $this->data['main']['email']=$main_email->data['email'];
       $this->updated=true;
     case('gender'):
       if($value==$this->get($key)){
	 $this->msg=_('Same value');
	 $this->updated=false;
	 return;
       }
       $valid_values=array('male','felame','neutro','unknown');
       if(!is_in_array($value,$valid_values)){
	 $this->msg=_('Same value');
	 $this->updated=false;
	 return;
       }
       $this->old['gender']=$this->get($key);
       $this->data['gender']=$value;
       

     }

    
   
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
      $this->emails=array();
      while($row=$result->fetchRow()){
	$email=new Email($row['id']);
	$this->emails[$email->id]=array(
					'email'=>$email->get('email'),
				       'email_link'=>$email->get('link')
				       );
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
  

  function file_as(){
    
    if($this->data['contact name']==$this->unknown_name)
      return $this->unknown_name;
    else
      return $this->data['contact name'];
  }

   function get_id(){
    
    $sql="select max(`Contact ID`)  as company_id from `Contact Dimension`";
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


   function parse_address($data){
     $address_data=array(
			 'Street Number'=>'',
			 'Street Name'=>'',
			 'Street Type'=>'',
			 'Street Direction'=>'',
			 'Post Box'=>'',
			 'Suite'=>'',
			 'City Subdivision'=>'',
			 'City Division'=>'',
			 'City'=>'',
			 'District'=>'',
			 'Second District'=>'',
			 'State'=>'',
			 'Region'=>'',
			 'Address Country Key'=>'',
			 'Address Country Name'=>'',
			 'Address Country Code'=>'',
			 'Address Country 2 Alpha Code'=>'',
			 'Address World Region'=>'',
			 'Address Continent'=>'',
			 'Postal Code'=>'',
			 'Primary Postal Code'=>'',
			 'Secondary Postal Code'=>'',
			 'Postal Code Separator'=>'',
			 'Fuzzy Address'=>''
			 );
    
     return $address_data;
   }



}

?>