<?php

namespace App\Controller\Admin;

use App\Entity\lieu;
use App\Form\LieuType;
use App\Repository\LieuxRepository;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class LieuController extends AbstractController
{
    #[Route('/admin/lieu', name: 'app_admin_lieu')]
    public function index(LieuxRepository $lieuxRepository, VillesRepository $villesRepository): Response
    {
        $villes = $villesRepository->findAll();
        $lieux = $lieuxRepository->findAll();
        return $this->render('admin/lieu/lieu.html.twig', [
            "lieux" => $lieux,
            "villes" => $villes,
        ]);
    }

    #[Route('/admin/lieu/supprimer/{id}', name: 'app_admin_lieu_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(lieuxRepository $lieuxRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $lieu = $lieuxRepository->find($id);
        if ($lieu) {
            $entityManager->remove($lieu);
            $entityManager->flush();
            $this->addFlash('success', 'Le lieu a bien été supprimé');
        } else {
            $this->addFlash('danger', 'La lieu n\'existe pas');
        }
        return $this->redirectToRoute('app_admin_lieu');
    }

    #[Route('/admin/lieu/ajouter', name: 'app_admin_lieu_ajouter')]
    #[Route('/admin/lieu/modifier/{id}', name: 'app_admin_lieu_modifier', requirements: ['id' => '\d+'])]
    public function editer(UserPasswordHasherInterface $hasher,SluggerInterface $slugger,lieuxRepository $lieuxRepository,  Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_admin_lieu_ajouter') {
            $lieu = new lieu();

        } else {
            $lieu = $lieuxRepository->find($id);
            //$bien = $entityManager->getRepository(Bien::class)->find($request->attributes->get('id'));
        }

        $form = $this->createForm(lieuType::class, $lieu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();


            $entityManager->persist($lieu);
            $entityManager->flush();
            if ($request->attributes->get('_route') == 'app_admin_lieu_ajouter') {
                $this->addFlash(
                    'success',
                    'lieu ajouté avec succès'
                );
            } else {
                $this->addFlash(
                    'success',
                    'lieu modifié avec succès'
                );
            }
            return $this->redirectToRoute('app_admin_lieu');
        }
        return $this->render('admin/lieu/editerlieu.html.twig', [
            'controller_name' => 'lieuController',
            'formulaireLieu' => $form->createView(),
        ]);
    }
}
