<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\CategorySelectorType;
use AppBundle\Form\Type\MetaTagType;
use AppBundle\Form\Type\SelectorType;
use AppBundle\Form\Type\WysiwygEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity as Entity;

class Product extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'translit-input'
                ]
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'translit-output'
                ]
            ])
            ->add('description', WysiwygEditorType::class, [])
            ->add('model', TextType::class)
            ->add('price', IntegerType::class)
            ->add('quantity', IntegerType::class)
            ->add('minimumQuantity', IntegerType::class, [])
            ->add('status', SelectorType::class, [
                'choices' => array_flip(Entity\Product::STATUSES)
            ])
            ->add('category', CategorySelectorType::class, [
                'placeholder' => 'Select category',
            ])
            ->add('manufacturer', EntityType::class, [
                'class' => Entity\Manufacturer::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false
            ])
            ->add('metaTitle', MetaTagType::class, [])
            ->add('metaKeywords', MetaTagType::class, [])
            ->add('metaDescription', MetaTagType::class, [])
            ->add('featured', SelectorType::class, [
                'choices' => array_flip(Entity\Product::FEATURED)
            ])
            ->add('specifications', CollectionType::class, [
                'entry_type' => Type\ProductSpecificationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'product' => $builder->getData(),
                    'label' => false
                ],
                'attr' => [
                    'novalidate'
                ],
            ])
            ->add('save', SubmitType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entity\Product::class
        ]);
    }
}