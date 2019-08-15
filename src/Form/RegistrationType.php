<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Locality;
use App\Entity\PostalCode;
use App\Entity\Provider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                ),
            ))
            ->add('address', TextType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Adresse',
                ),
            ))
            ->add('PostalCode', EntityType::class, array(
                'label' => ' ',
                'class' => PostalCode::class,
                'placeholder' => 'Code Postal',
                'empty_data' => null,
                'attr' => array('class' => 'form-control'),
            ))
            ->add('locality', EntityType::class, array(
                'label' => ' ',
                'class' => Locality::class,
                'placeholder' => 'Localité',
                'empty_data' => null,
                'attr' => array('class' => 'form-control'),
            ))
            ->add('phone', TelType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone'
                ),
            ))
            ->add('email', EmailType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                ),
            ))
            ->add('web', UrlType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'site internet',
                )
            ))
            ->add('tva', TextType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Numéro de TVA',
                )
            ))
            ->add('Service', EntityType::class, array(
                'label' => ' ',
                'class' => Category::class,
                'placeholder' => 'Catégorie(s) de service',
                'mapped' => false,
                'attr' => array('class' =>'form-control'),
            ))
            ->add('password', PasswordType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe',
                ),
            ))
            ->add('passwordConfirm', PasswordType::class,array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Veuillez confirmer votre mot de passe ...'
                )
            ))
            ->add('logoFilename', FileType::class, [
                'label' => "Logo",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '3M'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}
