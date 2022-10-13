<?php

namespace App\Controller;



use App\Repository\InscriptionRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortieRepository $sortieRepository,
                          SiteRepository $siteRepository,
                          InscriptionRepository $inscriptionRepository): Response
    {
        $sorties =$sortieRepository->findAll();
        $inscriptions =$inscriptionRepository ->findAll();
        $sites = $siteRepository->findAll();
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
                'tab' => $tab1,
                'sites'=>$sites
        ]);
    }
    #[Route('/accueil/filtres', name: 'app_accueil_filtres', methods: ['POST'])]
    public function indexfilter(SortieRepository $sortieRepository,
                                SiteRepository $siteRepository,
                                Request $request,
                                InscriptionRepository $inscriptionRepository): Response
    {

        //Recuperer les differents filtres selectionnés
        $siteFilter = $siteRepository->findOneBy(['id'=> $request->request->get('sites')]);
        $motClef = $request->request->get('mot-clef');
        $date1 = $request->request->get('date1');
        $date2 = $request->request->get('date2');
        $estOrganisateur = $request->request->get('estOrganisateur') != null;
        $estInscrit = $request->request->get('estInscrit') != null;
        $pasInscrit = $request->request->get('pasInscrit') != null;
        $sortiesPassees = $request->request->get('sortiesPassees') != null;



        $sorties = $sortieRepository->findFiltres($siteFilter,'%'.$motClef.'%', $date1, $date2, $estOrganisateur, $estInscrit, $pasInscrit, $sortiesPassees, $this->getUser());
        //dd($siteFilter, $motClef, $date1, $estOrganisateur, $sorties);
        $inscriptions =$inscriptionRepository ->findAll();
        $sites = $siteRepository->findAll();

        foreach ($sorties as $sort){
            //dd($sorties, $sort);
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
            'tab' => $tab1,
            'sites'=>$sites
        ]);
    }

}
