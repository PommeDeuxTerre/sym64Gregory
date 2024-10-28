<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Section;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('text', TextareaType::class, ['attr' => ['placeholder' => 'Votre Text', 'rows' => 5, 'cols' => 40]])
            ->add('articleDateCreate', null)
            ->add('articleDatePosted', null)
            ->add('published')
            ->add('sections', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'section_title',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'choice_attr' => function ($choice, $key, $value) {
                    return $key === 0 ? [] : ['class' => 'ms-2'];
                },
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'tag_name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'choice_attr' => function ($choice, $key, $value) {
                    return $key === 0 ? [] : ['class' => 'ms-2'];
                },
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
