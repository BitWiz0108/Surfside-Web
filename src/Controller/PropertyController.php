<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Property;
use App\Entity\PropertyPhoto;
use App\Form\PropertyType;
use App\Form\PropertyPhotoType;
use App\Repository\CustomerRepository;
use App\Repository\PropertyRepository;
use App\Repository\PropertyPhotoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/property')]
class PropertyController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger, private PropertyPhotoRepository $propertyPhotoRepository) {}

    #[Route('/', name: 'app_property_index', methods: ['GET'])]
    public function index(Request $request, PropertyRepository $propertyRepository): Response
    {
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'title';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $properties = $propertyRepository->findBy([], [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $numberpages = (count($properties) > 0) ? ceil(count($propertyRepository->findAll())/$recordsperpage) : 1;
        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => count($propertyRepository->findAll()),
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
        ]);
    }

    #[Route('/new', name: 'app_property_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PropertyRepository $propertyRepository): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property)
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'query_builder' => function (CustomerRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.company', 'ASC');
                },
                'choice_label' => 'company',
                'by_reference' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->save($property, true);

            return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('property/new.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Property $property): Response
    {
        // dd($request, $property);
        $propertyPhoto = new PropertyPhoto();
        $form = $this->createForm(PropertyPhotoType::class, $propertyPhoto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('file')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            $propertyPhoto->setProperty($property);
            $propertyPhoto->setUrl('/uploads/images/'.$newFilename);
            $this->propertyPhotoRepository->save($propertyPhoto, true);
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'form' => $form,            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_property_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Property $property, PropertyRepository $propertyRepository): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->save($property, true);

            return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('property/edit.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_delete', methods: ['POST'])]
    public function delete(Request $request, Property $property, PropertyRepository $propertyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->request->get('_token'))) {
            foreach ($property->getPropertyPhotos() as $photo) {
                $this->propertyPhotoRepository->remove($photo, true);
            }
            $propertyRepository->remove($property, true);
        }

        return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
    }
}
