<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\ImageType;
use App\Form\RecipeHasIngredientType;
use App\Form\RecipeHasSourceType;
use App\Form\StepType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            // On déclare un champ (field) pour la propriété id
            // et on utilise un champ de type IdField, dont c'est le travail.
            IdField::new(propertyName: 'id')
                // Par contre, on ne le veut pas dans les formulaires,
                // car c'est une donnée gérée par Doctrine
                ->hideOnForm()
            ,
            TextField::new(propertyName: 'name'),
            // Pour le champ description, on utilise un TextEditorField,
            // qui permet la mise en forme du texte avec du HTML (à ne pas oublier pour l'affichage ;) )
            TextEditorField::new(propertyName: 'description'),
            // Le BooleanField va s'afficher sous forme d'un bouton "switch" sur la liste,
            // ce qui nous permettra de modifier la valeur à la volée.
            BooleanField::new(propertyName: 'draft'),
            IntegerField::new(propertyName: 'cooking'),
            IntegerField::new(propertyName: 'break'),
            IntegerField::new(propertyName: 'preparation'),
            // CollectionField nous permet de créer / supprimer des éléments à la volée.
            // On n'oublie pas le "cascade: ['persist']" dans l'entité.
            // Si la suppression ne fonctionne pas, il faudra ajouter l'option 'by_reference' à false
            // (voir plus bas).
            CollectionField::new(propertyName: 'steps')
                // Pour la collection, on utilise un formulaire Symfony (créé avec bin/console make:form)
                // car EasyAdmin ne peut déterminer automatiquement les champs dont on a besoin.
                // Je vous recommande de lire la documentation de Symfony sur le sujet : https://symfony.com/doc/current/forms.html
                ->setEntryType(formTypeFqcn: StepType::class)
                // On veut pouvoir supprimer des éléments de la liste,
                ->allowDelete()
                // mais aussi en créer de nouveau.
                ->allowAdd(),
            CollectionField::new(propertyName: 'images')
                ->setEntryType(formTypeFqcn: ImageType::class)
                ->allowDelete()
                ->allowAdd(),
            CollectionField::new(propertyName: 'recipeHasIngredients')
                ->setEntryType(formTypeFqcn: RecipeHasIngredientType::class)
                ->allowDelete()
                ->allowAdd(),
            CollectionField::new(propertyName: 'recipeHasSources')
                ->setEntryType(formTypeFqcn: RecipeHasSourceType::class)
                ->allowDelete()
                ->allowAdd(),
            // Pour les tags, on veut seulement utiliser des tags existants, et non en créer,
            // et on utilise le AssociationField pour cela.
            AssociationField::new(propertyName: 'tags')
                // Cette option est nécessaire pour que nos adders et removers soient appelés
                // lors de l'enregistrement du formulaire (https://symfony.com/doc/current/reference/forms/types/entity.html#by-reference).
                // Sans cela, les suppressions de liens avec des tags sont juste ignorés.
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
            ,
        ];
    }
}
