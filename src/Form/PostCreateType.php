<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Region;
use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

class PostCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre : '
            ])
            ->add('text', TextareaType::class,[
                'label' => 'Déscription : '
            ])
            ->add('region', EntityType::class,[
                'class' => Region::class,
                'choice_label'  => 'name',
                'label' => 'Région',
                'placeholder'   => 'Région'
            ])
            ->add('department', ChoiceType::class,[
                'placeholder'   => 'Départements (choisir une région)'
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Enregistrer'
            ])
        ;
        $formModifier = function(FormInterface $form, Region $region = null) {
            $departments = null == $region ? [] : $region->getDepartments();
            $form->add('department',EntityType::class,[
                'class' => Department::class,
                'choices'   => $departments,
                'choice_label'  => 'name',
                'placeholder'   => 'Départements (choisir une région)',
                'label' => 'Department'
            ]);
        };
        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $region = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(),$region);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
