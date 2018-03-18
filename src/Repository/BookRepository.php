<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
    /**
     * @param Book $book
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Book $book)
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush($book);
    }

    /**
     * @param Book $book
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(Book $book)
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush($book);
    }
}