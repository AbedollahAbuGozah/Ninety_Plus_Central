<?php

namespace App\services;

use GetStream\Stream\Client as StreamClient;

class StreamService
{
    protected $client;


    public function __construct()
    {
        $apiKey = config('stream.stream_api_key');
        $apiSecret = config('stream_api_secret.api_secret');
        $this->client = new StreamClient($apiKey, $apiSecret);
    }

    public function generateToken($userId)
    {
        return $this->client->createUserToken($userId);
    }

}
