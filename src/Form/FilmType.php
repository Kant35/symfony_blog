<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dateSortie', DateType::class, [
                "widget" => "single_text"
            ])
            ->add('afficheFile', VichImageType::class, [
                "label" => "Affiche",
                "required" => false,
                "allow_delete" => false,
                "download_uri" => false,
                "image_uri" => false
            ])
            ->add('synopsis')
            ->add('acteurs', EntityType::class, [
                "class" => Artiste::class,
                "multiple" => true,
                "expanded" => true
            ])
            ->add('realisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
