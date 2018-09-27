<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\CategorySelectorType;
use AppBundle\Form\Type\MetaTagType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity as Entity;

class Category extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [])
            ->add('parent', CategorySelectorType::class, [
                'placeholder' => 'Select parent',
                'required' => false
            ])
            ->add('showInMenu', ChoiceType::class, [
                'choices' => [
                    'No' => 0,
                    'Yes' => 1
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('metaTitle', MetaTagType::class, [])
            ->add('metaKeywords', MetaTagType::class, [])
            ->add('metaDescription', MetaTagType::class, [])
            ->add('save', SubmitType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entity\Category::class
        ]);
    }
}