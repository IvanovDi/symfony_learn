<?php

namespace WarehouseBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarehouseBundle\Entity\Category;
use WarehouseBundle\Form\CategoryForm;



class ApiCategoryController extends FOSRestController
{
    public function getCategoriesAction()
    {
        $data = $this->getDoctrine()->getRepository('WarehouseBundle:Category')->getAllCategories();

        $articles = [];

        foreach ($data as $key => $item) {

            $articles [$key] = [
                'title' => $item->getTitle(),
                'description' => $item->getDescription()
             ];

        }

        $view = $this->view($articles)
        ->setFormat('json');

        return $this->handleView($view);
    }


    public function getCategoryAction($category)
    {
        $category = $this->getDoctrine()->getRepository('WarehouseBundle:Category')->find($category);

        $view = $this->view($category)
            ->setFormat('json');

        return $this->handleView($view);
    }

    public function postCategoryAction(Request $request)
    {

        $form = $this->createForm(CategoryForm::class, new Category(), ['csrf_protection' => false, 'method' => $request->getMethod()]);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        $validator = $this->get('validator');
        $errors = $validator->validate($form->getData());

        if (!$errors && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);

            $em->flush();

            $response = new Response();
            $response->setStatusCode(200);

            return $response;

        } else {

            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent($errors);

            return $response;

        }

    }

    public function putCategoryAction($category, Request $request)
    {
        $category = $this->getDoctrine()->getRepository('WarehouseBundle:Category')->find($category);


        $response = new Response();
        $response->setStatusCode(200);

        return $response;
    }
}