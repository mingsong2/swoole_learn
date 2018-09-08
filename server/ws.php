<?php
class Ws{
    CONST HOST = '0.0.0.0';
    CONST PORT = '8812';
    public $ws = null;
    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST,self::PORT);
        // 回调函数的对象写法
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->start();
    }
    /**
     * 监听ws连接事件(客户端连接成功)
     *
     * @param [type] $ws
     * @param [type] $request
     * @return void
     */
    public function onOpen($ws,$request){
        print_r("客户".$request->fd."连接swoole服务"); // 客户端的唯一标识 $request->fd
        echo "\n";
    }

    /**
     * 监听wx消息事件(客户端传来消息)
     *
     * @param [type] $ws
     * @param [type] $frame
     * @return void
     */
    public function onMessage($ws,$frame){
        echo "接受到的客户端的数据:{$frame->data}\n";
        $ws->push($frame->fd,"服务端推送的数据".date('Y-m-d H:i:s'));
    }

    /**
     * 监听wx关闭
     *
     * @param [type] $ws
     * @param [type] $fd
     * @return void
     */
    public function onClose($ws,$fd){
        echo "{$fd}:客户关闭";
    }
}
$obj = new Ws();