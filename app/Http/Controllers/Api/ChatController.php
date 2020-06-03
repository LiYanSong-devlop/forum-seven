<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiController;
use GatewayWorker\Lib\Gateway;

class ChatController extends ApiController
{
    //workerman 绑定
    public function __construct()
    {
        Gateway::$registerAddress = config('GatewayWorker.registerAddress');
    }


    /**
     * 通过clientId与用户ID进行绑定
     * @return mixed
     */
    public function build()
    {
        //TODO 暂时写死
        //$user = auth('api')->user();
        //$userId = $user->id;
        $userId = 16;
        $clientId = request()->get('client_id');
        if (!Gateway::isOnline($clientId)) {
            return $this->failed('ClientId不合法');
        }
        if (Gateway::getUidByClientId($clientId)) {
            return $this->failed('已被绑定');
        }
        try {
            Gateway::bindUid($clientId,$userId);
            return $this->success('绑定成功');
        } catch (\Exception $exception) {
            return $this->failed('连接被拒绝');
        }
    }

    /**
     * 发送消息
     * @return mixed
     */
    public function sendMessage()
    {
        //TODO 暂时写死
        //$user = auth('api')->user();
        $user = [
            'id' => 17,
            'user_name' => '发送方',
            'avatar' => 'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=1906469856,4113625838&fm=26&gp=0.jpg'
        ];
        $toId = request()->get('toId'); //接收方ID(接收方用户ID) 默认给16 发送消息
        //接收请求的数据并组合发送数据
        $data = request()->only([
            'message', //发送消息
        ]);
        $data['type'] = 'send'; //数据类型  发送
        //$data['from_user_id'] = $user->id; //发送方ID
        $data['from_user_id'] = $user['id']; //TODO 发送方ID
        //$data['from_user_name'] = $user->user_name; //发送方用户名
        $data['from_user_name'] = $user['user_name']; //TODO 发送方用户名
        //$data['from_user_avatar'] = $user->avatar; //发送方头像
        $data['from_user_avatar'] = $user['avatar']; //TODO 发送方头像
        $data['to_user_id'] = $toId; //发送方头像
        $data['send_time'] = date('Y-m-d H:i:s', time());
        try {
            //判断对方是否在线
            if (Gateway::isUidOnline($toId)) {
                //在线直接发送消息
                Gateway::sendToUid($toId,json_encode($data));
            }else{
                //不在线，存入缓存或者是Redis中  获取以前未读消息
                $noReadMessageJson = \Cache::get('userChat_' . $toId);
                $noReadMessage = json_decode($noReadMessageJson);
                if (!$noReadMessage || !is_array($noReadMessage)) $noReadMessage = [];
                $noReadMessage[] = $data;
                $result = \Cache::set('userChat_' . $toId, json_encode($noReadMessage));
            }
            //返回信息
            return $this->success('发送成功');
        } catch (\Exception $exception) {
            return $this->failed('连接被拒绝');
        }
    }

    /**
     * 接受未读消息
     * @return mixed
     */
    public function getMessage()
    {
        //TODO 暂时写死
        //$user = auth('api')->user();
        //$userId = $user->id;
        $userId = 16;
        //获取从缓存或者是Redis中存储的数据 并清除缓存
        $noReadMessageJson = \Cache::pull('userChat_' . $userId);
        $noReadMessage = json_decode($noReadMessageJson);
        if (!$noReadMessage || !is_array($noReadMessage)) $noReadMessage = [];
        //返回数据
        return $this->success($noReadMessage);
    }
}
