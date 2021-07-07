<?php
require_once('exceptions.php');
require_once('oauth_adapter.php');
require_once('oauth_application.php');
require_once('aweber_response.php');
require_once('aweber_collection.php');
require_once('aweber_entry_data_array.php');
require_once('aweber_entry.php');

class AWeberServiceProvider implements OAuthServiceProvider {

    public $baseUri = 'https://api.aweber.com/1.0';
    public $accessTokenUrl = 'https://auth.aweber.com/1.0/oauth/access_token';
    public $authorizeUrl = 'https://auth.aweber.com/1.0/oauth/authorize';
    public $requestTokenUrl = 'https://auth.aweber.com/1.0/oauth/request_token';

    public function getBaseUri() {
        return $this->baseUri;
    }

    public function removeBaseUri($url) {
        return str_replace($this->getBaseUri(), '', $url);
    }

    public function getAccessTokenUrl() {
        return $this->accessTokenUrl;
    }

    public function getAuthorizeUrl() {
        return $this->authorizeUrl;
    }

    public function getRequestTokenUrl() {
        return $this->requestTokenUrl;
    }

    public function getAuthTokenFromUrl() { return ''; }
    public function getUserData() { return ''; }

}

class AWeberAPIBase {

    static protected $_collectionMap = array(
        'account'              => array('lists', 'integrations'),
        'broadcast_campaign'   => array('links', 'messages'),
        'followup_campaign'    => array('links', 'messages'),
        'link'                 => array('clicks'),
        'list'                 => array('campaigns', 'custom_fields', 'subscribers',
                                        'web_forms', 'web_form_split_tests'),
        'web_form'             => array(),
        'web_form_split_test'  => array('components'),
    );

    public function loadFromUrl($url) {
        $data = $this->adapter->request('GET', $url);
        return $this->readResponse($data, $url);
    }

    protected function _cleanUrl($url) {
        return str_replace($this->adapter->app->getBaseUri(), '', $url);
    }

    protected function readResponse($response, $url) {
        $this->adapter->parseAsError($response);
        if (!empty($response['id'])) {
            return new AWeberEntry($response, $url, $this->adapter);
        } else if (array_key_exists('entries', $response)) {
            return new AWeberCollection($response, $url, $this->adapter);
        }
        return false;
    }
}

class AWeberAPI extends AWeberAPIBase {

    public $consumerKey    = false;
    public $consumerSecret = false;
    public $adapter = false;

    public static function getDataFromAweberID($string) {
        list($consumerKey, $consumerSecret, $requestToken, $tokenSecret, $verifier) = AWeberAPI::_parseAweberID($string);
        if (!$verifier) {
            return null;
        }
        $aweber = new AweberAPI($consumerKey, $consumerSecret);
        $aweber->adapter->user->requestToken = $requestToken;
        $aweber->adapter->user->tokenSecret = $tokenSecret;
        $aweber->adapter->user->verifier = $verifier;
        list($accessToken, $accessSecret) = $aweber->getAccessToken();
        return array($consumerKey, $consumerSecret, $accessToken, $accessSecret);
    }

    protected static function _parseAWeberID($string) {
        $values = explode('|', $string);
        if (count($values) < 5) {
            return null;
        }
        return array_slice($values, 0, 5);
    }

    public function __construct($key, $secret) {
        $this->consumerKey    = $key;
        $this->consumerSecret = $secret;
        $this->setAdapter();
    }

    public function getAuthorizeUrl() {
        $requestToken = $this->user->requestToken;
        return (empty($requestToken)) ?
            $this->adapter->app->getAuthorizeUrl()
                :
            $this->adapter->app->getAuthorizeUrl() . "?oauth_token={$this->user->requestToken}";
    }

    public function setAdapter($adapter=null) {
        if (empty($adapter)) {
            $serviceProvider = new AWeberServiceProvider();
            $adapter = new OAuthApplication($serviceProvider);
            $adapter->consumerKey = $this->consumerKey;
            $adapter->consumerSecret = $this->consumerSecret;
        }
        $this->adapter = $adapter;
    }

    public function getAccount($token=false, $secret=false) {
        if ($token && $secret) {
            $user = new OAuthUser();
            $user->accessToken = $token;
            $user->tokenSecret = $secret;
            $this->adapter->user = $user;
        }

        $body = $this->adapter->request('GET', '/accounts');
        $accounts = $this->readResponse($body, '/accounts');
        return $accounts[0];
    }

    public function __get($item) {
        if ($item == 'user') return $this->adapter->user;
        trigger_error("Could not find \"{$item}\"");
    }

    public function getRequestToken($callbackUrl) {
        $requestToken = $this->adapter->getRequestToken($callbackUrl);
        return array($requestToken, $this->user->tokenSecret);
    }

    public function getAccessToken() {
        return $this->adapter->getAccessToken();
    }
}