<?php
$redisClient = new swoole_redis();

$redisClient->connect('127.0.0.1',6379,function(swoole_redis $redisClient,$result){
    echo '连接redis'.$result.PHP_EOL;
    // redis  set
    $redisClient->set('name','frank',function(swoole_redis $redisClient,$result){
        var_dump($result);
    });

    $redisClient->get('name',function(swoole_redis $redisClient,$result){
        var_dump($result);
    });

    $redisClient->keys('*na*',function(swoole_redis $redisClient,$result){
        var_dump($result);
    });
});
