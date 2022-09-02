<?php

namespace App\Vich\Naming;

use App\Entity\Image;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class ImageDirectoryNamer implements DirectoryNamerInterface
{
    /**
     * @param Image           $object
     * @param PropertyMapping $mapping
     *
     * @return string
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {
        $recipe = $object->getRecipe();
        $step = $object->getStep();
        if ($step) {
            $recipe = $step->getRecipe();
        }

        $directory = $recipe->getSlug();
        if ($step) {
            $directory .= '/'.$step->getId();
        }

        return $directory;
    }
}