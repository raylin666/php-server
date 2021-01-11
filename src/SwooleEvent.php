<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2020 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Server;

use Raylin666\Server\Bootstrap\Callback\CloseCallback;
use Raylin666\Server\Bootstrap\Callback\ConnectCallback;
use Raylin666\Server\Bootstrap\Callback\FinishCallback;
use Raylin666\Server\Bootstrap\Callback\HandShakeCallback;
use Raylin666\Server\Bootstrap\Callback\ManagerStartCallback;
use Raylin666\Server\Bootstrap\Callback\MessageCallback;
use Raylin666\Server\Bootstrap\Callback\PacketCallback;
use Raylin666\Server\Bootstrap\Callback\PipeMessageCallback;
use Raylin666\Server\Bootstrap\Callback\ReceiveCallback;
use Raylin666\Server\Bootstrap\Callback\RequestCallback;
use Raylin666\Server\Bootstrap\Callback\ShutdownCallback;
use Raylin666\Server\Bootstrap\Callback\StartCallback;
use Raylin666\Server\Bootstrap\Callback\TaskCallback;
use Raylin666\Server\Bootstrap\Callback\WorkerErrorCallback;
use Raylin666\Server\Bootstrap\Callback\WorkerExitCallback;
use Raylin666\Server\Bootstrap\Callback\WorkerStartCallback;
use Raylin666\Server\Bootstrap\Callback\WorkerStopCallback;

/**
 * Class SwooleEvent
 * @package Raylin666\Server
 */
class SwooleEvent
{
    /**
     * 启动后在主进程（master）的主线程回调此函数
     *      onStart回调中，仅允许echo、打印Log、修改进程名称。
     *      不得执行其他操作。onWorkerStart和onStart回调是在不同进程中并行执行的，不存在先后顺序。
     *
     * @param \swoole_server $server
     * 
     * Swoole onStart event.
     */
    const ON_START = 'start';

    /**
     * 此事件在Worker进程/Task进程启动时发生。这里创建的对象可以在进程生命周期内使用
     *      onWorkerStart/onStart是并发执行的，没有先后顺序
     *      可以通过$server->taskworker属性来判断当前是Worker进程还是Task进程
     *      设置了worker_num和task_worker_num超过1时，每个进程都会触发一次onWorkerStart事件，可通过判断$worker_id区分不同的工作进程
     *      由 worker 进程向 task 进程发送任务，task 进程处理完全部任务之后通过onFinish回调函数通知 worker 进程。例如，我们在后台操作向十万个用户群发通知邮件，操作完成后操作的状态显示为发送中，这时我们可以继续其他操作。等邮件群发完毕后，操作的状态自动改为已发送。
     *
     * @param \swoole_server $server
     * @param int           $worker_id
     * 
     * Swoole onWorkerStart event.
     */
    const ON_WORKER_START = 'workerStart';

    /**
     * 此事件在Worker进程终止时发生。在此函数中可以回收Worker进程申请的各类资源
     *      $worker_id是一个从0-$worker_num之间的数字，表示这个Worker进程的ID
     *      $worker_id和进程PID没有任何关系
     *      进程异常结束，如被强制kill、致命错误、core dump时无法执行onWorkerStop回调函数
     *
     * @param \swoole_server $server
     * @param int           $worker_id
     * 
     * Swoole onWorkerStop event.
     */
    const ON_WORKER_STOP = 'workerStop';

    /**
     * 仅在开启reload_async特性后有效。异步重启特性，会先创建新的Worker进程处理新请求，旧的Worker进程自行退出
     *      Worker进程未退出，onWorkerExit会持续触发
     *      onWorkerExit仅在Worker进程内触发，Task进程不执行onWorkerExit
     *
     * @param \swoole_server $server
     * @param int           $worker_id
     * 
     * Swoole onWorkerExit event.
     */
    const ON_WORKER_EXIT = 'workerExit';

    /**
     * 当Worker/Task进程发生异常后会在Manager进程内回调此函数
     *      此函数主要用于报警和监控，一旦发现Worker进程异常退出，那么很有可能是遇到了致命错误或者进程CoreDump。通过记录日志或者发送报警的信息来提示开发者进行相应的处理。
     *
     * @param \swoole_server $server
     * @param int           $worker_id      异常进程的编号
     * @param int           $worker_pid     异常进程的ID
     * @param int           $exit_code      退出的状态码，范围是 0～255
     * @param int           $signal         进程退出的信号
     * 
     * Swoole onWorkerError event.
     */
    const ON_WORKER_ERROR = 'workerError';

    /**
     * 当工作进程收到由 sendMessage 发送的管道消息时会触发onPipeMessage事件。worker/task进程都可能会触发onPipeMessage事件
     *
     * @param \swoole_server $server
     * @param int           $src_worker_id  消息来自哪个Worker进程
     * @param mixed         $message        消息内容，可以是任意PHP类型
     * 
     * Swoole onPipeMessage event.
     */
    const ON_PIPE_MESSAGE = 'pipeMessage';

    /**
     * Http请求对象，保存了Http客户端请求的相关信息，包括GET、POST、COOKIE、Header等。
     *      Request对象销毁时会自动删除上传的临时文件
     *      请勿使用&符号引用$request对象
     *
     * @param \swoole_http_request  $request
     * @param \swoole_http_response $response
     * 
     * Swoole onRequest event.
     */
    const ON_REQUEST = 'request';

    /**
     * 接收到数据时回调此函数，发生在worker进程中
     *
     * @param \swoole_server $server
     * @param int           $fd             TCP客户端连接的唯一标识符
     * @param int           $reactor_id     TCP连接所在的Reactor线程ID
     * @param string        $data           收到的数据内容，可能是文本或者二进制内容
     * 
     * Swoole onReceive event.
     */
    const ON_RECEIVE = 'receive';

    /**
     * 有新的连接进入时，在worker进程中回调
     *
     * @param \swoole_server $server
     * @param int           $fd             连接的文件描述符
     * @param int           $reactorId      来自哪个Reactor线程
     * 
     * Swoole onConnect event.
     */
    const ON_CONNECT = 'connect';

    /**
     * WebSocket建立连接后进行握手。WebSocket服务器已经内置了handshake，如果用户希望自己进行握手处理，可以设置onHandShake事件回调函数
     *      onHandShake事件回调是可选的
     *      设置onHandShake回调函数后不会再触发onOpen事件，需要应用代码自行处理
     *      onHandShake中必须调用response->status设置状态码为101并调用end响应, 否则会握手失败.
     *      内置的握手协议为Sec-WebSocket-Version: 13，低版本浏览器需要自行实现握手
     *
     * @param \swoole_http_request  $request
     * @param \swoole_http_response $response
     * 
     * Swoole onHandShake event.
     */
    const ON_HAND_SHAKE = 'handshake';

    /**
     * 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数
     *      $req 是一个Http请求对象，包含了客户端发来的握手请求信息
     *      onOpen事件函数中可以调用push向客户端发送数据或者调用close关闭连接
     *      onOpen事件回调是可选的
     *
     * @param \swoole_websocket_server $svr
     * @param \swoole_http_request     $req
     * 
     * Swoole onOpen event.
     */
    const ON_OPEN = 'open';

    /**
     * 当服务器收到来自客户端的数据帧时会回调此函数
     *      onMessage回调必须被设置，未设置服务器将无法启动
     *      客户端发送的ping帧不会触发onMessage，底层会自动回复pong包
     *
     * @param \swoole_websocket_server $server
     * @param \swoole_websocket_frame  $frame        swoole_websocket_frame对象，包含了客户端发来的数据帧信息
     *          swoole_websocket_frame  共有4个属性，分别是
     *                  1.$frame->fd，客户端的socket id，使用$server->push推送数据时需要用到
     *                  2.$frame->data，数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
     *                  3.$frame->opcode，WebSocket的OpCode类型，可以参考WebSocket协议标准文档
     *                  4.$frame->finish， 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送（底层已经实现了自动合并数据帧，现在不用担心接收到的数据帧不完整）
     * 
     * Swoole onMessage event.
     */
    const ON_MESSAGE = 'message';

    /**
     * TCP客户端连接关闭后，在worker进程中回调此函数
     *
     * @param \swoole_server $server
     * @param int           $fd             连接的文件描述符
     * @param int           $reactorId      来自那个reactor线程，主动close关闭时为负数
     * 
     * Swoole onClose event.
     */
    const ON_CLOSE = 'close';

    /**
     * 在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。当前的Task进程在调用onTask回调函数时会将进程状态切换为忙碌，这时将不再接收新的Task，当onTask函数返回时会将进程状态切换为空闲然后继续接收新的Task。
     *
     * @param \swoole_server $server
     * @param int           $task_id        任务ID，由swoole扩展内自动生成，用于区分不同的任务。$task_id和$src_worker_id组合起来才是全局唯一的，不同的worker进程投递的任务ID可能会有相同
     * @param int           $src_worker_id  来自于哪个worker进程
     * @param mixed         $data           任务的内容
     * 
     * Swoole onTask event.
     */
    const ON_TASK = 'task';

    /**
     * 当worker进程投递的任务在task_worker中完成时，task进程会通过swoole_server->finish()方法将任务处理的结果发送给worker进程
     *
     * @param \swoole_server $server
     * @param int           $task_id        任务ID
     * @param string        $data           任务处理的结果内容
     * 
     * Swoole onFinish event.
     */
    const ON_FINISH = 'finish';

    /**
     * 此事件在Server正常结束时发生
     *
     * @param \swoole_server $server
     * 
     * Swoole onShutdown event.
     */
    const ON_SHUTDOWN = 'shutdown';

    /**
     * 接收到UDP数据包时回调此函数，发生在worker进程中
     *
     * @param \swoole_server $server
     * @param string        $data           收到的数据内容，可能是文本或者二进制内容
     * @param array         $client_info    客户端信息包括address/port/server_socket等多项客户端信息数据
     * 
     * Swoole onPacket event.
     */
    const ON_PACKET = 'packet';

    /**
     * 当管理进程启动时调用它 (在这个回调函数中可以修改管理进程的名称)
     *
     * @param \swoole_server $server
     * 
     * Swoole onManagerStart event.
     */
    const ON_MANAGER_START = 'managerStart';

    /**
     * 当管理进程结束时调用它
     *      onManagerStop触发时，说明Task和Worker进程已结束运行，已被Manager进程回收
     *
     * @param \swoole_server $server
     * 
     * Swoole onManagerStop event.
     */
    const ON_MANAGER_STOP = 'managerStop';

    /**
     * it's not a swoole event.
     */
    const ON_BEFORE_MAIN_SERVER_START = 'beforeMainServerStart';

    /**
     * Swoole 回调事件
     * @var array
     */
    protected static $swooleCallbackEvents = [
        self::ON_CLOSE          =>  ['onClose',         CloseCallback::class],
        self::ON_CONNECT        =>  ['onConnect',       ConnectCallback::class],
        self::ON_FINISH         =>  ['onFinish',        FinishCallback::class],
        self::ON_HAND_SHAKE     =>  ['onHandShake',     HandShakeCallback::class],
        self::ON_MANAGER_START  =>  ['onManagerStart',  ManagerStartCallback::class],
        self::ON_MESSAGE        =>  ['onMessage',       MessageCallback::class],
        self::ON_PACKET         =>  ['onPacket',        PacketCallback::class],
        self::ON_PIPE_MESSAGE   =>  ['onPipeMessage',   PipeMessageCallback::class],
        self::ON_RECEIVE        =>  ['onReceive',       ReceiveCallback::class],
        self::ON_REQUEST        =>  ['onRequest',       RequestCallback::class],
        self::ON_SHUTDOWN       =>  ['onShutdown',      ShutdownCallback::class],
        self::ON_START          =>  ['onStart',         StartCallback::class],
        self::ON_TASK           =>  ['onTask',          TaskCallback::class],
        self::ON_WORKER_ERROR   =>  ['onWorkerError',   WorkerErrorCallback::class],
        self::ON_WORKER_EXIT    =>  ['onWorkerExit',    WorkerExitCallback::class],
        self::ON_WORKER_START   =>  ['onWorkerStart',   WorkerStartCallback::class],
        self::ON_WORKER_STOP    =>  ['onWorkerStop',    WorkerStopCallback::class],
    ];

    /**
     * @param $event
     * @return bool
     */
    public static function isSwooleEvent($event): bool
    {
        if (in_array($event, [
            self::ON_BEFORE_MAIN_SERVER_START,
        ])) {
            return false;
        }

        return true;
    }

    /**
     * 获取 Swoole 事件名称
     * @return array
     */
    public static function getSwooleEvents(): array
    {
        return array_keys(self::$swooleCallbackEvents);
    }

    /**
     * 获取 Swoole 默认回调事件
     * @param array $events
     * @return array
     */
    public static function getDefaultSwooleCallbackEvents(array $events = []): array
    {
        if ($events) {
            $callback = [];
            foreach ($events as $event) {
                if (isset(self::$swooleCallbackEvents[$event])) {
                    $callback[$event] = self::$swooleCallbackEvents[$event];
                }
            }

            return $callback;
        }

        return self::$swooleCallbackEvents;
    }

    /**
     * Swoole 事件绑定
     * @param              $server
     * @param string       $event
     * @param callable     $callback
     */
    public static function on($server, string $event, callable $callback)
    {
        $server->on($event, $callback);
    }
}