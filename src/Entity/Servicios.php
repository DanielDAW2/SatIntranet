<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiciosRepository")
 */
class Servicios
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAlta;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaBaja;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRenovacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigoPromo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TiposServicios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Solicitante;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $tiquet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(?\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fechaBaja;
    }

    public function setFechaBaja(?\DateTimeInterface $fechaBaja): self
    {
        $this->fechaBaja = $fechaBaja;

        return $this;
    }

    public function getFechaRenovacion(): ?\DateTimeInterface
    {
        return $this->fechaRenovacion;
    }

    public function setFechaRenovacion(?\DateTimeInterface $fechaRenovacion): self
    {
        $this->fechaRenovacion = $fechaRenovacion;

        return $this;
    }

    public function getCodigoPromo(): ?string
    {
        return $this->codigoPromo;
    }

    public function setCodigoPromo(string $codigoPromo): self
    {
        $this->codigoPromo = $codigoPromo;

        return $this;
    }

    public function getTipo(): ?TiposServicios
    {
        return $this->tipo;
    }

    public function setTipo(?TiposServicios $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getSolicitante(): ?Usuario
    {
        return $this->Solicitante;
    }

    public function setSolicitante(?Usuario $Solicitante): self
    {
        $this->Solicitante = $Solicitante;

        return $this;
    }

    public function getTiquet(): ?string
    {
        return $this->tiquet;
    }

    public function setTiquet(?string $tiquet): self
    {
        $this->tiquet = $tiquet;

        return $this;
    }
    
}
