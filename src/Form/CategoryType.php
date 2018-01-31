<?php
namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
          ->add('name', TextType::class, array('label' => "Nom de la catégorie"))
          ->add('image', FileType::class, array(
            'label' => 'Image de fond de la catégorie',
            'data_class' => null
          ))
          ->add('save', SubmitType::class, array('label' => 'Enregistrer'));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => Category::class,
    ));
  }
}
?>
