<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PaginationService;
use Knp\Component\Pager\PaginatorInterface;
#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    private $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $pageSize = 5;
        $results = $userRepository->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

        $pagination = $this->paginationService->paginate(
            $results,
            $request->query->getInt('page', 1),
            $pageSize
        );

        return $this->render('admin/admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }
    // public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator): Response
    // {
    //     // Configurez le nombre d'avis à afficher par page
    // $pageSize = 5;

    // // Créer la requête pour récupérer les avis triés par ID croissant
    // $query = $userRepository->createQueryBuilder('a')
    //     ->orderBy('a.id', 'ASC')
    //     ->getQuery();

    // // Obtenir les résultats de la requête
    // $results = $query->getResult();

    // // Paginer les résultats
    // $pagination = $paginator->paginate(
    //     $results,
    //     $request->query->getInt('page', 1), // Numéro de page par défaut
    //     $pageSize // Nombre d'éléments par page
    // );
    //     return $this->render('admin/admin_user/index.html.twig', [
    //         'users' => $userRepository->findAll(),
    //         'pagination' => $pagination,
    //     ]);
    //     }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
