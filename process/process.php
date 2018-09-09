<?php
// 开启一个进程
$process = new swoole_process(function(swoole_process $worker){
    // 执行一个外部程序,这里去开启http服务
    $worker->exec('/usr/local/bin/php',[__DIR__.'/../server/http_server.php']);
},false);

$pid = $process->start();
echo $pid.PHP_EOL;

//回收结束运行的子进程
swoole_process::wait();