<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 120; ++$i) {
            $ingredient = new Ingredient();
            /** @var string $name */
            $name = $this->faker->words(4, true);
            $ingredient
                ->setName($name)
                ->setDescription($this->faker->realText(125))
                ->setDairyFree($this->faker->boolean())
                ->setGlutenFree($this->faker->boolean())
                ->setVegan($this->faker->boolean())
                ->setVegetarian($this->faker->boolean())
            ;
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
