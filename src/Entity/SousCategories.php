<?php

namespace App\Entity;

use App\Repository\SousCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SousCategoriesRepository::class)]
#[UniqueEntity(fields: ['type'], message: 'Cette sous-catégorie existe déjà.')]
class SousCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le nom de la sous-catégorie est obligatoire.')]
    #[Assert\Length(max: 50, maxMessage: 'La sous-catégorie ne doit pas dépasser {{ limit }} caractères.')]
    #[Assert\Regex(pattern: '/^[a-zA-ZÀ-ÿ0-9\s_?!:\-\']+$/', message: 'Les caractères spéciaux non autorisés sont interdits.')]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Portfolio::class, mappedBy: 'sousCategories')]
    private Collection $portfolios;

    public function __construct()
    {
        $this->portfolios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Portfolio>
     */
    public function getPortfolios(): Collection
    {
        return $this->portfolios;
    }

    public function addPortfolio(Portfolio $portfolio): static
    {
        if (!$this->portfolios->contains($portfolio)) {
            $this->portfolios->add($portfolio);
            $portfolio->addSousCategory($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): static
    {
        if ($this->portfolios->removeElement($portfolio)) {
            $portfolio->removeSousCategory($this);
        }

        return $this;
    }
}
