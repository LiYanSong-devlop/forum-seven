<?php


namespace App\Service\ElasticSearch;


use Elasticsearch\ClientBuilder;

final class ClientService
{
    //使用elasticsearch 基类
    public static $client;

    public function __construct()
    {

    }

    /**
     * 获取elasticsearch实例
     * @return \Elasticsearch\Client|null
     */
    public static function client()
    {
        if (self::$client == null) {
            return self::$client = ClientBuilder::create()->setHosts(config('database.elasticsearch'))->build();
        }else{
            return self::$client;
        }
    }
}
