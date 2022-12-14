<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lieu')]
class LieuController extends AbstractController
{
    #[Route('/', name: 'app_lieu_index', methods: ['GET'])]
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('lieu/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lieu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LieuRepository $lieuRepository): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuRepository->save($lieu, true);

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }


    #[Route('/nouveau', name: 'app_lieu_nouveau', methods: ['GET', 'POST'])]
    public function nouveau(Request $request, LieuRepository $lieuRepository, VilleRepository $villeRepository): Response
    {
        $lieu = new Lieu();

        $jsonData = json_decode($request->getContent(),true);
        $lieu ->setNomLieu($jsonData['nomLieu']);
        $lieu ->setVillesNoVille($villeRepository->findOneBy(['nom_ville' =>$jsonData['ville']]));
        $lieu->setRue($jsonData['rue']);
        $lieuRepository->save($lieu, true);

        return $this->json([$lieu->getId(), $lieu->getNomLieu()], 200);
    }




    #[Route('/{id}', name: 'app_lieu_show', methods: ['GET'])]
    public function show(Lieu $lieu): Response
    {
        return $this->render('lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lieu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lieu $lieu, LieuRepository $lieuRepository): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuRepository->save($lieu, true);
            $this->addFlash('message',"Modification prise en compte");

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_delete', methods: ['POST'])]
    public function delete(Request $request,
                           Lieu $lieu,
                           LieuRepository $lieuRepository
    ): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete' . $lieu->getId(), $request->request->get('_token'))) {
                $lieuRepository->remove($lieu, true);
                $this->addFlash('message',"Suppresion du lieu r??ussie");
            }
        }catch(\Exception $e){
            $this->addFlash('message',"Suppression impossible. Merci de contacter l'administrateur de base de donn??es. (Et nous envoyer son contact)");
        }

        return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
    }
}
