<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\Type\BookType;
use App\Repository\BookRepository;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\QueryParam;

/**
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */
class BooksController extends FOSRestController
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * BooksController constructor.
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param Request $request
     * @return Book|\Symfony\Component\Form\FormInterface
     * @throws \Doctrine\ORM\ORMException
     */
    public function postBookAction(Request $request)
    {
        $book = new Book();

        $form  = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $form->submit($request->request->all(), true);
        if ($form->isValid()) {
            $this->bookRepository->save($book);

            return $book;
        } else {
            return $form;
        }
    }

    /**
     * @param $isbn
     * @param Request $request
     * @return Book|\Symfony\Component\Form\FormInterface
     * @throws \Doctrine\ORM\ORMException
     */
    public function putBookAction($isbn, Request $request)
    {
        $book = $this->findBook($isbn);

        $form  = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $form->submit($request->request->all(), true);
        if ($form->isValid()) {
            $this->bookRepository->save($book);

            return $book;
        } else {
            return $form;
        }
    }

    /**
     * @param $isbn
     * @param Request $request
     * @return Book|\Symfony\Component\Form\FormInterface
     * @throws \Doctrine\ORM\ORMException
     */
    public function patchBookAction($isbn, Request $request)
    {
        $book = $this->findBook($isbn);

        $form  = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->bookRepository->save($book);

            return $book;
        } else {
            return $form;
        }
    }


    /**
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @QueryParam(name="pageSize", requirements="\d+", default="100", description="Page size.")
     * @param ParamFetcherInterface $paramFetcher
     * @return Book[]
     */
    public function getBooksAction(ParamFetcherInterface $paramFetcher)
    {
        $page =  $paramFetcher->get('page') ?? 1;
        $pageSize =  $paramFetcher->get('pageSize') ?? 100;

        $books = $this->bookRepository->findBy([], null, $pageSize, $pageSize * ($page-1));

        return $books;

    }

    /**
     * @param int $isbn
     * @return Book
     */
    public function getBookAction($isbn)
    {
        $book = $this->findBook($isbn);

        return $book;
    }

    /**
     * @param int $isbn
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteBookAction($isbn)
    {
        $book = $this->findBook($isbn);
        $this->bookRepository->delete($book);
    }

    /**
     * @param $isbn
     * @return Book
     * @throws NotFoundHttpException
     */
    protected function findBook($isbn): Book
    {
        if (!$book = $this->bookRepository->findOneBy([ 'isbn' => $isbn ])) {
            throw new NotFoundHttpException(
                sprintf('ISBN "%s" does not exist', $isbn)
            );
        }

        return $book;
    }
}