<?php

namespace App\Controller;

use App\Entity\Supply;
use App\Form\SupplyType;
use App\Repository\SupplyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/supply')]
class SupplyController extends AbstractController
{
    #[Route('/', name: 'app_supply_index', methods: ['GET'])]
    public function index(Request $request, SupplyRepository $supplyRepository): Response
    {
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'name';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $supplies = $supplyRepository->findBy([], [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $numberpages = (count($supplies) > 0) ? ceil(count($supplyRepository->findAll())/$recordsperpage) : 1;
        return $this->render('supply/index.html.twig', [
            'supplies' => $supplies,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => count($supplyRepository->findAll()),
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
        ]);
    }

    #[Route('/new', name: 'app_supply_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SupplyRepository $supplyRepository): Response
    {
        $supply = new Supply();
        $form = $this->createForm(SupplyType::class, $supply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplyRepository->save($supply, true);

            return $this->redirectToRoute('app_supply_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supply/new.html.twig', [
            'supply' => $supply,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supply_show', methods: ['GET'])]
    public function show(Supply $supply): Response
    {
        return $this->render('supply/show.html.twig', [
            'supply' => $supply,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supply_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supply $supply, SupplyRepository $supplyRepository): Response
    {
        $form = $this->createForm(SupplyType::class, $supply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplyRepository->save($supply, true);

            return $this->redirectToRoute('app_supply_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supply/edit.html.twig', [
            'supply' => $supply,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supply_delete', methods: ['POST'])]
    public function delete(Request $request, Supply $supply, SupplyRepository $supplyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supply->getId(), $request->request->get('_token'))) {
            $supplyRepository->remove($supply, true);
        }

        return $this->redirectToRoute('app_supply_index', [], Response::HTTP_SEE_OTHER);
    }
}
