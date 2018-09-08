<?php
$serv =  new swoole_server('127.0.0.1',9501);
$serv->set([
    'work_num' => 8,
    'max_request' => 10000,
]);

/**
 * $fd 客户端连接的唯一标识
 * $reactor_id 线程id
 */
$serv->on('connect',function($serv,$fd,$reactor_id){
    echo "client: {$reactor_id}-{$fd} Connect. \n";
});

$serv->on('receive',function($serv,$fd,$from_id,$data){
    $serv->send($fd,"server:".$data);
});

$serv->start();