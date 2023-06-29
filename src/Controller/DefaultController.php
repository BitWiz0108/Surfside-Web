<?php

namespace App\Controller;

use DateTime;
use App\Entity\Clean;
use App\Repository\CleanRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
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
        // $jantotal = $febtotal = $martotal = $aprtotal = $maytotal = $juntotal = $jultotal = $augtotal = $septtotal = $octtotal = $novtotal = $dectotal = 0;
        foreach ($chartData as $data) {
            // $jantotal += $data[1]?$data[1]:0;
            // $febtotal += $data[3]?$data[3]:0;
            // $martotal += $data[5]?$data[5]:0;
            // $aprtotal += $data[7]?$data[7]:0;
            // $maytotal += $data[9]?$data[9]:0;
            // $juntotal += $data[11]?$data[11]:0;
            // $jultotal += $data[13]?$data[13]:0;
            // $augtotal += $data[15]?$data[15]:0;
            // $septtotal += $data[17]?$data[17]:0;
            // $octtotal += $data[19]?$data[19]:0;
            // $novtotal += $data[21]?$data[21]:0;
            // $dectotal += $data[23]?$data[23]:0;
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
        // $dataset = [
        //     'label' => 'Total',
        //     'backgroundColor' => $colors[$i],
        //     'borderColor' => $colors[$i],
        //     'borderWidth' => 1,
        //     'data' => [$jantotal, $febtotal, $martotal, $aprtotal, $maytotal, $juntotal, $jultotal, $augtotal, $septtotal, $octtotal, $novtotal, $dectotal],
        // ];
        // $datasets[] = $dataset;
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
}
