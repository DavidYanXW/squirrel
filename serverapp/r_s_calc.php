<?php
namespace Rpc\R_server_app;

class R_s_calc
{
    public function add($a, $b) 
    {
        return $a+$b;
    }

    public function minus($a, $b) 
    {
        return $a-$b;
    }

    public function multiply($a, $b) 
    {
        return $a*$b;
    }

    public function divide($a, $b) 
    {
        if($b==0) {
            return false;
        }
        return $a/$b;
    }

}