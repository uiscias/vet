<?php

namespace AppBundle\Form;

use Gedmo\Mapping\Driver\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PhotosConsultationInputFileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('titre', TextType::class, array('label' => 'Titre de la Photo manuelle'))
//            ->add('description')
            ->add('file', FileType::class, array('label' => 'Photo consultation manuelle'))
//            ->add('datafile',HiddenType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PhotosManualConsultation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_photosmanualconsultation';
    }


}
