<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\HasDescriptionTrait;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasNameTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\SourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourceRepository::class)]
#[ApiResource(
    itemOperations: ['get', 'delete', 'patch'],
)]
class Source
{
    use HasIdTrait;
    use HasNameTrait;
    use HasDescriptionTrait;
    use HasTimestampTrait;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\OneToMany(mappedBy: 'source', targetEntity: RecipeHasSource::class, orphanRemoval: true)]
    private Collection $recipeHasSources;

    public function __construct()
    {
        $this->recipeHasSources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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
            $recipeHasSource->setSource($this);
        }

        return $this;
    }

    public function removeRecipeHasSource(RecipeHasSource $recipeHasSource): self
    {
        if ($this->recipeHasSources->removeElement($recipeHasSource)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasSource->getSource() === $this) {
                $recipeHasSource->setSource(null);
            }
        }

        return $this;
    }
}
