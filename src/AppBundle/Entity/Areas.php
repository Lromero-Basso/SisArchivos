<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Areas
 *
 * @ORM\Table(name="neosys.areas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AreasRepository")
 */
class Areas
{
    /**
     * @var int
     *
     * @ORM\Column(name="cod_area", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="nom_area", type="string", length=40)
     */
    private $nomArea;


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
     * Set nomArea
     *
     * @param string $nomArea
     *
     * @return Areas
     */
    public function setNomArea($nomArea)
    {
        $this->nomArea = $nomArea;

        return $this;
    }

    /**
     * Get nomArea
     *
     * @return string
     */
    public function getNomArea()
    {
        return $this->nomArea;
    }
}

