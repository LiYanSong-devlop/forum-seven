<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\WechatHandler\EventMessageHandler;
use App\Service\WechatHandler\TextMessageHandler;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Http\Request;

class WechatOfficialAccountController extends Controller
{
    //微信公众号登录相关
    protected $server;
    protected $menu;

    public function __construct(Application $application)
    {
        $this->server = $application->server;
        $this->menu = $application->menu;
    }

    /**
     * 所有定义的(Event)...MessageHandler
     * 都需要实现 EasyWeChat\Kernel\Contracts\EventHandlerInterface
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function serve()
    {
//        \Log::info('测试');
        $this->server->push(EventMessageHandler::class, Message::EVENT);
        $this->server->push(TextMessageHandler::class, Message::TEXT);
        /*$this->server->push(function($message){
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
//            return "欢迎关注 overtrue！";
        });*/
        return $this->server->serve();
    }
}
