<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function profile(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $books = $em->getRepository(Book::class)->findBy(['owner' => $user]);
        $comments = $em->getRepository(Comment::class)->findBy(['user' => $user]);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'books' => $books,
            'comments' => $comments,
        ]);
    }
}
