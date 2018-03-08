<?php

namespace WarehouseBundle\Services;

use Doctrine\ORM\EntityManager;
use function MongoDB\BSON\toJSON;
use WarehouseBundle\Entity\Category;
use WarehouseBundle\Entity\Product;

class SoapService
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createProduct($data)
    {

        $category = $this->entityManager->getRepository(Category::class)->find($data->Product->category);


        $product = new Product();
        $product->setTitle($data->Product->title);
        $product->setDescription($data->Product->description);
        $product->setModel($data->Product->model);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return array("create" => $category->getId());
    }

    public function updateProduct($data)
    {
        $product = $this->entityManager->getRepository('WarehouseBundle:Product')->find($data->Product->id);

        return array("update" => $product->getTitle());
    }
}