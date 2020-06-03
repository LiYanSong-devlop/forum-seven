<?php
return [
    'registerAddress' => '127.0.0.1:1236',              #注册服务地址

    'gateway' => [
        'websocket' => "websocket://0.0.0.0:2346",      #Gateway 地址 访问
        'name' => 'Gateway',                            #设置Gateway进程的名称，方便status命令中查看统计
        'count' => 1,                                   #进程的数量
        'lanIp' => '127.0.0.1',                         #内网ip,多服务器分布式部署的时候需要填写真实的内网ip
        'port' => 2300,                                 #监听本机端口的起始端口
        'pingInterval' => 30,                           #心跳间距
        'pingData' => '{"type":"HeartBeat"}',           #心跳数据
        'pingNotResponseLimit' => 0,                    #服务端主动发送心跳
    ],

    'businessWorker' => [
        'name' => 'BusinessWorker',                     #设置BusinessWorker进程的名称
        'count' => 1,                                   #设置BusinessWorker进程的数量
        'eventHandler' => \App\Workerman\Events::class, #设置使用哪个类来处理业务,业务类至少要实现onMessage静态方法，onConnect和onClose静态方法可以不用实现
    ],
];
