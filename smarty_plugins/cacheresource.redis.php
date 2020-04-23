<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 22 April 2020  15:50::37  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

class Smarty_CacheResource_Redis extends Smarty_CacheResource_KeyValueStore {

    protected $redis_read;
    protected $redis_write;

    public function __construct() {

        $this->redis_read  = false;
        $this->redis_write = false;


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


        if (!$this->redis_read) {
            $this->redis_read = new Redis();
            $this->redis_read->connect(REDIS_HOST, REDIS_READ_ONLY_PORT);
            $this->redis_read->select(REDIS_SMARTY_CACHE_DB);
        }


        $_res = array();
        $res  = $this->redis_read->mGet($keys);
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

        if (!$this->redis_write) {
            $this->init_redis_write();
        }

        foreach ($keys as $k => $v) {
            if ($expire == null) {
                $this->redis_write->setEx($k, $expire, $v);
            } else {
                $this->redis_write->set($k, $v);
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

        if (!$this->redis_write) {
            $this->init_redis_write();
        }
        $this->redis_write->del($keys);

        return true;
    }


    protected function purge() {
        if (!$this->redis_write) {
            $this->init_redis_write();
        }
        $this->redis_write->flushdb();

        return true;
    }

    protected function init_redis_write() {
        $this->redis_write = new Redis();
        $this->redis_write->connect(REDIS_HOST, REDIS_PORT);
        $this->redis_write->select(REDIS_SMARTY_CACHE_DB);
    }

}