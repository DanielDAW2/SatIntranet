<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $apellidos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ordentrabajo", mappedBy="usuarios")
     */
    private $ordentrabajos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delegacion", inversedBy="usuarios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delegacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NotasServicios", mappedBy="Usuario")
     */
    private $notasServicios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CierreReparaciones", mappedBy="Usuario")
     */
    private $cierreReparaciones;

    public function __construct()
    {
        $this->ordentrabajos = new ArrayCollection();
        $this->isActive = true;
        $this->notasServicios = new ArrayCollection();
        $this->cierreReparaciones = new ArrayCollection();
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

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
            $ordentrabajo->addUsuario($this);
        }

        return $this;
    }

    public function removeOrdentrabajo(Ordentrabajo $ordentrabajo): self
    {
        if ($this->ordentrabajos->contains($ordentrabajo)) {
            $this->ordentrabajos->removeElement($ordentrabajo);
            $ordentrabajo->removeUsuario($this);
        }

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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
    
    // serialize and unserialize must be updated - see below
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ) = unserialize($serialized);
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|NotasServicios[]
     */
    public function getNotasServicios(): Collection
    {
        return $this->notasServicios;
    }

    public function addNotasServicio(NotasServicios $notasServicio): self
    {
        if (!$this->notasServicios->contains($notasServicio)) {
            $this->notasServicios[] = $notasServicio;
            $notasServicio->setUsuario($this);
        }

        return $this;
    }

    public function removeNotasServicio(NotasServicios $notasServicio): self
    {
        if ($this->notasServicios->contains($notasServicio)) {
            $this->notasServicios->removeElement($notasServicio);
            // set the owning side to null (unless already changed)
            if ($notasServicio->getUsuario() === $this) {
                $notasServicio->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CierreReparaciones[]
     */
    public function getCierreReparaciones(): Collection
    {
        return $this->cierreReparaciones;
    }

    public function addCierreReparacione(CierreReparaciones $cierreReparacione): self
    {
        if (!$this->cierreReparaciones->contains($cierreReparacione)) {
            $this->cierreReparaciones[] = $cierreReparacione;
            $cierreReparacione->setUsuario($this);
        }

        return $this;
    }

    public function removeCierreReparacione(CierreReparaciones $cierreReparacione): self
    {
        if ($this->cierreReparaciones->contains($cierreReparacione)) {
            $this->cierreReparaciones->removeElement($cierreReparacione);
            // set the owning side to null (unless already changed)
            if ($cierreReparacione->getUsuario() === $this) {
                $cierreReparacione->setUsuario(null);
            }
        }

        return $this;
    }

}
