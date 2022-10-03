<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    #[Route('/admin/site', name: 'app_admin_site')]
    public function index(): Response
    {
        return $this->render('admin/site/site.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }
}
