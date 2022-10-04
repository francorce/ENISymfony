<?php

namespace App\Controller\Admin;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class VilleController extends AbstractController
{
    #[Route('/admin/ville', name: 'app_admin_ville')]
    public function index(VillesRepository $villesRepository): Response
    {
        $villes = $villesRepository->findAll();
        return $this->render('admin/ville/ville.html.twig', [
            "villes" => $villes,
        ]);
    }


    #[Route('/admin/ville/supprimer/{id}', name: 'app_admin_ville_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(VillesRepository $villesRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $ville = $villesRepository->find($id);
        if ($ville) {
            $entityManager->remove($ville);
            $entityManager->flush();
            $this->addFlash('success', 'La ville a bien été supprimé');
        } else {
            $this->addFlash('danger', 'La ville n\'existe pas');
        }
        return $this->redirectToRoute('app_admin_ville');
    }

    #[Route('/admin/ville/ajouter', name: 'app_admin_ville_ajouter')]
    #[Route('/admin/ville/modifier/{id}', name: 'app_admin_ville_modifier', requirements: ['id' => '\d+'])]
    public function editer(UserPasswordHasherInterface $hasher,SluggerInterface $slugger,VillesRepository $villesRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_admin_ville_ajouter') {
            $ville = new Ville();

        } else {
            $ville = $villesRepository->find($id);
            //$bien = $entityManager->getRepository(Bien::class)->find($request->attributes->get('id'));
        }

        $form = $this->createForm(VilleType::class, $ville);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ville = $form->getData();


            $entityManager->persist($ville);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_admin_ville_ajouter') {
                $this->addFlash(
                    'success',
                    'Ville ajouté avec succès'
                );
            } else {
                $this->addFlash(
                    'success',
                    'Ville modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_admin_ville');
        }
        return $this->render('admin/ville/editerVille.html.twig', [
            'controller_name' => 'VilleController',
            'formulaireVille' => $form->createView(),
        ]);
    }
}
