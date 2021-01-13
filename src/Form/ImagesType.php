<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('imageFile', VichImageType::class, [
                        'allow_delete' => false,
                        'download_uri' => false,
                        'label' => 'Choisir une Image',
                        'required' => false
                ])
                ->add('category', ChoiceType::class, [
                    'choices' => [
                        'Couleur' => 'couleur',
                        'Noir et Blanc' => 'noir-et-blanc',
                        'Réalisme' => 'realisme',
                        'Calligraphie' => 'calligraphie'
                    ],
                    'label' => 'Catégorie'
                ])
    ;
    }
}