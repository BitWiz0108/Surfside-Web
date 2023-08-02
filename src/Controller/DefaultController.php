<?php

namespace App\Controller;

use DateTime;
use App\Entity\Clean;
use App\Entity\CleanPhoto;
use App\Form\CleanPhotoType;
use App\Repository\CleanRepository;
use App\Repository\CleanPhotoRepository;
use App\Repository\CleanHousekeeperRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DefaultController extends AbstractController
{
    public function __construct(private Security $security, private SluggerInterface $slugger) {}

    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        } elseif ($this->security->isGranted('ROLE_HOUSEKEEPER')){
            return $this->redirectToRoute('app_home_housekeeper');
        }
        return $this->render('default/index.html.twig', []);
    }

    #[Route('/home', name: 'app_home')]
    public function home(CleanRepository $cleanRepository, PropertyRepository $propertyRepository, ChartBuilderInterface $chartBuilder): Response
    {   
        $today = new DateTime(date("Y-m-d H:i:s"));
        $todaysappointments = $cleanRepository->createQueryBuilder('c')
            ->where('c.scheduled > :today')
            ->setParameter('today', $today)
            ->orderBy('c.scheduled', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
        $datasets = [];
        $chartData = $propertyRepository->createQueryBuilder('p')
            ->select("p.id, p.title, (SELECT count(c.id) FROM App\Entity\Clean c WHERE monthname(c.scheduled) = 'January' AND c.property = p.id GROUP BY c.property), (SELECT count(c2.id) FROM App\Entity\Clean c2 WHERE monthname(c2.scheduled) = 'Febuary' AND c2.property = p.id GROUP BY c2.property), (SELECT count(c3.id) FROM App\Entity\Clean c3 WHERE monthname(c3.scheduled) = 'March' AND c3.property = p.id GROUP BY c3.property), (SELECT count(c4.id) FROM App\Entity\Clean c4 WHERE monthname(c4.scheduled) = 'April' AND c4.property = p.id GROUP BY c4.property), (SELECT count(c5.id) FROM App\Entity\Clean c5 WHERE monthname(c5.scheduled) = 'May' AND c5.property = p.id GROUP BY c5.property), (SELECT count(c6.id) FROM App\Entity\Clean c6 WHERE monthname(c6.scheduled) = 'June' AND c6.property = p.id GROUP BY c6.property), (SELECT count(c7.id) FROM App\Entity\Clean c7 WHERE monthname(c7.scheduled) = 'July' AND c7.property = p.id GROUP BY c7.property), (SELECT count(c8.id) FROM App\Entity\Clean c8 WHERE monthname(c8.scheduled) = 'August' AND c8.property = p.id GROUP BY c8.property), (SELECT count(c9.id) FROM App\Entity\Clean c9 WHERE monthname(c9.scheduled) = 'September' AND c9.property = p.id GROUP BY c9.property), (SELECT count(c10.id) FROM App\Entity\Clean c10 WHERE monthname(c10.scheduled) = 'October' AND c10.property = p.id GROUP BY c10.property), (SELECT count(c11.id) FROM App\Entity\Clean c11 WHERE monthname(c11.scheduled) = 'November' AND c11.property = p.id GROUP BY c11.property), (SELECT count(c12.id) FROM App\Entity\Clean c12 WHERE monthname(c12.scheduled) = 'December' AND c12.property = p.id GROUP BY c12.property)")
            ->getQuery()
            ->getResult()
        ;
        $colors = ['blueviolet', 'indianred', 'cornflowerblue', 'darkmagenta', 'darkolivegreen', 'cornsilk', 'gold', 'ghostwhite', 'indianred', 'honeydew', 'lavender', 'aliceblue'];
        $i = 0;
        foreach ($chartData as $data) {
            $dataset = [
                'label' => $data["title"],
                'backgroundColor' => $colors[$i],
                'borderColor' => $colors[$i],
                'borderWidth' => 1,
                'data' => [$data[1]?$data[1]:0, $data[3]?$data[3]:0, $data[5]?$data[5]:0, $data[7]?$data[7]:0, $data[9]?$data[9]:0, $data[11]?$data[11]:0, $data[13]?$data[13]:0, $data[15]?$data[15]:0, $data[17]?$data[17]:0, $data[19]?$data[19]:0, $data[21]?$data[21]:0, $data[23]?$data[23]:0],
            ];
            $datasets[] = $dataset;
            $i++;
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => ['Januray', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'datasets' => $datasets,
        ]);
        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                ],
            ],
        ]);
        return $this->render('default/home.html.twig', [
            'today' => $today,
            'todaysappointments' => $todaysappointments,
            'datasets' => $datasets,
            'chart' => $chart,
        ]);
    }

    #[Route('/home_housekeeper/{cleanid}', name: 'app_home_housekeeper', defaults: ["cleanid" => null], methods: ['GET', 'POST'])]
    public function home_housekeeper($cleanid, Request $request, CleanRepository $cleanRepository, CleanHousekeeperRepository $cleanHousekeeperRepository, CleanPhotoRepository $cleanPhotoRepository) 
    {
        $user = $this->getUser();
        $currentdate = new DateTime(date("Y-m-d"));
        if (!is_null($cleanid)) {
            $clean = $cleanRepository->find($cleanid);
        } else {
            $clean = null;
        }
        if (is_null($cleanid)) {
            $nextappointments = $cleanHousekeeperRepository->createQueryBuilder('ch')
                ->leftJoin('ch.housekeeper', 'h')
                ->leftJoin('ch.clean', 'c')
                ->andWhere('h.user = :user')
                ->andWhere('c.scheduled >= :currentdate')
                ->orderBy('c.scheduled', 'ASC')
                ->setParameter('currentdate', $currentdate)
                ->setParameter('user', $user)
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        } else {
            $nextappointments = $cleanHousekeeperRepository->createQueryBuilder('ch')
                ->leftJoin('ch.housekeeper', 'h')
                ->leftJoin('ch.clean', 'c')
                ->andWhere('h.user = :user')
                ->andWhere('c.id = :cleanid')
                ->setParameter('user', $user)
                ->setParameter('cleanid', $cleanid)
                ->getQuery()
                ->getResult()
            ;
            if (is_null($nextappointments) || empty($nextappointments)) {
                $this->addFlash('danger', 'You are not assigned to this appointment. Did you scan the correct label? Please try again.');
            } else {
                $today = new DateTime();
                $clean = $cleanRepository->find($cleanid);
                $clean->setSuppliesClaimed($today);
                $cleanRepository->save($clean, true);
                $nextappointments = $cleanHousekeeperRepository->createQueryBuilder('ch')
                    ->leftJoin('ch.housekeeper', 'h')
                    ->leftJoin('ch.clean', 'c')
                    ->andWhere('h.user = :user')
                    ->andWhere('c.id = :cleanid')
                    ->setParameter('user', $user)
                    ->setParameter('cleanid', $cleanid)
                    ->getQuery()
                    ->getResult()
                ;
            }
        }
        $positions = [];
        foreach ($nextappointments as $appointment) {
            $positions[] = ['title' => $appointment->getClean()->getProperty()->getTitle(), 'location' => ['lat' => (float) $appointment->getClean()->getProperty()->getLatitude(), 'lng' => (float) $appointment->getClean()->getProperty()->getLongitude()]];
        }
        $cleanPhoto = new CleanPhoto();
        $form = $this->createForm(CleanPhotoType::class, $cleanPhoto);
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
            $cleanPhoto->setTitle($form->get('title')->getData());
            $cleanPhoto->setClean($clean);
            $cleanPhoto->setUrl('/uploads/images/'.$newFilename);
            $cleanPhotoRepository->save($cleanPhoto, true);
        }
        return $this->render('default/home_housekeeper.html.twig', [
            'nextappointments' => $nextappointments,
            'positions' => json_encode($positions),
            'form' => $form,
        ]);
    }
}
