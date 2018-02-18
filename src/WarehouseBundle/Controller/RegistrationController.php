<?php

namespace WarehouseBundle\Controller;

use WarehouseBundle\Form\UserForm;
use WarehouseBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $passwordEncoder = $this->container->get('security.password_encoder');
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setApiKey("@@@@");
            $em = $this->container->get('doctrine.orm.default_entity_manager');
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render(
            '@Warehouse/Warehouse/registration/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    public function indexAction ()
    {
        return $this->render('WarehouseBundle:Warehouse/registration:welcome.html.twig');
    }
}