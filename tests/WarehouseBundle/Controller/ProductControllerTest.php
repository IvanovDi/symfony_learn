<?php

namespace Tests\WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ProductControllerTest extends WebTestCase
{
    protected function getLoginClient()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('submit-login')->form();

        $form['_username'] = 'dima';
        $form['_password'] = '111';

        $client->submit($form);

        return $client;
    }

    public function testShowProduct()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');


        $link = $crawler->filter('.category-list > a')->eq(2)->link();

        $crawler = $client->click($link);

        $link = $crawler->selectLink('Detail')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEditProduct()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > a')->eq(1)->link();


        $crawler = $client->click($link);

        $link = $crawler->selectLink('Edit')->link();

        $crawler = $client->click($link);

        $title_before = $crawler->filter('#product_form_title')->attr('value');
        $desc_before = $crawler->filter('#product_form_description')->attr('value');
        $model_before = $crawler->filter('#product_form_model')->attr('value');
        $category_before = $crawler->filter('#product_form_category>option[selected="selected"]')->attr('value');

        $category_id = $crawler->filter('#product_form_category>option')->last()->attr('value');

        $form = $crawler->selectButton('Update')->form();


        $form['product_form[title]'] = 'product test edit';
        $form['product_form[description]'] = 'desc product test edit';
        $form['product_form[model]'] = 'edit';
        $form['product_form[category]'] = $category_id;

        $client->submit($form);

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > a')->last()->link();

        $crawler = $client->click($link);

        $link = $crawler->selectLink('Edit')->link();

        $crawler = $client->click($link);

        $title_after = $crawler->filter('#product_form_title')->attr('value');
        $desc_after = $crawler->filter('#product_form_description')->attr('value');
        $model_after = $crawler->filter('#product_form_model')->attr('value');
        $category_after = $crawler->filter('#product_form_category>option[selected="selected"]')->attr('value');

        $this->assertTrue($title_before != $title_after);
        $this->assertTrue($desc_before != $desc_after);
        $this->assertTrue($model_before != $model_after);
        $this->assertTrue($category_before != $category_after);

    }

    public function testDeleteProduct()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > a')->last()->link();

        $crawler = $client->click($link);

        $count_product_before = $crawler->filter('.product-list-actions')->count();

        $link = $crawler->filter('.product-list-actions>a')->last()->link();

        $client->click($link);

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > a')->last()->link();

        $crawler = $client->click($link);

        $count_product_after = $crawler->filter('.product-list-actions')->count();


        $this->assertTrue($count_product_before > $count_product_after);
    }
}