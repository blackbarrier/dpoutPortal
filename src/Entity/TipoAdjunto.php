<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoAdjunto
 *
 * @ORM\Table(name="tipo_adjunto")
 * @ORM\Entity
 */
class TipoAdjunto
{

    const GENERICO = 1;


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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true, options={"default"="NULL"})
     */
    private $nombre = 'NULL';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="borrado", type="boolean", nullable=true)
     */
    private $borrado = '0';

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

    public function getBorrado(): ?bool
    {
        return $this->borrado;
    }

    public function setBorrado(?bool $borrado): self
    {
        $this->borrado = $borrado;

        return $this;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
