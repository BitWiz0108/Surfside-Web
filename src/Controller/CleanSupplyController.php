<?php

namespace App\Controller;

use App\Entity\CleanSupply;
use App\Form\CleanSupplyType;
use App\Repository\CleanSupplyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_HOUSEKEEPER')]
#[Route('/clean/supply')]
class CleanSupplyController extends AbstractController
{
    #[Route('/', name: 'app_clean_supply_index', methods: ['GET'])]
    public function index(CleanSupplyRepository $cleanSupplyRepository): Response
    {
        return $this->render('clean_supply/index.html.twig', [
            'clean_supplies' => $cleanSupplyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clean_supply_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CleanSupplyRepository $cleanSupplyRepository): Response
    {
        $cleanSupply = new CleanSupply();
        $form = $this->createForm(CleanSupplyType::class, $cleanSupply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanSupplyRepository->save($cleanSupply, true);

            return $this->redirectToRoute('app_clean_supply_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean_supply/new.html.twig', [
            'clean_supply' => $cleanSupply,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_supply_show', methods: ['GET'])]
    public function show(CleanSupply $cleanSupply): Response
    {
        return $this->render('clean_supply/show.html.twig', [
            'clean_supply' => $cleanSupply,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clean_supply_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CleanSupply $cleanSupply, CleanSupplyRepository $cleanSupplyRepository): Response
    {
        $form = $this->createForm(CleanSupplyType::class, $cleanSupply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanSupplyRepository->save($cleanSupply, true);

            return $this->redirectToRoute('app_clean_supply_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean_supply/edit.html.twig', [
            'clean_supply' => $cleanSupply,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_supply_delete', methods: ['POST'])]
    public function delete(Request $request, CleanSupply $cleanSupply, CleanSupplyRepository $cleanSupplyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cleanSupply->getId(), $request->request->get('_token'))) {
            $cleanSupplyRepository->remove($cleanSupply, true);
        }

        return $this->redirectToRoute('app_clean_supply_index', [], Response::HTTP_SEE_OTHER);
    }
}
