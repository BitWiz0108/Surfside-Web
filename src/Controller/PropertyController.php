<?php

namespace App\Controller;

use DateInterval;
use DateTime;
use App\Entity\Customer;
use App\Entity\Property;
use App\Entity\PropertyPhoto;
use App\Form\PropertyType;
use App\Form\PropertyPhotoType;
use App\Repository\CleanRepository;
use App\Repository\CleanHousekeeperRepository;
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
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[IsGranted('ROLE_ADMIN')]
#[Route('/property')]
class PropertyController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger, private PropertyPhotoRepository $propertyPhotoRepository) {}

    #[Route('/', name: 'app_property_index', methods: ['GET'])]
    public function index(Request $request, PropertyRepository $propertyRepository, CleanRepository $cleanRepository): Response
    {
        $filters = [];
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'title';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $includeinactive = ($request->query->has('includeinactive')) ? $request->query->get('includeinactive') : 0;
        if (!$includeinactive) {
            $filters = ['active' => 1];
        }
        $properties = $propertyRepository->findBy($filters, [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $propertiescount = count($propertyRepository->findBy($filters));
        $numberpages = (count($properties) > 0) ? ceil(count($propertyRepository->findAll())/$recordsperpage) : 1;
        $positions = [];
        $startDateTime = new DateTime();
        $endDateTime = new DateTime();
        $endDateTime->add(new DateInterval('PT72H')); 
        foreach ($properties as $property) {
            $greenCleans = $cleanRepository->createQueryBuilder('c')
                ->andWhere('c.scheduled >= :start')
                ->andWhere('c.scheduled < :end')
                ->andWhere('c.property = :property')
                ->setParameter('start', $startDateTime)
                ->setParameter('end', $endDateTime)
                ->setParameter('property', $property)
                ->getQuery()
                ->getResult()
            ;
            $positions[] = ['title' => $property->getTitle(), 'needsclean' => (count($greenCleans) > 0) ? true : false, 'location' => ['lat' => (float) $property->getLatitude(), 'lng' => (float) $property->getLongitude()]];
        }
        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => $propertiescount,
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
            'includeinactive' => $includeinactive,
            'positions' => json_encode($positions),
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
    public function show(Request $request, Property $property, ChartBuilderInterface $chartBuilder, CleanHousekeeperRepository $cleanHousekeeperRepository): Response
    {
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
        $chartData = $cleanHousekeeperRepository->createQueryBuilder('chr')
            ->select('h.first_name, h.last_name, COUNT(chr.housekeeper) as cleans_count')
            ->leftJoin('chr.clean', 'c')
            ->leftJoin('chr.housekeeper', 'h')
            ->andWhere('c.property = :property')
            ->groupBy('chr.housekeeper')
            ->setParameter('property', $property)
            ->getQuery()
            ->getResult()
        ;
        $labels = [];
        $data = [];
        $totalcleans = 0;
        foreach($chartData as $rec) {
            $labels[] = $rec['first_name'].' '.$rec['last_name'];
            $data[] = $rec['cleans_count'];
            $totalcleans += $rec['cleans_count'];
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Housekeeper/Property Experience',
                    'backgroundColor' => ['blueviolet', 'aliceblue', 'cornflowerblue', 'blancedalmond', 'darkmagenta', 'darkolivegreen', 'cornsilk', 'gold', 'ghostwhite', 'indianred', 'honeydew', 'lavender', 'lemonchiffon', 'lavenderblush', 'ivory', 'khaki', 'mediumpurple', 'mintcream', 'mavajowhite', 'olive', 'oldlace', 'orchid', 'plum', 'silver', 'rosybrown'],
                    'borderColor' => ['blueviolet', 'aliceblue', 'cornflowerblue', 'blancedalmond', 'darkmagenta', 'darkolivegreen', 'cornsilk', 'gold', 'ghostwhite', 'indianred', 'honeydew', 'lavender', 'lemonchiffon', 'lavenderblush', 'ivory', 'khaki', 'mediumpurple', 'mintcream', 'mavajowhite', 'olive', 'oldlace', 'orchid', 'plum', 'silver', 'rosybrown'],
                    'data' => $data,
                ],
            ],
        ]);  
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'form' => $form,    
            'chart' => $chart, 
            'totalcleans' => $totalcleans,     
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
