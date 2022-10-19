<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\InscriptionRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
                        SortieRepository $sortieRepository,
                        InscriptionRepository $inscriptionRepository,
                        EtatRepository $etatRepository,
                        LieuRepository $lieuRepository,

    ): Response
    {
        ;
        $date = new \DateTimeImmutable();
        $sortie = new Sortie();
        $lieu = new Lieu();
        $formL = $this->createForm(LieuType::class,$lieu);
        $formL->handleRequest($request);

        $sortie->setDateDebut($date);
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);
        $dateDebut = $request->request->get('dateDebut');


      /*  if($formL->isSubmitted()&& $formL->isValid()){



            $lieuRepository->save($lieu, true);


        }*/


        if ($form->isSubmitted() && $form->isValid()) {


            $sortie->setOrganisateur($this->getUser());
            $sortie->setSiteOrganisateur($this->getUser()->getSitesNoSite());

            if ($request->request->get('publier') != null) { //Si publié
                $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 2]));
            }else{ //Si simplement enregistré
                $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 1]));
            }
            $sortieRepository->save($sortie, true);

            //Inscrire automatiquement l'organisateur à sa sortie
            $inscription = new Inscription();
            $inscription->setParticipantId($this->getUser());
            $inscription->setDateInscription(new \DateTimeImmutable('now'));
            $inscription->setSortieId($sortie);

            $inscriptionRepository->save($inscription, true);

            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formL'=> $formL
        ]);
    }


    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie, LieuRepository $lieuRepository, InscriptionRepository $inscriptionRepository): Response
    {
        $lieu =  $lieuRepository ->findOneBy(['id' => $sortie->getLieuxNoLieu()]);
        $inscriptions = $inscriptionRepository ->findBy(['sortie_id'=>$sortie->getId()]);
        $nbredeParticipant = count($inscriptions);

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'lieu'=> $lieu,
            'inscriptions'=>$inscriptions,
            'nbreDeParticipant'=>  $nbredeParticipant
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
//                if($request->request->get('annuler') != null){
//                    return $this->redirectToRoute('app_sortie_delete');
//                }
                if ($request->request->get('publier') != null) { //Si publié
                    $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 2]));
                } else { //Si simplement enregistré
                    $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 1]));
                }
                $sortieRepository->save($sortie, true);

                return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
            }
        }
        //dd($sortie);
        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/annulation', name: 'app_sortie_delete', methods: ['POST', 'GET'])]
    public function redirectToAnnulation(Request $request,
                                         Sortie $sortie,
                                         InscriptionRepository $inscriptionRepository,
                                         LieuRepository $lieuRepository,
                                         EntityManagerInterface $entityManager): Response
    {
        $lieu =  $lieuRepository ->findOneBy(['id' => $sortie->getLieuxNoLieu()]);
        return $this->render('sortie/confirmannulation.html.twig',[
            'sortie' => $sortie,
            'lieu' => $lieu
        ]);
    }

    //Annuler Sortie après confirmation
    #[Route('/{id}', name: 'app_sortie_annulation', methods: ['POST'])]
    public function annuler(Request $request, Sortie $sortie, InscriptionRepository $inscriptionRepository,
                            EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {

        $motif = $request->request->get('motifAnnulation');

        //Checker l'état de la sortie, doit être strictement antérieur à 4 (aka ne pas avoir commencé)
        if($sortie->getEtatsNoEtat()->getId()<=3) {
            //Recupérer les inscriptions à la sortie puis les supprimer
            $inscriptions = $inscriptionRepository->findBy(['sortie_id' => $sortie->getId()]);
            foreach ($inscriptions as $inscription) {
                $inscriptionRepository->remove($inscription, true);
            }
            //Annuler la sortie
            $sortie->setDescriptionInfos($motif);
            $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 6]));
            $entityManager->persist($sortie);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }
}
