<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipoEquipoRepository")
 */
class TipoEquipo
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
     * @ORM\OneToMany(targetEntity="App\Entity\Equipo", mappedBy="tipoEquipo")
     */
    private $equipos;


    public function __construct()
    {
        $this->equipos = new ArrayCollection();
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
     * @return Collection|Equipo[]
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(Equipo $equipo): self
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos[] = $equipo;
            $equipo->setTipoEquipo($this);
        }

        return $this;
    }

    public function removeEquipo(Equipo $equipo): self
    {
        if ($this->equipos->contains($equipo)) {
            $this->equipos->removeElement($equipo);
            // set the owning side to null (unless already changed)
            if ($equipo->getTipoEquipo() === $this) {
                $equipo->setTipoEquipo(null);
            }
        }

        return $this;
    }
}
