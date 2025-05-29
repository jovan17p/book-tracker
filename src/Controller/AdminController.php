<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/change-role/{id}', name: 'admin_change_role')]
    public function changeRole(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Korisnik nije pronađen');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $roles = array_diff($user->getRoles(), ['ROLE_ADMIN']);
        } else {
            $roles = array_merge($user->getRoles(), ['ROLE_ADMIN']);
        }

        $user->setRoles($roles);
        $em->flush();

        return $this->redirectToRoute('admin_home');
    }

    #[Route('/users', name: 'admin_user_list')]
    public function listUsers(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}/make-admin', name: 'admin_make_user_admin')]
    public function makeUserAdmin(User $user, EntityManagerInterface $em): Response
    {
        $roles = $user->getRoles();

        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);
            $em->flush();
            $this->addFlash('success', 'Korisnik je sada admin.');
        } else {
            $this->addFlash('info', 'Korisnik je već admin.');
        }

        return $this->redirectToRoute('admin_user_list');
    }
}
