<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ReminderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
        ->add('enabled', ChoiceType::class, array(
            'choices' => array(
                'Oui' => true,
                'Non' => false),
            'preferred_choices' => array(true, 'Oui'),
            'label' => 'Actif'))
        ->add('reminderDateTime', DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
        ->add('media', ChoiceType::class, array(
            'choices' => array(
                'eMail' => 'eMail',
                'Telephone 1' => 'Phone1',
                'Telephone 2'   => 'Phone2',
                'Telephone 1 et eMail' => 'Phone1AndEMail',
                'Tout' =>   'ALL'),
            'preferred_choices' => array('eMail', 'eMail'),
            'label' => 'Préférences de contact'))
        ->add('note')
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
            'data_class' => 'AppBundle\Entity\Reminder'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reminder';
    }


}
