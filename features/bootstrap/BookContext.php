<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;

/**
 * Class BookContext
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */
class BookContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Given there are Books with the following details:
     * @param TableNode $books
     */
    public function thereAreRestUsersWithTheFollowingDetails(TableNode $books)
    {
        foreach ($books->getColumnsHash() as $key => $val) {
            $book = new Book();

            $book->setId($val['id']);
            $book->setTitle($val['title']);
            $book->setIsbn($val['isbn']);
            $book->setPrice($val['price']);

            $this->em->persist($book);
        }

        $this->em->flush();
    }
}