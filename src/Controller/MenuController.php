<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\Menu1Type;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/menu')]
class MenuController extends AbstractController
{
    #[Route('/', name: 'app_menu_index', methods: ['GET'])]
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->render('menu/index.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_menu_show', methods: ['GET'])]
    public function show(Menu $menu, MenuRepository $menuRepository): Response
    {
        $formules=$menu->getFormule();
        return $this->render('menu/show.html.twig', [
            'menu' => $menu,
            'formules' => $formules,
            'menus' => $menuRepository->findAll(),
        ]);
    }
}
