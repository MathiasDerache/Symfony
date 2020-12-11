<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SubscribeController extends AbstractController
{

    /**
     * @Route("/subscribe", name="subscribe")
     */
    public function createUser(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) : Response {
        
        try{
            $user = new User();

            $form = $this->createForm(UserType::class, $user);
    
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();
                return $this->redirect("/");
            }
        }
        catch(UserServiceException $e){
            return $this->render('subscribe/subscribe.html.twig', [
                "error" => $e->getCode(). ": " . $e->getMessage()
            ]);
        }
        return $this->render('subscribe/subscribe.html.twig', [
            'form' => $form->createView(),
            'error' => NULL
        ]);
    }
}
