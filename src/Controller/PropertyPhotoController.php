<?php

namespace App\Controller;

use App\Entity\PropertyPhoto;
use App\Form\PropertyPhotoType;
use App\Repository\PropertyPhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/property/photo')]
class PropertyPhotoController extends AbstractController
{
    #[Route('/', name: 'app_property_photo_index', methods: ['GET'])]
    public function index(PropertyPhotoRepository $propertyPhotoRepository): Response
    {
        return $this->render('property_photo/index.html.twig', [
            'property_photos' => $propertyPhotoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_property_photo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PropertyPhotoRepository $propertyPhotoRepository): Response
    {
        $propertyPhoto = new PropertyPhoto();
        $form = $this->createForm(PropertyPhotoType::class, $propertyPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyPhotoRepository->save($propertyPhoto, true);

            return $this->redirectToRoute('app_property_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('property_photo/new.html.twig', [
            'property_photo' => $propertyPhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_photo_show', methods: ['GET'])]
    public function show(PropertyPhoto $propertyPhoto): Response
    {
        return $this->render('property_photo/show.html.twig', [
            'property_photo' => $propertyPhoto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_property_photo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PropertyPhoto $propertyPhoto, PropertyPhotoRepository $propertyPhotoRepository): Response
    {
        $form = $this->createForm(PropertyPhotoType::class, $propertyPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyPhotoRepository->save($propertyPhoto, true);

            return $this->redirectToRoute('app_property_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('property_photo/edit.html.twig', [
            'property_photo' => $propertyPhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_photo_delete', methods: ['POST'])]
    public function delete(Request $request, PropertyPhoto $propertyPhoto, PropertyPhotoRepository $propertyPhotoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$propertyPhoto->getId(), $request->request->get('_token'))) {
            $propertyPhotoRepository->remove($propertyPhoto, true);
        }

        return $this->redirectToRoute('app_property_photo_index', [], Response::HTTP_SEE_OTHER);
    }
}
