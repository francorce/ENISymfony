<?php

namespace App\Controller;

use App\Repository\SortiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortiesRepository $sortiesRepository): Response
    {
        $sorties = $sortiesRepository->findAll();

        return $this->render('accueil/accueil.html.twig', [
            'sorties' => $sorties
        ]);
    }
}
