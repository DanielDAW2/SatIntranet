<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DelegacionRepository")
 */
class Delegacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $codigopostal;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $provincia;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $contacto;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $idTr;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MaterialesDelegacion", mappedBy="delegacion", orphanRemoval=true)
     */
    private $materialesDelegacion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ordentrabajo", mappedBy="delegacion")
     */
    private $ordentrabajos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Usuario", mappedBy="delegacion", orphanRemoval=true)
     */
    private $usuarios;

    /**
     * @ORM\Column(type="integer")
     */
    private $poblacion;


    public function __construct()
    {
        $this->materialesDelegacion = new ArrayCollection();
        $this->ordentrabajos = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCodigopostal(): ?string
    {
        return $this->codigopostal;
    }

    public function setCodigopostal(?string $codigopostal): self
    {
        $this->codigopostal = $codigopostal;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getContacto(): ?string
    {
        return $this->contacto;
    }

    public function setContacto(?string $contacto): self
    {
        $this->contacto = $contacto;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIdTr(): ?int
    {
        return $this->idTr;
    }

    public function setIdTr(int $idTr): self
    {
        $this->idTr = $idTr;

        return $this;
    }

    /**
     * @return Collection|MaterialesDelegacion[]
     */
    public function getMaterialesDelegacion(): Collection
    {
        return $this->materialesDelegacion;
    }

    public function addMaterialesDelegacion(MaterialesDelegacion $materialesDelegacion): self
    {
        if (!$this->materialesDelegacion->contains($materialesDelegacion)) {
            $this->materialesDelegacion[] = $materialesDelegacion;
            $materialesDelegacion->setDelegacion($this);
        }

        return $this;
    }

    public function removeMaterialesDelegacion(MaterialesDelegacion $materialesDelegacion): self
    {
        if ($this->materialesDelegacion->contains($materialesDelegacion)) {
            $this->materialesDelegacion->removeElement($materialesDelegacion);
            // set the owning side to null (unless already changed)
            if ($materialesDelegacion->getDelegacion() === $this) {
                $materialesDelegacion->setDelegacion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ordentrabajo[]
     */
    public function getOrdentrabajos(): Collection
    {
        return $this->ordentrabajos;
    }

    public function addOrdentrabajo(Ordentrabajo $ordentrabajo): self
    {
        if (!$this->ordentrabajos->contains($ordentrabajo)) {
            $this->ordentrabajos[] = $ordentrabajo;
            $ordentrabajo->setDelegacion($this);
        }

        return $this;
    }

    public function removeOrdentrabajo(Ordentrabajo $ordentrabajo): self
    {
        if ($this->ordentrabajos->contains($ordentrabajo)) {
            $this->ordentrabajos->removeElement($ordentrabajo);
            // set the owning side to null (unless already changed)
            if ($ordentrabajo->getDelegacion() === $this) {
                $ordentrabajo->setDelegacion(null);
            }
        }

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
            $usuario->setDelegacion($this);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): self
    {
        if ($this->usuarios->contains($usuario)) {
            $this->usuarios->removeElement($usuario);
            // set the owning side to null (unless already changed)
            if ($usuario->getDelegacion() === $this) {
                $usuario->setDelegacion(null);
            }
        }

        return $this;
    }

    public function getPoblacion(): ?int
    {
        return $this->poblacion;
    }

    public function setPoblacion(int $poblacion): self
    {
        $this->poblacion = $poblacion;

        return $this;
    }

}
