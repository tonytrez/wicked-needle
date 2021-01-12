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
                        'imagine_pattern' => 'thumbnail_small',
                        'label' => 'Choisir une Image',
                        'required' => false
                ])
                ->add('category', ChoiceType::class, [
                    'choices' => [
                        'Couleur' => 'color',
                        'Noir et Blanc' => 'blackandwhite',
                        'Réalisme' => 'realism',
                        'Calligraphie' => 'cali'
                    ],
                    'label' => 'Catégorie'
                ])
    ;
    }
}