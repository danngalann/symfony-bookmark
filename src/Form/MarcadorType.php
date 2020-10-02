<?php

namespace App\Form;

use App\Entity\Categoria;
use App\Entity\Marcador;
use App\Repository\CategoriaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class MarcadorType extends AbstractType
{

    private $categoriaRepository;

    public function __construct(CategoriaRepository $categoriaRepository)
    {
        $this->categoriaRepository = $categoriaRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('url')
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'choices' => $this->categoriaRepository->findAllByUser()
            ])
            ->add('favorito')
            ->add('etiquetas', Select2EntityType::class, [
                "multiple" => true,
                "remote_route" => 'etiqueta_index',
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
                ],
                'mapped' => false
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
