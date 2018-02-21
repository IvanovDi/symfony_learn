<?php

namespace Test\WarehouseBundle\Controller;

use Tests\WarehouseBundle\Controller\ApiControllerTest;

class ApiCategoryControllerTest extends ApiControllerTest
{

    protected function getLastId()
    {
        $response = $this->client->get('http://localhost:8000/api/categories?access_token='.$this->token);
        $catgories = json_decode($response->getBody()->getContents());
        return $catgories[0]->id;
    }

    /**
     * @group api
     */
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

    }

    /**
     * @group api
     */
    public function testGetCategoriesAction()
    {
        $response = $this->client->get('http://localhost:8000/api/categories?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testGetCategoryAction()
    {
        $response = $this->client->get('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * @group api
     */
    public function testPutCategoryAction()
    {
        $response = $this->client->get('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token);
        $category_before = json_decode($response->getBody()->getContents());

        $data = [
            'title' => 'test title_' . rand(0, 100),
            'description' => 'test description_' . rand(0, 100),
        ];

        $responsePut = $this->client->put('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token);
        $category_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($category_before->title, $category_after->title);
    }

    /**
     * @group api
     */
    public function testPatchCategoryAction()
    {
        $response = $this->client->get('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token);
        $category_before = json_decode($response->getBody()->getContents());

        $data = [
            'description' => 'test description_' . rand(0, 100)
        ];

        $responsePatch = $this->client->patch('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token);
        $category_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePatch->getStatusCode());
        $this->assertNotEquals($category_before->description, $category_after->description);
    }

    public function testDeleteCategoryAction()
    {
//        $response = $this->client->delete('http://localhost:8000/api/category/'. $this->getLastId() .'?access_token='.$this->token);
//
//        $this->assertEquals(204, $response->getStatusCode());
    }
}