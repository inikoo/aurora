<?php
/*
  
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/


class LightCustomer extends DB_Table {
  
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
                return $this->data['Customer Name'];
                break;
            case('contact'):
                return $this->data['Customer Main Contact Name'];
                break;
            case('email'):
                return $this->data['Customer Main Plain Email'];
                break;
            case('address'):
                return $this->data['Customer Main XHTML Address'];
                break;    
            case('address'):
                return $this->data['Customer Main XHTML Address'];
                break;

                               
            default:
                return false;
                break;
        }
            
        
    
        return false;
    }
    
 

}
?>
