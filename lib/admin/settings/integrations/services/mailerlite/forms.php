<?php

require_once dirname(__FILE__) . '/rest.php';

class MD_MailerLite_Forms extends MD_MailerLite_Forms_Rest
{
    function __construct($api_key)
    {
        $this->endpoint = 'webforms';
        parent::__construct($api_key);
    }

    public function getAllJson($data = [])
    {
        return parent::getAllJson($data);
    }
}