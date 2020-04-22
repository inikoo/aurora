<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 22 April 2020  15:50::37  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

class Smarty_CacheResource_Redis extends Smarty_CacheResource_KeyValueStore {

    protected $redis;


    public function __construct() {

        $this->redis = new Redis();
        $this->redis->connect(REDIS_HOST, REDIS_PORT);
        $this->redis->select(REDIS_SMARTY_CACHE_DB);

    }

    /**
     * Read values for a set of keys from cache
     *
     * @param array $keys list of keys to fetch
     *
     * @return array list of values with the given keys used as indexes
     * @return boolean true on success, false on failure
     */
    protected function read(array $keys) {

        $_res = array();

        $res = $this->redis->mGet($keys);

        foreach ($res as $k => $v) {
            $_res[$keys[$k]] = $v;
        }


        return $_res;
    }

    /**
     * Save values for a set of keys to cache.
     *
     * @param array $keys   list of values to save
     * @param int   $expire expiration time
     *
     * @return bool true on success, false on failure
     */
    protected function write(array $keys, $expire = null) {


        foreach ($keys as $k => $v) {
            if ($expire == null) {
                $this->redis->setEx($k, $expire, $v);
            } else {
                $this->redis->set($k, $v);
            }

        }

        return true;
    }

    /**
     * Remove values from cache.
     *
     * @param array $keys list of keys to delete
     *
     * @return bool true on success, false on failure
     */
    protected function delete(array $keys) {



        $this->redis->del($keys);

        return true;
    }


    protected function purge() {

        $this->redis->flushdb();

        return true;
    }
}