<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Property;
use App\Entity\User;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Repository\PropertyRepository;
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
#[Route('/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_customer_index', methods: ['GET'])]
    public function index(Request $request, CustomerRepository $customerRepository): Response
    {
        $filters = [];
        $sortfield = ($request->query->has('sortfield')) ? $request->query->get('sortfield') : 'company';
        $sortdirection = ($request->query->has('sortdirection')) ? $request->query->get('sortdirection') : 'ASC';
        $currentpage = ($request->query->has('currentpage')) ? (int) $request->query->get('currentpage') : 1;
        $recordsperpage = ($request->query->has('recordsperpage')) ? (int) $request->query->get('recordsperpage') : 10;
        $includeinactive = ($request->query->has('includeinactive')) ? $request->query->get('includeinactive') : 0;
        if (!$includeinactive) {
            $filters = ['active' => 1];
        }
        $customers = $customerRepository->findBy($filters, [$sortfield => $sortdirection], $recordsperpage, ($currentpage-1)*$recordsperpage);
        $customersscount = count($customerRepository->findBy($filters));
        $numberpages = (count($customers) > 0) ? ceil(count($customerRepository->findAll())/$recordsperpage) : 1;
        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
            'numberpages' => $numberpages,
            'currentpage' => $currentpage,
            'total' => $customersscount,
            'recordsperpage' => $recordsperpage,
            'sortfield' => $sortfield,
            'sortdirection' => $sortdirection,
            'includeinactive' => $includeinactive,
        ]);
    }

    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CustomerRepository $customerRepository, PropertyRepository $propertyRepository, UserRepository $userRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer)
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
            ->add('properties', EntityType::class, [
                'class' => Property::class,
                'query_builder' => function (PropertyRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->andWhere('p.customer IS NULL')
                        ->orderBy('p.title', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'Hold ctrl and click to select multiple properties.'
            ])
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customerproperties = $form->get('properties')->getData();
            $email = $form->get('email')->getData();
            $password = password_hash($form->get('password')->getData(), PASSWORD_BCRYPT);
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setRoles(["ROLE_USER", "ROLE_CUSTOMER"]);
            $userRepository->save($user, true);
            $customer->setUser($user);
            $customerRepository->save($customer, true);
            if ($customerproperties) {
                foreach ($customerproperties as $property) {
                    $property->setCustomer($customer);
                    $propertyRepository->save($customer, true);
                }
            }
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
    public function edit(Request $request, Customer $customer, CustomerRepository $customerRepository, PropertyRepository $propertyRepository): Response
    {
        $form = $this->createForm(CustomerType::class, $customer)
            ->add('properties', EntityType::class, [
                'class' => Property::class,
                'query_builder' => function (PropertyRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'Hold ctrl and click to select multiple properties.'
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customerproperties = $form->get('properties')->getData();
            $customerRepository->save($customer, true);
            foreach ($customer->getProperties() as $customerproperty) {
                $customerproperty->setCustomer(null);
                $propertyRepository->save($customerproperty, true);
            }
            if ($customerproperties) {
                foreach ($customerproperties as $property) {
                    $property->setCustomer($customer);
                    $propertyRepository->save($property, true);
                }
            }
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
