<?php

namespace App\Controller;


use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('accueil/index.html.twig', [
                'sorties' => $sortieRepository->findAll(),
        ]);
    }

}
