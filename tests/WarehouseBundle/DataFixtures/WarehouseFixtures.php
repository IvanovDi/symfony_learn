<?php

namespace tests\WarehouseBundle\DataFixture;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use WarehouseBundle\Entity\Category;
use WarehouseBundle\Entity\Product;
use WarehouseBundle\Entity\User;

class WarehouseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $sql = "INSERT INTO client (id, random_id, redirect_uris, secret, allowed_grant_types) VALUES (1, '4eyp1k2a6gw0kc4ocgwc4s00kgwkk0kw0oco4ggokswg0o4cco', 'a:1:{i:0;s:11:\"CLIENT_HOST\";}', '4v3bk52yaqyokskckk0sgwogwkwc8o4csgoc0occcwgkowgsgo', 'a:5:{i:0;s:18:\"authorization_code\";i:1;s:8:\"password\";i:2;s:13:\"refresh_token\";i:3;s:5:\"token\";i:4;s:18:\"client_credentials\";}');";

        $manager->getConnection()->exec( $sql );

        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setTitle('category_' . $i);
            $category->setDescription('desc_' . $i);
            $manager->persist($category);
        }


        for($i = 0; $i < 20; $i++) {
            if($i == 1) {continue;}
            $product = new Product();
            $product->setTitle('product_' . $i);
            $product->setDescription('desc_' . $i);
            $product->setCategory($manager->getRepository('WarehouseBundle:Category')->findOneByTitle('category_' . rand(2, 18)));
            $manager->persist($product);
        }

        $product = new Product();
        $product->setTitle('product_test');
        $product->setDescription('desc_test');
        $product->setCategory($manager->getRepository('WarehouseBundle:Category')->findOneByTitle('category_1'));
        $manager->persist($product);



        $user = new User();
        $user->setUsername('dima');
        $user->setEmail('dima@gmail.com');
        $user->setPassword('$2y$13$yVu2W/0UdR9OTRYxp.d5rudFWPNXq8Lq3pjQmOzotssef0yHcIHGC');
        $user->setApiKey('@apikey@');
        $user->setRoles('ROLE_ADMIN');
        $manager->persist($user);


        $manager->flush();
    }
}