<?php

class AWeberCollection extends AWeberResponse implements ArrayAccess, Iterator, Countable
{

    protected $pageSize = 100;
    protected $_entries = array();

    protected $_privateData = array(
        'entries',
        'start',
        'next_collection_link',
    );
    protected $_iterationKey = 0;

    public function getById($id)
    {
        $data = $this->adapter->request('GET', "{$this->url}/{$id}");
        return $this->_makeEntry($data, $id, "{$this->url}/{$id}");
    }

    protected function _makeEntry($data, $id = false, $url = false)
    {
        if ((!$url) or (!$id)) {
            $url = $this->adapter->app->removeBaseUri($data['self_link']);
        } else {
            $url = "{$this->url}/{$id}";
        }
        return new AWeberEntry($data, $url, $this->adapter);
    }

    public function create($kv_pairs)
    {
        $params = array_merge(array('ws.op' => 'create'), $kv_pairs);
        $data = $this->adapter->request('POST', $this->url, $params, array('return' => 'headers'));
        $this->_entries = array();
        $url = $data['Location'];
        $resource_data = $this->adapter->request('GET', $url);
        return new AWeberEntry($resource_data, $url, $this->adapter);
    }

    public function find($search_data)
    {
        $params = array_merge($search_data, array('ws.op' => 'find'));
        $data = $this->adapter->request('GET', $this->url, $params);
        $ts_params = array_merge($params, array('ws.show' => 'total_size'));
        $total_size = $this->adapter->request('GET', $this->url, $ts_params, array('return' => 'integer'));
        $data['total_size'] = $total_size;
        return $this->readResponse($data, $this->url);
    }

    public function getParentEntry()
    {
        $url_parts = explode('/', $this->url);
        $size = count($url_parts);
        $url = substr($this->url, 0, -strlen($url_parts[$size - 1]) - 1);
        try {
            $data = $this->adapter->request('GET', $url);
            return new AWeberEntry($data, $url, $this->adapter);
        } catch (Exception $e) {
            return NULL;
        }
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }

    public function current()
    {
        return $this->offsetGet($this->_iterationKey);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) return null;
        if (!empty($this->_entries[$offset])) return $this->_entries[$offset];

        $this->_entries[$offset] = $this->_makeEntry($this->_getEntry($offset));
        return $this->_entries[$offset];
    }

    public function offsetExists($offset)
    {
        if ($offset >= 0 && $offset < $this->total_size) {
            return true;
        }
        return false;
    }

    protected function _getEntry($offset)
    {
        if (empty($this->data['entries'][$offset])) {
            $this->_loadPageForOffset($offset);
        }
        return (empty($this->data['entries'][$offset])) ? null :
            $this->data['entries'][$offset];
    }

    protected function _loadPageForOffset($offset)
    {
        $this->_calculatePageSize();
        $start = round($offset / $this->pageSize) * $this->pageSize;
        $params = $this->_getPageParams($start, $this->pageSize);
        $data = $this->adapter->request('GET', $this->url, $params);
        $this->adapter->debug = false;
        $rekeyed = array();
        foreach ($data['entries'] as $key => $entry) {
            $rekeyed[$key + $data['start']] = $entry;
        }
        $this->data['entries'] = array_merge($this->data['entries'], $rekeyed);
    }

    protected function _calculatePageSize()
    {
        if (array_key_exists('next_collection_link', $this->data)) {
            $url = $this->data['next_collection_link'];
            $urlParts = parse_url($url);
            if (empty($urlParts['query'])) return $this->pageSize;
            $query = array();
            parse_str($urlParts['query'], $query);
            if (empty($query['ws_size'])) return $this->pageSize;
            $this->pageSize = $query['ws_size'];
        }
        return $this->pageSize;
    }

    protected function _getPageParams($start = 0, $size = 20)
    {
        if ($start > 0) {
            $params = array(
                'ws.start' => $start,
                'ws.size' => $size,
            );
            ksort($params);
        } else {
            $params = array();
        }
        return $params;
    }

    public function next()
    {
        $this->_iterationKey++;
    }

    public function rewind()
    {
        $this->_iterationKey = 0;
    }

    public function valid()
    {
        return $this->offsetExists($this->key());
    }

    public function key()
    {
        return $this->_iterationKey;
    }

    public function count()
    {
        return $this->total_size;
    }

    protected function _type()
    {
        $urlParts = explode('/', $this->url);
        $type = array_pop($urlParts);
        return $type;
    }

}