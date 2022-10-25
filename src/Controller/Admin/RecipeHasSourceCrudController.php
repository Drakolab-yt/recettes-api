<?php

namespace App\Controller\Admin;

use App\Entity\RecipeHasSource;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class RecipeHasSourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeHasSource::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            UrlField::new('url'),
            AssociationField::new('recipe'),
            AssociationField::new('source'),
        ];
    }
}
