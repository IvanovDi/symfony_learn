<?php

namespace Test\WarehouseBundle\Controller;

use PHPUnit\Framework\TestCase;

class ApiCategoryControllerTest extends TestCase
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
        $response = $this->client->get('http://127.0.0.1:8000/oauth/v2/token?client_id=1_1pylbga86af4wwco8wgwcwgsk0occcwgsgk0cscgwwswwcw44g&client_secret=1qistd1qu7okwggcgc4g04kogw4w88wk0oo8wowsw0g4sokwkc&grant_type=password&username=dima&password=111');
        return json_decode($response->getBody()->getContents())->access_token;
    }

    public function testPostCategoryAction()
    {

        $data = [
            'title' => 'test title_' . rand(0, 100),
            'description' => 'test description_' . rand(0, 100),
        ];

        $response = $this->client->post('http://localhost:8000/api/category?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        print_r($this->getToken());

    }

    public function testSGetCategoryAction()
    {
        $response = $this->client->get('http://localhost:8000/api/categories?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testGetCategoryAction()
    {
        $response = $this->client->get('http://localhost:8000/api/category/2?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testPutCategoryAction()
    {
        $response = $this->client->get('http://localhost:8000/api/category/2?access_token='.$this->token);
        $category_before = json_decode($response->getBody()->getContents());

        $data = [
            'title' => 'test title_' . rand(0, 100),
            'description' => 'test description_' . rand(0, 100),
        ];

        $responsePut = $this->client->put('http://localhost:8000/api/category/2?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/category/2?access_token='.$this->token);
        $category_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($category_before->title, $category_after->title);
    }

    public function testPatchCategoryAction()
    {
        $response = $this->client->get('http://localhost:8000/api/category/2?access_token='.$this->token);
        $category_before = json_decode($response->getBody()->getContents());

        $data = [
            'description' => 'test description_' . rand(0, 100)
        ];

        $responsePatch = $this->client->patch('http://localhost:8000/api/category/2?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/category/2?access_token='.$this->token);
        $category_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePatch->getStatusCode());
        $this->assertNotEquals($category_before->description, $category_after->description);
    }

    public function testDeleteCategoryAction()
    {
        $response = $this->client->delete('http://localhost:8000/api/category/2?access_token='.$this->token);

        $this->assertEquals(204, $response->getStatusCode());
    }
}