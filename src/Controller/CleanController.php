<?php

namespace App\Controller;

use DateTime;
use App\Entity\Clean;
use App\Entity\CleanHousekeeper;
use App\Entity\CleanLinen;
use App\Entity\CleanPhoto;
use App\Entity\CleanSupply;
use App\Entity\Linen;
use App\Entity\Supply;
use App\Form\CleanType;
use App\Form\CleanLinenType;
use App\Form\CleanPhotoType;
use App\Form\CleanSupplyType;
use App\Repository\CleanRepository;
use App\Repository\CleanHousekeeperRepository;
use App\Repository\CleanLinenRepository;
use App\Repository\CleanPhotoRepository;
use App\Repository\CleanSupplyRepository;
use App\Repository\LinenRepository;
use App\Repository\SupplyRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_HOUSEKEEPER')]
#[Route('/clean')]
class CleanController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger, private CleanPhotoRepository $cleanPhotoRepository, private CleanHousekeeperRepository $cleanHousekeeperRepository, private CleanLinenRepository $cleanLinenRepository, private CleanSupplyRepository $cleanSupplyRepository) {}

    #[Route('/', name: 'app_clean_index', methods: ['GET'])]
    public function index(Request $request, CleanRepository $cleanRepository): Response
    {
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'scheduled';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $cleans = $cleanRepository->findBy([], [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $numberpages = (count($cleans) > 0) ? ceil(count($cleanRepository->findAll())/$recordsperpage) : 1;
        return $this->render('clean/index.html.twig', [
            'cleans' => $cleans,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => count($cleanRepository->findAll()),
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
        ]);
    }

    #[Route('/new', name: 'app_clean_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CleanRepository $cleanRepository): Response
    {
        $clean = new Clean();
        $form = $this->createForm(CleanType::class, $clean);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleanRepository->save($clean, true);
            $cleanhousekeepers = $form->get('housekeepers')->getData();
            if ($cleanhousekeepers) {
                foreach ($cleanhousekeepers as $housekeeper) {
                    $cleanhousekeeper = new CleanHousekeeper();
                    $cleanhousekeeper->setHousekeeper($housekeeper);
                    $cleanhousekeeper->setClean($clean);
                    $this->cleanHousekeeperRepository->save($cleanhousekeeper, true);
                }
            }

            return $this->redirectToRoute('app_clean_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clean/new.html.twig', [
            'clean' => $clean,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Clean $clean, LinenRepository $linenRepository, SupplyRepository $supplyRepository): Response
    {
        $cleanPhoto = new CleanPhoto();
        $cleanLinen = new CleanLinen();
        $cleanSupply = new CleanSupply();
        $form = $this->createForm(CleanPhotoType::class, $cleanPhoto);
        $cleanlinenform = $this->createForm(CleanLinenType::class, $cleanLinen);
        $cleansupplyform = $this->createForm(CleanSupplyType::class, $cleanSupply);
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
            $this->cleanPhotoRepository->save($cleanPhoto, true);
        }
        $cleanlinenform->handleRequest($request);
        if ($cleanlinenform->isSubmitted() && $cleanlinenform->isValid()){
            $cleanLinen->setClean($clean);
            $linen = $cleanLinen->getLinen();
            $linen->setUnits($linen->getUnits()-$cleanLinen->getUnits());
            $this->cleanLinenRepository->save($cleanLinen, true);
            $linenRepository->save($linen, true);
        }
        $cleansupplyform->handleRequest($request);
        if ($cleansupplyform->isSubmitted() && $cleansupplyform->isValid()) {
            $cleanSupply->setClean($clean);
            $supply = $cleanSupply->getSupply();
            $supply->setUnits($supply->getUnits()-$cleanSupply->getUnits());
            $this->cleanSupplyRepository->save($cleanSupply, true);
            $supplyRepository->save($supply, true);
        }
        $qrcode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data('http://clean/home_housekeeper/'.$clean->getId())
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->logoPath($this->getParameter('images_directory').'/SurfsideWebElement450.png')
            ->logoResizeToWidth(50)
            ->logoPunchoutBackground(true)
            ->labelText($clean->getProperty().' '.$clean->getScheduled()->format('Y-m-d h:i a'))
            ->labelFont(new NotoSans(8))
            ->labelAlignment(new LabelAlignmentCenter())
            ->validateResult(false)
            ->build()
        ;
        $qrcodepath = $this->getParameter('images_directory').'/'.$clean->getId().'-qrcode.png';
        $qrcode->saveToFile($qrcodepath);
        return $this->render('clean/show.html.twig', [
            'clean' => $clean,
            'form' => $form,
            'cleanlinenform' => $cleanlinenform,
            'cleansupplyform' => $cleansupplyform,
            'qrcodepath' => substr($qrcodepath, 35),  
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clean_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clean $clean, CleanRepository $cleanRepository): Response
    {
        $form = $this->createForm(CleanType::class, $clean);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cleanRepository->save($clean, true);
            return $this->redirectToRoute('app_clean_show', ['id' => $clean->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('clean/edit.html.twig', [
            'clean' => $clean,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clean_delete', methods: ['POST'])]
    public function delete(Request $request, Clean $clean, CleanRepository $cleanRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clean->getId(), $request->request->get('_token'))) {
            $cleanRepository->remove($clean, true);
        }

        return $this->redirectToRoute('app_clean_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{cleanid}/start', name: 'app_clean_start', methods: ['GET', 'POST'])]
    public function start(Request $request, $cleanid, CleanRepository $cleanRepository): Response
    {
        $clean = $cleanRepository->find($cleanid);
        $clean->setStart(new DateTime());
        $cleanRepository->save($clean, true);
        return $this->redirectToRoute('app_home_housekeeper', ['cleanid' => $cleanid]); 
    }

    #[Route('/{cleanid}/end', name: 'app_clean_end', methods: ['GET', 'POST'])]
    public function end(Request $request, $cleanid, CleanRepository $cleanRepository): Response
    {
        $clean = $cleanRepository->find($cleanid);
        $clean->setEnd(new DateTime());
        $cleanRepository->save($clean, true);
        return $this->redirectToRoute('app_home_housekeeper', ['cleanid' => $cleanid]); 
    }
}
