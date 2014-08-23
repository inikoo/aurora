<?php

include_once('../../conf/dns.php');
include_once('../../class.Product.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
require_once '../../myconf/conf.php';           
mysql_query("SET time_zone ='+0:00'");
date_default_timezone_set('UTC');



$ftp_data=array(
	       'ftp_server'=>"freeolaweb4.freeola.net",
		'ftp_user'=>"sr0176514",
		'ftp_pass'=>"3356dnx0"
		);
$res=get_pages($ftp_data);
//update_file('files/bcp/index.html');

function update_file($filename,$original_file){
 $db =& MDB2::singleton();

 print "$filename\n";
 
  $txt=file_get_contents($filename);
  
 
  if(preg_match('/\<title\>.*\<\/title\>/i',$txt,$match)){

    $title=preg_replace('/\<title\>|\<\/title\>/i','',$match[0]);
  }

  $codes=array();
   $sdesc=array();
  $regex_list='/print_list_form\(\'[^\']*/i';
  $regex_list2='/print_list_form\(\'[^\;]*/i';


 $regex_indv='/\<\?\$pn\=\'[^\']*/i';
  
  if(preg_match_all($regex_list,$txt,$matches)){
    foreach($matches[0] as $match){
      $code=preg_replace('/print_list_form\(\'/','',$match);
      $codes[]=$code;
    }
  }

  if(preg_match_all($regex_list2,$txt,$matches)){
    foreach($matches[0] as $match){
      $code=preg_split('/\',\'/',$match);
      $_code=trim(preg_replace('/print_list_form\(\'/','',$code[0]));
      $sdesc[]=array($_code,trim(trim(trim($code[2]))));
    }
  }

if(preg_match_all($regex_indv,$txt,$matches)){
    foreach($matches[0] as $match){
      $code=preg_replace('/\<\?\$pn=\'/','',$match);
      $codes[]=$code;
    }
  }
   
 $original_file=preg_replace('/htdocs/','www.ancientwisdom.biz',$original_file);
 print "url $original_file \n";
   print "title $title \n";
 print_r($codes);
  foreach($codes as $code){
    $product=new Product('code',$code);
    if($product->id){
      $data_toupdate[]=array('key'=>'weblink','value'=>$original_file,'title'=>$title);
      $product->update($data_toupdate,'save');
    }else
      print"Warning!! ->$code<- not found\n";
    
  }
  
//    $sql=sprintf("select id from product where  code='%s'",addslashes($code));
//    $res = mysql_query($sql);
//    if($x=$res->fetchRow()) {
//      $id=$x['id'];
//       $sql=sprintf("update product set inweb=1 where id='%d'",$id);
//        print "$sql\n";
//       mysql_query($sql);
//    }else
//      print"Warning!! ->$code<- not founs\n";
   

//  }
//  foreach($sdesc as $sd){
//    $sql=sprintf("update product set sdescription='%s'  where code='%s'",addslashes($sd[1]),addslashes($sd[0]));
//    //   print "$sql\n";
//     mysql_query($sql);
//  }


}

function get_pages($ftp_data){




  //error_reporting(0);
  $res='';
  $ftp_server =$ftp_data['ftp_server'];
  $ftp_user = $ftp_data['ftp_user'];
  $ftp_pass = $ftp_data['ftp_pass'];
  
  $_dir="htdocs/forms/";
  //$_dir="htdocs/bagsbags/";

  if($conn_id = ftp_connect($ftp_server)){


  if($login_result = ftp_login($conn_id, $ftp_user, $ftp_pass)){
    
    $contents = ftp_rawlist($conn_id, $_dir);

    foreach($contents as $dir){
      if(preg_match('/^d.*/i',$dir)){
	//	preg_match('/[a-z0-9]+$/i',$dir,$match);
	preg_match('/[a-z0-9]+$/i',$dir,$match[0]);
	$dir_name=$match[0][0];

	//	print "- $dir_name\n";
	$contents2 = ftp_nlist($conn_id, $_dir.$dir_name."/");
	foreach($contents2 as $file){
	  if(preg_match('/^(index|page).*\.php$/i',$file)){
	    //  print "\t$file\n";
	    
	    $local_file="files/$dir_name/$file";
	    $server_file=$_dir."$dir_name/$file";
	    if (!file_exists("files/$dir_name"))
	      mkdir("files/$dir_name");
	    if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {


	      update_file($local_file,$server_file);
	      

	    } else {
	      echo "There was a problem\n";
	    }

	  }
	  
	}

	




      }
	

    }
    
    //   $remote_file='htdocs/db/data/'.$fam.'.php';
    // $handle = fopen($local_file, 'w');

  }
  ftp_close($conn_id);  
  }else
    print "cannot conect to the ftp\n";
}



?>
