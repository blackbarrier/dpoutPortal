<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoEnlace
 *
 * @ORM\Table(name="tipo_contenido")
 * @ORM\Entity
 */
class TipoContenido
{
    const TIPO_CONTENIDO = 1;
    const TIPO_BIBLIOTECA = 2;



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
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

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

    public function __toString()
    {
        return $this->nombre;
    }
}
