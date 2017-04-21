<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', new TextareaType(),array(
                'attr'=>array('class'=>'form-control','rows'=>'6',),
            ))
            ->add('submit',new SubmitType(),array('attr'=>array('class'=>'btn btn-primary'),
                'label'=>'Create comment'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>'AppBundle\Entity\Comment',));
    }

    public function getName()
    {
        return 'app_bundle_comment_type';
    }
}
