<?php

namespace Tests\WarehouseBundle\Controller;


class ApiUserControllerTest extends ApiControllerTest
{

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
        $response = $this->client->get('http://localhost:8000/api/user/9?access_token='.$this->token);
        $user_before = json_decode($response->getBody()->getContents());

        $data = [
            'username' => 'new username_' . rand(0, 100),
            'email' => 'newPuttest' . rand(0, 100) . '@gmail.com',
            'plainPassword' => [
                'first' => '111',
                'second' => '111'
            ],
        ];

        $responsePut = $this->client->put('http://localhost:8000/api/user/9?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/user/9?access_token='.$this->token);
        $user_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    public function testPatchUserAction()
    {
        $response = $this->client->get('http://localhost:8000/api/user/9?access_token='.$this->token);
        $user_before = json_decode($response->getBody()->getContents());

        $data = [
            'username' => 'new username_' . rand(0, 100),
            'plainPassword' => [
                'first' => '111',
                'second' => '111'
            ],
        ];

        $responsePut = $this->client->patch('http://localhost:8000/api/user/9?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/user/9?access_token='.$this->token);
        $user_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    public function testDeleteUserAction()
    {
        $response = $this->client->delete('http://localhost:8000/api/user/9?access_token='.$this->token);

        $this->assertEquals(204, $response->getStatusCode());
    }
}