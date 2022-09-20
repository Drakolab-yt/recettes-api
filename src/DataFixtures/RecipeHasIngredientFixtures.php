<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\IngredientGroup;
use App\Entity\RecipeHasIngredient;
use App\Entity\Unit;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use App\Repository\UnitRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeHasIngredientFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(
        protected UnitRepository $unitRepository,
        protected RecipeRepository $recipeRepository,
        protected IngredientRepository $ingredientRepository
    ) {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        $units = $this->unitRepository->findAll();
        $ingredients = $this->ingredientRepository->findAll();
        $recipes = $this->recipeRepository->findAll();

        $groups = [];
        for ($i = 0; $i < 20; ++$i) {
            $group = new IngredientGroup();
            /** @var string $name */
            $name = $this->faker->words(2, true);
            $group
                ->setName($name)
                ->setPriority(1)
            ;
            $manager->persist($group);
            $groups[] = $group;
        }

        foreach ($recipes as $recipe) {
            $recipeGroups = [];
            if ($this->faker->boolean(25)) {
                $recipeGroups = $this->faker->randomElements($groups, $this->faker->numberBetween(2, 3));
            }
            for ($i = 0; $i < $this->faker->numberBetween(2, 8); ++$i) {
                $rhi = new RecipeHasIngredient();
                /** @var Unit $unit */
                $unit = $this->faker->randomElement($units);
                /** @var Ingredient $ingredient */
                $ingredient = $this->faker->randomElement($ingredients);
                $rhi
                    ->setUnit($unit)
                    ->setIngredient($ingredient)
                    ->setRecipe($recipe)
                    ->setOptional($this->faker->boolean(10))
                    ->setQuantity($this->faker->randomFloat(1, 0, 10))
                ;
                if (count($recipeGroups) > 0) {
                    /** @var IngredientGroup $group */
                    $group = $this->faker->randomElement($recipeGroups);
                    $rhi->setIngredientGroup($group);
                }
                $manager->persist($rhi);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            IngredientFixtures::class,
            RecipeFixtures::class,
            UnitFixtures::class,
        ];
    }
}
