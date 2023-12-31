<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidat $candidat = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $annonce = null;

    #[ORM\Column]
    private ?bool $IsValidate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidatId(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidatId(?Candidat $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getAnnonceId(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonceId(?Annonce $annonce_id): static
    {
        $this->annonce = $annonce_id;

        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): static
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function isIsValidate(): ?bool
    {
        return $this->IsValidate;
    }

    public function setIsValidate(bool $IsValidate): static
    {
        $this->IsValidate = $IsValidate;

        return $this;
    }
}
