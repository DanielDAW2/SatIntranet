<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotasServiciosRepository")
 */
class NotasServicios
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="notasServicios")
     */
    private $Usuario;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $Nota;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Servicios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Servicio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->Usuario;
    }

    public function setUsuario(?Usuario $Usuario): self
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getNota(): ?string
    {
        return $this->Nota;
    }

    public function setNota(string $Nota): self
    {
        $this->Nota = $Nota;

        return $this;
    }

    public function getServicio(): ?Servicios
    {
        return $this->Servicio;
    }

    public function setServicio(?Servicios $Servicio): self
    {
        $this->Servicio = $Servicio;

        return $this;
    }
}
