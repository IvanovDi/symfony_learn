<?php

namespace Tests\WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SecurityControllerTest extends WebTestCase
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

    public function testShowWelcomePage()
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

    public function testSignOut()
    {
        $client = $this->getLoginClient();

        $crawler = $client->request('GET', '/home');

        $link = $crawler->filter('#sign_out')->link();

        $client->click($link);

        $crawler = $client->request('GET', '/home');

        $this->assertContains('/login', $crawler->text());

        return $client;

    }

}