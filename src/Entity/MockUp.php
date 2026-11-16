<?php

namespace App\Entity;

use App\Repository\MockUpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MockUpRepository::class)]
class MockUp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max: 50, maxMessage: 'Le type de mockup ne doit pas dépasser {{ limit }} caractères.')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9\s_?!:\-]+$/', message: 'Les caractères spéciaux non autorisés sont interdits.')]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'mockUp', targetEntity: Portfolio::class)]
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
            $portfolio->setMockUp($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): static
    {
        if ($this->portfolios->removeElement($portfolio)) {
            if ($portfolio->getMockUp() === $this) {
                $portfolio->setMockUp(null);
            }
        }

        return $this;
    }
}