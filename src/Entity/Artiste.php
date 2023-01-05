<?php

namespace App\Entity;

use App\Repository\ArtisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ArtisteRepository::class)]
#[Vich\Uploadable]
class Artiste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[Vich\UploadableField(mapping:'artistes', fileNameProperty:'photo')]
    private ?File $photoFile = null;

    #[ORM\ManyToMany(targetEntity: Film::class, mappedBy: 'acteurs')]
    private Collection $filmsJoues;

    #[ORM\OneToMany(mappedBy: 'realisateur', targetEntity: Film::class)]
    private Collection $filmsRealises;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $maj = null;

    public function __construct()
    {
        $this->filmsJoues = new ArrayCollection();
        $this->filmsRealises = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getPrenom() . " " . $this->getNom();  
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Film>
     */
    public function getFilmsJoues(): Collection
    {
        return $this->filmsJoues;
    }

    public function addFilmsJoue(Film $filmsJoue): self
    {
        if (!$this->filmsJoues->contains($filmsJoue)) {
            $this->filmsJoues->add($filmsJoue);
        }

        return $this;
    }

    public function removeFilmsJoue(Film $filmsJoue): self
    {
        $this->filmsJoues->removeElement($filmsJoue);

        return $this;
    }

    /**
     * @return Collection<int, Film>
     */
    public function getFilmsRealises(): Collection
    {
        return $this->filmsRealises;
    }

    public function addFilmsRealise(Film $filmsRealise): self
    {
        if (!$this->filmsRealises->contains($filmsRealise)) {
            $this->filmsRealises->add($filmsRealise);
            $filmsRealise->setRealisateur($this);
        }

        return $this;
    }

    public function removeFilmsRealise(Film $filmsRealise): self
    {
        if ($this->filmsRealises->removeElement($filmsRealise)) {
            // set the owning side to null (unless already changed)
            if ($filmsRealise->getRealisateur() === $this) {
                $filmsRealise->setRealisateur(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of photoFile
     *
     * @return ?File
     */
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    /**
     * Set the value of photoFile
     *
     * @param ?File $photoFile
     *
     * @return self
     */
    public function setPhotoFile(?File $photoFile): self
    {
        $this->photoFile = $photoFile;

        if( $photoFile !== null ){
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
