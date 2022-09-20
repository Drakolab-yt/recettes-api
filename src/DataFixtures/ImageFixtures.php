<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Repository\RecipeRepository;
use App\Repository\StepRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

// Cette migration étend AbstractFixtures pour avoir Faker (et déjà prêt à être utilisée),
// et implémente DependentFixtureInterface car certaines fixtures doivent être passées pour que celle-ci fonctionne.
// En l'occurrence, on doit avoir des recettes et des étapes en BdD.
class ImageFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    // On utilise l'injection de dépendances pour récupérer les repositories des recettes et des étapes.
    // On va ainsi pouvoir faire des requêtes sur les tables correspondantes (récupérer tous les éléments).
    public function __construct(
        protected RecipeRepository $recipeRepository,
        protected StepRepository $stepRepository
    ) {
        // On n'oublie pas d'appeler le constructeur de AbstractFixtures, sinon Faker ne sera pas prête.
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        // On récupère toutes les recettes et toutes les étapes, pour leur associer ensuite des images.
        $recipes = $this->recipeRepository->findAll();
        $steps = $this->stepRepository->findAll();

        // On construit le chemin de destination des images.
        // En l'état, je n'arrive pas à demander à VichUploader de le faire
        // et suis obligé de le faire "à la main".
        $destination = __DIR__.'/../../public/images/';
        // On va se servir du composant "Filesystem" de Symfony pour copier les images depuis le dossier var/
        // vers leur destination finale.
        $filesystem = new Filesystem();

        // On parcourt toutes les recettes
        foreach ($recipes as $recipe) {
            // et on leur ajoute entre 0 et 4 images (environ une recette sur 4 n'auront donc pas d'images).
            for ($i = 0; $i < $this->faker->numberBetween(0, 4); ++$i) {
                // On crée un UploadedFile, pour disposer de quelques méthodes pratiques,
                // mais ça n'est pas nécessaire, on aurait largement pu faire sans.
                $imgFile = $this->createImage();

                // On calcule le dossier de destination.
                // /!\ Attention, le calcul doit être le même que dans src/Namer/ImageDirectoryNamer.php .
                $fileDest = $destination.$recipe->getSlug();

                // On copie le fichier du dossier var vers sa destination
                // (si on ne copie pas, on va avoir de moins en moins de fichiers, et on en manquera vite ;) ).
                $filesystem->copy($imgFile->getRealPath(), $fileDest.'/'.$imgFile->getFilename());

                // On crée l'entité à persister, en mettant à jour manuellement les propriétés path et size.
                // Normalement, c'est VichUploader qui devraient les remplir automatiquement, c'est là mon souci !
                $image = new Image();
                $image->setPath($imgFile->getFilename());
                $image->setSize($imgFile->getSize());
                $image->setRecipe($recipe);
                $image->setPriority(1);

                // On demande à Doctrine (et son ObjectManager) de retenir cette entité comme "à sauvegarder".
                $manager->persist($image);
            }
        }

        // On répète l'opération pour les étapes.
        foreach ($steps as $step) {
            for ($i = 0; $i < $this->faker->numberBetween(0, 4); ++$i) {
                if (is_null($step->getRecipe())) {
                    continue;
                }
                $imgFile = $this->createImage();
                $fileDest = $destination.$step->getRecipe()->getSlug().'/'.$step->getId();
                $filesystem->copy($imgFile->getRealPath(), $fileDest.'/'.$imgFile->getFilename());

                $image = new Image();
                $image->setPath($imgFile->getFilename());
                $image->setSize($imgFile->getSize());
                $image->setStep($step);
                $image->setPriority(1);
                $manager->persist($image);
            }
        }

        // On demande à Doctrine (et son ObjectManager) d'enregistrer en BdD toutes les entités qu'il a en attente.
        $manager->flush();
    }

    // Une méthode pour récupérer une image au hasard dans le dossier var, et nous la renvoie
    // sous la forme d'un objet UploadedFile.
    protected function createImage(): UploadedFile
    {
        $number = $this->faker->numberBetween(1, 16);
        $folder = __DIR__.'/../../var/images/fixtures/';
        $imgName = 'image'.$number.'.jpg';
        $src = $folder.$imgName;

        return new UploadedFile(
            path: $src,
            originalName: $imgName,
            mimeType: 'image/jpeg',
            test: true
        );
    }

    public function getDependencies(): array
    {
        return [
            RecipeFixtures::class,
            StepFixtures::class,
        ];
    }
}
