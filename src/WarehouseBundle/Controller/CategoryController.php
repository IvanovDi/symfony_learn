<?php

namespace WarehouseBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarehouseBundle\Entity\Category;
use WarehouseBundle\Form\CategoryForm;
use WarehouseBundle\Repository\WarehouseRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CategoryController extends Controller
{
    public function showProductsInCategoryAction(Category $category)
    {   $products = $category->getProducts();

        return $this->render('@Warehouse/Warehouse/category/show_product_in_category.html.twig', [
            'category' => $category,
            'products' => $products
        ]);

    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('WarehouseBundle:Category')->getAllCategories();

        return $this->render('WarehouseBundle:Warehouse/category:index.html.twig', [
            'category' => $category
        ]);
    }

    public function createAction (Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryForm::class, $category,  array(
        'method' => 'POST',
    ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);

            $em->flush();

            return $this->redirectToRoute('warehouse_homepage');
        }


        return $this->render('WarehouseBundle:Warehouse/category:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Category $category, Request $request)
    {

        $form = $this->createForm('WarehouseBundle\Form\CategoryForm', $category, array(
            'method' => 'Post'
        ));


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form->getData()->setUpdated(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('warehouse_homepage');
        }


        return $this->render('WarehouseBundle:Warehouse/category:update.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function showAction(Category $category)
    {
        return $this->render('WarehouseBundle:Warehouse/category:show.html.twig', [
            'category' => $category
        ]);
    }


    public function deleteAction(Category $category, Request $request)
    {
        $form = $this->createDeleteForm($category);

        $form->handleRequest($request);

            if(count($category->getProducts()) > 0) {

                $this->addFlash('notice', 'while there are products in the category you delete a category, it is impossible!');

            } else {

                $em = $this->getDoctrine()->getManager();
                $em->remove($category);
                $em->flush();
            }


        return $this->redirectToRoute('warehouse_homepage');


    }

    protected function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_category', array('category' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
