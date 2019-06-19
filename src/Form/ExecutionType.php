<?php

namespace App\Form;

use App\Entity\Execution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExecutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, array(
                'label' => 'Date estimée de début des travaux',
                'widget' => 'single_text',
                'attr' => array(
                    'class' => ''
                ),
            ))
            ->add('enDate', DateType::class, array(
                'label' => 'Date estimée de fin des travaux',
                'widget' => 'single_text',
                'attr' => array(
                    'class' => ''
                ),
            ))
            ->add('comment', TextareaType::class, array(
                'label' => ' ',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Placez votre commentaire pour le demandeur ...'

                ),
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Execution::class,
        ]);
    }
}
