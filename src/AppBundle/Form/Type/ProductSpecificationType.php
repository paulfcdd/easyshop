<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductSpecification;
use AppBundle\Entity\Specification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSpecificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'attr' => [
                    'class' => 'hidden'
                ],
                'label' => false,
                'required' => false,
            ])
            ->add('specification', EntityType::class, [
                'label' => false,
                'class' => Specification::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'group_by' => function($choiceValue, $key, $value) {
                    return $choiceValue->getSpecificationGroup()->getName();
                }
            ])
            ->add('description', TextareaType::class,[
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSpecification::class,
        ]);

        $resolver->setRequired('product');
    }
}