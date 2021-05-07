<?php

class AWeberException extends Exception
{
}

class AWeberAPIException extends AWeberException
{
    public $type;
    public $status;
    public $message;
    public $documentation_url;
    public $url;

    public function __construct($error, $url)
    {
        $this->url = $url;
        $this->type = $error['type'];
        $this->status = array_key_exists('status', $error) ? $error['status'] : '';
        $this->message = $error['message'];
        $this->documentation_url = $error['documentation_url'];

        parent::__construct($this->message);
    }
}

class AWeberResourceNotImplemented extends AWeberException
{
    public function __construct($object, $value)
    {
        $this->object = $object;
        $this->value = $value;
        parent::__construct("Resource \"{$value}\" is not implemented on this resource.");
    }
}

class AWeberMethodNotImplemented extends AWeberException
{
    public function __construct($object)
    {
        $this->object = $object;
        parent::__construct("This method is not implemented by the current resource.");

    }
}

class AWeberOAuthException extends AWeberException
{
    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
        parent::__construct("{$type}: {$message}");
    }
}

class AWeberOAuthDataMissing extends AWeberException
{
    public function __construct($missing)
    {
        if (!is_array($missing)) $missing = array($missing);
        $this->missing = $missing;
        $required = join(', ', $this->missing);
        parent::__construct("OAuthDataMissing: Response was expected to contain: {$required}");

    }
}

class AWeberResponseError extends AWeberException
{
    public function __construct($uri)
    {
        $this->uri = $uri;
        parent::__construct("Request for {$uri} did not respond properly.");
    }
}