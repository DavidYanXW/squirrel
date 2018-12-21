<?php
require 'vendor/autoload.php';

$a = [
    'auth'=>1,
    'app'=> '\Serverapp\R_s_file',
    'ver'=>1.0,
    'method'=>'strecho',
    'arg' => ['fielname'=>'/tmp/debug.log']
];

$stub = new \Lib\ServerStub(serialize($a));
$bootstrap = $stub->bootstrap();
echo $bootstrap;
