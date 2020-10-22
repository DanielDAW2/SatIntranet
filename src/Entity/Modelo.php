<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeloRepository")
 */
class Modelo
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Marca", inversedBy="modelos", cascade={"PERSIST"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $marca;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartNumber", mappedBy="Modelo")
     */
    private $partNumbers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idTR;

    public function __construct()
    {
        $this->partNumbers = new ArrayCollection();
        $this->setVisible(false);
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

    public function getMarca(): ?Marca
    {
        return $this->marca;
    }

    public function setMarca(?Marca $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * @return Collection|PartNumber[]
     */
    public function getPartNumbers(): Collection
    {
        return $this->partNumbers;
    }

    public function addPartNumber(PartNumber $partNumber): self
    {
        if (!$this->partNumbers->contains($partNumber)) {
            $this->partNumbers[] = $partNumber;
            $partNumber->setModelo($this);
        }

        return $this;
    }

    public function removePartNumber(PartNumber $partNumber): self
    {
        if ($this->partNumbers->contains($partNumber)) {
            $this->partNumbers->removeElement($partNumber);
            // set the owning side to null (unless already changed)
            if ($partNumber->getModelo() === $this) {
                $partNumber->setModelo(null);
            }
        }

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getIdTR(): ?int
    {
        return $this->idTR;
    }

    public function setIdTR(?int $idTR): self
    {
        $this->idTR = $idTR;

        return $this;
    }
}
