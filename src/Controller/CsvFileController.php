<?php

namespace App\Controller;

use App\Entity\CsvFile;
use App\Form\CsvFileType;
use App\Repository\CsvFileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/csv/file')]
class CsvFileController extends AbstractController
{
    #[Route('/', name: 'app_csv_file_index', methods: ['GET'])]
    public function index(CsvFileRepository $csvFileRepository): Response
    {
        return $this->render('csv_file/index.html.twig', [
            'csv_files' => $csvFileRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_csv_file_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CsvFileRepository $csvFileRepository): Response
    {
        $csvFile = new CsvFile();
        $form = $this->createForm(CsvFileType::class, $csvFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFileRepository->save($csvFile, true);

            return $this->redirectToRoute('app_csv_file_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('csv_file/new.html.twig', [
            'csv_file' => $csvFile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_csv_file_show', methods: ['GET'])]
    public function show(CsvFile $csvFile): Response
    {
        return $this->render('csv_file/show.html.twig', [
            'csv_file' => $csvFile,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_csv_file_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CsvFile $csvFile, CsvFileRepository $csvFileRepository): Response
    {
        $form = $this->createForm(CsvFileType::class, $csvFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFileRepository->save($csvFile, true);

            return $this->redirectToRoute('app_csv_file_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('csv_file/edit.html.twig', [
            'csv_file' => $csvFile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_csv_file_delete', methods: ['POST'])]
    public function delete(Request $request, CsvFile $csvFile, CsvFileRepository $csvFileRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$csvFile->getId(), $request->request->get('_token'))) {
            $csvFileRepository->remove($csvFile, true);
        }

        return $this->redirectToRoute('app_csv_file_index', [], Response::HTTP_SEE_OTHER);
    }
}
