<?php

namespace App\Controller;

use App\Entity\Housekeeper;
use App\Entity\User;
use App\Form\HousekeeperType;
use App\Repository\HousekeeperRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/housekeeper')]
class HousekeeperController extends AbstractController
{
    #[Route('/', name: 'app_housekeeper_index', methods: ['GET'])]
    public function index(Request $request, HousekeeperRepository $housekeeperRepository): Response
    {
        $filters = [];
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'last_name';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $includeinactive = ($request->query->has('includeinactive')) ? $request->query->get('includeinactive') : 0;
        if (!$includeinactive) {
            $filters = ['active' => 1];
        }
        $housekeepers = $housekeeperRepository->findBy($filters, [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $housekeeperscount = count($housekeeperRepository->findBy($filters));
        $numberpages = (count($housekeepers) > 0) ? ceil(count($housekeeperRepository->findAll())/$recordsperpage) : 1;
        return $this->render('housekeeper/index.html.twig', [
            'housekeepers' => $housekeepers,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => $housekeeperscount,
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
            'includeinactive' => $includeinactive,
        ]);
    }

    #[Route('/new', name: 'app_housekeeper_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HousekeeperRepository $housekeeperRepository, UserRepository $userRepository): Response
    {
        $housekeeper = new Housekeeper();
        $form = $this->createForm(HousekeeperType::class, $housekeeper)
            ->add('email', EmailType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Password',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
            ])
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $i9front = $form->get('i9front')->getData();
            $i9back = $form->get('i9back')->getData();
            $idfront = $form->get('idfront')->getData();
            $idback = $form->get('idback')->getData();
            $email = $form->get('email')->getData();
            $password = password_hash($form->get('password')->getData(), PASSWORD_BCRYPT);
            if ($i9front) {
                $originalFilename = pathinfo($i9front->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$i9front->guessExtension();
                try {
                    $i9front->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            if ($i9back) {
                $originalFilename = pathinfo($i9back->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$i9back->guessExtension();
                try {
                    $i9back->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            if ($idfront) {
                $frontidoriginalFilename = pathinfo($idfront->getClientOriginalName(), PATHINFO_FILENAME);
                $frontidsafeFilename = $this->slugger->slug($frontidoriginalFilename);
                $frontidnewFilename = $frontidsafeFilename.'-'.uniqid().'.'.$idfront->guessExtension();
                try {
                    $idfront->move(
                        $this->getParameter('images_directory'),
                        $frontidnewFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            if ($idback) {
                $backidoriginalFilename = pathinfo($idback->getClientOriginalName(), PATHINFO_FILENAME);
                $backidsafeFilename = $this->slugger->slug($backidoriginalFilename);
                $backidnewFilename = $backidsafeFilename.'-'.uniqid().'.'.$idback->guessExtension();
                try {
                    $idback->move(
                        $this->getParameter('images_directory'),
                        $backidnewFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            $housekeeper->setINineFront('/uploads/images/'.$frontnewFilename);
            $housekeeper->setINineBack('/uploads/images/'.$backnewFilename);
            $housekeeper->setIdFront('/uploads/images/'.$frontidnewFilename);
            $housekeeper->setIdBack('/uploads/images/'.$backidnewFilename);
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setRoles(["ROLE_USER", "ROLE_HOUSEKEEPER"]);
            $userRepository->save($user, true);
            $housekeeper->setUser($user);
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
            $i9front = $form->get('i9front')->getData();
            $i9back = $form->get('i9back')->getData();
            $idfront = $form->get('idfront')->getData();
            $idback = $form->get('idback')->getData();
            if ($i9front) {
                $frontoriginalFilename = pathinfo($i9front->getClientOriginalName(), PATHINFO_FILENAME);
                $frontsafeFilename = $this->slugger->slug($frontoriginalFilename);
                $frontnewFilename = $frontsafeFilename.'-'.uniqid().'.'.$i9front->guessExtension();
                try {
                    $i9front->move(
                        $this->getParameter('images_directory'),
                        $frontnewFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            if ($i9back) {
                $backoriginalFilename = pathinfo($i9back->getClientOriginalName(), PATHINFO_FILENAME);
                $backsafeFilename = $this->slugger->slug($backoriginalFilename);
                $backnewFilename = $backsafeFilename.'-'.uniqid().'.'.$i9back->guessExtension();
                try {
                    $i9back->move(
                        $this->getParameter('images_directory'),
                        $backnewFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            if ($idfront) {
                $frontidoriginalFilename = pathinfo($idfront->getClientOriginalName(), PATHINFO_FILENAME);
                $frontidsafeFilename = $this->slugger->slug($frontidoriginalFilename);
                $frontidnewFilename = $frontidsafeFilename.'-'.uniqid().'.'.$idfront->guessExtension();
                try {
                    $idfront->move(
                        $this->getParameter('images_directory'),
                        $frontidnewFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            if ($idback) {
                $backidoriginalFilename = pathinfo($idback->getClientOriginalName(), PATHINFO_FILENAME);
                $backidsafeFilename = $this->slugger->slug($backidoriginalFilename);
                $backidnewFilename = $backidsafeFilename.'-'.uniqid().'.'.$idback->guessExtension();
                try {
                    $idback->move(
                        $this->getParameter('images_directory'),
                        $backidnewFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Error uploading image.');
                }
            }
            $housekeeper->setINineFront('/uploads/images/'.$frontnewFilename);
            $housekeeper->setINineBack('/uploads/images/'.$backnewFilename);
            $housekeeper->setIdFront('/uploads/images/'.$frontidnewFilename);
            $housekeeper->setIdBack('/uploads/images/'.$backidnewFilename);
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
