<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   07 January 2020  15:03::19  +0800Singapore

 Copyright (c) 2020, Inikoo

 Version 3.0
*/

function get_cached_object_data($redis,$account_code,$object,$object_key){
    $redis_key='Au_Cached_obj'.$account_code.'.'.$object.'.'.$object_key;
    if ($redis->exists('Au_Cached_obj'.$account_code.'.'.$object.'.'.$object_key) ) {
        return json_decode($redis->get($redis_key),true);
    }else{
        $store=get_object($object,$object_key);
        return json_decode($store->cache_object($redis,$account_code),true);
    }


}
