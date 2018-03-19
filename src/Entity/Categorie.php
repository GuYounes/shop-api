<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Type("int")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="text", length=65535, nullable=false)
     *
     * @JMS\Type("string")
     */
    private $libelle;

    /**
     * Categorie constructor.
     * @param int $id
     * @param string $libelle
     */
    public function __construct($id, $libelle)
    {
        $this->setId($id);
        $this->setLibelle($libelle);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }
}
