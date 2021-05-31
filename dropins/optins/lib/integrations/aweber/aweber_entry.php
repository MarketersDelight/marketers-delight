<?php

class AWeberEntry extends AWeberResponse {

    protected $_privateData = array(
        'resource_type_link',
        'http_etag',
    );
    protected $_localDiff = array();
    protected $_collections = array();

    public function attrs() {
        $attrs = array();
        foreach ($this->data as $key => $value) {
            if (!in_array($key, $this->_privateData) && !strpos($key, 'collection_link')) {
                $attrs[$key] = $value;
            }
        }
        if (!empty(AWeberAPI::$_collectionMap[$this->type])) {
            foreach (AWeberAPI::$_collectionMap[$this->type] as $child) {
                $attrs[$child] = 'collection';
            }
        }
        return $attrs;
    }

    protected function _type() {
        if (empty($this->type)) {
            $typeLink = $this->data['resource_type_link'];
            if (empty($typeLink)) return null;
            list($url, $type) = explode('#', $typeLink);
            $this->type = $type;
        }
        return $this->type;
    }

    public function delete() {
        $this->adapter->request('DELETE', $this->url, array(), array('return' => 'status'));
        return true;
    }

    public function move($list) {
        $params = array('ws.op' => 'move', 'list_link' => $list->self_link);
        $data = $this->adapter->request('POST', $this->url, $params, array('return' => 'headers'));
        $url = $data['Location'];
        $resource_data = $this->adapter->request('GET', $url);
        return new AWeberEntry($resource_data, $url, $this->adapter);
    }

    public function save() {
        if (!empty($this->_localDiff)) {
            $data = $this->adapter->request('PATCH', $this->url, $this->_localDiff, array('return' => 'status'));
        }
        $this->_localDiff = array();
        return true;

    }

    public function __get($value) {
        if (in_array($value, $this->_privateData)) {
            return null;
        }
        if (!empty($this->data) && array_key_exists($value, $this->data)) {
            if (is_array($this->data[$value])) {
                $array = new AWeberEntryDataArray($this->data[$value], $value, $this);
                $this->data[$value] = $array;
            }
            return $this->data[$value];
        }
        if ($value == 'type') return $this->_type();
        if ($this->_isChildCollection($value)) {
            return $this->_getCollection($value);
        }
        throw new AWeberResourceNotImplemented($this, $value);
    }

    public function __set($key, $value) {
        if (array_key_exists($key, $this->data)) {
            $this->_localDiff[$key] = $value;
            return $this->data[$key] = $value;
        } else {
            return parent::__set($key, $value);
        }
    }

    public function findSubscribers($search_data) {
        $this->_methodFor(array('account'));
        $params = array_merge($search_data, array('ws.op' => 'findSubscribers'));
        $data = $this->adapter->request('GET', $this->url, $params);
        $ts_params = array_merge($params, array('ws.show' => 'total_size'));
        $total_size = $this->adapter->request('GET', $this->url, $ts_params, array('return' => 'integer'));
        $data['total_size'] = $total_size;
        $url = $this->url . '?'. http_build_query($params);
        return new AWeberCollection($data, $url, $this->adapter);
    }

    public function getActivity() {
        $this->_methodFor(array('subscriber'));
        $params = array('ws.op' => 'getActivity');
        $data = $this->adapter->request('GET', $this->url, $params);
        $ts_params = array_merge($params, array('ws.show' => 'total_size'));
        $total_size = $this->adapter->request('GET', $this->url, $ts_params, array('return' => 'integer'));
        $data['total_size'] = $total_size;
        $url = $this->url . '?'. http_build_query($params);
        return new AWeberCollection($data, $url, $this->adapter);
    }

    public function getParentEntry(){
        $url_parts = explode('/', $this->url);
        $size = count($url_parts);
        $url = substr($this->url, 0, -strlen($url_parts[$size-1])-1);
        $url = substr($url, 0, -strlen($url_parts[$size-2])-1);
        try {
            $data = $this->adapter->request('GET', $url);
            return new AWeberEntry($data, $url, $this->adapter);
        } catch (Exception $e) {
            return NULL;
        }
    }

    public function getWebForms() {
        $this->_methodFor(array('account'));
        $data = $this->adapter->request('GET', $this->url.'?ws.op=getWebForms', array(),
            array('allow_empty' => true));
        return $this->_parseNamedOperation($data);
    }

    public function getWebFormSplitTests() {
        $this->_methodFor(array('account'));
        $data = $this->adapter->request('GET', $this->url.'?ws.op=getWebFormSplitTests', array(),
            array('allow_empty' => true));
        return $this->_parseNamedOperation($data);
    }

    protected function _parseNamedOperation($data) {
        $results = array();
        foreach($data as $entryData) {
            $results[] = new AWeberEntry($entryData, str_replace($this->adapter->app->getBaseUri(), '',
               $entryData['self_link']), $this->adapter);
        }
        return $results;
    }

    protected function _methodFor($entryTypes) {
        if (in_array($this->type, $entryTypes)) return true;
        throw new AWeberMethodNotImplemented($this);
    }

    protected function _getCollection($value) {
        if (empty($this->_collections[$value])) {
            $url = "{$this->url}/{$value}";
            $data = $this->adapter->request('GET', $url);
            $this->_collections[$value] = new AWeberCollection($data, $url, $this->adapter);
        }
        return $this->_collections[$value];
    }

    protected function _isChildCollection($value) {
        $this->_type();
        if (!empty(AWeberAPI::$_collectionMap[$this->type]) &&
            in_array($value, AWeberAPI::$_collectionMap[$this->type])) return true;
        return false;
    }

}