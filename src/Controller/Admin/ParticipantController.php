<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\SitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class ParticipantController extends AbstractController
{
    #[Route('/admin/participant', name: 'app_admin_participant')]
    public function index(ParticipantRepository $participantRepository, SitesRepository $sitesRepository): Response
    {
        $sites = $sitesRepository->findAll();
        $participants = $participantRepository->findAll();
        return $this->render('admin/participant/participant.html.twig', [
            "participants" => $participants,
            "sites" => $sites,
        ]);
    }

    #[Route('/admin/participant/supprimer/{id}', name: 'app_admin_participant_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(ParticipantRepository $participantRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $participant = $participantRepository->find($id);
        if ($participant) {
            $entityManager->remove($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Le lieu a bien été supprimé');
        } else {
            $this->addFlash('danger', 'La lieu n\'existe pas');
        }
        return $this->redirectToRoute('app_admin_participant');
    }

    #[Route('/admin/participant/ajouter', name: 'app_admin_participant_ajouter')]
    #[Route('/admin/participant/modifier/{id}', name: 'app_admin_participant_modifier', requirements: ['id' => '\d+'])]
    public function editer(UserPasswordHasherInterface $hasher,SluggerInterface $slugger,ParticipantRepository $participantRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_admin_participant_ajouter') {
            $participant = new Participant();

        } else {
            $participant = $participantRepository->find($id);
            //$bien = $entityManager->getRepository(Bien::class)->find($request->attributes->get('id'));
        }

        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();


            $participant->setRoles(['ROLE_USER']);

            $entityManager->persist($lieu);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_admin_participant_ajouter') {
                $this->addFlash(
                    'success',
                    'participant ajouté avec succès'
                );
            } else {
                $this->addFlash(
                    'success',
                    'participant modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_admin_participant');
        }
        return $this->render('admin/participant/editerParticipant.html.twig', [
            'controller_name' => 'ParticipantController',
            'formulaireParticipant' => $form->createView(),
        ]);
    }
}
