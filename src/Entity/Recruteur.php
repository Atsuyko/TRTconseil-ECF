<?php

namespace App\Entity;

use App\Repository\RecruteurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecruteurRepository::class)]
class Recruteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_postcode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_city = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getCompanyAdress(): ?string
    {
        return $this->company_adress;
    }

    public function setCompanyAdress(?string $company_adress): static
    {
        $this->company_adress = $company_adress;

        return $this;
    }

    public function getCompanyPostcode(): ?string
    {
        return $this->company_postcode;
    }

    public function setCompanyPostcode(?string $company_postcode): static
    {
        $this->company_postcode = $company_postcode;

        return $this;
    }

    public function getCompanyCity(): ?string
    {
        return $this->company_city;
    }

    public function setCompanyCity(?string $company_city): static
    {
        $this->company_city = $company_city;

        return $this;
    }
}
