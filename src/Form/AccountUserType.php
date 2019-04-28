<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Locality;
use App\Entity\PostalCode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-group',
                    'placeholder' => ' ',
                ),
            ))
            ->add('firstName', TextType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-group',
                    'placeholder' => ' ',
                ),
            ))
            ->add('address', TextType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => ' ',
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
                    'placeholder' => ' ',
                ),
            ))
            ->add('email', EmailType::class, array(
                'label' => ' ',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => ' ',
                ),
            ))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
