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
            IdField::new(propertyName: 'id')->hideOnForm(),
            BooleanField::new(propertyName: 'optional'),
            AssociationField::new(propertyName: 'recipe'),
            AssociationField::new(propertyName: 'ingredient'),
            NumberField::new(propertyName: 'quantity'),
            AssociationField::new(propertyName: 'unit'),
            AssociationField::new(propertyName: 'ingredientGroup'),
        ];
    }
}
