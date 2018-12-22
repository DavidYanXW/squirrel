<?php
/**
 *
 *
 */
namespace Lib;


class ServerStub
{
    /**
     * 定义错误码
     * @var null
     */
    const E_SUCCESS = 1000; // 成功
    const E_SYS_SEC = 1001; // 系统级错误:授权错误
    const E_SYS_RESOLVE = 1002;    // 系统级错误:请求参数解析错误
    const E_APP_ = 2001;    // 应用级别错误

    //raw_data
    protected $_raw_data = null;

    // 认证key
    protected $_auth_key = null;

    protected $_app = null;

    protected $_ver = null;

    protected $_method = null;

    protected $_arg = null;


    protected $_tcp_server = null;

    // 回调结果
    protected $_invoke_return = null;

    //初始化
    // ['auth'=>$str, 'app'=>$app, 'ver'=>$ver, 'method'=>$method, 'arg'=>$arg]
    public function __construct()
    {

        $this->_tcp_server = new \Swoole\Server('0.0.0.0', 9601, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
        $this->_tcp_server->set([
            'worker_num' => 4,
            'daemonize' => false,
            'log_level' => SWOOLE_LOG_INFO,
        ]);

        $this->_tcp_server->on('start', [$this, '_onStart']);
        $this->_tcp_server->on('Connect', array($this, '_onConnect'));
        $this->_tcp_server->on('Receive', array($this, '_onReceive'));
        $this->_tcp_server->on('Close', array($this, '_onClose'));

        $this->_tcp_server->start();

    }

    public function _onStart(\Swoole\Server $serv ) {
        echo "Start\n";

    }

    public function _onConnect(\Swoole\Server $serv, $fd, $reactor_id ) {
        echo "Hello {$fd}-{$reactor_id}!";
    }

    public function _onReceive( \Swoole\Server $serv, $fd, $reactor_id, $data ) {
        $rand = rand(1,100);
//        usleep($rand);

        $this->_raw_data = $data;
        $ret = $this->bootstrap();

        // debug info
        echo "RECEIVE:[{$fd}-{$reactor_id}:{$data}]".PHP_EOL;
        echo "SEND:[".$ret."]".PHP_EOL;

        $serv->send($fd, $ret);
    }

    public function _onClose(\Swoole\Server $serv, $fd, $reactor_id ) {
        echo "Client {$fd}-{$reactor_id} close connection\n";
    }

    /**
     * 引导
     *
     * @return array
     */
    public function bootstrap()
    {

        try {
            $this->_resolve();
            $this->_auth();
            $this->_verify();
            $this->_exec();

            return $this->_reply();
        }
        catch (\Exception $exception) {
//            return $this->__pack(['code'=>$exception->getCode(), 'msg'=>$exception->getMessage(),'data'=>[]]);
        }

    }

    // @todo: 安全：鉴权
    protected function _auth() 
    {
        return true;
        throw new \Exception("auth failed", SELF::E_SYS_SEC);
    }


    /**
     * @todo: 参数校验：
     * @return bool
     */
    protected function _verify() 
    {
        return true;
    }

    /**
     * 解析参数
     *
     * @throws \Exception
     */
    protected function _resolve() 
    {
        $unpack_data = $this->__unpack($this->_raw_data);
        if($unpack_data === false) {
            throw new \Exception("unpack data failed", SELF::E_SYS_RESOLVE);
        }

        list ($this->_auth_key, $this->_app, $this->_ver, $this->_method, $this->_arg) = [
            isset($unpack_data['auth'])?$unpack_data['auth']:'',
            $unpack_data['service_name'],
            isset($unpack_data['ver'])?$unpack_data['ver']:'',
            $unpack_data['method'],
            $unpack_data['param'],
        ];
        echo $this->_app.PHP_EOL;
        $this->_app = "\Serverapp\\".$this->_app;
    }

    // 运行
    protected function _exec() 
    {
        $this->_invoke_return = [];

        if(!class_exists($this->_app)) {
            throw new \Exception("class[{$this->_app}] not exist", 1003);
        }
        $app = new $this->_app();

        $this->_invoke_return = call_user_func_array([$app, $this->_method], $this->_arg);

    }

    // 返回执行结果
    protected function _reply() 
    {
        $output = [
            'code' => 1000,
            'msg' => '',
            'data' => $this->_invoke_return
        ];
        return $this->__pack($output);
    }

    //打包
    private function __pack(array $param) 
    {
        return json_encode($param);
    }


    /**
     * 解包
     *
     * @param  $str
     * @return mixed
     */
    private function __unpack($str)
    {
        return json_decode($str,true);
    }



}
