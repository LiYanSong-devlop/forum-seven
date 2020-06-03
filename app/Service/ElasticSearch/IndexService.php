<?php


namespace App\Service\ElasticSearch;


class IndexService
{
    //elasticsearch 索引 index
    protected $client;

    public function __construct()
    {
        $this->client = ClientService::client();
    }

    /**
     * @param $index
     * @return array|bool
     */
    public function compareIndex($index)
    {
        $params = [
            'index' => $index,
        ];
        try {
            $this->client->indices()->getSettings($params);
            return true;
        } catch (\Exception $e) {
            return ElasticsearchError::errors($e->getMessage());
        }
    }

    /**
     * 创建索引
     * 可以直接创建字段
     * 也可以不用直接创建字段
     * @param $index_name
     * @param $setting
     * @param null $mappings
     * @return array
     * @throws \Exception
     */
    public function createIndex($index,$setting,$mappings = null)
    {
        $params = [
            'index' => $index,
            'body' => [
                'settings' => $setting,
            ],
        ];

        if (!empty($mappings)) {
            $params['body']['mappings'] = $mappings;
        }
        try {
            return $this->client->indices()->create($params);
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 索引详情
     * 支持多个索引的查询
     * @param $index
     * @return array
     * @throws \Exception
     */
    public function show($index)
    {
        $params = [
            'index' => $index,
        ];
        try {
            return $this->client->indices()->get($params);
        } catch (\Exception $e) {
            return ElasticsearchError::errors($e->getMessage());
        }
    }

    /**
     * 更新索引设置 setting
     * 不可以动态更新
     * 目前只能更新副本数量 number_of_replicas
     * @param $setting
     * @param $index
     * @return array
     */
    public function updateSetting($index,$setting)
    {
        $params = [
            'index' => $index,
            'body' => [
                'settings' => $setting,
            ],
        ];
        try {
            return $this->client->indices()->putSettings($params);
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 增加字段
     * 无法更新索引的字段
     * 增加之后无法更改
     * @param $properties
     * @param $index
     * @return array
     */
    public function updateMapping($index,$properties)
    {
        $params = [
            'index' => $index,
            'body' => [
                'properties' => $properties,
            ]
        ];
        try {
            return $this->client->indices()->putMapping($params);
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 删除索引
     * @param string $index
     * @return array
     */
    public function delete($index)
    {
        $params = [
            'index' => $index,
        ];
        try {
            return $this->client->indices()->delete($params);
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

}
