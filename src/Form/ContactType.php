<?php
namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, OptionsResolver $options)
  {
    $builder
      ->add('firstname', TextType::class)
      ->add('lastname', TextType::class)
      ->add('email', EmailType::class)
      ->add('message', TextType::class)
      ->add('submit', SubmitType::class);
  }
  public function configureOptions(OptionsResolver $options)
  {
    $resolver->setDefaults(array(
        'data_class' => Contact::class
    ));
  }
}
