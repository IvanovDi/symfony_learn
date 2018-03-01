<?php

namespace tests\WarehouseBundle\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use WarehouseBundle\Entity\Category;
use WarehouseBundle\Entity\Product;

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


        for($i = 0; $i < 20; $i++) {
            if($i == 1) {continue;}
            $product = new Product();
            $product->setTitle('product_' . $i);
            $product->setDescription('desc_' . $i);
            $product->setCategory($manager->getRepository('WarehouseBundle:Category')->findOneByTitle('category_0'));
            $manager->persist($product);
        }

        $product = new Product();
        $product->setTitle('product_test');
        $product->setDescription('desc_test');
        $product->setCategory($manager->getRepository('WarehouseBundle:Category')->findOneByTitle('category_1'));
        $manager->persist($product);



        $manager->flush();
    }
}