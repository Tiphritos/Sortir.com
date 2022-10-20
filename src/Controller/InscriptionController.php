<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\InscriptionRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/{id}/{sortie}/inscription', name: 'app_inscription')]
    public function inscription(
        Participant $participant,
        Sortie $sortie ,
        InscriptionRepository $inscriptionRepository,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager): Response
    {
        $nouvelleInscription = new Inscription();
        $date = new \DateTimeImmutable();
        $nouvelleInscription ->setDateInscription($date);
        $nouvelleInscription ->setParticipantId($participant);
        $nouvelleInscription ->setSortieId($sortie);

        $inscriptions = $inscriptionRepository->findBy(['sortie_id'=>$sortie->getId()] );
        $estinscrit = false;

        //Vérifier si déjà inscrit
        foreach ($inscriptions as $inscrip  ){
            if ($inscrip->getParticipantId()->getId() == $participant->getId()){
                $this->addFlash('message', "Blaireau!!! Tu es déjà inscrit du con");
                $estinscrit = true;
            }
        }
        if (!$estinscrit){
            //Vérifier si état = ouvert
            if ($sortie ->getEtatsNoEtat()->getId() != 2  ) {
                $this->addFlash('message', "Impossible de s'inscrire à la sortie, les inscriptions sont clôturées");
            }
            //Vérifier si sortie complète
            else if (count($inscriptions) >=$sortie ->getNbInscriptionsMax()){
                $this->addFlash('message', "Impossible de s'inscrire à la sortie. 
            Sortie Complète. Tu l'as dans le uc ");
            }
            //Sinon inscrire
            else {
                if ((count($inscriptions)+1) >= $sortie->getNbInscriptionsMax()){
                    $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id'=>3]));
                    $entityManager->persist($sortie);
                }
                $entityManager->persist($nouvelleInscription);
                $entityManager->flush();

                $this->addFlash('message', "Inscription réussie");
            }
        }
        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/{sortie}/desistement', name: 'app_desistement')]
    public function desistement(
        Participant $participant,
        Sortie $sortie ,
        InscriptionRepository $inscriptionRepository,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager): Response
    {
        $inscription = $inscriptionRepository->findOneBy(['sortie_id'=>$sortie->getId(), 'participant_id'=>$participant->getId()]);
        $inscriptions = $inscriptionRepository->findBy(['sortie_id'=>$sortie->getId()] );
        $estinscrit = false;
        //Vérifier si inscrit
        foreach ($inscriptions as $inscrip  ){
            if ($inscrip->getParticipantId()->getId() == $participant->getId()){
                $estinscrit = true;
            }
        }
        if ($estinscrit){
            if ($sortie->getEtatsNoEtat()->getId() <= 3) {
                $AnnulerDeSortie = $participant->removeInscription($inscription, true);
                $entityManager->persist($AnnulerDeSortie);

                $this->addFlash('message', "Désistement pris en compte");
                if (($sortie->getEtatsNoEtat()->getId() == 3) && ($sortie->getDateCloture() > (new \DateTime('now')))) {
                    $sortie->setEtatsNoEtat($etatRepository->findOneBy(['id' => 2]));
                    $entityManager->persist($sortie);
                }
                $entityManager->flush();
            }else{
                $this->addFlash('message', "Désistement impossible, pose pas de questions.");
            }
        }else{
            $this->addFlash('message', "Blaireau!!! Tu n'es pas inscrit à cet événement");
        }
        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);

    }
}
