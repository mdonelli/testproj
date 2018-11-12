<?php

namespace AppBundle\Entity;

use AppBundle\Common\JsonConvertible;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="articles")
 */
class Article implements JsonConvertible
{
    /**
     * @ORM\Column(name="id_article", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $volume;

    /**
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Receipt", inversedBy="articles")
     * @ORM\JoinColumn(name="id_receipt", referencedColumnName="id_receipt")
     */
    private $receipt;

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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param mixed $volume
     */
    public function setVolume($volume): void
    {
        $this->volume = $volume;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName(),
            "volume" => $this->getVolume(),
            "price" => $this->getPrice()
        );
    }

    /**
     * @return mixed
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @param mixed $receipt
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * @return float|int
     */
    public function getTotalPrice() {
        return $this->getVolume() * $this->getPrice();
    }




}