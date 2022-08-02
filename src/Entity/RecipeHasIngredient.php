<?php

namespace App\Entity;

use App\Entity\Traits\HasIdTrait;
use App\Repository\RecipeHasIngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeHasIngredientRepository::class)]
class RecipeHasIngredient
{
    use HasIdTrait;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column]
    private ?bool $optional = null;

    #[ORM\ManyToOne(inversedBy: 'recipeHasIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\OneToMany(mappedBy: 'recipeHasIngredients', targetEntity: Unit::class)]
    private Collection $units;

    #[ORM\ManyToOne(inversedBy: 'recipeHasIngredients')]
    private ?Unit $unit = null;

    #[ORM\ManyToOne(inversedBy: 'recipeHasIngredients')]
    private ?IngredientGroup $ingredientGroup = null;

    #[ORM\ManyToOne(inversedBy: 'RecipeHasIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    public function __construct()
    {
        $this->units = new ArrayCollection();
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isOptional(): ?bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): self
    {
        $this->optional = $optional;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * @return Collection<int, Unit>
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function addUnit(Unit $unit): self
    {
        if (!$this->units->contains($unit)) {
            $this->units[] = $unit;
            $unit->setRecipeHasIngredients($this);
        }

        return $this;
    }

    public function removeUnit(Unit $unit): self
    {
        if ($this->units->removeElement($unit)) {
            // set the owning side to null (unless already changed)
            if ($unit->getRecipeHasIngredients() === $this) {
                $unit->setRecipeHasIngredients(null);
            }
        }

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getIngredientGroup(): ?IngredientGroup
    {
        return $this->ingredientGroup;
    }

    public function setIngredientGroup(?IngredientGroup $ingredientGroup): self
    {
        $this->ingredientGroup = $ingredientGroup;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }
}
