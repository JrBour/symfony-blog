<?php
namespace App\Form;

use App\Entity\Answer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnswerType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('content', TextareaType::class, ['label' => 'form.picture'])
      ->add('picture', FileType::class, [
        'label' => 'form.picture',
        'data_class' => null,
        'required' => false
      ])
      ->add('submit', SubmitType::class, ['label' => 'form.save']);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(
        array(
          'data_class' => Answer::class
        ));
  }
}
