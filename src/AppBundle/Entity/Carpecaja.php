<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carpecaja
 *
 * @ORM\Table(name="neosys.carpecaja")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarpecajaRepository")
 */
class Carpecaja
{
    /**
     * @var int
     *
     * @ORM\Column(name="cod_carpet", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nro_carpet", type="string", length=20)
     */
    private $nroCarpeta;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_caja", type="string", length=20)
     */
    private $codCaja;

    /**
     * @var string
     *
     * @ORM\Column(name="titulocarp", type="string", length=100)
     */
    private $tituloCarp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_des_carp", type="datetime")
     */
    private $fechaDesdeCarp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_has_carp", type="datetime")
     */
    private $fechaHastaCarp;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=40)
     */
    private $estado;

    /**
     * @var integer
     *
     * @ORM\Column(name="n_estado", type="integer")
     */
    private $nEstado;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nroCarpeta
     *
     * @param string $nroCarpeta
     *
     * @return Carpecaja
     */
    public function setNroCarpeta($nroCarpeta)
    {
        $this->nroCarpeta = $nroCarpeta;

        return $this;
    }

    /**
     * Get nroCarpeta
     *
     * @return string
     */
    public function getNroCarpeta()
    {
        return $this->nroCarpeta;
    }

    /**
     * Set codCaja
     *
     * @param string $codCaja
     *
     * @return Carpecaja
     */
    public function setCodCaja($codCaja)
    {
        $this->codCaja = $codCaja;

        return $this;
    }

    /**
     * Get codCaja
     *
     * @return string
     */
    public function getCodCaja()
    {
        return $this->codCaja;
    }

    /**
     * Set tituloCarp
     *
     * @param string $tituloCarp
     *
     * @return Carpecaja
     */
    public function setTituloCarp($tituloCarp)
    {
        $this->tituloCarp = $tituloCarp;

        return $this;
    }

    /**
     * Get tituloCarp
     *
     * @return string
     */
    public function getTituloCarp()
    {
        return $this->tituloCarp;
    }

    /**
     * Set fechaDesdeCarp
     *
     * @param \DateTime $fechaDesdeCarp
     *
     * @return Carpecaja
     */
    public function setFechaDesdeCarp($fechaDesdeCarp)
    {
        $this->fechaDesdeCarp = $fechaDesdeCarp;

        return $this;
    }

    /**
     * Get fechaDesdeCarp
     *
     * @return \DateTime
     */
    public function getFechaDesdeCarp()
    {
        return $this->fechaDesdeCarp;
    }

    /**
     * Set fechaHastaCarp
     *
     * @param \DateTime $fechaHastaCarp
     *
     * @return Carpecaja
     */
    public function setFechaHastaCarp($fechaHastaCarp)
    {
        $this->fechaHastaCarp = $fechaHastaCarp;

        return $this;
    }

    /**
     * Get fechaHastaCarp
     *
     * @return \DateTime
     */
    public function getFechaHastaCarp()
    {
        return $this->fechaHastaCarp;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Carpecaja
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

     /**
     * Set nEstado
     *
     * @param integer $nEstado
     *
     * @return Carpecaja
     */
    public function setNEstado($nEstado)
    {
        $this->nEstado = $nEstado;

        return $this;
    }

    /**
     * Get nEstado
     *
     * @return int
     */
    public function getNEstado()
    {
        return $this->nEstado;
    }
}

