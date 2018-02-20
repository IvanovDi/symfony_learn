<?php

namespace WarehouseBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use WarehouseBundle\Entity\Product;
use WarehouseBundle\Form\ProductForm;
use Symfony\Component\HttpFoundation\Response;

class ApiProductController extends FOSRestController
{
    public function getProductsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('WarehouseBundle:Product')->getAllProducts();

        $view = $this->view($products, 200);
        return $this->handleView($view);
    }

    public function getProductAction(Product $product)
    {

        $form = $this->createForm(ProductForm::class, $product, [
            'csrf_protection' => false,
        ]);

        $view = $this->view($product, 200);
        return $this->handleView($view);
    }

    public function postProductAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->denyAccessUnlessGranted('create', $user);

        $form = $this->createForm(ProductForm::class, new Product(), [
            'csrf_protection' => false,
            'method' => $request->getMethod()
        ]);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        $validator = $this->get('validator');
        $errors = $validator->validate($form->getData());

        if (count($errors) == 0 && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($product);

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

    public function putProductAction(Product $product, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->denyAccessUnlessGranted('edit', $user);

        $form = $this->createForm(ProductForm::class, $product, ['csrf_protection' => false, 'method' => $request->getMethod()]);

        return $this->processForm($request, $form);

    }


    public function patchProductAction(Product $product, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->denyAccessUnlessGranted('edit', $user);

        $form = $this->createForm(ProductForm::class, $product, ['csrf_protection' => false, 'method' => $request->getMethod()]);

        return $this->processForm($request, $form);
    }

    public function deleteProductAction(Product $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->denyAccessUnlessGranted('delete', $user);

        if($product) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
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