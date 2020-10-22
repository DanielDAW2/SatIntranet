<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CierreReparacionesRepository")
 */
class CierreReparaciones
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrdenTrabajo", inversedBy="cierreReparaciones", cascade={"persist", "remove"})
     * @JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $Reparacion;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TiquetFactura", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $TiquetCierre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="cierreReparaciones")
     */
    private $Usuario;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $NOrden;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $comentario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReparacion(): ?OrdenTrabajo
    {
        return $this->Reparacion;
    }

    public function setReparacion(?OrdenTrabajo $Reparacion): self
    {
        $this->Reparacion = $Reparacion;

        return $this;
    }

    public function getTiquetCierre(): ?TiquetFactura
    {
        return $this->TiquetCierre;
    }

    public function setTiquetCierre(?TiquetFactura $TiquetCierre): self
    {
        $this->TiquetCierre = $TiquetCierre;

        return $this;
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

    public function getNOrden(): ?string
    {
        return $this->NOrden;
    }

    public function setNOrden(string $NOrden): self
    {
        $this->NOrden = $NOrden;

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

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }
    
    public function __construct()
    {
        $this->setFecha(new \DateTime());
        
    }
}
