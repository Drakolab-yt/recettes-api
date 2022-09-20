<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Repository\StepRepository;
use App\Repository\TagRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class RecipeFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    protected Faker\Generator $faker;

    public function __construct(
        protected TagRepository $tagRepository,
        protected StepRepository $stepRepository
    ) {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        $tags = $this->tagRepository->findAll();
        for ($i = 0; $i < 50; ++$i) {
            $recipe = new Recipe();
            $recipe
                ->setName($this->faker->name())
                ->setDescription($this->faker->realText(500))
                ->setDraft($this->faker->boolean(0.1))
                ->setPreparation($this->faker->optional(0.99)->numberBetween(5, 120))
                ->setCooking($this->faker->optional(0.75)->numberBetween(0, 120))
                ->setBreak($this->faker->optional(0.01)->numberBetween(0, 600))
            ;

            $recipeTags = $this->faker->randomElements($tags, $this->faker->randomNumber(2));
            foreach ($recipeTags as $recipeTag) {
                $recipe->addTag($recipeTag);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TagFixtures::class,
        ];
    }
}
