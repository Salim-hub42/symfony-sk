<?php

namespace App\Form;

use App\Entity\category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('image')
            ->add('date_add')
            ->add('category', EntityType::class, [
                'class' => category::class,
                'choice_label' => 'name',
                'placeholder' => 'choisir une catégorie',
                'label' => 'Catégorie',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
