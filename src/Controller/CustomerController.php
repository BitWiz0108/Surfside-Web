<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_customer_index', methods: ['GET'])]
    public function index(Request $request, CustomerRepository $customerRepository): Response
    {
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'company';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $customers = $customerRepository->findBy([], [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $numberpages = (count($customers) > 0) ? ceil(count($customerRepository->findAll())/$recordsperpage) : 1;
        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => count($customerRepository->findAll()),
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
        ]);
    }

    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CustomerRepository $customerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer)
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
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRepository->save($customer, true);

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRepository->save($customer, true);

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $customerRepository->remove($customer, true);
        }

        return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
