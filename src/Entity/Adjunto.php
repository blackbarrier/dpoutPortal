<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Adjunto
 *
 * @ORM\Table(name="adjunto")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Adjunto
{
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string|null
     *
     * @ORM\Column(name="extension", type="string", length=255, nullable=true)
     */
    private $extension;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_alta", type="date", nullable=true)
     */
    private $fechaAlta;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="borrado", type="boolean", nullable=true, options={"comment"="0=NO, 1=SI"})
     */
    private $borrado = '0';

    /**
     * @var \Contenido
     *
     * @ORM\ManyToOne(targetEntity="Contenido",inversedBy="adjuntos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contenido_id", referencedColumnName="id")
     * })
     */
    private $contenido;

    /**
     * @var \TipoAdjunto
     *
     * @ORM\ManyToOne(targetEntity="TipoAdjunto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_adjunto_id", referencedColumnName="id")
     * })
     */
    private $tipoAdjunto;

    /**
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setFechaAlta(new \DateTime('now'));
        $this->setBorrado(0);
    }

    /**
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(?\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getBorrado(): ?bool
    {
        return $this->borrado;
    }

    public function setBorrado(?bool $borrado): self
    {
        $this->borrado = $borrado;

        return $this;
    }

    public function getContenido()
    {
        return $this->contenido;
    }

    public function setContenido(?Contenido $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getTipoAdjunto()
    {
        return $this->tipoAdjunto;
    }

    public function setTipoAdjunto(?TipoAdjunto $tipoAdjunto): self
    {
        $this->tipoAdjunto = $tipoAdjunto;

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
