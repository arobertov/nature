<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Article;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',new TextType(),array(
                'attr'=>array('class'=>'form-control','placeholder'=>'Post Title')
            ))
            ->add('file',new FileType(),array(
                'attr'=>array('class'=>'form-control')
            ))
            ->add('category',new TextType(),array(
                'attr'=>array('class'=>'form-control','placeholder'=>'Category article')
            ))
            ->add('content', new TextareaType(),array(
                'attr'=>array('class'=>'form-control','rows'=>'8',),
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>'AppBundle\Entity\Article',));
    }

    public function getName()
    {
        return 'app_bundle_article_type';
    }
}
