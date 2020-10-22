<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarcaRepository")
 */
class Marca implements \Serializable
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
     * @ORM\OneToMany(targetEntity="App\Entity\Modelo", mappedBy="marca", orphanRemoval=true)
     */
    private $modelos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartNumber", mappedBy="marca")
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
        $this->modelos = new ArrayCollection();
        $this->partNumbers = new ArrayCollection();
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
     * @return Collection|Modelo[]
     */
    public function getModelos(): Collection
    {
        return $this->modelos;
    }

    public function addModelo(Modelo $modelo): self
    {
        if (!$this->modelos->contains($modelo)) {
            $this->modelos[] = $modelo;
            $modelo->setMarca($this);
        }

        return $this;
    }

    public function removeModelo(Modelo $modelo): self
    {
        if ($this->modelos->contains($modelo)) {
            $this->modelos->removeElement($modelo);
            // set the owning side to null (unless already changed)
            if ($modelo->getMarca() === $this) {
                $modelo->setMarca(null);
            }
        }

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
            $partNumber->setMarca($this);
        }

        return $this;
    }

    public function removePartNumber(PartNumber $partNumber): self
    {
        if ($this->partNumbers->contains($partNumber)) {
            $this->partNumbers->removeElement($partNumber);
            // set the owning side to null (unless already changed)
            if ($partNumber->getMarca() === $this) {
                $partNumber->setMarca(null);
            }
        }

        return $this;
    }
    public function serialize()
    {
        return json_encode(array(
                    "marca"=>$this->getNombre() 
        ));
            
            
        
        
        
    }

    public function unserialize($serialized)
    {}

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
