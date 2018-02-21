<?php

namespace Tests\WarehouseBundle\Controller;



class ApiProductControllerTest extends ApiControllerTest
{
    public function testPostProductAction()
    {

        $data = [
            'title' => 'test title_' . rand(0, 100),
            'description' => 'test description_' . rand(0, 100),
            'model' => 'c200',
            'category' => 89
        ];

        $response = $this->client->post('http://localhost:8000/api/product?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        print_r($this->getToken());

    }

    public function testGetProductsAction()
    {
        $response = $this->client->get('http://localhost:8000/api/products?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testGetProductAction()
    {
        $response = $this->client->get('http://localhost:8000/api/product/1?access_token='.$this->token);
        $this->assertEquals($response->getStatusCode(), 200);
    }
//
    public function testPutProductAction()
    {
        $response = $this->client->get('http://localhost:8000/api/product/1?access_token='.$this->token);
        $product_before = json_decode($response->getBody()->getContents());

        $data = [
            'title' => 'put title_' . rand(0, 100),
            'description' => 'put description_' . rand(0, 100),
            'model' => 'c200',
            'category' => 89
        ];

        $responsePut = $this->client->put('http://localhost:8000/api/product/1?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/product/1?access_token='.$this->token);
        $product_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePut->getStatusCode());
        $this->assertNotEquals($product_before->title, $product_after->title);
    }
//
    public function testPatchProductAction()
    {
        $response = $this->client->get('http://localhost:8000/api/product/1?access_token='.$this->token);
        $product_before = json_decode($response->getBody()->getContents());

        $data = [
            'description' => 'patch description_' . rand(0, 100)
        ];

        $responsePatch = $this->client->patch('http://localhost:8000/api/product/1?access_token='.$this->token, [
            'body' => json_encode($data)
        ]);

        $response = $this->client->get('http://localhost:8000/api/product/1?access_token='.$this->token);
        $product_after = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $responsePatch->getStatusCode());
        $this->assertNotEquals($product_before->description, $product_after->description);
    }

    public function testDeleteProductAction()
    {
        $response = $this->client->delete('http://localhost:8000/api/product/1?access_token='.$this->token);

        $this->assertEquals(204, $response->getStatusCode());
    }
}