<?php


namespace App\Service\ElasticSearch;


use phpDocumentor\Reflection\Types\Integer;

class DataService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientService::client();
    }

    /**
     * 创建一条数据
     * 多字段创建
     * @param $index
     * @param array $data
     * @param null $id
     * @return array|bool
     */
    public function insert($index,array $data,$id = null)
    {
        $params = [
            'index' => $index,
            'body' => $data,
        ];
        if (!empty($id)) {
            $params['id'] = $id;
        }
        try {
            $this->client->index($params);
            return true;
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 批量创建数据
     * _id 由elasticsearch自动生成 可以设置成自增数字
     * 最多创建100条数据
     * 如果是想使用每条数据的ID即为，不传形参ID  并且每条数据里面携带ID字段即可
     * 如果存在错误返回插入格式
     * @param $index
     * @param array $data
     * @param null $data
     * @return array|bool|mixed|string
     */
    public function batchCreate($index,array $data, $id = null)
    {
        $params = [];
        foreach ($data as $key => $datum) {
            //如果是想使用每条数据的ID即为，不传形参ID  并且每条数据里面携带ID字段即可
            if (empty($id) && isset($datum['id']) && !empty($datum['id'])) {
                $_id = $datum['id'];
            }
            if (!empty($id)) {
                $_id = $id;
                $id++;
            }
            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_id' => isset($_id) && !empty($_id) ? $_id : null,
                ]
            ];
            $params['body'][] = $datum;
        }
        try {
            $response = $this->client->bulk($params);
            if ($response['errors'] === false) {
                return true;
            }else{
                return $response;
            }
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 超过100条数据插入如下，
     * 设置ID为自增从1自增
     * @param $index
     * @param array $data
     * @param null $id
     * @return array|bool
     */
    public function maxCreate($index,array $data,$id = null)
    {
        $params = [
            'body' => [],
        ];
        foreach ($data as $key => $datum) {
            //如果是想使用每条数据的ID即为，不传形参ID  并且每条数据里面携带ID字段即可
            if (empty($id) && isset($datum['id']) && !empty($datum['id'])) {
                $_id = $datum['id'];
            }
            if (!empty($id)) {
                $_id = $id;
                $id++;
            }
            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_id' => isset($_id) && !empty($_id) ? $_id : null,
                ]
            ];
            $params['body'][] = $datum;
            //如果数据量超过1000 执行如下操作
            if ($key % 1000 === 0) {
                $response = $this->client->bulk($params);
                $params['body'] = [];
                unset($response);
            }
        }
        try {
            if (!empty($params['body'])) {
                $response = $this->client->bulk($params);
            }
            if ($response['errors'] === false) {
                return true;
            }else{
                return $response;
            }
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 通过文档ID进行查询
     * @param $index
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function show($index,$id)
    {
        $params = [
            'index' => $index,
            'id' => $id
        ];
        try {
            $item = $this->client->get($params);
            return $this->dataFormatItem($item);
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 单条件查询
     * 分页查询，elasticsearch 默认从0开始
     * 不推荐如此使用
     * 当分页条数过多时，会出现重叠现象
     * @param $index
     * @param $search
     * @param int $from
     * @param int $size
     * @return array|mixed|string
     */
    public function conditionSearch($index,$search, $from = 1, $size = 10)
    {
        $params = [
            'index' => $index,
            'body' => [
                'query' =>[
                    'match' => $search
                ],
            ],
        ];
        //分页
        if (!empty($from)) {
            $params['body']['from'] = $from - 1;
            $params['body']['size'] = $size;
        }
        try {
            $result = $this->client->search($params);
            return $this->dataFormatSearch($result,$from,$size);
        } catch (\Exception $exception) {
            return ElasticsearchError::errors($exception->getMessage());
        }
    }

    /**
     * 数据格式化
     * 针对单个数据
     * @param $data
     * @return array
     */
    public function dataFormatItem($data)
    {
        $data = [
            'elsticsearch_id' => $data['_id'],
            'data' => $data['_source'],
        ];
        return $data;
    }

    /**
     * 数据格式化
     * 针对列表使用
     * @param $data
     * @param null $form
     * @param null $size
     * @return array
     */
    public function dataFormatSearch($data,$form = null,$size = null)
    {
        $result = [
            'data' => $data['hits']['hits'],
        ];
        if (!empty($form)) {
            $result['form'] = $form;
            $result['size'] = $size;
            $result['total'] = $data['hits']['total']['value'];
        }
        return $result;
    }
}
