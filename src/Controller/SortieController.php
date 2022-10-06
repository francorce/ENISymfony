<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SitesRepository;
use App\Repository\SortiesRepository;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SortieController extends AbstractController
{
    #[Route('/sortie/{id}', name: 'app_sortie_detail', requirements: ['id' => '\d+'])]
    public function index(SortiesRepository $sortiesRepository, ParticipantRepository $participantRepository, SitesRepository $sitesRepository, VillesRepository $villesRepository, LieuxRepository $lieuxRepository, $id = null): Response
    {
        $sites = $sitesRepository->findAll();
        $villes = $villesRepository->findAll();
        $lieux = $lieuxRepository->findAll();
        $sortie = $sortiesRepository->find($id);
        $participants = $participantRepository->findAll();
        $sorties = $sortiesRepository->findAll();
        return $this->render('sortie/index.html.twig', [
            "sites" => $sites,
            "ville" => $villes,
            "lieux" => $lieux,
            "sortie" => $sortie,
            "participants" => $participants,
            "sorties" => $sorties,
        ]);
    }

    #[Route('/sortie/ajouter', name: 'app_sortie_ajouter')]
    #[Route('/sortie/modifier/{id}', name: 'app_sortie_modifier', requirements: ['id' => '\d+'])]
    public function editer(SortiesRepository $sortiesRepository, EtatRepository $etatRepository, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_sortie_ajouter') {

            $sortie = new Sortie();


        } else {
            $sortie = $sortiesRepository->find($id);
            //$bien = $entityManager->getRepository(Bien::class)->find($request->attributes->get('id'));
        }

        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('urlPhoto')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $sortie->setUrlPhoto($newFilename);
            }

            $sortie = $form->getData();

            $sortie->setEtat($etatRepository->find(4));

            $entityManager->persist($sortie);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_sortie_ajouter') {
                $this->addFlash(
                    'success',
                    'Sortie ajouté avec succès'
                );
            } else {
                $this->addFlash(
                    'success',
                    'Sortie modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('sortie/editerSortie.html.twig', [
            'controller_name' => 'SortieController',
            'formulaireSortie' => $form->createView(),
        ]);
    }

    #[Route('/sortie/publier/{id}', name: 'app_sortie_publier', requirements: ['id' => '\d+'])]
    public function publier(SortiesRepository $sortiesRepository, EtatRepository $etatRepository, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

            $sortie = $sortiesRepository->find($id);

            $sortie->setEtat($etatRepository->find(3));

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }


}