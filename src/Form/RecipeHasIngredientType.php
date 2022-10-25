<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientGroup;
use App\Entity\RecipeHasIngredient;
use App\Entity\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeHasIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'quantity', type: NumberType::class)
            ->add(child: 'optional', type: CheckboxType::class)
            ->add(child: 'ingredient', type: EntityType::class, options: [
                'class' => Ingredient::class,
            ])
            ->add(child: 'ingredientGroup', type: EntityType::class, options: [
                'class' => IngredientGroup::class,
            ])
            ->add(child: 'unit', type: EntityType::class, options: [
                'class' => Unit::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeHasIngredient::class,
        ]);
    }
}
