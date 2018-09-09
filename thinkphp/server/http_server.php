<?php
$http = new swoole_http_server("0.0.0.0", 8811);
// 设置静态资源访问
$http->set([
    'enable_static_handler' => true,
    'document_root' => '/Volumes/work_1/pinnoocle/learn/swoole/thinkphp/public/static',
    'worker_num' => 5
]);
// 此事件在Worker进程/Task进程启动时发生(这里去引入thinkphp的一些文件，用于后续调用thinkphp的方法)
$http->on('WorkerStart',function(swoole_server $server,$worker_id){
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    // 加载框架里面的文件
    require __DIR__ . '/../thinkphp/base.php';
    // require __DIR__ . '/../thinkphp/start.php';
});

$http->on('request', function ($request, $response) use($http){
    // swoole中的header  cookie server get post和原生php不一样，这里做一个转换
    if(isset($request->server)){
        foreach($request->server as $k => $v){
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    if(isset($request->header)){
        foreach($request->header as $k => $v){
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    // if(!empty($_GET)){
    //     unset($_GET);
    // }
    if(isset($request->get)){
        foreach($request->get as $k => $v){
            $_GET = $v;
        }
    }
    if(isset($request->post)){
        foreach($request->post as $k => $v){
            $_POST = $v;
        }
    }
    ob_start();
    // 执行应用并相应
    try{
        think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
        ->run()
        ->send();
    }catch(\Exception $e){
        
    }

    $res = ob_get_contents();
    ob_end_clean();

    $response->end($res);

    $http->close();
});
$http->start();