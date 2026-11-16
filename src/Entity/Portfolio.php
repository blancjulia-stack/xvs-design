<?php

namespace App\Entity;

use App\Repository\PortfolioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PortfolioRepository::class)]
class Portfolio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max: 50, maxMessage: 'Le nom de l\'image ne doit pas dépasser {{ limit }} caractères.')]
    #[Assert\Regex(pattern: '/^[a-zA-ZÀ-ÿ0-9\s_?!:\-\']+$/', message: 'Les caractères spéciaux non autorisés sont interdits.')]
    private ?string $nom_image = null;

    #[ORM\Column(length: 255)]
    private ?string $chemin = null;

    #[ORM\ManyToOne(inversedBy: 'portfolios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $category = null;

    #[ORM\ManyToMany(targetEntity: SousCategories::class, inversedBy: 'portfolios')]
    #[ORM\JoinTable(name: 'image_lien_sous_cat')]
    private Collection $sousCategories;

    #[ORM\ManyToOne(inversedBy: 'portfolios')]
    private ?MockUp $mockUp = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100, maxMessage: 'La légende ne doit pas dépasser {{ limit }} caractères.')]
    #[Assert\Regex(pattern: '/^[a-zA-ZÀ-ÿ0-9\s_?!:\-\']+$/', message: 'Les caractères spéciaux non autorisés sont interdits.')]
    private ?string $legende = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $taille = null;

    #[ORM\Column(length: 255)]
    private ?string $Border = null;

    public function __construct()
    {
        $this->sousCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomImage(): ?string
    {
        return $this->nom_image;
    }

    public function setNomImage(string $nom_image): static
    {
        $this->nom_image = $nom_image;

        return $this;
    }

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(string $chemin): static
    {
        $this->chemin = $chemin;

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, SousCategories>
     */
    public function getSousCategories(): Collection
    {
        return $this->sousCategories;
    }

    public function addSousCategory(SousCategories $sousCategory): static
    {
        if (!$this->sousCategories->contains($sousCategory)) {
            $this->sousCategories->add($sousCategory);
        }

        return $this;
    }

    public function removeSousCategory(SousCategories $sousCategory): static
    {
        $this->sousCategories->removeElement($sousCategory);

        return $this;
    }

    public function getMockUp(): ?MockUp
    {
        return $this->mockUp;
    }

    public function setMockUp(?MockUp $mockUp): static
    {
        $this->mockUp = $mockUp;

        return $this;
    }

    public function getLegende(): ?string
    {
        return $this->legende;
    }

    public function setLegende(?string $legende): static
    {
        $this->legende = $legende;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getBorder(): ?string
    {
        return $this->Border;
    }

    public function setBorder(string $Border): static
    {
        $this->Border = $Border;

        return $this;
    }
}