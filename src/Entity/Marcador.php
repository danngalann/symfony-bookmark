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
     * @ORM\OneToMany(targetEntity=MarcadorEtiqueta::class, mappedBy="marcador")
     */
    private $marcadorEtiquetas;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->marcadorEtiquetas = new ArrayCollection();
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
     * @return Collection|MarcadorEtiqueta[]
     */
    public function getMarcadorEtiquetas(): Collection
    {
        return $this->marcadorEtiquetas;
    }

    public function addMarcadorEtiqueta(MarcadorEtiqueta $marcadorEtiqueta): self
    {
        if (!$this->marcadorEtiquetas->contains($marcadorEtiqueta)) {
            $this->marcadorEtiquetas[] = $marcadorEtiqueta;
            $marcadorEtiqueta->setMarcador($this);
        }

        return $this;
    }

    public function removeMarcadorEtiqueta(MarcadorEtiqueta $marcadorEtiqueta): self
    {
        if ($this->marcadorEtiquetas->contains($marcadorEtiqueta)) {
            $this->marcadorEtiquetas->removeElement($marcadorEtiqueta);
            // set the owning side to null (unless already changed)
            if ($marcadorEtiqueta->getMarcador() === $this) {
                $marcadorEtiqueta->setMarcador(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
