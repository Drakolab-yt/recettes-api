<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Step;
use App\Repository\RecipeRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StepFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(protected RecipeRepository $recipeRepository)
    {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        $recipes = $this->recipeRepository->findAll();
        for ($i = 0; $i < 250; ++$i) {
            /** @var Recipe $recipe */
            $recipe = $this->faker->randomElement($recipes);
            $step = new Step();
            $step
                ->setContent($this->faker->realText())
                ->setPriority($this->faker->randomDigitNotZero())
                ->setRecipe($recipe)
            ;
            $manager->persist($step);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RecipeFixtures::class,
        ];
    }
}
