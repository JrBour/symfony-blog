<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('email', EmailType::class)
      ->add('username', TextType::class)
      ->add('image', FileType::class, array(
        'label' => 'Image de MAIS PUTAIN FONCTIONNE TA MERE LA',
        'data_class' => null
      ))
      ->add('role', ChoiceType::class, array(
        'label' => 'Role',
        'choices' => $options['choices'],
        'choice_label' => function($value) {
            return $value->getName();
          }
      ))
      ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                if (!empty($form->getData()) && !empty($form->getData()->getUsername())) {
                    $form->add(
                        'plainPassword',
                        RepeatedType::class,
                        array(
                             'type' => PasswordType::class,
                             'required' => false,
                             'first_options' => array('label' => 'Mot de passe'),
                             'second_options' => array('label' => 'Mot de passe'),
                            )
                    );
                } else {
                    $form->add(
                        'plainPassword',
                        RepeatedType::class,
                        array(
                             'type' => PasswordType::class,
                             'first_options' => array('label' => 'Mot de passe'),
                             'second_options' => array('label' => 'Mot de passe'),
                        )
                    );
                }
            })
            ->add('save', SubmitType::class);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => User::class,
        'choices' => array()
    ));
  }
}
