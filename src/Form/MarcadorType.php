<?php

namespace App\Form;

use App\Entity\Marcador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class MarcadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('url')
            ->add('categoria')
            ->add('favorito')
            ->add('etiqueta', Select2EntityType::class, [
                "multiple" => true,
                "remote_route" => 'app_buscar_etiquetas',
                "class" => "\App\Entity\Etiqueta",
                "primary_key" => 'id',
                "text_property" => 'nombre',
                "minimum_input_length" => 3,
                "delay" => 3,
                "cache" => false,
                "placeholder" => "Selección de etiquetas",
                "allow_add" => [
                    'enabled' => true,
                    "new_tag_text" => '(nuevo)',
                    "tag_separators" => '[","]'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Marcador::class,
        ]);
    }
}
