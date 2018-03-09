<?php

namespace WarehouseBundle\Services;

use Doctrine\ORM\EntityManager;
use function GuzzleHttp\Promise\exception_for;
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

    protected function filterInputData($string)
    {
        $string = strip_tags($string);
        $string = htmlspecialchars($string);

        return $string;
    }

    public function createProduct($data)
    {

        $category = $this->entityManager->getRepository(Category::class)->find((int)$data->Request->Product->category);


        $product = new Product();
        $product->setTitle($this->filterInputData($data->Request->Product->title));
        $product->setDescription($this->filterInputData($data->Request->Product->description));
        $product->setModel($this->filterInputData($data->Request->Product->model));
        $product->setCategory($category);

        $this->entityManager->persist($product);

        try{

            $this->entityManager->flush();

        }catch(\Exception $exception) {

            return  $exception->getMessage();

        }


        return ["create" => true];
    }

    public function updateProduct($data)
    {
        $product = $this->entityManager->getRepository('WarehouseBundle:Product')->find((int)$data->Request->Product->id);
        $category = $this->entityManager->getRepository(Category::class)->find((int)$data->Request->Product->category);

        $product->setTitle($this->filterInputData($data->Request->Product->title));
        $product->setDescription($this->filterInputData($data->Request->Product->description));
        $product->setModel($this->filterInputData($data->Request->Product->model));
        $product->setCategory($category);

        try{

            $this->entityManager->flush();

        }catch(\Exception $exception) {

            return  $exception->getMessage();

        }


        return ["update" => true];
    }

    public function listProduct()
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();


        $products_arr = [];

        foreach ($products as $item) {
            $products_arr [$item->getId()] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'description' => $item->getDescription(),
                'model' => $item->getModel(),
                'category' => $item->getCategory() ? $item->getCategory()->getTitle() : "none"
            ];
        }


        return ['listProducts' => $products_arr];
    }

    public function deleteProduct($data)
    {

        try{

            $product = $this->entityManager->getRepository('WarehouseBundle:Product')->find((int)$data->Request->Product->id);
            $this->entityManager->remove($product);
            $this->entityManager->flush();

        }
        catch (\Exception $exception){
            return ['delete' => $exception->getMessage()];
        }

        return ['delete' => true];
    }



    public function createCategory($data)
    {
        $category = new Category();
        $category->setTitle($this->filterInputData($data->RequestCategory->Category->title));
        $category->setDescription($this->filterInputData($data->RequestCategory->Category->description));

        $this->entityManager->persist($category);

        try{

            $this->entityManager->flush();

        }catch(\Exception $exception) {

            return  $exception->getMessage();

        }

        return ['createCategory' => true];
    }

    public function updateCategory($data)
    {
        $category = $this->entityManager->getRepository(Category::class)->find((int)$data->RequestCategory->Category->id);
        $category->setTitle($this->filterInputData($data->RequestCategory->Category->title));
        $category->setDescription($this->filterInputData($data->RequestCategory->Category->description));

        try{

            $this->entityManager->flush();

        }catch(\Exception $exception) {

            return  $exception->getMessage();

        }

        return ['updateCategory' => true];
    }

    public function listCategories()
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        $categories_arr = [];

        foreach ($categories as $item) {
            $categories_arr [$item->getId()] = [
                'title' => $item->getTitle(),
                'description' => $item->getDescription()
            ];
        }

        return ['listCategories' => $categories_arr];
    }

    public function deleteCategory($data)
    {
        try{

            $category = $this->entityManager->getRepository(Category::class)->find((int)$data->RequestCategory->Category->id);
            $this->entityManager->remove($category);
            $this->entityManager->flush();

        }
        catch (\Exception $exception){
            return ['delete' => $exception->getMessage()];
        }

        return ['delete' => true];
    }
}