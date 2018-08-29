<?php

namespace AppBundle\Form;

//use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\AttachmentConsultation;
use AppBundle\Entity\PhotosManualConsultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ConsultationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('animal', EntityType::class , array(
                'class'      => 'AppBundle:Animal',
                'choices'   => $builder->getData()->getClient()->getAnimalsAlive(),
                'multiple'  => false,
                'required'  => false,
           ))
            ->add('debtValueForThisConsultation', TextType::class, array('label' => 'Créance pour la consultation'))
            ->add('notes', TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('photosConsultation', CollectionType::class, array(
                'entry_type' => PhotosConsultationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'prototype' => true,
                'required' => false,
                'auto_initialize' => true,
            ))
            ->add('photosManualConsultation', CollectionType::class, array(
                'entry_type' => PhotosConsultationInputFileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'prototype' => true,
                'required' => false,
                'auto_initialize' => true,
            ))
            ->add('attachmentConsultation', CollectionType::class, array(
                'entry_type' => AttachmentConsultationInputFileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'prototype' => true,
                'required' => false,
                'auto_initialize' => true,
            ))

            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'button btn btn-appoint')))

    ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Consultation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_consultation';
    }



}
