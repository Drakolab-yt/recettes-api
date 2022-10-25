<?php

namespace App\Controller\Admin;

use App\Entity\IngredientGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class IngredientGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IngredientGroup::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            TextField::new(propertyName: 'name'),
            IntegerField::new(propertyName: 'priority'),
        ];
    }
}
