<?php

namespace App\Controller;

use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile', requirements: ['id' => '\d+'])]
    public function index(ParticipantRepository $participantRepository, $id = null): Response
    {
        $participants = $participantRepository->find($id);
        $profile = $this->getUser();

        return $this->render('profile/profile.html.twig', [
            'id'=>$id,
            'participants' => $participants,
            'profile'=>$profile
        ]);
    }

    #[Route('/profileModifier', name: 'app_profile_modifier')]
    public function modif(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, Request $request, ParticipantRepository $participantRepository): Response
    {
        $participants = $this->getUser();
        $form = $this->createForm(ParticipantType::class, $participants);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();

            $participants->setPassword($hasher->hashPassword($participants, $participants->getPassword()));
            $participants->setRoles(['ROLE_USER']);

            $entityManager->persist($lieu);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_profile') {
                $this->addFlash(
                    'success',
                    'participant modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('profile/profileModif.html.twig', [
            'controller_name' => 'ProfileController',
            'formulaireProfile' => $form->createView(),

        ]);
    }
}
