<?php

namespace App\Form;

use App\Entity\Marcador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BuscadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('busqueda', TextType::class, [
                'label' => null,
                'constraints' => [new NotBlank()],
                'attr' => [
                    'placeholder' => 'Buscar',
                    'class' => 'form-control mr-sm-2',
                ],
            ])
            ->add('buscar', SubmitType::class, [
              'label' => 'Buscar',
              'attr' => [
                'class' => 'btn-outline-primary my-2 my-sm-0'
              ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Marcador::class,
        ]);
    }
}
