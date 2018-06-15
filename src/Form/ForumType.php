<?php
namespace App\Form;

use App\Entity\Forum;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'form.subject'])
            ->add('content', TextareaType::class, ['label' => 'form.content'])
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
                'data_class' => Forum::class
            ));
    }
}
