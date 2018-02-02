<?php

namespace WarehouseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WarehouseBundle\Entity\Category;
use WarehouseBundle\Entity\Product;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('model', TextType::class)
            ->add('category', EntityType::class, [
                'class' => 'WarehouseBundle\Entity\Category',
                'choice_label' => 'title',
            ])
            ->add('created', DateType::class, array('widget' => 'single_text'))
            ->add('updated', DateType::class, array('widget' => 'single_text'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }

}