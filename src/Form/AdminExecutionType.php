<?php

namespace App\Form;

use App\Entity\Demand;
use App\Entity\Execution;
use App\Entity\Provider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminExecutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text'
            ])

            ->add('enDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('comment')
            ->add('provider', EntityType::class,[
                'class' => Provider::class,
                'choice_label' => 'name'
            ])
            ->add('Demand', EntityType::class,[
                'class' => Demand:: class,
                'choice_label' => 'title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Execution::class,
        ]);
    }
}
