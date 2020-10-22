<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartNumberRepository")
 */
class PartNumber
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marca", inversedBy="partNumbers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marca;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modelo", inversedBy="partNumbers", cascade={"PERSIST"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Modelo;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $partnumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarca(): ?marca
    {
        return $this->marca;
    }

    public function setMarca(?marca $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    public function getModelo(): ?modelo
    {
        return $this->Modelo;
    }

    public function setModelo(?modelo $Modelo): self
    {
        $this->Modelo = $Modelo;

        return $this;
    }

    public function getPartnumber(): ?string
    {
        return $this->partnumber;
    }

    public function setPartnumber(string $partnumber): self
    {
        $this->partnumber = $partnumber;

        return $this;
    }
}
