<?php

namespace Tests\WarehouseBundle\Controller;

use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase
{
    protected $client;

    protected $token;


    public function __construct()
    {
        parent::__construct();

        $this->client = new \GuzzleHttp\Client([
            'base_url' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $this->token = $this->getToken();

    }

    protected function getToken()
    {
        $response = $this->client->get('http://127.0.0.1:8000/oauth/v2/token?client_id=6_4yzs0566dl0kcwsgwkg4wwccg4koc44cg880o0ws8s40osc8co&client_secret=4kjjryzg4xes4og0kckowk8w0owc4gokgo0kkcww4co80sk4go&grant_type=password&username=dima&password=111');
        return json_decode($response->getBody()->getContents())->access_token;
    }
}