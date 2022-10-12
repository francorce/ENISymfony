<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\AnnulationMotifType;
use App\Form\LieuType;
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
    public function editer(SortiesRepository $sortiesRepository, EtatRepository $etatRepository, LieuxRepository $lieuxRepository, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_sortie_ajouter') {

            $sortie = new Sortie();
            $sortie->setParticipant($this->getUser());
            $sortie->setArchived(false);


        } else {
            $sortie = $sortiesRepository->find($id);
            //$bien = $entityManager->getRepository(Bien::class)->find($request->attributes->get('id'));
        }

        $lieu = new lieu();
        $form2 = $this->createForm(LieuType::class, $lieu);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()) {
            $lieu = $form2->getData();
            $entityManager->persist($lieu);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Lieu ajouté avec succès'
            );
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
            'formulaireLieu' => $form2->createView(),
        ]);
    }

    #[Route('/sortie/publier/{id}', name: 'app_sortie_publier', requirements: ['id' => '\d+'])]
    public function publier(SortiesRepository $sortiesRepository, EtatRepository $etatRepository, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        $sortie = $sortiesRepository->find($id);

        if($sortie->getEtat()->getId() == 4) {
            $sortie->setEtat($etatRepository->find(3));

            $entityManager->persist($sortie);
            $entityManager->flush();
        }
            return $this->redirectToRoute('app_accueil');


        }

    #[Route('/sortie/annuler/{id}', name: 'app_sortie_annuler', requirements: ['id' => '\d+'])]
    public function supprimer(SortiesRepository $sortiesRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $sortie = $sortiesRepository->find($id);
        //send the id to the route app_sortie_motif_annuler
        return $this->redirectToRoute('app_sortie_motif_annuler', ['id' => $sortie->getId()]);
    }

    #[Route('/sortie/MotifAnnulation/{id}', name: 'app_sortie_motif_annuler', requirements: ['id' => '\d+'])]
    public function motifAnnuler(SortiesRepository $sortiesRepository, EtatRepository $etatRepository, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        $sortie = $sortiesRepository->find($id);

        $form = $this->createForm(AnnulationMotifType::class, $sortie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $site = $form->getData();

            $sortie->setArchived(true);
            $entityManager->persist($site);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Sortie archivé avec succès'
            );
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('sortie/annuler.html.twig', [
            'controller_name' => 'SortieController',
            'formulaireAnnulation' => $form->createView(),
            "sortie" => $sortie,
        ]);
    }


    #[Route('/sortie/inscription/{id}', name: 'app_sortie_inscription', requirements: ['id' => '\d+'])]
    public function inscription(SortiesRepository $sortiesRepository, ParticipantRepository $participantRepository, EtatRepository $etatRepository, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        $idu = $this->getUser()->getId();
        $participant = $participantRepository->find($idu);
        $sortie = $sortiesRepository->find($id);
        $sortie->addParticipant($participant);

        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/sortie/desinscription/{id}', name: 'app_sortie_desinscription', requirements: ['id' => '\d+'])]
    public function desinscription(SortiesRepository $sortiesRepository, ParticipantRepository $participantRepository, EtatRepository $etatRepository, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        $idu = $this->getUser()->getId();
        $participant = $participantRepository->find($idu);
        $sortie = $sortiesRepository->find($id);
        $sortie->removeParticipant($participant);

        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('app_accueil');
    }

}