<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\HasDescriptionTrait;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasNameTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['get', 'Recipe:item:get'],
            ],
        ],
        'delete',
        'patch',
    ],
    normalizationContext: ['groups' => ['get']]
)]
class Recipe
{
    use HasIdTrait;
    use HasNameTrait;
    use HasDescriptionTrait;
    use HasTimestampTrait;

    #[ORM\Column]
    #[Groups(['get'])]
    private ?bool $draft = true;

    /**
     * Temps de cuisson.
     */
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['get'])]
    private ?int $cooking = null;

    /**
     * Temps de repos.
     */
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['get'])]
    private ?int $break = null;

    /**
     * Temps de préparation.
     */
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['get'])]
    private ?int $preparation = null;

    /**
     * @var Collection<int, Step>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Step::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['get'])]
    private Collection $steps;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    #[Groups(['get'])]
    private Collection $images;

    /**
     * @var Collection<int, RecipeHasIngredient>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeHasIngredient::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['get'])]
    private Collection $recipeHasIngredients;

    /**
     * @var Collection<int, RecipeHasSource>
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeHasSource::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['get'])]
    private Collection $recipeHasSources;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'recipes')]
    #[Groups(['get'])]
    private Collection $tags;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->recipeHasIngredients = new ArrayCollection();
        $this->recipeHasSources = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function isDraft(): ?bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): self
    {
        $this->draft = $draft;

        return $this;
    }

    public function getCooking(): ?int
    {
        return $this->cooking;
    }

    public function setCooking(?int $cooking): self
    {
        $this->cooking = $cooking;

        return $this;
    }

    public function getBreak(): ?int
    {
        return $this->break;
    }

    public function setBreak(?int $break): self
    {
        $this->break = $break;

        return $this;
    }

    public function getPreparation(): ?int
    {
        return $this->preparation;
    }

    public function setPreparation(?int $preparation): self
    {
        $this->preparation = $preparation;

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setRecipe($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRecipe() === $this) {
                $image->setRecipe(null);
            }
        }

        return $this;
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
            $recipeHasIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): self
    {
        if ($this->recipeHasIngredients->removeElement($recipeHasIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasIngredient->getRecipe() === $this) {
                $recipeHasIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeHasSource>
     */
    public function getRecipeHasSources(): Collection
    {
        return $this->recipeHasSources;
    }

    public function addRecipeHasSource(RecipeHasSource $recipeHasSource): self
    {
        if (!$this->recipeHasSources->contains($recipeHasSource)) {
            $this->recipeHasSources[] = $recipeHasSource;
            $recipeHasSource->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeHasSource(RecipeHasSource $recipeHasSource): self
    {
        if ($this->recipeHasSources->removeElement($recipeHasSource)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasSource->getRecipe() === $this) {
                $recipeHasSource->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addRecipe($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeRecipe($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName().' ('.$this->getId().')';
    }
}
