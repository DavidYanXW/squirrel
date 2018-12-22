<?php
/**
 * Created by PhpStorm.
 * User: yanxiaowei
 * Date: 2018/1/9
 * Time: 19:06
 */
require 'vendor/autoload.php';

$client = new \Service\StrechoService();

// $http \swoole_http_server
$http = new swoole_http_server("0.0.0.0", 9511);
$http->set([
//    'worker_num' => 4,
    'daemonize' => false,
    'log_level' => SWOOLE_LOG_INFO,
]);

// on event: start|request
// exclude event: connect|receive
$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9511\n";
});

$http->on("request", function (\swoole_http_request $request, \swoole_http_response $response) {
    // $request
    if($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
        return ;
    }


    // param
    $param = "hello world";

//     StrechoService
//    $client = new \Service\StrechoService();
//    $ret = $client->strecho($param);

//    StrprocService
    $client2 = new \Service\StrprocService();
    $ret = $client2->reverse($param);



    //
    $output = json_encode($ret);



    // single
    $single_var = single::getInstance()->getVar();

    $response->write("data:".$output.",single_var:".$single_var.PHP_EOL);

});

// $http start
$http->start();


class single
{

    static protected $_instance = null;

    protected $_var;

    protected function __construct($var)
    {
        $this->_var = $var;
    }

    static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            $random = rand(1,100);
            self::$_instance = new self($random);
        }

        return self::$_instance;
    }

    public function getVar() {
        return $this->_var;
    }
}

