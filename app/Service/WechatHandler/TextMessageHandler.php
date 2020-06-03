<?php


namespace App\Service\WechatHandler;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;

class TextMessageHandler implements EventHandlerInterface
{
    //文字处理
    public function handle($payload = null)
    {
        $search_str = $payload['Content'];
        //文字处理 判断传过来的是什么文字
        return $this->getAnswer($search_str);
    }

    public function getAnswer($search_str)
    {
        $ai_result = $this->smartChatting($search_str);
        if ($ai_result == false) {
            return $this->definitionArray($search_str);
        }
        //将返回格式转换成数组
        $ai_array = json_decode($ai_result, true);
        if (is_array($ai_array) && $ai_array['ret'] == 0){
            return  $ai_array['data']['answer'];
        }else{
            return $this->definitionArray($search_str);
        }
    }

    /**
     * 自己定义的一些语句
     * @param $search_str
     * @return mixed|string
     */
    public function definitionArray($search_str)
    {
        $array = [
            '我', '你', '他', '它', '她'
        ];
        $result_array = [
            '我' => '你是有什么想对我说的话吗？',
            '你' => '我？ 什么情况？啥情况？ 不要让我彪东北话',
            '他' => '你说的他是指的谁？',
            '它' => '这么说，你也喜欢小动物了？',
            '她' => '真的假的？是不是给她发条消息？'
        ];
        foreach ($array as $value) {
            if (strstr($value, $search_str) !== false) {
                return $result_array[$search_str];
            }
        }
        return '没有找到相匹配的字词，可能是某个人太懒了，没有整呢！！！';
    }


    /**
     * AI 调用智能闲聊
     * @param $string
     * @return bool|string
     */
    public function smartChatting($string)
    {
        $app_id = '2131660189';
        $app_key = 'syl8iS7TvMJcB4AG';

        // 设置请求数据（应用密钥、接口请求参数）
        // 设置请求数据
        $appkey = $app_key;
        $params = array(
            'app_id'     => $app_id,
            'session'    => '10000',
            'question'   => $string,
            'time_stamp' => strval(time()),
            'nonce_str'  => strval(rand()),
            'sign'       => '',
        );
        $params['sign'] = $this->getReqSign($params, $appkey);
        // 执行API调用
        $url = 'https://api.ai.qq.com/fcgi-bin/nlp/nlp_textchat';
        $response = $this->doHttpPost($url, $params);
        return $response;
    }


    // getReqSign ：根据 接口请求参数 和 应用密钥 计算 请求签名
    // 参数说明
    //   - $params：接口请求参数（特别注意：不同的接口，参数对一般不一样，请以具体接口要求为准）
    //   - $appkey：应用密钥
    // 返回数据
    //   - 签名结果
    public function getReqSign($params /* 关联数组 */, $appkey /* 字符串*/)
    {
        // 1. 字典升序排序
        ksort($params);

        // 2. 拼按URL键值对
        $str = '';
        foreach ($params as $key => $value)
        {
            if ($value !== '')
            {
                $str .= $key . '=' . urlencode($value) . '&';
            }
        }

        // 3. 拼接app_key
        $str .= 'app_key=' . $appkey;

        // 4. MD5运算+转换大写，得到请求签名
        $sign = strtoupper(md5($str));
        return $sign;
    }

    // doHttpPost ：执行POST请求，并取回响应结果
    // 参数说明
    //   - $url   ：接口请求地址
    //   - $params：完整接口请求参数（特别注意：不同的接口，参数对一般不一样，请以具体接口要求为准）
    // 返回数据
    //   - 返回false表示失败，否则表示API成功返回的HTTP BODY部分
    function doHttpPost($url, $params)
    {
        $curl = curl_init();

        $response = false;
        do
        {
            // 1. 设置HTTP URL (API地址)
            curl_setopt($curl, CURLOPT_URL, $url);
            // 2. 设置HTTP HEADER (表单POST)
            $head = array(
                'Content-Type: application/x-www-form-urlencoded'
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
            // 3. 设置HTTP BODY (URL键值对)
            $body = http_build_query($params);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            // 4. 调用API，获取响应结果
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_NOBODY, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
            if ($response === false)
            {
                $response = false;
                break;
            }
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($code != 200)
            {
                $response = false;
                break;
            }
        } while (0);
        curl_close($curl);
        return $response;
    }
}
