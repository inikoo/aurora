<?php
/*
 File: Auth.php 

 Authenticatin Class
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('aes.php');
class Auth {

  private $user_key=false;
  private $status=false;
  private $use_cookies=false;
  
  function Auth($ikey,$skey,$options=''){
    if(preg_match('/use( |\_)cookies?/i',$options))
      $this->use_cookies=true;
    

    $this->ikey=$ikey;
    $this->skey=$skey;

      $this->pass=array(
		      'handle'=>'No'
		      ,'handle_in_use'=>'No'
		      ,'password'=>'No'
		      ,'time'=>'No'
		      ,'ip'=>'No'
		      ,'ikey'=>'No'
		      );


  }


  function authenticate($handle=null,$sk=null){
    
    if($handle and $sk){
      $this->handle=$handle;
      $this->sk=$sk;
      $this->authenticate_from_login();
    }elseif($this->use_cookies){
      $this->authenticate_from_cookie();
    }

  }

  function is_authenticated(){
    return $this->status;

  }

  function authenticate_from_cookie(){

  }

  function authenticate_from_login(){

    include_once('aes.php');
    $this->status=false;
    
    $this->pass=array(
		      'handle'=>'No'
		      ,'handle_in_use'=>'No'
		      ,'password'=>'No'
		      ,'time'=>'No'
		      ,'ip'=>'No'
		      ,'ikey'=>'No'
		      );
    
    $sql=sprintf("select `User Key`,`User Password` from `User Dimension` where `User Handle`=%s and `User Active`='Yes' "
		 ,prepare_mysql($this->handle)
		 
		 );
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
       	$st=AESDecryptCtr(AESDecryptCtr($this->sk,$row['User Password'],256),$this->skey,256);
	$this->pass['handle']='Yes';
	$this->pass['handle_in_use']='Yes';
	if(preg_match('/^skstart\|\d+\|[abcdef0-9\.\:]+\|.+\|/',$st)){
	  $this->pass['password']='Yes';


	  $data=preg_split('/\|/',$st);
	  $time=$data[1];
 	  $ip=$data[2];
 	  $ikey=$data[3];
	  
	  $pass_tests=true;

	  if($time<time(date('U'))  ){
	    $pass_tests=false;
	    //print date("d-m-Y H:i:s",$time).' '.date("d-m-Y H:i:s",date('U')+300);

	  }else
	    $this->pass['time']='Yes';

	  if(ip()!=$ip){
	    $pass_tests=false;
	  }
	  $this->pass['ip']='Yes';
	  
	  if($this->ikey!=$ikey){
	    $pass_tests=false;
	  }
	  $this->pass['ikey']='Yes';


 	  if($pass_tests ){

	    $this->status=true;
	    $this->user_key=$row['User Key'];
	  }
	  
	}
      

    }else{
      //TODO log failed atteps 


    }
    
    

    
  }

  public function get_user_key(){
    return $this->user_key;
  }
  

  }
?>
