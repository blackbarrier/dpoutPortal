<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(name="IDX_2265B05D4BAB96C", columns={"rol_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Usuario implements UserInterface {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dni", type="string", length=255, nullable=true)
     */
    private $dni;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="apellido", type="string", length=255, nullable=true)
     */
    private $apellido;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_alta", type="datetime", nullable=false)
     */
    private $fechaAlta;

    /**
     * @var int
     *
     * @ORM\Column(name="activo", type="integer", nullable=false)
     */
    private $activo;

    /**
     * @var \Rol
     *
     * @ORM\ManyToOne(targetEntity="Rol")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     * })
     */
    private $rol;
    

     
    
      /**
     * @var int
     *
     * @ORM\Column(name="borrado", type="integer", nullable=false)
     */
    private $borrado;
            
            
 

    
    
    public function __construct() {
       
    }        
    

    /**
     *
     * @ORM\PrePersist
     */
    public function prePersist() {
      
        $this->setFechaAlta(new \DateTime('now'));
        $this->setBorrado(0);
    }

    /**
     *
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getDni(): ?string {
        return $this->dni;
    }

    public function setDni(?string $dni) {
        $this->dni = trim($dni);

        return $this;
    }

    public function getNombre(): ?string {
        return $this->nombre;
    }

    public function setNombre(?string $nombre) {
        $this->nombre = ucfirst(trim($nombre));

        return $this;
    }

    public function getApellido(): ?string {
        return $this->apellido;
    }

    public function setApellido(?string $apellido) {
        $this->apellido = ucfirst(trim($apellido));

        return $this;
    }

    function getEmail() {
        return $this->email;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password) {
        $this->password = $password;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta) {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    function getActivo() {
        return $this->activo;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    public function getRol(): ?Rol {
        return $this->rol;
    }

    public function setRol(?Rol $rol) {
        $this->rol = $rol;

        return $this;
    }

    public function getUsername() {
        return (string) $this->getDni();
    }

    public function getSalt() {
        return null;
    }

    public function getRoles() {
        return $this->getRol()->getCredencial();
    }

    public function eraseCredentials() {
        
    }

    public function __toString() {
        return $this->apellido . ' ' . $this->nombre;
    }
    

 
    
    
        
    function getBorrado() {
        return $this->borrado;
    }

    function setBorrado($activo) {
        $this->borrado = $activo;
    }
 

   
    
     
    
    
}
