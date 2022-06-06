<?php
/**
 * @author Svetoslav Marinov (SLAVI)
 * @modified Alex Mangini
 */

Class MD_Drip {
	private $version = "2";
    private $api_token = '';
    private $error_code = '';
    private $error_message = '';
    private $user_agent = "Drip API PHP Wrapper (getdrip.com)";
    private $api_end_point = 'https://api.getdrip.com/v2/';
    private $recent_req_info = array();
    private $timeout = 30;
    private $connect_timeout = 30;
    private $debug = false;

    const GET  = 1;
    const POST = 2;
    const DELETE = 3;
    const PUT = 4;

    public function __construct($api_token) {
        $api_token = trim($api_token);

        if (empty($api_token) || !preg_match('#^[\w-]+$#si', $api_token)) {
            throw new Exception("Missing or invalid Drip API token.");
        }

        $this->api_token = $api_token;
    }

    public function get_forms($params) {
        if (empty($params['account_id'])) {
            throw new Exception("Account ID not specified");
        }

        $account_id = $params['account_id'];
        unset($params['account_id']);

        if (isset($params['status'])) {
            if (!in_array($params['status'], array('active', 'draft', 'paused', 'all'))) {
                throw new Exception("Invalid form status.");
            }
        } elseif (0) {
            $params['status'] = 'active';
        }

        $url = $this->api_end_point . "$account_id/forms";
        $res = $this->make_request($url, $params);

        if (!empty($res['buffer'])) {
            $raw_json = json_decode($res['buffer'], true);
        }

        $forms = empty($raw_json)
                ? false
                : empty($raw_json['forms'])
                    ? array()
                    : $raw_json['forms'];

        return $forms;
    }

    public function make_request($url, $params = array(), $req_method = self::GET) {
        if (!function_exists('curl_init')) {
            throw new Exception("Cannot find cURL php extension or it's not loaded.");
        }

        $ch = curl_init();

        if ($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }

        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_token . ":" . ''); // no pwd
        curl_setopt($ch, CURLOPT_USERAGENT, empty($params['user_agent']) ? $this->user_agent : $params['user_agent']);

        if ($req_method == self::POST) { // We want post but no params to supply. Probably we have a nice link structure which includes all the info.
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        } elseif ($req_method == self::DELETE) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        } elseif ($req_method == self::PUT) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }

        if (!empty($params)) {
            if ((isset($params['__req']) && strtolower($params['__req']) == 'get')
                    || $req_method == self::GET) {
                unset($params['__req']);
                $url .= '?' . http_build_query($params);
            } elseif ($req_method == self::POST || $req_method == self::DELETE) {
                $params_str = is_array($params) ? json_encode($params) : $params;
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params_str);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Content-Type: application/vnd.api+json',
        ));

        $buffer = curl_exec($ch);
        $status = !empty($buffer);

        $data = array(
            'url'       => $url,
            'params'    => $params,
            'status'    => $status,
            'error'     => empty($buffer) ? curl_error($ch) : '',
            'error_no'  => empty($buffer) ? curl_errno($ch) : '',
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'debug'     => $this->debug ? curl_getinfo($ch) : '',
        );

        curl_close($ch);

        $buffer = preg_replace('#HTTP/[\d.]+\s+\d+\s+\w+[\r\n]+#si', '', $buffer);
        $buffer = trim($buffer);
        $data['buffer'] = $buffer;

        $this->_parse_error($data);
        $this->recent_req_info = $data;

        return $data;
    }

    public function _parse_error($res) {
        if (empty($res['http_code']) || $res['http_code'] >= 200 && $res['http_code'] <= 299) {
            return true;
        }

        if (empty($res['buffer'])) {
            $this->error_message = "Response from the server.";
            $this->error_code = $res['http_code'];
        } elseif (!empty($res['buffer'])) {
            $json_arr = json_decode($res['buffer'], true);
            if (!empty($json_arr['errors'])) {
                $messages = $error_codes = array();

                foreach ($json_arr['errors'] as $rec) {
                    $messages[] = $rec['message'];
                    $error_codes[] = $rec['code'];
                }

                $this->error_code = join(", ", $error_codes);
                $this->error_message = join("\n", $messages);
            } else {
                $msg = $res['buffer'];

                $msg = preg_replace('#.*?<body[^>]*>#si', '', $msg);
                $msg = preg_replace('#</body[^>]*>.*#si', '', $msg);
                $msg = strip_tags($msg);
                $msg = preg_replace('#[\r\n]#si', '', $msg);
                $msg = preg_replace('#\s+#si', ' ', $msg);
                $msg = trim($msg);
                $msg = substr($msg, 0, 256);

                $this->error_code = $res['http_code'];
                $this->error_message = $msg;
            }
        } elseif ($res['http_code'] >= 400 || $res['http_code'] <= 499) {
            $this->error_message = "Not authorized.";
            $this->error_code = $res['http_code'];
        } elseif ($res['http_code'] >= 500 || $res['http_code'] <= 599) {
            $this->error_message = "Internal Server Error.";
            $this->error_code = $res['http_code'];
        }
    }

    public function __call($method, $args) {
        return array();
    }

}