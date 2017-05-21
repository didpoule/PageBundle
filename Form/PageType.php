<?php

namespace didpoule\PageBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('categories', EntityType::class, array(
                'class' => 'didpoule\PageBundle\Entity\Category',
                'choice_label' => 'title',
                'multiple' => true,
                'required' => true
            ))
            ->add('role', ChoiceType::class, array(
                'choices' => $options['user_roles']->getRoles(),
                'required' => false

            ))
            ->add('content', CKEditorType::class)
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('user_roles')
            ->setDefaults(['data_class' => 'didpoule\PageBundle\Entity\Page']);
    }

    public function getBlockPrefix()
    {
        return 'didpoule_pagebundle_page';
    }
}