<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);
if(!$client->connect('127.0.0.1',9501)){
    echo '连接失败';
    exit;
}

fwrite(STDOUT,'请输入消息:');
$msg = trim(fgets(STDIN));

$client->send($msg);

$result = $client->recv();
echo $result;