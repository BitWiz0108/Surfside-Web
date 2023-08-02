<?php

namespace App\Controller;

use App\Entity\CleanPhoto;
use App\Form\CleanPhotoType;
use App\Repository\CleanPhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_HOUSEKEEPER')]
#[Route('/clean/photo')]
class CleanPhotoController extends AbstractController
{
    #[Route('/', name: 'app_clean_photo_index', methods: ['GET'])]
    public function index(CleanPhotoRepository $cleanPhotoRepository): Response
    {
        return $this->render('clean_photo/index.html.twig', [
            'clean_photos' => $cleanPhotoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clean_photo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CleanPhotoRepository $cleanPhotoRepository): Response
    {
        $cleanPhoto = new CleanPhoto();
        $form = $this->createForm(CleanPhotoType::class, $cleanPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanPhotoRepository->save($cleanPhoto, true);

            return $this->redirectToRoute('app_clean_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean_photo/new.html.twig', [
            'clean_photo' => $cleanPhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_photo_show', methods: ['GET'])]
    public function show(CleanPhoto $cleanPhoto): Response
    {
        return $this->render('clean_photo/show.html.twig', [
            'clean_photo' => $cleanPhoto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clean_photo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CleanPhoto $cleanPhoto, CleanPhotoRepository $cleanPhotoRepository): Response
    {
        $form = $this->createForm(CleanPhotoType::class, $cleanPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanPhotoRepository->save($cleanPhoto, true);

            return $this->redirectToRoute('app_clean_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean_photo/edit.html.twig', [
            'clean_photo' => $cleanPhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_photo_delete', methods: ['POST'])]
    public function delete(Request $request, CleanPhoto $cleanPhoto, CleanPhotoRepository $cleanPhotoRepository): Response
    {
        $clean = $cleanPhoto->getClean();
        if ($this->isCsrfTokenValid('delete'.$cleanPhoto->getId(), $request->request->get('_token'))) {
            $cleanPhotoRepository->remove($cleanPhoto, true);
        }

        return $this->redirectToRoute('app_clean_show', ['id' => $clean->getId()], Response::HTTP_SEE_OTHER);
    }
}
