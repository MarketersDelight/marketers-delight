<?php

class AWeberResponse extends AWeberAPIBase {

    public $adapter = false;
    public $data = array();
    public $_dynamicData = array();

    public function __construct($response, $url, $adapter) {
        $this->adapter = $adapter;
        $this->url     = $url;
        $this->data    = $response;
    }

    public function __set($key, $value) {
        $this->{$key} = $value;
    }

    public function __get($value) {
        if (in_array($value, $this->_privateData)) {
            return null;
        }
        if (array_key_exists($value, $this->data)) {
            return $this->data[$value];
        }
        if ($value == 'type') return $this->_type();
    }

}