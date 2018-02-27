<?php

namespace tests\WarehouseBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    protected $client;

    protected $token;


    public function __construct()
    {
        parent::__construct();

        $this->client = static::createClient();

        $this->token = $this->getToken();

    }

    protected function getToken()
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/oauth/v2/token?client_id=1_4eyp1k2a6gw0kc4ocgwc4s00kgwkk0kw0oco4ggokswg0o4cco&client_secret=4v3bk52yaqyokskckk0sgwogwkwc8o4csgoc0occcwgkowgsgo&grant_type=password&username=dima&password=111');
        return json_decode($this->client->getResponse()->getContent())->access_token;
    }
}