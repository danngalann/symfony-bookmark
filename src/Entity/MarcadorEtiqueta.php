<?php

namespace App\Entity;

use App\Repository\MarcadorEtiquetaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarcadorEtiquetaRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class MarcadorEtiqueta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Marcador::class, inversedBy="marcadorEtiquetas")
     */
    private $marcador;

    /**
     * @ORM\ManyToOne(targetEntity=Etiqueta::class, cascade={"persist"})
     */
    private $etiqueta;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarcador(): ?Marcador
    {
        return $this->marcador;
    }

    public function setMarcador(?Marcador $marcador): self
    {
        $this->marcador = $marcador;

        return $this;
    }

    public function getEtiqueta(): ?Etiqueta
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(?Etiqueta $etiqueta): self
    {
        $this->etiqueta = $etiqueta;

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

    /**
     * @ORM\PrePersist
     */
    public function setValorDefectoCreado(){
        $this->creado = new \DateTime();
    }
}
