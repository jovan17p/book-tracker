<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}/like', name: 'comment_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(Comment $comment, EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();

        if ($comment->getLikes()->contains($user)) {
            $comment->removeLike($user);
        } else {
            $comment->addLike($user);
            $comment->removeDislike($user);
        }

        $em->flush();

        return $this->redirectToRoute('book_show', ['id' => $comment->getBook()->getId()]);
    }

    #[Route('/comment/{id}/dislike', name: 'comment_dislike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function dislike(Comment $comment, EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();

        if ($comment->getDislikes()->contains($user)) {
            $comment->removeDislike($user);
        } else {
            $comment->addDislike($user);
            $comment->removeLike($user); 
        }

        $em->flush();

        return $this->redirectToRoute('book_show', ['id' => $comment->getBook()->getId()]);
    }
}
