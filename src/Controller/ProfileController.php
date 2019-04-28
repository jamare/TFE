<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Entity\Provider;
use App\Form\AccountType;
use App\Form\AccountUserType;
use App\Form\PasswordUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * Permet d'afficher et de traiter les formulaires de modification de profil : Customer & Provider
     *
     * @Route("/profile", name="account_profile")
     *
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();


        if($user instanceof Provider){
            $form = $this->createForm(AccountType::class, $user);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Les données du profil ont été enregistrées avec succès!"
                );
            }

            return $this->render('profile/profile.html.twig',[
                'form' => $form->createView()
            ]);
        }
        elseif ($user instanceof Customer){

            $form = $this->createForm(AccountUserType::class, $user);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Les données du profil ont été enregistrées avec succès!"
                );
            }

            return $this->render('profile/profile_user.html.twig',[
                'form' => $form->createView()
            ]);

        }


    }

    /**
     * Fonction de modification de mot de pass
     *
     * @Route("/account/password-update", name="account_password")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager){
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // vérification du mot de passe actuel ( oldpassword )
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())){
                $form->get('oldPassword')->addError(new FormError("Le mot de passes que vous avez tapé n'est pas votre mot de passe actuel !"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $password = $encoder->encodePassword($user, $newPassword);

                $user->setPassword($password);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
