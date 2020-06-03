<?php


namespace App\Workerman;


use GatewayWorker\Lib\Gateway;

class Events
{
    //在 workerManServer 启动时 触发
    public static function onWorkerStart($businessWorker)
    {
        echo "Welcome To Open Worker Man Server";
    }

    //当客户端连接时触发 （可以删除 onConnect） 先执行onConnect  后执行 onWebSocketConnect
    public static function onConnect($client_id)
    {
        echo "Client_id: ".$client_id." Connect Success";
        $data = [
            'type' => 'build',
            'client_id' => $client_id,
        ];
        //向当前客户端连接发送消息
        return Gateway::sendToCurrentClient(json_encode($data));
    }

    //在 websocket 连接时触发
    /*public static function onWebSocketConnect($client_id, $data)
    {
        echo "Client WebSocket Connect Success";
    }*/

    //客户端发来消息时触发
    public static function onMessage($client_id, $message)
    {
        echo $message;
    }

    //客户端关闭时触发
    public static function onClose($client_id)
    {
        echo "Client_id: " . $client_id . " Close Success";
    }
}
