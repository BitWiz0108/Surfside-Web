<?php

namespace App\Controller;

use App\Entity\CleanLinen;
use App\Form\CleanLinenType;
use App\Repository\CleanLinenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_HOUSEKEEPER')]
#[Route('/clean/linen')]
class CleanLinenController extends AbstractController
{
    #[Route('/', name: 'app_clean_linen_index', methods: ['GET'])]
    public function index(CleanLinenRepository $cleanLinenRepository): Response
    {
        return $this->render('clean_linen/index.html.twig', [
            'clean_linens' => $cleanLinenRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clean_linen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CleanLinenRepository $cleanLinenRepository): Response
    {
        $cleanLinen = new CleanLinen();
        $form = $this->createForm(CleanLinenType::class, $cleanLinen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanLinenRepository->save($cleanLinen, true);

            return $this->redirectToRoute('app_clean_linen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean_linen/new.html.twig', [
            'clean_linen' => $cleanLinen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_linen_show', methods: ['GET'])]
    public function show(CleanLinen $cleanLinen): Response
    {
        return $this->render('clean_linen/show.html.twig', [
            'clean_linen' => $cleanLinen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clean_linen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CleanLinen $cleanLinen, CleanLinenRepository $cleanLinenRepository): Response
    {
        $form = $this->createForm(CleanLinenType::class, $cleanLinen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanLinenRepository->save($cleanLinen, true);

            return $this->redirectToRoute('app_clean_linen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean_linen/edit.html.twig', [
            'clean_linen' => $cleanLinen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_linen_delete', methods: ['POST'])]
    public function delete(Request $request, CleanLinen $cleanLinen, CleanLinenRepository $cleanLinenRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cleanLinen->getId(), $request->request->get('_token'))) {
            $cleanLinenRepository->remove($cleanLinen, true);
        }

        return $this->redirectToRoute('app_clean_linen_index', [], Response::HTTP_SEE_OTHER);
    }
}
