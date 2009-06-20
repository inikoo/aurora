<?




function display_person_name($name_id){

  if(!$name_id)
    return false;

  if(is_array($name_id)){
    $name_data=$name_id;
    $name=($name_data['prefix']!=''?$name_data['prefix'].' ':'').($name_data['first']!=''?$name_data['first'].' ':'').($name_data['middle']!=''?$name_data['middle'].' ':'').($name_data['last']!=''?$name_data['last'].' ':'').($name_data['suffix']!=''?$name_data['suffix']:' ').($name_data['alias']!=''?$name_data['alias']:'');
    return trim(trim($name));

  }


 $db =& MDB2::singleton();
 $sql=sprintf("select prefix,first,middle,last,suffix,alias from name where id=%d",$name_id);
 $res=mysql_query($sql); 
 if ($row=$res->fetchRow()){
   $name=($row['prefix']!=''?$row['prefix'].' ':'').($row['first']!=''?$row['first'].' ':'').($row['middle']!=''?$row['middle'].' ':'').($row['last']!=''?$row['last'].' ':'').($row['suffix']!=''?$row['suffix']:'').($row['alias']!=''?$row['alias']:'');
   return trim($name);
 }else
   return false;
}

function file_as($name,$tipo){
  $count=0;

  // print "FILE AS:\n";
  // print_r($name);
  switch($tipo){
  case('company'):
    $name=preg_replace('/[^a-z^\d^\s^\@]/i','',$name);
    
    $name=_trim(mb_ucwords($name));
    
    // things to remove
    

    $regex='/^the\s+|^el\s+|^la\s+|^los\s+|^las\s+/i';
    if(preg_match($regex,$name,$matches)){
      $article=_trim(mb_ucwords($matches[0]));
      $oname=preg_replace($regex,'',$name);
      $oname=$oname.', '.$article;
      return $oname;
    } 
    
    return $name;
    break;
  case('person'):

    $oname=$name['last'].', '.$name['prefix'].' '.$name['first'].' '.$name['middle'];
    
   $oname=preg_replace('/^,\s*/','',$oname);
   $oname=preg_replace('/\s*$/','',$oname);
   $oname=preg_replace('/\s{2,}/',' ',$oname);
   if($oname=='')
     $oname=$name['alias'];
    return $oname;
    break;

  }

}

function insert_name($name_data,$contact_id){
   $db =& MDB2::singleton();
 $prefix=($name_data['prefix']!=''?'"'.addslashes(trim($name_data['prefix'])).'"':'null');
 $first=($name_data['first']!=''?'"'.addslashes($name_data['first']).'"':'null');
 $middle=($name_data['middle']!=''?'"'.addslashes($name_data['middle']).'"':'null');
 $last=($name_data['last']!=''?'"'.addslashes($name_data['last']).'"':'null');
 $suffix=($name_data['suffix']!=''?'"'.addslashes($name_data['suffix']).'"':'null');
 $alias=($name_data['alias']!=''?'"'.addslashes($name_data['alias']).'"':'null');
 $sql=sprintf("insert into name (prefix,first,middle,last,suffix,alias,contact_id,genero) values (%s,%s,%s,%s,%s,%s,%d,%d)",
	      $prefix,$first,$middle,$last,$suffix,$alias,$contact_id,$name_data['genero']
	      );
 // print "$sql\n";
 //mysql_query($sql);
 //$name_id=$db->lastInsertID();

 mysql_query($sql);
 $name_id=mysql_insert_id();

 return $name_id;
}

function update_name($contact_id,$name_data){
   $db =& MDB2::singleton();
 $prefix=($name_data['prefix']!=''?'"'.addslashes(trim($name_data['prefix'])).'"':'null');
 $first=($name_data['first']!=''?'"'.addslashes($name_data['first']).'"':'null');
 $middle=($name_data['middle']!=''?'"'.addslashes($name_data['middle']).'"':'null');
 $last=($name_data['last']!=''?'"'.addslashes($name_data['last']).'"':'null');
 $suffix=($name_data['suffix']!=''?'"'.addslashes($name_data['suffix']).'"':'null');
 $alias=($name_data['alias']!=''?'"'.addslashes($name_data['alias']).'"':'null');
 $sql=sprintf("update name set  prefix=%s,first=%s,middle=%s,last=%s,suffix=%s,alias=%s where contact_id=%d",
	      $prefix,$first,$middle,$last,$suffix,$alias,$contact_id,$contact_id
	      );

 // print "$sql";
 //mysql_query($sql);
 mysql_query($sql);





}
// test
// require_once 'MDB2.php';  
// $dns_pwd='ajolote11';
// $dns_db='aw';
// $dns_user='root';
// $dsn = 'mysql://'.$dns_user.':'.$dns_pwd.'@localhost/'.$dns_db;
// $db =& MDB2::singleton($dsn);  
//   
// print_r(guess_name('raul perusquia del cueto'));


function guess_name($raw_name){
  //  print "raw name:$raw_name\n";
  $name=array(
	      'genero'=>0,
	      'prefix'=>'',
	      'first'=>'',
	      'middle'=>'',
	      'last'=>'',
	      'suffix'=>'',
	      'alias'=>''
	      );

  $raw_name=preg_replace('/\./',' ',$raw_name);
  $raw_name=preg_replace('/^\s*/','',$raw_name);
  $raw_name=preg_replace('/\s*$/','',$raw_name);
  $names=preg_split('/\s+/',$raw_name);

  $parts=count($names);
  switch($parts){
  case(1):
    if(is_surname($names[0]))
      $name['last']=$names[0];
    else if(is_givenname($names[0]))
      $name['first']=$names[0];
    else if(is_prefix($names[0]))
      $name['prefix']=$names[0];
    else
      $name['alias']=$names[0];
    break;
  case(2):
    // firt the most obious choise
    
    if( is_givenname($names[0])){
      $name['first']=$names[0];
      $name['last']=$names[1];
      

    }else if( is_givenname($names[0]) and   is_surname($names[1])){
      $name['first']=$names[0];
      $name['last']=$names[1];

    }else if( is_prefix($names[0]) and   is_surname($names[1])){
      $name['prefix']=$names[0];
      $name['last']=$names[1];
    }else if( is_prefix($names[0]) and   is_givenname($names[1])){
      $name['prefix']=$names[0];
      $name['first']=$names[1];
    }else if( is_surname($names[0]) and   is_surname($names[1])){
      $name['last']=$names[0].' '.$names[1];
    }else{
      $name['first']=$names[0];
      $name['last']=$names[1];

    }
    break;
  case(3):
    // firt the most obious choise

    if(!is_prefix($names[0]) and  strlen($names[1])==1   and   strlen($names[2])>1  ){
      $name['first']=$names[0];
      $name['middle']=$names[1];
      $name['last']=$names[2];
    }elseif( is_prefix($names[0])) {
	$name['prefix']=$names[0];
	$name['first']=$names[1];
	$name['last']=$names[2];

// 	if(   is_givenname($names[1]) and   is_surname($names[2])){

// 	  $name['first']=$names[1];
// 	  $name['last']=$names[2];
// 	}else if(    strlen($names[1])==1 and   is_surname($names[2])){
	  
// 	  $name['first']=$names[1];
// 	  $name['last']=$names[2];
// 	}else if(   is_givenname($names[1])    and   is_givenname($names[2])){
	  
// 	  $name['first']=$names[1].' '.$names[2];
// 	}else if(  is_surname($names[1])    and   is_surname($names[2])){
	  
// 	  $name['last']=$names[1].' '.$names[2];
// 	}else{
// 	  $name['first']=$names[1];
// 	  $name['last']=$names[2];
	  
// 	}
	

      }else if(  is_givenname($names[0])   and   is_givenname($names[1])  and   is_surname($names[2])){
	$name['first']=$names[0].' '.$names[1];
	$name['last']=$names[2];
      }else if(  is_givenname($names[0])   and   is_surname($names[1])  and   is_surname($names[2])){
	$name['first']=$names[0];
	$name['last']=$names[1].' '.$names[2];
      }else if( is_givenname($names[0]) and     strlen($names[1])==1 and   is_surname($names[2])){
	$name['first']=$names[0];
	$name['middle']=$names[1];
	$name['last']=$names[2];
      }else{
	$name['first']=$names[0];
	$name['last']=$names[1].' '.$names[2];
      }
      break;
    case(4):


      
  if( is_prefix($names[0])) {
	$name['prefix']=$names[0];
	
	if(  is_givenname($names[1]) and    strlen($names[2])==1 and  is_surname($names[3])){

	  $name['first']=$names[1];
	  $name['middle']=$names[2];
	  $name['last']=$names[3];
	}else if(  is_givenname($names[1]) and   is_givenname($names[2])  and  is_surname($names[3])){

	  $name['first']=$names[1].' '.$names[2];
	  $name['last']=$names[3];
	}else if( is_prefix($names[0]) and     is_givenname($names[1]) and   is_surname($names[2])  and  is_surname($names[3])){
	  
	  $name['first']=$names[1];
	  $name['last']=$names[2].' '.$names[3];
	  
	}else
	  $name['first']=$names[1].' '.$names[2];
	  $name['last']=$names[3];
	

    // firt the most obious choise
  }else if(      is_givenname($names[0]) and is_givenname($names[1]) and    is_surname($names[2])  and  is_surname($names[3])     ){

      $name['first']=$names[0].' '.$names[1];
      $name['last']=$names[2].' '.$names[3];
    }else  if(      is_givenname($names[0]) and is_givenname($names[1]) and    is_givenname($names[2])  and  is_surname($names[3])     ){

      $name['first']=$names[0].' '.$names[1].' '.$names[2];
      $name['last']=$names[3];
    }else{
      $name['first']=$names[0];
      $name['last']=$names[1].' '.$names[2].' '.$names[3];
    }
    break;
  case(5):
      if( is_prefix($names[0]) and     is_givenname($names[1]) and   is_givenname($names[2])   and  is_surname($names[3]) and is_surname($names[4])  ){
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

  $name['genero']=get_genero($name['prefix'],$name['first']);
  

  foreach($name as $key=>$value){
    $name[$key]=mb_ucwords($value);
     
  }


  return $name;
}


function get_genero($prefix,$raw_name){
 $db =& MDB2::singleton();

  $sql=sprintf("select genero from  list_prefixname where name='%s' ",addslashes(strtolower(trim($prefix))));
  // print "$sql\n"; 
 $res=mysql_query($sql);
  if ($row=$res->fetchRow()){
    if($row['genero']==1)
      return 1;
    if($row['genero']==2)
      return 2;
  }
  

  $male=0;
  $felame=0;
  $names=split(' ',$raw_name);
  foreach($names as $name){
    $sql=sprintf("select genero from  list_firstname where name='%s'",addslashes(strtolower(trim($name))));
    //  print "$sql\n";
    $res=mysql_query($sql);
    if ($row=$res->fetchRow()){
      if($row['genero']==1)
	$male++;
      if($row['genero']==2)
	$felame++;
    }
  }
  if($felame>$male)
    return 2;
  else if ($male>$felame)
    return 1;
  else
    return 0;
  
}

function is_givenname($name){
  $db =& MDB2::singleton();
  $sql=sprintf("select id from  list_firstname where name='%s'",addslashes(strtolower(trim($name))));
  $res2 = mysql_query($sql); 
  if ($row2=$res2->fetchRow()){
    return $row2['id'];
  }else
    return 0;
}

function is_surname($name){
  $db =& MDB2::singleton();
  $sql=sprintf("select id from  list_lastname where name='%s'",addslashes(strtolower(trim($name))));
  $res2 = mysql_query($sql); 
  if ($row2=$res2->fetchRow()){
    return $row2['id'];
  }else
    return 0;
}
function is_prefix($name){
  $db =& MDB2::singleton();
  $sql=sprintf("select id from  list_prefixname where name='%s'",addslashes(strtolower(trim($name))));

  $res2 = mysql_query($sql); 
  if ($row2=$res2->fetchRow()){
    return $row2['id'];
  }else
    return 0;
}



function prepare_name($c_name,$c_fname){

  //try to get prefixes
  $tipo=1;
  $prefix='';
  $fname='';

  $c_name=trim($c_name);
  $c_name=trim($c_name);
  if(preg_match('/^Mr\s/',$c_name)){
    $tipo=1;
    $prefix='Mr';
    $c_name=preg_replace('/^Mr\s/','',$c_name);
  }elseif(preg_match('/^Mr.\s/',$c_name)){
    $tipo=1;
    $prefix='Mr';
    $c_name=preg_replace('/^Mr.\s/','',$c_name);
  }elseif(preg_match('/^Mrs.\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Mrs.\s/','',$c_name);
  }elseif(preg_match('/^Mrs\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Mrs\s/','',$c_name);

	      
  }elseif(preg_match('/^Ms.\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Ms.\s/','',$c_name);
  }elseif(preg_match('/^Ms\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Ms\s/','',$c_name);
  }elseif(preg_match('/^Miss\s/',$c_name)){
    $tipo=2;
    $prefix='Miss';
    $c_name=preg_replace('/^Miss\s/','',$c_name);
  }
  $c_name=trim($c_name);
  //print "xxx $c_fname XXX $c_name  ";

  $tmp=str_replace("/",'\/',$c_fname);
  // print "$tmp\n";
  if($c_fname!='' and preg_match('/^'.$tmp.'/',$c_name)){
    $fname=$c_fname;
    //print "xx $c_fname -> $c_name\n";
    // $cname=str_replace($c_fname,'',$c_name);
    $c_name=preg_replace('/'.$c_fname.'/','',$c_name);//++++ Esto esta mal!!!! no se por que

    //print "xx $c_fname -> $c_name\n";

    //$c_name=preg_replace('/'.$c_fname.'/','',$c_name);//++++ Esto esta mal!!!! no se por que
    $c_name=mb_ucwords(trim($c_name));

    $order=$c_name.' '.$fname;
  }else
    $order=$c_name;
  //	    print "$fname $c_name";
  //	    exit;
  $name=trim(trim($prefix.' '.$fname.' '.$c_name));
  $oname=trim(trim($c_name.' '.$fname));
  return array($tipo,$prefix,$fname,$c_name,'',$name,$oname,'','');

}








?>