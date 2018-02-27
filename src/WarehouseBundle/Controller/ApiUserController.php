<?php

namespace WarehouseBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarehouseBundle\Entity\User;
use WarehouseBundle\Form\UserForm;

class ApiUserController extends FOSRestController
{
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('WarehouseBundle:User')->getAllUsers();

        $view = $this->view($users, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @param User $user
     * @return Response
     *
     * @Rest\Route("/users/{user}")
     */
    public function getUserAction(User $user)
    {
        $view = $this->view($user, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    public function postUsersAction(Request $request)
    {
        $form = $this->createForm(UserForm::class, new User(), ['csrf_protection' => false, 'method' => $request->getMethod()]);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);


        $validator = $this->get('validator');
        $errors = $validator->validate($form->getData());


        if (count($errors) == 0 && $form->isValid()) {
            $user = $form->getData();

            $passwordEncoder = $this->container->get('security.password_encoder');
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setApiKey('@@@@');

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);

            $em->flush();

            $view = $this->view($user, 201)
                ->setFormat('json');

            return $this->handleView($view);
        } else {

            $view = $this->view($errors, 400)
                ->setFormat('json');

            return $this->handleView($view);

        }
    }

    public function putUsersAction(User $user, Request $request)
    {
        $form = $this->createForm(UserForm::class, $user, ['csrf_protection' => false, 'method' => $request->getMethod()]);

        return $this->processForm($request, $form, $user);

    }


    public function patchUsersAction(User $user, Request $request)
    {
        $form = $this->createForm(UserForm::class, $user, ['csrf_protection' => false, 'method' => $request->getMethod()]);

        return $this->processForm($request, $form, $user);
    }

    public function deleteUsersAction(User $user)
    {
        if($user) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return new Response(null, 204);
    }
    //todo проверить как обновляются данные
    protected function processForm(Request $request, FormInterface $form, User $user)
    {
        $data = json_decode($request->getContent(), true);

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);

        $validator = $this->get('validator');
        $errors = $validator->validate($form->getData());

        if (count($errors) == 0 && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $view = $this->view($user, 204)
                ->setFormat('json');

            return $this->handleView($view);

        } else {

            $view = $this->view($errors, 400)
                ->setFormat('json');

            return $this->handleView($view);

        }
    }
}