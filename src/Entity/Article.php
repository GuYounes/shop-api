<?php

namespace App\Entity;

use App\EntityValidator\CategorieValidator;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="id_categorie", columns={"id_categorie"})})
 * @ORM\Entity
 */
class Article
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
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     *
     * @JMS\Type("float")
     */
    private $prix;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id")
     * })
     *
     * @JMS\Type("App\Entity\Categorie")
     */
    private $categorie;

    /**
     * Article constructor.
     * @param int $id
     * @param string $libelle
     * @param float $prix
     * @param Categorie $categorie
     */
    public function __construct($id, $libelle, $prix, Categorie $categorie)
    {
        $this->setId($id);
        $this->setLibelle($libelle);
        $this->setPrix($prix);
        $this->setCategorie($categorie);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
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

    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param Categorie $categorie
     */
    public function setCategorie(Categorie $categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @param Article $article
     */
    public function replaceWith(Article $article)
    {
        $this->setPrix($article->getPrix());
        $this->setLibelle($article->getLibelle());
        $this->setCategorie($article->getCategorie());
    }

}
