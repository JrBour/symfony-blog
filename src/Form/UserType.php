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
    /**
     * Build the form for register a new user
     * @param FormBuilderInterface  $builder
     * @param array                 $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class, ['label' => 'form.username'])
            ->add('image', FileType::class, array(
                'label' => 'form.profil_picture',
                'required' => false,
                'data_class' => null
            ))
            ->add('role', ChoiceType::class, array(
                'label' => 'Role',
                'choices' => $options['choices'],
                'choice_label' => function($value) {
                    return $value->getName();
                }
            ))
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                if (!empty($form->getData()) && !empty($form->getData()->getUsername())) {
                    $form->add(
                        'plainPassword',
                        RepeatedType::class, [
                            'type' => PasswordType::class,
                            'required' => false,
                            'first_options' => ['label' => 'form.password'],
                            'second_options' => ['label' => 'form.confirm_password'],
                        ]
                    );
                } else {
                    $form->add(
                        'plainPassword',
                        RepeatedType::class,
                        array(
                            'type' => PasswordType::class,
                            'first_options' => ['label' => 'form.password'],
                            'second_options' => ['label' => 'form.confirm_password'],
                        )
                    );
                }
            })
            ->add('save', SubmitType::class, ['label' => 'form.save']);
    }

    /**
     * Configure the form build for register a new user
     * @param OptionsResolver       $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'choices' => []
        ));
    }
}
