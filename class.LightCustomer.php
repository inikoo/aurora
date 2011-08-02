<?php
/*
  
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/


class LightCustomer{
  
  var $id=false;
  var $data=array();
  
    function __construct($arg1=false,$arg2=false) {
        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id',$arg1);
            return;
        }
   
        $this->get_data($arg1,$arg2);


    }

    function get_data($tag,$id) {
        if ($tag=='id')
            $sql=sprintf("select * from `Customer Dimension` where `Customer Key`=%s",prepare_mysql($id));
        elseif($tag=='email')
        $sql=sprintf("select * from `Customer Dimension` where `Customer Email`=%s",prepare_mysql($id));
        elseif($tag=='all') {
            $this->find($id);
            return true;
        }
        else
            return false;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->id=$this->data['Customer Key'];
        }
    }
 
 
    function get($key){
 
        switch ($key) {
            case('name'):
                return ($this->data['Customer Name']==''?_('Customer'):$this->data['Customer Name']);
                break;
            case('contact'):
                return ($this->data['Customer Main Contact Name']==''?_('Customer'):$this->data['Customer Main Contact Name']);
                break;
            case('email'):
                return $this->data['Customer Main Plain Email'];
                break;
            case('address'):
                return $this->data['Customer Main XHTML Address'];
                break;    
          
                case('greting'):
            case('greeting'):
             case('gretings'):
             case('greetings'):
                if($this->data['Customer Name']=='' and $this->data['Customer Main Contact Name']=='')
                    return _('To whom it corresponds');
                $greeting=_('Dear').' '.$this->data['Customer Main Contact Name'];
                if($this->data['Customer Type']=='Company'){
                  $greeting.=', '.$this->data['Customer Main Name'];
                }
                return $greeting;         
                break;
                               
            default:
                return false;
                break;
        }
            
        
    
        return false;
    }
    
 
 function generatePassword($length=9, $strength=0) {
    $vowels = 'aeuy'.md5(mt_rand());
    $consonants = 'bdghjmnpqrstvz'.md5(mt_rand());
    if ($strength & 1) {
        $consonants .= 'BDGHJLMNPQRSTVWXZlkjhgfduytrdqwertyuipasdfghjkzxcvbnm';
    }
    if ($strength & 2) {
        $vowels .= "AEUI";
    }
    if ($strength & 4) {
        $consonants .= '2345678906789$%^&*(';
    }
    if ($strength & 8) {
        $consonants .= '!=/[]{}~\<>$%^&*()_+@#.,)(*%%';
    }

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(mt_rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(mt_rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}
 
    function create_user() {
        include_once('class.User.php');
        
        $password=this->generatePassword(8,10);
        
        $data=array(
                  'User Handle'=>$this->data['Customer Main Plain Email']
                                ,'User Type'=>'Customer_'.$this->data['Customer Store Key']
                                             ,'User Password'=>md5($password)
                                                              ,'User Active'=>'Yes'
                                                                             ,'User Alias'=>$this->data['Customer Name']
                                                                                           ,'User Parent Key'=>$this->data['Customer Key']
              );
        // print_r($data);
        $user=new user('new',$data);
        if (!$user->id) {
            $this->error=true;
            $this->msg=$user->msg;
            $this->user_key=0;

        } else {
            $this->user_key=$user->id;

        }



    }

 

}
?>
