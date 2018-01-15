<?php
namespace App\Form;


use App\Entity\Blog;
use App\Form\CategoryType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BlogType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
          ->add('title')
          ->add('description', TextareaType::class)
          ->add('category', ChoiceType::class, array(
            'label' => 'Categories',
            'choices' => $options['choices'],
            'choice_label' => function($value) {
                return $value->getName();
              }
          ))
          ->add('Enregistrer', SubmitType::class);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => Blog::class,
        'choices' => array()
    ));
  }
}
?>
