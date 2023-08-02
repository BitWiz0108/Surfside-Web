<?php

namespace App\Controller;

use App\Entity\Linen;
use App\Form\LinenType;
use App\Repository\LinenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/linen')]
class LinenController extends AbstractController
{
    #[Route('/', name: 'app_linen_index', methods: ['GET'])]
    public function index(Request $request, LinenRepository $linenRepository): Response
    {
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'name';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $linens = $linenRepository->findBy([], [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $numberpages = (count($linens) > 0) ? ceil(count($linenRepository->findAll())/$recordsperpage) : 1;
        return $this->render('linen/index.html.twig', [
            'linens' => $linens,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => count($linenRepository->findAll()),
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
        ]);
    }

    #[Route('/new', name: 'app_linen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LinenRepository $linenRepository): Response
    {
        $linen = new Linen();
        $form = $this->createForm(LinenType::class, $linen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $linenRepository->save($linen, true);

            return $this->redirectToRoute('app_linen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('linen/new.html.twig', [
            'linen' => $linen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_linen_show', methods: ['GET'])]
    public function show(Linen $linen): Response
    {
        return $this->render('linen/show.html.twig', [
            'linen' => $linen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_linen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Linen $linen, LinenRepository $linenRepository): Response
    {
        $form = $this->createForm(LinenType::class, $linen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $linenRepository->save($linen, true);

            return $this->redirectToRoute('app_linen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('linen/edit.html.twig', [
            'linen' => $linen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_linen_delete', methods: ['POST'])]
    public function delete(Request $request, Linen $linen, LinenRepository $linenRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$linen->getId(), $request->request->get('_token'))) {
            $linenRepository->remove($linen, true);
        }

        return $this->redirectToRoute('app_linen_index', [], Response::HTTP_SEE_OTHER);
    }
}
