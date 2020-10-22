<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipoRepository")
 */
class Equipo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accesorios;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCompraEquipo;
    
    
    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $n_serie;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marca", cascade={"persist"})
     */
    private $marca;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modelo", cascade={"persist"})
     */
    private $Modelo;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PartNumber")
     */
    private $partnumber;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $color_lcd;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $top_cover;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $back_cover;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="equipo")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $ordenestrabajo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TipoEquipo", inversedBy="equipos")
     */
    private $tipoEquipo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $tipo_pantalla;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $tiquet;


    public function __construct()
    {
        $this->ordenestrabajo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccesorios(): ?string
    {
        return $this->accesorios;
    }

    public function setAccesorios(?string $accesorios): self
    {
        $this->accesorios = $accesorios;

        return $this;
    }

    public function getFechaCompraEquipo(): ?\DateTimeInterface
    {
        return $this->fechaCompraEquipo;
    }

    public function setFechaCompraEquipo(?\DateTimeInterface $fechaCompraEquipo): self
    {
        $this->fechaCompraEquipo = $fechaCompraEquipo;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMarca(): ?Marca
    {
        return $this->marca;
    }

    public function setMarca(?Marca $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    public function getModelo(): ?Modelo
    {
        return $this->Modelo;
    }

    public function setModelo(?Modelo $Modelo): self
    {
        $this->Modelo = $Modelo;

        return $this;
    }

    public function getPartnumber(): ?PartNumber
    {
        return $this->partnumber;
    }

    public function setPartnumber(?PartNumber $partnumber): self
    {
        $this->partnumber = $partnumber;

        return $this;
    }


    public function getColorLcd(): ?string
    {
        return $this->color_lcd;
    }

    public function setColorLcd(?string $color_lcd): self
    {
        $this->color_lcd = $color_lcd;

        return $this;
    }

    public function getTopCover(): ?string
    {
        return $this->top_cover;
    }

    public function setTopCover(?string $top_cover): self
    {
        $this->top_cover = $top_cover;

        return $this;
    }

    public function getBackCover(): ?string
    {
        return $this->back_cover;
    }

    public function setBackCover(?string $back_cover): self
    {
        $this->back_cover = $back_cover;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * @return Collection|OrdenTrabajo[]
     */
    public function getOrdenestrabajo(): Collection
    {
        return $this->ordenestrabajo;
    }

    public function addOrdenestrabajo(OrdenTrabajo $ordenestrabajo): self
    {
        if (!$this->ordenestrabajo->contains($ordenestrabajo)) {
            $this->ordenestrabajo[] = $ordenestrabajo;
            $ordenestrabajo->setEquipo($this);
        }

        return $this;
    }

    public function removeOrdenestrabajo(OrdenTrabajo $ordenestrabajo): self
    {
        if ($this->ordenestrabajo->contains($ordenestrabajo)) {
            $this->ordenestrabajo->removeElement($ordenestrabajo);
            // set the owning side to null (unless already changed)
            if ($ordenestrabajo->getEquipo() === $this) {
                $ordenestrabajo->setEquipo(null);
            }
        }

        return $this;
    }

    public function getTipoEquipo(): ?TipoEquipo
    {
        return $this->tipoEquipo;
    }

    public function setTipoEquipo(?TipoEquipo $tipoEquipo): self
    {
        $this->tipoEquipo = $tipoEquipo;

        return $this;
    }

    public function getTipoPantalla(): ?string
    {
        return $this->tipo_pantalla;
    }

    public function setTipoPantalla(string $tipo_pantalla): self
    {
        $this->tipo_pantalla = $tipo_pantalla;

        return $this;
    }

    public function getTiquet(): ?string
    {
        return $this->tiquet;
    }

    public function setTiquet(string $tiquet): self
    {
        $this->tiquet = $tiquet;

        return $this;
    }
    
    
}
