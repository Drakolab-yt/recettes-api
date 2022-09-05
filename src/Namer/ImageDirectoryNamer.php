<?php

namespace App\Namer;

use Exception;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class ImageDirectoryNamer implements DirectoryNamerInterface
{
    /**
     * @param \App\Entity\Image                            $object
     * @param \Vich\UploaderBundle\Mapping\PropertyMapping $mapping
     *
     * @return string
     * @throws \Exception
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {
        $recipe = $object->getRecipe();
        $step = $object->getStep();

        if (!is_null($step)) {
            $recipe = $step->getRecipe();
        }

        if (is_null($recipe)) {
            throw new Exception('Recipe and Step MUST not be empty in images');
        }

        $directoryName = $recipe->getSlug();

        if (!is_null($step)) {
            $directoryName .= '/' . $step->getId();
        }

        return $directoryName;
    }
}