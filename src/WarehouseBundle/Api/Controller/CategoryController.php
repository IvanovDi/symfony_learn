<?php

namespace WarehouseBundle\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;

class CategoryController extends FOSRestController
{
    public function getApiCategoriesActions()
    {
        $articles = $this->getDoctrine()->getRepository('WarehouseBundle:Category')->getAllCategories();
        dump($articles);
        return new JsonResponse($articles);
    }
}