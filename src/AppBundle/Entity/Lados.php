<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lados
 *
 * @ORM\Table(name="neosys.lados")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LadosRepository")
 */
class Lados
{
    /**
     * @var int
     *
     * @ORM\Column(name="cod_lado", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_lado", type="string", length=40)
     */
    private $nombreLado;


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
     * Set nombreLado
     *
     * @param string $nombreLado
     *
     * @return Lados
     */
    public function setNombreLado($nombreLado)
    {
        $this->nombreLado = $nombreLado;

        return $this;
    }

    /**
     * Get nombreLado
     *
     * @return string
     */
    public function getNombreLado()
    {
        return $this->nombreLado;
    }
}

