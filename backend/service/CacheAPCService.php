<?php

class CacheAPCService {

    public $iTtl; // Time To Live
    public $bEnabled = false; // APC enabled
    private static $instancia;

    // constructor
    function CacheAPCService() {

        $this->bEnabled = extension_loaded('apc');
    }

    public static function getInstance() {
        if (!self::$instancia instanceof self) {
            self::$instancia = new self;
        }
        return self::$instancia;
    }

    // get data from memory
    public function getData($sKey) {
        $bRes = false;
        $vData = apc_fetch($sKey, $bRes);
        return ($bRes) ? $vData : null;
    }

    // save data to memory
    public function setData($sKey, $vData, $time = 600) {

        $this->iTtl = $time;
        return apc_store($sKey, $vData, $this->iTtl);
    }

    // delete data from memory
    public function delData($sKey) {
        $bRes = false;
        apc_fetch($sKey, $bRes);
        return ($bRes) ? apc_delete($sKey) : true;
    }

    // clear
    public function clearCache() {
        apc_clear_cache('user');
    }

}

