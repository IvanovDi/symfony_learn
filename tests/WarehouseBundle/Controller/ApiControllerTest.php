<?php

namespace tests\WarehouseBundle\Controller;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    protected $client;

    protected $token;


    public function __construct()
    {
        parent::__construct();

        $this->client = $this->createClient();

    }


}