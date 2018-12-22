<?php
namespace Service;

use Lib\ClientStub;

/**
 * @todo autogenerate
 * Class StrechoService
 * @package Service
 */
class StrechoService extends ClientStub {

    public function strecho($str) {
        $this->assemble(__CLASS__, __FUNCTION__, func_get_args());
        $this->bootstrap();


        // call client stub
        return $this->response();
    }

}