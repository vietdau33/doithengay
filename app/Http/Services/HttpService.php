<?php

namespace App\Http\Services;
use GuzzleHttp\Client;

class HttpService
{
    private static Client $client;
    private static HttpService $ins;

    public function __construct()
    {
        self::initClient();
    }

    public static function ins(): HttpService
    {
        if (self::$ins == null) {
            self::$ins = new self;
        }
        return self::$ins;
    }

    private static function initClient (){
        if (self::$client == null) {
            self::$client = new Client();
        }
    }

    public function get()
    {
        //Code here
    }
}
