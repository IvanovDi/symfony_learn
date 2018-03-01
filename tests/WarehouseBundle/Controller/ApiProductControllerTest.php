<?php

namespace Tests\WarehouseBundle\Controller;

use tests\WarehouseBundle\Controller\ApiControllerTest;

class ApiProductControllerTest extends ApiControllerTest
{

    protected function getToken()
    {
        $this->client->request('GET', 'http://127.0.0.1:8000/oauth/v2/token?client_id=1_4eyp1k2a6gw0kc4ocgwc4s00kgwkk0kw0oco4ggokswg0o4cco&client_secret=4v3bk52yaqyokskckk0sgwogwkwc8o4csgoc0occcwgkowgsgo&grant_type=password&username=dima&password=111');
        return json_decode($this->client->getResponse()->getContent())->access_token;
    }

    protected function getLastId()
    {
        $this->client->request('GET', 'http://localhost:8000/api/products?access_token='.$this->getToken());
        $data = json_decode($this->client->getResponse()->getContent());
        return $data[0]->id;
    }

    protected function getLastCategoryId()
    {
        $this->client->request('GET', 'http://localhost:8000/api/categories?access_token='.$this->getToken());
        $categories = json_decode($this->client->getResponse()->getContent());

        return $categories[0]->id;
    }


    /**
     * @group api
     */
    public function testPostProductAction()
    {
        $this->loadFixtures([
            'tests\WarehouseBundle\DataFixtures\AuthFixtures',
            'tests\WarehouseBundle\DataFixtures\WarehouseFixtures'
        ]);


        $this->client->request('POST', 'http://localhost:8000/api/products?access_token='.$this->getToken(),[],[],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "title new","description": "Post description", "model": "c2001", "category": "' . $this->getLastCategoryId() . '"}');

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());


    }
    /**
     * @group api
     */
    public function testGetProductsAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/products?access_token='.$this->getToken());
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testGetProductAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken());
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testPutProductAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken());
        $product_before = json_decode($this->client->getResponse()->getContent());

        $this->client->request('PUT', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken(),[],[],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "title  PUT","description": "PUT description", "model": "c233", "category": "' . $this->getLastCategoryId() . '"}');

        $responsePut = $this->client->getResponse();

        $this->client->request('GET', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken());
        $product_after = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($product_before->title, $product_after->title);
    }

    /**
     * @group api
     */
    public function testPatchProductAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken());
        $product_before = json_decode($this->client->getResponse()->getContent());

        $this->client->request('PATCH', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken(),[],[],
            ['CONTENT_TYPE' => 'application/json'],
            '{"description": "PATCH description"}');

        $responsePut = $this->client->getResponse();

        $this->client->request('GET', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken());
        $product_after = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($product_before->description, $product_after->description);
    }

    /**
     * @group api
     */
    public function testDeleteProductAction()
    {
        $this->client->request('DELETE', 'http://localhost:8000/api/products/'. $this->getLastId() .'?access_token='.$this->getToken());

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }
}