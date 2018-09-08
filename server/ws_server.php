<?php
// 开启一个websocket服务，websocket服务继承于http_server
$server = new swoole_websocket_server("0.0.0.0", 8812);
$server->set([
    'enable_static_handler' => true,
    'document_root' => '/Volumes/work_1/pinnoocle/learn/swoole/data'
]);
// 客户端连接成功
$server->on('open','onOpen');
function onOpen($server, $request){
    print_r($request->fd);
}
// 接受客户端数据
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    // 向客户端推送数据
    $server->push($frame->fd, "swoole服务器推送过来的数据");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();