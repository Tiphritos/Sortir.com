<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/{id}/{sortie}/inscription', name: 'app_inscription')]
    public function inscription(
        Request $request,
        Participant $participant,
        Sortie $sortie ,
        SortieRepository $sortieRepository,
        ParticipantRepository $participantRepository,

        EntityManagerInterface $entityManager): Response
    {

         $idParticipant=$participant ->getId();
        $tableauDeSortie = $participant ->getSortiesNoSortie();



        $idSortie =$sortie->getId();
        $etatSortie = $sortie ->getEtatsNoEtat()->getId();
        $nombremax = $sortie ->getNbInscriptionsMax();
        $nombreDeParticipants = $participant->getId();



        if ($etatSortie!= 2  ){
            $this->addFlash('message', "Impossible de s'inscrire à la sortie");

        }else{


            $changementDeSortie = $participant->addSortiesNoSortie($sortie);

            $entityManager->persist($changementDeSortie);
            $entityManager->flush();
            $tableauDeSortie = $participant ->getSortiesNoSortie();
            dd( count($tableauDeSortie));
        }



       // dd($idParticipant,$idSortie);

        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',

        ]);
    }
    #[Route('/{id}/{sortie}/desistement', name: 'app_desistement')]
    public function desistement(
        Request $request,
        Participant $participant,
        Sortie $sortie ,
        SortieRepository $sortieRepository,
        ParticipantRepository $participantRepository,

        EntityManagerInterface $entityManager): Response
    {



            $AnnulerDeSortie = $participant->removeSortiesNoSortie($sortie);
            $entityManager->persist($AnnulerDeSortie);
            $entityManager->flush();
        $this->addFlash('message', "Désistement pris en compte");



        // dd($idParticipant,$idSortie);

        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',

        ]);
    }
}
