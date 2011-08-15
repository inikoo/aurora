<?php
/*

  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/


class LightCustomer {

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




    function get_greetings($locale=false) {

        if ($locale) {

            if (preg_match('/^es_/',$locale)) {
                $unknown_name='A quien corresponda';
                $greeting_prefix='Estimado';
            } else {
                $unknown_name='To whom it corresponds';
                $greeting_prefix='Dear';
            }



        } else{
            $unknown_name='To whom it corresponds';
             $greeting_prefix='Dear';
        }
        if ($this->data['Customer Name']=='' and $this->data['Customer Main Contact Name']=='')
            return $unknown_name;
        $greeting=$greeting_prefix.' '.$this->data['Customer Main Contact Name'];
        if ($this->data['Customer Type']=='Company') {
            $greeting.=', '.$this->data['Customer Name'];
        }
        return $greeting;

    }


    function get($key) {

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
            return $this->get_greetings();

            break;

        default:
            return false;
            break;
        }



        return false;
    }



}
?>
