<?php

namespace App\Controller;

use App\Repository\SortiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie', name: 'app_sortie')]
    public function index(SortiesRepository $sortiesRepository): Response
    {
        $sorties = $sortiesRepository->findAll();
        return $this->render('sortie/index.html.twig', [
            "sorties" => $sorties,
        ]);
    }
}
