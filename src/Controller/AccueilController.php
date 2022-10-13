<?php

namespace App\Controller;



use App\Repository\InscriptionRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortieRepository $sortieRepository, InscriptionRepository $inscriptionRepository): Response
    {
        $sorties =$sortieRepository->findAll();
        $inscriptions =$inscriptionRepository ->findAll();

        foreach ($sorties as $sort){
            $nbreInscrits = count($inscriptionRepository -> findBy(['sortie_id'=> $sort->getId()]));
           $estInscrit = (count($inscriptionRepository->findBy(['sortie_id' => $sort->getId(), 'participant_id' => $this->getUser()->getId()])) == 1);

            $tab1[] =array(
            'sortie' => $sort,
             'nbreInscrits'=>$nbreInscrits,
             'estInscrit' =>$estInscrit
            );
             }

        return $this->render('accueil/index.html.twig', [
                'sorties' =>$sorties,
                'inscriptions' => $inscriptions,
                 'tab' => $tab1
        ]);
    }

}
