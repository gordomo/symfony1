<?php

namespace App\Entity;

use App\Repository\CajaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CajaRepository::class)
 */
class Caja
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $ingreso;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $egreso;

    /**
     * @ORM\Column(type="boolean")
     */
    private $llevaTicket;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngreso(): ?float
    {
        return $this->ingreso;
    }

    public function setIngreso(float $ingreso): self
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    public function getEgreso(): ?float
    {
        return $this->egreso;
    }

    public function setEgreso(?float $egreso): self
    {
        $this->egreso = $egreso;

        return $this;
    }

    public function getLlevaTicket(): ?bool
    {
        return $this->llevaTicket;
    }

    public function setLlevaTicket(bool $llevaTicket): self
    {
        $this->llevaTicket = $llevaTicket;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }
}
