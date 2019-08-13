<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Provider;
use App\Entity\TempUser;
use App\Form\RegistrationTempType;
use App\Form\RegistrationType;
use App\Form\RegistrationUserType;
use App\Utils\Mailer;
use Doctrine\Common\Persistence\ObjectManager;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet de se connecter
     *
     * @Route("/login", name="account_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error= $utils->getLastAuthenticationError();
        $username= $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);

    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){

    }

    /**
     * @Route("/account_temp", name="register_temp")
     *
     */
    public function registerTemp(Request $request, Mailer $register_temp){

        $tempUser = new TempUser();

        $form = $this->createForm(RegistrationTempType::class, $tempUser);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $tempUser->setToken($token);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tempUser);
            $em->flush();
            $register_temp->registerMail($tempUser);

            $this->addFlash(
                'success',
                'Votre demande d\'inscription a bien été introduite, un email de confirmation vous a été envoyé par email !'
            );
            return $this->redirectToRoute('home');
        }

        return $this->render('account/registration_temp.html.twig', [
            'registrationTemp' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/{type}/{token}", name="account_confirm")
     */
    public function registerConfirm(Request $request, $token, $type, UserPasswordEncoderInterface $encoder){

        $repository = $this->getDoctrine()->getRepository(TempUser::class);
        $repository->findByToken(['token' => $token]);

        if($type === 'customer'){
            $customer = new Customer();

            $form = $this->createForm(RegistrationUserType::class, $customer);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $password = $encoder->encodePassword($customer, $customer->getPassword());
                $customer->setPassword($password);

                $customer->setRegistration(new \DateTime());
                $customer->setBanished(0);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($customer);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
                );
                return $this->redirectToRoute('account_login');
            }
            return $this->render('account/registration_user.html.twig',[
                'form' => $form->createView()
            ]);
        }

        else if($type === 'provider'){
            $provider = new Provider();

            $form = $this->createForm(RegistrationType::class, $provider);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $encoder->encodePassword($provider, $provider->getPassword());
                $provider->setPassword($password);

                $provider->setRegistration(new \DateTime());
                $provider->setBanished(0);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($provider);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre compte Pro a bien été créé ! Vous pouvez maintenant vous connecter !'
                );
                return $this->redirectToRoute('account_login');
            }
            return $this->render('account/registration.html.twig',[
                'form' => $form->createView()
            ]);
        }

    }

    /**
     * Permet d'afficher le formulaire d'inscription Pro
     *
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $provider = new Provider();

        $form = $this->createForm(RegistrationType::class, $provider);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $password = $encoder->encodePassword($provider, $provider->getPassword());
            $provider->setPassword($password);

            $provider->setRegistration(new \DateTime());
            $provider->setBanished(0);

            $manager->persist($provider);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte Pro a bien été créé ! Vous pouvez maintenant vous connecter !'
            );
           // return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * Permet d'afficher le formulaire d'inscription des utilisateurs
     *
     * @Route("/register_user", name="account_register_user")
     *
     * return Response
     *
     */
    public function registerUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $customer = new Customer();

        $form = $this->createForm(RegistrationUserType::class, $customer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $password = $encoder->encodePassword($customer, $customer->getPassword());
            $customer->setPassword($password);

            $customer->setRegistration(new \DateTime());
            $customer->setBanished(0);

            $manager->persist($customer);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
            );
        }


        return $this->render('account/registration_user.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
