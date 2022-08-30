<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Depcajas
 *
 * @ORM\Table(name="neosys.depcajas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepcajasRepository")
 */
class Depcajas
{
    /**
     * @var int
     *
     * @ORM\Column(name="cod_caja", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_estant", type="string", length=20)
     */
    private $codEstante;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_lado", type="string", length=20)
     */
    private $codLado;

    /**
     * @var string
     *
     * @ORM\Column(name="columna", type="string", length=20)
     */
    private $columna;

    /**
     * @var string
     *
     * @ORM\Column(name="piso", type="string", length=20)
     */
    private $piso;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_area", type="string", length=20)
     */
    private $codArea;

    /**
     * @var string
     *
     * @ORM\Column(name="tituloCaja", type="string", length=200)
     */
    private $tituloCaja;

    /**
     * @var string
     *
     * @ORM\Column(name="n_des_caja", type="string", length=20)
     */
    private $nroDesdeCaja;

    /**
     * @var string
     *
     * @ORM\Column(name="n_has_caja", type="string", length=20)
     */
    private $nroHastaCaja;

    /**
     * @var string
     *
     * @ORM\Column(name="observa", type="string", length=255)
     */
    private $observa;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(name="nro_caja", type="integer")
     */
    private $nroCaja;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_des_caja", type="datetime")
     */
    private $fechaDesdeCaja;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_has_caja", type="datetime")
     */
    private $fechaHastaCaja;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arch_hasta", type="datetime")
     */
    private $archivadoHasta;


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
     * Set codEstante
     *
     * @param string $codEstante
     *
     * @return Depcajas
     */
    public function setCodEstante($codEstante)
    {
        $this->codEstante = $codEstante;

        return $this;
    }

    /**
     * Get codEstante
     *
     * @return string
     */
    public function getCodEstante()
    {
        return $this->codEstante;
    }

    /**
     * Set codLado
     *
     * @param string $codLado
     *
     * @return Depcajas
     */
    public function setCodLado($codLado)
    {
        $this->codLado = $codLado;

        return $this;
    }

    /**
     * Get codLado
     *
     * @return string
     */
    public function getCodLado()
    {
        return $this->codLado;
    }

    /**
     * Set columna
     *
     * @param string $columna
     *
     * @return Depcajas
     */
    public function setColumna($columna)
    {
        $this->columna = $columna;

        return $this;
    }

    /**
     * Get columna
     *
     * @return string
     */
    public function getColumna()
    {
        return $this->columna;
    }

    /**
     * Set piso
     *
     * @param string $piso
     *
     * @return Depcajas
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * Get piso
     *
     * @return string
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set codArea
     *
     * @param string $codArea
     *
     * @return Depcajas
     */
    public function setCodArea($codArea)
    {
        $this->codArea = $codArea;

        return $this;
    }

    /**
     * Get codArea
     *
     * @return string
     */
    public function getCodArea()
    {
        return $this->codArea;
    }

    /**
     * Set tituloCaja
     *
     * @param string $tituloCaja
     *
     * @return Depcajas
     */
    public function setTituloCaja($tituloCaja)
    {
        $this->tituloCaja = $tituloCaja;

        return $this;
    }

    /**
     * Get tituloCaja
     *
     * @return string
     */
    public function getTituloCaja()
    {
        return $this->tituloCaja;
    }

    /**
     * Set nroDesdeCaja
     *
     * @param string $nroDesdeCaja
     *
     * @return Depcajas
     */
    public function setNroDesdeCaja($nroDesdeCaja)
    {
        $this->nroDesdeCaja = $nroDesdeCaja;

        return $this;
    }

    /**
     * Get nroDesdeCaja
     *
     * @return string
     */
    public function getNroDesdeCaja()
    {
        return $this->nroDesdeCaja;
    }

    /**
     * Set nroHastaCaja
     *
     * @param string $nroHastaCaja
     *
     * @return Depcajas
     */
    public function setNroHastaCaja($nroHastaCaja)
    {
        $this->nroHastaCaja = $nroHastaCaja;

        return $this;
    }

    /**
     * Get nroHastaCaja
     *
     * @return string
     */
    public function getNroHastaCaja()
    {
        return $this->nroHastaCaja;
    }

    /**
     * Set observa
     *
     * @param string $observa
     *
     * @return Depcajas
     */
    public function setObserva($observa)
    {
        $this->observa = $observa;

        return $this;
    }

    /**
     * Get observa
     *
     * @return string
     */
    public function getObserva()
    {
        return $this->observa;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return Depcajas
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }

     /**
     * Set nroCaja
     *
     * @param integer $nroCaja
     *
     * @return Depcajas
     */
    public function setNroCaja($nroCaja)
    {
        $this->nroCaja = $nroCaja;

        return $this;
    }

    /**
     * Get nroCaja
     *
     * @return int
     */
    public function getNroCaja()
    {
        return $this->nroCaja;
    }

    /**
     * Set fechaDesdeCaja
     *
     * @param \DateTime $fechaDesdeCaja
     *
     * @return Depcajas
     */
    public function setFechaDesdeCaja($fechaDesdeCaja)
    {
        $this->fechaDesdeCaja = $fechaDesdeCaja;

        return $this;
    }

    /**
     * Get fechaDesdeCaja
     *
     * @return \DateTime
     */
    public function getFechaDesdeCaja()
    {
        return $this->fechaDesdeCaja;
    }

    /**
     * Set fechaHastaCaja
     *
     * @param \DateTime $fechaHastaCaja
     *
     * @return Depcajas
     */
    public function setFechaHastaCaja($fechaHastaCaja)
    {
        $this->fechaHastaCaja = $fechaHastaCaja;

        return $this;
    }

    /**
     * Get fechaHastaCaja
     *
     * @return \DateTime
     */
    public function getFechaHastaCaja()
    {
        return $this->fechaHastaCaja;
    }

    /**
     * Set archivadoHasta
     *
     * @param \DateTime $archivadoHasta
     *
     * @return Depcajas
     */
    public function setArchivadoHasta($archivadoHasta)
    {
        $this->archivadoHasta = $archivadoHasta;

        return $this;
    }

    /**
     * Get archivadoHasta
     *
     * @return \DateTime
     */
    public function getArchivadoHasta()
    {
        return $this->archivadoHasta;
    }
}

