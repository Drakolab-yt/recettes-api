<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Repository\RecipeRepository;
use App\Repository\StepRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(
        protected RecipeRepository $recipeRepository,
        protected StepRepository $stepRepository
    ) {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        $recipes = $this->recipeRepository->findAll();
        $steps = $this->stepRepository->findAll();

        $destination = __DIR__.'/../../public/images/';
        $filesystem = new Filesystem();

        foreach ($recipes as $recipe) {
            for ($i = 0; $i < $this->faker->numberBetween(0, 4); $i++) {
                $imgFile = $this->createImage();

                $fileDest = $destination.$recipe->getSlug();
//                $imgFile->move($fileDest);
                $filesystem->copy($imgFile->getRealPath(), $fileDest.'/'.$imgFile->getFilename());

                $image = new Image();
                $image->setPath($imgFile->getFilename());
                $image->setSize($imgFile->getSize());
                $image->setRecipe($recipe);
                $image->setPriority(1);
                $manager->persist($image);
            }
        }
        foreach ($steps as $step) {
            for ($i = 0; $i < $this->faker->numberBetween(0, 4); $i++) {
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

        $manager->flush();
    }

    protected function createImage(): UploadedFile
    {
        $number = $this->faker->numberBetween(1, 16);
        $folder = __DIR__.'/../../var/images/fixtures/';
        $imgName = 'image'.$number.'.jpg';
        $src = realpath($folder.$imgName);
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
