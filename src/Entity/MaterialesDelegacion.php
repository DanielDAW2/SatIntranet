<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialesDelegacionRepository")
 */
class MaterialesDelegacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delegacion", inversedBy="materialesDelegacion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delegacion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\EstadosCentral")
     */
    private $estado_central;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\EstadosClinica")
     */
    private $EstadosClinica;

    public function __construct()
    {
        $this->estado_central = new ArrayCollection();
        $this->EstadosClinica = new ArrayCollection();
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

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getDelegacion(): ?Delegacion
    {
        return $this->delegacion;
    }

    public function setDelegacion(?Delegacion $delegacion): self
    {
        $this->delegacion = $delegacion;

        return $this;
    }

    /**
     * @return Collection|EstadosCentral[]
     */
    public function getEstadoCentral(): Collection
    {
        return $this->estado_central;
    }

    public function addEstadoCentral(EstadosCentral $estadoCentral): self
    {
        if (!$this->estado_central->contains($estadoCentral)) {
            $this->estado_central[] = $estadoCentral;
        }

        return $this;
    }

    public function removeEstadoCentral(EstadosCentral $estadoCentral): self
    {
        if ($this->estado_central->contains($estadoCentral)) {
            $this->estado_central->removeElement($estadoCentral);
        }

        return $this;
    }

    /**
     * @return Collection|EstadosClinica[]
     */
    public function getEstadosClinica(): Collection
    {
        return $this->EstadosClinica;
    }

    public function addEstadosClinica(EstadosClinica $estadosClinica): self
    {
        if (!$this->EstadosClinica->contains($estadosClinica)) {
            $this->EstadosClinica[] = $estadosClinica;
        }

        return $this;
    }

    public function removeEstadosClinica(EstadosClinica $estadosClinica): self
    {
        if ($this->EstadosClinica->contains($estadosClinica)) {
            $this->EstadosClinica->removeElement($estadosClinica);
        }

        return $this;
    }
}
