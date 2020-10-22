<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdentrabajoRepository")
 */
class Ordentrabajo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Serie", inversedBy="ordentrabajos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $serie;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Usuario", inversedBy="ordentrabajos")
     */
    private $usuarios;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $indicaciones_cliente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $averias_detectadas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delegacion", inversedBy="ordentrabajos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delegacion;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prioridad", inversedBy="ordentrabajos")
     * @ORM\JoinColumn(nullable=true)
     */
    private $prioridad;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tipo", inversedBy="ordentrabajos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_entrada;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_salida;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_fin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $PresupuestoAceptado;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $Actualizar = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Situacion", inversedBy="ordentrabajos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $situacion;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $n_orden;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $n_caso;

    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $TrabajosaRealizar;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Materiales", mappedBy="OrdenTrabajo", cascade="persist")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $materiales;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $observaciones_int;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $n_presupuesto;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $accesorios;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Tiquets", inversedBy="ordentrabajo", cascade={"persist", "remove"})
     */
    private $tiquet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipo", inversedBy="ordenestrabajo", cascade={"PERSIST"})
     */
    private $equipo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CierreReparaciones", mappedBy="Reparacion", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $cierreReparaciones;


    /**
     * @return mixed
     */
    public function getAccesorios() : ?string
    {
        return $this->accesorios;
    }

    /**
     * @param mixed $accesorios
     */
    public function setAccesorios(string $accesorios) : self
    {
        $this->accesorios = $accesorios;
        
        return $this;
    }

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->fecha_entrada = new \DateTime();
        $this->fecha_salida = null;
        $this->materiales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerie(): ?Serie
    {
        return $this->serie;
    }

    public function setSerie(?Serie $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * @return Collection|Usuario[]
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): self
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): self
    {
        if ($this->usuarios->contains($usuario)) {
            $this->usuarios->removeElement($usuario);
        }

        return $this;
    }

    public function getIndicacionesCliente(): ?string
    {
        return $this->indicaciones_cliente;
    }

    public function setIndicacionesCliente(?string $indicaciones_cliente): self
    {
        $this->indicaciones_cliente = $indicaciones_cliente;

        return $this;
    }

    public function getAveriasDetectadas(): ?string
    {
        return $this->averias_detectadas;
    }

    public function setAveriasDetectadas(?string $averias_detectadas): self
    {
        $this->averias_detectadas = $averias_detectadas;

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

    public function getPrioridad(): ?Prioridad
    {
        return $this->prioridad;
    }

    public function setPrioridad(?Prioridad $prioridad): self
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    

    public function getTipo(): ?Tipo
    {
        return $this->tipo;
    }

    public function setTipo(?Tipo $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getFechaEntrada(): ?\DateTimeInterface
    {
        return $this->fecha_entrada;
    }

    public function setFechaEntrada(?\DateTimeInterface $fecha_entrada): self
    {
        $this->fecha_entrada = $fecha_entrada;

        return $this;
    }

    public function getFechaSalida(): ?\DateTimeInterface
    {
        return $this->fecha_salida;
    }

    public function setFechaSalida(?\DateTimeInterface $fecha_salida): self
    {
        $this->fecha_salida = $fecha_salida;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(?\DateTimeInterface $fecha_fin): self
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    public function getPresupuestoAceptado(): ?bool
    {
        return $this->PresupuestoAceptado;
    }

    public function setPresupuestoAceptado(bool $PresupuestoAceptado): self
    {
        $this->PresupuestoAceptado = $PresupuestoAceptado;

        return $this;
    }

    public function getActualizar(): ?bool
    {
        return $this->Actualizar;
    }

    public function setActualizar(?bool $Actualizar): self
    {
        $this->Actualizar = $Actualizar;

        return $this;
    }

    public function getSituacion(): ?Situacion
    {
        return $this->situacion;
    }

    public function setSituacion(?Situacion $situacion): self
    {
        $this->situacion = $situacion;

        return $this;
    }

    public function getNOrden(): ?string
    {
        return $this->n_orden;
    }

    public function setNOrden(?string $n_orden): self
    {
        $this->n_orden = $n_orden;

        return $this;
    }

    public function getNCaso(): ?string
    {
        return $this->n_caso;
    }

    public function setNCaso(string $n_caso): self
    {
        $this->n_caso = $n_caso;

        return $this;
    }

    
    public function getTrabajosaRealizar(): ?string
    {
        return $this->TrabajosaRealizar;
    }

    public function setTrabajosaRealizar(?string $TrabajosaRealizar): self
    {
        $this->TrabajosaRealizar = $TrabajosaRealizar;

        return $this;
    }

    public function getNSerie(): ?string
    {
        return $this->n_serie;
    }

    public function setNSerie(?string $n_serie): self
    {
        $this->n_serie = $n_serie;

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
            $materiale->setOrdenTrabajo($this);
        }

        return $this;
    }

    public function removeMateriale(Materiales $materiale): self
    {
        if ($this->materiales->contains($materiale)) {
            $this->materiales->removeElement($materiale);
            // set the owning side to null (unless already changed)
            if ($materiale->getOrdenTrabajo() === $this) {
                $materiale->setOrdenTrabajo(null);
            }
        }

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getObservacionesInt(): ?string
    {
        return $this->observaciones_int;
    }

    public function setObservacionesInt(?string $observaciones_int): self
    {
        $this->observaciones_int = $observaciones_int;

        return $this;
    }

    public function getNPresupuesto(): ?string
    {
        return $this->n_presupuesto;
    }

    public function setNPresupuesto(?string $n_presupuesto): self
    {
        $this->n_presupuesto = $n_presupuesto;

        return $this;
    }

    public function getTiquet(): ?Tiquets
    {
        return $this->tiquet;
    }

    public function setTiquet(?Tiquets $tiquet): self
    {
        $this->tiquet = $tiquet;

        return $this;
    }

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): self
    {
        $this->equipo = $equipo;

        return $this;
    }

    public function getCierreReparaciones(): ?CierreReparaciones
    {
        return $this->cierreReparaciones;
    }

    public function setCierreReparaciones(?CierreReparaciones $cierreReparaciones): self
    {
        $this->cierreReparaciones = $cierreReparaciones;

        // set (or unset) the owning side of the relation if necessary
        $newReparacion = $cierreReparaciones === null ? null : $this;
        if ($newReparacion !== $cierreReparaciones->getReparacion()) {
            $cierreReparaciones->setReparacion($newReparacion);
        }

        return $this;
    }
    
    


}
