<?php
namespace Rpc\R_server_app;

class R_s_string
{

    /**
     * 反转
     *
     * @param  $str
     * @return string
     */
    public function reverse($str) 
    {
        return strrev($str);
    }

    /**
     * 大写
     *
     * @param  $str
     * @return string
     */
    public function toupper($str) 
    {
        return strtoupper($str);
    }

    /**
     * 小写
     *
     * @param  $str
     * @return string
     */
    public function tolower($str) 
    {
        return strtolower($str);
    }

}