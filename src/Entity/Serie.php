<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SerieRepository")
 */
class Serie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $idTr;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ordentrabajo", mappedBy="serie", orphanRemoval=true)
     */
    private $ordentrabajos;

    public function __construct()
    {
        $this->ordentrabajos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getIdTr(): ?int
    {
        return $this->idTr;
    }

    public function setIdTr(int $idTr): self
    {
        $this->idTr = $idTr;

        return $this;
    }

    /**
     * @return Collection|Ordentrabajo[]
     */
    public function getOrdentrabajos(): Collection
    {
        return $this->ordentrabajos;
    }

    public function addOrdentrabajo(Ordentrabajo $ordentrabajo): self
    {
        if (!$this->ordentrabajos->contains($ordentrabajo)) {
            $this->ordentrabajos[] = $ordentrabajo;
            $ordentrabajo->setSerie($this);
        }

        return $this;
    }

    public function removeOrdentrabajo(Ordentrabajo $ordentrabajo): self
    {
        if ($this->ordentrabajos->contains($ordentrabajo)) {
            $this->ordentrabajos->removeElement($ordentrabajo);
            // set the owning side to null (unless already changed)
            if ($ordentrabajo->getSerie() === $this) {
                $ordentrabajo->setSerie(null);
            }
        }

        return $this;
    }
}
