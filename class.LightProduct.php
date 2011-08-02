<?php
/*
  
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/


class LightProduct{
  
  var $id=false;
  var $data=array();
  
    function __construct($arg1) {
    
   
        $this->get_data('code',$arg2);


    }

    function get_data($tag,$id) {
        if ($tag=='id')
            $sql=sprintf("select * from `Product Dimension` where `Product Key`=%s",prepare_mysql($id));
        elseif($tag=='code')
        $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s",prepare_mysql($id));
        elseif($tag=='all') {
            $this->find($id);
            return true;
        }
        else
            return false;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->id=$this->data['Product Key'];
        }
    }
 
 
    function get($key){
 
        switch ($key) {
       
                               
            default:
                return false;
                break;
        }
            
        
    
        return false;
    }
    
 

}
?>
