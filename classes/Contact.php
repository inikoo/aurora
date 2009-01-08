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
      global $myconf;
     $this->unknown_name=$myconf['unknown_contact'];
     $this->unknown_informal_greeting=$myconf['unknown_informal_greting'];
     $this->unknown_formal_greeting=$myconf['unknown_formal_greting'];

  if(preg_match('/^new$/',$arg1)){

       $this->create($arg2);
       return;
     }

     if(is_numeric($arg1) and !$arg2){
       $this->get_data('id',$arg1);
       return;
     }
     $this->get_data($arg1,$arg2);


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

    //    print_r($data);

    $this->data=array(
		     'contact salutation'=>'',
		     'contact name'=>'',
		     'contact file as'=>'',
		     'contact first name'=>'',
		     'contact surname'=>'',
		     'contact suffix'=>'',
		     'contact gender'=>'Unknown',
		     'contact informal greeting'=>'',
		     'contact formal greeting'=>''
		      );
    
    if(isset($data['name_data'])){
      $this->parse_name_data($data['name_data']);
    }elseif(isset($data['name'])){
      $this->parse($data['name']);
      // print_r( $this->data);
    }
//     print "->".$data['name']."<-\n";
//     print  "->".$this->unknown_name."<-\n";
//     exit;
    if($data['name']==$this->unknown_name)
      $this->data['contact name']='';
    
    if($this->data['contact name']==''){
      $this->data['contact name']=$this->unknown_name;
      $this->data['contact file as']=$this->unknown_name;
      $this->data['contact informal greeting']=$this->unknown_informal_greeting;
      $this->data['contact formal greeting']=$this->unknown_formal_greeting;
    }
      
    
    $contact_id=$this->get_id();

    // print_r( $this->data);

    $sql=sprintf("insert into `Contact Dimension` (`Contact ID`,`Contact Name`,`Contact File as`,`Contact Salutation`,`Contact First Name`,`Contact Surname`,`Contact Suffix`,`Contact Gender`,`Contact Informal Greeting`,`Contact Formal Greeting`) values (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		 $contact_id,
		 prepare_mysql($this->data['contact name']),
		 prepare_mysql($this->data['contact file as']),
		 prepare_mysql($this->data['contact salutation']),
		 prepare_mysql($this->data['contact first name']),
		 prepare_mysql($this->data['contact surname']),
		 prepare_mysql($this->data['contact suffix']),
		 prepare_mysql($this->data['contact gender']),
		 prepare_mysql($this->data['contact informal greeting']),
		 prepare_mysql($this->data['contact formal greeting'])


	       );
    //print $sql;
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
      //$address_data=$this->parse_address($data['address_data']);

      $address=new address('new',$data['address_data']);
      
    }
    $address_id=$address->id;

    $sql=sprintf("insert into `Contact Bridge` (`Contact Key`,`Address Key`) values (%d,%d)",
		 $this->id,
		 $address->id
	       );
    $this->db->exec($sql);

    //  print "$sql\n";
    $sql=sprintf("update `Contact Dimension`  set `Contact main Location`=%s ,`Contact Main XHTML Address`=%s , `Contact Main Country Key`=%d,`Contact Main Country`=%s,`Contact Main Country Code`=%s where `Contact Key`=%d ",
		 prepare_mysql($address->get('address location')),
		 prepare_mysql($address->get('xhtml address')),
		 $address->get('address country Key'),
		 prepare_mysql($address->get('address Country name')),
		 prepare_mysql($address->get('address Country Code')),
		 $this->id
		 );
    // print "\n$sql\n";
    $this->db->exec($sql);
    $this->get_data('id',$this->id);
    if(isset($data['email']) & $data['email']!=''){
      $email_data=array('email'=>$data['email'],'email contact'=>$this->display('name'));
      
      if(isset($data['email type']))
	$email_data['email type']=$data['email type'];
      $email=new email('new',$email_data);
      if($email->id){
	$sql=sprintf("insert into  `Email Bridge` (`Email Key`, `Contact Key`) values (%d,%d)  ",$email->id,$this->id);
	$this->db->exec($sql);
	$sql=sprintf("update  `Contact Dimension` set  `Contact Main XHTML Email`=%s  where `Contact Key` ",prepare_mysql($email->display('html')),$this->id);
	//	print $sql;
	$this->db->exec($sql);

      }
	
    }
    
    if(isset($data['fax']) & $data['fax']!=''){
      $tel_data=array(
		      'country key'=>$this->get('Contact Main Country Key')
		      ,'telecom number'=>$data['fax']
		      ,'telecom type'=>'Bussiness Fax'
		      );
      if(isset($data['fax type']) and ($data['fax type']=='Home' or $data['fax type']=='Bussiness' ) )
	$tel_data['telecom type']=$data['fax type'].' Fax';
      $tel=new Telecom('new',$tel_data);
      if($tel->id){
	$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$tel->id,$this->id);
	$this->db->exec($sql);
	if(preg_match('/fax/i',$tel->get('telecom type'))){
	  $sql=sprintf("update  `Contact Dimension` set  `Contact Main FAX`=%s  where `Contact Key` ",prepare_mysql($tel->display()),$this->id);
	  $this->db->exec($sql);
	}elseif(preg_match('/mobile/i',$tel->get('telecom type'))){
	  $sql=sprintf("update  `Contact Dimension` set  `Contact Main Mobile`=%s  where `Contact Key` ",prepare_mysql($tel->display()),$this->id);
	  $this->db->exec($sql);
	}
	
	
      }
      
    }

    
    if(isset($data['mobile']) & $data['mobile']!=''){
      $tel_data=array(
		      'country key'=>$this->get('Contact Main Country Key')
		      ,'telecom number'=>$data['mobile']
		      ,'telecom type'=>'Mobile'
		      );

      $tel=new Telecom('new',$tel_data);
      if($tel->id){
	$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$tel->id,$this->id);
	$this->db->exec($sql);
	if(preg_match('/mobile/i',$tel->get('telecom type'))){
	  $sql=sprintf("update  `Contact Dimension` set  `Contact Main Mobile`=%s  where `Contact Key` ",prepare_mysql($tel->display()),$this->id);
	    $this->db->exec($sql);
	}elseif(preg_match('/telephone/i',$tel->get('telecom type'))){
	  $sql=sprintf("update  `Contact Dimension` set  `Contact Main Telephone`=%s  where `Contact Key` ",prepare_mysql($tel->display()),$this->id);
	  $this->db->exec($sql);
	}
	

      }
      
     }


     
     if(isset($data['telephone']) & $data['telephone']!=''){
       $tel_data=array(
		       'country key'=>$this->get('Contact Main Country Key')
		      ,'telecom number'=>$data['telephone']
		       ,'telecom type'=>'Bussiness Telephone'
		       );
       if(isset($data['telephone type']) and ($data['telephone type']=='Home' or $data['telephone type']=='Bussiness' ) )
	 $tel_data['telecom type']=$data['telephone type'].' Telephone';
       $tel=new Telecom('new',$tel_data);
       if($tel->id){
	$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$tel->id,$this->id);
	$this->db->exec($sql);

	//	print_r($tel->data);
	if(preg_match('/telephone|mobile/i',$tel->get('telecom type'))){
	  $sql=sprintf("update  `Contact Dimension` set  `Contact Main Telephone`=%s  where `Contact Key` ",prepare_mysql($tel->display()),$this->id);

	  $this->db->exec($sql);
	}
	if(preg_match('/mobile/i',$tel->get('telecom type'))){
	  $sql=sprintf("update  `Contact Dimension` set  `Contact Main Mobile`=%s  where `Contact Key` ",prepare_mysql($tel->display()),$this->id);
	    $this->db->exec($sql);
	}
	
       }
       
     }



	  
	  


    $this->get_data('id',$this->id);
    //  print_r($data);
    //print_r($this->data);
    //exit;
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
     //   print "$sql\n";

     if(preg_match('/principal/i',$args)){

       if($telecom->get('telecom type')=='Mobile')
	 $telecom_tipo='Main Contact Mobile';
       elseif(preg_match('/fax/i',$telecom->get('telecom type')))
	  $telecom_tipo='Main Contact FAX';
       else
	 $telecom_tipo='Main Main Telephone';
       $sql=sprintf("update `Contact Dimension` set `%s`=%s where `Contact Key`=%d",$telecom_tipo,prepare_mysql($telecom->display('html')),$this->id);
       //  print "$sql\n";
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
  
 //  function get_date($key='',$tipo='dt'){
//     if(isset($this->dates['ts_'.$key]) and is_numeric($this->dates['ts_'.$key]) ){

//       switch($tipo){
//       case('dt'):
//       default:
// 	return strftime("%e %B %Y %H:%M", $porder['date_expected']);
//       }
//     }else
//       return false;
//   }
  

//   function file_as(){
    
//     if($this->data['contact name']==$this->unknown_name)
//       return $this->unknown_name;
//     else
//       return $this->data['contact name'];
//   }

   function get_id(){
    
    $sql="select max(`Contact ID`)  as contact_id from `Contact Dimension`";
    $result =& $this->db->query($sql);
    if( $row=$result->fetchRow()){
      
      if(!preg_match('/\d*/',_trim($row['contact_id']),$match))
	$match[0]=1;
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
   

 function parse_name_data($data){

   if(isset($data['salutation']))
     $this->data['contact salutation']=mb_ucwords(_trim($data['salutation']));
   if(isset($data['first']) or $data['middle'])
     $this->data['contact first name']=mb_ucwords(_trim($data['first'].' '.$data['middle']));
   if(isset($data['surname']))
     $this->data['contact surname']=mb_ucwords(_trim($data['surname']));
   if(isset($data['suffix']))
     $this->data['contact suffix']=mb_ucwords(_trim($data['suffix']));
   if(isset($data['gender']) and ($data['gender']=='Male' or $data['gender']=='Female'))
     $this->data['contact gender']=_trim($data['gender']);
   
    if($this->data['contact gender']=='Unknown')
      $this->data['contact gender']=$this->gender();

   

 

    $this->data=array(
		      'contact name'=>$this->display('name'),
		      'contact file as'=>$this->display('file_as'),
		      'contact informal greeting'=>$this->display('informal gretting'),
		      'contact formal greeting'=>$this->display('formal gretting')
		     );


  


   }


   function parse($raw_name){

 $name=array(
	      'prefix'=>'',
	      'first'=>'',
	      'middle'=>'',
	      'last'=>'',
	      'suffix'=>'',
	      'alias'=>''
	      );
     
 $raw_name=_trim($raw_name);
 $raw_name=preg_replace('/\./',' ',$raw_name);
 $names=preg_split('/\s+/',$raw_name);

  $parts=count($names);
  switch($parts){
  case(1):
    if($this->is_surname($names[0]))
      $name['last']=$names[0];
    else if($this->is_givenname($names[0]))
      $name['first']=$names[0];
    else if($this->is_prefix($names[0]))
      $name['prefix']=$names[0];
    else
      $name['first']=$names[0];
    break;
  case(2):
    // firt the most obious choise
    
    if( $this->is_givenname($names[0])){
      $name['first']=$names[0];
      $name['last']=$names[1];
      

    }else if( $this->is_givenname($names[0]) and   $this->is_surname($names[1])){
      $name['first']=$names[0];
      $name['last']=$names[1];

    }else if( $this->is_prefix($names[0]) and   $this->is_surname($names[1])){
      $name['prefix']=$names[0];
      $name['last']=$names[1];
    }else if( $this->is_prefix($names[0]) and   $this->is_givenname($names[1])){
      $name['prefix']=$names[0];
      $name['first']=$names[1];
    }else if( $this->is_surname($names[0]) and   $this->is_surname($names[1])){
      $name['last']=$names[0].' '.$names[1];
    }else{
      $name['first']=$names[0];
      $name['last']=$names[1];

    }
    break;
  case(3):
    // firt the most obious choise

    if(!$this->is_prefix($names[0]) and  strlen($names[1])==1   and   strlen($names[2])>1  ){
      $name['first']=$names[0];
      $name['middle']=$names[1];
      $name['last']=$names[2];
    }elseif( $this->is_prefix($names[0])) {
	$name['prefix']=$names[0];
	$name['first']=$names[1];
	$name['last']=$names[2];

// 	if(   $this->is_givenname($names[1]) and   $this->is_surname($names[2])){

// 	  $name['first']=$names[1];
// 	  $name['last']=$names[2];
// 	}else if(    strlen($names[1])==1 and   $this->is_surname($names[2])){
	  
// 	  $name['first']=$names[1];
// 	  $name['last']=$names[2];
// 	}else if(   $this->is_givenname($names[1])    and   $this->is_givenname($names[2])){
	  
// 	  $name['first']=$names[1].' '.$names[2];
// 	}else if(  $this->is_surname($names[1])    and   $this->is_surname($names[2])){
	  
// 	  $name['last']=$names[1].' '.$names[2];
// 	}else{
// 	  $name['first']=$names[1];
// 	  $name['last']=$names[2];
	  
// 	}
	

      }else if(  $this->is_givenname($names[0])   and   $this->is_givenname($names[1])  and   $this->is_surname($names[2])){
	$name['first']=$names[0].' '.$names[1];
	$name['last']=$names[2];
      }else if(  $this->is_givenname($names[0])   and   $this->is_surname($names[1])  and   $this->is_surname($names[2])){
	$name['first']=$names[0];
	$name['last']=$names[1].' '.$names[2];
      }else if( $this->is_givenname($names[0]) and     strlen($names[1])==1 and   $this->is_surname($names[2])){
	$name['first']=$names[0];
	$name['middle']=$names[1];
	$name['last']=$names[2];
      }else{
	$name['first']=$names[0];
	$name['last']=$names[1].' '.$names[2];
      }
      break;
    case(4):


      
  if( $this->is_prefix($names[0])) {
	$name['prefix']=$names[0];
	
	if(  $this->is_givenname($names[1]) and    strlen($names[2])==1 and  $this->is_surname($names[3])){

	  $name['first']=$names[1];
	  $name['middle']=$names[2];
	  $name['last']=$names[3];
	}else if(  $this->is_givenname($names[1]) and   $this->is_givenname($names[2])  and  $this->is_surname($names[3])){

	  $name['first']=$names[1].' '.$names[2];
	  $name['last']=$names[3];
	}else if( $this->is_prefix($names[0]) and     $this->is_givenname($names[1]) and   $this->is_surname($names[2])  and  $this->is_surname($names[3])){
	  
	  $name['first']=$names[1];
	  $name['last']=$names[2].' '.$names[3];
	  
	}else
	  $name['first']=$names[1].' '.$names[2];
	  $name['last']=$names[3];
	

    // firt the most obious choise
  }else if(      $this->is_givenname($names[0]) and $this->is_givenname($names[1]) and    $this->is_surname($names[2])  and  $this->is_surname($names[3])     ){

      $name['first']=$names[0].' '.$names[1];
      $name['last']=$names[2].' '.$names[3];
    }else  if(      $this->is_givenname($names[0]) and $this->is_givenname($names[1]) and    $this->is_givenname($names[2])  and  $this->is_surname($names[3])     ){

      $name['first']=$names[0].' '.$names[1].' '.$names[2];
      $name['last']=$names[3];
    }else{
      $name['first']=$names[0];
      $name['last']=$names[1].' '.$names[2].' '.$names[3];
    }
    break;
  case(5):
      if( $this->is_prefix($names[0]) and     $this->is_givenname($names[1]) and   $this->is_givenname($names[2])   and  $this->is_surname($names[3]) and $this->is_surname($names[4])  ){
      $name['prefix']=$names[0];
      $name['first']=$names[1].' '.$names[2];
      $name['first']=$names[3].' '.$names[4];
      }
      else
	$name['last']=join(' ',$names);
    break;
  default:
    $name['last']=join(' ',$names);
    
  }

  

  foreach($name as $key=>$value){
    $name[$key]=mb_ucwords($value);
     
  }

  //print_r($name);
 

 
  $this->data['contact salutation']=_trim($name['prefix']);
  $this->data['contact first name']=_trim($name['first'].' '.$name['middle']);
  $this->data['contact surname']=_trim($name['last']);
  $this->data['contact suffix']=_trim($name['suffix']);
  $this->data['contact name']=$this->display('name');
  $this->data['contact file as']=$this->display('file_as');
  $this->data['contact gender']=$this->gender();
  $this->data['contact informal greeting']=$this->display('informal gretting');
  $this->data['contact formal greeting']=$this->display('formal gretting');
		    


   }

   function display($tipo='name'){

     switch($tipo){
     case('name'):
       $name=_trim($this->data['contact salutation'].' '.$this->data['contact first name'].' '.$this->data['contact surname']);
       $name=preg_replace('/\s+/',' ',$name);
       return $name;
     case('file as'):
        $name=_trim($this->data['contact surname'].' '.$this->data['contact first name']);
       $name=preg_replace('/\s+/',' ',$name);
       return $name;
     case('informal gretting'):
       $gretting=_('Hello').' ';
       $name=_trim($this->data['contact first name']);
       $name=preg_replace('/\s+/',' ',$name);
       if(strlen($name)>1 and !preg_match('/^[a-z] [a-z]$/i',$name) and  !preg_match('/^[a-z] [a-z] [a-z]$/i',$name)  ) 
	 return $gretting.$name;
       if(strlen($this->data['contact surname'])>1){
	 $name=_trim($this->data['contact salutation'].' '.$this->data['contact surname']);
	 $name=preg_replace('/\s+/',' ',$name);
	 return $gretting.$name;
       }
       return $this->unknown_informal_greeting;
     case('formal gretting'):
       $gretting=_('Dear').' ';
       if(strlen($this->data['contact surname'])>1){
	 
	 if($this->data['contact salutation']!=''){
	   $name=_trim($this->data['contact salutation'].' '.$this->data['contact surname']);
	   return $gretting.$name;
	 }elseif($this->data['contact first name']!=''){
	   $name=_trim($this->data['contact first name'].' '.$this->data['contact surname']);
	   return $gretting.$name;
	 }
       }
       return $this->unknown_formal_greeting;	 

     }
     
     return false;

   }

function gender(){
  
  $prefix=$this->data['contact salutation'];
  $first_name=$this->data['contact first name'];
  $sql=sprintf("select `Gender` from  `Salutation Dimension`  where `Salutation`=%s ",prepare_mysql($prefix));
  // print "$sql\n"; 
  $res = $this->db->query($sql); 
  if ($row=$res->fetchRow()){
    if($row['gender']=='Male' or $row['gender']=='Female')
      return $row['gender'];
  }
  

  $male=0;
  $felame=0;
  $names=preg_split('/\s+/',$first_name);
  foreach($names as $name){
    $sql=sprintf("select `Gender` as genero from  `First Name Dimension` where `First Name`=%s",prepare_mysql($name));
    //    print "$sql\n";
    $res = $this->db->query($sql); 
    if ($row=$res->fetchRow()){
      if($row['genero']=='Male')
	$male++;
      if($row['genero']=='Felame')
	$felame++;
    }
  }
  if($felame>$male)
    return 'Felame';
  else if ($male>$felame)
    return 'Male';
  else
    return 'Unknown';
  
}

function is_givenname($name){
  $sql=sprintf("select `First Name Key` as id from  `First Name Dimension` where `First Name`=%s",prepare_mysql($name));
  $res2 = $this->db->query($sql); 
  if ($row2=$res2->fetchRow()){
    return $row2['id'];
  }else
    return 0;
}

function is_surname($name){

  $sql=sprintf("select `Surname` as id from  `Surname Dimension` where `Surname`=%s",prepare_mysql($name));
  $res2 = $this->db->query($sql); 
  if ($row2=$res2->fetchRow()){
    return $row2['id'];
  }else
    return 0;
}
function is_prefix($name){

  $sql=sprintf("select `Salutation` as id from `Salutation Dimension`  where `Salutation`=%s",prepare_mysql($name));

  $res2 = $this->db->query($sql); 
  if ($row2=$res2->fetchRow()){
    return $row2['id'];
  }else
    return 0;
}

}

?>