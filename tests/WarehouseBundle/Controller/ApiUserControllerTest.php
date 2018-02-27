<?php

namespace Tests\WarehouseBundle\Controller;

use tests\WarehouseBundle\Controller\ApiControllerTest;


class ApiUserControllerTest extends ApiControllerTest
{
    protected function getLastId()
    {
        $response = $this->client->get('http://localhost:8000/api/users?access_token='.$this->token);
        $users = json_decode($response->getBody()->getContents());
        return $users[0]->id;
    }

    /**
     * @group api
     */
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

        $response = $this->client->post('http://localhost:8000/api/users?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());

    }

    /**
     * @group api
     */
    public function testGetUserAction()
    {
        $response = $this->client->get('http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testGetUsersAction()
    {
        $response = $this->client->get('http://localhost:8000/api/users?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testPutUserAction()
    {
        $response = $this->client->get('http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->token);
        $user_before = json_decode($response->getBody()->getContents());

        $data = [
            'username' => 'new username_' . rand(0, 100),
            'email' => 'newPuttest' . rand(0, 100) . '@gmail.com',
            'plainPassword' => [
                'first' => '111',
                'second' => '111'
            ],
        ];

        $responsePut = $this->client->put('http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->token);
        $user_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(204, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    /**
     * @group api
     */
    public function testPatchUserAction()
    {
        $response = $this->client->get('http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->token);
        $user_before = json_decode($response->getBody()->getContents());

        $data = [
            'username' => 'new username_' . rand(0, 100),
            'plainPassword' => [
                'first' => '111',
                'second' => '111'
            ],
        ];

        $responsePut = $this->client->patch('http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->token);
        $user_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(204, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    public function testDeleteUserAction()
    {
//        $response = $this->client->delete('http://localhost:8000/api/user/' . $this->getLastId() . '?access_token='.$this->token);
//
//        $this->assertEquals(204, $response->getStatusCode());
    }
}