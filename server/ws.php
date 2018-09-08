<?php
class Ws{
    CONST HOST = '0.0.0.0';
    CONST PORT = '8812';
    public $ws = null;
    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST,self::PORT);
        $this->ws->set([
            'worker_num' => 4,    //worker process num
            'task_worker_num' =>100
        ]);
        // 回调函数的对象写法
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
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
        if($request->fd == 1){
            // 每两秒执行
            swoole_timer_tick(2000,function($timer_id){
                echo "2s: timerId:{$timer_id}\n"; 
            });
        }
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
        
        // 投递一个异步任务
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        // $ws->task($data);
        swoole_timer_after(5000,function() use($ws,$frame){
            echo "使用swoole定时器5s后打印";
            $ws->push($frame->fd,"swoole定时器5s后推送的内容");
        });

        // 下面的代码不会等待上面执行完毕再执行
        $ws->push($frame->fd,"服务端推送的数据".date('Y-m-d H:i:s'));
    }
    /**
     * 监听$ws->task接受一个异步任务
     * @param [type] $serv
     * @param [type] $taskId
     * @param [type] $workerId
     * @param [type] $data
     * @return void
     */
    public function onTask($serv,$taskId,$workerId,$data){
        print_r($data);
        sleep(10);
        return "任务结束"; // 将结果返回给worker进程  会触发onFinish函数
    }
    /**
     * 当worker进程投递的任务在task_worker中完成时，task进程会通过swoole_server->finish()方法将任务处理的结果发送给worker进程。
     * @param [type] $serv
     * @param [type] $taskId
     * @param [type] $data
     * @return void
     */
    public function onFinish($serv,$taskId,$data){
        echo "taskId:{$taskId}\n";
        echo "任务结束后的返回参数:{$data}";
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