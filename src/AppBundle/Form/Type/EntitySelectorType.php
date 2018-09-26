<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntitySelectorType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add($options['field_name'], EntityType::class, [
            'class' => $options['data_class'],
            'choice_label' => $options['choice_label'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => false,
            'multiple' => false
        ]);

        $resolver->setRequired('field_name');
        $resolver->setRequired('data_class');
        $resolver->setRequired('choice_label');

    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return EntityType::class;
    }
}