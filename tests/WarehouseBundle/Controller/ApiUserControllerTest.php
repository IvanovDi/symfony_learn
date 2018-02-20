<?php

namespace Tests\WarehouseBundle\Controller;

use PHPUnit\Framework\TestCase;

class ApiUserControllerTest extends TestCase
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

    public function testPostUserAction()
    {
        $data = [
            'username' => 'test User' . rand(0, 100),
            'email' => 'test' . rand(0, 100) . '@gmail.com',
            'plainPassword' => [
                'first' => '111',
                'second' => '111'
            ],
        ];

        $response = $this->client->post('http://localhost:8000/api/user?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        print_r($this->getToken());

    }

    public function testGetUserAction()
    {
        $response = $this->client->get('http://localhost:8000/api/user/6?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testGetUsersAction()
    {
        $response = $this->client->get('http://localhost:8000/api/users?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testPutUserAction()
    {
        $response = $this->client->get('http://localhost:8000/api/user/7?access_token='.$this->token);
        $user_before = json_decode($response->getBody()->getContents());

        $data = [
            'username' => 'new username_' . rand(0, 100),
            'email' => 'newPuttest' . rand(0, 100) . '@gmail.com',
            'plainPassword' => [
                'first' => '111',
                'second' => '111'
            ],
        ];

        $responsePut = $this->client->put('http://localhost:8000/api/user/7?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/user/7?access_token='.$this->token);
        $user_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    public function testPatchUserAction()
    {
        $response = $this->client->get('http://localhost:8000/api/user/7?access_token='.$this->token);
        $user_before = json_decode($response->getBody()->getContents());

        $data = [
            'username' => 'new username_' . rand(0, 100),
            'plainPassword' => [
                'first' => '111',
                'second' => '111'
            ],
        ];

        $responsePut = $this->client->patch('http://localhost:8000/api/user/7?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/user/7?access_token='.$this->token);
        $user_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    public function testDeleteUserAction()
    {
        $response = $this->client->delete('http://localhost:8000/api/user/7?access_token='.$this->token);

        $this->assertEquals(204, $response->getStatusCode());
    }
}