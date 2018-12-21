<?php
namespace Lib;

class SwooleTcpServer {
    private $_handle;

    public function __construct() {

        $this->_handle = new swoole_server('0.0.0.0', 9601);
        $this->_handle->set([
            'worker_num' => 4,
            'daemonize' => false,
        ]);
        
        $this->_handle->on('start', [$this, 'onStart']);
        $this->_handle->on('Connect', array($this, 'onConnect'));
        $this->_handle->on('Receive', array($this, 'onReceive'));
        $this->_handle->on('Close', array($this, 'onClose'));

        $this->_handle->start();
    }
    
    public function onStart(swoole_server $serv ) {
        echo "Start\n";

    }

    public function onConnect(swoole_server $serv, $fd, $reactor_id ) {
        $serv->send( $fd, "Hello {$fd}-{$reactor_id}!" );
    }

    public function onReceive( swoole_server $serv, $fd, $reactor_id, $data ) {
        $rand = 100*rand(1,10);
//        usleep($rand);

        echo "Get Message From Client {$fd}-{$reactor_id}:{$data}\n";
        $data_server = "data from server:".$data;
        $serv->send($fd, $data_server);

        $add_num = rand(1,100);
    }

    public function onClose(swoole_server $serv, $fd, $reactor_id ) {
        echo "Client {$fd}-{$reactor_id} close connection\n";
    }
}

$server = new Server();
var_dump($server);


