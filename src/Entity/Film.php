<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
#[Vich\Uploadable]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(length: 255)]
    private ?string $affiche = null;

    #[Vich\UploadableField(mapping:'affiches', fileNameProperty:'affiche')]
    private ?File $afficheFile = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $synopsis = null;

    #[ORM\ManyToMany(targetEntity: Artiste::class, inversedBy: 'filmsJoues')]
    private Collection $acteurs;

    #[ORM\ManyToOne(inversedBy: 'filmsRealises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artiste $realisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $maj = null;

    public function __construct()
    {
        $this->acteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getAffiche(): ?string
    {
        return $this->affiche;
    }

    public function setAffiche(?string $affiche): self
    {
        $this->affiche = $affiche;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * @return Collection<int, Artiste>
     */
    public function getActeurs(): Collection
    {
        return $this->acteurs;
    }

    public function addActeur(Artiste $acteur): self
    {
        if (!$this->acteurs->contains($acteur)) {
            $this->acteurs->add($acteur);
            $acteur->addFilmsJoue($this);
        }

        return $this;
    }

    public function removeActeur(Artiste $acteur): self
    {
        if ($this->acteurs->removeElement($acteur)) {
            $acteur->removeFilmsJoue($this);
        }

        return $this;
    }

    public function getRealisateur(): ?Artiste
    {
        return $this->realisateur;
    }

    public function setRealisateur(?Artiste $realisateur): self
    {
        $this->realisateur = $realisateur;

        return $this;
    }

    /**
     * Get the value of afficheFile
     *
     * @return ?File
     */
    public function getAfficheFile(): ?File
    {
        return $this->afficheFile;
    }

    /**
     * Set the value of afficheFile
     *
     * @param ?File $afficheFile
     *
     * @return self
     */
    public function setAfficheFile(?File $afficheFile): self
    {
        $this->afficheFile = $afficheFile;

        if( $afficheFile !== null ){
            $this->maj = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getMaj(): ?\DateTimeInterface
    {
        return $this->maj;
    }

    public function setMaj(?\DateTimeInterface $maj): self
    {
        $this->maj = $maj;

        return $this;
    }
}
