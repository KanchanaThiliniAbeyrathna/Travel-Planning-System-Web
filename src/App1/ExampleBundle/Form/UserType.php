<?php

namespace App1\ExampleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameInFull')
            ->add('nameInInitials')
            ->add('email' , EmailType::class)
            ->add('username')
            ->add('sex', ChoiceType::class , array(
                'expanded'     => false,
                'choices'      => array('Male' => 'Male', 'Female' => 'Female')
            ))
            /*->add('role',EntityType::class, array(
                'class' => 'App1ExampleBundle:Role',
                'choice_label' => 'role',
                'choice_value' => 'id'

            ))*/
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App1\ExampleBundle\Entity\User'
        ));
    }
}
