<?php

namespace App\Controller\Admin;

use App\Entity\RecipeHasIngredient;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RecipeHasIngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeHasIngredient::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            BooleanField::new('optional'),
            AssociationField::new('recipe'),
            AssociationField::new('ingredient'),
            NumberField::new('quantity'),
            AssociationField::new('unit'),
            AssociationField::new('ingredientGroup'),
        ];
    }
}
