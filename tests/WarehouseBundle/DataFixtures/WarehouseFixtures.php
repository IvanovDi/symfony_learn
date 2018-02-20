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

        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setTitle('category_' . $i);
            $category->setDescription('desc_' . $i);
            $manager->persist($category);
        }

        $manager->flush();

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
        $user->setPassword('$2y$13$FXmjRVAOgOoQzpCT8c.zCuXU3ZqsHXHMMmx4cOEmXsI63p.X9ncWS');
        $user->setApiKey('@apikey@');
        $user->setRoles('ROLE_ADMIN');
        $manager->persist($user);


        $manager->flush();
    }
}