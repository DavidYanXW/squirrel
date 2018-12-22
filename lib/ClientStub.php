<?php
namespace Lib;

class ClientStub
{

    /**
     * todo:$_num_procedure, $_version_procedure
     * @var null
     */
    protected $_num_procedure = null;
    protected $_version_procedure = 0;

    protected $_client = null;  //
    protected $_arg = null; //
    protected $_packArg = null;
    protected $_ret = null;
    protected $_unpackRet = null;

    //初始化
    public function __construct()
    {
        // 注入
        $this->_client = new \swoole_client(SWOOLE_SOCK_TCP);
//        $this->_client->on("connect", array($this, '_onConnect'));
//        $this->_client->on("receive", array($this, '_onReceive'));
//        $this->_client->on("close", function($cli){
//            echo "closed\n";
//        });
//        $this->_client->on("error", function($cli){
//            exit("error\n");
//        });

        // 发起连接
        $this->_client->connect('127.0.0.1', 9601, 0.5);

//        $this->bootstrap();
    }

    /**
     * _onConnect
     * @param $cli
     */
    private function _onConnect($cli) {
        $this->request();
    }

    /**
     * _onReceive
     * @param $cli
     * @param $data
     */
    private function _onReceive($cli, $data) {
        $this->_ret = $data;
        $this->_unpack();
        $this->_verify();
        $this->_replyResponse();
    }

    /**
     * setter
     * @param $arg
     */
    public function setArg($arg) {
        $this->_arg = $arg;
    }

    /**
     * 引导
     * @todo : $arg
     * @return mixed
     */
    public function bootstrap()
    {

        try {
            $this->request();
        }
        catch (\Exception $exception) {
            return $this->__pack(['code'=>$exception->getCode(), 'msg'=>$exception->getMessage(),'data'=>[]]);
        }
    }

    /**
     * _resolve
     * @return bool
     */
    private function _resolve() {
        return true;
    }

    /**
     * todo: _verify
     * @return bool
     */
    private function _verify() {
        return true;
    }

    /**
     * 发送信息->rpc server
     */
    private function _callRequest() {
        echo "SEND:[{$this->_packArg}]".PHP_EOL;
        $this->_client->send($this->_packArg);
    }

    /**
     * @todo:
     */
    private function _replyResponse(){

    }


    //请求
    protected function request() 
    {
        echo "RECEIVE_REQUEST:[".json_encode($this->_arg)."]".PHP_EOL;
        $this->_resolve();
        $this->_verify();
        $this->_pack();
        $this->_callRequest();
    }

    // 取出结果
    public function response()
    {
        $this->_ret = $this->_client->recv();
        echo "RECEIVE_REPLY:[{$this->_ret}]".PHP_EOL;
        $this->_unpack();
        $this->_verify();
        return $this->_unpackRet;
    }


    /**
     * 组装
     * @param $service_name
     * @param $method
     * @param $param
     */
    public function assemble($service_name, $method, $param) {
        preg_match("/Service\\\(.*)Service/", $service_name, $single_service);
        // @todo: handle error


        $arr = [
            'service_name' => $single_service[1],
            'method' => $method,
            'param' => $param,
        ];
        $this->setArg($arr);
    }

    private function _unassemble() {

    }


    //打包
    private function _pack()
    {
        $this->_packArg = json_encode($this->_arg);
    }

    //解包
    private function _unpack()
    {
        $this->_unpackRet = json_decode($this->_ret);
    }




}