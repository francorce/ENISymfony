<?php

namespace App\Controller;

use App\Repository\EtatRepository;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SitesRepository;
use App\Repository\SortiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{

    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortiesRepository     $sortiesRepository,
                          SitesRepository       $sitesRepository,
                          EtatRepository        $etatRepository,
                          LieuxRepository       $lieuxRepository,
                          ParticipantRepository $participantRepository): Response
    {
        $participants = $participantRepository->findAll();
        $lieux = $lieuxRepository->findAll();
        $etats = $etatRepository->findAll();
        $sorties = $sortiesRepository->findAll();
        $sites = $sitesRepository->findAll();

        return $this->render('accueil/accueil.html.twig', [
            'sorties' => $sorties,
            'sites' => $sites,
            'etats' => $etats,
            'lieux' => $lieux,
            'participants' => $participants
        ]);
    }

}
