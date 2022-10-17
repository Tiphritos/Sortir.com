<?php

namespace App\Controller;

use App\Entity\Csv;
use App\Entity\Participant;
use App\Form\CsvType;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use App\Services\CsvService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant')]
class ParticipantController extends AbstractController
{
    #[Route('/', name: 'app_participant_index', methods: ['GET', 'POST'])]
    public function index(ParticipantRepository $participantRepository,
                          SiteRepository $siteRepository,
                          Request $request): Response
    {
        $csv= new Csv();
        $form = $this->createForm(CsvType::class, $csv);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            if (($handle = fopen($csv->getName(), "r")) !== FALSE)
            {
                $i = 0;
                while (($data = fgetcsv($handle, null, ";")) !== FALSE)
                {
                    $i++;
                    $arr[] = $data;
                }
                fclose($handle);
            }
            for($i = 1; $i < count($arr); $i++){
                $participant = new Participant();
                $participant->setSitesNoSite($siteRepository->findOneBy(['id'=>$arr[$i][0]]));
                $participant->setNom($arr[$i][1]);
                $participant->setPseudo($arr[$i][2]);
                $participant->setPrenom($arr[$i][3]);
                $participant->setTelephone($arr[$i][4]);
                $participant->setMail($arr[$i][5]);
                $participant->setMotDePasse($arr[$i][6]);
                $participant->setAdministrateur($arr[$i][7]);
                $participant->setActif($arr[$i][8]);
                //$participant->setRoles($arr[$i][9]);
                $participantRepository->save($participant, true);
            }

            //dd($arr);
            return $this->redirectToRoute('app_accueil');
        }

        return $this->renderForm('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParticipantRepository $participantRepository): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participantRepository->save($participant, true);

            return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/new.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participant_show', methods: ['GET'])]
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }


    #[Route('/partiel/{id}', name: 'app_Partiel_show', methods: ['GET'])]
    public function Participant(Participant $participant): Response
    {
        return $this->render('participant/showPartiel.html.twig', [
            'participant' => $participant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participant $participant, ParticipantRepository $participantRepository,UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        $motdepasse = $participant ->getMotDePasse();

        if ($form->isSubmitted() && $form->isValid()) {
            $motdepasseHache = $passwordHasher->hashPassword($participant,$motdepasse);
            $participant ->setMotDePasse($motdepasseHache);
            $participantRepository->save($participant, true);
            $id = $this->getUser()->getId();
           $this->addFlash('message',"Modification pris en compte");
            return $this->redirectToRoute('app_participant_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participant_delete', methods: ['POST'])]
    public function delete(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $participantRepository->remove($participant, true);
        }

        return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
    }

//    //https://zetcode.com/php/csv/
//    #[Route('/new/csv', name: 'app_participant_new_csv', methods: ['GET', 'POST'])]
//    public function newCsv(Request $request, ParticipantRepository $participantRepository): Response
//    {
//        $csv_name = $request->request->get('csvParticipant');
////        $csv = fgetcsv($csv_name, '1000');
////        if (is_uploaded_file($csv)){
//            $file = fopen($csv_name, 'r');
//            dd($file);
////            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
////                $participant = new Participant();
////                $participant->set
////            }
//        //}
//
//        return $this->redirectToRoute('app_accueil');
//    }
}
