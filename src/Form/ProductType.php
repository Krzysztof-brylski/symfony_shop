<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,array(
                "attr"=>["p-1"],
                "label"=>false
            ))
            ->add('Description',TextareaType::class,array(
                "attr"=>["p-1"],
                "label"=>false
            ))
            ->add('price',NumberType::class,array(
                "attr"=>["p-1"],
                "label"=>false
            ))
            ->add('images',FileType::class,array(
                "attr"=>[
                    'multiple' => 'multiple',
                ],
                "mapped"=>false,
                "label"=>false
            ))
            ->add('quantity',NumberType::class,array(
                "attr"=>["p-1"],
                "label"=>false
            ))
           ->add('category', EntityType::class, [
               'class' => Category::class,
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('u')
                       ->orderBy('u.name', 'ASC');
               },
               'choice_value'=>'id',
               'choice_label' => 'name',
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
