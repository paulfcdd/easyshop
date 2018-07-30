<?php

namespace AppBundle\Form;

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
            ->add('parent', EntityType::class, [
                'class' => Entity\Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select parent',
                'required' => false
            ])
            ->add('showInMenu', ChoiceType::class, [
                'choices' => [
                    'No' => 0,
                    'Yes' => 1
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('metaTitle', TextType::class, [
                'required' => false
            ])
            ->add('metaKeywords', TextType::class, [
                'required' => false
            ])
            ->add('metaDescription', TextType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entity\Category::class
        ]);
    }
}