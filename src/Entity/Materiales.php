<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialesRepository")
 */
class Materiales
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
     * @ORM\ManyToOne(targetEntity="App\Entity\EstadosCentral", inversedBy="materiales")
     */
    private $estado_central;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EstadosClinica", inversedBy="materiales")
     */
    private $estado_clinica;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_linea_presupuesto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ordentrabajo", inversedBy="materiales", cascade="remove")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $OrdenTrabajo;

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

    public function getEstadoCentral(): ?EstadosCentral
    {
        return $this->estado_central;
    }

    public function setEstadoCentral(?EstadosCentral $estado_central): self
    {
        $this->estado_central = $estado_central;

        return $this;
    }

    public function getEstadoClinica(): ?EstadosClinica
    {
        return $this->estado_clinica;
    }

    public function setEstadoClinica(?EstadosClinica $estado_clinica): self
    {
        $this->estado_clinica = $estado_clinica;

        return $this;
    }

    public function getIdLineaPresupuesto(): ?int
    {
        return $this->id_linea_presupuesto;
    }

    public function setIdLineaPresupuesto(int $id_linea_presupuesto): self
    {
        $this->id_linea_presupuesto = $id_linea_presupuesto;

        return $this;
    }

    public function getOrdenTrabajo(): ?Ordentrabajo
    {
        return $this->OrdenTrabajo;
    }

    public function setOrdenTrabajo(?Ordentrabajo $OrdenTrabajo): self
    {
        $this->OrdenTrabajo = $OrdenTrabajo;

        return $this;
    }
}
