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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('firstname', TextType::class, array('label' => 'Prénom'))
      ->add('lastname', TextType::class, array('label' => 'Nom'))
      ->add('mail', EmailType::class, array('label' => 'Mail'))
      ->add('message', TextareaType::class, array('label' => 'Message'))
      ->add('submit', SubmitType::class, array('label' => 'Envoyer !'));
  }
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => Contact::class
    ));
  }
}
