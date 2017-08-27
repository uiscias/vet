<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array('label' => 'Prénom'))
                ->add('lastName', TextType::class, array('label' => 'Nom de famille'))
                ->add('address', TextType::class, array('label' => 'Adresse'))
                ->add('cP', TextType::class, array('label' => 'Code Postal'))
                ->add('city', TextType::class, array('label' => 'Ville'))
                ->add('phone', TextType::class, array('label' => 'Téléphone'))
                ->add('phone2', TextType::class, array('label' => 'Téléphone 2', 'required' => false))
                ->add('eMail', EmailType::class, ['attr' => ['class' => 'input-lg']],array('label' => 'E-Mail'))
                ->add('contactPreferences', ChoiceType::class, array(
                    'choices' => array(
                        'eMail' => 'eMail',
                        'Telephone 1' => 'Phone1',
                        'Telephone 2'   => 'Phone2',
                        'Telephone 1 et eMail' => 'Phone1AndEMail',
                        'Tout' =>   'All'),
                    'preferred_choices' => array('eMail', 'eMail'),
                    'label' => 'Préférences de contact'))
                ->add('animals', CollectionType::class, array(
                    'entry_type' => AnimalType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'delete_empty' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'required' => false,
                    'auto_initialize' => true,
                    'label' => 'Animal'
                ))
                ->add('save', SubmitType::class, array(
                    'attr' => array('class' => 'button btn-appoint')))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_client';
    }


}
