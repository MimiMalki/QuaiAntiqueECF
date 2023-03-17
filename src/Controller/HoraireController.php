<?php

namespace App\Controller;

use App\Entity\Horaire;
use App\Repository\HoraireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HoraireController extends AbstractController
{

    public function getHoraire(HoraireRepository $horaireRepository): Response
    {
        return $this->render('_partials/_footer.html.twig', [
            'horaires' => $horaireRepository->findAll(),
        ]);
    }
}
