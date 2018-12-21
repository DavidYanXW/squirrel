<?php
namespace Service;

use Lib\ClientStub;

class StrechoClient extends ClientStub {

    public function strecho($str) {


        // call client stub
        return $this->response();
    }

}