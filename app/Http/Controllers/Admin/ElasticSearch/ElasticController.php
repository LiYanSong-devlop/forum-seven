<?php

namespace App\Http\Controllers\Admin\ElasticSearch;

use App\Http\Controllers\Controller;
use App\Service\ElasticSearch\DataService;
use App\Service\ElasticSearch\IndexService;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class ElasticController extends Controller
{
    //
    protected $client;

    public function __construct(ClientBuilder $builder)
    {
        $this->client = $builder->setHosts(config('database.elasticsearch'))->build();
    }

    /**
     * @param string $index_name
     * @throws \Exception
     */
    public function createIndex()
    {
        /*$params = [
            'index' => $index_name,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    'properties' => [
                        'test' => [
                            'type' => 'keyword',
                        ],
                        'age1' => [
                            'type' => 'keyword'
                        ]
                    ]
                ]
            ]
        ];*/
        $query = [
            'test123321312' => [
                'type' => 'keyword',
            ],
            'age123321312' => [
                'type' => 'keyword'
            ]
        ];
        $data = [
            [
                'title' => '今天天气真好，无聊',
                'first_name' => '小小考试考得1',
                'age' => 21,
//                'id' => 20,
                'content' => '天剑是打送到发送到发送发点发送到阿萨德啊撒旦法是阿斯蒂芬'
            ],
            [
                'title' => '今天天气真好，愉悦',
                'first_name' => '小小考试考得2',
                'age' => 22,
//                'id' => 30,
                'content' => '天剑是送到发送到发打发点发送到阿萨德啊撒旦法是阿斯蒂芬'
            ],
            [
                'title' => '今天天气真好，高兴',
                'first_name' => '小小考试考得3',
                'age' => 23,
//                'id' => 40,
                'content' => '天剑是说的发打发点发送到阿萨德啊撒旦法是阿斯蒂芬'
            ],
            [
                'title' => '今天天气真好,舒服',
                'first_name' => '小小考试考得4',
                'age' => 24,
//                'id' => 50,
                'content' => '天剑阿萨德是打发点发送到阿萨德啊撒旦法是阿斯蒂芬'
            ]
        ];
        $client = new DataService();
//        $a = $client->maxCreate('test_ik',$data);
        $a = $client->conditionSearch('test_ik',['title' => '舒服'],1,30);
        return $a;
        /*try {
            $result = $this->client->indices()->create($params);
            return $result;
        } catch (\Elasticsearch\Common\Exceptions\BadRequest400Exception $e) {
            $msg = $e->getMessage();
            $msg = json_decode($msg,true);
            return $msg;
        }*/
    }

    public function createMappings($index_name = 'test_ik')
    {
        $properties = [
            'id'      => [
                'type' => 'keyword', // 整型
            ],
            'title'   => [
                'type' => 'text', // 字符串型
            ],
            'content' => [
                'type' => 'text',
            ],
        ];
        $params = [
            'index' => $index_name,
            'body' => [
                'properties' => $properties,
            ]
        ];
        try {
            $response = $this->client->indices()->putMapping($params);
            return $response;
        } catch (\Elasticsearch\Common\Exceptions\BadRequest400Exception $e) {
            $msg = $e->getMessage();
            $msg = json_decode($msg,true);
            return $msg;
        }
    }

    public function createData($index_name = 'test_ik',$type = '_doc')
    {
        $data = [
            'id' => 2,
            'age' => 20,
            'first_name' => 'test',
            'title' => '测试数据',
            'content' => '测试内容',
        ];
        $params = [
            'index' => $index_name,
            'type' => $type,
            //'id' => '1', // 如果不提供id，es会自动生成
            'body' => $data
        ];
        return $this->client->index($params);
    }
}
