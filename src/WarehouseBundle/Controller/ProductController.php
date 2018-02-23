<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WarehouseBundle\Entity\Product;
use WarehouseBundle\Form\ProductForm;


class ProductController extends Controller
{

    public function createAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductForm::class, $product,  array(
            'method' => 'POST',
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($product);

            $em->flush();

            return $this->redirectToRoute('warehouse_homepage');
        }


        return $this->render('WarehouseBundle:Warehouse/category:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Product $product, Request $request)
    {
        $form = $this->createForm('WarehouseBundle\Form\ProductForm', $product, array(
            'method' => 'Post'
        ));


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('warehouse_homepage');
        }


        return $this->render('@Warehouse/Warehouse/product/update.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function showAction(Product $product)
    {
        return $this->render('@Warehouse/Warehouse/product/show.html.twig', [
            'product' => $product
        ]);
    }

    public function deleteAction(Product $product, Request $request)
    {
        $form = $this->createDeleteForm($product);

        $form->handleRequest($request);


        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('warehouse_homepage');


    }

    protected function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_product', array('product' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

}
