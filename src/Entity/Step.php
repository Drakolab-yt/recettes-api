<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasPriorityTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\StepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StepRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(security: "is_granted('ROLE_ADMIN') or object.getRecipe().getUser() == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.getRecipe().getUser() == user"),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN') or object.getRecipe().getUser() == user"),
    ],
    normalizationContext: ['groups' => ['get']]
)]
class Step
{
    use HasIdTrait;
    use HasPriorityTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['get'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(mappedBy: 'step', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    #[Groups(['get'])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
            $image->setStep($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getStep() === $this) {
                $image->setStep(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getRecipe().' n°'.$this->getPriority();
    }
}
