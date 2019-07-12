<?php

namespace App\Form;

use App\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Le formulaire a été généré automatiquement avec la commande php bin/console make:form, mais il faut rajouter les types des champs :
        $builder
            ->add('title',TextType::class )
            ->add('slug', TextType::class)
            ->add('content', TextType::class)
            ->add('date', IntegerType::class)
            ->add('category', TextType::class)
            ->add('featured', ChoiceType::class, array('choices' => array(
                'Yes' => '1',
                'No' => '2')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
