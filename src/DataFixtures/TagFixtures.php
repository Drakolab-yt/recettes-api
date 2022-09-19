<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager): void
    {
        $tags = [];
        for ($i = 0; $i < 200; ++$i) {
            $tag = new Tag();
            /** @var Tag $parent */
            $parent = $this->faker->optional(weight: 0.125)->randomElement($tags);
            $tag
                ->setName($this->faker->name())
                ->setDescription($this->faker->text(250))
                ->setMenu($this->faker->boolean(30))
                ->setParent($parent)
            ;
            $tags[] = $tag;
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
