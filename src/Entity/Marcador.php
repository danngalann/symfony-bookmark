<?php

namespace App\Entity;

use App\Validator as AppAssert;
use App\Repository\MarcadorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=MarcadorRepository::class)
 */
class Marcador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Url
     * @AppAssert\UrlAccesible
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $categoria;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creado;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $favorito;

    /**
     * @ORM\ManyToMany(targetEntity=Etiqueta::class, cascade={"persist"})
     */
    private $etiqueta;

    public function __construct()
    {
        $this->etiqueta = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function setValorDefecto(){
        $this->creado = new \Datetime();
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

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getCreado(): ?\DateTimeInterface
    {
        return $this->creado;
    }

    public function setCreado(\DateTimeInterface $creado): self
    {
        $this->creado = $creado;

        return $this;
    }

    public function getFavorito(): ?bool
    {
        return $this->favorito;
    }

    public function setFavorito(?bool $favorito): self
    {
        $this->favorito = $favorito;

        return $this;
    }

    /**
     * @return Collection|Etiqueta[]
     */
    public function getEtiqueta(): Collection
    {
        return $this->etiqueta;
    }

    public function addEtiquetum(Etiqueta $etiquetum): self
    {
        if (!$this->etiqueta->contains($etiquetum)) {
            $this->etiqueta[] = $etiquetum;
        }

        return $this;
    }

    public function removeEtiquetum(Etiqueta $etiquetum): self
    {
        if ($this->etiqueta->contains($etiquetum)) {
            $this->etiqueta->removeElement($etiquetum);
        }

        return $this;
    }
}
