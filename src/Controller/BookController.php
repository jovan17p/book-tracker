<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BookType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BookController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('book/home.html.twig');
    }

    #[Route('/books', name: 'book_list')]
    public function list(Request $request, EntityManagerInterface $em): Response
    {
        $sort = $request->query->get('sort');
        $search = $request->query->get('search');

        $repo = $em->getRepository(Book::class);
        $qb = $repo->createQueryBuilder('b');

        if ($search) {
            $qb->andWhere('LOWER(b.title) LIKE :search OR LOWER(b.author) LIKE :search')
               ->setParameter('search', '%' . strtolower($search) . '%');
        }

        switch ($sort) {
            case 'title_asc':
                $qb->orderBy('b.title', 'ASC');
                break;
            case 'title_desc':
                $qb->orderBy('b.title', 'DESC');
                break;
            case 'date_newest':
                $qb->orderBy('b.createdAt', 'DESC');
                break;
            case 'date_oldest':
                $qb->orderBy('b.createdAt', 'ASC');
                break;
            default:
                $qb->orderBy('b.id', 'DESC');
                break;
        }

        $books = $qb->getQuery()->getResult();

        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/my-books', name: 'my_books')]
    public function myBooks(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $books = $em->getRepository(Book::class)->createQueryBuilder('b')
            ->where('b.owner = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('book/my_books.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/books/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->setCreatedAt(new \DateTimeImmutable());
            $book->setOwner($this->getUser());
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/books/{id}', name: 'book_show')]
public function show(int $id, EntityManagerInterface $em, Request $request): Response
{
    $book = $em->getRepository(Book::class)->find($id);

    if (!$book) {
        throw $this->createNotFoundException('Knjiga nije pronađena.');
    }

    $book->incrementViews();
    $em->flush();

    $comment = new Comment();
    $comment->setBook($book);
    $comment->setUser($this->getUser());
    $comment->setCreatedAt(new \DateTimeImmutable());

    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($comment);
        $em->flush();
        return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
    }

    return $this->render('book/show.html.twig', [
        'book' => $book,
        'form' => $form->createView(),
    ]);
}


    #[Route('/books/{id}/like', name: 'book_like')]
    public function likeBook(Book $book, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($book->getLikes()->contains($user)) {
            $book->removeLike($user);
        } else {
            $book->addLike($user);
            $book->removeDislike($user);
        }

        $em->flush();
        return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
    }

    #[Route('/books/{id}/dislike', name: 'book_dislike')]
    public function dislikeBook(Book $book, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($book->getDislikes()->contains($user)) {
            $book->removeDislike($user);
        } else {
            $book->addDislike($user);
            $book->removeLike($user);
        }

        $em->flush();
        return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
    }

    #[Route('/books/{id}/edit', name: 'book_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $book = $em->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Knjiga nije pronađena.');
        }

        if ($book->getOwner() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Nemate pravo da menjate ovu knjigu.');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/books/{id}/delete', name: 'book_delete')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $book = $em->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Knjiga nije pronađena.');
        }

        if ($book->getOwner() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Nemate pravo da obrišete ovu knjigu.');
        }

        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('book_list');
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Nemate pristup ovom delu sajta.');
        }

        return $this->render('admin/dashboard.html.twig');
    }
}
