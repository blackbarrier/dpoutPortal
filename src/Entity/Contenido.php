<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="contenido")
 * @ORM\Entity
 */
class Contenido
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="contenido_tag",
     *      joinColumns={@ORM\JoinColumn(name="contenido_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;
    
    

    /**
     * @var \Organismo
     *
     * @ORM\ManyToOne(targetEntity="Organismo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     * })
     */
    private $organismo;

    /**
     * @var int
     *
     * @ORM\Column(name="activo", type="integer", nullable=false)
     */
    private $activo;

    /**
     * @var \TipoContenido
     *
     * @ORM\ManyToOne(targetEntity="TipoContenido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_contenido_id", referencedColumnName="id")
     * })
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255, nullable=false)
     */
    private $texto;


    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $fecha;


    /**
     * @var  \DateTime|null
     *
     * @ORM\Column(name="publicado", type="datetime", nullable=false)
     */
    private $publicado;


    /**
     * @var \Adjunto
     *
     * @ORM\OneToMany(targetEntity="Adjunto", mappedBy="contenido", cascade={"persist", "remove"})
     */
    private $adjuntos;

    /**
     * @var int
     *
     * @ORM\Column(name="interno", type="integer", nullable=false)
     */
    private $interno;


    /**
     * @var string
     *
     * @ORM\Column(name="mail_contacto", type="string", length=255, nullable=false)
     */
    private $contacto;

     /**
     * @var int
     *
     * @ORM\Column(name="borrado", type="integer", nullable=false)
     */
    private $borrado;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=false)
     */
    private $hash;
    public function __construct()
    {
        $this->setFecha(new \DateTime());
        $this->adjuntos = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    function getActivo()
    {
        return $this->activo;
    }

    function setActivo($activo)
    {
        $this->activo = $activo;
    }



    public function __toString()
    {
        return $this->nombre;
    }

    function getOrganismo()
    {
        return $this->organismo;
    }

    function setOrganismo($organismo)
    {
        $this->organismo = $organismo;
    }

    function getTipo()
    {
        return $this->tipo;
    }

    function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    function getTexto()
    {
        return $this->texto;
    }

    function setTexto($texto)
    {
        $this->texto = $texto;
    }

    function getFecha()
    {
        return $this->fecha;
    }

    function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    function getPublicado()
    {
        return $this->publicado;
    }

    function setPublicado($fecha)
    {
        $this->publicado = $fecha;
    }

    public function getAdjuntos()
    {

        $archivosActivos = new ArrayCollection();

        foreach ($this->adjuntos as $file) {
            if ($file->getBorrado() == 0) {
                $archivosActivos->add($file);
            }
        }
        return $archivosActivos;
    }



    public function addAdjunto(?Adjunto $archivo)
    {

        if (!$this->adjuntos->contains($archivo)) {
            $this->adjuntos[] = $archivo;
            $archivo->setContenido($this);
        }
        return $this;
    }

    public function removeAdjunto(?Adjunto $archivo)
    {
        if ($this->adjuntos->contains($archivo)) {
            $this->adjuntos->remove($archivo->getId());
            $archivo->setContenido(null);
        }
        return $this;
    }


    function getInterno()
    {
        return $this->interno;
    }

    function setInterno($fecha)
    {
        $this->interno = $fecha;
    }


    function getContacto()
    {
        return $this->contacto;
    }

    function setContacto($contacto)
    {
        $this->contacto = $contacto;
    }
    
    function getBorrado(): int {
        return $this->borrado;
    }

    function setBorrado(int $borrado): self {
        $this->borrado = $borrado;
        return $this;
    }


    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }


    function getTags() {
        return $this->tags;
    }

    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
        return $this;
    }

    public function removeTag(Tag $tag)
    {
        if ($this->tags->contains($tag))
        {
            $this->tags->removeElement($tag);
        }
        return $this;

    }


    
    
    
    
    public function getTagsAsString()
    {
        $str = '';
        foreach ($this->getTags() as $tag) {
            $str.= $tag->getNombre(). ' | ';
        }
        
        return $str;
    }        
}
