<?php


namespace App\Service\ElasticSearch;


class ElasticsearchError
{
    public static function errors($message)
    {
        $message = json_decode($message,true);
        return $message;
        $errors = [
            'status' => isset($message['status']) ? $message['status'] : 400,
            'message' => isset($message['error']['reason']) ? $message['error']['reason'] : $message['error'],
        ];
        return $errors;
    }
}
