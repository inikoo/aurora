<?php
class Dummy_Customer {


	var $id=0;	

    public function get($key) {
        return $key;
    }
    function get_hello() {


        return _('Hello, Customer Name');

    }



}
?>