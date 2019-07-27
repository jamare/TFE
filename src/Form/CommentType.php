<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, array(
                'label' => 'Note sur 5',
                'attr' => [
                        'min' => 0,
                        'max' => 5,
                        'step' =>1,
                        'placeholder' => 'Veuillez indiquer un nombre de 0 à 5'
                ]

            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Votre avis ',
                'attr' => array(
                    'placeholder' => 'Laissez nous votre commentaire sur ce prestataire'
                )
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
