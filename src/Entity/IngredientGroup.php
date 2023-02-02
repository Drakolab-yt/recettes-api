<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasNameTrait;
use App\Entity\Traits\HasPriorityTrait;
use App\Repository\IngredientGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientGroupRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
    normalizationContext: ['groups' => ['get']]
)]
class IngredientGroup
{
    use HasIdTrait;
    use HasNameTrait;
    use HasPriorityTrait;

    /**
     * @var Collection<int, RecipeHasIngredient>
     */
    #[ORM\OneToMany(mappedBy: 'ingredientGroup', targetEntity: RecipeHasIngredient::class)]
    private Collection $recipeHasIngredients;

    public function __construct()
    {
        $this->recipeHasIngredients = new ArrayCollection();
    }

    /**
     * @return Collection<int, RecipeHasIngredient>
     */
    public function getRecipeHasIngredients(): Collection
    {
        return $this->recipeHasIngredients;
    }

    public function addRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): self
    {
        if (!$this->recipeHasIngredients->contains($recipeHasIngredient)) {
            $this->recipeHasIngredients[] = $recipeHasIngredient;
            $recipeHasIngredient->setIngredientGroup($this);
        }

        return $this;
    }

    public function removeRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): self
    {
        if ($this->recipeHasIngredients->removeElement($recipeHasIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasIngredient->getIngredientGroup() === $this) {
                $recipeHasIngredient->setIngredientGroup(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName().' ('.$this->getId().')';
    }
}
