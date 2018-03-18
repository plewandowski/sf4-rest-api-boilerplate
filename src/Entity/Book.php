<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ORM\Table(name="book")
 * @UniqueEntity("isbn")
 */
class Book
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Exclude()
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Isbn()
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $isbn;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=200)
     */
    protected $title;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Type("float")
     * @Assert\GreaterThan(0)
     *
     * @ORM\Column(type="float", nullable=false)
     */
    protected $price;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Book
     */
    public function setId(int $id): Book
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * @param int $isbn
     * @return Book
     */
    public function setIsbn($isbn): Book
    {
        $this->isbn = $isbn;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Book
     */
    public function setTitle($title): Book
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Book
     */
    public function setPrice($price): Book
    {
        $this->price = $price;
        return $this;
    }

}