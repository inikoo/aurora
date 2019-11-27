<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Moved:   27 November 2019  09:33::40  +0100 Mijas Costa, Spain
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/

class fake_session {
    function __construct() {
        $this->data = array();
    }

    function set($key, $value) {
        $this->data[$key] = $value;
    }

    function get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return false;
        }
    }
}
