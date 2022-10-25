<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextEditorField::new('description'),
            BooleanField::new('draft'),
            IntegerField::new('cooking'),
            IntegerField::new('break'),
            IntegerField::new('preparation'),
            CollectionField::new('steps'),
            CollectionField::new('images')
                ->setEntryType(ImageType::class)
                ->allowDelete()
                ->allowAdd(),
            CollectionField::new('recipeHasIngredients'),
            CollectionField::new('recipeHasSources'),
            CollectionField::new('tags'),
        ];
    }
}
