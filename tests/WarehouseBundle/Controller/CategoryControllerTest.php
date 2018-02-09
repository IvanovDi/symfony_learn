<?php

namespace Tests\WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CategoryControllerTest extends WebTestCase
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

    public function testShowCategory()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');


        $link = $crawler->filter('.category-list > a')->first()->link();

        $crawler = $client->click($link);

        $this->assertEquals('Category category_0',  $crawler->filter('h1')->first()->text());

        $count_products_in_category = $crawler->filter('.product-list > a')->count();

        return $count_products_in_category;

    }

    public function testDeleteCategory()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > span > a')->eq(2)->link();

        $count_category_before = $crawler->filter('.category-list > a')->count();

        $crawler = $client->click($link);

        $crawler = $client->request('GET', '/home');

        $count_category_after = $crawler->filter('.category-list > a')->count();
        file_put_contents('/home/dmitrovskiy/Documents/file.txt', print_r([$count_category_before, $count_category_after], true));

        $this->assertTrue($count_category_before > $count_category_after);

    }

    public function testCategoryIndexPage()
    {

        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');

        $this->assertTrue($crawler->filter('.category-list>a')->count() > 0);

    }

    public function testAddCategory()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');

        $count_category_before = $crawler->filter('.category-list > a')->count();

        $link = $crawler->selectLink('Add Category')->link();


        $crawler = $client->click($link);

        $form = $crawler->selectButton('Create')->form();

        $form['category_form[title]'] = 'test_category';
        $form['category_form[description]'] = 'test desc category';

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/home');
        $count_category_after = $crawler->filter('.category-list > a')->count();

        $this->assertTrue($count_category_before < $count_category_after);

        return $client;
    }

    /**
     * @depends testShowCategory
     * @param $start_count_product_in_select_category
     */
    public function testAddProduct ($start_count_product_in_select_category)
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');


        $link = $crawler->selectLink('Add Product')->link();

        $first_category_id = explode('/', $crawler->filter('.category-list > a')->first()->attr('href'));

        $first_category_id = array_pop($first_category_id);

        $crawler = $client->click($link);

        $form = $crawler->selectButton('Create')->form();

        $form['product_form[title]'] = 'product test';
        $form['product_form[description]'] = 'desc product test';
        $form['product_form[model]'] = '777';
        $form['product_form[category]'] = $first_category_id;

        $client->submit($form);

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > a')->first()->link();

        $crawler = $client->click($link);

        $after_add_count_product_in_category = $crawler->filter('.product-list > a')->count();

        $this->assertTrue($after_add_count_product_in_category > $start_count_product_in_select_category);

    }

    public function testEditCategory()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > span > a')->eq(1)->link();


        $crawler = $client->click($link);

        $title_before = $crawler->filter('#category_form_title')->attr('value');
        $desc_before = $crawler->filter('#category_form_description')->attr('value');

        $form = $crawler->selectButton('Update')->form();

        $form['category_form[title]'] = 'new title';
        $form['category_form[description]'] = 'new desc';

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('.category-list > span > a')->eq(1)->link();


        $crawler = $client->click($link);

        $title_after = $crawler->filter('#category_form_title')->attr('value');
        $desc_after = $crawler->filter('#category_form_description')->attr('value');

        $this->assertTrue($title_before != $title_after);
        $this->assertTrue($desc_before != $desc_after);

    }

}
