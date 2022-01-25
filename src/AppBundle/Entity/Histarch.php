<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Histarch
 *
 * @ORM\Table(name="neosys.histarch")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HistarchRepository")
 */
class Histarch
{
    /**
     * @var int
     *
     * @ORM\Column(name="cod_int", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_carpet", type="string", length=20)
     */
    private $codCarpeta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_retiro", type="datetime")
     */
    private $fechaRetiro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_devuelto", type="datetime")
     */
    private $fechaDevolucion;

    /**
     * @var string
     *
     * @ORM\Column(name="leg", type="string", length=20)
     */
    private $legajo;


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
     * Set codCarpeta
     *
     * @param string $codCarpeta
     *
     * @return Histarch
     */
    public function setCodCarpeta($codCarpeta)
    {
        $this->codCarpeta = $codCarpeta;

        return $this;
    }

    /**
     * Get codCarpeta
     *
     * @return string
     */
    public function getCodCarpeta()
    {
        return $this->codCarpeta;
    }

    /**
     * Set fechaRetiro
     *
     * @param \DateTime $fechaRetiro
     *
     * @return Histarch
     */
    public function setFechaRetiro($fechaRetiro)
    {
        $this->fechaRetiro = $fechaRetiro;

        return $this;
    }

    /**
     * Get fechaRetiro
     *
     * @return \DateTime
     */
    public function getFechaRetiro()
    {
        return $this->fechaRetiro;
    }

    /**
     * Set fechaDevolucion
     *
     * @param \DateTime $fechaDevolucion
     *
     * @return Histarch
     */
    public function setFechaDevolucion($fechaDevolucion)
    {
        $this->fechaDevolucion = $fechaDevolucion;

        return $this;
    }

    /**
     * Get fechaDevolucion
     *
     * @return \DateTime
     */
    public function getFechaDevolucion()
    {
        return $this->fechaDevolucion;
    }

    /**
     * Set legajo
     *
     * @param string $legajo
     *
     * @return Histarch
     */
    public function setLegajo($legajo)
    {
        $this->legajo = $legajo;

        return $this;
    }

    /**
     * Get legajo
     *
     * @return string
     */
    public function getLegajo()
    {
        return $this->legajo;
    }
}

