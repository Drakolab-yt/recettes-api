<?php

namespace App\Namer;

use App\Entity\Image;
use Exception;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * @implements DirectoryNamerInterface<Image>
 */
class ImageDirectoryNamer implements DirectoryNamerInterface
{
    /**
     * @param Image $object
     *
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
            $directoryName .= '/'.$step->getId();
        }

        return (string) $directoryName;
    }
}
