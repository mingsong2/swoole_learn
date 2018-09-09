<?php
echo 'startTime'.date('Y-m-d H:i:s').PHP_EOL;
$workers = [];
$urls = [
    'http://baidu.com',
    'http://sina.com.cn',
    'http://qq.com',
    'http://baidu.com?search=frank',
    'http://baidu.com?search=frank',
    'http://baidu.com?search=frank',
];

// 循环开启6个子进程
for($i=0;$i<6;$i++){
    $process = new swoole_process(function(swoole_process $worker) use($i,$urls){
        // 子进程中去curl,这里仅模拟一下
        $content = curlData($urls[$i]);
        echo $content.PHP_EOL;
    },true);
    $pid = $process->start();
    $workers[$pid] = $process;
}

// 从进程管道中读取返回值
foreach($workers as $worker){
    echo $worker->read();
}
echo 'end'.date('Y-m-d H:i:s').PHP_EOL;

function curlData($url){
    sleep(1);
    return $url.":success";
}