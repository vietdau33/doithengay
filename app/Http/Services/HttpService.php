<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class HttpService
{
    private static Client|null $client = null;
    private static HttpService|null $ins = null;

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

    public static function client(): ?Client
    {
        self::initClient();
        return self::$client;
    }

    private static function initClient()
    {
        if (self::$client == null) {
            self::$client = new Client();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $url): array
    {
        try {
            $content = self::$client->get($url)->getBody()->getContents();
            return json_decode($content, 1);
        } catch (Exception $exception) {
            logger($exception->getMessage());
            return [];
        }
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $url, array $param = [], string $type = RequestOptions::JSON){
        try {
            $content = self::$client->post($url, [$type => $param])->getBody()->getContents();
            return json_decode($content, 1);
        } catch (Exception $exception) {
            logger($exception->getMessage());
            return [];
        }
    }
}
