<?php

namespace App\Controller;

use App\Form\ModifierMotDePasseProfileType;
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
            'id' => $id,
            'participants' => $participants,
            'profile' => $profile
        ]);
    }

    #[Route('/profileModifier', name: 'app_profile_modifier')]
    public function modif(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, Request $request): Response
    {
        $participants = $this->getUser();
        $form = $this->createForm(ParticipantType::class, $participants);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profile = $form->getData();

            if (!$this->isGranted('ROLE_ADMIN')) {
                $participants->setRoles(['ROLE_USER']);
            } else {
                $participants->setRoles(['ROLE_ADMIN']);
            }


            $entityManager->persist($profile);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_profile') {
                $this->addFlash(
                    'success',
                    'Profile modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('profile/profileModif.html.twig', [
            'controller_name' => 'ProfileController',
            'formulaireProfile' => $form->createView(),

        ]);


    }

    #[Route('/MotDePasse', name: 'app_mdp_modifier')]
    public function motDePasse(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, Request $request): Response
    {
        $participants = $this->getUser();

        $form = $this->createForm(ModifierMotDePasseProfileType::class, $participants);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profileMDP = $form->getData();


            $participants->setPassword($hasher->hashPassword($participants, $participants->getPassword()));


            $entityManager->persist($profileMDP);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_profile_modifier') {
                $this->addFlash(
                    'success',
                    'Mot de passe modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('profile/motDePasse.html.twig', [
            'controller_name' => 'ProfileController',
            'formulaireProfile' => $form->createView(),

        ]);
    }
}
