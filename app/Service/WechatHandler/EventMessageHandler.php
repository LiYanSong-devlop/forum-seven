<?php


namespace App\Service\WechatHandler;


use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OfficialAccount\Application;
use EasyWeChatComposer\EasyWeChat;

class EventMessageHandler implements EventHandlerInterface
{
    protected $user;
    public function __construct(Application $application)
    {
        $this->user = $application->user;
    }

    /**
     * @param null $payload
     * @return News|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function handle($payload = null)
    {
        switch ($payload['Event']) {
            //关注事件
            case "subscribe":
                $openId = $payload['FromUserName'];
                \Log::info('follow Time: '.date('Y-m-d H:i:s',time()));
                \Log::info('感谢关注：'.$openId);
//                $user = $this->user->get($openId);
                $items = [
                    new NewsItem([
                        'title' => '感谢你的关注，Thanks！',
                        'image' => 'https://up.enterdesk.com/edpic/7a/8e/ff/7a8eff2b13ae2ba2cb121c00349a8fb6.png',
                        'url' => 'https://up.enterdesk.com/edpic/7a/8e/ff/7a8eff2b13ae2ba2cb121c00349a8fb6.png'
                    ]),
                    new NewsItem([
                        'title' => '闲下来的时候可以随意发送一下你想对我说的话，看看有什么东西',
                    ]),
                ];
                //发送图文信息
                return new News($items);
                break;
            //取消关注事件
            case "unsubscribe":
                return '可以告诉我为什么取关吗？';
                break;
            //扫描带参数的二维码事件
            case "SCAN":
                return '感谢关注';
                break;
            //其他事件
            default:
                //记录其他事件触发的时间
                \Log::info('Other Event Time: '.date('Y-m-d H:i:s',time()));
                $openId = $payload['FromUserName'];
                //记录其他事件触发的用户
                \Log::info('OpenId：'.$openId);
                $items = new Text('其他事件，暂不做处理，请见谅');
                return $items;
        }
    }
}
