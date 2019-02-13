<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  13 February 2019 at 16:51:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

class email_recipient {

    public $data;
    public $id;


    function __construct($id,$data) {

        $this->data=$data;
        $this->id=$id;


    }


    function get_object_name() {

        return $this->get('object_name');


    }

    function get_greetings(){
        return $this->get('Name');
    }


    function get($key) {

        if(array_key_exists($key,$this->data)){
            return $this->data[$key];
        }else{
            return '';
        }


    }


}