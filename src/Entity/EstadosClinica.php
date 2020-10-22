<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstadosClinicaRepository")
 */
class EstadosClinica
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Materiales", mappedBy="estado_clinica")
     */
    private $materiales;

    public function __construct()
    {
        $this->materiales = new ArrayCollection();
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

    /**
     * @return Collection|Materiales[]
     */
    public function getMateriales(): Collection
    {
        return $this->materiales;
    }

    public function addMateriale(Materiales $materiale): self
    {
        if (!$this->materiales->contains($materiale)) {
            $this->materiales[] = $materiale;
            $materiale->setEstadoClinica($this);
        }

        return $this;
    }

    public function removeMateriale(Materiales $materiale): self
    {
        if ($this->materiales->contains($materiale)) {
            $this->materiales->removeElement($materiale);
            // set the owning side to null (unless already changed)
            if ($materiale->getEstadoClinica() === $this) {
                $materiale->setEstadoClinica(null);
            }
        }

        return $this;
    }
}
