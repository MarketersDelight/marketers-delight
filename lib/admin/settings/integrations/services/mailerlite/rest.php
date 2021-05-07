<?php

require_once dirname(__FILE__) . '/rest-base.php';

class MD_MailerLite_Forms_Rest extends MD_MailerLite_Forms_Rest_Base
{
    var $endpoint = '';

    function __construct($api_key)
    {
        parent::__construct();
        $this->apiKey = $api_key;
        $this->path = $this->url . $this->endpoint . '/';
    }

    function getAll()
    {
        return $this->execute('GET');
    }

    function getAllJson($data = [])
    {
        return json_decode($this->execute('GET', $data));
    }

    function get($data = null)
    {
        if (!$this->id)
            throw new InvalidArgumentException('ID is not set.');
        return $this->execute('GET');
    }

    function add($data = null)
    {
        return $this->execute('POST', $data);
    }

    function put($data = null)
    {
        return $this->execute('PUT', $data);
    }

    function remove($data = null)
    {
        return $this->execute('DELETE');
    }
}