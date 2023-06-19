<?php

namespace App\Controller;

use App\Entity\Housekeeper;
use App\Entity\User;
use App\Form\HousekeeperType;
use App\Repository\HousekeeperRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/housekeeper')]
class HousekeeperController extends AbstractController
{
    #[Route('/', name: 'app_housekeeper_index', methods: ['GET'])]
    public function index(Request $request, HousekeeperRepository $housekeeperRepository): Response
    {
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'last_name';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $housekeepers = $housekeeperRepository->findBy([], [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $numberpages = (count($housekeepers) > 0) ? ceil(count($housekeeperRepository->findAll())/$recordsperpage) : 1;
        return $this->render('housekeeper/index.html.twig', [
            'housekeepers' => $housekeepers,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => count($housekeeperRepository->findAll()),
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
        ]);
    }

    #[Route('/new', name: 'app_housekeeper_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HousekeeperRepository $housekeeperRepository): Response
    {
        $housekeeper = new Housekeeper();
        $form = $this->createForm(HousekeeperType::class, $housekeeper)
            ->add('user', EntityType::class, [
                'class' => User::class,
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('u')
                        ->orderBy('u.email', 'ASC');
                },
                'choice_label' => 'email',
                'by_reference' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $housekeeperRepository->save($housekeeper, true);

            return $this->redirectToRoute('app_housekeeper_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('housekeeper/new.html.twig', [
            'housekeeper' => $housekeeper,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_housekeeper_show', methods: ['GET'])]
    public function show(Housekeeper $housekeeper): Response
    {
        return $this->render('housekeeper/show.html.twig', [
            'housekeeper' => $housekeeper,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_housekeeper_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Housekeeper $housekeeper, HousekeeperRepository $housekeeperRepository): Response
    {
        $form = $this->createForm(HousekeeperType::class, $housekeeper);
        $form->remove('user');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $housekeeperRepository->save($housekeeper, true);

            return $this->redirectToRoute('app_housekeeper_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('housekeeper/edit.html.twig', [
            'housekeeper' => $housekeeper,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_housekeeper_delete', methods: ['POST'])]
    public function delete(Request $request, Housekeeper $housekeeper, HousekeeperRepository $housekeeperRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$housekeeper->getId(), $request->request->get('_token'))) {
            $housekeeperRepository->remove($housekeeper, true);
        }

        return $this->redirectToRoute('app_housekeeper_index', [], Response::HTTP_SEE_OTHER);
    }
}
