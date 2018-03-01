<?php

namespace Tests\WarehouseBundle\Controller;

use tests\WarehouseBundle\Controller\ApiControllerTest;


class ApiUserControllerTest extends ApiControllerTest
{

    protected function getToken()
    {
        $this->client->request('GET', 'http://127.0.0.1:8000/oauth/v2/token?client_id=1_4eyp1k2a6gw0kc4ocgwc4s00kgwkk0kw0oco4ggokswg0o4cco&client_secret=4v3bk52yaqyokskckk0sgwogwkwc8o4csgoc0occcwgkowgsgo&grant_type=password&username=dima&password=111');
        return json_decode($this->client->getResponse()->getContent())->access_token;
    }

    protected function getLastId()
    {
        $this->client->request('GET', 'http://localhost:8000/api/users?access_token='.$this->getToken());
        $users = json_decode($this->client->getResponse()->getContent());
        return $users[count($users) - 1]->id;
    }

    /**
     * @group api
     */
    public function testPostUserAction()
    {
        $this->loadFixtures([
            'tests\WarehouseBundle\DataFixtures\AuthFixtures',
            'tests\WarehouseBundle\DataFixtures\WarehouseFixtures'
        ]);

        $this->client->request('POST', 'http://localhost:8000/api/users?access_token='.$this->getToken(),[],[],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username": "user test","email": "test@gmail.com", "plainPassword": {"first": "111", "second": "111"}}');

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @group api
     */
    public function testGetUserAction()
    {
        $response = $this->client->request('GET', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken());
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testGetUsersAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/users?access_token='.$this->getToken());
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testPutUserAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken());
        $user_before = json_decode($this->client->getResponse()->getContent());



        $this->client->request('PUT', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken(),[],[],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username": "user PUT","email": "testput@gmail.com", "plainPassword": {"first": "111", "second": "111"}}');

        $responsePut = $this->client->getResponse();

        $this->client->request('GET', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken());
        $user_after = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(204, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    /**
     * @group api
     */
    public function testPatchUserAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken());
        $user_before = json_decode($this->client->getResponse()->getContent());



        $this->client->request('PATCH', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken(),[],[],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username": "user PATCH", "plainPassword": {"first": "111", "second": "111"}}');

        $responsePut = $this->client->getResponse();

        $this->client->request('GET', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken());
        $user_after = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(204, $responsePut->getStatusCode());
        $this->assertNotEquals($user_before->username, $user_after->username);
    }

    /**
     * @group api
     */
    public function testDeleteUserAction()
    {
        $this->client->request('DELETE', 'http://localhost:8000/api/users/' . $this->getLastId() . '?access_token='.$this->getToken());

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }
}