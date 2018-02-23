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
        $response = $this->client->get('http://127.0.0.1:8000/oauth/v2/token?client_id=11_4koqwixjyg00s0o0g4soww0ogcoso88o4c80k8o0cwwgwo4k8g&client_secret=2cirquyqslq8g8g44088wkwo0kg80oskwogs4gc8k8cgwk04ko&grant_type=password&username=dima&password=111');
        return json_decode($response->getBody()->getContents())->access_token;
    }
}