<?php

namespace WarehouseBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormInterface;
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

        $view = $this->view($data, 200)
        ->setFormat('json');

        return $this->handleView($view);
    }


    public function getCategoryAction(Category $category)
    {
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

        if (count($errors) == 0 && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);

            $em->flush();

            $response = new Response();
            $response->setStatusCode(201);

            return $response;

        } else {

            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent($errors);

            return $response;

        }

    }

    public function putCategoryAction(Category $category, Request $request)
    {
        $form = $this->createForm(CategoryForm::class, $category, ['csrf_protection' => false, 'method' => $request->getMethod()]);

        return $this->processForm($request, $form);

    }


    public function patchCategoryAction(Category $category, Request $request)
    {
        $form = $this->createForm(CategoryForm::class, $category, ['csrf_protection' => false, 'method' => $request->getMethod()]);

        return $this->processForm($request, $form);
    }

    public function deleteCategoryAction(Category $category)
    {
        if($category) {
             $em = $this->getDoctrine()->getManager();
             $em->remove($category);
             $em->flush();
        }

        return new Response(null, 204);
    }

    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);

        $validator = $this->get('validator');
        $errors = $validator->validate($form->getData());

        if (count($errors) == 0 && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

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
}