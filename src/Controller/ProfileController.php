<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile', requirements: ['id' => '\d+'])]
    public function index(ParticipantRepository $participantRepository, $id = null): Response
    {
        $participants = $participantRepository->find($id);


        return $this->render('profile/profile.html.twig', [

            "id" => $id,
            'participants' => $participants
        ]);
    }
}
