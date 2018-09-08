<?php
$http = new swoole_http_server("0.0.0.0", 8811);
// 设置静态资源访问
$http->set([
    'enable_static_handler' => true,
    'document_root' => '/Volumes/work_1/pinnoocle/learn/swoole/data'
]);
$http->on('request', function ($request, $response) {
    print_r($request->get);
    $response->cookie("frank","cookie",time()+10000);
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});
$http->start();