<?php

namespace App\Controller\Admin;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class SiteController extends AbstractController
{
    #[Route('/admin/site', name: 'app_admin_site')]
    public function index(SitesRepository $sitesRepository): Response
    {
        $sites = $sitesRepository->findAll();
        return $this->render('admin/site/site.html.twig', [
            "sites" => $sites,
        ]);
    }

    #[Route('/admin/site/supprimer/{id}', name: 'app_admin_site_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(SitesRepository $sitesRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $site = $sitesRepository->find($id);
        if ($site) {
            $entityManager->remove($site);
            $entityManager->flush();
            $this->addFlash('success', 'Le site a bien été supprimé');
        } else {
            $this->addFlash('danger', 'La site n\'existe pas');
        }
        return $this->redirectToRoute('app_admin_site');
    }

    #[Route('/admin/site/ajouter', name: 'app_admin_site_ajouter')]
    #[Route('/admin/site/modifier/{id}', name: 'app_admin_site_modifier', requirements: ['id' => '\d+'])]
    public function editer(UserPasswordHasherInterface $hasher,SluggerInterface $slugger,SitesRepository $sitesRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_admin_site_ajouter') {
            $site = new Site();

        } else {
            $site = $sitesRepository->find($id);
            //$bien = $entityManager->getRepository(Bien::class)->find($request->attributes->get('id'));
        }

        $form = $this->createForm(SiteType::class, $site);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $site = $form->getData();


            $entityManager->persist($site);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_admin_site_ajouter') {
                $this->addFlash(
                    'success',
                    'Site ajouté avec succès'
                );
            } else {
                $this->addFlash(
                    'success',
                    'Site modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_admin_site');
        }
        return $this->render('admin/site/editerSite.html.twig', [
            'controller_name' => 'SiteController',
            'formulaireSite' => $form->createView(),
        ]);
    }
}
