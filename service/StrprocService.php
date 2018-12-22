<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 18-12-23
 * Time: 上午1:56
 */
namespace Service;

use Lib\ClientStub;


class StrprocService extends ClientStub {

    public function reverse($str) {
        $this->assemble(__CLASS__, __FUNCTION__, func_get_args());
        $this->bootstrap();


        // call client stub
        return $this->response();
    }

    public function toupper($str) {
        $this->assemble(__CLASS__, __FUNCTION__, func_get_args());
        $this->bootstrap();


        // call client stub
        return $this->response();
    }


    public function tolower($str) {
        $this->assemble(__CLASS__, __FUNCTION__, func_get_args());
        $this->bootstrap();


        // call client stub
        return $this->response();
    }

}