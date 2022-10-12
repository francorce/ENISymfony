<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieFiltreType;
use App\Repository\EtatRepository;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SitesRepository;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{

    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortiesRepository $sortiesRepository,PaginatorInterface $paginator, SitesRepository $sitesRepository, EtatRepository $etatRepository, LieuxRepository $lieuxRepository, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager, Request $request): Response
    {



        //if user not logged, redirect to login page
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser()->isActif() == 0) {
            return $this->redirectToRoute('app_welcome');
        }

        //if we are at least 7 days after DateCloture, set archived to 1
        $sorties = $sortiesRepository->findAll();
        foreach ($sorties as $sortie) {
            if ($sortie->getDateCloture() < new \DateTime('-31 days')) {
                $sortie->setArchived(1);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }

        //if date now > date cloture, set etat to 2
        //de ouvert à fermé
        $sorties = $sortiesRepository->findAll();
        foreach ($sorties as $sortie) {
            if ($sortie->getDatecloture() < new \DateTime() && $sortie->getEtat()->getId() == 3) {
                $sortie->setEtat($etatRepository->find(2));
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }

        //de en création à fermé si date cloture < date now
        $sorties = $sortiesRepository->findAll();
        foreach ($sorties as $sortie) {
            if ($sortie->getDatecloture() < new \DateTime() && $sortie->getEtat()->getId() == 1) {
                $sortie->setEtat($etatRepository->find(2));
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }


        //de ouvert à date création si la date de début est atteinte.
        $sorties = $sortiesRepository->findAll();
        foreach ($sorties as $sortie) {
            if ($sortie->getDatedebut() < new \DateTime() && $sortie->getEtat()->getId() == 3) {
                $sortie->setEtat($etatRepository->find(1));
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }

        $search = new Sortie();
        $form = $this->createForm(SortieFiltreType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $sortiesRepository->findAllVisibleQuery($request->get('nom')),
            $request->query->getInt('page', 1),
            12
        );



        $sorties = $sortiesRepository->findAllVisibleQuery($request->get('nom'),['archived' => 0]);
        $participants = $participantRepository->findAll();
//        $lieux = $lieuxRepository->findAll();
//        $etats = $etatRepository->findAll();
        $sites = $sitesRepository->findAll();
        return $this->render('accueil/accueil.html.twig', [
            'sorties' => $sorties,
            'sites' => $sites,
//            'etats' => $etats,
//            'lieux' => $lieux,
            'properties' => $properties,
            'participants' => $participants,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sortie/archive', name: 'app_sortie_archive')]
    public function archived(SortiesRepository $sortiesRepository, SitesRepository $sitesRepository, EtatRepository $etatRepository, LieuxRepository $lieuxRepository, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager): Response
    {
        $sorties = $sortiesRepository->findBy(['archived' => 1]);


        return $this->render('accueil/archive.html.twig', [
            'sorties' => $sorties,
        ]);
    }


}
