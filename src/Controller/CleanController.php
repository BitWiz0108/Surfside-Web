<?php

namespace App\Controller;

use App\Entity\Clean;
use App\Form\CleanType;
use App\Repository\CleanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clean')]
class CleanController extends AbstractController
{
    #[Route('/', name: 'app_clean_index', methods: ['GET'])]
    public function index(CleanRepository $cleanRepository): Response
    {
        return $this->render('clean/index.html.twig', [
            'cleans' => $cleanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clean_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CleanRepository $cleanRepository): Response
    {
        $clean = new Clean();
        $form = $this->createForm(CleanType::class, $clean);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanRepository->save($clean, true);

            return $this->redirectToRoute('app_clean_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean/new.html.twig', [
            'clean' => $clean,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_show', methods: ['GET'])]
    public function show(Clean $clean): Response
    {
        return $this->render('clean/show.html.twig', [
            'clean' => $clean,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clean_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clean $clean, CleanRepository $cleanRepository): Response
    {
        $form = $this->createForm(CleanType::class, $clean);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanRepository->save($clean, true);

            return $this->redirectToRoute('app_clean_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean/edit.html.twig', [
            'clean' => $clean,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_delete', methods: ['POST'])]
    public function delete(Request $request, Clean $clean, CleanRepository $cleanRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clean->getId(), $request->request->get('_token'))) {
            $cleanRepository->remove($clean, true);
        }

        return $this->redirectToRoute('app_clean_index', [], Response::HTTP_SEE_OTHER);
    }
}
