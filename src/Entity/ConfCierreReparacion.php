<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfCierreReparacionRepository")
 */
class ConfCierreReparacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Situacion", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $SituacionOrigen;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Situacion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $SituacionFinal;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $pordefecto = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSituacionOrigen(): ?Situacion
    {
        return $this->SituacionOrigen;
    }

    public function setSituacionOrigen(Situacion $SituacionOrigen): self
    {
        $this->SituacionOrigen = $SituacionOrigen;

        return $this;
    }

    public function getSituacionFinal(): ?Situacion
    {
        return $this->SituacionFinal;
    }

    public function setSituacionFinal(?Situacion $SituacionFinal): self
    {
        $this->SituacionFinal = $SituacionFinal;

        return $this;
    }

    public function getPordefecto(): ?bool
    {
        return $this->pordefecto;
    }

    public function setPordefecto(bool $pordefecto): self
    {
        $this->pordefecto = $pordefecto;

        return $this;
    }

}
