<?php

namespace AppBundle\Entity;

use AppBundle\Common\JsonConvertible;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use NumberFormatter;

/**
 * @ORM\Entity
 * @ORM\Table(name="receipts")
 */
class Receipt implements JsonConvertible
{

    /**
     * Receipt constructor.
     */
    public function __construct()
    {
        $this->store = new Store();
        $this->articles = new ArrayCollection();
    }

    /**
     * @ORM\Column(name="id_receipt", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Store")
     * @ORM\JoinColumn(name="id_store", referencedColumnName="id_store")
     */
    private $store;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="receipt", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $articles;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @param mixed $store
     */
    public function setStore($store): void
    {
        $this->store = $store;
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param $article
     */
    public function addArticle($article)
    {
        $this->articles->add($article);
    }

    /**
     * @param $article
     */
    public function removeArticle($article) {
        $this->getArticles()->removeElement($article);
    }

    /**
     *
     */
    public function clearArticles()
    {
        foreach ($this->getArticles()->getValues() as $article)
        {
            $article->setReceipt(null);
        }
        $this->getArticles()->clear();
    }

    /**
     *
     */
    public function getTotal()
    {
        return array_reduce($this->getArticles()->getValues(), function ($carry, $item){
            $carry += $item->getTotalPrice();
            return $carry;
        });
    }

    /**
     * @return array
     */
    public function getJson()
    {
        $nf = new NumberFormatter("en", NumberFormatter::DECIMAL);
        $nf->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

        $data = array(
            "id" => $this->getId(),
            "store" => $this->getStore()->getJson(),
            "date" => $this->getDate()->format("d-m-Y"),
            "total" => $nf->format($this->getTotal()),
            "articles" => array()
        );

        foreach ($this->getArticles()->getValues() as $article)
        {
            array_push($data["articles"], $article->getJson());
        }
        return $data;
    }


}