<?php
namespace Rpc\R_server_app;

class R_s_file
{

    public function read($file) 
    {
        return file_get_contents($file);
    }

    public function append($file, $content) 
    {
        return file_put_contents($file, $content, FILE_APPEND);
    }
}