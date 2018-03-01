<?php

namespace tests\WarehouseBundle\Controller;


class ApiCategoryControllerTest extends ApiControllerTest
{


    protected function getToken()
    {
        $this->client->request('GET', 'http://127.0.0.1:8000/oauth/v2/token?client_id=1_4eyp1k2a6gw0kc4ocgwc4s00kgwkk0kw0oco4ggokswg0o4cco&client_secret=4v3bk52yaqyokskckk0sgwogwkwc8o4csgoc0occcwgkowgsgo&grant_type=password&username=dima&password=111');
        return json_decode($this->client->getResponse()->getContent())->access_token;
    }

    protected function getLastId()
    {
        $this->client->request('GET', 'http://localhost:8000/api/categories?access_token='.$this->getToken());
        $catgories = json_decode($this->client->getResponse()->getContent());
        return $catgories[0]->id;
    }

    /**
     * @group api
     */
    public function testPostCategoryAction()
    {

        $this->loadFixtures([
            'tests\WarehouseBundle\DataFixtures\AuthFixtures',
            'tests\WarehouseBundle\DataFixtures\WarehouseFixtures'
        ]);


        $this->client->request('POST',
            'http://localhost:8000/api/categories?access_token='.$this->getToken(),[],[],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "title test","description": "test description"}');

        file_put_contents('/home/dima/Documents/file1.txt', print_r($this->client->getResponse()->getContent(), true));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @group api
     */
    public function testGetCategoriesAction()
    {

        $this->loadFixtures([
            'tests\WarehouseBundle\DataFixtures\AuthFixtures',
            'tests\WarehouseBundle\DataFixtures\WarehouseFixtures'
        ]);

        $this->client->request('GET', 'http://localhost:8000/api/categories?access_token='.$this->getToken());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testGetCategoryAction()
    {
        $this->loadFixtures([
            'tests\WarehouseBundle\DataFixtures\AuthFixtures',
            'tests\WarehouseBundle\DataFixtures\WarehouseFixtures'
        ]);

        $this->client->request('GET', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken());
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testPutCategoryAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken());
        $category_before = json_decode($this->client->getResponse()->getContent());

        $this->client->request('PUT', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken(), [], [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "PUT title","description": "PUT description"}');

        $responsePut = $this->client->getResponse();

        $this->client->request('GET', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken());
        $category_after = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(204, $responsePut->getStatusCode());
        $this->assertNotEquals($category_before->title, $category_after->title);
    }

    /**
     * @group api
     */
    public function testPatchCategoryAction()
    {
        $this->client->request('GET', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken());
        $category_before = json_decode($this->client->getResponse()->getContent());

        $this->client->request('PATCH', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken(), [], [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"description": "PATCH description"}');

        $responsePut = $this->client->getResponse();

        $this->client->request('GET', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken());
        $category_after = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(204, $responsePut->getStatusCode());
        $this->assertNotEquals($category_before->description, $category_after->description);
    }


    /**
     * @group api
     */
    public function testDeleteCategoryAction()
    {
        $this->loadFixtures([
            'tests\WarehouseBundle\DataFixtures\AuthFixtures',
            'tests\WarehouseBundle\DataFixtures\WarehouseFixtures'
        ]);

        $this->client->request('DELETE', 'http://localhost:8000/api/categories/'. $this->getLastId() .'?access_token='.$this->getToken());

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }
}