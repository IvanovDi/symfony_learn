<?php

namespace WarehouseBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CategoryControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testWelcomePage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('a')->count() > 0);
    }

    public function testSignInLink()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $link = $crawler->filter('a')->first();

        $crawler = $client->click($link->link());

        $this->assertEquals('login', $crawler->filter('button')->first()->text());
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertEquals(1, $crawler->filter('h1:contains("Login")')->count());

        $form = $crawler->selectButton('submit-login')->form();


        $form['_username'] = 'dima';
        $form['_password'] = '111';

        $crawler = $client->submit($form);

        $this->assertContains('/home', $crawler->text());

    }

    public function testLoginForBadDate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertEquals(1, $crawler->filter('h1:contains("Login")')->count());

        $form = $crawler->selectButton('submit-login')->form();


        $form['_username'] = '@@@';
        $form['_password'] = '@@@';

        $crawler = $client->submit($form);

        $this->assertContains('/login', $crawler->text());

        return $crawler;
    }

    public function testCategoryIndexPage()
    {

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');


        $form = $crawler->selectButton('submit-login')->form();


        $form['_username'] = 'dima';
        $form['_password'] = '111';

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/home');

        $this->assertTrue($crawler->filter('.category-list>a')->count() > 0);

    }

    public function testSignOut()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');


        $form = $crawler->selectButton('submit-login')->form();


        $form['_username'] = 'dima';
        $form['_password'] = '111';

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('#sign_out')->link();



        $client->click($link);

        $crawler = $client->request('GET', '/home');


        $this->assertContains('/login', $crawler->text());

    }

    public function testAddCategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');


        $form = $crawler->selectButton('submit-login')->form();


        $form['_username'] = 'dima';
        $form['_password'] = '111';

        $crawler = $client->submit($form);

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

        file_put_contents('/home/dima/Documents/file.txt', print_r([$count_category_before, $count_category_after], true));

        $this->assertTrue($count_category_before < $count_category_after);
    }

}
