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
        $builder
                ->add('species', ChoiceType::class, array(
                    'choices' => array(
                        'Chat' => 'chat',
                        'Chien' => 'chien',
                        'Lapin'   => 'lapin',
                        'Furet'   => 'furet',
                        'Autre' => 'autre'),
                    'preferred_choices' => array('Chien', 'chien'),
                    'label' => 'Espèce'))
                ->add('gender', ChoiceType::class, array(
                    'choices' => array(
                        'Male' => 'Male',
                        'Femelle' => 'Femelle'),
                    'label' => 'Sexe'))
                ->add('year', TextType::class, array('label' => 'Année de naissance'))
                ->add('name', TextType::class, array('label' => 'Nom', 'required' => false))
                ->add('alerte', TextType::class, array('label' => 'Alerte', 'required' => false))
                ->add('identificationNumber', TextType::class, array('label' => 'Numéro d\'identification', 'required' => false))
                ->add('notes', TextareaType::class, array('label' => 'Notes', 'required' => false))
                ->add('isAlive', ChoiceType::class, array(
                    'choices' => array(
                        'Oui' => true,
                        'Non' => false),
                    'preferred_choices' => array(true, 'Oui'),
                    'label' => 'Est vivant'))
                ->add('isGoingOutside', ChoiceType::class, array(
                    'choices' => array(
                        'Oui' => true,
                        'Non' => false),
                    'preferred_choices' => array(true, 'Oui'),
                    'label' => 'Va à l\'extérieur'))
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
