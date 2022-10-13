<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
                        SortieRepository $sortieRepository,
                        EtatRepository $etatRepository
    ): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $sortie->setOrganisateur($this->getUser());
            $sortie->setSiteOrganisateur($this->getUser()->getSitesNoSite());
            if ($request->request->get('publier') != null) { //Si publié
                $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 2]));
            }else{ //Si simplement enregistré
                $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 1]));
            }

            $sortieRepository->save($sortie, true);

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,

        ]);
    }


    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie, LieuRepository $lieuRepository): Response
    {
        $lieu =  $lieuRepository ->findOneBy(['id' => $sortie->getLieuxNoLieu()]);
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'lieu'=> $lieu
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,
                         Sortie $sortie,
                         SortieRepository $sortieRepository,
                         EtatRepository $etatRepository): Response
    {

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $validator = Validation::createValidator();
            $listeOfErrors = $validator->validate($sortie);
            if (count($listeOfErrors) == 0){
                if ($request->request->get('publier') != null) { //Si publié
                    $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 2]));
                } else { //Si simplement enregistré
                    $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 1]));
                }
                $sortieRepository->save($sortie, true);

                return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }
    //Annuler Sortie
    #[Route('/{id}', name: 'app_sortie_delete', methods: ['POST'])]
    public function annuler(Request $request, Sortie $sortie, SortieRepository $sortieRepository,
                            EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {
        $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id'=> 6]));
        $entityManager->persist($sortie);
        $entityManager->flush();
//        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
//            $sortieRepository->remove($sortie, true);
//        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }
}
