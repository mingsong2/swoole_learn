<?php
swoole_async_readfile(__DIR__.'/1.txt',function($filename,$fileContent){
    echo "filename:".$filename.PHP_EOL;
    echo "filecontent:".$fileContent.PHP_EOL;
});