<?
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');

class Contact{

  var $data=array();
  var $items=array();
  var $emails=false;

  var $id;




  function __construct($arg1=false,$arg2=false) {

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

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Contact Key'];


  }

  function create ($data){

    //   print_r($data);

    if(!is_array($data))
      $data=array();



    $this->data=array(
		      'Contact Salutation'=>'',
		      'Contact Name'=>'',
		      'Contact File As'=>'',
		      'Contact First Name'=>'',
		      'Contact Surname'=>'',
		      'Contact Suffix'=>'',
		      'Contact Gender'=>'Unknown',
		      'Contact Informal Greeting'=>'',
		      'Contact Formal Greeting'=>''
		      );
    

    

    if(isset($data['name_data'])){
      $this->parse_name_data($data['name_data']);
    }elseif(isset($data['name'])  and $data['name']!=$this->unknown_name){
      $this->parse($data['name']);
    }else
       $this->data['Contact Name']='';

    if($this->data['Contact Name']==''){
      $this->data['Contact Name']=$this->unknown_name;
      $this->data['Contact File As']=$this->unknown_name;
      $this->data['Contact Informal Greeting']=$this->unknown_informal_greeting;
      $this->data['Contact Formal Greeting']=$this->unknown_formal_greeting;
    }
      
    
    $contact_id=$this->get_id();


    $sql=sprintf("insert into `Contact Dimension` (`Contact ID`,`Contact Name`,`Contact File as`,`Contact Salutation`,`Contact First Name`,`Contact Surname`,`Contact Suffix`,`Contact Gender`,`Contact Informal Greeting`,`Contact Formal Greeting`) values (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		 $contact_id,
		 prepare_mysql($this->data['Contact Name']),
		 prepare_mysql($this->data['Contact File As']),
		 prepare_mysql($this->data['Contact Salutation']),
		 prepare_mysql($this->data['Contact First Name']),
		 prepare_mysql($this->data['Contact Surname']),
		 prepare_mysql($this->data['Contact Suffix']),
		 prepare_mysql($this->data['Contact Gender']),
		 prepare_mysql($this->data['Contact Informal Greeting']),
		 prepare_mysql($this->data['Contact Formal Greeting'])


		 );
    //print $sql;

    if(mysql_query($sql)){//nre contacr
      $this->id = mysql_insert_id();
    
      if(!isset($data['address']) and !isset($data['address_data']) and !isset($data['address_key'])  ){

	$address=new address('fuzzy all');
       
      }else if(isset($data['address'])){
	if(preg_match('/\d*$/i',$data['address'],$match))
	  $address=new address('fuzzy country',$match[0]);
	else
	  $address=new address('fuzzy all');
      }elseif(isset($data['address_key'])){
	$address=new address('id',$data['address_key']);
	if(!$address->id)
	  $address=new address('fuzzy all');
      
      }else{
	$address=new address('new',$data['address_data']);
	
      }

      


      $address_id=$address->id;
      
      $sql=sprintf("insert into `Address Contact Bridge` (`Contact Key`,`Address Key`) values (%d,%d)",
		   $this->id,
		   $address_id
		   );

      if(!mysql_query($sql))
	exit("$sql\n error can no create contact address bridge");
      //  print "$sql\n";
      $sql=sprintf("update `Contact Dimension`  set `Contact Main Address Key`=%s ,`Contact main Location`=%s ,`Contact Main XHTML Address`=%s , `Contact Main Country Key`=%d,`Contact Main Country`=%s,`Contact Main Country Code`=%s where `Contact Key`=%d ",
		   prepare_mysql($address_id),
		   prepare_mysql($address->get('Address Location')),
		   prepare_mysql($address->get('XHTML Address')),
		   $address->get('Address Country Key'),
		   prepare_mysql($address->get('Address Country Name')),
		   prepare_mysql($address->get('Address Country Code')),
		   $this->id
		   );
      //   print_r($address->data);
      //      print "\n$sql\n";
      //      exit;
      if(!mysql_query($sql))
	exit(" $sql\n error can not update address data on contact");
      $this->get_data('id',$this->id);
      if(isset($data['email']) and  $data['email']!=''){
	$email_data=array('email'=>$data['email'],'email contact'=>$this->display('name'));
	
	if(isset($data['email type']))
	  $email_data['email type']=$data['email type'];
	$email=new email('new',$email_data);
	if($email->id){
	  $sql=sprintf("insert into  `Email Bridge` (`Email Key`, `Contact Key`) values (%d,%d)  ",$email->id,$this->id);
	  mysql_query($sql);
	  $sql=sprintf("update  `Contact Dimension` set  `Contact Main XHTML Email`=%s , `Contact Main Email Key`=%s  where `Contact Key` ",prepare_mysql($email->display('html')),prepare_mysql($email->id),$this->id);
	  //	print $sql;

	  mysql_query($sql);
	}
	
      }
    
      if(isset($data['fax']) and $data['fax']!=''){
	$tel_data=array(
			'Telecom Original Country Key'=>$this->get('Contact Main Country Key')
			,'Telecom Original Number'=>$data['fax']
			,'Telecom Original Type'=>'Bussiness Fax'
			);
	if(isset($data['fax type']) and ($data['fax type']=='Home' or $data['fax type']=='Bussiness' ) )
	  $tel_data['Telecom Original Type']=$data['fax type'].' Fax';
	$tel=new Telecom('new',$tel_data);
	if($tel->id){
	  $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$tel->id,$this->id);
	  mysql_query($sql);
	  if(preg_match('/fax/i',$tel->get('telecom type'))){
	    $sql=sprintf("update  `Contact Dimension` set  `Contact Main FAX`=%s, `Contact Main FAX`=%s where `Contact Key` ",prepare_mysql($tel->display()),prepare_mysql($tel->id),$this->id);
	    mysql_query($sql);
	  }elseif(preg_match('/mobile/i',$tel->get('telecom type'))){
	    $sql=sprintf("update  `Contact Dimension` set  `Contact Main Mobile`=%s , `Contact Main Mobile Key`=%s where `Contact Key` ",prepare_mysql($tel->display()),prepare_mysql($tel->id),$this->id);
	    mysql_query($sql);
	  }
	
	
	}
      
      }

    
      if(isset($data['mobile']) and $data['mobile']!=''){
	$tel_data=array(
			'Telecom Original Country Key'=>$this->get('Contact Main Country Key')
			,'Telecom Original Number'=>$data['mobile']
			,'Telecom Original Type'=>'Mobile'
			);

	$tel=new Telecom('new',$tel_data);
	if($tel->id){
	  $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$tel->id,$this->id);
	  mysql_query($sql);
	  if(preg_match('/mobile/i',$tel->get('telecom type'))){
	    $sql=sprintf("update  `Contact Dimension` set  `Contact Main Mobile`=%s ,`Contact Main Mobile Key`=%s   where `Contact Key` ",prepare_mysql($tel->display()),prepare_mysql($tel->id),$this->id);
	    mysql_query($sql);
	  }elseif(preg_match('/telephone/i',$tel->get('telecom type'))){
	    $sql=sprintf("update  `Contact Dimension` set  `Contact Main Telephone`=%s , `Contact Main Telephone Key`=%s   where `Contact Key` ",prepare_mysql($tel->display()),prepare_mysql($tel->id),$this->id);
	    mysql_query($sql);
	  }
	

	}
      
      }


     
      if(isset($data['telephone']) and $data['telephone']!=''){
	$tel_data=array(
			'Telecom Original Country Key'=>$this->get('Contact Main Country Key')
			,'Telecom Original Number'=>$data['telephone']
			,'Telecom Original Type'=>'Bussiness Telephone'
			);


	if(isset($data['telephone type']) and ($data['telephone type']=='Home' or $data['telephone type']=='Bussiness' ) )
	  $tel_data['Telecom Original Type']=$data['telephone type'].' Telephone';
	//print_r($tel_data);
	$tel=new Telecom('new',$tel_data);
	if($tel->id){
	  $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$tel->id,$this->id);
	  mysql_query($sql);

	  //	print_r($tel->data);
	  if(preg_match('/telephone|mobile/i',$tel->get('telecom type'))){
	    $sql=sprintf("update  `Contact Dimension` set  `Contact Main Telephone`=%s ,`Contact Main Telephone Key`=%s  where `Contact Key` ",prepare_mysql($tel->display()),prepare_mysql($tel->id),$this->id);
	    mysql_query($sql);
	 
	  }
	  if(preg_match('/mobile/i',$tel->get('telecom type'))){
	    $sql=sprintf("update  `Contact Dimension` set  `Contact Main Mobile`=%s,`Contact Main Mobile Key`=%s   where `Contact Key` ",prepare_mysql($tel->display()),prepare_mysql($tel->id),$this->id);
	    mysql_query($sql);
	  }
	
	}
       
      }



	  



      $this->get_data('id',$this->id);



    }else{
      print "Error can not create contact \n";exit;
    }

    //  print_r($data);
    //print_r($this->data);
    //exit;
  }
    
  
  
  
  function get($key='',$data=false){
    

    if(array_key_exists($key,$this->data))
      return $this->data[$key];


    switch($key){
    case('has_email_id'):
      if(!$this->emails)
	$this->load('emails');
      return array_key_exists($data,$this->emails);
      break;
    case('main_email'):
   
      return $this->data['main']['email'];
      break;
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


    if($this->data['Contact Name']!=$this->unknown_name)
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
      mysql_query($sql);
      if(preg_match('/principal/i',$args)){
	$sql=sprintf("update `Contact Dimension` set `Contact Main XHTML Email`=%s where `Contact Key`=%d",prepare_mysql($email->display('html')),$this->id);
	$this->data['contact main xhtml email']=$email->display('html');
	mysql_query($sql);
      }
     
      $this->add_email=$email->id;
    }else{
      $this->add_email=0;
     
    }

    

  }



  function add_tel($data,$args='principal'){

    if(!isset($data['Telecom Original Country Key']) or !$data['Telecom Original Country Key'])
      $data['Telecom Original Country Key']=$this->get('Customer Shipping Address Country Key');
    $telecom=new telecom('new',$data);
    if($telecom->new){
      
      $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Contact Key`) values (%d,%d)  ",$telecom->id,$this->id);
      mysql_query($sql);
      //   print "$sql\n";

      if(preg_match('/principal/i',$args)){
	
	if($telecom->get('Telecom Type')=='Mobile')
	  $telecom_tipo='Main Contact Mobile';
	elseif(preg_match('/fax/i',$telecom->get('telecom type')))
	  $telecom_tipo='Main Contact FAX';
	else
	  $telecom_tipo='Main Main Telephone';
	$sql=sprintf("update `Contact Dimension` set `%s`=%s where `Contact Key`=%d",$telecom_tipo,prepare_mysql($telecom->display('html')),$this->id);
	//  print "$sql\n";
	mysql_query($sql);
      }
      

      
      $this->add_telecom=$telecom->id;


    }else{
      $this->add_telecom=0;
     
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
      mysql_query($sql);

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
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	
	
	if($tel=new telecom($row['telecom_id']))
	  $this->tel[]=$tel;
      }
      break;
    case('emails'):
      //   $this->emails=array();
      //       $sql=sprintf("select id from email where contact_id=%d ",$this->id);
      //       $result =& $this->db->query($sql);

      //       while($row=$result->fetchRow()){
      // 	$email=new Email($row['id']);
      // 	$this->emails[$email->id]=array(
      // 					'email'=>$email->get('email'),
      // 				       'email_link'=>$email->get('link')
      // 				       );
      //       }
      break;
    case('contacts'):
      //    $sql=sprintf("select child_id from contact_relations where parent_id=%d ",$this->id);
      //       $result =& $this->db->query($sql);
      //       while($row=$result->fetchRow()){
      // 	if($child=new telecom($row['child_id']))
      // 	  $this->contacts[]=$child;
      //       }
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
 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
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
      $this->data['Contact Salutation']=mb_ucwords(_trim($data['salutation']));
    if(isset($data['first']) or $data['middle'])
      $this->data['Contact First Name']=mb_ucwords(_trim($data['first'].' '.$data['middle']));
    if(isset($data['surname']))
      $this->data['Contact Surname']=mb_ucwords(_trim($data['surname']));
    if(isset($data['suffix']))
      $this->data['Contact Suffix']=mb_ucwords(_trim($data['suffix']));
    if(isset($data['gender']) and ($data['gender']=='Male' or $data['gender']=='Female'))
      $this->data['Contact Gender']=_trim($data['gender']);
   
    if($this->data['Contact Gender']=='Unknown')
      $this->data['Contact Gender']=$this->gender();

   

 

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
  


 
    $this->data['Contact Salutation']=_trim($name['prefix']);
    $this->data['Contact First Name']=_trim($name['first'].' '.$name['middle']);
    $this->data['Contact Surname']=_trim($name['last']);
    $this->data['Contact Suffix']=_trim($name['suffix']);
    $this->data['Contact Name']=$this->display('name');
    $this->data['Contact File As']=$this->display('file_as');
    $this->data['Contact Gender']=$this->gender();
    $this->data['Contact Informal Greeting']=$this->display('informal gretting');
    $this->data['Contact Formal Greeting']=$this->display('formal gretting');
		    


  }
  

 
  function display($tipo='name'){
    
  

    switch($tipo){
    case('name'):
      $name=_trim($this->data['Contact Salutation'].' '.$this->data['Contact First Name'].' '.$this->data['Contact Surname']);
      $name=preg_replace('/\s+/',' ',$name);
      return $name;
    case('file as'):
      $name=_trim($this->data['Contact Surname'].' '.$this->data['Contact First Name']);
      $name=preg_replace('/\s+/',' ',$name);
      return $name;
  

  case('informal gretting'):
      $gretting=_('Hello').' ';
      $name=_trim($this->data['Contact First Name']);
      $name=preg_replace('/\s+/',' ',$name);
      if(strlen($name)>1 and !preg_match('/^[a-z] [a-z]$/i',$name) and  !preg_match('/^[a-z] [a-z] [a-z]$/i',$name)  ) 
	return $gretting.$name;
  
   

    if(strlen($this->data['Contact Surname'])>1){
	$name=_trim(
		    $this->data['Contact Salutation'].' '.$this->data['Contact Surname']
		    );
   

	$name=preg_replace('/\s+/',' ',$name);
	return $gretting.$name;
      }
       
   return $this->unknown_informal_greeting;
      
      
    case('formal gretting'):
      $gretting=_('Dear').' ';
      if(strlen($this->data['Contact Surname'])>1){
	 
	if($this->data['Contact Salutation']!=''){
	  $name=_trim($this->data['Contact Salutation'].' '.$this->data['Contact Surname']);
	  return $gretting.$name;
	}elseif($this->data['Contact First Name']!=''){
	  $name=_trim($this->data['Contact First Name'].' '.$this->data['Contact Surname']);
	  return $gretting.$name;
	}
      }
      return $this->unknown_formal_greeting;	 
      
    }
    
    return false;
    
  }

  
  function gender(){
  
    $prefix=$this->data['Contact Salutation'];
    $first_name=$this->data['Contact First Name'];
    $sql=sprintf("select `Gender` from  `Salutation Dimension`  where `Salutation`=%s ",prepare_mysql($prefix));
    // print "$sql\n"; 


 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

      if($row['Gender']=='Male' or $row['Gender']=='Female')
	return $row['Gender'];
    }
  

    $male=0;
    $felame=0;
    $names=preg_split('/\s+/',$first_name);
    foreach($names as $name){
      $sql=sprintf("select `Gender` as genero from  `First Name Dimension` where `First Name`=%s",prepare_mysql($name));
      //    print "$sql\n";
    
      
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	
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
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      return $row['id'];
    }else
      return 0;
  }

  function is_surname($name){

    $sql=sprintf("select `Surname` as id from  `Surname Dimension` where `Surname`=%s",prepare_mysql($name));
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      return $row['id'];
    }else
      return 0;
  }

  function is_prefix($name){
    $sql=sprintf("select `Salutation` as id from `Salutation Dimension`  where `Salutation`=%s",prepare_mysql($name));
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      return $row['id'];
    }else
      return 0;
  }


} 
 ?>