<?php
/**
 * Created by PhpStorm.
 * User: yanxiaowei
 * Date: 2018/1/9
 * Time: 19:06
 */

// $http \swoole_http_server
$http = new swoole_http_server("0.0.0.0", 9511);

// on event: start|request
// exclude event: connect|receive
$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9511\n";
});

$http->on("request", function (\swoole_http_request $request, \swoole_http_response $response) {
    // $request
//    echo json_encode($request->header);
//    echo json_encode($request->server);
//    echo json_encode($request->get);
//    echo json_encode($request->post);
//    echo json_encode($request->cookie);
//    echo json_encode($request->files);
//    echo json_encode($request->rawContent());

    // $response
//    $response->header("Content-Type", "application/json");
    // status
//    $response->status(503);
    // data
//    $response->write("line1: get data from david".PHP_EOL);

    // param
//    $param = ["hello world"];
    $param = "hello world";

    $client = new \Service\StrechoClient();
    $ret = $client->strecho($param);


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

