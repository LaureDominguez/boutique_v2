<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    private Collection $other;

    public function __construct()
    {
        $this->other = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getOther(): Collection
    {
        return $this->other;
    }

    public function addOther(Product $other): self
    {
        if (!$this->other->contains($other)) {
            $this->other->add($other);
            $other->setCategory($this);
        }

        return $this;
    }

    public function removeOther(Product $other): self
    {
        if ($this->other->removeElement($other)) {
            // set the owning side to null (unless already changed)
            if ($other->getCategory() === $this) {
                $other->setCategory(null);
            }
        }

        return $this;
    }
}
