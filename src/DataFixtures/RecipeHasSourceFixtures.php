<?php

namespace App\DataFixtures;

use App\Entity\RecipeHasSource;
use App\Repository\RecipeRepository;
use App\Repository\SourceRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeHasSourceFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(
        protected RecipeRepository $recipeRepository,
        protected SourceRepository $sourceRepository
    ) {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        $recipes = $this->recipeRepository->findAll();
        $sources = $this->sourceRepository->findAll();

        foreach ($recipes as $recipe) {
            $recipeSources = $this->faker->randomElements($sources, $this->faker->numberBetween(0, 3));
            foreach ($recipeSources as $recipeSource) {
                $recipeHasSource = new RecipeHasSource();
                $recipeHasSource
                    ->setUrl($this->faker->url())
                    ->setRecipe($recipe)
                    ->setSource($recipeSource)
                ;
                $manager->persist($recipeHasSource);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RecipeFixtures::class,
            SourceFixtures::class,
        ];
    }
}
