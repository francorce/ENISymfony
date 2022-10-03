<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    #[Route('/admin/ville', name: 'app_admin_ville')]
    public function index(): Response
    {
        return $this->render('admin/ville/ville.html.twig', [
            'controller_name' => 'VilleController',
        ]);
    }
}
