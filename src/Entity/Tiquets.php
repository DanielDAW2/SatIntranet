<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\TiquetsRepository")
 */
class Tiquets
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * 
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      minMessage = "El tiquet debe contener 4 digitos",
     * )
     */
    private $numero;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Ordentrabajo", mappedBy="tiquet", cascade={"persist", "remove"})
     */
    private $ordentrabajo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Cupon;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cobrado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha( ?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getOrdentrabajo(): ?Ordentrabajo
    {
        return $this->ordentrabajo;
    }

    public function setOrdentrabajo(?Ordentrabajo $ordentrabajo): self
    {
        $this->ordentrabajo = $ordentrabajo;

        // set (or unset) the owning side of the relation if necessary
        $newTiquet = $ordentrabajo === null ? null : $this;
        if ($newTiquet !== $ordentrabajo->getTiquet()) {
            $ordentrabajo->setTiquet($newTiquet);
        }

        return $this;
    }

    public function getCupon(): ?bool
    {
        return $this->Cupon;
    }

    public function setCupon(bool $Cupon): self
    {
        $this->Cupon = $Cupon;

        return $this;
    }

    public function getCobrado(): ?bool
    {
        return $this->cobrado;
    }

    public function setCobrado(bool $cobrado): self
    {
        $this->cobrado = $cobrado;

        return $this;
    }


}
