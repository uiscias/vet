<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AnimalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('species', ChoiceType::class, array(
                    'choices' => array(
                        'Chat' => 'chat',
                        'Chien' => 'chien',
                        'Furet'   => 'furet',
                        'Autre' => 'autre'),
                    'preferred_choices' => array('Chien', 'chien'),
                    'label' => 'Espèce'))
                ->add('year', TextType::class, array('label' => 'Année de naissance'))
                ->add('name', TextType::class, array('label' => 'Nom'))
                ->add('notes', TextareaType::class, array('label' => 'Notes'))
                ->add('isAlive', ChoiceType::class, array(
                    'choices' => array(
                        'Oui' => true,
                        'Non' => false),
                    'preferred_choices' => array(true, 'Oui'),
                    'label' => 'Est vivant'))
                ->add('vaccination', TextareaType::class, array('label' => 'Vaccination'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Animal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_animal';
    }


}
